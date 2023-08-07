<?php
defined('ABSPATH') || exit;

/**
 * make string for use in camel case
 *
 * @param string $in
 * @return Array
 */
function namer(String $in): array
{

    $word = preg_split('/[\s_]+/', $in);

    $lowercaseWords = array_map('strtolower', $word);

    $camelCaseWords = array_map(function ($w) {
        return ucfirst($w);
    }, $lowercaseWords);
    $db = array_map(function ($w) {
        return $w;
    }, $lowercaseWords);

    $camelCaseString = implode('', $camelCaseWords);
    $dbCaseString = implode('_', $db);

    return
        [
            'camel' => $camelCaseString,
            'snake' => substr($dbCaseString, 0, -1),
            // 'camelCase' => $camelCaseString //TODO add camel case 
        ];
}

/**
 * file make for console
 *
 * @param String $srcFile
 * @param String $destFile
 * @param array $params
 * @return void
 */
function consoleMaker(String $srcFile, String $destFile, array $params = [])
{

    $srcHandle = fopen($srcFile, 'r');
    $destHandle = fopen($destFile, 'w');
    while (($line = fgets($srcHandle)) !== false) {
        foreach ($params as $key => $value) {
            $line = str_replace($key, $value, $line);
        }
        fwrite($destHandle, $line);
    }
    fclose($srcHandle);
    fclose($destHandle);
}
