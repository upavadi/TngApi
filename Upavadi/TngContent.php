<?php

class Upavadi_TngContent
{

    private static $instance = null;
    protected $db;
    protected $currentPerson;
    protected $tables = array();
    protected $sortBy = null;
    protected $tree;
    protected $custom;

    /**
     * @var Upavadi_Shortcode_AbstractShortcode[]
     */
    protected $shortcodes = array();
    protected $domain;
    private $tngUser;

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

    public function setCustom($custom)
    {
        $this->custom = $custom;
    }

    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * Add shortcodes
     */
    public function addShortcode(Upavadi_Shortcode_AbstractShortcode $shortcode)
    {
        $this->shortcodes[] = $shortcode;
    }

    public function initVariables()
    {
        //check for TNG Path
        $path = esc_attr(get_option('tng-api-tng-path'));
        $configPath = $path . DIRECTORY_SEPARATOR . "config.php";
        if (!file_exists($configPath)) {
            $e = new Exception('TNG Path not found');
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            echo "TNG Path not Specified";
        //  Display admin message if tng path not specified
            add_action( 'admin_notices', array($this, 'pathNotSpecified') );
            return;
        }

        //check Database
        $dbHost = esc_attr(get_option('tng-api-db-host'));
        $dbUser = esc_attr(get_option('tng-api-db-user'));
        $dbPassword = esc_attr(get_option('tng-api-db-password'));
        $dbName = esc_attr(get_option('tng-api-db-database'));
        $EventID = esc_attr(get_option('tng-api-tng-event'));
        try {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);  //DB error reporting
        $db = @mysqli_connect($dbHost, $dbUser, $dbPassword,$dbName );
        } catch (exception $e) {
        error_log($e->getMessage());
        error_log($e->getTraceAsString());
        echo "DB not configured. Please contact the administrator (80)";
        add_action( 'admin_notices', array($this, 'dbNotSpecified') );
        return;
        } 
   
    }

    public function initPlugin()
    {
        $templates = new Upavadi_Templates();
        foreach ($this->shortcodes as $shortcode) {
            $shortcode->init($this, $templates);
        }
    }

    public function getTngPath()
    {
        $path = esc_attr(get_option('tng-api-tng-path'));
        if (!file_exists($path)) {
            add_action( 'admin_notices', array($this, 'pathNotSpecified'));
        }
        return $path;
    }
	public function getTngUrl()
    {
        return esc_attr(get_option('tng-api-tng-url'));
    }

    public function getTngIntegrationPath()
    {
        return esc_attr(get_option('tng-base-tng-path'));
    }
	public function getTngPhotoFolder()
    {
        return esc_attr(get_option('tng-api-tng-photo-folder'));
    }
	public function getTngShowButtons()
    {
        return esc_attr(get_option('tng-api-display-buttons'));
    }

    public function getTngTables()
    {
        return $this->tables;
    }

    public function initTables()
    {
        $tngPath = $this->getTngPath();
        $configPath = $tngPath . DIRECTORY_SEPARATOR . "config.php";
        if (!file_exists($configPath)) {
           //throw new DomainException('Could not find TNG config file');
           $e = new DomainException('TNG Path not found');
           error_log($e->getMessage());
           error_log($e->getTraceAsString());
           echo "TNG Path not found (" . __LINE__ . ")";
       //  Display admin message if tng path not specified
           add_action( 'admin_notices', array($this, 'pathNotSpecified') );
           return;
        }
        include $configPath;
        $vars = get_defined_vars();
        foreach ($vars as $name => $value) {
            if (preg_match('/_table$/', $name)) {
                $this->tables[$name] = $value;
            }
            if (preg_match('/tngdomain$/', $name)) {
                $this->domain = $value;
            }
        }
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function guessVersion()
    {
        $sql = 'describe ' . $this->tables['people_table'];
        $result = $this->query($sql);
        $version = 9;
        while ($row = $result->fetch_assoc()) {
            if ($row['Field'] == 'burialtype') {
                $version = 10;
                break;
            }
        }
        while ($row = $result->fetch_assoc()) {	
            if ($row['Field'] == 'languageID') {
                $version = 11;
                break;
            }
        }
    
        while ($row = $result->fetch_assoc()) {	
            if ($row['Field'] == 'dt_consented') {
                $version = 12;
                break;
            }
        }
        return $version;
    }

    /**
     * @return mysqli
     */
    public function getDbLink()
    {
        return $this->db;
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
            $EventID = esc_attr(get_option('tng-api-tng-event'));
            try {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);  //DB error reporting
                $db = mysqli_connect($dbHost, $dbUser, $dbPassword);
                mysqli_select_db($db, $dbName);
            } catch (exception $e) {
                error_log($e->getMessage());
                error_log($e->getTraceAsString());
                echo "DB not configured. Please contact the administrator (203)";
                add_action( 'admin_notices', array($this, 'dbNotSpecified') );
                return;
                } 
           // mysqli_select_db($db, $dbName);
            $this->db = $db; // added
            $this->initTables(); 

            if (!isset($this->tables['users_table'])) { 
                return $this;
            }

            
            $tng_user_name = $this->getTngUserName();
            $query = "SELECT * FROM {$this->tables['users_table']} WHERE username='{$tng_user_name}'";
            $result = mysqli_query($db, $query);
            if (!$result) {
             //   throw new RuntimeException(mysqli_error($this->db));
            }
            $row = $result->fetch_assoc();
            $this->currentPerson = $row['personID'];
            $this->db = $db;
            return $this;

        
    }

    public function query($sql)
    {
        if (!$this->db) {
            
            throw new RuntimeException("No DB configured please contact the administrator(233)");
        }
        $result = mysqli_query($this->db, $sql);
        if (!$result) {
            throw new RuntimeException(mysqli_error($this->db));
        }
        return $result;
    }

    public function initAdmin()
    {
	// To covert to multisite, read here:
	// 	http://wordpress.stackexchange.com/questions/166484/get-option-compatible-with-wordpress-network-multisite
        register_setting('tng-api-options', 'tng-api-email');
        register_setting('tng-api-options', 'tng-api-tng-event');
        register_setting('tng-api-options', 'tng-api-tng-page-id');
        register_setting('tng-api-options', 'tng-api-tng-path');
        register_setting('tng-api-options', 'tng-api-tng-url');
        register_setting('tng-api-options', 'tng-base-tng-path');
        register_setting('tng-api-options', 'tng-api-tng-photo-folder');
        register_setting('tng-api-options', 'tng-api-tng-photo-upload');
        register_setting('tng-api-options', 'tng-api-db-host');
        register_setting('tng-api-options', 'tng-api-db-user');
        register_setting('tng-api-options', 'tng-api-db-password');
        register_setting('tng-api-options', 'tng-api-db-database');
        register_setting('tng-api-options', 'tng-api-drop-table');
		register_setting('tng-api-options', 'tng-api-display-buttons');
        add_settings_section('general', 'General', function() {

        }, 'tng-api');

        add_settings_field('tng-email', 'Notification Email Address', function () {
            $tngEmail = esc_attr(get_option('tng-api-email'));
            echo "<input type='text' name='tng-api-email' value='$tngEmail' />";
        }, 'tng-api', 'general');
        add_settings_section('tng', 'TNG', function() {
            echo "In order for the plugin to work we need to know where the original TNG source files live";
        }, 'tng-api');
        add_settings_field('tng-path', 'TNG Path', function () {
            $tngPath = esc_attr(get_option('tng-api-tng-path'));
            echo "<input type='text' name='tng-api-tng-path' value='$tngPath' /> <br />Root Path as set in TNG Admin>Setup";
        }, 'tng-api', 'tng');
        add_settings_field('tng-url', 'URL to TNG Folder', function () {
            $tngPath = esc_attr(get_option('tng-api-tng-url'));
            echo "<input type='text' name='tng-api-tng-url' value='$tngPath' /><br />This is the url to TNG folder.<br /> It will be of the form http://yoursite.com/TNG folder name/
			";
        }, 'tng-api', 'tng');
       add_settings_field('tng-base-path', 'TNG Integration Path', function () {
            $tngPath = esc_attr(get_option('tng-base-tng-path'));
            echo "<input type='text' name='tng-base-tng-path' value='$tngPath' /><br />Enter TNG folder name here. If you are using TNG Wordpress Integration by Mark Barnes, 
			enter the name of the page you have specified to display TNG pages within Wordpress 
			container.
			";
        }, 'tng-api', 'tng');
        add_settings_field('tng-photo-folder', 'TNG Photo Folder', function () {
            $tngphotofolder = esc_attr(get_option('tng-api-tng-photo-folder'));
            if ($tngphotofolder == '') {
			$tngphotofolder = "photos";
			}
			echo "<input type='text' name='tng-api-tng-photo-folder' value='$tngphotofolder' /><br />Enter the name of the folder to use to get media from TNG. Default is photos";
        }, 'tng-api', 'tng');
        add_settings_field('tng-photo-upload', 'TNG Collection ID for Photo Uploads', function () {
            $tngPath = esc_attr(get_option('tng-api-tng-photo-upload'));
            echo "<input type='text' name='tng-api-tng-photo-upload' value='$tngPath' /><br />User images are uploaded in to one of TNG folders with the collection name specified by you in the 
			admin set up. Enter the name for the collection you have set up in TNG admin > media. Mine is called “My Uploads”.
			";
        }, 'tng-api', 'tng');
        $this->init();
        $events = $this->getEventList();
        add_settings_field('tng-event', 'TNG Event to Track', function () use ($events) {
            $tngEvent = esc_attr(get_option('tng-api-tng-event'));

            echo '<select name="tng-api-tng-event">';
            echo '<option value="">Do not Track</option>';

            foreach ($events as $event) {
                $eventId = $event['eventtypeID'];
                $selected = null;
                if ($eventId == $tngEvent) {

                    $selected = "selected='selected'";
                }
                echo "<option value='$eventId' $selected>{$event['display']}</option>";
            }
            echo '</select><br />';
			echo "if you would like to track a customized field or event, you may create 
			this as a special event type (TNG Admin>Custom Event Types > Add New) or use an existing 
			one. <br />Select this event in the drop down list. This feature may be turned off by selecting <b>
			Do not Track</b>";
			}, 'tng-api', 'tng');
		add_settings_field('display-buttons', 'Display Buttons', function () {
            $display = esc_attr(get_option('tng-api-display-buttons'));
            if (empty($display)) {
                $display = '0';
            }
            $checked = checked('1', $display, false);
            $input = <<<HTML
    <input
        type='checkbox'
        name='tng-api-display-buttons'
        value='1'
        {$checked}
    />
HTML;
            echo $input;
			echo "<br />Three buttons, My Genealogy Page, My Ancestors and My Descendents are links to TNG pages for 
			the person displayed on Family Page.";
        }, 'tng-api', 'tng');
           
	
		            
		add_settings_section('db', 'Database', function() {
            echo "We also need to know where the TNG database lives";
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

           
	   add_settings_section('table', 'Deactivate', function() {
            echo "On Deactivation, Remove ALL temporary tables storing User submissions.<br /> You may want to do this if you are upgrading or permanently removing TngApi plugin.";
        }, 'tng-api');
        add_settings_field('table-keep', 'Do not Remove. Keep data for future use', function () {
            $drop = esc_attr(get_option('tng-api-drop-table'));
            if (empty($drop)) {
                $drop = '0';
            }
            $checked = checked('0', $drop, false);
            $input = <<<HTML
    <input
        type='radio'
        name='tng-api-drop-table'
        value='0'
        {$checked}
    />
HTML;
            echo $input;
        }, 'tng-api', 'table');
        add_settings_field('table-drop', 'Remove User Submitted data', function () {
            $drop = esc_attr(get_option('tng-api-drop-table'));
            if (empty($drop)) {
                $drop = '0';
            }
            $checked = checked('1', $drop, false);
            $input = <<<HTML
    <input
        type='radio'
        name='tng-api-drop-table'
        value='1'
        {$checked}
    />
HTML;
            echo $input;
        }, 'tng-api', 'table');
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

    public function getCurrentPersonId()
    {
        return $this->currentPerson;
    }

    public function getPerson($personId = null, $tree = null)
    {
        if (!$personId) {
            $personId = $this->currentPerson;
        }

        $user = $this->getTngUser();

        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }

        if ($gedcom == '' && $tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND gedcom = "' . $gedcom . '"';
        }

        $sql = <<<SQL
SELECT *
FROM {$this->tables['people_table']}
WHERE personID = '{$personId}'
{$treeWhere}
SQL;
        $result = $this->query($sql);
        $row = $result->fetch_assoc();
        $userPrivate = $user['allow_private'];
        $personPrivate = $row['private'];
        if ($personPrivate > $userPrivate) {
            $row['firstname'] = 'Private:';
			$row['lastname'] = ' Details withheld';
        }
        return $row;
    }

    public function getFamily($personId = null, $tree = null)
    {

        if (!$personId) {
            $personId = $this->currentPerson;
        }
        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }

        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND gedcom = "' . $gedcom . '"';
        }
        $sql = <<<SQL
SELECT *
FROM {$this->tables['families_table']}
WHERE (husband = '{$personId}' or wife = '{$personId}') {$treeWhere}

SQL;
        $result = $this->query($sql);
        $row = $result->fetch_assoc();
        $userPrivate = $user['allow_private'];
        $familyPrivate = $row['private'];
        $personPrivate = $this->getPerson()['private'];
        if ($personPrivate > $userPrivate) {
            $row['marrdate'] = 'Private';
			$row['marrplace'] = ' Details withheld';
        }
        return $row;
    }

    /* Get Special events for ADMIN selection */

    function getEventList()
    {
        if (!isset($this->tables['eventtypes_table'])) {
            return array();
        }

        $sql = <<<SQL

SELECT *
FROM {$this->tables['eventtypes_table']}
ORDER BY display

SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $eventrows[] = $row;
        }

        return $eventrows;
    }

    /* Special event type 10 is called here */

    public function getSpEvent($personId = null, $tree = null)
    {

        if (!$personId) {
            $personId = $this->currentPerson;
        }
        $EventID = esc_attr(get_option('tng-api-tng-event'));
        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND gedcom = "' . $gedcom . '"';
        }
        $sql = <<<SQL

