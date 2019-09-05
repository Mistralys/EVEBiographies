<?php

namespace EVEBiographies;

abstract class Skins_Skin_Template_Email extends Skins_Skin_Template
{
    public function render() : string
    {
        $body = $this->_renderBody();
        
        ob_start();
        
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>        
    	<?php echo $body ?>
	</body>
</html><?php 
        
        return ob_get_clean();
    }
    
    protected function isFullPage() : bool
    {
        return true;
    }
}