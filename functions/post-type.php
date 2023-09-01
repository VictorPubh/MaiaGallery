<?php

function custom_gallery_post_type() {
    $labels = array(
        'name' => 'Galerias',
        'singular_name' => 'Galeria',
        'menu_name' => 'Galerias',
        'add_new' => 'Adicionar Nova',
        'add_new_item' => 'Adicionar Nova Galeria',
        'edit_item' => 'Editar Galeria',
        'new_item' => 'Nova Galeria',
        'view_item' => 'Ver Galeria',
        'search_items' => 'Buscar Galerias',
        'not_found' => 'Nenhuma galeria encontrada',
        'not_found_in_trash' => 'Nenhuma galeria encontrada na lixeira'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'menu_icon' => 'dashicons-images-alt2',
        'supports' => array('title', 'thumbnail', 'custom-fields'),
        'has_archive' => true,
    );

    register_post_type('gallery', $args);
}

add_action('init', 'custom_gallery_post_type');

function hide_custom_fields_metabox() {
    remove_meta_box('postcustom', 'gallery', 'normal');
}

add_action('admin_menu', 'hide_custom_fields_metabox');

function remove_featured_image_metabox() {
    remove_meta_box('postimagediv', 'gallery', 'side');
}

add_action('do_meta_boxes', 'remove_featured_image_metabox');

function display_shortcode_copy() {
    global $post;

    if ($post && $post->post_type === 'gallery') {
        $shortcode = '[wp_shortcode_gallery id="' . $post->ID . '"]';

        echo '<div class="shortcode-copy">';
        echo '<p>Shortcode:</p>';
        echo '<div class="shortcode-input">';
        echo '<input type="text" value="' . esc_attr($shortcode) . '" readonly>';
        echo '</div>';
        echo '</div>';
    }
}

add_action('edit_form_after_title', 'display_shortcode_copy');
