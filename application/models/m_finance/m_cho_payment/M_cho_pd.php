<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 05 Maret 2022
	* File Name		= M_cho_pd.php
	* Location		= -
	* Notes			= M_cho_pd -> Cash Project
*/

class M_cho_pd extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $jrnType, $search) // GOOD
	{
		if($jrnType == 'VCASH')
			$tblName 	= 'tbl_journalheader_vcash';
		else
			$tblName 	= 'tbl_journalheader_pd';

		$sql = "$tblName A
					LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
				WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE'
					AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
					OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
					OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $jrnType, $search, $length, $start, $order, $dir) // GOOD
	{
		if($jrnType == 'VCASH')
			$tblName 	= 'tbl_journalheader_vcash';
		else
			$tblName 	= 'tbl_journalheader_pd';

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM $tblName A
							LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
						WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE'
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir, Created $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM $tblName A
							LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
						WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE'
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM $tblName A
							LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
						WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE'
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir, Created $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM $tblName A
							LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
						WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE'
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataPDGRPC($PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $GEJ_CATEG, $search) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.PERSL_EMPID = '$SPLCODE'";
		if($GEJ_STAT != 0)
			$ADDQRY2 	= "AND A.GEJ_STAT = '$GEJ_STAT'";
		if($GEJ_CATEG != '')
			$ADDQRY3 	= "AND A.GEJ_CATEG = '$GEJ_CATEG'";

		if($jrnType == 'VCASH')
			$tblName 	= 'tbl_journalheader_vcash';
		else
			$tblName 	= 'tbl_journalheader_pd';

		$sql = "$tblName A
					LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
				WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE' $ADDQRY1 $ADDQRY2
					AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
					OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
					OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataPDGRPL($PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $GEJ_CATEG, $search, $length, $start, $order, $dir) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.PERSL_EMPID = '$SPLCODE'";
		if($GEJ_STAT != 0)
			$ADDQRY2 	= "AND A.GEJ_STAT = '$GEJ_STAT'";
		if($GEJ_CATEG != '')
			$ADDQRY3 	= "AND A.GEJ_CATEG = '$GEJ_CATEG'";

		if($jrnType == 'VCASH')
			$tblName 	= 'tbl_journalheader_vcash';
		else
			$tblName 	= 'tbl_journalheader_pd';

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM $tblName A
							LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
						WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir, Created $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM $tblName A
							LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
						WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM $tblName A
							LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
						WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir, Created $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM $tblName A
							LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
						WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataGRPC($PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $search) // GOOD
	{
		if($SPLCODE == '')
			$qry1 	= "";
		else
			$qry1	= "AND A.PERSL_EMPID = '$SPLCODE'";
		
		if($GEJ_STAT == 0)
			$qry2 	= "";
		else
			$qry2	= "AND (A.GEJ_STAT = $GEJ_STAT OR A.GEJ_STAT_VCASH = $GEJ_STAT)";

		$sql = "tbl_journalheader A
					LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
				WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE' $qry1 $qry2
					AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
					OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
					OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataGRPL($PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $search, $length, $start, $order, $dir) // GOOD
	{
		if($SPLCODE == '')
			$qry1 	= "";
		else
			$qry1	= "AND A.PERSL_EMPID = '$SPLCODE'";
		
		if($GEJ_STAT == 0)
			$qry2 	= "";
		else
			$qry2	= "AND (A.GEJ_STAT = $GEJ_STAT OR A.GEJ_STAT_VCASH = $GEJ_STAT)";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_journalheader_vcash A
							LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
						WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE' $qry1 $qry2
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir, Created $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_journalheader_vcash A
							LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
						WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE' $qry1 $qry2
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_journalheader_vcash A
							LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
						WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE' $qry1 $qry2
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir, Created $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_journalheader_vcash A
							LEFT JOIN tbl_supplier B ON A.PERSL_EMPID = B.SPLCODE
						WHERE A.JournalType = '$jrnType' AND proj_Code = '$PRJCODE' $qry1 $qry2
							AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.JournalH_Desc LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_GEJ($PRJCODE, $key) // G
	{
		if($key == '')
		{
			$sql = "tbl_journalheader_pd WHERE JournalType = 'CHO-PD' AND proj_Code = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_journalheader_pd 
					WHERE JournalType = 'CHO-PD' AND proj_Code = '$PRJCODE'
						AND (Manual_No LIKE '%$key%' ESCAPE '!' OR JournalH_Code LIKE '%$key%' ESCAPE '!' 
						OR JournalH_Desc LIKE '%$key%' ESCAPE '!' OR SPLDESC LIKE '%$key%')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_GEJ($PRJCODE, $start, $end, $key) // G
	{
		if($key == '')
		{
			$sql = "SELECT JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Emp_ID, approve_by, Journal_Amount, GEJ_STAT,
						Manual_No
					FROM tbl_journalheader_pd WHERE JournalType = 'CHO-PD' AND proj_Code = '$PRJCODE'
						LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Emp_ID, approve_by, Journal_Amount, GEJ_STAT,
						Manual_No
					FROM tbl_journalheader_pd 
						WHERE JournalType = 'CHO-PD' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$key%' ESCAPE '!' OR JournalH_Code LIKE '%$key%' ESCAPE '!' 
								OR JournalH_Desc LIKE '%$key%' ESCAPE '!' OR SPLDESC LIKE '%$key%')
						LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // G
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_COA($PRJCODE, $Emp_ID) // G
	{
		$sql		= "tbl_employee_acc A
						INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						WHERE A.Emp_ID = '$Emp_ID'";
		return $this->db->count_all($sql);
	}
	
	function view_all_COA($PRJCODE, $Emp_ID, $acc_number) // G
	{
		if($acc_number == '')
		{
			$sql	= "SELECT DISTINCT B.Account_Number, B.Account_NameEn, B.Account_NameId, B.Account_Class,
							B.Base_OpeningBalance, B.Base_Debet, B.Base_Kredit
						FROM tbl_employee_acc A
						INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						WHERE B.PRJCODE = '$PRJCODE' AND A.Emp_ID = '$Emp_ID' ORDER BY B.Account_Number";
		}
		else
		{
			$sql	= "SELECT DISTINCT B.Account_Number, B.Account_NameEn, B.Account_NameId, B.Account_Class,
							B.Base_OpeningBalance, B.Base_Debet, B.Base_Kredit
						FROM tbl_employee_acc A
						INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						WHERE B.PRJCODE = '$PRJCODE' AND (A.Emp_ID = '$Emp_ID' OR A.Acc_Number = '$acc_number') ORDER BY B.Account_Number";
		}
		return $this->db->query($sql);
	}
	
	function count_all_Account($PRJCODE) // G
	{
		$sql		= "tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
							AND B.ITM_GROUP != 'M'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP != 'M'
							AND A.ISLAST = 1
						 	-- AND B.ACC_ID != ''
						 ";
		return $this->db->count_all($sql);
	}
	
	function view_all_Account($PRJCODE) // G
	{
		$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.JOBDESC, A.PRJCODE, 
						A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_VOLM, 
						A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
						A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
						B.ITM_NAME, B.ACC_ID_UM AS ACC_ID, B.ITM_GROUP, B.ITM_CATEG
						FROM tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
							AND B.ITM_GROUP != 'M'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP != 'M'
							AND A.ISLAST = 1
						 	-- AND B.ACC_ID != ''
						";
							
		return $this->db->query($sql);
	}
	
	function add($projGEJH) // G
	{
		$this->db->insert('tbl_journalheader', $projGEJH);
	}
	
	function add_pd($projGEJH) // G
	{
		$this->db->insert('tbl_journalheader_pd', $projGEJH);
	}
	
	function add_vcash($projGEJH) // G
	{
		$this->db->insert('tbl_journalheader_vcash', $projGEJH);
	}

	function addTLK($itmTLK)
	{
		$this->db->insert('tbl_tsflk', $itmTLK);
	}
	
	function get_CHO_by_number($JournalH_Code) // OK
	{
		$sql = "SELECT * FROM tbl_journalheader_pd WHERE JournalH_Code = '$JournalH_Code'";
		return $this->db->query($sql);
	}
	
	function get_VCASH_by_number($JournalH_Code) // OK
	{
		$sql = "SELECT * FROM tbl_journalheader_vcash WHERE JournalH_Code = '$JournalH_Code'";
		return $this->db->query($sql);
	}

	function get_TLK_by_number($TLK_NUM) // OK
	{
		$sql = "SELECT * FROM tbl_tsflk WHERE TLK_NUM = '$TLK_NUM'";
		return $this->db->query($sql);
	}
	
	function update($PR_NUM, $projMatReqH) // OK
	{
		$this->db->where('PR_NUM', $PR_NUM);
		$this->db->update('tbl_pr_header', $projMatReqH);
	}
	
	function updateCHO($JournalH_Code, $projGEJH) // G
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->update('tbl_journalheader', $projGEJH);
	}
	
	function updateCHO_pd($JournalH_Code, $projGEJH) // G
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->update('tbl_journalheader_pd AA', $projGEJH);
	}
	
	function updateCHO_vcash($JournalH_Code, $projGEJH) // G
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->update('tbl_journalheader_vcash', $projGEJH);
	}

	function updateTLK($itmTLK, $TLK_NUM)
	{
		$this->db->where('TLK_NUM', $TLK_NUM);
		$this->db->update('tbl_tsflk', $itmTLK);
	}
	
	function deleteCPRJDetail($JournalH_Code) // G
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->delete('tbl_journaldetail');
	}
	
	function deleteCPRJDetail_pd($JournalH_Code) // G
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->delete('tbl_journaldetail_pd');
	}
	
	function deleteCPRJDetail_vcash($JournalH_Code) // G
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->delete('tbl_journaldetail_vcash');
	}
	
	function count_all_GEJOTH($PRJCODE, $key) // G
	{
		if($key == '')
		{
			$sql = "tbl_journalheader WHERE JournalType = 'O-EXP' AND proj_Code = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_journalheader 
					WHERE JournalType = 'O-EXP' AND proj_Code = '$PRJCODE'
						AND (Manual_No LIKE '%$key%' ESCAPE '!' OR JournalH_Code LIKE '%$key%' ESCAPE '!' 
						OR JournalH_Desc LIKE '%$key%' ESCAPE '!' OR SPLDESC LIKE '%$key%')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_GEJOTH($PRJCODE, $start, $end, $key) // G
	{
		if($key == '')
		{
			$sql = "SELECT JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Emp_ID, approve_by, Journal_Amount, GEJ_STAT,
						Manual_No
					FROM tbl_journalheader WHERE JournalType = 'O-EXP' AND proj_Code = '$PRJCODE'
						LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Emp_ID, approve_by, Journal_Amount, GEJ_STAT,
						Manual_No
					FROM tbl_journalheader 
						WHERE JournalType = 'O-EXP' AND proj_Code = '$PRJCODE'
							AND (Manual_No LIKE '%$key%' ESCAPE '!' OR JournalH_Code LIKE '%$key%' ESCAPE '!' 
								OR JournalH_Desc LIKE '%$key%' ESCAPE '!' OR SPLDESC LIKE '%$key%')
						LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function count_all_AccountyXP($PRJCODE) // OK
	{
		$sql		= "tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE  = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP != 'M'";
		return $this->db->count_all($sql);
	}
	
	function view_all_AccountyXP($PRJCODE) // OK
	{
		$sql		= "SELECT DISTINCT A.JOBCODEID, A.PRJCODE, A.JOBDESC,
							B.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM AS ITM_VOLMBG, A.ITM_PRICE, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_JOBCOST, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.WO_QTY AS PR_VOLM, A.WO_AMOUNT AS PR_AMOUNT, A.OPN_QTY AS PO_VOLM, A.OPN_AMOUNT AS PO_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM,
							B.ITM_VOLM AS ITM_STOCK, B.ITM_LASTP, 
							B.ITM_NAME, B.ACC_ID_UM AS ACC_ID, 'JOBL' AS FRM
						FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE  = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP != 'M'
						UNION ALL
						SELECT DISTINCT JOBCODEID, A.PRJCODE, '' AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLMBG, A.ITM_PRICE, A.ITM_TOTALP AS ITM_BUDG,
							A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_JOBCOST, A.ADDMVOLM AS ADDM_VOLM, A.ADDMCOST AS ADDM_JOBCOST,
							A.PR_VOLM, A.PR_AMOUNT, A.PO_VOLM, A.PO_AMOUNT,
							A.UM_VOLM AS ITM_USED, A.UM_AMOUNT AS ITM_USED_AM,
							A.ITM_VOLM AS ITM_STOCK, A.ITM_LASTP, 
							A.ITM_NAME, A.ACC_ID_UM AS ACC_ID, 'ITML' AS FRM
						FROM tbl_item A 
						WHERE A.PRJCODE = '$PRJCODE' 
							AND A.ITM_GROUP != 'M' AND A.JOBCODEID != ''";
		return $this->db->query($sql);
	}
	
	function count_all_AccountyXI($PRJCODE) // OK
	{
		$sql		= "tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE  = '$PRJCODE' AND B.ITM_GROUP = 'I'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP = 'I'";
		return $this->db->count_all($sql);
	}
	
	function view_all_AccountyXI($PRJCODE) // OK
	{
		$sql		= "SELECT DISTINCT A.JOBCODEID, A.PRJCODE, A.JOBDESC,
							B.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM AS ITM_VOLMBG, A.ITM_PRICE, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_JOBCOST, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.WO_QTY AS PR_VOLM, A.WO_AMOUNT AS PR_AMOUNT, A.OPN_QTY AS PO_VOLM, A.OPN_AMOUNT AS PO_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM,
							B.ITM_VOLM AS ITM_STOCK, B.ITM_LASTP, 
							B.ITM_NAME, B.ACC_ID_UM AS ACC_ID, 'JOBL' AS FRM
						FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE  = '$PRJCODE' AND B.ITM_GROUP = 'I'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP = 'I'
						UNION ALL
						SELECT DISTINCT JOBCODEID, A.PRJCODE, '' AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLMBG, A.ITM_PRICE, A.ITM_TOTALP AS ITM_BUDG,
							A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_JOBCOST, A.ADDMVOLM AS ADDM_VOLM, A.ADDMCOST AS ADDM_JOBCOST,
							A.PR_VOLM, A.PR_AMOUNT, A.PO_VOLM, A.PO_AMOUNT,
							A.UM_VOLM AS ITM_USED, A.UM_AMOUNT AS ITM_USED_AM,
							A.ITM_VOLM AS ITM_STOCK, A.ITM_LASTP, 
							A.ITM_NAME, A.ACC_ID_UM AS ACC_ID, 'ITML' AS FRM
						FROM tbl_item A 
						WHERE A.PRJCODE = '$PRJCODE' 
							AND A.ITM_GROUP = 'I' AND A.JOBCODEID != ''";
		return $this->db->query($sql);
	}
	
	function get_AllDataITMC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_joblist_detail A
					INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE AND B.ACC_ID_UM != ''
				WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
					OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE AND B.ACC_ID_UM != ''
						WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE AND B.ACC_ID_UM != ''
						WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE AND B.ACC_ID_UM != ''
						WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE AND B.ACC_ID_UM != ''
						WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllData_VTLKC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_tsflk
				WHERE PRJCODE = '$PRJCODE'
					AND (TLK_NUM LIKE '%$search%' ESCAPE '!' OR TLK_CODE LIKE '%$search%' ESCAPE '!' OR TLK_DESC LIKE '%$search%' ESCAPE '!'
					OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
		return $this->db->count_all($sql);
	}
	
	function get_AllData_VTLKL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT *
						FROM tbl_tsflk
						WHERE PRJCODE = '$PRJCODE'
							AND (TLK_NUM LIKE '%$search%' ESCAPE '!' OR TLK_CODE LIKE '%$search%' ESCAPE '!'
							OR TLK_DESC LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT *
						FROM tbl_tsflk
						WHERE PRJCODE = '$PRJCODE'
							AND (TLK_NUM LIKE '%$search%' ESCAPE '!' OR TLK_CODE LIKE '%$search%' ESCAPE '!'
							OR TLK_DESC LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT *
						FROM tbl_tsflk
						WHERE PRJCODE = '$PRJCODE'
							AND (TLK_NUM LIKE '%$search%' ESCAPE '!' OR TLK_CODE LIKE '%$search%' ESCAPE '!'
							OR TLK_DESC LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT *
						FROM tbl_tsflk
						WHERE PRJCODE = '$PRJCODE'
							AND (TLK_NUM LIKE '%$search%' ESCAPE '!' OR TLK_CODE LIKE '%$search%' ESCAPE '!'
							OR TLK_DESC LIKE '%$search%' ESCAPE '!'
							OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%')LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataPOC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_po_header A
                	INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
                WHERE A.PO_STAT = 3
					AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!' 
					OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataPOL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PO_CREATER, A.PO_APPROVER, 
                            A.JOBCODE, A.PO_NOTES, A.PO_STAT, A.PO_MEMO, A.PO_PLANIR, A.SPLCODE,
                            B.SPLDESC
                        FROM tbl_po_header A
                        	INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
                        WHERE A.PO_STAT = 3
							AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PO_CREATER, A.PO_APPROVER, 
                            A.JOBCODE, A.PO_NOTES, A.PO_STAT, A.PO_MEMO, A.PO_PLANIR, A.SPLCODE,
                            B.SPLDESC
                        FROM tbl_po_header A
                        	INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
                        WHERE A.PO_STAT = 3
							AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PO_CREATER, A.PO_APPROVER, 
                            A.JOBCODE, A.PO_NOTES, A.PO_STAT, A.PO_MEMO, A.PO_PLANIR, A.SPLCODE,
                            B.SPLDESC
                        FROM tbl_po_header A
                        	INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
                        WHERE A.PO_STAT = 3
							AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PO_CREATER, A.PO_APPROVER, 
                            A.JOBCODE, A.PO_NOTES, A.PO_STAT, A.PO_MEMO, A.PO_PLANIR, A.SPLCODE,
                            B.SPLDESC
                        FROM tbl_po_header A
                        	INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
                        WHERE A.PO_STAT = 3
							AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataWOC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_wo_header A
                    INNER JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
                WHERE A.WO_STAT = '3'
					AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' 
					OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
					OR A.WO_VALUE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataWOL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_STARTD, A.WO_ENDD,
        					A.WO_CREATER, A.WO_APPROVER,  A.JOBCODEID, A.WO_NOTE, A.WO_STAT, A.WO_MEMO,
                            CONCAT (B.First_Name,' ', B.Last_Name) AS complName,
                            C.PRJCODE, C.PRJNAME
						FROM tbl_wo_header A
		                    INNER JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
		                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
		                WHERE A.WO_STAT = '3'
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_VALUE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_STARTD, A.WO_ENDD,
        					A.WO_CREATER, A.WO_APPROVER,  A.JOBCODEID, A.WO_NOTE, A.WO_STAT, A.WO_MEMO,
                            CONCAT (B.First_Name,' ', B.Last_Name) AS complName,
                            C.PRJCODE, C.PRJNAME
						FROM tbl_wo_header A
		                    INNER JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
		                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
		                WHERE A.WO_STAT = '3'
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_VALUE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_STARTD, A.WO_ENDD,
        					A.WO_CREATER, A.WO_APPROVER,  A.JOBCODEID, A.WO_NOTE, A.WO_STAT, A.WO_MEMO,
                            CONCAT (B.First_Name,' ', B.Last_Name) AS complName,
                            C.PRJCODE, C.PRJNAME
						FROM tbl_wo_header A
		                    INNER JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
		                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
		                WHERE A.WO_STAT = '3'
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_VALUE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_STARTD, A.WO_ENDD,
        					A.WO_CREATER, A.WO_APPROVER,  A.JOBCODEID, A.WO_NOTE, A.WO_STAT, A.WO_MEMO,
                            CONCAT (B.First_Name,' ', B.Last_Name) AS complName,
                            C.PRJCODE, C.PRJNAME
						FROM tbl_wo_header A
		                    INNER JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
		                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
		                WHERE A.WO_STAT = '3'
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_VALUE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>