<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 Maret 2014
 * File Name	= Purchase_reqselectitem.css
 * Location		= ./system/application/models/m_purchase/m_requisition/Purchase_reqselectitem.php
*/
?>
<?php
class Purchase_reqselectitem extends Model
{
	function Purchase_reqselectitem()
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