<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\Product\Controller\Product' => 'CmsIr\Product\Controller\ProductController',
            'CmsIr\Product\Controller\Category' => 'CmsIr\Product\Controller\CategoryController',
            'CmsIr\Product\Controller\Client' => 'CmsIr\Product\Controller\ClientController',
            'CmsIr\Product\Controller\Realization' => 'CmsIr\Product\Controller\RealizationController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-product'  => __DIR__ . '/../view/partial/flashmessages-product.phtml',
            'partial/delete-product-modal'  => __DIR__ . '/../view/partial/delete-product-modal.phtml',
            'partial/delete-category-modal'  => __DIR__ . '/../view/partial/delete-category-modal.phtml',
            'partial/delete-client-modal'  => __DIR__ . '/../view/partial/delete-client-modal.phtml',
            'partial/delete-realization-modal'  => __DIR__ . '/../view/partial/delete-realization-modal.phtml',
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