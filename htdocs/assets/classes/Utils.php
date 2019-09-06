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
