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
	
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
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
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Name')$Name = $LangTransl;
		if($TranslCode == 'DocLocation')$DocLocation = $LangTransl;
		if($TranslCode == 'Type')$Type = $LangTransl;
		if($TranslCode == 'Action')$Action = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <?php echo $h2_title; ?>
    <small><?php echo $h3_title; ?></small>
  </h1>
  <br>
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
	<table id="example1" class="table table-bordered table-striped" width="100%">
    	<input type="hidden" name="DAU_WRITEX" id="DAU_WRITEX" value="<?php echo $DAU_WRITE; ?>">
        <thead>
            <tr style="background:#CCCCCC">
                <th style="text-align:center" width="8%" nowrap><?php echo $DocNumber ?> </th>
              	<th style="text-align:center" width="6%" nowrap><?php echo $DocCode ?></th>
              	<th style="text-align:center" width="3%" nowrap><?php echo $Date ?> </th>
              	<th style="text-align:center" width="6%" nowrap><?php echo $EndDate ?></th>
              	<th style="text-align:center" width="51%" nowrap><?php echo $Description ?></th>
              	<th style="text-align:center; <?php if($doc_parent != 'D0243') { ?> display:none <?php } ?>" width="9%" nowrap>
               		<?php echo $Name ?>
                </th>
              	<th style="text-align:center" width="6%"><?php echo $DocLocation ?></th>
              	<th style="text-align:center" width="3%" nowrap><?php echo $Type ?></th>
              	<th style="text-align:center" width="8%" nowrap><?php echo $Action ?></th>
          </tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			if($countDoc >0)
			{	
				foreach($viewdocument as $row) :
					$myNewNo = ++$i;
					$empID			= '';
					$HRDOCNO		= $row->HRDOCNO;
					$HRDOCCODE		= $row->HRDOCCODE;
					$HRDOCTYPE		= $row->HRDOCTYPE;
					if($HRDOCTYPE == 1)
						$HRDOCTYPED	= "Asli";
					else
						$HRDOCTYPED	= "Copy";
					
					$TRXDATE		= $row->TRXDATE;
					$END_DATE		= $row->END_DATE;
					if($END_DATE == '')
						$END_DATE	= "-";
					
					$PRJCODE		= $row->PRJCODE;
					$PRJNAME		= 'Not Found';
					if($PRJCODE != '')
					{
						$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
						$result 	= $this->db->query($sql)->result();
						foreach($result as $rowPRJ) :
							$PRJNAME = $rowPRJ ->PRJNAME;
						endforeach;
					}
					$OWNER_CODE		= $row->OWNER_CODE;
					$OWNER_DESC		= $row->OWNER_DESC;
					$HRDOCCOST		= 0;
					$HRDOCJNS		= $row->HRDOCJNS;
					if($HRDOCJNS == 1)
					{
						$HRDOCJNS	= "LEMBAR";
					}
					elseif($HRDOCJNS == 2)
					{
						$HRDOCJNS	= "BUKU";
					}
					elseif($HRDOCJNS == 3)
					{
						$HRDOCJNS	= "BUKU TIPIS";
					}
					else
					{
						$HRDOCJNS	= $HRDOCJNS;
					}
					$HRDOCJML		= $row->HRDOCJML;
					$HRDOCLOK		= $row->HRDOCLOK;
					$HRDOC_NAME		= $row->HRDOC_NAME;
					$PM_EMPCODE		= $row->PM_EMPCODE;
					$PM_NAME		= $row->PM_NAME;
					$PM_STATUS		= $row->PM_STATUS;					
					if($PM_EMPCODE != '')
					{
						$First_Name = "";
						$Last_Name = "";
						$sqlEMPD	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$PM_EMPCODE'";
						$resEMPD	= $this->db->query($sqlEMPD)->result();
						foreach($resEMPD as $rowEMPD) :
							$First_Name = $rowEMPD ->First_Name;
							$Last_Name 	= $rowEMPD ->Last_Name;
						endforeach;
						if($PM_STATUS != "")
						{
							$PM_NAME	= ": $First_Name $Last_Name ($PM_STATUS)";
						}
						else
						{
							$PM_NAME	= ": $First_Name $Last_Name";
						}
					}
					
					$HRD_EMPID		= $row->HRD_EMPID;
					$HRD_NAME		= '';
					if($HRD_EMPID != '')
					{
						$First_Name = "";
						$Last_Name = "";
						$sqlEMPD	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$HRD_EMPID'";
						$resEMPD	= $this->db->query($sqlEMPD)->result();
						foreach($resEMPD as $rowEMPD) :
							$First_Name = $rowEMPD ->First_Name;
							$Last_Name 	= $rowEMPD ->Last_Name;
						endforeach;
						if($PM_STATUS != "")
						{
							$HRD_NAME	= ": $First_Name $Last_Name ($PM_STATUS)";
						}
						else
						{
							$HRD_NAME	= ": $First_Name $Last_Name";
						}
					}
					
					$DIR_EMPCODE	= $row->DIR_EMPCODE;
					$DIR_NAME		= $row->DIR_NAME;
					if($DIR_EMPCODE != '')
					{
						$First_NameD = "";
						$Last_NameD = "";
						$sqlEMPD	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$DIR_EMPCODE'";
						$resEMPD	= $this->db->query($sqlEMPD)->result();
						foreach($resEMPD as $rowEMPD) :
							$First_NameD 	= $rowEMPD ->First_Name;
							$Last_NameD		= $rowEMPD ->Last_Name;
						endforeach;
						$DIR_NAME	= ": $First_NameD $Last_NameD";
					}
					$STATUS_DOK		= $row->STATUS_DOK;
					$BORROW_EMP		= $row->BORROW_EMP;
					$BORROW_NM		= "";
					if($BORROW_EMP != '')
					{
						$First_NameB = "";
						$Last_NameB = "";
						$sqlEMPD	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$BORROW_EMP'";
						$resEMPD	= $this->db->query($sqlEMPD)->result();
						foreach($resEMPD as $rowEMPD) :
							$First_NameB 	= $rowEMPD ->First_Name;
							$Last_NameB		= $rowEMPD ->Last_Name;
						endforeach;
						$BORROW_NM	= ": $First_NameB $Last_NameB";
					}
					$HRDOC_NOTE		= $row->HRDOC_NOTE;
					if($HRDOC_NOTE == "")
					{
						if($PRJCODE != "KTR")
						{
							$HRDOC_NOTE	= "$PRJCODE ($PRJNAME) : $HRDOCJML $HRDOCJNS $PM_NAME $DIR_NAME $BORROW_NM";
						}
						else
						{
							$HRDOC_NOTE	= "$HRDOCJML $HRDOCJNS $PM_NAME $DIR_NAME $BORROW_NM";
						}
					}
					else
					{
						$HRDOC_NOTE	= "$PRJCODE : $HRDOC_NOTE : $HRDOCJML $HRDOCJNS $PM_NAME $DIR_NAME $BORROW_NM";
					}
					$PRJCODE		= $row->PRJCODE;
					$PRJCODE		= $row->PRJCODE;
					$PRJCODE		= $row->PRJCODE;
					$PRJCODE		= $row->PRJCODE;
					//$TRXDATEa		= $row->HRDOC_CREATED;
					//$TRXDATE		= date('Y-m-d',strtotime($TRXDATEa));
					
					$secURLPI		= site_url('c_project/hrdocument/update/?id='.$this->url_encryption_helper->encode_url($HRDOCNO));
                	?>   	
						<tr>
                            <td nowrap> <?php echo anchor("$secURLPI",$HRDOCNO,array('class' => 'update')).' '; ?> </td>
                            <td nowrap> <?php echo $HRDOCCODE; ?> </td>
                            <td nowrap style="text-align:center"> <?php echo $TRXDATE; ?> </td>
                            <td nowrap style="text-align:center"><?php echo $END_DATE; ?></td>
                            <td><?php echo "$HRDOC_NOTE"; ?></td>
                            <td style=" <?php if($doc_parent != 'D0243') { ?> display:none <?php } ?>"><?php echo $HRD_NAME; ?></td>
                            <td style="text-align:center; text-transform:uppercase"> <?php echo $HRDOCLOK; ?> </td>
                            <td style="text-align:center"><?php echo $HRDOCTYPED; ?></td>
                            <td style="text-align:center" nowrap>
                                <?php
                                    if($HRDOC_NAME == '')
                                    {
										$secUplURL		= site_url('c_project/hrdocument/hrdocproject_upload/?id='.$this->url_encryption_helper->encode_url($HRDOCNO));
                                        ?>
                                            <input type="hidden" name="secUplURL_<?php echo $myNewNo; ?>" id="secUplURL_<?php echo $myNewNo; ?>" value="<?php echo $secUplURL; ?>"/>
                                            <a href="javascript:void(null);" onClick="selectPICT(<?php echo $myNewNo; ?>);" data-skin="skin-green" class="btn btn-success btn-xs" title="Upload Document">
                                            	<i class="fa fa-upload"></i>
                                            </a>
                                        <?php
                                    }
                                    else
                                    {
                                        $FileUpName = $HRDOC_NAME;
										$secUplURL	= site_url('c_project/hrdocument/hrdocproject_upload/?id='.$this->url_encryption_helper->encode_url($HRDOCNO));
                                        ?>
                                            <input type="hidden" name="FileUpName<?php echo $myNewNo; ?>" id="FileUpName<?php echo $myNewNo; ?>" value="<?php echo $FileUpName; ?>" />
                                            <a href="javascript:void(null);" onClick="typeOpenNewTab(<?php echo $myNewNo; ?>);" data-skin="skin-green" class="btn btn-success btn-xs" title="Download Document">
                                            	<i class="fa fa-eye"></i>
                                            </a>
                                        <?php
                                    }
                                ?>
							</td>
						</tr>
					<?php 
				endforeach; 
			}
			?>
        </tbody>
        <tr>
            <td colspan="9" nowrap>
            <?php
				if($DAU_WRITE == 1)
				{
					$secAddURL	= site_url('c_project/hrdocument/add/?id='.$this->url_encryption_helper->encode_url($doc_code));
					//echo anchor("$secAddURL",'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add [ + ]" />&nbsp;&nbsp;');
					echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>&nbsp;');
				}
				
                echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
            ?>
            </td>
        </tr>
   	</table>
    </div>
    <!-- /.box-body -->
</div>
  <!-- /.box -->
</div>
<?php
	$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if($DefEmp_ID == 'D15040004221')
		echo "<font size='1'><i>$act_lnk</i></font>";
?>
</body>

</html>
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