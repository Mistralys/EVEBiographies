<?php

    /**
     * The password to use to access the database administration.
     * The database is a SQLITE file under the <code>storage</code> folder.
     *
     * @var string
     */
    define('APP_DB_PASSWORD', '');

   /**
    * The absolute URL to the biographies website, without ending slash.
    *
    * @var string
    */
    define('APP_URL', 'https://domain.com/biographies');

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

   /**
    * Whether to enable URL rewriting to be able to use pretty URLs.
    * @var bool
    */
    define('APP_PRETTY_URLS', false);

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

   /**
    * Admin contact address for email notifications. Not displayed anywhere in the site.
    * @var string
    */
    define('APP_EMAIL_ADMIN', 'admin@domain.com');

   /**
    * A semicolon-separated list of the names of characters that are allowed to administrate the website.
    * @var string
    */
    define('APP_ADMIN_CHARACTERS', 'CharacterName1;CharacterName2');

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
