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

$selCOAYEAR 	= date('Y');
$selPRJCODE		= $selPRJCODE;
if(isset($_POST['submitPRJ']))
{
	$selPRJCODE = $_POST['selPRJCODE'];
}

$selCOACAT 	= 1;
if(isset($_POST['submitCOACAT']))
{
	$selCOAYEAR = $_POST['selCOAYEAR'];
	$selCOACAT 	= $_POST['selCOACAT'];
}
if($selCOAYEAR == "All")
	$jrnY 	= date('Y');
else
	$jrnY 	= $selCOAYEAR;

$myPRJCODE	= '';
$selPRJSYNC	= '';

if($selPRJCODE == "AllPRJ")
{
	$sqlC 			= "tbl_employee_proj A
						LEFT JOIN tbl_project B ON B.PRJCODE = A.proj_Code
						WHERE A.Emp_ID = '$Emp_ID'";
	$resulC 	= $this->db->count_all($sqlC);
	if($resulC > 0)
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
	$selPRJCODE	= "$selPRJCODE";
	$myPRJCODE	= $selPRJCODE;
	$PRJCODED	= $myPRJCODE;
}
else
{
	$selPRJCODE	= "$selPRJCODE";
	$myPRJCODE	= $selPRJCODE;
	$PRJCODED	= $myPRJCODE;
}

$getCCCOA	= "tbl_ccoa WHERE CCOA_PRJCODE = '$myPRJCODE'";
$resCCCOA	= $this->db->count_all($getCCCOA);
if($resCCCOA == 99)	// RE-ORDERING STRUKTUR. SEMENTARA JANGAN DIJALANKAN
{
}

if($myPRJCODE == 'AllPRJ')
	$sqlCOA		= "SELECT * FROM tbl_chartaccount WHERE Account_Category = 1";
else
	$sqlCOA		= "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$myPRJCODE' AND Account_Category = 1";

$this->db->query($sqlCOA);

$collPRJ	= "AllPRJ";
$LinkAcc	= 1;

$PRJLEV		= 0;
$rowPRJL	= 0;
$sqlPRJL = "SELECT PRJLEV FROM tbl_project WHERE PRJCODE = '$myPRJCODE'";
$resPRJL = $this->db->query($sqlPRJL)->result();
foreach($resPRJL as $rowPRJL) :
	$PRJLEV = $rowPRJL->PRJLEV;
endforeach;
if($PRJLEV == 1)
{
	$myPRJCODE 	= "AllPRJ";
	$sqlCOA		= "SELECT DISTINCT
						ORD_ID,
						Acc_ID,
						Account_Category,
						Account_Number,
						Account_NameEn,
						COGSReportID,
						Base_OpeningBalance,
						Default_Acc,
						Account_Class,
						Account_Level,
						sum(Base_OpeningBalance + BaseD_$jrnY - BaseK_$jrnY) as tot,
						isLast
					FROM
						tbl_chartaccount
					WHERE PRJCODE = '$myPRJCODE'
					GROUP BY Account_Number
					ORDER BY ORD_ID";
	$viewCOA 	= $this->db->query($sqlCOA)->result();
}

