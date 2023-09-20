<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Mei 2018
 * File Name	= joblistdet_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

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

$sqlJobC		= "tbl_joblist WHERE JOBLEV = 1 AND PRJCODE = '$PRJCODE' AND ISHEADER = '1'";
$resJobC		= $this->db->count_all($sqlJobC);
$RUNNO			= $resJobC+1;
$NEWCODE		= "$PRJCODE.$RUNNO";

$currentRow = 0;
if($task == 'add')
{
	$JOBCODEID 	= $NEWCODE;
	$JOBCODEIDV = '';
	$JOBPARENT 	= '';
	$PRJCODE	= $PRJCODE;
	$JOBCOD1 	= '';
	$JOBDESC 	= '';
	$JOBCLASS 	= '';
	$JOBGRP 	= '';
	$JOBTYPE 	= '';
	$JOBUNIT 	= '';
	$JOBLEV 	= 1;
	$JOBVOLM 	= 0;
	$PRICE 		= 0;
	$JOBCOST 	= 0;
	$ISLAST 	= 0;
	$ITM_NEED 	= 0;
	$ITM_GROUP	= 'S';
	$ISHEADER	= 1;
	$ITM_CODE	= '';
	$JOBLEV		= 0;
}
else
{
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
	$ISLAST 	= $default['ISLAST'];
	$ITM_NEED 	= $default['ITM_NEED'];
	$ITM_GROUP	= $default['ITM_GROUP'];
	$ISHEADER	= $default['ISHEADER'];
	$ITM_CODE	= $default['ITM_CODE'];
}

