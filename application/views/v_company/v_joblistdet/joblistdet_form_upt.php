<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Mei 2018
 * File Name	= joblistdet_form.php
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

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$JOBCODEID 	= $default['JOBCODEID'];
$JOBCODEIDV = $default['JOBCODEIDV'];
$JOBPARENT 	= $default['JOBPARENT'];
$PRJCODE	= $default['PRJCODE'];
$PRJCODE 	= $default['PRJCODE'];
$JOBCOD1 	= $default['JOBCOD1'];
$JOBDESC 	= $default['JOBDESC'];
$JOBCLASS 	= $default['JOBCLASS'];
$JOBGRP 	= $default['JOBGRP'];
$JOBTYPE 	= $default['JOBTYPE'];
$JOBUNIT 	= $default['JOBUNIT'];
$JOBLEV 	= $default['JOBLEV'];
$JOBVOLM 	= $default['JOBVOLM'];
$PRICE 		= $default['PRICE'];
$JOBCOST 	= $default['JOBCOST'];

if($PRICE == 0)
{
	if($JOBVOLM == 0)
		$PRICE	= 0;
	else
		$PRICE	= $JOBCOST / $JOBVOLM;
}
$ISLAST 	= $default['ISLAST'];
$ITM_NEED 	= $default['ITM_NEED'];
$ITM_GROUP	= $default['ITM_GROUP'];
$ISHEADER	= $default['ISHEADER'];
$ITM_CODE	= $default['ITM_CODE'];
?>
<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?></title>
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
	//$this->load->view('template/topbar');
	//$this->load->view('template/sidebar');
	
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
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'JobCode')$JobCode = $LangTransl;
		if($TranslCode == 'JobParent')$JobParent = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'JobDescription')$JobDescription = $LangTransl;
		if($TranslCode == 'Category')$Category = $LangTransl;
		if($TranslCode == 'Price')$Price = $LangTransl;
		if($TranslCode == 'Type')$Type = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'JobName')$JobName = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$subTitleH	= "Edit Pekerjaan";
		$subTitleD	= "detail pekerjaan";
		$Invoiced	= " sudah dibuatkan faktur";
		$alert1		= "Deskripsi pekerjaan sudah diedit.";
		$alert2		= "Deskripsi pekerjaan tidak boleh kosong.";
		$alert3		= "Anda harus memilih relasi material.";
		$miscell	= "Rupa-Rupa";
		$GenCost	= "Biaya Adm. Umum";
	}
	else
	{
		$subTitleH	= "Update Job";
		$subTitleD	= "job detail";
		$Invoiced	= " has already been created an invoice.";
		$alert1		= "Job description updated.";
		$alert2		= "Job description can not be empty.";
		$alert3		= "You must select an item relation.";
		$miscell	= "Miscellaneous";
		$GenCost	= "General Cost";
	}	
	
	$updated		= 0;
	if(isset($_POST['JOBCODEID']))
	{
		$JOBCODEID	= $_POST['JOBCODEID'];
		$JOBDESC	= $_POST['JOBDESC'];
		$JOBUNIT	= $_POST['JOBUNIT'];
		$ITM_GROUP	= $_POST['ITM_GROUP'];
		$ITM_PRICE	= $_POST['ITM_PRICE'];
		$ISHEADER	= $_POST['ISHEADER'];
		$ITM_CODE	= $_POST['ITM_CODE'];
		
		$sqlUPDJD	= "UPDATE tbl_joblist SET JOBDESC = '$JOBDESC', JOBGRP = '$ITM_GROUP', JOBUNIT = '$JOBUNIT', 
							PRICE = '$ITM_PRICE', ISHEADER = $ISHEADER
						WHERE JOBCODEID = '$JOBCODEID'";
		$this->db->query($sqlUPDJD);
		
		$sqlUPDJDD	= "UPDATE tbl_joblist_detail SET JOBDESC = '$JOBDESC', ITM_GROUP = '$ITM_GROUP', GROUP_CATEG = '$ITM_GROUP',
							ITM_UNIT = '$JOBUNIT', ITM_PRICE = '$ITM_PRICE', ITM_LASTP = '$ITM_PRICE'
						WHERE JOBCODEID = '$JOBCODEID'";
		$this->db->query($sqlUPDJDD);
		
		$sqlUPDBOQ	= "UPDATE tbl_boqlist SET JOBDESC = '$JOBDESC', JOBGRP = '$ITM_GROUP', JOBUNIT = '$JOBUNIT',
							PRICE = '$ITM_PRICE', ISHEADER = $ISHEADER
						WHERE JOBCODEID = '$JOBCODEID'";
		$this->db->query($sqlUPDBOQ);
		
		if($ITM_CODE	== '')
		{
			$sqlITM		= "SELECT ITM_CODE FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM) :
				$ITM_CODE	= $rowITM->ITM_CODE;
			endforeach;
		}
		
		if($ITM_CODE != '')
		{
			$sqlUITM	= "UPDATE tbl_item SET ITM_GROUP = '$ITM_GROUP', ITM_UNIT = '$JOBUNIT', UMCODE = '$JOBUNIT',
								ITM_PRICE = '$ITM_PRICE', ITM_LASTP = '$ITM_PRICE'
							WHERE ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUITM);
			
			$sqlUPD1	= "UPDATE tbl_joblist SET ITM_CODE = '$ITM_CODE' WHERE JOBCODEID = '$JOBCODEID'";
			$this->db->query($sqlUPD1);
			
			$sqlUPD2	= "UPDATE tbl_joblist_detail SET ITM_CODE = '$ITM_CODE' WHERE JOBCODEID = '$JOBCODEID'";
			$this->db->query($sqlUPD2);
			
			$sqlUPD2	= "UPDATE tbl_boqlist SET ITM_CODE = '$ITM_CODE' WHERE JOBCODEID = '$JOBCODEID'";
			$this->db->query($sqlUPD2);
		}
		$PRICE		= $ITM_PRICE;
		
		if($ISHEADER == 1)
		{
			$sqlUPD1	= "UPDATE tbl_joblist SET ITM_CODE = '' WHERE JOBCODEID = '$JOBCODEID'";
			$this->db->query($sqlUPD1);
			$sqlUPD2	= "UPDATE tbl_joblist_detail SET ITM_CODE = '' WHERE JOBCODEID = '$JOBCODEID'";
			$this->db->query($sqlUPD2);
			$sqlUPD2	= "UPDATE tbl_boqlist SET ITM_CODE = '' WHERE JOBCODEID = '$JOBCODEID'";
			$this->db->query($sqlUPD2);
		}
		$updated	= 1;
	}

