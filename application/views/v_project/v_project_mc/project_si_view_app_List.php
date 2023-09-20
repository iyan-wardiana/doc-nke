<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 16 Februari 2017
 * File Name	= project_si_view_app_List.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

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

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$selDocNumb			= '';
if(isset($_POST['submit']))
{
	$selDocNumb = $_POST['selDocNumb'];
}

$selDocNumbColl	= '';
$totSelect		= 0;
if(isset($_POST['submit1']))
{
	$totSelect 		= $_POST['totSelect'];
	$selDocNumbColl = $_POST['selDocNumbColl'];
	//$dataSessSrc = array('selDocNumbColl' 	=> $selDocNumbColl);
	//$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
	
	$dataSessSrc = array(
				'selSearchproj_Code' 	=> $PRJCODE,
				'selSearchType' 		=> $this->input->post('selSearchType'),
				'selSearchCat' 			=> "$selSearchCat",
				'txtSearch' 			=> "",
				'selDocNumbColl'		=> $selDocNumbColl);
	$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
	$this->session->set_userdata('dtSessSrc2', $dataSessSrc);

	$sesNumbColl     	= $this->session->userdata['dtSessSrc1']['selDocNumbColl'];
	//echo "hahahaa $sesNumbColl";		
}
else
{
	//$sesNumbColl     	= $this->session->userdata['dtSessSrc1']['selDocNumbColl'];
	$sesNumbColl     	= $selDocNumbColl;
	//echo "hahahab $sesNumbColl";
}

$frmSIApp		= site_url('c_project/project_mc/popSIApp/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

$sql01		= "tbl_siheader A
				LEFT JOIN  	tbl_employee B ON A.SI_EMPID = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
				WHERE A.PRJCODE = '$PRJCODE'";
$sql01Count	= $this->db->count_all($sql01);

$selSearchCat	= '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
</head>

	<?php
    
		$LangID 	= $this->session->userdata['LangID'];
	
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Number')$Number = $LangTransl;
			if($TranslCode == 'ManualNumber')$ManualNumber = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'FiledDate')$FiledDate = $LangTransl;
			if($TranslCode == 'ChargeFiled')$ChargeFiled = $LangTransl;
			if($TranslCode == 'ApproveDate')$ApproveDate = $LangTransl;
			if($TranslCode == 'ChargeApproved')$ChargeApproved = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
	
		endforeach;
				
    ?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <?php echo $h2_title; ?>
    <small>material request</small>
  </h1><br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>

<?php /*?><form name="frmSIApp" id="frmSIApp" action="" method=POST>
	<input type="hidden" name="selDocNumbColl" id="selDocNumbColl" value="<?php echo $sesNumbColl; ?>" />
	<input type="hidden" name="totSelect" id="totSelect" value="<?php echo $totSelect; ?>" />
	<input type="hidden" name="selSearchCat" id="selSearchCat" value="<?php echo $selSearchCat; ?>" />
    <input type="submit" class="button_css" name="submit1" id="submit1" value=" search " style="display:none" />
</form><?php */?>
<form name="frmSIApp2" id="frmSIApp2" action="<?php echo $frmSIApp; ?>" method=POST>
	<input type="hidden" name="selDocNumbColl" id="selDocNumbColl" value="<?php echo $selDocNumbColl; ?>" />
	<input type="hidden" name="totSelect" id="totSelect" value="<?php echo $totSelect; ?>" />
	<input type="hidden" name="selSearchCat" id="selSearchCat" value="<?php echo $selSearchCat; ?>" />
    <input type="submit" class="button_css" name="submit2" id="submit2" value=" search " style="display:none" />
</form>
<!-- Main content -->

  <div class="box">
    <!-- /.box-header -->
	<div class="box-body">
