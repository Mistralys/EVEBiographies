<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template.php';

abstract class Skins_Skin
{
    const ERROR_UNKNOWN_SKIN_LAYOUT = 31101;
    
    const LAYOUT_TYPE_DARK = 'dark';
    
    const LAYOUT_TYPE_LIGHT = 'light';
    
   /**
    * @var string
    */
    protected $id;
    
   /**
    * @var Website_Screen
    */
    protected $screen;
    
   /**
    * @var Skins
    */
    protected $skins;
    
    protected static $layoutTypes;
    
    public function __construct(Skins $skins, Website_Screen $screen, string $id)
    {
        $this->id = $id;
        $this->screen = $screen;
        $this->skins = $skins;
        
        if(!isset(self::$layoutTypes)) {
            self::$layoutTypes = array(
                self::LAYOUT_TYPE_DARK => t('Dark'),
                self::LAYOUT_TYPE_LIGHT => t('Light')
            );
        }
    }
    
    abstract public function getLabel() : string;
    
    abstract public function supportsBackground() : bool;
    
    public function getID() : string
    {
        return $this->id;
    }
    
    public function getLayoutTypeLabel($typeID) : string
    {
        if(isset(self::$layoutTypes[$typeID])) {
            return self::$layoutTypes[$typeID];
        }
        
        throw new Website_Exception(
            'Unknown skin layout type', 
            self::ERROR_UNKNOWN_SKIN_LAYOUT
        );
    }
    
   /**
    * @return \EVEBiographies\Website_Screen
    */
    public function getScreen() : Website_Screen
    {
        return $this->screen;
    }
    
    public function getPath() : string
    {
        return APP_ROOT.'/skins/'.$this->id;
    }
    
    public function getURL() : string
    {
        return APP_URL.'/skins/'.$this->id;
    }
    
    abstract public function isBiographySkin() : bool;
    
    abstract public function configureTemplate(Skins_Skin_Template $tpl);
    
    abstract public function getLayoutTypeID() : string;
    
    public function createTemplate($id) : Skins_Skin_Template
    {
        $class = 'EVEBiographies\Template_'.$this->id.'_'.$id;
        $file = $this->getPath().'/'.$id.'.tpl.php';
        
        require_once $file;
        
        $tpl = new $class($this);
        
        $this->configureTemplate($tpl);
        
        return $tpl;
    }
    
    public function getThumbnailURL() : string
    {
        return $this->getURL().'/thumb.png';
    }

    protected $jsOnload = array();
    
    /**
     * Adds a javascript statement to execute on page load via jQuery.
     * @param string $statement
     * @return Skins_Skin
     */
    public function addJSOnload(string $statement, bool $allowDuplicates = true) : Skins_Skin
    {
        if(!$allowDuplicates && in_array($statement, $this->jsOnload)) {
            return $this;
        }
        
        $this->jsOnload[] = $statement;
        return $this;
    }
    
    public function getJSOnload()
    {
        return $this->jsOnload;
    }

    protected $css = array();
    
    public function addCSS($selector, $statement) : Skins_Skin
    {
        if(!isset($this->css[$selector])) {
            $this->css[$selector] = array();
        }
        
        $this->css[$selector][] = rtrim($statement, ';');
        
        return $this;
    }
    
    public function getCSS()
    {
        return $this->css;
    }

    protected $includes = array(
        'styles' => array(),
        'scripts' => array()
    );
    
    public function addStylesheet(string $fileOrURL, string $media='all', string $integrity='', string $crossorigin='anonymous') : Skins_Skin
    {
        return $this->addInclude('styles', $fileOrURL, $integrity, $crossorigin, $media);
    }
    
    public function addJavascript(string $fileOrURL, string $integrity='', string $crossorigin='anonymous') : Skins_Skin
    {
        return $this->addInclude('scripts', $fileOrURL, $integrity, $crossorigin);
    }
    
    protected function addInclude(string $type, string $fileOrURL, string $integrity='', string $crossorigin='anonymous', string $media='all') : Skins_Skin
    {
        $url = $fileOrURL;
        
        $info = parse_url($url);
        
        if(!isset($info['scheme'])) {
            $url = $this->getURL().'/'.basename($fileOrURL);
        }
        
        if(!in_array($url, $this->includes[$type])) {
            $this->includes[$type][$url] = array(
                'media' => $media,
                'integrity' => $integrity,
                'crossorigin' => $crossorigin
            );
        }
        
        return $this;
    }
    
    public function getIncludes()
    {
        return $this->includes;
    }
}