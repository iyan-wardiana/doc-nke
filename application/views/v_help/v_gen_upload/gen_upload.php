<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 07 November 2017
 * File Name	= gen_upload.php
 * Function		= -
*/

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
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$Emp_DeptCode	= $this->session->userdata['Emp_DeptCode'];
date_default_timezone_set("Asia/Jakarta");
$tYear		= date('Y');
$tMonth		= date('m');
$tDays		= date('d');
$tTime		= date('His');
$UP_Code	= "$tYear$tMonth$tDays-$tTime";
$UP_Type	= 0;
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
    		
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Nomor')$Nomor = $LangTransl;
    		if($TranslCode == 'FileUpload')$FileUpload = $LangTransl;
    		if($TranslCode == 'General')$General = $LangTransl;
    		if($TranslCode == 'DocumentName')$DocumentName = $LangTransl;
    		if($TranslCode == 'Type')$Type = $LangTransl;
    		if($TranslCode == 'Size')$Size = $LangTransl;
    		if($TranslCode == 'Author')$Author = $LangTransl;
    		if($TranslCode == 'Download')$Download = $LangTransl;
    		if($TranslCode == 'Upload')$Upload = $LangTransl;
    		if($TranslCode == 'Save')$Save = $LangTransl;
    		if($TranslCode == 'IsRar')$IsRar = $LangTransl;
    	endforeach;
    ?>
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                <?php echo $FileUpload; ?>
                <small><?php echo $General; ?></small>
              </h1>
            </section>
            <style>
            	.search-table, td, th {
            		border-collapse: collapse;
            	}
            	.search-table-outter { overflow-x: scroll; }
            </style>
    
            <section class="content">
                <div class="box">
                	<div class="row">
                        <div class="col-md-12">
                				<?php
                                    if($ISCREATE == 1)
                                    {
                                        ?>
                                            <div class="box-body chart-responsive">
                                                <form class="form-horizontal" name="frm" id="frm" method="POST" action="<?php echo $form_action; ?>" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?></label>
                                                        <div class="col-sm-10"><input type="text" class="form-control" style="max-width:200px;" name="UP_Code" id="UP_Code" value="<?php echo $UP_Code; ?>" disabled>
                                                        <input type="hidden" class="form-control" style="max-width:130px;" name="UP_Code" id="UP_Code" value="<?php echo $UP_Code; ?>"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputName" class="col-sm-2 control-label"><?php echo $IsRar; ?> ?</label>
                                                        <div class="col-sm-10">
                                                            <select name="UP_Type" id="UP_Type" class="form-control select2" style="max-width:70px">
                                                                <option value="1" <?php if($UP_Type == 1) { ?> selected <?php } ?>>Yes</option>
                                                                <option value="0" <?php if($UP_Type == 0) { ?> selected <?php } ?>>No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Upload; ?></label>
                                                        <div class="col-sm-10">
                                                            <input type="file" name="userfile" id="userfile" class="filestyle" data-buttonName="btn-primary"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button class="btn btn-primary">
                                                            <i class="glyphicon glyphicon-upload"></i>&nbsp;&nbsp;<?php echo $Upload; ?>
                                                            </button>&nbsp;
                                                        </div>
                                                    </div>
                                                </form>
                							</div>
                               			<?php
                					}
                				?>
                    	</div>
                    </div>
                	<?php
                        $EmpID 			= $this->session->userdata('Emp_ID');
                        if($error == 'Error')
                        {
                            echo "Upload error... !!!";
                            echo "<br /><br />";
                        }
                        elseif($error == 'Sukses')
                        {							
                            $insertFileName = "INSERT INTO tgenfileupload(FileUpName, up_submitter, fext, fsize) values ('$fileName', '$EmpID', '$fext', '$fsize')";
                            mysql_query($insertFileName) or die(mysql_error());
                            
                            echo "Upload File $fileName Sukses... !!!";
                            echo "<br /><br />";
                        }
                    ?>		  
                    <div class="box-body">
                        <div class="search-table-outter">
                          <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                              <thead>
                                    <tr>
                                        <th width="4%" style="text-align:center"><?php echo $Nomor; ?></th>
                                        <th width="89%"><?php echo $DocumentName; ?></th>
                                        <th width="3%" nowrap><?php echo $Type; ?></th>
                                        <th width="4%"><?php echo $Size; ?></th>
                                        <th width="4%"><?php echo $Author; ?></th>
                                        <th width="4%"><?php echo $Download; ?></th>
                                    </tr>
                            </thead>
                                <tbody>
                                <?php
                                    $i = 0;
                                    $j = 0;
                                    if($countGUp >0)
                                    {
                                        foreach($vwGUp as $row) :
                                            $myNewNo 	= ++$i;
                                            $Code		= $row->UP_Code;						
                                            $Filename	= $row->Filename;
                                            $fext		= $row->fext;
                                            $fsize		= $row->fsize;
                                            $UP_Emp		= $row->UP_Emp;
                                            
                                            $firstName	= $row->First_Name;
                                            $MiddleName	= $row->Middle_Name;
                                            $lastName	= $row->Last_Name; 
                                            
                                            $compName	= "$firstName $MiddleName $lastName";
                                            
                                            $linkDLPDF = '<a href="'.base_url().'uploads/genupload/'.$Filename.'" title="Download file" class="btn btn-success btn-xs" id="isdl"><i class="fa fa-download"></i></a>';
                                        ?>
                                            <tr>
                                                <td>&nbsp;<?php print $myNewNo; ?>. </td>
                                                <td>&nbsp;<?php print $Filename; ?> </td>
                                                <td nowrap style="text-align:center">&nbsp;*.<?php print strtoupper($fext); ?> </td>
                                                <td nowrap style="text-align:right"><?php print $fsize; ?> KB</td>
                                                <td nowrap>&nbsp;<?php print $compName; ?></td>
                                                <td style="text-align:center">
                                                    <?php
                										if($ISDWONL == 1)
                										{
                                                    		echo $linkDLPDF;
                										}
                										else
                										{
                											echo "-";
                										}
                									 ?>
                                                </td>
                                            </tr>
                                        <?php 
                                        endforeach;
                                    }
                                ?>
                                </tbody>
                          </table>
                      </div>
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
<script>
function downloadFile(fileName)
{
	if($src ==  "xyyx")
	{
		$pth    =   file_get_contents(base_url()."path/to/the/file.pdf");
		$nme    =   "sample_file.pdf";
		force_download($nme, $pth);     
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
    $this->load->view('template/aside');

    $this->load->view('template/js_data');

    $this->load->view('template/foot');
?>