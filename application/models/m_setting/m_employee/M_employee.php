<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 Februari 2017
 * File Name	= M_employee.php
 * Location		= -
*/

class M_employee extends CI_Model
{
	function count_all_emp() // OK
	{
		$query = $this->db->get('tbl_employee')->num_rows();
   		return $query;
	}
	
	function count_all_emp_src($orlike) // OK
	{
		$this->db->or_like($orlike);
   		$query = $this->db->get('tbl_employee');
		return $query->num_rows();
	}
	
	function get_all_emp($batas = null,$offset = null, $key = null) // OK
	{
		//$this->db->from('tbl_employee');
		if ($key != null)
		{
		   $this->db->or_like($key);
		}
		if($batas != null)
		{
		   $this->db->limit($batas,$offset);
		}
		$query = $this->db->get('tbl_employee');
	 
		//if ($query->num_rows() > 0)
		//{
			return $query->result();
		//}
	}
	
	function count_all_num_rows($Emp_DeptCode, $DefEmp_ID) // USE
	{
		if($Emp_DeptCode == 1)
		{
			//return $this->db->count_all('tbl_employee');
			$sqlEMP		= "tbl_employee WHERE Emp_Status = 1";
			return $this->db->count_all($sqlEMP);
		}
		else
		{
			$sqlEMP		= "tbl_employee WHERE Emp_ID = '$DefEmp_ID' AND Emp_Status = 1";
			return $this->db->count_all($sqlEMP);
		}
	}
	
	function get_last_ten_employee($Emp_DeptCode, $DefEmp_ID) // USE
	{
		$FlagUSER 	= $this->session->userdata('FlagUSER');
		if($FlagUSER == 'SUPERADMIN')
		{
			$sql	= "SELECT Emp_ID,EmpNoIdentity,First_Name,Middle_Name,Last_Name,gender,Birth_Place,Date_Of_Birth,Mobile_Phone,Email,Religion,
							Marital_Status,Address1,city1,State1,zipcode1,log_username,log_passHint,log_password,Employee_status,writeEMP,editEMP,readEMP,
							Emp_Location, Gol_Code, pos_code
						FROM tbl_employee
						WHERE Emp_Status = 1
						ORDER BY First_Name ASC";
		}
		else
		{
			$sql	= "SELECT Emp_ID,EmpNoIdentity,First_Name,Middle_Name,Last_Name,gender,Birth_Place,Date_Of_Birth,Mobile_Phone,Email,Religion,
							Marital_Status,Address1,city1,State1,zipcode1,log_username,log_passHint,log_password,Employee_status,writeEMP,editEMP,readEMP,
							Emp_Location, Gol_Code, pos_code
						FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID' AND Emp_Status = 1 ORDER BY First_Name ASC";
		}
		return $this->db->query($sql);
	}
	
	function getCount_gol() // OK
	{
		//$this->db->where('POS_ISLAST', 1);
		return $this->db->count_all('tbl_employee_gol');
	}

	function get_gol() // OK
	{
		$sql = "SELECT * FROM tbl_employee_gol ORDER BY EMPG_CHILD ASC";
		return $this->db->query($sql);
	}
	
	function getCount_position() // OK
	{
		//$this->db->where('POS_ISLAST', 1);
		return $this->db->count_all('tbl_position_func');
	}

	function get_position() // OK
	{
		$sql = "SELECT A.POSF_CODE, A.POSF_NAME, A.POSF_DESC FROM tbl_position_func A
					INNER JOIN tbl_position_str B ON A.POSF_PARENT = B.POSS_CODE
					ORDER BY B.POSS_LEVIDX";
		return $this->db->query($sql);
	}
	
	function getCount_ACT() // USE
	{
		$sqlTotACT	= "tbl_employee WHERE Emp_Status = '1'";
		return $this->db->count_all($sqlTotACT);
	}
	
	function getCount_NACT() // USE
	{
		$sqlTotNACT	= "tbl_employee WHERE Emp_Status = '0'";
		return $this->db->count_all($sqlTotNACT);
	}
	
