<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 April 2016
 * File Name	= listproject_sd.php
 * Location		= -
*/
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

$EmpID 	= $this->session->userdata('Emp_ID');
$sqlEmpID 	= "SELECT Position_ID, Emp_DeptCode FROM thrmemployee WHERE Emp_ID = '$EmpID'";
$resEmpID	= $this->db->query($sqlEmpID)->result();
foreach($resEmpID as $rowEmpID) :
	$Position_ID		= $rowEmpID->Position_ID;
	$Emp_DeptCode		= $rowEmpID->Emp_DeptCode;
endforeach;
?>
<div class="HCSSTableGenerator">
    <table width="100%" border="0">
        <tr height="20">
            <td colspan="3" id="HCSSTableGeneratorx"><b><?php echo $h2_title; ?></b></td>
        </tr>
    </table>
</div>

<?php
	// Searching Function
	$selSearchType1     = $this->session->userdata['dtSessSrc1']['selSearchType'];
	$txtSearch1        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
	//$pagination1		= $this->mza_secureurl->setSecureUrl_encode($pagination);
?>

<?php
	$selProject = '';
	if(isset($_POST['submit']))
	{
		$selProject = $_POST['selProject'];
	}
?>
<form name="frmselect" id="frmselect" action="" method=POST>
	<input type="hidden" name="selProject" id="selProject" value="<?php echo $selProject; ?>" />
    <input type="submit" class="button_css" name="submit" id="submit" value=" search " style="display:none" />
</form>

<form action="<?php print $srch_url;?>" method=POST>   
    <table width="100%" border="0">
        <tr>
            <td>
                <select name="selSearchType" id="selSearchType" class="listmenu">
                    <option value="ProjNumber" <?php if($selSearchType1 == 'ProjNumber') { ?> selected <?php } ?>>Project Code</option>
                    <option value="ProjName" <?php if($selSearchType1 == 'ProjName') { ?> selected <?php } ?>>Project Name</option>
                </select>
                <input type="text" name="txtSearch" id="txtSearch" class="textbox"value="<?php echo $txtSearch1; ?>" />
                <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />    
                <?php echo anchor($showIndex,'<input type="button" name="btnShowAll" id="btnShowAll" class="button_css" value=" Show All " />');?>
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

