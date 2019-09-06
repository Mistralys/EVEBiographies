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
        <?php 
        
		return ob_get_clean();
	}
}        