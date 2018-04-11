<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller 
{

	public function index()
	{
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->view('home');
	}

	public function course_code()
	{
		//return list of course codes based on given department
		if ($this->input->post('department')) 
		{
			//ensure it is string type
			$department = (string)$this->input->post('department');
			//query database for list of course codes for that department
			$this->db->select('course_code')->from('courses')->where('department', $department)->distinct();
			//return results
			$query = $this->db->get()->result();
			$htmlOutput = '';
			//results are stored as an object in the $query array
			foreach ($query as $key => $value) 
			{
				//output as html code on the page, the javascript will append it
				echo '<option>' . $value->course_code . '</option>';
			}
		}
		
	}
}
