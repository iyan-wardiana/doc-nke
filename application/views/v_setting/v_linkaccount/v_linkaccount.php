<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 November 2018
 * File Name	= v_linkaccount.php
 * Location		= -
*/
$this->load->view('template/head');

date_default_timezone_set("Asia/Jakarta");
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

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
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
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Translate')$Translate = $LangTransl;
		if($TranslCode == 'Setting')$Setting = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Zone')$Zone = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'ZonePerc')$ZonePerc = $LangTransl;
		if($TranslCode == 'Criteria')$Criteria = $LangTransl;
		if($TranslCode == 'Calculation')$Calculation = $LangTransl;
		if($TranslCode == 'Allowance')$Allowance = $LangTransl;
		if($TranslCode == 'MtrReceipt')$MtrReceipt = $LangTransl;
		if($TranslCode == 'PurchaseInv')$PurchaseInv = $LangTransl;
		if($TranslCode == 'PurchasePay')$PurchasePay = $LangTransl;
		if($TranslCode == 'SpecialAllowance')$SpecialAllowance = $LangTransl;
		if($TranslCode == 'Process')$Process = $LangTransl;
		if($TranslCode == 'LastUpdateEmp')$LastUpdateEmp = $LangTransl;
		if($TranslCode == 'Periode')$Periode = $LangTransl;
		if($TranslCode == 'Download')$Download = $LangTransl;
		if($TranslCode == 'ViewType')$ViewType = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$h1_title		= "Pengaturan Relasi Akun";
		$h2_title		= "Pengaturan";
	}
	else
	{
		$h1_title		= "Link Account Setting";
		$h2_title		= "Setting";
	}
	
	$genCode		= date('Ymd-His');
	
	$AMOUNT_1		= 0;
	$AMOUNT_2		= 0;
	$AMOUNT_3		= 0;
	$AMOUNT_4		= 0;
	
	$isLoadDone_1	= 1;
	$isLoadDone_2	= 1;
	$isLoadDone_3	= 1;
	$isLoadDone_4	= 1;
	
	$thisMonth		= date('m');
	if(isset($_POST['AMOUNT_1']))	// MARKETING
	{
		$AMOUNT_1		= $_POST['AMOUNT_1'];
				
		$PRJCATEG_1	= '';
		$sqlPRJ_1	= "SELECT PRJCODE, PRJ_CATEG, PRJ_CATEGV, PRJCOST FROM tbl_project WHERE PRJCODE = '$POSS_PRJCODE_1'";
		$resPRJ_1	= $this->db->query($sqlPRJ_1)->result();
		foreach($resPRJ_1 as $row_1):
			$PRJCATEG_1		= $row_1->PRJ_CATEG;
			$PRJCATEGV_1	= $row_1->PRJ_CATEGV;
		endforeach;
		
		$isLoadDone_1	= 1;
		$task			= 'Process';
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h1_title; ?>
    <small><?php echo $h2_title; ?></small>
  </h1>
  <br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->	
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
                    <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                	<form class="form-horizontal" name="form_date" id="form_date" method="post" action="" onSubmit="return chekData_date()">
                        <input type="hidden" class="form-control" name="ASUM_PERIOD_DEF" id="ASUM_PERIOD_DEF" style="max-width:100%; text-align:right" value="" />
					</form>
                	<form class="form-horizontal" name="form_1" method="post" action="" onSubmit="return chekData_1()">
                    	<div class="col-md-4">
                            <div class="box box-warning box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $MtrReceipt; ?></h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <table width="100%" border="0">
                                        <tr>
                                            <td colspan="2" style="text-align:right">
                                                <input type="hidden" name="LA_CATEG" class="form-control pull-left" id="LA_CATEG" value="IR" style="width:150px">
                                                <input type="text" name="LA_CATEG1" class="form-control pull-left" id="LA_CATEG1" value="<?php echo $MtrReceipt; ?>" disabled>
                                            </td>
                                        </tr>
                                        <?php
											$LA_IRD1	= '';
											$sqlIRD1	= "SELECT LA_ACCID FROM tbl_link_account 
															WHERE LA_CATEG = 'IR' AND LA_DK = 'D'";
											$resIRD1	= $this->db->query($sqlIRD1)->result();
											foreach($resIRD1 as $row_IRD1):
												$LA_IRD1	= $row_IRD1->LA_ACCID;
											endforeach;
											$LA_IRK1	= '';
											$sqlIRK1	= "SELECT LA_ACCID FROM tbl_link_account 
															WHERE LA_CATEG = 'IR' AND LA_DK = 'K'";
											$resIRK1	= $this->db->query($sqlIRK1)->result();
											foreach($resIRK1 as $row_IRK1):
												$LA_IRK1	= $row_IRK1->LA_ACCID;
											endforeach;
										?>
                                        <tr style="text-align:right">
                                            <td colspan="2" style="text-align:right">
                                                <select name="IR_D1" id="IR_D1" class="form-control" style="max-width:350px">
                                                    <option value="" > ---- None ----</option>
                                                    <?php	// Account_Category = '1'
                                                        foreach($vwAccount1 as $rowC0b) :
                                                            $Acc_ID0		= $rowC0b->Acc_ID;
                                                            $Account_Number0= $rowC0b->Account_Number;
                                                            $Acc_DirParent0	= $rowC0b->Acc_DirParent;
                                                            if($LangID == 'IND')
                                                            {
                                                                $Account_Name0	= $rowC0b->Account_NameId;
                                                            }
                                                            else
                                                            {
                                                                $Account_Name0	= $rowC0b->Account_NameEn;
                                                            }
                                                            
                                                            $Acc_ParentList0	= $rowC0b->Acc_ParentList;
                                                            
                                                            $resC1a		= 0;
                                                            $sqlC1a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number0' 
                                                                            AND Account_Level = '1' AND PRJCODE = '$PRJCODE' 
                                                                            AND Account_Category = '1'";
                                                            $resC1a 	= $this->db->count_all($sqlC1a);
                                                            
                                                            $collData0	= "$Account_Number0";
                                                            ?>
                                                            <option value="<?php echo $Account_Number0; ?>" <?php if($resC1a > 0) { ?> disabled <?php } if($Account_Number0 == $LA_IRD1) { ?> selected <?php } ?>><?php echo "$Account_Name0"; ?></option>
                                                            <?php
                                                            if($resC1a>0)
                                                            {
                                                                $sqlC1b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, 
                                                                                    Acc_ParentList
                                                                                FROM tbl_chartaccount 
                                                                                WHERE Acc_DirParent = '$Account_Number0'
                                                                                    AND Account_Level = '1' AND PRJCODE = '$PRJCODE'
                                                                                    AND Account_Category = '1'";
                                                                $resC1b 	= $this->db->query($sqlC1b)->result();
                                                                foreach($resC1b as $rowC1b) :
                                                                    $Acc_IIRD1		= $rowC1b->Acc_ID;
                                                                    $Account_Number1= $rowC1b->Account_Number;
                                                                    $Acc_DirParent1	= $rowC0b->Acc_DirParent;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name1	= $rowC1b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name1	= $rowC1b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList1	= $rowC1b->Acc_ParentList;
                                                                    $level_coa1			= "&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $resC2a		= 0;
                                                                    $sqlC2a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                                    AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
                                                                                    AND Account_Category = '1'";
                                                                    $resC2a 	= $this->db->count_all($sqlC2a);
                                                                    
                                                                    $collData1	= "$Account_Number1";
                                                                    ?>
                                                                    <option value="<?php echo $Account_Number1; ?>" <?php if($resC2a > 0) { ?> disabled <?php } if($Account_Number1 == $LA_IRD1) { ?> selected <?php } ?>><?php echo "$level_coa1$Account_Name1"; ?></option>
                                                                    <?php
                                                                    if($resC2a>0)
                                                                    {
                                                                        $sqlC2b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId,
                                                                                            Acc_ParentList
                                                                                        FROM tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                                            AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
                                                                                            AND Account_Category = '1'";                                                   						$resC2b 	= $this->db->query($sqlC2b)->result();
                                                                        foreach($resC2b as $rowC2b) :
                                                                            $Acc_ID2		= $rowC2b->Acc_ID;
                                                                            $Account_Number2= $rowC2b->Account_Number;
                                                                            $Acc_DirParent2	= $rowC0b->Acc_DirParent;
                                                                            if($LangID == 'IND')
                                                                            {
                                                                                $Account_Name2	= $rowC2b->Account_NameId;
                                                                            }
                                                                            else
                                                                            {
                                                                                $Account_Name2	= $rowC2b->Account_NameEn;
                                                                            }
                                                                            
                                                                            $Acc_ParentList2	= $rowC2b->Acc_ParentList;
                                                                            $level_coa2			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                            
                                                                            $resC3a		= 0;
                                                                            $sqlC3a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number2'
                                                                                            AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
                                                                                            AND Account_Category = '1'";
                                                                            $resC3a 	= $this->db->count_all($sqlC3a);
                                                                            
                                                                            $collData2	= "$Account_Number2";
                                                                            ?>
                                                                            <option value="<?php echo $Account_Number2; ?>" <?php if($resC3a > 0) { ?> disabled <?php } if($Account_Number2 == $LA_IRD1) { ?> selected <?php } ?>><?php echo "$level_coa2$Account_Name2"; ?></option>
                                                                            <?php
                                                                            if($resC3a>0)
                                                                            {
                                                                                $sqlC3b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
                                                                                                    Account_NameId, Acc_ParentList
                                                                                                FROM tbl_chartaccount 
                                                                                                WHERE Acc_DirParent = '$Account_Number2'
                                                                                                    AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
                                                                                                    AND Account_Category = '1'";
                                                                                $resC3b 	= $this->db->query($sqlC3b)->result();
                                                                                foreach($resC3b as $rowC3b) :
                                                                                    $Acc_ID3		= $rowC3b->Acc_ID;
                                                                                    $Account_Number3= $rowC3b->Account_Number;
                                                                                    $Acc_DirParent3	= $rowC0b->Acc_DirParent;
                                                                                    if($LangID == 'IND')
                                                                                    {
                                                                                        $Account_Name3	= $rowC3b->Account_NameId;
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        $Account_Name3	= $rowC3b->Account_NameEn;
                                                                                    }
                                                                                    
                                                                                    $Acc_ParentList3	= $rowC3b->Acc_ParentList;
                                                                                    $level_coa3			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                    $resC4a		= 0;
                                                                                    $sqlC4a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number3'
                                                                                                    AND Account_Level = '4' AND PRJCODE = '$PRJCODE'
                                                                                                    AND Account_Category = '1'";
                                                                                    $resC4a 	= $this->db->count_all($sqlC4a);
                                                                                    
                                                                                    $collData3	= "$Account_Number3";
                                                                                    ?>
                                                                                    <option value="<?php echo $Account_Number3; ?>" <?php if($resC4a > 0) { ?> disabled <?php } if($Account_Number3 == $LA_IRD1) { ?> selected <?php } ?>><?php echo "$level_coa3$Account_Name3"; ?></option>
                                                                                    <?php
                                                                                    if($resC4a>0)
                                                                                    {
                                                                                        $sqlC4b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
                                                                                                            Account_NameId, Acc_ParentList
                                                                                                        FROM tbl_chartaccount 
                                                                                                        WHERE Acc_DirParent = '$Account_Number3'
                                                                                                            AND Account_Level = '4' 
                                                                                                            AND PRJCODE = '$PRJCODE'
                                                                                                            AND Account_Category = '1'";
                                                                                        $resC4b 	= $this->db->query($sqlC4b)->result();
                                                                                        foreach($resC4b as $rowC4b) :
                                                                                            $Acc_ID4		= $rowC4b->Acc_ID;
                                                                                            $Account_Number4= $rowC4b->Account_Number;
                                                                                            $Acc_DirParent4	= $rowC0b->Acc_DirParent;
                                                                                            if($LangID == 'IND')
                                                                                            {
                                                                                                $Account_Name4	= $rowC4b->Account_NameId;
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                $Account_Name4	= $rowC4b->Account_NameEn;
                                                                                            }
                                                                                            
                                                                                            $Acc_ParentList4	= $rowC4b->Acc_ParentList;
                                                                                            $level_coa4			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                            $resC5a		= 0;
                                                                                            $sqlC5a		= "tbl_chartaccount WHERE
                                                                                                            Acc_DirParent = '$Account_Number4'
                                                                                                            AND Account_Level = '5'
                                                                                                            AND Account_Category = '1'";
                                                                                            $resC5a 	= $this->db->count_all($sqlC5a);
                                                                                            
                                                                                            $collData4	= "$Account_Number4";
                                                                                            ?>
                                                                                            <option value="<?php echo $Account_Number4; ?>" <?php if($resC5a > 0) { ?> disabled <?php } if($Account_Number4 == $LA_IRD1) { ?> selected <?php } ?>><?php echo "$level_coa4$Account_Name4"; ?></option>
                                                                                            <?php
                                                                                        endforeach;
                                                                                    }
                                                                                endforeach;
                                                                            }
                                                                        endforeach;
                                                                    }
                                                                endforeach;
                                                            }
                                                        endforeach;
                                                    ?>
                                                </select>
                                        	</td>
                                        </tr>
                                        <tr style="text-align:right">
                                            <td colspan="2" style="text-align:right">
                                                <select name="IR_D2" id="IR_D2" class="form-control" style="max-width:350px">
                                                    <option value="" > ---- None ----</option>
                                                </select>
                                        	</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr></td>
                                        </tr>
                                        <tr style="text-align:right">
                                            <td colspan="2" style="text-align:right">
                                                <select name="IR_K1" id="IR_K1" class="form-control" style="max-width:350px">
                                                    <option value="" > ---- None ----</option>
                                                    <?php	// Account_Category = '2'
                                                        foreach($vwAccount2 as $rowC0b) :
                                                            $Acc_ID0		= $rowC0b->Acc_ID;
                                                            $Account_Number0= $rowC0b->Account_Number;
                                                            $Acc_DirParent0	= $rowC0b->Acc_DirParent;
                                                            if($LangID == 'IND')
                                                            {
                                                                $Account_Name0	= $rowC0b->Account_NameId;
                                                            }
                                                            else
                                                            {
                                                                $Account_Name0	= $rowC0b->Account_NameEn;
                                                            }
                                                            
                                                            $Acc_ParentList0	= $rowC0b->Acc_ParentList;
                                                            
                                                            $resC1a		= 0;
                                                            $sqlC1a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number0' 
                                                                            AND Account_Level = '1' AND PRJCODE = '$PRJCODE' 
                                                                            AND Account_Category = '2'";
                                                            $resC1a 	= $this->db->count_all($sqlC1a);
                                                            
                                                            $collData0	= "$Account_Number0";
                                                            ?>
                                                            <option value="<?php echo $Account_Number0; ?>" <?php if($resC1a > 0) { ?> disabled <?php } if($Account_Number0 == $LA_IRK1) { ?> selected <?php } ?>><?php echo "$Account_Name0"; ?></option>
                                                            <?php
                                                            if($resC1a>0)
                                                            {
                                                                $sqlC1b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, 
                                                                                    Acc_ParentList
                                                                                FROM tbl_chartaccount 
                                                                                WHERE Acc_DirParent = '$Account_Number0'
                                                                                    AND Account_Level = '1' AND PRJCODE = '$PRJCODE'
                                                                                    AND Account_Category = '2'";
                                                                $resC1b 	= $this->db->query($sqlC1b)->result();
                                                                foreach($resC1b as $rowC1b) :
                                                                    $Acc_IIRK1		= $rowC1b->Acc_ID;
                                                                    $Account_Number1= $rowC1b->Account_Number;
                                                                    $Acc_DirParent1	= $rowC0b->Acc_DirParent;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name1	= $rowC1b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name1	= $rowC1b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList1	= $rowC1b->Acc_ParentList;
                                                                    $level_coa1			= "&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $resC2a		= 0;
                                                                    $sqlC2a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                                    AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
                                                                                    AND Account_Category = '2'";
                                                                    $resC2a 	= $this->db->count_all($sqlC2a);
                                                                    
                                                                    $collData1	= "$Account_Number1";
                                                                    ?>
                                                                    <option value="<?php echo $Account_Number1; ?>" <?php if($resC2a > 0) { ?> disabled <?php } if($Account_Number1 == $LA_IRK1) { ?> selected <?php } ?>><?php echo "$level_coa1$Account_Name1"; ?></option>
                                                                    <?php
                                                                    if($resC2a>0)
                                                                    {
                                                                        $sqlC2b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId,
                                                                                            Acc_ParentList
                                                                                        FROM tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                                            AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
                                                                                            AND Account_Category = '2'";                                                   						$resC2b 	= $this->db->query($sqlC2b)->result();
                                                                        foreach($resC2b as $rowC2b) :
                                                                            $Acc_ID2		= $rowC2b->Acc_ID;
                                                                            $Account_Number2= $rowC2b->Account_Number;
                                                                            $Acc_DirParent2	= $rowC0b->Acc_DirParent;
                                                                            if($LangID == 'IND')
                                                                            {
                                                                                $Account_Name2	= $rowC2b->Account_NameId;
                                                                            }
                                                                            else
                                                                            {
                                                                                $Account_Name2	= $rowC2b->Account_NameEn;
                                                                            }
                                                                            
                                                                            $Acc_ParentList2	= $rowC2b->Acc_ParentList;
                                                                            $level_coa2			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                            
                                                                            $resC3a		= 0;
                                                                            $sqlC3a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number2'
                                                                                            AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
                                                                                            AND Account_Category = '2'";
                                                                            $resC3a 	= $this->db->count_all($sqlC3a);
                                                                            
                                                                            $collData2	= "$Account_Number2";
                                                                            ?>
                                                                            <option value="<?php echo $Account_Number2; ?>" <?php if($resC3a > 0) { ?> disabled <?php } if($Account_Number2 == $LA_IRK1) { ?> selected <?php } ?>><?php echo "$level_coa2$Account_Name2"; ?></option>
                                                                            <?php
                                                                            if($resC3a>0)
                                                                            {
                                                                                $sqlC3b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
                                                                                                    Account_NameId, Acc_ParentList
                                                                                                FROM tbl_chartaccount 
                                                                                                WHERE Acc_DirParent = '$Account_Number2'
                                                                                                    AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
                                                                                                    AND Account_Category = '2'";
                                                                                $resC3b 	= $this->db->query($sqlC3b)->result();
                                                                                foreach($resC3b as $rowC3b) :
                                                                                    $Acc_ID3		= $rowC3b->Acc_ID;
                                                                                    $Account_Number3= $rowC3b->Account_Number;
                                                                                    $Acc_DirParent3	= $rowC0b->Acc_DirParent;
                                                                                    if($LangID == 'IND')
                                                                                    {
                                                                                        $Account_Name3	= $rowC3b->Account_NameId;
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        $Account_Name3	= $rowC3b->Account_NameEn;
                                                                                    }
                                                                                    
                                                                                    $Acc_ParentList3	= $rowC3b->Acc_ParentList;
                                                                                    $level_coa3			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                    $resC4a		= 0;
                                                                                    $sqlC4a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number3'
                                                                                                    AND Account_Level = '4' AND PRJCODE = '$PRJCODE'
                                                                                                    AND Account_Category = '2'";
                                                                                    $resC4a 	= $this->db->count_all($sqlC4a);
                                                                                    
                                                                                    $collData3	= "$Account_Number3";
                                                                                    ?>
                                                                                    <option value="<?php echo $Account_Number3; ?>" <?php if($resC4a > 0) { ?> disabled <?php } if($Account_Number3 == $LA_IRK1) { ?> selected <?php } ?>><?php echo "$level_coa3$Account_Name3"; ?></option>
                                                                                    <?php
                                                                                    if($resC4a>0)
                                                                                    {
                                                                                        $sqlC4b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
                                                                                                            Account_NameId, Acc_ParentList
                                                                                                        FROM tbl_chartaccount 
                                                                                                        WHERE Acc_DirParent = '$Account_Number3'
                                                                                                            AND Account_Level = '4' 
                                                                                                            AND PRJCODE = '$PRJCODE'
                                                                                                            AND Account_Category = '2'";
                                                                                        $resC4b 	= $this->db->query($sqlC4b)->result();
                                                                                        foreach($resC4b as $rowC4b) :
                                                                                            $Acc_ID4		= $rowC4b->Acc_ID;
                                                                                            $Account_Number4= $rowC4b->Account_Number;
                                                                                            $Acc_DirParent4	= $rowC0b->Acc_DirParent;
                                                                                            if($LangID == 'IND')
                                                                                            {
                                                                                                $Account_Name4	= $rowC4b->Account_NameId;
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                $Account_Name4	= $rowC4b->Account_NameEn;
                                                                                            }
                                                                                            
                                                                                            $Acc_ParentList4	= $rowC4b->Acc_ParentList;
                                                                                            $level_coa4			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                            $resC5a		= 0;
                                                                                            $sqlC5a		= "tbl_chartaccount WHERE
                                                                                                            Acc_DirParent = '$Account_Number4'
                                                                                                            AND Account_Level = '5'
                                                                                                            AND Account_Category = '2'";
                                                                                            $resC5a 	= $this->db->count_all($sqlC5a);
                                                                                            
                                                                                            $collData4	= "$Account_Number4";
                                                                                            ?>
                                                                                            <option value="<?php echo $Account_Number4; ?>" <?php if($resC5a > 0) { ?> disabled <?php } if($Account_Number4 == $LA_IRK1) { ?> selected <?php } ?>><?php echo "$level_coa4$Account_Name4"; ?></option>
                                                                                            <?php
                                                                                        endforeach;
                                                                                    }
                                                                                endforeach;
                                                                            }
                                                                        endforeach;
                                                                    }
                                                                endforeach;
                                                            }
                                                        endforeach;
                                                    ?>
                                                </select>
                                        	</td>
                                        </tr>
                                        <tr style="text-align:right">
                                            <td colspan="2" style="text-align:right">
                                                <select name="IR_K2" id="IR_K2" class="form-control" style="max-width:350px">
                                                    <option value="" > ---- None ----</option>
                                                </select>
                                        	</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="text-align:right">
                                            	<button class="btn btn-success">
                                                	<i class="glyphicon glyphicon-refresh"></i>&nbsp;&nbsp;<?php echo $Process; ?>
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="loading_1" class="overlay" <?php if($isLoadDone_1 == 1) { ?> style="display:none" <?php } ?>>
                                	<i class="fa fa-refresh fa-spin"></i>
                                </div>
                            </div>
                        </div>
					</form>
                	<form class="form-horizontal" name="form_2" method="post" action="" onSubmit="return chekData_2()">
                    	<div class="col-md-4">
                            <div class="box box-primary box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $PurchaseInv; ?></h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <table width="100%" border="0">
                                        <tr>
                                            <td colspan="2" style="text-align:right">
                                                <input type="hidden" name="LA_CATEG" class="form-control pull-left" id="LA_CATEG" value="PINV" style="width:150px">
                                                <input type="text" name="LA_CATEG1" class="form-control pull-left" id="LA_CATEG1" value="<?php echo $PurchaseInv; ?>" disabled>
                                            </td>
                                        </tr>
                                        <?php
											$LA_PINVD1	= '';
											$sqlPINVD1	= "SELECT LA_ACCID FROM tbl_link_account 
															WHERE LA_CATEG = 'PINV' AND LA_DK = 'D'";
											$resPINVD1	= $this->db->query($sqlPINVD1)->result();
											foreach($resPINVD1 as $row_PINVD1):
												$LA_PINVD1	= $row_PINVD1->LA_ACCID;
											endforeach;
											$LA_PINVK1	= '';
											$sqlPINVK1	= "SELECT LA_ACCID FROM tbl_link_account 
															WHERE LA_CATEG = 'PINV' AND LA_DK = 'K'";
											$resPINVK1	= $this->db->query($sqlPINVK1)->result();
											foreach($resPINVK1 as $row_PINVK1):
												$LA_PINVK1	= $row_PINVK1->LA_ACCID;
											endforeach;
										?>
                                        <tr style="text-align:right">
                                            <td colspan="2" style="text-align:right">
                                                <select name="PINV_D1" id="PINV_D1" class="form-control" style="max-width:350px">
                                                    <option value="" > ---- None ----</option>
                                                    <?php	// Account_Category = '2'
                                                        foreach($vwAccount2 as $rowC0b) :
                                                            $Acc_ID0		= $rowC0b->Acc_ID;
                                                            $Account_Number0= $rowC0b->Account_Number;
                                                            $Acc_DirParent0	= $rowC0b->Acc_DirParent;
                                                            if($LangID == 'IND')
                                                            {
                                                                $Account_Name0	= $rowC0b->Account_NameId;
                                                            }
                                                            else
                                                            {
                                                                $Account_Name0	= $rowC0b->Account_NameEn;
                                                            }
                                                            
                                                            $Acc_ParentList0	= $rowC0b->Acc_ParentList;
                                                            
                                                            $resC1a		= 0;
                                                            $sqlC1a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number0' 
                                                                            AND Account_Level = '1' AND PRJCODE = '$PRJCODE' 
                                                                            AND Account_Category = '2'";
                                                            $resC1a 	= $this->db->count_all($sqlC1a);
                                                            
                                                            $collData0	= "$Account_Number0";
                                                            ?>
                                                            <option value="<?php echo $Account_Number0; ?>" <?php if($resC1a > 0) { ?> disabled <?php } if($Account_Number0 == $LA_PINVD1) { ?> selected <?php } ?>><?php echo "$Account_Name0"; ?></option>
                                                            <?php
                                                            if($resC1a>0)
                                                            {
                                                                $sqlC1b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, 
                                                                                    Acc_ParentList
                                                                                FROM tbl_chartaccount 
                                                                                WHERE Acc_DirParent = '$Account_Number0'
                                                                                    AND Account_Level = '1' AND PRJCODE = '$PRJCODE'
                                                                                    AND Account_Category = '2'";
                                                                $resC1b 	= $this->db->query($sqlC1b)->result();
                                                                foreach($resC1b as $rowC1b) :
                                                                    $Acc_IPINVD1		= $rowC1b->Acc_ID;
                                                                    $Account_Number1= $rowC1b->Account_Number;
                                                                    $Acc_DirParent1	= $rowC0b->Acc_DirParent;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name1	= $rowC1b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name1	= $rowC1b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList1	= $rowC1b->Acc_ParentList;
                                                                    $level_coa1			= "&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $resC2a		= 0;
                                                                    $sqlC2a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                                    AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
                                                                                    AND Account_Category = '2'";
                                                                    $resC2a 	= $this->db->count_all($sqlC2a);
                                                                    
                                                                    $collData1	= "$Account_Number1";
                                                                    ?>
                                                                    <option value="<?php echo $Account_Number1; ?>" <?php if($resC2a > 0) { ?> disabled <?php } if($Account_Number1 == $LA_PINVD1) { ?> selected <?php } ?>><?php echo "$level_coa1$Account_Name1"; ?></option>
                                                                    <?php
                                                                    if($resC2a>0)
                                                                    {
                                                                        $sqlC2b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId,
                                                                                            Acc_ParentList
                                                                                        FROM tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                                            AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
                                                                                            AND Account_Category = '2'";                                                   						$resC2b 	= $this->db->query($sqlC2b)->result();
                                                                        foreach($resC2b as $rowC2b) :
                                                                            $Acc_ID2		= $rowC2b->Acc_ID;
                                                                            $Account_Number2= $rowC2b->Account_Number;
                                                                            $Acc_DirParent2	= $rowC0b->Acc_DirParent;
                                                                            if($LangID == 'IND')
                                                                            {
                                                                                $Account_Name2	= $rowC2b->Account_NameId;
                                                                            }
                                                                            else
                                                                            {
                                                                                $Account_Name2	= $rowC2b->Account_NameEn;
                                                                            }
                                                                            
                                                                            $Acc_ParentList2	= $rowC2b->Acc_ParentList;
                                                                            $level_coa2			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                            
                                                                            $resC3a		= 0;
                                                                            $sqlC3a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number2'
                                                                                            AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
                                                                                            AND Account_Category = '2'";
                                                                            $resC3a 	= $this->db->count_all($sqlC3a);
                                                                            
                                                                            $collData2	= "$Account_Number2";
                                                                            ?>
                                                                            <option value="<?php echo $Account_Number2; ?>" <?php if($resC3a > 0) { ?> disabled <?php } if($Account_Number2 == $LA_PINVD1) { ?> selected <?php } ?>><?php echo "$level_coa2$Account_Name2"; ?></option>
                                                                            <?php
                                                                            if($resC3a>0)
                                                                            {
                                                                                $sqlC3b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
                                                                                                    Account_NameId, Acc_ParentList
                                                                                                FROM tbl_chartaccount 
                                                                                                WHERE Acc_DirParent = '$Account_Number2'
                                                                                                    AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
                                                                                                    AND Account_Category = '2'";
                                                                                $resC3b 	= $this->db->query($sqlC3b)->result();
                                                                                foreach($resC3b as $rowC3b) :
                                                                                    $Acc_ID3		= $rowC3b->Acc_ID;
                                                                                    $Account_Number3= $rowC3b->Account_Number;
                                                                                    $Acc_DirParent3	= $rowC0b->Acc_DirParent;
                                                                                    if($LangID == 'IND')
                                                                                    {
                                                                                        $Account_Name3	= $rowC3b->Account_NameId;
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        $Account_Name3	= $rowC3b->Account_NameEn;
                                                                                    }
                                                                                    
                                                                                    $Acc_ParentList3	= $rowC3b->Acc_ParentList;
                                                                                    $level_coa3			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                    $resC4a		= 0;
                                                                                    $sqlC4a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number3'
                                                                                                    AND Account_Level = '4' AND PRJCODE = '$PRJCODE'
                                                                                                    AND Account_Category = '2'";
                                                                                    $resC4a 	= $this->db->count_all($sqlC4a);
                                                                                    
                                                                                    $collData3	= "$Account_Number3";
                                                                                    ?>
                                                                                    <option value="<?php echo $Account_Number3; ?>" <?php if($resC4a > 0) { ?> disabled <?php } if($Account_Number3 == $LA_PINVD1) { ?> selected <?php } ?>><?php echo "$level_coa3$Account_Name3"; ?></option>
                                                                                    <?php
                                                                                    if($resC4a>0)
                                                                                    {
                                                                                        $sqlC4b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
                                                                                                            Account_NameId, Acc_ParentList
                                                                                                        FROM tbl_chartaccount 
                                                                                                        WHERE Acc_DirParent = '$Account_Number3'
                                                                                                            AND Account_Level = '4' 
                                                                                                            AND PRJCODE = '$PRJCODE'
                                                                                                            AND Account_Category = '2'";
                                                                                        $resC4b 	= $this->db->query($sqlC4b)->result();
                                                                                        foreach($resC4b as $rowC4b) :
                                                                                            $Acc_ID4		= $rowC4b->Acc_ID;
                                                                                            $Account_Number4= $rowC4b->Account_Number;
                                                                                            $Acc_DirParent4	= $rowC0b->Acc_DirParent;
                                                                                            if($LangID == 'IND')
                                                                                            {
                                                                                                $Account_Name4	= $rowC4b->Account_NameId;
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                $Account_Name4	= $rowC4b->Account_NameEn;
                                                                                            }
                                                                                            
                                                                                            $Acc_ParentList4	= $rowC4b->Acc_ParentList;
                                                                                            $level_coa4			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                            $resC5a		= 0;
                                                                                            $sqlC5a		= "tbl_chartaccount WHERE
                                                                                                            Acc_DirParent = '$Account_Number4'
                                                                                                            AND Account_Level = '5'
                                                                                                            AND Account_Category = '2'";
                                                                                            $resC5a 	= $this->db->count_all($sqlC5a);
                                                                                            
                                                                                            $collData4	= "$Account_Number4";
                                                                                            ?>
                                                                                            <option value="<?php echo $Account_Number4; ?>" <?php if($resC5a > 0) { ?> disabled <?php } if($Account_Number4 == $LA_PINVD1) { ?> selected <?php } ?>><?php echo "$level_coa4$Account_Name4"; ?></option>
                                                                                            <?php
                                                                                        endforeach;
                                                                                    }
                                                                                endforeach;
                                                                            }
                                                                        endforeach;
                                                                    }
                                                                endforeach;
                                                            }
                                                        endforeach;
                                                    ?>
                                                </select>
                                        	</td>
                                        </tr>
                                        <tr style="text-align:right">
                                            <td colspan="2" style="text-align:right">
                                                <select name="PINV_D2" id="PINV_D2" class="form-control" style="max-width:350px">
                                                    <option value="" > ---- None ----</option>
                                                    <?php	// Account_Category = '2'
                                                        
                                                    ?>
                                                </select>
                                        	</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr></td>
                                        </tr>
                                        <tr style="text-align:right">
                                            <td colspan="2" style="text-align:right">
                                                <select name="PINV_K1" id="IR_K1" class="form-control" style="max-width:350px">
                                                    <option value="" > ---- None ----</option>
                                                    <?php	// Account_Category = '2'
                                                        foreach($vwAccount2 as $rowC0b) :
                                                            $Acc_ID0		= $rowC0b->Acc_ID;
                                                            $Account_Number0= $rowC0b->Account_Number;
                                                            $Acc_DirParent0	= $rowC0b->Acc_DirParent;
                                                            if($LangID == 'IND')
                                                            {
                                                                $Account_Name0	= $rowC0b->Account_NameId;
                                                            }
                                                            else
                                                            {
                                                                $Account_Name0	= $rowC0b->Account_NameEn;
                                                            }
                                                            
                                                            $Acc_ParentList0	= $rowC0b->Acc_ParentList;
                                                            
                                                            $resC1a		= 0;
                                                            $sqlC1a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number0' 
                                                                            AND Account_Level = '1' AND PRJCODE = '$PRJCODE' 
                                                                            AND Account_Category = '2'";
                                                            $resC1a 	= $this->db->count_all($sqlC1a);
                                                            
                                                            $collData0	= "$Account_Number0";
                                                            ?>
                                                            <option value="<?php echo $Account_Number0; ?>" <?php if($resC1a > 0) { ?> disabled <?php } if($Account_Number0 == $LA_PINVK1) { ?> selected <?php } ?>><?php echo "$Account_Name0"; ?></option>
                                                            <?php
                                                            if($resC1a>0)
                                                            {
                                                                $sqlC1b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, 
                                                                                    Acc_ParentList
                                                                                FROM tbl_chartaccount 
                                                                                WHERE Acc_DirParent = '$Account_Number0'
                                                                                    AND Account_Level = '1' AND PRJCODE = '$PRJCODE'
                                                                                    AND Account_Category = '2'";
                                                                $resC1b 	= $this->db->query($sqlC1b)->result();
                                                                foreach($resC1b as $rowC1b) :
                                                                    $Acc_IPINVD1		= $rowC1b->Acc_ID;
                                                                    $Account_Number1= $rowC1b->Account_Number;
                                                                    $Acc_DirParent1	= $rowC0b->Acc_DirParent;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name1	= $rowC1b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name1	= $rowC1b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList1	= $rowC1b->Acc_ParentList;
                                                                    $level_coa1			= "&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $resC2a		= 0;
                                                                    $sqlC2a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                                    AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
                                                                                    AND Account_Category = '2'";
                                                                    $resC2a 	= $this->db->count_all($sqlC2a);
                                                                    
                                                                    $collData1	= "$Account_Number1";
                                                                    ?>
                                                                    <option value="<?php echo $Account_Number1; ?>" <?php if($resC2a > 0) { ?> disabled <?php } if($Account_Number1 == $LA_PINVK1) { ?> selected <?php } ?>><?php echo "$level_coa1$Account_Name1"; ?></option>
                                                                    <?php
                                                                    if($resC2a>0)
                                                                    {
                                                                        $sqlC2b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId,
                                                                                            Acc_ParentList
                                                                                        FROM tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                                            AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
                                                                                            AND Account_Category = '2'";                                                   						$resC2b 	= $this->db->query($sqlC2b)->result();
                                                                        foreach($resC2b as $rowC2b) :
                                                                            $Acc_ID2		= $rowC2b->Acc_ID;
                                                                            $Account_Number2= $rowC2b->Account_Number;
                                                                            $Acc_DirParent2	= $rowC0b->Acc_DirParent;
                                                                            if($LangID == 'IND')
                                                                            {
                                                                                $Account_Name2	= $rowC2b->Account_NameId;
                                                                            }
                                                                            else
                                                                            {
                                                                                $Account_Name2	= $rowC2b->Account_NameEn;
                                                                            }
                                                                            
                                                                            $Acc_ParentList2	= $rowC2b->Acc_ParentList;
                                                                            $level_coa2			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                            
                                                                            $resC3a		= 0;
                                                                            $sqlC3a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number2'
                                                                                            AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
                                                                                            AND Account_Category = '2'";
                                                                            $resC3a 	= $this->db->count_all($sqlC3a);
                                                                            
                                                                            $collData2	= "$Account_Number2";
                                                                            ?>
                                                                            <option value="<?php echo $Account_Number2; ?>" <?php if($resC3a > 0) { ?> disabled <?php } if($Account_Number2 == $LA_PINVK1) { ?> selected <?php } ?>><?php echo "$level_coa2$Account_Name2"; ?></option>
                                                                            <?php
                                                                            if($resC3a>0)
                                                                            {
                                                                                $sqlC3b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
                                                                                                    Account_NameId, Acc_ParentList
                                                                                                FROM tbl_chartaccount 
                                                                                                WHERE Acc_DirParent = '$Account_Number2'
                                                                                                    AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
                                                                                                    AND Account_Category = '2'";
                                                                                $resC3b 	= $this->db->query($sqlC3b)->result();
                                                                                foreach($resC3b as $rowC3b) :
                                                                                    $Acc_ID3		= $rowC3b->Acc_ID;
                                                                                    $Account_Number3= $rowC3b->Account_Number;
                                                                                    $Acc_DirParent3	= $rowC0b->Acc_DirParent;
                                                                                    if($LangID == 'IND')
                                                                                    {
                                                                                        $Account_Name3	= $rowC3b->Account_NameId;
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        $Account_Name3	= $rowC3b->Account_NameEn;
                                                                                    }
                                                                                    
                                                                                    $Acc_ParentList3	= $rowC3b->Acc_ParentList;
                                                                                    $level_coa3			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                    $resC4a		= 0;
                                                                                    $sqlC4a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number3'
                                                                                                    AND Account_Level = '4' AND PRJCODE = '$PRJCODE'
                                                                                                    AND Account_Category = '2'";
                                                                                    $resC4a 	= $this->db->count_all($sqlC4a);
                                                                                    
                                                                                    $collData3	= "$Account_Number3";
                                                                                    ?>
                                                                                    <option value="<?php echo $Account_Number3; ?>" <?php if($resC4a > 0) { ?> disabled <?php } if($Account_Number3 == $LA_PINVK1) { ?> selected <?php } ?>><?php echo "$level_coa3$Account_Name3"; ?></option>
                                                                                    <?php
                                                                                    if($resC4a>0)
                                                                                    {
                                                                                        $sqlC4b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
                                                                                                            Account_NameId, Acc_ParentList
                                                                                                        FROM tbl_chartaccount 
                                                                                                        WHERE Acc_DirParent = '$Account_Number3'
                                                                                                            AND Account_Level = '4' 
                                                                                                            AND PRJCODE = '$PRJCODE'
                                                                                                            AND Account_Category = '2'";
                                                                                        $resC4b 	= $this->db->query($sqlC4b)->result();
                                                                                        foreach($resC4b as $rowC4b) :
                                                                                            $Acc_ID4		= $rowC4b->Acc_ID;
                                                                                            $Account_Number4= $rowC4b->Account_Number;
                                                                                            $Acc_DirParent4	= $rowC0b->Acc_DirParent;
                                                                                            if($LangID == 'IND')
                                                                                            {
                                                                                                $Account_Name4	= $rowC4b->Account_NameId;
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                $Account_Name4	= $rowC4b->Account_NameEn;
                                                                                            }
                                                                                            
                                                                                            $Acc_ParentList4	= $rowC4b->Acc_ParentList;
                                                                                            $level_coa4			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                            $resC5a		= 0;
                                                                                            $sqlC5a		= "tbl_chartaccount WHERE
                                                                                                            Acc_DirParent = '$Account_Number4'
                                                                                                            AND Account_Level = '5'
                                                                                                            AND Account_Category = '2'";
                                                                                            $resC5a 	= $this->db->count_all($sqlC5a);
                                                                                            
                                                                                            $collData4	= "$Account_Number4";
                                                                                            ?>
                                                                                            <option value="<?php echo $Account_Number4; ?>" <?php if($resC5a > 0) { ?> disabled <?php } if($Account_Number4 == $LA_PINVK1) { ?> selected <?php } ?>><?php echo "$level_coa4$Account_Name4"; ?></option>
                                                                                            <?php
                                                                                        endforeach;
                                                                                    }
                                                                                endforeach;
                                                                            }
                                                                        endforeach;
                                                                    }
                                                                endforeach;
                                                            }
                                                        endforeach;
                                                    ?>
                                                </select>
                                        	</td>
                                        </tr>
                                        <tr style="text-align:right">
                                            <td colspan="2" style="text-align:right">
                                                <select name="IR_K2" id="IR_K2" class="form-control" style="max-width:350px">
                                                    <option value="" > ---- None ----</option>
                                                    <?php	// Account_Category = '2'
                                                        foreach($vwAccount2 as $rowC0b) :
                                                            $Acc_ID0		= $rowC0b->Acc_ID;
                                                            $Account_Number0= $rowC0b->Account_Number;
                                                            $Acc_DirParent0	= $rowC0b->Acc_DirParent;
                                                            if($LangID == 'IND')
                                                            {
                                                                $Account_Name0	= $rowC0b->Account_NameId;
                                                            }
                                                            else
                                                            {
                                                                $Account_Name0	= $rowC0b->Account_NameEn;
                                                            }
                                                            
                                                            $Acc_ParentList0	= $rowC0b->Acc_ParentList;
                                                            
                                                            $resC1a		= 0;
                                                            $sqlC1a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number0' 
                                                                            AND Account_Level = '1' AND PRJCODE = '$PRJCODE' 
                                                                            AND Account_Category = '2'";
                                                            $resC1a 	= $this->db->count_all($sqlC1a);
                                                            
                                                            $collData0	= "$Account_Number0";
                                                            ?>
                                                            <option value="<?php echo $Account_Number0; ?>" <?php if($resC1a > 0) { ?> disabled <?php } if($Account_Number0 == $LA_PINVK1) { ?> selected <?php } ?>><?php echo "$Account_Name0"; ?></option>
                                                            <?php
                                                            if($resC1a>0)
                                                            {
                                                                $sqlC1b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, 
                                                                                    Acc_ParentList
                                                                                FROM tbl_chartaccount 
                                                                                WHERE Acc_DirParent = '$Account_Number0'
                                                                                    AND Account_Level = '1' AND PRJCODE = '$PRJCODE'
                                                                                    AND Account_Category = '2'";
                                                                $resC1b 	= $this->db->query($sqlC1b)->result();
                                                                foreach($resC1b as $rowC1b) :
                                                                    $Acc_IPINVD1		= $rowC1b->Acc_ID;
                                                                    $Account_Number1= $rowC1b->Account_Number;
                                                                    $Acc_DirParent1	= $rowC0b->Acc_DirParent;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name1	= $rowC1b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name1	= $rowC1b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList1	= $rowC1b->Acc_ParentList;
                                                                    $level_coa1			= "&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $resC2a		= 0;
                                                                    $sqlC2a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                                    AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
                                                                                    AND Account_Category = '2'";
                                                                    $resC2a 	= $this->db->count_all($sqlC2a);
                                                                    
                                                                    $collData1	= "$Account_Number1";
                                                                    ?>
                                                                    <option value="<?php echo $Account_Number1; ?>" <?php if($resC2a > 0) { ?> disabled <?php } if($Account_Number1 == $LA_PINVK1) { ?> selected <?php } ?>><?php echo "$level_coa1$Account_Name1"; ?></option>
                                                                    <?php
                                                                    if($resC2a>0)
                                                                    {
                                                                        $sqlC2b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId,
                                                                                            Acc_ParentList
                                                                                        FROM tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                                            AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
                                                                                            AND Account_Category = '2'";                                                   						$resC2b 	= $this->db->query($sqlC2b)->result();
                                                                        foreach($resC2b as $rowC2b) :
                                                                            $Acc_ID2		= $rowC2b->Acc_ID;
                                                                            $Account_Number2= $rowC2b->Account_Number;
                                                                            $Acc_DirParent2	= $rowC0b->Acc_DirParent;
                                                                            if($LangID == 'IND')
                                                                            {
                                                                                $Account_Name2	= $rowC2b->Account_NameId;
                                                                            }
                                                                            else
                                                                            {
                                                                                $Account_Name2	= $rowC2b->Account_NameEn;
                                                                            }
                                                                            
                                                                            $Acc_ParentList2	= $rowC2b->Acc_ParentList;
                                                                            $level_coa2			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                            
                                                                            $resC3a		= 0;
                                                                            $sqlC3a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number2'
                                                                                            AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
                                                                                            AND Account_Category = '2'";
                                                                            $resC3a 	= $this->db->count_all($sqlC3a);
                                                                            
                                                                            $collData2	= "$Account_Number2";
                                                                            ?>
                                                                            <option value="<?php echo $Account_Number2; ?>" <?php if($resC3a > 0) { ?> disabled <?php } if($Account_Number2 == $LA_PINVK1) { ?> selected <?php } ?>><?php echo "$level_coa2$Account_Name2"; ?></option>
                                                                            <?php
                                                                            if($resC3a>0)
                                                                            {
                                                                                $sqlC3b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
                                                                                                    Account_NameId, Acc_ParentList
                                                                                                FROM tbl_chartaccount 
                                                                                                WHERE Acc_DirParent = '$Account_Number2'
                                                                                                    AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
                                                                                                    AND Account_Category = '2'";
                                                                                $resC3b 	= $this->db->query($sqlC3b)->result();
                                                                                foreach($resC3b as $rowC3b) :
                                                                                    $Acc_ID3		= $rowC3b->Acc_ID;
                                                                                    $Account_Number3= $rowC3b->Account_Number;
                                                                                    $Acc_DirParent3	= $rowC0b->Acc_DirParent;
                                                                                    if($LangID == 'IND')
                                                                                    {
                                                                                        $Account_Name3	= $rowC3b->Account_NameId;
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        $Account_Name3	= $rowC3b->Account_NameEn;
                                                                                    }
                                                                                    
                                                                                    $Acc_ParentList3	= $rowC3b->Acc_ParentList;
                                                                                    $level_coa3			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                    $resC4a		= 0;
                                                                                    $sqlC4a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number3'
                                                                                                    AND Account_Level = '4' AND PRJCODE = '$PRJCODE'
                                                                                                    AND Account_Category = '2'";
                                                                                    $resC4a 	= $this->db->count_all($sqlC4a);
                                                                                    
                                                                                    $collData3	= "$Account_Number3";
                                                                                    ?>
                                                                                    <option value="<?php echo $Account_Number3; ?>" <?php if($resC4a > 0) { ?> disabled <?php } if($Account_Number3 == $LA_PINVK1) { ?> selected <?php } ?>><?php echo "$level_coa3$Account_Name3"; ?></option>
                                                                                    <?php
                                                                                    if($resC4a>0)
                                                                                    {
                                                                                        $sqlC4b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
                                                                                                            Account_NameId, Acc_ParentList
                                                                                                        FROM tbl_chartaccount 
                                                                                                        WHERE Acc_DirParent = '$Account_Number3'
                                                                                                            AND Account_Level = '4' 
                                                                                                            AND PRJCODE = '$PRJCODE'
                                                                                                            AND Account_Category = '2'";
                                                                                        $resC4b 	= $this->db->query($sqlC4b)->result();
                                                                                        foreach($resC4b as $rowC4b) :
                                                                                            $Acc_ID4		= $rowC4b->Acc_ID;
                                                                                            $Account_Number4= $rowC4b->Account_Number;
                                                                                            $Acc_DirParent4	= $rowC0b->Acc_DirParent;
                                                                                            if($LangID == 'IND')
                                                                                            {
                                                                                                $Account_Name4	= $rowC4b->Account_NameId;
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                $Account_Name4	= $rowC4b->Account_NameEn;
                                                                                            }
                                                                                            
                                                                                            $Acc_ParentList4	= $rowC4b->Acc_ParentList;
                                                                                            $level_coa4			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                            $resC5a		= 0;
                                                                                            $sqlC5a		= "tbl_chartaccount WHERE
                                                                                                            Acc_DirParent = '$Account_Number4'
                                                                                                            AND Account_Level = '5'
                                                                                                            AND Account_Category = '2'";
                                                                                            $resC5a 	= $this->db->count_all($sqlC5a);
                                                                                            
                                                                                            $collData4	= "$Account_Number4";
                                                                                            ?>
                                                                                            <option value="<?php echo $Account_Number4; ?>" <?php if($resC5a > 0) { ?> disabled <?php } if($Account_Number4 == $LA_PINVK1) { ?> selected <?php } ?>><?php echo "$level_coa4$Account_Name4"; ?></option>
                                                                                            <?php
                                                                                        endforeach;
                                                                                    }
                                                                                endforeach;
                                                                            }
                                                                        endforeach;
                                                                    }
                                                                endforeach;
                                                            }
                                                        endforeach;
                                                    ?>
                                                </select>
                                        	</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="text-align:right">
                                            	<button class="btn btn-success">
                                                	<i class="glyphicon glyphicon-refresh"></i>&nbsp;&nbsp;<?php echo $Process; ?>
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="loading_2" class="overlay" <?php if($isLoadDone_2 == 1) { ?> style="display:none" <?php } ?>>
                                	<i class="fa fa-refresh fa-spin"></i>
                                </div>
                            </div>
                        </div>
					</form>
                	<form class="form-horizontal" name="form_3" method="post" action="" onSubmit="return chekData_3()">
                    	<div class="col-md-4">
                            <div class="box box-success box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $PurchasePay; ?></h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <table width="100%" border="0">
                                        <tr>
                                            <td colspan="2" style="text-align:right">
                                                <input type="hidden" name="LA_CATEG" class="form-control pull-left" id="LA_CATEG" value="PINV" style="width:150px">
                                                <input type="text" name="LA_CATEG1" class="form-control pull-left" id="LA_CATEG1" value="<?php echo $PurchaseInv; ?>" disabled>
                                            </td>
                                        </tr>
                                        <?php
											$LA_PINVD1	= '';
											$sqlPINVD1	= "SELECT LA_ACCID FROM tbl_link_account 
															WHERE LA_CATEG = 'PINV' AND LA_DK = 'D'";
											$resPINVD1	= $this->db->query($sqlPINVD1)->result();
											foreach($resPINVD1 as $row_PINVD1):
												$LA_PINVD1	= $row_PINVD1->LA_ACCID;
											endforeach;
											$LA_PINVK1	= '';
											$sqlPINVK1	= "SELECT LA_ACCID FROM tbl_link_account 
															WHERE LA_CATEG = 'PINV' AND LA_DK = 'K'";
											$resPINVK1	= $this->db->query($sqlPINVK1)->result();
											foreach($resPINVK1 as $row_PINVK1):
												$LA_PINVK1	= $row_PINVK1->LA_ACCID;
											endforeach;
										?>
                                        <tr style="text-align:right">
                                            <td colspan="2" style="text-align:right">
                                                <select name="PINV_D1" id="PINV_D1" class="form-control" style="max-width:350px">
                                                    <option value="" > ---- None ----</option>
                                                    <?php	// Account_Category = '2'
                                                        foreach($vwAccount2 as $rowC0b) :
                                                            $Acc_ID0		= $rowC0b->Acc_ID;
                                                            $Account_Number0= $rowC0b->Account_Number;
                                                            $Acc_DirParent0	= $rowC0b->Acc_DirParent;
                                                            if($LangID == 'IND')
                                                            {
                                                                $Account_Name0	= $rowC0b->Account_NameId;
                                                            }
                                                            else
                                                            {
                                                                $Account_Name0	= $rowC0b->Account_NameEn;
                                                            }
                                                            
                                                            $Acc_ParentList0	= $rowC0b->Acc_ParentList;
                                                            
                                                            $resC1a		= 0;
                                                            $sqlC1a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number0' 
                                                                            AND Account_Level = '1' AND PRJCODE = '$PRJCODE' 
                                                                            AND Account_Category = '2'";
                                                            $resC1a 	= $this->db->count_all($sqlC1a);
                                                            
                                                            $collData0	= "$Account_Number0";
                                                            ?>
                                                            <option value="<?php echo $Account_Number0; ?>" <?php if($resC1a > 0) { ?> disabled <?php } if($Account_Number0 == $LA_PINVD1) { ?> selected <?php } ?>><?php echo "$Account_Name0"; ?></option>
                                                            <?php
                                                            if($resC1a>0)
                                                            {
                                                                $sqlC1b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, 
                                                                                    Acc_ParentList
                                                                                FROM tbl_chartaccount 
                                                                                WHERE Acc_DirParent = '$Account_Number0'
                                                                                    AND Account_Level = '1' AND PRJCODE = '$PRJCODE'
                                                                                    AND Account_Category = '2'";
                                                                $resC1b 	= $this->db->query($sqlC1b)->result();
                                                                foreach($resC1b as $rowC1b) :
                                                                    $Acc_IPINVD1		= $rowC1b->Acc_ID;
                                                                    $Account_Number1= $rowC1b->Account_Number;
                                                                    $Acc_DirParent1	= $rowC0b->Acc_DirParent;
                                                                    if($LangID == 'IND')
                                                                    {
                                                                        $Account_Name1	= $rowC1b->Account_NameId;
                                                                    }
                                                                    else
                                                                    {
                                                                        $Account_Name1	= $rowC1b->Account_NameEn;
                                                                    }
                                                                    
                                                                    $Acc_ParentList1	= $rowC1b->Acc_ParentList;
                                                                    $level_coa1			= "&nbsp;&nbsp;&nbsp;";
                                                                    
                                                                    $resC2a		= 0;
                                                                    $sqlC2a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                                    AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
                                                                                    AND Account_Category = '2'";
                                                                    $resC2a 	= $this->db->count_all($sqlC2a);
                                                                    
                                                                    $collData1	= "$Account_Number1";
                                                                    ?>
                                                                    <option value="<?php echo $Account_Number1; ?>" <?php if($resC2a > 0) { ?> disabled <?php } if($Account_Number1 == $LA_PINVD1) { ?> selected <?php } ?>><?php echo "$level_coa1$Account_Name1"; ?></option>
                                                                    <?php
                                                                    if($resC2a>0)
                                                                    {
                                                                        $sqlC2b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId,
                                                                                            Acc_ParentList
                                                                                        FROM tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                                            AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
                                                                                            AND Account_Category = '2'";                                                   						$resC2b 	= $this->db->query($sqlC2b)->result();
                                                                        foreach($resC2b as $rowC2b) :
                                                                            $Acc_ID2		= $rowC2b->Acc_ID;
                                                                            $Account_Number2= $rowC2b->Account_Number;
                                                                            $Acc_DirParent2	= $rowC0b->Acc_DirParent;
                                                                            if($LangID == 'IND')
                                                                            {
                                                                                $Account_Name2	= $rowC2b->Account_NameId;
                                                                            }
                                                                            else
                                                                            {
                                                                                $Account_Name2	= $rowC2b->Account_NameEn;
                                                                            }
                                                                            
                                                                            $Acc_ParentList2	= $rowC2b->Acc_ParentList;
                                                                            $level_coa2			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                            
                                                                            $resC3a		= 0;
                                                                            $sqlC3a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number2'
                                                                                            AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
                                                                                            AND Account_Category = '2'";
                                                                            $resC3a 	= $this->db->count_all($sqlC3a);
                                                                            
                                                                            $collData2	= "$Account_Number2";
                                                                            ?>
                                                                            <option value="<?php echo $Account_Number2; ?>" <?php if($resC3a > 0) { ?> disabled <?php } if($Account_Number2 == $LA_PINVD1) { ?> selected <?php } ?>><?php echo "$level_coa2$Account_Name2"; ?></option>
                                                                            <?php
                                                                            if($resC3a>0)
                                                                            {
                                                                                $sqlC3b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
                                                                                                    Account_NameId, Acc_ParentList
                                                                                                FROM tbl_chartaccount 
                                                                                                WHERE Acc_DirParent = '$Account_Number2'
                                                                                                    AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
                                                                                                    AND Account_Category = '2'";
                                                                                $resC3b 	= $this->db->query($sqlC3b)->result();
                                                                                foreach($resC3b as $rowC3b) :
                                                                                    $Acc_ID3		= $rowC3b->Acc_ID;
                                                                                    $Account_Number3= $rowC3b->Account_Number;
                                                                                    $Acc_DirParent3	= $rowC0b->Acc_DirParent;
                                                                                    if($LangID == 'IND')
                                                                                    {
                                                                                        $Account_Name3	= $rowC3b->Account_NameId;
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        $Account_Name3	= $rowC3b->Account_NameEn;
                                                                                    }
                                                                                    
                                                                                    $Acc_ParentList3	= $rowC3b->Acc_ParentList;
                                                                                    $level_coa3			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                    $resC4a		= 0;
                                                                                    $sqlC4a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number3'
                                                                                                    AND Account_Level = '4' AND PRJCODE = '$PRJCODE'
                                                                                                    AND Account_Category = '2'";
                                                                                    $resC4a 	= $this->db->count_all($sqlC4a);
                                                                                    
                                                                                    $collData3	= "$Account_Number3";
                                                                                    ?>
                                                                                    <option value="<?php echo $Account_Number3; ?>" <?php if($resC4a > 0) { ?> disabled <?php } if($Account_Number3 == $LA_PINVD1) { ?> selected <?php } ?>><?php echo "$level_coa3$Account_Name3"; ?></option>
                                                                                    <?php
                                                                                    if($resC4a>0)
                                                                                    {
                                                                                        $sqlC4b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
                                                                                                            Account_NameId, Acc_ParentList
                                                                                                        FROM tbl_chartaccount 
                                                                                                        WHERE Acc_DirParent = '$Account_Number3'
                                                                                                            AND Account_Level = '4' 
                                                                                                            AND PRJCODE = '$PRJCODE'
                                                                                                            AND Account_Category = '2'";
                                                                                        $resC4b 	= $this->db->query($sqlC4b)->result();
                                                                                        foreach($resC4b as $rowC4b) :
                                                                                            $Acc_ID4		= $rowC4b->Acc_ID;
                                                                                            $Account_Number4= $rowC4b->Account_Number;
                                                                                            $Acc_DirParent4	= $rowC0b->Acc_DirParent;
                                                                                            if($LangID == 'IND')
                                                                                            {
                                                                                                $Account_Name4	= $rowC4b->Account_NameId;
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                $Account_Name4	= $rowC4b->Account_NameEn;
                                                                                            }
                                                                                            
                                                                                            $Acc_ParentList4	= $rowC4b->Acc_ParentList;
                                                                                            $level_coa4			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                            $resC5a		= 0;
                                                                                            $sqlC5a		= "tbl_chartaccount WHERE
                                                                                                            Acc_DirParent = '$Account_Number4'
                                                                                                            AND Account_Level = '5'
                                                                                                            AND Account_Category = '2'";
                                                                                            $resC5a 	= $this->db->count_all($sqlC5a);
                                                                                            
                                                                                            $collData4	= "$Account_Number4";
                                                                                            ?>
                                                                                            <option value="<?php echo $Account_Number4; ?>" <?php if($resC5a > 0) { ?> disabled <?php } if($Account_Number4 == $LA_PINVD1) { ?> selected <?php } ?>><?php echo "$level_coa4$Account_Name4"; ?></option>
                                                                                            <?php
                                                                                        endforeach;
                                                                                    }
                                                                                endforeach;
                                                                            }
                                                                        endforeach;
                                                                    }
                                                                endforeach;
                                                            }
                                                        endforeach;
                                                    ?>
                                                </select>
                                        	</td>
                                        </tr>
                                        <tr style="text-align:right">
                                            <td colspan="2" style="text-align:right">
                                                <select name="PINV_D2" id="PINV_D2" class="form-control" style="max-width:350px">
                                                    <option value="" > ---- None ----</option>
                                                    <?php	// Account_Category = '2'
                                                        
                                                    ?>
                                                </select>
                                        	</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr></td>
                                        </tr>
                                        <tr style="text-align:right">
                                            <td colspan="2" style="text-align:right">
                                                <select name="PINV_K1" id="IR_K1" class="form-control" style="max-width:350px">
                                                    <option value="" > ---- None ----</option>
                                                </select>
                                        	</td>
                                        </tr>
                                        <tr style="text-align:right">
                                            <td colspan="2" style="text-align:right">
                                                <select name="IR_K2" id="IR_K2" class="form-control" style="max-width:350px">
                                                    <option value="" > ---- None ----</option>
                                                </select>
                                        	</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="text-align:right">
                                            	<button class="btn btn-success">
                                                	<i class="glyphicon glyphicon-refresh"></i>&nbsp;&nbsp;<?php echo $Process; ?>
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="loading_3" class="overlay" <?php if($isLoadDone_3 == 1) { ?> style="display:none" <?php } ?>>
                                	<i class="fa fa-refresh fa-spin"></i>
                                </div>
                            </div>
                        </div>
					</form>
                	<form class="form-horizontal" name="form_dl" id="form_dl" method="post" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this)" >
                    	<div class="col-md-4">
                            <div class="box box-info box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $Download; ?></h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <table width="100%" border="0">
                                        <tr>
                                            <td width="50%" ><?php //echo $LastUpdateEmp; ?></td>
                                            <td width="50%" style="text-align:right">
                                                    <?php //echo $UEMP_DATEP; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td nowrap style="text-align:left"><?php echo $Periode; ?></td>
                                            <td style="text-align:right">
                                            	<div class="input-group date">
                                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                                    <input type="text" name="REPORT_PERIOD" class="form-control pull-left" id="datepicker4" value="" style="width:150px" >
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td nowrap style="text-align:left"><?php //echo $ViewType; ?></td>
                                            <td style="text-align:right">
                                            	<select name="viewType" id="viewType" class="form-control">
                                                    <option value="">--- None ---</option>
                                                    <option value="0" >View Report</option>
                                                    <option value="1" >Download Excel</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td style="text-align:right">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td style="text-align:right">
                                                <button class="btn btn-info pull-right" style="margin-right: 5px;">
                                                    <i class="fa fa-download"></i> Submit
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
					</form>
                    <script>
						var url = "<?php echo $form_action; ?>";
						function target_popup(typeVal)
						{
							viewType	= document.getElementById('viewType').value;
							if(viewType == '')
							{
								alert('<?php echo $alert_6; ?>');
								return false;
							}
							form	= document.getElementById('form_dl');
							w = 900;
							h = 550;
							var left = (screen.width/2)-(w/2);
							var top = (screen.height/2)-(h/2);
							window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
							form.target = 'formpopup';
						}
						
						function getViewType(thisValue)
						{
							document.getElementById('viewType').value = thisValue;
							document.getElementById("form_dl").submit();
						}
					</script>
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

    //Date picker
    $('#datepicker4').datepicker({
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
	
	function chekData_1()
	{
		AMOUNT_1		= document.getElementById('AMOUNT_1').value;
		POSS_PRJCODE_1	= document.getElementById('POSS_PRJCODE_1').value;
		if(AMOUNT_1 == 0)
		{
			alert('<?php echo $alert_1; ?>');
			document.getElementById('AMOUNT_1x').focus();
			return false;			
		}
		
		if(POSS_PRJCODE_1 == '')
		{
			alert('<?php echo $alert_5; ?>');
			document.getElementById('POSS_PRJCODE_1').focus();
			return false;			
		}
		document.getElementById('loading_1').style.display = '';
	}
	
	function chekData_2()
	{
		AMOUNT_2		= document.getElementById('AMOUNT_2').value;
		POSS_PRJCODE_2	= document.getElementById('POSS_PRJCODE_2').value;
		if(AMOUNT_2 == 0)
		{
			alert('<?php echo $alert_2; ?>');
			document.getElementById('AMOUNT_2x').focus();
			return false;			
		}
		
		if(POSS_PRJCODE_2 == '')
		{
			alert('<?php echo $alert_5; ?>');
			document.getElementById('POSS_PRJCODE_2').focus();
			return false;			
		}
		document.getElementById('loading_2').style.display = '';
	}
	
	function chekData_3()
	{
		AMOUNT_3		= document.getElementById('AMOUNT_3').value;
		POSS_PRJCODE_3	= document.getElementById('POSS_PRJCODE_3').value;
		if(AMOUNT_3 == 0)
		{
			alert('<?php echo $alert_3; ?>');
			document.getElementById('AMOUNT_3x').focus();
			return false;			
		}
		
		if(POSS_PRJCODE_3 == '')
		{
			alert('<?php echo $alert_5; ?>');
			document.getElementById('POSS_PRJCODE_3').focus();
			return false;			
		}
		document.getElementById('loading_3').style.display = '';
	}
	
	function chekData_4()
	{
		document.getElementById('loading_4').style.display = '';
		AMOUNT_4	= document.getElementById('AMOUNT_4').value;
	}
	
	function getNewDate(thisValue)
	{
		document.getElementById('ASUM_PERIOD_DEF').value = thisValue;
		document.getElementById("form_date").submit();
	}
	
	function getNewDate1(thisValue)
	{
		RepType	= document.getElementById('viewType').value;
		document.getElementById('ASUM_PERIOD_DEF').value = thisValue;
		document.getElementById("form_date").submit();
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>