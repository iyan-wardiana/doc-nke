<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 07 Juni 2018
 * File Name	= v_amd_sel_si.php
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

$PRJNAME		= '';
$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJNAME 	= $rowPRJ->PRJNAME;
endforeach;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?></title>
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

	$sqlTransl	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl	= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'SIStep')$SIStep = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'ContractAmount')$ContractAmount = $LangTransl;
		if($TranslCode == 'Deviation')$Deviation = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
	endforeach;
?>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<!-- Main content -->

<div class="box">
    <!-- /.box-header -->
<div class="box-body">
    <div class="callout callout-success" style="vertical-align:top">
        <?php echo "$PRJCODE - $PRJNAME"; ?>
    </div>
	<div class="search-table-outter">
        <form method="post" name="frmSearch" action="">
        <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
            <thead>
                <tr>
                    <th width="2%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
                    <th width="9%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Code; ?></th>
                    <th width="6%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
                    <th width="5%" style="vertical-align:middle; text-align:center" nowrap><?php echo $SIStep; ?></th>
                    <th width="70%" nowrap style="text-align:center"><?php echo $Description; ?></th>
                    <th width="8%" nowrap style="text-align:center"><?php echo $ContractAmount; ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
                $i = 0;
                $j = 0;
               /* $sqlSIC		= "tbl_siheader A
									INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
                                WHERE A.SI_STAT = 3 AND A.PRJCODE = '$PRJCODE' AND SI_AMDCREATED = 0";*/
                $sqlSIC		= "tbl_siheader A
									INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
                                WHERE A.SI_STAT = 3 AND A.PRJCODE = '$PRJCODE'";				
                $resSIC 	= $this->db->count_all($sqlSIC);
            
                $sql 		= "SELECT A.SI_CODE, A.SI_MANNO, A.SI_STEP, A.SI_OWNER, A.SI_DATE, A.SI_ENDDATE, 
                                    A.SI_DESC, A.SI_VALUE, A.SI_APPVAL
                                FROM tbl_siheader A
									INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
                                WHERE A.SI_STAT = 3 AND A.PRJCODE = '$PRJCODE'";
                $viewAllMR 	= $this->db->query($sql)->result();
                if($resSIC > 0)
                {
                    $totRow	= 0;
                    foreach($viewAllMR as $row) :
                        $SI_CODE 		= $row->SI_CODE;
                        $SI_MANNO 		= $row->SI_MANNO;
                        $SI_STEP 		= $row->SI_STEP;
                        $SI_OWNER 		= $row->SI_OWNER;
                        $SI_DATE 		= $row->SI_DATE;
						$SI_DATED		= date('d M Y', strtotime($SI_DATE));
                        $SI_ENDDATE 	= $row->SI_ENDDATE;
						$SI_ENDDATED	= date('d M Y', strtotime($SI_ENDDATE));
                        $SI_DESC 		= $row->SI_DESC;
                        $SI_VALUE 		= $row->SI_VALUE;
                        $SI_APPVAL		= $row->SI_APPVAL;
                        
                        $totRow			= $totRow + 1;
                    
                        if ($j==1) {
                            echo "<tr class=zebra1>";
                            $j++;
                        } else {
                            echo "<tr class=zebra2>";
                            $j--;
                        }
                        ?>
                        <td style="text-align:center"><input type="radio" name="chk" value="<?php echo $SI_CODE;?>|<?php echo $SI_VALUE;?>|<?php echo $SI_APPVAL;?>" onClick="pickThis(this);" /></td>
                        <td nowrap>
                            <a href="javascript:void(null);" class="link">
                            	<?php echo $SI_CODE; ?>
                            </a> 
                            <input type="hidden" name="SI_CODE<?php echo $totRow; ?>" id="SI_CODE<?php echo $totRow; ?>" value="<?php echo $SI_CODE; ?>" />
                        </td>
                        <td style="text-align:center" nowrap><?php echo $SI_DATED; ?></td>
                        <td style="text-align:center" nowrap><?php echo $SI_STEP; ?></td>
                        <td><?php echo $SI_DESC; ?></td>
                        <td style="text-align:right"><?php echo number_format($SI_VALUE, $decFormat); ?></td>
                        </tr>
                    <?php
                    endforeach;
                }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="6" nowrap>
                <button class="btn btn-primary" type="button" onClick="get_req();">
                    <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                    <button class="btn btn-danger" type="button" onClick="window.close()">
                    <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>
                </td>
            </tr>
            </tfoot>
        </table>
        </form>
                
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
var selectedRows = 0

function check_all(chk) {
	if(chk.checked) {
		if(typeof(document.frmSearch.chk[0]) == 'object') {
			for(i=0;i<document.frmSearch.chk.length;i++) {
				document.frmSearch.chk[i].checked = true;
			}
		} else {
			document.frmSearch.chk.checked = true;
		}
		selectedRows = document.frmSearch.chk.length;
	} else {
		if(typeof(document.frmSearch.chk[0]) == 'object') {
			for(i=0;i<document.frmSearch.chk.length;i++) {
				document.frmSearch.chk[i].checked = false;
			}
		} else {
			document.frmSearch.chk.checked = false;
		}
		selectedRows = 0;
	}
}

function pickThis(thisobj) 
{
	var NumOfRows = document.frmSearch.chk.length; // minus 1 because it's the header
	if (thisobj!= '') 
	{
		if (thisobj.checked) selectedRows++;
		else selectedRows--;
	}
	if(selectedRows > 1)
	{
		alert('Please select one Request');
		return false;
	}
	
	if (selectedRows==NumOfRows) 
	{
		document.frmSearch.ChkAllItem.checked = true;
	}
	else
	{
		document.frmSearch.ChkAllItem.checked = false;
	}
}
	

function get_req() 
	{
		if(typeof(document.frmSearch.chk[0]) == 'object') 
		{
			for(i=0;i<document.frmSearch.chk.length;i++) 
			{
				if(document.frmSearch.chk[i].checked) 
				{
					A = document.frmSearch.chk[i].value
					arrItem = A.split('|');
					arrparent = document.frmSearch.chk[i].value.split('|');

					window.opener.add_SI(document.frmSearch.chk[i].value);				
				}
			}
		}
		else
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_SI(document.frmSearch.chk.value);
				//alert('2' + '\n' + document.frmSearch.chk.value)
				/*A = document.frmSearch.chk.value
				arrItem = A.split('|');
				//alert(arrItem)
				for(z=1;z<=5;z++)
				{
					alert('1')
					B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
					//window.opener.add_item(B,'child');
					alert(B)
				}*/
			}
		}
		window.close();		
	}
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>