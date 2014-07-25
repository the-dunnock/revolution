<?php
/**
 * @package modx
 * @subpackage pgsql
 */
require_once (dirname(dirname(__FILE__)) . '/modresource.class.php');
/**
 * @package modx
 * @subpackage pgsql
 */
class modResource_pgsql extends modResource {
    public static function listGroups(modResource &$resource, array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        $result = array('collection' => array(), 'total' => 0);
        $c = $resource->xpdo->newQuery('modResourceGroup');
        $c->leftJoin('modResourceGroupResource', 'ResourceGroupResource', array(
            "{$resource->xpdo->escape('ResourceGroupResource')}.{$resource->xpdo->escape('document_group')} = {$resource->xpdo->escape('modResourceGroup')}.{$resource->xpdo->escape('id')}",
            "{$resource->xpdo->escape('ResourceGroupResource')}.{$resource->xpdo->escape('document')}" => $resource->get('id')
        ));
        $result['total'] = $resource->xpdo->getCount('modResourceGroup',$c);
        $c->select($resource->xpdo->getSelectColumns('modResourceGroup', 'modResourceGroup'));
        $c->select(array("CASE WHEN {$resource->xpdo->escape('ResourceGroupResource')}.{$resource->xpdo->escape('document')} IS NULL THEN 0 ELSE 1 END AS access"));
        foreach ($sort as $sortKey => $sortDir) {
            $c->sortby($resource->xpdo->escape('modResourceGroup') . '.' . $resource->xpdo->escape($sortKey), $sortDir);
        }
        if ($limit > 0) $c->limit($limit, $offset);
        $result['collection'] = $resource->xpdo->getCollection('modResourceGroup', $c);
        return $result;
    }

    public static function getTemplateVarCollection(modResource &$resource) {
        $c = $resource->xpdo->newQuery('modTemplateVar');
        $c->query['distinct'] = 'DISTINCT';
        $c->select($resource->xpdo->getSelectColumns('modTemplateVar', 'modTemplateVar'));
        if ($resource->isNew()) {
            $c->select(array(
                "{$resource->xpdo->escape('modTemplateVar')}.{$resource->xpdo->escape('default_text')} as value",
                "0 as {$resource->xpdo->escape('resourceId')}",
                "{$resource->xpdo->escape('tvtpl')}.{$resource->xpdo->escape('rank')} as \"tv_rank\""
            ));
        } else {
            $c->select(array(
                "CASE WHEN {$resource->xpdo->escape('tvc')}.{$resource->xpdo->escape('value')} IS NULL THEN {$resource->xpdo->escape('modTemplateVar')}.{$resource->xpdo->escape('default_text')} ELSE {$resource->xpdo->escape('tvc')}.{$resource->xpdo->escape('value')} END AS access",
                $resource->get('id')." AS {$resource->xpdo->escape('resourceId')}"
            ));
        }
        $c->innerJoin('modTemplateVarTemplate','tvtpl',array(
            "{$resource->xpdo->escape('tvtpl')}.{$resource->xpdo->escape('tmplvarid')} = {$resource->xpdo->escape('modTemplateVar')}.{$resource->xpdo->escape('id')}",
            "{$resource->xpdo->escape('tvtpl')}.{$resource->xpdo->escape('templateid')}" => $resource->get('template'),
        ));
        if (!$resource->isNew()) {
            $c->leftJoin('modTemplateVarResource','tvc',array(
                "{$resource->xpdo->escape('tvc')}.{$resource->xpdo->escape('tmplvarid')} = {$resource->xpdo->escape('modTemplateVar')}.{$resource->xpdo->escape('id')}",
                "{$resource->xpdo->escape('tvc')}.{$resource->xpdo->escape('contentid')}" => $resource->get('id'),
            ));
        }
        if ($resource->isNew()) {
            $c->sortby('"tv_rank"');
        }
        $c->sortby("{$resource->xpdo->escape('modTemplateVar')}.{$resource->xpdo->escape('rank')}");
        return $resource->xpdo->getCollection('modTemplateVar', $c);
    }
}