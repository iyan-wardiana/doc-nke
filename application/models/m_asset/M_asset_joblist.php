<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 April 2017
 * File Name	= M_asset_joblist.php
 * Location		= -
*/
?>
<?php
class M_asset_joblist extends CI_Model
{	
	function count_all_num_rows() // OK
	{
		$sql		= "tbl_asset_joblist";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_AG() // OK
	{	
		$sql = "SELECT JL_CODE, JL_MANCODE, JL_NAME, JL_PRJCODE, JL_DESC, JL_STAT
				FROM tbl_asset_joblist
				ORDER BY JL_NAME";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function add($InsAG) // OK
	{
		$this->db->insert('tbl_asset_joblist', $InsAG);
	}
				
	function get_AG($JL_CODE) // OK
	{
		$sql = "SELECT JL_CODE, JL_MANCODE, JL_NAME, JL_PRJCODE, JL_DESC, JL_STAT FROM tbl_asset_joblist
				WHERE JL_CODE = '$JL_CODE'";
		return $this->db->query($sql);
	}
	
	function update($JL_CODE, $UpdAG) // OK
	{
		$this->db->where('JL_CODE', $JL_CODE);
		$this->db->update('tbl_asset_joblist', $UpdAG);
	}
}
?>