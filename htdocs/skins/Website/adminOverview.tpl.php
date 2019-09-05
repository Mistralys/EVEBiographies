<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend.php';

class Template_Website_adminOverview extends Skins_Skin_Template_Frontend
{
    protected function _renderContent()
    {
        ob_start();
        
        ?>
        	<p>
        		Available admin functions:
        	</p>
        	<ul>
        		<li>
        			<a href="<?php echo APP_URL.'/storage/' ?>">
        				SQLite database admin
        			</a>
        		</li>
        	</ul>
        
        <?php
        
        return ob_get_clean();
    }
}