<table>
 	<tr>
        <td class="style2" height="">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="2%"><input name="chkAll" id="chkAll" type="checkbox" value="" /></th>
                    <th width="3%">No.</th>
                    <th width="10%" nowrap><?php echo $Code ?> / <?php echo $Number ?>  </th>
                    <th width="21%" nowrap><?php echo $ManualNumber ?> </th>
                    <th width="40%" nowrap><?php echo $Description ?> </th>
                    <th width="4%" nowrap><?php echo $FiledDate ?>  </th>
                    <th width="6%" nowrap><?php echo $ChargeFiled ?> </th>
                    <th width="4%" nowrap><?php echo $ApproveDate ?>  </th>
                    <th width="5%" nowrap><?php echo $ChargeApproved ?> </th>
                    <th width="1%" nowrap><?php echo $Status ?> </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $a 			= 0;
                $j 			= 0;
                $myNewNo1	= 0;
                if($sql01Count >0)
                {                
                    $sql02		= "SELECT A.SI_CODE, A.SI_MANNO, A.SI_DATE, A.SI_ENDDATE, A.SI_APPDATE, C.PRJNAME, A.SI_DESC, A.SI_VALUE, A.SI_APPVAL, A.SI_NOTES, A.SI_STAT
                                    FROM tbl_siheader A
                                        LEFT JOIN  	tbl_employee B ON A.SI_EMPID = B.Emp_ID
                                        INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
                                    WHERE A.PRJCODE = '$PRJCODE' ORDER BY A.SI_MANNO";
                    $sql02Count	= $this->db->query($sql02)->result();
                    foreach($sql02Count as $row) : 
                        $SI_CODE		= $row->SI_CODE;
                        $SI_MANNO		= $row->SI_MANNO;
                        $SI_MANNOD		= $SI_MANNO;
                        if($SI_MANNO == '')
                        {
                            $SI_MANNOD	= 'Not Set';
                        }
                        $SI_DATE		= $row->SI_DATE;
                        $SI_ENDDATE		= $row->SI_ENDDATE;
                        $SI_APPDATE		= $row->SI_APPDATE;
                        $SI_APPDATE		= $row->SI_APPDATE;
                        $PRJCODE		= $PRJCODE;
                        $PRJNAME		= $row->PRJNAME;
                        $SI_DESC		= $row->SI_DESC;
                        $SI_VALUE		= $row->SI_VALUE;
                        $SI_APPVAL		= $row->SI_APPVAL;
                        $SI_NOTES		= $row->SI_NOTES;
                        $SI_STAT		= $row->SI_STAT;
                        
                        if($SI_STAT == 0) $SI_STATDes = "fake";
                        elseif($SI_STAT == 1) $SI_STATDes = "New";
                        elseif($SI_STAT == 2) $SI_STATDes = "Approve";
                        elseif($SI_STAT == 3) $SI_STATDes = "Close";
                        
                        $myNewNo1 = ++$a;
                        
                        $secUpdURLSI	= site_url('c_project/project_mc/updateSI/?id='.$this->url_encryption_helper->encode_url($SI_CODE));
                
                        if ($j==1) {
                            echo "<tr class=zebra1>";
                            $j++;
                        } else {
                            echo "<tr class=zebra2>";
                            $j--;
                        }
                        ?> 
                            <td style="text-align:center" nowrap>
                            <input type="radio" name="chkDetail" id="chkDetail" value="<?php echo $SI_CODE;?>" onClick="getValueNo(this);" <?php if($SI_CODE == $selDocNumb) { ?> checked <?php } ?> style="display:none"/><?php
                                $isCheck	= 0;
                                $SICODEX	= '';
                                if(isset($_POST['submit1']))
                                {						
                                    if($totSelect == 1)
                                    {
                                        $SICODEX 	= $_POST['selDocNumbColl'];
                                        if($SICODEX == $SI_CODE)
                                        {
                                            $isCheck	= 1;
                                        }
                                    }
                                    else
                                    {
                                        $SICODEX 	= explode("|",$selDocNumbColl);
                                        for($i=0; $i<$totSelect; $i++)
                                        {
                                            $SICODER	= $SICODEX[$i];
                                            if($SICODER == $SI_CODE)
                                            {
                                                $isCheck	= 1;
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                        $SICODEX 	= explode("|",$selDocNumbColl);
                                        for($i=0; $i<$totSelect; $i++)
                                        {
                                            $SICODER	= $SICODEX[$i];
                                            if($SICODER == $SI_CODE)
                                            {
                                                $isCheck	= 1;
                                            }
                                        }
                                }
                            ?>
                            <input type="checkbox" name="SICODE" id="SICODE<?php echo $myNewNo1; ?>" value="<?php echo $SI_CODE;?>" onClick="addSICODE(this)" <?php if($isCheck == 1) { ?> checked <?php } ?>/>
                            </td>
                            <td> <?php print $myNewNo1; ?>. </td>
                            <td nowrap> <?php print anchor("$secUpdURLSI",$SI_CODE,array('class' => 'update')).' '; ?> </td>
                            <td> <?php print $SI_MANNOD; ?></td>
                            <td> <?php print $SI_DESC; ?> </td>
                            <td nowrap style="text-align:center;<?php if($SI_DATE == '0000-00-00') { ?> font-style:italic; color:#F00; <?php } ?>">
                              <?php
                                    $date = new DateTime($SI_DATE);
                                    if($SI_DATE == '0000-00-00')
                                    {
                                        echo "Not Set";
                                    }
                                    else
                                    {
                                        echo $date->format('d M Y');
                                    }
                                ?>
                            </td>
                            <td style="text-align:right">
                              <?php
                                    echo number_format($SI_VALUE, $decFormat);
                                ?>
                            </td>
                            <td style="text-align:center;<?php if($SI_APPDATE == '0000-00-00' || $SI_STAT == 1) { ?> font-style:italic; color:#F00; <?php } ?>" nowrap>
                                <?php
                                    $date = new DateTime($SI_APPDATE);
                                    if($SI_APPDATE == '0000-00-00')
                                    {
                                        echo "Not Set";
                                    }
                                    else
                                    {
                                        if($SI_STAT == 1)
                                        {
                                            echo "Not Set";
                                        }
                                        elseif($SI_STAT == 2)
                                        {
                                            echo $date->format('d M Y');
                                        }
                                    }
                                ?>
                            </td>
                            <td style="text-align:right;<?php if($SI_STAT == 1) { ?> font-style:italic; color:#F00; <?php } ?>" nowrap>
                              <?php
                                    echo number_format($SI_APPVAL, $decFormat);
                                ?>
                            </td>
                            <td width="1%" nowrap style="text-align:center"> <?php print $SI_STATDes; ?></td>
                        </tr>
                        <?php 
                    endforeach; 
                }
                ?>
            </tbody>
            </table>
        	<input type="button" name="btnPrintDoc1" id="btnPrintDoc1" class="btn btn-primary" value="Print SI Selected" onClick="getApproveSI2();" />
        	<hr />
        </td>
  </tr>
</table>
    </div>
    <!-- /.box-body -->
</div>
  <!-- /.box -->
</div>
</body>

</html>
<!-- jQuery 2.2.3 -->
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
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
<script>
	function addSICODE(thisValue)
	{
		SICODEL	= document.getElementsByName('SICODE').length;
		j		= 0;
		var selDocNumbColl = document.getElementById('selDocNumbColl').value;
		for(i=1; i <=SICODEL; i++)
		{
			SICODEC	= document.getElementById('SICODE'+i).checked;
			if(SICODEC == true)
			{
				j			= j + 1;
				SICODEV		= document.getElementById('SICODE'+i).value;
				if(j == 1)
				{
					SICODECOL1	= SICODEV;
					SICODECOL	= SICODEV;
				}
				else if(j > 1)

				{
					SICODECOL	= SICODECOL+'|'+SICODEV;
				}
			}
		}
		document.getElementById('totSelect').value 		= j;
		document.getElementById('selDocNumbColl').value = SICODECOL;
		document.frmSIApp.submit1.click();
	}
		
	function getApproveSI2()
	{
		document.frmSIApp2.submit2.click();
	}
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>