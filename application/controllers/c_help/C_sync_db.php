<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 Desember 2015
 * File Name	= c_sync_db.php
 * Function		= -
 * Location		= -
*/

class C_sync_db extends CI_Controller
{
 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_help/c_sync_db/sync_db_idx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function sync_db_idx()
	{
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['h1_title']		= 'Sync';
			$data['h2_title']		= 'Sync';
			$data['h3_title']		= 'Database';
			
			//return false;
			// UPDATE tbl_employee
				/*$IMP_N		= "SELECT KOLOM0, KOLOM1, KOLOM2, KOLOM21, KOLOM22, KOLOM3, KOLOM4, KOLOM5, KOLOM6, KOLOM7
									FROM tbl_import";
				$ResIMP_N	= $this->db->query($IMP_N)->result();
				$theRow		= 0;
				foreach($ResIMP_N as $rowIMP_N) :
					$theRow	= $theRow + 1;
					$KOLOM1	= $rowIMP_N->KOLOM1;	// EMP_ID
					$KOLOM7	= $rowIMP_N->KOLOM7;	// ID NO
					
					$IMP_CN		= "tbl_employee WHERE Emp_ID = '$KOLOM1'";
					$ResIMP_CN	= $this->db->count_all($IMP_CN);
					if($ResIMP_CN > 0)
					{
						$UPD_IMP_N	= "UPDATE tbl_employee SET EmpNoIdentity = '$KOLOM7' WHERE Emp_ID = '$KOLOM1'";
						$this->db->query($UPD_IMP_N);
						echo "$theRow = update<br>";
					}
					else
					{
						echo "$theRow = input<br>";
						$KOLOM2		= $rowIMP_N->KOLOM2;	// NAMA AWAL
						$KOLOM21	= $rowIMP_N->KOLOM21;	// NAMA TENGAH
						$KOLOM22	= $rowIMP_N->KOLOM22;	// NAMA AKHIR
						if($KOLOM21 == '' && $KOLOM22 == '')
							$KOLOM23= "";					// NAMA AKHIR
						elseif($KOLOM21 != '' && $KOLOM22 == '')
							$KOLOM23= "$KOLOM21";			// NAMA AKHIR
						elseif($KOLOM21 != '' && $KOLOM22 != '')
							$KOLOM23= "$KOLOM21 $KOLOM22";	// NAMA AKHIR
							
						$KOLOM3		= $rowIMP_N->KOLOM3;	// PROYEK
						$KOLOM4		= $rowIMP_N->KOLOM4;	// DIVISI
						$KOLOM5		= $rowIMP_N->KOLOM5;	// TMP. LAHIR
						$KOLOM6		= $rowIMP_N->KOLOM6;	// TGL. LAHIR
						// INSERT NEW
						$INS_IMP_N	= "INSERT INTO tbl_employee
										(Emp_ID, EmpNoIdentity, Position_ID, First_Name, Last_Name, gender, Birth_Place, Email, Religion, Emp_DeptCode, log_username, log_passHint, log_password, FlagUSER, Employee_status, Emp_Location, writeEMP, editEMP, readEMP, new_stat) 
										VALUES ('$KOLOM1', '$KOLOM7', 3, '$KOLOM2', '$KOLOM23', 'male', '$KOLOM5', 'mail.sample@nusakonstruksi.com', 'Islam', 6, '$KOLOM1', 'GENUSR', 'c33367701511b4f6020ec61ded352059', 'APPUSR', 1, 1, 0, 0, 1, 1)";
						$this->db->query($INS_IMP_N);
						
						// INSERT EMP PROJ
						$INS_IMP_O	= "INSERT INTO tbl_employee_proj (Emp_ID, proj_Code) VALUES ('$KOLOM1', 'KTR')";
						$this->db->query($INS_IMP_O);
						
						// INSERT OTHER
						$INS_IMP_O	= "INSERT INTO others (NK, U, P) VALUES ('$KOLOM1', '$KOLOM1', '654321')";
						$this->db->query($INS_IMP_O);
						
						// INSERT MENU
						$INS_IMP_O	= "INSERT INTO tusermenu (isChkDetail, emp_id, menu_code) VALUES (261, '$KOLOM1', 'MN268')";
						$this->db->query($INS_IMP_O);	
					}
				endforeach;*/
			//$this->load->view('v_help/v_task_request/task_request', $data);
			
			// UPDATE tbl_others
				/*$TBL_A		= "SELECT Emp_ID, Emp_Notes FROM tbl_employee WHERE Emp_Notes != ''";
				$ResTBL_A	= $this->db->query($TBL_A)->result();
				$theRow		= 0;
				foreach($ResTBL_A as $rowTBL_A) :
					$Emp_ID		= $rowTBL_A->Emp_ID;	// EMP_ID
					$Emp_Notes	= $rowTBL_A->Emp_Notes;	// EMP_ID
					$UPD_TBL_B	= "UPDATE others SET NK = '$Emp_ID' WHERE NK = '$Emp_Notes'";
					$this->db->query($UPD_TBL_B);
					echo "$theRow = update<br>";
				endforeach;*/
			
			// UPDATE tbl_employee_proj
				/*$TBL_A		= "SELECT Emp_ID, Emp_Notes FROM tbl_employee WHERE Emp_Notes != ''";
				$ResTBL_A	= $this->db->query($TBL_A)->result();
				$theRow		= 0;
				foreach($ResTBL_A as $rowTBL_A) :
					$Emp_ID		= $rowTBL_A->Emp_ID;	// EMP_ID
					$Emp_Notes	= $rowTBL_A->Emp_Notes;	// EMP_ID
					$UPD_TBL_B	= "UPDATE tbl_employee_proj SET Emp_ID = '$Emp_ID' WHERE Emp_ID = '$Emp_Notes'";
					$this->db->query($UPD_TBL_B);
					echo "$theRow = update<br>";
				endforeach;*/
			
			// UPDATE tbl_employee_proj
				/*$TBL_A		= "SELECT Emp_ID, Emp_Notes FROM tbl_employee WHERE Emp_Notes != ''";
				$ResTBL_A	= $this->db->query($TBL_A)->result();
				$theRow		= 0;
				foreach($ResTBL_A as $rowTBL_A) :
					$Emp_ID		= $rowTBL_A->Emp_ID;	// EMP_ID
					$Emp_Notes	= $rowTBL_A->Emp_Notes;	// EMP_ID
					$UPD_TBL_B	= "UPDATE tusermenu SET emp_id = '$Emp_ID' WHERE emp_id = '$Emp_Notes'";
					$this->db->query($UPD_TBL_B);
					echo "$theRow = update<br>";
				endforeach;*/
			
			// UPDATE tbl_employee username
				/*$TBL_A		= "SELECT Emp_ID, log_username FROM tbl_employee WHERE log_username = ''";
				$ResTBL_A	= $this->db->query($TBL_A)->result();
				$theRow		= 0;
				foreach($ResTBL_A as $rowTBL_A) :
					$theRow			= $theRow + 1;
					$Emp_ID1		= $rowTBL_A->Emp_ID;		// EMP_ID
					$log_username1	= $rowTBL_A->log_username;	// EMP_ID
					$UPD_TBL_B	= "UPDATE tbl_employee SET log_username = '$Emp_ID1' WHERE Emp_ID = '$Emp_ID1'";
					$this->db->query($UPD_TBL_B);
					echo "$theRow = update<br>";
				endforeach;*/
			
			// UPDATE tbl_employee password
				/*$TBL_A		= "SELECT KOLOM1, KOLOM3, KOLOM4, KOLOM5 FROM tbl_import";
				$ResTBL_A	= $this->db->query($TBL_A)->result();
				$theRow		= 0;
				$KOLOM3		= 0;
				$KOLOM4		= 0;
				$KOLOM5		= 0;
				foreach($ResTBL_A as $rowTBL_A) :
					$theRow		= $theRow + 1;
					$KOLOM1		= $rowTBL_A->KOLOM1;	// EMP_ID
					$KOLOM3		= $rowTBL_A->KOLOM3;	// EMP_ID
					if($KOLOM3 == '')
						$KOLOM3	= 0;
					$KOLOM4		= $rowTBL_A->KOLOM4;	// EMP_ID
					if($KOLOM4 == '')
						$KOLOM4	= 0;
					$KOLOM5		= $rowTBL_A->KOLOM5;	// EMP_ID
					if($KOLOM5 == '')
						$KOLOM5	= 0;
					$UPD_TBL_B	= "UPDATE tbl_employee_docauth 
										SET DAU_WRITE 	= '$KOLOM3',
										DAU_READ 	= '$KOLOM4',
										DAU_DL 		= '$KOLOM5'
									WHERE DAU_EMPID = '$KOLOM1'";
					$this->db->query($UPD_TBL_B);
					echo "$theRow = update<br>";
				endforeach;*/
			
			// UPDATE tbl_employee_docauth
				/*$TBL_A		= "SELECT Emp_ID FROM tbl_employee";
				$ResTBL_A	= $this->db->query($TBL_A)->result();
				$theRow		= 0;
				$KOLOM3		= 0;
				$KOLOM4		= 0;
				$KOLOM5		= 0;
				foreach($ResTBL_A as $rowTBL_A) :
					$theRow		= $theRow + 1;
					$Emp_ID		= $rowTBL_A->Emp_ID;	// EMP_ID
					$TBL_A		= "tbl_import WHERE KOLOM1 = '$Emp_ID'";
					$ResIMP_CN	= $this->db->count_all($TBL_A);
					echo "ResIMP_CN = $ResIMP_CN = > ";
					if($ResIMP_CN == 0)
					{
						$UPD_IMP_N	= "UPDATE tbl_employee SET log_password = 'e10adc3949ba59abbe56e057f20f883e'
										WHERE Emp_ID = '$Emp_ID'";
						$this->db->query($UPD_IMP_N);
						
						$UPD_IMP_N1	= "UPDATE others SET P = '123456'
										WHERE NK = '$Emp_ID'";
						$this->db->query($UPD_IMP_N1);
						echo "$theRow = update<br>";
					}
					else
					{
						//$this->db->query($UPD_TBL_B);
						echo "$theRow = No Update<br>";
					}
				endforeach;*/
			
			// UPDATE TBL_DOCUMENT 1
				/*$TBL_A		= "SELECT * FROM tbl_sementara";
				$ResTBL_A	= $this->db->query($TBL_A)->result();
				$theRow		= 0;
				$KOLOM3		= 0;
				$KOLOM4		= 0;
				$KOLOM5		= 0;
				foreach($ResTBL_A as $rowTBL_A) :
					$theRow		= $theRow + 1;
					$doc_code	= $rowTBL_A->doc_code;	// EMP_ID
					$TBL_A		= "tbl_document WHERE doc_code = '$doc_code'";
					$ResIMP_CN	= $this->db->count_all($TBL_A);
					echo "ResIMP_CN = $ResIMP_CN => ";
					if($ResIMP_CN > 0)
					{
						$UPD_IMP_N	= "UPDATE tbl_document SET isShow = '0'
										WHERE doc_code = '$doc_code'";
						$this->db->query($UPD_IMP_N);
						echo "$theRow = update<br>";
					}
					else
					{
						//$this->db->query($UPD_TBL_B);
						echo "$theRow = No Update<br>";
					}
				endforeach;*/
			
			// UPDATE TBL_DOCUMENT 1
				/*$TBL_A		= "SELECT * FROM tbl_sementara";
				$ResTBL_A	= $this->db->query($TBL_A)->result();
				$theRow		= 0;
				$KOLOM3		= 0;
				$KOLOM4		= 0;
				$KOLOM5		= 0;
				foreach($ResTBL_A as $rowTBL_A) :
					$theRow		= $theRow + 1;
					$doc_code	= $rowTBL_A->doc_code;	// EMP_ID
					$TBL_A		= "tbl_document WHERE doc_parent = '$doc_code'";
					$ResIMP_CN	= $this->db->count_all($TBL_A);
					echo "ResIMP_CN = $ResIMP_CN => ";
					if($ResIMP_CN > 0)
					{
						$UPD_IMP_N	= "UPDATE tbl_document SET isShow = '0'
										WHERE doc_parent = '$doc_code'";
						$this->db->query($UPD_IMP_N);
						echo "$theRow = update<br>";
					}
					else
					{
						//$this->db->query($UPD_TBL_B);
						echo "$theRow = No Update<br>";
					}
				endforeach;*/
					
			$this->load->view('v_help/v_sync_db/sync_db', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
}
?>