SELECT *
FROM {$this->tables['events_table']}
where persfamID = '{$personId}' AND eventtypeID = '$EventID' {$treeWhere}
SQL;
        $result = $this->query($sql);
        $row = $result->fetch_assoc();

        return $row;
    }

    /* Display for Special event tng-event display is called here */

    public function getEventDisplay()
    {
        $EventID = esc_attr(get_option('tng-api-tng-event'));
        $sql = <<<SQL

SELECT *
FROM {$this->tables['eventtypes_table']}
where eventtypeID = "$EventID"
SQL;
        $result = $this->query($sql);
        $row = $result->fetch_assoc();

        return $row;
    }

// Special event type 0 for Cause of Death
    public function getCause($personId = null, $tree = null)
    {

        if (!$personId) {
            $personId = $this->currentPerson;
        }
        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND gedcom = "' . $gedcom . '"';
        }
        $sql = <<<SQL

SELECT *
FROM {$this->tables['events_table']}
where persfamID = '{$personId}' AND eventtypeID = "o" AND parenttag = "DEAT" {$treeWhere}
SQL;
        $result = $this->query($sql);
        $row = $result->fetch_assoc();

        return $row;
    }

    public function getEvent($eventId, $tree = null)
    {
        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND gedcom = "' . $gedcom . '"';
        }
        $sql = <<<SQL

