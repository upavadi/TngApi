<?php

class Upavadi_Shortcode_PersonNotes extends Upavadi_Shortcode_AbstractShortcode
{
    const SHORTCODE = 'upavadi_pages_personnotes';

    public function show()
    {
        $this->content->init();
        $personId = filter_input(INPUT_GET, 'personId', FILTER_SANITIZE_SPECIAL_CHARS);
        $context = array(
            'personId' => $personId
        );
        return $this->templates->render('family_notes', $context);
    }
}