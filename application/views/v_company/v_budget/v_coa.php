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

$selPRJCODE		= $selPRJCODE;

if(isset($_POST['submitPRJ']))
{
	$selPRJCODE = $_POST['selPRJCODE'];
}

$selCOACAT 	= 1;
if(isset($_POST['submitCOACAT']))
{
	$selCOACAT = $_POST['selCOACAT'];
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

$PRJCODE_HO			= '';
$sqlPRJ 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODED'";
$resPRJ 			= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJCODE_HO = $rowPRJ->PRJCODE_HO;		
endforeach;

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
        <title><?php echo $appName; ?></title>
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

	<?php
		$this->load->view('template/mna');
		//______$$this->load->view('template/topbar');
		//______$$this->load->view('template/sidebar');
		
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
			$sureResOrd	= 'Sistem akan mengatur ulang urutan COA secara otomatis. Yakin?';
			$sureResSyn	= 'Sistem akan mengatur ulang relasi akun antar budget. Yakin?';
			$sureResJD	= 'Sistem akan mengatur ulang nilai COA secara otomatis dari jurnal. Yakin?';
			$sureAnJD	= 'Sistem akan menampilkan semua anomali jurnal, akan membutuhkan waktu beberapa saat. Lanjutkan?';
		}
		else
		{
			$sureDelete	= "Are your sure want to delete?";
			$h1_title	= 'Chart of Account';
			$sureResOrd	= 'System will reset COA Order ID automatically. Are you sure?';
			$sureResSyn	= 'System will reset budget sync automatically. Are you sure?';
			$sureResJD	= 'The system will reset the COA value automatically from the journal. Sure?';
			$sureAnJD	= 'The system will display all journal anomalies, which will take a few moments. Continue?';
		}

		$isLoadDone	= 0;
		$isSyncDone	= 0;
	?>
			
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

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    	<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/book.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;C O A
		    	<small><?php echo $h1_title; ?></small>
		  	</h1>
		    <ol class="breadcrumb">
		    <?php
				$getCount		= "tbl_employee_proj WHERE Emp_ID = '$Emp_ID'";
				$resGetCount	= $this->db->count_all($getCount);
				
				$secSelPRJ		= site_url('c_comprof/c_bUd93tL15t/get_all_ofCOAIDX/?id='.$this->url_encryption_helper->encode_url($appName));
				$LinkAcc 		= "$myPRJCODE~$selCOACAT~$PRJPERIOD";
				//$secSelPRJCAT	= site_url('c_comprof/c_bUd93tL15t/get_all_ofCOA/?id='.$this->url_encryption_helper->encode_url($LinkAcc));
				$secSelPRJCAT	= site_url('c_comprof/c_bUd93tL15t/get_all_ofCOA/?id='.$this->url_encryption_helper->encode_url($LinkAcc));
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
		    <form name="frmsrchCAT" id="frmsrchCAT" action="" method=POST>
		        <select name="selCOACAT" id="selCOACAT" class="form-control select2" onChange="chooseCOACAT(this)">
		        	<option value="1" <?php if($selCOACAT == 1) { ?> selected <?php } ?>> 1 ASSET</option>
		        	<option value="2" <?php if($selCOACAT == 2) { ?> selected <?php } ?>> 2 LIABILITAS</option>
		        	<option value="3" <?php if($selCOACAT == 3) { ?> selected <?php } ?>> 3 EKUITAS</option>
		        	<option value="4" <?php if($selCOACAT == 4) { ?> selected <?php } ?>> 4 PENDAPATAN</option>
		        	<option value="5" <?php if($selCOACAT == 5) { ?> selected <?php } ?>> 5 BEBAN KONTRAK</option>
		        	<option value="6" <?php if($selCOACAT == 6) { ?> selected <?php } ?>> 6 BEBAN USAHA</option>
		        	<option value="7" <?php if($selCOACAT == 7) { ?> selected <?php } ?>> 7 PENGHASILAN LAIN-LAIN</option>
		        	<option value="8" <?php if($selCOACAT == 8) { ?> selected <?php } ?>> 8 BEBAN LIAN-LAIN</option>
		        	<!-- <option value="9" <?php if($selCOACAT == 9) { ?> selected <?php } ?>> 9 Lainnya</option> -->
		        </select>
		        <input type="submit" name="submitCOACAT" id="submitCOACAT" style="display:none">
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


        <section class="content">
			<div class="row">
				<div class="col-md-12" id="idprogbar" style="display: none;">
					<div class="cssProgress">
				      	<div class="cssProgress">
						    <div class="progress3">
								<div id="progressbarXX" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
			</div>
			<div class="box">
			    <div class="box-body">
			        <div class="search-table-outter">
			          	<table id="example" class="table table-bordered table-striped" width="100%">
				            <thead>
				                <tr>
									<th width="75%"  nowrap style="text-align:center; vertical-align:middle"><?php echo $AccountName ?> </th>
									<th width="10%" nowrap style="text-align:center; vertical-align:middle"><?php echo $Type ?> </th>
									<th width="8%" nowrap style="text-align:center; vertical-align:middle"><?php echo $AccountPosition ?> </th>
									<th width="3%"  nowrap style="text-align:center; vertical-align:middle">Balance</th>
				                    <th width="4%"  nowrap style="text-align:center; vertical-align:middle">&nbsp;</th>
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
						$selAccCatg	= "$selPRJCODE~$AccCateg";
                        //$secAddURL 	= site_url('c_comprof/c_bUd93tL15t/a180e2edd/?id='.$this->url_encryption_helper->encode_url($selPRJCODE));
                        $secShowJUR = site_url('c_comprof/c_bUd93tL15t/a180e25H0w/?id='.$this->url_encryption_helper->encode_url($selAccCatg));
			            $backURL 	= site_url('c_comprof/c_bUd93tL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($PRJCODE_HO));
						if($Emp_ID == 'D15040004221') 
						{ 
                        	echo '<button class="btn btn-info" onClick="resOrder()" title="Reset Order"><i class="glyphicon glyphicon-refresh"></i></button>&nbsp;&nbsp;';
           					echo '<button class="btn btn-warning" onClick="resSync()" title="Reset Budget Sync"><i class="fa fa-share-alt"></i></button>&nbsp;&nbsp;';
                    	}
                    	if($pgFrom == 'HO')
		            		$backURL 	= site_url('c_comprof/c_bUd93tL15t/i180c2gdx_MN246/?id='.$this->url_encryption_helper->encode_url($PRJCODE_HO));
                    	else
		            		$backURL 	= site_url('c_comprof/c_bUd93tL15t/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($PRJCODE_HO));

                		echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                    ?>
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
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>

			<div class="row">
				<div class="col-md-12" id="idprogbar2" style="display: none;">
					<div class="cssProgress">
				      	<div class="cssProgress">
						    <div class="progress3">
								<div id="progressbarXX2" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
			</div>
		</section>
		<iframe id="myiFrame" src="<?php echo base_url('__l1y/impCOA' ) ?>" style="display: none;"></iframe>
	</body>
</html>

<script>
	$(function () 
	{
		//Initialize Select2 Elements
		$(".select2").select2();
		
		//Datemask dd/mm/yyyy
		$("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
		//Datemask2 mm/dd/yyyy
		$("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
		//Money Euro
		$("[data-mask]").inputmask();
		
		//Date range picker
		$('#reservation').daterangepicker();
		//Date range picker with time picker
		$('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
		//Date range as a button
		$('#daterange-btn').daterangepicker(
			{
			  ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			  },
			  startDate: moment().subtract(29, 'days'),
			  endDate: moment()
			},
			function (start, end) {
			  $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			}
		);
		
		//Date picker
		$.fn.datepicker.defaults.format = "dd/mm/yyyy";
	    $('#datepicker').datepicker({
	      autoclose: true,
		  endDate: '+1d'
	    });
		
		//Date picker
		$('#datepicker1').datepicker({
		  autoclose: true,
		  startDate: '+0d'
		});
		
		//iCheck for checkbox and radio inputs
		$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
		  checkboxClass: 'icheckbox_minimal-blue',
		  radioClass: 'iradio_minimal-blue'
		});
		//Red color scheme for iCheck
		$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
		  checkboxClass: 'icheckbox_minimal-red',
		  radioClass: 'iradio_minimal-red'
		});
		//Flat red color scheme for iCheck
		$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
		  checkboxClass: 'icheckbox_flat-green',
		  radioClass: 'iradio_flat-green'
		});
		
		//Colorpicker
		$(".my-colorpicker1").colorpicker();
		//color picker with addon
		$(".my-colorpicker2").colorpicker();
		
		//Timepicker
		$(".timepicker").timepicker({
		  showInputs: false
		});
	});

	$(document).ready(function()
	{
	    $('#example').DataTable( 
	    {
	        "processing": true,
	        "serverSide": true,
			//"scrollX": true,
			//"autoWidth": false,
			"filter": true,
	        "ajax": "<?php echo site_url('c_comprof/c_bUd93tL15t/get_AllDataCOA/?id='.$selPRJCODE.'&tC4t='.$selCOACAT.'&pgFrom='.$pgFrom)?>",
	        "type": "POST",
			//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
			"lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
			"columnDefs": [	{ targets: [3,4], className: 'dt-body-center' }
						  ],
			"language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
		});
	});

	function chooseProject(thisVal)
	{
		projCode = document.getElementById('selPRJCODE').value;
		document.frmsrch.submitPRJ.click();
	}

	function chooseCOACAT(thisVal)
	{
		<?php echo $secSelPRJCAT; ?>
		var thePrj 	= "<?php echo $myPRJCODE; ?>";
		var theCat 	= document.getElementById('selCOACAT').value;
		var thePer 	= "<?php echo $PRJPERIOD; ?>";
		var url 	= "<?php echo $secSelPRJCAT;?>";

		// $LinkAcc 		= "$myPRJCODE~$selCOACAT~$PRJPERIOD";
		var nURL	= url+'&tC4t='+theCat;
		document.getElementById('frmsrchCAT').action = nURL;


		document.frmsrchCAT.submitCOACAT.click();
	}
	
	function syncJournal()
	{
		document.getElementById('loading_1').style.display = '';
		projCode = document.getElementById('selPRJCODE').value;
		document.getElementById('selPRJSYNC').value	= projCode;
		document.frmsync.submitSYNC.click();
	}

	function getValueNo(thisVal)
	{
		myValue = thisVal.value;myMR_Number
		document.getElementById('myMR_Number').value = myValue;
	}
	
	function resOrder()
	{
		PRJCODE 	= "<?php echo $myPRJCODE; ?>";
	    swal({
            text: "<?php echo $sureResOrd; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
				document.getElementById('idprogbar').style.display 	= '';
				document.getElementById('idprogbar2').style.display = '';
			    document.getElementById("progressbarXX").innerHTML	="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
			    document.getElementById("progressbarXX2").innerHTML	="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display 	= '';
            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'REODERCOA';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'REODERCOA';
				butSubm.submit();

		        /*$.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	document.getElementById('loading_1').style.display = 'none';
		            	swal(response, 
						{
							icon: "success",
						});
		            }
		        });*/
            } 
            else 
            {
                /*swal("<?php echo $canReset; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}
	
	function resSync()
	{
		PRJCODE 	= "<?php echo $myPRJCODE; ?>";
	    swal({
            text: "<?php echo $sureResSyn; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
				document.getElementById('idprogbar').style.display = '';
			    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display = '';
            	var collID	= PRJCODE;
		        var myarr 	= collID.split("~");

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'REODERSYNC';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'REODERSYNC';
				butSubm.submit();
            } 
            else 
            {
                /*swal("<?php echo $canReset; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}
	
    function sleep(milliseconds) { 
        let timeStart = new Date().getTime(); 
        while (true) { 
            let elapsedTime = new Date().getTime() - timeStart; 
            if (elapsedTime > milliseconds) { 
                break; 
            } 
        } 
    }

	function updStat()
	{
		var timer = setInterval(function()
		{
	       	clsBar();
      	}, 8000);
	}

	function clsBar()
	{
		document.getElementById('idprogbar').style.display = 'none';
		document.getElementById('idprogbar2').style.display = 'none';
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
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

    // Right side column. contains the Control Panel
    //______$$this->load->view('template/aside');

    //______$$this->load->view('template/js_data');

    //______$$this->load->view('template/foot');
?>