<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 Maret 2020
 * File Name	= v_a553sm_view.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$sqlEMP	= "SELECT EMP_ID FROM tbl_assesment WHERE ASSM_CODE = '$ASSM_CODE'";
$resEMP	= $this->db->query($sqlEMP)->result();
foreach($resEMP as $rowEMP) :
	$EMP_ID = $rowEMP->EMP_ID;
endforeach;

$sqlGetDet			= "SELECT Emp_ID, EmpNoIdentity, CONCAT(First_Name, ' ', Last_Name) AS empName, gender, Birth_Place, Date_Of_Birth,
							Mobile_Phone, Email
						FROM tbl_employee WHERE Emp_ID = '$EMP_ID'";
$resGetDet 			= $this->db->query($sqlGetDet)->result();
foreach($resGetDet as $rowGDet) :
	$Emp_ID 		= $rowGDet ->Emp_ID;
	$EmpNoIdentity 	= $rowGDet ->EmpNoIdentity;
	$empName 		= $rowGDet ->empName;
	$gender 		= $rowGDet ->gender;
	$Birth_Place 	= $rowGDet ->Birth_Place;
	$Date_Of_Birth	= $rowGDet ->Date_Of_Birth;
	$Mobile_Phone	= $rowGDet ->Mobile_Phone;
	$Email			= $rowGDet ->Email;
endforeach;

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

$username 			= $this->session->userdata('username');
$imgemp_filename 	= '';
$imgemp_filenameX 	= '';

	$isSetDocNo = 1;
	// GET DETAIL USER
	$sqlGetDet			= "SELECT Emp_ID, EmpNoIdentity, Gol_Code, Pos_Code, First_Name, Last_Name, gender, Birth_Place, Date_Of_Birth,
							Age, Mobile_Phone, Email, Religion, Emp_Status, Tax_Status,
							Nationality, Marital_Status, Address1, State1, country1,
							city1, zipcode1, log_username, log_password, log_passHint, FlagUSER, Emp_Location, Emp_Notes, pos_code
							FROM tbl_employee WHERE Emp_ID = '$Emp_ID'";
	$resGetDet 			= $this->db->query($sqlGetDet)->result();
	foreach($resGetDet as $rowGDet) :
		$Emp_ID 		= $rowGDet ->Emp_ID;
		$EmpNoIdentity 	= $rowGDet ->EmpNoIdentity;
		$Gol_Code 		= $rowGDet ->Gol_Code;
		$Pos_Code 		= $rowGDet ->Pos_Code;
		$First_Name 	= $rowGDet ->First_Name;
		$Last_Name 		= $rowGDet ->Last_Name;
		$gender 		= $rowGDet ->gender;
		$Birth_Place 	= $rowGDet ->Birth_Place;
		$Date_Of_Birth1	= $rowGDet ->Date_Of_Birth;
		$Date_Of_Birth	= date('m/d/Y',strtotime($Date_Of_Birth1));
		$Age			= $rowGDet ->Age;
		$Mobile_Phone 	= $rowGDet ->Mobile_Phone;
		$Emp_Email 		= $rowGDet ->Email;
		$Religion 		= $rowGDet ->Religion;
		$Nationality 	= $rowGDet ->Nationality;
		$Marital_Status = $rowGDet ->Marital_Status;
		$Address1 		= $rowGDet ->Address1;
		$State1 		= $rowGDet ->State1;
		$country1 		= $rowGDet ->country1;
		$city1 			= $rowGDet ->city1;
		$zipcode1 		= $rowGDet ->zipcode1;
		$log_username 	= $rowGDet ->log_username;
		$log_password 	= $rowGDet ->log_password;
		$log_passHint	= $rowGDet ->log_passHint;
		$FlagUSER		= $rowGDet ->FlagUSER;
		$Emp_Location 	= $rowGDet ->Emp_Location;
		$Emp_Notes 		= $rowGDet ->Emp_Notes;
		$Emp_Status 	= $rowGDet ->Emp_Status;
		$Tax_Status		= $rowGDet ->Tax_Status;
		if($Emp_Location == 1)
		{
			$Emp_LocationD	= "Headquarters";
		}
		else
		{
			$Emp_LocationD	= "Project";
		}
	endforeach;
	$compName			= "$First_Name$Last_Name";
	
	// CURRENT POSITION LEVEL
	$POSF_LEVC		= '';
	$sqlCURLEV		= "SELECT B.POSF_LEVC FROM tbl_employee A
						INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
						WHERE A.Emp_ID = '$Emp_ID'";
	$resCURLEV		= $this->db->query($sqlCURLEV)->result();
	foreach($resCURLEV as $rowCL) :
		$POSF_LEVC 	= $rowCL->POSF_LEVC;
	endforeach;
	
	$UNIQE			= '';
	$sqlGetPs		= "SELECT P AS UNIQE FROM others WHERE NK = '$Emp_ID'";
	$resGetPs 		= $this->db->query($sqlGetPs)->result();
	foreach($resGetPs as $rowGPs) :
		$UNIQE 	= $rowGPs->UNIQE;
	endforeach;
	
	$sqlGetIMG		= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$Emp_ID'";
	$resGetIMG 		= $this->db->query($sqlGetIMG)->result();
	foreach($resGetIMG as $rowGIMG) :
		$imgemp_filename 	= $rowGIMG ->imgemp_filename;
		$imgemp_filenameX 	= $rowGIMG ->imgemp_filenameX;
	endforeach;

	// Mencari jumlah Mengikuti/Following
	$TOTFOLLOWINGS		= 0;
	$sqlGetCirA			= "SELECT COUNT(Followings) AS TOTFOLLOWINGS FROM tbl_employee_circle WHERE Emp_ID = '$Emp_ID'";
	$resGetCirA			= $this->db->query($sqlGetCirA)->result();
	foreach($resGetCirA as $rowGCirA) :
		$TOTFOLLOWINGS 	= $rowGCirA ->TOTFOLLOWINGS;
	endforeach;
	
	// Mencari jumlah Pengikut/Followers
	$TOTFOLLOWERS		= 0;
	$sqlGetCirB			= "SELECT COUNT(Emp_ID) AS TOTFOLLOWERS FROM tbl_employee_circle WHERE Followings = '$Emp_ID'";
	$resGetCirB			= $this->db->query($sqlGetCirB)->result();
	foreach($resGetCirB as $rowGCirB) :
		$TOTFOLLOWERS 	= $rowGCirB ->TOTFOLLOWERS;
	endforeach;
	
	$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID.'/'.$imgemp_filenameX);
	if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID))
	{
		$imgLoc			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
	}
	$urlAddProf			= site_url('c_hr/c_employee/c_employee/update_process/?id='.$this->url_encryption_helper->encode_url($appName));
	
