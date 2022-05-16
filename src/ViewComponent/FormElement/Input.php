<?php

abstract class Flink_ViewComponent_FormElement_Input extends Flink_ViewComponent_FormElement {

    public function get_value() {
        switch ($this->form->get_method()) {
            case 'POST': return $_POST[$this->name]; break;
            case 'GET': return $_GET[$this->name]; break;
            default: throw new Flink_Exception_NotImplemented('method ' . $this->form->get_method() . ' not implemented for input value');
        }
    }

}