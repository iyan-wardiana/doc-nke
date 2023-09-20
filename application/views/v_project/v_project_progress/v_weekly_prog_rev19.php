<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 6 Mei 2018
 * File Name	= v_weekly_prog.php
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

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

function weekNumberOfMonth($theDate)
{
	$tanggal 	= (int)date('d',strtotime($theDate));
	$bulan   	= (int)date('m',strtotime($theDate));
	$tahun   	= (int)date('Y',strtotime($theDate));
	
	//tanggal 1 tiap bulan
	$firstDate 	= mktime(0, 0, 0, $bulan, 1, $tahun);
	$firstWeek 	= (int) date('W', $firstDate);
	
	//tanggal sekarang
	$dateSel 	= mktime(0, 0, 0, $bulan, $tanggal, $tahun);
	$weekSel1 	= (int) date('W', $dateSel);
	//$weekSel	= $weekSel1 - $firstWeek + 1;	// MINGGE KE TIAP BULAN
	$weekSel	= $weekSel1;					// MINGGE KE TIAP TAHUN
	return $weekSel;
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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
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
		if($TranslCode == 'ProgressCode')$ProgressCode = $LangTransl;
		if($TranslCode == 'Weekto')$Weekto = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'JobDescription')$JobDescription = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'ApprovalStatus')$ApprovalStatus = $LangTransl;
		if($TranslCode == 'GeneralJournal')$GeneralJournal = $LangTransl;
		if($TranslCode == 'AddNew')$AddNew = $LangTransl;
		if($TranslCode == 'Print')$Print = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'TotalAmount')$TotalAmount = $LangTransl;
		if($TranslCode == 'ProgressAmmount')$ProgressAmmount = $LangTransl;
		if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
		if($TranslCode == 'Periode')$Periode = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$sureDelete		= "Anda yakin akan menghapus data ini?";
		$progressList	= "Daftar Progress Mingguan";
	}
	else
	{
		$sureDelete		= "Are your sure want to delete?";
		$progressList	= "List of Weekly Proggress";
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/progress.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $progressList; ?>
    <small><?php echo $PRJNAME; ?></small>
  </h1><br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<style type="text/css">
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
	
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>
<!-- Main content -->

  <div class="box">
        <?php /*?><div class="box-header with-border">
            <h3 class="box-title"><?php echo $PRJNAME; ?></h3>
        </div><?php */?>
    <!-- /.box-header -->
<div class="box-body">
	<div class="search-table-outter">
      <table id="example1" class="table table-bordered table-striped" width="100%">
		<thead>
            <tr>
                <th style="vertical-align:middle; text-align:center" width="2%"><input name="chkAll" id="chkAll" type="checkbox" value="" style="display:none" /></th>
                <th style="vertical-align:middle; text-align:center" width="7%" nowrap><?php echo $ProgressCode; ?> </th>
                <th style="vertical-align:middle; text-align:center" width="8%" nowrap><?php echo $Date; ?>  </th>
                <th width="6%" nowrap="nowrap" style="vertical-align:middle; text-align:center"><?php echo $Weekto; ?>  </th>
                <th width="15%" nowrap="nowrap" style="vertical-align:middle; text-align:center"><?=$Periode?></th>
                <th width="31%" style="vertical-align:middle; text-align:center"><?php echo $JobDescription; ?> </th>
                <th width="9%" style="vertical-align:middle; text-align:center"><?php echo $ProgressAmmount; ?></th>
                <th style="vertical-align:middle; text-align:center" width="7%" nowrap><?php echo $CreatedBy; ?> </th>
                <th style="vertical-align:middle; text-align:center" width="3%" nowrap><?php echo $Status; ?> </th>
                <th style="vertical-align:middle; text-align:center" width="12%" nowrap>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			$j = 0;
			if($countWK >0)
			{
				foreach($vwWP as $row) :				
					$myNewNo1 		= ++$i;
					$PRJP_NUM		= $row->PRJP_NUM;
					$PRJP_DATE		= $row->PRJP_DATE;
					$PRJP_STEP		= $row->PRJP_STEP;
					$PRJCODE		= $row->PRJCODE;
					$PRJP_DESC		= $row->PRJP_DESC;
					$PRJP_TOT		= $row->PRJP_TOT;
					$PRJP_STAT		= $row->PRJP_STAT;
					$PRJP_CREATER	= $row->PRJP_CREATER;
					$PRJP_CREATED	= $row->PRJP_CREATED;
					$PRJPDate		= date('d M Y', strtotime($PRJP_CREATED));
					//echo $PRJPDate;
					//return false;	
					
					$Prg_Step 		= '';
					$Prg_Date1 		= '';
					$Prg_Date2 		= '';
					$Prg_PlanAkum	= '';
					$Prg_Real		= '';
					$Prg_ProjNotes 	= '';
					$Prg_PstNotes 	= '';
					
					$sqlProjStep	= "SELECT Prg_Step, Prg_Date1, Prg_Date2, Prg_PlanAkum, Prg_Real, Prg_ProjNotes, Prg_PstNotes
										FROM tbl_projprogres 
										WHERE proj_Code = '$PRJCODE' AND Prg_Step = $PRJP_STEP";
					$resProjStep	= $this->db->query($sqlProjStep)->result();		
					foreach($resProjStep as $rowProjStep) :
						$Prg_Step 		= $rowProjStep->Prg_Step;
						$Prg_Date1 		= $rowProjStep->Prg_Date1;
						$Prg_Date2 		= $rowProjStep->Prg_Date2;
						$Prg_PlanAkum	= $rowProjStep->Prg_PlanAkum;
						$Prg_Real		= $rowProjStep->Prg_Real;
						$Prg_ProjNotes 	= $rowProjStep->Prg_ProjNotes;
						$Prg_PstNotes 	= $rowProjStep->Prg_PstNotes;
					endforeach;
					if($Prg_Step == '')
						$Prg_Step	= 1;
					if($Prg_Date1 == '')
						$Prg_Date1	= $PRJP_DATE;
					if($Prg_Date2 == '')
						$Prg_Date2	= $PRJP_DATE;
					if($Prg_PlanAkum == '')
						$Prg_PlanAkum	= 0;
					if($Prg_Real == '')
						$Prg_Real	= $PRJP_TOT;
					if($Prg_ProjNotes == '')
						$Prg_ProjNotes	= $PRJP_DESC;
					if($Prg_PstNotes == '')
						$Prg_PstNotes	= $PRJP_DESC;
					
					$PRJP_CREATERNM	= '';
					$First_Name		= '';
					$Last_Name		= '';
					$sqlEmp 		= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$PRJP_CREATER'";
					$resEmp			= $this->db->query($sqlEmp)->result();
					foreach($resEmp as $rowEmp) :
						$First_Name = $rowEmp->First_Name;
						$Last_Name	= $rowEmp->Last_Name;
					endforeach;
					$PRJP_CREATERNM	= "$First_Name $Last_Name";
					
					if($PRJP_STAT == 0) $PRJP_STATDesc = "fake";
					elseif($PRJP_STAT == 1) $PRJP_STATDesc = "New";
					elseif($PRJP_STAT == 2) $PRJP_STATDesc = "Confirm";
					elseif($PRJP_STAT == 3) $PRJP_STATDesc = "Approved";
					elseif($PRJP_STAT == 4) $PRJP_STATDesc = "Close";
					elseif($PRJP_STAT == 5) $PRJP_STATDesc = "Reject";
					
					if($PRJP_STAT == 1)
					{
						$PRJP_STATD = 'New';
						$STATCOL	= 'warning';
					}
					elseif($PRJP_STAT == 2)
					{
						$PRJP_STATD = 'Confirm';
						$STATCOL	= 'primary';
					}
					elseif($PRJP_STAT == 3)
					{
						$PRJP_STATD = 'Approved';
						$STATCOL	= 'success';
					}
					elseif($PRJP_STAT == 4)
					{
						$PRJP_STATD = 'Revise';
						$STATCOL	= 'info';
					}
					elseif($PRJP_STAT == 5)
					{
						$PRJP_STATD = 'Rejected';
						$STATCOL	= 'danger';
					}
					elseif($PRJP_STAT == 6)
					{
						$PRJP_STATD = 'Close';
						$STATCOL	= 'danger';
					}
					elseif($PRJP_STAT == 7)
					{
						$PRJP_STATD = 'Waiting';
						$STATCOL	= 'warning';
					}
					else
					{
						$PRJP_STATD = 'Fake';
						$STATCOL	= 'danger';
					}
					
					if ($j==1) {
						echo "<tr class=zebra1>";
						$j++;
					} else {
						echo "<tr class=zebra2>";
						$j--;
					}
						?>
								<td style="text-align:center">
									<input type="radio" name="chkDetail" id="chkDetail" value="<?php echo $PRJP_NUM;?>" onClick="getValueNo(this);" style="display:none" />
                                    <?php echo $myNewNo1; ?>.
                                </td>
								<td nowrap>
									<?php echo $PRJP_NUM;?>
                                </td>
								<td style="text-align:center">
									<?php
										$date = new DateTime($PRJP_DATE);
									   	//echo $PRJP_DATE;
										echo $PRJPDate;
									?>   
                                </td>
								<td style="text-align:center">
									<?php
										$date = new DateTime($PRJP_DATE);
									   	//echo $PRJP_DATE;
										echo $Prg_Step;
									?>                            </td>
								<td style="text-align:center">
                                	<?php
										$Start_Date = date('d M Y', strtotime($Prg_Date1));
										$End_Date	= date('d M Y', strtotime($Prg_Date2));
										echo "$Start_Date s.d $End_Date";
									?>
                                </td>
								<td><?php print $PRJP_DESC; ?> </td>
								<td style="text-align:right"><?php echo number_format($PRJP_TOT, 4); ?>&nbsp;</td>
								<td style="text-align:center" nowrap><?php echo $PRJP_CREATERNM; ?></td>
								<td style="text-align:center">
                                <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
									<?php 
                                        echo "&nbsp;&nbsp;$PRJP_STATD&nbsp;&nbsp;";
                                     ?>
                                </span>
                                </td>
                                <?php
									$secUpd		= site_url('c_project/c_uPpR09r355/up0b28t18/?id='.$this->url_encryption_helper->encode_url($PRJP_NUM));
									$secPrint	= site_url('c_project/c_uPpR09r355/printdocument/?id='.$this->url_encryption_helper->encode_url($PRJP_NUM));
								?>
                                <input type="hidden" name="urlPrint<?php echo $myNewNo1; ?>" id="urlPrint<?php echo $myNewNo1; ?>" value="<?php echo $secPrint; ?>">
								<td style="text-align:center" nowrap>
                                    <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
                                    <a href="javascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo1; ?>')" <?php if($PRJP_STAT > 1) { ?>disabled="disabled" <?php } ?>>
                                        <i class="glyphicon glyphicon-print"></i>
                                    </a>
                                    <a href="#" onClick="deleteMail('')" title="Delete file" class="btn btn-danger btn-xs" <?php if($PRJP_STAT > 1) { ?>disabled="disabled" <?php } ?>>
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
						    </tr>
						<?php 
				endforeach; 
			}
		?>
        </tbody>
        <tr>
          <td colspan="10">
            <?php
                echo anchor("$addURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;'.$Add.'</button>&nbsp;&nbsp;');
				echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
            ?>
            </td>
        </tr>
   	</table>
    
    <form method="post" name="sendDelete" id="sendDelete" class="form-user" action="" style="display:none">		
        <table>
            <tr>
                <td></td>
                <td><a class="tombol-delete" id="delClass">Simpan</a></td>
            </tr>
        </table>
    </form>
    <?php
		$secIndex_PR	= site_url('c_purchase/c_purchase_req/get_all_PR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
	?>
    </div>
    <!-- /.box-body -->
</div>
  <!-- /.box -->
</div>
</body>

</html>
<!-- jQuery 2.2.3 -->
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
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
  
	function deleteMail(thisVal)
	{
		//alert(thisVal)
		//document.sendDelete.action = thisVal;
		//document.sendDelete.submit();
		//document.getElementById('delClass').click();
		document.sendDelete.action = thisVal;
		document.getElementById('delClass').click();
	}
	
	$(document).ready(function()
	{
		$(".tombol-delete").click(function()
		{
			alert('a')
			var index_Mail	= "<?php echo $secIndex_PR; ?>";
			var formAction 	= $('#sendDelete')[0].action;
			alert('b')
			var data = $('.form-user').serialize();
			alert('c')
			$.ajax({
				type: 'POST',
				url: formAction,
				data: data,
				success: function(response)
				{
					$( "#example1" ).load( ""+index_Mail+" #example1" );
				}
			});
			
		});
	});
  
	function getValueNo(thisVal)
	{
		myValue = thisVal.value;myMR_Number
		document.getElementById('myMR_Number').value = myValue;
	}
	
	function printPOList(row)
	{
		var url	= document.getElementById('urlPOList'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	function printDocument(row)
	{
		var url	= document.getElementById('urlPrint'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>