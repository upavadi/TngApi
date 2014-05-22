<?php

class Upavadi_Shortcode_TabsShortcode extends Upavadi_Shortcode_AbstractShortcode
{

    const SHORTCODE = 'Tabs_Shortcodes';

  
    public function show()
    {
        return $this->c->TabsShortcodes();
    }


}
?>
<link rel="stylesheet" href="/wordpress/wp-content/plugins/tng-api/css/tabs.css">
<script type="text/javascript" src="/wordpress/wp-content/plugins/tng-api/js/tabs.js"></script>
<script type="text/javascript" src="/wordpress/wp-content/plugins/tng-api/js/tabs.min.js"></script>