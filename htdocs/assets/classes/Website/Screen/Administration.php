<?php

namespace EVEBiographies;

class Website_Screen_Administration extends Website_Screen
{
    protected function _start()
    {
    }
    
    protected function getExitURL() : string
    {
        return $this->website->createScreen('Nexus')->getURL();
    }
    
    public function requiresAuthentication() : bool
    {
        return true;
    }
    
    public function requiresAdmin()
    {
        return true;
    }
    
    protected function getSkinID()
    {
        return 'Website';
    }
    
    public function getPageTitle()
    {
        return t('Administration');
    }
    
    public function getNavigationTitle()
    {
        return t('Admin');
    }
    
    public function getDispatcher()
    {
        return 'admin.php';
    }
    
    public function getPrettyDispatcher()
    {
        return 'admin.php';
    }
    
    protected function _render()
    {
        if(!$this->website->isUserAuthenticated() || !$this->getCharacter()->isAdmin()) {
            $this->redirectWithErrorMessage('Only administrators may use these functions', $this->getExitURL());
        }
        
        $action = $this->request->getParam('action');
        
        $method = 'action_'.$action;
        if(method_exists($this, $method)) {
            return $this->$method();
        }

        $tpl = $this->createTemplate('adminOverview');
        
        return $tpl->render();
    }
    
   /**
    * @var Characters_Character
    */
    protected $character;
    
    protected function action_block()
    {
        $this->requireCharacter();
        
        $this->startTransaction();
         
            $bio = $this->character->getBiography();
            $bio->block();
        
        $this->endTransaction();
        
        $this->redirectWithSuccessMessage(
            t('The biography for %1$s has been blocked.', $this->character->getName()),
            $this->getExitURL()
        );
    }
    
    protected function action_unblock()
    {
        $this->requireCharacter();

        $this->startTransaction();
        
            $bio = $this->character->getBiography();
            $bio->unblock();
        
        $this->endTransaction();
        
        $this->redirectWithSuccessMessage(
            t('The biography for %1$s has been unblocked.', $this->character->getName()),
            $this->getExitURL()
        );
    }

    protected function requireCharacter()
    {
        $slug = $this->request->getParam('char');
        
        $this->character = $this->website->createCharacters()->getBySlug($slug);
        if(!$this->character) {
            $this->redirectWithErrorMessage(
                t('No such character slug exists.'),
                $this->getExitURL()
            );
        }
    }
}