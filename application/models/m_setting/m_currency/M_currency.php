<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 Februari 2017
 * File Name	= M_currency_sd.php
 * Location		= -
*/
?>
<?php
class M_currency extends CI_Model
{
	function count_all_num_rows() // USE
	{
		return $this->db->count_all('tbl_currency');
	}
	
	function get_last_ten_currency($limit, $offset) // USE
	{
		$sql = "SELECT * FROM tbl_currency
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function add($InsCurr) //USE
	{
		$this->db->insert('tbl_currency', $InsCurr);
	}
	
	function get_curr_by_code($CURR_ID) // USE
	{		
		$sql = "SELECT * FROM tbl_currency WHERE CURR_ID = '$CURR_ID'";
		return $this->db->query($sql);
	}
	
	function update($CURR_ID, $InsCurr) // USE
	{
		$this->db->where('CURR_ID', $CURR_ID);
		$this->db->update('tbl_currency', $InsCurr);
	}
}
?>