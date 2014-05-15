<?php

class Upavadi_Shortcode_Danniversariesplusone extends Upavadi_Shortcode_AbstractShortcode
{
    const SHORTCODE = 'upavadi_pages_danniversariesplusone';

    public function show()
    {
        $this->content->init();
				
        $danniversariesplusone = $this->content->getDeathAnniversariesPlusOne($month);
        $date = new DateTime();
        $date->setDate($year, $month, 01);
		       		
		$context = array(
            'year' => $year,
			'month' => $month,
            'date' => $date,
			'danniversariesplusone' => $danniversariesplusone
            
        );
        return $this->templates->render('danniversariesplusone.html', $context);
    }
}
