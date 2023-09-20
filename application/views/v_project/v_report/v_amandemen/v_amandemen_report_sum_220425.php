<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$h1_title?></title>
    <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/secure-03.png'; ?>" sizes="32x32">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
<style>

    body {
        font-family: sans-serif;
        font-size: 12px;
        margin: 30px;
    }
  
    h1 {
        font-weight: bold;
        font-size: 20pt;
        text-align: center;
    }
  
    table {
        border-collapse: collapse;
        width: 100%;
    }

    .header th {
        padding: 5px;
        font-size: 12px;
        background-color: #D9D9D9;
        border:1px solid #000000;
        border-bottom: 2px solid black;
        font-variant: small-caps;
        text-align: center;
        vertical-align: middle;
    }

    table thead {
        border: 2px solid black;
    }

    table tbody {
        font-size: 10px;
        border: 2px solid black;
    }

    .content td {
        padding: 4px;
        border:1px solid #000000;
        background-color: #D9D9D9;
    }
  
    .table td {
        padding: 3px 3px;
        border:1px solid #000000;
    }
  
    .text-center {
        text-align: center;
    }

    @media print {
        @page {
            size: landscape;
        }
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
<body class="page A3 landscape">
    <?php
        $this->db->from('tbl_item A');
        $this->db->join('tbl_amd_detail B', 'B.ITM_CODE = A.ITM_CODE AND B.PRJCODE = "'.$PRJCODE.'"', 'inner');
        $this->db->join('tbl_amd_header C', 'C.AMD_NUM = B.AMD_NUM AND C.PRJCODE = "'.$PRJCODE.'"', 'inner');
        $this->db->group_by('A.ITM_CODE');
        $this->db->where('A.PRJCODE', $PRJCODE);
        $this->db->where('C.AMD_STAT', 3);
        $this->db->where('C.AMD_DATE >=', $Start_Date);
        $this->db->where('C.AMD_DATE <=', $End_Date);
        $query = $this->db->get();

        $lnRow = $query->num_rows();
        $modLn = fmod($lnRow, 30);
        // echo $modLn;
        if($lnRow > 30) 
        {
            $TITM_VOLMBG = 0;
            $TITM_TOTALP = 0;
            $TADDVOLM    = 0;
            $TADDCOST    = 0;
            $GTADD_VOLM  = 0;
            $GTADD_COST  = 0;
            $lnRow = round($lnRow / 30);
            for($i = 0; $i < $lnRow; $i++):
                $this->db->select('A.PRJCODE, A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_VOLMBG, A.ITM_PRICE, A.ITM_LASTP, A.ITM_TOTALP, SUM(B.AMD_VOLM) AS ADDVOLM, SUM(B.AMD_TOTAL) AS ADDCOST, A.ADDMVOLM, A.ADDMCOST, C.AMD_CODE, C.AMD_CATEG, C.AMD_JOBDESC');
                $this->db->from('tbl_item A');
                $this->db->join('tbl_amd_detail B', 'B.ITM_CODE = A.ITM_CODE AND B.PRJCODE = "'.$PRJCODE.'"', 'inner');
                $this->db->join('tbl_amd_header C', 'C.AMD_NUM = B.AMD_NUM AND C.PRJCODE = "'.$PRJCODE.'"', 'inner');
                $this->db->group_by('A.ITM_CODE')->limit(30, $i*30);
                $this->db->where('A.PRJCODE', $PRJCODE);
                $this->db->where('C.AMD_STAT', 3);
                $this->db->where('C.AMD_DATE >=', $Start_Date);
                $this->db->where('C.AMD_DATE <=', $End_Date);
                $getQuery = $this->db->get();
                ?>
                    <section class="sheet padding-10mm">
                        <div class="logo" style="padding-bottom: 10px;">
                            <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg" alt="" width="190" height="44">
                        </div>
                        <table width="100%" style="font-size: 12px; font-weight: bold; border: hidden;">
                            <tr>
                                <td width="100" nowrap valign="top">Nama Laporan</td>
                                <td width="10">:</td>
                                <td><?php echo "$h1_title"; ?></td>
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
                        <div class="header" style="padding-bottom: 5px;"></div>
                        <table width="100%" border="1">
                            <thead>
                                <tr class="header">
                                    <th rowspan = "2">No.</th>
                                    <th rowspan = "2" style="width: 80px;">Item Budget</th>
                                    <th rowspan = "2" style="width: 170px;">Nama Item Budget</th>
                                    <th rowspan = "2" style="width: 120px;">Nomor Amandemen</th>
                                    <th rowspan = "2" style="width: 50px;">Jenis Amandemen</th>
                                    <th rowspan = "2" style="width: 170px;">Keterangan</th>
                                    <th rowspan = "2">Satuan</th>
                                    <th colspan = "3" style="background-color: gray;">Nilai Amandemen</th>
                                    <th colspan = "3" style="background-color: white;">Budget Awal</th>
                                    <th colspan = "3" style="background-color: gray;">Budget Setelah Amandemen</th>
                                </tr>
                                <tr class="header">
                                    <th style="background-color: gray;">Volume</th>
                                    <th style="background-color: gray;">Harga Satuan</th>
                                    <th style="background-color: gray;">Jumlah Harga</th>
                                    <th style="background-color: white;">Volume</th>
                                    <th style="background-color: white;">Harga Satuan</th>
                                    <th style="background-color: white;">Jumlah Harga</th>
                                    <th style="background-color: gray;">Volume</th>
                                    <th style="background-color: gray;">Harga Satuan</th>
                                    <th style="background-color: gray;">Jumlah Harga</th>
                                </tr>
                                <tr>
                                    <th colspan="16" style="line-height: 2px; border-left: hidden; border-right: hidden; padding: 0px">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody class="content">
                                <?php
                                    $theRow = $i*30;
                                    $TITM_VOLMBG = $TITM_VOLMBG++;
                                    $TITM_TOTALP = $TITM_TOTALP++;
                                    $TADDVOLM    = $TADDVOLM++;
                                    $TADDCOST    = $TADDCOST++;
                                    foreach($getQuery->result() as $rowAM):
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

                                        $TITM_VOLMBG = $TITM_VOLMBG + $ITM_VOLMBG;
                                        $TITM_TOTALP = $TITM_TOTALP + $ITM_TOTALP;
                                        $TITM_PRICE  = $TITM_TOTALP / $TITM_VOLMBG;

                                        $TADDVOLM      = $TADDVOLM + $ADDVOLM;
                                        $TADDCOST      = $TADDCOST + $ADDCOST;
                                        $TADDPRICE     = $TADDCOST / $TADDVOLM;
                            ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $theRow; ?></td>
                                    <td><?php echo $ITM_CODE; ?></td>
                                    <td><?php echo $ITM_NAME; ?></td>
                                    <td style="text-align: center;"><?php echo $AMD_CODE; ?></td>
                                    <td style="text-align: center;"><?php echo $AMD_CATEG; ?></td>
                                    <td><?php echo $AMD_JOBDESC; ?></td>
                                    <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                    <td style="text-align: right; background-color: gray;"><?php echo number_format($ADDVOLM, 2); ?></td>
                                    <td style="text-align: right; background-color: gray;"><?php echo number_format($ADDPRICE, 2); ?></td>
                                    <td style="text-align: right; background-color: gray;"><?php echo number_format($ADDCOST, 2); ?></td>
                                    <td style="text-align: right; background-color: white;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
                                    <td style="text-align: right; background-color: white;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                    <td style="text-align: right; background-color: white;"><?php echo number_format($ITM_TOTALP, 2); ?></td>
                                    <td style="text-align: right; background-color: gray;"><?php echo number_format($TADD_VOL, 2); ?></td>
                                    <td style="text-align: right; background-color: gray;"><?php echo number_format($TADDPRICE, 2); ?></td>
                                    <td style="text-align: right; background-color: gray;"><?php echo number_format($TADD_AMN, 2); ?></td>
                                </tr>
                            <?php
                                    endforeach;

                                    $GTADD_VOLM     = $TITM_VOLMBG + $TADDVOLM;
                                    $GTADD_COST     = $TITM_TOTALP + $TADDCOST;
                                    $GTADD_PRICE    = $GTADD_COST / $GTADD_VOLM;
                                ?>
                                <tr style="text-align: right; font-weight: bold;">
                                    <td colspan="7">TOTAL</td>
                                    <td style="background-color: gray;"><?=number_format($TADDVOLM, 2)?></td>
                                    <td style="background-color: gray;"><?=number_format($TADDPRICE, 2)?></td>
                                    <td style="background-color: gray;"><?=number_format($TADDCOST, 2)?></td>
                                    <td style="background-color: white;"><?=number_format($TITM_VOLMBG, 2)?></td>
                                    <td style="background-color: white;"><?=number_format($TITM_PRICE, 2)?></td>
                                    <td style="background-color: white;"><?=number_format($TITM_TOTALP, 2)?></td>
                                    <td style="background-color: gray;"><?=number_format($GTADD_VOLM, 2)?></td>
                                    <td style="background-color: gray;"><?=number_format($GTADD_PRICE, 2)?></td>
                                    <td style="background-color: gray;"><?=number_format($GTADD_COST, 2)?></td>
                                </tr>
                            </tbody>
                        </table>
                    </section>
                <?php
            endfor;
        } else {
            $this->db->select('A.PRJCODE, A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_VOLMBG, A.ITM_PRICE, A.ITM_LASTP, A.ITM_TOTALP, SUM(B.AMD_VOLM) AS ADDVOLM, SUM(B.AMD_TOTAL) AS ADDCOST, A.ADDMVOLM, A.ADDMCOST, C.AMD_CODE, C.AMD_CATEG, C.AMD_JOBDESC');
            $this->db->from('tbl_item A');
            $this->db->join('tbl_amd_detail B', 'B.ITM_CODE = A.ITM_CODE AND B.PRJCODE = "'.$PRJCODE.'"', 'inner');
            $this->db->join('tbl_amd_header C', 'C.AMD_NUM = B.AMD_NUM AND C.PRJCODE = "'.$PRJCODE.'"', 'inner');
            $this->db->group_by('A.ITM_CODE');
            $this->db->where('A.PRJCODE', $PRJCODE);
            $this->db->where('C.AMD_STAT', 3);
            $this->db->where('C.AMD_DATE >=', $Start_Date);
            $this->db->where('C.AMD_DATE <=', $End_Date);
            $getQuery = $this->db->get();

            ?>
                <section class="sheet padding-10mm">
                    <div class="logo" style="padding-bottom: 10px;">
                        <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg" alt="" width="190" height="44">
                    </div>
                    <table width="100%" style="font-size: 12px; font-weight: bold; border: hidden;">
                        <tr>
                            <td width="100" nowrap valign="top">Nama Laporan</td>
                            <td width="10">:</td>
                            <td><?php echo "$h1_title"; ?></td>
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
                    <div class="header" style="padding-bottom: 5px;"></div>
                    <table width="100%" border="1">
                        <thead>
                            <tr class="header">
                                <th rowspan = "2">No.</th>
                                <th rowspan = "2" style="width: 80px;">Item Budget</th>
                                <th rowspan = "2" style="width: 170px;">Nama Item Budget</th>
                                <th rowspan = "2" style="width: 120px;">Nomor Amandemen</th>
                                <th rowspan = "2">Jenis Amandemen</th>
                                <th rowspan = "2" style="width: 170px;">Keterangan</th>
                                <th rowspan = "2">Satuan</th>
                                <th colspan = "3" style="background-color: gray;">Nilai Amandemen</th>
                                <th colspan = "3" style="background-color: white;">Budget Awal</th>
                                <th colspan = "3" style="background-color: gray;">Budget Setelah Amandemen</th>
                            </tr>
                            <tr class="header">
                                <th style="background-color: gray;">Volume</th>
                                <th style="background-color: gray;">Harga Satuan</th>
                                <th style="background-color: gray;">Jumlah Harga</th>
                                <th style="background-color: white;">Volume</th>
                                <th style="background-color: white;">Harga Satuan</th>
                                <th style="background-color: white;">Jumlah Harga</th>
                                <th style="background-color: gray;">Volume</th>
                                <th style="background-color: gray;">Harga Satuan</th>
                                <th style="background-color: gray;">Jumlah Harga</th>
                            </tr>
                            <tr>
                                <th colspan="16" style="line-height: 2px; border-left: hidden; border-right: hidden; padding: 0px">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody class="content">
                            <?php
                                $theRow = 0;
                                $TITM_VOLMBG = 0;
                                $TITM_TOTALP = 0;
                                $TITM_PRICE  = 0;
                                $TADDVOLM    = 0;
                                $TADDCOST    = 0;
                                $TADDPRICE   = 0;
                                $GTADD_VOLM  = 0;
                                $GTADD_COST  = 0;
                                $GTADD_PRICE = 0;
                                foreach($getQuery->result() as $rowAM):
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

                                    $TITM_VOLMBG = $TITM_VOLMBG + $ITM_VOLMBG;
                                    $TITM_TOTALP = $TITM_TOTALP + $ITM_TOTALP;
                                    $TITM_PRICE  = $TITM_TOTALP / $TITM_VOLMBG;

                                    $TADDVOLM      = $TADDVOLM + $ADDVOLM;
                                    $TADDCOST      = $TADDCOST + $ADDCOST;
                                    $TADDPRICE     = $TADDCOST / $TADDVOLM;
                        ?>
                            <tr>
                                <td style="text-align: center;"><?php echo $theRow; ?></td>
                                <td><?php echo $ITM_CODE; ?></td>
                                <td><?php echo $ITM_NAME; ?></td>
                                <td style="text-align: center;"><?php echo $AMD_CODE; ?></td>
                                <td style="text-align: center;"><?php echo $AMD_CATEG; ?></td>
                                <td><?php echo $AMD_JOBDESC; ?></td>
                                <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                <td style="text-align: right; background-color: gray;"><?php echo number_format($ADDVOLM, 2); ?></td>
                                <td style="text-align: right; background-color: gray;"><?php echo number_format($ADDPRICE, 2); ?></td>
                                <td style="text-align: right; background-color: gray;"><?php echo number_format($ADDCOST, 2); ?></td>
                                <td style="text-align: right; background-color: white;"><?php echo number_format($ITM_VOLMBG, 2); ?></td>
                                <td style="text-align: right; background-color: white;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                <td style="text-align: right; background-color: white;"><?php echo number_format($ITM_TOTALP, 2); ?></td>
                                <td style="text-align: right; background-color: gray;"><?php echo number_format($TADD_VOL, 2); ?></td>
                                <td style="text-align: right; background-color: gray;"><?php echo number_format($TADDPRICE, 2); ?></td>
                                <td style="text-align: right; background-color: gray;"><?php echo number_format($TADD_AMN, 2); ?></td>
                            </tr>
                        <?php
                                endforeach;

                                $GTADD_VOLM     = $TITM_VOLMBG + $TADDVOLM;
                                $GTADD_COST     = $TITM_TOTALP + $TADDCOST;
                                if($getQuery->num_rows() > 0)
                                    $GTADD_PRICE    = $GTADD_COST / $GTADD_VOLM;
                            ?>
                            <tr style="text-align: right; font-weight: bold;">
                                <td colspan="7">TOTAL</td>
                                <td style="background-color: gray;"><?=number_format($TADDVOLM, 2)?></td>
                                <td style="background-color: gray;"><?=number_format($TADDPRICE, 2)?></td>
                                <td style="background-color: gray;"><?=number_format($TADDCOST, 2)?></td>
                                <td style="background-color: white;"><?=number_format($TITM_VOLMBG, 2)?></td>
                                <td style="background-color: white;"><?=number_format($TITM_PRICE, 2)?></td>
                                <td style="background-color: white;"><?=number_format($TITM_TOTALP, 2)?></td>
                                <td style="background-color: gray;"><?=number_format($GTADD_VOLM, 2)?></td>
                                <td style="background-color: gray;"><?=number_format($GTADD_PRICE, 2)?></td>
                                <td style="background-color: gray;"><?=number_format($GTADD_COST, 2)?></td>
                            </tr>
                        </tbody>
                    </table>
                </section>
            <?php
        }
    ?>
</body>
</html>