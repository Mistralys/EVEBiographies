<?php

    // -----------------------------------------------------------------------------
    // ROOT CONFIGURATION
    // -----------------------------------------------------------------------------

   /**
    * The absolute URL to the biographies website, without ending slash.
    *
    * @var string
    */
    define('APP_URL', 'https://domain.com/biographies');

    /**
     * Whether to enable URL rewriting to be able to use pretty URLs.
     * @var bool
     */
    define('APP_PRETTY_URLS', false);
    

    // -----------------------------------------------------------------------------
    // EVE ONLINE CREST API
    // -----------------------------------------------------------------------------
    
   /**
    * The application client ID to connect to the EVE application.
    * @var string
    * @see https://developers.eveonline.com/applications/
    */
    define('APP_CREST_CLIENT_ID', '');

   /**
    * The application secret key to connect to the EVE application.
    * @var string
    * @see https://developers.eveonline.com/applications/
    */
    define('APP_CREST_SECRET_KEY', '');


    
    // -----------------------------------------------------------------------------
    // PUBLIC EMAIL ADDRESSES
    // -----------------------------------------------------------------------------
    
   /**
    * The email address where legal requests should be sent
    * @var string
    */
    define('APP_EMAIL_LEGAL', 'legal@website.com');

    /**
     * The email address where general purpose requests should be sent
     * @var string
     */
    define('APP_EMAIL_CONTACT', 'contact@website.com');

    
    // -----------------------------------------------------------------------------
    // SMTP SERVER SETUP FOR SENDING MAILS
    // -----------------------------------------------------------------------------

    /**
     * The SMTP server host.
     * @var string
     */
    define('APP_SMTP_HOST', 'host.com');

    /**
     * The username to use to connect to the SMPT server.
     * @var string
     */
    define('APP_SMTP_USERNAME', '');

    /**
     * The password for the specified user name.
     * @var string
     */
    define('APP_SMTP_PASSWORD', '');

    /**
     * The security protocol to use for the connection.
     * @var string
     */
    define('APP_SMTP_CRYPT', 'tls'); // Possible values: "tls" or "ssl"

    /**
     * The SMTP server port to connect to.
     * @var integer
     */
    define('APP_SMTP_PORT', 587); // Typically 587 for tls, or 465 for ssl

    /**
     * The E-Mail address to use as the sender.
     * @var string
     */
    define('APP_SMTP_FROM_EMAIL', APP_EMAIL_CONTACT);

    /**
     * The name to use as the sender.
     * @var string
     */
    define('APP_SMTP_FROM_NAME', 'EVE Biographies');
    
    
    // -----------------------------------------------------------------------------
    // ADMINISTRATORS
    // -----------------------------------------------------------------------------
    
    // To define who has admin rights, add the according entries
    // in the following array. Admins also log in using EVE Online's
    // SSO, and get the admin privileges by being in this list. 
    //
    // Note: At least one of these should have the notifications enabled.
    // If not, they are automatically enabled for the first in the list.
    //
    // If notifications are enabled, these characters all get sent
    // a copy of the website's mail notifications. Several characters
    // can use the same email address: only one mail will be sent.
    
    $adminCharacters = array(
        array(
            'character' => 'Character Name',
            'email' => 'contact@domain.com',
            'notifications' => true
        ),
    );
    
