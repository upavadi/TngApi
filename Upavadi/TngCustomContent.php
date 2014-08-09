<?php

class Upavadi_TngCustomContent
{
    /**
     * @var Upavadi_TngContent 
     */
    protected $content;
    private $dir;

    public function __construct(Upavadi_TngContent $content, $path)
    {
        $this->content = $content;
        $this->dir = dirname($path);
        $this->initShortcodes();
    }

    public function initShortcodes()
    {
        $templatePath = $this->dir . '/templates/';
        $templates = new Upavadi_Templates($templatePath);
        
        foreach ($this->shortCodes as $shortCodeName) {
            $shortCodeClass = "TngApiCustom_Shortcode_" . $shortCodeName;
            $shortCode = new $shortCodeClass;
            $shortCode->init($this->content, $templates, $this);
        }
    }

}
