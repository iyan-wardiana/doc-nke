<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 April 2017
 * File Name	= C_asset_maintenance.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_asset_maintenance extends CI_Controller  
{
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_asset/c_asset_maintenance/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function listproject($offset=0) // OK
	{
		$this->load->model('m_project/m_joblist/m_joblist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project List';
			$data['h3_title']			= 'asset maintenance';
			
			$num_rows 					= $this->m_joblist->count_all_project();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_joblist->get_last_ten_project()->result();
			
			$this->load->view('v_asset/v_asset_maintenance/listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function index1()
	{
		$this->load->model('m_asset/m_asset_maintenance', '', TRUE);
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
			$data['h2_title']		= 'Asset Maintenance';
			$data['h3_title']		= 'asset management';
			$data['secAddURL'] 		= site_url('c_asset/c_asset_maintenance/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$linkBack				= site_url('c_asset/c_asset_maintenance/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
			//$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= $linkBack;
			$data['PRJCODE']		= $PRJCODE;
			
			$num_rows 				= $this->m_asset_maintenance->count_all_num_rows($PRJCODE);
			$data["recordcount"] 	= $num_rows;
			$data["MenuCode"] 		= 'MN063';
			$data['vAssetUsage']	= $this->m_asset_maintenance->get_last_ten_AU($PRJCODE)->result();
			
			$this->load->view('v_asset/v_asset_maintenance/asset_maintenance', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	// End
	
	function add() // OK
	{	
		$this->load->model('m_asset/m_asset_maintenance', '', TRUE);
		
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
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Maintenance';
			$data['h3_title']		= 'asset management';
			$data['form_action']	= site_url('c_asset/c_asset_maintenance/add_process');
			$linkBack				= site_url('c_asset/c_asset_maintenance/index1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= $linkBack;
			$data['PRJCODE']		= $PRJCODE;	
			$MenuCode 				= 'MN063';
			$data["MenuCode"] 		= 'MN063';
			$data['viewDocPattern'] = $this->m_asset_maintenance->getDataDocPat($MenuCode)->result();
						
			$this->load->view('v_asset/v_asset_maintenance/asset_maintenance_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallasset()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_asset/m_asset_maintenance', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName 	= $therow->app_name;		
			endforeach;
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Asset List';
			$data['h3_title'] 			= 'asset';
			$data['PRJCODE'] 			= $PRJCODE;
			
			$data['reCountAllAsset'] 	= $this->m_asset_maintenance->count_all_num_rowsAllAsset($PRJCODE);
			$data['viewAllAsset'] 		= $this->m_asset_maintenance->viewAllIAsset($PRJCODE)->result();
					
			$this->load->view('v_asset/v_asset_maintenance/asset_mainten_selectasset', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallitem()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_asset/m_asset_maintenance', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'List Item';
			$data['h3_title'] 			= 'asset maintenance';
			$data['PRJCODE'] 			= $PRJCODE;
			
			$data['recordcountAllItem'] = $this->m_asset_maintenance->count_all_num_rowsAllItem($PRJCODE);
			$data['viewAllItem'] 		= $this->m_asset_maintenance->viewAllItemMatBudget($PRJCODE)->result();
					
			$this->load->view('v_asset/v_asset_maintenance/asset_maintenance_selectitem', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_asset/m_asset_maintenance', '', TRUE);
		$this->load->model('m_journal/m_journal', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$AM_CODE	= $this->input->post('AM_CODE');
			$AM_PRJCODE = $this->input->post('AM_PRJCODE');
			$AM_STAT	= $this->input->post('AM_STAT');
			
			date_default_timezone_set("Asia/Jakarta");
			
			$AM_DATE	= date('Y-m-d',strtotime($this->input->post('AM_DATE')));
			$AM_CONFD	= '';
			$AM_APPD	= '';
			
			// SET START DATE AND TIME
				$AM_STARTD		= date('Y-m-d',strtotime($this->input->post('AM_STARTD')));
				$AM_STARTDY		= date('Y', strtotime($AM_STARTD));
				$AM_STARTDM		= date('m', strtotime($AM_STARTD));
				$AM_STARTDD		= date('d', strtotime($AM_STARTD));
				
				$AM_STARTT 		= date('H:i:s',strtotime($this->input->post('AM_STARTT')));
				$AM_STARTTH		= date('H', strtotime($AM_STARTT));
				$AM_STARTTI		= date('i', strtotime($AM_STARTT));
				$AM_STARTTS		= date('s', strtotime($AM_STARTT));
				
				$AM_STARTD		= "$AM_STARTD $AM_STARTT";
				$AM_STARTDC		= mktime($AM_STARTTH, $AM_STARTTI, $AM_STARTTS, $AM_STARTDM, $AM_STARTDD, $AM_STARTDY);
			
			// SET END DATE AND TIME
				$AM_ENDD		= date('Y-m-d',strtotime($this->input->post('AM_STARTT')));
				$AM_ENDDY		= date('Y', strtotime($AM_ENDD));
				$AM_ENDDM		= date('m', strtotime($AM_ENDD));
				$AM_ENDDD		= date('d', strtotime($AM_ENDD));
				
				$AM_ENDT		= date('H:i:s',strtotime($this->input->post('AM_ENDT')));
				$AM_ENDTH		= date('H', strtotime($AM_ENDT));
				$AM_ENDTI		= date('i', strtotime($AM_ENDT));
				$AM_ENDTS		= date('s', strtotime($AM_ENDT));
				
				$AM_ENDD		= "$AM_ENDD $AM_ENDT";
				$AM_ENDDC		= mktime($AM_ENDTH, $AM_ENDTI, $AM_ENDTS, $AM_ENDDM, $AM_ENDDD, $AM_ENDDY);
			
			// START : TIME ASSET PRODUCTION
				$TIME_DIFF 		= $AM_ENDDC - $AM_STARTDC;
				$SEC_TOT		= $TIME_DIFF % 86400;
				$AP_HOPR 		= round(($SEC_TOT/3600), 2);
			
			$AS_LASTSTAT	= 1;
			$AM_PROCS		= 0; // Open
			if($AM_STAT == 1)
			{
				$AS_LASTSTAT= 1;
							
				$AM_CONFD 	= '';
				$AM_PROCS	= 0; // Open
			}
			elseif($AM_STAT == 2)
			{
				$AS_LASTSTAT= 2;
							
				$AM_CONFD 	= date('Y-m-d H:i:s');
				$AM_PROCS	= 0; // Open
			}
			else if($AM_STAT == 3)
			{
				$AS_LASTSTAT= 3;
				
				$AM_APPD 	= date('Y-m-d H:i:s');
				$AM_PROCS	= 1; // Processing
			}
			
			// ADD HEADER
				$InsAU 		= array('AM_CODE' 		=> $this->input->post('AM_CODE'),
									'AM_AS_CODE'	=> $this->input->post('AM_AS_CODE'),
									'AM_DATE'		=> date('Y-m-d',strtotime($this->input->post('AM_DATE'))),
									'AM_PRJCODE'	=> $this->input->post('AM_PRJCODE'),
									'AM_DESC'		=> $this->input->post('AM_DESC'),
									'AM_STARTD'		=> $AM_STARTD,
									'AM_ENDD'		=> $AM_ENDD,
									'AM_STARTT'		=> $AM_STARTT,
									'AM_ENDT'		=> $AM_ENDT,
									'AM_STAT'		=> $this->input->post('AM_STAT'),
									'AM_PROCS'		=> $AM_PROCS,
									'AM_CONFD'		=> $AM_CONFD,
									'AM_APPD'		=> $AM_APPD,
									'Patt_Number'	=> $this->input->post('Patt_Number'));												
				$this->m_asset_maintenance->add($InsAU);
			
			// ADD DETAIL
				foreach($_POST['data'] as $d)
				{
					$d['AM_DATED'] 	= $AM_DATE;
					$this->db->insert('tbl_asset_maintendet',$d);
				}
			
			if($AM_STAT == 3)
			{
				// UPDATE ASSET STATUS TO USED MAINTENANCE
					$AS_CODE	= $this->input->post('AM_AS_CODE');
					$AS_STAT	= 4;		
					$UpdAS 		= array('AS_STAT'	=> $AS_STAT);
					$this->m_asset_maintenance->updateAST($AS_CODE, $UpdAS);
			}
			
			// UPDATE LAST POSITION AND LAST JOB ASSET
				$AS_CODE		= $this->input->post('AM_AS_CODE');		// ASSET CODE
				$AS_LASTPOS		= $AM_PRJCODE;							// LAST POSITION
				$AS_LASTJOB		= 'MNT';								// LAST JOB - Maintenance
				$UpdAS			= array('AS_LASTPOS'	=> $AS_LASTPOS,
										'AS_LASTJOB'	=> $AS_LASTJOB,
										'AS_LASTSTAT'	=> $AS_LASTSTAT);
				$this->m_asset_maintenance->updateAST($AS_CODE, $UpdAS);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_asset_maintenance/index1/?id='.$this->url_encryption_helper->encode_url($AM_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{	
		$this->load->model('m_asset/m_asset_maintenance', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$AM_CODE	= $_GET['id'];
			$AM_CODE	= $this->url_encryption_helper->decode_url($AM_CODE);
			
			$getAU 					= $this->m_asset_maintenance->get_AU($AM_CODE)->row();
			
			$PRJCODE				= $getAU->AM_PRJCODE;
			
			$data['default']['AM_CODE'] 		= $getAU->AM_CODE;
			$data['default']['AM_AS_CODE'] 		= $getAU->AM_AS_CODE;
			$data['default']['AM_DATE'] 		= $getAU->AM_DATE;
			$data['default']['AM_PRJCODE'] 		= $getAU->AM_PRJCODE;
			$data['default']['AM_DESC'] 		= $getAU->AM_DESC;
			$data['default']['AM_STARTD'] 		= $getAU->AM_STARTD;
			$data['default']['AM_ENDD'] 		= $getAU->AM_ENDD;
			$data['default']['AM_STARTT'] 		= $getAU->AM_STARTT;
			$data['default']['AM_ENDT'] 		= $getAU->AM_ENDT;
			$data['default']['AM_STAT'] 		= $getAU->AM_STAT;
			$data['default']['AM_PROCS'] 		= $getAU->AM_PROCS;
			$data['default']['AM_PROCD'] 		= $getAU->AM_PROCD;
			$data['default']['AM_PROCT'] 		= $getAU->AM_PROCT;
			$data['default']['AM_CONFD'] 		= $getAU->AM_CONFD;
			$data['default']['AM_APPD'] 		= $getAU->AM_APPD;
			$data['default']['Patt_Number'] 	= $getAU->Patt_Number;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Add Asset Maintenance';
			$data['h3_title']		= 'asset management';
			$data["MenuCode"] 		= 'MN063';
			$data['form_action']	= site_url('c_asset/c_asset_maintenance/update_process');
			$linkBack				= site_url('c_asset/c_asset_maintenance/index1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= $linkBack;
			$this->load->view('v_asset/v_asset_maintenance/asset_maintenance_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{	
		$this->load->model('m_asset/m_asset_maintenance', '', TRUE);
		$this->load->model('m_journal/m_journal', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$AM_CODE		= $this->input->post('AM_CODE');
			$AM_PRJCODE 	= $this->input->post('AM_PRJCODE');
			$AM_STAT		= $this->input->post('AM_STAT');
			
			$AM_DATE		= date('Y-m-d',strtotime($this->input->post('AM_DATE')));
			$AM_CONFD		= '';
			$AM_APPD		= '';
			
			// SET START DATE AND TIME
				$AM_STARTD		= date('Y-m-d',strtotime($this->input->post('AM_STARTD')));
				$AM_STARTDY		= date('Y', strtotime($AM_STARTD));
				$AM_STARTDM		= date('m', strtotime($AM_STARTD));
				$AM_STARTDD		= date('d', strtotime($AM_STARTD));
				
				$AM_STARTT 		= date('H:i:s',strtotime($this->input->post('AM_STARTT')));
				$AM_STARTTH		= date('H', strtotime($AM_STARTT));
				$AM_STARTTI		= date('i', strtotime($AM_STARTT));
				$AM_STARTTS		= date('s', strtotime($AM_STARTT));
				$AM_STARTD		= "$AM_STARTD $AM_STARTT";
				$AM_STARTDC		= mktime($AM_STARTTH, $AM_STARTTI, $AM_STARTTS, $AM_STARTDM, $AM_STARTDD, $AM_STARTDY);
				
			// SET END DATE AND TIME
				$AM_ENDD		= date('Y-m-d',strtotime($this->input->post('AM_STARTT')));
				$AM_ENDDY		= date('Y', strtotime($AM_ENDD));
				$AM_ENDDM		= date('m', strtotime($AM_ENDD));
				$AM_ENDDD		= date('d', strtotime($AM_ENDD));
				
				$AM_ENDT		= date('H:i:s',strtotime($this->input->post('AM_ENDT')));
				$AM_ENDTH		= date('H', strtotime($AM_ENDT));
				$AM_ENDTI		= date('i', strtotime($AM_ENDT));
				$AM_ENDTS		= date('s', strtotime($AM_ENDT));
				
				$AM_ENDD		= "$AM_ENDD $AM_ENDT";
				$AM_ENDDC		= mktime($AM_ENDTH, $AM_ENDTI, $AM_ENDTS, $AM_ENDDM, $AM_ENDDD, $AM_ENDDY);
			
			// START : TIME ASSET PRODUCTION
				$TIME_DIFF 		= $AM_ENDDC - $AM_STARTDC;
				$SEC_TOT		= $TIME_DIFF % 86400;
				$AP_HOPR 		= round(($SEC_TOT/3600), 2);
			
			$IS_PROCS	= $this->input->post('IS_PROCS');
			$AM_PROCS1	= $this->input->post('AM_PROCS'); // 1. Process, 2. Finish, 3. Canceled
			$AS_CODE	= $this->input->post('AM_AS_CODE');
			
			if($AM_STAT == 1)
			{
				$AS_LASTSTAT= 1;
				
				$AM_CONFD 	= date('Y-m-d H:i:s');
				//$IS_PROCS	= 0;
				$AM_PROCS	= 0;
			}
			elseif($AM_STAT == 2)
			{
				$AS_LASTSTAT= 2;
				
				$AM_CONFD 	= date('Y-m-d H:i:s');
				//$IS_PROCS	= 0;
				$AM_PROCS	= 0;
			}
			else if($AM_STAT == 3)
			{
				$AS_LASTSTAT= 3;
				
				$AM_APPD 	= date('Y-m-d H:i:s');
				//$IS_PROCS	= 1;
				$AM_PROCS	= 1;
				if($AM_PROCS1 > 1)
					$AM_PROCS	= $AM_PROCS1;
			}
			
			// UPDATE HEADER
				$UpdAM 		= array('AM_CODE' 		=> $this->input->post('AM_CODE'),
									'AM_AS_CODE'	=> $this->input->post('AM_AS_CODE'),
									'AM_DATE'		=> date('Y-m-d',strtotime($this->input->post('AM_DATE'))),
									'AM_PRJCODE'	=> $this->input->post('AM_PRJCODE'),
									'AM_DESC'		=> $this->input->post('AM_DESC'),
									'AM_STARTD'		=> $AM_STARTD,
									'AM_ENDD'		=> $AM_ENDD,
									'AM_STARTT'		=> $AM_STARTT,
									'AM_ENDT'		=> $AM_ENDT,
									'AM_STAT'		=> $this->input->post('AM_STAT'),
									'AM_PROCS'		=> $AM_PROCS,
									'AM_CONFD'		=> $AM_CONFD,
									'AM_APPD'		=> $AM_APPD,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
				$this->m_asset_maintenance->update($AM_CODE, $UpdAM);
				
			// UPDATE DETAIL
				$this->m_asset_maintenance->deleteDetail($AM_CODE, $AM_PRJCODE);
				foreach($_POST['data'] as $d)
				{
					$d['AM_DATED'] 	= $AM_DATE;
					$this->db->insert('tbl_asset_maintendet',$d);
				}
			
			if($IS_PROCS > 0) // JIKA SUDAH APPROVE
			{
				$AS_LASTSTAT	= 3;
				
				$AM_PROCD	= date('Y-m-d', strtotime($this->input->post('AM_DATE')));
				$AM_PROCT	= date('H:i:s', strtotime($this->input->post('AM_PROCT')));
				
				$UpdAM 		= array('AM_PROCS'		=> $AM_PROCS,
									'AM_PROCD'		=> $AM_PROCD,
									'AM_PROCT'		=> $AM_PROCT);
				$this->m_asset_maintenance->update($AM_CODE, $UpdAM);
				//return false;
				
				if($AM_PROCS == 2) // IF ASSET MAINTENANCE IS FINISHED : PROCESS
				{
					$AS_LASTSTAT	= 0;
					$JournalH_Code	= $AM_CODE;
					$AMR_CODE		= "DIR";
					$JournalType	= "AM";
					
					// START : CEK JOURNAL, JIKA SUDAH ADA MAKA DELETE				
						$sqlCJ		= "tbl_journalheader WHERE JournalH_Code = '$JournalH_Code' AND JournalType = '$JournalType'";
						$resCJ		= $this->db->count_all($sqlCJ);
						
						if($resCJ > 0)
						{
							$this->m_asset_maintenance->deleteJH($JournalH_Code); 				// DELETE JOURNAL HEADER
							$this->m_asset_maintenance->deleteJD($JournalH_Code, $JournalType); 	// DELETE JOURNAL DETAIL
						}
					// END : CEK JOURNAL, JIKA SUDAH ADA MAKA DELETE
					
					// Start : Pembuatan Journal Header
						$parameters = array('JournalH_Code' 	=> $AM_CODE,
										'JournalType'			=> $JournalType,
										'JournalH_Date' 		=> $AM_APPD,
										'Source'				=> $AMR_CODE,
										'Emp_ID'				=> $DefEmp_ID,
										'LastUpdate'			=> $AM_APPD,	
										'KursAmount_tobase'		=> 1,
										'Reference_Number'		=> '',
										'proj_Code'				=> $AM_PRJCODE);
						$this->m_journal->createJournalH($JournalH_Code, $parameters);
					// End : Pembuatan Journal Header
					
					// Start : Pembuatan Journal Detail
						$AS_CODE	= $this->input->post('AM_AS_CODE');
						$AS_NAME	= '';
							$sqlAST	= "SELECT AS_NAME FROM tbl_asset_list WHERE AS_CODE = '$AS_CODE' LIMIT 1";
							$resAST	= $this->db->query($sqlAST)->result();
							foreach($resAST as $rowsAST) :
								$AS_NAME = $rowsAST->AS_NAME;
							endforeach;
						$PRJNAME	= '';
							$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$AM_PRJCODE' LIMIT 1";
							$resPRJ		= $this->db->query($sqlPRJ)->result();
							foreach($resPRJ as $rowsPRJ) :
								$PRJNAME = $rowsPRJ->PRJNAME;
							endforeach;
						
						$this->m_asset_maintenance->delMntDet($AM_CODE, $AM_PRJCODE);
						$ITM_COSTTOT	= 0;
						foreach($_POST['data'] as $d)
						{
							$JournalH_Code	= $JournalH_Code;
							$AM_PRJCODE 	= $d['AM_PRJCODE'];
							$AM_CODE 		= $d['AM_CODE'];
							$ITM_CODE 		= $d['ITM_CODE'];
							$ITM_UNIT 		= $d['ITM_UNIT'];
							$ITM_QTY 		= $d['ITM_QTY'];
							$ITM_PRICE 		= $d['ITM_PRICE'];
							$ITM_TOTAL 		= $d['ITM_TOTAL'];
							
							// Update Qty RR for Project Plan per Item Per Project
							$parameters = array(
								'JournalH_Code'		=> $JournalH_Code,
								'JSource'			=> $JournalType,
								'proj_Code'			=> $AM_PRJCODE,
								'Transaction_Date'	=> $AM_DATE,
								'Item_Code' 		=> $ITM_CODE,
								'Qty_Plus'			=> $ITM_QTY,
								'Item_Price'		=> $ITM_PRICE
							);
							$this->m_journal->createJournalD($parameters);
							
							// Update Qty IR for Project Plan per Item Per Project
							$PRJCODE 		= $AM_PRJCODE;
							$AM_CODE 		= $AM_CODE;
							$ITM_CODE 		= $d['ITM_CODE'];
							$ITM_QTY 		= $d['ITM_QTY'];
							$ITM_PRICE 		= $d['ITM_PRICE'];
							$ITM_COST		= $ITM_QTY * $ITM_PRICE;
							$ITM_COSTTOT	= $ITM_COSTTOT + $ITM_COST;
							
							$parameterss = array(
								'PRJCODE' 	=> $PRJCODE,
								'AM_CODE'	=> $AM_CODE,
								'ITM_CODE' 	=> $ITM_CODE,
								'ITM_QTY' 	=> $ITM_QTY,
								'ITM_PRICE' => $ITM_PRICE
							);
							$this->m_asset_maintenance->updateITM($parameterss);
						}
						
						// DISIMPAN PERDETAIL MAINTENANCE
						// Start : PEMBUATAN TABEL COST REPORT DAN PRODCUTION
							$AM_DESC	= $this->input->post('AM_DESC');
							
							$InsReport 	= array('RASTC_CODE' 	=> $AM_CODE,
												'RASTC_DATE'	=> $AM_DATE,
												'RASTC_PRJCODE'	=> $AM_PRJCODE,
												'RASTC_PRJNAME'	=> $PRJNAME,
												'RASTC_ASTCODE'	=> $AS_CODE,
												'RASTC_ASTDESC'	=> $AS_NAME,
												'RAST_DESC'		=> $AM_DESC,
												'RASTC_STARTD' 	=> $AM_STARTD,
												'RASTC_ENDD'	=> $AM_ENDD,
												'RASTC_QTYTIME'	=> $AP_HOPR,
												'RASTC_TYPE'	=> 'M',
												//'RASTC_JOBC'	=> $AU_JOBCODE,	
												//'RASTC_JOBD'	=> $JL_NAME,
												'RASTC_VOL'		=> $ITM_QTY,
												//'RASTC_VOLAVG'	=> $VOL_AVG,
												'RASTC_UNIT'	=> $ITM_UNIT,
												'RASTC_COSTTOT'	=> $ITM_COSTTOT,
												//'RASTC_COSTAVGH'=> $COST_AVGH,
												//'RASTC_COSTAVGV'=> $COST_AVGV,
												'RASTC_NOTE'	=> $NOTES);
							$this->m_asset_maintenance->createCostReport($InsReport);
						// End : Pembuatan Journal Detail
					
					// UPDATE ASSET STATUS TO READY
						$AS_CODE	= $this->input->post('AM_AS_CODE');
						$AS_STAT	= 1;		
						$UpdAS 		= array('AS_STAT'	=> $AS_STAT);
						$this->m_asset_maintenance->updateAST($AS_CODE, $UpdAS);
					
					// UPDATE HEADER DETAIL
						foreach($_POST['data'] as $d)
						{
							// Update Qty IR for Project Plan per Item Per Project
							$PRJCODE 		= $AM_PRJCODE;
							$AM_CODE 		= $AM_CODE;
							$ITM_KIND 		= $d['ITM_KIND'];
							
							$parameterss = array(
								'PRJCODE' 	=> $PRJCODE,
								'AM_CODE'	=> $AM_CODE,
								'ITM_KIND' 	=> $ITM_KIND
							);
							$this->m_asset_maintenance->updateAUKIND($parameterss);
						}
				}
				elseif($AM_PROCS == 3) // IF ASSET MAINT IS CANCELED. SO, CREATE VOID JOURNAL & THE ASSET MUST BE ENABLED/READY
				{
					$AS_LASTSTAT	= 0;
					
					// UPDATE HEADER
						$UpdAM 		= array('AM_CODE' 		=> $this->input->post('AM_CODE'),
											'AM_AS_CODE'	=> $this->input->post('AM_AS_CODE'),
											'AM_DATE'		=> date('Y-m-d',strtotime($this->input->post('AM_DATE'))),
											'AM_PRJCODE'	=> $this->input->post('AM_PRJCODE'),
											'AM_DESC'		=> $this->input->post('AM_DESC'),
											'AM_STARTD'		=> $AM_STARTD,
											'AM_ENDD'		=> $AM_ENDD,
											'AM_STARTT'		=> $AM_STARTT,
											'AM_ENDT'		=> $AM_ENDT,
											'AM_STAT'		=> $this->input->post('AM_STAT'),
											'AM_PROCS'		=> $AM_PROCS,
											'AM_CONFD'		=> $AM_CONFD,
											'AM_APPD'		=> $AM_APPD,
											'Patt_Number'	=> $this->input->post('Patt_Number'));
											
						$this->m_asset_maintenance->update($AM_CODE, $UpdAM);
					
					// UPDATE ASSET STATUS TO READY
						$AS_CODE	= $this->input->post('AM_AS_CODE');
						$AS_STAT	= 1;		
						$UpdAS 		= array('AS_STAT'	=> $AS_STAT);
						$this->m_asset_maintenance->updateAST($AS_CODE, $UpdAS);
					
						$JournalH_Code	= $AM_CODE;
						
						$AMR_CODE		= "DIR";
						$JournalType	= "VAM";
						// Start : Pembuatan Journal Header
						$parameters = array('JournalH_Code' 	=> $AM_CODE,
										'JournalType'			=> $JournalType,
										'JournalH_Date' 		=> $AM_APPD,
										'Source'				=> $AMR_CODE,
										'Emp_ID'				=> $DefEmp_ID,
										'LastUpdate'			=> $AM_APPD,	
										'KursAmount_tobase'		=> 1,
										'Reference_Number'		=> '',
										'proj_Code'				=> $AM_PRJCODE);
						$this->m_journal->createJournalH($JournalH_Code, $parameters);
						// End : Pembuatan Journal Header
						
						foreach($_POST['data'] as $d)
						{
							$JournalH_Code	= $JournalH_Code;
							$AM_PRJCODE 	= $d['AM_PRJCODE'];
							$AM_CODE 		= $d['AM_CODE'];
							$ITM_CODE 		= $d['ITM_CODE'];
							$ITM_UNIT 		= $d['ITM_UNIT'];
							$ITM_QTY 		= $d['ITM_QTY'];
							$ITM_PRICE 		= $d['ITM_PRICE'];
							$ITM_TOTAL 		= $d['ITM_TOTAL'];
							
							// Update Qty RR for Project Plan per Item Per Project
							$parameters = array(
								'JournalH_Code'		=> $JournalH_Code,
								'JSource'			=> $JournalType,
								'proj_Code'			=> $AM_PRJCODE,
								'Transaction_Date'	=> $AM_DATE,
								'Item_Code' 		=> $ITM_CODE,
								'Qty_Plus'			=> $ITM_QTY,
								'Item_Price'		=> $ITM_PRICE
							);
							$this->m_journal->createJournalD($parameters);
							
							// Update Qty IR for Project Plan per Item Per Project
							$PRJCODE 		= $AM_PRJCODE;
							$AM_CODE 		= $AM_CODE;
							$ITM_CODE 		= $d['ITM_CODE'];
							$ITM_QTY 		= $d['ITM_QTY'];
							$ITM_PRICE 		= $d['ITM_PRICE'];
							
							$parameterss = array(
								'PRJCODE' 	=> $PRJCODE,
								'AM_CODE'	 => $AM_CODE,
								'ITM_CODE' 	=> $ITM_CODE,
								'ITM_QTY' 	=> $ITM_QTY,
								'ITM_PRICE' => $ITM_PRICE
							);
							$this->m_asset_maintenance->updateITMPLUS($parameterss);
						}
				}
				elseif($AM_PROCS == 1)
				{
					// UPDATE HEADER
						$UpdAM 		= array('AM_CODE' 		=> $this->input->post('AM_CODE'),
											'AM_AS_CODE'	=> $this->input->post('AM_AS_CODE'),
											'AM_DATE'		=> date('Y-m-d',strtotime($this->input->post('AM_DATE'))),
											'AM_PRJCODE'	=> $this->input->post('AM_PRJCODE'),
											'AM_DESC'		=> $this->input->post('AM_DESC'),
											'AM_STARTD'		=> $AM_STARTD,
											'AM_ENDD'		=> $AM_ENDD,
											'AM_STARTT'		=> $AM_STARTT,
											'AM_ENDT'		=> $AM_ENDT,
											'AM_STAT'		=> $this->input->post('AM_STAT'),
											'AM_PROCS'		=> $AM_PROCS,
											'AM_CONFD'		=> $AM_CONFD,
											'AM_APPD'		=> $AM_APPD,
											'Patt_Number'	=> $this->input->post('Patt_Number'));
											
						$this->m_asset_maintenance->update($AM_CODE, $UpdAM);
					
					// SETTING ULANG DETAIL
						$this->m_asset_maintenance->deleteDetail($AM_CODE, $AM_PRJCODE);
					
					// UPDATE DETAIL
						foreach($_POST['data'] as $d)
						{
							$this->db->insert('tbl_asset_maintendet',$d);
						}
				}
			}
						
			// UPDATE LAST POSITION AND LAST JOB ASSET
			$AS_CODE		= $this->input->post('AM_AS_CODE');		// ASSET CODE
			$AS_LASTPOS		= $AM_PRJCODE;							// LAST POSITION
			$AS_LASTJOB		= 'MNT';								// LAST JOB - Maintenance
			$UpdAS			= array('AS_LASTPOS'	=> $AS_LASTPOS,
									'AS_LASTJOB'	=> $AS_LASTJOB,
									'AS_LASTSTAT'	=> $AS_LASTSTAT);
			$this->m_asset_maintenance->updateAST($AS_CODE, $UpdAS);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_asset_maintenance/index1/?id='.$this->url_encryption_helper->encode_url($AM_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function inbox($offset=0) // OK
	{
		$this->load->model('m_asset/m_asset_maintenance', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project List';
			$data['h3_title']			= 'maintenance inbox';
			
			$num_rows 					= $this->m_asset_maintenance->count_all_project_inb();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_asset_maintenance->get_last_ten_project_inb()->result();
			
			$this->load->view('v_asset/v_asset_maintenance/inb_listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function inbox_maintenlist()
	{
		$this->load->model('m_asset/m_asset_maintenance', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Asset Maintenance';
			$data['h3_title']			= 'approve';
			$linkBack					= site_url('c_asset/c_asset_maintenance/inbox/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 				= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE']			= $PRJCODE;
			
			$num_rows 					= $this->m_asset_maintenance->count_all_num_rows_inb($PRJCODE);
			$data["recordcount"] 		= $num_rows;
	 
			$data['vAssetUsage']		= $this->m_asset_maintenance->get_last_ten_AM_inb($PRJCODE)->result();
			
			$this->load->view('v_asset/v_asset_maintenance/inb_asset_maintenance', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function inbox_update() // OK
	{	
		$this->load->model('m_asset/m_asset_maintenance', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$AM_CODE	= $_GET['id'];
			$AM_CODE	= $this->url_encryption_helper->decode_url($AM_CODE);
			
			$getAU 					= $this->m_asset_maintenance->get_AU($AM_CODE)->row();
			
			$PRJCODE				= $getAU->AM_PRJCODE;
			
			$data['default']['AM_CODE'] 		= $getAU->AM_CODE;
			$data['default']['AM_AS_CODE'] 		= $getAU->AM_AS_CODE;
			$data['default']['AM_DATE'] 		= $getAU->AM_DATE;
			$data['default']['AM_PRJCODE'] 		= $getAU->AM_PRJCODE;
			$data['default']['AM_DESC'] 		= $getAU->AM_DESC;
			$data['default']['AM_STARTD'] 		= $getAU->AM_STARTD;
			$data['default']['AM_ENDD'] 		= $getAU->AM_ENDD;
			$data['default']['AM_STARTT'] 		= $getAU->AM_STARTT;
			$data['default']['AM_ENDT'] 		= $getAU->AM_ENDT;
			$data['default']['AM_STAT'] 		= $getAU->AM_STAT;
			$data['default']['AM_CONFD'] 		= $getAU->AM_CONFD;
			$data['default']['AM_APPD'] 		= $getAU->AM_APPD;
			$data['default']['Patt_Number'] 	= $getAU->Patt_Number;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Asset Maintenance';
			$data['h3_title']		= 'approve';
			$data['form_action']	= site_url('c_asset/c_asset_maintenance/inbox_update_process');
			$linkBack				= site_url('c_asset/c_asset_maintenance/inbox_maintenlist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$this->load->view('v_asset/v_asset_maintenance/inb_asset_maintenance_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function inbox_update_process()
	{	
		$this->load->model('m_asset/m_asset_maintenance', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$AM_CODE	= $this->input->post('AM_CODE');
			$AM_PRJCODE = $this->input->post('AM_PRJCODE');
			$AM_STAT	= $this->input->post('AM_STAT');
			
			date_default_timezone_set("Asia/Jakarta");
			$AM_CONFD	= '';
			$AM_APPD	= '';
			if($AM_STAT == 2)
			{				
				$AM_CONFD 	= date('Y-m-d H:i:s');
			}
			else if($AM_STAT == 3)
			{
				$AM_APPD 	= date('Y-m-d H:i:s');
			}
			
			$UpdAU 		= array('AM_CODE' 		=> $this->input->post('AM_CODE'),
								'AM_STAT'		=> $this->input->post('AM_STAT'),
								'AM_APPD'		=> $AM_APPD);
								
			$this->m_asset_maintenance->update($AM_CODE, $UpdAU);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_asset_maintenance/inbox_maintenlist/?id='.$this->url_encryption_helper->encode_url($AM_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}