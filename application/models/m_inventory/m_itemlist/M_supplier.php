<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 29 Maret 2017
 * File Name	= M_user.php
 * Location		= -
*/
?>
<?php
class M_supplier extends CI_Model
{
	function viewSPLList()
	{
		return $this->db->get('tbl_supplier');
	}

	function create_codeSPLL()
	{
		$this->db->select("RIGHT(A.Kode_Supplier,3) AS kode", FALSE);
		$this->db->order_by('Kode_Supplier', 'DESC');
		$this->db->limit(1);
		$query	= $this->db->get('tbl_supplier A');
		if($query->num_rows() <> 0)
		{
			$data	= $query->row();
			$kode	= intval($data->kode) + 1;
		}
		else
		{
			$kode	= 1;
		}

		$kodemax	= str_pad($kode, 3, "0", STR_PAD_LEFT); // angka 4 usernjukkan jumlah digit angka 0
		$autocode	= "SPL".$kodemax;
		return $autocode;
	}

	function getTheSPLL($Nama_Supplier_1)
	{
		$query = "tbl_supplier WHERE Nama_Supplier = '$Nama_Supplier_1'";
		return $this->db->count_all($query);
	}

	function addSPLList($insSPLL)
	{
		$this->db->insert('tbl_supplier', $insSPLL);
	}

	function ViewupdSPLL()
	{
		$Kode_Supplier 	= $this->input->post('Kode_Supplier_1');
		return $this->db->get_where('tbl_supplier', array('Kode_Supplier' => $Kode_Supplier))->result();
	}

	function updateSPLList($Kode_Supplier, $upSPLL)
	{
		$this->db->where('Kode_Supplier', $Kode_Supplier);
		$this->db->update('tbl_supplier', $upSPLL);
	}

	function deleteSPLL()
	{
		$Kode_Supplier 	= $this->input->post('Kode_Supplier_1');
		$expl = explode('|', $Kode_Supplier);
		$lenExpl = count($expl);
		$cari = strpos($Kode_Supplier, '|');
		if(!$cari){
			$this->db->where('Kode_Supplier', $Kode_Supplier);
			$this->db->delete('tbl_supplier');
		}else{
			for($i=0;$i<$lenExpl;$i++){
				$this->db->where('Kode_Supplier', $expl[$i]);
				$this->db->delete('tbl_supplier');
			}
		}
	}
}
?>
