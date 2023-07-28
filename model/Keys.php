<?php
defined('ABSPATH') || exit;

require_once __DIR__.'/Model.php';
class Keys extends Model {

    function __construct()
    {
        parent::__construct('key_val_keys');   
    }
    
}