if($PRICE == 0)
{
	if($JOBVOLM == 0)
		$PRICE	= 0;
	else
		$PRICE	= $JOBCOST / $JOBVOLM;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers     = $this->session->userdata('vers');

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

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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

    <?php
        $this->load->view('template/mna');
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
    		if($TranslCode == 'Save')$Save = $LangTransl;
            if($TranslCode == 'Back')$Back = $LangTransl;
    		if($TranslCode == 'Update')$Update = $LangTransl;
    		if($TranslCode == 'Close')$Close = $LangTransl;
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Parent')$Parent = $LangTransl;
    		if($TranslCode == 'Unit')$Unit = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'JobDescription')$JobDescription = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'Price')$Price = $LangTransl;
    		if($TranslCode == 'Type')$Type = $LangTransl;
    		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
    		if($TranslCode == 'JobName')$JobName = $LangTransl;
    		if($TranslCode == 'JobLevel')$JobLevel = $LangTransl;
    		if($TranslCode == 'TopLevel')$TopLevel = $LangTransl;
    		if($TranslCode == 'Price')$Price = $LangTransl;
    	endforeach;
    	
    	if($LangID == 'IND')
    	{
    		$subTitleH	= "Tambah Pekerjaan";
    		$subTitleD	= "detail pekerjaan";
    		$Invoiced	= " sudah dibuatkan faktur";
    		$alert1		= "Deskripsi pekerjaan sudah diedit.";
    		$alert2		= "Deskripsi pekerjaan tidak boleh kosong.";
    		$alert3		= "Anda harus memilih relasi material.";
    		$miscell	= "Rupa-Rupa";
    	}
    	else
    	{
    		$subTitleH	= "Add Job";
    		$subTitleD	= "job detail";
    		$Invoiced	= " has already been created an invoice.";
    		$alert1		= "Job description updated.";
    		$alert2		= "Job description can not be empty.";
    		$alert3		= "You must select an item relation.";
    		$miscell	= "Miscellaneous";
    	}

        $JOBDESCD	= '';
        $sqlJD 		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
        $resJD 		= $this->db->query($sqlJD)->result();
        foreach($resJD as $rowJD) :
        	$JOBDESCD = $rowJD->JOBDESC;
        endforeach;

        $JOBDESCP	= '';
        $JOBDESC1	= '';
        $sqlJDP 	= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT'";
        $resJDP 	= $this->db->query($sqlJDP)->result();
        foreach($resJDP as $rowJDP) :
        	$JOBDESCP = $rowJDP->JOBDESC;
        endforeach;
        if($JOBPARENT != '')
        	$JOBDESC1	= "$JOBPARENT : $JOBDESCP";

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_req.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $subTitleH; ?>
                <small><?php echo $subTitleD; ?></small>  </h1>
        </section>

        <section class="content">
            <div class="box box-primary">
            	<div class="box-body chart-responsive">
                <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return submitForm()">
                    <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                    <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
                    <input type="Hidden" name="rowCount" id="rowCount" value="0">
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $JobLevel; ?></label>
                        <div class="col-sm-10">
                          <select name="JOBLEV" id="JOBLEV" class="form-control select2" onChange="chgLevel(this.value);">
                                <option value="">---</option>
                                <option value="1" <?php if($JOBLEV == 1) { ?> selected <?php } ?>><?php echo $TopLevel; ?></option>
                                <option value="2" <?php if($JOBLEV == 2) { ?> selected <?php } ?>>Level 2</option>
                                <option value="3" <?php if($JOBLEV == 3) { ?> selected <?php } ?>>Level 3</option>
                                <option value="4" <?php if($JOBLEV == 4) { ?> selected <?php } ?>>Level 4</option>
                                <option value="5" <?php if($JOBLEV == 5) { ?> selected <?php } ?>>Level 5</option>
                                <option value="6" <?php if($JOBLEV == 6) { ?> selected <?php } ?>>Level 6</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="display:none">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Type; ?></label>
                        <div class="col-sm-10">
                            <select name="ISHEADER" id="ISHEADER" class="form-control select2" style="max-width:150px" onChange="chgType(this.value);">
                                <option value="1" <?php if($ISHEADER == 1) { ?> selected <?php } ?>>Header</option>
                                <option value="0" <?php if($ISHEADER == 0) { ?> selected <?php } ?> disabled>Detail</option>
                            </select>
                        </div>
                    </div>
                  	<div class="form-group" id="showJobParent">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Parent; ?></label>
                        <div class="col-sm-10">
                            <select name="JOBPARENT" id="JOBPARENT" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobName; ?>">
                                <option value="">---</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" style="text-align:left" name="JOBCODEID" id="JOBCODEID" value="<?php echo $JOBCODEID; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label" style="vertical-align:top"><?php echo $Description; ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" style="text-align:left" name="JOBDESC" id="JOBDESC" value="<?php echo $JOBDESC; ?>" />
                        </div>
                    </div>
                    <div class="form-group" style="display:none">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Category; ?></label>
                        <div class="col-sm-10">
                            <select name="ITM_GROUP" id="ITM_GROUP" class="form-control">
                                    <option value="S" <?php if($ITM_GROUP == 'S') { ?> selected <?php } ?>><?php echo $JobName; ?></option>
                                    <option value="M" <?php if($ITM_GROUP == 'M') { ?> selected <?php } ?>>Material</option>
                                    <option value="U" <?php if($ITM_GROUP == 'U') { ?> selected <?php } ?>>Upah</option>
                                    <option value="SC" <?php if($ITM_GROUP == 'SC') { ?> selected <?php } ?>>Subkon</option>
                                    <option value="T" <?php if($ITM_GROUP == 'T') { ?> selected <?php } ?>>Alat</option>
                                    <option value="O" <?php if($ITM_GROUP == 'O') { ?> selected <?php } ?>>Overhead</option>
                                    <option value="I" <?php if($ITM_GROUP == 'I') { ?> selected <?php } ?>><?php echo $miscell; ?></option>
                                    <option value="GE" <?php if($ITM_GROUP == 'GE') { ?> selected <?php } ?>><?php echo $miscell; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Unit; ?></label>
                        <div class="col-sm-10">
                            <select name="JOBUNIT" id="JOBUNIT" class="form-control select2">
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
                        <label for="inputName" class="col-sm-2 control-label">Qty</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" style="text-align:right" name="JOBVOLM1" id="JOBVOLM1" value="<?php echo number_format($JOBVOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVolm(this);" disabled />
                            <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="JOBVOLM" id="JOBVOLM" value="<?php echo $JOBVOLM; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Price; ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" style="text-align:right" name="PRICE1" id="PRICE1" value="<?php echo number_format($PRICE, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrice(this);" disabled />
                            <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="PRICE" id="PRICE" value="<?php echo $PRICE; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-primary" >
                                <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
                            </button>&nbsp;
                            <?php
                                $backURL    = site_url('c_project/c_joblistdet/gl180c21JL/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                                    echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
                            ?>
                        </div>
                    </div>
                </form>
            	</div>
            </div>
        </section>
    </body>
</html>

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
	
	function chgLevel(thisValue)
	{
		if(thisValue == 1)
		{
			//document.getElementById('showITEM').style.display 	= 'none';
			document.getElementById('ISHEADER').style.display 		= 'none';
			document.getElementById('showJobParent').style.display 	= 'none';
		}
		else
		{
			//document.getElementById('showITEM').style.display 	= '';
			document.getElementById('ISHEADER').style.display 		= '';
			document.getElementById('showJobParent').style.display 	= '';
		}
	}
	
	function chgVolm(thisVal)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var JOBVOLM		= eval(document.getElementById('JOBVOLM1').value.split(",").join(""));
		
		document.getElementById('JOBVOLM').value = parseFloat(Math.abs(JOBVOLM));
		document.getElementById('JOBVOLM1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(JOBVOLM)),decFormat));
	}
	
	function chgPrice(thisVal)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var PRICE1	= eval(document.getElementById('PRICE1').value.split(",").join(""));
		
		document.getElementById('PRICE').value = parseFloat(Math.abs(PRICE1));
		document.getElementById('PRICE1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRICE1)),decFormat));
	}
	
	function chgType(isHeader)
	{
		if(isHeader == 1)
		{
			//document.getElementById('showITEM').style.display 		= 'none';
			document.getElementById('showJobParent').style.display 	= '';
		}
		else
		{
			//document.getElementById('showITEM').style.display 		= '';
			document.getElementById('showJobParent').style.display 	= '';
		}
	}
	
	$(document).ready(function()
	{
		$("#JOBLEV").change(function()
		{
			var PRJCODE		= document.getElementById("PRJCODE").value;
			var deptid1 	= $(this).val();
            alert(deptid1)
			var deptid		= deptid1+'~'+PRJCODE;
			if(deptid1 == 1)
			{
				document.getElementById("JOBCODEID").value	= '<?php echo $NEWCODE; ?>';
			}
			else
			{
				$.ajax({
					url: '<?php echo site_url('c_project/c_joblistdet/getJOBLIST/?id=')?>',
					type: 'post',
					data: {depart:deptid},
					dataType: 'json',
					success:function(response)
					{
						var len = response.length;
						
						$("#JOBPARENT").empty();
						for( var i = 0; i<len; i++){
							var JOBCODEID 	= response[i]['JOBCODEID'];
							var JOBDESC 	= response[i]['JOBDESC'];
							var ISDISABLED	= response[i]['ISDISABLED'];
							
							$("#JOBPARENT").append("<option value='"+JOBCODEID+"' "+ISDISABLED+">"+JOBDESC+"</option>");
						}
					}
				});
			}
		});
	
	});
	
	$(document).ready(function()
	{
		$("#JOBPARENT").change(function()
		{
			var PRJCODE		= document.getElementById("PRJCODE").value;
			var deptid1 	= $(this).val();
			var deptid		= deptid1+'~'+PRJCODE;
			
			$.ajax({
				url: '<?php echo site_url('c_project/c_joblistdet/getCODEJOBLIST/?id=')?>',
				type: 'post',
				data: {depart:deptid},
				dataType: 'json',
				success:function(response)
				{
					document.getElementById("JOBCODEID").value	= response;
				}
			});
		});
	
	});
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