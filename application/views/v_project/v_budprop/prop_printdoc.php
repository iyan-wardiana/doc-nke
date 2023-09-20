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

$PROP_NUM      = $default['PROP_NUM'];
$PROP_CODE     = $default['PROP_CODE'];
$PROP_DATE     = $default['PROP_DATE'];
$PRJCODE     = $default['PRJCODE'];
$PRJCODE     = $default['PRJCODE'];
$PROP_NOTE     = $default['PROP_NOTE'];
$PROP_STAT     = $default['PROP_STAT'];
$PRJNAME     = $default['PRJNAME'];

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
        @page { margin: 0 }
        body { margin: 0 }
        .sheet {
          margin: 0;
          overflow: hidden;
          position: relative;
          box-sizing: border-box;
          page-break-after: always;
        }

        /** Paper sizes **/
        body.A3               .sheet { width: 297mm; height: 419mm }
        body.A3.landscape     .sheet { width: 420mm; height: 296mm }
        body.A4               .sheet { width: 210mm; height: 296mm }
        body.A4.landscape     .sheet { width: 297mm; height: 209mm }
        body.A5               .sheet { width: 148mm; height: 209mm }
        body.A5.landscape     .sheet { width: 210mm; height: 147mm }
        body.letter           .sheet { width: 216mm; height: 279mm }
        body.letter.landscape .sheet { width: 280mm; height: 215mm }
        body.legal            .sheet { width: 216mm; height: 356mm }
        body.legal.landscape  .sheet { width: 357mm; height: 215mm }

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
          @page { size: landscape;}
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
            width: 830px;
            height: 100%;
            padding-top: 5px;
            /*border: 1px solid;*/
            text-align: center;
            /* background-color: gold; */
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
            width: 100%;
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
            left: 35px;
        }
        .ttd {
            position: relative;
            width: 500px;
            margin-top: 10px;
            left: 540px;
        }
        .ttd table td {
            padding: 5px;
        }
    </style>
