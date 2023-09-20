<?php
/*  
	* Author		= Dian Hermanto
	* Create Date	= 4 September 2020
	* File Name		= C_vehicle.php
	* Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class c_vehicle  extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_setting/m_vehicle/m_vehicle', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
	}

 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_vehicle/i4x/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function i4x($offset=0)
	{
		$this->load->model('m_setting/m_vehicle/m_vehicle', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Vehicle';
			$data['h3_title']			= 'Setting';
			$data['secAddURL'] 			= site_url('c_setting/c_vehicle/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_setting/c_vehicle/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			
			$num_rows 					= $this->m_vehicle->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
	 		$data["MenuCode"] 			= 'MN352';
			
			$data['vAssetGroup']		= $this->m_vehicle->get_last_ten_AG()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN419';
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

			$this->load->view('v_setting/v_vehicle/v_vehicle', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() // GOOD
	{
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("VH_TYPE");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_vehicle->get_AllDataC($search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_vehicle->get_AllDataL($search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$VH_CODE		= $dataI['VH_CODE'];
				$VH_TYPE		= $dataI['VH_TYPE'];
				$VH_MEREK		= $dataI['VH_MEREK'];
				$VH_NOPOL		= $dataI['VH_NOPOL'];
				$VH_COLOR		= $dataI['VH_COLOR'];

				$secUpd			= site_url('c_setting/c_vehicle/update/?id='.$this->url_encryption_helper->encode_url($VH_CODE));
				$secDelIcut 	= base_url().'index.php/__l1y/trash_Veh/?id=';
				$delID 			= "$secDelIcut~tbl_vehicle~VH_CODE~$VH_CODE~$VH_MEREK~$VH_NOPOL";
				
				$sqlsnC 		= "tbl_sn_header WHERE VEH_CODE = '$VH_CODE'";
				$ressnC 		= $this->db->count_all($sqlsnC);

				if($ressnC == 0) 
				{
					$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$output['data'][] = array($noU.".",
										  $VH_CODE,
										  $VH_TYPE,
										  $VH_MEREK,
										  $VH_NOPOL,
										  $secAction);

				$noU		= $noU + 1;
			}


			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function v3hl44d() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 				= $appName;
			
			// GET MENU DESC
				$mnCode				= 'MN419';
				$MenuCode			= 'MN419';
				$data["MenuCode"] 	= 'MN419';
				$data["MenuApp"] 	= 'MN419';
				//$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$docPatternPosition 		= 'Especially';	
			$data['error']				= '';
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add Vehicle';
			$data['h3_title']			= 'Vehicle';
			$data['form_action']		= site_url('c_setting/c_vehicle/add_process');
			$data['link'] 				= array('link_back' => anchor('c_setting/c_vehicle/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= site_url('c_setting/c_vehicle/');
			$MenuCode 					= 'MN419';
			$data["MenuCode"] 			= 'MN419';
			$data['viewDocPattern'] 	= $this->m_vehicle->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN419';
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
			
			$this->load->view('v_setting/v_vehicle/v_vehicle_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_setting/m_vehicle/m_vehicle', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$file 				= $_FILES['userfile'];
			$VH_IMAGE 			= $file['name'];
			$VH_CODE	 		= $this->input->post('VH_CODE');
			
			$InsAG 		= array('VH_CODE' 		=> $this->input->post('VH_CODE'),
								'VH_TYPE' 		=> $this->input->post('VH_TYPE'),
								'VH_MEREK' 		=> $this->input->post('VH_MEREK'),
								'VH_NOPOL'	 	=> $this->input->post('VH_NOPOL'),
								'VH_COLOR'	 	=> $this->input->post('VH_COLOR'));

			$this->m_vehicle->add($InsAG);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN419';
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
			
			$url			= site_url('c_setting/c_vehicle/i4x/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update() // OK
	{	
		$this->load->model('m_setting/m_vehicle/m_vehicle', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$VH_CODE	= $_GET['id'];
			$VH_CODE	= $this->url_encryption_helper->decode_url($VH_CODE);
			
			$data['title'] 			= $appName;
			
			// GET MENU DESC
				$mnCode				= 'MN419';
				$MenuCode			= 'MN419';
				$data["MenuCode"] 	= 'MN419';
				$data["MenuApp"] 	= 'MN419';
				//$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['error']			= '';
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Update Vehicle';
			$data['h3_title']		= 'Vehicle';
			$data['form_action']	= site_url('c_setting/c_vehicle/update_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_vehicle/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_setting/c_vehicle/');
			
			$getAG 					= $this->m_vehicle->get_AG($VH_CODE)->row();
			
			$data['default']['VH_CODE'] 		= $getAG->VH_CODE;
			$data['default']['VH_TYPE'] 		= $getAG->VH_TYPE;
			$data['default']['VH_MEREK'] 		= $getAG->VH_MEREK;
			$data['default']['VH_NOPOL'] 		= $getAG->VH_NOPOL;
			$data['default']['VH_COLOR'] 		= $getAG->VH_COLOR;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN419';
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
			
			$this->load->view('v_setting/v_vehicle/v_vehicle_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process()
	{	
		$this->load->model('m_setting/m_vehicle/m_vehicle', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$VH_CODE	= $this->input->post('VH_CODE');

			$UpdAG 		= array('VH_CODE' 		=> $this->input->post('VH_CODE'),
								'VH_TYPE' 		=> $this->input->post('VH_TYPE'),
								'VH_MEREK'		=> $this->input->post('VH_MEREK'),
								'VH_NOPOL'	 	=> $this->input->post('VH_NOPOL'),
								'VH_COLOR'	 	=> $this->input->post('VH_COLOR'));
			
			$this->m_vehicle->update($VH_CODE, $UpdAG);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN419';
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
				
			$url			= site_url('c_setting/c_vehicle/i4x/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}