<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 15 Maret 2022
	* File Name		= M_budprop.php
	* Location		= -
*/

class M_budprop extends CI_Model
{
	public function __construct() 													// GOOD
	{
		parent::__construct();
		$this->load->database();
	}

	function get_AllDataC($PRJCODE, $search)										// GOOD
	{
		$sql = "tbl_bprop_header A
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
					OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
					OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) 		// GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')
						LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataSHC($PRJCODE, $ISCLS, $search) 								// GOOD
	{
		if($ISCLS == 0)		// SHOW ALL
		{
			$sql = "tbl_bprop_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
						OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
						OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		}
		else				// HIDE DOKUMEN YANG REJECT, VOID, CLOSE
		{$sql = "tbl_bprop_header A
				WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_STAT IN (1,2,3)
					AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
					OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
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
							FROM tbl_bprop_header A
							WHERE A.PRJCODE = '$PRJCODE'
								AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
								OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')
							ORDER BY $order $dir";
				}
				else
				{
					$sql = "SELECT A.*, '' AS JOBDESC
							FROM tbl_bprop_header A
							WHERE A.PRJCODE = '$PRJCODE'
								AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
								OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT A.*, '' AS JOBDESC
							FROM tbl_bprop_header A
							WHERE A.PRJCODE = '$PRJCODE'
								AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
								OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')
							ORDER BY $order $dir
							LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT A.*, '' AS JOBDESC
							FROM tbl_bprop_header A
							WHERE A.PRJCODE = '$PRJCODE'
								AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
								OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
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
							FROM tbl_bprop_header A 
								/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'*/
							WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_STAT IN (1,2,3)
								AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
								OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')
							ORDER BY $order $dir";
				}
				else
				{
					$sql = "SELECT A.*, '' AS JOBDESC
							FROM tbl_bprop_header A
								/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'*/
							WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_STAT IN (1,2,3)
								AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
								OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT A.*, '' AS JOBDESC
							FROM tbl_bprop_header A
								/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'*/
							WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_STAT IN (1,2,3)
								AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
								OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')
							ORDER BY $order $dir
							LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT A.*, '' AS JOBDESC
							FROM tbl_bprop_header A
								/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'*/
							WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_STAT IN (1,2,3)
								AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
								OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!'
								OR A.STATDESC LIKE '%$search%' ESCAPE '!')
							LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
	}

