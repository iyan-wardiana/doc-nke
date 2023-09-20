<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Februari 2016
 * File Name	= project_invoiceRealINV.php
 * Notes		= Sync With SDBP on 27 Mei 2016
*/
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$selSearchproj_Code = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
?>
<div class="HCSSTableGenerator">
	<?php
		$sql = "SELECT PRJNAME FROM sd_tproject WHERE PRJCODE = '$PRJCODE'";
		$result = $this->db->query($sql)->result();
		foreach($result as $row) :
			$PRJNAME = $row ->PRJNAME;
		endforeach;
	?>
    <table width="100%" border="0">
        <tr height="20">
            <td colspan="3" id="HCSSTableGeneratorx"><b><?php echo $h2_title; echo " : $PRJNAME"; ?></b></td>
        </tr>
    </table>
</div>

<?php
	// Searching Function
	$selSearchType1      = $this->session->userdata['dtSessSrc1']['selSearchType'];
	$txtSearch1        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
	$selDocNumb = '';
	if(isset($_POST['submit']))
	{
		$selDocNumb = $_POST['selDocNumb'];
	}
?>
<form name="frmselect" id="frmselect" action="" method=POST>
	<input type="hidden" name="selDocNumb" id="selDocNumb" value="<?php echo $selDocNumb; ?>" />
    <input type="submit" class="button_css" name="submit" id="submit" value=" search " style="display:none" />
</form>
<form action="<?php print $srch_url;?>" method=POST>
    <table width="100%" border="0">
        <tr>
            <td>
                <select name="selSearchType" id="selSearchType" class="listmenu">
                    <option value="projINV_No" <?php if($selSearchType1 == 'projINV_No') { ?> selected <?php } ?>>Invoice No.</option>
                    <?php /*?><option value="ProjName" <?php if($selSearchType1 == 'ProjName') { ?> selected <?php } ?>>Project Name</option><?php */?>
                </select>
                <input type="text" name="txtSearch" id="txtSearch" class="textbox"value="<?php echo $txtSearch1; ?>" />
                <input type="hidden" name="proj_Code" id="proj_Code" class="textbox"value="<?php echo $PRJCODE; ?>" />
                <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />    
                <?php echo anchor("$showIdxMReq/".$PRJCODE,'<input type="button" name="btnShowAll" id="btnShowAll" class="button_css" value=" Show All " />');?>
        	</td>
            <td style="font-weight:bold; font-style:italic; text-align:right" nowrap>Active user: 
			<?php 
                $username 		= $this->session->userdata['username'];
                echo $username;
            ?>
        </td>
        </tr>
    </table>
</form>
<table width="100%" border="0">
    <tr height="20">
        <td colspan="3" style="text-align:right"><?php echo $pagination; ?></td>
    </tr>
</table>

