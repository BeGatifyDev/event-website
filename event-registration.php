<?php
/**
 * Plugin Name: Event Registration
 * Plugin URI: https://yourwebsite.com
 * Description: A simple event registration plugin to manage events and attendees.
 * Version: 1.0.0
 * Author: OLUWAFEMI OLUWATOBI BEST
 * Author URI: https://begatifydev.github.io/html-resume/
 * Text Domain: event-registration
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 🔹 Define plugin constants.
define( 'ER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'ER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// 🔹 Include required files.
require_once ER_PLUGIN_PATH . 'includes/admin-page.php';
require_once ER_PLUGIN_PATH . 'includes/frontend-registration.php';

// 🔹 Register activation hook (for future database tables).
function er_activate_plugin() {
    // Example: create custom tables here in future.
}
register_activation_hook( __FILE__, 'er_activate_plugin' );

// 🔹 Enqueue frontend assets
function er_enqueue_frontend_assets() {
    wp_enqueue_style(
        'er-style',
        ER_PLUGIN_URL . 'assets/css/style.css',
        array(),
        '1.0.0',
        'all'
    );

    wp_enqueue_script(
        'er-script',
        ER_PLUGIN_URL . 'assets/js/script.js',
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'er_enqueue_frontend_assets');

// 🔹 Enqueue admin assets if you plan to add admin-specific styles/scripts later
function er_enqueue_admin_assets($hook) {
    // Optional: Only load on specific admin pages
    wp_enqueue_style(
        'er-admin-style',
        ER_PLUGIN_URL . 'assets/css/admin-style.css',
        array(),
        '1.0.0',
        'all'
    );
}
add_action('admin_enqueue_scripts', 'er_enqueue_admin_assets');


