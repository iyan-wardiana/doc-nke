<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 07 Juni 2018
 * File Name	= v_amd_sel_si.php
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
                            <tr>
                                <td colspan="6" nowrap>
                                <button class="btn btn-primary" type="button" onClick="get_req();">
                                    <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                                    <button class="btn btn-danger" type="button" onClick="window.close()">
                                    <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>
                                </td>
                            </tr>
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