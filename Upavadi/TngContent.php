<?php

class Upavadi_TngContent
{

    private static $instance = null;
    protected $db;
    protected $currentPerson;
    protected $tables = array();
    protected $sortBy = null;

    /**
     * @var Upavadi_Shortcode_AbstractShortcode[]
     */
    protected $shortcodes = array();

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

    /**
     * Add shortcodes
     */
    public function addShortcode(Upavadi_Shortcode_AbstractShortcode $shortcode)
    {
        $this->shortcodes[] = $shortcode;
    }

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

        $templates = new Upavadi_Templates();
        foreach ($this->shortcodes as $shortcode) {
            $shortcode->init($this, $templates);
        }
    }

    public function initTables()
    {
        $tngPath = esc_attr(get_option('tng-api-tng-path'));
        $configPath = $tngPath . DIRECTORY_SEPARATOR . "config.php";
        include $configPath;
        $vars = get_defined_vars();
        foreach ($vars as $name => $value) {
            if (preg_match('/_table$/', $name)) {
                $this->tables[$name] = $value;
            }
        }
    }

    public function init()
    {
        if ($this->db) {
            return $this;
        }

        if ($this->currentPerson) {
            return $this;
        }

        // get_currentuserinfo();


        $dbHost = esc_attr(get_option('tng-api-db-host'));
        $dbUser = esc_attr(get_option('tng-api-db-user'));
        $dbPassword = esc_attr(get_option('tng-api-db-password'));
        $dbName = esc_attr(get_option('tng-api-db-database'));

        $db = mysql_connect($dbHost, $dbUser, $dbPassword);
        mysql_select_db($dbName, $db);
        $this->db = $db;
        $this->initTables();

        $tng_user_name = $this->getTngUserName();
        $query = "SELECT * FROM {$this->tables['users_table']} WHERE username='{$tng_user_name}'";
        $result = mysql_query($query, $db) or die("Cannot execute query: $query");
        $row = mysql_fetch_assoc($result);

        $this->currentPerson = $row['personID'];
        return $this;
    }

    protected function query($sql)
    {
        $result = mysql_query($sql, $this->db) or die("Cannot execute query: $sql");
        return $result;
    }

    public function initAdmin()
    {
        register_setting('tng-api-options', 'tng-api-tng-path');
        register_setting('tng-api-options', 'tng-api-db-host');
        register_setting('tng-api-options', 'tng-api-db-user');
        register_setting('tng-api-options', 'tng-api-db-password');
        register_setting('tng-api-options', 'tng-api-db-database');

        add_settings_section('tng', 'TNG', function() {
            echo "Help goes here";
        }, 'tng-api');
        add_settings_field('tng-path', 'TNG Path', function () {
            $tngPath = esc_attr(get_option('tng-api-tng-path'));
            echo "<input type='text' name='tng-api-tng-path' value='$tngPath' />";
        }, 'tng-api', 'tng');
        add_settings_section('db', 'Database', function() {
            echo "Help goes here";
        }, 'tng-api');
        add_settings_field('db-host', 'Hostname', function () {
            $dbHost = esc_attr(get_option('tng-api-db-host'));
            echo "<input type='text' name='tng-api-db-host' value='$dbHost' />";
        }, 'tng-api', 'db');
        add_settings_field('db-user', 'Username', function () {
            $dbUser = esc_attr(get_option('tng-api-db-user'));
            echo "<input type='text' name='tng-api-db-user' value='$dbUser' />";
        }, 'tng-api', 'db');
        add_settings_field('db-password', 'Password', function () {
            $dbPassword = esc_attr(get_option('tng-api-db-password'));
            echo "<input type='password' name='tng-api-db-password' value='$dbPassword' />";
        }, 'tng-api', 'db');
        add_settings_field('db-database', 'Database Name', function () {
            $dbName = esc_attr(get_option('tng-api-db-database'));
            echo "<input type='text' name='tng-api-db-database' value='$dbName' />";
        }, 'tng-api', 'db');
    }

    public function adminMenu()
    {
        add_options_page(
            "Options", "TngApi", "manage_options", "tng-api", array($this, "pluginOptions")
        );
    }

    function pluginOptions()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        ?>
        <form method="POST" action="options.php">
            <?php
            settings_fields('tng-api-options'); //pass slug name of page, also referred
            //to in Settings API as option group name
            do_settings_sections('tng-api');  //pass slug name of page
            submit_button();
            ?>
        </form>
        <?php
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
       gedcom
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

    public function getDeathAnniversariesPlusOne()
    {
        return $this->getDeathAnniversaries('MONTH(ADDDATE(now(), INTERVAL 1 month))');
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

    //do shortcode Family user form
    public function showfamilyform()
    {
        ob_start();
        $personId = filter_input(INPUT_GET, 'personId', FILTER_SANITIZE_SPECIAL_CHARS);
        Upavadi_Pages::instance()->familyForm($personId);
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

    public function getTngUserName()
    {
        $currentUser = wp_get_current_user();
        $userName = $currentUser->user_login;
        $query = "SELECT username FROM {$this->tables['users_table']} WHERE username='{$userName}'";
        $result = $this->query($query);
        $row = mysql_fetch_assoc($result);
        if ($row) {
            return $row['username'];
        }
        wp_die('User ' . $userName . ' not found in TNG');
    }

    public function proxyFilter($posts)
    {
        $id = 4;
        $page = get_page($id);
        $link = parse_url(get_permalink($id));
        $uri = $_SERVER['REQUEST_URI'];

        if (strpos($uri, $link['path']) === 0) {
            $request = parse_url($uri);
            $uri = preg_replace("|^{$link['path']}|", '', $request['path']);
            if ($_SERVER['QUERY_STRING']) {
                $uri .= '?' . $_SERVER['QUERY_STRING'];
            }
            $basePath = 'http://localhost/tng/';
            $url = $basePath . $uri;

            
            $proxy = new Upavadi_TngProxy('gondal', '0cddfde984f24fac68f2d4ac468d3d6b', 'md5', 'C:wampwwwtng');
            $response = $proxy->load($url);
            
            if (!is_string($response)) {
                foreach ($response->getHeaderLines() as $header) {
                    header($header);
                }
                echo $response->getBody();
                exit;
            }
            $this->setHtml($response);
            $posts = array($page);
            add_filter('user_trailingslashit', function ($url) {
                return preg_replace('|/$|', '', $url);
            });
        }
        return $posts;
    }

    public function setHtml($html)
    {
        $this->html = $html;
    }

    public function getHtml()
    {
        return $this->html;
    }

}



if (!class_exists('TabsShortcodes')) :

class TabsShortcodes {

static $add_script;
static $tab_titles;
//private static $instance = null;


 

 
 function __construct() {

$basename = plugin_basename(__FILE__);

# Load text domain
load_plugin_textdomain('tabs_shortcodes', false, dirname($basename) . '/languages/');

# Register JavaScript
add_action('wp_enqueue_scripts', array(__CLASS__, 'register_script'));

# Add shortcodes
add_shortcode('tabs', array(__CLASS__, 'tabs_shortcode'));
add_shortcode('tab', array(__CLASS__, 'tab_shortcode'));

# Print script in wp_footer
add_action('wp_footer', array(__CLASS__, 'print_script'));

}

# Installation function
static function install() {

# Add notice option
add_option('tabs_shortcodes_notice', 1, '', 'no');

}




# Registers the minified tabs JavaScript file
static function register_script() {

$min = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
wp_register_script('tabs-shortcodes-script', plugins_url('tabs' . $min . '.js', __FILE__), array(), '1.1', true);

}

# Prints the minified tabs JavaScript file in the footer
static function print_script() {

# Check to see if shortcodes are used on page
if (!self::$add_script) return;

wp_enqueue_script('tabs-shortcodes-script');

}

# Tabs wrapper shortcode
static function tabs_shortcode($atts, $content = null) {

# The shortcode is used on the page, so we'll need to load the JavaScript
self::$add_script = true;

# Create empty titles array
self::$tab_titles = array();

extract(shortcode_atts(array(), $atts, 'tabs'));

# Get all individual tabs content
$tab_content = do_shortcode($content);

# Start the tab navigation
$out = '<ul id="tabs" class="tabs">';

# Loop through tab titles
foreach (self::$tab_titles as $key => $title) {
$id = $key + 1;
$out .= sprintf('<li><a href="#%s"%s>%s</a></li>',
'tab-' . $id,
$id == 1 ? ' class="active"' : '',
$title
);
}

# Close the tab navigation container and add tab content
$out .= '</ul>';
$out .= $tab_content;

return $out;

}

# Tab item shortcode
static function tab_shortcode($atts, $content = null) {

extract(shortcode_atts(array(
'title' => ''
), $atts, 'tab'));

# Add the title to the titles array
array_push(self::$tab_titles, $title);

$id = count(self::$tab_titles);

return sprintf('<section id="%s" class="tab%s">%s</section>',
'tab-' . $id,
$id == 1 ? ' active' : '',
do_shortcode($content)
);

}



}


endif;

?>