SELECT *
FROM {$this->tables['events_table']}
where eventID = '{$eventId}' {$treeWhere}
SQL;
        $result = $this->query($sql);
        $row = $result->fetch_assoc();

        return $row;
    }

    public function getFamilyById($familyId = null, $tree = null)
    {
        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND gedcom = "' . $gedcom . '"';
        }
        $sql = <<<SQL
SELECT *
FROM {$this->tables['families_table']}
WHERE familyID = '{$familyId}' {$treeWhere}
SQL;

        $result = $this->query($sql);
        $row = $result->fetch_assoc();

        return $row;
    }

    public function getChildFamily($personId, $familyId, $tree = null)
    {
        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND gedcom = "' . $gedcom . '"';
        }

        $sql = <<<SQL
SELECT *
FROM {$this->tables['children_table']}
WHERE personID = '{$personId}' AND familyID = '{$familyId}' {$treeWhere}
SQL;

        $result = $this->query($sql);
        $row = $result->fetch_assoc();
        return $row;
    }

    public function getNotes($personId = null, $tree = null)
    {
        if (!$personId) {
            $personId = $this->currentPerson;
        }
        $user = $this->getTngUser();
        $userPrivate = $user['allow_private'];

        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND n1.gedcom = "' . $gedcom . '"';
        }
        $person = $this->getPerson($personId, $gedcom);
        $personPrivate = $person['private'];

        $sql = <<<SQL
