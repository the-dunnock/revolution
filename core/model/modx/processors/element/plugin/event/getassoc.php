<?php
/**
 * Gets a list of system events
 *
 * @package modx
 * @subpackage processors.element.plugin.event
 */
if (!$modx->hasPermission('view_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin','system_events');

/* setup default properties */
$isLimit = empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$name = $modx->getOption('name',$scriptProperties,false);
$event = $modx->getOption('event',$scriptProperties,false);

$c = $modx->newQuery('modPlugin');
if (!empty($name)) {
    $c->where(array('name:LIKE' => '%'.$name.'%'));
}
if (!empty($event)) {
    $c->innerJoin('modPluginEvent','modPluginEvent',array(
        "{$modx->escape('modPluginEvent')}.{$modx->escape('pluginid')}  = {$modx->escape('modPlugin')}.{$modx->escape('id')}",
        "{$modx->escape('modPluginEvent')}.{$modx->escape('event')}" => $event
    ));
    $c->select($modx->getSelectColumns('modPlugin','modPlugin'));
    $c->select(array(
        "{$modx->escape('modPluginEvent')}.{$modx->escape('priority')}",
        "{$modx->escape('modPluginEvent')}.{$modx->escape('pluginid')}",
        "{$modx->escape('modPluginEvent')}.{$modx->escape('propertyset')}"
    ));
}
$count = $modx->getCount('modPlugin',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$plugins = $modx->getCollection('modPlugin',$c);

$list = array();
foreach ($plugins as $plugin) {
    $pluginArray = $plugin->toArray();

    $list[] = array(
        $pluginArray['id'],
        $pluginArray['name'],
        $pluginArray['priority'],
        $pluginArray['propertyset'],
    );
}
return $modx->error->success('',$list);