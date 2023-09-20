<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 06 September 2018
 * File Name	= project_selectmc.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

$theProjCode 	= $PRJCODE;
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
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'MCStep')$MCStep = $LangTransl;
		if($TranslCode == 'Tax')$Tax = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$h1_title	= "Silahkan pilih Site Instruction di bawah ini.";
		$sureDelete	= "Anda yakin akan menghapus data ini?";
	}
	else
	{
		$h1_title	= "Please select Site Instruction below.";
		$sureDelete	= "Are your sure want to delete?";
	}
    ?>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">

</section>
<!-- Main content -->

  <div class="box">
    <!-- /.box-header -->
	<div class="box-body">
		<div class="callout callout-success">
        	<p><?php echo $h1_title; ?></p>
      	</div>
        <form method="post" name="frmSearch" action="">
        	<table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
            <thead>
                <tr>
                    <th width="2%" style="text-align:center" nowrap><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" /></th>
               	  	<th width="10%" style="text-align:center" nowrap><?php echo $Code; ?> </th>
               	  	<th width="7%" style="text-align:center" nowrap><?php echo $Date; ?> </th>
                  	<th width="2%" style="text-align:center" nowrap><?php echo $MCStep; ?> </th>
               	  	<th width="49%" style="text-align:center" nowrap><?php echo $Description; ?></th>
                  	<th width="3%" style="text-align:center" nowrap><?php echo $Amount; ?>&nbsp;</th>
                  	<th style="text-align:center" nowrap>PPn </th>
                  	<th style="text-align:center" nowrap>PPh</th>
                  	<th style="text-align:center" nowrap>Total</th>
                </tr>
            </thead>
            <tbody>
            <?php
                    $i 	= 0;
					$j	= 0;
					if($countMC>0)
                    {
                        $totRow	= 0;
                        foreach($vwMC as $row) :
							$MC_CODE		= $row->MC_CODE;
							$MC_MANNO		= $row->MC_MANNO;
							$MC_STEP		= $row->MC_STEP;
							$PRJCODE		= $row->PRJCODE;
							$MC_OWNER		= $row->MC_OWNER;
							$MC_DATE		= $row->MC_DATE;
							$MC_ENDDATE		= $row->MC_ENDDATE;
							$MC_RETVAL		= $row->MC_RETVAL;
							$MC_PROG		= $row->MC_PROG;
							$MC_PROGCUR		= $row->MC_PROGCUR;
							$MC_PROGCURVAL	= $row->MC_PROGCURVAL;
							$MC_PROGVAL		= $row->MC_PROGVAL;
							$MC_VALADD		= $row->MC_VALADD;
							$MC_MATVAL		= $row->MC_MATVAL;
							$MC_DPPER		= $row->MC_DPPER;
							$MC_DPVAL		= $row->MC_DPVAL;
							$MC_DPBACK		= $row->MC_DPBACK;
							$MC_DPBACKCUR	= $row->MC_DPBACKCUR;
							$MC_RETCUTP		= $row->MC_RETCUTP;
							$MC_RETCUT		= $row->MC_RETCUT;
							$MC_RETCUTCUR	= $row->MC_RETCUTCUR;
							$MC_PROGAPP		= $row->MC_PROGAPP;
							$MC_PROGAPPVAL	= $row->MC_PROGAPPVAL;
							$MC_AKUMNEXT	= $row->MC_AKUMNEXT;
							$MC_VALBEF		= $row->MC_VALBEF;
							$MC_TOTVAL		= $row->MC_TOTVAL;
							$MC_TOTVAL_PPn	= $row->MC_TOTVAL_PPn;
							$MC_TOTVAL_PPh	= $row->MC_TOTVAL_PPh;
							$MC_NOTES		= $row->MC_NOTES;
							$MC_EMPID		= $row->MC_EMPID;
							$MC_EMPIDAPP	= $row->MC_EMPIDAPP;
							$MC_STAT		= $row->MC_STAT;
							$MC_APPSTAT		= $row->MC_APPSTAT;
							$MC_APPSTAT		= $row->MC_APPSTAT;
							$MC_APPSTAT		= $row->MC_APPSTAT;
							$MC_APPSTAT		= $row->MC_APPSTAT;
							$MC_TOTNPPN		= $MC_TOTVAL+$MC_TOTVAL_PPn;
							$MC_GTOTVALG	= $MC_PROGAPPVAL + $MC_DPVAL - $MC_DPBACK - $MC_RETCUT;
							$MC_GTOTVAL		= $MC_TOTVAL - $MC_TOTVAL_PPh;
							//$MC_GTOTVAL		= $MC_TOTVAL;
							
							if($MC_STAT == 0)
							{
								$MC_STATDes = "fake";
								$STATCOL	= 'danger';
							}
							elseif($MC_STAT == 1)
							{
								$MC_STATDes = "New";
								$STATCOL	= 'warning';
							}
							elseif($MC_STAT == 2)
							{
								$MC_STATDes = "Confirm";
								$STATCOL	= 'primary';
							}
							elseif($MC_STAT == 3)
							{
								$MC_STATDes = "Approve";
								$STATCOL	= 'success';
							}
							elseif($MC_STAT == 4)
							{
								$MC_STATDes = "Revise";
								$STATCOL	= 'warning';
							}
							elseif($MC_STAT == 5)
							{
								$MC_STATDes = "Rejected";
								$STATCOL	= 'danger';
							}
							elseif($MC_STAT == 6)
							{
								$MC_STATDes = "Close";
								$STATCOL	= 'primary';
							}
							elseif($MC_STAT == 7)
							{
								$MC_STATDes = "Waiting";
								$STATCOL	= 'warning';
							}
                            $totRow			= $totRow + 1;
				
								?>
                                <tr>
                                <td style="text-align:center"><input type="checkbox" name="chk" id="chk<?php echo $totRow; ?>" value="<?php echo $MC_CODE;?>|<?php echo $MC_MANNO;?>|<?php echo $MC_DATE;?>|<?php echo $MC_STEP;?>|<?php echo $MC_PROG;?>|<?php echo $MC_PROGVAL;?>|<?php echo $MC_PROGAPP;?>|<?php echo $MC_PROGAPPVAL;?>|<?php echo $MC_VALADD;?>|<?php echo $MC_MATVAL;?>|<?php echo $MC_DPPER;?>|<?php echo $MC_DPBACK;?>|<?php echo $MC_RETCUT;?>|<?php echo $MC_AKUMNEXT;?>|<?php echo $MC_VALBEF;?>|<?php echo $MC_TOTVAL;?>|<?php echo $MC_TOTVAL_PPn;?>|<?php echo $MC_TOTVAL_PPh;?>|<?php echo $MC_NOTES;?>|<?php echo $MC_GTOTVAL;?>|<?php echo $MC_TOTNPPN;?>|<?php echo $MC_PROGCUR;?>|<?php echo $MC_PROGCURVAL;?>|<?php echo $MC_DPBACKCUR;?>|<?php echo $MC_RETCUTP;?>|<?php echo $MC_RETCUTCUR;?>|<?php echo $MC_DPVAL;?>" onClick="pickThis(this);" /></td>
                                <td nowrap><?php echo $MC_MANNO; ?></td>
                                <td style="text-align:center" nowrap><?php echo $MC_DATE; ?></td>
                                <td nowrap style="text-align:center">&nbsp;</td>
                                <td nowrap><?php echo $MC_NOTES; ?></td>
                                <td nowrap style="text-align:center"><?php echo number_format($MC_TOTVAL, $decFormat); ?>&nbsp;</td>
                                <td width="7%" nowrap style="text-align:right"><?php echo number_format($MC_TOTVAL_PPn, $decFormat); ?>&nbsp;</td>
                                <td width="8%" nowrap style="text-align:right"><?php echo number_format($MC_TOTVAL_PPh, $decFormat); ?>&nbsp;</td>
                                <td nowrap style="text-align:right"><?php echo number_format($MC_GTOTVAL, $decFormat); ?>&nbsp;</td>
                        </tr>
                    <?php
                    endforeach;
                }
            ?>
            </tbody>
            <tr>
                <td colspan="9" nowrap>
                <button class="btn btn-primary" type="button" onClick="get_item();">
                    <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                    <button class="btn btn-danger" type="button" onClick="window.close()">
                    <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>
                </td>
            </tr>
        </table>
        </form>
    </div>
