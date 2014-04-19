<?php

class Upavadi_Shortcode_FamilySearch extends Upavadi_Shortcode_AbstractShortcode
{
    const SHORTCODE = 'upavadi_pages_familysearch';

    public function show()
    {
        ob_start();
        $searchFirstName = filter_input(INPUT_GET, 'firstName', FILTER_SANITIZE_SPECIAL_CHARS);
        $searchLastName = filter_input(INPUT_GET, 'lastName', FILTER_SANITIZE_SPECIAL_CHARS);

        $this->pages->search($searchFirstName, $searchLastName);
        return ob_get_clean();
    }
}
