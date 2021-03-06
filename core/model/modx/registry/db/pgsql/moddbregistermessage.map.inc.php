
<?php
/**
 * @package modx
 * @subpackage registry.db.pgsql
 */
$xpdo_meta_map['modDbRegisterMessage']= array (
  'package' => 'modx.registry.db',
  'version' => '1.1',
  'table' => 'register_messages',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'topic' => NULL,
    'id' => NULL,
    'created' => NULL,
    'valid' => NULL,
    'accessed' => NULL,
    'accesses' => 0,
    'expires' => 0,
    'payload' => NULL,
    'kill' => 0,
  ),
  'fieldMeta' => 
  array (
    'topic' => 
    array (
      'dbtype' => 'integer',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
    'id' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'index' => 'pk',
    ),
    'created' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'index' => 'index',
    ),
    'valid' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'index' => 'index',
    ),
    'accessed' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
      'index' => 'index',
    ),
    'accesses' => 
    array (
      'dbtype' => 'integer',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'expires' => 
    array (
      'dbtype' => 'integer',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'payload' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'kill' => 
    array (
      'dbtype' => 'integer',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'topic' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'id_idx' => 
    array (
      'alias' => 'id_idx',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'id_idx' =>
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'created' => 
    array (
      'alias' => 'created',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'created' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'valid' => 
    array (
      'alias' => 'valid',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'valid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'accessed' => 
    array (
      'alias' => 'accessed',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'accessed' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'accesses' => 
    array (
      'alias' => 'accesses',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'accesses' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'expires' => 
    array (
      'alias' => 'expires',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'expires' => 
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
    'Topic' => 
    array (
      'class' => 'registry.db.modDbRegisterTopic',
      'local' => 'topic',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
