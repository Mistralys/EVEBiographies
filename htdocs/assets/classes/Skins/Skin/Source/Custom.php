<?php

namespace EVEBiographies;

abstract class Skins_Skin_Source_Custom extends Skins_Skin
{
    public function isBiographySkin() : bool
    {
        return true;
    }
    
    public function configureTemplate(Skins_Skin_Template $tpl)
    {
        $tpl->addStylesheet('normalize.css');
    }
}