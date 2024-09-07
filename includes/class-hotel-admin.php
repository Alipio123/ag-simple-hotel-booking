<?php

class Hotel_Admin {
    public function init() {
        add_action('admin_menu', array($this, 'register_admin_page'));
    }

    public function register_admin_page() {
        add_menu_page(
            'Hotel Bookings',
            'Bookings',
            'manage_options',
            'hotel-bookings',
            array($this, 'display_admin_page'),
            'dashicons-building',
            20
        );
    }

    public function display_admin_page() {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}hotel_bookings");

        echo '<h1>Hotel Bookings</h1>';
        foreach ($results as $booking) {
            echo "<p>Room ID: {$booking->room_id}, Check-in: {$booking->checkin_date}, Check-out: {$booking->checkout_date}, Name: {$booking->customer_name}, Email: {$booking->customer_email}</p>";
        }
    }
}