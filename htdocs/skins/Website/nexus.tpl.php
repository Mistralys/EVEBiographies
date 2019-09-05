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
        $this->bios = $this->getVar('biographies');
        
        ob_start();
        
        ?>
        	<p>
        		<?php pt('Browsing available biographies:') ?>
        	</p>
        	<ul>
        		<?php 
            		foreach($this->bios as $bio) {
            		    ?>
            		    	<li>
            		    		<a href="<?php echo $bio->getViewURL() ?>">
            		    			<?php echo $bio->getCharacter()->getName() ?>
        		    			</a>
            		    	</li>
            		    <?php 
            		}   
        		?>
        	</ul>
        <?php 
        
		return ob_get_clean();
	}
}        