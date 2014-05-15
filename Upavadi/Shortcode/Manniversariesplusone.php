<?php

class Upavadi_Shortcode_ManniversariesPlusone extends Upavadi_Shortcode_AbstractShortcode
{
    const SHORTCODE = 'upavadi_pages_manniversariesplusone';

    public function show()
    {
        $this->content->init();
				
        $manniversariesplusone = $this->content->getMarriageAnniversariesPlusOne($month);
        $date = new DateTime();
        $date->setDate($year, $month, 01);
		       		
		$context = array(
            'year' => $year,
			'month' => $month,
            'date' => $date,
			'manniversariesplusone' => $manniversariesplusone
            
        );
        return $this->templates->render('manniversariesplusone.html', $context);
    }
}
