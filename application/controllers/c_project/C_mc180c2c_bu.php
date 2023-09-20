<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 16 September 2018
 * File Name	= C_mc180c2c_bu.php
 * Notes		= -
*/

class C_mc180c2c_bu extends CI_Controller
{
 	// Start : Index tiap halaman
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_mc180c2c_bu/prj180c2cls/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c2cls() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Monthly Certificate - BUMN";
			}
			else
			{
				$data["h1_title"] 	= "Monthly Certificate - BUMN";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_mc180c2c_bu/gall180c2cmc/?id=";
			
			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function gall180c2cmc($offset=0) // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODEX			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODEX);
			
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar MC - BUMN";
				$data['h3_title']	= "MC Proyek - BUMN";
			}
			else
			{
				$data["h2_title"] 	= "MC Project List - BUMN";
				$data['h3_title']	= "MC Project - BUMN";
			}
			
			$data['main_view'] 		= 'v_project/v_project_mc/project_mcbu';
			$data['link'] 			= array('link_back' => anchor('c_project/c_mc180c2c_bu/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_project/c_mc180c2c_bu/');
			$data['PRJCODE'] 		= $PRJCODE;
			
			$selSearchproj_Code 	= $PRJCODE;
			$selSearchCat			= 'isMC';
			$GETFROM				= 'BUMN';
			$num_rows 				= $this->m_project_mc->count_all_num_rowsProjMC($PRJCODE, $GETFROM);
			$data['CATTYPE'] 		= 'isMC';
			$data["MenuCode"] 		= 'MN362';
			$data["recordcount"] 	= $num_rows;
			$data['viewmc'] 		= $this->m_project_mc->get_last_ten_projmc($PRJCODE, $GETFROM)->result();
			
			$myProjectSess = array(
					'myProjSession' => $selSearchproj_Code);
			$this->session->set_userdata('dtProjSess', $myProjectSess);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN362';
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
			
			$this->load->view('v_project/v_project_mc/project_mcbu', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a180c2cddmc() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODEX			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODEX);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['PRJCODE'] 			= $PRJCODE;
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Tambah MC - BUMN";
				$data['h3_title']	= "MC Proyek - BUMN";
			}
			else
			{
				$data["h2_title"] 	= "Add MC - BUMN";
				$data['h3_title']	= "MC Project - BUMN";
			}
			
			$data['main_view'] 			= 'v_project/v_project_mc/project_mcbu_form';
			$data['form_action']		= site_url('c_project/c_mc180c2c_bu/add_process');
			$cancel_url					= site_url('c_project/c_mc180c2c_bu/gall180c2cmc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancel_url;
			$data['countPRJ']			= $this->m_project_mc->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_mc->viewProject()->result();
			
			$MenuCode 					= 'MN362';
			$data['MenuCode']			= 'MN362';
			$data['viewDocPattern'] 	= $this->m_project_mc->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN362';
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
			
			$this->load->view('v_project/v_project_mc/project_mcbu_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];	
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
		
		$sqlApp 			= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			//setting MC Date
			$MC_CHECKD		= date('Y-m-d');
			$MC_CREATED		= date('Y-m-d H:i:s');			
			
			//setting MC Date
			$MC_DATE		= date('Y-m-d',strtotime($this->input->post('MC_DATE')));
			$MC_ENDDATE		= date('Y-m-d',strtotime($this->input->post('MC_ENDDATE')));
			//$MC_CHECKD		= date('Y-m-d',strtotime($this->input->post('MC_CHECKD')));
			//$MC_CREATED		= date('Y-m-d',strtotime($this->input->post('MC_CREATED')));
			$PATT_YEAR		= date('Y',strtotime($this->input->post('MC_DATE')));
			
			$MC_CODE 		= $this->input->post('MC_CODE');
			$MC_MANNO 		= $this->input->post('MC_MANNO');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$MC_OWNER 		= $this->input->post('MC_OWNER');
			
			$MC_STAT		= $this->input->post('MC_STAT');
			$dataMCH 		= array('MC_CODE' 		=> $MC_CODE,
									'MC_MANNO'		=> $MC_MANNO,
									'MC_STEP'		=> $this->input->post('MC_STEP'),
									'PRJCODE'		=> $PRJCODE,
									'MC_OWNER'		=> $MC_OWNER,
									'MC_DATE'		=> $MC_DATE,
									'MC_ENDDATE'	=> $MC_ENDDATE,
									'MC_CHECKD'		=> $MC_CHECKD,
									'MC_CREATED'	=> $MC_CREATED,
									'GETFROM'		=> 'BUMN',
									'MC_RETVAL'		=> $this->input->post('MC_RETVAL'),
									'MC_PROG'		=> $this->input->post('MC_PROG'),
									'MC_PROGVAL'	=> $this->input->post('MC_PROGVAL'),
									'MC_PROGAPP'	=> $this->input->post('MC_PROGAPP'),
									'MC_PROGAPPVAL'	=> $this->input->post('MC_PROGAPPVAL'),
									'MC_VALADD'		=> $this->input->post('MC_VALADD'),
									//'MC_MATVAL'	=> $this->input->post('MC_MATVAL'),
									'MC_DPPER'		=> $this->input->post('MC_DPPER'),
									'MC_DPVAL'		=> $this->input->post('MC_DPVAL'),
									'MC_DPBACK'		=> $this->input->post('MC_DPBACK'),
									'MC_RETCUT'		=> $this->input->post('MC_RETCUT'),
									'MC_VALBEF'		=> $this->input->post('MC_VALBEF'),
									'MC_AKUMNEXT'	=> $this->input->post('MC_AKUMNEXT'),
									'MC_TOTVAL'		=> $this->input->post('MC_PAYMENT'),
									'MC_TOTVAL_PPn'	=> $this->input->post('MC_TOTVAL_PPn'),
									'MC_TOTVAL_PPh'	=> $this->input->post('MC_TOTVAL_PPh'),
									'MC_NOTES'		=> $this->input->post('MC_NOTES'),
									'MC_EMPID'		=> $DefEmp_ID,
									'MC_STAT'		=> $MC_STAT,
									'PATT_YEAR'		=> $PATT_YEAR,
									'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));
			$this->m_project_mc->add($dataMCH, $PRJCODE);
			
			// COUNT DATA
				//$this->m_project_mc->count_all_mcnew($PRJCODE);
				//$this->m_project_mc->count_all_mccon($PRJCODE);
				//$this->m_project_mc->count_all_mcapp($PRJCODE);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('MC_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $MC_CODE,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "MC",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_mcheader",	// TABLE NAME
										'KEY_NAME'		=> "MC_CODE",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "MC_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $MC_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_MC",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_MC_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_MC_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_MC_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_MC_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_MC_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_MC_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $MC_CODE;
				$MenuCode 		= 'MN362';
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
			
			$url			= site_url('c_project/c_mc180c2c_bu/gall180c2cmc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u180c2cpmc() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$MC_CODEX			= $_GET['id'];
			$MC_CODE			= $this->url_encryption_helper->decode_url($MC_CODEX);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$getMCDET 					= $this->m_project_mc->get_MC_by_number($MC_CODE)->row();
			$PRJCODE					= $getMCDET->PRJCODE;
			
			$data['PRJCODE'] 			= $PRJCODE;
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Edit MC - BUMN";
				$data['h3_title']	= "MC Proyek - BUMN";
			}
			else
			{
				$data["h2_title"] 	= "Edit MC - BUMN";
				$data['h3_title']	= "MC Project - BUMN";
			}
			
			$data['MenuCode']			= 'MN362';
			$data['main_view'] 			= 'v_project/v_project_mc/project_mcbu_form';
			$data['form_action']		= site_url('c_project/c_mc180c2c_bu/update_process');
			$cancel_url					= site_url('c_project/c_mc180c2c_bu/gall180c2cmc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancel_url;
			
			$data['countPRJ']			= $this->m_project_mc->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_mc->viewProject()->result();
			
			$data['default']['MC_CODE'] 		= $getMCDET->MC_CODE;
			$data['default']['MC_MANNO'] 		= $getMCDET->MC_MANNO;
			$data['default']['GETFROM'] 		= $getMCDET->GETFROM;
			$data['default']['MC_STEP'] 		= $getMCDET->MC_STEP;
			$data['default']['PRJCODE'] 		= $getMCDET->PRJCODE;
			$data['default']['MC_OWNER'] 		= $getMCDET->MC_OWNER;
			$data['default']['MC_DATE'] 		= $getMCDET->MC_DATE;
			$data['default']['MC_ENDDATE'] 		= $getMCDET->MC_ENDDATE; 
			$data['default']['MC_CHECKD'] 		= $getMCDET->MC_CHECKD; 
			$data['default']['MC_CREATED'] 		= $getMCDET->MC_CREATED;
			$data['default']['MC_RETVAL'] 		= $getMCDET->MC_RETVAL;
			$data['default']['MC_RETCUT'] 		= $getMCDET->MC_RETCUT;
			$data['default']['MC_DPPER'] 		= $getMCDET->MC_DPPER;
			$data['default']['MC_DPVAL'] 		= $getMCDET->MC_DPVAL;
			$data['default']['MC_DPBACK'] 		= $getMCDET->MC_DPBACK;
			$data['default']['MC_PROG'] 		= $getMCDET->MC_PROG;
			$data['default']['MC_PROGVAL'] 		= $getMCDET->MC_PROGVAL;
			$data['default']['MC_PROGAPP'] 		= $getMCDET->MC_PROGAPP;
			$data['default']['MC_PROGAPPVAL']	= $getMCDET->MC_PROGAPPVAL;
			$data['default']['MC_VALADD'] 		= $getMCDET->MC_VALADD;
			$data['default']['MC_MATVAL'] 		= $getMCDET->MC_MATVAL;
			$data['default']['MC_VALBEF']		= $getMCDET->MC_VALBEF;
			$data['default']['MC_AKUMNEXT'] 	= $getMCDET->MC_AKUMNEXT;
			$data['default']['MC_TOTVAL'] 		= $getMCDET->MC_TOTVAL;
			$data['default']['MC_TOTVAL_PPn'] 	= $getMCDET->MC_TOTVAL_PPn;
			$data['default']['MC_TOTVAL_PPh'] 	= $getMCDET->MC_TOTVAL_PPh;
			$data['default']['MC_NOTES'] 		= $getMCDET->MC_NOTES;
			$data['default']['MC_EMPID'] 		= $getMCDET->MC_EMPID;
			$data['default']['MC_STAT'] 		= $getMCDET->MC_STAT;
			$data['default']['PATT_YEAR'] 		= $getMCDET->PATT_YEAR;
			$data['default']['PATT_MONTH'] 		= $getMCDET->PATT_MONTH;
			$data['default']['PATT_DATE'] 		= $getMCDET->PATT_DATE;
			$data['default']['PATT_NUMBER'] 	= $getMCDET->PATT_NUMBER;
			$data['PRJCODE'] 					= $getMCDET->PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getMCDET->MC_CODE;
				$MenuCode 		= 'MN362';
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
			
			$this->load->view('v_project/v_project_mc/project_mcbu_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{ 
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];	
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
		
		$sqlApp 			= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			date_default_timezone_set("Asia/Jakarta");
			
			//setting MC Date
			$MC_CHECKD		= date('Y-m-d');
			$MC_CREATED		= date('Y-m-d H:i:s');
			
			//setting MC Date
			$MC_DATE		= date('Y-m-d',strtotime($this->input->post('MC_DATE')));
			$MC_ENDDATE		= date('Y-m-d',strtotime($this->input->post('MC_ENDDATE')));
			//$MC_CHECKD	= date('Y-m-d',strtotime($this->input->post('MC_CHECKD')));
			//$MC_CREATED	= date('Y-m-d',strtotime($this->input->post('MC_CREATED')));


			$PATT_YEAR		= date('Y',strtotime($this->input->post('MC_DATE')));
			
			$MC_CODE 		= $this->input->post('MC_CODE');
			$MC_MANNO 		= $this->input->post('MC_MANNO');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$MC_OWNER 		= $this->input->post('MC_OWNER');
			
			$AH_CODE		= $MC_CODE;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= $this->input->post('MC_NOTES');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			$MC_STAT		= $this->input->post('MC_STAT');
			
			$dataMCH = array('MC_CODE' 		=> $MC_CODE,
							'MC_MANNO'		=> $MC_MANNO,
							'MC_STEP'		=> $this->input->post('MC_STEP'),
							'PRJCODE'		=> $PRJCODE,
							'MC_OWNER'		=> $MC_OWNER,
							'MC_DATE'		=> $MC_DATE,
							'MC_ENDDATE'	=> $MC_ENDDATE,
							'MC_CHECKD'		=> $MC_CHECKD,
							'MC_CREATED'	=> $MC_CREATED,
							'MC_RETVAL'		=> $this->input->post('MC_RETVAL'),
							'MC_PROG'		=> $this->input->post('MC_PROG'),
							'MC_PROGVAL'	=> $this->input->post('MC_PROGVAL'),
							'MC_PROGAPP'	=> $this->input->post('MC_PROGAPP'),
							'MC_PROGAPPVAL'	=> $this->input->post('MC_PROGAPPVAL'),
							'MC_VALADD'		=> $this->input->post('MC_VALADD'),
							//'MC_MATVAL'	=> $this->input->post('MC_MATVAL'),	
							'MC_DPPER'		=> $this->input->post('MC_DPPER'),
							'MC_DPVAL'		=> $this->input->post('MC_DPVAL'),
							'MC_DPBACK'		=> $this->input->post('MC_DPBACK'),
							'MC_RETCUT'		=> $this->input->post('MC_RETCUT'),				
							'MC_VALBEF'		=> $this->input->post('MC_VALBEF'),					
							'MC_AKUMNEXT'	=> $this->input->post('MC_AKUMNEXT'),
							'MC_TOTVAL'		=> $this->input->post('MC_PAYMENT'),
							'MC_TOTVAL_PPn'	=> $this->input->post('MC_TOTVAL_PPn'),
							'MC_TOTVAL_PPh'	=> $this->input->post('MC_TOTVAL_PPh'),
							'MC_NOTES'		=> $this->input->post('MC_NOTES'),
							'MC_EMPIDAPP'	=> $DefEmp_ID,
							'MC_STAT'		=> $this->input->post('MC_STAT'),
							'PATT_YEAR'		=> $PATT_YEAR,
							'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));						
			$this->m_project_mc->update($MC_CODE, $dataMCH, $PRJCODE);
			
			if($MC_STAT == 3)
			{
				$dataMCH = array('MC_STAT'		=> 7);						
				$this->m_project_mc->update($MC_CODE, $dataMCH, $PRJCODE);
			
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$AH_CODE		= $MC_CODE;
					$AH_APPLEV		= $this->input->post('APP_LEVEL');
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= date('Y-m-d H:i:s');
					$AH_NOTES		= $this->input->post('MC_NOTES');
					$PR_MEMO		= "";
					$AH_ISLAST		= $this->input->post('IS_LAST');
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
			}
			
			// SAVE TO PROFITLOSS
			$MC_STAT	= $this->input->post('MC_STAT');
			
			if($MC_STAT == 9)
			{				
				// START : UPDATE L/R
					$this->load->model('m_updash/m_updash', '', TRUE);
					$PERIODED	= $MC_DATE;
					$PERIODM	= date('m', strtotime($PERIODED));
					$PERIODY	= date('Y', strtotime($PERIODED));
					$MC_PROG		= $this->input->post('MC_PROG');
					$MC_PROGAPP		= $this->input->post('MC_PROGAPP');
					$MC_AKUMNEXT	= $this->input->post('MC_AKUMNEXT');
					$updLR			= "UPDATE tbl_profitloss SET PROGMC = PROGMC - $MC_PROG, PROGMC_PLAN = PROGMC_PLAN - $MC_PROG,
											 PROGMC_REAL = PROGMC_REAL - $MC_PROGAPP, PROGMC_REALA = PROGMC_REALA - $MC_AKUMNEXT
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
											AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				// END : UPDATE L/R
				
				// START : ADD CONCLUSION
					$MC_PAYMENT1	= $this->input->post('MC_PAYMENT');
					$MC_TOT_PPn		= $this->input->post('MC_TOTVAL_PPn');
					$MC_TOT_PPh		= $this->input->post('MC_TOTVAL_PPh');
					$MC_GTOT		= $MC_PAYMENT1 + $MC_TOT_PPn - $MC_TOT_PPh;
					
					$MC_PROG		= $this->input->post('MC_PROG');
					$MC_PROGVAL		= $this->input->post('MC_PROGVAL');									
					$MC_PROGAPP		= $this->input->post('MC_PROGAPP');
					$MC_PROGAPPVAL	= $this->input->post('MC_PROGAPPVAL');
					$MC_VALADD		= $this->input->post('MC_VALADD');
					$MC_RETVAL		= $this->input->post('MC_RETCUT');
					$MC_DPAMOUNT	= $this->input->post('MC_DPVAL');
					$MC_DPCUTAM		= $this->input->post('MC_DPBACK');
					$MC_TOTAMOUNT	= $this->input->post('MC_AKUMNEXT');
					$MC_TOTBEF		= $this->input->post('MC_VALBEF');
					$MC_TOT_NET		= $this->input->post('MC_PAYMENT');
					$MCP_BEFVAL		= $this->input->post('MC_VALBEF');
					$MC_TOT_PPn		= $MC_TOT_PPn;
					$MC_TOT_PPh		= $MC_TOT_PPh;
					$MC_GTOT		= $MC_GTOT;
					
					$updMCCON		= "UPDATE tbl_mc_conc SET MC_PROG = MC_PROG - $MC_PROG, MC_PROGVAL = MC_PROGVAL - $MC_PROGVAL,
											 MC_PROGAPPVAL = MC_PROGAPPVAL - $MC_PROGAPPVAL, MC_VALADD = MC_VALADD - $MC_VALADD,
											 MC_RETVAL = MC_RETVAL - $MC_RETVAL, MC_DPAMOUNT = MC_DPAMOUNT - $MC_DPAMOUNT,
											 MC_DPCUTAM = MC_DPCUTAM - $MC_DPCUTAM, MC_TOTAMOUNT = MC_TOTAMOUNT - $MC_TOTAMOUNT,
											 MC_TOTBEF = MC_TOTBEF - $MC_TOTBEF, MC_TOT_NET = MC_TOT_NET - $MC_TOT_NET,
											 MC_TOT_PPn = MC_TOT_PPn - $MC_TOT_PPn, MC_TOT_PPh = MC_TOT_PPh - $MC_TOT_PPh,
											 MC_GTOT = MC_GTOT - $MC_GTOT
										WHERE PRJCODE = '$PRJCODE' AND MCC_CODE = '$MC_CODE'";
					$this->db->query($updMCCON);
				// END : ADD CONCLUSION
			
				// Check untuk bulan yang sama
					$MC_DATEY	= date('Y',strtotime($MC_DATE));
					$MC_DATEM	= (int)date('m',strtotime($MC_DATE));
				// BUAT TANGGAL AKHIR BULAN PER SI
					$LASTDATE	= date('Y-m-t', strtotime($MC_DATE));
				
				$dataMCH = array('MC_STAT'	=> $this->input->post('MC_STAT'));						
				$this->m_project_mc->update($MC_CODE, $dataMCH, $PRJCODE);
				
				$updMCP		= "UPDATE tbl_mc_plan SET MCP_PROG = MCP_PROG - $MC_PROG, MCP_PROGVAL = MCP_PROGVAL - $MC_PROGVAL,
										 MCP_RETCUT = MCP_RETCUT - $MC_RETVAL, MCP_NEXTVAL = MCP_NEXTVAL - $MC_AKUMNEXT,
										 MCP_BEFVAL = MCP_BEFVAL - $MCP_BEFVAL, MCP_PROGAPP = MCP_PROGAPP - $MC_PROGAPP,
										 MCP_PROGAPPVAL = MCP_PROGAPPVAL - $MC_PROGAPPVAL, MCP_STATUS = $MC_STAT
									WHERE MCP_PRJCODE = '$PRJCODE' AND MCP_CODE = '$MC_CODE'";
				$this->db->query($updMCP);
			}
			else
			{
				if($AH_ISLAST == 1)
				{
					if($MC_STAT == 3)
					{
						// START : SETTING L/R
							$this->load->model('m_updash/m_updash', '', TRUE);
							$PERIODED	= $MC_DATE;
							$PERIODM	= date('m', strtotime($PERIODED));
							$PERIODY	= date('Y', strtotime($PERIODED));
							$getLR		= "tbl_profitloss WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
											AND YEAR(PERIODE) = '$PERIODY'";
							$resLR		= $this->db->count_all($getLR);
							if($resLR	== 0)
							{
								$this->m_updash->createLR($PRJCODE, $PERIODED);
							}
						// END : SETTING L/R
						
						// START : UPDATE L/R
							$MC_PROG		= $this->input->post('MC_PROG');
							$MC_PROGAPP		= $this->input->post('MC_PROGAPP');
							$MC_AKUMNEXT	= $this->input->post('MC_AKUMNEXT');
							$updLR			= "UPDATE tbl_profitloss SET PROGMC = PROGMC + $MC_PROG, PROGMC_PLAN = PROGMC_PLAN + $MC_PROG,
													 PROGMC_REAL = PROGMC_REAL + $MC_PROGAPP, PROGMC_REALA = PROGMC_REALA + $MC_AKUMNEXT
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						// END : UPDATE L/R
						
						// START : ADD CONCLUSION
							$MC_PAYMENT1	= $this->input->post('MC_PAYMENT');
							$MC_TOT_PPn		= $this->input->post('MC_TOTVAL_PPn');
							$MC_TOT_PPh		= $this->input->post('MC_TOTVAL_PPh');
							$MC_GTOT		= $MC_PAYMENT1 + $MC_TOT_PPn - $MC_TOT_PPh;
							$dataMCH 		= array('MCC_CODE' 	=> $MC_CODE,
												'MCC_STEP'		=> $this->input->post('MC_STEP'),
												'PRJCODE'		=> $PRJCODE,
												'MC_DATE'		=> $MC_DATE,
												'MC_PROG'		=> $this->input->post('MC_PROG'),
												'MC_PROGVAL'	=> $this->input->post('MC_PROGVAL'),									
												'MC_PROGAPP'	=> $this->input->post('MC_PROGAPP'),
												'MC_PROGAPPVAL'	=> $this->input->post('MC_PROGAPPVAL'),
												'MC_VALADD'		=> $this->input->post('MC_VALADD'),
												'MC_RETVAL'		=> $this->input->post('MC_RETCUT'),
												'MC_DPAMOUNT'	=> $this->input->post('MC_DPVAL'),
												'MC_DPCUTAM'	=> $this->input->post('MC_DPBACK'),
												'MC_TOTAMOUNT'	=> $this->input->post('MC_AKUMNEXT'),
												'MC_TOTBEF'		=> $this->input->post('MC_VALBEF'),
												'MC_TOT_NET'	=> $this->input->post('MC_PAYMENT'),
												'MC_TOT_PPn'	=> $MC_TOT_PPn,
												'MC_TOT_PPh'	=> $MC_TOT_PPh,
												'MC_GTOT'		=> $MC_GTOT);
							$this->m_project_mc->updateConc($MC_CODE, $dataMCH, $PRJCODE);
						// END : ADD CONCLUSION
					
						// Check untuk bulan yang sama
							$MC_DATEY	= date('Y',strtotime($MC_DATE));
							$MC_DATEM	= (int)date('m',strtotime($MC_DATE));
						// BUAT TANGGAL AKHIR BULAN PER SI
							$LASTDATE	= date('Y-m-t', strtotime($MC_DATE));
						
						$dataMCH = array('MC_STAT'	=> $this->input->post('MC_STAT'));						
						$this->m_project_mc->update($MC_CODE, $dataMCH, $PRJCODE);
				
						// CREATE MC MONITORING
							$dataMCM 	= array('MCP_CODE' 		=> $MC_CODE,
												'MCP_PRJCODE'	=> $PRJCODE,
												'MCP_DATE'		=> $MC_DATE,
												'MCP_PROG'		=> $this->input->post('MC_PROG'),
												'MCP_PROGVAL'	=> $this->input->post('MC_PROGVAL'),
												'MCP_RETCUT'	=> $this->input->post('MC_RETCUT'),
												'MCP_NEXTVAL'	=> $this->input->post('MC_AKUMNEXT'),
												'MCP_BEFVAL'	=> $this->input->post('MC_VALBEF'),
												'MCP_PROGAPP'	=> $this->input->post('MC_PROGAPP'),
												'MCP_PROGAPPVAL'=> $this->input->post('MC_PROGAPPVAL'),
												'MCP_STATUS'	=> $MC_STAT);
							$this->m_project_mc->addMCM($dataMCM, $PRJCODE);
					}
				}
			}
			
			// START : CLEAR HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				if($MC_STAT == 4)
				{
					$cllPar = array('AH_CODE' 		=> $MC_CODE,
									'AH_APPROVER'	=> $DefEmp_ID);
					$this->m_updash->clearHist($cllPar);
				}
			// END : CLEAR HISTORY
			
			// COUNT DATA
				//$this->m_project_mc->count_all_mcnew($PRJCODE);
				//$this->m_project_mc->count_all_mccon($PRJCODE);
				//$this->m_project_mc->count_all_mcapp($PRJCODE);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $MC_CODE,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "MC",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_mcheader",	// TABLE NAME
										'KEY_NAME'		=> "MC_CODE",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "MC_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $MC_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_MC",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_MC_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_MC_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_MC_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_MC_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_MC_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_MC_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $MC_CODE;
				$MenuCode 		= 'MN362';
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
			
			$url			= site_url('c_project/c_mc180c2c_bu/gall180c2cmc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function indexInbox()  // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$secIndex 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_mc180c2c_bu'),'inbox');
			redirect($secIndex);
		}
		else
		{
			redirect('__l1y');
		}
    }	
	
	// --------------------- SI ---------------------  //
	
 	public function prj180c2clsSI() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_mc180c2c_bu/prjls180c2cSI/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjls180c2cSI() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data["h1_title"] 	= "Site Instruction";
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_mc180c2c_bu/gAlL180c2cSI/?id=";
			
			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function gAlL180c2cSI() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODEX			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODEX);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'SI Project List';
			$data['h3_title'] 		= 'SI Project';
			$cancel_url				= site_url('c_project/c_mc180c2c_bu/prj180c2clsSI/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 		= $cancel_url;
			$data['PRJCODE'] 		= $PRJCODE;
			$data["selSearchCat"] 	= 'isSI';			
			$num_rows 				= $this->m_project_mc->count_all_num_rowsProjSI($PRJCODE);
			
			$data["recordcount"] 	= $num_rows;			
			$data['CATTYPE'] 		= 'isSI';
			$data['MenuCode']		= 'MN259';
			
			$data['viewprojinvoice'] = $this->m_project_mc->get_last_ten_projsi($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN259';
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
			
			$this->load->view('v_project/v_project_mc/project_si', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a180c2cddsi() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$cancel_url					= site_url('c_project/c_mc180c2c_bu/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['PRJCODE'] 			= $PRJCODE;
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add Site Instruction';
			$data['h3_title']			= 'SI Project';
			$data['form_action']		= site_url('c_project/c_mc180c2c_bu/addSI_process');
			$data['backURL'] 			= $cancel_url;
			
			$data['countPRJ']			= $this->m_project_mc->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_mc->viewProject()->result();
			
			$MenuCode 					= 'MN259';
			$data['MenuCode']			= 'MN259';
			$data['viewDocPattern'] 	= $this->m_project_mc->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN259';
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
			
			$this->load->view('v_project/v_project_mc/project_si_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addSI_process() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		$this->db->trans_begin();
		
		date_default_timezone_set("Asia/Jakarta");
		
		$SI_CODE 		= $this->input->post('SI_CODE');
		$SI_MANNO 		= $this->input->post('SI_MANNO');
		$PRJCODE 		= $this->input->post('PRJCODE');
		$SI_AMAND		= $this->input->post('SI_AMAND');		
		$SI_DATE		= date('Y-m-d',strtotime($this->input->post('SI_DATE')));
		$SI_ENDDATE		= date('Y-m-d',strtotime($this->input->post('SI_ENDDATE')));
		$SI_STAT 		= $this->input->post('SI_STAT');
		
		if($SI_STAT == 3)
		{
			$SI_APPDATE		= date('Y-m-d H:i:s');
			$SI_EMPIDAPP	= $DefEmp_ID;
		}
		else
		{
			$SI_APPDATE		= date('Y-m-d H:i:s');
			$SI_EMPIDAPP	= '';
		}
		
		$SI_CREATED		= date('Y-m-d H:i:s');
		$PATT_YEAR		= date('Y',strtotime($this->input->post('SI_DATE')));
		
		$dataSIH 		= array('SI_CODE' 		=> $SI_CODE,
								'SI_MANNO'		=> $SI_MANNO,
								'SI_INCCON'		=> $this->input->post('SI_INCCON'),
								'SI_STEP'		=> $this->input->post('SI_STEP'),
								'PRJCODE'		=> $PRJCODE,
								'SI_DATE'		=> $SI_DATE,
								'SI_ENDDATE'	=> $SI_ENDDATE,
								'SI_CREATED'	=> $SI_CREATED,
								'SI_DESC'		=> $this->input->post('SI_DESC'),
								'SI_DPPER'		=> $this->input->post('SI_DPPER'),
								'SI_DPVAL'		=> $this->input->post('SI_DPVAL'),
								'SI_VALUE'		=> $this->input->post('SI_VALUE'),		// SI PENGAJUAN
								'SI_APPVAL'		=> $this->input->post('SI_APPVAL'),		// SI DISETUJUI
								'SI_PROPPERC'	=> $this->input->post('SI_PROPPERC'),	// SI DISETUJUI (%)
								'SI_PROPVAL'	=> $this->input->post('SI_PROPVAL'),	// REAL COST PEK. +/-
								'SI_REALVAL'	=> $this->input->post('SI_REALVAL'),	// REAL COST PEK. +/-
								'SI_AMAND'		=> $this->input->post('SI_AMAND'),
								'SI_AMANDNO'	=> $this->input->post('SI_AMANDNO'),
								'SI_AMANDVAL'	=> $this->input->post('SI_AMANDVAL'),
								'SI_NOTES'		=> $this->input->post('SI_NOTES'),
								'SI_EMPID'		=> $DefEmp_ID,
								'SI_STAT'		=> $SI_STAT,
								'SI_APPDATE'	=> $SI_APPDATE,
								'SI_EMPIDAPP'	=> $SI_EMPIDAPP,
								'PATT_YEAR'		=> $PATT_YEAR,
								'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));						
		$this->m_project_mc->addSI($dataSIH, $PRJCODE);
		
		/*if($SI_AMAND == 1)
		{
			$this->m_project_mc->updateSIH($SI_CODE);
		}*/	
			
		// COUNT DATA
			//$this->m_project_mc->count_all_sinew($PRJCODE);
			//$this->m_project_mc->count_all_sicon($PRJCODE);
			//$this->m_project_mc->count_all_siapp($PRJCODE);
			
		// START : UPDATE TO TRANS-COUNT
			$this->load->model('m_updash/m_updash', '', TRUE);
			
			$STAT_BEFORE	= $this->input->post('SI_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
			$parameters 	= array('DOC_CODE' 		=> $SI_CODE,		// TRANSACTION CODE
									'PRJCODE' 		=> $PRJCODE,		// PROJECT
									'TR_TYPE'		=> "SI",			// TRANSACTION TYPE
									'TBL_NAME' 		=> "tbl_siheader",	// TABLE NAME
									'KEY_NAME'		=> "SI_CODE",		// KEY OF THE TABLE
									'STAT_NAME' 	=> "SI_STAT",		// NAMA FIELD STATUS
									'STATDOC' 		=> $SI_STAT,		// TRANSACTION STATUS
									'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
									'FIELD_NM_ALL'	=> "TOT_SI",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
									'FIELD_NM_N'	=> "TOT_SI_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
									'FIELD_NM_C'	=> "TOT_SI_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
									'FIELD_NM_A'	=> "TOT_SI_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
									'FIELD_NM_R'	=> "TOT_SI_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
									'FIELD_NM_RJ'	=> "TOT_SI_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
									'FIELD_NM_CL'	=> "TOT_SI_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
			$this->m_updash->updateDashData($parameters);
		// END : UPDATE TO TRANS-COUNT
			
		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= $PRJCODE;
			$TTR_REFDOC		= $SI_CODE;
			$MenuCode 		= 'MN259';
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
			
		$url			= site_url('c_project/c_mc180c2c_bu/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		redirect($url);
	}
	
	function u180c2cpsi() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$SI_CODE			= $_GET['id'];
			$SI_CODE			= $this->url_encryption_helper->decode_url($SI_CODE);
			
			$getSIDET 			= $this->m_project_mc->get_SI_by_number($SI_CODE)->row();
			$PRJCODE			= $getSIDET->PRJCODE;
			
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
					
			$data['PRJCODE'] 			= $PRJCODE;
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title']			= 'Edit Site Instruction';
			$data['h3_title']			= 'SI Project';
			$data['MenuCode']			= 'MN259';
			$data['form_action']		= site_url('c_project/c_mc180c2c_bu/updateSI_process');
			$cancel_url					= site_url('c_project/c_mc180c2c_bu/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));	
			//$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancel_url;
			
			$data['countPRJ']			= $this->m_project_mc->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_mc->viewProject()->result();
			
			$data['default']['SI_CODE'] 	= $getSIDET->SI_CODE;
			$data['default']['SI_MANNO'] 	= $getSIDET->SI_MANNO;
			$data['default']['SI_INCCON'] 	= $getSIDET->SI_INCCON;
			$data['default']['SI_STEP'] 	= $getSIDET->SI_STEP;
			$data['default']['PRJCODE'] 	= $getSIDET->PRJCODE;
			$data['default']['SI_OWNER'] 	= $getSIDET->SI_OWNER;
			$data['default']['SI_DATE'] 	= $getSIDET->SI_DATE;
			$data['default']['SI_ENDDATE'] 	= $getSIDET->SI_ENDDATE;
			$data['default']['SI_APPDATE'] 	= $getSIDET->SI_APPDATE;
			$data['default']['SI_APPDATE2']	= $getSIDET->SI_APPDATE2;
			$data['default']['SI_CREATED'] 	= $getSIDET->SI_CREATED;
			$data['default']['SI_DESC'] 	= $getSIDET->SI_DESC;
			$data['default']['SI_DPPER'] 	= $getSIDET->SI_DPPER;
			$data['default']['SI_DPVAL'] 	= $getSIDET->SI_DPVAL;
			$data['default']['SI_APPVAL'] 	= $getSIDET->SI_APPVAL;
			$data['default']['SI_VALUE'] 	= $getSIDET->SI_VALUE;
			$data['default']['SI_PROPPERC']	= $getSIDET->SI_PROPPERC;
			$data['default']['SI_PROPVAL'] 	= $getSIDET->SI_PROPVAL;
			$data['default']['SI_REALVAL'] 	= $getSIDET->SI_REALVAL;
			$data['default']['SI_AMAND'] 	= $getSIDET->SI_AMAND;
			$data['default']['SI_AMANDNO'] 	= $getSIDET->SI_AMANDNO;
			$data['default']['SI_AMANDVAL'] = $getSIDET->SI_AMANDVAL;
			$data['default']['SI_AMANDSTAT']= $getSIDET->SI_AMANDSTAT;
			$data['default']['SI_NOTES'] 	= $getSIDET->SI_NOTES;
			$data['default']['SI_EMPID'] 	= $getSIDET->SI_EMPID;
			$data['default']['SI_STAT'] 	= $getSIDET->SI_STAT;
			$data['default']['PATT_YEAR'] 	= $getSIDET->PATT_YEAR;
			$data['default']['PATT_MONTH'] 	= $getSIDET->PATT_MONTH;
			$data['default']['PATT_DATE'] 	= $getSIDET->PATT_DATE;
			$data['default']['PATT_NUMBER'] = $getSIDET->PATT_NUMBER;
			$data['PRJCODE'] 				= $getSIDET->PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getSIDET->SI_CODE;
				$MenuCode 		= 'MN259';
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
			
			$this->load->view('v_project/v_project_mc/project_si_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function updateSI_process() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		$this->db->trans_begin();
		
		date_default_timezone_set("Asia/Jakarta");
		
		$SI_STAT		= $this->input->post('SI_STAT');
		if($SI_STAT == 2)
		{
			$SI_APPDATE	= date('Y-m-d H:i:s');
		}
		else
		{
			$SI_APPDATE	= date('Y-m-d H:i:s');
		}
		
		$SI_DATE		= date('Y-m-d',strtotime($this->input->post('SI_DATE')));
		$SI_ENDDATE		= date('Y-m-d',strtotime($this->input->post('SI_ENDDATE')));
		//$SI_APPDATE	= date('Y-m-d',strtotime($this->input->post('SI_APPDATE')));
		$SI_APPDATE		= date('Y-m-d H:i:s');
		$SI_APPDATE2	= date('Y-m-d H:i:s');
		//$SI_CREATED	= date('Y-m-d H:i:s');
		$PATT_YEAR		= date('Y',strtotime($this->input->post('SI_DATE')));
		
		$SI_CODE 		= $this->input->post('SI_CODE');
		$SI_MANNO 		= $this->input->post('SI_MANNO');
		$PRJCODE 		= $this->input->post('PRJCODE');
		$SI_AMAND		= $this->input->post('SI_AMAND');
		
		$AH_CODE		= $SI_CODE;
		$AH_APPLEV		= $this->input->post('APP_LEVEL');
		$AH_APPROVER	= $DefEmp_ID;
		$AH_APPROVED	= date('Y-m-d H:i:s');
		$AH_NOTES		= $this->input->post('SI_NOTES');
		$AH_ISLAST		= $this->input->post('IS_LAST');
		
		$dataSIH 	= array('SI_CODE' 		=> $SI_CODE,
							'SI_MANNO'		=> $SI_MANNO,
							'SI_INCCON'		=> $this->input->post('SI_INCCON'),
							'SI_STEP'		=> $this->input->post('SI_STEP'),
							'PRJCODE'		=> $PRJCODE,
							'SI_DATE'		=> $SI_DATE,
							'SI_ENDDATE'	=> $SI_ENDDATE,
							'SI_APPDATE'	=> $SI_APPDATE,
							'SI_APPDATE2'	=> $SI_APPDATE2,
							'SI_DESC'		=> $this->input->post('SI_DESC'),
							'SI_DPPER'		=> $this->input->post('SI_DPPER'),
							'SI_DPVAL'		=> $this->input->post('SI_DPVAL'),
							'SI_VALUE'		=> $this->input->post('SI_VALUE'),
							'SI_APPVAL'		=> $this->input->post('SI_APPVAL'),
							'SI_PROPPERC'	=> $this->input->post('SI_PROPPERC'),
							'SI_PROPVAL'	=> $this->input->post('SI_PROPVAL'),
							'SI_REALVAL'	=> $this->input->post('SI_REALVAL'),
							'SI_AMAND'		=> $this->input->post('SI_AMAND'),
							'SI_AMANDNO'	=> $this->input->post('SI_AMANDNO'),
							'SI_AMANDVAL'	=> $this->input->post('SI_AMANDVAL'),
							'SI_AMANDSTAT'	=> $this->input->post('SI_AMANDSTAT'),
							'SI_NOTES'		=> $this->input->post('SI_NOTES'),
							//'SI_EMPID'	=> $DefEmp_ID,
							'SI_EMPIDAPP'	=> $DefEmp_ID,
							'SI_STAT'		=> $this->input->post('SI_STAT'),
							'PATT_YEAR'		=> $PATT_YEAR,
							'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));							
		$this->m_project_mc->updateSI($SI_CODE, $dataSIH, $PRJCODE);
		
		/*if($SI_AMAND == 1)
		{
			$this->m_project_mc->updateSIH($SI_CODE);
		}*/
		
		// SAVE TO PROFITLOSS
		if($SI_STAT == 3)
		{
			$dataSIH 	= array('SI_STAT'	=> 7);							
			$this->m_project_mc->updateSI($SI_CODE, $dataSIH, $PRJCODE);
			// START : SAVE APPROVE HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$AH_CODE		= $SI_CODE;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= date('Y-m-d H:i:s');
				$AH_NOTES		= $this->input->post('MC_NOTES');
				$PR_MEMO		= "";
				$AH_ISLAST		= $this->input->post('IS_LAST');
				
				$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
										'AH_APPLEV'		=> $AH_APPLEV,
										'AH_APPROVER'	=> $AH_APPROVER,
										'AH_APPROVED'	=> $AH_APPROVED,
										'AH_NOTES'		=> $AH_NOTES,
										'AH_ISLAST'		=> $AH_ISLAST);										
				$this->m_updash->insAppHist($insAppHist);
			// END : SAVE APPROVE HISTORY
		}
		
		if($SI_STAT == 3 && $AH_ISLAST == 1)
		{
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $SI_DATE;
				$PERIODM	= date('m', strtotime($PERIODED));
				$PERIODY	= date('Y', strtotime($PERIODED));
				$getLR		= "tbl_profitloss WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
								AND YEAR(PERIODE) = '$PERIODY'";
				$resLR		= $this->db->count_all($getLR);
				if($resLR	== 0)
				{
					$this->m_updash->createLR($PRJCODE, $PERIODED);
				}
			// END : SETTING L/R
							
			// START : UPDATE L/R
				$SI_VALUE		= $this->input->post('SI_VALUE');
				$SI_APPVAL		= $this->input->post('SI_APPVAL');
				$updLR			= "UPDATE tbl_profitloss SET SI_PLAN = SI_PLAN+$SI_VALUE, SI_REAL = SI_REAL+$SI_APPVAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
										AND YEAR(PERIODE) = '$PERIODY'";
				$this->db->query($updLR);
			// END : UPDATE L/R
			
			$dataSIH 	= array('SI_MANNO'		=> $SI_MANNO,
								'SI_INCCON'		=> $this->input->post('SI_INCCON'),
								'SI_STEP'		=> $this->input->post('SI_STEP'),
								'PRJCODE'		=> $PRJCODE,
								'SI_DATE'		=> $SI_DATE,
								'SI_ENDDATE'	=> $SI_ENDDATE,
								'SI_APPDATE'	=> $SI_APPDATE,
								'SI_APPDATE2'	=> $SI_APPDATE2,
								'SI_DESC'		=> $this->input->post('SI_DESC'),
								'SI_DPPER'		=> $this->input->post('SI_DPPER'),
								'SI_DPVAL'		=> $this->input->post('SI_DPVAL'),
								'SI_VALUE'		=> $this->input->post('SI_VALUE'),
								'SI_APPVAL'		=> $this->input->post('SI_APPVAL'),
								'SI_PROPPERC'	=> $this->input->post('SI_PROPPERC'),
								'SI_PROPVAL'	=> $this->input->post('SI_PROPVAL'),
								'SI_REALVAL'	=> $this->input->post('SI_REALVAL'),
								'SI_AMAND'		=> $this->input->post('SI_AMAND'),
								'SI_AMANDNO'	=> $this->input->post('SI_AMANDNO'),
								'SI_AMANDVAL'	=> $this->input->post('SI_AMANDVAL'),
								'SI_AMANDSTAT'	=> $this->input->post('SI_AMANDSTAT'),
								'SI_NOTES'		=> $this->input->post('SI_NOTES'),
								'SI_EMPIDAPP'	=> $DefEmp_ID,
								'SI_STAT'		=> $this->input->post('SI_STAT'),
								'PATT_YEAR'		=> $PATT_YEAR,
								'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));							
			$this->m_project_mc->updateSI($SI_CODE, $dataSIH, $PRJCODE);
		}
			
		// START : UPDATE TO TRANS-COUNT
			$this->load->model('m_updash/m_updash', '', TRUE);
			
			$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
			$parameters 	= array('DOC_CODE' 		=> $SI_CODE,		// TRANSACTION CODE
									'PRJCODE' 		=> $PRJCODE,		// PROJECT
									'TR_TYPE'		=> "SI",			// TRANSACTION TYPE
									'TBL_NAME' 		=> "tbl_siheader",	// TABLE NAME
									'KEY_NAME'		=> "SI_CODE",		// KEY OF THE TABLE
									'STAT_NAME' 	=> "SI_STAT",		// NAMA FIELD STATUS
									'STATDOC' 		=> $SI_STAT,		// TRANSACTION STATUS
									'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
									'FIELD_NM_ALL'	=> "TOT_SI",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
									'FIELD_NM_N'	=> "TOT_SI_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
									'FIELD_NM_C'	=> "TOT_SI_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
									'FIELD_NM_A'	=> "TOT_SI_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
									'FIELD_NM_R'	=> "TOT_SI_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
									'FIELD_NM_RJ'	=> "TOT_SI_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
									'FIELD_NM_CL'	=> "TOT_SI_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
			$this->m_updash->updateDashData($parameters);
		// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $SI_CODE;
				$MenuCode 		= 'MN259';
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
			
		$url			= site_url('c_project/c_mc180c2c_bu/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		redirect($url);
	}
	
	function deleteMC() // OK
	{ 
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$SI_CODE			= $_GET['id'];
			$SI_CODE			= $this->url_encryption_helper->decode_url($SI_CODE);
			
			$getSIDET 			= $this->m_project_mc->get_SI_by_number($SI_CODE)->row();
			$PRJCODE			= $getSIDET->PRJCODE;
		
			$this->db->trans_begin();
						
			$this->m_project_mc->deleteMC($SI_CODE);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_project/c_mc180c2c_bu/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function syncTable($PRJCODE) // OK
	{ 		
		$this->db->trans_begin();
		
		$this->m_project_mc->syncTable($PRJCODE);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		//return false;
		redirect('c_project/c_mc180c2c_bu/gall180c2cmc/'.$PRJCODE);
	}
	
	function popSIAppList() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Select Item';
			//$data['form_action']		= site_url('c_project/material_request_sd/update_process');
			//$data['selDocNumbColl'] 	= $selDocNumbColl;
			$dataSessSrc = array(
				'selSearchproj_Code' 	=> $PRJCODE,
				'selSearchCat' 			=> '',
				'selSearchType' 		=> '',
				'txtSearch' 			=> '');
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			$PRJCODE 					= $PRJCODE;
			$data['PRJCODE'] 			= $PRJCODE;
					
			$this->load->view('v_project/v_project_mc/project_si_view_app_List', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popSIApp() // OK
	{
		$this->load->model('m_project/m_project_mc/m_project_mc', '', TRUE);		
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
			$data['h2_title'] 			= 'Select Item';
			$data['form_action']		= site_url('c_project/material_request_sd/update_process');
			$data['selDocNumbColl'] 	= $this->input->post('selDocNumbColl');
			$selDocNumbColl				= $this->input->post('selDocNumbColl');
			$data['PRJCODE'] 			= $PRJCODE;
			
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_project/v_project_mc/project_si_view_app_admin', $data);
			}
			else
			{
				$this->load->view('v_project/v_project_mc/project_si_view_app', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
}