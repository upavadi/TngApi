TngApi & Wordpress Plugin (alpha)
=================================

## License
The code is licenced under the [MIT licence](http://opensource.org/licenses/MIT)

## Introduction
This plugin has two main features:
 - To provide a simpler integration of the TNG site within wordpress.
 - To provide a convenient collection of shortcode and functions for integrating TNG data into your site.

This plugin is not:
 - A replacement for any registration process (you'll still need a plugin for that)
 - Complete

## Performace
To get better query performance add an indexes to to following fields:

 * `tng_people.changedate`
 * `tng_people.personID`

## Installation

### Preparation
This plugin uses some 3rd party libraries, to get them we use the [composer](https://getcomposer.org).
Before you begin please run the following command in the tng-api directory source directory:
```sh
php composer.phar install
```

This plugin assumes that:
 - Your TNG installation is in the directory below the base of your site (i.e. something like `http://mytngsite.com/tng`).
 - You have created an empty page to act as the proxy for the TNG site.
 - You have set TNG to use template7.
 - Only logged in users can see TNG
 - User names are the same in wordpress and TNG

You will also need the connection settings for your TNG DB handy.

### Setup
After installing the plugin you can find the settings page in the settings menu named `TngApi`, here you'll need to specify:
 - The location of your TNG installtion as it is on disk (i.e. `/path/to/tng`)
 - Your DB connection settings.
 - The page ID of the page you wish to use as a proxy

Next edit to the proxy page you created earlier and add the `[upavadi_tng_proxy]` shortcode to the content.

## Shortcodes
We've provided a number of useful shortcodes for to play with.  You can find them all in the shortcodes directory.

 - `[short_code_1]` - description 1
 - `[short_code_2]` - description 2

## Patches & contributions
This is very much a work in progress so please feel free to fork and submit pull requests.
