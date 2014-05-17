<?php
/**
 * @package modx
 * @subpackage pgsql
 */
require_once (dirname(dirname(__FILE__)) . '/modcategory.class.php');
/**
 * @package modx
 * @subpackage pgsql
 */
class modCategory_pgsql extends modCategory {
    public function findPolicy($context = '') {
        $policy = array();
        $enabled = true;
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if ($context === $this->xpdo->context->get('key')) {
            $enabled = (boolean) $this->xpdo->getOption('access_category_enabled', null, true);
        } elseif ($this->xpdo->getContext($context)) {
            $enabled = (boolean) $this->xpdo->contexts[$context]->getOption('access_category_enabled', true);
        }
        if ($enabled) {
            if (empty($this->_policies) || !isset($this->_policies[$context])) {
                $aclSelectColumns = $this->xpdo->getSelectColumns('modAccessCategory','modaccessCategory','',array('id','target','principal','authority','policy'));
                $c = $this->xpdo->newQuery('modAccessCategory');
                $c->setClassAlias('modaccesscategory');
                $c->select($aclSelectColumns);
                $c->select($this->xpdo->getSelectColumns('modAccessPolicy','policy','',array('data')));
                $c->leftJoin('modAccessPolicy','policy');
                $c->innerJoin('modcategoryclosure','categoryclosure',array(
                    'categoryclosure.descendant:=' => $this->get('id'),
                    'modcccesscategory.principal_class:=' => 'modUserGroup',
                    'categoryclosure.ancestor = modcccesscategory.target',
                    array(
                        'modaccesscategory.context_key:=' => $context,
                        'OR:modaccesscategory.context_key:=' => null,
                        'OR:modaccesscategory.context_key:=' => '',
                    ),
                ));
                $c->groupby($aclSelectColumns);
                $c->sortby($this->xpdo->getSelectColumns('modCategoryClosure','categoryclosure','',array('depth')).' DESC, '.$this->xpdo->getSelectColumns('modAccessCategory','modaccesscategory','',array('authority')).' ASC','');
                $acls = $this->xpdo->getIterator('modAccessCategory',$c);

                foreach ($acls as $acl) {
                    $policy['modAccessCategory'][$acl->get('target')][] = array(
                        'principal' => $acl->get('principal'),
                        'authority' => $acl->get('authority'),
                        'policy' => $acl->get('data') ? $this->xpdo->fromJSON($acl->get('data'), true) : array(),
                    );
                }
                $this->_policies[$context] = $policy;
            } else {
                $policy = $this->_policies[$context];
            }
        }
        return $policy;
    }
}