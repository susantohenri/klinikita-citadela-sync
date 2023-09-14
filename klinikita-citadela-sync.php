<?php

/**
 * Klinikita Citadela Sync
 *
 * @package     KlinikitaCitadelaSync
 * @author      Henri Susanto
 * @copyright   2022 Henri Susanto
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Klinikita Citadela Sync
 * Plugin URI:  https://github.com/susantohenri/klinikita-citadela-sync
 * Description: WordPress Plugin to Synchronize Klinik Creation Between App
 * Version:     1.0.0
 * Author:      Henri Susanto
 * Author URI:  https://github.com/susantohenri/
 * Text Domain: KlinikitaCitadelaSync
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

//  https://go.postman.co/workspace/0c92d5bf-5234-45be-85e6-1b8161fc74ea/request/23512180-5259a273-dc6a-4465-9c7f-4e43a0e19f4e
add_action('rest_api_init', function () {
    register_rest_route('klinikita-citadela-sync/v1', '/create-klinik', array(
        'methods' => 'POST',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        },
        'callback' => function () {
            try {
                $post_id = wp_insert_post([
                    'post_title' => wp_strip_all_tags($_POST['Nama']),
                    'post_status' => 'publish',
                    'post_type' => 'citadela-item'
                ]);
                add_post_meta($post_id, '_citadela_address', wp_strip_all_tags($_POST['Alamat']));
                return 200;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
    ));
});
