<?php
/**
 * @package modx
 * @subpackage pgsql
 */
require_once (dirname(dirname(__FILE__)) . '/modelement.class.php');
/**
 * @package modx
 * @subpackage pgsql
 */
class modElement_pgsql extends modElement {
    public function getPropertySet($setName = null) {
        $propertySet= null;
        $name = $this->get('name');
        if (strpos($name, '@') !== false) {
            $psName= '';
            $split= xPDO :: escSplit('@', $name);
            if ($split && isset($split[1])) {
                $name= $split[0];
                $psName= $split[1];
                $filters= xPDO :: escSplit(':', $setName);
                if ($filters && isset($filters[1]) && !empty($filters[1])) {
                    $psName= $filters[0];
                    $name.= ':' . $filters[1];
                }
                $this->set('name', $name);
            }
            if (!empty($psName)) {
                $psObj= $this->xpdo->getObjectGraph('modPropertySet', '{"Elements":{}}', array(
                    "{$this->xpdo->escape('Elements')}.{$this->xpdo->escape('element')}" => $this->id,
                    "{$this->xpdo->escape('Elements')}.{$this->xpdo->escape('element_class')}" => $this->_class,
                    "{$this->xpdo->escape('modPropertySet')}.{$this->xpdo->escape('name')}" => $psName
                ));
                if ($psObj) {
                    $propertySet= $this->xpdo->parser->parseProperties($psObj->get('properties'));
                }
            }
        }
        if (!empty($setName)) {
            $propertySetObj= $this->xpdo->getObjectGraph('modPropertySet', '{"Elements":{}}', array(
                "{$this->xpdo->escape('Elements')}.{$this->xpdo->escape('element')}" => $this->id,
                "{$this->xpdo->escape('Elements')}.{$this->xpdo->escape('element_class')}" => $this->_class,
                "{$this->xpdo->escape('modPropertySet')}.{$this->xpdo->escape('name')}" => $setName
            ));
            if ($propertySetObj) {
                if (is_array($propertySet)) {
                    $propertySet= array_merge($propertySet, $this->xpdo->parser->parseProperties($propertySetObj->get('properties')));
                } else {
                    $propertySet= $this->xpdo->parser->parseProperties($propertySetObj->get('properties'));
                }
            }
        }
        return $propertySet;
    }
}