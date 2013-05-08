<?php

namespace ShoppingCart\Form;
use Zend\Form\Form;
 
class AddToCartForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('AddToCart');
        $this->setAttribute('method', 'post');
 
        $this->add(array('name' => 'id','type' => 'Hidden'));

        $this->add(array(
            'name' => 'quantity','type' => 'Text',
            'attributes' => array(
                'value' => '1',
                'style' => 'width: 20px;',
                ),            
            'options' => array(
            'label' => 'Quantity: ',
            ),
        ));
        $this->add(array(
            'name' => 'submit','type' => 'Submit',
                'attributes' => array(
                    'value' => 'Add To Cart',
                    'id' => 'submitbutton',
                ),
        ));
    }
}

?>

