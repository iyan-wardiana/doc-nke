<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 26 April 2016
 * File Name	= Purchase_reqselectitem_sd.php
 * Location		= -
*/
?>
<?php
class Purchase_reqselectitem_sd extends Model
{
	function Purchase_reqselectitem_sd()
	{
		parent::Model();
	}
	
	/*function viewallitem()
	{
		$query = $this->db->get('titem');
		return $query->result(); 
	}*/
	
	function viewallitem()
	{
		
		$query = $this->db->get('titem');
		return $query->result(); 
	}
}
?>