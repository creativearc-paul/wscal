<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Wscal_clients_rmdl extends Wscal_rmdl_abstract {

    const DB_TABLE                      = 'exp_wscal_clients';
    const DB_TABLE_PK                   = 'id';
    const DB_TABLE_RECORD_CREATED_KEY   = 'created_at';
    const DB_TABLE_RECORD_MODIFIED_KEY  = 'updated_at';

    public function __construct() {
        parent::__construct();
    }

}