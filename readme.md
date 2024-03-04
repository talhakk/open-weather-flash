### Open Weather Flash Plugin

The Open Weather Flash plugin allows you to display weather data from Open Weather on your WordPress site. You can easily customize the display options and default settings to show weather information based on your preferences.

#### Installation

1. **Download the Plugin:**
   - Download the plugin zip file from [here](#).
   - Extract the contents of the zip file.

2. **Upload to WordPress:**
   - Upload the extracted folder to the `/wp-content/plugins/` directory of your WordPress installation.

3. **Activate the Plugin:**
   - Log in to your WordPress dashboard.
   - Go to the Plugins menu and activate the "Open Weather Flash" plugin.

#### Usage

1. **Get Open Weather API Key:**
   - Sign up on [OpenWeatherMap](https://openweathermap.org/) to generate an API key.
   - Go to the plugin settings page (`Weather Flash` in the WordPress dashboard).
   - Enter your API key in the designated field and save it.

2. **Set Default Display Options:**
   - Choose your default location, display parameters, and unit preferences.
   - Save your settings.

3. **Display Weather Anywhere:**
   - Use the shortcode `[openweatherflash]` to display weather information anywhere on your site.
   - You can customize the shortcode attributes:
     - `location`: Specify the location for which you want to display weather information.
     - `display`: Define the parameters you want to display (e.g., temperature, condition, humidity, wind speed).
     - `unit`: Choose the unit system for temperature and wind speed (standard, metric, imperial).

#### Example Shortcode Usage:

```shortcode
[openweatherflash location="New York" display="temperature,condition" unit="imperial"]
```

This will display the temperature and condition for New York using the imperial unit system.

**Note:** Shortcode attributes will override the default settings configured in the plugin settings page.

Now you're all set to showcase accurate and real-time weather data on your WordPress site with the Open Weather Flash plugin!