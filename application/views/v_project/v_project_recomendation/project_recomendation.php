<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Februari 2017
 * File Name	= project_recomendation.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

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

$log_passHint	= $this->session->userdata['log_passHint'];
$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$isShow1 = 0;
$isShow2 = 0;
$isShow3 = 0;
$isShowx = 0;
// Get Employee's Authorization (Add) ---- Masih manual inject to database
$sqlGetC1			= "tbl_auth WHERE Emp_ID = '$DefEmp_ID' AND AUTH_CODE = 'ADD_PRJ_REKOM'";
$resGetC1			= $this->db->count_all($sqlGetC1);
// Get Employee's Authorization (Confirm)
$sqlGetC2			= "tbl_auth WHERE Emp_ID = '$DefEmp_ID' AND AUTH_CODE = 'CONF_PRJ_REKOM'";
$resGetC2			= $this->db->count_all($sqlGetC2);
// Get Employee's Authorization (App)
$sqlGetC3			= "tbl_auth WHERE Emp_ID = '$DefEmp_ID' AND AUTH_CODE = 'APP_PRJ_REKOM'";
$resGetC3			= $this->db->count_all($sqlGetC3);

if($resGetC1 > 0)
{
	$isShow1 = 1;
	$isShowx = 1;
}
if($resGetC2 > 0)
{
	$isShow2 = 1;
	$isShowx = 2;
}
if($resGetC3 > 0)
{
	$isShow3 = 1;
	$isShowx = 3;
}

