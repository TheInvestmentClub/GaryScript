<?php

$fileName = "../renames-2021.csv";
$file = file_get_contents($fileName);
print $file;
$lines = explode("\n", $file);
print_r($lines);

?>
