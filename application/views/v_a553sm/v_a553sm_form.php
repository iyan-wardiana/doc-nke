<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 Maret 2020
 * File Name	= v_a553sm_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody 	= $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$sqlGetDet			= "SELECT Emp_ID, EmpNoIdentity, CONCAT(First_Name, ' ', Last_Name) AS empName, gender, Birth_Place, Date_Of_Birth,
							Mobile_Phone, Email
						FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
$resGetDet 			= $this->db->query($sqlGetDet)->result();
foreach($resGetDet as $rowGDet) :
	$Emp_ID 		= $rowGDet ->Emp_ID;
	$EmpNoIdentity 	= $rowGDet ->EmpNoIdentity;
	$empName 		= $rowGDet ->empName;
	$gender 		= $rowGDet ->gender;
	$Birth_Place 	= $rowGDet ->Birth_Place;
	$Date_Of_Birth	= $rowGDet ->Date_Of_Birth;
	$Mobile_Phone	= $rowGDet ->Mobile_Phone;
	$Email			= $rowGDet ->Email;
endforeach;

if($task == 'add')
{
	$ASSM_CODE 	= "ASM.".date('YmdHis');
	$ASSM_DATE	= date('d/m/Y');
	$EMP_ID		= $DefEmp_ID;
	$EMP_NAME	= $empName;
	$EMP_BPLACE	= $Birth_Place;
	$EMP_BDATE	= date('d/m/Y', strtotime($Date_Of_Birth));
	$EMP_GENDER	= 'Male';
    $EMP_PROV   = 0;
    $EMP_KAB    = 0;
    $EMP_KEC    = 0;
    $EMP_KEL    = 0;
	$DIV_CODE	= '';
	$SEC_CODE	= '';
	$POS_NAME	= '';
	$Q_1		= 0;
	$Q_1_1		= 0;
	$Q_1_1DESC	= '';
	$Q_2		= 0;
	$Q_2_DESC	= '';
	$Q_3		= 0;
	$Q_4		= 0;
	$Q_5		= 0;
	$Q_6		= 0;
	$Q_6_DESC	= '';
	$Q_7		= 0;
	$EMP_MAIL	= '';
	$EMP_NOHP	= '';
}
else
{
	$ASSM_CODE 	= $default['ASSM_CODE'];
	$ASSM_DATE	= $default['ASSM_DATE'];
	$ASSM_DATE	= date('d/m/Y', strtotime($ASSM_DATE));
	$EMP_ID		= $default['EMP_ID'];
	$EMP_NAME	= $default['EMP_NAME'];
	$EMP_BPLACE	= $default['EMP_BPLACE'];
	$EMP_BDATE	= $default['EMP_BDATE'];
	$EMP_BDATE	= date('d/m/Y', strtotime($EMP_BDATE));
	$EMP_GENDER	= $default['EMP_GENDER'];
    $EMP_PROV   = $default['EMP_PROV'];
    $EMP_KAB    = $default['EMP_KAB'];
    $EMP_KEC    = $default['EMP_KEC'];
    $EMP_KEL    = $default['EMP_KEL'];
    $DIV_CODE   = $default['DIV_CODE'];
	$SEC_CODE	= $default['SEC_CODE'];
	$POS_NAME	= $default['POS_NAME'];
	$Q_1		= $default['Q_1'];
	$Q_1_1		= $default['Q_1_1'];
	$Q_1_1DESC	= $default['Q_1_1DESC'];
	$Q_2		= $default['Q_2'];
	$Q_2_DESC	= $default['Q_2_DESC'];
	$Q_3		= $default['Q_3'];
	$Q_4		= $default['Q_4'];
	$Q_5		= $default['Q_5'];
	$Q_6		= $default['Q_6'];
	$Q_6_DESC	= $default['Q_6_DESC'];
	$Q_7		= $default['Q_7'];
	$EMP_MAIL	= $default['EMP_MAIL'];
	$EMP_NOHP	= $default['EMP_NOHP'];
}
if($EMP_GENDER == 'Male')
	$EMP_GENDERD	= 'Laki-Laki';
else
	$EMP_GENDERD	= 'Perempuan';
	
$empNameAct = '';
$sqlEMP     = "SELECT CONCAT(First_Name, ' ', Last_Name) AS empName
                FROM tbl_employee
                WHERE Emp_ID = '$DefEmp_ID'";
