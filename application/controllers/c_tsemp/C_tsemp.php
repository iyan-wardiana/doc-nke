<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Maret 2020
 * File Name	= C_tsemp.php
 * Location		= -
*/

class C_tsemp extends CI_Controller
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
		
		$url			= site_url('c_tsemp/c_tsemp/t53Mp/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function t53Mp($offset=0) // USED
	{
		$this->load->model('m_tsemp/m_tsemp', '', TRUE);
		$Emp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Catatan Harian';
			$data['secAddURL'] 			= site_url('c_tsemp/c_tsemp/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_tsemp/c_tsemp/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 					= $this->m_tsemp->count_allTS($Emp_ID);
			$data["TSCount"] 			= $num_rows;
			$data['MenuCode'] 			= 'MN420';
			
			// Start of Pagination
			$config 					= array();
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"]		= 4;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_tsemp/c_tsemp/get_allTS');				
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
	 
			$data['TSView'] 	= $this->m_tsemp->get_allTS($config["per_page"], $offset, $Emp_ID)->result();
			$data["pagination"] = $this->pagination->create_links();
			
			$this->load->view('v_tsemp/v_tsemp', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add() // USED
	{
		$this->load->model('m_tsemp/m_tsemp', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['MenuCode'] 		= 'MN420';
			$data['h2_title']		= 'Tambah Catatan';
			$data['main_view'] 		= 'v_tsemp/v_tsemp_sd_form';
			$data['form_action']	= site_url('c_tsemp/c_tsemp/add_process');
			$data['backURL'] 		= site_url('c_tsemp/c_tsemp/');
			$data['default']['VendCat_Code'] = '';
			
			$this->load->view('v_tsemp/v_tsemp_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add_process() // USED
	{
		$this->load->model('m_tsemp/m_tsemp', '', TRUE);
		
		// SET START DATE AND TIME
				$EMPTS_DATE		= date('Y-m-d',strtotime($this->input->post('EMPTS_DATE')));
				
				$EMPTS_STIME 	= date('H:i:s',strtotime($this->input->post('EMPTS_STIME')));
				$EMPTS_ETIME	= date('H:i:s',strtotime($this->input->post('EMPTS_ETIME')));
		
		$jobEmp = array('EMPTS_CODE' 	=> $this->input->post('EMPTS_CODE'),
						'EMP_ID'		=> $this->input->post('EMP_ID'),
						'EMPTS_DATE'	=> $EMPTS_DATE,
						'EMPTS_STIME'	=> $EMPTS_STIME,
						'EMPTS_ETIME'	=> $EMPTS_ETIME,
						'EMPTS_DESK'	=> $this->input->post('EMPTS_DESK'),
						'EMPTS_PERSON'	=> $this->input->post('EMPTS_PERSON'));

		$this->m_tsemp->add($jobEmp);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_tsemp/c_tsemp/index/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function u_p4T() // USED
	{
		$this->load->model('m_tsemp/m_tsemp', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$EMPTS_CODE	= $_GET['id'];
		$EMPTS_CODE	= $this->url_encryption_helper->decode_url($EMPTS_CODE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Catatan';
			$data['main_view'] 		= 'v_tsemp/v_tsemp_sd_form';
			$data['form_action']	= site_url('c_tsemp/c_tsemp/update_process');
			$data['MenuCode'] 		= 'MN420';
			
			//$data['link'] 			= array('link_back' => anchor('c_tsemp/c_tsemp/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_tsemp/c_tsemp/');
			
			$getTSEmp = $this->m_tsemp->get_TSEmp_Bycode($EMPTS_CODE)->row();
			
			$data['default']['EMPTS_CODE'] 	= $getTSEmp->EMPTS_CODE;
			$data['default']['EMP_ID'] 		= $getTSEmp->EMP_ID;
			$data['default']['EMPTS_DATE'] 	= $getTSEmp->EMPTS_DATE;
			$data['default']['EMPTS_STIME'] = $getTSEmp->EMPTS_STIME;
			$data['default']['EMPTS_ETIME'] = $getTSEmp->EMPTS_ETIME;
			$data['default']['EMPTS_DESK'] 	= $getTSEmp->EMPTS_DESK;
			$data['default']['EMPTS_PERSON']= $getTSEmp->EMPTS_PERSON;
			
			$this->load->view('v_tsemp/v_tsemp_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_tsemp/m_tsemp', '', TRUE);
		
		$EMPTS_CODE		= $this->input->post('EMPTS_CODE');
		$EMPTS_DATE		= date('Y-m-d',strtotime($this->input->post('EMPTS_DATE')));
		$EMPTS_STIME 	= date('H:i:s',strtotime($this->input->post('EMPTS_STIME')));
		$EMPTS_ETIME	= date('H:i:s',strtotime($this->input->post('EMPTS_ETIME')));
		
		$jobEmp = array('EMP_ID'		=> $this->input->post('EMP_ID'),
						'EMPTS_DATE'	=> $EMPTS_DATE,
						'EMPTS_STIME'	=> $EMPTS_STIME,
						'EMPTS_ETIME'	=> $EMPTS_ETIME,
						'EMPTS_DESK'	=> $this->input->post('EMPTS_DESK'),
						'EMPTS_PERSON'	=> $this->input->post('EMPTS_PERSON'));
						
		$this->m_tsemp->update($EMPTS_CODE, $jobEmp);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_tsemp/c_tsemp/index/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function s19niN()
	{
		$this->load->model('m_tsemp/m_tsemp', '', TRUE);

		date_default_timezone_set("Asia/Jakarta");

		$Emp_ID		= $this->input->post('EMPID');
		$empName	= '';
		$sqlEMP 	= "SELECT CONCAT(First_Name, ' ', Last_Name) AS empName
						FROM tbl_employee
						WHERE Emp_ID = '$Emp_ID'";
		$resEMP 	= $this->db->query($sqlEMP)->result();
		foreach($resEMP as $rowEMP) :
			$empName	= $rowEMP->empName;
		endforeach;

		
		// SET START DATE AND TIME
			$ABS_CODE		= "ABS.".date('YmdHis');
			$ABS_DATE		= date('Y-m-d');
			$ABS_DATEI		= date('Y-m-d H:i:s');
		
		$absInp = array('ABS_CODE' 		=> $ABS_CODE,
						'EMP_ID'		=> $Emp_ID,
						'ABS_DATE'		=> $ABS_DATE,
						'ABS_DATEI'		=> $ABS_DATEI,
						'ABS_CREATED'	=> $ABS_DATEI);

		$this->m_tsemp->addAbs($absInp);

		echo $empName;
	}
	
	function s19noUt()
	{
		$this->load->model('m_tsemp/m_tsemp', '', TRUE);
		date_default_timezone_set("Asia/Jakarta");

		$Emp_ID		= $this->input->post('EMPID');
		$empName	= '';
		$sqlEMP 	= "SELECT CONCAT(First_Name, ' ', Last_Name) AS empName
						FROM tbl_employee
						WHERE Emp_ID = '$Emp_ID'";
		$resEMP 	= $this->db->query($sqlEMP)->result();
		foreach($resEMP as $rowEMP) :
			$empName	= $rowEMP->empName;
		endforeach;

		/*$sqlLOG	= "SELECT ABS_CODE FROM tbl_absensi WHERE ABS_STAT = 0 AND EMP_ID = '$Emp_ID'";
        $resLOG	= $this->db->query($sqlLOG)->result();
        foreach ($resLOG as $rowLOG) :
            $EMP_ID			= $rowLOG->EMP_ID;
            $ABS_DATEI		= $rowLOG->ABS_DATEI;
        endforeach;*/
		
		// SET START DATE AND TIME
			$ABS_CODE		= "ABS.".date('YmdHis');
			$ABS_DATE		= date('Y-m-d');
			$ABS_DATEO		= date('Y-m-d H:i:s');
		
		$absInp = array('ABS_CODE' 		=> $ABS_CODE,
						'EMP_ID'		=> $Emp_ID,
						'ABS_DATE'		=> $ABS_DATE,
						'ABS_DATEO'		=> $ABS_DATEO,
						'ABS_CREATED'	=> $ABS_DATEO);

		$this->m_tsemp->addAbs($absInp);

		echo $empName;
	}

	function d41lYr3p()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_tsemp/c_tsemp/d41lYr3p1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function d41lYr3p1() // USED
	{
		$this->load->model('m_tsemp/m_tsemp', '', TRUE);
		$Emp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 		= $appName;
			$data['secAbsDR'] 	= site_url('c_tsemp/c_tsemp/absDR/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['secAbsAR'] 	= site_url('c_tsemp/c_tsemp/absAR/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_tsemp/v_daily_rep', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function absDR() // USED
	{
		$this->load->model('m_tsemp/m_tsemp', '', TRUE);
		$Emp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Daftar Kehadiran Harian';
			
			$this->load->view('v_tsemp/v_absen_rep', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function absAR() // USED
	{
		$this->load->model('m_tsemp/m_tsemp', '', TRUE);
		$Emp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Daftar Penilaian Risiko';
			
			$this->load->view('v_tsemp/v_riskasm_idx', $data);
		}
		else
		{
			redirect('login');
		}
	}

	function Get_Emp()
	{
		$id_div	= $this->input->post('id_div');

		echo '<option value="0"> -- </option>';
		echo '<option value="All"> -- All -- </option>';
		if($id_div == 'All')
		{
			$sqlEMP    = "SELECT EMP_ID, EMP_NAME FROM tbl_assesment";
	        $resEMP    = $this->db->query($sqlEMP)->result();
	        foreach($resEMP as $rowEMP):
	            $EMP_ID  	= $rowEMP->EMP_ID;
	            $EMP_NAME  	= $rowEMP->EMP_NAME;

	            echo '<option value="'.$EMP_ID.'">'.$EMP_NAME.'</option>';

	        endforeach;
		}
		else
		{
			$sqlEMP    = "SELECT EMP_ID, EMP_NAME FROM tbl_assesment WHERE DIV_CODE = '$id_div'";
	        $resEMP    = $this->db->query($sqlEMP)->result();
	        foreach($resEMP as $rowEMP):
	            $EMP_ID  	= $rowEMP->EMP_ID;
	            $EMP_NAME  	= $rowEMP->EMP_NAME;

	            echo '<option value="'.$EMP_ID.'">'.$EMP_NAME.'</option>';

	        endforeach;
		}
	}
	
	function absDL() // USED
	{
		$this->load->model('m_tsemp/m_tsemp', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$id_div	= $this->input->post('DIV_CODE');
		$emp_ID	= $this->input->post('EMP_ID');
		$riskLV	= $this->input->post('RISK_LEV');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			$data['DIV_CODE'] 	= $id_div;
			$data['EMP_ID'] 	= $emp_ID;
			$data['RISK_LEV'] 	= $riskLV;
			$data['h2_title']	= 'Daftar Penilaian Risiko';
			
			$this->load->view('v_tsemp/v_riskasm_dl', $data);
		}
		else
		{
			redirect('login');
		}
	}
}