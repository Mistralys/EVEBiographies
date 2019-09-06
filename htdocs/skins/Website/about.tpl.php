<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend.php';

class Template_Website_about extends Skins_Skin_Template_Frontend
{
    protected function _renderContent()
    {
        ob_start();

        ?>
        	<p class="abstract">
        		<?php
        		    pts('EVE Online\'s ingame biographies have a pretty short size limit.');
        		    pts('Your character deserves more!');
        		    pts('Discover this free service, which lets you create a pretty biography out of game with many customization options.');
        		?>
        	</p>
        	<h2><?php pt('How does it work?') ?></h2>
        	<p>
        		<?php
        		    pts('Simply sign in via EVE Online\'s SSO to browse all available biographies.');
        		    pts('To create or edit your own biography, select the relevant character when you sign on:');
        		    pts('Nothing more is needed!');
        		    pts('You can start writing right away.');
    		?>
        	</p>
        	<h2><?php pt('Features') ?></h2>
        	<ul>
        		<li><?php pts('Customizing:'); pt('Several color schemes available (dark and light)') ?></li>
        		<li><?php pts('Customizing:'); pt('Lots of background images to choose from') ?></li>
        		<li><?php pts('Customizing:'); pt('Choose a font for the text') ?></li>
        		<li><?php pts('Customizing:'); pt('Optionally upload your character\'s portrait') ?></li>
        		<li><?php pts('Editing:'); pt('Full text editor for markdown syntax') ?></li>
        		<li><?php pts('Editing:'); pt('Fullscreen text editing') ?></li>
        	</ul>
        	<h2><?php pt('Start now!') ?></h2>
        	<p>
        		<a href="<?php echo $this->getScreenURL('Write') ?>" class="btn btn-primary">
        			<i class="fa fa-user-astronaut"></i>
        			<?php pt('Write your biography') ?>
    			</a>

    			<?php pts('or') ?>

        		<a href="<?php echo $this->getScreenURL('Nexus') ?>" class="btn btn-secondary">
			        <i class="fa fa-portrait"></i>
			        <?php pt('Browse biographies'); ?>
    			</a>
        	</p>
        <?php

		return ob_get_clean();
    }
}
