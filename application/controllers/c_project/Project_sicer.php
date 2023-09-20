<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 Maret 2017
 * File Name	= project_sicer.php
 * Notes		= -
*/

class Project_sicer  extends CI_Controller  
{
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/project_sicer/get_last_ten_projList/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function get_last_ten_projList() // OK
	{
		$this->load->model('m_project/m_project_sicer/m_project_sicer', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName		= $_GET['id'];
			$appName		= $this->url_encryption_helper->decode_url($idAppName);
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project List';
			$data['h3_title']			= 'SI certificate';
			$data['secAddURL'] 			= site_url('c_project/project_sicer/add/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 					= $this->m_project_sicer->count_all_num_rows($DefEmp_ID);
			$data["recordcount"] 		= $num_rows;
	 
			$data['viewproject']		= $this->m_project_sicer->get_last_ten_project($DefEmp_ID)->result();
			
			$this->load->view('v_project/v_project_sicer/project_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_projsicer() // OK
	{
		$this->load->model('m_project/m_project_sicer/m_project_sicer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			// Secure URL
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Project Certificate List';
			$data['h3_title'] 			= 'SI certificate';
			$data['main_view'] 			= 'v_project/v_project_sicer/project_sicer';			
			//$data['link'] 				= array('link_back' => anchor('c_project/project_sicer/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= site_url('c_project/project_sicer/');
			$data['PRJCODE'] 			= $PRJCODE;
			
			$selSearchproj_Code 		= $PRJCODE;
			$selSearchCat				= '';
			
			$num_rows 					= $this->m_project_sicer->count_all_num_rowsProjSIC($PRJCODE);
			
			$data["recordcount"] 		= $num_rows;			
			$data['CATTYPE'] 			= 'isMC';
			$data['MenuCode'] 			= 'MN254';	 
			$data['viewprojSIC']		= $this->m_project_sicer->get_last_ten_projectSIC($PRJCODE)->result();
			
			$this->load->view('v_project/v_project_sicer/project_sicer', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_project/m_project_sicer/m_project_sicer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$cancel_url 				= site_url('c_project/project_sicer/get_last_ten_projsicer/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['PRJCODE'] 			= $PRJCODE;
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add SI Certificate';
			$data['h3_title']			= 'SI Certificate';
			$data['main_view'] 			= 'v_project/v_project_sicer/project_sicer_sd_form';
			$data['form_action']		= site_url('c_project/project_sicer/add_process');
			//$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancel_url;
			
			$data['recordcountOwner'] 	= $this->m_project_sicer->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_sicer->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_sicer->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_sicer->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_sicer->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_sicer->viewProject()->result();
			
			$MenuCode 					= 'MN254';
			$data['viewDocPattern'] 	= $this->m_project_sicer->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_project/v_project_sicer/project_sicer_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallSI() // OK
	{
		$this->load->model('m_project/m_project_sicer/m_project_sicer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Select Site Instruction';
			$data['form_action']		= site_url('c_project/project_sicer/add_process');
			$data['PRJCODE'] 			= $PRJCODE;
			$data['secShowAll']			= site_url('c_project/project_sicer/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['recordcountAllSI'] 	= $this->m_project_sicer->count_all_num_rowsAllSI($PRJCODE);
			$data['viewAllSI'] 			= $this->m_project_sicer->viewAllSI($PRJCODE)->result();
					
			$this->load->view('v_project/v_project_sicer/project_selectsi', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_project/m_project_sicer/m_project_sicer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$FlagUSER 				= $this->session->userdata['FlagUSER'];
			//if($FlagUSER == 'APPSI')
				$SIC_STAT			= $this->input->post('SIC_STAT');
			//else
				//$SIC_STAT			= 1;
			
			if($SIC_STAT == 1)
				$SI_APPSTAT = 1;
			elseif($SIC_STAT == 2)
				$SI_APPSTAT = 2;
			elseif($SIC_STAT == 3)
				$SI_APPSTAT = 3;
			
			//setting MC Date
			$SIC_DATE				= date('Y-m-d',strtotime($this->input->post('SIC_DATE')));
			$SIC_CREATED			= date('Y-m-d H:i:s');
			$PATT_YEAR				= date('Y',strtotime($this->input->post('SIC_DATE')));
			
			$SIC_CODE 				= $this->input->post('SIC_CODE');
			$SIC_MANNO 				= $this->input->post('SIC_MANNO');
			$PRJCODE 				= $this->input->post('PRJCODE');
			
			$dataSIC 	= array('SIC_CODE' 		=> $SIC_CODE,
								'SIC_MANNO'		=> $SIC_MANNO,
								'SIC_STEP'		=> $this->input->post('SIC_STEP'),
								'PRJCODE'		=> $PRJCODE,
								'SIC_DATE'		=> $SIC_DATE,
								'SIC_CREATED'	=> $SIC_CREATED,
								'SIC_PROG'		=> $this->input->post('SIC_PROG'),
								'SIC_PROGVAL'	=> $this->input->post('SIC_PROGVAL'),
								'SIC_APPPROG'	=> $this->input->post('SIC_APPPROG'),
								'SIC_APPPROGVAL'=> $this->input->post('SIC_APPPROGVAL'),
								'SIC_TOTVAL'	=> $this->input->post('SIC_TOTVAL'),
								'SIC_NOTES'		=> $this->input->post('SIC_NOTES'),
								'SIC_EMPID'		=> $DefEmp_ID,
								'SIC_STAT'		=> $SIC_STAT,
								'PATT_YEAR'		=> $PATT_YEAR,
								'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));
							
			$this->m_project_sicer->add($dataSIC);
			
			// UPDATE TO TRANS-COUNT	
			$STAT_BEFORE			= $this->input->post('STAT_BEFORE');	
			$parameters = array(
					'DOC_CODE' 		=> $SIC_CODE,
					'PRJCODE' 		=> $PRJCODE,
					'TR_TYPE'		=> "SI_CER",
					'TBL_NAME' 		=> "tbl_sicertificate",
					'KEY_NAME'		=> "SIC_CODE",		// KEY FIELD IN THE TABLE 
					'STAT_NAME' 	=> "SIC_STAT",		// NAMA FIELD STATUS
					'APPSTATDOC' 	=> $SIC_STAT,
					'APPSTATDOCBEF'	=> $STAT_BEFORE,	// STAT SEBELUMNYA
					'FIELD_NM_CONF' => "SIC_PROGVAL",	// NAMA FIELD CONFIRM PADA TABEL
					'FIELD_NM_APP'	=> "SIC_APPPROGVAL",	// NAMA FIELD APPROVED PADA TABEL
					'FIELD_NM_DASH1'=> "TOT_SICER",		// NAMA FIELD PADA TABEL tbl_dash_data (Confirm)
					'FIELD_NM_DASH2'=> "TOT_SICERAPP"	// NAMA FIELD PADA TABEL tbl_dash_data (Approve)
				);
			$this->m_project_sicer->updateDashData($parameters);
				
			foreach($_POST['data'] as $d)
			{
				$d['SIC_CODE']	= $SIC_CODE;
				$d['SIC_MANNO']	= $SIC_MANNO;
				
				$SI_CODE		= $d['SI_CODE'];
				$ISCHK			= $d['ISCHK'];
				if($ISCHK == 1)
				{
					$this->db->insert('tbl_sicertificatedet',$d);
					$this->m_project_sicer->updateSI($SI_CODE, $SI_APPSTAT);
				}
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			$back	= site_url('c_project/project_sicer/get_last_ten_projsicer/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($back);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_project/m_project_sicer/m_project_sicer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$SIC_CODE		= $_GET['id'];
			$SIC_CODE		= $this->url_encryption_helper->decode_url($SIC_CODE);
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$getSICDET 					= $this->m_project_sicer->get_SIC_by_number($SIC_CODE)->row();
			$PRJCODE					= $getSICDET->PRJCODE;
			
			$cancel_url 				= site_url('c_project/project_sicer/get_last_ten_projsicer/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['PRJCODE'] 			= $PRJCODE;	
			$data['proj_Code'] 			= $PRJCODE;	
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title']			= 'Add SI Certificate';
			$data['h3_title']			= 'SI Certificate';
			$data['main_view'] 			= 'v_project/v_project_sicer/project_sicer_form';
			$data['form_action']		= site_url('c_project/project_sicer/update_process');
			//$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancel_url;
			
			$data['recordcountOwner'] 	= $this->m_project_sicer->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_sicer->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_sicer->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_sicer->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_sicer->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_sicer->viewProject()->result();
			
			$data['default']['SIC_CODE'] 		= $getSICDET->SIC_CODE;
			$data['default']['SIC_MANNO'] 		= $getSICDET->SIC_MANNO;
			$data['default']['SIC_STEP'] 		= $getSICDET->SIC_STEP;
			$data['default']['PRJCODE'] 		= $getSICDET->PRJCODE;
			$data['default']['SIC_OWNER'] 		= $getSICDET->SIC_OWNER;
			$data['default']['SIC_DATE'] 		= $getSICDET->SIC_DATE;
			$data['default']['SIC_APPDATE'] 	= $getSICDET->SIC_APPDATE; 
			$data['default']['SIC_CREATED'] 	= $getSICDET->SIC_CREATED;
			$data['default']['SIC_PROG'] 		= $getSICDET->SIC_PROG; 
			$data['default']['SIC_PROGVAL'] 	= $getSICDET->SIC_PROGVAL;
			$data['default']['SIC_APPPROG'] 	= $getSICDET->SIC_APPPROG; 
			$data['default']['SIC_APPPROGVAL'] 	= $getSICDET->SIC_APPPROGVAL;
			$data['default']['SIC_TOTVAL'] 		= $getSICDET->SIC_TOTVAL;
			$data['default']['SIC_NOTES'] 		= $getSICDET->SIC_NOTES;
			$data['default']['SIC_EMPID'] 		= $getSICDET->SIC_EMPID;
			$data['default']['SIC_STAT'] 		= $getSICDET->SIC_STAT;
			$data['default']['PATT_YEAR'] 		= $getSICDET->PATT_YEAR;
			$data['default']['PATT_MONTH'] 		= $getSICDET->PATT_MONTH;
			$data['default']['PATT_DATE'] 		= $getSICDET->PATT_DATE;
			$data['default']['PATT_NUMBER'] 	= $getSICDET->PATT_NUMBER;
			$data['PRJCODE'] 					= $getSICDET->PRJCODE;
			
			$this->load->view('v_project/v_project_sicer/project_sicer_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_project/m_project_sicer/m_project_sicer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$FlagUSER 				= $this->session->userdata['FlagUSER'];
			//if($FlagUSER == 'APPSI')
				$SIC_STAT			= $this->input->post('SIC_STAT');
			//else
				//$SIC_STAT			= 1;
			
			if($SIC_STAT == 1)
				$SI_APPSTAT = 1;
			elseif($SIC_STAT == 2)
				$SI_APPSTAT = 2;
			elseif($SIC_STAT == 3)
				$SI_APPSTAT = 3;
				
			//setting MC Date
			$SIC_DATE				= date('Y-m-d',strtotime($this->input->post('SIC_DATE')));
			$SIC_CREATED			= date('Y-m-d H:i:s');
			$PATT_YEAR				= date('Y',strtotime($this->input->post('SIC_DATE')));
			
			$SIC_CODE 				= $this->input->post('SIC_CODE');
			$SIC_MANNO 				= $this->input->post('SIC_MANNO');
			$PRJCODE 				= $this->input->post('PRJCODE');
			$SIC_APPPROG			= $this->input->post('SIC_APPPROG');
			//return false;
			
			$dataSIC 	= array('SIC_CODE' 		=> $SIC_CODE,
								'SIC_MANNO'		=> $SIC_MANNO,
								'SIC_STEP'		=> $this->input->post('SIC_STEP'),
								'PRJCODE'		=> $PRJCODE,
								'SIC_DATE'		=> $SIC_DATE,
								'SIC_CREATED'	=> $SIC_CREATED,
								'SIC_PROG'		=> $this->input->post('SIC_PROG'),
								'SIC_PROGVAL'	=> $this->input->post('SIC_PROGVAL'),
								'SIC_APPPROG'	=> $this->input->post('SIC_APPPROG'),
								'SIC_APPPROGVAL'=> $this->input->post('SIC_APPPROGVAL'),
								'SIC_TOTVAL'	=> $this->input->post('SIC_TOTVAL'),
								'SIC_NOTES'		=> $this->input->post('SIC_NOTES'),
								'SIC_EMPID'		=> $DefEmp_ID,
								'SIC_STAT'		=> $SIC_STAT,
								'PATT_YEAR'		=> $PATT_YEAR,
								'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));
							
			$this->m_project_sicer->update($SIC_CODE, $dataSIC);
			
			$this->m_project_sicer->deleteDetail($SIC_CODE);
			
			foreach($_POST['data'] as $d)
			{
				$d['SIC_CODE']	= $SIC_CODE;
				$d['SIC_MANNO']	= $SIC_MANNO;
				
				$SI_CODE		= $d['SI_CODE'];
				$ISCHK			= $d['ISCHK'];			
				if($ISCHK == 1)
				{
					$this->db->insert('tbl_sicertificatedet',$d);
					$this->m_project_sicer->updateSI($SI_CODE, $SI_APPSTAT);
				}
			}
			
			// UPDATE TO TRANS-COUNT	
			$STAT_BEFORE			= $this->input->post('STAT_BEFORE');	
			$parameters = array(
					'DOC_CODE' 		=> $SIC_CODE,
					'PRJCODE' 		=> $PRJCODE,
					'TR_TYPE'		=> "SI_CER",
					'TBL_NAME' 		=> "tbl_sicertificate",
					'KEY_NAME'		=> "SIC_CODE",		// KEY FIELD IN THE TABLE 
					'STAT_NAME' 	=> "SIC_STAT",		// NAMA FIELD STATUS
					'APPSTATDOC' 	=> $SIC_STAT,
					'APPSTATDOCBEF'	=> $STAT_BEFORE,	// STAT SEBELUMNYA
					'FIELD_NM_CONF' => "SIC_PROGVAL",	// NAMA FIELD CONFIRM PADA TABEL
					'FIELD_NM_APP'	=> "SIC_APPPROGVAL",	// NAMA FIELD APPROVED PADA TABEL
					'FIELD_NM_DASH1'=> "TOT_SICER",		// NAMA FIELD PADA TABEL tbl_dash_data (Confirm)
					'FIELD_NM_DASH2'=> "TOT_SICERAPP"	// NAMA FIELD PADA TABEL tbl_dash_data (Approve)
				);
			$this->m_project_sicer->updateDashData($parameters);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			$back	= site_url('c_project/project_sicer/get_last_ten_projsicer/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($back);
		}
		else
		{
			redirect('Auth');
		}
	}
}