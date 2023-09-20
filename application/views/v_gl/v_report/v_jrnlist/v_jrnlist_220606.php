<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 1 Maret 2022
	* File Name		= v_jrnlist.php
	* Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];
$comp_color	= $this->session->userdata['comp_color'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

$selProject = '';
if(isset($_POST['submit']))
{
	$selProject = $_POST['selProject'];
}

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
?>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
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

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

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
			if($TranslCode == 'JournalCode')$JournalCode = $LangTransl;
			if($TranslCode == 'JournalType')$JournalType = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'ApprovalStatus')$ApprovalStatus = $LangTransl;
			if($TranslCode == 'GeneralJournal')$GeneralJournal = $LangTransl;
			if($TranslCode == 'AddNew')$AddNew = $LangTransl;
			if($TranslCode == 'Print')$Print = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'TotalAmount')$TotalAmount = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
			if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
			if($TranslCode == 'yesDel')$yesDel = $LangTransl;
			if($TranslCode == 'cancDel')$cancDel = $LangTransl;
			if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
			if($TranslCode == 'Account')$Account = $LangTransl;
			if($TranslCode == 'Grouping')$Grouping = $LangTransl;
			if($TranslCode == 'All')$All = $LangTransl;
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$grpJrn	= "Pengelompokan Jurnal Transaksi";
		}
		else
		{
			$grpJrn = "Transaction List Grouping";
		}
	?>

<body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
	<section class="content-header">
		<h1>
		    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
		    <small><?php echo ""; ?></small>
		</h1>
	</section>
	<!-- Main content -->

    <section class="content">
	    <div class="row">
	    	<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="glyphicon glyphicon-list"></i>
						<h3 class="box-title"><?php echo $grpJrn ?> </h3>

			          	<div class="box-tools pull-right">
			            	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			            	<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
			          	</div>
					</div>
					<div class="box-body">
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-1 control-label" style="display: none;"><?php echo $Group ?> </label>
                          	<div class="col-sm-3">
                            	<select name="PRJCODE" id="PRJCODE" class="form-control select2">
                            		<option value="All"> <?=$All?> </option>
									<?php
										$s_00 	= "SELECT A.proj_Code, B.PRJNAME FROM tbl_employee_proj A
													INNER JOIN tbl_project B ON A.proj_Code = B.PRJCODE WHERE A.Emp_ID = '$DefEmp_ID'";
										$r_00 	= $this->db->query($s_00)->result();
										foreach($r_00 as $rw_00) :
											$PRJCODE = $rw_00->proj_Code;
											$PRJNAME = $rw_00->PRJNAME;
                                            ?>
                                            <option value="<?php echo $PRJCODE; ?>"><?php echo "$PRJCODE - $PRJNAME"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                          	</div>
                          	<div class="col-sm-3">
                            	<select name="ACCID" id="ACCID" class="form-control select2">
                            		<option value="All"> <?=$All?> </option>
									<?php
										$s_01 	= "SELECT A.Account_Number, A.Account_NameId FROM tbl_chartaccount A
													INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
													AND B.isHO = 1 AND A.isLast = 1";
										$r_01 	= $this->db->query($s_01)->result();
										foreach($r_01 as $rw_01) :
											$ACCID 		= $rw_01->Account_Number;
											$ACCNAME 	= $rw_01->Account_NameId;
                                            ?>
                                            <option value="<?php echo $ACCID; ?>"><?php echo "$ACCID - $ACCNAME"; ?></option>
                                            <?php
                                        endforeach;
                                    ?>
                                </select>
                          	</div>
                          	<?php
                          		$dateP 	= date('d/m/Y');
                          	?>
                          	<div class="col-sm-2">
                            	<div class="input-group date">
		                        	<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
		                        	<input type="text" name="Start_Date" class="form-control pull-left" id="datepicker1" value="<?php echo $dateP; ?>" size="10" >
		                        </div>
                          	</div>
                          	<div class="col-sm-2">
                            	<div class="input-group date">
		                        	<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
		                        	<input type="text" name="Start_Date" class="form-control pull-left" id="datepicker2" value="<?php echo $dateP; ?>" size="10" >
		                        </div>
                          	</div>
                          	<div class="col-sm-2">
	                          	<button class="btn btn-primary" type="button" id="btnGrp" name="btnGrp" onClick="grpItem()">
	                        		<i class="glyphicon glyphicon-ok"></i>
	                        	</button>
	                        </div>
                        </div>
                    </div>
            	</div>
	        </div>
	    </div>
	    <script type="text/javascript">
	    	function grpItem()
	    	{
	    		var PRJCODE 	= document.getElementById('PRJCODE').value;
	    		var ACCID 		= document.getElementById('ACCID').value;
	    		var STARTD 		= document.getElementById('datepicker1').value;
	    		var ENDD 		= document.getElementById('datepicker2').value;
	    		var collData 	= PRJCODE+'~'+ACCID+'~'+STARTD+'~'+ENDD;

		        $('#example').DataTable( {
		        	"destroy": true,
		            "processing": true,
		            "serverSide": true,
		    		//"scrollX": false,
		    		"autoWidth": true,
		    		"filter": true,
		            "ajax": "<?php echo site_url('c_gl/c_r3p0r77l/get_AllDataJRN/?id=')?>"+collData,
		            "type": "POST",
		    		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
		    		"lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
		    		"columnDefs": [	{ targets: [1,6], className: 'dt-body-center' },
									{ targets: [4,5], className: 'dt-body-right' },
									{ "width": "100px", "targets": [1] }
		    					  ],
		            "order": [[ 1, "desc" ]],
		    		"language": {
		                "infoFiltered":"",
		                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
		            },
				});
	    	}
	    </script>

		<div class="box box-success">
		    <div class="box-body">
		        <div class="search-table-outter">
		            <table id="example" class="table table-bordered table-striped" width="100%">
		                <thead>
		                    <tr>
		                        <th width="10%" style="vertical-align:middle; text-align:center" nowrap><?php echo $JournalCode; ?></th>
		                        <th width="5%" nowrap="nowrap" style="vertical-align:middle; text-align:center"><?php echo $Date; ?>  </th>
		                        <th width="55%" style="vertical-align:middle; text-align:center"><?php echo $Description; ?> </th>
		                        <th width="5%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Account; ?> </th>
		                        <th width="10%" style="vertical-align:middle; text-align:center" nowrap>Debet </th>
		                        <th width="10%" style="vertical-align:middle; text-align:center" nowrap>Kredit </th>
		                        <th width="5%" style="vertical-align:middle; text-align:center" nowrap>&nbsp;</th>
		                    </tr>
		                </thead>
		                <tbody>
		                </tbody>
		        	</table>
		        </div>
		        <br>
		    </div>
		</div>
    	<?php
			$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    		if($DefEmp_ID == 'D15040004221')
            	echo "<font size='1'><i>$act_lnk</i></font>";
        ?>
	</section>
