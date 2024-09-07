<?php
/*
Plugin Name: AG Simple Hotel Booking
Description: A lightweight hotel booking system plugin that allows hotels to manage room bookings, availability, and customer reservations directly from their WordPress site.
Version: 1.0.8
Author: Alipio Gabriel
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('ABSPATH') || exit; // Prevent direct access

// Load necessary files
require_once plugin_dir_path(__FILE__) . 'includes/class-hotel-booking-system.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-hotel-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/plugin-update-checker-master/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// Initialize the plugin
function ag_simple_hotel_booking_init() {
    $hotel_booking = new Hotel_Booking_System();
    $hotel_booking->init();
    
    // Initialize the admin interface
    $admin = new Hotel_Admin();
    $admin->init();
}
add_action('plugins_loaded', 'ag_simple_hotel_booking_init');

// Initialize the update checker
$update_checker = PucFactory::buildUpdateChecker(
    'https://github.com/Alipio123/ag-simple-hotel-booking/',
    __FILE__,
    'ag-simple-hotel-booking'
);
$update_checker->setBranch('main');

// Create the bookings table on plugin activation
function ag_simple_hotel_booking_create_table() {
    $hotel_booking = new Hotel_Booking();
    $hotel_booking->create_booking_table();
}
register_activation_hook(__FILE__, 'ag_simple_hotel_booking_create_table');

// Enqueue scripts and styles
function ag_simple_hotel_booking_enqueue_scripts() {
    wp_enqueue_script('jquery-ui-datepicker');
    
    wp_enqueue_script(
        'hotel-booking-form',
        plugin_dir_url(__FILE__) . 'assets/js/booking-form.js',
        array('jquery', 'jquery-ui-datepicker'),
        false,
        true
    );

    wp_enqueue_style(
        'jquery-ui-css',
        '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'
    );
}
add_action('wp_enqueue_scripts', 'ag_simple_hotel_booking_enqueue_scripts');
