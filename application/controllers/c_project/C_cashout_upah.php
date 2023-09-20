<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 Oktober 2017
 * File Name	= C_cashout_upah.php
 * Location		= -
*/


class C_cashout_upah extends CI_Controller  
{
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/C_cashout_upah/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function index1()
	{
		$this->load->model('m_project/m_cashout_upah/m_cashout_upah', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$num_rows 			= $this->m_cashout_upah->count_all_prj($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN332';	 
			$data['viewPRJ'] 	= $this->m_cashout_upah->get_all_prj($DefEmp_ID)->result();
			
			$this->load->view('v_project/v_cashout_upah/project_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function get_all_COU()
	{
		$this->load->model('m_project/m_cashout_upah/m_cashout_upah', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/C_cashout_upah/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/C_cashout_upah/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$num_rows 			= $this->m_cashout_upah->count_all_num_rowsCOU($PRJCODE);
			$data["countCOU"] 	= $num_rows;
	 
			$data['vwprojcou'] = $this->m_cashout_upah->get_all_COU($PRJCODE)->result();
			
			$this->load->view('v_project/v_cashout_upah/cashout_upah', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add()
	{
		$this->load->model('m_project/m_cashout_upah/m_cashout_upah', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Cash Out Upah';
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_project/C_cashout_upah/add_process');
			$data['backURL'] 	= site_url('c_project/C_cashout_upah/get_all_COU/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_cashout_upah->count_all_num_rowsProject();
			$data['vwPRJ'] 		= $this->m_cashout_upah->viewProject()->result();
			
			$MenuCode 			= 'MN332';
			$data["MenuCode"] 	= 'MN332';
			$data['vwDocPatt'] 	= $this->m_cashout_upah->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_project/v_cashout_upah/cashout_upah_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function genCode()
	{
		$PRJCODEX	= $this->input->post('PRJCODEX');
		$PattCode	= $this->input->post('Pattern_Code');
		$PattLength	= $this->input->post('Pattern_Length');
		$useYear	= $this->input->post('useYear');
		$useMonth	= $this->input->post('useMonth');
		$useDate	= $this->input->post('useDate');
		
		$PRDate		= date('Y-m-d',strtotime($this->input->post('PRDate')));
		$year		= date('Y',strtotime($this->input->post('PRDate')));
		$month 		= (int)date('m',strtotime($this->input->post('PRDate')));
		$date 		= (int)date('d',strtotime($this->input->post('PRDate')));
		
		$this->db->where('Patt_Year', $year);
		$myCount = $this->db->count_all('tbl_pr_header');
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_pr_header
					WHERE Patt_Year = $year AND PRJCODE = '$PRJCODEX'";
		$result = $this->db->query($sql)->result();
		if($myCount>0)
		{
			$myMax	= 0;
			foreach($result as $row) :
				$myMax = $row->maxNumber;
				$myMax = $myMax+1;
			endforeach;
		}	
		else
		{
			$myMax = 1;
		}
		
		$thisMonth = $month;
	
		$lenMonth = strlen($thisMonth);
		if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
		$pattMonth = $nolMonth.$thisMonth;
		
		$thisDate = $date;
		$lenDate = strlen($thisDate);
		if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
		$pattDate = $nolDate.$thisDate;
		
		// group year, month and date
		$year = substr($year,2,2);
		if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
			$groupPattern = "$year$pattMonth$pattDate";
		elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
			$groupPattern = "$year$pattMonth";
		elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
			$groupPattern = "$year$pattDate";
		elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
			$groupPattern = "$pattMonth$pattDate";
		elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
			$groupPattern = "$year";
		elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
			$groupPattern = "$pattMonth";
		elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
			$groupPattern = "$pattDate";
		elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
			$groupPattern = "";
			
		$lastPatternNumb = $myMax;
		$lastPatternNumb1 = $myMax;
		$len = strlen($lastPatternNumb);
		
		if($PattLength==2)
		{
			if($len==1) $nol="0";
		}
		elseif($PattLength==3)
		{if($len==1) $nol="00";else if($len==2) $nol="0";
		}
		elseif($PattLength==4)
		{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
		}
		elseif($PattLength==5)
		{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
		}
		elseif($PattLength==6)
		{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
		}
		elseif($PattLength==7)
		{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
		}
		$lastPatternNumb	= $nol.$lastPatternNumb;
		$DocNumber 			= "$PattCode$PRJCODEX$groupPattern-$lastPatternNumb";
		echo "$DocNumber~$lastPatternNumb";		
	}
	
	function popupallitem()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_purchase/m_cashout_upah/m_cashout_upah', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'List Item';
			$data['form_action']		= site_url('c_project/C_cashout_upah/update_process');
			$data['PRJCODE'] 			= $PRJCODE;
			$data['secShowAll']			= site_url('c_project/C_cashout_upah/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['recordcountAllItem'] = $this->m_cashout_upah->count_all_num_rowsAllItem();
			$data['viewAllItem'] 		= $this->m_cashout_upah->viewAllItemMatBudget($PRJCODE)->result();
					
			$this->load->view('v_purchase/v_purchase_req/project_reqselectitem', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process()
	{
		$this->load->model('m_project/m_cashout_upah/m_cashout_upah', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		$COU_Periode 		= date('Y-m-d',strtotime($this->input->post('COU_Periode')));
		
		$COU_Created	= date('Y-m-d H:i:s');
		if ($this->session->userdata('login') == TRUE)
		{			
	
			$projcou = array('COU_PeriodeCode' 	=> $this->input->post('COU_PeriodeCode'),
							'COU_Periode' 			=> $COU_Periode,
							'PRJCODE' 				=> $this->input->post('PRJCODE'),
							'COU_ExpanceName' 			=> $this->input->post('ExpanceName'),
							'COU_ValuePlan' 			=> $this->input->post('ValuePlan'),
							'COU_RealizationValue'		=> $this->input->post('RealizationValue'),
							'COU_Created' 			=> $COU_Created,
							'COU_EMP' 				=> $DefEmp_ID);
							
			$this->m_cashout_upah->add($projcou);
			
			$PRJCODE		= $this->input->post('PRJCODE');			
			$url			= site_url('c_project/c_cashout_upah/get_all_COU/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update()
	{
		$this->load->model('m_project/m_cashout_upah/m_cashout_upah', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$COU_PeriodeCode		= $_GET['id'];
		$COU_PeriodeCode		= $this->url_encryption_helper->decode_url($COU_PeriodeCode);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 					= $appName;
			$data['task'] 					= 'edit';
			$data['h2_title'] 				= 'Update';
			$data['main_view'] 				= 'v_project/v_cashout_upah/cashout_upah_form';
			$data['form_action']			= site_url('c_project/C_cashout_upah/update_process');
			$data["MenuCode"] 				= 'MN332';
			
			$data['countPRJ']		= $this->m_cashout_upah->count_all_num_rowsProject();
			
			$data['vwPRJ'] 			= $this->m_cashout_upah->viewProject()->result();
			
			
			$getpurreq 								= $this->m_cashout_upah->get_COU_by_number($COU_PeriodeCode)->row();
			$data['default']['COU_PeriodeCode'] 	= $getpurreq->COU_PeriodeCode;
			$data['default']['COU_Periode']			= $getpurreq->COU_Periode;
			$data['default']['PRJCODE']				= $getpurreq->PRJCODE;
			$PRJCODE								= $getpurreq->PRJCODE;
			$data['default']['COU_ExpanceName']		= $getpurreq->COU_ExpanceName;
			$data['default']['COU_ValuePlan']		= $getpurreq->COU_ValuePlan;
			$data['default']['COU_RealizationValue']= $getpurreq->COU_RealizationValue;
			$data['default']['COU_Created'] 		= $getpurreq->COU_Created;
			$data['default']['COU_EMP'] 			= $getpurreq->COU_EMP;
			$data['default']['COU_Updated']			= $getpurreq->COU_Updated;
			$data['default']['COU_UpEmp']			= $getpurreq->COU_UpEmp;
			
			$data['backURL'] 				= site_url('c_project/C_cashout_upah/get_all_COU/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$cancelURL					= site_url('c_project/C_cashout_upah/get_all_COU/?id='.$this->url_encryption_helper->encode_url($COU_PeriodeCode));
			$data['link'] 				= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-primary" />', array('style' => 'text-decoration: none;')));
			
			
			$this->load->view('v_project/v_cashout_upah/cashout_upah_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_project/m_cashout_upah/m_cashout_upah', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$COU_Periode 		= date('Y-m-d',strtotime($this->input->post('COU_Periode')));
			
			$COU_Updated		= date('Y-m-d H:i:s');
			
			$projcou = array('COU_PeriodeCode' 		=> $this->input->post('COU_PeriodeCode'),
							'COU_Periode' 			=> $COU_Periode,
							'PRJCODE' 				=> $this->input->post('PRJCODE'),
							'COU_ExpanceName' 		=> $this->input->post('ExpanceName'),
							'COU_ValuePlan' 		=> $this->input->post('ValuePlan'),
							'COU_RealizationValue'	=> $this->input->post('RealizationValue'),
							'COU_Updated' 			=> $COU_Updated,
							'COU_UpEmp' 			=> $DefEmp_ID);
			
			$COU_PeriodeCode	= $this->input->post('COU_PeriodeCode');
										
			$this->m_cashout_upah->update($COU_PeriodeCode, $projcou);
			/*
			$this->m_cashout_upah->deleteDetail($SPPNUM, $SPPCODE);
			*/
			$PRJCODE	= $this->input->post('PRJCODE');	
			$url			= site_url('c_project/C_cashout_upah/get_all_COU/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
 	public function inbox()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/C_cashout_upah/inbox1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function inbox1($offset=0)
	{
		$this->load->model('m_purchase/m_cashout_upah/m_cashout_upah', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Cash Out Upah';
			$data['secAddURL'] 			= site_url('c_project/C_cashout_upah/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_project/C_cashout_upah/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 					= $this->m_cashout_upah->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
			$data['MenuCode'] 			= 'MN332';
			
			// Start of Pagination
			$config 					= array();
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"]		= 4;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_project/project_owner/get_last_ten_owner');				
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
	 
			$data['viewOwner'] = $this->m_project_owner->get_last_ten_owner($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();
			
			$this->load->view('v_project/v_cashout_upah/project_owner', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_all_PRInb($offset=0)
	{
		$this->load->model('m_purchase/m_cashout_upah/m_cashout_upah', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			// Secure URL
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 		= $PRJCODE; // Session Project Per User
			
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Material Request';
			$data['main_view'] 			= 'v_purchase/v_purchase_req/material_request_inbox';
			//$data['srch_url']			= site_url('c_project/C_cashout_upah/get_all_PRInb_src/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['link'] 				= array('link_back' => anchor('c_project/C_cashout_upah/inbox','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-primary" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE'] 			= $PRJCODE;
			$data['moffset'] 			= $offset;	
			
			$num_rows 					= $this->m_cashout_upah->count_all_num_rowsPR_Inb($PRJCODE);
			$data["recordcount"] 		= $num_rows;
			$data["recordcount1"]		= $num_rows;
			$config						= array();
			
			$config['base_url'] 		= site_url('c_project/C_cashout_upah_sd/get_all_PR');
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"] 		= 3;				
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open'] 	= '<li>';
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
	 
			$this->pagination->initialize($config);
	
			$data['vwprojmatreq'] 	= $this->m_cashout_upah->get_all_PRInb($PRJCODE, $config["per_page"], $offset)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			// // Start : Searching Function --- Untuk delete session
			/*$dataSessSrc = array(
					'selSearchproj_Code' => $DefProj_Code,
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$dataSessSrc   = $this->session->userdata('dtSessSrc1');*/
			// End : Searching Function	
			
			
			$this->load->view('v_purchase/v_purchase_req/material_request_inbox', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_inbox()
	{
		$this->load->model('m_purchase/m_cashout_upah/m_cashout_upah', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$SPPNUM		= $_GET['id'];
		$SPPNUM		= $this->url_encryption_helper->decode_url($SPPNUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 					= $appName;
			$data['task'] 					= 'edit';
			$data['h2_title'] 				= 'Update Request';
			$data['main_view'] 				= 'v_purchase/v_purchase_req/material_request_form_inbox';
			$data['form_action']			= site_url('c_project/C_cashout_upah/update_process_inbox');
			
			$data['countPRJ']		= $this->m_cashout_upah->count_all_num_rowsProject();
			$data['vwPRJ'] 			= $this->m_cashout_upah->viewProject()->result();
			
			$getpurreq 						= $this->m_cashout_upah->get_MR_by_number($SPPNUM)->row();
			$data['default']['SPPNUM'] 		= $getpurreq->SPPNUM;
			$data['default']['PRJCODE']		= $getpurreq->PRJCODE;
			$data['default']['SPPCODE']		= $getpurreq->SPPCODE;
			$PRJCODE 						= $getpurreq->PRJCODE;
			$data['PRJCODE']				= $getpurreq->PRJCODE;
			$data['default']['TRXDATE'] 	= $getpurreq->TRXDATE;
			$data['default']['PRJCODE'] 	= $getpurreq->PRJCODE;
			$data['default']['TRXOPEN'] 	= $getpurreq->TRXOPEN;
			$data['default']['TRXUSER'] 	= $getpurreq->TRXUSER;
			$data['default']['APPROVE'] 	= $getpurreq->APPROVE;
			$data['default']['APPRUSR'] 	= $getpurreq->APPRUSR;
			$data['default']['JOBCODE'] 	= $getpurreq->JOBCODE;
			$data['default']['SPPNOTE'] 	= $getpurreq->SPPNOTE;
			$data['default']['SPPSTAT'] 	= $getpurreq->SPPSTAT;
			$data['default']['REVMEMO'] 	= $getpurreq->REVMEMO;
			$data['default']['PRJNAME'] 	= $getpurreq->PRJNAME;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			
			
			$cancelURL						= site_url('c_project/C_cashout_upah/get_all_PRInb/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 					= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-primary" />', array('style' => 'text-decoration: none;')));			
			
			$this->load->view('v_purchase/v_purchase_req/material_request_form_inbox', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process_inbox()
	{
		$this->load->model('m_purchase/m_cashout_upah/m_cashout_upah', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			// Setting Approve Date
			date_default_timezone_set("Asia/Jakarta");
			
			$APPDATE 			= date('Y-m-d H:i:s');
			$PRJCODE 			= $this->input->post('PRJCODE');
			$SPPNUM 			= $this->input->post('SPPNUM');
			$SPPCODE 			= $this->input->post('SPPCODE');
			$REVMEMO			= $this->input->post('REVMEMO');
			$APPROVE			= $this->input->post('APPROVE');
			$APPRUSR			= $this->session->userdata['Emp_ID'];
			
			// Dapatkan status MR sebelum ada perubahan status
			$sqlSPPN 		= "SELECT APPROVE FROM tbl_pr_header WHERE SPPNUM = '$SPPNUM' AND PRJCODE = '$PRJCODE'";
			$resSPPN 		= $this->db->query($sqlSPPN)->result();
			foreach($resSPPN as $rowSPPN) :
				$APPROVEBEF = $rowSPPN->APPROVE;		
			endforeach;
			
			$projMatReqH = array('APPROVE'		=> $APPROVE,
							'SPPSTAT'			=> $APPROVE,
							'REVMEMO'			=> $REVMEMO,
							'APPDATE'			=> $APPDATE,
							'APPRUSR'			=> $APPRUSR);
							
			$this->m_cashout_upah->update_inbox($SPPNUM, $projMatReqH);
			if($APPROVE == 3)
			{
				foreach($_POST['data'] as $d)
				{
					$SPPVOLM 	= $d['SPPVOLM'];
					$CSTCODE 	= $d['CSTCODE'];
					$parameters = array(
							'PRJCODE' 	=> $PRJCODE,
							'SPPNUM' 	=> $SPPNUM,
							'SPPCODE' 	=> $SPPCODE,
							'SPPVOLM' 	=> $SPPVOLM,
							'CSTCODE' 	=> $CSTCODE							
						);
					$this->m_cashout_upah->updatePP($SPPNUM, $parameters);
				}
			}
			
			// UPDATE TO TRANS-COUNT
				if($APPROVEBEF != $APPROVE)
				{
					// Kurangi 1 hitungan untuk MR dengan status TR_CONFIRM
						$sqlTRS 		= "SELECT TR_CONFIRM FROM tbl_trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$resTRS			= $this->db->query($sqlTRS)->result();
						foreach($resTRS as $rowTRS) :
							$TR_CONFIRMBEF = $rowTRS->TR_CONFIRM;		
						endforeach;							
						$TR_CONFIRMNOW		= $TR_CONFIRMBEF - 1;				
						$sqlUpd1		= "UPDATE tbl_trans_count SET TR_CONFIRM = $TR_CONFIRMNOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$this->db->query($sqlUpd1);
						
					// JIKA APPROVE
					if($APPROVE == 3) // Kondisinya berarti Status Approve = 1, Status SPP = 2 (confirm)
					{
						$sqlTRS 		= "SELECT TR_APPROVED FROM tbl_trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$resTRS			= $this->db->query($sqlTRS)->result();
						foreach($resTRS as $rowTRS) :
							$TR_APPROVEDBEF = $rowTRS->TR_APPROVED;		
						endforeach;							
						$TR_APPROVEDNOW		= $TR_APPROVEDBEF + 1;				
						$sqlUpd1		= "UPDATE tbl_trans_count SET TR_APPROVED = $TR_APPROVEDNOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$this->db->query($sqlUpd1);
					}
					// JIKA REVISE
					elseif($APPROVE == 4)
					{
						$sqlTRS 		= "SELECT TR_REVISE FROM tbl_trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$resTRS			= $this->db->query($sqlTRS)->result();
						foreach($resTRS as $rowTRS) :
							$TR_REVISEBEF = $rowTRS->TR_REVISE;		
						endforeach;							
						$TR_REVISENOW		= $TR_REVISEBEF + 1;				
						$sqlUpd1		= "UPDATE tbl_trans_count SET TR_REVISE = $TR_REVISENOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$this->db->query($sqlUpd1);
					}
					// JIKA REVISE
					elseif($APPROVE == 5)
					{
						$sqlTRS 		= "SELECT TR_REJECT FROM tbl_trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$resTRS			= $this->db->query($sqlTRS)->result();
						foreach($resTRS as $rowTRS) :
							$TR_REJECTBEF = $rowTRS->TR_REJECT;		
						endforeach;							
						$TR_REJECTNOW		= $TR_REJECTBEF + 1;				
						$sqlUpd1		= "UPDATE tbl_trans_count SET TR_REJECT = $TR_REJECTNOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$this->db->query($sqlUpd1);
					}
				}
		
			$url			= site_url('c_project/C_cashout_upah/get_all_PRInb/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallpr()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Select Purchase Requisition';
			$data['form_action']	= site_url('c_project/C_cashout_upah_sd/update_process');
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'PR';
			
			$data['recordcountAllPR'] = $this->m_cashout_upah->count_all_num_rowsAllPR();
			$data['viewAllPR'] 	= $this->m_cashout_upah->viewAllPR()->result();
					
			$this->load->view('v_project/v_listproject/purchase_selectpr', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallpr2()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;
		$data['h2_title'] 		= 'Select Item';
		$data['form_action']	= site_url('c_project/C_cashout_upah_sd/update_process');
		$data['txtRefference'] 	= '';
		$data['resultCount']	= 0;
		$data['pageFrom']		= 'DIR'; //DIR = Direct (non PR)
		
		$data['recordcountAllItem'] = $this->m_cashout_upah->count_all_num_rowsAllItem();
		$data['viewAllItem'] 	= $this->m_cashout_upah->viewAllItem()->result();
				
		$this->load->view('v_project/v_listproject/purchase_selectitem', $data);
	}
	
	function delete($PR_Number)
	{
		$this->m_cashout_upah->delete($this->input->post('chkDetail'));
		$this->session->set_flashdata('message', 'Data successfull deleted');
		
		redirect('c_project/C_cashout_upah_sd/');
	}
	
	function getVendAddress($vendCode)
	{
		$data['myVendCode']		= "$vendCode";
		$sql = "SELECT Vend_Code, Vend_Name, Vend_Address FROM tvendor
					WHERE Vend_Code = '$vendCode'";
		$result1 = $this->db->query($sql)->result();
		foreach($result1 as $row) :
			$Vend_Name = $row->Vend_Address;
		endforeach;
		echo $Vend_Name;
	}
	
	function printdocument($MR_Number)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Document Print';
			$data['form_action']	= site_url('c_project/C_cashout_upah_sd/update_process_inbox');
			
			$data['recordcountVend'] 	= $this->m_cashout_upah->count_all_num_rowsVend();
			$data['viewvendor'] 	= $this->m_cashout_upah->viewvendor()->result();
			$data['recordcountDept'] 	= $this->m_cashout_upah->count_all_num_rowsDept();
			$data['viewDepartment'] 	= $this->m_cashout_upah->viewDepartment()->result();
			$data['recordcountEmpDept'] 	= $this->m_cashout_upah->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_cashout_upah->viewEmployeeDept()->result();
			$data['countPRJ']	= $this->m_cashout_upah->count_all_num_rowsProject();
			$data['vwPRJ'] 		= $this->m_cashout_upah->viewProject()->result();
			
			$getpurreq = $this->m_cashout_upah->get_MR_by_number($MR_Number)->row();
			
			$this->session->set_userdata('MR_Number', $getpurreq->MR_Number);
			
			$data['link'] 			= array('link_back' => anchor('c_project/C_cashout_upah_sd/get_all_PRInb/'.$getpurreq->proj_Code,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-primary" />', array('style' => 'text-decoration: none;')));
			
			$data['default']['proj_ID'] = $getpurreq->proj_ID;
			$data['default']['proj_Code'] = $getpurreq->proj_Code;
			$data['default']['MR_Number'] = $getpurreq->MR_Number;
			$data['default']['MR_Date'] = $getpurreq->MR_Date;
			$data['default']['req_date'] = $getpurreq->req_date;
			$data['default']['latest_date'] = $getpurreq->latest_date;
			$data['default']['MR_Class'] = $getpurreq->MR_Class;
			$data['default']['MR_Type'] = $getpurreq->MR_Type;
			$data['default']['MR_DepID'] = $getpurreq->MR_DepID;
			$data['default']['MR_EmpID'] = $getpurreq->MR_EmpID;
			$data['default']['Vend_Code'] = $getpurreq->Vend_Code;
			$data['default']['MR_Notes'] = $getpurreq->MR_Notes;
			$data['default']['MR_Status'] = $getpurreq->MR_Status;
			$data['default']['Approval_Status'] = $getpurreq->Approval_Status;
			$data['default']['Patt_Year'] = $getpurreq->Patt_Year;
			$data['default']['Patt_Number'] = $getpurreq->Patt_Number;
			$data['default']['Memo_Revisi'] = $getpurreq->Memo_Revisi;
							
			$this->load->view('v_purchase/v_purchase_req/print_matreq', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	/*function get_last_ten_projList_src($offset=0)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 	= $this->session->userdata['sessionProject']['mysessionProject']; // Session Project Per User
			
			$data['secAddURL'] 		= site_url('c_project/C_cashout_upah_sd/add');
			$data['showIndex'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/C_cashout_upah_sd'),'index');
			$data['srch_url'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/C_cashout_upah_sd'),'get_last_ten_projList_src');
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Project Planning List';
			$data['main_view'] 		= 'v_purchase/v_purchase_req/project_planning_sd';
			$data['moffset'] 		= $offset;
			$data['perpage'] 		= 20;
			$data['theoffset']		= 0;
			$config 				= array();
			$config["per_page"] 	= 20;
			$config["uri_segment"] 	= 3;
			$config['cur_page']		= $offset;
			
			$data['selSearchType'] 	= $this->input->post('selSearchType');
			$data['txtSearch'] 		= $this->input->post('txtSearch');
			$selSearchType			= $this->input->post('selSearchType');
			$txtSearch 				= $this->input->post('txtSearch');
			
			if($selSearchType == 'ProjNumber')
			{
				$num_rows 				= $this->m_cashout_upah->count_all_num_rows_PNo($txtSearch, $DefEmp_ID);
				$data["recordcount"] 	= $num_rows;
				$data['vewproject'] 	= $this->m_cashout_upah->get_all_prj_PNo($config["per_page"], $offset, $txtSearch, $DefEmp_ID)->result();
			}
			else
			{
				$num_rows 				= $this->m_cashout_upah->count_all_num_rows_PNm($txtSearch, $DefEmp_ID);
				$data["recordcount"] 	= $num_rows;
				$data['vewproject'] 	= $this->m_cashout_upah->get_all_prj_PNm($config["per_page"], $offset, $txtSearch, $DefEmp_ID)->result();
			}
			
			$config["total_rows"] 		= $num_rows;
			$config['base_url'] 		= site_url('c_project/C_cashout_upah_sd/get_last_ten_projList');			
			
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open'] 	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link']		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close']	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open']	 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close']	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link'] 		= '&gt;&gt;';
	 
			$this->pagination->initialize($config);
	 
			$data["pagination"] 		= $this->pagination->create_links();
			
			// // Start : Searching Function --- Untuk delete session
			$dataSessSrc = array(
					'selSearchproj_Code' => $DefProj_Code,
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			// End : Searching Function	
			
			$this->load->view('template', $data);		
		}
		else
		{
			redirect('Auth');
		}
	}
*/

	/*function get_all_PR_src($offset=0) // HOLD
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			// Secure URL
			$data['secAddURL'] 			= site_url('c_project/C_cashout_upah_sd/add');
			$data['showIdxMReq']		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/C_cashout_upah_sd'),'get_all_PR');		
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Material Request';
			$data['main_view'] 			= 'v_purchase/v_purchase_req/material_request_sd';			
			$data['link'] 				= array('link_back' => anchor('c_project/C_cashout_upah_sd/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-primary" value="Back" />', array('style' => 'text-decoration: none;')));
			
			$data['selSearchType'] 		= $this->input->post('selSearchType');
			$data['txtSearch'] 			= $this->input->post('txtSearch');	
			$selSearchType				= $this->input->post('selSearchType');
			$txtSearch 					= $this->input->post('txtSearch');
				
			$data['PRJCODE'] 			= $this->input->post('PRJCODE');
			$PRJCODE 					= $this->input->post('PRJCODE');	
			$data['PRJCODE'] 			= $PRJCODE;
			$data['PRJCODE1'] 			= $PRJCODE;
			$data['moffset'] 			= $offset;	
			$data['PRJCODE'] 			= $PRJCODE;	
			
			if($selSearchType == 'MRNumber')
			{
				$num_rows = $this->m_cashout_upah->count_all_num_rows_projMatReq_srcMR($txtSearch);
			}
			else
			{
				$num_rows = $this->m_cashout_upah->count_all_num_rows_projMatReq_srcPN($txtSearch);
			}
			
			//$num_rows = $this->m_cashout_upah->count_all_num_rows_projMatReq_src();
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_project/C_cashout_upah_sd/get_all_PR');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 15;
			$config["uri_segment"] = 3;
				
			$config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] = '</ul>';
			$config['prev_link'] = '&lt;';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&gt;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="current"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			
			$config['first_link'] = '&lt;&lt;';
			$config['last_link'] = '&gt;&gt;';
	 
			$this->pagination->initialize($config);
			
			if($selSearchType == 'MRNumber')
			{
				$data['vwprojmatreq'] = $this->m_cashout_upah->get_all_PR_MRNo($config["per_page"], $offset, $txtSearch)->result();
			}
			else
			{
				$data['vwprojmatreq'] = $this->m_cashout_upah->get_all_PR_PNm($config["per_page"], $offset, $txtSearch)->result();
			}
			
			$data["pagination"] = $this->pagination->create_links();	
			
			// // Start : Searching Function --- Untuk delete session
			$dataSessSrc = array(
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$dataSessSrc   = $this->session->userdata('dtSessSrc1');
			// End : Searching Function	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
}