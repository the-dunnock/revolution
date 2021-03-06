<?php
/**
 * @package modx
 * @subpackage registry.db.pgsql
 */
$xpdo_meta_map['modDbRegisterTopic']= array (
  'package' => 'modx.registry.db',
  'version' => '1.1',
  'table' => 'register_topics',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'queue' => NULL,
    'name' => NULL,
    'created' => NULL,
    'updated' => NULL,
    'options' => NULL,
  ),
  'fieldMeta' => 
  array (
    'queue' => 
    array (
      'dbtype' => 'integer',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'fk',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'fk',
    ),
    'created' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
    ),
    'updated' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
    ),
    'options' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'array',
    ),
  ),
  'indexes' => 
  array (
    'queue' => 
    array (
      'alias' => 'queue',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'queue' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'name' => 
    array (
      'alias' => 'name',
      'primary' => false,
      'unique' => false,
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
    'Messages' => 
    array (
      'class' => 'registry.db.modDbRegisterMessage',
      'local' => 'id',
      'foreign' => 'topic',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'Queue' => 
    array (
      'class' => 'registry.db.modDbRegisterQueue',
      'local' => 'queue',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
