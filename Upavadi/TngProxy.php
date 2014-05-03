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
        $doc = new DOMDocument();
        $doc->loadHTML($response->getBody(true));
        $tables = $doc->getElementsByTagName('table');
        foreach ($tables as $table) {
            /* $table DOMNode */
            if ($table->parentNode->nodeName !== 'td') {
                continue;
            }
            if ($table->parentNode->attributes->getNamedItem('valign')->nodeValue !== 'top') {
                continue;
            }
            return $table->C14N();
        }
    }

}
