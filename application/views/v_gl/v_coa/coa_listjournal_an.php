<?php
/* 
 	* Author		= Dian Hermanto
 	* Create Date	= 20 November 2017
 	* File Name	= coa.php
 	* Location		= -
*/
date_default_timezone_set("Asia/Jakarta");
$dateNow	= date('YmdHis');
$dateNow1	= date('Y-m-d H:i:s');

$jrnY		= date('Y');

if(isset($_POST['submitDL']))
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=COA_$dateNow.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$vers     	= $this->session->userdata['vers'];
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$Emp_ID 	= $this->session->userdata['Emp_ID'];
$appBody    = $this->session->userdata['appBody'];

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

?>
<!DOCTYPE html>
<html>
  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Anomali Jurnal</title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers 	= $this->session->userdata['vers'];

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
	  	<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/pbar/css/cssprogress.css'; ?>">
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

	<style>
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }
	
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
    </style>
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
			if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
			if($TranslCode == 'GeneralJournal')$GeneralJournal = $LangTransl;
		endforeach;
	?>
	
	<?php
		$comp_color = $this->session->userdata('comp_color');
    ?>

    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    	<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/book.png'; ?>" style="max-width:40px; max-height:40px" >Daftar Anomali Jurnal
		  	</h1>
			</section>
		</section>

        <section class="content">
			<div class="box">
			    <div class="box-body">
			        <div class="row">
			            <div class="col-md-12">
		                    <div class="box-header with-border">
		                        <h3 class="box-title"><?php echo $GeneralJournal; ?></h3>
		                        <div class="box-tools pull-right">
		                            <span class="label label-danger"><?php //echo "$Approved : $resCAPPH "; ?></span>
		                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                            </button>
		                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
		                            </button>
		                        </div>
		                    </div>
		                    <div class="box-body">
		                        <div class="search-table-outter">
		                            <table id="example1" class="table table-bordered table-striped" width="100%">
		                                <thead>
		                                    <tr>
		                                        <th style="vertical-align:middle; text-align:center" width="4%" nowrap>NO</th>
		                                        <th style="vertical-align:middle; text-align:center" width="7%" nowrap>ID</th>
		                                        <th style="vertical-align:middle; text-align:center" width="18%" nowrap>Journal Code</th>
		                                        <th width="7%" nowrap="nowrap" style="vertical-align:middle; text-align:center">Last Update  </th>
		                                        <th width="14%" nowrap="nowrap" style="vertical-align:middle; text-align:center">Account</th>
		                                        <th width="13%" style="vertical-align:middle; text-align:center">Debit </th>
		                                        <th width="14%" style="vertical-align:middle; text-align:center">Kredit</th>
		                                        <th width="9%" style="vertical-align:middle; text-align:center">SUM</th>
		                                        <th style="vertical-align:middle; text-align:center" width="5%" nowrap>SYS </th>
		                                        <th style="vertical-align:middle; text-align:center" width="5%" nowrap>DB </th>
		                                        <th style="vertical-align:middle; text-align:center" width="4%" nowrap>Deviasi</th>
		                                    </tr>
		                                </thead>
		                                <tbody>
		                                </tbody>
		                                <?php
		                                    // START : TOTAL DEBET KREDIT
		                                        $TOTD		= 0;
		                                        $TOTK		= 0;
												$SumRowB	= 0;
												$totCount	= 0;
												
												$sqlJOURNT1	= "SELECT JournalH_Code, SUM(Base_Debet) AS TOTD, SUM(Base_Kredit) AS TOTK
																FROM tbl_journaldetail GROUP BY JournalH_Code HAVING ROUND(TOTD, 2) != ROUND(TOTK, 2)";
												$resJOURNT1	= $this->db->query($sqlJOURNT1)->result();
												foreach($resJOURNT1 as $rowJ1) :
													$totCount 	= $totCount + 1;
													$journCode1	= $rowJ1->JournalH_Code;
												
													$sqlJOURNT2	= "SELECT
																		A.JournalH_Code,
																		A.Manual_No,
																		A.proj_Code,
																		A.Acc_Id,
																		A.Base_Debet,
																		A.Base_Kredit,
																		A.LastUpdate
																	FROM
																		tbl_journaldetail A
																	WHERE
																		A.JournalH_Code = '$journCode1'
																	ORDER BY
																		A.JournalH_Code,
																		A.LastUpdate ASC";
													$resJOURNT2	= $this->db->query($sqlJOURNT2)->result();
													foreach($resJOURNT2 as $rowJ2) :
														$journCode	= $rowJ2->JournalH_Code;
														$journMCode	= $rowJ2->Manual_No;
														$LastUpdate	= $rowJ2->LastUpdate;
														$AccId		= $rowJ2->Acc_Id;
														//$AccClass1	= $rowJ2->Account_Class;
														$proj_Code1	= $rowJ2->proj_Code;
														$BaseDebet	= $rowJ2->Base_Debet;
														$TOTD		= $TOTD + $BaseDebet;
														$BaseKredit	= $rowJ2->Base_Kredit;
														$TOTK		= $TOTK + $BaseKredit;
														$SumRowB	= $SumRowB + $BaseDebet - $BaseKredit;
														?>
															<tr>
																<td><?php echo $totCount; ?></td>
																<td><?php echo $journCode; ?></td>
																<td><?php echo $journMCode; ?></td>
																<td><?php echo $LastUpdate; ?></td>
																<td><?php echo $AccId; ?></td>
																<td style="text-align:right"><?php echo number_format($BaseDebet, 4); ?></td>
																<td style="text-align:right"><?php echo number_format($BaseKredit, 4); ?></td>
																<td style="text-align:right"><?php echo number_format($SumRowB, 4); ?></td>
																<td><?php //echo $Base_Kredit; ?></td>
																<td><?php //echo $JournalD_Id; ?></td>
																<td><?php //echo $JournalD_Id; ?></td>
															</tr>
														<?php
													endforeach;
												endforeach;
		                                    // END : TOTAL DEBET KREDIT
											$DevDK		= $TOTD - $TOTK;
		                                ?>
		                                <tr>
		                                    <td>&nbsp;</td>
		                                    <td>&nbsp;</td>
		                                    <td>&nbsp;</td>
		                                    <td>&nbsp;</td>
		                                    <td>&nbsp;</td>
		                                    <td style="text-align:right"><?php echo number_format($TOTD, 6); ?></td>
		                                    <td style="text-align:right"><?php echo number_format($TOTK, 6); ?></td>
		                                    <td style="text-align:right"><?php echo number_format($DevDK, 6); ?></td>
		                                    <td>&nbsp;</td>
		                                    <td>&nbsp;</td>
		                                    <td>&nbsp;</td>
		                                </tr>
		                            </table>
		                        </div>
		                    </div>
			            </div>
			        </div>
			    </div>
			</div>
		</section>
	</body>
</html>
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