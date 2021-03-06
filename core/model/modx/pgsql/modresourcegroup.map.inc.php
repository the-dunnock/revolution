<?php
/**
 * @package modx
 * @subpackage pgsql
 */
$xpdo_meta_map['modResourceGroup']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'documentgroup_names',
  'extends' => 'modAccessibleSimpleObject',
  'fields' => 
  array (
    'name' => '',
    'private_memgroup' => 0,
    'private_webgroup' => 0,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
    'private_memgroup' => 
    array (
      'dbtype' => 'smallint',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
    'private_webgroup' => 
    array (
      'dbtype' => 'smallint',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
  ),
  'indexes' => 
  array (
    'name' => 
    array (
      'alias' => 'name',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'name' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'ResourceGroupResources' => 
    array (
      'class' => 'modResourceGroupResource',
      'local' => 'id',
      'foreign' => 'document_group',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'TemplateVarResourceGroups' => 
    array (
      'class' => 'modTemplateVarResourceGroup',
      'local' => 'id',
      'foreign' => 'documentgroup',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Acls' => 
    array (
      'class' => 'modAccessResourceGroup',
      'local' => 'id',
      'foreign' => 'target',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
);
