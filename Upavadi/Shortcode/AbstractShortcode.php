<?php

abstract class Upavadi_Shortcode_AbstractShortcode
{
    /**
     *
     * @var Upavadi_TngContent
     */
    protected $content;

    /**
     * @var Upavadi_Templates
     */
    protected $templates;

    public function init(Upavadi_TngContent $content, $templates)
    {
        $this->content = $content;
        $this->templates = $templates;
        
        add_shortcode(static::SHORTCODE, array($this, 'show'));
    }
    
    abstract public function show();
}