$JOBDESCD	= '';
$sqlJD 		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
$resJD 		= $this->db->query($sqlJD)->result();
foreach($resJD as $rowJD) :
	$JOBDESCD = $rowJD->JOBDESC;
endforeach;

$JOBDESCP	= '';
$sqlJDP 	= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT'";
$resJDP 	= $this->db->query($sqlJDP)->result();
foreach($resJDP as $rowJDP) :
	$JOBDESCP = $rowJDP->JOBDESC;
endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <style type="text/css">
            .search-table, td, th {
                border-collapse: collapse;
            }
            .search-table-outter { overflow-x: scroll; }
            
            a[disabled="disabled"] {
                pointer-events: none;
            }
        </style>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="callout callout-success" style="vertical-align:top">
            <font size="+2"><?php echo $subTitleH; ?></font>
            <small><?php echo $subTitleD; ?></small>
        </div>
    
        <div class="box box-primary">
            <div class="box-body chart-responsive">
            	<form class="form-horizontal" name="frm" method="post" action="" enctype="multipart/form-data" onSubmit="return submitForm()">
                	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                	<input type="Hidden" name="rowCount" id="rowCount" value="0">
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label" style="vertical-align:top"><?php echo $JobParent; ?></label>
                        <div class="col-sm-10">
                            <textarea name="JOBDESC1" class="form-control" style="max-width:350px;" id="JOBDESC1" cols="30" readonly><?php echo "$JOBPARENT : $JOBDESCP"; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $JobCode; ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" style="max-width:180px;text-align:left" name="JOBCODEID1" id="JOBCODEID1" size="30" value="<?php echo $JOBCODEID; ?>" readonly />
                            <input type="hidden" class="form-control" style="max-width:180px;text-align:left" name="JOBCODEID" id="JOBCODEID" size="30" value="<?php echo $JOBCODEID; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Description; ?></label>
                        <div class="col-sm-10">
                            <textarea name="JOBDESC" class="form-control" style="max-width:350px;" id="JOBDESC" cols="30"><?php echo $JOBDESCD; ?></textarea>
                        </div>
                    </div>
                    <?php
						// CHECK EXIST TRANSACTION
						$countTRX	= 0;
						$sqlCHKTRX	= "SELECT A.JOBCODEID FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader A1 ON A.JournalH_Code = A1.JournalH_Code AND A1.GEJ_STAT IN (3,6)
											AND A.JOBCODEID = '$JOBCODEID'
										UNION
										SELECT B.JOBCODEID FROM tbl_woreq_detail B
											INNER JOIN tbl_woreq_header B1 ON B.WO_NUM = B1.WO_NUM AND B1.WO_STAT IN (3,6)
											AND B.JOBCODEID = '$JOBCODEID'
										UNION
										SELECT C.JOBCODEID FROM tbl_wo_detail C
											INNER JOIN tbl_wo_header C1 ON C.WO_NUM = C1.WO_NUM AND C1.WO_STAT IN (3,6)
											AND C.JOBCODEID = '$JOBCODEID'
										UNION
										SELECT D.JOBCODEID FROM tbl_pr_detail D
											INNER JOIN tbl_pr_header D1 ON D.PR_NUM = D1.PR_NUM AND D1.PR_STAT IN (3,6)
											AND D.JOBCODEID = '$JOBCODEID'";
						$resCHKTRX	= $this->db->query($sqlCHKTRX)->result();
						foreach($resCHKTRX as $rowTRX) :
							$countTRX	= $countTRX + 1;
							$JOBCODEID 	= $rowTRX->JOBCODEID;
						endforeach;									
					?>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Type; ?></label>
                        <div class="col-sm-10">
                        	<select name="ISHEADER" id="ISHEADER" class="form-control select2" style="max-width:100px" onChange="chgType(this.value);" <?php if($countTRX > 0) { ?> disabled <?php } ?>>
                                <option value="0" <?php if($ISHEADER == 0) { ?> selected <?php } ?>>Detail</option>
                                <option value="1" <?php if($ISHEADER == 1) { ?> selected <?php } ?>>Header</option>
                            </select>
                        </div>
                    </div>
                    <script>
						function chgType(thisValue)
						{
							if(thisValue == 0)
							{
								//document.getElementById('isheaderID').style.display = '';
								document.getElementById('ITM_CODE').disabled = false;
							}
							else
							{
								//document.getElementById('isheaderID').style.display = 'none';
								document.getElementById('ITM_CODE').disabled = true;
							}
						}
					</script>
                    <div id="isheaderID" class="form-group">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $ItemName; ?></label>
                        <div class="col-sm-10">
                            <select name="ITM_CODE" id="ITM_CODE" class="form-control select2" style="max-width:350px" <?php if($ISHEADER == 1) { ?> disabled <?php } ?>>
                                <option value="">None</option>
                                <?php
                                    $sqlITM1 	= "SELECT ITM_CODE, ITM_NAME FROM tbl_item WHERE PRJCODE = '$PRJCODE'";
                                    $resITM1 	= $this->db->query($sqlITM1)->result();
                                    foreach($resITM1 as $rowITM1) :
                                        $ITM_CODE1 	= $rowITM1->ITM_CODE;
                                        $ITM_NAME1	= $rowITM1->ITM_NAME;
                                        ?>
                                        <option value="<?php echo $ITM_CODE1; ?>" <?php if($ITM_CODE1 == $ITM_CODE) { ?> selected <?php } ?>><?php echo "$ITM_NAME1 - $ITM_CODE1"; ?></option>
                                        <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Category; ?></label>
                        <div class="col-sm-10">
                        	<select name="ITM_GROUP" id="ITM_GROUP" class="form-control select2" style="max-width:200px">
									<option value="S" <?php if($ITM_GROUP == 'S') { ?> selected <?php } ?>><?php echo $JobName; ?></option>
									<option value="M" <?php if($ITM_GROUP == 'M') { ?> selected <?php } ?>>Material</option>
									<option value="U" <?php if($ITM_GROUP == 'U') { ?> selected <?php } ?>>Upah</option>
									<option value="SC" <?php if($ITM_GROUP == 'SC') { ?> selected <?php } ?>>Subkon</option>
									<option value="T" <?php if($ITM_GROUP == 'T') { ?> selected <?php } ?>>Alat</option>
									<option value="O" <?php if($ITM_GROUP == 'O') { ?> selected <?php } ?>>Overhead</option>
									<option value="I" <?php if($ITM_GROUP == 'I') { ?> selected <?php } ?>><?php echo $miscell; ?></option>
									<option value="GE" <?php if($ITM_GROUP == 'GE') { ?> selected <?php } ?>><?php echo $GenCost; ?></option>
                                </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Unit; ?></label>
                        <div class="col-sm-10">
                        	<select name="JOBUNIT" id="JOBUNIT" class="form-control select2" style="max-width:100px">
                                <option value="0">None</option>
                                <?php
                                    $sqlUnit 	= "SELECT * FROM tbl_unittype";
                                    $resUnit 	= $this->db->query($sqlUnit)->result();
                                    foreach($resUnit as $rowUM) :
                                        $Unit_Type_Code = $rowUM->Unit_Type_Code;
                                        $UMCODE 		= $rowUM->UMCODE;
                                        $Unit_Type_Name	= $rowUM->Unit_Type_Name;
                                        ?>
                                        <option value="<?php echo $Unit_Type_Code; ?>" <?php if($UMCODE == $JOBUNIT) { ?> selected <?php } ?>><?php echo $Unit_Type_Name; ?></option>
                                        <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Unit; ?></label>
                        <div class="col-sm-10">
                        	<input type="text" class="form-control" style="max-width:150px;text-align:right" name="ITM_PRICE1" id="ITM_PRICE1" value="<?php echo number_format($PRICE, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this);" />
                        	<input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="ITM_PRICE" id="ITM_PRICE" value="<?php echo $PRICE; ?>" />
                        </div>
                   </div>
					<?php
                        if($updated == 1)
                        {
                            ?>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                <div class="col-sm-10">
                                    <div class="callout callout-info">
                                        <?php echo $alert1; ?>
                                    </div>           
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-primary" >
                                <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                            </button>
                            <button class="btn btn-danger" type="button" onClick="window.close()">
                                <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                            </button>
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
    $('#datepicker').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
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
	
	function chgPrice(thisVal)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var ITM_PRICE1	= eval(document.getElementById('ITM_PRICE1').value.split(",").join(""));
		
		document.getElementById('ITM_PRICE').value = parseFloat(Math.abs(ITM_PRICE1));
		document.getElementById('ITM_PRICE1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE1)),decFormat));
	}
	
	function submitForm(value)
	{
		var JOBDESC 	= document.getElementById('JOBDESC').value;
		var ISHEADER 	= document.getElementById('ISHEADER').value;
		
		if(JOBDESC == '')
		{
			alert('<?php echo $alert2; ?>');
			document.getElementById('JOBDESC').focus();
			return false;
		}
		
		if(ISHEADER == 0)
		{
			ITM_CODE 	= document.getElementById('ITM_CODE').value;
			if(ITM_CODE == '')
			{
				alert('<?php echo $alert3; ?>');
				document.getElementById('ITM_CODE').focus();
				return false;
			}
		}
	}
	
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
	
	var selectedRows = 0;
	function check_all(chk) 
	{
		var totRow = document.getElementById('totalrow').value;
		if(chk.checked == true)
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = true;
			}
		}
		else
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = false;
			}
		}
	}
	
	function RoundNDecimal(X, N)
	{
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
</script>