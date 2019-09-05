<?php

namespace EVEBiographies;

abstract class Skins_Skin_Template_Read extends Skins_Skin_Template
{
   /**
    * @var Characters_Character
    */
    protected $character;

   /**
    *
    * @var Biographies_Biography
    */
    protected $biography;

   /**
    * @var Backgrounds_Background
    */
    protected $background;

   /**
    * @var Fonts_Font
    */
    protected $font;

    protected function preRender()
    {
        $this->character = $this->getVar('character');
        $this->biography = $this->getVar('biography');
        $this->font = $this->biography->getFont();

        if($this->skin->supportsBackground()) {
            $this->background = $this->biography->getBackground();
        }
    }

    abstract protected function _renderContent();

    abstract protected function _initCSS();

    abstract protected function _renderNotAvailable();

    protected function isFullPage() : bool
    {
        return true;
    }

    protected function _renderBody()
    {
        $html = $this->_initCSS();

        if(!$this->biography->isValid())
        {
            $html = $this->_renderNotAvailable();
        }
        else
        {
            $html = $this->_renderContent();
        }

        $this->addStylesheet($this->font->getURL());

        ob_start();
        ?>
        	<style>
                BODY{
                	font-family:'<?php echo $this->font->getFontFamily() ?>', <?php echo $this->font->getFallbackFamily() ?>;
                    font-weight:<?php echo $this->font->getWeight() ?>;
                	<?php if($this->background) { ?>background-image:url(<?php echo $this->background->getURL() ?>);<?php } ?>
                }
                .bio-body{
                    font-size:<?php echo $this->font->getDefaultSize() ?>rem;
            	}
            </style>
        <?php

        $html .= ob_get_clean();

        return $html;
    }

    protected function renderFooterText()
    {
        $user = $this->screen->getCharacter();

        ?>
            <p>
            	<span style="float:right">
                	<?php pts('Logged in as %1$s.', $user->getName()) ?>
                	<?php
                	    if($user === $this->character)
                	    {
                	        ?>
                	        	<a href="<?php echo $this->getScreenURL('Write') ?>"><?php pt('Edit your biography') ?> &raquo;</a>
                	        <?php
                	    }
                	    else
                	    {
                	        ?>
                	        	<?php pts('Write your own biography!') ?>
                	        	<a href="<?php echo $this->getScreenURL('Write') ?>"><?php pt('Start now') ?> &raquo;</a>
                	        <?php
                	    }
                	?>
            	</span>
            	<?php pt('Want to read more?') ?> <a href="<?php echo $this->getScreenURL('Nexus') ?>"><?php pt('Browse all biographies') ?> &raquo;</a>
            	|
	           	<a href="<?php echo $this->getScreenURL('About') ?>"><?php pt('About this service') ?></a>
    		</p>
        	<p>
        		<?php
        		    if($this->background) {
            		    $link =
            		    '<a href="'.$this->background->getLink().'">'.
            		        $this->background->getCredits().
            		    '</a>';

        		        echo
        		        t('Background image courtesy of %1$s.', $link).' '.
        		        '<abbr title="'.$this->background->getLicense().'">('.t('License').')</abbr>';
        		    }
        		?>
        	</p>
    		<p>
        		<?php pts('Offensive content, copyright infringment or other issues?') ?> <a href="<?php echo $this->character->getReportURL() ?>"><?php pt('Report this biography.') ?></a>
    		</p>
		<?php
    }
}
