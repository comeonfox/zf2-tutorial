<?php
namespace Blog\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class BlogEntry implements InputFilterAwareInterface
{
    protected $id;
    protected $title;
    protected $body;
    protected $time_created;
    protected $time_altered;
    protected $comment_count;
    protected $author;

    private $inputFilter;

    public function getArrayCopy()
    {
        $data = get_object_vars($this);
        unset($data['inputFilter']);
        return $data;
    }
    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->body = (isset($data['body'])) ? $data['body'] : null;
        $this->author = (isset($data['author'])) ? $data['author'] : null;
        $this->time_altered = (isset($data['time_altered'])) ? $data['time_altered'] : null;
        $this->time_created = (isset($data['time_created'])) ? $data['time_created'] : null;
        $this->comment_count = (isset($data['comment_count'])) ? $data['comment_count'] : null;
    }
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            $inputFilter->add($factory->createInput(array(
                'name'      => 'id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
        )));
        $inputFilter->add($factory->createInput(array(
             'name'     => 'title',
             'required' => true,
             'filters' => array(
                 array('name' => 'StripTags'),
                 array('name' => 'StringTrim'),
             ),
             'validators' => array(
                 array(
                     'name'    => 'StringLength',
                     'options' => array(
                         'encoding' => 'UTF-8',
                         'min'       => 1,
                         'max'       => 100,
                     ),
                 ),
             ),
        )));
        $inputFilter->add($factory->createInput(array(
             'name'     => 'body',
             'required' => true,
        )));
        $this->inputFilter = $inputFilter;
    }
    return $this->inputFilter;
  }
}
