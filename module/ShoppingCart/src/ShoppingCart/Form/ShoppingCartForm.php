<?php

namespace ShoppingCart\Form;
use Zend\Form\Form;


class ShoppingCartForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('ShoppingCart');
        $this->setAttribute('method', 'post');
 
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Update Cart',
                'id' => 'submitbutton',
            ),
        ));
    }
}

?>

