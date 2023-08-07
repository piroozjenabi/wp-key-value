#!/usr/bin/env php

<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

define('ABSPATH', 'consolemode');
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/console/utils.php';


/**
 * check only cli mode
 */
if (php_sapi_name() !== 'cli') {
    echo ('Access Denied');
    exit;
}

if (!DEVELOP) {
    echo ("turn of develop mode");
    exit;
}

/**
 * start console
 */
echo "Welcome to Console [ php console help ] for more info \r\n";

$command = $argv[1]??'help';
$help = "---- make:model --> make model \r\n";
$help .= "---- make:view --> make view for show in admin \r\n";
$help .= "---- make:shortcode --> make short code  \r\n";
$help .= "---- make:api --> make api for   \r\n";
$help .= "---- make:migration --> backup database \r\n";


switch ($command) {
    case 'help':
    case  null:
        echo $help;
        break;
    case "make:model":
        echo "enter name of model:";
        $name = fgets(STDIN);
        $nameObj = namer($name);

        consoleMaker(
            __DIR__ . '/model/.sample.php', 
            __DIR__ . "/model/{$nameObj['camel']}.php",
            [
                '__NAME_DB__' => $nameObj['snake'],
                '__NAME__' => $nameObj['camel']
            ]
        );

        echo "model = {$nameObj['camel']},data base = {$nameObj['camel']} , please complete migration";

    break;

    case "make:page":
        echo "enter name of page:";
        $page = fgets(STDIN);
        $pageObj = namer($page);

        consoleMaker(
            __DIR__ . '/pages/.sample.php',
            __DIR__ . "/pages/{$pageObj['camel']}.php",
            [
                '__NAME_DB__' => $pageObj['snake'],
                '__NAME__' => $pageObj['camel']
            ]
        );

        echo "page = {$pageObj['camel']},please complete form";

        break;    
    default:
        echo "command not found for more info type help\r\n";
        break;    
}

