<?php

namespace EVEBiographies;

require_once 'Utils.php';
require_once 'DB.php';
require_once 'Request.php';
require_once 'Mailer.php';
require_once 'Website/Exception.php';
require_once 'Website/Screen.php';
require_once 'Website/AdminCharacter.php';

class Website
{
    const ERROR_INVALID_LOGGING_MODE = 37201;
    
    const ERROR_ADMIN_CHARACTERS_EMPTY = 37202;
    
   /**
    * @var Biographies
    */
    protected $bios;

   /**
    * @var Website_Screen
    */
    protected $screen;

   /**
    * @var Characters
    */
    protected $characters;

   /**
    * @var Characters_Character
    */
    protected $character;

   /**
    * @var Request
    */
    protected $request;

    /**
     * @var Website_Logger
     */
    protected static $logger;
    
    protected function __construct()
    {
        $this->request = new Request();
    }
    
    public static function boot($screenID)
    {
        session_start();
        
        //$_SESSION = array();
        
        self::initLogging();
        self::initAdmins();
        
        self::log('');
        self::log('Starting request [#'.APP_REQUEST_ID.'] | Session ID ['.session_id().'] | Date ['.date('Y-m-d').']');
        
        try
        {
            $website = new Website();
            $screen = $website->createScreen($screenID);
            $website->start($screen);

            return $website;
        }
        catch(Website_Exception $e)
        {
            $e->display();
        }

        return null;
    }
    
    protected static function initLogging()
    {
        if(APP_LOG_MODE === APP_LOGMODE_NONE) {
            return;
        }
        
        if(!in_array(APP_LOG_MODE, array(APP_LOGMODE_ECHO, APP_LOGMODE_FILE))) 
        {
            throw new Website_Exception(
                'Invalid logging mode',
                self::ERROR_INVALID_LOGGING_MODE
            );
        }
        
        require_once 'Website/Logger.php';
        
        $loggerFile = 'Website/Logger/'.APP_LOG_MODE.'.php';
        
        require_once $loggerFile;
        
        $loggerClass = '\EVEBiographies\Website_Logger_'.APP_LOG_MODE;
        
        self::$logger = new $loggerClass();
    }
    
    public function createScreen($screenID) : Website_Screen
    {
        $screenClass = 'Website_Screen_'.$screenID;
        require_once str_replace('_', '/', $screenClass).'.php';
        $class = 'EVEBiographies\\'.$screenClass;
        $screen = new $class($this);

        return $screen;
    }

    public static function bootAndDisplay($screenID)
    {
        $website = self::boot($screenID);
        if($website) {
            $website->display($screenID);
        }
    }

    public function getScreen() : Website_Screen
    {
        return $this->screen;
    }

    public function getRequest() : Request
    {
        return $this->request;
    }

    public function getCharacter() : Characters_Character
    {
        if(isset($this->character)) {
            return $this->character;
        }

        if($this->isUserAuthenticated()) {
            $this->character = $this->createCharacters()->getByID($_SESSION['character_id']);
            return $this->character;
        }

        throw new \Exception(
            'No character available'
        );
    }

    protected function checkAuth()
    {
        if($this->isUserAuthenticated())
        {
            $this->log('Auth | User is authenticated.');
            
            // If the screen requires an administrator to be logged in,
            // log the current user out if it is not an admin.
            if($this->screen->requiresAdmin() && !$this->getCharacter()->isAdmin())
            {
                // force new login by resetting the session
                unset($_SESSION['character_id']);
                
                $this->log('Auth | Screen requires admin, user is not admin - removing character ID.');
            }
            else
            {
                return;
            }
        }

        // initialize the information we will need to keep track of
        // the authentication process steps
        if(!isset($_SESSION['auth']))
        {
            $this->log('Auth | Initializing auth session.');
            
            // @TODO use the APP_URL to rebuild this to avoid injection
            $landingURL = $_SERVER['REQUEST_URI'];

            $_SESSION['auth'] = array(
                'landing_url' => $landingURL,
                'info_shown' => false,
                'admin_required' => $this->screen->requiresAdmin()
            );
        }

        // Has the auth info screen been shown to the user? This screen tells
        // the user that they have to log in using EVE's SSO, before actually
        // sending them there.
        if(!$_SESSION['auth']['info_shown'])
        {
            $this->log('Login info not shown, redirect to auth info screen.');
            
            $this->screen->redirect($this->createScreen('AuthInfo')->getURL());
        }

        $this->log('Auth | Redirecting to EVE SSO');
        
        // redirect to the EVE SSO to select a character
        $url = $this->createEVEAuth()->getLoginURL($_SESSION);
        $this->screen->redirect($url);
    }

    public function isUserAuthenticated()
    {
        return isset($_SESSION['character_id']);
    }
    
    protected function start(Website_Screen $screen)
    {
        $this->screen = $screen;

        if($screen->requiresAuthentication()) {
            $this->checkAuth();
        }

        $this->screen->start($this);
    }
    
    public function display()
    {
        echo $this->render();
    }

    public function render()
    {
        return $this->screen->render();
    }

