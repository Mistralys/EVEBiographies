<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend/WriteTab.php';

class Template_Website_writeDelete extends Skins_Skin_Template_Frontend_WriteTab
{
    protected function _renderTab() : string
    {
        ob_start();
        
        ?>
			<p>
				<b><?php pt('This lets you delete your biography permanently.'); ?></b>
			</p>
        <?php 
        
		return ob_get_clean();
	}
}        