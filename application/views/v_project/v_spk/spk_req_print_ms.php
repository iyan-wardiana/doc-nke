<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 21 Januari 2018
 * File Name	= print_matreq.php
 * Location		= -*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;
$Start_DateY 	= date('Y');
$Start_DateM 	= date('m');
$Start_DateD 	= date('d');
$Start_Date 	= "$Start_DateY-$Start_DateM-$Start_DateD";
$LangID 		= $this->session->userdata['LangID'];
if($LangID == 'IND')
{
	$Transl_01	= "Halaman ini merupakan contoh untuk mencetak dokumen permintaan pembelian. Silahkan ajukan kepada kami untuk membuat halaman yang sebenarnya.";
}
else
{
	$Transl_01	= "This page is an example to print a purchase request document. Please feel free to ask us to create an actual page.";
}

$sql_01	= "SELECT * FROM tappname";
$res_01	= $this->db->query($sql_01)->result();
foreach($res_01 as $row_01):
	$comp_name	= $row_01->comp_name;
	$comp_add	= $row_01->comp_add;
	$comp_phone	= $row_01->comp_phone;
	$comp_mail	= $row_01->comp_mail;
endforeach;

$WO_NUM		= $default['WO_NUM'];
$WO_CODE	= $default['WO_CODE'];
$WO_DATE	= $default['WO_DATE'];
$PRJCODE	= $default['PRJCODE'];
$WO_NOTE	= $default['WO_NOTE'];
$WO_STAT	= $default['WO_STAT'];

$WO_DATEV	= date('d M Y', strtotime($WO_DATE));
$WO_PLAN_IRV= '';
$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJNAME= $rowPRJ->PRJNAME;
endforeach;

$WO_CATEG	= '';
$sqlWOC		= "SELECT WO_CATEG FROM tbl_woreq_header WHERE WO_NUM = '$WO_NUM'";
$resWOC		= $this->db->query($sqlWOC)->result();
foreach($resWOC as $rowWOC) :
	$WO_CATEG= $rowWOC->WO_CATEG;
endforeach;

