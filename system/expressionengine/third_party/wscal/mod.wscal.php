<?php
if (!defined('BASEPATH'))
	exit ('No direct script access allowed');

/**
 *
 * @package     ExpressionEngine
 * @category    Module
 * @author      Greg Crane
 * @link        http://ghcrane.com
 *
 */
class Wscal {

	const MOD_NAME = 'wscal';
    
	private $_returnData = '';
    
    private $_categoryBranch = array();

    private $_abstracts = array(
                                'Wscal_rmdl_abstract',
                                'Wscal_vmdl_abstract',
                                'Wscal_entity_abstract'
                                );

    // for loading module files
    private $_modFiles = array(
                                'models'    => array(),
                                'libraries' => array(
                                                    'pagination',
                                                    'table',
                                                    'api',
                                                    'email',
						                            'form_validation',
                                                    'Wscal_notifications',
                                                    'Wscal_data_helper',
                                                    'Wscal_form_helper',
                                                    'Wscal_ui',
                                                    'Wscal_blog',
                                                    'Wscal_news_events',
                                                    'Wscal_resources'
                                                    ),
                                'helpers'   => array('text','date','form','security','url','html5_form_helper'),
                                'lang'      => array('wscal')
                                );

	/**
	 * @access public
	 * @return void
	 */
	public function __construct() {
        
        ee()->config->load('config');
        
        require_once(BASEPATH . '/core/Model.php');

        // load abstract classes
        foreach($this->_abstracts as $abstract) {        
            require_once(realpath(dirname(__FILE__)) . '/abstracts/' . $abstract . '.php');
        }

        // load module files
        foreach($this->_modFiles as $fileType=>$files) {
            foreach($files as $file) {
                switch($fileType){
                    case 'helpers':
                        ee()->load->helper($file);
                        break;
                    case 'lang':
                        ee()->lang->loadfile($file);
                        break;
                    case 'models':
                        ee()->load->model($file);
                        break;
                    case 'libraries':
                        ee()->load->library($file);
                        break;
                }
            }
        }

	}
    
    /**
     * blog_archive_years
     * @access public
     * @return array
     * @author Greg Crane
     */
    public function blog_archive_years(){
        $data = ee()->wscal_blog->get_blog_archive_years();
        return ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $data);
    }
    
    /**
     * blog_contributors
     * @access public
     * @return array
     * @author Greg Crane
     */
    public function blog_contributors(){
        $data = ee()->wscal_blog->get_blog_contributors();
        return ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $data);
    }
    
    /**
     * news_events_archive_years
     * @access public
     * @return array
     * @author Greg Crane
     */
    public function news_events_archive_years(){
        $data = ee()->wscal_news_events->get_news_events_archive_years();
        return ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $data);
    }
    
    /**
     * resource years
     * @access public
     * @return array
     * @author Greg Crane
     */
    public function resource_years(){
        $data = ee()->wscal_resources->get_resource_years();
        return ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $data);
    }
    
    /**
     * resource authors
     * @access public
     * @return array
     * @author Greg Crane
     */
    public function resource_authors(){
        $entry_id = ee()->TMPL->fetch_param('entry_id');
        $data = ee()->wscal_resources->get_resource_authors($entry_id);
        return ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $data);
    }
    
    /**
     * resource scriptures
     * @access public
     * @return array
     * @author Greg Crane
     */
    public function resource_scriptures(){
        $entry_id = ee()->TMPL->fetch_param('entry_id');
        $data = ee()->wscal_resources->get_resource_scriptures($entry_id);
        return ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $data);
    }

     /**
     * return a category id from a cat_url_title
     * @access public
     * @return mixed
     * @author Greg Crane
     */
    public function get_catid_from_url_title(){

        $cat_url_title = ee()->TMPL->fetch_param('cat_url_title');
        $group_id = ee()->TMPL->fetch_param('group_id');
        
        ee()->db->select('cat_id');
        ee()->db->where('cat_url_title',$cat_url_title);
        ee()->db->where('group_id',$group_id);
        $query = ee()->db->get('exp_categories');
        $result = $query->row();
        if($query->num_rows() > 0){
            $result = $query->row();
            return $result->cat_id;
        }
        return '';
    }
    
    /**
     * has personal pages
     * @access public
     * @return array
     * @author Greg Crane
     */
    public function has_personal_pages(){
        $entry_id = ee()->TMPL->fetch_param('author_id');
        $data = ee()->wscal_resources->get_resource_authors($entry_id);
        return ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $data);
    }
    
    
    
    
    
    
    /**
     * category branch
     * @access public
     * @return array
     * @author Greg Crane
     */
    public function get_category_branch(){
        
        $parent_category_id = ee()->TMPL->fetch_param('parent_cat_id');
        $group_id           = ee()->TMPL->fetch_param('group_id');
       
        $this->_get_category_branch($parent_category_id, $group_id);
 
echo '<br/><br/><pre>';
echo print_r($this->_categoryBranch,TRUE);
echo '</pre><br/><br/>';
die('dead');

        return ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $data);
    }
    
    private function _get_category_branch($parent_category_id = 0, $group_id = 0) {

        ee()->db->select('cat_name, cat_url_title, cat_id, parent_id');
        ee()->db->where('parent_id', $parent_category_id);
        ee()->db->where('group_id', $group_id);
        ee()->db->order_by('cat_order', 'asc');
        $query = ee()->db->get('exp_categories');

        if($query->num_rows() > 0){
            foreach($query->result() as $Row){
                $iterator  = new RecursiveArrayIterator($this->_categoryBranch);
                $recursive = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
                foreach($recursive as $key => $value) {
                    if($key !== $Row->parent_id) {
                        $recursive[$Row->parent_id] = $Row->cat_id;
                    }
                }
            }
            $this->_get_category_branch($Row->cat_id, $group_id);
        }
   


    }

}