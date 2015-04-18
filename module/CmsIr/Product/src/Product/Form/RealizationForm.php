<?php
namespace CmsIr\Product\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class RealizationForm extends Form
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
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control',
                'name' => 'client_id',
            ),
            'options' => array(
                'label' => 'Klient',
            )
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
    }
}