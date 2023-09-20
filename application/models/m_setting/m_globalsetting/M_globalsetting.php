<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 Februari 2017
 * File Name	= M_globalsetting.php
 * Location		= -
*/
?>
<?php
class M_globalsetting extends CI_Model
{
	function viewglobalsetting()
	{
		$query = $this->db->get('tglobalsetting');
		return $query->result(); 
	}
	
	function viewCurrency()
	{
		$query = $this->db->get('tbl_currency');
		return $query->result(); 
	}
	
	var $table = 'tglobalsetting';
	
	function update($globalsetting)
	{
		$this->db->update($this->table, $globalsetting);
	}
	
	function updateMJRAPP($mjr_app, $MJR_APP)
	{
		$sqlCMJR	= "tbl_major_app";
		$resCMJR 	= $this->db->count_all($sqlCMJR);
		if($resCMJR > 0)
		{
			$this->db->update('tbl_major_app', $mjr_app);
		}
		else
		{
			$sqlCMJR	= "INSERT INTO tbl_major_app (Emp_ID1) VALUES ('$MJR_APP')";
			$this->db->query($sqlCMJR);
		}
	}
}
?>