	function getDataDocPat($MenuCode) 												// GOOD
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}

	function add($projBPROPH) 														// GOOD
	{
		$this->db->insert('tbl_bprop_header', $projBPROPH);
	}

	function get_PROP_by_number($PROP_NUM) 											// GOOD
	{
		$sql = "SELECT A.*,
					B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_bprop_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.PROP_NUM = '$PROP_NUM'";
		return $this->db->query($sql);
	}

	function update($PROP_NUM, $projBPROPH) 										// GOOD
	{
		$this->db->where('PROP_NUM', $PROP_NUM);
		$this->db->update('tbl_bprop_header', $projBPROPH);
	}

	function deleteDetail($PROP_NUM) 												// GOOD
	{
		$this->db->where('PROP_NUM', $PROP_NUM);
		$this->db->delete('tbl_bprop_detail');
	}

	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_bprop_header A
				WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'U' AND A.PROP_STAT IN (2,7)
					AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
					OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'U' AND A.PROP_STAT IN (2,7)
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'U' AND A.PROP_STAT IN (2,7)
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'U' AND A.PROP_STAT IN (2,7)
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'U' AND A.PROP_STAT IN (2,7)
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function count_all_WO($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			$sql = "tbl_bprop_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_bprop_header A
						LEFT JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (PROP_NUM LIKE '%$key%' ESCAPE '!' OR PROP_CODE LIKE '%$key%' ESCAPE '!'
						OR PROP_DATE LIKE '%$key%' ESCAPE '!' OR PROP_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}

	function get_all_WO($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PROP_CATEG, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_NOTE2, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE, A.PROP_CDOC,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_bprop_header A
						LEFT JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PROP_CATEG, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_NOTE2, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE, A.PROP_CDOC,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_bprop_header A
						LEFT JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (PROP_NUM LIKE '%$key%' ESCAPE '!' OR PROP_CODE LIKE '%$key%' ESCAPE '!'
						OR PROP_DATE LIKE '%$key%' ESCAPE '!' OR PROP_NOTE LIKE '%$key%' ESCAPE '!')
					LIMIT $start, $end";
		}
		return $this->db->query($sql);
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
							A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG, A.PROP_QTY, A.PROP_AMOUNT, A.OPN_QTY,
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
							A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG, A.PROP_QTY, A.PROP_AMOUNT, A.OPN_QTY,
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
					A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG, A.PROP_QTY, A.PROP_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT,
					A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.JOBDESC, A.ITM_GROUP, A.JOBPARENT,
					B.ITM_NAME
					FROM tbl_joblist_detail A
					LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('T') AND ISLAST = 1 AND A.JOBPARENT IN ($JOBCODE)";
		return $this->db->query($sql);
	}

	function count_WODET_by_number($PROP_NUM) // OK
	{
		$sql = "tbl_bprop_detail A INNER JOIN tbl_joblist_detail B ON B.JOBCODEID = A.JOBCODEID WHERE A.PROP_NUM = '$PROP_NUM'";
		return $this->db->count_all($sql);
	}

	function get_WODET_by_number($PROP_NUM) // OK
	{
		$sql = "SELECT A.*, B.JOBCODEID, B.JOBDESC, B.ITM_VOLM
				FROM tbl_bprop_detail A
				INNER JOIN tbl_joblist_detail B ON B.JOBCODEID = A.JOBCODEID
					AND B.PRJCODE = A.PRJCODE
				WHERE A.PROP_NUM = '$PROP_NUM'";
		return $this->db->query($sql);
	}

	function get_WOP_count($PROP_NUM) // OK
	{
		$sql	= "tbl_PROP_print A
					INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.WOP_NUM = '$PROP_NUM'";
		return $this->db->count_all($sql);
	}

	function get_WOP_by_number($PROP_NUM) // OK
	{
		$sql = "SELECT A.*,
					B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_PROP_print A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.WOP_NUM = '$PROP_NUM'";
		return $this->db->query($sql);
	}

	function deleteWO($PROP_NUM) // OK
	{
		// 1. COPY TO tbl_bprop_header_trash
			//$sqlqg11	= "INSERT INTO tbl_bprop_header_trash SELECT * FROM tbl_bprop_header WHERE PROP_NUM = '$PROP_NUM'";
			//$this->db->query($sqlqg11);

			$sqlqg12	= "DELETE FROM tbl_bprop_header WHERE PROP_NUM = '$PROP_NUM'";
			$this->db->query($sqlqg12);

		// 2. COPY TO tbl_bprop_detail_trash
			/*$sqlqg13	= "INSERT INTO tbl_bprop_detail_trash SELECT * FROM tbl_bprop_detail WHERE PROP_NUM = '$PROP_NUM'";
			$this->db->query($sqlqg13);

			$sqlqg14	= "DELETE FROM tbl_bprop_detail WHERE PROP_NUM = '$PROP_NUM'";
			$this->db->query($sqlqg14);*/
	}

	function count_all_WOInx($PRJCODE, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "tbl_bprop_header A
						LEFT JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'MDR'";
		}
		else
		{
			$sql = "tbl_bprop_header A
						LEFT JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'MDR'
						AND (PROP_NUM LIKE '%$key%' ESCAPE '!' OR PROP_CODE LIKE '%$key%' ESCAPE '!'
						OR PROP_DATE LIKE '%$key%' ESCAPE '!' OR PROP_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}

	function get_all_WOInb($PRJCODE, $start, $end, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_NOTE2, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_bprop_header A
						LEFT JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'MDR' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_NOTE2, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_bprop_header A
						LEFT JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'MDR'
						AND (PROP_NUM LIKE '%$key%' ESCAPE '!' OR PROP_CODE LIKE '%$key%' ESCAPE '!'
						OR PROP_DATE LIKE '%$key%' ESCAPE '!' OR PROP_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}

	function get_AllDataCSC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_bprop_header A
				WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'SUB' AND A.PROP_STAT IN (2,7)
					AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
					OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataLSC_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'SUB' AND A.PROP_STAT IN (2,7)
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'SUB' AND A.PROP_STAT IN (2,7)
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'SUB' AND A.PROP_STAT IN (2,7)
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'SUB' AND A.PROP_STAT IN (2,7)
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function count_all_WOInx_sub($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			$sql = "tbl_bprop_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'SUB'";
		}
		else
		{
			$sql = "tbl_bprop_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'SUB'
						AND (PROP_NUM LIKE '%$key%' ESCAPE '!' OR PROP_CODE LIKE '%$key%' ESCAPE '!'
						OR PROP_DATE LIKE '%$key%' ESCAPE '!' OR PROP_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}

	function get_all_WOInb_sub($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_NOTE2, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_bprop_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'SUB' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_NOTE2, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_bprop_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'SUB'
						AND (PROP_NUM LIKE '%$key%' ESCAPE '!' OR PROP_CODE LIKE '%$key%' ESCAPE '!'
						OR PROP_DATE LIKE '%$key%' ESCAPE '!' OR PROP_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}

	function updateWODet($PROP_NUM, $PRJCODE) // OK
	{
		$sqlGetPR	= "SELECT PROP_NUM, JOBCODEDET, JOBCODEID, ITM_CODE, PROP_VOLM, ITM_PRICE
						FROM tbl_bprop_detail
						WHERE PROP_NUM = '$PROP_NUM' AND PRJCODE = '$PRJCODE'";
		$resGetPR	= $this->db->query($sqlGetPR)->result();
		foreach($resGetPR as $rowRP) :
			$PROP_NUM 	= $rowRP->PROP_NUM;
			$JOBCODEDET	= $rowRP->JOBCODEDET;
			$JOBCODEID	= $rowRP->JOBCODEID;
			$ITM_CODE	= $rowRP->ITM_CODE;
			$PROP_VOLM	= $rowRP->PROP_VOLM;
			$ITM_PRICE	= $rowRP->ITM_PRICE;
			$PROP_TOTAMN	= $PROP_VOLM * $ITM_PRICE;

			// UPDATE JOBDETAIL
			/*$REQ_VOLM	= 0;
			$REQ_AMOUNT	= 0;
			$sqlGetJD		= "SELECT PROP_QTY, PROP_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$resGetJD		= $this->db->query($sqlGetJD)->result();
			foreach($resGetJD as $rowJD) :
				$PROP_QTY 	= $rowJD->PROP_QTY;
				$PROP_AMOUNT	= $rowJD->PROP_AMOUNT;
			endforeach;
			if($PROP_QTY == '')
				$PROP_QTY = 0;
			if($PROP_AMOUNT == '')
				$PROP_AMOUNT = 0;

			$totWOQty	= $PROP_QTY + $PROP_VOLM;
			$totWOAmn	= $PROP_AMOUNT + $PROP_TOTAMN;*/

			if($PROP_VOLM == '')
				$PROP_VOLM = 0;
			if($PROP_TOTAMN == '')
				$PROP_TOTAMN = 0;

			$sqlUpd		= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM+$PROP_VOLM, REQ_AMOUNT = REQ_AMOUNT+$PROP_TOTAMN,
								PROP_QTY = PROP_QTY+$PROP_VOLM, PROP_AMOUNT = PROP_AMOUNT+$PROP_TOTAMN
							WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlUpd);

			// UPDATE TBL_ITEM
			/*$PROP_VOLM1		= 0;
			$PROP_AMOUNT1		= 0;
			$sqlGetJD1		= "SELECT PROP_VOLM, PROP_AMOUNT FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resGetJD1		= $this->db->query($sqlGetJD1)->result();
			foreach($resGetJD1 as $rowJD1) :
				$PROP_VOLM1 	= $rowJD1->PROP_VOLM;
				$PROP_AMOUNT1	= $rowJD1->PROP_AMOUNT;
			endforeach;
			if($PROP_VOLM1 == '')
				$PROP_VOLM1 = 0;
			if($PROP_AMOUNT1 == '')
				$PROP_AMOUNT1 = 0;

			$totWORQty	= $PROP_VOLM1 + $PROP_VOLM;
			$totWORAmn	= $PROP_AMOUNT1 + $PROP_TOTAMN;*/

			$sqlUpd2	= "UPDATE tbl_item SET PR_VOLM = PR_VOLM+$PROP_VOLM, PR_AMOUNT = PR_AMOUNT+$PROP_TOTAMN WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpd2);
		endforeach;
	}

	function get_AllDataCTLS_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_bprop_header A
				WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'SALT' AND A.PROP_STAT IN (2,7)
					AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
					OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataLTLS_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'SALT' AND A.PROP_STAT IN (2,7)
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'SALT' AND A.PROP_STAT IN (2,7)
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'SALT' AND A.PROP_STAT IN (2,7)
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, '' AS JOBDESC
						FROM tbl_bprop_header A
							/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_CATEG = 'SALT' AND A.PROP_STAT IN (2,7)
							AND (A.PROP_CODE LIKE '%$search%' ESCAPE '!'
							OR A.PROP_DATE LIKE '%$search%' ESCAPE '!' OR A.PROP_NOTE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function count_all_WOInx_er($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			$sql = "tbl_bprop_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'SALT'";
		}
		else
		{
			$sql = "tbl_bprop_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'SALT'
						AND (PROP_NUM LIKE '%$key%' ESCAPE '!' OR PROP_CODE LIKE '%$key%' ESCAPE '!'
						OR PROP_DATE LIKE '%$key%' ESCAPE '!' OR PROP_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}

	function get_all_WOInb_er($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_NOTE2, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_bprop_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'SALT' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_NOTE2, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_bprop_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'SALT'
						AND (PROP_NUM LIKE '%$key%' ESCAPE '!' OR PROP_CODE LIKE '%$key%' ESCAPE '!'
						OR PROP_DATE LIKE '%$key%' ESCAPE '!' OR PROP_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}

	function get_AllDataCRT($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_woreq_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND (PROP_CODE LIKE '%$search%' ESCAPE '!' OR PROP_NOTE LIKE '%$search%' ESCAPE '!'
						OR STATDESC LIKE '%$search%' ESCAPE '!' OR CREATERNM LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataLRT($PRJCODE, $search, $length, $start) // GOOD
	{
		if($length == -1)
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE,
						A.STATDESC, A.STATCOL, A.CREATERNM, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
					FROM tbl_woreq_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND (PROP_CODE LIKE '%$search%' ESCAPE '!' OR PROP_NOTE LIKE '%$search%' ESCAPE '!'
						OR STATDESC LIKE '%$search%' ESCAPE '!' OR CREATERNM LIKE '%$search%' ESCAPE '!')";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE,
						A.STATDESC, A.STATCOL, A.CREATERNM, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
					FROM tbl_woreq_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND (PROP_CODE LIKE '%$search%' ESCAPE '!' OR PROP_NOTE LIKE '%$search%' ESCAPE '!'
						OR STATDESC LIKE '%$search%' ESCAPE '!' OR CREATERNM LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			return $this->db->query($sql);
		}
	}

	function get_AllDataCRT_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_woreq_header A
					WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_STAT IN (2,7)
						AND (PROP_CODE LIKE '%$search%' ESCAPE '!' OR PROP_NOTE LIKE '%$search%' ESCAPE '!'
						OR STATDESC LIKE '%$search%' ESCAPE '!' OR CREATERNM LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}

	function get_AllDataLRT_1n2($PRJCODE, $search, $length, $start) // GOOD
	{
		if($length == -1)
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE,
						A.STATDESC, A.STATCOL, A.CREATERNM, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
					FROM tbl_woreq_header A
					WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_STAT IN (2,7)
						AND (PROP_CODE LIKE '%$search%' ESCAPE '!' OR PROP_NOTE LIKE '%$search%' ESCAPE '!'
						OR STATDESC LIKE '%$search%' ESCAPE '!' OR CREATERNM LIKE '%$search%' ESCAPE '!')";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE,
						A.STATDESC, A.STATCOL, A.CREATERNM, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
					FROM tbl_woreq_header A
					WHERE A.PRJCODE = '$PRJCODE' AND A.PROP_STAT IN (2,7)
						AND (PROP_CODE LIKE '%$search%' ESCAPE '!' OR PROP_NOTE LIKE '%$search%' ESCAPE '!'
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
						AND (PROP_NUM LIKE '%$key%' ESCAPE '!' OR PROP_CODE LIKE '%$key%' ESCAPE '!'
						OR PROP_DATE LIKE '%$key%' ESCAPE '!' OR PROP_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}

	function get_all_WOTLS($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_woreq_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_woreq_header A
						INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (PROP_NUM LIKE '%$key%' ESCAPE '!' OR PROP_CODE LIKE '%$key%' ESCAPE '!'
						OR PROP_DATE LIKE '%$key%' ESCAPE '!' OR PROP_NOTE LIKE '%$key%' ESCAPE '!')
					LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}

	function addWOTLS($projWOH) // OK
	{
		$this->db->insert('tbl_woreq_header', $projWOH);
	}

	function updateDetWOTLS($PROP_NUM, $PRJCODE, $PROP_DATE) // OK
	{
		$sql = "UPDATE tbl_woreq_detail SET PRJCODE = '$PRJCODE', PROP_DATE = '$PROP_DATE' WHERE PROP_NUM = '$PROP_NUM'";
		return $this->db->query($sql);
	}

	function get_WOTLS_by_number($PROP_NUM) // OK
	{
		$sql = "SELECT A.*,
					B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_woreq_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.PROP_NUM = '$PROP_NUM'";
		return $this->db->query($sql);
	}

	function updateWOTLS($PROP_NUM, $projWOH) // OK
	{
		$this->db->where('PROP_NUM', $PROP_NUM);
		$this->db->update('tbl_woreq_header', $projWOH);
	}

	function deleteDetailWOTLS($PROP_NUM) // OK
	{
		$this->db->where('PROP_NUM', $PROP_NUM);
		$this->db->delete('tbl_woreq_detail');
	}

	function count_all_WOTLSInx_er($PRJCODE, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "tbl_woreq_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'SALT'";
		}
		else
		{
			$sql = "tbl_woreq_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'SALT'
						AND (PROP_NUM LIKE '%$key%' ESCAPE '!' OR PROP_CODE LIKE '%$key%' ESCAPE '!'
						OR PROP_DATE LIKE '%$key%' ESCAPE '!' OR PROP_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}

	function get_all_WOTLSInb_er($PRJCODE, $start, $end, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_woreq_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'SALT' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PRJCODE, A.SPLCODE, A.PROP_DEPT, A.JOBCODEID,
						A.PROP_NOTE, A.PROP_STAT, PROP_MEMO, A.PROP_REFNO, A.PROP_CREATER, A.PROP_ISCLOSE,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_woreq_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND PROP_STAT IN (2,7)
						AND PROP_CATEG = 'SALT'
						AND (PROP_NUM LIKE '%$key%' ESCAPE '!' OR PROP_CODE LIKE '%$key%' ESCAPE '!'
						OR PROP_DATE LIKE '%$key%' ESCAPE '!' OR PROP_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
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
		$sql	= "tbl_woreq_header WHERE PRJCODE = '$PRJCODE' AND PROP_STAT = 3";
		return $this->db->count_all($sql);
	}

	function view_all_FPA($PRJCODE) // OK
	{
		$sql	= "SELECT * FROM tbl_woreq_header WHERE PRJCODE = '$PRJCODE' AND PROP_STAT = 3";
		return $this->db->query($sql);
	}

	function count_all_ASTSPK($DefEmp_ID) // OK
	{
		$sql	= "tbl_bprop_header A
					WHERE A.PROP_CATEG = 'SALT'
						AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}

	function view_all_ASTFPA($DefEmp_ID) // OK
	{
		$sql	= "SELECT A.PROP_NUM, A.PROP_CODE, A.PROP_DATE, A.PROP_STARTD, A.PROP_ENDD, A.PRJCODE, A.PROP_NOTE, A.JOBCODEID, '' AS JOBDESC
					FROM tbl_bprop_header A
						/*LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID*/
					WHERE A.PROP_CATEG = 'SALT'
						AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->query($sql);
	}

	function closedUPDWO($PROP_NUM, $PRJCODE, $param) // G
	{
    	$JOBCODEID 	= $param['JOBCODEID'];
		$ITM_CODE 	= $param['ITM_CODE'];
		$PROP_VOLM 	= $param['PROP_VOLM'];
		$PROP_TOTAL = $param['PROP_TOTAL'];

		// GET ALL OPN BY JOBCODEID
			$sqlGetTOPN	= "SELECT SUM(A.OPND_VOLM) AS TOT_OPNVOLM, SUM(A.OPND_ITMTOTAL) AS TOT_OPNAMOUNT
							FROM tbl_opn_detail A
								INNER JOIN tbl_opn_header B ON B.OPNH_NUM = A.OPNH_NUM
									AND B.OPNH_STAT IN (3,6)
							WHERE
								B.PROP_NUM = '$PROP_NUM'
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

		$sqlUpd		= "UPDATE tbl_bprop_detail SET PROP_VOLM = $TOT_OPNVOLM, PROP_TOTAL = $TOT_OPNAMOUNT,
							OPN_VOLM = $TOT_OPNVOLM, OPN_AMOUNT = $TOT_OPNAMOUNT, PROP_VOLMB = $PROP_VOLM, PROP_TOTALB = $PROP_TOTAL
						WHERE PROP_NUM = '$PROP_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		// $this->db->query($sqlUpd);
	}

	function closedWO($PROP_NUM, $PRJCODE) // G
	{
		$sqlGetALLWO	= "SELECT DISTINCT A.JOBCODEID, A.PRJCODE, A.ITM_CODE
							FROM
								tbl_bprop_detail A
									INNER JOIN tbl_bprop_header B ON B.PROP_NUM = A.PROP_NUM
										AND B.PROP_STAT IN (3,6) AND B.PRJCODE = '$PRJCODE'
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
				$sqlGetTWO		= "SELECT SUM(A.PROP_VOLM) AS TOT_WOLVOL, SUM(A.PROP_TOTAL) AS TOT_WOAMOUNT
									FROM tbl_bprop_detail A
										INNER JOIN tbl_bprop_header B ON B.PROP_NUM = A.PROP_NUM
											AND B.PROP_STAT IN (3,6)
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
				$sqlUpd		= "UPDATE tbl_joblist_detail SET PROP_QTY = $TOT_WOLVOL, PROP_AMOUNT = $TOT_WOAMOUNT,
									OPN_QTY = $TOT_OPNVOLM, OPN_AMOUNT = $TOT_OPNAMOUNT
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				// $this->db->query($sqlUpd);
		endforeach;
	}
	
	function voidUPDJO($PROP_NUM, $PRJCODE, $param) // G
	{	
    	$JOBCODEID 	= $param['JOBCODEID'];
		$ITM_CODE 	= $param['ITM_CODE'];
		$PROP_VOLM 	= $param['PROP_VOLM'];
		$PROP_TOTAL 	= $param['PROP_TOTAL'];
			
		// UPDATE tbl_joblist_detail
			$sqlUpd		= "UPDATE tbl_joblist_detail SET PROP_QTY = PROP_QTY - $PROP_VOLM, PROP_AMOUNT = PROP_AMOUNT - $PROP_TOTAL
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpd);
	}
	
	function get_AllDataITMC($PRJCODE, $search) // GOOD
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
	
	function get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
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
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('SC', 'S')
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
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('SC', 'S')
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
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('SC', 'S')
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
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('SC', 'S')
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
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('SC', 'S')
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
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('I', 'R', 'O')
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
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('I', 'R', 'O')
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
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('I', 'R', 'O')
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
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('I', 'R', 'O')
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
						WHERE A.PRJCODE = '$PRJCODE' AND ISLAST = 1 AND ITM_GROUP IN ('I', 'R', 'O')
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>
