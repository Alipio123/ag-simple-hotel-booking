<?php

class Hotel_Booking {

    public function init() {
        add_action('init', array($this, 'handle_booking_submission'));
        add_shortcode('hotel_booking_form', array($this, 'display_form')); // Register shortcode for the form
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

    public function handle_booking_submission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_id'])) {
            global $wpdb;

            $room_id = intval($_POST['room_id']);
            $checkin_date = sanitize_text_field($_POST['checkin_date']);
            $checkout_date = sanitize_text_field($_POST['checkout_date']);
            $customer_name = sanitize_text_field($_POST['customer_name']);
            $customer_email = sanitize_email($_POST['customer_email']);

            // Check if the room is available
            if ($this->is_room_available($room_id, $checkin_date, $checkout_date)) {
                // Insert booking data into the database
                $wpdb->insert(
                    $wpdb->prefix . 'hotel_bookings',
                    array(
                        'room_id'        => $room_id,
                        'checkin_date'   => $checkin_date,
                        'checkout_date'  => $checkout_date,
                        'customer_name'  => $customer_name,
                        'customer_email' => $customer_email,
                    )
                );

                // Send email to customer
                $subject = 'Booking Confirmation';
                $message = "Hello $customer_name,\n\nYour booking from $checkin_date to $checkout_date has been confirmed.";
                wp_mail($customer_email, $subject, $message);

                // Send email to admin
                $admin_email = get_option('admin_email');
                $admin_message = "New booking from $customer_name for Room ID $room_id from $checkin_date to $checkout_date.";
                wp_mail($admin_email, 'New Booking Notification', $admin_message);

                // Display success message
                echo 'Booking successful!';

                // Redirect to PayPal for payment (optional)
                $this->display_paypal_button($room_id, $checkin_date, $checkout_date, $customer_name, $customer_email);
            } else {
                echo 'Sorry, this room is not available for the selected dates.';
            }
        }
    }

    // Display a PayPal payment button (optional)
    public function display_paypal_button($room_id, $checkin_date, $checkout_date, $customer_name, $customer_email) {
        // Change the PayPal details as necessary
        echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="your-paypal-email@example.com">
            <input type="hidden" name="item_name" value="Room Booking for Room ID ' . $room_id . '">
            <input type="hidden" name="amount" value="100.00"> <!-- Adjust this price -->
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="return" value="your-website.com/booking-confirmation-page">
            <input type="hidden" name="cancel_return" value="your-website.com/booking-cancel-page">
            <input type="hidden" name="custom" value="' . $room_id . '|' . $checkin_date . '|' . $checkout_date . '|' . $customer_name . '|' . $customer_email . '">
            <input type="submit" value="Pay with PayPal">
        </form>';
    }
}