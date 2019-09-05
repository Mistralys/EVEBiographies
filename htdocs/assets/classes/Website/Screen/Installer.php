<?php

namespace EVEBiographies;

class Website_Screen_Installer extends Website_Screen
{
    protected function _start()
    {
        if(empty(APP_DB_PASSWORD)) {
            $this->displayError(
                t(
                    'The [%s] setting has to be set in the configuration.',
                    'APP_DB_PASSWORD'
                )
            );
        }

        $db = Website::createDB();

        if($db->exists())
        {
           $this->redirectWithSuccessMessage(
               t('The database has been installed successfully.'),
               $this->buildURL('Nexus')
           );
        }
    }

    public function requiresAuthentication()
    {
        return false;
    }

    public function getDispatcher()
    {
        return 'install.php';
    }

    public function getPrettyDispatcher()
    {
        return 'install.php';
    }

    public function getPageTitle()
    {
        return t('Installer');
    }

    public function getNavigationTitle()
    {
        return t('Installer');
    }

    protected function getSkinID()
    {
        return 'Website';
    }

    protected function _render()
    {
        return 'TODO';
    }
}
