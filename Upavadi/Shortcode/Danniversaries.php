<?php

class Upavadi_Shortcode_Danniversaries extends Upavadi_Shortcode_AbstractShortcode
{
    const SHORTCODE = 'upavadi_pages_danniversaries';

    public function show()
    {
        $this->content->init();

        $month = date('m');
        $danniversaries = $this->content->getDeathAnniversaries($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 1);
        
        $context = array(
            'month' => $month,
            'danniversaries' => $danniversaries,
            'date' => $date
        );
        return $this->templates->render('danniversaries.html', $context);
    }
}
