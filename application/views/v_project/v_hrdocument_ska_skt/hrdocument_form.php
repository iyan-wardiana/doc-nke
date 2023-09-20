<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Februari 2017
 * File Name	= hrdocument_form.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');
$comp_color = $this->session->userdata('comp_color');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$ISREAD 	= $this->session->userdata['ISREAD'];
$ISCREATE 	= $this->session->userdata['ISCREATE'];
$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

if($task == 'add')
{		
	foreach($viewDocPattern as $row) :
		$Pattern_Code 		= $row->Pattern_Code;
		$Pattern_Position 	= $row->Pattern_Position;
		$Pattern_YearAktive = $row->Pattern_YearAktive;
		$Pattern_MonthAktive= $row->Pattern_MonthAktive;
		$Pattern_DateAktive = $row->Pattern_DateAktive;
		$Pattern_Length 	= $row->Pattern_Length;
		$useYear 			= $row->useYear;
		$useMonth 			= $row->useMonth;
		$useDate 			= $row->useDate;
	endforeach;
	if($Pattern_Position == 'Especially')
	{
		$Pattern_YearAktive = date('Y');
		$Pattern_MonthAktive = date('m');
		$Pattern_DateAktive = date('d');
	}
	$yearx		= substr((int)$Pattern_YearAktive, -2);
	$year 		= (int)$Pattern_YearAktive;
	$month 		= (int)$Pattern_MonthAktive;
	$date 		= (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	$this->db->where('Patt_Month', $month);
	$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_hrdoc_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_hrdoc_header
			WHERE Patt_Year = $year";
	$result = $this->db->query($sql)->result();
	
	if($result>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}
	
	$thisMonth = $month;
	
	$lenMonth = strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth = $nolMonth.$thisMonth;
	
	$thisDate = $date;
	$lenDate = strlen($thisDate);
	if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
	$pattDate = $nolDate.$thisDate;
	
	// group year, month and date
	if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$yearx$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$yearx$pattMonth";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$yearx$pattDate";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "$yearx";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$pattMonth";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$pattDate";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "";
	
		
	$lastPatternNumb = $myMax;
	$lastPatternNumb1 = $myMax;
	$len = strlen($lastPatternNumb);
	
	if($Pattern_Length==2)
	{
		if($len==1) $nol="0";
	}
	elseif($Pattern_Length==3)
	{if($len==1) $nol="00";else if($len==2) $nol="0";
	}
	elseif($Pattern_Length==4)
	{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
	}
	elseif($Pattern_Length==5)
	{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
	}
	elseif($Pattern_Length==6)
	{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
	}
	elseif($Pattern_Length==7)
	{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
	}
	$lastPatternNumb 	= $nol.$lastPatternNumb;
	$DocNumber 			= "$Pattern_Code$DOCCODE-$lastPatternNumb";
	$HRDOCNO			= "$Pattern_Code$DOCCODE-$lastPatternNumb";
	
	$HRDOCNO			= $HRDOCNO;
	$DocNumber			= $HRDOCNO;
	$HRDOCCODE			= '';
	$DOCCODE			= $DOCCODE;
	$HRDOCTYPE			= '';
	$YearD 				= date('Y');
	$MonthD 			= date('m');
	$DaysD 				= date('d');
	$TRXDATE			= "$MonthD/$DaysD/$YearD";
	$PRJCODE			= '';
	$OWNER_CODE			= '';
	$OWNER_DESC			= '';
	$OWNER_ADD			= '';
	$HRDOCCOSTX			= 0;
	$HRDOCCOST 			= str_replace(',', '', $HRDOCCOSTX);
	$HRDOCJNS			= 1;
	$HRDOCJML			= 1;
	$HRDOCLBR			= 1;
	$HRDOCLOK			= '';
	$TRXUSER			= $DefEmp_ID;
	$START_DATE			= $TRXDATE;
	$END_DATE			= $TRXDATE;
	if($START_DATE == '')
	{
		$SHOW_SE_DATE	= 0;
		$START_DATE		= date("m/d/Y", strtotime($TRXDATEX));
		$END_DATE		= date("m/d/Y", strtotime($TRXDATEX));
	}
	else
	{
		$SHOW_SE_DATE	= 1;
		$START_DATE		= date("m/d/Y", strtotime($START_DATE));
		$END_DATE		= date("m/d/Y", strtotime($END_DATE));
	
	}
	$HRDOCSTAT			= 1;
	$HRDOC_NAME			= '';
	$PM_EMPCODE			= '';
	$PM_NAME			= '';
	$PM_STATUS			= '';
	$DIR_EMPCODE		= '';
	$DIR_NAME			= '';
	$STATUS_DOK			= 'Ready';
	$BORROW_EMP			= '';
	$PEMILIK_MODAL		= '';
	$HRDOC_NOTE			= '';	
	$Patt_Year 			= date('Y');
	$Patt_Month			= date('m');
	$Patt_Date 			= date('Y');
	$Patt_Number		= $myMax;
	$SHOW_SE_DATE		= 1;
	$lastPatternNumb1	= $lastPatternNumb1;
	
	$PRJNAME 			= '';
	$sql 				= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$result 			= $this->db->query($sql)->result();
	foreach($result as $row) :
		$PRJNAME 		= $row ->PRJNAME;
	endforeach;
	
	$HRD_EMPID			= '';
	$HRD_CUALIF			= 'AU';
	$HRD_PUBLISHER		= '';
}
else
{
	$HRDOCNO			= $default['HRDOCNO'];
	$DocNumber			= $default['HRDOCNO'];
	$HRDOCCODE			= $default['HRDOCCODE'];
	$DOCCODE			= $default['DOCCODE'];
	$HRDOCTYPE			= $default['HRDOCTYPE'];
	$TRXDATEX			= $default['TRXDATE'];
	$TRXDATE			= date("m/d/Y", strtotime($TRXDATEX));
	$PRJCODE			= $default['PRJCODE'];
	$OWNER_CODE			= $default['OWNER_CODE'];
	$OWNER_DESC			= $default['OWNER_DESC'];
	$OWNER_ADD			= $default['OWNER_ADD'];
	$HRDOCCOSTX			= $default['HRDOCCOST'];
	$HRDOCCOST 			= str_replace(',', '', $HRDOCCOSTX);
	if($HRDOCCOST == '')
		$HRDOCCOST		= 0;
	$HRDOCJNS			= $default['HRDOCJNS'];
	$HRDOCJML			= $default['HRDOCJML'];
	$HRDOCLBR			= $default['HRDOCLBR'];
	$HRDOCLOK			= $default['HRDOCLOK'];
	$TRXUSER			= $default['TRXUSER'];
	$START_DATE			= $default['START_DATE'];
	$END_DATE			= $default['END_DATE'];
	if($START_DATE == '')
	{
		$SHOW_SE_DATE	= 0;
		$START_DATE		= date("m/d/Y", strtotime($TRXDATEX));
		$END_DATE		= date("m/d/Y", strtotime($TRXDATEX));
	}
	else
	{
		$SHOW_SE_DATE	= 1;
		$START_DATE		= date("m/d/Y", strtotime($START_DATE));
		$END_DATE		= date("m/d/Y", strtotime($END_DATE));
	
	}
	$HRDOCSTAT			= $default['HRDOCSTAT'];
	$HRDOC_NAME			= $default['HRDOC_NAME'];
	$PM_EMPCODE			= $default['PM_EMPCODE'];
	$PM_NAME			= $default['PM_NAME'];
	$PM_STATUS			= $default['PM_STATUS'];
	$DIR_EMPCODE		= $default['DIR_EMPCODE'];
	$DIR_NAME			= $default['DIR_NAME'];
	$STATUS_DOK			= $default['STATUS_DOK'];
	$BORROW_EMP			= $default['BORROW_EMP'];
	$PEMILIK_MODAL		= $default['PEMILIK_MODAL'];
	$HRDOC_NOTE			= $default['HRDOC_NOTE'];
	$Patt_Date 			= $default['Patt_Date'];
	$Patt_Month 		= $default['Patt_Month'];
	$Patt_Year 			= $default['Patt_Year'];
	$Patt_Number 		= $default['Patt_Number'];
	$lastPatternNumb1	= $default['Patt_Number'];
	
	$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sql)->result();
	foreach($result as $row) :
		$PRJNAME = $row ->PRJNAME;
	endforeach;
	
	$HRD_EMPID 			= $default['HRD_EMPID'];
	$HRD_CUALIF 		= $default['HRD_CUALIF'];
	$HRD_PUBLISHER		= $default['HRD_PUBLISHER'];
}

