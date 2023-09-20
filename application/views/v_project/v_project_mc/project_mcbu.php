<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 16 September 2018
 * File Name	= project_mcbu.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 		= $this->session->userdata['FlagUSER'];

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

if(isset($_POST['submitSrch']))
{
	$MC_CODE	= $_POST['selDocNumb'];
	$selDocNumb	= $_POST['selDocNumb'];
}
else
{
	$MC_CODE	= '';
	$selDocNumb	= '';
}
	
// Project List
$sqlPLC	= "tbl_project";
$resPLC	= $this->db->count_all($sqlPLC);

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;
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
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Number')$Number = $LangTransl;
		if($TranslCode == 'ManualNumber')$ManualNumber = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'ChargeFiled')$ChargeFiled = $LangTransl;
		if($TranslCode == 'ChargeApproved')$ChargeApproved = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'PaidStatus')$PaidStatus = $LangTransl;
		if($TranslCode == 'CheckTheList')$CheckTheList = $LangTransl;
		if($TranslCode == 'MCList')$MCList = $LangTransl;
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
	$sqlNEW	= "tbl_mcheader WHERE MC_STAT = '1' AND PRJCODE = '$PRJCODE'";
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
			$insDC	= "INSERT INTO tbl_dash_transac (PRJ_CODE, PRJ_VALUE, TOT_MC_N)
						VALUES ('$PRJCODE', '$PRJCOST', '$resNEW')";
			$this->db->query($insDC);
	}
	else
	{
		// SAVE TO PROFITLOSS
			$updDC	= "UPDATE tbl_dash_transac SET TOT_MC_N = '$resNEW' WHERE PRJ_CODE = '$PRJCODE'";
			$this->db->query($updDC);
	}

	$sqlCON	= "tbl_mcheader WHERE MC_STAT = '2' AND PRJCODE = '$PRJCODE'";
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
			$insDC	= "INSERT INTO tbl_dash_transac (PRJ_CODE, PRJ_VALUE, TOT_MC_C)
						VALUES ('$PRJCODE', '$PRJCOST', '$resCON')";
			$this->db->query($insDC);
	}
	else
	{
		// SAVE TO PROFITLOSS
			$updDC	= "UPDATE tbl_dash_transac SET TOT_MC_C = '$resCON' WHERE PRJ_CODE = '$PRJCODE'";
			$this->db->query($updDC);
	}

	$sqlAPP	= "tbl_mcheader WHERE MC_STAT = '3' AND PRJCODE = '$PRJCODE'";
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
			$insDC	= "INSERT INTO tbl_dash_transac (PRJ_CODE, PRJ_VALUE, TOT_MC_A)
						VALUES ('$PRJCODE', '$PRJCOST', '$resAPP')";
			$this->db->query($insDC);
	}
	else
	{
		// SAVE TO PROFITLOSS
			$updDC	= "UPDATE tbl_dash_transac SET TOT_MC_A = '$resAPP' WHERE PRJ_CODE = '$PRJCODE'";
			$this->db->query($updDC);
	}
*/

?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/mc.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h2_title; ?>
    <small><?php echo $PRJNAME; ?></small>
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
	
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>
<!-- Main content -->

<script>
	function getValueNo(thisVal)
	{
		myValue = thisVal.value;
		document.getElementById('selDocNumb').value = myValue;
		chooseDocNumb(thisVal);
	}
	
	function chooseDocNumb(thisVal)
	{
		document.frmsrch.submitSrch.click();
	}
</script>
<div class="box">
    <!-- /.box-header -->
<div class="box-body">
<form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
	<input type="text" name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:150px; text-align:left" value="<?php echo $PRJCODE; ?>" />
	<input type="text" name="selDocNumb" id="selDocNumb" class="form-control" style="max-width:150px; text-align:left" value="<?php echo $selDocNumb; ?>" />
    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
