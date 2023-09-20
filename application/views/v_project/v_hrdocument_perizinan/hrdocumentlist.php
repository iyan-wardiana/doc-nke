<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Februari 2017
 * File Name	= hrdocumentlist.php
 * Location		= -
*/
$sqlA 			= "SELECT doc_parent FROM tbl_document WHERE doc_code = '$doc_code' AND isHRD = 1";
$resultA 		= $this->db->query($sqlA)->result();
foreach($resultA as $rowA) :
	$doc_parent = $rowA->doc_parent;
endforeach;

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');
$comp_color = $this->session->userdata('comp_color');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$ISREAD 	= $this->session->userdata['ISREAD'];
$ISCREATE 	= $this->session->userdata['ISCREATE'];
$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

$DAU_WRITE 	= 0;
$DAU_READ 	= 0;
$sqlDAU 	= "SELECT DAU_WRITE, DAU_READ
				FROM tbl_employee_docauth
				WHERE DAU_EMPID = '$DefEmp_ID'";
$resultDAU 	= $this->db->query($sqlDAU)->result();
foreach($resultDAU as $rowDAU) :
	$DAU_WRITE 	= $rowDAU->DAU_WRITE;
endforeach;
?>
<script>
	function chooseProject(thisVal)
	{
		proj_Code	= thisVal.value;
		document.frmsrch.submitSrch.click();
	}
</script>
<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $appName; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
	// $this->load->view('template/topbar');
	// $this->load->view('template/sidebar');

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
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
		if($TranslCode == 'DocCode')$DocCode = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'Reminder')$Reminder = $LangTransl;
		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
		if($TranslCode == 'document')$document = $LangTransl;
		if($TranslCode == 'Month')$Month = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Name')$Name = $LangTransl;
		if($TranslCode == 'DocLocation')$DocLocation = $LangTransl;
		if($TranslCode == 'Type')$Type = $LangTransl;
		if($TranslCode == 'Action')$Action = $LangTransl;
		if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;

	endforeach;
?>

<body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
    <section class="content-header">
        <h1>
            <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;
	        <?php echo $doc_parent; ?>
	        <small><?php echo "$doc_name"; ?></small>
            <div class="pull-right">
            <?php 
            	if($DAU_WRITE == 1)
				{
					$secAddURL	= site_url('c_project/hrdocument_perizinan/add/?id='.$this->url_encryption_helper->encode_url($doc_code));
					echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="fa fa-plus"></i></button>&nbsp;');
				}
                echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-reply"></i></button>');
            ?></div>
        </h1>
    </section>
	<style>
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
	</style>

    <section class="content">
	    <div class="box">
			<div class="box-body">
				<div class="search-table-outter">
					<table id="example1" class="table table-bordered table-striped" width="100%">
				    	<input type="hidden" name="DAU_WRITEX" id="DAU_WRITEX" value="<?php echo $DAU_WRITE; ?>">
				        <thead>
				            <tr style="background:#CCCCCC">
				                <th style="text-align:center; vertical-align: middle;" width="8%" nowrap><?php echo $DocNumber ?> </th>
				              	<th style="text-align:center; vertical-align: middle;" width="6%" nowrap><?php echo $DocCode ?></th>
				              	<th style="text-align:center; vertical-align: middle;" width="3%" nowrap><?php echo $Date ?> </th>
				              	<th style="text-align:center; vertical-align: middle;" width="6%" nowrap><?php echo $EndDate ?></th>
				                <th style="text-align:center; vertical-align: middle;" width="6%" nowrap><?php echo $Reminder ?><br><?php echo "$DueDate&nbsp;$document" ?></th>
				              	<th style="text-align:center; vertical-align: middle;" width="51%" nowrap><?php echo $Description ?></th>
				              	<th style="text-align:center; vertical-align: middle;" width="6%"><?php echo $DocLocation ?></th>
				              	<th style="text-align:center; vertical-align: middle;" width="3%" nowrap><?php echo $Type ?></th>
				              	<th style="text-align:center; vertical-align: middle;" width="8%" nowrap><?php echo $Action ?></th>
				          	</tr>
				        </thead>
				        <tbody>
				        </tbody>
				   	</table>
				</div>
			</div>
		</div>
	</section>
	<?php
		$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if($DefEmp_ID == 'D15040004221')
			echo "<font size='1'><i>$act_lnk</i></font>";
	?>
