</head>
<?php
if(empty($MenuCode))
{
	$MenuCode	= "";
}

$LangID			= $this->session->userdata['LangID'];
if(isset($_POST['LangID']))
{
	$LangID	= $_POST['LangID'];
}
$LangDataSess = array('LangID' => $LangID);
$this->session->set_userdata($LangDataSess);	

$username 		= $this->session->userdata('username');
$Emp_ID 		= $this->session->userdata('Emp_ID');
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$LangID			= $this->session->userdata['LangID'];

$ISREAD 		= 0;
$ISCREATE 		= 0;
$ISAPPROVE 		= 0;
$ISDELETE		= 0;
$ISDWONL		= 0;
$sqlAUTH		= "SELECT ISREAD, ISCREATE, ISAPPROVE, ISDELETE, ISDWONL
					FROM tusermenu WHERE emp_id = '$DefEmp_ID' AND menu_code = '$MenuCode'";
$resAUTH 		= $this->db->query($sqlAUTH)->result();
foreach($resAUTH as $rowAUTH) :
	$ISREAD 	= $rowAUTH->ISREAD;
	$ISCREATE 	= $rowAUTH->ISCREATE;
	$ISAPPROVE 	= $rowAUTH->ISAPPROVE;
	$ISDELETE	= $rowAUTH->ISDELETE;
	$ISDWONL	= $rowAUTH->ISDWONL;
endforeach;

if($ISDELETE == 1)
{
	$ISREAD		= 1;
	$ISCREATE	= 1;
	$ISAPPROVE	= 1;
	$ISDWONL	= 1;
}
//echo "$ISDELETE = $ISREAD - $ISCREATE - $ISAPPROVE";
$data = array('ISREAD' => $ISREAD, 'ISCREATE' => $ISCREATE, 'ISAPPROVE' => $ISAPPROVE, 'ISDWONL' => $ISDWONL);
$this->session->set_userdata($data);

$imgemp_filename 	= '';
$imgemp_filenameX	= '';
$sqlGetIMG			= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$Emp_ID'";
$resGetIMG 			= $this->db->query($sqlGetIMG)->result();
foreach($resGetIMG as $rowGIMG) :
	$imgemp_filename 	= $rowGIMG ->imgemp_filename;
	$imgemp_filenameX 	= $rowGIMG ->imgemp_filenameX;
endforeach;
$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID.'/'.$imgemp_filenameX);
$imgLoc1			= base_url('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID);
if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID))
{
	$imgLoc			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
}

