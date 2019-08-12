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

    public function init(Upavadi_TngContent $content, $templates, Upavadi_TngCustomContent $custom = null)
    {
        $this->content = $content;
        $this->templates = $templates;

        add_shortcode(static::SHORTCODE, array($this, 'showShortcode'));
    }

    public function showShortcode()
    {
        $this->content->init();
        return $this->show();
    }

    abstract public function show();
}
