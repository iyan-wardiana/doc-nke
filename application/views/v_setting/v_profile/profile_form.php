<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 16 Februari 2017
	* File Name	= profile_form.php
	* Location		= -
*/
$this->load->view('template/head');

$appName  = $this->session->userdata('appName');
$FlagUSER   = $this->session->userdata['FlagUSER'];
$DefEmp_ID  = $this->session->userdata['Emp_ID'];
$appBody  = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
  $Display_Rows = $row->Display_Rows;
  $decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
  $decFormat    = 2;

$empNameAct = '';
$sqlEMP   = "SELECT CONCAT(First_Name, ' ', Last_Name) AS empName
		        FROM tbl_employee
		        WHERE Emp_ID = '$DefEmp_ID'";
$resEMP   = $this->db->query($sqlEMP)->result();
foreach($resEMP as $rowEMP) :
  $empNameAct = $rowEMP->empName;
endforeach;

$username 			= $this->session->userdata('username');
$Emp_ID 			 = $this->session->userdata('Emp_ID');
$imgemp_filename 	= '';
$imgemp_filenameX 	= '';

// GET DETAIL USER
$sqlGetDet			= "SELECT Emp_ID, EmpNoIdentity, Position_ID, First_Name, Last_Name, gender, Birth_Place, Date_Of_Birth, Mobile_Phone, Email, Religion, 
						Nationality, Marital_Status, Address1, State1, country1, Dept_Code,
						city1, zipcode1, log_username, log_password, log_passHint, FlagUSER, Emp_Location, Emp_Notes
						FROM tbl_employee WHERE Emp_ID = '$Emp_ID'";
$resGetDet 			= $this->db->query($sqlGetDet)->result();
foreach($resGetDet as $rowGDet) :
	$Emp_ID 		= $rowGDet ->Emp_ID;
	$EmpNoIdentity 	= $rowGDet ->EmpNoIdentity;
	$Position_ID 	= $rowGDet ->Position_ID;
	$First_Name 	= $rowGDet ->First_Name;
	$Last_Name 		= $rowGDet ->Last_Name;
	$gender 		= $rowGDet ->gender;
	$Birth_Place 	= $rowGDet ->Birth_Place;
	$Date_Of_Birth1	= $rowGDet ->Date_Of_Birth;
	$Date_Of_Birth	= date('m/d/Y',strtotime($Date_Of_Birth1));
	$Mobile_Phone 	= $rowGDet ->Mobile_Phone;
	$Email 			= $rowGDet ->Email;
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
	$Dept_Code 		= $rowGDet ->Dept_Code;
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

$UNIQE				= '';
$sqlGetPs			= "SELECT P AS UNIQE FROM others WHERE NK = '$Emp_ID'";
$resGetPs 			= $this->db->query($sqlGetPs)->result();
foreach($resGetPs as $rowGPs) :
	$UNIQE 	= $rowGPs->UNIQE;
endforeach;

$sqlGetIMG			= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$Emp_ID'";
$resGetIMG 			= $this->db->query($sqlGetIMG)->result();
foreach($resGetIMG as $rowGIMG) :
	$imgemp_filename 	= $rowGIMG ->imgemp_filename;
	$imgemp_filenameX 	= $rowGIMG ->imgemp_filenameX;
endforeach;
$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID.'/'.$imgemp_filenameX);
if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID))
{
	$imgLoc			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
}

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
	
// Mencari jumlah total karyawan
$sqlGetCirC			= "tbl_employee WHERE Employee_status = '1'";
$TOTFRIENDS			= $this->db->count_all($sqlGetCirC);

