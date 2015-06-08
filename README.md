TngApi Plugin for Wordpress
=================================
## Download
You can find the latest stable version (3.0.2) in the [releases](https://github.com/upavadi/TngApi/releases) section 
## License
The code is licenced under the [MIT licence](http://opensource.org/licenses/MIT)

## Introduction
The TngApi plugin for Wordpress is a stand-alone plugin. It integrates smoothly with [TNG ( The Next Generation of Genealogy Sitebuilding )](http://www.tngsitebuilding.com/)  to display genealogy data in Wordpress pages.
#####The plugin has several useful features:
 - Simple access to the TNG database from within Wordpress.
 - A convenient collection of shortcodes and functions for integrating TNG data into your Wordpress site.
 -	A convenient way for users to submit data-additions and data-changes
 -	A convenient way, for the administrator, to check and update TNG database from within Wordpress.
 - A convenient way, for the user, to upload default photo from the person page
 -	A convenient way, for the user, to upload media and update media links
 -	A custom shortcode directory, with a sample shortcode, to help you create and store your own custom shortcodes.

#### What’s New in TngApi Version 3
 -	In previous versions, user submissions were emailed to the administrator. In Version 3, user submissions are stored in temporary Wordpress tables and email is sent to the administrator as before.
 -	Once checked, the administrator has one-click facility to transfer user-submissions to TNG database.
 -	 The plugin now caters for multiple trees and privacy flags.
 - Facility to track a customized special event is now implemented from the setup menu.

###This plugin does not:
 -	Provide any registration process 
 -	Display TNG pages within Wordpress

## Requirements
 -	PHP 5.3 or greater
 -	Wordpress 3.8 or greater
 -	TNG V9 or better
 -	TNG installation in one directory below the base.
 -	Same User name in Wordpress and TNG
 -	User has logged in.

##Change Log
 - #####3.0.2
 - Update README.md
 - Update Version Number

 - #####3.0.1
 - Bump up version number and tidy up
 - #####3.0
 - Temporary Wordpress tables to hold User Submitted Data
 - User Submission Page to display User Data
 - Admin Submission Page to User Submissions
 - Facility to update TNG database from submission page
 - Cater for multiple trees
 - Respect Privacy flag for individual, family and notes
 - Specify whether a (customized) special event is to be tracked
 - Specify path to TNG from TNG links
  - Path dependent on whether TNG Page Integration is used or not
 - Option to remove wordpress temporary tables on deactivation
 - Bug fixes

 - #####1.3
 - Tab Shortcode
 - Image Upload Shortcode
 - Default Image upload from Family Page
 - Date Selector for Event Reports
 - Bug fixes
 - #####Initial Release

## Installation
This plugin assumes that:
 - Your TNG installation is in the directory below the base of your site (i.e. something like `http://mytngsite.com/tng`).
 - User has logged in to wordpress
 - User names are same in Wordpress and TNG

### Setup
You will need the connection settings for your TNG Database handy.<br />
After installing the plugin you can find the settings page in the Wordpress>Admin Panel>settings>TngApi.<br />
Here you'll need to specify:
 -	<b>Notification Email address:</b> 
 - <b>TNG Path:</b> The location of your TNG installation as it is on disk (i.e. /path/to/tng)
 -	<b>TNG Integration Path:</b> If you would like to show links to TNG in Family page, enter
 the location of TNG directory here. Leave blank to hide the links. ( such as tng or genealogy)
 -	<b>TNG Collection ID for Photo Uploads:</b> Before you enter this, refer to the section, Image Upload, below
 -	<b>TNG Event to Track:</b> If you would like to track a customized field or event, you may create this as a special event type (TNG Admin> Custom Event Types > Add New) or use an existing one. Select this event in the drop down list. This feature may be turned off by selecting ‘Do not Track’
 -	<b>Your Database connection settings:</b>
  -	Host Name
  -	User Name
  -	Password
  -	Database Name
 -	<b>Deactivation:</b>  The plug-in creates some temporary tables in the Wordpress database to store changes submitted by the user. On Deactivation, there is an option to either
   - Do not Remove. Keep user data for future use, or
   -	Remove User Submitted data
The default is Do not remove. 

Select Remove User Submitted data if you are upgrading or permanently removing TngApi plug-in.
 
## Shortcodes
There are a number of useful shortcodes for you to play with. You can find them all in the plug-in>upavadi>shortcodes directory.
<table border="1">
<tr>
<td>1</td>
<td>[upavadi_pages_familysearch]</td><td>Shortcode used on a page to display results of  Name-Search Widget.</td>
</tr>
<tr>
<td>2</td>
<td>[upavadi_pages_familyuser]</td>
<td>Family page for Person</td>
</tr>
<tr>
<td>3</td>
<td>[upavadi_pages_familyform]</td>
<td>Update details of Person’s Family</td>
</tr>
<tr>
<td>4</td>
<td>[upavadi_pages_addfamilyform]</td>
<td>Add Details of Spouse, children and notes</td>
</tr>
<td>5</td>
<td>[upavadi_pages_personnotes]</td>
<td>Add Notes for Person</td>
</tr>		
<td>6</td>
<td>[upavadi_pages_birthdays]</td>
<td>Birthdays Report</td>
</tr>
<td>7</td>
<td>[upavadi_pages_manniversaries]</td>
<td>Marriage Anniversaries Report</td>
</tr>		
<td>8</td>
<td>[upavadi_pages_danniversaries]</td>
<td>Death Anniversaries Report</td>
</tr>		
<td>9</td>
<td>[upavadi_pages_submit-image]</td>
<td>Upload Image</td>
</tr>
<td>10</td>
<td>[upavadi_pages_userfamilysheet]</td>
<td>Display Pending Submissions submitted by the logged in user.</td>
</tr>
</table>

##Custom Shortcodes
 -	A custom shortcode directory is included with a sample shortcode to help you create and store your custom shortcodes.
 -	Copy (or move) the custom directory, tng-api-custom in to wp-content/plugins/.

By placing the directory outside the plugin, your custom shortcodes would not be overwritten on any future updates.
##Wordpress Pages

<table border="1">
<tr>
<td>1</td>
<td><i>search</i></td>
<td>To enable search widget, Family Search, to display data. Page can also be used for search</td>
<td>Required for the widget</td>
</tr>
<td>2</td>
<td><i>family</i></td>
<td>To enable search widget, Family Search, to display data. Page can also be used for search</td>
<td>Required for the widget</td>
</tr>
<td>3</td>
<td><i>thank-you</i></td>
<td>Submission Acknowledge Page. Displays Thank you message and details of changes submitted by the user</td>
<td>Required</td>
</tr>
<tr>
<td>4</td>
<td><i>events</i></td>
<td>An events page to display reports of birthdays, anniversaries and death anniversaries for the month. Give it an appropriate name.I have named this page, events.</td>
<td>Optional</td>
</tr>
<tr>
<td>5</td>
<td><i>images</i></td>
<td>An image upload page. Give it an appropriate name.</td>
<td>Optional</td>
</tr>
</table>


The <i>family</i> page may be populated with 5 shortcodes using the TAB shortcode. 
  - `[tabs]'[tab title="Family"]``[upavadi_pages_familyuser]``[/tab]`
  - `[tab title="Update Family"]``[upavadi_pages_familyform]``[/tab]`
  - `[tab title="Add Family"]``[upavadi_pages_addfamilyform]``[/tab]`
  - `[[tab title="Update Person Notes"]``[upavadi_pages_personnotes]``[/tab]`
  - `[tab title="Pending Submissions "]``[[upavadi_pages_userfamilysheet]``[/tab]`
  - `[/tabs]`

Advantage of placing these shortcodes is that all 5 shortcodes are synchronized to the  logged in user and same personID.
 
There are 3 shortcodes for displaying events for the current month. Place these 3 shortcodes on one page, using `tabs`. 
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
Profile image upload is included in the family page. Here the user does not need to enter any information. The profile image is saved with PersonID. An Email is generated to the Administrator on upload.

## Submitting Changes in Family Page
Once the User has submitted changes, a thank-you page is displayed which will show changes submitted by the user.  
 - create page named `thank-you` with your message you would like to be displayed, in this page.
 - Add `[upavadi_pages_userfamilysheet]` shortcode to display changes submitted by the user
 - A Thank You message is displayed and an email is sent to the administrator.

##Approve User Submissions
 - These are saved in temporary Wordpress tables. Administrator can view each submission, approve and then transfer the changes to the TNG database.
 - You are presented with a list of original values and changes. Here you would accept the changes you would like to implement. You also have an opportunity to modify most of the submitted changes.
 - Wordpress>admin>TNG Submits>Pending Submissions  displays number of outstanding submits.
Pending submissions gives a list of submissions with User name, affected person and date of submission. 
 - You are presented with a list of original values and changes. Here you would accept the changes you would like to implement. You also have an opportunity to modify most of the submitted changes.
 - Click on View to open the submission.

 - The page is divided in sections. You will have to go through each section and Accept changes you approve.
  - Person - Events and Notes ( Events are special event and cause of death )
  - Father – Mother and events
  - Spouse - Events and Notes.
  - Family
  - Children
    - A change is displayed with a flag. 
    - New data is displayed with a +. Here you have an opportunity to check and modify the submission. Accept>all will select this section for update.
- #####IMPORTANT
If you are selecting an event or Notes section for update, you must Accept ALL. As TNG stores this data differently, it is essential that all the data for the item is submitted.

 - Once you have checked the submission, you may Save Accepted Changes. 
Save Accepted Changes will transfer your selected changes to the TNG database. Your submission page should update to reflect the updated values.
 - Once you are happy with the change, you can click on Discard Submission to delete the user submission.

##Event Reports
There are 3 reports which can be placed in a Wordpress page.
If you would like to display these reports, create a page for the templates.
There are three shortcodes for Birthdays, Marriage Anniversaries and Death Anniversaries.
Each shortcode has Month and year selector. By placing these shortcodes in one page, month selection would apply to all 3 templates.
 -	`[upavadi_pages__birthdays]` Birthdays
 -	`[upavadi_pages__manniversaries]` Marriage Anniversaries
 -	`[upavadi_pages__danniversaries]` Death Anniversaries Report
Above reports use Person hyperlinks to the 'Family' page.
 - You may use `[tab]` shortcode to place these 3 events in one page.
 - `[tabs]
[tab title="Birthdays"][upavadi_pages_birthdays][/tab]
[tab title="Marriage Anniversaries"][upavadi_pages_manniversaries][/tab]
[tab title="DeathAnniversaries"][upavadi_pages_danniversaries][/tab]
[/tabs]`
## Patches & contributions
This is very much a project that can evolve so please feel free to fork and submit pull requests.
