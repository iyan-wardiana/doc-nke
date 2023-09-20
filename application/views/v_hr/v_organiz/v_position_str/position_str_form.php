<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 15 Maret 2017
	* File Name	= position_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

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

if($task == "add")
{
	foreach($vwDocPatt as $row) :
		$Pattern_Code = $row->Pattern_Code;
		$Pattern_Position = $row->Pattern_Position;
		$Pattern_YearAktive = $row->Pattern_YearAktive;
		$Pattern_MonthAktive = $row->Pattern_MonthAktive;
		$Pattern_DateAktive = $row->Pattern_DateAktive;
		$Pattern_Length = $row->Pattern_Length;
		$useYear = $row->useYear;
		$useMonth = $row->useMonth;
		$useDate = $row->useDate;
	endforeach;
	if($Pattern_Position == 'Especially')
	{
		$Pattern_YearAktive = date('Y');
		$Pattern_MonthAktive = date('m');
		$Pattern_DateAktive = date('d');
	}
	$year = substr((int)$Pattern_YearAktive, 2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_MonthAktive;
	$konst = "";
	
	//$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	//$myCount 	= $this->db->count_all('tbl_position_func');
	$myCount	= 0;
	$sqlMAX		= "SELECT MAX(Patt_Number) AS MAX_PATTNumb FROM tbl_position_str";
	$resMAX 	= $this->db->query($sqlMAX)->result();
	foreach($resMAX as $rowMAX) :
		$myCount= $rowMAX->MAX_PATTNumb;
	endforeach;
	
	//$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	//$this->db->select('Patt_Number');
	//$result = $this->db->get('tbl_position_func')->result();
	
	// karena untuk nomor employee tidak ada ketentuan berdasarkan tahun, bulan dan tanggal, maka lgsung menhgitung jumlah row.
	/*if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->Patt_Number;
		endforeach;
	}	else	{		$myMax = 1;	}*/
	
	$myMax = $myCount + 1;
	
	$thisMonth = $month;
	
	$lenMonth = strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth = $nolMonth.$thisMonth;
	
	$thisDate = 24;
	$lenDate = strlen($thisDate);
	if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
	$pattDate = $nolDate.$thisDate;
	
	// group year, month and date
	if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$year$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$year$pattMonth";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$year$pattDate";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "$year";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$pattMonth";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$pattDate";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "";	
		
	$lastPatternNumb = $myMax;
	$len = strlen($lastPatternNumb);
	
	if($Pattern_Length==2)
	{
		if($len==1) $nol="0";
	}
	elseif($Pattern_Length==3)
	{if($len==1) $nol="00";else if($len==2) $nol="0";
	}
	elseif($Pattern_Length==4)
	{
		if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";else if($len==4) $nol="";
	}
	elseif($Pattern_Length==5)
	{
		if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";else if($len==5) $nol="";
	}
	elseif($Pattern_Length==6)
	{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
	}
	elseif($Pattern_Length==7)
	{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
	}
	
	$lastPatternNumb = $nol.$lastPatternNumb;
	$DocNumber 	= "$Pattern_Code$groupPattern$konst$lastPatternNumb";
	
	$POSS_NO 		= $DocNumber;	
	$POSS_CODE 		= '';		
	$POSS_NAME 		= '';
	$POSS_DESC		= '';
	$POSS_LEVEL		= '';
	$POSS_PARENT	= '';
	$POSS_ALLOW		= 0;
	$TOT_ALLOW		= 0;
	$POSS_ALLOW_VAL	= 0;
	$DEPCODE		= '';
	$POSS_ISLAST	= 0;
	$POSS_STAT		= 1;
	$chkAllow		= 0;
}	
else
{
	$POSS_NO 		= $default['POSS_NO'];
	$POSS_CODE 		= $default['POSS_CODE'];		
	$POSS_NAME 		= $default['POSS_NAME'];
	$POSS_DESC		= $default['POSS_DESC'];
	$POSS_LEVEL		= $default['POSS_LEVEL'];
	$POSS_PARENT	= $default['POSS_PARENT'];
	$POSS_ALLOW		= $default['POSS_ALLOW'];
	$POSS_ALLOW_VAL	= $default['POSS_ALLOW_VAL'];
	$TOTALLOW		= 0;
	$sqlTOT			= "SELECT SUM(POSS_ALLOW) AS TOTALLOW FROM tbl_position_str WHERE POSS_PARENT = '$POSS_PARENT' AND POSS_STAT = 1";
	$resTOT			= $this->db->query($sqlTOT)->result();
	foreach($resTOT as $rowTOT) :
		$TOTALLOW = $rowTOT->TOTALLOW;
	endforeach;
	$TOT_ALLOW	= $TOTALLOW;
	$POSS_ISLAST= $default['POSS_ISLAST'];
	$POSS_STAT	= $default['POSS_STAT'];
	$chkAllow	= $default['chkAllow'];
}
$ALLOW_AMOUNT	= 500000000; // HARDCORE
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

	<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
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
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'SectionName')$SectionName = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'SectionLevel')$SectionLevel = $LangTransl;
			if($TranslCode == 'Parent')$Parent = $LangTransl;
			if($TranslCode == 'LastPosition')$LastPosition = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'StructuralPosition')$StructuralPosition = $LangTransl;
			if($TranslCode == 'Organization')$Organization = $LangTransl;
			if($TranslCode == 'StruckturalAllowance')$StruckturalAllowance = $LangTransl;
			if($TranslCode == 'TotalAllowance')$TotalAllowance = $LangTransl;
			if($TranslCode == 'checkAllowance')$checkAllowance = $LangTransl;
			if($TranslCode == 'AllowanceAmmount')$AllowanceAmmount = $LangTransl;
			if($TranslCode == 'Nomor')$Nomor = $LangTransl;
			if($TranslCode == 'Active')$Active = $LangTransl;
			if($TranslCode == 'Inactive')$Inactive = $LangTransl;
			if($TranslCode == 'codeExist')$codeExist = $LangTransl;
		endforeach;

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <?php echo $Add; ?>
			    <small><?php echo $StructuralPosition; ?></small>
			 </h1>
		</section>

		<section class="content">
		    <div class="box box-primary">
		        <div class="box-header with-border" style="display: none;">
		            <h3 class="box-title">&nbsp;</h3>                
		            <div class="box-tools pull-right">
		                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                </button>
		                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		            </div>
		        </div>
		        <div class="box-body chart-responsive">
		            <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkData()">
		                <div class="form-group" style="display: none;">
		                  	<label class="col-sm-2 control-label"><?php echo $Nomor; ?> </label>
		                  	<div class="col-sm-10">
		                    	<input type="text" name="POSS_NO1" id="POSS_NO1" value="<?php echo $POSS_NO; ?>" class="form-control" style="max-width:150px" disabled>
		                    	<input type="hidden" name="POSS_NO" id="POSS_NO" value="<?php echo $POSS_NO; ?>" class="form-control" style="max-width:150px">
		                    	<input type="hidden" name="ALLOW_AMOUNT" id="ALLOW_AMOUNT" value="<?php echo $ALLOW_AMOUNT; ?>" class="form-control" style="max-width:150px">
		                    </div>
		                </div>
		              	<div class="form-group"> <!-- POSS_CODE -->
		                	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?> </label>
		                	<div class="col-sm-10">
								<!-- <label> -->
		                            <input type="text" class="form-control" name="POSS_CODE" id="POSS_CODE" value="<?php echo $POSS_CODE; ?>" placeholder="<?php echo $Code; ?>" maxlength="20" onChange="chkSTR(this.value)" <?php if($task == 'edit') { ?> readonly <?php } ?>>
		                        <!-- </label><label>&nbsp;&nbsp;</label><label id="isHidden"></label>
		                        <input type="hidden" name="CheckThe_Code" id="CheckThe_Code" value="" size="20" maxlength="25" > -->
		                    </div>
		              	</div>
		                <script>
		                    function chkSTR(myValue)
		                    {
		                        var ajaxRequest;
		                        try
		                        {
		                            ajaxRequest = new XMLHttpRequest();
		                        }
		                        catch (e)
		                        {
		                            alert("Something is wrong");
		                            return false;
		                        }
		                        ajaxRequest.onreadystatechange = function()
		                        {
		                            if(ajaxRequest.readyState == 4)
		                            {
		                                recordcount = ajaxRequest.responseText;

		                                if(recordcount > 0)
		                                {
		                                	swal("<?php echo $codeExist; ?>",
								           	{
								           		icon: "warning"
								           	});
		                                    /*document.getElementById('CheckThe_Code').value= recordcount;
		                                    document.getElementById("isHidden").innerHTML = ' Project Code already exist ... !';
		                                    document.getElementById("isHidden").style.color = "#ff0000";*/
		                                    document.getElementById("btnSave").style.display = "none";
		                                }
		                                else
		                                {
		                                    /*document.getElementById('CheckThe_Code').value= recordcount;
		                                    document.getElementById("isHidden").innerHTML = ' Project Code : OK .. !';
		                                    document.getElementById("isHidden").style.color = "green";*/
		                                    document.getElementById("btnSave").style.display = "";
		                                }
		                            }
		                        }
								
		                        ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_hr/c_organiz/c_position_str/getTheCode/';?>" + myValue, true);
		                        ajaxRequest.send(null);
		                    }
		                </script>
		                <div class="form-group" style="display: none;"> <!-- POSS_LEVEL -->
		                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $SectionLevel; ?></label>
		                    <div class="col-sm-10">
		                        <select name="POSS_LEVEL" id="POSS_LEVEL" class="form-control select2">
		                        	<option value="BOD" <?php if($POSS_LEVEL == 'BOD') { ?>selected<?php } ?>>Direktorat</option>
		                        	<option value="DEPT" <?php if($POSS_LEVEL == 'DEPT') { ?>selected<?php } ?>>Department</option>
		                        	<option value="DIV" <?php if($POSS_LEVEL == 'DIV') { ?>selected<?php } ?>>Dvisi</option>
		                        	<option value="BIRO" <?php if($POSS_LEVEL == 'BIRO') { ?>selected<?php } ?>>Biro</option>
		                        	<option value="UNIT" <?php if($POSS_LEVEL == 'UNIT') { ?>selected<?php } ?>>Unit</option>
		                        	<option value="URS" <?php if($POSS_LEVEL == 'URS') { ?>selected<?php } ?>>Urusan</option>
		                        	<option value="STAF" <?php if($POSS_LEVEL == 'STAF') { ?>selected<?php } ?>>Staf</option>
		                        </select>
		                    </div>
		                </div>
		                <div class="form-group"> <!-- POSS_PARENT -->
		                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $Parent ?></label>
		                    <div class="col-sm-10">
		                        <select name="POSS_PARENT" id="POSS_PARENT" class="form-control select2" onChange="chkTot()" >
		                        	<option value="0" > --- </option>
									<?php
		                            if($countParent>0)
		                            {
		                                $i = 0;
		                                foreach($vwParent as $row) :
											$POSS_CODE1		= $row->POSS_CODE;
											$POSS_NAME1		= $row->POSS_NAME;
											$POSS_PARENT1	= $row->POSS_PARENT;
											$POSS_LEVIDX1	= $row->POSS_LEVIDX;
											if($POSS_LEVIDX1 == 0)
												$SPACELEV 	= "";
											elseif($POSS_LEVIDX1 == 1)
												$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($POSS_LEVIDX1 == 2)
												$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($POSS_LEVIDX1 == 3)
												$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($POSS_LEVIDX1 == 4)
												$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($POSS_LEVIDX1 == 5)
												$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($POSS_LEVIDX1 == 6)
												$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($POSS_LEVIDX1 == 7)
												$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($POSS_LEVIDX1 == 8)
												$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($POSS_LEVIDX1 == 9)
												$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											elseif($POSS_LEVIDX1 == 10)
												$SPACELEV 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											
											$sqlC1		= "tbl_position_str WHERE POSS_PARENT = '$POSS_CODE1'";
											$ressqlC1 	= $this->db->count_all($sqlC1);
											?>
		                                		<option value="<?php echo $POSS_CODE1;?>" <?php if($POSS_CODE1 == $POSS_PARENT) { ?>selected<?php } if($ressqlC1>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$SPACELEV$POSS_NAME1";?></option>
		                            		<?php
										endforeach;
		                            }
		                            ?>
								</select>
		                    </div>
		                </div>
		                <div class="form-group">
		                  	<label class="col-sm-2 control-label"><?php echo $SectionName; ?> </label>
		                  	<div class="col-sm-10">
		                    	<input type="text" name="POSS_NAME" id="POSS_NAME" value="<?php echo $POSS_NAME; ?>" class="form-control">
		                    </div>
		                </div>
		                <div class="form-group" style="display: none;">
		                  	<label class="col-sm-2 control-label"><?php echo $StruckturalAllowance; ?> (%)</label>
		                  	<div class="col-sm-10">
		                    	<input type="text" name="POSS_ALLOW1" id="POSS_ALLOW1" value="<?php print number_format($POSS_ALLOW, 2); ?>" class="form-control" style="max-width:70px; text-align:right" onBlur="changePerc(this.value)" onKeyPress="return isIntOnlyNew(event);">
		                    	<input type="hidden" name="POSS_ALLOW" id="POSS_ALLOW" value="<?php echo $POSS_ALLOW; ?>" class="form-control" style="max-width:450px">
		                    </div>
		                </div>
		                <div class="form-group" style="display: none;">
		                  	<label class="col-sm-2 control-label"><?php echo $TotalAllowance; ?> (%)</label>
		                  	<div class="col-sm-10">
		                    	<input type="text" name="POSS_TOTPERC1" id="POSS_TOTPERC1" value="<?php print number_format($TOT_ALLOW, 2); ?>" class="form-control" style="max-width:70px; text-align:right" disabled>
		                    	<input type="hidden" name="POSS_TOTPERC" id="POSS_TOTPERC" value="<?php echo $TOT_ALLOW; ?>" class="form-control" style="max-width:70px; text-align:right">
		                    </div>
		                </div>
		                <div class="form-group" style="display: none;">
		                    <label for="inputEmail" class="col-sm-2 control-label" style="color:#F00"><?php echo $checkAllowance; ?>...?</label>
		                    <div class="col-sm-10">
		                        <input type="checkbox" name="chkAllow" id="chkAllow" value="1" <?php if($chkAllow==1) { ?> checked <?php } ?> onClick="checkAmount()">
		                    </div>
		                </div>
		                <script>
							function checkAmount()
							{
								chkAllow1 = document.getElementById('chkAllow').checked;
								//alert(chkAllow1)
								if(chkAllow1 == true)
								{
									var ajaxRequest;
									try
									{
										ajaxRequest = new XMLHttpRequest();
									}
									catch (e)
									{
										alert("Something is wrong");
										return false;
									}
									ajaxRequest.onreadystatechange = function()
									{
										if(ajaxRequest.readyState == 4)
										{
											recordcount = ajaxRequest.responseText;
											document.getElementById('POSS_ALLOW_VAL1').value	= DecFormat(RoundNDecimal(parseFloat(Math.abs(recordcount)), 2));
											document.getElementById('POSS_ALLOW_VAL').value		= recordcount;
										}
									}
									var POSS_PARENT 	= document.getElementById('POSS_PARENT').value;
									var POSS_ALLOW		= document.getElementById('POSS_ALLOW').value;
									var ALLOW_AMOUNT 	= document.getElementById('ALLOW_AMOUNT').value;
									data				= POSS_PARENT+'~'+POSS_ALLOW+'~'+ALLOW_AMOUNT;
									
									ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_hr/c_organiz/c_position_str/getAmount/';?>" + data, true);
									ajaxRequest.send(null);
								}
								else
								{
									document.getElementById('POSS_ALLOW_VAL1').value	= DecFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
									document.getElementById('POSS_ALLOW_VAL').value		= 0;
								}
							}
							
							function chkTot()
							{
								PercVal	= document.getElementById('POSS_ALLOW').value;
								functioncheck(PercVal)
							}
							
							function changePerc(thisVal)
							{
								document.getElementById('POSS_ALLOW1').value	= DecFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)), 2));
								document.getElementById('POSS_ALLOW').value		= thisVal;
								var PercVal	= thisVal;
								functioncheck(PercVal)
							}
							
							function functioncheck(PercVal)
							{
								var ajaxRequest;
								try
								{
									ajaxRequest = new XMLHttpRequest();
								}
								catch (e)
								{
									alert("Something is wrong");
									return false;
								}
								ajaxRequest.onreadystatechange = function()
								{
									if(ajaxRequest.readyState == 4)
									{
										recordcount = ajaxRequest.responseText;
										document.getElementById('POSS_TOTPERC1').value	= DecFormat(RoundNDecimal(parseFloat(Math.abs(recordcount)), 2));
										document.getElementById('POSS_TOTPERC').value	= recordcount;
									}
								}
								var POSS_PARENT = document.getElementById('POSS_PARENT').value;
								data		= POSS_PARENT+'~'+PercVal;
								
								ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_hr/c_organiz/c_position_str/getTot/';?>" + data, true);
								ajaxRequest.send(null);
							}
							
							function changeAmount(thisVal)
							{
								document.getElementById('POSS_ALLOW_VAL1').value	= DecFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)), 2));
								document.getElementById('POSS_ALLOW_VAL').value		= thisVal;
							}
						</script>
		                <div class="form-group" style="display: none;">
		                  	<label class="col-sm-2 control-label"><?php echo $AllowanceAmmount; ?> (Rp.)</label>
		                  	<div class="col-sm-10">
		                    	<input type="text" name="POSS_ALLOW_VAL1" id="POSS_ALLOW_VAL1" value="<?php print number_format($POSS_ALLOW_VAL, 2); ?>" onBlur="changeAmount(this.value)" class="form-control" style="max-width:150px; text-align:right">
		                    	<input type="hidden" name="POSS_ALLOW_VAL" id="POSS_ALLOW_VAL" value="<?php echo $POSS_ALLOW_VAL; ?>" class="form-control" style="max-width:70px; text-align:right">
		                    </div>
		                </div>
		                <div class="form-group">
		                  	<label class="col-sm-2 control-label"><?php echo $Description; ?></label>
		                  	<div class="col-sm-10">
		                    	<input type="text" name="POSS_DESC" id="POSS_DESC" value="<?php echo $POSS_DESC; ?>" class="form-control">
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $LastPosition ?></label>
		                    <div class="col-sm-10">
		                    	<select name="POSS_ISLAST" id="POSS_ISLAST" class="form-control select2" >
		                            <option value="1" <?php if($POSS_ISLAST == 1) { ?> selected <?php } ?>>Yes</option>
		                            <option value="0" <?php if($POSS_ISLAST == 0) { ?> selected <?php } ?>>No</option>
		                        </select>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $Status ?></label>
		                    <div class="col-sm-10">
		                    	<select name="POSS_STAT" id="POSS_STAT" class="form-control select2" >
		                            <option value="1" <?php if($POSS_STAT == 1) { ?> selected <?php } ?>><?php echo $Active; ?></option>
		                            <option value="0" <?php if($POSS_STAT == 0) { ?> selected <?php } ?>><?php echo $Inactive; ?></option>
		                        </select>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <div class="col-sm-offset-2 col-sm-10">
		                    	<?php
									if($ISCREATE == 1)
									{
										if($task=='add')
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
												</button>&nbsp;
											<?php
										}
										else
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
												</button>&nbsp;
											<?php
										}
									}
									echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
								?>
		                    </div>
		                </div>
		            </form>
		            <?php
		                $DefID      = $this->session->userdata['Emp_ID'];
		                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		                if($DefID == 'D15040004221')
		                    echo "<font size='1'><i>$act_lnk</i></font>";
		            ?>
		            <script>
						function checkData()
						{
							POSS_CODE = document.getElementById('POSS_CODE').value;
							POSS_NAME = document.getElementById('POSS_NAME').value;
							if(POSS_CODE == '')
							{
								alert('Please input Department Code.');
								document.getElementById("POSS_CODE").focus();
								return false;
							}
							if(POSS_NAME == '')
							{
								alert('Please input Department Name.');
								document.getElementById("POSS_NAME").focus();
								return false;
							}		
						}
					</script>
		        </div>
		    </div>
		</section>
	</body>
</html>
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
	
	function DecFormat(angka)
	{
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