<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
  <title><?php echo $appName; ?> | Laporan Internal Audit</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">

  <style>
    	body {
			margin: 0;
			padding: 0;
			background-color: #FAFAFA;
			font: 10px; Arial, Helvetica, sans-serif;
		}
		* {
			box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
		.page {
        width: 21cm;
        min-height: 29.7cm;
        padding-top: 0.5cm;
        padding-bottom: 0.1cm;
        padding-left: 1cm;
        padding-right: 0.3cm;
        margin: 0.5cm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    .subpage {
        padding: 1cm;
        height: 256mm;
    }

    @page {
        size: A4;
        margin: 0;
    }
    @media print {
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
    td.hideBorder{
      border: hidden;
    }
    input #minor {
    width: 20px;

    position: relative;
    left: -5px;

    vertical-align: middle;
    }

    label #chk {
        width: 20px;

        position: relative;
        left: -12px;

        display: inline-block;
        vertical-align: middle;
    }
	ul.minus{
		list-style:none;
	}
	ul.minus li:before{
		content: "- ";
	}
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
  <div class="page">
    <section class="content">
      <div style="font-size:10px; text-align:right; padding-right:0.2cm; font-weight:bold; font-style: italic;">LAMPIRAN-03;</div>
      <table border="0" rules="all" width="100%">
        <tr>
          <td width="150"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg') ?>" width="130" height="30"></td>
          <td width="500" class="hideBorder" style="text-align:center; font-size:16px; font-weight:bold;">LAPORAN KETIDAK SESUAIAN<br>AUDIT INTERNAL (NCR)</td>
          <td width="150">
            <table border="0" rules="all" width="100%">
              <tr>
                <td class="hideBorder" style="font-size:10px; line-height:5px;">No. Doc.</td>
                <td class="hideBorder" style="font-size:10px; line-height:5px;">:</td>
                <td class="hideBorder" style="font-size:10px; line-height:5px;">IQ-135</td>
              </tr>
              <tr>
                <td class="hideBorder" style="font-size:10px; line-height:5px;">Revisi</td>
                <td class="hideBorder" style="font-size:10px; line-height:5px;">:</td>
                <td class="hideBorder" style="font-size:10px; line-height:5px;">B (17/02/14)</td>
              </tr>
              <tr>
                <td class="hideBorder" style="font-size:10px; line-height:5px;">Amand.</td>
                <td class="hideBorder" style="font-size:10px; line-height:5px;">:</td>
                <td class="hideBorder" style="font-size:10px; line-height:5px;">(13/03/14)</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="3" class="hideBorder" style="font-size:10px; font-weight:bold; text-align:center;">
            <u>NOTE</u> : - *) CORET/HILANGKAN YANG TIDAK PERLU, - PRINT OUT NCR ASLI, MENGGUNAKAN LEMBAR KERTAS A4 WARNA KUNING
          </td>
        </tr>
      </table>
      <table border="1" rules="all" width="100%" style="border-color:black; border-width:2px; border-bottom-width:1px;">
        <tr>
          <td height="30" width="90" style="text-align:right;">Ktr / Proyek *):</td>
          <td width="365"><?php echo $PRJNAME; ?></td>
          <td width="30" style="text-align:center;">No:</td>
          <td width="70" style="text-align:center;">
            <?php echo $PRJCODE; ?><br>
            <span style="font-size:8px;">(Kode Proy/Ktr)</span>
          </td>
          <td width="70" style="text-align:center;">
            <?php echo $AUI_STEP; ?><br>
            <span style="font-size:8px;">(Audit Ke)</span>
          </td>
          <td width="45" style="text-align:center;">
            <?php echo $AUI_ORDNO; ?><br>
            <span style="font-size:8px;">(No Urut)</span>
          </td>
          <td style="text-align:center;">
            <?php echo $AUI_INIT; ?><br>
            <span style="font-size:8px;">(Initial Auditor)</span>
          </td>
        </tr>
      </table>
      <table border="1" rules="all" width="100%" cellpadding="0" cellspacing="0" style="border-color:black; border-top:hidden; border-left-width:2px; border-right-width:2px; border-bottom-width:1px;">
        <tr>
          <td width="90" style="text-align:right;">Dept. / Jab. *):</td>
          <td width="150"><?php echo $AUI_DEPT; ?></td>
          <td width="30" style="text-align:center;">Auditee:</td>
          <td width="150" style="text-align:left;"><?php echo $AUI_SUBJEK; ?>
          </td>
          <td width="90" style="text-align:center;">
            Lokasi Temuan:
          </td>
          <td width="92.5" style="text-align:left;"><?php echo $AUI_LOC; ?>

          </td>
          <td style="text-align:left; font-weight:bold; font-style:italic; border-bottom:hidden;">
            CC: 
          </td>
        </tr>
      </table>
      <table border="1" rules="all" width="100%" cellpadding="0" cellspacing="0" style="border-color:black; border-top:hidden; border-left-width:2px; border-right-width:2px; border-bottom-width:1px;">
        <tr>
          <td width="90" style="text-align:right;">Tgl Diaudit :</td>
          <td width="80"><?php echo $AUI_DATE; ?></td>
          <td width="85" style="text-align:center;">Tgl Ncr terbit:</td>
          <td width="80" style="text-align:left;"><?php echo $AUI_NCRD; ?></td>
          <td width="50" style="text-align:center;">
            Auditor:
          </td>
          <td width="80" style="text-align:left;"><?php echo $AUI_AUDITOR; ?></td>
          <td width="66" style="text-align:center; font-weight:bold; font-style:italic;">Ref Doc.:</td>
          <td width="92" style="text-align:left;"><?php echo $AUI_REFDOC; ?></td>
          <td style="text-align:left; font-weight:bold; font-style:italic;">&nbsp;

          </td>
        </tr>
      </table>
      <table border="1" rules="all" width="100%" cellpadding="0" cellspacing="0" style="border-color:black; border-left-width:2px; border-right-width:2px;">
        <tr>
          <td colspan="4" style="vertical-align:top;">
            <span><b>1. PENJELASAN KETIDAKSESUAIAN</b> <i>(diisi oleh Auditor/Pemeriksa)</i>:</span><br>
			<li><?php echo $AUI_PROBLDESC; ?></li></td>
          <td colspan="2">
            Klausul/Kriteria :
            <ol type="1" style="padding-left:12px; margin-top:2px; margin-bottom:2px;">
              <li>
                <b>SMK3 PP No 50, 2012:</b><br>
