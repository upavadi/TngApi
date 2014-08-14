HOW TO Use custom code
======================

## Introduction
Your custom shortcodes should be be placed in this folder.
Copy ( or move ) the folder, `tng-api-custom` in to wp-content/plugins. 
This way, update to the plugin will not overwrite your custom shortcodes.

Shortcode, `tngcustom_myshortcode` is included as a sample

# To create a shortcode `myshortcode`
 - Create your template file and place it in templates directory.
 - Name the file, `myshortcode.html.php`
 - Create a file, `MyShortcode.php. Create a public function to access your function(s). See sample file
 - Enter required functions in `TngCustom.php`. Register your `myshortcode` in this file.
 - Please ensure that for file names, upper and lower case characters are used as shown.
 - That's it. If you would like to see more samples, have a look at custom codes for my website [here](https://github.com/upavadi/TngApiUpavadi).
 
