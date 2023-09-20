<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Februari 2017
 * File Name	= project_planning.php
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
if($decFormat == 0)
	$decFormat		= 2;

$selProject = '';
if(isset($_POST['submit']))
{
	$selProject = $_POST['selProject'];
}

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$sqlPL 	= "SELECT proj_Number, PRJCODE, PRJNAME
			FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
			ORDER BY PRJNAME";
$resPL	= $this->db->query($sqlPL)->result();
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
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'ProjectList')$ProjectList = $LangTransl;
			if($TranslCode == 'Invoice')$Invoice = $LangTransl;
		endforeach;
	?>
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
			<section class="content-header">
			<h1>
			    <?php echo $ProjectList; ?>
			    <small><?php echo $Invoice; ?></small>
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
							<table id="example1" class="table table-bordered table-striped table-responsive search-table inner">
								<thead>
						            <tr>
						              <th style="text-align:center; vertical-align:middle" width="2%">No.</th>
						              <th style="text-align:center; vertical-align:middle" width="3%" nowrap><?php echo $Code ?> </th>
						              <th style="text-align:center; vertical-align:middle" width="71%" nowrap><?php echo $ProjectName ?> </th>
						              <th style="text-align:center; vertical-align:middle" width="12%" nowrap><?php echo $StartDate ?> </th>
						              <th style="text-align:center; vertical-align:middle" width="12%" nowrap><?php echo $EndDate ?> </th>
						        </tr>
						        </thead>
						        <tbody>
								<?php 
									$i = 0;
									$j = 0;
									if($recordcount >0)
									{
									foreach($viewproject as $row) : 
										$myNewNo		= ++$i;
										$PRJCODE 		= $row->PRJCODE;
										$PRJCNUM		= $row->PRJCNUM;
										$PRJNAME		= $row->PRJNAME;
										$PRJLOCT		= $row->PRJLOCT;
										$PRJCOST		= $row->PRJCOST;
										$PRJDATE		= $row->PRJDATE;
										$myDateProj 	= $row->PRJDATE;
										$PRJEDAT		= $row->PRJEDAT;
										$PRJSTAT		= $row->PRJSTAT;
											if($PRJSTAT == 0) $PRJSTATDesc = "New";
											elseif($PRJSTAT == 1) $PRJSTATDesc = "Confirm";		
										
											if($myDateProj == '0000-00-00')
											{
												$sqlX = "SELECT PRJDATE
														FROM tbl_project WHERE PRJCODE = '$prjcode'";
												$result = $this->db->query($sqlX)->result();
												foreach($result as $rowx) :
													$PRJDATE		= $rowx->PRJDATE;
												endforeach;
											}		
										$isActif = $row->PRJSTAT;
										if($isActif == 1)
										{
											$isActDesc = 'Active';
										}
										else
										{
											$isActDesc = 'In Active';
										}
										$secURLPI	= site_url('c_project/c_project_invoice/get_last_ten_projinv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
										
										if ($j==1) {
											echo "<tr class=zebra1>";
											$j++;
										} else {
											echo "<tr class=zebra2>";
											$j--;
										}
											?>
												<td style="text-align:center; vertical-align:middle"> <?php print $myNewNo; ?>. </td>
												<td style="text-align:center; vertical-align:middle" nowrap><?php echo anchor($secURLPI,$PRJCODE);?></td>
												<td nowrap> <?php print "$PRJCODE - $PRJNAME"; ?> </td>
												<td style="text-align:center; vertical-align:middle" nowrap> <?php print $PRJDATE; ?> </td>
												<td style="text-align:center; vertical-align:middle" nowrap> <?php print $PRJEDAT; ?> </td>
											</tr>
										<?php 
										endforeach; 
									}
								?>
						        </tbody>
						   	</table>
					    </div>
					</div>
				</div>
			</section>
		</div>
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
			swal('Please select one of Project Code.')
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
			swal('Please select one of Project Code.')
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