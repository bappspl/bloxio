<?php
namespace CmsIr\Product\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class ProductForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Category');
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods());

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'id'
            ),
        ));

        $this->add(array(
            'name' => 'realization_id',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'realization_id'
            ),
        ));

        $this->add(array(
            'name' => 'main_photo',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'filename_main_photo'
            ),
        ));

        $this->add(array(
            'name' => 'filename_gallery',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'filename_gallery'
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'id' => 'name',
                'type'  => 'text',
                'placeholder' => 'Wprowadź nazwę'
            ),
            'options' => array(
                'label' => 'Nazwa',
            ),
        ));

        $this->add(array(
            'name' => 'slug',
            'attributes' => array(
                'id' => 'slug',
                'type'  => 'text',
                'placeholder' => 'Wprowadź nazwę systemową'
            ),
            'options' => array(
                'label' => 'Nazwa systemowa',
            ),
        ));

        $this->add(array(
            'name' => 'date',
            'attributes' => array(
                'id' => 'text',
                'class' => 'form-control datepicker',
                'data-date-format' => 'yyyy-mm-dd',
                'type'  => 'text',
                'placeholder'  => 'yyyy-mm-dd',
            ),
            'options' => array(
                'label' => 'Data',
            ),
        ));

        $this->add(array(
            'name' => 'url',
            'attributes' => array(
                'id' => 'name',
                'type'  => 'text',
                'placeholder' => 'Wprowadź url'
            ),
            'options' => array(
                'label' => 'Url',
            ),
        ));

        $this->add(array(
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control',
                'name' => 'category_id',
            ),
            'options' => array(
                'label' => 'Kategoria',
            )
        ));

        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'id' => 'text',
                'class' => 'summernote-sm',
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Treść',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Zapisz',
                'id' => 'submit',
                'class' => 'btn btn-primary pull-right'
            ),
        ));

        $this->add(array(
            'name' => 'product_main_photo',
            'attributes' => array(
                'type'  => 'file',
                'id' => 'product_main_photo',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Upload',
            ),
        ));

        $this->add(array(
            'name' => 'product_files',
            'attributes' => array(
                'type'  => 'file',
                'id' => 'product_files',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Upload',
            ),
        ));
    }
}