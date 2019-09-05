<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend.php';

class Template_Website_share extends Skins_Skin_Template_Frontend
{
    protected function _renderContent()
    {
        /* @var $char Characters_Character */
        $char = $this->getVar('character');
        
        $this->addStylesheet('screen-share.css');
        
        ob_start();
        
        ?>
        	<?php 
            	if($char->hasPortrait()) 
            	{
        	       ?>
        	       	<img src="<?php echo $char->getPortraitURL() ?>" class="portrait-image"/>
        	       <?php 
            	}
        	?>
        	<p class="abstract">
        		<?php 
        		    pts('To read %1$s\'s biography, please login using EVE Online\'s single sign on service.', $char->getName());
        		?>
        	</p>
        	<p class="login-button">
        		<a href="<?php echo $char->getReadURL() ?>" class="btn btn-primary">
        			<i class="fa fa-user-astronaut"></i>
        			<?php pt('Log in and start reading') ?>
    			</a>
        	</p>
			<p>
				<b><?php pts('Why do I have to log in?') ?></b>
			</p>
			<p>
				<?php 
				    pts('Simply put:');
				    pts('It makes the legal hassle of hosting the biographies a lot easier.');
				    pts('Using EVE Online\'s single sign on service, we can guarantee our readers all have EVE Online accounts and have accepted EVE\'s terms and conditions.');
				    pts('As a result, we inherit those terms, including EVE\'s age limit.');
				    pts('Hosting the user-created biographies publicly would otherwise require a lot of additional disclaimers.');
			    ?>
			</p>
			<p>
				<?php 
				    pts('We realize this can be annoying, and may change it in the future.');
				    pts('For now however, we prefer investing this time into developing the service itself.');
				?>
			</p>
        <?php 
        
		return ob_get_clean();
    }
}        