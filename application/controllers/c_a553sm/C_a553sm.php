<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 Maret 2020
 * File Name	= C_a553sm.php
 * Location		= -
*/

class C_a553sm extends CI_Controller
{
	var $limit = 2;
	var $title = 'NKE ITSys';
	
 	public function index() // USED
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_a553sm/c_a553sm/t53Mp/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function t53Mp($offset=0) // USED
	{
		$this->load->model('m_a553sm/m_a553sm', '', TRUE);
		$Emp_ID 		= $this->session->userdata['Emp_ID'];

		$ISREAD 		= 0;
		$ISCREATE 		= 0;
		$ISAPPROVE 		= 0;
		$ISDELETE		= 0;
		$ISDWONL		= 0;
		$sqlAUTH		= "SELECT ISREAD, ISCREATE, ISAPPROVE, ISDELETE, ISDWONL
							FROM tusermenu WHERE emp_id = '$Emp_ID' AND menu_code = 'MN419'";
		$resAUTH 		= $this->db->query($sqlAUTH)->result();
		foreach($resAUTH as $rowAUTH) :
			$ISREAD 	= $rowAUTH->ISREAD;
			$ISCREATE 	= $rowAUTH->ISCREATE;
			$ISAPPROVE 	= $rowAUTH->ISAPPROVE;
			$ISDELETE	= $rowAUTH->ISDELETE;
			$ISDWONL	= $rowAUTH->ISDWONL;
		endforeach;

		if($ISDELETE == 1)
		{
			$ISREAD		= 1;
			$ISCREATE	= 1;
			$ISAPPROVE	= 1;
			$ISDWONL	= 1;
		}
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Assessment';
			$data['secAddURL'] 			= site_url('c_a553sm/c_a553sm/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_a553sm/c_a553sm/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 					= $this->m_a553sm->count_allTS($Emp_ID, $ISAPPROVE);
			$data["ASMCount"] 			= $num_rows;
			$data['MenuCode'] 			= 'MN419';
			
			// Start of Pagination
			$config 					= array();
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"]		= 4;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_a553sm/c_a553sm/get_allTS');				
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open']	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link'] 		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close'] 	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link'] 		= '&gt;&gt;';
			// End of Pagination
	 
			$this->pagination->initialize($config);
	 
			$data['ASMView'] 	= $this->m_a553sm->get_allTS($config["per_page"], $offset, $Emp_ID, $ISAPPROVE)->result();
			$data["pagination"] = $this->pagination->create_links();
			
