<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 Mei 2018
 * File Name	= v_item_view_xl.php
 * Location		= -
*/
$dlTime = date('YmdHis');

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=coatempl_$dlTime.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
    <?php
        $sql        = "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND isHO != 2
                            ORDER BY Account_Category, Account_Number ASC";
        $viewCOA    = $this->db->query($sql)->result();
    ?>
    <table width="100%" border="1">
        <tr>
            <td width="5%" nowrap>ID</td>
            <td width="5%" nowrap>ORD_ID</td>
            <td width="5%" nowrap>PRJCODE</td>
            <td width="5%" nowrap>PRJCODE_HO</td>
            <td width="5%" nowrap>Acc_ID</td>
            <td width="5%" nowrap>Account_Class</td>
            <td width="5%" nowrap>Account_Number</td>
            <td width="5%" nowrap>Account_NameEn</td>
            <td width="5%" nowrap>Account_NameId</td>
            <td width="5%" nowrap>Account_Category</td>                     <!-- 10 -->
            <td width="5%" nowrap>Account_Level</td>
            <td width="5%" nowrap>Acc_DirParent</td>
            <td width="5%" nowrap>Acc_ParentList</td>
            <td width="5%" nowrap>Acc_StatusLinked</td>
            <td width="5%" nowrap>Acc_Enable</td>
            <td width="5%" nowrap>Company_ID</td>
            <td width="5%" nowrap>Default_Acc</td>
            <td width="5%" nowrap>Currency_id</td>
            <td width="5%" nowrap>Calculated_Statement</td>
            <td width="5%" nowrap>Acc_MonthOpeningBalance</td>              <!-- 20 -->
            <td width="5%" nowrap>Acc_MonthDebet</td>
            <td width="5%" nowrap>Acc_MonthKredit</td>
            <td width="5%" nowrap>Base_OpeningBalance</td>
            <td width="5%" nowrap>Base_Debet</td>
            <td width="5%" nowrap>Base_Kredit</td>
            <td width="5%" nowrap>Base_OpeningBalance_tax</td>
            <td width="5%" nowrap>Base_Debet_tax</td>
            <td width="5%" nowrap>Base_Kredit_tax</td>
            <td width="5%" nowrap>Acc_MonthOpeningBalance_tax</td>
            <td width="5%" nowrap>Acc_MonthDebet_tax</td>                   <!-- 30 -->
            <td width="5%" nowrap>Acc_MonthKredit_tax</td>
            <td width="5%" nowrap>calculated_Statement_Tax</td>
            <td width="5%" nowrap>Match_Acc_ID</td>
            <td width="5%" nowrap>ChartCategory_BT</td>
            <td width="5%" nowrap>IsInterCompany</td>
            <td width="5%" nowrap>isCostComponent</td>
            <td width="5%" nowrap>BudgetGroup</td>
            <td width="5%" nowrap>isOnDuty</td>
            <td width="5%" nowrap>isFOHCost</td>
            <td width="5%" nowrap>LSTCOSTCENTER</td>                        <!-- 40 -->
            <td width="5%" nowrap>NEEDCC</td>
            <td width="5%" nowrap>AllowGEJ</td>
            <td width="5%" nowrap>Base_OpeningBalance2</td>
            <td width="5%" nowrap>Base_OpeningBalance_Tax2</td>
            <td width="5%" nowrap>Acc_MonthOpeningBalance2</td>
            <td width="5%" nowrap>Acc_MonthOpeningBalance_Tax2</td>
            <td width="5%" nowrap>Base_Kredit2</td>
            <td width="5%" nowrap>Base_Debet2</td>
            <td width="5%" nowrap>Base_Debet_tax2</td>
            <td width="5%" nowrap>Base_Kredit_tax2</td>                     <!-- 50 -->
            <td width="5%" nowrap>COGSReportID</td>
            <td width="5%" nowrap>Acc_Group</td>
            <td width="5%" nowrap>PatternGroupDisbursement</td>
            <td width="5%" nowrap>Link_Report</td>
            <td width="5%" nowrap>PatternGroupReceipt</td>
            <td width="5%" nowrap>isHO</td>
            <td width="5%" nowrap>isSync</td>
            <td width="5%" nowrap>syncPRJ</td>
            <td width="5%" nowrap>showCF</td>
            <td width="5%" nowrap>isLast</td>                               <!-- 60 -->
        </tr>
        <?php
            $ID     = 0;
            $i      = 0;
            $j      = 0;
            $TBL    = 0;
            foreach($viewCOA as $row) :
                $ID                             = $ID + 1;
                $ORD_ID                         = $row->ORD_ID;
                $PRJCODE                        = $row->PRJCODE;
                $PRJCODE_HO                     = $row->PRJCODE_HO;
                $Acc_ID                         = $row->Acc_ID;
                $Account_Class                  = $row->Account_Class;
                $Account_Number                 = $row->Account_Number;
                $Account_NameEn                 = $row->Account_NameEn;
                $Account_NameId                 = $row->Account_NameId;
                $Account_Category               = $row->Account_Category;                   // 10
                $Account_Level                  = $row->Account_Level;
                $Acc_DirParent                  = $row->Acc_DirParent;
                $Acc_ParentList                 = $row->Acc_ParentList;
                $Acc_StatusLinked               = $row->Acc_StatusLinked;
                $Acc_Enable                     = $row->Acc_Enable;
                $Company_ID                     = $row->Company_ID;
                $Default_Acc                    = $row->Default_Acc;
                $Currency_id                    = $row->Currency_id;
                $Calculated_Statement           = $row->Calculated_Statement;
                $Acc_MonthOpeningBalance        = $row->Acc_MonthOpeningBalance;            // 20
                $Acc_MonthDebet                 = $row->Acc_MonthDebet;
                $Acc_MonthKredit                = $row->Acc_MonthKredit;
                $Base_OpeningBalance            = $row->Base_OpeningBalance;
                $Base_Debet                     = $row->Base_Debet;
                $Base_Kredit                    = $row->Base_Kredit;
                $Base_OpeningBalance_tax        = $row->Base_OpeningBalance_tax;
                $Base_Debet_tax                 = $row->Base_Debet_tax;
                $Base_Kredit_tax                = $row->Base_Kredit_tax;
                $Acc_MonthOpeningBalance_tax    = $row->Acc_MonthOpeningBalance_tax;
                $Acc_MonthDebet_tax             = $row->Acc_MonthDebet_tax;                 // 30
                $Acc_MonthKredit_tax            = $row->Acc_MonthKredit_tax;
                $calculated_Statement_Tax       = $row->calculated_Statement_Tax;
                $Match_Acc_ID                   = $row->Match_Acc_ID;
                $ChartCategory_BT               = $row->ChartCategory_BT;
                $IsInterCompany                 = $row->IsInterCompany;
                $isCostComponent                = $row->isCostComponent;
                $BudgetGroup                    = $row->BudgetGroup;
                $isOnDuty                       = $row->isOnDuty;
                $isFOHCost                      = $row->isFOHCost;
                $LSTCOSTCENTER                  = $row->LSTCOSTCENTER;                      // 40
                $NEEDCC                         = $row->NEEDCC;
                $AllowGEJ                       = $row->AllowGEJ;
                $Base_OpeningBalance2           = $row->Base_OpeningBalance2;
                $Base_OpeningBalance_Tax2       = $row->Base_OpeningBalance_Tax2;
                $Acc_MonthOpeningBalance2       = $row->Acc_MonthOpeningBalance2;
                $Acc_MonthOpeningBalance_Tax2   = $row->Acc_MonthOpeningBalance_Tax2;
                $Base_Debet2                    = $row->Base_Debet2;
                $Base_Kredit2                   = $row->Base_Kredit2;
                $Base_Debet_tax2                = $row->Base_Debet_tax2;
                $Base_Kredit_tax2               = $row->Base_Kredit_tax2;                   // 50
                $COGSReportID                   = $row->COGSReportID;
                $Acc_Group                      = $row->Acc_Group;
                $PatternGroupDisbursement       = $row->PatternGroupDisbursement;
                $Link_Report                    = $row->Link_Report;
                $PatternGroupReceipt            = $row->PatternGroupReceipt;
                $isHO                           = $row->isHO;
                $isSync                         = $row->isSync;
                $syncPRJ                        = $row->syncPRJ;
                $showCF                         = $row->showCF;
                $isLast                         = $row->isLast;                             // 59
                ?>
                    <tr>
                        <td nowrap> <?php echo $ID; ?> </td>
                        <td nowrap> <?php echo $ORD_ID; ?> </td>
                        <td nowrap> <?php echo $PRJCODE; ?> </td>
                        <td nowrap> <?php echo $PRJCODE_HO; ?> </td>
                        <td nowrap> <?php echo $Acc_ID; ?> </td>
                        <td nowrap> <?php echo $Account_Class; ?> </td>
                        <td nowrap> <?php echo $Account_Number; ?> </td>
                        <td nowrap> <?php echo $Account_NameEn; ?> </td>
                        <td nowrap> <?php echo $Account_NameId; ?> </td>
                        <td nowrap> <?php echo $Account_Category; ?> </td>                     <!-- 10 -->
                        <td nowrap> <?php echo $Account_Level; ?> </td>
                        <td nowrap> <?php echo $Acc_DirParent; ?> </td>
                        <td nowrap> <?php echo $Acc_ParentList; ?> </td>
                        <td nowrap> <?php echo $Acc_StatusLinked; ?> </td>
                        <td nowrap> <?php echo $Acc_Enable; ?> </td>
                        <td nowrap> <?php echo $Company_ID; ?> </td>
                        <td nowrap> <?php echo $Default_Acc; ?> </td>
                        <td nowrap> <?php echo $Currency_id; ?> </td>
                        <td nowrap> <?php echo $Calculated_Statement; ?> </td>
                        <td nowrap> <?php echo $Acc_MonthOpeningBalance; ?> </td>              <!-- 20 -->
                        <td nowrap> <?php echo $Acc_MonthDebet; ?> </td>
                        <td nowrap> <?php echo $Acc_MonthKredit; ?> </td>
                        <td nowrap> <?php echo $Base_OpeningBalance; ?> </td>
                        <td nowrap> <?php echo $Base_Debet; ?> </td>
                        <td nowrap> <?php echo $Base_Kredit; ?> </td>
                        <td nowrap> <?php echo $Base_OpeningBalance_tax; ?> </td>
                        <td nowrap> <?php echo $Base_Debet_tax; ?> </td>
                        <td nowrap> <?php echo $Base_Kredit_tax; ?> </td>
                        <td nowrap> <?php echo $Acc_MonthOpeningBalance_tax; ?> </td>
                        <td nowrap> <?php echo $Acc_MonthDebet_tax; ?> </td>                   <!-- 30 -->
                        <td nowrap> <?php echo $Acc_MonthKredit_tax; ?> </td>
                        <td nowrap> <?php echo $calculated_Statement_Tax; ?> </td>
                        <td nowrap> <?php echo $Match_Acc_ID; ?> </td>
                        <td nowrap> <?php echo $ChartCategory_BT; ?> </td>
                        <td nowrap> <?php echo $IsInterCompany; ?> </td>
                        <td nowrap> <?php echo $isCostComponent; ?> </td>
                        <td nowrap> <?php echo $BudgetGroup; ?> </td>
                        <td nowrap> <?php echo $isOnDuty; ?> </td>
                        <td nowrap> <?php echo $isFOHCost; ?> </td>
                        <td nowrap> <?php echo $LSTCOSTCENTER; ?> </td>                        <!-- 40 -->
                        <td nowrap> <?php echo $NEEDCC; ?> </td>
                        <td nowrap> <?php echo $AllowGEJ; ?> </td>
                        <td nowrap> <?php echo $Base_OpeningBalance2; ?> </td>
                        <td nowrap> <?php echo $Base_OpeningBalance_Tax2; ?> </td>
                        <td nowrap> <?php echo $Acc_MonthOpeningBalance2; ?> </td>
                        <td nowrap> <?php echo $Acc_MonthOpeningBalance_Tax2; ?> </td>
                        <td nowrap> <?php echo $Base_Kredit2; ?> </td>
                        <td nowrap> <?php echo $Base_Debet2; ?> </td>
                        <td nowrap> <?php echo $Base_Debet_tax2; ?> </td>
                        <td nowrap> <?php echo $Base_Kredit_tax2; ?> </td>                     <!-- 50 -->
                        <td nowrap> <?php echo $COGSReportID; ?> </td>
                        <td nowrap> <?php echo $Acc_Group; ?> </td>
                        <td nowrap> <?php echo $PatternGroupDisbursement; ?> </td>
                        <td nowrap> <?php echo $Link_Report; ?> </td>
                        <td nowrap> <?php echo $PatternGroupReceipt; ?> </td>
                        <td nowrap> <?php echo $isHO; ?> </td>
                        <td nowrap> <?php echo $isSync; ?> </td>
                        <td nowrap> <?php echo $syncPRJ; ?> </td>
                        <td nowrap> <?php echo $showCF; ?> </td>
                        <td nowrap> <?php echo $isLast; ?> </td>                               <!-- 59 -->
                    </tr>
                <?php 
            endforeach;
        ?>
    </table>
</body>
</html>