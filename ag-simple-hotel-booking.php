<?php
/*
Plugin Name: AG Simple Hotel Booking
Description: A lightweight hotel booking system plugin that allows hotels to manage room bookings, availability, and customer reservations directly from their WordPress site.
Version: 1.0.6
Author: Alipio Gabriel
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('ABSPATH') || exit; // Prevent direct access

// Load necessary files
require_once plugin_dir_path(__FILE__) . 'includes/class-hotel-booking-system.php';

function ag_simple_hotel_booking_init() {
    $hotel_booking = new Hotel_Booking_System();
    $hotel_booking->init();
}
add_action('plugins_loaded', 'ag_simple_hotel_booking_init');

// Include the Plugin Update Checker library
require_once plugin_dir_path(__FILE__) . 'includes/plugin-update-checker-master/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// Initialize the update checker
$update_checker = PucFactory::buildUpdateChecker(
    'https://github.com/Alipio123/ag-simple-hotel-booking/', // GitHub repository URL
    __FILE__, // Full path to the main plugin file
    'ag-simple-hotel-booking' // Plugin slug
);

// Set the branch to check for updates (use 'main' to fetch from the main branch directly)
$update_checker->setBranch('main'); // Set to 'main' to check for updates directly from the main branch


add_action('wp_enqueue_scripts', 'ag_simple_hotel_booking_enqueue_scripts');
function ag_simple_hotel_booking_enqueue_scripts() {
    // Enqueue jQuery UI for the datepicker
    wp_enqueue_script('jquery-ui-datepicker');
    
    // Enqueue the custom booking form script
    wp_enqueue_script(
        'hotel-booking-form',
        plugin_dir_url(__FILE__) . 'assets/js/booking-form.js',
        array('jquery', 'jquery-ui-datepicker'), // jQuery and jQuery UI Datepicker as dependencies
        false,
        true // Load in the footer
    );

    // Enqueue jQuery UI CSS for the datepicker
    wp_enqueue_style(
        'jquery-ui-css',
        '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'
    );
}


require_once plugin_dir_path(__FILE__) . 'includes/class-hotel-admin.php';
// Initialize the admin interface
function ag_simple_hotel_admin_init() {
    $admin = new Hotel_Admin();
    $admin->init();
}
add_action('plugins_loaded', 'ag_simple_hotel_admin_init');
