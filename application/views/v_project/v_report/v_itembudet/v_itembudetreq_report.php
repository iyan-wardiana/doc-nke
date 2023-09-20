<?php
/* 
 * Author   = Dian Hermanto
 * Create Date  = 22 Maret 2019
 * File Name  = v_itembudet_report.php
 * Location   = -
*/

if($viewType == 1)
{
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=exceldata.xls");
  header("Pragma: no-cache");
  header("Expires: 0");
}

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
  $Display_Rows = $row->Display_Rows;
  $decFormat = $row->decFormat;
endforeach;
$DefEmp_ID  = $this->session->userdata['Emp_ID'];

$PRJCOST  = 0;
$PRJNAME  = '';
$PRJDATE  = date('Y/m/d');
$PRJDATE_CO = date('Y/m/d');
$sqlPRJ   = "SELECT PRJDATE, PRJNAME, PRJDATE_CO, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
$resPRJ   = $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
  $PRJCOST  = $rowPRJ->PRJCOST;
  $PRJNAME  = $rowPRJ->PRJNAME;
  $PRJDATE  = date('Y/m/d', strtotime($rowPRJ->PRJDATE));
  $PRJDATE_CO = date('Y/m/d', strtotime($rowPRJ->PRJDATE_CO));
endforeach;

$ITM_NAME = '';
// $sqlITM   = "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' LIMIT 1";
// $resITM   = $this->db->query($sqlITM)->result();
// foreach($resITM as $rowITM):
//   $ITM_NAME = $rowITM->ITM_NAME;
// endforeach;

function cut_text($var, $len = 200, $txt_titik = "-") 
{
  $var1 = explode("</p>",$var);
  $var  = $var1[0];
  if (strlen ($var) < $len) 
  { 
    return $var; 
  }
  if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
  {
    return $match [1] . $txt_titik;
  }
  else
  {
    return substr ($var, 0, $len) . $txt_titik;
  }
}

$getJOBID   = "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBPARENT'";
$resJOBID   = $this->db->query($getJOBID);
$JOBCODEID  = '';
$JOBDESC    = '';
if($resJOBID->num_rows() > 0)
{
    foreach($resJOBID->result() as $rJOBID):
        $JOBCODEID_H  = $rJOBID->JOBCODEID;
        $JOBDESC_H    = $rJOBID->JOBDESC;
    endforeach;
}

// get ITM_CODE from JOBPARENT
if($ITM_CODE[0] != 1) 
{
    $arrITM     = implode("','", $ITM_CODE);
    $addQITM    = "AND ITM_CODE IN ('$arrITM')";
}
else
    $addQITM    = "";

$getITMCODE     = "SELECT JOBCODEID, ITM_CODE FROM tbl_joblist_detail
                    WHERE PRJCODE = '$PRJCODE' AND JOBCODEID LIKE '$JOBPARENT%' AND ISLAST = 1 $addQITM
                    ORDER BY ORD_ID ASC";
$resITMCODE     = $this->db->query($getITMCODE);
$JOBCODEID      = [];
$ITM_CODE       = [];
if($resITMCODE->num_rows() > 0)
{
    foreach($resITMCODE->result() as $rITM):
        $JOBCODEID[]  = $rITM->JOBCODEID;
        $ITM_CODE[]   = $rITM->ITM_CODE;
    endforeach;
}

if(count($JOBCODEID) > 1)
{
    $arrJOBID     = implode("','", $JOBCODEID);
    $addQJOBID    = "AND A.JOBCODEID IN ('$arrJOBID')";
}
else
{
    $arrJOBID     = implode("','", $JOBCODEID);
    $addQJOBID    = "AND A.JOBCODEID = '$arrJOBID'";
}

