<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 16 Februari 2017
 * File Name	= project_si.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
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

$selDocNumb			= '';
if(isset($_POST['submit']))
{
	$selDocNumb = $_POST['selDocNumb'];
}

$selDocNumbColl	= '';
$totSelect		= 0;
if(isset($_POST['submit1']))
{
	$totSelect 		= $_POST['totSelect'];
	$selDocNumbColl = $_POST['selDocNumbColl'];
	$dataSessSrc = array('selDocNumbColl' 	=> $selDocNumbColl);
	$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
	$sesNumbColl     	= $this->session->userdata['dtSessSrc1']['selDocNumbColl'];
	//echo "hahahaa $sesNumbColl";		
}
else
{
	//$sesNumbColl     	= $this->session->userdata['dtSessSrc1']['selDocNumbColl'];
	$sesNumbColl     	= $selDocNumbColl;
	//echo "hahahab $sesNumbColl";
}

$showIdxAll		= site_url('c_project/c_mc180c2c/gAlL180c2cSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

$frmSIApp		= site_url('c_project/c_mc180c2c/popSIApp/?id='.$this->url_encryption_helper->encode_url($selDocNumbColl));

$PRJC			= $PRJCODE;
$frmSIAppList	= site_url('c_project/c_mc180c2c/popSIAppList/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

$PRJCODE1 		= "$PRJCODE";
$CATTYPE		= "isSI";
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
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Print')$Print = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'ManualNumber')$ManualNumber = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'ChargeFiled')$ChargeFiled = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'ChargeApproved')$ChargeApproved = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Action')$Action = $LangTransl;
			if($TranslCode == 'SIList')$SIList = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$sureDelete	= "Anda yakin akan menghapus data ini?";
		}
		else
		{
			$sureDelete	= "Are your sure want to delete?";
		}
			
		/*
			$sqlNEW	= "tbl_siheader WHERE SI_STAT = '1' AND PRJCODE = '$PRJCODE'";
			$resNEW	= $this->db->count_all($sqlNEW);
			$sqlPRJ	= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
			$resPRJ	= $this->db->count_all($sqlPRJ);
			if($resPRJ == 0)
			{
				// GET PRJECT DETAIL			
					$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$resPRJ	= $this->db->query($sqlPRJ)->result();
					foreach($resPRJ as $rowPRJ) :
						$PRJNAME 	= $rowPRJ->PRJNAME;
						$PRJCOST 	= $rowPRJ->PRJCOST;
					endforeach;
				
				// SAVE TO DATA COUNT
					$insDC	= "INSERT INTO tbl_dash_transac (PRJ_CODE, PRJ_VALUE, TOT_SI_N)
								VALUES ('$PRJCODE', '$PRJCOST', '$resNEW')";
					$this->db->query($insDC);
			}
			else
			{
				// SAVE TO PROFITLOSS
					$updDC	= "UPDATE tbl_dash_transac SET TOT_SI_N = '$resNEW' WHERE PRJ_CODE = '$PRJCODE'";
					$this->db->query($updDC);
			}


			$sqlCON	= "tbl_siheader WHERE SI_STAT = '2' AND PRJCODE = '$PRJCODE'";
			$resCON	= $this->db->count_all($sqlCON);
			$sqlPRJ	= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
			$resPRJ	= $this->db->count_all($sqlPRJ);
			if($resPRJ == 0)
			{
				// GET PRJECT DETAIL			
					$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$resPRJ	= $this->db->query($sqlPRJ)->result();
					foreach($resPRJ as $rowPRJ) :
						$PRJNAME 	= $rowPRJ->PRJNAME;
						$PRJCOST 	= $rowPRJ->PRJCOST;
					endforeach;
				
				// SAVE TO DATA COUNT
					$insDC	= "INSERT INTO tbl_dash_transac (PRJ_CODE, PRJ_VALUE, TOT_SI_C)
								VALUES ('$PRJCODE', '$PRJCOST', '$resCON')";
					$this->db->query($insDC);
			}
			else
			{
				// SAVE TO PROFITLOSS
					$updDC	= "UPDATE tbl_dash_transac SET TOT_SI_C = '$resCON' WHERE PRJ_CODE = '$PRJCODE'";
					$this->db->query($updDC);
			}
			
			$sqlAPP	= "tbl_siheader WHERE SI_STAT = '3' AND PRJCODE = '$PRJCODE'";
			$resAPP	= $this->db->count_all($sqlAPP);
			$sqlPRJ	= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
			$resPRJ	= $this->db->count_all($sqlPRJ);
			if($resPRJ == 0)
			{
				// GET PRJECT DETAIL			
					$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$resPRJ	= $this->db->query($sqlPRJ)->result();
					foreach($resPRJ as $rowPRJ) :
						$PRJNAME 	= $rowPRJ->PRJNAME;
						$PRJCOST 	= $rowPRJ->PRJCOST;
					endforeach;
				
				// SAVE TO DATA COUNT
					$insDC	= "INSERT INTO tbl_dash_transac (PRJ_CODE, PRJ_VALUE, TOT_SI_A)
								VALUES ('$PRJCODE', '$PRJCOST', '$resAPP')";
					$this->db->query($insDC);
			}
			else
			{
				// SAVE TO PROFITLOSS
					$updDC	= "UPDATE tbl_dash_transac SET TOT_SI_A = '$resAPP' WHERE PRJ_CODE = '$PRJCODE'";
					$this->db->query($updDC);
			}
		*/
		$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$resPRJ	= $this->db->query($sqlPRJ)->result();
		foreach($resPRJ as $rowPRJ) :
			$PRJNAMEH 	= $rowPRJ->PRJNAME;
		endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <?php echo $mnName; ?>
		    <small><?php echo $PRJNAMEH; ?></small>
		  </h1>
		</section>

		<style>
			.search-table, td, th {
				border-collapse: collapse;
			}
			.search-table-outter { overflow-x: scroll; }
			
		    a[disabled="disabled"] {
		        pointer-events: none;
		    }
		</style>

        <section class="content">
			<div class="box">
				<div class="box-body">
					<form name="frmselect" id="frmselect" action="" method=POST>
						<input type="hidden" name="selDocNumb" id="selDocNumb" value="<?php echo $selDocNumb; ?>" />
					    <input type="submit" class="button_css" name="submit" id="submit" value=" search " style="display:none" />
					</form>
					<form name="frmSIApp" id="frmSIApp" action="" method=POST>
						<input type="hidden" name="selDocNumbColl" id="selDocNumbColl" value="<?php echo $sesNumbColl; ?>" />
						<input type="hidden" name="totSelect" id="totSelect" value="<?php echo $totSelect; ?>" />
						<input type="hidden" name="selSearchCat" id="selSearchCat" value="<?php echo $selSearchCat; ?>" />
					    <input type="submit" class="button_css" name="submit1" id="submit1" value=" search " style="display:none" />
					</form>
					<form name="frmSIApp2" id="frmSIApp2" action="<?php echo $frmSIApp; ?>" method=POST>
						<input type="hidden" name="selDocNumbColl" id="selDocNumbColl" value="<?php echo $selDocNumbColl; ?>" />
						<input type="hidden" name="totSelect" id="totSelect" value="<?php echo $totSelect; ?>" />
						<input type="hidden" name="selSearchCat" id="selSearchCat" value="<?php echo $selSearchCat; ?>" />
					    <input type="submit" class="button_css" name="submit2" id="submit2" value=" search " style="display:none" />
					</form>
					<div class="search-table-outter">
						<table id="example" class="table table-bordered table-striped" width="100%">
					  		<thead>
					            <tr>
									<th width="10%" style="text-align: center; vertical-align: middle;" nowrap><?php echo $Code ?> </th>
									<th width="10%" style="text-align: center; vertical-align: middle;" nowrap><?php echo $ManualNumber ?>  </th>
									<th width="5%" style="text-align: center; vertical-align: middle;" nowrap><?php echo $Date ?>  </th>
									<th width="40%" style="text-align: center; vertical-align: middle;" nowrap><?php echo $Description ?> </th>
									<th width="10%" style="text-align: center; vertical-align: middle;" nowrap><?php echo $ChargeFiled ?> </th>
									<th width="5%" style="text-align: center; vertical-align: middle;" nowrap><?php echo $Status ?> </th>
									<th width="5%" style="text-align: center; vertical-align: middle;" nowrap>&nbsp;</th>
				          		</tr>
				        	</thead>
				        	<tbody>
				        	</tbody>
				        	<tfoot>
				        	</tfoot>
				   		</table>
				    </div>
				    <br>
	            	<?php
		                $secURLPDoc		= site_url('c_project/c_mc180c2c/printdocument/?id='.$this->url_encryption_helper->encode_url($selDocNumb));
		                $secURLEDoc		= site_url('c_project/c_mc180c2c/editdocument/?id='.$this->url_encryption_helper->encode_url($selDocNumb));
		                $secAddURLMC	= site_url('c_project/c_mc180c2c/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                $secAddURLSI	= site_url('c_project/c_mc180c2c/a180c2cddsi/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                $secAddCERSI	= site_url('c_project/c_mc180c2c/addCERSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                $secAddURLSync	= site_url('c_project/c_mc180c2c/syncTable/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                if($ISCREATE)
						{
							echo anchor("$secAddURLSI",'<button type="button" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>'); echo "&nbsp;";
							$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
							if($DefEmp_ID == 'D15040004221')
							{
								//echo anchor("$secAddURLSync",'&nbsp;<button type="button"class="btn btn-success"><i class="fa fa-life-ring"></i>&nbsp;&nbsp;Sync Table</button>');echo "&nbsp;";
							}
						}
					?>
					<input type="hidden" name="myPINV_Number" id="myPINV_Number" value="<?php echo $selDocNumb; ?>" />
					<button type="button" class="btn btn-warning" onClick="getApproveSIList();" style="display:none"><i class="fa fa-print"></i>&nbsp;&nbsp;<?php echo $Print; ?></button>
	        
					<?php 
						echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
		            ?>
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

<script>
    $(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        //"scrollX": false,
        "autoWidth": true,
        "filter": true,
        "ajax": "<?php echo site_url('c_project/c_mc180c2c/get_AllDataSI/?id='.$PRJCODE)?>",
        "type": "POST",
        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
        "columnDefs": [ { targets: [5,6], className: 'dt-body-center' },
        				{ targets: [4], className: 'dt-body-right' },
                        { "width": "100px", "targets": [1] }
                      ],
        "order": [[ 2, "desc" ]],
        "language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
        } );
    } );
