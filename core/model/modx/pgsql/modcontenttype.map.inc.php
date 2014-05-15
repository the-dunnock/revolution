<?php
/**
 * @package modx
 * @subpackage pgsql
 */
$xpdo_meta_map['modContentType']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'content_type',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => NULL,
    'description' => NULL,
    'mime_type' => NULL,
    'file_extensions' => NULL,
    'headers' => NULL,
    'binary' => 0,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'unique',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'mime_type' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
    ),
    'file_extensions' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
    ),
    'headers' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'array',
    ),
    'binary' => 
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
  'aggregates' => 
  array (
    'Resources' => 
    array (
      'class' => 'modResource',
      'local' => 'id',
      'foreign' => 'content_type',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
  'validation' => 
  array (
    'rules' => 
    array (
      'name' => 
      array (
        'name' => 
        array (
          'type' => 'xPDOValidationRule',
          'rule' => 'xPDOMinLengthValidationRule',
          'value' => '1',
          'message' => 'content_type_err_ns_name',
        ),
      ),
    ),
  ),
);
