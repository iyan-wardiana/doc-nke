<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 Januari 2019
 * File Name	= M_search.php
 * Location		= -
*/

class M_search extends CI_Model
{
	function count_all_($orlike, $table, $TXTPRJ, $PRJCODE) // OK
	{
		$this->db->or_like($orlike);
		$this->db->where($TXTPRJ, $PRJCODE);
   		$query = $this->db->get($table);
		return $query->num_rows();
	}
	
	function get_all_($batas = null, $offset = null, $key = null, $table = null) // OK
	{
		if ($key != null)
		{
		   $this->db->or_like($key);
		}
		if($batas != null)
		{
		   $this->db->limit($batas, $offset);
		}
		$query = $this->db->get($table);
		
		if ($query->num_rows() > 0) 
		{
			foreach ($query->result() as $row) 
			{
				$data[] = $row;
			}
		
			return $data;
		}
	}
	
	public function fetch_data($limit, $id)
	{
		$this->db->limit($limit);
		$this->db->where('JournalH_Id', $id);
		$query = $this->db->get("tbl_journalheader");
		if ($query->num_rows() > 0) 
		{
			foreach ($query->result() as $row) 
			{
				$data[] = $row;
			}
		
			return $data;
		}
		return false;
	}
	
	function fetch_dataX($limit, $start){
        $query = $this->db->get('tbl_journalheader', $limit, $start);
        return $query;
    }
}
?>