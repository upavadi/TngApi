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
                "tngloggedin" . '_' . $this->suffix => 1,
                "PHPSESSID" => uniqid("tngapi")
            )
        );
        $request = $client->get($path, null, $options);

        //ini_set('xdebug.max_nesting_level', 200);
        $response = $request->send();
//        echo $path;
//        echo $response->getEffectiveUrl();
//        echo $response->getContentType(); die;
        if (strpos($response->getContentType(), "text/html") !== 0) {
            return $response;
        }
        $doc = new DOMDocument();
        $body = $response->getBody(true);
        $body = preg_replace('|//\s*<!\[CDATA\[|', '', $body);
        $body = preg_replace('|//\s*\]\]>|', '', $body);

        @$doc->loadHTML($body);

        $styles = $doc->getElementsByTagName('style');
        $links = $doc->getElementsByTagName('link');

        $content = $doc->createElement('div');
        $doc->appendChild($content);

        $styleNodes = array();
        foreach (array($styles, $links) as $elements) {
            foreach ($elements as $element) {
                $styleNodes[] = $element;
            }
        }

        foreach ($styleNodes as $index => $node) {
            $content->appendChild($node);
        }

        $tables = $doc->getElementsByTagName('table');
        $scripts = $doc->getElementsByTagName('script');

        $nodes = array();
        foreach ($scripts as $node) {
            $code = $node->nodeValue;
            if ($code) {
                $node->nodeValue = null;
                $cm = $node->ownerDocument->createTextNode("\n//");
                $ct = $node->ownerDocument->createCDATASection("\n" . $code . "\n//");
                $node->appendChild($cm);
                $node->appendChild($ct);
            }
            $nodes[] = $node;
        }

        foreach ($tables as $table) {
            /* $table DOMNode */
            if ($table->parentNode->nodeName !== 'td') {
                continue;
            }
            if ($table->parentNode->attributes->getNamedItem('valign')->nodeValue !== 'top') {
                continue;
            }

            break;
        }

        foreach ($nodes as $index => $node) {
            if ($this->hasParent($table, $node)) {
                continue;
            }
            if ($node->nodeName === 'script') {
                $src = $node->attributes->getNamedItem('src');
                if ($src && preg_match('/jquery/', $src->nodeValue)) {
                    unset($nodes[$index]);
                    continue;
                }
            }
            $content->appendChild($node);
            unset($nodes[$index]);
        }

        $content->appendChild($table);
        foreach ($nodes as $index => $node) {
            $content->appendChild($node);
        }

        $body = str_replace("&#xD;", PHP_EOL, $content->C14N());
        $body = str_replace("&lt; slot", "< slot", $body);
        return $body;
    }

    public function hasParent(DOMNode $parent, DOMNode $child)
    {
        if (!$child->parentNode) {
            return false;
        }
        if ($child->parentNode === $parent) {
            return true;
        }
        return $this->hasParent($parent, $child->parentNode);
    }

}
