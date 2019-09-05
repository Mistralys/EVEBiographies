<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Read.php';

abstract class Skins_Skin_Template_Read_Bootswatch extends Skins_Skin_Template_Read
{
   /**
    * @var Skins_Skin_Source_Bootswatch
    */
    protected $skin;

    protected function _initCSS()
    {
        $this->addCSS(
            '.cover-container',
            sprintf(
                'background:rgba(%s, %s)',
                $this->skin->getCanvasBackground(),
                $this->skin->getCanvasOpacity()
                )
            );

        $this->addCSS('BODY', sprintf('color:#%s', $this->skin->getTextColor()));
        $this->addCSS('BODY', sprintf('background-color:#%s', $this->skin->getBodyBackground()));
        $this->addCSS('.bio-body EM', sprintf('color:#%s', $this->skin->getDialogueColor()));
    }

    protected function _renderContent()
    {
        $charName = $this->character->getName();

        ob_start();

        ?>
            <div class="cover-container">
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
                	<p><?php pt('This biography is currently not available.') ?></p>

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
