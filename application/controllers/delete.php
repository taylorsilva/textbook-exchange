<?php if ( ! defined('BASEPATH')) exit('');

class Delete extends CI_Controller {


	public function index($modifier_key = '') {
		$this->load->helper('url');
		if ($modifier_key == '') {
			$message = 'No key provided.';
			$this->load->view('delete', array('message' => $message));
		} else {
			$this->db->from('listings')->where('modifier_key', $modifier_key); // SELECT *
			$query = $this->db->get();
			$num_rows = $query->num_rows();
			if ($num_rows == 1) {
				// ID exists, delete record
				// $listing = $query->row();
				$this->db->delete('listings', array('modifier_key' => $modifier_key));
				$message = 'Post deleted!';
				$this->load->view('delete', array('message' => $message));
			}
		}
	}
}
