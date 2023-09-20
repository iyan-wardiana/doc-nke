<?php
/**
 * Author		= Dian Hermanto
 * Create Date	= 16 Maret 2014
 * File Name	= menu_model.php
 * Location		= ./system/application/model/menu_model.php
 */
class Menu_model extends CI_Model
{	
	function getAppName()
	{
		$this->db->select('app_id, app_name');
		return $this->db->get('tappname');
	}
	var $thetable = 'menu';
	function get_menu_by_user($Emp_ID)
	{
		/*$this->db->select('menu_id,menu_code,isHeader,level_menu,link_alias,menu_name,menu_user');
		$this->db->from('menu');
		$this->db->where('level_menu', 1);
		$this->db->where('menu_user', 1);
		//$this->db->where('menu.nis = menu.nis');
		$this->db->order_by('no_urut', 'asc');
		//$this->db->limit($limit, $offset);
		return $this->db->get();*/
		
		$sql = "SELECT A.menu_id, B.menu_code, A.isHeader, A.level_menu, A.link_alias, A.link_alias_sd, A.menu_name, A.fa_icon, A.menu_user
				FROM menu A
				LEFT JOIN tusermenu B ON A.menu_code = B.menu_code
				WHERE 
				A.menu_user =1
				AND A.level_menu =1
				AND emp_id = '$Emp_ID'
				ORDER BY A.no_urut";

		return $this->db->query($sql);
	}
	
	function get_submenu_by_user($Emp_ID, $menu_code)
	{		
		$sql = "SELECT A.menu_id, B.menu_code, A.isHeader, A.level_menu, A.link_alias, A.link_alias_sd, A.menu_name, A.fa_icon, A.menu_user
				FROM menu A
				INNER JOIN tusermenu B ON A.menu_code = B.menu_code
				WHERE 
				A.menu_user =1
				AND A.level_menu != 1
				AND emp_id = '$Emp_ID'
				AND parent_code = '$menu_code'
				ORDER BY A.no_urut";

		return $this->db->query($sql);
	}
	
	function get_subsubmenu_by_user($Emp_ID, $submenu_code)
	{		
		$sql = "SELECT A.menu_id, B.menu_code, A.isHeader, A.level_menu, A.link_alias, A.link_alias_sd, A.menu_name, A.fa_icon, A.menu_user
				FROM menu A
				INNER JOIN tusermenu B ON A.menu_code = B.menu_code
				WHERE 
				A.menu_user =1
				AND A.level_menu != 1
				AND emp_id = '$Emp_ID'
				AND parent_code = '$submenu_code'
				ORDER BY A.no_urut";

		return $this->db->query($sql);
	}
	
	function count_menutable()
	{
		return $this->db->count_all($this->thetable);
	}
	
	function creategraph()
    {
        $this->load->library('graph');
        $data_1 = array();
		
        /*for( $i=1; $i<=12; $i++ )
        {
            $data_1[] = rand(60,550);
        }*/
		
		$resGetData = $this->db->query("   
			select
			ifnull((SELECT sum(jumlah) FROM (pendapatan)WHERE((Month(tgl)=1)AND (YEAR(tgl)=2014))),0) AS 'Januari',
			ifnull((SELECT sum(jumlah) FROM (pendapatan)WHERE((Month(tgl)=2)AND (YEAR(tgl)=2014))),0) AS 'Februari',
			ifnull((SELECT sum(jumlah) FROM (pendapatan)WHERE((Month(tgl)=3)AND (YEAR(tgl)=2014))),0) AS 'Maret',
			ifnull((SELECT sum(jumlah) FROM (pendapatan)WHERE((Month(tgl)=4)AND (YEAR(tgl)=2014))),0) AS 'April',
			ifnull((SELECT sum(jumlah) FROM (pendapatan)WHERE((Month(tgl)=5)AND (YEAR(tgl)=2014))),0) AS 'Mei',
			ifnull((SELECT sum(jumlah) FROM (pendapatan)WHERE((Month(tgl)=6)AND (YEAR(tgl)=2014))),0) AS 'Juni',
			ifnull((SELECT sum(jumlah) FROM (pendapatan)WHERE((Month(tgl)=7)AND (YEAR(tgl)=2014))),0) AS 'Juli',
			ifnull((SELECT sum(jumlah) FROM (pendapatan)WHERE((Month(tgl)=8)AND (YEAR(tgl)=2014))),0) AS 'Agustus',
			ifnull((SELECT sum(jumlah) FROM (pendapatan)WHERE((Month(tgl)=9)AND (YEAR(tgl)=2014))),0) AS 'September',
			ifnull((SELECT sum(jumlah) FROM (pendapatan)WHERE((Month(tgl)=10)AND (YEAR(tgl)=2014))),0) AS 'Oktober',
			ifnull((SELECT sum(jumlah) FROM (pendapatan)WHERE((Month(tgl)=11)AND (YEAR(tgl)=2014))),0) AS 'November',
			ifnull((SELECT sum(jumlah) FROM (pendapatan)WHERE((Month(tgl)=12)AND (YEAR(tgl)=2014))),0) AS 'Desember'
			from pendapatan GROUP BY YEAR(tgl)
		")->result();
		
		foreach($resGetData as $rowData) :
			$data_1[] 	= $rowData->Januari;
			$data_1[] 	= $rowData->Februari;
			$data_1[] 	= $rowData->Maret;
			$data_1[] 	= $rowData->April;
			$data_1[] 	= $rowData->Mei;
			$data_1[] 	= $rowData->Juni;
			$data_1[] 	= $rowData->Agustus;
			$data_1[] 	= $rowData->September;
			$data_1[] 	= $rowData->Oktober;
			$data_1[] 	= $rowData->November;
			$data_1[] 	= $rowData->Desember;
		endforeach;
		
        $data_2 = array();
		
        for( $i=1; $i<=12; $i++ )
        {
            $data_2[] = $i;
        }
		
		$ValProg = "Nilai Progress ( Percent )";
        $ff = new graph();
        $ff->set_data( $data_1 );
        $ff->title( 'Perkembangan Proyek PT NKE, Tbk', '{font-size: 14px; color: #3D5570;font-family:calibri;}' );
        $ff->line_dot( 5, 5, '#8B6122', 'Garis Progress', 10 );
        $ff->bg_colour = '#FFFFFF';
        $ff->x_axis_colour('#818D9D', '#ADB5C7');
        $ff->y_axis_colour('#818D9D', '#ADB5C7');
        //$ff->set_x_labels(array( '25/12','26/12','27/12','28/12','29/12','30/12','31/12' ));
        $ff->set_x_labels(( $data_2 ));
        $ff->set_y_max( 100 ); // Jika = A
        $ff->y_label_steps( 10 ); // jIKA = B, maka jarak antar kolom (tinggi kolom) berarti A / B
        $ff->set_y_legend( $ValProg, 12, '#3D5570' ); // Text, Size, Color legenda y
        $ff->set_x_legend( 'Bulan', 12, '#3D5570' ); // Text, Size, Color legenda x
        $ff->set_output_type('js');
        $ff->width = '90%';
        $ff->height = '300';
        return $ff->render();
    } 
}
// END Menu_model Class

/* End of file menu_model.php */