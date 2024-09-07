<?php
/*
Plugin Name: AG Simple Hotel Booking
Description: A lightweight hotel booking system plugin that allows hotels to manage room bookings, availability, and customer reservations directly from their WordPress site.
Version: 1.0.1
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

// Initialize the update checker
$update_checker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/Alipio123/ag-simple-hotel-booking/', // GitHub repository URL
    __FILE__, // Full path to the main plugin file
    'ag-simple-hotel-booking' // Plugin slug
);

// Set the branch to check for updates
$update_checker->setBranch('main'); // Adjust if you're using a different default branch
