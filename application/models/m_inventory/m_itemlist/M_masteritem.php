<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 29 Maret 2017
 * File Name	= M_user.php
 * Location		= -
*/
?>
<?php
class M_masteritem extends CI_Model
{
	//var $table = ''; //nama tabel dari database
	var $column_order = array(null, 'Kode_Brg','Nama_Brg','Nama_Satuan','Harga_Beli','Harga_Jual',
														'Nama_Merek','Nama_Kategori','Nama_Jenis','Nama_Supplier',
														'Nama_Lokasi','Stok','Stok_Minimal','Stok_Maximal','Images_Brg'); //field yang ada di table user
	var $column_search = array('Kode_Brg','Nama_Brg','Nama_Satuan','Harga_Beli','Harga_Jual',
														 'Nama_Merek','Nama_Kategori','Nama_Jenis','Nama_Supplier',
														 'Nama_Lokasi','Stok','Stok_Minimal','Stok_Maximal','Images_Brg'); //field yang diizin untuk pencarian
	var $order = array('Kode_Brg' => 'asc'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{

		$this->db->select('A1.*, A2.Nama_Satuan, A3.Nama_Merek, A4.Nama_Kategori, A5.Nama_Jenis, A6.Nama_Supplier, A7.Nama_Lokasi');
		$this->db->from('tbl_masteritem A1');
		$this->db->join('tbl_satuan_barang A2', 'A1.Kode_Satuan = A2.Kode_Satuan');
		$this->db->join('tbl_merek_barang A3', 'A1.Kode_Merek = A3.Kode_Merek');
		$this->db->join('tbl_kategori_barang A4', 'A1.Kode_Kategori = A4.Kode_Kategori');
		$this->db->join('tbl_jenis_barang A5', 'A1.Kode_Jenis = A5.Kode_Jenis');
		$this->db->join('tbl_supplier A6', 'A1.Kode_Supplier = A6.Kode_Supplier');
		$this->db->join('tbl_lokasi_barang A7', 'A1.Kode_Lokasi = A7.Kode_Lokasi');

		$i = 0;

		foreach ($this->column_search as $item) // loop column
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{

				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from('tbl_masteritem A1');
		$this->db->join('tbl_satuan_barang A2', 'A1.Kode_Satuan = A2.Kode_Satuan');
		$this->db->join('tbl_merek_barang A3', 'A1.Kode_Merek = A3.Kode_Merek');
		$this->db->join('tbl_kategori_barang A4', 'A1.Kode_Kategori = A4.Kode_Kategori');
		$this->db->join('tbl_jenis_barang A5', 'A1.Kode_Jenis = A5.Kode_Jenis');
		$this->db->join('tbl_supplier A6', 'A1.Kode_Supplier = A6.Kode_Supplier');
		$this->db->join('tbl_lokasi_barang A7', 'A1.Kode_Lokasi = A7.Kode_Lokasi');
		return $this->db->count_all_results();
	}

	function viewItemList()
	{
		$this->db->select('A1.*, A2.Nama_Satuan, A3.Nama_Merek, A4.Nama_Kategori, A5.Nama_Jenis, A6.Nama_Supplier, A7.Nama_Lokasi');
		$this->db->from('tbl_masteritem A1');
		$this->db->join('tbl_satuan_barang A2', 'A1.Kode_Satuan = A2.Kode_Satuan');
		$this->db->join('tbl_merek_barang A3', 'A1.Kode_Merek = A3.Kode_Merek');
		$this->db->join('tbl_kategori_barang A4', 'A1.Kode_Kategori = A4.Kode_Kategori');
		$this->db->join('tbl_jenis_barang A5', 'A1.Kode_Jenis = A5.Kode_Jenis');
		$this->db->join('tbl_supplier A6', 'A1.Kode_Supplier = A6.Kode_Supplier');
		$this->db->join('tbl_lokasi_barang A7', 'A1.Kode_Lokasi = A7.Kode_Lokasi');
		return $this->db->get();
	}

	function create_codeIL()
	{
		$this->db->select("RIGHT(A.Kode_Brg,5) AS kode", FALSE);
		$this->db->order_by('Kode_Brg', 'DESC');
		$this->db->limit(1);
		$query	= $this->db->get('tbl_masteritem A');
		if($query->num_rows() <> 0)
		{
			$data	= $query->row();
			$kode	= intval($data->kode) + 1;
		}
		else
		{
			$kode	= 1;
		}

		$kodemax	= str_pad($kode, 5, "0", STR_PAD_LEFT); // angka 4 usernjukkan jumlah digit angka 0
		$autocode	= "B".$kodemax;
		return $autocode;
	}

	function ViewupdIL()
	{
		$Kode_Brg = $this->input->post('Kode_Brg_1');
		return $this->db->get_where('tbl_masteritem', array('Kode_Brg' => $Kode_Brg))->result();
	}

	function ViewOptJenisKat($Kode_Kategori)
	{
		//return $this->db->get_where('tbl_jenis_barang', array('Kode_Kategori', $Kode_Kategori))->result();
		$this->db->where('Kode_Kategori', $Kode_Kategori);
		return $this->db->get('tbl_jenis_barang')->result();
	}

	function ViewOptJenisKat_E($Kode_Kategori_E)
	{
		//return $this->db->get_where('tbl_jenis_barang', array('Kode_Kategori', $Kode_Kategori))->result();
		$this->db->where('Kode_Kategori', $Kode_Kategori_E);
		return $this->db->get('tbl_jenis_barang')->result();
	}

	function addItemL($insItemL)
	{
		$this->db->insert('tbl_masteritem', $insItemL);
	}

	function updateItemL($Kode_Brg, $upIL)
	{
		$this->db->where('Kode_Brg', $Kode_Brg);
		$this->db->update('tbl_masteritem', $upIL);
	}

	function deleteIL()
	{
		$Kode_Brg_1 = $this->input->post('Kode_Brg_1');
		$expl = explode('|', $Kode_Brg_1);
		$lenExpl = count($expl);
		$cari = strpos($Kode_Brg_1, '|');
		if(!$cari){
			//delete QR Code
			$path_file = "uploads/QRCode/images/".$Kode_Brg_1.".png";
			if(file_exists($path_file))
				unlink($path_file);

			//delete data
			$this->db->where('Kode_Brg', $Kode_Brg_1);
			$this->db->delete('tbl_masteritem');
		}else{
			for($i=0;$i<$lenExpl;$i++){
				//delete QR Code
				$path_file = "uploads/QRCode/images/".$expl[$i].".png";
				if(file_exists($path_file))
					unlink($path_file);

				//delete data
				$this->db->where('Kode_Brg', $expl[$i]);
				$this->db->delete('tbl_masteritem');
			}
		}
	}

	function getTheBarcode($Kode_Barcode_Brg)
	{
		$query = "tbl_masteritem WHERE Kode_Barcode_Brg = '$Kode_Barcode_Brg'";
		return $this->db->count_all($query);
	}

	function getTheNama_Brg($Nama_Brg_1)
	{
		$query = "tbl_masteritem WHERE Nama_Brg = '$Nama_Brg_1'";
		return $this->db->count_all($query);
	}

	function viewCatItem()
	{
		return $this->db->get('tbl_kategori_barang');
	}

	function create_codeCI()
	{
		$this->db->select("RIGHT(A.Kode_Kategori,3) AS kode", FALSE);
		$this->db->order_by('Kode_Kategori', 'DESC');
		$this->db->limit(1);
		$query	= $this->db->get('tbl_kategori_barang A');
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
		$autocode	= "CIID".$kodemax;
		return $autocode;
	}

	function getTheCatI($Nama_Kategori_1)
	{
		$query = "tbl_kategori_barang WHERE Nama_Kategori = '$Nama_Kategori_1'";
		return $this->db->count_all($query);
	}

	function addCatItem($insCatItem)
	{
		$this->db->insert('tbl_kategori_barang', $insCatItem);
	}

	function ViewupdCatI()
	{
		$Kode_Kategori 	= $this->input->post('Kode_Kategori_1');
		$query					= $this->db->get_where('tbl_kategori_barang', array('Kode_Kategori' => $Kode_Kategori));
		return $query->result();
	}

	function updateCatItem($Kode_Kategori, $upCatI)
	{
		$this->db->where('Kode_Kategori', $Kode_Kategori);
		$this->db->update('tbl_kategori_barang', $upCatI);
	}

	function deleteCatI()
	{
		$Kode_Kategori 	= $this->input->post('Kode_Kategori_1');
		$expl = explode('|', $Kode_Kategori);
		$lenExpl = count($expl);
		$cari = strpos($Kode_Kategori, '|');
		if(!$cari){
			$this->db->where('Kode_Kategori', $Kode_Kategori);
			$this->db->delete('tbl_kategori_barang');
		}else{
			for($i=0;$i<$lenExpl;$i++){
				$this->db->where('Kode_Kategori', $expl[$i]);
				$this->db->delete('tbl_kategori_barang');
			}
		}
	}

	function viewTypeItem()
	{
		$this->db->select('A.*,B.Nama_Kategori');
		$this->db->from('tbl_jenis_barang A');
		$this->db->join('tbl_kategori_barang B', 'A.Kode_Kategori = B.Kode_Kategori');
		return $this->db->get();
	}

	function create_codeTyI()
	{
		$this->db->select("RIGHT(A.Kode_Jenis,3) AS kode", FALSE);
		$this->db->order_by('Kode_Jenis', 'DESC');
		$this->db->limit(1);
		$query	= $this->db->get('tbl_jenis_barang A');
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
		$autocode	= "TYIID".$kodemax;
		return $autocode;
	}

	function getTheTypeI($Nama_Jenis_1)
	{
		$query = "tbl_jenis_barang WHERE Nama_Jenis = '$Nama_Jenis_1'";
		return $this->db->count_all($query);
	}

	function addTyItem($insTyItem)
	{
		$this->db->insert('tbl_jenis_barang', $insTyItem);
	}

	function ViewupdTyI()
	{
		$Kode_Jenis_1	= $this->input->post('Kode_Jenis_1');
		$this->db->select('A.*,B.Nama_Kategori');
		$this->db->from('tbl_jenis_barang A');
		$this->db->join('tbl_kategori_barang B', 'A.Kode_Kategori = B.Kode_Kategori');
		return $this->db->where('A.Kode_Jenis', $Kode_Jenis_1)->get()->result();
	}

	function updateTyItem($Kode_Jenis, $upTyI)
	{
		$this->db->where('Kode_Jenis', $Kode_Jenis);
		$this->db->update('tbl_jenis_barang', $upTyI);
	}

	function deleteTyI()
	{
		$Kode_Jenis_1	= $this->input->post('Kode_Jenis_1');
		$expl = explode('|', $Kode_Jenis_1);
		$lenExpl = count($expl);
		$cari = strpos($Kode_Jenis_1, '|');
		if(!$cari){
			$this->db->where('Kode_Jenis', $Kode_Jenis_1);
			$this->db->delete('tbl_jenis_barang');
		}else{
			for($i=0;$i<$lenExpl;$i++){
				$this->db->where('Kode_Jenis', $expl[$i]);
				$this->db->delete('tbl_jenis_barang');
			}
		}
	}

	function viewBrandItem()
	{
		return $this->db->get('tbl_merek_barang');
	}

	function create_codeBrandI()
	{
		$this->db->select("RIGHT(A.Kode_Merek,3) AS kode", FALSE);
		$this->db->order_by('Kode_Merek', 'DESC');
		$this->db->limit(1);
		$query	= $this->db->get('tbl_merek_barang A');
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
		$autocode	= "BRDID".$kodemax;
		return $autocode;
	}

	function getTheBrandI($Nama_Merek_1)
	{
		$query = "tbl_merek_barang WHERE Nama_Merek = '$Nama_Merek_1'";
		return $this->db->count_all($query);
	}

	function addBrandItem($insBrandItem)
	{
		$this->db->insert('tbl_merek_barang', $insBrandItem);
	}

	function ViewupdBrandI()
	{
		$Kode_Merek_1	= $this->input->post('Kode_Merek_1');
		return $this->db->get_where('tbl_merek_barang', array('Kode_Merek' => $Kode_Merek_1))->result();
	}

	function updateBrandItem($Kode_Merek, $upBrandI)
	{
		$this->db->where('Kode_Merek', $Kode_Merek);
		$this->db->update('tbl_merek_barang', $upBrandI);
	}

	function deleteBrandI()
	{
		$Kode_Merek_1	= $this->input->post('Kode_Merek_1');
		$expl = explode('|', $Kode_Merek_1);
		$lenExpl = count($expl);
		$cari = strpos($Kode_Merek_1, '|');
		if(!$cari){
			$this->db->where('Kode_Merek', $Kode_Merek_1);
			$this->db->delete('tbl_merek_barang');
		}else{
			for($i=0;$i<$lenExpl;$i++){
				$this->db->where('Kode_Merek', $expl[$i]);
				$this->db->delete('tbl_merek_barang');
			}
		}
	}

	function viewUnitItem()
	{
		return $this->db->get('tbl_satuan_barang');
	}

	function create_codeUnitI()
	{
		$this->db->select("RIGHT(A.Kode_Satuan,3) AS kode", FALSE);
		$this->db->order_by('Kode_Satuan', 'DESC');
		$this->db->limit(1);
		$query	= $this->db->get('tbl_satuan_barang A');
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
		$autocode	= "UN".$kodemax;
		return $autocode;
	}

	function getTheUnitI($Nama_Satuan_1)
	{
		$query = "tbl_satuan_barang WHERE Nama_Satuan = '$Nama_Satuan_1'";
		return $this->db->count_all($query);
	}

	function addUnitItem($insUnitItem)
	{
		$this->db->insert('tbl_satuan_barang', $insUnitItem);
	}

	function ViewupdUnitI()
	{
		$Kode_Satuan_1	= $this->input->post('Kode_Satuan_1');
		return $this->db->get_where('tbl_satuan_barang', array('Kode_Satuan' => $Kode_Satuan_1))->result();
	}

	function updateUnitItem($Kode_Satuan, $upUnitI)
	{
		$this->db->where('Kode_Satuan', $Kode_Satuan);
		$this->db->update('tbl_satuan_barang', $upUnitI);
	}

	function deleteUnitI()
	{
		$Kode_Satuan_1	= $this->input->post('Kode_Satuan_1');
		$expl = explode('|', $Kode_Satuan_1);
		$lenExpl = count($expl);
		$cari = strpos($Kode_Satuan_1, '|');
		if(!$cari){
			$this->db->where('Kode_Satuan', $Kode_Satuan_1);
			$this->db->delete('tbl_satuan_barang');
		}else{
			for($i=0;$i<$lenExpl;$i++){
				$this->db->where('Kode_Satuan', $expl[$i]);
				$this->db->delete('tbl_satuan_barang');
			}
		}
	}

	function viewLocItem()
	{
		return $this->db->get('tbl_lokasi_barang');
	}

	function create_codeLocI()
	{
		$this->db->select("RIGHT(A.Kode_Lokasi,3) AS kode", FALSE);
		$this->db->order_by('Kode_Lokasi', 'DESC');
		$this->db->limit(1);
		$query	= $this->db->get('tbl_lokasi_barang A');
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
		$autocode	= "LOC".$kodemax;
		return $autocode;
	}

	function getTheLocI($Nama_Lokasi_1)
	{
		$query = "tbl_lokasi_barang WHERE Nama_Lokasi = '$Nama_Lokasi_1'";
		return $this->db->count_all($query);
	}

	function addLocItem($insLocItem)
	{
		$this->db->insert('tbl_lokasi_barang', $insLocItem);
	}

	function ViewupdLocI()
	{
		$Kode_Lokasi_1	= $this->input->post('Kode_Lokasi_1');
		return $this->db->get_where('tbl_lokasi_barang', array('Kode_Lokasi' => $Kode_Lokasi_1))->result();
	}

	function updateLocItem($Kode_Lokasi, $upLocI)
	{
		$this->db->where('Kode_Lokasi', $Kode_Lokasi);
		$this->db->update('tbl_lokasi_barang', $upLocI);
	}

	function deleteLocI()
	{
		$Kode_Lokasi_1	= $this->input->post('Kode_Lokasi_1');
		$expl = explode('|', $Kode_Lokasi_1);
		$lenExpl = count($expl);
		$cari = strpos($Kode_Lokasi_1, '|');
		if(!$cari){
			$this->db->where('Kode_Lokasi', $Kode_Lokasi_1);
			$this->db->delete('tbl_lokasi_barang');
		}else{
			for($i=0;$i<$lenExpl;$i++){
				$this->db->where('Kode_Lokasi', $expl[$i]);
				$this->db->delete('tbl_lokasi_barang');
			}
		}
	}
}
?>
