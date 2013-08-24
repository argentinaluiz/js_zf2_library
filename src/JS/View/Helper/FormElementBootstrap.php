<?php

namespace JS\View\Helper;

use Zend\Form\Element;
use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

class FormElementBootstrap extends AbstractHelper {

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

        if ($element instanceof Element\Button) {
            $helper = $renderer->plugin('form_button');
            return $this->createHTML($helper, $element);
        }

        if ($element instanceof Element\Captcha) {
            $helper = $renderer->plugin('form_captcha');
            return $this->createHTML($helper, $element);
        }

        if ($element instanceof Element\Csrf) {
            $helper = $renderer->plugin('form_hidden');
            return $this->createHTML($helper, $element);
        }

        if ($element instanceof Element\Collection) {
            $helper = $renderer->plugin('form_collection');
            return $this->createHTML($helper, $element);
        }

        if ($element instanceof Element\DateTimeSelect) {
            $helper = $renderer->plugin('form_date_time_select');
            return $this->createHTML($helper, $element);
        }

        if ($element instanceof Element\DateSelect) {
            $helper = $renderer->plugin('form_date_select');
            return $this->createHTML($helper, $element);
        }

        if ($element instanceof Element\MonthSelect) {
            $helper = $renderer->plugin('form_month_select');
            return $this->createHTML($helper, $element);
        }

        $type = $element->getAttribute('type');

        if ('checkbox' == $type) {
            $helper = $renderer->plugin('form_checkbox');
            return $this->createHTML($helper, $element);
        }

        if ('color' == $type) {
            $helper = $renderer->plugin('form_color');
            return $this->createHTML($helper, $element);
        }

        if ('date' == $type) {
            $helper = $renderer->plugin('form_date');
            return $this->createHTML($helper, $element);
        }

        if ('datetime' == $type) {
            $helper = $renderer->plugin('form_date_time');
            return $this->createHTML($helper, $element);
        }

        if ('datetime-local' == $type) {
            $helper = $renderer->plugin('form_date_time_local');
            return $this->createHTML($helper, $element);
        }

        if ('email' == $type) {
            $helper = $renderer->plugin('form_email');
            return $this->createHTML($helper, $element);
        }

        if ('file' == $type) {
            $helper = $renderer->plugin('form_file');
            return $this->createHTML($helper, $element);
        }

        if ('hidden' == $type) {
            $helper = $renderer->plugin('form_hidden');
            return $this->createHTML($helper, $element);
        }

        if ('image' == $type) {
            $helper = $renderer->plugin('form_image');
            return $this->createHTML($helper, $element);
        }

        if ('month' == $type) {
            $helper = $renderer->plugin('form_month');
            return $this->createHTML($helper, $element);
        }

        if ('multi_checkbox' == $type) {
            $helper = $renderer->plugin('form_multi_checkbox');
            return $this->createHTML($helper, $element);
        }

        if ('number' == $type) {
            $helper = $renderer->plugin('form_number');
            return $this->createHTML($helper, $element);
        }

        if ('password' == $type) {
            $helper = $renderer->plugin('form_password');
            return $this->createHTML($helper, $element);
        }

        if ('radio' == $type) {
            $helper = $renderer->plugin('form_radio');
            return $this->createHTML($helper, $element);
        }

        if ('range' == $type) {
            $helper = $renderer->plugin('form_range');
            return $this->createHTML($helper, $element);
        }

        if ('reset' == $type) {
            $helper = $renderer->plugin('form_reset');
            return $this->createHTML($helper, $element);
        }

        if ('search' == $type) {
            $helper = $renderer->plugin('form_search');
            return $this->createHTML($helper, $element);
        }

        if ('select' == $type) {
            $helper = $renderer->plugin('form_select');
            return $this->createHTML($helper, $element);
        }

        if ('submit' == $type) {
            $helper = $renderer->plugin('form_submit');
            return $this->createHTML($helper, $element);
        }

        if ('tel' == $type) {
            $helper = $renderer->plugin('form_tel');
            return $this->createHTML($helper, $element);
        }

        if ('text' == $type) {
            $helper = $renderer->plugin('form_text');
            return $this->createHTML($helper, $element);
        }

        if ('textarea' == $type) {
            $helper = $renderer->plugin('form_textarea');
            return $this->createHTML($helper, $element);
        }

        if ('time' == $type) {
            $helper = $renderer->plugin('form_time');
            return $this->createHTML($helper, $element);
        }

        if ('url' == $type) {
            $helper = $renderer->plugin('form_url');
            return $this->createHTML($helper, $element);
        }

        if ('week' == $type) {
            $helper = $renderer->plugin('form_week');
            return $this->createHTML($helper, $element);
        }

        $helper = $renderer->plugin('form_input');
        return $this->createHTML($helper, $element);
    }

    public function createHTML($helper, $element) {
        $el = "<div class='control-group %s'>%s" .
                "<div class='controls'>%s" .
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
