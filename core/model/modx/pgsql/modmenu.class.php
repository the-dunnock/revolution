<?php
/**
 * @package modx
 * @subpackage pgsql
 */
require_once (dirname(dirname(__FILE__)) . '/modmenu.class.php');
/**
 * @package modx
 * @subpackage pgsql
 */
class modMenu_pgsql extends modMenu {
    public function getSubMenus($start = '') {
        if (!$this->xpdo->lexicon) {
            $this->xpdo->getService('lexicon','modLexicon');
        }
        $this->xpdo->lexicon->load('menu','topmenu');

        $c = $this->xpdo->newQuery('modMenu');
        $c->setClassAlias('modmenu');
        $c->select($this->xpdo->getSelectColumns('modMenu', 'modmenu'));

        /* 2.2 and earlier support */
        $c->leftJoin('modAction','Action', "modmenu.action = CAST({$this->xpdo->escape('Action')}.{$this->xpdo->escape('id')} AS varchar)");
        $c->select(array(
            'action_controller' => "{$this->xpdo->escape('Action')}.{$this->xpdo->escape('controller')}",
            'action_namespace' => "{$this->xpdo->escape('Action')}.{$this->xpdo->escape('namespace')}"
        ));

        $c->where(array(
            'modmenu.parent' => $start,
        ));
        $c->sortby($this->xpdo->getSelectColumns('modMenu','modmenu','',array('menuindex')),'ASC');
        $menus = $this->xpdo->getCollection('modMenu',$c);
        if (count($menus) < 1) return array();

        $list = array();
        /** @var modMenu $menu */
        foreach ($menus as $menu) {
            $ma = $menu->toArray();
            $ma['id'] = $menu->get('text');
            $action = $menu->get('action');
            $namespace = $menu->get('namespace');

            // allow 2.2 and earlier actions
            $deprecatedNamespace = $menu->get('action_namespace');
            if (!empty($deprecatedNamespace)) {
                $namespace = $deprecatedNamespace;
            }
            if ($namespace != 'core') {
                $this->xpdo->lexicon->load($namespace.':default');
            }

            /* if 3rd party menu item, load proper text */
            if (!empty($action)) {
                if (!empty($namespace) && $namespace != 'core') {
                    $ma['text'] = $menu->get('text') === 'user'
                        ? $this->xpdo->lexicon($menu->get('text'), array('username' => $this->xpdo->getLoginUserName()))
                        : $this->xpdo->lexicon($menu->get('text'));
                } else {
                    $ma['text'] = $menu->get('text') === 'user'
                        ? $this->xpdo->lexicon($menu->get('text'), array('username' => $this->xpdo->getLoginUserName()))
                        : $this->xpdo->lexicon($menu->get('text'));
                }
            } else {
                $ma['text'] = $menu->get('text') === 'user'
                    ? $this->xpdo->lexicon($menu->get('text'), array('username' => $this->xpdo->getLoginUserName()))
                    : $this->xpdo->lexicon($menu->get('text'));
            }

            $desc = $menu->get('description');
            $ma['description'] = !empty($desc) ? $this->xpdo->lexicon($desc) : '';
            $ma['children'] = $menu->get('text') != '' ? $this->getSubMenus($menu->get('text')) : array();

            if ($menu->get('controller')) {
                $ma['controller'] = $menu->get('controller');
            } else {
                $ma['controller'] = '';
            }
            $list[] = $ma;
        }
        unset($menu,$desc,$namespace,$ma);
        return $list;
    }
}