</body>
</html>
<?php
	//echo ".$PRJCODE.'&jrnCat='.$jrnCat";
?>
<script>
	$(function ()
	{
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
	    $.fn.datepicker.defaults.format = "dd/mm/yyyy";
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

	/*$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
		//"scrollX": false,
		"autoWidth": true,
		"filter": true,
        "ajax": "<?php echo site_url('c_gl/cgeje0b28t18/get_AllDataJRN/?id='.$PRJCODE.'&jrnCat='.$jrnCat)?>",
        "type": "POST",
		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
		"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
		"columnDefs": [	{ targets: [0,1,6], className: 'dt-body-center' },
						{ targets: [4,5], className: 'dt-body-right' },
						{ "width": "100px", "targets": [1] }
					  ],
        "order": [[ 0, "desc" ]],
		"language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
		} );
	} );*/
	$(document).ready(function() {
        $('#exampleXX').DataTable( {
            "processing": true,
            "serverSide": true,
    		//"scrollX": false,
    		"autoWidth": true,
    		"filter": true,
            "ajax": "<?php echo site_url('c_gl/cgeje0b28t18/get_AllDataJRN/?id='.$PRJCODE.'&jrnCat='.$jrnCat)?>",
            "type": "POST",
    		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
    		"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
    		"columnDefs": [	{ targets: [0,1,6], className: 'dt-body-center' },
							{ targets: [4,5], className: 'dt-body-right' },
							{ "width": "100px", "targets": [1] }
    					  ],
            "order": [[ 1, "desc" ]],
    		"language": {
                "infoFiltered":"",
                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
            },
		});
	} );
  
	function getValueNo(thisVal)
	{
		myValue = thisVal.value;myMR_Number
		document.getElementById('myMR_Number').value = myValue;
	}
	
	function printD(row)
	{
		var url	= document.getElementById('urlPrint'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	function deleteDOC(row)
	{
	    swal({
            text: "<?php echo $sureDelete; ?>",
            icon: "warning",
            buttons: ["Yes", "No"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                /*swal("<?php echo $cancDel; ?>", 
				{
					icon: "error",
				});*/
            } 
            else 
            {
                var collID	= document.getElementById('urlDel'+row).value;
		        var myarr 	= collID.split("~");

		        var url 	= myarr[0];

		        $.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	swal(response, 
						{
							icon: "success",
						});
		                $('#example').DataTable().ajax.reload();
		            }
		        });
            }
        });
	}
    
    function voidDOC(row)
    {
        swal({
            text: "<?php echo $sureVoid; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID  = document.getElementById('urlVoid'+row).value;
                var myarr   = collID.split("~");

                var url     = myarr[0];

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {collID: collID},
                    success: function(response)
                    {
                        swal(response, 
                        {
                            icon: "success",
                        });
                        $('#example').DataTable().ajax.reload();
                    }
                });
            } 
            else 
            {
                //...
            }
        });
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