$urlUpdProf			= site_url('c_setting/c_profile/update_processS/?id='.$this->url_encryption_helper->encode_url($appName));
$urlUpdProfPic		= site_url('c_setting/c_profile/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
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
			if($TranslCode == 'married')$married = $LangTransl;
			if($TranslCode == 'widow')$widow = $LangTransl;
			if($TranslCode == 'widower')$widower = $LangTransl;
			if($TranslCode == 'Setting')$Setting = $LangTransl;
			if($TranslCode == 'Profile')$Profile = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$EmployeeStatus = "$Status $Employee";
			$TaxStstus		= "$Status $Tax";
			$HeadOffice		= "Kantor Pusat";
		}
		else
		{
			$EmployeeStatus = "$Employee $Status";
			$TaxStstus		= "$Tax $Status";
			$HeadOffice		= "Head Office";
		}

		if($Emp_Location == 1)
		{
			$Emp_LocationD	= $HeadOffice;
		}
		else
		{
			$Emp_LocationD	= "Project";
		}

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
		  	<h1>
		      	<?php echo $Profile; ?>
		      	<small><?php echo $Setting; ?></small>
		    </h1>
		</section>

		<style>
			.search-table, td, th {
				border-collapse: collapse;
			}
			.search-table-outter { overflow-x: scroll; }
		</style>

		<section class="content">
		    <div class="row">
		        <div class="col-md-3">
		          	<div class="box box-primary">
			            <div class="box-body box-profile">
			          		<img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" alt="User profile picture" style="width:90; height:90">
			         		<h3 class="profile-username text-center"><?php echo "$First_Name $Last_Name"; ?></h3>
			          		<p class="text-muted text-center"><?php echo $Emp_LocationD; ?></p>
							<ul class="list-group list-group-unbordered">
								<li class="list-group-item">
									<b>Followers</b> <a class="pull-right"><?php print number_format($TOTFOLLOWERS, 0); ?></a>
								</li>
								<li class="list-group-item">
									<b>Following</b> <a class="pull-right"><?php print number_format($TOTFOLLOWINGS, 0); ?></a>
								</li>
								<li class="list-group-item">
									<b>Friends</b> <a class="pull-right"><?php print number_format($TOTFRIENDS, 0); ?></a>
								</li>
							</ul>
			          		<a href="#" class="btn btn-primary btn-block" style="display:none"><b>Follow</b></a>
			        	</div>
		          	</div>

		          	<div class="box box-primary">
			            <div class="box-header with-border">
			              	<h3 class="box-title">About Me</h3>
			            </div>

			            <div class="box-body">
			              	<strong><i class="fa fa-book margin-r-5"></i> Education</strong>
			              	<p class="text-muted">
			                	<em>None</em>
			                </p>
			              	<hr>

			              	<strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

			              	<p class="text-muted"><?php echo "$Address1, $State1 $zipcode1 $Nationality"; ?></p>

			              	<hr>

			              	<strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>

			              	<p>
			              		<em>None</em>
			                	<?php /*?><span class="label label-danger">UI Design</span>
			                	<span class="label label-success">Coding</span>
			                	<span class="label label-info">Javascript</span>
			                	<span class="label label-warning">PHP</span>
			                	<span class="label label-primary">Node.js</span><?php */?>
			              	</p>

			              	<hr>

			              	<strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

			              	<p><em>None</em></p>
			            </div>
			        </div>
		        </div>

		        <div class="col-md-9">
					<div class="nav-tabs-custom">
		            	<ul class="nav nav-tabs">
		                	<li class="active"><a href="#settings" data-toggle="tab">Setting</a></li> 		<!-- Tab 1 -->
		                    <li><a href="#profpicture" onclick="showPIC();" data-toggle="tab">Profile Picture</a></li>			<!-- Tab 2 -->
		                </ul>
		                <script type="text/javascript">
		                	function showPIC()
		                	{
		                		document.getElementById('profpicture').style.display = '';
		                	}
		                </script>
		                <div class="tab-content">
		                    <div class="active tab-pane" id="settings">
		                        <form class="form-horizontal" name="myForm" id="myForm" method="post" action="<?php echo $urlUpdProf; ?>">
		                            <div class="box box-success">
		                                <div class="box-header with-border">
		                                    <h3 class="box-title"><?php echo $ProfileInfo; ?></h3>
		                                </div><br>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-2 control-label">Employee ID</label>
		                                    <div class="col-sm-4">
		                                      	<input type="text" class="form-control" name="Emp_ID1" id="Emp_ID1" value="<?php echo "$Emp_ID"; ?>" disabled>
		                                      	<input type="hidden" class="form-control" name="Emp_ID" id="Emp_ID" placeholder="EMP ID" style="max-width:250px;" value="<?php echo "$Emp_ID"; ?>" >
		                                    </div>
		                                    <div class="col-sm-2">
			                                    <label for="inputName" class="control-label pull-right">No. KTP</label>
			                                </div>
		                                    <div class="col-sm-4">
		                                      	<input type="text" class="form-control" name="EmpNoIdentity" id="EmpNoIdentity" placeholder="Identification ID" value="<?php echo "$EmpNoIdentity"; ?>" >
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $FirstName ?> </label>
		                                    <div class="col-sm-4">
		                                      	<input type="text" class="form-control" name="First_Name" id="First_Name" placeholder="First Name" value="<?php echo "$First_Name"; ?>" >
		                                    </div>
		                                    <div class="col-sm-2">
			                                    <label for="inputName" class="control-label pull-right"><?php echo $LastName ?> </label>
			                                </div>
		                                    <div class="col-sm-4">
		                                      	<input type="text" class="form-control" name="Last_Name" id="Last_Name" placeholder="Last Name" value="<?php echo "$Last_Name"; ?>" >
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PlaceofBirth ?></label>
		                                    <div class="col-sm-4">
		                                    	<input type="text" class="form-control" name="Birth_Place" id="Birth_Place" placeholder="Last Name" value="<?php echo "$Birth_Place"; ?>" >
		                                    </div>
		                                    <div class="col-sm-2">
			                                    <label for="inputName" class="control-label pull-right"><?php echo $DateofBirth ?> </label>
			                                </div>
		                                    <div class="col-sm-4">
		                                      	<div class="input-group date">
		                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
		                                            <input type="text" name="Date_Of_Birth" class="form-control pull-left" id="datepicker" value="<?php echo $Date_Of_Birth; ?>" size="10" style="width:110px" >
		                                        </div>
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ReligionSubt ?> </label>
		                                    <div class="col-sm-4">
		                                      	<select name="Religion" id="Religion" class="form-control select2">
		                                            <option value="Islam" <?php if($Religion == 'Islam') { ?> selected <?php } ?>>Islam</option>
		                                            <option value="Kristen" <?php if($Religion == 'Kristen') { ?> selected <?php } ?>>Kristen</option>
		                                            <option value="Katolik" <?php if($Religion == 'Katolik') { ?> selected <?php } ?>>Katolik</option>
		                                            <option value="Hindu" <?php if($Religion == 'Hindu') { ?> selected <?php } ?>>Hindu</option>
		                                            <option value="Budha" <?php if($Religion == 'Budha') { ?> selected <?php } ?>>Budha</option>
		                                            <option value="Other" <?php if($Religion == 'Other') { ?> selected <?php } ?>>Other</option>
		                                        </select>
		                                    </div>
		                                    <label for="inputName" class="col-sm-2 control-label">Gender</label>
		                                    <div class="col-sm-4">
		                                        <input type="radio" name="gender" id="gender1" value="male" class="minimal" <?php if($gender == 'male') { ?> checked <?php } ?>>
		                                        &nbsp;&nbsp;Male&nbsp;&nbsp;&nbsp;&nbsp;
		                                        <input type="radio" name="gender" id="gender2" value="female" class="minimal" <?php if($gender == 'female') { ?> checked <?php } ?>>
		                                        &nbsp;&nbsp;Female
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <div class="col-sm-2">
			                                    <label for="inputName" class="control-label pull-right"><?php echo $MaritalStatus ?> </label>
			                                </div>
		                                    <div class="col-sm-4">
		                                      	<select name="Marital_Status" id="Marital_Status" class="form-control select2" >
		                                            <option value="Single"> --- </option>
		                                            <option value="Single" <?php if($Marital_Status == 'Single') { ?> selected <?php } ?>>Single</option>
		                                            <option value="Married" <?php if($Marital_Status == 'Married') { ?> selected <?php } ?>><?php echo $married; ?></option>
		                                            <option value="Widow" <?php if($Marital_Status == 'Widow') { ?> selected <?php } ?>><?php echo $widow; ?></option>
		                                            <option value="Widower" <?php if($Marital_Status == 'Widower') { ?> selected <?php } ?>><?php echo $widower; ?></option>
		                                        </select>
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $Address ?> </label>
		                                    <div class="col-sm-4">
		                                    	<textarea class="form-control" name="Address1" id="Address1" placeholder="Address"><?php echo $Address1; ?></textarea>
		                                    </div>
		                                    <div class="col-sm-2">
			                                    <label for="inputName" class="control-label pull-right"><?php echo $City ?> </label>
			                                </div>
		                                    <div class="col-sm-4">
		                                      	<input type="text" class="form-control" name="city1" id="city1" placeholder="City" value="<?php echo "$city1"; ?>">
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $Province ?> </label>
		                                    <div class="col-sm-4">
		                                    	<input type="text" class="form-control" name="country1" id="country1" placeholder="Province" value="<?php echo "$country1"; ?>">
		                                    </div>
		                                    <div class="col-sm-2">
			                                    <label for="inputName" class="control-label pull-right"><?php echo $State ?> </label>
			                                </div>
		                                    <div class="col-sm-4">
		                                      	<input type="text" class="form-control" name="State1" id="State1" placeholder="State" value="<?php echo "$State1"; ?>">
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputSkills" class="col-sm-2 control-label"><?php echo $Email ?> </label>
		                                    <div class="col-sm-4">
		                                    	<input type="email" class="form-control" name="Email" id="Email" placeholder="Email" value="<?php echo "$Email"; ?>">
		                                    </div>
		                                    <div class="col-sm-2">
			                                    <label for="inputName" class="control-label pull-right"><?php echo $Phone ?> </label>
			                                </div>
		                                    <div class="col-sm-4">
		                                      	<input type="text" class="form-control" name="Mobile_Phone" id="Mobile_Phone" placeholder="Phone No." value="<?php echo "$Mobile_Phone"; ?>">
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputSkills" class="col-sm-2 control-label"><?php echo $Location ?> </label>
		                                    <div class="col-sm-4">
		                                        <select name="Emp_Location" id="Emp_Location" class="form-control select2" >
		                                        	<option value="1"> --- </option>
		                                            <option value="1" <?php if($Emp_Location == 1) { ?> selected <?php } ?>><?=$HeadOffice?></option>
		                                            <option value="2" <?php if($Emp_Location == 2) { ?> selected <?php } ?>>Pabrik</option>
		                                        </select>
		                                    </div>
		                                    <div class="col-sm-2">
			                                    <label for="inputName" class="control-label pull-right"><?php echo $Department ?> </label>
			                                </div>
		                                    <div class="col-sm-4">
		                                    	<select name="Dept_Code" id="Dept_Code" class="form-control select2" >
						                        	<option value="0" > --- </option>
													<?php
													$cDept		= $this->db->count_all('tbl_position_str');
													$sqlDept 	= "SELECT * FROM tbl_position_str";
													$resDept	= $this->db->query($sqlDept)->result();
						                            if($cDept>0)
						                            {
						                                $i = 0;
						                                foreach($resDept as $row) :
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
						                                		<option value="<?php echo $POSS_CODE1;?>" <?php if($POSS_CODE1 == $Dept_Code) { ?>selected <?php } if($ressqlC1>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$SPACELEV$POSS_NAME1";?></option>
						                            		<?php
														endforeach;
						                            }
						                            ?>
												</select>
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputSkills" class="col-sm-2 control-label"><?php echo $Notes ?> </label>
		                                    <div class="col-sm-10">
		                                    	<textarea class="form-control" name="Emp_Notes" id="Emp_Notes" placeholder="Notes"><?php echo $Emp_Notes; ?></textarea>
		                                    </div>
		                                </div><br>
		                            </div>
		                            <div class="box box-warning">
		                                <div class="box-header with-border">
		                                    <h3 class="box-title">Authorization</h3>
		                                </div><br>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-2 control-label">Username</label>
		                                    <div class="col-sm-10">
		                                    <input type="text" class="form-control" name="log_username" id="log_username" placeholder="Username" value="<?php echo "$log_username"; ?>">
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputSkills" class="col-sm-2 control-label">Password</label>
		                                    <div class="col-sm-10">
		                                        <input type="password" class="form-control" name="log_password" id="log_password" placeholder="Password" value="<?php echo "$UNIQE"; ?>">
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputSkills" class="col-sm-2 control-label">Confirm Pass.</label>
		                                    <div class="col-sm-10">
		                                        <input type="password" class="form-control" name="log_password1" id="log_password1" placeholder="Confirm Password" value="<?php echo "$UNIQE"; ?>" onChange="checkPassword()">
		                                    </div>
		                                </div>
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
										</script><br>
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
		                                    <!-- <button type="button" class="btn btn-primary" id="deleteamc" name="delete">Submit</button> -->
		                                    <button type="button" class="btn btn-primary" id="deleteamc" name="delete">Submit</button>
											<?php 
		                                        /*if ( ! empty($link))
		                                        {
		                                            foreach($link as $links)
		                                            {
		                                                echo $links;
		                                            }
		                                        }*/
		                                    ?>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                    <script>
								$('#deleteamc').on('click',function(e) 
								{
									var fname = document.getElementById("myForm").name;

									event.preventDefault();

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

								    swal({
										  title: "Anda Yakin?",
										  text: "Sistem akan melakukan perubahan sesuai data yang Anda isi.",
										  icon: "warning",
										  buttons: ["Tidak", "Ya"],
										  dangerMode: true,
										})
										.then((willDelete) => {
										if (willDelete) 
										{
										    swal("Baik! Sistem akan Log Out. Silahkan Log In kembali ... !", {icon: "success"})
											.then((value) => {
											    var frm = $('#myForm');
										        e.preventDefault();

										        $.ajax({
										            type: frm.attr('method'),
										            url: frm.attr('action'),
										            data: frm.serialize(),
										            success: function (data) {
										                parent.logOut();
										            },
										            error: function (data) {
										                parent.logOut();
										            },
										        });
											});
										} else {
										    swal("Baik! Sistem akan membatalkan perubahan profile Anda.", {icon: "error"})
										}
									});
								});
								/*$('#deleteamc').on('click',function(e) 
								{
									swal("Click on either the button or outside the modal.")
									.then((value) => {
									  swal('The returned value is:');
									  document.getElementById('myForm').submit();
									});
								});*/
								//$('#myForm').submit();
							</script>
		                    <div class="active tab-pane" id="profpicture" style="display: none;">
		                        <form class="form-horizontal" name="frm" method="post" action="<?php echo $urlUpdProfPic; ?>" enctype="multipart/form-data" onSubmit="return checkData()">
		                            <div class="box box-success">
		                                <div class="box-header with-border">
		                                    <h3 class="box-title"><?php echo $UploadProfPict; ?></h3>
		                                </div><br>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-2 control-label">Employee ID</label>
		                                    <div class="col-sm-10">
		                                      <input type="text" class="form-control" name="Emp_ID2" id="Emp_ID2" value="<?php echo $Emp_ID; ?>" disabled>
		                                      <input type="hidden" class="form-control" name="Emp_ID" id="Emp_ID" style="max-width:250px;" placeholder="Employee ID" value="<?php echo $Emp_ID; ?>">
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-2 control-label">File Name</label>
		                                    <div class="col-sm-10">
		                                      <input type="text" class="form-control" name="FileName" id="FileName" placeholder="File Name" value="<?php echo $compName; ?>">
		                                    </div>
		                                </div>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-2 control-label">Choose File</label>
		                                    <div class="col-sm-10">
		                                      <input type="file" name="userfile" class="filestyle" data-buttonName="btn-primary"/>
		                                    </div>
		                                </div><br>
		                            </div>
		                            <div class="form-group">
		                                <div class="col-sm-offset-2 col-sm-10">
		                                    <button type="submit" class="btn btn-primary">Submit</button>
											<?php 
		                                        /*if ( ! empty($link))
		                                        {
		                                            foreach($link as $links)
		                                            {
		                                                echo $links;
		                                            }
		                                        }*/
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
										swal("","Please input file name.", "error");
										document.getElementById('FileName').focus();
										return false;
									}
								}
							</script>
						</div>
					</div>
		        	<?php
						$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		        		if($DefEmp_ID == 'D15040004221')
		                	echo "<font size='1'><i>$act_lnk</i></font>";
		            ?>
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

	function submForm()
	{
		/*var url 		= form.attr("action");
	    var formData 	= $(form).serializeArray();
	    $.post(url, formData).done(function (data) {
	        alert(data);
	    });*/
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