			$this->load->view('v_a553sm/v_a553sm', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add() // USED
	{
		$this->load->model('m_a553sm/m_a553sm', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['MenuCode'] 		= 'MN419';
			$data['h2_title']		= 'Tambah Assessment';
			$data['main_view'] 		= 'v_a553sm/v_a553sm_sd_form';
			$data['form_action']	= site_url('c_a553sm/c_a553sm/add_process');
			$data['backURL'] 		= site_url('c_a553sm/c_a553sm/');
			$data['default']['VendCat_Code'] = '';
			
			$this->load->view('v_a553sm/v_a553sm_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add_process() // USED
	{
		$this->load->model('m_a553sm/m_a553sm', '', TRUE);

		// SET START DATE AND TIME
			$ASSM_DATE	= date('Y-m-d H:i:s');
			$EMP_BDATE	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('EMP_BDATE'))));
		
		$Q_1			= $this->input->post('Q_1');			// Adakah Kemungkinan Kontak dengan Pihak Luar?
		$Q_1_1			= $this->input->post('Q_1_1');			// Jika "Ya" atau "Mungkin", dengan siapa saja mungkin harus bertemu?
		$Q_1_1DESC		= $this->input->post('Q_1_1DESC');		// Seseorang yang mungkin harus ditemui
		$Q_2			= $this->input->post('Q_2');			// Pekerjaan bisa dikerjakan di Rumah?
		$Q_2_DESC		= $this->input->post('Q_2_DESC');		// Jika "TIDAK" atau "Sebagian", maka pekerjaan apa saja yang TIDAK dapat dikerjakan dari Rumah
		$Q_3			= $this->input->post('Q_3');
		$Q_4			= $this->input->post('Q_4');			// Menggunakan Angkutan Umum menuju tempat kerja?
		$Q_6			= $this->input->post('Q_6');			// Angkutan Umum Apa yang dipakai menuju tempat kerja?
		$Q_6_DESC		= $this->input->post('Q_6_DESC');		// Angkutan lainnya :
		$Q_7			= $this->input->post('Q_7');			// Jarak yang berisiko saling menularkan virus adalah 1,8 meter. 
																// Berapa jarak Anda dengan rekan kerja dalam ruangan?

		if($Q_1 == 1)
		{
			$Q_1V 			= 70;

			$Q_1_1 			= $this->input->post('Q_1_1');

			if($Q_1_1 == 1 || $Q_1_1 == 6)
				$Q_1_1V		= 20;
			else
				$Q_1_1V		= 80;

			$Q_1_1DESC		= '';
			if($Q_1_1 == 6)
				$Q_1_1DESC	= $this->input->post('Q_1_1DESC');
		}
		if($Q_1 == 2)
		{
			$Q_1V 			= 10;
			$Q_1_1 			= 0;
			$Q_1_1DESC 		= '';
		}
		else
		{
			$Q_1V 			= 20;

			$Q_1_1 			= $this->input->post('Q_1_1');

			if($Q_1_1 == 1 || $Q_1_1 == 6)
				$Q_1_1V		= 10;
			else
				$Q_1_1V		= 20;

			$Q_1_1DESC		= '';
			if($Q_1_1 == 6)
				$Q_1_1DESC	= $this->input->post('Q_1_1DESC');
		}

		if($Q_2 == 1)
		{
			$Q_2V 			= 10;
			$Q_2_DESC 		= '';
		}
		elseif($Q_2 == 2)
		{
			$Q_2V 			= 60;
			$Q_2_DESC 		= $this->input->post('Q_2_DESC');
		}
		else
		{
			$Q_2V 			= 30;
			$Q_2_DESC 		= $this->input->post('Q_2_DESC');
		}

		if($Q_3 == 1)
			$Q_3V 			= 10;
		elseif($Q_3 == 2)
			$Q_3V 			= 60;
		else
			$Q_3V 			= 30;

		if($Q_4 == 1)
		{
			$Q_4V 			= 90;

			$Q_6 			= $this->input->post('Q_6');
			$Q_6V			= 300;

			$Q_6_DESC		= '';
			if($Q_6 == 5)
				$Q_6_DESC 	= $this->input->post('Q_6_DESC');
		}
		else
		{
			$Q_4V 			= 10;

			$Q_6 			= 0;
			$Q_6V			= 0;
			$Q_6_DESC 		= '';
		}

		if($Q_7 == 1)
			$Q_7V			= 80;
		else
			$Q_7V			= 20;

		$PROB_ACUAN			= 900;
		$PROB_COUNT			= $Q_1V + $Q_1_1V + $Q_2V + $Q_3V + $Q_4V + $Q_6V + $Q_7V;
		$PROB_CONCL			= $PROB_COUNT / $PROB_ACUAN * 100;
		
		$AssEmp = array('ASSM_CODE' 	=> $this->input->post('ASSM_CODE'),
						'ASSM_DATE'		=> $ASSM_DATE,
						'EMP_ID'		=> $this->input->post('EMP_ID'),
						'EMP_NAME'		=> $this->input->post('EMP_NAME'),
						'EMP_BPLACE'	=> $this->input->post('EMP_BPLACE'),
						'EMP_BDATE'		=> $EMP_BDATE,
						'EMP_GENDER'	=> $this->input->post('EMP_GENDER'),
						'EMP_PROV'		=> $this->input->post('EMP_PROV'),
						'EMP_KAB'		=> $this->input->post('EMP_KAB'),
						'EMP_KEC'		=> $this->input->post('EMP_KEC'),
						'EMP_KEL'		=> $this->input->post('EMP_KEL'),
						'DIV_CODE'		=> $this->input->post('DIV_CODE'),
						'SEC_CODE'		=> $this->input->post('SEC_CODE'),
						'POS_NAME'		=> $this->input->post('POS_NAME'),
						'Q_1'			=> $Q_1,
						'Q_1_1'			=> $Q_1_1,
						'Q_1_1DESC'		=> $Q_1_1DESC,
						'Q_2'			=> $Q_2,
						'Q_2_DESC'		=> $Q_2_DESC,
						'Q_3'			=> $Q_3,
						'Q_4'			=> $Q_4,
						'Q_5'			=> $Q_5,
						'Q_6'			=> $Q_6,
						'Q_6_DESC'		=> $Q_6_DESC,
						'Q_7'			=> $Q_7,
						'EMP_MAIL'		=> $this->input->post('EMP_MAIL'),
						'EMP_NOHP'		=> $this->input->post('EMP_NOHP'),
						'PROB_ACUAN'	=> $PROB_ACUAN,
						'PROB_COUNT'	=> $PROB_COUNT,
						'PROB_CONCL'	=> $PROB_CONCL);

		$this->m_a553sm->add($AssEmp);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_a553sm/c_a553sm/index/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function u_p4T() // USED
	{
		$this->load->model('m_a553sm/m_a553sm', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$ASSM_CODE	= $_GET['id'];
		$ASSM_CODE	= $this->url_encryption_helper->decode_url($ASSM_CODE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Assessment';
			$data['main_view'] 		= 'v_a553sm/v_a553sm_sd_form';
			$data['form_action']	= site_url('c_a553sm/c_a553sm/update_process');
			$data['MenuCode'] 		= 'MN419';
			
			//$data['link'] 			= array('link_back' => anchor('c_a553sm/c_a553sm/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_a553sm/c_a553sm/');
			
			$getASEmp = $this->m_a553sm->get_ASEmp_Bycode($ASSM_CODE)->row();
			
			$data['default']['ASSM_CODE'] 	= $getASEmp->ASSM_CODE;
			$data['default']['ASSM_DATE']	= $getASEmp->ASSM_DATE;
			$data['default']['EMP_ID']		= $getASEmp->EMP_ID;
			$data['default']['EMP_NAME']	= $getASEmp->EMP_NAME;
			$data['default']['EMP_BPLACE']	= $getASEmp->EMP_BPLACE;
			$data['default']['EMP_BDATE']	= $getASEmp->EMP_BDATE;
			$data['default']['EMP_GENDER']	= $getASEmp->EMP_GENDER;
			$data['default']['EMP_PROV']	= $getASEmp->EMP_PROV;
			$data['default']['EMP_KAB']		= $getASEmp->EMP_KAB;
			$data['default']['EMP_KEC']		= $getASEmp->EMP_KEC;
			$data['default']['EMP_KEL']		= $getASEmp->EMP_KEL;
			$data['default']['DIV_CODE']	= $getASEmp->DIV_CODE;
			$data['default']['SEC_CODE']	= $getASEmp->SEC_CODE;
			$data['default']['POS_NAME']	= $getASEmp->POS_NAME;
			$data['default']['Q_1']			= $getASEmp->Q_1;
			$data['default']['Q_1_1']		= $getASEmp->Q_1_1;
			$data['default']['Q_1_1DESC']	= $getASEmp->Q_1_1DESC;
			$data['default']['Q_2']			= $getASEmp->Q_2;
			$data['default']['Q_2_DESC']	= $getASEmp->Q_2_DESC;
			$data['default']['Q_3']			= $getASEmp->Q_3;
			$data['default']['Q_4']			= $getASEmp->Q_4;
			$data['default']['Q_5']			= $getASEmp->Q_5;
			$data['default']['Q_6']			= $getASEmp->Q_6;
			$data['default']['Q_6_DESC']	= $getASEmp->Q_6_DESC;
			$data['default']['Q_7']			= $getASEmp->Q_7;
			$data['default']['EMP_MAIL']	= $getASEmp->EMP_MAIL;
			$data['default']['EMP_NOHP']	= $getASEmp->EMP_NOHP;
			
			$this->load->view('v_a553sm/v_a553sm_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_a553sm/m_a553sm', '', TRUE);
		
		$ASSM_CODE		= $this->input->post('ASSM_CODE');
		$EMP_BDATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('EMP_BDATE'))));

		$Q_1			= $this->input->post('Q_1');			// Adakah Kemungkinan Kontak dengan Pihak Luar?
		$Q_1_1			= $this->input->post('Q_1_1');			// Jika "Ya" atau "Mungkin", dengan siapa saja mungkin harus bertemu?
		$Q_1_1DESC		= $this->input->post('Q_1_1DESC');		// Seseorang yang mungkin harus ditemui
		$Q_2			= $this->input->post('Q_2');			// Pekerjaan bisa dikerjakan di Rumah?
		$Q_2_DESC		= $this->input->post('Q_2_DESC');		// Jika "TIDAK" atau "Sebagian", maka pekerjaan apa saja yang TIDAK dapat dikerjakan dari Rumah
		$Q_3			= $this->input->post('Q_3');
		$Q_4			= $this->input->post('Q_4');			// Menggunakan Angkutan Umum menuju tempat kerja?
		$Q_6			= $this->input->post('Q_6');			// Angkutan Umum Apa yang dipakai menuju tempat kerja?
		$Q_6_DESC		= $this->input->post('Q_6_DESC');		// Angkutan lainnya :
		$Q_7			= $this->input->post('Q_7');			// Jarak yang berisiko saling menularkan virus adalah 1,8 meter. 
																// Berapa jarak Anda dengan rekan kerja dalam ruangan?

		if($Q_1 == 1)
		{
			$Q_1V 			= 70;

			$Q_1_1 			= $this->input->post('Q_1_1');

			if($Q_1_1 == 1 || $Q_1_1 == 6)
				$Q_1_1V		= 20;
			else
				$Q_1_1V		= 80;

			$Q_1_1DESC		= '';
			if($Q_1_1 == 6)
				$Q_1_1DESC	= $this->input->post('Q_1_1DESC');
		}
		if($Q_1 == 2)
		{
			$Q_1V 			= 10;
			$Q_1_1 			= 0;
			$Q_1_1DESC 		= '';
		}
		else
		{
			$Q_1V 			= 20;

			$Q_1_1 			= $this->input->post('Q_1_1');

			if($Q_1_1 == 1 || $Q_1_1 == 6)
				$Q_1_1V		= 10;
			else
				$Q_1_1V		= 20;

			$Q_1_1DESC		= '';
			if($Q_1_1 == 6)
				$Q_1_1DESC	= $this->input->post('Q_1_1DESC');
		}

		if($Q_2 == 1)
		{
			$Q_2V 			= 10;
			$Q_2_DESC 		= '';
		}
		elseif($Q_2 == 2)
		{
			$Q_2V 			= 60;
			$Q_2_DESC 		= $this->input->post('Q_2_DESC');
		}
		else
		{
			$Q_2V 			= 30;
			$Q_2_DESC 		= $this->input->post('Q_2_DESC');
		}

		if($Q_3 == 1)
			$Q_3V 			= 10;
		elseif($Q_3 == 2)
			$Q_3V 			= 60;
		else
			$Q_3V 			= 30;

		if($Q_4 == 1)
		{
			$Q_4V 			= 90;

			$Q_6 			= $this->input->post('Q_6');
			$Q_6V			= 300;

			$Q_6_DESC		= '';
			if($Q_6 == 5)
				$Q_6_DESC 	= $this->input->post('Q_6_DESC');
		}
		else
		{
			$Q_4V 			= 10;

			$Q_6 			= 0;
			$Q_6V			= 0;
			$Q_6_DESC 		= '';
		}

		if($Q_7 == 1)
			$Q_7V			= 80;
		else
			$Q_7V			= 20;

		$PROB_ACUAN			= 900;
		$PROB_COUNT			= $Q_1V + $Q_1_1V + $Q_2V + $Q_3V + $Q_4V + $Q_6V + $Q_7V;
		$PROB_CONCL			= $PROB_COUNT / $PROB_ACUAN * 100;

		$AssEmp = array('EMP_ID'		=> $this->input->post('EMP_ID'),
						'EMP_NAME'		=> $this->input->post('EMP_NAME'),
						'EMP_BPLACE'	=> $this->input->post('EMP_BPLACE'),
						'EMP_BDATE'		=> $EMP_BDATE,
						'EMP_GENDER'	=> $this->input->post('EMP_GENDER'),
						'EMP_PROV'		=> $this->input->post('EMP_PROV'),
						'EMP_KAB'		=> $this->input->post('EMP_KAB'),
						'EMP_KEC'		=> $this->input->post('EMP_KEC'),
						'EMP_KEL'		=> $this->input->post('EMP_KEL'),
						'DIV_CODE'		=> $this->input->post('DIV_CODE'),
						'SEC_CODE'		=> $this->input->post('SEC_CODE'),
						'POS_NAME'		=> $this->input->post('POS_NAME'),
						'Q_1'			=> $Q_1,
						'Q_1_1'			=> $Q_1_1,
						'Q_1_1DESC'		=> $Q_1_1DESC,
						'Q_2'			=> $Q_2,
						'Q_2_DESC'		=> $Q_2_DESC,
						'Q_3'			=> $Q_3,
						'Q_4'			=> $Q_4,
						'Q_5'			=> $Q_5,
						'Q_6'			=> $Q_6,
						'Q_6_DESC'		=> $Q_6_DESC,
						'Q_7'			=> $Q_7,
						'EMP_MAIL'		=> $this->input->post('EMP_MAIL'),
						'EMP_NOHP'		=> $this->input->post('EMP_NOHP'),
						'PROB_ACUAN'	=> $PROB_ACUAN,
						'PROB_COUNT'	=> $PROB_COUNT,
						'PROB_CONCL'	=> $PROB_CONCL);
						
		$this->m_a553sm->update($ASSM_CODE, $AssEmp);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_a553sm/c_a553sm/index/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function viewImage()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$ID					= $_GET['id'];
			$ASSM_CODE			= $this->url_encryption_helper->decode_url($ID);
			$data['ASSM_CODE'] 	= $ASSM_CODE;
			$data['h2_title']	= 'Gallery Eviden/Bukti Closing';

			$this->load->view('v_a553sm/v_a553sm_view', $data);	
		}
		else
		{
			redirect('__I1y');
		}
	}