/*$sql		= "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$myPRJCODE' AND Account_Category = $AccCateg  AND isHO != 2
				ORDER BY Account_Category, ORD_ID, Account_Number ASC";
$viewCOA 	= $this->db->query($sql)->result();*/
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
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
            if($TranslCode == 'Name')$Name = $LangTransl;
            if($TranslCode == 'Description')$Description = $LangTransl;
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
			if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
			if($TranslCode == 'yesDel')$yesDel = $LangTransl;
			if($TranslCode == 'cancDel')$cancDel = $LangTransl;
			if($TranslCode == 'Balance')$Balance = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$h1_title	= 'Daftar Akun';
			$sureResOrd	= 'Sistem akan mengatur ulang urutan COA secara otomatis. Yakin?';
			$sureResSyn	= 'Sistem akan mengatur ulang relasi akun antar budget. Yakin?';
			$sureResJD	= 'Sistem akan mengatur ulang nilai COA secara otomatis dari jurnal. Yakin?';
			$sureAnJD	= 'Sistem akan menampilkan semua anomali jurnal, akan membutuhkan waktu beberapa saat. Lanjutkan?';
			$sureAnJDY	= 'Sistem akan mensinkronisasi jurnal per tahun, akan membutuhkan waktu beberapa saat. Lanjutkan?';
		}
		else
		{
			$h1_title	= 'Chart of Account';
			$sureResOrd	= 'System will reset COA Order ID automatically. Are you sure?';
			$sureResSyn	= 'System will reset budget sync automatically. Are you sure?';
			$sureResJD	= 'The system will reset the COA value automatically from the journal. Sure?';
			$sureAnJD	= 'The system will display all journal anomalies, which will take a few moments. Continue?';
			$sureAnJDY	= 'The system will sync all journal per year, which will take a few moments. Continue?';
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
		
		function downTemp()
		{
			//document.frmdl.submitDL.click();
			var urlVWDoc = document.getElementById('seceXplURL_').value;
			title = 'View Bill of Quantity';
			w = 780;
			h = 550;
			//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
			var left = (screen.width/2)-(w/2);
			var top = (screen.height/2)-(h/2);
			return window.open(urlVWDoc, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		}
	</script>
	
	<?php
		$comp_color = $this->session->userdata('comp_color');
    ?>
    <form name="frmsrchCAT" id="frmsrchCAT" action="" method=POST style="display: none;">
        <input type="text" name="selCOACAT" id="selCOACAT" value="<?php echo $selCOACAT; ?>">
        <input type="text" name="selCOAYEAR" id="selCOAYEAR" value="<?php echo $selCOAYEAR; ?>">
        <input type="text" name="selPRJCODE1" id="selPRJCODE1" value="<?php echo $selPRJCODE; ?>">
        <input type="submit" name="submitCOACAT" id="submitCOACAT" style="display:none">
    </form>
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
				
				$secSelPRJ		= site_url('c_gl/c_ch1h0fbeart/get_all_ofCOAIDX/?id='.$this->url_encryption_helper->encode_url($appName));
			?>
		    <form name="frmsrch" id="frmsrch" action="<?php echo $secSelPRJ; ?>" method=POST>
		        <select name="selPRJCODE" id="selPRJCODE" class="form-control select2" onChange="chooseProject(this)">
		        	<option value="none"> --- </option>
					<?php                
		                if($resGetCount > 0)
		                {
		                    $getData		= 	"SELECT '' AS Emp_ID, A.PRJCODE AS proj_Code, A.PRJNAME FROM tbl_project A WHERE A.isHO = 1
		                    						UNION
		                    					SELECT A.Emp_ID, A.proj_Code, B.PRJNAME 
		                                        FROM tbl_employee_proj A
		                                        INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
		                                        WHERE A.Emp_ID = '$Emp_ID' AND B.isHO != 1";
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
		    <form name="frmdl" id="frmdl" action="" method=POST>
		        <input type="submit" name="submitDL" id="submitDL" style="display:none">
		    </form>
		    </ol>
		</section>

		<?php
			$seceXplURL	= site_url('c_gl/c_ch1h0fbeart/view_coaxp/?id='.$this->url_encryption_helper->encode_url($selPRJCODE));
		?>

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
      		<?php
      			$secRes		= base_url().'index.php/__l1y/resORID/?id=';
      			$secResJD	= base_url().'index.php/__l1y/resJD/?id=';
      			$secResJDY	= base_url().'index.php/__l1y/resJDY/?id=';

      			$btnColl1 	= "class='btn btn-default btn-block'";
      			$btnColl2 	= "class='btn btn-default btn-block'";
      			$btnColl3 	= "class='btn btn-default btn-block'";
      			$btnColl4 	= "class='btn btn-default btn-block'";
      			$btnColl5 	= "class='btn btn-default btn-block'";
      			$btnColl6 	= "class='btn btn-default btn-block'";
      			$btnColl8 	= "class='btn btn-default btn-block'";
      			$btnColl9 	= "class='btn btn-default btn-block'";
      			if($selCOACAT == 1)
      			{
      				$coaDesc	= "AKTIVA";
      				$btnColl1 	= "class='btn btn-default btn-block btn-primary' style='color: #FFFFFF; font-weight: bold;'";
      			}
      			elseif($selCOACAT == 2)
      			{
      				$coaDesc	= "PASIVA";
      				$btnColl2 	= "class='btn btn-default btn-block btn-primary' style='color: #FFFFFF; font-weight: bold;'";
      			}
      			elseif($selCOACAT == 3)
      			{
      				$coaDesc	= "MODAL";
      				$btnColl3 	= "class='btn btn-default btn-block btn-primary' style='color: #FFFFFF; font-weight: bold;'";
      			}
      			elseif($selCOACAT == 4)
      			{
      				$coaDesc	= "PENJUALAN";
      				$btnColl4 	= "class='btn btn-default btn-block btn-primary' style='color: #FFFFFF; font-weight: bold;'";
      			}
      			elseif($selCOACAT == 5)
      			{
      				$coaDesc	= "BEBAN OPERASIONAL";
      				$btnColl5 	= "class='btn btn-default btn-block btn-primary' style='color: #FFFFFF; font-weight: bold;'";
      			}
      			elseif($selCOACAT == 6)
      			{
      				$coaDesc	= "HPP";
      				$btnColl6 	= "class='btn btn-default btn-block btn-primary' style='color: #FFFFFF; font-weight: bold;'";
      			}
      			elseif($selCOACAT == 8)
      			{
      				$coaDesc	= "BEBAN";
      				$btnColl8 	= "class='btn btn-default btn-block btn-primary' style='color: #FFFFFF; font-weight: bold;'";
      			}
      			elseif($selCOACAT == 9)
      			{
      				$coaDesc	= "LAINNYA";
      				$btnColl9 	= "class='btn btn-default btn-block btn-primary' style='color: #FFFFFF; font-weight: bold;'";
      			}

      			$TOT_D 			= 0;
      			$TOT_K 			= 0;
      			if($selCOAYEAR == "All")
      			{
      				$qgetBal	= "SELECT SUM(Base_OpeningBalance+Base_Debet) AS TOT_D, SUM(Base_Kredit) AS TOT_K FROM tbl_chartaccount
      								WHERE PRJCODE = '$myPRJCODE' AND Account_Category = $selCOACAT";
      			}
      			else
      			{
      				$qgetBal	= "SELECT SUM(Base_OpeningBalance+BaseD_$jrnY) AS TOT_D, SUM(BaseK_$jrnY) AS TOT_K FROM tbl_chartaccount
      								WHERE PRJCODE = '$myPRJCODE' AND Account_Category = $selCOACAT";
      			}
                $rgetBal 		= $this->db->query($qgetBal)->result();
                foreach($rgetBal as $rowqBal) :
                    $TOT_D 		= $rowqBal->TOT_D;
                    $TOT_K 		= $rowqBal->TOT_K;
                endforeach;
                $TOT_B 			= abs($TOT_D - $TOT_K);

                if($TOT_D < 1000)
                {
                	$TOT_DV = number_format($TOT_D / 1, 2);
                	$TOTCOD	= "";
                }
                elseif($TOT_D < 1000000)
                {
                	$TOT_DV = number_format($TOT_D / 1000, 2);
                	$TOTCOD	= " RB";
                }
                elseif($TOT_D < 1000000000)
                {
                	$TOT_DV = number_format($TOT_D / 1000000, 2);
                	$TOTCOD	= " JT";
                }
                elseif($TOT_D < 1000000000000)
                {
                	$TOT_DV = number_format($TOT_D / 1000000000, 2);
                	$TOTCOD	= " M";
                }
                else
                {
                	$TOT_DV = number_format($TOT_D / 1000000000000, 2);
                	$TOTCOD	= " T";
                }

                if($TOT_K < 1000)
                {
                	$TOT_KV = number_format($TOT_K / 1, 2);
                	$TOTCOK	= "";
                }
                elseif($TOT_K < 1000000)
                {
                	$TOT_KV = number_format($TOT_K / 1000, 2);
                	$TOTCOK	= " RB";
                }
                elseif($TOT_K < 1000000000)
                {
                	$TOT_KV = number_format($TOT_K / 1000000, 2);
                	$TOTCOK	= " JT";
                }
                elseif($TOT_K < 1000000000000)
                {
                	$TOT_KV = number_format($TOT_K / 1000000000, 2);
                	$TOTCOK	= " M";
                }
                else
                {
                	$TOT_KV = number_format($TOT_K / 1000000000000, 2);
                	$TOTCOK	= " T";
                }

                if($TOT_B < 1000)
                {
                	$TOT_BV = number_format($TOT_B / 1, 2);
                	$TOTCOB	= "";
                }
                elseif($TOT_B < 1000000)
                {
                	$TOT_BV = number_format($TOT_B / 1000, 2);
                	$TOTCOB	= " RB";
                }
                elseif($TOT_B < 1000000000)
                {
                	$TOT_BV = number_format($TOT_B / 1000000, 2);
                	$TOTCOB	= " JT";
                }
                elseif($TOT_B < 1000000000000)
                {
                	$TOT_BV = number_format($TOT_B / 1000000000, 2);
                	$TOTCOB	= " M";
                }
                else
                {
                	$TOT_BV = number_format($TOT_B / 1000000000000, 2);
                	$TOTCOB	= " T";
                }

                $TOTDV 		= $TOT_DV.$TOTCOD;
                $TOTKV 		= $TOT_KV.$TOTCOK;
                $TOTBV 		= $TOT_BV.$TOTCOB;
      		?>
			<div class="box">
			    <div class="box-body">
	                <div class="col-lg-3 col-xs-3">
						<div class="small-box bg-green-gradient">
	                    	<div class="ribbon-wrapper ribbon-lg">
								<div class="ribbon bg-red-gradient text-lg">
									DEBET (D)
								</div>
							</div>
							<div class="inner">
								<h3><?php echo $TOTDV; ?></h3>
								<?php echo $coaDesc; ?> (D)
							</div>
							<div class="icon">
								<i class="ion ion-arrow-up-b"></i>
							</div>
						</div>
					</div>
	                <div class="col-lg-3 col-xs-3">
						<div class="small-box bg-red-gradient">
	                    	<div class="ribbon-wrapper ribbon-lg">
								<div class="ribbon bg-aqua-gradient text-lg">
									KREDIT (K)
								</div>
							</div>
							<div class="inner">
								<h3><?php echo $TOTKV; ?></h3>
								<?php echo $coaDesc; ?> (K)
							</div>
							<div class="icon">
								<i class="ion ion-arrow-down-b"></i>
							</div>
						</div>
					</div>
	                <div class="col-lg-3 col-xs-3">
						<div class="small-box bg-aqua-gradient">
	                    	<div class="ribbon-wrapper ribbon-lg">
								<div class="ribbon bg-green-gradient text-lg">
									<?php echo strtoupper($Balance); ?>
								</div>
							</div>
							<div class="inner">
								<h3><?php echo $TOTBV; ?></h3>
								<?php echo strtoupper($Balance); ?>
							</div>
							<div class="icon">
								<i class="ion ion-cash"></i>
							</div>
						</div>
					</div>
			    	<div class="col-lg-3 col-xs-3">
		    			<div class="input-group margin">
							<div class="input-group-btn">
								<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Tahun&nbsp;&nbsp;&nbsp;&nbsp;
									<span class="fa fa-caret-down"></span>
								</button>
								<ul class="dropdown-menu">
									<li><a onClick="selYEAR('All');"> All</a></li>
									<?php
										$fYear 	= 2019;
										$fNow 	= date('Y');
						                for($i=$fYear; $i<=$fNow;$i++)
						                {
				                        ?>
				                        	<li><a onClick="selYEAR(<?php echo $i; ?>);"><?php echo $i; ?></a></li>
				                        <?php
				                    	}
						            ?>
								</ul>
							</div>
							<input type="text" name="seltYear" id="seltYear" class="form-control" readonly value="<?php echo $selCOAYEAR; ?>">
						</div>

		    			<div class="input-group margin">
							<div class="input-group-btn">
								<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">Kategori
									<span class="fa fa-caret-down"></span>
								</button>
								<ul class="dropdown-menu">
						        	<li><a onClick="checkAkt(1);"> 1 ASSET </a></li>
						        	<li><a onClick="checkAkt(2);"> 2 LIABILITAS </a></li>
						        	<li><a onClick="checkAkt(3);"> 3 EKUITAS </a></li>
						        	<li><a onClick="checkAkt(4);"> 4 PENDAPATAN </a></li>
						        	<li><a onClick="checkAkt(5);"> 5 BEBAN KONTRAK</a></li>
						        	<li><a onClick="checkAkt(6);"> 6 BEBAN USAHA </a></li>
						        	<li><a onClick="checkAkt(7);"> 7 PENGHASILAN LAIN-LAIN </a></li>
						        	<li><a onClick="checkAkt(8);"> 8 BEBAN LIAN-LAIN </a></li>
						        	<!-- <li><a onClick="checkAkt(9);"> LAINNYA </a></li> -->
								</ul>
							</div>
							<input type="text" class="form-control" value="<?php echo $coaDesc; ?>" readonly>
							<input type="hidden" name="selCOAC" id="selCOAC" class="form-control" value="<?php echo $selCOACAT; ?>">
						</div>
					</div>
			    	<div class="col-xs-12">
				        <div class="search-table-outter">
		              		<input type="hidden" name="secRes" id="secRes" value="<?php echo $secRes; ?>" />
		              		<input type="hidden" name="secResJD" id="secResJD" value="<?php echo $secResJD; ?>" />
		              		<input type="hidden" name="AccCateg" id="AccCateg" value="<?php echo $AccCateg; ?>" />
				        	<input type="hidden" name="seceXplURL_" id="seceXplURL_" value="<?php echo $seceXplURL; ?>"/>
		              		<input type="hidden" name="secResJDY" id="secResJDY" value="<?php echo $secResJDY; ?>" />
				            <table width="100%" id="example" class="table table-bordered table-striped">
				                <thead>
				                    <tr>
				                        <th width="65%" style="vertical-align: middle; text-align: center;"><?php echo $AccountName; ?></th>
				                        <th width="10%" style="vertical-align: middle; text-align: center;" nowrap>Kat. Neraca</th>
				                        <th width="10%" style="vertical-align: middle; text-align: center;" nowrap><?php echo $Type; ?></th>
				                        <th width="15%" style="vertical-align: middle; text-align: center;" nowrap><?php echo $Balance; ?></th>
				                    </tr>
					            </thead>
				                <tbody>
				                </tbody> 
					        </table>
					    </div>
					    <br> 
					    <div>
					        <?php
								$selAccCatg	= "$selPRJCODE~$AccCateg";
		                        $secAddURL 	= site_url('c_gl/c_ch1h0fbeart/a180e2edd/?id='.$this->url_encryption_helper->encode_url($selPRJCODE));
		                        //$secShowJUR = site_url('c_gl/c_ch1h0fbeart/a180e25H0w/?id='.$this->url_encryption_helper->encode_url($selAccCatg));
		                        $secShowJUR = site_url('c_gl/c_ch1h0fbeart/a180e25H0wX/?id='.$this->url_encryption_helper->encode_url($selAccCatg));
		                        $secShowAn 	= site_url('c_gl/c_ch1h0fbeart/a180e25H0w4nJD/?id='.$this->url_encryption_helper->encode_url($selAccCatg));
		                        $urlSynJDYear= site_url('c_gl/c_ch1h0fbeart/a180e25H0w4nJDY/?id='.$this->url_encryption_helper->encode_url($selAccCatg));

	                        	if($ISCREATE == 1)
		                        {								
		                            echo anchor("$secAddURL",'<button class="btn btn-primary" title="Tambah"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;&nbsp;');
		                            echo anchor("$secAdd",'<button class="btn btn-warning" title="Upload COA"><i class="glyphicon glyphicon-import"></i></button>&nbsp;&nbsp;');
		                        }
		                        if($DefEmp_ID == 'D15040004221')
		                        {
		                            //echo '<button class="btn btn-info" onClick="syncJournal()" title="Reset Order"><i class="glyphicon glyphicon-refresh"></i></button>&nbsp;&nbsp;';
		                            //echo '<button class="btn btn-info" onClick="resOrder()" title="Reset Order"><i class="glyphicon glyphicon-refresh"></i></button>&nbsp;&nbsp;';
		                            //echo '<button class="btn btn-info" onClick="resSync()" title="Reset Budget"><i class="fa fa-share-alt"></i></button>&nbsp;&nbsp;';
		                            //echo '<button class="btn btn-success" onClick="downTemp()" title="Download Template"><i class="glyphicon glyphicon-download"></i></button>&nbsp;&nbsp;';
		                            //echo '<button class="btn btn-danger" onClick="secShowJUR()" title="Show All Journal"><i class="glyphicon glyphicon-eye-open"></i></button>&nbsp;&nbsp;';
		                            echo '<button class="btn bg-navy" onClick="secShowANJD()" title="Show Anomali Journal"><i class="glyphicon glyphicon-bell"></i></button>&nbsp;&nbsp;';
		                            echo '<button class="btn bg-blue" onClick="secSyncJURDYShw()" title="Sinkronisasi Journal"><i class="fa fa-spinner"></i></button>&nbsp;&nbsp;';
		                            //echo anchor("$secShowJUR",'<button class="btn btn-danger" title="Show All"><i class="glyphicon glyphicon-eye-open"></i></button>&nbsp;&nbsp;');
		                        }
	                        ?>
					    </div>
					</div>
				    <div id="loading_1" class="overlay" style="display:none">
				        <i class="fa fa-refresh fa-spin"></i>
				    </div>
				    <?php
				    	$secSelPRJCAT	= site_url('c_gl/c_ch1h0fbeart/get_all_ofCOA/?id='.$this->url_encryption_helper->encode_url($myPRJCODE));
				    ?>
				    <script>
				        function selYEAR(selCOAYEAR)
				        {
				            document.getElementById('loading_1').style.display = '';

							var thePrj 	= "<?php echo $myPRJCODE; ?>";
							var theCat 	= document.getElementById('selCOAC').value;
							var theYear = selCOAYEAR;
							document.getElementById('selCOACAT').value 	= theCat;
							document.getElementById('selCOAYEAR').value = theYear;
							var thePer 	= "<?php echo $myPRJCODE; ?>";
							var url 	= "<?php echo $secSelPRJCAT;?>";

							//var nURL	= url+'&tC4t='+theCat;
							var nURL	= url+'&accYr='+theYear+'&tC4t='+theCat+'&thePrj='+thePrj;
							document.getElementById('frmsrchCAT').action = nURL;

							document.frmsrchCAT.submitCOACAT.click();
				        }

				        function checkAkt(selCOACAT)
				        {
				            document.getElementById('loading_1').style.display = '';

							var thePrj 	= "<?php echo $myPRJCODE; ?>";
							var theCat 	= selCOACAT;
							var theYear = document.getElementById('selCOAYEAR').value;
							document.getElementById('selCOACAT').value 	= selCOACAT;
							document.getElementById('selCOAYEAR').value = theYear;
							var thePer 	= "<?php echo $myPRJCODE; ?>";
							var url 	= "<?php echo $secSelPRJCAT;?>";

							//var nURL	= url+'&tC4t='+theCat;
							var nURL	= url+'&accYr='+theYear+'&tC4t='+theCat+'&thePrj='+thePrj;
							document.getElementById('frmsrchCAT').action = nURL;

							document.frmsrchCAT.submitCOACAT.click();
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
				<div class="row" id="SYNCJRNTOCOADESC" style="display: none;">
	                <div class="col-sm-12">
	                    <div class="alert alert-danger alert-dismissible">
	                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                        <h4><i class="icon fa fa-warning"></i> PERHATIAN</h4>
	                        Proses ini akan melakukan:<br>
	                        1. Mendata seluruh jurnal dalam proyek terpilih.<br> 
	                        2. Mengatur ulang nilai Akun yang diperoleh dari total transaksi yang terdapat di dalam jurnal.<br>
	                        <button class="btn btn-info" onClick="secSyncJURDY()"></i>Lanjutkan</button>
	                    </div>
	                </div>
	            </div>
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
								<div id="progressbarXY" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
			</div>
		</section>
		<iframe id="myiFrame" src="<?php echo base_url('__l1y/impCOA' ) ?>" style="width: 100%; display: none;"></iframe>
	</body>
</html>
<?php
	$vwJDURL	= site_url('c_gl/c_ch1h0fbeart/sH0wJD/?id='.$this->url_encryption_helper->encode_url($appName));
?>
<script>
	$(function () {
		$(".select2").select2();
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
	        "ajax": "<?php echo site_url('c_gl/c_ch1h0fbeart/get_AllDataCOA/?id='.$selPRJCODE.'&tYr='.$selCOAYEAR.'&tC4t='.$selCOACAT)?>",
	        "type": "POST",
			//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
			"lengthMenu": [[50, 100, 200], [50, 100, 200]],
			"columnDefs": [	{ targets: [3], className: 'dt-body-center' }
						  ],
			"language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
		});
	});

	function showDetC(AccCode)
	{
		var url 	= "<?php echo $vwJDURL;?>";
		var PRJCODE = "<?php echo $myPRJCODE;?>";
		var LinkD	= PRJCODE+'~'+AccCode;
		
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		//window.open(url,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
		window.open(url+'&lC='+LinkD,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
	}
	
	function delCOA(row)
	{
	    swal({
            text: "<?php echo $sureDelete; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID	= document.getElementById('urlDel'+row).value;
		        var myarr 	= collID.split("~");

		        var url 	= myarr[0];

		        $.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	swal(response, 
						{
							icon: "success",
						});
		                window.location.reload();
		            }
		        });
            } 
            else 
            {
                swal("<?php echo $cancDel; ?>", 
				{
					icon: "error",
				});
            }
        });
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
				document.getElementById('idprogbar').style.display = '';
			    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display = '';
            	var collID	= PRJCODE;
		        var myarr 	= collID.split("~");

		        var url 	= document.getElementById('secRes').value;

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
	
	function secShowJUR()
	{
		PRJCODE 	= "<?php echo $myPRJCODE; ?>";
		AccCateg 	= document.getElementById('AccCateg').value;
	    swal({
            text: "<?php echo $sureResJD; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
            	document.getElementById('loading_1').style.display = '';
            	var collID	= PRJCODE+'~'+AccCateg;
		        var myarr 	= collID.split("~");

		        var url 	= document.getElementById('secResJD').value;

		        $.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	document.getElementById('loading_1').style.display = 'none';
						swal({
				            text: response,
				            icon: "warning",
				            buttons: ["No", "Yes"],
				        })
						.then((willDelete1) =>
						{
				            if (willDelete1) 
				            {
								var logoutUrl 	= '<?php echo $secShowJUR; ?>';
								//window.location = logoutUrl;
								window.open(logoutUrl, '_blank');
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
		        });
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
	
	function secShowANJD()
	{
	    swal({
            text: "<?php echo $sureAnJD; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
				var logoutUrl 	= '<?php echo $secShowAn; ?>';
				//window.location = logoutUrl;
				window.open(logoutUrl, '_blank');
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
	
	function secSyncJURDYShw()
	{
		document.getElementById('SYNCJRNTOCOADESC').style.display 	= '';
	}
	
	function secSyncJURDY()
	{
		PRJCODE 	= "<?php echo $myPRJCODE; ?>";
		AccCateg 	= document.getElementById('AccCateg').value;
	    swal({
            text: "<?php echo $sureAnJDY; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
            	document.getElementById('SYNCJRNTOCOADESC').style.display 	= 'none';

            	document.getElementById('idprogbar').style.display 	= '';
				document.getElementById('idprogbar2').style.display = '';
			    document.getElementById("progressbarXX").innerHTML	="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
			    document.getElementById("progressbarXY").innerHTML	="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display 	= '';
            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'SYNJRNCOA';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'SYNJRNCOA';
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
      	}, 2000);
	}

	function clsBar()
	{
		document.getElementById('idprogbar').style.display = 'none';
		document.getElementById('idprogbar2').style.display = 'none';
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