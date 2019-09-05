<?php

namespace EVEBiographies;

abstract class Website_Screen
{
    const ERROR_CANNOT_CREATE_TEMPLATE_BEFORE_START = 37101;

   /**
    * @var Website
    */
    protected $website;

   /**
    * @var Skins_Skin
    */
    protected $skin;

   /**
    * @var Characters_Character
    */
    protected $character;

    protected $simulation = false;

   /**
    * @var Request
    */
    protected $request;

    public function __construct(Website $website)
    {
        $this->website = $website;
        $this->request = $website->getRequest();
    }

    protected $started = false;

    public function start()
    {
        if(isset($_REQUEST['simulate_only']) && $_REQUEST['simulate_only'] == 'yes') {
            $this->simulation = true;
        }

        if($this->requiresAuthentication() && $this->website->isUserAuthenticated()) {
            $this->character = $this->website->getCharacter();
        }

        $this->skin = $this->website->createSkins()->getByID($this->getSkinID());

        $this->_start();

        $this->started = true;
    }

    public function isUserAuthenticated()
    {
        return $this->website->isUserAuthenticated();
    }

    public function getID()
    {
        return str_replace('EVEBiographies\Website_Screen_', '', get_class($this));
    }

    public function getSkin()
    {
        return $this->skin;
    }

    public function render()
    {
        try
        {
            $html = $this->_render();
        }
        catch(Website_Exception $e)
        {
            $html = 'Exception: '.$e->getMessage();
        }

        return $html;
    }

    public function getWebsite()
    {
        return $this->website;
    }

   /**
    * When a character is logged in, returns the character instance.
    * @return Characters_Character|NULL
    */
    public function getCharacter() : ?Characters_Character
    {
        return $this->website->getCharacter();
    }

    abstract protected function _start();

    abstract protected function _render();

    abstract public function requiresAuthentication();

    abstract protected function getSkinID();

    abstract public function getPageTitle();

    abstract public function getNavigationTitle();

    abstract public function getDispatcher();

    abstract public function getPrettyDispatcher();

    public function requiresAdmin()
    {
        return false;
    }

    protected function displayError($message)
    {
        ?>
        <?php
        exit;
    }

    protected function createTemplate($id) : Skins_Skin_Template
    {
        if(!$this->started) {
            throw new Website_Exception(
                'Cannot create template before screen is started',
                self::ERROR_CANNOT_CREATE_TEMPLATE_BEFORE_START
            );
        }

        return $this->skin->createTemplate($id);
    }

    const MESSAGE_SUCCESS = 'success';

    const MESSAGE_WARNING = 'warning';

    const MESSAGE_ERROR = 'danger';

    const MESSAGE_INFO = 'info';

    protected function redirectWithSuccessMessage($message, $url)
    {
        $this->redirectWithMessage(self::MESSAGE_SUCCESS, $message, $url);
    }

    protected function redirectWithWarningMessage($message, $url)
    {
        $this->redirectWithMessage(self::MESSAGE_WARNING, $message, $url);
    }

    protected function redirectWithErrorMessage($message, $url)
    {
        $this->redirectWithMessage(self::MESSAGE_ERROR, $message, $url);
    }

    protected function redirectWithInfoMessage($message, $url)
    {
        $this->redirectWithMessage(self::MESSAGE_INFO, $message, $url);
    }

    protected function redirectWithMessage($type, $message, $url)
    {
        $this->addMessage($type, $message);
        $this->redirect($url);
    }

    protected function addMessage($type, $message) : Website_Screen
    {
        if(!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = array();
        }

        $_SESSION['messages'][] = array(
            'text' => $message,
            'type' => $type
        );

        return $this;
    }

    protected function addErrorMessage($message)
    {
        $this->addMessage(self::MESSAGE_ERROR, $message);
    }

    protected function addInfoMessage($message)
    {
        $this->addMessage(self::MESSAGE_INFO, $message);
    }

    protected function addWarningMessage($message)
    {
        $this->addMessage(self::MESSAGE_WARNING, $message);
    }

    protected function addSuccessMessage($message)
    {
        $this->addMessage(self::MESSAGE_SUCCESS, $message);
    }

    public function redirect($url)
    {
        if(headers_sent()) {
            ?>
            	<hr>
            	<a href="<?php echo $url ?>">
            		<?php echo $url ?>
            	</a>
            <?php
        }

        header('Location:'.$url);
        exit;
    }

    public function createForm($id=null, $defaultValues=array())
    {
        if(empty($id)) {
            $id = 'screen-'.$this->getID();
        }

        $form = new \HTML_QuickForm2($id, 'post');

        $ds = new \HTML_QuickForm2_DataSource_Array($defaultValues);
        $form->addDataSource($ds);

        \HTML_QuickForm2_Renderer::register(
            'bootstrap',
            'EVEBiographies\Website_FormRenderer',
            APP_ROOT.'/assets/classes/Website/FormRenderer.php'
        );

        return $form;
    }

    protected function startTransaction()
    {
        $this->website->createDB()->startTransaction();
    }

    protected function endTransaction()
    {
        if($this->simulation) {
            $this->website->createDB()->rollbackTransaction();
        } else{
            $this->website->createDB()->commitTransaction();
        }
    }

    public function getURL($params=array())
    {
        return $this->getScreenURL($this->getID(), $params);
    }

    public function getScreenURL($screenID, $params=array())
    {
        $screen = $this->website->createScreen($screenID);
        $dispatcher = null;

        if(APP_PRETTY_URLS)
        {
            $dispatcher = $screen->getPrettyDispatcher();
            foreach($params as $name => $value)
            {
                $var = '{'.$name.'}';
                if(stristr($dispatcher, $var)) {
                    $dispatcher = str_replace($var, $value, $dispatcher);
                    unset($params[$name]);
                }
            }
        }
        else
        {
            $dispatcher = $screen->getDispatcher();
        }

        $url = APP_URL.'/'.$dispatcher;

        if(!empty($params)) {
            $url .= '?'.http_build_query($params);
        }

        return $url;

    }
}
