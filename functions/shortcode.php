<?php

function custom_gallery_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts, 'custom_gallery');

    $post_id = $atts['id'];
    $output = '';

    if ($post_id && get_post_type($post_id) === 'gallery') {
        $images = get_post_meta($post_id, '_gallery_images', true);

        if (!empty($images)) {
            $output .= '<div class="custom-gallery-carousel glider-contain">';
            $output .= '<div class="glider">';
            
            foreach ($images as $image_id) {
                $image_url = wp_get_attachment_image_url($image_id, 'full');
                if ($image_url) {
                    $output .= '<div><img src="' . esc_url($image_url) . '" alt=""></div>';
                }
            }
            
            $output .= '</div>'; 
            $output .= '<button class="glider-prev"><i class="fas fa-chevron-left"></i></button>';
            $output .= '<button class="glider-next"><i class="fas fa-chevron-right"></i></button>';
            $output .= '</div>';

            $output .= '<div class="gallery-download-button">';
            $output .= '<a href="' . esc_url(add_query_arg('download_gallery', $post_id)) . '">Baixar Imagens</a>';
            $output .= '<div class="glider-index">1 / ' . count($images) . '</div>';
            $output .= '</div>';
        }
    }
    
    return $output;
}

add_shortcode('maia-gallery', 'custom_gallery_shortcode');

function enqueue_custom_styles() {
    wp_enqueue_style('admin-shortcode', plugin_dir_url(__FILE__) . '../styles/carousel.css');
}

add_action('wp_enqueue_scripts', 'enqueue_custom_styles');

function download_gallery_images() {
    if (isset($_GET['download_gallery'])) {
        $post_id = intval($_GET['download_gallery']);
        $images = get_post_meta($post_id, '_gallery_images', true);

        if (!empty($images)) {
            $zip = new ZipArchive();
            $zip_name = 'gallery_images_' . $post_id . '.zip';
            if ($zip->open($zip_name, ZipArchive::CREATE)) {
                foreach ($images as $image_id) {
                    $image_path = get_attached_file($image_id);
                    if ($image_path) {
                        $image_filename = basename($image_path);
                        $zip->addFile($image_path, $image_filename);
                    }
                }
                $zip->close();

                header("Content-type: application/zip");
                header("Content-Disposition: attachment; filename=$zip_name");
                header("Pragma: no-cache");
                header("Expires: 0");
                readfile($zip_name);
                unlink($zip_name);
                exit;
            }
        }
    }
}

add_action('init', 'download_gallery_images');
