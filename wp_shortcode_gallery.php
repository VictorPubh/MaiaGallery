<?php
/*
    Plugin Name: Shortcode Gallery
    Description: Um plugin de galeria em formato carosel via Shortcode.
    Version: 1.0
    Author: InstaPress
    AuthorURI: https://instagram.com/grafica.instapress
*/

$plugin_dir = plugin_dir_path(__FILE__);

include $plugin_dir . 'functions/post-type.php';
include $plugin_dir . 'functions/gallery-images.php';
include $plugin_dir . 'functions/shortcode.php';

function enqueue_shortcode_copy_script() {
    wp_enqueue_style('glider-css', 'https://cdnjs.cloudflare.com/ajax/libs/glider-js/1.7.8/glider.min.css');
    wp_enqueue_script('gliderjs', plugin_dir_url(__FILE__) . 'scripts/glider.min.js', array('jquery'), null, true);
    wp_enqueue_script('glider-custom', plugin_dir_url(__FILE__) . 'scripts/glider-custom.js', array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'enqueue_shortcode_copy_script');

function enqueue_custom_admin_style() {
    if (is_admin()) {
        wp_enqueue_style('admin-gallery', plugin_dir_url(__FILE__) . 'styles/admin-gallery.css');
        wp_enqueue_style('admin-shortcode', plugin_dir_url(__FILE__) . 'styles/admin-shortcode.css');
    }
}

add_action('admin_enqueue_scripts', 'enqueue_custom_admin_style');

function enqueue_font_awesome() {
    if (!wp_style_is('font-awesome', 'enqueued')) {
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css', array(), '5.15.3');
    }
}

add_action('wp_enqueue_scripts', 'enqueue_font_awesome');
