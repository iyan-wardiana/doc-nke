<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 Februari 2017
 * File Name	= Listproject.php
 * Location		= -
*/

class C_c0mPL15t extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_company/m_listcomp/m_listcomp', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_company/m_boq/m_boq', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		
		$LangID 	= $this->session->userdata['LangID'];
		$sqlTransl	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl	= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			if($TranslCode == 'Active')$Active = $LangTransl;
			if($TranslCode == 'Inactive')$Inactive = $LangTransl;
			if($TranslCode == 'Finish')$Finish = $LangTransl;
		endforeach;
		
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
		
		// DEFAULT PROJECT
			$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$PRJCODE	= $rowISHO->PRJCODE;
			endforeach;
			$this->data['PRJCODE']		= $PRJCODE;
			$this->data['PRJCODE_HO']	= $PRJCODE;
		
		// GET PROJECT SELECT
			$collDATA		= isset($_GET['id']);
			$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);	
			$EXP_COLLD		= explode('~', $EXP_COLLD1);	
			$C_COLLD1		= count($EXP_COLLD);
			if($C_COLLD1 > 1)
			{
				$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
				$PRJCODE_HO	= $EXP_COLLD[1];
			}
			else
			{
				$PRJCODE_HO	= $EXP_COLLD1;
			}
		
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO, PRJPERIOD FROM tbl_project_budg WHERE PRJCODE_HO = '$PRJCODE_HO' AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
				$this->data['PRJPERIOD']	= $rowISHO->PRJPERIOD;
			endforeach;
	}
	
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_comprof/C_c0mPL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function i180c2gdx($offset=0)
	{
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN403';
				$data["MenuApp"] 	= 'MN403';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN403';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();		
			$data["secURL"] 	= "c_comprof/c_bUd93tL15t/i180c2gdx/?id=";
			$data['secAddURL'] 	= site_url('c_comprof/c_c0mPL15t/add/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data["secVIEW"]	= 'v_company/v_listcomp/v_listcomp';
			$this->load->view($data["secVIEW"], $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['h2_title']	= 'Add Project';
			$data['form_action']= site_url('c_comprof/C_c0mPL15t/add_process');
			$data['backURL'] 	= site_url('c_comprof/C_c0mPL15t/');
			$data['MenuCode'] 		= 'MN403';

			// GET MENU DESC
				$mnCode				= 'MN403';
				$data["MenuApp"] 	= 'MN403';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN403';
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
			
			$this->load->view('v_company/v_listcomp/v_listcomp_form', $data);
		}
		else
		{
			redirect('__I1y');
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
		date_default_timezone_set("Asia/Jakarta");
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$sqlC 	= "tbl_project";
			$resC 	= $this->db->count_all($sqlC);

			$proj_Number	= date('YmdHis');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE_HO 	= $this->input->post('PRJCODE_HO');
			$PRJNAME 		= $this->input->post('PRJNAME');
			$PRJ_MNG1 		= $this->input->post('PRJ_MNG');
			$PRJDATE		= date('Y-m-d',strtotime($this->input->post('PRJDATE')));
			$PRJDATE_CO		= date('Y-m-d',strtotime($this->input->post('PRJDATE')));
			$PRJEDAT		= date('Y-m-d',strtotime($this->input->post('PRJDATE')));
			$PRJCNUM 		= $this->input->post('PRJCNUM');
			$PRJCATEG 		= $this->input->post('PRJCATEG');
			$PRJSCATEG 		= $this->input->post('PRJSCATEG');
			$PRJLOCT 		= addslashes($this->input->post('PRJLOCT'));
			$PRJTELP		= $this->input->post('PRJTELP');
			$PRJADD 		= addslashes($this->input->post('PRJADD'));
			$PRJNOTE		= addslashes($this->input->post('PRJNOTE'));
			$PRJSTAT 		= $this->input->post('PRJSTAT');
			$PRJCURR		= 'IDR';
			$PRJCOST 		= 0;		
			$Patt_Year		= date('Y',strtotime($this->input->post('PRJDATE')));
			$Patt_Number	= $resC + 1;
			$PRJTYPE		= 2; // 1 HOLDING COMPANY, 2 COMPANY, 3 BUDGET PERIODE

			$syncPRJHO		= "$PRJCODE_HO~$PRJCODE";
			
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
					}
					else
					{					
						$user_to	= explode ("|",$sel_pm);
						$user_ID	= $user_to[0];			
						$PRJ_MNG	= "$TASKD_EMPID2;$user_ID";
					}
				}
			}
			
			$projectheader = array('proj_Number' 	=> $proj_Number,
									'PRJCODE'		=> $PRJCODE,
									'PRJCODE_HO'	=> $PRJCODE_HO,
									'PRJPERIOD'		=> $PRJCODE,
									'PRJPERIOD_P'	=> $PRJCODE_HO,
									'PRJCNUM'		=> $PRJCNUM,
									'PRJNAME'		=> $PRJNAME,
									'PRJLOCT'		=> $PRJLOCT,
									'PRJADD'		=> $PRJADD,
									'PRJTELP'		=> $PRJTELP,
									'PRJDATE'		=> $PRJDATE,
									'PRJDATE_CO'	=> $PRJDATE_CO,
									'PRJEDAT'		=> $PRJEDAT,
									'PRJCURR'		=> $PRJCURR,
									'CURRRATE'		=> 1,
									'PRJBOQ'		=> $PRJCOST,
									'PRJSTAT'		=> $PRJSTAT,
									'PRJNOTE'		=> $PRJNOTE,
									'PRJ_MNG'		=> $PRJ_MNG,
									'PRJCATEG'		=> $PRJCATEG,
									'PRJSCATEG'		=> $PRJSCATEG,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Number'	=> $Patt_Number,
									'PRJTYPE'		=> $PRJTYPE);
			$this->m_listcomp->add($projectheader);
			
			// START : CREATE LABA RUGI
				$LR_CODE	= date('YmdHis');
				$PERIODE	= date('Y-m-d');
				$LR_CREATER	= $this->session->userdata['Emp_ID'];
				$LR_CREATED	= date('Y-m-d H:i:s');
				
				$projectLR 	= array('LR_CODE'			=> $LR_CODE,
									'PERIODE'			=> $PERIODE,
									'PRJCODE'			=> $PRJCODE,
									'PRJNAME'			=> $PRJNAME,
									'PRJCOST'			=> $PRJCOST,
									'LR_CREATER'		=> $LR_CREATER,
									'LR_CREATED'		=> $LR_CREATED);
				$this->m_listcomp->addLR($projectLR);
			// END : UPDATE LABA RUGI

			$sqlCOA	= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
			$resCOA = $this->db->count_all($sqlCOA);

			// START : COPY COA
				$ORD_ID		= $resCOA;
				$sqlAcc		= "SELECT Acc_ID, Account_Class, Account_Number, Account_NameEn, Account_NameId, Account_Category,
									Account_Level, Acc_DirParent, Acc_ParentList, Acc_StatusLinked, Company_ID, Default_Acc,
									Currency_id, Base_OpeningBalance, Base_Debet, Base_Kredit, Base_Debet_tax, Base_Kredit_tax,
									IsInterCompany, isCostComponent, isOnDuty, isFOHCost, Base_Debet2, Base_Kredit2,
									Base_Debet_tax2, Base_Kredit_tax2, COGSReportID, isHO, syncPRJ, isLast
								FROM tbl_chartaccount
								WHERE 
									PRJCODE = '$PRJCODE_HO'";
				$resAcc 	= $this->db->query($sqlAcc)->result();
				foreach($resAcc as $rowAcc) :
					$ORD_ID				= $ORD_ID + 1;
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

					//if($isHO == 1)
					if($Account_Class == 3 || $Account_Class == 4)
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

					//if($isHO == 1)
					if($Account_Class == 3 || $Account_Class == 4)
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
												(ORD_ID, PRJCODE, PRJCODE_HO, Acc_ID, Account_Class, Account_Number, Account_NameEn, Account_NameId, 
												Account_Category, Account_Level, Acc_DirParent, Acc_ParentList, Acc_StatusLinked,
												Company_ID, Default_Acc, Currency_id, Base_OpeningBalance, Base_Debet, Base_Kredit, 
												Base_Debet_tax, Base_Kredit_tax, IsInterCompany, isCostComponent, isOnDuty, isFOHCost,
												Base_Debet2, Base_Kredit2, Base_Debet_tax2, Base_Kredit_tax2, COGSReportID, isHO,
												syncPRJ, isLast) 
											VALUES 
												($ORD_ID, '$PRJCODE', '$PRJCODE_HO', '$Acc_ID', '$Account_Class', '$Account_Number', '$Account_NameEn', 
												'$Account_NameId', '$Account_Category', '$Account_Level', '$Acc_DirParent', 
												'$Acc_ParentList', '$Acc_StatusLinked', '$Company_ID', '$Default_Acc','$Currency_id', 
												'$Base_OpeningBalance', '$Base_Debet', '$Base_Kredit', '$Base_Debet_tax', 
												'$Base_Kredit_tax', '$IsInterCompany', '$isCostComponent', '$isOnDuty', '$isFOHCost',
												'$Base_Debet2', '$Base_Kredit2', '$Base_Debet_tax2', '$Base_Kredit_tax2', 
												'$COGSReportID', '$isHO', '$syncPRJ', '$isLast')";
					$this->db->query($sqlInsrAcc);
				endforeach;	
			// END : COPY COA
			
			// UPDATE SYNC KHUSUS UNTUK CLASS KAS/BANK (3,4) AGAR DISET KE SEMUA PROYEK
				$syncPRALL	= '';
				$i			= 0;
				$sqlPRJALL	= "SELECT DISTINCT PRJCODE FROM tbl_project";
				$resPRJALL 	= $this->db->query($sqlPRJALL)->result();
				foreach($resPRJALL as $rowPRJALL) :
					$i			= $i + 1;
					$PRJCODE1 	= $rowPRJALL->PRJCODE;
					if($i == 1)
					{
						$syncPRALL = $PRJCODE1;
					}
					else
					{
						$syncPRALL	= "$syncPRALL~$PRJCODE1";
					}
				endforeach;
				
				$sqlUpdPRJ			= "UPDATE tbl_chartaccount SET syncPRJ = '$syncPRALL' WHERE Account_Class IN (3,4) AND isLast = 1";
				$this->db->query($sqlUpdPRJ);
					
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
			
			$url			= site_url('c_comprof/C_c0mPL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
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
            $this->m_listproject->updatePict($PRJCODE, $nameFile);
         }
		 
         $sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_comprof/C_c0mPL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function u180c2gdt()
	{
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
			$data['task'] 		= 'edit';
			$data['h2_title']	= 'Edit Project';
			$data['form_action']= site_url('c_comprof/C_c0mPL15t/update_process');
			$data['backURL'] 	= site_url('c_comprof/C_c0mPL15t/');
			$data['MenuCode'] 		= 'MN403';

			// GET MENU DESC
				$mnCode				= 'MN403';
				$data["MenuApp"] 	= 'MN403';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$getproject = $this->m_listcomp->get_PROJ_by_number($PRJCODE)->row();
					
			$data['default']['proj_Number'] = $getproject->proj_Number;
			$data['default']['PRJCODE'] 	= $getproject->PRJCODE;
			$data['default']['PRJCODE_HO'] 	= $getproject->PRJCODE_HO;
			$data['default']['PRJCNUM'] 	= $getproject->PRJCNUM;
			$data['default']['PRJNAME'] 	= $getproject->PRJNAME;
			$data['default']['PRJLOCT'] 	= $getproject->PRJLOCT;
			$data['default']['PRJCATEG'] 	= $getproject->PRJCATEG;
			$data['default']['PRJSCATEG'] 	= $getproject->PRJSCATEG;
			$data['default']['PRJDATE'] 	= $getproject->PRJDATE;
			$data['default']['PRJDATE_CO'] 	= $getproject->PRJDATE_CO;
			$data['default']['PRJEDAT'] 	= $getproject->PRJEDAT;
			$PRJEDAT						= $getproject->PRJEDAT;

			$data['default']['PRJPERIOD'] 	= $getproject->PRJPERIOD;
			$data['default']['PRJBOQ'] 		= $getproject->PRJBOQ;
			$data['default']['PRJRAP'] 		= $getproject->PRJRAP;
			$data['default']['PRJLKOT'] 	= $getproject->PRJLKOT;
			$data['default']['PRJCBNG']		= $getproject->PRJCBNG;
			$data['default']['PRJTELP']		= $getproject->PRJTELP;
			$data['default']['PRJADD']		= $getproject->PRJADD;
			$data['default']['PRJSTAT'] 	= $getproject->PRJSTAT;
			$data['default']['PRJNOTE'] 	= $getproject->PRJNOTE;
			$data['default']['isHO']		= $getproject->isHO;
			
			$data['default']['ISCHANGE']	= $getproject->ISCHANGE;
			$data['default']['REFCHGNO']	= $getproject->REFCHGNO;
			$data['default']['PRJCOST2'] 	= $getproject->PRJCOST2;
			$data['default']['CHGUSER'] 	= $getproject->CHGUSER;
			$data['default']['CHGSTAT'] 	= $getproject->CHGSTAT;
			$data['default']['PRJ_MNG'] 	= $getproject->PRJ_MNG;
			$data['default']['PRJ_IMGNAME'] = $getproject->PRJ_IMGNAME;
			$data['default']['Patt_Year'] 	= $getproject->Patt_Year;
			$data['default']['Patt_Number'] = $getproject->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getproject->proj_Number;
				$MenuCode 		= 'MN403';
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
			
			$this->load->view('v_company/v_listcomp/v_listcomp_form', $data);
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
			
			$proj_Number	= $this->input->post('proj_Number');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE_HO 	= $this->input->post('PRJCODE_HO');
			$PRJNAME 		= $this->input->post('PRJNAME');
			$PRJ_MNG1 		= $this->input->post('PRJ_MNG');
			$PRJDATE		= date('Y-m-d',strtotime($this->input->post('PRJDATE')));
			$PRJDATE_CO		= date('Y-m-d',strtotime($this->input->post('PRJDATE')));
			$PRJEDAT		= date('Y-m-d',strtotime($this->input->post('PRJDATE')));
			$PRJCNUM 		= $this->input->post('PRJCNUM');
			$PRJCATEG 		= $this->input->post('PRJCATEG');
			$PRJSCATEG 		= $this->input->post('PRJSCATEG');
			$PRJLOCT 		= addslashes($this->input->post('PRJLOCT'));
			$PRJTELP		= $this->input->post('PRJTELP');
			$PRJADD 		= addslashes($this->input->post('PRJADD'));
			$PRJNOTE		= addslashes($this->input->post('PRJNOTE'));
			$PRJSTAT 		= $this->input->post('PRJSTAT');
			$PRJCURR		= 'IDR';
			$PRJCOST 		= 0;		
			$Patt_Year		= date('Y',strtotime($this->input->post('PRJDATE')));
			// DI FORM INI HANYA UNTUK CREATE COMPANY, BUKAN HOLDING COMPANY. HC ADA DI MENU PROFILE PERUSAHAAN
			$PRJTYPE		= 2; // 1 HOLDING COMPANY, 2 COMPANY, 3 BUDGET PERIODE
			echo "PRJCODE = $PRJCODE";
			return false;
			$syncPRJHO		= "$PRJCODE_HO~$PRJCODE";
			
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
					}
					else
					{					
						$user_to	= explode ("|",$sel_pm);
						$user_ID	= $user_to[0];			
						$PRJ_MNG	= "$TASKD_EMPID2;$user_ID";
					}
				}
			}
			
			$projectheader 	= array('PRJCODE'		=> $PRJCODE,
									'PRJCODE_HO'	=> $PRJCODE_HO,
									'PRJPERIOD'		=> $PRJCODE,
									'PRJPERIOD_P'	=> $PRJCODE_HO,
									'PRJCNUM'		=> $PRJCNUM,
									'PRJNAME'		=> $PRJNAME,
									'PRJLOCT'		=> $PRJLOCT,
									'PRJADD'		=> $PRJADD,
									'PRJTELP'		=> $PRJTELP,
									'PRJDATE'		=> $PRJDATE,
									'PRJDATE_CO'	=> $PRJDATE_CO,
									'PRJEDAT'		=> $PRJEDAT,
									'PRJCURR'		=> $PRJCURR,
									'CURRRATE'		=> 1,
									'PRJBOQ'		=> $PRJCOST,
									'PRJSTAT'		=> $PRJSTAT,
									'PRJNOTE'		=> $PRJNOTE,
									'PRJ_MNG'		=> $PRJ_MNG,
									'PRJCATEG'		=> $PRJCATEG,
									'PRJSCATEG'		=> $PRJSCATEG,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Number'	=> $Patt_Number,
									'PRJTYPE'		=> $PRJTYPE);
			$this->m_listproject->update($PRJCODE, $projectheader);

			// CHECK COA
				$sqlCOA	= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
				$resCOA = $this->db->count_all($sqlCOA);

				if($resCOA == 0)
				{
					$ORD_ID			= 0;
					// START : COPY COA
						$sqlAcc		= "SELECT Acc_ID, Account_Class, Account_Number, Account_NameEn, Account_NameId, Account_Category,
											Account_Level, Acc_DirParent, Acc_ParentList, Acc_StatusLinked, Company_ID, Default_Acc,
											Currency_id, Base_OpeningBalance, Base_Debet, Base_Kredit, Base_Debet_tax, Base_Kredit_tax,
											IsInterCompany, isCostComponent, isOnDuty, isFOHCost, Base_Debet2, Base_Kredit2,
											Base_Debet_tax2, Base_Kredit_tax2, COGSReportID, isHO, syncPRJ, isLast
										FROM tbl_chartaccount
										WHERE 
											PRJCODE = '$PRJCODE_HO'";
						$resAcc 	= $this->db->query($sqlAcc)->result();
						foreach($resAcc as $rowAcc) :
							$ORD_ID				= $ORD_ID + 1;
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

							//if($isHO == 1)
							if($Account_Class == 3 || $Account_Class == 4)
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

							//if($isHO == 1)
							if($Account_Class == 3 || $Account_Class == 4)
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
														(ORD_ID, PRJCODE, PRJCODE_HO, Acc_ID, Account_Class, Account_Number, Account_NameEn, Account_NameId, 
														Account_Category, Account_Level, Acc_DirParent, Acc_ParentList, Acc_StatusLinked,
														Company_ID, Default_Acc, Currency_id, Base_OpeningBalance, Base_Debet, Base_Kredit, 
														Base_Debet_tax, Base_Kredit_tax, IsInterCompany, isCostComponent, isOnDuty, isFOHCost,
														Base_Debet2, Base_Kredit2, Base_Debet_tax2, Base_Kredit_tax2, COGSReportID, isHO,
														syncPRJ, isLast) 
													VALUES 
														($ORD_ID, '$PRJCODE', '$PRJCODE_HO', '$Acc_ID', '$Account_Class', '$Account_Number', '$Account_NameEn', 
														'$Account_NameId', '$Account_Category', '$Account_Level', '$Acc_DirParent', 
														'$Acc_ParentList', '$Acc_StatusLinked', '$Company_ID', '$Default_Acc','$Currency_id', 
														'$Base_OpeningBalance', '$Base_Debet', '$Base_Kredit', '$Base_Debet_tax', 
														'$Base_Kredit_tax', '$IsInterCompany', '$isCostComponent', '$isOnDuty', '$isFOHCost',
														'$Base_Debet2', '$Base_Kredit2', '$Base_Debet_tax2', '$Base_Kredit_tax2', 
														'$COGSReportID', '$isHO', '$syncPRJ', '$isLast')";
							$this->db->query($sqlInsrAcc);
						endforeach;	
					// END : COPY COA
					
					// UPDATE SYNC KHUSUS UNTUK CLASS KAS/BANK (3,4) AGAR DISET KE SEMUA PROYEK
						$syncPRALL	= '';
						$i			= 0;
						$sqlPRJALL	= "SELECT DISTINCT PRJCODE FROM tbl_project";
						$resPRJALL 	= $this->db->query($sqlPRJALL)->result();
						foreach($resPRJALL as $rowPRJALL) :
							$i			= $i + 1;
							$PRJCODE1 	= $rowPRJALL->PRJCODE;
							if($i == 1)
							{
								$syncPRALL = $PRJCODE1;
							}
							else
							{
								$syncPRALL	= "$syncPRALL~$PRJCODE1";
							}
						endforeach;
						
						$sqlUpdPRJ			= "UPDATE tbl_chartaccount SET syncPRJ = '$syncPRALL' WHERE Account_Class IN (3,4) AND isLast = 1";
						$this->db->query($sqlUpdPRJ);
				}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $proj_Number;
				$MenuCode 		= 'MN403';
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
			
			redirect('c_comprof/C_c0mPL15t/');
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

  	function get_AllDataPRJ() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$secURL		= $_GET['id'];
		
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
			
			$columns_valid 	= array("");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_projectlist->get_AllDataC($DefEmp_ID, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_projectlist->get_AllDataL($DefEmp_ID, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PRJCODE 		= $dataI['PRJCODE'];
				$PRJCODE_HO		= $dataI['PRJCODE_HO'];
				$PRJPERIOD		= $dataI['PRJPERIOD'];
				$PRJCNUM		= $dataI['PRJCNUM'];
				$PRJNAME		= $dataI['PRJNAME'];
				$PRJLOCT		= $dataI['PRJLOCT'];
				$PRJCOST		= $dataI['PRJCOST'];
				$PRJDATE		= $dataI['PRJDATE'];
				$myDateProj 	= $dataI['PRJDATE'];
				$PRJEDAT		= $dataI['PRJEDAT'];
				$PRJSTAT		= $dataI['PRJSTAT'];
				$isActif 		= $dataI['PRJSTAT'];
				
				//$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
				$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
				$result = $this->db->query($sqlX)->result();
				foreach($result as $rowx) :
					$PRJNAME1	= $rowx->PRJNAME;
				endforeach;
				
				if($PRJSTAT == 0) $PRJSTATDesc = "New";
				elseif($PRJSTAT == 1) $PRJSTATDesc = "Confirm";		
			
				if($myDateProj == '0000-00-00')
				{
					$sqlX = "SELECT PRJDATE FROM tbl_project WHERE PRJCODE = '$prjcode'";
					$result = $this->db->query($sqlX)->result();
					foreach($result as $rowx) :
						$PRJDATE		= $rowx->PRJDATE;
					endforeach;
				}
				
				$TOTBP		= 0;	// Budget Plan
				$TOTADD		= 0;
				$TOTUSEDM	= 0;
				$sqlBudg 	= "SELECT SUM(ITM_BUDG) AS TOTBP, SUM(ADD_JOBCOST) AS TOTADD, SUM(ITM_USED_AM) AS TOTUSEDM FROM tbl_joblist_detail 
								WHERE ISLAST = 1 AND PRJCODE = '$PRJCODE'";
				$resBudg 	= $this->db->query($sqlBudg)->result();
				foreach($resBudg as $rowBT) :
					$TOTBP		= $rowBT->TOTBP;
					$TOTADD		= $rowBT->TOTADD;
					$TOTUSEDM	= $rowBT->TOTUSEDM;
				endforeach;
				$TOTBUDG	= $TOTBP + $TOTADD;
				if($TOTBUDG == 0)
					$TOTBUDG	= 1;
				
				$PERCUSED	= round($TOTUSEDM / $TOTBUDG * 100, 0);
				if($PERCUSED <= 25)
					$GRFCOL	= 'danger';
				elseif($PERCUSED <= 50)
					$GRFCOL	= 'warning';
				elseif($PERCUSED <= 75)
					$GRFCOL	= 'primary';
				elseif($PERCUSED <= 100)
					$GRFCOL	= 'success';
					
				if($isActif == 1)
				{
					$isActDesc 	= 'Active';
					$STATCOL	= 'success';
				}
				else
				{
					$isActDesc 	= 'Not Active';
					$STATCOL	= 'danger';
				}
				
                $secUpd				= site_url($secURL.$this->url_encryption_helper->encode_url($PRJCODE));               	
				$output['data'][] 	= array("<div style='text-align:center; white-space:nowrap'>".anchor("$secUpd",$PRJCODE,array('class' => 'update')).' '."</div>",
										"$PRJNAME1</em>",
										"<div style='text-align:center; white-space:nowrap'>$PRJDATE</div>",
										"<div style='text-align:center; white-space:nowrap'>$PRJEDAT</div>",
										"<div style='text-align:center; white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".$isActDesc."</span></div>",
										"<div class='progress-group'><div class='progress sm'><div class='progress-bar progress-bar-".$GRFCOL."' style='width: ".$PERCUSED."%'></div></div></div>",
										"<div class='progress-group'><span class='progress-number'><b>".$PERCUSED."</b>/100</span></div>");
				$noU			= $noU + 1;
			}
			
			

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}