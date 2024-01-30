<?php
/*
Plugin Name: Custom Tables Plugin
Description: Automatically creates custom tables in the database.
Version: 1.0
Author: Marriane Natad
*/

// Activation hook
register_activation_hook(__FILE__, 'custom_tables_plugin_activation');

function custom_tables_plugin_activation() {
    create_custom_tables();
}

// Function to create custom tables
function create_custom_tables() {
    global $wpdb;

    $table_name_latestblogs = $wpdb->prefix . 'latestblogs';
    $table_name_startupposts = $wpdb->prefix . 'startupposts';

    $charset_collate = $wpdb->get_charset_collate();

    // SQL statement for creating the latestblogs table
    $sql_latestblogs = "CREATE TABLE $table_name_latestblogs (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title text NOT NULL,
        content longtext NOT NULL,
        img varchar(255),
        date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    // SQL statement for creating the startupposts table
    $sql_startupposts = "CREATE TABLE $table_name_startupposts (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title text NOT NULL,
        content longtext NOT NULL,
        img varchar(255),
        date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    // Include the WordPress upgrade file for dbDelta()
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    // Create the tables
    dbDelta($sql_latestblogs);
    dbDelta($sql_startupposts);
}
?>
