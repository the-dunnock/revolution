<?php
/**
 * @package modx
 * @subpackage pgsql
 */
$xpdo_meta_map['modUserMessage']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'user_messages',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'type' => '',
    'subject' => '',
    'message' => '',
    'sender' => 0,
    'recipient' => 0,
    'private' => 0,
    'date_sent' => NULL,
    'read' => 0,
  ),
  'fieldMeta' => 
  array (
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '15',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'subject' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'message' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'sender' => 
    array (
      'dbtype' => 'integer',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'recipient' => 
    array (
      'dbtype' => 'integer',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'private' => 
    array (
      'dbtype' => 'integer',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'date_sent' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'datetime',
      'null' => false,
    ),
    'read' => 
    array (
      'dbtype' => 'smallint',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'aggregates' => 
  array (
    'Sender' => 
    array (
      'class' => 'modUser',
      'local' => 'sender',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Recipient' => 
    array (
      'class' => 'modUser',
      'local' => 'recipient',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);