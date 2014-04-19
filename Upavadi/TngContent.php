<?php

class Upavadi_TngContent
{

    private static $instance = null;

    protected $db;

    protected $currentPerson;

    protected $tables = array();

    protected $sortBy = null;

    protected function __construct()
    {
        
    }

    public static function instance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

//Shortcodes - Add
    public function initPlugin()
    {
        add_shortcode('upavadi_getuser', array($this, 'showUser'));
        add_shortcode('upavadi_getuserfamily', array($this, 'showUserfamily'));
        add_shortcode('upavadi_getuserchildren', array($this, 'showUserchildren'));
        add_shortcode('upavadi_pages_birthdays', array($this, 'showBirthdays'));
        add_shortcode('upavadi_pages_birthdaysplusone', array($this, 'showBirthdaysplusone'));
        add_shortcode('upavadi_pages_birthdaysplustwo', array($this, 'showBirthdaysplustwo'));
        add_shortcode('upavadi_pages_birthdaysplusthree', array($this, 'showBirthdaysplusthree'));
        add_shortcode('upavadi_pages_birthdaysplustwo', array($this, 'showBirthdaysplustwo'));
        add_shortcode('upavadi_pages_birthdaysplusthree', array($this, 'showBirthdaysplusthree'));
        add_shortcode('upavadi_pages_manniversaries', array($this, 'showmanniversaries'));
        add_shortcode('upavadi_pages_manniversariesplusone', array($this, 'showmanniversariesplusone'));
        add_shortcode('upavadi_pages_manniversariesplustwo', array($this, 'showmanniversariesplustwo'));
        add_shortcode('upavadi_pages_manniversariesplusthree', array($this, 'showmanniversariesplusthree'));
        add_shortcode('upavadi_pages_danniversaries', array($this, 'showdanniversaries'));
        add_shortcode('upavadi_pages_danniversariesplusone', array($this, 'showdanniversariesplusone'));
        add_shortcode('upavadi_pages_danniversariesplustwo', array($this, 'showdanniversariesplustwo'));
        add_shortcode('upavadi_pages_danniversariesplusthree', array($this, 'showdanniversariesplusthree'));
        add_shortcode('upavadi_pages_familyuser', array($this, 'showfamilyuser'));
        add_shortcode('upavadi_pages_familyform', array($this, 'showfamilyform'));
        add_shortcode('upavadi_pages_addfamilyform', array($this, 'showaddfamilyform'));
        add_shortcode('upavadi_pages_personnotes', array($this, 'showpersonnotes'));
        add_shortcode('upavadi_pages_familysearch', array($this, 'showSearch'));
    }

    public function initTables()
    {
        foreach ($GLOBALS as $name => $value) {
            if (preg_match('/_table$/', $name)) {
                $this->tables[$name] = $value;
            }
        }
    }

    public function init()
    {
        global $current_user;

        if ($this->currentPerson) {
            return $this;
        }

        $tng_folder = get_option('mbtng_path');
        $origin = getcwd();

        get_currentuserinfo();
        $tng_user_name = mbtng_check_user($current_user->ID);
        $db = mbtng_db_connect() or exit;
        chdir($origin);
        $this->initTables();
        $query = "SELECT * FROM {$this->tables['users_table']} WHERE username='{$tng_user_name}'";
        $result = mysql_query($query, $db) or die("Cannot execute query: $query");
        $row = mysql_fetch_assoc($result);

        $this->db = $db;
        $this->currentPerson = $row['personID'];
        return $this;
    }

    protected function query($sql)
    {
        $result = mysql_query($sql, $this->db) or die("Cannot execute query: $sql");
        return $result;
    }

    public function showUser()
    {
        $user = $this->getPerson();
        return print_r($user, true);
    }

    public function showUserfamily()
    {
        $user = $this->getFamily();
        return print_r($user, true);
    }

    public function showUserchildren()
    {
        $user = $this->getChildren();
        return print_r($user, true);
    }

    public function getCurrentPersonId()
    {
        return $this->currentPerson;
    }

