<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Maret 2017
 * File Name	= v_project_progress.php
 * Location		= -
*/


$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 3;

$progress_DateY = date('Y');
$progress_DateM = date('m');
$progress_DateD = date('d');
$progress_Date = "$progress_DateY-$progress_DateM-$progress_DateD";
$ETD = $progress_Date;
$month = date("m",strtotime($progress_Date));

$EmpID 	= $this->session->userdata('Emp_ID');
$sqlEmpID 	= "SELECT Position_ID, Emp_DeptCode FROM tbl_employee WHERE Emp_ID = '$EmpID'";
$resEmpID	= $this->db->query($sqlEmpID)->result();
foreach($resEmpID as $rowEmpID) :
	$Position_ID		= $rowEmpID->Position_ID;
	$Emp_DeptCode		= $rowEmpID->Emp_DeptCode;
endforeach;

if(isset($_POST['submitChgType']))
{
	$isTypeDoc 		= 1;
	$progressType 	= $_POST['progressType'];	// 1. Cash Flow, 2. Profit and Loss, 3. Project Progress
	$projCode 		= $_POST['projCode'];
	$Progress_Step	= $_POST['Progress_Step1'];
	
	// Mencari Last Step Progress Report. Mendeteksinya dari isShow from table tbl_projprogres
	$getCountPS		= "tbl_projprogres WHERE proj_Code = '$projCode' AND progress_Type = $progressType AND lastStepPS = 1";
	$resGetCountPS	= $this->db->count_all($getCountPS);
	
	$lastStepPS		= $resGetCountPS;
	$nextStepPS		= $resGetCountPS + 1;
	if($resGetCountPS > 0)
	{
		$lastStepPS		= $resGetCountPS;
		
		$sqlProjStep	= "SELECT Prg_Step, Prg_Date1, Prg_Date2, Prg_PlanAkum, Prg_Real, Prg_ProjNotes, Prg_PstNotes
							FROM tbl_projprogres 
							WHERE proj_Code = '$projCode' AND progress_Type = $progressType AND Prg_Step = $Progress_Step";
		$resProjStep	= $this->db->query($sqlProjStep)->result();		
		foreach($resProjStep as $rowProjStep) :
			$Prg_Step 		= $rowProjStep->Prg_Step;
			$Prg_Date1 		= $rowProjStep->Prg_Date1;
			$Prg_Date2 		= $rowProjStep->Prg_Date2;
			$Prg_PlanAkum	= $rowProjStep->Prg_PlanAkum;
			$Prg_Real		= $rowProjStep->Prg_Real;
			$Prg_ProjNotes 	= $rowProjStep->Prg_ProjNotes;
			$Prg_PstNotes 	= $rowProjStep->Prg_PstNotes;
		endforeach;
	}
	else
	{
		$lastStepPS 	= 1;
		$Prg_Date1 		= $progress_Date;
		$Prg_Date2 		= date('Y-m-d', strtotime('+6 days', strtotime($progress_Date))); // Penambahan tanggal sebanyak 6 hari
		$Prg_ProjNotes 	= '';
		$Prg_PstNotes 	= '';
		$Prg_PlanAkum	= 0;
		$Prg_Real		= 0;
	}
	$lastStepPSP		= $Prg_PlanAkum;
}
else
{	
	$isTypeDoc 		= 1;
	$progressType 	= 3;	// 1. Cash Flow, 2. Profit and Loss, 3. Project Progress
	$projCode 		= $projCode;
	$Prg_Step_L		= $progress_Step-1;
	$Progress_Step	= $progress_Step;
	
	// Mencari Last Step Progress Report. Mendeteksinya dari isShow from table tbl_projprogres
	$getCountPS		= "tbl_projprogres WHERE proj_Code = '$projCode' AND progress_Type = $progressType AND lastStepPS = 1";
	$resGetCountPS	= $this->db->count_all($getCountPS);
	
	$lastStepPS		= $resGetCountPS;
	$nextStepPS		= $resGetCountPS + 1;
	if($resGetCountPS > 0)
	{
		$lastStepPS		= $resGetCountPS;
		
		$sqlProjStep	= "SELECT Prg_Step, Prg_Date1, Prg_Date2, Prg_PlanAkum, Prg_Real, Prg_ProjNotes, Prg_PstNotes
							FROM tbl_projprogres 
							WHERE proj_Code = '$projCode' AND progress_Type = $progressType AND Prg_Step = $Progress_Step";
		$resProjStep	= $this->db->query($sqlProjStep)->result();		
		foreach($resProjStep as $rowProjStep) :
			$Prg_Step 		= $rowProjStep->Prg_Step;
			$Prg_Date1 		= $rowProjStep->Prg_Date1;
			$Prg_Date2 		= $rowProjStep->Prg_Date2;
			$Prg_PlanAkum	= $rowProjStep->Prg_PlanAkum;
			$Prg_Real		= $rowProjStep->Prg_Real;
			$Prg_ProjNotes 	= $rowProjStep->Prg_ProjNotes;
			$Prg_PstNotes 	= $rowProjStep->Prg_PstNotes;
		endforeach;
	}
	else
	{
		$lastStepPS 	= 1;
		$Prg_Date1 		= $progress_Date;
		$Prg_Date2 		= date('Y-m-d', strtotime('+6 days', strtotime($progress_Date))); // Penambahan tanggal sebanyak 6 hari
		$Prg_ProjNotes 	= '';
		$Prg_PstNotes 	= '';
		$Prg_PlanAkum	= 0;
		$Prg_Real		= 0;
	}
}
// Next Planning Progress
$Prg_Step_N 		= 0;
$Prg_Date1_N 		= '';
$Prg_Date2_N 		= '';
$Prg_Plan_N			= 0;
$Prg_PlanAkum_N		= 0;
$Prg_Real_N			= 0;
$sqlProgPlan		= "SELECT Prg_Step, Prg_Date1, Prg_Date2, Prg_Plan, Prg_PlanAkum, Prg_Real
						FROM tbl_projprogres 
						WHERE proj_Code = '$projCode' AND progress_Type = $progressType AND Prg_Step = $Progress_Step";
