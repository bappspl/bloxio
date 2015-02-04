<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\Product\Controller\Product' => 'CmsIr\Product\Controller\ProductController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-product'  => __DIR__ . '/../view/partial/flashmessages-product.phtml',
            'partial/delete-product-modal'  => __DIR__ . '/../view/partial/delete-product-modal.phtml',
        ),
        'template_path_stack' => array(
            'product' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
);