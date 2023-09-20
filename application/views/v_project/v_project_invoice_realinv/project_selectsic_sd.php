<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 05 September 2016
 * File Name	= project_selectsic_sd.php
 * Location		= -
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
if(isset($_POST['srcSubmit']))
{
	$txtRefference = $_POST['txtRefference'];			
	$dataSessSrcMC = array(
			'txtRefference' => $txtRefference);				
	$this->session->set_userdata('dtSessSrcMC', $dataSessSrcMC);
	
	$txtRefference 		= $this->session->userdata['dtSessSrcMC']['txtRefference'];
}
elseif(isset($_POST['srcSubmit2']))
{
	$txtRefference = $_POST['txtRefference2'];			
	$dataSessSrcMC = array(
			'txtRefference' => $txtRefference);				
	$this->session->set_userdata('dtSessSrcMC', $dataSessSrcMC);
	
	$txtRefference 		= $this->session->userdata['dtSessSrcMC']['txtRefference'];
}
else
{
	$txtRefference 		= '';
}
$PRJCODE 					= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
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
                <td colspan="3" class="style2"><?php echo $h2_title; ?></td>
              </tr>
            </table>
            </div>
        </td>
    </tr>
	<tr id="srchMRNumber">
    	<td>	
            <form name="frm" id="frm" action="" method=POST>
                <select name="selSearchType2" id="selSearchType2" class="listmenu">
                    <option value="srcSPPNUM">SI Number</option>
                </select>
                <input type="text" name="txtRefference2" id="txtRefference2" class="textbox"value="<?php echo $txtRefference; ?>" />
                <input type="submit" class="button_css" name="srcSubmit2" id="srcSubmit2" value=" Search MC" />
            </form>
        </td>
    </tr>
	<tr>
    	<td>
        	
        </td>
    </tr>
