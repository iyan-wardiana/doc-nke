<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 Februari 2017
 * File Name	= globalsetting_form.php
 * Location		= -
*/
?>
<?php
// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;
 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

foreach($viewglobalsetting as $row) :
	$Display_Rows 	= $row->Display_Rows;
	$decFormat		= $row->decFormat;
	$currency_ID 	= $row->currency_ID;
	$purchasePrice 	= $row->purchasePrice;
	$salesPrice 	= $row->salesPrice; 
	$RateType_SO 	= $row->RateType_SO;
	$RateType_PO 	= $row->RateType_PO;
	$RateType_SN 	= $row->RateType_SN;
	$RateType_RR 	= $row->RateType_RR;
	$RateType_SI 	= $row->RateType_SI;
	$RateType_VI	= $row->RateType_VI;
	$RateType_GL	= $row->RateType_GL; 
	$recountType	= $row->recountType;
	$isUpdOutApp	= $row->isUpdOutApp;
	$isUpdProfLoss	= $row->isUpdProfLoss;
	$ACC_ID_IR		= $row->ACC_ID_IR;
	$ACC_ID_RDP		= $row->ACC_ID_RDP;
	$ACC_ID_RET		= $row->ACC_ID_RET;
	$ACC_ID_POT		= $row->ACC_ID_POT;
	$ACC_ID_MC		= $row->ACC_ID_MC;
	$RESET_JOURN	= $row->RESET_JOURN;