$resProgPlan		= $this->db->query($sqlProgPlan)->result();		
foreach($resProgPlan as $projPlan) :
	$Prg_Step_N 	= $projPlan->Prg_Step;
	$Prg_Date1_N 	= $projPlan->Prg_Date1;
	$Prg_Date2_N 	= $projPlan->Prg_Date2;
	$Prg_Plan_N 	= $projPlan->Prg_Plan;
	$Prg_PlanAkum_N	= $projPlan->Prg_PlanAkum;
	$Prg_Real_N		= $projPlan->Prg_Real;
endforeach;

// Last Planning Progress
$Prg_Step_L 		= $lastStepPS;
$Prg_PlanAkum_L		= 0;
$Prg_Real_L			= 0;
$Prg_RealAkum_L		= 0;
$sqlProgPlan		= "SELECT Prg_Step, Prg_PlanAkum, Prg_Real, Prg_RealAkum
						FROM tbl_projprogres 
						WHERE proj_Code = '$projCode' AND progress_Type = $progressType AND Prg_Step = $lastStepPS";
$resProgPlan		= $this->db->query($sqlProgPlan)->result();		
foreach($resProgPlan as $projPlan) :
	$Prg_Step_L 	= $projPlan->Prg_Step;
	$Prg_PlanAkum_L	= $projPlan->Prg_PlanAkum;
	$Prg_Real_L		= $projPlan->Prg_Real;
	$Prg_RealAkum_L	= $projPlan->Prg_RealAkum;
endforeach;

$secIndex 		= site_url(). '/c_project/c_project_progress/add/';

//$collProject1		= '523';

$collProject1		= $projCode;
$GraphicTitleText	= 3;

