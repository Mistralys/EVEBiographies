<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend/WriteTab.php';

class Template_Website_writeShare extends Skins_Skin_Template_Frontend_WriteTab
{
    protected function _renderTab() : string
    {
        ob_start();
        
        ?>
        	<p>
        		<?php pt('To share your biography, use the following URL:') ?>
        	</p>
        	<div class="alert alert-secondary monospace"><?php echo $this->bio->getShareURL() ?></div>
        	<p>	
        		<a href="<?php echo $this->bio->getShareURL() ?>" class="btn btn-primary" target="_blank" title="<?php pt('Opens in a new tab') ?>">
        			<i class="fa fa-user-astronaut"></i>
        			<?php pt('View the biography') ?>
        		</a>
        	</p>
        <?php 
        
		return ob_get_clean();
	}
}        