<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 April 2017
 * File Name	= C_453tu55493.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_453tu55493 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_asset/m_asset_usage', '', TRUE);
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
	
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_asset/c_453tu55493/prj_l15t4ll/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj_l15t4ll() // OK
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
				$data["h1_title"] 	= "Penggunaan Alat";
			}
			else
			{
				$data["h1_title"] 	= "Tools Usage";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN147';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();		
			$data["secURL"] 	= "c_asset/c_453tu55493/iN45537/?id=";
			
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
	
	function iN45537()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$LangID 		= $this->session->userdata['LangID'];
				
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
			$end		= 100;
		}
		else
		{
			$key		= '';
			$PRJCODE	= $EXP_COLLD1;
			$start		= 0;
			$end		= 50;
		}
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
	
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Penggunaan Alat';
				$data['h2_title']	= 'manajemen alat';
			}
			else
			{
				$data['h1_title']	= 'Tools Usage';
				$data['h2_title']	= 'tools management';
			}
			
			$data['secAddURL'] 		= site_url('c_asset/c_453tu55493/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['srch_url'] 		= site_url('c_asset/c_453tu55493/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			$linkBack				= site_url('c_asset/c_453tu55493/prj_l15t4ll/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $linkBack;
			$data['PRJCODE']		= $PRJCODE;
			
			$num_rows 				= $this->m_asset_usage->count_all_num_rows($PRJCODE);
			$data["recordcount"] 	= $num_rows;
			$data["MenuCode"] 		= 'MN065';
			$data['vAssetUsage']	= $this->m_asset_usage->get_last_ten_AU($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN065';
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
			
			$this->load->view('v_asset/v_asset_usage/asset_usage', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() // GOOD
	{		
		$PRJCODE		= $_GET['id'];
		
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
			
			$columns_valid 	= array("AU_ID",
									"AU_CODE", 
									"PR_DATE", 
									"JOBDESC", 
									"CREATERNM", 
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_asset_usage->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_asset_usage->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{		
				$AU_CODE 		= $dataI['AU_CODE'];
				$AU_JOBCODE		= $dataI['AU_JOBCODE'];
				$JL_NAME		= '';
				if($AU_JOBCODE = 'OTH')
				{
					$JOBDESC 	= "OTH";
				}
				else
				{
					$sqlJOB 		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID  = '$AU_JOBCODE'";
					$resultJOB 		= $this->db->query($sqlJOB)->result();
					foreach($resultJOB as $rowJOB) :
						$JOBDESC 	= $rowJOB->JOBDESC;
					endforeach;
				}
				$AU_AS_CODE	= $dataI['AU_AS_CODE'];
				$AS_NAME	= $dataI['AS_NAME'];
				$AU_DATE	= $dataI['AU_DATE'];
				$AU_DATEV	= date('d M Y', strtotime($AU_DATE));
				$PRJCODE	= $dataI['PRJCODE'];
				$AU_DESC	= $dataI['AU_DESC'];
				$AU_STARTD	= $dataI['AU_STARTD'];
				$AU_ENDD	= $dataI['AU_ENDD'];
				$AU_STAT	= $dataI['AU_STAT'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$AU_PROCS	= $dataI['AU_PROCS'];
				if($AU_PROCS == 0)
				{
					$AU_PROCSD	= 'Open';
				}
				elseif($AU_PROCS == 1 || $AU_PROCS == 2)
				{
					$AU_PROCSD	= 'Procesing';
				}
				elseif($AU_PROCS == 3)
				{
					$AU_PROCSD	= 'Finished';
				}
				elseif($AU_PROCS == 4)
				{
					$AU_PROCSD	= 'Canceled';
				}
				
				if($AU_PROCS == 0)		// DRAFT
				{
					$gbrPROC	= "<img src='" .base_url(). "assets/AdminLTE-2.0.5/dist/img/draft_icon.png' width=25 height=25 title='Draft'>";
				}
				elseif($AU_PROCS == 1)	// APPROVE -> LOCK
				{
					$gbrPROC	= "<img src='" .base_url(). "assets/AdminLTE-2.0.5/dist/img/lock_icon.png' width=25 height=25 title='Processing'>";
				}
				elseif($AU_PROCS == 2)	// ON PROGRESS
				{
					$gbrPROC	= "<img src='" .base_url(). "assets/AdminLTE-2.0.5/dist/img/process_icon2.png' width=25 height=25 title='Processing'>";
				}
				elseif($AU_PROCS == 3)	// FINISH
				{
						$gbrPROC	= "<img src='" .base_url(). "assets/AdminLTE-2.0.5/dist/img/finish_icon.png' width=25 height=25 title='Finish'>";
				}
				elseif($AU_PROCS == 4)	// CANCELED
				{
						$gbrPROC	= "<img src='" .base_url(). "assets/AdminLTE-2.0.5/dist/img/canceled_icon.png' width=25 height=25 title='Canceled'>";
				}
				
				$secUpd		= site_url('c_asset/c_453tu55493/update/?id='.$this->url_encryption_helper->encode_url($AU_CODE));				
				$secPrint	= site_url('c_asset/c_453tu55493/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($AU_CODE));
				$CollID		= "AU~$AU_CODE~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
                                    
				if($AU_STAT == 1) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}                                            
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				
				$output['data'][] = array("$noU.",
										  "<label style='white-space:nowrap'>".$dataI['AU_CODE']."</label>",
										  $AU_DATEV,
										  $AS_NAME,
										  $JOBDESC,
										  $AU_DESC,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $gbrPROC,
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
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
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Penggunaan Alat';
				$data['h2_title']	= 'manajemen alat';
			}
			else
			{
				$data['h1_title']	= 'Tools Usage';
				$data['h2_title']	= 'tools management';
			}
			
			$data['form_action']	= site_url('c_asset/c_453tu55493/add_process');
			$linkBack				= site_url('c_asset/c_453tu55493/iN45537/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= $linkBack;
			$data['PRJCODE']		= $PRJCODE;	
			$MenuCode 				= 'MN065';
			$data["MenuCode"] 		= 'MN065';
			$data['viewDocPattern'] = $this->m_asset_usage->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN065';
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
			
			$this->load->view('v_asset/v_asset_usage/asset_usage_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function g3T4Lli73m()
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
	
	function p04553t()
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
			$PRJCODE 		= $this->input->post('PRJCODE');
			$AU_STAT		= $this->input->post('AU_STAT');
			$AU_REFNO		= $this->input->post('AU_REFNO');
			
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
			
			// ADD HEADER
				$AP_QTYUNIT	= $this->input->post('AP_QTYUNIT');
				$AU_NEEDITM	= $this->input->post('AU_NEEDITM');
				$InsAU 		= array('AU_CODE' 		=> $this->input->post('AU_CODE'),
									'AUR_CODE'		=> $this->input->post('AUR_CODE'),
									'AU_JOBCODE'	=> $this->input->post('AU_JOBCODE'),
									'AU_AS_CODE'	=> $this->input->post('AU_AS_CODE'),
									'AU_DATE'		=> date('Y-m-d',strtotime($this->input->post('AU_DATE'))),
									'PRJCODE'		=> $this->input->post('PRJCODE'),
									'AU_DESC'		=> $this->input->post('AU_DESC'),
									'AU_NEEDITM'	=> $this->input->post('AU_NEEDITM'),
									'AU_STARTD'		=> $AU_STARTD,
									'AU_ENDD'		=> $AU_ENDD,
									'AU_STARTT'		=> $AU_STARTT,
									'AU_ENDT'		=> $AU_ENDT,
									'AP_HOPR'		=> $AP_HOPR,
									'AU_STAT'		=> $this->input->post('AU_STAT'),
									'AU_PROCS'		=> $AU_PROCS,
									'AU_CONFD'		=> $AU_CONFD,
									'AU_APPD'		=> $AU_APPD,
									'AU_REFNO'		=> $AU_REFNO,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
				$this->m_asset_usage->add($InsAU);
			
			if($AU_NEEDITM == 1)
			{
				foreach($_POST['data'] as $d)
				{
					$this->db->insert('tbl_asset_usagedet',$d);
				}
			}
			
			// UPDATE LAST POSITION AND LAST JOB ASSET
				$AS_CODE		= $this->input->post('AU_AS_CODE');		// ASSET CODE
				$AS_LASTPOS		= $PRJCODE;								// LAST POSITION
				$AS_LASTJOB		= $this->input->post('AU_JOBCODE');		// LAST JOB
				$UpdAS			= array('AS_LASTPOS'	=> $AS_LASTPOS,
										'AS_LASTJOB'	=> $AS_LASTJOB,
										'AS_LASTSTAT'	=> $AS_LASTSTAT);
				$this->m_asset_usage->updateAST($AS_CODE, $UpdAS);
			
			// START : UPDATE EXPENSE PRODUCTION -- MODIFY : Diupdate setelah prpses finish agar waktu dihitung setelah proses
				//$this->m_asset_usage->updateEXP($AU_CODE, $PRJCODE, $AP_HOPR, $AP_QTYOPR);
			// END : UPDATE EXPENSE PRODUCTION
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "AU_CODE",
										'DOC_CODE' 		=> $AU_CODE,
										'DOC_STAT' 		=> $AU_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_asset_usage");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AU_CODE;
				$MenuCode 		= 'MN065';
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
			
			$url			= site_url('c_asset/c_453tu55493/iN45537/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
			
			$PRJCODE				= $getAU->PRJCODE;
			
			$data['default']['AU_CODE'] 		= $getAU->AU_CODE;
			$data['default']['AUR_CODE'] 		= $getAU->AUR_CODE;
			$data['default']['AU_JOBCODE'] 		= $getAU->AU_JOBCODE;
			$data['default']['AU_AS_CODE'] 		= $getAU->AU_AS_CODE;
			$data['default']['AU_DATE'] 		= $getAU->AU_DATE;
			$data['default']['PRJCODE'] 		= $getAU->PRJCODE;
			$data['default']['AU_DESC'] 		= $getAU->AU_DESC;
			$data['default']['AU_STARTD'] 		= $getAU->AU_STARTD;
			$data['default']['AU_ENDD'] 		= $getAU->AU_ENDD;
			$data['default']['AU_STARTT'] 		= $getAU->AU_STARTT;
			$data['default']['AU_ENDT'] 		= $getAU->AU_ENDT;
			$data['default']['AP_QTYOPR'] 		= $getAU->AP_QTYOPR;
			$data['default']['AP_QTYUNIT'] 		= $getAU->AP_QTYUNIT;
			$data['default']['AU_STAT'] 		= $getAU->AU_STAT;
			$data['default']['AU_NEEDITM'] 		= $getAU->AU_NEEDITM;
			$data['default']['AU_PROCS'] 		= $getAU->AU_PROCS;
			$data['default']['AU_CONFD'] 		= $getAU->AU_CONFD;
			$data['default']['AU_APPD'] 		= $getAU->AU_APPD;
			$data['default']['AU_PROCD'] 		= $getAU->AU_PROCD;
			$data['default']['AU_PROCT'] 		= $getAU->AU_PROCT;
			$data['default']['AU_REFNO'] 		= $getAU->AU_REFNO;
			$data['default']['Patt_Number'] 	= $getAU->Patt_Number;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Penggunaan Alat';
				$data['h2_title']	= 'manajemen alat';
			}
			else
			{
				$data['h1_title']	= 'Tools Usage';
				$data['h2_title']	= 'tools management';
			}
			
			$data['form_action']	= site_url('c_asset/c_453tu55493/update_process');
			$data["MenuCode"] 		= 'MN065';
			$linkBack				= site_url('c_asset/c_453tu55493/iN45537/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= $linkBack;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AU_CODE;
				$MenuCode 		= 'MN065';
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
			$PRJCODE 		= $this->input->post('PRJCODE');
			$AU_REFNO		= $this->input->post('AU_REFNO');
			$AU_STAT		= $this->input->post('AU_STAT');
			$STAT_BEFORE	= $this->input->post('STAT_BEFORE');
			
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
			$AS_CODE	= $this->input->post('AU_AS_CODE');
			
			$AU_PROCS	= 0;
			$AS_LASTSTAT= 1;
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
			if($AU_STAT == 9)
			{
				$AS_LASTSTAT= 9;				
				$AU_CONFD 	= date('Y-m-d H:i:s');
				$AU_PROCS	= 0; // Open
				$IS_PROCS	= 0;				
			}
			
			$AU_NEEDITM	= $this->input->post('AU_NEEDITM');
			
			// UPDATE HEADER
				$UpdAU 		= array('AU_CODE' 		=> $this->input->post('AU_CODE'),
									'AUR_CODE'		=> $this->input->post('AUR_CODE'),
									'AU_JOBCODE'	=> $this->input->post('AU_JOBCODE'),
									'AU_AS_CODE'	=> $this->input->post('AU_AS_CODE'),
									'AU_DATE'		=> date('Y-m-d',strtotime($this->input->post('AU_DATE'))),
									'PRJCODE'		=> $this->input->post('PRJCODE'),
									'AU_DESC'		=> $this->input->post('AU_DESC'),
									'AU_STARTD'		=> $AU_STARTD,
									'AU_ENDD'		=> $AU_ENDD,
									'AU_STARTT'		=> $AU_STARTT,
									'AU_ENDT'		=> $AU_ENDT,
									'AU_STAT'		=> $AU_STAT,
									'AU_PROCS'		=> $AU_PROCS,
									'AU_CONFD'		=> $AU_CONFD,
									'AU_APPD'		=> $AU_APPD,
									'AU_REFNO'		=> $AU_REFNO,
									'Patt_Number'	=> $this->input->post('Patt_Number'));	
				$this->m_asset_usage->update($AU_CODE, $UpdAU);
				
			if($AU_NEEDITM == 1)
			{
				$this->m_asset_usage->deleteDetail($AU_CODE, $PRJCODE);
				foreach($_POST['data'] as $d)
				{
					$this->db->insert('tbl_asset_usagedet',$d);
				}
			}	
			
			// UPDATE LAST POSITION AND LAST JOB ASSET
				$AS_CODE		= $this->input->post('AU_AS_CODE');		// ASSET CODE
				$AS_LASTPOS		= $PRJCODE;								// LAST POSITION
				$AS_LASTJOB		= $this->input->post('AU_JOBCODE');		// LAST JOB
				$UpdAS			= array('AS_LASTPOS'	=> $AS_LASTPOS,
										'AS_LASTJOB'	=> $AS_LASTJOB,
										'AS_LASTSTAT'	=> $AS_LASTSTAT);
				$this->m_asset_usage->updateAST($AS_CODE, $UpdAS);			
				
			if($STAT_BEFORE > 0) // JIKA STATUS = 3
			{
				$AU_PROCS		= $this->input->post('AU_PROCS'); // 0.New/Conf, 1.App. 2.Process, 3.Finish, 4.Canc
				
				if($AU_PROCS == 3) // IF ASSET USAGE IS FINISHED : PROCESS
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
						$AP_QTYOPR		= $this->input->post('AP_QTYOPR');	// VOLUME PRODUKSI
					// END : TIME ASSET PRODUCTION
				
					$AP_QTYUNIT	= $this->input->post('AP_QTYUNIT');			// UNIT PRODUKSI
					$AU_PROCD	= date('Y-m-d', strtotime($this->input->post('AU_PROCD')));
					$AU_PROCT	= date('H:i:s', strtotime($this->input->post('AU_PROCT')));
					$UpdAU 		= array('AU_PROCS'		=> $AU_PROCS,
										'AP_HOPR'		=> $AP_HOPR,
										'AP_QTYOPR'		=> $AP_QTYOPR,
										'AP_QTYUNIT'	=> $AP_QTYUNIT,
										'AU_PROCD'		=> $AU_PROCD,
										'AU_PROCT'		=> $AU_PROCT);
					$this->m_asset_usage->update($AU_CODE, $UpdAU);
					
					// UPDATE ASSET STATUS TO READY
						$AS_CODE		= $this->input->post('AU_AS_CODE');
						$AS_STAT		= 1;
						$UpdAS 			= array('AS_STAT'	=> $AS_STAT);
						$this->m_asset_usage->updateAST($AS_CODE, $UpdAS);
				}
				elseif($AU_PROCS == 4) // IF ASSET USAGE IS CANCELED. SO, CREATE VOID JOURNAL & THE ASSET MUST BE ENABLED/READY
				{
					$AS_LASTSTAT	= 0;	// LAST STAT OF ASSET - READY
					$AU_STAT		= 9;
					// UPDATE ASSET STATUS TO READY
						$AS_CODE		= $this->input->post('AU_AS_CODE');
						$AS_STAT		= 1;
						$AS_LASTSTAT	= 0;		
						$UpdAS 			= array('AS_STAT'	=> $AS_STAT);
						//$this->m_asset_usage->updateAST($AS_CODE, $UpdAS); // TIDAK PERLU ADA PERUBAHAN STATUS ASSET 190925
						
					// UPDATE HEADER
						$UpdAU 		= array('AU_PROCS'		=> $AU_PROCS);									
						$this->m_asset_usage->update($AU_CODE, $UpdAU);
				}
			}
			
			// START : UPDATE EXPENSE PRODUCTION -- MODIFY : Diupdate setelah proses finish agar waktu dihitung setelah proses
				//$this->m_asset_usage->updateEXP($AU_CODE, $PRJCODE, $AP_HOPR, $AP_QTYOPR);
			// END : UPDATE EXPENSE PRODUCTION
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "AU_CODE",
										'DOC_CODE' 		=> $AU_CODE,
										'DOC_STAT' 		=> $AU_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_asset_usage");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AU_CODE;
				$MenuCode 		= 'MN065';
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
			
			$url			= site_url('c_asset/c_453tu55493/iN45537/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
	
 	function inbox() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_asset/c_453tu55493/pR7_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function pR7_l5t_x1() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan Penggunaan Alat";
			}
			else
			{
				$data["h1_title"] 	= "Tools Usage Approval";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_asset/c_453tu55493/iN20_x1/?id=";
			
			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function iN20_x1()
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
					
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Pers. Penggunaan Alat";
			}
			else
			{
				$data["h1_title"] 	= "Tools Usage Approval";
			}
			
			$linkBack					= site_url('c_asset/c_453tu55493/inbox/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 				= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE']			= $PRJCODE;
			
			$num_rows 					= $this->m_asset_usage->count_all_num_rows_inb($PRJCODE);
			$data["recordcount"] 		= $num_rows;
	 
			$data['vAssetUsage']		= $this->m_asset_usage->get_last_ten_AU_inb($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN066';
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
			
			$this->load->view('v_asset/v_asset_usage/inb_asset_usage', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData_1n2() // GOOD
	{		
		$PRJCODE		= $_GET['id'];
		
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
			
			$columns_valid 	= array("AU_ID",
									"AU_CODE", 
									"PR_DATE", 
									"JOBDESC", 
									"CREATERNM", 
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_asset_usage->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_asset_usage->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{		
				$AU_CODE 		= $dataI['AU_CODE'];
				$AU_JOBCODE		= $dataI['AU_JOBCODE'];
				$JL_NAME		= '';
				if($AU_JOBCODE = 'OTH')
				{
					$JOBDESC 	= "OTH";
				}
				else
				{
					$sqlJOB 		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID  = '$AU_JOBCODE'";
					$resultJOB 		= $this->db->query($sqlJOB)->result();
					foreach($resultJOB as $rowJOB) :
						$JOBDESC 	= $rowJOB->JOBDESC;
					endforeach;
				}
				$AU_AS_CODE	= $dataI['AU_AS_CODE'];
				$AS_NAME	= $dataI['AS_NAME'];
				$AU_DATE	= $dataI['AU_DATE'];
				$AU_DATEV	= date('d M Y', strtotime($AU_DATE));
				$PRJCODE	= $dataI['PRJCODE'];
				$AU_DESC	= $dataI['AU_DESC'];
				$AU_STARTD	= $dataI['AU_STARTD'];
				$AU_ENDD	= $dataI['AU_ENDD'];
				$AU_STAT	= $dataI['AU_STAT'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$AU_PROCS	= $dataI['AU_PROCS'];
				if($AU_PROCS == 0)
				{
					$AU_PROCSD	= 'Open';
				}
				elseif($AU_PROCS == 1 || $AU_PROCS == 2)
				{
					$AU_PROCSD	= 'Procesing';
				}
				elseif($AU_PROCS == 3)
				{
					$AU_PROCSD	= 'Finished';
				}
				elseif($AU_PROCS == 4)
				{
					$AU_PROCSD	= 'Canceled';
				}
				
				if($AU_PROCS == 0)		// DRAFT
				{
					$gbrPROC	= "<img src='" .base_url(). "assets/AdminLTE-2.0.5/dist/img/draft_icon.png' width=25 height=25 title='Draft'>";
				}
				elseif($AU_PROCS == 1)	// APPROVE -> LOCK
				{
					$gbrPROC	= "<img src='" .base_url(). "assets/AdminLTE-2.0.5/dist/img/lock_icon.png' width=25 height=25 title='Processing'>";
				}
				elseif($AU_PROCS == 2)	// ON PROGRESS
				{
					$gbrPROC	= "<img src='" .base_url(). "assets/AdminLTE-2.0.5/dist/img/process_icon2.png' width=25 height=25 title='Procesing'>";
				}
				elseif($AU_PROCS == 3)	// FINISH
				{
						$gbrPROC	= "<img src='" .base_url(). "assets/AdminLTE-2.0.5/dist/img/finish_icon.png' width=25 height=25 title='Finish'>";
				}
				elseif($AU_PROCS == 4)	// CANCELED
				{
						$gbrPROC	= "<img src='" .base_url(). "assets/AdminLTE-2.0.5/dist/img/canceled_icon.png' width=25 height=25 title='Canceled'>";
				}
				
				$secUpd		= site_url('c_asset/c_453tu55493/In2_Up45537/?id='.$this->url_encryption_helper->encode_url($AU_CODE));				
				$secPrint	= site_url('c_asset/c_453tu55493/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($AU_CODE));
                                    
				if($AU_STAT == 1) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									</label>";
				}                                            
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									</label>";
				}
								
				
				$output['data'][] = array("$noU.",
										  "<label style='white-space:nowrap'>".$dataI['AU_CODE']."</label>",
										  $AU_DATEV,
										  $AS_NAME,
										  $JOBDESC,
										  $AU_DESC,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $gbrPROC,
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function In2_Up45537() // OK
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
			
			$PRJCODE				= $getAU->PRJCODE;
			
			$data['default']['AU_CODE'] 		= $getAU->AU_CODE;
			$data['default']['AUR_CODE'] 		= $getAU->AUR_CODE;
			$data['default']['AU_JOBCODE'] 		= $getAU->AU_JOBCODE;
			$data['default']['AU_AS_CODE'] 		= $getAU->AU_AS_CODE;
			$data['default']['AU_DATE'] 		= $getAU->AU_DATE;
			$data['default']['PRJCODE'] 		= $getAU->PRJCODE;
			$data['default']['AU_DESC'] 		= $getAU->AU_DESC;
			$data['default']['AU_STARTD'] 		= $getAU->AU_STARTD;
			$data['default']['AU_ENDD'] 		= $getAU->AU_ENDD;
			$data['default']['AU_STARTT'] 		= $getAU->AU_STARTT;
			$data['default']['AU_ENDT'] 		= $getAU->AU_ENDT;
			$data['default']['AP_QTYOPR'] 		= $getAU->AP_QTYOPR;
			$data['default']['AP_QTYUNIT'] 		= $getAU->AP_QTYUNIT;
			$data['default']['AU_STAT'] 		= $getAU->AU_STAT;
			$data['default']['AU_NEEDITM'] 		= $getAU->AU_NEEDITM;
			$data['default']['AU_PROCS'] 		= $getAU->AU_PROCS;
			$data['default']['AU_CONFD'] 		= $getAU->AU_CONFD;
			$data['default']['AU_APPD'] 		= $getAU->AU_APPD;
			$data['default']['AU_PROCD'] 		= $getAU->AU_PROCD;
			$data['default']['AU_PROCT'] 		= $getAU->AU_PROCT;
			$data['default']['AU_REFNO'] 		= $getAU->AU_REFNO;
			$data['default']['Patt_Number'] 	= $getAU->Patt_Number;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Pers. Penggunaan Alat";
			}
			else
			{
				$data["h1_title"] 	= "Tools Usage Approval";
			}
			
			$data['form_action']	= site_url('c_asset/c_453tu55493/In2_Up45537_process');
			$linkBack				= site_url('c_asset/c_453tu55493/iN20_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$linkBack				= site_url('c_asset/c_453tu55493/inbox/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 		= $linkBack;
			
			$MenuCode 			= 'MN066';
			$data["MenuCode"] 	= 'MN066';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getAU->AU_CODE;
				$MenuCode 		= 'MN066';
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
			
			$this->load->view('v_asset/v_asset_usage/inb_asset_usage_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function In2_Up45537_process()
	{
		$this->load->model('m_asset/m_asset_usage', '', TRUE);
		$this->load->model('m_journal/m_journal', '', TRUE);
		
		$comp_init 		= $this->session->userdata('comp_init');
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
			$PRJCODE 	= $this->input->post('PRJCODE');
			$AU_STAT	= $this->input->post('AU_STAT');
			$AUR_CODE	= $this->input->post('AUR_CODE');
			$AU_DATE	= date('Y-m-d', strtotime($this->input->post('AU_DATE')));
			$AU_DESC	= $this->input->post('AU_DESC');
			$PRJCODE	= $this->input->post('PRJCODE');
			
			date_default_timezone_set("Asia/Jakarta");
			$AU_CONFD	= "0000-00-00";
			$AU_APPD	= "0000-00-00";
			
			$AH_CODE		= $AU_CODE;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= $this->input->post('AU_DESC');
			$AH_ISLAST		= $this->input->post('IS_LAST');
								
			// UPDATE JOBDETAIL ITEM
			if($AU_STAT == 3)
			{
				$UpdAU 		= array('AU_CODE' 		=> $this->input->post('AU_CODE'),
									'AU_STAT'		=> 7);
				$this->m_asset_usage->update($AU_CODE, $UpdAU);
				
				$AU_APPD 	= date('Y-m-d H:i:s');
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "AU_CODE",
											'DOC_CODE' 		=> $AU_CODE,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_asset_usage");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
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
					if($AP_QTYOPR == '') $AP_QTYOPR = 0;
				// END : TIME ASSET PRODUCTION
				
				// UPDATE HEADER
					$AU_NEEDITM	= $this->input->post('AU_NEEDITM');
					$UpdAU 		= array('AU_CODE' 		=> $AU_CODE,
										'AUR_CODE'		=> $AUR_CODE,
										'AU_JOBCODE'	=> $this->input->post('AU_JOBCODE'),
										'AU_AS_CODE'	=> $this->input->post('AU_AS_CODE'),
										'AU_DATE'		=> date('Y-m-d',strtotime($AU_DATE)),
										'PRJCODE'		=> $PRJCODE,
										'AU_DESC'		=> $AU_DESC,
										'AU_STARTD'		=> $AU_STARTD,
										'AU_ENDD'		=> $AU_ENDD,
										'AU_STARTT'		=> $AU_STARTT,
										'AU_ENDT'		=> $AU_ENDT,
										'AU_PROCS'		=> 2,
										'AU_CONFD'		=> $AU_CONFD,
										'AU_APPD'		=> $AU_APPD);
					$this->m_asset_usage->update($AU_CODE, $UpdAU);
					
					if($AH_ISLAST == 1)
					{
						// UPDATE HEADER
							$AU_NEEDITM	= $this->input->post('AU_NEEDITM');
							$UpdAU 		= array('AP_HOPR'	=> $AP_HOPR,
												'AU_STAT'	=> $AU_STAT,
												'AU_PROCS'	=> 3);			// BERARTI AUTO FINISH
							$this->m_asset_usage->update($AU_CODE, $UpdAU);
							
						// UPDATE LAST POSITION AND LAST JOB ASSET
							$AS_CODE		= $this->input->post('AU_AS_CODE');		// ASSET CODE
							$AS_LASTPOS		= $PRJCODE;								// LAST POSITION
							$AS_LASTJOB		= $this->input->post('AU_JOBCODE');		// LAST JOB
							/*$UpdAS			= array('AS_LASTPOS'	=> $AS_LASTPOS,
													'AS_LASTJOB'	=> $AS_LASTJOB,
													'AS_LASTSTAT'	=> $AU_STAT,
													'AS_STAT'		=> $AU_STAT);*/

							// KARENA AUTO FINISH, MAKA STATUS ALAT LANGSUNG READY = 1
							$UpdAS			= array('AS_LASTPOS'	=> $AS_LASTPOS,
													'AS_LASTJOB'	=> $AS_LASTJOB,
													'AS_LASTSTAT'	=> 1,
													'AS_STAT'		=> 1);
							$this->m_asset_usage->updateAST($AS_CODE, $UpdAS);
						
							$JournalH_Code	= $AU_CODE;
							
						if($AU_NEEDITM == 1)
						{
							// START : JOURNAL HEADER
								$this->load->model('m_journal/m_journal', '', TRUE);
								
								$JournalH_Code	= $AU_CODE;
								$JournalType	= 'AU';
								$JournalH_Date	= $AU_DATE;
								$Company_ID		= $comp_init;
								$DOCSource		= $AUR_CODE;
								$LastUpdate		= $AU_APPD;
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= '';
								$RefType		= 'WBSD';
								$PRJCODE		= $PRJCODE;
								
								$parameters = array('JournalH_Code' 	=> $JournalH_Code,
													'JournalType'		=> $JournalType,
													'JournalH_Desc'		=> $AU_DESC,
													'JournalH_Date' 	=> $JournalH_Date,
													'Company_ID' 		=> $Company_ID,
													'Source'			=> $DOCSource,
													'Emp_ID'			=> $DefEmp_ID,
													'LastUpdate'		=> $LastUpdate,	
													'KursAmount_tobase'	=> 1,
													'WHCODE'			=> $PRJCODE,
													'Reference_Number'	=> $Refer_Number,
													'Manual_No'			=> $JournalH_Code,
													'RefType'			=> $RefType,
													'PRJCODE'			=> $PRJCODE);
								$this->m_journal->createJournalH($JournalH_Code, $parameters); // OK
							// END : JOURNAL HEADER
					
							foreach($_POST['data'] as $d)
							{
								$JournalH_Code	= $JournalH_Code;
								$PRJCODE 		= $d['PRJCODE'];
								$AU_CODE 		= $d['AU_CODE'];
								$ITM_CODE 		= $d['ITM_CODE'];
								$ITM_UNIT 		= $d['ITM_UNIT'];
								$ITM_TOTAL 		= $d['ITM_TOTAL'];
								
								$ACC_NUM		= '';
								$ITM_GROUP		= '';
								$ITM_TYPE		= 4;
								
								$sqlL_D	= "SELECT ITM_UNIT, ACC_ID_UM AS ACC_ID, ITM_GROUP, ITM_TYPE,
												ISMTRL, ISRENT, ISPART, ISFUEL, ISLUBRIC, ISFASTM, ISWAGE
											FROM tbl_item 
											WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
								$resL_D = $this->db->query($sqlL_D)->result();					
								foreach($resL_D as $rowL_D):
									$ITM_UNIT	= $rowL_D->ITM_UNIT;
									$ACC_NUM	= $rowL_D->ACC_ID;
									$ITM_GROUP	= $rowL_D->ITM_GROUP;
									$ISMTRL 	= $rowL_D->ISMTRL;
									$ISRENT 	= $rowL_D->ISRENT;
									$ISPART 	= $rowL_D->ISPART;
									$ISFUEL 	= $rowL_D->ISFUEL;
									$ISLUBRIC 	= $rowL_D->ISLUBRIC;
									$ISFASTM 	= $rowL_D->ISFASTM;
									$ISWAGE 	= $rowL_D->ISWAGE;
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
								
								$ACC_ID 		= $ACC_NUM;
								$ITM_UNIT 		= $ITM_UNIT;
								$ITM_GROUP 		= $ITM_GROUP;
								$ITM_TYPE 		= $ITM_TYPE;
								$ITM_QTY 		= $d['ITM_QTY'];
								$ITM_PRICE 		= $d['ITM_PRICE'];
								$ITM_DISC 		= 0;
								$TAXCODE1 		= '';
								$TAXPRICE1 		= 0;
								
								$JournalType	= 'AU';
								$JournalH_Date	= $JournalH_Date;
								$Company_ID		= $comp_init;
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
													'TRANS_CATEG' 		=> 'AU',			// UM = Asset Usage
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

								// START : UPDATE PROFIT AND LOSS
									$this->load->model('m_updash/m_updash', '', TRUE);

									$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
									$PERIODED	= $JournalH_Date;
									$FIELDNME	= "";
									$FIELDVOL	= $ITM_QTY;
									$FIELDPRC	= $ITM_PRICE;
									$ADDTYPE	= "MIN";		// PENGURANGAN KARENA SEBAGAI BAHAN MASUKAN

									$parameters1 = array('PERIODED' 	=> $PERIODED,
														'FIELDNME'		=> $FIELDNME,
														'FIELDVOL' 		=> $FIELDVOL,
														'FIELDPRC' 		=> $FIELDPRC,
														'ADDTYPE' 		=> $ADDTYPE,
														'ITM_CODE'		=> $ITM_CODE,
														'ITM_TYPE'		=> $ITM_TYPE);
									$this->m_updash->updateLR_NForm($PRJCODE, $parameters1);
								// END : UPDATE PROFIT AND LOSS
							}
						}
						
						// START : UPDATE STATUS
							$completeName 	= $this->session->userdata['completeName'];
							$paramStat 		= array('PM_KEY' 		=> "AU_CODE",
													'DOC_CODE' 		=> $AU_CODE,
													'DOC_STAT' 		=> $AU_STAT,
													'PRJCODE' 		=> $PRJCODE,
													'CREATERNM'		=> '',
													'TBLNAME'		=> "tbl_asset_usage");
							$this->m_updash->updateStatus($paramStat);
						// END : UPDATE STATUS
					}
			}
			elseif($AU_STAT == 4 || $AU_STAT == 5)
			{
				$UpdAU 		= array('AU_CODE' 		=> $this->input->post('AU_CODE'),
									'AU_STAT'		=> $this->input->post('AU_STAT'));
				$this->m_asset_usage->update($AU_CODE, $UpdAU);
				
				$UpdAU 		= array('AU_CODE' 		=> $this->input->post('AU_CODE'),
									'AU_STAT'		=> $this->input->post('AU_STAT'),
									'AU_PROCS'		=> 4,	// 0.New/Conf, 1.App. 2.Process, 3.Finish, 4.Canc
									'AU_APPD'		=> $AU_APPD);
				$this->m_asset_usage->update($AU_CODE, $UpdAU);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "AU_CODE",
											'DOC_CODE' 		=> $AU_CODE,
											'DOC_STAT' 		=> $AU_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_asset_usage");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $AU_CODE;
				$MenuCode 		= 'MN066';
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
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_453tu55493/iN20_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prnt180d0bdocxx()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$AU_CODE	= $_GET['id'];
		$AU_CODE	= $this->url_encryption_helper->decode_url($AU_CODE);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getAU 					= $this->m_asset_usage->get_AU($AU_CODE)->row();
			$data['AU_CODE'] 		= $getAU->AU_CODE;
			$data['AUR_CODE'] 		= $getAU->AUR_CODE;
			$data['AU_JOBCODE'] 	= $getAU->AU_JOBCODE;
			$data['AU_AS_CODE'] 	= $getAU->AU_AS_CODE;
			$data['AU_DATE'] 		= $getAU->AU_DATE;
			$data['PRJCODE'] 		= $getAU->PRJCODE;
			$data['AU_DESC'] 		= $getAU->AU_DESC;
			$data['AU_STARTD'] 		= $getAU->AU_STARTD;
			$data['AU_ENDD'] 		= $getAU->AU_ENDD;
			$data['AU_STARTT'] 		= $getAU->AU_STARTT;
			$data['AU_ENDT'] 		= $getAU->AU_ENDT;
			$data['AP_QTYOPR'] 		= $getAU->AP_QTYOPR;
			$data['AP_QTYUNIT'] 	= $getAU->AP_QTYUNIT;
			$data['AU_STAT'] 		= $getAU->AU_STAT;
			$data['AU_NEEDITM'] 	= $getAU->AU_NEEDITM;
			$data['AU_PROCS'] 		= $getAU->AU_PROCS;
			$data['AU_CONFD'] 		= $getAU->AU_CONFD;
			$data['AU_APPD'] 		= $getAU->AU_APPD;
			$data['AU_PROCD'] 		= $getAU->AU_PROCD;
			$data['AU_PROCT'] 		= $getAU->AU_PROCT;
			
			$this->load->view('v_asset/v_asset_usage/print_asset_usage', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function prnt180d0bdoc()
	{
		$this->load->model('m_project/m_fpa/m_fpa', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$AU_CODE		= $_GET['id'];
		$AU_CODE		= $this->url_encryption_helper->decode_url($AU_CODE);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getAU 					= $this->m_asset_usage->get_AU($AU_CODE)->row();
			
			$data['default']['AU_CODE'] 		= $getAU->AU_CODE;
			$data['default']['AUR_CODE'] 		= $getAU->AUR_CODE;
			$data['default']['AU_JOBCODE'] 		= $getAU->AU_JOBCODE;
			$data['default']['AU_AS_CODE'] 		= $getAU->AU_AS_CODE;
			$data['default']['AU_DATE'] 		= $getAU->AU_DATE;
			$data['default']['PRJCODE'] 		= $getAU->PRJCODE;
			$data['default']['AU_DESC'] 		= $getAU->AU_DESC;
			$data['default']['AU_STARTD'] 		= $getAU->AU_STARTD;
			$data['default']['AU_ENDD'] 		= $getAU->AU_ENDD;
			$data['default']['AU_STARTT'] 		= $getAU->AU_STARTT;
			$data['default']['AU_ENDT'] 		= $getAU->AU_ENDT;
			$data['default']['AP_QTYOPR'] 		= $getAU->AP_QTYOPR;
			$data['default']['AP_QTYUNIT'] 		= $getAU->AP_QTYUNIT;
			$data['default']['AU_STAT'] 		= $getAU->AU_STAT;
			$data['default']['AU_NEEDITM'] 		= $getAU->AU_NEEDITM;
			$data['default']['AU_PROCS'] 		= $getAU->AU_PROCS;
			$data['default']['AU_CONFD'] 		= $getAU->AU_CONFD;
			$data['default']['AU_APPD'] 		= $getAU->AU_APPD;
			$data['default']['AU_PROCD'] 		= $getAU->AU_PROCD;
			$data['default']['AU_PROCT'] 		= $getAU->AU_PROCT;
			$data['default']['AU_REFNO'] 		= $getAU->AU_REFNO;
			$data['default']['Patt_Number'] 	= $getAU->Patt_Number;
							
			$this->load->view('v_asset/v_asset_usage/print_asset_usage', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}