<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Neoseo_channel_rmdl extends Neoseo_rmdl_abstract {

    const DB_TABLE                      = 'exp_neoseo_channels';
    const DB_TABLE_PK                   = 'channel_id';
    const DB_TABLE_RECORD_CREATED_KEY   = 'created_at';
    const DB_TABLE_RECORD_MODIFIED_KEY  = 'updated_at';

    public function __construct() {
        parent::__construct();
    }

}