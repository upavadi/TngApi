<?php

abstract class Upavadi_Shortcode_AbstractCustomShortcode extends Upavadi_Shortcode_AbstractShortcode
{
    /**
     *
     * @var Upavadi_TngCustomContent
     */
    protected $custom;

    public function init(Upavadi_TngContent $content, $templates, Upavadi_TngCustomContent $custom = null)
    {
        parent::init($content, $templates);
        $this->custom = $custom;
    }
}
