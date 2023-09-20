<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Juli 2018
 * File Name	= profit_loss_list.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$Emp_ID 	= $this->session->userdata('Emp_ID');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

$SELPRJ		= '';
$PRJCODE	= '';
$PRJNM		= '';
$sqlPRJ 	= "SELECT A.proj_Code, B.PRJNAME
				FROM tbl_employee_proj A
					INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
				WHERE A.Emp_ID = '$Emp_ID' LIMIT 1";
$resPRJ 	= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$SELPRJ	= $rowPRJ ->proj_Code;
	$PRJNM	= $rowPRJ ->PRJNAME;
endforeach;
$PRJCODE	= $SELPRJ;

if(isset($_POST['submitPRJ']))
{
	$SELPRJ 	= $_POST['selPRJCODE'];
	$PRJCODE	= $SELPRJ;
	$sqlPRJ 	= "SELECT PRJNAME
					FROM tbl_project WHERE PRJCODE = '$SELPRJ' LIMIT 1";
	$resPRJ 	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :
		$PRJNM	= $rowPRJ ->PRJNAME;
	endforeach;
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
	if($TranslCode == 'Save')$Save = $LangTransl;
	if($TranslCode == 'Update')$Update = $LangTransl;	
	if($TranslCode == 'Back')$Back = $LangTransl;	
	if($TranslCode == 'Upload')$Upload = $LangTransl;
	
	if($TranslCode == 'Code')$Code = $LangTransl;
	if($TranslCode == 'Date')$Date = $LangTransl;
	if($TranslCode == 'Project')$Project = $LangTransl;
	if($TranslCode == 'Description')$Description = $LangTransl;
	if($TranslCode == 'User')$User = $LangTransl;
	if($TranslCode == 'Status')$Status = $LangTransl;
	if($TranslCode == 'View')$View = $LangTransl;
endforeach;
	
$isLoadDone_1	= 1;
$COAH_CODEX		= '';
$sqlCOAC		= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
$resCOAC		= $this->db->count_all($sqlCOAC);

// CHECK STATUS
$PERIODEM	= date('m');
if(isset($_POST['PRJCODEX']))	// MARKETING
{
	$PRJCODE	= $_POST['PRJCODEX'];
	
	$sqlCLR	= "tbl_profitloss WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODEM'";
	$resCLR	= $this->db->count_all($sqlCLR);
}
else
{
	$sqlCLR	= "tbl_profitloss WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODEM'";
	$resCLR	= $this->db->count_all($sqlCLR);
}
?>
<input type="hidden" name="resCLR" id="resCLR" class="textbox" value="<?php echo $resCLR; ?>" />
<?php
//if($resBoQC == 0)
if($resCLR == 0)
{
	if(isset($_POST['PRJCODEX']))	// MARKETING
	{
		$PRJCODE	= $_POST['PRJCODEX'];
		$PERIODEM	= date('m');
		$sqlGetLR	= "SELECT* FROM tbl_profitloss WHERE PRJCODE = '$PRJCODECOL' AND MONTH(PERIODE) = $PERIODEM AND LR_ISCREATED = 0";
		$resGetLR	= $this->db->query($sqlGetLR)->result();
		foreach($resGetLR as $rowLR):
			$PERIODE 		= $rowLR->PERIODE;
			$PRJCODE 		= $rowLR->PRJCODE;
			$PRJNAME 		= $rowLR->PRJNAME;
			$PRJCOST 		= $rowLR->PRJCOST;
			$PRJADD 		= $rowLR->PRJADD;
			$SIAPPVAL 		= $rowLR->SIAPPVAL;
			$PROG_PLAN 		= $rowLR->PROG_PLAN;
			$PROG_REAL 		= $rowLR->PROG_REAL;
			$INV_REAL 		= $rowLR->INV_REAL;
			$PROGMC 		= $rowLR->PROGMC;
			$PROGMC_PLAN 	= $rowLR->PROGMC_PLAN;
			$PROGMC_REAL	= $rowLR->PROGMC_REAL;
			$SI_PLAN 		= $rowLR->SI_PLAN;
			$SI_REAL 		= $rowLR->SI_REAL;
			$SI_PROYEKSI 	= $rowLR->SI_PROYEKSI;
			$MC_CAT_PLAN 	= $rowLR->MC_CAT_PLAN;
			$MC_CAT_REAL 	= $rowLR->MC_CAT_REAL;
			$MC_CAT_PROYEKSI = $rowLR->MC_CAT_PROYEKSI;
			$MC_OTH_PLAN	= $rowLR->MC_OTH_PLAN;
			$MC_OTH_REAL 	= $rowLR->MC_OTH_REAL;
			$MC_OTH_PROYEKSI= $rowLR->MC_OTH_PROYEKSI;
			$KURS_DEV_PLAN 	= $rowLR->KURS_DEV_PLAN;
			$KURS_DEV_REAL 	= $rowLR->KURS_DEV_REAL;
			$KURS_DEV_PROYEKSI = $rowLR->KURS_DEV_PROYEKSI;
			$ASSURAN_PLAN 	= $rowLR->ASSURAN_PLAN;
			$ASSURAN_REAL 	= $rowLR->ASSURAN_REAL;
			$ASSURAN_PROYEKSI = $rowLR->ASSURAN_REAL;
			$CASH_EXPENSE 	= $rowLR->CASH_EXPENSE;
			$BPP_MTR_PLAN 	= $rowLR->BPP_MTR_PLAN;
			$BPP_MTR_REAL 	= $rowLR->BPP_MTR_REAL;
			$BPP_MTR_PROYEKSI = $rowLR->BPP_MTR_PROYEKSI;
			$BPP_UPH_PLAN 	= $rowLR->BPP_UPH_PLAN;
			$BPP_UPH_REAL 	= $rowLR->BPP_UPH_REAL;
			$BPP_UPH_PROYEKSI = $rowLR->BPP_UPH_PROYEKSI;
			$BPP_ALAT_PLAN	= $rowLR->BPP_ALAT_PLAN;
			$BPP_ALAT_REAL 	= $rowLR->BPP_ALAT_REAL;
			$BPP_ALAT_PROYEKSI = $rowLR->BPP_ALAT_PROYEKSI;
			$BPP_SUBK_PLAN 	= $rowLR->BPP_SUBK_PLAN;
			$BPP_SUBK_REAL	= $rowLR->BPP_SUBK_REAL;
			$BPP_SUBK_PROYEKSI = $rowLR->BPP_SUBK_PROYEKSI;
			$BPP_BAU_PLAN 	= $rowLR->BPP_BAU_PLAN;
			$BPP_BAU_REAL 	= $rowLR->BPP_BAU_REAL;
			$BPP_BAU_PROYEKSI = $rowLR->BPP_BAU_PROYEKSI;
			$BPP_OTH_PLAN 	= $rowLR->BPP_OTH_PLAN;
			$BPP_OTH_REAL 	= $rowLR->BPP_OTH_REAL;
			$BPP_OTH_PROYEKSI = $rowLR->BPP_OTH_PROYEKSI;
			$STOCK 			= $rowLR->STOCK;
			$STOCK_MTR 		= $rowLR->STOCK_MTR;
			$STOCK_BBM 		= $rowLR->STOCK_BBM;
			$STOCK_TOOLS 	= $rowLR->STOCK_TOOLS;
			$EXP_TOOLS 		= $rowLR->EXP_TOOLS;
			$EXP_BAU_HOCAB 	= $rowLR->EXP_BAU_HOCAB;
			$EXP_BUNGA 		= $rowLR->EXP_BUNGA;
			$EXP_PPH 		= $rowLR->EXP_PPH;
			$GRAND_PROFLOS 	= $rowLR->GRAND_PROFLOS;
			$LR_CREATER 	= $rowLR->LR_CREATER;
			$LR_CREATED 	= $rowLR->LR_CREATED;
		endforeach;
			
		// INSERT INTO ITM
			/*$sqlInsCOA		= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, PRJADD, SIAPPVAL, PROG_PLAN,
								PROG_REAL, INV_REAL, PROGMC, PROGMC_PLAN, PROGMC_REAL, SI_PLAN, SI_REAL, SI_PROYEKSI, MC_CAT_PLAN,
								MC_CAT_REAL, MC_CAT_PROYEKSI, MC_OTH_PLAN, MC_OTH_REAL, MC_OTH_PROYEKSI, KURS_DEV_PLAN, KURS_DEV_REAL,
								KURS_DEV_PROYEKSI, ASSURAN_PLAN, ASSURAN_REAL, ASSURAN_PROYEKSI, CASH_EXPENSE, BPP_MTR_PLAN,
								BPP_MTR_REAL, BPP_MTR_PROYEKSI, BPP_UPH_PLAN, BPP_UPH_REAL, BPP_UPH_PROYEKSI, BPP_ALAT_PLAN,
								BPP_ALAT_REAL, BPP_ALAT_PROYEKSI, BPP_SUBK_PLAN, BPP_SUBK_REAL, BPP_SUBK_PROYEKSI, BPP_BAU_PLAN,
								BPP_BAU_REAL, BPP_BAU_PROYEKSI, BPP_OTH_PLAN, BPP_OTH_REAL, BPP_OTH_PROYEKSI, BPP_GAJI_A, BPP_GAJI_B,
								BPP_GAJI_C, STOCK, STOCK_MTR, STOCK_BBM, STOCK_TOOLS, EXP_TOOLS, EXP_BAU_HOCAB, EXP_BUNGA, EXP_PPH,
								GRAND_PROFLOS, CREATER, CREATED)
								VALUES ('$PRJCODE', '$Acc_ID', '$Account_Class', '$Account_Number', '$Account_NameEn',
									'$Account_NameId', '$Account_Category', '$Account_Level', '$Acc_DirParent', '$Acc_ParentList', 
									'$Acc_StatusLinked', '$Acc_Enable', '$Company_ID', '$Default_Acc', '$Currency_id',
									'$Base_OpeningBalance', '$IsInterCompany', '$isCostComponent', '$isOnDuty', '$isFOHCost', '$NEEDCC',
									'$AllowGEJ', '$Link_Report', '$isLast')";*/
		$sqlInsCOA		= "UPDATE tbl_profitloss SET LR_ISCREATED = 1 WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODEM'";
		$this->db->query($sqlInsCOA);
		$isLoadDone_1	= 1;
	}
}
else
{
	//echo "Sudah diimport sebelumnya";
	//return false;
}

$empUSER	= '';
$sqlUSER	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$Emp_ID' LIMIT 1";
$resUSER	= $this->db->query($sqlUSER)->result();
foreach($resUSER as $rowUSER) :
	$First_Name1= $rowUSER->First_Name;
	$Last_Name1	= $rowUSER->Last_Name;
	$empUSER1	= "$First_Name1 $Last_Name1";
	$empUSER	= cut_text ("$empUSER1", 20);
endforeach;
$PERIODE	= date('Y-m-d');
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
		<?php echo $title; ?>
        <small><?php echo $h2_title; ?></small>
    </h1>
    <ol class="breadcrumb">
    <?php
		$getCount		= "tbl_employee_proj WHERE Emp_ID = '$Emp_ID'";
		$resGetCount	= $this->db->count_all($getCount);
		
		$getCountLR		= "tbl_profitloss WHERE PRJCODE = '$PRJCODE'";
		$countLR		= $this->db->count_all($getCountLR);
	?>
	<script>
        function chooseProject(thisVal)
        {
            document.frmsrchPRJ.submitPRJ.click();
        }
    </script>
    <form name="frmsrchPRJ" id="frmsrchPRJ" action="" method=POST>
        <select name="selPRJCODE" id="selPRJCODE" class="form-control" onChange="chooseProject(this)">
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
                        <option value="<?php echo $proj_Code; ?>" <?php if($proj_Code == $SELPRJ) { ?> selected <?php } ?>><?php echo "$proj_Code - $proj_Name"; ?></option>
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
    
    <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
        <input type="text" name="PRJCODEX" id="PRJCODEX" class="textbox" value="<?php echo $PRJCODE; ?>" />
        <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
    </form>
    </ol>
</section><br>
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
    <div class="box-body">
        <div class="search-table-outter">
              <table id="example1" class="table table-bordered table-striped" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align:middle; text-align:center" width="3%"><input name="chkAll" id="chkAll" type="checkbox" value="" style="display:none" /></th>
                        <th width="12%" nowrap style="vertical-align:middle; text-align:center"><?php echo $Code ?></th>
                        <th width="13%" nowrap="nowrap" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
                        <th width="37%" nowrap style="vertical-align:middle; text-align:center;"><?php echo $Description; ?></th>
                        <th width="14%" style="vertical-align:middle; text-align:center; display:none"><?php echo $Description; ?></th>
                        <th width="12%" nowrap style="vertical-align:middle; text-align:center"><?php echo $title; ?></th>
                        <th width="5%" nowrap style="vertical-align:middle; text-align:center"><?php echo $User; ?></th>
                        <th style="vertical-align:middle; text-align:center" width="4%" nowrap><?php echo $View ?></th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    $i 			= 0;
                    $j 			= 0;	
					$myNewNo1	= 0;					
                    if($countLR >0)
                    {
						$getDataLR	= "SELECT LR_CODE, PERIODE, PRJCODE, GRAND_PROFLOS, LR_CREATER, LR_CREATED
										FROM tbl_profitloss WHERE PRJCODE = '$PRJCODE'";
						$resDataLR	= $this->db->query($getDataLR)->result();
                        foreach($resDataLR as $rowLR) :				
                            $myNewNo1 		= ++$i;
                            $LR_CODE		= $rowLR->LR_CODE;
                            $PERIODE		= $rowLR->PERIODE;
							$datePERIODEM	= date('M Y', strtotime($PERIODE));
                            $PRJCODE		= $rowLR->PRJCODE;
                            $LR_DESC		= '';
                            $GRAND_PROFLOS	= $rowLR->GRAND_PROFLOS;
                            $LR_CREATER		= $rowLR->LR_CREATER;
                            $LR_CREATED		= $rowLR->LR_CREATED;
								
                            // CARI TOTAL REGUSEST BUDGET APPROVED
                                $empName		= '';
                                $sqlJOBDESC		= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$LR_CREATED' LIMIT 1";
                                $resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
                                foreach($resJOBDESC as $rowJOBDESC) :
                                    $First_Name	= $rowJOBDESC->First_Name;
                                    $Last_Name	= $rowJOBDESC->Last_Name;
									$empName1	= "$First_Name $Last_Name";
									$empName	= cut_text ("$empName1", 20);
                                endforeach;
							
							$LAST_PERIODE	= date('Y-m', strtotime($PERIODE));
                            
                            if ($j==1) {
                                echo "<tr class=zebra1>";
                                $j++;
                            } else {
                                echo "<tr class=zebra2>";
                                $j--;
                            }
                                ?>
                                        <td style="text-align:center">
                                        	<?php echo $myNewNo1; ?>.
                                        </td>
                                        <td nowrap>
                                            <?php echo $LR_CODE;?>
                                        </td>
                                        <td style="text-align:center" nowrap>
										<?php
                                            echo $PERIODE;
                                        ?>
                                        </td>
                                        <td nowrap>
										<?php echo "$h2_title $title Period : $datePERIODEM"; ?>
                                        </td>
                                        <td style="display:none">&nbsp;</td>
                                        <td style="text-align:right"><?php echo number_format($GRAND_PROFLOS, 2); ?>&nbsp;</td>
                                        <td style="text-align:center"><?php echo strtolower($empName); ?></td>
                                        <?php
                                            $CollID		= "$PRJCODE~$PERIODE";
											$secVWURL	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vw/?id='.$this->url_encryption_helper->encode_url($CollID));
											$secDLURL	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005dl/?id='.$this->url_encryption_helper->encode_url($CollID));
                                        ?>
                                        <td style="text-align:center" nowrap>
                                            <a href="javascript:void(null);" onClick="createLR_doc('<?php echo $PRJCODE; ?>');" data-skin="skin-green" class="btn btn-warning btn-xs" title="<?php echo $createLR; ?>" disabled="disabled">
                                                <i class="glyphicon glyphicon-refresh"></i>
                                            </a>
                        					<a href="javascript:void(null);" onClick="viewLR_doc('<?php echo $secDLURL; ?>');" data-skin="skin-green" class="btn btn-primary btn-xs" title="<?php echo $viewLR; ?>">
                                                <i class="glyphicon glyphicon-download-alt"></i>
                                            </a>
                                            <a href="javascript:void(null);" onClick="viewLR_doc('<?php echo $secVWURL; ?>');" data-skin="skin-green" class="btn btn-info btn-xs" title="<?php echo $viewLR; ?>">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php 
                        endforeach; 
                    }
					$nextNo	= $myNewNo1 + 1;
					$dateCREATED	= date('Y-m-d H:i:s');
					$datePERIODEM	= date('M Y');
					$NEW_PERIODE	= date('Y-m');
                ?>
                <tr style="display:none">
                	<td style="text-align:center">
						<?php echo $nextNo; ?>.
                    </td>
                    <td style="font-style:italic; text-align:center" nowrap>&nbsp;-- not set --</td>
                    <td style="text-align:center" nowrap><?php echo $dateCREATED; ?></td>
                    <td nowrap><?php echo "$h2_title $title Period : $datePERIODEM"; ?></td>
                    <td style="display:none">&nbsp;</td>
                    <td style="text-align:right"><?php echo number_format(0, 2); ?>&nbsp;</td>
                    <td style="text-align:center"><?php echo strtolower($empUSER); ?></td>
					<?php
                        $CollID		= "$PRJCODE~$PERIODE";
                        $secVWURL	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vw/?id='.$this->url_encryption_helper->encode_url($CollID));
                        $secDLURL	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005dl/?id='.$this->url_encryption_helper->encode_url($CollID));
                    ?>
                    <td style="text-align:center" nowrap>
                        <a href="javascript:void(null);" onClick="createLR_doc('<?php echo $PRJCODE; ?>');" data-skin="skin-green" class="btn btn-warning btn-xs" title="<?php echo $createLR; ?>">
                            <i class="glyphicon glyphicon-refresh"></i>
                        </a>
                        <a href="javascript:void(null);" onClick="viewLR_doc('<?php echo $secDLURL; ?>');" data-skin="skin-green" class="btn btn-primary btn-xs" title="<?php echo $viewLR; ?>">
                            <i class="glyphicon glyphicon-download-alt"></i>
                        </a>
                        <a href="javascript:void(null);" onClick="viewLR_doc('<?php echo $secVWURL; ?>');" data-skin="skin-green" class="btn btn-info btn-xs" title="<?php echo $viewLR; ?>">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
            
            <form method="post" name="sendDelete" id="sendDelete" class="form-user" action="" style="display:none">		
                <table>
                    <tr>
                        <td></td>
                        <td><a class="tombol-delete" id="delClass">Simpan</a></td>
                    </tr>
              </table>
          	</form>
      </div>
    </div>
    <div id="loading_1" class="overlay" <?php if($isLoadDone_1 == 1) { ?> style="display:none" <?php } ?>>
        <i class="fa fa-refresh fa-spin"></i>
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
  	
	var myTime;
	function createLR_doc(PRJCODE)
	{
		result 	= confirm("<?php echo $sure; ?>");
		document.getElementById('loading_1').style.display = '';
		
		myTime		= setTimeout(function(){createLR_doc1(PRJCODE)}, 1000);
	}
  
	function createLR_doc1(PRJCODE)
	{
		document.getElementById('loading_1').style.display = 'none';
		createLR_doc2(PRJCODE)
	}
  
	function createLR_doc2(PRJCODE)
	{
		document.getElementById('loading_1').style.display = 'none';
		resCLR	= document.getElementById('resCLR').value;
		
		if(resCLR == 1)
		{
			alert('<?php echo $cannot1; ?>');
			alert('<?php echo $cannot2; ?>');
			clearTimeout ();
			return false;
		}
		else
		{
			document.getElementById('loading_1').style.display = '';
			document.getElementById('PRJCODE').value = PRJCODE;
			clearTimeout ();
			document.frmsrch.submitSrch.click();
		}
	}
	
	function viewLR_doc(urlVWDoc)
	{
		title = 'Laporan Laba Rugi';
		w = 780;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(urlVWDoc, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>