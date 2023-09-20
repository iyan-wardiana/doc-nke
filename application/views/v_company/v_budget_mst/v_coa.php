<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 November 2017
 * File Name	= coa.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$Emp_ID 	= $this->session->userdata['Emp_ID'];

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

$decFormat		= 2;

$selPRJCODE		= $selPRJCODE;

if(isset($_POST['submitPRJ']))
{
	$selPRJCODE = $_POST['selPRJCODE'];
}

$selPRJSYNC	= '';
if(isset($_POST['submitSYNC']))
{
	$selPRJSYNC = $_POST['selPRJSYNC'];
	$SYNC_PRJ	= $selPRJSYNC;
	
	// START PROCEDUR - RESET ORDER
		$sqlRES01 	= "UPDATE tbl_chartaccount SET Account_Level = 0 WHERE LENGTH(Account_Number) = 1";
		$this->db->query($sqlRES01);
		
		$sqlRES02 	= "UPDATE tbl_chartaccount SET ORD_ID = 0 WHERE PRJCODE = '$SYNC_PRJ'";
		$this->db->query($sqlRES02);
		
		$ORD_ID		= 0;
		$sqlRES03	= "SELECT Account_Number, isLast FROM tbl_chartaccount WHERE PRJCODE = '$SYNC_PRJ' AND Account_Level = 0";
		$resRES03 	= $this->db->query($sqlRES03)->result();
		foreach($resRES03 as $rowRES03) :
			$ORD_ID			= $ORD_ID+1;
			$Account_N03	= $rowRES03->Account_Number;
			$isLast03		= $rowRES03->isLast;
			
			$sqlRESORD 	= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID WHERE Account_Number = '$Account_N03' AND PRJCODE = '$SYNC_PRJ'";
			$this->db->query($sqlRESORD);
			if($isLast03 == 0)
			{
				$sqlRES03A	= "SELECT Account_Number, isLast FROM tbl_chartaccount
								WHERE Acc_DirParent = '$Account_N03' AND PRJCODE = '$SYNC_PRJ' ORDER BY Account_Number";											
				$sqlRES03A	= $this->db->query($sqlRES03A)->result();
				foreach($sqlRES03A as $rowRES03A):
					$ORD_ID			= $ORD_ID+1;
					$Account_N3A	= $rowRES03A->Account_Number;
					$isLast3A		= $rowRES03A->isLast;
					
					$sqlRESORD 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID 
										WHERE Account_Number = '$Account_N3A' AND PRJCODE = '$SYNC_PRJ'";
					$this->db->query($sqlRESORD);
					if($isLast3A == 0)
					{
						$sqlRES03B	= "SELECT Account_Number, isLast FROM tbl_chartaccount
										WHERE Acc_DirParent = '$Account_N3A' AND PRJCODE = '$SYNC_PRJ'
										ORDER BY Account_Number";											
						$sqlRES03B	= $this->db->query($sqlRES03B)->result();
						foreach($sqlRES03B as $rowRES03B):
							$ORD_ID			= $ORD_ID+1;
							$Account_N3B	= $rowRES03B->Account_Number;
							$isLast3B		= $rowRES03B->isLast;
							
							$sqlRESORD 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID 
												WHERE Account_Number = '$Account_N3B' AND PRJCODE = '$SYNC_PRJ'";
							$this->db->query($sqlRESORD);
							if($isLast3B == 0)
							{
								$sqlRES03C	= "SELECT Account_Number, isLast FROM tbl_chartaccount
												WHERE Acc_DirParent = '$Account_N3B' AND PRJCODE = '$SYNC_PRJ' ORDER BY Account_Number";											
								$sqlRES03C	= $this->db->query($sqlRES03C)->result();
								foreach($sqlRES03C as $rowRES03C):
									$ORD_ID			= $ORD_ID+1;
									$Account_N3C	= $rowRES03C->Account_Number;
									$isLast3C		= $rowRES03C->isLast;
									
									$sqlRESORD 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID 
														WHERE Account_Number = '$Account_N3C' AND PRJCODE = '$SYNC_PRJ'";
									$this->db->query($sqlRESORD);
									if($isLast3C == 0)
									{
										$sqlRES03D	= "SELECT Account_Number, isLast FROM tbl_chartaccount
														WHERE Acc_DirParent = '$Account_N3C' AND PRJCODE = '$SYNC_PRJ'
														ORDER BY Account_Number";											
										$sqlRES03D	= $this->db->query($sqlRES03D)->result();
										foreach($sqlRES03D as $rowRES03D):
											$ORD_ID			= $ORD_ID+1;
											$Account_N3D	= $rowRES03D->Account_Number;
											$isLast3D		= $rowRES03D->isLast;
											
											$sqlRESORD 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID 
																WHERE Account_Number = '$Account_N3D' AND PRJCODE = '$SYNC_PRJ'";
											$this->db->query($sqlRESORD);
											if($isLast3D == 0)
											{
												$sqlRES03E	= "SELECT Account_Number, isLast FROM tbl_chartaccount
																WHERE Acc_DirParent = '$Account_N3D' AND PRJCODE = '$SYNC_PRJ'
																ORDER BY Account_Number";											
												$sqlRES03E	= $this->db->query($sqlRES03E)->result();
												foreach($sqlRES03E as $rowRES03E):
													$ORD_ID			= $ORD_ID+1;
													$Account_N3E	= $rowRES03E->Account_Number;
													$isLast3E		= $rowRES03E->isLast;
													
													$sqlRESORD 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID 
																		WHERE Account_Number = '$Account_N3E' AND PRJCODE = '$SYNC_PRJ'";
													$this->db->query($sqlRESORD);
													if($isLast3E == 0)
													{
														$sqlRES03F	= "SELECT Account_Number, isLast FROM tbl_chartaccount
																		WHERE Acc_DirParent = '$Account_N3E' AND PRJCODE = '$SYNC_PRJ'
																		ORDER BY Account_Number";											
														$sqlRES03F	= $this->db->query($sqlRES03F)->result();
														foreach($sqlRES03F as $rowRES03F):
															$ORD_ID			= $ORD_ID+1;
															$Account_N3F	= $rowRES03F->Account_Number;
															$isLast3F		= $rowRES03F->isLast;
															
															$sqlRESORD 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID 
																				WHERE Account_Number = '$Account_N3F' AND PRJCODE = '$SYNC_PRJ'";
															$this->db->query($sqlRESORD);
															if($isLast3F == 0)
															{
																$sqlRES03G	= "SELECT Account_Number, isLast FROM tbl_chartaccount
																				WHERE Acc_DirParent = '$Account_N3E' AND PRJCODE = '$SYNC_PRJ'
																				ORDER BY Account_Number";											
																$sqlRES03G	= $this->db->query($sqlRES03G)->result();
																foreach($sqlRES03G as $rowRES03G):
																	$ORD_ID			= $ORD_ID+1;
																	$Account_N3G	= $rowRES03G->Account_Number;
																	$isLast3G		= $rowRES03G->isLast;
																	
																	$sqlRESORD 		= "UPDATE tbl_chartaccount SET ORD_ID = $ORD_ID 
																						WHERE Account_Number = '$Account_N3G' 
																							AND PRJCODE = '$SYNC_PRJ'";
																	$this->db->query($sqlRESORD);
																endforeach;
															}
														endforeach;
													}
												endforeach;
											}
										endforeach;
									}
								endforeach;
							}
						endforeach;
					}
				endforeach;
			}
		endforeach;
}
$myPRJCODE		= '';
if($selPRJCODE == "AllPRJ")
{
	$myrow			= 0;
	$sql 			= "SELECT A.proj_Code
						FROM tbl_employee_proj A
						LEFT JOIN tbl_project B ON B.PRJCODE = A.proj_Code
						WHERE A.Emp_ID = '$Emp_ID'";
	$result 	= $this->db->query($sql)->result();
	foreach($result as $row) :
		$myrow		= $myrow + 1;
		$PRJCODED 	= $row ->proj_Code;
		if($myrow == 1)
		{
			$myPRJCODE	= "'$PRJCODED'";
			$myPRJCODE1	= "'$PRJCODED'";
		}
		if($myrow > 1)
		{
			$myPRJCODE1	= "$myPRJCODE1, '$PRJCODED'";
			$myPRJCODE	= "$myPRJCODE1";
		}		
	endforeach;
	
	$selPRJCODE	= '';
	$sql 		= "SELECT A.proj_Code
						FROM tbl_employee_proj A
						LEFT JOIN tbl_project B ON B.PRJCODE = A.proj_Code
						WHERE A.Emp_ID = '$Emp_ID' LIMIT 1";
	$result 	= $this->db->query($sql)->result();
	foreach($result as $row) :
		$selPRJCODE 	= $row->proj_Code;
	endforeach;
	$myPRJCODE	= $selPRJCODE;
}
else
{
	$selPRJCODE	= "$selPRJCODE";
	$myPRJCODE	= $selPRJCODE;
	$PRJCODED	= $myPRJCODE;
}

