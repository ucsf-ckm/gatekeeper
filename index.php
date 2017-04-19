<?php
/**
 * Entry script to this application.
 */

require_once __DIR__ . '/lib.php';

$whitelist = getenv('WHITELIST');
$destination_pass = getenv('DESTINATION_PASS');
$destination_fail = getenv('DESTINATION_FAIL');

// run the damned thing.
main($whitelist, $destination_pass, $destination_fail);
