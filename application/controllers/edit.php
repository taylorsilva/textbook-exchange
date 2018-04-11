<?php if ( ! defined('BASEPATH')) exit('');

class Edit extends CI_Controller {


	public function index($modifier_key = '') {
		if ($modifier_key == '') {
			$message = 'No key provided.';
			echo $message;
		} else {
			$this->load->helper('url');
			$this->load->helper('form');

			$this->db->from('listings')->where('modifier_key', $modifier_key); // SELECT *
			$query = $this->db->get();
			$num_rows = $query->num_rows();
			$listing_data = $query->row();

			if ($this->input->post('macid') != FALSE) {
				// Post data sent, update record 
				$where = array('macid' => $this->input->post('macid'),
								'modifier_key' => $modifier_key);
				$this->db->from('listings')->where($where);
				$query = $this->db->get();
				$num_rows = $query->num_rows();
				if ($num_rows == 1) {
					// Only one record returned
					// Verify data user has submitted meets validation rules

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

					if ($this->form_validation->run() == FALSE) {
						$this->load->view('edit', array('listing_data' => $listing_data));
						return;
					}
					// Update record with new information, delta is not checked
					$set = array('users_fname' 	=> $this->input->post('fname'),
								'department' 	=> $this->input->post('department'),
								'course_code' 	=> $this->input->post('course_code'),
								'price' 		=> $this->input->post('price'),
								'title' 		=> $this->input->post('title'),
								'isbn' 			=> $this->input->post('isbn'),
								'condition' 	=> $this->input->post('condition'),
								'notes' 		=> $this->input->post('notes'),
								'edition' 		=> $this->input->post('edition'),
								'authors' 		=> $this->input->post('authors'),
								'date_submitted' => date("Y-m-d H:i:s"),
								'phone' 		=> $this->input->post('phone'),
								'modifier_key'	=> $modifier_key,
								'macid'			=> $macid);
					$this->db->update('listings', $set, $where);
					$successful_message = array('successful_message' =>'Your listing was updated successfully');
					$data = array_merge(array('listing_data' => (object) $set), $successful_message);
					$this->load->view('edit', $data);
					return;
				}
			}
			
			if ($num_rows == 1) {
				// ID exists, pull data for it and send to view to populate form
				$this->load->view('edit', array('listing_data' => $listing_data));
			}
		}
	}
}