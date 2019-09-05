<?php

namespace EVEBiographies;

class Skin_Website extends Skins_Skin
{
    public function isBiographySkin() : bool
    {
        return false;
    }
    
    public function getLabel() : string
    {
        return 'Website';
    }
    
    public function supportsBackground() : bool
    {
        return false;
    }
    
    public function getLayoutTypeID() : string
    {
        return self::LAYOUT_TYPE_DARK;
    }
    
    public function configureTemplate(Skins_Skin_Template $tpl)
    {
        $tpl->addStylesheet('bootstrap.min.css');
        $tpl->addStylesheet('common.css');
        
        $tpl->addJavascript('https://code.jquery.com/jquery-3.3.1.slim.min.js', 'sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo');
        $tpl->addJavascript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', 'sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49');
        $tpl->addJavascript('https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', 'sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy');
        $tpl->addStylesheet('https://use.fontawesome.com/releases/v5.6.1/css/all.css', 'all', 'sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP');
    }
}