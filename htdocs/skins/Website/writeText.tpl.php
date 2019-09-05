<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend/WriteTab.php';

class Template_Website_writeText extends Skins_Skin_Template_Frontend_WriteTab
{
    protected function _renderTab() : string
    {
        $this->addJavascript('https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js');
        $this->addStylesheet('https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css');
        
        $this->addJSOnload(sprintf(
            'new SimpleMDE(%s);',
            json_encode(array(
                'autofocus' => true,
                'placeholder' => t('Start typing here...'),
                'autoDownloadFontAwesome' => false
            )))
        );
        
        $font = $this->bio->getFont();
        
        $fontStyles = array(
            'font-family' => "'".$font->getFontFamily()."', ".$font->getFallbackFamily()." !important",
            'font-size' => $font->getDefaultSize().'rem',
            'font-weight' => $font->getWeight()
        );
        
        ob_start();
        
        ?>
        	<?php echo $this->renderForm($this->form) ?>
        
        	<style>
        	    .CodeMirror,
                .editor-preview,
        	    .editor-preview-side{
	                <?php 
	                   foreach($fontStyles as $style => $value) {
	                       echo $style.':'.$value.';';
	                   }
	                ?>  	    	
                }
        	</style>
        <?php 
        
		return ob_get_clean();
	}
}        