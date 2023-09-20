<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 29 Maret 2017
 * File Name	= menu_form.php
 * Location		= -
*/
?>
<?php
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$LangID		= $this->session->userdata['LangID'];
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

if($task == "add")
{	
	//$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	//$myCount = $this->db->count_all('tbl_menu');
	$sqlMAXMN 	= "SELECT MAX(Pattern_No) AS MAXSTEP FROM tbl_menu";
	$resMAXMN 	= $this->db->query($sqlMAXMN)->result();
	foreach($resMAXMN as $rowMAXMN) :
		$MAXMENU = $rowMAXMN->MAXSTEP;
	endforeach;
	$MAXMENU	= $MAXMENU + 1;
	
	//$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$result = $this->db->get('tbl_menu')->result();
	
	// karena untuk nomor employee tidak ada ketentuan berdasarkan tahun, bulan dan tanggal, maka lgsung menhgitung jumlah row.
	/*if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->Patt_Number;
		endforeach;
	}	else	{		$myMax = 1;	}*/
	
	//$myMax = $myCount + 1;
	$myMax = $MAXMENU;
		
	$lastPatternNumb = $myMax;
	$len = strlen($lastPatternNumb);
	
	$Pattern_Length	= 3;
	
	if($Pattern_Length==2)
	{
		if($len==1) $nol="0";
	}
	elseif($Pattern_Length==3)
	{
		if($len==1) $nol="00";else if($len==2) $nol="0";else $nol="";
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
	$DocNumber 		= "MN$lastPatternNumb";
	
	$menu_code 		= $DocNumber;		
	$isNeedPattern 	= 0;
	$isNeedStepAppr	= 0;
	$no_urut		= 1;
	$isHeader		= '';
	$level_menu		= 2;
	$parent_code	= '';
	$link_alias		= '';
	$link_alias_sd	= '';
	$menu_name_IND	= '';
	$menu_name_ENG	= '';
	$menu_user		= '';
	$fa_icon		= '';
	$isActive		= 1;
	$Pattern_No		= $myMax;
}	
else
{
	$menu_code 		= $default['menu_code'];
	$menu_name_IND	= $default['menu_name_IND'];
	$menu_name_ENG	= $default['menu_name_ENG'];
	$isNeedPattern 	= $default['isNeedPattern'];
	$isNeedStepAppr = $default['isNeedStepAppr'];
	$no_urut		= $default['no_urut'];
	$isHeader		= $default['isHeader'];
	$level_menu		= $default['level_menu'];
	$parent_code	= $default['parent_code'];
	$link_alias		= $default['link_alias'];
	$link_alias_sd	= $default['link_alias_sd'];
	$isActive		= $default['isActive'];
	$Pattern_No		= $default['Pattern_No'];
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
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'MenuNameIND')$MenuNameIND = $LangTransl;
			if($TranslCode == 'MenuNameENG')$MenuNameENG = $LangTransl;
			if($TranslCode == 'MenuParent')$MenuParentTR = $LangTransl;
			if($TranslCode == 'MenuLevel')$MenuLevel = $LangTransl;
			if($TranslCode == 'OrderNo')$OrderNo = $LangTransl;
			if($TranslCode == 'LinkAlias')$LinkAlias = $LangTransl;
			if($TranslCode == 'NeedPattern')$NeedPattern = $LangTransl;
			if($TranslCode == 'NeedStepApprove')$NeedStepApprove = $LangTransl;
			if($TranslCode == 'UseHeader')$UseHeader = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Yes')$Yes = $LangTransl;
			if($TranslCode == 'No')$No = $LangTransl;

		endforeach;
	?>
	
	<body class="<?php echo $appBody; ?>">
		<div class="content-wrapper">
			<section class="content-header">
				<h1>
				    <?php echo $h2_title; ?>
				    <small><?php echo $h3_title; ?></small>
				  </h1>
				  <?php /*?><ol class="breadcrumb">
				    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				    <li><a href="#">Tables</a></li>
				    <li class="active">Data tables</li>
				  </ol><?php */?>
			</section>

			<section class="content">
			    <div class="box box-primary">
			        <div class="box-header with-border" style="display:none">
			            <h3 class="box-title">Input Menu<span class="col-sm-10">
			            </span></h3>                
			      		<div class="box-tools pull-right">
			                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			                </button>
			                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			            </div>
			        </div>
			  		<div class="box-body chart-responsive">
			            <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkData()">
			            	<input type="hidden" name="Pattern_No" id="Pattern_No" value="<?php echo $Pattern_No; ?>" >
			              	<div class="form-group">
			                	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?>  </label>
			                	<div class="col-sm-10">
			                    	<?php
										if($task == "add")
										{
											?>
			                                	<input type="hidden" name="menu_code" id="menu_code" value="<?php echo $menu_code; ?>" class="form-control" style="max-width:250px">
			                                	<input type="text" name="menu_codeX" id="menu_codeX" value="<?php echo $menu_code; ?>" class="form-control" style="max-width:100px" disabled>
											<?php
										}
										else
										{
											?>
			   	    							<input type="text" name="menu_codeX" id="menu_codeX" value="<?php echo $menu_code; ?>" class="form-control" style="max-width:100px" disabled>
			                                  	<input type="hidden" name="menu_code" id="menu_code" value="<?php echo $menu_code; ?>" class="form-control" style="max-width:250px">
											<?php
										}
									?>
			                    </div>
			           	  	</div>
			                <div class="form-group">
			                  	<label class="col-sm-2 control-label"><?php echo $MenuNameIND ?> </label>
			                  	<div class="col-sm-10">
			                    	<input type="text" name="menu_name_IND" id="menu_name_IND" value="<?php echo $menu_name_IND; ?>" class="form-control" style="max-width:400px">
			                    </div>
			                </div>
			                <div class="form-group">
			                  	<label class="col-sm-2 control-label"><?php echo $MenuNameENG ?> </label>
			                  	<div class="col-sm-10">
			                    	<input type="text" name="menu_name_ENG" id="menu_name_ENG" value="<?php echo $menu_name_ENG; ?>" class="form-control" style="max-width:400px">
			                    </div>
			                </div>
			                <div class="form-group">
			                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $MenuParentTR ?> </label>
			                    <div class="col-sm-10">
			                        <select name="parent_code" id="parent_code" class="form-control select2" style="max-width:400px" >
			                        	<option value="" > ---- None ----</option>
										<?php
			                            if($MenuParentC>0)
			                            {
			                                foreach($MenuParent as $rowMP1) :
												$menu_code1		= $rowMP1->menu_code;
												if($LangID == 'IND')
												{
													$menu_name1	= $rowMP1->menu_name_IND;
												}
												else
												{
													$menu_name1	= $rowMP1->menu_name_ENG;
												}
												$level_menu1	= $rowMP1->level_menu;
												$parent_code1	= $rowMP1->parent_code;
												$level_menuD1	= "";
												
												$sqlC1		= "tbl_menu WHERE parent_code = '$menu_code1'";
												$ressqlC1 	= $this->db->count_all($sqlC1);
											?>
			                                    <option value="<?php echo $menu_code1;?>" <?php if($menu_code1 == $parent_code) { ?>selected<?php } if($ressqlC1>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$level_menuD1$menu_name1 - $menu_code1";?></option>
			                            	<?php
												if($ressqlC1 > 0)
												{
													if($LangID == 'IND')
													{
														$sqlMP2		= "SELECT menu_code, menu_name_IND AS menu_name, level_menu, parent_code
																		FROM tbl_menu
																		WHERE parent_code = '$menu_code1' ORDER BY no_urut";
													}
													else
													{
														$sqlMP2		= "SELECT menu_code, menu_name_ENG AS menu_name, level_menu, parent_code
																		FROM tbl_menu
																		WHERE parent_code = '$menu_code1' ORDER BY no_urut";
													}
													$resMP2 	= $this->db->query($sqlMP2)->result();
													foreach($resMP2 as $rowMP2) :
														$menu_code2		= $rowMP2->menu_code;
														$menu_name2		= $rowMP2->menu_name;
														$level_menu2	= $rowMP2->level_menu;
														$parent_code2	= $rowMP2->parent_code;
														$level_menuD2	= "&nbsp;&nbsp;&nbsp;";
													
														$sqlC2			= "tbl_menu WHERE parent_code = '$menu_code2'";
														$ressqlC2 		= $this->db->count_all($sqlC2);
														?>
			                                    		<option value="<?php echo $menu_code2;?>" <?php if($menu_code2 == $parent_code) { ?>selected<?php } if($ressqlC2>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$level_menuD2$menu_name2 - $menu_code2";?></option>
			                                            <?php
															if($ressqlC2 > 0)
															{
																if($LangID == 'IND')
																{
																	$sqlMP3		= "SELECT menu_code, menu_name_IND AS menu_name, level_menu, parent_code
																					FROM tbl_menu
																					WHERE parent_code = '$menu_code2' ORDER BY no_urut";
																}
																else
																{
																	$sqlMP3		= "SELECT menu_code, menu_name_ENG AS menu_name, level_menu, parent_code
																					FROM tbl_menu
																					WHERE parent_code = '$menu_code2' ORDER BY no_urut";
																}
																$resMP3 	= $this->db->query($sqlMP3)->result();
																foreach($resMP3 as $rowMP3) :
																	$menu_code3		= $rowMP3->menu_code;
																	$menu_name3		= $rowMP3->menu_name;
																	$level_menu3	= $rowMP3->level_menu;
																	$parent_code3	= $rowMP3->parent_code;
																	$level_menuD3	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																
																	$sqlC3			= "tbl_menu WHERE parent_code = '$menu_code3'";
																	$ressqlC3 		= $this->db->count_all($sqlC3);
																?>
																	<option value="<?php echo $menu_code3;?>" <?php if($menu_code3 == $parent_code) { ?>selected<?php } if($ressqlC3>0) {?> style="font-weight:bold"<?php } ?>><?php echo "$level_menuD3$menu_name3 - $menu_code3";?></option>
																<?php
																endforeach;
															}
													endforeach;
												}
											endforeach;
			                            }
			                            ?>
									</select>
			                    </div>
			                </div>
			                <div class="form-group">
			                  	<label class="col-sm-2 control-label"><?php echo $MenuLevel ?> </label>
			                  	<div class="col-sm-10">
			                    	<select name="level_menu" id="level_menu" class="form-control select2" style="max-width:70px" >
										<?php
			                                for($j=1; $j<=3; $j++)
											{							
											?>
			                        			<option value="<?php echo $j;?>" <?php if($j == $level_menu) { ?>selected<?php } ?>><?php echo $j;?></option>
			                            	<?php 
											}
			                            ?>
			                      </select>
			                    </div>
			                </div>
			                <div class="form-group">
			                  	<label class="col-sm-2 control-label"><?php echo $OrderNo ?> </label>
			                  	<div class="col-sm-10">
			                    	<select name="no_urut" id="no_urut" class="form-control select2" style="max-width:70px" >
										<?php
			                                for($i=1; $i<=100; $i++)
											{							
											?>
			                        			<option value="<?php echo $i;?>" <?php if($i == $no_urut) { ?>selected<?php } ?>><?php echo $i;?></option>
			                            	<?php 
											}
			                            ?>
			                      </select>
			                    </div>
			                </div>
			                <div class="form-group">
			                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $LinkAlias ?> </label>
			                    <div class="col-sm-10">
			                    	<input type="text" name="link_alias" id="link_alias" value="<?php echo $link_alias; ?>" class="form-control" style="max-width:400px">
			                    </div>
			                </div>
			                <div class="form-group">
			                  	<label class="col-sm-2 control-label"><?php echo $NeedPattern ?> </label>
			                  	<div class="col-sm-10">
			                        <input type="radio" name="isNeedPattern" id="isNeedPattern1" value="1" class="minimal" <?php if($isNeedPattern == 1) { ?> checked <?php } ?>>
			                        &nbsp;&nbsp;<?php echo $Yes ?> &nbsp;&nbsp;&nbsp;&nbsp;
			                        <input type="radio" name="isNeedPattern" id="isNeedPattern2" value="0" class="minimal" <?php if($isNeedPattern == 0) { ?> checked <?php } ?>>
			                        &nbsp;&nbsp;<?php echo $No ?>
			                    </div>
			                </div>
			                <div class="form-group">
			                  	<label class="col-sm-2 control-label"><?php echo $NeedStepApprove ?> </label>
			                  	<div class="col-sm-10">
			                        <input type="radio" name="isNeedStepAppr" id="isNeedStepAppr1" value="1" class="minimal" <?php if($isNeedStepAppr == 1) { ?> checked <?php } ?>>
			                        &nbsp;&nbsp;<?php echo $Yes ?>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <input type="radio" name="isNeedStepAppr" id="isNeedStepAppr2" value="0" class="minimal" <?php if($isNeedStepAppr == 0) { ?> checked <?php } ?>>
			                        &nbsp;&nbsp;<?php echo $No ?>
			                    </div>
			                </div>
			                <div class="form-group">
			                  	<label class="col-sm-2 control-label"><?php echo $UseHeader ?> </label>
			                  	<div class="col-sm-10">
			                        <input type="radio" name="isHeader" id="isHeader1" value="1" class="minimal" <?php if($isHeader == 1) { ?> checked <?php } ?>>
			                        &nbsp;&nbsp;<?php echo $Yes ?>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <input type="radio" name="isHeader" id="isHeader2" value="0" class="minimal" <?php if($isHeader == 0) { ?> checked <?php } ?>>
			                        &nbsp;&nbsp;<?php echo $No ?>
			                    </div>
			                </div>
			                <div class="form-group">
			                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo $Status ?> </label>
			                    <div class="col-sm-10">
			                        <input type="radio" name="isActive" id="isActive1" value="1" class="minimal" <?php if($isActive == 1) { ?> checked <?php } ?>>
			                        &nbsp;&nbsp;<?php echo $Yes ?>&nbsp;&nbsp;&nbsp;&nbsp;
			                        <input type="radio" name="isActive" id="isActive2" value="0" class="minimal" <?php if($isActive == 0) { ?> checked <?php } ?>>
			                        &nbsp;&nbsp;<?php echo $No ?>
			                    </div>
			                </div>
			                <div class="form-group">
			                    <div class="col-sm-offset-2 col-sm-10">
			                        <?php
										if($ISCREATE == 1)
										{
											if($task=='add')
											{
												?>
													<button class="btn btn-primary">
													<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
													</button>&nbsp;
												<?php
											}
											else
											{
												?>
													<button class="btn btn-primary" >
													<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
													</button>&nbsp;
												<?php
											}
										}
									
										echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
									?>
			                    </div>
			                </div>
			            </form>
			            <script>
							function checkData()
							{
								menu_code = document.getElementById('menu_code').value;
								POS_NAME = document.getElementById('POS_NAME').value;
								if(menu_code == '')
								{
									alert('Please input Department Code.');
									document.getElementById("menu_code").focus();
									return false;
								}
								if(POS_NAME == '')
								{
									alert('Please input Department Name.');

									document.getElementById("POS_NAME").focus();
									return false;
								}		
							}
						</script>
			        </div>
			    </div>
			</section>
		</div>
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