<?php
/**
 * @package modx
 * @subpackage pgsql
 */
require_once (dirname(dirname(__FILE__)) . '/modtemplate.class.php');
/**
 * @package modx
 * @subpackage pgsql
 */
class modTemplate_pgsql extends modTemplate {
    public function & getMany($alias, $criteria= null, $cacheFlag= true) {
        $collection= array ();
        if (($alias === 'TemplateVars' || $alias === 'modTemplateVar') && ($criteria === null || strtolower($criteria) === 'all')) {
            $c = $this->xpdo->newQuery('modTemplateVar');
            $c->query['distinct'] = 'DISTINCT';
            $c->select($this->xpdo->getSelectColumns('modTemplateVar'));
            $c->select(array('value' => $this->xpdo->getSelectColumns('modTemplateVar', 'modTemplateVar', '', array('default_text'))));
            $c->select(array('tv_rank' => '"tvtpl"."rank"'));
            $c->innerJoin('modTemplateVarTemplate','tvtpl',array(
                'tvtpl.tmplvarid = "modTemplateVar".id',
                'tvtpl.templateid' => $this->get('id'),
            ));
            $c->sortby('tv_rank,"modTemplateVar".rank');

            $collection = $this->xpdo->getCollection('modTemplateVar', $c, $cacheFlag);
        } else {
            $collection= parent :: getMany($alias, $criteria, $cacheFlag);
        }
        return $collection;
    }

    public static function listTemplateVars(modTemplate &$template, array $sort = array('name' => 'ASC'), $limit = 0, $offset = 0,array $conditions = array()) {
        $result = array('collection' => array(), 'total' => 0);
        $c = $template->xpdo->newQuery('modTemplateVar');
        $result['total'] = $template->xpdo->getCount('modTemplateVar',$c);
        $c->select($template->xpdo->getSelectColumns('modTemplateVar','modTemplateVar'));
        $c->leftJoin('modTemplateVarTemplate','modTemplateVarTemplate', array(
            "\"modTemplateVarTemplate\".\"tmplvarid\" = \"modTemplateVar\".\"id\"",
            '"modTemplateVarTemplate"."templateid"' => $template->get('id')
        ));
        $c->leftJoin('modCategory','Category');
        if (!empty($conditions)) { $c->where($conditions); }
        $c->select(array(
            'CASE WHEN "modTemplateVarTemplate"."tmplvarid" IS NULL THEN 0 ELSE 1 END as access',
            'COALESCE(CAST("modTemplateVarTemplate"."rank" AS VARCHAR), \'-\') AS tv_rank',
            'category_name' => '"Category"."category"',
        ));
        foreach ($sort as $sortKey => $sortDir) {
            $c->sortby($sortKey, $sortDir);
        }
        if ($limit > 0) $c->limit($limit, $offset);
        $result['collection'] = $template->xpdo->getCollection('modTemplateVar',$c);
        return $result;
    }

    public function getTemplateVars() {
        $c = $this->xpdo->newQuery('modTemplateVar');
        $c->innerJoin('modTemplateVarTemplate','TemplateVarTemplates');
        $c->where(array(
            '"TemplateVarTemplates"."templateid"' => $this->get('id'),
        ));
        $c->sortby('"TemplateVarTemplates"."rank"','ASC');
        return $this->xpdo->getCollection('modTemplateVar',$c);
    }
}