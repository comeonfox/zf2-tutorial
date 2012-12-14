<?php
namespace Blog\Form;
use Zend\Form\Form;
class BlogForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('blog');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name'=>'id',
            'attributes'=>array(
                'type'=>'hidden',
            ),
        ));
        $this->add(array(
            'name'=>'title',
            'attributes'=>array(
                'type'=>'text',
                'class'=>'span6',
            ),
            'options'=>array(
                'label'=>'Title',
            ),
        ));
        $this->add(array(
            'name'=>'body',
            'attributes'=>array(
                'type'=>'textarea',
                'class'=>'span6',
                'rows' => '20',
                'cols' => '20',
            ),
            'options'=>array(
                'label'=>'Content',
            ),
        ));
        $this->add(array(
            'name'=>'submit',
            'attributes'=>array(
                'type'=>'submit',
                'value'=>'Go',
                'id'=>'submitbutton',
            ),
        ));
    }
}
