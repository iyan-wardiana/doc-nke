<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 Februari 2017
 * File Name	= M_currency_conv_sd.php
 * Location		= -
*/
?>
<?php
class M_currency_conv extends CI_Model
{	
	function count_all_num_rows() // USE
	{
		return $this->db->count_all('tbl_currconv');
	}
	
	function get_last_ten_currconv($limit, $offset) // USE
	{
		$sql = "SELECT * FROM tbl_currconv
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function add($InsCurrConv) //USE
	{
		$this->db->insert('tbl_currconv', $InsCurrConv);
	}
	
	function get_curr_by_code($CC_CODE) // USE
	{		
		$sql = "SELECT * FROM tbl_currconv WHERE CC_CODE = '$CC_CODE'";
		return $this->db->query($sql);
	}
	
	function update($CURR_ID, $InsCurr) // USE
	{
		$this->db->where('CURR_ID', $CURR_ID);
		$this->db->update('tbl_currconv', $InsCurr);
	}
}
?>