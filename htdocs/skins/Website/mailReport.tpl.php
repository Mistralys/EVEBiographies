<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Email.php';

class Template_Website_mailReport extends Skins_Skin_Template_Email
{
    /**
     * @var Characters_Character
     */
    protected $targetCharacter;
    
   /**
    * @var Biographies_Biography
    */
    protected $targetBio;
    
    protected function _renderBody()
    {
        $this->targetCharacter = $this->getVar('target-character');
        $this->targetBio = $this->targetCharacter->getBiography();
        
        $type = $this->getVar('type');
        $comments = $this->getVar('comments');
        $email = $this->getVar('email');
        
        ob_start();
        
        ?>
        	<p>
        		<b>Target character:</b> <?php echo $this->targetCharacter->getName() ?>
        	</p>
        	<p>
        		<b>Report cause:</b> <?php $type ?>
	       	</p>
	       	<p>
	       		<b>Reported by:</b> <?php echo $this->screen->getCharacter()->getName() ?> 
	       		<a href="mailto:<?php echo $email ?>"><?php echo $email ?></a>
	       	</p>
        	<p>
        1		<b>Comments:</b>
    		</p>
    		<div style="font-family:monospace;border:solid 2px #ddd;border-radius:6px;padding:13px">
        		<?php echo $comments ?>
        	</div>
        	<p>
        		<b>Admin actions:</b>
        	</p>
        	<p>
				<a href="<?php echo $this->targetBio->getBlockURL() ?>">Block the biography</a><br/>
        	</p>
        <?php 
        
        return ob_get_clean();
    }
}