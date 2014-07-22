<?php
/**
 * @package modx
 * @subpackage pgsql
 */
require_once (dirname(dirname(__FILE__)) . '/modcontext.class.php');
/**
 * @package modx
 * @subpackage pgsql
 */
class modContext_pgsql extends modContext {
    public static function getResourceCacheMapStmt(&$context) {
        $stmt = false;
        if ($context instanceof modContext) {
            $tblResource= $context->xpdo->getTableName('modResource');
            $tblContextResource= $context->xpdo->getTableName('modContextResource');
            $resourceFields= array('id','parent','uri');
            $resourceCols= $context->xpdo->getSelectColumns('modResource', 'r', '', $resourceFields);
            $bindings = array($context->get('key'), $context->get('key'));
            $sql = "SELECT DISTINCT {$resourceCols} FROM {$tblResource} r
                LEFT JOIN {$tblContextResource} cr ON cr.context_key = ? AND r.id = cr.resource
                WHERE r.id != r.parent AND (r.context_key = ? OR cr.context_key IS NOT NULL) AND r.deleted = 0
                ";
            $criteria = new xPDOCriteria($context->xpdo, $sql, $bindings, false);
            if ($criteria && $criteria->stmt && $criteria->stmt->execute()) {
                $stmt =& $criteria->stmt;
            }
        }
        return $stmt;
    }

    public static function getWebLinkCacheMapStmt(&$context) {
        $stmt = false;
        if ($context instanceof modContext) {
            $tblResource = $context->xpdo->getTableName('modResource');
            $tblContextResource = $context->xpdo->getTableName('modContextResource');
            $resourceFields= array('id','content');
            $resourceCols= $context->xpdo->getSelectColumns('modResource', 'r', '', $resourceFields);
            $bindings = array($context->get('key'), $context->get('key'));
            $sql = "SELECT DISTINCT {$resourceCols} FROM {$tblResource} r
                LEFT JOIN {$tblContextResource} cr ON cr.context_key = ? AND r.id = cr.resource
                WHERE r.id != r.parent AND r.class_key = 'modWebLink' AND (r.context_key = ? OR cr.context_key IS NOT NULL) AND r.deleted = 0
                ";
            $criteria = new xPDOCriteria($context->xpdo, $sql, $bindings, false);
            if ($criteria && $criteria->stmt && $criteria->stmt->execute()) {
                $stmt =& $criteria->stmt;
            }
        }
        return $stmt;
    }

    public function findPolicy($context = '') {
        $policy = array();
        $enabled = true;
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if (!is_object($this->xpdo->context) || $context === $this->xpdo->context->get('key')) {
            $enabled = (boolean) $this->xpdo->getOption('access_context_enabled', null, true);
        } elseif ($this->xpdo->getContext($context)) {
            $enabled = (boolean) $this->xpdo->contexts[$context]->getOption('access_context_enabled', true);
        }
        if ($enabled) {
            if (empty($this->_policies) || !isset($this->_policies[$context])) {
                $c = $this->xpdo->newQuery('modAccessContext');
                $c->setClassAlias('modaccesscontext');
                $c->leftJoin('modAccessPolicy','policy', 'policy.id = modaccesscontext.policy');
                $c->select(array(
                    'modaccesscontext.id',
                    'modaccesscontext.target',
                    'modaccesscontext.principal',
                    'modaccesscontext.authority',
                    'modaccesscontext.policy',
                    'policy.data',
                ));
                $c->where(array(
                    'modaccesscontext.principal_class' => 'modUserGroup',
                    'modaccesscontext.target' => $this->get('key'),
                ));
                $c->sortby('modaccesscontext.target,modaccesscontext.principal,modaccesscontext.authority,modaccesscontext.policy');
                $acls = $this->xpdo->getCollection('modAccessContext',$c);
                foreach ($acls as $acl) {
                    $policy['modAccessContext'][$acl->get('target')][] = array(
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