<?php
/* 
 	* Author   		= Dian Hermanto
 	* Create Date  	= 14 Nopember 2020
 	* File Name  	= v_ledger_report_tb.php
 	* Location   	= -
*/

setlocale(LC_ALL, 'id-ID', 'id_ID');
date_default_timezone_set("Asia/Jakarta");

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$LangID 	= $this->session->userdata['LangID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=GL_".date('YmdHis').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];

if($CFType == 1)
	$CFTyped	= "Detail";
else
	$CFTyped	= "Summary";
	
$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJDATE_CO, A.PRJEDAT,
			A.PRJCOST, A.PRJCATEG,
			A.PRJLKOT, A.PRJCBNG, A.PRJCURR, A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.ISCHANGE, A.REFCHGNO, A.PRJCOST2, 
			A.PRJ_MNG, A.PRJBOQ,
			A.CHGUSER, A.CHGSTAT, A.PRJPROG, A.QTY_SPYR, A.PRC_STRK, A.PRC_ARST, A.PRC_MKNK, A.PRC_ELCT, A.PRJ_IMGNAME,
			A.isHO
		FROM tbl_project A
		WHERE A.PRJCODE = '$PRJCODECOL'";

$resPROJ	= $this->db->query($sql)->result();
foreach($resPROJ as $rowPROJ){
	$PRJCODE	= $rowPROJ->PRJCODE;
	$PRJNAME	= $rowPROJ->PRJNAME;
	$PRJCNUM	= $rowPROJ->PRJCNUM;
	$PRJDATE	= $rowPROJ->PRJDATE;
	$PRJEDAT	= $rowPROJ->PRJEDAT;
	$PRJOWN		= $rowPROJ->PRJOWN;
	$PRJLOCT	= $rowPROJ->PRJLOCT;
	$PRJ_MNG	= $rowPROJ->PRJ_MNG;
	$isHO		= $rowPROJ->isHO;
}
$MNGP_NAME	= '';
if($PRJ_MNG != '')
{
	$sqlMNGP 	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$PRJ_MNG'";
	$resMNGP 	= $this->db->query($sqlMNGP)->result();
	foreach($resMNGP as $rowMNGP) :
		$First_Name = $rowMNGP->First_Name;
		$Last_Name 	= $rowMNGP->Last_Name;
	endforeach;
	$MNGP_NAME	= $First_Name.$Last_Name;
}

$Account_Number = '';
$Account_NameId	= '';
$syncPRJ		= $ACCSELCOL;
$sqlACC 	= "SELECT Account_Number, Account_NameId, syncPRJ FROM tbl_chartaccount WHERE Account_Number IN ($ACCSELCOL) AND PRJCODE = '$PRJCODECOL'";
$resACC 	= $this->db->query($sqlACC)->result();
foreach($resACC as $rowACC) :
	$Account_Number = $rowACC->Account_Number;
	$Account_NameId	= $rowACC->Account_NameId;
	$syncPRJ		= $rowACC->syncPRJ;
endforeach;
$tags 		= explode('~' , $syncPRJ);
$TOTPRJ 	= count($tags);

$StartDate	= date('Y-m-d', strtotime($Start_Date));
$EndDate	= date('Y-m-d', strtotime($End_Date . '+ 1 day'));
$StartDateV	= date('d M Y', strtotime($Start_Date));
$EndDateV	= date('d M Y', strtotime($End_Date));

$DrafTTD1   = "white";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
        <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 12pt Arial, Helvetica, sans-serif;
        }
        * {
          box-sizing: border-box;
          -moz-box-sizing: border-box;
        }
        .page {
            width: 21cm;
            min-height: 29.7cm;
            padding-left: 0.5cm;
            padding-right: 0.5cm;
            padding-top: 1cm;
            padding-bottom: 1cm;
            margin: 0.5cm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: <?php echo $DrafTTD1;?>;
            background-size: 400px 200px !important;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        
        @page {
           /* size: A4;*/
            margin: 0;
        }
        @media print {
            /*@page{size: portrait;}*/
            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
            .hcol1{
                background-color: #F7DC6F !important;
            }
        }
    </style>
    
</head>
<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>

<?php
    if($LangID == 'IND')
    {
        $header     = "BUKU BESAR RINCI";
    }
    else
    {
        $header     = "LEDGER";
    }
?>

<body class="hold-transition skin-blue sidebar-mini">
	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	</style>
    <div class="page">
    	<table width="100%" border="0" style="size:auto">
		    <tr style="border-top: hidden; border-left: hidden; border-right: hidden; ">
		        <td colspan="3" class="style2" style="text-align:center; font-weight:bold;">
		        	<span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php echo $comp_name; ?></span><br>
					<div style="font-weight: bold; font-size: 18px; color: #2E86C1"><?php echo $h1_title; ?></div>
		            <?php echo "$StartDateV - $EndDateV"; ?>
				</td>
		  	</tr>
        	<tr style="display: none;">
            	<td width="10%" nowrap>Nomor &amp; Nama Proyek </td>
                <td width="1%">:</td>
                <td width="84%" nowrap><?php echo "$PRJCODE - $PRJNAME";?></td>
            </tr>
		    <tr style="display: none;">
		        <td colspan="3" class="style2" style="line-height: 2px; border-left: hidden; border-right: hidden; border-bottom: hidden;">&nbsp;</td>
		    </tr>
        	<tr style="display: none;">
        	  	<td width="10%" style="border-left: hidden; border-right: hidden; border-bottom: hidden;">Nama Akun</td>
        	  	<td width="1%" style="border-left: hidden; border-right: hidden; border-bottom: hidden;">:</td>
        	  	<td width="84%" style="border-left: hidden; border-right: hidden; border-bottom: hidden;"><?php echo "$Account_Number - $Account_NameId"; ?></td>
      	  	</tr>
        	<tr>
        	  	<td width="10%" style="border-left: hidden; border-right: hidden; border-bottom: hidden;" nowrap>&nbsp;<!-- Tanggal Cetak --></td>
        	  	<td width="1%" style="border-left: hidden; border-right: hidden; border-bottom: hidden;">&nbsp;</td>
        	  	<td width="84%" style="border-left: hidden; border-right: hidden; border-bottom: hidden; text-align: right; font-style: italic;" nowrap><?php echo date("d M Y H:i:s");?></td>
       	  	</tr>
		    <tr>
		        <td colspan="3" class="style2" style="border-left: hidden; border-right: hidden; border-bottom: hidden;">
		            <table width="100%" border="1" rules="all">
		                <tr style="line-height: 25px;">
							<td width="10%" nowrap style="text-align:center; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Kode Akun</td>
							<td width="50%" nowrap style="text-align:left; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Nama Akun</td>
							<td width="10%" nowrap style="text-align:right; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Saldo Awal</td>
							<td width="10%" nowrap style="text-align:right; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Debit</td>
							<td width="10%" nowrap style="text-align:right; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Kredit</td>
							<td width="10%" nowrap style="text-align:right; font-weight:bold; border-top: double; border-bottom: double; border-left: hidden; border-right: hidden;">Saldo Akhir</td>
		                </tr>
					    <tr style="line-height: 4px">
		                  	<td colspan="6" nowrap style="text-align:center; border-left: hidden; border-right: hidden; border-bottom: hidden;">&nbsp;</td>
					    </tr>
		                <?php
							$CASH_OUTTOT	= 0;
							$sqlACC		= "SELECT Account_Class FROM tbl_chartaccount 
												WHERE PRJCODE = '$PRJCODE' AND Account_Number IN ($ACCSELCOL)";
							$resACC	= $this->db->query($sqlACC)->result();
							foreach($resACC as $rowACC):
								$Account_Class	= $rowACC->Account_Class;
							endforeach;
								
							// CASH IN COLLECT
							$therow			= 0;
							$therow1		= 0;
							$therow2		= 0;
							$CASH_SALDO		= 0;
							$CASH_INTOT		= 0;
							$CASH_OUTTOT	= 0;
							$CASH_TOTD		= 0;
							$CASH_TOTK		= 0;
							
							if($Account_Class == 3 || $Account_Class == 4)
							{
								$ADDQUERY	= "";
							}
							else
							{
								if($TOTPRJ > 1)
								{
									$COLLPRJ1	= $tags[0];
									$COLLPRJ	= '';
									foreach($tags as $i =>$key)
									{
										+$i;
										if($i==0)
											$COLLPRJ = "'$COLLPRJ1'";
										else
											$COLLPRJ = "$COLLPRJ,'$key'";
									}
								}
								//$ADDQUERY	= "A.proj_Code IN ($COLLPRJ) AND";
								$ADDQUERY	= "A.proj_Code = '$PRJCODE' AND";
							}
							if($isHO == 1)
							{
								$ADDQUERY	= "";		// TAMPILKAN SEMUA PROYEK
							}
							
							$ACC_NAME 		= "";
							$sqlACC			= "SELECT Account_Number AS ACC_NUMB, Account_NameId AS ACC_NAME FROM tbl_chartaccount 
												WHERE PRJCODE = '$PRJCODE' AND Account_Number IN ($ACCSELCOL)";
							$resACC			= $this->db->query($sqlACC)->result();
							foreach($resACC as $rowACC):
								$ACC_NUMB	= $rowACC->ACC_NUMB;
								$ACC_NAME	= $rowACC->ACC_NAME;

								// SALDO AWAL : SALDO SEBELUM TGL. PERIODE LAPORAN
									$sqlSA	= "SELECT SUM(A.Base_Debet - A.Base_Kredit) AS TOT_SAWAL FROM tbl_journaldetail A
														INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
													WHERE 
														$ADDQUERY
														B.JournalH_Date < '$StartDate'
														AND A.Acc_Id = '$ACC_NUMB'
														AND B.GEJ_STAT = 3";
									$resSA	= $this->db->query($sqlSA)->result();
									foreach($resSA as $rowSA):
										$TOT_SAWAL	= $rowSA->TOT_SAWAL;
									endforeach;

								// DEBET
									$sqlDEB	= "SELECT SUM(A.Base_Debet) AS TOT_DEBET FROM tbl_journaldetail A
														INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
													WHERE 
														$ADDQUERY
														B.JournalH_Date >= '$StartDate'
														AND B.JournalH_Date < '$EndDate'
														AND A.Acc_Id = '$ACC_NUMB'
														AND B.GEJ_STAT = 3";
									$resDEB	= $this->db->query($sqlDEB)->result();
									foreach($resDEB as $rowDEB):
										$TOT_DEBET	= $rowDEB->TOT_DEBET;
									endforeach;

								// KREDIT
									$sqlKRE	= "SELECT SUM(A.Base_Kredit) AS TOT_KREDIT FROM tbl_journaldetail A
														INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
													WHERE 
														$ADDQUERY
														B.JournalH_Date >= '$StartDate'
														AND B.JournalH_Date < '$EndDate'
														AND A.Acc_Id = '$ACC_NUMB'
														AND B.GEJ_STAT = 3";
									$resKRE	= $this->db->query($sqlKRE)->result();
									foreach($resKRE as $rowKRE):
										$TOT_KREDIT	= $rowKRE->TOT_KREDIT;
									endforeach;

								// SALDO AKHIR
									$TOT_SE = $TOT_SAWAL + $TOT_DEBET - $TOT_KREDIT;
								?>
								<tr>
									<td nowrap style="text-align:left; border-left: hidden; border-right: hidden; border-bottom: hidden;"><?php echo $ACC_NUMB; ?></td>
									<td nowrap style="text-align:left; border-left: hidden; border-right: hidden; border-bottom: hidden;"><?php echo $ACC_NAME; ?></td>
									<td nowrap style="text-align:right; border-left: hidden; border-right: hidden; border-bottom: hidden;">
										<?php echo number_format($TOT_SAWAL, 2); ?></td>
									<td nowrap style="text-align:right; border-left: hidden; border-right: hidden; border-bottom: hidden;">
										<?php echo number_format($TOT_DEBET, 2); ?></td>
									<td nowrap style="text-align:right; border-left: hidden; border-right: hidden; border-bottom: hidden;">
										<?php echo number_format($TOT_KREDIT, 2); ?></td>
									<td nowrap style="text-align:right; border-left: hidden; border-right: hidden; border-bottom: hidden;">
										<?php echo number_format($TOT_SE, 2); ?></td>
								</tr>
		                        <?php
							endforeach;
						?>
					    <tr style="line-height: 4px">
		                  	<td colspan="3" nowrap style="text-align:center; border-left: hidden; border-right: hidden;">&nbsp;</td>
			                <td nowrap style="text-align:right; font-weight:bold; border-left: hidden; border-right: hidden;">&nbsp;</td>
			                <td nowrap style="text-align:right; font-weight:bold; border-left: hidden; border-right: hidden;">&nbsp;</td>
		                  	<td nowrap style="text-align:right; font-weight:bold; border-left: hidden; border-right: hidden;">&nbsp;</td>
					    </tr>
		                <tr style="line-height:2px;">
		                    <td colspan="8" nowrap style="text-align:center; font-style:italic; border-left:hidden; border-right:hidden; border-bottom:hidden">&nbsp;</td>
		                </tr>
		            </table>
		            <table width="100%" border="1" rules="all" style="display:none">
		                <tr>
		                    <td width="21%">
		                    	Dibuat oleh: Adm. Um. &amp; Keu. Proyek<br><br>
		                        Paraf<br><br>
		                        Tanggal
		                    </td>
		                    <td width="21%">
		                    	Diperiksa Oleh : Kepala Proyek<br><br>
		                        Paraf<br><br>
		                        Tanggal
		                    </td>
		                    <td width="21%">
		                    	Diverifikasi Oleh : Keuangan Pusat<br><br>
		                        Paraf<br><br>
		                        Tanggal
		                    </td>
		                    <td width="21%">
		                    	Disetujui Oleh :  Direktur Operasional<br><br>
		                        Paraf<br><br>
		                        Tanggal
		                    </td>
		                    <td width="17%">
		                    	No. Form : 16.R0/PRO/keu/17<br><br>
		                        Revisi ke : 0<br><br>
		                        Tanggal  : <?php echo date("d M Y", strtotime("now"));?>
		                    </td>
		              </tr>
		          	</table>
			  	</td>
		    </tr>
		</table>
    </div>
</body>

</html>

<!-- jQuery 2.2.3 -->
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
<script>
  $(function () 
  {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>

<?php
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>