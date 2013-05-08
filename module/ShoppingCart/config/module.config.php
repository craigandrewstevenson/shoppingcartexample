<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'ShoppingCart\Controller\ShoppingCart' => 'ShoppingCart\Controller\ShoppingCartController',
            'ShoppingCart\Controller\Product' => 'ShoppingCart\Controller\ProductController',
         ),
    ),

   'router' => array(
        'routes' => array(
            'shoppingcart' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/shoppingcart[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ShoppingCart\Controller\ShoppingCart',
                        'action'     => 'index',
                    ),
                ),
            ),
           'product' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/product[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ShoppingCart\Controller\Product',
                        'action'     => 'index',
                    ),
                ),
            ),

        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'shoppingcart' => __DIR__ . '/../view',
        ),
    ),
);

?>
