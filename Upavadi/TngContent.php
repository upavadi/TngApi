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

        $db = mysqli_connect($dbHost, $dbUser, $dbPassword);
        mysqli_select_db($db, $dbName);
        $this->db = $db;
        $this->initTables();

        $tng_user_name = $this->getTngUserName();
        $query = "SELECT * FROM {$this->tables['users_table']} WHERE username='{$tng_user_name}'";
        $result = mysqli_query($db, $query) or die("Cannot execute query: $query");
        $row = $result->fetch_assoc();

        $this->currentPerson = $row['personID'];
        return $this;
    }

    protected function query($sql)
    {
        $result = mysqli_query($this->db, $sql) or die("Cannot execute query: $sql");
        return $result;
    }

    public function initAdmin()
    {
        register_setting('tng-api-options', 'tng-api-tng-page-id');
        register_setting('tng-api-options', 'tng-api-tng-path');
        register_setting('tng-api-options', 'tng-api-db-host');
        register_setting('tng-api-options', 'tng-api-db-user');
        register_setting('tng-api-options', 'tng-api-db-password');
        register_setting('tng-api-options', 'tng-api-db-database');

        add_settings_section('tng', 'TNG', function() {
            echo "In order for the plug in work we need to know where the original TNG source files live";
        }, 'tng-api');
        
        add_settings_field('tng-path', 'TNG Path', function () {
            $tngPath = esc_attr(get_option('tng-api-tng-path'));
            echo "<input type='text' name='tng-api-tng-path' value='$tngPath' />";
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
        $row = $result->fetch_assoc();

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
        $row = $result->fetch_assoc();

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
        $row = $result->fetch_assoc();

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
        $row = $result->fetch_assoc();

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
        while ($row = $result->fetch_assoc()) {
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
        $row = $result->fetch_assoc();

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
        while ($row = $result->fetch_assoc()) {
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

        while ($row = $result->fetch_assoc()) {
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

        while ($row = $result->fetch_assoc()) {
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
        while ($row = $result->fetch_assoc()) {
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
        while ($row = $result->fetch_assoc()) {
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
        while ($row = $result->fetch_assoc()) {
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
        while ($row = $result->fetch_assoc()) {
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
        while ($row = $result->fetch_assoc()) {
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
        while ($row = $result->fetch_assoc()) {
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
        while ($row = $result->fetch_assoc()) {
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
        while ($row = $result->fetch_assoc()) {
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
        while ($row = $result->fetch_assoc()) {
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
        while ($row = $result->fetch_assoc()) {
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

        while ($row = $result->fetch_assoc()) {
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
        $currentUser = wp_get_current_user();
        $userName = $currentUser->user_login;
        $query = "SELECT * FROM {$this->tables['users_table']} WHERE username='{$userName}'";
        $result = $this->query($query);
        $row = $result->fetch_assoc();
        if ($row) {
            return $row;
        }
        wp_die('User ' . $userName . ' not found in TNG');
    }
}