SELECT nl.ID as notelinkID, nl.*, xl.*
FROM   {$this->tables['notelinks_table']} as nl
LEFT JOIN {$this->tables['xnotes_table']} AS xl
ON nl.xnoteID = xl.ID
where persfamID = '{$personId}'
SQL;
        $result = $this->query($sql);

        $rows = array();
        if ($personPrivate > $userPrivate) {
            return $rows;
        }
        while ($row = $result->fetch_assoc()) {
            if ($row['secret']) {
                continue;
            }
            $rows[] = $row;
        }
        return $rows;
    }

    public function getDefaultMedia($personId = null, $tree = null)
    {

        if (!$personId) {
            $personId = $this->currentPerson;
        }
        $user = $this->getTngUser();
        $userPrivate = $user['allow_private'];
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $person = $this->getPerson($personId, $gedcom);
        $personPrivate = $person['private'];

        if ($personPrivate > $userPrivate) {
            return array();
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND m.gedcom = "' . $gedcom . '"';
        }
	$sql = <<<SQL
SELECT *
FROM   {$this->tables['media_table']} as ml
    LEFT JOIN {$this->tables['medialinks_table']} AS m
              ON ml.mediaID = m.mediaID
where personID = '{$personId}' AND m.defphoto = "1" {$treeWhere}
SQL;
        $result = $this->query($sql);
        $row = $result->fetch_assoc();
        return $row;
    }

    public function getAllPersonMedia($personId = null, $tree = null)
    {

        if (!$personId) {
            $personId = $this->currentPerson;
        }
        $user = $this->getTngUser();
        $userPrivate = $user['allow_private'];
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $persFam = $this->getPerson($personId, $gedcom);
        if (!$persFam) {
            $persFam = $this->getFamilyById($personId, $gedcom);

        }
        $persFamPrivate = $persFam['private'];
        if ($persFamPrivate > $userPrivate) {
            return array();
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND m.gedcom = "' . $gedcom . '"';
        }

        $sql = <<<SQL
SELECT *
FROM   {$this->tables['media_table']} as ml
    LEFT JOIN {$this->tables['medialinks_table']} AS m
              ON ml.mediaID = m.mediaID
where personID = '{$personId}' AND m.defphoto <> "1" {$treeWhere}

ORDER  BY m.ordernum

SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getProfileMedia($personId = null, $tree = null)
    {
        //get default media
        $defaultmedia = $this->getdefaultmedia($personId);
        //$mediaID = "../tng/photos/". $defaultmedia['thumbpath'];

        if ($defaultmedia['thumbpath'] == null AND $person['sex'] == "M") {
             $mediaID = "./". $tngDirectory. "/img/male.jpg";
        }
        if ($defaultmedia['thumbpath'] == null AND $person['sex'] == "F") {
             $mediaID = "./". $tngDirectory. "/img/female.jpg";
        }
        if ($defaultmedia['thumbpath'] !== null) {
            $mediaID = $photosPath. "/" . $defaultmedia['thumbpath'];
        }
        return $mediaID;
    }

    public function getChildren($familyId = null, $tree = nullS)
    {

        if (!$familyId) {
            return array();
        }
        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND gedcom = "' . $gedcom . '"';
        }
        $sql = <<<SQL
	SELECT *
FROM {$this->tables['children_table']}
WHERE familyID = '{$familyId}' {$treeWhere}
ORDER BY ordernum
SQL;
        $result = $this->query($sql);

        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getChildrow($personId = null, $tree = null)
    {
        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND gedcom = "' . $gedcom . '"';
        }
        $sql = <<<SQL
	SELECT *
FROM {$this->tables['children_table']}
WHERE personID = '{$personId}' {$treeWhere}
ORDER BY ordernum
SQL;
        $result = $this->query($sql);

        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getFamilyUser($personId = null, $tree = null, $sortBy = null)    {

        if (!$personId) {
            $personId = $this->currentPerson;
        }
        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND gedcom = "' . $gedcom . '"';
        }

        $sql = <<<SQL
SELECT*


FROM {$this->tables['families_table']}

WHERE (husband = '{$personId}' {$treeWhere}) or (wife = '{$personId}' {$treeWhere})
SQL;
        $result = $this->query($sql);
        $rows = array();

        while ($row = $result->fetch_assoc()) {
			$userPrivate = $user['allow_private'];
			$familyPrivate = $row['private'];
			if ($familyPrivate > $userPrivate) {
				$row['marrdate'] = 'Private';
				$row['marrplace'] = ' Private';
			}

			if ($sortBy) {
				$this->sortBy = $sortBy;
				usort($rows, array($this, 'sortRows'));
			}
			$rows[] = $row;
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

    public function getBirthdays($month, $tree = null)
    {

        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND gedcom = "' . $gedcom . '"';
        }

        $sql = <<<SQL
SELECT personid,
       firstname,
       lastname,
       birthdate,
       birthplace,
	   private,
       famc,
       gedcom
FROM   {$this->tables['people_table']}
WHERE  Month(birthdatetr) = {$month}
       AND living = 1 {$treeWhere}
ORDER  BY Day(birthdatetr),
          lastname
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $userPrivate = $user['allow_private'];
			$birthdayPrivate = $row['private'];
			if ($birthdayPrivate > $userPrivate) {
				$row['firstname'] = 'Private:';
				$row['lastname'] = ' Details withheld';

			}
			$rows[] = $row;
        }
		return $rows;
    }

    public function getDeathAnniversaries($month, $tree = null)
    {
        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND gedcom = "' . $gedcom . '"';
        }

        $sql = <<<SQL
