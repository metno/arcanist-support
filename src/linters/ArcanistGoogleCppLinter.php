<?php

/**
 * Uses Google's `cpplint.py` to check code.
 */
final class ArcanistGoogleCppLinter extends ArcanistExternalLinter {

  private $max_line_length;
  private $header_guard_root_dir;

  public function getLinterName() {
    return 'C++ cppLint - working version';
  }

  public function getLinterConfigurationName() {
    return 'googlecpplint';
  }

  public function getDefaultBinary() {
    return 'cpplint.py';
  }

  public function getDefaultFlags() {
    $flags = array();
    if (isset($this->max_line_length)) {
      array_push($flags, '--linelength=' . $this->max_line_length);
    }
    if (isset($this->header_guard_root_dir)) {
      array_push($flags, '--root=' . $this->header_guard_root_dir);
    }
    return $flags;
  }

  public function getLinterConfigurationOptions() {
    $options = array(
      'cpplint.max_line_length' => array(
        'type' => 'optional int',
        'help' => pht('Maximum line length'),
      ),
      'cpplint.header_guard_root_dir' => array(
        'type' => 'optional string',
        'help' => pht('Root directory for determining correct define guards')
      )
    );

    return $options + parent::getLinterConfigurationOptions();
  }

  public function setLinterConfigurationValue($key, $value) {
    switch ($key) {
      case 'cpplint.max_line_length':
        $this->max_line_length = $value;
        return;

      case 'cpplint.header_guard_root_dir':
        $this->header_guard_root_dir = $value;
        return;

      default:
        return parent::setLinterConfigurationValue($key, $value);
    }
  }

  public function getInstallInstructions() {
    return pht(
      'Install cpplint.py using `%s`, and place it in your path with the '.
      'appropriate permissions set.',
      'wget https://raw.github.com'.
      '/google/styleguide/gh-pages/cpplint/cpplint.py');
  }

  protected function getDefaultMessageSeverity($code) {
    return ArcanistLintSeverity::SEVERITY_WARNING;
  }

  protected function parseLinterOutput($path, $err, $stdout, $stderr) {
    $lines = explode("\n", $stderr);

    $messages = array();
    foreach ($lines as $line) {
      $line = trim($line);
      $matches = null;
      $regex = '/(\d+):\s*(.*)\s*\[(.*)\] \[(\d+)\]$/';
      if (!preg_match($regex, $line, $matches)) {
        continue;
      }
      foreach ($matches as $key => $match) {
        $matches[$key] = trim($match);
      }

      $severity = $this->getLintMessageSeverity($matches[3]);

      $message = new ArcanistLintMessage();
      $message->setPath($path);
      $message->setLine($matches[1]);
      $message->setCode($matches[3]);
      $message->setName($matches[3]);
      $message->setDescription($matches[2]);
      $message->setSeverity($severity);

      $messages[] = $message;
    }

    return $messages;
  }

  protected function getLintCodeFromLinterConfigurationKey($code) {
    if (!preg_match('@^[a-z_]+/[a-z0-9_+]+$@', $code)) {
      throw new Exception(
        pht(
          'Unrecognized lint message code "%s". Expected a valid cpplint '.
          'lint code like "%s" or "%s".',
          $code,
          'build/include_order',
          'whitespace/braces'));
    }

    return $code;
  }

}