$WO_CATEGD	= '';
if($WO_CATEG == 'SALT')
	$WO_CATEGD	= "ALAT";
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
		
		if($TranslCode == 'From')$From = $LangTransl;
		if($TranslCode == 'To_x')$To_x = $LangTransl;
		if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;
		if($TranslCode == 'OrderID')$OrderID = $LangTransl;
		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
		if($TranslCode == 'To_x')$To_x = $LangTransl;
		if($TranslCode == 'To_x')$To_x = $LangTransl;
	endforeach
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

    <div id="Layer1">
        <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
        <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
        <a href="#" onClick="window.close();" class="button"> close </a>
    </div>
    <div class="pad margin no-print" style="display:none">
        <div class="callout callout-info" style="margin-bottom: 0!important;">
            <h4><i class="fa fa-info"></i> Note:</h4>
            <?php echo $Transl_01; ?>
        </div>
    </div>
    <!-- Main content -->
    <section class="invoice">
        <table border="0" width="100%">
            <tr>
                <td width="4%" rowspan="2"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" style="max-width:120px; max-height:120px" ></td>
                <td width="35%">&nbsp;</td>
                <td width="43%">&nbsp;</td>
                <td width="18%">No. Ref. : <?php echo $WO_CODE; ?></td>
            </tr>
            <tr>
              <td colspan="2" style="text-align:center; font-size:18px; font-weight:bold">FORM PERMINTAAN PENGADAAN <?php echo $WO_CATEGD; ?></td>
              <td>&nbsp;</td>
            </tr>
        </table>
        <table border="0" width="100%">
            <tr>
              <td nowrap>&nbsp;</td>
              <td>&nbsp;</td>
              <td nowrap>&nbsp;</td>
              <td nowrap>&nbsp;</td>
              <td nowrap>&nbsp;</td>
            </tr>
            <tr>
                <td width="10%" nowrap>Tanggal Permintaan</td>
                <td width="1%">:</td>
                <td width="40%" nowrap><?php echo $WO_DATEV; ?></td>
                <td width="14%" nowrap>Nama Proyek</td>
                <td width="35%" nowrap>: <?php echo $PRJNAME; ?></td>
            </tr>
            <tr>
                <td nowrap>Tanggal Dibutuhkan</td>
              <td>:</td>
                <td nowrap><?php echo $WO_PLAN_IRV; ?></td>
              <td nowrap>Nomor Proyek</td>
              <td nowrap>: <?php echo $PRJCODE; ?></td>
            </tr>
            <tr>
                <td nowrap>&nbsp;</td>
              <td>&nbsp;</td>
              <td nowrap>&nbsp;</td>
              <td nowrap>Lokasi Pengiriman</td>
              <td nowrap>:</td>
            </tr>
            <tr>
                <td nowrap>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
        </table>
        <table border="1" width="100%">
            <tr style="background-color:#999">
                <th width="3%" rowspan="2" style="background-color:#999; vertical-align:middle">NO.</th>
                <th width="23%" rowspan="2" style="background-color:#999; vertical-align:middle; text-align:center">DESKRIPSI</th>
                <th width="10%" rowspan="2" style="background-color:#999; vertical-align:middle; text-align:center">SPESIFIKASI</th>
                <th width="7%" rowspan="2" style="background-color:#999; vertical-align:middle; text-align:center">VOLUME<br>RENCANA<br>VOLUME</th>
                <th width="6%" rowspan="2" style="background-color:#999; vertical-align:middle; text-align:center">SATUAN</th>
                <th colspan="3" style="background-color:#999; vertical-align:middle; text-align:center">PENGADAAN</th>                    <th width="29%" rowspan="2" style="background-color:#999; vertical-align:middle; text-align:center">KETERANGAN</th>
            </tr>
            <tr>
                <th width="8%" style="background-color:#999; text-align:center" nowrap>YANG LALU</th>
                <th width="6%" style="background-color:#999; text-align:center" nowrap>SAAT INI</th>
                <th width="8%" style="background-color:#999; text-align:center" nowrap>S.D. SAT INI</th>
            </tr>
            <?php
                $maxRow		= 10;
                $rowNo		= 0;
                $sqlPRDetC	= "tbl_woreq_detail WHERE WO_NUM = '$WO_NUM'";
                $resPRDetC	= $this->db->count_all($sqlPRDetC);
                
                $sqlPRDet	= "SELECT ITM_CODE, ITM_UNIT, WO_VOLM, ITM_PRICE, WO_DESC FROM tbl_woreq_detail
                                WHERE WO_NUM = '$WO_NUM'";
                $resPRDet	= $this->db->query($sqlPRDet)->result();
                
                if($resPRDetC <= 10)
                {
                    foreach($resPRDet as $rowPR) :
                        $rowNo		= $rowNo + 1;
                        $ITM_CODE	= $rowPR->ITM_CODE;
                        $ITM_NAME	= '';
                        $sqlITM		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE'";
                        $resITM		= $this->db->query($sqlITM)->result();
                            foreach($resITM as $rowITM) :
                                $ITM_NAME	= $rowITM->ITM_NAME;								
                            endforeach;
                        $ITM_UNIT	= $rowPR->ITM_UNIT;
                        $WO_VOLM	= $rowPR->WO_VOLM;
                        $ITM_PRICE	= $rowPR->ITM_PRICE;
                        $WO_DESC	= $rowPR->WO_DESC;
                        
                        /*$TOTWO_BEF	= 0;
                        $sqlBEF		= "SELECT SUM(A.WO_VOLM) AS TOTWO_BEF FROM tbl_woreq_detail A
                                            INNER JOIN tbl_woreq_header B ON A.WO_CODE = B.WO_CODE
                                        WHERE B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE' AND B.WO_STAT = 3";
                        $resBEF		= $this->db->query($sqlBEF)->result();
                        foreach($sqlBEF as $rowBEF) :
                            $TOTWO_BEF	= $rowBEF->TOTWO_BEF;								
                        endforeach;*/
                        
                        $ITM_VOLMB	= 0;
                        $sqlBEF		= "SELECT ITM_VOLM, REQ_VOLM, ITM_STOCK FROM tbl_joblist_detail
                                        WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
                        $resBEF		= $this->db->query($sqlBEF)->result();
                        foreach($resBEF as $rowBEF) :
                            $ITM_VOLMB	= $rowBEF->ITM_VOLM;							
                        endforeach;
                        
                        $sqlBEF		= "SELECT SUM(A.WO_VOLM) AS TOT_PR
                                        FROM tbl_woreq_detail A
                                        INNER JOIN tbl_woreq_header B ON A.WO_NUM = B.WO_NUM
                                        WHERE
                                            A.ITM_CODE = '$ITM_CODE'
                                            AND A.PRJCODE = '$PRJCODE'
                                            AND B.WO_STAT IN (3,6)
                                            AND A.WO_NUM != '$WO_NUM'";
                        $resBEF		= $this->db->query($sqlBEF)->result();
                        foreach($resBEF as $rowBEF) :
                            $TOT_PR	= $rowBEF->TOT_PR;						
                        endforeach;
                        
                        /*if($WO_STAT == 3)
                            $REQ_BEFORE	= $REQ_VOLM - $WO_VOLM;
                        else*/
                            $REQ_BEFORE	= $TOT_PR;
                        
                        $TOTWO_VOLM		= $REQ_BEFORE + $WO_VOLM
                        ?>
                            <tr>
                                <td style="text-align:center"><?php echo $rowNo; ?>.</td>
                                <td nowrap><?php echo $ITM_NAME; ?></td>
                                <td nowrap><?php echo $ITM_NAME; ?></td>
                                <td style="text-align:right"><?php echo number_format($ITM_VOLMB, $decFormat); ?></td>
                                <td style="text-align:center"><?php echo $ITM_UNIT; ?></td>
                                <td style="text-align:right"><?php echo number_format($REQ_BEFORE, $decFormat); ?></td>
                                <td style="text-align:right"><?php echo number_format($WO_VOLM, $decFormat); ?></td>
                                <td style="text-align:right"><?php echo number_format($TOTWO_VOLM, $decFormat); ?></td>
                                <td><?php echo $WO_DESC; ?></td>
                            </tr>
                        <?php
                    endforeach;
                    $rowRem	= $maxRow - $rowNo;
                    for($i=0; $i < $rowRem; $i++)
                    {
                    ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="text-align:center">&nbsp;</td>
                            <td style="text-align:right">&nbsp;</td>
                            <td style="text-align:right">&nbsp;</td>
                            <td style="text-align:right">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    <?php
                    }
                }
            ?>
            <tr height="80px" style="border-color:#000; vertical-align:top">
                <td colspan="9">Catatan : <?php echo $WO_NOTE; ?></td>
            </tr>
        </table>
        <table width="100%" border="1">
            <tr>
                <td width="25%">Diterima Oleh : <br><br>Paraf : <br><br>Tanggal :</td>
                <td width="25%">Diperiksa Oleh : <br><br>Paraf : <br><br>Tanggal :</td>
                <td width="25%">Dibuat Oleh : <br><br>Paraf : <br><br>Tanggal :</td>
                <td width="25%">No. Form : 19.R0/LOG/17 <br><br>Paraf : <br><br>Tanggal :</td>
            </tr>
        </table>
	</section>
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