	function Get_kab()
	{
		$id_prov	= $this->input->post('id_prov');

		echo '<option value="0"> -- </option>';
		$sqlKAB    = "SELECT * FROM tbl_wilayah WHERE LEVEL = 2 AND CODE_P = '$id_prov'";
        $resKAB    = $this->db->query($sqlKAB)->result();
        foreach($resKAB as $rowKAB):
            $KAB_CODE  = $rowKAB->CODE;
            $KAB_NAME  = $rowKAB->NAME;

            echo '<option value="'.$KAB_CODE.'">'.$KAB_NAME.'</option>';

        endforeach;
	}

	function Get_kec()
	{
		$id_kab		= $this->input->post('id_kab');

		echo '<option value="0"> -- </option>';
		$sqlKEC    = "SELECT * FROM tbl_wilayah WHERE LEVEL = 3 AND CODE_P = '$id_kab'";
        $resKEC    = $this->db->query($sqlKEC)->result();
        foreach($resKEC as $rowKEC):
            $KEC_CODE  = $rowKEC->CODE;
            $KEC_NAME  = $rowKEC->NAME;

            echo '<option value="'.$KEC_CODE.'">'.$KEC_NAME.'</option>';

        endforeach;
	}

	function Get_kel()
	{
		$id_kec		= $this->input->post('id_kec');

		echo '<option value="0"> -- </option>';
		$sqlKEL    = "SELECT * FROM tbl_wilayah WHERE LEVEL = 4 AND CODE_P = '$id_kec'";
        $resKEL    = $this->db->query($sqlKEL)->result();
        foreach($resKEL as $rowKEL):
            $KEL_CODE  = $rowKEL->CODE;
            $KEL_NAME  = $rowKEL->NAME;

            echo '<option value="'.$KEL_CODE.'">'.$KEL_NAME.'</option>';

        endforeach;
	}
}