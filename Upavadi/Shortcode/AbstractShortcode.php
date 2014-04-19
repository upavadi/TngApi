<?php

class Upavadi_Shortcode_AbstractShortcode
{
    /**
     *
     * @var Upavadi_TngContent
     */
    protected $content;

    /**
     *
     * @var Upavadi_Pages
     */
    protected $pages;

    public function init(Upavadi_TngContent $content, Upavadi_Pages $pages)
    {
        $this->content = $content;
        $this->pages = $pages;
        add_shortcode(static::SHORTCODE, array($this, 'show'));
    }
    
    abstract public function show();
}
