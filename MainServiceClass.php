<?php
/**
 * Validates the existence of all files, parses them and passes every row for validation.
 */
class MainServiceClass {
  /**
   * Reads the CSV file from the given path.
   *
   */
  public function readCsvFile($natoffice) {
    // Fetch every CSV file
    $csvFileList = $this->fetchCsvFileList();

    // File loop begins.
    foreach ($csvFileList as $csvname => $csvpath) {
      // League CSV file reader object
      $reader = $this->csvFileReader($csvpath);

      // Row loop begins.
      foreach ($reader as $index => $row) {
        $error = $this->csvRowIterator($csvname, $index, $row);
        if (!empty($error)) {
          $errors[] = $error;
        }
      }
    }

    if (!empty($errors)) {
      // Calls the error log class method.
      $this->logCsvErrors($errors);
      return FALSE;
    }

    // Trigger custom event indicating that migration should start.
    if (empty($errors)) {
      $event = new MigrateDataEvent();
      $this->eventDispatcher->dispatch(MigrateDataEvent::MIGRATE_START, $event);
      return TRUE;
    }
  }

  /**
   * Iterates through each row and selects the validator method.
   *
   * The validator method is based on the file name and are methods of
   * another class.
   *
   * @param string $csvname
   *   The filename.
   * @param mixed $index
   *   The index object.
   * @param array $row
   *   The row object.
   *
   * @return mixed
   *   The csvdata.
   */
  public function csvRowIterator(string $csvname, $index, array $row) {
    $errors = '';
    $method = 'validate' . $csvname;
    if (method_exists($this->validatorMethods, $method)) {
      $errors = $this->validatorMethods->$method($csvname, $index, $row);
    }
    if (!empty($errors)) {
      return $errors;
    }
  }
}
