<?php
  /**
   * Download files.
   *
   * Download files from AWS to private file system.
   */
  public function downloadFiles() {
    // Logic to download the files in each directory in AWS
    foreach ($dirs as $dir) {
      $source = "{$bucket_path}/{$dir}";
      $destination = "{$dest}/{$dir}";

      // We use AWS SDK for PHP to transfer the files.
      $this->transferFiles($source, $destination, $dir);

      // Start event to validate files data.
      $this->fireEvent($dir, MigrateDataEvent::MIGRATE_VALIDATE);
    }
  }

  // Override getSubscribedEvents of Event API.
  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      MigrateDataEvent::MIGRATE_VALIDATE => ['validateFilesData'],
    ];
  }

  /**
   * The data validation function for migration.
   *
   */
  public function validateFilesData(MigrateDataEvent $event) {
    // Check pre-conditions are satisfied to validate the files
    if ($conditionStatisfied) {
      // This will in turn call the code in MainServiceClass
      $validation_status = $this->csvValidator->readCsvFile();
      $sendEmail = $validation_status ? 'validation_success' : 'validation_errors';
    }

    if ($sendEmail) {
      // Call Drupal's mail manager and send email.
    }
  }