&nbsp;          <?php echo $AUI_KLAUS1; ?></li>
              <li>
                <b>OHSAS 18001:2007:</b><br>
&nbsp;          <?php echo $AUI_KLAUS2; ?></li>
              <li>
                <b>ISO 14001:2004:</b><br>
&nbsp;          <?php echo $AUI_KLAUS3; ?></li>
              <li>
                <b>ISO 9001:2008:</b><br>
&nbsp;          <?php echo $AUI_KLAUS4; ?></li>
            </ol>          </td>
        </tr>
        <tr>
          <td width="145" rowspan="3" style="vertical-align:top; text-align:center; font-weight:bold; border-bottom:hidden;">Auditor</td>
          <td width="154" rowspan="3" style="vertical-align:top; text-align:center; font-weight:bold;  border-bottom:hidden;">Auditee</td>
          <td width="147" rowspan="3" style="vertical-align:top; font-weight:bold; text-align:center; border-bottom:hidden;">
              <span style="text-align:center">Representative Sys (Qhse Proyek/Cab*): </span>
              <br><br><br><br>                        </td>
          <td width="144" rowspan="3" style="vertical-align:top; font-weight:bold; text-align:center; border-bottom:hidden;">
          	<span style="text-align:center">Proj. Manager /Manager/Kacab/Dir*):</span>
            <br><br><br><br>                        </td>
          <td colspan="2">Pilih dengan memberikan tanda &radic;</td>
        </tr>
        <tr>
          <td colspan="2">
          	<b>Temuan :</b>
          	<input type="checkbox" class="chk" id="minor" style="padding-top:50px;" <?php if($AUI_TYPE == 1) { ?> checked <?php } ?>><label style="font-weight:bold">Minor</label>
            <input type="checkbox" class="chk" id="mayor" style="padding-top:50px;" <?php if($AUI_TYPE == 2) { ?> checked <?php } ?>> <label style="font-weight:bold">Mayor</label>          </td>
        </tr>
        <tr>
          <td width="60" rowspan="2">Scope temuan :</td>
          <td width="132" rowspan="2">
          	<input type="checkbox" id="K3L" style="padding-top:50px;" <?php if($AUI_SCOPE1 == 1) { ?> checked <?php } ?>><label>K3L</label>
            <input type="checkbox" id="Biaya" style="padding-top:50px;" <?php if($AUI_SCOPE2 == 1) { ?> checked <?php } ?>> <label>Biaya</label>
            <br>
            <input type="checkbox" id="Mutu" style="padding-top:50px;" <?php if($AUI_SCOPE3 == 1) { ?> checked <?php } ?>><label>Mutu</label><label>&nbsp;&nbsp;&nbsp;&amp;</label><label>Waktu</label>          </td>
        </tr>
        <tr>
          <td style="vertical-align:top; text-align:center; font-weight:bold;">&nbsp;</td>
          <td style="vertical-align:top; text-align:center; font-weight:bold;">&nbsp;</td>
          <td style="vertical-align:top; font-weight:bold;"><span style="text-align:left"><i>Nama:</i></span></td>
          <td style="vertical-align:top; font-weight:bold;"><span style="text-align:left"><i>Nama:</i></span></td>
        </tr>
        <tr>
          <td colspan="3" rowspan="2" style="vertical-align:middle; font-weight:bold; text-align:right;"> Note: Butir 2 s/d 6 di bawah sudah terisi lengkap dan disampaikan AUDITOR paling lambat 3 hari sejak NCR terbit, tgl :</td>
          <td rowspan="2" style="vertical-align:top;">&nbsp;</td>
          <td style="border-bottom:hidden;">Mngt Sys</td>
          <td style="border-bottom:hidden;">
          	<input type="checkbox" id="P" style="padding-top:50px;" <?php if($AUI_SYSPROC1 == 1) { ?> checked <?php } ?>><label>P</label>
            <input type="checkbox" id="D" style="padding-top:50px;" <?php if($AUI_SYSPROC2 == 1) { ?> checked <?php } ?>> <label>D</label>          </td>
        </tr>
        <tr>
          <td height="21">Process :</td>
          <td>
          	<input type="checkbox" id="C" style="padding-top:50px;" <?php if($AUI_SYSPROC3 == 1) { ?> checked <?php } ?>><label>C</label>
            <input type="checkbox" id="A" style="padding-top:50px;" <?php if($AUI_SYSPROC4 == 1) { ?> checked <?php } ?>> <label>A</label>          </td>
        </tr>
        <tr>
          <td height="21" colspan="6" style="vertical-align:top;text-align:left;">
          	<b>2. SEBAB KETIDAKSESUAIAN</b> <i>(diisi oleh Auditee/Bagian atau pihak yang diperiksa)</i> :
            <ul class="minus" style="margin-top:2px; margin-bottom:2px; padding-left:15px;">
            	<li><?php echo $AUI_CAUSE; ?></li>
            </ul>          </td>
        </tr>
        <tr>
          <td height="21" colspan="6" style="vertical-align:top;text-align:left;">
          	<b>3. P E R B A I K A N</b> <i>(diisi/ditetapkan secara tim oleh Auditee, Koord./Ka/Chief dan Manager atau Kep. Cabang atau Dir terkait)</i> :
            <ul type="square" style="padding-left:27px; margin-top:5px;">
            	<li><b>Tindakan Perbaikan:</b> <i><u>Ditetapkan menjawab temuan butir 1 diatas berupa judul Tindakan Perbaikannya (bukan proses perbaikan)</u></i> : 
                	<ul class="minus" style="padding-left:3px; height:">
                    	<li><?php echo $AUI_CORACT; ?></li>
                    </ul>
                <li><b>Proses/ Langkah<sup>2</sup> Perbaikan (<i>Action Plan</i>):</b> <i><u>Ditetapkan berdasarkan sebab ketidak sesuaian dan tindak perbaikannya</u></i> :
                	<ul class="minus" style="padding-left:3px; margin-top:3px; margin-bottom:3px;">
                    	<li><?php echo $AUI_CORSTEP; ?></li>
                    </ul>
          </ul>          </td>
        </tr>
        <tr>
          <td height="21" colspan="6" style="vertical-align:top;text-align:left;">
          	<b>4. P E N C E G A H A N :</b> <u><i>Ditetapkan konsisten dengan proses perbaikan diatas, control, sangsi, .......</i></u>
            <ul class="minus" style="padding-left:15px; margin-top:5px;">
            	<li><?php echo $AUI_PREVENT; ?></li>
            </ul>
          </td>
        </tr>
      </table>
      <table border="1" rules="all" width="100%" cellpadding="0" cellspacing="0" style="border-color:black; border-left-width:2px; border-right-width:2px; border-top:hidden;">
      	<tr>
        	<td width="200" style="vertical-align:top">
            	<b>5. RENCANA SELESAI : </b><br><br>
                <b>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal : </b><?php echo $AUI_FINISHP; ?>            </td>
            <td>
            	<b>6. EVIDENCE/BUKTI CLOSING (TERLAMPIR) :</b>
                <ul class="minus" style="padding-left:15px; margin-top:5px;">
                	<li>&nbsp;</li>
                    <li>&nbsp;</li>
                </ul>            </td>
        </tr>
      	<tr>
      	  <td colspan="2" style="vertical-align:top">
          	<b><i>Penetapan secara tim (butir 2 s/d 6 diatas), ditanda-tangani oleh :</i></b>
          </td>
   	    </tr>
      </table>
      <table border="1" rules="all" width="100%" cellpadding="0" cellspacing="0" style="border-color:black; border-left-width:2px; border-right-width:2px; border-top:hidden;">
      	<tr>
        	<td width="250" style="border-bottom:hidden; text-align:center;"><b>Auditee</b></td>
            <td width="250" style="border-bottom:hidden; text-align:left;"><b>Representative Sys (Qhse Proyek/ Cab*):</b></td>
            <td style="border-bottom:hidden; text-align:left;"><b>Project Manager/Manager/Kacab/Dir*):</b></td>
        </tr>
      	<tr>
      	  <td style="border-bottom:hidden;">&nbsp;</td>
      	  <td style="border-bottom:hidden;">&nbsp;</td>
      	  <td style="border-bottom:hidden;">&nbsp;</td>
   	    </tr>
      	<tr>
      	  <td>&nbsp;</td>
      	  <td><b><i>Nama:</i></b></td>
      	  <td><b><i>Nama:</i></b></td>
   	    </tr>
      	<tr>
      	  <td colspan="2" rowspan="5" style="vertical-align:top; line-height:20px;">
          	<b>7. TINJAUAN ULANG TINDAKAN PERBAIKAN DAN PENCEGAHAN :</b><br>          
          	Tanggal <u> <?php echo $AUI_REVIEWD; ?> </u>, Hasil pemeriksaan ulang : <br>         
          	<input type="checkbox" id="C" style="padding-top:50px;" <?php if($AUI_CONCL == 1) { ?> checked <?php } ?>>
          	<label>Tindakan perbaikan dan pencegahan telah dilakukan dan efektif</label>
          	<br>
            <input type="checkbox" id="C" style="padding-top:50px;" <?php if($AUI_CONCL == 2) { ?> checked <?php } ?>>            <label>Tindakan perbaikan dan pencegahan belum dilakukan atau tidak efektif,<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;perlu dibuatkan NCR baru No. <u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> Tgl : <u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></label>          </td>
      	  <td> Tanggal Rencana Tinjauan Ulang : <br><span style="font-size:10px; font-style:italic;">(Bila Perbaikan Belum Efektif)</span> </td>
   	    </tr>
      	
      	<tr>
      	  <td style="border-bottom:hidden; font-weight:bold;">Ttd Auditor</td>
   	    </tr>
      	<tr>
      	  <td style="vertical-align:bottom; height:35px;">
          <span style="font-size:10px; font-weight:bold;">Nama & Tgl:</span>          </td>
   	    </tr>
      	<tr>
      	  <td style="vertical-align:bottom; border-bottom:hidden;">
          	<span style="font-weight:bold;">Tanda-tangan (Closing):<br>
            Auditor          </span></td>
   	    </tr>
      	<tr>
      	  <td style="vertical-align:bottom; height:35px;"><span style="font-size:10px; font-weight:bold;">Nama & tgl:</span></td>
   	    </tr>
      </table>
      <span style="font-size:9px; font-style:italic;"> &copy;Hak Cipta PT NUSA KONSTRUKSI ENJINIRING Tbk</span>
    </section>
  </div>
</body>
