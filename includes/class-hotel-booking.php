<?php

class Hotel_Booking {

    public function init() {
        add_action('init', array($this, 'handle_booking_submission'));
    }

    // Create the booking table
    public static function create_booking_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hotel_bookings';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            room_id mediumint(9) NOT NULL,
            checkin_date date NOT NULL,
            checkout_date date NOT NULL,
            customer_name tinytext NOT NULL,
            customer_email varchar(100) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    // Display the booking form on the frontend
    public static function display_form() {
        ob_start();
        ?>
        <form method="POST" action="">
            <label for="room">Room:</label>
            <select name="room_id">
                <?php
                $rooms = get_posts(array('post_type' => 'hotel_room', 'numberposts' => -1));
                foreach ($rooms as $room): ?>
                    <option value="<?php echo $room->ID; ?>"><?php echo $room->post_title; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="checkin">Check-in Date:</label>
            <input type="text" id="checkin_date" name="checkin_date" required>

            <label for="checkout">Check-out Date:</label>
            <input type="text" id="checkout_date" name="checkout_date" required>

            <label for="name">Name:</label>
            <input type="text" name="customer_name" required>

            <label for="email">Email:</label>
            <input type="email" name="customer_email" required>

            <input type="submit" value="Book Now">
        </form>
        <?php
        return ob_get_clean();
    }

    // Handle form submission and save booking data
    public function handle_booking_submission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_id'])) {
            global $wpdb;
            $wpdb->insert(
                $wpdb->prefix . 'hotel_bookings',
                array(
                    'room_id'        => intval($_POST['room_id']),
                    'checkin_date'   => sanitize_text_field($_POST['checkin_date']),
                    'checkout_date'  => sanitize_text_field($_POST['checkout_date']),
                    'customer_name'  => sanitize_text_field($_POST['customer_name']),
                    'customer_email' => sanitize_email($_POST['customer_email']),
                )
            );
            echo 'Booking successful!';
        }
    }
}