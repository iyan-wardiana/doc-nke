<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 2 Febrruari 2018
 * File Name	= Reservation.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reservation  extends CI_Controller
{
 	// Start : Index tiap halaman
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('reservation/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	public function index1($offset=0)
	{
		$this->load->model('m_reservation', '', TRUE);
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$data['title'] 				= $appName;
			$data['secAddURL'] 			= site_url('reservation/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('reservation/get_list_type/?id='.$this->url_encryption_helper->encode_url($appName));

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'L';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_reservation', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	// End

	function addMR() // OK
	{
		$this->load->model('m_reservation', '', TRUE);

		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['form_action']		= site_url('reservation/add_process');
			$data['backURL'] 			= site_url('reservation/');

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'A';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_reservation_mr_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function add_process() // OK
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");

			$this->db->trans_begin();

			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

			$RSV_CODE 	= $this->input->post('RSV_CODE');
			$RSV_STARTD	= date('Y-m-d', strtotime($this->input->post('RSV_STARTD')));
			$RSV_ENDD	= date('Y-m-d', strtotime($this->input->post('RSV_ENDD')));
			$RSV_STARTT = $this->input->post('RSV_STARTT');
			$RSV_ENDT	= $this->input->post('RSV_ENDT');
			$RSV_STAT	= $this->input->post('RSV_STAT');

			$RSV_STARTD	= "$RSV_STARTD $RSV_STARTT";
			$RSV_ENDD	= "$RSV_ENDD $RSV_ENDT";

			$InsRes		= array('RSV_CODE' 		=> $this->input->post('RSV_CODE'),
								'RSV_CATEG' 	=> $this->input->post('RSV_CATEG'),
								'CATEG_CODE' 	=> $this->input->post('CATEG_CODE'),
								'DRIVER_CODE'	=> $this->input->post('DRIVER_CODE'),
								'RSV_STARTD' 	=> $RSV_STARTD,
								'RSV_ENDD'		=> $RSV_ENDD,
								'RSV_STARTT' 	=> $RSV_STARTT,
								'RSV_ENDT'		=> $RSV_ENDT,
								'CATEG_CODE2' 	=> $this->input->post('CATEG_CODE'),
								'RSV_STARTD2' 	=> $RSV_STARTD,
								'RSV_ENDD2'		=> $RSV_ENDD,
								'RSV_STARTT2' 	=> $RSV_STARTT,
								'RSV_ENDT2'		=> $RSV_ENDT,
								'RSV_TITLE'		=> $this->input->post('RSV_TITLE'),
								'RSV_DESC'		=> $this->input->post('RSV_DESC'),
								'RSV_QTY'		=> $this->input->post('RSV_QTY'),
								'RSV_SUBMITTER'	=> $this->input->post('RSV_SUBMITTER'),
								'RSV_MAIL'		=> $this->input->post('RSV_MAIL'),
								'RSV_EMPID'		=> $DefEmp_ID,
								'RSV_CREATED'	=> date('Y-m-d H:i:s'),
								'RSV_STAT'		=> $this->input->post('RSV_STAT'));
			$this->m_reservation->add($InsRes);

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'C';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}

			$url			= site_url('reservation/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}

	function update() // OK
	{
		$this->load->model('m_reservation', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$RSV_CODE	= $_GET['id'];
			$RSV_CODE	= $this->url_encryption_helper->decode_url($RSV_CODE);

			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('reservation/update_process');
			$data['backURL'] 		= site_url('reservation/');

			$getRESERV				= $this->m_reservation->get_RESERV($RSV_CODE)->row();

			$data['default']['RSV_CODE']		= $getRESERV->RSV_CODE;
			$data['default']['RSV_CATEG']		= $getRESERV->RSV_CATEG;
			$RSV_CATEG							= $getRESERV->RSV_CATEG;
			$data['default']['CATEG_CODE']		= $getRESERV->CATEG_CODE;
			$data['default']['DRIVER_CODE']		= $getRESERV->DRIVER_CODE;
			$DRIVER_CODE						= $getRESERV->DRIVER_CODE;

			$CATEG_CODE							= $getRESERV->CATEG_CODE;
			if($CATEG_CODE != '' && $RSV_CATEG == 'VH')
			{
				$getVehicle							= $this->m_reservation->get_VEHICLE($CATEG_CODE)->row();
				$data['default']['VH_CODE']			= $getVehicle->VH_CODE;
				$data['default']['VH_TYPE']			= $getVehicle->VH_TYPE;
				$data['default']['VH_MEREK']		= $getVehicle->VH_MEREK;
				$data['default']['VH_NOPOL']		= $getVehicle->VH_NOPOL;
				$data['default']['VH_STAT']			= $getVehicle->VH_STAT;
			}
			if($DRIVER_CODE != '' && $RSV_CATEG == 'VH')
			{
				$getDriver							= $this->m_reservation->get_DRIVER($DRIVER_CODE)->row();
				$data['default']['DRIVER_CODE']		= $getDriver->DRIVER_CODE;
				$data['default']['DRIVER']			= $getDriver->DRIVER;
				$data['default']['DRIVER_STAT']		= $getDriver->DRIVER_STAT;
			}


			$data['default']['RSV_STARTD']		= $getRESERV->RSV_STARTD;
			$data['default']['RSV_ENDD']		= $getRESERV->RSV_ENDD;
			$data['default']['RSV_STARTT']		= $getRESERV->RSV_STARTT;
			$data['default']['RSV_ENDT']		= $getRESERV->RSV_ENDT;
			$data['default']['RSV_TITLE']		= $getRESERV->RSV_TITLE;
			$data['default']['RSV_DESC']		= $getRESERV->RSV_DESC;
			$data['default']['RSV_QTY']			= $getRESERV->RSV_QTY;
			$data['default']['RSV_MEMO']		= $getRESERV->RSV_MEMO;
			$data['default']['RSV_SUBMITTER']	= $getRESERV->RSV_SUBMITTER;
			$data['default']['RSV_MAIL']		= $getRESERV->RSV_MAIL;
			$data['default']['RSV_STAT']		= $getRESERV->RSV_STAT;

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'U';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			//cek RSV_CATEG
			if($RSV_CATEG == 'MR')
			{
				$this->load->view('v_reservation/v_reservation_mr_form', $data);
			}
			else if($RSV_CATEG == 'VH')
			{
				$this->load->view('v_reservation/v_reservation_vc_form', $data);
			}
			else if($RSV_CATEG == 'RR')
			{
				$this->load->view('v_reservation/v_reservation_rr_form', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}

	function update_process()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");

			$this->db->trans_begin();

			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$RSV_CODE 	= $this->input->post('RSV_CODE');
			$RSV_STARTD	= date('Y-m-d', strtotime($this->input->post('RSV_STARTD')));
			$RSV_ENDD	= date('Y-m-d', strtotime($this->input->post('RSV_ENDD')));
			$RSV_STARTT = $this->input->post('RSV_STARTT');
			$RSV_ENDT	= $this->input->post('RSV_ENDT');
			$RSV_STAT	= $this->input->post('RSV_STAT');

			$RSV_STARTD	= "$RSV_STARTD $RSV_STARTT";
			$RSV_ENDD	= "$RSV_ENDD $RSV_ENDT";

			$UpdRes		= array('RSV_CODE' 		=> $this->input->post('RSV_CODE'),
								'RSV_CATEG' 	=> $this->input->post('RSV_CATEG'),
								'CATEG_CODE' 	=> $this->input->post('CATEG_CODE'),
								'DRIVER_CODE'	=> $this->input->post('DRIVER_CODE'),
								'RSV_STARTD' 	=> $RSV_STARTD,
								'RSV_ENDD'		=> $RSV_ENDD,
								'RSV_STARTT' 	=> $RSV_STARTT,
								'RSV_ENDT'		=> $RSV_ENDT,
								'CATEG_CODE2' 	=> $this->input->post('CATEG_CODE'),
								'RSV_STARTD2' 	=> $RSV_STARTD,
								'RSV_ENDD2'		=> $RSV_ENDD,
								'RSV_STARTT2' 	=> $RSV_STARTT,
								'RSV_ENDT2'		=> $RSV_ENDT,
								'RSV_TITLE'		=> $this->input->post('RSV_TITLE'),
								'RSV_DESC'		=> $this->input->post('RSV_DESC'),
								'RSV_QTY'		=> $this->input->post('RSV_QTY'),
								'RSV_EMPID'		=> $DefEmp_ID,
								'RSV_CREATED'	=> date('Y-m-d H:i:s'),
								'RSV_SUBMITTER'	=> $this->input->post('RSV_SUBMITTER'),
								'RSV_MAIL'		=> $this->input->post('RSV_MAIL'),
								'RSV_STAT'		=> $this->input->post('RSV_STAT'));

			$this->m_reservation->update($RSV_CODE, $UpdRes);

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'UP';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}

			$url			= site_url('reservation/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}

	function listMR()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$REQ_STAT	= $_GET['id'];
			$REQ_STAT	= $this->url_encryption_helper->decode_url($REQ_STAT);
			if($REQ_STAT == 1)
			{
				$REQ_STAT	= '1';
			}
			elseif($REQ_STAT == 3)
			{
				$REQ_STAT	= '3,4';
			}

			$data['title'] 		= $appName;
			$data['secAddURL'] 	= site_url('reservation/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('reservation/');
			$data['REQ_STAT'] 	= $REQ_STAT;

			$data["MRCount"] 	= $this->m_reservation->count_all_MR($DefEmp_ID, $REQ_STAT);
			$data['vwMR']		= $this->m_reservation->get_all_MR($DefEmp_ID, $REQ_STAT)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'MRS-L';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_reservation_mr', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function listMR_view()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$REQ_STAT	= $_GET['id'];
			$REQ_STAT	= $this->url_encryption_helper->decode_url($REQ_STAT);
			if($REQ_STAT == 1)
			{
				$REQ_STAT	= '1';
			}
			elseif($REQ_STAT == 3)
			{
				$REQ_STAT	= '3,4';
			}

			$data['title'] 		= $appName;
			$data['secAddURL'] 	= site_url('reservation/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('reservation/');
			$data['REQ_STAT'] 	= $REQ_STAT;

			$data["MRCount"] 	= $this->m_reservation->count_all_MR($DefEmp_ID, $REQ_STAT);
			$data['vwMR']		= $this->m_reservation->get_all_MR($DefEmp_ID, $REQ_STAT)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'MRS-L';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_reservation_mr_list_user', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function listYourMR()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$DefEmp_ID	= $_GET['id'];
			$DefEmp_ID	= $this->url_encryption_helper->decode_url($DefEmp_ID);

			$data['title'] 		= $appName;
			$data['secAddURL'] 	= site_url('reservation/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('reservation/');
			$data['REQ_STAT'] 	= 99;

			$data["MRCount"] 	= $this->m_reservation->count_all_YourMR($DefEmp_ID);
			$data['vwMR']		= $this->m_reservation->get_all_YourMR($DefEmp_ID)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'YMRS-L';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK


			$this->load->view('v_reservation/v_reservation_mr', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function listYourVH()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$DefEmp_ID	= $_GET['id'];
			$DefEmp_ID	= $this->url_encryption_helper->decode_url($DefEmp_ID);

			$data['title'] 		= $appName;
			$data['secAddURL'] 	= site_url('reservation/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('reservation/');
			$data['REQ_STAT'] 	= 99;

			$data["VHCount"] 	= $this->m_reservation->count_all_YourVH($DefEmp_ID);
			$data['vwVH']		= $this->m_reservation->get_all_YourVH($DefEmp_ID)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'YVHS-L';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_reservation_vh', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function listYourRR()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$DefEmp_ID	= $_GET['id'];
			$DefEmp_ID	= $this->url_encryption_helper->decode_url($DefEmp_ID);

			$data['title'] 		= $appName;
			$data['secAddURL'] 	= site_url('reservation/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('reservation/');
			$data['REQ_STAT'] 	= 99;

			$data["RRCount"] 	= $this->m_reservation->count_all_YourRR($DefEmp_ID);
			$data['vwRR']		= $this->m_reservation->get_all_YourRR($DefEmp_ID)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'YMRS-L';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK


			$this->load->view('v_reservation/v_reservation_rr', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function listMRAvail()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$REQ_STAT	= $_GET['id'];
			$REQ_STAT	= $this->url_encryption_helper->decode_url($REQ_STAT);
			if($REQ_STAT == 1)
			{
				$REQ_STAT	= '1,4';
			}

			$data['title'] 		= $appName;
			$data['secAddURL'] 	= site_url('reservation/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('reservation/');

			$data["MRCount"] 	= $this->m_reservation->count_all_MRAvail($DefEmp_ID, $REQ_STAT);
			$data['vwMR']		= $this->m_reservation->get_all_MRAvail($DefEmp_ID, $REQ_STAT)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'MRA-L';		// MR AVAILABLE

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_reservation_mr_avail', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function listVHAvail()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			/*
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$REQ_STAT	= $_GET['id'];
			$REQ_STAT	= $this->url_encryption_helper->decode_url($REQ_STAT);
			if($REQ_STAT == 1)
			{
				$REQ_STAT	= '1,4';
			}
			*/

			$data['title'] 		= $appName;
			$data['secAddURL'] 	= site_url('reservation/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('reservation/');

			$data["VHCount"] 	= $this->m_reservation->count_all_VHAvail();
			$data['vwVH']		= $this->m_reservation->get_all_VHAvail()->result_array();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'VHA-L';		// MR AVAILABLE

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_reservation_vh_avail', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

 	function bookinglist()
	{
		$this->load->model('m_reservation', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$this->m_reservation->update_close_Expire();

		$url			= site_url('reservation/bklst1a2xe/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function bklst1a2xe()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			date_default_timezone_set("Asia/Jakarta");

			$data['title'] 		= $appName;
			$data['secAddURL'] 	= site_url('reservation/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('reservation/');

			$data["BLCount"] 	= $this->m_reservation->count_all_BL();
			$data['vwBL']		= $this->m_reservation->get_all_BL()->result();

			//$this->m_reservation->update_close_Expire();



			// START : UPDATE TO T-TRACK

				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'BL-L';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_reservation_mr_adm', $data);
			//$this->load->view('v_reservation/v_reservation_adm', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function bookinglistVH()
	{
		$this->load->model('m_reservation', '', TRUE);

		$this->m_reservation->update_close_Expire();

		$url			= site_url('reservation/bklst1a2xeVH/?id='.$this->url_encryption_helper->encode_url(""));
		redirect($url);
	}

	function bklst1a2xeVH()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$RSV_STAT			= $_GET['id'];
			$ID					= $this->url_encryption_helper->decode_url($RSV_STAT);
			$RSV_STAT			= $ID;
			$data['RSV_STAT']	= $RSV_STAT;

			//echo "Test : $RSV_STAT";
			//return false;
			if($RSV_STAT == "")
			{
				$data['isReadyAdd'] 	= 0;
			}
			else
			{
				$data['isReadyAdd'] 	= 1;
				$data['RSV_STAT_DEF']	= $RSV_STAT;
			}

			$data['title'] 		= $appName;
			$data['h1_title']	= 'Daftar Pemesanan';
			$data['h2_title']	= 'Kendaraan';
			$data['edit_eventVH']		= site_url('reservation/edit_eventVH_process');
			$data['secAddURL'] 	= site_url('reservation/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('reservation/');
			$data["MenuCode"] 	= 'MN360';

			$data['BLVH_C']		= $this->m_reservation->count_BLVH($RSV_STAT);
			$data['BLVH_V']		= $this->m_reservation->get_BLVH($RSV_STAT)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'BLVH-L';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_reservation_vh_adm', $data);
			//$this->load->view('v_reservation/v_calendar_vehicle_adm', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function bookinglistRR()
	{
		$this->load->model('m_reservation', '', TRUE);

		$this->m_reservation->update_close_Expire();

		$url			= site_url('reservation/bklst1a2xeRR/?id='.$this->url_encryption_helper->encode_url(""));
		redirect($url);
	}

	function bklst1a2xeRR()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$RSV_STAT			= $_GET['id'];
			$ID					= $this->url_encryption_helper->decode_url($RSV_STAT);
			$RSV_STAT			= $ID;
			$data['RSV_STAT']	= $RSV_STAT;

			//echo "Test : $RSV_STAT";
			//return false;
			if($RSV_STAT == "")
			{
				$data['isReadyAdd'] 	= 0;
			}
			else
			{
				$data['isReadyAdd'] 	= 1;
				$data['RSV_STAT_DEF']	= $RSV_STAT;
			}

			$data['title'] 		= $appName;
			$data['h1_title']	= 'Daftar Pemesanan';
			$data['h2_title']	= 'Apartement';
			$data['edit_eventRR']		= site_url('reservation/edit_eventRR_process');
			$data['secAddURL'] 	= site_url('reservation/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('reservation/');

			$data['BLRR_C']		= $this->m_reservation->count_BLRR($RSV_STAT);
			$data['BLRR_V']		= $this->m_reservation->get_BLRR($RSV_STAT)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'BLRR-L';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_reservation_rr_adm', $data);
			//$this->load->view('v_reservation/v_calendar_vehicle_adm', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function update_adm() // OK
	{
		$this->load->model('m_reservation', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;


		if ($this->session->userdata('login') == TRUE)
		{
			$Cell_RSV	= $_GET['id'];
			$Cell_RSV	= $this->url_encryption_helper->decode_url($Cell_RSV);
			$EXPL			= explode('~', $Cell_RSV);
			$RSV_CODE		= $EXPL[0];
			$CATEG_CODE2X	= $EXPL[1];
			$RSV_STARTD2X	= $EXPL[2];
			$RSV_ENDD2X		= $EXPL[3];

			$data['alertMR']		= "";

			if($CATEG_CODE2X == 'MR-180001')
			{
				$CATEG_CODE2X = "'MR-180001','MR-180008','MR-180010'";
			}
			elseif($CATEG_CODE2X == 'MR-180002')
			{
				$CATEG_CODE2X = "'MR-180002','MR-180008','MR-180009','MR-180010'";
			}
			elseif($CATEG_CODE2X == 'MR-180003')
			{
				$CATEG_CODE2X = "'MR-180003','MR-180009','MR-180010'";
			}
			elseif($CATEG_CODE2X == 'MR-180008')
			{
				$CATEG_CODE2X = "'MR-180001','MR-180002','MR-180008','MR-180009','MR-180010'";
			}
			elseif($CATEG_CODE2X == 'MR-180009')
			{
				$CATEG_CODE2X = "'MR-180002','MR-180003','MR-180008','MR-180009','MR-180010'";
			}
			elseif($CATEG_CODE2X == 'MR-180010')
			{
				$CATEG_CODE2X = "'MR-180001','MR-180002','MR-180003','MR-180008','MR-180009','MR-180010'";
			}
			else
			{
				$CATEG_CODE2X = "'$CATEG_CODE2X'";
			}

			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];


			//Cek kondisi jika ada reservasi yang overlap
			$COV_MR		= "tbl_reservation WHERE RSV_STARTD2 BETWEEN '$RSV_STARTD2X' AND '$RSV_ENDD2X'
						   AND CATEG_CODE2 IN ($CATEG_CODE2X) AND RSV_STAT = 2
						   OR RSV_ENDD2 BETWEEN '$RSV_STARTD2X' AND '$RSV_ENDD2X'
						   AND CATEG_CODE2 IN ($CATEG_CODE2X) AND RSV_STAT = 2";
			$VOV_MR		= "SELECT * FROM tbl_reservation WHERE RSV_STARTD2 BETWEEN '$RSV_STARTD2X' AND '$RSV_ENDD2X'
						   AND CATEG_CODE2 IN ($CATEG_CODE2X) AND RSV_STAT = 2
						   OR RSV_ENDD2 BETWEEN '$RSV_STARTD2X' AND '$RSV_ENDD2X'
						   AND CATEG_CODE2 IN ($CATEG_CODE2X) AND RSV_STAT = 2";
			$ResCOV_MR	= $this->db->count_all($COV_MR);
			$ResVOV_MR	= $this->db->query($VOV_MR)->result();
			$data['ResCOV_MR_a'] = 0;
			if($ResCOV_MR > 0)
			{
				foreach($ResVOV_MR as $rowVOV_MR)
				{
					$RSV_STARTD2_a 	= $rowVOV_MR->RSV_STARTD2;
					$RSV_ENDD2_a	= $rowVOV_MR->RSV_ENDD2;
					$CATEG_CODE2_a	= $rowVOV_MR->CATEG_CODE2;

					if($CATEG_CODE2_a == 'MR-180001')
					{
						$CATEG_CODE2_a = "'MR-180001','MR-180008','MR-180010'";
					}
					elseif($CATEG_CODE2_a == 'MR-180002')
					{
						$CATEG_CODE2_a = "'MR-180002','MR-180008','MR-180009','MR-180010'";
					}
					elseif($CATEG_CODE2_a == 'MR-180003')
					{
						$CATEG_CODE2_a = "'MR-180003','MR-180009','MR-180010'";
					}
					elseif($CATEG_CODE2_a == 'MR-180008')
					{
						$CATEG_CODE2_a = "'MR-180001','MR-180002','MR-180008','MR-180009','MR-180010'";
					}
					elseif($CATEG_CODE2_a == 'MR-180009')
					{
						$CATEG_CODE2_a = "'MR-180002','MR-180003','MR-180008','MR-180009','MR-180010'";
					}
					elseif($CATEG_CODE2_a == 'MR-180010')
					{
						$CATEG_CODE2_a = "'MR-180001','MR-180002','MR-180003','MR-180008','MR-180009','MR-180010'";
					}
					else
					{
						$CATEG_CODE2_a = "'$CATEG_CODE2_a'";
					}


					$COV_MR_a		= "tbl_reservation WHERE RSV_STARTD2 BETWEEN '$RSV_STARTD2_a' AND '$RSV_ENDD2_a'
									   AND CATEG_CODE2 IN ($CATEG_CODE2_a) AND RSV_STAT = 3
									   OR RSV_ENDD2 BETWEEN '$RSV_STARTD2_a' AND '$RSV_ENDD2_a'
									   AND CATEG_CODE2 IN ($CATEG_CODE2_a) AND RSV_STAT = 3";
					$VOV_MR_a		= "SELECT * FROM tbl_reservation WHERE RSV_STARTD2 BETWEEN '$RSV_STARTD2_a' AND '$RSV_ENDD2_a'
									   AND CATEG_CODE2 IN ($CATEG_CODE2_a) AND RSV_STAT = 3
									   OR RSV_ENDD2 BETWEEN '$RSV_STARTD2_a' AND '$RSV_ENDD2_a'
									   AND CATEG_CODE2 IN ($CATEG_CODE2_a) AND RSV_STAT = 3";
					$ResCOV_MR_a	= $this->db->count_all($COV_MR_a);
					//$data['ResCOV_MR_a'] = $ResCOV_MR_a;
					$ResVOV_MR_a	= $this->db->query($VOV_MR_a)->result();

					//$data['ResVOV_MR_a'] = $ResVOV_MR_a;
					if($ResCOV_MR_a > 0)
					{
						foreach($ResVOV_MR_a as $rowVOV_MR_a)
						{
							$RSV_CODE_b		= $rowVOV_MR_a->RSV_CODE;
							$RSV_CATEG_b	= $rowVOV_MR_a->RSV_CATEG;
							if($RSV_CATEG_b == 'MR')
							{
								$Clean_Code		= "$RSV_CODE_b-ADD";
								$C_ClnMR	= "tbl_reservation WHERE RSV_CODE = '$Clean_Code'";
								$V_ClnMR	= "SELECT * FROM tbl_reservation WHERE RSV_CODE = '$Clean_Code'";
								$ResC_ClnMR	= $this->db->count_all($C_ClnMR);
								$ResV_ClnMR	= $this->db->query($V_ClnMR)->result();
								if($ResC_ClnMR > 0)
								{
									//Get Waktu pembersihan ruangan
									foreach($ResV_ClnMR as $rowV_ClnMR)
									{
										$data['alertMR'] = '<div class="alert alert-warning alert-dismissible">
										  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										  <h4><i class="icon fa fa-warning"></i> Peringatan!</h4>
										  Untuk pemesanan ini akan digunakan oleh Bpk/Ibu <b>'.$rowVOV_MR_a->RSV_SUBMITTER.'</b>
										  pada waktu '.$rowVOV_MR_a->RSV_STARTT2.' s/d '.$rowVOV_MR_a->RSV_ENDT2.' dengan ID : '.$rowVOV_MR_a->RSV_CODE.' dan untuk pembersihan ruang meeting dimulai pada waktu '.$rowV_ClnMR->RSV_STARTT2.' s/d '.$rowV_ClnMR->RSV_ENDT2.', silahkan untuk reschedule ulang.
										</div>';
									}
								}
							}
							elseif($RSV_CATEG_b == 'VH')
							{
								//get data kendaraan
								$VH_CODE		= $rowVOV_MR_a->CATEG_CODE2;
								$RSV_STARTD_dt	= date('Y-m-d', strtotime($rowVOV_MR_a->RSV_STARTD2));
								$RSV_ENDD_dt	= date('Y-m-d', strtotime($rowVOV_MR_a->RSV_ENDD2));
								$RSV_STARTD_tm	= date('H:i', strtotime($rowVOV_MR_a->RSV_STARTD2));
								$RSV_ENDD_tm	= date('H:i', strtotime($rowVOV_MR_a->RSV_ENDD2));
								$QC_VH			= "tbl_vehicle WHERE VH_CODE = '$VH_CODE'";
								$ResC_VH		= $this->db->count_all($QC_VH);
								if($ResC_VH > 0)
								{
									$v_VH		= "SELECT * FROM tbl_vehicle WHERE VH_CODE = '$VH_CODE'";
									$ResV_VH	= $this->db->query($v_VH)->result();
									foreach($ResV_VH as $rowVH)
									{
										$data['alertMR'] = '<div class="alert alert-warning alert-dismissible">
										  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										  <h4><i class="icon fa fa-warning"></i> Peringatan!</h4>
										  Untuk pemesanan kendaraan '.$rowVH->VH_MEREK.' dengan plat nomor '.$rowVH->VH_NOPOL.'
										  sudah digunakan pada tanggal '.$RSV_STARTD_dt.'
										  s/d '.$RSV_ENDD_dt.' dan dari jam '.$RSV_STARTD_tm.' s/d '.$RSV_ENDD_tm.', silahkan untuk reschedule ulang.
										</div>';
									}
								}
							}
						}
					}
				}
			}

			//echo $ResCOV_MR;

			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('reservation/update_adm_process');
			$data["MenuCode"] 		= 'MN360';

			$data['CountUND']		= $this->m_reservation->count_und_employee();
			$data['vwUND']			= $this->m_reservation->get_und_employee()->result_array();
			$getRESERV				= $this->m_reservation->get_RESERV($RSV_CODE)->row();

			$data['default']['RSV_CODE']		= $getRESERV->RSV_CODE;
			$data['default']['RSV_CATEG']		= $getRESERV->RSV_CATEG;
			$data['default']['CATEG_CODE']		= $getRESERV->CATEG_CODE2;
			$RSV_CATEG							= $getRESERV->RSV_CATEG;
			$data['default']['DRIVER_CODE']		= $getRESERV->DRIVER_CODE;
			$DRIVER_CODE						= $getRESERV->DRIVER_CODE;


			$CATEG_CODE							= $getRESERV->CATEG_CODE2;

			if($RSV_CATEG == 'VH')
			{
				$data['backURL'] 		= site_url('reservation/bookinglistVH');
			}
			else
			{
				$data['backURL'] 		= site_url('reservation/bookinglist');
			}
			//echo $CATEG_CODE;
			//return false;
			if($CATEG_CODE != '' && $RSV_CATEG == 'VH')
			{
				//$CATEG_CODE_a	= str_replace("'","",$CATEG_CODE2X);
				$CATEG_CODE_a = preg_replace('/[^A-Za-z0-9\-]/', '', $CATEG_CODE2X);
				//return false;
				$getVehicle							= $this->m_reservation->get_VEHICLE($CATEG_CODE_a)->row();
				$data['default']['VH_CODE']			= $getVehicle->VH_CODE;
				$data['default']['VH_TYPE']			= $getVehicle->VH_TYPE;
				$data['default']['VH_MEREK']		= $getVehicle->VH_MEREK;

				$data['default']['VH_NOPOL']		= $getVehicle->VH_NOPOL;
				$data['default']['VH_STAT']			= $getVehicle->VH_STAT;
			}
			if($DRIVER_CODE != '' && $RSV_CATEG == 'VH')
			{
				$getDriver							= $this->m_reservation->get_DRIVER($DRIVER_CODE)->row();
				$data['default']['DRIVER_CODE']		= $getDriver->DRIVER_CODE;
				$data['default']['DRIVER']			= $getDriver->DRIVER;
				$data['default']['DRIVER_STAT']		= $getDriver->DRIVER_STAT;
			}

			$data['default']['RSV_STARTD']		= $getRESERV->RSV_STARTD;
			$data['default']['RSV_ENDD']		= $getRESERV->RSV_ENDD;
			$data['default']['RSV_STARTT']		= $getRESERV->RSV_STARTT;
			$data['default']['RSV_ENDT']		= $getRESERV->RSV_ENDT;

			$data['default']['RSV_STARTD2']		= $getRESERV->RSV_STARTD2;
			$data['default']['RSV_ENDD2']		= $getRESERV->RSV_ENDD2;
			$data['default']['RSV_STARTT2']		= $getRESERV->RSV_STARTT2;
			$data['default']['RSV_ENDT2']		= $getRESERV->RSV_ENDT2;

			$data['default']['RSV_TITLE']		= $getRESERV->RSV_TITLE;
			$data['default']['RSV_DESC']		= $getRESERV->RSV_DESC;
			$data['default']['RSV_QTY']			= $getRESERV->RSV_QTY;
			$data['default']['RSV_MEMO']		= $getRESERV->RSV_MEMO;
			$data['default']['RSV_SUBMITTER']	= $getRESERV->RSV_SUBMITTER;
			$data['default']['RSV_MAIL']		= $getRESERV->RSV_MAIL;
			$data['default']['RSV_INVIT']		= $getRESERV->RSV_INVIT;
      $data['default']['RSV_INVIT_Emp']		= $getRESERV->RSV_INVIT_Emp;
			$data['default']['RSV_STAT']		= $getRESERV->RSV_STAT;

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'U';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			if($RSV_CATEG == 'MR')
			{
				$this->load->view('v_reservation/v_reservation_mr_adm_form', $data);
			}
			elseif($RSV_CATEG == 'VH')
			{
				$this->load->view('v_reservation/v_reservation_vh_adm_form', $data);
			}
			elseif($RSV_CATEG == 'RR')
			{
				$this->load->view('v_reservation/v_reservation_rr_adm_form', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}

	function GetCountMR($RSV_CODE)
	{
		//load model
		$this->load->model('m_reservation','',TRUE);

		$result = $this->m_reservation->GetCountMR_Ovr($RSV_CODE);
		//$result = 0;
		echo $result;
	}

	function GetCountVH($RSV_CODE)
	{
		//load model
		$this->load->model('m_reservation','',TRUE);

		$result = $this->m_reservation->GetCountVH_Ovr($RSV_CODE);
		//$result = 0;
		echo $result;
	}

	function GetCountMR_y()
	{
		//load model
		$this->load->model('m_reservation','',TRUE);
		$arrMR			= $_GET['id'];
		$EXPL			= explode('~', $arrMR);
		$RSV_STARTD		= $EXPL[0];
		$RSV_ENDD		= $EXPL[1];
		$CATEG_CODE		= $EXPL[2];
		$result = $this->m_reservation->GetCountMR_Ovr_y($RSV_STARTD, $RSV_ENDD, $CATEG_CODE);
		//$result = 0;
		echo $result;
	}

	function GetCountVH_y()
	{
		//load model
		$this->load->model('m_reservation','',TRUE);
		$arrMR			= $_GET['id'];
		$EXPL			= explode('~', $arrMR);
		$RSV_STARTD		= $EXPL[0];
		$RSV_ENDD		= $EXPL[1];
		$CATEG_CODE		= $EXPL[2];
		$result = $this->m_reservation->GetCountVH_Ovr_y($RSV_STARTD, $RSV_ENDD, $CATEG_CODE);
		//$result = 0;
		echo $result;
	}

	function update_adm_process()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");

			$this->db->trans_begin();

			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$RSV_CODE 	= $this->input->post('RSV_CODE');
			$CATEG_CODE	= $this->input->post('CATEG_CODEX');
			//echo "RSV_CODE : $RSV_CODE<br>";
			//echo "CATEG_CODE : $CATEG_CODE";
			//return false;
			$RSV_STAT	= $this->input->post('RSV_STAT');
			$RSV_CATEG	= $this->input->post('RSV_CATEG');
			$DRIVER_CODE	= $this->input->post('DRIVER_CODE');
			$RSV_NOTES		= $this->input->post('RSV_NOTES');

			$RSV_STARTD		= date('Y-m-d', strtotime($this->input->post('RSV_STARTD2')));
			$RSV_STARTDT	= $RSV_STARTD;
			$RSV_ENDD		= date('Y-m-d', strtotime($this->input->post('RSV_ENDD2')));
			$RSV_ENDDT		= $RSV_ENDD;
			$RSV_STARTT = $this->input->post('RSV_STARTT2');
			$RSV_ENDT	= $this->input->post('RSV_ENDT2');
			$RSV_MAIL	= $this->input->post('RSV_MAIL');
			$RSV_STARTD	= "$RSV_STARTD $RSV_STARTT";
			$RSV_ENDD	= "$RSV_ENDD $RSV_ENDT";
			$RSV_STAT	= $this->input->post('RSV_STAT');

			// START : Add 30 Minutes
			if(function_exists('date_default_timezone_set')) date_default_timezone_set('Asia/Jakarta');
			$date = date_create($RSV_ENDD);
			$dateSTR = date_create($RSV_ENDD);
			$dateENDA = date_add($date, date_interval_create_from_date_string('30 minutes'));

			/*if($DefEmp_ID == 'W17110004874')
			{
				$RSV_STARTDA	= $RSV_ENDD;
				$RSV_ENDDA		= date_format($dateENDA, 'Y-m-d H:i:s');
				$RSV_STARTT2	= date_format($dateSTR, 'H:i:s');
				$RSV_ENDT2		= date_format($dateENDA, 'H:i:s');
				echo "$RSV_STARTT2 - $RSV_ENDT2";
				return false;
			}*/

			/*if($DefEmp_ID == 'W17110004874')
			{
				echo "RSV_CATEG = $RSV_CATEG<br>";
				echo "CATEG_CODE2 = $CATEG_CODE<br>";
				echo "RSV_STARTD2 = $RSV_STARTD<br>";
				echo "RSV_ENDD2	= $RSV_ENDD<br>";
				echo "RSV_STARTT2 = $RSV_STARTT<br>";
				echo "RSV_ENDT2	= $RSV_ENDT<br>";
				echo "RSV_APPROVER	= $DefEmp_ID<br>";
				echo "RSV_STAT	= $RSV_STAT";
				return false;
			}*/

			$RSV_STARTDA	= $RSV_ENDD;
			$RSV_ENDDA		= date_format($dateENDA, 'Y-m-d H:i:s');
			$RSV_STARTT2	= date_format($dateSTR, 'H:i:s');
			$RSV_ENDT2		= date_format($dateENDA, 'H:i:s');

      //arr Inv_EmpID
  		$Inv_Emp	= $this->input->post('Inv_Emp');
  		$jmlEmp	= count($Inv_Emp);
  		$collData = "";
      $Inv_Emp1a = "";
      for ($i=0; $i < $jmlEmp; $i++) {
        $Inv_Emp1a = $Inv_Emp[$i];
        if ($i==0) {
          $collData = $Inv_Emp1a;
          $collData1 = $Inv_Emp1a;
        }else {
          $collData = "$collData;$Inv_Emp1a";
          $collData1 = "$collData1','$Inv_Emp1a";
        }
      }

      $collData1a = "'$collData1'";
  		//echo $collData1a;
      //return false;

      $csql = "tbl_employee WHERE Emp_ID IN ($collData1a)";
      $crsql = $this->db->count_all($csql);
      $sql = "SELECT * FROM tbl_employee WHERE Emp_ID IN ($collData1a)";
      $res = $this->db->query($sql)->result();
      $Email = "";
      $cellEmail   = "";
      $i = 0;
      if($crsql > 0){
        foreach ($res as $rowEMP)
        {
          $i = $i +1;
          $Email = $rowEMP->Email;
          if($i == 1)
          {
            $cellEmail = $Email;
          }
          else
          {
            $cellEmail = "$cellEmail;$Email";
          }
        }
      }

  		//echo "$collData<br>$cellEmail";
  		//return false;



			$UpdRes		= array('CATEG_CODE2' 	=> $CATEG_CODE,
								'RSV_CATEG'		=> $RSV_CATEG,
								//'DRIVER_CODE'	=> $DRIVER_CODE,
								'DRIVER_CODE2'	=> $DRIVER_CODE,
								'RSV_STARTD2' 	=> $RSV_STARTD,
								'RSV_ENDD2'		=> $RSV_ENDD,
								'RSV_STARTT2' 	=> $RSV_STARTT,
								'RSV_ENDT2'		=> $RSV_ENDT,
								'RSV_DESC'		=> $this->input->post('RSV_DESC'),
								'RSV_NOTES'		=> $this->input->post('RSV_NOTES'),
								'RSV_SUBMITTER'	=> $this->input->post('RSV_SUBMITTER'),
								'RSV_MAIL'		=> $this->input->post('RSV_MAIL'),
                'RSV_INVIT_Emp'	=> $collData,
								'RSV_INVIT'		=> $cellEmail,
								'RSV_MEMO'		=> $this->input->post('RSV_MEMO'),
								'RSV_APPROVER'	=> $DefEmp_ID,
								'RSV_APPROVED'	=> date('Y-m-d H:i:s'),
								'RSV_STAT'		=> $this->input->post('RSV_STAT'));

			//Update Cleaning

			$UpdRes2		= array('CATEG_CODE2' 	=> $CATEG_CODE,
									'RSV_STARTD2' 	=> $RSV_STARTDA,
									'RSV_ENDD2'		=> $RSV_ENDDA,
									'RSV_STARTT2' 	=> $RSV_STARTT2,
									'RSV_ENDT2'		=> $RSV_ENDT2,
									'RSV_STARTDA'	=> $RSV_STARTDA,
									'RSV_ENDDA'		=> $RSV_ENDDA,
									'RSV_TITLE'		=> "Cleaning",
									'RSV_DESC'		=> "Cleaning",
									'RSV_EMPID'		=> $DefEmp_ID,
                  'RSV_INVIT_Emp'	=> $collData,
  								'RSV_INVIT'		=> $cellEmail,
									'RSV_APPROVED'	=> date('Y-m-d H:i:s'),
									'RSV_STAT'		=> 99); // 99 = Adding Time

			$this->m_reservation->update($RSV_CODE, $UpdRes, $UpdRes2);

			/* Created By Dian Hermanto
			$MR_CODE 		= $this->input->post('CATEG_CODE');
			$UpdMRS	= array('MR_CODE' 		=> $this->input->post('CATEG_CODE'),
							'MR_STARTD' 	=> $RSV_STARTD,
							'MR_ENDD'		=> $RSV_ENDD,
							'MR_STARTT' 	=> $RSV_STARTT,
							'MR_ENDT'		=> $RSV_ENDT,
							'MR_NOTES'		=> $this->input->post('RSV_DESC'),
							'MR_SUBMITTER'	=> $this->input->post('RSV_SUBMITTER'),
							'MR_CREATER'	=> $DefEmp_ID,
							'MR_CREATED'	=> date('Y-m-d H:i:s'),
							'MR_STAT'		=> 1);
			$this->m_reservation->updateMRStat($MR_CODE, $UpdMRS, $RSV_STAT, $RSV_MAIL, $RSV_CODE);
			*/

			// Created By Iyan
			if($RSV_CATEG == 'MR')
			{
				$MR_CODE 		= $this->input->post('CATEG_CODE');
				//echo "test $MR_CODE";
				//return false;
				$UpdMRS	= array('MR_CODE' 		=> $CATEG_CODE,
								'MR_STARTD' 	=> $RSV_STARTD,
								'MR_ENDD'		=> $RSV_ENDD,
								'MR_STARTT' 	=> $RSV_STARTT,
								'MR_ENDT'		=> $RSV_ENDT,
								'MR_NOTES'		=> $this->input->post('RSV_DESC'),
								'MR_SUBMITTER'	=> $this->input->post('RSV_SUBMITTER'),
								'MR_CREATER'	=> $DefEmp_ID,
								'MR_CREATED'	=> date('Y-m-d H:i:s'),
								'MR_STAT'		=> 1);
				$this->m_reservation->updateMRStat($MR_CODE, $UpdMRS, $RSV_STAT, $RSV_MAIL, $RSV_CODE);

				//SENT UNDANGAN MEETING
				if($RSV_STAT == 3)
				{
					$RSV_SUBMITTER	= $this->input->post('RSV_SUBMITTER');
					$RSV_TITLE		= $this->input->post('RSV_TITLE');
					$RSV_DESC		= $this->input->post('RSV_DESC');
					$MR_NAME		= '';
					$sqlMR 			= "SELECT MR_NAME FROM tbl_meeting_room WHERE MR_CODE = '$CATEG_CODE'";
					$resMR 			= $this->db->query($sqlMR)->result();
					foreach($resMR as $rowMR) :
						$MR_NAME 	= $rowMR->MR_NAME;
					endforeach;
					//SENT UNDANGAN MEETING
					$toMail		 = ''.$cellEmail.'';
					$headers 	 = 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	 = "Informasi Undangan Meeting";
					$output		 = '';
					$output		.= '<table width="100%" border="0">
							<tr>
								<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3">Dear,</td>
							</tr>
							<tr>
								<td colspan="3">Assalamu \'alaikum wr.wb.</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3" style="vertical-align:top">Berikut kami sampaikan bahwa anda mendapatkan undangan meeting pada :</td>
							</tr>
							<tr>
								<td colspan="3" style="vertical-align:top">&nbsp;</td>
							</tr>
							<tr>
								<td width="2%" style="vertical-align:top">&nbsp;</td>
								<td width="9%">Tanggal</td>
								<td width="89%">: '.$RSV_STARTDT.' s.d. '.$RSV_ENDDT.'</td>
							</tr>
							<tr>
								<td width="2%" style="vertical-align:top">&nbsp;</td>
								<td width="9%">Waktu</td>
								<td width="89%">: Pukul '.$RSV_STARTT.' s.d. '.$RSV_ENDT.' WIB</td>
							</tr>
							<tr>
								<td width="2%" style="vertical-align:top">&nbsp;</td>
								<td width="9%">Ruang Rapat</td>
								<td width="89%">: '.$MR_NAME.'</td>
							</tr>
							<tr>
								<td width="2%" style="vertical-align:top">&nbsp;</td>
								<td width="9%">Prihal</td>
								<td width="89%">: '.$RSV_TITLE.'</td>
							</tr>
							<tr>
								<td width="2%" style="vertical-align:top">&nbsp;</td>
								<td width="9%">&nbsp;</td>
								<td width="89%">: '.$RSV_DESC.'</td>
							</tr>
							<tr>
							  <td style="vertical-align:top">&nbsp;</td>
							  <td>Pemberi Undangan</td>
							  <td>: '.$RSV_SUBMITTER.'</td>
  </tr>
							<tr>
								<td width="2%" style="vertical-align:top">&nbsp;</td>
								<td width="9%">&nbsp;</td>
								<td width="89%">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
							</tr>
							<tr>
								<td style="vertical-align:top">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3" style="vertical-align:top">Hormat kami,</td>
							</tr>
							<tr>
								<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
							</tr>
							<tr>
								<td style="vertical-align:top">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3" style="vertical-align:top">ttd</td>
							</tr>
							<tr>
							<td style="vertical-align:top">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
							</tr>
							<tr>
								<td colspan="3" style="vertical-align:top">&nbsp;</td>
							</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);

				}
        //Update Iyan date: 17 Juni 2019 17:04 WIB
        if($RSV_STAT == 6){
          $this->m_reservation->deleteClose($RSV_CODE);
        }
			}
			elseif($RSV_CATEG == 'VH')
			{
				if($RSV_STAT == 1 || $RSV_STAT == 2 || $RSV_STAT == 5 || $RSV_STAT == 6)
				{
					$VH_STAT 		= 0;
					$DRIVER_STAT	= 0;
				}
				else
				{
					$VH_STAT 		= 1;
					$DRIVER_STAT 	= 1;
				}
				//Update VH_STAT = 1 (Used)
				$VH_CODE 		= $this->input->post('CATEG_CODE');
				$UpdVHS	= array('VH_CODE' 		=> $CATEG_CODE,
								'VH_CREATER'	=> $DefEmp_ID,
								'VH_CREATED'	=> date('Y-m-d H:i:s'),
								'VH_STAT'		=> $VH_STAT);

				$UpdDR	= array('DRIVER_CODE' 	=> $DRIVER_CODE,
								'DR_CREATER'	=> $DefEmp_ID,
								'DR_CREATED'	=> date('Y-m-d H:i:s'),
								'DRIVER_STAT'	=> $DRIVER_STAT);

				$this->m_reservation->updateVHStat($VH_CODE, $UpdVHS, $UpdDR, $RSV_STAT, $RSV_MAIL, $RSV_CODE, $CATEG_CODE, $DRIVER_CODE);
			}
			elseif($RSV_CATEG == 'RR')
			{
				$AR_CODE 		= $this->input->post('CATEG_CODEX');
				//echo "AR_CODE = $AR_CODE";
				//return false;
				$UpdARS	= array('AR_CODE' 		=> $CATEG_CODE,
								'AR_STARTD' 	=> $RSV_STARTD,
								'AR_ENDD'		=> $RSV_ENDD,
								'AR_STARTT' 	=> $RSV_STARTT,
								'AR_ENDT'		=> $RSV_ENDT,
								'AR_NOTES'		=> $this->input->post('RSV_DESC'),
								'AR_SUBMITTER'	=> $this->input->post('RSV_SUBMITTER'),
								'AR_CREATER'	=> $DefEmp_ID,
								'AR_CREATED'	=> date('Y-m-d H:i:s'),
								'AR_STAT'		=> 1);
				$this->m_reservation->updateARStat($AR_CODE, $UpdARS, $RSV_STAT, $RSV_MAIL, $RSV_CODE);
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV-RR';
				$TTR_CATEG		= 'APP-P';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}

			if($RSV_CATEG == 'MR')
			{
				$url			= site_url('reservation/bookinglist/?id='.$this->url_encryption_helper->encode_url($appName));
			}
			elseif($RSV_CATEG == 'VH')
			{
				$url			= site_url('reservation/bookinglistVH/?id='.$this->url_encryption_helper->encode_url($appName));
			}
			elseif($RSV_CATEG == 'RR')
			{
				$url			= site_url('reservation/bookinglistRR/?id='.$this->url_encryption_helper->encode_url($appName));
			}
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}

	function view_booklist()
	{
		$this->load->model('m_reservation', '', TRUE);

		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$appName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($appName);

		if ($this->session->userdata('login') == TRUE)
		{
			$data["BLCount2"] 	= $this->m_reservation->count_all_BL2();
			$data['vwBL2']		= $this->m_reservation->get_all_BL2()->result();

			$this->load->view('v_reservation/v_reservation_mr_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function addVH() // OK
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$this->m_reservation->update_close_Expire();

		if ($this->session->userdata('login') == TRUE)
		{
			$ID_VH		= $_GET['id'];
			$VH_CODEX	= $this->url_encryption_helper->decode_url($ID_VH);
			//$VH_CODEX	= substr($VH_CODE,0,2);
			//$VH_CODEX			= "";
			$data['VH_CODEX']	= $VH_CODEX;
			//echo $VH_CODEX;
			//return false;

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Pemesanan';
				$data['h2_title']	= 'Kendaraan';
			}
			else
			{
				$data['h1_title']	= 'Reservation';
				$data['h2_title']	= 'Vehicle';
			}

			if($VH_CODEX == "")
			{
				$data['isReadyAdd'] 	= 0;
			}
			else
			{
				$data['isReadyAdd'] 	= 1;
				$data['VH_CODE_DEF']	= $VH_CODEX;
			}

			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['form_action']		= site_url('reservation/add_process');
			$data['add_eventVH']		= site_url('reservation/add_eventVH_process');
			$data['edit_eventVH']		= site_url('reservation/edit_eventVH_process');
			//$data['VH_CODE']			= $VH_CODE;
			/*
			$cNPL	= "tbl_vehicle WHERE VH_CODE = '$VH_CODEX'";
			$rcNPL	= $this->db->count_all($cNPL);
			$qNPL	= "SELECT * FROM tbl_vehicle WHERE VH_CODE = '$VH_CODEX'";
			$rNPL	= $this->db->query($qNPL)->result_array();
			if($rcNPL > 0)
			{
				foreach($rNPL as $rowNPL)
				{
					$data['VH_NOPOL'] = $rowNPL['VH_NOPOL'];
				}
			}
			else
			{
				$data['VH_NOPOL']			= '';
			}

			if($VH_CODEX == "VH")
			{
				$data['backURL'] 			= site_url('reservation/listVHAvail/?id='.$this->url_encryption_helper->encode_url($appName));
			}
			else
			{
				$data['backURL'] 			= site_url('reservation/');
			}
			*/

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'A';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			//$this->load->view('v_reservation/v_reservation_vc_form', $data);
			$this->load->view('v_reservation/v_calendar_vehicle', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function viewMR1() // OK
	{
		$this->load->model('m_reservation', '', TRUE);

		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['form_action']		= site_url('reservation/add_process');
			$data['backURL'] 			= site_url('reservation/');

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'A';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_calendar', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function viewMR() // OK
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$this->m_reservation->update_close_Expire();

		if ($this->session->userdata('login') == TRUE)
		{
			$MR_CODE	= $_GET['id'];
			$MR_CODE	= $this->url_encryption_helper->decode_url($MR_CODE);

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Pemesanan';
				$data['h2_title']	= 'Ruang Rapat';
			}
			else
			{
				$data['h1_title']	= 'Reservation';
				$data['h2_title']	= 'Meeting Room';
			}

			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['isReadyAdd'] 	= 0;
			$data['MR_CODE_DEF'] 	= '';
			$data['form_action']	= site_url('reservation/add_process');
			$data['add_event']		= site_url('reservation/add_event');
			$data['edit_event']		= site_url('reservation/edit_event');
			$data['get_events']		= site_url('reservation/get_events');
			$data['backURL'] 		= site_url('reservation/');

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'A';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_calendar', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function selectMR() // OK
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$MR_CODE	= $_GET['id'];
			$MR_CODE	= $this->url_encryption_helper->decode_url($MR_CODE);

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Pemesanan';
				$data['h2_title']	= 'Ruang Rapat';
			}
			else
			{
				$data['h1_title']	= 'Reservation';
				$data['h2_title']	= 'Meeting Room';
			}

			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['isReadyAdd'] 	= 1;
			$data['MR_CODE_DEF'] 	= $MR_CODE;
			$data['form_action']	= site_url('reservation/add_process');
			$data['add_event']		= site_url('reservation/add_event');
			$data['edit_event']		= site_url('reservation/edit_event');
			//$data['get_events']	= site_url('reservation/get_events');
			$data['backURL'] 		= site_url('reservation/');

			$data['CountUND']		= $this->m_reservation->count_und_employee();
			$data['vwUND']			= $this->m_reservation->get_und_employee()->result_array();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'A';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			if($DefEmp_ID == 'Y16040004542')
			{
				$this->load->view('v_reservation/v_calendar_dh', $data);
			}
			else
			{
				$this->load->view('v_reservation/v_calendar', $data);
			}
			//$this->load->view('v_reservation/v_calendar', $data);

		}
		else
		{
			redirect('Auth');
		}
	}

	function addRR() // OK
	{
		$this->load->model('m_reservation', '', TRUE);

		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['form_action']		= site_url('reservation/addRR_process');
			$data['backURL'] 			= site_url('reservation/');

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV-RR';
				$TTR_CATEG		= 'A';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			//$this->load->view('v_reservation/v_reservation_rr_form', $data);
			$this->load->view('v_reservation/v_calendar_room', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_events()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		date_default_timezone_set("Asia/Jakarta");

		$MR_CODE_DEF	= $_GET['id'];
		$MR_CODE_DEF	= $this->url_encryption_helper->decode_url($MR_CODE_DEF);
		$EXPL			= explode('~', $MR_CODE_DEF);
		$MR_CODE		= $EXPL[0];
		$startA			= $EXPL[1];
		$endA			= $EXPL[2];

		$RSV_EMPID 		= $this->session->userdata['Emp_ID'];

		// Our Start and End Dates
		$start 			= $this->input->get("start");
		$end 			= $this->input->get("end");

		$startdt 		= new DateTime('now'); 					// setup a local datetime
		$startdt->setTimestamp($start);							// Set the date based on timestamp
		$start_format 	= $startdt->format('Y-m-d H:i:s');

		$enddt 			= new DateTime('now'); // setup a local datetime
		$enddt->setTimestamp($end); // Set the date based on timestamp
		$end_format 	= $enddt->format('Y-m-d H:i:s');

		$timestamp 		= strtotime($end);
		$date 			= new DateTime($timestamp);
		$end_formata	= $date->format('Y-m-d H:i:s'); // 31.07.2012

		$eventsC 		= $this->m_reservation->get_eventsC($start_format, $end_format, $MR_CODE, $RSV_EMPID);
		$events 		= $this->m_reservation->get_events($start_format, $end_format, $MR_CODE, $RSV_EMPID);

		$data_events = array();

		foreach($events->result() as $r)
		{
			if($r->RSV_STAT == 2)
			{
				$color	= '#B8860B'; // Confirm
				$textColor = 'white';
			}
			elseif($r->RSV_STAT == 3)
			{
				$color	= '#006400'; // Approve
				$textColor = 'white';
			}
			elseif($r->RSV_STAT == 4)
			{
				$color	= '#00FFFF'; // Reschedule
				$textColor = 'black';
			}
			elseif($r->RSV_STAT == 5)
			{
				$color	= 'red'; // Rejected
				$textColor = 'white';
			}
			elseif($r->RSV_STAT == 6)
			{
				$color	= 'gray'; // Close
				$textColor = 'black';
			}
			elseif($r->RSV_STAT == 7)
			{
				$color	= 'purple'; // Revise
				$textColor = 'white';
			}
			elseif($r->RSV_STAT == 8)
			{
				$color	= '#1569C7'; // In Used
				$textColor = 'white';
			}
			elseif($r->RSV_STAT == 99)
			{
				$color	= 'red'; // In Used
				$textColor = 'white';
			}

			$data_events[] = array(
				"RSV_ID" => $r->RSV_ID,
				"RSV_CODE" => $r->RSV_CODE,
				"RSV_CATEG" => $r->RSV_CATEG,
				"CATEG_CODE" => $r->CATEG_CODE,
				"RSV_STARTD" => $r->RSV_STARTD,
				"RSV_ENDD" => $r->RSV_ENDD,
				"CATEG_CODE2" => $r->CATEG_CODE2,
				"RSV_STARTD2" => $r->RSV_STARTD2,
				"RSV_ENDD2" => $r->RSV_ENDD2,
				"RSV_STARTDA" => $r->RSV_STARTDA,
				"RSV_ENDDA" => $r->RSV_ENDDA,
				"RSV_TITLE" => $r->RSV_TITLE,
				"RSV_DESC" => $r->RSV_DESC,
				"RSV_QTY" => $r->RSV_QTY,
				"RSV_SUBMITTER" => $r->RSV_SUBMITTER,
				"RSV_MEMO"	=> $r->RSV_MEMO,
				"RSV_MEMO2"	=> $r->RSV_MEMO2,
				"RSV_MAIL" => $r->RSV_MAIL,
				//"Inv_Email" => $r->RSV_INVIT,
				"Inv_Emp_ID" => $r->RSV_INVIT_Emp,
				"RSV_STAT" => $r->RSV_STAT,
				"start" => $r->RSV_STARTD2,
				"end" => $r->RSV_ENDD2,
				//"start1" => $r->RSV_STARTDA,
				//"end1" => $r->RSV_ENDDA,
				"id" => $r->RSV_ID,
				"title" => $r->RSV_TITLE,
				"description" => $r->RSV_DESC,
				"color"	=> $color,
				"textColor" => $textColor,
				"tooltip" => 'Click View Detail',
				"editable" => false
			);
		}

		echo json_encode(array("events" => $data_events));
		exit();
	}

	function get_events_b()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$MR_CODE	= $_GET['id'];
		$MR_CODE	= $this->url_encryption_helper->decode_url($MR_CODE);

		// Our Start and End Dates
		$start 			= $this->input->post("date_start", TRUE);
		$end 			= $this->input->post("date_end", TRUE);
		//$start 			= $this->input->get("start");
		//$end 			= $this->input->get("end");

		$starta			= DateTime::createFromFormat("Y/m/d H:i", $start);
		$start_format	= $starta->format('Y-m-d H:i:s');

		$enda			= DateTime::createFromFormat("Y/m/d H:i", $start);
		$end_format		= $enda->format('Y-m-d H:i:s');

		$eventsC 		= $this->m_reservation->get_eventsC($start_format, $end_format, $MR_CODE);
		//$events 		= $this->m_reservation->get_eventsAdd($start_format, $end_format, $MR_CODE)->result();
		$sql			= "SELECT * FROM tbl_meeting_room
							WHERE
								MR_CODE = '$MR_CODE'";
		$result			= $this->db->query($sql)->result();
		foreach($result as $rowEvent):
			$MR_CODE 	= $rowEvent->MR_CODE;
			$MR_NAME 	= $rowEvent->MR_NAME;
			$MR_STAT 	= $rowEvent->MR_STAT;
			$MR_STATD 	= $rowEvent->MR_STATD;
			$isDisabled	= 0;
			$isDisDesc	= "";

			if($MR_STAT == 1)
			{
				$isDisabled	= 1;
				$isDisDesc	= " - $MR_STATD";
			}
			echo '<option value="'.$MR_CODE.'">'.$MR_NAME.'</option>';
		endforeach;
	}

	function add_event()
	{
		$this->load->model('m_reservation', '', TRUE);

		/* Our calendar data */
		date_default_timezone_set("Asia/Jakarta");

		$this->db->trans_begin();

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

		$RSV_CODE 		= date('YmdHis');
		$RSV_CATEG		= $this->input->post("RSV_CATEG", TRUE);
		//echo $RSV_CATEG;
		//return false;
		//$RSV_CATEG		= 'MR';
		$RSV_STARTD		= $this->input->post("RSV_STARTD", TRUE);		// MR Code
		$RSV_ENDD		= $this->input->post("RSV_ENDD", TRUE);

		//arr Inv_EmpID
		$Inv_Emp	= $this->input->post('Inv_Emp');
		$jmlEmp	= count($Inv_Emp);
		$collData = "";
    $Inv_Emp1a = "";
    for ($i=0; $i < $jmlEmp; $i++) {
      $Inv_Emp1a = $Inv_Emp[$i];
      if ($i==0) {
        $collData = $Inv_Emp1a;
        $collData1 = $Inv_Emp1a;
      }else {
        $collData = "$collData;$Inv_Emp1a";
        $collData1 = "$collData1','$Inv_Emp1a";
      }
    }

    $collData1a = "'$collData1'";
		//echo $collData1a;
    //return false;

    $csql = "tbl_employee WHERE Emp_ID IN ($collData1a)";
    $crsql = $this->db->count_all($csql);
    $sql = "SELECT * FROM tbl_employee WHERE Emp_ID IN ($collData1a)";
    $res = $this->db->query($sql)->result();
    $Email = "";
    $cellEmail   = "";
    $i = 0;
    if($crsql > 0){
      foreach ($res as $rowEMP)
      {
        $i = $i +1;
        $Email = $rowEMP->Email;
        if($i == 1)
        {
          $cellEmail = $Email;
        }
        else
        {
          $cellEmail = "$cellEmail;$Email";
        }
      }
    }

		//echo $cellEmail;
		//return false;

		if(!empty($RSV_STARTD))
		{
		   $sd = DateTime::createFromFormat("Y/m/d H:i", $RSV_STARTD);
		   $RSV_STARTD = $sd->format('Y-m-d H:i:s');
		   $RSV_STARTD_timestamp = $sd->getTimestamp();

		   $RSV_STARTT = $sd->format('H:i:s');
		}
		else
		{
		   $RSV_STARTD = date("Y-m-d H:i:s", time());
		   $RSV_STARTD_timestamp = time();

		   $RSV_STARTT = date("H:i:s", time());
		}

		if(!empty($RSV_ENDD))
		{
		   $ed = DateTime::createFromFormat("Y/m/d H:i", $RSV_ENDD);
		   $RSV_ENDD = $ed->format('Y-m-d H:i:s');
		   $RSV_ENDD_timestamp = $ed->getTimestamp();

		   $RSV_ENDT = $ed->format('H:i:s');
		}
		else
		{
		   $RSV_ENDD = date("Y-m-d H:i:s", time());
		   $RSV_ENDD_timestamp = time();

		   $RSV_ENDT = date("H:i:s", time());
		}

		$CATEG_CODE		= $this->input->post("CATEG_CODE", TRUE);		// MR Code
		$RSV_TITLE		= $this->input->post("RSV_TITLE", TRUE);
		$RSV_DESC		= $this->input->post("RSV_DESC", TRUE);
		$RSV_QTY		= $this->input->post("RSV_QTY", TRUE);
		$RSV_SUBMITTER	= $this->input->post("RSV_SUBMITTER", TRUE);
		$RSV_MAIL		= $this->input->post("RSV_MAIL", TRUE);
		$RSV_STAT		= $this->input->post("RSV_STAT", TRUE);

		$InsRes			= array('RSV_CODE' 		=> $RSV_CODE,
								'RSV_CATEG' 	=> $RSV_CATEG,
								'CATEG_CODE' 	=> $this->input->post('CATEG_CODE'),
								'RSV_STARTD' 	=> $RSV_STARTD,
								'RSV_ENDD'		=> $RSV_ENDD,
								'RSV_STARTT' 	=> $RSV_STARTT,
								'RSV_ENDT'		=> $RSV_ENDT,
								'CATEG_CODE2' 	=> $this->input->post('CATEG_CODE'),
								'RSV_STARTD2' 	=> $RSV_STARTD,
								'RSV_ENDD2'		=> $RSV_ENDD,
								'RSV_STARTT2' 	=> $RSV_STARTT,
								'RSV_ENDT2'		=> $RSV_ENDT,
								//'RSV_STARTDA'	=> $RSV_STARTDA,
								//'RSV_ENDDA'	=> $RSV_ENDDA,
								'RSV_TITLE'		=> $this->input->post('RSV_TITLE'),
								'RSV_DESC'		=> $this->input->post('RSV_DESC'),
								'RSV_QTY'		=> $this->input->post('RSV_QTY'),
								'RSV_SUBMITTER'	=> $this->input->post('RSV_SUBMITTER'),
								'RSV_MAIL'		=> $this->input->post('RSV_MAIL'),
								'RSV_EMPID'		=> $DefEmp_ID,
								'RSV_INVIT_Emp'	=> $collData,
								'RSV_INVIT'		=> $cellEmail,
								'RSV_CREATED'	=> date('Y-m-d H:i:s'),
								'RSV_STAT'		=> $this->input->post('RSV_STAT'));
		$this->m_reservation->add($InsRes);

		// START : Add 30 Minutes
			if(function_exists('date_default_timezone_set')) date_default_timezone_set('Asia/Jakarta');
			$date = date_create($RSV_ENDD);
			$dateSTR = date_create($RSV_ENDD);
			$dateADD = date_add($date, date_interval_create_from_date_string('30 minutes'));

			$RSV_STARTDA	= $RSV_ENDD;
			$RSV_ENDDA		= date_format($dateADD, 'Y-m-d H:i:s');
			$RSV_STARTT2	= date_format($dateSTR, 'H:i:s');
			$RSV_ENDT2		= date_format($dateADD, 'H:i:s');

			$RSV_CODEA		= "$RSV_CODE-ADD";
			$InsRes2		= array('RSV_CODE' 		=> $RSV_CODEA,
									'RSV_CATEG' 	=> $RSV_CATEG,
									'CATEG_CODE' 	=> $this->input->post('CATEG_CODE'),
									'RSV_STARTD' 	=> $RSV_STARTD,
									'RSV_ENDD'		=> $RSV_ENDD,
									'RSV_STARTT' 	=> $RSV_STARTT,
									'RSV_ENDT'		=> $RSV_ENDT,
									'CATEG_CODE2' 	=> $this->input->post('CATEG_CODE'),
									'RSV_STARTD2' 	=> $RSV_STARTDA,
									'RSV_ENDD2'		=> $RSV_ENDDA,
									'RSV_STARTT2' 	=> $RSV_STARTT2,
									'RSV_ENDT2'		=> $RSV_ENDT2,
									'RSV_STARTDA'	=> $RSV_STARTDA,
									'RSV_ENDDA'		=> $RSV_ENDDA,
									'RSV_TITLE'		=> "Cleaning",
									'RSV_DESC'		=> "Cleaning",
									'RSV_QTY'		=> $this->input->post('RSV_QTY'),
									'RSV_SUBMITTER'	=> $this->input->post('RSV_SUBMITTER'),
									'RSV_MAIL'		=> $this->input->post('RSV_MAIL'),
									'RSV_EMPID'		=> $DefEmp_ID,
                  'RSV_INVIT_Emp'	=> $collData,
  								'RSV_INVIT'		=> $cellEmail,
									'RSV_CREATED'	=> date('Y-m-d H:i:s'),
									'RSV_STAT'		=> 99); // 99 = Adding Time
			$this->m_reservation->add($InsRes2);
		// END : Add 30 Minutes


		//Insert Meeting Room
		if($RSV_CATEG == 'MR')
		{
			$RSV_CODE 		= $RSV_CODE;
			$MR_CODE 		= $CATEG_CODE;
			$MR_STARTD 		= $RSV_STARTD;
			$MR_ENDD 		= $RSV_ENDD;
			$MR_STARTT 		= $RSV_STARTT;
			$MR_ENDT 		= $RSV_ENDT;
			$MR_NOTES 		= $RSV_DESC;
			$MR_SUBMITTER 	= $RSV_SUBMITTER;
			$RSV_MAIL 		= $RSV_MAIL;
			$RSV_EMPID		= $RSV_EMPID;
			$MR_STAT 		= $RSV_STAT;

			if($MR_STAT == 2)
			{
				$MR_NAME		= '';
				$sqlMR 			= "SELECT MR_NAME FROM tbl_meeting_room WHERE MR_CODE = '$MR_CODE'";
				$resMR 			= $this->db->query($sqlMR)->result();
				foreach($resMR as $rowMR) :
					$MR_NAME 	= $rowMR->MR_NAME;
				endforeach;
				$RSVSTARTDV		= date('d/m/Y', strtotime($MR_STARTD));
				$MRENDDV		= date('d/m/Y', strtotime($MR_ENDD));
				$MRSTARTTV		= date('H:i', strtotime($MR_STARTT));
				$MRENDTV		= date('H:i', strtotime($MR_ENDT));

				// SENT MAIL TO SUBMITTER
					$toMail		= ''.$RSV_MAIL.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Pemesanan Ruang Rapat";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Terimakasih sudah melakukan pemesanan Ruang Rapat melalui NKE Smart System.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Pesanan Anda:</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Ruang Rapat</td>
											<td width="89%">: '.$MR_NAME.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSVSTARTDV.' s.d. '.$MRENDDV.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$MRSTARTTV.' s.d. '.$MRENDTV.' WIB</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: MENUNGGU</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);


				if($DefEmp_ID == 'W17110004874')
				{
					$MAIL_APP	= 'iyanwardiana@gmail.com';
				}
				else
				{
					$MAIL_APP	= 'ully@nusakonstruksi.com';
				}

				if($CATEG_CODE == 'MR-180001')
				{
					$CATEG_CODE = "'MR-180001','MR-180008','MR-180010'";
				}
				elseif($CATEG_CODE == 'MR-180002')
				{
					$CATEG_CODE = "'MR-180002','MR-180008','MR-180009','MR-180010'";
				}
				elseif($CATEG_CODE == 'MR-180003')
				{
					$CATEG_CODE = "'MR-180003','MR-180009','MR-180010'";
				}
				elseif($CATEG_CODE == 'MR-180008')
				{
					$CATEG_CODE = "'MR-180001','MR-180002','MR-180008','MR-180009','MR-180010'";
				}
				elseif($CATEG_CODE == 'MR-180009')
				{
					$CATEG_CODE = "'MR-180002','MR-180003','MR-180008','MR-180009','MR-180010'";
				}
				elseif($CATEG_CODE == 'MR-180010')
				{
					$CATEG_CODE = "'MR-180001','MR-180002','MR-180003','MR-180008','MR-180009','MR-180010'";
				}
				else
				{
					$CATEG_CODE = "'$CATEG_CODE'";
				}

				//Cek kondisi jika ada reservasi yang overlap
				$COV_MR		= "tbl_reservation WHERE RSV_STARTD2 BETWEEN '$RSV_STARTD' AND '$RSV_ENDDA' AND CATEG_CODE2 IN ($CATEG_CODE)
							   OR RSV_ENDD2 BETWEEN '$RSV_STARTD' AND '$RSV_ENDDA' AND CATEG_CODE2 IN ($CATEG_CODE)";
				//$VOV_MR		= "SELECT * FROM tbl_reservation WHERE RSV_STARTD2 BETWEEN '$RSV_STARTD' AND '$RSV_ENDDA' AND CATEG_CODE2 = '$CATEG_CODE'";
				$ResCOV_MR	= $this->db->count_all($COV_MR);
				//$ResVOV_MR	= $this->db->query($VOV_MR)->result();
				//echo $ResCOV_MR;

				//return false;
				if($ResCOV_MR > 2)
				{
					echo $Notes = '<tr>
									  <td width="2%" style="vertical-align:top">&nbsp;</td>
									  <td width="9%">Catatan</td>
									  <td width="89%">: Silahkan di cek untuk pemesanan ini, dikarenakan ada waktu pemesanan yang overlap</td>
								  </tr>';
				}

				// SENT MAIL TO APPROVER
					//$MAIL_APP	= 'ully@nusakonstruksi.com';
					$toMail		= ''.$MAIL_APP.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Pemesanan Ruang Rapat";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Ada pemesanan baru untuk penggunaan Ruang Rapat yang perlu Anda konfirmasi.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Ruang Rapat</td>
											<td width="89%">: '.$MR_NAME.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSVSTARTDV.' s.d. '.$MRENDDV.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$MRSTARTTV.' s.d. '.$MRENDTV.' WIB</td>
										</tr>
										'.$Notes.'
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: MENUNGGU KONFIRMASI</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);
			}
		}

		//Insert Vehicle

		elseif($RSV_CATEG == 'VH')
		{
			$RSV_CODE 		= $RSV_CODE;
			$VH_CODE 		= $CATEG_CODE;
			$DRIVER_CODE	= $DRIVER_CODE;

			$VH_STARTD 		= $RSV_STARTD;
			$VH_ENDD 		= $RSV_ENDD;
			$VH_STARTT 		= $RSV_STARTT;
			$VH_ENDT 		= $RSV_ENDT;
			$VH_NOTES 		= $RSV_DESC;
			$VH_SUBMITTER 	= $RSV_SUBMITTER;
			$RSV_MAIL 		= $RSV_MAIL;
			$VH_STAT 		= $RSV_STAT;

			if($VH_STAT == 2)
			{
				$VH_MEREK		= '';
				$VH_NOPOL		= '';
				$sqlVH 			= "SELECT VH_MEREK, VH_NOPOL FROM tbl_vehicle WHERE VH_CODE = '$VH_CODE'";
				$resVH 			= $this->db->query($sqlVH)->result();
				foreach($resVH as $rowVH) :
					$VH_MEREK 	= $rowVH->VH_MEREK;
					$VH_NOPOL 	= $rowVH->VH_NOPOL;
				endforeach;

				$DRIVER		= '';
				$sqlDR 		= "SELECT DRIVER FROM tbl_driver WHERE DRIVER_CODE = '$DRIVER_CODE'";
				$resDR 		= $this->db->query($sqlDR)->result();
				foreach($resDR as $rowDR) :
					$DRIVER 	= $rowDR->DRIVER;
				endforeach;

				if($DRIVER != '')
				{
					$tbl_DRIVER = '<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Driver</td>
										<td width="89%">: '.$DRIVER.'</td>
								  </tr>
								  <tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">No. HP</td>
										<td width="89%">: '.$DR_CONTACT.'</td>
								  </tr>';
				}

				$RSVSTARTDV		= date('d/m/Y', strtotime($VH_STARTD));
				$VHENDDV		= date('d/m/Y', strtotime($VH_ENDD));
				$VHSTARTTV		= date('H:i', strtotime($VH_STARTT));
				$VHENDTV		= date('H:i', strtotime($VH_ENDT));

				// SENT MAIL TO SUBMITTER
					$toMail		= ''.$RSV_MAIL.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Pemesanan Kendaraan";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Dear,</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Terimakasih sudah melakukan pemesanan kendaraan melalui NKE Smart System.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Pesanan Anda:</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Merek Kendaraan</td>
											<td width="89%">: '.$VH_MEREK.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">No. Polisi</td>
											<td width="89%">: '.$VH_NOPOL.'</td>
										</tr>
										'.$tbl_DRIVER.'
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSVSTARTDV.' s.d. '.$VHENDDV.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$VHSTARTTV.' s.d. '.$VHENDTV.' WIB</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: MENUNGGU</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);

				if($DefEmp_ID == 'W17110004874')
				{
					$MAIL_APP	= 'iyanwardiana@gmail.com';
				}
				else
				{
					$MAIL_APP	= 'ully@nusakonstruksi.com';
				}

				// SENT MAIL TO APPROVER
					//$MAIL_APP	= 'iyanwardiana@gmail.com';
					$toMail		= ''.$MAIL_APP.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Pemesanan Kendaraan";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Dear,</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Ada pemesanan baru untuk penggunaan kendaraan yang perlu Anda konfirmasi.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Merek Kendaraan</td>
											<td width="89%">: '.$VH_MEREK.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">No. Polisi</td>
											<td width="89%">: '.$VH_NOPOL.'</td>
										</tr>
										'.$tbl_DRIVER.'
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSVSTARTDV.' s.d. '.$VHENDDV.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$VHSTARTTV.' s.d. '.$VHENDTV.' WIB</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: MENUNGGU KONFIRMASI</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);
			}
		}

		//Insert Rest Room
		if($RSV_CATEG == 'RR')
		{
			$RSV_CODE 		= $RSV_CODE;
			$AR_CODE 		= $CATEG_CODE;
			$AR_STARTD 		= $RSV_STARTD;
			$AR_ENDD 		= $RSV_ENDD;
			$AR_STARTT 		= $RSV_STARTT;
			$AR_ENDT 		= $RSV_ENDT;
			$AR_NOTES 		= $RSV_DESC;
			$AR_SUBMITTER 	= $RSV_SUBMITTER;
			$RSV_MAIL 		= $RSV_MAIL;
			$RSV_EMPID		= $RSV_EMPID;
			$AR_STAT 		= $RSV_STAT;

			if($AR_STAT == 2)
			{
				$AR_NAME		= '';
				$AR_ADDRESS		= '';
				$sqlAR 			= "SELECT AR_NAME, AR_ADDRESS FROM tbl_apartement WHERE AR_CODE = '$AR_CODE'";
				$resAR 			= $this->db->query($sqlAR)->result();
				foreach($resAR as $rowAR) :
					$AR_NAME 	= $rowAR->AR_NAME;
					$AR_ADDRESS	= $rowAR->AR_ADDRESS;
				endforeach;
				$RSVSTARTDV		= date('d/m/Y', strtotime($MR_STARTD));
				$MRENDDV		= date('d/m/Y', strtotime($MR_ENDD));
				$MRSTARTTV		= date('H:i', strtotime($MR_STARTT));
				$MRENDTV		= date('H:i', strtotime($MR_ENDT));

				// SENT MAIL TO SUBMITTER
					$toMail		= ''.$RSV_MAIL.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Pemesanan Kamar Penginapan";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Terimakasih sudah melakukan pemesanan apartement melalui NKE Smart System.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Pesanan Anda:</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Kamar</td>
											<td width="89%">: '.$AR_NAME.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Alamat</td>
											<td width="89%">: '.$AR_ADDRESS.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSVSTARTDV.' s.d. '.$MRENDDV.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$MRSTARTTV.' s.d. '.$MRENDTV.' WIB</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: MENUNGGU</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);

				if($DefEmp_ID == 'W17110004874')
				{
					$MAIL_APP	= 'iyanwardiana@gmail.com';
				}
				else
				{
					$MAIL_APP	= 'ully@nusakonstruksi.com';
				}

				// SENT MAIL TO APPROVER
					//$MAIL_APP	= 'ully@nusakonstruksi.com';
					$toMail		= ''.$MAIL_APP.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Pemesanan Kamar Penginapan";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Ada pemesanan baru untuk penggunaan kamar penginapan yang perlu Anda konfirmasi.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Kamar</td>
											<td width="89%">: '.$AR_NAME.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Alamat</td>
											<td width="89%">: '.$AR_ADDRESS.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSVSTARTDV.' s.d. '.$MRENDDV.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$MRSTARTTV.' s.d. '.$MRENDTV.' WIB</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: MENUNGGU KONFIRMASI</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);
			}
		}


		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= '';
			$MenuCode 		= 'RESERV';
			$TTR_CATEG		= 'C';

			$this->load->model('m_updash/m_updash', '', TRUE);
			$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
									'TTR_DATE' 		=> date('Y-m-d H:i:s'),
									'TTR_MNCODE'	=> $MenuCode,
									'TTR_CATEG'		=> $TTR_CATEG,
									'TTR_PRJCODE'	=> $TTR_PRJCODE,
									'TTR_REFDOC'	=> $TTR_REFDOC);
			$this->m_updash->updateTrack($paramTrack);
		// END : UPDATE TO T-TRACK

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}

		$url			= site_url('reservation/selectMR/?id='.$this->url_encryption_helper->encode_url($CATEG_CODE));
		redirect($url);
	}

	function edit_event()
    {
		$this->load->model('m_reservation', '', TRUE);

		/* Our calendar data */
		date_default_timezone_set("Asia/Jakarta");

		$this->db->trans_begin();

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

		$RSV_CODE 		= $this->input->post("RSV_CODE", TRUE);
		$RSV_CATEG		= 'MR';
		$RSV_STARTD		= $this->input->post("RSV_STARTD", TRUE);		// MR Code
		$RSV_ENDD		= $this->input->post("RSV_ENDD", TRUE);

    //arr Inv_EmpID
		$Inv_Emp	= $this->input->post('Inv_Emp');
		$jmlEmp	= count($Inv_Emp);
    if($jmlEmp > 0){
      //echo $jmlEmp;
      //return false;
  		$collData = "";
      $Inv_Emp1a = "";
      for ($i=0; $i < $jmlEmp; $i++) {
        $Inv_Emp1a = $Inv_Emp[$i];
        if ($i==0) {
          $collData = $Inv_Emp1a;
          $collData1 = $Inv_Emp1a;
        }else {
          $collData = "$collData;$Inv_Emp1a";
          $collData1 = "$collData1','$Inv_Emp1a";
        }
      }

      $collData1a = "'$collData1'";
  		//echo $collData1a;
      //return false;

      $csql = "tbl_employee WHERE Emp_ID IN ($collData1a)";
      $crsql = $this->db->count_all($csql);
      $sql = "SELECT * FROM tbl_employee WHERE Emp_ID IN ($collData1a)";
      $res = $this->db->query($sql)->result();
      $Email = "";
      $cellEmail   = "";
      $i = 0;
      if($crsql > 0){
        foreach ($res as $rowEMP)
        {
          $i = $i +1;
          $Email = $rowEMP->Email;
          if($i == 1)
          {
            $cellEmail = $Email;
          }
          else
          {
            $cellEmail = "$cellEmail;$Email";
          }
        }
      }

  		//echo $cellEmail;
  		//return false;
    }else{
      $collData = "";
      $cellEmail = "";
    }


		if(!empty($RSV_STARTD))
		{
		   $sd = DateTime::createFromFormat("Y/m/d H:i", $RSV_STARTD);
		   $RSV_STARTD = $sd->format('Y-m-d H:i:s');
		   $RSV_STARTD_timestamp = $sd->getTimestamp();

		   $RSV_STARTT = $sd->format('H:i:s');
		}
		else
		{
		   $RSV_STARTD = date("Y-m-d H:i:s", time());
		   $RSV_STARTD_timestamp = time();

		   $RSV_STARTT = date("H:i:s", time());
		}

		if(!empty($RSV_ENDD))
		{
		   $ed = DateTime::createFromFormat("Y/m/d H:i", $RSV_ENDD);
		   $RSV_ENDD = $ed->format('Y-m-d H:i:s');
		   $RSV_ENDD_timestamp = $ed->getTimestamp();

		   $RSV_ENDT = $ed->format('H:i:s');
		}
		else
		{
		   $RSV_ENDD = date("Y-m-d H:i:s", time());
		   $RSV_ENDD_timestamp = time();

		   $RSV_ENDT = date("H:i:s", time());
		}

		$CATEG_CODE		= $this->input->post("CATEG_CODE", TRUE);		// MR Code
		$RSV_TITLE		= $this->input->post("RSV_TITLE", TRUE);
		$RSV_DESC		= $this->input->post("RSV_DESC", TRUE);
		$RSV_QTY		= $this->input->post("RSV_QTY", TRUE);
		$RSV_SUBMITTER	= $this->input->post("RSV_SUBMITTER", TRUE);
		$RSV_MAIL		= $this->input->post("RSV_MAIL", TRUE);
		$RSV_MEMO		= $this->input->post("RSV_MEMO", TRUE);
		$RSV_MEMO2		= $this->input->post("RSV_MEMO2", TRUE);
		$RSV_STAT		= $this->input->post("RSV_STAT", TRUE);

		// START : Added by DH on 04042018 - Re-Schedule
		if($RSV_STAT == 4 || $RSV_STAT == 6)
		{
			$RSV_STARTD2	= $this->input->post("RSV_STARTD2", TRUE);
			$RSV_ENDD2		= $this->input->post("RSV_ENDD2", TRUE);

			if(!empty($RSV_STARTD2))
			{
			   $sd = DateTime::createFromFormat("Y/m/d H:i", $RSV_STARTD2);
			   $RSV_STARTD2 = $sd->format('Y-m-d H:i:s');
			   $RSV_STARTD2_timestamp = $sd->getTimestamp();

			   $RSV_STARTT2 = $sd->format('H:i:s');
			}
			else
			{
			   $RSV_STARTD2 = date("Y-m-d H:i:s", time());
			   $RSV_STARTD2_timestamp = time();

			   $RSV_STARTT2 = date("H:i:s", time());
			}

			if(!empty($RSV_ENDD2))
			{
			   $ed = DateTime::createFromFormat("Y/m/d H:i", $RSV_ENDD2);
			   $RSV_ENDD2 = $ed->format('Y-m-d H:i:s');
			   $RSV_ENDD2_timestamp = $ed->getTimestamp();

			   $RSV_ENDT2 = $ed->format('H:i:s');
			}
			else
			{
			   $RSV_ENDD2 = date("Y-m-d H:i:s", time());
			   $RSV_ENDD2_timestamp = time();

			   $RSV_ENDT2 = date("H:i:s", time());
			}
		}
		//echo "1. $RSV_STARTD2 s.d.$RSV_ENDD2 == $RSV_STARTT2 = $RSV_ENDT2<br>";
		// END : Added by DH on 04042018 - Re-Schedule

		$UpdRes			= array('RSV_CODE' 		=> $RSV_CODE,
								'RSV_CATEG' 	=> $RSV_CATEG,
								'CATEG_CODE' 	=> $this->input->post('CATEG_CODE'),
								'RSV_STARTD' 	=> $RSV_STARTD,
								'RSV_ENDD'		=> $RSV_ENDD,
								'RSV_STARTT' 	=> $RSV_STARTT,
								'RSV_ENDT'		=> $RSV_ENDT,
								'CATEG_CODE2' 	=> $this->input->post('CATEG_CODE'),
								'RSV_STARTD2' 	=> $RSV_STARTD2,
								'RSV_ENDD2'		=> $RSV_ENDD2,
								'RSV_STARTT2' 	=> $RSV_STARTT2,
								'RSV_ENDT2'		=> $RSV_ENDT2,
								'RSV_MEMO'		=> $RSV_MEMO,
								'RSV_MEMO2'		=> $RSV_MEMO2,
								'RSV_TITLE'		=> $this->input->post('RSV_TITLE'),
								'RSV_DESC'		=> $this->input->post('RSV_DESC'),
								'RSV_QTY'		=> $this->input->post('RSV_QTY'),
								'RSV_SUBMITTER'	=> $this->input->post('RSV_SUBMITTER'),
								'RSV_MAIL'		=> $this->input->post('RSV_MAIL'),
								'RSV_EMPID'		=> $DefEmp_ID,
                'RSV_INVIT_Emp'	=> $collData,
								'RSV_INVIT'		=> $cellEmail,
								'RSV_STAT'		=> $this->input->post('RSV_STAT'));
		//$this->m_reservation->update($RSV_CODE, $UpdRes);

		// START : Added by DH on 04042018 - Re-Schedule +30 mnutes
		if($RSV_STAT == 4 || $RSV_STAT == 6)
		{
			// HARUS MENGUPDATE ADDING TIME
			$RSV_CODEA		= "$RSV_CODE-ADD";
			if(function_exists('date_default_timezone_set')) date_default_timezone_set('Asia/Jakarta');
			$date	 = date_create($RSV_ENDD2);
			$dateSTR = date_create($RSV_ENDD2);
			date_add($date, date_interval_create_from_date_string('30 minutes'));

			$RSV_STARTD2A	= $RSV_ENDD2;
			$RSV_ENDD2A		= date_format($date, 'Y-m-d H:i:s');
			$RSV_STARTT2	= date_format($dateSTR, 'H:i:s');
			$RSV_ENDT2		= date_format($date, 'H:i:s');
			//echo "$RSV_CODEA = $RSV_STARTD2A s.d.$RSV_ENDD2A == $RSV_STARTT2 = $RSV_ENDT2<br>";
			//return false;
			$UpdRes2		= array('RSV_CODE' 		=> $RSV_CODEA,
									'CATEG_CODE2' 	=> $this->input->post('CATEG_CODE'),
									'RSV_STARTD2' 	=> $RSV_STARTD2A,
									'RSV_ENDD2'		=> $RSV_ENDD2A,
									'RSV_STARTT2' 	=> $RSV_STARTT2,
									'RSV_ENDT2'		=> $RSV_ENDT2,
									'RSV_STARTDA'	=> $RSV_STARTD2A,
									'RSV_ENDDA'		=> $RSV_ENDD2A,
									'RSV_MEMO'		=> $RSV_MEMO,
									'RSV_MEMO2'		=> $RSV_MEMO2,
									'RSV_TITLE'		=> $this->input->post('RSV_TITLE'),
									'RSV_DESC'		=> $this->input->post('RSV_DESC'),
									'RSV_QTY'		=> $this->input->post('RSV_QTY'),
									'RSV_SUBMITTER'	=> $this->input->post('RSV_SUBMITTER'),
									'RSV_MAIL'		=> $this->input->post('RSV_MAIL'),
									'RSV_EMPID'		=> $DefEmp_ID,
                  'RSV_INVIT_Emp'	=> $collData,
  								'RSV_INVIT'		=> $cellEmail,
									'RSV_STAT'		=> 99);
			//$this->m_reservation->update($RSV_CODEA, $UpdRes2);
		}
		$this->m_reservation->update($RSV_CODE, $UpdRes, $UpdRes2);
		// END : Added by DH on 04042018

    //Update by Iyan Date : 17 Juni 2019 16:13 WIB
    if($RSV_STAT == 6){
      $this->m_reservation->deleteClose($RSV_CODE);
    }


		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= '';
			$MenuCode 		= 'RESERV';
			$TTR_CATEG		= 'RESERVUP-P';

			$this->load->model('m_updash/m_updash', '', TRUE);
			$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
									'TTR_DATE' 		=> date('Y-m-d H:i:s'),
									'TTR_MNCODE'	=> $MenuCode,
									'TTR_CATEG'		=> $TTR_CATEG,
									'TTR_PRJCODE'	=> $TTR_PRJCODE,
									'TTR_REFDOC'	=> $TTR_REFDOC);
			$this->m_updash->updateTrack($paramTrack);
		// END : UPDATE TO T-TRACK

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}

		$url			= site_url('reservation/selectMR/?id='.$this->url_encryption_helper->encode_url($CATEG_CODE));
		redirect($url);
    }

	function get_eventsVH()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		date_default_timezone_set("Asia/Jakarta");

		$VH_CODE_DEF	= $_GET['id'];
		$VH_CODE_DEF	= $this->url_encryption_helper->decode_url($VH_CODE_DEF);
		$EXPL			= explode('~', $VH_CODE_DEF);
		$VH_CODE		= $EXPL[0];
		$startA			= $EXPL[1];
		$endA			= $EXPL[2];

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

		// Our Start and End Dates
		$start 			= $this->input->get("start");
		$end 			= $this->input->get("end");

		$startdt 		= new DateTime('now'); 					// setup a local datetime
		$startdt->setTimestamp($start);							// Set the date based on timestamp
		$start_format 	= $startdt->format('Y-m-d H:i:s');

		$enddt 			= new DateTime('now'); // setup a local datetime
		$enddt->setTimestamp($end); // Set the date based on timestamp
		$end_format 	= $enddt->format('Y-m-d H:i:s');

		$timestamp 		= strtotime($end);
		$date 			= new DateTime($timestamp);
		$end_formata	= $date->format('Y-m-d H:i:s'); // 31.07.2012

		$eventsC 		= $this->m_reservation->get_eventsCVH($start_format, $end_format, $VH_CODE, $DefEmp_ID);
		$events 		= $this->m_reservation->get_eventsVH($start_format, $end_format, $VH_CODE, $DefEmp_ID);

		$data_events = array();

		foreach($events->result() as $r)
		{
			if($r->RSV_STAT == 2)
			{
				$color	= '#B8860B'; // Confirm
				$textColor = 'white';
			}
			elseif($r->RSV_STAT == 3)
			{
				$color	= '#006400'; // Approve
				$textColor = 'white';
			}
			elseif($r->RSV_STAT == 4)
			{
				$color	= '#00FFFF'; // Reschedule
				$textColor = 'black';
			}
			elseif($r->RSV_STAT == 5)
			{
				$color	= 'red'; // Rejected
				$textColor = 'white';
			}
			elseif($r->RSV_STAT == 6)
			{
				$color	= 'gray'; // Close
				$textColor = 'black';
			}
			elseif($r->RSV_STAT == 7)
			{
				$color	= 'purple'; // Revise
				$textColor = 'white';
			}
			elseif($r->RSV_STAT == 8)
			{
				$color	= '#1569C7'; // In Used
				$textColor = 'white';
			}

			$data_events[] = array(
				"RSV_ID" => $r->RSV_ID,
				"RSV_CODE" => $r->RSV_CODE,
				"RSV_CATEG" => $r->RSV_CATEG,
				"CATEG_CODE" => $r->CATEG_CODE,
				"RSV_STARTD" => $r->RSV_STARTD,
				"RSV_ENDD" => $r->RSV_ENDD,
				"CATEG_CODE2" => $r->CATEG_CODE2,
				"DRIVER_CODE" => $r->DRIVER_CODE2,
				"RSV_STARTD2" => $r->RSV_STARTD2,
				"RSV_ENDD2" => $r->RSV_ENDD2,
				"RSV_TITLE" => $r->RSV_TITLE,
				"RSV_DESC" => $r->RSV_DESC,
				"RSV_NOTES" => $r->RSV_NOTES,
				"RSV_QTY" => $r->RSV_QTY,
				"RSV_SUBMITTER" => $r->RSV_SUBMITTER,
				"VH_BORROWER" => $r->VH_BORROWER,
				"VH_DEPT_BORROWER" => $r->VH_DEPT_BORROWER,
				"VH_DEPT_MGRDEV" => $r->VH_DEPT_MGRDEV,
				"VH_MGRDEV" => $r->VH_MGRDEV,
				"RSV_MAIL" => $r->RSV_MAIL,
				"RSV_MEMO" => $r->RSV_MEMO,
				"RSV_STAT" => $r->RSV_STAT,
				"id" => $r->RSV_ID,
				"title" => $r->RSV_TITLE,
				"description" => $r->RSV_DESC,
				"end" => $r->RSV_ENDD2,
				"start" => $r->RSV_STARTD2,
				"color"	=> $color,
				"textColor" => $textColor,
				"tooltip" => 'Click View Detail',
				"editable" => false
			);
		}

		echo json_encode(array("events" => $data_events));
		exit();
	}

	function add_eventVH_process()
	{
		$this->load->model('m_reservation', '', TRUE);

		/* Our calendar data */
		date_default_timezone_set("Asia/Jakarta");

		$this->db->trans_begin();

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

		$RSV_CODE 		= date('YmdHis');
		$RSV_CATEG		= 'VH';
		$RSV_STARTD		= $this->input->post("RSV_STARTD", TRUE);		// MR Code
		$RSV_ENDD		= $this->input->post("RSV_ENDD", TRUE);


		if(!empty($RSV_STARTD))
		{
		   $sd = DateTime::createFromFormat("Y/m/d H:i", $RSV_STARTD);
		   $RSV_STARTD = $sd->format('Y-m-d H:i:s');
		   $RSV_STARTD_timestamp = $sd->getTimestamp();

		   $RSV_STARTT = $sd->format('H:i:s');
		}
		else
		{
		   $RSV_STARTD = date("Y-m-d H:i:s", time());
		   $RSV_STARTD_timestamp = time();

		   $RSV_STARTT = date("H:i:s", time());
		}

		if(!empty($RSV_ENDD))
		{
		   $ed = DateTime::createFromFormat("Y/m/d H:i", $RSV_ENDD);
		   $RSV_ENDD = $ed->format('Y-m-d H:i:s');
		   $RSV_ENDD_timestamp = $ed->getTimestamp();

		   $RSV_ENDT = $ed->format('H:i:s');
		}
		else
		{
		   $RSV_ENDD = date("Y-m-d H:i:s", time());
		   $RSV_ENDD_timestamp = time();

		   $RSV_ENDT = date("H:i:s", time());
		}

		$CATEG_CODE		= $this->input->post("CATEG_CODE", TRUE);		// MR Code
		$DRIVER_CODE	= $this->input->post("DRIVER_CODE", TRUE);
		$RSV_TITLE		= $this->input->post("RSV_TITLE", TRUE);
		$RSV_DESC		= $this->input->post("RSV_DESC", TRUE);
		$RSV_QTY		= $this->input->post("RSV_QTY", TRUE);
		$RSV_SUBMITTER	= $this->input->post("RSV_SUBMITTER", TRUE);
		//$RSV_DEPT		= $this->input->post("RSV_DEPT", TRUE);
		$RSV_MAIL		= $this->input->post("RSV_MAIL", TRUE);
		$RSV_STAT		= $this->input->post("RSV_STAT", TRUE);

		/* Testing .....................................
		echo "RSV_CODE : $RSV_CODE<br>";
		echo "RSV_CATEG :$RSV_CATEG<br>";
		echo "RSV_STARTD : $RSV_STARTD<br>";		// MR Code
		echo "RSV_ENDD : $RSV_ENDD<br>";
		echo "RSV_STARTT : $RSV_STARTT<br>";
		echo "RSV_ENDT : $RSV_ENDT<br>";
		echo "CATEG_CODE : $CATEG_CODE<br>";		// MR Code
		echo "DRIVER_CODE : $DRIVER_CODE<br>";
		echo "RSV_TITLE : $RSV_TITLE<br>";
		echo "RSV_DESC : $RSV_DESC<br>";
		echo "RSV_QTY : $RSV_QTY<br>";
		echo "RSV_SUBMITTER : $RSV_SUBMITTER<br>";
		echo "RSV_MAIL : $RSV_MAIL<br>";
		echo "RSV_STAT : $RSV_STAT<br>";

		return false;
		.................................................*/


		$InsRes			= array('RSV_CODE' 		=> $RSV_CODE,
								'RSV_CATEG' 	=> $RSV_CATEG,
								'CATEG_CODE' 	=> $CATEG_CODE,
								'RSV_STARTD' 	=> $RSV_STARTD,
								'RSV_ENDD'		=> $RSV_ENDD,
								'RSV_STARTT' 	=> $RSV_STARTT,
								'RSV_ENDT'		=> $RSV_ENDT,
								'CATEG_CODE2' 	=> $CATEG_CODE,
								'DRIVER_CODE'	=> $DRIVER_CODE,
								'RSV_STARTD2' 	=> $RSV_STARTD,
								'RSV_ENDD2'		=> $RSV_ENDD,
								'RSV_STARTT2' 	=> $RSV_STARTT,
								'RSV_ENDT2'		=> $RSV_ENDT,
								'RSV_TITLE'		=> $RSV_TITLE,
								'RSV_DESC'		=> $RSV_DESC,
								'RSV_QTY'		=> $RSV_QTY,
								'RSV_SUBMITTER'	=> $RSV_SUBMITTER,
								//'RSV_DEPT'		=> $RSV_DEPT,
								'RSV_MAIL'		=> $RSV_MAIL,
								'RSV_EMPID'		=> $DefEmp_ID,
								'RSV_CREATED'	=> date('Y-m-d H:i:s'),
								'RSV_STAT'		=> $this->input->post('RSV_STAT'));
		$this->m_reservation->add($InsRes);

		if($RSV_CATEG == 'VH')
		{
			$RSV_CODE 		= $RSV_CODE;
			$VH_CODE 		= $CATEG_CODE;
			$DRIVER_CODE	= $DRIVER_CODE;

			$VH_STARTD 		= $RSV_STARTD;
			$VH_ENDD 		= $RSV_ENDD;
			$VH_STARTT 		= $RSV_STARTT;
			$VH_ENDT 		= $RSV_ENDT;
			$VH_NOTES 		= $RSV_DESC;
			$VH_SUBMITTER 	= $RSV_SUBMITTER;
			//$VH_DEPT		= $RSV_DEPT;
			$RSV_MAIL 		= $RSV_MAIL;
			$VH_STAT 		= $RSV_STAT;

			$VH_BORROWER		= $RSV_SUBMITTER;
			$VH_DEPT_BORROWER	= $this->input->post('RSV_DEPT_BORROWER', TRUE);
			$VH_MGRDEV			= $this->input->post('RSV_MGRDEV', TRUE);
			$VH_DEPT_MGRDEV		= $this->input->post('RSV_DEPT_MGRDEV', TRUE);

			$DeptVH			= "tbl_grdept_vehicle WHERE VH_BORROWER = '$VH_BORROWER'";
			$ressDeptVH		= $this->db->count_all($DeptVH);
			if($ressDeptVH > 0)
			{
				$updGrDeptVH	= array('VH_DEPT_BORROWER'	=> $VH_DEPT_BORROWER,
										'VH_MGRDEV'			=> $VH_MGRDEV,
										'VH_DEPT_MGRDEV'	=> $VH_DEPT_MGRDEV);
				$this->m_reservation->updateDept($updGrDeptVH, $VH_BORROWER);
			}
			else
			{
				$insGrDeptVH	= array('VH_BORROWER'		=> $VH_BORROWER,
										'VH_DEPT_BORROWER'	=> $VH_DEPT_BORROWER,
										'VH_MGRDEV'			=> $VH_MGRDEV,
										'VH_DEPT_MGRDEV'	=> $VH_DEPT_MGRDEV);
				$this->m_reservation->addDept($insGrDeptVH);
			}

			if($VH_STAT == 2)
			{
				$VH_MEREK		= '';
				$VH_NOPOL		= '';
				$sqlVH 			= "SELECT VH_MEREK, VH_NOPOL FROM tbl_vehicle WHERE VH_CODE = '$VH_CODE'";
				$resVH 			= $this->db->query($sqlVH)->result();
				foreach($resVH as $rowVH) :
					$VH_MEREK 	= $rowVH->VH_MEREK;
					$VH_NOPOL 	= $rowVH->VH_NOPOL;
				endforeach;

				$DRIVER		= '';
				$sqlDR 		= "SELECT DRIVER FROM tbl_driver WHERE DRIVER_CODE = '$DRIVER_CODE'";
				$resDR 		= $this->db->query($sqlDR)->result();
				foreach($resDR as $rowDR) :
					$DRIVER 	= $rowDR->DRIVER;
				endforeach;

				if($DRIVER != '')
				{
					$tbl_DRIVER = '<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Driver</td>
										<td width="89%">: '.$DRIVER.'</td>
								  </tr>
								  <tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">No. HP</td>
										<td width="89%">: '.$DR_CONTACT.'</td>
								  </tr>';
				}
				else
				{
					$tbl_DRIVER = '';
				}

				$RSVSTARTDV		= date('d/m/Y', strtotime($VH_STARTD));
				$VHENDDV		= date('d/m/Y', strtotime($VH_ENDD));
				$VHSTARTTV		= date('H:i', strtotime($VH_STARTT));
				$VHENDTV		= date('H:i', strtotime($VH_ENDT));

				// SENT MAIL TO SUBMITTER
					$toMail		= ''.$RSV_MAIL.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Pemesanan Kendaraan";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Dear,</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Terimakasih sudah melakukan pemesanan kendaraan melalui NKE Smart System.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Pesanan Anda:</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Merek Kendaraan</td>
											<td width="89%">: '.$VH_MEREK.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">No. Polisi</td>
											<td width="89%">: '.$VH_NOPOL.'</td>
										</tr>
										'.$tbl_DRIVER.'
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSVSTARTDV.' s.d. '.$VHENDDV.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$VHSTARTTV.' s.d. '.$VHENDTV.' WIB</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: MENUNGGU</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);

				if($DefEmp_ID == 'W17110004874')
				{
					$MAIL_APP	= 'iyanwardiana@gmail.com';
				}
				else
				{
					$MAIL_APP	= 'kendaraan@nusakonstruksi.com;nina@nusakonstruksi.com';
				}

				//Cek kondisi jika ada reservasi yang overlap
				$COV_MR		= "tbl_reservation WHERE RSV_STARTD2 BETWEEN '$VH_STARTD' AND '$VH_ENDD' AND CATEG_CODE2 = '$CATEG_CODE'
							   OR RSV_ENDD2 BETWEEN '$VH_STARTD' AND '$VH_ENDD' AND CATEG_CODE2 = '$CATEG_CODE'";
				//$VOV_MR		= "SELECT * FROM tbl_reservation WHERE RSV_STARTD2 BETWEEN '$RSV_STARTD' AND '$RSV_ENDDA' AND CATEG_CODE2 = '$CATEG_CODE'";
				$ResCOV_MR	= $this->db->count_all($COV_MR);
				//$ResVOV_MR	= $this->db->query($VOV_MR)->result();
				//echo $ResCOV_MR;

				//return false;
				if($ResCOV_MR > 1)
				{
					echo $Notes = '<tr>
									  <td width="2%" style="vertical-align:top">&nbsp;</td>
									  <td width="9%">Catatan</td>
									  <td width="89%">: Silahkan di cek untuk pemesanan ini, dikarenakan ada waktu pemesanan yang overlap</td>
								  </tr>';
				}

				// SENT MAIL TO APPROVER
					//$MAIL_APP	= 'iyanwardiana@gmail.com';
					$toMail		= ''.$MAIL_APP.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Pemesanan Kendaraan";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Dear,</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Ada pemesanan baru untuk penggunaan kendaraan yang perlu Anda konfirmasi.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Merek Kendaraan</td>
											<td width="89%">: '.$VH_MEREK.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">No. Polisi</td>
											<td width="89%">: '.$VH_NOPOL.'</td>
										</tr>
										'.$tbl_DRIVER.'
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSVSTARTDV.' s.d. '.$VHENDDV.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$VHSTARTTV.' s.d. '.$VHENDTV.' WIB</td>
										</tr>
										'.$Notes.'
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: MENUNGGU KONFIRMASI</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);
			}
		}

		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= '';
			$MenuCode 		= 'RESERV';
			$TTR_CATEG		= 'C';

			$this->load->model('m_updash/m_updash', '', TRUE);
			$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
									'TTR_DATE' 		=> date('Y-m-d H:i:s'),
									'TTR_MNCODE'	=> $MenuCode,
									'TTR_CATEG'		=> $TTR_CATEG,
									'TTR_PRJCODE'	=> $TTR_PRJCODE,
									'TTR_REFDOC'	=> $TTR_REFDOC);
			$this->m_updash->updateTrack($paramTrack);
		// END : UPDATE TO T-TRACK

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}

		$url			= site_url('reservation/addVH/?id='.$this->url_encryption_helper->encode_url($CATEG_CODE));
		redirect($url);
	}

	function edit_eventVH_process()
	{
		$this->load->model('m_reservation', '', TRUE);


		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");

			$this->db->trans_begin();

			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$RSV_CODE 		= $this->input->post('RSV_CODE');
			$RSV_CATEG		= 'VH';
			$CATEG_CODE		= $this->input->post('CATEG_CODE');
			$DRIVER_CODE	= $this->input->post('DRIVER_CODE');
			$RSV_STARTD		= $this->input->post('RSV_STARTD');
			$RSV_STARTDT	= date('Y-m-d H:i:s', strtotime($RSV_STARTD));
			$RSV_ENDD		= $this->input->post('RSV_ENDD');
			$RSV_ENDDT		= date('Y-m-d H:i:s', strtotime($RSV_ENDD));
			$dateST 		= new DateTime($this->input->post('RSV_STARTD'), new DateTimeZone('Asia/Jakarta') );
			$RSV_STARTT 	= $dateST->format('H:i:s');
			$dateET 		= new DateTime($this->input->post('RSV_ENDD'), new DateTimeZone('Asia/Jakarta') );
			$RSV_ENDT	 	= $dateET->format('H:i:s');
			//$RSV_STARTT 	= DateTime('H:i:s', strtotime($this->input->post('RSV_STARTT')));
			//$RSV_ENDT		= date('H:i:s', strtotime($this->input->post('RSV_ENDT')));
			$CATEG_CODE2	= $this->input->post('CATEG_CODE2');
			$DRIVER_CODE2	= $this->input->post('DRIVER_CODE2');
			$RSV_STARTD2	= $this->input->post('RSV_STARTD');
			$RSV_STARTDT2	= date('Y-m-d H:i:s', strtotime($RSV_STARTD2));
			$RSV_ENDD2		= $this->input->post('RSV_ENDD');
			$RSV_ENDDT2		= date('Y-m-d H:i:s', strtotime($RSV_ENDD2));
			$dateST2 		= new DateTime($this->input->post('RSV_STARTD'), new DateTimeZone('Asia/Jakarta') );
			$RSV_STARTT2 	= $dateST2->format('H:i:s');
			$dateET2 		= new DateTime($this->input->post('RSV_ENDD'), new DateTimeZone('Asia/Jakarta') );
			$RSV_ENDT2	 	= $dateET2->format('H:i:s');
			$RSV_TITLE		= $this->input->post('RSV_TITLE');
			$RSV_DESC		= $this->input->post('RSV_DESC');
			$RSV_QTY		= $this->input->post('RSV_QTY');
			$RSV_MEMO		= $this->input->post('RSV_MEMO');
			$RSV_SUBMITTER	= $this->input->post('RSV_SUBMITTER');
			$RSV_MAIL		= $this->input->post('RSV_MAIL');
			$RSV_STAT		= $this->input->post('RSV_STAT');
			//$RSV_CATEG	= $this->input->post('RSV_CATEG');

			//$RSV_STARTD		= "$RSV_STARTD $RSV_STARTT";
			//$RSV_ENDD		= "$RSV_ENDD $RSV_ENDT";

			/*echo "RSV_CODE : $RSV_CODE<br>";
			echo "CATEG_CODE : $CATEG_CODE<br>";
			echo "CATEG_CODE2	= $CATEG_CODE2<br>";
			echo "DRIVER_CODE	= $DRIVER_CODE<br>";
			echo "DRIVER_CODE2	= $DRIVER_CODE2<br>";
			echo "RSV_STARTDT		= $RSV_STARTDT<br>";
			echo "RSV_ENDDT		= $RSV_ENDDT<br>";
			echo "RSV_STARTT		= $RSV_STARTT<br>";
			echo "RSV_ENDT		= $RSV_ENDT<br>";

			echo "RSV_TITLE		= $RSV_TITLE<br>";
			echo "RSV_DESC		= $RSV_DESC<br>";
			echo "RSV_QTY		= $RSV_QTY<br>";
			echo "RSV_MEMO		= $RSV_MEMO<br>";
			echo "RSV_SUBMITTER	= $RSV_SUBMITTER<br>";
			echo "RSV_STAT		= $RSV_STAT<br>";*/
			//$RSV_CATEG	= $RSV_CATEG";

			//return false;

			//Update Kendaraan
			$Up_VH	= "UPDATE tbl_vehicle SET VH_STAT = 0 WHERE VH_CODE = '$CATEG_CODE'";
			$this->db->query($Up_VH);
			$Up_VH2	= "UPDATE tbl_vehicle SET VH_STAT = 1 WHERE VH_CODE = '$CATEG_CODE2'";
			$this->db->query($Up_VH2);

			//Update Driver
			$Up_DR	= "UPDATE tbl_driver SET DRIVER_STAT = 0 WHERE DRIVER_CODE = '$DRIVER_CODE'";
			$this->db->query($Up_DR);
			$Up_DR2	= "UPDATE tbl_driver SET DRIVER_STAT = 1 WHERE DRIVER_CODE = '$DRIVER_CODE2'";
			$this->db->query($Up_DR2);

			$UpdRes		= array('RSV_CODE' 		=> $RSV_CODE,
								'RSV_CATEG' 	=> $RSV_CATEG,
								'CATEG_CODE' 	=> $CATEG_CODE2,
								'DRIVER_CODE'	=> $DRIVER_CODE2,
								'RSV_STARTD' 	=> $RSV_STARTD,
								'RSV_ENDD'		=> $RSV_ENDD,
								'RSV_STARTT' 	=> $RSV_STARTT,
								'RSV_ENDT'		=> $RSV_ENDT,
								'CATEG_CODE2' 	=> $CATEG_CODE2,
								'DRIVER_CODE2'	=> $DRIVER_CODE2,
								'RSV_STARTD2' 	=> $RSV_STARTD,
								'RSV_ENDD2'		=> $RSV_ENDD,
								'RSV_STARTT2' 	=> $RSV_STARTT,
								'RSV_ENDT2'		=> $RSV_ENDT,
								'RSV_TITLE'		=> $RSV_TITLE,
								'RSV_DESC'		=> $RSV_DESC,
								'RSV_QTY'		=> $RSV_QTY,
								'RSV_SUBMITTER'	=> $RSV_SUBMITTER,
								'RSV_MAIL'		=> $RSV_MAIL,
								'RSV_EMPID'		=> $DefEmp_ID,
								//'RSV_INVIT_Emp'	=> $coll_EmpID,
								//'RSV_INVIT'		=> $coll_Email,
								'RSV_CREATED'	=> date('Y-m-d H:i:s'),
								'RSV_STAT'		=> $RSV_STAT);
			$this->m_reservation->update($RSV_CODE, $UpdRes);

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}

			$url			= site_url('reservation/addVH/?id='.$this->url_encryption_helper->encode_url(""));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_eventsVH_adm()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		date_default_timezone_set("Asia/Jakarta");

		$VH_CODE_DEF	= $_GET['id'];
		$VH_CODE_DEF	= $this->url_encryption_helper->decode_url($VH_CODE_DEF);
		$EXPL			= explode('~', $VH_CODE_DEF);
		$VH_CODE		= $EXPL[0];
		$startA			= $EXPL[1];
		$endA			= $EXPL[2];

		// Our Start and End Dates
		$start 			= $this->input->get("start");
		$end 			= $this->input->get("end");

		$startdt 		= new DateTime('now'); 					// setup a local datetime
		$startdt->setTimestamp($start);							// Set the date based on timestamp
		$start_format 	= $startdt->format('Y-m-d H:i:s');

		$enddt 			= new DateTime('now'); // setup a local datetime
		$enddt->setTimestamp($end); // Set the date based on timestamp
		$end_format 	= $enddt->format('Y-m-d H:i:s');

		$timestamp 		= strtotime($end);
		$date 			= new DateTime($timestamp);
		$end_formata	= $date->format('Y-m-d H:i:s'); // 31.07.2012

		$eventsC 		= $this->m_reservation->get_eventsCVH($start_format, $end_format, $VH_CODE);
		$events 		= $this->m_reservation->get_eventsVH($start_format, $end_format, $VH_CODE);

		$data_events = array();

		foreach($events->result() as $r)
		{
			$data_events[] = array(
				"RSV_ID" => $r->RSV_ID,
				"RSV_CODE" => $r->RSV_CODE,
				"RSV_CATEG" => $r->RSV_CATEG,
				"CATEG_CODE" => $r->CATEG_CODE,
				"RSV_STARTD" => $r->RSV_STARTD,
				"RSV_ENDD" => $r->RSV_ENDD,
				"CATEG_CODE2" => $r->CATEG_CODE2,
				"VH_NOPOL" => $r->VH_NOPOL,
				"VH_MEREK" => $r->VH_MEREK,
				"DRIVER_CODE" => $r->DRIVER_CODE,
				"RSV_STARTD2" => $r->RSV_STARTD2,
				"RSV_ENDD2" => $r->RSV_ENDD2,
				"RSV_TITLE" => $r->RSV_TITLE,
				"RSV_DESC" => $r->RSV_DESC,
				"RSV_QTY" => $r->RSV_QTY,
				"RSV_SUBMITTER" => $r->RSV_SUBMITTER,
				"RSV_MAIL" => $r->RSV_MAIL,
				"RSV_STAT" => $r->RSV_STAT,
				"start" => $r->RSV_STARTD2,
				"id" => $r->RSV_ID,
				"title" => $r->RSV_TITLE,
				"description" => $r->RSV_DESC,
				"end" => $r->RSV_ENDD2,
				"start" => $r->RSV_STARTD2
			);
		}

		echo json_encode(array("events" => $data_events));
		exit();
	}

	function listVH()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$REQ_STAT	= $_GET['id'];
			$REQ_STAT	= $this->url_encryption_helper->decode_url($REQ_STAT);
			if($REQ_STAT == 1)
			{
				$REQ_STAT	= '1';
			}
			elseif($REQ_STAT == 3)
			{
				$REQ_STAT	= '3,4';
			}

			$data['title'] 		= $appName;
			$data['secAddURL'] 	= site_url('reservation/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('reservation/');
			$data['REQ_STAT'] 	= $REQ_STAT;

			$data["VHCount"] 	= $this->m_reservation->count_all_VH($DefEmp_ID, $REQ_STAT);
			$data['vwVH']		= $this->m_reservation->get_all_VH($DefEmp_ID, $REQ_STAT)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'MRS-L';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_reservation_vh_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function SendVH_TYPE($VH_TYPE)
	{
		//load model
		$convertVH_TYPE = urldecode($VH_TYPE);
		$this->load->model('m_reservation','',TRUE);
		$CountVH_TYPE = $this->m_reservation->CountVH_TYPE($convertVH_TYPE);
		$GETVH_TYPE = $this->m_reservation->GETVH_TYPE($convertVH_TYPE);
		$row = 0;
		$VH_MEREK1	= '';
		foreach($GETVH_TYPE as $rowTYPE)
		{
			$row = $row + 1;
			$VH_MEREK 	= $rowTYPE['VH_MEREK'];
			if($row == 1)
			{
				$VH_MEREK1	= $VH_MEREK;
			}
			else
			{
				$VH_MEREK1 = "$VH_MEREK1~$VH_MEREK";
			}
		}

		echo $VH_MEREK1;
	}

	function GET_VH($CATEG_CODE)
	{
		//load model
		$convertCATEG_CODE = urldecode($CATEG_CODE);
		$this->load->model('m_reservation','',TRUE);
		$GETVH_NOPOLX = $this->m_reservation->GETVH_NOPOL($convertCATEG_CODE);
		foreach($GETVH_NOPOLX as $rowNOPOL)
		{
			$NOPOL = $rowNOPOL['VH_NOPOL'];
			echo $NOPOL;
		}
		//echo $GETVH_NOPOLX;
	}

	function GET_DRCONTACT($DRIVER_CODE)
	{
		$this->load->model('m_reservation','',TRUE);
		$GETDR_CONTACTX = $this->m_reservation->GETDR_CONTACT($DRIVER_CODE);
		foreach($GETDR_CONTACTX as $rowCONTACT)
		{
			$DR_CONTACT = $rowCONTACT['DR_CONTACT'];
			echo $DR_CONTACT;
		}
	}

	function viewRR() // OK
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$AR_CODE	= $_GET['id'];
			$AR_CODE	= $this->url_encryption_helper->decode_url($AR_CODE);

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Pemesanan';
				$data['h2_title']	= 'Kamar';
			}
			else
			{
				$data['h1_title']	= 'Reservation';
				$data['h2_title']	= 'Room';
			}

			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['isReadyAdd'] 	= 0;
			$data['RR_CODE_DEF'] 	= '';
			$data['form_action']	= site_url('reservation/add_process_RR');
			$data['add_event']		= site_url('reservation/add_event_RR');
			$data['edit_event']		= site_url('reservation/edit_event_RR');
			$data['get_events']		= site_url('reservation/get_events_RR');
			$data['backURL'] 		= site_url('reservation/viewRR');

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'A';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_calendar_room', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function selectRR() // OK
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$AR_CODE	= $_GET['id'];
			$AR_CODE	= $this->url_encryption_helper->decode_url($AR_CODE);

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Pemesanan';
				$data['h2_title']	= 'Ruang Penginapan';
			}
			else
			{
				$data['h1_title']	= 'Reservation';
				$data['h2_title']	= 'Room Inn';
			}

			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['isReadyAdd'] 	= 1;
			$data['RR_CODE_DEF'] 	= $AR_CODE;
			$data['form_action']	= site_url('reservation/add_process_RR');
			$data['add_event']		= site_url('reservation/add_event_RR');
			$data['edit_event']		= site_url('reservation/edit_event_RR');
			$data['get_events']		= site_url('reservation/get_events_RR');
			$data['backURL'] 		= site_url('reservation/viewRR');

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'RESERV';
				$TTR_CATEG		= 'A-RR';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_reservation/v_calendar_room', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_events_RR()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		date_default_timezone_set("Asia/Jakarta");

		$AR_CODE_DEF	= $_GET['id'];
		$AR_CODE_DEF	= $this->url_encryption_helper->decode_url($AR_CODE_DEF);
		$EXPL			= explode('~', $AR_CODE_DEF);
		$AR_CODE		= $EXPL[0];
		$startA			= $EXPL[1];
		$endA			= $EXPL[2];

		$RSV_EMPID 		= $this->session->userdata['Emp_ID'];

		// Our Start and End Dates
		$start 			= $this->input->get("start");
		$end 			= $this->input->get("end");

		$startdt 		= new DateTime('now'); 					// setup a local datetime
		$startdt->setTimestamp($start);							// Set the date based on timestamp
		$start_format 	= $startdt->format('Y-m-d H:i:s');

		$enddt 			= new DateTime('now'); // setup a local datetime
		$enddt->setTimestamp($end); // Set the date based on timestamp
		$end_format 	= $enddt->format('Y-m-d H:i:s');

		$timestamp 		= strtotime($end);
		$date 			= new DateTime($timestamp);
		$end_formata	= $date->format('Y-m-d H:i:s'); // 31.07.2012

		$eventsC 		= $this->m_reservation->get_eventsCRR($start_format, $end_format, $AR_CODE, $RSV_EMPID);
		$events 		= $this->m_reservation->get_eventsRR($start_format, $end_format, $AR_CODE, $RSV_EMPID);

		$data_events = array();

		foreach($events->result() as $r)
		{
			$data_events[] = array(
				"RSV_ID" => $r->RSV_ID,
				"RSV_CODE" => $r->RSV_CODE,
				"RSV_CATEG" => $r->RSV_CATEG,
				"CATEG_CODE" => $r->CATEG_CODE,
				"RSV_STARTD" => $r->RSV_STARTD,
				"RSV_ENDD" => $r->RSV_ENDD,
				"CATEG_CODE2" => $r->CATEG_CODE2,
				"RSV_STARTD2" => $r->RSV_STARTD2,
				"RSV_ENDD2" => $r->RSV_ENDD2,
				"RSV_TITLE" => $r->RSV_TITLE,
				"RSV_DESC" => $r->RSV_DESC,
				"RSV_QTY" => $r->RSV_QTY,
				"RSV_SUBMITTER" => $r->RSV_SUBMITTER,
				"RSV_MAIL" => $r->RSV_MAIL,
				"RSV_STAT" => $r->RSV_STAT,
				"start" => $r->RSV_STARTD2,
				"id" => $r->RSV_ID,
				"title" => $r->RSV_TITLE,
				"description" => $r->RSV_DESC,
				"end" => $r->RSV_ENDD2,
				"start" => $r->RSV_STARTD2
			);
		}

		echo json_encode(array("events" => $data_events));
		exit();
	}

	function add_event_RR()
	{
		$this->load->model('m_reservation', '', TRUE);

		/* Our calendar data */
		date_default_timezone_set("Asia/Jakarta");

		$this->db->trans_begin();

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

		$RSV_CODE 		= date('YmdHis');
		$RSV_CATEG		= 'RR';
		$RSV_STARTD		= $this->input->post("RSV_STARTD", TRUE);		// MR Code
		$RSV_ENDD		= $this->input->post("RSV_ENDD", TRUE);

		if(!empty($RSV_STARTD))
		{
		   $sd = DateTime::createFromFormat("Y/m/d H:i", $RSV_STARTD);
		   $RSV_STARTD = $sd->format('Y-m-d H:i:s');
		   $RSV_STARTD_timestamp = $sd->getTimestamp();

		   $RSV_STARTT = $sd->format('H:i:s');
		}
		else
		{
		   $RSV_STARTD = date("Y-m-d H:i:s", time());
		   $RSV_STARTD_timestamp = time();

		   $RSV_STARTT = date("H:i:s", time());
		}

		if(!empty($RSV_ENDD))
		{
		   $ed = DateTime::createFromFormat("Y/m/d H:i", $RSV_ENDD);
		   $RSV_ENDD = $ed->format('Y-m-d H:i:s');
		   $RSV_ENDD_timestamp = $ed->getTimestamp();

		   $RSV_ENDT = $ed->format('H:i:s');
		}
		else
		{
		   $RSV_ENDD = date("Y-m-d H:i:s", time());
		   $RSV_ENDD_timestamp = time();

		   $RSV_ENDT = date("H:i:s", time());
		}

		$CATEG_CODE		= $this->input->post("CATEG_CODE", TRUE);		// RR Code
		$RSV_TITLE		= $this->input->post("RSV_TITLE", TRUE);
		$RSV_DESC		= $this->input->post("RSV_DESC", TRUE);
		$RSV_QTY		= $this->input->post("RSV_QTY", TRUE);
		$RSV_SUBMITTER	= $this->input->post("RSV_SUBMITTER", TRUE);
		$RSV_MAIL		= $this->input->post("RSV_MAIL", TRUE);
		$RSV_STAT		= $this->input->post("RSV_STAT", TRUE);

		/*echo "$RSV_CODE<br>";
		echo "$RSV_CATEG<br>";
		echo "$RSV_STARTD<br>";		// MR Code
		echo "$RSV_ENDD<br>";
		echo "$RSV_STARTT<br>";
		echo "$RSV_ENDT<br>";
		echo "$RSV_TITLE<br>";
		echo "$RSV_DESC<br>";
		echo "$RSV_QTY<br>";
		echo "$RSV_SUBMITTER<br>";
		echo "$RSV_MAIL<br>";
		echo "$RSV_STAT<br>";
		return false;*/

		$InsRes			= array('RSV_CODE' 		=> $RSV_CODE,
								'RSV_CATEG' 	=> $RSV_CATEG,
								'CATEG_CODE' 	=> $this->input->post('CATEG_CODE'),
								'RSV_STARTD' 	=> $RSV_STARTD,
								'RSV_ENDD'		=> $RSV_ENDD,
								'RSV_STARTT' 	=> $RSV_STARTT,
								'RSV_ENDT'		=> $RSV_ENDT,
								'CATEG_CODE2' 	=> $this->input->post('CATEG_CODE'),
								'RSV_STARTD2' 	=> $RSV_STARTD,
								'RSV_ENDD2'		=> $RSV_ENDD,
								'RSV_STARTT2' 	=> $RSV_STARTT,
								'RSV_ENDT2'		=> $RSV_ENDT,
								'RSV_TITLE'		=> $this->input->post('RSV_TITLE'),
								'RSV_DESC'		=> $this->input->post('RSV_DESC'),
								'RSV_QTY'		=> $this->input->post('RSV_QTY'),
								'RSV_SUBMITTER'	=> $this->input->post('RSV_SUBMITTER'),
								'RSV_MAIL'		=> $this->input->post('RSV_MAIL'),
								'RSV_EMPID'		=> $DefEmp_ID,
								'RSV_CREATED'	=> date('Y-m-d H:i:s'),
								'RSV_STAT'		=> $this->input->post('RSV_STAT'));
		$this->m_reservation->add($InsRes);

		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= '';
			$MenuCode 		= 'RESERV';
			$TTR_CATEG		= 'C-RR';

			$this->load->model('m_updash/m_updash', '', TRUE);
			$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
									'TTR_DATE' 		=> date('Y-m-d H:i:s'),
									'TTR_MNCODE'	=> $MenuCode,
									'TTR_CATEG'		=> $TTR_CATEG,
									'TTR_PRJCODE'	=> $TTR_PRJCODE,
									'TTR_REFDOC'	=> $TTR_REFDOC);
			$this->m_updash->updateTrack($paramTrack);
		// END : UPDATE TO T-TRACK

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}

		$url			= site_url('reservation/selectRR/?id='.$this->url_encryption_helper->encode_url($CATEG_CODE));
		redirect($url);
	}

	function edit_event_RR()
    {
		$this->load->model('m_reservation', '', TRUE);

		/* Our calendar data */
		date_default_timezone_set("Asia/Jakarta");

		$this->db->trans_begin();

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

		$RSV_CODE 		= $this->input->post("RSV_CODE", TRUE);
		$RSV_CATEG		= 'RR';
		$RSV_STARTD		= $this->input->post("RSV_STARTD", TRUE);		// MR Code
		$RSV_ENDD		= $this->input->post("RSV_ENDD", TRUE);

		if(!empty($RSV_STARTD))
		{
		   $sd = DateTime::createFromFormat("Y/m/d H:i", $RSV_STARTD);
		   $RSV_STARTD = $sd->format('Y-m-d H:i:s');
		   $RSV_STARTD_timestamp = $sd->getTimestamp();

		   $RSV_STARTT = $sd->format('H:i:s');
		}
		else
		{
		   $RSV_STARTD = date("Y-m-d H:i:s", time());
		   $RSV_STARTD_timestamp = time();

		   $RSV_STARTT = date("H:i:s", time());
		}

		if(!empty($RSV_ENDD))
		{
		   $ed = DateTime::createFromFormat("Y/m/d H:i", $RSV_ENDD);
		   $RSV_ENDD = $ed->format('Y-m-d H:i:s');
		   $RSV_ENDD_timestamp = $ed->getTimestamp();

		   $RSV_ENDT = $ed->format('H:i:s');
		}
		else
		{
		   $RSV_ENDD = date("Y-m-d H:i:s", time());
		   $RSV_ENDD_timestamp = time();

		   $RSV_ENDT = date("H:i:s", time());
		}

		$CATEG_CODE		= $this->input->post("CATEG_CODE", TRUE);		// MR Code
		$RSV_TITLE		= $this->input->post("RSV_TITLE", TRUE);
		$RSV_DESC		= $this->input->post("RSV_DESC", TRUE);
		$RSV_QTY		= $this->input->post("RSV_QTY", TRUE);
		$RSV_SUBMITTER	= $this->input->post("RSV_SUBMITTER", TRUE);
		$RSV_MAIL		= $this->input->post("RSV_MAIL", TRUE);
		$RSV_STAT		= $this->input->post("RSV_STAT", TRUE);

		$UpdRes			= array('RSV_CODE' 		=> $RSV_CODE,
								'RSV_CATEG' 	=> $RSV_CATEG,
								'CATEG_CODE' 	=> $this->input->post('CATEG_CODE'),
								'RSV_STARTD' 	=> $RSV_STARTD,
								'RSV_ENDD'		=> $RSV_ENDD,
								'RSV_STARTT' 	=> $RSV_STARTT,
								'RSV_ENDT'		=> $RSV_ENDT,
								'CATEG_CODE2' 	=> $this->input->post('CATEG_CODE'),
								'RSV_STARTD2' 	=> $RSV_STARTD,
								'RSV_ENDD2'		=> $RSV_ENDD,
								'RSV_STARTT2' 	=> $RSV_STARTT,
								'RSV_ENDT2'		=> $RSV_ENDT,
								'RSV_TITLE'		=> $this->input->post('RSV_TITLE'),
								'RSV_DESC'		=> $this->input->post('RSV_DESC'),
								'RSV_QTY'		=> $this->input->post('RSV_QTY'),
								'RSV_SUBMITTER'	=> $this->input->post('RSV_SUBMITTER'),
								'RSV_MAIL'		=> $this->input->post('RSV_MAIL'),
								'RSV_EMPID'		=> $DefEmp_ID,
								'RSV_CREATED'	=> date('Y-m-d H:i:s'),
								'RSV_STAT'		=> $this->input->post('RSV_STAT'));
		$this->m_reservation->update($RSV_CODE, $UpdRes);

		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= '';
			$MenuCode 		= 'RESERV-RR';
			$TTR_CATEG		= 'RESERV-RRUP-P';

			$this->load->model('m_updash/m_updash', '', TRUE);
			$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
									'TTR_DATE' 		=> date('Y-m-d H:i:s'),
									'TTR_MNCODE'	=> $MenuCode,
									'TTR_CATEG'		=> $TTR_CATEG,
									'TTR_PRJCODE'	=> $TTR_PRJCODE,
									'TTR_REFDOC'	=> $TTR_REFDOC);
			$this->m_updash->updateTrack($paramTrack);
		// END : UPDATE TO T-TRACK

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}

		$url			= site_url('reservation/selectRR/?id='.$this->url_encryption_helper->encode_url($CATEG_CODE));
		redirect($url);
    }

	function printMEMO()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$RSV_CODE	= $_GET['id'];
			$RSV_CODE	= $this->url_encryption_helper->decode_url($RSV_CODE);


			$data['countRVH']		= $this->m_reservation->count_RVH($RSV_CODE);
			$data['viewRVH']		= $this->m_reservation->view_RVH($RSV_CODE);

			$data['TTD_RSVMGR']		= $this->input->post('TTD_RSVMGR', TRUE);
			$data['TTD_DEPTMGR']	= $this->input->post('TTD_DEPTMGR', TRUE);
			$VH_CODE				= $this->input->post('VH_CODE', TRUE);
			$sqlVH					= "SELECT VH_MEREK FROM tbl_vehicle WHERE VH_CODE = '$VH_CODE'";
			$ressSQL				= $this->db->query($sqlVH)->result();
			foreach($ressSQL as $row)
			{
				$data['VH_MEREK']	= $row->VH_MEREK;
			}
			$data['VH_NOPOL']	= $this->input->post('VH_NOPOL', TRUE);
			$DRIVER_CODE		= $this->input->post('DRIVER_CODE', TRUE);
			$sqlDR				= "SELECT DRIVER FROM tbl_driver WHERE DRIVER_CODE = '$DRIVER_CODE'";
			$ressDR				= $this->db->query($sqlDR)->result();
			foreach($ressDR as $rDR)
			{
				$data['DRIVER']	= $rDR->DRIVER;
			}

			$this->load->view('v_reservation/v_reservation_vh_adm_print', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function setprintMEMO()
	{
		$this->load->model('m_reservation', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$data['appName'] = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$RSV_CODE			= $_GET['id'];
			$data['RSV_CODE']	= $this->url_encryption_helper->decode_url($RSV_CODE);

			$data['countRVH']		= $this->m_reservation->count_RVH($data['RSV_CODE']);
			$data['viewRVH']		= $this->m_reservation->view_RVH($data['RSV_CODE']);

			$this->load->view('v_reservation/v_reservation_setprint', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function DEPT_VH($RSV_SUBMITTER)
	{
		$DeptVH			= "tbl_grdept_vehicle WHERE VH_BORROWER = '$RSV_SUBMITTER'";
		$ressDeptVH		= $this->db->count_all($DeptVH);
		if($ressDeptVH > 0)
		{
			$sqlDeptVH	= "SELECT * FROM tbl_grdept_vehicle WHERE VH_BORROWER = '$RSV_SUBMITTER'";
			$rSQLDept	= $this->db->query($sqlDeptVH)->result();
			foreach($rSQLDept as $r)
			{
				$VH_BORROWER		= $r->VH_BORROWER;
				$VH_DEPT_BORROWER	= $r->VH_DEPT_BORROWER;
				$VH_MGRDEV			= $r->VH_MGRDEV;
				$VH_DEPT_MGRDEV		= $r->VH_DEPT_MGRDEV;
				echo "$VH_BORROWER~$VH_DEPT_BORROWER~$VH_MGRDEV~$VH_DEPT_MGRDEV";
			}
		}
		else
		{
			echo "";
		}
	}
}
