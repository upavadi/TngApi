<?php

class Upavadi_Shortcode_Birthdays extends Upavadi_Shortcode_AbstractShortcode
{

    const SHORTCODE = 'upavadi_pages_birthdays';

    public function show()
    {
        $this->content->init();
        $monthyear = filter_input(INPUT_GET, 'monthyear', FILTER_SANITIZE_SPECIAL_CHARS);
		$currentPerson = $this->content->getCurrentPersonId();
        
		if ($monthyear == "") {
            $month = date('m');
            $year = date('Y');
        } else {
            $month = substr($monthyear, 3, 2);
            $year = substr($monthyear, 6, 4);
        }

        $birthdays = $this->content->getBirthdays($month);
        foreach ($birthdays as $index => $birthday) {
            $birthdate = strtotime($birthday['birthdate']);
            $age = $year - date('Y', $birthdate);
            $birthdays[$index]['age'] = $age;
        }
        $date = new DateTime();
        $date->setDate($year, $month, 01);

        $context = array(
            'year' => $year,
            'month' => $month,
            'date' => $date,
			'monthyear' => $monthyear,
            'birthdays' => $birthdays,
            'currentperson' => $currentPerson
        );

        return $this->templates->render('birthdays.html', $context);
    }

}
