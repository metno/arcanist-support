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

      $keys = preg_split("/[:]+/", $line);
      $lintMessage->setCode(trim($keys[0]));
      $lintMessage->setName("Foodcritic");
      $lintMessage->setPath(trim($keys[2]));
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
