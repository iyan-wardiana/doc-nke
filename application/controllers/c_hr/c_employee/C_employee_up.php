<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 Januari 2018
 * File Name	= C_employee_up.php
 * Location		= -
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_employee_up extends CI_Controller
{
 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_hr/c_employee/c_employee_up/indexUpload/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function indexUpload() // USE
	{		
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
		$data['title'] 			= $appName;
		$data['h2_title'] 		= 'Employee Data';
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		$data["MenuCode"] 		= 'MN351';
		$data['form_action']	= site_url('c_hr/c_employee/c_employee_up/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
		$data['form_actSync']	= site_url('c_hr/c_employee/c_employee_up/sync_process/?id='.$this->url_encryption_helper->encode_url($appName));
		$data['up_stat'] 		= '';
		$data['isUpdDone_1'] 	= 1;
		$data['up_type'] 		= '';
		$data['UEMP_FNAME'] 	= '';		
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->view('v_hr/v_employee/employee_up', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	 
	function do_upload($offset=0)
	{
		/* -----------------------------------------------------------------------------
		try {
        //$conn = mysqli_connect('localhost', 'root', 'nke@dmin64', 'nke.co.id');
        $conn = mysqli_connect('localhost', 'root', 'nke@NUS4k0n$truk51', 'nke.co.id');
        //echo "Connected successfully"; 
		} 
		catch (exception $e) 
		{
			echo "Connection failed: " . $e->getMessage();
		}

		---------------------------------------------------------------------------------*/
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$UEMP_DATE		= date('Y-m-d H:i:s');
		
		$UEMP_EMP		= $DefEmp_ID;
		
		$data['form_action']	= site_url('c_hr/c_employee/c_employee_up/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
		$data['form_actSync']	= site_url('c_hr/c_employee/c_employee_up/sync_process/?id='.$this->url_encryption_helper->encode_url($appName));
		$filename 		= $_FILES["userfile"]["name"];
		$source 		= $_FILES["userfile"]["tmp_name"];
		$type 			= $_FILES["userfile"]["type"];
		$fsize			= $_FILES['userfile']['size'];
		$fext			= preg_replace("/.*\.([^.]+)$/","\\1", $_FILES['userfile']['name']);
		
		$file 						= $_FILES['userfile'];
		$file_name 					= $file['name'];
		$config['upload_path']   	= "uploads/employee/"; 
		$config['allowed_types']	= 'csv'; 
		$config['overwrite'] 		= TRUE;
		//$config['max_size']     	= 1000000000; 
		//$config['max_width']    	= 10024; 
		//$config['max_height']    	= 10000;  
		$config['file_name']       	= $file['name'];
		
		$this->load->library('upload', $config);
		
		$sqlGetFN	= "tbl_upload_emp WHERE UEMP_FNAME = '$file_name'";
		$resGetFN	= $this->db->count_all($sqlGetFN);
		
		if($resGetFN > 0)
		{
			$myPath = "uploads/employee/$filename";
			unlink($myPath);
		}
		
		if ( ! $this->upload->do_upload('userfile')) 
		{
			$up_stat	= 0;
		}
		else
		{
			$myPath = "uploads/employee/$filename";
			
			$trunFN 		= "TRUNCATE TABLE tbl_employee_new1";
			$this->db->query($trunFN);
			
			$file 	= fopen($myPath, "r");
			$i		= 0;
			//while (($getData = fgetcsv($file, 100000000, ";")) !== FALSE) 
			while (($getData = fgetcsv($file, 100000000, ",")) !== FALSE) 
			{
				$i	= $i + 1;
				if($i > 1)
				{
					$EMP_ID			= $getData[1];
					$FIRST_NAME		= $getData[2];
					$LAST_NAME		= $getData[3];
					$ISPROJECT		= $getData[4];
					$DEPT_CODE		= $getData[5];
					$REG_CODE		= $getData[6];
					$GOL_CODE		= $getData[7];
					$EMPG_KOEF		= $getData[8];
					//echo "$EMP_ID - $FIRST_NAME = $EMPG_KOEF<br>";
					$EMP_STAT		= $getData[9];
					$Joint_Date		= $getData[10];
					$Email			= $getData[11];
					//$EMPG_KOEF	= 0;
					/*$sqlKOEF 	= "SELECT EMPG_K_ALLOW FROM tbl_employee_gol WHERE EMPG_NAME = '$GOL_CODE'";
					$resKOEF 	= $this->db->query($sqlKOEF)->result();
					foreach($resKOEF as $rKoef) :
						$EMPG_KOEF 	= $rKoef->EMPG_K_ALLOW;		
					endforeach;*/
					
					//$DATA01		= $getData[0];
					//echo "$DATA01<br>";
					//echo "$getData[1] - $getData[2] - $getData[3] - $getData[10] - $getData[11]<br>";
					
					/*$sql = "INSERT INTO tbl_employee_new (EMP_ID, FIRST_NAME, ISPROJECT, DEPT_CODE, REG_CODE, GOL_CODE, EMP_STAT, GOL_KOEF)
							values ('" . $getData[1] . "','" . $getData[2] . "','" . $getData[4] . "','" . $getData[5] . "','" . $getData[6] . "','" . $getData[7] . "', '" . $getData[8] . "', $EMPG_KOEF)";*/
					$sql = "INSERT INTO tbl_employee_new1 (EMP_ID, FIRST_NAME, LAST_NAME, ISPROJECT, DEPT_CODE, REG_CODE, GOL_CODE, GOL_KOEF, EMP_STAT, Joint_Date, Email)
							values ('$EMP_ID','$FIRST_NAME', '$LAST_NAME', '$ISPROJECT','$DEPT_CODE','$REG_CODE','$GOL_CODE', '$EMPG_KOEF', '$EMP_STAT', '$Joint_Date', '$Email')";
					$this->db->query($sql);
				}
			}
			fclose($file);
			
			$UEMP_FNAME	= $filename;
			
			$updFN 		= "UPDATE tbl_upload_emp SET UEMP_STAT = 0";
			$this->db->query($updFN);
			
			$insFN 		= "INSERT INTO tbl_upload_emp(UEMP_DATE, UEMP_FNAME, UEMP_EMP, UEMP_STAT) values ('$UEMP_DATE', '$UEMP_FNAME', '$UEMP_EMP', 1)";
			$this->db->query($insFN);
			
			$up_stat	= 1;
		}
		
		$data['up_type'] 		= 'UPL';
		$data['UEMP_FNAME'] 	= $file_name;
		$data['up_stat'] 		= $up_stat;
		
		$data['title'] 			= $appName;
		$data['h2_title'] 		= 'Employee Data';
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		$data['isUpdDone_1'] 	= 1;
		
		$data["MenuCode"] 		= 'MN351';
		$this->load->view('v_hr/v_employee/employee_up', $data);
	}
	
	function sync_process() // USE
	{
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$UEMP_DATE		= date('Y-m-d H:i:s');
		
		$UEMP_FNAME		= $this->input->post('UEMP_FNAME');
		$UEMP_EMP		= $DefEmp_ID;
		
		$data['form_action']	= site_url('c_hr/c_employee/c_employee_up/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
		$data['form_actSync']	= site_url('c_hr/c_employee/c_employee_up/sync_process/?id='.$this->url_encryption_helper->encode_url($appName));
		
		$thisMonth		= date('m');
		$updEMP 		= "UPDATE tbl_employee SET Dept_Code = '', isRemun = 0, Emp_Status = 0, Employee_status = 0";
		$this->db->query($updEMP);
		// $updEMP 		= "UPDATE tbl_employee SET Dept_Code = '', isRemun = 0";
		// $this->db->query($updEMP);
			
		// DAPATKAN SEMUA NILAI PRESENTASI TIAP LEVEL
		$sql_1a			= "tbl_employee_new1";
		$res_1a			= $this->db->count_all($sql_1a);
		
		$ORGST_CODE_1b	= '';
		$POSS_ALLOW_1b	= 0;
		$TOTHEADER_1bax	= 0;
		$TOTKOEF_1bbx	= 0;
		$AMOUNT_1Finalx	= 0;
		if($res_1a > 0)
		{
			$noRow			= 0;
			$sql_1b			= "SELECT EMP_ID, FIRST_NAME, LAST_NAME, ISPROJECT, DEPT_CODE, GOL_CODE, EMP_STAT, GOL_KOEF, Joint_Date, Email FROM tbl_employee_new1";
			$res_1b			= $this->db->query($sql_1b)->result();
			foreach($res_1b as $row_1b):
				$noRow			= $noRow + 1;
				$konsIdenID		= "18012900";
				$konsTGab		= $konsIdenID.$noRow;
				$EMP_ID_1b		= $row_1b->EMP_ID;
				$FIRST_NAME_1b	= $row_1b->FIRST_NAME;
				$LAST_NAME_1b	= $row_1b->LAST_NAME;
				$ISPROJECT_1b	= $row_1b->ISPROJECT;
				$DEPT_CODE_1b	= $row_1b->DEPT_CODE;
				$GOL_CODE_1b	= $row_1b->GOL_CODE;
				$GOL_KOEF_1b	= $row_1b->GOL_KOEF;
				$EMP_STAT_1b	= $row_1b->EMP_STAT;
				$Joint_Date_1b	= $row_1b->Joint_Date;
				$Email_1b		= $row_1b->Email;
				$default_pass	= "123456";		
				$log_password	= md5($default_pass);
				
				$EPO			= "KTR";
				if($ISPROJECT_1b == 1)
					$EPO		= $DEPT_CODE_1b;
				
				$sqlEMPC		= "tbl_employee WHERE Emp_ID = '$EMP_ID_1b'";
				$CountEMPC		= $this->db->count_all($sqlEMPC);
				if($CountEMPC > 0)
				{
					$updEMP_1c 		= "UPDATE tbl_employee SET Dept_Code = '$DEPT_CODE_1b', Gol_Code = '$GOL_CODE_1b', isRemun = $EMP_STAT_1b,
											Emp_poss_office = '$EPO', Emp_Status = '$EMP_STAT_1b', Employee_Status = '$EMP_STAT_1b', 
											Joint_Date = '$Joint_Date_1b', Email = '$Email_1b', ENews = 1
										WHERE Emp_ID = '$EMP_ID_1b'";
					$this->db->query($updEMP_1c);
				}
				else
				{
					$insEMPNew 		= "INSERT INTO tbl_employee(Emp_ID, EmpNoIdentity, First_Name, Last_Name, Dept_Code, Gol_Code, Emp_Status, Employee_Status, isRemun,
										Emp_poss_office, FlagUSER, Email, Joint_Date, log_username, log_password, log_passHint, ENews) 
										values 
										('$EMP_ID_1b', '$konsTGab', '$FIRST_NAME_1b', '$LAST_NAME_1b', '$DEPT_CODE_1b', '$GOL_CODE_1b', '$EMP_STAT_1b', '$EMP_STAT_1b',
										 '$EMP_STAT_1b', '$EPO', '$DEPT_CODE_1b', '$Email_1b', '$Joint_Date_1b', '$EMP_ID_1b', '$log_password', '$DEPT_CODE_1b', '1')";
					$this->db->query($insEMPNew);
				}
				
				$sqlEmpProj	= "tbl_employee_proj WHERE Emp_ID = '$EMP_ID_1b'";
				$CountEProj	= $this->db->count_all($sqlEmpProj);
				if($CountEProj == 0)
				{
					$insEmpProj	= "INSERT INTO tbl_employee_proj(Emp_ID, proj_Code) values('$EMP_ID_1b', '$DEPT_CODE_1b')";
					$this->db->query($insEmpProj);
				}
								
			endforeach;
		}
				
		$updEMP_1c 		= "UPDATE tbl_upload_emp SET UEMP_DATEP = '$UEMP_DATE' WHERE UEMP_FNAME = '$UEMP_FNAME' AND UEMP_STAT = '1'";
		$this->db->query($updEMP_1c);
		
		$isProcDone_1	= 1;
				
		$data['up_type'] 		= "SYNC";
		$data['UEMP_FNAME'] 	= $UEMP_FNAME;
		$data['up_stat'] 		= "";
		
		$data['title'] 			= $appName;
		$data['h2_title'] 		= 'Employee Data';
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		$data['isUpdDone_1'] 	= 1;
		
		$data["MenuCode"] 		= 'MN351';
		$this->load->view('v_hr/v_employee/employee_up', $data);
	}
}