<?php

/**
 * Function for not only printing the variable, but also file path and number of string of function call.
 * @param  mixed $data Some data.
 * @return void
 */
function pr($data): void
{
    $backtrace = debug_backtrace()[0];
    $output    = "{$backtrace['file']}:{$backtrace['line']}\n" . print_r($data, true) . "\n\n";
    if (PHP_SAPI != 'cli') {
        $output = '<pre style="background-color: lightgrey;">' . $output . '</pre><br><br>';
    }
    echo $output;
}

/**
 * Recursively trims array content
 * @param array $arr
 * @return array
 */
function trimArray(array $arr)
{
    foreach ($arr as &$value) {
        if (is_array($value)) {
            $value = trimArray($value);
        } else {
            $value = trim($value);
        }
    }
    return $arr;
}
