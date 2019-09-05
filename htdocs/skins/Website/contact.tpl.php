<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend.php';

class Template_Website_contact extends Skins_Skin_Template_Frontend
{
    protected function _renderContent()
    {
        ob_start();
        
        $addresses = array(
            array(
                'label' => t('General information:'),
                'mail' => APP_EMAIL_CONTACT
            ),
            array(
                'label' => t('Copyright or legal issues:'),
                'mail' => APP_EMAIL_LEGAL
            ),
        );
        
        foreach($addresses as $def) 
        {
            ?>
                <p>
                	<?php echo $def['label'] ?>
                	<a href="mailto:<?php echo $def['mail'] ?>"><?php echo $def['mail'] ?></a>
                </p>
            <?php
        }
        
        return ob_get_clean();
    }
}