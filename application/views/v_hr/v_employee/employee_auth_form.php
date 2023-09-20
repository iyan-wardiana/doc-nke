<?php
/* 
    * Author		= Dian Hermanto
    * Create Date	= 21 Februari 2017
    * File Name	= employee_auth_form.php
    * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];
$PRJSCATEG  = $this->session->userdata['PRJSCATEG'];    // 1. Kontraktor 2. Manufacture

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
	if($LangID == 'IND')
		$MenuName	= 'menu_name_IND';
	else
		$MenuName	= 'menu_name_ENG';

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
            <?php echo $h2_title; ?>
            <small>setting</small>
            </h1>
        </section>

        <script>
        	function validateInCurr()
        	{
        		CURR_ID		= document.getElementById('CURR_ID').value;
        		CURR_CODE	= document.getElementById('CURR_CODE').value;
        		if(CURR_ID == "")
        		{
        			alert('Currency ID can not be empty.');
        			document.getElementById('CURR_ID').focus();
        			return false;
        		}
        		if(CURR_CODE == "")
        		{
        			alert('Currency Code can not be empty.');
        			document.getElementById('CURR_CODE').focus();
        			return false;
        		}
        	}
        </script>
        <?php
        	$urlUpdAuth			= site_url('c_hr/c_employee/c_employee/employee_authorization_process/?id='.$this->url_encryption_helper->encode_url($appName));
        ?>
        <section class="content">
            <div class="box box-primary"><br>
        		<form action="<?php echo $urlUpdAuth; ?>" onSubmit="confirmDelete();" method=POST>
                    <table width="100%">
                        <tr>
                            <td width="16%">
                            &nbsp;&nbsp;&nbsp;<input type="hidden" id="Emp_ID1" name="Emp_ID1" value="<?php print $Emp_ID; ?>" width="10" size="10" class="textbox">
                                &nbsp;Function Name        </td>
                            <td width="1%">:</td>
                            <td width="83%">Function Authorization</td>
                        </tr>
                        <tr>
                            <td width="16%">
                                &nbsp;&nbsp;&nbsp;&nbsp;Username Name        </td>
                            <td width="1%">:</td>
                        <?php
                                $sqlgEmp = "SELECT A.Position_ID, A.First_Name, A.Middle_Name, A.Last_Name, B.POS_NAME
                                            FROM tbl_employee A
                                            LEFT JOIN tbl_position B ON A.POS_CODE = B.POS_CODE
                                            WHERE A.emp_id = '$Emp_ID'";
                                $ressqlgEmp = $this->db->query($sqlgEmp)->result();
                                foreach($ressqlgEmp as $rowEmp) :
                                    $First_Name = $rowEmp->First_Name;
                                    $Middle_Name = $rowEmp->Middle_Name;
                                    $Last_Name = $rowEmp->Last_Name;
                                    $POS_NAME = $rowEmp->POS_NAME;
                                endforeach;
                            ?>
                            <td width="83%"><?php echo "$First_Name $Middle_Name $Last_Name";; ?></td>
                        </tr>
                        <tr>
                            <td width="16%">
                                &nbsp;&nbsp;&nbsp;&nbsp;Position Name        </td>
                            <td width="1%">:</td>
                          <td width="83%" style="font-weight:bold"><?php echo $POS_NAME; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div class="box box-primary">           
                                    <div class="box-header with-border"> 
                                        <h3 class="box-title">Menu Authorization</h3>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                        	<td colspan="3">
                        		<table width="100%">
        							<?php 
        								$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
                                        // TYPE (0,1,2,5,22,50)
                                        $addQuery       = " AND menu_type IN (0,1) AND isActive = 1 AND menu_user = 1";           // CONTRACTOR
                                        if($Emp_ID == 'D15040004221')
                                            $addQuery  = " AND menu_type IN (0,1,50)";

                                        if($PRJSCATEG == 2)                             
                                        {
                                            $addQuery   = " AND menu_type IN (0,2,22) AND isActive = 1 AND menu_user = 1";        // MANUFACTURE
                                            if($Emp_ID == 'D15040004221')
                                                $addQuery  = " AND menu_type IN (0,2,22,50)";
                                        }
                                        elseif($PRJSCATEG == 3)                             
                                        {
                                            $addQuery   = " AND menu_type IN (0,2) AND isActive = 1 AND menu_user = 1";           // TRADING
                                            if($Emp_ID == 'D15040004221')
                                                $addQuery  = " AND menu_type IN (0,2,50)";
                                        }

                                        $i  = 0;
                                        $i1 = 0;
                                        $i2 = 0;
                                        $i3 = 0;
                                        foreach($viewallmenu as $row) : 
                                        {
                                            $i = $i + 1;
                                            $menu_code1 = $row->menu_code;
                                            // To Get Checked
                                            $menu_idg1a = $row->menu_id;
                                            $isActive1 = $row->isActive;									
        									$isActive1D		= '';
        									if($isActive1 == 0)
        									{
        										$isActive1D	= 'O';
        									}
        									
                                            $menu_idg1 = 0;
                                            $menu_codeg1 = '';
                                            $sqlg1      = "SELECT isChkDetail, emp_id, menu_code
                                                            FROM tusermenu
                                                            WHERE emp_id = '$Emp_ID' AND menu_code = '$menu_code1'";
                                            $resultg1   = $this->db->query($sqlg1)->result();
                                            foreach($resultg1 as $rowg1) :
                                                $menu_idg1 = $rowg1->isChkDetail;
                                                $menu_codeg1 = $rowg1->menu_code;
                                            endforeach;

                                            $sqlUPMN   = "UPDATE tbl_menu SET order_id = $i WHERE menu_code = '$menu_code1'";
                                            //___$this->db->query($sqlUPMN);
                                            ?>
                                            <tr>
                                                <td width="35%" style="text-align:left; font-weight:bold">
                                                &nbsp;&nbsp;&nbsp;<input name="chkDetail<?php echo $i; ?>" id="chkDetail<?php echo $i; ?>" type="checkbox" value="<?php echo $row->menu_id; ?>" onClick="checkVal(<?php echo $i; ?>)" <?php if($menu_idg1 == $menu_idg1a) { ?> checked <?php } ?>/>
                                                &nbsp;&nbsp;&nbsp;<?php print "$menu_code1 -"; ?>
                                                <input type="hidden" name="data[<?php echo $i; ?>][Emp_ID]" id="data<?php echo $i; ?>Emp_ID" size="6" value="<?php print $Emp_ID; ?>" />
                                                <input type="hidden" name="data[<?php echo $i; ?>][isChkDetail]" id="data<?php echo $i; ?>isChkDetail" size="6" value="<?php print $menu_idg1; ?>" />
                                                <input type="hidden" name="data[<?php echo $i; ?>][menu_code]" id="data<?php echo $i; ?>menu_code" size="6" value="<?php print $menu_code1; ?>" />
                                                <?php
                                                	if ($LangID=='IND')										
        										 		print $row->menu_name_IND; 
        											else
        												print $row->menu_name_ENG; 
        													
        										?>
                                                <small class="label bg-red"><?php echo $isActive1D; ?></small>
                                                </td>
                                                
                                                <td width="65%" style="text-align:left; font-weight:bold">
                                                  <input type="checkbox" name="data[<?php echo $i; ?>][ISREAD]" id="data<?php echo $i; ?>ISREAD" value="1" style="display:none">
                                                    &nbsp;&nbsp;&nbsp;
                                                  <input type="checkbox" name="data[<?php echo $i; ?>][ISCREATE]" id="data<?php echo $i; ?>ISCREATE" value="1" style="display:none">
                                                    &nbsp;&nbsp;&nbsp;
                                                  <input type="checkbox" name="data[<?php echo $i; ?>][ISAPPROVE]" id="data<?php echo $i; ?>ISAPPROVE" value="1" style="display:none">
                                                    &nbsp;&nbsp;&nbsp;
                                                    <input type="checkbox" name="data[<?php echo $i; ?>][ISDELETE]" id="data<?php echo $i; ?>ISDELETE" value="1" style="display:none">&nbsp;&nbsp;&nbsp;</td>
                                            </tr>
                                            <?php
                                            // Menu level 2
                                            $sql2 = "SELECT menu_id, menu_code, isHeader, level_menu, link_alias, menu_name_IND, menu_name_ENG, menu_user, isActive
                                                FROM tbl_menu
                                                WHERE parent_code = '$menu_code1' $addQuery
                                                ORDER BY no_urut";
                                            $result2 = $this->db->query($sql2)->result();
                                            // count data
                                                $resultCount2 = $this->db->where('parent_code', $menu_code1);
                                                $resultCount2 = $this->db->count_all_results('tbl_menu');
                                            // End count data
                                            if($resultCount2 > 0)
                                            {
                                                foreach($result2 as $row2) :
                                                {
                                                    $i2 = $i + 1;
                                                    $i  = $i2;
                                                    $menu_id2 = $row2->menu_id;
                                                    $menu_code2 = $row2->menu_code;											
        											$isActive2 = $row2->isActive;									
        											$isActive2D		= '';
        											if($isActive2 == 0)
        											{
        												$isActive2D	= 'O';
        											}
        											
        											if ($LangID=='IND')
        	                                           	$menu_name2 = $row2->menu_name_IND;
        											else
        												$menu_name2 = $row2->menu_name_ENG;
        												
                                                    // To Get Checked
                                                    $menu_idg2a = $menu_id2;
                                                    $menu_idg2 	= 0;
                                                    $menu_codeg2= '';
        											$ISREAD2	= 0;
        											$ISCREATE2 	= 0;
        											$ISAPPROVE2	= 0;
        											$ISDELETE2 	= 0;
        											$ISDWONL2 	= 0;
                                                    $sqlg2 = "SELECT isChkDetail, emp_id, menu_code, ISREAD, ISCREATE, ISAPPROVE,
        															ISDELETE, ISDWONL
                                                                FROM tusermenu
                                                                WHERE emp_id = '$Emp_ID' AND menu_code = '$menu_code2'";
                                                    $resultg2 = $this->db->query($sqlg2)->result();
                                                    foreach($resultg2 as $rowg2) :
                                                        $menu_idg2 	= $rowg2->isChkDetail;
                                                        $menu_codeg2= $rowg2->menu_code;
                                                        $ISREAD2	= $rowg2->ISREAD;
                                                        $ISCREATE2 	= $rowg2->ISCREATE;
                                                        $ISAPPROVE2	= $rowg2->ISAPPROVE;
                                                        $ISDELETE2 	= $rowg2->ISDELETE;
                                                        $ISDWONL2 	= $rowg2->ISDWONL;
                                                    endforeach;

                                                    $sqlUPMN   = "UPDATE tbl_menu SET order_id = $i2 WHERE menu_code = '$menu_code2'";
                                                    //___$this->db->query($sqlUPMN);
                                                ?>
                                                <tr>
                                                    <td style="text-align:left">
                                                    &nbsp;&nbsp;&nbsp;<?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; ?>
                                                     <input name="chkDetail<?php echo $i2; ?>" id="chkDetail<?php echo $i2; ?>" type="checkbox" value="<?php echo $menu_id2; ?>" onClick="checkVal(<?php echo $i2; ?>)" <?php if($menu_idg2 == $menu_idg2a) { ?> checked <?php } ?> />
                                                    &nbsp;&nbsp;&nbsp;<?php print "$menu_code2 -"; ?>
                                                    <input type="hidden" name="data[<?php echo $i2; ?>][Emp_ID]" id="data<?php echo $i2; ?>Emp_ID" size="6" value="<?php print $Emp_ID; ?>" />
                                                    <input type="hidden" name="data[<?php echo $i2; ?>][isChkDetail]" id="data<?php echo $i2; ?>isChkDetail" size="6" value="<?php print $menu_idg2; ?>" />
                                                    <input type="hidden" name="data[<?php echo $i2; ?>][menu_code]" id="data<?php echo $i2; ?>menu_code" size="6" value="<?php print $menu_code2; ?>" />
                                                    <?php print $menu_name2; ?>
                                                    <small class="label bg-red"><?php echo $isActive2D; ?></small>
                                                    </td>
                                                    <td style="text-align:left">
                                                        <input type="checkbox" name="data[<?php echo $i2; ?>][ISDWONL]" id="data<?php echo $i2; ?>ISDWONL" value="1" <?php if($ISDWONL2 == 1) { ?> checked <?php } ?>>
                                                        Download&nbsp;&nbsp;&nbsp;
                                                        <input type="checkbox" name="data[<?php echo $i2; ?>][ISREAD]" id="data<?php echo $i2; ?>ISREAD" value="1" <?php if($ISREAD2 == 1) { ?> checked <?php } ?>>
                                                        Read&nbsp;&nbsp;&nbsp;
                                                        <input type="checkbox" name="data[<?php echo $i2; ?>][ISCREATE]" id="data<?php echo $i2; ?>ISCREATE" value="1" <?php if($ISCREATE2 == 1) { ?> checked <?php } ?>>
                                                        Create&nbsp;&nbsp;&nbsp;
                                                        <input type="checkbox" name="data[<?php echo $i2; ?>][ISAPPROVE]" id="data<?php echo $i2; ?>ISAPPROVE" value="1" <?php if($ISAPPROVE2 == 1) { ?> checked <?php } ?>>
                                                        Approve&nbsp;&nbsp;&nbsp;
                                                        <input type="checkbox" name="data[<?php echo $i2; ?>][ISDELETE]" id="data<?php echo $i2; ?>ISDELETE" value="1" <?php if($ISDELETE2 == 1) { ?> checked <?php } ?>>
                                                        Delete
                                                    </td>
                                                </tr>
                                                <?php
                                                    // Menu level 3
                                                    $sql3 = "SELECT menu_id, menu_code, isHeader, level_menu, link_alias, menu_name_IND, menu_name_ENG, menu_user, isActive
                                                        FROM tbl_menu
                                                        WHERE parent_code = '$menu_code2' $addQuery
                                                        ORDER BY no_urut";
                                                    $result3 = $this->db->query($sql3)->result();
                                                    // count data
                                                        $resultCount3 = $this->db->where('parent_code', $menu_code2);
                                                        $resultCount3 = $this->db->count_all_results('tbl_menu');
                                                    // End count data
                                                    if($resultCount3 > 0)
                                                    {
                                                        foreach($result3 as $row3) :
                                                        {
                                                            $i3 = $i2 + 1;
                                                            $i2 = $i3;
                                                            $i = $i3;
                                                            $menu_id3 = $row3->menu_id;
                                                            $menu_code3 = $row3->menu_code;
                                                            $isActive3 = $row3->isActive;								
        													$isActive3D		= '';
        													if($isActive3 == 0)
        													{
        														$isActive3D	= 'O';
        													}
        													
        													if ($LangID=='IND')
                                                            	$menu_name3 = $row3->menu_name_IND;
        													else
        														$menu_name3 = $row3->menu_name_ENG;
        													
                                                            // To Get Checked
                                                            $menu_idg3a = $menu_id3;
                                                            $menu_idg3 	= 0;
                                                            $menu_codeg3= '';
        													$ISREAD3	= 0;
        													$ISCREATE3 	= 0;
        													$ISAPPROVE3	= 0;
        													$ISDELETE3 	= 0;
        													$ISDWONL3	= 0;
                                                            $sqlg3 = "SELECT isChkDetail, emp_id, menu_code, ISREAD, ISCREATE, ISAPPROVE,
        																	ISDELETE, ISDWONL
                                                                        FROM tusermenu
                                                                        WHERE emp_id = '$Emp_ID' AND menu_code = '$menu_code3'";
                                                            $resultg3 = $this->db->query($sqlg3)->result();
                                                            foreach($resultg3 as $rowg3) :
                                                                $menu_idg3 	= $rowg3->isChkDetail;
                                                                $menu_codeg3= $rowg3->menu_code;
        														$ISREAD3	= $rowg3->ISREAD;
        														$ISCREATE3 	= $rowg3->ISCREATE;
        														$ISAPPROVE3	= $rowg3->ISAPPROVE;
        														$ISDELETE3 	= $rowg3->ISDELETE;
        														$ISDWONL3 	= $rowg3->ISDWONL;
                                                            endforeach;
                                                    
                                                            $sqlUPMN   = "UPDATE tbl_menu SET order_id = $i3 WHERE menu_code = '$menu_code3'";
                                                            //___$this->db->query($sqlUPMN);
                                                        ?>
                                                            <tr>
                                                                <td style="text-align:left">
                                                                &nbsp;&nbsp;&nbsp;<?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; ?>
                                                                <input name="chkDetail<?php echo $i3; ?>" id="chkDetail<?php echo $i3; ?>" type="checkbox" value="<?php echo $menu_id3; ?>" onClick="checkVal(<?php echo $i3; ?>)" <?php if($menu_idg3 == $menu_idg3a) { ?> checked <?php } ?> />
                                                                &nbsp;&nbsp;&nbsp;<?php print "$menu_code3 -"; ?>
                                                                <input type="hidden" name="data[<?php echo $i3; ?>][Emp_ID]" id="data<?php echo $i3; ?>Emp_ID" size="6" value="<?php print $Emp_ID; ?>" />
                                                                <input type="hidden" name="data[<?php echo $i3; ?>][isChkDetail]" id="data<?php echo $i3; ?>isChkDetail" size="6" value="<?php print $menu_idg3; ?>" />
                                                                <input type="hidden" name="data[<?php echo $i3; ?>][menu_code]" id="data<?php echo $i3; ?>menu_code" size="6" value="<?php print $menu_code3; ?>" />
                                                                <?php print $menu_name3; ?>
                                                                <small class="label bg-red"><?php echo $isActive3D; ?></small>
                                                                </td>
                                                                <td style="text-align:left">
                                                        			<input type="checkbox" name="data[<?php echo $i3; ?>][ISDWONL]" id="data<?php echo $i3; ?>ISDWONL" value="1" <?php if($ISDWONL3 == 1) { ?> checked <?php } ?>> Download&nbsp;&nbsp;&nbsp;
                                                                  <input type="checkbox" name="data[<?php echo $i3; ?>][ISREAD]" id="data<?php echo $i3; ?>ISREAD" value="1" <?php if($ISREAD3 == 1) { ?> checked <?php } ?>>
                                                                    Read&nbsp;&nbsp;&nbsp;
                                                                  <input type="checkbox" name="data[<?php echo $i3; ?>][ISCREATE]" id="data<?php echo $i3; ?>ISCREATE" value="1" <?php if($ISCREATE3 == 1) { ?> checked <?php } ?>>
                                                                    Create&nbsp;&nbsp;&nbsp;
                                                                  <input type="checkbox" name="data[<?php echo $i3; ?>][ISAPPROVE]" id="data<?php echo $i3; ?>ISAPPROVE" value="1" <?php if($ISAPPROVE3 == 1) { ?> checked <?php } ?>>
                                                                    Approve&nbsp;&nbsp;&nbsp;
                                                                    <input type="checkbox" name="data[<?php echo $i3; ?>][ISDELETE]" id="data<?php echo $i3; ?>ISDELETE" value="1" <?php if($ISDELETE3 == 1) { ?> checked <?php } ?>>
                                                                    Delete</td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        endforeach;
                                                    }
                                                }
                                                endforeach;
                                            }
                                        } 
                                        endforeach; 
                                    ?> 
        						</table>
                            </td>
                        </tr>
                    </table>
                
                    <table width="100%" border="0">
                        <tr height="20">
                            <td colspan="3"><hr /></td>
                        </tr>
                        <tr height="20">
                            <td colspan="3">
                                <input type="submit" name="btnDelete" id="btnDelete" class="btn btn-primary" value="Update" />&nbsp;
                                <?php 
                                    if ( ! empty($link))
                                    {
                                        foreach($link as $links)
                                        {
                                            echo $links;
                                        }
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr height="20">
                            <td colspan="3"><hr /></td>
                        </tr>
                    </table>
                    <?php
                        $DefID      = $this->session->userdata['Emp_ID'];
                        $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        if($DefID == 'D15040004221')
                            echo "<font size='1'><i>$act_lnk</i></font>";
                    ?>
                </form>
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
    $('#datepicker1').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
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
	function checkVal(thisVal)
	{
		var isCheck = document.getElementById('chkDetail'+thisVal).checked;
		var isCheckVal = document.getElementById('chkDetail'+thisVal).value;
		if(isCheck == true)
		{
			document.getElementById('data'+thisVal+'isChkDetail').value = isCheckVal;
		}
		else
		{
			document.getElementById('data'+thisVal+'isChkDetail').value = 0;
		}
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