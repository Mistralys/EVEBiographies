<?php

    require_once '../prepend.php';

    ob_start();
    
    $website = EVEBiographies\Website::bootAndDisplay('SqliteAdmin');
    
    ob_end_clean();

    ob_start();
    
    ?>
        <p style="padding-left:9px">
		    <a href="<?php echo $website->createScreen('Administration')->getURL() ?>">
		    	&laquo; <?php EVEBiographies\pt('Back to %1$s', $website->getName()) ?>
	    	</a>
    	</p>
    <?php 
    
    $headerHTML = ob_get_clean();
    
    ob_start();
    
    require_once APP_ROOT.'/storage/phpliteadmin.php';
    
    $html = ob_get_clean();
    
    $result = array();
    preg_match_all('/<body[^<>]*>/i', $html, $result, PREG_PATTERN_ORDER);
    
    $tag = $result[0][0];
    
    $html = str_replace(
        $tag, 
        $tag.$headerHTML, 
        $html
    );
    
    echo $html;