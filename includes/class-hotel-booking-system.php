<?php

class Hotel_Booking_System {
    
    public function init() {
        // Initialize room post type and booking system
        $this->register_post_types();
        $this->load_dependencies();
        $this->handle_hooks();
    }

    // Register custom post type for hotel rooms
    private function register_post_types() {
        add_action('init', array($this, 'register_room_post_type'));
    }

    public function register_room_post_type() {
        $labels = array(
            'name' => __('Rooms'),
            'singular_name' => __('Room'),
        );
        $args = array(
            'labels'      => $labels,
            'public'      => true,
            'has_archive' => true,
            'supports'    => array('title', 'editor', 'custom-fields'),
        );
        register_post_type('hotel_room', $args);
    }

    // Load necessary classes and scripts
    private function load_dependencies() {
        require_once plugin_dir_path(__FILE__) . 'class-hotel-room.php';
        require_once plugin_dir_path(__FILE__) . 'class-hotel-booking.php';
        require_once plugin_dir_path(__FILE__) . 'class-hotel-admin.php';
    }

    private function handle_hooks() {
        // Register shortcode for the booking form
        add_shortcode('hotel_booking_form', array('Hotel_Booking', 'display_form'));
    }

}