if($myPRJCODE == 'AllPRJ')
	$sqlCOA		= "SELECT * FROM tbl_chartaccount WHERE Account_Category = 1";
else
	$sqlCOA		= "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$myPRJCODE' AND Account_Category = 1";

$this->db->query($sqlCOA);

$collPRJ	= "AllPRJ";
$LinkAcc	= 1;

$rowPRJL	= 0;
$sqlPRJL = "SELECT PRJLEV FROM tbl_project_budg WHERE PRJCODE = '$myPRJCODE'";
$resPRJL = $this->db->query($sqlPRJL)->result();
foreach($resPRJL as $rowPRJL) :
	$PRJLEV = $rowPRJL->PRJLEV;
endforeach;
if($PRJLEV == 1)
{
	$myPRJCODE 	= "AllPRJ";
	$sqlCOA		= "SELECT DISTINCT
						Acc_ID,
						Account_Category,
						Account_Number,
						Account_NameEn,
						Base_OpeningBalance,
						Default_Acc,
						Account_Class,
						Account_Level,
						sum(Base_OpeningBalance + Base_Debet - Base_Kredit) as tot,
						isLast
					FROM
						tbl_chartaccount
					WHERE PRJCODE = '$myPRJCODE'
					GROUP BY Account_Number
					ORDER BY ORD_ID";
	$viewCOA 	= $this->db->query($sqlCOA)->result();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?></title>
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
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Project')$EProjectdit = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Action')$Action = $LangTransl;
		if($TranslCode == 'Periode')$Periode = $LangTransl;
		if($TranslCode == 'AccountName')$AccountName = $LangTransl;
		if($TranslCode == 'Type')$Type = $LangTransl;
		if($TranslCode == 'AccountPosition')$AccountPosition = $LangTransl;
		if($TranslCode == 'Balance')$Balance = $LangTransl;
		if($TranslCode == 'Upload')$Upload = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$sureDelete	= "Anda yakin akan menghapus data ini?";
		$h1_title	= 'Daftar Akun';
	}
	else
	{
		$sureDelete	= "Are your sure want to delete?";
		$h1_title	= 'Chart of Account';
	}
	$isLoadDone	= 0;
	$isSyncDone	= 0;
