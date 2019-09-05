<?php

namespace EVEBiographies;

require_once 'DB.php';
require_once 'Website/Exception.php';
require_once 'Website/Screen.php';
require_once 'Request.php';
require_once 'Mailer.php';

class Website
{
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

    protected function __construct()
    {
        $this->request = new Request();
    }

    public static function boot($screenID)
    {
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
            // If the screen requires an administrator to be logged in,
            // log the current user out if it is not an admin.
            if($this->screen->requiresAdmin() && !$this->getCharacter()->isAdmin())
            {
                // force new login by resetting the session
                unset($_SESSION['character_id']);
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
            $this->screen->redirect($this->createScreen('AuthInfo')->getURL());
        }

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
        session_start();

        //$_SESSION = array();

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

    public function createAdminMailer($subject)
    {
        return $this->createMailer(APP_EMAIL_ADMIN, 'Administrator', $subject);
    }

    public function createMailer($email, $name, $subject)
    {
        return new Mailer(Mailer::createRecipient($email, $name), $subject);
    }
}

/**
 * Translates the specified text to the current UI locale.
 * @return string
 */
function t()
{
    $args = func_get_args();
    if(count($args) == 1) {
        return $args[0];
    }

    return call_user_func_array('sprintf', $args);
}

function pt()
{
    $args = func_get_args();
    if(count($args) == 1) {
        echo $args[0];
        return;
    }

    echo call_user_func_array('sprintf', $args);
}

function pts()
{
    $args = func_get_args();
    if(count($args) == 1) {
        echo $args[0].' ';
        return;
    }

    echo call_user_func_array('sprintf', $args).' ';
}

function nextJSID()
{
    static $counter = 0;

    $counter++;

    return 'el'.$counter;
}
