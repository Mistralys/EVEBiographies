<?php

namespace EVEBiographies;

class Website_FormRenderer extends \HTML_QuickForm2_Renderer
{
    protected $html = array();

    protected $hiddenHTML = array();

   /**
    * @var \HTML_QuickForm2_Element_Button[]
    */
    protected $buttons = array();

    public function __toString()
    {
        return implode('', $this->html);
    }

    public function renderHidden(\HTML_QuickForm2_Node $element)
    {
        $this->hiddenHTML[] = (string)$element;
    }

    public function renderElement(\HTML_QuickForm2_Node $element)
    {
        $type = $element->getType();

        if(strtolower($type)=='submit') {
            $this->buttons[] = $element;
            return '';
        }

        $label = $element->getLabel();

        $labelBefore = true;
        $labelClasses = array();
        $groupClasses = array(
            'form-group',
            'element-type-'.$type
        );

        $classes = $element->getRuntimeProperty('container-classes');
        if(!empty($classes)) {
            $groupClasses[] = $classes;
        }

        if($element->getType() == 'checkbox')
        {
            $labelBefore = false;
            $groupClasses[] = 'form-check';
            $labelClasses[] = 'form-check-label';
            $element->addClass('form-check-input');
        }
        else if($element->getType() != 'submit')
        {
            $element->addClass('form-control');
        }

        if($element->isRequired()) {
            $groupClasses[] = 'element-required';
            $label .= ' <i class="fa fa-warning text-warning" title="'.t('This field is required.').'"></i>';
        }

        $errorHTML = '';

        if($element->hasErrors())
        {
            $element->addClass('is-invalid');
            $groupClasses[] = 'has-error';

            $msg = $element->getError();
            if(!empty($msg)) {
                $errorHTML = '<div class="invalid-feedback">'.$msg.'</div>';
            }
        }

        $labelCode = '<label for="'.$element->getId().'" class="'.implode(' ', $labelClasses).'">'.$label.'</label>';

        $html =
        '<div class="'.implode(' ', $groupClasses).'">';

            if($labelBefore) {
                $html .= $labelCode;
            }

            $html .=
            (string)$element;

            if(!$labelBefore) {
                $html .= $labelCode;
            }

            $html .= $errorHTML;

            $hint = $element->getComment();
            if(!empty($hint)) {
                $html .= '<p class="help-block">'.$hint.'</p>';
            }
            $html .=

        '</div>';

        $this->html[] = $html;
    }

    public function reset()
    {
        $this->html = array();
    }

    public function startForm(\HTML_QuickForm2_Node $form)
    {
        $this->html[] = '<form '.$form->getAttributes(true).'>';
    }

    public function startGroup(\HTML_QuickForm2_Node $group)
    {

    }

    public function startContainer(\HTML_QuickForm2_Node $container)
    {

    }

    public function finishForm(\HTML_QuickForm2_Node $form)
    {
        if(!empty($this->buttons)) {
            $this->html[] = '<div class="form-buttons form-group">';
            foreach($this->buttons as $button) {
                $this->html[] = (string)$button.' ';
            }
            $this->html[] = '</div>';
        }

        $this->html[] = implode('', $this->hiddenHTML);

        $this->html[] = '</form>';
    }

    public function finishContainer(\HTML_QuickForm2_Node $container)
    {

    }

    public function finishGroup(\HTML_QuickForm2_Node $group)
    {

    }
}
