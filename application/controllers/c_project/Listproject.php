<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 Februari 2017
 * File Name	= Listproject.php
 * Location		= -
*/

class Listproject  extends CI_Controller  
{
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/listproject/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function index1($offset=0)
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Project List';
			$data['secAddURL'] 	= site_url('c_project/listproject/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 	= site_url('c_project/listproject/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 				= $this->m_listproject->count_all_num_rows();
			$data["recordcount"] 	= $num_rows;
			$data['MenuCode'] 		= 'MN126';
	 
			$data['vewproject']		= $this->m_listproject->get_last_ten_project()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN126';
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
			
			$this->load->view('v_project/v_listproject/listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{	
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['h2_title']	= 'Add Project';
			$data['main_view'] 	= 'v_project/v_listproject/listproject_form';
			$data['main_view2'] = 'v_project/v_listproject/getaddress_sd';
			$data['form_action']= site_url('c_project/listproject/add_process');
			$data['backURL'] 	= site_url('c_project/listproject/');
			
			$MenuCode 				= 'MN126';
			$data['MenuCode'] 		= 'MN126';
			$data['viewDocPattern'] = $this->m_listproject->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN126';
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
			
			$this->load->view('v_project/v_listproject/listproject_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
		
	function getTheCode($PRJCODE) // OK
	{ 	
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		$recordcountProj 	= $this->m_listproject->count_all_num_rowsProj($PRJCODE);
		echo $recordcountProj;
	}
	
	function add_process() // OK
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$proj_Number			= $this->input->post('proj_Number');
			$PRJCODE 				= $this->input->post('PRJCODE');
			$PRJNAME 				= $this->input->post('PRJNAME');
			$PRJDATE				= date('Y-m-d',strtotime($this->input->post('PRJDATE')));
			$PRJDATE_CO				= date('Y-m-d',strtotime($this->input->post('PRJDATE_CO')));
			$PRJEDAT				= date('Y-m-d',strtotime($this->input->post('PRJEDAT')));
			$PRJCNUM 				= $this->input->post('PRJCNUM');
			$PRJOWN 				= $this->input->post('PRJOWN');
			$PRJCURR				= $this->input->post('PRJCURR');
			$PRJCOST 				= $this->input->post('PRJCOST');
			$PRJLOCT 				= $this->input->post('PRJLOCT');
			$PRJLKOT 				= $this->input->post('PRJLKOT');
			$PRJ_MNG1				= $this->input->post('PRJ_MNG');
			$QTY_SPYR				= $this->input->post('QTY_SPYR');
			$PRJNOTE				= $this->input->post('PRJNOTE');
			$PRC_STRK				= $this->input->post('PRC_STRK');
			$PRC_ARST				= $this->input->post('PRC_ARST');
			$PRC_MKNK				= $this->input->post('PRC_MKNK');
			$PRC_ELCT				= $this->input->post('PRC_ELCT');
			$PRJSTAT 				= $this->input->post('PRJSTAT');			
			$PRJCBNG				= '';			
			$Patt_Year				= date('Y',strtotime($this->input->post('PRJDATE')));
			$Patt_Number			= $this->input->post('Patt_Number');
			
			$CURRRATE				= 1;
			if($PRJCURR == 'IDR')
			{
				//$CURRRATE 	= 1;
			}
			else
			{
				//$PRJCOST 	= $this->input->post('proj_amountUSD');
				//$CURRRATE 	= $this->input->post('CURRRATE');
				//$PRJCOST	= $PRJCOST1 * $CURRRATE;
			}
			
			$selStep	= 0;
			$PRJ_MNG	= '';
			if($PRJ_MNG1 != '')
			{
				foreach ($PRJ_MNG1 as $sel_pm)
				{
					$selStep	= $selStep + 1;
					if($selStep == 1)
					{
						$user_to	= explode ("|",$sel_pm);
						$user_ID	= $user_to[0];
						$PRJ_MNG	= $user_ID;
						//$coll_MADD	= $user_ADD;
					}
					else
					{					
						$user_to	= explode ("|",$sel_pm);
						$user_ID	= $user_to[0];			
						$PRJ_MNG	= "$TASKD_EMPID2;$user_ID";
						//$coll_MADD	= "$coll_MADD;$user_ADD";
					}
				}
			}
			
			$projectheader = array('proj_Number' 	=> $proj_Number,
							'PRJCODE'				=> $PRJCODE,
							'PRJCNUM'				=> $PRJCNUM,
							'PRJNAME'				=> $PRJNAME,
							'PRJLOCT'				=> $PRJLOCT,
							'PRJOWN'				=> $PRJOWN,
							'PRJDATE'				=> $PRJDATE,
							'PRJDATE_CO'			=> $PRJDATE_CO,
							'PRJEDAT'				=> $PRJEDAT,
							'PRJCOST'				=> $PRJCOST,
							'PRJLKOT'				=> $PRJLKOT,
							'PRJCBNG'				=> $PRJCBNG,
							'PRJCURR'				=> $PRJCURR,
							'CURRRATE'				=> $CURRRATE,
							'PRJSTAT'				=> $PRJSTAT,
							'PRJNOTE'				=> $PRJNOTE,
							'PRJ_MNG'				=> $PRJ_MNG,
							'QTY_SPYR'				=> $QTY_SPYR,
							'PRC_STRK'				=> $PRC_STRK,
							'PRC_ARST'				=> $PRC_ARST,
							'PRC_MKNK'				=> $PRC_MKNK,
							'PRC_ELCT'				=> $PRC_ELCT,
							'Patt_Year'				=> $Patt_Year,
							'Patt_Number'			=> $Patt_Number);
			$this->m_listproject->add($projectheader);
			
			// CHECK IN PROFLOSS
				$sqlPL	= "tbl_profitloss WHERE PRJCODE = '$PRJCODE'";
				$resPL	= $this->db->count_all($sqlPL);
				if($resPL == 0)
				{
					$insPL	= array('PRJCODE' 	=> $PRJCODE,
									'PRJCOST' 	=> $PRJCOST,
									'PRJNAME' 	=> $PRJNAME);
					$this->m_listproject->addPL($insPL);
				}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $proj_Number;
				$MenuCode 		= 'MN126';
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
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/listproject/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function do_upload()
	{ 
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$PRJCODE			= $this->input->post('PRJCODE');
		
		// CEK FILE
        $file 				= $_FILES['userfile'];
		$nameFile			= $_FILES["userfile"]["name"];
		$ext 				= end((explode(".", $nameFile)));
			
		if (!file_exists('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE))
		{
			mkdir('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE, 0777, true);
		}
		
		$file 						= $_FILES['userfile'];
		$file_name 					= $file['name'];
		$config['upload_path']   	= "assets/AdminLTE-2.0.5/project_image/$PRJCODE/"; 
		$config['allowed_types']	= 'gif|jpg|png'; 
		$config['overwrite'] 		= TRUE;
		$config['max_size']     	= 1000000; 
		$config['max_width']    	= 10024; 
		$config['max_height']    	= 10000;  
		$config['file_name']       	= $file['name'];
		
        $this->load->library('upload', $config);
		
        if ( ! $this->upload->do_upload('userfile')) 
		{
			//$data['Emp_ID']		= $Emp_ID;
			//$data['task'] 		= 'edit';
         }
         else 
		 {
            //$data['path']			= $file_name;
			//$data['Emp_ID']			= $Emp_ID;
			//$data['task'] 			= 'edit';
            //$data['showSetting']	= 0;
            $this->m_listproject->updatePict($PRJCODE, $nameFile);
         }
		 
         $sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/listproject/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function update()
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Project';
			$data['main_view'] 		= 'v_project/v_listproject/listproject_form';
			$data['form_action']	= site_url('c_project/listproject/update_process');
			$data['backURL'] 		= site_url('c_project/listproject/');
			
			$data['recordcountCust'] 	= $this->m_listproject->count_all_num_rowsCust();
			$data['viewcustomer'] 		= $this->m_listproject->viewcustomer()->result();
			
			$MenuCode 					= 'MN126';
			$data['MenuCode'] 			= 'MN126';
			$data['viewDocPattern'] 	= $this->m_listproject->getDataDocPat($MenuCode)->result();
			
			$getproject = $this->m_listproject->get_PROJ_by_number($PRJCODE)->row();
					
			$data['default']['proj_Number'] = $getproject->proj_Number;
			$data['default']['PRJCODE'] 	= $getproject->PRJCODE;
			$data['default']['PRJCNUM'] 	= $getproject->PRJCNUM;
			$data['default']['PRJNAME'] 	= $getproject->PRJNAME;
			$data['default']['PRJLOCT'] 	= $getproject->PRJLOCT;
			$data['default']['PRJOWN'] 		= $getproject->PRJOWN;
			$data['default']['PRJDATE'] 	= $getproject->PRJDATE;
			$data['default']['PRJDATE_CO'] 	= $getproject->PRJDATE_CO;
			$data['default']['PRJEDAT'] 	= $getproject->PRJEDAT;
			$PRJEDAT						= $getproject->PRJEDAT;
			//echo "c_hehe $PRJEDAT";
			$data['default']['PRJCOST'] 	= $getproject->PRJCOST;
			$data['default']['PRJBOQ'] 		= $getproject->PRJBOQ;
			$data['default']['PRJLKOT'] 	= $getproject->PRJLKOT;
			$data['default']['PRJCBNG']		= $getproject->PRJCBNG;
			$data['default']['PRJCURR']		= $getproject->PRJCURR;
			$data['default']['CURRRATE']	= $getproject->CURRRATE;
			$data['default']['PRJSTAT'] 	= $getproject->PRJSTAT;
			$data['default']['PRJNOTE'] 	= $getproject->PRJNOTE;
			
			$data['default']['ISCHANGE']	= $getproject->ISCHANGE;
			$data['default']['REFCHGNO']	= $getproject->REFCHGNO;
			$data['default']['PRJCOST2'] 	= $getproject->PRJCOST2;
			$data['default']['CHGUSER'] 	= $getproject->CHGUSER;
			$data['default']['CHGSTAT'] 	= $getproject->CHGSTAT;
			$data['default']['PRJ_MNG'] 	= $getproject->PRJ_MNG;
			$data['default']['QTY_SPYR'] 	= $getproject->QTY_SPYR;
			$data['default']['PRC_STRK'] 	= $getproject->PRC_STRK;
			$data['default']['PRC_ARST'] 	= $getproject->PRC_ARST;
			$data['default']['PRC_MKNK'] 	= $getproject->PRC_MKNK;
			$data['default']['PRC_ELCT'] 	= $getproject->PRC_ELCT;
			$data['default']['PRJ_IMGNAME'] = $getproject->PRJ_IMGNAME;
			$data['default']['Patt_Year'] 	= $getproject->Patt_Year;
			$data['default']['Patt_Number'] = $getproject->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getproject->proj_Number;
				$MenuCode 		= 'MN126';
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
			
			$this->load->view('v_project/v_listproject/listproject_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function vInpProjDet($PRJCODE) // OK
	{		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;	
			
			$data['proj_Code'] 		= $PRJCODE;
			$data['title'] 			= $appName;
			$data['form_action']	= site_url('c_project/listproject/vInpProjDet_process');
			$data['h2_title'] 		= 'Input Project Progress';
			
			$this->load->view('v_project/v_listproject/project_sd_detInput', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function vInpProjDet_process() // HOLD - LANGSUNG DI HALAMAN POP UP
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		$this->db->trans_begin();
		
		//$this->m_listproject->deleteProjDet($this->input->post('proj_Code'));
		
		// untuk penyimpanan ke tabel tproject_header
		$projectDet = array('proj_Code' 	=> $this->input->post('proj_Code'),
						'OrigProj_Value'	=> $this->input->post('OrigProj_Value'),
						'Remeasure_Value'	=> $this->input->post('Remeasure_Value'),
						'SIntruc_Value'		=> $this->input->post('SIntruc_Value'),
						
						'RemPropos_Value'	=> $this->input->post('RemPropos_Value'),
						'RemApprov_Value'	=> $this->input->post('RemApprov_Value'),
						'RemDenied_Value'	=> $this->input->post('RemDenied_Value'),
						
						'SInPropos_Value'	=> $this->input->post('SInPropos_Value'),
						'SInApprov_Value'	=> $this->input->post('SInApprov_Value'),
						'SInDenied_Value'	=> $this->input->post('SInDenied_Value'),
						
						'CIDP_Value'		=> $this->input->post('CIDP_Value'),
						'CIProgress_Value'	=> $this->input->post('CIProgress_Value'),
						'CIOthers_Value'	=> $this->input->post('CIOthers_Value'),
						
						'COSDBP_Value'		=> $this->input->post('COSDBP_Value'),
						'COOStanding_Value'	=> $this->input->post('COOStanding_Value'),
						
						'LastUpdate'		=> $this->input->post('LastUpdate'),
						'UpdatedBy'			=> $this->input->post('UpdatedBy'));	
											
		$this->m_listproject->addInpProjDet($projectDet);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		
		$this->session->set_flashdata('message', 'Data succesfull to insert.!');
		redirect('c_project/listproject/');
	}
	
	function vProjPerform($PRJCODE) // OK
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'View Project Performance';
			
			$data['proj_Code'] 		= $PRJCODE;
			
			$this->load->view('v_project/v_listproject/project_sd_perform', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			$DATE_CREATED	= date('Y-m-d H:i:s');
			
			$proj_Number			= $this->input->post('proj_Number');
			$PRJCODE 				= $this->input->post('PRJCODE');
			$PRJNAME 				= $this->input->post('PRJNAME');
			$PRJDATE				= date('Y-m-d',strtotime($this->input->post('PRJDATE')));
			$PRJDATE_CO				= date('Y-m-d',strtotime($this->input->post('PRJDATE_CO')));
			$PRJEDAT				= date('Y-m-d',strtotime($this->input->post('PRJEDAT')));
			$PRJCNUM 				= $this->input->post('PRJCNUM');
			$PRJOWN 				= $this->input->post('PRJOWN');
			$PRJCURR				= $this->input->post('PRJCURR');
			$PRJCOST 				= $this->input->post('PRJCOST');
			$PRJLOCT 				= $this->input->post('PRJLOCT');
			$PRJLKOT 				= $this->input->post('PRJLKOT');
			$PRJ_MNG				= $this->input->post('PRJ_MNG');
			$QTY_SPYR				= $this->input->post('QTY_SPYR');
			$PRJNOTE				= $this->input->post('PRJNOTE');
			$PRC_STRK				= $this->input->post('PRC_STRK');
			$PRC_ARST				= $this->input->post('PRC_ARST');
			$PRC_MKNK				= $this->input->post('PRC_MKNK');
			$PRC_ELCT				= $this->input->post('PRC_ELCT');
			$PRJSTAT 				= $this->input->post('PRJSTAT');
			$PRJCBNG				= '';			
			$Patt_Year				= date('Y',strtotime($this->input->post('PRJDATE')));
			$Patt_Number			= $this->input->post('Patt_Number');
			
			$CURRRATE				= 1;
			if($PRJCURR == 'IDR')
			{
				//$CURRRATE 	= 1;
			}
			else
			{
				//$PRJCOST 	= $this->input->post('proj_amountUSD');
				//$CURRRATE 	= $this->input->post('CURRRATE');
				//$PRJCOST	= $PRJCOST1 * $CURRRATE;
			}
			
			$projectheader = array('proj_Number' 	=> $proj_Number,
							'PRJCODE'				=> $PRJCODE,
							'PRJCNUM'				=> $PRJCNUM,
							'PRJNAME'				=> $PRJNAME,
							'PRJLOCT'				=> $PRJLOCT,
							'PRJOWN'				=> $PRJOWN,
							'PRJDATE'				=> $PRJDATE,
							'PRJEDAT'				=> $PRJEDAT,
							'PRJDATE_CO'			=> $PRJDATE_CO,
							'PRJCOST'				=> $PRJCOST,
							'PRJLKOT'				=> $PRJLKOT,
							'PRJCBNG'				=> $PRJCBNG,
							'PRJCURR'				=> $PRJCURR,
							'CURRRATE'				=> $CURRRATE,
							'PRJSTAT'				=> $PRJSTAT,
							'PRJNOTE'				=> $PRJNOTE,
							'PRJ_MNG'				=> $PRJ_MNG,
							'QTY_SPYR'				=> $QTY_SPYR,
							'PRC_STRK'				=> $PRC_STRK,
							'PRC_ARST'				=> $PRC_ARST,
							'PRC_MKNK'				=> $PRC_MKNK,
							'PRC_ELCT'				=> $PRC_ELCT,
							'Patt_Year'				=> $Patt_Year,
							'Patt_Number'			=> $Patt_Number);
							
			$this->m_listproject->update($PRJCODE, $projectheader);
			
			// CHECK IN PROFLOSS
				$sqlPL	= "tbl_profitloss WHERE PRJCODE = '$PRJCODE'";
				$resPL	= $this->db->count_all($sqlPL);
				if($resPL == 0)
				{
					$insPL	= array('PRJCODE' 	=> $PRJCODE,
									'PRJCOST' 	=> $PRJCOST,
									'PRJNAME'	=> $PRJNAME);
					$this->m_listproject->addPL($insPL);
				}
				else
				{
					$updPL	= array('PRJCODE' 	=> $PRJCODE,
									'PRJCOST' 	=> $PRJCOST,
									'PRJNAME'	=> $PRJNAME);
					$this->m_listproject->updatePL($PRJCODE, $updPL);
				}
				
			/*$PRJEDAT2		= date('Y-m-d',strtotime($this->input->post('PRJEDAT2')));	
			$PRJEDAT2a		= date('d/m/Y',strtotime($this->input->post('PRJEDAT2')));			
			$updateEndDate 	= array('PRJCODE'		=> $PRJCODE,
								'EDATORI'			=> $PRJEDAT,
								'ENDDATE'			=> $PRJEDAT2,
								'DATETIME'			=> $DATE_CREATED,
								'EMP_ID'			=> $DefEmp_ID);												
			$this->m_listproject->addUpdEDat($updateEndDate);*/
			
			//$odbc 		= odbc_connect ("DBaseNKE4", "" , "");
			//$DBFname	= "PROJECT.DBF";
			//$db 		= dbase_open('C:/NKE/DatabaseDBF/VOCHD.DBF', 2); for local
			/*$db 		= dbase_open('G:/NKE/SDBP/PROJECT.DBF', 2); // for live in 0.44
			$jmlSPL		= dbase_numrecords($db);
			$getTID1	= "UPDATE PROJECT.DBF SET ENDDATE = '$PRJEDAT2a' WHERE PRJCODE = '$PRJCODE'";
			$qTID1 		= odbc_exec($odbc, $getTID1) or die (odbc_errormsg());*/
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $proj_Number;
				$MenuCode 		= 'MN126';
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
			
			redirect('c_project/listproject/');
		}
		else
		{
			redirect('Auth');
		}
	}
	
    function inbox($offset=0)
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Project List Inbox';
			$data['main_view'] 	= 'v_project/v_listproject/listproject_inbox';

			/*$num_rows = $this->m_listproject->count_all_num_rows_inbox();
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_project/listproject/get_last_ten_project');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 2;
			$config["uri_segment"] = 3;
				
			$config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] = '</ul>';
			$config['prev_link'] = '&lt;';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&gt;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="current"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			
			$config['first_link'] = '&lt;&lt;';
			$config['last_link'] = '&gt;&gt;';
	 		
			$this->pagination->initialize($config);
	 		
			$data['viewpurord'] = $this->m_listproject->get_last_ten_PR_inbox($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();*/	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
    }
	
	function getVendAddress($vendCode)
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['myVendCode']		= "$vendCode";
		$sql = "SELECT Vend_Code, Vend_Name, Vend_Address FROM tvendor
					WHERE Vend_Code = '$vendCode'";
		$result1 = $this->db->query($sql)->result();
		foreach($result1 as $row) :
			$Vend_Name = $row->Vend_Address;
		endforeach;
		echo $Vend_Name;
	}
}