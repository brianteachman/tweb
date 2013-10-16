<?php
namespace TWeb\Model;


use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * User info entity for Tarot reading
 * 
 * @link http://packages.zendframework.com/docs/latest/manual/en/user-guide/database-and-models.html#the-model-files 
 * @link http://framework.zend.com/apidoc/2.0/classes/Zend.InputFilter.InputFilterAwareInterface.html
 */
class PageContent implements InputFilterAwareInterface
{
    public $id;
    public $name;
    public $content;

    protected $inputFilter;

    /**
     * Zend\Stdlib\Hydrator\ArraySerializable method
     */
    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id']: null;
        $this->name = (isset($data['name'])) ? $data['name']: null;
        $this->content = (isset($data['content'])) ? $data['content']: null;
    }
    
    /**
     * Zend\Stdlib\Hydrator\ArraySerializable method
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Contract method
     *
     * Could also be implemented using: 
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('Tsk, tsk; you know were not using that!');
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory(); // alias for Zend\InputFilter\Factory

            $inputFilter->add(array(
                'name' => 'id',
            ));
            
            $inputFilter->add(array(
                'name' => 'name',
                'required' => true,
                'validators' => array(
                    array('name' => 'NotEmpty'),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 5
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'content',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array('isEmpty' => 'You must ask something for this to work...'),
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