$doc_codeA 		= '';
$doc_parentA	= '';
$doc_nameA 		= '';
$sqlA 			= "SELECT doc_code, doc_parent, doc_name FROM tbl_document WHERE doc_code = '$DOCCODE' AND isHRD = 1";
$resultA 		= $this->db->query($sqlA)->result();
foreach($resultA as $rowA) :
	$doc_codeA 		= $rowA->doc_code;
	$doc_parentA	= $rowA->doc_parent;
	$doc_nameA 		= $rowA->doc_name;
endforeach;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers   = $this->session->userdata['vers'];

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
		// $this->load->view('template/topbar');
		// $this->load->view('template/sidebar');

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
			
			if($TranslCode == 'DocCode')$DocCode = $LangTransl;
			if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'DocType')$DocType = $LangTransl;
			if($TranslCode == 'DocPageQty')$DocPageQty = $LangTransl;
			if($TranslCode == 'DocGroup')$DocGroup = $LangTransl;
			if($TranslCode == 'EmployeeID')$EmployeeID = $LangTransl;
			if($TranslCode == 'Cualification')$Cualification = $LangTransl;
			if($TranslCode == 'Publisher')$Publisher = $LangTransl;
			if($TranslCode == 'Location')$Location = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'ChecktoShowStartandEndDate')$ChecktoShowStartandEndDate = $LangTransl;
			if($TranslCode == 'KeyWords')$KeyWords = $LangTransl;
			
			if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
			if($TranslCode == 'OwnerName')$OwnerName = $LangTransl;
			if($TranslCode == 'PMDescription')$PMDescription = $LangTransl;
			if($TranslCode == 'OwnerAddress')$OwnerAddress = $LangTransl;
			if($TranslCode == 'DocumentValue')$DocumentValue = $LangTransl;
			if($TranslCode == 'PMName')$PMName = $LangTransl;
			if($TranslCode == 'PMDescription')$PMDescription = $LangTransl;
			if($TranslCode == 'PMStatus')$PMStatus = $LangTransl;
			if($TranslCode == 'DirName')$DirName = $LangTransl;
			if($TranslCode == 'DirDescription')$DirDescription = $LangTransl;
			if($TranslCode == 'DocPosition')$DocPosition = $LangTransl;
			if($TranslCode == 'Borrower')$Borrower = $LangTransl;
			if($TranslCode == 'DocFile')$DocFile = $LangTransl;


		endforeach;
	?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/mc.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h2_title; ?>
                <small><?php echo $doc_name; ?></small>
            </h1>
        </section>

        <section class="content">	
            <div class="row">
            	<form name="frm" class="form-horizontal" action="<?php echo $form_action; ?>" onSubmit="return validateInData();" method="POST" enctype="multipart/form-data">
	                <div class="col-md-6">
	                    <div class="box box-primary">
	                        <div class="box-header with-border" style="display:none">               
	                      		<div class="box-tools pull-right">
	                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                                </button>
	                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
	                            </div>
	                        </div>
	                        <div class="box-body chart-responsive">
							    <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
								<input type="Hidden" name="rowCount" id="rowCount" value="0">
							    <input type="hidden" name="istask" id="istask" value="<?php echo $task; ?>">
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label">No. Dokumen</label>
	                              	<div class="col-sm-4">
	                                    <input type="text" name="DocNumber" id="DocNumber" value="<?php echo "$HRDOCNO"; ?>" class="form-control" disabled>
			                            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPatternNumb1; ?>" class="form-control">
			                            <input type="hidden" name="HRDOCNO" id="HRDOCNO" value="<?php echo $HRDOCNO; ?>" class="form-control">
			                            <input type="hidden" name="DOCCODE" id="DOCCODE" value="<?php echo $DOCCODE; ?>" class="form-control">
	                              	</div>
	                              	<div class="col-sm-5">
	                                    <input type="text" name="HRDOCCODE" id="HRDOCCODE" value="<?php echo $HRDOCCODE; ?>" class="form-control" placeholder="No. Dokumen">
	                              	</div>
	                            </div>
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label"><?php echo $Date ?></label>
	                              	<div class="col-sm-9">
	                                    <div class="input-group date">
			                                <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                                <input type="text" name="TRXDATE" class="form-control pull-left" id="datepicker1" value="<?php echo $TRXDATE; ?>" style="width:150px">
			                            </div>
	                              	</div>
	                            </div>
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
	                              	<div class="col-sm-9">
	                                    <input type="checkbox" name="SHOW_SE_DATE" id="SHOW_SE_DATE" value="<?php echo $SHOW_SE_DATE; ?>" <?php if($SHOW_SE_DATE == 1) { ?> checked <?php } ?> onClick="showSEdATE(this);">&nbsp;&nbsp;<?php echo $ChecktoShowStartandEndDate ?>
	                              	</div>
	                            </div>
			                    <script>
			                        function showSEdATE(thisVal)
			                        {
			                            if(thisVal.checked)
			                            {
			                                document.getElementById("s_Date").style.display = '';
			                                //document.getElementById("e_Date").style.display = '';
			                            }
			                            else
			                            {
			                                document.getElementById("s_Date").style.display = 'none';
			                                //document.getElementById("e_Date").style.display = 'none';
			                            }
			                        }
			                    </script>
	                            <div class="form-group" id="s_Date" <?php if($SHOW_SE_DATE == 0) { ?> style="display:none" <?php } ?>>
	                              	<label for="inputName" class="col-sm-3 control-label"><?php echo "Periode"; ?></label>
	                              	<div class="col-sm-4">
	                                    <div class="input-group date">
			                                <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                                <input type="text" name="START_DATE" class="form-control pull-left" id="datepicker2" value="<?php echo $START_DATE; ?>">
			                            </div>
	                              	</div>
	                              	<label for="inputName" class="col-sm-1 control-label">s.d.</label>
	                              	<div class="col-sm-4">
	                                    <div class="input-group date">
			                                <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                                <input type="text" name="END_DATE" class="form-control pull-left" id="datepicker3" value="<?php echo $END_DATE; ?>">
			                            </div>
	                              	</div>
	                            </div>
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label"><?php echo $DocType ?></label>
	                              	<div class="col-sm-4">
	                                    <select name="HRDOCTYPE" id="HRDOCTYPE" class="form-control select2">
				                            <option value="1" <?php if($HRDOCTYPE == 1) { ?>selected <?php } ?>> ASLI </option>
				                            <option value="2" <?php if($HRDOCTYPE == 2) { ?>selected <?php } ?>> COPY </option>
			                          	</select>
	                              	</div>
	                              	<label for="inputName" class="col-sm-3 control-label">Jml. Halaman</label>
	                              	<div class="col-sm-2">
	                                    <input type="text" class="form-control" name="HRDOCJMLX" id="HRDOCJMLX" style="text-align:right;" value="<?php echo number_format($HRDOCJML, 0); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getHRDOCJML(this)">
                            			<input type="hidden" class="form-control" name="HRDOCJML" id="HRDOCJML" style="text-align:right" value="<?php echo $HRDOCJML; ?>" size="10">
	                              	</div>
	                            </div>
			                    <script>
			                        function getHRDOCJML(thisVal)
			                        {
			                            var decFormat	= document.getElementById('decFormat').value;
			                            var thisVal		= eval(thisVal).value.split(",").join("");
			                            HRDOCJML		= thisVal;
			                            document.getElementById('HRDOCJML').value 	= HRDOCJML;
			                            document.getElementById('HRDOCJMLX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(HRDOCJML)),decFormat));
			                        }
			                    </script>
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label"><?php echo $Location ?></label>
	                              	<div class="col-sm-4">
	                                    <input type="text" class="form-control" name="HRDOCLOK" id="HRDOCLOK" value="<?php print $HRDOCLOK; ?>" placeholder="Lokasi Dokumen">
	                              	</div>
	                              	<label for="inputName" class="col-sm-2 control-label">Tipe</label>
	                              	<div class="col-sm-3">
	                                    <select name="HRDOCLBR" id="HRDOCLBR" class="form-control select2">
			                                <option value="1" <?php if($HRDOCLBR == 1) { ?>selected <?php } ?>> LEMBAR </option>
			                                <option value="2" <?php if($HRDOCLBR == 2) { ?>selected <?php } ?>> BUKU </option>
			                                <option value="3" <?php if($HRDOCLBR == 3) { ?>selected <?php } ?>> BUKU TIPIS </option>
			                          	</select>
	                              	</div>
	                            </div>
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label"><?php echo $DocPosition ?></label>
	                              	<div class="col-sm-4">
			                            <!-- <select name="HRDOCSTAT" id="HRDOCSTAT" class="form-control select2" onChange="showEMPID(this.value)"> -->
			                            <select name="HRDOCSTAT" id="HRDOCSTAT" class="form-control select2">
				                            <option value="1" <?php if($HRDOCSTAT == 1) { ?>selected <?php } ?>> TERSEDIA </option>
				                            <option value="2" <?php if($HRDOCSTAT == 2) { ?>selected <?php } ?>> DIPINJAM </option>
				                            <option value="2" <?php if($HRDOCSTAT == 3) { ?>selected <?php } ?>> TIDAK ADA / HILANG </option>
				                        </select>
					                    <script>
											/*function showEMPID(thisVal)
											{
												if(thisVal == 1)
													document.getElementById("showBorrowed").style.display = '';
												else
													document.getElementById("showBorrowed").style.display = '';
											}*/
										</script>
	                              	</div>
	                              	<div class="col-sm-5">
			                            <select name="BORROW_EMP" id="BORROW_EMP" class="form-control select2">
			                                <option value="">---</option>
			                                <?php
			                                    $CountEMP 	= $this->db->count_all('tbl_employee');
			                                    $sqlEMP 	= "SELECT EMP_ID, First_Name, Last_Name FROM tbl_employee ORDER BY First_Name";
			                                    $resultEMP = $this->db->query($sqlEMP)->result();
			                                    if($CountEMP > 0)
			                                    {
			                                        foreach($resultEMP as $rowEMP) :
			                                            $EMP_IDX 	= $rowEMP->EMP_ID;
			                                            $First_Name = $rowEMP->First_Name;
			                                            $Last_Name 	= $rowEMP->Last_Name;
			                                            ?>
			                                                <option value="<?php echo $EMP_IDX; ?>" <?php if($EMP_IDX == $BORROW_EMP) { ?>selected <?php } ?>>
			                                                    <?php echo "$EMP_IDX - $First_Name $Last_Name"; ?>
			                                                </option>
			                                            <?php
			                                         endforeach;
			                                     }
			                                ?>
			                            </select>
				                    </div>
	                            </div>
				                <?php
									if($doc_parentA == 'D0243')
									{
						            	$url_selEMP	= site_url('c_project/hrdocument/getallemp/?id='.$this->url_encryption_helper->encode_url($DOCCODE));
						                ?>
											<script>
							                    var url1 = "<?php echo $url_selEMP;?>";
							                    function pleaseCheck()
							                    {
							                        title = 'Select Item';
							                        w = 1000;
							                        h = 550;
							                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
							                        var left = (screen.width/2)-(w/2);
							                        var top = (screen.height/2)-(h/2);
							                        return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
							                    }
												
												function add_emp(strItem)
												{
													arrItem = strItem.split('|');
													Emp_ID 			= arrItem[0];
													Emp_Identity 	= arrItem[1];
													compName 		= arrItem[2];
													
													document.getElementById('HRD_EMPID1').value	= compName;
													document.getElementById('HRD_EMPID').value	= Emp_ID;
												}
							                </script>
				                            <div class="form-group">
				                              	<label for="inputName" class="col-sm-3 control-label"><?php echo $EmployeeID ?></label>
				                              	<div class="col-sm-9">
				                                    <div class="input-group">
				                                        <div class="input-group-btn">
				                                            <button type="button" class="btn btn-primary">Seacrh</button>
				                                        </div>
				                                        <input type="text" class="form-control" name="HRD_EMPID1" id="HRD_EMPID1" value="<?php echo $HRD_EMPID; ?>" onClick="pleaseCheck();">
				                                        <input type="hidden" class="form-control" name="HRD_EMPID" id="HRD_EMPID" style="max-width:160px" value="<?php echo $HRD_EMPID; ?>">
				                                    </div>
				                              	</div>
				                            </div>
				                            <div class="form-group">
				                              	<label for="inputName" class="col-sm-3 control-label"><?php echo $Cualification ?></label>
				                              	<div class="col-sm-4">
			                                        <select name="HRD_CUALIF" id="HRD_CUALIF" class="form-control select2" >
				                                        <option value="AM" <?php if($HRD_CUALIF == 'AM') { ?>selected <?php } ?>> AHLI MADYA </option>
				                                        <option value="AU" <?php if($HRD_CUALIF == 'AU') { ?>selected <?php } ?>> AHLI UTAMA </option>
				                                        <option value="TKT1" <?php if($HRD_CUALIF == 'TKT1') { ?>selected <?php } ?>> TINGKAT-1/SKT </option>
				                                        <option value="JNG1" <?php if($HRD_CUALIF == 'JNG1') { ?>selected <?php } ?>> JENJANG-1 </option>
				                                        <option value="JNG2" <?php if($HRD_CUALIF == 'JNG2') { ?>selected <?php } ?>> JENJANG-2 </option>
				                                        <option value="JNG3" <?php if($HRD_CUALIF == 'JNG3') { ?>selected <?php } ?>> JENJANG-3 </option>
				                                        <option value="JNG4" <?php if($HRD_CUALIF == 'JNG4') { ?>selected <?php } ?>> JENJANG-4 </option>
				                                        <option value="JNG5" <?php if($HRD_CUALIF == 'JNG5') { ?>selected <?php } ?>> JENJANG-5 </option>
				                                        <option value="JNG6" <?php if($HRD_CUALIF == 'JNG6') { ?>selected <?php } ?>> JENJANG-6 </option>
				                                        <option value="JNG7" <?php if($HRD_CUALIF == 'JNG7') { ?>selected <?php } ?>> JENJANG-7 </option>
				                                        <option value="JNG8" <?php if($HRD_CUALIF == 'JNG8') { ?>selected <?php } ?>> JENJANG-8 </option>
				                                        <option value="JNG9" <?php if($HRD_CUALIF == 'JNG9') { ?>selected <?php } ?>> JENJANG-9 </option>
				                                  	</select>
				                              	</div>
				                              	<div class="col-sm-5">
			                                        <select name="HRD_PUBLISHER" id="HRD_PUBLISHER" class="form-control select2" >
				                                        <option value=""><?php echo $Publisher ?>  --- </option>
				                                        <option value="ATAKI" <?php if($HRD_PUBLISHER == 'ATAKI') { ?>selected <?php } ?>> ATAKI </option>
				                                        <option value="HPJI" <?php if($HRD_PUBLISHER == 'HPJI') { ?>selected <?php } ?>> HPJI </option>
				                                        <option value="PIPI" <?php if($HRD_PUBLISHER == 'PIPI') { ?>selected <?php } ?>> PIPI </option>
				                                        <option value="LSPAKM" <?php if($HRD_PUBLISHER == 'LSPAKM') { ?>selected <?php } ?>> LSP Astekindo Konstruksi Mandiri </option>
				                                        <option value="LSPGKM" <?php if($HRD_PUBLISHER == 'LSPGKM') { ?>selected <?php } ?>> LSP Gataki Konstruksi Mandiri </option>
				                                        <option value="LSPPKM" <?php if($HRD_PUBLISHER == 'LSPPKM') { ?>selected <?php } ?>> LSP Petakindo Konstruksi Mandiri </option>
				                                        <option value="LSPATAKIM" <?php if($HRD_PUBLISHER == 'LSPATAKIM') { ?>selected <?php } ?>> LSP ATAKI Konstruksi Mandiri </option>
				                                  	</select>
				                              	</div>
				                            </div>
										<?php
				                    }
				                    else
				                    {	
										?>
											<input type="hidden" class="form-control" name="HRD_EMPID" id="HRD_EMPID" value="">
				                    	<?php
				                    }
								?>
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label"><?php echo $Description ?> <br>&nbsp;&nbsp;<em>(<?php echo $KeyWords  ?> )</em></label>
	                              	<div class="col-sm-9">
	                                    <textarea class="form-control" name="HRDOC_NOTE"  id="HRDOC_NOTE" style="height:70px" placeholder="Catatan Dokumen"><?php echo $HRDOC_NOTE; ?></textarea>
	                              	</div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-md-6">
	                    <div class="box box-warning">
	                        <div class="box-header with-border" style="display:none">               
	                      		<div class="box-tools pull-right">
	                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                                </button>
	                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
	                            </div>
	                        </div>
	                        <div class="box-body chart-responsive">
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label"><?php echo $ProjectName ?></label>
	                              	<div class="col-sm-9">
			                            <select name="PRJCODE" id="PRJCODE" class="form-control select2">
			                            	 <option value="0">---</option>
			                                <?php
			                                    $own_Code 	= '';
			                                    $CountPRJ 	= $this->db->count_all('tbl_project');
			                                    $sqlPRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project";
			                                    $resultPRJ = $this->db->query($sqlPRJ)->result();
			                                    if($CountPRJ > 0)
			                                    {
			                                        foreach($resultPRJ as $rowPRJ) :
			                                            $PRJCODEA = $rowPRJ->PRJCODE;
			                                            $PRJNAMEA = $rowPRJ->PRJNAME;
			                                            ?>
			                                                <option value="<?php echo $PRJCODEA; ?>" <?php if($PRJCODEA == $PRJCODE) { ?>selected <?php } ?>>
			                                                    <?php echo "$PRJCODEA - $PRJNAMEA"; ?>                                                </option>
			                                            <?php
			                                         endforeach;
			                                     }
			                                ?>
			                            </select>
	                              	</div>
	                            </div>
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label"><?php echo $OwnerName ?></label>
	                              	<div class="col-sm-9">
			                        	<select name="OWNER_CODE" id="OWNER_CODE" class="form-control select2">
	                                        <option value="">---</option>
	                                        <?php
	                                            $CountOWN 	= $this->db->count_all('tbl_owner');
	                                            $sqlOWN 	= "SELECT own_Code, own_Name FROM tbl_owner ORDER BY own_Name ASC";
	                                            $resultOWN = $this->db->query($sqlOWN)->result();
	                                            if($CountOWN > 0)
	                                            {
	                                                foreach($resultOWN as $rowOWN) :
	                                                    $own_CodeA 	= $rowOWN->own_Code;
	                                                    $own_Name = $rowOWN->own_Name;
	                                                    ?>
	                                                        <option value="<?php echo $own_CodeA; ?>" <?php if($own_CodeA == $OWNER_CODE) { ?>selected <?php } ?>>
	                                                            <?php echo "$own_Name"; ?>                                                        </option>
	                                                    <?php
	                                                 endforeach;
	                                             }
	                                        ?>
	                                    </select>
                                    	<input type="hidden" name="OWNER_DESC" id="OWNER_DESC" size="15" value="<?php echo $OWNER_DESC; ?>" class="form-control" />
	                              	</div>
	                            </div>
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label"><?php echo $OwnerAddress ?></label>
	                              	<div class="col-sm-9">
			                            <textarea class="form-control" name="OWNER_ADD"  id="OWNER_ADD" style="height:70px" placeholder="<?php echo $OwnerAddress ?>"><?php echo $OWNER_ADD; ?></textarea>
	                              	</div>
	                            </div>
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label"><?php echo $DocumentValue ?></label>
	                              	<div class="col-sm-9">
			                            <input type="text" name="HRDOCCOSTX" id="HRDOCCOSTX" size="15" value="<?php echo number_format($HRDOCCOST, 0); ?>" class="form-control" style="text-align:right; max-width:150px" onKeyPress="return isIntOnlyNew(event);" onBlur="getHRDOCCOST(this)" />
                            			<input type="hidden" name="HRDOCCOST" id="HRDOCCOST" size="15" value="<?php echo $HRDOCCOST; ?>" class="form-control" style="text-align:right" />
	                              	</div>
									<script>
										function getHRDOCCOST(thisVal)
										{
											var decFormat	= document.getElementById('decFormat').value;
											var thisVal		= eval(thisVal).value.split(",").join("");
											HRDOCCOST			= thisVal;
											document.getElementById('HRDOCCOST').value 	= HRDOCCOST;
											document.getElementById('HRDOCCOSTX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(HRDOCCOST)),decFormat));
										}
									</script>
	                            </div>
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label"><?php echo $PMName ?> / Status</label>
	                              	<div class="col-sm-5">
			                        	<select name="PM_EMPCODE" id="PM_EMPCODE" class="form-control select2">
	                                        <option value="">---</option>
	                                        <?php
	                                            $CountEMP 	= $this->db->count_all('tbl_employee');
	                                            $sqlEMP 	= "SELECT EMP_ID, First_Name, Last_Name FROM tbl_employee ORDER BY First_Name";
	                                            $resultEMP = $this->db->query($sqlEMP)->result();
	                                            if($CountEMP > 0)
	                                            {
	                                                foreach($resultEMP as $rowEMP) :
	                                                    $EMP_IDA 	= $rowEMP->EMP_ID;
	                                                    $First_Name = $rowEMP->First_Name;
	                                                    $Last_Name 	= $rowEMP->Last_Name;
	                                                    ?>
	                                                        <option value="<?php echo $EMP_IDA; ?>" <?php if($EMP_IDA == $PM_EMPCODE) { ?>selected <?php } ?>>
	                                                            <?php echo "$EMP_IDA - $First_Name $Last_Name"; ?>
	                                                        </option>
	                                                    <?php
	                                                 endforeach;
	                                             }
	                                        ?>
	                                    </select>
	                                    <input type="hidden" name="PM_NAME" id="PM_NAME" size="15" value="<?php echo $PM_NAME; ?>" class="form-control" />
	                              	</div>
	                              	<div class="col-sm-4">
			                            <input type="text" name="PM_STATUS" id="PM_STATUS" size="15" value="<?php echo $PM_STATUS; ?>" class="form-control">
	                              	</div>
	                            </div>
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label"><?php echo $DirName ?></label>
	                              	<div class="col-sm-9">
			                            <select name="DIR_EMPCODE" id="DIR_EMPCODE" class="form-control select2">
	                                        <option value="">---</option>
	                                        <?php
	                                            $CountEMP 	= $this->db->count_all('tbl_employee');
	                                            $sqlEMP 	= "SELECT EMP_ID, First_Name, Last_Name FROM tbl_employee ORDER BY First_Name";
	                                            $resultEMP = $this->db->query($sqlEMP)->result();
	                                            if($CountEMP > 0)
	                                            {
	                                                foreach($resultEMP as $rowEMP) :
	                                                    $EMP_IDA 	= $rowEMP->EMP_ID;
	                                                    $First_Name = $rowEMP->First_Name;
	                                                    $Last_Name 	= $rowEMP->Last_Name;
	                                                    ?>
	                                                        <option value="<?php echo $EMP_IDA; ?>" <?php if($EMP_IDA == $DIR_EMPCODE) { ?>selected <?php } ?>>
	                                                            <?php echo "$EMP_IDA - $First_Name $Last_Name"; ?>
	                                                        </option>
	                                                    <?php
	                                                 endforeach;
	                                             }
	                                        ?>
                                    	</select>
                                    	<input type="hidden" name="DIR_NAME" id="DIR_NAME" size="15" value="<?php echo $DIR_NAME; ?>" class="form-control" />
	                              	</div>
	                            </div>
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label"><?php echo $DocFile ?></label>
	                              	<div class="col-sm-9">
			                            <input type="file" name="userfile" class="filestyle" data-buttonName="btn-primary"/>
	                              	</div>
	                            </div>
	                            <div class="form-group">
	                              	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
	                              	<div class="col-sm-9">
			                            <button class="btn btn-primary"><i class="fa fa-save"></i></button>
			                            <?php echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>'); ?>
	                              	</div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </form>
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
</script>

<script>
	function validateInData()
	{
		nextornot = document.getElementById('CheckThe_Code').value;
		if(nextornot > 0)
		{
			alert('Project Code Already Exist. Please Change.');
			document.getElementById('PRJCODE').value = '';
			document.getElementById('PRJCODE').focus();
			return false;
		}
		
		PRJNAME = document.getElementById('PRJNAME').value;
		if(PRJNAME == '')
		{
			alert('Project Name can not be empty');
			document.getElementById('PRJNAME').focus();
			return false;
		}
		
		PRJOWN = document.getElementById('PRJOWN').value;
		if(PRJOWN == 'none')
		{
			//alert('Please chose one of Owner Project.');
			//document.getElementById('PRJOWN').focus();
			//return false;
		}
		
		var PRJDATE = new Date(document.frm.PRJDATE.value);
		
		var PRJEDAT = new Date(document.frm.PRJEDAT.value);
		
		if(PRJEDAT < PRJDATE)
		{
			alert('End Date Project must be Greater than Start Date Project.');
			return false;
		}
		
		var ISCHANGE	= document.getElementById('ISCHANGEX').value;
		if(ISCHANGE == 1)
		{
			var REFCHGNO	= document.getElementById('REFCHGNO').value;
			if(REFCHGNO == '')
			{
				alert('Please input reference number.');
				document.getElementById('REFCHGNO').focus();
				return false;
			}
			
			var PRJCOST2 = eval(document.getElementById('PRJCOST22a')).value.split(",").join("");
			if(PRJCOST2 == 0)
			{
				alert('Please input new of Contract Value.');
				document.getElementById('PRJCOST22a').focus();
				return false;
			}
		}
		else
		{
			document.getElementById('REFCHGNO').value 	= '';
			document.getElementById('PRJCOST22a').value = '0.00';
			document.getElementById('PRJCOST22').value 	= '0';
		}
	}
	
	var decFormat		= 2;
	
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
		else return (c + '.' + dec); 
	}
	
	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
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