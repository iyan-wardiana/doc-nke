<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 28 Juli 2017
 * File Name	= draft_maillist.php
 * Location		= -
*/
date_default_timezone_set("Asia/Jakarta");

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$this->load->view('template/topbar');
$this->load->view('template/sidebar');
$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
$Emp_DeptCode		= $this->session->userdata['Emp_DeptCode'];
function jin_pendek ($var, $len = 200, $txt_titik = "...") 
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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Mailbox</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- fullCalendar 2.2.5-->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fullcalendar/fullcalendar.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fullcalendar/fullcalendar.print.css'; ?>" media="print">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/flat/blue.css'; ?>">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo $h2_title; ?>
        <small><?php echo $countInbox; ?> messages receipt</small>
    </h1>
    <?php /*?><ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mailbox</li>
    </ol><?php */?>
</section>
<?php
	$secInbox_Mail	= site_url('c_mailbox/c_mailbox/inbox_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secSend_Mail	= site_url('c_mailbox/c_mailbox/sent_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secDraft_Mail	= site_url('c_mailbox/c_mailbox/draft_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secTrash_Mail	= site_url('c_mailbox/c_mailbox/trash_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secWrite_Mail	= site_url('c_mailbox/c_mailbox/write_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	
	// delete all url
	$secInbox_Del	= site_url('c_mailbox/c_mailbox/trash_mail_process_idx_I_all/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secSend_Del	= site_url('c_mailbox/c_mailbox/trash_mail_process_idx_S_all/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secDraft_Del	= site_url('c_mailbox/c_mailbox/trash_mail_process_idx_D_all/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
	$secTrash_Del	= site_url('c_mailbox/c_mailbox/trash_mail_process_idx_T_all/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-3" style="width:20%">
            <a href="<?php echo $secWrite_Mail; ?>" class="btn btn-primary btn-block margin-bottom">Write Mail</a>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Folders</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li>
                        	<a href="<?php echo $secInbox_Mail; ?>"><i class="fa fa-inbox"></i> Inbox
                        	<span class="label label-primary pull-right"><?php echo $countInbox; ?></span></a>
						</li>
                        <li>
                        	<a href="<?php echo $secSend_Mail; ?>"><i class="fa fa-envelope-o"></i> Sent
                        	<span class="label label-warning pull-right"><?php echo $countSent; ?></span></a>
                        </li>
                        <li class="active">
                        	<a href="<?php echo $secDraft_Mail; ?>"><i class="fa fa-file-text-o"></i> Drafts
                            <span class="label label-warning pull-right"><?php echo $countDraft; ?></span></a>
						</li>
                        <li style="display:none">
                        	<a href="#"><i class="fa fa-filter"></i> Junk
                            <span class="label label-warning pull-right"><?php echo $countJunk; ?></span></a>
                        </li>
                        <li>
                        	<a href="<?php echo $secTrash_Mail; ?>"><i class="fa fa-trash-o"></i> Trash
                            <span class="label label-warning pull-right"><?php echo $countTrash; ?></span></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="box box-solid" style="display:none">
                <div class="box-header with-border">
                    <h3 class="box-title">Chat Messanger</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
                        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
                        <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9" style="width:80%">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Draft Mail</h3>
                    <div class="box-tools pull-right">
                        <div class="has-feedback">
                            <input type="text" class="form-control input-sm" placeholder="Search Mail" style="display:none">
                            <span class="glyphicon glyphicon-search form-control-feedback" style="display:none"></span>
                        </div>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <div class="mailbox-controls">
                        <!-- Check all button -->
                        <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm" onClick="deleteMailAll()">
                            	<i class="fa fa-trash-o"></i>
                            </button>
                            <button type="button" class="btn btn-default btn-sm" style="display:none"><i class="fa fa-reply"></i></button>
                            <button type="button" class="btn btn-default btn-sm" style="display:none"><i class="fa fa-share"></i></button>
                        </div>
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh" onClick="refreshPage()"></i></button>
                        <div class="pull-right" style="display:none">
                            1-50/200
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <script>
						function refreshPage()
						{
                    		window.location.reload(true);
						}
					</script>
                    <div class="box">
                        <div class="box-body">
                            <div class="table-responsive mailbox-messages">
                            <span id ="Display">
                            	<form method="post" name="sendDeleteAll" id="sendDeleteAll" class="form-del-all" action="<?php echo $secDraft_Del; ?>" style="display:none">		
                                    <table>
                                        <tr>
                                            <td><input type="text" name="collID" id="collID" value=""></td>
                                            <td>
                                            	<a class="tombol-delete-all" id="delClassAll">Delete All</a>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            	<form method="post" name="sendDelete" id="sendDelete" class="form-user" action="" style="display:none">		
                                    <table>
                                        <tr>
                                            <td></td>
                                            <td><a class="tombol-delete" id="delClass">Simpan</a></td>
                                        </tr>
                                    </table>
                                </form>
                                <table id="example1" class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%">&nbsp;</th>
                                            <th width="5%" class="mailbox-star" nowrap>Mail To</th>
                                            <th width="7%" class="mailbox-name">Subject</th>
                                            <th width="65%" class="mailbox-subject">Message</th>
                                            <th width="7%" class="mailbox-attachment">Attach.</th>
                                            <th width="5%" class="mailbox-date">Receipt</th>
                                            <th width="6%" class="mailbox-date">&nbsp;</th>
                                        </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $i = 0;
                                        $j = 0;
										//while($row= mysql_fetch_array($viewmail))
										//{
											
                                        if($countDraft >0)
                                        {
                                            foreach($viewmail as $row) :
                                                $myNewNo 		= ++$i;
                                                $MB_ID 			= $row->MB_ID;
                                                $MB_CODE 		= $row->MB_CODE;
                                                $MB_PARENTC		= $row->MB_PARENTC;
                                                $MB_DATE		= $row->MB_DATE;
                                                $MB_DATE1		= $row->MB_DATE1;
                                                $MB_READD		= $row->MB_READD;
                                                $MB_SUBJECT		= $row->MB_SUBJECT;
                                                $MB_FROM_ID		= $row->MB_FROM_ID;
                                                $MB_FROM		= $row->MB_FROM;
                                                $MB_TO_ID		= $row->MB_TO_ID;
                                                $MB_TO			= $row->MB_TO;
                                                $MB_TOV			= $row->MB_TO;
                                                $MB_MESSAGE		= $row->MB_MESSAGE;
                                                $MB_STATUS		= $row->MB_STATUS;
                                                $MB_FN1			= $row->MB_FN1;
												
												//$TIME1 		= strtotime($MB_DATE1);
												$TIME1_Y		= date("Y", strtotime($MB_DATE1));
												$TIME1_M		= date("m", strtotime($MB_DATE1));
												$TIME1_D		= date("d", strtotime($MB_DATE1));
												$TIME1_H		= date("H", strtotime($MB_DATE1));
												$TIME1_I		= date("i", strtotime($MB_DATE1));
												$TIME1_S		= date("s", strtotime($MB_DATE1));
												$TIME1_F 		= mktime($TIME1_H, $TIME1_I, $TIME1_S, $TIME1_M, $TIME1_D, $TIME1_Y);
												//$TIME2 			= strtotime('now');
												$TIME2_F 		= mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
												$TIME_DIFF		= $TIME2_F - $TIME1_F;
												
												/*$waktu_sekarang = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));

												// hitung selisih kedua waktu
												$waktu_tujuan = mktime(10,0,0,7,27,2017);
												$selisih_waktu = $waktu_sekarang - $waktu_tujuan;
												
												// Untuk menghitung jumlah dalam satuan hari:
												$jumlah_hari = floor($selisih_waktu/86400);
												
												// Untuk menghitung jumlah dalam satuan jam:
												$sisa = $selisih_waktu % 86400;
												$jumlah_jam = floor($sisa/3600);
												
												// Untuk menghitung jumlah dalam satuan menit:
												$sisa = $sisa % 3600;
												$jumlah_menit = floor($sisa/60);
												
												echo "$MB_DATE1 == $TIME1_F : $TIME2_F = $jumlah_hari : $jumlah_jam : $jumlah_menit<br>";*/
												
												// DIFF IN DAYS
													$TIME_DIFF_D 	= floor($TIME_DIFF/86400);		// Days
													$TIME_DIFF_H 	= floor($TIME_DIFF/3600);		// Hours
													$TIME_DIFF_M 	= floor($TIME_DIFF/60);			// menit
												// DIFF IN HOURS
													//$TIME_DIFF_H1	= $TIME_DIFF % 86400;
													//$TIME_DIFF_H	= floor($TIME_DIFF_H1/3600);	// Remain Hours
												// DIFF IN MINUTES
													//$TIME_DIFF_M1 = $TIME_DIFF_H1 % 3600;
													//$TIME_DIFF_M	= floor($TIME_DIFF_M1/60);		// Remain Minutes
													
												if($TIME_DIFF_H < 1)
													$TIME_DIFFD	= "$TIME_DIFF_M Min.(s) ago";													
												elseif($TIME_DIFF_H <= 24)
													$TIME_DIFFD	= "$TIME_DIFF_H Houraa(s) ago";
												elseif($TIME_DIFF_H <= 48)
													$TIME_DIFFD	= "Yesterday";
												else
													$TIME_DIFFD	= date_format(date_create($MB_DATE), "d M");
																								
                                                $secRead_Mail	= site_url('c_mailbox/c_mailbox/read_mail/?id='.$this->url_encryption_helper->encode_url($MB_ID));
                                                if($MB_STATUS == 1)
												{
													if ($j==1) {
														echo "<tr class=zebra1 style='font-weight:bold'>";
														$j++;
													} else {
														echo "<tr class=zebra2 style='font-weight:bold'>";
														$j--;
													}
												}
												else
												{
													if ($j==1) {
														echo "<tr class=zebra1>";
														$j++;
													} else {
														echo "<tr class=zebra2>";
														$j--;
													}
												}
                                                ?>
                                                    <td>
                                                   		<?php /*?><input type="checkbox"><?php */?>
                                                    	<input type="checkbox" name="myChkAll" id="myChkAll<?php echo $myNewNo; ?>" value="<?php echo $myNewNo;?>" >
                                                        <input type="hidden" id="data<?php echo $myNewNo; ?>MB_ID" name="data[<?php echo $myNewNo; ?>][MB_ID]" value="<?php echo $MB_ID; ?>" width="10" size="15" readonly class="form-control">
                                                    </td>
                                                    <td class="mailbox-name" nowrap>
														<?php
                                                        	$secUpdate_Mail	= site_url('c_mailbox/c_mailbox/draft_mail_update/?id='.$this->url_encryption_helper->encode_url($MB_ID));
															if($MB_TO == '')
																$MB_TO	= " ";
                                                            $MB_TOV	= jin_pendek ("$MB_TO", 16);
                                                            echo anchor($secUpdate_Mail, $MB_TOV);
                                                        ?>
                                                    </td>
                                                    <td class="mailbox-subject" nowrap>
														<?php
                                                            echo jin_pendek ("$MB_SUBJECT", 16);
                                                        ?>
                                                    </td>
                                                    <td class="mailbox-subject">
														<?php
                                                            echo jin_pendek ("$MB_MESSAGE", 50);
                                                        ?>
                                                    </td>
                                                    <td class="mailbox-attachment" style="text-align:center">
                                                    	<?php
															$FileUpName = $MB_FN1;
															$secDLFile	= base_url('mail_attachment/'.$FileUpName);
															if($FileUpName != '')
															{
														?>
                                                                <a href="<?php echo $secDLFile; ?>" title="Download file" class="btn btn-success btn-xs">
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                                        <?php
															}
														?>
                                                    </td>
                                                    <td width="5%" nowrap class="mailbox-date">
                                                    <?php
														echo $TIME_DIFFD;
											$secDel_Mail = base_url().'index.php/c_mailbox/c_mailbox/trash_mail_process_idx_D/?id='.$MB_ID;
													?>
                                                    </td>
                                                    <td width="6%" nowrap class="mailbox-date" style="text-align:center">
                                                        <a href="#" onClick="deleteMail('<?php echo $secDel_Mail; ?>')" title="Delete file" class="btn btn-danger btn-xs">
                                                        	<i class="fa fa-trash-o"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                            endforeach; 
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </span>
                            </div>
                        </div>
                    </div>
				</div>
                <div class="box-footer no-padding">
                    <div class="mailbox-controls" style="display:none">
                        <!-- Check all button -->
                        <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                        </div>
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                        <div class="pull-right">
                            1-50/200
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
	function deleteMailAll()
	{
		var totChk = document.getElementsByName('myChkAll').length;
		
		if (totChk!=null) 
		{
			thechk=document.getElementsByName('myChkAll');
			panjang = parseInt(thechk.length);
		} 
		else 
		{
			thechk[0]=document.getElementsByName('myChkAll');
			panjang = 0;
		}
		
		var panjang = panjang + 1;
		var collID	= '';
		var j		= 0;
		for (var i=0;i<panjang;i++) 
		{
			var temp = 'tr_'+parseInt(i+1);
			if(i>0)
			{
				var elitem1		= document.getElementById('data'+i+'MB_ID').value;
				var ischecked	= document.getElementById('myChkAll'+i).checked;
				if(ischecked == true)
				{
					var j = j + 1;
					if(j == 1)
						var collID	= ''+elitem1+'';
					else if(j > 1)
						var collID	= ''+collID+'|'+elitem1+'';
				}
			}
		}
		if(collID == '')
		{
			alert('Please check mail to trash');
			return false;
		}
		
		document.getElementById('collID').value = collID;
		document.getElementById('delClassAll').click();			
	}
	
	$(document).ready(function()
	{
		$(".tombol-delete-all").click(function()
		{
			var index_Mail	= "<?php echo $secDraft_Mail; ?>";
			var formAction 	= $('#sendDeleteAll')[0].action;
			var data = $('.form-del-all').serialize();
			$.ajax({
				type: 'POST',
				url: formAction,
				data: data,
				success: function(response)
				{
					$( "#example1" ).load( ""+index_Mail+" #example1" );
				}
			});
			
		});
	});
	
	function deleteMail(thisVal)
	{
		document.sendDelete.action = thisVal;
		document.getElementById('delClass').click();			
	}
	
	$(document).ready(function()
	{
		$(".tombol-delete").click(function()
		{
			var index_Mail	= "<?php echo $secDraft_Mail; ?>";
			var formAction 	= $('#sendDelete')[0].action;
			var data = $('.form-user').serialize();
			$.ajax({
				type: 'POST',
				url: formAction,
				data: data,
				success: function(response)
				{
					$( "#example1" ).load( ""+index_Mail+" #example1" );
				}
			});
			
		});
	});
	
    /*function deletedata(MB_ID)
    {
		var xmlhttp;
		if (MB_ID=="")
		{
			document.getElementById("Display").innerHTML = "";
			return;
		}
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				//alert(xmlhttp.responseText)
			  	window.location.reload();
				//document.getElementById('Display').value = xmlhttp.responseText;		
			}
		}
		xmlhttp.open("GET",MB_ID,true);
		xmlhttp.send();
    }*/
	
  $(function () 
  {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
  
  $(function () {
    //Enable iCheck plugin for checkboxes
    //iCheck for checkbox and radio inputs
    $('.mailbox-messages input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
      var clicks = $(this).data('clicks');
      if (clicks) {
        //Uncheck all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
      } else {
        //Check all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("check");
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
      }
      $(this).data("clicks", !clicks);
    });

    //Handle starring for glyphicon and font awesome
    $(".mailbox-star").click(function (e) {
      e.preventDefault();
      //detect type
      var $this = $(this).find("a > i");
      var glyph = $this.hasClass("glyphicon");
      var fa = $this.hasClass("fa");

      //Switch states
      if (glyph) {
        $this.toggleClass("glyphicon-star");
        $this.toggleClass("glyphicon-star-empty");
      }

      if (fa) {
        $this.toggleClass("fa-star");
        $this.toggleClass("fa-star-o");
      }
    });
  });
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>
</body>
</html>