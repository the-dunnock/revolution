<?php
/**
 * @package modx
 * @subpackage pgsql
 */
require_once (dirname(dirname(__FILE__)) . '/modtemplatevar.class.php');
/**
 * @package modx
 * @subpackage pgsql
 */
class modTemplateVar_pgsql extends modTemplateVar {
    public function checkForFormCustomizationRules($value,&$resource) {
        if ($this->xpdo->request && $this->xpdo->user instanceof modUser) {
            if (empty($resource)) {
                $resource =& $this->xpdo->resource;
            }
            if ($this->xpdo->getOption('form_customization_use_all_groups',null,false)) {
                $userGroups = $this->xpdo->user->getUserGroups();
            } else {
                $primaryGroup = $this->xpdo->user->getPrimaryGroup();
                if ($primaryGroup) {
                    $userGroups = array($primaryGroup->get('id'));
                }
            }
            $c = $this->xpdo->newQuery('modActionDom');
            $c->innerJoin('modFormCustomizationSet','FCSet');
            $c->innerJoin('modFormCustomizationProfile','Profile','"FCSet"."profile" = "Profile""."id"');
            $c->leftJoin('modFormCustomizationProfileUserGroup','ProfileUserGroup','"Profile"."id" = "ProfileUserGroup"."profile"');
            $c->leftJoin('modFormCustomizationProfile','UGProfile','"UGProfile"."id" = "ProfileUserGroup"."profile"');
            $ruleFieldName = $this->xpdo->escape('rule');
            $c->where(array(
                array(
                    "(\"modActionDom\".{$ruleFieldName} = 'tvDefault'
                   OR \"modActionDom\".{$ruleFieldName} = 'tvVisible'
                   OR \"modActionDom\".{$ruleFieldName} = 'tvTitle')"
                ),
                "'tv{$this->get('id')}' IN ({$this->xpdo->escape('modActionDom')}.{$this->xpdo->escape('name')})",
                '"FCSet"."active"' => true,
                '"Profile"."active"' => true,
            ));
            if (!empty($userGroups)) {
                $c->where(array(
                    array(
                        'ProfileUserGroup.usergroup:IN' => $userGroups,
                        array(
                            'OR:ProfileUserGroup.usergroup:IS' => null,
                            'AND:UGProfile.active:=' => true,
                        ),
                    ),
                    'OR:ProfileUserGroup.usergroup:=' => null,
                ),xPDOQuery::SQL_AND,null,2);
            }
            if (!empty($this->xpdo->request) && !empty($this->xpdo->request->action)) {
                $c->where(array(
                    'modActionDom.action' => $this->xpdo->request->action,
                ));
            }
            $c->select($this->xpdo->getSelectColumns('modActionDom','modActionDom'));
            $c->select(array(
                '"FCSet".constraint_class',
                '"FCSet".constraint_field',
                '"FCSet".' . $this->xpdo->escape('constraint'),
                '"FCSet".template',
            ));
            $c->sortby('"FCSet"."template"','ASC');
            $c->sortby('"modActionDom"."rank"','ASC');
            $domRules = $this->xpdo->getCollection('modActionDom',$c);
            /** @var modActionDom $rule */
            foreach ($domRules as $rule) {
                if (!empty($resource)) {
                    $template = $rule->get('template');
                    if (!empty($template) && $template != $resource->get('template')) {
                        continue;
                    }
                }
                switch ($rule->get('rule')) {
                    case 'tvVisible':
                        if ($rule->get('value') == 0) {
                            $this->set('type','hidden');
                        }
                        break;
                    case 'tvDefault':
                        $v = $rule->get('value');
                        if (empty($resourceId)) {
                            $value = $v;
                            $this->set('value',$v);
                        }
                        $this->set('default_text',$v);
                        break;
                    case 'tvTitle':
                        $v = $rule->get('value');
                        $this->set('caption',$v);
                        break;
                }
            }
            unset($domRules,$rule,$userGroups,$v,$c);
        }
        return $value;
    }

    public function checkResourceGroupAccess($user = null,$context = '') {
        $context = !empty($context) ? $context : '';

        $c = $this->xpdo->newQuery('modResourceGroup');
        $c->innerJoin('modTemplateVarResourceGroup','TemplateVarResourceGroups',array(
            '"TemplateVarResourceGroups"."documentgroup" = "modResourceGroup"."id"',
            '"TemplateVarResourceGroups"."tmplvarid"' => $this->get('id'),
        ));
        $resourceGroups = $this->xpdo->getCollection('modResourceGroup',$c);
        $hasAccess = true;
        if (!empty($resourceGroups)) {
            $hasAccess = false;
            /** @var modResourceGroup $resourceGroup */
            foreach ($resourceGroups as $resourceGroup) {
                if ($resourceGroup->hasAccess($user,$context)) {
                    $hasAccess = true;
                    break;
                }
            }
        }
        return $hasAccess;
    }
}