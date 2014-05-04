<?php

class Upavadi_Shortcode_TngProxy extends Upavadi_Shortcode_AbstractShortcode
{

    const SHORTCODE = 'upavadi_tng_proxy';

    //do shortcode Add Family form
    public function show()
    {
        return $this->content->getHtml();
    }

}
