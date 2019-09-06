<?php

namespace EVEBiographies;

abstract class Skins_Skin_Template
{
   /**
    * @var Website_Screen
    */
    protected $screen;
    
   /**
    * @var Skins_Skin
    */
    protected $skin;
    
    public function __construct(Skins_Skin $skin)
    {
        $this->skin = $skin;
        $this->screen = $skin->getScreen();
        
        $this->init();
    }
    
    protected function init()
    {
        // can be overridden in template classes to do things right after instantiation
    }
    
    protected function preRender()
    {
        // can be overridden in template classes to do things before rendering output
    }
    
    protected $title;
    
    protected function getTitle() : ?string
    {
        if(isset($this->title)) {
            return $this->title; 
        }
        
        return $this->screen->getPageTitle();
    }
    
    public function addStylesheet(string $fileOrURL, string $media='all', string $integrity='', string $crossorigin='anonymous') : Skins_Skin_Template
    {
        $this->skin->addStylesheet($fileOrURL, $media, $integrity, $crossorigin);
        return $this;
    }
    
    public function addJavascript(string $fileOrURL, string $integrity='', string $crossorigin='anonymous') : Skins_Skin_Template
    {
        $this->skin->addJavascript($fileOrURL, $integrity, $crossorigin);
        return $this;
    }
    
    abstract protected function _renderBody();
    
    abstract protected function isFullPage() : bool;
    
    protected function renderForm(\HTML_QuickForm2 $form) : string
    {
        return $form->render(\HTML_QuickForm2_Renderer::factory('bootstrap'));
    }
    
    protected $vars = array();
    
   /**
    * Adds a template variable that can be retrieved using {@link getVar()}.
    * @param string $name
    * @param mixed $value
    * @return Skins_Skin_Template
    */
    public function addVar(string $name, $value) : Skins_Skin_Template
    {
        $this->vars[$name] = $value;
        return $this;
    }
    
    public function addVars($vars) : Skins_Skin_Template
    {
        foreach($vars as $name => $value) {
            $this->addVar($name, $value);
        }
        
        return $this;
    }
    
   /**
    * Retrieves a previously set template variable by its name. 
    * Returns the specified default value if the variable has
    * not been set.
    * 
    * @param string $name
    * @param mixed $default
    * @return mixed
    */
    public function getVar(string $name, $default=null)
    {
        if(isset($this->vars[$name])) {
            return $this->vars[$name];
        }
        
        return $default;
    }
    
    
   /**
    * Adds a javascript statement to execute on page load via jQuery.
    * @param string $statement
    * @return Skins_Skin_Template
    */
    public function addJSOnload(string $statement) : Skins_Skin_Template
    {
        $this->skin->addJSOnload($statement);
        return $this;
    }
    
    public function render() : string
    {
        $this->skin->configureTemplate($this);
     
        $this->addCookieConsent();
        
        $this->preRender();
        
        $html = $this->_renderBody();
        
        if(!$this->isFullPage()) {
            return $html;
        }

        $includes = $this->skin->getIncludes();
        
        ob_start();
        
        ?><!doctype html>
        <html lang="en">
          <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="">
            <meta name="author" content="">
            <title><?php echo $this->getTitle() ?></title>
            <?php 
                foreach($includes['styles'] as $url => $def) {
                    ?>
                        <link rel="stylesheet" href="<?php echo $url ?>" media="<?php echo $def['media'] ?>" integrity="<?php echo $def['integrity'] ?>" crossorigin="<?php echo $def['crossorigin'] ?>">
                    <?php 
                }
            ?>
          </head>
        <body>
            <?php 
            
                echo $html;
               
                foreach($includes['scripts'] as $url => $def) {
                   ?>
                        <script src="<?php echo $url ?>" integrity="<?php echo $def['integrity'] ?>" crossorigin="<?php echo $def['crossorigin'] ?>"></script>
                   <?php 
                }
               
                $onload = $this->skin->getJSOnload();
                if(!empty($onload)) {
                    ?>
                       	<script>
                           	$(document).ready(function() {
                                <?php 
                                    foreach($onload as $statement) {
                                        echo rtrim($statement, ';').';';
                                    }
                                ?>
                           	});
                   		</script>
                   	<?php 
                }
                
                $css = $this->skin->getCSS();
                if(!empty($css)) 
                {
                    ?>
                    	<style>
                   		    <?php 
                   		         foreach($css as $selector => $statements) {
                   		             echo $selector.'{'.implode(';', $statements).';}';
                   		         }
                   		    ?>
                   		</style>
                    <?php 
                }
            ?>
          </body>
        </html><?php 
        
        return ob_get_clean();
    }
    
    protected function renderMessages()
    {
        if(!isset($_SESSION['messages']) || empty($_SESSION['messages'])) {
            return;
        }
        
        foreach($_SESSION['messages'] as $def)
        {
            ?>
           		<div class="alert alert-<?php echo $def['type'] ?>">
               		<?php echo $def['text'] ?>
           		</div>
    		<?php 
        }
               
        $_SESSION['messages'] = array();
    }
    
    protected function getScreenURL($screenID, $params=array())
    {
        return $this->screen->getScreenURL($screenID, $params);
    }
    
    protected function getURL($params=array())
    {
        return $this->screen->getURL($params);
    }
    
    protected function addCSS($selector, $statement) : Skins_Skin_Template
    {
        $this->skin->addCSS($selector, $statement);
        return $this;
    }
    
    protected function renderNavigation($name)
    {
        require_once 'Website/Navigation.php';
        
        $class = 'Website_Navigation_'.$name;
        
        require_once str_replace('_', '/', $class).'.php';
        
        $name = 'EVEBiographies\\'.$class;
        
        $nav = new $name($this->screen);
        
        return $nav->render();
    }

    public function addCookieConsent()
    {
        $this->addStylesheet('https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css');
        $this->addJavascript('https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js');
        
        $this->addJSOnload(sprintf(
            'window.cookieconsent.initialise({
              "palette": {
                "popup": {
                  "background": "#000"
                },
                "button": {
                  "background": "#f1d600"
                }
              },
              "theme": "classic",
              "content": {
                "message": "%1$s",
                "dismiss": "%2$s",
                "href": "%3$s"
              }
            });',
            t('Cookies are used to remember your preferences and to allow you to log in.'),
            t('Dismiss message'),
            $this->screen->getWebsite()->createScreen('Legal')->getURL()
        ));
    }
}