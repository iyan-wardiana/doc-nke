<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Februari 2017
 * File Name	= Project_recomendation.php
 * Location		= -
*/

class Project_recomendation extends CI_Controller  
{
 	// Start : Index tiap halaman
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/project_recomendation/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_project/m_project_recomendation/m_project_recomendation', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Recomendation List';
			$data['h3_title']			= 'project';
			$data['secAddURL'] 			= site_url('c_project/project_recomendation/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_project/project_recomendation/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 					= $this->m_project_recomendation->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
	 		$data["MenuCode"] 			= 'MN267';
			$data['viewrecproj']		= $this->m_project_recomendation->get_last_ten_projrecom()->result();
			
			$this->load->view('v_project/v_project_recomendation/project_recomendation', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	// End
	
	function add() // OK
	{	
		$this->load->model('m_project/m_project_recomendation/m_project_recomendation', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 				= $appName;
			
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add Recomendation';
			$data['h3_title']			= 'project';
			$data['main_view'] 			= 'v_project/v_project_recomendation/project_recomendation_form';
			$data['main_view2'] 		= 'v_project/v_listproject/getaddress_sd';
			$data['form_action']		= site_url('c_project/project_recomendation/add_process');
			$data['cancel_url']			= site_url('c_project/project_recomendation/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 				= array('link_back' => anchor('c_project/project_recomendation/','<input type="button" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$MenuCode 					= 'MN267';
			$data["MenuCode"] 			= 'MN267';
			
			$data['viewDocPattern'] 	= $this->m_project_recomendation->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_project/v_project_recomendation/project_recomendation_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
		
	function getTheCode($REC_NO) // OK
	{
		$this->load->model('m_project/m_project_recomendation/m_project_recomendation', '', TRUE);
		$recordcountProj 	= $this->m_project_recomendation->count_all_num_rowsProj($REC_NO);
		echo $recordcountProj;
	}
	
	function add_process() // OK
	{
		$this->load->model('m_project/m_project_recomendation/m_project_recomendation', '', TRUE);
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			$isShowx			=  $this->input->post('isShowx');
			$REC_USR_MRK_STAT	= $this->input->post('REC_USR_MRK_STAT');
			$Patt_Year			= date('Y');
			if($REC_USR_MRK_STAT == 2)
			{
				date_default_timezone_set("Asia/Jakarta");
				$REC_SIGN_USR_MRK	= date('Y-m-d H:i:s');
			}
			else
			{
				$REC_SIGN_USR_MRK	= "";
			}
			
			$projectrecom = array('REC_CODE' 		=> $this->input->post('REC_CODE'),
							'REC_NO'				=> $this->input->post('REC_NO'),
							'REC_NO1'				=> $this->input->post('REC_NO1'),
							'PRJ_JNS'				=> $this->input->post('PRJ_JNS'),
							'REC_PAGE_NO'			=> 1,
							'REC_PRJNAME'			=> $this->input->post('REC_PRJNAME'),
							'REC_VALUE'				=> $this->input->post('REC_VALUE'),
							'REC_OWNER'				=> $this->input->post('REC_OWNER'),
							'REC_CURRENCY'			=> $this->input->post('REC_CURRENCY'),
							'REC_CONSULT_ARS'		=> $this->input->post('REC_CONSULT_ARS'),
							'REC_CONSULT_QS'		=> $this->input->post('REC_CONSULT_QS'),
							'REC_LOCATION'			=> $this->input->post('REC_LOCATION'),
							'REC_DATE'				=> date('Y-m-d',strtotime($this->input->post('REC_DATE'))),
							'REC_TEND_DATE'			=> date('Y-m-d',strtotime($this->input->post('REC_TEND_DATE'))),
							'REC_FUNDSRC'			=> $this->input->post('REC_FUNDSRC'),
							
							'REC_FUNDSRC_APBN'		=> $this->input->post('REC_FUNDSRC_APBN'),
							'REC_FUNDSRC_APBD'		=> $this->input->post('REC_FUNDSRC_APBD'),
							'REC_FUNDSRC_PRIV'		=> $this->input->post('REC_FUNDSRC_PRIV'),
							'REC_FUNDSRC_OTH'		=> $this->input->post('REC_FUNDSRC_OTH'),
							
							'REC_PAY_SYS'			=> $this->input->post('REC_PAY_SYS'),
							'REC_DP'				=> $this->input->post('REC_DP'),
							'REC_TURNOVER'			=> $this->input->post('REC_TURNOVER'),
							'REC_EXP'				=> $this->input->post('REC_EXP'),
							'REC_BASCAPAB'			=> $this->input->post('REC_BASCAPAB'),
							'REC_FINCAPAB'			=> $this->input->post('REC_FINCAPAB'),
							//'REC_DATEXEC'			=> date('Y-m-d',strtotime($this->input->post('REC_DATEXEC'))),		
							'REC_DATEXEC'			=> $this->input->post('REC_DATEXEC'),							
							'REC_PQ_ESTIME'			=> $this->input->post('REC_PQ_ESTIME'),
							'REC_TEND_ESTIME'		=> $this->input->post('REC_TEND_ESTIME'),
							'REC_BIDDER'			=> $this->input->post('REC_BIDDER'),
							'REC_BIDDER_QTY'		=> $this->input->post('REC_BIDDER_QTY'),
							'REC_ESKAL_EST'			=> $this->input->post('REC_ESKAL_EST'),
							'REC_CONCLUTION'		=> $this->input->post('REC_CONCLUTION'),
							'REC_CONC_TARGET'		=> $this->input->post('REC_CONC_TARGET'),
							'REC_CONC_NOTES'		=> $this->input->post('REC_CONC_NOTES'),
							'REC_NOTES'				=> $this->input->post('REC_NOTES'),
							'REC_SIGN_USR_MRK'		=> $REC_SIGN_USR_MRK,
							'REC_USR_MRK_STAT'		=> $this->input->post('REC_USR_MRK_STAT'),
							'REC_USR_MRK'			=> $this->input->post('REC_USR_MRK'),
							'REC_MNG_MRK_STAT'		=> 1,
							//'REC_MNG_MRK'			=> $this->input->post('REC_MNG_MRK'),
							'REC_DIR_MRK_STAT'		=> 1,
							//'REC_DIR_MRK'			=> $this->input->post('REC_DIR_MRK'),
							'REC_MNG_EST_STAT'		=> 1,
							//'REC_MNG_EST'			=> $this->input->post('REC_MNG_EST'),
							//'REC_PRESDIR'			=> $this->input->post('REC_PRESDIR'),
							'REC_STAT'				=> 1,
							
							'REC_FUNDSRC_NOTE'		=> $this->input->post('REC_FUNDSRC_NOTE'),
							'REC_PAY_SYS_NOTE'		=> $this->input->post('REC_PAY_SYS_NOTE'),
							'REC_DP_NOTE'			=> $this->input->post('REC_DP_NOTE'),
							'REC_TURNOVER_NOTE'		=> $this->input->post('REC_TURNOVER_NOTE'),
							'REC_EXP_NOTE'			=> $this->input->post('REC_EXP_NOTE'),
							'REC_BASCAPAB_NOTE'		=> $this->input->post('REC_BASCAPAB_NOTE'),
							'REC_FINCAPAB_NOTE'		=> $this->input->post('REC_FINCAPAB_NOTE'),
							'REC_TIMEXEC_NOTE'		=> $this->input->post('REC_TIMEXEC_NOTE'),
							'REC_PQ_ESTIME_NOTE'	=> $this->input->post('REC_PQ_ESTIME_NOTE'),
							'REC_TEND_ESTIME_NOTE'	=> $this->input->post('REC_TEND_ESTIME_NOTE'),
							'REC_BIDDER_NOTE'		=> $this->input->post('REC_BIDDER_NOTE'),
							'REC_ESKAL_EST_NOTE'	=> $this->input->post('REC_ESKAL_EST_NOTE'),
							
							'DOK_NO'				=> 'IQ-210',
							'REVISI'				=> 'A (29/08/03)',
							'AMAND'					=> '!(25/01/07)',
							'ISAMANDEMEN'			=> $this->input->post('ISAMANDEMEN'),
							'REC_CREATER'			=> $DefEmp_ID,
							'Patt_Year'				=> $Patt_Year,
							'Patt_Number'			=> $this->input->post('Patt_Number'));
												
			$this->m_project_recomendation->add($projectrecom);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/project_recomendation/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{	
		$this->load->model('m_project/m_project_recomendation/m_project_recomendation', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$REC_CODE	= $_GET['id'];
			$REC_CODE	= $this->url_encryption_helper->decode_url($REC_CODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Recomendation';
			$data['h3_title']		= 'project';
			$data['main_view'] 		= 'v_project/v_project_recomendation/project_recomendation_form';
			$data['form_action']	= site_url('c_project/project_recomendation/update_process');
			$data['cancel_url']		= site_url('c_project/project_recomendation/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 			= array('link_back' => anchor('c_project/project_recomendation/','<input type="button" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 		= 'MN267';
			
			$getprojrecom = $this->m_project_recomendation->get_projrecom_bycode($REC_CODE)->row();
							
			$data['default']['REC_CODE'] 		= $getprojrecom->REC_CODE;
			$data['default']['REC_NO'] 			= $getprojrecom->REC_NO;
			$data['default']['REC_NO1'] 		= $getprojrecom->REC_NO1;
			$data['default']['PRJ_JNS'] 		= $getprojrecom->PRJ_JNS;
			$data['default']['REC_PAGE_NO'] 	= $getprojrecom->REC_PAGE_NO;
			$data['default']['REC_PRJNAME'] 	= $getprojrecom->REC_PRJNAME;
			$data['default']['REC_VALUE'] 		= $getprojrecom->REC_VALUE;
			$data['default']['REC_OWNER'] 		= $getprojrecom->REC_OWNER;
			$data['default']['REC_CURRENCY'] 	= $getprojrecom->REC_CURRENCY;
			$data['default']['REC_CONSULT_ARS'] = $getprojrecom->REC_CONSULT_ARS;
			$data['default']['REC_CONSULT_QS']	= $getprojrecom->REC_CONSULT_QS;
			$data['default']['REC_LOCATION'] 	= $getprojrecom->REC_LOCATION;			
			$data['default']['REC_DATE'] 		= $getprojrecom->REC_DATE;
			$data['default']['REC_TEND_DATE']	= $getprojrecom->REC_TEND_DATE;
			$data['default']['REC_FUNDSRC']		= $getprojrecom->REC_FUNDSRC;
							
			$data['default']['REC_FUNDSRC_APBN']= $getprojrecom->REC_FUNDSRC_APBN;
			$data['default']['REC_FUNDSRC_APBD']= $getprojrecom->REC_FUNDSRC_APBD;
			$data['default']['REC_FUNDSRC_PRIV']= $getprojrecom->REC_FUNDSRC_PRIV;
			$data['default']['REC_FUNDSRC_OTH']	= $getprojrecom->REC_FUNDSRC_OTH;
							
			$data['default']['REC_PAY_SYS']		= $getprojrecom->REC_PAY_SYS;
			$data['default']['REC_DP'] 			= $getprojrecom->REC_DP;			
			$data['default']['REC_TURNOVER'] 	= $getprojrecom->REC_TURNOVER;
			$data['default']['REC_EXP'] 		= $getprojrecom->REC_EXP;			
			$data['default']['REC_BASCAPAB']	= $getprojrecom->REC_BASCAPAB;
			$data['default']['REC_FINCAPAB']	= $getprojrecom->REC_FINCAPAB;
			$data['default']['REC_DATEXEC'] 	= $getprojrecom->REC_DATEXEC;
			$data['default']['REC_PQ_ESTIME'] 	= $getprojrecom->REC_PQ_ESTIME;
			$data['default']['REC_TEND_ESTIME'] = $getprojrecom->REC_TEND_ESTIME;
			$data['default']['REC_BIDDER'] 		= $getprojrecom->REC_BIDDER;
			$data['default']['REC_BIDDER_QTY'] 	= $getprojrecom->REC_BIDDER_QTY;
			$data['default']['REC_ESKAL_EST'] 	= $getprojrecom->REC_ESKAL_EST;
			$data['default']['REC_CONCLUTION']	 = $getprojrecom->REC_CONCLUTION;			
			$data['default']['REC_CONC_TARGET'] = $getprojrecom->REC_CONC_TARGET;
			$data['default']['REC_CONC_NOTES'] 	= $getprojrecom->REC_CONC_NOTES;
			$data['default']['REC_NOTES'] 		= $getprojrecom->REC_NOTES;
			$data['default']['REC_NOTES_EST'] 	= $getprojrecom->REC_NOTES_EST;
			$data['default']['REC_SIGN_USR_MRK']= $getprojrecom->REC_SIGN_USR_MRK;
			$data['default']['REC_USR_MRK_STAT']= $getprojrecom->REC_USR_MRK_STAT;
			$data['default']['REC_USR_MRK'] 	= $getprojrecom->REC_USR_MRK;
			$data['default']['REC_SIGN_MNG_MRK']= $getprojrecom->REC_SIGN_MNG_MRK;
			$data['default']['REC_MNG_MRK_STAT']= $getprojrecom->REC_MNG_MRK_STAT;
			$data['default']['REC_MNG_MRK'] 	= $getprojrecom->REC_MNG_MRK;
			$data['default']['REC_DIR_MRK_STAT']= $getprojrecom->REC_DIR_MRK_STAT;
			$data['default']['REC_DIR_MRK'] 	= $getprojrecom->REC_DIR_MRK;
			$data['default']['REC_SIGN_MNG_EST']= $getprojrecom->REC_SIGN_MNG_EST;
			$data['default']['REC_MNG_EST_STAT']= $getprojrecom->REC_MNG_EST_STAT;
			$data['default']['REC_MNG_EST'] 	= $getprojrecom->REC_MNG_EST;
			$data['default']['REC_PRESDIR'] 	= $getprojrecom->REC_PRESDIR;
			$data['default']['REC_STAT'] 		= $getprojrecom->REC_STAT;
							
			$data['default']['REC_FUNDSRC_NOTE']	= $getprojrecom->REC_FUNDSRC_NOTE;
			$data['default']['REC_PAY_SYS_NOTE']	= $getprojrecom->REC_PAY_SYS_NOTE;
			$data['default']['REC_DP_NOTE']		= $getprojrecom->REC_DP_NOTE;
			$data['default']['REC_TURNOVER_NOTE']	= $getprojrecom->REC_TURNOVER_NOTE;
			$data['default']['REC_EXP_NOTE']		= $getprojrecom->REC_EXP_NOTE;
			$data['default']['REC_BASCAPAB_NOTE']	= $getprojrecom->REC_BASCAPAB_NOTE;
			$data['default']['REC_FINCAPAB_NOTE']	= $getprojrecom->REC_FINCAPAB_NOTE;
			$data['default']['REC_TIMEXEC_NOTE']	= $getprojrecom->REC_TIMEXEC_NOTE;
			$data['default']['REC_PQ_ESTIME_NOTE']= $getprojrecom->REC_PQ_ESTIME_NOTE;
			$data['default']['REC_TEND_ESTIME_NOTE']	= $getprojrecom->REC_TEND_ESTIME_NOTE;
			$data['default']['REC_BIDDER_NOTE']	= $getprojrecom->REC_BIDDER_NOTE;
			$data['default']['REC_ESKAL_EST_NOTE']= $getprojrecom->REC_ESKAL_EST_NOTE;
							
			$data['default']['DOK_NO'] 			= $getprojrecom->DOK_NO;
			$data['default']['REVISI'] 			= $getprojrecom->REVISI;
			$data['default']['AMAND'] 			= $getprojrecom->AMAND;
			$data['default']['ISAMANDEMEN'] 	= $getprojrecom->ISAMANDEMEN;
			
			$data['default']['REC_CREATER'] 	= $getprojrecom->REC_CREATER;
			$data['default']['Patt_Number'] 	= $getprojrecom->Patt_Number;
			
			$this->load->view('v_project/v_project_recomendation/project_recomendation_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_project/m_project_recomendation/m_project_recomendation', '', TRUE);
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp		= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;	
		
		$REC_STAT		= $this->input->post('REC_STAT');
		
		if($REC_STAT == 3)
		{
			date_default_timezone_set("Asia/Jakarta"); 
			$myYear 		= date('y');
			$myMonth 		= date('m');
			$myDays 		= date('d');
			$myHours 		= date('H');
			$myMinutes 		= date('i');
			$mySeconds 		= date('s');
			$CreaterNm 		= getenv("username");
			$CreaterNm2		= str_replace('$', '', $CreaterNm);
			$localIP 		= getHostByName(php_uname('n'));
			$localIP2 		= str_replace('.', '', $localIP);
			$Creater_Code	= "TR$myMonth$myYear$myDays$myHours$myMinutes$mySeconds";
			$REC_SIGN_DATE	= date('Y-m-d H:i:s');
		}
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			$isShowx			=  $this->input->post('isShowx');
			$REC_USR_MRK_STAT	=  $this->input->post('REC_USR_MRK_STAT');
			$REC_MNG_EST_STAT	=  $this->input->post('REC_MNG_EST_STAT');			
							
			if($isShowx == 1)
			{
				$GEN_STAT		= "`REC_USR_MRK_STAT`";
				$GEN_STAT_VAL	= $this->input->post('REC_USR_MRK_STAT');
				$GEN_USER		= "`REC_USR_MRK`";
				$GEN_USER_VAL	= $this->input->post('REC_USR_MRK');
				$REC_SIGN_DATE	= date('Y-m-d H:i:s');
				$REC_SIGN		= "`REC_SIGN_USR_MRK`";
				if($GEN_STAT_VAL == 2)
					$REC_SIGN_DATE	= date('Y-m-d H:i:s');
				else
					$REC_SIGN_DATE	= '';				
			}
			elseif($isShowx == 2)
			{
				$GEN_STAT		= "`REC_MNG_EST_STAT`";
				$GEN_STAT_VAL	= $this->input->post('REC_MNG_EST_STAT');
				$GEN_USER		= "`REC_MNG_EST`";
				$GEN_USER_VAL	= $this->input->post('REC_MNG_EST');
				$REC_SIGN_DATE	= date('Y-m-d H:i:s');
				$REC_SIGN		= "`REC_SIGN_MNG_EST`";
				if($GEN_STAT_VAL == 2)
					$REC_SIGN_DATE	= date('Y-m-d H:i:s');
				else
					$REC_SIGN_DATE	= '';	
			}
			elseif($isShowx == 3)
			{
				$GEN_STAT		= "`REC_MNG_MRK_STAT`";
				$GEN_STAT_VAL	= $this->input->post('REC_MNG_MRK_STAT');
				$GEN_USER		= "`REC_MNG_MRK`";
				$GEN_USER_VAL	= $this->input->post('REC_MNG_MRK');
				$REC_SIGN_DATE	= date('Y-m-d H:i:s');
				$REC_SIGN		= "`REC_SIGN_MNG_MRK`";
				if($GEN_STAT_VAL == 3)
					$REC_SIGN_DATE	= date('Y-m-d H:i:s');
				else
					$REC_SIGN_DATE	= '';	
			}
				//return false;			
			
			$REC_CODE		= $this->input->post('REC_CODE');
			$ISAMANDEMEN	= $this->input->post('ISAMANDEMEN');
			
			$projectrecom = array('REC_CODE' 		=> $this->input->post('REC_CODE'),
							'REC_NO'				=> $this->input->post('REC_NO'),
							'REC_NO1'				=> $this->input->post('REC_NO1'),
							'PRJ_JNS'				=> $this->input->post('PRJ_JNS'),
							'REC_PAGE_NO'			=> 1,
							'REC_PRJNAME'			=> $this->input->post('REC_PRJNAME'),
							'REC_VALUE'				=> $this->input->post('REC_VALUE'),
							'REC_OWNER'				=> $this->input->post('REC_OWNER'),
							'REC_CURRENCY'			=> $this->input->post('REC_CURRENCY'),
							'REC_CONSULT_ARS'		=> $this->input->post('REC_CONSULT_ARS'),
							'REC_CONSULT_QS'		=> $this->input->post('REC_CONSULT_QS'),
							'REC_LOCATION'			=> $this->input->post('REC_LOCATION'),
							'REC_DATE'				=> date('Y-m-d',strtotime($this->input->post('REC_DATE'))),
							'REC_TEND_DATE'			=> date('Y-m-d',strtotime($this->input->post('REC_TEND_DATE'))),
							'REC_FUNDSRC'			=> $this->input->post('REC_FUNDSRC'),
							
							'REC_FUNDSRC_APBN'		=> $this->input->post('REC_FUNDSRC_APBN'),
							'REC_FUNDSRC_APBD'		=> $this->input->post('REC_FUNDSRC_APBD'),
							'REC_FUNDSRC_PRIV'		=> $this->input->post('REC_FUNDSRC_PRIV'),
							'REC_FUNDSRC_OTH'		=> $this->input->post('REC_FUNDSRC_OTH'),
							
							'REC_PAY_SYS'			=> $this->input->post('REC_PAY_SYS'),
							'REC_DP'				=> $this->input->post('REC_DP'),
							'REC_TURNOVER'			=> $this->input->post('REC_TURNOVER'),
							'REC_EXP'				=> $this->input->post('REC_EXP'),
							'REC_BASCAPAB'			=> $this->input->post('REC_BASCAPAB'),
							'REC_FINCAPAB'			=> $this->input->post('REC_FINCAPAB'),
							//'REC_DATEXEC'			=> date('Y-m-d',strtotime($this->input->post('REC_DATEXEC'))),		
							'REC_DATEXEC'			=> $this->input->post('REC_DATEXEC'),							
							'REC_PQ_ESTIME'			=> $this->input->post('REC_PQ_ESTIME'),
							'REC_TEND_ESTIME'		=> $this->input->post('REC_TEND_ESTIME'),
							'REC_BIDDER'			=> $this->input->post('REC_BIDDER'),
							'REC_BIDDER_QTY'		=> $this->input->post('REC_BIDDER_QTY'),
							'REC_ESKAL_EST'			=> $this->input->post('REC_ESKAL_EST'),
							'REC_CONCLUTION'		=> $this->input->post('REC_CONCLUTION'),
							'REC_CONC_TARGET'		=> $this->input->post('REC_CONC_TARGET'),
							'REC_CONC_NOTES'		=> $this->input->post('REC_CONC_NOTES'),
							'REC_NOTES'				=> $this->input->post('REC_NOTES'),
							'REC_NOTES_EST'			=> $this->input->post('REC_NOTES_EST'),
							$GEN_STAT				=> $GEN_STAT_VAL,
							$GEN_USER				=> $GEN_USER_VAL,							
							'REC_STAT'				=> $GEN_STAT_VAL,
							$REC_SIGN				=> $REC_SIGN_DATE,
							
							'REC_FUNDSRC_NOTE'		=> $this->input->post('REC_FUNDSRC_NOTE'),
							'REC_PAY_SYS_NOTE'		=> $this->input->post('REC_PAY_SYS_NOTE'),
							'REC_DP_NOTE'			=> $this->input->post('REC_DP_NOTE'),
							'REC_TURNOVER_NOTE'		=> $this->input->post('REC_TURNOVER_NOTE'),
							'REC_EXP_NOTE'			=> $this->input->post('REC_EXP_NOTE'),
							'REC_BASCAPAB_NOTE'		=> $this->input->post('REC_BASCAPAB_NOTE'),
							'REC_FINCAPAB_NOTE'		=> $this->input->post('REC_FINCAPAB_NOTE'),
							'REC_TIMEXEC_NOTE'		=> $this->input->post('REC_TIMEXEC_NOTE'),
							'REC_PQ_ESTIME_NOTE'	=> $this->input->post('REC_PQ_ESTIME_NOTE'),
							'REC_TEND_ESTIME_NOTE'	=> $this->input->post('REC_TEND_ESTIME_NOTE'),
							'REC_BIDDER_NOTE'		=> $this->input->post('REC_BIDDER_NOTE'),
							'REC_ESKAL_EST_NOTE'	=> $this->input->post('REC_ESKAL_EST_NOTE'),
							
							//'REC_PRESDIR'			=> $this->input->post('REC_PRESDIR'),
							//'REC_STAT'			=> $this->input->post('REC_STAT'),
							'DOK_NO'				=> 'IQ-210',
							'REVISI'				=> 'A (29/08/03)',
							'AMAND'					=> '!(25/01/07)',
							'ISAMANDEMEN'			=> $this->input->post('ISAMANDEMEN'));
							
			$this->m_project_recomendation->update($REC_CODE, $projectrecom);
			
			// SAVE HISTORY IF AMANDEMEN
			if($ISAMANDEMEN == 1)
			{
				$RECH_CODE			= $this->input->post('REC_CODE');
				$RECH_NO			= $this->input->post('REC_NO');
				$RECH_MNG_MRK		= $this->input->post('REC_MNG_MRK');
				$RECH_CONCLUTION	= $this->input->post('REC_CONCLUTION');
				$RECH_CONC_TARGET	= $this->input->post('REC_CONC_TARGET');
				$RECH_CONC_NOTES	= $this->input->post('REC_CONC_NOTES');
				$RECH_DATE			= date('Y-m-d H:i:s');
							
				$recomHist = array('RECH_CODE' 		=> $RECH_CODE,
								'RECH_NO'			=> $RECH_NO,
								'RECH_MNG_MRK'		=> $RECH_MNG_MRK,
								'RECH_CONCLUTION'	=> $RECH_CONCLUTION,
								'RECH_CONC_TARGET'	=> $RECH_CONC_TARGET,
								'RECH_CONC_NOTES'	=> $RECH_CONC_NOTES,
								'RECH_DATE'			=> $RECH_DATE);
								
				$this->m_project_recomendation->addRecHist($recomHist);
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$this->session->set_flashdata('message', 'Data succesful to update.');
			
			redirect('c_project/project_recomendation/');			
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function viewDocRecomend()
	{	
		$this->load->model('m_project/m_project_recomendation/m_project_recomendation', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$REC_CODE	= $_GET['id'];
			$REC_CODE	= $this->url_encryption_helper->decode_url($REC_CODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'View Recomendation';
			$data['h3_title'] 		= 'project';
			$data['main_view'] 		= 'v_project/v_project_recomendation/project_recomendation_form';
			$data['form_action']	= site_url('c_project/project_recomendation/update_process');
			$data['cancel_url']		= site_url('c_project/project_recomendation/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 			= array('link_back' => anchor('c_project/project_recomendation/','<input type="button" class="btn btn-primary" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$getprojrecom = $this->m_project_recomendation->get_projrecom_bycode($REC_CODE)->row();
							
			$data['default']['REC_CODE'] 		= $getprojrecom->REC_CODE;
			$data['default']['REC_NO'] 			= $getprojrecom->REC_NO;
			$data['default']['REC_NO1'] 		= $getprojrecom->REC_NO1;
			$data['default']['PRJ_JNS'] 		= $getprojrecom->PRJ_JNS;
			$data['default']['REC_PAGE_NO'] 	= $getprojrecom->REC_PAGE_NO;
			$data['default']['REC_PRJNAME'] 	= $getprojrecom->REC_PRJNAME;
			$data['default']['REC_VALUE'] 		= $getprojrecom->REC_VALUE;
			$data['default']['REC_OWNER'] 		= $getprojrecom->REC_OWNER;
			$data['default']['REC_CURRENCY'] 	= $getprojrecom->REC_CURRENCY;
			$data['default']['REC_CONSULT_ARS'] = $getprojrecom->REC_CONSULT_ARS;
			$data['default']['REC_CONSULT_QS']	= $getprojrecom->REC_CONSULT_QS;
			$data['default']['REC_LOCATION'] 	= $getprojrecom->REC_LOCATION;			
			$data['default']['REC_DATE'] 		= $getprojrecom->REC_DATE;
			$data['default']['REC_TEND_DATE']	= $getprojrecom->REC_TEND_DATE;
			$data['default']['REC_FUNDSRC']		= $getprojrecom->REC_FUNDSRC;
							
			$data['default']['REC_FUNDSRC_APBN']= $getprojrecom->REC_FUNDSRC_APBN;
			$data['default']['REC_FUNDSRC_APBD']= $getprojrecom->REC_FUNDSRC_APBD;
			$data['default']['REC_FUNDSRC_PRIV']= $getprojrecom->REC_FUNDSRC_PRIV;
			$data['default']['REC_FUNDSRC_OTH']	= $getprojrecom->REC_FUNDSRC_OTH;
			
			$data['default']['REC_PAY_SYS']		= $getprojrecom->REC_PAY_SYS;
			$data['default']['REC_DP'] 			= $getprojrecom->REC_DP;			
			$data['default']['REC_TURNOVER'] 	= $getprojrecom->REC_TURNOVER;
			$data['default']['REC_EXP'] 		= $getprojrecom->REC_EXP;			
			$data['default']['REC_BASCAPAB']	= $getprojrecom->REC_BASCAPAB;
			$data['default']['REC_FINCAPAB']	= $getprojrecom->REC_FINCAPAB;
			$data['default']['REC_DATEXEC'] 	= $getprojrecom->REC_DATEXEC;
			$data['default']['REC_PQ_ESTIME'] 	= $getprojrecom->REC_PQ_ESTIME;
			$data['default']['REC_TEND_ESTIME'] = $getprojrecom->REC_TEND_ESTIME;
			$data['default']['REC_BIDDER'] 		= $getprojrecom->REC_BIDDER;
			$data['default']['REC_BIDDER_QTY'] 	= $getprojrecom->REC_BIDDER_QTY;
			$data['default']['REC_ESKAL_EST'] 	= $getprojrecom->REC_ESKAL_EST;
			$data['default']['REC_CONCLUTION']	 = $getprojrecom->REC_CONCLUTION;			
			$data['default']['REC_CONC_TARGET'] = $getprojrecom->REC_CONC_TARGET;
			$data['default']['REC_CONC_NOTES'] 	= $getprojrecom->REC_CONC_NOTES;
			$data['default']['REC_NOTES'] 		= $getprojrecom->REC_NOTES;
			$data['default']['REC_NOTES_EST'] 	= $getprojrecom->REC_NOTES_EST;
			$data['default']['REC_SIGN_USR_MRK']= $getprojrecom->REC_SIGN_USR_MRK;
			$data['default']['REC_USR_MRK_STAT']= $getprojrecom->REC_USR_MRK_STAT;
			$data['default']['REC_USR_MRK'] 	= $getprojrecom->REC_USR_MRK;
			$data['default']['REC_SIGN_MNG_MRK']= $getprojrecom->REC_SIGN_MNG_MRK;
			$data['default']['REC_MNG_MRK_STAT']= $getprojrecom->REC_MNG_MRK_STAT;
			$data['default']['REC_MNG_MRK'] 	= $getprojrecom->REC_MNG_MRK;
			$data['default']['REC_DIR_MRK_STAT']= $getprojrecom->REC_DIR_MRK_STAT;
			$data['default']['REC_DIR_MRK'] 	= $getprojrecom->REC_DIR_MRK;
			$data['default']['REC_SIGN_MNG_EST']= $getprojrecom->REC_SIGN_MNG_EST;
			$data['default']['REC_MNG_EST_STAT']= $getprojrecom->REC_MNG_EST_STAT;
			$data['default']['REC_MNG_EST'] 	= $getprojrecom->REC_MNG_EST;
			$data['default']['REC_PRESDIR'] 	= $getprojrecom->REC_PRESDIR;
			$data['default']['REC_STAT'] 		= $getprojrecom->REC_STAT;
							
			$data['default']['REC_FUNDSRC_NOTE']	= $getprojrecom->REC_FUNDSRC_NOTE;
			$data['default']['REC_PAY_SYS_NOTE']	= $getprojrecom->REC_PAY_SYS_NOTE;
			$data['default']['REC_DP_NOTE']			= $getprojrecom->REC_DP_NOTE;
			$data['default']['REC_TURNOVER_NOTE']	= $getprojrecom->REC_TURNOVER_NOTE;
			$data['default']['REC_EXP_NOTE']		= $getprojrecom->REC_EXP_NOTE;
			$data['default']['REC_BASCAPAB_NOTE']	= $getprojrecom->REC_BASCAPAB_NOTE;
			$data['default']['REC_FINCAPAB_NOTE']	= $getprojrecom->REC_FINCAPAB_NOTE;
			$data['default']['REC_TIMEXEC_NOTE']	= $getprojrecom->REC_TIMEXEC_NOTE;
			$data['default']['REC_PQ_ESTIME_NOTE']	= $getprojrecom->REC_PQ_ESTIME_NOTE;
			$data['default']['REC_TEND_ESTIME_NOTE']	= $getprojrecom->REC_TEND_ESTIME_NOTE;
			$data['default']['REC_BIDDER_NOTE']		= $getprojrecom->REC_BIDDER_NOTE;
			$data['default']['REC_ESKAL_EST_NOTE']	= $getprojrecom->REC_ESKAL_EST_NOTE;
			
			$data['default']['DOK_NO'] 			= $getprojrecom->DOK_NO;
			$data['default']['REVISI'] 			= $getprojrecom->REVISI;
			$data['default']['AMAND'] 			= $getprojrecom->AMAND;
			$data['default']['ISAMANDEMEN'] 	= $getprojrecom->ISAMANDEMEN;
			$data['default']['REC_CREATER'] 	= $getprojrecom->REC_CREATER;
			$data['default']['Patt_Number'] 	= $getprojrecom->Patt_Number;
			
			$this->load->view('v_project/v_project_recomendation/project_recomendation_view', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function printDocRecomend()
	{	
		$this->load->model('m_project/m_project_recomendation/m_project_recomendation', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$REC_CODE	= $_GET['id'];
			$REC_CODE	= $this->url_encryption_helper->decode_url($REC_CODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Project Recomendation';
			$data['main_view'] 		= 'v_project/v_project_recomendation/project_recomendation_form';
			$data['form_action']	= site_url('c_project/project_recomendation/update_process');
			$data['cancel_url']		= site_url('c_project/project_recomendation/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 			= array('link_back' => anchor('c_project/project_recomendation/','<input type="button" class="btn btn-primary" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$getprojrecom = $this->m_project_recomendation->get_projrecom_bycode($REC_CODE)->row();
							
			$data['default']['REC_CODE'] 		= $getprojrecom->REC_CODE;
			$data['default']['REC_NO'] 			= $getprojrecom->REC_NO;
			$data['default']['REC_NO1'] 		= $getprojrecom->REC_NO1;
			$data['default']['PRJ_JNS'] 		= $getprojrecom->PRJ_JNS;
			$data['default']['REC_PAGE_NO'] 	= $getprojrecom->REC_PAGE_NO;
			$data['default']['REC_PRJNAME'] 	= $getprojrecom->REC_PRJNAME;
			$data['default']['REC_VALUE'] 		= $getprojrecom->REC_VALUE;
			$data['default']['REC_OWNER'] 		= $getprojrecom->REC_OWNER;
			$data['default']['REC_CURRENCY'] 	= $getprojrecom->REC_CURRENCY;
			$data['default']['REC_CONSULT_ARS'] = $getprojrecom->REC_CONSULT_ARS;
			$data['default']['REC_CONSULT_QS']	= $getprojrecom->REC_CONSULT_QS;
			$data['default']['REC_LOCATION'] 	= $getprojrecom->REC_LOCATION;			
			$data['default']['REC_DATE'] 		= $getprojrecom->REC_DATE;
			$data['default']['REC_TEND_DATE']	= $getprojrecom->REC_TEND_DATE;
			$data['default']['REC_FUNDSRC']		= $getprojrecom->REC_FUNDSRC;
							
			$data['default']['REC_FUNDSRC_APBN']= $getprojrecom->REC_FUNDSRC_APBN;
			$data['default']['REC_FUNDSRC_APBD']= $getprojrecom->REC_FUNDSRC_APBD;
			$data['default']['REC_FUNDSRC_PRIV']= $getprojrecom->REC_FUNDSRC_PRIV;
			$data['default']['REC_FUNDSRC_OTH']	= $getprojrecom->REC_FUNDSRC_OTH;
			
			$data['default']['REC_PAY_SYS']		= $getprojrecom->REC_PAY_SYS;
			$data['default']['REC_DP'] 			= $getprojrecom->REC_DP;			
			$data['default']['REC_TURNOVER'] 	= $getprojrecom->REC_TURNOVER;
			$data['default']['REC_EXP'] 		= $getprojrecom->REC_EXP;			
			$data['default']['REC_BASCAPAB']	= $getprojrecom->REC_BASCAPAB;
			$data['default']['REC_FINCAPAB']	= $getprojrecom->REC_FINCAPAB;
			$data['default']['REC_DATEXEC'] 	= $getprojrecom->REC_DATEXEC;
			$data['default']['REC_PQ_ESTIME'] 	= $getprojrecom->REC_PQ_ESTIME;
			$data['default']['REC_TEND_ESTIME'] = $getprojrecom->REC_TEND_ESTIME;
			$data['default']['REC_BIDDER'] 		= $getprojrecom->REC_BIDDER;
			$data['default']['REC_BIDDER_QTY'] 	= $getprojrecom->REC_BIDDER_QTY;
			$data['default']['REC_ESKAL_EST'] 	= $getprojrecom->REC_ESKAL_EST;
			$data['default']['REC_CONCLUTION']	 = $getprojrecom->REC_CONCLUTION;			
			$data['default']['REC_CONC_TARGET'] = $getprojrecom->REC_CONC_TARGET;
			$data['default']['REC_CONC_NOTES'] 	= $getprojrecom->REC_CONC_NOTES;
			$data['default']['REC_NOTES'] 		= $getprojrecom->REC_NOTES;
			$data['default']['REC_NOTES_EST'] 	= $getprojrecom->REC_NOTES_EST;
			$data['default']['REC_SIGN_USR_MRK']= $getprojrecom->REC_SIGN_USR_MRK;
			$data['default']['REC_USR_MRK_STAT']= $getprojrecom->REC_USR_MRK_STAT;
			$data['default']['REC_USR_MRK'] 	= $getprojrecom->REC_USR_MRK;
			$data['default']['REC_SIGN_MNG_MRK']= $getprojrecom->REC_SIGN_MNG_MRK;
			$data['default']['REC_MNG_MRK_STAT']= $getprojrecom->REC_MNG_MRK_STAT;
			$data['default']['REC_MNG_MRK'] 	= $getprojrecom->REC_MNG_MRK;
			$data['default']['REC_DIR_MRK_STAT']= $getprojrecom->REC_DIR_MRK_STAT;
			$data['default']['REC_DIR_MRK'] 	= $getprojrecom->REC_DIR_MRK;
			$data['default']['REC_SIGN_MNG_EST']= $getprojrecom->REC_SIGN_MNG_EST;
			$data['default']['REC_MNG_EST_STAT']= $getprojrecom->REC_MNG_EST_STAT;
			$data['default']['REC_MNG_EST'] 	= $getprojrecom->REC_MNG_EST;
			$data['default']['REC_PRESDIR'] 	= $getprojrecom->REC_PRESDIR;
			$data['default']['REC_STAT'] 		= $getprojrecom->REC_STAT;
							
			$data['default']['REC_FUNDSRC_NOTE']	= $getprojrecom->REC_FUNDSRC_NOTE;
			$data['default']['REC_PAY_SYS_NOTE']	= $getprojrecom->REC_PAY_SYS_NOTE;
			$data['default']['REC_DP_NOTE']			= $getprojrecom->REC_DP_NOTE;
			$data['default']['REC_TURNOVER_NOTE']	= $getprojrecom->REC_TURNOVER_NOTE;
			$data['default']['REC_EXP_NOTE']		= $getprojrecom->REC_EXP_NOTE;
			$data['default']['REC_BASCAPAB_NOTE']	= $getprojrecom->REC_BASCAPAB_NOTE;
			$data['default']['REC_FINCAPAB_NOTE']	= $getprojrecom->REC_FINCAPAB_NOTE;
			$data['default']['REC_TIMEXEC_NOTE']	= $getprojrecom->REC_TIMEXEC_NOTE;
			$data['default']['REC_PQ_ESTIME_NOTE']	= $getprojrecom->REC_PQ_ESTIME_NOTE;
			$data['default']['REC_TEND_ESTIME_NOTE']	= $getprojrecom->REC_TEND_ESTIME_NOTE;
			$data['default']['REC_BIDDER_NOTE']		= $getprojrecom->REC_BIDDER_NOTE;
			$data['default']['REC_ESKAL_EST_NOTE']	= $getprojrecom->REC_ESKAL_EST_NOTE;
			
			$data['default']['DOK_NO'] 			= $getprojrecom->DOK_NO;
			$data['default']['REVISI'] 			= $getprojrecom->REVISI;
			$data['default']['AMAND'] 			= $getprojrecom->AMAND;
			$data['default']['ISAMANDEMEN'] 	= $getprojrecom->ISAMANDEMEN;
			$data['default']['REC_CREATER'] 	= $getprojrecom->REC_CREATER;
			$data['default']['Patt_Number'] 	= $getprojrecom->Patt_Number;
			
			$this->load->view('v_project/v_project_recomendation/project_recomendation_print', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function printDocRecomendEXCEL()
	{	
		$this->load->model('m_project/m_project_recomendation/m_project_recomendation', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$this->load->helper('pdf_helper');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$REC_CODE	= $_GET['id'];
			$REC_CODE	= $this->url_encryption_helper->decode_url($REC_CODE);
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Edit Project Recomendation';
			$data['cancel_url']		= site_url('c_project/project_recomendation/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 			= array('link_back' => anchor('c_project/project_recomendation/','<input type="button" class="btn btn-primary" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$getprojrecom = $this->m_project_recomendation->get_projrecom_bycode($REC_CODE)->row();
							
			$data['default']['REC_CODE'] 		= $getprojrecom->REC_CODE;
			$data['default']['REC_NO'] 			= $getprojrecom->REC_NO;
			$data['default']['REC_NO1'] 		= $getprojrecom->REC_NO1;
			$data['default']['PRJ_JNS'] 		= $getprojrecom->PRJ_JNS;
			$data['default']['REC_PAGE_NO'] 	= $getprojrecom->REC_PAGE_NO;
			$data['default']['REC_PRJNAME'] 	= $getprojrecom->REC_PRJNAME;
			$data['default']['REC_VALUE'] 		= $getprojrecom->REC_VALUE;
			$data['default']['REC_OWNER'] 		= $getprojrecom->REC_OWNER;
			$data['default']['REC_CURRENCY'] 	= $getprojrecom->REC_CURRENCY;
			$data['default']['REC_CONSULT_ARS'] = $getprojrecom->REC_CONSULT_ARS;
			$data['default']['REC_CONSULT_QS']	= $getprojrecom->REC_CONSULT_QS;
			$data['default']['REC_LOCATION'] 	= $getprojrecom->REC_LOCATION;			
			$data['default']['REC_DATE'] 		= $getprojrecom->REC_DATE;
			$data['default']['REC_TEND_DATE']	= $getprojrecom->REC_TEND_DATE;
			$data['default']['REC_FUNDSRC']		= $getprojrecom->REC_FUNDSRC;
							
			$data['default']['REC_FUNDSRC_APBN']= $getprojrecom->REC_FUNDSRC_APBN;
			$data['default']['REC_FUNDSRC_APBD']= $getprojrecom->REC_FUNDSRC_APBD;
			$data['default']['REC_FUNDSRC_PRIV']= $getprojrecom->REC_FUNDSRC_PRIV;
			$data['default']['REC_FUNDSRC_OTH']	= $getprojrecom->REC_FUNDSRC_OTH;
			
			$data['default']['REC_PAY_SYS']		= $getprojrecom->REC_PAY_SYS;
			$data['default']['REC_DP'] 			= $getprojrecom->REC_DP;			
			$data['default']['REC_TURNOVER'] 	= $getprojrecom->REC_TURNOVER;
			$data['default']['REC_EXP'] 		= $getprojrecom->REC_EXP;			
			$data['default']['REC_BASCAPAB']	= $getprojrecom->REC_BASCAPAB;
			$data['default']['REC_FINCAPAB']	= $getprojrecom->REC_FINCAPAB;
			$data['default']['REC_DATEXEC'] 	= $getprojrecom->REC_DATEXEC;
			$data['default']['REC_PQ_ESTIME'] 	= $getprojrecom->REC_PQ_ESTIME;
			$data['default']['REC_TEND_ESTIME'] = $getprojrecom->REC_TEND_ESTIME;
			$data['default']['REC_BIDDER'] 		= $getprojrecom->REC_BIDDER;
			$data['default']['REC_BIDDER_QTY'] 	= $getprojrecom->REC_BIDDER_QTY;
			$data['default']['REC_ESKAL_EST'] 	= $getprojrecom->REC_ESKAL_EST;
			$data['default']['REC_CONCLUTION']	 = $getprojrecom->REC_CONCLUTION;			
			$data['default']['REC_CONC_TARGET'] = $getprojrecom->REC_CONC_TARGET;
			$data['default']['REC_CONC_NOTES'] 	= $getprojrecom->REC_CONC_NOTES;
			$data['default']['REC_NOTES'] 		= $getprojrecom->REC_NOTES;
			$data['default']['REC_NOTES_EST'] 	= $getprojrecom->REC_NOTES_EST;
			$data['default']['REC_SIGN_USR_MRK']= $getprojrecom->REC_SIGN_USR_MRK;
			$data['default']['REC_USR_MRK_STAT']= $getprojrecom->REC_USR_MRK_STAT;
			$data['default']['REC_USR_MRK'] 	= $getprojrecom->REC_USR_MRK;
			$data['default']['REC_SIGN_MNG_MRK']= $getprojrecom->REC_SIGN_MNG_MRK;
			$data['default']['REC_MNG_MRK_STAT']= $getprojrecom->REC_MNG_MRK_STAT;
			$data['default']['REC_MNG_MRK'] 	= $getprojrecom->REC_MNG_MRK;
			$data['default']['REC_DIR_MRK_STAT']= $getprojrecom->REC_DIR_MRK_STAT;
			$data['default']['REC_DIR_MRK'] 	= $getprojrecom->REC_DIR_MRK;
			$data['default']['REC_SIGN_MNG_EST']= $getprojrecom->REC_SIGN_MNG_EST;
			$data['default']['REC_MNG_EST_STAT']= $getprojrecom->REC_MNG_EST_STAT;
			$data['default']['REC_MNG_EST'] 	= $getprojrecom->REC_MNG_EST;
			$data['default']['REC_PRESDIR'] 	= $getprojrecom->REC_PRESDIR;
			$data['default']['REC_STAT'] 		= $getprojrecom->REC_STAT;
							
			$data['default']['REC_FUNDSRC_NOTE']	= $getprojrecom->REC_FUNDSRC_NOTE;
			$data['default']['REC_PAY_SYS_NOTE']	= $getprojrecom->REC_PAY_SYS_NOTE;
			$data['default']['REC_DP_NOTE']			= $getprojrecom->REC_DP_NOTE;
			$data['default']['REC_TURNOVER_NOTE']	= $getprojrecom->REC_TURNOVER_NOTE;
			$data['default']['REC_EXP_NOTE']		= $getprojrecom->REC_EXP_NOTE;
			$data['default']['REC_BASCAPAB_NOTE']	= $getprojrecom->REC_BASCAPAB_NOTE;
			$data['default']['REC_FINCAPAB_NOTE']	= $getprojrecom->REC_FINCAPAB_NOTE;
			$data['default']['REC_TIMEXEC_NOTE']	= $getprojrecom->REC_TIMEXEC_NOTE;
			$data['default']['REC_PQ_ESTIME_NOTE']	= $getprojrecom->REC_PQ_ESTIME_NOTE;
			$data['default']['REC_TEND_ESTIME_NOTE']	= $getprojrecom->REC_TEND_ESTIME_NOTE;
			$data['default']['REC_BIDDER_NOTE']		= $getprojrecom->REC_BIDDER_NOTE;
			$data['default']['REC_ESKAL_EST_NOTE']	= $getprojrecom->REC_ESKAL_EST_NOTE;
			
			$data['default']['DOK_NO'] 			= $getprojrecom->DOK_NO;
			$data['default']['REVISI'] 			= $getprojrecom->REVISI;
			$data['default']['AMAND'] 			= $getprojrecom->AMAND;
			$data['default']['ISAMANDEMEN'] 	= $getprojrecom->ISAMANDEMEN;
			$data['default']['REC_CREATER'] 	= $getprojrecom->REC_CREATER;
			$data['default']['Patt_Number'] 	= $getprojrecom->Patt_Number;
			
			$this->load->view('v_project/v_project_recomendation/project_recomendation_print_excel', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function printDocRecomendPDF()
	{	
		$this->load->model('m_project/m_project_recomendation/m_project_recomendation', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$this->load->helper('pdf_helper');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$REC_CODE	= $_GET['id'];
			$REC_CODE	= $this->url_encryption_helper->decode_url($REC_CODE);
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Edit Project Recomendation';
			$data['cancel_url']		= site_url('c_project/project_recomendation/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 			= array('link_back' => anchor('c_project/project_recomendation/','<input type="button" class="btn btn-primary" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$getprojrecom = $this->m_project_recomendation->get_projrecom_bycode($REC_CODE)->row();
							
			$data['default']['REC_CODE'] 		= $getprojrecom->REC_CODE;
			$data['default']['REC_NO'] 			= $getprojrecom->REC_NO;
			$data['default']['REC_NO1'] 		= $getprojrecom->REC_NO1;
			$data['default']['PRJ_JNS'] 		= $getprojrecom->PRJ_JNS;
			$data['default']['REC_PAGE_NO'] 	= $getprojrecom->REC_PAGE_NO;
			$data['default']['REC_PRJNAME'] 	= $getprojrecom->REC_PRJNAME;
			$data['default']['REC_VALUE'] 		= $getprojrecom->REC_VALUE;
			$data['default']['REC_OWNER'] 		= $getprojrecom->REC_OWNER;
			$data['default']['REC_CURRENCY'] 	= $getprojrecom->REC_CURRENCY;
			$data['default']['REC_CONSULT_ARS'] = $getprojrecom->REC_CONSULT_ARS;
			$data['default']['REC_CONSULT_QS']	= $getprojrecom->REC_CONSULT_QS;
			$data['default']['REC_LOCATION'] 	= $getprojrecom->REC_LOCATION;			
			$data['default']['REC_DATE'] 		= $getprojrecom->REC_DATE;
			$data['default']['REC_TEND_DATE']	= $getprojrecom->REC_TEND_DATE;
			$data['default']['REC_FUNDSRC']		= $getprojrecom->REC_FUNDSRC;
			$data['default']['REC_PAY_SYS']		= $getprojrecom->REC_PAY_SYS;
			$data['default']['REC_DP'] 			= $getprojrecom->REC_DP;			
			$data['default']['REC_TURNOVER'] 	= $getprojrecom->REC_TURNOVER;
			$data['default']['REC_EXP'] 		= $getprojrecom->REC_EXP;			
			$data['default']['REC_BASCAPAB']	= $getprojrecom->REC_BASCAPAB;
			$data['default']['REC_FINCAPAB']	= $getprojrecom->REC_FINCAPAB;
			$data['default']['REC_DATEXEC'] 	= $getprojrecom->REC_DATEXEC;
			$data['default']['REC_PQ_ESTIME'] 	= $getprojrecom->REC_PQ_ESTIME;
			$data['default']['REC_TEND_ESTIME'] = $getprojrecom->REC_TEND_ESTIME;
			$data['default']['REC_BIDDER'] 		= $getprojrecom->REC_BIDDER;
			$data['default']['REC_BIDDER_QTY'] 	= $getprojrecom->REC_BIDDER_QTY;
			$data['default']['REC_ESKAL_EST'] 	= $getprojrecom->REC_ESKAL_EST;
			$data['default']['REC_CONCLUTION']	 = $getprojrecom->REC_CONCLUTION;			
			$data['default']['REC_CONC_TARGET'] = $getprojrecom->REC_CONC_TARGET;
			$data['default']['REC_CONC_NOTES'] 	= $getprojrecom->REC_CONC_NOTES;
			$data['default']['REC_NOTES'] 		= $getprojrecom->REC_NOTES;
			$data['default']['REC_NOTES_EST'] 	= $getprojrecom->REC_NOTES_EST;
			$data['default']['REC_SIGN_USR_MRK']= $getprojrecom->REC_SIGN_USR_MRK;
			$data['default']['REC_USR_MRK_STAT']= $getprojrecom->REC_USR_MRK_STAT;
			$data['default']['REC_USR_MRK'] 	= $getprojrecom->REC_USR_MRK;
			$data['default']['REC_SIGN_MNG_MRK']= $getprojrecom->REC_SIGN_MNG_MRK;
			$data['default']['REC_MNG_MRK_STAT']= $getprojrecom->REC_MNG_MRK_STAT;
			$data['default']['REC_MNG_MRK'] 	= $getprojrecom->REC_MNG_MRK;
			$data['default']['REC_DIR_MRK_STAT']= $getprojrecom->REC_DIR_MRK_STAT;
			$data['default']['REC_DIR_MRK'] 	= $getprojrecom->REC_DIR_MRK;
			$data['default']['REC_SIGN_MNG_EST']= $getprojrecom->REC_SIGN_MNG_EST;
			$data['default']['REC_MNG_EST_STAT']= $getprojrecom->REC_MNG_EST_STAT;
			$data['default']['REC_MNG_EST'] 	= $getprojrecom->REC_MNG_EST;
			$data['default']['REC_PRESDIR'] 	= $getprojrecom->REC_PRESDIR;
			$data['default']['REC_STAT'] 		= $getprojrecom->REC_STAT;
							
			$data['default']['REC_FUNDSRC_NOTE']	= $getprojrecom->REC_FUNDSRC_NOTE;
			$data['default']['REC_PAY_SYS_NOTE']	= $getprojrecom->REC_PAY_SYS_NOTE;
			$data['default']['REC_DP_NOTE']			= $getprojrecom->REC_DP_NOTE;
			$data['default']['REC_TURNOVER_NOTE']	= $getprojrecom->REC_TURNOVER_NOTE;
			$data['default']['REC_EXP_NOTE']		= $getprojrecom->REC_EXP_NOTE;
			$data['default']['REC_BASCAPAB_NOTE']	= $getprojrecom->REC_BASCAPAB_NOTE;
			$data['default']['REC_FINCAPAB_NOTE']	= $getprojrecom->REC_FINCAPAB_NOTE;
			$data['default']['REC_TIMEXEC_NOTE']	= $getprojrecom->REC_TIMEXEC_NOTE;
			$data['default']['REC_PQ_ESTIME_NOTE']	= $getprojrecom->REC_PQ_ESTIME_NOTE;
			$data['default']['REC_TEND_ESTIME_NOTE']	= $getprojrecom->REC_TEND_ESTIME_NOTE;
			$data['default']['REC_BIDDER_NOTE']		= $getprojrecom->REC_BIDDER_NOTE;
			$data['default']['REC_ESKAL_EST_NOTE']	= $getprojrecom->REC_ESKAL_EST_NOTE;
			
			$data['default']['DOK_NO'] 			= $getprojrecom->DOK_NO;
			$data['default']['REVISI'] 			= $getprojrecom->REVISI;
			$data['default']['AMAND'] 			= $getprojrecom->AMAND;
			$data['default']['ISAMANDEMEN'] 	= $getprojrecom->ISAMANDEMEN;
			$data['default']['REC_CREATER'] 	= $getprojrecom->REC_CREATER;
			$data['default']['Patt_Number'] 	= $getprojrecom->Patt_Number;
			
			$this->load->view('v_project/v_project_recomendation/project_recomendation_print_pdf', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
}