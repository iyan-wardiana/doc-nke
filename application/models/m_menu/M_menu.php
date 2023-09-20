<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 November 2018
 * File Name	= M_menu.php
 * Notes		= -
*/

class M_menu extends CI_Model
{
	function get_menu($MenuCode) // G
	{			
		$sql = "SELECT menu_name_IND, menu_name_ENG FROM tbl_menu
				WHERE menu_code = '$MenuCode'";
		return $this->db->query($sql);
	}
}
?>