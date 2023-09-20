<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 30 Agustus 2017
 * File Name	= v_task_request.php
 * Location		= -
*/
$this->load->view('template/head');
setlocale(LC_ALL, 'id-ID', 'id_ID');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody 	= $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$ISREAD 	= $this->session->userdata['ISREAD'];
$ISCREATE 	= $this->session->userdata['ISCREATE'];
$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
$sysMnt		= $this->session->userdata['sysMnt'];
$LastMntD	= $this->session->userdata['LastMntD'];

$tgl1 = new DateTime($LastMntD);
$tgl2 = new DateTime();
 
$dif1 = $tgl1->diff($tgl2);
$dif2 = $dif1->days;

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

/*function cut_text($var, $len = 200, $txt_titik = "...") 
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
}*/
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
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Title')$Title = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Sender')$Sender = $LangTransl;
			if($TranslCode == 'Progress')$Progress = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Warning')$Warning = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
			$mntWarn1	= "Layanan '1stWeb Assistance' akan segera berakhir pada tanggal : ";
			$mntWarn2	= "Silahkan hubungi kami agar tetap mendapatkan layanan '1stWeb Assistance'.";
			$mntWarn3	= "Layanan '1stWeb Assistance' sudah berakhir per ";
			$mntWarn4	= "Mengapa saya melihat ini?";
		}
		else
		{
			$mntWarn1	= "Sorry, '1stWeb Assistance' services will be finished on : ";
			$mntWarn2	= "Please contact us to get '1stWeb Assistance' services.";
			$mntWarn3	= "Sorry, we have finished '1stWeb Assistance' services per ";
			$mntWarn4	= "Why did I see this message?";
		}
	?>

	<style>
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }

		p {
		    word-break: break-all;
		    white-space: normal;
		}
	</style>

	<?php
        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <?php echo $h1_title; ?>
			    <small><?php echo $h2_title; ?></small>
			  </h1>
		</section>


	    <section class="content">
		    <div class="box">
				<div class="box-body">
					<?php 
						if($sysMnt == 1 && $tgl1 <= $tgl2)
						{
							?>
					        <div class="alert alert-danger alert-dismissible">
					            <h4><i class="icon fa fa-warning"></i> <?php echo $Warning; ?></h4>
					            <?php echo "$mntWarn3".date('d-M-Y', strtotime($LastMntD)).". $mntWarn2"; ?>
					        </div>
				    		<?php 
						}
						elseif($sysMnt == 1 && $dif2 < 6) 
						{
							?>
					        <div class="alert alert-warning alert-dismissible">
					            <h4><i class="icon fa fa-warning"></i> <?php echo $Warning; ?></h4>
					            <?php echo "$mntWarn1".date('d-M-Y', strtotime($LastMntD)).". $mntWarn2"; ?>
					        </div>
				    		<?php 
						}
					?>
					<div class="search-table-outter">
				      	<table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
					        <thead>
					            <tr style="background:#CCCCCC">
					                <th style="vertical-align:middle; text-align:center" width="15%"><?php echo $Code; ?> </th>
					                <th style="vertical-align:middle; text-align:center" width="15%"><?php echo $Category; ?></th>
					                <th style="vertical-align:middle; text-align:center" width="60%"><?php echo $Description; ?> </th>
					                <th style="vertical-align:middle; text-align:center" width="5%"><?php echo $Status; ?> </th>
					                <th style="vertical-align:middle; text-align:center" width="5%"><?php echo $Progress; ?> </th>
					            </tr>
					        </thead>
				        	<tbody>
								<?php
									$i = 0;
									$j = 0;
									if($countTask > 0)
									{
										foreach($vwTask as $row) :
											$myNewNo 		= ++$i;
											$TASK_CODE		= $row->TASK_CODE;
											$TASK_MENU		= $row->TASK_MENU;
											$TASK_DATE		= $row->TASK_DATE;
											$TASK_TITLE		= $row->TASK_TITLE;
											$TASK_MENU		= $row->TASK_MENU;
											$TASK_MENUNM	= $row->TASK_MENUNM;
											if($TASK_MENUNM == '')
												$TASK_MENUNM= 'none';
											$TASK_AUTHOR	= $row->TASK_AUTHOR;
											$TASK_CONTENT	= '';
											$TASK_REQUESTER	= $row->TASK_REQUESTER;
											$TASK_STAT		= $row->TASK_STAT;
											
											$REQUESTER_NAME	= '';
											$First_Name		= '';
											$Last_Name		= '';
											$sqlEmp 		= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$TASK_REQUESTER'";
											$resEmp			= $this->db->query($sqlEmp)->result();
											foreach($resEmp as $rowEmp) :
												$First_Name = $rowEmp->First_Name;
												$Last_Name	= $rowEmp->Last_Name;
											endforeach;
											$REQUESTER_NAME	= "$First_Name $Last_Name";

											$imgempfNmX 	= "username.jpg";
											$sqlIMGCrt		= "SELECT imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$TASK_REQUESTER'";
											$resIMGCrt 		= $this->db->query($sqlIMGCrt)->result();
											foreach($resIMGCrt as $rowGIMGCrt) :
												$imgempfNmX = $rowGIMGCrt->imgemp_filenameX;
											endforeach;
											
											$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/'.$TASK_REQUESTER.'/'.$imgempfNmX);
											if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$TASK_REQUESTER))
											{
												$imgMng		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
											}
											
											$TASKD_CONTENT	= '';
											/*$sqlC		= "tbl_task_request_detail WHERE TASKD_EMPID2 LIKE '%$DefEmp_ID%' AND TASKD_PARENT = '$TASK_CODE'
																 AND TASKD_RSTAT = 1";*/
											$sqlC		= "tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE' AND TASKD_EMPID2 = '$TASK_AUTHOR'
																 AND TASKD_RSTAT = 1";
											$resC		= $this->db->count_all($sqlC); 		// NOT YET READ BY AUTHOR

											$sqlC1		= "tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE' AND TASKD_EMPID2 = '$TASK_REQUESTER'
																 AND TASKD_RSTAT = 1";
											$resC1		= $this->db->count_all($sqlC1); 	// NOT YET READ BY REQUESTER
											
											$sqlV		= "SELECT TASKD_CONTENT FROM tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE' ORDER BY TASKD_CREATED ASC LIMIT 1";
											$vwTaskD	= $this->db->query($sqlV)->result();
											foreach($vwTaskD as $rowD) :
												$TASKD_CONTENT	= $rowD->TASKD_CONTENT;
											endforeach;
											
											if($resC > 0)
											{
												$readStat	= "Unread";
												$readCOL	= 'danger';
											}
											else
											{
												$readStat	= "read";
												$readCOL	= 'success';
											}
											
											if($TASK_STAT == 1)
											{
												$isActDesc	= 'New';
												$STATCOL	= 'warning';
											}
											elseif($TASK_STAT == 2)
											{
												$isActDesc	= 'Process';
												$STATCOL	= 'primary';
											}
											else
											{
												$isActDesc	= 'Closed';
												$STATCOL	= 'success';
											}
											
											if($resC1 > 0)
											{
												$STATCOL	= 'danger';
											}
										
											if ($j==1) {
												echo "<tr class=zebra1>";
												$j++;
											} else {
												echo "<tr class=zebra2>";
												$j--;
											}
											//echo $readStat;
											?>
						                            <td>
					                            		<p><strong style='font-size:13px; white-space:nowrap'>
					                            			<?php
																$secUpd			= site_url('c_help/c_t180c2hr/t180c2htread/?id='.$this->url_encryption_helper->encode_url($TASK_CODE));
							                                	echo anchor($secUpd,$TASK_CODE);
															?>
					                            		</strong><br></p>
														<strong><i class='fa fa-calendar margin-r-5'></i> <?php ECHO $Date; ?> </strong>
												  		<div>
													  		<p class='text-muted'>
													  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo strftime('%d %B %Y', strtotime($TASK_DATE)); ?>
													  		</p>
													  	</div>
						                            </td>
						                            <td style="text-align:left">
						                            	<strong style='white-space:nowrap'><i class='fa fa-bell margin-r-5'></i> Modul / Menu </strong>
												  		<div>
													  		<p class='text-muted'>
													  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $TASK_MENUNM; ?>
													  		</p>
													  	</div>
						                            </td>
						                            	<?php //echo $TASK_TITLE; ?>
						                            <td>
						                            	<strong><p><?php echo $TASK_TITLE; ?></p></strong>
						                            	<strong><p><i class='fa fa-tags margin-r-5'></i> <?php echo $Notes; ?></p></strong>
												  		<div style="margin-left: 20px">
													  		<p class='text-muted'>
													  			<?php
													  				$TASKD_CONTENTD	= cut_text ("$TASKD_CONTENT", 150);
						                                			echo $TASKD_CONTENTD; 
													  			?>
													  		</p>
													  		<div class='box-comments' style='background-color: transparent;'>
														  		<div class='box-comment'>
													                <!-- User image -->
													                <img class='img-circle img-sm' src='<?php echo $imgMng; ?>' alt='User Image'>
													                <div class='comment-text'>
													                   	<span class='username'>
													                        <?php echo ucwords($REQUESTER_NAME); ?>
													                    </span>
												                  		<?php echo ucwords($TASK_REQUESTER); ?>
													                </div>
													            </div>
												            </div>
													  	</div>
						                            </td>
						                            <td nowrap style="text-align:center">
							                            <span class="label label-<?php echo $readCOL; ?>" style="font-size:12px">
															<?php 
							                                    echo "&nbsp;&nbsp;$readStat&nbsp;&nbsp;";
							                                 ?>
							                            </span>
						                           	</td>
						                            <td style="text-align:center" nowrap>
							                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
															<?php 
							                                    echo "&nbsp;&nbsp;$isActDesc&nbsp;&nbsp;";
							                                 ?>
							                            </span>
						                            </td>
												</tr>
											<?php 
										endforeach; 
									}
								?>
					        </tbody>
					        <tfoot>
				          	</tfoot>
					   	</table>
				    </div>
				    <br>
					<?php 
						//echo anchor($secAddURL,'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add [ + ]" />');
						if($sysMnt == 1 && ($tgl1 >= $tgl2))
						{
							echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
						}
					?>
				</div>
			</div>
		</section>
	</body>
</html>

<script>
	$(function () 
	{
		$("#example1").DataTable();
			$('#example2').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": false,
			"ordering": false,
			"info": true,
			"autoWidth": false
		});
	});
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