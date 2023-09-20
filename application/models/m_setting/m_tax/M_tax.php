<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 September 2018
 * File Name	= M_tax.php
 * Location		= -
*/

class M_tax extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($search) // GOOD
	{
		$sql = "tbl_tax_la
					WHERE TAXLA_CODE LIKE '%$search%' ESCAPE '!' OR TAXLA_DESC LIKE '%$search%' ESCAPE '!' 
						OR TAXLA_LINKIN LIKE '%$search%' ESCAPE '!'";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_tax_la
						WHERE TAXLA_CODE LIKE '%$search%' ESCAPE '!' OR TAXLA_DESC LIKE '%$search%' ESCAPE '!' 
							OR TAXLA_LINKIN LIKE '%$search%' ESCAPE '!' ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT * FROM tbl_tax_la
						WHERE TAXLA_CODE LIKE '%$search%' ESCAPE '!' OR TAXLA_DESC LIKE '%$search%' ESCAPE '!' 
							OR TAXLA_LINKIN LIKE '%$search%' ESCAPE '!'";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_tax_la
						WHERE TAXLA_CODE LIKE '%$search%' ESCAPE '!' OR TAXLA_DESC LIKE '%$search%' ESCAPE '!' 
							OR TAXLA_LINKIN LIKE '%$search%' ESCAPE '!' ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT * FROM tbl_tax_la
						WHERE TAXLA_CODE LIKE '%$search%' ESCAPE '!' OR TAXLA_DESC LIKE '%$search%' ESCAPE '!' 
							OR TAXLA_LINKIN LIKE '%$search%' ESCAPE '!' LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function count_all_taxla() // G
	{
		return $this->db->count_all('tbl_tax_la');
	}
	
	function get_all_taxla() // G
	{
		$sql = "SELECT * FROM tbl_tax_la";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // G
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function add($InsLA) // G
	{
		$this->db->insert('tbl_tax_la', $InsLA);
	}
	
	function get_tax_la($TAXLA_NUM) // G
	{		
		$sql = "SELECT * FROM tbl_tax_la WHERE TAXLA_NUM = '$TAXLA_NUM'";
		return $this->db->query($sql);
	}
	
	function t4x_l4_update($TAXLA_NUM) // G
	{
		$sql	= "tbl_tax_la WHERE TAXLA_NUM = '$TAXLA_NUM'";
		return $this->db->count_all($sql);
	}
	
	function update($TAXLA_NUM, $UpdTLA) // G
	{
		$this->db->where('TAXLA_NUM', $TAXLA_NUM);
		$this->db->update('tbl_tax_la', $UpdTLA);
	}
}
?>