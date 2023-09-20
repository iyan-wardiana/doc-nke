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
            width: 29.7cm;
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
                        <h3><?=$h1_title?></h3>
                    </td>
                </tr>
            </table>
        </div>

        <div class="print_body" style="padding-top: 10px; font-size: 14px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100">Proyek</td>
                    <td width="10">:</td>
                    <td>531 - Universitas Mulawarman</td>
                </tr>
                <tr>
                    <td width="100">Periode</td>
                    <td width="10">:</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
        <div class="print_content" style="padding-top: 5px; font-size: 12px; font-weight: bold;">
            <table width="100%" border="1" rules="all" style="border-color: black;">
                <!-- Header -->
                <tr align="center" style="border-top: solid; border-right: solid; border-left: solid; background-color: tan;">
                    <td rowspan = "2" width="50" style="border-bottom: solid; border-left: solid;">No.</td>
                    <td colspan="6">Pekerjaan (Tambah/Kurang)</td>
                    <td colspan="3">Amandemen</td>
                    <td colspan="2">Tagihan SI %</td>
                </tr>
                <tr align="center" style="background-color: tan;">
                    <td style="border-bottom: solid;">Nomor</td>
                    <td style="border-bottom: solid;">Uraian</td>
                    <td style="border-bottom: solid;">Volume</td>
                    <td style="border-bottom: solid;">Satuan</td>
                    <td style="border-bottom: solid;">Harsat</td>
                    <td style="border-bottom: solid;">Jumlah</td>
                    <td style="border-bottom: solid;">Nomor</td>
                    <td style="border-bottom: solid;">Nilai</td>
                    <td style="border-bottom: solid;">Item Budget</td>
                    <td style="border-bottom: solid;">Progress</td>
                    <td style="border-bottom: solid; border-right: solid;">Ditagihkan</td>
                </tr>
                <tr>
                    <td colspan="12" style="line-height: 2px; border-left: hidden; border-right: hidden;">&nbsp;</td>
                </tr>
                <!-- End Header -->
                <tr style="background-color: darkgray;">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr style="background-color: darkgray;">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>