<?php
/* 
 	* Author   = Dian Hermanto
 	* Create Date  = 21 Januari 2018
 	* File Name  = print_po.php
 	* Location   = -
*/

setlocale(LC_ALL, 'id-ID', 'id_ID');

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
$decFormat	= 2;
$DrafTTD1   = "white";

$sql_01		= "SELECT * FROM tappname";
$res_01		= $this->db->query($sql_01)->result();
foreach($res_01 as $row_01):
	$comp_name	= $row_01->comp_name;
	$comp_add	= $row_01->comp_add;
	$comp_phone	= $row_01->comp_phone;
	$comp_mail	= $row_01->comp_mail;
endforeach;

$PeriodeD 	= date('Y-m-d',strtotime($PERIODE));

$sqlPRJ			= "SELECT PRJCODE, PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ	= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJCODE 	= $rowPRJ->PRJCODE;
	$PRJNAME 	= $rowPRJ->PRJNAME;
	$PRJCOST 	= $rowPRJ->PRJCOST;
endforeach;
$PRJCODEL		= $PRJCODE;
$PRJNAMECOLL	= $PRJNAME;

$PERIODEV		= strftime('%B %Y', strtotime($PERIODE));

$PERIODE 		= date('Y-m-d',strtotime($PERIODE));
$PERIODEBef1	= date('Y-m-d', strtotime('-1 month', strtotime($PERIODE)));
$PERIODEBef	= date('Y-m-t', strtotime('-1 day', strtotime($PERIODEBef1)));
$PERIODEM_BEF	= date('m', strtotime($PERIODEBef));
$PERIODEY_BEF	= date('Y', strtotime($PERIODEBef));

