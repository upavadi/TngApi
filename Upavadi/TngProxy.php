<?php

class Upavadi_TngProxy
{

    private $passwordType;
    private $passwordHash;
    private $userName;
    private $suffix;

    public function __construct($userName, $passwordHash, $passwordType, $suffix)
    {
        $this->userName = $userName;
        $this->passwordHash = $passwordHash;
        $this->passwordType = $passwordType;
        $this->suffix = $suffix;
    }

    public function load($path)
    {
        $client = new Guzzle\Http\Client();
        $options = array(
            "cookies" => array(
                "tnguser" . '_' . $this->suffix => $this->userName,
                "tngpass" . '_' . $this->suffix => $this->passwordHash,
                "tngpasstype" . '_' . $this->suffix => $this->passwordType,
                "PHPSESSID" => uniqid("tngapi")
            )
        );
        $request = $client->get($path, null, $options);

        //ini_set('xdebug.max_nesting_level', 200);
        $response = $request->send();
        if (strpos($response->getContentType(), "text/html") !== 0) {
            return $response;
        }
        $doc = new DOMDocument();
        @$doc->loadHTML($response->getBody(true));
        $tables = $doc->getElementsByTagName('table');
        $scripts = $doc->getElementsByTagName('script');

        $styles = $doc->getElementsByTagName('style');
        $links = $doc->getElementsByTagName('link');

        $content = $doc->createElement('div');
        $doc->appendChild($content);
        
        $nodes = array();
        foreach (array($styles, $links) as $elements) {
            foreach ($elements as $element) {
                $nodes[] = $element;
            }
        }
        
        foreach ($nodes as $index => $node) {
            $content->appendChild($node);
        }
        
        
        $nodes = array();
        foreach ($scripts as $emement) {
            $nodes[] = $element;
        }
        
        foreach ($nodes as $index => $node) {
            if ($node->parentNode->nameName !== 'head') {
                continue;
            }
            if ($node->nodeName === 'script') {
                $src = $node->attributes->getNamedItem('src');
                if ($src && preg_match('/jquery/', $src->nodeValue)) {
                    continue;
                }
            }
            $content->appendChild($node);
            unset($nodes[$index]);
        }
        
        foreach ($tables as $table) {
            /* $table DOMNode */
            if ($table->parentNode->nodeName !== 'td') {
                continue;
            }
            if ($table->parentNode->attributes->getNamedItem('valign')->nodeValue !== 'top') {
                continue;
            }


            $content->appendChild($table);
        }
        
        foreach ($nodes as $index => $node) {
            $content->appendChild($node);
        }
        
        return str_replace("&#xD;", PHP_EOL, $content->C14N());
    }

}