SELECT personid,
       firstname,
       lastname,
       birthdate,
	   birthdatetr,
	   deathdate,
       deathdatetr,
	   deathplace,
       gedcom,
       Year(Now()) - Year(deathdatetr) AS Years
FROM   {$this->tables['people_table']}
WHERE  Month(deathdatetr) = {$month}
       AND living = 0 {$treeWhere}
ORDER  BY Day(deathdatetr),
          lastname
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = $result->fetch_assoc()) {
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
        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND gedcom = "' . $gedcom . '"';
        }

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
       AND living = 0 {$treeWhere}
ORDER  BY Day(deathdatetr),
          lastname
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getDeathAnniversariesPlusThree($month)
    {
        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND gedcom = "' . $gedcom . '" AND private = 0';
        }

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
       AND living = 0 {$treeWhere}
ORDER  BY Day(deathdatetr),
          lastname
SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getMarriageAnniversaries($month, $tree = null)
    {
        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        // If we are searching, enter $tree value
        if ($tree) {
            $gedcom = $tree;
        }
        $treeWhere = null;
        if ($gedcom) {
            $treeWhere = ' AND f.gedcom = "' . $gedcom . '"';
        }
        $sql = <<<SQL
SELECT
    h.gedcom,
    h.private AS private1,
    h.personid AS personid1,
    h.firstname AS firstname1,
    h.lastname AS lastname1,
    w.personid AS personid2,
    w.firstname AS firstname2,
    w.lastname AS lastname2,
    w.private AS private2,
    w.gedcom,
    f.gedcom,
    f.private,
    f.familyID,
    f.marrdate,
    f.marrplace,
    f.divdate,
    Year(Now()) - Year(marrdatetr) AS Years
FROM {$this->tables['families_table']} as f
    LEFT JOIN {$this->tables['people_table']} AS h
    ON (f.husband = h.personid AND f.gedcom = h.gedcom)
    LEFT JOIN {$this->tables['people_table']} AS w
    ON (f.wife = w.personid AND f.gedcom = w.gedcom)
WHERE  Month(f.marrdatetr) = {$month}
{$treeWhere}
ORDER  BY Day(f.marrdatetr)

SQL;
        $result = $this->query($sql);

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $userPrivate = $user['allow_private'];
			$manniversaryPrivate1 = $row['private1'];
			$manniversaryPrivate2 = $row['private2'];
			if ($manniversaryPrivate1 > $userPrivate) {
				$row['firstname1'] = 'Private1:';
				$row['lastname1'] = ' Details withheld';
			}
			if ($manniversaryPrivate2 > $userPrivate) {
				$row['firstname2'] = 'Private2:';
				$row['lastname2'] = ' Details withheld';
			}
		$rows[] = $row;
		}

        return $rows;
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
	// echo $user; 
        $rows = array();
        $where = null;
        if (count($wheres)) {
            $where = 'WHERE ' . implode(' AND ', $wheres);
        }

        $user = $this->getTngUser();
        $gedcom = $user['gedcom'];
        if ($gedcom) {
            if (!$where) {
                $where = ' WHERE ';
            } else {
                $where .= ' AND ';
            }
            $where .= ' gedcom = "' . $gedcom . '"';
        }

        $sql = <<<SQL
