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
			redirect('__l1y');
		}
	}
	
	function index1()
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
					
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Asset Usage';
			$data['h3_title']		= 'asset management';
			$data['secAddURL'] 		= site_url('c_asset/c_asset_usage/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 		= site_url('c_asset/c_asset_usage/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			$linkBack				= site_url('c_asset/c_asset_usage/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
			//$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= $linkBack;
			$data['PRJCODE']		= $PRJCODE;
			
			$num_rows 				= $this->m_asset_usage->count_all_num_rows($PRJCODE);
			$data["recordcount"] 	= $num_rows;
			$data["MenuCode"] 		= 'MN065';
			$data['vAssetUsage']	= $this->m_asset_usage->get_last_ten_AU($PRJCODE)->result();
			
			$this->load->view('v_asset/v_asset_usage/asset_usage', $data);
		}
		else
		{
			redirect('__l1y');
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
			
			$data['title'] 			= $appName;
			
			$docPatternPosition		= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Asset Usage';
			$data['h3_title']		= 'asset management';
			$data['form_action']	= site_url('c_asset/c_asset_usage/add_process');
			$linkBack				= site_url('c_asset/c_asset_usage/index1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= $linkBack;
			$data['PRJCODE']		= $PRJCODE;	
			$MenuCode 				= 'MN065';
			$data["MenuCode"] 		= 'MN065';
			$data['viewDocPattern'] = $this->m_asset_usage->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_asset/v_asset_usage/asset_usage_form', $data);
		}
		else
		{
			redirect('__l1y');
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
			redirect('__l1y');
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
			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Asset List';
			$data['h3_title'] 	= 'asset';
			$data['PRJCODE'] 	= $PRJCODE;
			$data['StartDate'] 	= $StartDate;
			$data['EndDate'] 	= $EndDate;
			
			$data['reCountAllAsset'] 	= $this->m_asset_usage->count_all_num_rowsAllAsset($PRJCODE, $StartDate, $EndDate);
			$data['viewAllAsset'] 		= $this->m_asset_usage->viewAllIAsset($PRJCODE, $StartDate, $EndDate)->result();
					
			$this->load->view('v_asset/v_asset_usage/asset_usage_selectasset', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_asset/m_asset_usage', '', TRUE);
		$this->load->model('m_journal/m_journal', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		
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
			$AU_CONFD		= "0000-00-00";
			$AU_APPD		= "0000-00-00";
			
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
			
			$AS_LASTSTAT	= 1;
			$AU_PROCS		= 0; // Open
			
			if($AU_STAT == 1)
			{
				$AS_LASTSTAT= 1;
				
				$AU_CONFD 	= date('Y-m-d H:i:s');
				$AU_PROCS	= 0;
			}
			elseif($AU_STAT == 2)
			{
				$AS_LASTSTAT= 2;
				
				$AU_CONFD 	= date('Y-m-d H:i:s');
				$AU_PROCS	= 0; // Open
			}
			else if($AU_STAT == 3)
			{
				$AS_LASTSTAT= 3;
				
				$AU_APPD 	= date('Y-m-d H:i:s');
				$AU_PROCS	= 1;
			}
			
			// ADD HEADER
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
			
			// DETAIL
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
			}
			
			// UPDATE LAST POSITION AND LAST JOB ASSET
				$AS_CODE		= $this->input->post('AU_AS_CODE');		// ASSET CODE
				$AS_LASTPOS		= $AU_PRJCODE;							// LAST POSITION
				$AS_LASTJOB		= $this->input->post('AU_JOBCODE');		// LAST JOB
				$UpdAS			= array('AS_LASTPOS'	=> $AS_LASTPOS,
										'AS_LASTJOB'	=> $AS_LASTJOB,
										'AS_LASTSTAT'	=> $AS_LASTSTAT);
				$this->m_asset_usage->updateAST($AS_CODE, $UpdAS);
			
			// START : UPDATE EXPENSE PRODUCTION -- MODIFY : Diupdate setelah prpses finish agar waktu dihitung setelah proses
				//$this->m_asset_usage->updateEXP($AU_CODE, $AU_PRJCODE, $AP_HOPR, $AP_QTYOPR);
			// END : UPDATE EXPENSE PRODUCTION
			
			// -- IMPORTANT ... !!!!
			// -- IMPORTANT ... !!!!
			// -- IMPORTANT ... !!!!
			// -- IMPORTANT ... !!!!
			// -- IMPORTANT ... !!!! HIDDEN JIKA TIDAK DIPERLUKAN
			// TAMBAHKAN PROSEDUR UNTUK AUTO FINISH DENGAN CATATAN HANYA UNTUK POSISI APPROVE
			if($AU_STAT == 3)
			{									
				// START : JOURNAL HEADER		
					$AUR_CODE		= "DIR";				
					$JournalH_Code	= $AU_CODE;
					$JournalType	= "AU";
					$JournalH_Date	= $AU_APPD;
					$Company_ID		= $comp_init;
					$DOCSource		= $AUR_CODE;
					$LastUpdate		= $AU_APPD;
					$WH_CODE		= $AU_PRJCODE;
					$Refer_Number	= "";
					$RefType		= 'AU';
					$PRJCODE		= $AU_PRJCODE;
					
					$parameters = array('JournalH_Code' 	=> $JournalH_Code,
										'JournalType'		=> $JournalType,
										'JournalH_Date' 	=> $JournalH_Date,
										'Company_ID' 		=> $Company_ID,
										'Source'			=> $DOCSource,
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $LastUpdate,	
										'KursAmount_tobase'	=> 1,
										'WHCODE'			=> $WH_CODE,
										'Reference_Number'	=> $Refer_Number,
										'RefType'			=> $RefType,
										'PRJCODE'			=> $PRJCODE);
					$this->m_journal->createJournalH($JournalH_Code, $parameters);
				// End : Pembuatan Journal Header
				
				// Start : Pembuatan Journal Detail
					$ITM_COSTTOT	= 0;
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
						$ITM_COST		= $ITM_QTY * $ITM_PRICE;
						$ITM_COSTTOT	= $ITM_COSTTOT + $ITM_COST;
						
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
				// End : Pembuatan Journal Detail
				
				// Start : PEMBUATAN TABEL JOB REPORT DAN PRODCUTION
					$AS_CODE	= $this->input->post('AU_AS_CODE');
					$AS_NAME	= '';
						$sqlAST	= "SELECT AS_NAME FROM tbl_asset_list WHERE AS_CODE = '$AS_CODE' LIMIT 1";
						$resAST	= $this->db->query($sqlAST)->result();
						foreach($resAST as $rowsAST) :
							$AS_NAME = $rowsAST->AS_NAME;
						endforeach;
						
					$AU_STARTDT	= date('Y-m-d',strtotime($this->input->post('AU_STARTD')));
					$AU_ENDDT	= date('Y-m-d',strtotime($this->input->post('AU_STARTT')));
					$AU_JOBCODE	= $this->input->post('AU_JOBCODE');
					$JL_NAME	= '';
						$sqlJL 		= "SELECT JL_NAME FROM tbl_asset_joblist WHERE JL_CODE = '$AU_JOBCODE' LIMIT 1";
						$resJL 		= $this->db->query($sqlJL)->result();
						foreach($resJL as $rowsJL) :
							$JL_NAME = $rowsJL->JL_NAME;
						endforeach;
					$AU_DESC	= $this->input->post('AU_DESC');
					
					$PRJNAME	= '';
						$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$AU_PRJCODE' LIMIT 1";
						$resPRJ		= $this->db->query($sqlPRJ)->result();
						foreach($resPRJ as $rowsPRJ) :
							$PRJNAME = $rowsPRJ->PRJNAME;
						endforeach;
					$VOL_AVG	= $AP_QTYOPR / $AP_HOPR;		// VOL PRODUCTION AVG PER HOURS
					$COST_AVGH	= $ITM_COSTTOT / $AP_HOPR;		// COST PRODUCTION AVG PER HOURS
					$COST_AVGV	= $ITM_COSTTOT / $AP_QTYOPR;	// COST PRODUCTION AVG PER VOLUME OPERATION
					
					$InsReport 	= array('RASTC_CODE' 	=> $AU_CODE,
										'RASTC_DATE'	=> $AU_DATE,
										'RASTC_PRJCODE'	=> $AU_PRJCODE,
										'RASTC_PRJNAME'	=> $PRJNAME,
										'RASTC_ASTCODE'	=> $AS_CODE,
										'RASTC_ASTDESC'	=> $AS_NAME,
										'RASTC_STARTD' 	=> $AU_STARTD,
										'RASTC_ENDD'	=> $AU_ENDD,
										'RASTC_QTYTIME'	=> $AP_HOPR,
										'RASTC_TYPE'	=> 'U',
										'RASTC_JOBC'	=> $AU_JOBCODE,	
										'RASTC_JOBD'	=> $JL_NAME,
										'RASTC_VOL'		=> $AP_QTYOPR,
										'RASTC_VOLAVG'	=> $VOL_AVG,
										'RASTC_UNIT'	=> $AP_QTYUNIT,
										'RASTC_COSTTOT'	=> $ITM_COSTTOT,
										'RASTC_COSTAVGH'=> $COST_AVGH,
										'RASTC_COSTAVGV'=> $COST_AVGV,
										'RASTC_NOTE'	=> $AU_DESC);
					$this->m_asset_usage->createJobReport($InsReport);
				// End : PEMBUATAN TABEL JOB REPORT DAN PRODCUTION
				
				$AU_APPD 	= date('Y-m-d H:i:s');
				$AS_LASTSTAT= 3;
				$AU_PROCS	= 2;	// FINISH STAT
				
				//$AP_QTYUNIT	= $this->input->post('AP_QTYUNIT');
				$AU_PROCD	= date('Y-m-d', strtotime($this->input->post('AU_PROCD')));
				$AU_PROCT	= date('H:i:s', strtotime($this->input->post('AU_PROCT')));
				$UpdAU 		= array('AU_PROCS'		=> $AU_PROCS,
									'AP_HOPR'		=> $AP_HOPR,
									//'AP_QTYUNIT'	=> $AP_QTYUNIT,
									'AU_PROCD'		=> $AU_PROCD,
									'AU_PROCT'		=> $AU_PROCT);
				$this->m_asset_usage->update($AU_CODE, $UpdAU);
				
				$AS_LASTSTAT	= 0;	// LAST STAT OF ASSET - READY
				
				// UPDATE ASSET STATUS TO READY
				$AS_CODE		= $this->input->post('AU_AS_CODE');
				$AS_STAT		= 1;
				$UpdAS 			= array('AS_STAT'	=> $AS_STAT);
				$this->m_asset_usage->updateAST($AS_CODE, $UpdAS);
			}
			
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
			redirect('__l1y');
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
			$data["MenuCode"] 		= 'MN065';
			$linkBack				= site_url('c_asset/c_asset_usage/index1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= $linkBack;
			$this->load->view('v_asset/v_asset_usage/asset_usage_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_asset/m_asset_usage', '', TRUE);
		$this->load->model('m_journal/m_journal', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		
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
			$AU_CONFD		= "0000-00-00";
			$AU_APPD		= "0000-00-00";
			
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
			
			if($AU_STAT == 1)
			{
				$AS_LASTSTAT= 1;				
				$AU_CONFD 	= date('Y-m-d H:i:s');
				$AU_PROCS	= 0;
				$IS_PROCS	= 0;
			}
			elseif($AU_STAT == 2)
			{
				$AS_LASTSTAT= 2;				
				$AU_CONFD 	= date('Y-m-d H:i:s');
				$AU_PROCS	= 0; // Open
				$IS_PROCS	= 0;
			}
			else if($AU_STAT == 3)
			{
				$AS_LASTSTAT= 3;				
				$AU_APPD 	= date('Y-m-d H:i:s');
				$AU_PROCS	= 1;
				$IS_PROCS	= 1;
				if($AU_PROCS1 > 1)
					$AU_PROCS	= $AU_PROCS1;
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
		
			// UPDATE DETAIL
				$this->m_asset_usage->deleteDetail($AU_CODE, $AU_PRJCODE);
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
			}
			
			if($IS_PROCS > 0) // JIKA SUDAH APPROVE
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
				$AU_PROCD	= date('Y-m-d', strtotime($this->input->post('AU_PROCD')));
				$AU_PROCT	= date('H:i:s', strtotime($this->input->post('AU_PROCT')));
				$UpdAU 		= array('AU_PROCS'		=> $AU_PROCS,
									'AP_HOPR'		=> $AP_HOPR,
									'AP_QTYUNIT'	=> $AP_QTYUNIT,
									'AU_PROCD'		=> $AU_PROCD,
									'AU_PROCT'		=> $AU_PROCT);
				$this->m_asset_usage->update($AU_CODE, $UpdAU);
				
				if($AU_PROCS == 2) // IF ASSET USAGE IS FINISHED : PROCESS
				{
					$AS_LASTSTAT	= 0;	// LAST STAT OF ASSET - READY
					
					// UPDATE ASSET STATUS TO READY
						$AS_CODE		= $this->input->post('AU_AS_CODE');
						$AS_STAT		= 1;
						$UpdAS 			= array('AS_STAT'	=> $AS_STAT);
						$this->m_asset_usage->updateAST($AS_CODE, $UpdAS);
						
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
					$AS_LASTSTAT	= 0;	// LAST STAT OF ASSET - READY
					
					// UPDATE ASSET STATUS TO READY
						$AS_CODE		= $this->input->post('AU_AS_CODE');
						$AS_STAT		= 1;
						$AS_LASTSTAT	= 0;		
						$UpdAS 			= array('AS_STAT'	=> $AS_STAT);
						$this->m_asset_usage->updateAST($AS_CODE, $UpdAS);
						
						$JournalH_Code	= $AU_CODE;
						
						$AUR_CODE		= "DIR";
						$JournalType	= "VAU";
						
					// Start : Pembuatan Journal Header
						$AUR_CODE		= "DIR";				
						$JournalH_Code	= $AU_CODE;
						$JournalType	= "VAU";
						$JournalH_Date	= $AU_APPD;
						$Company_ID		= $comp_init;
						$DOCSource		= $AUR_CODE;
						$LastUpdate		= $AU_APPD;
						$WH_CODE		= $AU_PRJCODE;
						$Refer_Number	= "";
						$RefType		= 'AU';
						$PRJCODE		= $AU_PRJCODE;
						
						$parameters = array('JournalH_Code' 	=> $JournalH_Code,
											'JournalType'		=> $JournalType,
											'JournalH_Date' 	=> $JournalH_Date,
											'Company_ID' 		=> $Company_ID,
											'Source'			=> $DOCSource,
											'Emp_ID'			=> $DefEmp_ID,
											'LastUpdate'		=> $LastUpdate,	
											'KursAmount_tobase'	=> 1,
											'WHCODE'			=> $WH_CODE,
											'Reference_Number'	=> $Refer_Number,
											'RefType'			=> $RefType,
											'PRJCODE'			=> $PRJCODE);
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
					$AS_LASTSTAT	= 3;	// LAST STAT OF ASSET - APPROVE
					
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
			
			// START : UPDATE EXPENSE PRODUCTION -- MODIFY : Diupdate setelah proses finish agar waktu dihitung setelah proses
				$this->m_asset_usage->updateEXP($AU_CODE, $AU_PRJCODE, $AP_HOPR, $AP_QTYOPR);
			// END : UPDATE EXPENSE PRODUCTION
					
			// UPDATE LAST POSITION AND LAST JOB ASSET
				$AS_CODE		= $this->input->post('AU_AS_CODE');		// ASSET CODE
				$AS_LASTPOS		= $AU_PRJCODE;							// LAST POSITION
				$AS_LASTJOB		= $this->input->post('AU_JOBCODE');		// LAST JOB
				$UpdAS			= array('AS_LASTPOS'	=> $AS_LASTPOS,
										'AS_LASTJOB'	=> $AS_LASTJOB,
										'AS_LASTSTAT'	=> $AS_LASTSTAT);
				$this->m_asset_usage->updateAST($AS_CODE, $UpdAS);				
			
			// -- IMPORTAN ... !!!!
			// -- IMPORTAN ... !!!!
			// -- IMPORTAN ... !!!!
			// -- IMPORTAN ... !!!!
			// -- IMPORTAN ... !!!! HIDDEN JIKA TIDAK DIPERLUKAN
			// TAMBAHKAN PROSEDUR UNTUK AUTO FINISH DENGAN CATATAN HANYA UNTUK POSISI APPROVE
			if($AU_STAT == 3)
			{
				// START : CEK JOURNAL, JIKA SUDAH ADA MAKA DELETE						
					$JournalH_Code	= $AU_CODE;
					$AUR_CODE		= "DIR";
					$JournalType	= "AU";			
					$sqlCJ			= "tbl_journalheader WHERE JournalH_Code = '$JournalH_Code' AND JournalType = '$JournalType'";
					$resCJ			= $this->db->count_all($sqlCJ);
					
					if($resCJ > 0)
					{
						$this->m_asset_usage->deleteJH($JournalH_Code); 				// DELETE JOURNAL HEADER
						$this->m_asset_usage->deleteJD($JournalH_Code, $JournalType); 	// DELETE JOURNAL DETAIL
					}
				// END : CEK JOURNAL, JIKA SUDAH ADA MAKA DELETE	
				
				$AU_JOBCODE	= $this->input->post('AU_JOBCODE');
				// Start : Pembuatan Journal Header
					$this->load->model('m_journal/m_journal', '', TRUE);
					
					$JournalH_Code	= $AU_CODE;
					$JournalType	= 'AU';
					$JournalH_Date	= $AU_APPD;
					$Company_ID		= 'NKE';
					$DOCSource		= $AU_CODE;
					$LastUpdate		= $AU_APPD;
					$WH_CODE		= $AU_PRJCODE;
					$Refer_Number	= '';
					$RefType		= 'AU';
					$PRJCODE		= $AU_PRJCODE;
					
					$parameters = array('JournalH_Code' 	=> $JournalH_Code,
										'JournalType'		=> $JournalType,
										'JournalH_Date' 	=> $JournalH_Date,
										'Company_ID' 		=> $Company_ID,
										'Source'			=> $DOCSource,
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $LastUpdate,	
										'KursAmount_tobase'	=> 1,
										'WHCODE'			=> $WH_CODE,
										'Reference_Number'	=> $Refer_Number,
										'RefType'			=> $RefType,
										'PRJCODE'			=> $PRJCODE);
					$this->m_journal->createJournalH($JournalH_Code, $parameters);
				// End : Pembuatan Journal Header
				
				// Start : Pembuatan Journal Detail
					$ITM_COSTTOT	= 0;
					foreach($_POST['data'] as $d)
					{
						/*$JournalH_Code	= $JournalH_Code;
						$AU_PRJCODE 	= $d['AU_PRJCODE'];
						$AU_CODE 		= $d['AU_CODE'];
						$ITM_CODE 		= $d['ITM_CODE'];
						$ITM_UNIT 		= $d['ITM_UNIT'];
						$ITM_QTY_P 		= $d['ITM_QTY_P'];
						$ITM_QTY 		= $d['ITM_QTY'];
						$ITM_PRICE 		= $d['ITM_PRICE'];
						$ITM_TOTAL 		= $d['ITM_TOTAL'];*/
						
						$ITM_CODE 		= $d['ITM_CODE'];
						
						$ITM_TYPE		= 0;
						$ACC_ID_UM		= '';
						$sqlITM1		= "SELECT ACC_ID_UM, ITM_GROUP, ISMTRL, ISRENT, ISPART, ISFUEL, ISLUBRIC, ISFASTM, ISWAGE
											FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
						$resITM1		= $this->db->query($sqlITM1)->result();
						foreach($resITM1 as $rowITM) :
							$ACC_ID_UM = $rowITM->ACC_ID_UM;
							$ITM_GROUP 	= $rowITM->ITM_GROUP;
							$ISMTRL 	= $rowITM->ISMTRL;
							$ISRENT 	= $rowITM->ISRENT;
							$ISPART 	= $rowITM->ISPART;
							$ISFUEL 	= $rowITM->ISFUEL;
							$ISLUBRIC 	= $rowITM->ISLUBRIC;
							$ISFASTM 	= $rowITM->ISFASTM;
							$ISWAGE 	= $rowITM->ISWAGE;
							if($ISMTRL == 1)
								$ITM_TYPE	= 1;
							elseif($ISRENT == 1)
								$ITM_TYPE	= 2;
							elseif($ISPART == 1)
								$ITM_TYPE	= 3;
							elseif($ISFUEL == 1)
								$ITM_TYPE	= 4;
							elseif($ISLUBRIC == 1)
								$ITM_TYPE	= 5;
							elseif($ISFASTM == 1)
								$ITM_TYPE	= 6;
							else
								$ITM_TYPE	= 1;
						endforeach;
						
						$JOBCODEID 		= $AU_JOBCODE;
						$ACC_ID 		= $ACC_ID_UM;
						$ITM_UNIT 		= $d['ITM_UNIT'];
						$ITM_QTY 		= $d['ITM_QTY'];
						$ITM_PRICE 		= $d['ITM_QTY_P'];
						$ITM_DISC 		= 0;
						$TAXCODE1 		= '';
						$TAXPRICE1 		= 0;
						
						$JournalH_Code	= $JournalH_Code;
						$JournalType	= 'AU';
						$JournalH_Date	= $AU_DATE;
						$Company_ID		= 'NKE';
						$Currency_ID	= 'IDR';
						$LastUpdate		= $AU_APPD;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= '';
						$RefType		= 'AU';
						$JSource		= 'AU';
						$PRJCODE		= $PRJCODE;
						
						$parameters = array('JournalH_Code' 	=> $JournalH_Code,
											'JournalType'		=> $JournalType,
											'JournalH_Date' 	=> $JournalH_Date,
											'Company_ID' 		=> $Company_ID,
											'Currency_ID' 		=> $Currency_ID,
											'Source'			=> $DOCSource,
											'Emp_ID'			=> $DefEmp_ID,
											'LastUpdate'		=> $LastUpdate,	
											'KursAmount_tobase'	=> 1,
											'WHCODE'			=> $WH_CODE,
											'Reference_Number'	=> $Refer_Number,
											'RefType'			=> $RefType,
											'PRJCODE'			=> $PRJCODE,
											'JSource'			=> $JSource,
											'TRANS_CATEG' 		=> 'UM',			// UM = Use Material
											'ITM_CODE' 			=> $ITM_CODE,
											'ACC_ID' 			=> $ACC_ID,
											'ITM_UNIT' 			=> $ITM_UNIT,
											'ITM_GROUP' 		=> $ITM_GROUP,
											'ITM_TYPE' 			=> $ITM_TYPE,
											'ITM_QTY' 			=> $ITM_QTY,
											'ITM_PRICE' 		=> $ITM_PRICE,
											'ITM_DISC' 			=> $ITM_DISC,
											'TAXCODE1' 			=> $TAXCODE1,
											'TAXPRICE1' 		=> $TAXPRICE1);
						$this->m_journal->createJournalD($JournalH_Code, $parameters);
						
						$PRJCODE 		= $AU_PRJCODE;
						$AU_CODE 		= $AU_CODE;
						$ITM_CODE 		= $d['ITM_CODE'];
						$ITM_QTY 		= $d['ITM_QTY'];
						$ITM_PRICE 		= $d['ITM_PRICE'];
						$ITM_KIND 		= $d['ITM_KIND'];
						$ITM_COST		= $ITM_QTY * $ITM_PRICE;
						$ITM_COSTTOT	= $ITM_COSTTOT + $ITM_COST;
						
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
				
				// Start : PEMBUATAN TABEL JOB REPORT DAN PRODCUTION
					$AS_CODE	= $this->input->post('AU_AS_CODE');
					$AS_NAME	= '';
						$sqlAST	= "SELECT AS_NAME FROM tbl_asset_list WHERE AS_CODE = '$AS_CODE' LIMIT 1";
						$resAST	= $this->db->query($sqlAST)->result();
						foreach($resAST as $rowsAST) :
							$AS_NAME = $rowsAST->AS_NAME;
						endforeach;
						
					$AU_STARTDT	= date('Y-m-d',strtotime($this->input->post('AU_STARTD')));
					$AU_ENDDT	= date('Y-m-d',strtotime($this->input->post('AU_STARTT')));
					$AU_JOBCODE	= $this->input->post('AU_JOBCODE');
					$JL_NAME	= '';
						$sqlJL 		= "SELECT JL_NAME FROM tbl_asset_joblist WHERE JL_CODE = '$AU_JOBCODE' LIMIT 1";
						$resJL 		= $this->db->query($sqlJL)->result();
						foreach($resJL as $rowsJL) :
							$JL_NAME = $rowsJL->JL_NAME;
						endforeach;
					$AU_DESC	= $this->input->post('AU_DESC');
					
					$PRJNAME	= '';
						$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$AU_PRJCODE' LIMIT 1";
						$resPRJ		= $this->db->query($sqlPRJ)->result();
						foreach($resPRJ as $rowsPRJ) :
							$PRJNAME = $rowsPRJ->PRJNAME;
						endforeach;
					$VOL_AVG	= $AP_QTYOPR / $AP_HOPR;		// VOL PRODUCTION AVG PER HOURS
					$COST_AVGH	= $ITM_COSTTOT / $AP_HOPR;		// COST PRODUCTION AVG PER HOURS
					$COST_AVGV	= $ITM_COSTTOT / $AP_QTYOPR;	// COST PRODUCTION AVG PER VOLUME OPERATION
					
					$InsReport 	= array('RASTC_CODE' 	=> $AU_CODE,
										'RASTC_DATE'	=> $AU_DATE,
										'RASTC_PRJCODE'	=> $AU_PRJCODE,
										'RASTC_PRJNAME'	=> $PRJNAME,
										'RASTC_ASTCODE'	=> $AS_CODE,
										'RASTC_ASTDESC'	=> $AS_NAME,
										'RASTC_STARTD' 	=> $AU_STARTD,
										'RASTC_ENDD'	=> $AU_ENDD,
										'RASTC_QTYTIME'	=> $AP_HOPR,
										'RASTC_TYPE'	=> 'U',
										'RASTC_JOBC'	=> $AU_JOBCODE,	
										'RASTC_JOBD'	=> $JL_NAME,
										'RASTC_VOL'		=> $AP_QTYOPR,
										'RASTC_VOLAVG'	=> $VOL_AVG,
										'RASTC_UNIT'	=> $AP_QTYUNIT,
										'RASTC_COSTTOT'	=> $ITM_COSTTOT,
										'RASTC_COSTAVGH'=> $COST_AVGH,
										'RASTC_COSTAVGV'=> $COST_AVGV,
										'RASTC_NOTE'	=> $AU_DESC);
					$this->m_asset_usage->createJobReport($InsReport);
				// End : PEMBUATAN TABEL JOB REPORT DAN PRODCUTION
				$AU_APPD 	= date('Y-m-d H:i:s');
				$AS_LASTSTAT= 3;
				$AU_PROCS	= 2;	// FINISH STAT
				
				//$AP_QTYUNIT	= $this->input->post('AP_QTYUNIT');
				$AU_PROCD	= date('Y-m-d', strtotime($this->input->post('AU_PROCD')));
				$AU_PROCT	= date('H:i:s', strtotime($this->input->post('AU_PROCT')));
				$UpdAU 		= array('AU_PROCS'		=> $AU_PROCS,
									'AP_HOPR'		=> $AP_HOPR,
									//'AP_QTYUNIT'	=> $AP_QTYUNIT,
									'AU_PROCD'		=> $AU_PROCD,
									'AU_PROCT'		=> $AU_PROCT);
				$this->m_asset_usage->update($AU_CODE, $UpdAU);
				
				$AS_LASTSTAT	= 0;	// LAST STAT OF ASSET - READY
				
				// UPDATE ASSET STATUS TO READY
				$AS_CODE		= $this->input->post('AU_AS_CODE');
				$AS_STAT		= 1;
				$UpdAS 			= array('AS_STAT'	=> $AS_STAT);
				$this->m_asset_usage->updateAST($AS_CODE, $UpdAS);
			}
			
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
			redirect('__l1y');
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
			redirect('__l1y');
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
			redirect('__l1y');
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
			redirect('__l1y');
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
			redirect('__l1y');
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
			$AU_CONFD	= "0000-00-00";
			$AU_APPD	= "0000-00-00";
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
			redirect('__l1y');
		}
	}
}