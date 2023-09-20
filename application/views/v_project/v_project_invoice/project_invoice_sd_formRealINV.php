<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 Mei 2016
 * File Name	= project_invoice_formRealINV.php
 * Notes		= Sync With SDBP on 27 Mei 2016
*/
?>
<?php
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
?>
<style>
.inplabel {border:none;background-color:white;}
.inpdim {border:none;background-color:white;}
</style>
<body>
<div class="HCSSTableGenerator">
    <table width="100%" border="0" style="size:auto">
      <tr>
        <td colspan="3" class="style2"><?php echo $h2_title; ?></td>
      </tr>
    </table>
</div>
<?php
	$currentRow = 0;
	if($task == 'add')
	{
		//$proj_Code = '';
		/*$sqlPID = "SELECT proj_ID FROM sd_tproject WHERE proj_Code = '$proj_Code'";
		$resultPID = $this->db->query($sqlPID)->result();
		foreach($resultPID  as $rowPID) :
			$proj_ID = $rowPID->proj_ID;
		endforeach;*/
		
		$dataSessSrc = array(
                'selSearchproj_Code' => $proj_Code,
                'selSearchType' => $this->input->post('selSearchType'),
                'txtSearch' => $this->input->post('txtSearch'));
		$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
		$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
		
		$PRINV_EmpID 				= '';
		$default['PRINV_EmpID'] 	= '';
		$Vend_Code 					= '';
		$default['Vend_Code'] 		= '';
		$default['PRINV_Status'] 	= 2;
		$default['PINV_STAT'] = 1;
		$PRINV_Status 				= 2;
		$PINV_STAT 			= 1;
		$PRINV_Class = 'Normal';
		foreach($viewDocPattern as $row) :
			$Pattern_Code = $row->Pattern_Code;
			$Pattern_Position = $row->Pattern_Position;
			$Pattern_YearAktive = $row->Pattern_YearAktive;
			$Pattern_MonthAktive = $row->Pattern_MonthAktive;
			$Pattern_DateAktive = $row->Pattern_DateAktive;
			$Pattern_Length = $row->Pattern_Length;
			$useYear = $row->useYear;
			$useMonth = $row->useMonth;
			$useDate = $row->useDate;
		endforeach;
		if($Pattern_Position == 'Especially')
		{
			$Pattern_YearAktive = date('Y');
			$Pattern_MonthAktive = date('m');
			$Pattern_DateAktive = date('d');
		}
		$yearC = (int)$Pattern_YearAktive;
		$year = substr($Pattern_YearAktive,2,2);
		$month = (int)$Pattern_MonthAktive;
		$date = (int)$Pattern_DateAktive;
	
		$this->db->where('Patt_Year', $year);
		//$this->db->where('Patt_Month', $month);
		//$this->db->where('Patt_Date', $date);
		$myCount = $this->db->count_all('sd_tprojinv_realh');
		
		$sql = "SELECT MAX(Patt_Number) as maxNumber FROM sd_tprojinv_realh WHERE proj_Code = '$proj_Code'";
		$result = $this->db->query($sql)->result();
		if($myCount>0)
		{
			foreach($result as $row) :
				$myMax = $row->maxNumber;
				$myMax = $myMax+1;
			endforeach;
		}	else	{		$myMax = 1;	}
		
		$thisMonth = $month;
		
		$lenMonth = strlen($thisMonth);
		if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
		$pattMonth = $nolMonth.$thisMonth;
		
		$thisDate = $date;
		$lenDate = strlen($thisDate);
		if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
		$pattDate = $nolDate.$thisDate;
		
		// group year, month and date
		if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
			$groupPattern = "$year$pattMonth$pattDate";
		elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
			$groupPattern = "$year$pattMonth";
		elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
			$groupPattern = "$year$pattDate";
		elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
			$groupPattern = "$pattMonth$pattDate";
		elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
			$groupPattern = "$year";
		elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
			$groupPattern = "$pattMonth";
		elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
			$groupPattern = "$pattDate";
		elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
			$groupPattern = "";
		
			
		$lastPatternNumb = $myMax;
		$lastPatternNumb1 = $myMax;
		
		$len = strlen($lastPatternNumb);
		
		if($Pattern_Length==2)
		{
			if($len==1) $nol="0";
		}
		elseif($Pattern_Length==3)
		{if($len==1) $nol="00";else if($len==2) $nol="0";
		}
		elseif($Pattern_Length==4)
		{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
		}
		elseif($Pattern_Length==5)
		{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
		}
		elseif($Pattern_Length==6)
		{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
		}
		elseif($Pattern_Length==7)
		{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
		}
		$lastPatternNumb = $nol.$lastPatternNumb;
		
		
		$sql = "SELECT proj_Number, PRJCODE, PRJNAME FROM sd_tproject WHERE PRJCODE = '$proj_Code'";
		$resultProj = $this->db->query($sql)->result();
		foreach($resultProj as $row) :
			$proj_Number = $row->proj_Number;
			$PRJCODE 	= $row->PRJCODE;
			$PRJNAME 	= $row->PRJNAME;
		endforeach;
		
		$DocNumber = "$Pattern_Code$proj_Code$groupPattern-$lastPatternNumb";
		
		
		$PRINV_DateY = date('Y');
		$PRINV_DateM = date('m');
		$PRINV_DateD = date('d');
		
		$PRINV_Date 		= "$PRINV_DateY-$PRINV_DateM-$PRINV_DateD";
		$PRINV_CreateDate 	= "$PRINV_DateY-$PRINV_DateM-$PRINV_DateD";
		
		$Patt_Year = date('Y');
		$PRINV_EndDate = $PRINV_Date;
		
		
		$PRINV_Number = "$Pattern_Code$proj_Code$groupPattern-$lastPatternNumb";
		$RealINVAmount		= 0;
		$RealINVAmountPPh	= 0;
		$RealINVOtherAm		= 0;
		$isPPh				= 1;
		$PRINV_Deviation	= '';
		
		$PRINV_Notes 		= '';
		$PINV_KwitAm 		= '';
		$PINV_KwitAm 		= 0;
		$PINV_KwitAmPPn 	= 0;
		$PINV_KwitAmPPnTot	= $PINV_KwitAm + $PINV_KwitAmPPn;
	}
	else
	{
		$PRINV_Number 		= $default['PRINV_Number'];
		$DocNumber			= $PRINV_Number;
		$PINV_Number 		= $default['PINV_Number'];
		$PRINV_Date 		= $default['PRINV_Date'];
		$PRINV_CreateDate 	= $default['PRINV_CreateDate'];
		$RealINVAmount		= $default['RealINVAmount'];
		$RealINVAmountPPh	= $default['RealINVAmountPPh'];
		$RealINVOtherAm		= $default['RealINVOtherAm'];
		$isPPh				= $default['isPPh'];
		$proj_Code 			= $default['proj_Code'];
		$PRJCODE 			= $default['PRJCODE'];
		$PRINV_Notes 		= $default['PRINV_Notes'];
		$PRINV_Deviation 	= $default['PRINV_Deviation'];
		$PRINV_Status		= '';
		$lastPatternNumb1 	= $default['PRINV_Deviation'];
		$Patt_Year 			= $default['Patt_Year'];
		
		$dataSessSrc = array(
                'selSearchproj_Code' => $PRJCODE,
                'selSearchType' => $this->input->post('selSearchType'),
                'txtSearch' => $this->input->post('txtSearch'));
		$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
		$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
										
		$sqlx 		= "SELECT A.PINV_KwitAm, A.PINV_KwitAmPPn
						FROM sd_tprojinv_header A
							INNER JOIN 	sd_tproject B ON A.proj_Code = B.PRJCODE
						WHERE 
							A.PINV_Status NOT IN (1,4,5)
							AND A.proj_Code  = '$proj_Code'
							AND A.PINV_Number = '$PINV_Number'";
		$viewAllPINVx = $this->db->query($sqlx)->result();
		foreach($viewAllPINVx as $rowx) :
			$PINV_KwitAm 	= $rowx->PINV_KwitAm;
			$PINV_KwitAmPPn = $rowx->PINV_KwitAmPPn;
		endforeach;
		$PINV_KwitAmPPnTot	= $PINV_KwitAm + $PINV_KwitAmPPn;
	}
	
	$sqlPRJ = "SELECT PRJCOST AS proj_amountIDR FROM sd_tproject WHERE PRJCODE = '$PRJCODE'";
	$resultPRJ = $this->db->query($sqlPRJ)->result();
	foreach($resultPRJ  as $rowPRJ) :
		$proj_amountIDR = $rowPRJ->proj_amountIDR;
	endforeach;
	
	$AchievAmount = 0;
