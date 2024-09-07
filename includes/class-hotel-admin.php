<?php

class Hotel_Admin {
    public function init() {
        // Register admin menu page
        add_action('admin_menu', array($this, 'register_admin_page'));
    }

    public function register_admin_page() {
        // Add a new menu item under the "Settings" menu
        add_menu_page(
            'Hotel Bookings',         // Page title
            'Bookings',               // Menu title
            'manage_options',         // Capability required
            'hotel-bookings',         // Menu slug
            array($this, 'display_bookings_page'),  // Callback function
            'dashicons-list-view',    // Icon for the menu item
            20                        // Menu position
        );
    }

    // Display the bookings page
    public function display_bookings_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hotel_bookings';  // Define your table name
        $bookings = $wpdb->get_results("SELECT * FROM $table_name");

        ?>
        <div class="wrap">
            <h1>Hotel Bookings</h1>
            <table class="wp-list-table widefat fixed striped table-view-list">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Room ID</th>
                        <th>Check-in Date</th>
                        <th>Check-out Date</th>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo $booking->id; ?></td>
                        <td><?php echo $booking->room_id; ?></td>
                        <td><?php echo $booking->checkin_date; ?></td>
                        <td><?php echo $booking->checkout_date; ?></td>
                        <td><?php echo $booking->customer_name; ?></td>
                        <td><?php echo $booking->customer_email; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
