<?php

class Upavadi_Shortcode_FamilySearch extends Upavadi_Shortcode_AbstractShortcode
{
    const SHORTCODE = 'upavadi_pages_familysearch';

    public function show()
    {
        $this->content->init();
        $firstName = filter_input(INPUT_GET, 'firstName', FILTER_SANITIZE_SPECIAL_CHARS);
        $lastName = filter_input(INPUT_GET, 'lastName', FILTER_SANITIZE_SPECIAL_CHARS);

        $context = array();
        $context['results'] = $this->content->searchPerson($firstName, $lastName);
        return $this->templates->render('search', $context);
    }
}
