<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Oktober 2017
 * File Name	= M_multi_lang.php
 * Location		= -
*/

class M_multi_lang extends CI_Model
{
	function count_all_num_rows() // OK
	{
		return $this->db->count_all('tbl_translate');
	}
	
	function get_all_multilang() // OK
	{
		$sql = "SELECT * FROM tbl_translate";
		return $this->db->query($sql);
	}
	
	function add($InsML) //OK
	{
		$this->db->insert('tbl_translate', $InsML);
	}
	
	function count_ml_code($MLANG_CODE) // OK
	{
		$sql	= "tbl_translate WHERE MLANG_CODE = '$MLANG_CODE'";
		return $this->db->count_all($sql);
	}
	
	function get_translate($MLANG_ID) // OK
	{		
		$sql = "SELECT * FROM tbl_translate WHERE MLANG_ID = '$MLANG_ID'";
		return $this->db->query($sql);
	}
	
	function update($MLANG_CODE, $UpdML) // OK
	{
		$this->db->where('MLANG_CODE', $MLANG_CODE);
		$this->db->update('tbl_translate', $UpdML);
	}
}
?>