// Mencari jumlah total karyawan
$sqlGetCirC			= "tbl_employee WHERE Employee_status = '1'";
$TOTFRIENDS			= $this->db->count_all($sqlGetCirC);

$urlUpdProfPic		= site_url('c_hr/c_employee/c_employee/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));

// CURRENT POSITION LEVEL
$POSF_LEVC		= '';
$sqlCURLEV		= "SELECT B.POSF_LEVC FROM tbl_employee A
					INNER JOIN tbl_position_func B ON A.Pos_Code = B.POSF_CODE
					WHERE A.Emp_ID = '$Emp_ID'";
$resCURLEV		= $this->db->query($sqlCURLEV)->result();
foreach($resCURLEV as $rowCL) :
	$POSF_LEVC 	= $rowCL->POSF_LEVC;
endforeach;

// POSITION LIST
$sqlPOSC		= "SELECT POSS_CODE, POSS_NAME, POSS_PARENT, POSS_LEVIDX FROM tbl_position_str WHERE POSS_STAT = 1";
$resPOSC		= $this->db->query($sqlPOSC)->result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
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
		
		if($TranslCode == 'ProfilePicture')$ProfilePicture = $LangTransl;
		if($TranslCode == 'Biodata')$Biodata = $LangTransl;
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'EmployeeID')$EmployeeID = $LangTransl;
		if($TranslCode == 'IdentificationID')$IdentificationID = $LangTransl;
		if($TranslCode == 'FirstName')$FirstName = $LangTransl;
		if($TranslCode == 'LastName')$LastName = $LangTransl;
		if($TranslCode == 'PlaceofBirth')$PlaceofBirth = $LangTransl;
		if($TranslCode == 'DateofBirth')$DateofBirth = $LangTransl;
		if($TranslCode == 'Gender')$Gender = $LangTransl;
		if($TranslCode == 'Male')$Male = $LangTransl;
		if($TranslCode == 'Female')$Female = $LangTransl;
		if($TranslCode == 'MaritalStatus')$MaritalStatus = $LangTransl;
		if($TranslCode == 'Religion')$ReligionSubt = $LangTransl;
		if($TranslCode == 'Address')$Address = $LangTransl;
		if($TranslCode == 'City')$City = $LangTransl;
		if($TranslCode == 'Province')$Province = $LangTransl;
		if($TranslCode == 'State')$State = $LangTransl;
		if($TranslCode == 'Email')$Email = $LangTransl;
		if($TranslCode == 'Phone')$Phone = $LangTransl;
		if($TranslCode == 'Location')$Location = $LangTransl;
		if($TranslCode == 'Department')$Department = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'ProfileInfo')$ProfileInfo = $LangTransl;
		if($TranslCode == 'Authorization')$Authorization = $LangTransl;
		if($TranslCode == 'StatusnControl')$StatusnControl = $LangTransl;
		if($TranslCode == 'UploadProfPict')$UploadProfPict = $LangTransl;
		
		if($TranslCode == 'AboutMe')$AboutMe = $LangTransl;
		if($TranslCode == 'Followers')$Followers = $LangTransl;
		if($TranslCode == 'Following')$Following = $LangTransl;
		if($TranslCode == 'Friends')$Friends = $LangTransl;
		if($TranslCode == 'Education')$Education = $LangTransl;
		if($TranslCode == 'Location')$Location = $LangTransl;
		if($TranslCode == 'Skills')$Skills = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'None')$None = $LangTransl;
		
		if($TranslCode == 'Username')$Username = $LangTransl;
		if($TranslCode == 'Password')$Password = $LangTransl;
		if($TranslCode == 'ConfirmPass')$ConfirmPass = $LangTransl;
		if($TranslCode == 'Hint')$Hint = $LangTransl;
		if($TranslCode == 'UserStatus')$UserStatus = $LangTransl;
		if($TranslCode == 'UserFlag')$UserFlag = $LangTransl;
		if($TranslCode == 'FileName')$FileName = $LangTransl;
		if($TranslCode == 'ChooseFile')$ChooseFile = $LangTransl;
		if($TranslCode == 'Addemployee')$Addemployee = $LangTransl;
		if($TranslCode == 'Employee')$Employee = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Gol')$Gol = $LangTransl;
		
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Tax')$Tax = $LangTransl;
	endforeach;
	
		if($LangID == 'IND')
		{
			$EmployeeStatus = "$Status $Employee";
			$TaxStstus		= "$Status $Tax";
		}
		else
		{
			$EmployeeStatus = "$Employee $Status";
			$TaxStstus		= "$Tax $Status";
		}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->

