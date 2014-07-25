<?php
/**
 * @package modx
 * @subpackage sources.pgsql
 */
require_once (dirname(dirname(__FILE__)) . '/modmediasource.class.php');
class modMediaSource_pgsql extends modMediaSource {
    public function findPolicy($context = '') {
        $policy = array();
        $enabled = true;
        $context = 'mgr';
        if ($context === $this->xpdo->context->get('key')) {
            $enabled = (boolean) $this->xpdo->getOption('access_media_source_enabled', null, true);
        } elseif ($this->xpdo->getContext($context)) {
            $enabled = (boolean) $this->xpdo->contexts[$context]->getOption('access_media_source_enabled', true);
        }
        if ($enabled) {
            if (empty($this->_policies) || !isset($this->_policies[$context])) {
                $accessTable = $this->xpdo->getTableName('sources.modAccessMediaSource');
                $sourceTable = $this->xpdo->getTableName('sources.modMediaSource');
                $policyTable = $this->xpdo->getTableName('modAccessPolicy');
                $sql = "SELECT DISTINCT \"Acl\".\"target\", \"Acl\".\"principal\", \"Acl\".\"authority\", \"Acl\".\"policy\", \"Policy\".\"data\" FROM {$accessTable} \"Acl\" " .
                    "LEFT JOIN {$policyTable} \"Policy\" ON \"Policy\".\"id\" = \"Acl\".\"policy\" " .
                    "JOIN {$sourceTable} \"Source\" ON \"Acl\".\"principal_class\" = 'modUserGroup' " .
                    "AND (\"Acl\".\"context_key\" = :context OR \"Acl\".\"context_key\" IS NULL OR \"Acl\".\"context_key\" = '') " .
                    "AND \"Source\".\"id\" = \"Acl\".\"target\" " .
                    "WHERE \"Acl\".\"target\" = :source " .
                    "";//"GROUP BY Acl.target, Acl.principal, Acl.authority, Acl.policy";
                $bindings = array(
                    ':source' => $this->get('id'),
                    ':context' => $context,
                );
                $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
                if ($query->stmt && $query->stmt->execute()) {
                    while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                        $policy['sources.modAccessMediaSource'][$row['target']][] = array(
                            'principal' => $row['principal'],
                            'authority' => $row['authority'],
                            'policy' => $row['data'] ? $this->xpdo->fromJSON($row['data'], true) : array(),
                        );
                    }
                }
                $this->_policies[$context] = $policy;
            } else {
                $policy = $this->_policies[$context];
            }
        }
        return $policy;
    }
}