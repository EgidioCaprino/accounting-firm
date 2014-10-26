<?php
namespace Authentication\Acl;

class Acl extends \Zend\Permissions\Acl\Acl {
    const ROLE_GUEST = "guest";
    const ROLE_USER = "user";
    const ROLE_ADMIN = "admin";

    public function __construct(array $permissions) {
        foreach ($permissions as $resource => $p) {
            if (!$this->hasResource($resource)) {
                $this->addResource($resource);
            }
            foreach ($p as $privilege => $roles) {
                foreach ($roles as $role) {
                    if (!$this->hasRole($role)) {
                        $this->addRole($role);
                    }
                    $this->allow($role, $resource, $privilege);
                }
            }
        }
    }
}