<section class="content-header">
<h1>
    <?php echo $Addemployee; ?>
    <small><?php echo $Employee; ?></small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          	<!-- Profile Image -->
          	<div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" alt="User profile picture">
                    <h3 class="profile-username text-center"><?php echo "$First_Name $Last_Name"; ?></h3>                    
                    <p class="text-muted text-center"><?php echo $Emp_LocationD; ?></p>
                    <ul class="list-group list-group-unbordered">
                    	<li class="list-group-item">
                    		<b><?php echo $Followers; ?></b> <a class="pull-right"><?php print number_format($TOTFOLLOWERS, 0); ?></a>
                    	</li>
                    	<li class="list-group-item">
                    		<b><?php echo $Following; ?></b> <a class="pull-right"><?php print number_format($TOTFOLLOWINGS, 0); ?></a>
                    	</li>
                    	<li class="list-group-item">
                    		<b><?php echo $Friends; ?></b> <a class="pull-right"><?php print number_format($TOTFRIENDS, 0); ?></a>
                    	</li>
                    </ul>
                    <a href="#" class="btn btn-primary btn-block" style="display:none"><b>Follow</b></a>
                </div>
          	</div>

          <!-- About Me Box -->
          	<div class="box box-primary">
                <div class="box-header with-border">
                	<h3 class="box-title"><?php echo $AboutMe; ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                	<strong><i class="fa fa-book margin-r-5"></i> <?php echo $Education; ?></strong>
                	<p class="text-muted">
                		<em><?php echo $None; ?></em>
                	</p>
               		<hr>
                    <strong><i class="fa fa-map-marker margin-r-5"></i> <?php echo $Location; ?></strong>
                    <p class="text-muted"><?php echo "$Address1, $State1 $zipcode1 $Nationality"; ?></p>
					<hr>
                    <strong><i class="fa fa-pencil margin-r-5"></i> <?php echo $Skills; ?></strong>
                    <p>
                        <span class="label label-danger">UI Design</span>
                        <span class="label label-success">Coding</span>
                        <span class="label label-info">Javascript</span>
                        <span class="label label-warning">PHP</span>
                        <span class="label label-primary">Node.js</span>
                	</p>
                	<hr>
                	<strong><i class="fa fa-file-text-o margin-r-5"></i> <?php echo $Notes; ?></strong>
                    <p><em><?php echo $None; ?></em></p>
                </div>
            </div>
        </div>
        <div class="col-md-9">
			<div class="nav-tabs-custom">
            	<ul class="nav nav-tabs">
                	<li class="active"><a href="#settings" data-toggle="tab"><?php echo $Biodata; ?></a></li> 		<!-- Tab 1 -->
                    <li><a href="#profPicture" data-toggle="tab"><?php echo $ProfilePicture; ?></a></li>			<!-- Tab 2 -->
                </ul>
                <!-- Biodata -->
                <div class="tab-content">
                    <div class="active tab-pane" id="settings">
                        <form class="form-horizontal" name="frm" method="post" action="<?php echo $urlAddProf; ?>" onSubmit="return validateInEmpData()">
                            <input type="hidden" class="form-control" name="POSIT_BEF" id="POSIT_BEF" placeholder="EMP ID"  value="<?php echo "$POSF_LEVC"; ?>" >
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $ProfileInfo; ?></h3>
                                </div>
                                <br>
								<?php if($isSetDocNo == 0) { ?>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                    <div class="col-sm-10">
                                        <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
                                            <?php echo $docalert2; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $EmployeeID ?> </label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" name="Emp_ID" id="Emp_ID" placeholder="EMP ID"  value="<?php echo "$Emp_ID"; ?>" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php //echo $IdentificationID ?> No. KTP</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" name="EmpNoIdentity" id="EmpNoIdentity" placeholder="Identification ID"  value="<?php echo "$EmpNoIdentity"; ?>" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $FirstName ?> </label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" name="First_Name" id="First_Name" placeholder="First Name" value="<?php echo "$First_Name"; ?>" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $LastName ?> </label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="Last_Name" id="Last_Name" placeholder="Last Name" value="<?php echo "$Last_Name"; ?>" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PlaceofBirth ?> </label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="Birth_Place" id="Birth_Place" placeholder="Birth Place" value="<?php echo "$Birth_Place"; ?>" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $DateofBirth ?> </label>
                                    <div class="col-sm-10">
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" name="Date_Of_Birth" class="form-control pull-left" id="datepicker" value="<?php echo $Date_Of_Birth; ?>" style="width:100px" onChange="getAge(this.value)" >
                                            <input type="hidden" name="Age" class="form-control pull-left" id="Age" value="<?php echo $Age; ?>" style="width:90px" >
                                        </div>
                                    </div>
                                </div>
                                <script>
									function getAge(myValue)
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
												document.getElementById('Age').value= recordcount;
											}
										}
										var BirthDate = document.getElementById('datepicker').value;
										var newchar = '~';
										newDate = BirthDate.split('/').join(newchar);
										ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_hr/c_employee/c_employee/getAge/';?>" + newDate, true);
										ajaxRequest.send(null);
									}
								</script>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Gender ?> </label>
                                    <div class="col-sm-10">
                                        <input type="radio" name="gender" id="gender1" value="male" class="minimal" <?php if($gender == 'male') { ?> checked <?php } ?>>
                                        &nbsp;&nbsp;<?php echo $Male ?> &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="gender" id="gender2" value="female" class="minimal" <?php if($gender == 'female') { ?> checked <?php } ?>>
                                        &nbsp;&nbsp;<?php echo $Female ?> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $EmployeeStatus ?> </label>
                                    <div class="col-sm-10">
                                        <select name="EmployeeStatus" id="EmployeeStatus" class="form-control select2" >
                                            <option value="0">-- Select Option --</option>
                                            <option value="1" <?php if($Emp_Status == 1){ ?> selected <?php }?>>Kontrak</option>
                                            <option value="2" <?php if($Emp_Status == 2){ ?> selected <?php }?>>Tetap</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $TaxStstus ?> </label>
                                    <div class="col-sm-10">
                                        <select name="TaxStstus" id="TaxStstus" class="form-control select2" >
                                            <option value="0">-- Select Option --</option>
                                            <option value="1" <?php if($Tax_Status == 1){ ?> selected <?php }?>>TK0</option>
                                            <option value="2" <?php if($Tax_Status == 2){ ?> selected <?php }?>>TK1</option>
                                            <option value="3" <?php if($Tax_Status == 3){ ?> selected <?php }?>>TK2</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $MaritalStatus ?> </label>
                                    <div class="col-sm-10">
                                        <select name="Marital_Status" id="Marital_Status" class="form-control select2" >
                                            <option value="Single"> --- </option>
                                            <option value="Single" <?php if($Marital_Status == 'Single') { ?> selected <?php } ?>>Single</option>
                                            <option value="Married" <?php if($Marital_Status == 'Married') { ?> selected <?php } ?>>Married</option>
                                            <option value="Widow" <?php if($Marital_Status == 'Widow') { ?> selected <?php } ?>>Widow</option>
                                            <option value="Widower" <?php if($Marital_Status == 'Widower') { ?> selected <?php } ?>>Widower</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ReligionSubt ?> </label>
                                    <div class="col-sm-10">
                                        <select name="Religion" id="Religion" class="form-control select2" >
                                            <option value="Islam"> --- </option>
                                            <option value="Islam" <?php if($Religion == 'Islam') { ?> selected <?php } ?>>Islam</option>
                                            <option value="Kristen" <?php if($Religion == 'Kristen') { ?> selected <?php } ?>>Kristen</option>
                                            <option value="Katolik" <?php if($Religion == 'Katolik') { ?> selected <?php } ?>>Katolik</option>
                                            <option value="Hindu" <?php if($Religion == 'Hindu') { ?> selected <?php } ?>>Hindu</option>
                                            <option value="Budha" <?php if($Religion == 'Budha') { ?> selected <?php } ?>>Budha</option>
                                            <option value="Other" <?php if($Religion == 'Other') { ?> selected <?php } ?>>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $Address ?> </label>
                                    <div class="col-sm-10">
                                    <textarea class="form-control" name="Address1" id="Address1" placeholder="Address"><?php echo $Address1; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $City ?> </label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="city1" id="city1" placeholder="City" value="<?php echo "$city1"; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $Province ?> </label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="country1" id="country1" placeholder="Province" value="<?php echo "$country1"; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $State ?> </label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="State1" id="State1" placeholder="State" value="<?php echo "$State1"; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label"><?php echo $Email ?> </label>
                                    <div class="col-sm-10">
                                    <input type="email" class="form-control" name="Email" id="Email" placeholder="Email" value="<?php echo "$Emp_Email"; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label"><?php echo $Phone ?> </label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="Mobile_Phone" id="Mobile_Phone" placeholder="Phone No." value="<?php echo "$Mobile_Phone"; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label"><?php echo $Location ?> </label>
                                    <div class="col-sm-10">
                                        <select name="Emp_Location" id="Emp_Location" class="form-control select2" >
                                        	<option value="1"> --- </option>
                                            <option value="1" <?php if($Emp_Location == 1) { ?> selected <?php } ?>>Headquarters</option>
                                            <option value="2" <?php if($Emp_Location == 2) { ?> selected <?php } ?>>Pabrik</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputSkills" class="col-sm-2 control-label"><?php echo $Gol; ?> </label>
                                    <div class="col-sm-10">
                                        <select name="Gol_Code" id="Gol_Code" class="form-control select2" >
                                            <option value="" > ---- None ----</option>
                                            <?php
                                            if($GolC>0)
                                            {
                                                $i = 0;
                                                foreach($GetGol as $row) :
                                                    $EMPG_CODE	= $row->EMPG_CODE;
                                                    $EMPG_NAME	= $row->EMPG_NAME;
                                                    $EMPG_RANK	= $row->EMPG_RANK;
                                                    $EMPG_CHILD	= $row->EMPG_CHILD;						
                                                ?>
                                                    <option value="<?php echo $EMPG_CHILD;?>" <?php if($EMPG_CHILD == $Gol_Code) { ?>selected<?php } ?>><?php echo "$EMPG_CHILD - $EMPG_RANK";?></option>
                                                <?php 
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label"><?php echo $Department ?> </label>
                                    <div class="col-sm-10">
                                        <select name="Pos_Code" id="Pos_Code" class="form-control select2" >
                                            <option value="" > ---- None ----</option>
                                            <?php
                                                foreach($resPOSC as $row) :
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
				                                		<option value="<?php echo $POSS_CODE1;?>" <?php if($POSS_CODE1 == $Pos_Code) { ?>selected<?php } if($ressqlC1>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$SPACELEV$POSS_NAME1";?></option>
				                            		<?php
												endforeach;	
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputExperience" class="col-sm-2 control-label"><?php echo $Notes ?> </label>
                                    <div class="col-sm-10">
                                    <textarea class="form-control" name="Emp_Notes" id="Emp_Notes" placeholder="Notes"><?php echo $Emp_Notes; ?></textarea>
                                    </div>
                                </div>
                            	<br>
                            </div>
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $Authorization; ?></h3>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Username ?> </label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="log_username" id="log_username"  placeholder="Username" value="<?php echo "$log_username"; ?>" onChange="functioncheck(this.value)">
                                    </label><label>&nbsp;&nbsp;</label><label id="isHidden"></label>
                                	<input type="hidden" name="CheckThe_Code" id="CheckThe_Code" value="" size="20" maxlength="25" >
                                    </div>
                                </div>
								<script>
                                    function functioncheck(myValue)
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
                                                    document.getElementById('CheckThe_Code').value	= recordcount;
                                                    document.getElementById('log_username').value	= '';
                                                    document.getElementById("isHidden").innerHTML 	= ' Username already exist ... !';
                                                    document.getElementById("isHidden").style.color = "#ff0000";
                                                }
                                                else
                                                {
                                                    document.getElementById('CheckThe_Code').value= recordcount;
                                                    document.getElementById("isHidden").innerHTML = ' Username : OK .. !';
                                                    document.getElementById("isHidden").style.color = "green";
                                                }
                                            }
                                        }
                                        var log_username = document.getElementById('log_username').value;
                                        
                                        ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_hr/c_employee/c_employee/getTheCode/';?>" + log_username, true);
                                        ajaxRequest.send(null);
                                    }
                                </script>
                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label"><?php echo $Password ?> </label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="log_password" id="log_password"  placeholder="Password" value="<?php echo "$UNIQE"; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label"><?php echo $ConfirmPass ?> </label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="log_password1" id="log_password1"  placeholder="Confirm Password" value="<?php echo "$UNIQE"; ?>" onChange="checkPassword()">
                                    </div>
                                </div>
                                <br>
                                <script>
									function checkPassword()
									{
										log_password1	= document.getElementById('log_password').value;
										log_password2	= document.getElementById('log_password1').value;
										if(log_password1 != log_password2)
										{
											alert('Password does not match.');
											document.getElementById('log_password1').value = '';
											document.getElementById('log_password1').focus();
											return false;
										}
									}
								</script>
                            </div>
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $StatusnControl; ?></h3>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label"><?php echo $Hint ?> </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="log_passHint" id="log_passHint"  placeholder="User Hint" value="<?php echo "$log_passHint"; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label"><?php echo $UserStatus ?> </label>
                                    <div class="col-sm-10">
                                        <input type="radio" name="Employee_status" id="Employee_status1" value="1" class="minimal" <?php if($Emp_Status == 1) { ?> checked <?php } ?>>
                                        &nbsp;&nbsp;Active&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="Employee_status" id="Employee_status2" value="0" class="minimal" <?php if($Emp_Status == 0) { ?> checked <?php } ?>>
                                        &nbsp;&nbsp;Non Active
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label"><?php echo $UserFlag ?> </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="FlagUSER" id="FlagUSER"  placeholder="User Flag" value="<?php echo "$FlagUSER"; ?>">
                                    </div>
                                </div>
                                <br>
                            </div>
                            <div class="form-group" style="display:none">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <?php
										if($ISAPPROVE == 1)
										{
											if($task=='add')
											{
												?>
													<button class="btn btn-primary">
													<i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
													</button>&nbsp;
												<?php
											}
											else
											{
												?>
													<button class="btn btn-primary" >
													<i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
													</button>&nbsp;
												<?php
											}
										}
										echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
									?>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
						function validateInEmpData()
						{
							EmpNoIdentity	= document.getElementById('EmpNoIdentity').value;
							if(EmpNoIdentity == '')
							{
								alert('Please input Identity Number');
								document.getElementById('EmpNoIdentity').focus();
								return false;
							}
							First_Name	= document.getElementById('First_Name').value;
							if(First_Name == '')
							{
								alert('First Name is Empty, please input.');
								document.getElementById('First_Name').focus();
								return false;
							}
							Birth_Place	= document.getElementById('Birth_Place').value;
							if(Birth_Place == '')
							{
								alert('Birth Place is Empty, please input.');
								document.getElementById('Birth_Place').focus();
								return false;
							}
							log_username	= document.getElementById('log_username').value;
							if(log_username == '')
							{
								alert('Username is Empty, please input.');
								document.getElementById('log_username').focus();
								return false;
							}
							log_password	= document.getElementById('log_password').value;
							if(log_password == '')
							{
								alert('Password is Empty, please input.');
								document.getElementById('log_password').focus();
								return false;
							}
							log_password1	= document.getElementById('log_password1').value;
							if(log_password1 == '')
							{
								alert('Please confirm the password.');
								document.getElementById('log_password1').focus();
								return false;
							}
							if(log_password != log_password1)
							{
								alert('Password does not match.');
								document.getElementById('log_password1').focus();
								return false;
							}
						}
					</script>
                    <div class="tab-pane" id="profPicture">
                        <form class="form-horizontal" name="frm" method="post" action="<?php echo $urlUpdProfPic; ?>" enctype="multipart/form-data" onSubmit="return checkData()">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $UploadProfPict; ?></h3>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $EmployeeID ?> </label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" name="Emp_ID" id="Emp_ID" placeholder="Employee ID" value="<?php echo $Emp_ID; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $FileName ?> </label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" name="FileName" id="FileName" placeholder="File Name" value="<?php echo $compName; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ChooseFile ?> </label>
                                    <div class="col-sm-10">
                                      <input type="file" name="userfile" class="filestyle" data-buttonName="btn-primary"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <?php
										if($ISAPPROVE == 1)
										{
											if($task=='add')
											{
												?>
													<button class="btn btn-primary">
													<i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
													</button>&nbsp;
												<?php
											}
											else
											{
												?>
													<button class="btn btn-primary" >
													<i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
													</button>&nbsp;
												<?php
											}
										}
										echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
                                    ?>
                                </div>
                            </div>
                        </form>
               		</div>
                    <script>
						function checkData()
						{
							filename	= document.getElementById('FileName').value;
							if(filename == '')
							{
								alert('Please input file name.');
								document.getElementById('FileName').focus();
								return false;
							}
						}
					</script>
                    
                    <div class="active tab-pane" id="profPicture" style="display:none">
                        <form class="form-horizontal" name="frm" method="post" action="<?php echo $urlUpdProfPic; ?>" enctype="multipart/form-data" onSubmit="return checkData()">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php //echo $UploadProfPict; ?>Upload Scan KTP dan Ijazah</h3>
                                </div>
                                <div class="form-group">
                                    <label for="inputKTP" class="col-sm-2 control-label"><?php //echo $ChooseFile ?> Pilih File Scan KTP </label>
                                    <div class="col-sm-10">
                                      <input type="file" name="userfileKTP" class="filestyle" data-buttonName="btn-primary"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputIjazah" class="col-sm-2 control-label"><?php //echo $ChooseFile ?> Pilih File Scan Ijazah </label>
                                    <div class="col-sm-10">
                                      <input type="file" name="userfileIjazah" class="filestyle" data-buttonName="btn-primary"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <?php
										if($ISAPPROVE == 1)
										{
											if($task=='add')
											{
												?>
													<button class="btn btn-primary">
													<i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Save; ?>
													</button>&nbsp;
												<?php
											}
											else
											{
												?>
													<button class="btn btn-primary" >
													<i class="cus-save1-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
													</button>&nbsp;
												<?php
											}
										}
										echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
                                    ?>
                                </div>
                            </div>
                        </form>
               		</div>
                    <script>
						function checkData()
						{
							filename	= document.getElementById('FileName').value;
							if(filename == '')
							{
								alert('Please input file name.');
								document.getElementById('FileName').focus();
								return false;
							}
						}
					</script>
                    
				</div>
			</div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
</body>

</html>

<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>

<!-- Select2 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js'; ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.date.extensions.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.extensions.js'; ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.js'; ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js'; ?>"></script>
<!-- Page script -->
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
    $('#datepicker').datepicker({
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
	var decFormat		= 2;
	
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
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>