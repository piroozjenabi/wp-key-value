<?php
defined('ABSPATH') || exit;

// create database

  $table_name = $wpdb->prefix . "key_val";
  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `val` VARCHAR(250) NOT NULL ,
  `key_id` INT NOT NULL ,
  `created_by` INT NULL ,
  `created_type` VARCHAR(10) NULL ,
  `created_at` DATETIME NOT NULL DEFAULT NOW() ,
  PRIMARY KEY (id)
  ) $charset_collate;";
  dbDelta( $sql );

  $table_name = $wpdb->prefix . "key_val_keys";
  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NOT NULL ,
  `title` VARCHAR(50) NOT NULL ,
  `api_key` VARCHAR(150) NULL ,
  `params` Text NULL ,
  `created_at` DATETIME NOT NULL DEFAULT NOW() ,
  PRIMARY KEY (id)
  ) $charset_collate;";
  dbDelta($sql);
  $results = $wpdb->get_results($wpdb->prepare("select * from $table_name"));

  if (empty($results)) {
    $sql = "INSERT INTO $table_name (name, title) values ('default','default') ";
    dbDelta($sql);

  }

  //type = TEXT, NUMBER
  $table_name = $wpdb->prefix . "key_val_tags";
  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NOT NULL ,
  `title` VARCHAR(50) NOT NULL ,
  `type` VARCHAR(50) NOT NULL ,
  `default_value` VARCHAR(255) NOT NULL ,
  `class` VARCHAR(255) NULL ,
  `style` TEXT NULL ,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `readonly` TINYINT(1) NOT NULL DEFAULT 1,
  `validation` TEXT NULL,
  `params` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT NOW() ,
  PRIMARY KEY (id)
  ) $charset_collate;";
  dbDelta($sql);



  $table_name = $wpdb->prefix . "key_val_tag_value";
  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `tag_id` INT NOT NULL ,
  `key_val_id` INT NOT NULL ,
  `value` VARCHAR(255) NOT NULL ,
  `created_at` DATETIME NOT NULL DEFAULT NOW() ,
  PRIMARY KEY (id)
  ) $charset_collate;";
  dbDelta($sql);



?>