$PERIODM		= date('m', strtotime($PERIODE));
$PERIODY		= date('Y', strtotime($PERIODE));
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
            padding-left: 2cm;
            padding-right: 2cm;
            padding-top: 2cm;
            padding-bottom: 2cm;
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
	//$this->load->view('template/topbar');
	//$this->load->view('template/sidebar');
	
	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
        if($TranslCode == 'Date')$Date = $LangTransl;
        if($TranslCode == 'CustName')$CustName = $LangTransl;
        if($TranslCode == 'Color')$Color = $LangTransl;
        if($TranslCode == 'Remarks')$Remarks = $LangTransl;
        if($TranslCode == 'Nominal')$Nominal = $LangTransl;
        if($TranslCode == 'salesPrcCust')$salesPrcCust = $LangTransl;
        if($TranslCode == 'Created')$Created = $LangTransl;
        if($TranslCode == 'Approved')$Approved = $LangTransl;
        if($TranslCode == 'Approved')$Approved = $LangTransl;
	endforeach;

    if($LangID == 'IND')
    {
        $header     = "DETAIL TRANSAKSI";
        $title1     = "Kategori : ";
    }
    else
    {
        $header     = "TRANSACTION DETAIL";
        $title1     = "Category : ";
    }

    $stlLine1 		= "border-left-style: hidden; border-top-style: hidden; border-right-style: hidden; border-bottom-style: hidden;";
    $stlLine2 		= "border-left-style: hidden; border-top-style: hidden; border-right-style: hidden;";
    $stlLine3 		= "border-left-style: hidden; border-right-style: hidden;";
    $stlLine4 		= "border-top:double; border-bottom:double; border-right-style: hidden;";
    $stlLine5 		= "border-bottom:groove; border-right-style: hidden; border-top-style: hidden;";
    $stlLine6 		= "border-left-style: hidden; border-top-style: hidden; border-right-style: hidden; border-bottom-style: hidden;";

    /*$stlLine1 		= "";
    $stlLine2 		= "";
    $stlLine3 		= "";
    $stlLine4 		= "";
    $stlLine5 		= "";
    $stlLine6 		= "";*/

    $titCat 		= "-";
    if($CATEGREP == 'PENP')
    	$titCat 	= "Penjualan Pokok";
    elseif($CATEGREP == 'BPP')
    	$titCat 	= "Beban Pokok Produksi";
    elseif($CATEGREP == 'BLL')
        $titCat     = "Beban Lain-Lain";
    elseif($CATEGREP == 'BGP')
        $titCat     = "Beban Gaji Pegawai";
    elseif($CATEGREP == 'BLAT')
        $titCat     = "Beban Listrik, Air dan Telepon";
    elseif($CATEGREP == 'BAU')
        $titCat     = "Beban Administrasi dan Umum";
    elseif($CATEGREP == 'BOL')
        $titCat     = "Beban Operasional Lainnya";
    elseif($CATEGREP == 'BPB')
        $titCat     = "Beban Penyusutan Bangunan";
    elseif($CATEGREP == 'BPM')
        $titCat     = "Beban Penyusutan Mesin";
    elseif($CATEGREP == 'BPL')
        $titCat     = "Beban Penyusutan Kendaraan";
    elseif($CATEGREP == 'BNOL')
        $titCat     = "Beban Non Operasional Lainnya";
    else
        $titCat     = "no desc";
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
    <!-- <div id="Layer1">
        <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
        <img src="<?php //echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
        <a href="#" onClick="window.close();" class="button"> close </a>
    </div>
    <div class="pad margin no-print" style="display:none">
        <div class="callout callout-info" style="margin-bottom: 0!important;">
            <h4><i class="fa fa-info"></i> Note:</h4>
            <?php //echo $Transl_01; ?>
        </div>
    </div> -->
    <!-- Main content -->
    <div class="page">
        <table border="1" width="100%">
            <tr>
                <td style="<?=$stlLine1?> text-align: left; font-weight: bold; font-size: 18px"><?=$header?></td>
            </tr>
            <tr>
                <td style="<?=$stlLine1?>text-align: left; font-weight: bold; font-size: 18px"><?=$title1.$titCat?></td>
            </tr>
        	<tr>
				<td style="<?=$stlLine5.$stlLine3?> line-height: 1px">&nbsp;</td>
	      	</tr>
            <tr>
                <td style="<?=$stlLine3?> line-height: 5px">&nbsp;</td>
            </tr>
            <tr>
            	<td style="<?=$stlLine1?>">
            		<table border="1" width="100%">
            			<tr style="font-weight:bold; text-align:center; background:#CCC;">
	                    	<td width="5%" style="text-align:center">&nbsp;No.&nbsp;</td>
	                      	<td width="11%" style="text-align:center">Kode</td>
	                      	<td width="12%" style="text-align:center">Tanggal</td>
	                        <td width="40%" style="text-align:center">Deskripsi</td>
	                        <td width="10%" style="text-align:center" nowrap>Jumlah</td>
	                        <td width="10%" style="text-align:center" nowrap>Total</td>
	                    </tr>
	                    <?php
							$no		= 0;
							$totAm	= 0;

							if($CATEGREP == 'PENP')
							{
								$sqlJD	= "SELECT JournalH_Code, Ref_Number, JournalH_Date, JournalType, Other_Desc,
												oth_reason, Base_Debet AS Journal_Amn, '' AS ITM_LR
											FROM
												tbl_journaldetail
											WHERE
												JournalType = 'BR'
												AND GEJ_STAT = 3
												AND Base_Debet > 0
												AND proj_Code IN (SELECT PRJCODE FROM tbl_project WHERE PRJCODE_HO = '$PRJCODE')
												AND MONTH(JournalH_Date) = '$PERIODM' AND YEAR(JournalH_Date) = '$PERIODY'";
							}
							else
							{
								$sqlJD	= "SELECT A.JournalH_Code, A.Ref_Number, A.JournalType, A.JournalH_Date, A.JournalType, A.Other_Desc, 
												A.oth_reason, A.Base_Debet, A.Base_Kredit, B.ITM_LR
											FROM
												tbl_journaldetail A
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.proj_Code = B.PRJCODE
													AND B.ITM_LR = '$CATEGREP'
											WHERE
												A.GEJ_STAT = 3
												AND A.JournalType != 'IR'
												AND IF(A.JournalType = 'GEJ', A.Base_Debet >0, A.Base_Kredit > 0)
												AND A.proj_Code IN (SELECT PRJCODE FROM tbl_project WHERE PRJCODE_HO = '$PRJCODE')
												AND MONTH(A.JournalH_Date) = '$PERIODM' AND YEAR(A.JournalH_Date) = '$PERIODY'";
							}
							$resJD	= $this->db->query($sqlJD)->result();
							foreach($resJD as $rowJD) :
								$no				= $no+1;
								$ITM_LR 		= $rowJD->ITM_LR;
                                $JournalH_Code  = $rowJD->JournalH_Code;
                                $JournalType    = $rowJD->JournalType;
								$Manual_No 		= $rowJD->Ref_Number;

                                $sqlJH          = "SELECT Manual_No FROM tbl_journalheader WHERE JournalH_Code = '$JournalH_Code'";
                                $resJH          = $this->db->query($sqlJH)->result();
                                foreach($resJH as $rowJH) :
                                    $Manual_No  = $rowJH->Manual_No;
                                endforeach;

								if($Manual_No == '')
									$Manual_No	= $JournalH_Code;

								$JournalH_Date 	= $rowJD->JournalH_Date;
								$JournalType 	= $rowJD->JournalType;
								$Other_Desc 	= $rowJD->Other_Desc;
								$oth_reason 	= $rowJD->oth_reason;
                                $Journal_Amn    = $rowJD->Base_Kredit;
                                if($JournalType == 'GEJ')
                                   $Journal_Amn = $rowJD->Base_Debet;

								$totAm			= $totAm + $Journal_Amn;
								$JournalDesc 	= $oth_reason.$Other_Desc;
								if($JournalDesc == '')
								{
									if($JournalType == 'UM')
										$JournalDesc	= "Penggunaan material";
									elseif($JournalType == 'OPN')
										$JournalDesc	= "Opname";
								}
								?>
	                                <tr>
	                                    <td style="text-align:center"><?php echo $no; ?></td>
	                                    <td style="text-align:center" nowrap><?php echo $Manual_No; ?></td>
	                                    <td style="text-align:center" nowrap><?php echo $JournalH_Date; ?></td>
	                                    <td style="text-align:left"><?php echo $JournalDesc; ?></td>
	                                    <td style="text-align:right"><?php echo number_format($Journal_Amn,2); ?></td>
	                                    <td style="text-align:right"><?php echo number_format($totAm,2); ?></td>
	                                </tr>
	                    		<?php
							endforeach;
						?>
            		</table>
            	</td>
            </tr>
        </table>
        <br>
        <div class="row no-print">
            <div class="col-xs-12">
                <div id="Layer1">
                    <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
                    <i class="fa fa-download"></i> Generate PDF
                    </button>
                </div>
            </div>
        </div>
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