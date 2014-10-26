<?php
namespace Database\Dao;

class UserDao extends AbstractDao {
    public static function encryptPassword($password) {
        $encrypted = hash("sha256", $password);
        return $encrypted;
    }
}