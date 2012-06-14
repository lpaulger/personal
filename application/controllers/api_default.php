<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class api_default extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->helper('url');
		$this->load->view('api_default');
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */