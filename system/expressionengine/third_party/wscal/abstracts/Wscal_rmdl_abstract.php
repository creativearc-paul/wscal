<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * CRUD functions for a single db record
 * @package     ExpressionEngine
 * @category    Module
 * @author      Greg Crane
 * @link        http://ghcrane.com
 */

abstract class Wscal_rmdl_abstract extends CI_Model{

    /**
    * @property table name
    */
    const DB_TABLE = NULL;
    
    /**
    * @property table primary key
    */
    const DB_TABLE_PK = NULL;
    
    /**
    * @property record created key, must be mysql datetime type
    */
    const DB_TABLE_RECORD_CREATED_KEY = NULL;
    
    /**
    * @property record modified key, must be mysql datetime type
    */
    const DB_TABLE_RECORD_MODIFIED_KEY = NULL;

    public function __construct() {
        parent::__construct();
        // initialize fields for this model
        $field_data = $this->db->field_data($this::DB_TABLE);
        foreach($field_data as $field){
            $this->{$field->name} = '';
        }
    }

    /////////////////////////////
    // CRUD FUNCTIONS
    ////////////////////////////
    /**
    * create record
    * @access public
    * @return mixed
    */
    public function insert(){
        // unset primary key so we know we'll get an insert
        unset($this->{$this::DB_TABLE_PK});

        // set record modification keys as needed
        if(!is_null($this::DB_TABLE_RECORD_CREATED_KEY)){
            $this->{$this::DB_TABLE_RECORD_CREATED_KEY} = date("Y-m-d H:i:s");
        }
        if(!is_null($this::DB_TABLE_RECORD_MODIFIED_KEY)){
            $this->{$this::DB_TABLE_RECORD_MODIFIED_KEY} = date("Y-m-d H:i:s");
        }

        $this->db->insert($this::DB_TABLE, $this);
        if($this->db->insert_id()){
            $this->{$this::DB_TABLE_PK} = $this->db->insert_id();
            return $this->db->insert_id();
        }
        return FALSE;
    }
    
    /**
    * create record using key
    * @access public
    * @return mixed
    */
    public function insert_with_key(){
        if($this->db->insert($this::DB_TABLE, $this)){
            return TRUE;
        }
        return FALSE;
    }

    /**
    * update record
    * @access public
    * @return bool
    */
    public function update(){
        // unset record created key since we are updating and don't want to alter 
        if(!is_null($this::DB_TABLE_RECORD_CREATED_KEY)){
            unset($this->{$this::DB_TABLE_RECORD_CREATED_KEY});
        }
        // update record modified key
        if(!is_null($this::DB_TABLE_RECORD_MODIFIED_KEY)){
            $this->{$this::DB_TABLE_RECORD_MODIFIED_KEY} = date("Y-m-d H:i:s");
        }
        $this->db->update($this::DB_TABLE, $this, array($this::DB_TABLE_PK => $this->{$this::DB_TABLE_PK}));
        return TRUE;
    }

    /**
    * populate record from array or object
    * @access public
    * @param mixed $record
    * @return void
    */
    public function populate($record){     
        foreach($record as $property=>$value){
            if(property_exists($this, $property)){
                $this->$property = trim($value);
            }
        }
    }

    /**
    * load record from db
    * @access public
    * @param int $pk - primary key
    * @return bool
    */
    public function load($pk){
        $query = $this->db->get_where($this::DB_TABLE, array($this::DB_TABLE_PK => $pk));
        if($query->num_rows() > 0){
            $this->populate($query->row());
            return TRUE;
        }
        return FALSE;
    }
    
    /**
    * load record from db
    * @access public
    * @param array $where - column => value
    * @return bool
    */
    public function load_where(array $where){
        $query = $this->db->get_where($this::DB_TABLE, $where, 1);
        if($query->num_rows() == 1){
            $this->populate($query->row());
            return TRUE;
        }
        return FALSE;
    }

    /**
    * delete record
    * @access public
    * @return bool
    */
    public function delete(){
        $this->db->delete($this::DB_TABLE, array($this::DB_TABLE_PK => $this->{$this::DB_TABLE_PK}));
        if($this->db->affected_rows() > 0){
            unset($this->{$this::DB_TABLE_PK});
            return TRUE;
        }
        return FALSE;
    }

    /**
    * save record
    * @access public
    * @return bool
    */
    public function save(){
        if(isset($this->{$this::DB_TABLE_PK}) && $this->{$this::DB_TABLE_PK} != ''){
            $this->_prep_fields();
            return $this->update();
        } else {
            $this->_prep_fields();
            return $this->insert();
        }
    }
    
    /**
    * clear model object
    * @access public
    * @return void
    */
    public function clear(){
        foreach($this as $property=>$value){
            $this->$property = NULL;
        }
    }
   
    /**
    * delete multiple records
    * @access public
    * @param array $where - column => value
    * @return bool
    */
    public function delete_where(array $where){
        $this->db->delete($this::DB_TABLE, $where);
        if($this->db->affected_rows() > 0){
            return TRUE;
        }
        return FALSE;
    }
    
    /**
    * get count where
    * @access public
    * @return int record count
    */
    public function count_rows_where(array $where){
        foreach($where as $column=>$value){
            $this->db->where($column,$value);
        }
        $this->db->select($this::DB_TABLE_PK);
        $resultsCount = $this->db->count_all_results($this::DB_TABLE);
        return $resultsCount;
    }
    
    /**
    * update records where
    * @access public
    * @return bool
    */
    public function update_where(array $data, array $where){
        foreach($where as $column=>$value){
            $this->db->where($column, $value);
        }
        $this->db->update($this::DB_TABLE, $data);
        if($this->db->affected_rows() > 0){
            return TRUE;
        }
        return FALSE;
    }

    /**
    * make sure int fields are actually ints, and trim data
    * @access public
    * @return int record count
    */
    public function _prep_fields(){

    }

}