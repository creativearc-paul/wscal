<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

///////// REQUIREMENTS /////////////////
// LIBRARIES get_settings, locales, package_installer
// MODELS field_model, channel_model, generic_model, template_model, crud_model, packages_field_model, packages_entries_model, table_model
// HELPERS  security, form, string, data_formatting, file

// imagess: ct_drag_handle.gif

if (! class_exists('Mbr_addon_builder'))
{
	class Mbr_addon_builder
	{
		public $module_name = NULL; 
		public $settings = array(); 
		public $module_enabled = NULL; 
		public $extension_enabled = NULL; 
		public $no_form = array(); 
		public $no_nav = array(); 
		public $version = 1; 
		public $nav = array();

		private $remove_keys = array(
			'name',
			'submit',
			'x',
			'y',
			'templates',
			'CSRF_TOKEN',
			'XID'
		);		
		
		public $tables = array(
		); 
		public $mod_actions = array(
		);
		public $mcp_actions = array(
		);
		public $fieldtypes = array(
		);
		public $hooks = array(
	 	);
		public $notification_events = array(
		);
		public $cartthrob, $store, $cart;
		
		public $drag_handle; 
		public function __construct()
		{
			$this->EE =& get_instance();
			
			if ($this->get_cartthrob_settings())
			{
				$this->drag_handle = $this->EE->config->item('theme_folder_url').'third_party/cartthrob/images/ct_drag_handle.gif'; 
			}
			else
			{
				$this->drag_handle = $this->EE->config->item('theme_folder_url').'third_party/'.$this->module_name.'/images/ct_drag_handle.gif'; 
			}
			
 		}
		public function initialize($params = array())
		{
			if (empty($params)) $params = array(); 
			
			foreach ($params as $key=>$value)
			{
				$this->$key = $value; 
			}
			if (!empty($params['module_name']))
			{
				$this->module_name = $params['module_name'];
				unset($params['module_name']); 
			}
			else
			{
				$trace = debug_backtrace();
				$caller = array_shift($trace);
				if (isset($caller['class']))
				{
					$this->module_name = $caller['class']; 
				}
			}
 
			$this->EE->load->library('get_settings');
			$this->EE->load->library('table'); 
			$this->EE->load->library('locales');
			
			$this->settings = $this->EE->get_settings->settings($this->module_name);
			
			if ($this->get_cartthrob_settings() )
			{
				$this->EE->load->add_package_path(PATH_THIRD.'cartthrob/'); 
			}
			$this->EE->load->add_package_path(PATH_THIRD.$this->module_name."/"); 

			$this->EE->load->helper(array('security',  'form',  'string', 'data_formatting'));
			$this->EE->load->model(array('field_model', 'channel_model', 'generic_model', 'template_model'));

			$this->EE->lang->loadfile($this->module_name, $this->module_name);

			$this->module_enabled = TRUE; 
			$this->extension_enabled = TRUE; 

			if (empty($params['skip_module']))
			{
				$this->module_enabled = (bool) $this->EE->db->where('module_name', ucwords($this->module_name))->count_all_results('modules');
			}
			if (empty($params['skip_extension']))
			{
				$this->extension_enabled = (bool) $this->EE->db->where(array('class' => ucwords($this->module_name).'_ext', 'enabled' => 'y'))->count_all_results('extensions');
			}
		}
 		//////////////////////////////////////////
		//////// MCP Functions
		//////////////////////////////////////////
		public function  load_view($current_nav, $more = array(), $structure = array())
		{
			$this->EE->cp->cp_page_title = $this->EE->lang->line($this->module_name.'_module_name').' - '.$this->EE->lang->line('nav_head_'.$current_nav);

			$vars = array();

	 		$nav = $this->nav;

			
			$settings_views = array();

			$view_paths = array();

			/*
			// this currently doesn't do anything since settigns_views is blank
			if (is_array($settings_views) && count($settings_views))
			{
				foreach ($settings_views as $key => $value)
				{
					if (is_array($value))
					{
						if (isset($value['path']))
						{
							$view_paths[$key] = $value['path'];
						}

						if (isset($value['title']))
						{
							$nav['more_settings'][$key] = $value['title'];
						}
					}
					else
					{
						$nav['more_settings'][$key] = $value;
					}
				}
			}
			*/ 

			$sections = array();

			foreach ($nav as $top_nav => $subnav)
			{
				if ($top_nav != $current_nav)
				{
					continue;
				}

				foreach ($subnav as $url_title => $section)
				{
					if ( ! preg_match('/^http/', $url_title))
					{
						$sections[] = $url_title;
					}
				}
			}
			// we need to get CartThrob's settings for this too. DON'T CHANGE THIS. It's not a mistake. 
	 		$settings =  array_merge((array) $this->settings,$this->get_cartthrob_settings()); 

			$channels = $this->EE->channel_model->get_channels()->result_array();

			$fields = array();
			$channel_titles = array();
			$statuses = array(); 
			$product_channel_titles = array();
			$product_channel_fields = array(); 
			$order_channel_fields = array(); 
			// @TODO remove this stuff from this generic function so it can be used without CT. 
			$product_channels = array();
			$order_channel = array(); 
			if ($this->get_cartthrob_settings())
			{
				$product_channels = $this->EE->cartthrob->store->config('product_channels'); 
				$order_channel = $this->EE->cartthrob->store->config('orders_channel'); 
			}
			foreach ($channels as $channel)
			{
				// get all channels and fields
				$channel_titles[$channel['channel_id']] = $channel['channel_title'];
				$channel_fields = $this->EE->field_model->get_fields($channel['field_group'])->result_array(); 
				foreach ($channel_fields as $key => &$data)
				{
					$fields[$channel['channel_id']][$key] = array_intersect_key($data, array_fill_keys(array('field_id', 'site_id', 'group_id', 'field_name', 'field_type', 'field_label'), TRUE));
				}
				$statuses[$channel['channel_id']] = $this->EE->channel_model->get_channel_statuses($channel['status_group'])->result_array();
			}

			foreach ($channels as $channel)
			{
				// @TODO fill product and order channel fields from already populated 
				// get product channels and fields
				if (in_array($channel['channel_id'], $product_channels))
				{
					$product_channel_titles[$channel['channel_id']] = $channel['channel_title'];
					$product_channel_fields[$channel['channel_id']] = $fields[$channel['channel_id']]; 
				}
				// order channel fields
				if ($channel['channel_id'] == $order_channel)
				{
					$order_channel_fields[$channel['channel_id']] = $fields[$channel['channel_id']]; 
				}
			}
			$status_titles = array(); 
			foreach ($statuses as $status)
			{
				foreach ($status as $item)
				{
					$status_titles[$item['status']] = $item['status']; 
				}
			}
			$member_fields = array('' => '----');
			
			foreach ($this->EE->member_model->get_all_member_fields(array(), FALSE)->result() as $row)
			{
				$member_fields[$row->m_field_id] = $row->m_field_label;
			}
			$data = array(
				'structure'	=> $structure, 
				'nav' => $nav,
				'channels' => $channels,
				'channel_titles' => $channel_titles,
				'fields' => $fields,
				'statuses' => $statuses,
				'status_titles' => $status_titles,
				'product_channel_titles'	=> $product_channel_titles,
				'product_channel_fields'	=> $product_channel_fields,
				'order_channel_fields'		=> $order_channel_fields,
				'settings_mcp' => $this,
				'sections' => $sections,
				'view_paths' => $view_paths,
				'module_name'	=> $this->module_name,
				$this->module_name.'_tab' => (isset($this->settings[$this->module_name.'_tab'])) ? $this->settings[$this->module_name.'_tab'] : 'system_settings',
				$this->module_name.'_mcp' => $this,
				'form_open' => form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name.AMP.'method=quick_save'.AMP.'return='.$this->EE->input->get('method', "index")),
				'extension_enabled' => $this->extension_enabled,
				'module_enabled' => $this->module_enabled,
				'settings' => $settings,
				'no_form' => (in_array($current_nav, $this->no_form)),
				'no_nav'	=>  $this->no_nav, 
				'states_and_countries' => array_merge(array(""=> '---', 'global' => 'Global', 'non-continental_us'=> 'Non-Continental US' , 'europe'=> 'Europe', 'european_union' => 'European Union'), $this->EE->locales->states(), array('0' => '---'), $this->EE->locales->all_countries()),
				'states' => $this->EE->locales->states(),
				'countries' => array_merge(array(""=> '---','global' => 'Global', 'non-continental_us'=> 'Non-Continental US' , 'europe'=> 'Europe', 'european_union' => 'European Union'), $this->EE->locales->all_countries()),
				'templates' =>  $this->get_templates(),
				'member_fields'	=> $member_fields,
				
			);

			if (!empty($structure))
			{
				$data['html'] = $this->view_settings_template($data);
			}

	 		$data = array_merge($data, $more);

			$self = $data;

			$data['data'] = $self;

			unset($self);

			$this->EE->cp->add_js_script('ui', 'accordion');

			if (@file_exists($this->EE->config->item('theme_folder_url').'third_party/'.$this->module_name.'/css/'.$this->module_name.'.css'))
			{
				$css = '<link href="'.URL_THIRD_THEMES.$this->module_name.'/css/'.$this->module_name.'.css" rel="stylesheet" type="text/css" media="screen" />'; 
			}
			else
			{
				$css =  $this->default_css(); 
			}
			
			$this->EE->cp->add_to_head($css);
			
			$this->EE->cp->add_to_foot($this->view_settings_form_head($data) );

			return $this->view_settings_form($data); 
		}
		
		public function form_update($database_table = NULL)
		{
			if ($database_table)
			{
				$table = $database_table;
			}
			else
			{
				$table = $this->module_name. "_options"; 
			}
			$model = new Generic_model($table);

			if ( $this->EE->input->post('delete'))
			{
				if ($database_table)
				{
					if ( $this->EE->input->post('id') )
					{
						$this->EE->db->delete($database_table, array('id' => $this->EE->input->post('id')));
					}
				}
				else
				{
					// even though we have a model in play, for safety's sake, I'm goig to use EE's delete method for entries. 
					if ( $this->EE->input->post('id') )
					{
						$this->EE->load->library('api');
						$this->EE->api->instantiate('channel_entries');
						$this->EE->api_channel_entries->delete_entry(  $this->EE->input->post('id')  );
					}
				}
				
				$this->EE->session->set_flashdata($this->module_name.'_system_message', sprintf('%s', lang($this->module_name.'_deleted')));
			}
			else
			{
				foreach (array_keys($_POST) as $key)
				{
					if ( ! in_array($key, $this->remove_keys) && ! preg_match('/^('.ucwords($this->module_name).'_.*?_settings)_.*/', $key))
					{
						$data[$key] = $this->EE->input->post($key, TRUE);
					}
				}

				if (isset($data["sub_settings"]["data"]))
				{
					$data['data'] = serialize($data["sub_settings"]["data"]); 
				}

	 			if (!$this->EE->input->post('id'))
				{
	 				$model->create($data);
				}
				else
				{
					if ( $this->EE->input->post('id') && !empty($data))
					{
						$model->update($this->EE->input->post('id'), $data);
					}

				}
				$this->EE->session->set_flashdata($this->module_name.'_system_message', sprintf('%s',lang($this->module_name.'_updated')));
			}
			$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name.AMP.'method='.$this->EE->input->get('return', TRUE));
		} // END form_update
		public function pagination_config($method, $total_rows, $per_page=50)
		{
			$config['base_url'] = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name.AMP.'method='.$method;
			$config['total_rows'] = $total_rows;
			$config['per_page'] = $per_page;
			$config['page_query_string'] = TRUE;
			$config['query_string_segment'] = 'rownum';
			$config['full_tag_open'] = '<p id="paginationLinks">';
			$config['full_tag_close'] = '</p>';
			$config['prev_link'] = '<img src="'.$this->EE->cp->cp_theme_url.'images/pagination_prev_button.gif" width="13" height="13" alt="<" />';
			$config['next_link'] = '<img src="'.$this->EE->cp->cp_theme_url.'images/pagination_next_button.gif" width="13" height="13" alt=">" />';
			$config['first_link'] = '<img src="'.$this->EE->cp->cp_theme_url.'images/pagination_first_button.gif" width="13" height="13" alt="< <" />';
			$config['last_link'] = '<img src="'.$this->EE->cp->cp_theme_url.'images/pagination_last_button.gif" width="13" height="13" alt="> >" />';

			return $config;
		}
		
		// $method is the redirect module method. If it's not set, the module name will be used instead 
		public function get_pagination($table, $limit, $offset = NULL, $method = NULL)
		{
			if ( ! $offset )
			{		
				$offset = $this->EE->input->get_post('rownum');
			}
			$this->EE->load->library('pagination');
			$total = $this->EE->db->count_all($table);
			if ($total == 0)
			{
				return FALSE; 
			}
			
			if (! $method)
			{
				$method = $this->module_name;
			}
			$this->EE->pagination->initialize( $this->pagination_config($method, $total, $limit) );

			return $this->EE->pagination->create_links();
		}
		
		/**
		 * saves everything in POST to a like named setting in the DB (if found)
		 * 
		 *
		 * @param bool $set_success_message if FALSE, a success message will not be set (in case you want to roll your own.) Otherwise says something like "saved"
		 * 
		 * GET variables: 
		 * return location to return to. 
		 * $this->module_name.'_tab' adds a tab to return to (adds: #YOUR_SPECIFIED_TAB to the return value): return=method#some_tab
		 * 
		 * @return void
		 * @author Chris Newton
		 */
		public function quick_save($set_success_message = TRUE)
		{
			$this->EE->load->library('get_settings');

			$db_settings = $this->EE->get_settings->settings($this->module_name,$saved_settings = TRUE);	

			$data = array();

			foreach (array_keys($_POST) as $key)
			{
				if ( ! in_array($key, $this->remove_keys) && ! preg_match('/^('.ucwords($this->module_name).'_.*?_settings)_.*/', $key))
				{
					$data[$key] = $this->EE->input->post($key, TRUE);
				}
			}
	 		foreach ($data as $key => $value)
			{
				$where = array(
					'site_id' => $this->EE->config->item('site_id'),
					'`key`' => $key
				);

				// custom key actions
				switch($key)
				{
					case 'cp_menu':

						$is_installed = (bool) $this->EE->db->where('class', ucwords($this->module_name)."_ext")->where('hook', 'cp_menu_array')->count_all_results('extensions');

						if ($value && $value="yes")
						{
							if ( ! $is_installed)
							{
								$this->EE->db->insert('extensions', array(
									'class' => ucwords($this->module_name)."_ext", 
									'method' => 'cp_menu_array',
									'hook' => 'cp_menu_array', 
									'settings' => '', 
									'priority' => 10, 
									'version' => $this->version(),
									'enabled' => 'y',
								));
							}
						}
						else
						{
							if ($is_installed)
							{
								$this->EE->db->where('class', ucwords($this->module_name)."_ext")->where('hook', 'cp_menu_array')->delete('extensions');
							}
						}

						break;
				}
				
				if (is_array($value))
				{
					$row['serialized'] = 1;
					$row['value'] = serialize($value);
				}
				else
				{
					$row['serialized'] = 0;
					$row['value'] = $value;
				}
				if (isset($db_settings[$key]))
				{
					if ($value !== $db_settings[$key])
					{
						$this->EE->db->update($this->module_name.'_settings', $row, $where);
					}
				}
				else
				{
	 				$this->EE->db->insert($this->module_name.'_settings', array_merge($row, $where));
	 			}
			}


			if ($set_success_message)
			{
				$this->EE->session->set_flashdata('message_success', sprintf('%s', lang('settings_saved')));
			}

			$return = ($this->EE->input->get('return')) ? AMP.'method='.$this->EE->input->get('return', TRUE) : '';

			if ($this->EE->input->post($this->module_name.'_tab'))
			{
				$return .= '#'.$this->EE->input->post($this->module_name.'_tab', TRUE);
			}

			$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name.$return);
		}
		
		public function get_cartthrob_settings()
		{
			$this->EE->load->library('get_settings');
			
			if ( (bool) $this->EE->db->where('module_name', 'Cartthrob')->count_all_results('modules'))
			{
				return $this->EE->get_settings->settings("cartthrob");
			}

			return array(); 
		}
		// @TODO deprecate
		public function channel_dropdown_js()
		{
			
			
			$this->EE->load->model(array('channel_model', 'field_model'));
			
			$this->EE->load->library('javascript');
			
			$data = array(
				'channels' => array(),
				'fields' => array(),
				'statuses' => array(),
			);
			
			$statuses = array();
			
			$query = $this->EE->channel_model->get_channels();
			
			foreach ($query->result() as $channel)
			{
				$data['channels'][$channel->channel_id] = $channel->channel_title;
				
				// $fields[$channel['channel_id']] = $this->EE->field_model->get_fields($channel['field_group'])->result_array();
				// only want to capture a subset of data, because we're using this for JSON and we were getting too much data previously
				$field_query = $this->EE->field_model->get_fields($channel->field_group);
				
				$data['fields'][$channel->channel_id] = array();
				
				foreach ($field_query->result_array() as $fields)
				{
					$data['fields'][$channel->channel_id][] = array_intersect_key($fields, array_fill_keys(array('field_id', 'site_id', 'group_id', 'field_name', 'field_type', 'field_label'), TRUE));
				}
				
				$statuses[$channel->channel_id] = $this->EE->channel_model->get_channel_statuses($channel->status_group)->result_array();
			}
			
			foreach ($statuses as $status)
			{
				foreach ($status as $item)
				{
					$data['statuses'][$item['status']] = $item['status']; 
				}
			}
			
			$this->EE->cp->add_to_foot('
			<script type="text/javascript">
			(function() {
				window.channelDropdown = '.json_encode($data).';
				channelDropdown.updateSelect = function(select, options) {
					var val = $(select).val();
					var attrs = {};
					for (i=0;i<select.attributes.length;i++) {
						if (select.attributes[i].name == "value") {
							val = select.attributes[i].value;
						} else {
							attrs[select.attributes[i].name] = select.attributes[i].value;
						}
					}
					$(select).replaceWith(channelDropdown.createSelect(attrs, options, val));
				};
				channelDropdown.createSelect = function(attributes, options, selected) {
					var select = "<select ";
					for (i in attributes) {
						select += i+\'="\'+attributes[i]+\'" \';
					}
					select += ">";
					for (i in options) {
						select += \'<option value="\'+i+\'"\';
						if (selected != undefined && selected == i) {
							select += \' selected="selected"\';
						}
						select += ">"+options[i]+"</option>";
					}
					select += "</select>";
					return select;
				};
			})();
			</script>');
			
			$this->EE->javascript->output('
			$("select.statuses").each(function(){
				channelDropdown.updateSelect(this, channelDropdown.statuses);
			});
			$("select.statuses_blank").each(function(){
				var statuses = {"" : "---", "ANY" : "ANY"};
				$.extend(statuses, channelDropdown.statuses);
				channelDropdown.updateSelect(this, statuses);
			});
			$("select.all_fields").each(function(){
				var fields = {"":"---"};
				for (i in channelDropdown.fields) {
					for (j in channelDropdown.fields[i]) {
						fields["field_id_"+channelDropdown.fields[i][j].field_id] = channelDropdown.fields[i][j].field_label;
					}
				}
				channelDropdown.updateSelect(this, fields);
			});
			$("select.channels").each(function(){
				channelDropdown.updateSelect(this, channelDropdown.channels);
			});
			$("select.channels").bind("change", function(e){
				var channel_id = Number($(e.target).val());
				var section = $(e.target).attr("id").replace("select_", "");
				$("select.field_"+section).children().filter(function(){ return this.value; }).remove();
				$("select.status_"+section).children().filter(function(){ return this.value; }).remove();
				if ($(this).val() != "")
				{
					for (i in channelDropdown.fields[channel_id])
					{
						$("select.field_"+section).append(\'<option value="\'+channelDropdown.fields[channel_id][i].field_id+\'">\'+channelDropdown.fields[channel_id][i].field_label+"</option>");
					}
					for (i in channelDropdown.statuses[channel_id])
					{
						$("select.status_"+section).append(\'<option value="\'+channelDropdown.statuses[channel_id][i].status_id+\'">\'+channelDropdown.statuses[channel_id][i].status+"</option>");
					}
				}
			});
			');
		}
		
		public function get_templates()
		{
			static $templates;

			if (is_null($templates))
			{
				$templates = array();

				$query = $this->EE->template_model->get_templates();

				foreach ($query->result() as $row)
				{
					$templates[$row->group_name.'/'.$row->template_name] = $row->group_name.'/'.$row->template_name;
				}
			}

			return $templates;
		}
		function get_shipping_plugins()
		{
			return $this->get_plugins('shipping');
		}

		function get_tax_plugins()
		{
			return $this->get_plugins('tax');
		}
		
		// --------------------------------
		//  Get Shipping Plugins
		// --------------------------------
		/**
		 * Loads shipping plugin files
		 *
		 * @access private
		 * @param NULL
		 * @return array $plugins Array containing settings and information about the plugin
		 * @since 1.0.0
		 * @author Rob Sanchez
		 */
		function get_plugins($type)
		{
			$this->EE->load->helper(array('file', 'data_formatting'));

			$plugins = array();

			$paths = array(); 
			if (defined("CARTTHROB_PATH"))
			{
				$paths[] = CARTTHROB_PATH.'plugins/'.$type.'/';
				require_once CARTTHROB_PATH.'core/Cartthrob_'.$type.EXT;
				
				
				if ($this->EE->config->item('cartthrob_third_party_path'))
				{
					$paths[] = rtrim($this->EE->config->item('cartthrob_third_party_path'), '/').'/'.$type.'_plugins/';
				}
				else
				{
					$paths[] = PATH_THIRD.'cartthrob/third_party/'.$type.'_plugins/';
				}
			}

			foreach ($paths as $path)
			{
				if ( ! is_dir($path))
				{
					continue;
				}

				foreach (get_filenames($path, TRUE) as $file)
				{
					if ( ! preg_match('/^Cartthrob_/', basename($file, EXT)))
					{
						continue;
					}

					require_once $file;

					$class = basename($file, EXT);

					$language = set($this->EE->session->userdata('language'), $this->EE->input->cookie('language'), $this->EE->config->item('deft_lang'), 'english');			

					if (file_exists(PATH_THIRD.'cartthrob/language/'.$language.'/'.strtolower($class).'_lang.php'))
					{
						$this->EE->lang->loadfile(strtolower($class));
					}
					else if (file_exists($path.'../language/'.$language.'/'.strtolower($class).'_lang.php'))
					{
						$this->EE->lang->load(strtolower($class), $language, FALSE, TRUE, $path.'../');
					}

					$plugin_info = get_class_vars($class);

					$plugin_info['classname'] = $class;

					$plugins[$plugin_info['classname']] = $plugin_info['title'];
				}
			}
	 		return $plugins;
		}
		public function get_member_info($member_id)
		{
			return $this->EE->db->select("*")->where('member_id', $member_id)
	 											->limit(1)
												->get("members")
												->row_array();

		}
		
		
		protected function html($content, $tag = 'p', $attributes = '')
		{
			if (is_array($attributes))
			{
				$attributes = _parse_attributes($attributes);
			}

			return '<'.$tag.$attributes.'>'.$content.'</'.$tag.'>';
		}
		
		// --------------------------------
		//  Plugin Settings
		// --------------------------------
		/**
		 * Creates setting controls
		 * 
		 * @access private
		 * @param string $type text|textarea|radio The type of control that is being output
		 * @param string $name input name of the control option
		 * @param string $current_value the current value stored for this input option
		 * @param array|bool $options array of options that will be output (for radio, else ignored) 
		 * @return string the control's HTML 
		 * @since 1.0.0
		 * @author Rob Sanchez
		 */
		public function plugin_setting($type, $name, $current_value, $options = array(), $attributes = array())
		{
			$output = '';

			if ( ! is_array($options))
			{
				$options = array();
			}
			else
			{
				$new_options = array(); 
				foreach ($options as $key => $value)
				{
					// optgropus
					if (is_array($value))
					{	
						$key = lang($key); 
						foreach ($value as $sub=> $item)
						{
							$new_options[$key][$sub] = lang($item);
						}
					}
					else
					{
						$new_options[$key] = lang($value);
					}
				}
				$options = $new_options; 
			}

			if ( ! is_array($attributes))
			{
				$attributes = array();
			}
	 		switch ($type)
			{
				case 'select':
					if (empty($options)) $attributes['value'] = $current_value;
					$output = form_dropdown($name, $options, $current_value, _attributes_to_string($attributes));
					break;
				case 'file':

					$this->EE->load->library('file_field');
					$trigger = '.choose_file'; 
					if (isset($attributes['trigger']))
					{
						$trigger =  $attributes['trigger']; 
					}
					$config = array(
						'publish'	=> TRUE,
						'trigger'	=> $trigger,
						'field_name' => $name, 
						'function (file, field) { console.log(file, field); }'
					);
 					$this->EE->file_field->browser($config);
					$output = $this->EE->file_field->field($name, $current_value, $allowed_file_dirs = 'all', $content_type = 'image');
 
					#$output .= '<input type="file" name="choose file" class="file_upload" />'; 
					break;
				case 'multiselect':
					$output = form_multiselect($name."[]", $options, $current_value, _attributes_to_string($attributes));
					break;
				case 'checkbox':

					$is_checked = FALSE; 
					if (!empty($attributes['checked']))
					{
						$checked_opt = $attributes['checked']; 
						if (strpos($checked_opt, "not") !== FALSE)
						{
							$is_checked = FALSE;  
						}
						else
						{
							$is_checked = TRUE;
						}
					}
					else
					{
						if (!empty($current_value))
						{
							$is_checked = TRUE; 
						}
					}
					$output = form_label(form_checkbox($name, 1, $is_checked, isset($options['extra']) ? $options['extra'] : '').'&nbsp;'.(!empty($options['label'])? $options['label'] : $this->EE->lang->line('yes') ), $name);
					break;
				case 'text':
					$attributes['name'] = $name;
					$attributes['value'] = $current_value;
					$output =  form_input($attributes);
					break;
				case 'hidden':
 					$output =  form_hidden($name, $current_value);
					break;
				case 'textarea':
					$attributes['name'] = $name;
					$attributes['class'] = 'rte'; 
					$attributes['value'] = $current_value;
					$output =  form_textarea($attributes);
					break;
				case 'radio':
					if (empty($options))
					{
						$output .= form_label(form_radio($name, 1, (bool) $current_value).'&nbsp;'. $this->EE->lang->line('yes'), $name, array('class' => 'radio'));
						$output .= form_label(form_radio($name, 0, (bool) ! $current_value).'&nbsp;'. $this->EE->lang->line('no'), $name, array('class' => 'radio'));
					}
					else
					{
						//if is index array
						if (array_values($options) === $options)
						{
							foreach($options as $option)
							{
								$output .= form_label(form_radio($name, $option, ($current_value === $option)).'&nbsp;'. $option, $name, array('class' => 'radio'));
							}
						}
						//if associative array
						else
						{
							foreach($options as $option => $option_name)
							{
								$output .= form_label(form_radio($name, $option, ($current_value === $option)).'&nbsp;'. lang($option_name), $name, array('class' => 'radio'));
							}
						}
					}
					break;
				default:
			}
			return $output;
		}
		
		
		////////////////////////
		// EXTENSION FUNCTIONS
		////////////////////////
		public function settings_form()
		{
			$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name);
		}
		/**
		 * Activates Extension
		 *
		 * @access public
		 * @param NULL
		 * @return void
		 * @since 1.0.0
		 * @author Rob Sanchez
		 */
		public function activate_extension($hooks = array(), $class=NULL)
		{
			if (!$class)
			{
				$class=$this->module_name."_ext";
			}
			
			foreach ($hooks as $row)
			{
				$this->EE->db->insert(
					'extensions',
					array(
						'class' => $class,
						'method' => $row[0],
						'hook' => ( ! isset($row[1])) ? $row[0] : $row[1],
						'settings' => ( ! isset($row[2])) ? '' : $row[2],
						'priority' => ( ! isset($row[3])) ? 10 : $row[3],
						'version' => $this->version,
						'enabled' => 'y',
					)
				);
			}

			return TRUE;
		}
		// --------------------------------
		//  Update Extension
		// --------------------------------  
		/**
		 * Updates Extension
		 *
		 * @access public
		 * @param string
		 * @return void|BOOLEAN False if the extension is current
		 * @since 1.0.0
		 * @author Rob Sanchez
		 */
		public function update_extension($current='')
		{
			if ($current == '' OR $current == $this->version)
			{
				return FALSE;
			}

			$this->EE->db->update('extensions', array('version' => $this->version), array('class' => $this->module_name));

			return TRUE;
		}
		
		// --------------------------------
		//  Disable Extension
		// --------------------------------
		/**
		 * Disables Extension
		 * 
		 * Deletes mention of this extension from the exp_extensions database table
		 *
		 * @access public
		 * @param NULL
		 * @return void
		 * @since 1.0.0
		 * @author Rob Sanchez
		 */
		public function disable_extension()
		{
			$this->EE->db->delete('extensions', array('class' => $this->module_name));
		}
		
		/////////////////////////////////////
		/// UPDATE FUNCTIONS
		/////////////////////////////////////
		public function mbr_install($has_cp_backend="y", $has_publish_fields = "n", $current= FALSE)
		{
			// updates
			if ( $current!== FALSE )
			{
				$this->current = $current;
				if ($this->current == $this->version)
				{
					return FALSE;
				}
			}
			else
			// installs
			{
				//install module to exp_modules
				$data = array(
					'module_name' => ucwords($this->module_name),
					'module_version' => $this->version,
					'has_cp_backend' => $has_cp_backend,
					'has_publish_fields' => $has_publish_fields
				);

				$this->EE->db->insert('modules', $data);
				
				////////////// FIELD TYPES

				if (!empty($this->fieldtypes))
				{
					//install the fieldtypes
					require_once APPPATH.'fieldtypes/EE_Fieldtype'.EXT;

					foreach ($this->fieldtypes as $fieldtype)
					{
						require_once PATH_THIRD.$fieldtype.'/ft.'.$fieldtype.EXT;

						$ft = get_class_vars(ucwords($fieldtype.'_ft'));

						$this->EE->db->insert('fieldtypes', array(
							'name' => $fieldtype,
							'version' => $ft['info']['version'],
							'settings' => base64_encode(serialize(array())),
							'has_global_settings' => method_exists($fieldtype, 'display_global_settings') ? 'y' : 'n'
						));
					}
				}
			}
			///////////////// TABLES /////////////////////////
			$this->EE->load->dbforge();

			// only do this if we actually have tables. 
			if (!empty($this->tables))
			{
				foreach ($this->tables as $key=>$value)
				{
					if ($key =="generic_settings")
					{
						unset($this->tables['generic_settings']);
						$this->tables[$this->module_name."_settings"] = $value;
						break;
					}
				}
				$this->EE->load->model('table_model');
				$this->EE->table_model->update_tables($this->tables);
			}
 
			/////////////// NOTIFICICATIONS /////////////////////////
			if (!empty($this->notification_events))
			{
				$existing_notifications = array();

				if ($this->EE->db->table_exists('cartthrob_notification_events'))
				{
					$this->EE->db->select('notification_event')
 							->like('application', ucwords($this->module_name), 'after');
					$query = $this->EE->db->get('cartthrob_notification_events'); 
					
					if ($query->result() && $query->num_rows() > 0)
					{
						foreach ($query->result() as $row)
						{
							$existing_notifications[] = $row->notification_event;
						}					
					}

					foreach ($this->notification_events as $event)
					{
						if (!empty($event))
						{
							if ( ! in_array($event, $existing_notifications))
							{
								$this->EE->db->insert(
									'cartthrob_notification_events',
										array(
											'application' => ucwords($this->module_name),
											'notification_event' => $event,
										)
									);
							}
						}
					}
				}
			}
			// end notifications


			/////////////// EXTENSIONS /////////////////////////

			if (!empty($this->hooks))
			{
				if ( $current !== FALSE )
				{
					$this->EE->db->update('extensions', array('version' => $this->version), array('class' => ucwords($this->module_name).'_ext'));
				}
				$this->EE->db->select('method')
						->from('extensions')
						->like('class', ucwords($this->module_name), 'after');

				$existing_extensions = array();

				foreach ($this->EE->db->get()->result() as $row)
				{
					$existing_extensions[] = $row->method;
				}

				foreach ($this->hooks as $row)
				{
					if (!empty($row))
					{
						if ( ! in_array($row[0], $existing_extensions))
						{
							$this->EE->db->insert(
								'extensions',
								array(
									'class' => ucwords($this->module_name).'_ext',
									'method' => $row[0],
									'hook' => ( ! isset($row[1])) ? $row[0] : $row[1],
									'settings' => ( ! isset($row[2])) ? '' : $row[2],
									'priority' => ( ! isset($row[3])) ? 10 : $row[3],
									'version' => $this->version,
									'enabled' => 'y',
								)
							);
						}
					}
				}
			}
			////////////////////////// MODULE AND MCP ACTIONS /////////////////////

			//check for Addon actions in the database
			//so we don't get duplicates
			$this->EE->db->select('method')
					->from('actions')
					->like('class', ucwords($this->module_name), 'after');

			$existing_methods = array();

			foreach ($this->EE->db->get()->result() as $row)
			{
				$existing_methods[] = $row->method;
			}
			//////////// MODULE ACTIONS
			if (!empty($this->mod_actions))
			{
				//install the module actions from $this->mod_actions
				foreach ($this->mod_actions as $method)
				{
					if ( ! in_array($method, $existing_methods))
					{
						$this->EE->db->insert('actions', array('class' => ucwords($this->module_name), 'method' => $method));
					}
				}

			}
			///////////// MCP ACTIONS
			if (!empty($this->mcp_actions))
			{
				//install the module actions from $this->mcp_actions
				foreach ($this->mcp_actions as $method)
				{
					if ( ! in_array($method, $existing_methods))
					{
						$this->EE->db->insert('actions', array('class' => ucwords($this->module_name).'_mcp', 'method' => $method));
					}
				}
			}

			return TRUE;
 		}
		public function install($has_cp_backend = "y", $has_publish_fields = "n")
		{
			return $this->mbr_install($has_cp_backend, $has_publish_fields); 
		}
		public function update($current = '')
		{
			return $this->mbr_install(NULL, NULL, $current); 
		}
		public function uninstall()
		{
			$this->EE->db->delete('modules', array('module_name' => ucwords($this->module_name)));

			$this->EE->db->like('class', ucwords($this->module_name), 'after')->delete('actions');

			$this->EE->db->delete('extensions', array('class' => ucwords($this->module_name).'_ext'));

			if ($this->EE->db->table_exists('cartthrob_notification_events'))
			{
				$this->EE->db->delete('cartthrob_notification_events', array('application' => ucwords($this->module_name)));
			}

			//should we do this?
			//nah, do it yourself if you really want to
			/*
			$this->EE->load->dbforge();

			foreach (array_keys($this->tables) as $table)
			{
				$this->EE->dbforge->drop_table($table);
			}
			*/

			return TRUE;
			
		}
		////////////////////////// VIEW CONTENT /////////////////////////////
		
		public function view_settings_template($data)
		{
        	    	/*
             		* @var $structure is contained in the $data variable
            	 	*/
            		$structure = array();
			extract($data, EXTR_OVERWRITE);
			$content =NULL; 
			$content ='<div class="'.$structure['class'].'_settings" id="'.$structure['class'].'">';
			
			////////////////////////////// Main Heading /////////////////////// 
			$tmpl = array (
				'table_open'          => '<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">',
			);

			$output_table = FALSE; 
			if (!empty($structure['caption']))
			{
				$output_table = TRUE; 
				$this->EE->table->set_caption(lang($structure['caption']));
			}

			if (!empty($structure['description']))
			{
				$output_table = TRUE; 
				$this->EE->table->set_heading(array(
					'<strong>'.lang($structure['title']) .'</strong><p>'.lang($structure['description'])."</p>"
					));
			}
			elseif(!empty($structure['title']))
			{
				$output_table = TRUE; 
				$this->EE->table->set_heading(array(
					'<strong>'.lang($structure['title']) .'</strong>'
					));
			}
			// normally this just outputs a table with nothing in it other than headers. This kills that if it's empty
			if ($output_table)
			{
				$this->EE->table->set_template($tmpl);
				$content .= $this->EE->table->generate(); 
			}
			$this->EE->table->clear();

		 	///////////////////////////////////////////////////////////////////

			if (is_array($structure['settings'])) 
			{
				foreach ($structure['settings'] as $row_id => $setting) 
				{
					if ($setting['type'] == 'matrix') 
					{
						//retrieve the current set value of the field
					    $current_values = (isset($settings[ $setting['short_name']]) ) ? $settings[ $setting['short_name']] : FALSE;

					    //set the value to the default value if there is no set value and the default value is defined
					    $current_values = ($current_values === FALSE && isset($setting['default'])) ? 
							$setting['default'] : $current_values;

						$content .='<div class="matrix">';
						$content .='<table cellpadding="0" cellspacing="0" border="0" class="mainTable padTable">'; 

						$header = array(""); 
						foreach ($setting['settings'] as $count => $matrix_setting) 
						{

							$style=""; 
						    $setting['settings'][$count]['style'] = $style;
							$line = "<strong>". lang($matrix_setting['name']). "</strong>"; 

							isset($matrix_setting['note']) ? $line .='<br />'.lang($matrix_setting['note']) : '';
							$header[] = $line; 
						}
						$header[] = ""; 
						$content .="<thead>"; 
						$content .="<tr>"; 
						foreach ($header as $th)
						{
							$content .="<th>";
							$content .=$th; 
							$content .="</th>"; 
						}
						$content .="</tr>"; 
						$content .='</thead>'; 
						$content .='<tbody>'; 


						if ($current_values === FALSE || ! count($current_values))
						{
							$current_values = array(array());
							foreach ($setting['settings'] as $matrix_setting)
							{
								$current_values[0][$matrix_setting['short_name']] = isset($matrix_setting['default']) ? $matrix_setting['default'] : '';
							}
						}

						foreach ($current_values as $count => $current_value)
						{
							$content .='<tr class="'.$setting['short_name'].'_setting"';
							$content .=	'rel ="'.$setting['short_name'].'"'; 
							$content .=	'id	="'.$setting['short_name'].'_setting_'.$count.'">';

								$content .='<td><img border="0" ';
								$content .='src="'. $this->drag_handle .'" width="10" height="17" /></td>';
									foreach ($setting['settings'] as $matrix_setting) 
									{
										$content .='<td style="'.$matrix_setting['style'].'" rel="'.$matrix_setting['short_name'].'"';
										$content .='class="'.$matrix_setting['short_name'].'" >'; 
										$content .= $this->plugin_setting($matrix_setting['type'], $setting['short_name'].'['.$count.']['.$matrix_setting['short_name'].']', @$current_value[$matrix_setting['short_name']], @$matrix_setting['options'], @$matrix_setting['attributes']);
										$content .='</td>'; 
									}
								$content .='<td>'; 
								$content .=' <a href="#" class="remove_matrix_row">
											<img border="0" src="'.$this->EE->config->item('theme_folder_url').'cp_themes/default/images/content_custom_tab_delete.png" />
										</a>';
								$content .='</td>'; 
							$content .='</tr>';
						}

						$content.='	</tbody>
						</table>
					</div>';

						$content .='
						<fieldset class="plugin_add_new_setting" >
							<a href="#" class="ct_add_matrix_row" id="add_new_'. $setting['short_name'].'">
								'.lang('add_another_row').'
							</a>
						</fieldset>';

						$content .='
						<table style="display: none;" class="'.$structure['class'].'">
							<tr id="'. $setting['short_name'].'_blank"  class="'.$setting['short_name'].'">
								<td ><img border="0" src="'.$this->drag_handle .'" width="10" height="17" /></td>';

								foreach ($setting['settings'] as $matrix_setting)
								{
									$content .='<td style="'.$matrix_setting['style'].'"  rel="'.$matrix_setting['short_name'].'"  class="'.$matrix_setting['short_name'].'">'.$this->plugin_setting($matrix_setting['type'], '', (isset($matrix_setting['default'])) ? $matrix_setting['default'] : '', @$matrix_setting['options'], @$matrix_setting['attributes']).'</td>';							
								}

								$content .='
								<td>
									<a href="#" class="remove_matrix_row"><img border="0" src="'.$this->EE->config->item('theme_folder_url').'cp_themes/default/images/content_custom_tab_delete.png" /></a>
								</td>
							</tr>
						</table>
						';
					}
					 elseif ($setting['type'] == 'header')
					{
						$content .='<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
								<thead class="">
									<tr>
										<th colspan="2">
											<strong>'.lang($setting['name']).'</strong><br />
										</th>
									</tr>
								</thead>
							</table>';
					}
					elseif ($setting['type'] == "html")
					{
 						$content .='
							<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
							<tbody>
								<tr class="even">
									<td>
										'.$setting['html'].'
									</td>
								</tr>
							</tbody>
							</table>';
 					}
					else
					{
						//retrieve the current set value of the field
						$current_value = (array_key_exists($setting['short_name'], $settings) ? $settings[ $setting['short_name']] : FALSE);
						
						// @NOTE. if one of the CT global config contains a value... it'll always fill the PREVIOUS current value. Make sure your setting name doesn't clash with a default config value, or it will always be used as a default instead of your local default
						//set the value to the default value if there is no set value and the default value is defined
						$current_value = (($current_value === FALSE && array_key_exists('default', $setting)) ? $setting['default'] : $current_value);

						$current_value = array_key_exists('current', $setting)  ? $setting['current'] : $current_value;
						
 						if ($setting['type'] == "hidden")
						{
	 						$content .= $this->plugin_setting($setting['type'], $setting['short_name'], $current_value, @$setting['options'], @$setting['attributes']); 
						}
						else
						{
							$content .='
								<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
								<tbody>
									<tr class="even">
										<td>
											<label>'.lang($setting['name']).'</label><br><span class="subtext">'.(isset($setting['note']) ? lang($setting['note']) : NULL).'</span>
											</td>
										<td style="width:50%;">
											'.$this->plugin_setting($setting['type'], $setting['short_name'], $current_value, @$setting['options'], @$setting['attributes']).'
										</td>
									</tr>
								</tbody>
								</table>';
						}
 					}
				}
			}
			$content .="</div>";
			
			return $content; 
		}
		
		public function get_html($structure, $view_file_name = NULL)
		{
 			$view_path = PATH_THIRD.$this->module_name.'/views/';
			$view_html = NULL; 

			if (file_exists($view_path.$view_file_name)) {
				$view_html = $this->EE->load->view($view_file_name, $structure, TRUE); 
			}
			else
			{
				if (!is_array($structure))
				{
					$view_html = $structure;
				}
				elseif (!empty($structure['html']))
				{
					$view_html = $structure['html']; 
				}
				else
				{
					// this will probably throw an error,but that's probably what we want
					$view_html = $this->EE->load->view($view_file_name, $structure, TRUE); 
				}
			}
			return $view_html; 
		}
		public function view_settings_form($data)
		{
            /*
             * @var $no_form
             * @var string  $form_open
             * @var array $sections
             */
            $no_form = FALSE;
            $form_open = NULL;
            $sections = array();
			extract($data, EXTR_OVERWRITE);
			
			$tab = $this->module_name. "_tab"; 
 			$content = "<!-- begin right column -->";

			$content .='
			<div class="ct_top_nav">
				<div class="ct_nav" >';
				foreach (array_keys($this->nav) as $method) 
				{
					if (!in_array($method, $this->no_nav))
					{
						$content .='<span class="button"><a class="nav_button';
						if ( ! $this->EE->input->get('method') || $this->EE->input->get('method') == $method)
						{
							$content .=' current'; 
						}
						$content.='"'; 
						
						// if there's no lang itme for this, we'll just convert the method name. 
						$nav_lang = lang('nav_head_'.$method); 
						if ($nav_lang == 'nav_head_'.$method)
						{
 							$nav_lang = ucwords(str_replace("_", " ",$method)); 
						}
						
						$content .= ' href="'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name.AMP.'method='.$method.'">'.$nav_lang.'</a></span>'; 
					}
				} 
			$content .='			
					<div class="clear_both"></div>	
				</div>	
			</div>';

			$content .='
			<div class="clear_left shun"></div>';

			if ($this->EE->session->flashdata($this->module_name.'_system_error'))
			{
				$content .='<div id="ct_system_error"><h4>';
				$content .= $this->EE->session->flashdata($this->module_name.'_system_error');
				$content .='</h4></div>';

			}

			if ($this->EE->session->flashdata($this->module_name.'_system_message'))
			{
				$content .='<div id="ct_system_message"><h4>';
				$content .= $this->EE->session->flashdata($this->module_name.'_system_message');
				$content .='</h4></div>';
			}

			if ($this->extension_enabled===FALSE) 
			{
				$content .='<div id="ct_system_error"><h4>';
				$content .= lang('extension_not_installed'); 
				$content .='</h4>';
				$content .= lang('please').' <a href="'.BASE.AMP.'C=addons_extensions'.'">'.lang('enable').'</a> '.lang('before_proceeding').'</div>';
			}

			if ($this->module_enabled=== FALSE)
			{
				$content .='<div id="ct_system_error"><h4>';
				$content .= lang('module_not_installed'); 
				$content .='</h4>';
				$content .= lang('please').' <a href="'.BASE.AMP.'C=addons_modules'.'">'.lang('install').'</a> '.lang('before_proceeding').'</div>';
			}

			if (! $no_form)
			{
				if ($form_open)
				{
					$content .= $form_open; 
				}
				else
				{
					$content .= form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name.AMP.'method=quick_save'.AMP.'return='.$this->EE->input->get('method', "index"));
				}
			}
			else
			{
				/*
				// if this is uncommented, a quick_save form is output before the correct form
				if (!empty($form_open))
				{
					$content .= $form_open; 
				}
				*/
			}
			$content .='<div id="'.$this->module_name.'_settings_content">
				<input type="hidden" name="'.$this->module_name.'_tab" value="'.$tab.'" id="'.$this->module_name.'_tab" />'; 
 
			foreach ($sections as $section)
			{
				$view_html = $this->get_html($data, $section); 
 					
				$section_lang = lang($section."_header"); 
				if ($section_lang == $section."_header")
				{
					$section_lang = ucwords(str_replace("_", " ", $section)); 
				}

				$content .='<h3 class="accordion" data-hash="'.$section.'">'.$section_lang.'</h3>
				<div style="padding: 5px 1px;">
					'.$view_html.'
				</div>'; 
			}	

			if (! $no_form)
			{
				$content .='<p><input type="submit" name="submit" value="'.lang('submit').'" class="submit" /></p>
				</form>'; 
			}
			
			return $content; 
			
		}
		public function default_css()
		{
			$css = "
			<style type='text/css'>
			#ct_errors {
			    border: #CCC9A4 1px solid;
			    border-top-width: 0px;
				background: #fff2cb url(../images/ct_subnav_bg.gif) repeat-x;
				padding: 8px 10px 8px 10px; 
				margin: 0px 0 10px 0;
			}
			#ct_system_error, #ct_system_message{
				border:1px solid #bf0012;
				background:#ffbc9f url(../images/ct_not_logged_heart.png) no-repeat right top;
				padding:15px 45px 15px 15px;
			    font-family: 'Lucida Grande', Arial, Helvetica, sans-serif;
				color:#18362D;
				font-size:14px;
				margin:0 0 10px 0;
			}
			#ct_system_message{
				border:1px solid #00bf3c;
				background:#99f7af url(../images/ct_not_logged_heart.png) no-repeat right top;
				
			}
			#ct_system_error a{
				color:#a10a0a;
			}
			#ct_system_message a{
				color:#a10a0a;
			}
			#ct_system_error h4, #ct_system_message h4{
			    font-family: 'Lucida Grande', Arial, Helvetica, sans-serif;
				color:#18362D;
				font-size:18px;
				font-weight:bold;
				display:inline;
				margin:0 8px 0 0;
			}


			.ct_nav {
				//padding:			0 25px;
				float: left;
			}

			.ct_nav span.button {
				float:				  left;
				margin-bottom:  10px;
			    margin-right: 6px;
			}
			 .ct_nav .nav_button {
				font-weight:	normal;
				background:		#d0d9e1;
				color:			#34424b;
			}

			.ct_nav span.button a.nav_button{
				color: #34424b;
			    font: 12px/12px Arial, 'Hevlvetica Neue', sans-serif;
			    -moz-border-radius: 3px;
			    -webkit-border-radius: 3px;
			}
			.ct_nav span.button a.nav_button:hover {
				background-color:#e11842;
				text-decoration:none;
				color: #fff;
			}
			.ct_nav span.button a.current {
				background-color:#e11842;
				text-decoration:none;
				color: #fff;
			}
			.clear_both{
				clear: both;
			}
			label.radio{
				display: block;
				margin: 0;
				padding:  0;
				padding-bottom: 5;
			}

			.plugin_add_new_setting{
				 margin-top:10px;
				 margin-bottom:10px;	
			}
			</style>
			"; 
			return $css; 
		}
		public function jquery_create_select($name, $class, $attributes = array(),  $options = array(), $selected = NULL )
		{
			$selected = ($selected ? ",'". $selected."'": '');
			$attributes = array_merge($attributes, array("name"=> $name)); 

			$jquery = "attrs = {};";
			foreach($attributes as $key => $value)
			{
				$jquery .= " attrs['".$key."'] = '".$value."'; ";
			}
			$jquery .= '
				
				var options =  '.json_encode($options).' ; 
				
				$.'.$this->module_name.'CP.createSelect(attrs, options'.$selected.');
				
				$(".'.$class.'").replaceWith($.'.$this->module_name.'CP.createSelect(attrs, options '.$selected.'));
				
			 ';

			$this->EE->javascript->output($jquery);
			
		}
		public function view_settings_form_head($data)
		{
			extract($data); 
			
			// when an href in the plugin_add_new_setting fieldset is clicked....
			$add_new_setting_js = '$("fieldset.plugin_add_new_setting a").bind("click", function(){
			// get the name of the thing to add. The ID of the HREF is used as the name
				var name = $(this).attr("id").replace("add_new_", "");
				
			// if there is an existing TR with the classname NAME_setting, look for the ID and remove NAME_setting to get the current count
				var count = ($("tr."+name+"_setting:last").length > 0) ? Number($("tr."+name+"_setting:last").attr("id").replace(name+"_setting_","")) + 1 : 0;

			// get the plugin class name from the div that surrounds the table containing the settings 
				var plugin_classname = $("#"+name+"_blank").parent().parent().attr("class");

			// there is probably not multiple classes applied, but if there are, split them at the space
				var element = $("#"+name+"_blank").attr("class").split(" ");
			// get the short_name from the split class. 
				var setting_short_name = element[0];

			// clone the blank
				var clone = $("#"+name+"_blank").clone();
			
			// clone the ID NAME_setting_1
				clone.attr({"id":name+"_setting_"+count});
			// clone the class, NAME_setting
				clone.attr({"class":name+"_setting"});
				
			// clone the rel STRUCTURE_CLASSNAME_settings[SHORT_NAME]
				clone.attr({"rel": plugin_classname+"_settings["+setting_short_name+"]"});
			// finde each INPUT
				clone.find(":input").each(function(){
					
					// get the SETTING_SHORT_NAME
					var matrix_setting_short_name = $(this).parent().attr("class");
					if ( ! $(this).parent().attr("rel"))
					{
 						// change the name attribute to STRUCTURE_CLASSNAME_settings[setting_short_name][count][matrix setting short name]
						$(this).attr("name", plugin_classname+"_settings["+setting_short_name+"]["+count+"]["+matrix_setting_short_name+"]");
						$(this).attr("rel", plugin_classname); 	
					}
					else
					{
						$(this).attr("name", name+"["+count+"]["+matrix_setting_short_name+"]");	
					}
					// add taht short name to the parent rel
					$(this).parent().attr("rel", matrix_setting_short_name);

				});
				// in the clone, remove the content from the TD classes
	 			clone.children("td").attr("class","");
				// add to the row above. 
				$(this).parent().prev().find("tbody").append(clone);
				return false;
			});';
			
			$content = ""; 
			$content .='

			<script type="text/javascript">

				jQuery.'.$this->module_name.'CP = {
					currentSection: function() {
						if (window.location.hash && window.location.hash != "#") {
							return window.location.hash.substring(1);
						} else {
							return $("#'.$this->module_name.'_settings_content h3:first").attr("data-hash");
						}
					},
 					'.(isset($channel_titles) ? "channels: ".json_encode($channel_titles).',' : NULL).'
 					'.(isset($product_channel_titles) ? "product_channels: ".json_encode($product_channel_titles).',' : NULL).'
					'.(isset($fields) ? "fields: ".json_encode($fields).',' : NULL).'
					'.(isset($member_fields) ? "member_fields: ".json_encode($member_fields).',' : NULL).'
					'.(isset($product_channel_fields) ? "product_channel_fields: ".json_encode($product_channel_fields).',' : NULL).'
					'.(isset($order_channel_fields) ? "order_channel_fields: ".json_encode($order_channel_fields).',' : NULL).'
 					'.(isset($status_titles) ? "statuses: ".json_encode($status_titles).',' : NULL).'
					'.(isset($templates) ? "templates: ".json_encode($templates).',' : NULL).'
					'.(isset($states) ? "states: ".json_encode($states).',' : NULL).'
				 	'.(isset($countries) ? "countries: ".json_encode($countries).',': NULL).'
				 	'.(isset($states_and_countries) ? "statesAndCountries: ".json_encode($states_and_countries).',' : NULL).'
					checkSelectedChannel: function (selector, section) {
						if ($(selector).val() !="") {
							$(section).css("display","inline");
						} else {
							$(section).css("display","none");
						}
					},
					updateSelect: function(select, options) {
						var val = $(select).val();
						var attrs = {};
						for (i=0;i<select.attributes.length;i++) {
							if (select.attributes[i].name == "value") {
								val = select.attributes[i].value;
							} else {
								attrs[select.attributes[i].name] = select.attributes[i].value;
							}
						}
						$(select).replaceWith($.'.$this->module_name.'CP.createSelect(attrs, options, val));
					},
					createSelect: function(attributes, options, selected) {
						var select = "<select ";
						for (i in attributes) {
							select += i+ "=\""+attributes[i]+ "\"";
						}
						select += ">";
						for (i in options) {
							select += "<option value=\""+i+"\" ";
							if (selected != undefined && selected == i) {
								select += " selected=\"selected\"";
							}
							select += ">"+options[i]+"</option>";
						}
						select += "</select>";
						return select;
					}
				}

				jQuery(document).ready(function($){

					$("select.states").each(function(){
						$.'.$this->module_name.'CP.updateSelect(this, $.'.$this->module_name.'CP.states);
					});
					$("select.states_blank").each(function(){
						var states = {"" : "---"};
						$.extend(states, $.'.$this->module_name.'CP.states);
						$.'.$this->module_name.'CP.updateSelect(this, states);
					});
					$("select.templates").each(function(){
						$.'.$this->module_name.'CP.updateSelect(this, $.'.$this->module_name.'CP.templates);
					});
					$("select.templates_blank").each(function(){
						var templates = {"" : "---"};
						$.extend(templates, $.'.$this->module_name.'CP.templates);
						$.'.$this->module_name.'CP.updateSelect(this, templates);
					});
					$("select.statuses").each(function(){
						$.'.$this->module_name.'CP.updateSelect(this, $.'.$this->module_name.'CP.statuses);
					});
					$("select.statuses_blank").each(function(){
						var statuses = {"" : "---", "ANY" : "ANY"};
						$.extend(statuses, $.'.$this->module_name.'CP.statuses);
						$.'.$this->module_name.'CP.updateSelect(this, statuses);
					});

					$("select.countries").each(function(){
						$.'.$this->module_name.'CP.updateSelect(this, $.'.$this->module_name.'CP.countries);
					});
					$("select.countries_blank").each(function(){
						var countries = {"" : "---"};
						$.extend(countries, $.'.$this->module_name.'CP.countries);
						$.'.$this->module_name.'CP.updateSelect(this, countries);
					});
					$("select.states_and_countries").each(function(){
						$.'.$this->module_name.'CP.updateSelect(this, $.'.$this->module_name.'CP.statesAndCountries);
					});
					$("select.all_fields").each(function(){
						var fields = {"":"---"};
						for (i in $.'.$this->module_name.'CP.fields) {
							for (j in $.'.$this->module_name.'CP.fields[i]) {
								fields["field_id_"+$.'.$this->module_name.'CP.fields[i][j].field_id] = $.'.$this->module_name.'CP.fields[i][j].field_label;
							}
						}
						$.'.$this->module_name.'CP.updateSelect(this, fields);
					});
					$("select.product_channel_fields").each(function(){
						var product_channel_fields = {"":"---"};
						for (i in $.'.$this->module_name.'CP.product_channel_fields) {
							for (j in $.'.$this->module_name.'CP.product_channel_fields[i]) {
								product_channel_fields["field_id_"+$.'.$this->module_name.'CP.product_channel_fields[i][j].field_id] = $.'.$this->module_name.'CP.product_channel_fields[i][j].field_label;
							}
						}
						$.'.$this->module_name.'CP.updateSelect(this, product_channel_fields);
					});
					$("select.order_channel_fields").each(function(){
						var order_channel_fields = {"":"---"};
						for (i in $.'.$this->module_name.'CP.order_channel_fields) {
							for (j in $.'.$this->module_name.'CP.order_channel_fields[i]) {
								order_channel_fields["field_id_"+$.'.$this->module_name.'CP.order_channel_fields[i][j].field_id] = $.'.$this->module_name.'CP.order_channel_fields[i][j].field_label;
							}
						}
						$.'.$this->module_name.'CP.updateSelect(this, order_channel_fields);
					});
					$("select.channels").each(function(){
						$.'.$this->module_name.'CP.updateSelect(this, $.'.$this->module_name.'CP.channels);
					});
					$("select.product_channels").each(function(){
						$.'.$this->module_name.'CP.updateSelect(this, $.'.$this->module_name.'CP.product_channels);
					});
					$("select.member_fields").each(function(){
						$.'.$this->module_name.'CP.updateSelect(this, $.'.$this->module_name.'CP.member_fields);
					});
					
					$.'.$this->module_name.'CP.checkSelectedChannel("#select_orders", ".requires_orders_channel"); 
					
					
					
					$("#select_orders").bind("change", function(){
						$.'.$this->module_name.'CP.checkSelectedChannel("#select_orders", ".requires_orders_channel"); 
					});
					
					
					$("select.product_channels").bind("change", function(){
						var channel_id = Number($(this).val());
						var section = $(this).attr("id").replace("select_", "");
						$("select.field_"+section).children().not(".blank").remove();
			 			if ($(this).val() != "")
						{
							for (i in $.'.$this->module_name.'CP.product_channel_fields[channel_id])
							{
								$("select.field_"+section).append("<option value=\"field_id_"+$.'.$this->module_name.'CP.product_channel_fields[channel_id][i].field_id+"\">"+$.'.$this->module_name.'CP.product_channel_fields[channel_id][i].field_label+"</option>");
							}

						}
					});

					$("#'.$this->module_name.'_tab").val($.'.$this->module_name.'CP.currentSection() );

					var count = 0; 
					'.$add_new_setting_js.'

					$("a.remove_matrix_row").live("click", function(){
						if (confirm("Are you sure you want to delete this row?"))
						{
							if ($(this).parent().get(0).tagName.toLowerCase() == "td")
							{
								$(this).parent().parent().remove();
							}
							else
							{
								$(this).parent().remove();
							}
						}
						return false;
					}).live("mouseover", function(){
						$(this).find("img").animate({opacity:1});
						console.log("in");
					}).live("mouseout", function(){
						console.log("out");
						$(this).find("img").animate({opacity:.2});
					}).find("img").css({opacity:.2});


					$(".add_matrix_row").bind("click", function(){
						var name = $(this).attr("id").replace("_button", "");
						var index = ($("."+name+"_row:last").length > 0) ? Number($("."+name+"_row:last").attr("id").replace(name+"_row_","")) + 1 : 0;
						var clone = $("#"+name+"_row_blank").clone(); 
						clone.attr("id", name+"_row_"+index).addClass(name+"_row").show();
						clone.find(":input").bind("each", function(){
							$(this).attr("name", $(this).attr("data-hash").replace("INDEX", index));
						});
						$(this).parent().before(clone);
						return false;
					});

					// Return a helper with preserved width of cells
					var fixHelper = function(e, ui) {
						ui.children().each(function() {
							$(this).width($(this).width());
						});
						return ui;
					};

					$("div.matrix table tbody").sortable({
						helper: fixHelper,
						stop: function(event, ui) { 
							var count=0; 
							$("div.matrix table tbody tr").each(function(){
								$(this).find(":input").each(function(){
			 						$(this).attr("name", $(this).parents("tr").attr("rel")+"["+count+"]["+$(this).parent().attr("rel")+"]");	
								}); 
								count +=1; 
							});
						}
					});


				});
 
			 	';

 			$content.='</script>';
			
			return $content; 
		}
		
		// @TODO missing member stuff
		
		public function view_installation($data)
		{
            /*
             * @var $templates_installed is extracted from $data
             * @var $template_errors is extracted from $data
             * @var $install_channels is extracted from $data
             * @var $install_template_groups is extracted from $data
             * @var $install_channel_data is extracted from $data
             */
           $templates_installed = NULL;
            $template_errors = NULL;
            $install_channels = NULL;
            $install_template_groups = NULL;
            $install_channel_data = NULL;
			extract($data, EXTR_OVERWRITE);
			$content = ""; 
			
			if (is_array($templates_installed) && count($templates_installed)) 
			{
				$content .='<h4>'.lang('installed').':</h4><ul class="bullets">';
				foreach ($templates_installed as $installed) 
				{
					$content .='<li>'.$installed.'</li>';
				}
				$content .='</ul>';
			}

			if (is_array($template_errors) && count($template_errors))
			{
				$content .='<h4>'.lang('errors').':</h4><ul class="bullets">';
				foreach ($template_errors as $errors) 
				{
					$content .='<li>'.$errors.'</li>';
				}
				$content .='</ul>';
			}

			 $content .='<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
			<caption>'.lang('install_channels_header').'</caption>
			<tbody>';

			if (count($install_channels)) 
			{
					$content.='
					<tr class="'.alternator('odd', 'even').'">
						<td>
							<label style="height:100%;">'.lang('channel').'</label>
						</td>
						<td style="width:50%;">
						<ul>';
							foreach ($install_channels as $index => $name)
							{
								$content .='
								<li>
									<label class="radio">
										<input type="checkbox" checked="checked" name="channels[]" class="channels" value="'.$index.'" />
										'.$name.'
									</label>';

								if (isset($fields[$index]))
								{
									$content .='<ul class="bullets">';

									foreach ($fields[$index] as $field) 
									{
										$content .= '<li>'.$field.'</li>'; 
									}
									$content .='</ul>';
								}
								$content .='</li>';
							}

						$content .='
							</ul>
						</td>
					</tr>';
					
			}

			if (count($install_template_groups))
			{
				$content .='
					<tr class="'.alternator('odd', 'even').'">
						<td>
							<label style="height:100%;">'.lang('template_group').'</label>
						</td>
						<td style="width:50%;">
							<ul>';

							foreach ($install_template_groups as $index => $name)
							{
								$content .='
								<li>
									<label class="radio">
										<input type="checkbox" checked="checked" name="template_groups[]" class="template_groups" value="'.$index.'" />
										'.$name.'
									</label>
									';

									if (isset($templates[$index]))
									{
										$content .='
										<ul class="bullets">
										';
										foreach ($templates[$index] as $template)
										{
											$content .= '<li>'.$template.'</li>';
										}
										$content .='</ul>';
									}
								$content .="</li>";
							}
							$content .='
							</ul>
						</td>
					</tr>
					'; 	
			}
			
 			if (count($install_channel_data))
			{
				$content .='
					<tr class="'.alternator('odd', 'even').'">
						<td>
							<label style="height:100%;">'.lang('install_channel_data').'</label>
						</td>
						<td style="width:50%;">
							<ul>';

 							foreach ($install_channel_data as $index => $name)
							{
								$content .='
								<li>
									<label class="radio">
										<input type="checkbox" checked="checked" name="channel_data[]"  value="'.$index.'" />
										'.$name.'
									</label>
									';
									 
								$content .="</li>";
							}
 							$content .='
							</ul>
						</td>
					</tr>
					';
			}
				$content .=	'
				</tbody>	
			</table>
			';
			
			return $content; 
			
		}
		public function view_plugin_settings($data)
		{
            /*
             * @var array $plugins is extracted from $data
             * @var string $plugin_type is extracted from $data
             */
            $plugins = array();
            $plugin_type = NULL;
			extract($data, EXTR_OVERWRITE);
			$content =""; 
			foreach ($plugins as $plugin)
			{
				$content .='<div class="'.$plugin_type.'_settings" id="'.$plugin['classname'].'">

					<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
						<thead class="">
							<tr>
								<th colspan="2">
									<strong>'.lang($plugin['title']).' '.lang('settings').'</strong><br />
								</th>
							</tr>
						</thead>
						<tbody>';

							if (!empty($plugin['note']))
							{
								$content .='
								<tr class="'.alternator('odd', 'even').'">
									<td colspan="2">
										<div class="subtext note">'.lang('gateway_settings_note_title').'</div>
										'.lang($plugin['note']).'
									</td>
								</tr>';
							} 

							if (!empty($plugin['overview']))
							{
								$content .='
								<tr class="'.alternator('odd', 'even').'">
									<td colspan="2">
				 						<div class="ct_overview">
											'.lang($plugin['overview']).'
										</div>
									</td>
								</tr>
								';
							}

							if (!empty($plugin['affiliate']))
							{
								$content .='
								<tr class="'.alternator('odd', 'even').'">
									<td>
										<div class="subtext">'.lang('gateway_settings_affiliate_title').'</div>
									</td>
									<td style="width:50%;">
										'.lang($plugin['affiliate']).'
									</td>
								</tr>';
							}
						$content .='	
						</tbody>
					</table>';

					if (is_array($plugin['settings']))
					{
						foreach ($plugin['settings'] as $setting)
						{
							if ($setting['type'] == 'matrix')
							{
								    //retrieve the current set value of the field
								    $current_values = (isset($settings[$plugin['classname'].'_settings'][$setting['short_name']])) ?
								 		$settings[$plugin['classname'].'_settings'][$setting['short_name']] : FALSE;

								    //set the value to the default value if there is no set value and the default value is defined
								    $current_values = ($current_values === FALSE && isset($setting['default'])) ? 
										$setting['default'] : $current_values;

								$content .='
								<div class="matrix">
									<table cellpadding="0" cellspacing="0" border="0" class="mainTable padTable">
										<thead>
										    <tr>
												<th></th>';

												foreach ($setting['settings'] as $count => $matrix_setting) 
												{
													$style=""; 
												    $setting['settings'][$count]['style'] = $style;

													$content .='
				 									<th>
														<strong>'.lang($matrix_setting['name']).'</strong>'.(isset($matrix_setting['note']) ? '<br />'.lang($matrix_setting['note']) : '').'
													</th>';
												}

												$content .='<th style="width:20px;"></th>
										    </tr>
										</thead>
										<tbody>';

									if ($current_values === FALSE || ! count($current_values))
									{
										$current_values = array(array());
										foreach ($setting['settings'] as $matrix_setting)
										{
											$current_values[0][$matrix_setting['short_name']] = isset($matrix_setting['default']) ? $matrix_setting['default'] : '';
										}
									}
			 						foreach ($current_values as $count => $current_value) 
									{
										$content .='
										<tr class="'.$plugin['classname'].'_'.$setting['short_name'].'_setting" 
											rel = "'.$plugin['classname'].'_settings['.$setting['short_name'].']'.'" 		
											id="'.$plugin['classname'].'_'.$setting['short_name'].'_setting_'.$count.'">
											<td><img border="0" src="'.$this->drag_handle .'" width="10" height="17" /></td>';
											foreach ($setting['settings'] as $matrix_setting) 
											{
												$content .='<td  style="'.$matrix_setting['style'].'" rel="'.$matrix_setting['short_name'].'">'.$this->plugin_setting($matrix_setting['type'], $plugin['classname'].'_settings['.$setting['short_name'].']['.$count.']['.$matrix_setting['short_name'].']', @$current_value[$matrix_setting['short_name']], @$matrix_setting['options'], @$matrix_setting['attributes']).'</td>';									
											}

											$content .='

											<td>
												<a href="#" class="remove_matrix_row">
													<img border="0" src="'.$this->EE->config->item('theme_folder_url').'cp_themes/default/images/content_custom_tab_delete.png" />
												</a>
											</td>
										</tr>	';						
									}

									$content.='</tbody>
									</table>
								</div>

								<fieldset class="plugin_add_new_setting">
									<a href="#" class="ct_add_matrix_row" id="add_new_'.$plugin['classname'].'_'.$setting['short_name'].'">
										'.lang('add_another_row').'
									</a>
								</fieldset>

								<table style="display: none;" class="'.$plugin['classname'].'">
									<tr id="'.$plugin['classname'].'_'.$setting['short_name'].'_blank" class="'.$setting['short_name'].'">
										<td  ><img border="0" src="'.$this->drag_handle .'" width="10" height="17" /></td>';

										foreach ($setting['settings'] as $matrix_setting)
										{
											$content .='<td  class="'.$matrix_setting['short_name'].'" style="'.$matrix_setting['style'].'">'.$this->plugin_setting($matrix_setting['type'], '', (isset($matrix_setting['default'])) ? $matrix_setting['default'] : '', @$matrix_setting['options'], @$matrix_setting['attributes']).'</td>';							
										}

										$content.='
										<td>
											<a href="#" class="remove_matrix_row"><img border="0" src="'.$this->EE->config->item('theme_folder_url').'cp_themes/default/images/content_custom_tab_delete.png" /></a>
										</td>
									</tr>
								</table>';
							}
							elseif ($setting['type'] == 'header')
							{
								$content .='
									<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
										<thead class="">
											<tr>
												<th colspan="2">
													<strong>'.lang($setting['name']).'</strong><br />
												</th>
											</tr>
										</thead>
									</table>';
							}
							else 
							{
								//retrieve the current set value of the field
								$current_value = (isset($settings[$plugin['classname'].'_settings'][$setting['short_name']])) ? $settings[$plugin['classname'].'_settings'][$setting['short_name']] : FALSE;
								//set the value to the default value if there is no set value and the default value is defined
								$current_value = ($current_value === FALSE && isset($setting['default'])) ? $setting['default'] : $current_value;

								$content .='
									<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
									<tbody>
										<tr class="even">
											<td>
												<label>'.lang($setting['name']).'</label><br><span class="subtext">'.(isset($setting['note']) ? lang($setting['note']) : '').'</span>
			 								</td>
											<td style="width:50%;">
												'.$this->plugin_setting($setting['type'], $plugin['classname'].'_settings['.$setting['short_name'].']', $current_value, @$setting['options'], @$setting['attributes']).'
											</td>
										</tr>
									</tbody>
									</table>';
							}
						}
					}

				$content .='</div>';
			}
			return $content; 
		}
		
		/**
		 * get_package
		 *
		 * Returns an array of variable data used for printing out package installer templates.
		 * 
		 * @note requires package installer and packages entries model to use. 
		 * 
		 * @see package_installer::packages
		 * @see packages_entries_model
		 * 
		 * @param string $xml_location path to location of installer file.
		 * @return array
		 * @author Chris Newton
		 */
		public function get_package($xml_location = NULL)
		{
			if (!$xml_location)
			{
				$xml_location = PATH_THIRD.$this->module_name. '/installer/installer.xml'; 
			}
			$vars = array(
				'module_name' => $this->module_name,
				'install_channels' => array(),
				'install_template_groups' => array(),
				'install_member_groups' => array(),
				'install_channel_data'	=> array(),
				'template_errors' => ($this->EE->session->flashdata('template_errors')) ? $this->EE->session->flashdata('template_errors') : array(),
				'templates_installed' => ($this->EE->session->flashdata('templates_installed')) ? $this->EE->session->flashdata('templates_installed') : array(),
			);

			$this->EE->load->library('package_installer', array('xml' => $xml_location));

			foreach ($this->EE->package_installer->packages() as $index => $package)
			{
				switch($package->getName())
				{
					case 'channel':
						$vars['install_channels'][$index] = (string) $package->attributes()->channel_title;
						if (isset($package->field_group) && isset($package->field_group->field))
						{
							foreach ($package->field_group->field as $field)
							{
								$vars['fields'][$index][] = (string) $field->attributes()->field_label;
							}
						}

						if (isset($package->channel_data))
						{
							$vars['install_channel_data'][$index] = (string) $package->attributes()->channel_title; 
						}
						
						break;
					case 'template_group':
						$vars['install_template_groups'][$index] = (string) $package->attributes()->group_name;
						if (isset($package->template))
						{
							foreach ($package->template as $template)
							{
								$vars['templates'][$index][] = (string) $template->attributes()->template_name;
							}
						}
						break;
					case 'member_group':
						$vars['install_member_groups'][$index] = (string) $package->attributes()->group_name;
						break;
				}
			}
			return $vars; 
		}
		
		/**
		 * 
		 * install_templates
		 *
		 * @note requires package installer and packages entries model to use. 
		 * 
		 * @see package_installer::packages
		 * @see packages_entries_model
		 * 
		 * @param array $templates_to_install array of template data to install
		 * @param string $xml_location path to location of installer file.
		 * @param string $template_file_location path to location of installer template files.
		 * @return array
		 * @author Chris Newton
		 */
		public function install_templates($templates_to_install = array(), $xml_location = NULL, $template_file_location = NULL)
		{
			if (!$xml_location)
			{
				$xml_location = PATH_THIRD.$this->module_name. '/installer/installer.xml'; 
			}
			if (!$template_file_location)
			{
				$template_file_location = PATH_THIRD.$this->module_name. '/installer/templates/'; 
			}
			$this->EE->load->library('package_installer');
			$this->EE->package_installer->clear_packages(); 
			$this->EE->package_installer->load_xml($xml_location); 
			
			if (is_array($templates_to_install))
			{
				foreach ($this->EE->package_installer->packages() as $row_id => $package)
				{
					if ( ! in_array($row_id, $templates_to_install))
					{
						$this->EE->package_installer->remove_package($row_id);
					}
				}
 
				$this->EE->package_installer->set_template_path($template_file_location)->install_templates();
			}
			$this->EE->session->set_flashdata('template_errors', $this->EE->package_installer->errors());
			$this->EE->session->set_flashdata('templates_installed', $this->EE->package_installer->installed());

			#$this->EE->session->set_flashdata('message_failure', $this->EE->package_installer->errors());
			#$this->EE->session->set_flashdata('message_success', $this->EE->package_installer->installed());

			return true; 
		}
 		/**
		 * 
		 * install_channels
		 *
		 * @note requires package installer and packages entries model to use. 
		 * 
		 * @see package_installer::packages
		 * @see packages_entries_model
		 * 
		 * @param array $channels_to_install array of template data to install
		 * @param bool $install_sample_data whether or not to install sample data
		 * @param string $xml_location path to location of installer template files.
		 * @return array
		 * @author Chris Newton
		 */
		public function install_channels($channels_to_install = array(), $install_sample_data = FALSE, $xml_location = NULL )
		{
			if (!$xml_location)
			{
				$xml_location = PATH_THIRD.$this->module_name. '/installer/installer.xml'; 
			}
			
			$this->EE->load->library('package_installer');
			$this->EE->package_installer->clear_packages(); 
			$this->EE->package_installer->load_xml($xml_location); 

			if (is_array($channels_to_install))
			{
				foreach ($this->EE->package_installer->packages() as $row_id => $package)
				{
					if ( ! in_array($row_id, $channels_to_install))
					{
						$this->EE->package_installer->remove_package($row_id);
					}
				}
 
				$params['add_data'] = FALSE; 
				
 				if ( $install_sample_data )
				{
 					$params['add_data'] = TRUE; 
				}
  				
				$this->EE->package_installer->install_channels($params);
			}

			$this->EE->session->set_flashdata('template_errors', $this->EE->package_installer->errors());
			$this->EE->session->set_flashdata('templates_installed', $this->EE->package_installer->installed());

			return true; 
		}
	} // END CLASS

}
