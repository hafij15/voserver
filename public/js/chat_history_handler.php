<?php

$data = $_POST["data"];

$fileName = $data[1] . "_" . date("Y-m-d") . ".txt";
$myfile = fopen($fileName, "a") or die("Unable to open file!");
fwrite($myfile, "\n\n" . $data[0]);
fclose($myfile);
