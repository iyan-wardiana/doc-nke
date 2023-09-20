<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 28 Januari 2019
 * File Name	= C_tr4n5p3r.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_tr4n5p3r extends CI_Controller  
{
 	public function index() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_asset/c_tr4n5p3r/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function listproject($offset=0) // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];		
		if ($this->session->userdata('login') == TRUE)
		{			
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Pemindahan Alat";
				$data["h2_title"] 	= "Daftar Proyek";
				$data["h3_title"] 	= "Pemindahan Alat";
			}
			else
			{
				$data["h1_title"] 	= "Tools Transfer";
				$data["h2_title"] 	= "Project List";
				$data["h3_title"] 	= "Tools Transfer";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_asset/c_tr4n5p3r/tr4n5p3r_i4x/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list';
			if($this->session->userdata['nSELP'] == 0)
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				$this->load->view($data["secVIEW"], $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function tr4n5p3r_i4x()
	{
		$this->load->model('m_asset/m_transfer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			// -------------------- START : SEARCHING METHOD --------------------
				// $chg_url		= 'c_finance/c_f180p0/cp2b0d18_all'
				
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);	
				$EXP_COLLD		= explode('~', $EXP_COLLD1);	
				$C_COLLD1		= count($EXP_COLLD);
				
				if($C_COLLD1 > 1)
				{
					$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
					$key		= $EXP_COLLD[0];
					$PRJCODE	= $EXP_COLLD[1];
					$start		= 0;
					$end		= 30;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_asset/c_tr4n5p3r/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_transfer->count_all_tsf($PRJCODE, $key);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_transfer->get_all_tsf($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
					
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Pemindahan Alat";
				$data["h3_title"] 	= "Pemindahan Alat";
			}
			else
			{
				$data["h2_title"] 	= "Tools Transfer";
				$data["h3_title"] 	= "Tools Transfer";
			}
			
			$linkBack			= site_url('c_asset/c_tr4n5p3r/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['addURL'] 	= site_url('c_asset/c_tr4n5p3r/a44_1stF0rM/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['backURL'] 	= $linkBack;
			$data['PRJCODE']	= $PRJCODE;			
	 		$data["MenuCode"] 	= 'MN109';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN109';
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
			
			$this->load->view('v_asset/v_asset_transfer/asset_transfer', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE";
			$url			= site_url('c_asset/c_tr4n5p3r/tr4n5p3r_i4x/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------
	
	function a44_1stF0rM() // G
	{	
		$this->load->model('m_asset/m_transfer', '', TRUE);
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
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Tambah";
				$data["h3_title"] 	= "Pemindahan Alat";
			}
			else
			{
				$data["h2_title"] 	= "Add";
				$data["h3_title"] 	= "Tools Transfer";
			}
			
			$docPatternPosition		= 'Especially';
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_asset/c_tr4n5p3r/add_process');
			$linkBack				= site_url('c_asset/c_tr4n5p3r/tr4n5p3r_i4x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['backURL'] 		= $linkBack;
			$data['PRJCODE']		= $PRJCODE;	
			$MenuCode 				= 'MN109';
			$data["MenuCode"] 		= 'MN109';
			$data['vwDocPatt'] 		= $this->m_transfer->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN109';
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
			
			$this->load->view('v_asset/v_asset_transfer/asset_transfer_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function p0p_4llM7R() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_asset/m_transfer', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$COLLID		= $_GET['id'];
			$COLLID		= $this->url_encryption_helper->decode_url($COLLID);
			$plitWord	= explode('~', $COLLID);
			$PRJCODE	= $plitWord[0];
			$JOBCODE	= $plitWord[1];
			
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar Material";
			}
			else
			{
				$data["h2_title"] 	= "Material List";
			}
			
			$data['PRJCODE'] 		= $PRJCODE;			
			$data['cAllItem']		= $this->m_transfer->count_all_item($PRJCODE, $JOBCODE);
			$data['vwAllItem'] 		= $this->m_transfer->get_all_item($PRJCODE, $JOBCODE)->result();
					
			$this->load->view('v_asset/v_asset_transfer/asset_transfer_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_asset/m_transfer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN109';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$ASTSF_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$ASTSF_CODE 	= $this->input->post('ASTSF_CODE');
			
			//setting ASTSF Date
			$ASTSF_DATE	= date('Y-m-d',strtotime($this->input->post('ASTSF_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('ASTSF_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('ASTSF_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('ASTSF_DATE')));
			$ASTSF_SENDD	= date('Y-m-d',strtotime($this->input->post('ASTSF_SENDD')));
			$ASTSF_RECD	= date('Y-m-d',strtotime($this->input->post('ASTSF_SENDD')));
			
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE_DEST	= $this->input->post('PRJCODE_DEST');
			//$JOBCODEID 	= $this->input->post('ASTSF_REFNO');
			$JOBCODEID 		= '';
			$ASTSF_NOTE	= $this->input->post('ASTSF_NOTE');
			$ASTSF_NOTE2	= $this->input->post('ASTSF_NOTE2');
			$ASTSF_REVMEMO	= $this->input->post('ASTSF_REVMEMO');
			$ASTSF_SENDER 	= $this->input->post('ASTSF_SENDER');
			$ASTSF_RECEIVER= $this->input->post('ASTSF_RECEIVER');
			$ASTSF_STAT 	= $this->input->post('ASTSF_STAT');
			
			$ASTSF_CREATER	= $DefEmp_ID;
			$ASTSF_CREATED = date('Y-m-d H:i:s');	
			
			$insASTSFH		= array('ASTSF_NUM' 	=> $ASTSF_NUM,
									'ASTSF_CODE'	=> $ASTSF_CODE,
									'ASTSF_DATE'	=> $ASTSF_DATE,
									'ASTSF_SENDD'	=> $ASTSF_SENDD,
									'ASTSF_RECD'	=> $ASTSF_RECD,
									'PRJCODE'		=> $PRJCODE,
									'PRJCODE_DEST'	=> $PRJCODE_DEST,
									'JOBCODEID'		=> $JOBCODEID,
									'ASTSF_NOTE'	=> $ASTSF_NOTE,
									'ASTSF_NOTE2'	=> $ASTSF_NOTE2,
									'ASTSF_REVMEMO'	=> $ASTSF_REVMEMO,
									'ASTSF_SENDER'	=> $ASTSF_SENDER,
									'ASTSF_RECEIVER'=> $ASTSF_RECEIVER,
									'ASTSF_STAT'	=> $ASTSF_STAT,
									'ASTSF_CREATER'	=> $ASTSF_CREATER,
									'ASTSF_CREATED'	=> $ASTSF_CREATED,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_transfer->add($insASTSFH);
			
			foreach($_POST['data'] as $d)
			{
				$d['ASTSF_NUM']	= $ASTSF_NUM;
				$d['ASTSF_CODE']	= $ASTSF_CODE;
				$d['ASTSF_DATE']	= $ASTSF_DATE;
				$d['PRJCODE_DEST']	= $PRJCODE_DEST;
				$this->db->insert('tbl_asset_tsfd',$d);
				
				/* HOLDED ON 28 JAN 2019
				if($ASTSF_STAT == 3 && $AH_ISLAST == 1)
				{
					// UPDATE LAST POSITION
					$AS_CODE	= $d['AS_CODE'];
					$updLASTPOS	= array('AS_LASTPOS' 	=> $PRJCODE);
					$this->m_transfer->updateLP($AS_CODE, $updLASTPOS);
				}*/
			}
			
			// UPDATE DETAIL
				// $this->m_transfer->updateDet($ASTSF_NUM, $PRJCODE, $ASTSF_DATE);	// HOLDED ON 28 JAN 2019
				
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('ASTSF_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $ASTSF_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "ASTSF",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_asset_tsfh",// TABLE NAME
										'KEY_NAME'		=> "ASTSF_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "ASTSF_STAT",	// NAMA FIELD STATUS
										'STATDOC' 		=> $ASTSF_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_TSFT",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_TSFT_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_TSFT_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_TSFT_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_TSFT_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_TSFT_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_TSFT_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ASTSF_NUM;
				$MenuCode 		= 'MN109';
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
			
			$url			= site_url('c_asset/c_tr4n5p3r/tr4n5p3r_i4x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update() // G
	{	
		$this->load->model('m_asset/m_transfer', '', TRUE);
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$ASTSF_NUM		= $_GET['id'];
		$ASTSF_NUM		= $this->url_encryption_helper->decode_url($ASTSF_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_asset/c_tr4n5p3r/update_process');
			
			$getASTSF 			= $this->m_transfer->get_ASTSF_by_number($ASTSF_NUM)->row();									
									
			$data['default']['ASTSF_NUM'] 		= $getASTSF->ASTSF_NUM;
			$data['default']['ASTSF_CODE'] 		= $getASTSF->ASTSF_CODE;
			$data['default']['ASTSF_DATE'] 		= $getASTSF->ASTSF_DATE;
			$data['default']['ASTSF_SENDD'] 	= $getASTSF->ASTSF_SENDD;
			$data['default']['ASTSF_RECD'] 		= $getASTSF->ASTSF_RECD;
			$data['default']['PRJCODE'] 		= $getASTSF->PRJCODE;
			$data['PRJCODE']					= $getASTSF->PRJCODE;
			$PRJCODE 							= $getASTSF->PRJCODE;
			$data['default']['PRJCODE_DEST']	= $getASTSF->PRJCODE_DEST;
			$data['default']['JOBCODEID'] 		= $getASTSF->JOBCODEID;
			$data['default']['ASTSF_NOTE'] 		= $getASTSF->ASTSF_NOTE;
			$data['default']['ASTSF_NOTE2'] 	= $getASTSF->ASTSF_NOTE2;
			$data['default']['ASTSF_REVMEMO'] 	= $getASTSF->ASTSF_REVMEMO;
			$data['default']['ASTSF_STAT'] 		= $getASTSF->ASTSF_STAT;
			$data['default']['ASTSF_AMOUNT'] 	= $getASTSF->ASTSF_AMOUNT;
			$data['default']['ASTSF_SENDER'] 	= $getASTSF->ASTSF_SENDER;
			$data['default']['ASTSF_RECEIVER'] 	= $getASTSF->ASTSF_RECEIVER;
			$data['default']['Patt_Year'] 		= $getASTSF->Patt_Year;
			$data['default']['Patt_Month'] 		= $getASTSF->Patt_Month;
			$data['default']['Patt_Date'] 		= $getASTSF->Patt_Date;
			$data['default']['Patt_Number']		= $getASTSF->Patt_Number;
			
			$data['MenuCode']					= 'MN109';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getASTSF->ASTSF_NUM;
				$MenuCode 		= 'MN109';
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
			
			$this->load->view('v_asset/v_asset_transfer/asset_transfer_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_asset/m_transfer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$ASTSF_NUM 	= $this->input->post('ASTSF_NUM');
			$ASTSF_CODE 	= $this->input->post('ASTSF_CODE');
			
			//setting ASTSF Date
			$ASTSF_DATE	= date('Y-m-d',strtotime($this->input->post('ASTSF_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('ASTSF_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('ASTSF_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('ASTSF_DATE')));
			$ASTSF_SENDD	= date('Y-m-d',strtotime($this->input->post('ASTSF_SENDD')));
			$ASTSF_RECD	= date('Y-m-d',strtotime($this->input->post('ASTSF_SENDD')));
			
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE_DEST	= $this->input->post('PRJCODE_DEST');
			//$JOBCODEID 	= $this->input->post('ASTSF_REFNO');
			$JOBCODEID 		= '';
			$ASTSF_NOTE	= $this->input->post('ASTSF_NOTE');
			$ASTSF_NOTE2	= $this->input->post('ASTSF_NOTE2');
			$ASTSF_REVMEMO	= $this->input->post('ASTSF_REVMEMO');
			$ASTSF_SENDER 	= $this->input->post('ASTSF_SENDER');
			$ASTSF_RECEIVER= $this->input->post('ASTSF_RECEIVER');
			$ASTSF_STAT 	= $this->input->post('ASTSF_STAT');
			
			$ASTSF_CREATER	= $DefEmp_ID;
			$ASTSF_CREATED = date('Y-m-d H:i:s');	
			
			$updASTSFH		= array('ASTSF_CODE'	=> $ASTSF_CODE,
									'ASTSF_DATE'	=> $ASTSF_DATE,
									'ASTSF_SENDD'	=> $ASTSF_SENDD,
									'ASTSF_RECD'	=> $ASTSF_RECD,
									'PRJCODE'		=> $PRJCODE,
									'PRJCODE_DEST'	=> $PRJCODE_DEST,
									'JOBCODEID'		=> $JOBCODEID,
									'ASTSF_NOTE'	=> $ASTSF_NOTE,
									'ASTSF_NOTE2'	=> $ASTSF_NOTE2,
									'ASTSF_REVMEMO'=> $ASTSF_REVMEMO,
									'ASTSF_SENDER'	=> $ASTSF_SENDER,
									'ASTSF_RECEIVER'=> $ASTSF_RECEIVER,
									'ASTSF_STAT'	=> $ASTSF_STAT);
			$this->m_transfer->update($ASTSF_NUM, $updASTSFH);
			
			$this->m_transfer->deleteDetail($ASTSF_NUM);
						
			foreach($_POST['data'] as $d)
			{
				$d['ASTSF_NUM']	= $ASTSF_NUM;
				$d['ASTSF_CODE']	= $ASTSF_CODE;
				$d['ASTSF_DATE']	= $ASTSF_DATE;
				$d['PRJCODE_DEST']	= $PRJCODE_DEST;
				$this->db->insert('tbl_asset_tsfd',$d);
			}
			
			// UPDATE DETAIL
				// $this->m_transfer->updateDet($ASTSF_NUM, $PRJCODE, $ASTSF_DATE);	// HOLDED ON 28 JAN 2019
				
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('ASTSF_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $ASTSF_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "ASTSF",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_asset_tsfh",// TABLE NAME
										'KEY_NAME'		=> "ASTSF_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "ASTSF_STAT",	// NAMA FIELD STATUS
										'STATDOC' 		=> $ASTSF_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_TSFT",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_TSFT_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_TSFT_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_TSFT_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_TSFT_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_TSFT_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_TSFT_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ASTSF_NUM;
				$MenuCode 		= 'MN109';
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
			
			$url			= site_url('c_asset/c_tr4n5p3r/tr4n5p3r_i4x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function inb0x() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_asset/c_tr4n5p3r/t5f_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function t5f_l5t_x1() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_asset/m_transfer', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan Pemindahan";
			}
			else
			{
				$data["h1_title"] 	= "Transfer Approval";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_asset/c_tr4n5p3r/iN20_x1/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list';
			if($this->session->userdata['nSELP'] == 0)
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				$this->load->view($data["secVIEW"], $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function iN20_x1() // OK
	{
		$this->load->model('m_asset/m_transfer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			// -------------------- START : SEARCHING METHOD --------------------				
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);	
				$EXP_COLLD		= explode('~', $EXP_COLLD1);	
				$C_COLLD1		= count($EXP_COLLD);
				
				if($C_COLLD1 > 1)
				{
					$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
					$key		= $EXP_COLLD[0];
					$PRJCODE	= $EXP_COLLD[1];
					$start		= 0;
					$end		= 30;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_asset/c_tr4n5p3r/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				
				$num_rows 			= $this->m_transfer->count_all_tsfInb($PRJCODE, $key, $DefEmp_ID);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_transfer->get_all_tsfInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
					
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Persetujuan Pemindahan";
				$data["h3_title"] 	= "Persetujuan Pemindahan";
			}
			else
			{
				$data["h2_title"] 	= "Transfer Approval";
				$data["h3_title"] 	= "Transfer Approval";
			}

			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_asset/c_tr4n5p3r/inb0x/');
			$data['PRJCODE'] 	= $PRJCODE;

			$MenuCode 			= 'MN391';
			$data["MenuCode"] 	= 'MN391';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN391';
				$TTR_CATEG		= 'APP-L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_asset/v_asset_transfer/asset_inb_transfer', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH1nB()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE";
			$url			= site_url('c_asset/c_tr4n5p3r/iN20_x1/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------
	
	function up180djinb() // G
	{	
		$this->load->model('m_asset/m_transfer', '', TRUE);
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$ASTSF_NUM		= $_GET['id'];
		$ASTSF_NUM		= $this->url_encryption_helper->decode_url($ASTSF_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_asset/c_tr4n5p3r/update_process_inb');
			
			$getASTSF 			= $this->m_transfer->get_ASTSF_by_number($ASTSF_NUM)->row();									
									
			$data['default']['ASTSF_NUM'] 		= $getASTSF->ASTSF_NUM;
			$data['default']['ASTSF_CODE'] 		= $getASTSF->ASTSF_CODE;
			$data['default']['ASTSF_DATE'] 		= $getASTSF->ASTSF_DATE;
			$data['default']['ASTSF_SENDD']	 	= $getASTSF->ASTSF_SENDD;
			$data['default']['ASTSF_RECD'] 		= $getASTSF->ASTSF_RECD;
			$data['default']['PRJCODE'] 		= $getASTSF->PRJCODE;
			$data['PRJCODE']					= $getASTSF->PRJCODE;			
			$PRJCODE 							= $getASTSF->PRJCODE;
			$data['default']['PRJCODE_DEST']	= $getASTSF->PRJCODE_DEST;
			$data['default']['JOBCODEID'] 		= $getASTSF->JOBCODEID;
			$data['default']['ASTSF_NOTE'] 		= $getASTSF->ASTSF_NOTE;
			$data['default']['ASTSF_NOTE2'] 	= $getASTSF->ASTSF_NOTE2;
			$data['default']['ASTSF_REVMEMO'] 	= $getASTSF->ASTSF_REVMEMO;
			$data['default']['ASTSF_STAT'] 		= $getASTSF->ASTSF_STAT;
			$data['default']['ASTSF_AMOUNT'] 	= $getASTSF->ASTSF_AMOUNT;
			$data['default']['ASTSF_SENDER'] 	= $getASTSF->ASTSF_SENDER;
			$data['default']['ASTSF_RECEIVER'] 	= $getASTSF->ASTSF_RECEIVER;
			$data['default']['Patt_Year'] 		= $getASTSF->Patt_Year;
			$data['default']['Patt_Month'] 		= $getASTSF->Patt_Month;
			$data['default']['Patt_Date'] 		= $getASTSF->Patt_Date;
			$data['default']['Patt_Number']		= $getASTSF->Patt_Number;
			
			$MenuCode 							= 'MN391';
			$data["MenuCode"] 					= 'MN391';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getASTSF->ASTSF_NUM;
				$MenuCode 		= 'MN391';
				$TTR_CATEG		= 'APP-U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_asset/v_asset_transfer/asset_inb_transfer_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // G
	{
		$this->load->model('m_asset/m_transfer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$ASTSF_NUM 	= $this->input->post('ASTSF_NUM');
			$ASTSF_CODE 	= $this->input->post('ASTSF_CODE');
			
			//setting ASTSF Date
			$ASTSF_DATE	= date('Y-m-d',strtotime($this->input->post('ASTSF_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('ASTSF_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('ASTSF_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('ASTSF_DATE')));
			$ASTSF_SENDD	= date('Y-m-d',strtotime($this->input->post('ASTSF_SENDD')));
			$ASTSF_RECD	= date('Y-m-d',strtotime($this->input->post('ASTSF_SENDD')));
			
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE_DEST	= $this->input->post('PRJCODE_DEST');
			//$JOBCODEID 	= $this->input->post('ASTSF_REFNO');
			$JOBCODEID 		= '';
			$ASTSF_NOTE		= $this->input->post('ASTSF_NOTE');
			$ASTSF_NOTE2	= $this->input->post('ASTSF_NOTE2');
			$ASTSF_REVMEMO	= $this->input->post('ASTSF_REVMEMO');
			$ASTSF_SENDER 	= $this->input->post('ASTSF_SENDER');
			$ASTSF_RECEIVER	= $this->input->post('ASTSF_RECEIVER');
			$ASTSF_STAT 	= $this->input->post('ASTSF_STAT');
			
			$ASTSF_CREATER	= $DefEmp_ID;
			$ASTSF_CREATED 	= date('Y-m-d H:i:s');	
			
			$updASTSFH		= array('ASTSF_CODE'	=> $ASTSF_CODE,
									'ASTSF_DATE'	=> $ASTSF_DATE,
									'ASTSF_SENDD'	=> $ASTSF_SENDD,
									'ASTSF_RECD'	=> $ASTSF_RECD,
									'PRJCODE'		=> $PRJCODE,
									'PRJCODE_DEST'	=> $PRJCODE_DEST,
									'JOBCODEID'		=> $JOBCODEID,
									'ASTSF_NOTE'	=> $ASTSF_NOTE,
									'ASTSF_NOTE2'	=> $ASTSF_NOTE2,
									'ASTSF_REVMEMO'=> $ASTSF_REVMEMO,
									'ASTSF_SENDER'	=> $ASTSF_SENDER,
									'ASTSF_RECEIVER'=> $ASTSF_RECEIVER,
									'ASTSF_STAT'	=> $ASTSF_STAT);
			$this->m_transfer->update($ASTSF_NUM, $updASTSFH);
									
			foreach($_POST['data'] as $d)
			{
				$ITM_CODE 			= $d['ITM_CODE'];
				$DEST_EXIST 		= $d['DEST_EXIST'];
				$ASTSF_VOLM 		= $d['ASTSF_VOLM'];
				$ASTSF_PRICE 		= $d['ASTSF_PRICE'];
				
				$this->m_transfer->updateAST($ASTSF_NUM, $ITM_CODE, $PRJCODE, $PRJCODE_DEST, $ASTSF_VOLM);
			}
				
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('ASTSF_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $ASTSF_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "ASTSF",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_asset_tsfh",// TABLE NAME
										'KEY_NAME'		=> "ASTSF_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "ASTSF_STAT",	// NAMA FIELD STATUS
										'STATDOC' 		=> $ASTSF_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_TSFT",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_TSFT_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_TSFT_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_TSFT_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_TSFT_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_TSFT_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_TSFT_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ASTSF_NUM;
				$MenuCode 		= 'MN391';
				$TTR_CATEG		= 'APP-UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$url			= site_url('c_asset/c_tr4n5p3r/t5f_l5t_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printdocument()
	{
		$this->load->model('m_asset/m_transfer', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$ASTSF_NUM		= $_GET['id'];
		$ASTSF_NUM		= $this->url_encryption_helper->decode_url($ASTSF_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "DAFTAR LALU LINTAS ALAT";
			}
			else
			{
				$data["h1_title"] 	= "TOOLS TRANSFER LIST";
			}
			$getASTSF 				= $this->m_transfer->get_ASTSF_by_number($ASTSF_NUM)->row();
			$data['ASTSF_NUM'] 		= $getASTSF->ASTSF_NUM;
			$data['ASTSF_CODE'] 	= $getASTSF->ASTSF_CODE;
			$data['ASTSF_DATE'] 	= $getASTSF->ASTSF_DATE;
			$data['ASTSF_SENDD']	= $getASTSF->ASTSF_SENDD;
			$data['ASTSF_RECD'] 	= $getASTSF->ASTSF_RECD;
			$data['PRJCODE'] 		= $getASTSF->PRJCODE;
			$PRJCODE 				= $getASTSF->PRJCODE;
			$data['JOBCODEID'] 		= $getASTSF->JOBCODEID;
			$data['ASTSF_NOTE'] 	= $getASTSF->ASTSF_NOTE;
			$data['ASTSF_NOTE2']	= $getASTSF->ASTSF_NOTE2;
			$data['ASTSF_REVMEMO'] 	= $getASTSF->ASTSF_REVMEMO;
			$data['ASTSF_STAT'] 	= $getASTSF->ASTSF_STAT;
			$data['ASTSF_AMOUNT'] 	= $getASTSF->ASTSF_AMOUNT;
			$data['ASTSF_SENDER'] 	= $getASTSF->ASTSF_SENDER;
			$data['ASTSF_RECEIVER'] = $getASTSF->ASTSF_RECEIVER;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ASTSF_NUM;
				$MenuCode 		= 'MN109';
				$TTR_CATEG		= 'PRINT';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_asset/v_asset_transfer/asset_transfer_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}