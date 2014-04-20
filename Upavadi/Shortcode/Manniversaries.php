<?php

class Upavadi_Shortcode_Manniversaries extends Upavadi_Shortcode_AbstractShortcode
{
    const SHORTCODE = 'upavadi_pages_manniversaries';

    public function show()
    {
        $this->content->init();

        $month = date('m');
        $manniversaries = $this->content->getMarriageAnniversaries($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 1);
        
        $context = array(
            'month' => $month,
            'manniversaries' => $manniversaries,
            'date' => $date
        );
        return $this->templates->render('manniversaries.html', $context);
    }
}
