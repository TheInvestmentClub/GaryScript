<?php

$fileName = "../renames-2021.csv";
if (($handle = fopen($fileName, 'r')) !== FALSE) { // Check the resource is valid
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { // Check opening the file is OK!
        print_r($data); // Array
    }
    fclose($handle);
}

?>
