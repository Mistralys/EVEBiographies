<?php

namespace EVEBiographies;

class Website_Screen_Report extends Website_Screen
{
    const REGEX_EMAIL = '/[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/i';
    
   /**
    * @var Characters_Character
    */
    protected $targetCharacter;
    
    protected function _start()
    {
        if(!$this->request->paramExists('char')) {
            $this->redirect($this->getScreenURL('About'));
        }
        
        $slug = $this->request->getParam('char');
        $chars = $this->website->createCharacters();
        
        if(!$chars->slugExists($slug)) {
            $this->redirect($this->getScreenURL('About'));
        }
        
        $this->targetCharacter = $chars->getBySlug($slug);
        
        if($this->character === $this->targetCharacter) {
            $this->redirectWithInfoMessage(
                '<b>'.t('Woah, hey, reporting your own biography?').'</b> '.
                t('We will assume it was a misclick, and leave it at that.'), 
                $this->getScreenURL('About')
            );
        }
        
        $this->createReportForm();
        
        if($this->form->isSubmitted() && $this->form->validate()) 
        {
            $values = $this->form->getValues();
            
            $tpl = $this->createTemplate('mailReport');
            $tpl->addVars(array(
                'target-character' => $this->targetCharacter,
                'type' => $values['type'],
                'comments' => $values['comments'],
                'email' => $values['contact_mail']
            ));
            
            $mail = $this->website->createLegalMailer(t('Biography reported:').' '.$this->targetCharacter->getName());
            $mail->setHTMLBody($tpl->render());
            $mail->send();
            
            $this->redirectWithSuccessMessage(
                t(
                    'Thank you for taking the time to report %1$s\'s biography, we will investigate shortly.', 
                    $this->targetCharacter->getName()
                ), 
                $this->getScreenURL('Nexus')
            );
        }
    }
    
    public function requiresAuthentication()
    {
        return true;
    }
    
    protected function getSkinID()
    {
        return 'Website';
    }
    
    public function getPageTitle()
    {
        return t('Report');
    }
    
    public function getNavigationTitle()
    {
        return t('Report');
    }
    
    public function getDispatcher()
    {
        return 'report.php';
    }
    
    public function getPrettyDispatcher()
    {
        return 'report';
    }
    
    protected function _render()
    {
        $tpl = $this->skin->createTemplate('report');
        $tpl->addVar('target-character', $this->targetCharacter);
        $tpl->addVar('form', $this->form);
        
        return $tpl->render();
    }
    
   /**
    * @var \HTML_QuickForm2
    */
    protected $form;
    
    protected $minCommentsLength = 10;
    
    protected $maxCommentsLength = 2000;
    
    protected function createReportForm()
    {
        $form = $this->createForm();
        
        $form->addHidden('char', array('value' => $this->targetCharacter->getSlug()));
        
        /* @var $type \HTML_QuickForm2_Element_Select */
        
        $type = $form->addElement('select', 'type');
        $type->setLabel(t('Type of issue'));
        $type->addOption(t('Please select...'), '');
        $type->addOption(t('Copyright issue'), 'copyright');
        $type->addOption(t('Sexually suggestive content'), 'suggestive');
        $type->addOption(t('Harassment, threats, bullying'), 'harassment');
        $type->addOption(t('Personal or confidential information'), 'confidential');
        $type->addOption(t('Impersonation, deception'), 'impersonation');
        $type->addOption(t('SPAM, advertisements'), 'spam');
        $type->addOption(t('Other - please specify in the comments'), 'other');
        $type->addRule('required', t('Please select a type of issue.'));
        
        $email = $form->addElement('text', 'contact_mail');
        $email->setLabel(t('Your contact email'));
        $email->addFilter('trim');
        $email->addRule('regex', t('Please enter a valid email address.'), self::REGEX_EMAIL);
        $email->setComment(t('We will only contact you if we need more information.'));
        $email->addRule('required', t('Please enter an email address.'));
        
        $comments = $form->addElement('textarea', 'comments');
        $comments->setLabel(t('Comments'));
        $comments->setComment(
            t('Please describe why you think %1$s\'s biography needs to be reported.', $this->targetCharacter->getName()).' '.
            t('Between %1$s and %2$s characters are allowed.', $this->minCommentsLength, $this->maxCommentsLength)
        );
        $comments->addFilter('trim');
        $comments->setAttribute('rows', 14);
        $comments->addRuleCallback(t('Please enter a minimum of %1$s characters.', $this->minCommentsLength), array($this, 'callback_minComments'));
        $comments->addRuleCallback(t('Please enter a maximum of %1$s characters.', $this->maxCommentsLength), array($this, 'callback_maxComments'));
        $comments->addRuleCallback(t('May not contain HTML markup.'), array($this, 'validate_comments'));
        $comments->addRule('required', t('Please enter a comment.'));
        
        $btn = $form->addButton('send_report');
        $btn->addClass('btn btn-primary form-btn');
        $btn->setType('submit');
        $btn->setLabel(
            '<i class="fa fa-paper-plane"></i> '.
            t('Send report')
        );
        
        $this->form = $form;
    }
    
    public function validate_comments($comments)
    {
        return !Utils::isStringHTML($comments);
    }
    
    public function callback_minComments($value)
    {
        return mb_strlen($value) >= $this->minCommentsLength;
    }

    public function callback_maxComments($value)
    {
        return mb_strlen($value) <= $this->maxCommentsLength;
    }
}