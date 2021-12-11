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
        try {
            $this->content->init();
            return $this->show();
        } catch (Upavadi_WpOnlyException $e) {
            return '<div class="error">You must be a TNG user to use this part of the site, please contact your administrator</div>';
        }
    }

    abstract public function show();
}
