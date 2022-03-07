<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Ghc_module_utility_model extends CI_Model {
    
    private $_mysqli_field_types = array(
                                        MYSQLI_TYPE_DECIMAL             => 'DECIMAL',
                                        MYSQLI_TYPE_NEWDECIMAL          => 'DECIMAL_PRECISION',
                                        MYSQLI_TYPE_BIT                 => 'BIT',
                                        MYSQLI_TYPE_TINY                => 'TINYINT',
                                        MYSQLI_TYPE_SHORT               => 'SMALLINT',
                                        MYSQLI_TYPE_LONG                => 'INT',
                                        MYSQLI_TYPE_FLOAT               => 'FLOAT',
                                        MYSQLI_TYPE_DOUBLE              => 'DOUBLE',
                                        MYSQLI_TYPE_NULL                => 'DEFAULT_NULL',
                                        MYSQLI_TYPE_LONGLONG            => 'BIGINT',
                                        MYSQLI_TYPE_INT24               => 'MEDIUMINT',
                                        MYSQLI_TYPE_DATE                => 'DATE',
                                        MYSQLI_TYPE_TIME                => 'TIME',
                                        MYSQLI_TYPE_DATETIME            => 'DATETIME',
                                        MYSQLI_TYPE_TIMESTAMP           => 'TIMESTAMP',
                                        MYSQLI_TYPE_YEAR                => 'YEAR',
                                        MYSQLI_TYPE_NEWDATE             => 'DATE_NEW',
                                        MYSQLI_TYPE_ENUM                => 'ENUMERATED',
                                        MYSQLI_TYPE_SET                 => 'SET',
                                        MYSQLI_TYPE_TINY_BLOB           => 'TINY_BLOB',
                                        MYSQLI_TYPE_MEDIUM_BLOB         => 'MEDIUM_BLOB',
                                        MYSQLI_TYPE_LONG_BLOB           => 'LONG_BLOB',
                                        MYSQLI_TYPE_BLOB                => 'BLOB',
                                        MYSQLI_TYPE_VAR_STRING          => 'VARCHAR',
                                        MYSQLI_TYPE_STRING              => 'CHAR',
                                        MYSQLI_TYPE_GEOMETRY            => 'GEOMETRY'
                                        );
                                
    private $_mysqli_field_flags = array(
                                        MYSQLI_PRI_KEY_FLAG             => 'Primary Key',    // part of primary index
                                        MYSQLI_UNIQUE_KEY_FLAG          => 'Unique Key',    // part of a unique index
                                        MYSQLI_MULTIPLE_KEY_FLAG        => 'Multiple Key',    // part of an index (not specific)
                                        MYSQLI_PART_KEY_FLAG            => 'Partial Key',    // part of a multi-index
                                        MYSQLI_NUM_FLAG                 => 'Numeric',
                                        MYSQLI_UNSIGNED_FLAG            => 'Unsigned', 
                                        MYSQLI_ZEROFILL_FLAG            => 'Zero-fill',
                                        MYSQLI_AUTO_INCREMENT_FLAG      => 'Auto-increment',
                                        MYSQLI_TIMESTAMP_FLAG           => 'Timestamp',        // is a timestamp of some sort
                                        MYSQLI_BLOB_FLAG                => 'Blob',
                                        MYSQLI_SET_FLAG                 => 'Set',
                                        MYSQLI_GROUP_FLAG               => 'Group',
                                        MYSQLI_NOT_NULL_FLAG            => 'NOT NULL'
                                        );

    public function __construct() {
        parent::__construct();
    }

    public function describe_table($table) {
        return $this->db->field_data($table);
    }
    
    public function get_table_details($table) {
        $table_deets = array();
        $query = "SELECT * from " . $table . ' LIMIT 1';

        if($result = ee()->db->conn_id->query($query)){
            // Get field information for all columns
            $columns = $result->fetch_fields();
            foreach($columns as  $column){
                // set type to human readable
                $column->type = $this->_mysqli_field_types[$column->type];
                if($column->type == 'VARCHAR' || $column->type == 'BLOB'){
                    $column->length = $column->length/3;
                }
                $column->flag_types = array();
                $column->flags = decbin($column->flags);
                if ($column->flags & MYSQLI_NOT_NULL_FLAG) {
                    $column->flag_types[] = $this->_mysqli_field_flags[MYSQLI_NOT_NULL_FLAG];
                }
                if ($column->flags & MYSQLI_PRI_KEY_FLAG) {
                    $column->flag_types[] = $this->_mysqli_field_flags[MYSQLI_PRI_KEY_FLAG];
                }

                $col = new stdClass();
                $col->name = $column->name;
                $col->type = $column->type;
                $col->length = $column->length;
                if(in_array('NOT NULL', $column->flag_types)){
                    $col->not_null = 'TRUE';
                } else {
                    $col->not_null = 'FALSE';
                }
                if(in_array('Primary Key', $column->flag_types)){
                    $col->is_primary = 'TRUE';
                } else {
                    $col->is_primary = 'FALSE';
                }

                $table_deets[$column->name] = $col;
                
            }
        }
        return $table_deets;
    }
    
}