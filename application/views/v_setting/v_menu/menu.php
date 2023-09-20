<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 29 Maret 2017
 * File Name	= menu.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$LangID		= $this->session->userdata['LangID'];
$appBody 	= $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
$Emp_DeptCode		= $this->session->userdata['Emp_DeptCode'];
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
		$this->load->view('template/topbar');
		$this->load->view('template/sidebar');
		
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
			if($TranslCode == 'MenuNameIND')$MenuNameIND = $LangTransl;
			if($TranslCode == 'MenuNameENG')$MenuNameENG = $LangTransl;
			if($TranslCode == 'MenuParent')$MenuParent = $LangTransl;
			if($TranslCode == 'MenuLevel')$MenuLevel = $LangTransl;
			if($TranslCode == 'Sort')$Sort = $LangTransl;

		endforeach;
	?>
	
	<body class="<?php echo $appBody; ?>">
		<div class="content-wrapper">
		    <section class="content-header">
		        <h1>
		        <?php echo $h2_title; ?>
		        <small>setting</small>
		        </h1>
		        <?php /*?><ol class="breadcrumb">
		        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		        <li><a href="#">Tables</a></li>
		        <li class="active">Data tables</li>
		        </ol><?php */?>
		    </section>
    		
    		<section class="content">
			    <div class="box">
			        <!-- /.box-header -->
			        <div class="box-body">
			            <table id="example1" class="table table-bordered table-striped" width="100%">
			            	<thead>
			                <tr>
			                    <th width="7%"><?php echo $Code ?>  </th>
			                  	<th width="33%"><?php echo $MenuNameIND ?></th>
			                  	<th width="33%"><?php echo $MenuNameENG ?> </th>
			               	  <th width="47%"><?php echo $MenuParent ?> </th>
			               	  <th width="7%" nowrap><?php echo $MenuLevel ?> </th>
			               	  <th width="6%"><?php echo $Sort ?> </th>

			                </tr>
			                </thead>
			                <tbody>
			                <?php
			                $i = 0;
			                $j = 0;
							foreach($viewmenu as $row) : 
								$menu_code 		= $row->menu_code;
								$isNeedPattern	= $row->isNeedPattern;
								$isNeedStepAppr	= $row->isNeedStepAppr;
								$no_urut		= $row->no_urut;
								$isHeader		= $row->isHeader;
								$level_menu		= $row->level_menu;
								$parent_code	= $row->parent_code;
								$menu_code1 	= "-";
								$menu_name1 	= "-";
									if($LangID == 1)
									{
										$sqlmn1 = "SELECT menu_code, menu_name_IND AS menu_name, menu_name_ENG
													FROM tbl_menu
													WHERE menu_code = '$parent_code'";
									}
									else
									{
										$sqlmn1 = "SELECT menu_code, menu_name_ENG AS menu_name, menu_name_ENG
													FROM tbl_menu
													WHERE menu_code = '$parent_code'";
									}
									$resultmn1 = $this->db->query($sqlmn1)->result();
									foreach($resultmn1 as $rowmn1) :
										$menu_code1 = $rowmn1->menu_code;
										$menu_name1 = $rowmn1->menu_name;
									endforeach;
								$link_alias		= $row->link_alias;
								$link_alias_sd	= $row->link_alias_sd;
								$menu_name_IND	= $row->menu_name_IND;
								$menu_name_ENG	= $row->menu_name_ENG;
								$menu_user		= $row->menu_user;
								$fa_icon		= $row->fa_icon;
								$isActive		= $row->isActive;
								if($isActive == 1)
								{
									$isActiveD	= "Active";
								}
								else
								{
									$isActiveD	= "In Active";
								}
								$menu_codeX		= substr($menu_code, 2, 3);
								$menu_codeY		= (int)$menu_codeX;
								//$sqlUPDATE = "UPDATE tbl_menu SET Pattern_No = $menu_codeY WHERE menu_code = '$menu_code'";
								//$this->db->query($sqlUPDATE);
								$secUpd		= site_url('c_setting/c_menu/update/?id='.$this->url_encryption_helper->encode_url($menu_code));
								
								if ($j==1) {
									echo "<tr class=zebra1>";
									$j++;
								} else {
									echo "<tr class=zebra2>";
									$j--;
								}
								?> 
			                        <td nowrap> <?php print anchor("$secUpd",$menu_code,array('class' => 'update')).' '; ?> </td>
			                        <td> <?php print $menu_name_IND; ?> </td>
			                        <td> <?php print $menu_name_ENG; ?> </td>
			                        <td> <?php print "$menu_code1 - $menu_name1"; ?> </td>
			                        <td> <?php print $level_menu; ?> </td>
			                        <td> <?php print $no_urut; ?> </td>
			                    </tr>
			                    <?php
							endforeach; 
							$secAddURL = site_url('c_setting/c_menu/add/?id='.$this->url_encryption_helper->encode_url($appName));
			                ?>
			                </tbody> 
			                <tr>
			                    <td colspan="6">
			                    	<?php echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>&nbsp;');?></td>
						    </tr>                              
			            </table>
			      </div>
			      	<!-- /.box -->
			    </div>
			</section>
		</div>
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
	$this->load->view('template/aside');

	$this->load->view('template/js_data');

	$this->load->view('template/foot');
?>