<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend.php';

class Template_Website_write extends Skins_Skin_Template_Frontend
{
   /**
    * @var Website_Screen_Write
    */
    protected $screen;

    protected function _renderContent()
    {
        $char = $this->screen->getCharacter();
        $bio = $char->getBiography();

        $this->addStylesheet('screen-write.css');
        $this->addJavascript('screen-write.js');

        // initialize elements that have a thumbnail selection
        $ids = $this->getVar('thumbnail-elements');
        if(is_array($ids)) {
            foreach($ids as $id) {
                $this->addJSOnload(sprintf(
                    '$(\'#%s\').change()',
                    $id
                ));
            }
        }

        ob_start();

        ?>
        	<p>
        		<?php
        		    pts('This is where you can write %1$s\' biography, and configure the layout of the biography page.', $char->getName());
        		?>
    		</p>
    		<p>
    			<?php
    			    pts('Publish status:');
    			    echo $bio->getPublishStatePretty();
    			?>
    		</p>
    		<?php
        		if($bio->isBlocked())
        		{
        		    ?>
        		    	<div class="alert alert-danger">
        		    		<b><?php
    		    		        pts('Your biography has been censored by a moderator, so it is currently hidden.');
		    		        ?></b>

		    		        <?php
    		    		        pt('If you wish to have it reviewed again by a moderator, make the necessary changes, and click the %1$s button below.', '"'.t('Save and request moderation').'"');

		    		        ?>
        		    	</div>
        		    <?php
        		}
    		?>
    		<br>
    		<ul class="nav nav-pills">
    			<?php
    			    $tabs = $this->getVar('tabs');
    			    $activeTab = $this->getVar('active-tab');

    			    foreach($tabs as $tabID => $def)
    			    {
    			        ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if($tabID == $activeTab) { echo 'active'; } ?>" href="<?php echo $def['url'] ?>">
                                	<?php echo $def['label'] ?>
                            	</a>
                            </li>
    			        <?php
    			    }
    			?>
            </ul>
            <p></p>
            <br>
        	<?php
	        	echo $this->getVar('tab-html');
        	?>
    	<?php

		return ob_get_clean();
	}
}
