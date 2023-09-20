<?php 
	$this->load->view('template/head');

	$appName 	= $this->session->userdata('appName');
	$appBody    = $this->session->userdata['appBody'];

	//$this->load->view('template/topbar');
	//$this->load->view('template/sidebar');

	$this->db->select('Display_Rows,decFormat');
	$resGlobal = $this->db->get('tglobalsetting')->result();
	foreach($resGlobal as $row) :
		$Display_Rows = $row->Display_Rows;
		$decFormat = $row->decFormat;
	endforeach;
	if($decFormat == 0)
		$decFormat		= 2;
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
    		if($TranslCode == 'PatternCode')$PatternCode = $LangTransl;
    		if($TranslCode == 'PatternName')$PatternName = $LangTransl;
    		if($TranslCode == 'YearAktive')$YearAktive = $LangTransl;
    		if($TranslCode == 'MonthAktive')$MonthAktive = $LangTransl;
    		if($TranslCode == 'DateAktive')$DateAktive = $LangTransl;
    		if($TranslCode == 'PatternLength')$PatternLength = $LangTransl;

    	endforeach;

    	$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
          	<h1>
            <?php echo $h2_title; ?>
            <small>setting</small>
          	</h1>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-body">
		    		<div class="search-table-outter">
	                  	<table id="example1" class="table table-bordered table-striped">
		                    <thead>
		                        <tr>
		                            <th width="10%"><?php echo $PatternCode ?> </th>
		                            <th width="37%"><?php echo $PatternName ?> </th>
		                            <th width="15%"><?php echo $YearAktive ?> </th>
		                            <th width="12%"><?php echo $MonthAktive ?> </th>
		                            <th width="11%"><?php echo $DateAktive ?> </th>
		                            <th width="15%"><?php echo $PatternLength ?> </th>
		                        </tr>
		                    </thead>
		                    <tbody>
		            		<?php 
		            			$i = 0;
		            			$j = 0;
		            			if($recordcount >0)
		            			{
		            				foreach($viewdocpattern as $row) :
		            					$Pattern_Code	= $row->Pattern_Code;
		            					$secUpd			= site_url('c_setting/c_docpattern/update/?id='.$this->url_encryption_helper->encode_url($Pattern_Code));
		            					
		            					if ($j==1) {
		            						echo "<tr class=zebra1>";
		            						$j++;
		            					} else {
		            						echo "<tr class=zebra2>";
		            						$j--;
		            					}
		            						?>
		            							<td><?php echo anchor($secUpd,$Pattern_Code);?></td>
		            							<td> <?php print $row->Pattern_Name ?> </td>
		            							<td> <?php print $row->Pattern_YearAktive; ?> </td>
		            							<td> <?php print $row->Pattern_MonthAktive; ?> </td>
		            							<td> <?php print $row->Pattern_DateAktive; ?> </td>
		            							<td> <?php print $row->Pattern_Length; ?> </td>
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
    				<?php
    					echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;&nbsp;');
    				?>

                </div>
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