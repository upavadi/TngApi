<?php

class Upavadi_Shortcode_TngProxy extends Upavadi_Shortcode_AbstractShortcode
{

    const SHORTCODE = 'upavadi_tng_proxy';

    //do shortcode Add Family form
    public function show()
    {
        $proxy = new Upavadi_TngProxy('gondal', '0cddfde984f24fac68f2d4ac468d3d6b', 'md5', 'C:wampwww');
        return $proxy->load("http://192.168.1.102/statistics.php");
    }

}
