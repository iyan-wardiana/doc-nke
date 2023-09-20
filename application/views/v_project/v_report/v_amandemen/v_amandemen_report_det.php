<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $h1_title; ?></title>
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
                    <td width="100">Item Budget</td>
                    <td width="10">:</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td nowrap valign="top">Tgl. Cetak</td>
                    <td>:</td>
                    <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                </tr>
            </table>
        </div>
        <div class="print_content" style="padding-top: 5px; font-size: 12px; font-weight: bold;">
            <table width="100%" border="1" rules="all">
                <!-- Header -->
                <tr align="center" style="border-top: solid; border-right: solid; border-left: solid;">
                    <td rowspan="3" width="50" style="border-bottom: solid; border-left: solid;">No.</td>
                    <td rowspan="3" width="100" style="border-bottom: solid;">Nomor Amandemen</td>
                    <td rowspan="3" width="200" style="border-bottom: solid;">Keterangan</td>
                    <td rowspan="3" style="border-bottom: solid;">Nomor SI</td>
                    <td colspan="12">Jenis Amandemen</td>
                    <td rowspan="3" style="border-right: solid;">Satuan</td>
                </tr>
                <tr style="text-align: center;">
                    <?php
                        $jmlItem = 4;
                        for($i=1; $i <= $jmlItem; $i++):
                    ?>
                    <td colspan="3">Item <?=$i?></td>
                    <?php
                        endfor;
                    ?>
                </tr>
                <tr style="border-bottom: solid; text-align: center;">
                    <?php
                        $catItem = 4;
                        for($j=1; $j <= $catItem; $j++):
                    ?>
                    <td>Volume</td>
                    <td>Hasrat</td>
                    <td>Jumlah</td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td colspan="<?=5+($jmlItem*3)?>" style="line-height: 2px; border-left: hidden; border-right: hidden;">&nbsp;</td>
                </tr>
                <!-- End Header -->
                <tr>
                    <td width="50">&nbsp;</td>
                    <td width="100">&nbsp;</td>
                    <td width="200">&nbsp;</td>
                    <td>&nbsp;</td>
                    <?php
                        $nilai_catItem = 4;
                        for($k=1; $k <= $nilai_catItem; $k++):
                    ?>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <?php
                        endfor;
                    ?>
                    <td>&nbsp;</td>
                </tr>
                <!-- footer -->
                <tr>
                    <td colspan="4">&nbsp;</td>
                    <?php
                        $jmlItemEndCol = 4;
                        for($l=1; $l <= $jmlItemEndCol; $l++):
                    ?>
                    <td colspan="3">&nbsp;</td>
                    <?php
                        endfor;
                    ?>
                    <td>&nbsp;</td>
                </tr>
                <!-- end footer -->
            </table>
        </div>
    </div>

</body>

</html>