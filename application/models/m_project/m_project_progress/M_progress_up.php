<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 6 Mei 2018
 * File Name	= M_progress_up.php
 * Location		= -
*/

class M_progress_up extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_project_progress A 
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
					OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
		
	function count_all_WP($PRJCODE) // G
	{
		$sql	= "tbl_project_progress WHERE PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_all_WP($PRJCODE) // G
	{
		$sql = "SELECT * FROM tbl_project_progress WHERE PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function count_all_Job($PRJCODE) // G
	{
		//$sql		= "tbl_joblist WHERE PRJCODE = '$PRJCODE' AND ISHEADER = 1 AND BOQ_PRICE > 0 AND JOBLEV > 1";
		$sql		= "tbl_joblist WHERE PRJCODE = '$PRJCODE' AND ISBOBOT = 1";
		return $this->db->count_all($sql);
	}
	
	function view_all_Job($PRJCODE) // G
	{
		/*$sql		= "SELECT DISTINCT JOBCODEID, JOBCODEIDV, PRJCODE, JOBDESC, JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST
						FROM tbl_joblist
						WHERE PRJCODE = '$PRJCODE' AND ISHEADER = 1 AND BOQ_PRICE > 0 AND JOBLEV > 1";*/
		$sql		= "SELECT DISTINCT JOBCODEID, JOBCODEIDV, PRJCODE, JOBDESC, JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, 
							BOQ_JOBCOST, BOQ_BOBOT, JOBUNIT
						FROM tbl_joblist
						WHERE PRJCODE = '$PRJCODE' AND ISBOBOT = 1";
		return $this->db->query($sql);
	}
	
	function add($paramPRJP) // G
	{
		$this->db->insert('tbl_project_progress', $paramPRJP);
	}
	
	function get_PRJP_by_number($PRJP_NUM) // G
	{
		$sql = "SELECT * FROM tbl_project_progress WHERE PRJP_NUM = '$PRJP_NUM'";
		return $this->db->query($sql);
	}
	
	function updatePRPJ($PRJP_NUM, $paramPRJP) // G
	{
		$this->db->where('PRJP_NUM', $PRJP_NUM);
		$this->db->update('tbl_project_progress', $paramPRJP);
	}
	
	function deletePRPJDet($PRJP_NUM) // G
	{
		$this->db->where('PRJP_NUM', $PRJP_NUM);
		$this->db->delete('tbl_project_progress_det');
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_COA($PRJCODE, $Emp_ID) // OK
	{
		$sql		= "tbl_employee_acc A
						INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						WHERE A.Emp_ID = '$Emp_ID' AND B.PRJCODE = '$PRJCODE' AND B.isLast = '1'";
		return $this->db->count_all($sql);
	}
	
	function view_all_COA($PRJCODE, $Emp_ID) // OK
	{
		$sql		= "SELECT DISTINCT B.Account_Number, B.Account_NameEn, B.Account_NameId, B.Account_Class, B.PRJCODE, 
							B.Base_OpeningBalance, B.Base_Debet, B.Base_Kredit
						FROM tbl_employee_acc A
						INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						WHERE A.Emp_ID = '$Emp_ID' AND B.PRJCODE = '$PRJCODE' AND B.isLast = '1'";
		return $this->db->query($sql);
	}
	
	function updateDet($PR_NUM, $PRJCODE, $PR_DATE) // OK
	{
		$sql = "UPDATE tbl_pr_detail SET PRJCODE = '$PRJCODE', PR_DATE = '$PR_DATE' WHERE PR_NUM = '$PR_NUM'";
		return $this->db->query($sql);
	}
	
	function update($PR_NUM, $projMatReqH) // OK
	{
		$this->db->where('PR_NUM', $PR_NUM);
		$this->db->update('tbl_pr_header', $projMatReqH);
	}
	
	function deleteDetail($PR_NUM) // OK
	{
		$this->db->where('PR_NUM', $PR_NUM);
		$this->db->delete('tbl_pr_detail');
	}
	
	function updateBOQPROG($PRJCODE, $JOBCODEID, $PRJP_TOT, $PRJP_STEP, $PROG_PERC, $PROG_VAL) // OK
	{
		$sql = "UPDATE tbl_joblist SET BOQ_PROGR = $PRJP_TOT WHERE JOBCODEID = '$JOBCODEID'";
		$this->db->query($sql);
		
		// CEK REAL AKUM BEFORE
		$PRJP_STEPBEF	= $PRJP_STEP - 1;
		if($PRJP_STEP == 1)
		{
			$Prg_PlanAkum	= 0;
			$REALAKUM_BEF	= 0;
		}
		else
		{
			$Prg_PlanAkum	= 0;
			$REALAKUM_BEF	= 0;
			$sqlREALBEF		= "SELECT Prg_PlanAkum, Prg_RealAkum FROM tbl_projprogres
								WHERE proj_Code = '$PRJCODE' AND Prg_Step = $PRJP_STEPBEF";
			$resREALBEF 	= $this->db->query($sqlREALBEF)->result();
			foreach($resREALBEF as $rowREALBEF) :
				$Prg_PlanAkum = $rowREALBEF->Prg_PlanAkum;
				$REALAKUM_BEF = $rowREALBEF->Prg_RealAkum;
			endforeach;
		}
		
		$Prg_LastUpdate		= date('Y-m-d H:i:s');
		$Prg_RealAkumBef	= $REALAKUM_BEF;
		$Prg_RealAkumNow	= $PRJP_TOT;
		$Prg_RealNow		= $Prg_RealAkumNow - $Prg_RealAkumBef;
		$Prg_Dev			= $Prg_RealAkumNow - $Prg_PlanAkum;
		
		$s_00 = "UPDATE tbl_projprogres SET Prg_Real = $Prg_RealNow, Prg_RealAkum = $Prg_RealAkumNow, Prg_Dev = $Prg_Dev,
					isShowRA = 1, isShowDev = 1, lastStepPS =1, Prg_LastUpdate = '$Prg_LastUpdate'
				WHERE proj_Code = '$PRJCODE' AND Prg_Step = $PRJP_STEP";
		$this->db->query($s_00);

		if($PROG_PERC > 0)
		{
			$s_01 = "UPDATE tbl_joblist SET BOQ_BOBOT_PI = BOQ_BOBOT_PI + $PROG_PERC, BOQ_BOBOT_PIV = BOQ_BOBOT_PIV + $PROG_VAL
						WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
			$this->db->query($s_01);
		}
	}
	
	function updateBOQPROG_EKS($PRJCODE, $JOBCODEID, $PROG_PERC_EKS, $PROG_VAL_EKS) // OK
	{
		$s_01 = "UPDATE tbl_joblist SET BOQ_BOBOT_PIEKS = BOQ_BOBOT_PIEKS + $PROG_PERC_EKS, BOQ_BOBOT_PIVEKS = BOQ_BOBOT_PIVEKS + $PROG_VAL_EKS
					WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
		$this->db->query($s_01);
	}
	
	function add_importprogg($ProggHist) // OK
	{
		$this->db->insert('tbl_progg_uphist', $ProggHist);
	}
	
	function get_AllDataEkstC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_project_progress A 
				WHERE A.PRJCODE = '$PRJCODE' AND A.PRJP_STAT = 3
					AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
					OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataEkstL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.PRJP_STAT = 3
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.PRJP_STAT = 3
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.PRJP_STAT = 3
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.PRJP_STAT = 3
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>