</head>
<body class="page A4 landscape">
    <section class="page sheet custom">
        <div class="cont">
            <div class="box-header">
                <div class="box-column-logo">
                    <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
                </div>
                <div class="box-column-title">
                    <span>PROPOSAL KAS PROYEK</span>
                </div>
            </div>
            <div style="width: 100%; height: 3px; background-color: gray !important;"></div>
            <div class="box-header-detail-col-6">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="110">Kode & Nama Proyek</td>
                        <td width="10">:</td>
                        <td><?php echo "$PRJCODE $PRJNAME"; ?></td>
                    </tr>
                    <tr>
                        <td width="110">Nomor Proposal</td>
                        <td width="10">:</td>
                        <td><?php echo $PROP_CODE; ?></td>
                    </tr>
                    <tr>
                        <td width="110">Nama Proposal</td>
                        <td width="10">:</td>
                        <td><?php echo $PROP_NOTE; ?></td>
                    </tr>
                    <tr>
                        <td width="110">Tanggal Proposal</td>
                        <td width="10">:</td>
                        <td><?php echo date('d-m-Y', strtotime($PROP_DATE)); ?></td>
                    </tr>
                </table>
            </div>
            <div class="box-detail">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="30">No</th>
                            <th>Klp</th>
                            <th width="50">Kode Item</th>
                            <th>Nama Komponen</th>
                            <th width="50">Sat</th>
                            <th width="50">Volume</th>
                            <th width="100">Harga</th>
                            <th width="100">Jumlah</th>
                            <th>Kode Pek</th>
                            <th>Nama Pekerjaan</th>
                            <th>Uraian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            $sqlDET = "SELECT A.*,
                                            C.JOBPARENT
                                        FROM tbl_bprop_detail A
                                            INNER JOIN tbl_joblist_detail C ON A.JOBCODEID = C.JOBCODEID
                                                AND C.ITM_CODE = A.ITM_CODE
                                                AND C.PRJCODE = A.PRJCODE
                                                AND A.ITM_CODE = C.ITM_CODE
                                            INNER JOIN tbl_bprop_header D ON D.PROP_NUM = A.PROP_NUM
                                        WHERE A.PROP_NUM = '$PROP_NUM' AND A.PRJCODE = '$PRJCODE'";
                            $result = $this->db->query($sqlDET)->result();
                            $no = 0;
                            $PROP_GTOTAL      = 0;
                            foreach($result as $row) :
                                $no             = $no + 1;
                                $PROP_NUM       = $row->PROP_NUM;
                                $PROP_CODE      = $row->PROP_CODE;
                                $PROP_DATE      = $row->PROP_DATE;
                                $PRJCODE        = $row->PRJCODE;
                                $JOBCODEID      = $row->JOBCODEID;
                                $JOBDESC        = $row->JOBDESC;
                                $ITM_CODE       = $row->ITM_CODE;
                                $ITM_NAME       = $row->ITM_NAME;
                                $ITM_UNIT       = $row->ITM_UNIT;
                                $ITM_VOLM       = $row->ITM_VOLM;
                                $ITM_PRICE      = $row->ITM_PRICE;
                                $PROP_TOTAL     = $row->PROP_TOTAL;
                                $PROP_DESC      = $row->PROP_DESC;

                                $PROP_GTOTAL    = $PROP_GTOTAL + $PROP_TOTAL;

                                $JOBPARENT      = $row->JOBPARENT;
                                // $JOBDESCH       = '';
                                // $sqlJOBDESC     = "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
                                // $resJOBDESC     = $this->db->query($sqlJOBDESC)->result();
                                // foreach($resJOBDESC as $rowJOBDESC) :
                                //     $JOBDESCH   = $rowJOBDESC->JOBDESC;
                                // endforeach;

                                $ITM_CATEG      = $this->db->get_where("tbl_item", ["PRJCODE" => $PRJCODE, "ITM_CODE" => $ITM_CODE])->row("ITM_CATEG");
                                

                                ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $no; ?></td>
                                        <td style="text-align: center;"><?php echo $ITM_CATEG; ?></td>
                                        <td><?php echo $JOBCODEID; ?></td>
                                        <td><?php echo $ITM_NAME; ?></td>
                                        <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                        <td style="text-align: center;"><?php echo number_format($ITM_VOLM, 3); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($PROP_TOTAL, 2); ?></td>
                                        <td style="text-align: center;"><?php echo $JOBPARENT; ?></td>
                                        <td>
                                            <?php
                                                $JOBDS  = strlen($JOBDESC);
                                                if($JOBDS > 50)
                                                {
                                                    echo wordwrap($JOBDESC, 45, '<br>');
                                                    echo " ...";
                                                }
                                                else
                                                {
                                                    echo $JOBDESC;
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $PROP_DESC; ?></td>
                                    </tr>
                                <?php

                            endforeach;
                            if($no <= 15)
                            {
                                $amRow = 15 - $no;
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
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" style="text-align: right; border-bottom: hidden;">TOTAL</td>
                            <td style="border-bottom: double; text-align: right;"><?php echo number_format($PROP_GTOTAL, 2); ?></td>
                            <td colspan="4" style="border-bottom: hidden;">&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
                <table width="100%" border="1" cellpadding="0" cellspacing="0" style="border-top: hidden; margin-top: -2px;">
                    <tr>
                        <td>
                            <div class="ttd">
                                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td colspan="2" style="text-align: right;">______________________________ <?php echo strftime('%d %B %Y'); ?></td>
                                    </tr>
                                    <tr style="height: 100px; text-align: center;">
                                        <td width="50%">
                                            <div>Verivikasi Oleh :</div>
                                            <div style="padding-top: 70px;">
                                                _________________________
                                                <div>Cost Control</div>
                                            </div>
                                        </td>
                                        <td width="50%">
                                            <div>Transfer Oleh :</div>
                                            <div style="padding-top: 70px;">
                                                _________________________
                                                <div>Keuangan</div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>
        <div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
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