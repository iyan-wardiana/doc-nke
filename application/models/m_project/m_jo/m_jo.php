<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Agustus 2017
 * File Name	= M_profit_loss.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Create Date	= 18 Oktober 2017
 * File Name	= m_jo.php
 * Location		= -
*/
?>
<?php
class m_jo extends CI_Model
{
	function count_all_project($DefEmp_ID) // U
	{
		$sql	= "tbl_project_jo";
		return $this->db->count_all($sql);
	}
	
	function get_all_project($DefEmp_ID) // U
	{
		$sql = "SELECT *
				FROM tbl_project_jo
				ORDER BY PRJCODE";
		return $this->db->query($sql);
	}
	
	function count_all_jo($PRJCODE) // U
	{
		$sql	= "tbl_jo_header A
					INNER JOIN tbl_project_jo B ON A.JO_PRJCODE = B.PRJCODE
					WHERE A.JO_PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_all_jo($PRJCODE) // U
	{
		$sql = "SELECT A.*, B.PRJCODE, B.PRJNAME
				FROM tbl_jo_header A
					INNER JOIN tbl_project_jo B ON A.JO_PRJCODE = B.PRJCODE
				WHERE A.JO_PRJCODE = '$PRJCODE'
					ORDER BY A.JO_CODE ASC";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // U
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_Proj_Item($PRJCODE) // U
	{
		$sql	= "tbl_jo_item";
		return $this->db->count_all($sql);
	}
	
	function viewProjItem($PRJCODE) // U
	{
		/*$sql		= "SELECT DISTINCT Z.PRJCODE, Z.CSTCODE, Z.Item_code, Z.unit_type_code, Z.unit_type_code2, Z.desc1,
						Z.PPMat_Qty, Z.PPMat_Qty2, Z.request_qty, Z.request_qty2, Z.PO_Qty, Z.PO_Qty2, Z.receipt_qty, Z.receipt_qty2,
						Z.used_qty, Z.used_qty2, Z.Unit_Price, Z.Amount, Z.Price, Z.TotPrice, B.Unit_Type_Name
						FROM tbl_projplan_material Z
						INNER JOIN tbl_unittype B ON B.unit_type_code = Z.unit_type_code
						WHERE Z.PRJCODE = '$PRJCODE' AND budgetCategory = 'MTRL'
						ORDER BY Z.Item_code";*/
		/*$sql		= "SELECT DISTINCT PRJCODE, CSTCODE, ITEM_CODE, CTGCODE, CSTDESC, CSTUNIT,
						CSTPUNT, CSTVOLM
						FROM tbl_cost
						WHERE CTGCODE IN ('MTRL','TOOLS') AND PRJCODE = '$PRJCODE'";*/
		$sql		= "SELECT * FROM tbl_jo_item ORDER BY JOI_CODE"; 
		return $this->db->query($sql);
	}
	
	function add($InpJo) // U
	{
		$this->db->insert('tbl_jo_header', $InpJo);
	}
	
	function updateGT($JO_CODE, $UpdGT) // U
	{
		$this->db->where('JO_CODE', $JO_CODE);
		$this->db->update('tbl_jo_header', $UpdGT);
	}
	
	function get_jo_det($JO_CODE) // U
	{
		$sql = "SELECT * FROM tbl_jo_header
				WHERE JO_CODE = '$JO_CODE'";
		return $this->db->query($sql);
	}
	
	function update($JO_CODE, $UpdJO) // U
	{
		$this->db->where('JO_CODE', $JO_CODE);
		$this->db->update('tbl_jo_header', $UpdJO);
	}
	
	function deleteDetail($JO_CODE) // U
	{
		$this->db->where('JO_CODE', $JO_CODE);
		$this->db->delete('tbl_jo_detail');
	}
	
/*	function count_all_asset_exp($PRJCODE) // U
	{
		$sql	= "tbl_assetexp_header A
					INNER JOIN tbl_project B ON A.RASSET_PROJECT = B.PRJCODE
					WHERE A.RASSET_PROJECT = '$PRJCODE'";
		return $this->db->count_all($sql);
	}*/
	
/*	function get_all_asset_exp($PRJCODE) // U
	{
		$sql = "SELECT A.*, B.PRJCODE, B.PRJNAME
				FROM tbl_assetexp_header A
					INNER JOIN tbl_project B ON A.RASSET_PROJECT = B.PRJCODE
				WHERE A.RASSET_PROJECT = '$PRJCODE'
					ORDER BY A.RASSET_CODE ASC";
		return $this->db->query($sql);
	}*/
	
/*	function count_Asset($PRJCODE) // U
	{
		return $this->db->count_all('tbl_asset_list');
	}*/
	
/*	function viewAsset($PRJCODE) // U
	{
		$sql		= "SELECT DISTINCT Z.AS_CODE, Z.AG_CODE, Z.AS_NAME, Z.AS_DESC, Z.AS_BRAND, Z.AS_HOPR,
						Z.AS_HM, Z.AS_STAT, Z.AS_PRICE
						FROM tbl_asset_list Z WHERE Z.AS_LASTPOS = '$PRJCODE' LIMIT 50";
		return $this->db->query($sql);
	}*/
	
/*	function addAstExp($InpAssetExp) // U
	{
		$this->db->insert('tbl_assetexp_header', $InpAssetExp);
	}*/
	
/*	function get_stock_astExp_det($RASSET_CODE) // U
	{
		$sql = "SELECT * FROM tbl_assetexp_header
				WHERE RASSET_CODE = '$RASSET_CODE'";
		return $this->db->query($sql);
	}*/
	
/*	function updAstExp($RASSET_CODE, $UpdAssetExp) // U
	{
		$this->db->where('RASSET_CODE', $RASSET_CODE);
		$this->db->update('tbl_assetexp_header', $UpdAssetExp);
	}*/
	
/*	function deleteAstDetail($RASSET_CODE) // U
	{
		$this->db->where('RASSETD_CODE', $RASSET_CODE);
		$this->db->delete('tbl_assetexp_detail');
	}*/
	
/*	function addCon($InpConcl) // U
	{
		$this->db->insert('tbl_sopn_concl', $InpConcl);
	}*/
	
/*	function addConAst($InpConcl) // U
	{
		$this->db->insert('tbl_assetexp_concl', $InpConcl);
	}*/
	
/*	function count_all_overhead($PRJCODE) // U
	{
		$sql	= "tbl_overhead A
					INNER JOIN tbl_project B ON A.OVH_PRJCODE = B.PRJCODE
					WHERE A.OVH_PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}*/
	
/*	function get_all_overhead($PRJCODE) // U
	{
		$sql = "SELECT A.*, B.PRJCODE, B.PRJNAME
				FROM tbl_overhead A
					INNER JOIN tbl_project B ON A.OVH_PRJCODE = B.PRJCODE
					WHERE A.OVH_PRJCODE = '$PRJCODE'
					ORDER BY A.OVH_PERIODE ASC";
		return $this->db->query($sql);
	}*/
	
/*	function addOVH($InpOVH) // U
	{
		$this->db->insert('tbl_overhead', $InpOVH);
	}*/
	
/*	function get_ovh_det($OVH_CODE) // U
	{
		$sql = "SELECT * FROM tbl_overhead WHERE OVH_CODE = '$OVH_CODE'";
		return $this->db->query($sql);
	}*/
	
/*	function updOVH($OVH_CODE, $UpdOVH) // U
	{
		$this->db->where('OVH_CODE', $OVH_CODE);
		$this->db->update('tbl_overhead', $UpdOVH);
	}*/
}
?>