?>

<script>
	function chooseProject(thisVal)
	{
		projCode = document.getElementById('selPRJCODE').value;
		document.frmsrch.submitPRJ.click();
	}
	
	function syncJournal()
	{
		document.getElementById('loading_1').style.display = '';
		projCode = document.getElementById('selPRJCODE').value;
		document.getElementById('selPRJSYNC').value	= projCode;
		document.frmsync.submitSYNC.click();
	}
</script>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
    	<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/book.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;C O A
    	<small><?php echo $h1_title; ?></small>
  	</h1>
    <ol class="breadcrumb">
    <?php
		$getCount		= "tbl_employee_proj WHERE Emp_ID = '$Emp_ID'";
		$resGetCount	= $this->db->count_all($getCount);
		
		$secSelPRJ		= site_url('c_gl/c_ch1h0fbeart/get_all_ofCOAIDX/?id='.$this->url_encryption_helper->encode_url($appName));
	?>
    <form name="frmsrch" id="frmsrch" action="<?php echo $secSelPRJ; ?>" method=POST style="display:none">
        <select name="selPRJCODE" id="selPRJCODE" class="form-control" onChange="chooseProject(this)">
        	<option value="AllPRJ" style="display:none" <?php if($selPRJCODE == "AllPRJ") { ?> selected <?php } ?>> All Project</option>
			<?php                
                if($resGetCount > 0)
                {
                    $getData		= "SELECT A.Emp_ID, A.proj_Code, B.PRJNAME 
                                        FROM tbl_employee_proj A
                                        INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
                                        WHERE A.Emp_ID = '$Emp_ID'";
                    $resGetData 	= $this->db->query($getData)->result();
                    foreach($resGetData as $rowData) :
                        $Emp_ID 	= $rowData->Emp_ID;
                        $proj_Code 	= $rowData->proj_Code;
                        $proj_Name 	= $rowData->PRJNAME;
                        ?>
                        <option value="<?php echo $proj_Code; ?>" <?php if($proj_Code == $selPRJCODE) { ?> selected <?php } ?>><?php echo "$proj_Code - $proj_Name"; ?></option>
                        <?php
                    endforeach;
                }
                else
                {
                    ?>
                        <option value="none">--- No Unit Found ---</option>
                    <?php
                }
            ?>
        </select>
        <input type="submit" name="submitPRJ" id="submitPRJ" style="display:none">
    </form>
    <form name="frmsync" id="frmsync" action="" method=POST>
        <input type="hidden" name="selPRJSYNC" id="selPRJSYNC" value="<?php echo $PRJCODED; ?>">
        <input type="submit" name="submitSYNC" id="submitSYNC" style="display:none">
    </form>
    </ol>
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
<br>
  <div class="box">
        <?php /*?><div class="box-header with-border">
            <h3 class="box-title"><?php echo $PRJNAME; ?></h3>
        </div><?php */?>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="search-table-outter">
            <table width="100%" border="0">
                <tr height="20">
                    <td align="center" style="text-align:center" width="25%">
                        <?php
                            $LinkAcc = "$myPRJCODE~1";
                            print anchor('c_gl/c_ch1h0fbeart/get_all_ofCOA/?id='.$this->url_encryption_helper->encode_url($LinkAcc),"AKTIVA",array('class' => 'update', 'onClick' => 'checkAkt()')).' ';
                        ?>
                    </td>
                    <td align="center" style="text-align:center" width="25%">
                        <?php
                            $LinkAcc = "$myPRJCODE~2";
                            print anchor('c_gl/c_ch1h0fbeart/get_all_ofCOA/?id='.$this->url_encryption_helper->encode_url($LinkAcc),"PASSIVA",array('class' => 'update', 'onClick' => 'checkAkt()')).' ';
                        ?>
                    </td>
                    <td align="center" style="text-align:center" width="25%">
                        <?php
                            $LinkAcc = "$myPRJCODE~3";
                            print anchor('c_gl/c_ch1h0fbeart/get_all_ofCOA/?id='.$this->url_encryption_helper->encode_url($LinkAcc),"MODAL",array('class' => 'update', 'onClick' => 'checkAkt()')).' ';
                        ?>
                    </td>
                    <td align="center" style="text-align:center" width="25%">
                        <?php
                            $LinkAcc = "$myPRJCODE~4";
                            print anchor('c_gl/c_ch1h0fbeart/get_all_ofCOA/?id='.$this->url_encryption_helper->encode_url($LinkAcc),"INCOME STATEMENT",array('class' => 'update', 'onClick' => 'checkAkt()')).' ';
                        ?>
                    </td>
                </tr>
                <tr height="20">
                    <td align="center" style="text-align:center">
                        <?php
                            $LinkAcc = "5";
                            $LinkAcc = "$myPRJCODE~5";
                            print anchor('c_gl/c_ch1h0fbeart/get_all_ofCOA/?id='.$this->url_encryption_helper->encode_url($LinkAcc),"COGS",array('class' => 'update', 'onClick' => 'checkAkt()')).' ';
                        ?>
                    </td>
                    <td align="center" style="text-align:center">
                        <?php
                            $LinkAcc = "6";
                            $LinkAcc = "$myPRJCODE~6";
                            print anchor('c_gl/c_ch1h0fbeart/get_all_ofCOA/?id='.$this->url_encryption_helper->encode_url($LinkAcc),"HPP",array('class' => 'update', 'onClick' => 'checkAkt()')).' ';
                        ?>
                    </td>
                    <?php
						// Kategori 7 skiped
					?>
                    <td align="center" style="text-align:center">
                        <?php
                            $LinkAcc = "8";
                            $LinkAcc = "$myPRJCODE~8";
                            print anchor('c_gl/c_ch1h0fbeart/get_all_ofCOA/?id='.$this->url_encryption_helper->encode_url($LinkAcc),"BEBAN",array('class' => 'update', 'onClick' => 'checkAkt()')).' ';
                        ?>
                    </td>
                    <td align="center" style="text-align:center">
                    	<?php
                            $LinkAcc = "9";
                            $LinkAcc = "$myPRJCODE~9";
                            echo anchor('c_gl/c_ch1h0fbeart/get_all_ofCOA/?id='.$this->url_encryption_helper->encode_url($LinkAcc),"OTHERS",array('class' => 'update', 'onClick' => 'checkAkt()')).' ';
                        ?>
                    </td>
                </tr>
            </table>
            
            <table width="100%">
                <thead>
                    <tr>
                        <th width="75%"><?php echo $AccountName; ?></th>
                        <th width="10%"><?php echo $Type; ?></th>
                        <th width="8%"><?php echo $AccountPosition; ?></th>
                        <th width="3%"><?php echo $Balance; ?></th>
                        <th width="4%">&nbsp;</th>
                    </tr>
              </thead>
                <tbody>
                    <?php 
                        $i = 0;
                        $j = 0;
						$TBL 	= 0;
                        foreach($viewCOA as $row) :
                            $Acc_ID					= $row->Acc_ID;
                            $Account_Category		= $row->Account_Category;
                            $Account_Number			= $row->Account_Number;
                            $Account_NameEn			= $row->Account_NameEn;
                            $Base_OpeningBalance	= $row->Base_OpeningBalance;
							
                           // $AccView				= "$Account_Category.$Acc_ID $Account_NameEn";
                            $AccView				= "$Account_Number $Account_NameEn";
                            
                            if($row->Default_Acc == "D")$DAName = "Debit";else $DAName = "Credit";
							
							$Account_Class			= $row->Account_Class;
                            if($Account_Class == 1)
                            { $ACName = "Header"; }
                            elseif($Account_Class == 2)
                            { $ACName = "Detail"; }
                            elseif($Account_Class == 3)
                            { $ACName = "Detail Bank"; }
                            elseif($Account_Class == 4)
                            { $ACName = "Detail Cash"; }
                            elseif($Account_Class == 5)
                            { $ACName = "Detail Cheque"; }
                            
                            $Account_Level	= $row->Account_Level;
							
                            if($row->Account_Level == 0)
                            { $LongSpace = ""; }
                            elseif($row->Account_Level == 1)
                            { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;"; }
                            elseif($row->Account_Level == 2)
                            { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                            elseif($row->Account_Level == 3)
                            { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                            elseif($row->Account_Level == 4)
                            { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                            elseif($row->Account_Level == 5)
                            { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                            elseif($row->Account_Level == 6)
                            { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                            
                            $collID		= "$Acc_ID~$myPRJCODE";
                            $secUpd		= site_url('c_gl/c_ch1h0fbeart/up1h0fbt/?id='.$this->url_encryption_helper->encode_url($collID));
                            
                            if ($j==1) {
                                echo "<tr class=zebra1>";
                                $j++;
                            } else {
                                echo "<tr class=zebra2>";
                                $j--;
                            }
                                ?>
                              	<td <?php if($Account_Class == 1) { ?> style="font-weight:bold" <?php } ?>>
								<?php
                                    print $LongSpace;
                                    print $AccView;
                                ?>   
                                </td>
                                <td <?php if($Account_Class == 1) { ?> style="font-weight:bold" <?php } ?>><?php print $ACName; ?></td>
                                <td <?php if($Account_Class == 1) { ?> style="font-weight:bold" <?php } ?>><?php print $DAName; ?></td>
                                <td style="text-align:right; <?php if($Account_Class == 1) { ?> font-weight:bold; <?php } ?>" nowrap>
                                    <?php
										if($myPRJCODE == "AllPRJ")
										{
											$ADDQUERY	= "";
										}
										else
										{
											$ADDQUERY	= "AND PRJCODE = '$myPRJCODE'";
										}
										
										if($PRJLEV == 1)
										{
											$TBL 			= $row->tot;
										}
										else
										{
											$Base_Debet 	= $row->Base_Debet;
											$Base_Kredit 	= $row->Base_Kredit;
											$TBL 			= $Base_OpeningBalance + $Base_Debet - $Base_Kredit;
										}										
										$isLast			= $row->isLast;
										// JIKA HEADER, CARI TURUNANNYA - 1
										if($isLast == 0)
										{
											$sql2A	= "SELECT Account_Number, isLast, Base_OpeningBalance, Base_Debet, Base_Kredit
														FROM tbl_chartaccount
														WHERE Acc_DirParent = '$Account_Number' 
															$ADDQUERY
															AND Account_Category = '$Account_Category'";											
											$res2A	= $this->db->query($sql2A)->result();
											$TLEV2A	= 0;
											foreach($res2A as $row2A):
												$Acc2A	= $row2A->Account_Number;
												$OpB2A	= $row2A->Base_OpeningBalance;
												$BD2A	= $row2A->Base_Debet;
												$BK2A	= $row2A->Base_Kredit;
												$Last2A	= $row2A->isLast;
												// JIKA HEADER, CARI TURUNANNYA - 2
												$TLEV3A	= 0;
												if($Last2A == 0)
												{
													$sql3A	= "SELECT Account_Number, isLast, Base_OpeningBalance, Base_Debet, 
																	Base_Kredit
																FROM tbl_chartaccount
																WHERE Acc_DirParent = '$Acc2A' 
																	$ADDQUERY
																	AND Account_Category = '$Account_Category'";											
													$res3A	= $this->db->query($sql3A)->result();
													foreach($res3A as $row3A):
														$Acc3A	= $row3A->Account_Number;
														$OpB3A	= $row3A->Base_OpeningBalance;
														$BD3A	= $row3A->Base_Debet;
														$BK3A	= $row3A->Base_Kredit;
														$Last3A	= $row3A->isLast;
														// JIKA HEADER, CARI TURUNANNYA - 3
														$TLEV4A	= 0;
														if($Last3A == 0)
														{
															$sql4A	= "SELECT Account_Number, isLast, Base_OpeningBalance,
																		Base_Debet, Base_Kredit
																		FROM tbl_chartaccount
																		WHERE Acc_DirParent = '$Acc3A' 
																			$ADDQUERY
																			AND Account_Category = '$Account_Category'";											
															$res4A	= $this->db->query($sql4A)->result();
															foreach($res4A as $row4A):
																$Acc4A	= $row4A->Account_Number;
																$OpB4A	= $row4A->Base_OpeningBalance;
																$BD4A	= $row4A->Base_Debet;
																$BK4A	= $row4A->Base_Kredit;
																$Last4A	= $row4A->isLast;	
																// JIKA HEADER, CARI TURUNANNYA - 4
																$TLEV5A	= 0;
																if($Last4A == 0)
																{
																	$sql5A	= "SELECT Account_Number, isLast, Base_OpeningBalance,
																				Base_Debet, Base_Kredit
																				FROM tbl_chartaccount
																				WHERE Acc_DirParent = '$Acc4A' 
																					$ADDQUERY
																					AND Account_Category = '$Account_Category'";											
																	$res5A	= $this->db->query($sql5A)->result();
																	foreach($res5A as $row5A):
																		$Acc5A	= $row5A->Account_Number;
																		$OpB5A	= $row5A->Base_OpeningBalance;
																		$BD5A	= $row5A->Base_Debet;
																		$BK5A	= $row5A->Base_Kredit;
																		$Last5A	= $row5A->isLast;
																		$TLEV6A	= 0;
																		if($Last5A == 0)
																		{
																			$sql6A	= "SELECT Account_Number, isLast, Base_OpeningBalance,
																						Base_Debet, Base_Kredit
																						FROM tbl_chartaccount
																						WHERE Acc_DirParent = '$Acc5A' 
																							$ADDQUERY
																							AND Account_Category = '$Account_Category'";											
																			$res6A	= $this->db->query($sql6A)->result();
																			foreach($res6A as $row6A):
																				$Acc6A	= $row6A->Account_Number;
																				$OpB6A	= $row6A->Base_OpeningBalance;
																				$BD6A	= $row6A->Base_Debet;
																				$BK6A	= $row6A->Base_Kredit;
																				$Last6A	= $row6A->isLast;
																				$TLEV7A	= 0;
																				if($Last6A == 0)
																				{
																					$sql7A	= "SELECT Account_Number, isLast, 
																								Base_OpeningBalance,
																								Base_Debet, Base_Kredit
																								FROM tbl_chartaccount
																								WHERE Acc_DirParent = '$Acc6A' 
																									$ADDQUERY
																									AND Account_Category = '$Account_Category'";											
																					$res7A	= $this->db->query($sql7A)->result();
																					foreach($res7A as $row7A):
																						$Acc7A	= $row6A->Account_Number;
																						$OpB7A	= $row7A->Base_OpeningBalance;
																						$BD7A	= $row7A->Base_Debet;
																						$BK7A	= $row7A->Base_Kredit;
																						$Last7A	= $row7A->isLast;
																						$TLEV8A	= 0;
																						$TLEV7A	= $TLEV7A + $OpB7A + $BD7A - $BK7A + $TLEV8A;
																					endforeach;
																				}
																				$TLEV6A	= $TLEV6A + $OpB6A + $BD6A - $BK6A + $TLEV7A;
																			endforeach;
																		}
																		$TLEV5A	= $TLEV5A + $OpB5A + $BD5A - $BK5A + $TLEV6A;
																	endforeach;
																}
																$TLEV4A	= $TLEV4A + $OpB4A + $BD4A - $BK4A + $TLEV5A;
															endforeach;
														}
														$TLEV3A	= $TLEV3A + $OpB3A + $BD3A - $BK3A + $TLEV4A;
													endforeach;
												}
												$TLEV2A	= $TLEV2A + $OpB2A + $BD2A - $BK2A + $TLEV3A;
											endforeach;
											$TBL	= $TBL + $TLEV2A;
										}
										$balanceValvA	= $TBL;
                                        $balanceValv 	= number_format(abs($TBL), $decFormat);
										echo "$balanceValv";
                                    ?>
                                    &nbsp;
                                </td>
                                <td style="text-align:right" nowrap>
                                    <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update Account">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a> 
                                    <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    ?>
                </tbody>
                <tr>
                    <td colspan="12">&nbsp;</td>
            	</tr>
                <tr>
                    <td colspan="12">
                        <?php
							$selAccCatg	= "$selPRJCODE~$AccCateg";
                            $secAddURL 	= site_url('c_gl/c_ch1h0fbeart/a180e2edd/?id='.$this->url_encryption_helper->encode_url($selPRJCODE));
                            $secShowJUR = site_url('c_gl/c_ch1h0fbeart/a180e25H0w/?id='.$this->url_encryption_helper->encode_url($selAccCatg));
                            if($ISCREATE == 1)
                            {								
                                echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;'.$Add.'</button>&nbsp;&nbsp;');
                            }
                            if($Emp_ID == 'D15040004221')
                            {
                                echo anchor("$secShowJUR",'<button class="btn btn-danger"><i class="glyphicon glyphicon-eye-open"></i>&nbsp;&nbsp;Show Journal</button>&nbsp;&nbsp;');
                            }
                        ?>
                	</td>
            	</tr>
          </table>
      </div>
        <!-- /.box-body -->
    </div>
    <div id="loading_1" class="overlay" style="display:none">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
    <script>
        function checkAkt()
        {
            document.getElementById('loading_1').style.display = '';
        }
    </script>
    
    <?php 
		$isLoadDone = 1;
		if($isLoadDone == 1)
		{
			?>
				<script>
					document.getElementById('loading_1').style.display = 'none';
                </script>
    		<?php
		}
	?>
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
	function getValueNo(thisVal)
	{
		myValue = thisVal.value;myMR_Number
		document.getElementById('myMR_Number').value = myValue;
	}
	function printDocument()
	{
		myVal = document.getElementById('myMR_Number').value;
		if(myVal == '')
		{
			alert('Please select one MR Number.')
			return false;
		}
		var url = '<?php echo base_url().'index.php/c_project/material_request/printdocument/';?>'+myVal;
		title = 'Select Item';
		w = 1000;
		h = 550;
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