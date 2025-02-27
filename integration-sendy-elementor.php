<?php
/**
 * Plugin Name: Integration Sendy for Elementor
 * Description: Easily connect Elementor Pro forms to Sendy and automatically subscribe users.
 * Version: 1.0
 * Author: Asheville Web Design
 * Author URI: https://ashevillewebdesign.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.0
 * Tested up to: 6.7
 * Requires PHP: 7.2
 * Requires Plugins: elementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Sendy_Elementor_Integration {
    private $options;

    public function __construct() {
        add_action('admin_menu', [$this, 'create_settings_page']);
        add_action('admin_init', [$this, 'setup_settings']);
        add_action('elementor_pro/forms/new_record', [$this, 'send_to_sendy'], 10, 2);
    }

    public function create_settings_page() {
        add_options_page('Integration Sendy for Elementor Settings', 'Integration Sendy for Elementor', 'manage_options', 'sendy-elementor', [$this, 'settings_page_html']);
    }

    public function setup_settings() {
        register_setting(
            'sendy_elementor_settings',
            'sendy_elementor_options',
            array(
                'sanitize_callback' => [$this, 'sanitize_options']
            )
        );

        add_settings_section('sendy_section', 'Sendy API Settings', null, 'sendy-elementor');

        add_settings_field('sendy_url', 'Sendy Installation URL', [$this, 'sendy_url_callback'], 'sendy-elementor', 'sendy_section');
        add_settings_field('api_key', 'Sendy API Key', [$this, 'api_key_callback'], 'sendy-elementor', 'sendy_section');
        add_settings_field('list_id', 'Sendy List ID', [$this, 'list_id_callback'], 'sendy-elementor', 'sendy_section');
        add_settings_field('name_field', 'Elementor Name Field ID', [$this, 'name_field_callback'], 'sendy-elementor', 'sendy_section');
        add_settings_field('email_field', 'Elementor Email Field ID', [$this, 'email_field_callback'], 'sendy-elementor', 'sendy_section');
    }

    public function sendy_url_callback() {
        $options = get_option('sendy_elementor_options');
        echo '<input type="text" name="sendy_elementor_options[sendy_url]" value="' . esc_url($options['sendy_url'] ?? '') . '" class="regular-text">';
        echo '<p class="description">Enter your Sendy installation URL (e.g., https://your-sendy.com).</p>';
    }

    public function api_key_callback() {
        $options = get_option('sendy_elementor_options');
        echo '<input type="text" name="sendy_elementor_options[api_key]" value="' . esc_attr($options['api_key'] ?? '') . '" class="regular-text">';
    }

    public function list_id_callback() {
        $options = get_option('sendy_elementor_options');
        echo '<input type="text" name="sendy_elementor_options[list_id]" value="' . esc_attr($options['list_id'] ?? '') . '" class="regular-text">';
    }

    public function name_field_callback() {
        $options = get_option('sendy_elementor_options');
        echo '<input type="text" name="sendy_elementor_options[name_field]" value="' . esc_attr($options['name_field'] ?? 'name') . '" class="regular-text">';
    }

    public function email_field_callback() {
        $options = get_option('sendy_elementor_options');
        echo '<input type="text" name="sendy_elementor_options[email_field]" value="' . esc_attr($options['email_field'] ?? 'email') . '" class="regular-text">';
    }

    public function settings_page_html() {
        echo '<div class="wrap">';
        echo '<h1>Integration Sendy for Elementor</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields('sendy_elementor_settings');
        do_settings_sections('sendy-elementor');
        submit_button();
        echo '</form>';
        echo '</div>';
    }

    public function sanitize_options($options) {
        $sanitized_options = array();

        if (isset($options['sendy_url'])) {
            $sanitized_options['sendy_url'] = esc_url_raw($options['sendy_url']);
        }

        if (isset($options['api_key'])) {
            $sanitized_options['api_key'] = sanitize_text_field($options['api_key']);
        }

        if (isset($options['list_id'])) {
            $sanitized_options['list_id'] = sanitize_text_field($options['list_id']);
        }

        if (isset($options['name_field'])) {
            $sanitized_options['name_field'] = sanitize_text_field($options['name_field']);
        }

        if (isset($options['email_field'])) {
            $sanitized_options['email_field'] = sanitize_text_field($options['email_field']);
        }

        return $sanitized_options;
    }

    public function send_to_sendy($record, $handler) {
        $options = get_option('sendy_elementor_options');

        if (empty($options['sendy_url']) || empty($options['api_key']) || empty($options['list_id'])) {
            return; // Exit if required fields are missing
        }

        $form_data = $record->get('fields');
        $name = isset($form_data[$options['name_field']]) ? sanitize_text_field($form_data[$options['name_field']]['value']) : '';
        $email = isset($form_data[$options['email_field']]) ? sanitize_email($form_data[$options['email_field']]['value']) : '';

        if (empty($name) || empty($email)) {
            return;
        }

        $sendy_url = trailingslashit($options['sendy_url']) . 'subscribe';

        $post_data = [
            'api_key' => $options['api_key'],
            'name'    => $name,
            'email'   => $email,
            'list'    => $options['list_id'],
            'boolean' => 'true'
        ];

        $response = wp_remote_post($sendy_url, [
            'body'      => $post_data,
            'timeout'   => 15,
            'sslverify' => false,
        ]);

        if (is_wp_error($response)) {
            error_log('Sendy API Error: ' . $response->get_error_message());
        } else {
            $http_code = wp_remote_retrieve_response_code($response);
            if ($http_code !== 200) {
                error_log('Sendy API Response Code: ' . $http_code);
            }
        }
    }
}

new Sendy_Elementor_Integration();