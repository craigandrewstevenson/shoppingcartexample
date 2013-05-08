<?php


namespace ShoppingCart\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ShoppingCart\Form\AddToCartForm;

class ProductController extends AbstractActionController
{
    protected $productTable;
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
        return new ViewModel(array(
            'products' => $this->getProductTable()->getAllProducts(),
            ));
    }

    public function productAction()
    {
        $id = (int) $this->params()->fromRoute('id',0);
        $product = $this->getProductTable()->getProduct($id);
        $form = new AddToCartForm();
        $form->bind($product);
        return array('product' => $product, 'form' => $form);
    }
}

?>
