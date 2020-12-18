<?php
  // Below snippet in main class checks if the csv file is having columns 
  // that needs to be checked for referential integrity.
  // In these cases we store the values in temp table from a temp store.
  if ($csvname == 'filename') {
    $primary_ids = explode(',', $store->get($csvname));
    $this->addToTempTable('temp_table', 'column_name', $column_value);
  }
  
  // Later we check in below function if the IDs are present in temp table
  // or in Drupal database.
  /**
   * Checks if the id in csv file exists in the temp table or in Drupal.
   *
   * @param string $rowValue
   *   The value to be checked.
   * @param string $csvname
   *   The filename.
   * @param int $index
   *   The index of the row.
   *
   * @return array
   *   Error message if id is not found either in Drupal or in the temp table.
   *
   */
  protected function checkRefIntegrity(string $rowValue, string $csvname, int $index) {
    $match = $this->retrieveFromTempTable('temp_table', 'column_name', $rowValue);
    if ($match < 1) {
      // We then query the database to see if the data with matching id
      // exists there.
      $entity = $this->retrieveFromNodeEntities($rowValue, 'entity_name', 'entity_field');
      if ($entity < 1) {
        // The id is not in the Drupal database from previous imports
        // or in the CSV file.
        $errorMsg = "Id $rowValue not found in $csvname or Drupal database.";
        return $this->constructErrorMessage($csvname, $index, $errorMsg);
      }
    }
  }
