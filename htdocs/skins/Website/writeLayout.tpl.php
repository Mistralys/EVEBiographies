<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend/WriteTab.php';

class Template_Website_writeLayout extends Skins_Skin_Template_Frontend_WriteTab
{
    protected function _renderTab() : string
    {
        ob_start();
        
        ?>
        	<label for=""><?php pt('Portrait') ?></label><br>
        	<?php 
        	
	        	if($this->character->hasPortrait()) {
	        	    ?>
	        	    	<div class="portrait-container">
	        	    		<img src="<?php echo $this->character->getPortraitURL() ?>" class="character-portrait">
	        	    		<a href="<?php echo $this->character->getDeletePortraitURL() ?>" class="btn btn-warning btn-sm">
	        	    			<i class="fa fa-times"></i>
	        	    			<?php pt('Delete image') ?>
        	    			</a>
	        	    	</div>
        	    	<?php 
	        	}
        	
        	?>
        	<?php echo $this->renderForm($this->form) ?>

        <?php 
        
		return ob_get_clean();
	}
}        