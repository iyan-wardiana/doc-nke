<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Oktober 2018
 * File Name	= v_prodprocess_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$comp_name 	= $this->session->userdata('comp_name');
$appBody    = $this->session->userdata['appBody'];

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

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$currentRow = 0;

	$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
	
	$SO_NUM			= '';
	$SC_NUM			= '';
	$SO_CODE 		= '';
	$SO_TYPE 		= 1; // Internal
	$SO_CAT			= 1;
	$SO_DATE		= '';
	$PRJNAME 		= '';
	$CUST_CODE 		= '0';
	$CUST_DESC 		= '';
	$CUST_ADD1 		= '';
	$SO_CURR 		= 'IDR';
	$SO_CURRATE		= 1;
	$SO_TAXCURR 	= 'IDR';
	$SO_TAXRATE 	= 1;
	$SO_TOTCOST		= 0;
	$DP_CODE		= '';
	$DP_JUML		= 0;
	$SO_PAYTYPE 	= 'Cash';
	$SO_TENOR 		= 0;
	$SO_STAT 		= 1;
	$SO_INVSTAT		= 0;					
	$SO_NOTES		= '';
	$SO_MEMO 		= '';
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

        <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/qrcode/css/style.css'; ?>">
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

    <?php
    	//______$this->load->view('template/topbar');
    	//______$this->load->view('template/sidebar');
    	
    	$ISREAD 	= $this->session->userdata['ISREAD'];
    	$ISCREATE 	= $this->session->userdata['ISCREATE'];
    	$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
    	$ISDWONL 	= $this->session->userdata['ISDWONL'];
    	$LangID 	= $this->session->userdata['LangID'];

    	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    	$resTransl		= $this->db->query($sqlTransl)->result();
    	foreach($resTransl as $rowTransl) :
    		$TranslCode	= $rowTransl->MLANG_CODE;
    		$LangTransl	= $rowTransl->LangTransl;
    		
    		if($TranslCode == 'Add')$Add = $LangTransl;
    		if($TranslCode == 'Edit')$Edit = $LangTransl;
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'DetInfo')$DetInfo = $LangTransl;
    		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
    		if($TranslCode == 'OwnerName')$OwnerName = $LangTransl;
    		if($TranslCode == 'Customer')$Customer = $LangTransl;
            if($TranslCode == 'CustName')$CustName = $LangTransl;
    		if($TranslCode == 'Receipt')$Receipt = $LangTransl;
            if($TranslCode == 'SOCode')$SOCode = $LangTransl;
            if($TranslCode == 'SODate')$SODate = $LangTransl;
    		if($TranslCode == 'BOMCode')$BOMCode = $LangTransl;
            if($TranslCode == 'JOCode')$JOCode = $LangTransl;
            if($TranslCode == 'JODate')$JODate = $LangTransl;
    		if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Status')$Status = $LangTransl;
    		if($TranslCode == 'ShowDetail')$ShowDetail = $LangTransl;
            if($TranslCode == 'GreigeFrom')$GreigeFrom = $LangTransl;
            if($TranslCode == 'GreigeFrom')$GreigeFrom = $LangTransl;
            if($TranslCode == 'GreigeFrom')$GreigeFrom = $LangTransl;
    	endforeach;
    	if($LangID == 'IND')
    	{
    		$alert1		= 'Silahkan Scan QR Code.';
    		$alert2		= 'Silahkan pilih nama supplier';
    		$isManual	= "Centang untuk kode manual.";
    	}
    	else
    	{
    		$alert1		= 'Please scan the QR Code.';
    		$alert2		= 'Please select a supplier name';
    		$isManual	= "Check to manual code.";
    	}
        // IRFBS19-FBSHO-191023063819010002 -- GRP.19.093204.00001 -- 191030171118
        $QRCode     = 'STW20112416325901001';
        $QRCType    = '';
        $PRJCODE    = '';
        $JO_NUM     = '';
        $JO_CODE    = '';
        $JO_DATEV   = '';
        $IR_CODE    = '';
        $ITM_CODE   = '';
        $ITM_NAME   = '';
        $QRC_PATT   = '';
        $SO_NUM     = '';
        $SO_CODE    = '';
        $SO_DATEV   = '';
        $BOM_NUM    = '';
        $BOM_CODE   = '';
        $BOM_NAME   = '';
        $CUST_DESC  = '';
        $ISEXIST    = 0;
        $QRC_CODEV  = '';
        $QRC_DATE   = '';
        $QRC_DATEV  = '';
        $GRP_CODE   = '';
        $JO_DESC    = '';
        $TOT_SOVOLM = 0;

        if(isset($_POST['scanned-QRText']))
        {
            $QRCode     = $_POST['scanned-QRText'];
            $QRCType    = $_POST['QRCType'];
            if($QRCType == 'QRCL')
            {
                $ISEXIST    = 1;
                $PRJCODE    = '';
                $ITM_CODE   = '';
                $IR_CODE    = '';
                $QRC_CODEV  = '';
                $QRC_DATE   = '';
                $CUST_DESC  = '';
                $IR_NUM     = '';
                $IR_CODE    = '';
                $ITM_NAME   = '';
                $GRP_CODE   = '';
                $JO_NUM     = '';
                $sqlSO      = "SELECT PRJCODE, QRC_CODEV, QRC_DATE, REC_DESC AS CUST_DESC, IR_NUM, IR_CODE, ITM_NAME, GRP_CODE, JO_NUM, QRC_PATT
                                    FROM tbl_qrc_detail WHERE QRC_NUM = '$QRCode' LIMIT 1";
                $resSO      = $this->db->query($sqlSO)->result();
                foreach($resSO as $rowSO) :
                    $PRJCODE    = $rowSO->PRJCODE;
                    $QRC_CODEV  = $rowSO->QRC_CODEV;
                    $QRC_DATE   = $rowSO->QRC_DATE;
                    $CUST_DESC  = $rowSO->CUST_DESC;
                    $IR_NUM     = $rowSO->IR_NUM;
                    $IR_CODE    = $rowSO->IR_CODE;
                    $ITM_NAME   = $rowSO->ITM_NAME;
                    $GRP_CODE   = $rowSO->GRP_CODE;
                    $JO_NUM     = $rowSO->JO_NUM;
                    $QRC_PATT   = $rowSO->QRC_PATT;
                endforeach;

                $IR_NOTE        = "-";
                $sqlIR          = "SELECT IR_DATE, IR_NOTE FROM tbl_ir_header WHERE IR_NUM = '$IR_NUM' LIMIT 1";
                $resIR          = $this->db->query($sqlIR)->result();
                foreach($resIR as $rowIR) :
                    $IR_DATE    = date('d-m-Y', strtotime($rowIR->IR_DATE));
                    $IR_NOTE    = $rowSO->IR_NOTE;
                endforeach;

                $JO_CODE    = '';
                $JO_DESC    = '';
                $SO_NUM     = '';
                $SO_CODE    = '';
                $SO_DATEV   = '';
                $TOT_SOVOLM = 0;
                $BOM_CODE   = '';
                $ITM_CODE   = '';
                $BOM_NAME   = '';
                if($JO_NUM != '')
                {
                    $sqlJO     = "SELECT A.JO_CODE, A.JO_DATE, A.JO_DESC, A.SO_NUM, A.SO_CODE, A.BOM_CODE, B.ITM_CODE, C.SO_DATE
                                    FROM tbl_jo_header A
                                        INNER JOIN tbl_jo_detail B ON B.JO_NUM = A.JO_NUM
                                        INNER JOIN tbl_so_header C ON C.SO_NUM = A.SO_NUM
                                    WHERE A.JO_NUM = '$JO_NUM' LIMIT 1";
                    $resJO      = $this->db->query($sqlJO)->result();
                    foreach($resJO as $rowJO) :
                        $JO_CODE    = $rowJO->JO_CODE;
                        $JO_DESC    = $rowJO->JO_DESC;
                        $JO_DATEV   = date('d-m-Y', strtotime($rowJO->JO_DATE));
                        $SO_NUM     = $rowJO->SO_NUM;
                        $SO_CODE    = $rowJO->SO_CODE;
                        $SO_DATEV   = date('d-m-Y', strtotime($rowJO->SO_DATE));
                        $BOM_CODE   = $rowJO->BOM_CODE;
                        $ITM_CODE   = $rowJO->ITM_CODE;
                    endforeach;

                    if($SO_NUM != '')
                    {
                        $sqlSO      = "SELECT SUM(SO_VOLM) AS TOT_SOVOLM from tbl_so_detail WHERE SO_NUM = '$SO_NUM' AND ITM_CODE = '$ITM_CODE'";
                        $resSO      = $this->db->query($sqlSO)->result();
                        foreach($resSO as $rowSO) :
                            $TOT_SOVOLM    = $rowSO->TOT_SOVOLM;
                        endforeach;
                    }

                    if($BOM_CODE != '')
                    {
                        $sqlBOM     = "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_CODE = '$BOM_CODE' LIMIT 1";
                        $sqlBOM     = $this->db->query($sqlBOM)->result();
                        foreach($sqlBOM as $rowBOM) :
                            $BOM_NAME   = $rowBOM->BOM_NAME;
                        endforeach;                     
                    }
                }
            }
            else if($QRCType == 'GRPL')
            {
                $ISEXIST    = 1;
                $PRJCODE    = '';
                $ITM_CODE   = '';
                $IR_CODE    = '';
                $QRC_CODEV  = '';
                $QRC_DATE   = '';
                $CUST_DESC  = '';
                $IR_CODE    = '';
                $ITM_NAME   = '';
                $GRP_CODE   = '';
                $JO_NUM     = '';
                $sqlSO      = "SELECT PRJCODE, ICOLL_CODE AS QRC_CODEV, ICOLL_CREATED AS QRC_DATE, CUST_DESC, ICOLL_REFNUM AS IR_CODE,
                                        ICOLL_NOTES AS ITM_NAME, ICOLL_CODE AS GRP_CODE, JO_NUM
                                    FROM tbl_item_collh WHERE ICOLL_CODE = '$QRCode' LIMIT 1";
                $resSO      = $this->db->query($sqlSO)->result();
                foreach($resSO as $rowSO) :
                    $PRJCODE    = $rowSO->PRJCODE;
                    $QRC_CODEV  = $rowSO->QRC_CODEV;
                    $QRC_DATE   = $rowSO->QRC_DATE;
                    $CUST_DESC  = $rowSO->CUST_DESC;
                    $IR_CODE    = $rowSO->IR_CODE;
                    $ITM_NAME   = $rowSO->ITM_NAME;
                    $GRP_CODE   = $rowSO->GRP_CODE;
                    $JO_NUM     = $rowSO->JO_NUM;
                endforeach;

                $JO_CODE    = '';
                $JO_DESC    = '';
                $SO_NUM     = '';
                $SO_CODE    = '';
                $SO_DATEV   = '';
                $TOT_SOVOLM = 0;
                $BOM_CODE   = '';
                $ITM_CODE   = '';
                $BOM_NAME   = '';
                if($JO_NUM != '')
                {
                    $sqlJO     = "SELECT A.JO_CODE, A.JO_DATE, A.JO_DESC, A.SO_NUM, A.SO_CODE, A.BOM_CODE, B.ITM_CODE, C.SO_DATE
                                    FROM tbl_jo_header A
                                        INNER JOIN tbl_jo_detail B ON B.JO_NUM = A.JO_NUM
                                        INNER JOIN tbl_so_header C ON C.SO_NUM = A.SO_NUM
                                    WHERE A.JO_NUM = '$JO_NUM' LIMIT 1";
                    $resJO      = $this->db->query($sqlJO)->result();
                    foreach($resJO as $rowJO) :
                        $JO_CODE    = $rowJO->JO_CODE;
                        $JO_DESC    = $rowJO->JO_DESC;
                        $JO_DATEV   = date('d-m-Y', strtotime($rowJO->JO_DATE));
                        $SO_NUM     = $rowJO->SO_NUM;
                        $SO_CODE    = $rowJO->SO_CODE;
                        $SO_DATEV   = date('d-m-Y', strtotime($rowJO->SO_DATE));
                        $BOM_CODE   = $rowJO->BOM_CODE;
                        $ITM_CODE   = $rowJO->ITM_CODE;
                    endforeach;

                    if($SO_NUM != '')
                    {
                        $sqlSO      = "SELECT SUM(SO_VOLM) AS TOT_SOVOLM from tbl_so_detail WHERE SO_NUM = '$SO_NUM' AND ITM_CODE = '$ITM_CODE'";
                        $resSO      = $this->db->query($sqlSO)->result();
                        foreach($resSO as $rowSO) :
                            $TOT_SOVOLM    = $rowSO->TOT_SOVOLM;
                        endforeach;
                    }

                    if($BOM_CODE != '')
                    {
                        $sqlBOM     = "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_CODE = '$BOM_CODE' LIMIT 1";
                        $sqlBOM     = $this->db->query($sqlBOM)->result();
                        foreach($sqlBOM as $rowBOM) :
                            $BOM_NAME   = $rowBOM->BOM_NAME;
                        endforeach;                     
                    }
                }
                $IR_NOTE    = "-";
            }
            else if($QRCType == 'JOL')
            {
                $ISEXIST    = 1;
                $PRJCODE    = '';
                $ITM_CODE   = '';
                $IR_CODE    = '';
                $QRC_CODEV  = '';
                $QRC_DATE   = '';
                $CUST_DESC  = '';
                $IR_CODE    = '';
                $ITM_NAME   = '';
                $GRP_CODE   = '';
                $JO_NUM     = '';
                $JO_CODE    = '';
                $JO_DESC    = '';
                $SO_NUM     = '';
                $SO_CODE    = '';
                $SO_DATEV   = '';
                $TOT_SOVOLM = 0;
                $BOM_CODE   = '';
                $ITM_CODE   = '';
                $BOM_NAME   = '';

                $sqlJO      = "SELECT A.JO_NUM, A.JO_CODE, A.JO_DATE, A.JO_DESC, A.SO_NUM, A.SO_CODE, A.BOM_CODE, B.ITM_CODE, C.SO_DATE,
                                    A.PRJCODE, A.JO_CODE AS QRC_CODEV, A.JO_DATE AS QRC_DATE, A.CUST_DESC, '' AS IR_CODE, '' AS GRP_CODE,
                                    C.SO_DATE, A.BOM_CODE
                                FROM tbl_jo_header A
                                    INNER JOIN tbl_jo_detail B ON B.JO_NUM = A.JO_NUM
                                    INNER JOIN tbl_so_header C ON C.SO_NUM = A.SO_NUM
                                WHERE A.JO_UC = '$QRCode' LIMIT 1";
                $resJO      = $this->db->query($sqlJO)->result();
                foreach($resJO as $rowJO) :
                    $PRJCODE    = $rowJO->PRJCODE;
                    $QRC_CODEV  = $rowJO->QRC_CODEV;
                    $QRC_DATE   = $rowJO->QRC_DATE;
                    $CUST_DESC  = $rowJO->CUST_DESC;
                    $IR_CODE    = $rowJO->IR_CODE;
                    $ITM_NAME   = '';
                    $GRP_CODE   = $rowJO->GRP_CODE;
                    $JO_NUM     = $rowJO->JO_NUM;
                    $JO_CODE    = $rowJO->JO_CODE;
                    $JO_DESC    = $rowJO->JO_DESC;
                    $JO_DATEV   = date('d-m-Y', strtotime($rowJO->JO_DATE));
                    $SO_NUM     = $rowJO->SO_NUM;
                    $SO_CODE    = $rowJO->SO_CODE;
                    $SO_DATEV   = date('d-m-Y', strtotime($rowJO->SO_DATE));
                    $BOM_CODE   = $rowJO->BOM_CODE;
                    $ITM_CODE   = $rowJO->ITM_CODE;
                endforeach;

                if($SO_NUM != '')
                {
                    $sqlSO      = "SELECT SUM(SO_VOLM) AS TOT_SOVOLM from tbl_so_detail WHERE SO_NUM = '$SO_NUM' AND ITM_CODE = '$ITM_CODE'";
                    $resSO      = $this->db->query($sqlSO)->result();
                    foreach($resSO as $rowSO) :
                        $TOT_SOVOLM    = $rowSO->TOT_SOVOLM;
                    endforeach;
                }

                if($BOM_CODE != '')
                {
                    $sqlBOM     = "SELECT BOM_NAME FROM tbl_bom_header WHERE BOM_CODE = '$BOM_CODE' LIMIT 1";
                    $sqlBOM     = $this->db->query($sqlBOM)->result();
                    foreach($sqlBOM as $rowBOM) :
                        $BOM_NAME   = $rowBOM->BOM_NAME;
                    endforeach;                     
                }
            }
        }
    ?>
            
    <style>
        .inplabel {border:none;background-color:white;}
        .inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
        .inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
        .inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
        .inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
        .inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
        .inpdim {border:none;background-color:white;}
    </style>
    <?php

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
            <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/secttransfer.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h1_title; ?>
            <small><?php //echo $PRJNAME1; ?></small>  </h1>
          <?php /*?><ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Tables</a></li>
            <li class="active">Data tables</li>
          </ol><?php */?>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="display:none">               
                      		<div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body chart-responsive">
                            <div class="container" id="QR-Code">
                                <div class="form-group">
                                    <div class="col-sm-10">
                                        <select class="form-control" id="camera-select" style="display:none"></select>
                                        <div class="form-group" style="text-align:center">
                                            <input id="image-url" type="text" class="form-control" placeholder="Image url" style="display:none">
                                            <button title="Decode Image" class="btn btn-default btn-sm" id="decode-img" type="button" data-toggle="tooltip" style="display:none"><span class="glyphicon glyphicon-upload"></span></button>
                                            <button title="Image shoot" class="btn btn-info btn-sm disabled" id="grab-img" type="button" data-toggle="tooltip" style="display:none"><span class="glyphicon glyphicon-picture"></span></button>
                                            <button title="Play" class="btn btn-success btn-sm" id="play" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-play"></span></button>
                                            <button title="Pause" class="btn btn-warning btn-sm" id="pause" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-pause"></span></button>
                                            <button title="Stop streams" class="btn btn-danger btn-sm" id="stop" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-stop"></span></button>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                    <div class="col-sm-10">
                                        <div class="form-group" style="text-align:center">
                                            <div class="well" style="position: relative;display: inline-block;">
                                                <canvas style="max-width: 250px; max-height: 250px" id="webcodecam-canvas"></canvas>
                                                <div class="scanner-laser laser-rightBottom" style="opacity: 0.5;"></div>
                                                <div class="scanner-laser laser-rightTop" style="opacity: 0.5;"></div>
                                                <div class="scanner-laser laser-leftBottom" style="opacity: 0.5;"></div>
                                                <div class="scanner-laser laser-leftTop" style="opacity: 0.5;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    $urlGetData	= base_url().'index.php/y_5c4nQR/GetData/';
                                ?>
                                <div class="form-group">
                                    <div class="col-sm-10">
                                        <form method="post" name="sendData" id="sendData" class="form-user" action="">
                                            <div class="form-group" style="text-align:center">
                                                <div class="thumbnail" id="result">
                                                    <div class="well" style="overflow: hidden; display:none">
                                                        <img width="150" height="150" id="scanned-img" src="">
                                                    </div>
                                                    <div class="caption">
                                                        <p id="scanned-QR"></p>
                                                        <input type="text" name="scanned-QRText" id="scanned-QRText" value="<?php echo $QRCode; ?>" width="250px" class="inplabel" style="display:none" >
                                                        <input type="hidden" name="QRCType" id="QRCType" value="<?php echo $QRCType; ?>">
                                                        <button type="button" class="btn btn-primary" id="btnDetail">
                                                            <i class="glyphicon glyphicon glyphicon-ok"></i>&nbsp;OK
                                                        </button>
                                                        <button class="btn btn-success" id="btnShD" style="display: none;">
                                                            <i class="glyphicon glyphicon-leaf"></i>&nbsp;<?php echo $ShowDetail; ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
        							<script>
                                        $(document).ready(function()
                                        {
                                            $("#btnDetail").button().click(function()
                                            {
                                                var textField = $("#scanned-QRText").val();

                                                if(textField == '')
                                                {
                                                    alert('<?php echo $alert1; ?>');
                                                    return false;
                                                }
                                                    
                                                $.ajax({
                                                    type: 'POST',
                                                    url: '<?php echo $urlGetData; ?>',
                                                    data: $('#sendData').serialize(),
                                                    success: function(response)
                                                    {
                                                        //alert(response)
        												// $ISEXIST~QRCL~$PRJCODE~$QRCode~$QRC_CODEV~$QRC_DATE~$CUST_DESC~$IR_CODE~$ITM_NAME~$GRP_CODE~$JO_NUM~$JO_CODE~$JO_DATEV~$SO_CODE~$SO_DATEV~$BOM_CODE~$BOM_NAME
                                                        var myarr   = response.split("~");
                                                        isExist     = myarr[0];
                                                        document.getElementById('QRCType').value            = myarr[1];
                                                        if(isExist == 1)
                                                        {
                                                            document.getElementById('noQRC').style.display  = 'none';
                                                            document.getElementById('detInf').style.display = '';
                                                            document.getElementById('btnShD').style.display = '';
                                                            document.getElementById('custName').innerHTML 	= myarr[6];
                                                            document.getElementById('IRCode').innerHTML     = myarr[7];
                                                            document.getElementById('itemCode').innerHTML   = myarr[8];
                                                            document.getElementById('JOCode').innerHTML     = myarr[11]+' / '+ myarr[12];
                                                            document.getElementById('SOCode').innerHTML 	= myarr[13]+' / '+ myarr[14];
                                                            document.getElementById('BOMCode').innerHTML 	= myarr[15]+' / '+ myarr[16];
                                                        }
                                                        else
                                                        {
                                                            document.getElementById('noQRC').style.display  = '';
                                                            document.getElementById('detInf').style.display = 'none';
                                                            document.getElementById('btnShD').style.display = 'none';
                                                        }
                                                    }
                                                });
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="box box-widget widget-user-2">
                                                    <div id="noQRC" class="widget-user-header bg-red" style="text-align:center; display: none;">
                                                        QRC Code not found.
                                                    </div>
                                                    <div id="detInf" class="widget-user-header bg-yellow" style="text-align:center">
                                                        <?php echo $DetInfo; ?>
                                                    </div>
                                                    <div class="box-footer no-padding">
                                                        <ul class="nav nav-stacked">
                                                            <?php
                                                                if($QRCType == 'QRCL')
                                                                {
                                                                ?>
                                                                    <li style="display: none;"><a href="#" onClick="return false"><?php echo $OwnerName; ?>
                                                                    	<span class="pull-right badge bg-blue">
                                                                    		<div id="compName"><?php echo $comp_name; ?></div>
                                                                        </span></a>
                                                                    </li>
                                                                    <li><a href="#" onClick="return false"><?php echo $CustName; ?>
                                                                        <span class="pull-right badge bg-blue">
                                                                            <div id="custName"><?php echo $CUST_DESC; ?></div>
                                                                        </span>
                                                                        </a>
                                                                    </li>
                                                                    <li style="display: none;"><a href="#" onClick="return false"><?php echo $ItemCode; ?>
                                                                        <span class="pull-right badge bg-aqua">
                                                                            <div id="itemCode"><?php echo "$ITM_CODE - $QRC_PATT / $ITM_NAME"; ?></div>
                                                                        </span>
                                                                        </a>
                                                                    </li>
                                                                    <li><a href="#" onClick="return false"><?php echo $Receipt; ?>
                                                                    	<span class="pull-right badge bg-yellow">
                                                                        	<div id="IRCode"><?php echo "$IR_CODE / $IR_DATE"; ?></div>
                                                                        </span>
                                                                        </a>
                                                                    </li>
                                                                    <li ><a href="#" onClick="return false"><?php echo $GreigeFrom; ?>
                                                                        <span class="pull-right badge bg-aqua">
                                                                            <div id="itemCode"><?php echo "$ITM_CODE - $QRC_PATT / $ITM_NAME"; ?></div>
                                                                        </span>
                                                                        </a>
                                                                    </li>
                                                                    <li><a href="#" onClick="return false"><?php echo $SOCode; ?>
                                                                    	<span class="pull-right badge bg-green">
                                                                        	<div id="SOCode"><?php echo "$SO_CODE / $SO_DATEV"; ?></div>
                                                                        </span>
                                                                        </a>
                                                                    </li>
                                                                    <li><a href="#" onClick="return false"><?php echo $JOCode; ?>
                                                                        <span class="pull-right badge bg-purple">
                                                                            <div id="JOCode"><?php echo "$JO_CODE / $JO_DATEV"; ?></div>
                                                                        </span>
                                                                        </a>
                                                                    </li>
                                                                    <li><a href="#" onClick="return false"><?php echo $BOMCode; ?>
                                                                        <span class="pull-right badge bg-red">
                                                                            <div id="BOMCode"><?php echo "$BOM_CODE / $BOM_NAME"; ?></div>
                                                                        </span>
                                                                        </a>
                                                                    </li>
                                                                    <li><a href="#" onClick="return false"><?php echo $Description; ?></a>
                                                                    </li>
                                                                    <li><a href="#" onClick="return false">
                                                                        <div class="alert alert-info alert-dismissible">
                                                                            <p><?php echo $JO_DESC; ?></p>
                                                                        </div>
                                                                        </a>
                                                                    </li>
                                                                    <li style="display: none;"><a href="#" onClick="return false">Log Produksi</a>
                                                                    </li>
                                                                    <div id="detInf" class="widget-user-header bg-green" style="text-align:center">
                                                                        Produksi
                                                                    </div>
                                                                    <li>
                                                                        <a href="#" onClick="return false">
                                                                            <div>
                                                                                <?php
                                                                                    // TOTAL SO PRODSUCTION
                                                                                        $TOT_SOVOLM;

                                                                                    $sqlSTP = "SELECT DISTINCT A.JOSTF_STEP, 
                                                                                                    SUM(A.ITM_QTY) AS TOT_PLN,
                                                                                                    SUM(A.ITM_QTY_PROC) AS TOT_PROC, B.PRODS_NAME
                                                                                                FROM tbl_jo_stfdetail A
                                                                                                INNER JOIN tbl_prodstep B ON A.JOSTF_STEP = B.PRODS_STEP
                                                                                                WHERE A.PRJCODE = '$PRJCODE' 
                                                                                                    AND A.SO_NUM = '$SO_NUM'
                                                                                                    AND A.JOSTF_TYPE = 'OUT'
                                                                                                GROUP BY A.ITM_CODE
                                                                                                ORDER BY B.PRODS_ORDER ASC";
                                                                                    $resSTP = $this->db->query($sqlSTP)->result();
                                                                                    foreach ($resSTP as $key):
                                                                                        $STEP_CODE  = $key->JOSTF_STEP;
                                                                                        $TOT_PLN    = $key->TOT_PLN;
                                                                                        //$TOT_PROC = $key->TOT_PROC;
                                                                                        //$TOT_PERC = $TOT_PROC / $TOT_PLN * 100;
                                                                                        // TOTAL PRODUKSI PER STEP BY SO
                                                                                        $TOT_PLN    = $TOT_SOVOLM;
                                                                                        $TOT_PROD   = 0;
                                                                                        $TOT_PROP   = 0;
                                                                                        $sqlPROC    = "SELECT SUM(DISTINCT A.ITM_QTY) AS TOT_PROD,
                                                                                                             COUNT(A.ITM_QTY) AS TOT_PROP
                                                                                                        FROM tbl_jo_detail A
                                                                                                        INNER JOIN tbl_jo_header B ON A.JO_NUM = B.JO_NUM
                                                                                                        INNER JOIN tbl_stf_detail C ON A.JO_NUM = C.JO_NUM
                                                                                                        WHERE A.SO_NUM = '$SO_NUM' AND B.JO_STAT IN (3,6)
                                                                                                        AND C.STF_FROM = '$STEP_CODE' AND C.ITM_TYPE = 'OUT'";
                                                                                        $resPROC    = $this->db->query($sqlPROC)->result();
                                                                                        foreach ($resPROC as $keyPROC):
                                                                                            $TOT_PROD  = $keyPROC->TOT_PROD;
                                                                                            $TOT_PROP  = $keyPROC->TOT_PROP;
                                                                                        endforeach;

                                                                                        $TOT_PERC    = $TOT_PROD / $TOT_PLN * 100;
                                                                                        $PRODS_NAME  = $key->PRODS_NAME;
                                                                                        if($STEP_CODE == 'ONE')
                                                                                            $STEP_COL   = "red";
                                                                                        elseif($STEP_CODE == 'TWO')
                                                                                            $STEP_COL   = "yellow";
                                                                                        elseif($STEP_CODE == 'THR')
                                                                                            $STEP_COL   = "aqua";
                                                                                        elseif($STEP_CODE == 'FOU')
                                                                                            $STEP_COL   = "blue";
                                                                                        elseif($STEP_CODE == 'FIV')
                                                                                            $STEP_COL   = "purple";
                                                                                        elseif($STEP_CODE == 'SIX')
                                                                                            $STEP_COL   = "green";
                                                                                        elseif($STEP_CODE == 'SEV')
                                                                                            $STEP_COL   = "maroon";
                                                                                        elseif($STEP_CODE == 'NIN')
                                                                                            $STEP_COL   = "navy";
                                                                                        elseif($STEP_CODE == 'TEN')
                                                                                            $STEP_COL   = "orange";
                                                                                        else
                                                                                            $STEP_COL   = "red";
                                                                                        ?>
                                                                                        <div class="progress-group">
                                                                                            <span class="progress-text"><?php echo "$PRODS_NAME ($TOT_PROP)"; ?></span>
                                                                                            <span class="progress-number"><b><?php echo number_format($TOT_PROD,2); ?></b> / <?php echo number_format($TOT_PLN,2); ?> &nbsp; (<?php echo number_format($TOT_PERC,2); ?> %)</span>
                                                                                            <div class="progress sm">
                                                                                                <div class="progress-bar progress-bar-<?php echo $STEP_COL; ?>" style="width: <?php echo $TOT_PERC; ?>%"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php
                                                                                    endforeach;
                                                                                ?>
                                                                            </div>
                                                                        </a>
                                                                    </li>
                                                                <?php
                                                                }
                                                                else
                                                                {
                                                                ?>
                                                                    <li style="display: none;"><a href="#" onClick="return false"><?php echo $OwnerName; ?>
                                                                        <span class="pull-right badge bg-blue">
                                                                            <div id="compName"><?php echo $comp_name; ?></div>
                                                                        </span></a>
                                                                    </li>
                                                                    <li><a href="#" onClick="return false"><?php echo $Customer; ?>
                                                                        <span class="pull-right badge bg-blue">
                                                                            <div id="custName"><?php echo $CUST_DESC; ?></div>
                                                                        </span>
                                                                        </a>
                                                                    </li>
                                                                    <li style="display: none;"><a href="#" onClick="return false"><?php echo $ItemCode; ?>
                                                                        <span class="pull-right badge bg-aqua">
                                                                            <div id="itemCode"><?php echo "$ITM_CODE - $QRC_PATT"; ?></div>
                                                                        </span>
                                                                        </a>
                                                                    </li>
                                                                    <li><a href="#" onClick="return false">SO
                                                                        <span class="pull-right badge bg-green">
                                                                            <div id="SOCode"><?php echo "$SO_CODE / $SO_DATEV"; ?></div>
                                                                        </span>
                                                                        </a>
                                                                    </li>
                                                                    <li><a href="#" onClick="return false">JO
                                                                        <span class="pull-right badge bg-purple">
                                                                            <div id="JOCode"><?php echo "$JO_CODE / $JO_DATEV"; ?></div>
                                                                        </span>
                                                                        </a>
                                                                    </li>
                                                                    <li><a href="#" onClick="return false">Resep
                                                                        <span class="pull-right badge bg-red">
                                                                            <div id="BOMCode"><?php echo "$BOM_CODE / $BOM_NAME"; ?></div>
                                                                        </span>
                                                                        </a>
                                                                    </li>
                                                                    <li><a href="#" onClick="return false"><?php echo $Description; ?></a>
                                                                    </li>
                                                                    <li><a href="#" onClick="return false">
                                                                        <div class="alert alert-info alert-dismissible">
                                                                            <p><?php echo $JO_DESC; ?></p>
                                                                        </div>
                                                                        </a>
                                                                    </li>
                                                                    <li style="display: none;"><a href="#" onClick="return false">Log Produksi</a>
                                                                    </li>
                                                                    <div id="detInf" class="widget-user-header bg-green" style="text-align:center">
                                                                        Produksi
                                                                    </div>
                                                                    <li>
                                                                        <a href="#" onClick="return false">
                                                                            <div>
                                                                                <?php
                                                                                    // TOTAL SO PRODSUCTION
                                                                                        $TOT_SOVOLM;

                                                                                    $sqlSTP = "SELECT DISTINCT A.JOSTF_STEP, 
                                                                                                    SUM(A.ITM_QTY) AS TOT_PLN,
                                                                                                    SUM(A.ITM_QTY_PROC) AS TOT_PROC, B.PRODS_NAME
                                                                                                FROM tbl_jo_stfdetail A
                                                                                                INNER JOIN tbl_prodstep B ON A.JOSTF_STEP = B.PRODS_STEP
                                                                                                WHERE A.PRJCODE = '$PRJCODE' 
                                                                                                    AND A.SO_NUM = '$SO_NUM'
                                                                                                    AND A.JOSTF_TYPE = 'OUT'
                                                                                                GROUP BY A.ITM_CODE
                                                                                                ORDER BY B.PRODS_ORDER ASC";
                                                                                    $resSTP = $this->db->query($sqlSTP)->result();
                                                                                    foreach ($resSTP as $key):
                                                                                        $STEP_CODE  = $key->JOSTF_STEP;
                                                                                        $TOT_PLN    = $key->TOT_PLN;
                                                                                        //$TOT_PROC = $key->TOT_PROC;
                                                                                        //$TOT_PERC = $TOT_PROC / $TOT_PLN * 100;
                                                                                        // TOTAL PRODUKSI PER STEP BY SO
                                                                                        $TOT_PLN    = $TOT_SOVOLM;
                                                                                        $TOT_PROD   = 0;
                                                                                        $TOT_PROP   = 0;
                                                                                        $sqlPROC    = "SELECT SUM(DISTINCT A.ITM_QTY) AS TOT_PROD,
                                                                                                             COUNT(A.ITM_QTY) AS TOT_PROP
                                                                                                        FROM tbl_jo_detail A
                                                                                                        INNER JOIN tbl_jo_header B ON A.JO_NUM = B.JO_NUM
                                                                                                        INNER JOIN tbl_stf_detail C ON A.JO_NUM = C.JO_NUM
                                                                                                        WHERE A.SO_NUM = '$SO_NUM' AND B.JO_STAT IN (3,6)
                                                                                                        AND C.STF_FROM = '$STEP_CODE' AND C.ITM_TYPE = 'OUT'";
                                                                                        $resPROC    = $this->db->query($sqlPROC)->result();
                                                                                        foreach ($resPROC as $keyPROC):
                                                                                            $TOT_PROD  = $keyPROC->TOT_PROD;
                                                                                            $TOT_PROP  = $keyPROC->TOT_PROP;
                                                                                        endforeach;

                                                                                        $TOT_PERC    = $TOT_PROD / $TOT_PLN * 100;
                                                                                        $PRODS_NAME  = $key->PRODS_NAME;
                                                                                        if($STEP_CODE == 'ONE')
                                                                                            $STEP_COL   = "red";
                                                                                        elseif($STEP_CODE == 'TWO')
                                                                                            $STEP_COL   = "yellow";
                                                                                        elseif($STEP_CODE == 'THR')
                                                                                            $STEP_COL   = "aqua";
                                                                                        elseif($STEP_CODE == 'FOU')
                                                                                            $STEP_COL   = "blue";
                                                                                        elseif($STEP_CODE == 'FIV')
                                                                                            $STEP_COL   = "purple";
                                                                                        elseif($STEP_CODE == 'SIX')
                                                                                            $STEP_COL   = "green";
                                                                                        elseif($STEP_CODE == 'SEV')
                                                                                            $STEP_COL   = "maroon";
                                                                                        elseif($STEP_CODE == 'NIN')
                                                                                            $STEP_COL   = "navy";
                                                                                        elseif($STEP_CODE == 'TEN')
                                                                                            $STEP_COL   = "orange";
                                                                                        else
                                                                                            $STEP_COL   = "red";
                                                                                        ?>
                                                                                        <div class="progress-group">
                                                                                            <span class="progress-text"><?php echo "$PRODS_NAME ($TOT_PROP)"; ?></span>
                                                                                            <span class="progress-number"><b><?php echo number_format($TOT_PROD,2); ?></b> / <?php echo number_format($TOT_PLN,2); ?> &nbsp; (<?php echo number_format($TOT_PERC,2); ?> %)</span>
                                                                                            <div class="progress sm">
                                                                                                <div class="progress-bar progress-bar-<?php echo $STEP_COL; ?>" style="width: <?php echo $TOT_PERC; ?>%"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php
                                                                                    endforeach;
                                                                                ?>
                                                                            </div>
                                                                        </a>
                                                                    </li>
                                                                <?php
                                                                }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>

<script>
	var decFormat		= 2;
	
	function doDecimalFormat(angka) {
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		else return (c + '.' + dec);
	}
	
	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
		
	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
		
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}

	function decimalin(ini)
	{	
		var i, j;
		var bil2 = deletecommaperiod(ini.value,'both')
		var bil3 = ""
		j = 0
		for (i=bil2.length-1;i>=0;i--)
		{
			j = j + 1;
			if (j == 3)
			{
				bil3 = "." + bil3
			}
			else if ((j >= 6) && ((j % 3) == 0))
			{
				bil3 = "," + bil3
			}
			bil3 = bil2.charAt(i) + "" + bil3
		}
		ini.value = bil3
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}
</script>
<?php
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
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