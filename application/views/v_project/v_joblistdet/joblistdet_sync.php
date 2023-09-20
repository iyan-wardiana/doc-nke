<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 September 2019
 * File Name	= joblistdet_sync.php
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

$selPRJCODE		= $PRJCODE;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.css'; ?>">
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

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;List of Journal
    <small><?php //echo $PRJNAME; ?></small>
  </h1><br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->

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
                                        <th style="vertical-align:middle; text-align:center" width="2%" nowrap>NO</th>
                                        <th style="vertical-align:middle; text-align:center" width="5%" nowrap>ID</th>
                                        <th style="vertical-align:middle; text-align:center" width="18%" nowrap>Journal Code</th>
                                        <th width="14%" nowrap="nowrap" style="vertical-align:middle; text-align:center">Item Code</th>
                                        <th width="17%" nowrap="nowrap" style="vertical-align:middle; text-align:center">Account</th>
                                        <th width="12%" style="vertical-align:middle; text-align:center">Debit </th>
                                        <th width="11%" style="vertical-align:middle; text-align:center">Kredit</th>
                                        <th width="10%" style="vertical-align:middle; text-align:center">SUM</th>
                                        <th style="vertical-align:middle; text-align:center" width="11%" nowrap>Last Update</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <?php
                                    // UPDATE STATUS
                                        $sqlRESS	= "UPDATE tbl_journaldetail A, tbl_journalheader B
															SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date
														WHERE A.JournalH_Code = B.JournalH_Code";
                                        $this->db->query($sqlRESS);
                                     
									 // PROSES 1 : Penggunaan Material
                                        $sqlJOURN	= "SELECT A.JournalH_Code, A.Acc_Id, B.JournalType, A.JOBCODEID, A.ITM_CODE, 
															A.ITM_VOLM, A.ITM_PRICE, A.JournalD_Debet, A.JournalD_Kredit 
														FROM tbl_journaldetail A
															INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
																AND A.GEJ_STAT = 3
														WHERE A.proj_Code = '$PRJCODE' AND A.JournalD_Debet > 0";
                                        $resJOURNC	= $this->db->query($sqlJOURN)->result();
										foreach($resJOURNT1 as $rowJ1) :
											$JOBCODEID	= $rowJ1->JOBCODEID;
											$ITM_CODE	= $rowJ1->ITM_CODE;
											$ITM_VOLM	= $rowJ1->ITM_VOLM;
											$ITM_PRICE	= $rowJ1->ITM_PRICE;
											$ITM_USEDAM	= $rowJ1->JournalD_Debet;
											
											if($JournalType == 'AU')
											{
											}
											elseif($JournalType == 'CPRJ')
											{
												$updITM	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLM, ITM_OUTP = ITM_OUTP + $ITM_PRICE,
																UM_VOLM = UM_VOLM + $ITM_VOLM, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAM
															WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
												$this->db->query($updITM);
												
												$updITM	= "UPDATE tbl_joblist_detail SET OPN_QTY = OPN_QTY + $ITM_VOLM,
																OPN_AMOUNT = OPN_AMOUNT + $ITM_USEDAM,
																ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
															WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
												$this->db->query($updITM);
											}
											elseif($JournalType == 'GEJ')
											{
												if($JOBCODEID != '' && $ITM_CODE != '')
												{
													$updITM	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLM, ITM_OUTP = ITM_OUTP + $ITM_PRICE,
																	UM_VOLM = UM_VOLM + $ITM_VOLM, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAM
																WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
													$this->db->query($updITM);
													
													$updITM	= "UPDATE tbl_joblist_detail SET OPN_QTY = OPN_QTY + $ITM_VOLM,
																	OPN_AMOUNT = OPN_AMOUNT + $ITM_USEDAM,
																	ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
																WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
													$this->db->query($updITM);
												}
												elseif($JOBCODEID != '' && $ITM_CODE == '')
												{
													$updITM	= "UPDATE tbl_joblist_detail SET OPN_QTY = OPN_QTY + $ITM_VOLM,
																	OPN_AMOUNT = OPN_AMOUNT + $ITM_USEDAM,
																	ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
																WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
													$this->db->query($updITM);
												}
												elseif($JOBCODEID == '' && $ITM_CODE != '')
												{
													$updITM	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLM, ITM_OUTP = ITM_OUTP + $ITM_PRICE,
																	UM_VOLM = UM_VOLM + $ITM_VOLM, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAM
																WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
													$this->db->query($updITM);
												}
											}
											elseif($JournalType == 'CHO')
											{
												$updITM	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_VOLM, ITM_OUTP = ITM_OUTP + $ITM_PRICE,
																UM_VOLM = UM_VOLM + $ITM_VOLM, UM_AMOUNT = UM_AMOUNT + $ITM_USEDAM
															WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
												$this->db->query($updITM);
												
												$updITM	= "UPDATE tbl_joblist_detail SET OPN_QTY = OPN_QTY + $ITM_VOLM,
																OPN_AMOUNT = OPN_AMOUNT + $ITM_USEDAM,
																ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM + $ITM_USEDAM
															WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
												$this->db->query($updITM);
											}
											elseif($JournalType == 'O-EXP')
											{
											}
											elseif($JournalType == 'OPN')
											{
											}
											elseif($JournalType == 'UM')
											{
											}
										endforeach;
									 
									                           
                                    // COUNT TOTAL ROW
										// MISALNYA ADA $startJ = 3500 - 10000
                                        //$sqlJOURNC	= "tbl_journaldetail A WHERE A.GEJ_STAT IN (3, 9)";
                                        $sqlJOURNC	= "tbl_journaldetail A 
															INNER JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number
																AND B.Account_Category = '$AccCateg'
																-- AND B.PRJCODE = '$PRJCODE'
																AND B.PRJCODE = A.proj_Code
                                                            WHERE A.GEJ_STAT = 3";
                                        $resJOURNC	= $this->db->count_all($sqlJOURNC);
                                        if($resJOURNC > 1000)
                                        {
                                            $startJ		= $resJOURNC - 1000;
                                        }
                                        else
                                        {
                                            $startJ		= 0;
                                        }
										
                                    // TOTAL DEBET KREDIT
                                        $TOTD		= 0;
                                        $TOTK		= 0;
										$SumRowB	= 0;
                                        if($resJOURNC > 1000)
                                        {
                                            $sqlJOURNT1	= "SELECT
                                                                A.JournalH_Code,
																A.proj_Code,
                                                                A.Acc_Id,
                                                                B.Account_Class,
                                                                A.Base_Debet,
                                                                A.Base_Kredit,
                                                                A.LastUpdate
                                                            FROM
                                                                tbl_journaldetail A
																	INNER JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number
																		AND B.Account_Category = '$AccCateg'
																		-- AND B.PRJCODE = '$PRJCODE'
																		AND B.PRJCODE = A.proj_Code
                                                            WHERE
                                                                A.GEJ_STAT = 3
                                                            ORDER BY
                                                                A.LastUpdate,
                                                                A.JournalH_Code ASC LIMIT $startJ";
                                            $resJOURNT1	= $this->db->query($sqlJOURNT1)->result();
                                            foreach($resJOURNT1 as $rowJ1) :
                                                $journCode	= $rowJ1->JournalH_Code;
                                                $AccId		= $rowJ1->Acc_Id;
                                                $AccClass1	= $rowJ1->Account_Class;
                                                $proj_Code1	= $rowJ1->proj_Code;
                                                $BaseDebet	= $rowJ1->Base_Debet;
                                                $TOTD		= $TOTD + $BaseDebet;
                                                $BaseKredit	= $rowJ1->Base_Kredit;
                                                $TOTK		= $TOTK + $BaseKredit;
												$SumRowB	= $SumRowB + $BaseDebet - $BaseKredit;
                                                
												if($JRNSET == 1)
												{
													$syncPRJ	= '';
													$sqlSyns	= "SELECT syncPRJ FROM tbl_chartaccount 
																	WHERE PRJCODE = '$proj_Code1' AND Account_Number = $AccId
																		AND Account_Category = '$AccCateg'";
													$resSyns	= $this->db->query($sqlSyns)->result();
													foreach($resSyns as $rowSYNC) :
														$syncPRJ= $rowSYNC->syncPRJ;
													endforeach;
																							
													if($AccClass1 == 3 || $AccClass1 == 4)
													{
														$PRJCODE1	= '';
														$sqlPrj1	= "SELECT PRJCODE FROM tbl_project WHERE PRJSTAT = 1";
														$resPrj1	= $this->db->query($sqlPrj1)->result();
														foreach($resPrj1 as $rowPrj1) :
															$PRJCODE1	= $rowPrj1->PRJCODE;
															$sqlUCOA1	= "UPDATE tbl_chartaccount SET
																				Base_Debet = Base_Debet + $BaseDebet, 
																				Base_Debet2 = Base_Debet2 + $BaseDebet, 
																				Base_Kredit = Base_Kredit + $BaseKredit, 
																				Base_Kredit2 = Base_Kredit2 + $BaseKredit
																			WHERE PRJCODE = '$PRJCODE1' AND Account_Number = $AccId";
															$this->db->query($sqlUCOA1);
														endforeach;
													}
													else
													{			
														$getSPLIT 	= explode("~",$syncPRJ);
														foreach($getSPLIT as $prj)
														{
															$sqlUCOA1	= "UPDATE tbl_chartaccount SET
																				Base_Debet = Base_Debet + $BaseDebet, 
																				Base_Debet2 = Base_Debet2 + $BaseDebet, 
																				Base_Kredit = Base_Kredit + $BaseKredit, 
																				Base_Kredit2 = Base_Kredit2 + $BaseKredit
																			WHERE PRJCODE = '$prj' AND Account_Number = $AccId";
															$this->db->query($sqlUCOA1);
														}
													}
													
													$sqlUCOB	= "UPDATE tbl_journaldetail SET isChecked = 1
																	WHERE proj_Code = '$proj_Code1' AND Acc_Id = '$AccId'
																		AND JournalH_Code = '$journCode'";
													$this->db->query($sqlUCOB);
												}
                                            endforeach;
                                        }
										//return false;
										if($resJOURNC > 1000)
										{
											?>
												<tr>
													<td style="text-align:center" colspan="5">S E B E L U M N Y A</td>
													<td style="text-align:right"><?php echo number_format($TOTD, 4); ?></td>
													<td style="text-align:right"><?php echo number_format($TOTK, 4); ?></td>
													<td style="text-align:right"><?php echo number_format($SumRowB, 4); ?></td>
													<td>&nbsp;</td>
												</tr>
											<?php
										}
										$totCount	= 0;
	
										$SumRow		= 0;
										$TotalAmD	= 0;
										$TotalAmK	= 0;
										$sqlJOURNAL	= "SELECT
															A.JournalH_Code,
															A.Acc_Id,
															B.Account_Class,
															A.proj_Code,
															A.Base_Debet,
															A.Base_Kredit,
															A.LastUpdate
														FROM
															tbl_journaldetail A
																INNER JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number
																	AND B.Account_Category = '$AccCateg'
																	-- AND B.PRJCODE = '$PRJCODE'
																	AND B.PRJCODE = A.proj_Code
														WHERE
															A.GEJ_STAT = 3
														ORDER BY
															A.LastUpdate,
															A.JournalH_Code ASC LIMIT $startJ, $resJOURNC";
										$resJOURNAL	= $this->db->query($sqlJOURNAL)->result();
										foreach($resJOURNAL as $rowJ) :				
											$totCount 		= $totCount + 1;
											//$JournalD_Id	= $rowJ->JournalD_Id;
											$JournalH_Code	= $rowJ->JournalH_Code;
											$proj_Code2		= $rowJ->proj_Code;
											$Acc_Id			= $rowJ->Acc_Id;
											$AccClass2		= $rowJ->Account_Class;
											$Base_Debet		= $rowJ->Base_Debet;
											$TotalAmD		= $TotalAmD + $Base_Debet;
											$Base_Kredit	= $rowJ->Base_Kredit;
											$TotalAmK		= $TotalAmK + $Base_Kredit;
											$SumRow			= $SumRow + $Base_Debet - $Base_Kredit;
											//$LastUpdate		= date('d M Y', strtotime($rowJ->LastUpdate));
											$LastUpdate		= $rowJ->LastUpdate;
											
											if($JRNSET == 1)
											{
												$syncPRJ2	= '';
												$sqlSyns2	= "SELECT syncPRJ FROM tbl_chartaccount 
																WHERE PRJCODE = '$proj_Code2' AND Account_Number = $Acc_Id
																	AND Account_Category = '$AccCateg'";
												$resSyns2	= $this->db->query($sqlSyns2)->result();
												foreach($resSyns2 as $rowSYNC2) :
													$syncPRJ2	= $rowSYNC2->syncPRJ;
												endforeach;
																							
												if($AccClass2 == 3 || $AccClass2 == 4)
												{
													$PRJCODE2	= '';
													$sqlPrj2	= "SELECT PRJCODE FROM tbl_project WHERE PRJSTAT = 1";
													$resPrj2	= $this->db->query($sqlPrj2)->result();
													foreach($resPrj2 as $rowPrj2) :
														$PRJCODE2	= $rowPrj2->PRJCODE;
														$sqlUCOA	= "UPDATE tbl_chartaccount SET
																			Base_Debet = Base_Debet + $Base_Debet, 
																			Base_Debet2 = Base_Debet2 + $Base_Debet, 
																			Base_Kredit = Base_Kredit + $Base_Kredit, 
																			Base_Kredit2 = Base_Kredit2 + $Base_Kredit
																		WHERE PRJCODE = '$PRJCODE2' AND Account_Number = $Acc_Id";
														$this->db->query($sqlUCOA);
													endforeach;
												}
												else
												{			
													$getSPLIT2 	= explode("~",$syncPRJ2);
													foreach($getSPLIT2 as $prj2)
													{
														$sqlUCOA	= "UPDATE tbl_chartaccount SET
																			Base_Debet = Base_Debet + $Base_Debet, 
																			Base_Debet2 = Base_Debet2 + $Base_Debet, 
																			Base_Kredit = Base_Kredit + $Base_Kredit, 
																			Base_Kredit2 = Base_Kredit2 + $Base_Kredit
																		WHERE PRJCODE = '$prj2' AND Account_Number = $Acc_Id";
														$this->db->query($sqlUCOA);
													}
												}
												
												$sqlUCOB	= "UPDATE tbl_journaldetail SET isChecked = 1
																WHERE proj_Code = '$proj_Code2' AND Acc_Id = '$Acc_Id'
																	AND JournalH_Code = '$JournalH_Code'";
												$this->db->query($sqlUCOB);
											}
											?>
											<tr>
												<td><?php echo $totCount; ?></td>
												<td><?php //echo $JournalD_Id; ?></td>
												<td><?php echo $JournalH_Code; ?></td>
												<td><?php echo $LastUpdate; ?></td>
												<td><?php echo $Acc_Id; ?></td>
												<td style="text-align:right"><?php echo number_format($Base_Debet, 4); ?></td>
												<td style="text-align:right"><?php echo number_format($Base_Kredit, 4); ?></td>
												<td style="text-align:right"><?php echo number_format($SumRow, 4); ?></td>
												<td><?php //echo $Base_Kredit; ?></td>
											</tr>
											<?php
										endforeach;
										//$DevDK		= $TotalAmD - $TotalAmK;
										$DevDK		= ($TotalAmD+$TOTD) - ($TotalAmK+$TOTK);
                                ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td style="text-align:right"><?php echo number_format($TotalAmD+$TOTD, 6); ?></td>
                                    <td style="text-align:right"><?php echo number_format($TotalAmK+$TOTK, 6); ?></td>
                                    <td style="text-align:right"><?php echo number_format($DevDK, 6); ?></td>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    </div>
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

<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://datatables.net/download/build/nightly/jquery.dataTables.js"></script>
<script>
	$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
		//"scrollX": true,
		//"autoWidth": false,
		"filter": true,
        "ajax": "<?php echo site_url('c_gl/cgeje0b28t18/get_AllData/?id='.$PRJCODE)?>",
        "type": "POST",
		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
		"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
		"columnDefs": [	{ targets: [0,1,3,4,5], className: 'dt-body-center' }
					  ],
		"language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
		} );
	} );
  
	function getValueNo(thisVal)
	{
		myValue = thisVal.value;myMR_Number
		document.getElementById('myMR_Number').value = myValue;
	}
	
	function printD(row)
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