</script>

<script>
	function chooseProject(thisVal)
	{
		//sssss	= thisVal.value;
		//projCode = document.getElementById('selSearchproj_Code').value;
		document.frmsrch.submitSrch.click();
	}
		
	function getApproveSIList()
	{
		var url		= "<?php echo $frmSIAppList; ?>";
		var myVal 	= document.getElementById('selDocNumbColl').value;
		title = 'Select Item';
		w = 780;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		document.frmSIApp2.submit2.click();
	}
	
	function addSICODE(thisValue)
	{
		SICODEL	= document.getElementsByName('SICODE').length;
		j		= 0;
		var selDocNumbColl = document.getElementById('selDocNumbColl').value;
		//swal(selDocNumbColl)
		for(i=1; i <=SICODEL; i++)
		{
			SICODEC	= document.getElementById('SICODE'+i).checked;
			if(SICODEC == true)
			{
				j			= j + 1;
				SICODEV		= document.getElementById('SICODE'+i).value;
				if(j == 1)
				{
					SICODECOL1	= SICODEV;
					SICODECOL	= SICODEV;
				}
				else if(j > 1)

				{
					SICODECOL	= SICODECOL+'|'+SICODEV;
				}
			}
		}
		document.getElementById('totSelect').value 		= j;
		document.getElementById('selDocNumbColl').value = SICODECOL;
		document.frmSIApp.submit1.click();
	}
		
	function getApproveSI2()
	{
		var url		= "<?php echo $frmSIApp; ?>";
		var myVal 	= document.getElementById('selDocNumbColl').value;
		title = 'Select Item';
		w = 780;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		document.frmSIApp2.submit2.click();
	}
	
	function getValueNo(thisVal)
	{
		myValue = thisVal.value;
		document.getElementById('myPINV_Number').value = myValue;
		document.getElementById('selDocNumb').value = myValue;
		chooseDocNumb(thisVal);
	}
	
	function chooseDocNumb(thisVal)
	{
		document.frmselect.submit.click();
	}
	
	function printDocument()
	{
		myVal = document.getElementById('myPINV_Number').value;
		
		if(myVal == '')
		{
			swal('Please select one of Invoice Number.')
			return false;
		}
		var url = '<?php echo $secURLPDoc; ?>';
		title = 'Select Item';
		w = 800;
		h = 700;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/3)-(h/3);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
	
	function EditDocument()
	{
		myVal = document.getElementById('myPINV_Number').value;
		
		if(myVal == '')
		{
			swal('Please select one of Invoice Number.')
			return false;
		}
		var url = '<?php echo $secURLEDoc; ?>';
		title = 'Select Item';
		w = 700;
		h = 700;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
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