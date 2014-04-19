<?php

class Upavadi_Shortcode_Birthdays extends Upavadi_Shortcode_AbstractShortcode
{
    const SHORTCODE = 'upavadi_pages_birthdays';

    public function show()
    {
        $this->content->init();

        $month = date('m');
        $birthdays = $this->api->getBirthdays($month);
        $date = new DateTime();
        $date->setDate(date('Y'), $month, 1);
        
        $context = array(
            'month' => $month,
            'birthdays' => $birthdays,
            'date' => $date
        );
        return $this->templates->render('birthdays.html', $context);
    }
}
