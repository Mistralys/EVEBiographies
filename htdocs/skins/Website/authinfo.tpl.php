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
        		        pts('Please sign in with one of the available administrator characters to access this page.');
        		    }
        		    else 
        		    {
        		        pts('To choose a character to use, please sign in using EVE Online\'s single sign on service.');
        		    }
        		?>
        	</p>
        	<p>
        		<a href="<?php echo $this->screen->getScreenURL('AuthInfo', array('confirm' => 'yes')) ?>" class="btn btn-primary">
        			<i class="fa fa-sign-in-alt"></i>
        			<?php pt('Sign in')?>
        		</a>
        	</p>
        	<p></p><br>
        	<p>
        		<b><?php pt('Why do you have to sign in?') ?></b>
        	</p>
        	<p>
        		<?php 
        		  pts('I decided to use EVE\'S single sign on service to keep the legal hoops for hosting user content to a minimum.');
        		  pts('This way, the site inherits EVE\'s terms of service and age limit, allowing a lot more freedom in what you can write.');
        		?>
        	</p>
        <?php 
        
		return ob_get_clean();
    }
}        