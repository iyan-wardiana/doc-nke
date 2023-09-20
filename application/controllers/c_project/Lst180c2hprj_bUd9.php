<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Agustus 2019
 * File Name	= Lst180c2hprj_bUd9.php
 * Location		= -
*/

class Lst180c2hprj_bUd9 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_project/m_listproject/m_listproject_budg', '', TRUE);
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
	
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/lst180c2hprj_bUd9/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function i180c2gdx($offset=0)
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Project List';
			$data['secAddURL'] 	= site_url('c_project/lst180c2hprj_bUd9/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 	= site_url('c_project/lst180c2hprj_bUd9/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 				= $this->m_listproject_budg->count_all_num_rows();
			$data["recordcount"] 	= $num_rows;
			$data['MenuCode'] 		= 'MN126';
	 
			$data['vewproject']		= $this->m_listproject_budg->get_last_ten_project()->result();
			
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
			redirect('__I1y');
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
			$data['form_action']= site_url('c_project/lst180c2hprj_bUd9/add_process');
			$data['backURL'] 	= site_url('c_project/lst180c2hprj_bUd9/');
			
			$MenuCode 				= 'MN126';
			$data['MenuCode'] 		= 'MN126';
			$data['viewDocPattern'] = $this->m_listproject_budg->getDataDocPat($MenuCode)->result();
			
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
			redirect('__I1y');
		}
	}
		
	function getTheCode($PRJCODE) // OK
	{ 	
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		$recordcountProj 	= $this->m_listproject_budg->count_all_num_rowsProj($PRJCODE);
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
		date_default_timezone_set("Asia/Jakarta");
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
			$PRJCATEG 				= $this->input->post('PRJCATEG');
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
			
			$sqlPRJHOC	= "tbl_project WHERE isHO = '1'";
			$resPRJHOC 	= $this->db->count_all($sqlPRJHOC);
			$syncPRJHO	= "";
			if($resPRJHOC > 0)
			{
				$sqlPRJHO	= "SELECT PRJCODE AS PRJCODEHO FROM tbl_project WHERE isHO = 1";
				$resPRJHO 	= $this->db->query($sqlPRJHO)->result();
				foreach($resPRJHO as $rowPRJHO) :
					$PRJCODEHO 	= $rowPRJHO->PRJCODEHO;
				endforeach;
				$syncPRJHO	= "$PRJCODEHO~$PRJCODE";
			}
			else
			{
				echo "We not found the Head Office Code<br>Contact your administrator.";
				return false;
			}
			
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
									'PRJCODE'		=> $PRJCODE,
									'PRJCNUM'		=> $PRJCNUM,
									'PRJNAME'		=> $PRJNAME,
									'PRJLOCT'		=> $PRJLOCT,
									'PRJCATEG'		=> $PRJCATEG,
									'PRJOWN'		=> $PRJOWN,
									'PRJDATE'		=> $PRJDATE,
									'PRJDATE_CO'	=> $PRJDATE_CO,
									'PRJEDAT'		=> $PRJEDAT,
									'PRJBOQ'		=> $PRJCOST,
									'PRJCOST'		=> $PRJCOST,
									'PRJLKOT'		=> $PRJLKOT,
									'PRJCBNG'		=> $PRJCBNG,
									'PRJCURR'		=> $PRJCURR,
									'CURRRATE'		=> $CURRRATE,
									'PRJSTAT'		=> $PRJSTAT,
									'PRJNOTE'		=> $PRJNOTE,
									'PRJ_MNG'		=> $PRJ_MNG,
									'QTY_SPYR'		=> $QTY_SPYR,
									'PRC_STRK'		=> $PRC_STRK,
									'PRC_ARST'		=> $PRC_ARST,
									'PRC_MKNK'		=> $PRC_MKNK,
									'PRC_ELCT'		=> $PRC_ELCT,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Number'	=> $Patt_Number);
			$this->m_listproject_budg->add($projectheader);
			
			// START : CREATE LABA RUGI
				$LR_CODE	= date('YmdHis');
				$PERIODE	= date('Y-m-d');
				$LR_CREATER	= $this->session->userdata['Emp_ID'];
				$LR_CREATED	= date('Y-m-d H:i:s');
				
				$projectLR 	= array('LR_CODE'			=> $LR_CODE,
								'PERIODE'				=> $PERIODE,
								'PRJCODE'				=> $PRJCODE,
								'PRJNAME'				=> $PRJNAME,
								'PRJCOST'				=> $PRJCOST,
								'LR_CREATER'			=> $LR_CREATER,
								'LR_CREATED'			=> $LR_CREATED);
				$this->m_listproject_budg->addLR($projectLR);
			// END : UPDATE LABA RUGI
			
			// START : COPY ALL ACCOUNT
				$LR_CODE	= date('YmdHis');
				$PERIODE	= date('Y-m-d');
				$LR_CREATER	= $this->session->userdata['Emp_ID'];
				$LR_CREATED	= date('Y-m-d H:i:s');
				
				$projectLR 	= array('LR_CODE'			=> $LR_CODE,
								'PERIODE'				=> $PERIODE,
								'PRJCODE'				=> $PRJCODE,
								'PRJNAME'				=> $PRJNAME,
								'PRJCOST'				=> $PRJCOST,
								'LR_CREATER'			=> $LR_CREATER,
								'LR_CREATED'			=> $LR_CREATED);
				$this->m_listproject_budg->addLR($projectLR);
			// END : UPDATE LABA RUGI
			
			// START : COPY COA
				$sqlAcc		= "SELECT Acc_ID, Account_Class, Account_Number, Account_NameEn, Account_NameId, Account_Category,
									Account_Level, Acc_DirParent, Acc_ParentList, Acc_StatusLinked, Company_ID, Default_Acc,
									Currency_id, Base_OpeningBalance, Base_Debet, Base_Kredit, Base_Debet_tax, Base_Kredit_tax,
									IsInterCompany, isCostComponent, isOnDuty, isFOHCost, Base_Debet2, Base_Kredit2,
									Base_Debet_tax2, Base_Kredit_tax2, COGSReportID, isHO, syncPRJ, isLast
								FROM tbl_chartaccount
								WHERE 
									PRJCODE = '$PRJCODEHO'";
				$resAcc 	= $this->db->query($sqlAcc)->result();
				foreach($resAcc as $rowAcc) :
					$PRJCODE			= $PRJCODE;
					$Acc_ID 			= $rowAcc->Acc_ID;
					$Account_Class 		= $rowAcc->Account_Class;
					$Account_Number 	= $rowAcc->Account_Number;
					$Account_NameEn 	= $rowAcc->Account_NameEn;
					$Account_NameId 	= $rowAcc->Account_NameId;
					$Account_Category 	= $rowAcc->Account_Category;
					$Account_Level 		= $rowAcc->Account_Level;
					$Acc_DirParent 		= $rowAcc->Acc_DirParent;
					$Acc_ParentList 	= $rowAcc->Acc_ParentList;
					$Acc_StatusLinked 	= $rowAcc->Acc_StatusLinked;
					$Company_ID 		= $rowAcc->Company_ID;
					$Default_Acc 		= $rowAcc->Default_Acc;
					$Currency_id 		= $rowAcc->Currency_id;
					$isHO 				= $rowAcc->isHO;

					if($isHO == 1)
					{
						$Base_OpeningBalance= $rowAcc->Base_OpeningBalance;
						$Base_Debet 		= $rowAcc->Base_Debet;
						$Base_Kredit 		= $rowAcc->Base_Kredit;
						$Base_Debet_tax 	= $rowAcc->Base_Debet_tax;
						$Base_Kredit_tax 	= $rowAcc->Base_Kredit_tax;						
					}
					else
					{
						// SEMENTAR SEMUA AKUN DI NOL KAN BAIK KAS/BANK
							$Base_OpeningBalance= 0;
							$Base_Debet 		= 0;
							$Base_Kredit 		= 0;
							$Base_Debet_tax 	= 0;
							$Base_Kredit_tax 	= 0;						
					}
						
					$IsInterCompany 	= $rowAcc->IsInterCompany;
					$isCostComponent 	= $rowAcc->isCostComponent;
					$isOnDuty 			= $rowAcc->isOnDuty;
					$isFOHCost 			= $rowAcc->isFOHCost;

					if($isHO == 1)
					{
						$Base_Debet2 		= $rowAcc->Base_Debet2;
						$Base_Kredit2 		= $rowAcc->Base_Kredit2;
						$Base_Debet_tax2 	= $rowAcc->Base_Debet_tax2;
						$Base_Kredit_tax2 	= $rowAcc->Base_Kredit_tax2;					
					}
					else
					{
						// SEMENTAR SEMUA AKUN DI NOL KAN BAIK KAS/BANK
							$Base_Debet2		= 0;
							$Base_Kredit2 		= 0;
							$Base_Debet_tax2 	= 0;
							$Base_Kredit_tax2 	= 0;						
					}
						
					$COGSReportID 		= $rowAcc->COGSReportID;
					$syncPRJ1 			= $rowAcc->syncPRJ;
					//$syncPRJ			= "$syncPRJ1~$PRJCODE";
					$syncPRJ			= $syncPRJHO;
					$isLast 			= $rowAcc->isLast;
					$sqlInsrAcc			= "INSERT INTO tbl_chartaccount 
												(PRJCODE, Acc_ID, Account_Class, Account_Number, Account_NameEn, Account_NameId, 
												Account_Category, Account_Level, Acc_DirParent, Acc_ParentList, Acc_StatusLinked,
												Company_ID, Default_Acc, Currency_id, Base_OpeningBalance, Base_Debet, Base_Kredit, 
												Base_Debet_tax, Base_Kredit_tax, IsInterCompany, isCostComponent, isOnDuty, isFOHCost,
												Base_Debet2, Base_Kredit2, Base_Debet_tax2, Base_Kredit_tax2, COGSReportID, isHO,
												syncPRJ, isLast) 
											VALUES 
												('$PRJCODE', '$Acc_ID', '$Account_Class', '$Account_Number', '$Account_NameEn', 
												'$Account_NameId', '$Account_Category', '$Account_Level', '$Acc_DirParent', 
												'$Acc_ParentList', '$Acc_StatusLinked', '$Company_ID', '$Default_Acc','$Currency_id', 
												'$Base_OpeningBalance', '$Base_Debet', '$Base_Kredit', '$Base_Debet_tax', 
												'$Base_Kredit_tax', '$IsInterCompany', '$isCostComponent', '$isOnDuty', '$isFOHCost',
												'$Base_Debet2', '$Base_Kredit2', '$Base_Debet_tax2', '$Base_Kredit_tax2', 
												'$COGSReportID', '$isHO', '$syncPRJ', '$isLast')";
					$this->db->query($sqlInsrAcc);
				endforeach;	
			// END : COPY COA
				
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
			
			$url			= site_url('c_project/lst180c2hprj_bUd9/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__I1y');
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
            $this->m_listproject_budg->updatePict($PRJCODE, $nameFile);
         }
		 
         $sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/lst180c2hprj_bUd9/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function u180c2gdt()
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
			$data['form_action']	= site_url('c_project/lst180c2hprj_bUd9/update_process');
			$data['backURL'] 		= site_url('c_project/lst180c2hprj_bUd9/');
			
			$data['recordcountCust'] 	= $this->m_listproject_budg->count_all_num_rowsCust();
			$data['viewcustomer'] 		= $this->m_listproject_budg->viewcustomer()->result();
			
			$MenuCode 					= 'MN126';
			$data['MenuCode'] 			= 'MN126';
			$data['viewDocPattern'] 	= $this->m_listproject_budg->getDataDocPat($MenuCode)->result();
			
			$getproject = $this->m_listproject_budg->get_PROJ_by_number($PRJCODE)->row();
					
			$data['default']['proj_Number'] = $getproject->proj_Number;
			$data['default']['PRJCODE'] 	= $getproject->PRJCODE;
			$data['default']['PRJCNUM'] 	= $getproject->PRJCNUM;
			$data['default']['PRJNAME'] 	= $getproject->PRJNAME;
			$data['default']['PRJLOCT'] 	= $getproject->PRJLOCT;
			$data['default']['PRJCATEG'] 	= $getproject->PRJCATEG;
			$data['default']['PRJOWN'] 		= $getproject->PRJOWN;
			$data['default']['PRJDATE'] 	= $getproject->PRJDATE;
			$data['default']['PRJDATE_CO'] 	= $getproject->PRJDATE_CO;
			$data['default']['PRJEDAT'] 	= $getproject->PRJEDAT;
			$PRJEDAT						= $getproject->PRJEDAT;
			//echo "c_hehe $PRJEDAT";
			$data['default']['PRJCOST'] 	= $getproject->PRJCOST;
			$data['default']['PRJBOQ'] 		= $getproject->PRJBOQ;
			$data['default']['PRJRAP'] 		= $getproject->PRJRAP;
			$data['default']['PRJLKOT'] 	= $getproject->PRJLKOT;
			$data['default']['PRJCBNG']		= $getproject->PRJCBNG;
			$data['default']['PRJCURR']		= $getproject->PRJCURR;
			$data['default']['CURRRATE']	= $getproject->CURRRATE;
			$data['default']['PRJSTAT'] 	= $getproject->PRJSTAT;
			$data['default']['PRJNOTE'] 	= $getproject->PRJNOTE;
			$data['default']['isHO']		= $getproject->isHO;
			
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
			redirect('__I1y');
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
			$data['form_action']	= site_url('c_project/lst180c2hprj_bUd9/vInpProjDet_process');
			$data['h2_title'] 		= 'Input Project Progress';
			
			$this->load->view('v_project/v_listproject/project_sd_detInput', $data);
		}
		else
		{
			redirect('__I1y');
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
		
		//$this->m_listproject_budg->deleteProjDet($this->input->post('proj_Code'));
		
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
											
		$this->m_listproject_budg->addInpProjDet($projectDet);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		
		$this->session->set_flashdata('message', 'Data succesfull to insert.!');
		redirect('c_project/lst180c2hprj_bUd9/');
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
			redirect('__I1y');
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
			$PRJCATEG 				= $this->input->post('PRJCATEG');
			$PRJOWN 				= $this->input->post('PRJOWN');
			$isHO 					= $this->input->post('isHO');
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
			$PRJ_MNG1				= $this->input->post('PRJ_MNG');
			
			// CEK HEAD OFFICE EXIST
				$sqlHO	= "tbl_project WHERE PRJCODE != '$PRJCODE' AND isHO = 1";
				$resHO	= $this->db->count_all($sqlHO);
			
			// CEK THIS PROJECT
				$sqlHO1	= "tbl_project WHERE PRJCODE = '$PRJCODE' AND isHO = 1";
				$resHO1	= $this->db->count_all($sqlHO1);
			if($resHO1 == 1)
			{
				$projectheader = array('proj_Number' 	=> $proj_Number,
								'PRJCODE'				=> $PRJCODE,
								'PRJCNUM'				=> $PRJCNUM,
								'PRJNAME'				=> $PRJNAME,
								'PRJLOCT'				=> $PRJLOCT,
								'PRJCATEG'				=> $PRJCATEG,
								'PRJOWN'				=> $PRJOWN,
								'PRJDATE'				=> $PRJDATE,
								'PRJEDAT'				=> $PRJEDAT,
								'PRJDATE_CO'			=> $PRJDATE_CO,
								'PRJBOQ'				=> $PRJCOST,
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
				$this->m_listproject_budg->update($PRJCODE, $projectheader);
			}
			else
			{
				$PRJCODEHOX	= '';
				if($resHO == 0)
				{
					$LangID 	= $this->session->userdata['LangID'];
					if($LangID == 'IND')
					{
						$Back	= "Kembali";
					}
					else
					{
						$Back	= "Back";
					}
					
					$backURL	= site_url('c_project/lst180c2hprj_bUd9/');
					echo "Kami tidak menemukan kode kantor pusat.<br>Tidak bsia dilanjutkan. Hubungi administrator.<br>";
					echo anchor("$backURL",'<button class="btn btn-primary">'.$Back.'</button>');
					return false;
				}
				else
				{
					// START : COPY COA BARU UNTUK PROYEK HO BARU
						if($isHO == 1)
						{
							$PRJCODEHO	= '';
							$sqlPRJHO	= "SELECT PRJCODE AS PRJCODEHO FROM tbl_project WHERE isHO = 1";
							$resPRJHO 	= $this->db->query($sqlPRJHO)->result();
							foreach($resPRJHO as $rowPRJHO) :
								$PRJCODEHO 	= $rowPRJHO->PRJCODEHO;
							endforeach;
							$PRJCODEHOX	= $PRJCODEHO;
							
							$syncPRJA	= '';
							$sqlCOASYN	= "SELECT syncPRJ FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODEHO' LIMIT 1";
							$resCOASYN 	= $this->db->query($sqlCOASYN)->result();
							foreach($resCOASYN as $rowCOASYN) :
								$syncPRJA 	= $rowCOASYN->syncPRJ;
							endforeach;
							
							$splitPRJ	= explode("~",$syncPRJA);
							$row 		= 0;
							$syncPRJHO	= '';
							foreach($splitPRJ as $i =>$key) 
							{
								$row	= $row + 1;
								$NEWPRJ	= $key;
								if($NEWPRJ == $PRJCODEHO)
								{
									$NEWPRJ	= $PRJCODE;
								}
								if($row == 1)
								{
									$syncPRJHO	= $NEWPRJ;
								}
								else
								{
									$syncPRJHO	= "$syncPRJHO~$NEWPRJ";
								}
							}
							
							// DELETE SEMUA COA UNTUK PROYEK BARU U/ DIGANTI DENGAN COA BARU
								$sqlDELCOA	= "DELETE FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
								$this->db->query($sqlDELCOA);
								
							$sqlAcc		= "SELECT Acc_ID, Account_Class, Account_Number, Account_NameEn, Account_NameId, Account_Category,
												Account_Level, Acc_DirParent, Acc_ParentList, Acc_StatusLinked, Company_ID, Default_Acc,
												Currency_id, Base_OpeningBalance, Base_Debet, Base_Kredit, Base_Debet_tax, Base_Kredit_tax,
												IsInterCompany, isCostComponent, isOnDuty, isFOHCost, Base_Debet2, Base_Kredit2,
												Base_Debet_tax2, Base_Kredit_tax2, COGSReportID, isHO, syncPRJ, isLast
											FROM tbl_chartaccount
											WHERE 
												PRJCODE = '$PRJCODEHO'";
							$resAcc 	= $this->db->query($sqlAcc)->result();
							foreach($resAcc as $rowAcc) :
								$PRJCODE			= $PRJCODE;
								$Acc_ID 			= $rowAcc->Acc_ID;
								$Account_Class 		= $rowAcc->Account_Class;
								$Account_Number 	= $rowAcc->Account_Number;
								$Account_NameEn 	= $rowAcc->Account_NameEn;
								$Account_NameId 	= $rowAcc->Account_NameId;
								$Account_Category 	= $rowAcc->Account_Category;
								$Account_Level 		= $rowAcc->Account_Level;
								$Acc_DirParent 		= $rowAcc->Acc_DirParent;
								$Acc_ParentList 	= $rowAcc->Acc_ParentList;
								$Acc_StatusLinked 	= $rowAcc->Acc_StatusLinked;
								$Company_ID 		= $rowAcc->Company_ID;
								$Default_Acc 		= $rowAcc->Default_Acc;
								$Currency_id 		= $rowAcc->Currency_id;
								$isHOX 				= $rowAcc->isHO;
								$Base_OpeningBalance= $rowAcc->Base_OpeningBalance;
								$Base_Debet 		= $rowAcc->Base_Debet;
								$Base_Kredit 		= $rowAcc->Base_Kredit;
								$Base_Debet_tax 	= $rowAcc->Base_Debet_tax;
								$Base_Kredit_tax 	= $rowAcc->Base_Kredit_tax;	
								$IsInterCompany 	= $rowAcc->IsInterCompany;
								$isCostComponent 	= $rowAcc->isCostComponent;
								$isOnDuty 			= $rowAcc->isOnDuty;
								$isFOHCost 			= $rowAcc->isFOHCost;
								$Base_Debet2 		= $rowAcc->Base_Debet2;
								$Base_Kredit2 		= $rowAcc->Base_Kredit2;
								$Base_Debet_tax2 	= $rowAcc->Base_Debet_tax2;
								$Base_Kredit_tax2 	= $rowAcc->Base_Kredit_tax2;								
								$COGSReportID 		= $rowAcc->COGSReportID;
								//$syncPRJ1 		= $rowAcc->syncPRJ;
								//$syncPRJ			= "$syncPRJ1~$PRJCODE";
								$syncPRJ			= $syncPRJHO;
								$isLast 			= $rowAcc->isLast;
								$sqlInsrAcc			= "INSERT INTO tbl_chartaccount 
														(PRJCODE, Acc_ID, Account_Class, Account_Number, Account_NameEn, Account_NameId, 
														Account_Category, Account_Level, Acc_DirParent, Acc_ParentList, Acc_StatusLinked,
														Company_ID, Default_Acc, Currency_id, Base_OpeningBalance, Base_Debet, Base_Kredit, 
														Base_Debet_tax, Base_Kredit_tax, IsInterCompany, isCostComponent, isOnDuty, isFOHCost,
														Base_Debet2, Base_Kredit2, Base_Debet_tax2, Base_Kredit_tax2, COGSReportID, isHO,
														syncPRJ, isLast) 
														VALUES 
														('$PRJCODE', '$Acc_ID', '$Account_Class', '$Account_Number', '$Account_NameEn', 
														'$Account_NameId', '$Account_Category', '$Account_Level', '$Acc_DirParent', 
														'$Acc_ParentList', '$Acc_StatusLinked', '$Company_ID', '$Default_Acc','$Currency_id', 
														'$Base_OpeningBalance', '$Base_Debet', '$Base_Kredit', '$Base_Debet_tax', 
														'$Base_Kredit_tax', '$IsInterCompany', '$isCostComponent', '$isOnDuty', '$isFOHCost',
														'$Base_Debet2', '$Base_Kredit2', '$Base_Debet_tax2', '$Base_Kredit_tax2', 
														'$COGSReportID', '$isHOX', '$syncPRJ', '$isLast')";
								$this->db->query($sqlInsrAcc);
							endforeach;	
						}
					// END : COPY COA
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
				// UPDATE OLD HO
					$sqlOLDHO	= "UPDATE tbl_project SET isHO = 0 WHERE PRJCODE = '$PRJCODEHOX'";
					$this->db->query($sqlOLDHO);
				
				$projectheader = array('proj_Number' 	=> $proj_Number,
								'PRJCODE'				=> $PRJCODE,
								'PRJCNUM'				=> $PRJCNUM,
								'PRJNAME'				=> $PRJNAME,
								'PRJLOCT'				=> $PRJLOCT,
								'PRJCATEG'				=> $PRJCATEG,
								'PRJOWN'				=> $PRJOWN,
								'isHO'					=> $isHO,
								'PRJDATE'				=> $PRJDATE,
								'PRJEDAT'				=> $PRJEDAT,
								'PRJDATE_CO'			=> $PRJDATE_CO,
								'PRJBOQ'				=> $PRJCOST,
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
				$this->m_listproject_budg->update($PRJCODE, $projectheader);
			}
			
			// START : CHECK IN PROFLOSS
				$sqlPL	= "tbl_profitloss WHERE PRJCODE = '$PRJCODE'";
				$resPL	= $this->db->count_all($sqlPL);
				if($resPL == 0)
				{
					$LR_CODE	= date('YmdHis');
					$PERIODE	= date('Y-m-d');
					$LR_CREATER	= $this->session->userdata['Emp_ID'];
					$LR_CREATED	= date('Y-m-d H:i:s');
					
					$projectLR 	= array('LR_CODE'			=> $LR_CODE,
									'PERIODE'				=> $PERIODE,
									'PRJCODE'				=> $PRJCODE,
									'PRJNAME'				=> $PRJNAME,
									'PRJCOST'				=> $PRJCOST,
									'LR_CREATER'			=> $LR_CREATER,
									'LR_CREATED'			=> $LR_CREATED);
					$this->m_listproject_budg->addLR($projectLR);
				}
				else
				{
					$LR_CODE	= date('YmdHis');
					$PERIODE	= date('Y-m-d');
					$CREATER	= $this->session->userdata['Emp_ID'];
					$CREATED	= date('Y-m-d H:i:s');
					
					$projectLR 	= array('PRJNAME'		=> $PRJNAME,
										'PRJCOST'		=> $PRJCOST);
					$this->m_listproject_budg->updateLR($PRJCODE, $projectLR);
				}
			// END : CHECK IN PROFLOSS
			
			/*$PRJEDAT2		= date('Y-m-d',strtotime($this->input->post('PRJEDAT2')));	
			$PRJEDAT2a		= date('d/m/Y',strtotime($this->input->post('PRJEDAT2')));			
			$updateEndDate 	= array('PRJCODE'		=> $PRJCODE,
								'EDATORI'			=> $PRJEDAT,
								'ENDDATE'			=> $PRJEDAT2,
								'DATETIME'			=> $DATE_CREATED,
								'EMP_ID'			=> $DefEmp_ID);												
			$this->m_listproject_budg->addUpdEDat($updateEndDate);*/
			
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
			
			redirect('c_project/lst180c2hprj_bUd9/');
		}
		else
		{
			redirect('__I1y');
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

			/*$num_rows = $this->m_listproject_budg->count_all_num_rows_inbox();
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_project/lst180c2hprj_bUd9/get_last_ten_project');
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
	 		
			$data['viewpurord'] = $this->m_listproject_budg->get_last_ten_PR_inbox($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();*/	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__I1y');
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