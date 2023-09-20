<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Februari 2017
 * File Name	= m_project_mcg.php
 * Notes		= -
*/

class M_project_mcg extends CI_Model
{
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_mcg_header A
					LEFT JOIN tbl_employee B ON A.MCH_EMPID = B.Emp_ID
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.MCH_MANNO LIKE '%$search%' ESCAPE '!' OR A.MCH_NOTES LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.MCH_CODE, A.MCH_MANNO, A.MCH_DATE, A.MCH_ENDDATE, A.MCH_STEP, A.PRJCODE, A.MCH_PROG, A.MCH_PROGVAL, A.MCH_NOTES,
							A.MCH_STAT, A.MCH_CREATER, A.MCH_ISINV
						FROM tbl_mcg_header A
							LEFT JOIN tbl_employee B ON A.MCH_EMPID = B.Emp_ID
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.MCH_MANNO LIKE '%$search%' ESCAPE '!' OR A.MCH_NOTES LIKE '%$search%' ESCAPE '!') ORDER BY A.MCH_DATE, $order $dir";
			}
			else
			{
				$sql = "SELECT A.MCH_CODE, A.MCH_MANNO, A.MCH_DATE, A.MCH_ENDDATE, A.MCH_STEP, A.PRJCODE, A.MCH_PROG, A.MCH_PROGVAL, A.MCH_NOTES,
							A.MCH_STAT, A.MCH_CREATER, A.MCH_ISINV
						FROM tbl_mcg_header A
							LEFT JOIN tbl_employee B ON A.MCH_EMPID = B.Emp_ID
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.MCH_MANNO LIKE '%$search%' ESCAPE '!' OR A.MCH_NOTES LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.MCH_CODE, A.MCH_MANNO, A.MCH_DATE, A.MCH_ENDDATE, A.MCH_STEP, A.PRJCODE, A.MCH_PROG, A.MCH_PROGVAL, A.MCH_NOTES,
							A.MCH_STAT, A.MCH_CREATER, A.MCH_ISINV
						FROM tbl_mcg_header A
							LEFT JOIN tbl_employee B ON A.MCH_EMPID = B.Emp_ID
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.MCH_MANNO LIKE '%$search%' ESCAPE '!' OR A.MCH_NOTES LIKE '%$search%' ESCAPE '!') ORDER BY A.MCH_DATE, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.MCH_CODE, A.MCH_MANNO, A.MCH_DATE, A.MCH_ENDDATE, A.MCH_STEP, A.PRJCODE, A.MCH_PROG, A.MCH_PROGVAL, A.MCH_NOTES,
							A.MCH_STAT, A.MCH_CREATER, A.MCH_ISINV
						FROM tbl_mcg_header A
							LEFT JOIN tbl_employee B ON A.MCH_EMPID = B.Emp_ID
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.MCH_MANNO LIKE '%$search%' ESCAPE '!' OR A.MCH_NOTES LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function count_all_num_rowsProjMC($PRJCODE) // G
	{
		$sql	= "tbl_mcg_header A
					LEFT JOIN  	tbl_employee B ON A.MCH_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_projmc($PRJCODE) // G
	{
		$sql = "SELECT A.*, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_Number, C.PRJCODE, C.PRJNAME
				FROM tbl_mcg_header A
					LEFT JOIN  	tbl_employee B ON A.MCH_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
				WHERE A.PRJCODE = '$PRJCODE' 
				ORDER BY A.MCH_DATE ASC";
		return $this->db->query($sql);
	}
	
	function count_all_MC($PRJCODE) // G
	{
		$sql	= "tbl_mcheader A
					LEFT JOIN  	tbl_employee B ON A.MC_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.MC_ISGROUP = 0 AND A.MC_STAT = 3";
		return $this->db->count_all($sql);
	}
	
	function view_all_MC($PRJCODE) // G
	{
		$sql = "SELECT A.*, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_Number, C.PRJCODE, C.PRJNAME
				FROM tbl_mcheader A
					LEFT JOIN  	tbl_employee B ON A.MC_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
				WHERE A.PRJCODE = '$PRJCODE' AND A.MC_ISGROUP = 0 AND A.MC_STAT = 3
				ORDER BY A.MC_DATE ASC";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // G
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function add($dataMCH, $PRJCODE) // G
	{
		$this->db->insert('tbl_mcg_header', $dataMCH);
	}
	
	function get_MC_by_number($MCH_CODE) // G
	{
		$sql = "SELECT * FROM tbl_mcg_header
				WHERE MCH_CODE = '$MCH_CODE'";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsProject() // G
	{
		return $this->db->count_all('tbl_project');
	}
	
	function viewProject() // G
	{
		$sql = "SELECT proj_Number, PRJCODE, PRJNAME
				FROM tbl_project
				WHERE PRJSTAT = 1
				ORDER BY PRJCODE ASC";
		return $this->db->query($sql);
	}
	
	function update($MCH_CODE, $dataMCH, $PRJCODE) // G
	{
		$this->db->where('MCH_CODE', $MCH_CODE);
		$this->db->update('tbl_mcg_header', $dataMCH);
	}
	
	function deleteMCGDet($MCH_CODE) // G
	{
		$this->db->where('MCH_CODE', $MCH_CODE);
		$this->db->delete('tbl_mcg_detail');
	}
	
	function updateMC($MC_CODE, $dataMCX, $PRJCODE) // G
	{
		$this->db->where('MC_CODE', $MC_CODE);
		$this->db->update('tbl_mcheader', $dataMCX);
	}
	
	function addMCM($dataMCM, $PRJCODE) // OK
	{
		$MCP_CODE 		= $dataMCM['MCP_CODE'];
		$MCP_PRJCODE 	= $dataMCM['MCP_PRJCODE'];
		$MCP_DATE 		= $dataMCM['MCP_DATE'];
		$MCP_PROG 		= $dataMCM['MCP_PROG'];
		$MCP_PROGVAL 	= $dataMCM['MCP_PROGVAL'];
		$MCP_RETCUT		= $dataMCM['MCP_RETCUT'];
		$MCP_NEXTVAL	= $dataMCM['MCP_NEXTVAL'];
		$MCP_BEFVAL		= $dataMCM['MCP_BEFVAL'];
		$MCP_PROGAPP	= $dataMCM['MCP_PROGAPP'];
		$MCP_PROGAPPVAL	= $dataMCM['MCP_PROGAPPVAL'];
		$MCP_STATUS		= $dataMCM['MCP_STATUS'];
		
		// GET PRJECT DETAIL	
			$PRJCOST 	= 0;
			$PRJDATE 	= date('Y-m-d');
			$PRJEDAT 	= date('Y-m-d');
			$sqlPRJ	= "SELECT PRJNAME, PRJCOST, PRJDATE, PRJEDAT FROM tbl_project WHERE PRJCODE = '$MCP_PRJCODE'";
			$resPRJ	= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJNAME 	= $rowPRJ->PRJNAME;
				$PRJCOST 	= $rowPRJ->PRJCOST;
				$PRJDATE 	= $rowPRJ->PRJDATE;
				$PRJEDAT 	= $rowPRJ->PRJEDAT;
			endforeach;
		
		$sq1l			= "tbl_mc_plan WHERE MCP_CODE = '$MCP_CODE' AND MCP_PRJCODE = '$MCP_PRJCODE'";
		$res1			= $this->db->count_all($sq1l);
		if($res1 == 0)
		{				
			// SAVE TO CLAIM MONITORING
				$insCM	= "INSERT INTO tbl_mc_plan (MCP_CODE, MCP_PRJCODE, MCP_PRJDATE1, MCP_PRJDATE2, MCP_PRJCOST, MCP_DATE, MCP_PROG, MCP_PROGVAL, MCP_RETCUT, 
								MCP_NEXTVAL, MCP_BEFVAL, MCP_PROGAPP, MCP_PROGAPPVAL, MCP_STATUS)
							VALUES ('$MCP_CODE', '$MCP_PRJCODE', '$PRJDATE', '$PRJEDAT', $PRJCOST, '$MCP_DATE', $MCP_PROG, $MCP_PROGVAL, $MCP_RETCUT, $MCP_NEXTVAL, 
								$MCP_BEFVAL, $MCP_PROGAPP, $MCP_PROGAPPVAL, $MCP_STATUS)";
				$this->db->query($insCM);
		}
		else
		{
			// SAVE TO CLAIM MONITORING
				$sqlMC	= "UPDATE tbl_mc_plan SET MCP_CODE = '$MCP_CODE', MCP_PRJCODE = '$MCP_PRJCODE', MCP_PRJDATE1 = '$PRJDATE', MCP_PRJDATE2 = '$PRJEDAT',
									MCP_PRJCOST = $PRJCOST, MCP_DATE = '$MCP_DATE', MCP_PROG = $MCP_PROG, MCP_PROGVAL = $MCP_PROGVAL, MCP_RETCUT = $MCP_RETCUT, 
									MCP_NEXTVAL = $MCP_NEXTVAL, MCP_BEFVAL = $MCP_BEFVAL, MCP_PROGAPP = $MCP_PROGAPP, MCP_PROGAPPVAL =$MCP_PROGAPPVAL,
									MCP_STATUS = $MCP_STATUS
							WHERE MCP_CODE = '$MCP_CODE' AND MCP_PRJCODE = '$MCP_PRJCODE'";
				$this->db->query($sqlMC);
		}
	}
	
	function count_all_mcnew($PRJCODE) // OK
	{
		$sqlNEW	= "tbl_mcg_header WHERE MC_STAT = '1' AND PRJCODE = '$PRJCODE'";
		$resNEW	= $this->db->count_all($sqlNEW);
		$sqlPRJ	= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
		$resPRJ	= $this->db->count_all($sqlPRJ);
		if($resPRJ == 0)
		{
			// GET PRJECT DETAIL			
				$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
				$resPRJ	= $this->db->query($sqlPRJ)->result();
				foreach($resPRJ as $rowPRJ) :
					$PRJNAME 	= $rowPRJ->PRJNAME;
					$PRJCOST 	= $rowPRJ->PRJCOST;
				endforeach;
			
			// SAVE TO DATA COUNT
				$insDC	= "INSERT INTO tbl_dash_transac (PRJ_CODE, PRJ_VALUE, TOT_MC_N)
							VALUES ('$PRJCODE', '$PRJCOST', '$resNEW')";
				$this->db->query($insDC);
		}
		else
		{
			// SAVE TO PROFITLOSS
				$updDC	= "UPDATE tbl_dash_transac SET TOT_MC_N = '$resNEW' WHERE PRJ_CODE = '$PRJCODE'";
				$this->db->query($updDC);
		}
	}
	
	function count_all_mccon($PRJCODE) // OK
	{
		$sqlCON	= "tbl_mcg_header WHERE MC_STAT = '2' AND PRJCODE = '$PRJCODE'";
		$resCON	= $this->db->count_all($sqlCON);
		$sqlPRJ	= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
		$resPRJ	= $this->db->count_all($sqlPRJ);
		if($resPRJ == 0)
		{
			// GET PRJECT DETAIL			
				$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
				$resPRJ	= $this->db->query($sqlPRJ)->result();
				foreach($resPRJ as $rowPRJ) :
					$PRJNAME 	= $rowPRJ->PRJNAME;
					$PRJCOST 	= $rowPRJ->PRJCOST;
				endforeach;
			
			// SAVE TO DATA COUNT
				$insDC	= "INSERT INTO tbl_dash_transac (PRJ_CODE, PRJ_VALUE, TOT_MC_C)
							VALUES ('$PRJCODE', '$PRJCOST', '$resCON')";
				$this->db->query($insDC);
		}
		else
		{
			// SAVE TO PROFITLOSS
				$updDC	= "UPDATE tbl_dash_transac SET TOT_MC_C = '$resCON' WHERE PRJ_CODE = '$PRJCODE'";
				$this->db->query($updDC);
		}
	}
	
	function count_all_mcapp($PRJCODE) // OK
	{
		$sqlAPP	= "tbl_mcg_header WHERE MC_STAT = '3' AND PRJCODE = '$PRJCODE'";
		$resAPP	= $this->db->count_all($sqlAPP);
		$sqlPRJ	= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
		$resPRJ	= $this->db->count_all($sqlPRJ);
		if($resPRJ == 0)
		{
			// GET PRJECT DETAIL			
				$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
				$resPRJ	= $this->db->query($sqlPRJ)->result();
				foreach($resPRJ as $rowPRJ) :
					$PRJNAME 	= $rowPRJ->PRJNAME;
					$PRJCOST 	= $rowPRJ->PRJCOST;
				endforeach;
			
			// SAVE TO DATA COUNT
				$insDC	= "INSERT INTO tbl_dash_transac (PRJ_CODE, PRJ_VALUE, TOT_MC_A)
							VALUES ('$PRJCODE', '$PRJCOST', '$resAPP')";
				$this->db->query($insDC);
		}
		else
		{
			// SAVE TO PROFITLOSS
				$updDC	= "UPDATE tbl_dash_transac SET TOT_MC_A = '$resAPP' WHERE PRJ_CODE = '$PRJCODE'";
				$this->db->query($updDC);
		}
	}
	
	function updateConc($MC_CODE, $dataMCH, $PRJCODE) // OK
	{
		$this->db->where('MCC_CODE', $MC_CODE);
		$this->db->update('tbl_mc_conc', $dataMCH);
	}
	
	function count_all_num_rowsProjSI($PRJCODE) // OK
	{
		$sql	= "tbl_siheader A
					LEFT JOIN  	tbl_employee B ON A.SI_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_projsi($PRJCODE) // OK
	{
		$sql = "SELECT A.*, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_Number, C.PRJCODE, C.PRJNAME
				FROM tbl_siheader A
				LEFT JOIN  	tbl_employee B ON A.SI_EMPID = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
				WHERE A.PRJCODE = '$PRJCODE'
				ORDER BY A.SI_DATE ASC";
		return $this->db->query($sql);
	}
	
	function addSI($dataSIH, $PRJCODE) // OK
	{
		$this->db->insert('tbl_siheader', $dataSIH);
		
		// UPDATE TO DASHBOARD
		$res_N	= 0;
		$res_C	= 0;
		$res_A	= 0;
		$res_CL	= 0;
		$sql_N	= "tbl_siheader WHERE PRJCODE = '$PRJCODE' AND SI_STAT = 1";
		$res_N	= $this->db->count_all($sql_N);
		$sql_C	= "tbl_siheader WHERE PRJCODE = '$PRJCODE' AND SI_STAT = 2";
		$res_C	= $this->db->count_all($sql_C);
		$sql_A	= "tbl_siheader WHERE PRJCODE = '$PRJCODE' AND SI_STAT = 3";
		$res_A	= $this->db->count_all($sql_A);
		$sql_CL	= "tbl_siheader WHERE PRJCODE = '$PRJCODE' AND SI_STAT = 6";
		$res_CL	= $this->db->count_all($sql_CL);
		
		$sqlPRJ	= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
		$resPRJ	= $this->db->count_all($sqlPRJ);
		if($resPRJ == 0)
		{
			// GET PRJECT DETAIL			
				$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
				$resPRJ	= $this->db->query($sqlPRJ)->result();
				foreach($resPRJ as $rowPRJ) :
					$PRJNAME 	= $rowPRJ->PRJNAME;
					$PRJCOST 	= $rowPRJ->PRJCOST;
				endforeach;
				
			// SAVE TO PROFITLOSS
				$insPL	= "INSERT INTO tbl_dash_transac (PRJ_CODE, PRJ_VALUE, TOT_SI_N, TOT_SI_C, TOT_SI_A, TOT_SI_CL)
							VALUES ('$PRJCODE', '$PRJCOST', '$res_N', '$res_C', '$res_A', '$res_CL')";
				$this->db->query($insPL);
						
		}
		else
		{
			// SAVE TO PROFITLOSS
			$sqlUPD	= "UPDATE tbl_dash_transac SET TOT_SI_N = $res_N, TOT_SI_C = $res_C, TOT_SI_A = $res_A, TOT_SI_CL = $res_CL
						WHERE PRJ_CODE = '$PRJCODE'";
			$this->db->query($sqlUPD);
		}
		
	}
	
	function count_all_sinew($PRJCODE) // OK
	{
		$sqlNEW	= "tbl_siheader WHERE SI_STAT = '1' AND PRJCODE = '$PRJCODE'";
		$resNEW	= $this->db->count_all($sqlNEW);
		$sqlPRJ	= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
		$resPRJ	= $this->db->count_all($sqlPRJ);
		if($resPRJ == 0)
		{
			// GET PRJECT DETAIL			
				$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
				$resPRJ	= $this->db->query($sqlPRJ)->result();
				foreach($resPRJ as $rowPRJ) :
					$PRJNAME 	= $rowPRJ->PRJNAME;
					$PRJCOST 	= $rowPRJ->PRJCOST;
				endforeach;
			
			// SAVE TO DATA COUNT
				$insDC	= "INSERT INTO tbl_dash_transac (PRJ_CODE, PRJ_VALUE, TOT_SI_N)
							VALUES ('$PRJCODE', '$PRJCOST', '$resNEW')";
				$this->db->query($insDC);
		}
		else
		{
			// SAVE TO PROFITLOSS
				$updDC	= "UPDATE tbl_dash_transac SET TOT_SI_N = '$resNEW' WHERE PRJ_CODE = '$PRJCODE'";
				$this->db->query($updDC);
		}
	}
	
	function count_all_sicon($PRJCODE) // OK
	{
		$sqlCON	= "tbl_siheader WHERE SI_STAT = '2' AND PRJCODE = '$PRJCODE'";
		$resCON	= $this->db->count_all($sqlCON);
		$sqlPRJ	= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
		$resPRJ	= $this->db->count_all($sqlPRJ);
		if($resPRJ == 0)
		{
			// GET PRJECT DETAIL			
				$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
				$resPRJ	= $this->db->query($sqlPRJ)->result();
				foreach($resPRJ as $rowPRJ) :
					$PRJNAME 	= $rowPRJ->PRJNAME;
					$PRJCOST 	= $rowPRJ->PRJCOST;
				endforeach;
			
			// SAVE TO DATA COUNT
				$insDC	= "INSERT INTO tbl_dash_transac (PRJ_CODE, PRJ_VALUE, TOT_SI_C)
							VALUES ('$PRJCODE', '$PRJCOST', '$resCON')";
				$this->db->query($insDC);
		}
		else
		{
			// SAVE TO PROFITLOSS
				$updDC	= "UPDATE tbl_dash_transac SET TOT_SI_C = '$resCON' WHERE PRJ_CODE = '$PRJCODE'";
				$this->db->query($updDC);
		}
	}
	
	function count_all_siapp($PRJCODE) // OK
	{
		$sqlAPP	= "tbl_siheader WHERE SI_STAT = '3' AND PRJCODE = '$PRJCODE'";
		$resAPP	= $this->db->count_all($sqlAPP);
		$sqlPRJ	= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
		$resPRJ	= $this->db->count_all($sqlPRJ);
		if($resPRJ == 0)
		{
			// GET PRJECT DETAIL			
				$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
				$resPRJ	= $this->db->query($sqlPRJ)->result();
				foreach($resPRJ as $rowPRJ) :
					$PRJNAME 	= $rowPRJ->PRJNAME;
					$PRJCOST 	= $rowPRJ->PRJCOST;
				endforeach;
			
			// SAVE TO DATA COUNT
				$insDC	= "INSERT INTO tbl_dash_transac (PRJ_CODE, PRJ_VALUE, TOT_SI_A)
							VALUES ('$PRJCODE', '$PRJCOST', '$resAPP')";
				$this->db->query($insDC);
		}
		else
		{
			// SAVE TO PROFITLOSS
				$updDC	= "UPDATE tbl_dash_transac SET TOT_SI_A = '$resAPP' WHERE PRJ_CODE = '$PRJCODE'";
				$this->db->query($updDC);
		}
	}
	
	function get_SI_by_number($SI_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_siheader WHERE SI_CODE = '$SI_CODE'";
		return $this->db->query($sql);
	}
	
	function updateSI($SI_CODE, $dataSIH, $PRJCODE) // OK
	{
		$this->db->where('SI_CODE', $SI_CODE);
		$this->db->update('tbl_siheader', $dataSIH);
		
		// UPDATE TO DASHBOARD
		$res_N	= 0;
		$res_C	= 0;
		$res_A	= 0;
		$res_CL	= 0;
		$sql_N	= "tbl_siheader WHERE PRJCODE = '$PRJCODE' AND SI_STAT = 1";
		$res_N	= $this->db->count_all($sql_N);
		$sql_C	= "tbl_siheader WHERE PRJCODE = '$PRJCODE' AND SI_STAT = 2";
		$res_C	= $this->db->count_all($sql_C);
		$sql_A	= "tbl_siheader WHERE PRJCODE = '$PRJCODE' AND SI_STAT = 3";
		$res_A	= $this->db->count_all($sql_A);
		$sql_CL	= "tbl_siheader WHERE PRJCODE = '$PRJCODE' AND SI_STAT = 6";
		$res_CL	= $this->db->count_all($sql_CL);
		
		$sqlPRJ	= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
		$resPRJ	= $this->db->count_all($sqlPRJ);
		if($resPRJ == 0)
		{
			// GET PRJECT DETAIL			
				$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
				$resPRJ	= $this->db->query($sqlPRJ)->result();
				foreach($resPRJ as $rowPRJ) :
					$PRJNAME 	= $rowPRJ->PRJNAME;
					$PRJCOST 	= $rowPRJ->PRJCOST;
				endforeach;
				
			// SAVE TO PROFITLOSS
				$insPL	= "INSERT INTO tbl_dash_transac (PRJ_CODE, PRJ_VALUE, TOT_SI_N, TOT_SI_C, TOT_SI_A, TOT_SI_CL)
							VALUES ('$PRJCODE', '$PRJCOST', '$res_N', '$res_C', '$res_A', '$res_CL')";
				$this->db->query($insPL);
						
		}
		else
		{
			// SAVE TO PROFITLOSS
			$sqlUPD	= "UPDATE tbl_dash_transac SET TOT_SI_N = $res_N, TOT_SI_C = $res_C, TOT_SI_A = $res_A, TOT_SI_CL = $res_CL
						WHERE PRJ_CODE = '$PRJCODE'";
			$this->db->query($sqlUPD);
		}
	}
	
	function deleteMC($SI_CODE) // OK
	{
		$sql = "DELETE FROM tbl_siheader WHERE SI_CODE = '$SI_CODE' AND SI_STAT = 1";
		return $this->db->query($sql);
	}
		
	function count_all_num_rows_PNo($txtSearch, $DefEmp_ID)  // U
	{
		$sql	= "tbl_project WHERE PRJCODE LIKE '%$txtSearch%' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_PNm($txtSearch, $DefEmp_ID) // U
	{
		$sql	= "tbl_project WHERE PRJNAME LIKE '%$txtSearch%' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project_PNo($limit, $offset, $txtSearch, $DefEmp_ID) // U
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];		
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, A.PRJCOST, A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
					A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
				FROM tbl_project A
				WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
					AND A.PRJCODE LIKE '%$txtSearch%'
				ORDER BY A.PRJCODE
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function get_last_ten_project_PNm($limit, $offset, $txtSearch, $DefEmp_ID) // U
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];		
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, A.PRJCOST, A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
				A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
				FROM tbl_project A 
				WHERE A.PRJCODE IN (SELECT PRJCODE FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
					AND A.PRJNAME LIKE '%$txtSearch%'
				ORDER BY A.PRJCODE
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsOwner() // U
	{
		return $this->db->count_all('tbl_owner');
	}
	
	function viewOwner() // U
	{
		$sql = "SELECT * FROM tbl_owner ORDER BY own_Name";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsEmpDept() // U
	{
		return $this->db->count_all('tbl_employee');
	}
	
	function viewEmployeeDept() // U
	{
		$this->db->select('Emp_ID, First_name, FlagUSER, Middle_Name, Last_Name');
		$this->db->from('tbl_employee');
		$this->db->order_by('First_name', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rows_projMC_srcMC($txtSearch, $PRJCODE) // U
	{		
		$sql	= "tbl_mcg_header A
					LEFT JOIN  	tbl_employee B ON A.MC_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.MC_CODE LIKE '%$txtSearch%' AND A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_projMC_srcPN($txtSearch, $PRJCODE) // U
	{
		$sql	= "tbl_mcg_header A
					LEFT JOIN  	tbl_employee B ON A.MC_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE C.PRJNAME LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_projmc_MCNo($limit, $offset, $txtSearch, $PRJCODE) // U
	{		
		$sql = "SELECT A.*, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_Number, C.PRJCODE, C.PRJNAME
				FROM tbl_mcg_header A
					LEFT JOIN  	tbl_employee B ON A.MC_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
				WHERE 
					A.MC_CODE LIKE '%$txtSearch%' AND A.PRJCODE = '$PRJCODE'
				ORDER BY A.MC_CODE ASC
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function get_last_ten_projmc_PNm($limit, $offset, $txtSearch) // U
	{
		$sql = "SELECT A.PINV_ID, A.PINV_Number, A.PINV_Date, A.PINV_EndDate, A.PINV_Class, A.PINV_Type, A.proj_Code, A.Owner_Code, A.MC_EMPID, A.PINV_Notes, A.PINV_Status, 
					A.Approval_Status, A.Approve_Date, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.First_Name, B.Middle_Name, B.Last_Name, 
					C.proj_Number, C.PRJCODE, C.PRJNAME
				FROM tbl_mcg_header A
					LEFT JOIN  	tbl_employee B ON A.MC_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
				WHERE
					C.PRJNAME LIKE '%$txtSearch%'
				ORDER BY A.MC_CODE ASC
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_projSI_srcSI($txtSearch, $PRJCODE) // U
	{		
		$sql	= "tbl_siheader A
					LEFT JOIN  	tbl_employee B ON A.SI_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.SI_CODE LIKE '%$txtSearch%' AND A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_projSI_srcSIMN($txtSearch, $PRJCODE) // U
	{
		$sql	= "tbl_siheader A
					LEFT JOIN  	tbl_employee B ON A.SI_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.SI_MANNO LIKE '%$txtSearch%' AND A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_projsi_MSINo($limit, $offset, $txtSearch, $PRJCODE) // U
	{		
		$sql = "SELECT A.*, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_Number, C.PRJCODE, C.PRJNAME
				FROM tbl_siheader A
					LEFT JOIN  	tbl_employee B ON A.SI_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
				WHERE 
					A.SI_CODE LIKE '%$txtSearch%' AND A.PRJCODE = '$PRJCODE'
				ORDER BY A.SI_MANNO ASC
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function get_last_ten_projsi_SIMN($limit, $offset, $txtSearch, $PRJCODE) // U
	{		
		$sql = "SELECT A.*, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_Number, C.PRJCODE, C.PRJNAME
				FROM tbl_siheader A
					LEFT JOIN  	tbl_employee B ON A.SI_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
				WHERE 
					A.SI_MANNO LIKE '%$txtSearch%' AND A.PRJCODE = '$PRJCODE'
				ORDER BY A.SI_MANNO ASC
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function updateSIH($SI_CODE, $dataSIH) // U 
	{
		$this->db->where('SI_CODE', $SI_CODE);
		$this->db->update('tbl_siheader', $dataSIH);
	}
	
	function syncTable($PRJCODE) // U 
	{
		$FIRST		= "SI$PRJCODE";
		$sql1 		= "SELECT SI_ID, ADD1, ADD2, ADD3, ADD4, PATT_NUMBER AS ADD5 FROM tbl_siheader";
		$result1 	= $this->db->query($sql1)->result();
		foreach($result1 as $row1) :
			$SI_ID 		= $row1 ->SI_ID;
			$ADD1 		= $row1 ->ADD1;		// SI[year]
			$ADD2 		= $row1 ->ADD2;		// Year
			$ADD3 		= $row1 ->ADD3;		// Month
			$ADD4 		= $row1 ->ADD4;		// Day
			$ADD5a		= $row1 ->ADD5;		// Pattern
			
			$lenYear 	= strlen($ADD2);
			if($lenYear==1) $nolYear="0";elseif($lenYear==2) $nolYear="";
			$pattYear 	= $nolYear.$ADD2;
			
			$lenMonth 	= strlen($ADD3);
			if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
			$pattMonth 	= $nolMonth.$ADD3;
			
			$lenDate 	= strlen($ADD4);
			if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
			$pattDate 	= $nolDate.$ADD4;	
			
			$ADD5 		= strlen($ADD5a);
			if($ADD5==1) $nolADD5="00";elseif($ADD5==2) $nolADD5="0";elseif($ADD5==3) $nolADD5="";
			$pattADD5 	= $nolADD5.$ADD5a;	
			
			$colCode	= "$ADD1$pattYear$pattMonth$pattDate-$pattADD5";
			
			$sqlUpDet	= "UPDATE tbl_siheader SET SI_CODE = '$colCode' WHERE SI_ID = $SI_ID";	
			$this->db->query($sqlUpDet);
		endforeach;	
	}
}
?>