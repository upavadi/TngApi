<?php

class Upavadi_Shortcode_TabsShortcode extends Upavadi_Shortcode_AbstractShortcode
{

    const SHORTCODE = 'Tabs_Shortcode';

  
    public function show()
    {
        return $this->content->TabsShortcodes();
    }


}
?>
<link rel="stylesheet" href="<?php echo plugins_url('../'. '/css/tabs.css', dirname(__FILE__)); ?>">
<!--
<script type="text/javascript" src="/wordpress/wp-content/plugins/tng-api/js/tabs.js"></script>

<script type="text/javascript" src="<?php echo plugins_url('../'. '/js/tabs.min.js', dirname(__FILE__)); ?>"></script>
-->