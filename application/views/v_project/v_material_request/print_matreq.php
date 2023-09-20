<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 8 Mei 2015
 * File Name	= print_matreq.php
 * Location		= system\application\views\v_project\v_material_request\print_matreq.php
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
		<td colspan="3" class="style2">
        <div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
            <img src="<?php echo base_url().'images/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
            <a href="#" onClick="window.close();" class="button"> close </a>
        </div>
        </td>
  </tr>
	<tr>
		<td colspan="3" class="style2">&nbsp;</td>
  </tr>
</table>
</div>
<?php	
	$proj_ID = $default['proj_ID'];
	$MR_Number = $default['MR_Number'];
	$DocNumber = $default['MR_Number'];
	$MR_Date = $default['MR_Date'];
	$req_date = $default['req_date'];
	$latest_date = $default['latest_date'];
	$MR_Class = $default['MR_Class'];
	$MR_Type = $default['MR_Type'];
	$MR_DepID = $default['MR_DepID'];
	$MR_EmpID = $default['MR_EmpID'];
	$Vend_Code = $default['Vend_Code'];
	$MR_Notes = $default['MR_Notes'];
	$MR_Status = $default['MR_Status'];
	$Approval_Status = $default['Approval_Status'];
	$Patt_Year = $default['Patt_Year'];
	$lastPatternNumb1 = $default['Patt_Number'];
	$Memo_Revisi = $default['Memo_Revisi'];
	$Vend_Name = '';
	
	$sql = "SELECT proj_Number, proj_Code, proj_Name FROM tproject_header
			WHERE proj_ID = $proj_ID";
	$result1 = $this->db->query($sql)->result();
	foreach($result1 as $row) :
		$proj_Number = $row->proj_Number;
		$proj_Code = $row->proj_Code;
		$proj_Name = $row->proj_Name;
	endforeach;
	
	$sql2 = "SELECT Dept_ID, Dept_Name FROM tdepartment
			WHERE Dept_ID = $MR_DepID";
	$result2 = $this->db->query($sql2)->result();
	foreach($result2 as $row) :
		$Dept_ID = $row->Dept_ID;
		$Dept_Name = $row->Dept_Name;
	endforeach;
	
	$sql3 = "SELECT Emp_ID, First_name, Middle_Name, Last_Name FROM temployee
			WHERE Emp_ID = '$MR_EmpID'";
	$result3 = $this->db->query($sql3)->result();
	foreach($result3 as $row) :
		$Emp_ID = $row->Emp_ID;
		$First_name = $row->First_name;
		$Middle_Name = $row->Middle_Name;
		$Last_Name = $row->Last_Name;
	endforeach;
	
	$sql4 = "SELECT Vend_Code, Vend_Name, Vend_Address FROM tvendor
			WHERE Vend_Code = '$Vend_Code'";
	$result4 = $this->db->query($sql4)->result();
	foreach($result4 as $row) :
		$Vend_Code = $row->Vend_Code;
		$Vend_Name = $row->Vend_Name;
		$Vend_Address = $row->Vend_Address;
	endforeach;
