<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Maret 2020
 * File Name	= v_riskasm_dl.php
 * Location		= -
*/

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=RASSESM_".date('dmYHis').".xls");
header("Pragma: no-cache");
header("Expires: 0");


/*$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody 	= $this->session->userdata['appBody'];
*/
//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

$empNameAct	= '';
/*$sqlEMP 	= "SELECT CONCAT(First_Name, ' ', Last_Name) AS empName
				FROM tbl_employee
				WHERE Emp_ID = '$DefEmp_ID'";
$resEMP 	= $this->db->query($sqlEMP)->result();
foreach($resEMP as $rowEMP) :
	$empNameAct	= $rowEMP->empName;
endforeach;*/
?>
<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <title>AdminLTE 2 | Dashboard</title>
	    <!-- Tell the browser to be responsive to screen width -->
	    <?php
		    $vers     = $this->session->userdata['vers'];
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
		/*$this->load->view('template/topbar');
		$this->load->view('template/sidebar');

		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];*/
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Name')$Name = $LangTransl;
			if($TranslCode == 'Address')$Address = $LangTransl;
			if($TranslCode == 'Phone')$Phone = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;

		endforeach;

		// DEF FILTER
		$resASMC	= 0;

		if($DIV_CODE == 'All')
			$QR_DIV	= "WHERE ";
		else
			$QR_DIV	= "WHERE DIV_CODE = '$DIV_CODE'";

		if($EMP_ID == 'All')
		{
			$QR_EMP	= "";
		}
		else
		{
			if($DIV_CODE == 'All')
				$QR_EMP	= "EMP_ID = '$EMP_ID' ";
			else
				$QR_EMP	= "AND EMP_ID = '$EMP_ID' ";
		}

		if($RISK_LEV == 'All')
			$QR_RLV	= "";
		else
		{
			if($DIV_CODE == 'All' AND $EMP_ID == 'All')
			{
				if($RISK_LEV == 1)
					$QR_RLV	= "PROB_CONCL <= '30'";
				elseif($RISK_LEV == 2)
					$QR_RLV	= "PROB_CONCL > '30' AND PROB_CONCL <= '60'";
				elseif($RISK_LEV == 3)
					$QR_RLV	= "PROB_CONCL > '60'";
			}
			else
			{
				if($RISK_LEV == 1)
					$QR_RLV	= "AND PROB_CONCL <= '30'";
				elseif($RISK_LEV == 2)
					$QR_RLV	= "AND PROB_CONCL > '30' AND PROB_CONCL <= '60'";
				elseif($RISK_LEV == 3)
					$QR_RLV	= "AND PROB_CONCL > '60'";

			}
		}

		if($DIV_CODE == 'All' AND $EMP_ID == 'All' AND $RISK_LEV == 'All')
			$QR_COL	= "";
		else
			$QR_COL	= "$QR_DIV $QR_EMP $QR_RLV";

		$sqlASMC    = "tbl_assesment $QR_COL";
        $resASMC    = $this->db->count_all($sqlASMC);

		$sqlASM    	= "SELECT * FROM tbl_assesment $QR_COL";
        $resASM    	= $this->db->query($sqlASM)->result();
	?>
	
	<body>
		<div class="content-wrapper">
			<table id="example3" width="100%" class="table table-bordered table-striped table-responsive search-table inner">
				<thead>
					<tr style="background:#CCCCCC">
					    <th style="vertical-align:middle; text-align:center" width="3%">&nbsp;</th>
					    <th style="vertical-align:middle; text-align:center" width="7%">Nama Karyawan</th>
					    <th style="vertical-align:middle; text-align:center" width="5%">Tempat, Tgl. Lahir</th>
					    <th style="vertical-align:middle; text-align:center" width="5%">Departemen</th>
					    <th style="vertical-align:middle; text-align:center" width="50%">Catatan Penting</th>
					    <th style="vertical-align:middle; text-align:center; display: none;" width="10%">No. Kontak</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$i = 0;
				$j = 0;
				if($resASMC > 0)
				{
					foreach($resASM as $row) :
						$myNewNo 	= ++$i;
						$ASSM_CODE	= $row->ASSM_CODE;
						$ASSM_DATE	= $row->ASSM_DATE;
						$EMP_ID		= $row->EMP_ID;
						$EMP_NAME	= $row->EMP_NAME;
						$EMP_BPLACE	= $row->EMP_BPLACE;
						$EMP_BDATE	= $row->EMP_BDATE;
						$EMP_BDATE	= date('d-m-Y', strtotime($EMP_BDATE));
						$EMP_GENDER	= $row->EMP_GENDER;
						$DIV_CODE	= $row->DIV_CODE;
						$SEC_CODE	= $row->SEC_CODE;
						$POS_NAME	= $row->POS_NAME;
						$Q_1		= $row->Q_1;
						$Q_1_1		= $row->Q_1_1;
						$Q_1_1DESC	= $row->Q_1_1DESC;
						$Q_2		= $row->Q_2;
						$Q_2_DESC	= $row->Q_2_DESC;
						$Q_3		= $row->Q_3;
						$Q_4		= $row->Q_4;
						$Q_5		= $row->Q_5;
						$Q_6		= $row->Q_6;
						$Q_6_DESC	= $row->Q_6_DESC;
						$Q_7		= $row->Q_7;
						$EMP_MAIL	= $row->EMP_MAIL;
						$EMP_NOHP	= $row->EMP_NOHP;
						$PROB_CONCL	= $row->PROB_CONCL;

						$birthDt 	= new DateTime($EMP_BDATE);
						$today 		= new DateTime('today');
						$y 			= $today->diff($birthDt)->y;
						$m 			= $today->diff($birthDt)->m;
						$d 			= $today->diff($birthDt)->d;

						if($y <= 30)
							$yD 	= "green";
						elseif($y <= 50)
							$yD 	= "yellow";
						else
							$yD 	= "red";

						$EMP_USIA	= "Usia: " . $y . " tahun " . $m . " bulan " . $d . " hari";

						$yDesc		= "<a class='text-$yD'>$EMP_USIA</a>";

						$DEPT_NAME	= '';
						$sqlDEPT	= "SELECT DEPT_NAME FROM tbl_dept WHERE DEPT_CODE = '$DIV_CODE'";
						$resDEPT	= $this->db->query($sqlDEPT)->result();
						foreach ($resDEPT as $key) :
							$DEPT_NAME	= $key->DEPT_NAME;
						endforeach;
						
						$EMP_DEPCOL	= $POS_NAME." ".$DEPT_NAME."<br>".$SEC_CODE;

						$Q1DESC 	= "";
						if($Q_1 == 1)
						{
							$Q1DESC = "<a class='text-red'>Sangat memungkinkan Kontak dengan Pihak Luar</a>.";
							$COLQ1	= "<label><input type='radio' class='flat-red' checked></label>";
						}
						elseif($Q_1 == 2)
						{
							$Q1DESC = "<a class='text-green'>Tidak akan ada Kontak dengan Pihak Luar</a>.";
							$COLQ1	= "<label><input type='radio' class='flat-green' checked></label>";
						}
						elseif($Q_1 == 3)
						{
							$Q1DESC = "<a class='text-yellow'>Belum Tentu ada Kontak dengan Pihak Luar</a>.";
							$COLQ1	= "<label><input type='radio' class='flat-yellow' checked></label>";
						}

						$Q2DESC 	= "";
						if($Q_2 == 1)
						{
							$Q2DESC = " <a class='text-green'>Bisa melakukan pekerjaan dari rumah</a>.";
							$COLQ2	= "<label><input type='radio' class='flat-green' checked></label>";
						}
						elseif($Q_2 == 2)
						{
							$Q2DESC = "<a class='text-red'> Tidak dapat melakukan pekerjaan dari rumah</a>.";
							$COLQ2	= "<label><input type='radio' class='flat-red' checked></label>";
						}
						elseif($Q_2 == 3)
						{
							$Q2DESC = "<a class='text-yellow'> Sebagian pekerjaan dapat dilakukan dari rumah</a>.";
							$COLQ2	= "<label><input type='radio' class='flat-yellow' checked></label>";
						}

						$Q3DESC 	= "";
						if($Q_3 == 1)
						{
							$Q3DESC = " <a class='text-green'>Pekerjaan dapat menggunakan Sistem NKE</a>.";
							$COLQ3	= "<label><input type='radio' class='flat-green' checked></label>";
						}
						elseif($Q_3 == 2)
						{
							$Q3DESC = "<a class='text-red'> Tidak dapat melakukan pekerjaan menggunakan Sistem NKE</a>.";
							$COLQ3	= "<label><input type='radio' class='flat-red' checked></label>";
						}
						elseif($Q_3 == 3)
						{
							$Q3DESC = "<a class='text-yellow'> Sebagian pekerjaan dapat dilakukan menggunakan Sistem NKE</a>.";
							$COLQ3	= "<label><input type='radio' class='flat-yellow' checked></label>";
						}

						$Q4DESC 	= "";
						if($Q_4 == 1)
						{
							$Q4DESC = " <a class='text-red'>Menggunakan Angkutan Umum menuju tempat kerja</a>.";
							$COLQ4	= "<label><input type='radio' class='flat-red' checked></label>";
						}
						elseif($Q_4 == 2)
						{
							$Q4DESC = "<a class='text-green'> Tidak menggunakan Angkutan Umum menuju tempat kerja</a>.";
							$COLQ4	= "<label><input type='radio' class='flat-green' checked></label>";
						}

						$Q7DESC 	= "";
						if($Q_7 == 1)
						{
							$Q4DESC = " <a class='text-red'>Cukup beresiko, karena jarak tempat duduk dengan rekan kerja dalam ruangan kurang 1,8 meter</a>.";
							$COLQ7	= "<label><input type='radio' class='flat-red' checked></label>";
						}
						elseif($Q_7 == 2)
						{
							$Q7DESC = "<a class='text-green'> Tidak beresiko, karena jarak tempat duduk dengan rekan kerja dalam ruangan lebih 1,8 meter</a>.";
							$COLQ7	= "<label><input type='radio' class='flat-green' checked></label>";
						}

						$NOTES		= "$Q1DESC$Q2DESC$Q3DESC$Q4DESC$Q7DESC";	
						$CONT_NO	= "<br>WA : $EMP_NOHP<br>e-Mail : $EMP_MAIL";
						
						$viewImage	= site_url('c_a553sm/c_a553sm/viewImage/?id='.$this->url_encryption_helper->encode_url($ASSM_CODE));

						if($PROB_CONCL <= 30)
						{
							$GRFCOL	= 'success';
							$TXTCOL	= 'green';
							$CONCD	= 'Risiko Rendah/Low';
						}
						elseif($PROB_CONCL <= 60)
						{
							$GRFCOL	= 'warning';
							$TXTCOL	= 'yellow';
							$CONCD	= 'Risiko sedang/Meium';
						}
						else
						{
							$GRFCOL	= 'danger';
							$TXTCOL	= 'red';
							$CONCD	= 'Risiko Tinggi/High';
						}
							
						if ($j==1) {
							echo "<tr class=zebra1>";
							$j++;
						} else {
							echo "<tr class=zebra2>";
							$j--;
						}
						?>
				                <td style="text-align:center">
				                    <?php echo $myNewNo; ?>.
                                </td>
				                <td nowrap>
				                	<?php echo "$EMP_NAME<br>$EMP_ID"; ?>
				                </td>
				                <td nowrap>
				                	<?php echo $EMP_BPLACE.", ".$EMP_BDATE."<br>"; ?>
				                	<?php echo $yDesc; ?>
				                </td>
				                <td >
				                	<?php echo $EMP_DEPCOL; ?>
				                </td>
				                <td>
				                	<div>(<?php echo $CONCD; ?>) (<?php echo number_format($PROB_CONCL, 3); ?>)</div>
				                	<div><?php echo $NOTES."<br>Untuk tindak lanjut, hubungi : ".$CONT_NO; ?></div>
				                </td>
				                <td style="text-align:center; display: none;"><?php echo $CONT_NO; ?></td>
							</tr>
						<?php 
					endforeach; 
				}
				?>
			</table>
		</div>
	</body>
</html>