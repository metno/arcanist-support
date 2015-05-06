<?php

final class ArcanistFoodcriticLinter extends ArcanistExternalLinter
{

  public function getDefaultBinary()
  {
    return 'foodcritic';
  }

  public function getInstallInstructions()
  {
    return 'See http://acrmp.github.io/foodcritic/';
  }

  public function shouldExpectCommandErrors()
  {
    return false;
  }

  protected function getMandatoryFlags()
  {
    // Excludes three rules that cause false positives with Arcanist, because
    // of the way arc applies the linter
    // - FC011  "Missing README in markdown format"
    // - FC031  "Cookbook without metadata file"
    // - FC045  "Consider setting cookbook name in metadata"
    return array(
      '-t ~FC011',
      '-t ~FC031',
      '-t ~FC045'
    );
  }

  protected function parseLinterOutput($path, $err, $stdout, $stderr)
  {
    $messages = array();

    $output = trim($stdout);

    if (strlen($output) === 0) {
      return $messages;
    }

    $lines = explode(PHP_EOL, $output);

    foreach ($lines as $line) {
      $lintMessage = id(new ArcanistLintMessage())->setPath($path)->setCode($this->getLinterName());
      // Everything gets set to warning as default
      $lintMessage->setSeverity(ArcanistLintSeverity::SEVERITY_WARNING);

      $keys = preg_split("/[:]+/", $line);
      $code = trim($keys[0]);
      $lintMessage->setCode($code);
      $lintMessage->setName("Foodcritic");
      $lintMessage->setDescription(trim($keys[1]));
      $path = trim($keys[2]);
      $lintMessage->setPath($path);
      $lintMessage->setLine(trim($keys[3]));

      $messages[] = $lintMessage;
    }

    return $messages;
  }

  public function getInfoURI()
  {
    return 'https://docs.chef.io/foodcritic.html';
  }

  public function getInfoDescription()
  {
    return 'Foodcritic lint tool for Opscode Chef cookbooks';
  }

  public function getLinterName()
  {
    return 'foodcritic';
  }

  public function getLinterConfigurationName()
  {
    return 'foodcritic';
  }
}
