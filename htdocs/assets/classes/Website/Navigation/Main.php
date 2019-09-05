<?php

namespace EVEBiographies;

class Website_Navigation_Main extends Website_Navigation
{
    protected function configure()
    {
        $this->addAuthScreen('Nexus');
        $this->addAuthScreen('Write');
        $this->addScreen('About');
        $this->addScreen('Legal');
        $this->addScreen('Contact');
        $this->addAuthScreen('Administration');
    }
}