<?php  
class moneyFormat
{ 
    public function rupiah ($angka) 
    {
        $rupiah = number_format($angka ,2, ',' , '.' );
        return $rupiah;
    }
 
    public function terbilang ($angka)
    {
        $angka = (float)$angka;
        $bilangan = array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas');
        if ($angka < 12) {
            return $bilangan[$angka];
        } else if ($angka < 20) {
            return $bilangan[$angka - 10] . ' Belas';
        } else if ($angka < 100) {
            $hasil_bagi = (int)($angka / 10);
            $hasil_mod = $angka % 10;
            return trim(sprintf('%s Puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
        } else if ($angka < 200) {
            return sprintf('Seratus %s', $this->terbilang($angka - 100));
        } else if ($angka < 1000) {
            $hasil_bagi = (int)($angka / 100);
            $hasil_mod = $angka % 100;
            return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], $this->terbilang($hasil_mod)));
        } else if ($angka < 2000) {
            return trim(sprintf('Seribu %s', $this->terbilang($angka - 1000)));
        } else if ($angka < 1000000) {
            $hasil_bagi = (int)($angka / 1000); 
            $hasil_mod = $angka % 1000;
            return sprintf('%s Ribu %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod));
        } else if ($angka < 1000000000) {
            $hasil_bagi = (int)($angka / 1000000);
            $hasil_mod = $angka % 1000000;
            return trim(sprintf('%s Juta %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else if ($angka < 1000000000000) {
            $hasil_bagi = (int)($angka / 1000000000);
            $hasil_mod = fmod($angka, 1000000000);
            return trim(sprintf('%s Milyar %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else if ($angka < 1000000000000000) {
            $hasil_bagi = $angka / 1000000000000;
            $hasil_mod = fmod($angka, 1000000000000);
            return trim(sprintf('%s Triliun %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else {
            return 'Data Salah';
        }
    }
}

$moneyFormat = new moneyFormat();

$WO_NUM      = $default['WO_NUM'];
$WO_CODE     = $default['WO_CODE'];
$WO_DATE     = $default['WO_DATE'];
$WO_STARTD   = $default['WO_STARTD'];
$WO_ENDD     = $default['WO_ENDD'];
$PRJCODE     = $default['PRJCODE'];
$PRJCODE     = $default['PRJCODE'];
$SPLCODE     = $default['SPLCODE'];
$WO_DEPT     = $default['WO_DEPT'];
$WO_CATEG    = $default['WO_CATEG'];
$WO_TYPE     = $default['WO_TYPE'];
//$WO_PAYTYPE    = $default['WO_PAYTYPE'];
$JOBCODEID   = $default['JOBCODEID'];
$WO_NOTE     = nl2br($default['WO_NOTE']);
$WO_NOTE2    = nl2br($default['WO_NOTE2']);
$WO_STAT     = $default['WO_STAT'];
$WO_VALUE    = $default['WO_VALUE'];
$WO_MEMO     = nl2br($default['WO_MEMO']);
$WO_PAYNOTE  = nl2br($default['WO_PAYNOTE']);
$PRJNAME     = $default['PRJNAME'];
$FPA_NUM     = $default['FPA_NUM'];
$FPA_CODE    = $default['FPA_CODE'];
$WO_QUOT     = $default['WO_QUOT'];
$WO_NEGO     = $default['WO_NEGO'];

if($WO_CATEG == 'U') $WO_CATEGD =  "Upah";
elseif($WO_CATEG == 'A') $WO_CATEGD = "Alat";
elseif($WO_CATEG == 'S') $WO_CATEGD = "Subkon";

$sqlSpl = "SELECT SPLCODE, SPLDESC, SPLADD1, SPLTELP FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' AND SPLSTAT = '1'";
$sqlSpl = $this->db->query($sqlSpl)->result();
foreach($sqlSpl as $row) :
    $SPLCODE    = $row->SPLCODE;
    $SPLDESC    = $row->SPLDESC;
    $SPLADD1    = $row->SPLADD1;
    $SPLTELP    = $row->SPLTELP;
endforeach;
if($SPLADD1 == '') $SPLADD1 = '-';
if($SPLTELP == '') $SPLTELP = '-';

$sql = "SELECT PRJNAME, PRJLOCT FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
    $PRJNAME = $row->PRJNAME;
    $PRJLOCT = $row->PRJLOCT;
endforeach;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $appName; ?></title>
    <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/contract.png'; ?>" sizes="32x32">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.min.css" integrity="sha512-FEQLazq9ecqLN5T6wWq26hCZf7kPqUbFC9vsHNbXMJtSZZWAcbJspT+/NEAQkBfFReZ8r9QlA9JHaAuo28MTJA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style type="text/css">
        /* @page { margin: 0 } */
        body { margin: 0 }
        .sheet {
          margin: 0;
          overflow: hidden;
          position: relative;
          box-sizing: border-box;
          page-break-after: always;
        }

        /** Paper sizes **/
        body.A3               .sheet { width: 297mm; }
        body.A3.landscape     .sheet { width: 420mm; }
        body.A4               .sheet { width: 210mm; }
        body.A4.landscape     .sheet { width: 297mm; }
        body.A5               .sheet { width: 148mm; }
        body.A5.landscape     .sheet { width: 210mm; }
        body.letter           .sheet { width: 216mm; }
        body.letter.landscape .sheet { width: 280mm; }
        body.legal            .sheet { width: 216mm; }
        body.legal.landscape  .sheet { width: 357mm; }

        /** Padding area **/
        .sheet.padding-10mm { padding: 10mm }
        .sheet.padding-15mm { padding: 15mm }
        .sheet.padding-20mm { padding: 20mm }
        .sheet.padding-25mm { padding: 25mm }
        .sheet.custom { padding: 1cm 1cm 0.97cm 1cm }

        /** For screen preview **/
        @media screen {
          body { background: #e0e0e0 }
          .sheet {
            background: white;
            box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
            margin: 5mm auto;
            border-radius: 5px 5px 5px 5px;
          }
        }

        /** Fix for Chrome issue #273306 **/
        @media print {
          @page { size: a4;}
          body.A3.landscape { width: 420mm }
          body.A3, body.A4.landscape { width: 297mm }
          body.A4, body.A5.landscape { width: 210mm }
          body.A5                    { width: 148mm }
          body.letter, body.legal    { width: 216mm }
          body.letter.landscape      { width: 280mm }
          body.legal.landscape       { width: 357mm }
        }
        .cont {
            position: relative;
            /*border: 2px solid;*/
            font-family: "Arial";
            font-size: 8pt;
        }
        .box-header {
            /*position: relative;*/
            width: 100%;
            height: 70px;
            padding: 5px;
            border: 1px solid;
        }
        .box-header .box-column-logo {
            float: left;
            width: 200px;
            /*border: 1px solid;*/
        }
        .box-header .box-column-title {
            /*position: absolute;*/
            /*top: 20px;*/
            float: left;
            width: 500px;
            height: 100%;
            padding-top: 5px;
            /*border: 1px solid;*/
            text-align: center;
            /*background-color: gold;*/
        }
        .box-header .box-column-title > span {
            font-family: "Impact";
            font-size: 24pt;
            font-weight: bold;
        }
        .box-header .box-column-logo img {
            margin: 9px auto;
            width: 5cm;
        }
        .box-header-detail-col-6 {
            float: left;
            width: 50%;
            padding: 5px;
            height: 100px;
            border: 1px solid;
        }
        .box-header-detail-col-6 table td {
            /*background-color: gold;*/
            padding: 3px;
        }
        .box-header-detail-col-12 {
            border: 1px solid;
        }
        .box-header-detail-col-12 table td {
            /*background-color: gold;*/
            padding: 3px;
        }
        .box-detail {
            margin-top: 2px;
        }
        .box-detail table th, .box-detail table td {
            border: 1px solid;
            padding: 2px;
        }
        .box-detail table thead th, tbody td, tfoot td {
            padding: 2px;
        }
        .box-detail table thead th {
            text-align: center;
            border-top: double;
        }
        .box-detail #box-asign-1 tr td {
            border: hidden;
        }
        .box-detail tfoot td > p {
            margin: 0;
            padding: 0;
        }
        .box-asign-1, .box-asign-2 {
            margin-top: 10px;
        }
        .box-asign-1, .box-asign-2 table td {
            text-align: center;
        }
        ul.dashed {
            list-style-type: none;
            padding-left: 5px;
        }
        ul.dashed > li {
            text-indent: -8px;
        }
        ul.dashed > li::before {
            content: "- ";
            text-indent: -5px;
        }
        #Layer1 {
            position: absolute;
            top: 10px;
            left: 705px;
        }
    </style>
</head>
<body class="page A4">
    <section class="page sheet custom">
        <div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>
        <div class="cont">
            <div class="box-header">
                <div class="box-column-logo">
                    <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
                </div>
                <div class="box-column-title">
                    <span>SURAT PERINTAH KERJA - <?php echo strtoupper($WO_CATEGD);?></span>
                </div>
            </div>
            <div style="width: 100%; height: 3px; background-color: gray !important;"></div>
            <div class="box-header-detail-col-6">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="110">Nomor SPK</td>
                        <td width="10">:</td>
                        <td><?php echo $WO_CODE; ?></td>
                    </tr>
                    <tr>
                        <td width="110">Tanggal SPK</td>
                        <td width="10">:</td>
                        <td><?php echo date('d-m-Y', strtotime($WO_DATE)); ?></td>
                    </tr>
                    <tr>
                        <td width="110">Waktu Pelaksanaan</td>
                        <td width="10">:</td>
                        <td>Disesuaikan dengan schedule lapangan</td>
                    </tr>
                </table>
            </div>
            <div class="box-header-detail-col-6" style="border-left: hidden;">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="50">Supplier</td>
                        <td width="10">:</td>
                        <td><?php echo "$SPLDESC ($SPLCODE)"; ?></td>
                    </tr>
                    <tr>
                        <td width="50">Alamat</td>
                        <td width="10">:</td>
                        <td><?php echo $SPLADD1; ?></td>
                    </tr>
                    <tr>
                        <td width="50">Telp.</td>
                        <td width="10">:</td>
                        <td><?php echo $SPLTELP; ?></td>
                    </tr>
                </table>
            </div>
            <div class="box-header-detail-col-12">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="50">Proyek</td>
                        <td width="10">:</td>
                        <td><?php echo "$PRJNAME - $PRJCODE"; ?></td>
                    </tr>
                    <tr>
                        <td width="50">Lokasi</td>
                        <td width="10">:</td>
                        <td><?php echo $PRJLOCT; ?></td>
                    </tr>
                </table>
            </div>
            <div class="box-detail">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Uraian Pekerjaan</th>
                            <th width="80">Volume</th>
                            <th width="50">Sat</th>
                            <th width="100">Harga</th>
                            <th width="100">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            $sqlDET = "SELECT A.*,
                                            C.JOBDESC, C.JOBPARENT
                                        FROM tbl_wo_detail A
                                            INNER JOIN tbl_joblist_detail C ON A.JOBCODEID = C.JOBCODEID
                                                AND C.PRJCODE = '$PRJCODE'
                                                AND A.ITM_CODE = C.ITM_CODE
                                            LEFT JOIN tbl_wo_header D ON D.WO_NUM = A.WO_NUM
                                                AND D.PRJCODE = C.PRJCODE
                                        WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE'";
                            $result = $this->db->query($sqlDET)->result();
                            $no = 0;
                            $WO_SUBTOTAL    = 0;
                            $WO_GTOTAL      = 0;
                            $TOT_TAXPPN     = 0;
                            $TOT_TAXPPH     = 0;
                            foreach($result as $row) :
                                $no             = $no + 1;
                                $WO_ID          = $row->WO_ID;
                                $WO_NUM         = $row->WO_NUM;
                                $WO_CODE        = $row->WO_CODE;
                                $WO_DATE        = $row->WO_DATE;
                                $PRJCODE        = $row->PRJCODE;
                                $JOBCODEDET     = $row->JOBCODEDET;
                                $JOBCODEID      = $row->JOBCODEID;
                                $ITM_CODE       = $row->ITM_CODE;
                                $ITM_NAME       = $row->JOBDESC;
                                $SNCODE         = $row->SNCODE;
                                $ITM_UNIT       = $row->ITM_UNIT;
                                $WO_VOLM        = $row->WO_VOLM;
                                $ITM_PRICE      = $row->ITM_PRICE;
                                $WO_DISC        = $row->WO_DISC;
                                $WO_DISCP       = $row->WO_DISCP;
                                $WO_TOTAL       = $row->WO_TOTAL;
                                $WO_CVOL        = $row->WO_CVOL;
                                $WO_CAMN        = $row->WO_CAMN;
                                $WO_DESC        = $row->WO_DESC;
                                $TAXCODE1       = $row->TAXCODE1;
                                $TAXPERC1       = $row->TAXPERC1;
                                $TAXPRICE1      = $row->TAXPRICE1;
                                $TAXCODE2       = $row->TAXCODE2;
                                $TAXPERC2       = $row->TAXPERC2;
                                $TAXPRICE2      = $row->TAXPRICE2;
                                $WO_TOTAL2      = $row->WO_TOTAL2;
                                $ITM_BUDG_VOL   = $row->ITM_BUDG_VOL;
                                $ITM_BUDG_AMN   = $row->ITM_BUDG_AMN;
                                $WO_SUBTOTAL    = $WO_SUBTOTAL + $WO_TOTAL;
                                $WO_GTOTAL      = $WO_GTOTAL + $WO_TOTAL;
                                $itemConvertion = 1;

                                $OPN_VOLM       = $row->OPN_VOLM;
                                $REM_VOLWO      = $WO_VOLM - $OPN_VOLM;
                                
                                $TOT_TAXPPN     = $TOT_TAXPPN + $TAXPRICE1;
                                $TOT_TAXPPH     = $TOT_TAXPPH + $TAXPRICE2;

                                $UNITTYPE       = strtoupper($ITM_UNIT);
                                if($UNITTYPE == 'LS' )
                                    $ITM_BUDQTY = $ITM_BUDG_AMN;
                                else
                                    $ITM_BUDQTY     = $ITM_BUDG_VOL;

                                $JOBPARENT      = $row->JOBPARENT;
                                $JOBDESCH       = '';
                                $sqlJOBDESC     = "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
                                $resJOBDESC     = $this->db->query($sqlJOBDESC)->result();
                                foreach($resJOBDESC as $rowJOBDESC) :
                                    $JOBDESCH   = $rowJOBDESC->JOBDESC;
                                endforeach;

                                ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $no; ?></td>
                                        <td>
                                            <div>
                                                <span><?php echo "$JOBCODEID - $ITM_NAME"; ?></span>
                                            </div>
                                            <div style="padding-left: 5px; font-style: italic;">
                                                <i class="text-muted fa fa-rss"></i>
                                                <?php
                                                    $JOBDS  = strlen($JOBDESCH);
                                                    if($JOBDS > 50)
                                                    {
                                                        echo wordwrap($JOBDESCH, 45, '<br>');
                                                        echo " ...";
                                                    }
                                                    else
                                                    {
                                                        echo $JOBDESCH;
                                                    }
                                                ?>
                                            </div>
                                            <div>
                                                <?php 
                                                    if($WO_DESC == '') echo "";
                                                    else echo "($WO_DESC)"; 
                                                ?>
                                            </div>
                                        </td>
                                        <td style="text-align: center;"><?php echo number_format($WO_VOLM, 2); ?></td>
                                        <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                        <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($WO_TOTAL, 2); ?></td>
                                    </tr>
                                <?php

                            endforeach;
                            if($no <= 7)
                            {
                                $amRow = 7 - $no;
                                for($i=0;$i<$amRow;$i++)
                                {
                                    ?>
                                        <tr class="blank-line">
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    <?php
                                }
                            }
                        ?>
                        
                        <tr>
                            <td colspan="4" rowspan="4" valign="top">
                                <p><b>Terbilang :</b></p>
                                <p><?php echo $moneyFormat->terbilang(($WO_GTOTAL + $TOT_TAXPPN)); ?></p>
                            </td>
                            <td width="100">Subtotal :</td>
                            <td width="100" style="text-align: right;"><?php echo number_format($WO_SUBTOTAL, 2); ?></td>
                        </tr>
                        <tr>
                            <td width="100">
                                <?php echo $TAXPERC1 != 0 ? "PPN $TAXPERC1 %":"PPN"; ?> :
                            </td>
                            <td width="100" style="text-align: right;"><?php echo number_format($TOT_TAXPPN, 2); ?></td>
                        </tr>
                        <tr>
                            <td width="100">
                                <?php echo $TAXPERC2 != 0 ? "PPh $TAXPERC2 %":"PPh"; ?> :
                            </td>
                            <td width="100" style="text-align: right;"><?php echo number_format($TOT_TAXPPH, 2); ?></td>
                        </tr>
                        <tr>
                            <td width="100">Total :</td>
                            <td width="100" style="text-align: right;"><?php echo number_format(($WO_GTOTAL + $TOT_TAXPPN), 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php echo $this->db->get_where("tbl_wo_detail", ["PRJCODE" => $PRJCODE, "WO_NUM" => $WO_NUM])->num_rows() > 8 ? "</section>":""; ?>
    <?php echo $this->db->get_where("tbl_wo_detail", ["PRJCODE" => $PRJCODE, "WO_NUM" => $WO_NUM])->num_rows() > 8 ? "<section class=\"\page sheet custom\"\>":""; ?>
        <div class="cont">
            <div class="box-detail" style="margin-top: -1px;">
                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <td colspan="6">
                                <p><b><u>CATATAN :</u></b></p>
                                <p><?=$WO_NOTE?></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <p><b><u>CARA PEMBAYARAN :</u></b></p>
                                <p><?=$WO_PAYNOTE?></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <div>
                                    <span style="display: inline-block; width: 150px;">Dikeluarkan di</span>
                                    <span style="display: inline-block;">: <?php echo "$PRJLOCT, ".date('d F Y', strtotime($WO_DATE)); ?></span>
                                </div>
                                <div>
                                    <span style="display: inline-block; width: 150px;">Yang memberikan pekerjaan</span>
                                    <span style="display: inline-block;">: <?php echo "$appName"; ?></span>
                                </div>
                            </td>
                            <td colspan="2" style="text-align: center;">Menyatakan Setuju Menerima Pekerjaan</td>
                        </tr>
                        <tr style="height: 100px;">
                            <td width="130" style="vertical-align: top;">
                                <div style="text-align: center; font-weight: bold;">ENG. (QS)</div>
                                <div style="margin-top: 70px;">Nama : </div>
                            </td>
                            <td width="130" style="vertical-align: top;">
                                <div style="text-align: center; font-weight: bold;">Mgr.Opr. (SM)</div>
                                <div style="margin-top: 70px;">Nama : </div>
                            </td>
                            <td width="130" style="vertical-align: top;">
                                <div style="text-align: center; font-weight: bold;">Mgr.Proyek (PM)</div>
                                <div style="margin-top: 70px;">Nama : </div>
                            </td>
                            <td width="130" style="vertical-align: top;">
                                <div style="text-align: center; font-weight: bold;">Mgr.Pengadaan</div>
                                <div style="margin-top: 70px;">Nama : </div>
                            </td>
                            <td colspan="2" style="vertical-align: top;">
                                <div style="text-align: center; font-weight: bold;">PEMASOK</div>
                                <div style="margin-top: 70px;">Nama : </div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</body>
</html>
<script type="text/javascript">
    document.addEventListener("keydown", function (event) {
        console.log(event);
        if (event.ctrlKey) {
            event.preventDefault();
            // sebuah method yang berfungsi untuk mencegah terjadinya event bawaan dari sebuah DOM
        }   
    });
</script>