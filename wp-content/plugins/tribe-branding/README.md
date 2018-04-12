# tribe-branding
Update links and logos and social icons

## General Information

This plugin allows you to update a theme's icon and image branding set by way of the WordPress Customizer.

There is a fallback method for all images except for the login logo. To utilize this fallback, you add your image files into the `wp-content/themes/core/img/theme/branding-assets` dir using the appropriate names found below.

## Assets / Image Names
The following is a list of assets consumed by this plugin.

* **favicon.ico** - Recommended dimensions: 64px X 64px. Recommended file type: .ico.
* **android-icon.png** - Recommended dimensions: 192px X 192px. Recommended file type: .png.
* **apple-touch-icon-precomposed.png** - Recommended dimensions: 512px X 512px. Recommended file type: .png.
* **ms-icon-144.png** - Recommended dimensions: 144px X 144px. Recommended file type: .png.
* **social-share.jpg** - Recommended dimensions will vary from theme to theme. Work with your team's designer to determine the optimal image size.
* **login-logo.png** - Recommended minimum width: 700px. Recommended file type: .png.
    * _Note_: **Login Logo** can only be set via the WordPress Customizer.

## Editing Methods
You're welcome to edit the contents of a method to meet the needs of your theme. For instance, using a .png file instead of a .ico file.

There are two methods we encourage you to review and modify to match the branding assets.
* `set_android_theme_color()` - Android OS active Chrome window theme color.
* `set_ie_metro_icon_bgd_color()` - IE Metro Icon Background Color

You can set these colors via the customizer, but it is a good idea to confirm or update the default `#fff` color set to these methods.