<?php

class Upavadi_Shortcode_BirthdaysPlusOne extends Upavadi_Shortcode_AbstractShortcode
{
    const SHORTCODE = 'upavadi_pages_birthdaysplusone';

    public function show()
    {
        $this->content->init();

        $month = date('m');
        $birthdaysplusone = $this->content->getBirthdaysPlusOne($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 1);
        
        $context = array(
            'month' => $month,
            'birthdaysplusone' => $birthdaysplusone,
            'date' => $date
        );
        return $this->templates->render('birthdaysplusone.html', $context);
    }
}
