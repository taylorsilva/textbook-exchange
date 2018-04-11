<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller 
{

	public function index() {
		$this->load->helper('url');
		$this->load->library('session');
		// Index is basically page 1 of the search, page() is duplicate but only works after a search as been made
		$user_search_params['department'] = $this->input->post('department');
		$user_search_params['course_code'] = $this->input->post('course_code');
		$this->session->set_userdata($user_search_params);

		$listings = self::_get_listings_pagination_html($user_search_params['department'], $user_search_params['course_code']);
		$page_data = $listings;
		$this->load->view('search', $page_data);
	}

	public function page($page = 1){
		// shows pages 1+
		$this->load->library('session');
		$this->load->helper('url');
		$department = $this->session->userdata('department');
		$course_code = $this->session->userdata('course_code');
		$limit = $page * 20;
		$listings = self::_get_listings_pagination_html($department, $course_code, $limit, $page);
		$page_data = $listings;
		$this->load->view('search', $page_data);


	}

	private function _verify_post_data($department = FALSE, $course_code = FALSE) {
		// Verifies post data
		if ($department == FALSE OR $course_code == FALSE) { 
			redirect('/');
		}
		// Post data sent, confirm that department and course_code combination exist
		$this->db->distinct();
		$where_1 = array('department' => $department, 'course_code' => $course_code);
		$this->db->where($where_1);
		$verify_dept_code = $this->db->get('courses');
		if ($verify_dept_code->num_rows != 1) {
			redirect('/');		
		}
		return $where_1; //Need this for pagination 
	}

	private function _get_pagination($where1) {
		// Returns the HTML for the pagination 
		$this->load->library('pagination');
		// Set pagination config 
		$pagination_config['base_url'] = base_url('/search/page/'); // Will display like http://website.com/search/page/3
		$pagination_config['per_page'] = 20; // listings to show per page
		$pagination_config['num_links'] = 4; 
		$pagination_config['use_page_numbers'] = TRUE; // Will display page numbers, will have to calc page based on page number
		$pagination_config['full_tag_open'] = '<ul class="pagination">';
		$pagination_config['full_tag_close'] = '</ul>';
		$pagination_config['first_tag_open'] = '<li>';
		$pagination_config['first_tag_close'] = '</li>';
		$pagination_config['last_tag_open'] = '<li>';
		$pagination_config['last_tag_close'] = '</li>';
		$pagination_config['next_tag_open'] = '<li>';
		$pagination_config['next_tag_close'] = '</li>';
		$pagination_config['prev_tag_open'] = '<li>';
		$pagination_config['prev_tag_close'] = '</li>';
		$pagination_config['cur_tag_open'] = '<li class="active"><a href="#">';
		$pagination_config['cur_tag_close'] = '</a></li>';
		$pagination_config['num_tag_open'] = '<li>';
		$pagination_config['num_tag_close'] = '</li>';


		// Determine total number of records this search will return
		$this->db->from('listings');
		$where2 = $where1 + array('activation_status' => '1');
		$this->db->where($where2);
		$num_listings = $this->db->count_all_results();
		if ($num_listings <= 0) {
			// Back to home page
			echo $this->load->view('home', array('error_msg' => 'There are no textbooks being sold for this class. Check back later.'), TRUE);
			exit();
		} else {
			$pagination_config['total_rows'] = $num_listings;
			$this->pagination->initialize($pagination_config); 
			$links_html = $this->pagination->create_links();
			return $links_html;
			
		}
		

	}

	public function _get_listings_pagination_html($department, $course_code, $limit = 20, $page = 1){
		// Returns list of textbooks for sale for this search
		// as well as the pagination html code
		$where1 = self::_verify_post_data($department, $course_code);
		
		$pagination_html = self::_get_pagination($where1);
		$this->db->select('listing_id, price, title, edition, condition, date_submitted');
		$this->db->from('listings');
		$where2 = $where1 + array('activation_status' => '1');
		$this->db->where($where2);
		$this->db->order_by('date_submitted', 'desc'); // Listings sorted by newest records first
		if ($page == 1) {
			$this->db->limit($limit, 0);
		} else {
			$this->db->limit($limit, $limit/$page);
		}
		$listings = $this->db->get();
		return array('listings' => $listings->result(), 'pagination_html' => $pagination_html);
	}
}