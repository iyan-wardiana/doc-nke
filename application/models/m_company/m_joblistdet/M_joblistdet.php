<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Maret 2017
 * File Name	= M_joblistdet.php
 * Location		= -
*/

class M_joblistdet extends CI_Model
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
		//$sql = "tbl_boqlist WHERE PRJCODE = '$PRJCODE' AND JOBLEV = 1";
		$sql = "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND IS_LEVEL = 1";
		return $this->db->count_all($sql);
	}
	
	function get_all_joblist($PRJCODE) // OK
	{
		//$sql = "SELECT * FROM tbl_boqlist WHERE PRJCODE = '$PRJCODE' AND JOBLEV = 1 ORDER BY JOBCODEID";
		$sql = "SELECT * FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND IS_LEVEL = 1 AND WBSD_STAT = 1 ORDER BY JOBCODEID";
		return $this->db->query($sql);
	}
	
	function get_project_name($PRJCODE) // OK
	{
		$sql = "SELECT * FROM tbl_project WHERE PRJCODE = '$PRJCODE'";			
		return $this->db->query($sql);
	}
	
	function count_all_JOB($PRJCODE, $JOB_CODE) // OK
	{	
		$sql	= "tbl_boqlist WHERE PRJCODE = '$PRJCODE' AND JOBCOD1 = '$JOB_CODE'";
		return $this->db->count_all($sql);
	}
	
	function add($joblist) // OK
	{
		$this->db->insert('tbl_boqlist', $joblist);
	}
	
	function addBOQ($boqlist) // OK
	{
		$this->db->insert('tbl_boqlist', $boqlist);
	}
	
	function addJOB($joblist) // OK
	{
		$this->db->insert('tbl_joblist', $joblist);
	}
	
	function addJOBDET($joblistD) // OK
	{
		$this->db->insert('tbl_joblist_detail', $joblistD);
	}
	
	function get_joblist_by_code($JOBCODEID) // OK
	{
		if($JOBCODEID == '')
		{
			$sql = "SELECT * FROM tbl_boqlist";
		}
		else
		{
			$sql = "SELECT * FROM tbl_boqlist WHERE JOBCODEID = '$JOBCODEID'";
		}
		return $this->db->query($sql);
	}
	
	function update($JOBCODEID, $joblist) // OK
	{
		$this->db->where('JOBCODEID', $JOBCODEID);
		$this->db->update('tbl_boqlist', $joblist);
	}
	
	function count_all_job1() // OK
	{
		return $this->db->count_all('tbl_boqlist');
	}
	
	function get_all_job1() // OK
	{		
		$sql = "SELECT * FROM tbl_boqlist WHERE JOBPARENT = '0'";
		return $this->db->query($sql);
	}
	
	function updateParent($JOBPARENT, $JOBCODEID) // OK
	{
		if($JOBPARENT == 0)
		{
			$sqlUTOTC1		= "UPDATE tbl_boqlist SET ISHEADER = 1 WHERE JOBCODEID = '$JOBCODEID'";
			return $this->db->query($sqlUTOTC1);
		}
		else
		{
			$TOTCOST		= 0;
			$sqlTOTC		= "SELECT SUM(JOBCOST) AS TOTCOST FROM tbl_boqlist WHERE JOBPARENT = '$JOBPARENT'";
			$resTOTC 		= $this->db->query($sqlTOTC)->result();
			foreach($resTOTC as $rowTOTC) :
				$TOTCOST	= $rowTOTC->TOTCOST;
			endforeach;
			
			$sqlUTOTC		= "UPDATE tbl_boqlist SET JOBCOST = $TOTCOST, ISHEADER = 1 WHERE JOBCODEID = '$JOBPARENT'";
			$this->db->query($sqlUTOTC);
			
			// CEK NEXT UP 1 LEVEL PARENT
			$JOBPARENT2		= '';
			$sqlGETP2C		= "tbl_boqlist WHERE JOBCODEID = '$JOBPARENT'";
			$resGETP2C 		= $this->db->count_all($sqlGETP2C);
			
			if($resGETP2C > 0)
			{
				$sqlGETP2		= "SELECT JOBPARENT FROM tbl_boqlist WHERE JOBCODEID = '$JOBPARENT'";
				$resGETP2 		= $this->db->query($sqlGETP2)->result();
				foreach($resGETP2 as $rowP2) :
					$JOBPARENT2	= $rowP2->JOBPARENT;
				endforeach;
				
				$TOTCOST2		= 0;
				$sqlTOTC2		= "SELECT SUM(JOBCOST) AS TOTCOST2 FROM tbl_boqlist WHERE JOBPARENT = '$JOBPARENT2'";
				$resTOTC2 		= $this->db->query($sqlTOTC2)->result();
				foreach($resTOTC2 as $rowTOTC2) :
					$TOTCOST2	= $rowTOTC2->TOTCOST2;
				endforeach;
				
				$sqlUTOTC2		= "UPDATE tbl_boqlist SET JOBCOST = $TOTCOST2, ISHEADER = 1 WHERE JOBCODEID = '$JOBPARENT2'";
				$this->db->query($sqlUTOTC2);
				
				// CEK NEXT UP 2 LEVEL PARENT
				$JOBPARENT3		= '';
				$sqlGETP3C		= "tbl_boqlist WHERE JOBCODEID = '$JOBPARENT2'";
				$resGETP3C 		= $this->db->count_all($sqlGETP3C);
				if($resGETP3C > 0)
				{
					$sqlGETP3		= "SELECT JOBPARENT FROM tbl_boqlist WHERE JOBCODEID = '$JOBPARENT2'";
					$resGETP3 		= $this->db->query($sqlGETP3)->result();
					foreach($resGETP3 as $rowP3) :
						$JOBPARENT3	= $rowP3->JOBPARENT;
					endforeach;
					
					$TOTCOST3		= 0;
					$sqlTOTC3		= "SELECT SUM(JOBCOST) AS TOTCOST3 FROM tbl_boqlist WHERE JOBPARENT = '$JOBPARENT2'";
					$resTOTC3 		= $this->db->query($sqlTOTC3)->result();
					foreach($resTOTC3 as $rowTOTC3) :
						$TOTCOST3	= $rowTOTC3->TOTCOST3;
					endforeach;
					
					$sqlUTOTC3		= "UPDATE tbl_boqlist SET JOBCOST = $TOTCOST3, ISHEADER = 1 WHERE JOBCODEID = '$JOBPARENT2'";
					$this->db->query($sqlUTOTC3);
					
					// CEK NEXT UP 3 LEVEL PARENT
					$JOBPARENT4		= '';
					$sqlGETP4C		= "tbl_boqlist WHERE JOBCODEID = '$JOBPARENT3'";
					$resGETP4C 		= $this->db->count_all($sqlGETP4C);
					if($resGETP4C > 0)
					{
						$sqlGETP4		= "SELECT JOBPARENT FROM tbl_boqlist WHERE JOBCODEID = '$JOBPARENT3'";
						$resGETP4 		= $this->db->query($sqlGETP4)->result();
						foreach($resGETP4 as $rowP4) :
							$JOBPARENT4	= $rowP4->JOBPARENT;
						endforeach;
						
						$TOTCOST4		= 0;
						$sqlTOTC4		= "SELECT SUM(JOBCOST) AS TOTCOST4 FROM tbl_boqlist WHERE JOBPARENT = '$JOBPARENT3'";
						$resTOTC4 		= $this->db->query($sqlTOTC4)->result();
						foreach($resTOTC4 as $rowTOTC4) :
							$TOTCOST4	= $rowTOTC4->TOTCOST4;
						endforeach;
						
						$sqlUTOTC4		= "UPDATE tbl_boqlist SET JOBCOST = $TOTCOST4, ISHEADER = 1 WHERE JOBCODEID = '$JOBPARENT3'";
						$this->db->query($sqlUTOTC4);
						
						// CEK NEXT UP 4 LEVEL PARENT
						$JOBPARENT5		= '';
						$sqlGETP5C		= "tbl_boqlist WHERE JOBCODEID = '$JOBPARENT4'";
						$resGETP5C 		= $this->db->count_all($sqlGETP5C);
						if($resGETP5C > 0)
						{
							$sqlGETP5		= "SELECT JOBPARENT FROM tbl_boqlist WHERE JOBCODEID = '$JOBPARENT4'";
							$resGETP5 		= $this->db->query($sqlGETP5)->result();
							foreach($resGETP5 as $rowP5) :
								$JOBPARENT5	= $rowP5->JOBPARENT;
							endforeach;
							
							$TOTCOST5		= 0;
							$sqlTOTC5		= "SELECT SUM(JOBCOST) AS TOTCOST5 FROM tbl_boqlist WHERE JOBPARENT = '$JOBPARENT4'";
							$resTOTC5 		= $this->db->query($sqlTOTC5)->result();
							foreach($resTOTC5 as $rowTOTC5) :
								$TOTCOST5	= $rowTOTC5->TOTCOST5;
							endforeach;
							
							$sqlUTOTC5		= "UPDATE tbl_boqlist SET JOBCOST = $TOTCOST5, ISHEADER = 1 WHERE JOBCODEID = '$JOBPARENT4'";
							$this->db->query($sqlUTOTC5);
							
							// CEK NEXT UP 5 LEVEL PARENT
							$JOBPARENT6		= '';
							$sqlGETP6C		= "tbl_boqlist WHERE JOBCODEID = '$JOBPARENT5'";
							$resGETP6C 		= $this->db->count_all($sqlGETP6C);
							if($resGETP6C > 0)
							{
								$sqlGETP6		= "SELECT JOBPARENT FROM tbl_boqlist WHERE JOBCODEID = '$JOBPARENT5'";
								$resGETP6 		= $this->db->query($sqlGETP6)->result();
								foreach($resGETP6 as $rowP6) :
									$JOBPARENT6	= $rowP6->JOBPARENT;
								endforeach;
								
								$TOTCOST6		= 0;
								$sqlTOTC6		= "SELECT SUM(JOBCOST) AS TOTCOST6 FROM tbl_boqlist WHERE JOBPARENT = '$JOBPARENT5'";
								$resTOTC6 		= $this->db->query($sqlTOTC6)->result();
								foreach($resTOTC6 as $rowTOTC6) :
									$TOTCOST6	= $rowTOTC6->TOTCOST6;
								endforeach;
								
								$sqlUTOTC6		= "UPDATE tbl_boqlist SET JOBCOST = $TOTCOST6, ISHEADER = 1 WHERE JOBCODEID = '$JOBPARENT5'";
								$this->db->query($sqlUTOTC6);
							}
						}
					}
				}
			}
		}
	}
}
?>