?>
<table width="100%" border="0">
    <tr>
    	<td>
            <table width="100%" border="0">
                <tr>
                    <td width="17%" align="left">MR Number</td>
                    <td width="1%" align="left">:</td>
                    <td width="37%" align="left"> <?php echo $DocNumber; ?></td>
                    <td width="13%" align="left">Date</td>
                    <td width="32%" align="left">: &nbsp;
                        <?php
                            $date = new DateTime($MR_Date);
                            echo $date->format('d F Y');
                        ?>
                	</td>
                </tr>
                <tr>
                    <td align="left">Project</td>
                    <td align="left">:</td>
                    <td align="left">
                        <?php echo $proj_Name; ?>
                    </td>
                    <td align="left">Request Date</td>
                    <td align="left">: &nbsp;   
                        <?php
                            $date = new DateTime($req_date);
                            echo $date->format('d F Y');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">Request Class</td>
                    <td align="left">:</td>
                    <td align="left"><?php echo $MR_Class; ?></td>
                    <td align="left">Latest Date</td>
                    <td align="left">: &nbsp;  
                        <?php
                            $date = new DateTime($latest_date);
                            echo $date->format('d F Y');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">Requester Dep.</td>
                    <td align="left">:</td>
                    <td align="left">
                            <?php echo $Dept_Name; ?>
                    </td> 
                    <td align="left" id="labelProject1">Requester Name</td>
                    <td align="left" id="labelProject2">
                    :&nbsp;&nbsp;&nbsp;<?php echo $First_name; echo '&nbsp;'; echo $Middle_Name; echo '&nbsp;'; echo $Last_Name; ?>
                     </td>
                </tr>
                <tr>
                    <td align="left" valign="top">Notes</td>
                    <td align="left" valign="top">:</td>
                    <td align="left">
                    <?php 
                        if($MR_Notes == '')
                        {
                            echo " - ";
                        }
                        else
                        {
                            echo $MR_Notes;
                        }
                    ?>
                    </td> 
                    <td align="left" valign="top">Vendor Name</td>
                    <td align="left" valign="top">:&nbsp;&nbsp;
                    <?php
                        if($Vend_Name == '')
                        {
                            echo ' - ';
                        }
                        else
                        {
                            echo $Vend_Name; 
                        }
                    ?>
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top"> Approval Status</td>
                    <td align="left" valign="top">:</td>
                    <td align="left" valign="top">
                    <?php if($Approval_Status == 1) { ?> New <?php } ?>
                    <?php if($Approval_Status == 2) { ?> Awaiting <?php } ?>
                    <?php if($Approval_Status == 3) { ?> Approve <?php } ?>
                    <?php if($Approval_Status == 4) { ?> Revise <?php } ?>
                    <?php if($Approval_Status == 5) { ?> Reject <?php } ?>
                    </td> 
                  <td align="left" id="memoName" <?php if($Memo_Revisi == '') { ?> style="display:none" <?php } ?> valign="top">Memo</td>
                  <td align="left" id="memoBox" <?php if($Memo_Revisi == '') { ?> style="display:none" <?php } ?> valign="top">: <?php echo $Memo_Revisi; ?></td>
                </tr>
                <tr>
                    <td colspan="5" align="left" style="font-style:italic">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5" align="left">
                        <table width="100%" border="1" id="tbl" rules="all" >
                            <tr>
                                <td width="2%" height="25" rowspan="2" style="text-align:center">No.</td>
                              <td width="13%" rowspan="2" style="text-align:center">Item Code</td>
                              <td width="24%" rowspan="2" style="text-align:center">Item Name</td>
                              <td colspan="2" style="text-align:center">Request Now</td>
                                <td colspan="2" style="text-align:center">Unit of Material</td>
                                <td width="17%" rowspan="2" style="text-align:center">Remarks</td>
                          </tr>
                            <tr>
                                <td style="text-align:center;">Qty 1</td>
                                <td style="text-align:center;">Qty 2</td>
                                <td style="text-align:center;">Primary</td>
                                <td style="text-align:center;">Secondary</td>
                            </tr>
                            <?php
                            if($task == 'edit')
                            {
                                $sql		= "SELECT A.MR_Number,A.Item_code,A.request_qty1,A.request_qty2,A.projmat_qty1,A.projmat_qty2,A.unit_type_id1,A.unit_type_id2, A.remarks,
                                                B.Item_Name,B.serialNumber,B.itemConvertion,
                                                C.Unit_Type_Code,C.Unit_Type_Name
                                                FROM tproject_mrdetail A
                                                INNER JOIN TItem B ON A.Item_code = B.Item_code
                                                INNER JOIN tunittype C ON C.unit_type_id = A.unit_type_id1
                                                WHERE MR_Number = '$DocNumber' ORDER BY A.Item_code ASC";
                                // count data
                                    $resultCount = $this->db->where('MR_Number', $DocNumber);
                                    $resultCount = $this->db->count_all_results('tproject_mrdetail');
                                // End count data
                                $result = $this->db->query($sql)->result();
                                $i = 0;
                                if($resultCount > 0)
                                {
                                    foreach($result as $row) :
                                    $currentRow  = ++$i;
                                    $Item_code1 = $row->Item_code;
                                ?>
                                    <tr>
                                        <td width="2%" height="25" style="text-align:left">&nbsp;<?php echo $currentRow; ?>.</td>
                                        <td width="13%" style="text-align:left">
                                            <?php print $row->Item_code; ?>                                </td>
                                        <td width="24%" style="text-align:left" nowrap> <?php print $row->Item_Name; ?> </td>
                                        <?php
                                        $sqlgetQty	= "SELECT A.PPMat_Qty, A.PPMat_Qty2, A.request_qty
                                                        FROM tprojplan_material A
                                                        INNER JOIN TItem B ON A.Item_code = B.Item_code
                                                        WHERE A.Item_code = '$Item_code1'";
                                        $resultgetQty = $this->db->query($sqlgetQty)->result();
                                        foreach($resultgetQty as $nRow) :
                                            $PPMat_Qty = $nRow->PPMat_Qty;
                                            $PPMat_Qty2 = $nRow->PPMat_Qty2;
                                            $request_qty = $nRow->request_qty;
                                        endforeach;
                                        ?>
                                    <td width="7%" style="text-align:right" nowrap>
                                        <?php print number_format($row->request_qty1, $decFormat); ?>&nbsp;</td>
                                    <td width="8%" style="text-align:right" nowrap>
                                        <?php print number_format($row->request_qty2, $decFormat); ?>&nbsp;</td>
                                    <td width="6%" style="text-align:center" nowrap>
                                        <?php print $row->Unit_Type_Code; ?>                            </td>
                                    <td width="7%" style="text-align:center" nowrap>
                                        <?php print $row->Unit_Type_Code; ?>                          	</td>
                                    <td width="17%" style="text-align:left">
                                        &nbsp;<?php print $row->remarks; ?>                       		</td>
                                    </tr>
                                <?php
                                    endforeach;
                                }
                            }
                            if($task != 'add')
                            {
                                ?>
                                    <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                <?php
                            }
                            ?>
                        </table>
                  </td>
                </tr>
                <tr>
                    <td colspan="5" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5" align="left">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="5" align="left">
                  <table width="100%" border="0">
                    <tr>
                      <td width="20%" style="text-align:center">Dibuat Oleh,</td>
                      <td width="57%">&nbsp;</td>
                      <td width="20%" style="text-align:center">Mengetahui,</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="text-align:center">( .....................................)</td>
                      <td>&nbsp;</td>
                      <td style="text-align:center">( .....................................)</td>
                    </tr>
                  </table></td>
                </tr> 
            </table>        
        </td>
    </tr>
</table>

</body>
</html>
