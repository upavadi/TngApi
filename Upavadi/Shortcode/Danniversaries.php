<?php

class Upavadi_Shortcode_Danniversaries extends Upavadi_Shortcode_AbstractShortcode
{
    const SHORTCODE = 'upavadi_pages_danniversaries';

    public function show()
    {
        $this->content->init();
		$monthyear = filter_input(INPUT_GET, 'monthyear', FILTER_SANITIZE_SPECIAL_CHARS);
						
		if ($monthyear == "") {
        $month = date('m');
		$year = date('Y');
		} else {
		$month = substr($monthyear, 3, 2);
		$year = substr($monthyear, 6, 4);
		}
		
        $danniversaries = $this->content->getDeathAnniversaries($month);
        $date = new DateTime();
        $date->setDate($year, $month, 01);
		       		
		$context = array(
            'year' => $year,
			'month' => $month,
            'date' => $date,
			'danniversaries' => $danniversaries
            
        );
        return $this->templates->render('danniversaries.html', $context);
    }
}
