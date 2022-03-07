<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* Faux abstract class (since EE instantiates) used primarily for views 
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

abstract class Neoseo_vmdl_abstract extends CI_Model {

    /**
    * module tables
    */ 
    const OPTION_VALUES_TABLE   = 'exp_neoseo_option_values';
    const CHANNELS_TABLE        = 'exp_neoseo_channels';
    const ENTRIES_TABLE         = 'exp_neoseo_entries';
    
    /**
    * main table for this model $this->db->get($this::DB_TABLE);
    */
    const DB_TABLE = NULL;
    
    /**
    * primary key for main table
    */
    const DB_TABLE_PK = NULL;

    /**
    * maps column aliases to select statements

    * @var array
    */
    public $_columnMap = array();
    
    /**
    * maps join table and join conditions
    * @var array
    */
    public $_join = array();
    
    /**
    * maps date column aliases to the approriate format
    * @var array
    */
    public $_date_columns = array();
    
    /**
    * allows for a where condition to always apply
    * @var array
    */
    public $_always_where = array();
    
    /**
    * allows for a where_in condition to always apply
    * @var array
    */
    public $_always_where_in = array();
    
    /**
    * always select these fields, mostly needed if using modifiying results function
    * @var array
    */
    public $_always_select = array();
    
    /**
    * always group_by
    * @var array
    */
    public $_always_group_by = array();
    
    public $_clauses = array();
            
    public function __construct() {
        parent::__construct();
    }

    /**
    * get record(s) - 
    * @access public
    * @param int $limit
    * @param int $offset
    * @params array()
    * @return array of result objects
    */
    public function get_records($limit = 0, $offset = 0, $params = array(), $get_related = TRUE){
        
        $this->format_clause_data($params);

        foreach($this->_clauses as $clause=>$clauseData){
            
            switch($clause){
                case 'select': 
                    $this->db->select($clauseData, FALSE);
                    break;
                    
                case 'join': 
                    foreach($clauseData as $table=>$condition){
                        $this->db->join($table, $condition, 'left');
                    } 
                    break;
                    
                case 'where': 
                    $this->db->where($clauseData);
                    break;
                    
                case 'custom_where': 
                    $this->db->where($clauseData);
                    break;
                    
                case 'or_where': 
                    $this->db->or_where($clauseData);
                    break;
                    
                case 'where_in':
                    foreach($clauseData as $columnAlias=>$items){
                        $this->db->where_in($columnAlias, $items);
                    } 
                    break;
                    
                case 'like': 
                    foreach($clauseData as $columnAlias=>$term){
                        $this->db->like($columnAlias, $term);
                    } 
                    break;
                    
                case 'or_like': 
                    foreach($clauseData as $columnAlias=>$term){
                        $this->db->or_like($columnAlias, $term);
                    } 
                    break;
                    
                case 'before_like': 
                    foreach($clauseData as $columnAlias=>$term){
                        $this->db->like($columnAlias, $term, 'before');
                    }
                    break;
                    
                case 'after_like': 
                    foreach($clauseData as $columnAlias=>$term){
                        $this->db->like($columnAlias, $term, 'after');
                    } 
                    break;
                    
                case 'order_by': 
                    foreach($clauseData as $columnAlias=>$direction){
                        $this->db->order_by($columnAlias, $direction);
                    } 
                    break;
                    
                case 'group_by': 
                    foreach($clauseData as $columnAlias){
                        $this->db->group_by($columnAlias);
                    } 
                    break;
            }
            
        }
        
        //$this->db->save_queries = TRUE;
        
        // run appropriate query based on limit/offset params
        if($limit > 0) {
            $query = $this->db->get($this::DB_TABLE, $limit, $offset);
        } else {
            $query = $this->db->get($this::DB_TABLE);
        }
        
        //$lastQuery = $this->db->last_query();
        //die($lastQuery);

        // before returning results, pass to get_related() so additional processing can be done on the result array if needed
        if($get_related){
            $results = $query->result();
            return $this->get_related($results);
        }
        
        return $query->result();
    }
    
    /**
    * modify results if needed adding additional related queries and appending to the result set to build complex results
    * @access public
    * @param array $results_array
    * @return array
    */
    public function get_related($results_array){
        return $results_array;
    }
    
