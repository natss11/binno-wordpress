<?php
/*
Plugin Name: Startup Company Plugin
Description: Creates a table named startup_company.
Version: 1.0
Author: Marriane Natad
*/

// Activate the plugin
register_activation_hook(__FILE__, 'startup_company_activation');

function startup_company_activation() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'startup_company';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        coverpic varchar(255) NOT NULL,
        profilepic varchar(255) NOT NULL,
        startup_name varchar(255) NOT NULL,
        startup_address varchar(255) NOT NULL,
        startup_description text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
?>
