<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 Maret 2017
 * File Name	= project_selectsi.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

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
	    
	    $sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'SICode')$SICode = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'CertificateNo')$CertificateNo = $LangTransl;
			if($TranslCode == 'Asked')$Asked = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
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
    
    <body class="<?php echo $appBody; ?>">
		<section class="content-header">
		</section>

        <section class="content">
			<div class="box">
				<div class="box-body">
					<div class="callout callout-success">
			        	<p><?php echo $h1_title; ?></p>
			      	</div>
			        <form method="post" name="frmSearch" action="">
			            <table id="example1" class="table table-bordered table-striped" width="100%">
			            	<thead>
			                <tr>
			                    <th width="2%" style="text-align:center" nowrap><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" /></th>
			               	  	<th width="18%" style="text-align:center" nowrap><?php echo $SICode; ?> </th>
			               	  	<th width="29%" style="text-align:center" nowrap><?php echo $ManualCode; ?> </th>
			                  	<th width="29%" style="text-align:center" nowrap><?php echo $CertificateNo; ?> </th>
			                  	<th colspan="2" style="text-align:center" nowrap><?php echo $Asked; ?> </th>
			                  	<th colspan="2" style="text-align:center" nowrap><?php echo $Approved; ?> </th>
			              </tr>
			              </thead>
			                <?php
			                    $i 	= 0;
								$j	= 0;
								if($recordcountAllSI>0)
			                    {
			                        $totRow	= 0;
			                        foreach($viewAllSI as $row) :
			                            $SI_CODE		= $row->SI_CODE;		// 0
			                            $SI_MANNO		= $row->SI_MANNO;		// 1
			                            $SI_DATE		= $row->SI_DATE;		// 2
			                            $SI_ENDDATE		= $row->SI_ENDDATE;
			                            $SI_APPDATE1	= $row->SI_APPDATE;
			                            if($SI_APPDATE1 == '')
			                                $SI_APPDATE	= "0000-00-00";			// 3
			                            else
			                                $SI_APPDATE	= date('Y-m-d',strtotime($SI_APPDATE1));
			                            $PRJCODE		= $row->PRJCODE;
			                            $PRJNAME		= $row->PRJNAME;
			                            $SI_DESC		= $row->SI_DESC;		// 4
			                            $SI_STAT		= $row->SI_STAT;
			                            $SI_VALUE		= $row->SI_VALUE;		// 5
			                            $SI_APPVAL		= $row->SI_APPVAL;		// 6
			                            $SI_APPSTAT		= $row->SI_APPSTAT;
			                            $totRow			= $totRow + 1;
			                                        
			                            // GET SERTIFICATE NUMBER BY SI_CODE
			                                $SIC_CODE 		= "-";
			                                $SIC_MANNO 		= "-";
			                                $isDisabled		= 0;
			                                
			                                $sqlGetSIXH		= "tbl_sicertificatedet WHERE SI_CODE = '$SI_CODE'";
			                                $resGetSIXH 	= $this->db->count_all($sqlGetSIXH);
			                                
			                                if($resGetSIXH > 0)
			                                {
			                                    $sqlGetSIC 			= "SELECT SIC_CODE, SIC_MANNO FROM tbl_sicertificatedet WHERE SI_CODE = '$SI_CODE'";
			                                    $resGetSIC 			= $this->db->query($sqlGetSIC)->result();
			                                    foreach($resGetSIC as $rowGetSIC) :
			                                        $SIC_CODE 		= $rowGetSIC->SIC_CODE;
			                                        $SIC_MANNO 		= $rowGetSIC->SIC_MANNO;
			                                    endforeach;
			                                    
			                                    $isDisabled			= 1;
			                                }
							
											if ($j==1) {
												echo "<tr class=zebra1>";
												$j++;
											} else {
												echo "<tr class=zebra2>";
												$j--;
											}
											?>
			                                    <td style="text-align:center"><input type="checkbox" name="chk" id="chk<?php echo $totRow; ?>" value="<?php echo $SI_CODE;?>|<?php echo $SI_MANNO;?>|<?php echo $SI_DATE;?>|<?php echo $SI_APPDATE;?>|<?php echo $SI_DESC;?>|<?php echo $SI_VALUE;?>|<?php echo $SI_APPVAL;?>" onClick="pickThis(this);" <?php if($isDisabled == 1) { ?> disabled <?php } ?> /></td>
			                                    <td nowrap><?php echo $SI_CODE; ?></td>
			                                    <td nowrap><?php echo $SI_MANNO; ?></td>
			                                    <td nowrap><?php echo $SIC_MANNO; ?></td>
			                                    <td width="6%" nowrap style="text-align:right"><?php echo $SI_DATE; ?>&nbsp;&nbsp;|</td>
			                              		<td width="4%" nowrap style="text-align:right"><?php echo number_format($SI_VALUE, $decFormat); ?>&nbsp;</td>
			                              		<td width="7%" nowrap style="text-align:right"><?php echo $SI_APPDATE; ?>&nbsp;&nbsp;|</td>
			                              		<td width="5%" nowrap style="text-align:right"><?php echo number_format($SI_APPVAL, $decFormat); ?>&nbsp;</td>
			              				</tr>
			                			<?php
			                        endforeach;
			                        ?>
			                        <tr>
			                            <td colspan="8" style="text-align:left">Total : <?php echo $totRow; ?> Site Instruction</td>
			                        </tr>
			                        <tr>
			                            <td colspan="8" style="text-align:left">
			                                <!--<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value=" Select" onClick="get_item()" />&nbsp;
			                                <input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value=" Close " onClick="window.close()" />-->
			                                
			                                <button class="btn btn-primary" type="button" onClick="get_item();">
			                                <i class="cus-check-green-16x16"></i>&nbsp;&nbsp;<?php echo $Select; ?>
			                                </button>&nbsp;
			                                
			                                <button class="btn btn-danger" type="button" onClick="window.close()">
			                                <i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>
			                                </button>
			                            </td>
			                        </tr>
									<?php
			                    }
			                ?>
			            </table>
			        </form>
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