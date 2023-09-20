<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Februari 2018
 * File Name	= opname_sel_spk.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

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
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata('vers');

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
    	$LangID 	= $this->session->userdata['LangID'];

    	$sqlTransl	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    	$resTransl	= $this->db->query($sqlTransl)->result();
    	foreach($resTransl as $rowTransl) :
    		$TranslCode	= $rowTransl->MLANG_CODE;
    		$LangTransl	= $rowTransl->LangTransl;
    		
    		if($TranslCode == 'Select')$Select = $LangTransl;
    		if($TranslCode == 'Close')$Close = $LangTransl;
    		if($TranslCode == 'NoSPK')$NoSPK = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
    		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
    		if($TranslCode == 'Remain')$Remain = $LangTransl;
    	endforeach;
    ?>
    
    <body class="<?php echo $appBody; ?>">
        <section class="content-header">
        </section>
        <style>
        	.search-table, td, th {
        		border-collapse: collapse;
        	}
        	.search-table-outter { overflow-x: scroll; }
        </style>

        <section class="content">
            <div class="box">
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
                                    <th width="9%" style="vertical-align:middle; text-align:center" nowrap><?php echo $NoSPK; ?></th>
                                    <th width="10%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
                                    <th width="5%" nowrap style="text-align:center"><?php echo $StartDate; ?></th>
                                    <th width="7%" nowrap style="text-align:center"><?php echo $EndDate; ?></th>
                                    <th width="34%" nowrap style="text-align:center"><?php echo $Description; ?></th>
                                    <th width="11%" nowrap style="text-align:center"><?php echo $BudgetQty; ?></th>
                                    <th width="12%" nowrap style="text-align:center"><?php echo $QtyOpnamed; ?></th>
                                    <th width="10%" nowrap style="text-align:center"><?php echo $Remain; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $i = 0;
                                $j = 0;
                                $sqlReqC	= "tbl_wo_header A
                                                    INNER JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
                                                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
                                                WHERE A.WO_STAT = 3 AND A.PRJCODE = '$PRJCODE'
                                                ORDER BY A.WO_NUM ASC";				
                                $resReqC 	= $this->db->count_all($sqlReqC);
                            
                                $sql 		= "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_STARTD, A.WO_ENDD, A.WO_CREATER, A.WO_APPROVER, 
                                                    A.JOBCODEID, A.WO_NOTE, A.WO_STAT, A.WO_MEMO,
                                                    B.First_Name, B.Middle_Name, B.Last_Name,
                                                    C.PRJCODE, C.PRJNAME
                                                FROM tbl_wo_header A
                                                    INNER JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
                                                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
                                                WHERE A.WO_STAT = 3 AND A.PRJCODE = '$PRJCODE'
                                                ORDER BY A.WO_NUM ASC";
                                $viewAllMR 	= $this->db->query($sql)->result();
                                if($resReqC > 0)
                                {
                                    $totRow	= 0;
                                    foreach($viewAllMR as $row) :
                                        $WO_NUM 		= $row->WO_NUM;
                                        $WO_CODE 		= $row->WO_CODE;
                                        $WO_DATE 		= $row->WO_DATE;
                                        $WO_STARTD 		= $row->WO_STARTD;
                                        $WO_ENDD 		= $row->WO_ENDD;
                                        $WO_CREATER 	= $row->WO_CREATER;
                                        $WO_STAT 		= $row->WO_STAT;
                                        $WO_APPROVER 	= $row->WO_APPROVER;
                                        $WO_NOTE 		= $row->WO_NOTE;
                                        $PRJCODE		= $row->PRJCODE;
                                        $PRJNAME		= $row->PRJNAME;
                                        $First_Name		= $row->First_Name;
                                        $Middle_Name	= $row->Middle_Name;
                                        $Last_Name		= $row->Last_Name;
                                        $compName 		= "$First_Name $Middle_Name $Last_Name";
                						
                						// GET TOTAL SPK QTY
                						$TOTWO			= 0;
                						$TOTOPN			= 0;
                						$TOTREM			= 0;
                						$sqlQtyWO 		= "SELECT SUM(WO_VOLM) AS TOTWO, SUM(OPN_VOLM) AS TOTOPN
                											FROM tbl_wo_detail
                											WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
                						$resQtyWO 		= $this->db->query($sqlQtyWO)->result();
                						foreach($resQtyWO as $rowWOQty):
                							$TOTWO		= $rowWOQty->TOTWO;
                							$TOTOPN		= $rowWOQty->TOTOPN;
                							$TOTREM		= $TOTWO - $TOTOPN;
                						endforeach;
                                        
                                        $totRow			= $totRow + 1;
                                    
                                        if ($j==1) {
                                            echo "<tr class=zebra1>";
                                            $j++;
                                        } else {
                                            echo "<tr class=zebra2>";
                                            $j--;
                                        }
                                        ?>
                                        <td style="text-align:center"><input type="radio" name="chk" value="<?php echo $WO_NUM;?>|<?php echo $WO_CODE;?>" onClick="pickThis(this);" /></td>
                                        <td nowrap>
                                            <a href="javascript:void(null);" onClick="showItem(<?php echo $totRow; ?>)" class="link">
                                            <?php echo $WO_CODE; ?></a> 
                                            <input type="hidden" name="WO_NUM<?php echo $totRow; ?>" id="WO_NUM<?php echo $totRow; ?>" value="<?php echo $WO_NUM; ?>" />
                                        </td>
                                        <td nowrap><?php echo date('d M Y', strtotime($WO_DATE)); ?></td>
                                        <td nowrap><?php echo date('d M Y', strtotime($WO_STARTD)); ?></td>
                                        <td style="text-align:center" nowrap><?php echo date('d M Y', strtotime($WO_ENDD)); ?></td>
                                        <td><?php echo $WO_NOTE; ?></td>
                                        <td style="text-align:right"><?php echo number_format($TOTWO, 2); ?></td>
                                        <td style="text-align:right"><?php echo number_format($TOTOPN, 2); ?></td>
                                        <td style="text-align:right"><?php echo number_format($TOTREM, 2); ?></td>
                                        </tr>
                                    <?php
                                    endforeach;
                                }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="9" nowrap>
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
            </div>
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
		swal('Please select one Request');
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

					window.opener.add_header(document.frmSearch.chk[i].value);				
				}
			}
		}
		else
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_header(document.frmSearch.chk.value);
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
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

    // Right side column. contains the Control Panel
    //$this->load->view('template/aside');

    //$this->load->view('template/js_data');

    //$this->load->view('template/foot');
?>