<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 19 Oktober 2017
 * File Name	= M_projectlist.php
 * Notes		= -
*/

class M_projectlist extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($DefEmp_ID, $jrnType, $search) // GOOD
	{
		$this->db->select('APPLEV');
		$resGlobal = $this->db->get('tglobalsetting')->result();
		foreach($resGlobal as $row) :
		    $APPLEV = $row->APPLEV;
		endforeach;

		if($APPLEV == 'HO') // BERARTI HARUS MENAMPILKAN SEMUA TURUNAN DARI INDUK PERUSAHAAN YANG DISETTING PADA USER OTORISASI, DENGAN PRJTYPE TETAP 3
			$ADDQRY		= "A.PRJCODE_HO";
		else
			$ADDQRY		= "A.PRJCODE";

		$PRJTYPE    	= "3";
		if($APPLEV == 'PRJ')
		    $PRJTYPE    = "2";

		$ADDQRY2 	= "";
		if($jrnType == 'CPRJ')
			$ADDQRY2 = "AND A.PRJLKOT = 1";

		/*$sql 	= "tbl_project A 
					WHERE A.PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.PRJTYPE = 3
						AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
						OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
						OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!')";*/

		// CATATAN PENTING ... !!!
		// WHERE A.PRJCODE_HO IN (SELECT proj_Code DIGANTI menjadi WHERE A.PRJCODE IN (SELECT proj_Code
		// karena yang ditampilkan akan proyek yang bertipe anggaran. Sehingga, tidak setiap orang akan melihat daftar anggaran secara menyeluruh
		// namun hanya yang dipilih. BUKAN LAGI BERDASARKAN Induk Anggarannya.
		$sql 	= "tbl_project A 
					WHERE $ADDQRY IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.PRJTYPE IN ($PRJTYPE)
						$ADDQRY2 AND A.PRJSTAT = 1 AND A.PRJCODE = 'KTR-22'
						AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
						OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
						OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') LIMIT 10";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($DefEmp_ID, $jrnType, $search, $length, $start, $order, $dir) // GOOD
	{
		$this->db->select('APPLEV');
		$resGlobal = $this->db->get('tglobalsetting')->result();
		foreach($resGlobal as $row) :
		    $APPLEV = $row->APPLEV;
		endforeach;

		if($APPLEV == 'HO') // BERARTI HARUS MENAMPILKAN SEMUA TURUNAN DARI INDUK PERUSAHAAN YANG DISETTING PADA USER OTORISASI, DENGAN PRJTYPE TETAP 3
			$ADDQRY		= "A.PRJCODE_HO";
		else
			$ADDQRY		= "A.PRJCODE";

		$PRJTYPE    	= "3";
		if($APPLEV == 'PRJ')
		    $PRJTYPE    = "2";

		$ADDQRY2 	= "";
		if($jrnType == 'CPRJ')
			$ADDQRY2 = "AND A.PRJLKOT = 1";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project A 
						WHERE $ADDQRY IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.PRJTYPE IN ($PRJTYPE)
							$ADDQRY2 AND A.PRJSTAT = 1 AND A.PRJCODE = 'KTR-22'
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY A.PRJLEV DESC, $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project A 
						WHERE $ADDQRY IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.PRJTYPE IN ($PRJTYPE)
							$ADDQRY2 AND A.PRJSTAT = 1 AND A.PRJCODE = 'KTR-22'
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY A.PRJLEV DESC";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project A 
						WHERE $ADDQRY IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.PRJTYPE IN ($PRJTYPE)
							$ADDQRY2 AND A.PRJSTAT = 1 AND A.PRJCODE = 'KTR-22'
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY A.PRJLEV DESC, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project A 
						WHERE $ADDQRY IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.PRJTYPE IN ($PRJTYPE)
							$ADDQRY2 AND A.PRJSTAT = 1 AND A.PRJCODE = 'KTR-22'
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY A.PRJLEV DESC LIMIT 10";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataHOC($DefEmp_ID, $search) // GOOD
	{
		/*$sql 	= "tbl_project_budg A WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
					AND A.PRJTYPE IN (1,2)";*/
		$sql 	= "tbl_project A WHERE A.PRJTYPE IN (1)";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataHOL($DefEmp_ID, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project A 
						WHERE
							-- A.PRJTYPE IN (1,2)
							A.PRJTYPE IN (1)
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project A 
						WHERE
							-- A.PRJTYPE IN (1,2)
							A.PRJTYPE IN (1)
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project A 
						WHERE
							-- A.PRJTYPE IN (1,2)
							A.PRJTYPE IN (1)
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project A 
						WHERE
							-- A.PRJTYPE IN (1,2)
							A.PRJTYPE IN (1)
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataHOFFC($DefEmp_ID, $search) // GOOD
	{
		/*$sql 	= "tbl_project_budg A WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
					AND A.PRJTYPE IN (1,2)";*/
		$sql 	= "tbl_project A WHERE A.PRJLEV = '1'";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataHOFFL($DefEmp_ID, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project A 
						WHERE
							-- A.PRJTYPE IN (1,2)
							A.PRJLEV = '1'
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project A 
						WHERE
							-- A.PRJTYPE IN (1,2)
							A.PRJLEV = '1'
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project A 
						WHERE
							-- A.PRJTYPE IN (1,2)
							A.PRJLEV = '1'
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project A 
						WHERE
							-- A.PRJTYPE IN (1,2)
							A.PRJLEV = '1'
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataLCC($DefEmp_ID, $search) // GOOD / MENDAFTAR SEMUA PERUSAHAAN (BUKAN BUDGET)
	{
		$sql 	= "tbl_project_budg A WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
					AND A.PRJTYPE IN (1,2)";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataLCL($DefEmp_ID, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project_budg A 
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.PRJTYPE IN (1,2)
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project_budg A 
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.PRJTYPE IN (1,2)
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project_budg A 
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.PRJTYPE IN (1,2)
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project_budg A 
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.PRJTYPE IN (1,2)
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_project($DefEmp_ID)  // OK
	{
		$this->db->select('APPLEV');
		$resGlobal = $this->db->get('tglobalsetting')->result();
		foreach($resGlobal as $row) :
		    $APPLEV = $row->APPLEV;
		endforeach;

		if($APPLEV == 'HO') // BERARTI HARUS MENAMPILKAN SEMUA TURUNAN DARI INDUK PERUSAHAAN YANG DISETTING PADA USER OTORISASI, DENGAN PRJTYPE TETAP 3
			$ADDQRY		= "A.PRJCODE_HO";
		else
			$ADDQRY		= "A.PRJCODE";

		$PRJTYPE    	= "3";
		if($APPLEV == 'PRJ')
		    $PRJTYPE    = "2";

		//$sql	= "tbl_project_budg WHERE PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		$sql	= "tbl_project A WHERE $ADDQRY IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_all_project($DefEmp_ID)  // OK
	{
		$this->db->select('APPLEV');
		$resGlobal = $this->db->get('tglobalsetting')->result();
		foreach($resGlobal as $row) :
		    $APPLEV = $row->APPLEV;
		endforeach;

		if($APPLEV == 'HO') // BERARTI HARUS MENAMPILKAN SEMUA TURUNAN DARI INDUK PERUSAHAAN YANG DISETTING PADA USER OTORISASI, DENGAN PRJTYPE TETAP 3
			$ADDQRY		= "A.PRJCODE_HO";
		else
			$ADDQRY		= "A.PRJCODE";

		$PRJTYPE    	= "3";
		if($APPLEV == 'PRJ')
		    $PRJTYPE    = "2";

		/*$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCODE_HO, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, A.PRJCOST, 
					A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
					A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
				FROM tbl_project_budg A 
				WHERE A.PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";*/
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCODE_HO, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, A.PRJCOST, 
					A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
					A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
				FROM tbl_project A 
				WHERE $ADDQRY IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function count_all_projectx($DefEmp_ID)  // OK
	{
		$sql	= "tbl_project_budg WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		//$sql	= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_all_projectx($DefEmp_ID)  // OK
	{
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCODE_HO, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, A.PRJCOST, 
					A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
					A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
				FROM tbl_project_budg A 
				WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
}
?>