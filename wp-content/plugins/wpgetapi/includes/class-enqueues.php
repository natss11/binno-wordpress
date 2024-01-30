<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The main class
 *
 * @since 1.4.4
 */
class WpGetApi_Api_Enqueues {


	/**
     * Main constructor
     *
     * @since 1.4.4
     *
     */
    public function __construct() {

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_styles' ) );

    }

	/**
	 * Enqueue scripts and styles.
	 */
	public function admin_scripts_styles( $hook_suffix ) {

		$v = WPGETAPIVERSION;
		//$v = time();

		if( isset( $hook_suffix ) && strpos( $hook_suffix, 'wpgetapi_' ) !== false )  {

		    wp_enqueue_script( 'wpgetapi-script', WPGETAPIURL .'assets/js/wpgetapi-admin.js', array( 'jquery' ), $v );
		    wp_enqueue_script( 'jquery-ui-tooltip', '', array( 'jquery-ui-core', 'wpgetapi-script' ) );


		}

		wp_enqueue_style( 'wpgetapi-style', WPGETAPIURL .'assets/css/wpgetapi-admin.css', false, $v );

	}

}

return new WpGetApi_Api_Enqueues();