</form>
<div class="search-table-outter">
<form name="isfrmList" id="isfrmList" action="" method=POST>
      <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
  		<thead>
            <tr>
                <th width="10%" style="text-align:center" nowrap><?php echo $Code ?>  / <?php echo $Number ?> </th>
                <th width="21%" style="text-align:center" nowrap> <?php echo $ManualNumber ?> </th>
                <th width="40%" style="text-align:center" nowrap><?php echo $Description ?> </th>
                <th width="4%" <?php if($CATTYPE == 'isMC') { ?> style="display:none" <?php } ?>><?php echo $ChargeFiled ?> </th>
                <th width="6%" <?php if($CATTYPE == 'isMC') { ?> style="display:none" <?php } ?>><?php echo $ChargeApproved ?> </th>
                <th width="4%" style="text-align:center" nowrap><?php echo $Date ?>  </th>
                <th width="5%" style="text-align:center" nowrap><?php echo $EndDate ?> </th>
                <th width="0%" style="text-align:center" nowrap><?php echo $Status ?> </th>
                <th width="1%" style="text-align:center" nowrap><?php echo $PaidStatus ?> </th>
                <th width="1%" style="text-align:center;" nowrap>&nbsp;</th>
        	</tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			$j = 0;
			if($recordcount >0)
			{
				foreach($viewmc as $row) :
					$myNewNo 		= ++$i;
					$MC_CODE		= $row->MC_CODE;
					$MC_MANNO		= $row->MC_MANNO;
					$MC_MANNOD		= $row->MC_MANNO;
					if($MC_MANNOD == '0')
					{
						$MC_MANNOD	= 'Not Set';
					}
					else
					{
						$MC_MANNOD		= $row->MC_MANNO;
					}
					
					$MC_DATE		= $row->MC_DATE;
					$MC_ENDDATE		= $row->MC_ENDDATE;
					$MC_CHECKD		= $row->MC_CHECKD;
					$PRJCODE		= $row->PRJCODE;
					$PRJNAME		= $row->PRJNAME;
					$MC_PROG		= $row->MC_PROG;
					$MC_PROGVAL		= $row->MC_PROGVAL;
					$MC_NOTES		= $row->MC_NOTES;
					$MC_STAT		= $row->MC_STAT;
					
					if($MC_STAT == 3)
					{
						// Check untuk bulan yang sama
							$MC_DATEY	= date('Y',strtotime($MC_DATE));
							$MC_DATEM	= (int)date('m',strtotime($MC_DATE));
						// BUAT TANGGAL AKHIR BULAN PER MC
							$LASTDATE	= date('Y-m-t', strtotime($MC_DATE));
							
						$sqlPL	= "tbl_profitloss WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
						$resPL	= $this->db->count_all($sqlPL);
						if($resPL == 0)
						{
							// GET PRJECT DETAIL			
								$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
								$resPRJ	= $this->db->query($sqlPRJ)->result();
								foreach($resPRJ as $rowPRJ) :
									$PRJNAME 	= $rowPRJ->PRJNAME;
									$PRJCOST 	= $rowPRJ->PRJCOST;
								endforeach;
								
							// GET MC MAX STEP PER MONTH
								$PROGMC_P	= 0;
								$PROGMC_R	= 0;
								$PROGMC_A	= 0;
								$PROGMC_B	= 0;
								$PROGCONTT_B= 0;
								$sqlTOTRP	= "SELECT MC_STEP, MC_PROG, MC_PROGVAL, MC_PROGAPP, MC_PROGAPPVAL
												FROM tbl_mcheader
												WHERE PRJCODE = '$PRJCODE'
													AND YEAR(MC_DATE) = $MC_DATEY AND MONTH(MC_DATE) = $MC_DATEM
													AND MC_STAT = 3
													AND MC_STEP = (SELECT MAX(B.MC_STEP) FROM tbl_mcheader B 
														WHERE B.PRJCODE = '$PRJCODE' 
													AND YEAR(MC_DATE) = $MC_DATEY AND MONTH(MC_DATE) = $MC_DATEM)";
								$resTOTRP	= $this->db->query($sqlTOTRP)->result();
								foreach($resTOTRP as $rowTOTRP) :
									$PROGMC_P	= $rowTOTRP->MC_PROGAPP;	// REALISASI PROGRESS PENGAJUAN YANG DI-APPROVE - P
									$PROGMC_R 	= $rowTOTRP->MC_PROG;		// REALISASI PROGRESS PENGAJUAN = PROGRESS FISIK
									$PROGMC_A 	= 0;						// 
									$PROGMC_B	= $rowTOTRP->MC_PROGVAL;	// REALISASI PROGRESS MC
									$PROGCONTT_B= $rowTOTRP->MC_PROGAPPVAL;	// REALISASI KONTRAKTUIL B
								endforeach;
							
							// SAVE TO PROFITLOSS
								$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, PROGMC_R, PROGMC_P, 
												PROGMC_A, 
												PROGMC_B, PROGCONTT_B)
											VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$PROGMC_R', '$PROGMC_P', 
												'$PROGMC_A', 
												'$PROGMC_B', '$PROGCONTT_B')";
								//* hold $this->db->query($insPL);
						}
						else
						{
							// GET MC MAX STEP PER MONTH
								$PROGMC_P	= 0;
								$PROGMC_R	= 0;
								$PROGMC_A	= 0;
								$PROGMC_B	= 0;
								$PROGCONTT_B= 0;
								$sqlTOTRP	= "SELECT MC_STEP, MC_PROG, MC_PROGVAL, MC_PROGAPP, MC_PROGAPPVAL
												FROM tbl_mcheader
												WHERE PRJCODE = '$PRJCODE'
													AND YEAR(MC_DATE) = $MC_DATEY AND MONTH(MC_DATE) = $MC_DATEM
													AND MC_STAT = 3
													AND MC_STEP = (SELECT MAX(B.MC_STEP) FROM tbl_mcheader B 
														WHERE B.PRJCODE = '$PRJCODE' 
													AND YEAR(MC_DATE) = $MC_DATEY AND MONTH(MC_DATE) = $MC_DATEM)";
								$resTOTRP	= $this->db->query($sqlTOTRP)->result();
								foreach($resTOTRP as $rowTOTRP) :
									$PROGMC_P	= $rowTOTRP->MC_PROGAPP;	// REALISASI PROGRESS PENGAJUAN YANG DI-APPROVE - P
									$PROGMC_R 	= $rowTOTRP->MC_PROG;		// REALISASI PROGRESS PENGAJUAN = PROGRESS FISIK
									$PROGMC_A 	= 0;						// 
									$PROGMC_B	= $rowTOTRP->MC_PROGVAL;	// REALISASI PROGRESS MC
									$PROGCONTT_B= $rowTOTRP->MC_PROGAPPVAL;	// REALISASI KONTRAKTUIL B
								endforeach;
							
							// SAVE TO PROFITLOSS
								$updPL	= "UPDATE tbl_profitloss SET PROGMC_R = '$PROGMC_R',  PROGMC_P = '$PROGMC_P', 
												PROGMC_A = '$PROGMC_A',
												PROGMC_B = '$PROGMC_B', PROGCONTT_B = '$PROGCONTT_B'
											WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
								//* hold $this->db->query($updPL);
						}
					}
					
					if($MC_STAT == 0)
					{
						$MC_STATDes = "fake";
						$STATCOL	= 'danger';
					}
					elseif($MC_STAT == 1)
					{
						$MC_STATDes = "New";
						$STATCOL	= 'warning';
					}
					elseif($MC_STAT == 2)
					{
						$MC_STATDes = "Confirm";
						$STATCOL	= 'primary';
					}
					elseif($MC_STAT == 3)
					{
						$MC_STATDes = "Approve";
						$STATCOL	= 'success';
					}
					elseif($MC_STAT == 4)
					{
						$MC_STATDes = "Revise";
						$STATCOL	= 'warning';
					}
					elseif($MC_STAT == 5)
					{
						$MC_STATDes = "Rejected";
						$STATCOL	= 'danger';
					}
					elseif($MC_STAT == 6)
					{
						$MC_STATDes = "Close";
						$STATCOL	= 'primary';
					}
					elseif($MC_STAT == 7)
					{
						$MC_STATDes = "Waiting";
						$STATCOL	= 'warning';
					}
					elseif($MC_STAT == 9)
					{
						$MC_STATDes = "Void";
						$STATCOL	= 'danger';
					}
					else
					{
						$MC_STATDes = "Not Range";
						$STATCOL	= 'danger';
					}
					
					$resPatStatD	= "Outstanding";
					$sqlPayStat		= "tbl_projinv_header WHERE PINV_SOURCE = '$MC_CODE'";
					$resPatStat		= $this->db->count_all($sqlPayStat);
					if($resPatStat > 0)
					{
						$resPatStatD	= "Payed";
					}
						
					if ($j==1) {
						echo "<tr class=zebra1>";
						$j++;
					} else {
						echo "<tr class=zebra2>";
						$j--;
					}
					?>
						<td nowrap> <?php print $MC_CODE; ?> </td>
						<td nowrap> <?php print $MC_MANNOD; ?></td>
						<td>&nbsp; </td>
						<td <?php if($CATTYPE == 'isMC') { ?> style="display:none" <?php } ?> nowrap>
						  <?php
								echo $MC_NOTES;
							?></td>
						<td <?php if($CATTYPE == 'isMC') { ?> style="display:none" <?php } ?>>&nbsp;</td>
						<td style="text-align:center" nowrap>
							<?php
								$date = new DateTime($MC_DATE);
								echo $date->format('d F Y');
							?>        </td>
						<td style="text-align:center" nowrap>
							<?php
								$date = new DateTime($MC_ENDDATE);
								echo $date->format('d F Y');
							?> </td>
						<td nowrap style="text-align:center">
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
								<?php 
                                    echo "$MC_STATDes";
                                 ?>
                             </span>
                        </td>
						<td nowrap style="text-align:center">                        
                            <?php
								if($resPatStat == 0)
								{
									?>
                                    <span class="label label-danger" style="font-size:11px">
										<?php 
                                            echo $resPatStatD;
                                         ?>
                                     </span>
                                    <?php
								}
								elseif($resPatStat > 0)
								{
									?>
                            			<img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/paid-stamp.png'; ?>" width="30" height="20" title="Processing">
                                	<?php
								}
								$secUpd	= site_url('c_project/c_mc180c2c_bu/u180c2cpmc/?id='.$this->url_encryption_helper->encode_url($MC_CODE));
							?>
                        </td>
						<td style="text-align:center" nowrap>
                            <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </a>
                            <a href="avascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo; ?>')">
                                <i class="glyphicon glyphicon-print"></i>
                            </a>
                            <a href="" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('<?php echo $sureDelete; ?>')" <?php if($MC_STAT > 1) { ?>disabled="disabled" <?php } ?>>
                                <i class="glyphicon glyphicon-trash"></i>
                            </a>
                        </td>
					</tr>
					<?php 
				endforeach; 
			}
		?>
        </tbody>
        <tr>
            <td colspan="10" style="text-align:left">
            <?php
                $secURLPDoc		= site_url('c_project/c_mc180c2c_bu/printdocument/?id='.$this->url_encryption_helper->encode_url($selDocNumb));
                $secURLEDoc		= site_url('c_project/c_mc180c2c_bu/editdocument/?id='.$this->url_encryption_helper->encode_url($selDocNumb));
                $secAddURLMC	= site_url('c_project/c_mc180c2c_bu/a180c2cddmc/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                $secAddURLSI	= site_url('c_project/c_mc180c2c_bu/addSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                $secAddCERSI	= site_url('c_project/c_mc180c2c_bu/addCERSI/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				if($ISAPPROVE == 1)
					$ISCREATE	= 1;
					
				if($ISCREATE == 1)
				{
					if($CATTYPE == 'isMC')
					{
						$valPrint	= "Print MC";						
						echo anchor("$secAddURLMC",'<button type="button" class="btn btn-primary "><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;MC</button>&nbsp;');
					}
					else
					{
						$valPrint	= "Print SI";
						//echo anchor("$secAddURLSI",'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add SI [ + ]" />&nbsp;&nbsp;');
						
						echo anchor("$secAddURLSI",'<button type="button" class="btn btn-primary "><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;SI</button>');
					}
				}
 					echo anchor("$backURL",'&nbsp;<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
            ?></td>
        </tr>
   	</table>
</form>
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
</script>
    
<script>
	function addSICODE(thisValue)
	{
		SICODEL	= document.getElementsByName('SICODE').length;
		j		= 0;
		
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
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>