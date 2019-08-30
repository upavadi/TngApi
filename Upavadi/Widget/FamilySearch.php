<?php
/***************************************************************
Adds a sidebar widget to let users search TNG Database for names
****************************************************************/
class Upavadi_Widget_FamilySearch implements Upavadi_Widget_WidgetInterface
{

    public function init()
    {

        // Check for the required plugin functions. This will prevent fatal
        // errors occurring when you deactivate the dynamic-sidebar plugin.
        if (!function_exists('wp_register_sidebar_widget'))
            return;

        // Run our code later in case this loads prior to any required plugins.
        // This registers our widget so it appears with the other available
        // widgets and can be dragged and dropped into any active sidebars.
    /** change below line to
        wp_register_sidebar_widget(__CLASS__ . '_widget', array('Family Search', 'widgets'), array($this, 'familySearch'));
    and same for widget_control    
    ***/    
         wp_register_sidebar_widget( __CLASS__ . '_widget', 'Family Search', array($this, 'familySearch'));

        // This registers our optional widget control form. Because of this
        // our widget will have a button that reveals a 300x100 pixel form.
        wp_register_widget_control(__CLASS__ . '_control', 'Family Search', array($this, 'familySearchControl'), 300, 100);
    }

// This is the function that outputs the TNG search form.
    function familySearch($args)
    {
global $lastName, $firstName;
// $args is an array of strings that help widgets to conform to
// the active theme: before_widget, before_title, after_widget,
// and after_title are the array keys. Default tags: li and h2.
        extract($args);

// Each widget can store its own options. We keep strings here.
        $options = get_option('widget_familysearch');
        $title = $options['title'];
        $results = $options['results'];

// These lines generate our output. Adjust the form action path to the path of your own site.
// Adjust the style to the style of your own Wordpress site

        if (is_user_logged_in()) {
            echo $before_widget . $before_title . $title . $after_title;
            $url_parts = parse_url(get_bloginfo('url'));
            ?>
            <div><form action="/search" style="display: inline-block;" method="get">
                    <label for="top-search-lastname">Last Name: <input style="width: 100px; height: 20px; font-family: Arial, Helvetica, Tahoma" type="text" value="<?php echo $lastName; ?>" name="lastName" id="top-search-lastname"></label> 
                    <label for="top-search-firstname">First Name: <input  style="width: 100px; height: 20px; font-family: Arial, Helvetica, Tahoma" type="text" value="<?php echo $firstName; ?>" name="firstName" id="top-search-firstname"></label>
                    <input type="submit" style="margin: 4px 0 5px;" value="Search Tree">
                </form></div>


            <?php
            echo $after_widget;
        }
    }

// This is the function that outputs the form to let the users edit
// the widget's title. 
    public function familySearchControl()
    {

// Get our options and see if we're handling a form submission.
        $options = get_option('widget_familysearch');
        if (!is_array($options))
            $options = array('title' => '', 'results' => __('Family Search', 'widgets'));
        if ($_POST['familysearch-submit']) {

// Remember to sanitize and format use input appropriately.
            $options['title'] = strip_tags(stripslashes($_POST['familysearch-title']));
            $options['results'] = strip_tags(stripslashes($_POST['familysearch-results']));
            update_option('widget_familysearch', $options);
        }

// Be sure you format your options to be valid HTML attributes.
        $title = htmlspecialchars($options['title'], ENT_QUOTES);
        $results = htmlspecialchars($options['results'], ENT_QUOTES);

// Here is our little form segment. Notice that we don't need a
// complete form. This will be embedded into the existing form.
//echo '<p style="text-align:right;"><label for="familysearch-title">' . __('Title:') . ' <input style="width: 200px;" id="familysearch-title" name="familysearch-title" type="text" value="'.$title.'" /></label></p>';
//echo '<p style="text-align:right;"><label for="familysearch-results">' . __('Button Text:', 'widgets') . ' <input style="width: 200px;" id="familysearch-results" name="familysearch-results" type="text" value="'.$results.'" /></label></p>';
//echo '<input type="hidden" id="familysearch-submit" name="familysearch-submit" value="1" />';
    }

}
