<?php

namespace EVEBiographies;

class Website_Exception extends \Exception
{
    protected $details;
    
    public function __construct($message, $code, $details=null, $previous=null)
    {
        parent::__construct($message, $code, $previous);
        
        $this->details = $details;
    }
    
    public function display()
    {
        ?><!doctype html>
        <html lang="en">
          <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="">
            <meta name="author" content="">
            <title>Error</title>
          </head>
          <body>
        	  <h1>Error</h1>
        	  <p>Code: <?php echo $this->getCode() ?></p>
        	  <p>Message: <?php echo $this->getMessage() ?></p>
        	  <p>Details: <?php echo $this->details ?></p>
        	  <p>Trace:</p>
        	  <pre>
        	  	<?php echo $this->getTraceAsString() ?>
        	  </pre>
          </body>
        </html><?php 
    }
}