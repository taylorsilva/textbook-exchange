<?php if ( ! defined('BASEPATH')) exit('');

class View extends CI_Controller {


	public function index($listing_id = '') {
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->library('session');
		
		if ($listing_id != '') {
			//Verify valid listing ID
			$this->db->from('listings')->where('listing_id', $listing_id);
			$query = $this->db->get();
			if ($query->num_rows() != 1) {
				//More than one record returned or no records returned, go home
				redirect('/home/', 'refresh');
				exit();
			}
			//Listing ID must be valid, pull all data
			$listing_data = $query->row(); //Only one row should be returned, this returns only one row
			//Can now call data from listing like $listing_data->Column_Name_Here

			//Check that listing has been activated, if not redirect
			if ($listing_data->activation_status == 0) {
				redirect('/home/', 'refresh');
				exit();
			}
			// Create reCaptcha in case buyer wants to send message to seller
			$this->load->library('recaptcha');
			$recaptcha_html = $this->recaptcha->recaptcha_get_html();

			// Get list of other textbooks seller is selling
			$sql = "SELECT department, course_code, price, listing_id FROM listings WHERE macid = '".$listing_data->macid."' AND activation_status = 1 AND listing_id <> '".$listing_id."'";
			$other_seller_textbooks = $this->db->query($sql);

			//Prep data to be passed to view
			$page_data = array('title' => $listing_data->title,
								'seller' => $listing_data->users_fname,
								'department' => $listing_data->department,
								'course_code' => $listing_data->course_code,
								'price' => $listing_data->price,
								'isbn' => $listing_data->isbn,
								'condition' => $listing_data->condition,
								'notes' => $listing_data->notes,
								'edition' => $listing_data->edition,
								'authors' => $listing_data->authors,
								'phone' => $listing_data->phone,
								'recaptcha_html' => $recaptcha_html,
								'listing_id' => $listing_id,
								'other_seller_textbooks' => $other_seller_textbooks->result());
			$this->load->view('view', $page_data);
		} else {
			// $this->load->view('view');
			redirect('/home/', 'refresh');
			exit();
		}
	}
}
