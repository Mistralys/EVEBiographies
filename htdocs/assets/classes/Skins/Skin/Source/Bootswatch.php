<?php

namespace EVEBiographies;

abstract class Skins_Skin_Source_Bootswatch extends Skins_Skin
{
    public function isBiographySkin() : bool
    {
        return true;
    }
    
    public function configureTemplate(Skins_Skin_Template $tpl)
    {
        $tpl->addStylesheet('bootstrap.min.css');
        
        // we use Cyborg's CSS file as the main file. The other bootswatch
        // skins only have color changes.
        $tpl->addStylesheet($this->skins->getURL().'/Cyborg/common.css');
        
        $tpl->addJavascript('https://code.jquery.com/jquery-3.3.1.slim.min.js', 'sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo');
        $tpl->addJavascript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', 'sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49');
        $tpl->addJavascript('https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', 'sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy');
    }
    
    public function supportsBackground() : bool
    {
        return true;
    }
    
    abstract public function getCanvasBackground();

    abstract public function getCanvasOpacity();
    
    abstract public function getTextColor();
    
    abstract public function getBodyBackground();
    
    abstract public function getDialogueColor();
}