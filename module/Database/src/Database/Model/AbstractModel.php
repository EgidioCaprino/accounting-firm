<?php
namespace Database\Model;

use Utils\InputFilter\Exception\InvalidDataException;
use Utils\InputFilter\InputFilterUtils;
use Zend\Db\RowGateway\RowGateway;
use Zend\InputFilter\InputFilterInterface;

abstract class AbstractModel extends RowGateway {
    /**
     * @var InputFilterInterface
     */
    private $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter) {
        $this->inputFilter = $inputFilter;
    }

    public function getInputFilter() {
        return $this->inputFilter;
    }

    public function save() {
        $data = $this->toArray();
        $inputFilter = $this->getInputFilter();
        InputFilterUtils::assertDataIsValid($inputFilter, $data);
        $this->populate($inputFilter->getValues(), $this->rowExistsInDatabase());
        return parent::save();
    }

    public function populate(array $rowData, $rowExistsInDatabase = false) {
        $inputFilter = $this->getInputFilter();
        $inputFilter->setData($rowData);
        $inputFilter->isValid();
        $rowData = $inputFilter->getValues();
        return parent::populate($rowData, $rowExistsInDatabase);
    }
}