<?php
namespace Database\Dao;

use Utils\InputFilter\InputFilterUtils;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;
use Zend\Db\TableGateway\TableGateway;
use Zend\InputFilter\InputFilterInterface;

abstract class AbstractDao extends TableGateway {
    private $primaryKey;

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

    public function findById($id) {
        if (!is_array($id)) {
            $id = array($id);
        }
        $where = array_combine($this->getPrimaryKey(), $id);
        $resultSet = $this->select($where);
        if ($resultSet->count() === 0) {
            return null;
        }
        return $resultSet->current();
    }

    public function getPrimaryKey() {
        if ($this->primaryKey === null) {
            $metadata = new Metadata($this->getAdapter());
            $constraints = $metadata->getConstraints($this->getTable());
            foreach ($constraints as $constraint) {
                if ($constraint->isPrimaryKey()) {
                    $this->primaryKey = $constraint->getColumns();
                }
            }
        }
        return $this->primaryKey;
    }

    protected function executeInsert(Insert $insert) {
        $data = $insert->getRawState("values");
        $inputFilter = $this->getInputFilter();
        if ($data instanceof Select) {
            $statement = $this->sql->prepareStatementForSqlObject($data);
            $result = $statement->execute();
            InputFilterUtils::assertDataIsValid($inputFilter, $result);
        } else {
            InputFilterUtils::assertDataIsValid($inputFilter, $data);
        }
        $insert->values($inputFilter->getValues());
        return parent::executeInsert($insert);
    }

    protected function executeUpdate(Update $update) {
        $data = $update->getRawState("set");
        $inputFilter = $this->getInputFilter();
        InputFilterUtils::assertDataIsValid($inputFilter, $data);
        $update->set($inputFilter->getValues());
        return parent::executeUpdate($update);
    }
}