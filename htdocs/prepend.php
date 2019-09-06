<?php

    define('APP_ROOT', __DIR__);

    $autoload = APP_ROOT.'/vendor/autoload.php';
    if(!file_exists($autoload)) {
        die(
            '<p style="color:#cc0000"><b>ERROR:</b> The autoloader is not present. Run a composer install to create it.</p>'
        );
    }
    
    require_once $autoload;
    
    $paths = array(
        ini_get('include_path'),
        APP_ROOT.'/assets/classes'
    );
    
    ini_set('include_path', implode(PATH_SEPARATOR, $paths));

    $adminCharacters = null;
    
    $localConfig = APP_ROOT.'/config/config-local.php';
    if(!file_exists($localConfig)) {
        die(
            '<p style="color:#cc0000"><b>ERROR:</b> The local configuration file does not exist.</p>'. 
            '<p>Rename the <code>config-local.dist.php</code> file to <code>config-local.php</code> and edit the file to set the required settings.</p>'
        );
    }
    
    if(!function_exists('curl_init')) {
        die('<p style="color:#cc0000"><b>ERROR:</b> The CURL extension is not enabled.</p>');
    }
    
    require_once $localConfig;

    if(!isset($adminCharacters) || empty($adminCharacters)) {
        die(
            '<p style="color:#cc0000"><b>ERROR:</b> The admin characters list is empty.</p>'
        );
    }
    
    /**
     * Serialized list of admin characters.
     * @var string
     */
    define('APP_ADMIN_CHARACTERS', serialize($adminCharacters)); unset($adminCharacters);
    
    define('APP_GITHUB_URL', 'https://github.com/Mistralys/EVEBiographies');
    define('APP_DB_PATH', APP_ROOT.'/storage/biographies.sqlite');
    define('APP_CREST_CALLBACK_URL', APP_URL.'/auth.php');
    
    require_once 'Website.php';
    
    