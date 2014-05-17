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
<<<<<<< HEAD
        $c->select(array(
            $xpdo->getSelectColumns('modContextSetting','modContextSetting'),
=======
        $c->setClassAlias('modcontextsetting');
        $c->select(array(
            $xpdo->getSelectColumns('modContextSetting','modcontextsetting'),
>>>>>>> Loads of sql escapes, added override methods to pgsql objects
        ));
        $c->select(array(
            'entry.value AS name_trans',
            'description.value AS description_trans',
        ));
<<<<<<< HEAD
        $c->leftJoin('modLexiconEntry','entry',"'setting_'||\"modContextSetting\".{$xpdo->escape('key')} = entry.name");
        $c->leftJoin('modLexiconEntry','description',"'setting_'||\"modContextSetting\".{$xpdo->escape('key')}||'_desc' = description.name");
        $c->where($criteria);
        $count = $xpdo->getCount('modContextSetting',$c);
        $c->sortby($xpdo->getSelectColumns('modContextSetting','modContextSetting','',array('area')),'ASC');
        foreach($sort as $field=> $dir) {
            $c->sortby($xpdo->getSelectColumns('modContextSetting','modContextSetting','',array($field)),$dir);
=======
        $c->leftJoin('modLexiconEntry','entry',"'setting_'||modcontextsetting.{$xpdo->escape('key')}) = entry.name");
        $c->leftJoin('modLexiconEntry','description',"'setting_'||modContextSetting.{$xpdo->escape('key')}||'_desc') = description.name");
        $c->where($criteria);

        $count = $xpdo->getCount('modContextSetting',$c);
        $c->sortby($xpdo->getSelectColumns('modContextSetting','modcontextsetting','',array('area')),'ASC');
        foreach($sort as $field=> $dir) {
            $c->sortby($xpdo->getSelectColumns('modContextSetting','modcontextsetting','',array($field)),$dir);
>>>>>>> Loads of sql escapes, added override methods to pgsql objects
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