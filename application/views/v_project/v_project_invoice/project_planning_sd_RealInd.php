<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 26 Januari 2016
 * File Name	= project_planning.php
 * Location		= -
*/
?>
<div class="HCSSTableGenerator">
    <table width="100%" border="0">
        <tr height="20">
            <td colspan="3"><b><?php echo $h2_title; ?></b></td>
        </tr>
    </table>
</div>

<?php
	// Searching Function
	$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
	$DefProjCode 			= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
	$selSearchproj_Code		= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
	$selSearchType1      	= $this->session->userdata['dtSessSrc1']['selSearchType'];
	$txtSearch1        		= $this->session->userdata['dtSessSrc1']['txtSearch'];
		
	$dataSessSrc = array(
			'selSearchproj_Code' => $selSearchproj_Code,
			'selSearchType' => $this->input->post('selSearchType'),
			'txtSearch' => $this->input->post('txtSearch'));
	$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
	$this->session->set_userdata('dtSessSrc2', $dataSessSrc);

	if($selSearchproj_Code == '')
	{
		$selSearchproj_Code= $DefProjCode;
	}
	
	// Project List
	$sqlPLC	= "sd_tproject";
	$resPLC	= $this->db->count_all($sqlPLC);
	
	$sqlPL 	= "SELECT proj_Number, PRJCODE, PRJNAME
				FROM sd_tproject WHERE PRJCODE IN (SELECT proj_Code FROM thrmemployee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY PRJNAME";
	$resPL	= $this->db->query($sqlPL)->result();
?>

<form name="frmsrch" action="<?php print $srch_url;?>" method=POST> 
    <table width="100%" border="0">
        <tr>
            <td> 
                <select name="selSearchType" id="selSearchType" class="listmenu">
                    <option value="ProjNumber" <?php if($selSearchType1 == 'ProjNumber') { ?> selected <?php } ?>>Project Code</option>
                    <option value="ProjName" <?php if($selSearchType1 == 'ProjName') { ?> selected <?php } ?>>Project Name</option>
                </select>
                <input type="text" name="txtSearch" id="txtSearch" class="textbox"value="<?php echo $txtSearch1; ?>" />
                <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                <?php echo anchor('c_project/material_request/','<input type="button" name="btnShowAll" id="btnShowAll" class="button_css" value=" Show All " />');?>
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

<script>
	function chooseProject(thisVal)
	{
		proj_Code	= thisVal.value;
		document.frmsrch.submitSrch.click();
	}
</script>

<table width="100%" border="0">
    <tr height="20">
        <td colspan="3" style="text-align:right"><?php echo $pagination; ?></td>
    </tr>
</table>

<form action="" onsubmit="" method=POST>
<div class="CSSTableGenerator">
<table width="100%">
    <tr>
      <td width="3%">No.</td>
      <td width="10%" nowrap> Project Number</td>
      <td width="24%" nowrap>Project Code - Name</td>
      <td width="11%" nowrap>Project Manager</td>
      <td width="11%" nowrap>Date</td>
      <td width="27%">Customer Name</td>
      <td width="8%" nowrap>Start Date</td>
      <td width="6%" nowrap>End Date</td>
      <?php /*?><td width="12%">Project</td><?php */?>
      <?php /*?><td width="10%">Notes</td><?php */?>
    </tr>
	<?php 
	$i = 0;
	if($recordcount >0)
	{
		foreach($vewproject as $row) : 			
			$empID			= '';
			$proj_Number	= $row->proj_Number;
			$PRJCODE		= $row->PRJCODE;
			$PRJCNUM		= $row->PRJCNUM; 
			$PRJNAME		= $row->PRJNAME;
			$PRJDATE		= $row->PRJDATE;
			$PRJEDAT		= $row->PRJEDAT;
			$PRJSTAT		= $row->PRJSTAT;
			if($PRJSTAT == 0) $PRJSTATDesc = "New";
			elseif($PRJSTAT == 1) $PRJSTATDesc = "Confirm";
			$myNewNo1 = ++$i;
			$myNewNo = $moffset + $myNewNo1;
			
			$secURLPI	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_invoice_sd'),'get_last_ten_projinvRealINV', array('param' => $PRJCODE));
			?>
	<tr>
        <td style="text-align:center"> <?php print $myNewNo; ?>. </td>
        <td> <?php print anchor("$secURLPI",$PRJCODE,array('class' => 'update')).' '; ?> </td>
        <td nowrap> <?php print "$PRJNAME"; ?> </td>
        <td> <?php print ""; ?> </td>
        <td> <?php print $PRJDATE; ?> </td>
        <td> <?php print ""; ?> </td>
        <td> <?php print $PRJDATE; ?> </td>
        <td> <?php print $PRJEDAT; ?> </td>
    </tr>
    <?php endforeach; 
	}
	else
	{
	?>
		<tr>
            <td colspan="11" style="text-align:center"> --- None ---</td>
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
</table>
</form>
