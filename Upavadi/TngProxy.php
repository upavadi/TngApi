<?php

class Upavadi_TngProxy
{

    private $passwordType;
    private $passwordHash;
    private $userName;
    private $suffix;
    private $rewriter;

    public function __construct($userName, $passwordHash, $passwordType, $suffix, $rewriter)
    {
        $this->userName = $userName;
        $this->passwordHash = $passwordHash;
        $this->passwordType = $passwordType;
        $this->suffix = $suffix;
        $this->rewriter = $rewriter;
    }

    public function login()
    {
        $path = "http://localhost/tng/processlogin.php";
        $options = array(
            "cookies" => array(
                "tnguser" . '_' . $this->suffix => $this->userName,
                "tngpass" . '_' . $this->suffix => $this->passwordHash,
                "tngpasstype" . '_' . $this->suffix => $this->passwordType,
                "tngloggedin" . '_' . $this->suffix => 1,
                "PHPSESSID" => $_SESSION['upavadi_tng_session_id']
            )
        );
        $form = array(
            'tngusername' => $this->userName,
            'tngpassword' => $this->passwordHash,
            'encrypted' => 'encrypted'
        );
        $client = new Guzzle\Http\Client();
        $request = $client->post($path, null, $form, $options);
        ini_set('xdebug.max_nesting_level', 200);
        $response = $request->send();
    }

    public function load($path, $method = 'get', $post = null)
    {
        if (!isset($_SESSION['upavadi_tng_session_id'])) {
            $_SESSION['upavadi_tng_session_id'] = md5(uniqid("tngapi"));
            $this->login();
        }
        $client = new Guzzle\Http\Client();
        $options = array(
            "cookies" => array(
                "tnguser" . '_' . $this->suffix => $this->userName,
                "tngpass" . '_' . $this->suffix => $this->passwordHash,
                "tngpasstype" . '_' . $this->suffix => $this->passwordType,
                "tngloggedin" . '_' . $this->suffix => 1,
                "PHPSESSID" => $_SESSION['upavadi_tng_session_id']
            ),
            'allow_redirects' => false
        );
        $headers = array(
            'referer' => $_SERVER['HTTP_REFERER']
        );

        $method = strtoupper($method);
        switch ($method) {
            case 'GET':
                $request = $client->get($path, $headers, $options);
                break;
            case 'POST':
                $request = $client->post($path, $headers, $post, $options);
                break;
        }
        //ini_set('xdebug.max_nesting_level', 200);
        $response = $request->send();
        //echo implode('<br>', $response->getHeaderLines());
        //echo $response->getBody(true); die;
//        echo $path;
//        echo $response->getEffectiveUrl(); die;
//        echo $response->getContentType(); die;
        if ($response->getHeader('Location')) {
            return $response;
        }
        if (strpos($response->getContentType(), "text/html") !== 0) {
            return $response;
        }
        if (preg_match('~/(ajx_|findpersonform.php)~', $path)) {
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
        $rewriter = $this->rewriter;
        foreach (array($styles, $links) as $elements) {
            foreach ($elements as $element) {
                $src = $element->attributes->getNamedItem('href');
                if ($src) {
                    $src->nodeValue = $rewriter($src->nodeValue);
                }
                $styleNodes[] = $element;
            }
        }

        foreach ($styleNodes as $index => $node) {
            $content->appendChild($node);
        }

        $tables = $doc->getElementsByTagName('table');
        $scripts = $doc->getElementsByTagName('script');
        $imgs = $doc->getElementsByTagName('img');
        
        foreach ($imgs as $img) {
            $src = $img->attributes->getNamedItem('src');
            if ($src) {
                if (preg_match('~^(http|/)~', $src->nodeValue)) {
                    continue;
                }
                if (preg_match('~&~', $src->nodeValue)) {
                    continue;
                }
                $src->nodeValue = $rewriter($src->nodeValue);
            }
        }
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
            $src = $node->attributes->getNamedItem('src');
            if ($src) {
                if (!preg_match('~^(http|/)~', $src->nodeValue)) {
                    $src->nodeValue = $rewriter($src->nodeValue);
                }               
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
                //if ($src && preg_match('/jquery/', $src->nodeValue)) {
                //    unset($nodes[$index]);
                //    continue;
                //}
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
        $body = str_replace(") &gt; 3", ") > 3", $body);
        $body = str_replace("&lt;img", "<img", $body);
        $body = str_replace("/&gt;", "/>", $body);
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