   /**
    * @var \EVEBiographies\DB
    */
    protected static $db;

   /**
    * Creates/returns the global database instance.
    * @return \EVEBiographies\DB
    */
    public static function createDB()
    {
        if(!isset(self::$db)) {
            self::$db = new DB();
        }

        return self::$db;
    }

   /**
    * @var \zkillboard\crestsso\CrestSSO
    */
    protected $eveAuth;

   /**
    * Creates/returns the EVE authentication library instance.
    * @return \zkillboard\crestsso\CrestSSO
    */
    public function createEVEAuth()
    {
        if(!isset($this->eveAuth)) {
            $this->eveAuth = new \zkillboard\crestsso\CrestSSO(APP_CREST_CLIENT_ID, APP_CREST_SECRET_KEY, APP_CREST_CALLBACK_URL);
        }

        return $this->eveAuth;
    }

   /**
    * Creates/returns the characters instance.
    * @return \EVEBiographies\Characters
    */
    public function createCharacters() : Characters
    {
        return $this->createCollection('Characters');
    }

   /**
    * Creates/returns the biographies collection management class.
    * @return \EVEBiographies\Biographies
    */
    public function createBiographies() : Biographies
    {
        return $this->createCollection('Biographies');
    }

   /**
    * @var DB_Collection[]
    */
    protected $collections = array();

    protected function createCollection($name) : DB_Collection
    {
        if(isset($this->collections[$name])) {
            return $this->collections[$name];
        }

        require_once $name.'.php';

        $class = 'EVEBiographies\\'.$name;
        $collection = new $class($this);

        require_once str_replace('_', '/', $collection->getRecordClass()).'.php';

        $this->collections[$name] = $collection;

        return $collection;
    }

    protected $skins;

    /**
     * Creates a new instance of the skins collection manager.
     * @return \EVEBiographies\Skins
     */
    public function createSkins()
    {
        if(!isset($this->skins)) {
            require_once 'Skins.php';
            $this->skins = new Skins($this);
        }

        return $this->skins;
    }


    protected $bgs;

   /**
    * Creates a new instance of the backgrounds collection manager.
    * @return \EVEBiographies\Backgrounds
    */
    public function createBackgrounds()
    {
        if(!isset($this->bgs)) {
            require_once 'Backgrounds.php';
            $this->bgs = new Backgrounds();
        }

        return $this->bgs;
    }

    protected $fonts;

   /**
    * Creates a new instance of the fonts collection manager.
    * @return \EVEBiographies\Fonts
    */
    public function createFonts()
    {
        if(!isset($this->fonts)) {
            require_once 'Fonts.php';
            $this->fonts = new Fonts();
        }

        return $this->fonts;
    }

    public static function getName()
    {
        return t('EVE Biographies');
    }

    public static function getAdmins()
    {
        return self::$admins;
    }
    
   /**
    * @var Website_AdminCharacter[]
    */
    protected static $admins;
    
    protected static function initAdmins()
    {
        $data = unserialize(APP_ADMIN_CHARACTERS);
        
        self::$admins = array();
        
        $hasNotification = false;
        
        foreach($data as $entry) 
        {
            if(!isset($entry['character']) || !isset($entry['email']) || !isset($entry['notifications'])) {
                self::log('Invalid entry in the administrators list, one of the required keys is missing in the array.');
                continue;
            }
            
            $admin = new Website_AdminCharacter(
                $entry['character'], 
                $entry['email'], 
                $entry['notifications']
            );
            
            self::$admins[] = $admin;
            
            if($admin->hasNotifications()) {
                $hasNotification = true;
            }
        }
        
        if(empty(self::$admins)) {
            throw new Website_Exception(
                'No valid admin characters found, check your configuration. Enable logging to debug if needed.', 
                self::ERROR_ADMIN_CHARACTERS_EMPTY
            );
        }
        
        if(!$hasNotification) {
            self::$admins[0]->enableNotification();
        }
    }
    
    public function createLegalMailer($subject)
    {
        $mailer = $this->createMailer($subject);
        
        $recipient = $mailer->createRecipient(APP_EMAIL_LEGAL, 'Legal');
        $mailer->addRecipient($recipient);
        
        return $mailer;
    }
    
    public function createAdminMailer($subject)
    {
        $mailer = $this->createMailer($subject);
        
        $admins = $this->getAdmins();
        
        foreach($admins as $admin) 
        {
            if(!$admin->hasNotifications()) {
                continue;
            }
            
            $recipient = $mailer->createRecipient($admin->getEmail(), $admin->getName()); 
            $mailer->addRecipient($recipient);
        }
        
        return $mailer;
    }
    
    public function createMailer($subject)
    {
        return new Mailer($subject);
    }
    
    public static function log($message)
    {
        if(!isset(self::$logger)) {
            return;
        }
        
        $line = sprintf(
            '%s | %s',
            date('H:i:s'),
            $message
        );
        
        self::$logger->debug($line);
    }
    
    public static function getAdminNames()
    {
        $result = array();
        
        foreach(self::$admins as $admin) {
            $result[] = $admin->getName();
        }
        
        return $result;
    }
}