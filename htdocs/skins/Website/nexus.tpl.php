<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend.php';

class Template_Website_nexus extends Skins_Skin_Template_Frontend
{
    protected function _renderContent()
    {
        ob_start();
        
        ?>
        	<p>
        		<?php pt('Browsing available biographies:') ?>
        	</p>
        <?php 
        
		return ob_get_clean();
	}
}        