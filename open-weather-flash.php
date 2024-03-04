<?php
/*
Plugin Name: Open Weather Flash
Description: The Plugin displays weather data from Open Weather.
Version: 1
Author: Talha Rasheed Khan
Author URI: https://wordpressflash.com/
Text Domain: open-weather-flash
*/
//aa32b4fd1114d2faa5ab83b23fa4f1c0
// Prevent direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * Delete the plugin's options when the plugin is deleted.
 */
function openweatherflash_uninstall() {
	// Delete the plugin's options on uninstall.
	delete_option( 'openweatherflash_save_api_key' );
    delete_option( 'openweatherflash_default_location' );
    delete_option( 'openweatherflash_default_display' );
    delete_option( 'openweatherflash_default_unit' );
}
register_uninstall_hook( __FILE__, 'openweatherflash_uninstall' );
/**
 * Register Scripts
 */
function openweatherflash_enqueue_script()
{   
        // Enqueue jQuery
        wp_enqueue_script("jquery");   
        // Enqueue your custom script with handle 'openweatherflash_jquery'
        wp_enqueue_script( 'openweatherflash_jquery', plugin_dir_url( __FILE__ ) . 'open-weather-flash.js', array('jquery'), '1.1', true );
        // Enqueue your custom CSS file
        wp_enqueue_style( 'openweatherflash_styles', plugin_dir_url( __FILE__ ) . 'open-weather-flash.css', array(), '1.0' );
}
add_action('admin_enqueue_scripts', 'openweatherflash_enqueue_script');

// Define the callback function for the settings page
function openweatherflash_settings_page_content() {
    // Include the content of your settings page
    include_once plugin_dir_path( __FILE__ ) . 'settings-page.php';
}

// Add the dashboard page for settings
add_action( 'admin_menu', 'openweatherflash_add_settings_page' );
function openweatherflash_add_settings_page() {
    // Add the menu page
    add_menu_page(
        'Open Weather API Settings', // Page title
        'Weather Flash',              // Menu title
        'manage_options',           // Capability required
        'openweatherflash_settings', // Menu slug
        'openweatherflash_settings_page_content', // Callback function to output page content
        'dashicons-align-pull-left',
        6          // Position in the menu
    );
}
/**
 * Getting Open Weather Api Key and Saving it to options
 */

 add_action( 'wp_ajax_save_api_key', 'save_api_key' );

 function save_api_key() {
    // Check nonce
    check_ajax_referer( 'save_apikey', 'apikey_nonce' );

    // Check if the API key is set
    if ( ! isset( $_POST['weatherapikey'] ) ) {
        wp_send_json_error( 'API key is missing.' );
    }

    // Sanitize the API key
    $weatherapikey = sanitize_text_field( $_POST['weatherapikey'] );

    // Verify the API key by making a test API call
    $api_url = 'http://api.openweathermap.org/data/2.5/weather?q=London&appid=' . $weatherapikey; // Adjust the URL and parameters as per your needs
    $response = wp_remote_get( $api_url );

    // Check if the API call was successful
    if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
        // Save the API key
        $result = update_option( 'openweatherflash_save_api_key', $weatherapikey );

        // Check if the API key was saved
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( 'Error saving API key: ' . $result->get_error_message() );
        } else {
            wp_send_json_success( 'API key saved successfully.' );
        }
    } else {
        // API key is invalid
        wp_send_json_error( 'Invalid API key.' );
    }

    wp_die(); // this is required to terminate immediately and return a proper response
}
/**
 * Getting Open Weather API data
 */
// Function to fetch weather data based on location
function get_weather_data($location,$unit) {
    // Retrieve OpenWeatherMap API key from options
    $api_key = get_option('openweatherflash_save_api_key');

    // Construct API URL with location and API key
    $api_url = 'http://api.openweathermap.org/data/2.5/weather?q=' . urlencode($location) . '&units=' . $unit . '&appid=' . $api_key;

    // Make API call
    $response = wp_remote_get($api_url);

    // Check if API call was successful
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        $data = json_decode(wp_remote_retrieve_body($response), true);
        return $data;
    } else {
        return false;
    }
}

// Shortcode function to display weather data
function openweatherflash_shortcode($atts) {
    //pull default location  
    $defaultlocation = get_option( 'openweatherflash_default_location' );
    //pull default Display  
    $defaultdisplay = get_option( 'openweatherflash_default_display' );
    //pull default Unit  
    $defaultunit = get_option( 'openweatherflash_default_unit' );
    // If default location is not set or empty, use 'Lahore'
    if (empty($defaultlocation)) {
        $defaultlocation = 'Lahore';
    }
    // If default Unit is not set or empty, use 'temperature,condition,humidity,wind_speed'
    if (empty($defaultdisplay)) {
        $defaultdisplay = 'temperature,condition,humidity,wind_speed';
    }
     // If default Unit is not set or empty, use  'standard'
     if (empty($defaultunit)) {
        $defaultunit = 'metric';
    }
    // Extract shortcode attributes
    $atts = shortcode_atts(array(
        'location' => $defaultlocation, // Default location is London
        'display' => $defaultdisplay, // Default display parameters
        'unit' => $defaultunit // Default Unit parameters
    ), $atts);

    // Get weather data based on provided location
    $weather_data = get_weather_data($atts['location'],$atts['unit']);

    // Check if weather data is available
    if ($weather_data) {
        // Initialize output variable
        $output = '<div class="openweatherflash-widget">';

        // Display weather data based on display parameters
        $display_params = explode(',', $atts['display']);
        foreach ($display_params as $param) {
            switch ($param) {
                case 'temperature':
                    // Determine the temperature sign based on the selected unit
                    $temperature_sign = $atts['unit'] === 'imperial' ? '°F' : '°C';
                    $output .= '<p>Temperature: ' . $weather_data['main']['temp'] . ' ' . $temperature_sign . '</p>';
                    break;
                case 'condition':
                    $output .= '<p>Condition: ' . $weather_data['weather'][0]['main'] . '</p>';
                    break;
                case 'humidity':
                    $output .= '<p>Humidity: ' . $weather_data['main']['humidity'] . '%</p>';
                    break;
                case 'wind_speed':
                    $wind_sign = $atts['unit'] === 'imperial' ? 'miles/hour' : 'meter/sec';
                    $output .= '<p>Wind Speed: ' . $weather_data['wind']['speed'] .' '.$wind_sign.'</p>';
                    break;
                // Add more cases for additional weather parameters
            }
        }

        $output .= '</div>';
    } else {
        $output = '<p>No weather data available for the specified location.</p>';
    }

    return $output;
}

// Register shortcode
add_shortcode('openweatherflash', 'openweatherflash_shortcode');

/***
 * 
 * 
 * 
 * 
 * 
 * Change Settings 
 * 
 */
add_action('wp_ajax_save_openweatherflash_settings', 'save_openweatherflash_settings');
function save_openweatherflash_settings() {
    check_ajax_referer('openweatherflash_settings_nonce', 'openweatherflash_nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission Denied, Only Admin Can Change');
    }

    if (isset($_POST['default_location'])) {
        update_option('openweatherflash_default_location', sanitize_text_field($_POST['default_location']));
    }

    if (isset($_POST['default_display'])) {
        update_option('openweatherflash_default_display', sanitize_text_field($_POST['default_display']));
    }

    if (isset($_POST['default_unit'])) {
        update_option('openweatherflash_default_unit', sanitize_key($_POST['default_unit']));
    }
    wp_send_json_success('Settings Saved Successfully');
}
