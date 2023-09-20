<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Maret 2019
 * File Name	= v_projinv_report.php
 * Location		= -
*/

if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

date_default_timezone_set("Asia/Jakarta");
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$PRJNAME	= '';
$PRJCOST	= 0;
$PRJDATE	= date('Y/m/d');
$PRJDATE_CO	= date('Y/m/d');
$sqlPRJ		= "SELECT PRJDATE, PRJNAME, PRJDATE_CO, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
	$PRJNAME	= $rowPRJ->PRJNAME;
	$PRJCOST	= $rowPRJ->PRJCOST;
	$PRJDATE	= date('Y/m/d', strtotime($rowPRJ->PRJDATE));
	$PRJDATE_CO	= date('Y/m/d', strtotime($rowPRJ->PRJDATE_CO));
endforeach;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?=$h1_title?></title>
        <style>
            body {
                margin: 0;
                padding: 0;
                background-color: #FAFAFA;
                font: 12pt Arial, Helvetica, sans-serif;
            }

            * {
                box-sizing: border-box;
                -moz-box-sizing: border-box;
            }

            .page {
                width: 35.7cm;
                min-height: 21cm;
                padding-left: 1cm;
                padding-right: 1cm;
                padding-top: 1cm;
                padding-bottom: 1cm;
                margin: 0.5cm auto;
                border: 1px #D3D3D3 solid;
                border-radius: 5px;
                background: white;
                background-size: 400px 200px !important;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            }

            @page {
                /*size: A4;*/
                margin: 0;
            }

            @media print {

                @page{size: landscape;}
                .page {
                    margin: 0;
                    border: initial;
                    border-radius: initial;
                    width: initial;
                    min-height: initial;
                    box-shadow: initial;
                    background: initial;
                    page-break-after: always;
                }
            }
        </style>
    </head>

    <body>
        <div class="page">
            <div class="print_title">
                <table width="100%" border="0" style="size:auto">
                    <tr>
                        <td><img src="<?= base_url('assets/AdminLTE-2.0.5/dist/img/compLog/NKES/compLog.png') ?>" alt="" width="181" height="44"></td>
                        <td>
                            <h3><?php // $h1_title?></h3>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="print_body" style="padding-top: 10px; font-size: 14px;">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="100" nowrap valign="top">Nama Laporan</td>
                        <td width="10">:</td>
                        <td><?php echo "$h1_title"; ?></span></td>
                    </tr>
                    <tr style="text-align:left; font-style:italic; display:none">
                        <td width="100" nowrap valign="top">Periode</td>
                        <td width="10">:</td>
                        <td><?php //echo "$StartDate s.d. $EndDate"; ?></span></td>
                    </tr>
                    <tr>
                        <td width="100" nowrap valign="top">Kode Proyek</td>
                        <td width="10">:</td>
                        <td><?php echo "$PRJCODE"; ?></span></td>
                    </tr>
                    <tr>
                        <td nowrap valign="top">Nama Proyek</td>
                        <td>:</td>
                        <td><span class="style2"><?php echo $PRJNAME;?></span></td>
                    </tr>
                    <tr>
                        <td nowrap valign="top">Tgl. Cetak</td>
                        <td>:</td>
                        <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                    </tr>
                </table>
            </div>
            <div class="print_content" style="padding-top: 5px; font-size: 12px;">
                <table width="100%" border="1" rules="all">
                    <tr style="background:#CCCCCC">
                        <th width="3%" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO</th>
                        <th colspan="6" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000;">FAKTUR / INVOICE</th>
                        <th colspan="3" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000;">FAKTUR PAJAK</th>
                        <th colspan="3" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000;">STATUS INVOICE</th>
                        <th colspan="3" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000">OUTSTANDING</th>
                    </tr>
                    <tr style="background:#CCCCCC">
                        <th width="8%" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000;">TANGGAL</th>
                        <th width="9%" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000;">NOMOR</th>
                        <th width="16%" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000;">NILAI (NON PPN)</th>
                        <th width="8%" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000;">NILAI (PPN)</th>
                        <th width="4%" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000;">PROGRESS</th>
                        <th width="8%" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000;">PROGRESS (%)</th>
                        <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">TANGGAL</th>
                        <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">NO. SERI</th>
                        <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">JUMLAH</th>
                        <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">J. TEMPO</th>
                        <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">TGL. BAYAR</th>
                        <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">JUMLAH</th>
                        <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">J. TEMPO</th>
                        <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">HARI</th>
                        <th width="16%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;  border-right-width:2px; border-right-color:#000">JUMLAH</th>
                    </tr>
                    <tr style="line-height:1px; border-left:hidden; border-right:hidden">
                        <td colspan="16" nowrap style="text-align:center; border-bottom-width:2px; border-bottom-color:#000">&nbsp;</td>
                    </tr>
                    <?php
    			   		$theRow			= 0;
    					$TAMD_AMOUNT	= 0;
    				   	$sqlAMC			= "tbl_projinv_header A
    											LEFT JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
    										WHERE A.PRJCODE = '$PRJCODE' AND A.PINV_STAT = 3";
    					$resAMC			= $this->db->count_all($sqlAMC);
    					if($resAMC > 0)
    					{
                            $TOTPROGPER = 0;
                            $TOTPROGVAL = 0;
    						$sqlAM		= "SELECT A.*, B.own_Title, B.own_Name
    										FROM tbl_projinv_header A
    											LEFT JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
    										WHERE A.PRJCODE = '$PRJCODE' AND A.PINV_STAT = 3
    											ORDER BY A.PINV_DATE ASC";
    						$resAM		= $this->db->query($sqlAM)->result();
    						foreach($resAM as $rowPRJINV):
    							$theRow			= $theRow + 1;
    							$PINV_CODE		= $rowPRJINV->PINV_CODE;
    							$PINV_MANNO		= $rowPRJINV->PINV_MANNO;
    							$PINV_STEP		= $rowPRJINV->PINV_STEP;
    							$PINV_CAT		= $rowPRJINV->PINV_CAT;
    							$PINV_SOURCE	= $rowPRJINV->PINV_SOURCE;
    							$OWN_CODE		= $rowPRJINV->PINV_OWNER;
    							$OWN_TITLE		= $rowPRJINV->own_Title;
    							$OWN_NAME		= $rowPRJINV->own_Name;
    							$PINV_DATE		= $rowPRJINV->PINV_DATE;
    							$PINV_ENDDATE	= $rowPRJINV->PINV_ENDDATE;
    							$PINV_RETVAL	= $rowPRJINV->PINV_RETVAL;
    							$PINV_RETCUTP	= $rowPRJINV->PINV_RETCUTP;
    							$PINV_RETCUT	= $rowPRJINV->PINV_RETCUT;
    							$PINV_DPPER		= $rowPRJINV->PINV_DPPER;
    							$PINV_DPVAL		= $rowPRJINV->PINV_DPVAL;
    							$PINV_DPVALPPn	= $rowPRJINV->PINV_DPVALPPn;
    							$PINV_DPBACK	= $rowPRJINV->PINV_DPBACK;
    							$PINV_DPBACKPPn	= $rowPRJINV->PINV_DPBACKPPn;
    							$PINV_PROG		= $rowPRJINV->PINV_PROG;
    							$PINV_PROGVAL	= $rowPRJINV->PINV_PROGVAL;
    							$PINV_PROGVALPPn= $rowPRJINV->PINV_PROGVALPPn;
    							$PINV_PROGAPP	= $rowPRJINV->PINV_PROGAPP;
    							$PINV_PROGAPPVAL= $rowPRJINV->PINV_PROGAPPVAL;
    							$PINV_VALADD	= $rowPRJINV->PINV_VALADD;
    							$PINV_VALADDPPn	= $rowPRJINV->PINV_VALADDPPn;
    							$PINV_VALBEF	= $rowPRJINV->PINV_VALBEF;
    							$PINV_VALBEFPPn	= $rowPRJINV->PINV_VALBEFPPn;
    							$PINV_AKUMNEXT	= $rowPRJINV->PINV_AKUMNEXT;
    							$PINV_TOTVAL	= $rowPRJINV->PINV_TOTVAL;
    							$PINV_TOTVALPPn	= $rowPRJINV->PINV_TOTVALPPn;
    							$PINV_TOTVALPPh	= $rowPRJINV->PINV_TOTVALPPh;
    							$GPINV_TOTVAL	= $rowPRJINV->GPINV_TOTVAL;
    							$PINV_NOTES		= $rowPRJINV->PINV_NOTES;
    							$PINV_PAIDAM	= $rowPRJINV->PINV_PAIDAM;

                                $PINV_PAIDDATE  = $rowPRJINV->PINV_PAIDDATE;
                                $PINV_TAXDATE   = $rowPRJINV->PINV_TAXDATE;
                                $PINV_TAXNO     = $rowPRJINV->PINV_TAXNO;


                                $TOTPROGPER     = $TOTPROGPER + $PINV_PROG;
                                $TOTPROGVAL     = $TOTPROGVAL + $PINV_PROGVAL;
                                //$REM_AMOUNT    = $GPINV_TOTVAL - $PINV_PAIDAM;
    							$REM_AMOUNT		= $PINV_PROGVAL - $PINV_PAIDAM;
    						?>
    							<tr>
                                    <td nowrap style="text-align:center;border-left-width:2px; border-left-color:#000"><?php echo $theRow; ?>.</td>
                                    <td nowrap style="text-align:left;"><?php echo $PINV_DATE; ?></td>
                                    <td nowrap style="text-align:left;"><?php echo $PINV_MANNO; ?></td>
                                  	<td nowrap style="text-align:right;"><?php echo number_format($PINV_PROGVAL, 2); ?></td>
                                    <td nowrap style="text-align:right;"><?php echo number_format($PINV_PROGVALPPn, 2); ?></td>
                                  	<td nowrap style="text-align:right;"><?php echo number_format($TOTPROGVAL, 2); ?></td>
                                  	<td nowrap style="text-align:right;"><?php echo number_format($TOTPROGPER, 2); ?></td>
                                    <td nowrap style="text-align:center;"><?php echo $PINV_PAIDDATE; ?></td>
                                    <td nowrap style="text-align:right;"><?php echo $PINV_TAXNO; ?></td>
                                    <td nowrap style="text-align:right;">&nbsp;</td>
                                    <td nowrap style="text-align:center;"><?php echo $PINV_ENDDATE; ?></td>
                                    <td nowrap style="text-align:center;"><?php echo $PINV_PAIDDATE; ?></td>
                                    <td nowrap style="text-align:right;"><?php echo number_format($PINV_PAIDAM, 2); ?></td>
                                    <td nowrap style="text-align:center;"><?php echo $PINV_ENDDATE; ?></td>
                                    <td nowrap style="text-align:right;"><?php echo number_format(0, 2); ?></td>
                                    <td nowrap style="text-align:right; border-right-width:2px; border-right-color:#000">
    									<?php echo number_format($REM_AMOUNT, 2); ?>
                                    </td>
                                </tr>
                   			<?php
    						endforeach;
    					}
    					else
    					{
    						?>
                                <tr>
                                    <td colspan="16" nowrap style="text-align:center; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000">--- none ---</td>
                                </tr>
    						<?php
    					}
    				?>
                    <tr bgcolor="#CCCCCC" style="font-weight:bold">
                        <td colspan="16" nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000">&nbsp;</td>
                   </tr>
                </table>
            </div>
        </div>
    </body>
</html>