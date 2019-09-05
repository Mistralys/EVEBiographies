<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Read.php';

class Template_Alabaster_read extends Skins_Skin_Template_Read
{
    protected function _initCSS()
    {
        $this->addStylesheet('Alabaster.css');
    }

    protected function _renderContent()
    {
        $charName = $this->character->getName();

        ob_start();

        ?>
            <div class="cover-container">
            	<div class="cover-inner">
            		<div class="content-container">
              <main role="main">
              	<div class="bio-name">
                  	<?php
                  	    if($this->character->hasPortrait()) {
                            ?><img class="cover-portrait" src="<?php echo $this->character->getPortraitURL() ?>" alt="<?php echo $charName ?>"><?php
                  	    }
                    ?>
                    <h1 class="cover-heading">
                    	<?php echo $charName ?>
                    	<span class="cover-subheading"><?php pt('An EVE online biography') ?></span>
                	</h1>
                </div>
                <div class="bio-body">
                    <?php echo $this->biography->render(); ?>
                </div>
              </main>

              <footer>
                <div class="inner">
                	<?php echo $this->renderFooterText() ?>
                </div>
              </footer>
              </div>
              </div>
            </div>
        <?php

        return ob_get_clean();
    }

    protected function _renderNotAvailable()
    {
        ob_start();
        ?>
        <div class="cover-container">
        	<main role="main">
            	<div class="bio-name">
            		<h1 class="cover-heading">
                    	<?php echo $this->character->getName() ?>
                    	<span class="cover-subheading"><?php pt('An EVE online biography') ?></span>
                	</h1>
                </div>
                <div class="bio-body">
                	<p><?php pts('This biography is currently not available.'); ?></p>

            	   	<?php
            	   	    $user = $this->screen->getCharacter();
            	   	
                	   	if($user->isAdmin() || $this->character->getID() == $user->getID()) {
                	   	    ?>
                	   	    	<p>
                	   	    		<?php pts('Reason (only you can see this):') ?>
                	   	    		<?php echo $this->biography->getValidationMessage() ?>
                	   	    	</p>
                	   	    <?php
                	   	}
            	   	?>
                </div>
        	</main>
    	</div>

       	<?php

       	return ob_get_clean();
    }
}