	function getCount_NEW() // USE
	{
		$MonthAct		= (int)date('m');
		$sqlTotNEW	= "tbl_employee WHERE MONTH(Joint_Date) = $MonthAct";
		return $this->db->count_all($sqlTotNEW);
	}
	
	function getCount_BOD() // USE
	{
		$sqlTotBOD	= "tbl_employee A
						INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
						WHERE B.POSF_LEVC = 'BOD'";
		return $this->db->count_all($sqlTotBOD);
	}
	
	function getCount_GM() // USE
	{
		$sqlTotGM	= "tbl_employee A
						INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
						WHERE B.POSF_LEVC = 'GM'";
		return $this->db->count_all($sqlTotGM);
	}
	
	function getCount_MNG() // USE
	{
		$sqlTotMNG	= "tbl_employee A
						INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
						WHERE B.POSF_LEVC = 'MNG'";
		return $this->db->count_all($sqlTotMNG);
	}
	
	function getCount_KEPU() // USE
	{
		$sqlTotKEPU	= "tbl_employee A
						INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
						WHERE B.POSF_LEVC = 'KEPU'";
		return $this->db->count_all($sqlTotKEPU);
	}
	
	function getCount_PM() // USE
	{
		$sqlTotPM	= "tbl_employee A
						INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
						WHERE B.POSF_LEVC = 'PM'";
		return $this->db->count_all($sqlTotPM);
	}
	
	function getCount_KU() // USE
	{
		$sqlTotKU	= "tbl_employee A
						INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
						WHERE B.POSF_LEVC = 'KU'";
		return $this->db->count_all($sqlTotKU);
	}
	
	function getCount_SM() // USE
	{
		$sqlTotSM	= "tbl_employee A
						INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
						WHERE B.POSF_LEVC = 'SM'";
		return $this->db->count_all($sqlTotSM);
	}
	
	function getCount_SPEC() // USE
	{
		$sqlTotSPEC	= "tbl_employee A
						INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
						WHERE B.POSF_LEVC = 'SPEC'";
		return $this->db->count_all($sqlTotSPEC);
	}
	
	function getCount_STF() // USE
	{
		$sqlTotSTF	= "tbl_employee A
						INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
						WHERE B.POSF_LEVC = 'STF'";
		return $this->db->count_all($sqlTotSTF);
	}
	
	function getCount_NSTF() // USE
	{
		$sqlTotNSTF	= "tbl_employee A
						INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
						WHERE B.POSF_LEVC = 'NSTF'";
		return $this->db->count_all($sqlTotNSTF);
	}
	
	function add($employee) // USE
	{
		$this->db->insert('tbl_employee', $employee);
	}
	
	function add2($employeeCp) // USE
	{
		$this->db->insert('others', $employeeCp);
	}
	
	function add3($employeeImg) // USE
	{
		$this->db->insert('tbl_employee_img', $employeeImg);
	}
	