    public function getPerson($personId = null)
    {
        if (!$personId) {
            $personId = $this->currentPerson;
        }

        $sql = <<<SQL
SELECT *
FROM {$this->tables['people_table']}
WHERE personID = '{$personId}'
SQL;
        $result = $this->query($sql);
        $row = mysql_fetch_assoc($result);

        return $row;
    }

    public function getFamily($personId = null)
    {

        if (!$personId) {
            $personId = $this->currentPerson;
        }

        $sql = <<<SQL
SELECT *
FROM {$this->tables['families_table']}
WHERE husband = '{$personId}' or wife = '{$personId}'
SQL;
        $result = $this->query($sql);
        $row = mysql_fetch_assoc($result);

        return $row;
    }

    public function getGotra($personId = null)
    {

        if (!$personId) {
            $personId = $this->currentPerson;
        }

        $sql = <<<SQL
		SELECT *
FROM {$this->tables['events_table']}
where persfamID = '{$personId}' AND eventtypeID = "10"
SQL;
        $result = $this->query($sql);
        $row = mysql_fetch_assoc($result);

        return $row;
    }

    public function getFamilyById($familyId)
    {
        $sql = <<<SQL
SELECT *
FROM {$this->tables['families_table']}
WHERE familyID = '{$familyId}'
SQL;

        $result = $this->query($sql);
        $row = mysql_fetch_assoc($result);

        return $row;
    }

