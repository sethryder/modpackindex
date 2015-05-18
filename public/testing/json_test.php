<?php

$raw_json = file_get_contents('Addons-Complete-432.json');
$json = json_decode($raw_json);

print_r($json);