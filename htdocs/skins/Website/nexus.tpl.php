<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend.php';

class Template_Website_nexus extends Skins_Skin_Template_Frontend
{
   /**
    * @var Biographies_Biography[]
    */
    protected $bios;
    
    protected function _renderContent()
    {
        $this->addStylesheet('nexus.css');
        
        $this->bios = $this->getVar('biographies');
        $startEntry = $this->getVar('start');
        $endEntry = $this->getVar('end');
        $total = $this->getVar('total');
        $next = $this->getVar('url-next');
        $prev = $this->getVar('url-prev');
        
        ob_start();
        
        ?>
        	<ul class="biographies-overview">
        		<?php 
            		foreach($this->bios as $bio) 
            		{
            		    $character = $bio->getCharacter();
            		    
            		    ?>
            		    	<li class="bio-entry">
            		    		<p class="bio-thumbnail">
                		    		<a href="<?php echo $bio->getViewURL() ?>">
                		    			<img src="<?php echo $character->getPortraitURL() ?>" width="64"/>
            		    			</a>
        		    			</p>
        		    			<p class="bio-text">
                		    		<a href="<?php echo $bio->getViewURL() ?>">
                		    			<?php echo $bio->getCharacter()->getName() ?>
            		    			</a>
            		    			<br>
            		    			<?php
            		    			   pt(
            		    			       '%1$s words, %2$s views', 
            		    			       number_format($bio->countWords(), 0, '.', ' '),
            		    			       number_format($bio->countViews(), 0, '.', ' ')
        		    			       ) 
        		    			   ?>
        		    			</p>
            		    	</li>
            		    <?php 
            		}   
        		?>
        	</ul>
        	<br>
        	<p>
        		<?php
        		    $prevDisabled = '';
        		    if(!$prev) {
        		        $prevDisabled = ' disabled';
        		    }
        		    
        		    $nextDisabled = '';
        		    if(!$next) {
        		        $nextDisabled = ' disabled';
        		    }
    		    ?>
				<a href="<?php echo $prev ?>" class="btn btn-secondary btn-sm<?php echo $prevDisabled ?>">
        			<i class="fa fa-arrow-circle-left"></i>
        			<?php pt('Previous') ?>
    			</a>
        		<a href="<?php echo $next ?>" class="btn btn-secondary btn-sm<?php echo $nextDisabled ?>">
        			<?php pt('Next') ?>
        			<i class="fa fa-arrow-circle-right"></i>
    			</a>
        	</p>
        	<p>
        		<?php pts(sprintf('Showing biographies %s to %s.', $startEntry, $endEntry)); pts('Total biographies:'); echo $total ?>
        	</p>
        <?php 
        
		return ob_get_clean();
	}
}        