<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Form_validation extends CI_Form_validation
{
	public function __construct()
    {
        parent::__construct();
    }

    function _department_check($department)
	{
		//Check that department exists in db
		$this->CI->db->select('department')->from('courses')->where('department', $department);
		if ($this->CI->db->count_all_results() == 0) {
			//No records returned
			$this->set_message('_department_check', 'That department does not exist.');
			return FALSE;
		}
		else
		{//At least one record returned
			return TRUE;
		}
	}

	function _coursecode_check($course_code)
	{
		//check that course code exists for given department
		$this->CI->db->select('department')->from('courses')->where(array('department' => $this->CI->input->post('department'), 'course_code' => $course_code));
		if ($this->CI->db->count_all_results() == 0) {
			//No records returned
			$this->set_message('_coursecode_check', 'That course code does not exist for that department.');
			return FALSE;
		}
		else {
			//At least one record returned
			return TRUE;
		}
	}

	function _price_check($price)
	{
		//Remove $ sign in case user put it in there
		$price = str_replace('$', '', $price);
		//Verify input data is numeric
		if (preg_match('/(?:\d*\.)?\d+/',$price)) {
			return true;
		}
		else {
			$this->set_message('_price_check', 'Enter a numeric value for Price');
			return false;
		}
	}

	function _textbook_condition($condition)
	{
		//check that selected option is from the list provided
		if ($condition == '' || $condition == 'Heavy highlighting/notes' || $condition == 'Some highlighting/notes' || $condition == 'No highlighting/notes' || $condition == 'New/Unopened') {
			return TRUE;
		}
		else
		{
			$this->set_message('_textbook_condition', 'Please select a condition from the dropdown');
			return FALSE;
		}
	}

	function _alpha_dash_space($str)
	{
		//Thanks internet!
		//Modified to also allow !.,': characters
		$this->set_message('_alpha_dash_space', '%s only accepts Alphabetic characters, dashes, spaces');
		if ($str != '') {
	    	return ( ! preg_match("/^([-a-z_ !.,':#])+$/i", $str)) ? FALSE : TRUE;
	    }
	} 

	function _alpha_numeric_dash_space($str) {
		//Modified to also allow !.,':& characters
		$this->set_message('_alpha_numeric_dash_space', '%s only accepts alphanumeric characters (a-z, 0-9), dashes, spaces');
		if ($str != '') {
			return ( ! preg_match("/^([-a-z0-9_ !.,':&#])+$/i", $str)) ? FALSE : TRUE;
		}
		
	}

	function _user_enetered_email($email) {
		// Incase user enters macid@mcmaster.ca we won't punish them for doing this even though we only want macid
		
		$parts = explode('@', $email);
		$result = $parts[0];
		if (preg_match('/[^a-z_\-0-9]/i', $result)) {
			// Checks for valid characters
			$this->set_message('_user_enetered_email', 'Invalid MacID');
			return FALSE;
		}
		return $result; //Returns just the MacID
	}

	function _phone_number($phone) {
		if ($phone == '') {
			return TRUE; 
		} elseif(preg_match("/\b\d{3}[-.]?\d{3}[-.]?\d{4}\b/", $phone)) {
			// $phone is valid
			return TRUE;
		}else {
			$this->set_message('_phone_number', 'Phone Number is not valid');
			return FALSE;
		}
	}
} 
?>