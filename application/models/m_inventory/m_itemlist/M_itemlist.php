<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 5 April 2017
	* File Name		= M_itemlist.php
	* Location		= -
*/
?>
<?php
class M_itemlist extends CI_Model
{
	//var $table = ''; //nama tabel dari database
	var $column_order = array(null, 'ITM_CODE','ITM_NAME','ITM_VOLM','ITM_PRICE','ITM_UNIT');
	var $column_search = array('ITM_CODE','ITM_NAME','ITM_VOLM','ITM_PRICE','ITM_UNIT');
	var $order = array('ITM_NAME' => 'asc'); // default order
	
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function count_all_num_rows($PRJCODE, $PRJPERIOD) // OK
	{
		$sql	= "tbl_item A
					LEFT JOIN tbl_unittype B ON A.UMCODE = B.UMCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.PRJPERIOD = '$PRJPERIOD'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_item($PRJCODE, $PRJPERIOD) // OK
	{
		$sql	= "SELECT A.*,
					B.UMCODE, B.Unit_Type_Name
					FROM  tbl_item A
					LEFT JOIN tbl_unittype B ON A.UMCODE = B.UMCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.PRJPERIOD = '$PRJPERIOD'
					ORDER BY ITM_DESC ASC";
		return $this->db->query($sql);
	}
	
	function get_AllDataC($PRJCODE, $PRJPERIOD, $search) // GOOD
	{
		$sql = "tbl_item A
				WHERE A.PRJCODE = '$PRJCODE' AND A.PRJPERIOD = '$PRJPERIOD'
					AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $PRJPERIOD, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			/*$sql = "SELECT A.*
					FROM tbl_item A
					WHERE A.PRJCODE = '$PRJCODE' AND A.PRJPERIOD = '$PRJPERIOD' AND A.ITM_GROUP = 'M'
						AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!')";*/
			$sql = "SELECT A.*
					FROM tbl_item A
					WHERE A.PRJCODE = '$PRJCODE' AND A.PRJPERIOD = '$PRJPERIOD'
						AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.*
					FROM tbl_item A
					WHERE A.PRJCODE = '$PRJCODE' AND A.PRJPERIOD = '$PRJPERIOD'
						AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataGRPC($PRJCODE, $PRJPERIOD, $ITM_GROUP, $ITM_CATEG, $search) // GOOD
	{
		$ADDQRY1 	= " AND A.ITM_GROUP = '$ITM_GROUP'";
		if($ITM_GROUP == '')
			$ADDQRY1 	= "";

		$ADDQRY2 	= " AND A.ITM_CATEG = '$ITM_CATEG'";
		if($ITM_CATEG == '')
			$ADDQRY2 	= "";

		$sql = "tbl_item A
				WHERE A.PRJCODE = '$PRJCODE' AND A.PRJPERIOD = '$PRJPERIOD' $ADDQRY1 $ADDQRY2
					AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataGRPL($PRJCODE, $PRJPERIOD, $ITM_GROUP, $ITM_CATEG, $search, $length, $start, $order, $dir) // GOOD
	{
		$ADDQRY1 	= " AND A.ITM_GROUP = '$ITM_GROUP'";
		if($ITM_GROUP == '')
			$ADDQRY1 	= "";

		$ADDQRY2 	= " AND A.ITM_CATEG = '$ITM_CATEG'";
		if($ITM_CATEG == '')
			$ADDQRY2 	= "";

		if($length == -1)
		{
			/*$sql = "SELECT A.*
					FROM tbl_item A
					WHERE A.PRJCODE = '$PRJCODE' AND A.PRJPERIOD = '$PRJPERIOD' AND A.ITM_GROUP = 'M'
						AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!')";*/
			$sql = "SELECT A.*
					FROM tbl_item A
					WHERE A.PRJCODE = '$PRJCODE' AND A.PRJPERIOD = '$PRJPERIOD' $ADDQRY1 $ADDQRY2
						AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.*
					FROM tbl_item A
					WHERE A.PRJCODE = '$PRJCODE' AND A.PRJPERIOD = '$PRJPERIOD' $ADDQRY1 $ADDQRY2
						AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			return $this->db->query($sql);
		}
	}
	
	function count_all_num_rowsUnit() // OK
	{
		return $this->db->count_all('tbl_unittype');
	}
	
	function viewUnit() // OK
	{
		$this->db->select('Unit_Type_Code, UMCODE, Unit_Type_Name');
		$this->db->from('tbl_unittype');
		$this->db->order_by('UMCODE', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rowsCateg() // OK
	{
		return $this->db->count_all('tbl_itemcategory');
	}
	
	function viewCateg() // OK
	{
		$LangID = $this->session->userdata['LangID'];
		$sql	= "SELECT IC_Num, IC_Code, IC_Name AS ItemCategory_name
					FROM  tbl_itemcategory ORDER BY IC_Num ASC";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsProject() // OK
	{
		return $this->db->count_all('tbl_project');
	}
	
	function viewProject() // OK
	{		
		$sql = "SELECT A.proj_Number, A.proj_Code, A.proj_Name, A.proj_Date, A.proj_StartDate, A.proj_EndDate, A.proj_addDate, A.Proj_ownCode, A.proj_ContractNo, 
				A.proj_Status, A.proj_Location, A.proj_Type, A.proj_Category, A.proj_Currency, A.Patt_Year, A.proj_notes, A.Patt_Number, 
				A.proj_CurrencyRate, A.proj_amountUSD, A.proj_amountIDR, A.proj_MP, A.PRJLKOT, A.isActive,
				B.own_Code, B.own_Title, B.own_Name
				FROM tbl_project A
				LEFT JOIN  towner B ON A.proj_ownCode = B.own_Code";
		return $this->db->query($sql);
	}
	
	function add($itemPar) // OK
	{
		$this->db->insert('tbl_item', $itemPar);
	}
	
	function add_importitem($ItemHist) // OK
	{
		$this->db->insert('tbl_item_uphist', $ItemHist);
	}
	
	function updateStat() // 
	{
		$sql = "UPDATE tbl_item_uphist SET ITMH_STAT = 0";
		$this->db->query($sql);
	}
	
	function viewemployee()
	{
		$query = $this->db->get('tbl_cost');
		return $query->result(); 
	}
	
	var $table = 'tbl_cost';
	
	function searchitem($konstSearch)
	{
		$selSearchType 	= $this->input->POST ('selSearchType');
		$txtSearch 		= $this->input->POST ('txtSearch');
		if($selSearchType == $konstSearch)
		{
			$this->db->like('Item_Code', $txtSearch);
		}
		else
		{
			$this->db->like('ITM_DESC', $txtSearch);
		}
		$query = $this->db->get('tbl_cost');
		return $query->result(); 
	}
	
	function get_item_by_code($ITM_CODE, $PRJCODE, $PRJPERIOD)
	{
		$sql	= "SELECT A.*,
					B.UMCODE, B.Unit_Type_Name
					FROM  tbl_item A
					LEFT JOIN tbl_unittype B ON A.UMCODE = B.UMCODE
					WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
						-- AND A.PRJPERIOD = '$PRJPERIOD'
					ORDER BY ITM_DESC ASC";
		return $this->db->query($sql);
	}
	
	function update($ITM_CODE, $itemPar)
	{	
		$PRJCODE		= $itemPar['PRJCODE'];
		$ITM_CODE		= $itemPar['ITM_CODE'];
		$ITM_CATEG		= $itemPar['ITM_CATEG'];
		$ITM_NAME		= $itemPar['ITM_NAME'];
		$ITM_DESC		= $itemPar['ITM_DESC'];
		$ITM_TYPE		= $itemPar['ITM_TYPE'];
		$ITM_UNIT		= $itemPar['ITM_UNIT'];
		$UMCODE			= $itemPar['ITM_UNIT'];
		$ITM_CURRENCY	= $itemPar['ITM_CURRENCY'];
		$ITM_PRICE		= $itemPar['ITM_PRICE'];
		$ITM_TOTALP		= $itemPar['ITM_TOTALP'];
		$ITM_VOLMBG		= $itemPar['ITM_VOLMBG'];
		$ITM_VOLMBGR	= $itemPar['ITM_VOLMBGR'];
		$ITM_VOLM		= $itemPar['ITM_VOLM'];
		$STATUS			= $itemPar['STATUS'];
		$ISMTRL			= $itemPar['ISMTRL'];
		$ISRENT			= $itemPar['ISRENT'];
		$ISPART			= $itemPar['ISPART'];
		$ISFUEL			= $itemPar['ISFUEL'];
		$ISLUBRIC		= $itemPar['ISLUBRIC'];
		$ISFASTM		= $itemPar['ISFASTM'];
		$ISWAGE			= $itemPar['ISWAGE'];
		$ITM_KIND		= $itemPar['ITM_KIND'];
		$ACC_ID			= $itemPar['ACC_ID'];
		$ACC_ID_UM		= $itemPar['ACC_ID_UM'];
		$ITM_IN			= $itemPar['ITM_IN'];
		$ITM_INP		= $itemPar['ITM_INP'];
		
		$sqlUpd		= "UPDATE tbl_item SET ITM_CATEG = '$ITM_CATEG', ITM_NAME = '$ITM_NAME', ITM_DESC = '$ITM_DESC',
							ITM_UNIT = '$ITM_UNIT', UMCODE = '$ITM_UNIT',  ITM_TOTALP = '$ITM_TOTALP', ITM_VOLMBG = '$ITM_VOLMBG', 
							ITM_CURRENCY = '$ITM_CURRENCY', ITM_PRICE = '$ITM_PRICE', ITM_VOLM = '$ITM_VOLM', STATUS = '$STATUS',
							ISMTRL = '$ISMTRL', ISRENT = '$ISRENT', ISPART = '$ISPART', ISFUEL = '$ISFUEL', ISLUBRIC = '$ISLUBRIC',
							ISFASTM = '$ISFASTM', ISWAGE = '$ISWAGE', ITM_KIND = '$ITM_KIND', ACC_ID = '$ACC_ID', 
							ACC_ID_UM = '$ACC_ID_UM', ITM_IN = '$ITM_IN', ITM_INP = '$ITM_INP'
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
		//return $this->db->query($sqlUpd);
		$this->db->where('PRJCODE', $PRJCODE);
		//$this->db->where('PRJPERIOD', $PRJPERIOD);
		$this->db->where('ITM_CODE', $ITM_CODE);
		$this->db->update('tbl_item', $itemPar);
	}
	
	function delete($Item_Code)
	{
		$PRJCODE = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
		$this->db->where('PRJCODE', $PRJCODE);
		$this->db->where('Item_Code', $Item_Code);
		$this->db->update($this->table);
	}
	
	function updateStatus($CodeItem, $NItemStatus)
	{
		$PRJCODE = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
		$sql2		= "UPDATE tbl_cost SET STATUS	= '$NItemStatus'
						WHERE Item_code = '$CodeItem' AND PRJCODE = '$PRJCODE'";
		$this->db->query($sql2);
	}
	
	// Searching Function
	function count_all_num_rows_src_ic($txtSearch, $selSearchproj_Code)
	{
		$sql	= "tbl_cost A
					INNER JOIN tbl_unittype B ON A.UMCODE = B.UMCODE
					WHERE A.Item_Code LIKE '%$txtSearch%' AND PRJCODE = '$selSearchproj_Code'";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_src_in($txtSearch, $selSearchproj_Code)
	{		
		$sql	= "tbl_cost WHERE ITM_DESC LIKE '%$txtSearch%' AND PRJCODE = '$selSearchproj_Code'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_item_ic($limit, $offset, $txtSearch, $selSearchproj_Code)
	{
		$sql	= "SELECT A.PRJCODE, A.ITM_CODE, A.Item_Code, A.ITM_CATEG, A.ITM_DESC, A.ITM_TYPE, A.ITM_UNIT, A.UMCODE, A.ITM_PRICE, A.ITM_VOLM,  A.STATUS, A.LASTNO,
					B.UMCODE, B.Unit_Type_Name
					FROM  tbl_cost A
					INNER JOIN tbl_unittype B ON A.UMCODE = B.UMCODE
					WHERE A.Item_Code LIKE '%$txtSearch%' AND PRJCODE = '$selSearchproj_Code'
					ORDER BY ITM_DESC ASC
					LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function get_last_ten_item_in($limit, $offset, $txtSearch, $selSearchproj_Code)
	{
		$sql	= "SELECT A.PRJCODE, A.ITM_CODE, A.Item_Code, A.ITM_CATEG, A.ITM_DESC, A.ITM_TYPE, A.ITM_UNIT, A.UMCODE, A.ITM_PRICE, A.ITM_VOLM,  A.STATUS, A.LASTNO,
					B.UMCODE, B.Unit_Type_Name
					FROM  tbl_cost A
					INNER JOIN tbl_unittype B ON A.UMCODE = B.UMCODE
					WHERE A.ITM_DESC LIKE '%$txtSearch%'
						AND A.PRJCODE = '$selSearchproj_Code'
					ORDER BY A.ITM_DESC ASC
					LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode)
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tdocpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_Item($PRJCODE, $ITM_GROUP) // OK
	{
		$sql	= "tbl_joblist_detail A
					INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE' AND B.ITM_GROUP = '$ITM_GROUP'";
		return $this->db->count_all($sql);
	}
	
	function view_all_Item($PRJCODE, $ITM_GROUP) // OK
	{
		
		$sql	= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, 
					A.ITM_VOLM, A.ADD_VOLM, A.ADD_PRICE, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
					A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
					A.JOBDESC AS ITM_NAME
					FROM tbl_joblist_detail A
					INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE' AND B.ITM_GROUP = '$ITM_GROUP'
					WHERE A.PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
}
?>