$resEMP     = $this->db->query($sqlEMP)->result();
foreach($resEMP as $rowEMP) :
    $empNameAct = $rowEMP->empName;
endforeach;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>AdminLTE 2 | Dashboard</title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata['vers'];

          $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
          $rescss = $this->db->query($sqlcss)->result();
          foreach($rescss as $rowcss) :
              $cssjs_lnk  = $rowcss->cssjs_lnk;
              ?>
                  <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
              <?php
          endforeach;

          $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
          $rescss = $this->db->query($sqlcss)->result();
          foreach($rescss as $rowcss) :
              $cssjs_lnk1  = $rowcss->cssjs_lnk;
              ?>
                  <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
              <?php
          endforeach;
        ?>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

    <?php
    	$this->load->view('template/topbar');
    	$this->load->view('template/sidebar');

    	$ISREAD 	= $this->session->userdata['ISREAD'];
    	$ISCREATE 	= $this->session->userdata['ISCREATE'];
    	$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
    	$ISDWONL 	= $this->session->userdata['ISDWONL'];
    	$LangID 	= $this->session->userdata['LangID'];
        $appBody    = $this->session->userdata['appBody'];

    	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    	$resTransl		= $this->db->query($sqlTransl)->result();
    	foreach($resTransl as $rowTransl) :
    		$TranslCode	= $rowTransl->MLANG_CODE;
    		$LangTransl	= $rowTransl->LangTransl;
    		
    		if($TranslCode == 'Add')$Add = $LangTransl;
    		if($TranslCode == 'Edit')$Edit = $LangTransl;
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Save')$Save = $LangTransl;
    		if($TranslCode == 'Update')$Update = $LangTransl;
    		if($TranslCode == 'Back')$Back = $LangTransl;
    		if($TranslCode == 'Name')$Name = $LangTransl;
    		if($TranslCode == 'Address')$Address = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'ContactPerson')$ContactPerson = $LangTransl;
    		if($TranslCode == 'Status')$Status = $LangTransl;

    	endforeach;
    ?>
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
            <section class="content-header">
            <h1>
                <?php echo $h2_title; ?>
                <small><?php echo $empNameAct; ?></small>
              </h1>
            </section>

            <section class="content">	
                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-info"></i> Penilaian Risiko COVID-19 PT Nusa Konstruksi Enjiniring Tbk (Kary. Pusat)</h4>
                            Berikut adalah form asesmen risiko terkait pekerjaan Anda. Mohon diisi sebagai media update informasi agar perusahaan dapat mengukur dampak/risiko serta antisipasi yang tepat dalam menghadapi efek COVID-19. Kami mohon kepedulian Anda dengan cara mengisi dan submit form ini paling lambat Senin 23 Maret 2020 jam 13.00 WIB.<br>
