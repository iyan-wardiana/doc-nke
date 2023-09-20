<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 November 2017
 * File Name	= M_vendor.php
 * Location		= -
*/

class M_vendor extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($search) // GOOD
	{
		$sql = "tbl_supplier A 
				WHERE SPLCODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
					OR SPLADD1 LIKE '%$search%' ESCAPE '!' OR SPLKOTA LIKE '%$search%' ESCAPE '!'";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_supplier A 
						WHERE SPLCODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR SPLADD1 LIKE '%$search%' ESCAPE '!' OR SPLKOTA LIKE '%$search%' ESCAPE '!' ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_supplier A 
						WHERE SPLCODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR SPLADD1 LIKE '%$search%' ESCAPE '!' OR SPLKOTA LIKE '%$search%' ESCAPE '!'";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_supplier A 
						WHERE SPLCODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR SPLADD1 LIKE '%$search%' ESCAPE '!' OR SPLKOTA LIKE '%$search%' ESCAPE '!' ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_supplier A 
						WHERE SPLCODE LIKE '%$search%' ESCAPE '!' OR SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR SPLADD1 LIKE '%$search%' ESCAPE '!' OR SPLKOTA LIKE '%$search%' ESCAPE '!' LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_supplier() // OK
	{
		return $this->db->count_all('tbl_supplier');
	}
	
	function get_all_supplier() // OK
	{
		$sql = "SELECT * FROM tbl_supplier";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // U
	{
		$sql = "SELECT * FROM tbl_docpattern WHERE menu_code = '$MenuCode'";
		return $this->db->query($sql);
	}
	
	function add($vendcat) // OK
	{
		$this->db->insert('tbl_supplier', $vendcat);
	}
	
	function get_vendor_by_code($SPLCODE) // OK
	{
		if($SPLCODE == '')
		{
			$sql = "SELECT * FROM tbl_supplier";
		}
		else
		{
			$sql = "SELECT * FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
		}
		return $this->db->query($sql);
	}
	
	function update($SPLCODE, $vendor) // OK
	{
		$this->db->where('SPLCODE', $SPLCODE);
		$this->db->update('tbl_supplier', $vendor);
	}
	
	function count_all_AP($SPLCODE) // OK
	{
		return $this->db->count_all('tbl_pinv_header');
		$sql = "tbl_pinv_header WHERE INV_STAT = 3 AND INV_PAYSTAT NOT IN ('FP') AND SPLCODE = '$SPLCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_all_AP($SPLCODE) // OK
	{
		$sql = "SELECT INV_NUM, INV_CODE, INV_TAXCURR, INV_CATEG, PO_NUM, IR_NUM, PRJCODE, INV_DATE, INV_DUEDATE, INV_AMOUNT_BASE, 
					INV_AMOUNT_PAID, INV_TERM, INV_NOTES, (coalesce(INV_AMOUNT_BASE, 0) - coalesce(INV_AMOUNT_PAID,0)) AS REMTOT
				FROM tbl_pinv_header
				WHERE INV_STAT = 3 AND INV_PAYSTAT NOT IN ('FP') AND SPLCODE = '$SPLCODE'";
		return $this->db->query($sql);
	}
	
	function get_all_TOTAP($SPLCODE) // OK
	{
		$sql = "SELECT coalesce(sum(INV_AMOUNT_BASE), 0) - coalesce(sum(INV_AMOUNT_PAID), 0) AS REMTOT FROM tbl_pinv_header
				WHERE INV_STAT = 3 AND INV_PAYSTAT NOT IN ('FP') AND SPLCODE = '$SPLCODE'";
		return $this->db->query($sql);
	}
}
?>