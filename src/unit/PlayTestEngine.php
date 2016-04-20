<?php

final class PlayTestEngine extends ArcanistUnitTestEngine {

  protected $projectRoot;

  protected $reportDirs;

  protected function loadEnvironment() {
    $this->projectRoot = $this->getWorkingCopy()->getProjectRoot();
    $this->reportDirs = $this->getConfigurationManager()->getConfigFromAnySource('unit.engine.sbt.report-dirs');

    if (is_null($this->reportDirs)) {
      $this->reportDirs = array();
    }

    if (count($this->reportDirs) == 0) {
      $this->reportDirs[] = $this->projectRoot.'/target/test-reports';
    }
  }

  public function run() {
    $this->loadEnvironment();
    $future = new ExecFuture('activator test');
    do {
      list($stdout, $stderr) = $future->read();
      echo $stdout;
      echo $stderr;
      sleep(0.5);
    } while (!$future->isReady());
    list($error, $stdout, $stderr) = $future->resolve();
    return $this->parseJUnitFiles();
  }

  public function shouldEchoTestResults() {
    return true;
  }

  private function resultsContainFailures(array $results) {
    foreach ($results as $result) {
      if ($result->getResult() != ArcanistUnitTestResult::RESULT_PASS) {
        return true;
      }
    }
    return false;
  }

  private function parseJUnitFiles() {
    $xml_parser = new ArcanistXUnitTestResultParser();
    $results = array();
    $working_copy = $this->getWorkingCopy();

    foreach ($this->reportDirs as $dir) {
      foreach (Filesystem::listDirectory($dir) as $file) {
        if (!preg_match('|.*\.xml$|', $file)) {
          continue;
        }
        $file = $dir.DIRECTORY_SEPARATOR.$file;
        $result = $xml_parser->parseTestResults(Filesystem::readFile($file));

        // Only report test sets with failure.
        if ($this->resultsContainFailures($result)) {
          $results[] = $result;
        }
      }
    }
    return array_mergev($results);
  }
}