$getCountPSA		= "tbl_projprogres WHERE proj_Code = '$collProject1' AND progress_Type = $progressType AND lastStepPS = 1";
$resGetCountPSA		= $this->db->count_all($getCountPSA);
$totPS				= $resGetCountPSA;
?>
<!DOCTYPE html>
<html>
    <head>
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

    <script type="text/javascript" src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script type="text/javascript" src="https://cdn.amcharts.com/lib/3/pie.js"></script>
    <script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/light.js"></script>
	<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/js/highcharts/highcharts.js'; ?>"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="http://www.chartjs.org/dist/2.7.2/Chart.bundle.js"></script>
    <script src="http://www.chartjs.org/samples/latest/utils.js"></script>
	<script src="http://code.jquery.com/jquery-2.2.1.min.js"></script>

	<script type="text/javascript">
		function changeProject(projCode)
		{
			ChartType 	= document.getElementById('progressType').value;
			document.getElementById('projCode').value = projCode;
			
			document.getElementById('submitChgType').click();
		}
		
		function changeProgType(ChartType)
		{
			projCode = document.getElementById('proj_Code').value;
			document.getElementById('progressType').value = ChartType;
			document.getElementById('projCode').value = projCode;
			
			document.getElementById('submitChgType').click();
		}
		
		Highcharts.theme = {
		    colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', 
		             '#FF9655', '#FFF263', '#6AF9C4'],
		    chart: {
		        backgroundColor: {
		            linearGradient: [0, 0, 500, 500],
		            stops: [
		                [0, 'rgb(255, 255, 255)'],
		                [1, 'rgb(240, 240, 255)']
		            ]
		        },
		    },
		    title: {
		        style: {
		            color: '#000',
		            font: 'bold 16px "Trebuchet MS", Verdana, sans-serif'
		        }
		    },
		    subtitle: {
		        style: {
		            color: '#666666',
		            font: 'bold 12px "Trebuchet MS", Verdana, sans-serif'
		        }
		    },

		    legend: {
		        itemStyle: {
		            font: '9pt Trebuchet MS, Verdana, sans-serif',
		            color: 'black'
		        },
		        itemHoverStyle:{
		            color: 'gray'
		        }   
		    }
		};

		// Apply the theme
		Highcharts.setOptions(Highcharts.theme);
	</script>

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
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'ProgressType')$ProgressType = $LangTransl;
			if($TranslCode == 'LastStep')$LastStep = $LangTransl;
			if($TranslCode == 'ProgressStep')$ProgressStep = $LangTransl;
			if($TranslCode == 'Week')$Week = $LangTransl;
			if($TranslCode == 'ProgresPlan')$ProgresPlan = $LangTransl;
			if($TranslCode == 'Progress')$Progress = $LangTransl;
			if($TranslCode == 'NotesbyProject')$NotesbyProject = $LangTransl;
			if($TranslCode == 'NotesbyOffice')$NotesbyOffice = $LangTransl;
			if($TranslCode == 'InputProgress')$InputProgress = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$h_title	= "Progres Mingguan";
			$h1_title	= "Proyek";
		}
		else
		{
			$h_title	= "Weekly Progress";
			$h1_title	= "Project";
		}
		$getCount		= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$Emp_ID')";
		$resGetCount	= $this->db->count_all($getCount);

	    $comp_color = $this->session->userdata('comp_color');
	?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/progress.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h_title; ?>
		    <small><?php echo $h1_title; ?></small>
		  </h1>
		</section>

		<form name="frmChangeType" id="frmChangeType" method="post">
			<input type="hidden" name="progressType" id="progressType" value="<?php echo $progressType; ?>" />
			<input type="hidden" name="projCode" id="projCode" value="<?php echo $projCode; ?>" />
			<input type="hidden" name="Progress_Step1" id="Progress_Step1" value="<?php echo $Progress_Step; ?>" />
		    <input type="submit" name="submitChgType" id="submitChgType" style="display:none" />
		</form>

		<section class="content">	
		    <div class="row">
		        <div class="col-md-6">
		            <div class="box box-primary">
		                <div class="box-header with-border">
		                    <h3 class="box-title"><?php echo $InputProgress; ?></h3>                
		              		<div class="box-tools pull-right">
		                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                        </button>
		                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		                    </div>
		                </div>
		                <div class="box-body chart-responsive">
		                	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action1; ?>" onSubmit="return validateInEmpData()">  
		                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		                        <input type="hidden" name="progressType" id="progressType" value="<?php echo $ProgressType; ?>" />
		                        <input type="hidden" name="isAddorShow" id="isAddorShow" value="2" />                   
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $Project ?> </label>
		                            <div class="col-sm-9">
		                            <select name="proj_Code" id="proj_Code" class="form-control select2" onChange="changeProject(this.value)" >
		                            	<option value=""> --- </option>
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
		                                            <option value="<?php echo $proj_Code; ?>" <?php if($proj_Code == $collProject1) { ?> selected <?php } ?>><?php echo "$proj_Code - $proj_Name"; ?></option>
		                                            <?php
		                                        endforeach;
		                                    }
		                                ?>
		                            </select>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="col-sm-3 control-label"><?php echo $ProgressType ?> </label>
		                            <div class="col-sm-9">
		                                <select name="progress_Type" id="progress_Type" class="form-control select2" onChange="changeProgType(this.value)" >
		                                    <!-- <option value="1" <?php if($progressType == 1) { ?> selected <?php } ?>>Cash Flow</option>
		                                    <option value="2" <?php if($progressType == 2) { ?> selected <?php } ?>>Profit and Loss</option> -->
		                                    <option value="3" <?php if($progressType == 3) { ?> selected <?php } ?>>Project Progress</option>
		                                </select>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label class="col-sm-3 control-label"><?php echo $LastStep; ?> </label>
		                            <div class="col-sm-9">
		                                <label>
		                               	  <input type="text" name="Prg_Step_L" id="Prg_Step_L" class="form-control" value="<?php echo $Prg_Step_L; ?>" style="text-align:right; max-width:60px; display:none" />
		                               	  <input type="text" name="Prg_Step_L1" id="Prg_Step_L1" class="form-control" value="<?php echo $Prg_Step_L; ?>" style="text-align:right; max-width:60px" disabled />
		                                </label>
		                                <label>
		                                	&nbsp;-&nbsp;&nbsp;
		                                </label>
		                                <label>
		                               	  <input type="text" name="Prg_RealAkum_L" id="Prg_RealAkum_L" class="form-control" value="<?php echo number_format($Prg_RealAkum_L, $decFormat); ?>" style="text-align:right; max-width:70px; display:none" />
		                               	  <input type="text" name="Prg_RealAkum_L1" id="Prg_RealAkum_L1" class="form-control" value="<?php echo number_format($Prg_RealAkum_L, $decFormat); ?>" style="text-align:right; max-width:70px" disabled />
		                                </label>
		                                <label>
		                                	&nbsp;(%)
		                                </label>
		                                <label>
		                                	&nbsp;:&nbsp;&nbsp;
		                                </label>
		                                <label>
		                                	<input type="text" name="Prg_Real_L" id="Prg_Real_L" class="form-control" value="<?php echo number_format($Prg_Real_L, $decFormat); ?>" style="text-align:right; max-width:70px" disabled />
		                                </label>
		                                <label>
		                                	&nbsp;(%)
		                                </label>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputEmail" class="col-sm-3 control-label"><?php echo $ProgressStep ?> </label>
		                            <div class="col-sm-2">
	                                    <select name="progress_Step" id="progress_Step" class="form-control select2" onChange="sel_progStep(this.value)" >
	                                        <option value="0"> --- </option>
	                                        <?php
	                                            if($totPS > 0)
	                                            {
	                                                for($i=1;$i<=$nextStepPS;$i++)
	                                                {
	                                                ?>
	                                                    <option value="<?php echo $i; ?>" <?php if($i == $Progress_Step) { ?> selected <?php } ?>><?php echo $i; ?></option>
	                                                <?php
	                                                }
	                                            }
	                                        ?>
	                                    </select>
	                                </div>
		                            <div class="col-sm-5">
	                                	&nbsp;&nbsp;<i>(
										<?php
	                                        $date1 = new DateTime($Prg_Date1);
	                                        echo $date1->format('d M Y');
	                                        //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; to &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	                                        echo "&nbsp;&nbsp; - &nbsp;&nbsp;";
	                                        $date2 = new DateTime($Prg_Date2);
	                                        echo $date2->format('d M Y');
	                                    ?>)</i>
										<script>
											function sel_progStep(stepVal)
											{
												ChartType 		= document.getElementById('progress_Type').value;
												projCode 		= document.getElementById('proj_Code').value;
												
												document.getElementById('Progress_Step1').value = stepVal;
												document.getElementById('progressType').value = ChartType;
												document.getElementById('projCode').value = projCode;
												
												document.getElementById('submitChgType').click();
											}
										</script>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                   	  	  	<label for="inputEmail" class="col-sm-3 control-label"><?php echo $ProgresPlan ?></label>
		                            <div class="col-sm-9">
		                                <label>
										<input type="text" name="Prg_PlanAkum_N" id="Prg_PlanAkum_N" class="form-control" value="<?php echo number_format($Prg_PlanAkum_N, $decFormat); ?>" style="text-align:right; max-width:70px; display:none" />
										<input type="text" name="Prg_PlanAkum_N1" id="Prg_PlanAkum_N1" class="form-control" value="<?php echo number_format($Prg_PlanAkum_N, $decFormat); ?>" style="text-align:right; max-width:70px" disabled />
		                                </label>
		                                <label>
		                                	(%)&nbsp;:&nbsp;
		                                </label>
		                                <label>
		                                	<input type="text" name="Prg_Plan_N" id="Prg_Plan_N" class="form-control" value="<?php echo number_format($Prg_Plan_N, $decFormat); ?>" style="text-align:right; max-width:70px" disabled />
		                                </label>
		                                <label>
		                                	&nbsp;(%)
		                                </label>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                   	  	  <label for="inputEmail" class="col-sm-3 control-label"><?php echo $Progress; ?>   (Real)</label>
		                            <div class="col-sm-9">
		                            	<input type="text" name="Prg_Real_Now" id="Prg_Real_Now" class="form-control" value="<?php echo $Prg_Real; ?>" style="display:none" />
										<input type="text" name="Prg_Real_Now1" id="Prg_Real_Now1" class="form-control" value="<?php echo number_format($Prg_Real, $decFormat); ?>" style="text-align:right; max-width:70px" onBlur="changePrg_Real(this.value)" <?php if($Progress_Step <= $Prg_Step_L) { ?> disabled <?php } ?>/>
		                            </div>
		                        </div>
		                        <script>
									function changePrg_Real(PrgRealVal)
									{
										var decFormat		= document.getElementById('decFormat').value;
										document.getElementById('Prg_Real_Now').value 	= parseFloat(PrgRealVal);
										document.getElementById('Prg_Real_Now1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(PrgRealVal)),decFormat));
									}
								</script>
		                        <div class="form-group" style="display: none;">
		                        	<label for="inputEmail" class="col-sm-3 control-label"><?php echo $NotesbyProject ?> </label>
		                          	<div class="col-sm-9">
		                            	<?php
											if($Progress_Step > $Prg_Step_L)
											{
											?>
		                            			<textarea name="Prg_ProjNotes" class="form-control" id="Prg_ProjNotes" cols="50" style="height:50px" <?php if($ISCREATE != 1) { ?> disabled<?php } ?>><?php echo $Prg_ProjNotes; ?></textarea>
		                               		<?php
											}
											else
											{
												echo $Prg_ProjNotes;
											}
										?>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display: none;">
		                        	<label for="inputEmail" class="col-sm-3 control-label"><?php echo $NotesbyOffice ?> </label>
		                          	<div class="col-sm-9">
		                            	<?php
											if($Progress_Step > $Prg_Step_L)
											{
											?>
		                            			<textarea name="Prg_PstNotes" class="form-control" id="Prg_PstNotes" cols="50" style="height:50px" <?php if($ISAPPROVE != 1) { ?> disabled<?php } ?>><?php echo $Prg_PstNotes; ?></textarea>
		                               		<?php
											}
											else
											{
												echo "<strong>$Prg_PstNotes</strong>";
											}
										?>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <div class="col-sm-offset-3 col-sm-9">
		                            	<?php
											if(($Progress_Step > $Prg_Step_L) && $ISCREATE == 1)
											{
											?>
											<button class="btn btn-primary" onClick="return buttonShowPhoto(1)"><i class="cus-chart-16x16" ></i>&nbsp;&nbsp;<?php echo "$Add Progress"; ?></button>&nbsp;
											<?php
		                                    }
		                                    if ( ! empty($link))
		                                    {
		                                        foreach($link as $links)
		                                        {
		                                            echo $links;
		                                        }
		                                    }
		                                ?>
		                            </div>
		                        </div>
		                        <br>
		                        <br>
		                    </form>
		                </div>
		            </div>
		        </div>
		        <div class="col-md-6">
		            <div class="box box-warning">
		                <div class="box-body">
		                    <div id="line-chartx" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		                </div>
		            </div>
		        </div>
		    </div>
        	<?php
        		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
				$act_lnk 	= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
	</body>
</html>

<!-- START FLOT CHARTS -->
	<script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/flot/jquery.flot.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/flot/jquery.flot.resize.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/flot/jquery.flot.pie.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/flot/jquery.flot.categories.min.js') ?>" type="text/javascript"></script>
<!-- END FLOT CHARTS -->

<!-- START MORRIS CHARTS -->
	<script src="<?php echo base_url('assets/js/raphael-min.js') ?>"></script>
    <script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/morris/morris.min.js') ?>" type="text/javascript"></script>
<!-- END MORRIS CHARTS -->
<script src="//code.highcharts.com/themes/sand-signika.js"></script>
<?php
$sqlProgC	= "tbl_projprogres WHERE proj_Code = '$collProject1' AND progress_Type = $GraphicTitleText AND isShow = 1";
				$resProgC	= $this->db->count_all($sqlProgC);
				if($resProgC <= 20)
				{
					$pmbg	= 1;
				}
				else
				{
					$pmbg	= $resProgC / 20;
				}
				$ADDR	= 0;
				for($PC = 1; $PC <= $resProgC; $PC++)
				{
					$ADDRX	= $ADDR + $PC;
				}
?>
<script>
  $(function () {
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
    $('#datepicker1').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker3').datepicker({
      autoclose: true
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
  
	Highcharts.chart('line-chartx', {
		chart: {
			type: 'line'
		},
		title: {
			text: 'Progres/Persentase Kemajuan Pekerjaan'
		},
		subtitle: {
			//text: 'Source: WorldClimate.com'
		},
		xAxis: {
			title: {
				text: 'Minggu ke'
			},
			categories: [
			<?php
				$sqlProgC	= "tbl_projprogres WHERE proj_Code = '$collProject1' AND progress_Type = $GraphicTitleText AND isShow = 1";
				$resProgC	= $this->db->count_all($sqlProgC);
				if($resProgC <= 20)
				{
					$pmbg	= 1;
				}
				else
				{
					$pmbg	= $resProgC / 20;
				}
				$ADDR	= 0;
				for($PC = 1; $PC <= $resProgC; $PC++)
				{
					$ADDRX	= $ADDR + $PC;
					echo "'$ADDRX',";
				}
			?>
			]
		},
		yAxis: {
			title: {
				text: 'Progress/Prosentasi'
			}
		},
		plotOptions: {
			line: {
				dataLabels: {
					enabled: true
				},
				enableMouseTracking: false
			}
		},
		series: [{
			name: 'Plan',
			color: "#00F",
			data: [<?php
						$jumTotPlanAkuma 	= '';
						$GraphicTitleText	= 3;
						$sqla   	= "SELECT A.proj_Code, B.PRJNAME
										FROM tbl_projprogres A
										INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
										WHERE A.proj_Code = '$collProject1' AND progress_Type = $GraphicTitleText
										GROUP BY proj_Code";
						$ressqla 	= $this->db->query($sqla)->result();
						
						foreach($ressqla as $rowa) :
							$proj_Code 	= $rowa->proj_Code;
							$projName 	= $rowa->PRJNAME;
							$proj_Name	= "Project : $proj_Code";
							
							$NoU 		= 0;
							$sql0   	= "SELECT MAX(day(Prg_Date1)) AS myDay, MAX(MONTH(Prg_Date1)) AS myMonth, 
												MAX(YEAR(Prg_Date1)) AS myYear, MAX(Prg_Date1) AS myDate,
												MAX(Prg_PlanAkum) AS Prg_PlanAkum, MAX(Prg_RealAkum) AS Prg_RealAkum, isShow, 
												isShowRA, isShowDev
											FROM tbl_projprogres WHERE proj_Code = '$collProject1' 
												AND progress_Type = $GraphicTitleText AND isShow = 1 
											GROUP BY day(Prg_Date1), month(Prg_Date1), year(Prg_Date1) ORDER BY Prg_Date1";
							$ressql0 	= $this->db->query($sql0)->result();
							foreach($ressql0 as $row0) :
								$NoU 			= $NoU + 1;
								$DayAx 			= $row0->myDay;
								$myDate			= $row0->myDate;
								$Prg_PlanAkum	= $row0->Prg_PlanAkum;
								$Prg_RealAkum	= $row0->Prg_RealAkum;
								$Prg_Dev2 		= $Prg_RealAkum - $Prg_PlanAkum;
								$isShow			= $row0->isShow;
								$isShowRA		= $row0->isShowRA;
								$isShowDev		= $row0->isShowDev;
								if($isShow == 1)
								{
									echo "$Prg_PlanAkum,";
								}
							endforeach;
						endforeach;
					?>]
		}, {
			name: 'Real',
			color: "#390",
			data: [<?php
						$jumTotPlanAkuma 	= '';
						$GraphicTitleText	= 3;
						$sqla   	= "SELECT A.proj_Code, B.PRJNAME
										FROM tbl_projprogres A
										INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
										WHERE A.proj_Code = '$collProject1' AND progress_Type = $GraphicTitleText
										GROUP BY proj_Code";
						$ressqla 	= $this->db->query($sqla)->result();
						
						foreach($ressqla as $rowa) :
							$proj_Code 	= $rowa->proj_Code;
							$projName 	= $rowa->PRJNAME;
							$proj_Name	= "Project : $proj_Code";
							
							$getCountx		= "tbl_projprogres WHERE proj_Code = '$collProject1' AND progress_Type = $GraphicTitleText AND isShow = 1";
							$resGetCountx	= $this->db->count_all($getCountx);
							
							$NoU 		= 0;
							$sql0   	= "SELECT MAX(day(Prg_Date1)) AS myDay, MAX(MONTH(Prg_Date1)) AS myMonth, 
												MAX(YEAR(Prg_Date1)) AS myYear, MAX(Prg_Date1) AS myDate,
												MAX(Prg_PlanAkum) AS Prg_PlanAkum, MAX(Prg_RealAkum) AS Prg_RealAkum, isShow, 
												isShowRA, isShowDev
											FROM tbl_projprogres WHERE proj_Code = '$collProject1' 
												AND progress_Type = $GraphicTitleText AND isShow = 1 
											GROUP BY day(Prg_Date1), month(Prg_Date1), year(Prg_Date1) ORDER BY Prg_Date1";
							$ressql0 	= $this->db->query($sql0)->result();
							foreach($ressql0 as $row0) :
								$NoU 			= $NoU + 1;
								$DayAx 			= $row0->myDay;
								$myDate			= $row0->myDate;
								$Prg_PlanAkum	= $row0->Prg_PlanAkum;
								$Prg_RealAkum	= $row0->Prg_RealAkum;
								$Prg_Dev2 		= $Prg_RealAkum - $Prg_PlanAkum;
								$isShow			= $row0->isShow;
								$isShowRA		= $row0->isShowRA;
								$isShowDev		= $row0->isShowDev;
								if($isShowRA == 1)
								{
									echo "$Prg_RealAkum,";
								}
							endforeach;
						endforeach;
					?>]
		}]
	});
	
	function doDecimalFormat(angka) {
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		//else return (c + '.' + dec);
		else return (c);  // untuk menghilangkan 2 angka di belakang koma
	}
	
	function doDecimalFormatxx(angka) {
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		else return (c + '.' + dec);
		//else return (c);  // untuk menghilangkan 2 angka di belakang koma
	}
	
	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}

	function decimalin(ini)
	{	
		var i, j;
		var bil2 = deletecommaperiod(ini.value,'both')
		var bil3 = ""
		j = 0
		for (i=bil2.length-1;i>=0;i--)
		{
			j = j + 1;
			if (j == 3)
			{
				bil3 = "." + bil3
			}
			else if ((j >= 6) && ((j % 3) == 0))
			{
				bil3 = "," + bil3
			}
			bil3 = bil2.charAt(i) + "" + bil3
		}
		ini.value = bil3
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