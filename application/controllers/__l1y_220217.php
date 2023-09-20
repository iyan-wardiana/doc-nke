<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class __L1y extends CI_Controller
 {
  	function __construct() // GOOD
	{
		parent::__construct();

		$this->load->library('session');
	}

 	public function index()
	{
		$this->load->model('login_model', '', TRUE);
		$this->load->library('session');
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;
			$comp_name	= $therow->comp_name;
			$comp_init	= $therow->comp_init;
			$sysMode	= $therow->sysMode;
			$LastModeD	= $therow->LastModeD;
			$sysMnt		= $therow->sysMnt;
			$LastMntD	= $therow->LastMntD;
			$version	= $therow->version;
			$app_body	= $therow->app_body;
			$vend_app	= $therow->vend_app;
			$comp_color	= $therow->comp_color;
			$app_notes	= $therow->app_notes;
			$srvURL 	= $_SERVER['SERVER_ADDR'];

			$sqlAppDesc	= "SELECT TS_DESC, TS_AC FROM tbl_trashsys";
			$resAppDesc = $this->db->query($sqlAppDesc)->result();
			foreach($resAppDesc as $rowDesc) :
				$TSDESC = $rowDesc->TS_DESC;
				$TSAC 	= $rowDesc->TS_AC;
			endforeach;
		endforeach;
		$srvURL 	= $_SERVER['SERVER_ADDR'];
		
		if($this->crypt180c1c->sys_decsryptxx($srvURL, $appName, $app_notes, $TSDESC) == TRUE)
			$url	= site_url('__l1y/y1I__/?id='.$this->url_encryption_helper->encode_url($appName));
		else
		{
			$data 	= array('username' => '', 'appName' => $appName, 'comp_name' => $comp_name, 'comp_init' => $comp_init, 'LangID' => 'IND', 'COMPANY_ID' => $comp_init, 'sysMode' => $sysMode, 'LastModeD' => $LastModeD, 'sysMnt' => $sysMnt, 'LastMntD' => $LastMntD, 'vers' => $version, 'appBody' => $app_body, 'vend_app' => $vend_app, 'comp_color' => $comp_color, 'TSAC' => $TSAC, 'TSDESC' => $TSDESC);
			
			$this->session->set_userdata($data);
			
			$url	= site_url('__l1y/y2I__/?id='.$this->url_encryption_helper->encode_url($appName));
		}
		redirect($url);
	}
	
	function y1I__()
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
				
		$this->load->model('login_model', '', TRUE);
		if($this->session->userdata('login') == TRUE)
		{
			$url		= site_url('__180c2f/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			$this->load->view('login');
		}
	}
	
	function y2I__()
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		$this->load->model('login_model', '', TRUE);
		$this->load->view('login2');
	}
	
	function y1I__vl()
	{
		$appName 	= date('m/d/Y');
		$url		= site_url('__l1y/y1I__vln3xt/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function y1I__vln3xt()
	{
		$this->load->model('login_model', '', TRUE);
		$this->load->view('visitor_sign');
	}
        
	function login_process()
	{
		date_default_timezone_set("Asia/Jakarta");

		$this->load->model('login_model', '', TRUE);
		
		$username 		= $this->input->post('username');
		$password 		= md5($this->input->post('password'));
		
		$sqlUNC			= "SELECT Emp_ID, log_username, log_password FROM tbl_employee";
		$resultUNC 		= $this->db->query($sqlUNC)->result();
		foreach($resultUNC as $rowUNC) :
			$EmpID				= $rowUNC->Emp_ID;
			$log_usernameX		= $rowUNC->log_username;
			$log_passwordX		= $rowUNC->log_password;
			$newPSW				= md5($log_passwordX);
			$sqlAppDesc	= "SELECT TS_DESC FROM tbl_trashsys";
			$resAppDesc = $this->db->query($sqlAppDesc)->result();
			foreach($resAppDesc as $rowDesc) :
				$TS_DESC 	= $rowDesc->TS_DESC;
			endforeach;
		endforeach;
		
		$comp_color	= "#ECF0F5";
  		$canLogIn 	= true;
		$resOn		= 1;
		if ($this->login_model->check_user($username, $password) == TRUE)
		{
			$sql		= "SELECT Emp_ID, EmpNoIdentity, First_Name, Middle_Name, Last_Name, Employee_status, Emp_DeptCode, 
								writeEMP, editEMP, readEMP, log_passHint, FlagUSER, FlagAppCheck, isSDBP, Dept_Code AS DEPCODE
							FROM tbl_employee
							WHERE 
								log_username = '$username' 
								AND log_password = '$password'";
			$result		= $this->db->query($sql)->result();
			foreach($result as $therow) :
				$Emp_ID 		= $therow->Emp_ID;	
				$First_Name 	= $therow->First_Name;	
				$Middle_Name 	= $therow->Middle_Name;	
				$Last_Name 		= $therow->Last_Name;
				$log_passHint	= $therow->log_passHint;
				$FlagUSER 		= $therow->FlagUSER;
				$FlagAppCheck	= $therow->FlagAppCheck;
				$isSDBP 		= $therow->isSDBP;
				$Emp_DeptCode 	= $therow->Emp_DeptCode;
				$readEMP 		= $therow->readEMP;	
				$writeEMP 		= $therow->writeEMP;
				$editEMP 		= $therow->editEMP;
				$readEMP 		= $therow->readEMP;
				$DEPCODE 		= $therow->DEPCODE;
				if($DEPCODE == '')
					$DEPCODE 	= $Emp_DeptCode;
			endforeach;
			
			$updLog	= "UPDATE tbl_employee SET OLStat = 1, Log_Act = 1 WHERE Emp_ID = '$Emp_ID'";
			$this->db->query($updLog);
			
			$completeName 		= "$First_Name";
			if($Last_Name != '')
				$completeName 	= "$First_Name $Last_Name";
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName 	= $therow->app_name;
				$vend_app 	= $therow->vend_app;
				$comp_name	= $therow->comp_name;
				$comp_init	= $therow->comp_init;
				$app_notes	= $therow->app_notes;
				$maxLimit	= $therow->maxLimit;
				$nSELP 		= $therow->nSELP;
				$sysMode	= $therow->sysMode;
				$LastModeD	= $therow->LastModeD;
				$sysMnt		= $therow->sysMnt;
				$LastMntD	= $therow->LastMntD;
				$version	= $therow->version;
				$app_body	= $therow->app_body;
				$comp_color	= $therow->comp_color;
			endforeach;
			$LOG_DATE1 	= date('Y-m-d');
			$LOG_DATE	= date('Y-m-d', strtotime($LOG_DATE1));
			$LastModeD	= date('Y-m-d', strtotime($LastModeD));
			
			if($sysMode == 1 && $LastModeD < $LOG_DATE)
			{
				$canLogIn 	= false;
				$resOn		= 2;
			}
			
			/*if($sysMnt == 1 && $LastMntD < $LOG_DATE)
			{
				$canLogIn 	= false;
				$resOn		= 3;
			}*/
			
			// Get session project firt time
			$EmpID 			= $Emp_ID;
			$getCount		= "tbl_employee_proj WHERE Emp_ID = '$EmpID'";
			$resGetCount	= $this->db->count_all($getCount);
			$collProject1	= '';
			$projCode		= '';
				
			if($resGetCount > 0)
			{
				$noU			= 0;
				$getData		= "SELECT A.Emp_ID, A.proj_Code, B.PRJNAME
									FROM tbl_employee_proj A
									INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
									WHERE A.Emp_ID = '$EmpID' LIMIT 1";
				$resGetData 	= $this->db->query($getData)->result();
				foreach($resGetData as $rowData) :
					$projCode 	= $rowData->proj_Code;
					$PRJCODE 	= $rowData->proj_Code;
				endforeach;
			}
			else
			{
				$FlagU	= "";
				$sql	= "SELECT FlagUSER FROM tbl_employee WHERE log_username = '$username' AND log_password = '$password'";
				$result	= $this->db->query($sql)->result();
				foreach($result as $therow) :
					$FlagU 	= $therow->FlagUSER;
				endforeach;
				$PRJC	= "";
				$sqlPRJ	= "SELECT PRJCODE FROM tbl_project WHERE PRJTYPE = 1";
				$resPRJ	= $this->db->query($sqlPRJ)->result();
				foreach($resPRJ as $rowPRJ) :
					$PRJC 	= $rowPRJ->PRJCODE;
				endforeach;
				if($FlagU == 'SUPERADMIN')
				{
					$sqlIns	= "INSERT INTO tbl_employee_proj (Emp_ID, proj_Code) VALUES ('$EmpID', '$PRJC')";
					$this->db->query($sqlIns);
				}
				$getData		= "SELECT A.Emp_ID, A.proj_Code, B.PRJNAME 
									FROM tbl_employee_proj A
									INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
									WHERE A.Emp_ID = '$EmpID' GROUP BY proj_Code LIMIT 1";
				$resGetData 	= $this->db->query($getData)->result();
				foreach($resGetData as $rowData) :
					$projCode 	= $rowData->proj_Code;
					$PRJCODE 	= $rowData->proj_Code;
				endforeach;
			}
			
			if($projCode == '')
			{
				$updLog	= "UPDATE tbl_employee SET OLStat = 0 WHERE Emp_ID = '$Emp_ID'";
				$this->db->query($updLog);
			
				echo "This user is not setting for project. Please contact Administrators.";
				return false;
			}

			$dataSessSrc = array('sessTempProj' => $projCode);
			$this->session->set_userdata('SessTempProject', $dataSessSrc);

			$Lang_ID	= 'IND';
			$APPLEV		= 'PRJ';
			$SETDPURCH 	= 0;
			$sqlLANG	= "SELECT Lang_ID, APPLEV, SET_DEPTPURCH from tglobalsetting";
			$resLANG	= $this->db->query($sqlLANG)->result();
			foreach($resLANG as $rowLANG) :
				$Lang_ID 	= $rowLANG->Lang_ID;
				$APPLEV 	= $rowLANG->APPLEV;
				$SETDPURCH 	= $rowLANG->SET_DEPTPURCH;
			endforeach;
			
			date_default_timezone_set("Asia/Jakarta");
			$LOG_CODEY 	= date('Y');
			$LOG_CODEM 	= date('m');
			$LOG_CODED 	= date('d');
			$LOG_CODEH 	= date('H-i-s');
			$LOG_CODEH 	= date('H-i-s');
			$LOG_CODE 	= "$Emp_ID$LOG_CODEY$LOG_CODEM$LOG_CODED-$LOG_CODEH";		
			$LOG_IND 	= date('Y-m-d H:i:s');
			$srvURL 	= $_SERVER['SERVER_ADDR'];
			$COMPANY_ID	= $comp_init;
			$sysLog		= $this->crypt180c1c->sys_decsrypt($srvURL, $appName, $app_notes, $TS_DESC);
			

			$sqlPRJT	= "SELECT PRJSCATEG FROM tbl_project WHERE PRJTYPE = 1";
			$resPRJT	= $this->db->query($sqlPRJT)->result();
			foreach($resPRJT as $rowPRJT) :
				$PRJSCATEGX 	= $rowPRJT->PRJSCATEG;
			endforeach;
			$PRJSCATEG 	= $PRJSCATEGX ?: 1;

			$data 		= array('username' => $username,'password' => $password, 'Emp_ID' => $Emp_ID, 'First_Name' => $First_Name, 'Last_Name' => $Last_Name, 'completeName' => $completeName, 'appName' => $appName, 'comp_name' => $comp_name, 'comp_init' => $comp_init, 'proj_Code' => $PRJCODE, 'Emp_DeptCode' => $Emp_DeptCode, 'writeEMP' => $writeEMP, 'editEMP' => $editEMP, 'readEMP' => $readEMP, 'log_passHint' => $log_passHint, 'isSDBP' => $isSDBP, 'FlagUSER' => $FlagUSER, 'FlagAppCheck' => $FlagAppCheck, 'LOG_CODE' => $LOG_CODE, 'LangID' => 'IND', 'COMPANY_ID' => $COMPANY_ID, 'login' => $sysLog, 'maxLimit' => $maxLimit, 'nSELP' => $nSELP, 'sysMode' => $sysMode, 'LastModeD' => $LastModeD, 'sysMnt' => $sysMnt, 'LastMntD' => $LastMntD, 'APPLEV' => $APPLEV, 'DEPCODE' => $DEPCODE, 'vers' => $version, 'appBody' => $app_body, 'vend_app' => $vend_app, 'PRJSCATEG' => $PRJSCATEG, 'SETDPURCH' => $SETDPURCH, 'comp_color' => $comp_color);
			
			$this->session->set_userdata($data);
						
			$appName1	= $this->encrypt->encode($appName);
			$appName2	= $this->encrypt->decode($appName1);
			
			$insLog 	= array('LOG_CODE' 	=> $LOG_CODE,
								'LOG_EMP' 	=> $Emp_ID,
								'LOG_IND' 	=> $LOG_IND);
							
			$this->login_model->addLogin($insLog);
			
			$getLogC	= "tbl_login_hist WHERE LOG_EMP = '$Emp_ID'";
			$resLogC	= $this->db->count_all($getLogC);
			$resLogCT	= $resLogC + 1;
			$sqlUpdLogC	= "UPDATE tbl_employee SET Log_Count = $resLogCT, OLStat = 1 WHERE Emp_ID = '$Emp_ID'";
			$this->db->query($sqlUpdLogC);
			
			$dt1 		= strtotime("2017/04/01");
			$dt2 		= strtotime(date('Y/m/d'));
			$diff 		= abs($dt2-$dt1);
			$DayTot 	= $diff/86400;
			$Log_AVG	= $resLogCT / $DayTot * 100;
			
			$UpdLogAVG	= "UPDATE tbl_employee SET Log_AVG = $Log_AVG WHERE Emp_ID = '$Emp_ID'";
			$this->db->query($UpdLogAVG);
			
			// PEMISAHAN KATEGORI
				$LogC_Nev	= "SELECT COUNT(*) AS totNev FROM tbl_employee WHERE Log_AVG = 0";
				$LogC_Nev	= $this->db->query($LogC_Nev)->result();
				foreach($LogC_Nev as $rowData) :
					$totNev 	= $rowData->totNev;
				endforeach;
				
				$LogC_SomeT	= "SELECT COUNT(*) AS totSomeT FROM tbl_employee WHERE Log_AVG > 0 AND Log_AVG < 25";
				$LogC_SomeT	= $this->db->query($LogC_SomeT)->result();
				foreach($LogC_SomeT as $rowData) :
					$totSomeT 	= $rowData->totSomeT;
				endforeach;
				
				$LogC_Often	= "SELECT COUNT(*) AS totOften FROM tbl_employee WHERE Log_AVG > 25 AND Log_AVG < 50";
				$LogC_Often	= $this->db->query($LogC_Often)->result();
				foreach($LogC_Often as $rowData) :
					$totOften 	= $rowData->totOften;
				endforeach;
				
				$LogC_Excl	= "SELECT COUNT(*) AS totExcl FROM tbl_employee WHERE Log_AVG > 50";
				$LogC_Excl	= $this->db->query($LogC_Excl)->result();
				foreach($LogC_Excl as $rowData) :
					$totExcl 	= $rowData->totExcl;
				endforeach;
				
				$sqlConc	= "tbl_login_concl";
				$resConc	= $this->db->count_all($sqlConc);
				if($resConc == 0)
				{
					$UpdLogCONCL	= "INSERT INTO tbl_login_concl (LCONC_NEVER, LCONC_SOMET, LCONC_OFTEN, LCONC_FANTASTIC)
										VALUES ($totNev, $totSomeT, $totOften, $totExcl)";
					$this->db->query($UpdLogCONCL);
				}
				else
				{
					$UpdLogCONCL	= "UPDATE tbl_login_concl SET LCONC_NEVER = '$totNev', LCONC_SOMET = '$totSomeT', 
										LCONC_OFTEN = '$totOften', LCONC_FANTASTIC = '$totExcl'";
					$this->db->query($UpdLogCONCL);
				}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $Emp_ID;
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= '';
				$TTR_CATEG		= 'ENTER';
				
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			if($canLogIn == true)
			{
				$url		= site_url('__180c2f/?id='.$this->url_encryption_helper->encode_url($appName));
				redirect($url);
			}
			/*else
			{
				$this->load->view('lockscreen_lock');
			}*/
		}
		else
		{
			$canLogIn 	= false;
		}
		
		if($canLogIn == false)
		{
			$updLog	= "UPDATE tbl_employee SET OLStat = 0 WHERE Emp_ID = '$EmpID'";
			$this->db->query($updLog);
			
			$this->session->sess_destroy();
			if($resOn == 1)
				$message 	=  "<script>alert('Maaf, anda tidak bisa log in. Username tidak terdeteksi atau status Anda di non-aktifkan. Silahkan hubungi administrator.')</script>";
			elseif($resOn == 2)
				$message 	=  "<script>alert('Maaf, masa uji coba sudah berakhir. Silahkan hubungi administrator.')</script>";
			else
				$message 	=  "<script>alert('Maaf, masa layanan perawatan sudah berakhir. Silahkan hubungi administrator.')</script>";

			echo $message;
		}
		
		if (!$canLogIn)
		{
			$this->load->view('login');
		}
	}
	
	function logout()
	{
		$this->load->model('login_model', '', TRUE);
		
		date_default_timezone_set("Asia/Jakarta");
		
		$LOG_CODE 		= $this->session->userdata('LOG_CODE');
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				if(isset($this->session->userdata['Emp_ID']))
					$DefEmp_ID	= $this->session->userdata['Emp_ID'];
				else
					$DefEmp_ID	= 'TERM';
				
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= '';
				$TTR_CATEG		= 'TERMINATE';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
		
		$this->session->sess_destroy();
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
					
		$LOG_OUTD 		= date('Y-m-d H:i:s');
		$updLog 		= array('LOG_OUTD' 	=> $LOG_OUTD);
		
		$sqlUpdLogC	= "UPDATE tbl_employee SET OLStat = 0 WHERE Emp_ID = '$DefEmp_ID'";
		$this->db->query($sqlUpdLogC);
						
		$this->login_model->updateLogout($LOG_CODE, $updLog);
		
		$url			= site_url('__l1y/y1I__/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function lockSystem()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collData	= $_GET['id'];
		$collData	= $this->url_encryption_helper->decode_url($collData);
		$splitData	= explode("~", $collData);
		
		//$username = $this->session->userdata('username');
		$sysMode 	= 1;
		if(isset($this->session->userdata['sysMode']))
			$sysMode	= $this->session->userdata['sysMode'];

		$username	= $splitData[1];
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		if(isset($appName))
			$appName = $appName;
		else
			$appName = "";
		
		$data['username'] 	= $username;
		$data['appName'] 	= $appName;
		$data['collData'] 	= $collData;
		
		$this->load->view('lockscreen', $data);
	}
	
	function openLock()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$this->load->model('login_model', '', TRUE);

		$username 		= $this->session->userdata('username');
		$Emp_ID 		= $this->session->userdata('Emp_ID');
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$myempID 		= $this->input->post('mySessionEmp_ID');
		$password 		= md5($this->input->post('log_password'));
		
		$sqlEmp 		= "SELECT log_username, log_password FROM tbl_employee WHERE Emp_ID = '$myempID'";
		$resEmp 		= $this->db->query($sqlEmp)->result();
		foreach($resEmp as $row) :
			$username = $row->log_username;
			$password1 = $row->log_password;		
		endforeach;
		
		$sqlUNC			= "SELECT Emp_ID, log_username, log_password FROM tbl_employee";
		$resultUNC 		= $this->db->query($sqlUNC)->result();
		foreach($resultUNC as $rowUNC) :
			$EmpID				= $rowUNC->Emp_ID;
			$log_usernameX		= $rowUNC->log_username;
			$log_passwordX		= $rowUNC->log_password;
			$newPSW				= md5($log_passwordX);
			$sqlAppDesc		= "SELECT TS_DESC FROM tbl_trashsys";
			$resAppDesc = $this->db->query($sqlAppDesc)->result();
			foreach($resAppDesc as $rowDesc) :
				$TS_DESC 	= $rowDesc->TS_DESC;
			endforeach;
		endforeach;
		
  		$canLogIn 	= true;
		$resOn		= 1;
		if ($this->login_model->check_user($username, $password) == TRUE)
		{
			$sql		= "SELECT Emp_ID, EmpNoIdentity, First_Name, Middle_Name, Last_Name, Employee_status, Emp_DeptCode, 
								writeEMP, editEMP, readEMP, log_passHint, FlagUSER, FlagAppCheck, isSDBP, Emp_DeptCode AS DEPCODE
							FROM tbl_employee
							WHERE 
								log_username = '$username' 
								AND log_password = '$password'";
			$result		= $this->db->query($sql)->result();
			foreach($result as $therow) :
				$Emp_ID 		= $therow->Emp_ID;	
				$First_Name 	= $therow->First_Name;	
				$Middle_Name 	= $therow->Middle_Name;	
				$Last_Name 		= $therow->Last_Name;
				$log_passHint	= $therow->log_passHint;
				$FlagUSER 		= $therow->FlagUSER;
				$FlagAppCheck	= $therow->FlagAppCheck;
				$isSDBP 		= $therow->isSDBP;
				$Emp_DeptCode 	= $therow->Emp_DeptCode;
				$readEMP 		= $therow->readEMP;	
				$writeEMP 		= $therow->writeEMP;
				$editEMP 		= $therow->editEMP;
				$readEMP 		= $therow->readEMP;
				$DEPCODE 		= $therow->DEPCODE;
			endforeach;
			
			$updLog	= "UPDATE tbl_employee SET OLStat = 1, Log_Act = 1 WHERE Emp_ID = '$Emp_ID'";
			$this->db->query($updLog);
			
			$completeName 		= "$First_Name";
			if($Last_Name != '')
				$completeName 	= "$First_Name $Last_Name";
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName 	= $therow->app_name;
				$vend_app 	= $therow->vend_app;
				$comp_name	= $therow->comp_name;
				$comp_init	= $therow->comp_init;
				$app_notes	= $therow->app_notes;
				$maxLimit	= $therow->maxLimit;
				$nSELP 		= $therow->nSELP;
				$sysMode	= $therow->sysMode;
				$LastModeD	= $therow->LastModeD;
				$sysMnt		= $therow->sysMnt;
				$LastMntD	= $therow->LastMntD;
				$version	= $therow->version;
				$app_body	= $therow->app_body;
			endforeach;
			$LOG_DATE1 	= date('Y-m-d');
			$LOG_DATE	= date('Y-m-d', strtotime($LOG_DATE1));
			$LastModeD	= date('Y-m-d', strtotime($LastModeD));
			
			if($sysMode == 1 && $LastModeD < $LOG_DATE)
			{
				$canLogIn 	= false;
				$resOn		= 2;
			}
			
			/*if($sysMnt == 1 && $LastMntD < $LOG_DATE)
			{
				$canLogIn 	= false;
				$resOn		= 3;
			}*/
			
			// Get session project firt time
			$EmpID 			= $Emp_ID;
			$getCount		= "tbl_employee_proj WHERE Emp_ID = '$EmpID'";
			$resGetCount	= $this->db->count_all($getCount);
			$collProject1	= '';
			$projCode		= '';
				
			if($resGetCount > 0)
			{
				$noU			= 0;
				$getData		= "SELECT A.Emp_ID, A.proj_Code, B.PRJNAME
									FROM tbl_employee_proj A
									INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
									WHERE A.Emp_ID = '$EmpID' GROUP BY proj_Code LIMIT 1";
				$resGetData 	= $this->db->query($getData)->result();
				foreach($resGetData as $rowData) :
					$projCode 	= $rowData->proj_Code;
					$PRJCODE 	= $rowData->proj_Code;
				endforeach;
			}
			else
			{
				$FlagU	= "";
				$sql	= "SELECT FlagUSER FROM tbl_employee WHERE log_username = '$username' AND log_password = '$password'";
				$result	= $this->db->query($sql)->result();
				foreach($result as $therow) :
					$FlagU 	= $therow->FlagUSER;
				endforeach;
				$PRJC	= "";
				$sqlPRJ	= "SELECT PRJCODE FROM tbl_project WHERE PRJTYPE = 1";
				$resPRJ	= $this->db->query($sqlPRJ)->result();
				foreach($resPRJ as $rowPRJ) :
					$PRJC 	= $rowPRJ->PRJCODE;
				endforeach;
				if($FlagU == 'SUPERADMIN')
				{
					$sqlIns	= "INSERT INTO tbl_employee_proj (Emp_ID, proj_Code) VALUES ('$EmpID', '$PRJC')";
					$this->db->query($sqlIns);
				}
				$getData		= "SELECT A.Emp_ID, A.proj_Code, B.PRJNAME 
									FROM tbl_employee_proj A
									INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
									WHERE A.Emp_ID = '$EmpID' GROUP BY proj_Code LIMIT 1";
				$resGetData 	= $this->db->query($getData)->result();
				foreach($resGetData as $rowData) :
					$projCode 	= $rowData->proj_Code;
					$PRJCODE 	= $rowData->proj_Code;
				endforeach;
			}
			
			if($projCode == '')
			{
				$updLog	= "UPDATE tbl_employee SET OLStat = 0 WHERE Emp_ID = '$Emp_ID'";
				$this->db->query($updLog);
			
				echo "This user is not setting for project. Please contact Administrators.";
				return false;
			}

			$dataSessSrc = array('sessTempProj' => $projCode);
			$this->session->set_userdata('SessTempProject', $dataSessSrc);

			$Lang_ID	= 'IND';
			$APPLEV		= 'PRJ';
			$SETDPURCH 	= 0;
			$sqlLANG	= "SELECT Lang_ID, APPLEV, SET_DEPTPURCH from tglobalsetting";
			$resLANG	= $this->db->query($sqlLANG)->result();
			foreach($resLANG as $rowLANG) :
				$Lang_ID 	= $rowLANG->Lang_ID;
				$APPLEV 	= $rowLANG->APPLEV;
				$SETDPURCH 	= $rowLANG->SET_DEPTPURCH;
			endforeach;
			
			date_default_timezone_set("Asia/Jakarta");
			$LOG_CODEY 	= date('Y');
			$LOG_CODEM 	= date('m');
			$LOG_CODED 	= date('d');
			$LOG_CODEH 	= date('H-i-s');
			$LOG_CODEH 	= date('H-i-s');
			$LOG_CODE 	= "$Emp_ID$LOG_CODEY$LOG_CODEM$LOG_CODED-$LOG_CODEH";		
			$LOG_IND 	= date('Y-m-d H:i:s');
			$srvURL 	= $_SERVER['SERVER_ADDR'];
			$COMPANY_ID	= $comp_init;
			$sysLog		= $this->crypt180c1c->sys_decsrypt($srvURL, $appName, $app_notes, $TS_DESC);
			

			$sqlPRJT	= "SELECT PRJSCATEG FROM tbl_project WHERE PRJTYPE = 1";
			$resPRJT	= $this->db->query($sqlPRJT)->result();
			foreach($resPRJT as $rowPRJT) :
				$PRJSCATEGX 	= $rowPRJT->PRJSCATEG;
			endforeach;
			$PRJSCATEG 	= $PRJSCATEGX ?: 1;

			$data 		= array('username' => $username,'password' => $password, 'Emp_ID' => $Emp_ID, 'First_Name' => $First_Name, 'Last_Name' => $Last_Name, 'completeName' => $completeName, 'appName' => $appName, 'comp_name' => $comp_name, 'comp_init' => $comp_init, 'proj_Code' => $PRJCODE, 'Emp_DeptCode' => $Emp_DeptCode, 'writeEMP' => $writeEMP, 'editEMP' => $editEMP, 'readEMP' => $readEMP, 'log_passHint' => $log_passHint, 'isSDBP' => $isSDBP, 'FlagUSER' => $FlagUSER, 'FlagAppCheck' => $FlagAppCheck, 'LOG_CODE' => $LOG_CODE, 'LangID' => 'IND', 'COMPANY_ID' => $COMPANY_ID, 'login' => $sysLog, 'maxLimit' => $maxLimit, 'nSELP' => $nSELP, 'sysMode' => $sysMode, 'LastModeD' => $LastModeD, 'sysMnt' => $sysMnt, 'LastMntD' => $LastMntD, 'APPLEV' => $APPLEV, 'DEPCODE' => $DEPCODE, 'vers' => $version, 'appBody' => $app_body, 'vend_app' => $vend_app, 'PRJSCATEG' => $PRJSCATEG, 'SETDPURCH' => $SETDPURCH);
			
			$this->session->set_userdata($data);
						
			$appName1	= $this->encrypt->encode($appName);
			$appName2	= $this->encrypt->decode($appName1);
			
			$insLog 	= array('LOG_CODE' 	=> $LOG_CODE,
								'LOG_EMP' 	=> $Emp_ID,
								'LOG_IND' 	=> $LOG_IND);
							
			$this->login_model->addLogin($insLog);
			
			$getLogC	= "tbl_login_hist WHERE LOG_EMP = '$Emp_ID'";
			$resLogC	= $this->db->count_all($getLogC);
			$resLogCT	= $resLogC + 1;
			$sqlUpdLogC	= "UPDATE tbl_employee SET Log_Count = $resLogCT, OLStat = 1 WHERE Emp_ID = '$Emp_ID'";
			$this->db->query($sqlUpdLogC);
			
			$dt1 		= strtotime("2017/04/01");
			$dt2 		= strtotime(date('Y/m/d'));
			$diff 		= abs($dt2-$dt1);
			$DayTot 	= $diff/86400;
			$Log_AVG	= $resLogCT / $DayTot * 100;
			
			$UpdLogAVG	= "UPDATE tbl_employee SET Log_AVG = $Log_AVG WHERE Emp_ID = '$Emp_ID'";
			$this->db->query($UpdLogAVG);
			
			// PEMISAHAN KATEGORI
				$LogC_Nev	= "SELECT COUNT(*) AS totNev FROM tbl_employee WHERE Log_AVG = 0";
				$LogC_Nev	= $this->db->query($LogC_Nev)->result();
				foreach($LogC_Nev as $rowData) :
					$totNev 	= $rowData->totNev;
				endforeach;
				
				$LogC_SomeT	= "SELECT COUNT(*) AS totSomeT FROM tbl_employee WHERE Log_AVG > 0 AND Log_AVG < 25";
				$LogC_SomeT	= $this->db->query($LogC_SomeT)->result();
				foreach($LogC_SomeT as $rowData) :
					$totSomeT 	= $rowData->totSomeT;
				endforeach;
				
				$LogC_Often	= "SELECT COUNT(*) AS totOften FROM tbl_employee WHERE Log_AVG > 25 AND Log_AVG < 50";
				$LogC_Often	= $this->db->query($LogC_Often)->result();
				foreach($LogC_Often as $rowData) :
					$totOften 	= $rowData->totOften;
				endforeach;
				
				$LogC_Excl	= "SELECT COUNT(*) AS totExcl FROM tbl_employee WHERE Log_AVG > 50";
				$LogC_Excl	= $this->db->query($LogC_Excl)->result();
				foreach($LogC_Excl as $rowData) :
					$totExcl 	= $rowData->totExcl;
				endforeach;
				
				$sqlConc	= "tbl_login_concl";
				$resConc	= $this->db->count_all($sqlConc);
				if($resConc == 0)
				{
					$UpdLogCONCL	= "INSERT INTO tbl_login_concl (LCONC_NEVER, LCONC_SOMET, LCONC_OFTEN, LCONC_FANTASTIC)
										VALUES ($totNev, $totSomeT, $totOften, $totExcl)";
					$this->db->query($UpdLogCONCL);
				}
				else
				{
					$UpdLogCONCL	= "UPDATE tbl_login_concl SET LCONC_NEVER = '$totNev', LCONC_SOMET = '$totSomeT', 
										LCONC_OFTEN = '$totOften', LCONC_FANTASTIC = '$totExcl'";
					$this->db->query($UpdLogCONCL);
				}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $Emp_ID;
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= '';
				$TTR_CATEG		= 'ENTER';
				
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			if($canLogIn == true)
			{
				$url		= site_url('__180c2f/?id='.$this->url_encryption_helper->encode_url($appName));
				redirect($url);
			}
		}
		else
		{
			$canLogIn 	= false;
		}
		
		if($canLogIn == false)
		{
			$updLog	= "UPDATE tbl_employee SET OLStat = 0 WHERE Emp_ID = '$EmpID'";
			$this->db->query($updLog);
			
			$this->session->sess_destroy();
			if($resOn == 1)
				$message 	=  "<script>alert('Maaf, anda tidak bisa log in. Username tidak terdeteksi atau status Anda di non-aktifkan. Silahkan hubungi administrator.')</script>";
			elseif($resOn == 2)
				$message 	=  "<script>alert('Maaf, masa uji coba sudah berakhir. Silahkan hubungi administrator.')</script>";
			else
				$message 	=  "<script>alert('Maaf, masa layanan perawatan sudah berakhir. Silahkan hubungi administrator.')</script>";

			echo $message;
		}
		
		if (!$canLogIn)
		{
			$this->load->view('login');
		}
	}
	
	function forgot_password()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['appName'] 	= $appName;
		
		$this->load->view('forgot_password', $data);
	}
        
	function reset_process()
	{
		$this->load->model('login_model', '', TRUE);
		
		date_default_timezone_set("Asia/Jakarta");
			
		$EMP_ID			= $this->input->post('EMP_ID');
		$EMAIL 			= $this->input->post('EMAIL');
		$NEW_PASS1 		= date('Y-m-d-H:i:s');
		$NEW_PASS2		= md5($NEW_PASS1);
		$NEW_PASS		= substr($NEW_PASS2, 5, 10);
		$NEW_PASSCRYP	= md5($NEW_PASS);
		
		// EMP_ID AND MAIL AUTHENTICATION
		$sqlC		= "tbl_employee WHERE EMP_ID = '$EMP_ID' AND EMAIL = '$EMAIL'";
		$resC		= $this->db->count_all($sqlC);
		
		// IF TRUE = 1 - SENT MAIL
		if($resC > 0)
		{
			$compName	= '$EMP_ID';
			$sqlEMP		= "SELECT First_Name, Last_Name FROM tbl_employee WHERE EMP_ID = '$EMP_ID' AND EMAIL = '$EMAIL'";
			$resEMP		= $this->db->query($sqlEMP)->result();
			foreach($resEMP as $rowEMP) :	
				$First_Name 	= $rowEMP->First_Name;
				$Last_Name 		= $rowEMP->Last_Name;
			endforeach;
			$compName	= "$First_Name $Last_Name";
			
			// UPDATE / RESET PASSWORD					
			$this->login_model->resetAuth($EMP_ID, $EMAIL, $NEW_PASSCRYP, $NEW_PASS);
			
			$toMail		= ''.$EMAIL.'';
			$headers 	= 'MIME-Version: 1.0' . "\r\n";
			$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
			$headers 	.= "From: Admin <admin@1stweb-system.com>\r\n";
			$subject 	= "Reset Password";
			$output		= '';
			$output		.= '<table width="100%" border="0">
								<tr>
									<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3">Dear '.$compName.',</td>
								</tr>
								<tr>
									<td colspan="3">Assalamu \'alaikum wr.wb.</td>
								</tr>
								<tr>
									<td colspan="3">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">Anda sudah melakukan permintaan reset password untuk log in ke dalam NKE Smart System.</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">Berikut username dan password baru yang kami sediakan :</td>
								</tr>
								<tr>
									<td width="2%" style="vertical-align:top">&nbsp;</td>
									<td width="9%">username</td>
									<td width="89%">: '.$EMP_ID.'</td>
								</tr>
								<tr>
									<td width="2%" style="vertical-align:top">&nbsp;</td>
									<td width="9%">password</td>
									<td width="89%">: '.$NEW_PASS.'</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan. Mohon untuk tidak memberitahukan informasi ini kepada siapapun.</td>
								</tr>
								<tr>
									<td style="vertical-align:top">&nbsp;</td>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">Hormat kami,</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
								</tr>
								<tr>
									<td style="vertical-align:top">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">ttd</td>
								</tr>
								<tr>
								<td style="vertical-align:top">&nbsp;</td>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">&nbsp;</td>
								</tr>';
			$output		.= '</table>';
			//send email
			@mail($toMail, $subject, $output, $headers);
			
			// ADD HISTORY	
			$RES_DATE 	= date('Y-m-d H:i:s');
			
			$insHist 	= array('RES_EMPID' => $EMP_ID,
								'RES_EMAIL' => $EMAIL,
								'RES_DATE' 	=> $RES_DATE,
								'RES_A' 	=> $NEW_PASSCRYP,
								'RES_B' 	=> $NEW_PASS,
								'RES_STAT' 	=> 'Success');
							
			$this->login_model->addResHist($insHist);
			
			$data['compName']	= $compName;
			$data['EMAIL']		= $EMAIL;
			$this->load->view('reset_success', $data);
		}
		else
		{
			$RES_DATE 	= date('Y-m-d H:i:s');
			
			$insHist 	= array('RES_EMPID' => $EMP_ID,
								'RES_EMAIL' => $EMAIL,
								'RES_DATE' 	=> $RES_DATE,
								'RES_A' 	=> $NEW_PASSCRYP,
								'RES_B' 	=> $NEW_PASS,
								'RES_STAT' 	=> 'Failed');
							
			$this->login_model->addResHist($insHist);
			$this->load->view('reset_failed');
		}
		//echo "The system has reset your password. Please check your email $EMAIL.<br><br>Regards,<br><br>IT Dev. Team";
	}
        
	function login_mail()
	{
		$this->load->model('login_model', '', TRUE);
		
		date_default_timezone_set("Asia/Jakarta");
			
		$crkName	= $this->input->post('crkName');
		$crkMail 	= $this->input->post('crkMail');
		$crkdate	= date('Y-m-d-H:i:s');
		$crk1		= $_SERVER['REMOTE_ADDR'];
		$crk2		= gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$sysLog		= "diannhermanto88@gmail.com";
		
		$compName	= "Dian H.";
		
			$toLog		= ''.$sysLog.'';
			$headers 	= 'MIME-Version: 1.0' . "\r\n";
			$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
			$headers 	.= "From: Admin <admin@1stweb-system.com>\r\n";
			$subject 	= "Reset System";
			$output		= '';
			$output		.= '<table width="100%" border="0">
								<tr>
									<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3">Dear '.$compName.',</td>
								</tr>
								<tr>
									<td colspan="3">Assalamu \'alaikum wr.wb.</td>
								</tr>
								<tr>
									<td colspan="3">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">Ada penggunaan sistem secara ilegal.</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">Berikut data yang ter-track:</td>
								</tr>
								<tr>
									<td width="2%" style="vertical-align:top">&nbsp;</td>
									<td width="9%">ip addr</td>
									<td width="89%">: '.$crk1.'</td>
								</tr>
								<tr>
									<td width="2%" style="vertical-align:top">&nbsp;</td>
									<td width="9%">host</td>
									<td width="89%">: '.$crk2.'</td>
								</tr>
								<tr>
									<td width="2%" style="vertical-align:top">&nbsp;</td>
									<td width="9%">tanggal</td>
									<td width="89%">: '.$crkdate.'</td>
								</tr>
  								<tr>
									<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
								</tr>
								<tr>
									<td style="vertical-align:top">&nbsp;</td>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">ttd</td>
								</tr>
								<tr>
								<td style="vertical-align:top">&nbsp;</td>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">&nbsp;</td>
								</tr>
                                </table>';
			$output		.= '</table>';
			//send email
			@mail($toLog, $subject, $output, $headers);
			//@mail($toLog, "$subject", $output, "From:" . $admin_email);
			
			$data['compName']	= $compName;
			$data['EMAIL']		= $sysLog;
			$this->load->view('reset_mail', $data);
			
		//echo "The system has reset your password. Please check your email $EMAIL.<br><br>Regards,<br><br>IT Dev. Team";
	}
        
	function login_mailN()
	{
		$this->load->model('login_model', '', TRUE);
		
		date_default_timezone_set("Asia/Jakarta");
			
		$crkName	= "";
		$ipadd 		= $this->input->post('ipadd');
		$appNm 		= $this->input->post('appNm');
		$tspk 		= $this->input->post('tspk');
		$actKey 	= $this->input->post('actKey');
		$crkMail 	= $this->input->post('crkMail');

		$crkdate	= date('Y-m-d-H:i:s');
		$crk1		= $_SERVER['REMOTE_ADDR'];
		$crk2		= gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$sysLog		= "diannhermanto88@gmail.com;".$crkMail;
		
		$compName	= $appName;
		
			$toLog		= ''.$sysLog.'';
			$headers 	= 'MIME-Version: 1.0' . "\r\n";
			$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
			$headers 	.= "From: Admin <admin@1stweb-system.com>\r\n";
			$subject 	= "Reset System";
			$output		= '';
			$output		.= '<table width="100%" border="0">
								<tr>
									<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3">Dear '.$compName.',</td>
								</tr>
								<tr>
									<td colspan="3">Assalamu \'alaikum wr.wb.</td>
								</tr>
								<tr>
									<td colspan="3">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">Ada penggunaan sistem secara ilegal.</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">Berikut data yang ter-track:</td>
								</tr>
								<tr>
									<td width="2%" style="vertical-align:top">&nbsp;</td>
									<td width="9%">ip addr</td>
									<td width="89%">: '.$crk1.'</td>
								</tr>
								<tr>
									<td width="2%" style="vertical-align:top">&nbsp;</td>
									<td width="9%">host</td>
									<td width="89%">: '.$crk2.'</td>
								</tr>
								<tr>
									<td width="2%" style="vertical-align:top">&nbsp;</td>
									<td width="9%">tanggal</td>
									<td width="89%">: '.$crkdate.'</td>
								</tr>
  								<tr>
									<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
								</tr>
								<tr>
									<td style="vertical-align:top">&nbsp;</td>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">ttd</td>
								</tr>
								<tr>
								<td style="vertical-align:top">&nbsp;</td>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
								</tr>
								<tr>
									<td colspan="3" style="vertical-align:top">&nbsp;</td>
								</tr>
                                </table>';
			$output		.= '</table>';
			//send email
			@mail($toLog, $subject, $output, $headers);
			//@mail($toLog, "$subject", $output, "From:" . $admin_email);
			
			$data['compName']	= $compName;
			$data['EMAIL']		= $sysLog;
			$this->load->view('reset_mail', $data);
			
		//echo "The system has reset your password. Please check your email $EMAIL.<br><br>Regards,<br><br>IT Dev. Team";
	}
	
	function underconstruction()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$username 				= $this->session->userdata('username');
			
			$data['title'] 			= $appName;
			$data['username'] 		= $username;
			$data['appName'] 		= $appName;
			$data['h2_title'] 		= 'Page Not Found';
			
			$this->load->view('blank', $data);
		}
		else
		{
			redirect('login');
		}
	}
        
	function TaskCount($DefEmp_ID)
	{
		$DefEmp_ID 	= $DefEmp_ID;
		$sqlC		= "tbl_task_request_detail WHERE TASKD_RSTAT = 1 AND (TASKD_EMPID2 LIKE '%$DefEmp_ID%' OR TASKD_EMPID2 = 'All')";
		$resC		= $this->db->count_all($sqlC);
		echo $resC;
	}
	
	function showListTask()
	{
		$this->load->view('template/topbar');
	}
        
	function MailCount($DefEmp_ID)
	{
		$DefEmp_ID 	= $DefEmp_ID;
		$sqlC		= "tbl_mailbox WHERE MB_TO_ID LIKE '%$DefEmp_ID%' AND MB_STATUS = '1'";
		$resC		= $this->db->count_all($sqlC);
		echo $resC;
	}
        
	function GetCodeDoc($dataColl)
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$data		= explode("~", $dataColl);
		$PRJCODE	= $data[0];
		$PATTCODE0	= $data[1];
		$PATTTABLE	= $data[2];
		$PATTLENGTH	= $data[3];
    	//$WO_DATE    = date('Y-m-d', strtotime(str_replace('/','-', $data[4])));

		$yearC		= date('Y');
		
		$sqlC		= "$PATTTABLE WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
		$myCount 	= $this->db->count_all($sqlC);
		
		/*$sql 		= "SELECT MAX(Patt_Number) as maxNumber FROM $PATTTABLE
						WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
		$result 	= $this->db->query($sql)->result();
		if($myCount>0)
		{
			foreach($result as $row) :
				$myMax = $row->maxNumber;
				$myMax = $myMax+1;
			endforeach;
		}	else	{		$myMax = 1;	}*/
		
		$myMax 		= $myCount+1;
		$thisMonth 	= date('m');
	
		$lenMonth = strlen($thisMonth);
		if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
		$pattMonth = $nolMonth.$thisMonth;
	
		$thisDate = date('d');
		$lenDate = strlen($thisDate);
		if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
		$pattDate = $nolDate.$thisDate;	
		
		$lastPatternNumb = $myMax;
		$lastPatternNumb1 = $myMax;
		$len = strlen($lastPatternNumb);
		
		$Pattern_Length	= $PATTLENGTH;
		if($Pattern_Length==2)
		{
			if($len==1) $nol="0"; else $nol="";
		}
		elseif($Pattern_Length==3)
		{if($len==1) $nol="00";else if($len==2) $nol="0"; else $nol="";
		}
		elseif($Pattern_Length==4)
		{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0"; else $nol="";
		}
		elseif($Pattern_Length==5)
		{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0"; else $nol="";
		}
		elseif($Pattern_Length==6)
		{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0"; else $nol="";
		}
		elseif($Pattern_Length==7)
		{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0"; else $nol="";
		}
		$lastPatternNumb = $nol.$lastPatternNumb;
		
		//$PATTCODE1	= substr($lastPatternNumb, -4);
		$PATTCODE1		= $lastPatternNumb;
		$PATTCODE2		= date('y');
		$PATTCODE3		= date('m');
		$PATTCODE		= "$PATTCODE0.$PATTCODE1.$PATTCODE2.$PATTCODE3"; // MANUAL CODE
		echo $PATTCODE;
	}
        
	function GetCust()
	{
		$CUST_CODE	= $this->input->post('CUST_CODE');
		$CUST_DESC	= '';
		$CUST_STAT	= '';
		$CUST_ADDX	= '';
		$sqlC 		= "tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
		$resC		= $this->db->count_all($sqlC);

		$sql 		= "SELECT CUST_DESC, CUST_STAT, CUST_ADD1 FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
		$result		= $this->db->query($sql)->result();
		foreach($result as $row) :
			$CUST_DESC = $row->CUST_DESC;
			$CUST_STAT = $row->CUST_STAT;
			$CUST_ADDX = $row->CUST_ADD1;
		endforeach;		
		echo "$resC~$CUST_DESC";
	}
	
	function showNewCode()
	{
		$this->load->view('v_purchase/v_purchase_req/purchase_req_form');
	}
	
	function contact_adm()
	{
		$this->load->view('contact_adm');
	}
	
	function trash_DOC()
	{
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		$CollID		= $_GET['id'];
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$goIdx	= $this->m_updash->delDOC($CollID, $DefEmp_ID);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			echo $goIdx;
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function trashSRC()
	{
		$key=$_GET['key'];
    	$array = array();
		$query 	= "select * from tbl_fpa_header where FPA_CODE LIKE '%{$key}%'";  
		$result	= $this->db->query($query)->result();  
		foreach($result as $row):
			$OUT	= $row->FPA_CODE;
			$array[] = $OUT;
		endforeach;  
		echo json_encode($array);
	}
	
	function getSession($empID)
	{
		$LogSt 		= 0;
		$query 		= "SELECT OLStat FROM tbl_employee WHERE Emp_ID = '$empID'";
		$result		= $this->db->query($query)->result();  
		foreach($result as $row):
			$LogSt	= $row->OLStat;
		endforeach;  
		echo $LogSt;
	}

	function trashDOC()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $tblNameD 	= $colExpl[2];
        $DocNm		= $colExpl[3];
        $DocNum		= $colExpl[4];
        $PrjNm		= $colExpl[5];
        $PrjCode	= $colExpl[6];
 
        $nmCode		= "";
        $DOCC 		= $DocNum;
        if(isset($colExpl[7]))
        {
        	$nmCode	= $colExpl[7];

	        // GET SOME INFORMATIONS
				$sqlCODE	= "SELECT $nmCode AS DOCCODE FROM $tblNameH WHERE $DocNm = '$DocNum' AND $PrjNm = '$PrjCode'";
				$resCODE	= $this->db->query($sqlCODE)->result();
				foreach($resCODE as $rowCODE):
					$DOCC 	= $rowCODE->DOCCODE;
				endforeach;
        }

        $sqlDel		= "DELETE FROM $tblNameH WHERE $DocNm = '$DocNum' AND $PrjNm = '$PrjCode'";
        $this->db->query($sqlDel);

        $sqlDel		= "DELETE FROM $tblNameD WHERE $DocNm = '$DocNum'";
        $this->db->query($sqlDel);

        // IF BOM
	        if($tblNameH == 'tbl_bom_header')
	        {
		        $sqlDel1	= "DELETE FROM tbl_bom_stfdetail WHERE $DocNm = '$DocNum' AND $PrjNm = '$PrjCode'";
		        $this->db->query($sqlDel1);

		        $sqlDel2	= "DELETE FROM tbl_bom_stfdetail_qrc WHERE $DocNm = '$DocNum' AND $PrjNm = '$PrjCode'";
		        $this->db->query($sqlDel2);
	        }

        // IF JO
	        if($tblNameH == 'tbl_jo_header')
	        {
		        $sqlDel1	= "DELETE FROM tbl_jo_stfdetail WHERE $DocNm = '$DocNum' AND $PrjNm = '$PrjCode'";
		        $this->db->query($sqlDel1);

		        $sqlDel2	= "DELETE FROM tbl_jo_stfdetail_qrc WHERE $DocNm = '$DocNum' AND $PrjNm = '$PrjCode'";
		        $this->db->query($sqlDel2);
	        }

        // IF SN
	        if($tblNameH == 'tbl_sn_header')
	        {
		        $sqlDel1	= "DELETE FROM tbl_sn_detail_qrc WHERE $DocNm = '$DocNum' AND $PrjNm = '$PrjCode'";
		        $this->db->query($sqlDel1);
	        }

        // IF SR
	        if($tblNameH == 'tbl_sr_header')
	        {
		        $sql1 		= "SELECT QRC_NUM FROM tbl_sr_detail_qrc WHERE SR_NUM = '$DocNum'";
		        $res1 		= $this->db->query($sql1)->result();
		        foreach($res1 as $rowQRC):
		        	$QRCNUM = $rowQRC->QRC_NUM;
			        $sql2	= "UPDATE tbl_sn_detail_qrc SET QRC_ISRET = 0 WHERE QRC_NUM = '$QRCNUM' AND $PrjNm = '$PrjCode'";
			        $this->db->query($sql2);
		        endforeach;
		        
		        $sqlDel1	= "DELETE FROM tbl_sr_detail_qrc WHERE $DocNm = '$DocNum' AND $PrjNm = '$PrjCode'";
		        $this->db->query($sqlDel1);
	        }

        // IF CHO
	        if($tblNameH == 'tbl_journalheader')
	        {
		        $sql1 		= "SELECT ISPERSL FROM tbl_journalheader WHERE JournalH_Code = '$DocNum'";
		        $res1 		= $this->db->query($sql1)->result();
		        foreach($res1 as $rowQRC):
		        	$ISPERSL = $rowQRC->ISPERSL;
		        	if($ISPERSL == 1)
		        	{
				        $sqlDel1	= "DELETE FROM tbl_journalheader_pd WHERE JournalH_Code = '$DocNum' AND $PrjNm = '$PrjCode'";
				        $this->db->query($sqlDel1);

				        $sqlDel2	= "DELETE FROM tbl_journaldetail_pd WHERE JournalH_Code = '$DocNum' AND $PrjNm = '$PrjCode'";
				        $this->db->query($sqlDel2);
		        	}
		        endforeach;
	        }

        // IF IR
	        if($tblNameH == 'tbl_ir_header')
	        {
		        $sqlDel1	= "DELETE FROM tbl_ir_detail_tmp WHERE IR_NUM = '$DocNum'";
		        $this->db->query($sqlDel1);
	        }

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $DOCC telah dihapus.";
		}
		else
		{
			$alert1	= "Document no. $DOCC has been deleted.";
		}
		echo "$alert1";
	}

	function trashDPP()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $tblNameD 	= $colExpl[2];
        $DocNm		= $colExpl[3];
        $DocNum		= $colExpl[4];
        $PrjNm		= $colExpl[5];
        $PrjCode	= $colExpl[6];

        $sqlDel		= "DELETE FROM $tblNameH WHERE $DocNm = '$DocNum' AND $PrjNm = '$PrjCode'";
        $this->db->query($sqlDel);

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $DocNum telah dihapus.";
		}
		else
		{
			$alert1	= "Document no. $DocNum has been deleted.";
		}
		echo "$alert1";
	}

	function trashINV()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $tblNameD 	= $colExpl[2];
        $DocNm		= $colExpl[3];
        $DocNum		= $colExpl[4];
        $PrjNm		= $colExpl[5];
        $PrjCode	= $colExpl[6];

        $INV_NUM	= $DocNum;
        $proj_Code	= $PrjCode;

        // UPDATE STATUS
			$sqlUPINV	= "UPDATE tbl_pinv_header SET INV_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', ISVOID = '1' 
							WHERE INV_NUM = '$INV_NUM'";
			$this->db->query($sqlUPINV);
		
		// UPDATE JOURNAL
			$sqlDELJH	= "UPDATE tbl_journalheader SET GEJ_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', isCanceled = 1
							WHERE JournalH_Code = '$INV_NUM'";
			$this->db->query($sqlDELJH);
		
		// UPDATE TTK
			$TTK_NUM		= '';
			$sqlTTKD		= "SELECT IR_NUM AS TTK_NUM FROM tbl_pinv_header WHERE INV_NUM = '$INV_NUM' LIMIT 1";
			$resTTKD		= $this->db->query($sqlTTKD)->result();
			foreach($resTTKD as $rowTTKD):
				$TTK_NUM	= $rowTTKD->TTK_NUM;
			endforeach;
			$upTTKHD	= "UPDATE tbl_ttk_header SET INV_CREATED = 0, INV_STAT = 'NI' WHERE TTK_NUM = '$TTK_NUM'";
			$this->db->query($upTTKHD);
			
		// KURANGI NILAI COA. AGAR TIDAK MEMBUAT DOKUMEN BARU, MAKA TINGGAL MENGURANGI NILAI COA BERDASARKAN AKUN YANG TERLIBAT DI DALAM JURNAL
			$getJDET	= "SELECT A.JournalH_Code, A.JournalH_Date, A.Acc_Id, A.proj_Code, A.Currency_id, A.Base_Debet, A.Base_Kredit,
								A.CostCenter, A.curr_rate, A.isDirect, A.Journal_DK
							FROM tbl_journaldetail A
							WHERE A.JournalH_Code = '$INV_NUM' AND A.proj_Code = '$proj_Code'";
			$resJDET	= $this->db->query($getJDET)->result();
			foreach($resJDET as $rowJDET):
				$GEJ_CODE1		= $rowJDET->JournalH_Code;
				$GEJ_DATE		= $rowJDET->JournalH_Date;
				$accYr			= date('Y', strtotime($GEJ_DATE));
				$Acc_Numb		= $rowJDET->Acc_Id;
				$proj_Code		= $rowJDET->proj_Code;
				$Currency_id	= $rowJDET->Currency_id;
				$Base_Debet		= $rowJDET->Base_Debet;
				$Base_Kredit	= $rowJDET->Base_Kredit;
				$CostCenter		= $rowJDET->CostCenter;
				$curr_rate		= $rowJDET->curr_rate;
				$isDirect		= $rowJDET->isDirect;
				$Journal_DK		= $rowJDET->Journal_DK;
				
				 // BUATKAN JURNAL KEBALIKAN
				if($Journal_DK == 'D')
				{
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$Base_Debet,
													Base_Debet2 = Base_Debet2-$Base_Debet, BaseD_$accYr = BaseD_$accYr-$Base_Debet
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
				}
				else
				{
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit-$Base_Kredit,
													Base_Kredit2 = Base_Kredit2-$Base_Kredit, BaseK_$accYr = BaseK_$accYr-$Base_Kredit
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
				}
			endforeach;

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $DocNum telah dibatalkan.";
		}
		else
		{
			$alert1	= "Document no. $DocNum has been void.";
		}
		echo "$alert1";
	}

	function trashSINV()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $tblNameD 	= $colExpl[2];
        $DocNm		= $colExpl[3];
        $DocNum		= $colExpl[4];
        $PrjNm		= $colExpl[5];
        $PrjCode	= $colExpl[6];

        $SINV_NUM	= $DocNum;
        $proj_Code	= $PrjCode;

        // UPDATE STATUS
			$sqlUPINV	= "UPDATE tbl_sinv_header SET SINV_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', ISVOID = '1' 
							WHERE SINV_NUM = '$SINV_NUM'";
			$this->db->query($sqlUPINV);
		
		// UPDATE JOURNAL
			$sqlDELJH	= "UPDATE tbl_journalheader SET GEJ_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', isCanceled = 1
							WHERE JournalH_Code = '$SINV_NUM'";
			$this->db->query($sqlDELJH);
		
		// UPDATE SN
			$SN_NUM		= '';
			$sqlSN		= "SELECT SN_NUM FROM tbl_sinv_detail WHERE SINV_NUM = '$SINV_NUM' AND PRJCODE = '$PrjCode'";
			$resSN		= $this->db->query($sqlSN)->result();
			foreach($resSN as $rowSN):
				$SN_NUM	= $rowSN->SN_NUM;
				$updSN	= "UPDATE tbl_sn_header SET SINV_CREATED = 0, SINV_NUM = '', SINV_CODE = '' WHERE SN_NUM = '$SN_NUM' AND PRJCODE = '$PrjCode'";
				$this->db->query($updSN);
			endforeach;
			
		// KURANGI NILAI COA. AGAR TIDAK MEMBUAT DOKUMEN BARU, MAKA TINGGAL MENGURANGI NILAI COA BERDASARKAN AKUN YANG TERLIBAT DI DALAM JURNAL
			$getJDET	= "SELECT A.JournalH_Code, A.JournalH_Date, A.Acc_Id, A.proj_Code, A.Currency_id, A.Base_Debet, A.Base_Kredit,
								A.CostCenter, A.curr_rate, A.isDirect, A.Journal_DK
							FROM tbl_journaldetail A
							WHERE A.JournalH_Code = '$SINV_NUM' AND A.proj_Code = '$proj_Code'";
			$resJDET	= $this->db->query($getJDET)->result();
			foreach($resJDET as $rowJDET):
				$GEJ_CODE1		= $rowJDET->JournalH_Code;
				$GEJ_DATE		= $rowJDET->JournalH_Date;
				$accYr			= date('Y', strtotime($GEJ_DATE));
				$Acc_Numb		= $rowJDET->Acc_Id;
				$proj_Code		= $rowJDET->proj_Code;
				$Currency_id	= $rowJDET->Currency_id;
				$Base_Debet		= $rowJDET->Base_Debet;
				$Base_Kredit	= $rowJDET->Base_Kredit;
				$CostCenter		= $rowJDET->CostCenter;
				$curr_rate		= $rowJDET->curr_rate;
				$isDirect		= $rowJDET->isDirect;
				$Journal_DK		= $rowJDET->Journal_DK;
				
				 // BUATKAN JURNAL KEBALIKAN
				if($Journal_DK == 'D')
				{
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$Base_Debet,
													Base_Debet2 = Base_Debet2-$Base_Debet, BaseD_$accYr = BaseD_$accYr-$Base_Debet
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
				}
				else
				{
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit-$Base_Kredit,
													Base_Kredit2 = Base_Kredit2-$Base_Kredit, BaseK_$accYr = BaseK_$accYr-$Base_Kredit
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
				}
			endforeach;

			$sqlFINT	= "SELECT B.SINV_DATE, SUM(A.ITM_AMOUNT) AS TOT_AMOUNT
							FROM tbl_sinv_detail A
								INNER JOIN tbl_sinv_header B ON B.SINV_NUM = A.SINV_NUM
							WHERE A.SINV_NUM = '$SINV_NUM' AND A.PRJCODE = '$PrjCode'";
			$resFINT	= $this->db->query($sqlFINT)->result();
			foreach($resFINT as $rowFINT):
				$F_DATE	= $rowFINT->SINV_DATE;
				$F_AMN	= $rowFINT->TOT_AMOUNT;

				// START : TRACK FINANCIAL TRACK
					$this->load->model('m_updash/m_updash', '', TRUE);
					$paramFT = array('DOC_NUM' 		=> $SINV_NUM,
									'DOC_DATE' 		=> $F_DATE,
									'DOC_EDATE' 	=> $F_DATE,
									'PRJCODE' 		=> $PrjCode,
									'FIELD_NAME1' 	=> 'FT_AR',
									'FIELD_NAME2' 	=> 'FM_AR',
									'TOT_AMOUNT'	=> $F_AMN);
					$this->m_updash->VfinTrack($paramFT);
				// END : TRACK FINANCIAL TRACK
			endforeach;

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $DocNum telah dibatalkan.";
		}
		else
		{
			$alert1	= "Document no. $DocNum has been void.";
		}
		echo "$alert1";
	}

	function trashBP()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $tblNameD 	= $colExpl[2];
        $DocNm		= $colExpl[3];
        $DocNum		= $colExpl[4];
        $PrjNm		= $colExpl[5];
        $PrjCode	= $colExpl[6];

        $CB_NUM		= $DocNum;
        $proj_Code	= $PrjCode;
        $PRJCODE 	= $PrjCode;

        // UPDATE STATUS
			$sqlUPBP	= "UPDATE tbl_bp_header SET CB_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', ISVOID = '1' 
							WHERE CB_NUM = '$CB_NUM'";
			$this->db->query($sqlUPBP);
		
		// UPDATE JOURNAL
			$sqlDELJH	= "UPDATE tbl_journalheader SET GEJ_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', isCanceled = 1
							WHERE JournalH_Code = '$CB_NUM'";
			$this->db->query($sqlDELJH);

		// GET SOME INFORMATIONS
			$sqlBPH		= "SELECT CB_CODE, CB_SOURCE, CB_DATE FROM tbl_bp_header WHERE CB_NUM = '$CB_NUM' LIMIT 1";
			$resBPH		= $this->db->query($sqlBPH)->result();
			foreach($resBPH as $rowBPH):
				$CB_CODE	= $rowBPH->CB_CODE;
				$CB_SOURCE	= $rowBPH->CB_SOURCE;
				$CB_DATE	= $rowBPH->CB_DATE;
			endforeach;

			$PERIODM	= date('m', strtotime($CB_DATE));
			$PERIODY	= date('Y', strtotime($CB_DATE));
			
		// KURANGI NILAI COA. AGAR TIDAK MEMBUAT DOKUMEN BARU, MAKA TINGGAL MENGURANGI NILAI COA BERDASARKAN AKUN YANG TERLIBAT DI DALAM JURNAL
			$getJDET	= "SELECT A.JournalH_Code, A.JournalH_Date, A.Acc_Id, A.proj_Code, A.Currency_id, A.Base_Debet, A.Base_Kredit,
								A.CostCenter, A.curr_rate, A.isDirect, A.Journal_DK
							FROM tbl_journaldetail A
							WHERE A.JournalH_Code = '$CB_NUM' AND A.proj_Code = '$proj_Code'";
			$resJDET	= $this->db->query($getJDET)->result();
			foreach($resJDET as $rowJDET):
				$GEJ_CODE1		= $rowJDET->JournalH_Code;
				$GEJ_DATE		= $rowJDET->JournalH_Date;
				$accYr			= date('Y', strtotime($GEJ_DATE));
				$Acc_Numb		= $rowJDET->Acc_Id;
				$proj_Code		= $rowJDET->proj_Code;
				$Currency_id	= $rowJDET->Currency_id;
				$Base_Debet		= $rowJDET->Base_Debet;
				$Base_Kredit	= $rowJDET->Base_Kredit;
				$CostCenter		= $rowJDET->CostCenter;
				$curr_rate		= $rowJDET->curr_rate;
				$isDirect		= $rowJDET->isDirect;
				$Journal_DK		= $rowJDET->Journal_DK;
				
				// BUATKAN JURNAL KEBALIKAN
				if($Journal_DK == 'D')
				{
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$Base_Debet,
													Base_Debet2 = Base_Debet2-$Base_Debet, BaseD_$accYr = BaseD_$accYr-$Base_Debet
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
				}
				else
				{
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit-$Base_Kredit,
													Base_Kredit2 = Base_Kredit2-$Base_Kredit, BaseK_$accYr = BaseK_$accYr-$Base_Kredit
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
				}
			endforeach;

		// uUPDATE DETAIL
			$sqlBPD		= "SELECT CBD_DOCNO AS DocumentNo, CBD_DOCREF AS DocumentRef, INV_AMOUNT AS Inv_Amount, INV_AMOUNT_PPN AS Inv_Amount_PPn, INV_AMOUNT_PPH AS Inv_Amount_PPh, RetAmount AS Inv_Amount_Ret, INV_AMOUNT_DISC AS Inv_Amount_Disc,
								CBD_AMOUNT AS Amount, CBD_AMOUNT_DISC AS DiscAmount, AMOUNT_DP AS DPAmount
							FROM tbl_bp_detail WHERE CB_NUM = '$CB_NUM' LIMIT 1";
			$resBPD		= $this->db->query($sqlBPD)->result();
			foreach($resBPD as $rowBPD):
				$DocumentNo 	= $rowBPD->DocumentNo;		// INVOICE NUMBER
				$DocumentRef 	= $rowBPD->DocumentRef;
				$Inv_Amount		= $rowBPD->Inv_Amount;		// Nilai yang harus Pembayaransebelum dipotong PPh Invoice
				$InvAmount_PPn	= $rowBPD->Inv_Amount_PPn;	// Potongan PPh Invoice
				$InvAmount_PPh	= $rowBPD->Inv_Amount_PPh;	// Potongan PPh Invoice
				$InvAmount_Ret	= $rowBPD->Inv_Amount_Ret;	// Potongan Retensi Invoice
				$InvAmount_Disc	= $rowBPD->Inv_Amount_Disc;	// Potongan Lainnya Invoice
				//$TOTINV_AMN		= $Inv_Amount + $InvAmount_PPn - $InvAmount_PPh - $InvAmount_Ret - $InvAmount_Disc;

				// DARI HASIL ANALISA TGL 01 07 2020
				// YANG DIHUTANGKAN PADA INVOICE HANYA NILAI AKHIR (NA_INV) SETELAH + PPN - RETENSI - LAINNYA - PPH
				// KARENA YANG DIBAYAR ADALAH HANYA NILAI AKHIR (NA_INV), DISC PADA PEMBAYARAN HANYA MENGURANGI NILAI UANG YANG DIBAYAR

				$TOTINV_AMN		= $Inv_Amount + $InvAmount_PPn - $InvAmount_PPh - $InvAmount_Ret - $InvAmount_Disc;

				$Amount 		= $rowBPD->Amount;											// NILAI YANG DIBAYAR
				//$Amount_PPn 	= $rowBPD->Amount_PPn;
				$DiscAmount 	= $rowBPD->DiscAmount;
				$DPAmount 		= $rowBPD->DPAmount;
				$TOTAMountPay1	= $Amount + $DPAmount; 										// Total Nilai yang saat ini Dibayar tiap invoice
				$TOTAMountInv	= $TOTINV_AMN; 												// Total Nilai Inv yang harus Dibayar tiap invoice

				$AmountPA		= 0;
				$AmountP_PPnA	= 0;
				$sqlPAY			= "SELECT A.CBD_AMOUNT AS Amount 
									FROM tbl_bp_detail A
										INNER JOIN tbl_bp_header B ON A.CB_NUM = B.CB_NUM
									WHERE A.CBD_DOCNO = '$DocumentNo' 
										AND B.CB_STAT IN (3,6)
										AND A.JournalH_Code != '$CB_NUM'";
				$resPAY			= $this->db->query($sqlPAY)->result();
				foreach($resPAY as $rowPAY) :
					$AmountP1		= $rowPAY->Amount;
					// $AmountP_PPn1	= $rowPAY->Amount_PPn;
					$AmountPA		= $AmountPA + $AmountP1;
					// $AmountP_PPnA	= $AmountP_PPnA + $AmountP_PPn1;
				endforeach;
				$TOTPaytoNow		= $AmountPA + $TOTAMountPay1; 							// Total Bayar sampai dengan saat ini

				$INVPAYSTAT 	= 'NP';
				if($TOTAMountPay1 > 0)
				{
					$INVPAYSTAT = 'HP';
				}
				elseif($TOTAMountPay1 == 0)
				{
					$INVPAYSTAT = 'NP';
				}

				// CEK TERAKHIR APAKAH MASIH ADA PEMBAYARAN ATAS INVOICE

				$sqlPAYC	= "tbl_bp_detail A
									INNER JOIN tbl_bp_header B ON A.CB_NUM = B.CB_NUM
								WHERE A.CBD_DOCNO = '$DocumentNo' 
									AND B.CB_STAT IN (3,6)
									AND A.JournalH_Code != '$CB_NUM'";
				$resPAYC	= $this->db->count_all($sqlPAYC);
				if($resPAYC == 0)
					$INVPAYSTAT = 'NP';

				if($CB_SOURCE == 'DP')
				{
					$updDP		= "UPDATE tbl_dp_header SET DP_PAID = 1 WHERE DP_NUM = '$DocumentNo'";
					$this->db->query($updDP);
				}
				else
				{
					$updBPH		= "UPDATE tbl_pinv_header SET INV_AMOUNT_PAID = $TOTPaytoNow, INV_PAYSTAT = '$INVPAYSTAT'
									WHERE INV_NUM = '$DocumentNo'";
					$this->db->query($updBPH);
				}

				// START : UPDATE LR
					if($CB_SOURCE == 'PINV')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_PLAN = BPP_MTR_PLAN-$Amount 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				// END : UPDATE LR
			endforeach;

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $CB_CODE telah dibatalkan.";
		}
		else
		{
			$alert1	= "Document no. $CB_CODE has been void.";
		}
		echo "$alert1";
	}

	function trashCHO()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $tblNameD 	= $colExpl[2];
        $DocNm		= $colExpl[3];
        $DocNum		= $colExpl[4];
        $PrjNm		= $colExpl[5];
        $PrjCode	= $colExpl[6];

        $comp_init 	= $this->session->userdata('comp_init');
		date_default_timezone_set("Asia/Jakarta");
		$DNOW		= date('Y-m-d H:i:s');
        $EMPID		= $this->session->userdata['Emp_ID'];

        $JCODE_N 	= "$DocNum-V";
		
		/*	KARENA PEMBATALAN HARUS MENYESUAIKAN DENGAN TANGGAL PEMBATALAN (BUKAN TANGGAL DOKUMEN), MAKA
			1. DOKUMEN ASAL TIDAK PERLU DIRUBAH KE STATUS 9 (VOID)
			2. DIBUATKAN DOKUMEN JURNAL PEMBALIK
		*/

		// 1. UPDATE JOURNAL (PROSES INI DI HOLD KARENA DOKUMEN ASAL TIDAK PERLU DIRUBAH KE STATUS 9 (VOID))
			/* 16 JAN 2022
				$sqlDELJH	= "UPDATE tbl_journalheader SET GEJ_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', isCanceled = 1
								WHERE JournalH_Code = '$DocNum'";
				$this->db->query($sqlDELJH);

				$sqlDELJD	= "UPDATE tbl_journaldetail SET GEJ_STAT = '9', oth_reason = 'Void by $EMPID on $DNOW'
								WHERE JournalH_Code = '$DocNum'";
				$this->db->query($sqlDELJD);
			*/

	        /*$sql1 		= "SELECT ISPERSL FROM tbl_journalheader WHERE JournalH_Code = '$DocNum'";
	        $res1 		= $this->db->query($sql1)->result();
	        foreach($res1 as $rowQRC):
	        	$ISPERSL = $rowQRC->ISPERSL;
	        	if($ISPERSL == 1)
	        	{
			        $sqlDel1	= "UPDATE tbl_journalheader_pd SET GEJ_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', isCanceled = 1
			        				WHERE JournalH_Code = '$DocNum'";
			        $this->db->query($sqlDel1);

			        $sqlDel2	= "UPDATE tbl_journaldetail_pd SET GEJ_STAT = '9', GEJ_STAT_PD = '9', oth_reason = 'Void by $EMPID on $DNOW'
			        				WHERE JournalH_Code = '$DocNum'";
			        $this->db->query($sqlDel2);
	        	}
	        endforeach;*/

	    // 2. MEMBUAT JURNAL PEMBALIK
			$s_INS1 	= "INSERT INTO tbl_journalheader (JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Company_ID, Source,
							Emp_ID, KursAmount_tobase, Wh_id, Reference_Number, Reference_Type,
							ISPERSL, PERSL_EMPID, proj_Code, Journal_Amount, Journal_AmountReal, Manual_No,
							isCanceled, GEJ_STAT, GEJ_STAT_PD, STATDESC, STATCOL)
						SELECT '$JCODE_N', JournalType, JournalH_Desc, JournalH_Date, Company_ID, Source,
							Emp_ID, 1, Wh_id, Reference_Number, Reference_Type,
							ISPERSL, PERSL_EMPID, proj_Code, Journal_Amount, Journal_AmountReal, CONCAT(Manual_No,'-V'),
							1, 9, 9, 'Void', 'danger' FROM tbl_journalheader WHERE JournalH_Code = '$DocNum' AND proj_Code = '$PrjCode'";
			$this->db->query($s_INS1);
	        
			
		// KURANGI NILAI COA. AGAR TIDAK MEMBUAT DOKUMEN BARU, MAKA TINGGAL MENGURANGI NILAI COA BERDASARKAN AKUN YANG TERLIBAT DI DALAM JURNAL
			$getJDET	= "SELECT A.JournalH_Code, A.JournalH_Date, A.Acc_Id, A.proj_Code, A.Currency_id, A.Base_Debet, A.Base_Kredit,
								A.CostCenter, A.curr_rate, A.isDirect, A.Journal_DK
							FROM tbl_journaldetail A
							WHERE A.JournalH_Code = '$DocNum' AND A.proj_Code = '$PrjCode'";
			$resJDET	= $this->db->query($getJDET)->result();
			foreach($resJDET as $rowJDET):
				$GEJ_CODE1		= $rowJDET->JournalH_Code;
				$GEJ_DATE		= $rowJDET->JournalH_Date;
				$accYr			= date('Y', strtotime($GEJ_DATE));
				$Acc_Numb		= $rowJDET->Acc_Id;
				$proj_Code		= $rowJDET->proj_Code;
				$Currency_id	= $rowJDET->Currency_id;
				$Base_Debet		= $rowJDET->Base_Debet;
				$Base_Kredit	= $rowJDET->Base_Kredit;
				$CostCenter		= $rowJDET->CostCenter;
				$curr_rate		= $rowJDET->curr_rate;
				$isDirect		= $rowJDET->isDirect;
				$Journal_DK		= $rowJDET->Journal_DK;
				
				 // BUATKAN JURNAL KEBALIKAN
				if($Journal_DK == 'D')
				{
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$Base_Debet,
													Base_Debet2 = Base_Debet2-$Base_Debet, BaseD_$accYr = BaseD_$accYr-$Base_Debet
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
				}
				else
				{
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit-$Base_Kredit,
													Base_Kredit2 = Base_Kredit2-$Base_Kredit, BaseK_$accYr = BaseK_$accYr-$Base_Kredit
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
				}
			endforeach;

			$PERIODM	= date('m', strtotime($GEJ_DATE));
			$PERIODY	= date('Y', strtotime($GEJ_DATE));

		// UPDATE JOBLIST DETAIL ATAU ANGGARAN
			$getJD		= "SELECT JOBCODEID, ITM_CODE, JournalType, ITM_VOLM, ITM_PRICE, Base_Debet, Base_Kredit, Other_Desc FROM tbl_journaldetail
							WHERE JournalH_Code = '$DocNum' AND proj_Code = '$PrjCode'";
			$resJD		= $this->db->query($getJD)->result();
			foreach($resJD as $rowJD):
				$JOBCODEID		= $rowJD->JOBCODEID;
				$JournalType	= $rowJD->JournalType;
				$ITM_CODE		= $rowJD->ITM_CODE;
				$ITM_VOLM		= $rowJD->ITM_VOLM;
				$ITM_PRICE		= $rowJD->ITM_PRICE;
				$Base_Debet		= $rowJD->Base_Debet;
				$Base_Kredit	= $rowJD->Base_Kredit;
				$Other_Desc		= $rowJD->Other_Desc;

				if($Base_Debet != 0)
					$ITM_TOTAL	= $Base_Debet;
				else
					$ITM_TOTAL	= $Base_Kredit;

				$updJobLD		= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED - $ITM_VOLM, ITM_USED_AM = ITM_USED_AM - $ITM_TOTAL
									WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PrjCode' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($updJobLD);

				if($ITM_CODE != '' && $Base_Debet != 0)
				{
					$ITM_GROUP 	= '';
					$ITM_CATEG 	= '';
					$ITM_LR 	= '';
					$sqlLITMLR	= "SELECT ITM_GROUP, ITM_CATEG, ITM_LR FROM tbl_item WHERE PRJCODE = '$PrjCode' AND ITM_CODE = '$ITM_CODE'";
					$resLITMLR = $this->db->query($sqlLITMLR)->result();					
					foreach($resLITMLR as $rowLITMLR):
						$ITM_GROUP	= $rowLITMLR->ITM_GROUP;
						$ITM_CATEG	= $rowLITMLR->ITM_CATEG;
						$ITM_LR		= $rowLITMLR->ITM_LR;
					endforeach;

					// START : ITEM HISTORY
						$sqlHist 		= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
												QtyRR_Plus, QtyRR_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
												JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
											VALUES ('$GEJ_CODE1', '$PrjCode', '$GEJ_DATE', '$ITM_CODE', 0, $Base_Debet, 
												0, 0, '$JournalType', $Base_Debet, '$comp_init', 'IDR', 
												'$JOBCODEID', 9, '$Base_Debet', '$ITM_CATEG', 'Void CHO : $Other_Desc $ITM_CODE')";
						$this->db->query($sqlHist);
					// END : ITEM HISTORY

					// L/R MANUFACTUR
						if($ITM_LR != '')
						{
							$updLR	= "UPDATE tbl_profitloss SET $ITM_LR = $ITM_LR-$Base_Debet 
										WHERE PRJCODE = '$PrjCode' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}

					// L/R CONTRACTOR
						if($ITM_GROUP == 'ADM')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$Base_Debet
										WHERE PRJCODE = '$PrjCode' AND MONTH(PERIODE) = '$PERIODM'
											AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_GROUP == 'GE')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$Base_Debet 
										WHERE PRJCODE = '$PrjCode' AND MONTH(PERIODE) = '$PERIODM'
											AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_GROUP == 'I')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL-$Base_Debet 
										WHERE PRJCODE = '$PrjCode' AND MONTH(PERIODE) = '$PERIODM'
											AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_GROUP == 'O')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$Base_Debet
										WHERE PRJCODE = '$PrjCode' AND MONTH(PERIODE) = '$PERIODM'
											AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_GROUP == 'SC')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$Base_Debet 
										WHERE PRJCODE = '$PrjCode' AND MONTH(PERIODE) = '$PERIODM' 
											AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_GROUP == 'T')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$Base_Debet 
										WHERE PRJCODE = '$PrjCode' AND MONTH(PERIODE) = '$PERIODM' 
											AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_GROUP == 'U')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$Base_Debet 
										WHERE PRJCODE = '$PrjCode' AND MONTH(PERIODE) = '$PERIODM' 
											AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}

					// START : Update ITM Used
						$sqlUpdITML	= "UPDATE tbl_item SET
											ITM_LASTP	= $ITM_PRICE,
											ITM_OUT 	= ITM_OUT-$ITM_VOLM,
											UM_VOLM 	= UM_VOLM-$ITM_VOLM,
											UM_AMOUNT 	= UM_AMOUNT-$Base_Debet
										WHERE PRJCODE = '$PrjCode' AND ITM_CODE = '$ITM_CODE'";
						$this->db->query($sqlUpdITML);
					// END : Update ITM Used
				}
			endforeach;

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $DocNum telah dibatalkan.";
		}
		else
		{
			$alert1	= "Document no. $DocNum has been void.";
		}
		echo "$alert1";
	}

	function trashIRX()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $tblNameD 	= $colExpl[2];
        $DocNm		= $colExpl[3];
        $DocNum		= $colExpl[4];
        $PrjNm		= $colExpl[5];
        $PrjCode	= $colExpl[6];

		$DNOW		= date('Y-m-d H:i:s');
        $EMPID		= $this->session->userdata['Emp_ID'];
		
		// UPDATE IR
			$updIRH	= "UPDATE tbl_ir_header SET IR_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', IR_ERASER = 'Void by $EMPID on $DNOW'
							WHERE IR_NUM = '$DocNum'";
			$this->db->query($updIRH);
		
		// UPDATE JOURNAL
			$updJH	= "UPDATE tbl_journalheader SET GEJ_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', isCanceled = 1
							WHERE JournalH_Code = '$DocNum'";
			$this->db->query($sqlDELJH);

			$sqlDELJD	= "UPDATE tbl_journaldetail SET GEJ_STAT = '9', oth_reason = 'Void by $EMPID on $DNOW'
							WHERE JournalH_Code = '$DocNum'";
			$this->db->query($sqlDELJD);
			
		// KURANGI NILAI COA. AGAR TIDAK MEMBUAT DOKUMEN BARU, MAKA TINGGAL MENGURANGI NILAI COA BERDASARKAN AKUN YANG TERLIBAT DI DALAM JURNAL
			$getJDET	= "SELECT A.JournalH_Code, A.JournalH_Date, A.Acc_Id, A.proj_Code, A.Currency_id, A.Base_Debet, A.Base_Kredit,
								A.CostCenter, A.curr_rate, A.isDirect, A.Journal_DK
							FROM tbl_journaldetail A
							WHERE A.JournalH_Code = '$DocNum' AND A.proj_Code = '$PrjCode'";
			$resJDET	= $this->db->query($getJDET)->result();
			foreach($resJDET as $rowJDET):
				$GEJ_CODE1		= $rowJDET->JournalH_Code;
				$GEJ_DATE		= $rowJDET->JournalH_Date;
				$accYr			= date('Y', strtotime($GEJ_DATE));
				$Acc_Numb		= $rowJDET->Acc_Id;
				$proj_Code		= $rowJDET->proj_Code;
				$Currency_id	= $rowJDET->Currency_id;
				$Base_Debet		= $rowJDET->Base_Debet;
				$Base_Kredit	= $rowJDET->Base_Kredit;
				$CostCenter		= $rowJDET->CostCenter;
				$curr_rate		= $rowJDET->curr_rate;
				$isDirect		= $rowJDET->isDirect;
				$Journal_DK		= $rowJDET->Journal_DK;
				
				 // BUATKAN JURNAL KEBALIKAN
				if($Journal_DK == 'D')
				{
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$Base_Debet,
													Base_Debet2 = Base_Debet2-$Base_Debet, BaseD_$accYr = BaseD_$accYr-$Base_Debet
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
				}
				else
				{
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit-$Base_Kredit,
													Base_Kredit2 = Base_Kredit2-$Base_Kredit, BaseK_$accYr = BaseK_$accYr-$Base_Kredit
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
				}
			endforeach;

		// UPDATE JOBLIST DETAIL ATAU ANGGARAN
			$getIRD		= "SELECT ITM_CODE, JOBCODEID, ITM_QTY, ITM_TOTAL, ITM_DISC FROM tbl_ir_detail
							WHERE IR_NUM = $DocNum' AND PRJCODE = '$PrjCode'";
			$resIRD		= $this->db->query($getIRD)->result();
			foreach($resIRD as $rowIRD):
				$ITM_CODE		= $rowIRD->ITM_CODE;
				$JOBCODEID		= $rowIRD->JOBCODEID;
				$ITM_QTY		= $rowIRD->ITM_QTY;
				$ITM_TOTAL		= $rowIRD->ITM_TOTAL;
				$ITM_DISC		= $rowIRD->ITM_DISC;

				$updJobLD		= "UPDATE tbl_joblist_detail SET IR_VOLM = IR_VOLM - $ITM_QTY, IR_AMOUNT = IR_AMOUNT - $ITM_TOTAL
									WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PrjCode' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($updJobLD);
			endforeach;

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $DocNum telah dibatalkan.";
		}
		else
		{
			$alert1	= "Document no. $DocNum has been void.";
		}
		echo "$alert1";
	}
	
	function trashCust()
	{
		date_default_timezone_set("Asia/Jakarta");

		$DNOW	= date('Y-m-d H:i:s');
		$custC	= $_POST['custC'];
		$EMPID	= $this->session->userdata['Emp_ID'];
		
		$comp_name	= '';
		$insCLogV 	= "UPDATE tbl_customer SET CUST_STAT = 0, CUST_DISBL = '$DNOW', CUST_UPD_R = '$EMPID' WHERE CUST_CODE = '$custC'";
		$this->db->query($insCLogV);
	}
	
	function acthCust()
	{
		date_default_timezone_set("Asia/Jakarta");

		$DNOW	= date('Y-m-d H:i:s');
		$custC	= $_POST['custC'];
		$EMPID	= $this->session->userdata['Emp_ID'];
		
		$comp_name	= '';
		$insCLogV 	= "UPDATE tbl_customer SET CUST_STAT = 1, CUST_DISBL = '$DNOW', CUST_UPD_R = '$EMPID' WHERE CUST_CODE = '$custC'";
		$this->db->query($insCLogV);
	}
	
	function trashSpl()
	{
		date_default_timezone_set("Asia/Jakarta");

		$DNOW	= date('Y-m-d H:i:s');
		$EMPID	= $this->session->userdata['Emp_ID'];
		$splC	= $_POST['splC'];
		
		$comp_name	= '';
		$insCLogV 	= "UPDATE tbl_supplier SET SPLSTAT = 0, SPLUPD_D = '$DNOW', SPLUPD_R = '$EMPID' WHERE SPLCODE = '$splC'";
		$this->db->query($insCLogV);
	}
	
	function acthSpl()
	{
		date_default_timezone_set("Asia/Jakarta");

		$DNOW	= date('Y-m-d H:i:s');
		$splC	= $_POST['splC'];
		$EMPID	= $this->session->userdata['Emp_ID'];
		
		$comp_name	= '';
		$insCLogV 	= "UPDATE tbl_supplier SET SPLSTAT = 1, SPLUPD_D = '$DNOW', SPLUPD_R = '$EMPID' WHERE SPLCODE = '$splC'";
		$this->db->query($insCLogV);
	}
	
	function delSpl()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
        $splC 		= $colExpl[1];

        $sqlSPL = "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$splC'";
	    $resSPL = $this->db->query($sqlSPL)->result();
	    foreach($resSPL as $rowSPL) :
	        $SPLDESC = $rowSPL->SPLDESC;
	    endforeach;

		$sqlDel	= "DELETE FROM tbl_supplier WHERE SPLCODE = '$splC'";
		$this->db->query($sqlDel);

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Suplier $SPLDESC telah dihapus.";
		}
		else
		{
			$alert1	= "Supplier $SPLDESC has been deleted.";
		}
		echo "$splC";
	}

	function resSyst()
	{
		date_default_timezone_set("Asia/Jakarta");

		$DNOW		= date('Y-m-d H:i:s');
		$EMPID		= $this->session->userdata['Emp_ID'];
		$RHIST_CODE	= date('YmhHis');
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$PrjCode 	= $colExpl[0];
        $RESFULL 	= $colExpl[1];

        $host_name 	= gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $ipaddress	= '';
        if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	        $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'IP Tidak Dikenali';

	    $browser = '';
	    if(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape'))
	        $browser = 'Netscape';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox'))
	        $browser = 'Firefox';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome'))
	        $browser = 'Chrome';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera'))
	        $browser = 'Opera';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
	        $browser = 'Internet Explorer';
	    else
	        $browser = 'Other';

	    $insResHist	= "INSERT INTO tbl_rhist (RHIST_CODE, RHIST_EMPID, RHIST_IPADD, RHIST_HOTSN, RHIST_BROWS, RHIST_DATET)
							VALUES ('$RHIST_CODE', '$EMPID', '$ipaddress', '$host_name', '$browser', '$DNOW')";
		$this->db->query($insResHist);

		$PRJCODE 	= $PrjCode;
		if($RESFULL == 0)		// Hanya Data Transaksi
		{
            $sql003	= "DELETE FROM tbl_alert_list WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);	

        	$sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AMD_NUM 
                            FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_amd_detail WHERE AMD_NUM IN (SELECT AMD_NUM 
                            FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_amd_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_apartement			-- No Reset

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ASEXP_NUM 
                            FROM tbl_asset_exph WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_asset_exph WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_asset_expd WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

        	// tbl_asset_group 			-- No Reset

            $sql003	= "DELETE FROM tbl_asset_joblist WHERE JL_PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

        	// tbl_asset_list 			-- No Reset

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AM_CODE 
                            FROM tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_asset_maintendet WHERE AM_CODE IN (SELECT AM_CODE 
                            FROM tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE')";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql003	= "DELETE FROM tbl_asset_prod WHERE AP_PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql003	= "DELETE FROM tbl_asset_rcost WHERE RASTC_PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql003	= "DELETE FROM tbl_asset_rjob WHERE RASTC_PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ASTSF_NUM 
                            FROM tbl_asset_tsfh WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_asset_tsfd WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_asset_tsfh WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_asset_type			-- No Reset

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AU_CODE 
                            FROM tbl_asset_usage WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_asset_usagedet WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_asset_usage WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT AUR_CODE 
                            FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);

            $sql003	= "DELETE FROM tbl_assetexp_concl WHERE RASTXP_PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT RASSET_CODE 
                            FROM tbl_assetexp_header WHERE RASSET_PROJECT = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_assetexp_detail WHERE RASSETD_PROJECT = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_assetexp_header WHERE RASSET_PROJECT = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_auth 				-- Not Used
            // tbl_balances				-- Not Used
            // tbl_bgheader				-- Not Used

            $sql003	= "DELETE FROM tbl_bobot WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT BOM_NUM 
                            FROM tbl_bom_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            
            $sql002	= "DELETE FROM tbl_bom_detail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_bom_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);
            $sql004	= "DELETE FROM tbl_bom_stfdetail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql004);
            $sql004	= "DELETE FROM tbl_bom_stfdetail_qrc WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql004);

            $sql003	= "DELETE FROM tbl_boq_hist WHERE BOQH_PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);
            
            $sql003	= "DELETE FROM tbl_boqlist WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);
            
            $sql003	= "DELETE FROM tbl_boqlistm WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JournalH_Code 
                            FROM tbl_bp_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_bp_detail WHERE JournalH_Code IN (SELECT JournalH_Code 
                            FROM tbl_bp_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_bp_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JournalH_Code 
                            FROM tbl_br_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_br_detail WHERE JournalH_Code IN 
                        (SELECT JournalH_Code FROM tbl_br_header 
                        WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_br_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_cashbank				-- No Reset
            // tbl_cb_detail			-- No Reset / Not Used
            // tbl_cb_header			-- No Reset / Not Used

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT CCAL_NUM 
                            FROM tbl_ccal_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_ccal_detail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_ccal_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql003	= "DELETE FROM tbl_ccoa WHERE CCOA_PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_cf_report_in, tbl_cf_report_out		-- Not Used

            // RE-COUNT CHART ACCOUNT FROM JURNAL FOR ALL RELATION CHART ACCOUNT
                $sqlJOURND	= "SELECT A.JournalH_Date, A.Acc_Id, A.proj_Code, A.Base_Debet, 
                                    A.Base_Debet_tax, A.Base_Kredit, A.Base_Kredit_tax
                                FROM tbl_journaldetail A INNER JOIN tbl_journalheader B 
                                    ON A.JournalH_Code = B.JournalH_Code
                                WHERE B.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3";
                $resJOURND	= $this->db->query($sqlJOURND)->result();
                foreach($resJOURND as $rowJD) :
					$GEJ_DATE			= $rowJD->JournalH_Date;
					$accYr				= date('Y', strtotime($GEJ_DATE));
                    $Acc_Id				= $rowJD->Acc_Id;
                    $proj_Code			= $rowJD->proj_Code;
                    $Base_Debet			= $rowJD->Base_Debet;
                    $Base_Debet_tax		= $rowJD->Base_Debet_tax;
                    $Base_Kredit		= $rowJD->Base_Kredit;
                    $Base_Kredit_tax	= $rowJD->Base_Kredit_tax;
                    
                    // LOOP PROJECT
                    $syncPRJ	= '';
                    $sqlISHO 	= "SELECT syncPRJ FROM tbl_chartaccount
                                    WHERE PRJCODE = '$proj_Code'
                                        AND Account_Number = '$Acc_Id' LIMIT 1";
                    $resISHO	= $this->db->query($sqlISHO)->result();
                    foreach($resISHO as $rowISHO):
                        $syncPRJ= $rowISHO->syncPRJ;
                    endforeach;
                    $dataPecah 	= explode("~",$syncPRJ);
                    $jmD 		= count($dataPecah);
                
                    if($jmD > 0)
                    {
                        $SYNC_PRJ	= '';
                        for($i=0; $i < $jmD; $i++)
                        {
                            $SYNC_PRJ	= $dataPecah[$i];
                            $sqlCOA		= "UPDATE tbl_chartaccount SET 
		                                        Base_Debet = Base_Debet - $Base_Debet, 
		                                        Base_Debet_tax = Base_Debet_tax - $Base_Debet_tax, 
		                                        Base_Debet2 = Base_Debet2 - $Base_Debet,
		                                        Base_Debet_tax2 = Base_Debet_tax2 - $Base_Debet_tax,
		                                        Base_Kredit = Base_Kredit - $Base_Kredit,
		                                        Base_Kredit_tax = Base_Kredit_tax - $Base_Kredit_tax, 
		                                        Base_Kredit2 = Base_Kredit2 - $Base_Kredit, 
		                                        Base_Kredit_tax2 = Base_Kredit_tax2 - $Base_Kredit_tax 
	                                        WHERE Account_Number = '$Acc_Id' 
	                                        AND PRJCODE = '$SYNC_PRJ'";
                            $this->db->query($sqlCOA);
                        }
                    }
                endforeach;
                
            // CLEAR CHART ACCOUNT
                $sqlCOA	= "UPDATE tbl_chartaccount SET 
                                Base_Debet = 0, 
                                Base_Debet_tax = 0, 
                                Base_Debet2 = 0,
                                Base_Debet_tax2 = 0,
                                Base_Kredit = 0,
                                Base_Kredit_tax = 0, 
                                Base_Kredit2 = 0, 
                                Base_Kredit_tax2 = 0
                            WHERE PRJCODE = '$PRJCODE'";
                $this->db->query($sqlCOA);

            //, tbl_chartaccountm, 			-- Not Used
            // tbl_chartcategory,			-- No Reset
            // tbl_chat, tbl_chat_detail, 	-- Not Used

            $sql003	= "DELETE FROM tbl_coa_uphist WHERE COAH_PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_coadetail, tbl_cssjs, tbl_currate, tbl_currconv, tbl_currency, 	-- No Reset
            // tbl_custcat, tbl_customer, tbl_customer_img							-- No Reset

            $sql003	= "DELETE FROM tbl_dash_data WHERE PRJ_CODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_dash_hr, tbl_dash_sett, tbl_dash_sett_emp, tbl_dash_sett_hr, tbl_dash_sett_hr_emp,	-- No Reset

            $sql003	= "DELETE FROM tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_dash_transac_all, tbl_decreaseinvoice, tbl_department, tbl_doc_cc, tbl_docpattern,	-- No Reset
            // tbl_docstepapp, tbl_docstepapp_det, tbl_document, 										-- No Reset

            $sql003	= "DELETE FROM tbl_dp_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_dp_report, tbl_dp_report_in, tbl_dp_report_out, tbl_dpr_header, tbl_driver,			-- No Reset
            // tbl_dwlhist, tbl_emp_vers, tbl_employee, tbl_employee_acc, tbl_employee_age, 			-- No Reset
            // tbl_employee_appauth, tbl_employee_circle, tbl_employee_docauth, tbl_employee_gol,		-- No Reset
            // tbl_employee_img, tbl_employee_proj, 													-- No Reset

            $sql003	= "DELETE FROM tbl_financial_monitor WHERE FM_PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql003	= "DELETE FROM tbl_financial_track WHERE FT_PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT FPA_NUM 
                        FROM tbl_fpa_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            
            $sql002	= "DELETE FROM tbl_fpa_detail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_fpa_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT FU_CODE 
                            FROM tbl_fuel_usage WHERE FU_PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            
            $sql002	= "DELETE FROM tbl_fuel_usage WHERE FU_PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);

            // tbl_genfileupload, tbl_hrdoc_header, tbl_htu, tbl_import, tbl_indikator,					-- No Reset
            // tbl_inv_detail, tbl_inv_header,  														-- No Reset

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT IR_NUM 
                            FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            
            $sql002	= "DELETE FROM tbl_ir_detail WHERE IR_NUM IN (SELECT IR_NUM 
                            FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);
            $sql003	= "DELETE FROM tbl_ir_detail_trash WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // RESET ITEM
                $sql003	= "UPDATE tbl_item SET 
                                ITM_VOLMBGR = ITM_VOLMBG, ITM_VOLM = 0,
                                ITM_REMQTY = 0, ITM_TOTALP = 0, ITM_LASTP = ITM_PRICE,
                                ITM_IN = 0, ITM_INP = 0, ITM_OUT = 0, ITM_OUTP = 0, 
                                PR_VOLM = 0, PR_AMOUNT = 0, MR_VOLM = 0, MR_AMOUNT = 0,
                                PO_VOLM = 0, PO_AMOUNT = 0, IR_VOLM = 0, IR_AMOUNT = 0, 
                                UM_VOLM = 0, UM_AMOUNT = 0, SO_VOLM = 0, SO_AMOUNT = 0,
                                JO_VOLM = 0, JO_AMOUNT = 0, PROD_VOLM = 0, PROD_AMOUNT = 0, 
                                RET_VOLM = 0, RET_AMOUNT = 0, ADDVOLM = 0, ADDCOST = 0, 
                                ADDMVOLM = 0, ADDMCOST = 0, ITM_VOLMBON = 0
                            WHERE PRJCODE = '$PRJCODE'";
                $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ADJ_NUM 
                            FROM tbl_item_adjh WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_item_adjd WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_item_adjh WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);
            
            $sql003	= "DELETE FROM tbl_item_colld WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);
            
            $sql003	= "DELETE FROM tbl_item_collh WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);
            
            $sql003	= "DELETE FROM tbl_item_cutd WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);
            
            $sql003	= "DELETE FROM tbl_item_cuth WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);
        
            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT ITMTSF_NUM 
                            FROM tbl_item_tsfh WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_item_tsfd WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_item_tsfh WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_item_uphist WHERE ITMH_PRJCODE = '$PRJCODE'";
            $this->db->query($sql001);

            $sql001	= "DELETE FROM tbl_item_whqty WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql001);

            // tbl_itemcategory, tbl_itemgroup				-- No Reset

            $sql003	= "DELETE FROM tbl_itemhistory WHERE proj_Code = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOC_CODE 
                            FROM tbl_jo_concl WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            
            $sql002	= "DELETE FROM tbl_jo_concl WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
        
            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JO_NUM 
                            FROM tbl_jo_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_jo_detail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql002	= "DELETE FROM tbl_jo_detail_tmp3 WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_jo_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOSTF_NUM 
                            FROM tbl_jo_stfdetail WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            
            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT JOSTF_NUM 
                            FROM tbl_jo_stfdetail_qrc WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            
            $sql002	= "DELETE FROM tbl_jo_stfdetail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            
            $sql002	= "DELETE FROM tbl_jo_stfdetail_qrc WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            
            // RESET JOBLIST
                $sql004	= "UPDATE tbl_joblist SET BOQ_PROGR = 0, 
                                ADD_VOLM = 0, ADD_PRICE = 0, 
                                ADD_JOBCOST = 0, ADDM_VOLM = 0, ADDM_JOBCOST = 0 
                            WHERE PRJCODE = '$PRJCODE'";
                $this->db->query($sql004);
                
            // RESET JOBLIST DETAIL
                $sql005	= "UPDATE tbl_joblist_detail SET ITM_LASTP = ITM_PRICE,
                                BOQ_AMDVOLM = 0, BOQ_AMDPRICE = 0, BOQ_AMDTOTAL = 0, 
                                ADD_VOLM = 0, ADD_PRICE = 0, ADD_JOBCOST = 0, 
                                ADDM_VOLM = 0, ADDM_JOBCOST = 0, REQ_VOLM = 0, REQ_AMOUNT = 0, 
                                PO_VOLM = 0, PO_AMOUNT = 0, IR_VOLM = 0, IR_AMOUNT = 0, 
                                WO_QTY = 0, WO_AMOUNT = 0, OPN_QTY = 0, OPN_AMOUNT = 0, 
                                ITM_USED = 0, ITM_USED_AM = 0, ITM_RET = 0, ITM_RET_AM = 0, 
                                ITM_STOCK = 0, ITM_STOCK_AM = 0
                            WHERE PRJCODE = '$PRJCODE'";
                $this->db->query($sql005);

            // tbl_joblist_detailm, tbl_joblistm, tbl_jobopname,	-- Not Used 
            
            // CLEAR JOURNAL
                $sql001	= "DELETE FROM tbl_journalheader WHERE proj_Code = '$PRJCODE'";
                $this->db->query($sql001);
                
                $sql002	= "DELETE FROM tbl_journaldetail WHERE proj_Code = '$PRJCODE'";
                $this->db->query($sql002);

            // tbl_language, tbl_lastsync, tbl_link_account, tbl_login_concl, tbl_login_hist,		-- No Reset
            // tbl_machine, tbl_machine_itm, tbl_mail_dept, tbl_mail_dept_emp, tbl_mail_detail, 	-- No Reset
            // tbl_mail_header, tbl_mail_type, tbl_mailbox, tbl_mailbox_reply, tbl_mailbox_send,	-- No Reset
            // tbl_mailbox_trash, tbl_mailbox_trash_ext, tbl_mailgroup_detail, tbl_mailgroup_header,-- No Reset
            // tbl_major_app, tbl_master_item, tbl_mc_balance										-- No Reset

            $sql003	= "DELETE FROM tbl_mc_conc WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql003	= "DELETE FROM tbl_mc_plan WHERE MCP_PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT MCH_CODE 
                            FROM tbl_mcg_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_mcg_detail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_mcg_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);
        
            $sql003	= "DELETE FROM tbl_mcheader WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_meeting_room						-- No Reset

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT MR_NUM 
                            FROM tbl_mr_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_mr_detail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_mr_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_news_detail, tbl_news_header		-- No Reset

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT OFF_NUM 
                            FROM tbl_offering_h WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_offering_d WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_offering_h WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT OPNH_NUM 
                            FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_opn_detail WHERE OPNH_NUM IN (SELECT OPNH_NUM 
                            FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_opn_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_opn_inv, tbl_opn_invdet, 		-- Not Used
            // tbl_outpay_report, tbl_overhead, 	-- Not Used
            // tbl_owner, tbl_owner_img,			-- No Reset
            // tbl_payterm							-- No Reset

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT INV_NUM 
                            FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_pinv_detail WHERE INV_NUM IN (SELECT INV_NUM 
                            FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PO_NUM 
                            FROM tbl_po_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_po_detail WHERE PO_NUM IN (SELECT PO_NUM 
                            FROM tbl_po_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_po_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_position, tbl_position_func, tbl_position_str, 						-- No Reset

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PR_NUM 
                            FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_pr_detail WHERE PR_NUM IN (SELECT PR_NUM 
                            FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);
            $sql003	= "DELETE FROM tbl_pr_detail_trash WHERE PR_NUM IN (SELECT PR_NUM 
                            FROM tbl_pr_header_trash WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql003);
            $sql003	= "DELETE FROM tbl_pr_header_trash WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_printdoc, tbl_printdoc_wo,											-- Not Used
            // tbl_prodstep, 															-- No Reset

            $sql003	= "DELETE FROM tbl_profitloss WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_profloss_man,														-- Not Used
            // tbl_progg_uphist,														-- Not Used
            // tbl_project, tbl_project_active, tbl_project_budg, tbl_project_budgm, 	-- No Reset

            $sql002	= "DELETE FROM tbl_project_concl WHERE PROGG_PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PRJP_NUM 
                            FROM tbl_project_progress WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_project_progress_det WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_project_progress WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_project_recom, tbl_project_recom_hist,								-- Not Used
            // tbl_projhistory, tbl_projinv_detail										-- Not Used

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT PINV_CODE 
                            FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql003	= "DELETE FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_projinv_realh, tbl_projplan_material									-- Not Used

            $sql002	= "UPDATE tbl_projprogres SET Prg_Real = 0, Prg_RealAkum = 0, 
                            Prg_Dev = 0, isShowRA = 0, isShowDev = 0,
                            isShowRA = 0, isShowDev = 0, lastStepPS = 0
                        WHERE proj_Code = '$PRJCODE'";
            $this->db->query($sql002);

            // tbl_purch_report, tbl_qhsedoc_header, 									-- Not Used

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT QRC_NUM 
                            FROM tbl_qrc_detail WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_qrc_detail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);

            $sql003	= "DELETE FROM tbl_qty_coll WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_reservation,															-- Not Used
        
            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT RET_NUM 
                            FROM tbl_ret_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_ret_detail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_ret_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_riskcategory, tbl_riskdescdet, tbl_riskidentif, tbl_riskimpactdet, tbl_riskpolicydet,	-- Not Used
            // tbl_rtflista, tbl_rtflistb, tbl_schedule, tbl_section, tbl_sementara,	-- Not Used

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SIC_CODE 
                            FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_sicertificatedet WHERE SIC_CODE IN (SELECT SIC_CODE 
                            FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_sicertificate WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql003	= "DELETE FROM tbl_siheader WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql003	= "DELETE FROM tbl_sinv_detail_qrc WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SINV_NUM 
                            FROM tbl_sinv_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_sinv_detail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_sinv_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql003	= "DELETE FROM tbl_sn_detail_qrc WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SN_NUM 
                            FROM tbl_sn_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_sn_detail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_sn_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SO_NUM 
                            FROM tbl_so_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_so_detail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_so_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);
            $sql003	= "DELETE FROM tbl_so_concl WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_sopn_concl, tbl_sopn_detail, tbl_sopn_header, tbl_spkprint,			-- Not Used

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT SR_NUM 
                            FROM tbl_sr_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_sr_detail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql002	= "DELETE FROM tbl_sr_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql002	= "DELETE FROM tbl_sr_detail_qrc WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT STF_NUM 
                            FROM tbl_stf_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_stf_detail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_stf_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_stf_mtrused,															-- Not Used
            // tbl_supplier, tbl_task_request, tbl_task_request_detail,					-- No Reset
            // tbl_tax, tbl_tax_la, tbl_tax_ppn, tbl_tax_ppn_copy, tbl_trackcreater,	-- No Reset 

            $sql002	= "DELETE FROM tbl_trail_tracker WHERE TTR_PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);

            $sql003	= "DELETE FROM tbl_trans_count WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_translate, tbl_trashsys,

            $sql003	= "DELETE FROM tbl_ttk WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT TTK_NUM 
                            FROM tbl_ttk_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_ttk_detail WHERE TTK_NUM IN (SELECT TTK_NUM 
                            FROM tbl_ttk_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_ttk_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);
            $sql003	= "DELETE FROM tbl_ttk_print WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_ttkestinvoice														-- Not Used

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT UM_NUM 
                            FROM tbl_um_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_um_detail WHERE UM_NUM IN (SELECT UM_NUM 
                            FROM tbl_um_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_um_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            // tbl_unittype,															-- No Reset
            // tbl_uploadbca, tbl_uploadbca_data, tbl_uploadreceipt, tbl_uploadttkest,	-- Not Used
            // tbl_userdoctype,															-- Not Used
            // tbl_vehicle, tbl_vendcat, tbl_warehouse, tbl_wip, 						-- No Reset

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT WO_NUM 
                            FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_wo_detail WHERE WO_NUM IN (SELECT WO_NUM 
                            FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);
            $sql003	= "DELETE FROM tbl_wo_print WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

            $sql001	= "DELETE FROM tbl_approve_hist WHERE AH_CODE IN (SELECT WO_NUM 
                            FROM tbl_woreq_header WHERE PRJCODE = '$PRJCODE')";
            $this->db->query($sql001);
            $sql002	= "DELETE FROM tbl_woreq_detail WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql002);
            $sql003	= "DELETE FROM tbl_woreq_header WHERE PRJCODE = '$PRJCODE'";
            $this->db->query($sql003);

        	// tglobalsetting, tusermenu
                
            // OTHERS
                $sqloth01	= "TRUNCATE tbl_login_hist";
                // $this->db->query($sqloth01);
        }
		elseif($RESFULL == 1)	// Bersihkan Semua Data
		{
			$barPr	= 0;
	            $sql001	= "TRUNCATE tbl_alert_list"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_approve_hist"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_amd_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_amd_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_apartement"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_asset_exph"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_asset_expd"; $this->db->query($sql001);
	        	// tbl_asset_group 			-- No Reset
	            $sql001	= "TRUNCATE tbl_asset_joblist"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_asset_list"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_asset_maintendet"; $this->db->query($sql001);


			$barPr	= 5;
	            $sql001	= "TRUNCATE tbl_asset_mainten"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_asset_prod"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_asset_rcost"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_asset_rjob"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_asset_tsfh"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_asset_tsfd"; $this->db->query($sql001);
	            // tbl_asset_type			-- No Reset
	            $sql001	= "TRUNCATE tbl_asset_usagedet"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_asset_usage"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_asset_usagereq"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_assetexp_concl"; $this->db->query($sql001);


			$barPr	= 10;
	            $sql001	= "TRUNCATE tbl_assetexp_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_assetexp_detail"; $this->db->query($sql001);
	            // tbl_auth 				-- Not Used
	            $sql001	= "TRUNCATE tbl_balances"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_bgheader"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_bobot"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_bom_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_bom_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_bom_stfdetail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_bom_stfdetail_qrc"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_boq_hist"; $this->db->query($sql001);


			$barPr	= 15;
	            $sql001	= "TRUNCATE tbl_boqlist"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_boqlistm"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_bp_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_bp_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_br_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_br_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_cashbank"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_cb_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_cb_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_ccal_detail"; $this->db->query($sql001);


			$barPr	= 20;
	            $sql001	= "TRUNCATE tbl_ccal_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_ccoa"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_cf_report_in"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_cf_report_out"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_chartaccount"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_chartaccountm"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_chat"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_chat_detail"; $this->db->query($sql001);
	            // tbl_chartcategory,			-- No Reset
	            $sql001	= "TRUNCATE tbl_coa_uphist"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_coadetail"; $this->db->query($sql001);


			$barPr	= 25;
	            // tbl_cssjs, tbl_currate, tbl_currconv, tbl_currency, 			-- No Reset
	            // tbl_custcat,													-- No Reset
	            $sql001	= "TRUNCATE tbl_customer"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_customer_img"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_dash_data"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_dash_hr"; $this->db->query($sql001);
	            // , tbl_dash_sett, , tbl_dash_sett_hr, tbl_dash_sett_hr_emp,	-- No Reset
	            //$sql001	= "TRUNCATE tbl_dash_sett_emp"; $this->db->query($sql001);
	            $sql003	= "DELETE FROM tbl_dash_sett_emp WHERE EMP_ID != '$EMPID'";
            	$this->db->query($sql003);
	            $sql001	= "TRUNCATE tbl_dash_transac"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_dash_transac_all"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_decreaseinvoice"; $this->db->query($sql001);
	            // tbl_department, tbl_doc_cc, tbl_docpattern,					-- No Reset
	            $sql001	= "TRUNCATE tbl_docstepapp"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_docstepapp_det"; $this->db->query($sql001);


			$barPr	= 30;
	            $sql001	= "TRUNCATE tbl_document"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_dp_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_dp_report"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_dp_report_in"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_dp_report_out"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_dpr_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_driver"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_dwlhist"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_emp_vers"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_employee_acc"; $this->db->query($sql001);


			$barPr	= 35;
	            // tbl_employee, tbl_employee_age, 								-- No Reset
	            $sql001	= "TRUNCATE tbl_employee_appauth"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_employee_docauth"; $this->db->query($sql001);
	            // tbl_employee_circle, tbl_employee_gol,						-- No Reset
	            // tbl_employee_img,											-- No Reset
				$sql002	= "DELETE FROM tbl_employee_proj WHERE proj_Code NOT IN (SELECT PRJCODE FROM tbl_project WHERE PRJTYPE = 1)";
	            $this->db->query($sql002);
	            $sql001	= "TRUNCATE tbl_financial_monitor"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_financial_track"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_fpa_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_fpa_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_fuel_usage"; $this->db->query($sql001);
	            // tbl_hrdoc_header, tbl_htu, tbl_import, tbl_indikator,		-- No Reset
	            $sql001	= "TRUNCATE tbl_genfileupload"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_inv_detail"; $this->db->query($sql001);


			$barPr	= 40;
	            $sql001	= "TRUNCATE tbl_inv_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_ir_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_ir_detail_trash"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_ir_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_item"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_item_adjd"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_item_adjh"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_item_colld"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_item_collh"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_item_cutd"; $this->db->query($sql001);


			$barPr	= 45;
	            $sql001	= "TRUNCATE tbl_item_cuth"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_item_tsfd"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_item_tsfh"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_item_uphist"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_item_whqty"; $this->db->query($sql001);
	            // tbl_itemcategory, tbl_itemgroup								-- No Reset
	            $sql001	= "TRUNCATE tbl_itemhistory"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_jo_concl"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_jo_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_jo_detail_tmp3"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_jo_header"; $this->db->query($sql001);


			$barPr	= 50;
	            $sql001	= "TRUNCATE tbl_jo_stfdetail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_jo_stfdetail_qrc"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_joblist"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_joblist_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_joblist_detailm"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_joblistm"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_jobopname"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_journaldetail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_journalheader"; $this->db->query($sql001);
	            // tbl_language, , tbl_link_account,							-- No Reset
	            $sql001	= "TRUNCATE tbl_lastsync"; $this->db->query($sql001);


			$barPr	= 55;
	            $sql001	= "TRUNCATE tbl_login_concl"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_login_hist"; $this->db->query($sql001);
	            // tbl_machine, tbl_mail_dept,									-- No Reset
	            $sql001	= "TRUNCATE tbl_machine_itm"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mail_dept_emp"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mail_detail"; $this->db->query($sql001);
	            // tbl_mail_type,												-- No Reset
	            $sql001	= "TRUNCATE tbl_mail_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mailbox"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mailbox_reply"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mailbox_send"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mailbox_trash"; $this->db->query($sql001);


			$barPr	= 57;
	            $sql001	= "TRUNCATE tbl_mailbox_trash_ext"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mailgroup_detail"; $this->db->query($sql001);
	            // tbl_mailgroup_header,										-- No Reset
	            $sql001	= "TRUNCATE tbl_major_app"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_master_item"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mc_balance"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mc_conc"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mc_plan"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mcg_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mcg_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mcheader"; $this->db->query($sql001);


			$barPr	= 60;
	            $sql001	= "TRUNCATE tbl_meeting_room"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mr_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_mr_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_news_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_news_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_offering_d"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_offering_h"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_opn_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_opn_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_opn_inv"; $this->db->query($sql001);


			$barPr	= 65;
	            $sql001	= "TRUNCATE tbl_opn_invdet"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_outpay_report"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_overhead"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_owner"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_owner_img"; $this->db->query($sql001);
	            // tbl_payterm													-- No Reset
	            $sql001	= "TRUNCATE tbl_pinv_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_pinv_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_po_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_po_header"; $this->db->query($sql001);
	            // tbl_position, tbl_position_func, tbl_position_str, 			-- No Reset
	            $sql001	= "TRUNCATE tbl_pr_detail"; $this->db->query($sql001);


			$barPr	= 67;
	            $sql001	= "TRUNCATE tbl_pr_detail_trash"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_pr_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_pr_header_trash"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_printdoc"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_printdoc_wo"; $this->db->query($sql001);
	            // tbl_prodstep, 												-- No Reset
	            $sql001	= "TRUNCATE tbl_profitloss"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_profloss_man"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_progg_uphist"; $this->db->query($sql001);


			$barPr	= 70;
	            $sql002	= "DELETE FROM tbl_project WHERE PRJTYPE != 1";
	            $this->db->query($sql002);
	            $sql001	= "TRUNCATE tbl_project_active"; $this->db->query($sql001);
	            $sql002	= "DELETE FROM tbl_project_budg WHERE PRJTYPE != 1";
	            $this->db->query($sql002);
	            $sql002	= "DELETE FROM tbl_project_budgm WHERE PRJTYPE != 1";
	            $this->db->query($sql002);
	            $sql001	= "TRUNCATE tbl_project_progress"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_project_progress_det"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_project_recom"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_project_recom_hist"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_projhistory"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_projinv_detail"; $this->db->query($sql001);


			$barPr	= 75;
	            $sql001	= "TRUNCATE tbl_projinv_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_projinv_realh"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_projplan_material"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_projprogres"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_purch_report"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_qhsedoc_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_qrc_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_qty_coll"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_reservation"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_ret_detail"; $this->db->query($sql001);


			$barPr	= 80;
	            $sql001	= "TRUNCATE tbl_ret_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_riskcategory"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_riskdescdet"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_riskidentif"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_riskimpactdet"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_riskpolicydet"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_rtflista"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_rtflistb"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_schedule"; $this->db->query($sql001);

            	// tbl_section,													-- Not Used


			$barPr	= 84;
	            $sql001	= "TRUNCATE tbl_sementara"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_sicertificate"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_sicertificatedet"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_siheader"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_sinv_detail_qrc"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_sinv_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_sinv_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_sn_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_sn_detail_qrc"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_sn_header"; $this->db->query($sql001);


			$barPr	= 88;
	            $sql001	= "TRUNCATE tbl_so_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_so_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_so_concl"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_sopn_concl"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_sopn_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_sopn_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_spkprint"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_sr_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_sr_detail_qrc"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_sr_header"; $this->db->query($sql001);


			$barPr	= 92;
	            $sql001	= "TRUNCATE tbl_stf_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_stf_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_stf_mtrused"; $this->db->query($sql001);
	            // tbl_supplier,												-- No Reset
	            $sql001	= "TRUNCATE tbl_task_request"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_task_request_detail"; $this->db->query($sql001);
	            // tbl_tax, tbl_tax_la, tbl_tax_ppn, tbl_tax_ppn_copy,			-- No Reset
	            $sql001	= "TRUNCATE tbl_trackcreater"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_trail_tracker"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_trans_count"; $this->db->query($sql001);
	            // tbl_translate, tbl_trashsys,
	            $sql001	= "TRUNCATE tbl_ttk"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_ttk_detail"; $this->db->query($sql001);


			$barPr	= 96;
	            $sql001	= "TRUNCATE tbl_ttk_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_ttk_print"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_ttkestinvoice"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_um_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_um_header"; $this->db->query($sql001);
	            // tbl_unittype,												-- No Reset
	            $sql001	= "TRUNCATE tbl_uploadbca"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_uploadbca_data"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_uploadreceipt"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_uploadttkest"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_userdoctype"; $this->db->query($sql001);


			$barPr	= 100;
	            // tbl_vehicle, tbl_vendcat,		 							-- No Reset
	            $sql001	= "TRUNCATE tbl_warehouse"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_wip"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_wo_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_wo_header"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_wo_print"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_woreq_detail"; $this->db->query($sql001);
	            $sql001	= "TRUNCATE tbl_woreq_header"; $this->db->query($sql001);

	        	// tglobalsetting, tusermenu
		}

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Sistem telah selesai melakukan pengaturan ulang sistem oleh $ipaddress - $host_name melalui $browser. Anda akan keluar.";
		}
		else
		{
			$alert1	= "The system has finished system reset by $ipaddress - $host_name in $browser. You will log out.";
		}
		echo "$alert1";
	}
	
	function trashCAT()
	{
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $attNm 		= $colExpl[2];
        $attCode	= $colExpl[3];
        $alert1		= $colExpl[4];
		
		$delDATA 	= "DELETE FROM $tblNameH WHERE $attNm = '$attCode'";
		$this->db->query($delDATA);
		echo "$alert1";
	}

	function trashSN()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 		= $_POST['collID'];
		$colExpl		= explode("~", $collID);
		$url 			= $colExpl[0];
        $tblNameH 		= $colExpl[1];
        $tblNameD 		= $colExpl[2];
        $DocNm			= $colExpl[3];
        $DocNum			= $colExpl[4];
        $PrjNm			= $colExpl[5];
        $PrjCode		= $colExpl[6];

        $SN_NUM			= $DocNum;
        $proj_Code		= $PrjCode;
        $PRJCODE 		= $PrjCode;

        $SN_NOTES1 		= "";
        $SN_STAT 		= 9;
        $DefEmp_ID 		= $this->session->userdata['Emp_ID'];
        $comp_init 		= $this->session->userdata('comp_init');
        $SN_CREATED 	= date('Y-m-d H:i:s');

        $this->load->model('m_updash/m_updash', '', TRUE);

        $SNCODE 		= "";
		$SN_TYPE 		= 1;
		$SN_DATE 		= "";
		$sqlSNTYP		= "SELECT SN_CODE, SN_TYPE, SN_DATE FROM tbl_sn_header WHERE PRJCODE = '$PRJCODE' AND SN_NUM = '$SN_NUM' LIMIT 1";
		$resSNTYP		= $this->db->query($sqlSNTYP)->result();
		foreach($resSNTYP as $rowSNTYP) :
			$SNCODE 	= $rowSNTYP->SN_CODE;
			$SN_TYPE 	= $rowSNTYP->SN_TYPE;
			$SN_DATE 	= $rowSNTYP->SN_DATE;
		endforeach;

        // UPDATE STATUS
			$sqlUPSN	= "UPDATE tbl_sn_header SET SN_STAT = '9', STATDESC = 'Void', STATCOL = 'danger' WHERE SN_NUM = '$SN_NUM'";
			$this->db->query($sqlUPSN);

		// UPDATE DETAIL : PENGEMBALIAN STOCK
			$SN_TOTVOLM	= 0;
			$SN_TOTCOST	= 0;
			$SN_TOTDISC	= 0;
			$SN_TOTPPN	= 0;
			$SN_TOTPPH	= 0;
			$sqlGetSN	= "SELECT A.SN_NUM, A.SN_CODE, A.ITM_CODE, A.SN_VOLM, A.SN_PRICE, A.SN_DISC, A.SN_TOTAL, A.TAXPRICE1, A.TAXPRICE2,
								B.SO_NUM, B.SO_CODE
							FROM tbl_sn_detail A
								INNER JOIN tbl_sn_header B ON A.SN_NUM = B.SN_NUM
							WHERE A.SN_NUM = '$SN_NUM' AND A.PRJCODE = '$PRJCODE'";
			$resGetSN	= $this->db->query($sqlGetSN)->result();
			foreach($resGetSN as $rowSN) :
				$SN_NUM 		= $rowSN->SN_NUM;
				$SN_CODE 		= $rowSN->SN_CODE;
				$SO_NUM 		= $rowSN->SO_NUM;
				$SO_CODE 		= $rowSN->SO_CODE;
				$ITM_CODE 		= $rowSN->ITM_CODE;
				$SN_VOLM		= $rowSN->SN_VOLM;
				$SN_PRICE		= $rowSN->SN_PRICE;
				$SN_DISC		= $rowSN->SN_DISC;
				$SN_TOTAL		= $rowSN->SN_TOTAL;
				$TAXPRICE1		= $rowSN->TAXPRICE1;
				$TAXPRICE2		= $rowSN->TAXPRICE2;
				
				$SN_VOLM_NOW	= $rowSN->SN_VOLM;
				$SN_PRICE_NOW	= $rowSN->SN_PRICE;
				$SN_COST_NOW	= $SN_VOLM_NOW * $SN_PRICE_NOW;

				$ITM_GROUP 		= 'M';
				$ITM_CATEG 		= 'M';
				$sqlITMCAT		= "SELECT ITM_GROUP, ITM_CATEG FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resITMCAT		= $this->db->query($sqlITMCAT)->result();
				foreach($resITMCAT as $rowicat) :
					$ITM_GROUP 	= $rowicat->ITM_GROUP;
					$ITM_CATEG 	= $rowicat->ITM_CATEG;
				endforeach;

				$sqlUpd			= "UPDATE tbl_so_detail SET SN_VOLM = SN_VOLM - $SN_VOLM_NOW, SN_AMOUNT = SN_AMOUNT - $SN_COST_NOW
									WHERE SO_NUM = '$SO_NUM' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlUpd);

				$sqlUpd2		= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM + $SN_VOLM_NOW, SN_VOLM = SN_VOLM - $SN_VOLM_NOW,
										SN_AMOUNT = SN_AMOUNT - $SN_COST_NOW, ITM_OUT = ITM_OUT - $SN_VOLM_NOW, ITM_OUTP = ITM_OUTP - $SN_COST_NOW
									WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sqlUpd2);

				// PEREKAMAN JEJAK KE tbl_itemhistory
					$sqlHist 	= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
										QtyRR_Plus, QtyRR_Min, QtySN_Plus, QtySN_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
										JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
									VALUES ('$SN_NUM', '$PRJCODE', '$SN_CREATED', '$ITM_CODE', $SN_VOLM_NOW, 0, 
										0, 0, $SN_VOLM_NOW, 0, 'V-SN', $SN_COST_NOW, '$comp_init', 'IDR', 
										'', 9, '$SN_PRICE', '$ITM_CATEG', 'Pengembalian Pengiriman : $SN_CODE ($SO_CODE)')";
					$this->db->query($sqlHist);

				// PROFIT AND LOSS
					$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);

					$PERIODM	= date('m', strtotime($SN_DATE));
					$PERIODY	= date('Y', strtotime($SN_DATE));
					if($ITM_GROUP == 'M')
					{
						// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFULE, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
						if($ITM_TYPE == 1)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR+$SN_COST_NOW 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$SN_COST_NOW 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM+$SN_COST_NOW
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 9)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP+$SN_COST_NOW
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 10)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG+$SN_COST_NOW
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
					}
					elseif($ITM_GROUP == 'T')
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$SN_COST_NOW 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
			endforeach;

		// UPDATE QRCODE
			if($SN_TYPE == 1)
			{
				$sqlQRCSN 	= "SELECT QRC_NUM, SN_NUM, SN_CODE FROM tbl_sn_detail_qrc WHERE SN_NUM = '$SN_NUM'";
				$resQRCSN	= $this->db->query($sqlQRCSN)->result();
				foreach($resQRCSN as $rowQRCSN) :
					$QRC_NUM 	= $rowQRCSN->QRC_NUM;
					$SN_NUM 	= $rowQRCSN->SN_NUM;
					$SN_CODE 	= $rowQRCSN->SN_CODE;
					// UPDATE
						$updQRC	= "UPDATE tbl_qrc_detail SET QRC_STATS = 0, SN_NUM = '', SN_CODE = '' WHERE QRC_NUM = '$QRC_NUM'";
						$this->db->query($updQRC);

						$updGRP	= "UPDATE tbl_item_colld SET QRC_STATS = 0, SN_NUM = '', SN_CODE = '' WHERE QRC_NUM = '$QRC_NUM'";
						$this->db->query($updGRP);

						$updV	= "UPDATE tbl_sn_detail_qrc SET QRC_ISVOID = 1 WHERE QRC_NUM = '$QRC_NUM'";
						$this->db->query($updV);		
				endforeach;
			}
		
		// UPDATE JOURNAL
			$sqlUpdJH	= "UPDATE tbl_journalheader SET JournalH_Desc3 = 'Void by $DefEmp_ID', LastUpdate = '$SN_CREATED', isCanceled = 1,
								GEJ_STAT = 9, STATDESC = 'Void', STATCOL = 'danger'
							WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$SN_NUM'";
			$this->db->query($sqlUpdJH);

		// UPDATE JOURNAL DETAIL
			$sqlJD			= "SELECT JournalH_Code, JournalH_Date, Acc_Id, proj_Code, Currency_id, JournalD_Debet, Base_Debet, Base_Kredit,
									Journal_DK, ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE
								FROM tbl_journaldetail WHERE JournalH_Code = '$SN_NUM' AND proj_Code = '$PRJCODE'";
			$resJD			= $this->db->query($sqlJD)->result();
			foreach($resJD as $rowJD) :
				$JD_NUM 	= $rowJD->JournalH_Code;
				$GEJ_DATE	= $rowJD->JournalH_Date;
				$accYr		= date('Y', strtotime($GEJ_DATE));
				$ACC_NUM 	= $rowJD->Acc_Id;
				$proj_Code 	= $rowJD->proj_Code;
				$JD_Debet 	= $rowJD->Base_Debet;
				$JD_Kredit 	= $rowJD->Base_Kredit;
				$Journal_DK = $rowJD->Journal_DK;

				$transacValue 		= $JD_Debet;
				// START : Update to COA - Debit
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							if($Journal_DK == 'D')
							{
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$transacValue,
													Base_Debet2 = Base_Debet2-$transacValue, BaseD_$accYr = BaseD_$accYr-$transacValue
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
							}
							else
							{
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit-$transacValue,
													Base_Kredit2 = Base_Kredit2-$transacValue, BaseK_$accYr = BaseK_$accYr-$transacValue
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
							}
							$this->db->query($sqlUpdCOA);
						}
					}
				// END : Update to COA - Debit
			endforeach;

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $SNCODE telah dibatalkan.";
		}
		else
		{
			$alert1	= "Document no. $SNCODE has been void.";
		}
		echo "$alert1";
	}

	function trashIR()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 		= $_POST['collID'];
		$colExpl		= explode("~", $collID);
		$url 			= $colExpl[0];
        $tblNameH 		= $colExpl[1];
        $tblNameD 		= $colExpl[2];
        $DocNm			= $colExpl[3];
        $DocNum			= $colExpl[4];
        $PrjNm			= $colExpl[5];
        $PrjCode		= $colExpl[6];

        $IR_NUM			= $DocNum;
        $proj_Code		= $PrjCode;
        $PRJCODE 		= $PrjCode;

        $IR_NOTE2 		= "Void IR";
        $IR_STAT 		= 9;
        $DefEmp_ID 		= $this->session->userdata['Emp_ID'];
        $comp_init 		= $this->session->userdata('comp_init');
        $SN_CREATED 	= date('Y-m-d H:i:s');

        $this->load->model('m_updash/m_updash', '', TRUE);
        $this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);

        $IRCODE 		= "";
		$IR_SOURCE 		= 1;
		$IR_DATE 		= "";
		$sqlIRH			= "SELECT IR_CODE, IR_SOURCE, IR_DATE FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE' AND IR_NUM = '$IR_NUM' LIMIT 1";
		$resIRH			= $this->db->query($sqlIRH)->result();
		foreach($resIRH as $rowIRH) :
			$IRCODE 	= $rowIRH->IR_CODE;
			$IR_SOURCE 	= $rowIRH->IR_SOURCE;
			$IR_DATE 	= $rowIRH->IR_DATE;
		endforeach;

		// 1. PREPARE
			$DOCSource		= '';
			if($IR_SOURCE == 1)			// DIRECTS
			{
				$Ref_Number 	= '';
				$PO_NUM			= '';
				$DOCSource		= 'Direct';
				$this->m_itemreceipt->updateJOBDET($IR_NUM, $PRJCODE); // UPDATE JOB DETAIL
			}
			elseif($IR_SOURCE == 2)		// MR
			{
				$DOCSource		= "MRXXXXXXXX";
			}
			elseif($IR_SOURCE == 3)		// PO
			{
				$Ref_Number 	= $this->input->post('Ref_NumberPO');
				$PO_NUM			= $Ref_Number;
				$DOCSource		= $PO_NUM;
				//$this->m_itemreceipt->updatePO($IR_NUM, $PRJCODE, $PO_NUM, $ISDIRECT); // UPDATE PO
				//$this->m_itemreceipt->updatePO($IR_NUM, $PRJCODE, $PO_NUM); // UPDATE PO
			}
			elseif($IR_SOURCE == 4)		// GREIGE
			{
				$Ref_Number 	= '';
				$PO_NUM			= '';
				$DOCSource		= 'SO-GREIGE';
			}

		// 2. UPDATE STATUS
			$sqlUPSN	= "UPDATE tbl_ir_header SET IR_STAT = '9', STATDESC = 'Void', STATCOL = 'danger' WHERE IR_NUM = '$IR_NUM'";
			$this->db->query($sqlUPSN);

			$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = 9, STATCOL = 'danger', STATDESC = 'Void' WHERE JournalH_Code = '$IR_NUM'";
			$this->db->query($upJH);

			$upJD	= "UPDATE tbl_journaldetail SET GEJ_STAT = 9, isVoid = 1 WHERE JournalH_Code = '$IR_NUM'";
			$this->db->query($upJD);

		// 3. MEMBUAT JURNAL PEMBALIK
			$sqlDET 	= "SELECT JournalH_Date, Acc_Id, proj_Code, JOBCODEID, ITM_CODE, ITM_GROUP, ITM_VOLM, ITM_PRICE, Base_Debet, Base_Kredit, Journal_DK
							FROM tbl_journaldetail WHERE JournalH_Code = '$IR_NUM'";
			$resDET 	= $this->db->query($sqlDET)->result();
			foreach($resDET as $rowDET) :
				$GEJ_DATE		= $rowDET->JournalH_Date;
				$accYr			= date('Y', strtotime($GEJ_DATE));
				$ACC_NUM 		= $rowDET->Acc_Id;
				$PRJCODE 		= $rowDET->proj_Code;
				$JOBCODEID 		= $rowDET->JOBCODEID;
				$ITM_CODE 		= $rowDET->ITM_CODE;
				$ITM_GROUP 		= $rowDET->ITM_GROUP;
				$ITM_VOLM 		= $rowDET->ITM_VOLM;
				$ITM_PRICE 		= $rowDET->ITM_PRICE;
				$Base_Debet 	= $rowDET->Base_Debet;
				$Base_Kredit 	= $rowDET->Base_Kredit;
				$Journal_DK 	= $rowDET->Journal_DK;

				$ITM_TYPE 	= $this->m_updash->get_itmType($PRJCODE, $ITM_CODE);
				if($ITM_TYPE == 0)
					$ITM_TYPE	= 1;

				$PRJCODE		= $PRJCODE;
				$JOURN_DATE		= $IR_DATE;
				$ITM_GROUP		= $ITM_GROUP;
				$ITM_TYPE		= $ITM_TYPE;
				$ITM_QTY 		= $ITM_VOLM;
				if($ITM_QTY == 0 || $ITM_QTY == '')
					$ITM_QTY	= 1;

				$isHO			= 0;
				$syncPRJ		= '';
				$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$isHO		= $rowISHO->isHO;
					$syncPRJ	= $rowISHO->syncPRJ;
				endforeach;
				$dataPecah 	= explode("~",$syncPRJ);
				$jmD 		= count($dataPecah);
			
				if($jmD > 0)
				{
					$SYNC_PRJ	= '';
					for($i=0; $i < $jmD; $i++)
					{
						$SYNC_PRJ	= $dataPecah[$i];
						if($Journal_DK == 'D')
						{
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$Base_Debet,
												Base_Debet2 = Base_Debet2-$Base_Debet, BaseD_$accYr = BaseD_$accYr-$Base_Debet
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
						}
						else
						{
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit-$Base_Kredit,
												Base_Kredit2 = Base_Kredit2-$Base_Kredit, BaseK_$accYr = BaseK_$accYr-$Base_Kredit
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
						}
						$this->db->query($sqlUpdCOA);
					}
				}
			endforeach;

		// 4. UPDATE DETAIL PR, PO
			$sqlIRDET 	= "SELECT IR_CODE, IR_DATE, ACC_ID, IR_SOURCE, PO_NUM, JOBCODEID, WH_CODE, ITM_CODE, ITM_GROUP, ITM_QTY, ITM_PRICE, ITM_TOTAL,
								POD_ID, ITM_UNIT, ITM_QTY_BONUS, ITM_DISC, TAXCODE1, TAXPRICE1, PR_NUM, PRD_ID
							FROM tbl_ir_detail WHERE IR_NUM = '$IR_NUM' AND  PRJCODE = '$PRJCODE'";
			$resIRDET 	= $this->db->query($sqlIRDET)->result();
			foreach($resIRDET as $rowIRDET) :
				$IR_CODE 		= $rowIRDET->IR_CODE;
				$IR_DATE 		= $rowIRDET->IR_DATE;
				$ACC_ID 		= $rowIRDET->ACC_ID;
				$IR_SOURCE 		= $rowIRDET->IR_SOURCE;
				$PO_NUM 		= $rowIRDET->PO_NUM;
				$POD_ID 		= $rowIRDET->POD_ID;
				$PR_NUM 		= $rowIRDET->PR_NUM;
				$PRD_ID 		= $rowIRDET->PRD_ID;
				$JOBCODEID 		= $rowIRDET->JOBCODEID;
				$WH_CODE 		= $rowIRDET->WH_CODE;
				$ITM_CODE 		= $rowIRDET->ITM_CODE;
				$ITM_GROUP 		= $rowIRDET->ITM_GROUP;
				$ITM_UNIT 		= $rowIRDET->ITM_UNIT;
				$ITM_QTY 		= $rowIRDET->ITM_QTY;
				$ITM_PRICE 		= $rowIRDET->ITM_PRICE;
				$ITM_TOTAL 		= $rowIRDET->ITM_TOTAL;
				$ITM_QTY_BONUS 	= $rowIRDET->ITM_QTY_BONUS;
				$ITM_DISC 		= $rowIRDET->ITM_DISC;
				$TAXCODE1 		= $rowIRDET->TAXCODE1;
				$TAXPRICE1 		= $rowIRDET->TAXPRICE1;

				$ITM_TYPE 	= $this->m_updash->get_itmType($PRJCODE, $ITM_CODE);
				if($ITM_TYPE == 0)
					$ITM_TYPE	= 1;
			
				$param2		= array('JournalH_Code' 	=> $IR_NUM,
									'IR_DATE'			=> $IR_DATE,
									'JOBCODEID' 		=> $JOBCODEID,
									'JOBCODEID' 		=> $JOBCODEID,
									'PO_NUM'			=> $PO_NUM,
									'POD_ID'			=> $POD_ID,
									'PR_NUM'			=> $PR_NUM,
									'PRD_ID'			=> $PRD_ID,
									'PRJCODE'			=> $PRJCODE,
									'ITM_CODE' 			=> $ITM_CODE,
									'ACC_ID' 			=> $ACC_ID,
									'ITM_UNIT' 			=> $ITM_UNIT,
									'ITM_GROUP' 		=> $ITM_GROUP,
									'ITM_QTY' 			=> $ITM_QTY,
									'ITM_QTY_BONUS' 	=> $ITM_QTY_BONUS,
									'ITM_PRICE' 		=> $ITM_PRICE,
									'ITM_DISC' 			=> $ITM_DISC,
									'TAXCODE1' 			=> $TAXCODE1,
									'TAXPRICE1' 		=> $TAXPRICE1,
									'WH_CODE'			=> $WH_CODE,
									'IR_NOTE2'			=> $IR_NOTE2);
				$this->m_itemreceipt->updateIR_PO($IR_NUM, $param2);

				$PERIODM	= date('m', strtotime($IR_DATE));
				$PERIODY	= date('Y', strtotime($IR_DATE));
				$JOURN_VAL 	= $ITM_TOTAL;

				if($ITM_GROUP == 'M')
				{
					// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFUEL, 5.ISLUBTICANT, 6.ISFASTM, 7.ISWAGE, 8.ISRM, 9.ISWIP, 10.ISFG, 11.ISCOST
					if($ITM_TYPE == 1 || $ITM_TYPE == 8)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$JOURN_VAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$JOURN_VAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$JOURN_VAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 9)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP-$JOURN_VAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 10)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG-$JOURN_VAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				}
				elseif($ITM_GROUP == 'T')
				{
					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$JOURN_VAL
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
			endforeach;
				
			$upJD		= "UPDATE tbl_journaldetail SET isVoid = 1 WHERE JournalH_Code = '$IR_NUM'";
			$this->db->query($upJD);
		
		// START : UPDATE TO DOC. COUNT
			$parameters 	= array('DOC_CODE' 		=> $IR_NUM,
									'PRJCODE' 		=> $PRJCODE,
									'DOC_TYPE'		=> "IR",
									'DOC_QTY' 		=> "DOC_IRQ",
									'DOC_VAL' 		=> "DOC_IRV",
									'DOC_STAT' 		=> 'VOID');
			$this->m_updash->updateDocC($parameters);
		// END : UPDATE TO DOC. COUNT
			
        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $IRCODE telah dibatalkan.";
		}
		else
		{
			$alert1	= "Document no. $IRCODE has been void.";
		}
		echo "$alert1";
	}

	function trashSO()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 		= $_POST['collID'];
		$colExpl		= explode("~", $collID);
		$url 			= $colExpl[0];
        $tblNameH 		= $colExpl[1];
        $tblNameD 		= $colExpl[2];
        $DocNm			= $colExpl[3];
        $DocNum			= $colExpl[4];
        $PrjNm			= $colExpl[5];
        $PrjCode		= $colExpl[6];

        $SO_NUM			= $DocNum;
        $proj_Code		= $PrjCode;
        $PRJCODE 		= $PrjCode;

        $SO_MEMO 		= "Void SO";
        $SO_STAT 		= 9;
        $DefEmp_ID 		= $this->session->userdata['Emp_ID'];
        $comp_init 		= $this->session->userdata('comp_init');
        $SO_UPDATED 	= date('Y-m-d H:i:s');

        $this->load->model('m_updash/m_updash', '', TRUE);

		// 1. UPDATE STATUS
			$sqlUPSO	= "UPDATE tbl_so_header SET SO_STAT = '9', STATDESC = 'Void', STATCOL = 'danger' WHERE SO_NUM = '$SO_NUM'";
			$this->db->query($sqlUPSO);

		// 2. UPDATE ITEM
			$SO_CODE 	= "";
			$sqlGetPO	= "SELECT SO_CODE, ITM_CODE, SO_VOLM, SO_PRICE, PROD_VOLM, PROD_PRICE FROM tbl_so_detail WHERE SO_NUM = '$SO_NUM' AND PRJCODE = '$PRJCODE'";
			$resGetPO	= $this->db->query($sqlGetPO)->result();
			foreach($resGetPO as $rowPO) :
				$SO_CODE 		= $rowPO->SO_CODE;
				$ITM_CODE 		= $rowPO->ITM_CODE;
				$SO_VOLM 		= $rowPO->SO_VOLM;
				$SO_PRICE 		= $rowPO->SO_PRICE;
				$SO_AMOUNT		= $SO_VOLM * $SO_PRICE;
				$PROD_VOLM 		= $rowPO->PROD_VOLM;
				$PROD_PRICE	 	= $rowPO->PROD_PRICE;
				$PROD_AMOUNT	= $PROD_VOLM * $PROD_PRICE;
				
				// Kembalikan di tabel Item
					$sqlSO	= "UPDATE tbl_item SET SO_VOLM = SO_VOLM - $SO_VOLM, SO_AMOUNT = SO_AMOUNT - $SO_AMOUNT
								WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sqlSO);
			endforeach;

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $SO_CODE telah dibatalkan.";
		}
		else
		{
			$alert1	= "Document no. $SO_CODE has been void.";
		}
		echo "$alert1";
	}

	function trashPR()
	{
		$collID 		= $_POST['collID'];
		$colExpl		= explode("~", $collID);
		$url 			= $colExpl[0];
        $tblNameH 		= $colExpl[1];
        $tblNameD 		= $colExpl[2];
        $DocNm			= $colExpl[3];
        $DocNum			= $colExpl[4];
        $PrjNm			= $colExpl[5];
        $PrjCode		= $colExpl[6];

        $PR_NUM			= $DocNum;
        $proj_Code		= $PrjCode;
        $PRJCODE 		= $PrjCode;

        $PR_STAT 		= 9;
        $DefEmp_ID 		= $this->session->userdata['Emp_ID'];
        $comp_init 		= $this->session->userdata('comp_init');
        $PR_UPDATED 	= date('Y-m-d H:i:s');

        $this->load->model('m_updash/m_updash', '', TRUE);

		// 1. UPDATE STATUS
			$sqlUPSO	= "UPDATE tbl_pr_header SET PR_STAT = '9', STATDESC = 'Void', STATCOL = 'danger' WHERE PR_NUM = '$PR_NUM'";
			$this->db->query($sqlUPSO);

		// 2. UPDATE ITEM
			$PRCODE = "";
			$sqlPR	= "SELECT PR_CODE, JOBCODEID, ITM_CODE, PR_VOLM, PR_TOTAL FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
			$resPR	= $this->db->query($sqlPR)->result();
			foreach($resPR as $rowPR) :
				$PRCODE		= $rowPR->PR_CODE;
				$JOBCODEID	= $rowPR->JOBCODEID;
				$ITM_CODE	= $rowPR->ITM_CODE;
				$PR_VOLM	= $rowPR->PR_VOLM;
				$PR_TOTAL	= $rowPR->PR_TOTAL;
				
				// RESET PR TABLE
					$sqlPR	= "UPDATE tbl_pr_detail SET PO_VOLM = 0, PO_AMOUNT = 0, IR_VOLM = 0, IR_AMOUNT = 0
								WHERE PR_NUM = '$PR_NUM' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sqlPR);
				
				// RETURN BUDGET QTY IN JOBLIST
					$sqlJLD	= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM - $PR_VOLM, REQ_AMOUNT = REQ_AMOUNT - $PR_TOTAL
								WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sqlJLD);
				
				// RETURN BUDGET QTY IN MASTER ITEM
					$sqlITM	= "UPDATE tbl_item SET PR_VOLM = PR_VOLM - $PR_VOLM, PR_AMOUNT = PR_AMOUNT - $PR_TOTAL
								WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sqlITM);
			endforeach;

		// START : UPDATE TO DOC. COUNT
			$parameters 	= array('DOC_CODE' 		=> $PR_NUM,
									'PRJCODE' 		=> $PRJCODE,
									'DOC_TYPE'		=> "PR",
									'DOC_QTY' 		=> "DOC_PRQ",
									'DOC_VAL' 		=> "DOC_PRV",
									'DOC_STAT' 		=> 'VOID');
			$this->m_updash->updateDocC($parameters);
		// END : UPDATE TO DOC. COUNT

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $PRCODE telah dibatalkan.";
		}
		else
		{
			$alert1	= "Document no. $PRCODE has been void.";
		}
		echo "$alert1";
	}

	function trashPO()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 		= $_POST['collID'];
		$colExpl		= explode("~", $collID);
		$url 			= $colExpl[0];
        $tblNameH 		= $colExpl[1];
        $tblNameD 		= $colExpl[2];
        $DocNm			= $colExpl[3];
        $DocNum			= $colExpl[4];
        $PrjNm			= $colExpl[5];
        $PrjCode		= $colExpl[6];

        $PO_NUM			= $DocNum;
        $proj_Code		= $PrjCode;
        $PRJCODE 		= $PrjCode;

        $PO_STAT 		= 9;
        $DefEmp_ID 		= $this->session->userdata['Emp_ID'];
        $comp_init 		= $this->session->userdata('comp_init');
        $PO_UPDATED 	= date('Y-m-d H:i:s');

        $this->load->model('m_updash/m_updash', '', TRUE);

		// 1. UPDATE STATUS
			$sqlUPSO	= "UPDATE tbl_po_header SET PO_STAT = '9', STATDESC = 'Void', STATCOL = 'danger' WHERE PO_NUM = '$PO_NUM'";
			$this->db->query($sqlUPSO);

		// 2. UPDATE ITEM
			$POCODE = "";
			$sqlPO	= "SELECT PR_NUM, PO_CODE, JOBCODEID, ITM_CODE, PO_VOLM, PO_COST FROM tbl_po_detail WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
			$resPO	= $this->db->query($sqlPO)->result();
			foreach($resPO as $rowPO) :
				$PR_NUM		= $rowPO->PR_NUM;
				$POCODE		= $rowPO->PO_CODE;
				$JOBCODEID	= $rowPO->JOBCODEID;
				$ITM_CODE	= $rowPO->ITM_CODE;
				$PO_VOLM	= $rowPO->PO_VOLM;
				$PO_COST	= $rowPO->PO_COST;
				
				// Kembalikan di tabel PR
				$sqlPR	= "UPDATE tbl_pr_detail SET PO_VOLM = PO_VOLM - $PO_VOLM, PO_AMOUNT = PO_AMOUNT - $PO_COST
							WHERE PR_NUM = '$PR_NUM' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlPR);
				
				// Kembalikan di tabel JOBLIST
				$sqlJLD	= "UPDATE tbl_joblist_detail SET PO_VOLM = PO_VOLM - $PO_VOLM, PO_AMOUNT = PO_AMOUNT - $PO_COST
							WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlJLD);
				
				// Kembalikan di tabel MASTER ITEM
				$sqlITM	= "UPDATE tbl_item SET PO_VOLM = PO_VOLM - $PO_VOLM, PO_AMOUNT = PO_AMOUNT - $PO_COST
							WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlITM);
			endforeach;
			
			// UPDATE REQUEST STATUS
				$sqlJLD	= "UPDATE tbl_pr_header SET PR_STAT = 3, PR_ISCLOSE = 0, STATDESC = 'Approved', STATCOL = 'success'
							WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlJLD);

		// START : UPDATE TO DOC. COUNT
			$parameters 	= array('DOC_CODE' 		=> $PO_NUM,
									'PRJCODE' 		=> $PRJCODE,
									'DOC_TYPE'		=> "PO",
									'DOC_QTY' 		=> "DOC_POQ",
									'DOC_VAL' 		=> "DOC_POV",
									'DOC_STAT' 		=> 'VOID');
			$this->m_updash->updateDocC($parameters);
		// END : UPDATE TO DOC. COUNT

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $POCODE telah dibatalkan.";
		}
		else
		{
			$alert1	= "Document no. $POCODE has been void.";
		}
		echo "$alert1";
	}

	function trashMTSF()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 		= $_POST['collID'];
		$colExpl		= explode("~", $collID);
		$url 			= $colExpl[0];
        $tblNameH 		= $colExpl[1];
        $tblNameD 		= $colExpl[2];
        $DocNm			= $colExpl[3];
        $DocNum			= $colExpl[4];
        $PrjNm			= $colExpl[5];
        $PrjCode		= $colExpl[6];

        $DOC_NUM		= $DocNum;
        $proj_Code		= $PrjCode;
        $PRJCODE 		= $PrjCode;

        $DOC_STAT 		= 9;
        $DefEmp_ID 		= $this->session->userdata['Emp_ID'];
        $comp_init 		= $this->session->userdata('comp_init');
        $DOC_UPDATED 	= date('Y-m-d H:i:s');

        $this->load->model('m_updash/m_updash', '', TRUE);

        // 0. CEK REM QTY
			$retFalse 	= 0;
			$DOCCODE 	= "";
			$sqlSTOCK	= "SELECT PRJCODE, ITMTSF_DEST, ITM_CODE FROM tbl_item_tsfd WHERE ITMTSF_NUM = '$DOC_NUM' AND PRJCODE = '$PRJCODE'";
			$resSTOCK	= $this->db->query($sqlSTOCK)->result();
			foreach($resSTOCK as $rowSTOCK) :
				$PRJCODE	= $rowSTOCK->PRJCODE;
				$ITMTSFDEST	= $rowSTOCK->ITMTSF_DEST;
				$ITM_CODE	= $rowSTOCK->ITM_CODE;

				$sqlGetWH	= "SELECT WH_NUM FROM tbl_warehouse WHERE WH_CODE = '$ITMTSFDEST'";
				$resetWH	= $this->db->query($sqlGetWH)->result();
				foreach($resetWH as $rowWH) :
					$WH_NUM 	= $rowWH->WH_NUM;
				endforeach;

				$REM_QTY	= 0;
				/*$sqlSTOCK 	= "SELECT SUM(ITM_IN - ITM_OUT) AS REM_QTY FROM tbl_item_whqty
								WHERE PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_NUM' AND ITM_CODE = '$ITM_CODE'";*/
				$sqlSTOCK 	= "SELECT SUM(ITM_IN - ITM_OUT) AS REM_QTY FROM tbl_item_whqty
								WHERE WH_CODE = '$WH_NUM' AND ITM_CODE = '$ITM_CODE'";
				$resSTOCK 	= $this->db->query($sqlSTOCK)->result();
				foreach($resSTOCK as $rowSTOCK) :
					$REM_QTY	= $rowSTOCK->REM_QTY;
				endforeach;
				if($REM_QTY == 0)
					$retFalse	= $retFalse+1;
			endforeach;

			if($retFalse == 0)
			{
				// 1. UPDATE STATUS
					$sqlUPH		= "UPDATE tbl_item_tsfh SET ITMTSF_STAT = '9', STATDESC = 'Void', STATCOL = 'danger' WHERE ITMTSF_NUM = '$DOC_NUM'";
					$this->db->query($sqlUPH);

				// 2. UPDATE ITEM
					$DOCCODE 	= "";
					$sqlTSF		= "SELECT ITMTSF_NUM, ITMTSF_CODE, PRJCODE, PRJCODE_DEST, ITMTSF_ORIGIN, ITMTSF_DEST, ITMTSF_REFNO,
										ITM_CODE, ITM_GROUP, ITMTSF_VOLM, ITMTSF_PRICE
									FROM tbl_item_tsfd WHERE ITMTSF_NUM = '$DOC_NUM' AND PRJCODE = '$PRJCODE'";
					$resTSF		= $this->db->query($sqlTSF)->result();
					foreach($resTSF as $rowTSF) :
						$ITMTSF_NUM		= $rowTSF->ITMTSF_NUM;
						$DOCCODE		= $rowTSF->ITMTSF_CODE;
						$PRJCODE		= $rowTSF->PRJCODE;
						$PRJCODE_DEST	= $rowTSF->PRJCODE_DEST;
						$ITMTSF_ORIGIN	= $rowTSF->ITMTSF_ORIGIN;
						$ITMTSF_DEST	= $rowTSF->ITMTSF_DEST;
						$ITMTSF_REFNO	= $rowTSF->ITMTSF_REFNO;
						$ITM_CODE		= $rowTSF->ITM_CODE;
						$ITM_GROUP		= $rowTSF->ITM_GROUP;
						$ITMTSF_VOLM	= $rowTSF->ITMTSF_VOLM;
						$ITMTSF_PRICE	= $rowTSF->ITMTSF_PRICE;
						$ITM_TOTALP		= $ITMTSF_VOLM * $ITMTSF_PRICE;

						// START : UPDATE WH_QTY IN ORIGIN
							// HOLD DULU KARENA INI AKAN TERJADI JIKA BEDA COMPANY
							/*if(($PRJCODE_DEST != $PRJCODE) && $PRJCODE_DEST != '') // 1. JIKA BEDA WH, MAKA TAMBAHKAN DI WH ASAL ('$PRJCODE')
							{
								$sqlUpdORIG	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM + $ITMTSF_VOLM, ITM_OUT = ITM_OUT - $ITMTSF_VOLM,
													ITM_OUTP = ITM_OUTP - $ITM_TOTALP
												WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sqlUpdORIG);
							}
							else // 2. TAMBAHKAN DI WAREHOUSE ASAL ('$PRJCODE')
							{
								$sqlUpWHORI	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM + $ITMTSF_VOLM,
													ITM_OUT = ITM_OUT - $ITMTSF_VOLM, ITM_OUTP = ITM_OUTP - $ITM_TOTALP
												WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$ITMTSF_ORIGIN'";
								$this->db->query($sqlUpWHORI);*/

								$sqlGetWH	= "SELECT WH_NUM FROM tbl_warehouse WHERE WH_CODE = '$ITMTSF_ORIGIN'";
								$resetWH	= $this->db->query($sqlGetWH)->result();
								foreach($resetWH as $rowWH) :
									$WH_NUM 	= $rowWH->WH_NUM;
								endforeach;

								$sqlUpWHORI	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM + $ITMTSF_VOLM,
													ITM_OUT = ITM_OUT - $ITMTSF_VOLM, ITM_OUTP = ITM_OUTP - $ITM_TOTALP
												WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_NUM'";
								$this->db->query($sqlUpWHORI);
							//}
						// END : UPDATE WH_QTY IN ORIGIN
							
						// START : UPDATE WH_QTY IN DESTINATION
							// HOLD DULU KARENA INI AKAN TERJADI JIKA BEDA COMPANY
							/*if(($PRJCODE_DEST != $PRJCODE) && $PRJCODE_DEST != '') // 1. JIKA BEDA WH, MAKA KURANGI DI WH TUJUAN ('$PRJCODE_DEST')
							{
								$sqlUpdDEST	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM - $ITMTSF_VOLM, ITM_IN = ITM_IN - $ITMTSF_VOLM,
													ITM_INP = ITM_INP - $ITM_TOTALP
												WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE_DEST'";
								$this->db->query($sqlUpdDEST);
							}
							else // 2. KURANGI DI WAREHOUSE TUJUAN ('$PRJCODE_DEST')
							{
								$sqlUpWH	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM - $ITMTSF_VOLM,
													ITM_IN = ITM_IN - $ITMTSF_VOLM, ITM_INP = ITM_INP - $ITM_TOTALP
												WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$ITMTSF_DEST'";
								$this->db->query($sqlUpWH);*/

								$WHDEST 	= "";
								$sqlGetWH	= "SELECT WH_NUM FROM tbl_warehouse WHERE WH_CODE = '$ITMTSF_DEST'";
								$resetWH	= $this->db->query($sqlGetWH)->result();
								foreach($resetWH as $rowWH) :
									$WHDEST = $rowWH->WH_NUM;
								endforeach;

								$sqlUpWH	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM - $ITMTSF_VOLM,
													ITM_IN = ITM_IN - $ITMTSF_VOLM, ITM_INP = ITM_INP - $ITM_TOTALP
												WHERE ITM_CODE = '$ITM_CODE' AND WH_CODE = '$WHDEST'";
								$this->db->query($sqlUpWH);
							//}
						// END : UPDATE WH_QTY IN DESTINATION

						// UPDATE PENERIMAAN TIAP MR
							$sqlUPDTMR	= "UPDATE tbl_mr_detail SET IRM_VOLM = IRM_VOLM - $ITMTSF_VOLM, IRM_AMOUNT = IRM_AMOUNT - $ITMTSF_PRICE
											WHERE MR_NUM = '$ITMTSF_REFNO' AND ITM_CODE = '$ITM_CODE'";
							$this->db->query($sqlUPDTMR);
					endforeach;

					// UPDATE MR HEADER
						$sqlUPDTMRH	= "UPDATE tbl_mr_header SET MR_STAT = 3, STATCOL = 'success' , STATDESC = 'Approved'
										WHERE MR_NUM = '$ITMTSF_REFNO'";
						$this->db->query($sqlUPDTMRH);

				// 3. NOTIFIKASI
					$LangID 	= $this->session->userdata['LangID'];
					if($LangID == 'IND')
					{
						$alert1	= "No. Dokumen : $DOCCODE telah dibatalkan.";
					}
					else
					{
						$alert1	= "Document no. $DOCCODE has been void.";
					}
			}
			else
			{
				$LangID 	= $this->session->userdata['LangID'];
				if($LangID == 'IND')
				{
					$alert1	= "No. Dokumen : $sqlSTOCK gagal dibatalkan karena ada material yang tidak memiliki stok.";
				}
				else
				{
					$alert1	= "Document no. $DOCCODE failed to void because there is a material out of stock.";
				}
			}

		echo "$alert1";
	}
	
	function loginProc()
	{
		date_default_timezone_set("Asia/Jakarta");

		$DNOW			= date('Y-m-d H:i:s');
		$custC			= $_POST['collUP'];
		$colExpl		= explode("~", $custC);
		$usern 			= $colExpl[0];
        $userp 			= $colExpl[1];
        $userp 			= md5($userp);

        $LangID         = "IND";

	    $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	    $resTransl      = $this->db->query($sqlTransl)->result();
	    foreach($resTransl as $rowTransl) :
	        $TranslCode = $rowTransl->MLANG_CODE;
	        $LangTransl = $rowTransl->LangTransl;
	        
	        if($TranslCode == 'userpWrong')$userpWrong = $LangTransl;
	        if($TranslCode == 'usernNF')$usernNF = $LangTransl;
	    endforeach;

	    $succLog	= 0;
		$sqlUSRN	= "tbl_employee WHERE log_username = '$usern'";
		$resUSRN	= $this->db->count_all($sqlUSRN);
		if($resUSRN > 0)
		{
			$sqlEMPC	= "tbl_employee WHERE log_username = '$usern' AND log_password = '$userp'";
			$resEMPC	= $this->db->count_all($sqlEMPC);
			if($resEMPC > 0)
			{
				$succLog	= 1;
			}
			else
			{
				$succLog	= 2;
			}
		}
		else
		{
			$succLog		= 0;
		}
		if($succLog == 1)
			$succInf	= "Success";
		elseif($succLog == 2)
			$succInf	= $userpWrong;
		else
			$succInf	= $usernNF;

		echo "$succLog~$succInf";
	}
	
	function chkNIK()
	{
		$this->load->model('login_model', '', TRUE);
		date_default_timezone_set("Asia/Jakarta");

		$DNOW		= date('Y-m-d H:i:s');
		$nikemp		= $_POST['nikemp'];

	    $nikReg		= 0;
		$sqlUSRN	= "tbl_employee WHERE Emp_ID = '$nikemp'";
		$resUSRN	= $this->db->count_all($sqlUSRN);
		if($resUSRN > 0)
		{
			$nikReg	= 1;

			$getImg	= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$nikemp'";
			$resImg = $this->db->query($getImg)->result();
			$imgFlX	= "username.jpg";
			foreach($resImg as $rowImg) :
				$imgFlX = $rowImg ->imgemp_filenameX;
			endforeach;
			$imgLoc		= base_url('assets/AdminLTE-2.0.5/emp_image/'.$nikemp.'/'.$imgFlX);
			if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$nikemp))
			{
				$imgLoc	= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
			}
		}
		else
		{
			$NEW_PASSCRYP	= "";
			$NEW_PASS		= "";
			$resInfo		= "failed_nik";

			$host_name 	= gethostbyaddr($_SERVER['REMOTE_ADDR']);
	        $ipaddress	= '';
	        if (getenv('HTTP_CLIENT_IP'))
		        $ipaddress = getenv('HTTP_CLIENT_IP');
		    else if(getenv('HTTP_X_FORWARDED_FOR'))
		        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		    else if(getenv('HTTP_X_FORWARDED'))
		        $ipaddress = getenv('HTTP_X_FORWARDED');
		    else if(getenv('HTTP_FORWARDED_FOR'))
		        $ipaddress = getenv('HTTP_FORWARDED_FOR');
		    else if(getenv('HTTP_FORWARDED'))
		        $ipaddress = getenv('HTTP_FORWARDED');
		    else if(getenv('REMOTE_ADDR'))
		        $ipaddress = getenv('REMOTE_ADDR');
		    else
		        $ipaddress = 'IP Tidak Dikenali';

		    $browser = '';
		    if(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape'))
		        $browser = 'Netscape';
		    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox'))
		        $browser = 'Firefox';
		    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome'))
		        $browser = 'Chrome';
		    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera'))
		        $browser = 'Opera';
		    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
		        $browser = 'Internet Explorer';
		    else
		        $browser = 'Other';

		   	$RES_DESC	= "$host_name, $ipaddress, $browser";
		   	$imgLoc		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');

			$insHist 	= array('RES_EMPID' => $nikemp,
								'RES_EMAIL' => $hintemp,
								'RES_DATE' 	=> $DNOW,
								'RES_A' 	=> $NEW_PASSCRYP,
								'RES_B' 	=> $NEW_PASS,
								'RES_DESC'	=> $RES_DESC,
								'RES_STAT' 	=> $resInfo,);
			$this->login_model->addResHist($insHist);
		}

		echo "$nikReg~$imgLoc";
	}
	
	function chkHint()
	{
		$this->load->model('login_model', '', TRUE);
		date_default_timezone_set("Asia/Jakarta");

		$DNOW			= date('Y-m-d H:i:s');
		$collData		= $_POST['collData'];
		$colExpl		= explode("~", $collData);
		$nikemp 		= $colExpl[0];
        $hintemp 		= $colExpl[1];

        $LangID         = "IND";

	    $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	    $resTransl      = $this->db->query($sqlTransl)->result();
	    foreach($resTransl as $rowTransl) :
	        $TranslCode = $rowTransl->MLANG_CODE;
	        $LangTransl = $rowTransl->LangTransl;
	        
	        if($TranslCode == 'emailNotReg')$emailNotReg = $LangTransl;
	        if($TranslCode == 'nikhintNSync')$nikhintNSync = $LangTransl;
	    endforeach;

	    $nikmSync	= 0;
	    $passInfo 	= $nikhintNSync;
		$sqlUSRN	= "tbl_employee WHERE Emp_ID = '$nikemp' AND log_passHint = '$hintemp'";
		$resUSRN	= $this->db->count_all($sqlUSRN);
		if($resUSRN > 0)
		{
			$nikmSync	= 1;
			$email 		= '';
			$sqlEmp 	= "SELECT Emp_ID, Email FROM tbl_employee WHERE Emp_ID = '$nikemp' AND log_passHint = '$hintemp'";
		    $resEmp     = $this->db->query($sqlEmp)->result();
		    foreach($resEmp as $rowEmp) :
		        $Emp_ID = $rowEmp->Emp_ID;
		        $email 	= $rowEmp->Email;
		    endforeach;
		    
			$NEW_PASS1 		= date('Y-m-d-H:i:s');
			$NEW_PASS2		= md5($NEW_PASS1);
			$NEW_PASS		= substr($NEW_PASS2, 5, 10);
			$NEW_PASSCRYP	= md5($NEW_PASS);
			$passInfo 		= $NEW_PASS;
			$resInfo		= "Berhasil";

			$this->login_model->resetAuth($nikemp, $email, $NEW_PASSCRYP, $NEW_PASS);
		}
		else
		{
			$NEW_PASSCRYP	= "";
			$NEW_PASS		= "";
			$resInfo		= "failed_hint";
		}

		$host_name 	= gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $ipaddress	= '';
        if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	        $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'IP Tidak Dikenali';

	    $browser = '';
	    if(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape'))
	        $browser = 'Netscape';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox'))
	        $browser = 'Firefox';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome'))
	        $browser = 'Chrome';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera'))
	        $browser = 'Opera';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
	        $browser = 'Internet Explorer';
	    else
	        $browser = 'Other';

	   	$RES_DESC	= "$host_name, $ipaddress, $browser";

		$insHist 	= array('RES_EMPID' => $nikemp,
							'RES_EMAIL' => $hintemp,
							'RES_DATE' 	=> $DNOW,
							'RES_A' 	=> $NEW_PASSCRYP,
							'RES_B' 	=> $NEW_PASS,
							'RES_DESC'	=> $RES_DESC,
							'RES_STAT' 	=> $resInfo,);
		$this->login_model->addResHist($insHist);

		echo "$nikmSync~$passInfo";
	}

	function trash_TAX()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblName 	= $colExpl[1];
        $DocNm		= $colExpl[2];
        $DocNum		= $colExpl[3];

        $taxCode	= '';
        $taxDesc	= '';
		$sqlTax 	= "SELECT TAXLA_CODE, TAXLA_DESC FROM $tblName WHERE $DocNm = '$DocNum'";
	    $resTax     = $this->db->query($sqlTax)->result();
	    foreach($resTax as $rowTax) :
	        $taxCode = $rowTax->TAXLA_CODE;
	        $taxDesc = $rowTax->TAXLA_DESC;
	    endforeach;

        $sqlDel		= "DELETE FROM $tblName WHERE $DocNm = '$DocNum'";
        $this->db->query($sqlDel);

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Kode Pajak $taxCode : $taxDesc telah dihapus.";
		}
		else
		{
			$alert1	= "Tax Code $taxCode : $taxDesc has been deleted.";
		}
		echo "$alert1";
	}

	function getTaxP()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$tblName	= $colExpl[0];
        $DocNum 	= $colExpl[1];	//TAX CODE

        $taxPerc	= 0;
		/*$sqlTax 	= "SELECT TAXLA_PERC FROM $tblName WHERE TAXLA_NUM = '$DocNum'";
	    $resTax     = $this->db->query($sqlTax)->result();
	    foreach($resTax as $rowTax) :
	        $taxPerc = $rowTax->TAXLA_PERC;
	    endforeach;*/

	    $isTax 		= 0;
		$sTaxPPn 	= "tbl_tax_ppn WHERE TAXLA_NUM = '$DocNum'";	// Tax PPn
		$rTaxPPn	= $this->db->count_all($sTaxPPn);
		if($rTaxPPn > 0)
		{
			$taxTyp = 1;	// 1 = PPn, 2 = PPh
			$sqlTax = "SELECT TAXLA_PERC FROM tbl_tax_ppn WHERE TAXLA_NUM = '$DocNum'";
		}

		$sTaxPPh 	= "tbl_tax_la WHERE TAXLA_NUM = '$DocNum'";		// Tax PPh
		$rTaxPPh	= $this->db->count_all($sTaxPPh);
		if($rTaxPPh > 0)
		{
			$taxTyp = 2;	// 1 = PPn, 2 = PPh
			$sqlTax = "SELECT TAXLA_PERC FROM tbl_tax_la WHERE TAXLA_NUM = '$DocNum'";
		}

	    $resTax     = $this->db->query($sqlTax)->result();
	    foreach($resTax as $rowTax) :
	        $taxPerc = $rowTax->TAXLA_PERC;
	    endforeach;

		echo "$taxTyp~$taxPerc";
	}

	function trashHO()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
        $PRJCODE 	= $colExpl[1];

		$delPRJ1 	= "DELETE FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$this->db->query($delPRJ1);

		$delPRJ2 	= "DELETE FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
		$this->db->query($delPRJ2);

		$delPRJ3 	= "DELETE FROM tbl_project_budgm WHERE PRJCODE = '$PRJCODE'";
		$this->db->query($delPRJ3);

		$delPRJ4 	= "DELETE FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
		$this->db->query($delPRJ4);

		$delPRJ5 	= "DELETE FROM tbl_profitloss WHERE PRJCODE = '$PRJCODE'";
		$this->db->query($delPRJ5);

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Kode Anggaran $PRJCODE telah dihapus.";
		}
		else
		{
			$alert1	= "Budget Code $PRJCODE has been deleted.";
		}
		echo "$alert1";
	}

	function trashCOA()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $DocNm		= $colExpl[2];
        $DocNum		= $colExpl[3];
        $PrjNm		= $colExpl[4];
        $PrjCode	= $colExpl[5];

		$sqlDel		= "DELETE FROM $tblNameH WHERE $DocNm = '$DocNum'";
        $this->db->query($sqlDel);

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Kode Akun $DocNum~$PrjCode telah dihapus.";
		}
		else
		{
			$alert1	= "Account Code $DocNum~$PrjCode has been deleted.";
		}
		echo "$alert1";
	}

	function resORID()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$PRJCODE 	= $_POST['collID'];

		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');
		$DefEmp_ID	= $this->session->userdata['Emp_ID'];

		$selPRJSYNC = $PRJCODE;
		$SYNC_PRJ	= $selPRJSYNC;

		$PRJCODEHO	= '';
		$sqlPRJHO	= "SELECT PRJCODE AS PRJCODEHO FROM tbl_project WHERE isHO = 1";
		$resPRJHO 	= $this->db->query($sqlPRJHO)->result();
		foreach($resPRJHO as $rowPRJHO) :
			$PRJCODEHO 	= $rowPRJHO->PRJCODEHO;
		endforeach;

		$sqlUpCO1 	= "UPDATE tbl_chartaccount SET isHO = 1 WHERE PRJCODE = '$PRJCODEHO'";
		$this->db->query($sqlUpCO1);

		$sqlUpCO2 	= "UPDATE tbl_chartaccount SET isHO = 0 WHERE PRJCODE != '$PRJCODEHO'";
		$this->db->query($sqlUpCO2);

		$COLprj 	= "";
		$na 		= 0;
		$sqlPRJ1	= "SELECT PRJCODE FROM tbl_project WHERE isHO = 0";
		$resPRJ1	= $this->db->query($sqlPRJ1)->result();
		foreach($resPRJ1 as $rowPRJ1):
			$PRJCD 	= $rowPRJ1->PRJCODE;
			$na 	= $na+1;
			if($na ==1)
				$COLprj = $PRJCD;
			else
				$COLprj = $COLprj."~".$PRJCD;
		endforeach;

		$sqlUpCO3 	= "UPDATE tbl_chartaccount SET syncPRJ = CONCAT(PRJCODE,'~$COLprj') WHERE isHO = 1";
		$this->db->query($sqlUpCO3);

		$sqlUpCO4 	= "UPDATE tbl_chartaccount SET syncPRJ = CONCAT(PRJCODE,'~$PRJCODEHO') WHERE isHO = 0";
		$this->db->query($sqlUpCO4);

		$sqlICCOA	= "INSERT INTO tbl_ccoa (CCOA_PRJCODE, CCOA_ISCHECK, CCOA_ISCHECKED, CCOA_ISCHECKER)
						VALUES ('$SYNC_PRJ', 1, '$dateNow1', '$DefEmp_ID')";
		$this->db->query($sqlICCOA);
		
		// START : PROCEDUR - RESET ORDER
			// 1.	CEK POSISI LEVEL 0, DARI KODE INDUK YANG TIDAK ADA DI DALAM DAFTAR COA
					$sql_01	= "SELECT A.Account_Number AS AccNumb FROM tbl_chartaccount A
								WHERE A.Acc_DirParent NOT IN (SELECT B.Account_Number FROM tbl_chartaccount B WHERE B.PRJCODE = '$SYNC_PRJ')
									AND A.PRJCODE = '$SYNC_PRJ' AND A.Account_Level > 1";
					$res_01 = $this->db->query($sql_01)->result();
					foreach($res_01 as $row_01) :
						$AccNumb	= $row_01->AccNumb;

						$sql_01A	= "UPDATE tbl_chartaccount SET ORD_ID = 9999999, Account_Level = 0
										WHERE Account_Number = '$AccNumb' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($sql_01A);
						// 9999999 = damage account
					endforeach;

					$sql_01B	= "UPDATE tbl_chartaccount SET ORD_ID = 9999999, Account_Level = 0
									WHERE Account_Number = Acc_DirParent AND PRJCODE = '$SYNC_PRJ'";
					$this->db->query($sql_01B);
					// 9999999 = damage account

			// 2. 	CEK APAKAH MEMILIKI HEADER, JIKA TIDAK, PASTI LEVEL 0
					$sql_02		= "UPDATE tbl_chartaccount SET Account_Level = 0 WHERE PRJCODE = '$SYNC_PRJ' AND Acc_DirParent = ''";
					$this->db->query($sql_02);

			// 3. 	RESET SUSUNAN COA
					$sql_03 	= "UPDATE tbl_chartaccount SET ORD_ID = 0 WHERE PRJCODE = '$SYNC_PRJ' AND ORD_ID != '9999999'";
					$this->db->query($sql_03);

			// 4.	TAMBAHAN
					$sql_01X= "SELECT A.Account_Number AS AccNumb FROM tbl_chartaccount A
								WHERE A.Acc_DirParent NOT IN (SELECT B.Account_Number FROM tbl_chartaccount B WHERE B.PRJCODE = '$SYNC_PRJ')
									AND A.PRJCODE = '$SYNC_PRJ' AND A.Acc_DirParent != '' AND ORD_ID != '9999999'";
					$res_01X= $this->db->query($sql_01X)->result();
					foreach($res_01X as $row_01X) :
						$AccNumb	= $row_01X->AccNumb;

						$sql_01AX	= "UPDATE tbl_chartaccount SET Account_Level = 0 WHERE Account_Number = '$AccNumb' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($sql_01AX);
					endforeach;


					$sql_01Y= "SELECT Account_Number AS AccNumb FROM tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND isLast = 0
								AND Account_Number NOT IN (SELECT B.Acc_DirParent FROM tbl_chartaccount B WHERE B.Acc_DirParent AND B.PRJCODE = '$SYNC_PRJ')";
					$res_01Y= $this->db->query($sql_01Y)->result();
					foreach($res_01Y as $row_01Y) :
						$AccNumb	= $row_01Y->AccNumb;

						$sql_01AY	= "UPDATE tbl_chartaccount SET ORD_ID = 9999999 WHERE Account_Number = '$AccNumb' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($sql_01AY);
					endforeach;
		// END : PROCEDUR - RESET ORDER

		// START : PROSES PROCEDUR - RESET ORDER
			$ORD_ID		= 0;
			$sql_04		= "SELECT Account_Number, isLast FROM tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND Account_Level = 0 AND ORD_ID != '9999999'
							ORDER BY ORD_ID, Account_Number, Acc_ID";
			$res_04 	= $this->db->query($sql_04)->result();
			foreach($res_04 as $row_04) :
				$ORD_ID			= $ORD_ID+1;
				$Account_N03	= $row_04->Account_Number;
				$isLast03		= $row_04->isLast;

				$sql_04RO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID WHERE Account_Number = '$Account_N03' AND PRJCODE = '$SYNC_PRJ'";
				$this->db->query($sql_04RO);
				if($isLast03 == 0)
				{
					$sql_04RO1 	= "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0, Base_Debet2 = 0, Base_Kredit2 = 0
									WHERE Account_Number = '$Account_N03' AND PRJCODE = '$SYNC_PRJ'";
					$this->db->query($sql_04RO1);

					$sql_01	= "SELECT Account_Number, isLast FROM tbl_chartaccount
									WHERE Acc_DirParent = '$Account_N03' AND PRJCODE = '$SYNC_PRJ' ORDER BY Account_Number";											
					$res_01	= $this->db->query($sql_01)->result();
					foreach($res_01 as $row_01):
						$ORD_ID			= $ORD_ID+1;
						$Account_N3A	= $row_01->Account_Number;
						$isLast3A		= $row_01->isLast;
						
						$sql_01RO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 1
											WHERE Account_Number = '$Account_N3A' AND PRJCODE = '$SYNC_PRJ'";
						$this->db->query($sql_01RO);
						if($isLast3A == 0)
						{
							$sql_01RO1 = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0, Base_Debet2 = 0, Base_Kredit2 = 0
											WHERE Account_Number = '$Account_N3A' AND PRJCODE = '$SYNC_PRJ'";
							$this->db->query($sql_01RO1);

							$sql_04B	= "SELECT Account_Number, isLast FROM tbl_chartaccount
											WHERE Acc_DirParent = '$Account_N3A' AND PRJCODE = '$SYNC_PRJ'
											ORDER BY Account_Number";											
							$res_04B	= $this->db->query($sql_04B)->result();
							foreach($res_04B as $row_04B):
								$ORD_ID			= $ORD_ID+1;
								$Account_N3B	= $row_04B->Account_Number;
								$isLast3B		= $row_04B->isLast;
								
								$sql_04BRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 2
													WHERE Account_Number = '$Account_N3B' AND PRJCODE = '$SYNC_PRJ'";
								$this->db->query($sql_04BRO);
								if($isLast3B == 0)
								{
									$sql_04BRO1 = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0, Base_Debet2 = 0, Base_Kredit2 = 0
													WHERE Account_Number = '$Account_N3B' AND PRJCODE = '$SYNC_PRJ'";
									$this->db->query($sql_04BRO1);

									$sql_04C	= "SELECT Account_Number, isLast FROM tbl_chartaccount
													WHERE Acc_DirParent = '$Account_N3B' AND PRJCODE = '$SYNC_PRJ' ORDER BY Account_Number";											
									$res_04C	= $this->db->query($sql_04C)->result();
									foreach($res_04C as $row_04C):
										$ORD_ID			= $ORD_ID+1;
										$Account_N3C	= $row_04C->Account_Number;
										$isLast3C		= $row_04C->isLast;
										
										$sql_04CRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 3
															WHERE Account_Number = '$Account_N3C' AND PRJCODE = '$SYNC_PRJ'";
										$this->db->query($sql_04CRO);
										if($isLast3C == 0)
										{
											$sql_04CRO1 = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0,
																Base_Debet2 = 0, Base_Kredit2 = 0
															WHERE Account_Number = '$Account_N3C' AND PRJCODE = '$SYNC_PRJ'";
											$this->db->query($sql_04CRO1);

											$sql_04D	= "SELECT Account_Number, isLast FROM tbl_chartaccount
															WHERE Acc_DirParent = '$Account_N3C' AND PRJCODE = '$SYNC_PRJ'
															ORDER BY Account_Number";											
											$res_04D	= $this->db->query($sql_04D)->result();
											foreach($res_04D as $row_04D):
												$ORD_ID			= $ORD_ID+1;
												$Account_N3D	= $row_04D->Account_Number;
												$isLast3D		= $row_04D->isLast;
												
												$sql_04DRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 4
																	WHERE Account_Number = '$Account_N3D' AND PRJCODE = '$SYNC_PRJ'";
												$this->db->query($sql_04DRO);
												if($isLast3D == 0)
												{
													$sql_04DRO1 = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0,
																		Base_Debet2 = 0, Base_Kredit2 = 0
																	WHERE Account_Number = '$Account_N3D' AND PRJCODE = '$SYNC_PRJ'";
													$this->db->query($sql_04DRO1);

													$sql_04E	= "SELECT Account_Number, isLast FROM tbl_chartaccount
																	WHERE Acc_DirParent = '$Account_N3D' AND PRJCODE = '$SYNC_PRJ'
																	ORDER BY Account_Number";											
													$res_04E	= $this->db->query($sql_04E)->result();
													foreach($res_04E as $row_04E):
														$ORD_ID			= $ORD_ID+1;
														$Account_N3E	= $row_04E->Account_Number;
														$isLast3E		= $row_04E->isLast;
														
														$sql_04ERO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 5
																			WHERE Account_Number = '$Account_N3E' AND PRJCODE = '$SYNC_PRJ'";
														$this->db->query($sql_04ERO);
														if($isLast3E == 0)
														{
															$sql_04ERO1 = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0,
																				Base_Debet2 = 0, Base_Kredit2 = 0
																			WHERE Account_Number = '$Account_N3E' AND PRJCODE = '$SYNC_PRJ'";
															$this->db->query($sql_04ERO1);

															$sql_04F	= "SELECT Account_Number, isLast FROM tbl_chartaccount
																			WHERE Acc_DirParent = '$Account_N3E' AND PRJCODE = '$SYNC_PRJ'
																			ORDER BY Account_Number";											
															$res_04F	= $this->db->query($sql_04F)->result();
															foreach($res_04F as $row_04F):
																$ORD_ID			= $ORD_ID+1;
																$Account_N3F	= $row_04F->Account_Number;
																$isLast3F		= $row_04F->isLast;
																
																$sql_04FRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 6
																					WHERE Account_Number = '$Account_N3F' AND PRJCODE = '$SYNC_PRJ'";
																$this->db->query($sql_04FRO);
																if($isLast3F == 0)
																{
																	$sql_04FRO1 = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0,
																						Base_Debet = 0, Base_Kredit = 0,
																						Base_Debet2 = 0, Base_Kredit2 = 0
																					WHERE Account_Number = '$Account_N3F' AND PRJCODE = '$SYNC_PRJ'";
																	$this->db->query($sql_04FRO1);
																	
																	$sql_04G	= "SELECT Account_Number, isLast FROM tbl_chartaccount
																					WHERE Acc_DirParent = '$Account_N3F' AND PRJCODE = '$SYNC_PRJ'
																					ORDER BY Account_Number";											
																	$res_04G	= $this->db->query($sql_04G)->result();
																	foreach($res_04G as $row_04G):
																		$ORD_ID			= $ORD_ID+1;
																		$Account_N3G	= $row_04G->Account_Number;
																		$isLast3G		= $row_04G->isLast;
																		
																		$sql_04GRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, Account_Level = 7
																							WHERE Account_Number = '$Account_N3G' AND PRJCODE = '$SYNC_PRJ'";
																		$this->db->query($sql_04GRO);
																		if($isLast3G == 0)
																		{
																			$sql_04GRO1 = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0,
																								Base_Debet = 0, Base_Kredit = 0,
																								Base_Debet2 = 0, Base_Kredit2 = 0
																							WHERE Account_Number = '$Account_N3G' AND PRJCODE = '$SYNC_PRJ'";
																			$this->db->query($sql_04GRO1);
																			
																			$sql_04H	= "SELECT Account_Number, isLast FROM tbl_chartaccount
																							WHERE Acc_DirParent = '$Account_N3G' AND PRJCODE = '$SYNC_PRJ'
																							ORDER BY Account_Number";											
																			$res_04H	= $this->db->query($sql_04H)->result();
																			foreach($res_04H as $row_04H):
																				$ORD_ID			= $ORD_ID+1;
																				$Account_N3H	= $row_04H->Account_Number;
																				$isLast3H		= $row_04H->isLast;
																				
																				$sql_04HRO 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID, 
																										Account_Level = 8
																									WHERE Account_Number = '$Account_N3H' AND PRJCODE = '$SYNC_PRJ'";
																				$this->db->query($sql_04HRO);
																			endforeach;
																		}
																	endforeach;
																}
															endforeach;
														}
													endforeach;
												}
											endforeach;
										}
									endforeach;
								}
							endforeach;
						}
					endforeach;
				}
			endforeach;

			$sql_05	= "SELECT Account_Number, isLast FROM tbl_chartaccount WHERE ORD_ID = '9999999' AND PRJCODE = '$SYNC_PRJ' ORDER BY Account_Number";
			$res_05	= $this->db->query($sql_05)->result();
			foreach($res_05 as $row_05):
				$Account_N5	= $row_05->Account_Number;
				$isLast5	= $row_05->isLast;
				if($isLast5 == 0)
				{
					$sql_05A = "UPDATE tbl_chartaccount SET Base_OpeningBalance = 0, Base_Debet = 0, Base_Kredit = 0,
										Base_Debet2 = 0, Base_Kredit2 = 0, COGSReportID = ''
									WHERE Account_Number = '$Account_N5' AND PRJCODE = '$SYNC_PRJ'";
					$this->db->query($sql_05A);
				}
			endforeach;
		// END : PROSES PROCEDUR - RESET ORDER

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Pengaturan ulang Daftar Akun untuk Anggaran $PRJCODE telah selesai.";
		}
		else
		{
			$alert1	= "Reset Account List for Budget $PRJCODE has been completed";
		}
		echo "$alert1";
	}

	function resJD()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$PRJCODE	= $colExpl[0];
        $AccCateg 	= $colExpl[1];

        if($AccCateg == 1)
        	$AccCategD	= "AKTIVA";
        elseif($AccCateg == 2)
        	$AccCategD	= "PASSIVA";
        elseif($AccCateg == 3)
        	$AccCategD	= "MODAL";
        elseif($AccCateg == 4)
        	$AccCategD	= "INCOME STAEMENT";
        elseif($AccCateg == 5)
        	$AccCategD	= "COGS";
        elseif($AccCateg == 6)
        	$AccCategD	= "HPP";
        elseif($AccCateg == 8)
        	$AccCategD	= "BEBAN";
        elseif($AccCateg == 9)
        	$AccCategD	= "BEBAN LAINNYA";
        elseif($AccCateg == 10)
        	$AccCategD	= "BEBAN LAINNYA";
        else
        	$AccCategD	= "BEBAN LAINNYA";
		
		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');
		$accYr		= date('Y', strtotime($dateNow1));
		$DefEmp_ID	= $this->session->userdata['Emp_ID'];

		// UPDATE STATUS
            $sqlRESS	= "UPDATE tbl_journaldetail A, tbl_journalheader B
							SET A.GEJ_STAT = B.GEJ_STAT WHERE A.JournalH_Code = B.JournalH_Code";
            $this->db->query($sqlRESS);

		// UPDATE STATUS BY RETENSI
            $sqlRESRET	= "UPDATE tbl_journaldetail SET GEJ_STAT = 3 WHERE JournalH_Code LIKE '%-RET'";
            $this->db->query($sqlRESRET);
			
        // GET RESET SETING
            $JRNSET		= 0;
            $sqlRESSET	= "SELECT RESET_JOURN FROM tglobalsetting LIMIT 1";
            $resRESSET	= $this->db->query($sqlRESSET)->result();
            foreach($resRESSET as $rowRESSET) :
                $JRNSET	= $rowRESSET->RESET_JOURN;
            endforeach;
            
        // ZERO VALUE COA
            $PRJCODE	= '';
            $sqlPRJ		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1";
            $resPRJ		= $this->db->query($sqlPRJ)->result();
            foreach($resPRJ as $rowPRJ) :
                $PRJCODE	= $rowPRJ->PRJCODE;
            endforeach;
            
            if($JRNSET == 1)
            {
                /*$sqlUCOA	= "UPDATE tbl_chartaccount SET Base_Debet = 0, Base_Debet2 = 0, 
									Base_Kredit = 0, Base_Kredit2 = 0
                                WHERE PRJCODE = '$PRJCODE'";*/
                $sqlUCOA	= "UPDATE tbl_chartaccount SET Base_Debet = 0, Base_Debet2 = 0, 
									Base_Kredit = 0, Base_Kredit2 = 0, BaseD_$accYr = 0, BaseK_$accYr = 0
								WHERE Account_Category = '$AccCateg'";
				$this->db->query($sqlUCOA);
                //ONLY ISCHECKED 1 $this->db->query($sqlUCOA);
				
                $sqlUCOB	= "UPDATE tbl_journaldetail SET isChecked = 0 WHERE proj_Code = '$PRJCODE'";
                //$this->db->query($sqlUCOB);
                // NOTE: TIDAK BOLEH ADA RESET ISCHECKED KARENA DISIMPAN KE LASTSYNC
            }
			
        // START : TOTAL DEBET KREDIT
            $TOTD		= 0;
            $TOTK		= 0;
			$SumRowB	= 0;
			$totCount	= 0;
			
			$sqlJOURNT1	= "SELECT
								A.JournalH_Code,
								A.proj_Code,
								A.Acc_Id,
								B.Account_Class,
								A.Base_Debet,
								A.Base_Kredit,
								A.LastUpdate
							FROM
								tbl_journaldetail A
									INNER JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number
										AND B.Account_Category = '$AccCateg'
										-- AND B.PRJCODE = '$PRJCODE'
										AND B.PRJCODE = A.proj_Code
							WHERE
								A.GEJ_STAT = 3 AND isChecked = 0
							ORDER BY
								A.JournalH_Code,
								A.LastUpdate ASC";
			$resJOURNT1	= $this->db->query($sqlJOURNT1)->result();
			foreach($resJOURNT1 as $rowJ1) :
				$totCount 	= $totCount + 1;
				$journCode	= $rowJ1->JournalH_Code;
				$LastUpdate	= $rowJ1->LastUpdate;
				$AccId		= $rowJ1->Acc_Id;
				$AccClass1	= $rowJ1->Account_Class;
				$proj_Code1	= $rowJ1->proj_Code;
				$BaseDebet	= $rowJ1->Base_Debet;
				$TOTD		= $TOTD + $BaseDebet;
				$BaseKredit	= $rowJ1->Base_Kredit;
				$TOTK		= $TOTK + $BaseKredit;
				$SumRowB	= $SumRowB + $BaseDebet - $BaseKredit;

				$sqlLS 		= "tbl_lastsync WHERE LS_ACC_ID = '$AccId' AND PRJCODE = '$proj_Code1'";
				$resLS 		= $this->db->count_all($sqlLS);
				if($resLS == 0)
				{
					// INSERT TO LASTSYNC, TETAPI NILAINYA DINOLKAN DULU, PROSES UPDATE ADA DI CODE SELANJUTNYA
					$sqlILS 	= "INSERT INTO tbl_lastsync (PRJCODE, LS_ACC_ID, LS_DEBET, LS_KREDIT)
									VALUES ('$proj_Code1', '$AccId', 0, 0)";
					$this->db->query($sqlILS);
				}
				
				if($JRNSET == 1)
				{
					$syncPRJ	= '';
					$sqlSyns	= "SELECT syncPRJ FROM tbl_chartaccount 
									WHERE PRJCODE = '$proj_Code1' AND Account_Number = '$AccId'";
					$resSyns	= $this->db->query($sqlSyns)->result();
					foreach($resSyns as $rowSYNC) :
						$syncPRJ= $rowSYNC->syncPRJ;
					endforeach;
															
					if($AccClass1 == 33 || $AccClass1 == 44)
					{
						// KHUSUS AKUN KAS DAN BANK UPDATE KE SEMUA PROYEK BAIK AKTIF MAUPUN TIDAK
							$sqlUCOA2	= "UPDATE tbl_lastsync SET
												LS_DEBET = LS_DEBET + $BaseDebet, 
												LS_KREDIT = LS_KREDIT + $BaseKredit
											WHERE LS_ACC_ID = '$AccId'";
							$this->db->query($sqlUCOA2);

							// PEMANGGILAN ULANG NILAI SETELAH DIUPDATE UNTUK DI-TRIGGER KE COA
								$LS_DEBET	= 0;
								$LS_KREDIT	= 0;
								$sqlLSyns	= "SELECT LS_DEBET, LS_KREDIT FROM tbl_lastsync 
												WHERE PRJCODE = '$proj_Code1' AND LS_ACC_ID = '$AccId'";
								$resLSyns	= $this->db->query($sqlLSyns)->result();
								foreach($resLSyns as $rowLSYNC) :
									$LS_DEBET	= $rowLSYNC->LS_DEBET;
									$LS_KREDIT	= $rowLSYNC->LS_KREDIT;
								endforeach;

								$sqlUCOA1	= "UPDATE tbl_chartaccount SET
													Base_Debet = $LS_DEBET, 
													Base_Debet2 = $LS_DEBET, 
													Base_Kredit = $LS_KREDIT,
													Base_Kredit2 = $LS_KREDIT
												WHERE Account_Number = '$AccId'";
								$this->db->query($sqlUCOA1);
					}
					else
					{
						$getSPLIT 	= explode("~",$syncPRJ);
						foreach($getSPLIT as $prj)
						{
							$sqlLS 		= "tbl_lastsync WHERE LS_ACC_ID = '$AccId' AND PRJCODE = '$prj'";
							$resLS 		= $this->db->count_all($sqlLS);
							if($resLS == 0)
							{
								// INSERT TO LASTSYNC, TETAPI NILAINYA DINOLKAN DULU, PROSES UPDATE ADA DI CODE SELANJUTNYA
								$sqlILS 	= "INSERT INTO tbl_lastsync (PRJCODE, LS_ACC_ID, LS_DEBET, LS_KREDIT)
												VALUES ('$prj', '$AccId', $BaseDebet, $BaseKredit)";
								$this->db->query($sqlILS);
							}
							else
							{
								$sqlUCOA2	= "UPDATE tbl_lastsync SET
													LS_DEBET = LS_DEBET + $BaseDebet, 
													LS_KREDIT = LS_KREDIT + $BaseKredit
												WHERE PRJCODE = '$prj' AND LS_ACC_ID = '$AccId'";
								$this->db->query($sqlUCOA2);
							}
							
							// PEMANGGILAN ULANG NILAI SETELAH DIUPDATE UNTUK DI-TRIGGER KE COA
								$LS_DEBET	= 0;
								$LS_KREDIT	= 0;
								$sqlLSyns	= "SELECT LS_DEBET, LS_KREDIT FROM tbl_lastsync 
												WHERE PRJCODE = '$prj' AND LS_ACC_ID = '$AccId'";
								$resLSyns	= $this->db->query($sqlLSyns)->result();
								foreach($resLSyns as $rowLSYNC) :
									$LS_DEBET	= $rowLSYNC->LS_DEBET;
									$LS_KREDIT	= $rowLSYNC->LS_KREDIT;
								endforeach;

								$sqlUCOA1	= "UPDATE tbl_chartaccount SET
													Base_Debet = $LS_DEBET,
													Base_Debet2 = $LS_DEBET,
													Base_Kredit = $LS_KREDIT,
													Base_Kredit2 = $LS_KREDIT
												WHERE PRJCODE = '$prj' AND Account_Number = '$AccId'";
								$this->db->query($sqlUCOA1);
						}
					}
					
					// UPDATE STATUS CHECKED JOURNAL
						$sqlUCOB	= "UPDATE tbl_journaldetail SET isChecked = 1
										WHERE proj_Code = '$proj_Code1' AND Acc_Id = '$AccId'
											AND JournalH_Code = '$journCode'";
						$this->db->query($sqlUCOB);
				}
			endforeach;
        // END : TOTAL DEBET KREDIT
			
        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Pengaturan ulang Daftar Akun untuk Anggaran $AccCategD pada Anggaran $PRJCODE telah selesai. Tampilkan Jurnal?";
		}
		else
		{
			$alert1	= "Reset Account List for Budget $AccCategD on Budget $PRJCODE has been completed. Show journal?";
		}
		echo "$alert1";
	}

	function resJDY()
	{
		date_default_timezone_set("Asia/Jakarta");

		$DefEmp_ID	= $this->session->userdata['Emp_ID'];
			
        // START : TOTAL DEBET KREDIT
            $TOTD		= 0;
            $TOTK		= 0;
			$SumRowB	= 0;
			$totCount	= 0;
			
			$sqlJOURNT1	= "SELECT
								A.JournalD_Id,
								A.JournalH_Code,
								A.JournalH_Date,
								A.proj_Code,
								A.Acc_Id,
								A.Base_Debet,
								A.Base_Kredit,
								A.LastUpdate
							FROM
								tbl_journaldetail A
							WHERE
								A.GEJ_STAT = 3 AND A.isSyncY = 0
							ORDER BY A.JournalH_Date ASC";
			$resJOURNT1	= $this->db->query($sqlJOURNT1)->result();
			foreach($resJOURNT1 as $rowJ1) :
				$totCount 	= $totCount + 1;
				$journID	= $rowJ1->JournalD_Id;
				$journCode	= $rowJ1->JournalH_Code;
				$journDate	= $rowJ1->JournalH_Date;
				$LastUpdate	= $rowJ1->LastUpdate;
				$AccId		= $rowJ1->Acc_Id;
				$proj_Code1	= $rowJ1->proj_Code;
				$BaseDebet	= $rowJ1->Base_Debet;
				$BaseKredit	= $rowJ1->Base_Kredit;

				$accYr		= date('Y', strtotime($journDate));

				$syncPRJ	= '';
				$sqlSyns	= "SELECT syncPRJ FROM tbl_chartaccount 
								WHERE PRJCODE = '$proj_Code1' AND Account_Number = '$AccId'";
				$resSyns	= $this->db->query($sqlSyns)->result();
				foreach($resSyns as $rowSYNC) :
					$syncPRJ= $rowSYNC->syncPRJ;
				endforeach;

				$dataPecah 	= explode("~",$syncPRJ);
				$jmD 		= count($dataPecah);
			
				if($jmD > 0)
				{
					$SYNC_PRJ	= '';
					for($i=0; $i < $jmD; $i++)
					{
						$SYNC_PRJ	= $dataPecah[$i];
						$sqlUpdCOA	= "UPDATE tbl_chartaccount SET BaseD_$accYr = BaseD_$accYr+$BaseDebet,
											BaseK_$accYr = BaseK_$accYr+$BaseKredit
										WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$AccId'";
						$this->db->query($sqlUpdCOA);
					}
				}

				$sqlUpdJRD	= "UPDATE tbl_journaldetail SET isSyncY = 1 WHERE JournalD_Id = '$journID'";
				$this->db->query($sqlUpdJRD);
			endforeach;
        // END : TOTAL DEBET KREDIT
			
        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Sinkronisasi journal telah selesai.";
		}
		else
		{
			$alert1	= "Journal Synchronization has been completed.";
		}
		echo "$alert1";
	}
	
	function chkPass()
	{
		$this->load->model('login_model', '', TRUE);
		date_default_timezone_set("Asia/Jakarta");

		$DNOW			= date('Y-m-d H:i:s');
		$collData		= $_POST['collData'];
		$colExpl		= explode("~", $collData);
		$nikemp 		= $colExpl[0];
        $passemp 		= md5($colExpl[1]);

	    $LangID 		= $this->session->userdata['LangID'];

        $sqlEMPC		= "tbl_employee WHERE Emp_ID = '$nikemp' AND log_password = '$passemp'";
		$resEMPC 		= $this->db->count_all($sqlEMPC);
		if($resEMPC == 0)
		{
			$isRight	= 0;

			if($LangID == 'IND')
			{
				$alert1	= "Password yang Anda masukan salah.";
			}
			else
			{
				$alert1	= "The Password you entered is incorrect.";
			}
		}
		else
		{
			$sqlEmpStat = "UPDATE tbl_employee SET Log_Act = 1, OLStat = 1 WHERE Emp_ID = '$nikemp'";
			$this->db->query($sqlEmpStat);

			$compName 		= "";
			$sqlEmp 		= "SELECT CONCAT(First_Name, ' ', Last_Name) AS compName, Log_Act FROM tbl_employee WHERE Emp_ID = '$nikemp'";
			$resEmp 		= $this->db->query($sqlEmp)->result();
			foreach($resEmp as $row) :
				$compName 	= $row->compName;
				$LogAct 	= $row->Log_Act;
			endforeach;

			$isRight	= 1;

			if($LangID == 'IND')
			{
				$alert1	= "Selamat datang kembali $compName";
			}
			else
			{
				$alert1	= "Welcome back $compName";
			}
		}
		echo "$isRight~$alert1";
	}

	function trashBR()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $tblNameD 	= $colExpl[2];
        $DocNm		= $colExpl[3];
        $DocNum		= $colExpl[4];
        $PrjNm		= $colExpl[5];
        $PrjCode	= $colExpl[6];

        $BR_NUM		= $DocNum;
        $proj_Code	= $PrjCode;
        $PRJCODE 	= $PrjCode;

        // UPDATE STATUS
			$sqlUPBP	= "UPDATE tbl_br_header SET BR_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', ISVOID = '1' 
							WHERE BR_NUM = '$BR_NUM'";
			$this->db->query($sqlUPBP);
		
		// UPDATE JOURNAL
			$sqlDELJH	= "UPDATE tbl_journalheader SET GEJ_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', isCanceled = 1
							WHERE JournalH_Code = '$BR_NUM'";
			$this->db->query($sqlDELJH);

		// GET SOME INFORMATIONS
			$sqlBRH		= "SELECT BR_CODE, BR_DATE, BR_RECTYPE FROM tbl_br_header WHERE BR_NUM = '$BR_NUM' LIMIT 1";
			$resBRH		= $this->db->query($sqlBRH)->result();
			foreach($resBRH as $rowBRH):
				$BR_CODE	= $rowBRH->BR_CODE;
				$BR_DATE	= $rowBRH->BR_DATE;
				$BR_RECTYPE	= $rowBRH->BR_RECTYPE;
			endforeach;

			$PERIODM	= date('m', strtotime($BR_DATE));
			$PERIODY	= date('Y', strtotime($BR_DATE));
			
		// KURANGI NILAI COA. AGAR TIDAK MEMBUAT DOKUMEN BARU, MAKA TINGGAL MENGURANGI NILAI COA BERDASARKAN AKUN YANG TERLIBAT DI DALAM JURNAL
			$getJDET	= "SELECT A.JournalH_Code, A.JournalH_Date, A.Acc_Id, A.proj_Code, A.Currency_id, A.Base_Debet, A.Base_Kredit,
								A.CostCenter, A.curr_rate, A.isDirect, A.Journal_DK
							FROM tbl_journaldetail A
							WHERE A.JournalH_Code = '$BR_NUM' AND A.proj_Code = '$proj_Code'";
			$resJDET	= $this->db->query($getJDET)->result();
			foreach($resJDET as $rowJDET):
				$GEJ_CODE1		= $rowJDET->JournalH_Code;
				$GEJ_DATE		= $rowJDET->JournalH_Date;
				$accYr			= date('Y', strtotime($GEJ_DATE));
				$Acc_Numb		= $rowJDET->Acc_Id;
				$proj_Code		= $rowJDET->proj_Code;
				$Currency_id	= $rowJDET->Currency_id;
				$Base_Debet		= $rowJDET->Base_Debet;
				$Base_Kredit	= $rowJDET->Base_Kredit;
				$CostCenter		= $rowJDET->CostCenter;
				$curr_rate		= $rowJDET->curr_rate;
				$isDirect		= $rowJDET->isDirect;
				$Journal_DK		= $rowJDET->Journal_DK;
				
				// BUATKAN JURNAL KEBALIKAN
				if($Journal_DK == 'D')
				{
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$Base_Debet,
													Base_Debet2 = Base_Debet2-$Base_Debet, BaseD_$accYr = BaseD_$accYr-$Base_Debet
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
				}
				else
				{
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit-$Base_Kredit,
													Base_Kredit2 = Base_Kredit2-$Base_Kredit, BaseK_$accYr = BaseK_$accYr-$Base_Kredit
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
				}
			endforeach;

		// uUPDATE DETAIL
			$sqlBPD		= "SELECT DocumentNo, DocumentRef, Inv_Amount, Inv_Amount_PPn, Inv_Amount_PPh, GInv_Amount, Ginv_Remain,
								Amount, Amount_PPn
							FROM tbl_br_detail WHERE BR_NUM = '$BR_NUM' LIMIT 1";
			$resBPD		= $this->db->query($sqlBPD)->result();
			foreach($resBPD as $rowBPD):
				$DocumentNo 	= $rowBPD->DocumentNo;		// Sales Invoice No.
				$DocumentRef 	= $rowBPD->DocumentRef;
				$Inv_Amount		= $rowBPD->Inv_Amount;		// Nilai yang harus diterima
				$InvAmount_PPn	= $rowBPD->Inv_Amount_PPn;	// PPn Faktur
				$InvAmount_PPh	= $rowBPD->Inv_Amount_PPh;	// PPh Faktur
				$GInv_Amount	= $rowBPD->GInv_Amount;		// Nilai total yang harus diterima
				$Ginv_Remain	= $rowBPD->Ginv_Remain;		// Nilai sisa total yang harus diterima

				// DARI HASIL ANALISA TGL 03 08 2020
				// YANG DITERIMA HANYA NILAI GInv_Amount

				$Amount 		= $rowBPD->Amount;			// Nilai yang sudah diterima tiap invoice
				$Amount_PPn 	= $rowBPD->Amount_PPn;

				// HITUNG NILAI TOTAL YANG SUDAH DITERIMA TIAP FAKTUR PENJUALAN
					$AmountPA		= 0;
					$AmountP_PPnA	= 0;
					$sqlPAY			= "SELECT A.Amount, A.Amount_PPn 
										FROM tbl_br_detail A
											INNER JOIN tbl_br_header B ON A.BR_NUM = B.BR_NUM
										WHERE A.DocumentNo = '$DocumentNo' 
											AND B.BR_STAT IN (3,6)
											AND A.JournalH_Code != '$BR_NUM'";
					$resPAY			= $this->db->query($sqlPAY)->result();
					foreach($resPAY as $rowPAY) :
						$AmountP1		= $rowPAY->Amount;
						$AmountP_PPn1	= $rowPAY->Amount_PPn;
						$AmountPA		= $AmountPA + $AmountP1;
						$AmountP_PPnA	= $AmountP_PPnA + $AmountP_PPn1;
					endforeach;
					$AmountPA 			= $AmountPA ?: 0;
					$AmountP_PPnA 		= $AmountP_PPnA ?: 0;

				$INVPAYSTAT 	= 'NR';
				if($AmountPA > 0)
				{
					$INVPAYSTAT = 'HR';
				}

				// CEK TERAKHIR APAKAH MASIH ADA PENERIMAAN ATAS INVOICE INI
					$sqlPAYC	= "tbl_br_detail A
										INNER JOIN tbl_br_header B ON A.BR_NUM = B.BR_NUM
									WHERE A.DocumentNo = '$DocumentNo' 
										AND B.BR_STAT IN (3,6)
										AND A.JournalH_Code != '$BR_NUM'";
					$resPAYC	= $this->db->count_all($sqlPAYC);
					if($resPAYC == 0)
						$INVPAYSTAT = 'NR';

					/*if($CB_SOURCE == 'DP')
					{
						$updDP		= "UPDATE tbl_dp_header SET DP_PAID = 1 WHERE DP_NUM = '$DocumentNo'";
						$this->db->query($updDP);
					}
					else
					{*/
						$updBPH		= "UPDATE tbl_sinv_header SET SINV_AMOUNT_PAID = $AmountPA, SINV_PAYSTAT = '$INVPAYSTAT',
											SINV_STAT = 3, STATDESC = 'Approved', STATCOL = 'success'
										WHERE SINV_NUM = '$DocumentNo'";
						$this->db->query($updBPH);

					//}

				// START : UPDATE LR
					/*if($CB_SOURCE == 'PINV')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_PLAN = BPP_MTR_PLAN-$Amount 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}*/
				// END : UPDATE LR
			endforeach;
		// tbl_br_header,tbl_br_detail,tbl_sinv_header,tbl_journalheader,tbl_chartaccount

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $BR_CODE telah dibatalkan.";
		}
		else
		{
			$alert1	= "Document no. $BR_CODE has been void.";
		}
		echo "$alert1";
	}
	
	function updActStat()
	{
		$this->load->model('login_model', '', TRUE);
		date_default_timezone_set("Asia/Jakarta");

		$DNOW			= date('Y-m-d H:i:s');
		$collData		= $_POST['collData'];
		$colExpl		= explode("~", $collData);
		$nikemp 		= $colExpl[0];
        //$passemp 		= md5($colExpl[1]);

        $sqlEMPC		= "tbl_employee WHERE Emp_ID = '$nikemp'";
		$resEMPC 		= $this->db->count_all($sqlEMPC);
		if($resEMPC > 0)
		{
			$sqlEmpStat = "UPDATE tbl_employee SET Log_Act = 0 WHERE Emp_ID = '$nikemp'";
			$this->db->query($sqlEmpStat);
		}
	}
	
	function chkActStat()
	{
		$this->load->model('login_model', '', TRUE);
		date_default_timezone_set("Asia/Jakarta");

		$DNOW			= date('Y-m-d H:i:s');
		$collData		= $_POST['collData'];
		$colExpl		= explode("~", $collData);
		$nikemp 		= $colExpl[0];
        //$passemp 		= md5($colExpl[1]);

	    $LangID 		= $this->session->userdata['LangID'];

	    $LogActD		= 1;	// Default is Login
		$LogAct 		= 1;	// Aktif
		$OLStat 		= 1;	// Stat Login
        $sqlEMPC		= "tbl_employee WHERE Emp_ID = '$nikemp'";
		$resEMPC 		= $this->db->count_all($sqlEMPC);
		if($resEMPC > 0)
		{
			$sqlEmpStat = "SELECT Log_Act, OLStat FROM tbl_employee WHERE Emp_ID = '$nikemp'";
			$resEmpStat = $this->db->query($sqlEmpStat)->result();
			foreach($resEmpStat as $rowEmpStat):
				$LogAct = $rowEmpStat->Log_Act ?: 0;
				$OLStat = $rowEmpStat->OLStat ?: 0;
			endforeach;
		}
		if($OLStat == 0 || $LogAct == 0)
			$LogActD	= 0;

		echo $LogActD;
	}
	
	function trashOwn()
	{
		date_default_timezone_set("Asia/Jakarta");

		$DNOW	= date('Y-m-d H:i:s');
		$ownC	= $_POST['ownC'];
		$EMPID	= $this->session->userdata['Emp_ID'];
		
		$comp_name	= '';
		$insCLogV 	= "UPDATE tbl_owner SET own_Status = 2, own_Disabled = '$DNOW', own_Disabler = '$EMPID' WHERE own_Code = '$ownC'";
		$this->db->query($insCLogV);
	}
	
	function acthOwn()
	{
		date_default_timezone_set("Asia/Jakarta");

		$DNOW	= date('Y-m-d H:i:s');
		$ownC	= $_POST['ownC'];
		$EMPID	= $this->session->userdata['Emp_ID'];
		
		$comp_name	= '';
		$insCLogV 	= "UPDATE tbl_owner SET own_Status = 1, own_Disabled = '$DNOW', own_Disabler = '$EMPID' WHERE own_Code = '$ownC'";
		$this->db->query($insCLogV);
	}
	
	function impCOA() // OK
	{
		$this->load->view('v_gl/v_coa/sampleView');
	}

	function trash_Veh()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $DocNm 		= $colExpl[2];
        $DocNum		= $colExpl[3];
        $veHBrand	= $colExpl[4];
        $vehNopol	= $colExpl[5];

		$sqlCODE	= "DELETE FROM $tblNameH WHERE $DocNm = '$DocNum'";
		$this->db->query($sqlCODE);

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Kendaraan $veHBrand $vehNopol telah dihapus.";
		}
		else
		{
			$alert1	= "Vehicle $veHBrand $vehNopol has been deleted.";
		}
		echo "$alert1";
    }

	function resJLD()	// Reset Job List Detail
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$PRJCODE 	= $_POST['collID'];

		$PRJNAME 	= '';
		$sqlPRJNM	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$resPRJNM	= $this->db->query($sqlPRJNM)->result();
		foreach($resPRJNM as $rowPRJNM) :
			$PRJNAME= $rowPRJNM->PRJNAME;
		endforeach;
		
		$dateNow	= date('YmdHis');
		$dateNow1	= date('Y-m-d H:i:s');
		$DefEmp_ID	= $this->session->userdata['Emp_ID'];

		// UPDATE Journal Header
		$resGEJH 	= "UPDATE tbl_journaldetail A, tbl_journalheader B
							SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date, A.JournalType = B.JournalType
						WHERE A.JournalH_Code = B.JournalH_Code";
		$this->db->query($resGEJH);

		// RESET SELURUH PENGGUNAAN JOBLISDETAIL
			$updJLD = "UPDATE tbl_joblist_detail SET OPN_QTY=0, OPN_AMOUNT=0, ITM_USED=0, ITM_USED_AM=0
						WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($updJLD);

			$updJLD = "UPDATE tbl_item SET ITM_OUT=0, ITM_OUTP=0, UM_VOLM=0, UM_AMOUNT=0
						WHERE PRJCODE = '$PRJCODE'";
			$this->db->query($updJLD);

		$tRow 		= 0;
		$JournType	= '';
		$sqlJLType	= "SELECT DISTINCT JournalType FROM tbl_journaldetail WHERE GEJ_STAT = 3 AND proj_Code = '$PRJCODE'";
		$resJLType	= $this->db->query($sqlJLType)->result();
		foreach($resJLType as $rowJLType) :
			$JournType	= $rowJLType->JournalType;
			if($JournType == 'GEJ')
			{
				//
			}
			elseif($JournType == 'CPRJ')
			{
				$sqlJDet	= "SELECT A.JournalH_Code, A.Acc_Id, A.JOBCODEID, A.ITM_CODE, A.ITM_VOLM, A.ITM_PRICE, A.Base_Debet
								FROM tbl_journaldetail A
								WHERE A.JournalType = 'CPRJ' AND Base_Debet > 0 AND A.GEJ_STAT = 3 AND proj_Code = '$PRJCODE'";
				$resJDet	= $this->db->query($sqlJDet)->result();
				foreach($resJDet as $rowJDet) :
					$tRow 		= $tRow + 1;
					$JOBCODEID	= $rowJDet->JOBCODEID;
					$ITM_CODE	= $rowJDet->ITM_CODE;
					$ITM_VOLM	= $rowJDet->ITM_VOLM;
					$ITM_PRICE	= $rowJDet->ITM_PRICE;
					$Base_Debet	= $rowJDet->Base_Debet;

					// UPDATE JOBLIST DETAIL
						$updJLD = "UPDATE tbl_joblist_detail SET ITM_USED=ITM_USED+$ITM_VOLM, ITM_USED_AM=ITM_USED_AM+$Base_Debet
									WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
						$this->db->query($updJLD);

					// UPDATE ITEM
						$updJLD = "UPDATE tbl_item SET ITM_OUT=ITM_OUT+$ITM_VOLM, ITM_OUTP=ITM_OUTP+$Base_Debet,
														UM_VOLM=UM_VOLM+$ITM_VOLM, UM_AMOUNT=UM_AMOUNT+$Base_Debet
									WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
						$this->db->query($updJLD);
				endforeach;
			}
			elseif($JournType == 'OPN')
			{
				$tRow 		= $tRow + 1;
				$sqlJDet	= "SELECT A.JournalH_Code, A.Acc_Id, A.JOBCODEID, A.ITM_CODE, A.ITM_VOLM, A.ITM_PRICE, A.Base_Debet
								FROM tbl_journaldetail A
								WHERE A.JournalType = 'OPN' AND Base_Debet > 0 AND A.GEJ_STAT = 3 AND proj_Code = '$PRJCODE'";
				$resJDet	= $this->db->query($sqlJDet)->result();
				foreach($resJDet as $rowJDet) :
					$tRow 		= $tRow + 1;
					$OPNH_NUM	= $rowJDet->JournalH_Code;
					$JOBCODEID	= $rowJDet->JOBCODEID;
					$ITM_CODE	= $rowJDet->ITM_CODE;
					$ITM_VOLM	= $rowJDet->ITM_VOLM;
					$ITM_PRICE	= $rowJDet->ITM_PRICE;
					$Base_Debet	= $rowJDet->Base_Debet;

					if($JOBCODEID == '')
					{
						$sqlOPND	= "SELECT JOBCODEID, ITM_CODE, OPND_VOLM, OPND_ITMPRICE, OPND_ITMTOTAL
										FROM tbl_opn_detail
										WHERE OPNH_NUM = '$OPNH_NUM' AND PRJCODE = '$PRJCODE'";
						$resOPND	= $this->db->query($sqlOPND)->result();
						foreach($resOPND as $rowOPND) :
							$JOBCODEID	= $rowOPND->JOBCODEID;
							$ITM_CODE	= $rowOPND->ITM_CODE;
							$ITM_VOLM	= $rowOPND->OPND_VOLM;
							$OPND_PRICE	= $rowOPND->OPND_ITMPRICE;
							$Base_Debet	= $rowOPND->OPND_ITMTOTAL;
							// UPDATE JOBLIST DETAIL
								$updJLD = "UPDATE tbl_joblist_detail SET OPN_QTY=OPN_QTY+$ITM_VOLM, OPN_AMOUNT=OPN_AMOUNT+$Base_Debet,
												ITM_USED=ITM_USED+$ITM_VOLM, ITM_USED_AM=ITM_USED_AM+$Base_Debet
											WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
								$this->db->query($updJLD);

							// UPDATE ITEM
								$updJLD = "UPDATE tbl_item SET ITM_OUT=ITM_OUT+$ITM_VOLM, ITM_OUTP=ITM_OUTP+$Base_Debet,
																UM_VOLM=UM_VOLM+$ITM_VOLM, UM_AMOUNT=UM_AMOUNT+$Base_Debet
											WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
								$this->db->query($updJLD);

							// UPDATE JOURNAL
								$updJD = "UPDATE tbl_journaldetail SET JOBCODEID = '$JOBCODEID'
											WHERE JournalH_Code = '$OPNH_NUM' AND proj_Code = '$PRJCODE'";
								$this->db->query($updJD);
						endforeach;
					}
					else
					{
						// UPDATE JOBLIST DETAIL
							$updJLD = "UPDATE tbl_joblist_detail SET OPN_QTY=OPN_QTY+$ITM_VOLM, OPN_AMOUNT=OPN_AMOUNT+$Base_Debet,
											ITM_USED=ITM_USED+$ITM_VOLM, ITM_USED_AM=ITM_USED_AM+$Base_Debet
										WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
							$this->db->query($updJLD);

						// UPDATE ITEM
							$updJLD = "UPDATE tbl_item SET ITM_OUT=ITM_OUT+$ITM_VOLM, ITM_OUTP=ITM_OUTP+$Base_Debet,
															UM_VOLM=UM_VOLM+$ITM_VOLM, UM_AMOUNT=UM_AMOUNT+$Base_Debet
										WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
							$this->db->query($updJLD);
					}
				endforeach;
			}
			elseif($JournType == 'UM')
			{
				$tRow 		= $tRow + 1;
				$sqlJDet	= "SELECT A.JournalH_Code, A.Acc_Id, A.JOBCODEID, A.ITM_CODE, A.ITM_VOLM, A.ITM_PRICE, A.Base_Debet
								FROM tbl_journaldetail A
								WHERE A.JournalType = 'UM' AND Base_Debet > 0 AND A.GEJ_STAT = 3 AND proj_Code = '$PRJCODE'";
				$resJDet	= $this->db->query($sqlJDet)->result();
				foreach($resJDet as $rowJDet) :
					$tRow 		= $tRow + 1;
					$UM_NUM		= $rowJDet->JournalH_Code;
					$JOBCODEID	= $rowJDet->JOBCODEID;
					$ITM_CODE	= $rowJDet->ITM_CODE;
					$ITM_VOLM	= $rowJDet->ITM_VOLM;
					$ITM_PRICE	= $rowJDet->ITM_PRICE;
					$Base_Debet	= $rowJDet->Base_Debet;

					// UPDATE JOBLIST DETAIL
						$updJLD = "UPDATE tbl_joblist_detail SET ITM_USED=ITM_USED+$ITM_VOLM, ITM_USED_AM=ITM_USED_AM+$Base_Debet
									WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
						$this->db->query($updJLD);

					// UPDATE ITEM
						$updJLD = "UPDATE tbl_item SET ITM_OUT=ITM_OUT+$ITM_VOLM, ITM_OUTP=ITM_OUTP+$Base_Debet,
														UM_VOLM=UM_VOLM+$ITM_VOLM, UM_AMOUNT=UM_AMOUNT+$Base_Debet
									WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
						$this->db->query($updJLD);

					// UPDATE JOURNAL
						$updJD = "UPDATE tbl_journaldetail SET JOBCODEID = '$JOBCODEID'
									WHERE JournalH_Code = '$UM_NUM' AND proj_Code = '$proj_Code'";
						$this->db->query($updJD);
				endforeach;
			}
		endforeach;
			
        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Reset Pekerjaan untuk proyek $PRJNAME telah selesai. ($tRow row)";
		}
		else
		{
			$alert1	= "The joblist detail for Project $PRJNAME has been completed. ($tRow row)";
		}
		echo "$alert1";
    }

	function getJobCode()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$JOBCODEID 	= $colExpl[0];
        $JOBPARENT 	= $colExpl[1];
        $PRJCODE 	= $colExpl[2];

        // GET JOBCODEID
			$sqlCODEC	= "tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$resCODEC	= $this->db->count_all($sqlCODEC);

		// GET JOBCODEID
			$DOCC 		= '';
			$sqlCODE	= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBPARENT' AND PRJCODE = '$PRJCODE' ORDER BY JOBCODEID DESC LIMIT 1";
			$resCODE	= $this->db->query($sqlCODE)->result();
			foreach($resCODE as $rowCODE):
				$DOCC 	= $rowCODE->JOBCODEID;
			endforeach;
			
        $LangID 	= $this->session->userdata['LangID'];
        if($resCODEC > 0)
        {
			$stat 		= 0;
			if($LangID == 'IND')
			{
				$alert1	= "Kode yang Anda masukan sudah ada. Kode terakhir $DOCC";
			}
			else
			{
				$alert1	= "The code you entered is already exist. The last code is $DOCC";
			}
		}
		elseif($resCODEC == 0)
		{
			$stat 		= 1;
			if($LangID == 'IND')
			{
				$alert1	= "Kode yang Anda masukan diterima.";
			}
			else
			{
				$alert1	= "The code you entered is accepted.";
			}
		}
		if($JOBCODEID == '')
		{
			$stat 		= 0;
			if($LangID == 'IND')
			{
				$alert1	= "Anda belum memasukan kdoe pekerjaan. Kode pekerjaan terakhir : $DOCC";
			}
			else
			{
				$alert1	= "You have not entered the job code. The last code : $DOCC";
			}
		}
		echo "$stat~$alert1";
	}

	function getLCHRT($CAT)
	{
		echo $CAT;
	}

	function getPRDQ()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$tDate 	= $_POST['tDate'];
		$tDate	= substr($tDate, 0, strpos($tDate, '('));
		$rDate 	= date('Y-m-d', strtotime($tDate));

		/*$sqlPRD = "SELECT SUM(DOC_PRODQ) AS TOT_PRDQ, SUM(DOC_PRODV) AS TOT_PRDV
					FROM tbl_doc_concl WHERE DOC_DATE = '$rDate'";
	    $resPRD = $this->db->query($sqlPRD)->result();
	    foreach($resPRD as $rowPRD) :
	        $T_PRDQ  = $rowPRD->TOT_PRDQ;
	        $T_PRDV  = $rowPRD->TOT_PRDV;
	    endforeach;
	    if($T_PRDQ == '') $T_PRDQ = 0;
		*/
	    $T_PRDQ 	= 17;
		echo $T_PRDQ;
	}

	function getCUSTIMG()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$cscode = $_POST['cscode'];
		//$cscode = "PL20000003";
		$urlIMG = 'assets/AdminLTE-2.0.5/cust_image/'.$cscode;

		if(!is_dir($urlIMG))
			$imgLoc	= 0;
		else
			$imgLoc = 1;

		echo $imgLoc;
	}
	
	function chkAct()
	{
		$this->load->model('login_model', '', TRUE);
		date_default_timezone_set("Asia/Jakarta");
		
		$DNOW			= date('Y-m-d H:i:s');
		$collData		= $_POST['collData'];
		$colExpl		= explode("~", $collData);

		$ips 			= $colExpl[0];
		$appNm 			= $colExpl[1];
		$actKey			= $colExpl[2];
		$tspkA 			= $colExpl[3];
		$collActK 		= $ips.$appNm.$actKey;
        $tspkB 			= md5($collActK);

	    $LangID 		= $this->session->userdata['LangID'];

		if($tspkA == $tspkB)
		{
			$updAppNm	= "UPDATE tappname SET app_name = '$appNm', comp_name = '$appNm'";
			$this->db->query($updAppNm);

			$isRight	= 1;

			if($LangID == 'IND')
			{
				$alert1	= "Selamat! Software Anda sudah berhasil diaktivasi.";
			}
			else
			{
				$alert1	= "Congratulations! Your software has been successfully activated.";
			}
		}
		else
		{
			$isRight	= 0;

			if($LangID == 'IND')
			{
				$alert1	= "Maaf, perpaduan nama perusahaan dan kode aktivasi tidak sinkron.";
			}
			else
			{
				$alert1	= "Sorry, the combination of the company name and activation code is out of sync.";
			}
		}
		echo "$isRight~$alert1";
		//"2e6d385132743eb8f3b52974e82ebe31~81a7ea9305d4359b09c52ec9ee545eab";
		// 2e6d385132743eb8f3b52974e82ebe31
		// 81a7ea9305d4359b09c52ec9ee545eab~::1PT Khalifa Synergi2e6d385132743eb8f3b52974e82ebe31

	}

	function trashVCat()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $DocNm 		= $colExpl[2];
        $DocNum		= $colExpl[3];

        $sqlSPL 	= "SELECT VendCat_Name AS vendName FROM $tblNameH WHERE $DocNm = '$DocNum'";
	    $resSPL     = $this->db->query($sqlSPL)->result();
	    foreach($resSPL as $rowSPL) :
	        $vendNM = $rowSPL->vendName;
	    endforeach;

		$sqlCODE	= "DELETE FROM $tblNameH WHERE $DocNm = '$DocNum'";
		$this->db->query($sqlCODE);

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Kategori Suplier $vendNM telah dihapus.";
		}
		else
		{
			$alert1	= "Supplier Category $vendNM has been deleted.";
		}
		echo "$alert1";
    }

	function trashPSTR()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $DocNm 		= $colExpl[2];
        $DocNum		= $colExpl[3];

        $sqlSTR 	= "SELECT POSS_NAME FROM tbl_position_str WHERE POSS_CODE = '$DocNum'";
	    $resSTR     = $this->db->query($sqlSTR)->result();
	    foreach($resSTR as $rowSTR) :
	        $pstrNM = $rowSTR->POSS_NAME;
	    endforeach;

		$sqlCODE	= "DELETE FROM $tblNameH WHERE $DocNm = '$DocNum'";
		$this->db->query($sqlCODE);

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Posisi Struktur $pstrNM telah dihapus.";
		}
		else
		{
			$alert1	= "Supplier Category $pstrNM has been deleted.";
		}
		echo "$alert1";
    }

	function trashTTK()
	{
		date_default_timezone_set("Asia/Jakarta");

		$collID 		= $_POST['collID'];
		$colExpl		= explode("~", $collID);
		$url 			= $colExpl[0];
        $tblNameH 		= $colExpl[1];
        $tblNameD 		= $colExpl[2];
        $DocNm			= $colExpl[3];
        $DocNum			= $colExpl[4];
        $PrjNm			= $colExpl[5];
        $PrjCode		= $colExpl[6];

        $TTK_NUM		= $DocNum;
        $proj_Code		= $PrjCode;
        $PRJCODE 		= $PrjCode;

        $TTK_STAT 		= 9;
        $DefEmp_ID 		= $this->session->userdata['Emp_ID'];
        $comp_init 		= $this->session->userdata('comp_init');
        $TTK_UPDATED 	= date('Y-m-d H:i:s');

        $this->load->model('m_updash/m_updash', '', TRUE);

		// 1. UPDATE STATUS
			$sqlUPH	= "UPDATE tbl_ttk_header SET TTK_STAT = '9', STATDESC = 'Void', STATCOL = 'danger' WHERE TTK_NUM = '$TTK_NUM'";
			$this->db->query($sqlUPH);

		// 2. UPDATE ITEM
			$s_TTKD 	= "SELECT A.TTK_CODE, A.JOBCODEID, A.ITM_CODE, A.TTK_VOLM, A.TTK_PRICE, A.TTK_TOTAL,
								A.TAXCODE1, A.TAXPRICE1, A.TTK_DESC,
								B.ITM_UNIT, B.ITM_GROUP, B.ITM_TYPE, B.ACC_ID_UM AS ACC_ID
							FROM tbl_ttk_detail_itm A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.TTK_NUM = '$TTK_NUM' AND A.PRJCODE = '$PRJCODE'";
			$r_TTKD		= $this->db->query($s_TTKD)->result();
			foreach($r_TTKD as $rw_TTKD) :
				$TTK_CODE	= $rw_TTKD->TTK_CODE;
				$JOBCODEID	= $rw_TTKD->JOBCODEID;
				$ITM_CODE	= $rw_TTKD->ITM_CODE;
				$TTK_VOLM	= $rw_TTKD->TTK_VOLM;
				$TTK_PRICE	= $rw_TTKD->TTK_PRICE;
				$TTK_TOTAL	= $rw_TTKD->TTK_TOTAL;
				
				// RETURN BUDGET QTY IN JOBLIST
					$sqlUpDetJ		= "UPDATE tbl_joblist_detail SET
											REQ_VOLM 	= REQ_VOLM - $TTK_VOLM, 
											REQ_AMOUNT 	= REQ_AMOUNT - $TTK_TOTAL, 
											PO_VOLM 	= PO_VOLM - $TTK_VOLM, 
											PO_AMOUNT 	= PO_AMOUNT - $TTK_TOTAL, 
											WO_QTY 		= WO_QTY - $TTK_VOLM, 
											WO_AMOUNT 	= WO_AMOUNT - $TTK_TOTAL, 
											OPN_QTY 	= OPN_QTY - $TTK_VOLM, 
											OPN_AMOUNT 	= OPN_AMOUNT - $TTK_TOTAL, 
											ITM_USED 	= ITM_USED - $TTK_VOLM, 
											ITM_USED_AM = ITM_USED_AM - $TTK_TOTAL
										WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
					$this->db->query($sqlUpDetJ);		
				
				// RETURN BUDGET QTY IN MASTER ITEM
					$sqlUpDet	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM + $TTK_VOLM, ITM_TOTALP = ITM_TOTALP + $TTK_TOTAL,
										ITM_REMQTY = ITM_REMQTY + $TTK_VOLM, ITM_LASTP = $TTK_PRICE,
										ITM_OUT = ITM_OUT - $TTK_VOLM, ITM_OUTP = ITM_OUTP - $TTK_TOTAL
									WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
					$this->db->query($sqlUpDet);
			endforeach;

		// 3. UPDATE JOURNAL
	        // UPDATE STATUS
				$sqlDELJH	= "UPDATE tbl_journalheader SET GEJ_STAT = '9', STATDESC = 'Void', STATCOL = 'danger', isCanceled = 1
								WHERE JournalH_Code = '$TTK_NUM'";
				$this->db->query($sqlDELJH);

			// GET SOME INFORMATIONS
				/*$sqlBRH		= "SELECT BR_CODE, BR_DATE, BR_RECTYPE FROM tbl_br_header WHERE BR_NUM = '$BR_NUM' LIMIT 1";
				$resBRH		= $this->db->query($sqlBRH)->result();
				foreach($resBRH as $rowBRH):
					$BR_CODE	= $rowBRH->BR_CODE;
					$BR_DATE	= $rowBRH->BR_DATE;
					$BR_RECTYPE	= $rowBRH->BR_RECTYPE;
				endforeach;

				$PERIODM	= date('m', strtotime($BR_DATE));
				$PERIODY	= date('Y', strtotime($BR_DATE));*/
				
			// KURANGI NILAI COA. AGAR TIDAK MEMBUAT DOKUMEN BARU, MAKA TINGGAL MENGURANGI NILAI COA BERDASARKAN AKUN YANG TERLIBAT DI DALAM JURNAL
				$getJDET	= "SELECT A.JournalH_Code, A.JournalH_Date, A.Acc_Id, A.proj_Code, A.Currency_id, A.Base_Debet, A.Base_Kredit,
									A.CostCenter, A.curr_rate, A.isDirect, A.Journal_DK
								FROM tbl_journaldetail A
								WHERE A.JournalH_Code = '$TTK_NUM' AND A.proj_Code = '$PRJCODE'";
				$resJDET	= $this->db->query($getJDET)->result();
				foreach($resJDET as $rowJDET):
					$GEJ_CODE1		= $rowJDET->JournalH_Code;
					$GEJ_DATE		= $rowJDET->JournalH_Date;
					$accYr			= date('Y', strtotime($GEJ_DATE));
					$Acc_Numb		= $rowJDET->Acc_Id;
					$proj_Code		= $rowJDET->proj_Code;
					$Currency_id	= $rowJDET->Currency_id;
					$Base_Debet		= $rowJDET->Base_Debet;
					$Base_Kredit	= $rowJDET->Base_Kredit;
					$CostCenter		= $rowJDET->CostCenter;
					$curr_rate		= $rowJDET->curr_rate;
					$isDirect		= $rowJDET->isDirect;
					$Journal_DK		= $rowJDET->Journal_DK;
					
					if($Journal_DK == 'D')
					{
						// START : Update to COA - Debit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$Base_Debet,
														Base_Debet2 = Base_Debet2-$Base_Debet, BaseD_$accYr = BaseD_$accYr-$Base_Debet
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
					else
					{
						// START : Update to COA - Debit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Numb' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit-$Base_Kredit,
														Base_Kredit2 = Base_Kredit2-$Base_Kredit, BaseK_$accYr = BaseK_$accYr-$Base_Kredit
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Numb'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
				endforeach;

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $TTK_CODE telah dibatalkan.";
		}
		else
		{
			$alert1	= "Document no. $TTK_CODE has been void.";
		}
		echo "$alert1";
	}

	function trashMANL()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $tblNameD 	= $colExpl[2];
        $DocNm		= $colExpl[3];
        $DocNum		= $colExpl[4];
        $PrjNm		= $colExpl[5];
        $PrjCode	= $colExpl[6];

        $sqlDelH	= "DELETE FROM $tblNameH WHERE $DocNm = '$DocNum'";
        $this->db->query($sqlDelH);

        $sqlDelD	= "DELETE FROM $tblNameD WHERE $DocNm = '$DocNum'";
        $this->db->query($sqlDelD);

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $DocNum telah dihapus.";
		}
		else
		{
			$alert1	= "Document no. $DocNum has been deleted.";
		}
		echo "$alert1";
	}

	function JaNlProc()
	{
        $this->load->model('m_updash/m_updash', '', TRUE);
		date_default_timezone_set("Asia/Jakarta");

		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$JAN_NUM 	= $colExpl[0];
        $JOBCODEID 	= $colExpl[1];				// JOB PARENT
        $PRJCODE 	= $colExpl[2];
        $JAN_TYPE 	= $colExpl[3];

		$PRJ_HO 	= "";
		$s_00 		= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_00 		= $this->db->query($s_00)->result();
		foreach($r_00 as $rw_00) :
			$PRJ_HO = $rw_00->PRJCODE_HO;
		endforeach;

		// START : INSERT PROCEDURE
			// 	1. 	SIMPAN KE KODE PEKERJAAN YANG DITENTUKAN DI HEADER (DAFTAR TUJUAN PEKERJAAN YANG AKAN DICOPY-KAN DETILNYA)
					$JOBCODEID_MST 	= "";
					$s_01 	= "SELECT JOBCODEID, JOBDESC FROM tbl_janalysis_header WHERE JAN_NUM = '$JAN_NUM' AND PRJCODE = '$PRJCODE'";
					$r_01 	= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$JOBCODEID_01	= $rw_01->JOBCODEID;	// JOB PARENT
						$JOBCODEID_MST	= $rw_01->JOBCODEID;	// JOB PARENT
						$JOBDESC_01		= $rw_01->JOBDESC;

						// START : HEADER DETIAL
							$nNo 	= 0;						// For next ORD_IDDETIL
							$ORD_IDN= 0;
							$JOBLEV = 0;
							$JOBIDP = $JOBCODEID_01;
							$s_02	= "SELECT ORD_ID, IS_LEVEL AS JOBLEV FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID_01' AND PRJCODE = '$PRJCODE'";
							$r_02 	= $this->db->query($s_02)->result();
							foreach($r_02 as $rw_02) :
								$ORD_IDN	= $rw_02->ORD_ID;
								$JOBLEV 	= $rw_02->JOBLEV;
							endforeach;
						// END : HEADER DETIAL

						// START : CLEAR ALL DETAIL
							$s_03 	= "DELETE FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBCODEID_01' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_03);
						// END : CLEAR ALL DETAIL

						// START : INSERT INTO JOBLIST DETAIL
							$s_04A 	= "tbl_janalysis_detail WHERE JAN_NUM = '$JAN_NUM' AND PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID_01'";
							$r_04A 	= $this->db->count_all($s_04A);

							// START : REORDER OR_ID JIKA ADA ORD_ID YG SAMA
								$s_04B 	= 	"UPDATE tbl_joblist_detail SET ORD_ID = ORD_ID + $r_04A
												WHERE ORD_ID > $ORD_IDN AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_04B);
							// END : REORDER OR_ID JIKA ADA ORD_ID YG SAMA

							$s_04 	= "SELECT * FROM tbl_janalysis_detail
										WHERE JAN_NUM = '$JAN_NUM' AND PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID_01'";
							$r_04 	= $this->db->query($s_04)->result();
							foreach($r_04 as $rw_04):
								$nNo 		= $nNo+1;
								$JAN_NUM 	= $rw_04->JAN_NUM;
								$JAN_CODE 	= $rw_04->JAN_CODE;
								$MAN_NUM 	= $rw_04->MAN_NUM;
								$MAN_CODE 	= $rw_04->MAN_CODE;
								$PRJCODE 	= $rw_04->PRJCODE;
								$JOBCODEID 	= $JOBCODEID_01;
								$JOBDESC 	= $JOBDESC_01;
								$ITM_CODE 	= $rw_04->ITM_CODE;
								$ITM_NAME 	= $rw_04->ITM_NAME;
								$ITM_UNIT 	= $rw_04->ITM_UNIT;
								$ITM_GROUP 	= $rw_04->ITM_GROUP;
								$ITM_QTY 	= $rw_04->ITM_QTY;
								$ITM_PRICE 	= $rw_04->ITM_PRICE;
								$ITM_TOTAL 	= $rw_04->ITM_TOTAL;
								$ITM_KOEF 	= $rw_04->ITM_KOEF;

								$ORD_IDN 	= $ORD_IDN+1;
								$JOBID_DET 	= $JOBIDP.".".$nNo;
								$JOBLEV_DET = $JOBLEV+1;

								// INSERT INTO JOBDET
									$s_05	= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
													PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC, ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM,
													ITM_PRICE, ITM_LASTP, PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
													ISLAST, Patt_Number)
												VALUES ($ORD_IDN, '$JOBID_DET', '$JOBID_DET', '$JOBIDP', '$JOBIDP',
													'$PRJCODE', '$PRJ_HO', '$ITM_CODE', '$ITM_NAME', '$ITM_GROUP','$ITM_GROUP', '$ITM_UNIT', '$ITM_QTY', 
													'$ITM_PRICE', '$ITM_PRICE', '$PRJCODE', '$PRJ_HO', '$ITM_TOTAL', '0', '0', '0', '$JOBLEV_DET', 
													'1', '$nNo')";
									$this->db->query($s_05);

								// UPDATE ITEM BUDGET
									$TOTVOLBUDG = 0;
									$TOTAMNBUDG = 1;
									$s_06	= "SELECT SUM(B.ITM_VOLM) AS TOTVOLBUDG, SUM(B.ITM_BUDG) AS TOTAMNBUDG FROM tbl_joblist_detail B
													WHERE B.ITM_CODE = '$ITM_CODE' AND B.PRJCODE = '$PRJCODE'";
									$r_06 	= $this->db->query($s_06)->result();
									foreach($r_06 as $rw_06) :
										$TOTVOLBUDG = $rw_06->TOTVOLBUDG;
										$TOTAMNBUDG = $rw_06->TOTAMNBUDG;
									endforeach;
									$ITM_VOLMBG = $TOTVOLBUDG ?: 0;
									$ITM_VOLMBGP=$ITM_VOLMBG;
									if($ITM_VOLMBG == 0)
										$ITM_VOLMBGP = 1;
									$ITM_AMNBG 	= $TOTAMNBUDG ?: 1;
									//$TOTAMNBUD= $ITM_VOLMBG * $ITM_PRICE;
									$ITM_AVGP 	= $ITM_AMNBG / $ITM_VOLMBGP;

									$s_07		= "UPDATE tbl_item SET ITM_VOLMBG = $ITM_VOLMBG, ITM_PRICE = $ITM_PRICE, ITM_LASTP = $ITM_PRICE,
														ITM_TOTALP = $ITM_AMNBG, ITM_AVGP = $ITM_AVGP
													WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$PRJCODE'";
									$this->db->query($s_07);
							endforeach;
						// END : INSERT INTO JOBLIST DETAIL
					endforeach;

			// 2. SIMPAN KE DAFTAR PEKERJAAN YANG DIPILIH (TERAPKAN KE PEKERJAAN ....)
				$s_07 	= "tbl_janalysis_jlist  WHERE JAN_NUM = '$JAN_NUM' AND PRJCODE = '$PRJCODE'";
				$r_07 	= $this->db->count_all($s_07);

				if($r_07 > 0)
				{
					$s_08 	= "SELECT * FROM tbl_janalysis_jlist WHERE JAN_NUM = '$JAN_NUM' AND PRJCODE = '$PRJCODE'";
					$r_08 	= $this->db->query($s_08)->result();
					foreach($r_08 as $rw_08):
						$JOBCODEID_01	= $rw_08->JOBCODEID;	// JOB PARENT
						$JOBDESC_01		= $rw_08->JOBDESC;

						// START : HEADER DETIAL
							$nNo 	= 0;						// For next ORD_IDDETIL
							$ORD_IDN= 0;
							$JOBLEV = 0;
							$JOBIDP = $JOBCODEID_01;
							$s_09	= "SELECT ORD_ID, IS_LEVEL AS JOBLEV FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID_01' AND PRJCODE = '$PRJCODE'";
							$r_09 	= $this->db->query($s_09)->result();
							foreach($r_09 as $rw_09) :
								$ORD_IDN	= $rw_09->ORD_ID;
								$JOBLEV 	= $rw_09->JOBLEV;
							endforeach;
						// END : HEADER DETIAL

						// START : CLEAR ALL DETAIL IF JAN_TYPE = 2, REPLACE ALL OR GET LAST OR_ID
							if($JAN_TYPE == 2)		// REPLACE = DELETE ALL DETIL
							{
								$s_10 	= "DELETE FROM tbl_joblist_detail WHERE JOBPARENT = '$JOBCODEID_01' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_10);
							}
							else
							{
								$s_11	= "SELECT MAX(ORD_ID) AS ORD_ID, COUNT(ORD_ID) AS TOT_RW FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID_01'";
								$r_11 	= $this->db->query($s_11)->result();
								foreach($r_11 as $rw_11) :
									$ORD_ID		= $rw_11->ORD_ID;
									$TOT_RW		= $rw_11->TOT_RW;
									if($ORD_ID == '')
										$ORD_ID = 0;
									if($TOT_RW == '')
										$TOT_RW = 0;
								endforeach;
								$nNo 	= $TOT_RW+1;
							}
						// END : CLEAR ALL DETAIL IF JAN_TYPE = 2, REPLACE ALL OR GET LAST OR_ID

						// START : INSERT INTO JOBLIST DETAIL
							$s_12 	= "tbl_janalysis_detail WHERE JAN_NUM = '$JAN_NUM' AND PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID_MST'";
							$r_12 	= $this->db->count_all($s_12);

							// START : REORDER OR_ID JIKA ADA ORD_ID YG SAMA
								$s_13 	= 	"UPDATE tbl_joblist_detail SET ORD_ID = ORD_ID + $r_12
												WHERE ORD_ID > $ORD_IDN AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_13);
							// END : REORDER OR_ID JIKA ADA ORD_ID YG SAMA

							$s_14 	= "SELECT * FROM tbl_janalysis_detail
										WHERE JAN_NUM = '$JAN_NUM' AND PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID_MST'";
							$r_14 	= $this->db->query($s_14)->result();
							foreach($r_14 as $rw_14):
								$nNo 		= $nNo+1;
								$JAN_NUM 	= $rw_14->JAN_NUM;
								$JAN_CODE 	= $rw_14->JAN_CODE;
								$MAN_NUM 	= $rw_14->MAN_NUM;
								$MAN_CODE 	= $rw_14->MAN_CODE;
								$PRJCODE 	= $rw_14->PRJCODE;
								$JOBCODEID 	= $JOBCODEID_01;
								$JOBDESC 	= $JOBDESC_01;
								$ITM_CODE 	= $rw_14->ITM_CODE;
								$ITM_NAME 	= $rw_14->ITM_NAME;
								$ITM_UNIT 	= $rw_14->ITM_UNIT;
								$ITM_GROUP 	= $rw_14->ITM_GROUP;
								$ITM_QTY 	= $rw_14->ITM_QTY;
								$ITM_PRICE 	= $rw_14->ITM_PRICE;
								$ITM_TOTAL 	= $rw_14->ITM_TOTAL;
								$ITM_KOEF 	= $rw_14->ITM_KOEF;

								$ORD_IDN 	= $ORD_IDN+1;
								$JOBID_DET 	= $JOBIDP.".".$nNo;
								$JOBLEV_DET = $JOBLEV+1;

								// INSERT INTO JOBDET
									$s_15	= "INSERT INTO tbl_joblist_detail (ORD_ID, JOBCODEDET, JOBCODEID, JOBPARENT, JOBCODEID_P,
													PRJCODE, PRJCODE_HO, ITM_CODE, JOBDESC, ITM_GROUP, GROUP_CATEG, ITM_UNIT, ITM_VOLM,
													ITM_PRICE, ITM_LASTP, PRJPERIOD, PRJPERIOD_P, ITM_BUDG, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, IS_LEVEL, 
													ISLAST, Patt_Number)
												VALUES ($ORD_IDN, '$JOBID_DET', '$JOBID_DET', '$JOBIDP', '$JOBIDP',
													'$PRJCODE', '$PRJ_HO', '$ITM_CODE', '$ITM_NAME', '$ITM_GROUP','$ITM_GROUP', '$ITM_UNIT', '$ITM_QTY', 
													'$ITM_PRICE', '$ITM_PRICE', '$PRJCODE', '$PRJ_HO', '$ITM_TOTAL', '0', '0', '0', '$JOBLEV_DET', 
													'1', '$nNo')";
									$this->db->query($s_15);

								// UPDATE ITEM BUDGET
									$TOTVOLBUDG = 0;
									$TOTAMNBUDG = 1;
									$s_16	= "SELECT SUM(B.ITM_VOLM) AS TOTVOLBUDG, SUM(B.ITM_BUDG) AS TOTAMNBUDG FROM tbl_joblist_detail B
													WHERE B.ITM_CODE = '$ITM_CODE' AND B.PRJCODE = '$PRJCODE'";
									$r_16 	= $this->db->query($s_16)->result();
									foreach($r_16 as $rw_16) :
										$TOTVOLBUDG = $rw_16->TOTVOLBUDG;
										$TOTAMNBUDG = $rw_16->TOTAMNBUDG;
									endforeach;
									$ITM_VOLMBG = $TOTVOLBUDG ?: 0;
									$ITM_VOLMBGP=$ITM_VOLMBG;
									if($ITM_VOLMBG == 0)
										$ITM_VOLMBGP = 1;
									$ITM_AMNBG 	= $TOTAMNBUDG ?: 1;
									//$TOTAMNBUD= $ITM_VOLMBG * $ITM_PRICE;
									$ITM_AVGP 	= round($ITM_AMNBG / $ITM_VOLMBGP, 4);

									$s_17		= "UPDATE tbl_item SET ITM_VOLMBG = $ITM_VOLMBG, ITM_PRICE = $ITM_PRICE, ITM_LASTP = $ITM_PRICE,
														ITM_TOTALP = $ITM_AMNBG, ITM_AVGP = $ITM_AVGP
													WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$PRJCODE'";
									$this->db->query($s_17);
							endforeach;
						// END : INSERT INTO JOBLIST DETAIL
					endforeach;
				}
		// END : INSERT PROCEDURE

		// UPDATE STATUS ANALISA
			$s_18		= "UPDATE tbl_janalysis_header SET JAN_STAT = 3 WHERE JAN_NUM = '$JAN_NUM' AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_18);

		echo "Analisa RAP sudah kami proses. Silahkan cek di daftar RAP..!";
    }

    function getLastDocNum()
    {
		$MenuCode 	= $_POST['MenuCode'];
		$jrnType 	= $_POST['JournalType'];
		$tblName 	= $_POST['tblName'];
		$attDate 	= $_POST['attDate'];
		$attPRJCODE = $_POST['attPRJCODE'];
		$PRJCODE 	= $_POST['PRJCODE'];
		$CREATER	= $_POST['CREATER'];
		$PATTL 		= 6;

		$attCATEG 	= '';
		if(isset($_POST['attCATEG']))
			$attCATEG 	= $_POST['attCATEG'];

		//$IPADD 		= getHostByName(getHostName());
		//$HOSTNM 	= gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
		// START : GET DOCUMENT PATTERN
			$s_PattC	= "tbl_docpattern WHERE menu_code = '$MenuCode'";
			$r_PattC 	= $this->db->count_all($s_PattC);
			if($r_PattC > 0)
			{
				$isSetDocNo 	= 1;
				$s_Patt	= "SELECT Pattern_Code, Pattern_Position, Pattern_YearAktive, Pattern_MonthAktive, Pattern_DateAktive, Pattern_Length,
									useYear, useMonth, useDate
								FROM tbl_docpattern WHERE menu_code = '$MenuCode'";
				$r_Patt = $this->db->query($s_Patt)->result();
				foreach($r_Patt as $row) :
					$Pattern_Code 			= $row->Pattern_Code;
					$Pattern_Position 		= $row->Pattern_Position;
					$Pattern_YearAktive 	= $row->Pattern_YearAktive;
					$Pattern_MonthAktive 	= $row->Pattern_MonthAktive;
					$Pattern_DateAktive 	= $row->Pattern_DateAktive;
					$Pattern_Length 		= $row->Pattern_Length;
					$useYear 				= $row->useYear;
					$useMonth 				= $row->useMonth;
					$useDate 				= $row->useDate;
				endforeach;
				$PATTL 	= $Pattern_Length;
			}
			else
			{
				$isSetDocNo 				= 0;
				$Pattern_Code 				= "XXX";
				$Pattern_Length 			= 6;
				$PATTL 						= $Pattern_Length;
			}
		// END : GET DOCUMENT PATTERN
		
		$yearC 		= (int)date('Y');
		$month 		= (int)date('m');
		$date 		= (int)date('d');

		if($jrnType == '') $addQJType = '';
		else $addQJType = "AND JournalType = '$jrnType'";

		// LAST NUMBER
			//$s_00 = "$tblName WHERE YEAR($attDate) = $yearC AND $attPRJCODE = '$PRJCODE' $addQJType ";
			$s_00 	= "$tblName WHERE $attPRJCODE = '$PRJCODE' $addQJType ";
			$r_00 	= $this->db->count_all($s_00);
			$myMax	= $r_00+1;

			if($tblName == 'tbl_journalheader_vcash')
			{
				$MAXPATT 	= $myMax;
				$s_Max		= "SELECT MAX(RIGHT(Manual_No, $PATTL)) AS MAX_NO FROM tbl_journalheader_vcash WHERE proj_Code = '$PRJCODE'";
				$r_Max 		= $this->db->query($s_Max)->result();
				foreach($r_Max as $rw_Max) :
					$MAXPATT = (int)$rw_Max->MAX_NO;
				endforeach;
				if($MAXPATT == '')
					$MAXPATT 	= 0;
				$myMax 	= $MAXPATT+1;
			}

		// GET MAX IN TEMPORARY NUMBER
			/*$s_01 	= "tbl_doclist WHERE YEAR = $yearC AND PRJCODE = '$PRJCODE' AND MNCODE = '$MenuCode' AND RUNUM = $myMax
						AND IPADD != '$IPADD' AND HOSTNM != '$HOSTNM'";
			$r_01 = $this->db->count_all($s_01);
			if($r_01 > 0)
			{
				$MAXPATT 	= $myMax;
				$s_Max		= "SELECT MAX(RUNUM) AS MAXPATT FROM tbl_doclist WHERE YEAR = $yearC AND PRJCODE = '$PRJCODE' AND MNCODE = '$MenuCode'";
				$r_Max 		= $this->db->query($s_Max)->result();
				foreach($r_Max as $rw_Max) :
					$MAXPATT = $rw_Max->MAXPATT;
				endforeach;
				$myMax 	= $MAXPATT+1;
			}*/

		// MONTH PATTERN
			$thisMonth 	= $month;
			$lenMonth 	= strlen($thisMonth);
			if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
			$pattMonth = $nolMonth.$thisMonth;
		
		// DATE PATTERN
			$thisDate 	= $date;
			$lenDate 	= strlen($thisDate);
			if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
			$pattDate 	= $nolDate.$thisDate;
		
		// GROUP PATTERN
			if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
				$groupPattern = "$yearC$pattMonth$pattDate";
			elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
				$groupPattern = "$year$pattMonth";
			elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
				$groupPattern = "$yearC$pattDate";
			elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
				$groupPattern = "$pattMonth$pattDate";
			elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
				$groupPattern = "$yearC";
			elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
				$groupPattern = "$pattMonth";
			elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
				$groupPattern = "$pattDate";
			elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
				$groupPattern = "";

		// GENERATE NUMBER
			$lastNumb 	= $myMax;
			$len 		= strlen($lastNumb);
			
			if($Pattern_Length==2)
			{
				if($len==1) $nol="0";
			}
			elseif($Pattern_Length==3)
			{if($len==1) $nol="00";else if($len==2) $nol="0";
			}
			elseif($Pattern_Length==4)
			{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
			}
			elseif($Pattern_Length==5)
			{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
			}
			elseif($Pattern_Length==6)
			{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
			}
			elseif($Pattern_Length==7)
			{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
			}
			$pattNum 	= $nol.$lastNumb;
			$DocNum 	= "$Pattern_Code$attCATEG.$PRJCODE.$pattNum";

		// SAVE TO TEMPORARY
			/*$s_02 	= "tbl_doclist WHERE YEAR = $yearC AND PRJCODE = '$PRJCODE' AND MNCODE = '$MenuCode' AND RUNUM = $myMax
						AND IPADD = '$IPADD' AND HOSTNM = '$HOSTNM'";
			$r_02 = $this->db->count_all($s_02);
			if($r_02 == 0)
			{
				$s_02 	= "INSERT INTO tbl_doclist (YEAR, PRJCODE, MNCODE, PATTCODE, DOCCODE, RUNUM, IPADD, HOSTNM, CREATER)
							VALUES
							($yearC, '$PRJCODE', '$MenuCode', '$Pattern_Code', '$DocNum', $lastNumb, '$IPADD', '$HOSTNM', '$CREATER')";
				$this->db->query($s_02);
			}*/

		echo "$DocNum~$Pattern_Code";
    }
}