endforeach;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');
	
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
			
		if($TranslCode == 'Description')$Description = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$RecDP		= "Penerimaan DP";
		$RecItem	= "Penerimaan Item";
		$Disc		= "Potongan";
		$Retensi	= "Retensi";
		$Invoice	= "Faktur";
	}
	else
	{
		$RecDP		= "Receipt of Advances";
		$RecItem	= "Item Receipt";
		$Disc		= "Discount";
		$Retensi	= "Retention";
		$Invoice	= "Invoice";
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h1_title; ?>
    <small><?php echo $h2_title; ?></small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
    	<div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $h1_title; ?></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="box-body chart-responsive">
                            <form class="form-horizontal" name="absen_form" method="post" action="" onSubmit="return chekData()">
                                <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                                <input type="hidden" name="genSett" id="genSett" value="1" />
                                <input type="hidden" name="invDP" id="invDP" value="0" />
                                <input type="hidden" name="invMC" id="invDP" value="0" />
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"> Display of Row</label>
                                    <div class="col-sm-10">
                                        <label>
                                            <input type="hidden" maxlength="5" name="Display_Rows" id="Display_Rows" class="form-control" style="max-width:60px" value="<?php echo $Display_Rows; ?>">	
                                          <input type="text" maxlength="5" name="Display_Rows1" id="Display_Rows1" class="form-control" style="max-width:80px" value="<?php echo $Display_Rows; ?>" disabled>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Description; ?></label>
                                    <div class="col-sm-10">
                                        <select name="decFormat" id="decFormat" class="form-control select2" style="max-width:80px">
                                            <?php 
                                                for($idx=0;$idx<=4;$idx++)
                                                {
                                                    ?>
                                                        <option value="<?php echo $idx; ?>" <?php if($idx == $decFormat) { ?>selected<?php } ?>><?php echo $idx; ?></option>
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Currency</label>
                                    <div class="col-sm-10">
                                        <select name="currency_ID" id="currency_ID" class="form-control select2" style="max-width:80px">
                                            <?php 
                                                foreach($viewCurrency as $row) : ?>
                                                    <option value="<?php echo $row->CURR_ID; ?>" <?php if($row->CURR_ID == $currency_ID) { ?>selected<?php } ?>><?php echo $row->CURR_ID;?></option>
                                                <?php endforeach; ?>
                                         </select>
                                    </div>
                                </div>
                                <?php
                                    $PRJCODE	= 'KTR';
                                    $sqlPL 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 LIMIT 1";
                                    $resPL		= $this->db->query($sqlPL)->result();
                                    foreach($resPL as $rowPL1):
                                        $PRJCODE	= $rowPL1->PRJCODE;
                                    endforeach;
                                    
                                    $sqlC0a		= "tbl_chartaccount WHERE Account_Category IN (1,2,4) AND PRJCODE = '$PRJCODE'";
                                    $resC0a 	= $this->db->count_all($sqlC0a);
                                    
                                    $sqlC0b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, Acc_ParentList,
                                                        Acc_DirParent, isLast
                                                    FROM tbl_chartaccount WHERE Account_Category IN (1,2,4) AND PRJCODE = '$PRJCODE' ORDER BY ORD_ID ASC";
                                    $resC0b 	= $this->db->query($sqlC0b)->result();  
                                ?>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $RecDP; ?></label>
                                    <div class="col-sm-10">
                                        <select name="ACC_ID_RDP" id="ACC_ID_RDP" class="form-control select2">
                                            <option value="" > ---- None ----</option>
                                            <?php
                                            if($resC0a>0)
                                            {
                                                foreach($resC0b as $rowC0b) :
                                                    $Acc_ID0		= $rowC0b->Acc_ID;
                                                    $Account_Number0= $rowC0b->Account_Number;
                                                    $Acc_DirParent0	= $rowC0b->Acc_DirParent;
                                                    $Account_Level0	= $rowC0b->Account_Level;
                                                    if($LangID == 'IND')
                                                    {
                                                        $Account_Name0	= $rowC0b->Account_NameId;
                                                    }
                                                    else
                                                    {
                                                        $Account_Name0	= $rowC0b->Account_NameEn;
                                                    }
                                                    
                                                    $Acc_ParentList0	= $rowC0b->Acc_ParentList;
                                                    $isLast_0			= $rowC0b->isLast;
                                                    $disbaled_0			= 0;
                                                    if($isLast_0 == 0)
                                                        $disbaled_0		= 1;
                                                        
                                                    if($Account_Level0 == 0)
                                                        $level_coa1			= "";
                                                    elseif($Account_Level0 == 1)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 2)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 3)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 4)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 5)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 6)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 7)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    
                                                    $collData0	= "$Account_Number0";
                                                    ?>
                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_RDP) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                    <?php
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $RecItem; ?></label>
                                    <div class="col-sm-10">
                                        <select name="ACC_ID_IR" id="ACC_ID_IR" class="form-control select2">
                                            <option value="" > ---- None ----</option>
                                            <?php
                                            if($resC0a>0)
                                            {
                                                foreach($resC0b as $rowC0b) :
                                                    $Acc_ID0		= $rowC0b->Acc_ID;
                                                    $Account_Number0= $rowC0b->Account_Number;
                                                    $Acc_DirParent0	= $rowC0b->Acc_DirParent;
                                                    $Account_Level0	= $rowC0b->Account_Level;
                                                    if($LangID == 'IND')
                                                    {
                                                        $Account_Name0	= $rowC0b->Account_NameId;
                                                    }
                                                    else
                                                    {
                                                        $Account_Name0	= $rowC0b->Account_NameEn;
                                                    }
                                                    
                                                    $Acc_ParentList0	= $rowC0b->Acc_ParentList;
                                                    $isLast_0			= $rowC0b->isLast;
                                                    $disbaled_0			= 0;
                                                    if($isLast_0 == 0)
                                                        $disbaled_0		= 1;
                                                        
                                                    if($Account_Level0 == 0)
                                                        $level_coa1			= "";
                                                    elseif($Account_Level0 == 1)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 2)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 3)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 4)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 5)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 6)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 7)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    
                                                    $collData0	= "$Account_Number0";
                                                    ?>
                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_IR) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                    <?php
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Disc; ?></label>
                                    <div class="col-sm-10">
                                        <select name="ACC_ID_POT" id="ACC_ID_POT" class="form-control select2">
                                            <option value="" > ---- None ----</option>
                                            <?php
                                            if($resC0a>0)
                                            {
                                                foreach($resC0b as $rowC0b) :
                                                    $Acc_ID0		= $rowC0b->Acc_ID;
                                                    $Account_Number0= $rowC0b->Account_Number;
                                                    $Acc_DirParent0	= $rowC0b->Acc_DirParent;
                                                    $Account_Level0	= $rowC0b->Account_Level;
                                                    if($LangID == 'IND')
                                                    {
                                                        $Account_Name0	= $rowC0b->Account_NameId;
                                                    }
                                                    else
                                                    {
                                                        $Account_Name0	= $rowC0b->Account_NameEn;
                                                    }
                                                    
                                                    $Acc_ParentList0	= $rowC0b->Acc_ParentList;
                                                    $isLast_0			= $rowC0b->isLast;
                                                    $disbaled_0			= 0;
                                                    if($isLast_0 == 0)
                                                        $disbaled_0		= 1;
                                                        
                                                    if($Account_Level0 == 0)
                                                        $level_coa1			= "";
                                                    elseif($Account_Level0 == 1)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 2)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 3)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 4)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 5)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 6)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 7)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    
                                                    $collData0	= "$Account_Number0";
                                                    ?>
                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_POT) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                    <?php
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Retensi; ?></label>
                                    <div class="col-sm-10">
                                        <select name="ACC_ID_RET" id="ACC_ID_RET" class="form-control select2">
                                            <option value="" > ---- None ----</option>
                                            <?php
                                            if($resC0a>0)
                                            {
                                                foreach($resC0b as $rowC0b) :
                                                    $Acc_ID0		= $rowC0b->Acc_ID;
                                                    $Account_Number0= $rowC0b->Account_Number;
                                                    $Acc_DirParent0	= $rowC0b->Acc_DirParent;
                                                    $Account_Level0	= $rowC0b->Account_Level;
                                                    if($LangID == 'IND')
                                                    {
                                                        $Account_Name0	= $rowC0b->Account_NameId;
                                                    }
                                                    else
                                                    {
                                                        $Account_Name0	= $rowC0b->Account_NameEn;
                                                    }
                                                    
                                                    $Acc_ParentList0	= $rowC0b->Acc_ParentList;
                                                    $isLast_0			= $rowC0b->isLast;
                                                    $disbaled_0			= 0;
                                                    if($isLast_0 == 0)
                                                        $disbaled_0		= 1;
                                                        
                                                    if($Account_Level0 == 0)
                                                        $level_coa1			= "";
                                                    elseif($Account_Level0 == 1)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 2)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 3)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 4)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 5)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 6)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 7)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    
                                                    $collData0	= "$Account_Number0";
                                                    ?>
                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_RET) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                    <?php
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Invoice; ?> - MC</label>
                                    <div class="col-sm-10">
                                        <select name="ACC_ID_MC" id="ACC_ID_MC" class="form-control select2">
                                            <option value="" > ---- None ----</option>
                                            <?php
                                            if($resC0a>0)
                                            {
                                                foreach($resC0b as $rowC0b) :
                                                    $Acc_ID0		= $rowC0b->Acc_ID;
                                                    $Account_Number0= $rowC0b->Account_Number;
                                                    $Acc_DirParent0	= $rowC0b->Acc_DirParent;
                                                    $Account_Level0	= $rowC0b->Account_Level;
                                                    if($LangID == 'IND')
                                                    {
                                                        $Account_Name0	= $rowC0b->Account_NameId;
                                                    }
                                                    else
                                                    {
                                                        $Account_Name0	= $rowC0b->Account_NameEn;
                                                    }
                                                    
                                                    $Acc_ParentList0	= $rowC0b->Acc_ParentList;
                                                    $isLast_0			= $rowC0b->isLast;
                                                    $disbaled_0			= 0;
                                                    if($isLast_0 == 0)
                                                        $disbaled_0		= 1;
                                                        
                                                    if($Account_Level0 == 0)
                                                        $level_coa1			= "";
                                                    elseif($Account_Level0 == 1)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 2)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 3)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 4)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 5)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 6)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 7)
                                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    
                                                    $collData0	= "$Account_Number0";
                                                    ?>
                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $ACC_ID_MC) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                    <?php
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php
                                    $Emp_ID1	= '';
                                    $Emp_ID2	= '';
                                    $sqlMJREMP	= "SELECT * FROM tbl_major_app";
                                    $resMJREMP	= $this->db->query($sqlMJREMP)->result();
                                    foreach($resMJREMP as $rowMJR) :
                                        $Emp_ID1	= $rowMJR->Emp_ID1;
                                        $Emp_ID2	= $rowMJR->Emp_ID2;
                                    endforeach;
                                ?>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Reset Journal</label>
                                    <div class="col-sm-10">
                                        <select name="RESET_JOURN" id="RESET_JOURN" class="form-control select2" style="max-width:80px">
                                            <option value="0" <?php if($RESET_JOURN == 0) { ?> selected <?php } ?>> No </option>
                                            <option value="1" <?php if($RESET_JOURN == 1) { ?> selected <?php } ?> > Yes </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Penyetuju Khusus</label>
                                    <div class="col-sm-10">
                                        <select name="MJR_APP[]" id="MJR_APP" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;Project Manager">
                                            <?php
                                                /*$sqlEmp	= "SELECT Emp_ID, First_Name, Last_Name, Email
                                                            FROM tbl_employee WHERE Pos_Code LIKE 'PM%' ORDER BY First_Name";*/
                                                $sqlEmp	= "SELECT Emp_ID, First_Name, Last_Name, Email
                                                            FROM tbl_employee ORDER BY First_Name";
                                                $sqlEmp	= $this->db->query($sqlEmp)->result();
                                                foreach($sqlEmp as $row) :
                                                    $Emp_ID		= $row->Emp_ID;
                                                    $First_Name	= $row->First_Name;
                                                    $Last_Name	= $row->Last_Name;
                                                    $Email		= $row->Email;
                                                    ?>
                                                        <option value="<?php echo "$Emp_ID"; ?>" <?php if($Emp_ID1 == $Emp_ID) { ?> selected <?php } ?>>
                                                            <?php echo "$First_Name $Last_Name"; ?>
                                                        </option>
                                                    <?php
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label">Purchase Price</label>
                                    <div class="col-sm-10">
                                        <input name="purchasePrice" type="radio" value="0" <?php if($purchasePrice == 0) { ?> checked <?php } ?>> Fixed
                                        <input name="purchasePrice" type="radio" value="1" <?php if($purchasePrice == 1) { ?> checked <?php } ?>> Editable 
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label">Sales Price</label>
                                    <div class="col-sm-10">
                                        <input name="salesPrice" type="radio" value="0" <?php if($salesPrice == 0) { ?> checked <?php } ?>> Fixed
                                        <input name="salesPrice" type="radio" value="1" <?php if($salesPrice == 1) { ?> checked <?php } ?>> Editable
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label">Rate Sales</label>
                                    <div class="col-sm-10">
                                        <input name="RateType_SO" type="radio" value="0" <?php if($RateType_SO == 0) { ?> checked <?php } ?>> Fixed
                                        <input name="RateType_SO" type="radio" value="1" <?php if($RateType_SO == 1) { ?> checked <?php } ?>> Editable
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label">Rate Purchase Invoice</label>
                                    <div class="col-sm-10">
                                        <input name="RateType_VI" type="radio" value="0" <?php if($RateType_VI == 0) { ?> checked <?php } ?>> Fixed 
                                        <input name="RateType_VI" type="radio" value="1" <?php if($RateType_VI == 1) { ?> checked <?php } ?>> Editable
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label">Rate Sales Invoice</label>
                                    <div class="col-sm-10">
                                        <input name="RateType_SI" type="radio" value="0" <?php if($RateType_SI == 0) { ?> checked <?php } ?>> Fixed
                                        <input name="RateType_SI" type="radio" value="1" <?php if($RateType_SI == 1) { ?> checked <?php } ?>> Editable
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label">Rate Journal</label>
                                    <div class="col-sm-10">
                                        <input name="RateType_GL" type="radio" value="0" <?php if($RateType_GL == 0) { ?> checked <?php } ?>> Fixed
                                        <input name="RateType_GL" type="radio" value="1" <?php if($RateType_GL == 1) { ?> checked <?php } ?>> Editable
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label">Recount Type</label>
                                    <div class="col-sm-10">
                                        <select name="recountType" id="recountType" class="form-control" style="max-width:80px">
                                            <option value="AVG" <?php if($recountType == "AVG") { ?> selected <?php } ?>>AVG</option>
                                            <option value="FIFO" <?php if($recountType == "FIFO") { ?> selected <?php } ?> >FIFO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label">Upd. Outst. Approv</label>
                                    <div class="col-sm-10">
                                        <input name="isUpdOutApp" type="radio" value="1" <?php if($isUpdOutApp == 1) { ?> checked <?php } ?>>Yes
                                        <input name="isUpdOutApp" type="radio" value="0" <?php if($isUpdOutApp == 0) { ?> checked <?php } ?>>No
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label">Upd. L/R Report</label>
                                    <div class="col-sm-10">
                                        <input name="isUpdProfLoss" type="radio" value="1" <?php if($isUpdProfLoss == 1) { ?> checked <?php } ?>>Yes
                                        <input name="isUpdProfLoss" type="radio" value="0" <?php if($isUpdProfLoss == 0) { ?> checked <?php } ?>>No
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button class="btn btn-primary" ><i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo "Update"; ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    	</div>
    </div>
</section>
</body>

</html>

<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>

<!-- Select2 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js'; ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.date.extensions.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.extensions.js'; ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.js'; ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js'; ?>"></script>
<!-- Page script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker3').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>

<script>
	var decFormat		= 2;
	
	function doDecimalFormat(angka) 
	{
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
		
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>