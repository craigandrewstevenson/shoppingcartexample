<?php

namespace ShoppingCart\Controller;

use ShoppingCart\Model\CartItem;
use ShoppingCart\Model\ShoppingCart;
use Zend\Mvc\Controller\AbstractActionController;
use ShoppingCart\Form\ShoppingCartForm; 
use ShoppingCart\Form\AddToCartForm; 

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

use Zend\View\Model\ViewModel;

class ShoppingCartController extends AbstractActionController
{
    protected $productTable;
    protected $cartItemTable;
    protected $shoppingCart;
    protected $form;

    public function __construct()
    {
        $this->shoppingCart = new ShoppingCart();   
        $this->form = new ShoppingCartForm(); 
        foreach ($this->shoppingCart->cartItems as $cartItem)
        {
            $item = $this->form->add(array(
                'name' => "quantity[". $cartItem->productID."]",
                'type' => 'Text',
                'attributes' => array(
                'style' => 'width: 20px;',
                'value' => $cartItem->quantity,
                ),
                'options' => array(
                'label' => 'Quantity: ',
                ),
            ));
        }
    }  

    public function getProductTable()
    {
        if (!$this->productTable)
        {
            $sm = $this->getServiceLocator();
            $this->productTable = $sm->get('ShoppingCart\Model\ProductTable');
        }
        return $this->productTable;
    }

    public function indexAction()
    {
        return array('form' => $this->form, 'shoppingCart' =>  $this->shoppingCart );
    }

    private function quantityHasValidInput($data, $fieldName)
    {
        $quantity = new Input( $fieldName);

        // validate that the user has:
        // entered a digit
        // and its not empty or zero
        $quantity->getValidatorChain()->addValidator(new Validator\NotEmpty(array('zero',)))->addValidator(new Validator\Digits);
        $inputFilter = new InputFilter();
        $inputFilter->add($quantity)->setData($data );
        return ($inputFilter->isValid());
    }

    public function addToCartAction()
    {
        // getRequest contains:
        //      productID
 
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $data = $request->getPost();
             $product = $this->getProductTable()->getProduct($data->id);  
             if ($product == NULL)
                throw new \Exception("Adding product to cart error: Product does not exist. Product ID: $data->productID");
            $this->shoppingCart->addItem($product, 1);
        }
            return $this->redirect()->toRoute('shoppingcart');
        }

    function removeFromCartAction()
    {
        // retrieve product from database
        $id = (int) $this->params()->fromRoute('id',0);
        if (!$id)
        {
           return $this->redirect()->toRoute('shoppingcart');
        }

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $data = $request->getPost();
            if ($data->delete == 'Yes') 
            {
                $id = (int) $request->getPost('id');
           $this->shoppingCart->removeItem($id);
        }
        return $this->redirect()->toRoute('shoppingcart');
   }
        return array('id' => $id, 'product' => $this->getProductTable()->getProduct($id)  );
    }

    function refreshCartItemInformation()
    {
        foreach ($this->shoppingCart->cartItems as $cartItem)
          {
            $product = $this->getProductTable()->getProduct($cartItem->productID);  
            $cartItem->refresh($product);
             }
    }

    function updateCartAction()
    {
       $request = $this->getRequest();
       if ($request->isPost())
       {
            $this->refreshCartItemInformation();
            $data = $request->getPost();

            //loop thru each quantity field and validate
            // each field is named as quantity[$productID]
            // get the latest product information in case someone has change the product database since adding
            // items to the cart
            foreach ($this->shoppingCart->cartItems as $cartItem)
          {
               // two tests used here
               // has the user entered a valid non zero digit?
               // has the user entered a number less than the allow maximum?  This maximum can be located in the the product table
               // 1. check for valid digits.  If not, we can remove the cart item
               // 2. If we pass the valid digit test, we can check if its less than or equal to the maximum. If not, cap the
               //     update the item to the maximum
               // 3. If we make it this far, we can update the item with the new valid quantity

               // Note: didnt use a validator for the max quantity check as it felt better to just check myself
               // Considered adding it to the validation chain. However we have to do two different things based on 
               // which test fails and I dont know if there is a way of knowing which test in a chain fails. 
               // So I separated them. 
               if ($this->quantityHasValidInput($data->quantity, $cartItem->productID))
               {
                   if ($data->quantity[$cartItem->productID] > $cartItem->maxQuantity) {
                         $this->shoppingCart->updateItemWithMaximumQuantity($cartItem->productID);
                   }
                   else
                   {
                        $this->shoppingCart->updateItem($cartItem->productID, $data->quantity[$cartItem->productID]);
                   }
               }
               else
               {
                    $this->shoppingCart->removeItem($cartItem->productID);
                }
          }
        }
       return $this->redirect()->toRoute('shoppingcart');
    }
}
?>
