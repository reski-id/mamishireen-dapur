<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends CI_Controller {

	var $view = "web";
	var $view_page = "page_mode";

	public function index()
	{
		$this->p();
	}

	// P & DETAIl
	// =======================================================
	public function p($offset=0)
	{
		$limit = (view_mobile()) ? '1' : '5';
		$tbl = 'video';
		$this->db->select('video');
		// $this->db->limit('4');
		$jml_baris = get($tbl)->num_rows();
		$config['base_url'] = base_url()."web/p";
		$config['total_rows']  = $jml_baris;
		$config['per_page']    = $limit; /*Jumlah data yang dipanggil perhalaman*/
		$config['uri_segment'] = 3; /*data selanjutnya di parse diurisegmen 3*/
		/*Class pagination yang digunakan*/
		$config['first_link']       = '<i class="bx bx-chevrons-left"></i>';
		$config['last_link']        = '<i class="bx bx-chevrons-right"></i>';
		$config['next_link']        = '<i class="bx bx-chevron-right"></i>';
		$config['prev_link']        = '<i class="bx bx-chevron-left"></i>';
		$config['full_tag_open']    = '<div class="pagging text-center"><nav aria-label="..."><ul class="pagination pagination-success justify-content-center">';
		$config['full_tag_close']   = '</ul></nav></div>';
		$config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close']    = '</span></li>';
		$config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
		$config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
		$config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['prev_tagl_close']  = '</span>Next</li>';
		$config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['first_tagl_close'] = '</span></li>';
		$config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tagl_close']  = '</span></li>';
		$this->pagination->initialize($config);

			$this->db->select('upper(judul) as judul, video')->where('status',1);
			$this->db->order_by("id_$tbl",'DESC');
			// $this->db->limit('4');
			$query = $this->db->get($tbl,$config['per_page'], $offset);

			$data = array(
				'judul_web' => 'Dapur Mami Shireen',
				'content' 	=> "web/page/beranda/index",
				'halaman'		=> $this->pagination->create_links(),
				'offset'		=> $offset,
				'query'			=> $query,
				'footer'    => true
			);
			$this->load->view("web/index", $data);
	}

	
	// Icon
	public function boxicons()
	{
			$data = array(
				'judul_web' => 'Box Icons',
				'content'		=> "plugin/icon/boxicons",
			);
			$this->load->view("users/index", $data);
	}

	public function test_email()
	{
		check_permission('page', 'create', "setup/email");
		$level = get_session('level');
		if(!isset($level)) { redirect("web/login"); }
		if($level!=0){ redirect('404'); }
		sent_email('test');
	}

	function error_not_found(){
		if (get('menu', array('url'=>uri('x')))->num_rows()!=0) {
			redirect('web/coming-soon?url=dashboard');
		}
		$judul_web = "404 Halaman tidak ditemukan!";
		$this->_page('error_not_found', $judul_web);
	}

	function noscript(){
		$judul_web = "JavaScript tidak aktif dibrowser anda!";
		$this->_page('noscript', $judul_web);
	}

	function maintenance(){
		$judul_web = "Maintenance";
		$this->_page('maintenance', $judul_web);
	}

	function coming_soon(){
		$judul_web = "";
		$this->_page('coming_soon', $judul_web);
	}

	function _page($method='',$judul_web='')
	{
		if(!method_exists($this,$method)){ redirect('404'); }
		if ($judul_web=='') {
			$judul_web = web('title_web');
		}else {
			$judul_web = $judul_web." - ".web('title_web');
		}
		$data = array(
			'judul_web' => $judul_web,
			'content' 	=> "$this->view_page/$method"
		);
		$this->load->view("$this->view_page/index", $data);
	}

}
