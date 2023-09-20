<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 Februari 2017
 * File Name	= material_request_form_inbox.php
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

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
	
$SPPNUM				= $default['SPPNUM'];
$DocNumber			= $default['SPPNUM'];
$SPPCODE			= $default['SPPCODE'];
$TRXDATE			= $default['TRXDATE'];
$TRXDATE			= date('m/d/Y',strtotime($TRXDATE));
$PRJCODE			= $default['PRJCODE'];
$TRXOPEN			= $default['TRXOPEN'];
$TRXUSER			= $default['TRXUSER'];
$APPROVE			= $default['APPROVE'];
$APPRUSR			= $default['APPRUSR'];
$JOBCODE			= $default['JOBCODE'];
$PRJNAME			= $default['PRJNAME'];
$SPPNOTE			= $default['SPPNOTE'];
$SPPSTAT			= $default['SPPSTAT'];
$REVMEMO			= $default['REVMEMO'];
$Patt_Year			= $default['Patt_Year'];
$Patt_Month			= $default['Patt_Month'];
$Patt_Date			= $default['Patt_Date'];
$Patt_Number		= $default['Patt_Number'];
$lastPatternNumb1	= $default['Patt_Number'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
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
    <small>material request approval</small>  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Update Data</h3>
        </div>
          <form name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return validateInData();">
                <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                <input type="Hidden" name="rowCount" id="rowCount" value="0">
              <table width="100%" border="0" style="size:auto">
                  <tr>
                      <td width="13%" align="left" class="style1" nowrap>Material Req. Number</td>
                      <td width="1%" align="left" class="style1">:</td>
                      <td width="30%" align="left" class="style1"> <?php echo $DocNumber; ?>
                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo set_value('PRJCODE', isset($default['PRJCODE']) ? $default['PRJCODE'] : $PRJCODE); ?>" />
                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="SPPNUM" id="SPPNUM" size="30" value="<?php echo set_value('SPPNUM', isset($default['SPPNUM']) ? $default['SPPNUM'] : $DocNumber); ?>" />
                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="Patt_Year" id="Patt_Year" size="30" value="<?php echo set_value('Patt_Year', isset($default['Patt_Year']) ? $default['Patt_Year'] : $Patt_Year); ?>" />
                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="SPPSTAT" id="SPPSTAT" size="30" value="<?php echo set_value('SPPSTAT', isset($default['SPPSTAT']) ? $default['SPPSTAT'] : $SPPSTAT); ?>" />
                      <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="APPROVE" id="APPROVE" size="30" value="<?php echo set_value('APPROVE', isset($default['APPROVE']) ? $default['APPROVE'] : $SPPSTAT); ?>" /></td>
                      <td width="15%" align="left" class="style1">Date</td>
                        <td width="1%" align="left" class="style1">:</td>
                      <td width="40%" align="left" class="style1">
                          <div class="input-group date">
                            <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="TRXDATE" class="form-control pull-left" id="datepicker" value="<?php echo $TRXDATE; ?>" style="width:150px"></div>
                      <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="lastPatternNumb" name="lastPatternNumb" size="20" value="<?php echo $lastPatternNumb1; ?>" /></td> <!-- DOCNUMBER AND TRXDATE -->
                  </tr>
                  <tr>
                      <td align="left" class="style1">SPP Code</td>
                      <td align="left" class="style1">:</td>
                      <td align="left" class="style1"><input type="text" class="form-control" style="min-width:110px; max-width:100px; text-align:left" id="SPPCODE" name="SPPCODE" size="5" value="<?php echo $SPPCODE; ?>" /></td>
                      <td align="left" class="style1">Project</td>
                      <td align="left" class="style1">:</td>
                      <td align="left" class="style1"><select name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
                          <option value="none">--- None ---</option>
                          <?php echo $i = 0;
                            if($recordcountProject > 0)
                            {
                                foreach($viewProject as $row) :
                                    $PRJCODE1 	= $row->PRJCODE;
                                    $PRJNAME 	= $row->PRJNAME;
                                    ?>
                                  <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE - $PRJNAME"; ?></option>
                                  <?php
                                endforeach;
                            }
                            else
                            {
                                ?>
                                  <option value="none">--- No Unit Found ---</option>
                              <?php
                            }
                            ?>
                        </select>
                    <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" /></td>  <!-- SPPCODE AND PRJCODE -->
                  </tr>
                  <tr>
                      <td align="left" class="style1" style="vertical-align:top">SPP Code</td>
                      <td align="left" class="style1" style="vertical-align:top">:</td>
                      <td align="left" class="style1">
                      	<textarea name="SPPNOTE" class="form-control" style="max-width:350px;" id="SPPNOTE" cols="30"><?php echo $SPPNOTE; ?></textarea>
                      </td>
                      <td align="left" class="style1" id="memoName" <?php if(($APPROVE == 3  || $APPROVE == 1) && $REVMEMO == '') { ?> style="display:none" <?php } ?> valign="top">Alert Revise</td>
                      <td align="left" class="style1" id="memoName1" <?php if(($APPROVE == 3  || $APPROVE == 1) && $REVMEMO == '') { ?> style="display:none" <?php } ?> >:</td>
                      <td align="left" class="style1" id="memoBox" <?php if(($APPROVE == 3  || $APPROVE == 1) && $REVMEMO == '') { ?> style="display:none" <?php } ?>>
                      <textarea name="REVMEMO" class="form-control" style="max-width:350px;" id="REVMEMO" cols="30"><?php echo $REVMEMO; ?></textarea>
                  </tr>
                  <tr>
                      <td colspan="3" align="left" valign="middle" class="style1"><hr></td>
                      <td align="left" class="style1">Choose  Status</td>
                      <td align="left" class="style1">:</td>
                      <td align="left" class="style1">
                      	<input type="hidden" name="APPROVE" id="APPROVE" value="<?php echo $APPROVE; ?>">
                        <input type="hidden" name="isAPPROVE" id="isAPPROVE" value="<?php echo $APPROVE; ?>">
                        <label>
							<?php /*?><input type="radio" name="APPROVE" id="AS1" value="1" <?php if($APPROVE == 1) { ?> checked <?php } ?>onclick="OpenNotes(this);" /> New
                            <input type="radio" name="APPROVE" id="AS2" value="2" <?php if($APPROVE == 2) { ?> checked <?php } ?>onclick="OpenNotes(this);" /> Awaiting<?php */?>
                            <input type="radio" name="APPROVE" id="AS3" value="3" <?php if($APPROVE == 3) { ?> checked <?php } ?>onclick="OpenNotes(this);" /> Approve
                            <input type="radio" name="APPROVE" id="AS4" value="4" <?php if($APPROVE == 4) { ?> checked <?php } ?>onclick="OpenNotes(this);" /> Revise
                            <input type="radio" name="APPROVE" id="AS5" value="5" <?php if($APPROVE == 5) { ?> checked <?php } ?>onclick="OpenNotes(this);" /> Reject
                        </label>
						<script>
                            function OpenNotes(thisVal1)
                            {
                                thisVal = thisVal1.value;
                                if(thisVal==4)
                                {
                                    document.getElementById('isAPPROVE').value = thisVal;
                                    alert('Please input memo why you Revise this Request ... !');
                                    document.getElementById('memoName').style.display = '';
                                    document.getElementById('memoName1').style.display = '';
                                    document.getElementById('memoBox').style.display = '';
                                    document.getElementById('REVMEMO').focus();
                                }
                                else if(thisVal==5)
                                {
                                    document.getElementById('isAPPROVE').value = thisVal;
                                    alert('Please input memo why you Reject this Request ... !');
                                    document.getElementById('memoName').style.display = '';
                                    document.getElementById('memoName1').style.display = '';
                                    document.getElementById('memoBox').style.display = '';
                                    document.getElementById('REVMEMO').focus();
                                }
                                else
                                {
                                    document.getElementById('isAPPROVE').value = thisVal;
                                    document.getElementById('memoName').style.display = 'none';
                                    document.getElementById('memoName1').style.display = 'none';
                                    document.getElementById('memoBox').style.display = 'none';
                                    document.getElementById('REVMEMO').value = "";
                                }
                            }
                        </script>        	</td> <!-- SPPSTAT -->
                  </tr>
                  <tr <?php if($APPROVE != 4 && $APPROVE != 5) { ?> style="display:none" <?php } ?>>
                      <td align="left" class="style1" style="font-style:italic">&nbsp;</td>
                      <td align="left" class="style1" style="font-style:italic">&nbsp;</td>
                      <td align="left" class="style1" style="font-style:italic">&nbsp;</td>
                      <td align="left" class="style1" style="font-style:italic; background:#CCCCCC">&nbsp;</td>
                    <td align="left" class="style1" style="font-style:italic; background:#CCCCCC">&nbsp;</td>
                    <td align="left" class="style1" style="font-style:italic; background:#CCCCCC">&nbsp;</td> 
                    <!-- SPP MEMO REVISION -->
                  </tr>
                  <tr>
                      <td colspan="6" align="left" class="style1">
                              <table width="100%" border="1" id="tbl" >
                                  <tr style="background:#CCCCCC">
                                      <td width="2%" height="25" rowspan="2" style="text-align:left">&nbsp;</td>
                                      <td width="13%" rowspan="2" style="text-align:center">Item Code</td>
                                      <td width="40%" rowspan="2" style="text-align:center">Item Name</td>
                                      <td colspan="2" style="text-align:center">Budget Qty</td>
                                      <td colspan="2" style="text-align:center">Request Now</td>
                                      <td colspan="2" style="text-align:center">Unit of Material</td>
                                      <td width="10%" rowspan="2" style="text-align:center">Remarks</td>
                                  </tr>
                                  <tr style="background:#CCCCCC">
                                      <td style="text-align:center;">Planning</td>
                                      <td style="text-align:center;">Requested</td>
                                      <td style="text-align:center;" nowrap>Qty 1</td>
                                      <td style="text-align:center;" nowrap>Qty 2</td>
                                      <td style="text-align:center;">Primary</td>
                                      <td style="text-align:center;">Secondary</td>
                                  </tr>
                                  <?php					
                                    if($task == 'edit')
                                    {
                                        $sqlDET		= "SELECT A.SPPCODE, A.CSTCODE, A.SNCODE, A.CSTUNIT, A.SPPVOLM, A.OPVOLM, A.IRVOLM, A.SPPVPRS, A.SPPDESC,
                                                        B.CSTDESC,
                                                        C.Unit_Type_Code, C.UMCODE, C.Unit_Type_Name
                                                        FROM tbl_spp_detail A
                                                        INNER JOIN tbl_cost B ON A.CSTCODE = B.CSTCODE
                                                        INNER JOIN tbl_unittype C ON C.UMCODE = A.CSTUNIT
                                                        WHERE SPPCODE = '$SPPCODE' 
                                                        AND B.PRJCODE = '$PRJCODE'
                                                        ORDER BY A.CSTCODE ASC";
                                        // count data
                                            $resultCount = $this->db->where('SPPCODE', $SPPCODE);
                                            $resultCount = $this->db->count_all_results('tbl_spp_detail');
                                        // End count data
                                        $result = $this->db->query($sqlDET)->result();
                                        $i		= 0;
                                        if($resultCount > 0)
                                        {
                                            foreach($result as $row) :
                                                $currentRow  	= ++$i;
                                                $SPPCODE 		= $row->SPPCODE;
                                                $CSTCODE 		= $row->CSTCODE;
                                                $CSTDESC 		= $row->CSTDESC;
                                                $SNCODE 		= $row->SNCODE;
                                                $CSTUNIT 		= $row->CSTUNIT;
                                                $SPPVOLM 		= $row->SPPVOLM;
                                                $OPVOLM 		= $row->OPVOLM;
                                                $IRVOLM 		= $row->IRVOLM;
                                                $SPPVPRS 		= $row->SPPVPRS;
                                                $SPPDESC 		= $row->SPPDESC;
                                                $Unit_Type_Code	= $row->Unit_Type_Code;
                                                $UMCODE 		= $row->UMCODE;
                                                $Unit_Type_Name	= $row->Unit_Type_Name;
                                                $itemConvertion	= 1;
                                            ?>
                                          <tr>
                                              <td width="2%" height="25" style="text-align:left">
													<?php echo "$currentRow."; ?> <!-- Checkbox -->                                    </td>
                                                <td width="12%" style="text-align:left">
                                                    <?php echo $CSTCODE; ?>
                                                    <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][CSTCODE]" id="data<?php echo $currentRow; ?>CSTCODE" size="10" value="<?php echo $CSTCODE; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" > <!-- Item Code -->                                    </td>
                                                <td width="32%" style="text-align:left">
                                                    <?php echo $CSTDESC; ?> <!-- Item Name -->                                    </td>
                                                <?php
                                                    $sqlgetQty	= "SELECT A.PPMat_Qty, A.PPMat_Qty2, A.request_qty, A.request_qty2
                                                                    FROM tbl_projplan_material A
                                                                    INNER JOIN tbl_cost B ON A.CSTCODE = B.CSTCODE
                                                                    WHERE A.CSTCODE = '$CSTCODE' AND A.PRJCODE = '$PRJCODE'";
                                                    $resultgetQty = $this->db->query($sqlgetQty)->result();
                                                    foreach($resultgetQty as $nRow) :
                                                        $PPMat_Qty 		= $nRow->PPMat_Qty;
                                                        $PPMat_Qty2 	= $nRow->PPMat_Qty2;
                                                        $request_qty 	= $nRow->request_qty;
                                                        $request_qty2	= $nRow->request_qty2;
                                                    endforeach;
                                                ?>
                                                <td width="10%" style="text-align:right" nowrap>
                                                    <?php print number_format($PPMat_Qty, $decFormat); ?> <!-- Item Bdget -->                                    </td>
                                                <td width="8%" style="text-align:right" nowrap>
                                                    <?php print number_format($request_qty, $decFormat); ?> <!-- Item Requested FOR INFORMATION ONLY -->                                    			</td>
                                                <td width="8%" style="text-align:right" nowrap>
                                                    <?php print number_format($SPPVOLM, $decFormat); ?>
                                                    <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][SPPVOLM]" id="data<?php echo $currentRow; ?>SPPVOLM" size="10" value="<?php echo $SPPVOLM; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" > <!-- Item Request Now -- SPPVOLM -->                                   	</td>
                                                <td width="8%" style="text-align:right" nowrap>
                                                    <?php print number_format($SPPVOLM, $decFormat); ?> <!-- tem Request Now 2 FOR INFORMATION ONLY -->                                    			</td>
                                                <td width="4%" style="text-align:center" nowrap>
                                                    <?php echo $Unit_Type_Name; ?> <!-- Item Unit Type -- CSTUNIT -->                                    </td>
                                                <td width="6%" style="text-align:center" nowrap>
                                                    <?php print $Unit_Type_Name; ?> <!-- Item Unit Type 2 FOR INFORMATION ONLY -->                                   	</td>
                                                <td width="10%" style="text-align:left">
                                                    <?php print $SPPDESC; ?> <!-- Remarks -- SPPDESC -->                                    </td>
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
                              </table></td>
                  </tr>
                  <tr>
                    <td colspan="6" align="left" class="style1">&nbsp;</td>
                  </tr>
                  <tr>
                      <td colspan="6" align="left" class="style1">
                          <?php
							if($APPRUSR < 3)
							//if($APPRUSR == 3 || $APPRUSR == 4 || $APPRUSR == 5)
							/*{
								?>
									<input type="button" class="button_css" name="btnSubmt" id="btnSubmt" value="<?php if($task=='add')echo 'save'; else echo 'update';?>" onClick="submitForm(3);" />
								<?php 
							}
							else*/
							{
								?>
									<input type="button" class="btn btn-primary" name="btnSubmt" id="btnSubmt" value="<?php if($task=='add')echo 'save mn'; else echo 'update';?>" onClick="submitForm(2);" />&nbsp;
								<?php 				
							}
						?>
						
						<?php 
							if ( ! empty($link))
							{
								foreach($link as $links)
								{
									echo $links;
								}
							}
						?>       	</td>
                  </tr>
                  <tr>
                      <td colspan="6" align="left" class="style1">&nbsp;</td>
                  </tr> 
              </table>
          </form>
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
	var selectedRows = 0;
	function check_all(chk) 
	{
		var totRow = document.getElementById('totalrow').value;
		if(chk.checked == true)
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = true;
			}
		}
		else
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = false;
			}
		}
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
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function submitForm(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		//var venCode 	= document.getElementById('Vend_Code').value;
		var APPROVE		= document.getElementById('APPROVE').value;
		var isAPPROVE	= document.getElementById('isAPPROVE').value;
		
		if(APPROVE >= 3)
		{
			alert('Can not be updated. Beacuse the request has been approved ... !');
			return false;
		}
		
		if(isAPPROVE == 1)
		{
			alert("Please check one of Approval Status");
			document.getElementById('AS3').checked = true;
			document.getElementById('isAPPROVE').value = 3;
			return false;
		}
		else if(isAPPROVE == 4)
		{
			var REVMEMO	= document.getElementById('REVMEMO').value;
			if(REVMEMO == '')
			{
				alert('Please input memo why you Revise this Request ... !');
				document.getElementById('REVMEMO').focus();
				return false;
			}
		}
		else if(isAPPROVE == 5)
		{
			var REVMEMO	= document.getElementById('REVMEMO').value;
			if(REVMEMO == '')
			{
				alert('Please input memo why you Reject this Request ... !');
				document.getElementById('REVMEMO').focus();
				return false;
			}
		}
		
		if(totrow == 0)
		{
			alert('You canot approve Request which have not detail.');
			return false;		
		}
		
		document.frm.submit();
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>