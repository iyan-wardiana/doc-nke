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
            <table width="100%" border="1" rules="all" style="border-color: black;">
                <!-- Header -->
                    <tr align="center" style="border-top: solid; border-right: solid; border-left: solid; background-color: tan;">
                        <td rowspan = "2" style="border-bottom: solid; border-left: solid;">No.</td>
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

                <?php
                    $theRow     = 0;
                    $sqlSIC     = "tbl_siheader WHERE PRJCODE = '$PRJCODE'";
                    $resSIC     = $this->db->count_all($sqlSIC);
                    if($resSIC > 0)
                    {          
                        $sqlSI      = "SELECT SI_CODE, SI_MANNO, SI_DATE, SI_ENDDATE, SI_DESC, SI_APPVAL, SI_STAT, SI_VALUE, SI_APPVAL
                                        FROM tbl_siheader WHERE PRJCODE = '$PRJCODE'";
                        $resSI      = $this->db->query($sqlSI)->result();
                        foreach($resSI as $rowSI):
                            $theRow     = $theRow + 1;
                            $SI_CODE    = $rowSI->SI_CODE;
                            $SI_MANNO   = $rowSI->SI_MANNO;
                            $SI_DATE    = $rowSI->SI_DATE;
                            $SI_ENDDATE = $rowSI->SI_ENDDATE;
                            $SI_DESC    = $rowSI->SI_DESC;
                            $SI_APPVAL  = $rowSI->SI_APPVAL;
                            $SI_STAT    = $rowSI->SI_STAT;
                            $SI_VALUE   = $rowSI->SI_VALUE;
                            $SI_APPVAL  = $rowSI->SI_APPVAL;

                            // STATUS
                                if($SI_STAT == 0)
                                {
                                    $SI_STATD   = 'fake';
                                    $STATCOL    = 'danger';
                                }
                                elseif($SI_STAT == 1)
                                {
                                    $SI_STATD   = 'New';
                                    $STATCOL    = 'warning';
                                }
                                elseif($SI_STAT == 2)
                                {
                                    $SI_STATD   = 'Confirm';
                                    $STATCOL    = 'primary';
                                }
                                elseif($SI_STAT == 3)
                                {
                                    $SI_STATD   = 'Approved';
                                    $STATCOL    = 'success';
                                }
                                elseif($SI_STAT == 4)
                                {
                                    $SI_STATD   = 'Revise';
                                    $STATCOL    = 'danger';
                                }
                                elseif($SI_STAT == 5)
                                {
                                    $SI_STATD   = 'Rejected';
                                    $STATCOL    = 'danger';
                                }
                                elseif($SI_STAT == 6)
                                {
                                    $SI_STATD   = 'Close';
                                    $STATCOL    = 'info';
                                }
                                elseif($SI_STAT == 7)
                                {
                                    $SI_STATD   = 'Awaiting';
                                    $STATCOL    = 'warning';
                                }
                                elseif($SI_STAT == 9)
                                {
                                    $SI_STATD   = 'Void';
                                    $STATCOL    = 'danger';
                                }
                                else
                                {
                                    $SI_STATD   = 'Fake';
                                    $STATCOL    = 'warning';
                                }
                            ?>
                                <tr>
                                    <td style="text-align: center;"><?=$theRow?></td>
                                    <td><?php echo $SI_MANNO; ?></td>
                                    <td><?php echo ""; ?></td>
                                    <td style="text-align: center;"><?php echo "1"; ?></td>
                                    <td style="text-align: center;">Unit</td>
                                    <td style="text-align: right;"><?php echo number_format($SI_VALUE, 2); ?></td>
                                    <td style="text-align: right;"><?php echo number_format($SI_VALUE, 2); ?></td>
                                    <td style="text-align: right;"><?php echo ""; ?></td>
                                    <td style="text-align: right;"><?php echo number_format(0, 2); ?></td>
                                    <td style="text-align: right;"><?php echo number_format(0, 2); ?></td>
                                    <td style="text-align: right;"><?php echo number_format(0, 2); ?></td>
                                    <td style="text-align: right;"><?php echo number_format(0, 2); ?></td>
                                </tr>
                            <?php
                        endforeach;
                    }
                ?>
            </table>
        </div>
    </div>

</body>

</html>