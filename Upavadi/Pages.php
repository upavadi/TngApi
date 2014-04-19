<?php

class Upavadi_Pages
{

    private static $instance = null;

    protected $templates;

    protected $api;

    protected function __construct()
    {
        $this->api = Upavadi_TngContent::instance()->init();
        $this->templates = dirname(dirname(__FILE__)) . '/templates/';
    }

    public static function instance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function birthdays($month = null)
    {
        if (!$month) {
            $month = date('m');
        }
        $birthdays = $this->api->getBirthdays($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 1);
        include $this->templates . 'birthdays.php';
    }

    public function birthdaysplusone($month = null)
    {

        if (!$month) {
            $month = date('m');
        }
        $birthdaysplusone = $this->api->getBirthdaysPlusOne($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 1);
        include $this->templates . 'birthdaysplusone.php';
    }

    public function birthdaysplustwo($month = null)
    {

        if (!$month) {
            $month = date('m');
        }
        $birthdaysplustwo = $this->api->getBirthdaysPlusTwo($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 1);
        include $this->templates . 'birthdaysplustwo.php';
    }

    public function birthdaysplusthree($month = null)
    {

        if (!$month) {
            $month = date('m');
        }
        $birthdaysplusthree = $this->api->getBirthdaysPlusThree($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 1);
        include $this->templates . 'birthdaysplusthree.php';
    }

    public function familyuser($personId)
    {
        include $this->templates . 'family.php';
    }

    public function getallpersonmedia($personId)
    {
        include $this->templates . 'family.php';
    }

    public function familyForm($personId)
    {
        include $this->templates . 'familyform.php';
    }

    public function danniversaries($month = null)
    {
        if (!$month) {
            $month = date('m');
        }
        $danniversaries = $this->api->getDeathAnniversaries($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 1);
        include $this->templates . 'danniversaries.php';
    }

    public function danniversariesplusone($month = null)
    {
        if (!$month) {
            $month = date('m');
        }
        $danniversariesplusone = $this->api->getDeathAnniversariesPlusOne($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 1);
        include $this->templates . 'danniversariesplusone.php';
    }

    public function danniversariesplustwo($month = null)
    {
        if (!$month) {
            $month = date('m');
        }
        $danniversariesplustwo = $this->api->getDeathAnniversariesPlusTwo($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 1);
        include $this->templates . 'danniversariesplustwo.php';
    }

    public function danniversariesplusthree($month = null)
    {
        if (!$month) {
            $month = date('m');
        }
        $danniversariesplusthree = $this->api->getDeathAnniversariesPlusThree($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 1);
        include $this->templates . 'danniversariesplusthree.php';
    }

    public function manniversaries($month = null)
    {
        if (!$month) {
            $month = date('m');
        }
        $manniversaries = $this->api->getMarriageAnniversaries($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 1);
        include $this->templates . 'manniversaries.php';
    }

    public function manniversariesplusone($month = null)
    {
        if (!$month) {
            $month = date('m');
        }
        $manniversariesplusone = $this->api->getMarriageAnniversariesPlusOne($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 1);
        include $this->templates . 'manniversariesplusone.php';
    }

    public function manniversariesplustwo($month = null)
    {
        if (!$month) {
            $month = date('m');
        }
        $manniversariesplustwo = $this->api->getmanniversariesplustwo($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 2);
        include $this->templates . 'manniversariesplustwo.php';
    }

    public function manniversariesplusthree($month = null)
    {
        if (!$month) {
            $month = date('m');
        }
        $manniversariesplusthree = $this->api->getmanniversariesplusthree($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 3);
        include $this->templates . 'manniversariesplusthree.php';
    }

    public function search($firstName, $lastName)
    {
        $results = $this->api->searchPerson($firstName, $lastName);
        include $this->templates . 'search.php';
    }

}
