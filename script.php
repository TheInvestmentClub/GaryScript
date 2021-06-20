<?php

$fileName = "../renames-2021.csv";
$file = file_get_contents($fileName);
$lines = explode("\n", $file);
foreach($lines as $line) {
  [$oldFileName, $newFileName] = explode(",", $line);
  // Test 
  print "{$oldFileName} - {$newFileName}/n";
}

?>
