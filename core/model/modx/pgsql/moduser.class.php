<?php
/**
 * @package modx
 * @subpackage pgsql
 */
require_once (dirname(dirname(__FILE__)) . '/moduser.class.php');
/**
 * @package modx
 * @subpackage pgsql
 */
class modUser_pgsql extends modUser {
    public function getUserGroupSettings() {
        $settings = array();
        $primary = array();
        $query = $this->xpdo->newQuery('modUserGroupSetting');
        $query->innerJoin('modUserGroup', 'UserGroup', array("{$this->xpdo->escape('UserGroup')}.{$this->xpdo->escape('id')} = {$this->xpdo->escape('modUserGroupSetting')}.{$this->xpdo->escape('group')}"));
        $query->innerJoin('modUserGroupMember', 'Member', array("{$this->xpdo->escape('Member')}.{$this->xpdo->escape('member')}" => $this->get('id'), "{$this->xpdo->escape('UserGroup')}.{$this->xpdo->escape('id')} = {$this->xpdo->escape('Member')}.{$this->xpdo->escape('user_group')}"));
        $query->sortby("{$this->xpdo->escape('UserGroup')}.{$this->xpdo->escape('rank')}", 'DESC');
        $query->sortby("{$this->xpdo->escape('Member')}.{$this->xpdo->escape('rank')}", 'DESC');
        $ugss = $this->xpdo->getCollection('modUserGroupSetting', $query);
        /** @var modUserGroupSetting $ugs */
        foreach ($ugss as $ugs) {
            if ($ugs->get('group') === $this->get('primary_group')) {
                $primary[$ugs->get('key')] = $ugs->get('value');
            } else {
                $settings[$ugs->get('key')] = $ugs->get('value');
            }
        }
        return array_merge($settings, $primary);
    }

    public function getPrimaryGroup() {
        if (!$this->isAuthenticated($this->xpdo->context->get('key'))) {
            return null;
        }
        $userGroup = $this->getOne('PrimaryGroup');
        if (!$userGroup) {
            $c = $this->xpdo->newQuery('modUserGroup');
            $c->innerJoin('modUserGroupMember','UserGroupMembers');
            $c->where(array(
                'UserGroupMembers.member' => $this->get('id'),
            ));
            $c->sortby('"UserGroupMembers"."rank"','ASC');
            $userGroup = $this->xpdo->getObject('modUserGroup',$c);
        }
        return $userGroup;
    }
}