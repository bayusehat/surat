<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('surat_model');
	}

	public function index()
	{
		if($this->session->userdata('logged_in') == TRUE){

			if($this->session->userdata('jabatan') == 'Sekretaris'){

				$data['main_view'] 		= 'admin/dashboard_view';
				$data['data_dashboard'] = $this->surat_model->get_data_dashboard();

				$this->load->view('template_view', $data);
			} else {
				$data['main_view'] 		= 'pegawai/disposisi_masuk_view';
				$data['data_disposisi']	= $this->surat_model->get_all_disposisi_masuk($this->session->userdata('id_pegawai'));

				$this->load->view('template_view', $data);
			}

		} else {
			redirect('login');
		}
	}

	//surat masuk

	public function surat_masuk()
	{
		if($this->session->userdata('logged_in') == TRUE){

			if($this->session->userdata('jabatan') == 'Sekretaris'){

				$data['main_view'] = 'admin/data_surat_masuk_sekretaris_view';
				$data['data_surat_masuk'] = $this->surat_model->get_surat_masuk();

				$this->load->view('template_view', $data);
			} else {

			}
		} else {
			redirect('login');
		}
	}

	public function tambah_surat_masuk()
	{
		if($this->session->userdata('logged_in') == TRUE){

			if($this->session->userdata('jabatan') == 'Sekretaris'){

				$this->form_validation->set_rules('no_surat', 'No.Surat', 'trim|required');
				$this->form_validation->set_rules('tgl_kirim', 'Tgl.Kirim', 'trim|required|date');
				$this->form_validation->set_rules('tgl_terima', 'Tgl.Kirim', 'trim|required|date');
				$this->form_validation->set_rules('pengirim', 'Pengirim', 'trim|required');
				$this->form_validation->set_rules('penerima', 'Penerima', 'trim|required');
				$this->form_validation->set_rules('perihal', 'Perihal', 'trim|required');

				if ($this->form_validation->run() == TRUE) {
					//konfigurasi upload file
					$config['upload_path'] 		= './uploads/';
					$config['allowed_types']	= 'pdf';
					$config['max_size']			= 2000;
					$this->load->library('upload', $config);
					
					if ($this->upload->do_upload('file_surat')){
						
						if($this->surat_model->tambah_surat_masuk($this->upload->data()) == TRUE ){
							$this->session->set_flashdata('notif', 'Tambah surat berhasil!');
							redirect('surat/surat_masuk');			
						} else {
							$this->session->set_flashdata('notif', 'Tambah surat gagal!');
							redirect('surat/surat_masuk');			
						}

					} else {
						$this->session->set_flashdata('notif', $this->upload->display_errors());
						redirect('surat/surat_masuk');	
					}

				} else {
					$this->session->set_flashdata('notif', validation_errors());
					redirect('surat/surat_masuk');			
				}
			}
		} else {
			redirect('login');
		}
	}

	public function get_surat_masuk_by_id($id_surat)
	{
		if($this->session->userdata('logged_in') == TRUE){

			if($this->session->userdata('jabatan') == 'Sekretaris'){

				$data_surat_masuk_by_id = $this->surat_model->get_surat_masuk_by_id($id_surat);

				echo json_encode($data_surat_masuk_by_id);
			}
		} else {
			redirect('login');
		}
	}

	public function ubah_surat_masuk()
	{
		if($this->session->userdata('logged_in') == TRUE){

			if($this->session->userdata('jabatan') == 'Sekretaris'){
				$this->form_validation->set_rules('edit_no_surat', 'No.Surat', 'trim|required');
				$this->form_validation->set_rules('edit_no_surat', 'Tgl.Kirim', 'trim|required|date');
				$this->form_validation->set_rules('edit_no_surat', 'Tgl.Kirim', 'trim|required|date');
				$this->form_validation->set_rules('edit_no_surat', 'Pengirim', 'trim|required');
				$this->form_validation->set_rules('edit_no_surat', 'Penerima', 'trim|required');
				$this->form_validation->set_rules('edit_no_surat', 'Perihal', 'trim|required');

				if ($this->form_validation->run() == TRUE) {
						
					if($this->surat_model->ubah_surat_masuk() == TRUE ){
						$this->session->set_flashdata('notif', 'Ubah surat berhasil!');
						redirect('surat/surat_masuk');			
					} else {
						$this->session->set_flashdata('notif', 'Ubah surat gagal!');
						redirect('surat/surat_masuk');			
					}

				} else {
					$this->session->set_flashdata('notif', validation_errors());
					redirect('surat/surat_masuk');			
				}
			}
		} else {
			redirect('login');
		}
	}

	public function ubah_file_surat_masuk(){
		if($this->session->userdata('logged_in') == TRUE){

			if($this->session->userdata('jabatan') == 'Sekretaris'){
					//konfigurasi upload file
				$config['upload_path'] 		= './uploads/';
				$config['allowed_types']	= 'pdf';
				$config['max_size']			= 2000;
				$this->load->library('upload', $config);
				
				if ($this->upload->do_upload('edit_file_surat')){
					
					if($this->surat_model->ubah_file_surat_masuk($this->upload->data()) == TRUE ){
						$this->session->set_flashdata('notif', 'Ubah file surat berhasil!');
						redirect('surat/surat_masuk');			
					} else {
						$this->session->set_flashdata('notif', 'Ubah file surat gagal!');
						redirect('surat/surat_masuk');			
					}

				} else {
					$this->session->set_flashdata('notif', $this->upload->display_errors());
					redirect('surat/surat_masuk');	
				}
			}
		} else {
			redirect('login');
		}
	}

	public function hapus_surat_masuk($id_surat)
	{
		if($this->session->userdata('logged_in') == TRUE){

			if($this->session->userdata('jabatan') == 'Sekretaris'){
				if($this->surat_model->hapus_surat_masuk($id_surat) == TRUE){
					$this->session->set_flashdata('notif', 'Hapus surat Berhasil!');
					redirect('surat/surat_masuk');
				} else {
					$this->session->set_flashdata('notif', 'Hapus surat gagal!');
					redirect('surat/surat_masuk');
				}
			} else {

			}
		} else {
			redirect('login');
		}
	}

	//surat keluar

	public function surat_keluar()
	{
		if($this->session->userdata('logged_in') == TRUE){

			if($this->session->userdata('jabatan') == 'Sekretaris'){

				$data['main_view'] = 'admin/data_surat_keluar_view';
				$data['keluar'] = $this->surat_model->get_data_surat_keluar();
				$this->load->view('template_view',$data);

			} else {

			}
		} else {
			redirect('login');
		}
	}

	public function tambah_surat_keluar()
	{
		if($this->session->userdata('logged_in') == TRUE){

			if($this->session->userdata('jabatan') == 'Sekretaris'){
				$this->form_validation->set_rules('no_surat', 'No.Surat', 'trim|required');
				$this->form_validation->set_rules('tgl_kirim', 'Tgl.Kirim', 'trim|required|date');
				$this->form_validation->set_rules('tgl_terima', 'Tgl.Kirim', 'trim|required|date');
				$this->form_validation->set_rules('pengirim', 'Pengirim', 'trim|required');
				$this->form_validation->set_rules('penerima', 'Penerima', 'trim|required');
				$this->form_validation->set_rules('perihal', 'Perihal', 'trim|required');

				if($this->form_validation->run() == TRUE){
					$config['upload_path'] 		= './uploads/';
					$config['allowed_types']	= 'pdf';
					$config['max_size']			= 2000;
					$this->load->library('upload', $config);

					if($this->upload->do_upload()){
						if($this->surat_keluar->tambah_surat_keluar($this->upload->data())==TRUE){
							$this->session->set_flashdata('notif', 'Tambah surat berhasil!');
							redirect('surat/surat_keluar');
						}else{
							$this->session->set_flashdata('notif', 'Tambah surat gagal!');
							redirect('surat/surat_keluar');
						}
					}else{
						$this->session->set_flashdata('notif',$this->upload->display_errors());
						redirect('surat/surat_keluar');
					}
				}
			}else{
				redirect('login');
			}
		} else {
			redirect('login');
		}
	}

	public function ubah_surat_keluar()
	{
		if($this->session->userdata('logged_in') == TRUE){

			if($this->session->userdata('jabatan') == 'Sekretaris'){

			} else {

			}
		} else {
			redirect('login');
		}
	}

	public function hapus_surat_keluar()
	{
		if($this->session->userdata('logged_in') == TRUE){

			if($this->session->userdata('jabatan') == 'Sekretaris'){

			} else {

			}
		} else {
			redirect('login');
		}
	}

	//disposisi ADMIN/Sekretaris

	public function disposisi($id_surat)
	{
		if($this->session->userdata('logged_in') == TRUE){

			if($this->session->userdata('jabatan') == 'Sekretaris'){

				$data['main_view'] 			= 'admin/disposisi_sekretaris_view';
				$data['data_surat']			= $this->surat_model->get_surat_masuk_by_id($this->uri->segment(3));
				$data['drop_down_jabatan']	= $this->surat_model->get_jabatan();
				$data['data_disposisi']		= $this->surat_model->get_all_disposisi($id_surat);

				$this->load->view('template_view', $data);

			} else {
				$data['main_view'] = 'pegawai/disposisi_masuk_view';

				$this->load->view('template_view', $data);
			}
		} else {
			redirect('login');
		}
	}

	public function tambah_disposisi()
	{
		if($this->session->userdata('logged_in') == TRUE){
			$this->form_validation->set_rules('tujuan_pegawai', 'Tujuan Pegawai', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				if($this->surat_model->tambah_disposisi($this->uri->segment(3)) == TRUE ){
					$this->session->set_flashdata('notif', 'Tambah disposisi berhasil!');
					if($this->session->userdata('jabatan') == 'Sekretaris'){
						redirect('surat/disposisi/'.$this->uri->segment(3));
					} else {
						redirect('surat/disposisi_keluar/'.$this->uri->segment(3));
					}			
				} else {
					$this->session->set_flashdata('notif', 'Tambah disposisi gagal!');
					if($this->session->userdata('jabatan') == 'Sekretaris'){
						redirect('surat/disposisi/'.$this->uri->segment(3));
					} else {
						redirect('surat/disposisi_keluar/'.$this->uri->segment(3));
					}		
				}
			} else {
				$this->session->set_flashdata('notif', validation_errors());
				if($this->session->userdata('jabatan') == 'Sekretaris'){
					redirect('surat/disposisi/'.$this->uri->segment(3));
				} else {
					redirect('surat/disposisi_keluar/'.$this->uri->segment(3));
				}			
			}
		} else {
			redirect('login');
		}
	}

	public function ubah_disposisi()
	{
		
	}

	public function hapus_disposisi($id_surat,$id_disposisi)
	{
		if($this->session->userdata('logged_in') == TRUE){
			if($this->surat_model->hapus_disposisi($id_disposisi) == TRUE){
				$this->session->set_flashdata('notif', 'Hapus disposisi Berhasil!');
				redirect('surat/disposisi/'. $id_surat);
			} else {
				$this->session->set_flashdata('notif', 'Hapus disposisi gagal!');
				redirect('surat/disposisi/'.$id_surat);
			}
		} else {
			redirect('login');
		}
	}

	public function get_pegawai_by_jabatan($id_jabatan)
	{
		if($this->session->userdata('logged_in') == TRUE){
			$data_pegawai = $this->surat_model->get_pegawai_by_jabatan($id_jabatan);

			echo json_encode($data_pegawai);

		} else {
			redirect('login');
		}
	}

	public function get_disposisi_by_id($id_disposisi)
	{
		if($this->session->userdata('logged_in') == TRUE){
			$data_disposisi = $this->surat_model->get_disposisi_by_id($id_disposisi);

			echo json_encode($data_disposisi);

		} else {
			redirect('login');
		}
	}

	// DISPOSISI PEGAWAI

	public function disposisi_keluar($id_surat)
	{
		if($this->session->userdata('logged_in') == TRUE){
			$data['data_disposisi']	= $this->surat_model->get_all_disposisi_keluar($this->session->userdata('id_pegawai'));
			$data['data_surat']	= $this->surat_model->get_surat_masuk_by_id($id_surat);
			$data['drop_down_jabatan']	= $this->surat_model->get_jabatan();
			$data['main_view'] = 'pegawai/disposisi_keluar_view';
			$this->load->view('template_view', $data);
		} else {
			redirect('login');
		}
	}

	public function hapus_disposisi_keluar()
	{
		if($this->session->userdata('logged_in') == TRUE){
			if($this->surat_model->hapus_disposisi_keluar($id_disposisi) == TRUE){
				$this->session->set_flashdata('notif', 'Hapus disposisi Berhasil!');
				redirect('surat/disposisi_keluar');
			} else {
				$this->session->set_flashdata('notif', 'Hapus disposisi Gagal!');
				redirect('surat/disposisi_keluar');
			}

		} else {
			redirect('login');
		}
	}



}

/* End of file Surat.php */
/* Location: ./application/controllers/Surat.php */