SELECT *
FROM {$this->tables['people_table']}
{$where}
ORDER BY firstname, lastname
LIMIT 100
SQL;

        $result = $this->query($sql);

        while ($row = $result->fetch_assoc()) {
            $userPrivate = $user['allow_private'];
			$searchPrivate = $row['private'];
			if ($searchPrivate > $userPrivate) {
				$row['firstname'] = 'Private:';
				$row['lastname'] = ' Details withheld';

			}
			$rows[] = $row;
		}
        return $rows;
    }

    public function getTngUserName()
    {
        $user = $this->getTngUser();
        return $user['username'];
    }

    public function getTngUser()
    {
        if ($this->tngUser) {
            return $this->tngUser;
        }
		
		$currentUser = wp_get_current_user();
		$userName = $currentUser->user_login;
		// Uncomment to allow for a configurable default user.
		/*
		$defaultUser = esc_attr(('tng-api-tng-default-user'));
		if ($defaultUser) {
			$userName = $defaultUser;
		} else {		
			$currentUser = wp_get_current_user();
			$userName = $currentUser->user_login;
		}
        */

       $query = "SELECT * FROM {$this->tables['users_table']} WHERE username='{$userName}'";
        //$result = $conn->query($query);
        

       $result = $this->query($query);
        $row = $result->fetch_assoc();
        if ($row) {
            $this->tngUser = $row;
            return $row;
        }
        throw new Upavadi_WpOnlyException('User ' . $userName . ' not found in TNG (or donot have access to) (1247)'); //mu - change from wp_die
    }

    /**
     * @staticvar Upavadi_Repository_TngRepository $repo
     * @return \Upavadi_Repository_TngRepository
     */
    public function getRepo()
    {
        static $repo;
        if (!$repo) {
            $this->init();
            $repo = new Upavadi_Repository_TngRepository($this);
        }

        return $repo;
    }

    public function getTree()
    {
        $user = $this->getTngUser();
        return $user['gedcom'];
    }



    public function pathNotSpecified()
    {
      $tngPath = get_option('tng-api-tng-path');
     // $tngdomain = get_option('tng-api-tng-url');
      //$photopath = get_option('tng-api-tng-photo-folder');
      $success = "";
        if(isset ($_POST['Update_api_Paths'])) {
            $tngFileError = $this->checkForTngApiPath();
            $tngPromt = "";
            if ($tngFileError[0] == true) {
                $tngPromt = "<div style='color: red; font-size: 1.2em'>Cannot find TNG folder. Please check TNG setup.</div>";
            }
            
            if ($tngFileError[0] == false) {
                $tngPromt = "<div style='color: green; font-size: 1.2em'>Found TNG folder</div>";
                $tngPath = $_POST['tng_api_path'];
                $tngdomain = $tngFileError[2];
                $photopath = $tngFileError[3];
                update_option('tng-api-tng-path', $tngPath);
                update_option('tng-api-tng-url', $tngdomain);
                update_option('tng-api-tng-photo-folder', $photopath);
                $success = "Paths saved";
            }
        }

        if($success) {
            echo "<div class='notice notice-success'>";
        } else {
            echo "<div class='notice notice-error'>";
        }
       
         ?>
		<div>
			<h2>TngApi: We need to know where TNG is installed:</h2>
		</div>
		<form action=''  method="post">	
		<div> 	
			<input type="text"  style="width: 250px" name="tng_api_path" value= '<?php echo $tngPath; ?>' placeholder='TNG Root Path:'>
			TNG Root Path is absolute path to TNG. You may look this up from TNG Admin Setup or in config.php in TNG folder.
		</div>
		<?php
		echo $tngPromt;
		?>
		<div> 	
			<input style="color: green; width: 250px" type="text"  name="tng_url" value= '<?php echo $tngdomain; ?>' placeholder='TNG url:' disabled>
			TNG URL (www.mysite.com/tng) from TNG Admin Setup.
		</div>
		<div> 	
			<input style="color: green" type="text"  name="tng_photo_folder" style="width: 250px" value= '<?php echo $photopath; ?>' placeholder='TNG photo folder:' disabled>
			Name of TNG Photo Folder in TNG Setup.  If you want to use different folder for this plugin, change it in admin menu>WP-TNG Login>Plugin Paths.
		</div>
		<p style="color: green; display: inline-block"><?php echo "<b>". $success. "</b><br />"; ?></p>
		
	<p>
	<input type="submit" name="Update_api_Paths" value="Update Paths">
	</p>
	</div>
	</form>
	
    <?php
    }

    public function dbNotSpecified()
    {
        $dbHost = (get_option('tng-api-db-host'));
        $dbUser = esc_attr(get_option('tng-api-db-user'));
        $dbPassword = esc_attr(get_option('tng-api-db-password'));
        $dbName = esc_attr(get_option('tng-api-db-database'));
       
        
        if (isset ($_POST['Update_credentials']))  {
            $tngPrompt = "";
            $success = "";
            $dbHost = $_POST['tng_db_host'];
            $dbUser = $_POST['tng_db_user'];
            $dbPassword = $_POST['tng_db_password'];
            $dbName = $_POST['tng_db_name'];
            update_option('tng-api-db-host',  $dbHost);
            update_option('tng-api-db-user',  $dbUser);
            update_option('tng-api-db-password',  $dbPassword);
            update_option('tng-api-db-database',  $dbName);
            $dbPromt = $this->checkForTngDb($dbHost, $dbUser, $dbPassword, $dbName);
        }
        
        if ($dbPromt == true) {
            $tngPromt = "<div style='color: green; font-size: 1.2em'>DB: No Errors</div>";
            $fieldColor = "color: green; width: 250px";
            $success = "db re-configured successfully"; 
          }    
      

        if ($dbPromt == false) {
            $tngPromt = "<div style='color: red; font-size: 1.2em'>Cannot connect to DB. Please check TNG DB setup.</div>";
          $fieldColor = "color: red; width: 250px";
          
        } 

        if($success) {
            echo "<div class='notice notice-success'>";
        } else {
            echo "<div class='notice notice-error'>";
        }   
    ?>
    	<div>
			<h2>TngApi: Please check TNG DB Credentials:</h2>
		</div>
        <form action=''  method="post">	
		<div> 	
			<input type="text"  style="<?php echo $fieldColor; ?>" name="tng_db_host" value= '<?php echo $dbHost; ?>' placeholder='DB Host Name'>
			Host Name: You may look these up from TNG Admin Setup or in config.php in TNG folder.
		</div>
		<?php
		
		?>
		<div> 	
			<input style= '<?php echo $fieldColor; ?>' type="text"  name="tng_db_user" value= '<?php echo $dbUser; ?>' placeholder='DB User Name:'>
			DB User Name.
		</div>
		<div> 	
			<input style="<?php echo $fieldColor; ?>" type="text"  name="tng_db_password" style="width: 250px" value= '<?php echo $dbPassword; ?>' placeholder='DB Password:'> DB Password:
			
		</div>
        <div> 	
			<input style="<?php echo $fieldColor; ?>" type="text"  name="tng_db_name" style="width: 250px" value= '<?php echo $dbName; ?>' placeholder='DB Name:'> DB Name:
		</div>
        <p><?php echo $tngPromt; ?></p>
        <p>
        <input type="submit" name="Update_credentials" value="Update Credentials">
        </p>
        </div>
        <p style="color: green; display: inline-block"><?php echo "<b>". $success. "</b><br />"; ?></p>
        </form>
	
    
        <?php
    }  
    
    public function checkForTngApiPath() {
        $wp_tng_path = $_POST['tng_api_path']. 'config.php';
        $tngFileError = "";
        if (!file_exists($wp_tng_path) || !is_readable($wp_tng_path)) {	
            return array(true, "", "","");
        } else {
        include($wp_tng_path);
        
        return array(false, $rootpath, $tngdomain, $photopath);
        }
    }

    public function checkForTngDb($dbHost, $dbUser, $dbPassword, $dbName) {
        try {
            $tngPrompt = true;
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);  //DB error reporting
            $db = @mysqli_connect($dbHost, $dbUser, $dbPassword,$dbName );
            } catch (exception $e) {
            $tngPrompt = false;
               return $tngPrompt;
            }
         
        return $tngPrompt;
        
    }
}
?>
