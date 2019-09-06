<?php 
/**
 * Configuration for the phpLiteAdmin script, and
 * dummy authorization class to replace the original
 * auth layer.
 * 
 * @package EVEBiographies
 * @subpackage Configuration
 */

if(!defined('APP_ROOT')) {
    die('May not be accessed directly.');
}

$directory = '.';
$subdirectories = false;
$theme = 'phpliteadmin.css';
$language = 'en';
$rowsNum = 30;
$charsNum = 300;
$maxSavedQueries = 10;
$debug = false;
$allowed_extensions = array('sqlite');

$databases = array(
	array(
		'path'=> __DIR__.'/biographies.sqlite',
		'name'=> 'biographies'
	),
);

/**
 * Dummy authorization class to replace the phpLiteAdmin
 * authorization class, which is not needed since we use
 * the main site authentication.
 * 
 * @package EVEBiographies
 * @subpackage Configuration
 * @author Sebastian Mordziol <eve@aeonoftime.com>
 */
class DummyAuthorization
{
    public function attemptGrant($password, $remember)
    {
        return true;
    }
    
    public function revoke()
    {
    }
    
    public function isAuthorized()
    {
        return true;
    }
    
    public function isFailedLogin()
    {
        return false;
    }
    
    public function isPasswordDefault()
    {
        return false;
    }
}
