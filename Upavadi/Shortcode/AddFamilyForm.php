<?php

class Upavadi_Shortcode_AddFamilyForm extends Upavadi_Shortcode_AbstractShortcode
{
    const SHORTCODE = 'upavadi_pages_addfamilyform';

    //do shortcode Add Family form
    public function show()
    {
        $personId = filter_input(INPUT_GET, 'personId', FILTER_SANITIZE_SPECIAL_CHARS);
        $tree = filter_input(INPUT_GET, 'tree', FILTER_SANITIZE_SPECIAL_CHARS);
        $context = array();
        $context['personId'] = $personId;
        $context['tree'] = $tree;
        return $this->templates->render('addfamilyform.html', $context);
    }
}