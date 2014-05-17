<?php
/**
 * @package modx
 * @subpackage pgsql
 */
require_once (dirname(dirname(__FILE__)) . '/modusergroup.class.php');
/**
 * @package modx
 * @subpackage pgsql
 */
class modUserGroup_pgsql extends modUserGroup {
    public function getUsersIn(array $criteria = array()) {
        $c = $this->xpdo->newQuery('modUser');
        $c->select($this->xpdo->getSelectColumns('modUser','modUser'));
        $c->select(array(
            'role' => "{$this->xpdo->escape('UserGroupRole')}.{$this->xpdo->escape('name')}",
            'role_name' => "{$this->xpdo->escape('UserGroupRole')}.{$this->xpdo->escape('name')}",
        ));
        $c->innerJoin('modUserGroupMember','UserGroupMembers');
        $c->leftJoin('modUserGroupRole', 'UserGroupRole', "{$this->xpdo->escape('UserGroupMembers')}.{$this->xpdo->escape('role')} = {$this->xpdo->escape('UserGroupRole')}.{$this->xpdo->escape('id')}");
        $c->where(array(
            'UserGroupMembers.user_group' => $this->get('id'),
        ));

        $sort = !empty($criteria['sort']) ? $criteria['sort'] : "{$this->xpdo->escape('modUser')}.{$this->xpdo->escape('username')}";
        $dir = !empty($criteria['dir']) ? $criteria['dir'] : 'DESC';
        $c->sortby($sort,$dir);

        if (isset($criteria['limit'])) {
            $start = !empty($criteria['start']) ? $criteria['start'] : 0;
            $c->limit($criteria['limit'],$start);
        }
        return $this->xpdo->getCollection('modUser',$c);
    }
}