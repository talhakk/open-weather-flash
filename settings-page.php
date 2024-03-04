<?php
// Prevent direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// Retrieve saved API key
$apikey = get_option( 'openweatherflash_save_api_key' );
?>
<section id="openweatherflash_main">
  <!-- API Key Form -->
<h1>Open Weather Flash</h1>
<p>Go to <a href="https://openweathermap.org/">https://openweathermap.org/</a> to Sign up and generate API Key</p>
<form id="openweatherflash-save-api" class="openweatherflash_forms" action="#" method="post">
    <input id="apikey" type="text" name="apikey" value="<?php echo esc_attr( $apikey ); ?>">
    <?php wp_nonce_field( 'save_apikey', 'apikey_nonce' ); ?>
    <input id="form-button-submit" type="submit" value="Save API">
</form>
<p id="weather_submit_message" class="openweatherflash_notice"></p>
<!-- Select Weather Display Form -->
<h2>Select Weather Display Options</h2>
<div class="wrap">
    <form id="openweatherflash-settings-form" class="openweatherflash_forms" method="post">
        <label for="openweatherflash_default_location">Default Location:</label>
        <input type="text" name="openweatherflash_default_location" id="openweatherflash_default_location" value="<?php echo esc_attr(get_option('openweatherflash_default_location', 'London')); ?>"><br>
        <br>
        <label for="openweatherflash_default_display">Default Display:(temperature,condition,humidity,wind_speed)</label><br>
        <input type="checkbox" id="temperature_checkbox" name="openweatherflash_default_display[]" value="temperature" <?php checked(in_array('temperature', explode(',', get_option('openweatherflash_default_display', 'temperature,condition,humidity,wind_speed')))); ?>>
        <label for="temperature_checkbox">Temperature</label><br>
        <input type="checkbox" id="condition_checkbox" name="openweatherflash_default_display[]" value="condition" <?php checked(in_array('condition', explode(',', get_option('openweatherflash_default_display', 'temperature,condition,humidity,wind_speed')))); ?>>
        <label for="condition_checkbox">Condition</label><br>
        <input type="checkbox" id="humidity_checkbox" name="openweatherflash_default_display[]" value="humidity" <?php checked(in_array('humidity', explode(',', get_option('openweatherflash_default_display', 'temperature,condition,humidity,wind_speed')))); ?>>
        <label for="humidity_checkbox">Humidity</label><br>
        <input type="checkbox" id="wind_speed_checkbox" name="openweatherflash_default_display[]" value="wind_speed" <?php checked(in_array('wind_speed', explode(',', get_option('openweatherflash_default_display', 'temperature,condition,humidity,wind_speed')))); ?>>
        <label for="wind_speed_checkbox">Wind Speed</label><br>

        <br>
      <label for="openweatherflash_default_unit">Default Unit:</label><br>
      <input type="radio" id="openweatherflash_default_unit_standard" name="openweatherflash_default_unit" value="standard" <?php checked(get_option('openweatherflash_default_unit', 'metric'), 'standard'); ?>>
      <label for="openweatherflash_default_unit_standard">Standard</label><br>
      <input type="radio" id="openweatherflash_default_unit_metric" name="openweatherflash_default_unit" value="metric" <?php checked(get_option('openweatherflash_default_unit', 'metric'), 'metric'); ?>>
      <label for="openweatherflash_default_unit_metric">Metric</label><br>
      <input type="radio" id="openweatherflash_default_unit_imperial" name="openweatherflash_default_unit" value="imperial" <?php checked(get_option('openweatherflash_default_unit', 'metric'), 'imperial'); ?>>
      <label for="openweatherflash_default_unit_imperial">Imperial</label><br><br>


        <?php wp_nonce_field('openweatherflash_settings_nonce', 'openweatherflash_nonce'); ?>
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Settings">
    </form>
    <p id="weather_settings_submit_message" class="openweatherflash_notice"></p>
</div>
<p>Use shortcode [openweatherflash] to show weather anywhere on the site. Accepts Attributes location,unit,display. Shortcode Attributes will override defaults set on this page.</p>
