<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 02 Januari 2018
	* File Name		= M_spk.php
	* Location		= -
*/

class M_spk extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}

	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_wo_header A
				WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O')
					AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
					OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
					OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O')
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O')
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O')
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O')
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')
						LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataGRPC($PRJCODE, $SPLCODE, $WO_STAT, $WO_CATEG, $search) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.SPLCODE = '$SPLCODE'";
		if($WO_STAT != 0)
			$ADDQRY2 	= "AND A.WO_STAT = '$WO_STAT'";
		if($WO_CATEG != '')
			$ADDQRY3 	= "AND A.WO_CATEG = '$WO_CATEG'";

		$sql = "tbl_wo_header A
				WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2 $ADDQRY3
					AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
					OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
					OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataGRPL($PRJCODE, $SPLCODE, $WO_STAT, $WO_CATEG, $search, $length, $start, $order, $dir) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";
		$ADDQRY3 	= "";

		if($SPLCODE != '')
			$ADDQRY1 	= "AND A.SPLCODE = '$SPLCODE'";
		if($WO_STAT != 0)
			$ADDQRY2 	= "AND A.WO_STAT = '$WO_STAT'";
		if($WO_CATEG != '')
			$ADDQRY3 	= "AND A.WO_CATEG = '$WO_CATEG'";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2 $ADDQRY3
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')
						LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataSHC($PRJCODE, $ISCLS, $search) // GOOD
	{
		if($ISCLS == 0)		// SHOW ALL
		{
			$sql = "tbl_wo_header A
					WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O')
						AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
						OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
						OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		}
		else				// HIDE DOKUMEN YANG REJECT, VOID, CLOSE
		{$sql = "tbl_wo_header A
				WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O') AND A.WO_STAT IN (1,2,3)
					AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
					OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
					OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		}
		
		return $this->db->count_all($sql);
	}

	function get_AllDataSHL($PRJCODE, $ISCLS, $search, $length, $start, $order, $dir) // GOOD
	{
		if($ISCLS == 0)		// SHOW ALL
		{
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT A.*, '' AS JOBDESC
							FROM tbl_wo_header A 
								/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'*/
							WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O')
								AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
								OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')
							ORDER BY $order $dir";
				}
				else
				{
					$sql = "SELECT A.*, '' AS JOBDESC
							FROM tbl_wo_header A
								/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'*/
							WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O')
								AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
								OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT A.*, '' AS JOBDESC
							FROM tbl_wo_header A
								/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'*/
							WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O')
								AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
								OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')
							ORDER BY $order $dir
							LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT A.*, '' AS JOBDESC
							FROM tbl_wo_header A
								/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'*/
							WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O')
								AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
								OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')
							LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
		else				// HIDE DOKUMEN YANG REJECT, VOID, CLOSE
		{
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT A.*, '' AS JOBDESC
							FROM tbl_wo_header A 
								/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'*/
							WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O') AND A.WO_STAT IN (1,2,3)
								AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
								OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')
							ORDER BY $order $dir";
				}
				else
				{
					$sql = "SELECT A.*, '' AS JOBDESC
							FROM tbl_wo_header A
								/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'*/
							WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O') AND A.WO_STAT IN (1,2,3)
								AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
								OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT A.*, '' AS JOBDESC
							FROM tbl_wo_header A
								/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'*/
							WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O') AND A.WO_STAT IN (1,2,3)
								AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
								OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')
							ORDER BY $order $dir
							LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT A.*, '' AS JOBDESC
							FROM tbl_wo_header A
								/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'*/
							WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG IN ('U','A','S','O') AND A.WO_STAT IN (1,2,3)
								AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
								OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')
							LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
	}

	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_wo_header A
				WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'U' AND A.WO_STAT IN (2,7)
					AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
					OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'U' AND A.WO_STAT IN (2,7)
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'U' AND A.WO_STAT IN (2,7)
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'U' AND A.WO_STAT IN (2,7)
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'U' AND A.WO_STAT IN (2,7)
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function count_all_WO($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			$sql = "tbl_wo_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_wo_header A
						LEFT JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (WO_NUM LIKE '%$key%' ESCAPE '!' OR WO_CODE LIKE '%$key%' ESCAPE '!'
						OR WO_DATE LIKE '%$key%' ESCAPE '!' OR WO_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}

	function get_all_WO($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_CATEG, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_NOTE2, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE, A.WO_CDOC,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_wo_header A
						LEFT JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_CATEG, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_NOTE2, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE, A.WO_CDOC,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_wo_header A
						LEFT JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (WO_NUM LIKE '%$key%' ESCAPE '!' OR WO_CODE LIKE '%$key%' ESCAPE '!'
						OR WO_DATE LIKE '%$key%' ESCAPE '!' OR WO_NOTE LIKE '%$key%' ESCAPE '!')
					LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}

	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}

	function count_all_ItemServ($PRJCODE, $JOBCODE, $PGFROM) // OK
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		//return $this->db->count_all('tbl_cost');
		if(trim($JOBCODE) == "''")
			$QRYJBCODY	= "";
		else
			$QRYJBCODY	= "AND JOBPARENT IN ($JOBCODE)";

		if($PGFROM == 'SALT')
		{
			$sql		= "vw_joblist_detail_$PRJCODEVW WHERE PRJCODE = '$PRJCODE' AND ITM_GROUP NOT IN ('M') AND ISLAST = 1 $QRYJBCODY";
		}
		else
		{
			$sql		= "vw_joblist_detail_$PRJCODEVW WHERE PRJCODE = '$PRJCODE' AND ITM_GROUP NOT IN ('M') AND ISLAST = 1 $QRYJBCODY";
		}		
		return $this->db->count_all($sql);

	}

	function viewAllItemServ($PRJCODE, $JOBCODE, $PGFROM) // OK
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		if(trim($JOBCODE) == "''")
			$QRYJBCODY	= "";
		else
			$QRYJBCODY	= "AND JOBPARENT IN ($JOBCODE)";

		if($PGFROM == 'SALT')
		{
			$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE,
							A.ITM_VOLM, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED,
							A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG, A.WO_QTY, A.WO_AMOUNT, A.OPN_QTY,
							A.OPN_AMOUNT, A.JOBPARENT,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.JOBDESC, A.ITM_GROUP,
							B.ITM_NAME
							FROM vw_joblist_detail_$PRJCODEVW A
							LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP NOT IN ('M') AND ISLAST = 1 $QRYJBCODY";
		}
		else
		{
			$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE,
							A.ITM_VOLM, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED,
							A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG, A.WO_QTY, A.WO_AMOUNT, A.OPN_QTY,
							A.OPN_AMOUNT, A.JOBPARENT,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.JOBDESC, A.ITM_GROUP,
							B.ITM_NAME
							FROM vw_joblist_detail_$PRJCODEVW A
							LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP NOT IN ('M') AND ISLAST = 1 $QRYJBCODY";
		}
		return $this->db->query($sql);
	}

	function count_all_ItemFPA($PRJCODE, $JOBCODE, $PGFROM) // OK
	{
		// HANYA KATEGORI T (SEBELUMNYA NOT IN ('M') --> MS.201990400005
		$sql	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('T') AND ISLAST = 1 
					AND JOBPARENT IN ($JOBCODE)";
		return $this->db->count_all($sql);
	}

	function viewAllItemFPA($PRJCODE, $JOBCODE, $PGFROM) // OK
	{
		// HANYA KATEGORI T (SEBELUMNYA NOT IN ('M') --> MS.201990400005
		$sql	= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, 
					A.ITM_VOLM, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, 
					A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG, A.WO_QTY, A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT,
					A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.JOBDESC, A.ITM_GROUP, A.JOBPARENT,
					B.ITM_NAME
					FROM tbl_joblist_detail A
					LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('T') AND ISLAST = 1 AND A.JOBPARENT IN ($JOBCODE)";
		return $this->db->query($sql);
	}

	function add($projWOH) // OK
	{
		$this->db->insert('tbl_wo_header', $projWOH);
	}

	function updateDet($WO_NUM, $PRJCODE, $WO_DATE) // OK
	{
		$sql = "UPDATE tbl_wo_detail SET PRJCODE = '$PRJCODE', WO_DATE = '$WO_DATE' WHERE WO_NUM = '$WO_NUM'";
		return $this->db->query($sql);
	}

	function get_WO_by_number($WO_NUM) // OK
	{
		$sql = "SELECT A.*,
					B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_wo_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.WO_NUM = '$WO_NUM'";
		return $this->db->query($sql);
	}

	function count_WODET_by_number($WO_NUM) // OK
	{
		$sql = "tbl_wo_detail A INNER JOIN tbl_joblist_detail B ON B.JOBCODEID = A.JOBCODEID WHERE A.WO_NUM = '$WO_NUM'";
		return $this->db->count_all($sql);
	}

	function get_WODET_by_number($WO_NUM) // OK
	{
		$sql = "SELECT A.*, B.JOBCODEID, B.JOBDESC, B.ITM_VOLM
				FROM tbl_wo_detail A
				INNER JOIN tbl_joblist_detail B ON B.JOBCODEID = A.JOBCODEID
					AND B.PRJCODE = A.PRJCODE
				WHERE A.WO_NUM = '$WO_NUM'";
		return $this->db->query($sql);
	}

	function get_WOP_count($WO_NUM) // OK
	{
		$sql	= "tbl_wo_print A
					INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.WOP_NUM = '$WO_NUM'";
		return $this->db->count_all($sql);
	}

	function get_WOP_by_number($WO_NUM) // OK
	{
		$sql = "SELECT A.*,
					B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_wo_print A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.WOP_NUM = '$WO_NUM'";
		return $this->db->query($sql);
	}

	function update($WO_NUM, $projWOH) // OK
	{
		$this->db->where('WO_NUM', $WO_NUM);
		$this->db->update('tbl_wo_header', $projWOH);
	}

	function deleteDetail($WO_NUM) // OK
	{
		$this->db->where('WO_NUM', $WO_NUM);
		$this->db->delete('tbl_wo_detail');
	}

	function deleteWO($WO_NUM) // OK
	{
		// 1. COPY TO tbl_wo_header_trash
			//$sqlqg11	= "INSERT INTO tbl_wo_header_trash SELECT * FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM'";
			//$this->db->query($sqlqg11);

			$sqlqg12	= "DELETE FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM'";
			$this->db->query($sqlqg12);

		// 2. COPY TO tbl_WO_detail_trash
			/*$sqlqg13	= "INSERT INTO tbl_WO_detail_trash SELECT * FROM tbl_WO_detail WHERE WO_NUM = '$WO_NUM'";
			$this->db->query($sqlqg13);

			$sqlqg14	= "DELETE FROM tbl_WO_detail WHERE WO_NUM = '$WO_NUM'";
			$this->db->query($sqlqg14);*/
	}

	function count_all_WOInx($PRJCODE, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "tbl_wo_header A
						LEFT JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'U'";
		}
		else
		{
			$sql = "tbl_wo_header A
						LEFT JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'U'
						AND (WO_NUM LIKE '%$key%' ESCAPE '!' OR WO_CODE LIKE '%$key%' ESCAPE '!'
						OR WO_DATE LIKE '%$key%' ESCAPE '!' OR WO_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}

	function get_all_WOInb($PRJCODE, $start, $end, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_NOTE2, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_wo_header A
						LEFT JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'U' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_NOTE2, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_wo_header A
						LEFT JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'U'
						AND (WO_NUM LIKE '%$key%' ESCAPE '!' OR WO_CODE LIKE '%$key%' ESCAPE '!'
						OR WO_DATE LIKE '%$key%' ESCAPE '!' OR WO_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}

	function get_AllDataCSC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_wo_header A
				WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'S' AND A.WO_STAT IN (2,7)
					AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
					OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataLSC_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'S' AND A.WO_STAT IN (2,7)
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'S' AND A.WO_STAT IN (2,7)
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'S' AND A.WO_STAT IN (2,7)
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'S' AND A.WO_STAT IN (2,7)
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function count_all_WOInx_sub($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			$sql = "tbl_wo_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'S'";
		}
		else
		{
			$sql = "tbl_wo_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'S'
						AND (WO_NUM LIKE '%$key%' ESCAPE '!' OR WO_CODE LIKE '%$key%' ESCAPE '!'
						OR WO_DATE LIKE '%$key%' ESCAPE '!' OR WO_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}

	function get_all_WOInb_sub($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_NOTE2, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_wo_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'S' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_NOTE2, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_wo_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'S'
						AND (WO_NUM LIKE '%$key%' ESCAPE '!' OR WO_CODE LIKE '%$key%' ESCAPE '!'
						OR WO_DATE LIKE '%$key%' ESCAPE '!' OR WO_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}

	function updateWODet($WO_NUM, $PRJCODE) // OK
	{
		$sqlGetPR	= "SELECT WO_NUM, JOBCODEDET, JOBCODEID, ITM_CODE, WO_VOLM, ITM_PRICE
						FROM tbl_wo_detail
						WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
		$resGetPR	= $this->db->query($sqlGetPR)->result();
		foreach($resGetPR as $rowRP) :
			$WO_NUM 	= $rowRP->WO_NUM;
			$JOBCODEDET	= $rowRP->JOBCODEDET;
			$JOBCODEID	= $rowRP->JOBCODEID;
			$ITM_CODE	= $rowRP->ITM_CODE;
			$WO_VOLM	= $rowRP->WO_VOLM;
			$ITM_PRICE	= $rowRP->ITM_PRICE;
			$WO_TOTAMN	= $WO_VOLM * $ITM_PRICE;

			// UPDATE JOBDETAIL
			/*$REQ_VOLM	= 0;
			$REQ_AMOUNT	= 0;
			$sqlGetJD		= "SELECT WO_QTY, WO_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$resGetJD		= $this->db->query($sqlGetJD)->result();
			foreach($resGetJD as $rowJD) :
				$WO_QTY 	= $rowJD->WO_QTY;
				$WO_AMOUNT	= $rowJD->WO_AMOUNT;
			endforeach;
			if($WO_QTY == '')
				$WO_QTY = 0;
			if($WO_AMOUNT == '')
				$WO_AMOUNT = 0;

			$totWOQty	= $WO_QTY + $WO_VOLM;
			$totWOAmn	= $WO_AMOUNT + $WO_TOTAMN;*/

			if($WO_VOLM == '')
				$WO_VOLM = 0;
			if($WO_TOTAMN == '')
				$WO_TOTAMN = 0;

			$sqlUpd		= "UPDATE tbl_joblist_detail SET WO_QTY = WO_QTY+$WO_VOLM, WO_AMOUNT = WO_AMOUNT+$WO_TOTAMN
							WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlUpd);

			// UPDATE TBL_ITEM
			/*$WO_VOLM1		= 0;
			$WO_AMOUNT1		= 0;
			$sqlGetJD1		= "SELECT WO_VOLM, WO_AMOUNT FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resGetJD1		= $this->db->query($sqlGetJD1)->result();
			foreach($resGetJD1 as $rowJD1) :
				$WO_VOLM1 	= $rowJD1->WO_VOLM;
				$WO_AMOUNT1	= $rowJD1->WO_AMOUNT;
			endforeach;
			if($WO_VOLM1 == '')
				$WO_VOLM1 = 0;
			if($WO_AMOUNT1 == '')
				$WO_AMOUNT1 = 0;

			$totWORQty	= $WO_VOLM1 + $WO_VOLM;
			$totWORAmn	= $WO_AMOUNT1 + $WO_TOTAMN;*/

			$sqlUpd2	= "UPDATE tbl_item SET PR_VOLM = PR_VOLM+$WO_VOLM, PR_AMOUNT = PR_AMOUNT+$WO_TOTAMN WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpd2);

			$updLP 	= "UPDATE tbl_joblist_detail SET ITM_LASTP = $ITM_PRICE
						WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($updLP);
		endforeach;
	}

	function get_AllDataCTLS_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_wo_header A
				WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'A' AND A.WO_STAT IN (2,7)
					AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
					OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataLTLS_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'A' AND A.WO_STAT IN (2,7)
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'A' AND A.WO_STAT IN (2,7)
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'A' AND A.WO_STAT IN (2,7)
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_wo_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_CATEG = 'A' AND A.WO_STAT IN (2,7)
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!'
							OR A.WO_DATE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function count_all_WOInx_er($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			$sql = "tbl_wo_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'A'";
		}
		else
		{
			$sql = "tbl_wo_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'A'
						AND (WO_NUM LIKE '%$key%' ESCAPE '!' OR WO_CODE LIKE '%$key%' ESCAPE '!'
						OR WO_DATE LIKE '%$key%' ESCAPE '!' OR WO_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}

	function get_all_WOInb_er($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_NOTE2, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_wo_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'A' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_NOTE2, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_wo_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'A'
						AND (WO_NUM LIKE '%$key%' ESCAPE '!' OR WO_CODE LIKE '%$key%' ESCAPE '!'
						OR WO_DATE LIKE '%$key%' ESCAPE '!' OR WO_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}

	function get_AllDataCRT($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_woreq_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND (WO_CODE LIKE '%$search%' ESCAPE '!' OR WO_NOTE LIKE '%$search%' ESCAPE '!'
						OR STATDESC LIKE '%$search%' ESCAPE '!' OR CREATERNM LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataLRT($PRJCODE, $search, $length, $start) // GOOD
	{
		if($length == -1)
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE,
						A.STATDESC, A.STATCOL, A.CREATERNM, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
					FROM tbl_woreq_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND (WO_CODE LIKE '%$search%' ESCAPE '!' OR WO_NOTE LIKE '%$search%' ESCAPE '!'
						OR STATDESC LIKE '%$search%' ESCAPE '!' OR CREATERNM LIKE '%$search%' ESCAPE '!')";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE,
						A.STATDESC, A.STATCOL, A.CREATERNM, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
					FROM tbl_woreq_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND (WO_CODE LIKE '%$search%' ESCAPE '!' OR WO_NOTE LIKE '%$search%' ESCAPE '!'
						OR STATDESC LIKE '%$search%' ESCAPE '!' OR CREATERNM LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			return $this->db->query($sql);
		}
	}

	function get_AllDataCRT_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_woreq_header A
					WHERE A.PRJCODE = '$PRJCODE' AND A.WO_STAT IN (2,7)
						AND (WO_CODE LIKE '%$search%' ESCAPE '!' OR WO_NOTE LIKE '%$search%' ESCAPE '!'
						OR STATDESC LIKE '%$search%' ESCAPE '!' OR CREATERNM LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataLRT_1n2($PRJCODE, $search, $length, $start) // GOOD
	{
		if($length == -1)
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE,
						A.STATDESC, A.STATCOL, A.CREATERNM, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
					FROM tbl_woreq_header A
					WHERE A.PRJCODE = '$PRJCODE' AND A.WO_STAT IN (2,7)
						AND (WO_CODE LIKE '%$search%' ESCAPE '!' OR WO_NOTE LIKE '%$search%' ESCAPE '!'
						OR STATDESC LIKE '%$search%' ESCAPE '!' OR CREATERNM LIKE '%$search%' ESCAPE '!')";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE,
						A.STATDESC, A.STATCOL, A.CREATERNM, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
					FROM tbl_woreq_header A
					WHERE A.PRJCODE = '$PRJCODE' AND A.WO_STAT IN (2,7)
						AND (WO_CODE LIKE '%$search%' ESCAPE '!' OR WO_NOTE LIKE '%$search%' ESCAPE '!'
						OR STATDESC LIKE '%$search%' ESCAPE '!' OR CREATERNM LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			return $this->db->query($sql);
		}
	}

	function count_all_WOTLS($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			$sql = "tbl_woreq_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_woreq_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (WO_NUM LIKE '%$key%' ESCAPE '!' OR WO_CODE LIKE '%$key%' ESCAPE '!'
						OR WO_DATE LIKE '%$key%' ESCAPE '!' OR WO_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}

	function get_all_WOTLS($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_woreq_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_woreq_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (WO_NUM LIKE '%$key%' ESCAPE '!' OR WO_CODE LIKE '%$key%' ESCAPE '!'
						OR WO_DATE LIKE '%$key%' ESCAPE '!' OR WO_NOTE LIKE '%$key%' ESCAPE '!')
					LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}

	function addWOTLS($projWOH) // OK
	{
		$this->db->insert('tbl_woreq_header', $projWOH);
	}

	function updateDetWOTLS($WO_NUM, $PRJCODE, $WO_DATE) // OK
	{
		$sql = "UPDATE tbl_woreq_detail SET PRJCODE = '$PRJCODE', WO_DATE = '$WO_DATE' WHERE WO_NUM = '$WO_NUM'";
		return $this->db->query($sql);
	}

	function get_WOTLS_by_number($WO_NUM) // OK
	{
		$sql = "SELECT A.*,
					B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_woreq_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.WO_NUM = '$WO_NUM'";
		return $this->db->query($sql);
	}

	function updateWOTLS($WO_NUM, $projWOH) // OK
	{
		$this->db->where('WO_NUM', $WO_NUM);
		$this->db->update('tbl_woreq_header', $projWOH);
	}

	function deleteDetailWOTLS($WO_NUM) // OK
	{
		$this->db->where('WO_NUM', $WO_NUM);
		$this->db->delete('tbl_woreq_detail');
	}

	function count_all_WOTLSInx_er($PRJCODE, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "tbl_woreq_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'A'";
		}
		else
		{
			$sql = "tbl_woreq_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'A'
						AND (WO_NUM LIKE '%$key%' ESCAPE '!' OR WO_CODE LIKE '%$key%' ESCAPE '!'
						OR WO_DATE LIKE '%$key%' ESCAPE '!' OR WO_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}

	function get_all_WOTLSInb_er($PRJCODE, $start, $end, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_woreq_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'A' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, A.SPLCODE, A.WO_DEPT, A.JOBCODEID,
						A.WO_NOTE, A.WO_STAT, WO_MEMO, A.WO_REFNO, A.WO_CREATER, A.WO_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_woreq_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND WO_STAT IN (2,7)
						AND WO_CATEG = 'A'
						AND (WO_NUM LIKE '%$key%' ESCAPE '!' OR WO_CODE LIKE '%$key%' ESCAPE '!'
						OR WO_DATE LIKE '%$key%' ESCAPE '!' OR WO_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}

	function count_all_FPAMDR($PRJCODE) // OK
	{
		$sql	= "tbl_fpa_header WHERE PRJCODE = '$PRJCODE' AND FPA_STAT = 3 AND FPA_CATEG = 'MDR'";
		return $this->db->count_all($sql);
	}

	function view_all_FPAMDR($PRJCODE) // OK
	{
		$sql	= "SELECT * FROM tbl_fpa_header WHERE PRJCODE = '$PRJCODE' AND FPA_STAT = 3 AND FPA_CATEG = 'MDR'";
		return $this->db->query($sql);
	}

	function count_all_FPASUB($PRJCODE) // OK
	{
		$sql	= "tbl_fpa_header WHERE PRJCODE = '$PRJCODE' AND FPA_STAT = 3 AND FPA_CATEG = 'SUB'";
		return $this->db->count_all($sql);
	}

	function view_all_FPASUB($PRJCODE) // OK
	{
		$sql	= "SELECT * FROM tbl_fpa_header WHERE PRJCODE = '$PRJCODE' AND FPA_STAT = 3 AND FPA_CATEG = 'SUB'";
		return $this->db->query($sql);
	}

	function count_all_FPA($PRJCODE) // OK
	{
		$sql	= "tbl_woreq_header WHERE PRJCODE = '$PRJCODE' AND WO_STAT = 3";
		return $this->db->count_all($sql);
	}

	function view_all_FPA($PRJCODE) // OK
	{
		$sql	= "SELECT * FROM tbl_woreq_header WHERE PRJCODE = '$PRJCODE' AND WO_STAT = 3";
		return $this->db->query($sql);
	}

	function count_all_ASTSPK($DefEmp_ID) // OK
	{
		$sql	= "tbl_wo_header A
					WHERE A.WO_CATEG = 'A'
						AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}

	function view_all_ASTFPA($DefEmp_ID) // OK
	{
		$sql	= "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_STARTD, A.WO_ENDD, A.PRJCODE, A.WO_NOTE, A.JOBCODEID, '' AS JOBDESC
					FROM tbl_wo_header A
						/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID*/
					WHERE A.WO_CATEG = 'A'
						AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->query($sql);
	}

	function closedUPDWO($WO_NUM, $PRJCODE, $param) // G
	{
    	$JOBCODEID 	= $param['JOBCODEID'];
		$ITM_CODE 	= $param['ITM_CODE'];
		$WO_VOLM 	= $param['WO_VOLM'];
		$WO_TOTAL 	= $param['WO_TOTAL'];

		// GET ALL OPN BY JOBCODEID
			$sqlGetTOPN	= "SELECT SUM(A.OPND_VOLM) AS TOT_OPNVOLM, SUM(A.OPND_ITMTOTAL) AS TOT_OPNAMOUNT
							FROM tbl_opn_detail A
								INNER JOIN tbl_opn_header B ON B.OPNH_NUM = A.OPNH_NUM
									AND B.OPNH_STAT IN (3,6)
							WHERE
								B.WO_NUM = '$WO_NUM'
								AND A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$resGetTOPN		= $this->db->query($sqlGetTOPN)->result();
			foreach($resGetTOPN as $rowTOPN) :
				$TOT_OPNVOLM	= $rowTOPN->TOT_OPNVOLM;
				$TOT_OPNAMOUNT	= $rowTOPN->TOT_OPNAMOUNT;
			endforeach;
			if($TOT_OPNVOLM == '')
				$TOT_OPNVOLM	= 0;

			if($TOT_OPNAMOUNT == '')
				$TOT_OPNAMOUNT	= 0;

		$sqlUpd		= "UPDATE tbl_wo_detail SET WO_VOLM = $TOT_OPNVOLM, WO_TOTAL = $TOT_OPNAMOUNT,
							OPN_VOLM = $TOT_OPNVOLM, OPN_AMOUNT = $TOT_OPNAMOUNT, WO_VOLMB = $WO_VOLM, WO_TOTALB = $WO_TOTAL
						WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sqlUpd);
	}

	function closedWO($WO_NUM, $PRJCODE) // G
	{
		$sqlGetALLWO	= "SELECT DISTINCT A.JOBCODEID, A.PRJCODE, A.ITM_CODE
							FROM
								tbl_wo_detail A
									INNER JOIN tbl_wo_header B ON B.WO_NUM = A.WO_NUM
										AND B.WO_STAT IN (3,6) AND B.PRJCODE = '$PRJCODE'
							WHERE
								 A.PRJCODE = '$PRJCODE'
							ORDER BY
								JOBCODEID";
		$resGetALLWO		= $this->db->query($sqlGetALLWO)->result();
		foreach($resGetALLWO as $rowALLWO) :
			$JOBCODEID 	= $rowALLWO->JOBCODEID;
			$PRJCODE	= $rowALLWO->PRJCODE;
			$ITM_CODE	= $rowALLWO->ITM_CODE;

			// GET ALL WO BY JOBCODEID
				$TOT_WOVOLM		= 0;
				$TOT_WOAMOUNT	= 0;
				$sqlGetTWO		= "SELECT SUM(A.WO_VOLM) AS TOT_WOLVOL, SUM(A.WO_TOTAL) AS TOT_WOAMOUNT
									FROM tbl_wo_detail A
										INNER JOIN tbl_wo_header B ON B.WO_NUM = A.WO_NUM
											AND B.WO_STAT IN (3,6)
									WHERE
										A.JOBCODEID = '$JOBCODEID'
										AND A.PRJCODE = '$PRJCODE'
										AND A.ITM_CODE = '$ITM_CODE'";
				$resGetTWO		= $this->db->query($sqlGetTWO)->result();
				foreach($resGetTWO as $rowTWO) :
					$TOT_WOLVOL 	= $rowTWO->TOT_WOLVOL;
					$TOT_WOAMOUNT	= $rowTWO->TOT_WOAMOUNT;
				endforeach;
				if($TOT_WOLVOL == '')
					$TOT_WOLVOL	= 0;

				if($TOT_WOAMOUNT == '')
					$TOT_WOAMOUNT	= 0;

			// GET ALL OPN BY JOBCODEID
				$sqlGetTOPN		= "SELECT SUM(A.OPND_VOLM) AS TOT_OPNVOLM, SUM(A.OPND_ITMTOTAL) AS TOT_OPNAMOUNT
									FROM tbl_opn_detail A
										INNER JOIN tbl_opn_header B ON B.OPNH_NUM = A.OPNH_NUM
											AND B.OPNH_STAT IN (3,6)
									WHERE
										A.JOBCODEID = '$JOBCODEID'
										AND A.PRJCODE = '$PRJCODE'
										AND A.ITM_CODE = '$ITM_CODE'";
				$resGetTOPN		= $this->db->query($sqlGetTOPN)->result();
				foreach($resGetTOPN as $rowTOPN) :
					$TOT_OPNVOLM	= $rowTOPN->TOT_OPNVOLM;
					$TOT_OPNAMOUNT	= $rowTOPN->TOT_OPNAMOUNT;
				endforeach;
				if($TOT_OPNVOLM == '')
					$TOT_OPNVOLM	= 0;

				if($TOT_OPNAMOUNT == '')
					$TOT_OPNAMOUNT	= 0;

			// UPDATE TO TBL ITEM N JOB
				$sqlUpd		= "UPDATE tbl_joblist_detail SET WO_QTY = $TOT_WOLVOL, WO_AMOUNT = $TOT_WOAMOUNT,
									OPN_QTY = $TOT_OPNVOLM, OPN_AMOUNT = $TOT_OPNAMOUNT
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sqlUpd);
		endforeach;
	}
	
	function voidUPDJO($WO_NUM, $PRJCODE, $param) // G
	{	
    	$JOBCODEID 	= $param['JOBCODEID'];
		$ITM_CODE 	= $param['ITM_CODE'];
		$WO_VOLM 	= $param['WO_VOLM'];
		$WO_TOTAL 	= $param['WO_TOTAL'];
			
		// UPDATE tbl_joblist_detail
			$sqlUpd		= "UPDATE tbl_joblist_detail SET WO_QTY = WO_QTY - $WO_VOLM, WO_AMOUNT = WO_AMOUNT - $WO_TOTAL
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpd);
	}
	
	function get_AllDataSRVC($PRJCODE, $search) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		$sql 		= "vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataSRVL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE'
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
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataHC($PRJCODE, $search) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		$sql 		= "tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBPARENT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataHL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.ORD_ID, A.JOBCODEID, A.JOBPARENT, A.JOBDESC, A.JOBLEV, A.ISLAST, A.ISLAST_BOQ
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBPARENT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT A.ORD_ID, A.JOBCODEID, A.JOBPARENT, A.JOBDESC, A.JOBLEV, A.ISLAST, A.ISLAST_BOQ
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBPARENT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.ORD_ID, A.JOBCODEID, A.JOBPARENT, A.JOBDESC, A.JOBLEV, A.ISLAST, A.ISLAST_BOQ
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBPARENT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.ORD_ID, A.JOBCODEID, A.JOBPARENT, A.JOBDESC, A.JOBLEV, A.ISLAST, A.ISLAST_BOQ
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBPARENT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataDC($PRJCODE, $JOBCODEID, $search) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		$sql 		= "vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND A.JOBPARENT = '$JOBCODEID'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataDL($PRJCODE, $JOBCODEID, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND A.JOBPARENT = '$JOBCODEID'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND A.JOBPARENT = '$JOBCODEID'
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
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND A.JOBPARENT = '$JOBCODEID'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND A.JOBPARENT = '$JOBCODEID'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMMC($PRJCODE, $search) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		$sql 		= "vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('M', 'T')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMML($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('M', 'T')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('M', 'T')
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
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('M', 'T')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('M', 'T')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMSC($PRJCODE, $search) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		$sql 		= "vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('S')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMSL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('S')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('S')
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
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('S')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('S')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMUC($PRJCODE, $search) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		$sql 		= "vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('U')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMUL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('U')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('U')
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
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('U')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('U')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMOVHC($PRJCODE, $search) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		$sql 		= "vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP NOT IN ('M','U','S')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMOVHL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP NOT IN ('M','U','S')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP NOT IN ('M','U','S')
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
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP NOT IN ('M','U','S')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP NOT IN ('M','U','S')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataITMAC($PRJCODE, $search) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		$sql 		= "vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('T')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMAL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('T')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('T')
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
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('T')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('T')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataITMOC($PRJCODE, $search) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		$sql 		= "vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('I','R','O')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMOL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('I','R','O')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('I','R','O')
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
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('I','R','O')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('I','R','O')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataITMC($PRJCODE, $ITMCAT, $search) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		if(count($ITMCAT) > 1)
		{
			$ArrITMCAT = join("','", $ITMCAT);
		}
		else
		{
			$ArrITMCAT = "'$ITMCAT'";
		}

		$sql 		= "vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ($ArrITMCAT)
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITML($PRJCODE, $ITMCAT, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 		= $PRJCODE;
		$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_prjvw		= $this->db->query($s_prjvw)->result();
		foreach($r_prjvw as $rw_prjvw):
			$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
		endforeach;

		if(count($ITMCAT) > 1)
		{
			$ArrITMCAT = join("','", $ITMCAT);
		}
		else
		{
			$ArrITMCAT = "'$ITMCAT'";
		}

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ($ArrITMCAT)
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ($ArrITMCAT)
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
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ($ArrITMCAT)
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
							A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM vw_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ($ArrITMCAT)
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>
