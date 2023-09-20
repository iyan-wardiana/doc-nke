<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 19 Oktober 2017
 * File Name	= M_jobopname.php
 * Location		= -
*/

class M_jobopname extends CI_Model
{
	function count_all_OPN($PRJCODE)
	{
		$sql = "opn_spkhd WHERE PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_all_OPN($PRJCODE)
	{
		$sql = "SELECT * FROM opn_spkhd WHERE PRJCODE = '$PRJCODE' LIMIT 7";
		return $this->db->query($sql);
	}
}
?>