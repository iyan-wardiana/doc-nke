<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 Mei 2016
 * File Name	= project_selectinv_sd_selectINV.php
 * Notes		= Sync With SDBP on 25 Mei 2016
*/
?>
<?php
// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
?>

<?php
	// Searching Function	
	$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
	$DefProj_Code    	= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
	$selSearchType1     = $this->session->userdata['dtSessSrc1']['selSearchType'];
	$txtSearch1        	= $this->session->userdata['dtSessSrc1']['txtSearch'];	
	$dataSessSrc = array(
			'selSearchproj_Code' => $DefProj_Code,
			'selSearchType' => $selSearchType1,
			'txtSearch' => $txtSearch1);
	$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
	$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
	
	$proj_Code = $DefProj_Code;
	
	$sqlPrj	= "SELECT * FROM sd_tproject WHERE PRJCODE = '$proj_Code'";
	$PrjRes	= $this->db->query($sqlPrj)->result();
	foreach($PrjRes as $rowPrj):
		$proj_Code 	= $rowPrj->PRJCODE;
		$PRJCODE 	= $rowPrj->PRJCODE;
		$PRJNAME 	= $rowPrj->PRJNAME;
	endforeach;
?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo base_url().'imagess/fav_icon.png';?>" />
<style type="text/css">@import url("<?php echo base_url() . 'css/reset.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style_menu.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style_table.css'; ?>");</style>
<script language="javascript" src="<?php echo base_url() . 'assets/js/allscript.js'; ?>"></script>
<style type="text/css">
.link{
	color:##003;
	cursor:pointer;
}
</style>

<title><?php echo isset($title) ? $title : ''; ?></title>
</head>

<body id="<?php echo isset($title) ? $title : ''; ?>">

<div id="mainPopUp">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<td>
        	<div class="HCSSTableGenerator">
            <table width="90%" border="0" style="size:auto">
              <tr>
                <td colspan="3" class="style2"><?php echo "$h2_title - $proj_Code : $PRJNAME"; ?></td>
              </tr>
            </table>
            </div>
        </td>
    </tr>
	<tr>
    	<td>	
            <form name="frm" id="frm" action="" method=POST>
                <select name="selSearchType" id="selSearchType" class="listmenu">
                    <option value="srcINV_Number">Invoice No.</option>
                    <option value="srcINV_NumberM">Kwitansi<h6></h6> No.</option>
                </select>
                <input type="text" name="txtRefference" id="txtRefference" class="textbox" value="<?php echo $txtRefference; ?>" />
                <input type="submit" class="button_css" name="srcSubmit" id="srcSubmit" value=" search " />
            </form>
        </td>
    </tr>
	<tr>
    	<td>
        	
        </td>
    </tr>
</table>
    