$viewFriends		= site_url('c_setting/c_profile/index1/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
$viewMyProfile		= site_url('c_setting/c_profile/viewMyProfile/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
$viewMailBox		= site_url('c_mailbox/c_mailbox/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
//$viewMailBox		= '';
//$viewChatBox1		= site_url('c_chat/c_chat/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
$viewChatBox		= site_url('c_help/c_t180c2hr/?id='.$this->url_encryption_helper->encode_url($Emp_ID));

$sqlApp 		= "SELECT * FROM tappname";
$resultaApp 	= $this->db->query($sqlApp)->result();
foreach($resultaApp as $therow) :
	$appName	= $therow->app_name;
	$top_name 	= $therow->top_name;
endforeach;

$completeName	= $this->session->userdata('completeName');
	
$collData		= "$Emp_ID~$username~$completeName~$appName";
$urlLock		= site_url('__l1y/lockSystem/?id='.$this->url_encryption_helper->encode_url($collData));

function cut_text($var, $len = 200, $txt_titik = "...") 
{
	$var1	= explode("</p>",$var);
	$var	= $var1[0];
	if (strlen ($var) < $len) 
	{ 
		return $var; 
	}
	if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
	{
		return $match [1] . $txt_titik;
	}
	else
	{
		return substr ($var, 0, $len) . $txt_titik;
	}
}

$vwAllTask	= site_url('__l1y/showListTask/');
$vwAllMail	= site_url('__l1y/showListMail/');
?>

<script>
	$(document).ready(function()
	{
		setInterval(function(){cache_clear()}, 36000000);
	});
	
	function cache_clear()
	{
		//alert('This system will be log out.');
		var url = "<?php echo $urlLock; ?>";
		window.location = url;
	}
	
	// Count Task
		$(document).ready(function()
		{
			setInterval(function(){countTask()}, 1000000);
		});
		
		function countTask()
		{
			empId			= document.getElementById('DefEmp_ID').value;
			if(window.XMLHttpRequest)
			{
				//code for IE7+,Firefox,Chrome,Opera,Safari
				xmlhttpTask=new XMLHttpRequest();
			}
			else
			{
				xmlhttpTask=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttpTask.onreadystatechange=function()
			{
				if(xmlhttpTask.readyState==4&&xmlhttpTask.status==200)
				{
					if(xmlhttpTask.responseText > 0)
					{
						document.getElementById('countTask').innerHTML  = xmlhttpTask.responseText;
						ListTask()
					}
					else
					{
						document.getElementById('countTask').innerHTML  = '';
						ListTask()
					}
				}
			}
			xmlhttpTask.open("GET","<?php echo base_url().'index.php/__l1y/TaskCount/';?>"+empId,true);
			xmlhttpTask.send();
			
			//Mail
			if(window.XMLHttpRequest)
			{
				//code for IE7+,Firefox,Chrome,Opera,Safari
				xmlhttpMail=new XMLHttpRequest();
			}
			else
			{
				xmlhttpMail=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttpMail.onreadystatechange=function()
			{
				if(xmlhttpMail.readyState==4&&xmlhttpMail.status==200)
				{
					if(xmlhttpMail.responseText > 0)
					{
						document.getElementById('countMail').innerHTML  = xmlhttpMail.responseText;
					}
					else
					{
						document.getElementById('countMail').innerHTML  = '';
					}
				}
			}
			xmlhttpMail.open("GET","<?php echo base_url().'index.php/__l1y/MailCount/';?>"+empId,true);
			xmlhttpMail.send();
		}
		
		function ListTask()
		{
			var vwAllTask	= "<?php echo $vwAllTask; ?>";
			var vwAllMail	= "<?php echo $vwAllMail; ?>";
			var empId		= document.getElementById('DefEmp_ID').value;
			$("#div_element_task").load(vwAllTask + " #div_element_task");
			$("#div_element_mail").load(vwAllTask + " #div_element_mail");
		}
		
        function changeLanguage(thisVal)
        {
			if(thisVal == 1)
           		document.getElementById("LangID").value = 'IND';
			else
				document.getElementById("LangID").value = 'ENG';
			document.forms['formLang'].submit();
        }
</script>
<body class="skin-blue">
    <!-- Site wrapper -->
    <form name="formLang" id="formLang" method="post" style="display:none">
        <input type="text" name="LangID" id="LangID" value="<?php echo $LangID; ?>">
        <input type="submit" name="submit1" id="submit1" value="OK">
    </form>
    <div class="wrapper">
        <header class="main-header">
            <a href="#" class="logo"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog-256x256.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $top_name; ?></a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
				<?php
                    $GetMailC		= "tbl_mailbox WHERE MB_TO_ID LIKE '%$DefEmp_ID%' AND MB_STATUS = '1'";
                    $RGetMailC		= $this->db->count_all($GetMailC);
					$RGetMailCAl	= $RGetMailC;
                    if($RGetMailC == 0)
					{
                        $RGetMailC	= "no";
                        $RGetMailCAl= "";
					}
                ?>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Tasks: style can be found in dropdown.less -->
                        <li class="dropdown messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-language"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header"><?php if($LangID == 'IND') { ?>Pilihan Bahasa <?php } else { ?> Language  <?php } ?></li>
                                <li>
                                    <ul class="menu">
                                        <?php
                                            $MLANGIND	= 1;
                                            $MLANGENG	= 2;
                                            
                                            $ML_IND		= site_url('c_help/c_t180c2hr/t180c2htread/?id='.$this->url_encryption_helper->encode_url($MLANGIND));
                                            $ML_ENG		= site_url('c_help/c_t180c2hr/t180c2htread/?id='.$this->url_encryption_helper->encode_url($MLANGENG));
                                            
                                            $imgIND		= base_url('assets/AdminLTE-2.0.5/dist/img/ind.png');
                                            $imgENG		= base_url('assets/AdminLTE-2.0.5/dist/img/eng.png');
                                        ?>
                                        <li><!-- start message -->
                                            <a onClick="changeLanguage(1)">
                                                <div class="pull-left">
                                                    <img src="<?php echo $imgIND; ?>" class="img-circle" alt="User Image"/>
                                                </div>
                                                <h4>
                                                    <?php echo "Bahasa Indonesia"; ?>
                                                </h4>
                                                <p>
                                                <?php 
                                                    echo "Indonesian";
                                                ?>
                                                </p>
                                            </a>
                                        </li>
                                        <li><!-- start message -->
                                            <a onClick="changeLanguage(2)">
                                                <div class="pull-left">
                                                    <img src="<?php echo $imgENG; ?>" class="img-circle" alt="User Image"/>
                                                </div>
                                                <h4>
                                                    <?php echo "Bahasa Inggris"; ?>
                                                </h4>
                                                <p>
                                                <?php 
                                                    echo "English";
                                                ?>
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <!-- Messages: style can be found in dropdown.less-->
                        <li class="dropdown messages-menu" style="display:none">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-envelope-o"></i>
                                <span class="label label-danger" id="countMail"><?php echo $RGetMailCAl; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have <?php echo $RGetMailC; ?> new messages(s)</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <div id="div_element_mail">
                                        <ul class="menu">
                                            <?php
                                                $GetMailC		= "tbl_mailbox WHERE MB_TO_ID LIKE '%$DefEmp_ID%' AND MB_STATUS = '1'";
                                                $RGetMailC		= $this->db->count_all($GetMailC);
                                                if($RGetMailC == 0)
                                                    $RGetMailC	= "no";
                                                
                                                $sqlGetMail		= "SELECT MB_ID, MB_SUBJECT, MB_DATE, MB_DATE1, MB_FROM_ID, MB_FROM
                                                                    FROM tbl_mailbox WHERE MB_TO_ID LIKE '%$DefEmp_ID%' AND MB_STATUS = '1'";
                                                $resGetMail		= $this->db->query($sqlGetMail)->result();
                                                foreach($resGetMail as $rowMail) :
                                                    $MB_ID 		= $rowMail ->MB_ID;
                                                    $Read_Mail	= site_url('c_mailbox/c_mailbox/read_mail/?id='.$this->url_encryption_helper->encode_url($MB_ID));
                                                    $MB_SUBJECT = $rowMail ->MB_SUBJECT;
                                                    $MB_SUBJECTD= cut_text("$MB_SUBJECT", 16);
                                                    
                                                    $MB_DATE 	= $rowMail ->MB_DATE;
                                                    $MB_DATE1 	= $rowMail ->MB_DATE1;
                                                    $MB_FROM_ID	= $rowMail ->MB_FROM_ID;
                                                    $FROM_IDA	= explode (";",$MB_FROM_ID);
                                                    $FROM_IDB	= $FROM_IDA[0];									
                                                     // SENDER IMAGE
                                                    $FROM_IMG 	= '';
                                                    $FROM_IMGX 	= '';
                                                    $getFRM_IMG	= "SELECT imgemp_filename, imgemp_filenameX
                                                                    FROM tbl_employee_img WHERE imgemp_empid = '$FROM_IDB'";
                                                    $resFRM_IMG	= $this->db->query($getFRM_IMG)->result();
                                                    foreach($resFRM_IMG as $rowFRM_IMG) :
                                                        $FRM_IMG 	= $rowFRM_IMG ->imgemp_filename;
                                                        $FROM_IMGX 	= $rowFRM_IMG ->imgemp_filenameX;
                                                    endforeach;
                                                    if($FROM_IMGX == '')
                                                    {
                                                        $FROM_IMGX		= "username.jpg";
                                                        $imgFRMEmp		= base_url('assets/AdminLTE-2.0.5/emp_image/'.$FROM_IMGX);
                                                    }
                                                    elseif($FROM_IMGX == 'username.jpg')
                                                    {
                                                        $FROM_IMGX		= "username.jpg";
                                                        $imgFRMEmp		= base_url('assets/AdminLTE-2.0.5/emp_image/'.$FROM_IMGX);
                                                    }
                                                    else
                                                    {
                                                        $imgFRMEmp		= base_url('assets/AdminLTE-2.0.5/emp_image/'.$FROM_IDB.'/'.$FROM_IMGX);												
                                                    }
                                                    
                                                    $MB_FROM	= $rowMail ->MB_FROM;
                                                    $MB_FROMD	= cut_text("$MB_FROM", 10);
                                                    
                                                    $TIME1_Y		= date("Y", strtotime($MB_DATE1));
                                                    $TIME1_M		= date("m", strtotime($MB_DATE1));
                                                    $TIME1_D		= date("d", strtotime($MB_DATE1));
                                                    $TIME1_H		= date("H", strtotime($MB_DATE1));
                                                    $TIME1_I		= date("i", strtotime($MB_DATE1));
                                                    $TIME1_S		= date("s", strtotime($MB_DATE1));
                                                    $TIME1_F 		= mktime($TIME1_H, $TIME1_I, $TIME1_S, $TIME1_M, $TIME1_D, $TIME1_Y);
                                                    $TIME2_F 		= mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
                                                    $TIME_DIFF		= $TIME2_F - $TIME1_F;
                                                    
                                                    $TIME_DIFF_D 	= floor($TIME_DIFF/86400);		// Days
                                                    $TIME_DIFF_H 	= floor($TIME_DIFF/3600);		// Hours
                                                    $TIME_DIFF_M 	= floor($TIME_DIFF/60);			// menit
                                                        
                                                    if($TIME_DIFF_H < 1)
                                                        $TIME_DIFFD	= "$TIME_DIFF_M Min.(s) ago";													
                                                    elseif($TIME_DIFF_H <= 24)
                                                        $TIME_DIFFD	= "$TIME_DIFF_H Hour(s) ago";
                                                    elseif($TIME_DIFF_H <= 48)
                                                        $TIME_DIFFD	= "Yesterday";
                                                    else
                                                        $TIME_DIFFD	= date_format(date_create($MB_DATE), "d M");
                                            ?>
                                            <li><!-- start message -->
                                                <a href="<?php echo $Read_Mail; ?>">
                                                    <div class="pull-left">
                                                        <img src="<?php echo $imgFRMEmp; ?>" class="img-circle" alt="User Image"/>
                                                    </div>
                                                    <h4>
                                                        <?php echo $MB_FROMD; ?>
                                                        <small><i class="fa fa-clock-o"></i><?php echo " $TIME_DIFFD "; ?></small>
                                                    </h4>
                                                    <p><?php echo $MB_SUBJECTD; ?></p>
                                                </a>
                                            </li><!-- end message -->
                                            <?php
                                                endforeach;
                                            ?>
                                        </ul>
                                    </div>
                                </li>
                                <li class="footer"><a href="<?php echo $viewMailBox; ?>">See All Messages</a></li>
                            </ul>
                        </li>
                        <input type="hidden" name="DefEmp_ID" id="DefEmp_ID" value="<?php echo $DefEmp_ID; ?>">
                        <?php
							$sqlC		= "tbl_task_request_detail WHERE TASKD_RSTAT = 1 
											AND (TASKD_EMPID2 LIKE '%$DefEmp_ID%' OR TASKD_EMPID2 = 'All')";
							$resC		= $this->db->count_all($sqlC);
						?>
                        <!-- Tasks: style can be found in dropdown.less -->
                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-comment-o"></i>
                                    <span class="label label-danger" id="countTask"><?php if($resC > 0) echo $resC; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have no task</li>
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <div id="div_element_task">
                                            <ul class="menu">
                                                <?php                                            
                                                    $sqlCHATH 	= "SELECT TASKD_ID, TASKD_PARENT, TASKD_TITLE, TASKD_CONTENT, TASKD_CREATED, 
                                                                        TASKD_EMPID, TASKD_EMPID2
                                                                    FROM tbl_task_request_detail
                                                                    WHERE TASKD_RSTAT = 1 
																		AND (TASKD_EMPID2 LIKE '%$DefEmp_ID%' OR TASKD_EMPID2 = 'All')
                                                                    ORDER BY TASKD_CREATED DESC";
                                                    $resCHATH 	= $this->db->query($sqlCHATH)->result();
                                                    
                                                    foreach($resCHATH as $rowCHATH) :
                                                        // RECEIVER IS NON ACTIVE USER, WHO SENDED BY USER ACTIVE
                                                        $TASKD_ID 		= $rowCHATH->TASKD_ID;
                                                        $TASKD_PARENT 	= $rowCHATH->TASKD_PARENT;
                                                        $TASKD_TITLE	= $rowCHATH->TASKD_TITLE;
                                                        $TASKD_CONTENT	= $rowCHATH->TASKD_CONTENT;
                                                        $TASKD_CREATED	= $rowCHATH->TASKD_CREATED;
                                                        $TASKD_EMPID	= $rowCHATH->TASKD_EMPID;
                                                        $TASKD_EMPID2	= $rowCHATH->TASKD_EMPID2;
                                                        
                                                        $CompName	= '';
                                                        $First_Name	= '';
                                                        $Last_Name	= '';
                                                        $sqlEmp 	= "SELECT First_Name, Last_Name
                                                                        FROM tbl_employee WHERE Emp_ID = '$TASKD_EMPID'";
                                                        $resEmp		= $this->db->query($sqlEmp)->result();
                                                        foreach($resEmp as $rowEmp) :
                                                            $First_Name = $rowEmp->First_Name;
                                                            $Last_Name	= $rowEmp->Last_Name;
                                                        endforeach;
                                                                    
                                                        $Last_Name1		= " $Last_Name";
                                                        $CHAT_SENDNAME1	= "$First_Name$Last_Name1";
                                                        $CHAT_SENDNAME2	= strtolower($CHAT_SENDNAME1);
                                                        $CHAT_SENDNAMEA	= ucwords($CHAT_SENDNAME2);
                                                        
                                                        // GET RECEIVE DATA
                                                        $imgemp_fnRec 	= '';
                                                        $imgemp_fnRecX 	= '';
                                                        $getIMGRec		= "SELECT imgemp_filename, imgemp_filenameX 
                                                                            FROM tbl_employee_img WHERE imgemp_empid = '$TASKD_EMPID'";
                                                        $resIMGRec 		= $this->db->query($getIMGRec)->result();
                                                        foreach($resIMGRec as $rowIMGRec) :
                                                            $imgemp_fnRec 	= $rowIMGRec ->imgemp_filename;
                                                            $imgemp_fnRecX 	= $rowIMGRec ->imgemp_filenameX;
                                                        endforeach;
                                                            
                                                        $imgReqer	= base_url('assets/AdminLTE-2.0.5/emp_image/'.$TASKD_EMPID.'/'.$imgemp_fnRecX);
                                                        if($imgemp_fnRecX == 'username.jpg')
                                                            $imgReqer	= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
                                                        else
                                                            $imgReqer	= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
                                                        
                                                        $Read_Task	= site_url('c_help/c_t180c2hr/t180c2htread/?id='.$this->url_encryption_helper->encode_url($TASKD_PARENT));
                                                        ?>
                                                        <li><!-- start message -->
                                                            <a href="<?php echo $Read_Task; ?>">
                                                                <div class="pull-left">
                                                                    <img src="<?php echo $imgReqer; ?>" class="img-circle" alt="User Image"/>
                                                                </div>
                                                                <h4>
                                                                    <?php echo $CHAT_SENDNAMEA; ?>
                                                                    <small><i class="fa fa-clock-o"></i> <?php echo $TASKD_CREATED; ?></small>
                                                                </h4>
                                                                <p>
																<?php 
																	echo $TASKD_TITLE;
																?>
                                                                </p>
                                                            </a>
                                                        </li><!-- end message -->
                                                        <?php
                                                    endforeach;
                                                ?>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="footer"><a href="<?php echo $viewChatBox; ?>">See All Task</a></li>
                                </ul>
                            </li>
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo $imgLoc; ?>" class="user-image" alt="User Image"/>
                                <span class="hidden-xs"><?php echo $this->session->userdata('completeName'); ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?php echo $imgLoc; ?>" class="img-circle" alt="User Image" />
                                    <p>
                                       <?php echo $this->session->userdata('completeName'); ?>
                                        <small>Member since Feb. 2017</small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body" style="display:none">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?php echo $viewFriends;?>" class="btn btn-default btn-flat">Friends List</a>
                                    </div>
                                    <div class="pull-left">
                                         &nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="pull-left">
                                        <a href="<?php echo $viewMyProfile;?>" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo site_url('__l1y/logout') ?>" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- =============================================== -->