<?php

class TngApiCustom_TngCustom extends Upavadi_TngCustomContent
{

    protected $shortCodes = array(
        "MyShortcode"
    );

    public function __construct(Upavadi_TngContent $content)
    {
        parent::__construct($content, __FILE__);
    }

    public function getPersonName($personId)
    {
        $person = $this->content->getPerson($personId);
        $name = $person['firstname'] . $person['lastname'];

        return $name;
    }

}
