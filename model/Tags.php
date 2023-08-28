<?php
defined('ABSPATH') || exit;

require_once __DIR__ . '/Model.php';
class Tags extends Model
{

    public $type = [
        ['key' => "text", 'value' => "Text Box"],
        ['key' => "textarea", 'value' => "Text Area Box"],
        ['key' => "number", 'value' => "Number Box"],
        ['key' => "url", 'value' => "Url Box"],
        ['key' => "email", 'value' => "Email Box"],
        ['key' => "color", 'value' => "Color Box"],
        ['key' => "hidden", 'value' => "hidden Box"],
        ['key' => "file", 'value' => "File input"],
        ['key' => "password", 'value' => "Password input"],
        ['key' => "range", 'value' => "Range input"],
        ['key' => "checkbox", 'value' => "Check Box"],
        ['key' => "date", 'value' => "Date Box"],
        ['key' => "select", 'value' => "Select Box"],
        ['key' => "wooid", 'value' => "Woocomerce Id"],
        ['key' => "formula", 'value' => "Formula Box"],
        ['key' => "api", 'value' => "api"],
        ['key' => "remote_keyval", 'value' => "remote key_val"],
        ['key' => "group_auto_number", 'value' => "group with auto increment number"],
    ];
    function __construct()
    {
        parent::__construct('key_val_tags');
    }


}
