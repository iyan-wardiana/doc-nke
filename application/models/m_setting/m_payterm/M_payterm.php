<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 September 2018
 * File Name	= M_payterm.php
 * Location		= -
*/
?>
<?php
class M_payterm extends CI_Model
{
	function get_data() // OK
	{
		$sql = "SELECT * FROM tbl_payterm";
		return $this->db->query($sql);
	}
	
	function update($upPayTerm, $IDPT) // G
	{
		$this->db->where('ID', $IDPT);
		$this->db->update('tbl_payterm', $upPayTerm);
	}
}
?>