if(count($ITM_CODE) > 1)
{
    $arrITM     = implode("','", $ITM_CODE);
    $addQITM    = "AND A.ITM_CODE IN ('$arrITM')";
}
else
{
    $arrITM     = implode("','", $ITM_CODE);
    $addQITM    = "AND A.ITM_CODE = '$arrITM'";
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
  <title><?php echo $title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body style="overflow:auto">
<section class="content">
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td width="300">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
        <td width="300" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="300" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="3" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td colspan="2" class="style2" style="text-align:left; font-weight:bold; text-transform:uppercase; font-size:20px"><?php echo $h1_title; ?>
        </td>
  </tr>
    <tr>
        <td colspan="3" valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:16px"><?php /*?>&nbsp;Periode : <?php */?>
        <?php 
        /*$CRDate = date_create($End_Date);
        echo date_format($CRDate,"d-m-Y");*/
        $StartDate  = date('d-m-Y');
        //echo "$StartDate";
      ?></td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center; font-style:italic">&nbsp;</td>
      </tr>
    <tr style="vertical-align: top;">
        <td class="style2" style="text-align:left; font-style:italic">
            <table width="100%">
              <!--<tr style="text-align:left; font-style:italic">
              <td width="8%" nowrap valign="top">Type Dokumen</td>
              <td width="1%">:</td>
              <td width="91%"><?php// echo $DOCTYPE1; ?></td>
          </tr>-->
                <tr style="text-align:left; font-style:italic; display: none;">
                    <td width="8%" nowrap valign="top">NAMA LAPORAN</td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo "$h1_title"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">PERIODE LAPORAN</td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo "$datePeriod"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">KODE PROYEK</td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo "$PRJCODE"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">NAMA PROYEK</td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAME;?></span></td>
              </tr>
              <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">Induk Pekerjaan</td>
                  <td>:</td>
                  <td><?php echo "$JOBCODEID_H - $JOBDESC_H"; ?></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">TANGGAL CETAK</td>
                  <td>:</td>
                  <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                </tr>
            </table>
        </td>
        <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
        <td class="style2" style="text-align:left; font-style:italic">
            <?php
                $sqlAMC         = "tbl_joblist_detail A
                                   INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.PRJCODE = A.PRJCODE
                                   WHERE A.PRJCODE = '$PRJCODE' $addQITM $addQJOBID";
                $resAMC         = $this->db->count_all($sqlAMC);
                if($resAMC > 0)
                {
                    $ITMGROUP   = "";
                    $sqlJBUD    = "SELECT SUM(A.ITM_VOLM) AS ITM_VOLMBG, SUM(A.ITM_BUDG) AS ITM_BUDG, 
                                    SUM(ADDVOLM) AS ADD_VOLM, SUM(ADDCOST) AS ADD_TOTAL, 
                                    SUM(ADDMVOLM) AS ADDM_VOLM, SUM(ADDMCOST) AS ADDM_TOTAL  
                                    FROM tbl_joblist_detail A
                                    INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.PRJCODE = A.PRJCODE
                                    WHERE A.PRJCODE = '$PRJCODE' $addQITM $addQJOBID";
                    $resJBUD    = $this->db->query($sqlJBUD)->result();
                    foreach($resJBUD as $rowJBUD):
                        $ITM_VOLMBG = $rowJBUD->ITM_VOLMBG;
                        $ITMVOLMBG  = $rowJBUD->ITM_VOLMBG;
                        $ITM_BUDG   = $rowJBUD->ITM_BUDG;

                    // ADDENDUM
                        $ADD_VOLM   = $rowJBUD->ADD_VOLM;
                        $ADD_TOTAL  = $rowJBUD->ADD_TOTAL;
                        $ADDM_VOLM  = $rowJBUD->ADDM_VOLM;
                        $ADDM_TOTAL = $rowJBUD->ADDM_TOTAL;

                    // AFTER ADDENDUM
                        $ITM_VOLM2  = $ITM_VOLMBG + $ADD_VOLM - $ADDM_VOLM;
                        $ITM_BUDG2  = $ITM_BUDG + $ADD_TOTAL - $ADDM_TOTAL;

                    // PERMINTAAN
                        $sqlTREQ     = "SELECT SUM(A.PO_VOLM) AS TREQ_VOLM, SUM(A.PO_COST) AS TREQ_AMOUNT
                                        FROM tbl_po_detail A 
                                        INNER JOIN tbl_po_header B ON B.PO_NUM = A.PO_NUM AND B.PRJCODE = B.PRJCODE
                                        WHERE A.PRJCODE = '$PRJCODE' $addQITM $addQJOBID AND A.PO_DATE BETWEEN '$Start_Date' AND '$End_Date' AND B.PO_STAT IN (1,2,3,4,7)
                                        UNION
                                        SELECT SUM(A.WO_VOLM) AS TREQ_VOLM, SUM(A.WO_TOTAL) AS TREQ_AMOUNT
                                        FROM tbl_wo_detail A 
                                        INNER JOIN tbl_wo_header B ON B.WO_NUM = A.WO_NUM AND B.PRJCODE = A.PRJCODE
                                        WHERE A.PRJCODE = '$PRJCODE' $addQITM $addQJOBID AND A.WO_DATE BETWEEN '$Start_Date' AND '$End_Date' AND B.WO_STAT IN (1,2,3,4,7)
                                        UNION
                                        SELECT SUM(A.ITM_VOLM) AS TREQ_VOLM, SUM(A.JournalD_Debet) AS TREQ_AMOUNT
                                        FROM tbl_journaldetail_vcash A 
                                        INNER JOIN tbl_journalheader_vcash B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
                                        WHERE B.proj_Code = '$PRJCODE' $addQITM $addQJOBID AND B.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' AND B.GEJ_STAT IN (1,2,3,4,7)
                                        UNION
                                        SELECT SUM(A.ITM_VOLM) AS TREQ_VOLM, SUM(A.JournalD_Debet) AS TREQ_AMOUNT
                                        FROM tbl_journaldetail_cprj A 
                                        INNER JOIN tbl_journalheader_cprj B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
                                        WHERE B.proj_Code = '$PRJCODE' $addQITM $addQJOBID AND B.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' AND B.GEJ_STAT IN (1,2,3,4,7)";
                        $resTREQ     = $this->db->query($sqlTREQ);
                        foreach($resTREQ->result() as $rTREQ):
                            $TREQ_VOLM      = $rTREQ->TREQ_VOLM;
                            $TREQ_AMOUNT    = $rTREQ->TREQ_AMOUNT;
                        endforeach;


                    // SISA BUDGET THD PERMINTAAN
                        $REM_VOLM       = $ITM_VOLM2 - $TREQ_VOLM;
                        $REM_AMOUNT     = $ITM_BUDG2 - $TREQ_AMOUNT;

                        ?>
                            <table width="100%" border="0" rules="all" style="border-bottom: 1px solid;">
                              <!--<tr style="text-align:left; font-style:italic">
                              <td width="8%" nowrap valign="top">Type Dokumen</td>
                              <td width="1%">:</td>
                              <td width="91%"><?php// echo $DOCTYPE1; ?></td>
                          </tr>-->
                                <tr style="text-align:left; font-style:italic;  border: 2px double;">
                                    <td width="50" nowrap valign="top" style="font-weight: bold;"><?php echo "$JOBCODEID_H - $JOBDESC_H"; ?></td>
                                    <td width="50" style="font-weight: bold; text-align: center;">Volume</td>
                                    <td width="150" style="font-weight: bold; text-align: center;">Jumlah</td>
                                </tr>
                                <tr style="text-align:left; font-style:italic">
                                    <td width="50" nowrap valign="top">BUDGET AWAL</td>
                                    <td width="50" style="text-align: right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
                                    <td width="150" style="text-align: right;"><?php echo number_format($ITM_BUDG, 2);?></td>
                                </tr>
                                <tr style="text-align:left; font-style:italic">
                                    <td width="50" nowrap valign="top">PERUBAHAN</td>
                                    <td width="50" style="text-align: right;"><?php echo number_format($ADD_VOLM, 2); ?></td>
                                    <td width="150" style="text-align: right;"><?php echo number_format($ADD_TOTAL, 2); ?></span></td>
                                </tr>
                                <tr style="text-align:left; font-style:italic">
                                  <td nowrap valign="top">SETELAH PERUBAHAN</td>
                                  <td style="text-align: right;"><?php echo number_format($ITM_VOLM2, 2); ?></td>
                                  <td style="text-align: right;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
                              </tr>
                              <tr style="text-align:left; font-style:italic">
                                  <td nowrap valign="top">PERMINTAAN</td>
                                  <td style="text-align: right;"><?php echo number_format($TREQ_VOLM, 2); ?></td>
                                  <td style="text-align: right;"><?php echo number_format($TREQ_AMOUNT, 2); ?></td>
                              </tr>
                              <tr style="text-align:left; font-style:italic">
                                  <td nowrap valign="top" style="font-weight: bold;">SISA BUDGET THD PERMINTAAN</td>
                                  <td style="text-align: right; font-weight: bold;"><?php echo number_format($REM_VOLM, 2); ?></td>
                                  <td style="text-align: right; font-weight: bold;"><?php echo number_format($REM_AMOUNT, 2); ?></td>
                              </tr>
                            </table>
                        <?php
                    endforeach;
                }
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center"><hr /></td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
            <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                    <th width="1%" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO</th>
                    <th width="2%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">KODE</th>
                    <th width="5%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">KETERANGAN</th>
                    <th width="5%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">ITEM</th>
                    <th width="3%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">VOLUME</th>
                    <th width="3%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">HARGA</th>
                    <th width="3%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">JUMLAH</th>
                    <th width="3%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">SISA BUDGET</th>
                    <th width="2%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">SATUAN</th>
                </tr>
              <tr style="line-height:1px; border-left:hidden; border-right:hidden">
                 <td colspan="8" nowrap style="text-align:center;border:none">&nbsp;</td>
               </tr>
               <?php
                    $sqlREQ     = "SELECT A.PO_NUM AS REQ_NUM, A.PO_CODE AS REQ_CODE, A.PO_DATE AS REQ_DATE, A.PO_DESC AS REQ_DESC, A.PO_VOLM AS REQ_VOLM, A.PO_COST AS REQ_AMOUNT, A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT 
                                    FROM tbl_po_detail A 
                                    INNER JOIN tbl_po_header B ON B.PO_NUM = A.PO_NUM AND B.PRJCODE = B.PRJCODE
                                    WHERE A.PRJCODE = '$PRJCODE' $addQITM $addQJOBID AND A.PO_DATE BETWEEN '$Start_Date' AND '$End_Date' AND B.PO_STAT IN (1,2,3,4,7)
                                    UNION
                                    SELECT A.WO_NUM AS REQ_NUM, A.WO_CODE AS REQ_CODE, A.WO_DATE AS REQ_DATE, A.WO_DESC AS REQ_DESC, A.WO_VOLM AS REQ_VOLM, A.WO_TOTAL AS REQ_AMOUNT, A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT
                                    FROM tbl_wo_detail A 
                                    INNER JOIN tbl_wo_header B ON B.WO_NUM = A.WO_NUM AND B.PRJCODE = A.PRJCODE
                                    WHERE A.PRJCODE = '$PRJCODE' $addQITM $addQJOBID AND A.WO_DATE BETWEEN '$Start_Date' AND '$End_Date' AND B.WO_STAT IN (1,2,3,4,7)
                                    UNION
                                    SELECT B.JournalH_Code AS REQ_NUM, B.Manual_No AS REQ_CODE, B.JournalH_Date AS REQ_DATE, A.Other_Desc AS REQ_DESC, A.ITM_VOLM AS REQ_VOLM, A.JournalD_Debet AS REQ_AMOUNT, A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT
                                    FROM tbl_journaldetail_vcash A 
                                    INNER JOIN tbl_journalheader_vcash B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
                                    WHERE B.proj_Code = '$PRJCODE' $addQITM $addQJOBID AND B.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' AND B.GEJ_STAT IN (1,2,3,4,7)
                                    UNION
                                    SELECT B.JournalH_Code AS REQ_NUM, B.Manual_No AS REQ_CODE, B.JournalH_Date AS REQ_DATE, A.Other_Desc AS REQ_DESC, A.ITM_VOLM AS REQ_VOLM, A.JournalD_Debet AS REQ_AMOUNT, A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT
                                    FROM tbl_journaldetail_cprj A 
                                    INNER JOIN tbl_journalheader_cprj B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
                                    WHERE B.proj_Code = '$PRJCODE' $addQITM $addQJOBID AND B.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' AND B.GEJ_STAT IN (1,2,3,4,7)";
                    $resREQ     = $this->db->query($sqlREQ);
                    $TOTREQ_AM      = 0;
                    $TOTREMREQ_BUDG = 0;
                    if($resREQ->num_rows() > 0) 
                    {
                        $no             = 0;
                        $REMREQ_BUDG    = $ITM_BUDG;
                        foreach($resREQ->result() as $rREQ):
                            $no         = $no + 1;
                            $REQ_NUM    = $rREQ->REQ_NUM;
                            $REQ_CODE   = $rREQ->REQ_CODE;
                            $REQ_DATE   = $rREQ->REQ_DATE;
                            $REQ_DESC   = $rREQ->REQ_DESC;
                            $REQ_VOLM   = $rREQ->REQ_VOLM;
                            $REQ_AMOUNT = $rREQ->REQ_AMOUNT;
                            $JOBCODEID  = $rREQ->JOBCODEID;
                            $ITM_CODE   = $rREQ->ITM_CODE;
                            $ITM_UNIT   = $rREQ->ITM_UNIT;
                            
                            if($REQ_VOLM == '' || $REQ_VOLM == 0) 
                                $REQ_VOLM = 1;
                                
                            $REQ_PRICE      = $REQ_AMOUNT / $REQ_VOLM;
                            $REMREQ_BUDG    = $REMREQ_BUDG - $REQ_AMOUNT;

                            $TOTREQ_AM          = $TOTREQ_AM + $REQ_AMOUNT;
                            $TOTREMREQ_BUDG     = $REMREQ_BUDG;

                            $this->db->select("JOBPARENT, JOBDESC")->from("tbl_joblist_detail");
                            $this->db->where(["PRJCODE" => $PRJCODE, "JOBCODEID" => $JOBCODEID, "ITM_CODE" => $ITM_CODE]);
                            $getJobDESC     = $this->db->get();
                            foreach($getJobDESC->result() as $rJD):
                                $JOBPARENT      = $rJD->JOBPARENT;
                                $JOBDESC        = $rJD->JOBDESC;
                            endforeach;


                            ?>
                                <tr>
                                    <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; border-right-color:#000; border-right-width:2px;"><?php echo $no; ?></td>
                                    <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;"><?php echo $REQ_CODE; ?></td>
                                    <td width="300" style="text-align:left;border-bottom-width:2px; border-bottom-color:#000;"><?php echo $REQ_DESC; ?></td>
                                    <td nowrap style="text-align:left;border-bottom-width:2px; border-bottom-color:#000;">
                                        <?php
                                            $JOBDESCH       = "";
                                            $sqlJOBDESC     = "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
                                            $resJOBDESC     = $this->db->query($sqlJOBDESC)->result();
                                            foreach($resJOBDESC as $rowJOBDESC) :
                                                $JOBDESCH   = $rowJOBDESC->JOBDESC;
                                            endforeach;

                                            $JobView        = "$ITM_CODE - $JOBDESC";
                                            $JobView        = wordwrap($JobView, 50, "<br>", TRUE);

                                            $JOBDESCH1      = wordwrap("$JOBPARENT : $JOBDESCH", 50, "<br>", TRUE);
                                            $JOBDESCH       = '<div style="margin-left: 15px; font-style: italic;">
                                                                    &nbsp;&nbsp;'.$JOBDESCH1.'
                                                                </div>';

                                            
                                            $JOBDESCH1      = $JOBDESCH;
                                            if($JOBPARENT == '')
                                            {
                                                $disButton      = 1;
                                                $JOBDESCH1      = "Kode komponen ini belum terkunci atau sedang dibuka dalam daftar RAP. Silahkan hubungi pihak yang memiliki otorisasi mengunci RAP.";
                                                $JOBDESCH2      = wordwrap("$JOBDESCH1", 50, "<br>", TRUE);
                                                $JOBDESCH       = '<span class="label label-danger" style="font-size:12px; font-style: italic;">'.$JOBDESCH2.'</span>';
                                            }

                                            echo "$JobView$JOBDESCH";
                                        ?>
                                    </td>
                                    <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;"><?php echo number_format($REQ_VOLM, 2); ?></td>
                                    <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000;"><?php echo number_format($REQ_PRICE, 2); ?></td>
                                    <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000;"><?php echo number_format($REQ_AMOUNT, 2); ?></td>
                                    <td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000;"><?php echo number_format($REMREQ_BUDG, 2); ?></td>
                                    <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;"><?php echo $ITM_UNIT; ?></td>
                                </tr>
                            <?php
                        endforeach;
                    }
               ?>
                <tr>
                    <td colspan="6" nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; border-right-color:#000; text-align: right; font-weight: bold;">TOTAL</td>
                    <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; text-align: right; font-weight: bold;"><?php echo number_format($TOTREQ_AM, 2); ?></td>
                    <td colspan="2" nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
                </tr>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>