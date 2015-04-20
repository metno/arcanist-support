<?php

final class ArcanistScalastyleLinter extends ArcanistExternalLinter {

  private $jarPath = null;
  private $configPath = null;

  public function getInfoURI() {
    return 'http://www.scalastyle.org/';
  }

  public function getInfoDescription() {
    return 'Scalastyle linter for Scala code';
  }

  public function getLinterName() {
    return 'scalastyle';
  }

  public function getLinterConfigurationName() {
    return 'scalastyle';
  }

  public function getDefaultBinary() {
    return 'scalastyle';
  }

  public function getInstallInstructions() {
    return 'Install debian package scalastyle-0.6, or get it from https://github.com/metno/scalastyle';
  }

  public function shouldExpectCommandErrors() {
    return true;
  }

  protected function getMandatoryFlags() {
    if ($this->configPath === null) {
      throw new ArcanistUsageException(
        pht('Scalastyle config XML path must be configured.'));
    }

    return array(
      '--config', $this->configPath,
      '--quiet', 'true');
  }

  protected function getDefaultFlags() {
    return array();
  }

  protected function parseLinterOutput($path, $err, $stdout, $stderr) {

    $messages = array();

    $output = trim($stdout);
    if (strlen($output) === 0) {
      return $messages;
    }

    $lines = explode(PHP_EOL, $output);

    foreach ($lines as $line) {
      $lintMessage = id(new ArcanistLintMessage())
        ->setPath($path)
        ->setCode($this->getLinterName());

      $matches = array();
      if (preg_match('/^([a-z]+)/', $line, $matches)) {
        switch ($matches[1]) {
          case 'warning':
            $lintMessage->setSeverity(ArcanistLintSeverity::SEVERITY_WARNING);
            break;
          case 'error':
            $lintMessage->setSeverity(ArcanistLintSeverity::SEVERITY_ERROR);
            break;
        }
      }

      $matches = array();
      if (preg_match('/message=([^=]+ )/', $line, $matches)) {
        $lintMessage->setDescription(trim($matches[1]));
      } else if (preg_match('/message=([^=]+$)/', $line, $matches)) {
        $lintMessage->setDescription(trim($matches[1]));
      }

      $matches = array();
      if (preg_match('/line=([^=]+ )/', $line, $matches)) {
        $lintMessage->setLine(trim($matches[1]));
      } else if (preg_match('/line=([^=]+$)/', $line, $matches)) {
        $lintMessage->setLine(trim($matches[1]));
      }

      $matches = array();
      if (preg_match('/column=([^=]+ )/', $line, $matches)) {
        $lintMessage->setChar(trim($matches[1]));
      } else if (preg_match('/column=([^=]+$)/', $line, $matches)) {
        $lintMessage->setChar(trim($matches[1]));
      }

      $messages[] = $lintMessage;
    }

    return $messages;
  }

  public function getLinterConfigurationOptions() {
    $options = array(
      'config' => array(
        'type' => 'optional string | list<string>',
        'help' => pht(
          'Specify a string (or list of strings) identifying the Scalastyle '.
          'config XML file.')
      ),
    );

    return $options + parent::getLinterConfigurationOptions();
  }

  public function setLinterConfigurationValue($key, $value) {
    switch ($key) {
      case 'config':
        $working_copy = $this->getEngine()->getWorkingCopy();
        $root = $working_copy->getProjectRoot();

        foreach ((array)$value as $path) {
          if (Filesystem::pathExists($path)) {
            $this->configPath = $path;
            return;
          }

          $path = Filesystem::resolvePath($path, $root);

          if (Filesystem::pathExists($path)) {
            $this->configPath = $path;
            return;
          }
        }

        throw new ArcanistUsageException(
            pht('None of the configured Scalastyle configs can be located. If you have the met.no\'s scalastyle package installed, you may change scalastyle config to /etc/scalastyle-config.xml in .arclint')
        );
    }

    return parent::setLinterConfigurationValue($key, $value);
  }

}
