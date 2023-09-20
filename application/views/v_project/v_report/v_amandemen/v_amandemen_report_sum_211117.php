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
            width: 42cm;
            min-height: 29.70cm;
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
            /*size: A3;*/
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
                        <h3><?php //echo $h1_title; ?></h3>
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
                <!-- Header -->
                    <tr align="center" style="border-top: solid; border-right: solid; border-left: solid;">
                        <td rowspan = "2" style="border-bottom: solid; border-left: solid;">No.</td>
                        <td rowspan = "2" style="border-bottom: solid;">Item Budget</td>
                        <td rowspan = "2" style="border-bottom: solid;">Nama Item Budget</td>
                        <td rowspan = "2" style="border-bottom: solid;">Nomor Amandemen</td>
                        <td rowspan = "2" style="border-bottom: solid;">Jenis Amandemen</td>
                        <td rowspan = "2" style="border-bottom: solid;">Keterangan</td>
                        <td rowspan = "2" style="border-bottom: solid;">Satuan</td>
                        <td colspan = "3">Nilai Amandemen</td>
                        <td colspan = "3">Budget Awal</td>
                        <td colspan = "3" style="border-right: solid;">Budget Setelah Amandemen</td>
                    </tr>
                    <tr align="center">
                        <td style="border-bottom: solid;">Volume</td>
                        <td style="border-bottom: solid;">Harga Satuan</td>
                        <td style="border-bottom: solid;">Jumlah Harga</td>
                        <td style="border-bottom: solid;">Volume</td>
                        <td style="border-bottom: solid;">Harga Satuan</td>
                        <td style="border-bottom: solid;">Jumlah Harga</td>
                        <td style="border-bottom: solid;">Volume</td>
                        <td style="border-bottom: solid;">Harga Satuan</td>
                        <td style="border-bottom: solid; border-right: solid;">Jumlah Harga</td>
                    </tr>
                    <tr>
                        <td colspan="16" style="line-height: 2px; border-left: hidden; border-right: hidden;">&nbsp;</td>
                    </tr>
                <!-- End Header -->
                <?php
                    $theRow         = 0;
                    $TAMD_AMOUNT    = 0;
                    $sqlAMC         = "tbl_item A
                                            INNER JOIN tbl_amd_detail B ON B.ITM_CODE = A.ITM_CODE
                                                AND B.PRJCODE = '$PRJCODE'
                                            INNER JOIN tbl_amd_header C ON C.AMD_NUM = B.AMD_NUM
                                                AND C.PRJCODE = '$PRJCODE'
                                        WHERE A.PRJCODE = '$PRJCODE' AND C.AMD_STAT = 3
                                            AND (C.AMD_DATE BETWEEN '$Start_Date' AND '$End_Date')";
                    $resAMC         = $this->db->count_all($sqlAMC);
                    if($resAMC > 0)
                    {
                        $sqlAM      = "SELECT A.PRJCODE, A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_VOLMBG, A.ITM_PRICE, 
                                            A.ITM_LASTP, A.ITM_TOTALP,
                                            SUM(B.AMD_VOLM) AS ADDVOLM, SUM(B.AMD_TOTAL) AS ADDCOST, A.ADDMVOLM, A.ADDMCOST,
                                            C.AMD_CODE, C.AMD_CATEG, C.AMD_JOBDESC
                                        FROM tbl_item A
                                            INNER JOIN tbl_amd_detail B ON B.ITM_CODE = A.ITM_CODE
                                                AND B.PRJCODE = '$PRJCODE'
                                            INNER JOIN tbl_amd_header C ON C.AMD_NUM = B.AMD_NUM
                                                AND C.PRJCODE = '$PRJCODE'
                                        WHERE A.PRJCODE = '$PRJCODE' AND C.AMD_STAT = 3
                                            AND (C.AMD_DATE BETWEEN '$Start_Date' AND '$End_Date')
                                            GROUP BY A.ITM_CODE";
                        $resAM      = $this->db->query($sqlAM)->result();
                        foreach($resAM as $rowAM):
                            $theRow     = $theRow + 1;
                            $PRJCODE    = $rowAM->PRJCODE;
                            $ITM_CODE   = $rowAM->ITM_CODE;
                            $ITM_NAME   = $rowAM->ITM_NAME;
                            $ITM_UNIT   = $rowAM->ITM_UNIT;
                            $ITM_VOLMBG = $rowAM->ITM_VOLMBG;
                            $ITM_PRICE  = $rowAM->ITM_PRICE;
                            $ITM_LASTP  = $rowAM->ITM_LASTP;
                            $ITM_TOTALP = $ITM_VOLMBG * $ITM_PRICE;
                            $ADDVOLM    = $rowAM->ADDVOLM;
                            $ADDCOST    = $rowAM->ADDCOST;
                            $ADDVOLMA   = $ADDVOLM;
                            if($ADDVOLM == '' OR $ADDVOLM == 0)
                                $ADDVOLMA   = 1;
                            $ADDPRICE   = $ADDCOST / $ADDVOLMA;
                            $ADDMVOLM   = $rowAM->ADDMVOLM;
                            $ADDMCOST   = $rowAM->ADDMCOST;
                            $TADD_VOL   = $ITM_VOLMBG + $ADDVOLM - $ADDMVOLM;
                            $TADD_AMN   = $ITM_TOTALP + $ADDCOST - $ADDMCOST;
                            $TADD_VOLA  = $TADD_VOL;
                            if($TADD_VOL == '' OR $TADD_VOL == 0)
                                $TADD_VOLA  = 1;
                            $TADDPRICE  = $TADD_AMN / $TADD_VOLA;

                            $AMD_CODE   = $rowAM->AMD_CODE;
                            $AMD_CATEG  = $rowAM->AMD_CATEG;
                            $AMD_JOBDESC= $rowAM->AMD_JOBDESC;
                            ?>
                            <tr>
                                <td style="text-align: center;"><?php echo $theRow; ?></td>
                                <td><?php echo $ITM_CODE; ?></td>
                                <td><?php echo $ITM_NAME; ?></td>
                                <td><?php echo $AMD_CODE; ?></td>
                                <td style="text-align: center;"><?php echo $AMD_CATEG; ?></td>
                                <td><?php echo $AMD_JOBDESC; ?></td>
                                <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                <td style="text-align: right;"><?php echo number_format($ADDVOLM, 2); ?></td>
                                <td style="text-align: right;"><?php echo number_format($ADDPRICE, 2); ?></td>
                                <td style="text-align: right;"><?php echo number_format($ADDCOST, 2); ?></td>
                                <td style="text-align: right;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
                                <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                <td style="text-align: right;"><?php echo number_format($ITM_TOTALP, 2); ?></td>
                                <td style="text-align: right;"><?php echo number_format($TADD_VOL, 2); ?></td>
                                <td style="text-align: right;"><?php echo number_format($TADDPRICE, 2); ?></td>
                                <td style="text-align: right;"><?php echo number_format($TADD_AMN, 2); ?></td>
                            </tr>
                            <?php
                        endforeach;
                    }
                ?>
                <!-- footer -->
                <tr>
                    <td colspan="7">&nbsp;</td>
                    <td colspan="3">&nbsp;</td>
                    <td colspan="3">&nbsp;</td>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <!-- End footer -->
            </table>
        </div>
    </div>

</body>

</html>