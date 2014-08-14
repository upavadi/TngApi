TngApi Plugin for Wordpress (Version 1.3)
=================================

## License
The code is licenced under the [MIT licence](http://opensource.org/licenses/MIT)

## Introduction
This plugin has two main features:
 - To provide a simple access to the TNG database from within Wordpress.
 - To provide a convenient collection of shortcode and functions for integrating TNG data into your site.

This plugin does not:
 - Provide any registration process 
 - Display TNG pages within Wordpress
 - You'll still need other plugins for that.

## Performace
To get better query performance add an indexes to to following fields:

 * `tng_people.changedate`
 * `tng_people.personID`

## Installation

### Preparation
This plugin assumes that:
 - Your TNG installation is in the directory below the base of your site (i.e. something like `http://mytngsite.com/tng`).

### Setup
You will need the connection settings for your TNG DB handy.

After installing the plugin you can find the settings page in the settings menu named `TngApi`, here you'll need to specify:
- Notification Email address 

- The location of your TNG installtion as it is on disk (i.e. `/path/to/tng`)
- Photo Upload mediatypeID - Before you enter this, refer to the section on Image Upload below

- Your DB connection settings.
   - Host Name
   - User Name
   - Password
   - Database Name
   
## Shortcodes
We've provided a number of useful shortcodes for you to play with.  You can find them all in the shortcodes directory.

 - `[upavadi_pages_familysearch]` - Name-Search Widget. To be able to use this widget, create page named 'search' and place    shortcode in the page. Familysearch results are displayed in this page.
 - Following 4 shortcodes are used in family page. To use existing hyperlinks, this page must be called 'family'
   - `[upavadi_pages__familyuser]`  Family page of the user
   - `[upavadi_pages__familyform]`  Update details of Individual
   - `[upavadi_pages__addfamilyform]`  Add Details of Individual
   - `[upavadi_pages__personnotes]`  Add Notes for Individual

 - Above 4 shortcodes may be used on a single page using the TAB shortcode. 
  - `[tabs]'[tab title="Family"]``[upavadi_pages_familyuser]``[/tab]`
  - `[tab title="Update Family"]``[upavadi_pages_familyform]``[/tab]`
  - `[tab title="Add Family"]``[upavadi_pages_addfamilyform]``[/tab]`
  - `[[tab title="Update Person Notes"]``[upavadi_pages_personnotes]``[/tab]`
  - `[/tabs]`
 - Advantage of placing these shortcodes is that all 4 shortcodes are synchronized to the same personID.
 
 - There are 3 shortcodes for displaying events for the current month. Place these 3 shortcodes on one page, using `tabs` as 
 - Each shortcode has Month and year selector. By placing these shortcodes in one page, month selection would apply to all 3 files.
- `[upavadi_pages__birthdays]` - Birthdays
 - `[upavadi_pages__manniversaries]` - Marriage Anniversaries
 - `[upavadi_pages__danniversaries]` - Death Anniversaries Report
 -  Above reports use Individual hyperlinks to the 'Family' page
 
 -  `[upavadi_pages__tng_proxy]` - TNG Index page redirect
 -  `[upavadi_pages__submitimage]` - Upload photos. This shortcode saves the image but does not store image data at present.        Work In Progress. 
 
## Patches & contributions
This is very much a work in progress so please feel free to fork and submit pull requests.
