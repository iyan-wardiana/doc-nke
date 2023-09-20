<?php
	date_default_timezone_set("Asia/Jakarta");
	setlocale(LC_ALL, 'id-ID', 'id_ID');
	/* 
		 * Author		= Dian Hermanto
		 * Create Date	= 13 Oktober 2022
		 * File Name	= v_lkh_report_pd.php
		 * Location		= -
	*/
	//$this->load->view('template/head');
	$Periode1 = date('YmdHis');
	if($viewType == 1)
	{
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=LapLabaRugi_$Periode1.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	    
	$sqlApp         = "SELECT * FROM tappname";
	$resultaApp     = $this->db->query($sqlApp)->result();
	foreach($resultaApp as $therow) :
	    $appName    = $therow->app_name;
	    $comp_init  = strtolower($therow->comp_init);
	    $comp_name  = $therow->comp_name;
	endforeach;

	$this->db->select('Display_Rows,decFormat');
	$resGlobal = $this->db->get('tglobalsetting')->result();
	foreach($resGlobal as $row) :
		$Display_Rows = $row->Display_Rows;
		$decFormat = $row->decFormat;
	endforeach;
	$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
	$comp_name 	= $this->session->userdata['comp_name'];

	// $PeriodeD 		= date('Y-m-d',strtotime($DWR_DATE));

	// $End_Date 		= date('Y-m-d',strtotime($DWR_DATE));
	// $End_DateBef1	= date('Y-m-d', strtotime('-1 month', strtotime($DWR_DATE)));
	// $End_DateBef	= date('Y-m-t', strtotime('-1 day', strtotime($End_DateBef1)));
	// $PERIODEM_BEF	= date('m', strtotime($End_DateBef));
	// $PERIODEY_BEF	= date('Y', strtotime($End_DateBef));

	$comp_init 		= $this->session->userdata('comp_init');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $appName; ?></title>
	<?php
		$vers   = $this->session->userdata['vers'];

		$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
		$rescss = $this->db->query($sqlcss)->result();
		foreach($rescss as $rowcss) :
			$cssjs_lnk  = $rowcss->cssjs_lnk;
			?>
				<link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
			<?php
		endforeach;

		$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
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

	<style type="text/css">
		 /*@page { margin: 0 }*/
		 body { 
			margin: 0; 
			font-family: 'Times New Roman', Times, serif;
			font-size: 12pt; 
		}
        .sheet {
          margin: 0;
          overflow: hidden;
          position: relative;
          box-sizing: border-box;
          page-break-after: always;
        }

        /** Paper sizes **/
        body.A3               .sheet { width: 297mm; }
        body.A3.landscape     .sheet { width: 420mm; }
        body.A4               .sheet { width: 210mm; }
        body.A4.landscape     .sheet { width: 297mm; }
        body.A5               .sheet { width: 148mm; }
        body.A5.landscape     .sheet { width: 210mm; }
        body.letter           .sheet { width: 216mm; }
        body.letter.landscape .sheet { width: 280mm; }
        body.legal            .sheet { width: 216mm; }
        body.legal.landscape  .sheet { width: 357mm; }

        /** Padding area **/
        .sheet.padding-10mm { padding: 10mm }
        .sheet.padding-15mm { padding: 15mm }
        .sheet.padding-20mm { padding: 20mm }
        .sheet.padding-25mm { padding: 25mm }
        .sheet.custom { padding: 1cm 1cm 0.97cm 1cm }

        /** For screen preview **/
        @media screen {
          body { background: #e0e0e0 }
          .sheet {
            background: white;
            box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
            margin: 5mm auto;
            border-radius: 5px 5px 5px 5px;
          }
        }

        /** Fix for Chrome issue #273306 **/
        @media print {
          /* @page { size: a4;} */
          body.A3.landscape { width: 420mm }
          body.A3, body.A4.landscape { width: 297mm }
          body.A4, body.A5.landscape { width: 210mm }
          body.A5                    { width: 148mm }
          body.letter, body.legal    { width: 216mm }
          body.letter.landscape      { width: 280mm }
          body.legal.landscape       { width: 357mm }
        }

		.main-container {
			display: grid;
			grid-template-columns: 1fr;

			grid-template-areas: 'header'
								 'content_det'
								 'footer'
								 'footnote';
		}

		.header {
			grid-area: header;
			margin-top: 5px;
			margin-bottom: 5px;

		}

		.header table thead th {
			border: 1px solid;
		}

		.header table th {
			background-color: initial;
			color: initial;
			border: 1;
		}

		.header .logo img {
			width: 200px;
			/* margin: 5px 5px 5px 5px; */
		}

		.header .title {
            /* padding-top: 10px; */
            /* background-color: red; */
			/* font-family: Impact; */
			text-align: center;
			font-weight: bold;
			font-size: 18pt;
		}

		.header .frmNotes table td {
			/* line-height: 10px; */
			font-size: 8pt;
			font-weight: normal;
			border: 0;
		}

		.content_det {
			grid-area: content_det;
			width: 100%;
			/* background-color: red; */
		}

		.content_det table th, td {
			background-color: initial;
			color: initial;
			border: 1px solid;
		}

		.footer {
			grid-area: footer;
		}

		.footer table td {
			/* border: 1px solid; */
			height: 150px;
			/* font-size: 12pt; */
		}

		.footnote {
			grid-area: footnote;
			font-size: 8pt;
			font-style: italic;
			margin-top: 20px;
		}

		.footnote span:first-child {
			float: left;
		}

		.footnote span:last-child {
			float: right;
		}

		ul.unchecked {
            padding-left: 0;
            margin-bottom: 0;
        }
        ul.unchecked > li {
            list-style: none;
        }
        ul.unchecked > li::before {
            /* content:"\e157"; */
			content:"";
            font-family: 'Glyphicons Halflings';
			font-size: 12pt;
            padding-right: 5px;
        }

	</style>
</head>
<body class="page A4 landscape">
	<section class="page sheet custom">
		<div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>
		<div class="main-container">
			<?php
				$getLKHH 	= "SELECT DISTINCT DWR_EMPID, DWR_VERPOSC, DWR_LKHRES FROM tbl_lkh
								WHERE DWR_EMPID = '$DWR_EMPID'";
				$resLKHH 	= $this->db->query($getLKHH);
				if($resLKHH->num_rows() > 0)
				{
					foreach($resLKHH->result() as $rLKHH):
						$DWR_EMPID 		= $rLKHH->DWR_EMPID;
						$DWR_VERPOSC 	= $rLKHH->DWR_VERPOSC;
						$DWR_LKHRES 	= $rLKHH->DWR_LKHRES;

						// Get Employee Name
							$getEmpName = "SELECT Emp_ID, First_Name, Last_Name FROM tbl_employee
											WHERE Emp_ID = '$DWR_EMPID' AND Emp_Status = 1";
							$resEmpName	= $this->db->query($getEmpName);
							if($resEmpName->num_rows() > 0)
							{
								foreach($resEmpName->result() as $rEmp):
									$First_Name	= $rEmp->First_Name;
									$Last_Name	= $rEmp->Last_Name;
									$FullName 	= "$First_Name $Last_Name";
								endforeach;
							}

						// Get Dept
							$POSS_DESC 	= "";
							$getDept 	= "SELECT POSS_CODE, POSS_NAME, POSS_PARENT, POSS_DESC FROM tbl_position_str
											WHERE POSS_CODE = '$DWR_VERPOSC'";
							$resDept 	= $this->db->query($getDept);
							if($resDept->num_rows() > 0)
							{
								foreach($resDept->result() as $rDept):
									$POSS_CODE		= $rDept->POSS_CODE;
									$POSS_NAME		= $rDept->POSS_NAME;
									$POSS_PARENT	= $rDept->POSS_PARENT;
									$POSS_DESC		= $rDept->POSS_DESC;							
								endforeach;
							}
					endforeach;
				}
			?>
			<div class="header">
				<table width="100%">
					<thead>
						<tr>
							<th rowspan="2" width="150">
								<div class="logo">
									<img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
								</div>
							</th>
							<th width="450">
								<div class="title">
									LAPORAN KERJA HARIAN
								</div>
							</th>
							<th>
								<div>KANTOR / PROYEK :</div>
								<div>&nbsp;</div>
							</th>
							<th rowspan="2" width="100">
								<div class="frmNotes">
									<table width="100%">
										<tr>
											<td>NO.DOK.</td>
											<td>:</td>
											<td>FRM.NKE.01.39</td>
										</tr>
										<tr>
											<td>REVISI</td>
											<td>:</td>
											<td>(30/09/22)</td>
										</tr>
										<tr>
											<td>AMAND.</td>
											<td>:</td>
											<td>-</td>
										</tr>
									</table>
								</div>
							</th>
						</tr>
						<tr>
							<th>NAMA : <?=$FullName?></th>
							<th>BAGIAN : <?=$POSS_DESC?></th>
						</tr>
						<tr>
							<th colspan="4" style="text-align: center; font-size: 10pt;">Note : Laporan kerja harus dibuat setiap hari dan dilengkapi seluruh isian melalui sistem document center: <a href="<?php echo base_url(); ?>" target="_blank">doc.nke.co.id</a> atau mengisi lengkap formulir ini serta disampaikan ke Bagian Human Capital diakhir waktu kerja setiap harinya paling lambat pukul 23.00</th>
						</tr>
					</thead>
				</table>
			</div>
			<div class="content_det">
				<table width="100%">
					<thead>
						<tr>
							<td width="10" style="text-align: center; font-weight: bold;">No.</td>
							<td style="text-align: center; font-weight: bold;">URAIAN PEKERJAAN</td>
							<td width="50" style="text-align: center; font-weight: bold;">TANGGAL PELAKSANAAN</td>
							<td width="150" style="text-align: center; font-weight: bold;">WAKTU PELAKSANAAN <br>(JAM)</td>
							<td width="400" style="text-align: center; font-weight: bold;">KETERANGAN</td>
						</tr>
					</thead>
					<tbody>
						<?php
							// $DWR_DATE = date('Y-m-d',strtotime(str_replace('/', '-', $DWR_DATE)));
							// $getLKH 	= "SELECT * FROM tbl_lkh WHERE DWR_EMPID = '$DWR_EMPID' AND DWR_DATE = '$DWR_DATE'";
							$getLKH 	= "SELECT * FROM tbl_lkh WHERE DWR_EMPID = '$DWR_EMPID' AND DWR_DATE BETWEEN '$Start_Date' AND '$End_Date'";
							$resLKH 	= $this->db->query($getLKH);
							if($resLKH->num_rows() > 0)
							{
								$no = 0;
								foreach($resLKH->result() as $rLKH):
									$no 			= $no + 1;
									$DWR_NUM 		= $rLKH->DWR_NUM;	
									$DWR_CODE		= $rLKH->DWR_CODE;
									$DWR_EMPID		= $rLKH->DWR_EMPID;	
									$DWR_EMPNM		= $rLKH->DWR_EMPNM;	
									$DWR_DATE		= $rLKH->DWR_DATE;
									$DWR_DATEV		= date('d/m/y', strtotime($DWR_DATE));
									$DWR_DATES		= $rLKH->DWR_DATES;
									$DWR_DATESV		= date('H:i', strtotime($DWR_DATES));
									$DWR_DATEE		= $rLKH->DWR_DATEE;	
									$DWR_DATEEV		= date('H:i', strtotime($DWR_DATEE));
									$DWR_CATEG		= $rLKH->DWR_CATEG;	
									$DWR_NOTES		= $rLKH->DWR_NOTES;	
									$DWR_STAT		= $rLKH->DWR_STAT;	
									$DWR_VERPOSC	= $rLKH->DWR_VERPOSC;	
									$DWR_VERIFIER	= $rLKH->DWR_VERIFIER;	
									$DWR_VERIFIED	= $rLKH->DWR_VERIFIED;	
									$DWR_CREATED	= $rLKH->DWR_CREATED;

									// Get Departement
										$POSS_DESC = '';
										$getDept = "SELECT POSS_CODE, POSS_NAME, POSS_DESC FROM tbl_position_str
													WHERE POSS_CODE = '$DWR_VERPOSC'";

									?>
										<tr>
											<td style="text-align: center;"><?=$no?></td>
											<td><?php echo $DWR_NOTES; ?></td>
											<td style="text-align: center;"><?php echo $DWR_DATEV; ?></td>
											<td style="text-align: center;"><?php echo "$DWR_DATESV s/d $DWR_DATEEV" ?></td>
											<td>&nbsp;</td>
										</tr>
									<?php
								endforeach;

								if($no <= 5)
								{
									for($i=$no;$i<5;$i++):
									?>
										<tr>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
									<?php
									endfor;
								}
							}
							?>
								<tr>
									<td colspan="5">
									<table width="100%" border="0">
										<tr style="border-top: hidden; border-left: hidden; border-bottom: hidden; border-right: hidden;">
											<td style="vertical-align: top; border-right: hidden;">
												<div>
													<div>Laporan ini dibuat secara benar dan penuh tanggung jawab.</div>
													<div>Hasil LKH:</div>
													<div>
														<ul class="unchecked">
															<li><input type="checkbox" id="LKHRes_1" name="LKHRes" value="1" <?php if($DWR_LKHRES == 1) { echo "checked"; } ?>>&nbsp;Semua uraian kerja di atas sudah dilaksanakan sesuai instruksi pembuat tugas</li>
															<li><input type="checkbox" id="LKHRes_2" name="LKHRes" value="2" <?php if($DWR_LKHRES == 2) { echo "checked"; } ?>>&nbsp;Satu atau lebih uraian kerja di atas belum semua dilaksanakan dan masih berlanjut sampai tanggal :</li>
														</ul>
														<div style="padding-left: 20px; padding-top: 10px;">
															<div style="border-bottom: 1px solid; width: 150px;">&nbsp;</div>
														</div>
													</div>
												</div>
											</td>
											<td width="250" style="vertical-align: top;">
												<div style="padding-top: 20px;">Dibuat Oleh,</div>
												<div style="border-bottom: 1px solid; width: 200px; padding-top: 60px; font-style: italic;">Nama:</div>
												<div style="font-style: italic;">Tanggal:</div>
											</td>
										</tr>
									</table>
									</td>
								</tr>
							<?php
						?>
					</tbody>
				</table>
			</div>
			<div class="footnote">
				<span>&copy;&nbsp;PT NUSA KONSTRUKSI ENJINIRING Tbk</span>
				<span>File: FRM.NKE.01.39.Doc, Auth: AP, ESA</span>
			</div>
		</div>
	</section>
</body>
</html>
<script type="text/javascript">
	$(function(){
		let DWR_EMPID 	= '<?=$DWR_EMPID?>';
		let DWR_DATES 	= '<?=$Start_Date?>';
		let DWR_DATEE 	= '<?=$End_Date?>';

		let dt		= new Date();

		let CD 		= ("0"+dt.getDate()).slice(-2);
		// let CM 		= ("0"+dt.getMonth()).slice(-2);
		let CM1		= new Date().getMonth() + 1;
		let CM		= ("0"+CM1).slice(-2);
		let CY 		= dt.getFullYear();
		let CDate 	= CY+'-'+CM+'-'+CD;

		let H		= ("0"+dt.getHours()).slice(-2);
		let M		= ("0"+dt.getMinutes()).slice(-2);
		let S 		= ("0"+dt.getSeconds()).slice(-2);
		let time	= H+':'+M+':'+S;

		console.log(dt);

		console.log(DWR_DATES+' != '+CDate+' && '+DWR_DATEE+' != '+CDate)

		if(DWR_DATES != CDate && DWR_DATEE != CDate) 
		{
			$('input[name="LKHRes"]').prop('disabled', true);
			$('input[name="LKHRes"]').prop('checked', false);
		}
		else 
		{
			if(time >= '23:00:00' && time <= '00:00:00')
			{
				$('input[name="LKHRes"]').prop('disabled', true);
			}
			else
			{
				$('input[name="LKHRes"]').prop('disabled', false);
			}
		}

		$('input[name="LKHRes"]').change(function(e) {
			let LKHRes 		= e.target.value;

			$('input[name="LKHRes"]').not(this).prop('checked', false);

			if(e.target.checked == false) LKHRes = 0;
			$.ajax({
				url: "<?php echo base_url().'index.php/__l1y/updLKHRes'; ?>",
				type: "POST",
				dataType: "JSON",
				data: {DWR_EMPID:DWR_EMPID, DWR_DATE:CDate, LKHRes:LKHRes}
			});
		});
	});
</script>
<?php
	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

	// Right side column. contains the Control Panel
	//______$this->load->view('template/aside');

	//______$this->load->view('template/js_data');

	//______$this->load->view('template/foot');
?>