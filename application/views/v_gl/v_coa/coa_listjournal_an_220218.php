<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 Juli 2020
 * File Name	= coa_listjournal_an.php
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
?>
<!DOCTYPE html>
	<html>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
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
		
		// SYN DEBET AND CREDIT
			/*$sql01	= "SELECT A.JournalH_Code,
							SUM(A.Base_Debet) AS TOTD,
							SUM(A.Base_Kredit) AS TOTK
						FROM
							tbl_journaldetail A
						INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
						WHERE B.JournalType = 'CPRJ'
						GROUP BY A.JournalH_Code
						HAVING TOTD != TOTK";
			$res01	= $this->db->query($sql01)->result();
			foreach($res01 as $row01) :
				$JournalH_Code	= $row01->JournalH_Code;
				$TOTD			= $row01->TOTD;
				$TOTK			= $row01->TOTK;
				
				// UPDATE KREDIT DETAIL
				$sql02	= "UPDATE tbl_journaldetail SET JournalD_Kredit = $TOTD, Base_Kredit = $TOTD, COA_Kredit = $TOTD
							WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'";
				$this->db->query($sql02);
				
				// UPDATE AMOUNT IN HEADER
				$sql03	= "UPDATE tbl_journalheader SET Journal_Amount = $TOTD WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($sql03);
			endforeach;*/
			
		// SYN UNTUK JOURNAL_AMOUNT IN HEADER
			/*$sql04	= "SELECT JournalH_Code, Journal_Amount FROM tbl_journalheader WHERE GEJ_STAT IN (2,7) AND JournalType = 'CPRJ'";
			$res04	= $this->db->query($sql04)->result();
			foreach($res04 as $row04) :
				$JournalH_Code	= $row04->JournalH_Code;
				$Journal_Amount	= $row04->Journal_Amount;
				
				$TOTD	= 0;
				$sql05	= "SELECT SUM(Base_Debet) AS TOTD FROM tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code'";
				$res05	= $this->db->query($sql05)->result();
				foreach($res05 as $row05) :
					$TOTD	= $row05->TOTD;
					if($TOTD == '')
						$TOTD	= 0;
				endforeach;
				if($Journal_Amount != $TOTD)
				{
					// UPDATE AMOUNT IN HEADER
					$sql06	= "UPDATE tbl_journalheader SET Journal_Amount = $TOTD WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($sql06);
				}
			endforeach;*/
			
		// DELETE APP_HISTORY
		// difungsikan untuk mengembalikan semua dokumen yang dari New -> Aprove (Kesalahan)
			/*$sql07	= "SELECT COUNT(A.AH_CODE) AS TOTAPP, A.AH_CODE FROM tbl_approve_hist A 
						INNER JOIN tbl_journalheader B ON A.AH_CODE = B.JournalH_Code
							AND B.GEJ_STAT = 3 AND B.JournalType IN ('GEJ', 'CPRJ')
						GROUP BY AH_CODE
						HAVING TOTAPP = 1
						ORDER BY A.AH_CODE";
			$res07	= $this->db->query($sql07)->result();
			foreach($res07 as $row07) :
				$TOTAPP		= $row07->TOTAPP;
				$AH_CODE	= $row07->AH_CODE;
				
				// DELETE APP HISTORY
					$sql08	= "DELETE FROM tbl_approve_hist WHERE AH_CODE = '$AH_CODE'";
					$this->db->query($sql08);
				
				// UPDATE JOURNAL HEADER
					$sql09	= "UPDATE tbl_journalheader SET GEJ_STAT = 1, STATDESC = 'New', STATCOL = 'warning' WHERE JournalH_Code = '$AH_CODE'";
					$this->db->query($sql09);
				
				// UPDATE JOURNAL DETAIL
					$sql10	= "UPDATE tbl_journaldetail SET GEJ_STAT = 1 WHERE JournalH_Code = '$AH_CODE'";
					$this->db->query($sql10);
			endforeach;*/
	?>

	<body class="hold-transition skin-blue sidebar-mini fixed">
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
				    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;List of Journal
				    <small><?php //echo $PRJNAME; ?></small>
				  </h1>
			</section>

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
																	FROM tbl_journaldetail GROUP BY JournalH_Code HAVING TOTD != TOTK";
													$resJOURNT1	= $this->db->query($sqlJOURNT1)->result();
													foreach($resJOURNT1 as $rowJ1) :
														$totCount 	= $totCount + 1;
														$journCode1	= $rowJ1->JournalH_Code;
													
														$sqlJOURNT2	= "SELECT
																			A.JournalH_Code,
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
																	<td><?php //echo $JournalD_Id; ?></td>
																	<td><?php echo $journCode; ?></td>
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
		</div>
	</body>
</html>
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