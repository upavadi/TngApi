<?php

class Upavadi_Shortcode_PersonNotes extends Upavadi_Shortcode_AbstractShortcode
{
    const SHORTCODE = 'upavadi_pages_personnotes';

    public function show()
    {
        ob_start();
        $personId = filter_input(INPUT_GET, 'personId', FILTER_SANITIZE_SPECIAL_CHARS);

        $this->pages->personnotes($personId);
        return ob_get_clean();
    }
}