* Wajib diisi
                        </div>
                    </div>
                    <form name="absen_form" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                        <div class="col-md-6">
                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Biodata Karyawan</h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" name="ASSM_CODE" id="ASSM_CODE" value="<?php echo $ASSM_CODE; ?>" />
                                        <label for="exampleInputEmail1">No. Pegawai (NIK) *</label>
                                        <input type="text" class="form-control" name="EMP_ID1" id="EMP_ID1" value="<?php echo $EMP_ID; ?>" readonly />
                                        <input type="hidden" class="form-control" name="EMP_ID" id="EMP_ID" value="<?php echo $EMP_ID; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Nama Pegawai *</label>
                                        <input type="text" class="form-control" name="EMP_NAME1" id="EMP_NAME1" value="<?php echo $EMP_NAME; ?>" readonly />
                                        <input type="hidden" class="form-control" name="EMP_NAME" id="EMP_NAME" value="<?php echo $EMP_NAME; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Tempat Lahir *</label>
                                        <input type="text" class="form-control" name="EMP_BPLACE1" id="EMP_BPLACE1" value="<?php echo $EMP_BPLACE; ?>" readonly />
                                        <input type="hidden" class="form-control" name="EMP_BPLACE" id="EMP_BPLACE" value="<?php echo $EMP_BPLACE; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Tanggal Lahir * <a class="text-yellow" style="font-style: italic;">(dd/mm/yyyy)</a></label>
                                        <div class="input-group date">
                                          	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                          	<input type="text" name="EMP_BDATE" class="form-control pull-left" id="datepicker1" value="<?php echo $EMP_BDATE; ?>" style="width:105px">
                                      	</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Jenis Kelamin *</label>
                                        <input type="text" class="form-control" name="EMP_GENDER1" id="EMP_GENDER1" value="<?php echo $EMP_GENDERD; ?>" readonly />
                                        <input type="hidden" class="form-control" name="EMP_GENDER" id="EMP_GENDER" value="<?php echo $EMP_GENDER; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Provinsi * <a class="text-yellow" style="font-style: italic;">(domisili saat ini)</a></label>
                                        <select name="EMP_PROV" id="EMP_PROV" class="form-control select2">
                                            <option value="0"> -- </option>
                                            <?php
                                                $sqlPROV    = "SELECT * FROM tbl_wilayah WHERE LEVEL = 1";
                                                $resPROV    = $this->db->query($sqlPROV)->result();
                                                foreach($resPROV as $rowPROV):
                                                    $PROV_CODE  = $rowPROV->CODE;
                                                    $PROV_NAME  = $rowPROV->NAME;
                                                    ?>
                                                        <option value="<?php echo $PROV_CODE; ?>" <?php if($PROV_CODE == $EMP_PROV) { ?> selected <?php } ?>> <?php echo $PROV_NAME; ?></option>
                                                    <?php
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>
                                    <?php
                                        $urlgetKab = base_url().'index.php/c_a553sm/c_a553sm/Get_kab/?';
                                        $urlgetKec = base_url().'index.php/c_a553sm/c_a553sm/Get_kec/?';
                                        $urlgetKel = base_url().'index.php/c_a553sm/c_a553sm/Get_kel/?';
                                    ?>
                                    <script type="text/javascript">
                                        $(document).ready(function()
                                        {
                                            $("#EMP_PROV").change(function()
                                            {
                                                var id_prov = $("#EMP_PROV").val();

                                                $.ajax({
                                                    type: 'POST',
                                                    url: '<?php echo $urlgetKab; ?>',
                                                    data: "id_prov="+id_prov,
                                                    success: function(msg)
                                                    {
                                                        $("select#EMP_KAB").html(msg);
                                                        
                                                    }
                                                });
                                            });


                                            $("#EMP_KAB").change(function()
                                            {
                                                var id_kab = $("#EMP_KAB").val();

                                                $.ajax({
                                                    type: 'POST',
                                                    url: '<?php echo $urlgetKec; ?>',
                                                    data: "id_kab="+id_kab,
                                                    success: function(msg)
                                                    {
                                                        $("select#EMP_KEC").html(msg);
                                                        
                                                    }
                                                });
                                            });


                                            $("#EMP_KEC").change(function()
                                            {
                                                var id_kec = $("#EMP_KEC").val();

                                                $.ajax({
                                                    type: 'POST',
                                                    url: '<?php echo $urlgetKel; ?>',
                                                    data: "id_kec="+id_kec,
                                                    success: function(msg)
                                                    {
                                                        $("select#EMP_KEL").html(msg);
                                                        
                                                    }
                                                });
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Kabupaten * <a class="text-yellow" style="font-style: italic;">(domisili saat ini)</a></label>
                                        <select name="EMP_KAB" id="EMP_KAB" class="form-control select2">
                                            <option value="0"> -- </option>
                                            <?php if($EMP_PROV != '') 
                                                {
                                                    $sqlKAB    = "SELECT * FROM tbl_wilayah WHERE LEVEL = 2 AND CODE_P = '$EMP_PROV'";
                                                    $resKAB    = $this->db->query($sqlKAB)->result();
                                                    foreach($resKAB as $rowKAB):
                                                        $KAB_CODE  = $rowKAB->CODE;
                                                        $KAB_NAME  = $rowKAB->NAME;
                                                        ?>
                                                            <option value="<?php echo $KAB_CODE; ?>" <?php if($KAB_CODE == $EMP_KAB) { ?> selected <?php } ?>> <?php echo $KAB_NAME; ?></option>
                                                        <?php
                                                    endforeach;
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Kecamatan * <a class="text-yellow" style="font-style: italic;">(domisili saat ini)</a></label>
                                        <select name="EMP_KEC" id="EMP_KEC" class="form-control select2">
                                            <option value="0"> -- </option>
                                            <?php if($EMP_KAB != '') 
                                                {
                                                    $sqlKEC    = "SELECT * FROM tbl_wilayah WHERE LEVEL = 3 AND CODE_P = '$EMP_KAB'";
                                                    $resKEC    = $this->db->query($sqlKEC)->result();
                                                    foreach($resKEC as $rowKEC):
                                                        $KEC_CODE  = $rowKEC->CODE;
                                                        $KEC_NAME  = $rowKEC->NAME;
                                                        ?>
                                                            <option value="<?php echo $KEC_CODE; ?>" <?php if($KEC_CODE == $EMP_KEC) { ?> selected <?php } ?>> <?php echo $KEC_NAME; ?></option>
                                                        <?php
                                                    endforeach;
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Kelurahan * <a class="text-yellow" style="font-style: italic;">(domisili saat ini)</a></label>
                                        <select name="EMP_KEL" id="EMP_KEL" class="form-control select2">
                                            <option value="0"> -- </option>
                                            <?php if($EMP_KEC != '') 
                                                {
                                                    $sqlKEL    = "SELECT * FROM tbl_wilayah WHERE LEVEL = 4 AND CODE_P = '$EMP_KEC'";
                                                    $resKEL    = $this->db->query($sqlKEL)->result();
                                                    foreach($resKEL as $rowKEL):
                                                        $KEL_CODE  = $rowKEL->CODE;
                                                        $KEL_NAME  = $rowKEL->NAME;
                                                        ?>
                                                            <option value="<?php echo $KEL_CODE; ?>" <?php if($KEL_CODE == $EMP_KEL) { ?> selected <?php } ?>> <?php echo $KEL_NAME; ?></option>
                                                        <?php
                                                    endforeach;
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Dir/Dept/Biro/Div/Unit *</label>
                                        <select name="DIV_CODE" id="DIV_CODE" class="form-control select2">
                                            <option value="0"> -- </option>
                                            <?php
                                                $sqlDEPT    = "SELECT * FROM tbl_dept";
                                                $resDEPT    = $this->db->query($sqlDEPT)->result();
                                                foreach($resDEPT as $rowDEPT):
                                                    $DEPT_CODE  = $rowDEPT->DEPT_CODE;
                                                    $DEPT_NAME  = $rowDEPT->DEPT_NAME;
                                                    ?>
                                                        <option value="<?php echo $DEPT_CODE; ?>" <?php if($DEPT_CODE == $DIV_CODE) { ?> selected <?php } ?>> <?php echo $DEPT_NAME; ?></option>
                                                    <?php
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Bagian / Proyek *</label>
                                        <input type="text" class="form-control" placeholder="Bagian / Proyek" name="SEC_CODE" id="SEC_CODE" value="<?php echo $SEC_CODE; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Jabatan *</label>
                                        <input type="text" class="form-control" placeholder="Jabatan" name="POS_NAME" id="POS_NAME" value="<?php echo $POS_NAME; ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Kemungkinan Penyebab Risiko</h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Adakah Kemungkinan Kontak dengan Pihak Luar? *</label>
                                        <select name="Q_1" id="Q_1" class="form-control select2" onChange="chgQ_1(this.value)">
                                        	<option value="0"> -- </option>
                                        	<option value="1" <?php if($Q_1 == 1) { ?> selected <?php } ?>> Ya </option>
                                        	<option value="2" <?php if($Q_1 == 2) { ?> selected <?php } ?>> Tidak </option>
                                        	<option value="3" <?php if($Q_1 == 3) { ?> selected <?php } ?>> Mungkin </option>
                                        </select>
                                    </div>
                                    <script>
                                        function chgQ_1(thisVal)
                                        {
                                            if(thisVal == 2)
                                            {
                                                document.getElementById('Q_1_1').disabled = true;
                                                $('#Q_1_1').val(0).trigger('change');
                                                document.getElementById('Q11_DESC').style.display = 'none';
                                            }
                                            else
                                            {
                                                document.getElementById('Q_1_1').disabled = false;
                                                document.getElementById('Q11_DESC').style.display = '';
                                            }
                                        }
                                    </script>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Jika <a class="text-green">"Ya"</a> atau <a class="text-yellow">"Mungkin"</a>, dengan siapa saja mungkin harus bertemu?</label>
                                        <select name="Q_1_1" id="Q_1_1" class="form-control select2" onChange="chgQ_1_1(this.value)" <?php if($Q_1 == 2) { ?> disabled <?php } ?>>
                                        	<option value="0"> -- </option>
                                        	<option value="1" <?php if($Q_1_1 == 1) { ?> selected <?php } ?>> Tim Internal </option>
                                        	<option value="2" <?php if($Q_1_1 == 2) { ?> selected <?php } ?>> Owner </option>
                                        	<option value="3" <?php if($Q_1_1 == 3) { ?> selected <?php } ?>> Konsultan </option>
                                        	<option value="4" <?php if($Q_1_1 == 4) { ?> selected <?php } ?>> Pemasok </option>
                                        	<option value="5" <?php if($Q_1_1 == 5) { ?> selected <?php } ?>> Pekerja </option>
                                        	<option value="6" <?php if($Q_1_1 == 6) { ?> selected <?php } ?>> Lainnya : </option>
                                        </select>
                                    </div>
                                    <script>
										function chgQ_1_1(thisVal)
										{
											if(thisVal == 6)
												document.getElementById('Q11_DESC').style.display = '';
											else
												document.getElementById('Q11_DESC').style.display = 'none';
										}
									</script>
                                    <div id="Q11_DESC" class="form-group" <?php if($Q_1_1 != 6) { ?> style="display:none" <?php } ?>>
                                        <label for="exampleInputPassword1">Seseorang yang mungkin harus ditemui</label>
                                        <input type="text" class="form-control" placeholder="Lainnya" name="Q_1_1DESC" id="Q_1_1DESC" value="<?php echo $Q_1_1DESC; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Pekerjaan bisa dikerjakan di Rumah? *</label>
                                        <select name="Q_2" id="Q_2" class="form-control select2" onChange="chgQ_2(this.value)">
                                        	<option value="0"> -- </option>
                                        	<option value="1" <?php if($Q_2 == 1) { ?> selected <?php } ?>> Ya </option>
                                        	<option value="2" <?php if($Q_2 == 2) { ?> selected <?php } ?>> Tidak </option>
                                        	<option value="3" <?php if($Q_2 == 3) { ?> selected <?php } ?>> Sebagian </option>
                                        </select>
                                    </div>
                                    <script>
                                        function chgQ_2(thisVal)
                                        {
                                            if(thisVal == 1)
                                                document.getElementById('Q_2_DESC').disabled = true;
                                            else
                                                document.getElementById('Q_2_DESC').disabled = false;
                                        }
                                    </script>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Jika <a class="text-red">"TIDAK"</a> atau <a class="text-yellow">"Sebagian"</a>, maka pekerjaan apa saja yang <a class="text-red">TIDAK</a> dapat dikerjakan dari Rumah?</label>
                                        <textarea class="form-control" name="Q_2_DESC"  id="Q_2_DESC" style="height:70px;" ><?php echo set_value('Q_2_DESC', isset($default['Q_2_DESC']) ? $default['Q_2_DESC'] : ''); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Apakah pekerjaan dapat dilakukan menggunakan sistem server NKE? *</label>
                                        <select name="Q_3" id="Q_3" class="form-control select2">
                                        	<option value="0"> -- </option>
                                        	<option value="1" <?php if($Q_3 == 1) { ?> selected <?php } ?>> Ya </option>
                                        	<option value="2" <?php if($Q_3 == 2) { ?> selected <?php } ?>> Tidak </option>
                                        	<option value="3" <?php if($Q_3 == 3) { ?> selected <?php } ?>> Sebagian </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Menggunakan Angkutan Umum menuju tempat kerja? *</label>
                                        <select name="Q_4" id="Q_4" class="form-control select2" onChange="chgQ_4(this.value)">
                                        	<option value="0"> -- </option>
                                        	<option value="1" <?php if($Q_4 == 1) { ?> selected <?php } ?>> Ya </option>
                                        	<option value="2" <?php if($Q_4 == 2) { ?> selected <?php } ?>> Tidak </option>
                                        </select>
                                    </div>
                                    <script>
                                        function chgQ_4(thisVal)
                                        {
                                            if(thisVal == 2)
                                            {
                                                document.getElementById('Q_6').disabled = true;
                                                $('#Q_6').val(0).trigger('change');
                                                document.getElementById('Q_6_DESC').style.display = 'none';
                                            }
                                            else
                                            {
                                                document.getElementById('Q_6').disabled = false;
                                                document.getElementById('Q_6_DESC').style.display = '';
                                            }
                                        }
                                    </script>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Angkutan Umum Apa yang dipakai menuju tempat kerja?</label>
                                        <select name="Q_6" id="Q_6" class="form-control select2" onChange="chgQ_6(this.value)">
                                        	<option value="0"> -- </option>
                                        	<option value="1" <?php if($Q_6 == 1) { ?> selected <?php } ?>> Ojek/ojek Online </option>
                                        	<option value="2" <?php if($Q_6 == 2) { ?> selected <?php } ?>> Taxi/taxi Online </option>
                                        	<option value="3" <?php if($Q_6 == 3) { ?> selected <?php } ?>> Bus </option>
                                        	<option value="4" <?php if($Q_6 == 4) { ?> selected <?php } ?>> Kereta </option>
                                        	<option value="5" <?php if($Q_6 == 5) { ?> selected <?php } ?>> Lainnya : </option>
                                        </select>
                                    </div>
                                    <script>
										function chgQ_6(thisVal)
										{
											if(thisVal == 5)
												document.getElementById('Q6_DESC').style.display = '';
											else
												document.getElementById('Q6_DESC').style.display = 'none';
										}
									</script>
                                    <div id="Q6_DESC" class="form-group" <?php if($Q_6 != 5) { ?> style="display:none" <?php } ?>>
                                        <label for="exampleInputPassword1">Angkutan lainnya : </label>
                                        <input type="text" class="form-control" placeholder="Lainnya" name="Q_6_DESC" id="Q_6_DESC" value="<?php echo $Q_6_DESC; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Jarak yang berisiko saling menularkan virus adalah 1,8 meter. Berapa jarak Anda dengan rekan kerja dalam ruangan? *</label>
                                        <select name="Q_7" id="Q_7" class="form-control select2">
                                        	<option value="0"> -- </option>
                                        	<option value="1" <?php if($Q_7 == 1) { ?> selected <?php } ?>> Kurang dari 1,8 m </option>
                                        	<option value="2" <?php if($Q_7 == 2) { ?> selected <?php } ?>> Lebih dari 1,8 m </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Update alamat E-mail *</label>
                                        <input type="email" class="form-control" placeholder="Email" name="EMP_MAIL" id="EMP_MAIL" value="<?php echo $EMP_MAIL; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Update Nomor WhatsApp *</label>
                                        <p class="text-green">Silakan tulis nomor WhatsApp anda dengan format 62xxx...dst (contoh: 62818213105)</p>
                                        <input type="text" class="form-control" name="EMP_NOHP" id="EMP_NOHP" value="<?php echo $EMP_NOHP; ?>"
                                        data-inputmask="'mask': ['628-999-9999-[999]', '+099 99 99 9999[9]-9999']" data-mask>
                                    </div>
                                    <div class="form-group has-error">
                                        <label class="control-label" for="inputError"><i class="fa fa-bell-o"></i> PERINGATAN ... !!!</label>
                                        <span class="help-block">Silahkan periksa kembali isian Anda sebelum disimpan.</span>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <?php	
										echo anchor("$backURL",'<button class="btn btn-danger" type="button">'.$Back.'</button>');
									?>
                                    <button class="btn btn-primary pull-right"><?php echo $Save; ?></button>
                                </div>
                            </div>
                        </div>
					</form>
                </div>
            </section>
        </div>
    </body>
</html>

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $.fn.datepicker.defaults.format = "dd/mm/yyyy";
    $('#datepicker1').datepicker({
      autoclose: true,
      endDate: '+1d'
    });

    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker3').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>

<script>
	function checkInp()
	{
        EMP_PROV    = document.getElementById("EMP_PROV").value;
        EMP_KAB     = document.getElementById("EMP_KAB").value;
        EMP_KEC     = document.getElementById("EMP_KEC").value;
        EMP_KEL     = document.getElementById("EMP_KEL").value;
        DIV_CODE    = document.getElementById("DIV_CODE").value;
		SEC_CODE	= document.getElementById("SEC_CODE").value;
		POS_NAME	= document.getElementById("POS_NAME").value;
		EMP_MAIL	= document.getElementById("EMP_MAIL").value;
		EMP_NOHP	= document.getElementById("EMP_NOHP").value;
		Q_1			= document.getElementById("Q_1").value;
		Q_1_1		= document.getElementById("Q_1_1").value;
		Q_1_1DESC	= document.getElementById("Q_1_1DESC").value;
		Q_2			= document.getElementById("Q_2").value;
		Q_2_DESC	= document.getElementById("Q_2_DESC").value;
		Q_3			= document.getElementById("Q_3").value;
		Q_4			= document.getElementById("Q_4").value;
		Q_7			= document.getElementById("Q_7").value;
        
        if(EMP_PROV == 0)
        {
            alert("Pilih Provinsi.");
            document.getElementById("EMP_PROV").focus();
            return false;
        }
        
        if(EMP_KAB == 0)
        {
            alert("Pilih Kabupaten/Kota.");
            document.getElementById("EMP_KAB").focus();
            return false;
        }
        
        if(EMP_KEC == 0)
        {
            alert("Pilih Kecamatan.");
            document.getElementById("EMP_KEC").focus();
            return false;
        }
        
        if(EMP_KEL == 0)
        {
            alert("Pilih Kelurahan.");
            document.getElementById("EMP_KEL").focus();
            return false;
        }
        
        if(DIV_CODE == '')
        {
            alert("Nama Divisi / Unit / Departemen tidak boleh kosong.");
            document.getElementById("DIV_CODE").focus();
            return false;
        }
		
		if(SEC_CODE == '')
		{
			alert("Nama Bagian / Proyek tidak boleh kosong.");
			document.getElementById("SEC_CODE").focus();
			return false;
		}
		
		if(POS_NAME == '')
		{
			alert("Nama jabatan tidak boleh kosong.");
			document.getElementById("POS_NAME").focus();
			return false;
		}
		
		if(EMP_MAIL == '')
		{
			alert("Alamat email tidak boleh kosong.");
			document.getElementById("EMP_MAIL").focus();
			return false;
		}
		
		if(EMP_NOHP == '')
		{
			alert("No HP / Whatsapp tidak boleh kosong.");
			document.getElementById("EMP_NOHP").focus();
			return false;
		}
		
		if(Q_1 == 0)
		{
			alert("Tentukan pilihan : Adakah Kemungkinan Kontak dengan Pihak Luar?.");
			document.getElementById("Q_1").focus();
			return false;
		}
		
		if(Q_1_1 == 6 && Q_1_1DESC == '')
		{
			alert("Masukan dengan siapa saja mungkin harus bertemu.");
			document.getElementById("Q_1_1DESC").focus();
			return false;
		}
		
		if(Q_2 == 0)
		{
			alert("Tentukan apakah pekerjaan bisa dikerjakan di Rumah?");
			document.getElementById("Q_1_1DESC").focus();
			return false;
		}
		
		if((Q_2 == 2 || Q_2 == 3) && Q_2_DESC == '')
		{
			alert("Masukan pekerjaan apa saja yang TIDAK dapat dikerjakan dari Rumah.");
			document.getElementById("Q_2_DESC").focus();
			return false;
		}
		
		if(Q_3 == 0)
		{
			alert("Tentukan pilihan apakah pekerjaan dapat menggunakan Simpro atau tidak.");
			document.getElementById("Q_3").focus();
			return false;
		}
		
		if(Q_4 == 0)
		{
			alert("Tentukan pilihan apakah menggunakan Angkutan Umum menuju tempat kerja.");
			document.getElementById("Q_4").focus();
			return false;
		}
		
		if(Q_4 == 0)
		{
			alert("Tentukan pilihan seberapa jarak Anda dengan rekan kerja dalam ruangan.");
			document.getElementById("Q_4").focus();
			return false;
		}
	}
</script>
<?php
	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

	// Right side column. contains the Control Panel
	$this->load->view('template/aside');

	$this->load->view('template/js_data');

	$this->load->view('template/foot');
?>