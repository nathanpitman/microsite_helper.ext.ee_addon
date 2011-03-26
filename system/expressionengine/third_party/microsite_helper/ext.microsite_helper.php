<?php

/**
 * Microsite Helper Extension 
 *
 * Helps turn categories into a tool for managing micro-sites.
 *
 * @package		microsite_helper_ext
 * @category	Extension
 * @author		Nathan Pitman
 * @link		http://ninefour.co.uk/labs
 */
 
class Microsite_helper_ext {
    
    var $name 				= 'Microsite Helper';
    var $version 			= '1.0';
    var $description 		= 'Helps turn categories into a tool for managing micro-sites';
    var $settings_exist 	= 'n';
    var $docs_url 			= 'http://ninefour.co.uk/labs';
 
    var $settings 			= array();
 
	// -------------------------------------------------------------------------- 
   
    function Microsite_helper_ext($settings='')
    {
    	$this->EE =& get_instance();
    
        $this->settings = $settings;
    }

	// -------------------------------------------------------------------------- 
    
    /**
     * Set some current data.
     */
    function microsite_helper()
    {
    	$this->EE->load->helper('url');
    
    	// Get domains equivelant category id
 
     	$domain_cat_url_title = $_SERVER['HTTP_HOST'];
		$query = $this->EE->db->query("SELECT cat_id FROM exp_categories WHERE cat_url_title='".$domain_cat_url_title."' AND group_id='2' LIMIT 1");
  
  		if ($query->num_rows() == 1) {
		
			$domain_cat_id = $query->row('cat_id');
		
		} else {
		
			$domain_cat_id = "No valid domain found";
		
		}
		
		// Get segments equivelant category id
		
		$segments = $this->EE->uri->segment_array();
		$total = count($segments);

		if ($total == 0) {
		
			$segment_cat_url_title = 'home';

		} else {

			$segment_cat_url_title = $segments[$total];

		}
		
		$query = $this->EE->db->query("SELECT cat_id FROM exp_categories WHERE cat_url_title='".$segment_cat_url_title."' AND group_id='1' LIMIT 1");

		if ($query->num_rows() == 1) {
		
			$segment_cat_id = $query->row('cat_id');
		
		} else {
		
			$segment_cat_id = "No valid segment found";
		
		}
		

		$this->EE->config->_global_vars['msite_domain_cat_id'] = $domain_cat_id;
		$this->EE->config->_global_vars['msite_domain_cat_url_title'] = $domain_cat_url_title;
		$this->EE->config->_global_vars['msite_segment_cat_id'] = $segment_cat_id;
		$this->EE->config->_global_vars['msite_segment_cat_url_title'] = $segment_cat_url_title;
    }

	// --------------------------------------------------------------------------

	/**
	 * Activate Extension
	 *
	 * @return void
	 */
	function activate_extension()
	{
		$data = array(
			'class'		=> __CLASS__,
			'method'	=> 'microsite_helper',
			'hook'		=> 'sessions_start',
			'settings'	=> '',
			'priority'	=> 6,
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);

		$this->EE->db->insert('extensions', $data);
	}

	// --------------------------------------------------------------------------

	/**
	 * Disable Extension
	 *
	 * @return void
	 */
	function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');
	}

}

/* End of file ext.microsite_helper.php */
/* Location: ./system/expressionengine/third_party/ext.microsite_helper.php */