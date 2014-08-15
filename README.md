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
## Custom Shortcodes
- A custom shortcode directory is included with a sample shortcode to help you create and store your custom shortcodes.
- Copy ( or move ) the custom directory, tng-api-custom in to wp-content/plugins/.
- By placing the directory outside the plugin, custom shortcodes would not be overwritten on updates.
 
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
 
- There are 3 shortcodes for displaying events for the current month. Place these 3 shortcodes on one page, using `tabs`. 
- Each shortcode has Month and year selector. By placing these shortcodes in one page, month selection would apply to all 3 files.
  - `[upavadi_pages__birthdays]`      Birthdays
  - `[upavadi_pages__manniversaries]` Marriage Anniversaries
  - `[upavadi_pages__danniversaries]` Death Anniversaries Report
  -  Above reports use Individual hyperlinks to the 'Family' page
 
## Upload User Images
- Place shortcode `[upavadi_pages_submit-image]` in your Upload page .
- User images are uploaded in to TNG/photos/ directory with the collection name specified by you. I have called my collection, uploads. 
- To set this up,
  - Enter the name for the collection in settings >TngApi > Photo Upload mediaID.
  - In TNG admin, go to media and create a collection with same name.
- Once an image is uploaded, an Email will be sent to the administrator with image details. 
- Go to TNG Admin > Media and select your upload collection. Process the image there with the data submitted.
- Tag the image with personID and replace the name of the collection ( say Photos ) to publish.
- The image would have been given a random name. You will have to rename the image according to the convention you use.

## Upload a Profile Image
- Profile image upload is included in the family page. Here the user does not need to enter any information. The profile image is saved with PersonID. An Email is generated to the Administrator on upload.

## Special Event in Family Page
- I am using a custom TNG event in the family page. The event is displayed in last column of row 1.
- I have defined a special event, Gotra, which is stored in TNG eventtypes table as eventtypeID=10
- If you have defined an event with eventtypeID=10, then this will be displayed in last column of row 1
- If you do not have eventtypeID=10, then this cell will be blank.
- If you would like to display another eventtype or you do not want to display your eventtypeID=10
  - open TngContent.php, in Upavadi folder, in wordpress editor.
  - find function getGotra ( around line 241 )
  - Find line `where persfamID = '{$personId}' AND eventtypeID = "10"` (line 252) and change "10" to required eventtype.
  - If you do nort require this, change "10" to " "
  - Do the same thing to the next function `getEventDisplay`
  - The line to change is `where eventtypeID = "10"` (line 267)

## Patches & contributions
This is very much a project that can evolve so please feel free to fork and submit pull requests.
