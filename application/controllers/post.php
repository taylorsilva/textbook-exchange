<?php if ( ! defined('BASEPATH')) exit('');

class Post extends CI_Controller {


	public function index()
	{
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('recaptcha');
		
		// If post data submitted then try creating new listing
		if ($this->input->post('macid') != FALSE) {

			// Verify data, if fails then display errors and repopulate form
			$this->load->library('form_validation');

			// Incase user enters macid@mcmaster.ca we won't punish them for doing this even though we only want their macid
			$macid = $this->form_validation->_user_enetered_email($this->input->post('macid')); // Will return MacID or FALSE
			// Set form validation rules
			$this->form_validation->set_rules('macid', 'MacID', 'required|min_length[3]|_user_enetered_email');
			$this->form_validation->set_rules('fname', 'First Name', 'required|min_length[3]|alpha');
			$this->form_validation->set_rules('department', 'Department', 'required|_alpha_numeric_dash_space|_department_check');
			$this->form_validation->set_rules('course_code', 'Course Code', 'required|alpha_numeric|_coursecode_check');
			$this->form_validation->set_rules('title', 'Textbook title', 'required|min_length[5]|max_length[100]|_alpha_numeric_dash_space');
			$this->form_validation->set_rules('edition', 'Textbook edition', 'required|integer|max_length[3]');
			$this->form_validation->set_rules('authors', 'Author(s)', 'min_length[5]|_alpha_numeric_dash_space|max_length[50]');
			$this->form_validation->set_rules('price', 'Price', 'required|_price_check');
			$this->form_validation->set_rules('condition', 'Textbook Condition', '_textbook_condition');
			$this->form_validation->set_rules('notes', 'Notes', 'min_length[3]|_alpha_numeric_dash_space|max_length[300]');
			$this->form_validation->set_rules('isbn', 'ISBN', 'min_length[13]|integer|max_length[13]');
			$this->form_validation->set_rules('phone', 'Phone Number', '_phone_number');

			//Check reCaptcha | JUNE 2016: DISABLING CAPTCHA, NEED TO UPDATE TO GOOGLE'S NEW ROBOT CAPTCHA SYSTEM
			// DISABLED | $this->recaptcha->recaptcha_check_answer();
			// Run form validation
			if ($this->form_validation->run() == FALSE /*OR $this->recaptcha->getIsValid() == FALSE*/)
			{
				/*
				if (!$this->recaptcha->getIsValid()) {
					// Captcha was incorrect, let user know
					$this->session->set_flashdata('captcha_error','Incorrect captcha');
				}
				$recaptcha_html = $this->recaptcha->recaptcha_get_html();
				*/
				// Form input not valid, errors returned
				$this->load->view('post'/*, array('recaptcha_html' => $recaptcha_html)*/);
			}
			else
			{
				//Form valid submit to database
				$this->load->helper('string');
				//Generate unique listing_id, modifier_key, activation_id
				$unique_status = FALSE;
				$listing_id = '123';
				$modifier_key = '123';
				$activation_id = '1234';
				//Generate listing ID
				while ($unique_status == FALSE) {
					$listing_id = random_string('alnum', 12);
					$query = $this->db->query("SELECT * FROM listings WHERE listing_id = '" . $listing_id . "'" ); 
					if ($query->num_rows() == 0) {
						//No records returned, use this ID
						$unique_status = TRUE;
					}
				}
				$unique_status = FALSE; //Reset status for next loop
				//Generate modifier key
				while ($unique_status == FALSE) {
					$modifier_key = random_string('alnum', 32);
					$query = $this->db->query("SELECT * FROM listings WHERE modifier_key = '" . $modifier_key . "'" ); 
					if ($query->num_rows() == 0) {
						//No records returned, use this key
						$unique_status = TRUE;
					}
				}
				//Generate activation ID
				$unique_status = FALSE;
				//Generate modifier key
				while ($unique_status == FALSE) {
					$activation_id = random_string('alnum', 42);
					$query = $this->db->query("SELECT * FROM listings WHERE activation_id = '" . $activation_id . "'" ); 
					if ($query->num_rows() == 0) {
						//No records returned, use this key
						$unique_status = TRUE;
					}
				}
				//Place all form data into an array for submission
				$form_data = array('listing_id' => $listing_id, 
									'modifier_key' => $modifier_key,
									'macid' => $macid,
									'users_fname' => $this->input->post('fname'),
									'department' => $this->input->post('department'),
									'course_code' => $this->input->post('course_code'),
									'price' => $this->input->post('price'),
									'title' => $this->input->post('title'),
									'isbn' => $this->input->post('isbn'),
									'condition' => $this->input->post('condition'),
									'notes' => $this->input->post('notes'),
									'edition' => $this->input->post('edition'),
									'authors' => $this->input->post('authors'),
									'activation_id' => $activation_id,
									'activation_status' => '0',
									'date_submitted' => date("Y-m-d H:i:s"),
									'phone' => $this->input->post('phone')
									);

				$this->db->trans_begin();
				$this->db->insert('listings', $form_data); //Submiitted to DB here, using active record so all data escaped
				if ($this->db->trans_status() === FALSE)
				{
				    $this->db->trans_rollback();
				    $this->load->view('post_error');
				}
				else
				{
				    $this->db->trans_commit();
				    //Data sent successfully, send email to user with listing info
				    $this->load->library('email');
				    $this->email->from('noreply@mactextbooks.ca', 'MacTextbooks.ca');
				    $this->email->to($macid.'@mcmaster.ca');
				    $this->email->subject('Activate your new textbook listing '.$this->input->post('fname').' - Mac Textbook Exchange');
				    $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><body>
				    	Please click the following link to activate your textbook listing: '.base_url('activate/'.$activation_id).
				    	'<p>You can edit or delete your listing by using the following links:
				    	<ul>
				    	<li><a href="'.base_url('edit/'.$modifier_key).'">'.base_url('edit/'.$modifier_key).'</a></li>
				    	<li><a href="'.base_url('delete/'.$modifier_key).'">'.base_url('delete/'.$modifier_key).'</a></li>
				    	</ul>
				    	</p>
				    	Good luck selling your textbook! Remember to delete your listing after you&#39;ve sold your textbook.
				    	</body>
				    	</html>';
				    $this->email->message($message);
				    $this->email->send();
				    //echo $this->email->print_debugger();

				    // Set macid and fname as cookie info so user can submit multiple textbooks without retyping that info multiple times
				    // Cookies last entire time browser is open
				    $this->input->set_cookie('macid', $macid, 0);
				    $this->input->set_cookie('fname', $this->input->post('fname'), 0);
				    $this->load->view('post_success', array('listing_id' => $listing_id ));
				}
			}
		}
		else 
		{
			//Load the post submission form, form not submitted
			// Create reCaptcha
			// $recaptcha_html = $this->recaptcha->recaptcha_get_html(); // CAPTCHA DISABLED
			$this->load->view('post'/*, array('recaptcha_html' => $recaptcha_html)*/);
		}

	}

	/////////////////////////////////////
	//Custom form validation functions//
	///////////////////////////////////
	

}
