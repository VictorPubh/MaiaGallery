<?php

function custom_gallery_metabox() {
    add_meta_box('gallery_images', 'Imagens da Galeria', 'render_gallery_images_metabox', 'gallery', 'normal', 'high');
}

add_action('add_meta_boxes', 'custom_gallery_metabox');

function render_gallery_images_metabox($post) {
    wp_nonce_field('custom_gallery_metabox', 'custom_gallery_metabox_nonce');

    $gallery_images = get_post_meta($post->ID, '_gallery_images', true);

    ?>

    <div id="gallery-images-container">
        <?php
        if (!empty($gallery_images)) {
            foreach ($gallery_images as $image_id) {
                $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                echo '<div class="gallery-image"><img src="' . esc_url($image_url) . '" /><a href="#" class="remove-image">Remover</a><input type="hidden" name="gallery_images[]" value="' . esc_attr($image_id) . '" /></div>';
            }
        }
        ?>
    </div>

    <a href="#" class="add-images button">Adicionar Imagens</a>

    <script>
        jQuery(document).ready(function($) {
            var imageContainer = $('#gallery-images-container');

            $('.add-images').on('click', function(e) {
                e.preventDefault();
                var frame = wp.media({
                    multiple: true
                });

                frame.on('select', function() {
                    var attachments = frame.state().get('selection').toJSON();
                    $.each(attachments, function(index, attachment) {
                        imageContainer.append('<div class="gallery-image"><img src="' + attachment.url + '" /><a href="#" class="remove-image">Remover</a><input type="hidden" name="gallery_images[]" value="' + attachment.id + '" /></div>');
                    });
                });

                frame.open();
            });

            imageContainer.on('click', '.remove-image', function(e) {
                e.preventDefault();
                $(this).closest('.gallery-image').remove();
            });
        });
    </script>
    <?php
}

function upload_gallery_images() {
    if (isset($_FILES['gallery_images'])) {
        $images = $_FILES['gallery_images'];

        foreach ($images['name'] as $key => $value) {
            if ($images['name'][$key]) {
                $file = array(
                    'name' => $images['name'][$key],
                    'type' => $images['type'][$key],
                    'tmp_name' => $images['tmp_name'][$key],
                    'error' => $images['error'][$key],
                    'size' => $images['size'][$key]
                );

                $_FILES = array("gallery_images" => $file);
                custom_handle_attachment("gallery_images", $_POST['post_id']);
            }
        }
    }

    die();
}

add_action('wp_ajax_upload_gallery_images', 'upload_gallery_images');

function save_gallery_images_metabox($post_id) {
    if (!isset($_POST['custom_gallery_metabox_nonce']) || !wp_verify_nonce($_POST['custom_gallery_metabox_nonce'], 'custom_gallery_metabox')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if ('gallery' !== $_POST['post_type']) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['gallery_images'])) {
        $gallery_images = array_map('intval', $_POST['gallery_images']);
        update_post_meta($post_id, '_gallery_images', $gallery_images);
    }
}

add_action('save_post', 'save_gallery_images_metabox');
