<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 April 2017
 * File Name	= inb_asset_maintenance_form.php
 * Location		= -
*/
?>
<?php
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

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

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$currentRow = 0;

$AM_CODE 		= $default['AM_CODE'];
$AM_AS_CODE 	= $default['AM_AS_CODE'];
$AM_PRJCODE 	= $default['AM_PRJCODE'];
$PRJCODE 		= $default['AM_PRJCODE'];
$AM_DATE 		= $default['AM_DATE'];
$AM_DESC 		= $default['AM_DESC'];
$AM_STARTD		= $default['AM_STARTD'];
$AM_STARTD		= date('Y-m-d',strtotime($AM_STARTD));
$AM_ENDD 		= $default['AM_ENDD'];
$AM_ENDD		= date('Y-m-d',strtotime($AM_ENDD));
$AM_STARTT		= $default['AM_STARTT'];
$AM_STARTT		= date('H:i',strtotime($AM_STARTT));
$AM_ENDT 		= $default['AM_ENDT'];
$AM_ENDT		= date('H:i',strtotime($AM_ENDT));
$AM_STAT 		= $default['AM_STAT'];
$Patt_Number	= $default['Patt_Number'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
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
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/iCheck/flat/blue.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/morris/morris.css') ?>" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker-bs3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/ilmudetil.css') ?>">
    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/highcharts.js') ?>" type="text/javascript"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');
	
	$ISREAD 	= $this->session->userdata['ISREAD'];
	$ISCREATE 	= $this->session->userdata['ISCREATE'];
	$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
	$ISDWONL 	= $this->session->userdata['ISDWONL'];
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small><?php echo $h3_title; ?></small>  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">	
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">               
              		<div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                    	<input type="hidden" name="AM_PRJCODE" id="AM_PRJCODE" value="<?php echo $AM_PRJCODE; ?>" />
           				<input type="hidden" name="rowCount" id="rowCount" value="0">
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Usage Code</label>
                          	<div class="col-sm-10">
                            	<?php echo $AM_CODE; ?>
                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
                                <input type="hidden" name="AM_CODE" id="AM_CODE" value="<?php echo $AM_CODE; ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Asset Code</label>
                          	<div class="col-sm-10">
                            	<input type="hidden" name="AS_CODE" id="AS_CODE" value="<?php echo $AM_AS_CODE; ?>" >
                            	<select name="AS_CODE1" id="AS_CODE1" class="form-control" style="max-width:250px" disabled>
                                <option value="0">--- None ---</option>
                                <?php
                                    $own_Code 	= '';
                                    $CountAS 	= $this->db->count_all('tbl_project');
                                    $sqlAS 		= "SELECT AS_CODE, AS_NAME FROM tbl_asset_list ORDER BY AS_NAME";
                                    $resultAS 	= $this->db->query($sqlAS)->result();
                                    if($CountAS > 0)
                                    {
                                        foreach($resultAS as $rowAS) :
                                            $AS_CODE = $rowAS->AS_CODE;
                                            $AS_NAME = $rowAS->AS_NAME;
                                            ?>
                                                <option value="<?php echo $AS_CODE; ?>" <?php if($AS_CODE == $AM_AS_CODE) { ?>selected <?php } ?>>
                                                    <?php echo $AS_NAME; ?>
                                                </option>
                                            <?php
                                         endforeach;
                                     }
                                ?>
                            </select>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Date</label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="AM_DATE" class="form-control pull-left" id="datepicker2" value="<?php echo $AM_DATE; ?>" style="width:150px" disabled>
                                </div>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Description</label>
                          	<div class="col-sm-10">
                                <textarea class="form-control" name="AM_DESC"  id="AM_DESC" style="max-width:350px;height:70px" disabled><?php echo $AM_DESC; ?></textarea>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Start Date</label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="AM_STARTD" class="form-control pull-left" id="datepicker2" value="<?php echo $AM_STARTD; ?>" style="width:150px" disabled>
                                </div>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Time</label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:80px" name="AM_STARTT" id="AM_STARTT" value="<?php echo $AM_STARTT; ?>" disabled >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">End Date</label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="AM_ENDD" class="form-control pull-left" id="datepicker2" value="<?php echo $AM_ENDD; ?>" style="width:150px" disabled>
                                </div>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">Time</label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:80px" name="AM_ENDT" id="AM_ENDT"value="<?php echo $AM_ENDT; ?>" disabled >
                          	</div>
                        </div>
                        <!--
                        	APPROVE STATUS
                            1 - New
                            2 - Confirm
                            3 - Approve
                        -->
                        <div class="form-group" >
                          	<label for="inputName" class="col-sm-2 control-label">Status</label>
                          	<div class="col-sm-10">
                                <select name="AM_STAT" id="AM_STAT" class="form-control" style="max-width:100px">
                                	<option value="0" <?php if($AM_STAT == 0) { ?> selected <?php } ?>>None</option>
                                	<option value="3" <?php if($AM_STAT == 3) { ?> selected <?php } ?>>Approve</option>
                                </select>
                            </div>
                        </div>
						<?php
							$url_AddItem	= site_url('c_asset/c_asset_usage/popupallitem/?id='.$this->url_encryption_helper->encode_url($AM_PRJCODE));
                        ?>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                          	<div class="col-sm-10">&nbsp;</div>
                        </div>
                        <div class="form-group">
                          	<div class="col-sm-10">
                                <div class="box-tools pull-right">
                                    <table width="100%" border="1" id="tbl">
                                        <tr style="background:#CCCCCC">
                                            <th width="4%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
                                          <th width="10%" rowspan="2" style="text-align:center">Item Code</th>
                                          <th width="40%" rowspan="2" style="text-align:center">Item Name</th>
                                          <th colspan="2" style="text-align:center">Usage</th>
                                            <th width="29%" rowspan="2" style="text-align:center">Remarks</th>
                                      </tr>
                                        <tr style="background:#CCCCCC">
                                            <th style="text-align:center;">Qty</th>
                                            <th style="text-align:center;">Unit</th>
                                        </tr>
                                        <?php					
                                        if($task == 'edit')
                                        {
                                            $sqlDET		= "SELECT A.AM_CODE, A.AM_PRJCODE, A.ITM_CODE, A.ITM_QTY, A.ITM_UNIT, A.NOTES,
															B.ITM_DESC,
                                                            C.Unit_Type_Code, C.UMCODE, C.Unit_Type_Name
                                                            FROM tbl_asset_maintendet A
                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                                INNER JOIN tbl_unittype C ON C.UMCODE = A.ITM_UNIT
                                                            WHERE A.AM_CODE = '$AM_CODE' 
                                                            AND B.PRJCODE = '$PRJCODE'";
                                            // count data
                                                $resultCount = $this->db->where('AM_CODE', $AM_CODE);
                                                $resultCount = $this->db->count_all_results('tbl_asset_maintendet');
                                            // End count data
                                            $result = $this->db->query($sqlDET)->result();
                                            $i		= 0;
                                            $j		= 0;
                                            if($resultCount > 0)
                                            {
                                                foreach($result as $row) :
                                                    $currentRow  	= ++$i;
                                                    $AM_CODE 		= $row->AM_CODE;
                                                    $ITM_CODE 		= $row->ITM_CODE;
                                                    $ITM_DESC 		= $row->ITM_DESC;
                                                    $AM_PRJCODE		= $row->AM_PRJCODE;
                                                    $ITM_QTY 		= $row->ITM_QTY;
                                                    $ITM_UNIT 		= $row->ITM_UNIT;
                                                    $Unit_Type_Code	= $row->Unit_Type_Code;
                                                    $UMCODE 		= $row->UMCODE;
                                                    $Unit_Type_Name	= $row->Unit_Type_Name;
                                                    $NOTES			= $row->NOTES;
                                                    $itemConvertion	= 1;
                                        
                                                    if ($j==1) {
                                                        echo "<tr class=zebra1>";
                                                        $j++;
                                                    } else {
                                                        echo "<tr class=zebra2>";
                                                        $j--;
                                                    }
                                                    ?> 
                                                    <td width="4%" height="25" style="text-align:left">
                                                     	<?php echo "$currentRow."; ?>
                                               	    	<input type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)" style="display:none">
                                                        <input type="Checkbox" style="display:none" id="chk<?php echo $currentRow; ?>" name="chk<?php echo $currentRow; ?>" value=""><input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                                        <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>AM_CODE" name="data[<?php echo $currentRow; ?>][AM_CODE]" value="<?php echo $AM_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>AM_PRJCODE" name="data[<?php echo $currentRow; ?>][AM_PRJCODE]" value="<?php echo $AM_PRJCODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                    	<!-- Checkbox -->
                                                    </td>
                                               	  	<td width="10%" style="text-align:left" nowrap>
                                                      	<?php echo $ITM_CODE; ?>
                                           				<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                        <!-- Item Code -->
													</td>
                                               	  	<td width="40%" style="text-align:left">
                                                      	<?php echo $ITM_DESC; ?>
														<!-- Item Name --></td>
                                               	  	<td width="14%" style="text-align:right" nowrap>
                                            			<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTYx<?php echo $currentRow; ?>" id="ITM_QTYx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY, $decFormat); ?>" onBlur="changeValue(this, <?php echo $currentRow; ?>)" >
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="ITM_QTY<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY; ?>" >
                                                        <input type="hidden" style="text-align:right" name="ITM_QTY_MIN<?php echo $currentRow; ?>" id="ITM_QTY_MIN<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY; ?>" >
                                                        <!-- Item Qty -->
													</td>
                                               	  <td width="3%" style="text-align:center" nowrap>
                                                      	<?php echo $ITM_UNIT; ?>
                                       					<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="ITM_UNIT<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_UNIT; ?>" >
                                                      	<!-- Item Unit -->
													</td>
                                               	  <td width="29%" style="text-align:center">
                                           				<input type="text" name="data[<?php echo $currentRow; ?>][NOTES]" id="data<?php echo $currentRow; ?>NOTES" value="<?php echo $NOTES; ?>" class="form-control" style="max-width:450px;text-align:left">
                                                        <!-- Notes -->
													</td>
													  <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                              </tr>
                                          <?php
                                                endforeach;
                                            }
                                        }
                                        if($task == 'add')
                                        {
                                            ?>
                                              <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                          <?php
                                        }
                                        ?>
                                    </table> 
                                </div>
                            </div>                      
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            	<input type="submit" class="btn btn-primary" name="submit" id="submit" value="<?php if($task=='add')echo 'save'; else echo 'update';?>" align="left" />&nbsp;
                                <?php
									if ( ! empty($link))
									{
										foreach($link as $links)
										{
											echo $links;
										}
									}
								?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
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
	var decFormat		= 2;
	
	function checkInp()
	{		
		AM_STAT	= document.getElementById('AM_STAT').value;
		if(AM_STAT == 0)
		{
			alert("Please select approval status.");
			document.getElementById('AM_STAT').focus();
			return false;			
		}
	}
	
	function doDecimalFormat(angka) {
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

	function decimalin(ini)
	{	
		var i, j;
		var bil2 = deletecommaperiod(ini.value,'both')
		var bil3 = ""
		j = 0
		for (i=bil2.length-1;i>=0;i--)
		{
			j = j + 1;
			if (j == 3)
			{
				bil3 = "." + bil3
			}
			else if ((j >= 6) && ((j % 3) == 0))
			{
				bil3 = "," + bil3
			}
			bil3 = bil2.charAt(i) + "" + bil3
		}
		ini.value = bil3
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>