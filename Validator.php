<?php
class Validator {
  /**
    * Validates 1 CSV file.
    *
    * @param string $csvname
    *   The csv file name value.
    * @param int $index
    *   The index of the $row.
    * @param array $row
    *   The data in the row.
    *
    */
    public function validateFileName($csvname, $index, array $row) {
      // The below array will map each constraint in constraint class to every column.
      $data_array = [
        'column1' => [$row[0], 'NB', 'column1Constraint'],
        'column2' => [$row[1], 'NB', 'column2Constraint'],
        'column3' => [$row[2], 'NB', 'column3Constraint'],
      ];
      // Call to the Error Log class to generate errors and add it to custom table.
      $errors = $this->errorLogClass($data_array, $csvname, $index);

      // Call to function to check referential integrity checks for a particular column (here 3rd)
      $referential_errors = $this->checkRefIntegrity($row[2], $csvname, $index);

      // Merge all the errors for this file and return
      $totalErrors = $this->mergeErrors($errors, $referential_errors);
      return $totalErrors;
    }
  }