if($FlagUSER == 'SUPERADMIN')
{
	$isShow1 = 1;
	$isShow2 = 1;
	$isShow3 = 1;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
		if($TranslCode == 'Owner')$Owner = $LangTransl;
		if($TranslCode == 'Location')$Location = $LangTransl;
		if($TranslCode == 'TenderEstimateDate')$TenderEstimateDate = $LangTransl;
		if($TranslCode == 'ProjectValue')$ProjectValue = $LangTransl;
		if($TranslCode == 'Conclusion')$Conclusion = $LangTransl;
		if($TranslCode == 'User')$User = $LangTransl;
		if($TranslCode == 'View')$View = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">

<h1>
    <?php echo $h2_title; ?> 
    <small>project</small>
  </h1><br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<!-- Main content -->

  <div class="box">
    <!-- /.box-header -->
<div class="box-body">
	<div class="search-table-outter">
      <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
        <thead>
            <tr>
              	<th width="7%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code?> </th>
       	  	  <th width="17%" style="text-align:center; vertical-align:middle" nowrap><?php echo $ProjectName?></th>
           	  <th width="27%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Owner?></th>
           	  <th width="13%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Location?></th>
              	<th width="7%" style="text-align:center; vertical-align:middle; display:none" nowrap>PQ Estimate<br>
           	  Date</th>
           	  <th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $TenderEstimateDate?></th>
              <th width="7%" style="text-align:center; vertical-align:middle" nowrap><?php echo $ProjectValue?></th>
              	<th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Conclusion?></th>
              	<th width="3%" style="text-align:center; vertical-align:middle" nowrap><?php echo $User?></th>
           	  <th width="3%" style="text-align:center; vertical-align:middle" nowrap><?php echo $View?></th>
           	  <th width="4%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Status?></th>
          </tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			$j = 0;
			if($recordcount >0)
			{
			foreach($viewrecproj as $row) :
				$myNewNo 		= ++$i;
				$REC_CODE		= $row->REC_CODE;
				$REC_NO 		= $row->REC_NO;
				$REC_LL_NO		= $row->REC_LL_NO;
				$REC_PRJNAME	= $row->REC_PRJNAME;
				if(strlen($REC_PRJNAME) > 40)
				{
					$MAXCHAR_1		= 30;
					$CUT_TEXT_1		= substr($REC_PRJNAME, 0, $MAXCHAR_1);
					if ($REC_PRJNAME{$MAXCHAR_1 - 1} != ' ') 
					{
						$NEW_POS_1 	= strrpos($CUT_TEXT_1, ' ');
						$CUT_TEXT_1 	= substr($REC_PRJNAME, 1, $NEW_POS_1);
					}
					$REC_PRJNAMEV	= $CUT_TEXT_1 . "...";
				}
				else
				{
					$REC_PRJNAMEV	= $REC_PRJNAME;
				}

				$REC_LOCATION	= $row->REC_LOCATION;
				if(strlen($REC_LOCATION) > 40)
				{
					$MAXCHAR_2		= 30;
					$CUT_TEXT_2		= substr($REC_LOCATION, 0, $MAXCHAR_2);
					if ($REC_LOCATION{$MAXCHAR_2 - 1} != ' ') 
					{
						$NEW_POS_2 	= strrpos($CUT_TEXT_2, ' ');
						$CUT_TEXT_2 	= substr($REC_LOCATION, 1, $NEW_POS_2);
					}
					$REC_LOCATIONV	= $CUT_TEXT_2 . "...";
				}
				else
				{
					$REC_LOCATIONV	= $REC_LOCATION;
				}
				$REC_PQ_DATEX	= $row->REC_PQ_DATE;
				$REC_PQ_DATE	= date('Y-m-d',strtotime($REC_PQ_DATEX));
				
				$REC_TEND_DATEX	= $row->REC_TEND_DATE;
				$REC_TEND_DATE	= date('Y-m-d',strtotime($REC_TEND_DATEX));
				
				$REC_DATEX		= $row->REC_DATE;
				$REC_DATE		= date('Y-m-d',strtotime($REC_DATEX));	
				
				$REC_OWNER		= $row->REC_OWNER;
				$ownerName		= "";
					$sqlX 		= "SELECT own_Title, own_Name
									FROM tbl_owner WHERE own_Code = '$REC_OWNER'";
					$result 	= $this->db->query($sqlX)->result();
					foreach($result as $rowx) :
						$own_Title		= $rowx->own_Title;
						$own_Name		= $rowx->own_Name;
						if($own_Title != '')
						{
							$ownerName	= "$own_Title $own_Name";
						}
						else
						{
							$ownerName	= "$own_Name";
						}
					endforeach;
					if(strlen($ownerName) > 40)
					{
						$MAXCHAR_3		= 40;
						$CUT_TEXT_3		= substr($ownerName, 0, $MAXCHAR_3);
						if ($ownerName{$MAXCHAR_3 - 1} != ' ') 
						{
							$NEW_POS_3 	= strrpos($CUT_TEXT_3, ' ');
							$CUT_TEXT_3 	= substr($ownerName, 1, $NEW_POS_3);
						}
						$ownerNameV	= $CUT_TEXT_3 . "...";
					}
					else
					{
						$ownerNameV	= $ownerName;
					}
					
				$REC_PRJTYPEX	= $row->REC_PRJTYPE;
				if($REC_PRJTYPEX == 1)
				{
					$REC_PRJTYPED = 'Kecil';
				}
				elseif($REC_PRJTYPEX == 2)
				{
					$REC_PRJTYPED = 'Sedang';
				}
				else
				{
					$REC_PRJTYPED = 'Besar';
				}
				
				$REC_STAT		= $row->REC_STAT;
				if($REC_STAT == 1)
				{
					$REC_STATD = 'New';
					$STATCOL	= 'warning';
				}
				elseif($REC_STAT == 2)
				{
					$REC_STATD = 'Confirm';
					$STATCOL	= 'primary';
				}
				elseif($REC_STAT == 3)
				{
					$REC_STATD 	= 'Approve';
					$STATCOL	= 'success';
				}
				else
				{
					$REC_STATD = 'Not Detected';
					$STATCOL	= 'danger';
				}
				
				$REC_CONCLUTION	= $row->REC_CONCLUTION;
				if($REC_CONCLUTION == 0)
				{
					$REC_CONCLUTIOND	= " - ";
				}
				elseif($REC_CONCLUTION == 1)
				{
					$REC_CONCLUTIOND	= "Participate";
				}
				else
				{
					$REC_CONCLUTIOND	= "Not Participate";
				}
				
				$REC_CREATER	= $row->REC_CREATER;
				$sqlZ 			= "SELECT log_passHint
									FROM tbl_employee WHERE Emp_ID = '$REC_CREATER'";
				$resultZ 		= $this->db->query($sqlZ)->result();
				foreach($resultZ as $rowz) :
					$log_passHint	= $rowz->log_passHint;
				endforeach;
					
				$secUpd		= site_url('c_project/project_recomendation/update/?id='.$this->url_encryption_helper->encode_url($REC_CODE));
					
				if ($j==1) {
					echo "<tr class=zebra1>";
					$j++;
				} else {
					echo "<tr class=zebra2>";
					$j--;
				}
					$urlViewDoc	= site_url('c_project/project_recomendation/viewDocRecomend/?id='.$this->url_encryption_helper->encode_url($REC_CODE));
					?>
                    		<input type="hidden" name="viewUrl_<?php echo $myNewNo; ?>" id="viewUrl_<?php echo $myNewNo; ?>" value="<?php echo $urlViewDoc; ?>">
                            <td nowrap><?php echo anchor($secUpd,$REC_NO);?></td>
                            <td title="<?php echo $REC_PRJNAME; ?>" nowrap> <?php echo $REC_PRJNAMEV; ?> </td>
                            <td title="<?php echo $ownerName; ?>" > <?php echo $ownerNameV; ?> </td>
                            <td title="<?php echo $REC_LOCATIONV; ?>" nowrap style="text-align:left"> <?php echo $REC_LOCATIONV; ?></td>
                            <td nowrap style="text-align:center; display:none"><?php echo $REC_PQ_DATE; ?></td>
                            <td nowrap style="text-align:center"><?php echo $REC_TEND_DATE; ?></td>
                            <td nowrap> <?php echo $REC_PRJTYPED; ?> </td>
                            <td nowrap> <?php echo $REC_CONCLUTIOND; ?> </td>
                            <td nowrap> <?php echo $log_passHint; ?> </td>
                            <td nowrap style="text-align:center">
                            	<a href="javascript:void(null);" onClick="selectitem('<?php echo $myNewNo; ?>');" data-skin="skin-green" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                            </td>
                            <td style="text-align:center" nowrap>
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
								<?php 
                                    echo "&nbsp;&nbsp;$REC_STATD&nbsp;&nbsp;";
                                 ?>
                            </span>
                            </td>
                        </tr>
					<?php 
				endforeach; 
			}
		?>
		<script>
            function selectitem(thisValu)
            {
            	var url = document.getElementById('viewUrl_'+thisValu).value;
				//alert(url)
                title = 'Select Item';
                w = 1000;
                h = 550;
                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
                var left = (screen.width/2)-(w/2);
                var top = (screen.height/2)-(h/2);
                return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
            }
        </script>
        <tfoot>
          <tr>
            <td colspan="11" style="text-align:left">
            	<?php if($isShowx == 1) 
				{
					echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>&nbsp;');
				} 
				?>
			</td>
          </tr>
        </tfoot>
   	</table>
    </div>
    <!-- /.box-body -->
</div>
  <!-- /.box -->
</div>
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
  $(function () 
  {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
<script>
	function getValueNo(thisVal)
	{
		myValue = thisVal;
		document.getElementById('myProjCode').value = myValue;
		document.getElementById('selProject').value = myValue;
		chooseProject(thisVal);
	}
	
	function chooseProject(thisVal)
	{
		document.frmselect.submit.click();
	}
		
	function vProjPerform()
	{
		myVal = document.getElementById('myProjCode').value;
		
		if(myVal == '')
		{
			alert('Please select one of Project Code.')
			return false;
		}
		var url = '<?php echo $urlProjPerF; ?>';
		title = 'Select Item';		
		
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+screen.width+', height='+screen.height);
	}
		
	function vInpProjDet()
	{
		myVal = document.getElementById('myProjCode').value;
		
		if(myVal == '')
		{
			alert('Please select one of Project Code.')
			return false;
		}
		var url = '<?php echo $urlProjInDet; ?>';
		title = 'Select Item';		
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>