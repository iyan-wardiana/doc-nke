<?php
/* 
    * Author		= Dian Hermanto
    * Create Date	= 30 Oktober 2020
    * File Name	    = v_hpp_report.php
    * Location		= -
*/
 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

    $this->db->select('Display_Rows,decFormat');
    $resGlobal = $this->db->get('tglobalsetting')->result();
    foreach($resGlobal as $row) :
    	$Display_Rows = $row->Display_Rows;
    	$decFormat = $row->decFormat;
    endforeach;
    $decFormat		= 2;

    $DefEmp_ID 		= $this->session->userdata['Emp_ID'];
    		
    $today			= date("Y-m-d");
    $lastdateMonth	= date('Y-m-t', strtotime($today));
    $LastMonth		= date('m', strtotime($lastdateMonth));
    $LastDate		= date('d', strtotime($lastdateMonth));

    $Start_DateY 	= date('Y');
    $Start_DateM 	= date('m');
    $Start_DateD 	= date('d');

    if($Start_DateD <= $LastDate)
    {
    	//$befDM		= date('Y-m-d', strtotime($today . '- 1 month'));
    	$befDM		= date('Y-m-d', strtotime($today));
    	$lastbefDM	= date('Y-m-t', strtotime($befDM));
    	$befDMY 	= date('Y', strtotime($lastbefDM));
    	$befDMM 	= date('m', strtotime($lastbefDM));
    	$befDMD 	= date('d', strtotime($lastbefDM));
    }

    $End_Date 		= "$befDMM/$befDMD/$befDMY";	

    $DefEmp_ID 		= $this->session->userdata['Emp_ID'];

    $getproject 	= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
    					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') ORDER BY A.PRJCODE";
    $qProject 		= $this->db->query($getproject)->result();
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
    		if($TranslCode == 'DisplayReport')$DisplayReport = $LangTransl;
    		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
    		if($TranslCode == 'Periode')$Periode = $LangTransl;
    		if($TranslCode == 'Type')$Type = $LangTransl;
    		if($TranslCode == 'Summary')$Summary = $LangTransl;
    		if($TranslCode == 'Detail')$Detail = $LangTransl;
    		if($TranslCode == 'ViewType')$ViewType = $LangTransl;
    		if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
    		if($TranslCode == 'Excel')$Excel = $LangTransl;
    	endforeach;
    	
    	if($LangID == 'IND')
    	{
    		$h1_title		= "Laporan";
    		$alert1			= "Anda belum memilih salah satu proyek.";
    	}
    	else
    	{
    		$h1_title		= "Report";
    		$alert1			= "You not yet select a project.";
    	}
    ?>
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    <?php echo $mnName; ?>
                    <small><?php echo $h1_title; ?></small>
                </h1>
            </section>

            <section class="content">
                <div class="box box-primary">
                	<div class="box-body chart-responsive">
                    	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return target_popup(this)">
                        	<div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $ProjectName ?></label>
                                <div class="col-sm-10">
                                	<select name="PRJCODE" id="PRJCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;<?php echo $ProjectName; ?>">
                                    	<option value="0"> --- </option>
            							<?php
                                            $sqlPRJ 	= "SELECT PRJCODE, PRJNAME
            											FROM tbl_project 
            											WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
            											ORDER BY PRJCODE";
                                            $resPRJ	= $this->db->query($sqlPRJ)->result();
                                            foreach($resPRJ as $row_1) :
                                                $PRJCODE	= $row_1->PRJCODE;
                                                $PRJNAME	= $row_1->PRJNAME;
                                                ?>
                                                <option value="<?php echo $PRJCODE; ?>">
                                                    <?php echo $PRJNAME; ?>
                                                </option>
                                               	<?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                        	<div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $Periode ?></label>
                                <div class="col-sm-10">
                                	<div class="input-group date">
                                        <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>&nbsp;</div>
                                        <input type="text" name="End_Date" class="form-control pull-left" id="datepicker" value="<?php echo $End_Date; ?>" style="width:100px">
                                	</div>
                                </div>
                            </div>
                        	<div class="form-group" style="display:none">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $Type ?></label>
                                <div class="col-sm-10">
                                	<label>
                                    <input type="radio" name="CFType" id="CFType1" value="1" checked /> 
                                    <?php echo $Summary ?> <br />
                                    <input type="radio" name="CFType" id="CFType2" value="2" /> 
                              		<?php echo $Detail ?>                     </label>
                                </div>
                            </div>
                        	<div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label"><?php echo $ViewType ?></label>
                                <div class="col-sm-10">
                                	<label>
                                    <input type="radio" name="viewType" value="0" class="flat-red" checked>
                                    <?php echo $WebViewer ?> &nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="viewType" value="1" class="flat-red">
                                  	<?php echo $Excel ?>         </label>
                                </div>
                            </div>
                        	<div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                <div class="col-sm-10">
                                	<button class="btn btn-primary"><i class="cus-display-report-16x16"></i>&nbsp;&nbsp;<?php echo $DisplayReport; ?></button>
                                    <?php if($DefEmp_ID == 'D15040004221') { ?>
                                        &nbsp;<button type="button" class="btn btn-warning" onclick="showJournal()"><i class="glyphicon glyphicon-refresh"></i>&nbsp;&nbsp;Syns LR</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                        <script>
                            function showJournal()
                            {
                                document.frmsrch2.submitSrch2.click();
                            }

                            function checkProj()
                            {
                                PRJCODE = document.getElementById('PRJCODE').value;

                                if(PRJCODE == '0')
                                {
                                    swal('<?php echo $alert1; ?>');
                                    return false;
                                }
                                else
                                {
                                    document.getElementById('PRJCODEXX').value = PRJCODE;
                                }
                            }
                        </script>
                        <form class="form-horizontal" name="frmsrch2" method="post" action="" onSubmit="return checkProj()" style="display: none;">
                            <input type="text" name="PRJCODEXX" id="PRJCODEXX" value="">
                            <input type="submit" class="button_css" name="submitSrch2" id="submitSrch2" value=" search " />
                        </form>
                        <?php
                            if(isset($_POST['PRJCODEXX']))
                            {
                                $PRJCODEX   = $_POST['PRJCODEXX'];

                                // RESET PROFIT AND LOSS
                                    $sqlRESET   = "UPDATE tbl_profitloss SET BPP_MTR_REAL = 0, BPP_UPH_REAL = 0, BPP_SUBK_REAL = 0,
                                                        BPP_ALAT_REAL = 0, BPP_I_REAL = 0, BPP_OTH_REAL = 0, BPP_BAU_REAL = 0,
                                                        BPP = 0, BLL = 0, BGP = 0, BLAT = 0, BAU = 0, BOL = 0, BPB = 0, BPM = 0, BPK = 0, BNOL = 0
                                                    WHERE PRJCODE IN (SELECT C.PRJCODE FROM tbl_project C WHERE C.PRJCODE_HO = '$PRJCODEX')";
                                    $this->db->query($sqlRESET);

                                // SYNC TABLE
                                    $sqlSYNCHD  = "UPDATE tbl_journaldetail A, tbl_journalheader B
                                                        SET A.JournalH_Date = B.JournalH_Date, A.GEJ_STAT = B.GEJ_STAT,
                                                            A.LastUpdate = B.LastUpdate
                                                    WHERE A.JournalH_Code  = B.JournalH_Code AND A.proj_Code = B.proj_Code";
                                    $this->db->query($sqlSYNCHD);

                                // SYNC ITM_GROUP
                                    $sqlSYNGRP  = "UPDATE tbl_journaldetail A, tbl_item B
                                                        SET A.ITM_GROUP = B.ITM_GROUP
                                                    WHERE A.ITM_CODE = B.ITM_CODE AND A.proj_Code = B.PRJCODE";
                                    $this->db->query($sqlSYNGRP);

                                // SHOW ALL JOURNAL STAT 3
                                    $sqlJOURNT1 = "SELECT DISTINCT
                                                        A.proj_Code,
                                                        A.JournalH_Code,
                                                        A.JournalH_Date,
                                                        A.JournalType,
                                                        A.Base_Debet,
                                                        A.Base_Kredit,
                                                        A.ITM_GROUP,
                                                        A.ITM_CODE
                                                    FROM
                                                        tbl_journaldetail A
                                                        INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
                                                            AND B.JournalType != 'IR'
                                                    WHERE
                                                        B.GEJ_STAT = 3
                                                        AND A.proj_Code IN (SELECT C.PRJCODE FROM tbl_project C WHERE C.PRJCODE_HO = '$PRJCODEX')
                                                        AND ( ITM_GROUP != '' OR ! ISNULL(ITM_GROUP))
                                                        AND IF(A.JournalType = 'GEJ', A.Base_Debet >0, A.Base_Kredit > 0)";
                                    $resJOURNT1 = $this->db->query($sqlJOURNT1)->result();
                                    foreach($resJOURNT1 as $rowJ1) :
                                        $PRJCODE    = $rowJ1->proj_Code;
                                        $journCode  = $rowJ1->JournalH_Code;
                                        $JournDate  = $rowJ1->JournalH_Date;
                                        $JournType  = $rowJ1->JournalType;
                                        $BaseDebet  = $rowJ1->Base_Debet;
                                        $BaseKredit = $rowJ1->Base_Kredit;
                                        $ITM_GROUP  = $rowJ1->ITM_GROUP;
                                        $ITM_CODE   = $rowJ1->ITM_CODE;

                                        $JournalAmn = $BaseKredit;
                                        if($JournType == 'GEJ')
                                           $JournalAmn = $BaseDebet; 

                                        $PERIODED   = $JournDate;
                                        $PERIODM    = date('m', strtotime($PERIODED));
                                        $PERIODY    = date('Y', strtotime($PERIODED));

                                        $ITM_LR     = '';
                                        $sqlLITMCTG = "SELECT ITM_LR FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
                                        $resLITMCTG = $this->db->query($sqlLITMCTG)->result();                  
                                        foreach($resLITMCTG as $rowLITMCTG):
                                            $ITM_LR = $rowLITMCTG->ITM_LR;
                                        endforeach;

                                        // L/R MANUFACTUR
                                            if($ITM_LR != '')
                                            {
                                                $updLR  = "UPDATE tbl_profitloss SET $ITM_LR = $ITM_LR+$JournalAmn 
                                                            WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
                                                $this->db->query($updLR);
                                            }

                                        if($ITM_GROUP == 'M')
                                        {
                                            $updLR  = "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL+$JournalAmn 
                                                        WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
                                            $this->db->query($updLR);
                                        }
                                        elseif($ITM_GROUP == 'U')
                                        {
                                            $updLR  = "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$JournalAmn 
                                                        WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
                                            $this->db->query($updLR);
                                        }
                                        elseif($ITM_GROUP == 'SC')
                                        {
                                            $updLR  = "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$JournalAmn 
                                                        WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
                                            $this->db->query($updLR);
                                        }
                                        elseif($ITM_GROUP == 'T')
                                        {
                                            $updLR  = "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$JournalAmn 
                                                        WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
                                            $this->db->query($updLR);
                                        }
                                        elseif($ITM_GROUP == 'I')
                                        {
                                            $updLR  = "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$JournalAmn 
                                                        WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
                                            $this->db->query($updLR);
                                        }
                                        elseif($ITM_GROUP == 'O')
                                        {
                                            $updLR  = "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$JournalAmn 
                                                        WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
                                            $this->db->query($updLR);
                                        }
                                        elseif($ITM_GROUP == 'GE')
                                        {
                                            $updLR  = "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$JournalAmn 
                                                        WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
                                            $this->db->query($updLR);
                                        }
                                    endforeach;
                            }
                        ?>
                	</div>
                </div>
            </section>
        </div>
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
</script>
<script>
	function MoveOption(objSourceElement, objTargetElement) 
	{ 
		var aryTempSourceOptions = new Array(); 
		var aryTempTargetOptions = new Array(); 
		var x = 0; 
    
   		//looping through source element to find selected options 
   		for (var i = 0; i < objSourceElement.length; i++)
		{ 
    		if (objSourceElement.options[i].selected)
			{ 
				 //need to move this option to target element 
				 var intTargetLen = objTargetElement.length++; 
				 objTargetElement.options[intTargetLen].text = objSourceElement.options[i].text; 
				 objTargetElement.options[intTargetLen].value = objSourceElement.options[i].value; 
    		} 
    		else
			{ 
				 //storing options that stay to recreate select element 
				 var objTempValues = new Object(); 
				 objTempValues.text = objSourceElement.options[i].text; 
				 objTempValues.value = objSourceElement.options[i].value; 
				 aryTempSourceOptions[x] = objTempValues; 
				 x++; 
			} 
   		}
		
   		//sorting and refilling target list 
		for (var i = 0; i < objTargetElement.length; i++)
		{ 
			var objTempValues = new Object(); 
			objTempValues.text = objTargetElement.options[i].text; 
			objTempValues.value = objTargetElement.options[i].value; 
			aryTempTargetOptions[i] = objTempValues; 
		} 

		aryTempTargetOptions.sort(sortByText); 

		for (var i = 0; i < objTargetElement.length; i++)
		{ 
			objTargetElement.options[i].text = aryTempTargetOptions[i].text; 
			objTargetElement.options[i].value = aryTempTargetOptions[i].value; 
			objTargetElement.options[i].selected = false; 
		}
		
   		//resetting length of source 
   		objSourceElement.length = aryTempSourceOptions.length; 
   		//looping through temp array to recreate source select element 
   		for (var i = 0; i < aryTempSourceOptions.length; i++) 
		{ 
			objSourceElement.options[i].text = aryTempSourceOptions[i].text; 
			objSourceElement.options[i].value = aryTempSourceOptions[i].value; 
			objSourceElement.options[i].selected = false; 
		}
	}

     function sortByText(a, b) 
     { 
		if (a.text < b.text) {return -1} 
		if (a.text > b.text) {return 1} 
		return 0; 
     } 
	
	var url = "<?php echo $form_action; ?>";
	function target_popup(form)
	{
		packageelements	= document.getElementById('PRJCODE').value;
		if(packageelements == '0')
		{
			swal('<?php echo $alert1; ?>',
            {
                icon:'warning'
            });
			return false;
		}
		title = 'Select Item';
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open('url', 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	var url = "<?php echo base_url().'index.php/c_itmng/uploadtxt/export_txt';?>";
	function exporttoexcel()
	{
		window.open(url,'window_baru','width=800','height=200','scrollbars=yes,resizable=yes,location=no,status=yes')
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
    $this->load->view('template/aside');

    $this->load->view('template/js_data');

    $this->load->view('template/foot');
?>