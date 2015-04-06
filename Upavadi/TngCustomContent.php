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
        $content->setCustom($this);
        $this->dir = dirname($path);
        $this->initShortcodes();
    }

    public function initShortcodes()
    {
        $templatePath = $this->getTemplatePath();
        $templates = new Upavadi_Templates($templatePath);

        foreach ($this->shortCodes as $shortCodeName) {
            $shortCodeClass = "TngApiCustom_Shortcode_" . $shortCodeName;
            $shortCode = new $shortCodeClass;
            $shortCode->init($this->content, $templates, $this);
        }
    }

    public function query($sql)
    {
        return $this->content->query($sql);
    }

    public function getTemplatePath()
    {
        return $this->dir . '/templates/';
    }
}