    /**
    * get count for pagination in listing tables, etc.
    * @access public
    * @param array $where
    * @param array $like
    * @return int record count
    */
    public function get_records_count($params = array()){

        $this->format_clause_data($params);
        
        foreach($this->_clauses as $clause=>$clauseData){
            
            switch($clause){
                case 'join': 
                    foreach($clauseData as $table=>$condition){
                        $this->db->join($table, $condition, 'left');
                    } 
                    break;
                case 'where': 
                    $this->db->where($clauseData);
                    break;
                case 'custom_where': 
                    $this->db->where($clauseData);
                    break;
                case 'or_where': 
                    $this->db->or_where($clauseData);
                    break;
                case 'where_in':
                    foreach($clauseData as $columnAlias=>$items){
                        $this->db->where_in($columnAlias, $items);
                    } 
                    break;
                case 'like': 
                    foreach($clauseData as $columnAlias=>$term){
                        $this->db->like($columnAlias, $term);
                    } 
                    break;
                case 'or_like': 
                    foreach($clauseData as $columnAlias=>$term){
                        $this->db->or_like($columnAlias, $term);
                    } 
                    break;
                case 'before_like': 
                    foreach($clauseData as $columnAlias=>$term){
                        $this->db->like($columnAlias, $term, 'before');
                    }
                    break;
                case 'after_like': 
                    foreach($clauseData as $columnAlias=>$term){
                        $this->db->like($columnAlias, $term, 'after');
                    } 
                    break;
            }
        }
        
        $resultsCount = $this->db->count_all_results($this::DB_TABLE);

        return $resultsCount;
    }
    
