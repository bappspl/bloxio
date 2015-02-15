<?php
return array(
    'product-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/product',
            'defaults' => array(
            ),
        ),
    ),
    'category-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/category',
            'defaults' => array(
            ),
        ),
    ),
    'client-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/client',
            'defaults' => array(
            ),
        ),
    ),
    'realization-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/realization',
            'defaults' => array(
            ),
        ),
    ),
    'realization' => array(
        'type'    => 'Segment',
        'may_terminate' => true,
        'options' => array(
            'route'    => '/cms-ir/realization',
            'defaults' => array(
                'module' => 'CmsIr\Product',
                'controller' => 'CmsIr\Product\Controller\Realization',
                'action'     => 'realizationList',
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Realization',
                        'action' => 'createRealization',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:realization_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Realization',
                        'action' => 'editRealization',
                    ),
                    'constraints' => array(
                        'realization_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:realization_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Realization',
                        'action' => 'deleteRealization',
                    ),
                    'constraints' => array(
                        'realization_id' => '[0-9]+'
                    ),
                ),
            ),
            'product' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/:realization_id/products',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Product',
                        'action' => 'productList',
                    ),
                    'constraints' => array(
                        'realization_id' => '[0-9]+'
                    ),
                ),
                'child_routes' => array(
                    'create' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/create',
                            'defaults' => array(
                                'module' => 'CmsIr\Product',
                                'controller' => 'CmsIr\Product\Controller\Product',
                                'action' => 'createProduct',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/edit/:product_id',
                            'defaults' => array(
                                'module' => 'CmsIr\Product',
                                'controller' => 'CmsIr\Product\Controller\Product',
                                'action' => 'editProduct',
                            ),
                            'constraints' => array(
                                'product_id' => '[0-9]+'
                            ),
                        ),
                    ),
                    'delete' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/delete/:product_id',
                            'defaults' => array(
                                'module' => 'CmsIr\Product',
                                'controller' => 'CmsIr\Product\Controller\Product',
                                'action' => 'deleteProduct',
                            ),
                            'constraints' => array(
                                'product_id' => '[0-9]+'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'category' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/category',
            'defaults' => array(
                'module' => 'CmsIr\Product',
                'controller' => 'CmsIr\Product\Controller\Category',
                'action'     => 'categoryList',
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Category',
                        'action' => 'createCategory',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:category_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Category',
                        'action' => 'editCategory',
                    ),
                    'constraints' => array(
                        'category_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:category_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Category',
                        'action' => 'deleteCategory',
                    ),
                    'constraints' => array(
                        'category_id' => '[0-9]+'
                    ),
                ),
            ),
        ),
    ),
    'client' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/client',
            'defaults' => array(
                'module' => 'CmsIr\Product',
                'controller' => 'CmsIr\Product\Controller\Client',
                'action'     => 'clientList',
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Client',
                        'action' => 'createClient',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:client_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Client',
                        'action' => 'editClient',
                    ),
                    'constraints' => array(
                        'client_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:client_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Client',
                        'action' => 'deleteClient',
                    ),
                    'constraints' => array(
                        'client_id' => '[0-9]+'
                    ),
                ),
            ),
        ),
    ),
    'upload-client' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/client/upload',
            'defaults' => array(
                'module' => 'CmsIr\Product',
                'controller' => 'CmsIr\Product\Controller\Client',
                'action'     => 'upload',
            ),
            'constraints' => array(
            ),
        ),
    ),
    'upload-product-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/product/upload-main',
            'defaults' => array(
                'module' => 'CmsIr\Product',
                'controller' => 'CmsIr\Product\Controller\Product',
                'action'     => 'uploadMain',
            ),
            'constraints' => array(
            ),
        ),
    ),
    'upload-product-gallery' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/product/upload-gallery',
            'defaults' => array(
                'module' => 'CmsIr\Product',
                'controller' => 'CmsIr\Product\Controller\Product',
                'action'     => 'uploadGallery',
            ),
            'constraints' => array(
            ),
        ),
    ),
    'delete-photo' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/product/delete-photo',
            'defaults' => array(
                'module' => 'CmsIr\Product',
                'controller' => 'CmsIr\Product\Controller\Product',
                'action'     => 'deletePhoto',
            ),
            'constraints' => array(
            ),
        ),
    ),
);