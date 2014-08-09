<?php

class TngApiCustom_Shortcode_MyShortcode extends Upavadi_Shortcode_AbstractCustomShortcode
{
    const SHORTCODE = 'tngcustom_myshortcode';
    
    public function show()
    {
        $personId = $this->content->getCurrentPersonId();
        $context = array();
        $context['name'] = $this->custom->getPersonName($personId);
        return $this->templates->render('myshortcode.html', $context);
    }
}
