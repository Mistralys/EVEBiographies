<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend.php';

class Template_Website_authinfo extends Skins_Skin_Template_Frontend
{
    protected function _renderContent()
    {
        $adminRequired = $this->getVar('admin-required');
        
        ob_start();
        
        ?>
        	<p class="abstract">
        		<?php 
        		    if($adminRequired) 
        		    {
        		        pts('Please log in with any of the available administrator characters.');
        		    }
        		    else 
        		    {
        		        pts('To continue, you must log in using EVE Online\'s single sign on service.');
        		    }
        		?>
        	</p>
        	<p>
        		<a href="<?php echo $this->screen->getScreenURL('AuthInfo', array('confirm' => 'yes')) ?>" class="btn btn-primary">
        			<?php pt('Log in')?>
        		</a>
        	</p>
        <?php 
        
		return ob_get_clean();
    }
}        