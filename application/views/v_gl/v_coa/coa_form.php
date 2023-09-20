<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 29 April 2014
 * File Name	= Department_form.php
 * Location		= ./system/application/views/v_setting/v_position/Department_form.php
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody 	= $this->session->userdata['appBody'];
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

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$ISREAD 		= 0;
$ISCREATE 		= 0;
$ISAPPROVE 		= 0;
$ISDELETE		= 0;
$sqlAUTH		= "SELECT ISREAD, ISCREATE, ISAPPROVE, ISDELETE FROM tusermenu WHERE emp_id = '$DefEmp_ID' AND menu_code = '$MenuCode'";
$resAUTH 		= $this->db->query($sqlAUTH)->result();
foreach($resAUTH as $rowAUTH) :
	$ISREAD 	= $rowAUTH->ISREAD;
	$ISCREATE 	= $rowAUTH->ISCREATE;
	$ISAPPROVE 	= $rowAUTH->ISAPPROVE;
	$ISDELETE	= $rowAUTH->ISDELETE;
endforeach;
if($ISDELETE == 1)
{
	$ISREAD		= 1;
	$ISCREATE	= 1;
	$ISAPPROVE	= 1;
}

if($task == 'add')
{
	foreach($viewDocPattern as $row) :
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
	$LangID 	= $this->session->userdata['LangID'];
	if(isset($Pattern_Position))
	{
		$isSetDocNo = 1;
		if($Pattern_Position == 'Especially')
		{
			$Pattern_YearAktive 	= date('Y');
			$Pattern_MonthAktive 	= date('m');
			$Pattern_DateAktive 	= date('d');
		}
		$year 						= (int)$Pattern_YearAktive;
		$month 						= (int)$Pattern_MonthAktive;
		$date 						= (int)$Pattern_DateAktive;
	}
	else
	{
		$isSetDocNo = 0;
		$Pattern_Code 			= "XXX";
		$Pattern_Length 		= "5";
		$useYear 				= 1;
		$useMonth 				= 1;
		$useDate 				= 1;
		
		$Pattern_YearAktive 	= date('Y');
		$Pattern_MonthAktive 	= date('m');
		$Pattern_DateAktive 	= date('d');
		$year 					= (int)$Pattern_YearAktive;
		$month 					= (int)$Pattern_MonthAktive;
		$date 					= (int)$Pattern_DateAktive;
		
		if($LangID == 'IND')
		{
			$docalert1	= 'Peringatan';
			$docalert2	= 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
		}
		else
		{
			$docalert1	= 'Warning';
			$docalert2	= 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
		}
	}
	$yearC = (int)$Pattern_YearAktive;
	$year = substr($Pattern_YearAktive,2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;
	
	$sql = "SELECT MAX(Acc_ID) as maxNumber FROM tbl_chartaccount";
	$result = $this->db->query($sql)->result();
	if($result>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}
	
	/*$thisMonth = $month;
	
	$lenMonth = strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth = $nolMonth.$thisMonth;
	
	$thisDate = $date;
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
	$lastPatternNumb = $nol.$lastPatternNumb;
	
	
	$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project
			WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$PRJNAME = $row->PRJNAME;
	endforeach;
	
	$PACODE			= substr($lastPatternNumb, -4);
	$DocNumber 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$Acc_ID			= "$DocNumber";
	
	$CACODE			= substr($lastPatternNumb, -4);
	$CAYEAR			= date('y');
	$CAMONTH		= date('m');
	$CACODE			= "PP.$CACODE.$CAYEAR.$CAMONTH"; // MANUAL CODE*/
	
	$Acc_ID 				= $myMax;
	$ORD_ID					= $myMax;
	$PRJCODE 				= $PRJCODE;
	$Account_Class 			= '';
	$Account_Number 		= '';
	$Account_NameEn 		= '';
	$Account_NameId 		= '';
	$Account_Category		= 1;
	$Account_Level 			= 1;
	$Acc_ParentList 		= '';
	$Acc_DirParent 			= '';
	$Acc_StatusLinked 		= 1;
	$Default_Acc 			= 'D';
	$COGSReportID 			= '';
	$Currency_id 			= 'IDR';
	$Base_Debet 			= 0;
	$Base_Kredit 			= 0;
	$Base_Debet2 			= 0;
	$Base_Kredit2 			= 0;
	$Base_OpeningBalance	= 0;
	$isHO					= 1;
	$isSync					= 1;
	$syncPRJ				= "";
	$showCF					= 0;
	$Acc_Group				= "";
}
else
{
	$Acc_ID 				= $default['Acc_ID'];
	$ORD_ID 				= $default['ORD_ID'];
	$PRJCODE 				= $default['PRJCODE'];
	$Account_Class 			= $default['Account_Class'];
	$Account_Number 		= $default['Account_Number'];
	$Account_NameEn 		= $default['Account_NameEn'];
	$Account_NameId 		= $default['Account_NameId'];
	$Account_Category		= $default['Account_Category'];
	$Account_Level 			= $default['Account_Level'];
	$Acc_ParentList 		= $default['Acc_ParentList'];
	$Acc_DirParent 			= $default['Acc_DirParent'];
	$Acc_StatusLinked 		= $default['Acc_StatusLinked'];
	$Default_Acc 			= $default['Default_Acc'];
	$COGSReportID 			= $default['COGSReportID'];
	$Currency_id 			= $default['Currency_id'];
	$Base_Debet 			= $default['Base_Debet'];
	$Base_Kredit 			= $default['Base_Kredit'];
	$Base_Debet2 			= $default['Base_Debet2'];
	$Base_Kredit2 			= $default['Base_Kredit2'];
	$Base_OpeningBalance	= $default['Base_OpeningBalance'];
	$isHO					= $default['isHO'];
	$isSync					= $default['isSync'];
	$syncPRJ				= $default['syncPRJ'];
	$showCF					= $default['showCF'];
	$Acc_Group 				= $default['Base_Debet'];
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

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    
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
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Name')$Name = $LangTransl;
			if($TranslCode == 'Address')$Address = $LangTransl;
			if($TranslCode == 'Phone')$Phone = $LangTransl;
			if($TranslCode == 'ContactPerson')$ContactPerson = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'AccountCode')$AccountCode = $LangTransl;
			if($TranslCode == 'AccountNo')$AccountNo = $LangTransl;
			if($TranslCode == 'OrderNo')$OrderNo = $LangTransl;
			if($TranslCode == 'Account')$Account = $LangTransl;
			if($TranslCode == 'AccountClass')$AccountClass = $LangTransl;
			if($TranslCode == 'AccountName')$AccountName = $LangTransl;
			if($TranslCode == 'AccountCateg')$AccountCateg = $LangTransl;
			if($TranslCode == 'positBal')$positBal = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'OpeningBalance')$OpeningBalance = $LangTransl;
			if($TranslCode == 'Parent')$Parent = $LangTransl;
			if($TranslCode == 'CFReport')$CFReport = $LangTransl;
			if($TranslCode == 'Supply')$Supply = $LangTransl;
			if($TranslCode == 'Sales')$Sales = $LangTransl;
			if($TranslCode == 'WIP')$WIP = $LangTransl;
			if($TranslCode == 'Production')$Production = $LangTransl;
			if($TranslCode == 'accGroup')$accGroup = $LangTransl;
			if($TranslCode == 'catClassAcc')$catClassAcc = $LangTransl;
			if($TranslCode == 'groupBLR')$groupBLR = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$AcumtoHO	= "Diakumulasi";
			$alert0		= "Kode Akun tidak boleh kosong.";
			$alert01	= "Nama Akun tidak boleh kosong.";
			$alert02	= "Induk Akun tidak boleh kosong.";
			$alert1		= "Pilih daftar anggaran yang akan disinkronisasi.";
			$alert2		= "Silahkan masukan Nomor Akun dengan benar.";
			$alert3		= "Akun ini sebagai header. Nilai awal akan dinolkan.";
			$alert4		= "Anda tidak dapat mengedit akun ini. Akun ini terkunci karena sudah digunakan : ";
			$groupBLRx	= "Group Lap. Neraca";
		}
		else
		{
			$AcumtoHO	= "Accumulated";
			$alert0		= "Account Code can not be empty.";
			$alert01	= "Account Name can not be empty.";
			$alert02	= "Account Parent can not be empty.";
			$alert1		= "Please select for budget(s) syncronization.";
			$alert2		= "Please input Account Number correctly.";
			$alert3		= "This account is set as Header. Opening Balance must be zero.";
			$alert4		= "You cannot edit this account. This account is locked because it has been used : ";
			$groupBLRx	= "Bal. Sheet Group";
		}
		$isGood			= 1;

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/book.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $Add; ?>
			    <small><?php echo $Account; ?></small>
		  	</h1>
		</section>

		<section class="content">	
		    <div class="row">
                <?php
                	$isDisB = 0;
                	// CHEK PENGGUNAAN AKUN
                		$JDDesc	= "";
                		if($Account_Class == 1)
                        {
                        	$JDDesc	= "Header";
                        	$isDisB	= 1;
                        }
                        elseif($Account_Class == 2)
                        { $JDDesc = ""; $isDisB = 0; }
                        elseif($Account_Class == 3)
                        { $JDDesc = ""; $isDisB = 0; }
                        elseif($Account_Class == 4)
                        { $JDDesc = ""; $isDisB = 0; }
                        elseif($Account_Class == 5)
                        { $JDDesc = ""; $isDisB = 0; }

                		$sqlJDC1	= "tbl_journaldetail WHERE Acc_Id = '$Account_Number'";
                		$resJDC1	= intval($this->db->count_all($sqlJDC1));
                		if($resJDC1 > 0)
                			$JDDesc	= $JDDesc."Journal";

                    	$sqlJDC2	= "tbl_item WHERE ACC_ID = '$Account_Number' OR ACC_ID_UM = '$Account_Number'";
                		$resJDC2	= intval($this->db->count_all($sqlJDC2));
                		if($resJDC2 > 0)
                			$JDDesc	= $JDDesc." - Master Material";

                    	$sqlJDC3	= "tbl_vendcat WHERE VC_LA_PAYDP = '$Account_Number' OR VC_LA_PAYINV = '$Account_Number'
                    						OR VC_LA_RET = '$Account_Number'";
                		$resJDC3	= intval($this->db->count_all($sqlJDC3));
                		if($resJDC3 > 0)
                			$JDDesc	= $JDDesc." - Supl. Cat";

                    	$sqlJDC4	= "tbl_custcat WHERE CC_LA_CINV = '$Account_Number' OR CC_LA_RECDP = '$Account_Number'";
                		$resJDC4	= intval($this->db->count_all($sqlJDC4));
                		if($resJDC4 > 0)
                			$JDDesc	= $JDDesc." - Cust. Cat";

                		$AccisUsed	= $resJDC1 + $resJDC2 + $resJDC3 + $resJDC4;
                		if($AccisUsed > 0)
                		{
                			$isDisB = 1;
                		}

                		if($task == 'add')
                			$isDisB = 0;
                ?>
                <form class="form-horizontal" name="absen_form" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkInp()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
		                    	<input type="hidden" name="isHO" id="isHO" value="<?php echo $isHO; ?>">
		                        <div class="form-group">
		                          	<label class="col-sm-3 control-label"><?php echo $AccountCode; ?></label>
		                          	<div class="col-sm-4">
		                                <input type="text" class="form-control" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" style="display:none"/>
		                                <input type="text" class="form-control" name="Acc_ID" id="Acc_ID" value="<?php echo $Acc_ID; ?>" style="display:none"/>
		                                <input type="text" class="form-control" name="Acc_ID1" id="Acc_ID1" value="<?php echo $Acc_ID; ?>" disabled />
		                          	</div>
		                          	<div class="col-sm-5">
		                            	<input type="hidden" class="form-control" name="isGood" id="isGood" value="<?php echo $isGood; ?>"/>
		                                <input type="hidden" class="form-control" name="Account_Number" id="Account_Number" value="<?php echo $Account_Number; ?>" />
		                            	<?php if($isDisB == 0) { ?>
		                                	<input type="text" class="form-control" name="Account_NumberX" id="Account_NumberX" value="<?php echo $Account_Number; ?>" onBlur="chgACCID()" />
		                            	<?php } else { ?>
		                                	<input type="hidden" class="form-control" name="Account_NumberX" id="Account_NumberX" value="<?php echo $Account_Number; ?>" onBlur="chgACCID()" />
		                                	<input type="text" class="form-control" name="Account_NumberX1" id="Account_NumberX1" value="<?php echo $Account_Number; ?>" readonly />
		                                <?php } ?>
		                          	</div>
		                        </div>
		                        <div id="alertExist" class="form-group" style="display: none;">
		                          	<label class="col-sm-3 control-label">&nbsp;</label>
		                          	<div class="col-sm-9">
		                               	<label id="isHidden"></label>
		                                <input type="hidden" name="CheckThe_Code" id="CheckThe_Code" value="" size="20" maxlength="25" >
				                        <script>
											function chgACCID()
											{
												var myValue1	= document.getElementById('Account_Number').value;
												var myValue		= document.getElementById('Account_NumberX').value;
												
												var ajaxRequest;
												try
												{
													ajaxRequest = new XMLHttpRequest();
												}
												catch (e)
												{
													swal("Something is wrong");
													return false;
												}
												ajaxRequest.onreadystatechange = function()
												{
													if(ajaxRequest.readyState == 4)
													{
														recordcount = ajaxRequest.responseText;
														if(recordcount > 0)
														{
															document.getElementById('alertExist').style.display = '';
															document.getElementById('isGood').value 			= 0;
															//document.getElementById('Account_NumberX').value 	= myValue1;
															document.getElementById('CheckThe_Code').value		= recordcount;
															document.getElementById("isHidden").innerHTML 		= ' Already exist ... ! Suggestion: '+myValue1;
															document.getElementById("isHidden").style.color 	= "#ff0000";
														}
														else
														{
															document.getElementById('alertExist').style.display = 'none';
															document.getElementById('isGood').value 			= 1;
															document.getElementById('Account_NumberX').value 	= myValue;
															document.getElementById('Account_Number').value 	= myValue;
															document.getElementById('CheckThe_Code').value		= recordcount;
															document.getElementById("isHidden").innerHTML 		= ' Account No. : OK .. ! ';
															document.getElementById("isHidden").style.color 	= "green";
														}
													}
												}
												var ACC_NUM = myValue;
												
												ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_gl/c_ch1h0fbeart/getTheCode/';?>" + ACC_NUM, true);
												ajaxRequest.send(null);
											}
										</script>
		                          	</div>
		                        </div>
		                        <?php if($task == 'edit') { ?>
			                        <div class="form-group" style="display: none;">
			                            <label class="col-sm-3 control-label"><?php echo $OrderNo; ?></label>
			                            <div class="col-sm-9">
		                                    <input type="text" class="form-control" name="ORD_ID" id="ORD_ID" style="max-width:150px" value="<?php echo $ORD_ID; ?>" />
			                            </div>
			                        </div>
		                    	<?php } ?>
								<?php
		                            $sqlC0a		= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
		                            $resC0a 	= $this->db->count_all($sqlC0a);
		                            
		                            $sqlC0b		= "SELECT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, Acc_ParentList,
														Acc_DirParent, (Base_Debet + Base_Kredit) AS TRX0, isLast
		                                            FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' ORDER BY ORD_ID ASC";
		                            $resC0b 	= $this->db->query($sqlC0b)->result();
		                        ?>
		                        <script>
									function getACC_NUM(Acc_Num1)
									{
										var Acc_Num2 	= Acc_Num1.split("~");
										var Acc_Num 	= Acc_Num2[0];
										var PattNo		= parseInt(Acc_Num2[2]);
										var NextPatt	= parseInt(PattNo + 1);								
										if(NextPatt < 10)
										{
											var NextPattV	= '0'+NextPatt;
										}
										else
										{
											var NextPattV	= NextPatt;
										}
										var Acc_NumComp		= Acc_Num+"."+NextPattV;
										document.getElementById('Account_Number').value 	= Acc_NumComp;
										document.getElementById('Account_NumberX').value 	= Acc_NumComp;
										
										chgACCID();
									}
								</script>
		                        <div class="form-group">
		                            <label class="col-sm-3 control-label"><?php echo $Parent; ?></label>
		                            <div class="col-sm-9">
		                            	<?php if($isDisB == 0) { ?>
			                                <select name="Acc_DirParent" id="Acc_DirParent" class="form-control select2" onChange="getACC_NUM(this.value)">
			                        			<option value="" > --- </option>
			                                    <?php
												if($resC0a>0)
												{
													foreach($resC0b as $rowC0b) :
														$Acc_ID0		= $rowC0b->Acc_ID;
														$Account_Number0= $rowC0b->Account_Number;
														$Acc_DirParent0	= $rowC0b->Acc_DirParent;
														$Account_Level0	= $rowC0b->Account_Level;
														$TRX0			= $rowC0b->TRX0;
														$isLast			= $rowC0b->isLast;
														if($LangID == 'IND')
														{
															$Account_Name0	= $rowC0b->Account_NameId;
														}
														else
														{
															$Account_Name0	= $rowC0b->Account_NameEn;
														}											
														$Acc_ParentList0	= $rowC0b->Acc_ParentList;
														
														if($Account_Level0 == 0)
															$level_coa1			= "";
														elseif($Account_Level0 == 1)
															$level_coa1			= "&nbsp;&nbsp;&nbsp;";
														elseif($Account_Level0 == 2)
															$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($Account_Level0 == 3)
															$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($Account_Level0 == 4)
															$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($Account_Level0 == 5)
															$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($Account_Level0 == 6)
															$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($Account_Level0 == 7)
															$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														
														$resC1a		= 0;
														$sqlC1a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number0' AND PRJCODE = '$PRJCODE'";
														$resC1a 	= $this->db->count_all($sqlC1a);
														if($resC1a == '')
															$resC1a = 0;

														$disSel 	= 0;
														if($isLast == 1 || $TRX0 > 0)
															$disSel = 1;
														
														$collData0	= "$Account_Number0~$Acc_ParentList0~$resC1a";
														?>
			                                    			<option value="<?php echo $collData0; ?>" <?php if($Account_Number0 == $Acc_DirParent) { ?> selected <?php } if($isLast == 1) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Number0 - $Account_Name0"; ?></option>
			                                         	<?php
													endforeach;
												}
												?>
			                                </select>
		                            	<?php } else { ?>
			                                <select name="Acc_DirParentx" id="Acc_DirParentx" class="form-control select2" disabled>
			                        			<option value="" > --- </option>
			                                    <?php
												if($resC0a>0)
												{
													foreach($resC0b as $rowC0b) :
														$Acc_ID0		= $rowC0b->Acc_ID;
														$Account_Number0= $rowC0b->Account_Number;
														$Acc_DirParent0	= $rowC0b->Acc_DirParent;
														$Account_Level0	= $rowC0b->Account_Level;
														$TRX0			= $rowC0b->TRX0;
														if($LangID == 'IND')
														{
															$Account_Name0	= $rowC0b->Account_NameId;
														}
														else
														{
															$Account_Name0	= $rowC0b->Account_NameEn;
														}											
														$Acc_ParentList0	= $rowC0b->Acc_ParentList;
														
														if($Account_Level0 == 0)
															$level_coa1			= "";
														elseif($Account_Level0 == 1)
															$level_coa1			= "&nbsp;&nbsp;&nbsp;";
														elseif($Account_Level0 == 2)
															$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($Account_Level0 == 3)
															$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($Account_Level0 == 4)
															$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($Account_Level0 == 5)
															$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($Account_Level0 == 6)
															$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														elseif($Account_Level0 == 7)
															$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
														
														$resC1a		= 0;
														$sqlC1a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number0' AND PRJCODE = '$PRJCODE'";
														$resC1a 	= $this->db->count_all($sqlC1a);
														if($resC1a == '')
															$resC1a = 0;
														
														$collData0	= "$Account_Number0~$Acc_ParentList0~$resC1a";
														if($Account_Number0 == $Acc_DirParent)
														{
															$collDataX	= "$Account_Number0~$Acc_ParentList0~$resC1a";
														}
														?>
			                                    			<option value="<?php echo $collData0; ?>" <?php if($Account_Number0 == $Acc_DirParent) { ?> selected <?php } if($TRX0 > 0) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Number0 - $Account_Name0"; ?></option>
			                                         	<?php
													endforeach;
												}
												?>
			                                </select>
		                            		<input type="hidden" class="form-control" name="Acc_DirParent" id="Acc_DirParent" value="<?php echo $collData0; ?>" />
			                            <?php } ?>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="col-sm-3 control-label"><?php echo $AccountName; ?> IND</label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="Account_NameId" id="Account_NameId" value="<?php echo $Account_NameId; ?>" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="col-sm-3 control-label"><?php echo $AccountName; ?> ENG</label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="Account_NameEn" id="Account_NameEn" value="<?php echo $Account_NameEn; ?>" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="col-sm-3 control-label"><?php echo $catClassAcc; ?></label>
		                            <div class="col-sm-5">
		                                <select name="Account_Category" id="Account_Category" class="form-control select2">
		                                    <option value="1" <?php if($Account_Category == '1') { ?> selected <?php } ?>>Aktiva</option>
		                                    <option value="2" <?php if($Account_Category == '2') { ?> selected <?php } ?>>Passiva</option>
		                                    <option value="3" <?php if($Account_Category == '3') { ?> selected <?php } ?>>Capital (Modal)</option>
		                                    <option value="4" <?php if($Account_Category == '4') { ?> selected <?php } ?>>Income Statement</option>
		                                    <option value="5" <?php if($Account_Category == '5') { ?> selected <?php } ?>>COGS</option>
		                                    <option value="6" <?php if($Account_Category == '6') { ?> selected <?php } ?>>HPP</option>
		                                    <option value="8" <?php if($Account_Category == '7') { ?> selected <?php } ?>>Expenses (Beban)</option>
		                                    <option value="9" <?php if($Account_Category == '8') { ?> selected <?php } ?>>Others</option>
		                                </select>
		                            </div>
		                            <div class="col-sm-4">
		                                <select name="Account_Class" id="Account_Class" class="form-control select2" onchange="chgClass(this.value)">
		                                    <option value="1" <?php if($Account_Class == '1') { ?> selected <?php } ?>>Header</option>
		                                    <option value="2" <?php if($Account_Class == '2') { ?> selected <?php } ?>>Detail</option>
		                                    <option value="3" <?php if($Account_Class == '3') { ?> selected <?php } ?>>Detail Cash</option>
		                                    <option value="4" <?php if($Account_Class == '4') { ?> selected <?php } ?>>Detail Bank</option>
		                                    <option value="5" <?php if($Account_Class == '5') { ?> selected <?php } ?>>Detail Other In</option>
		                                </select>
		                            </div>
		                        </div>
		                        <script type="text/javascript">
		                        	function chgClass(theVal)
		                        	{
		                        		if(theVal == 1)
		                        		{
		                        			var decFormat	= document.getElementById('decFormat').value;
											BALANCE_VAL 	= 0;
											document.getElementById('Base_OpeningBalance').value 	= BALANCE_VAL;
			                                document.getElementById('Base_OpeningBalance1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(BALANCE_VAL)),decFormat));
		                        		}
		                        	}
		                        </script>
		                        <div class="form-group">
		                            <label class="col-sm-3 control-label"><?php echo $positBal; ?></label>
		                            <div class="col-sm-5">
		                                <select name="Default_Acc" id="Default_Acc" class="form-control select2" >
		                                    <option value="D" <?php if($Default_Acc == 'D') { ?> selected <?php } ?>>Debit</option>
		                                    <option value="K" <?php if($Default_Acc == 'K') { ?> selected <?php } ?>>Kredit</option>
		                                </select>
		                            </div>
		                            <div class="col-sm-4">
		                                <input type="hidden" class="form-control" name="Base_OpeningBalance" id="Base_OpeningBalance" style="max-width:100px" value="<?php echo $Base_OpeningBalance; ?>" />
		                                <input type="text" style="text-align: right;" class="form-control" name="Base_OpeningBalance1" id="Base_OpeningBalance1" value="<?php echo number_format($Base_OpeningBalance, 2); ?>" onBlur="chgBalance()" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="col-sm-3 control-label"><?php echo $AcumtoHO; ?></label>
		                            <div class="col-sm-9">
		                                <select name="isSync" id="isSync" class="form-control select2" onChange="getsavePRJ(this.value)">
		                                    <option value="1" <?php if($isSync == 1) { ?> selected <?php } ?>>Yes</option>
		                                    <option value="0" <?php if($isSync == 0) { ?> selected <?php } ?>>No</option>
		                                </select>
		                            </div>
		                        </div>
		                        <script>
									function MoveOption(objSourceElement, objTargetElement) 
									{ 
										var aryTempSourceOptions = new Array(); 
										var aryTempTargetOptions = new Array(); 
										var x = 0; 
									
										//looping through source element to find selected options 
										for (var i = 0; i < objSourceElement.length; i++)
										{ 
											if (objSourceElement.options[i].selected)
											{ 
												 //need to move this option to target element 
												 var intTargetLen = objTargetElement.length++; 
												 objTargetElement.options[intTargetLen].text = objSourceElement.options[i].text; 
												 objTargetElement.options[intTargetLen].value = objSourceElement.options[i].value; 
											} 
											else
											{ 
												 //storing options that stay to recreate select element 
												 var objTempValues = new Object(); 
												 objTempValues.text = objSourceElement.options[i].text; 
												 objTempValues.value = objSourceElement.options[i].value; 
												 aryTempSourceOptions[x] = objTempValues; 
												 x++; 
											} 
										}
										
										//sorting and refilling target list 
										for (var i = 0; i < objTargetElement.length; i++)
										{ 
											var objTempValues = new Object(); 
											objTempValues.text = objTargetElement.options[i].text; 
											objTempValues.value = objTargetElement.options[i].value; 
											aryTempTargetOptions[i] = objTempValues; 
										} 
								
										aryTempTargetOptions.sort(sortByText); 
								
										for (var i = 0; i < objTargetElement.length; i++)
										{ 
											objTargetElement.options[i].text = aryTempTargetOptions[i].text; 
											objTargetElement.options[i].value = aryTempTargetOptions[i].value; 
											objTargetElement.options[i].selected = false; 
										}
										
										//resetting length of source 
										objSourceElement.length = aryTempSourceOptions.length; 
										//looping through temp array to recreate source select element 
										for (var i = 0; i < aryTempSourceOptions.length; i++) 
										{ 
											objSourceElement.options[i].text = aryTempSourceOptions[i].text; 
											objSourceElement.options[i].value = aryTempSourceOptions[i].value; 
											objSourceElement.options[i].selected = false; 
										}
									}

									function sortByText(a, b) 
									{ 
										if (a.text < b.text) {return -1} 
										if (a.text > b.text) {return 1} 
										return 0; 
									} 
									
									function getsavePRJ(isSync)
									{
										if(isSync == 0)
										{
											document.getElementById('getPRJ').style.display = 'none';
										}
										else
										{
											document.getElementById('getPRJ').style.display = '';
										}
									}
								</script>
		                        <?php
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
									$rowx		= 0;
									$PRJCODEA	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$rowx	= $rowx + 1;
										if($rowx == 1)
										{
											$PRJCODEA	= $dataPecah[$i];
										}
										else
										{
											$PRJCODEB	= $dataPecah[$i];
											$PRJCODEA	= "$PRJCODEA','$PRJCODEB";
										}
									}
									
									$getproject 	= "SELECT PRJCODE, PRJNAME FROM tbl_project
														WHERE PRJSTAT = 1 
															AND PRJCODE NOT IN ('$PRJCODEA') AND PRJTYPE != 1
														ORDER BY PRJCODE";
									$qProject 		= $this->db->query($getproject)->result();
									
									
									$getproject2 	= "SELECT PRJCODE, PRJNAME FROM tbl_project
														WHERE PRJSTAT = 1 
															AND PRJCODE IN ('$PRJCODEA') AND PRJTYPE != 1
														ORDER BY PRJCODE";
									$qProject2 		= $this->db->query($getproject2)->result();
								?>
		                        <div class="form-group" id="getPRJ">
		                            <label class="col-sm-3 control-label">&nbsp;</label>
		                            <div class="col-sm-9">
	                                    <select multiple class="form-control" name="pavailable" onclick="MoveOption(this.form.pavailable, this.form.packageelements)">
	                                        <?php
												/* Hidden => default packageelements select all project
	                                            foreach($qProject as $rowPRJ) :
	                                                $PRJCODE 	= $rowPRJ->PRJCODE;
	                                                $PRJNAME	= $rowPRJ->PRJNAME;
	                                                ?>
	                                                    <option value="<?php echo $PRJCODE; ?>"><?php echo "$PRJCODE - $PRJNAME";?></option>
	                                                <?php
	                                            endforeach;
												--------------------------------------------------------- */
	                                        ?>
	                                    </select>
		                            </div>
		                        </div>
		                        <div class="form-group" id="getPRJ">
		                            <label class="col-sm-3 control-label">&nbsp;</label>
		                            <div class="col-sm-9">
	                                	<select multiple class="form-control" name="packageelements[]" id="packageelements" ondblclick="MoveOption(this.form.packageelements, this.form.pavailable)">
	                                    	<?php
	                                            foreach($qProject as $rowPRJ) :
	                                                $PRJCODE 	= $rowPRJ->PRJCODE;
	                                                $PRJNAME	= $rowPRJ->PRJNAME;
	                                                ?>
	                                                    <option value="<?php echo $PRJCODE; ?>" selected><?php echo "$PRJCODE - $PRJNAME";?></option>
	                                                <?php
	                                            endforeach;
	                                        ?>
	                                    </select>
		                            </div>
		                        </div>
		                	</div>
		            	</div>
		        	</div>
					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border">
								<i class="fa fa-pie-chart"></i>
								<h3 class="box-title"><?php echo $groupBLR; ?></h3>
							</div>
							<div class="box-body">
		                        <div class="form-group">
		                            <label class="col-sm-3 control-label"><?php echo $groupBLRx; ?></label>
		                            <div class="col-sm-9">
		                                <select name="COGSReportID" id="COGSReportID" class="form-control select2">
		                                    <option value="" > --- </option>
		                                    <option value="" disabled>AKTIVA LANCAR</option>
		                                    <option value="CASH-HO" <?php if($COGSReportID == 'CASH-HO') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Kas Kantor</option>
		                                    <option value="CASH-PRJ" <?php if($COGSReportID == 'CASH-PRJ') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Kas Proyek</option>
		                                    <option value="BANK-HO" <?php if($COGSReportID == 'BANK-HO') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Bank Kantor</option>
		                                    <option value="BANK-PRJ" <?php if($COGSReportID == 'BANK-PRJ') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Bank Proyek</option>
		                                    <option value="AR" <?php if($COGSReportID == 'AR') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Piutang Usaha</option>
		                                    <option value="AR-OTH" <?php if($COGSReportID == 'AR-OTH') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Piutang Lain-Lain</option>
		                                    <option value="AR-DP" <?php if($COGSReportID == 'AR-DP') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Uang Muka Pembelian</option>
		                                    <option value="STOCK" <?php if($COGSReportID == 'STOCK') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Persediaan</option>
		                                    <option value="EXP-DP" <?php if($COGSReportID == 'EXP-DP') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Biaya Dibayar Dimuka</option>
		                                    <option value="TAX-DP" <?php if($COGSReportID == 'TAX-DP') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Pajak Dibayar Dimuka</option>
		                                    <option value="" disabled>AKTIVA TETAP</option>
		                                    <option value="AST-LAND" <?php if($COGSReportID == 'AST-LAND') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Tanah</option>
		                                    <option value="AST-BUILD" <?php if($COGSReportID == 'AST-BUILD') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Bangunan</option>
		                                    <option value="AST-MACH" <?php if($COGSReportID == 'AST-MACH') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Mesin</option>
		                                    <option value="AST-VEHICL" <?php if($COGSReportID == 'AST-VEHICL') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Kendaraan</option>
		                                    <option value="AST-TOOLPRY" <?php if($COGSReportID == 'AST-TOOLPRY') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Peralatan Proyek</option>
		                                    <option value="AST-TOOL" <?php if($COGSReportID == 'AST-TOOL') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Peralatan Kantor</option>
		                                    <option value="AST-EQPRY" <?php if($COGSReportID == 'AST-EQPRY') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Perlengkapan Proyek</option>
		                                    <option value="AST-EQOFC" <?php if($COGSReportID == 'AST-EQOFC') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Perlengkapan Kantor</option>
		                                    <option value="INVENT" <?php if($COGSReportID == 'INVENT') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Inventaris</option>
		                                    <option value="AST-DEPR" <?php if($COGSReportID == 'AST-DEPR') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Akum. Penyusutan</option>
		                                    <option value="" disabled>HUTANG LANCAR</option>
		                                    <option value="AP" <?php if($COGSReportID == 'AP') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Hutang Usaha</option>
		                                    <option value="AP-BANK" <?php if($COGSReportID == 'AP-BANK') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Hutang Bank</option>
		                                    <option value="AP-OTH" <?php if($COGSReportID == 'AP-OTH') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Hutang Lain-lain</option>
		                                    <option value="" disabled>MODAL / EKUITAS</option>
		                                    <option value="EKUIT" <?php if($COGSReportID == 'EKUIT') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Modal Disetor</option>
		                                    <option value="EKUIT-LRD" <?php if($COGSReportID == 'EKUIT-LRD') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Laba Rugi Ditahan</option>
		                                    <option value="EKUIT-LRC" <?php if($COGSReportID == 'EKUIT-LRC') { ?> selected <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;Laba Rugi Bulan Berjalan</option>
		                                </select>
		                            </div>
		                        </div>
		                        <script>
									function chgBalance()
									{
										var decFormat	= document.getElementById('decFormat').value;
										var accClass	= document.getElementById('Account_Class').value;
										if(accClass == 1)
										{
											swal('<?php echo $alert3; ?>',
											{
												icon: "warning",
											});
											BALANCE_VAL 	= 0;
											document.getElementById('Base_OpeningBalance').value 	= BALANCE_VAL;
			                                document.getElementById('Base_OpeningBalance1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(BALANCE_VAL)),decFormat));
										}
										else
										{
											BALANCE_VAL 	= eval(document.getElementById('Base_OpeningBalance1')).value.split(",").join("");
											document.getElementById('Base_OpeningBalance').value 	= BALANCE_VAL;
			                                document.getElementById('Base_OpeningBalance1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(BALANCE_VAL)),decFormat));
			                            }
									}
								</script>
		                        <div class="form-group">
		                            <label class="col-sm-3 control-label" title="Kategori laporan di dashboard"><?php echo $accGroup; ?></label>
		                            <div class="col-sm-9">
		                                <select name="Acc_Group" id="Acc_Group" class="form-control select2">
		                                    <option value="" > --- </option>
		                                    <option value="STOCK" <?php if($Acc_Group == 'STOCK') { ?> selected <?php } ?>><?php echo $Supply; ?></option>
		                                    <option value="SALES" <?php if($Acc_Group == 'SALES') { ?> selected <?php } ?>><?php echo $Sales; ?></option>
		                                    <option value="WIP" <?php if($Acc_Group == 'WIP') { ?> selected <?php } ?>><?php echo $WIP; ?></option>
		                                    <option value="PROD" <?php if($Acc_Group == 'PROD') { ?> selected <?php } ?>><?php echo $Production; ?></option>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="col-sm-3 control-label"><?php echo $CFReport; ?></label>
		                            <div class="col-sm-9">
		                                <select name="showCF" id="showCF" class="form-control select2">
		                                    <option value="1" <?php if($showCF == 1) { ?> selected <?php } ?>>Yes</option>
		                                    <option value="0" <?php if($showCF == 0) { ?> selected <?php } ?>>No</option>
		                                </select>
		                            </div>
		                        </div>
		                        <br>
		                        <div class="form-group">
		                        	<label class="col-sm-3 control-label">&nbsp;</label>
		                            <div class="col-sm-9">
		                            <?php
										if($ISCREATE == 1 )
										{
											?>
												<button class="btn btn-primary">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
										echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
									?>
		                            </div>
		                        </div>
							</div>
						</div>
					</div>
		        </form>
		    </div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
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
  
	var decFormat		= 2;
	
	function checkInp()
	{
		var Acc_DirParent	= document.getElementById('Acc_DirParent').value;
		if(Acc_DirParent == '')
		{
			swal('<?php echo $alert02; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#Acc_DirParent').focus();
            });
            return false;
		}

		var Acc_Number	= document.getElementById('Account_NumberX').value;
		if(Acc_Number == '')
		{
			swal('<?php echo $alert0; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#Account_NumberX').focus();
            });
            return false;
		}

		var Acc_Name	= document.getElementById('Account_NameId').value;
		if(Acc_Name == '')
		{
			swal('<?php echo $alert01; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#Account_NameId').focus();
            });
            return false;
		}

		var synProj	= document.getElementById('packageelements').value;
		if(synProj == '')
		{
			swal('<?php echo $alert1; ?>',
			{
				icon: "warning",
			});
			document.getElementById('packageelements').focus();
			return false;
		}
		
		var isGood	= document.getElementById('isGood').value;
		if(isGood == 0)
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			});
			document.getElementById('Account_NumberX').focus();
			return false;
		}
	}
	
	function doDecimalFormat(angka) 
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