</div>
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
function check_all(chk) 
{
	if(chk.checked) 
	{
		if(typeof(document.frmSearch.chk[0]) == 'object') 
		{
			for(i=0;i<document.frmSearch.chk.length;i++) 
			{
				document.frmSearch.chk[i].checked 	= true;
				if(document.frmSearch.chk[i].disabled == true)
					document.frmSearch.chk[i].checked 	= false;
			}
		}
		else 
		{
			document.frmSearch.chk.checked = true;
		}
		selectedRows = document.frmSearch.chk.length;
	}
	else
	{
		if(typeof(document.frmSearch.chk[0]) == 'object')
		{
			for(i=0;i<document.frmSearch.chk.length;i++)
			{
				document.frmSearch.chk[i].checked = false;
			}
		}
		else
		{
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
	if (selectedRows==NumOfRows) 
	{
		document.frmSearch.ChkAllItem.checked = true;
	}
	else
	{
		document.frmSearch.ChkAllItem.checked = false;
	}
}
	

function get_item() 
	{ 
		//swal(document.frmSearch.chk.length) 
		if(typeof(document.frmSearch.chk[0]) == 'object') 
		{
			for(i=0;i<document.frmSearch.chk.length;i++) 
			{
				if(document.frmSearch.chk[i].checked) 
				{
					A = document.frmSearch.chk[i].value
					arrItem = A.split('|');
					arrparent = document.frmSearch.chk[i].value.split('|');
					
					window.opener.add_item(document.frmSearch.chk[i].value);				
				}
			}
		} 
		else 
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_item(document.frmSearch.chk.value);
				//swal('2' + '\n' + document.frmSearch.chk.value)
				/*A = document.frmSearch.chk.value
				arrItem = A.split('|');
				//swal(arrItem)
				for(z=1;z<=5;z++)
				{
					swal('1')
					B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
					//window.opener.add_item(B,'child');
					swal(B)
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