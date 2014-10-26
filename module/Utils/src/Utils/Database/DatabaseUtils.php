<?php
namespace Utils\Database;

use Zend\Db\ResultSet\ResultSetInterface;

class DatabaseUtils {
    public static function resultSetToArray(ResultSetInterface $resultSet) {
        $array = array();
        foreach ($resultSet as $item) {
            $array[] = $item->toArray();
        }
        return $array;
    }
}