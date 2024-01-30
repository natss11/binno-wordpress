<?php
/*
Plugin Name: Startup Posts Plugin
Description: A custom plugin to retrieve data from the startupposts table.
Version: 1
Author: Marriane Natad
*/

// Activate the plugin hook
register_activation_hook(__FILE__, 'startup_posts_plugin_activation');

// Function to run when the plugin is activated
function startup_posts_plugin_activation()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'startupposts';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        content text NOT NULL,
        img varchar(255) NOT NULL,
        date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        comp_name varchar(255) NOT NULL,
        profile varchar(255) DEFAULT '' NOT NULL, 
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
