<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Low Variables Instructions
 *
 * @package Low Variables Instructions
 * @author Dan Grebb <dgrebb@gmail.com>
 * @link http://dgrebb.com
 
	Low Variables Instructions is licensed under Creative Commons Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)

	This is free for you to use and share under the following conditions:

	Attribution: 

	You must attribute the work in the manner specified by the author or licensor (but not in any way that suggests that they endorse you or your use of the work).

	Share Alike: 

	If you alter, transform, or build upon this work, you may distribute the resulting work only under the same or similar license to this one.

 */

class Dg_low_variables_instructions_ext
{

	var $name 					= 'Low Variables Instructions';
	var $version				= '1.0';
	var $description			= 'Inserts instructions describing how to use Low Variables within channel fields';
	var $settings_exist			= 'y';
	var $docs_url				= 'https://github.com/dgrebb/Structure-Search';
	
	var $settings				= array();

	/**
		* Constructor
		*
		* @param 	mixed	Settings array or empty string if none exist.
	*/
	function __construct($settings = '')
	{
		$this->EE =& get_instance();
		$this->settings = $settings;
	}
	// END

    // --------------------------------
	//  Settings
	// --------------------------------

	function settings()
	{
	    $settings = array();

	    $settings['input_placeholder'] = array('t', array('rows' => '4'), '');

	    return $settings;
	}
	// END

	/**
		* Activate Extension
		*
		* This function inserts extension info into exp_extensions
		* 
		* @see http://codeigniter.com/user_guide/database/index.html for
		* more sweet sugar-loving codeigniter delicousness. yum!
		* 
		* @return voide
	*/

	function activate_extension()
	{
		$data = array(
			'class'			=>	__CLASS__,
			'method'		=>	'cp_js_end',
			'hook'			=>	'cp_js_end',
			'settings'		=>	'',
			'priority'		=>	10,
			'version'		=> 	$this->version,
			'enabled'		=>	'y'
	);

	$this->EE->db->insert('extensions', $data);

	}

	/**
		* Update Extension
		* 
		* This function performs any necesary db updates when the extension page is visited
		* 
		* @return mixed void on update / false if none
	*/

	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}

		if ($current < '1.0')
		{
			//update to next version when ready
		}

		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->update(
			'extensions',
			array('version' => $this->version)
		);
	}

	/**
		* Disable the Extension
		*
		* This method removes information from the exp_extensions table
		*
		* @return voide
	*/
	function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');
	}
	/**
		* Let's add some javascript to the Structure tree view
	*/
	function low_variables_js_insert($settings)
	{
		$javascript = "";

		/**
			* Set some variables based on settings page
		*/

		$input_placeholder = $settings['input_placeholder'];

		/**
			* add the instructions to lowvars view
		*/

		$javascript .= <<<EOJS
			$('form#low-variable-form').prepend('<div style="margin: 20px 0;"><h1>Instructions</h1><p>{$input_placeholder}</p>');
		
EOJS;

		return $javascript;
	}

	public function cp_js_end()
	{

		$this->EE->load->helper('array');
	    $settings = $this->settings;
	    $javascript = $this->EE->extensions->last_call;

		$javascript .= <<<EOJS

		// start dg low variables instructions
		$(document).ready(function () {
EOJS;

		$javascript .= $this->low_variables_js_insert($settings);

		$javascript .= <<<EOJS

		});
		// end dg low variables instructions

EOJS;
		return $javascript;
    }

}

// END CLASS