<?php

namespace EVEBiographies;

abstract class Skins_Skin_Template_Frontend extends Skins_Skin_Template
{
    abstract protected function _renderContent();
    
    protected function isFullPage() : bool
    {
        return true;
    }
    
    protected function _renderBody()
    {
        ob_start();
            
        ?>
            <?php echo $this->renderNavigation('Main'); ?>
        
	        <main role="main" class="with-navbar">
	        	<div class="container">
    	        	<?php echo $this->renderMessages() ?>
    	        	<h1><?php echo $this->screen->getPageTitle() ?></h1>
    	        	<?php echo $this->_renderContent() ?>
	        	</div>
            </main>
        
            <footer>
                <div class="container">
                	<p>
                		<?php 
                		  pts('Brought to you by %1$s.', '<a href="http://eve.aeonoftime.com">AeonOfTime</a>'); 
                		  pts('Like this service?');
                		  pts('Ingame ISK donations are always welcome \o/');
            		  ?>
            		</p>
            		<p>
            			<?php 
            			     pts('All EVE Online related materials are property of CCP Games.');
            			     pts('See %1$s.', '<a href="'.$this->getScreenURL('Legal').'">'.t('Legal Notice').'</a>');
            			?>
            		</p>
            		<p>
            			<a href="<?php echo APP_GITHUB_URL ?>"><?php pts('Source on GitHub') ?></a>
            			|
            			<a href="<?php echo APP_GITHUB_URL ?>/issues"><?php pts('Report an issue') ?></a>
            		</p>
                </div>
        	</footer>
        <?php 
        
		return ob_get_clean();
    }
}