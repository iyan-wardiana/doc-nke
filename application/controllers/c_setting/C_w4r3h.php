<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 07 Maret 2019
 * File Name	= C_w4r3h.php
 * Location		= -
*/

class C_w4r3h extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_setting/m_wh/m_wh', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		
		function cut_text2($var, $len = 200, $txt_titik = "-") 
		{
			$var1	= explode("</p>",$var);
			$var	= $var1[0];
			if (strlen ($var) < $len) 
			{ 
				return $var; 
			}
			if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
			{
				return $match [1] . $txt_titik;
			}
			else
			{
				return substr ($var, 0, $len) . $txt_titik;
			}
		}
	}
 	public function index() // GOOD
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_w4r3h/w4r3h_1nd/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function w4r3h_1nd() // GOOD
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 			= $appName;		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Gudang';
				$data['h2_title']	= 'Master';
			}
			else
			{
				$data['h1_title']	= 'Warehouse';
				$data['h2_title']	= 'Master';
			}
			$data["MenuCode"] 		= 'MN393';
			
			$num_rows 				= $this->m_wh->count_all_wh();
			$data["cData"] 			= $num_rows;	 
			$data['vData'] 			= $this->m_wh->get_all_wh()->result();
			
			$this->load->view('v_setting/v_wh/v_wh', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function w4r3h_l44d() // GOOD
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';	
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Gudang';
				$data['h2_title']	= 'Master';
			}
			else
			{
				$data['h1_title']	= 'Warehouse';
				$data['h2_title']	= 'Master';
			}
			
			$data['form_action']	= site_url('c_setting/c_w4r3h/add_process');
			$data['backURL'] 		= site_url('c_setting/c_w4r3h/');
			
			$MenuCode 			= 'MN393';
			$data["MenuCode"] 	= 'MN393';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN393';
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
			
			$this->load->view('v_setting/v_wh/v_wh_form', $data);
		}
		else
		{
			rredirect('__l1y');
		}
	}
	
	function add_process() // GOOD
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
	
			$this->load->model('m_projectlist/m_projectlist', '', TRUE);
			
			$MenuCode 		= 'MN393';
			
			$TRXTIME1		= date('ymdHis');
			
			$InsWH	= array('WH_NUM'	=> $TRXTIME1,
							'WH_CODE'	=> $this->input->post('WH_CODE'),
							'WH_NAME'	=> $this->input->post('WH_NAME'),
							'WH_LOC'	=> $this->input->post('WH_LOC'),
							'PRJCODE'	=> $this->input->post('PRJCODE'),
							'ISWHPROD'	=> $this->input->post('ISWHPROD'));
			$this->m_wh->add($InsWH);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp 	= $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_setting/c_w4r3h/index/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update() // GOOD
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$WH_NUM				= $_GET['id'];
		$WH_NUM				= $this->url_encryption_helper->decode_url($WH_NUM);
		$data["MenuCode"] 	= 'MN393';
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Gudang';
				$data['h2_title']	= 'Master';
			}
			else
			{
				$data['h1_title']	= 'Warehouse';
				$data['h2_title']	= 'Master';
			}
			
			$data['form_action']	= site_url('c_setting/c_w4r3h/update_process');
			$data['backURL'] 		= site_url('c_setting/c_w4r3h/');
			
			$getWH 					= $this->m_wh->get_wh($WH_NUM)->row();
			
			$data['default']['WH_NUM']		= $getWH->WH_NUM;
			$data['default']['WH_CODE']		= $getWH->WH_CODE;
			$data['default']['WH_NAME']		= $getWH->WH_NAME;
			$data['default']['WH_LOC']		= $getWH->WH_LOC;
			$data['default']['PRJCODE'] 	= $getWH->PRJCODE;
			$data['default']['ISWHPROD'] 	= $getWH->ISWHPROD;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN393';
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
			
			$this->load->view('v_setting/v_wh/v_wh_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // GOOD
	{
		$this->load->model('m_setting/m_tax/m_tax', '', TRUE);
		
		$MLANG_CODE	= $this->input->post('MLANG_CODE');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$WH_NUM	= $this->input->post('WH_NUM');
			$UpdWH		= array('WH_NUM'	=> $WH_NUM,
								'WH_CODE'	=> $this->input->post('WH_CODE'),
								'WH_NAME'	=> $this->input->post('WH_NAME'),
								'WH_LOC'	=> $this->input->post('WH_LOC'),
								'PRJCODE'	=> $this->input->post('PRJCODE'),
								'ISWHPROD'	=> $this->input->post('ISWHPROD'));							
			$this->m_wh->update($WH_NUM, $UpdWH);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp 	= $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_setting/c_w4r3h/index/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function getCode() // GOOD
	{
		$collData	= $_POST['collID'];
		$colExpl	= explode("~", $collData);
		$WHCODE 	= $colExpl[1];

		$sqlWH 		= "tbl_warehouse WHERE WH_CODE = '$WHCODE'";
		$resWH 		= $this->db->count_all($sqlWH);

		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Kode Gudang sudah digunakan.";
		}
		else
		{
			$alert1	= "Warehouse code already used.";
		}
		$resultWH	= "$resWH~$alert1";
		echo $resultWH;
	}
}