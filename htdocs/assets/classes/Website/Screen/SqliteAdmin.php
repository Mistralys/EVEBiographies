<?php

namespace EVEBiographies;

class Website_Screen_SqliteAdmin extends Website_Screen
{
    protected function _start()
    {
        if(!$this->website->isUserAuthenticated() || !$this->website->getCharacter()->isAdmin()) {
            $this->redirect($this->website->createScreen('Administration')->getURL(array('action' => 'sqliteadmin')));
        }
    }
    
    public function requiresAuthentication()
    {
        return false;
    }
    
    protected function getSkinID()
    {
        return 'Website';
    }
    
    public function getPageTitle()
    {
        return t('Biographies showcase');
    }
    
    public function getNavigationTitle()
    {
        return t('Showcase');
    }
    
    public function getDispatcher()
    {
        return 'storage/';
    }
    
    public function getPrettyDispatcher()
    {
        return 'storage/';
    }
    
    protected function _render()
    {
        ob_start();
        
        ?>
	        <p style="padding-left:9px">
    		    <a href="<?php echo $this->website->createScreen('Administration')->getURL() ?>">
    		    	&laquo; <?php pt('Back to %1$s', $this->website->getName()) ?>
		    	</a>
        	</p>
        <?php 
        
        $headerHTML = ob_get_clean();
        
        ob_start();
        
        require_once APP_ROOT.'/storage/phpliteadmin.php';
        
        $html = ob_get_clean();
        
        $result = array();
        preg_match_all('/<body[^<>]*>/i', $html, $result, PREG_PATTERN_ORDER);
        
        $tag = $result[0][0];
        
        $html = str_replace(
            $tag, 
            $tag.$headerHTML, 
            $html
        );
        
        return $html;
    }
}