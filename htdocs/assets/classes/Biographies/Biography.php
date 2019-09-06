<?php

namespace EVEBiographies;

class Biographies_Biography extends DB_Item
{
    const MIN_TEXT_LENGTH = 120;

    public function setText($text) : Biographies_Biography
    {
        if($this->setDataKey('text', $text))
        {
            // update the word count as well
            $raw = strip_tags($text);
            $this->setDataKey('words', str_word_count($raw));
        }
        
        return $this;
    }

    public function getText() : string
    {
        return $this->getDataKey('text', '');
    }

    public function setSkinID($skinID)
    {
        $this->setDataKey('skin', $skinID);
        return $this;
    }

    public function setBackgroundID($backgroundID)
    {
        $this->setDataKey('background', $backgroundID);
        return $this;
    }

    public function getSkinID() : ?string
    {
        return $this->getDataKey('skin');
    }
    
    public function countWords() : int
    {
        return (int)$this->getDataKey('words');
    }
    
    public function countViews() : int
    {
        return (int)$this->getDataKey('views');
    }
    
    public function incrementViews()
    {
        $this->setDataKey('views', $this->countViews()+1);
        return $this;
    }
    
    public function setPublished(bool $published=true)
    {
        $state = '0';
        if($published === true) {
            $state = '1';
        }

        $this->setDataKey('published', $state);
        return $this;
    }

    public function publish()
    {
        $this->setPublished();
        $this->save();
        return $this;
    }

    public function render()
    {
        $text = $this->getText();

        $result = array();
        preg_match_all('/".*"/siU', $text, $result, PREG_PATTERN_ORDER);
        $matches = array_unique($result[0]);
        foreach($matches as $match) {
            $text = str_replace($match, '_'.$match.'_', $text);
        }

        $parser = new \Parsedown();
        $parser->setSafeMode(true);

        return $parser->text($text);
    }

    public function getBackgroundID() : ?string
    {
        return $this->getDataKey('background');
    }

    public function getBackground() : ?Backgrounds_Background
    {
        $id = $this->getBackgroundID();
        $bgs = $this->collection->getWebsite()->createBackgrounds();

        if($id != 'None' && $bgs->idExists($id)) {
            return $bgs->getByID($id);
        }

        return null;
    }

    public function setFontID($fontID)
    {
        $this->setDataKey('font', $fontID);
        return $this;
    }

    public function getFontID() : string
    {
        return $this->getDataKey('font', 'OpenSans');
    }

    public function getFont() : Fonts_Font
    {
        return $this->collection->getWebsite()->createFonts()->getByID($this->getFontID());
    }

    public function getCharacterID()
    {
        return $this->db->fetchKey('characters', 'character_id', array('biography_id' => $this->getID()));
    }

    public function getCharacter() : Characters_Character
    {
        return $this->collection->getWebsite()->createCharacters()->getByID($this->getCharacterID());
    }

    public function getViewURL()
    {
        $url = $this->collection->getWebsite()->getScreen()->getScreenURL('read', array('char' => $this->getCharacter()->getSlug()));
        return $url;
    }

    public function getShareURL()
    {
        $screen = $this->collection->getWebsite()->getScreen();

        $url = $screen->getScreenURL('share', array('char' => $this->getCharacter()->getSlug()));

        return $url;
    }

    public function getBlockURL()
    {
        $screen = $this->collection->getWebsite()->getScreen();

        $url = $screen->getScreenURL('administration', array('action' => 'block', 'char' => $this->getCharacter()->getSlug()));
        return $url;
    }

    public function getUnblockURL()
    {
        $screen = $this->collection->getWebsite()->getScreen();

        $url = $screen->getScreenURL('administration', array('action' => 'unblock', 'char' => $this->getCharacter()->getSlug()));
        return $url;
    }

    protected $validationMessage;

    public function isValid() : bool
    {
        $this->validationMessage = null;

        $text = trim(strip_tags($this->getText()));
        if(mb_strlen($text) < self::MIN_TEXT_LENGTH) {
            return $this->setValidationMessage(
                t('The biography text must be a minimum of %1$s characters long.', self::MIN_TEXT_LENGTH)
            );
        }

        if(!$this->getCharacter()->hasAcceptedTerms()) {
            return $this->setValidationMessage(
                t('The terms and conditions have not been accepted yet.')
            );
        }

        if($this->isBlocked()) {
            return $this->setValidationMessage(t('The biography has been censored by an administrator.'));
        }

        return true;
    }

    protected function setValidationMessage($message) : bool
    {
        $this->validationMessage = $message;
        return false;
    }

    public function getValidationMessage()
    {
        return $this->validationMessage;
    }

    public function getPublishState()
    {
        if($this->isBlocked()) {
            return t('Censored by moderator');
        }

        if($this->isPublished()) {
            return t('Published');
        }

        return t('Not published');
    }

    public function getPublishStatePretty()
    {
        if($this->isPublished()) {
            return
            '<span class="badge badge-success">'.
                '<i class="fa fa-check"></i> '.
                $this->getPublishState().
            '</span>';
        }

        $this->isValid();

        $message = strip_tags($this->getValidationMessage());

        return
        '<span class="badge badge-warning" style="cursor:help" title="'.$message.'">'.
            '<i class="fa fa-times"></i> '.
            $this->getPublishState().
        '</span>';
    }

    public function isPublished() : bool
    {
        return $this->getDataKey('published') === '1' && !$this->isBlocked();
    }

    public function setBlocked($blocked=true)
    {
        $value = '0';
        if($blocked === true) {
            $value = '1';
        }

        $this->setDataKey('blocked', $value);

        return $this;
    }

    public function block()
    {
        $this->setPublished(false);
        $this->setBlocked(true);

        $this->save();
        return $this;
    }

    public function unblock()
    {
        $this->setBlocked(false);

        if($this->isValid()) {
            $this->setPublished(true);
        }

        $this->save();
        return $this;
    }

    public function isBlocked() : bool
    {
        return $this->getDataKey('blocked') === '1';
    }
}
