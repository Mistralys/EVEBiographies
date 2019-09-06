<?php

namespace EVEBiographies;

class Website_Screen_About extends Website_Screen
{
    protected function _start()
    {
    }

    public function requiresAuthentication() : bool
    {
        return false;
    }

    protected function getSkinID()
    {
        return 'Website';
    }

    public function getPageTitle()
    {
        return t('Finally, limitless biographies \o/');
    }

    public function getNavigationTitle()
    {
        return t('About');
    }

    public function getDispatcher()
    {
        return 'index.php';
    }

    public function getPrettyDispatcher()
    {
        return '';
    }

    protected function _render()
    {
        $tpl = $this->skin->createTemplate('about');
        return $tpl->render();
    }
}
