<?php 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody  = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

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
			if($TranslCode == 'Setting')$Setting = $LangTransl;
			if($TranslCode == 'Profile')$Profile = $LangTransl;
		endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
		  	<h1>
		      	<?php echo $Profile; ?>
		      	<small><?php echo $Setting; ?></small>
		    </h1>
		</section>

		<section class="content">
			<div class="box">
				<div class="box-body">
				    <table id="example1" class="table table-bordered table-striped">
				        <thead>
				        <tr>
				            <th width="5%" nowrap>No</th>
				            <th width="10%" nowrap>Employee ID</th>
				            <th width="18%">Name </th>
				            <th width="45%">Email</th>
				            <th width="22%">Location</th>
				        </tr>
				        </thead>
				        <tbody>
						<?php 
				        $noUrut = 0;
						$j = 0;
				        if($recordcount >0)
				        {
				            foreach($viewEmployee as $row) : 
								$noUrut			= $noUrut + 1;
								$Emp_ID 		= $row->Emp_ID;
								$First_Name 	= $row->First_Name;
								$Last_Name 		= $row->Last_Name;
								$Email 			= $row->Email;
								$Emp_Location 	= $row->Emp_Location;
								if($Emp_Location == 1)
								{
									$Emp_LocationD	= "Kantor Pusat";
								}
								else
								{
									$Emp_LocationD	= "Proyek";
								}
								
								$secUpd		= site_url('c_setting/c_profile/viewEmployee/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
									
								if ($j==1) {
									echo "<tr class=zebra1>";
									$j++;
								} else {
									echo "<tr class=zebra2>";
									$j--;
								}
								?> 
									<td style="text-align:center"> <?php echo $noUrut; ?>.</td>
									<td><?php echo anchor($secUpd,$Emp_ID);?></td>
									<td><?php echo "$First_Name $Last_Name"; ?></td>
									<td><?php echo "$Email"; ?></td>
									<td><?php echo "$Emp_LocationD"; ?></td>
								</tr>
								<?php
							endforeach; 
				        }
						$secAddURL = site_url('c_setting/c_currency/add/?id='.$this->url_encryption_helper->encode_url($appName));
				        ?>
				        </tbody>
					</table>
				    </div>
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
			"ordering": true,
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