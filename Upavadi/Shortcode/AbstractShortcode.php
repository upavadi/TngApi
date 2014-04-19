<?php

abstract class Upavadi_Shortcode_AbstractShortcode
{
    /**
     *
     * @var Upavadi_TngContent
     */
    protected $content;

    public function init(Upavadi_TngContent $content)
    {
        $this->content = $content;
        add_shortcode(static::SHORTCODE, array($this, 'show'));
    }
    
    abstract public function show();
}
