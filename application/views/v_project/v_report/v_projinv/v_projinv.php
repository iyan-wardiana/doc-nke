<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 Maret 2019
 * File Name	= v_projinv.php
 * Location		= -
*/

$this->load->view('template/head');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$Start_DateY 	= date('Y');
$Start_DateM 	= date('m');
$Start_DateD 	= date('d');
$Start_Date		= date('m/d/Y');	
$End_Date 		= date('m/d/Y');	

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$appBody    = $this->session->userdata('appBody');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers   = $this->session->userdata['vers'];

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
    		if($TranslCode == 'DisplayReport')$DisplayReport = $LangTransl;
    		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
    		if($TranslCode == 'Select')$Select = $LangTransl;
    		if($TranslCode == 'All')$All = $LangTransl;
    		if($TranslCode == 'ViewType')$ViewType = $LangTransl;
    		if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
    		if($TranslCode == 'Excel')$Excel = $LangTransl;
    		if($TranslCode == 'DateUntilto')$DateUntilto = $LangTransl;
    		if($TranslCode == 'ItemGroup')$ItemGroup = $LangTransl;
    	endforeach;
    	
    	if($LangID == 'IND')
    	{
    		$alert1	= "Silahkan pilih salah satu proyek.";
    		$alert2	= "Silahkan pilih salah satu grup item.";
    	}
    	else
    	{
    		$alert1	= "Please select a project.";
    		$alert2	= "Please select a item group.";
    	}

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo $mnName; ?>
                <small><?php //echo $h2_title; ?></small>
            </h1>
        </section>

        <section class="content">
            <div class="box box-primary">
                <div class="box-body chart-responsive">
                    <form class="form-horizontal" method="post" name="frm" id="frm" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this);">
        				<?php
                            $sqlPRJC	= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
                            $resPRJC 	= $this->db->count_all($sqlPRJC);
                        ?>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ProjectName; ?></label>
                            <div class="col-sm-10">
                                <select name="PRJCODE" id="PRJCODE" class="form-control select2" data-placeholder="&nbsp;<?php echo $ProjectName; ?>">
                                    <option value="0"> --- </option>
                                    <?php
                                    if($resPRJC>0)
                                    {
                                        $sqlPRJ		= "SELECT PRJCODE, PRJNAME FROM tbl_project 
        												WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj  WHERE Emp_ID = '$DefEmp_ID')
                                                        ORDER BY PRJNAME ASC";
                                        $resPRJ 	= $this->db->query($sqlPRJ)->result();
                                        foreach($resPRJ as $rowPRJ) :
                                            $PRJCODE1	= $rowPRJ->PRJCODE;
                                            $PRJNAME1	= $rowPRJ->PRJNAME;
                                            ?>
                                            <option value="<?php echo $PRJCODE1; ?>"> <?php echo "$PRJCODE1 - $PRJNAME1"; ?> </option>
                                            <?php
                                        endforeach;
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $DateUntilto; ?></label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="End_Date" class="form-control pull-left" id="datepicker" value="<?php echo $End_Date; ?>" style="width:106px">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ViewType; ?></label>
                            <div class="col-sm-10">
                                <label>
                                    <input type="radio" name="viewType" id="viewType" value="0" checked /> 
                                    <?php echo $WebViewer ?> 
                                    <input type="radio" name="viewType" id="viewType" value="1" /> 
                                    <?php echo $Excel ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-10">
                                <button class="btn btn-primary"><i class="cus-display-report-16x16"></i>&nbsp;&nbsp;<?php echo $DisplayReport; ?></button>
                            </div>
                        </div>
                    </form>
            	</div>
            </div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
        </section>
    </body>
</html>

<script>
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();

        //Date range picker
        $('#reservation').daterangepicker();
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
        //Date range as a button
    	
        //Date picker
        $('#datepicker').datepicker({
          autoclose: true
        });

        //Timepicker
        $(".timepicker").timepicker({
          showInputs: false
        });
    });
	
	function selPRJ(PRJCODE) 
	{
		PRJCODE	= document.getElementById("PRJCODE").value;
		document.getElementById("PRJCODE1").value = PRJCODE;
		document.frmsrch1.submitSrch1.click();
	}
	
	function target_popup(form)
	{
		var url = "<?php echo $form_action; ?>";
		
		PRJCODE = $('#PRJCODE').val();
		if(PRJCODE == 0)
		{
			swal("<?php echo $alert1; ?>",
            {
                icon:"warning",
            });
			$('#PRJCODE').focus();
			return false;
		}
		
		title = 'Select Item';
		w = 1300;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open('url', 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
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