<?php

namespace JS\View\Helper;

use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

class FormNumberBootstrap extends AbstractHelper {

    /**
     * Invoke helper as function
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string|FormElement
     */
    public function __invoke(ElementInterface $element = null) {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * Render an element
     *
     * Introspects the element type and attributes to determine which
     * helper to utilize when rendering.
     *
     * @param  ElementInterface $element
     * @return string
     */
    public function render(ElementInterface $element) {
        $renderer = $this->getView();
        if (!method_exists($renderer, 'plugin')) {
            // Bail early if renderer is not pluggable
            return '';
        }

        $helper = $renderer->plugin('form_text');
        return $this->createHTML($helper, $element);
    }

    public function createHTML($helper, $element) {
        $el = "<div class='control-group %s'>%s" .
                "<div class='controls'>" .
                "<div class='input-prepend'>" .
                "<span class='add-on'>R$</span>%s" . "</span></div>" .
                "<span class='help-inline'>%s</span>" .
                "</div>" .
                "</div>";
        $helperErrors = $this->getView()->plugin('form_element_errors');
        $classError = 'error';
        $label = $element->getLabel();
        $divControlGroupClass = count($element->getMessages()) > 0 ? $classError : '';
        if (!isset($label) || '' == $label)
            $label = '';
        else {
            $helperLabel = $this->getView()->plugin('form_label');
            $label = $helperLabel($element);
        }
        $el = sprintf($el, $divControlGroupClass, $label, $helper($element), $helperErrors($element)
        );
        return $el;
    }

}
