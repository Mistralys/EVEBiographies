<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend/WriteTab.php';

class Template_Website_writeDelete extends Skins_Skin_Template_Frontend_WriteTab
{
    protected function _renderTab() : string
    {
        ob_start();
        
        ?>
			<p>
				<b class="text-danger"><?php pt('This lets you delete your biography permanently.'); ?></b>
			</p>
			<p>
				<?php 
				    pts('Your current biography text and settings, as well as your character account, will be deleted.');
				    pts('However, you can create a new biography again anytime afterwards.');
			    ?>
			</p>
			<p class="text-warning">
				<?php pt('This cannot be undone, are you sure?') ?>
			</p>
			<p>
				<a href="<?php echo $this->screen->getTabURL('delete', array('confirm' => 'yes')) ?>" class="btn btn-danger">
					<i class="fa fa-times"></i>
					<?php pt('Yes, delete now') ?>
				</a>
			</p>
        <?php 
        
		return ob_get_clean();
	}
}        