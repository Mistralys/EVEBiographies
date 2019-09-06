<?php

namespace EVEBiographies;

class Utils
{
    public static function isStringHTML($string) : bool
    {
        if(preg_match('%<[a-z/][\s\S]*>%siU', $string)) {
            return true;
        }
        
        $decoded = html_entity_decode($string);
        if($decoded !== $string) {
            return true;
        }
        
        return false;
    }
}
