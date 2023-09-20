<?php
/*  
	* Author		= Dian Hermanto
	* Create Date	= 4 September 2020
	* File Name		= m_vehicle.php
	* Location		= -
*/

class m_vehicle extends CI_Model
{	
	function count_all_num_rows() // OK
	{
		$sql		= "tbl_vehicle";
		return $this->db->count_all($sql);
	}
	
	
	function get_AllDataC($search) // GOOD
	{
		$sql = "tbl_vehicle WHERE VH_TYPE LIKE '%$search%' ESCAPE '!' OR VH_MEREK LIKE '%$search%' ESCAPE '!' 
						OR VH_NOPOL LIKE '%$search%' ESCAPE '!'";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_vehicle WHERE VH_TYPE LIKE '%$search%' ESCAPE '!' OR VH_MEREK LIKE '%$search%' ESCAPE '!' 
						OR VH_NOPOL LIKE '%$search%' ESCAPE '!' ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT * FROM tbl_vehicle WHERE VH_TYPE LIKE '%$search%' ESCAPE '!' OR VH_MEREK LIKE '%$search%' ESCAPE '!' 
						OR VH_NOPOL LIKE '%$search%' ESCAPE '!'";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_vehicle WHERE VH_TYPE LIKE '%$search%' ESCAPE '!' OR VH_MEREK LIKE '%$search%' ESCAPE '!' 
						OR VH_NOPOL LIKE '%$search%' ESCAPE '!' ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT * FROM tbl_vehicle WHERE VH_TYPE LIKE '%$search%' ESCAPE '!' OR VH_MEREK LIKE '%$search%' ESCAPE '!' 
						OR VH_NOPOL LIKE '%$search%' ESCAPE '!' LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_last_ten_AG() // OK
	{	
		$sql = "SELECT *
				FROM tbl_vehicle
				ORDER BY VH_CODE";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function add($Ins) // OK
	{
		$this->db->insert('tbl_vehicle', $Ins);
	}
				
	function get_AG($VH_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_vehicle
				WHERE VH_CODE = '$VH_CODE'";
		return $this->db->query($sql);
	}
	
	function update($VH_CODE, $Upd) // OK
	{
		$this->db->where('VH_CODE', $VH_CODE);
		$this->db->update('tbl_vehicle', $Upd);
	}
	
	function deleteVH($VH_CODE) // USED
	{
		$this->db->where('VH_CODE', $VH_CODE);
		$this->db->delete('tbl_vehicle');
	}
}
?>