<form action="<?php print site_url();?>/c_project/material_request/delete" onsubmit="confirmDelete();" method=POST>
<div class="CSSTableGenerator">
<table width="100%">
    <tr>
        <td width="2%"><input name="chkAll" id="chkAll" type="checkbox" value="" /></td>
      <td width="2%">No.</td>
      <td width="8%" nowrap>Realization Code</td>
      <td width="7%" nowrap>Invoice Number</td>
      <td width="7%" nowrap> Date</td>
      <td width="33%" nowrap>Project Name</td>
      <td width="8%" nowrap>Invoice Amount</td>
      <td width="10%">Realization Amount</td>
      <td width="7%" nowrap>PPh Amount</td>
      <td width="16%" nowrap>Other Amount</td>
    </tr>
	<?php 
	$i = 0;
	$PRJCODE1 = "$PRJCODE";
	
	$dataSessSrc = array(
			'selSearchproj_Code' => $PRJCODE1,
			'selSearchType' => $this->input->post('selSearchType'),
			'txtSearch' => $this->input->post('txtSearch'));
	$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
	$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
		
	if($recordcount >0)
	{
	foreach($viewprojinvoice as $row) : 
		$PINV_Number 		= $row->PINV_Number;
		$PRINV_Number 		= $row->PRINV_Number;
		$PRINV_Date 		= $row->PRINV_Date;
		$PRINV_CreateDate 	= $row->PRINV_CreateDate;
		$PINV_Amount 		= $row->PINV_Amount;
		$RealINVAmount 		= $row->RealINVAmount;
		$RealINVAmountPPh 	= $row->RealINVAmountPPh;
		$RealINVOtherAm 	= $row->RealINVOtherAm;
		$isPPh 				= $row->isPPh;
		$PRINV_Notes 		= $row->PRINV_Notes;
		
		$myNewNo1 = ++$i;
		$myNewNo = $moffset + $myNewNo1;
		
		$secURLUpd	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_invoice_sd'),'updateRealINV', array('param' => $PRINV_Number));
	?>
	<tr>
        <td style="text-align:center">
        <input type="radio" name="chkDetail" id="chkDetail" value="<?php echo $PRINV_Number;?>" onclick="getValueNo(this);" <?php if($PRINV_Number == $selDocNumb) { ?> checked <?php } ?>/></td>
        <td> <?php print $myNewNo; ?>. </td>
        <td nowrap> <?php print anchor("$secURLUpd",$row->PRINV_Number,array('class' => 'update')).' '; ?> </td>
        <td nowrap><?php print $row->PINV_Number; ?></td>
        <td style="text-align:center" nowrap>
			<?php
                $date = new DateTime($PRINV_Date);
                echo $date->format('d F Y');
            ?>		</td>
        <td nowrap> <?php print $PRJNAME; ?> </td>
        <td style="text-align:right" nowrap> <?php print number_format($PINV_Amount, $decFormat); ?> </td>
        <td style="text-align:right" nowrap> <?php print number_format($RealINVAmount, $decFormat); ?> </td>
        <td style="text-align:right" nowrap> <?php print number_format($RealINVAmountPPh, $decFormat); ?> </td>
        <td style="text-align:right" nowrap> <?php print number_format($RealINVOtherAm, $decFormat); ?> </td>
    </tr>
    <?php endforeach; 
	}
	else
	{
	?>
		<tr>
            <td colspan="10" style="text-align:center"> --- None ---</td>
   	    </tr>
    <?php
	}
	?>
</table>
</div>
<table width="100%" border="0">
    <tr height="20">
        <td colspan="3"><hr /></td>
    </tr>
    <tr height="20">
        <td colspan="3">
		<?php
			$secAddURL	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_invoice_sd'),'addRealINV', array('param' => $PRJCODE));
			$secURLPDoc	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_invoice_sd'),'printdocumentRealInv', array('param' => $selDocNumb));
			//$secURLEDoc	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_invoice_sd'),'editdocument', array('param' => $selDocNumb));
			echo anchor("$secAddURL",'<input type="button" name="btnSubmit" id="btnSubmit" class="button_css" value="Add New" />');
		?>&nbsp;
        <input type="hidden" name="myPINV_Number" id="myPINV_Number" value="<?php echo $selDocNumb; ?>" />
        <input type="button" name="btnDelete" id="btnDelete" class="button_css" value="Print Invoice" onclick="printDocument();" />
		<?php
			if ( ! empty($link))
			{
				foreach($link as $links)
				{
					echo $links;
				}
			}
		?>
		</td>
    </tr>
    <tr height="20">
        <td colspan="3"><hr /></td>
    </tr>
</table>    
<script>
	function getValueNo(thisVal)
	{
		myValue = thisVal.value;
		document.getElementById('myPINV_Number').value = myValue;
		document.getElementById('selDocNumb').value = myValue;
		chooseDocNumb(thisVal);
	}
	
	function chooseDocNumb(thisVal)
	{
		document.frmselect.submit.click();
	}
	
	function printDocument()
	{
		myVal = document.getElementById('myPINV_Number').value;
		if(myVal == '')
		{
			alert('Please select one of Invoice Number.')
			return false;
		}
		var url = '<?php echo $secURLPDoc; ?>';
		title = 'Select Item';
		w = 700;
		h = 700;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
	
</script>
</form>