	function updateDash() // USE
	{
		// GET TOTAL
		$sqlTotAct		= "tbl_employee WHERE Emp_Status = '1'";
		$resTotAct 		= $this->db->count_all($sqlTotAct);
		
		$sqlTotNAct		= "tbl_employee WHERE Emp_Status = '0'";
		$resTotNAct 	= $this->db->count_all($sqlTotNAct);
		
		$resTotEmp		= $resTotAct + $resTotNAct;
		
		$MonthAct		= (int)date('m');
		$sqlTotNew		= "tbl_employee WHERE MONTH(Joint_Date) = $MonthAct AND Emp_Status = '1'";
		$TotNew 		= $this->db->count_all($sqlTotNew);
		
		$sqlTotBOD		= "tbl_employee A
							INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
							WHERE B.POSF_LEVC = 'BOD' AND A.Emp_Status = '1'";
		$TotBOD 		= $this->db->count_all($sqlTotBOD);
		
		$sqlTotGM		= "tbl_employee A
							INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
							WHERE B.POSF_LEVC = 'GM' AND A.Emp_Status = '1'";
		$TotGM 			= $this->db->count_all($sqlTotGM);
		
		$sqlTotMNG		= "tbl_employee A
							INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
							WHERE B.POSF_LEVC = 'MNG' AND A.Emp_Status = '1'";
		$TotMNG 		= $this->db->count_all($sqlTotMNG);
		
		$sqlTotKEPU		= "tbl_employee A
							INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
							WHERE B.POSF_LEVC = 'KEPU' AND A.Emp_Status = '1'";
		$TotKEPU 		= $this->db->count_all($sqlTotKEPU);
		
		$sqlTotPM		= "tbl_employee A
							INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
							WHERE B.POSF_LEVC = 'PM' AND A.Emp_Status = '1'";
		$TotPM 			= $this->db->count_all($sqlTotPM);
		
		$sqlTotKU		= "tbl_employee A
							INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
							WHERE B.POSF_LEVC = 'KU' AND A.Emp_Status = '1'";
		$TotKU 			= $this->db->count_all($sqlTotKU);
		
		$sqlTotSM		= "tbl_employee A
							INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
							WHERE B.POSF_LEVC = 'SM' AND A.Emp_Status = '1'";
		$TotSM 			= $this->db->count_all($sqlTotSM);
		
		$sqlTotSPEC		= "tbl_employee A
							INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
							WHERE B.POSF_LEVC = 'SPEC' AND A.Emp_Status = '1'";
		$TotSPEC 		= $this->db->count_all($sqlTotSPEC);
		
		$sqlTotSTF		= "tbl_employee A
							INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
							WHERE B.POSF_LEVC = 'STF' AND A.Emp_Status = '1'";
		$TotSTF 		= $this->db->count_all($sqlTotSTF);
		
		$sqlTotNSTF		= "tbl_employee A
							INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
							WHERE B.POSF_LEVC = 'NSTF' AND A.Emp_Status = '1'";
		$TotNSTF 		= $this->db->count_all($sqlTotNSTF);
		
		$sqlTot30		= "tbl_employee WHERE Age BETWEEN '0' AND '30' AND Emp_Status = '1'";
		$Tot30 			= $this->db->count_all($sqlTot30);
		
		$sqlTot40		= "tbl_employee WHERE Age BETWEEN '31' AND '50' AND Emp_Status = '1'";
		$Tot40 			= $this->db->count_all($sqlTot40);
		
		$sqlTot50		= "tbl_employee WHERE Age BETWEEN '51' AND '90' AND Emp_Status = '1'";
		$Tot50 			= $this->db->count_all($sqlTot50);
		
		$sqlCount		= "tbl_dash_hr";
		$resCount		= $this->db->count_all($sqlCount);
		
		$sqlTotMale		= "tbl_employee WHERE gender = 'male' AND Emp_Status = '1'";
		$resTotMale		= $this->db->count_all($sqlTotMale);
		
		$sqlTotFMale	= "tbl_employee WHERE gender = 'female' AND Emp_Status = '1'";
		$resTotFMale 	= $this->db->count_all($sqlTotFMale);
		
		if($resCount == 0)
		{
			$UpdateDash	= "INSERT INTO tbl_dash_hr (DHR_TOTEMP,DHR_TOTACT,DHR_TOTNACT,DHR_TOTM,DHR_TOTF,
							DHR_TOTNEW,DHR_TOT30,DHR_TOT40,DHR_TOT50,DHR_TOTBOD,
							DHR_TOTGM,DHR_TOTMNG,DHR_TOTKEPU,DHR_TOTPM,DHR_TOTKU,DHR_TOTSM,DHR_TOTSPEC,DHR_TOTSTF,DHR_TOTNSTF) VALUES
							($resTotEmp,$resTotAct,$resTotNAct,$TotNew,$resTotMale,$resTotFMale,
							$Tot30,$Tot40,$Tot50,$TotBOD,$TotGM,$TotMNG,$TotKEPU, $TotPM, $TotKU, 
							$TotSM,$TotSPEC, $TotSTF, $TotNSTF)";
		}
		else
		{
			$UpdateDash	= "UPDATE tbl_dash_hr SET DHR_TOTEMP = '$resTotEmp', DHR_TOTACT = '$resTotAct', DHR_TOTNACT = '$resTotNAct', 
							DHR_TOTM = '$resTotMale', DHR_TOTF = '$resTotFMale',
							DHR_TOTNEW = '$TotNew', DHR_TOT30 = '$Tot30', DHR_TOT40 = '$Tot40', DHR_TOT50 = '$Tot50', 
							DHR_TOTBOD = '$TotBOD', DHR_TOTGM = '$TotGM', DHR_TOTMNG = '$TotMNG', DHR_TOTKEPU = '$TotKEPU', 
							DHR_TOTPM = '$TotPM', DHR_TOTKU = '$TotKU', DHR_TOTSM = '$TotSM',DHR_TOTSPEC = '$TotSPEC', 
							DHR_TOTSTF = '$TotSTF', DHR_TOTNSTF = '$TotNSTF'";
		}
		$this->db->query($UpdateDash);
	}
	
	function updateProfPict($Emp_ID, $nameFile, $fileInpName) // U
	{
		$UpdateProfPict	= "UPDATE tbl_employee_img SET imgemp_filename = '$fileInpName', imgemp_filenameX = '$nameFile' WHERE imgemp_empid = '$Emp_ID'";
		$this->db->query($UpdateProfPict);
	}
	
	function getDataDocPat($MenuCode) // U
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function get_allmenu() // U
	{		
		$sql = "SELECT * FROM tbl_menu
				WHERE level_menu = 1
				ORDER BY no_urut";
		return $this->db->query($sql);
	}
	
	function deleteAuthEmp($Emp_ID) // U
	{
		$this->db->where('Emp_ID', $Emp_ID);
		$this->db->delete('tusermenu');
	}
	
	function get_allproject() // U
	{		
		$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project ORDER BY PRJCODE";
		return $this->db->query($sql);
	}
	
	function update($Emp_ID, $employee) // U
	{
		$this->db->where('Emp_ID', $Emp_ID);
		$this->db->update('tbl_employee', $employee);
	}
	
	function update2($Emp_ID, $employeeCp) // U
	{
		$this->db->where('NK', $Emp_ID);
		$this->db->update('others', $employeeCp);
	}
	
	function deleteEmpProjEmp($Emp_ID) // U
	{
		$this->db->where('Emp_ID', $Emp_ID);
		$this->db->delete('tbl_employee_proj');
	}
	
	function addEmpProj($employeeProj) // U
	{
		$this->db->insert('tbl_employee_proj', $employeeProj);
	}
	
	function get_purreq_by_code($Emp_ID) // HOLD
	{
		$sql	= "SELECT Emp_ID, POS_CODE, EmpNoIdentity, First_Name, Middle_Name, Last_Name, Birth_Place, Date_Of_Birth, gender, Religion, Marital_Status, Mobile_Phone, 
					Address1, Email, Emp_Location, Employee_status, FlagUSER, Emp_DeptCode, log_username, log_passHint, log_password, writeEMP, editEMP, readEMP,
					city1, State1, zipcode1
					FROM tbl_employee WHERE Emp_ID = '$Emp_ID'";
		return $this->db->query($sql);
	}
	
	function get_alldashboard() // U
	{		
		$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project ORDER BY PRJCODE";
		return $this->db->query($sql);
	}
	
	function deleteEmpDashEmp($Emp_ID) // U
	{
		$this->db->where('EMP_ID', $Emp_ID);
		$this->db->delete('tbl_dash_sett_emp');
	}
	
	function addEmpDash($employeeDash) // U
	{
		$this->db->insert('tbl_dash_sett_emp', $employeeDash);
	}
	
	function deleteEmpDashEmpHR($Emp_ID) // U
	{
		$this->db->where('EMP_ID', $Emp_ID);
		$this->db->delete('tbl_dash_sett_hr_emp');
	}
	
	function addEmpDashHR($employeeDash) // U
	{
		$this->db->insert('tbl_dash_sett_hr_emp', $employeeDash);
	}
	
	function get_all_doctype_list() // U
	{		
		$sql = "SELECT doc_ID, doc_code, doc_level, doc_parent, doc_name
				FROM tbl_document
				WHERE isHRD = '1' 
					AND doc_level = 2
				ORDER BY doc_code";
		return $this->db->query($sql);
	}
	
	function deleteAccDocEmp($Emp_ID) // U
	{
		$this->db->where('Emp_ID', $Emp_ID);
		$this->db->delete('tbl_userdoctype');
	}
	
	function deleteAuthDocEmp($Emp_ID) // U
	{
		$this->db->where('DAU_EMPID', $Emp_ID);
		$this->db->delete('tbl_employee_docauth');
	}
	
	function addEMPAUTH($INSAUTDOC) // USE
	{
		$this->db->insert('tbl_employee_docauth', $INSAUTDOC);
	}
	
	function sendMail($Emp_ID, $compName, $theUsername, $thePassword, $EMAIL) // USE
	{
		$toMail		= ''.$EMAIL.'';
		$headers 	= 'MIME-Version: 1.0' . "\r\n";
		$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
		$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
		$subject 	= "User Reqistration";
		$output		= '';
		$output		.= '<table width="100%" border="0">
							<tr>
								<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3">Dear <strong>'.$compName.'</strong>,</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3">Assalamu \'alaikum wr.wb.</td>
							</tr>
							<tr>
								<td colspan="3" style="vertical-align:top">Kami sudah meregistrasikan NIK Anda ke dalam NKE Smart System (NSS).</td>
							</tr>
  <tr>
								<td colspan="3" style="vertical-align:top">Berikut informasi singkat yang kami sediakan :</td>
							</tr>
							<tr>
								<td width="2%" style="vertical-align:top">&nbsp;</td>
								<td width="9%">URL</td>
								<td width="89%">: www.nke.co.id</td>
							</tr>
							<tr>
								<td width="2%" style="vertical-align:top">&nbsp;</td>
								<td width="9%">username</td>
								<td width="89%">: '.$theUsername.'</td>
							</tr>
							<tr>
								<td width="2%" style="vertical-align:top">&nbsp;</td>
								<td width="9%">password</td>
								<td width="89%">: '.$thePassword.'</td>
							</tr>
							<tr>
								<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan. Mohon untuk tidak memberitahukan informasi ini kepada siapapun.</td>
							</tr>
							<tr>
								<td colspan="3" style="vertical-align:top">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3" style="vertical-align:top">Kami sarankan agar segera ubah password Anda dengan cara:</td>
							</tr>
							<tr>
								<td style="vertical-align:top">&nbsp;</td>
								<td colspan="2">1. Klik foto Anda di sebelah pojok kanan atas</td>
							</tr>
							<tr>
								<td style="vertical-align:top">&nbsp;</td>
								<td colspan="2">2. Pilih Tab <strong>Profile</strong></td>
							</tr>
							<tr>
								<td style="vertical-align:top">&nbsp;</td>
								<td colspan="2">3. Pilih Tab <strong>Setting</strong></td>
							</tr>
							<tr>
								<td style="vertical-align:top">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td style="vertical-align:top">NB:</td>
								<td colspan="2"><em>Apabila ada pertanyaan, silahkan hubungi kami dengan membuat Task Request melalui Menu Pertolongan yang telah kami sediakan.</em></td>
							</tr>
							<tr>
								<td style="vertical-align:top">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3" style="vertical-align:top">Hormat kami,</td>
							</tr>
							<tr>
								<td colspan="3" style="vertical-align:top">NSS Developer Team</td>
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
	}
}
?>