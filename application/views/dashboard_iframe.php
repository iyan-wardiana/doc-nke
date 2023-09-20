<?php
    // _global function
        $this->db->select('Display_Rows,decFormat,CompDesc');
        $resGlobal = $this->db->get('tglobalsetting')->result();
        foreach($resGlobal as $row) :
            $Display_Rows   = $row->Display_Rows;
            $decFormat    = $row->decFormat;
            $CompDesc     = $row->CompDesc;
        endforeach;
        $decFormat  = 2;

        date_default_timezone_set("Asia/Jakarta");

		$tNow    	= date('Y-m-d');
		$tNDate1    = date('Y-m-t', strtotime('-11 month', strtotime($tNow)));
		$tY       	= date('Y', strtotime($tNDate1));
		$tM       	= date('n', strtotime($tNDate1));

        $myPRJCODE  = '';

        $this->load->view('template/head');

        $appName 	= $this->session->userdata('appName');
		$vers     	= $this->session->userdata['vers'];
		$FlagUSER 	= $this->session->userdata['FlagUSER'];
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$Emp_ID 	= $this->session->userdata['Emp_ID'];
		$appBody    = $this->session->userdata['appBody'];
		$sysMnt		= $this->session->userdata['sysMnt'];
		$LastMntD	= $this->session->userdata['LastMntD'];
		$PRJSCATEG	= $this->session->userdata['PRJSCATEG'];
		$tgl1 		= new DateTime($LastMntD);
		$tgl2 		= new DateTime();
		$dif1 		= $tgl1->diff($tgl2);
		$dif2 		= $dif1->days;

		$cLogV		= "tbl_emp_vers WHERE EMP_ID = '$Emp_ID' AND VERS = '$vers'";
		$vLogV		= $this->db->count_all($cLogV);

		/*$isEnd 		= 0;
		if($sysMnt == 1 && $tgl2 >= $tgl1)
		{
			$isEnd 	= 2;
		}
		elseif($sysMnt == 1 && $dif2 < 6) 
		{
			$isEnd 	= 1;
		}*/

		$lasTDMnt	= date('d M Y', strtotime($LastMntD));
		$mntWarn1	= "";
		$mntWarn2	= "";

		$LangID 	= $this->session->userdata['LangID'];

		if($LangID == 'IND')
		{
			$transL01	= "Perangkat lunak ini mutahkir.";
			$transL02	= "1stWeb v$vers berhasil dipasang. Sembunyikan pesan ini.";
			$transL03	= "Anda tidak akan melihat peringatan ini di lain waktu. Terimakasih.";
			$transL04	= "Anda akan tetap melihat peringatan ini di saat Anda Log In. Terimakasih.";
			$transL05	= "Ya";
			$transL06	= "Tidak";
			$transL07	= "Anda dapat mengganti warna template.";
			$transL08	= "Cara Penggunaan";
			$transL09	= "Klik ikon pengaturan pada kanan atas, kemudian pilih warna.";

			/*if($isEnd == 1)
			{
				$mntWarn1	= "Perawatan Sistem";
				$mntWarn2	= "Akan berakhir pada $lasTDMnt.";
			}
			else if($isEnd == 2)
			{
				$mntWarn1	= "Perawatan Sistem";
				$mntWarn2	= "sudah berkahir pada $lasTDMnt.";
			}*/
		}
		else
		{
			$transL01	= "The software is up to date.";
			$transL02	= "1stWeb v$vers successfully installed. Please hide this message.";
			$transL03	= "You will not see this warning again. Thank you.";
			$transL04	= "You will still see this warning when you log in. Thank you.";
			$transL05	= "Yes";
			$transL06	= "No";
			$transL07	= "You can change the template color.";
			$transL08	= "How to use";
			$transL09	= "Click the settings icon on the top right. And select the color.";

			/*if($isEnd == 1)
			{
				$mntWarn1	= "System Maintenance";
				$mntWarn2	= "will expire on $lasTDMnt.";
			}
			else if($isEnd == 2)
			{
				$mntWarn1	= "System Maintenance";
				$mntWarn2	= "has expired on $lasTDMnt.";
			}*/
		}

        //$this->load->view('template/topbar');
        //$this->load->view('template/sidebar');

        $LOGHIST  = 1;

        $WEEK_BEF1  = mktime(0, 0, 0, date("m"), date("d")-31, date("Y"));
        $WEEK_BEF = date("Y-m-d", $WEEK_BEF1);
        $WEEK_NEXT1 = mktime(0, 0, 0, date("m"), date("d")+2, date("Y"));
        $WEEK_NEXT  = date("Y-m-d", $WEEK_NEXT1);
        $THISD    = date('d');
        $THISM    = date('m');
        $THISY    = date('Y');

    // CHECK LOG ON
        $TOTE       = 0;
        $TOTOL      = 0;
        $TOTOF      = 0;
        $sqlTLE     = "SELECT COUNT(*) AS TOTENTER
                        FROM tbl_trail_tracker
                        WHERE
                          DAY(TTR_DATE) = '$THISD' AND MONTH(TTR_DATE) = '$THISM' AND YEAR(TTR_DATE) = '$THISY' 
                          AND TTR_CATEG = 'ENTER'
                        GROUP BY
                        DATE(TTR_DATE)";
        $resTLE     = $this->db->query($sqlTLE)->result();
        foreach($resTLE as $rowTLE) :
            $TOTE   = $rowTLE->TOTENTER;
        endforeach;
        $sqlTOL     = "SELECT COUNT(*) AS TOTOL FROM tbl_employee WHERE OLStat = 1";
        $resTOL     = $this->db->query($sqlTOL)->result();
        foreach($resTOL as $rowTOL) :
            $TOTOL  = $rowTOL->TOTOL;
        endforeach;
        $sqlTOF     = "SELECT COUNT(*) AS TOTOF FROM tbl_employee WHERE OLStat = 0";
        $resTOF     = $this->db->query($sqlTOF)->result();
        foreach($resTOF as $rowTOF) :
            $TOTOF  = $rowTOF->TOTOF;
        endforeach;
        $TOTE   = $TOTOL + $TOTOF;

    // Menampilkan Grafik Project Plan Akumulate
        $jumTotPlanAkuma  = '';
        $GraphicTitleText = 3;

    // URL
	    $imgLoc		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
		$showAllOUT = site_url('__180c2f/prjlist/?id='.$this->url_encryption_helper->encode_url($appName));
		$showAboutC = site_url('__180c2f/aboutcomp/?id='.$this->url_encryption_helper->encode_url($appName));
		$logURL 	= site_url('__180c2f/crtLogV/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
		$GraphicTitleText	= 3;						
?>
<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <title><?php echo $appName; ?></title>
	    <!-- Tell the browser to be responsive to screen width -->
	    <?php
		    $vers     = $this->session->userdata['vers'];

	        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
	        $rescss = $this->db->query($sqlcss)->result();
	        foreach($rescss as $rowcss) :
	            $cssjs_lnk  = $rowcss->cssjs_lnk;
	            ?>
	                <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
	            <?php
	        endforeach;

	        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
	        $rescss = $this->db->query($sqlcss)->result();
	        foreach($rescss as $rowcss) :
	            $cssjs_lnk1  = $rowcss->cssjs_lnk;
	            ?>
	                <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
	            <?php
        	endforeach;
		?>

		<!-- Google Font -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>

	<?php
		$data['DCode'] 			= $DCode;
		$data['defNm'] 			= $defNm;
		$data['defMn'] 			= $defMn;
		
		if(!isset($defMn))
			$data['defMn'] 		= "site_url('__180c2f/dahsBoard/?id=')";

		// Top Bar
  			$this->load->view('template/topbar');

		// Left side column. contains the logo and sidebar
  			$this->load->view('template/sidebar', $data);

		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];	
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl	= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$Translate	= $rowTransl->LangTransl;
			if($TranslCode == 'Dashboard')$Dashboard = $Translate;
			if($TranslCode == 'DailyNews')$DailyNews = $Translate;
			if($TranslCode == 'Supply')$Supply = $Translate;
			if($TranslCode == 'WIP')$WIP = $Translate;
			if($TranslCode == 'Waste')$Waste = $Translate;
			if($TranslCode == 'Sales')$Sales = $Translate;
			if($TranslCode == 'SalesOrder')$SalesOrder = $Translate;
			if($TranslCode == 'Production')$Production = $Translate;
			if($TranslCode == 'Shipment')$Shipment = $Translate;

			if($TranslCode == 'PurchaseRequest')$PurchaseRequest = $Translate;
			if($TranslCode == 'Purchase')$Purchase = $Translate;
			if($TranslCode == 'MtrReceipt')$MtrReceipt = $Translate;
			if($TranslCode == 'WorkOrder')$WorkOrder = $Translate;
			if($TranslCode == 'JobOpname')$JobOpname = $Translate;
			if($TranslCode == 'mtrUsed')$mtrUsed = $Translate;
			if($TranslCode == 'mcprj')$mcprj = $Translate;
			if($TranslCode == 'InvoiceRealization')$InvoiceRealization = $Translate;
		endforeach;
		
		$sql001   	= "SELECT browsername FROM browser";
		$res001 	= $this->db->query($sql001)->result();
		foreach($res001 as $row001) :
				$browsername 	= $row001->browsername;
			$trendbrowser	= $browsername;    
			$sql002   	= "SELECT total FROM browser WHERE browsername = '$trendbrowser'";
			$res002 	= $this->db->query($sql002)->result();
			foreach($res002 as $row002) :
				$total 	= $row002->total;
			endforeach;
		endforeach;
		
		$thisDay	= (int)date('d');
		$modDay		= $thisDay % 6;
		if($modDay == 0)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading.gif";
		}
		elseif($modDay == 1)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif";
		}
		elseif($modDay == 2)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading2.gif";
		}
		elseif($modDay == 3)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading3.gif";
		}
		elseif($modDay == 4)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading4.gif";
		}
		elseif($modDay == 5)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading5.gif";
		}

		if($LangID == 'IND')
		{
			$transL1	= "Mengaktfikan 'Fixed Layout'. Anda tidak dapat menggunakan 'Fixed Layout' dan 'Boxed Layout' bersama-sama.";
			$transL2	= "Mengaktifkan 'Boxed Layout'.";
			$transL3	= "Sembunyikan menu utama sisi kiri.";
			$transL4	= "SO Confirmed vs Approved";
			$transL5	= "Aktifitas";
			$transL6	= "Persediaan bahan produksi";
			$transL7	= "Sales Order disetujui per tahun berjalan";
			$transL8	= "Persediaan material dalam proses";
			$transL9	= "Persediaan barang jadi";
			$transL10	= "Progres Penyelesaian";
			$transL11	= "Informasi Umum";
			$transL12	= "Latest Login";
		}
		else
		{
			$transL1	= "Activate the 'Fixed Layout'. You can't use 'Fixed Layout' and 'Boxed Layout' together.";
			$transL2	= "Activate the boxed layout";
			$transL3	= "Toggle the left sidebar's state.";
			$transL4	= "SO Confirmed vs Approved";
			$transL5	= "Activity Graph";
			$transL6	= "Inventory of production materials";
			$transL7	= "Sales Order Approved Annualy";
			$transL8	= "Material inventory in process";
			$transL9	= "Finished goods inventory";
			$transL10	= "Completion Progress";
			$transL11	= "General Information";
			$transL12	= "Latest Login";
		}
	?>
</html>

<?php
	$addDt 		= 0;
	$getPRDQ 	= base_url().'index.php/__l1y/getPRDQ/?id=';
	$cIMG		= base_url('assets/AdminLTE-2.0.5/cust_image/');
	$secIMG 	= base_url().'index.php/__l1y/getCUSTIMG/?id=';
?>