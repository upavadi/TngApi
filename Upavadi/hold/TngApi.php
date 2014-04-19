<?php

class Upavadi_TngContent
{
	private static $instance = null;

	protected $db;

	protected $currentPerson;

	protected $tables = array();

	protected function __construct()
	{ }

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
		add_shortcode('upavadi_pages_manniversaries', array($this, 'showmanniversaries'));
		add_shortcode('upavadi_pages_danniversaries', array($this, 'showdanniversaries'));
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
		$result = mysql_query($query, $db) or die ("Cannot execute query: $query");
		$row = mysql_fetch_assoc($result);
		
		$this->db = $db;
		$this->currentPerson = $row['personID'];
		return $this;
	}

	protected function query($sql)
	{
		$result = mysql_query($sql, $this->db) or die ("Cannot execute query: $sql");
		return $result;
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

	public function showUser()
	{
		$user = $this->getPerson();
		return print_r($user, true);
	}
	public function showUserfamily()
	{
		$user = $this->getfamily();
		return print_r($user, true);
	}
	public function showUserchildren()
	{
		$user = $this->getchildren();
		return print_r($user, true);
	}
	
	public function getfamily($personId = null)
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

	public function getchildren($personId = null)
	{
	
		if (!$personId) {
			$personId = $this->currentPerson;
		}

		$sql = <<<SQL
	SELECT *
FROM {$this->tables['children_table']}
WHERE personID = '{$personId}'
SQL;
		$result = $this->query($sql);
		$row = mysql_fetch_assoc($result);
		
		return $row;
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
	
		public function getdanniversaries($month)
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

	
		public function getmanniversaries($month)
	{
		$sql = <<<SQL
SELECT h.gedcom,
       h.personid,
       h.firstname AS firstname1,
       h.lastname AS lastname1,
       w.personid,
       w.firstname AS firstname2,
       w.lastname AS lastname2,
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
	//do shortcode birthdays
	public function showBirthdays()
	{
		ob_start();
		Upavadi_Pages::instance()->birthdays();
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
}