<?php if ( ! defined('BASEPATH')) exit('');

class Contact extends CI_Controller 
{
	/*
	This controller sends the seller the buyers message
	*/
	public function index() {
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('recaptcha');

		$seller_macid = $this->input->post('macid');
		$message = str_replace(array("\r", "\n"), "<br>", $this->input->post('message')); //Replace all carriage returns with <br> for the email

		$listing_id = $this->input->post('listing_id');

		// Form validation rules
		$this->form_validation->set_rules('macid', 'MacID', 'required|min_length[3]|_user_enetered_email');
		$this->form_validation->set_rules('message', 'Message', 'required|min_length[8]');

		if ($this->form_validation->run() == FALSE)
		{
			echo "here1";
			//Form input not valid, errors returned
			$form_errors = validation_errors();
			$this->session->set_flashdata('form_errors', $form_errors);
			//Check reCaptcha, this is to ensure all possible errors are shown
			$this->recaptcha->recaptcha_check_answer();
			if(!$this->recaptcha->getIsValid()) //If getValid returns false
			{
				echo "here2";
	            $this->session->set_flashdata('captcha_error','Incorrect captcha');
	            redirect('/view/'.$listing_id);
	            exit();
	        }
			redirect('/view/'.$listing_id);
			exit();
		}

		//Check reCaptcha
		$this->recaptcha->recaptcha_check_answer();
		if(!$this->recaptcha->getIsValid()) //If getValid returns false
		{
            $this->session->set_flashdata('captcha_error','Incorrect captcha');
            redirect('/view/'.$listing_id);
            exit();
        }

		/**************
		Send seller the email
		**************/
		
		$this->load->library('email');
		$query = $this->db->get_where('listings', array('listing_id' => $listing_id));
		$listing = $query->result()[0]; //Returns 1st record, only one should be returned since listing_id is unique
		/*
		For reference, this is what you can access with $listing object
		$listing->listing_id;
		$listing->modifier_key;
		$listing->macid;
		$listing->users_fname;
		$listing->department;
		$listing->course_code;
		$listing->price;
		$lisitng->title;
		$listing->isbn;
		$listing->condition;
		$listing->notes;
		$listing->edition;
		$listing->authors;
		*/
		
		//Send to seller
		$this->email->clear();
		$this->email->from('noreply@mactextbooks.ca', 'MacTextbooks.ca');
		$this->email->to($listing->macid.'@mcmaster.ca');
		$this->email->reply_to($seller_macid.'@mcmaster.ca');
		$this->email->subject('Textbook Exchange - Response to '.$listing->title);
		$this->email->message('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><body><strong>'
			.$seller_macid.' is interested in your textbook for <a href="'.base_url('view/'.$listing_id).'">'.$listing->department.' '.$listing->course_code.
			'</a>. Their message is below.</strong><br><p>'.$message.'</p><br><strong>You can respond to their message by replying to this email.</strong></body></html>');
		$this->email->send();
		//Send to buyer
		$this->email->clear();
		$this->email->from('noreply@mactextbooks.ca', 'MacTextbooks.ca');
		$this->email->to($seller_macid . '@mcmaster.ca');
		$this->email->subject('Textbook Exchange - You sent a message to '.$listing->title);
		$this->email->message('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><body><strong>
			You responded to a listing: <a href="'.base_url('view/'.$listing_id).'">'.$listing->title.'</a><br>Please wait for a response from the seller.
			A copy of the message you sent is below.</strong><br><p>'.$message.'</p></body></html>');
		$this->email->send();
		
		$this->load->view('contact');
		//echo $this->email->print_debugger();

		}
	}
