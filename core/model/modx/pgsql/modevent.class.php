<?php
/**
 * @package modx
 * @subpackage pgsql
 */
require_once (dirname(dirname(__FILE__)) . '/modevent.class.php');
/**
 * @package modx
 * @subpackage pgsql
 */
class modEvent_pgsql extends modEvent {
    public static function listEvents(xPDO &$xpdo, $plugin, array $criteria = array(), array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        $c = $xpdo->newQuery('modEvent');
        $c->setClassAlias('modevent');
        $count = $xpdo->getCount('modEvent',$c);
        $c->select($xpdo->getSelectColumns('modEvent','modevent'));
        $c->select(array(
            "CASE WHEN {$xpdo->escape('modpluginevent')}.{$xpdo->escape('pluginid')} IS NULL THEN 0 ELSE 1 END AS enabled",
            'modpluginevent.priority AS priority',
            'modpluginevent.propertyset AS propertyset',
        ));
        $c->leftJoin('modPluginEvent','modpluginevent','
            modpluginevent.event = modevent.name
            AND modpluginevent.pluginid = '.$plugin.'
        ');
        $c->where($criteria);
        foreach($sort as $field=> $dir) {
            $c->sortby($xpdo->getSelectColumns('modEvent','modevent','',array($field)),$dir);
        }
        if ((int) $limit > 0) {
            $c->limit((int) $limit, (int) $offset);
        }
        return array(
            'count'=> $count,
            'collection'=> $xpdo->getCollection('modEvent',$c)
        );
    }
}