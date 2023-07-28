<?php
defined('ABSPATH') || exit;

require_once __DIR__.'/Model.php';
class Tags extends Model {

    function __construct()
    {
        parent::__construct('key_val_tags');   
    }

}