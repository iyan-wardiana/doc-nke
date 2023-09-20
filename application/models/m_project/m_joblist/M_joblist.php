<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 20 Desember 2021
	* File Name		= M_joblist.php
	* Location		= -  
*/

class M_joblist extends CI_Model
{	
	function count_all_project() // OK
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];	
		$sql		= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project() // OK
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];		
		$sql 		= "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, 
							A.PRJCOST, A.PRJCOST2, A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
							A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
						FROM tbl_project A 
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function count_all_schedule($PRJCODE) // OK
	{
		$sql = "tbl_joblist WHERE PRJCODE = '$PRJCODE' AND JOBLEV = 1";
		return $this->db->count_all($sql);
	}
	
	function get_all_joblist($PRJCODE) // OK
	{
		$sql = "SELECT *
				FROM tbl_joblist WHERE PRJCODE = '$PRJCODE' AND JOBLEV = 1
				ORDER BY JOBCODEID";
		return $this->db->query($sql);
	}
	
	function get_project_name($PRJCODE) // OK
	{
		$sql = "SELECT * FROM tbl_project WHERE PRJCODE = '$PRJCODE'";			
		return $this->db->query($sql);
	}
	
	function count_all_JOB($PRJCODE, $JOB_CODE) // OK
	{	
		$sql	= "tbl_joblist WHERE PRJCODE = '$PRJCODE' AND JOBCOD1 = '$JOB_CODE'";
		return $this->db->count_all($sql);
	}
	
	function add($joblist) // OK
	{
		$this->db->insert('tbl_joblist', $joblist);
	}
	
	function get_joblist_by_code($JOBCODEID) // OK
	{
		if($JOBCODEID == '')
		{
			$sql = "SELECT * FROM tbl_joblist";
		}
		else
		{
			$sql = "SELECT * FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
		}
		return $this->db->query($sql);
	}
	
	function update($JOBCODEID, $joblist) // OK
	{
		$this->db->where('JOBCODEID', $JOBCODEID);
		$this->db->update('tbl_joblist', $joblist);
	}
	
	function count_all_job1() // OK
	{
		return $this->db->count_all('tbl_joblist');
	}
	
	function get_all_job1() // OK
	{		
		$sql = "SELECT * FROM tbl_joblist WHERE JOBPARENT = '0'";
		return $this->db->query($sql);
	}
	
	function updateParent($JOBPARENT, $JOBCODEID) // OK
	{
		if($JOBPARENT == 0)
		{
			$sqlUTOTC1		= "UPDATE tbl_joblist SET ISHEADER = 1 WHERE JOBCODEID = '$JOBCODEID'";
			return $this->db->query($sqlUTOTC1);
		}
		else
		{
			$TOTCOST		= 0;
			$sqlTOTC		= "SELECT SUM(JOBCOST) AS TOTCOST FROM tbl_joblist WHERE JOBPARENT = '$JOBPARENT'";
			$resTOTC 		= $this->db->query($sqlTOTC)->result();
			foreach($resTOTC as $rowTOTC) :
				$TOTCOST	= $rowTOTC->TOTCOST;
			endforeach;
			
			$sqlUTOTC		= "UPDATE tbl_joblist SET JOBCOST = $TOTCOST, ISHEADER = 1 WHERE JOBCODEID = '$JOBPARENT'";
			$this->db->query($sqlUTOTC);
			
			// CEK NEXT UP 1 LEVEL PARENT
			$JOBPARENT2		= '';
			$sqlGETP2C		= "tbl_joblist WHERE JOBCODEID = '$JOBPARENT'";
			$resGETP2C 		= $this->db->count_all($sqlGETP2C);
			
			if($resGETP2C > 0)
			{
				$sqlGETP2		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT'";
				$resGETP2 		= $this->db->query($sqlGETP2)->result();
				foreach($resGETP2 as $rowP2) :
					$JOBPARENT2	= $rowP2->JOBPARENT;
				endforeach;
				
				$TOTCOST2		= 0;
				$sqlTOTC2		= "SELECT SUM(JOBCOST) AS TOTCOST2 FROM tbl_joblist WHERE JOBPARENT = '$JOBPARENT2'";
				$resTOTC2 		= $this->db->query($sqlTOTC2)->result();
				foreach($resTOTC2 as $rowTOTC2) :
					$TOTCOST2	= $rowTOTC2->TOTCOST2;
				endforeach;
				
				$sqlUTOTC2		= "UPDATE tbl_joblist SET JOBCOST = $TOTCOST2, ISHEADER = 1 WHERE JOBCODEID = '$JOBPARENT2'";
				$this->db->query($sqlUTOTC2);
				
				// CEK NEXT UP 2 LEVEL PARENT
				$JOBPARENT3		= '';
				$sqlGETP3C		= "tbl_joblist WHERE JOBCODEID = '$JOBPARENT2'";
				$resGETP3C 		= $this->db->count_all($sqlGETP3C);
				if($resGETP3C > 0)
				{
					$sqlGETP3		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT2'";
					$resGETP3 		= $this->db->query($sqlGETP3)->result();
					foreach($resGETP3 as $rowP3) :
						$JOBPARENT3	= $rowP3->JOBPARENT;
					endforeach;
					
					$TOTCOST3		= 0;
					$sqlTOTC3		= "SELECT SUM(JOBCOST) AS TOTCOST3 FROM tbl_joblist WHERE JOBPARENT = '$JOBPARENT2'";
					$resTOTC3 		= $this->db->query($sqlTOTC3)->result();
					foreach($resTOTC3 as $rowTOTC3) :
						$TOTCOST3	= $rowTOTC3->TOTCOST3;
					endforeach;
					
					$sqlUTOTC3		= "UPDATE tbl_joblist SET JOBCOST = $TOTCOST3, ISHEADER = 1 WHERE JOBCODEID = '$JOBPARENT2'";
					$this->db->query($sqlUTOTC3);
					
					// CEK NEXT UP 3 LEVEL PARENT
					$JOBPARENT4		= '';
					$sqlGETP4C		= "tbl_joblist WHERE JOBCODEID = '$JOBPARENT3'";
					$resGETP4C 		= $this->db->count_all($sqlGETP4C);
					if($resGETP4C > 0)
					{
						$sqlGETP4		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT3'";
						$resGETP4 		= $this->db->query($sqlGETP4)->result();
						foreach($resGETP4 as $rowP4) :
							$JOBPARENT4	= $rowP4->JOBPARENT;
						endforeach;
						
						$TOTCOST4		= 0;
						$sqlTOTC4		= "SELECT SUM(JOBCOST) AS TOTCOST4 FROM tbl_joblist WHERE JOBPARENT = '$JOBPARENT3'";
						$resTOTC4 		= $this->db->query($sqlTOTC4)->result();
						foreach($resTOTC4 as $rowTOTC4) :
							$TOTCOST4	= $rowTOTC4->TOTCOST4;
						endforeach;
						
						$sqlUTOTC4		= "UPDATE tbl_joblist SET JOBCOST = $TOTCOST4, ISHEADER = 1 WHERE JOBCODEID = '$JOBPARENT3'";
						$this->db->query($sqlUTOTC4);
						
						// CEK NEXT UP 4 LEVEL PARENT
						$JOBPARENT5		= '';
						$sqlGETP5C		= "tbl_joblist WHERE JOBCODEID = '$JOBPARENT4'";
						$resGETP5C 		= $this->db->count_all($sqlGETP5C);
						if($resGETP5C > 0)
						{
							$sqlGETP5		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT4'";
							$resGETP5 		= $this->db->query($sqlGETP5)->result();
							foreach($resGETP5 as $rowP5) :
								$JOBPARENT5	= $rowP5->JOBPARENT;
							endforeach;
							
							$TOTCOST5		= 0;
							$sqlTOTC5		= "SELECT SUM(JOBCOST) AS TOTCOST5 FROM tbl_joblist WHERE JOBPARENT = '$JOBPARENT4'";
							$resTOTC5 		= $this->db->query($sqlTOTC5)->result();
							foreach($resTOTC5 as $rowTOTC5) :
								$TOTCOST5	= $rowTOTC5->TOTCOST5;
							endforeach;
							
							$sqlUTOTC5		= "UPDATE tbl_joblist SET JOBCOST = $TOTCOST5, ISHEADER = 1 WHERE JOBCODEID = '$JOBPARENT4'";
							$this->db->query($sqlUTOTC5);
							
							// CEK NEXT UP 5 LEVEL PARENT
							$JOBPARENT6		= '';
							$sqlGETP6C		= "tbl_joblist WHERE JOBCODEID = '$JOBPARENT5'";
							$resGETP6C 		= $this->db->count_all($sqlGETP6C);
							if($resGETP6C > 0)
							{
								$sqlGETP6		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT5'";
								$resGETP6 		= $this->db->query($sqlGETP6)->result();
								foreach($resGETP6 as $rowP6) :
									$JOBPARENT6	= $rowP6->JOBPARENT;
								endforeach;
								
								$TOTCOST6		= 0;
								$sqlTOTC6		= "SELECT SUM(JOBCOST) AS TOTCOST6 FROM tbl_joblist WHERE JOBPARENT = '$JOBPARENT5'";
								$resTOTC6 		= $this->db->query($sqlTOTC6)->result();
								foreach($resTOTC6 as $rowTOTC6) :
									$TOTCOST6	= $rowTOTC6->TOTCOST6;
								endforeach;
								
								$sqlUTOTC6		= "UPDATE tbl_joblist SET JOBCOST = $TOTCOST6, ISHEADER = 1 WHERE JOBCODEID = '$JOBPARENT5'";
								$this->db->query($sqlUTOTC6);
							}
						}
					}
				}
			}
		}
	}
	
	function get_AllDataBQC($PRJCODE, $search) // GOOD
	{
		$PRJCODEVW 		= '';
		$s_PRJVW		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_PRJVW 		= $this->db->query($s_PRJVW)->result();
		foreach($r_PRJVW as $rw_PRJVW) :
			$PRJCODEVW	= strtolower($rw_PRJVW->PRJCODEVW);
		endforeach;

		$sql = "vw_joblist_detail_$PRJCODEVW A 
				WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 0
					AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
					OR JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataBQL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 		= '';
		$s_PRJVW		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_PRJVW 		= $this->db->query($s_PRJVW)->result();
		foreach($r_PRJVW as $rw_PRJVW) :
			$PRJCODEVW	= strtolower($rw_PRJVW->PRJCODEVW);
		endforeach;

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT ORD_ID, JOBCODEID, JOBDESC, IS_LEVEL, ITM_UNIT, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_LASTP, BOQ_JOBCOST, ITM_BUDG, 
							BOQ_VOLM, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, ITM_USED, ITM_USED_AM, ISLASTH, ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A 
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 0
							AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT ORD_ID, JOBCODEID, JOBDESC, IS_LEVEL, ITM_UNIT, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_LASTP, BOQ_JOBCOST, ITM_BUDG,
							BOQ_VOLM, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, ITM_USED, ITM_USED_AM, ISLASTH, ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A 
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 0 
							AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT ORD_ID, JOBCODEID, JOBDESC, IS_LEVEL, ITM_UNIT, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_LASTP, BOQ_JOBCOST, ITM_BUDG,
							BOQ_VOLM, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, ITM_USED, ITM_USED_AM, ISLASTH, ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A 
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 0
							AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT ORD_ID, JOBCODEID, JOBDESC, IS_LEVEL, ITM_UNIT, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_LASTP, BOQ_JOBCOST, ITM_BUDG,
							BOQ_VOLM, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, ITM_USED, ITM_USED_AM, ISLASTH, ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A 
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 0
							AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataJLC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_joblist_detail A 
				WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 0
					AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
					OR JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataJLL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT ORD_ID, JOBCODEID, JOBDESC, IS_LEVEL, ITM_UNIT, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_LASTP, BOQ_JOBCOST, ITM_BUDG,
							ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, ITM_USED, ITM_USED_AM, ISLASTH, ISLAST
						FROM tbl_joblist_detail A 
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 0
							AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT ORD_ID, JOBCODEID, JOBDESC, IS_LEVEL, ITM_UNIT, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_LASTP, BOQ_JOBCOST, ITM_BUDG,
							ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, ITM_USED, ITM_USED_AM, ISLASTH, ISLAST
						FROM tbl_joblist_detail A 
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 0 
							AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT ORD_ID, JOBCODEID, JOBDESC, IS_LEVEL, ITM_UNIT, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_LASTP, BOQ_JOBCOST, ITM_BUDG,
							ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, ITM_USED, ITM_USED_AM, ISLASTH, ISLAST
						FROM tbl_joblist_detail A 
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 0
							AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT ORD_ID, JOBCODEID, JOBDESC, IS_LEVEL, ITM_UNIT, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_LASTP, BOQ_JOBCOST, ITM_BUDG,
							ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, ITM_USED, ITM_USED_AM, ISLASTH, ISLAST
						FROM tbl_joblist_detail A 
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 0
							AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataMANC($search) // GOOD
	{
		$sql = "tbl_manalysis_header A 
				WHERE MAN_CODE LIKE '%$search%' ESCAPE '!' OR MAN_NAME LIKE '%$search%' ESCAPE '!'
					OR MAN_DESC LIKE '%$search%' ESCAPE '!'";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataMANL($search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_manalysis_header A 
						WHERE MAN_CODE LIKE '%$search%' ESCAPE '!' OR MAN_NAME LIKE '%$search%' ESCAPE '!'
							OR MAN_DESC LIKE '%$search%' ESCAPE '!' ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT * FROM tbl_manalysis_header A 
						WHERE MAN_CODE LIKE '%$search%' ESCAPE '!' OR MAN_NAME LIKE '%$search%' ESCAPE '!'
							OR MAN_DESC LIKE '%$search%' ESCAPE '!'";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_manalysis_header A 
						WHERE MAN_CODE LIKE '%$search%' ESCAPE '!' OR MAN_NAME LIKE '%$search%' ESCAPE '!'
							OR MAN_DESC LIKE '%$search%' ESCAPE '!' ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT * FROM tbl_manalysis_header A 
						WHERE MAN_CODE LIKE '%$search%' ESCAPE '!' OR MAN_NAME LIKE '%$search%' ESCAPE '!'
							OR MAN_DESC LIKE '%$search%' ESCAPE '!' $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMC($search, $ITM_GROUP) // GOOD
	{
		$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
		$resISHO		= $this->db->query($sqlISHO)->result();
		foreach($resISHO as $rowISHO):
			$PRJCODE	= $rowISHO->PRJCODE;
		endforeach;

		$sql = "tbl_item A
				WHERE A.ITM_GROUP = '$ITM_GROUP' AND A.PRJCODE = '$PRJCODE' AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
				OR A.ITM_NAME LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITML($search, $ITM_GROUP, $length, $start, $order, $dir) // GOOD
	{
		$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
		$resISHO		= $this->db->query($sqlISHO)->result();
		foreach($resISHO as $rowISHO):
			$PRJCODE	= $rowISHO->PRJCODE;
		endforeach;

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_GROUP, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ITM_VOLMBG, A.BOQ_ITM_VOLM, A.BOQ_ITM_TOTALP, A.PR_VOLM, A.PR_AMOUNT
						FROM tbl_item A
						WHERE A.ITM_GROUP = '$ITM_GROUP' AND A.PRJCODE = '$PRJCODE' AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_GROUP, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ITM_VOLMBG, A.BOQ_ITM_VOLM, A.BOQ_ITM_TOTALP, A.PR_VOLM, A.PR_AMOUNT
						FROM tbl_item A
						WHERE A.ITM_GROUP = '$ITM_GROUP' AND A.PRJCODE = '$PRJCODE' AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_GROUP, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ITM_VOLMBG, A.BOQ_ITM_VOLM, A.BOQ_ITM_TOTALP, A.PR_VOLM, A.PR_AMOUNT
						FROM tbl_item A
						WHERE A.ITM_GROUP = '$ITM_GROUP' AND A.PRJCODE = '$PRJCODE' AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_GROUP, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ITM_VOLMBG, A.BOQ_ITM_VOLM, A.BOQ_ITM_TOTALP, A.PR_VOLM, A.PR_AMOUNT
						FROM tbl_item A
						WHERE A.ITM_GROUP = '$ITM_GROUP' AND A.PRJCODE = '$PRJCODE' AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMHOC($ITM_GROUP, $search) // GOOD
	{
		$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
		$resISHO		= $this->db->query($sqlISHO)->result();
		foreach($resISHO as $rowISHO):
			$PRJCODE	= $rowISHO->PRJCODE;
		endforeach;

		if($ITM_GROUP == 'S')
			$ITM_GROUP = "S', 'SC";

		$sql = "tbl_item A
				WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('$ITM_GROUP') AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
				OR A.ITM_NAME LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMHOL($ITM_GROUP, $search, $length, $start, $order, $dir) // GOOD
	{
		$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
		$resISHO		= $this->db->query($sqlISHO)->result();
		foreach($resISHO as $rowISHO):
			$PRJCODE	= $rowISHO->PRJCODE;
		endforeach;

		if($ITM_GROUP == 'S')
			$ITM_GROUP = "S', 'SC";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_GROUP, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ITM_VOLMBG, A.BOQ_ITM_VOLM, A.BOQ_ITM_TOTALP, A.PR_VOLM, A.PR_AMOUNT
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('$ITM_GROUP') AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_GROUP, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ITM_VOLMBG, A.BOQ_ITM_VOLM, A.BOQ_ITM_TOTALP, A.PR_VOLM, A.PR_AMOUNT
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('$ITM_GROUP') AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_GROUP, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ITM_VOLMBG, A.BOQ_ITM_VOLM, A.BOQ_ITM_TOTALP, A.PR_VOLM, A.PR_AMOUNT
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('$ITM_GROUP') AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_GROUP, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ITM_VOLMBG, A.BOQ_ITM_VOLM, A.BOQ_ITM_TOTALP, A.PR_VOLM, A.PR_AMOUNT
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('$ITM_GROUP') AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMALLC($ITM_GROUP, $search) // GOOD
	{
		$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
		$resISHO		= $this->db->query($sqlISHO)->result();
		foreach($resISHO as $rowISHO):
			$PRJCODE	= $rowISHO->PRJCODE;
		endforeach;

		if($ITM_GROUP == 'S')
			$ITM_GROUP = "S', 'SC";

		$sql = "tbl_item A
				WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
				OR A.ITM_NAME LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMALLL($ITM_GROUP, $search, $length, $start, $order, $dir) // GOOD
	{
		$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
		$resISHO		= $this->db->query($sqlISHO)->result();
		foreach($resISHO as $rowISHO):
			$PRJCODE	= $rowISHO->PRJCODE;
		endforeach;

		if($ITM_GROUP == 'S')
			$ITM_GROUP = "S', 'SC";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_GROUP, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ITM_VOLMBG, A.BOQ_ITM_VOLM, A.BOQ_ITM_TOTALP, A.PR_VOLM, A.PR_AMOUNT
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_GROUP, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ITM_VOLMBG, A.BOQ_ITM_VOLM, A.BOQ_ITM_TOTALP, A.PR_VOLM, A.PR_AMOUNT
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_GROUP, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ITM_VOLMBG, A.BOQ_ITM_VOLM, A.BOQ_ITM_TOTALP, A.PR_VOLM, A.PR_AMOUNT
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_GROUP, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ITM_VOLMBG, A.BOQ_ITM_VOLM, A.BOQ_ITM_TOTALP, A.PR_VOLM, A.PR_AMOUNT
						FROM tbl_item A
						WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1 AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function addMAnl($inpMANL) // OK
	{
		$this->db->insert('tbl_manalysis_header', $inpMANL);
	}
	
	function get_manl_by_number($MAN_NUM) // OK
	{
		$sql = "SELECT * FROM tbl_manalysis_header WHERE MAN_NUM = '$MAN_NUM'";
		return $this->db->query($sql);
	}
	
	function MAnl_update($MAN_NUM, $updMANL) // OK
	{
		$this->db->where('MAN_NUM', $MAN_NUM);
		$this->db->update('tbl_manalysis_header', $updMANL);
	}
	
	function deleteDetail($MAN_NUM) // G
	{
		$this->db->where('MAN_NUM', $MAN_NUM);
		$this->db->delete('tbl_manalysis_detail');
	}
	
	function get_AllDataJANC($PRJCODE, $search) // GOOD
	{
		$sql 	= "tbl_janalysis_header
					WHERE PRJCODE = '$PRJCODE' AND (JAN_CODE LIKE '%$search%' ESCAPE '!' OR JAN_NAME LIKE '%$search%' ESCAPE '!'
						OR JAN_DESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataJANL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_janalysis_header
						WHERE PRJCODE = '$PRJCODE' AND (JAN_CODE LIKE '%$search%' ESCAPE '!' OR JAN_NAME LIKE '%$search%' ESCAPE '!'
							OR JAN_DESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT * FROM tbl_janalysis_header
						WHERE PRJCODE = '$PRJCODE' AND (JAN_CODE LIKE '%$search%' ESCAPE '!' OR JAN_NAME LIKE '%$search%' ESCAPE '!'
							OR JAN_DESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_janalysis_header
						WHERE PRJCODE = '$PRJCODE' AND (JAN_CODE LIKE '%$search%' ESCAPE '!' OR JAN_NAME LIKE '%$search%' ESCAPE '!'
							OR JAN_DESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT * FROM tbl_janalysis_header
						WHERE PRJCODE = '$PRJCODE' AND (JAN_CODE LIKE '%$search%' ESCAPE '!' OR JAN_NAME LIKE '%$search%' ESCAPE '!'
							OR JAN_DESC LIKE '%$search%' ESCAPE '!') $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataSRVCX($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_joblist A
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBUNIT LIKE '%$search%' ESCAPE '!'
				OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataSRVLX($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBPARENT, A.JOBDESC, A.JOBUNIT, A.JOBCOST, A.JOBLEV, A.ISLASTH, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBUNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBPARENT, A.JOBDESC, A.JOBUNIT, A.JOBCOST, A.JOBLEV, A.ISLASTH, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBUNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBPARENT, A.JOBDESC, A.JOBUNIT, A.JOBCOST, A.JOBLEV, A.ISLASTH, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBUNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBPARENT, A.JOBDESC, A.JOBUNIT, A.JOBCOST, A.JOBLEV, A.ISLASTH, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBUNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataJLDC($PRJCODE, $JOBCOST, $search) // GOOD
	{
		$sql = "tbl_joblist A
				WHERE A.PRJCODE = '$PRJCODE' AND (A.JOBCOST = $JOBCOST OR A.JOBCOST = 0)
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBUNIT LIKE '%$search%' ESCAPE '!'
				OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataJLDL($PRJCODE, $JOBCOST, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBPARENT, A.JOBDESC, A.JOBUNIT, A.JOBCOST, A.JOBLEV, A.ISLASTH, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE' AND (A.JOBCOST = $JOBCOST OR A.JOBCOST = 0)
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBUNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBPARENT, A.JOBDESC, A.JOBUNIT, A.JOBCOST, A.JOBLEV, A.ISLASTH, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE' AND (A.JOBCOST = $JOBCOST OR A.JOBCOST = 0)
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBUNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBPARENT, A.JOBDESC, A.JOBUNIT, A.JOBCOST, A.JOBLEV, A.ISLASTH, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE' AND (A.JOBCOST = $JOBCOST OR A.JOBCOST = 0)
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBUNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBPARENT, A.JOBDESC, A.JOBUNIT, A.JOBCOST, A.JOBLEV, A.ISLASTH, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE' AND (A.JOBCOST = $JOBCOST OR A.JOBCOST = 0)
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBUNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function addJAnl($inpJANL) // OK
	{
		$this->db->insert('tbl_janalysis_header', $inpJANL);
	}
	
	function get_janl_by_number($JAN_NUM) // OK
	{
		$sql = "SELECT * FROM tbl_janalysis_header WHERE JAN_NUM = '$JAN_NUM'";
		return $this->db->query($sql);
	}
	
	function JAnl_update($JAN_NUM, $updJANL) // OK
	{
		$this->db->where('JAN_NUM', $JAN_NUM);
		$this->db->update('tbl_janalysis_header', $updJANL);
	}
	
	function deleteMANL($JAN_NUM, $PRJCODE) // G
	{
		$this->db->where('JAN_NUM', $JAN_NUM);
		$this->db->where('PRJCODE', $PRJCODE);
		$this->db->delete('tbl_janalysis_manl');
	}
	
	function deleteJL($JAN_NUM, $PRJCODE) // G
	{
		$this->db->where('JAN_NUM', $JAN_NUM);
		$this->db->where('PRJCODE', $PRJCODE);
		$this->db->delete('tbl_janalysis_jlist');
	}
	
	function deleteJANLD($JAN_NUM, $PRJCODE) // G
	{
		$this->db->where('JAN_NUM', $JAN_NUM);
		$this->db->where('PRJCODE', $PRJCODE);
		$this->db->delete('tbl_janalysis_detail');
	}
	
	function get_AllDataITMMANC($COLLMANL) // GOOD
	{
		$sql = "tbl_manalysis_detail WHERE MAN_NUM IN ($COLLMANL)";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMMANL($COLLMANL) // GOOD
	{
		$sql = "SELECT * FROM tbl_manalysis_detail  WHERE MAN_NUM IN ($COLLMANL) ORDER BY ITM_NAME";
		return $this->db->query($sql);
	}
	
	function addRAP($jobAddH) // OK
	{
		$this->db->insert('tbl_jobcreate_header', $jobAddH);
	}
	
	function get_par_by_number($JOBPARCODE, $PRJCODE) // OK
	{
		$sql = "SELECT * FROM tbl_jobcreate_header WHERE JOB_PARCODE = '$JOBPARCODE' AND PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function get_AllDataRAPDC($PRJCODE, $JOBPARENT, $search) // GOOD
	{
		$sql = "tbl_jobcreate_detail
				WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$JOBPARENT'
					AND (JOBPARENT LIKE '%$search%' ESCAPE '!' OR ITM_CODE LIKE '%$search%' ESCAPE '!'
					OR ITM_NAME LIKE '%$search%' ESCAPE '!' OR ITM_NOTES LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataRAPDL($PRJCODE, $JOBPARENT, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_jobcreate_detail
						WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$JOBPARENT'
							AND (JOBPARENT LIKE '%$search%' ESCAPE '!' OR ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR ITM_NAME LIKE '%$search%' ESCAPE '!' OR ITM_NOTES LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT * FROM tbl_jobcreate_detail
						WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$JOBPARENT'
							AND (JOBPARENT LIKE '%$search%' ESCAPE '!' OR ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR ITM_NAME LIKE '%$search%' ESCAPE '!' OR ITM_NOTES LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_jobcreate_detail
						WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$JOBPARENT'
							AND (JOBPARENT LIKE '%$search%' ESCAPE '!' OR ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR ITM_NAME LIKE '%$search%' ESCAPE '!' OR ITM_NOTES LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT * FROM tbl_jobcreate_detail
						WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$JOBPARENT'
							AND (JOBPARENT LIKE '%$search%' ESCAPE '!' OR ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR ITM_NAME LIKE '%$search%' ESCAPE '!' OR ITM_NOTES LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function addJC($PRJCODE, $JOBPARENT, $search, $length, $start, $order, $dir) // GOOD
	{
		$JOB_NUM 	= $PRJCODE.".".date('YmdHis');
		$ITM_TOTAL 	= 0;
		$ITM_RAPV 	= 0;
		$s_00 		= "SELECT JOBCODEID, JOBPARENT, ITM_CODE, JOBDESC, ITM_UNIT, ITM_GROUP, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST,
							ITM_VOLM, ITM_PRICE, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST
						FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$JOBPARENT'";
		$r_00 		= $this->db->query($s_00)->result();
		foreach($r_00 as $rw_00) :
        	$JOBCODEID  = $rw_00->JOBCODEID;
        	$ITM_CODE 	= $rw_00->ITM_CODE;
        	$JOBDESC  	= $rw_00->JOBDESC;
        	$ITM_UNIT  	= $rw_00->ITM_UNIT;
        	$ITM_GROUP  = $rw_00->ITM_GROUP;
        	$BOQ_VOLM  	= $rw_00->BOQ_VOLM;
        	$BOQ_PRICE  = $rw_00->BOQ_PRICE;
        	$BOQ_JOBCOST= $rw_00->BOQ_JOBCOST;
        	$ITM_VOLM  	= $rw_00->ITM_VOLM;
        	$ITM_PRICE  = $rw_00->ITM_PRICE;
        	$ITM_BUDG 	= $rw_00->ITM_BUDG ;
        	$ADD_VOLM 	= $rw_00->ADD_VOLM ;
        	$ADD_PRICE 	= $rw_00->ADD_PRICE ;
        	$ADD_JOBCOST= $rw_00->ADD_JOBCOST;
        	$ITM_KOEF 	= 0;

        	$ITM_RAPV 	= $ITM_VOLM+$ADD_VOLM;
        	$ITM_TOTAL 	= $ITM_BUDG+$ADD_JOBCOST;

        	// INSERT DETAIL
	        	$s_01 	= "INSERT INTO tbl_jobcreate_detail (PRJCODE, JOB_NUM, JOBCODEID, JOBPARENT, ITM_CODE, ITM_NAME, ITM_UNIT, ITM_GROUP,
	        				ITM_BOQV, ITM_BOQP, ITM_KOEF, ITM_RAPV, ITM_RAPP, ITM_TOTAL, ITM_NOTES, ISLOCK) VALUES
	        				('$PRJCODE', '$JOB_NUM', '$JOBCODEID', '$JOBPARENT', '$ITM_CODE', '$JOBDESC', '$ITM_UNIT', '$ITM_GROUP',
	        				'$BOQ_VOLM', '$BOQ_PRICE', '$ITM_KOEF', '$ITM_RAPV', '$ITM_PRICE', '$ITM_TOTAL', '', 1)";
	        	$this->db->query($s_01);
    	endforeach;

		$s_02 		= "SELECT JOBDESC, ITM_UNIT, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, ITM_VOLM, ITM_PRICE, ITM_BUDG
						FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBPARENT'";
		$r_02 		= $this->db->query($s_02)->result();
		foreach($r_02 as $rw_02) :
        	$JOBDESCH  	= $rw_02->JOBDESC;
        	$JOB_UNIT  	= strtoupper($rw_02->ITM_UNIT);
        	$JOB_BOQV  	= $rw_02->BOQ_VOLM;
        	$JOB_BOQP  	= $rw_02->BOQ_PRICE;
        	$JOB_BOQT 	= $rw_02->BOQ_JOBCOST;
        	//$JOB_RAPV = $rw_02->ITM_VOLM;
        	$JOB_RAPP  	= $rw_02->ITM_PRICE;
        	//$JOB_RAPT = $rw_02->ITM_BUDG;

        	if($JOB_UNIT == 'LS')
        		$JOB_RAPV 	= 1;
        	else
        		$JOB_RAPV 	= $ITM_RAPV;

        	if($JOB_RAPV == 0 || $JOB_RAPV == 0)
        		$JOB_RAPVP 	= 1;
        	else
        		$JOB_RAPVP 	= $JOB_RAPV;

        	$JOB_RAPT 	= $ITM_TOTAL;

        	$JOB_RAPP 	= $JOB_RAPT / $JOB_RAPVP;

	    	// INSERT HEADER
	        	$s_03 	= "INSERT INTO tbl_jobcreate_header (PRJCODE, JOB_NUM, JOB_PARCODE, JOB_PARDESC, JOB_UNIT, JOB_BOQV, JOB_BOQP, JOB_BOQT,
	        				JOB_RAPV, JOB_RAPP, JOB_RAPT, JOB_NOTE, JOB_STAT) VALUES
	        				('$PRJCODE', '$JOB_NUM', '$JOBPARENT', '$JOBDESCH', '$JOB_UNIT', '$JOB_BOQV', '$JOB_BOQP', '$JOB_BOQT',
	        				'$JOB_RAPV', '$JOB_RAPP', '$JOB_RAPT', '', 1)";
	        	$this->db->query($s_03);
        endforeach;

        $s_00 		= "tbl_jobcreate_detail WHERE JOB_NUM = '$JOB_NUM' AND PRJCODE =  '$PRJCODE'";
		return $this->db->count_all($s_00);
	}
	
	function get_AllDataRAPDC_2($PRJCODE, $JOBPARENT, $search) // GOOD
	{
		$sql 	= "tbl_joblist_detail
					WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$JOBPARENT'
						AND (JOBPARENT LIKE '%$search%' ESCAPE '!' OR ITM_CODE LIKE '%$search%' ESCAPE '!'
						OR JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataRAPDL_2($PRJCODE, $JOBPARENT, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_joblist_detail
						WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$JOBPARENT'
							AND (JOBPARENT LIKE '%$search%' ESCAPE '!' OR ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT * FROM tbl_joblist_detail
						WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$JOBPARENT'
							AND (JOBPARENT LIKE '%$search%' ESCAPE '!' OR ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_joblist_detail
						WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$JOBPARENT'
							AND (JOBPARENT LIKE '%$search%' ESCAPE '!' OR ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT * FROM tbl_joblist_detail
						WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$JOBPARENT'
							AND (JOBPARENT LIKE '%$search%' ESCAPE '!' OR ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function updRAPH($JOB_NUM, $jobAddH) // OK
	{
		$this->db->where('JOB_NUM', $JOB_NUM);
		$this->db->update('tbl_jobcreate_header', $jobAddH);
	}
}
?>