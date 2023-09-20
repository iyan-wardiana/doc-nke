<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 21 Oktober 2018
 * File Name	= M_joselect.php
 * Location		= -
*/

class M_joselect extends CI_Model
{
	function count_all_JO($PRJCODE) // G
	{
		$sql	= "tbl_jo_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
						INNER JOIN tbl_customer C ON A.CUST_CODE = C.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.JO_STAT = 3 AND ISSELECT = 0";
		return $this->db->count_all($sql);
	}
	
	function get_all_JO($PRJCODE) // G
	{
		$sql = "SELECT A.*, B.PRJCODE, B.PRJNAME, C.CUST_DESC
				FROM tbl_jo_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					INNER JOIN tbl_customer C ON A.CUST_CODE = C.CUST_CODE
				WHERE A.PRJCODE = '$PRJCODE'
					AND A.JO_STAT = 3 AND ISSELECT = 0
					ORDER BY A.JO_CODE ASC";		
		return $this->db->query($sql);
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_jo_header 
				WHERE PRJCODE = '$PRJCODE' AND JO_STAT = 3 AND ISSELECT = 0
					AND (JO_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
						OR JO_DESC LIKE '%$search%' ESCAPE '!' OR JO_DATE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT PRJCODE, JO_ID, JO_NUM, JO_CODE, JO_DATE, JO_PRODD, CUST_CODE, CUST_DESC, JO_DESC, JO_VOLM, JO_AMOUNT, JO_NOTES, JO_NOTES2,
							STATDESC, STATCOL, CREATERNM, JO_STAT, MR_NUM, ISSELECT
						FROM tbl_jo_header
						WHERE PRJCODE = '$PRJCODE' AND JO_STAT = 3 AND ISSELECT = 0
							AND (JO_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
								OR JO_DESC LIKE '%$search%' ESCAPE '!' OR JO_DATE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT PRJCODE, JO_ID, JO_NUM, JO_CODE, JO_DATE, JO_PRODD, CUST_CODE, CUST_DESC, JO_DESC, JO_VOLM, JO_AMOUNT, JO_NOTES, JO_NOTES2,
							STATDESC, STATCOL, CREATERNM, JO_STAT, MR_NUM, ISSELECT
						FROM tbl_jo_header
						WHERE PRJCODE = '$PRJCODE' AND JO_STAT = 3 AND ISSELECT = 0
							AND (JO_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
								OR JO_DESC LIKE '%$search%' ESCAPE '!' OR JO_DATE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT PRJCODE, JO_ID, JO_NUM, JO_CODE, JO_DATE, JO_PRODD, CUST_CODE, CUST_DESC, JO_DESC, JO_VOLM, JO_AMOUNT, JO_NOTES, JO_NOTES2,
							STATDESC, STATCOL, CREATERNM, JO_STAT, MR_NUM, ISSELECT
						FROM tbl_jo_header
						WHERE PRJCODE = '$PRJCODE' AND JO_STAT = 3 AND ISSELECT = 0
							AND (JO_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
								OR JO_DESC LIKE '%$search%' ESCAPE '!' OR JO_DATE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT PRJCODE, JO_ID, JO_NUM, JO_CODE, JO_DATE, JO_PRODD, CUST_CODE, CUST_DESC, JO_DESC, JO_VOLM, JO_AMOUNT, JO_NOTES, JO_NOTES2,
							STATDESC, STATCOL, CREATERNM, JO_STAT, MR_NUM, ISSELECT
						FROM tbl_jo_header
						WHERE PRJCODE = '$PRJCODE' AND JO_STAT = 3 AND ISSELECT = 0
							AND (JO_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
								OR JO_DESC LIKE '%$search%' ESCAPE '!' OR JO_DATE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>