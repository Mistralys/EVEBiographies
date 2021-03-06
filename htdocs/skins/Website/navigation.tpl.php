<?php

namespace EVEBiographies;

class Template_Website_navigation extends Skins_Skin_Template
{
    protected function isFullPage() : bool
    {
        return false;
    }
    
    protected function _renderBody()
    {
        $items = $this->nav->getItems();
        
        ob_start();
        
        ?>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
              <div class="container">
                <a class="navbar-brand" href="<?php echo APP_URL ?>">
                	<img src="<?php echo APP_URL ?>/img/logo.png" width="32"/>
                	<?php echo $this->screen->getWebsite()->getName() ?>
            	</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav-menu" aria-controls="nav-menu" aria-expanded="false" aria-label="<?php pt('Toggle navigation') ?>">
                  <span class="navbar-toggler-icon"></span>
                </button>
        
                <div class="collapse navbar-collapse" id="nav-menu">
                  <ul class="navbar-nav mr-auto">
                  	<?php 
                  	 foreach($items as $item) {
                  	     ?>
                            <li class="nav-item<?php if($item->isActive()) { echo ' active'; } ?>">
                              <a class="nav-link" href="<?php echo $item->getURL() ?>"><?php echo $item->getLabel() ?></a>
                            </li>
                  	     <?php 
                  	 }
                  	
                  	?>
                  </ul>
                  <?php 
                       if($this->screen->isUserAuthenticated()) 
                       {
                           $char = $this->screen->getCharacter();
                           
                           ?>
                               <span class="nav-item">
                               	   <i class="fa fa-user-astronaut"></i> 
                               	   <b><?php echo $char->getName() ?></b>
                           	   </span>
                           	   <a href="<?php echo $this->getScreenURL('Logout') ?>" class="btn btn-warning btn-sm">
                           		    <i class="fa fa-sign-out-alt"></i>
                           		    <?php pt('Log out') ?>
                           	   </a>
                           <?php 
                       }
                       else 
                       {
                           ?>
                              <a href="<?php echo $this->getScreenURL('Nexus', array('authinfo' => 'no')) ?>" class="btn btn-secondary btn-sm nav-item" title="<?php pt('Sign in with an EVE Online character to browse biogrphies.') ?>">
                                <i class="fa fa-sign-in-alt"></i>
                              	<?php pt('Sign in') ?>
                              </a>
                              <a href="<?php echo $this->getScreenURL('Write') ?>" class="btn btn-primary btn-sm nav-item" title="<?php pt('Sign in with an EVE Online character to write your biography') ?>">
                              	<i class="fa fa-user-astronaut"></i>
                              	<?php pt('Write your biography') ?>
                              </a>
                           <?php 
                       }
                  ?>
                </div>
              </div>
            </nav>
        <?php 
        
		return ob_get_clean();
	}
	
   /**
    * @var Website_Navigation
    */
	protected $nav;
	
	protected function preRender()
	{
	    $this->nav = $this->getVar('nav');
	}
}        
