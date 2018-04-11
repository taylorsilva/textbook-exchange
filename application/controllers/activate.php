<?php if ( ! defined('BASEPATH')) exit('');

class Activate extends CI_Controller {

	public function index($activation_id = '') {
		$this->load->helper('url');

		if ($activation_id ==  '') {
			$message = 'No activation key provided.';

		} else {
			// Activation ID provided, see if it exists in the Db
			$this->db->select('listing_id')->from('listings')->where('activation_id', $activation_id);
			$query = $this->db->get();
			$num_rows = $query->num_rows();
			if ($num_rows == 1) {
				// Activation ID exists, update activation status to 1 regardless of current value
				$this->db->where('activation_id', $activation_id);
				$this->db->update('listings', array('activation_status' => 1));
				$result = $this->db->affected_rows();
				$row = $query->row();
				if ($result == 1) {
					// The record updated successfully
					
					$message = 'Listing successfully activated. You can view your listing <a href="'.base_url('view/'.$row->listing_id).'">here</a>'; 
				} else {
					$message = 'Error: Your listing is already activated or there was an error updating its activation status.
								<br>See if your listing is active by clicking <a href="'.base_url('view/'.$row->listing_id).'">here</a>';
				}
			} else {
				// Activation ID does not exist
				$message = 'Invalid activation ID.';
			}
		}

		$this->load->view('activate', array('message' => $message));
	}
}