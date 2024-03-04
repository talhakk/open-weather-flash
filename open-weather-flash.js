jQuery(document).ready(function($) {
    // Prevent default form submission behavior
    $('.openweatherflash_forms').on('submit', function(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }

        // Save API key
        if ($(this).attr('id') === 'openweatherflash-save-api') {
            $(".loader").addClass("loading");
            var key = $("#apikey").val();
            var nonce = $("#apikey_nonce").val(); // Get the nonce value
            var data = {
                'action': 'save_api_key',
                'weatherapikey': key,
                'apikey_nonce': nonce // Include the nonce value in the data
            };

            jQuery.post(ajaxurl, data, function(response) {
                if (response.success) {
                    document.getElementById('weather_submit_message').innerHTML =  response.data;
                    $(".openweatherflash_main").removeClass("loading");
                } else {
                    document.getElementById('weather_submit_message').innerHTML = response.data;
                    $(".openweatherflash_main").removeClass("loading");
                }            
            });
        }
        // Save Default Settings
        if ($(this).attr('id') === 'openweatherflash-settings-form') {
            $(".loader").addClass("loading");
            var data = {
                'action': 'save_openweatherflash_settings',
                'default_location': $('#openweatherflash_default_location').val(),
                'default_display': $('input[name="openweatherflash_default_display[]"]:checked').map(function(){
                    return $(this).val(); }).get().join(','),
                'default_unit': $('input[name=openweatherflash_default_unit]:checked').val(),
                'openweatherflash_nonce': $('#openweatherflash_nonce').val()
            };
    
            jQuery.post(ajaxurl, data, function(response) {
                if (response.success) {
                    document.getElementById('weather_settings_submit_message').innerHTML =  response.data;
                    $(".openweatherflash_main").removeClass("loading");
                } else {
                    document.getElementById('weather_settings_submit_message').innerHTML = response.data;
                    $(".openweatherflash_main").removeClass("loading");
                }            
            });
        }
    });
});

	