<form method="post" name="frmSearch" action="">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr>
            <td>
                <div class="CSSTableGenerator">
                    <table width="100%" border="0">
                        <tr>
                          	<td width="20%">Invoice No.</td>
                          	<td width="26%">Manual Numbera</td>
                          	<td width="19%">Invoice Date</td>
                          	<td width="28%">Due Date</td>
                          	<td width="28%">Value (IDR)</td>
                          	<td width="28%">PPn (IDR)</td>
                      	</tr>
                        <?php
                            $i = 0;
							$currow = 0;
							$pageFrom = $pageFrom;
							if(isset($_POST['txtRefference']))
							{
								$txtRefference = $_POST['txtRefference'];
								$selSearchType = $_POST['selSearchType'];
								if($txtRefference == '')
								{									
									// count data		
										$sqlC = "sd_tprojinv_header A
												INNER JOIN 	sd_tproject B ON A.proj_Code = B.PRJCODE
												WHERE 
												A.PINV_Status NOT IN (1,4,5)
												AND A.proj_Code  = '$DefProj_Code'
												ORDER BY A.PINV_Number ASC";		
										$recordcountAllPINV = $this->db->count_all($sqlC);
									// End count data
									
									$sql = "SELECT A.*
											FROM sd_tprojinv_header A
											INNER JOIN 	sd_tproject B ON A.proj_Code = B.PRJCODE
											WHERE 
											A.PINV_Status NOT IN (1,4,5)
											AND A.proj_Code  = '$DefProj_Code'
											ORDER BY A.PINV_Number ASC";
								}
								else
								{
									if($selSearchType == 'srcINV_Number')								
									{
										// count data		
											$sqlC = "sd_tprojinv_header A
													INNER JOIN 	sd_tproject B ON A.proj_Code = B.PRJCODE
													WHERE 
													A.PINV_Status NOT IN (1,4,5)
													AND A.proj_Code  = '$DefProj_Code'
													AND A.PINV_Number LIKE '%$txtRefference%'
													ORDER BY A.PINV_Number ASC";		
											$recordcountAllPINV = $this->db->count_all($sqlC);
										// End count data
										
										$sql = "SELECT A.*
												FROM sd_tprojinv_header A
												INNER JOIN 	sd_tproject B ON A.proj_Code = B.PRJCODE
												WHERE 
												A.PINV_Status NOT IN (1,4,5)
												AND A.proj_Code  = '$DefProj_Code'
												AND A.PINV_Number LIKE '%$txtRefference%'
												ORDER BY A.PINV_Number ASC";
									}
									else
									{									
										// count data		
											$sqlC = "sd_tprojinv_header A
													INNER JOIN 	sd_tproject B ON A.proj_Code = B.PRJCODE
													WHERE 
													A.PINV_Status NOT IN (1,4,5)
													AND A.proj_Code  = '$DefProj_Code'
													AND A.PINV_NumberM LIKE '%$txtRefference%'
													ORDER BY A.PINV_Number ASC";		
											$recordcountAllPINV = $this->db->count_all($sqlC);
										// End count data
										
										$sql = "SELECT A.*
												FROM sd_tprojinv_header A
												INNER JOIN 	sd_tproject B ON A.proj_Code = B.PRJCODE
												WHERE 
												A.PINV_Status NOT IN (1,4,5)
												AND A.proj_Code  = '$DefProj_Code'
												AND A.PINV_NumberM LIKE '%$txtRefference%'
												ORDER BY A.PINV_Number ASC";
									}
								}
								$viewAllPINV = $this->db->query($sql)->result();
							}
                            if($recordcountAllPINV>0)
                            {
                                foreach($viewAllPINV as $row) :
                                    $PINV_Number = $row->PINV_Number;
                                    $PINV_NumberM = $row->PINV_NumberM;
                                    $PINV_Date = $row->PINV_Date;
                                    $PINV_EndDate = $row->PINV_EndDate;
                                    $AchievPercent = $row->AchievPercent;
                                    $AmountINV = $row->AchievAmount;
                                    $AmPPn = $row->AchievAmountPPn;
                                    $TotAm = $AmountINV + $AmPPn;
                                    $PINV_KwitAm = $row->PINV_KwitAm;
									$currow = $currow + 1;
									
                        			?>
                                    <tr>
                                  		<td>
                                        	<a  onclick="get_item('<?php echo $PINV_Number; ?>', '<?php echo $AmountINV; ?>',' <?php echo $AmPPn; ?>', '<?php echo $TotAm; ?>', '<?php echo $PINV_KwitAm; ?>')" class="link">
											<?php echo $PINV_Number; ?></a> 
                                            <input type="hidden" name="PINV_Number<?php echo $currow; ?>" id="PINV_Number<?php echo $currow; ?>" value="<?php echo $PINV_Number; ?>" />
                                       	</td>
                                        <td><?php echo $PINV_NumberM; ?></td>
                                        <td><?php echo $PINV_Date; ?></td>
                                        <td><?php echo $PINV_EndDate; ?></td>
                                        <td><?php print number_format($AmountINV, $decFormat); ?></td>
                                        <td><?php print number_format($AmPPn, $decFormat); ?></td>
                                    </tr>
                        			<?php
                            	endforeach;
                            }
                            if($currow == 0)
                            {
                        		?>
                                <tr>
                                    <td colspan="4" style="text-align:center"> ---- None ----</td>
                                </tr>
                        		<?php
                            }
                        ?>
                    </table>
              </div>
            </td>
        </tr>
        <tr>
            <td><hr /></td>
        </tr>
        <tr>
            <td>
                <input type="button" name="btnCancel" id="btnCancel" class="button_css" value=" Cancel " onclick="window.close()" />
            </td>
        </tr>
    </table>
</form>

<script>
	function showItem(pthis){
		var txtRefference = document.getElementById('txtRefference').value
		//alert('Please input one Purchase Requisition Number.')
		document.getElementById('txtRefference').focus();
		document.getElementById('txtRefference').value = document.getElementById('RR_Number'+pthis).value;
		document.getElementById('detailPR').style.display = '';
		document.getElementById('headerPR').style.display = 'none';
		document.getElementById("frm").submit();
	}


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
	if (selectedRows==NumOfRows) 
	{
		document.frmSearch.ChkAllItem.checked = true;
	}
	else
	{
		document.frmSearch.ChkAllItem.checked = false;
	}
}	

function get_item(PINV_Number, AmountINV, AmPPn, TotAm, PINV_KwitAm) 
{
	//alert(TotAm)
	window.opener.add_invoice(PINV_Number, AmountINV, AmPPn, TotAm, PINV_KwitAm);	
				
	window.close();		
}
</script>

 </div>
</body>
</html>