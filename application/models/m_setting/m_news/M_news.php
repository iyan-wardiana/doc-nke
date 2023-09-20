<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 Agustus 2017
 * File Name	= M_news.php
 * Location		= -
*/

class M_news extends CI_Model
{
	function count_all_news($Emp_ID) // OK
	{
		if ($Emp_ID == 'W17110004874' || $Emp_ID == 'D15040004221' || $Emp_ID == 'H17050004765' || $Emp_ID == 'D08010000767' || $Emp_ID == 'D02060000245')
		{
			$sql = "tbl_news_header";
		}
		else
		{
			$sql = "tbl_news_header WHERE NEWSH_CREATER IN ('All','$Emp_ID')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_news($Emp_ID) // OK
	{
		if ($Emp_ID == 'W17110004874' || $Emp_ID == 'D15040004221' || $Emp_ID == 'H17050004765' || $Emp_ID == 'D08010000767' || $Emp_ID == 'D02060000245')
		{
			$sql = "SELECT * FROM tbl_news_header ORDER BY NEWSH_CREATED DESC";
		}
		else
		{
			$sql = "SELECT * FROM tbl_news_header WHERE NEWSH_CREATER IN ('All','$Emp_ID') ORDER BY NEWSH_CREATED DESC";
		}
		return $this->db->query($sql);
	}
	
	function addH($InsNewsH) //OK
	{
		$this->db->insert('tbl_news_header', $InsNewsH);
	}
	
	function addD($InsNewsD) //OK
	{
		$this->db->insert('tbl_news_detail', $InsNewsD);
	}
	
	function get_news($NEWSH_CODE) // OK
	{		
		$sql = "SELECT A.*, B.NEWSD_URL, B.NEWSD_TTD, B.NEWSD_MAIL FROM tbl_news_header A
				INNER JOIN tbl_news_detail B ON B.NEWSD_CODE = A.NEWSH_CODE
				WHERE A.NEWSH_CODE = '$NEWSH_CODE'";
		return $this->db->query($sql);
	}
	
	function update($NEWSH_CODE, $UpdNewsH) // USE
	{
		$this->db->where('NEWSH_CODE', $NEWSH_CODE);
		$this->db->update('tbl_news_header', $UpdNewsH);
	}
	
	function viewNews($NEWSD_ID) // OK
	{		
		$sql = "SELECT * FROM tbl_news_detail WHERE NEWSD_ID = '$NEWSD_ID'";
		return $this->db->query($sql);
	}
	
	function delDetail($NEWSH_CODE) // OK
	{		
		$sql = "DELETE FROM tbl_news_detail WHERE NEWSD_CODE = '$NEWSH_CODE'";
		return $this->db->query($sql);
	}
	
	function get_IMG($NEWSH_CODE) // OK
	{		
		$sql = "SELECT NEWSD_IMG, NEWSD_IMG1, NEWSD_IMG2,NEWSD_IMG3, NEWSD_IMG4
					FROM tbl_news_detail WHERE NEWSD_CODE = '$NEWSH_CODE'";
		return $this->db->query($sql);
	}
	
	function data($number,$offset)
	{
		return $query = $this->db->get('tbl_news_header',$number,$offset)->result();		
	}
	
	function count_news()
	{
		return $this->db->get('tbl_news_header')->num_rows();
	}
	
	function count_allnews() // OK
	{
		$sql = "tbl_news_header WHERE NEWSH_STAT = '1'";
		return $this->db->count_all($sql);
	}
	
	function get_allnews() // OK
	{
		$sql = "SELECT * FROM tbl_news_header WHERE NEWSH_STAT = '1' ORDER BY NEWSH_CREATED DESC";
		return $this->db->query($sql);
	}
	
	function viewNews_list($NEWSH_CODE) // OK
	{		
		$sql = "SELECT * FROM tbl_news_detail WHERE NEWSD_CODE = '$NEWSH_CODE'";
		return $this->db->query($sql);
	}
	
	function count_news_all()
	{
		date_default_timezone_set("Asia/Jakarta");
		$date_now	= date('Y-m-d');
		$sql = "tbl_news_detail A
				INNER JOIN tbl_news_header B ON A.NEWSD_CODE = B.NEWSH_CODE
				AND B.NEWSH_STAT = '1'
				WHERE
				B.NEWSDT_EXPIRED != '' AND B.NEWS_EXPIRED = 1
				OR (B.NEWSDT_EXPIRED > '$date_now' AND B.NEWS_EXPIRED = 2)";	
		return $this->db->count_all($sql);
	}
	
	function get_all_news1($batas = null,$offset = null) // OK
	{
		date_default_timezone_set("Asia/Jakarta");
		$date_now	= date('Y-m-d');
		if($batas != null)
		{
		   $LIMIT = "$offset,$batas";
		}
		$sqlNews= "SELECT A.NEWSD_ID, A.NEWSD_TITLE, A.NEWSD_CONTENT, 
					A.NEWSD_CREATED, A.NEWSD_IMG 
				FROM tbl_news_detail A
				INNER JOIN tbl_news_header B ON A.NEWSD_CODE = B.NEWSH_CODE
					AND B.NEWSH_STAT = '1'
					WHERE B.NEWSDT_EXPIRED != '' AND B.NEWS_EXPIRED = 1
					OR (B.NEWSDT_EXPIRED > '$date_now' AND B.NEWS_EXPIRED = 2) 
					ORDER BY B.NEWSH_DATE DESC LIMIT $LIMIT";
		$resNews 	= $this->db->query($sqlNews)->result();
	 
		//if ($query->num_rows() > 0)
		//{
			return $resNews;
		//}
	}
}
?>