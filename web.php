<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
defined('ABSPATH') || exit;
################################# [ACTIONS] ################################# 

add_action("admin_menu", "init_menu");

function init_menu()
{
  add_menu_page(
    "Key Val",
    "Key Val",
    "manage_options",
    "manage_key_val",
    '',
    "dashicons-database"
  );
  add_submenu_page(
    "manage_key_val",
    "Manage values",
    "Manage values",
    "manage_options",
    "manage_key_val",
    function () {
      view('main');
    },
  );
  add_submenu_page(
    "manage_key_val",
    "Manage Key Types",
    "Manage Key Types",
    "manage_options",
    "manage_key_val_keys",
    function () {
      view('types');
    },
  );
  add_submenu_page(
    "manage_key_val",
    "Manage Tags",
    "Manage Tags",
    "manage_options",
    "manage_key_val_tags",
    function () {
      view('tags');
    }
  );
  add_submenu_page(
    "manage_key_val",
    "Settings",
    "Settings",
    "manage_options",
    "manage_key_val_settings",
    function () {
      view('setting');
    }
  );
  add_submenu_page(
    "manage_key_val",
    "Help",
    "Help",
    "manage_options",
    "manage_key_val_help",
    function () {
      view('help');
    }
  );
}

function init()
{
  // Load the settings API
  $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
  $this->init_settings(); // This is part of the settings API. Loads settings you previously init.
  // Save settings in admin if you have any defined
}