    /**
    * take param array and make appropriate array for clauses
    * @access public
    * @param array $params
    * @return void
    */
    public function format_clause_data($params){

        $this->_clauses['select'] = '';
        // add select columns - use column aliases in param array if passed
        if(isset($params['select']) && is_array($params['select'])){
            // add always select fields
            foreach($this->_always_select as $columnAlias){
                if(key_exists($columnAlias, $this->_columnMap)){
                    $this->_clauses['select'] .= $this->_columnMap[$columnAlias] . ' AS ' . $columnAlias . ',';
                }
            }
            foreach($params['select'] as $columnAlias){
                // add select fields, skip if in always select array
                if(key_exists($columnAlias, $this->_columnMap) && !in_array($columnAlias, $this->_always_select)){
                    $this->_clauses['select'] .= $this->_columnMap[$columnAlias] . ' AS ' . $columnAlias . ',';
                }
            }
        } else {
            // include all columns in columnMap
            if(isset($this->_columnMap)){
                foreach($this->_columnMap as $alias=>$columnName){
                    $this->_clauses['select'] .= $columnName . ' AS ' . $alias . ',';
                }
            }
        }
        $this->_clauses['select'] = rtrim($this->_clauses['select'], ',');
        
        // add join tables and conditions
        if(isset($this->_join)){
            foreach($this->_join as $join_table_name => $join_condition_string){
                $this->_clauses['join'][$join_table_name] = $join_condition_string;
            }
        }
        
        // add always where statement if set
        if(count($this->_always_where) > 0){
            foreach($this->_always_where as $columnAlias=>$value){
                if(key_exists($columnAlias, $this->_columnMap)){
                    if(strpos($value, '>= ') === 0){
                        $this->_clauses['where'][$this->_columnMap[$columnAlias] . ' >='] = ltrim($value, '>= ');
                    } elseif(strpos($value, '<= ') === 0){
                        $this->_clauses['where'][$this->_columnMap[$columnAlias] . ' <='] = ltrim($value, '<= ');
                    } else {
                        $this->_clauses['where'][$this->_columnMap[$columnAlias]] = $value;
                    }
                }
            }
        }
        
        // add always where_in statement if set
        if(count($this->_always_where_in) > 0){
            foreach($this->_always_where_in as $columnAlias=>$values){
                if(key_exists($columnAlias, $this->_columnMap)){
                    $this->_clauses['where_in'][$this->_columnMap[$columnAlias]] = $values;
                }
            }
        }
        
        // add _always_group_by statement if set
        if(count($this->_always_group_by) > 0){
            $this->_clauses['group_by'] = '';
            foreach($this->_always_group_by as $columnAlias){
                if(key_exists($columnAlias, $this->_columnMap)){
                    $this->_clauses['group_by'] .= $columnAlias . ',';
                }
            }
            $this->_clauses['group_by'] = rtrim($this->_clauses['group_by'], ',');
        }

        // loop params
        if(is_array($params) && count($params) >= 1){
            foreach($params as $clause => $clauseValues){
                // $data should always be an array, if not move on to the next
                if(!is_array($clauseValues)){
                    continue;
                }
                // add statements as needed
                switch($clause){
                    case 'where': 
                        foreach($clauseValues as $columnAlias=>$value){
                            if(key_exists($columnAlias, $this->_columnMap)){
                                if(strpos($value, '>= ') === 0){
                                    $this->_clauses['where'][$this->_columnMap[$columnAlias] . ' >='] = ltrim($value, '>= ');
                                } elseif(strpos($value, '<= ') === 0){
                                    $this->_clauses['where'][$this->_columnMap[$columnAlias] . ' <='] = ltrim($value, '<= ');
                                } else {
                                    $this->_clauses['where'][$this->_columnMap[$columnAlias]] = $value;
                                }
                            }
                        }                        
                        break;
                    case 'or_where': 
                        foreach($clauseValues as $columnAlias=>$value){
                            if(key_exists($columnAlias, $this->_columnMap)){
                                $this->_clauses['or_where'][$this->_columnMap[$columnAlias]] = $value;
                            }
                        }                        
                        break;
                    case 'custom_where': 
                        $this->_clauses['custom_where'] = $clauseValues;
                        break;
                    case 'where_in': // values should be an array
                        foreach($clauseValues as $columnAlias=>$values){
                            if(key_exists($columnAlias, $this->_columnMap)){
                                if(is_array($values)){
                                    $this->_clauses['where_in'][$this->_columnMap[$columnAlias]] = $values;
                                }
                            }
                        }
                        break;
                    case 'like': 
                        foreach($clauseValues as $columnAlias=>$term){
                            if(key_exists($columnAlias, $this->_columnMap)){
                                $this->_clauses['like'][$this->_columnMap[$columnAlias]] = $term;
                            }
                        }
                        break;
                    case 'or_like': 
                        foreach($clauseValues as $columnAlias=>$term){
                            if(key_exists($columnAlias, $this->_columnMap)){
                                $this->_clauses['or_like'][$this->_columnMap[$columnAlias]] = $term;
                            }
                        }
                        break;
                    case 'before_like': 
                        foreach($clauseValues as $columnAlias=>$term){
                            if(key_exists($columnAlias, $this->_columnMap)){
                                $this->_clauses['before_like'][$this->_columnMap[$columnAlias]] = $term;
                            }
                        }
                        break;
                    case 'after_like': 
                        foreach($clauseValues as $columnAlias=>$term){
                            if(key_exists($columnAlias, $this->_columnMap)){
                                $this->_clauses['after_like'][$this->_columnMap[$columnAlias]] = $term;
                            }
                        }
                        break;
                    case 'date_range': 
                        // all incoming dates should be unixtime, we'll convert to the proper db format here
                        foreach($clauseValues as $columnAlias=>$dateValues){
                            if(key_exists($columnAlias, $this->_date_columns)){
                                // $dateValues should always be an array with a start_date and end_date
                                if(is_array($dateValues) && key_exists('start_date', $dateValues) && key_exists('end_date', $dateValues)){

                                    if(is_int($dateValues['start_date'])){
                                        // convert if needed
                                        switch($this->_date_columns[$columnAlias]['format']){
                                            case 'mysql_timestamp': 
                                            case 'mysql_datetime': $startDate = date('Y-m-d H:i:s', $dateValues['start_date']);
                                                break;
                                            case 'mysql_date': $startDate = date('Y-m-d', $dateValues['start_date']);
                                                break;
                                            case 'mysql_year': $startDate = date('Y', $dateValues['start_date']);
                                                break;
                                            case 'mysql_time': $startDate = date('H:i:s', $dateValues['start_date']);
                                                break;
                                            case 'unixtime': 
                                            default: $startDate = $dateValues['start_date'];
                                        }
                                        $this->_clauses['where'][$this->_date_columns[$columnAlias]['column'] . ' >= '] = $startDate;
                                    }
                                    
                                    if(is_int($dateValues['end_date'])){
                                        // convert if needed
                                        switch($this->_date_columns[$columnAlias]['format']){
                                            case 'mysql_timestamp': 
                                            case 'mysql_datetime': $endDate = date('Y-m-d H:i:s', $dateValues['end_date']);
                                                break;
                                            case 'mysql_date': $endDate = date('Y-m-d', $dateValues['end_date']);
                                                break;
                                            case 'mysql_year': $endDate = date('Y', $dateValues['end_date']);
                                                break;
                                            case 'mysql_time': $endDate = date('H:i:s', $dateValues['end_date']);
                                                break;
                                            case 'unixtime': 
                                            default: $endDate = $dateValues['end_date'];
                                        }
                                        $this->_clauses['where'][$this->_date_columns[$columnAlias]['column'] . ' <= '] = $endDate;
                                    }

                                }
                            }
                        }
                        break;
                    case 'order_by': 
                        foreach($clauseValues as $columnAlias=>$direction){
                            if(key_exists($columnAlias, $this->_columnMap) && ($direction === 'asc' || $direction === 'desc')){
                                $this->_clauses['order_by'][$this->_columnMap[$columnAlias]] = $direction;
                            }
                        }
                        break;
                    case 'group_by': 
                        foreach($clauseValues as $columnAlias){
                            if(key_exists($columnAlias, $this->_columnMap)){
                                $this->_clauses['group_by'] = $columnAlias;
                            }
                        }                        
                        break;
                }
            }
            
        }

    }
    
}