<?php
/*
Plugin Name: Events Plugin
Description: Creates a custom table for events in the binno database.
*/

// Activation hook to create the table when the plugin is activated
register_activation_hook(__FILE__, 'custom_events_plugin_activation');

function custom_events_plugin_activation()
{
    // Ensure that the global $wpdb is available
    global $wpdb;

    // Set the table name with the appropriate prefix
    $table_name = $wpdb->prefix . 'events';

    // SQL query to create the events table
    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        profile VARCHAR(255) NOT NULL,
        img VARCHAR(255) NOT NULL,
        enabler_name varchar(255) NOT NULL,
        Title VARCHAR(255) NOT NULL,
        address VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        date DATE NOT NULL,
        PRIMARY KEY (id)
    )";

    // Include the WordPress database upgrade file
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // Execute the SQL query
    dbDelta($sql);
}
