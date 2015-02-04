<?php
return array(
    'fake' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/product',
            'defaults' => array(
            ),
        ),
    ),
    'product-list' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/product',
            'defaults' => array(
                'module' => 'CmsIr\Product',
                'controller' => 'CmsIr\Product\Controller\Product',
                'action'     => 'productList',
            ),
        ),
    ),
);