?>

<form name="frm" id="frm" method="post" action="<?php echo $form_action; ?>">
    <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
    <input type="hidden" name="rowCount" id="rowCount" value="0">
    <input type="hidden" name="PINV_KwitAm" id="PINV_KwitAm" value="<?php echo $PINV_KwitAm; ?>">
    <table width="100%" border="0" style="size:auto">
        <tr>
            <th width="17%" align="left" class="style1"> Invoice Realization No.</th>
       	  <th width="1%" align="left" class="style1">:</th>
			<th width="35%" align="left" class="style1"> <?php echo $DocNumber; ?>
            <input type="hidden" class="textbox" name="PRINV_Number" id="PRINV_Number" size="30" value="<?php echo set_value('PRINV_Number', isset($default['PRINV_Number']) ? $default['PRINV_Number'] : $DocNumber); ?>" />
            <input type="hidden" class="textbox" name="PRINV_Status" id="PRINV_Status" size="30" value="<?php echo set_value('PRINV_Status', isset($default['PRINV_Status']) ? $default['PRINV_Status'] : $PRINV_Status); ?>" />
            <input type="hidden" class="textbox" name="Patt_Number" id="Patt_Number" size="30" value="<?php echo $lastPatternNumb1; ?>" />
            <input type="hidden" class="textbox" name="Patt_Year" id="Patt_Year" size="30" value="<?php echo $Patt_Year; ?>" />           	</th>
      		<th width="13%" align="left" class="style1">Invoice Number</th>
      		<th width="34%" align="left" class="style1">: &nbsp;<input type="text" class="textbox" name="PINV_Number" id="PINV_Number" size="20" value="<?php echo set_value('PINV_Number', isset($default['PINV_Number']) ? $default['PINV_Number'] : ''); ?>" />  
            <?php
				$secAllINV	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_invoice_sd'),'popupallINV', array('param' => $PRJCODE));
			?>         
            	<script>
					var urlINV = "<?php echo $secAllINV; ?>";
					function selectINV()
					{
						title = 'Select Item';
						w = 850;
						h = 550;
						//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
						var left = (screen.width/2)-(w/2);
  						var top = (screen.height/2)-(h/2);
  						return window.open(urlINV, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
					}
				</script>
                <a href="javascript:void(null);" onClick="selectINV();"><img src="<?php echo base_url().'images/11.png';?>" width="20" height="20"></a>      		</th>
      	</tr>
        <tr>
            <th align="left" class="style1">Project</th>
            <th align="left" class="style1">:</th>
            <th align="left" class="style1">          
            <select name="proj_Code1" id="proj_Code1" class="listmenu" onChange="chooseProject()">
            	<option value="none">--- None ---</option>
                <?php echo $i = 0;
				if($recordcountProject > 0)
				{
					foreach($viewProject as $row) :
                        $PRJCODE1 	= $row->PRJCODE;
						$PRJNAME 	= $row->PRJNAME;
						?>
			  			<option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo $PRJNAME; ?></option>
						<?php
					endforeach;
				}
				else
				{
					?>
						<option value="none">--- No Unit Found ---</option>
					<?php
				}
				?>
		    </select>
            <input type="text" name="proj_Code" id="proj_Code" value="<?php echo $proj_Code; ?>" style="display:none">            </th>
            <th align="left" class="style1">Invoice Amount</th>
            <th align="left" class="style1">: &nbsp;
                <input type="text" size="15" class="textbox" style="text-align:right;" name="AchievAmount" id="AchievAmount" value="<?php print number_format($PINV_KwitAm, $decFormat); ?>" disabled>            </th>
        </tr>
        <tr>
            <th align="left" class="style1">Project Value</th>
          	<th align="left" class="style1">:</th>
            <th align="left" class="style1" style="font-weight:bold">
            	<input type="text" size="15" class="textbox" style="text-align:right;" name="proj_amountIDR1" id="proj_amountIDR1" value="<?php print number_format($proj_amountIDR, $decFormat); ?>" disabled>
            	<input type="hidden" size="15" class="textbox" style="text-align:right;" name="proj_amountIDR" id="proj_amountIDR" value="<?php echo $proj_amountIDR; ?>">            </th> 
            <th align="left" class="style1">Invoice PPn</th>
            <th align="left" class="style1">: &nbsp;
                <input type="text" size="15" class="textbox" style="text-align:right;" name="AchievAmountPPn" id="AchievAmountPPn" value="<?php print number_format($PINV_KwitAmPPn, $decFormat); ?>" disabled>            </th>
        </tr>
        <tr>
            <th align="left" class="style1">PPn Value (10%)</th>
          	<th align="left" class="style1">:</th>
            <th align="left" class="style1" style="font-weight:bold">
              <?php
				$proj_amountPPnIDR 	= $proj_amountIDR * 0.1;
				$proj_amountnPPnIDR = $proj_amountIDR + $proj_amountPPnIDR;
			?>
              <input type="text" size="15" class="textbox" style="text-align:right;" name="proj_amountPPnIDR1" id="proj_amountPPnIDR1" value="<?php print number_format($proj_amountPPnIDR, $decFormat); ?>" disabled>
              <input type="hidden" size="15" class="textbox" style="text-align:right;" name="proj_amountPPnIDR" id="proj_amountPPnIDR" value="<?php echo $proj_amountPPnIDR; ?>">              </th> 
            <th align="left" class="style1" id="labelProject1">Total Invoice</th>
            <th align="left" class="style1" id="labelProject2">: &nbsp;
                <input type="text" size="15" class="textbox" style="text-align:right;" name="TotAchievAmount" id="TotAchievAmount" value="<?php print number_format($PINV_KwitAmPPnTot, $decFormat); ?>" disabled>            </th>
      </tr>
        <tr>
            <th align="left" class="style1">Total Project  Value (+ PPn 10%)</th>
          	<th align="left" class="style1">:</th>
            <th align="left" class="style1" style="font-weight:bold">
            <input type="text" size="15" class="textbox" style="text-align:right;" name="proj_amountTotIDR1" id="proj_amountTotIDR1" value="<?php print number_format($proj_amountnPPnIDR, $decFormat); ?>" disabled>
            <input type="hidden" size="15" class="textbox" style="text-align:right;" name="proj_amountTotIDR" id="proj_amountTotIDR" value="<?php echo $proj_amountnPPnIDR; ?>"></th> 
            <th align="left" class="style1" id="labelProject1">&nbsp;</th>
            <th align="left" class="style1" id="labelProject2">&nbsp;</th>
        </tr>
        <tr id="DPA" >
            <th align="left" class="style1" style="font-weight:bold">REALIZATION DETAIL</th>
            <th colspan="4" align="left" class="style1" style="font-weight:bold"><hr></th>
        </tr>
        <tr id="DPB">
            <th align="left" class="style1">Realization Amount</th>
            <th align="left" class="style1">:</th>
            <th align="left" class="style1">
                <input type="text" size="17" class="textbox" style="text-align:right;" name="RealINVAmount1" id="RealINVAmount1" value="<?php print number_format($RealINVAmount, $decFormat); ?>" onChange="changeRealINVAmount(this.value);" >
                <input type="hidden" size="17" class="textbox" style="text-align:right;" name="RealINVAmount" id="RealINVAmount" value="<?php echo $RealINVAmount; ?>">            </th> 
            <th align="left" class="style1" id="labelProject1">Realization Date</th>
            <th align="left" class="style1" id="labelProject2">: &nbsp;
                <script type="text/javascript">SunFishERP_DateTimePicker('PRINV_CreateDate','<?php echo $PRINV_CreateDate;?>','onMouseOver="mybirdthdates();"');</script></th>
        </tr>
        <tr>
            <th align="left" class="style1">Inlcude PPh ?</th>
          	<th align="left" class="style1">:</th>
            <th align="left" class="style1">
                <input type="radio" name="isPPh" id="isPPh" value="1" onClick="changeisPPh(this.value)" checked> Yes <input type="radio" name="isPPh" id="isPPh" value="0" onClick="changeisPPh(this.value)" > 
                No</th> 
            <th align="left" class="style1">Deviation (Days)</th>
            <th align="left" class="style1">: &nbsp; <input type="text" size="2" class="textbox" style="text-align:right;" name="PRINV_Deviation" id="PRINV_Deviation" value="<?php echo $PRINV_Deviation; ?>"></th>
        </tr>
        <tr>
            <th align="left" class="style1">PPh Amount</th>
          	<th align="left" class="style1">:</th>
            <th align="left" class="style1"> <input type="text" size="17" class="textbox" style="text-align:right;" name="RealINVAmountPPh1" id="RealINVAmountPPh1" value="<?php print number_format($RealINVAmountPPh, $decFormat); ?>" onChange="changePPh(this.value)" >
              <input type="hidden" size="17" class="textbox" style="text-align:right;" name="RealINVAmountPPh" id="RealINVAmountPPh" value="<?php echo $RealINVAmountPPh; ?>"></th> 
            <th align="left" class="style1">&nbsp;</th>
            <th align="left" class="style1">&nbsp;</th>
        </tr>
        <tr>
            <th align="left" class="style1">Other Realization Amount</th>
          	<th align="left" class="style1">:</th>
            <th align="left" class="style1"> <input type="text" size="17" class="textbox" style="text-align:right;" name="RealINVOtherAm1" id="RealINVOtherAm1" value="<?php print number_format($RealINVOtherAm, $decFormat); ?>" onChange="changeOthINVAmount(this.value);" >
              <input type="hidden" size="17" class="textbox" style="text-align:right;" name="RealINVOtherAm" id="RealINVOtherAm" value="<?php echo $RealINVOtherAm; ?>"></th> 
            <th align="left" class="style1">&nbsp;</th>
            <th align="left" class="style1">&nbsp;</th>
        </tr>
        <tr>
            <th align="left" class="style1" valign="top">Notes</th>
            <th align="left" class="style1" valign="top">:</th>
          	<th align="left" class="style1"><textarea name="PRINV_Notes" class="textbox" id="PRINV_Notes" cols="30" style="height:50px"><?php echo set_value('PRINV_Notes', isset($default['PRINV_Notes']) ? $default['PRINV_Notes'] : ''); ?></textarea>
          	<?php echo form_error('PRINV_Notes', '<p class="field_error">', '</p>');?></th> 
            <th align="left" class="style1" id="labelProject1" valign="top">&nbsp;</th>
          	<th align="left" class="style1" id="labelProject2" valign="top">&nbsp;</th>
        </tr>
        <tr>
			<th colspan="5" align="left" class="style1">&nbsp;</th>
        </tr> 
        <tr>
          	<th colspan="5" align="left" class="style1"><hr></th>
        </tr> 
        <tr>
            <th colspan="4" align="left" class="style1">
				<?php
					//if($PRINV_Status == 2 || $PRINV_Status == 3)
						?>
							<input type="button" class="button_css" name="btnSubmt" id="btnSubmt" value="<?php if($task=='add')echo 'save'; else echo 'update';?>" onClick="submitForm(1);" />
						<?php 
                ?>
                
                <?php 
					if ( ! empty($link))
					{
						foreach($link as $links)
						{
							echo $links;
						}
					}
				?>        	</th>
            <th style="font-weight:bold; font-style:italic; text-align:right" nowrap>Active user: 
			<?php 
                $username 		= $this->session->userdata['username'];
                echo $username;
            ?></th>
        </tr>
        <tr>
          <th colspan="5" align="left" class="style1"><hr></th>
        </tr> 
    </table>
</form>
<script>	
	function changeisPPh(thisVal)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var RealINVAmount	= document.getElementById('RealINVAmount').value;
		var PINV_KwitAm		= document.getElementById('PINV_KwitAm').value.split(",").join("");
		if(thisVal == 1)
		{
			//RealINVAmountPPh = 0.03 * RealINVAmount;
			RealINVAmountPPh = 0.03 * PINV_KwitAm;
			document.getElementById('RealINVAmountPPh1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(RealINVAmountPPh)),decFormat));
			document.getElementById('RealINVAmountPPh').value 	= RealINVAmountPPh;
		}
		else
		{
			RealINVAmountPPh = 0.03 * 0;
			document.getElementById('RealINVAmountPPh1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(RealINVAmountPPh)),decFormat));
			document.getElementById('RealINVAmountPPh').value 	= RealINVAmountPPh;
		}
	}
	
	function changeisPPhB(thisVal)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var PINV_KwitAm		= document.getElementById('PINV_KwitAm').value.split(",").join("");
		var isPPh			= document.getElementById('isPPh').value;
		if(isPPh == 1)
		{
			//RealINVAmountPPh = 0.03 * RealINVAmount;
			RealINVAmountPPh = 0.03 * PINV_KwitAm;
			document.getElementById('RealINVAmountPPh1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(RealINVAmountPPh)),decFormat));
			document.getElementById('RealINVAmountPPh').value 	= RealINVAmountPPh;
		}
		else
		{
			RealINVAmountPPh = 0.03 * 0;
			document.getElementById('RealINVAmountPPh1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(RealINVAmountPPh)),decFormat));
			document.getElementById('RealINVAmountPPh').value 	= RealINVAmountPPh;
		}
	}
	
	function changePPh(thisValx)
	{
		var thisVal			= thisValx.split(",").join("");
		var decFormat		= document.getElementById('decFormat').value;
		document.getElementById('RealINVAmountPPh1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
		document.getElementById('RealINVAmountPPh').value 	= thisVal;
	}
	
	function changeOthINVAmount(thisValx)
	{
		var thisVal			= thisValx.split(",").join("");
		var decFormat		= document.getElementById('decFormat').value;
		document.getElementById('RealINVOtherAm1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
		document.getElementById('RealINVOtherAm').value 	= thisVal;		
	}
	
	function changeRealINVAmount(thisValx)
	{
		var thisVal			= thisValx.split(",").join("");
		var PINV_KwitAm		= document.getElementById('PINV_KwitAm').value.split(",").join("");
		var decFormat		= document.getElementById('decFormat').value;
		var isPPh			= document.getElementById('isPPh').checked;
				
		document.getElementById('RealINVAmount1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
		document.getElementById('RealINVAmount').value = thisVal;
		if(isPPh == 1)
		{
			RealINVAmountPPh = 0.03 * PINV_KwitAm;
			document.getElementById('RealINVAmountPPh1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(RealINVAmountPPh)),decFormat));
			document.getElementById('RealINVAmountPPh').value 	= RealINVAmountPPh;
		}
	}
	
	function doDecimalFormat(angka) {
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		//else return (c + '.' + dec);
		else return (c);  // untuk menghilangkan 2 angka di belakang koma
	}
	
	function doDecimalFormatxx(angka) {
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		else return (c + '.' + dec);
		//else return (c);  // untuk menghilangkan 2 angka di belakang koma
	}
	
	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
		
	var selectedRows = 0;
	function check_all(chk) 
	{
		var totRow = document.getElementById('totalrow').value;
		if(chk.checked == true)
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = true;
			}
		}
		else
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = false;
			}
		}
	}

	var selectedRows = 0;
	
	function add_invoice(PINV_Number, AmountINV, AmPPn, TotAm, PINV_KwitAm) 
	{
		var decFormat		= document.getElementById('decFormat').value;
		document.getElementById('PINV_Number').value 		= PINV_Number;
		document.getElementById('PINV_KwitAm').value 		= PINV_KwitAm;
		document.getElementById('AchievAmount').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AmountINV)),decFormat));
		document.getElementById('AchievAmountPPn').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AmPPn)),decFormat));
		document.getElementById('TotAchievAmount').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TotAm)),decFormat));
		changeisPPhB(PINV_KwitAm);
	}
	
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}

	function decimalin(ini)
	{	
		var i, j;
		var bil2 = deletecommaperiod(ini.value,'both')
		var bil3 = ""
		j = 0
		for (i=bil2.length-1;i>=0;i--)
		{
			j = j + 1;
			if (j == 3)
			{
				bil3 = "." + bil3
			}
			else if ((j >= 6) && ((j % 3) == 0))
			{
				bil3 = "," + bil3
			}
			bil3 = bil2.charAt(i) + "" + bil3
		}
		ini.value = bil3
	}
	
	function validateDouble(vcode,serialNumber) 
	{
		var thechk=new Array();
		var duplicate = false;
		var jumchk = document.getElementsByName('chk').length;
		if (jumchk!=null) 
		{
			thechk=document.getElementsByName('chk');
			panjang = parseInt(thechk.length);
		} 
		else 
		{
			thechk[0]=document.getElementsByName('chk');
			panjang = 0;
		}
		var panjang = panjang + 1;
		for (var i=0;i<panjang;i++) 
		{
			var temp = 'tr_'+parseInt(i+1);
			if(i>0)
			{
				var elitem1= document.getElementById('data'+i+'Item_code').value;
				var iparent= document.getElementById('data'+i+'serialNumber').value;
				if (elitem1 == vcode && iparent == serialNumber)
				{
					if (elitem1 == vcode) 
					{
						duplicate = true;
						break;
					}
				}
			}
		}
		return duplicate;
	}
	
	function getConvertion(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var RequestedQty	= eval(document.getElementById('adend_Percent'+row)).value.split(",").join("");
		
		thisVal = parseFloat(Math.abs(thisVal1.value))
		
		document.getElementById('data'+row+'adend_Percent1').value = thisVal;
		document.getElementById('adend_Percent1'+row).value = reqQty;
		document.getElementById('data'+row+'adend_Percent2').value = reqQty;
		document.getElementById('adend_Percent2'+row).value = reqQty;			
	}
	
	function getDPValue(thisVal)
	{
		var decFormat		= document.getElementById('decFormat').value;
		document.getElementById('DPPercent1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
		document.getElementById('DPPercent').value 		= thisVal;
		proj_amountIDR		= document.getElementById('proj_amountIDR').value;
		DPAmountx			= thisVal * proj_amountIDR / 100;
		document.getElementById('DPAmount').value 		= DPAmountx;
		document.getElementById('DPAmount1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(DPAmountx)),decFormat));
	}
	
	function getTMPercent(thisVal)
	{
		var decFormat		= document.getElementById('decFormat').value;
		document.getElementById('TMPercent1').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
		document.getElementById('TMPercent').value 		= thisVal;
	}
	
	function getTMAmount(thisVal)
	{
		var decFormat		= document.getElementById('decFormat').value;
		document.getElementById('TMAmount1').value 		= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
		document.getElementById('TMAmount').value 		= thisVal;
	}
	
	function submitForm(value)
	{
		var PINV_Number	= document.getElementById('PINV_Number').value;
		if(PINV_Number == '')
		{
			alert('Please select one of Invoice Number.');
			document.getElementById('PINV_Number').focus();
			return false;
		}
		var RealINVAmount	= document.getElementById('RealINVAmount').value;
		if(RealINVAmount == '')
		{
			alert('Please input Realization Amount.');
			document.getElementById('RealINVAmount').focus();
			return false;
		}
		document.frm.submit();
	}
	
	function changeFDate(thisVal)
	{		
		var date 			= new Date(thisVal);
		var datey 			= new Date(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0);
		var theM			= datey.getMonth();
		var dateDesc		= datey.getFullYear()+ "-" + theM + "-" + datey.getDate();
		document.getElementById('PRINV_TTODatex').value 	= formatDate(datey);
		var FDate			= document.getElementById('PRINV_TTODatex').value
		changeDueDate(FDate)
	}
	
	function changeDueDate(thisVal)
	{
		var FDate			= document.getElementById('PRINV_TTODatex').value
		var date 			= new Date(FDate);
		//alert(date)
		PRINV_TTOTerm		= parseInt(document.getElementById('PRINV_TTOTerm').value);
		var datey 			= new Date(date.getFullYear(), date.getMonth(), date.getDate() + PRINV_TTOTerm, 0, 0, 0);
		var theM			= datey.getMonth();
		if(theM == 0)
		{
			theMD	= 'January';
		}
		else if(theM == 1)
		{
			theMD	= 'February';
		}
		else if(theM == 2)
		{
			theMD	= 'March';
		}
		else if(theM == 3)
		{
			theMD	= 'April';
		}
		else if(theM == 4)
		{
			theMD	= 'May';
		}
		else if(theM == 5)
		{
			theMD	= 'June';
		}
		else if(theM == 6)
		{
			theMD	= 'July';
		}
		else if(theM == 7)
		{
			theMD	= 'August';
		}
		else if(theM == 8)
		{
			theMD	= 'September';
		}
		else if(theM == 9)
		{
			theMD	= 'October';
		}
		else if(theM == 10)
		{
			theMD	= 'November';
		}
		else if(theM == 11)
		{
			theMD	= 'December';
		}
		var dateDesc	=  datey.getDate()+ " " + theMD + " " + datey.getFullYear();
		document.getElementById('PRINV_TTODDate').value 	= formatDate(datey);
		document.getElementById('PRINV_TTODDatex').value	= dateDesc;
	}
	
	function formatDate(d) 
	{
		var dd = d.getDate()
		if ( dd < 10 ) dd = '0' + dd
		
		var mm = d.getMonth()+1
		if ( mm < 10 ) mm = '0' + mm
		
		var yy = d.getFullYear()
		
		return yy+'-'+mm+'-'+dd
	}

</script>
</body>
</html>
