<?php
/*
Plugin Name: Startup Enabler
Description: A plugin to create a table for startup enabler.
Version: 1.0
Author: Marriane Natad
*/

// Activation hook to create the table on plugin activation
register_activation_hook(__FILE__, 'startup_enabler_activate');

function startup_enabler_activate() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'startup_enabler';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        coverpic VARCHAR(255),
        profilepic VARCHAR(255),
        company_name VARCHAR(100),
        company_address VARCHAR(255),
        company_description TEXT,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Deactivation hook to remove the table on plugin deactivation
register_deactivation_hook(__FILE__, 'startup_enabler_deactivate');

function startup_enabler_deactivate() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'startup_enabler';

    $sql = "DROP TABLE IF EXISTS $table_name;";

    $wpdb->query($sql);
}
?>
