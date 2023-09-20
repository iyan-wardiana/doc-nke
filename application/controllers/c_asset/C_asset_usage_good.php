<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 April 2017
 * File Name	= C_asset_usage.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_asset_usage extends CI_Controller  
{
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_asset/c_asset_usage/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
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
			$data['h3_title']			= 'asset usage';
			
			$num_rows 					= $this->m_joblist->count_all_project();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_joblist->get_last_ten_project()->result();
			
			$this->load->view('v_asset/v_asset_usage/listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	public function index1()
	{
		$this->load->model('m_asset/m_asset_usage', '', TRUE);
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
			$data['h2_title']			= 'Asset Usage';
			$data['h3_title']			= 'asset management';
			$data['secAddURL'] 			= site_url('c_asset/c_asset_usage/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_asset/c_asset_usage/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			$linkBack					= site_url('c_asset/c_asset_usage/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 				= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE']			= $PRJCODE;
			
			$num_rows 					= $this->m_asset_usage->count_all_num_rows($PRJCODE);
			$data["recordcount"] 		= $num_rows;
	 
			$data['vAssetUsage']		= $this->m_asset_usage->get_last_ten_AU($PRJCODE)->result();
			
			$this->load->view('v_asset/v_asset_usage/asset_usage', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	// End
	
	function add() // OK
	{	
		$this->load->model('m_asset/m_asset_usage', '', TRUE);
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
			
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add Asset Usage';
			$data['h3_title']			= 'asset management';
			$data['form_action']		= site_url('c_asset/c_asset_usage/add_process');
			$linkBack					= site_url('c_asset/c_asset_usage/index1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 				= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE']			= $PRJCODE;
			
			$this->load->view('v_asset/v_asset_usage/asset_usage_form', $data);
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
			$this->load->model('m_asset/m_asset_usage', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'List Item';
			$data['h2_title'] 			= 'asset usage';
			$data['PRJCODE'] 			= $PRJCODE;
			
			$data['recordcountAllItem'] = $this->m_asset_usage->count_all_num_rowsAllItem($PRJCODE);
			$data['viewAllItem'] 		= $this->m_asset_usage->viewAllItemMatBudget($PRJCODE)->result();
					
			$this->load->view('v_asset/v_asset_usage/asset_usage_selectitem', $data);
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
			$this->load->model('m_asset/m_asset_usage', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName 	= $therow->app_name;		
			endforeach;
			$varURL			= $_GET['id'];
			$varURL			= $this->url_encryption_helper->decode_url($varURL);
			$varURLArr 		= explode("|", $varURL);
			$PRJCODE 		= $varURLArr[0];
			$StartDate 		= $varURLArr[1];
			$EndDate 		= $varURLArr[2];
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Asset List';
			$data['h3_title'] 			= 'asset';
			$data['PRJCODE'] 			= $PRJCODE;
			$data['StartDate'] 			= $StartDate;
			$data['EndDate'] 			= $EndDate;
			
			$data['reCountAllAsset'] 	= $this->m_asset_usage->count_all_num_rowsAllAsset($PRJCODE, $StartDate, $EndDate);
			$data['viewAllAsset'] 		= $this->m_asset_usage->viewAllIAsset($PRJCODE, $StartDate, $EndDate)->result();
					
			$this->load->view('v_asset/v_asset_usage/asset_usage_selectasset', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_asset/m_asset_usage', '', TRUE);
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
			
			$AU_CODE		= $this->input->post('AU_CODE');
			$AU_PRJCODE 	= $this->input->post('AU_PRJCODE');
			$AU_STAT		= $this->input->post('AU_STAT');
			
			$AU_DATE		= date('Y-m-d',strtotime($this->input->post('AU_DATE')));
			$AU_CONFD		= '';
			$AU_APPD		= '';
			
			// SET START DATE AND TIME
				$AU_STARTD		= date('Y-m-d',strtotime($this->input->post('AU_STARTD')));
				$AU_STARTDY		= date('Y', strtotime($AU_STARTD));
				$AU_STARTDM		= date('m', strtotime($AU_STARTD));
				$AU_STARTDD		= date('d', strtotime($AU_STARTD));
				
				$AU_STARTT 		= date('H:i:s',strtotime($this->input->post('AU_STARTT')));
				$AU_STARTTH		= date('H', strtotime($AU_STARTT));
				$AU_STARTTI		= date('i', strtotime($AU_STARTT));
				$AU_STARTTS		= date('s', strtotime($AU_STARTT));
				
				$AU_STARTD		= "$AU_STARTD $AU_STARTT";
				$AU_STARTDC		= mktime($AU_STARTTH, $AU_STARTTI, $AU_STARTTS, $AU_STARTDM, $AU_STARTDD, $AU_STARTDY);
			
			
			// SET END DATE AND TIME
				$AU_ENDD		= date('Y-m-d',strtotime($this->input->post('AU_STARTT')));
				$AU_ENDDY		= date('Y', strtotime($AU_ENDD));
				$AU_ENDDM		= date('m', strtotime($AU_ENDD));
				$AU_ENDDD		= date('d', strtotime($AU_ENDD));
				
				$AU_ENDT		= date('H:i:s',strtotime($this->input->post('AU_ENDT')));
				$AU_ENDTH		= date('H', strtotime($AU_ENDT));
				$AU_ENDTI		= date('i', strtotime($AU_ENDT));
				$AU_ENDTS		= date('s', strtotime($AU_ENDT));
				
				$AU_ENDD		= "$AU_ENDD $AU_ENDT";
				$AU_ENDDC		= mktime($AU_ENDTH, $AU_ENDTI, $AU_ENDTS, $AU_ENDDM, $AU_ENDDD, $AU_ENDDY);
			
			// START : TIME ASSET PRODUCTION
				$TIME_DIFF 		= $AU_ENDDC - $AU_STARTDC;
				$SEC_TOT		= $TIME_DIFF % 86400;
				$AP_HOPR 		= round(($SEC_TOT/3600), 2);
				$AP_QTYOPR		= $this->input->post('AP_QTYOPR');
			// END : TIME ASSET PRODUCTION
			
			$AU_PROCS		= 0; // Open
			if($AU_STAT == 2)
			{				
				$AU_CONFD 	= date('Y-m-d H:i:s');
				$AU_PROCS	= 0; // Open
			}
			else if($AU_STAT == 3)
			{
				$AU_APPD 	= date('Y-m-d H:i:s');
				$AU_PROCS	= 1;
			}
			
			$AP_QTYUNIT	= $this->input->post('AP_QTYUNIT');
			$InsAU 		= array('AU_CODE' 		=> $this->input->post('AU_CODE'),
								'AUR_CODE'		=> $this->input->post('AUR_CODE'),
								'AU_JOBCODE'	=> $this->input->post('AU_JOBCODE'),
								'AU_AS_CODE'	=> $this->input->post('AU_AS_CODE'),
								'AU_DATE'		=> date('Y-m-d',strtotime($this->input->post('AU_DATE'))),
								'AU_PRJCODE'	=> $this->input->post('AU_PRJCODE'),
								'AU_DESC'		=> $this->input->post('AU_DESC'),
								'AU_STARTD'		=> $AU_STARTD,
								'AU_ENDD'		=> $AU_ENDD,
								'AU_STARTT'		=> $AU_STARTT,
								'AU_ENDT'		=> $AU_ENDT,
								'AP_HOPR'		=> $AP_HOPR,
								'AP_QTYOPR'		=> $AP_QTYOPR,
								'AP_QTYUNIT'	=> $AP_QTYUNIT,
								'AU_STAT'		=> $this->input->post('AU_STAT'),
								'AU_PROCS'		=> $AU_PROCS,
								'AU_CONFD'		=> $AU_CONFD,
								'AU_APPD'		=> $AU_APPD,
								'Patt_Number'	=> $this->input->post('Patt_Number'));
												
			$this->m_asset_usage->add($InsAU);
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_asset_usagedet',$d);
			}
				
			if($AU_STAT == 3)
			{
				// UPDATE ASSET STATUS TO USED
				$AS_CODE	= $this->input->post('AU_AS_CODE');
				$AS_STAT	= 3;		
				$UpdAS 		= array('AS_STAT'	=> $AS_STAT);
				$this->m_asset_usage->updateAST($AS_CODE, $UpdAS);
				
				$JournalH_Code	= $AU_CODE;
				
				$AUR_CODE		= "DIR";
				$JournalType	= "AU";
				// Start : Pembuatan Journal Header
				$parameters = array('JournalH_Code' 	=> $AU_CODE,
								'JournalType'			=> $JournalType,
								'JournalH_Date' 		=> $AU_APPD,
								'Source'				=> $AUR_CODE,
								'Emp_ID'				=> $DefEmp_ID,
								'LastUpdate'			=> $AU_APPD,	
								'KursAmount_tobase'		=> 1,
								'Reference_Number'		=> '',
								'proj_Code'				=> $AU_PRJCODE);
				$this->m_journal->createJournalH($JournalH_Code, $parameters);
				// End : Pembuatan Journal Header
				
				foreach($_POST['data'] as $d)
				{
					$JournalH_Code	= $JournalH_Code;
					$AU_PRJCODE 	= $d['AU_PRJCODE'];
					$AU_CODE 		= $d['AU_CODE'];
					$ITM_CODE 		= $d['ITM_CODE'];
					$ITM_UNIT 		= $d['ITM_UNIT'];
					$ITM_QTY_P 		= $d['ITM_QTY_P'];
					$ITM_QTY 		= $d['ITM_QTY'];
					$ITM_PRICE 		= $d['ITM_PRICE'];
					$ITM_TOTAL 		= $d['ITM_TOTAL'];
					
					// Update Qty RR for Project Plan per Item Per Project
					$parameters = array(
						'JournalH_Code'		=> $JournalH_Code,
						'JSource'			=> $JournalType,
						'proj_Code'			=> $AU_PRJCODE,
						'Transaction_Date'	=> $AU_DATE,
						'Item_Code' 		=> $ITM_CODE,
						'Qty_Plus'			=> $ITM_QTY_P,
						'Item_Price'		=> $ITM_PRICE
					);
					$this->m_journal->createJournalD($parameters);
					
					// Update Qty IR for Project Plan per Item Per Project
					$PRJCODE 		= $AU_PRJCODE;
					$AU_CODE 		= $AU_CODE;
					$ITM_CODE 		= $d['ITM_CODE'];
					$ITM_QTY 		= $d['ITM_QTY'];
					$ITM_PRICE 		= $d['ITM_PRICE'];
					
					$parameterss = array(
						'PRJCODE' 	=> $PRJCODE,
						'AU_CODE'	 => $AU_CODE,
						'ITM_CODE' 	=> $ITM_CODE,
						'ITM_QTY' 	=> $ITM_QTY,
						'ITM_PRICE' => $ITM_PRICE
					);
					// Update Item akan dirubah setelah proses selesai
					$this->m_asset_usage->updateITM($parameterss);
				}
			}
			
			// START : UPDATE EXPENSE PRODUCTION -- MODIFY : Diupdate setelah prpses finish agar waktu dihitung setelah proses
				//$this->m_asset_usage->updateEXP($AU_CODE, $AU_PRJCODE, $AP_HOPR, $AP_QTYOPR);
			// END : UPDATE EXPENSE PRODUCTION
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_asset_usage/index1/?id='.$this->url_encryption_helper->encode_url($AU_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{	
		$this->load->model('m_asset/m_asset_usage', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$AU_CODE	= $_GET['id'];
			$AU_CODE	= $this->url_encryption_helper->decode_url($AU_CODE);
			
			$getAU 					= $this->m_asset_usage->get_AU($AU_CODE)->row();
			
			$PRJCODE				= $getAU->AU_PRJCODE;
			
			$data['default']['AU_CODE'] 		= $getAU->AU_CODE;
			$data['default']['AUR_CODE'] 		= $getAU->AUR_CODE;
			$data['default']['AU_JOBCODE'] 		= $getAU->AU_JOBCODE;
			$data['default']['AU_AS_CODE'] 		= $getAU->AU_AS_CODE;
			$data['default']['AU_DATE'] 		= $getAU->AU_DATE;
			$data['default']['AU_PRJCODE'] 		= $getAU->AU_PRJCODE;
			$data['default']['AU_DESC'] 		= $getAU->AU_DESC;
			$data['default']['AU_STARTD'] 		= $getAU->AU_STARTD;
			$data['default']['AU_ENDD'] 		= $getAU->AU_ENDD;
			$data['default']['AU_STARTT'] 		= $getAU->AU_STARTT;
			$data['default']['AU_ENDT'] 		= $getAU->AU_ENDT;
			$data['default']['AP_QTYOPR'] 		= $getAU->AP_QTYOPR;
			$data['default']['AP_QTYUNIT'] 		= $getAU->AP_QTYUNIT;
			$data['default']['AU_STAT'] 		= $getAU->AU_STAT;
			$data['default']['AU_PROCS'] 		= $getAU->AU_PROCS;
			$data['default']['AU_CONFD'] 		= $getAU->AU_CONFD;
			$data['default']['AU_APPD'] 		= $getAU->AU_APPD;
			$data['default']['AU_PROCD'] 		= $getAU->AU_PROCD;
			$data['default']['AU_PROCT'] 		= $getAU->AU_PROCT;
			$data['default']['Patt_Number'] 	= $getAU->Patt_Number;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Add Asset Usage';
			$data['h3_title']		= 'asset management';
			$data['form_action']	= site_url('c_asset/c_asset_usage/update_process');
			$linkBack				= site_url('c_asset/c_asset_usage/index1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$this->load->view('v_asset/v_asset_usage/asset_usage_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{	
		$this->load->model('m_asset/m_asset_usage', '', TRUE);
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
			
			$AU_CODE		= $this->input->post('AU_CODE');
			$AU_PRJCODE 	= $this->input->post('AU_PRJCODE');
			$AU_STAT		= $this->input->post('AU_STAT');
			
			$AU_DATE		= date('Y-m-d',strtotime($this->input->post('AU_DATE')));
			$AU_CONFD		= '';
			$AU_APPD		= '';
			
			// SET START DATE AND TIME
				$AU_STARTD		= date('Y-m-d',strtotime($this->input->post('AU_STARTD')));
				$AU_STARTDY		= date('Y', strtotime($AU_STARTD));
				$AU_STARTDM		= date('m', strtotime($AU_STARTD));
				$AU_STARTDD		= date('d', strtotime($AU_STARTD));
				
				$AU_STARTT 		= date('H:i:s',strtotime($this->input->post('AU_STARTT')));
				$AU_STARTTH		= date('H', strtotime($AU_STARTT));
				$AU_STARTTI		= date('i', strtotime($AU_STARTT));
				$AU_STARTTS		= date('s', strtotime($AU_STARTT));
				
				$AU_STARTD		= "$AU_STARTD $AU_STARTT";
				$AU_STARTDC		= mktime($AU_STARTTH, $AU_STARTTI, $AU_STARTTS, $AU_STARTDM, $AU_STARTDD, $AU_STARTDY);
			
			// SET END DATE AND TIME
				$AU_ENDD		= date('Y-m-d',strtotime($this->input->post('AU_STARTT')));
				$AU_ENDDY		= date('Y', strtotime($AU_ENDD));
				$AU_ENDDM		= date('m', strtotime($AU_ENDD));
				$AU_ENDDD		= date('d', strtotime($AU_ENDD));
				
				$AU_ENDT		= date('H:i:s',strtotime($this->input->post('AU_ENDT')));
				$AU_ENDTH		= date('H', strtotime($AU_ENDT));
				$AU_ENDTI		= date('i', strtotime($AU_ENDT));
				$AU_ENDTS		= date('s', strtotime($AU_ENDT));
				
				$AU_ENDD		= "$AU_ENDD $AU_ENDT";
				$AU_ENDDC		= mktime($AU_ENDTH, $AU_ENDTI, $AU_ENDTS, $AU_ENDDM, $AU_ENDDD, $AU_ENDDY);
			
			// START : TIME ASSET PRODUCTION
				$TIME_DIFF 		= $AU_ENDDC - $AU_STARTDC;
				$SEC_TOT		= $TIME_DIFF % 86400;
				$AP_HOPR 		= round(($SEC_TOT/3600), 2);
				$AP_QTYOPR		= $this->input->post('AP_QTYOPR');
			// END : TIME ASSET PRODUCTION
			
			$IS_PROCS	= $this->input->post('IS_PROCS');
			$AU_PROCS1	= $this->input->post('AU_PROCS'); // 1. Process, 2. Finish, 3. Canceled
			$AS_CODE	= $this->input->post('AU_AS_CODE');
			
			if($AU_STAT == 2)
			{
				$AU_CONFD 	= date('Y-m-d H:i:s');
				//$IS_PROCS	= 0;
				$AU_PROCS	= 0;
			}
			else if($AU_STAT == 3)
			{
				$AU_APPD 	= date('Y-m-d H:i:s');
				//$IS_PROCS	= 1;
				$AU_PROCS	= 1;
				if($AU_PROCS1 > 1)
					$AU_PROCS	= $AU_PROCS1;					
			}
			
			if($IS_PROCS == 0) // HANYA DAN HANYA JIKA Status is OPEN (NOT APPROVED)
			{
				if($AU_STAT == 2)
				{
					$IS_PROCS	= 0;
				}
				else if($AU_STAT == 3)
				{
					$IS_PROCS	= 1;
				}
				
				// UPDATE HEADER
					$UpdAU 		= array('AU_CODE' 		=> $this->input->post('AU_CODE'),
										'AUR_CODE'		=> $this->input->post('AUR_CODE'),
										'AU_JOBCODE'	=> $this->input->post('AU_JOBCODE'),
										'AU_AS_CODE'	=> $this->input->post('AU_AS_CODE'),
										'AU_DATE'		=> date('Y-m-d',strtotime($this->input->post('AU_DATE'))),
										'AU_PRJCODE'	=> $this->input->post('AU_PRJCODE'),
										'AU_DESC'		=> $this->input->post('AU_DESC'),
										'AU_STARTD'		=> $AU_STARTD,
										'AU_ENDD'		=> $AU_ENDD,
										'AU_STARTT'		=> $AU_STARTT,
										'AU_ENDT'		=> $AU_ENDT,
										'AU_STAT'		=> $this->input->post('AU_STAT'),
										'AU_PROCS'		=> $AU_PROCS,
										'AU_CONFD'		=> $AU_CONFD,
										'AU_APPD'		=> $AU_APPD,
										'Patt_Number'	=> $this->input->post('Patt_Number'));
										
					$this->m_asset_usage->update($AU_CODE, $UpdAU);
					
					$this->m_asset_usage->deleteDetail($AU_CODE, $AU_PRJCODE);
			
				// UPDATE DETAIL
					foreach($_POST['data'] as $d)
					{
						$this->db->insert('tbl_asset_usagedet',$d);
					}
					
					// TIDAK ADA KODINGAN INI JUGA SEBANRNYA TIDAK APA-APA
					if($AU_STAT == 3)
					{
						// UPDATE ASSET STATUS TO USED
						$AS_CODE	= $this->input->post('AU_AS_CODE');
						$AS_STAT	= 3;		
						$UpdAS 		= array('AS_STAT'	=> $AS_STAT);
						$this->m_asset_usage->updateAST($AS_CODE, $UpdAS);
						
						$JournalH_Code	= $AU_CODE;
						$AUR_CODE		= "DIR";
						$JournalType	= "AU";
						
						// START : CEK JOURNAL, JIKA SUDAH ADA MAKA DELETE				
							$sqlCJ		= "tbl_journalheader WHERE JournalH_Code = '$JournalH_Code' AND JournalType = '$JournalType'";
							$resCJ		= $this->db->count_all($sqlCJ);
							
							if($resCJ > 0)
							{
								$this->m_asset_usage->deleteJH($JournalH_Code); 				// DELETE JOURNAL HEADER
								$this->m_asset_usage->deleteJD($JournalH_Code, $JournalType); 	// DELETE JOURNAL DETAIL
							}
						// END : CEK JOURNAL, JIKA SUDAH ADA MAKA DELETE	
						
						// Start : Pembuatan Journal Header
							$parameters = array('JournalH_Code' 	=> $AU_CODE,
											'JournalType'			=> $JournalType,
											'JournalH_Date' 		=> $AU_APPD,
											'Source'				=> $AUR_CODE,
											'Emp_ID'				=> $DefEmp_ID,
											'LastUpdate'			=> $AU_APPD,	
											'KursAmount_tobase'		=> 1,
											'Reference_Number'		=> '',
											'proj_Code'				=> $AU_PRJCODE);
							$this->m_journal->createJournalH($JournalH_Code, $parameters);
						// End : Pembuatan Journal Header
						
						// Start : Pembuatan Journal Detail
							foreach($_POST['data'] as $d)
							{
								$JournalH_Code	= $JournalH_Code;
								$AU_PRJCODE 	= $d['AU_PRJCODE'];
								$AU_CODE 		= $d['AU_CODE'];
								$ITM_CODE 		= $d['ITM_CODE'];
								$ITM_UNIT 		= $d['ITM_UNIT'];
								$ITM_QTY 		= $d['ITM_QTY'];
								$ITM_PRICE 		= $d['ITM_PRICE'];
								$ITM_TOTAL 		= $d['ITM_TOTAL'];
								
								$parameters = array(
									'JournalH_Code'		=> $JournalH_Code,
									'JSource'			=> $JournalType,
									'proj_Code'			=> $AU_PRJCODE,
									'Transaction_Date'	=> $AU_DATE,
									'Item_Code' 		=> $ITM_CODE,
									'Qty_Plus'			=> $ITM_QTY,
									'Item_Price'		=> $ITM_PRICE
								);
								$this->m_journal->createJournalD($parameters);
								
								$PRJCODE 		= $AU_PRJCODE;
								$AU_CODE 		= $AU_CODE;
								$ITM_CODE 		= $d['ITM_CODE'];
								$ITM_QTY 		= $d['ITM_QTY'];
								$ITM_PRICE 		= $d['ITM_PRICE'];
								$ITM_KIND 		= $d['ITM_KIND'];
								
								$parameterss = array(
									'PRJCODE' 	=> $PRJCODE,
									'AU_CODE'	 => $AU_CODE,
									'ITM_CODE' 	=> $ITM_CODE,
									'ITM_QTY' 	=> $ITM_QTY,
									'ITM_PRICE' => $ITM_PRICE,
									'ITM_PRICE' => $ITM_PRICE,
									'ITM_KIND' 	=> $ITM_KIND
								);
								// Update Item
								$this->m_asset_usage->updateITM($parameterss);
							}
						// End : Pembuatan Journal Detail
					}
			}
			else // JIKA SUDAH APPROVE
			{
				if($AU_PROCS == 2) // IF ASSET USAGE IS FINISHED : PREPARING
				{
					// SET START DATE AND TIME
						$AU_PROCD		= date('Y-m-d',strtotime($this->input->post('AU_PROCD')));
						$AU_PROCDY		= date('Y', strtotime($AU_PROCD));
						$AU_PROCDM		= date('m', strtotime($AU_PROCD));
						$AU_PROCDD		= date('d', strtotime($AU_PROCD));
						
						$AU_PROCT 		= date('H:i:s',strtotime($this->input->post('AU_PROCT')));
						$AU_PROCTH		= date('H', strtotime($AU_PROCT));
						$AU_PROCTI		= date('i', strtotime($AU_PROCT));
						$AU_PROCTS		= date('s', strtotime($AU_PROCT));
						
						$AU_PROCD		= "$AU_PROCD $AU_PROCT";
						$AU_PROCDC		= mktime($AU_PROCTH, $AU_PROCTI, $AU_PROCTS, $AU_PROCDM, $AU_PROCDD, $AU_PROCDY);
						
					// START : TIME ASSET PRODUCTION
						$TIME_DIFF 		= $AU_PROCDC - $AU_STARTDC;
						$SEC_TOT		= $TIME_DIFF % 86400;
						$AP_HOPR 		= round(($SEC_TOT/3600), 2);
						$AP_QTYOPR		= $this->input->post('AP_QTYOPR');
					// END : TIME ASSET PRODUCTION
				}
				
				$AP_QTYUNIT	= $this->input->post('AP_QTYUNIT');
				$AU_PROCD	= date('Y-m-d', strtotime($this->input->post('AU_DATE')));
				$AU_PROCT	= date('H:i:s', strtotime($this->input->post('AU_PROCT')));
				$UpdAU 		= array('AU_PROCS'			=> $AU_PROCS,
										'AP_HOPR'		=> $AP_HOPR,
										'AP_QTYUNIT'	=> $AP_QTYUNIT,
										'AU_PROCD'		=> $AU_PROCD,
										'AU_PROCT'		=> $AU_PROCT);
				$this->m_asset_usage->update($AU_CODE, $UpdAU);
				
				if($AU_PROCS == 2) // IF ASSET USAGE IS FINISHED : PROCESS
				{
					// SETTING ULANG DETAIL
					$this->m_asset_usage->deleteDetail($AU_CODE, $AU_PRJCODE);
					
					// UPDATE DETAIL
					foreach($_POST['data'] as $d)
					{
						$this->db->insert('tbl_asset_usagedet',$d);
					}
					
					// RESET JOURNAL KARENA MUNGKIN SAJA ADA PERUBAHAN QTY REALISASI
					if($AU_STAT == 3)
					{						
						$JournalH_Code	= $AU_CODE;
						$AUR_CODE		= "DIR";
						$JournalType	= "AU";
						
						// START : CEK JOURNAL, JIKA SUDAH ADA MAKA DELETE				
							$sqlCJ		= "tbl_journalheader WHERE JournalH_Code = '$JournalH_Code' AND JournalType = '$JournalType'";
							$resCJ		= $this->db->count_all($sqlCJ);
							
							if($resCJ > 0)
							{
								$this->m_asset_usage->deleteJH($JournalH_Code); 				// DELETE JOURNAL HEADER
								$this->m_asset_usage->deleteJD($JournalH_Code, $JournalType); 	// DELETE JOURNAL DETAIL
							}
						// END : CEK JOURNAL, JIKA SUDAH ADA MAKA DELETE	
						
						// Start : Pembuatan Journal Header
							$parameters = array('JournalH_Code' 	=> $AU_CODE,
											'JournalType'			=> $JournalType,
											'JournalH_Date' 		=> $AU_APPD,
											'Source'				=> $AUR_CODE,
											'Emp_ID'				=> $DefEmp_ID,
											'LastUpdate'			=> $AU_APPD,	
											'KursAmount_tobase'		=> 1,
											'Reference_Number'		=> '',
											'proj_Code'				=> $AU_PRJCODE);
							$this->m_journal->createJournalH($JournalH_Code, $parameters);
						// End : Pembuatan Journal Header
						
						// Start : Pembuatan Journal Detail
							foreach($_POST['data'] as $d)
							{
								$JournalH_Code	= $JournalH_Code;
								$AU_PRJCODE 	= $d['AU_PRJCODE'];
								$AU_CODE 		= $d['AU_CODE'];
								$ITM_CODE 		= $d['ITM_CODE'];
								$ITM_UNIT 		= $d['ITM_UNIT'];
								$ITM_QTY 		= $d['ITM_QTY'];
								$ITM_PRICE 		= $d['ITM_PRICE'];
								$ITM_TOTAL 		= $d['ITM_TOTAL'];
								
								$parameters = array(
									'JournalH_Code'		=> $JournalH_Code,
									'JSource'			=> $JournalType,
									'proj_Code'			=> $AU_PRJCODE,
									'Transaction_Date'	=> $AU_DATE,
									'Item_Code' 		=> $ITM_CODE,
									'Qty_Plus'			=> $ITM_QTY,
									'Item_Price'		=> $ITM_PRICE
								);
								$this->m_journal->createJournalD($parameters);
								
								$PRJCODE 		= $AU_PRJCODE;
								$AU_CODE 		= $AU_CODE;
								$ITM_CODE 		= $d['ITM_CODE'];
								$ITM_QTY 		= $d['ITM_QTY'];
								$ITM_PRICE 		= $d['ITM_PRICE'];
								$ITM_KIND 		= $d['ITM_KIND'];
								
								$parameterss = array(
									'PRJCODE' 	=> $PRJCODE,
									'AU_CODE'	 => $AU_CODE,
									'ITM_CODE' 	=> $ITM_CODE,
									'ITM_QTY' 	=> $ITM_QTY,
									'ITM_PRICE' => $ITM_PRICE,
									'ITM_PRICE' => $ITM_PRICE,
									'ITM_KIND' 	=> $ITM_KIND
								);
								// Update Item
								$this->m_asset_usage->updateITM($parameterss);
							}
						// End : Pembuatan Journal Detail
					}
					
					// UPDATE ASSET STATUS TO READY
					$AS_CODE	= $this->input->post('AU_AS_CODE');
					$AS_STAT	= 1;		
					$UpdAS 		= array('AS_STAT'	=> $AS_STAT);
					$this->m_asset_usage->updateAST($AS_CODE, $UpdAS);
				
					$JournalH_Code	= $AU_CODE;
					$AUR_CODE		= "DIR";
					$JournalType	= "AU";
					
					foreach($_POST['data'] as $d)
					{
						// Update Qty IR for Project Plan per Item Per Project
						$PRJCODE 		= $AU_PRJCODE;
						$AU_CODE 		= $AU_CODE;
						//$ITM_CODE 	= $d['ITM_CODE'];
						//$ITM_QTY 		= $d['ITM_QTY'];
						//$ITM_PRICE 	= $d['ITM_PRICE'];
						$ITM_KIND 		= $d['ITM_KIND'];
						
						$parameterss = array(
							'PRJCODE' 	=> $PRJCODE,
							'AU_CODE'	 => $AU_CODE,
							//'ITM_CODE'	=> $ITM_CODE,
							//'ITM_QTY' 	=> $ITM_QTY,
							//'ITM_PRICE'	=> $ITM_PRICE,
							'ITM_KIND' 	=> $ITM_KIND
						);
						$this->m_asset_usage->updateAUKIND($parameterss);
					}
				}
				elseif($AU_PROCS == 3) // IF ASSET USAGE IS CANCELED. SO, CREATE VOID JOURNAL & THE ASSET MUST BE ENABLED/READY
				{
					// UPDATE ASSET STATUS TO READY
					$AS_CODE	= $this->input->post('AU_AS_CODE');
					$AS_STAT	= 1;		
					$UpdAS 		= array('AS_STAT'	=> $AS_STAT);
					$this->m_asset_usage->updateAST($AS_CODE, $UpdAS);
					
					$JournalH_Code	= $AU_CODE;
					
					$AUR_CODE		= "DIR";
					$JournalType	= "VAU";
					// Start : Pembuatan Journal Header
						$parameters = array('JournalH_Code' 	=> $AU_CODE,
										'JournalType'			=> $JournalType,
										'JournalH_Date' 		=> $AU_APPD,
										'Source'				=> $AUR_CODE,
										'Emp_ID'				=> $DefEmp_ID,
										'LastUpdate'			=> $AU_APPD,	
										'KursAmount_tobase'		=> 1,
										'Reference_Number'		=> '',
										'proj_Code'				=> $AU_PRJCODE);
						$this->m_journal->createJournalH($JournalH_Code, $parameters);
					// End : Pembuatan Journal Header
					
					// Start : Pembuatan Journal Detail
						foreach($_POST['data'] as $d)
						{
							$JournalH_Code	= $JournalH_Code;
							$AU_PRJCODE 	= $d['AU_PRJCODE'];
							$AU_CODE 		= $d['AU_CODE'];
							$ITM_CODE 		= $d['ITM_CODE'];
							$ITM_UNIT 		= $d['ITM_UNIT'];
							$ITM_QTY 		= $d['ITM_QTY'];
							$ITM_PRICE 		= $d['ITM_PRICE'];
							$ITM_TOTAL 		= $d['ITM_TOTAL'];
							
							$parameters = array(
								'JournalH_Code'		=> $JournalH_Code,
								'JSource'			=> $JournalType,
								'proj_Code'			=> $AU_PRJCODE,
								'Transaction_Date'	=> $AU_DATE,
								'Item_Code' 		=> $ITM_CODE,
								'Qty_Plus'			=> $ITM_QTY,
								'Item_Price'		=> $ITM_PRICE
							);
							$this->m_journal->createJournalD($parameters);
							
							$PRJCODE 		= $AU_PRJCODE;
							$AU_CODE 		= $AU_CODE;
							$ITM_CODE 		= $d['ITM_CODE'];
							$ITM_QTY 		= $d['ITM_QTY'];
							$ITM_PRICE 		= $d['ITM_PRICE'];
							
							$parameterss = array(
								'PRJCODE' 	=> $PRJCODE,
								'AU_CODE'	 => $AU_CODE,
								'ITM_CODE' 	=> $ITM_CODE,
								'ITM_QTY' 	=> $ITM_QTY,
								'ITM_PRICE' => $ITM_PRICE
							);
							$this->m_asset_usage->updateITMPLUS($parameterss);
						}
					// End : Pembuatan Journal Detail
				}
				elseif($AU_PROCS == 1) // IF ASSET USAGE IS PROCESSING
				{
					// UPDATE HEADER
						$UpdAU 		= array('AU_CODE' 		=> $this->input->post('AU_CODE'),
											'AUR_CODE'		=> $this->input->post('AUR_CODE'),
											'AU_JOBCODE'	=> $this->input->post('AU_JOBCODE'),
											'AU_AS_CODE'	=> $this->input->post('AU_AS_CODE'),
											'AU_DATE'		=> date('Y-m-d',strtotime($this->input->post('AU_DATE'))),
											'AU_PRJCODE'	=> $this->input->post('AU_PRJCODE'),
											'AU_DESC'		=> $this->input->post('AU_DESC'),
											'AU_STARTD'		=> $AU_STARTD,
											'AU_ENDD'		=> $AU_ENDD,
											'AU_STARTT'		=> $AU_STARTT,
											'AU_ENDT'		=> $AU_ENDT,
											'AU_STAT'		=> $this->input->post('AU_STAT'),
											'AU_PROCS'		=> $AU_PROCS,
											'AU_CONFD'		=> $AU_CONFD,
											'AU_APPD'		=> $AU_APPD,
											'Patt_Number'	=> $this->input->post('Patt_Number'));
											
						$this->m_asset_usage->update($AU_CODE, $UpdAU);
						
						$this->m_asset_usage->deleteDetail($AU_CODE, $AU_PRJCODE);
				
					// UPDATE DETAIL
						foreach($_POST['data'] as $d)
						{
							$this->db->insert('tbl_asset_usagedet',$d);
						}
				}
			}			
			// START : UPDATE EXPENSE PRODUCTION -- MODIFY : Diupdate setelah prpses finish agar waktu dihitung setelah proses
				$this->m_asset_usage->updateEXP($AU_CODE, $AU_PRJCODE, $AP_HOPR, $AP_QTYOPR);
			// END : UPDATE EXPENSE PRODUCTION
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_asset_usage/index1/?id='.$this->url_encryption_helper->encode_url($AU_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallaur()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_asset/m_asset_usage', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Request List';
			$data['h3_title'] 			= 'asset usage';
			$data['PRJCODE'] 			= $PRJCODE;
			
			$data['recordcountAllAUR'] = $this->m_asset_usage->count_all_num_rowsAllAUR($PRJCODE);
			$data['viewAllAUR'] 		= $this->m_asset_usage->viewAllIAUR($PRJCODE)->result();
					
			$this->load->view('v_asset/v_asset_usage/asset_usage_selectiaur', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function inbox($offset=0) // OK
	{
		$this->load->model('m_asset/m_asset_usage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project List';
			$data['h3_title']			= 'asset usage inbox';
			
			$num_rows 					= $this->m_asset_usage->count_all_project_inb();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_asset_usage->get_last_ten_project_inb()->result();
			
			$this->load->view('v_asset/v_asset_usage/inb_listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function inbox_usagelist()
	{
		$this->load->model('m_asset/m_asset_usage', '', TRUE);
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
			$data['h2_title']			= 'Asset Usage';
			$data['h3_title']			= 'asset management';
			$linkBack					= site_url('c_asset/c_asset_usage/inbox/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 				= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE']			= $PRJCODE;
			
			$num_rows 					= $this->m_asset_usage->count_all_num_rows_inb($PRJCODE);
			$data["recordcount"] 		= $num_rows;
	 
			$data['vAssetUsage']		= $this->m_asset_usage->get_last_ten_AU_inb($PRJCODE)->result();
			
			$this->load->view('v_asset/v_asset_usage/inb_asset_usage', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function inbox_update() // OK
	{	
		$this->load->model('m_asset/m_asset_usage', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$AU_CODE	= $_GET['id'];
			$AU_CODE	= $this->url_encryption_helper->decode_url($AU_CODE);
			
			$getAU 					= $this->m_asset_usage->get_AU($AU_CODE)->row();
			
			$PRJCODE				= $getAU->AU_PRJCODE;
			
			$data['default']['AU_CODE'] 		= $getAU->AU_CODE;
			$data['default']['AUR_CODE'] 		= $getAU->AUR_CODE;
			$data['default']['AU_JOBCODE'] 		= $getAU->AU_JOBCODE;
			$data['default']['AU_AS_CODE'] 		= $getAU->AU_AS_CODE;
			$data['default']['AU_DATE'] 		= $getAU->AU_DATE;
			$data['default']['AU_PRJCODE'] 		= $getAU->AU_PRJCODE;
			$data['default']['AU_DESC'] 		= $getAU->AU_DESC;
			$data['default']['AU_STARTD'] 		= $getAU->AU_STARTD;
			$data['default']['AU_ENDD'] 		= $getAU->AU_ENDD;
			$data['default']['AU_STARTT'] 		= $getAU->AU_STARTT;
			$data['default']['AU_ENDT'] 		= $getAU->AU_ENDT;
			$data['default']['AU_STAT'] 		= $getAU->AU_STAT;
			$data['default']['AU_CONFD'] 		= $getAU->AU_CONFD;
			$data['default']['AU_APPD'] 		= $getAU->AU_APPD;
			$data['default']['Patt_Number'] 	= $getAU->Patt_Number;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Asset Usage';
			$data['h3_title']		= 'approve';
			$data['form_action']	= site_url('c_asset/c_asset_usage/inbox_update_process');
			$linkBack				= site_url('c_asset/c_asset_usage/inbox_usagelist/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$this->load->view('v_asset/v_asset_usage/inb_asset_usage_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function inbox_update_process()
	{
		$this->load->model('m_asset/m_asset_usage', '', TRUE);
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
			
			$AU_CODE	= $this->input->post('AU_CODE');
			$AU_PRJCODE = $this->input->post('AU_PRJCODE');
			$AU_STAT	= $this->input->post('AU_STAT');
			$AUR_CODE	= $this->input->post('AUR_CODE');
			$AU_DATE	= $this->input->post('AU_DATE');
			
			date_default_timezone_set("Asia/Jakarta");
			$AU_CONFD	= '';
			$AU_APPD	= '';
			if($AU_STAT == 2)
			{				
				$AU_CONFD 	= date('Y-m-d H:i:s');
			}
			else if($AU_STAT == 3)
			{
				$AU_APPD 	= date('Y-m-d H:i:s');
			}
			
			$UpdAU 		= array('AU_CODE' 		=> $this->input->post('AU_CODE'),
								'AU_STAT'		=> $this->input->post('AU_STAT'),
								'AU_APPD'		=> $AU_APPD);
								
			$this->m_asset_usage->update($AU_CODE, $UpdAU);
			
				if($AU_STAT == 3)
				{
					$JournalH_Code	= $AU_CODE;
					
					// Start : Pembuatan Journal Header
					$parameters = array('JournalH_Code' 	=> $AU_CODE,
									'JournalType'			=> 'AU',
									'JournalH_Date' 		=> $AU_APPD,
									'Source'				=> $AUR_CODE,
									'Emp_ID'				=> $DefEmp_ID,
									'LastUpdate'			=> $AU_APPD,	
									'KursAmount_tobase'		=> 1,
									'Reference_Number'		=> '',
									'proj_Code'				=> $AU_PRJCODE);
					$this->m_journal->createJournalH($JournalH_Code, $parameters);
					// End : Pembuatan Journal Header
					
					foreach($_POST['data'] as $d)
					{
						$JournalH_Code	= $JournalH_Code;
						$AU_PRJCODE 	= $d['AU_PRJCODE'];
						$AU_CODE 		= $d['AU_CODE'];
						$ITM_CODE 		= $d['ITM_CODE'];
						$ITM_UNIT 		= $d['ITM_UNIT'];
						$ITM_QTY 		= $d['ITM_QTY'];
						$ITM_PRICE 		= $d['ITM_PRICE'];
						$ITM_TOTAL 		= $d['ITM_TOTAL'];
						
						// Update Qty RR for Project Plan per Item Per Project
						$parameters = array(
							'JournalH_Code'		=> $JournalH_Code,
							'JSource'			=> 'AU',
							'proj_Code'			=> $AU_PRJCODE,
							'Transaction_Date'	=> $AU_DATE,
							'Item_Code' 		=> $ITM_CODE,
							'Qty_Plus'			=> $ITM_QTY,
							'Item_Price'		=> $ITM_PRICE
						);
						$this->m_journal->createJournalD($parameters);
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
			
			$url			= site_url('c_asset/c_asset_usage/inbox_usagelist/?id='.$this->url_encryption_helper->encode_url($AU_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}