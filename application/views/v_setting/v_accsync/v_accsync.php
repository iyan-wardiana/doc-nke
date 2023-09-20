<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 29 September 2018
 * File Name	= v_reset_project.php
 * Location		= -
*/

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
	
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
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
<style type="text/css">
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
	
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>
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
			
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'Type')$Type = $LangTransl;
		if($TranslCode == 'Process')$Process = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$alert1	= "Silahkan pilih salah satu proyek.";
		$alert2	= "Silahkan pilih akun yang akan disinkron.";
		$alert3	= "Silahkan pilih proyek yang akan disinkron.";
		$alert4	= "Anda yakin untuk update siknronisasi akun?";
	}
	else
	{
		$alert1	= "Please select a project.";
		$alert2	= "Please select one or more account(s).";
		$alert3	= "Please select one or more project(s).";
		$alert4	= "Are you sure want to update Account Syncronization?";
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h1_title; ?>
    <small><?php echo $h2_title; ?></small>
  </h1>
</section>
<!-- Main content -->
<section class="content">
    <div class="box box-primary">
    	<div class="box-body chart-responsive">
        	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkForm()">
				<?php
                    $sqlCOA 	= "SELECT Account_Number, Account_NameEn FROM tbl_chartaccount
                                        WHERE isHO = 1
                                        ORDER BY Account_Number, Account_NameEn ASC";
                    $resCOA 	= $this->db->query($sqlCOA)->result();
                    
                    $sqlPRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project
                                        ORDER BY PRJNAME ASC";
                    $resPRJ 	= $this->db->query($sqlPRJ)->result();
                    
                    
                    $sqlC0a		= "tbl_chartaccount WHERE Account_Level = '0' AND  isHO = 1";
                    $resC0a 	= $this->db->count_all($sqlC0a);
                    
                    $sqlC0b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, Acc_ParentList,
                                        Acc_DirParent, (Base_Debet + Base_Kredit) AS TRX0
                                    FROM tbl_chartaccount WHERE Account_Level = '0' AND  isHO = 1";
                    $resC0b 	= $this->db->query($sqlC0b)->result();
                ?>
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
                    <div class="col-sm-10">
                        <select name="PRJCODE" id="PRJCODE" class="form-control select2" style="max-width:500px;">
                        	<option value=""> --- </option>
                          	<?php
                            foreach($resPRJ as $row) :
                                $PRJCODE1 	= $row->PRJCODE;
                                $PRJNAME 	= $row->PRJNAME;
                                ?>
                              <option value="<?php echo $PRJCODE1; ?>"><?php echo "$PRJCODE1 - $PRJNAME"; ?></option>
                              <?php
                            endforeach;
                            ?>
                        </select>                   
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Akun</label>
                    <div class="col-sm-10">
                        <select multiple="multiple" name="packACC[]" id="packACC" size="10" style="max-height:150px; width:500px" ondblclick="MoveOption(this.form.packACC, this.form.pavailable)">
                            <?php
                            if($resC0a>0)
                            {
                                foreach($resC0b as $rowC0b) :
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
                                    $isLast_0			= $rowC0b->isLast;
                                    $disbaled_0			= 0;
                                    if($isLast_0 == 0)
                                        $disbaled_0		= 1;
                            
                                    $sqlC1a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number0' 
                                                    AND Account_Level = '1' AND isHO = 1";
                                    $resC1a 	= $this->db->count_all($sqlC1a);
                                    
                                    $collData0	= "$Account_Number0";
                                ?>
                                <option value="<?php echo $Account_Number0; ?>" <?php if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$Account_Name0 - $collData0"; ?></option>
                                <?php
                                if($resC1a>0)
                                {
                                    $sqlC1b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, Account_NameId, 
                                                        Acc_ParentList, isLast
                                                    FROM tbl_chartaccount 
                                                    WHERE Acc_DirParent = '$Account_Number0'
                                                        AND Account_Level = '1' AND isHO = 1";
                                    $resC1b 	= $this->db->query($sqlC1b)->result();
                                    foreach($resC1b as $rowC1b) :
                                        $Acc_ID1		= $rowC1b->Acc_ID;
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
                                        $isLast_1			= $rowC1b->isLast;
                                        $disbaled_1			= 0;
                                        if($isLast_1 == 0)
                                            $disbaled_1		= 1;
                                    
                                        $level_coa1			= "&nbsp;&nbsp;&nbsp;";
                                
                                        $sqlC2a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                        AND Account_Level = '2' AND isHO = 1";
                                        $resC2a 	= $this->db->count_all($sqlC2a);
                                        
                                        $collData1	= "$Account_Number1";
                                        ?>
                                        <option value="<?php echo $Account_Number1; ?>" <?php if($disbaled_1 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$Account_Name1 - $collData1"; ?></option>
                                        <?php
                                        if($resC2a>0)
                                        {
                                            $sqlC2b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, Account_NameId,
                                                                Acc_ParentList, isLast
                                                            FROM tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                AND Account_Level = '2' AND isHO = 1";                                                    $resC2b 	= $this->db->query($sqlC2b)->result();
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
                                                $isLast_2			= $rowC2b->isLast;
                                                $disbaled_2			= 0;
                                                if($isLast_2 == 0)
                                                    $disbaled_2		= 1;
                                                
                                                $level_coa2			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                        
                                                $sqlC3a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number2'
                                                                AND Account_Level = '3' AND isHO = 1";
                                                $resC3a 	= $this->db->count_all($sqlC3a);
                                                
                                                $collData2	= "$Account_Number2";
                                                ?>
                                                <option value="<?php echo $Account_Number2; ?>" <?php if($disbaled_2 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa2$Account_Name2 - $collData2"; ?></option>
                                                <?php
                                                if($resC3a>0)
                                                {
                                                    $sqlC3b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, 
                                                                        Account_NameId, Acc_ParentList, isLast
                                                                    FROM tbl_chartaccount 
                                                                    WHERE Acc_DirParent = '$Account_Number2'
                                                                        AND Account_Level = '3' AND isHO = 1";
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
                                                        $isLast_3			= $rowC3b->isLast;
                                                        $disbaled_3			= 0;
                                                        if($isLast_3 == 0)
                                                            $disbaled_3		= 1;
                                                        
                                                        $level_coa3			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                
                                                        $sqlC4a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number3'
                                                                        AND Account_Level = '4' AND isHO = 1";
                                                        $resC4a 	= $this->db->count_all($sqlC4a);
                                                        
                                                        $collData3	= "$Account_Number3";
                                                        ?>
                                                        <option value="<?php echo $Account_Number3; ?>" <?php if($disbaled_3 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa3$Account_Name3 - $collData3"; ?></option>
                                                        <?php
                                                        if($resC4a>0)
                                                        {
                                                            $sqlC4b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_NameEn, 
                                                                                Account_NameId, Acc_ParentList, isLast
                                                                            FROM tbl_chartaccount 
                                                                            WHERE Acc_DirParent = '$Account_Number3'
                                                                                AND Account_Level = '4'
                                                                                AND Account_Category = '1' AND isHO = 1";
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
                                                                $isLast_4			= $rowC4b->isLast;
                                                                $disbaled_4			= 0;
                                                                if($isLast_4 == 0)
                                                                    $disbaled_4		= 1;
                                                                
                                                                $level_coa4			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                        
                                                                $sqlC5a		= "tbl_chartaccount WHERE
                                                                                Acc_DirParent = '$Account_Number4'
                                                                                AND Account_Category = '1' AND isHO = 1";
                                                                $resC5a 	= $this->db->count_all($sqlC5a);
                                                                
                                                                $collData4	= "$Account_Number4";
                                                                ?>
                                                                <option value="<?php echo $Account_Number4; ?>" <?php if($disbaled_4 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa4$Account_Name4 - $collData4"; ?></option>
                                                                <?php
                                                                if($resC5a>0)
                                                                {
                                                                    $sqlC5b		= "SELECT DISTINCT Acc_ID, Account_Number, 
                                                                                        Account_NameEn, 
                                                                                        Account_NameId, Acc_ParentList, isLast
                                                                                    FROM tbl_chartaccount 
                                                                                    WHERE Acc_DirParent = '$Account_Number4'
                                                                                        AND Account_Level = '5'
                                                                                        AND Account_Category = '1' AND isHO = 1";
                                                                    $resC5b 	= $this->db->query($sqlC5b)->result();
                                                                    foreach($resC5b as $rowC5b) :
                                                                        $Acc_ID5		= $rowC5b->Acc_ID;
                                                                        $Account_Number5= $rowC5b->Account_Number;
                                                                        $Acc_DirParent5	= $rowC5b->Acc_DirParent;
                                                                        if($LangID == 'IND')
                                                                        {
                                                                            $Account_Name5	= $rowC5b->Account_NameId;
                                                                        }
                                                                        else
                                                                        {
                                                                            $Account_Name5	= $rowC5b->Account_NameEn;
                                                                        }
                                                                        
                                                                        $Acc_ParentList5	= $rowC5b->Acc_ParentList;
                                                                        $isLast_5			= $rowC5b->isLast;
                                                                        $disbaled_5			= 0;
                                                                        if($isLast_5 == 0)
                                                                            $disbaled_5		= 1;
                                                                        
                                                                        $level_coa5			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                
                                                                        $sqlC6a		= "tbl_chartaccount WHERE
                                                                                        Acc_DirParent = '$Account_Number5'
                                                                                        AND Account_Category = '1' AND isHO = 1";
                                                                        $resC6a 	= $this->db->count_all($sqlC5a);
                                                                        
                                                                        $collData5	= "$Account_Number5";
                                                                        ?>
                                                                        <option value="<?php echo $Account_Number5; ?>" <?php if($disbaled_5 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa5$Account_Name5 - $collData5"; ?></option>
                                                                        
                                                                        <?php
                                                                        if($resC6a>0)
                                                                        {
                                                                            $sqlC6b	= "SELECT DISTINCT Acc_ID, 
                                                                                        Account_Number, Account_NameEn, 
                                                                                        Account_NameId, Acc_ParentList, 
                                                                                        isLast
                                                                                        FROM tbl_chartaccount 
                                                                                        WHERE Acc_DirParent = '$Account_Number5'
                                                                                        AND Account_Level = '6'
                                                                                        AND Account_Category = 1 AND isHO = 1";
                                                                            $resC6b 	= $this->db->query($sqlC6b)->result();
                                                                            foreach($resC6b as $rowC6b) :
                                                                                $Acc_ID6		= $rowC6b->Acc_ID;
                                                                                $Account_Number6= $rowC6b->Account_Number;
                                                                                $Acc_DirParent6	= $rowC6b->Acc_DirParent;
                                                                                if($LangID == 'IND')
                                                                                {
                                                                                    $Account_Name6	= $rowC6b->Account_NameId;
                                                                                }
                                                                                else
                                                                                {
                                                                                    $Account_Name6	= $rowC6b->Account_NameEn;
                                                                                }
                                                                                
                                                                                $Acc_ParentList6	= $rowC6b->Acc_ParentList;
                                                                                $isLast_6			= $rowC6b->isLast;
                                                                                $disbaled_6			= 0;
                                                                                if($isLast_6 == 0)
                                                                                    $disbaled_6		= 1;
                                                                                
                                                                                $level_coa6			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                        
                                                                                $sqlC7a		= "tbl_chartaccount WHERE
                                                                                                Acc_DirParent = '$Account_Number5'";
                                                                                $resC7a 	= $this->db->count_all($sqlC7a);
                                                                                
                                                                                $collData6	= "$Account_Number5";
                                                                                ?>
                                                                                <option value="<?php echo $Account_Number6; ?>" <?php if($disbaled_6 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa6$Account_Name6 - $collData6"; ?></option>
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
                                        }
                                    endforeach;
                                }
                                endforeach;
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="getPRJ">
                    <label class="col-sm-2 control-label">Proyek</label>
                    <div class="col-sm-10">
                        <select multiple="multiple" name="packPRJ[]" id="packPRJ" size="10" style="max-height:150px; width:500px" ondblclick="MoveOption(this.form.packPRJ, this.form.pavailable)">
                            <?php
                                foreach($resPRJ as $rowPRJ) :
                                    $PRJCODE2 	= $rowPRJ->PRJCODE;
                                    $PRJNAME2	= $rowPRJ->PRJNAME;
                                    ?>
                                        <option value="<?php echo $PRJCODE2; ?>"><?php echo "$PRJCODE2 - $PRJNAME2";?></option>
                                    <?php
                                endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <!--<input type="submit" value="Upload File" class="btn btn-warning" style="width:120px;" />&nbsp;&nbsp;-->
                        <button class="btn btn-primary"><i class="glyphicon glyphicon-refresh"></i>&nbsp;&nbsp;<?php echo "$Update"; ?></button>
                    </div>
                </div>
			</form>
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
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>

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
<!-- bootstrap color picker -->
<!-- bootstrap time picker -->
<!-- SlimScroll 1.3.0 -->
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
	$('#datepicker').datepicker({
	  autoclose: true
	});
	
	//Date picker
	$('#datepicker1').datepicker({
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
	
	function checkForm()
	{
		PRJCODE	= document.getElementById('PRJCODE').value;
		if(PRJCODE == '')
		{
			alert('<?php echo $alert1; ?>');
			document.getElementById('PRJCODE').focus();
			return false;
		}
		
		packACC	= document.getElementById('packACC').value;
		if(packACC == '')
		{
			alert('<?php echo $alert2; ?>');
			document.getElementById('packACC').focus();
			return false;
		}
		
		packPRJ	= document.getElementById('packPRJ').value;
		if(packPRJ == '')
		{
			alert('<?php echo $alert3; ?>');
			document.getElementById('packPRJ').focus();
			return false;
		}
		
		
		result = confirm("<?php echo $alert4; ?>");
		if (!result)
		{
			return false;
		}
		
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>