</body>

</html>
<?php
	$secOpen 	= base_url() . 'assets/AdminLTE-2.0.5/doc_center/webpdf/web/viewer.php?FileUpName=';
?>
<script>
	function chgGROUPDOC()
	{
		selGROUPDOC 	= document.getElementById('selGROUPDOCX').value;
		document.getElementById('selGROUPDOC').value = selGROUPDOC;
		selCLASSDOC 	= document.getElementById('selCLASSDOCX').value;
		document.getElementById('selCLASSDOC').value = '';
		selTYPEDOC 	= document.getElementById('selTYPEDOCX').value;
		document.getElementById('selTYPEDOC').value = '';
		
		document.getElementById('subChgGROUPDOC').click();
	}
	
	function chgCLASSDOC()
	{
		selGROUPDOC 	= document.getElementById('selGROUPDOCX').value;
		document.getElementById('selGROUPDOCA').value = selGROUPDOC;
		selCLASSDOC 	= document.getElementById('selCLASSDOCX').value;
		document.getElementById('selCLASSDOCA').value = selCLASSDOC;
		selTYPEDOC 	= document.getElementById('selTYPEDOCX').value;
		document.getElementById('selTYPEDOCA').value = '';
		
		document.getElementById('subChgCLASSDOC').click();
	}
	
	function chgTYPEDOC()
	{
		selGROUPDOC 	= document.getElementById('selGROUPDOCX').value;
		document.getElementById('selGROUPDOCB').value = selGROUPDOC;
		selCLASSDOC 	= document.getElementById('selCLASSDOCX').value;
		document.getElementById('selCLASSDOCB').value = selCLASSDOC;
		selTYPEDOC 	= document.getElementById('selTYPEDOCX').value;
		document.getElementById('selTYPEDOCB').value = selTYPEDOC;
		
		document.getElementById('subChgTYPEDOC').click();
	}
	
	function selectPICT(thisVal)
	{
		var DAU_WRITEX = document.getElementById('DAU_WRITEX').value;
		if(DAU_WRITEX == 0)
		{
			alert('You can not access to upload document.');
			return false;
		}
		var urlUplPICT = document.getElementById('secUplURL_'+thisVal).value;
		title = 'Select Item';
		w = 780;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(urlUplPICT, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
	
	var urlOpen = "<?php echo $secOpen;?>";
	var urlOpenx = "<?php echo base_url();?>";
	function typeOpenNewTab(thisVal)
	{
		var myFileName	= document.getElementById('FileUpName'+thisVal).value;
		var FileUpName	= ''+myFileName+'&base_url='+urlOpenx;
		title = 'Select Item';
		w = 850;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(urlOpen+FileUpName, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
    
    function deleteDOC(row)
    {
        swal({
            text: "<?php echo $sureDelete; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID  = document.getElementById('urlDel'+row).value;
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
                        $('#example1').DataTable().ajax.reload();
                    }
                });
            } 
            else 
            {
                //...
            }
        });
    }

	/*function typeOpenNewTab(thisVal)
	{
		var myFileName	= document.getElementById('FileUpName'+thisVal).value;
		var FileUpName	= ''+myFileName;
		alert(FileUpName)
		title = 'Select Item';
		w = 850;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(urlOpen+FileUpName, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}*/
</script>

<script>
	$(document).ready(function() {
		$('#example1').DataTable({
			"processing": true,
			"serverSide": true,
			//"scrollX": false,
			"autoWidth": true,
			"filter": true,
			"ajax": "<?php echo site_url('c_project/hrdocument_perizinan/get_AllData/?id='.$doc_code)?>",
			"type": "POST",
			//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
			"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
			"columnDefs": [	{ targets: [0,1,2,4,6,7,8], className: 'dt-body-center' },
							{ "width": "100px", "targets": [1] }
						],
			"language": {
				"infoFiltered":"",
				"processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
			},
		});

		//$("#example1").DataTable();
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