<?php
/**
 * @package modx
 * @subpackage pgsql
 */
require_once (dirname(dirname(__FILE__)) . '/modcontextsetting.class.php');
/**
 * @package modx
 * @subpackage pgsql
 */
class modContextSetting_pgsql extends modContextSetting {
    public static function listSettings(xPDO &$xpdo, array $criteria = array(), array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        /* build query */
        $c = $xpdo->newQuery('modContextSetting');
        $c->setClassAlias('modcontextsetting');
        $c->select(array(
            $xpdo->getSelectColumns('modContextSetting','modcontextsetting'),
        ));
        $c->select(array(
            'entry.value AS name_trans',
            'description.value AS description_trans',
        ));
        $c->leftJoin('modLexiconEntry','entry',"'setting_'||\"modContextSetting\".{$xpdo->escape('key')} = entry.name");
        $c->leftJoin('modLexiconEntry','description',"'setting_'||\"modContextSetting\".{$xpdo->escape('key')}||'_desc' = description.name");
        $c->where($criteria);
        $count = $xpdo->getCount('modContextSetting',$c);
        $c->sortby($xpdo->getSelectColumns('modContextSetting','modContextSetting','',array('area')),'ASC');
        foreach($sort as $field=> $dir) {
            $c->sortby($xpdo->getSelectColumns('modContextSetting','modContextSetting','',array($field)),$dir);
        }
        if ((int) $limit > 0) {
            $c->limit((int) $limit, (int) $offset);
        }
        return array(
            'count'=> $count,
            'collection'=> $xpdo->getCollection('modContextSetting',$c)
        );
    }
}