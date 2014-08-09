<?php

class Upavadi_Templates
{
    protected $templates;

    public function __construct($path = null)
    {
        if ($path) {
            $this->templates = $path;
        } else {
            $this->templates = dirname(dirname(__FILE__)) . '/templates/';
        }
    }

    public function render($template, array $context)
    {
        extract($context);
        ob_start();
        include $this->templates . $template .'.php';
        return ob_get_clean();
    }
    
}