<form action="<?php print site_url();?>/c_project/listproject/delete" onsubmit="confirmDelete();" method=POST>
<div class="CSSTableGenerator">
<table width="100%">
    <tr>
      <?php /*?><td width="2%"><input name="chkAll" id="chkAll" type="checkbox" value="" /></td><?php */?>
      <td width="3%">No.</td>
      <td width="6%" nowrap> Project Code</td>
      <td width="18%" nowrap>Project Name</td>
      <td width="21%" nowrap>Owner</td>
      <td width="29%" nowrap>Contract No.</td>
      <td width="9%">Project Cost<BR />(IDR)</td>
      <td width="8%" nowrap>Start Date</td>
      <td width="6%" nowrap>End Date</td>
      <td width="6%" nowrap>Status</td>
      <?php /*?><td width="12%">Project</td><?php */?>
      <?php /*?><td width="10%">Notes</td><?php */?>
    </tr>
	<?php 
	$i = 0;
	if($recordcount >0)
	{
	foreach($vewproject as $row) :
		$myNewNo1 		= ++$i;
		$myNewNo 		= $moffset + $myNewNo1;
		$PRJCODE 		= $row->PRJCODE;
		$PRJCNUM		= $row->PRJCNUM;
		$PRJNAME		= $row->PRJNAME;
		$PRJLOCT		= $row->PRJLOCT;
		$PRJCOST		= $row->PRJCOST;
		$PRJDATE		= $row->PRJDATE;
		$myDateProj 	= $row->PRJDATE;		
		$PRJSTAT		= $row->PRJSTAT;
			if($PRJSTAT == 0) $PRJSTATDesc = "New";
			elseif($PRJSTAT == 1) $PRJSTATDesc = "Confirm";		
		
			if($myDateProj == '0000-00-00')
			{
				$sqlX = "SELECT PRJDATE
						FROM sd_tproject WHERE PRJCODE = '$prjcode'";
				$result = $this->db->query($sqlX)->result();
				foreach($result as $rowx) :
					$PRJDATE		= $rowx->PRJDATE;
				endforeach;
			}
					
		$isActif = $row->PRJSTAT;
		if($isActif == 1)
		{
			$isActDesc = 'Active';
		}
		else
		{
			$isActDesc = 'In Active';
		}
		
		$PRJOWN			= $row->PRJOWN;
		$ownerName		= "";
			$sqlX 		= "SELECT own_Title, own_Name
							FROM sd_towner WHERE own_Code = '$PRJOWN'";
			$result 	= $this->db->query($sqlX)->result();
			foreach($result as $rowx) :
				$own_Title		= $rowx->own_Title;
				$own_Name		= $rowx->own_Name;
				if($own_Title != '')
				{
					$ownerName	= "$own_Title $own_Name";
				}
			endforeach;
				
		$theProjCode = $row->PRJCODE;
		$url_updt	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/listproject_sd'),'update', array('param' => $theProjCode));
	?>
	<tr>
        <?php /*?><td style="text-align:center"> <?php print '<input name="chkDetail" id="chkDetail" type="checkbox" value="'.$row->proj_Number.'" />'; ?> </td>
        <td> <?php print $myNewNo; ?>. </td><?php */?>
        <td> <input type="radio" name="chkDetail" id="chkDetail" value="<?php echo $PRJCODE;?>" <?php if($PRJCODE == $selProject) { ?> checked <?php } ?> onclick="getValueNo(this.value);" /> </td>
        <td nowrap> <?php print anchor("$url_updt",$PRJCODE,array('class' => 'update')).' '; ?> </td>
        <td nowrap> <?php print $PRJNAME; ?> </td>
        <td > <?php print $ownerName; ?> </td>
        <td nowrap> <?php print $PRJCNUM; ?> </td>
        <td style="text-align:right" nowrap> <?php print number_format($PRJCOST, $decFormat); ?>&nbsp;</td>
        <td nowrap> <?php print date('d M Y', strtotime($PRJDATE)); ?> </td>
        <td nowrap> <?php print date('d M Y', strtotime($PRJDATE)); ?> </td>
        <td nowrap> <?php print $isActDesc; ?> </td>
    </tr>
    <?php 
	endforeach; 
	}
	else
	{
	?>
		<tr>
            <td colspan="12" style="text-align:center"> --- None ---</td>
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
        <input type="hidden" name="myProjCode" id="myProjCode" value="<?php echo $selProject; ?>" />
        <input type="submit" name="btnDelete" id="btnDelete" class="button_css" value="Change Status" style="display:none" />
		<?php
			if($Emp_DeptCode == 1 || $Emp_DeptCode == 6 || $Emp_DeptCode == 99)
			{
        		echo anchor($secAddURL,'<input type="button" name="btnSubmit" id="btnSubmit" class="button_css" value="Add New Project" />');
			}
			if($Emp_DeptCode == 1 || $Emp_DeptCode == 3 || $Emp_DeptCode == 4)
			{
		?>
        	<input type="button" name="btnInputDetProj" id="btnInputDetProj" class="button_css" value="Progress Input" onclick="vInpProjDet();" />
        <?php
			}
			if($Emp_DeptCode == 1 || $Emp_DeptCode == 2 || $Emp_DeptCode == 5)
			{
		?>
        	<input type="button" name="btnPrintDoc" id="btnPrintDoc" class="button_css" value="View Project Performance" onclick="vProjPerform();" />
        <?php
			}
		?>
        </td>
    </tr>
    <tr height="20">
        <td colspan="3"><hr /></td>
    </tr>
</table>
</form>
<?php
	$urlProjInDet	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/listproject_sd'),'vInpProjDet', array('param' => $selProject));
	$urlProjPerF	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/listproject_sd'),'vProjPerform', array('param' => $selProject));
?>
<script>
	function getValueNo(thisVal)
	{
		myValue = thisVal;
		document.getElementById('myProjCode').value = myValue;
		document.getElementById('selProject').value = myValue;
		chooseProject(thisVal);
	}
	
	function chooseProject(thisVal)
	{
		document.frmselect.submit.click();
	}
		
	function vProjPerform()
	{
		myVal = document.getElementById('myProjCode').value;
		
		if(myVal == '')
		{
			alert('Please select one of Project Code.')
			return false;
		}
		var url = '<?php echo $urlProjPerF; ?>';
		title = 'Select Item';		
		
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+screen.width+', height='+screen.height);
	}
		
	function vInpProjDet()
	{
		myVal = document.getElementById('myProjCode').value;
		
		if(myVal == '')
		{
			alert('Please select one of Project Code.')
			return false;
		}
		var url = '<?php echo $urlProjInDet; ?>';
		title = 'Select Item';		
		w = 900;
		h = 600;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
</script>
