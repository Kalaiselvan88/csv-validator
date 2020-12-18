<?php
  /**
   * Generic helper method to construct the error log values.
   *
   * @param array $data_array
   *   The data of the csv file to be validated.
   * @param string $csvname
   *   The CSV file name.
   * @param int $index
   *   The index value of the row being processed.
   *
   * @return mixed
   *   An array of error messages with filename and line no.
   */
  protected function constructErrorLog(array $data_array, $csvname, int $index) {
    $error_log = [];
    $error_message = $this->validateConstraints($data_array);
    if (!empty($error_message)) {
      foreach ($error_message as $error) {
        $error_log[] = $this->constructErrorMessage($csvname, $index, $error);
      }
      return $error_log;
    }
  }


  /**
   * Constructs the error message array from the given data.
   *
   * @param string $csvname
   *   The file name.
   * @param int $index
   *   The row no.
   * @param string $error_message
   *   The error message.
   *
   * @return array
   *   The constructed error message.
   */
  protected function constructErrorMessage(string $csvname, int $index, string $error_message) {
    // Incrementing the row no by one as we are not considering the header
    // when showing the log messages.
    $index++;
    return [
      'file' => $csvname,
      'rowno' => $index,
      'error' => $error_message,
    ];
  }
