<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Ctrt_ri_export_entry_model extends Ctrt_ri_vmdl_abstract {
                     
    public function __construct() {
    }
    
    /**
    * @access public
    * @return array
    */
    public function get_data(){
/*
        $rows = array();
        
        $provider_columns = array(
                                'provider_id'           => 'exp_ctrt_ri_provider.provider_id',
                                'display_created_at'    => 'CASE
                                                                WHEN exp_ctrt_ri_provider.created_at = "00:00:0000 00:00:00" THEN NULL
                                                                WHEN exp_ctrt_ri_provider.created_at = NULL THEN NULL
                                                                ELSE DATE_FORMAT(exp_ctrt_ri_provider.created_at, "%c-%e-%Y, %l:%i %p")
                                                            END',
                                'display_updated_at'    => 'CASE
                                                                WHEN exp_ctrt_ri_provider.updated_at = "00:00:0000 00:00:00" THEN NULL
                                                                WHEN exp_ctrt_ri_provider.updated_at = NULL THEN NULL
                                                                ELSE DATE_FORMAT(exp_ctrt_ri_provider.updated_at, "%c-%e-%Y, %l:%i %p")
                                                            END',
                                'provider_first_name'   => 'exp_ctrt_ri_provider.provider_first_name',
                                'provider_last_name'    => 'exp_ctrt_ri_provider.provider_last_name',
                                'provider_full_name'    => 'CONCAT(exp_ctrt_ri_provider.provider_first_name, " ", exp_ctrt_ri_provider.provider_last_name)',
                                'provider_number'       => 'exp_ctrt_ri_provider.provider_number',
                                'project_id'            => 'exp_ctrt_ri_provider.project_id',
                                'program_name'          => 'exp_ctrt_ri_project.program_name',
                                'project_id_number' => 'exp_ctrt_ri_project.project_id_number',
                        );

        $provider_join = array(
                            'exp_ctrt_ri_project'  => 'exp_ctrt_ri_provider.project_id = exp_ctrt_ri_project.project_id',
                            );
         
        
        // start constructing export row template
        $RowTemplate = new stdClass();
        foreach($provider_columns as $columnAlias=>$column){
            $RowTemplate->$columnAlias = '';
        }
        
        // csv data rows
        $data = '';
        
        // create the header rows and add to return array
        $line = '';
        foreach($RowTemplate as $key=>$val){
            $line .= '"' . str_replace('"', '""', $key) . '",';
        }
        $data .= rtrim($line, ",") . "\n";

        // get projects
        foreach($provider_columns as $alias=>$select){
            ee()->db->select($select . ' AS ' . $alias, FALSE);
        }
        foreach($provider_join as $table=>$condition){
            ee()->db->join($table, $condition, 'left');
        }
        $query = ee()->db->get('exp_ctrt_ri_provider');
        $providers = $query->result();

        foreach($providers as $key=>$provider){
            
            // add project to row
            $Row = clone $RowTemplate;
            foreach($provider as $key=>$value){
                if(isset($Row->$key)){
                    $Row->$key = $value;
                }
            }
            
            // add row to data
            // new line
            $line = '';
            foreach($Row as $cell){
                $line .= '"' . str_replace('"', '""', $cell) . '",';
            }
            $data .= rtrim($line, ",") . "\n";
            unset($Row);
        }

        return $data;
        */
    }
    
}