</table>
    <?php 
		if(isset($_POST['srcSubmit'])) // Search Item
		{
			$txtRefference 		= $_POST['txtRefference'];
			
			$sqlSrc 			= "sd_tsicertificate A
									WHERE A.PRJCODE = '$PRJCODE' AND SI_APPSTAT IN (0,1)
									ORDER BY A.SI_MANNO ASC";				
			$recordcountAllSIC 	= $this->db->count_all($sqlSrc);
			
			$sql 				= "SELECT A.*
									FROM sd_tsicertificate A
									WHERE A.PRJCODE = '$PRJCODE' AND SI_APPSTAT IN (0,1)
									ORDER BY A.SI_MANNO ASC";
			$viewAllMC 			= $this->db->query($sql)->result();
			?>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" id="headerPR" >
					<tr>
						<td>
							<div class="CSSTableGenerator">
								<table width="100%" border="0">
									<tr>
										<td width="2%">&nbsp;</td>
										<td width="11%">SI Number</td>
										<td width="11%" nowrap>Invoice Number</td>
										<td width="18%">SI Date</td>
										<td width="7%">SI Value</td>
										<td width="8%" nowrap>Approve Value</td>
										<td width="43%">Notes</td>
								  </tr>
									<?php							
										$currow = 0;
										if($recordcountAllSIC>0)
										{
											foreach($viewAllMC as $row) :
												$SI_CODE 		= $row->SI_CODE;		// 0
												$SI_MANNO 		= $row->SI_MANNO;		// 1
												$SI_DATE 		= $row->SI_DATE;		// 2
												$SI_ENDDATE 	= $row->SI_ENDDATE;		// 3
												$SI_DESC		= $row->SI_DESC;		// 4
												$SI_DPPER 		= $row->SI_DPPER;		// 5
												$SI_DPVAL		= $row->SI_DPVAL;		// 6
												$SI_VALUE 		= $row->SI_VALUE;		// 7
												$SI_APPVAL 		= $row->SI_APPVAL;		// 8
												$SI_PROPPERC	= $row->SI_PROPPERC;	// 9 
												$SI_PROPVAL 	= $row->SI_PROPVAL;		// 10
												$SI_NOTES 		= $row->SI_NOTES;		// 11
												$pageFrom 		= "SI";					// 12
												$SI_APPSTAT		= $row->SI_APPSTAT;
												$currow			= $currow + 1;
												
												// GET INVOICE NUMBER BY SI_CODE
													if($SI_APPSTAT == 1)
													{
														$sqlGetINV 			= "SELECT PINV_CODE, PINV_MANNO FROM sd_tprojinv_header WHERE PINV_SOURCE = '$SI_CODE'";
														$resGetINV 			= $this->db->query($sqlGetINV)->result();
														foreach($resGetINV as $rowGetINV) :
															$PINV_CODE 		= $rowGetINV->PINV_CODE;
															$PINV_MANNO 	= $rowGetINV->PINV_MANNO;
														endforeach;
													}	
													else
													{
														$PINV_CODE 		= "-";
														$PINV_MANNO 	= "-";
													}					
												?>
												<tr>
													<td style="text-align:center" nowrap><input type="checkbox" name="chk" value="<?php echo $SI_CODE;?>|<?php echo $SI_MANNO;?>|<?php echo $SI_DATE;?>|<?php echo $SI_ENDDATE;?>|<?php echo $SI_DESC;?>|<?php echo $SI_DPPER;?>|<?php echo $SI_DPVAL;?>|<?php echo $SI_VALUE;?>|<?php echo $SI_APPVAL;?>|<?php echo $SI_PROPPERC;?>|<?php echo $SI_PROPVAL;?>|<?php echo $SI_NOTES;?>|<?php echo $pageFrom;?>" onClick="pickThis(this);" <?php if($SI_APPSTAT == 1) { ?> disabled <?php } ?> /></td>
													<td nowrap><?php echo "$SI_MANNO"; ?></td>
													<td nowrap><?php echo "$PINV_MANNO"; ?></td>
													<td><?php echo $SI_DATE; ?></td>
													<td style="text-align:right"><?php print number_format($SI_VALUE, $decFormat); ?></td>
													<td style="text-align:right"><?php print number_format($SI_APPVAL, $decFormat); ?></td>
													<td><?php echo $SI_NOTES; ?></td>
												</tr>
												<?php
											endforeach;
										}
										else
										{
									?>
											<tr>
													<td colspan="6" style="text-align:center"> ---- None ----</td>
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
							<input type="button" name="btnSubmit" id="btnSubmit" class="button_css" value=" Select " onclick="getPurchaseNo()" />&nbsp;
							<input type="button" name="btnCancel" id="btnCancel" class="button_css" value=" Cancel " onclick="window.close()" />
						</td>
					</tr>
				</table>
			<?php
		}
		else
		{
			$sqlSrc 			= "sd_tsicertificate A
									WHERE A.PRJCODE = '$PRJCODE' AND SIC_STAT IN (2,3) AND SIC_APPSTAT IN (0,1)
									ORDER BY A.SIC_MANNO ASC";				
			$recordcountAllSIC 	= $this->db->count_all($sqlSrc);
			
			$sql 				= "SELECT A.*
									FROM sd_tsicertificate A
									WHERE A.PRJCODE = '$PRJCODE' AND SIC_STAT IN (2,3) AND SIC_APPSTAT IN (0,1)
									ORDER BY A.SIC_MANNO ASC";
			$viewAllMC 			= $this->db->query($sql)->result();
			?>
			<form method="post" name="frmSearch" action="">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" id="detailPR">
					<tr>
						<td><input type="button" name="btnSubmit" id="btnSubmit" class="button_css" value=" Select" onclick="get_item()" />&nbsp;
						<input type="button" name="btnCancel" id="btnCancel" class="button_css" value=" Cancel " onclick="window.close()" /></td>
					</tr>
					<tr>
						<td><hr /></td>
					</tr>
					<tr>
						<td>
							<div class="CSSTableGenerator">
								<table width="100%" border="0">
									<tr>
										<td width="2%">&nbsp;</td>
									  	<td width="11%">Certificate Number</td>
									  	<td width="11%" nowrap>Invoice Number</td>
									  	<td width="18%">SIC Date</td>
									  	<td width="7%">SIC Value</td>
										<td width="8%" nowrap>Approved Value</td>
										<td width="43%">Notes</td>
								 	</tr>
									<?php							
										$currow = 0;
										if($recordcountAllSIC>0)
										{
											foreach($viewAllMC as $row) :
												$pageFrom 		= "SIC";				// 0
												$SIC_CODE 		= $row->SIC_CODE;		// 1
												$SIC_MANNO 		= $row->SIC_MANNO;		// 2
												$SIC_DATE 		= $row->SIC_DATE;		// 3
												$SIC_APPDATE 	= $row->SIC_APPDATE;	// 4
												$SIC_PROG		= $row->SIC_PROG;		// 5
												$SIC_PROGVAL	= $row->SIC_PROGVAL;	// 6
												$SIC_TOTVAL 	= $row->SIC_TOTVAL;		// 7
												$SIC_NOTES 		= $row->SIC_NOTES;		// 8
												$SIC_STAT		= $row->SIC_STAT;		// 9 
												$SIC_APPSTAT 	= $row->SIC_APPSTAT;	// 10
												$currow			= $currow + 1;
												
												// GET INVOICE NUMBER BY SIC_CODE
													if($SIC_APPSTAT == 1)
													{
														$sqlGetINV 			= "SELECT PINV_CODE, PINV_MANNO FROM sd_tprojinv_header WHERE PINV_SOURCE = '$SIC_CODE'";
														$resGetINV 			= $this->db->query($sqlGetINV)->result();
														foreach($resGetINV as $rowGetINV) :
															$PINV_CODE 		= $rowGetINV->PINV_CODE;
															$PINV_MANNO 	= $rowGetINV->PINV_MANNO;
														endforeach;
													}	
													else
													{
														$PINV_CODE 		= "-";
														$PINV_MANNO 	= "-";
													}					
												?>
												<tr>
													<td style="text-align:center" nowrap><input type="checkbox" name="chk" value="<?php echo $pageFrom;?>|<?php echo $SIC_CODE;?>|<?php echo $SIC_MANNO;?>|<?php echo $SIC_DATE;?>|<?php echo $SIC_APPDATE;?>|<?php echo $SIC_PROG;?>|<?php echo $SIC_PROGVAL;?>|<?php echo $SIC_TOTVAL;?>|<?php echo $SIC_NOTES;?>|<?php echo $SIC_STAT;?>|<?php echo $SIC_APPSTAT;?>" onClick="pickThis(this);" <?php if($SIC_APPSTAT == 1) { ?> disabled <?php } ?> /></td>
													<td nowrap><?php echo "$SIC_MANNO"; ?></td>
													<td nowrap><?php echo "$PINV_MANNO"; ?></td>
													<td><?php echo $SIC_DATE; ?></td>
													<td style="text-align:right"><?php print number_format($SIC_PROGVAL, $decFormat); ?></td>
													<td style="text-align:right"><?php print number_format($SIC_TOTVAL, $decFormat); ?></td>
													<td><?php echo $SIC_NOTES; ?></td>
												</tr>
												<?php
											endforeach;
										}
										else
										{
									?>
											<tr>
													<td colspan="10" style="text-align:center"> ---- None ----</td>
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
						<td><input type="button" name="btnSubmit" id="btnSubmit" class="button_css" value=" Select " onclick="get_item()" />&nbsp;
						<input type="button" name="btnCancel" id="btnCancel" class="button_css" value=" Cancel " onclick="window.close()" /></td>
					</tr>
				</table>
			</form>
			<?php
		}
	?>
<script>	
	var selectedRows = 0
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
		// alert(document.frmSearch.chk.length) 
		if(typeof(document.frmSearch.chk[0]) == 'object') 
		{
			for(i=0;i<document.frmSearch.chk.length;i++) 
			{
				if(document.frmSearch.chk[i].checked) 
				{
					A = document.frmSearch.chk[i].value
					arrItem = A.split('|');
					arrparent = document.frmSearch.chk[i].value.split('|');

					window.opener.getDetail(document.frmSearch.chk[i].value);				
				}
			}
		} 
		else 
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.getDetail(document.frmSearch.chk.value);
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

 </div>
</body>
</html>