    public function getNotes($personId = null)
    {
        if (!$personId) {
            $personId = $this->currentPerson;
        }

        $sql = <<<SQL
SELECT *
FROM   {$this->tables['notelinks_table']} as nl
    LEFT JOIN {$this->tables['xnotes_table']} AS xl
              ON nl.ID = xl.ID
where persfamID = '{$personId}' AND secret="0"
       
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getDefaultMedia($personId = null)
    {

        if (!$personId) {
            $personId = $this->currentPerson;
        }

        $sql = <<<SQL
		SELECT *
FROM {$this->tables['medialinks_table']}
JOIN {$this->tables['media_table']} USING (mediaID)
where personID = '{$personId}' AND defphoto = "1"
SQL;
        $result = $this->query($sql);
        $row = mysql_fetch_assoc($result);

        return $row;
    }

    public function getAllPersonMedia($personId = null)
    {

        if (!$personId) {
            $personId = $this->currentPerson;
        }

        $sql = <<<SQL
SELECT *
FROM   {$this->tables['medialinks_table']} as ml
    LEFT JOIN {$this->tables['media_table']} AS m
              ON ml.mediaID = m.mediaID
where personID = '{$personId}' AND defphoto <> 1
       
ORDER  BY ml.ordernum
          
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getChildren($familyId = null)
    {

        if (!$familyId) {
            return array();
        }

        $sql = <<<SQL
	SELECT *
FROM {$this->tables['children_table']}
WHERE familyID = '{$familyId}'
ORDER BY ordernum
SQL;
        $result = $this->query($sql);

        $rows = array();

        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getFamilyUser($personId = null, $sortBy = null)
    {

        if (!$personId) {
            $personId = $this->currentPerson;
        }


        $sql = <<<SQL
SELECT*
		
	
FROM {$this->tables['families_table']}

WHERE (husband = '{$personId}' or wife = '{$personId}')
SQL;
        $result = $this->query($sql);
        $rows = array();

        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        if ($sortBy) {
            $this->sortBy = $sortBy;
            usort($rows, array($this, 'sortRows'));
        }
        return $rows;
    }

    public function sortRows($a, $b)
    {
        if ($a[$this->sortBy] > $b[$this->sortBy]) {
            return 1;
        }
        if ($a[$this->sortBy] < $b[$this->sortBy]) {
            return -1;
        }
        return 0;
    }

    public function getBirthdaysPlusOne($month)
    {
        $sql = <<<SQL
SELECT personid,
       firstname,
       lastname,
       birthdate,
       birthplace,
       gedcom,
       Year(Now()) - Year(birthdatetr) AS Age
FROM   {$this->tables['people_table']}
WHERE  Month(birthdatetr) = MONTH(ADDDATE(now(), INTERVAL 1 month))
       AND living = 1
ORDER  BY Day(birthdatetr),
          lastname
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getBirthdaysPlusTwo($month)
    {
        $sql = <<<SQL
SELECT personid,
       firstname,
       lastname,
       birthdate,
       birthplace,
       gedcom,
       Year(Now()) - Year(birthdatetr) AS Age
FROM   {$this->tables['people_table']}
WHERE  Month(birthdatetr) = MONTH(ADDDATE(now(), INTERVAL 2 month))
       AND living = 1
ORDER  BY Day(birthdatetr),
          lastname
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getBirthdaysPlusThree($month)
    {
        $sql = <<<SQL
SELECT personid,
       firstname,
       lastname,
       birthdate,
       birthplace,
       gedcom,
       Year(Now()) - Year(birthdatetr) AS Age
FROM   {$this->tables['people_table']}
WHERE  Month(birthdatetr) = MONTH(ADDDATE(now(), INTERVAL 3 month))
       AND living = 1
ORDER  BY Day(birthdatetr),
          lastname
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getBirthdays($month)
    {
        $sql = <<<SQL
SELECT personid,
       firstname,
       lastname,
       birthdate,
       birthplace,
       gedcom,
       Year(Now()) - Year(birthdatetr) AS Age
FROM   {$this->tables['people_table']}
WHERE  Month(birthdatetr) = {$month}
       AND living = 1
ORDER  BY Day(birthdatetr),
          lastname
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getDeathAnniversaries($month)
    {
        $sql = <<<SQL
SELECT personid,
       firstname,
       lastname,
       deathdate,
       deathplace,
       gedcom,
       Year(Now()) - Year(deathdatetr) AS Years
FROM   {$this->tables['people_table']}
WHERE  Month(deathdatetr) = {$month}
       AND living = 0
ORDER  BY Day(deathdatetr),
          lastname
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getDeathAnniversariesPlusOne($month)
    {
        $sql = <<<SQL
SELECT personid,
       firstname,
       lastname,
       deathdate,
       deathplace,
       gedcom,
       Year(Now()) - Year(deathdatetr) AS Years
FROM   {$this->tables['people_table']}
WHERE  Month(deathdatetr) = MONTH(ADDDATE(now(), INTERVAL 1 month))
       AND living = 0
ORDER  BY Day(deathdatetr),
          lastname
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getDeathAnniversariesPlusTwo($month)
    {
        $sql = <<<SQL
SELECT personid,
       firstname,
       lastname,
       deathdate,
       deathplace,
       gedcom,
       Year(Now()) - Year(deathdatetr) AS Years
FROM   {$this->tables['people_table']}
WHERE  Month(deathdatetr) = MONTH(ADDDATE(now(), INTERVAL 2 month))
       AND living = 0
ORDER  BY Day(deathdatetr),
          lastname
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getDeathAnniversariesPlusThree($month)
    {
        $sql = <<<SQL
SELECT personid,
       firstname,
       lastname,
       deathdate,
       deathplace,
       gedcom,
       Year(Now()) - Year(deathdatetr) AS Years
FROM   {$this->tables['people_table']}
WHERE  Month(deathdatetr) = MONTH(ADDDATE(now(), INTERVAL 3 month))
       AND living = 0
ORDER  BY Day(deathdatetr),
          lastname
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getMarriageAnniversaries($month)
    {
        $sql = <<<SQL
SELECT h.gedcom,
	   h.personid AS personid1,
       h.firstname AS firstname1,
       h.lastname AS lastname1,
       w.personid AS personid2,
       w.firstname AS firstname2,
       w.lastname AS lastname2,
	   f.familyID,
       f.marrdate,
       f.marrplace,
       Year(Now()) - Year(marrdatetr) AS Years
FROM   {$this->tables['families_table']} as f
    LEFT JOIN {$this->tables['people_table']} AS h
              ON f.husband = h.personid
       LEFT JOIN {$this->tables['people_table']} AS w
              ON f.wife = w.personid
WHERE  Month(f.marrdatetr) = {$month}
       
ORDER  BY Day(f.marrdatetr)
          
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getMarriageAnniversariesPlusOne($month)
    {
        $sql = <<<SQL
SELECT h.gedcom,
	   h.personid AS personid1,
       h.firstname AS firstname1,
       h.lastname AS lastname1,
       w.personid AS personid2,
       w.firstname AS firstname2,
       w.lastname AS lastname2,
	   f.familyID,
       f.marrdate,
       f.marrplace,
       Year(Now()) - Year(marrdatetr) AS Years
FROM   {$this->tables['families_table']} as f
    LEFT JOIN {$this->tables['people_table']} AS h
              ON f.husband = h.personid
       LEFT JOIN {$this->tables['people_table']} AS w
              ON f.wife = w.personid
WHERE  Month(f.marrdatetr) = MONTH(ADDDATE(now(), INTERVAL 1 month))
       
ORDER  BY Day(f.marrdatetr)
          
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getmanniversariesplustwo($month)
    {
        $sql = <<<SQL
SELECT h.gedcom,
	   h.personid AS personid1,
       h.firstname AS firstname1,
       h.lastname AS lastname1,
       w.personid AS personid2,
       w.firstname AS firstname2,
       w.lastname AS lastname2,
	   f.familyID,
       f.marrdate,
       f.marrplace,
       Year(Now()) - Year(marrdatetr) AS Years
FROM   {$this->tables['families_table']} as f
    LEFT JOIN {$this->tables['people_table']} AS h
              ON f.husband = h.personid
       LEFT JOIN {$this->tables['people_table']} AS w
              ON f.wife = w.personid
WHERE  Month(f.marrdatetr) = MONTH(ADDDATE(now(), INTERVAL 2 month))
       
ORDER  BY Day(f.marrdatetr)
          
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getmanniversariesplusthree($month)
    {
        $sql = <<<SQL
SELECT h.gedcom,
	   h.personid AS personid1,
       h.firstname AS firstname1,
       h.lastname AS lastname1,
       w.personid AS personid2,
       w.firstname AS firstname2,
       w.lastname AS lastname2,
	   f.familyID,
       f.marrdate,
       f.marrplace,
       Year(Now()) - Year(marrdatetr) AS Years
FROM   {$this->tables['families_table']} as f
    LEFT JOIN {$this->tables['people_table']} AS h
              ON f.husband = h.personid
       LEFT JOIN {$this->tables['people_table']} AS w
              ON f.wife = w.personid
WHERE  Month(f.marrdatetr) = MONTH(ADDDATE(now(), INTERVAL 3 month))
       
ORDER  BY Day(f.marrdatetr)
          
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    //do shortcode birthdays
    public function showBirthdays()
    {
        ob_start();
        Upavadi_Pages::instance()->birthdays();
        return ob_get_clean();
    }

    //do shortcode birthdays Next month

    public function showBirthdaysplusone()
    {
        ob_start();
        Upavadi_Pages::instance()->birthdaysplusone();
        return ob_get_clean();
    }

    public function showBirthdaysplustwo()
    {
        ob_start();
        Upavadi_Pages::instance()->birthdaysplustwo();
        return ob_get_clean();
    }

    public function showBirthdaysplusthree()
    {
        ob_start();
        Upavadi_Pages::instance()->birthdaysplusthree();
        return ob_get_clean();
    }

//do shortcode Marriage anniversaries
    public function showmanniversaries()
    {
        ob_start();
        Upavadi_Pages::instance()->manniversaries();
        return ob_get_clean();
    }

    //do shortcode Death anniversaries
    public function showdanniversaries()
    {
        ob_start();
        Upavadi_Pages::instance()->danniversaries();
        return ob_get_clean();
    }

    //do shortcode Marriage anniversaries plus one
    public function showmanniversariesplusone()
    {
        ob_start();
        Upavadi_Pages::instance()->manniversariesplusone();
        return ob_get_clean();
    }

    public function showmanniversariesplustwo()
    {
        ob_start();
        Upavadi_Pages::instance()->manniversariesplustwo();
        return ob_get_clean();
    }

    public function showmanniversariesplusthree()
    {
        ob_start();
        Upavadi_Pages::instance()->manniversariesplusthree();
        return ob_get_clean();
    }

    //do shortcode Death anniversaries plus one
    public function showdanniversariesplusone()
    {
        ob_start();
        Upavadi_Pages::instance()->danniversariesplusone();
        return ob_get_clean();
    }

    public function showdanniversariesplustwo()
    {
        ob_start();
        Upavadi_Pages::instance()->danniversariesplustwo();
        return ob_get_clean();
    }

    public function showdanniversariesplusthree()
    {
        ob_start();
        Upavadi_Pages::instance()->danniversariesplusthree();
        return ob_get_clean();
    }

    //do shortcode Family user
    public function showfamilyuser()
    {
        ob_start();
        $personId = filter_input(INPUT_GET, 'personId', FILTER_SANITIZE_SPECIAL_CHARS);
        Upavadi_Pages::instance()->familyuser($personId);
        return ob_get_clean();
    }

    //do shortcode Person Notes
    public function showpersonnotes()
    {
        ob_start();
        $personId = filter_input(INPUT_GET, 'personId', FILTER_SANITIZE_SPECIAL_CHARS);

        Upavadi_Pages::instance()->personnotes($personId);
        return ob_get_clean();
    }

    //do shortcode Family user form
    public function showfamilyform()
    {
        ob_start();
        $personId = filter_input(INPUT_GET, 'personId', FILTER_SANITIZE_SPECIAL_CHARS);
        Upavadi_Pages::instance()->familyForm($personId);
        return ob_get_clean();
    }

    //do shortcode Add Family form
    public function showaddfamilyform()
    {
        ob_start();
        $personId = filter_input(INPUT_GET, 'personId', FILTER_SANITIZE_SPECIAL_CHARS);
        Upavadi_Pages::instance()->addfamilyForm($personId);
        return ob_get_clean();
    }

    public function searchPerson($searchFirstName, $searchLastName)
    {
        $wheres = array();
        if ($searchFirstName) {
            $wheres[] = "firstname LIKE '%{$searchFirstName}%'";
        }
        if ($searchLastName) {
            $wheres[] = "lastname LIKE '{$searchLastName}%'";
        }

        $rows = array();
        $where = null;
        if (count($wheres)) {
            $where = 'WHERE ' . implode(' AND ', $wheres);
        }
        $sql = <<<SQL
SELECT *
FROM {$this->tables['people_table']}
{$where}
ORDER BY firstname, lastname
LIMIT 100
SQL;

        $result = $this->query($sql);

        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function showSearch()
    {
        ob_start();
        $searchFirstName = filter_input(INPUT_GET, 'firstName', FILTER_SANITIZE_SPECIAL_CHARS);
        $searchLastName = filter_input(INPUT_GET, 'lastName', FILTER_SANITIZE_SPECIAL_CHARS);

        Upavadi_Pages::instance()->search($searchFirstName, $searchLastName);
        return ob_get_clean();
    }

}

function widget_familysearch_init()
{

    // Check for the required plugin functions. This will prevent fatal
    // errors occurring when you deactivate the dynamic-sidebar plugin.
    if (!function_exists('register_sidebar_widget'))
        return;

    // This is the function that outputs the TNG search form.
    function widget_familysearch($args)
    {

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
            $url_parts = parse_url(get_bloginfo('home'));
            ?>
            <div><form action="/search" style="display: inline-block;" method="get">
                    <label for="top-search-lastname">Last Name: <input style="width: 100px; height: 14px; font-family: Arial, Helvetica, Tahoma" type="text" value="<?php echo $lastName; ?>" name="lastName" id="top-search-lastname"></label> 
                    <label for="top-search-firstname">First Name: <input  style="width: 100px; height: 14px; font-family: Arial, Helvetica, Tahoma" type="text" value="<?php echo $firstName; ?>" name="firstName" id="top-search-firstname"></label>
                    <input type="submit" style="margin: 4px 0 5px;" value="Search Tree">
                </form></div>


            <?php
            echo $after_widget;
        }
    }

    // This is the function that outputs the form to let the users edit
    // the widget's title. 
    function widget_familysearch_control()
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

    // This registers our widget so it appears with the other available
    // widgets and can be dragged and dropped into any active sidebars.
    register_sidebar_widget(array('Family Search', 'widgets'), 'widget_familysearch');

    // This registers our optional widget control form. Because of this
    // our widget will have a button that reveals a 300x100 pixel form.
    register_widget_control(array('Family Search', 'widgets'), 'widget_familysearch_control', 300, 100);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_familysearch_init');
?>