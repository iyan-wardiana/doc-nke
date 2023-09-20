<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 Maret 2017
 * File Name	= Project_invoice.php
 * Notes		= -
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_project_invoice extends CI_Controller  
{	
 	// Start : Index tiap halaman
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_project_invoice/projectlist/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function projectlist() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_project_invoice/get_last_ten_projinv/?id=";
			
			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_projinv() // OK
	{
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE				= $_GET['id'];
			$PRJCODE				= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			// Secure URL
			$data['showIdxMReq']		= site_url('c_project/c_project_invoice/get_last_ten_projinv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Project Invoice List';	
			$data['h3_title'] 	= 'Project Invoice List';
			$data['addURL'] 	= site_url('c_project/c_project_invoice/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_project_invoice/?id='.$this->url_encryption_helper->encode_url($PRJCODE));			
			$data['PRJCODE'] 			= $PRJCODE;
			$data["MenuCode"] 			= 'MN232';
			
			$num_rows 					= $this->m_project_invoice->count_all_num_rowsProjInv($PRJCODE);
			
			$data["recordcount"] 		= $num_rows;
	 
			$data['viewprojinvoice'] 	= $this->m_project_invoice->get_last_ten_projinv($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN232';
				$TTR_CATEG		= 'L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_project_invoice/project_invoice', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function printdocument() // OK
	{	
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		if ($this->session->userdata('login') == TRUE)
		{	
			$PINV_Number			= $_GET['id'];
			$PINV_Number			= $this->url_encryption_helper->decode_url($PINV_Number);
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
					
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Document Print';
			$data['h3_title'] 		= 'Document Print';
			
			$data['recordcountOwner'] 	= $this->m_project_invoice->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_invoice->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_invoice->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_invoice->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_invoice->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice->viewProject()->result();
			
			$getpurINVO					= $this->m_project_invoice->get_PINV_by_number($PINV_Number)->row();
			
			$this->session->set_userdata('PINV_Number', $getpurINVO->PINV_CODE);
			
			$data['link'] 				= array('link_back' => anchor('c_project/c_project_invoice/get_last_ten_projinv/'.$getpurINVO->PRJCODE,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			
			$data['PINV_CODE'] 		= $getpurINVO->PINV_CODE;
			$data['PINV_MANNO'] 	= $getpurINVO->PINV_MANNO;
			$data['PINV_STEP'] 		= $getpurINVO->PINV_STEP;
			$data['PINV_CAT'] 		= $getpurINVO->PINV_CAT;
			$data['PINV_SOURCE']	= $getpurINVO->PINV_SOURCE;
			$data['PRJCODE'] 		= $getpurINVO->PRJCODE;
			$PRJCODE				= $getpurINVO->PRJCODE;
			$data['PINV_OWNER'] 	= $getpurINVO->PINV_OWNER;
			$data['PINV_DATE'] 		= $getpurINVO->PINV_DATE;
			$data['PINV_ENDDATE']	= $getpurINVO->PINV_ENDDATE; 
			$data['PINV_CHECKD'] 	= $getpurINVO->PINV_CHECKD; 
			$data['PINV_CREATED']	= $getpurINVO->PINV_CREATED;
			$data['PINV_RETVAL'] 	= $getpurINVO->PINV_RETVAL;
			$data['PINV_RETCUT'] 	= $getpurINVO->PINV_RETCUT;
			$data['PINV_DPPER'] 	= $getpurINVO->PINV_DPPER;
			$data['PINV_DPVAL'] 	= $getpurINVO->PINV_DPVAL;
			$data['PINV_DPVALPPn']	= $getpurINVO->PINV_DPVALPPn;
			$data['PINV_DPBACK'] 	= $getpurINVO->PINV_DPBACK;
			$data['PINV_DPBACKPPn'] = $getpurINVO->PINV_DPBACKPPn;
			$data['PINV_PROG'] 		= $getpurINVO->PINV_PROG;
			$data['PINV_PROGVAL']	= $getpurINVO->PINV_PROGVAL;
			$data['PINV_PROGVALPPn']= $getpurINVO->PINV_PROGVALPPn;
			$data['PINV_PROGAPP']	= $getpurINVO->PINV_PROGAPP;
			$data['PINV_PROGAPPVAL']= $getpurINVO->PINV_PROGAPPVAL;
			$data['PINV_VALADD'] 	= $getpurINVO->PINV_VALADD;
			$data['PINV_VALADDPPn'] = $getpurINVO->PINV_VALADDPPn;
			$data['PINV_MATVAL'] 	= $getpurINVO->PINV_MATVAL;
			$data['PINV_VALBEF']	= $getpurINVO->PINV_VALBEF;
			$data['PINV_VALBEFPPn']	= $getpurINVO->PINV_VALBEFPPn;
			$data['PINV_AKUMNEXT']	= $getpurINVO->PINV_AKUMNEXT;
			$data['PINV_TOTVAL'] 	= $getpurINVO->PINV_TOTVAL;
			$data['PINV_TOTVALPPn'] = $getpurINVO->PINV_TOTVALPPn;
			$data['PINV_NOTES'] 	= $getpurINVO->PINV_NOTES;
			$data['PINV_EMPID'] 	= $getpurINVO->PINV_EMPID;
			$data['PINV_STAT'] 		= $getpurINVO->PINV_STAT;
			$data['PATT_YEAR'] 		= $getpurINVO->PATT_YEAR;
			$data['PATT_MONTH'] 	= $getpurINVO->PATT_MONTH;
			$data['PATT_DATE'] 		= $getpurINVO->PATT_DATE;
			$data['PATT_NUMBER'] 	= $getpurINVO->PATT_NUMBER;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getpurINVO->PINV_CODE;
				$MenuCode 		= 'MN232';
				$TTR_CATEG		= 'P';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_project_invoice/project_invoice_print', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{	
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		if ($this->session->userdata('login') == TRUE)
		{	
			$PRJCODE				= $_GET['id'];
			$PRJCODE				= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$cancel_url 			= site_url('c_project/c_project_invoice/get_last_ten_projinv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['PRJCODE'] 		= $PRJCODE;
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Project Invoice';
			$data['h3_title']		= 'project';
			$data['main_view'] 		= 'v_project/v_project_invoice/project_invoice_sd_form';
			$data['form_action']	= site_url('c_project/c_project_invoice/add_process');
			//$data['link'] 			= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $cancel_url ;
			$data['recordcountOwner'] 	= $this->m_project_invoice->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_invoice->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_invoice->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_invoice->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_invoice->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice->viewProject()->result();
			
			$MenuCode 					= 'MN232';
			$data["MenuCode"] 			= 'MN232';
			$data['viewDocPattern'] 	= $this->m_project_invoice->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN232';
				$TTR_CATEG		= 'A';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_project_invoice/project_invoice_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			date_default_timezone_set("Asia/Jakarta");
			
			$this->db->trans_begin();
						
			//setting MC Date
			$PINV_CAT				= $this->input->post('PINV_CAT');
			$PINV_DATE				= date('Y-m-d',strtotime($this->input->post('PINV_DATE')));
			$PINV_DateY				= date('Y',strtotime($this->input->post('PINV_DATE')));
			$PINV_DateM				= date('m',strtotime($this->input->post('PINV_DATE')));
			$PINV_DateD				= date('d',strtotime($this->input->post('PINV_DATE')));
			$PINV_DATEX				= mktime(0,0,0,$PINV_DateM,$PINV_DateD,$PINV_DateY);
			$PINV_TTOTerm			= 30;
			$PINV_ENDDATE 			= date("Y-m-d",strtotime("+$PINV_TTOTerm days",$PINV_DATEX));
			//$PINV_ENDDATE			= date('Y-m-d',strtotime($this->input->post('PINV_ENDDATE')));
			
			//$PINV_CHECKD			= date('Y-m-d',strtotime($this->input->post('PINV_CHECKD')));		
			//$PINV_CREATED			= date('Y-m-d',strtotime($this->input->post('PINV_CREATED')));
			$PINV_CREATED			= date('Y-m-d H:i:s');
			$PATT_YEAR				= date('Y',strtotime($this->input->post('PINV_DATE')));
			
			$PINV_CODE 				= $this->input->post('PINV_CODE');
			$PINV_MANNO 			= $this->input->post('PINV_MANNO');
			$PRJCODE 				= $this->input->post('PRJCODE');
			
			if($PINV_CAT == 2)
				$PINV_SOURCE		= $this->input->post('PINV_SOURCE1');	// MC
			elseif($PINV_CAT == 3)
				$PINV_SOURCE		= $this->input->post('PINV_SOURCE2');	// SI
			else
				$PINV_SOURCE		= "";									// DP
			
			$PINV_STAT				= $this->input->post('PINV_STAT');
			if($PINV_STAT == 1)
				$MC_APPSTAT = 1;
			elseif($PINV_STAT == 3)
				$MC_APPSTAT = 3;
					
			$prohPINVH 				= array('PINV_CODE' 	=> $PINV_CODE,
											'PINV_MANNO'	=> $PINV_MANNO,
											'PINV_STEP'		=> $this->input->post('PINV_STEP'),
											'PINV_CAT'		=> $this->input->post('PINV_CAT'),
											'PINV_MMC'		=> $this->input->post('PINV_MMC'),
											'PINV_SOURCE'	=> $PINV_SOURCE,
											'PRJCODE'		=> $PRJCODE,
											'PINV_DATE'		=> $PINV_DATE,
											'PINV_ENDDATE'	=> $PINV_ENDDATE,
											//'PINV_CHECKD'	=> $PINV_CHECKD,
											'PINV_CREATED'	=> $PINV_CREATED,
											'PINV_RETVAL'	=> $this->input->post('PINV_RETVAL'),
											'PINV_RETCUT'	=> $this->input->post('PINV_RETCUT'),
											'PINV_DPPER'	=> $this->input->post('PINV_DPPER'),
											'PINV_DPVAL'	=> $this->input->post('PINV_DPVAL'),
											'PINV_DPVALPPn'	=> $this->input->post('PINV_DPVALPPn'),
											'PINV_DPBACK'	=> $this->input->post('PINV_DPBACK'),
											'PINV_PROG'		=> $this->input->post('PINV_PROG'),
											'PINV_PROGVAL'	=> $this->input->post('PINV_PROGVAL'),
											'PINV_VALADD'	=> $this->input->post('PINV_VALADD'),
											//'PINV_MATVAL'	=> $this->input->post('PINV_MATVAL'),					
											'PINV_VALBEF'	=> $this->input->post('PINV_VALBEF'),					
											'PINV_AKUMNEXT'	=> $this->input->post('PINV_AKUMNEXT'),
											'PINV_TOTVAL'	=> $this->input->post('PINV_TOTVAL'),
											'MC_REF'		=> $this->input->post('MC_REF'),
											'PINV_EMPID'	=> $DefEmp_ID,
											'PINV_STAT'		=> $PINV_STAT,
											'PATT_YEAR'		=> $PATT_YEAR,
											'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));
			$this->m_project_invoice->add($prohPINVH);
			
			if($PINV_CAT == 2)
			{
				$MC_REF		= $this->input->post('MC_REF');
				$MC_REFB	= explode("|",$MC_REF);
				for($i=0;$i< count($MC_REFB);$i++)
				{
					$MC_CODE 	= $MC_REFB[$i];
					$this->m_project_invoice->updateMC($MC_CODE, $MC_APPSTAT);
				}
			}
			elseif($PINV_CAT == 3)
			{
				$this->m_project_invoice->updateSIC($PINV_SOURCE, $MC_APPSTAT);
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PINV_CODE;
				$MenuCode 		= 'MN232';
				$TTR_CATEG		= 'C';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url	= site_url('c_project/c_project_invoice/get_last_ten_projinv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$PINV_CODE					= $_GET['id'];
			$PINV_CODE					= $this->url_encryption_helper->decode_url($PINV_CODE);
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			
			$getpurINVO 				= $this->m_project_invoice->get_PINV_by_number($PINV_CODE)->row();
			$PRJCODE					= $getpurINVO->PRJCODE;
			
			$data['PRJCODE'] 			= $PRJCODE;	
			$data['proj_Code'] 			= $PRJCODE;	
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'Edit';
			$data['h2_title']			= 'Add Project Invoice';
			$data['h3_title']			= 'project';
			$data['form_action']		= site_url('c_project/c_project_invoice/update_process');
			$data["MenuCode"] 			= 'MN232';
			
			$data['recordcountOwner'] 	= $this->m_project_invoice->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_invoice->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_invoice->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_invoice->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_invoice->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice->viewProject()->result();
			//echo "hahaha $PRJCODE<br>";
			//return false;
			$cancel_url 				= site_url('c_project/c_project_invoice/get_last_ten_projinv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $cancel_url ;
			$data['default']['PINV_CODE'] 		= $getpurINVO->PINV_CODE;
			$data['default']['PINV_MANNO'] 		= $getpurINVO->PINV_MANNO;
			$data['default']['PINV_STEP'] 		= $getpurINVO->PINV_STEP;
			$data['default']['PINV_CAT'] 		= $getpurINVO->PINV_CAT;
			$data['default']['PINV_MMC'] 		= $getpurINVO->PINV_MMC;
			$data['default']['PINV_SOURCE']		= $getpurINVO->PINV_SOURCE;
			$data['default']['PRJCODE'] 		= $getpurINVO->PRJCODE;
			$data['default']['PINV_OWNER'] 		= $getpurINVO->PINV_OWNER;
			$data['default']['PINV_DATE'] 		= $getpurINVO->PINV_DATE;
			$data['default']['PINV_ENDDATE']	= $getpurINVO->PINV_ENDDATE; 
			$data['default']['PINV_CHECKD'] 	= $getpurINVO->PINV_CHECKD; 
			$data['default']['PINV_CREATED']	= $getpurINVO->PINV_CREATED;
			$data['default']['PINV_RETVAL'] 	= $getpurINVO->PINV_RETVAL;
			$data['default']['PINV_RETCUT'] 	= $getpurINVO->PINV_RETCUT;
			$PINV_RETCUT						= $getpurINVO->PINV_RETCUT;
			$data['default']['PINV_RETCUTPPn'] 	= round(0.1 * $PINV_RETCUT);
			$data['default']['PINV_DPPER'] 		= $getpurINVO->PINV_DPPER;
			$data['default']['PINV_DPVAL'] 		= $getpurINVO->PINV_DPVAL;
			$data['default']['PINV_DPVALPPn']	= $getpurINVO->PINV_DPVALPPn;
			$data['default']['PINV_DPBACK'] 	= $getpurINVO->PINV_DPBACK;
			$data['default']['PINV_DPBACKPPn'] 	= $getpurINVO->PINV_DPBACKPPn;
			$data['default']['PINV_PROG'] 		= $getpurINVO->PINV_PROG;
			$data['default']['PINV_PROGVAL']	= $getpurINVO->PINV_PROGVAL;
			$data['default']['PINV_PROGVALPPn']	= $getpurINVO->PINV_PROGVALPPn;
			$data['default']['PINV_PROGAPP']	= $getpurINVO->PINV_PROGAPP;
			$data['default']['PINV_PROGAPPVAL'] = $getpurINVO->PINV_PROGAPPVAL;
			$data['default']['PINV_VALADD'] 	= $getpurINVO->PINV_VALADD;
			$data['default']['PINV_VALADDPPn'] 	= $getpurINVO->PINV_VALADDPPn;
			$data['default']['PINV_MATVAL'] 	= $getpurINVO->PINV_MATVAL;
			$data['default']['PINV_VALBEF']		= $getpurINVO->PINV_VALBEF;
			$data['default']['PINV_VALBEFPPn']	= $getpurINVO->PINV_VALBEFPPn;
			$data['default']['PINV_AKUMNEXT']	= $getpurINVO->PINV_AKUMNEXT;
			$data['default']['PINV_TOTVAL'] 	= $getpurINVO->PINV_TOTVAL;
			$data['default']['PINV_TOTVALPPn'] 	= $getpurINVO->PINV_TOTVALPPn;
			$data['default']['PINV_NOTES'] 		= $getpurINVO->PINV_NOTES;
			$data['default']['PINV_EMPID'] 		= $getpurINVO->PINV_EMPID;
			$data['default']['PINV_STAT'] 		= $getpurINVO->PINV_STAT;
			$data['default']['PATT_YEAR'] 		= $getpurINVO->PATT_YEAR;
			$data['default']['PATT_MONTH'] 		= $getpurINVO->PATT_MONTH;
			$data['default']['PATT_DATE'] 		= $getpurINVO->PATT_DATE;
			$data['default']['PATT_NUMBER'] 	= $getpurINVO->PATT_NUMBER;
			$data['default']['MC_REF'] 			= $getpurINVO->MC_REF;
			$data['PRJCODE'] 					= $getpurINVO->PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getpurINVO->PINV_CODE;
				$MenuCode 		= 'MN232';
				$TTR_CATEG		= 'U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_project_invoice/project_invoice_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			date_default_timezone_set("Asia/Jakarta");
		
			$this->db->trans_begin();
			
			//setting MC Date
			$PINV_CAT				= $this->input->post('PINV_CAT');
			$PINV_DATE				= date('Y-m-d',strtotime($this->input->post('PINV_DATE')));
			$PINV_DateY				= date('Y',strtotime($this->input->post('PINV_DATE')));
			$PINV_DateM				= date('m',strtotime($this->input->post('PINV_DATE')));
			$PINV_DateD				= date('d',strtotime($this->input->post('PINV_DATE')));
			$PINV_DATEX				= mktime(0,0,0,$PINV_DateM,$PINV_DateD,$PINV_DateY);
			$PINV_TTOTerm			= 30;
			$PINV_ENDDATE 			= date("Y-m-d",strtotime("+$PINV_TTOTerm days",$PINV_DATEX));
			//$PINV_ENDDATE			= date('Y-m-d',strtotime($this->input->post('PINV_ENDDATE')));
			
			//$PINV_CHECKD			= date('Y-m-d',strtotime($this->input->post('PINV_CHECKD')));		
			//$PINV_CREATED			= date('Y-m-d',strtotime($this->input->post('PINV_CREATED')));
			$PINV_CREATED			= date('Y-m-d H:i:s');
			$PATT_YEAR				= date('Y',strtotime($this->input->post('PINV_DATE')));
			
			$PINV_CODE 				= $this->input->post('PINV_CODE');
			$PINV_MANNO 			= $this->input->post('PINV_MANNO');
			$PRJCODE 				= $this->input->post('PRJCODE');
			
			if($PINV_CAT == 2)
				$PINV_SOURCE		= $this->input->post('PINV_SOURCE1');	// MC
			elseif($PINV_CAT == 3)
				$PINV_SOURCE		= $this->input->post('PINV_SOURCE2');	// SI
			else
				$PINV_SOURCE		= "";									// DP
			
			$PINV_STAT				= $this->input->post('PINV_STAT');
			if($PINV_STAT == 1)
				$MC_APPSTAT = 1;
			elseif($PINV_STAT == 3)
				$MC_APPSTAT = 3;
					
			$prohPINVH 				= array('PINV_CODE' 	=> $PINV_CODE,
											'PINV_MANNO'	=> $PINV_MANNO,
											'PINV_STEP'		=> $this->input->post('PINV_STEP'),
											'PINV_CAT'		=> $this->input->post('PINV_CAT'),
											'PINV_MMC'		=> $this->input->post('PINV_MMC'),
											'PINV_SOURCE'	=> $PINV_SOURCE,
											'PRJCODE'		=> $PRJCODE,
											'PINV_DATE'		=> $PINV_DATE,
											'PINV_ENDDATE'	=> $PINV_ENDDATE,
											//'PINV_CHECKD'	=> $PINV_CHECKD,
											'PINV_CREATED'	=> $PINV_CREATED,
											'PINV_RETVAL'	=> $this->input->post('PINV_RETVAL'),
											'PINV_RETCUT'	=> $this->input->post('PINV_RETCUT'),
											'PINV_DPPER'	=> $this->input->post('PINV_DPPER'),
											'PINV_DPVAL'	=> $this->input->post('PINV_DPVAL'),
											'PINV_DPVALPPn'	=> $this->input->post('PINV_DPVALPPn'),
											'PINV_DPBACK'	=> $this->input->post('PINV_DPBACK'),
											'PINV_PROG'		=> $this->input->post('PINV_PROG'),
											'PINV_PROGVAL'	=> $this->input->post('PINV_PROGVAL'),
											'PINV_VALADD'	=> $this->input->post('PINV_VALADD'),
											//'PINV_MATVAL'	=> $this->input->post('PINV_MATVAL'),					
											'PINV_VALBEF'	=> $this->input->post('PINV_VALBEF'),					
											'PINV_AKUMNEXT'	=> $this->input->post('PINV_AKUMNEXT'),
											'PINV_TOTVAL'	=> $this->input->post('PINV_TOTVAL'),
											'MC_REF'		=> $this->input->post('MC_REF'),
											'PINV_EMPID'	=> $DefEmp_ID,
											'PINV_STAT'		=> $PINV_STAT,
											'PATT_YEAR'		=> $PATT_YEAR,
											'PATT_NUMBER'	=> $this->input->post('PATT_NUMBER'));
							
			$this->m_project_invoice->update($PINV_CODE, $prohPINVH);
			
			if($PINV_CAT == 2)
			{
				$MC_REF		= $this->input->post('MC_REF');
				$MC_REFB	= explode("|",$MC_REF);
				for($i=0;$i< count($MC_REFB);$i++)
				{
					$MC_CODE 	= $MC_REFB[$i];
					$this->m_project_invoice->updateMC($MC_CODE, $MC_APPSTAT);
				}
			}
			elseif($PINV_CAT == 3)
			{
				$this->m_project_invoice->updateSIC($PINV_SOURCE, $MC_APPSTAT);
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PINV_CODE;
				$MenuCode 		= 'MN232';
				$TTR_CATEG		= 'UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url	= site_url('c_project/c_project_invoice/get_last_ten_projinv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallMC() // OK
	{	
		$this->load->model('m_project/m_project_invoice/m_project_invoice', '', TRUE);
		$this->load->model('m_project/m_project_invoice/m_project_invoice_RealINV', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		if ($this->session->userdata('login') == TRUE)
		{	
			$PRJCODE				= $_GET['id'];
			$PRJCODE				= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
						
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Select MC Number';
			$data['h3_title'] 		= 'project invoice';
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'MC';
			$data['PRJCODE']		= $PRJCODE;
					
			$this->load->view('v_project/v_project_invoice/project_selectmc', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallSI() // 
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 				= $MyAppName;
			$data['h2_title'] 			= 'Select SI Certificate Number';
			$data['txtRefference'] 		= '';
			$data['resultCount']		= 0;
			$data['pageFrom']			= 'SI';
			
			$PRJCODE 					= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
					
			$this->load->view('v_project/v_project_invoice/project_selectsic_sd', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
    function indexInbox()  // 
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$secIndex 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'inbox');
			redirect($secIndex);
		}
		else
		{
			redirect('Auth');
		}
    }

	function get_last_ten_projList($offset=0) // 
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// Secure URL
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 		= $this->session->userdata['dtSessSrc2']['selSearchproj_Code']; // Session Project Per User
			
			$data['secAddURL'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'add');
			$data['showIndex'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'index');
			$data['srch_url'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'get_last_ten_projList_src');
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Project Invoice | Project Planning List';
			$data['main_view'] 			= 'v_project/v_project_invoice/project_list_sd';
			$data['moffset'] 			= $offset;
			$data['perpage'] 			= 15;
			$data['theoffset']			= 0;
			
			$num_rows					= $this->m_project_invoice->count_all_num_rows($DefEmp_ID);
			$data["recordcount"] 		= $num_rows;
			$config 					= array();
			
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"] 		= 3;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_project/c_project_invoice/get_last_ten_projList');				
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
	 
			$data['vewproject'] = $this->m_project_invoice->get_last_ten_project($config["per_page"], $offset, $DefEmp_ID)->result();
			$data["pagination"] = $this->pagination->create_links();
			
			// // Start : Searching Function --- Untuk delete session
			$dataSessSrc = array(
					'selSearchproj_Code' => $DefProj_Code,
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			// End : Searching Function	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function get_last_ten_projList_src($offset=0) // 
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// Secure URL
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 		= $this->session->userdata['dtSessSrc2']['selSearchproj_Code']; // Session Project Per User
			
			$data['secAddURL'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'add');
			$data['showIndex'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'index');
			$data['srch_url'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'get_last_ten_projList_src');
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Project Invoice | Project Planning List';
			$data['main_view'] 			= 'v_project/v_project_invoice/project_list_sd';
			$data['moffset'] 			= $offset;
			$data['perpage'] 			= 15;
			$data['theoffset']			= 0;
			$data['moffset'] 			= $offset;	
			
			$data['selSearchType'] 		= $this->input->post('selSearchType');
			$data['txtSearch'] 			= $this->input->post('txtSearch');
			
			if (isset($_POST['submitSrch']))
			{
				$selSearchproj_Code	= $this->input->post('txtSearch');
				$selSearchType		= $this->input->post('selSearchType');
				$txtSearch 			= $this->input->post('txtSearch');
			}
			else
			{
				$selSearchproj_Code = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
				$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
				$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
			}
			
			if($selSearchType == 'ProjNumber')
			{
				$num_rows = $this->m_project_invoice->count_all_num_rows_PNo($txtSearch, $DefEmp_ID);
			}
			else
			{
				$num_rows = $this->m_project_invoice->count_all_num_rows_PNm($txtSearch, $DefEmp_ID);
			}
			
			$data["recordcount"] 		= $num_rows;
			$config 					= array();
			
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"] 		= 3;
			$config['cur_page'] 		= $offset;
			
			if($selSearchType == 'ProjNumber')
			{
				$num_rows 				= $this->m_project_invoice->count_all_num_rows_PNo($txtSearch, $DefEmp_ID);
				$data["recordcount"] 	= $num_rows;
				$data['vewproject'] 	= $this->m_project_invoice->get_last_ten_project_PNo($config["per_page"], $offset, $txtSearch, $DefEmp_ID)->result();
			}
			else
			{
				$num_rows 				= $this->m_project_invoice->count_all_num_rows_PNm($txtSearch, $DefEmp_ID);
				$data["recordcount"] 	= $num_rows;
				$data['vewproject'] 	= $this->m_project_invoice->get_last_ten_project_PNm($config["per_page"], $offset, $txtSearch, $DefEmp_ID)->result();
			}
			
			if($num_rows > 0)
			{
				$selSearchproj_Code	= $this->input->post('txtSearch');
				$selSearchType		= $this->input->post('selSearchType');
				$txtSearch 			= $this->input->post('txtSearch');
			}
			else
			{
				$selSearchproj_Code = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
				$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
				$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
			}
			
			$dataSessSrc = array(
				'selSearchproj_Code' => $selSearchproj_Code,
				'selSearchType' => $selSearchType,
				'txtSearch' => $txtSearch);
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			
			$config['base_url'] 		= site_url('c_project/c_project_invoice/get_last_ten_projList');				
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
	 
			$data["pagination"] = $this->pagination->create_links();	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_projinv_src($offset=0) // 
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$PRJCODE = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
			
			// Secure URL
			$data['showIdxMReq']	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'get_last_ten_projinv');
			$data['srch_url'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'get_last_ten_projinv_src');			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Project Invoice List';
			$data['main_view'] 		= 'v_project/v_project_invoice/project_invoice_sd';			
			$data['link'] 			= array('link_back' => anchor('c_project/c_project_invoice/','<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['proj_Code'] 		= $PRJCODE;
			$data['moffset'] 		= $offset;
			
			if (isset($_POST['submitSrch']))
			{
				$selSearchproj_Code = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
				$selSearchType		= $this->input->post('selSearchType'); // projINV_No
				$txtSearch 			= $this->input->post('txtSearch');
				
				$dataSessSrc = array(
					'selSearchproj_Code' => $selSearchproj_Code,
					'selSearchType' => $selSearchType,
					'txtSearch' => $txtSearch);
				$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
				$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			}
			else
			{
				$selSearchproj_Code = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
				$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
				$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
				
				$dataSessSrc = array(
					'selSearchproj_Code' => $selSearchproj_Code,
					'selSearchType' => $selSearchType,
					'txtSearch' => $txtSearch);
				$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
				$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			}
			
			if($selSearchType == 'projINV_No')
			{
				$num_rows = $this->m_project_invoice->count_all_num_rows_projINV_srcINV($txtSearch, $PRJCODE);
			}
			else
			{
				$num_rows = $this->m_project_invoice->count_all_num_rows_projMatReq_srcPN($txtSearch, $PRJCODE);
			}
			
			$data['PRJCODE'] 			= $PRJCODE;
			$data['selSearchType']		= $selSearchType;
			$data['txtSearch'] 			= $txtSearch;
			
			$data["recordcount"] 		= $num_rows;
			
			$config 					= array();
			$config['base_url'] 		= site_url('c_project/c_project_invoice/get_last_ten_projinv_src');
			$config["total_rows"] 		= $num_rows;			
			$config["per_page"] 		= 15;
			$config["uri_segment"] 		= 4;				
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open'] 	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link'] 		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close']	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link']		= '&gt;&gt;';
	 
			$this->pagination->initialize($config);
			
			if($selSearchType == 'projINV_No')
			{
				$data['viewprojinvoice'] = $this->m_project_invoice->get_last_ten_projinv_INVNo($config["per_page"], $offset, $txtSearch, $PRJCODE)->result();
			}
			else
			{
				$data['viewprojinvoice'] = $this->m_project_invoice->get_last_ten_projinv_PNm($config["per_page"], $offset, $txtSearch, $PRJCODE)->result();
			}
						
			$data["pagination"] = $this->pagination->create_links();
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function printdocument1($PINV_Number) // 
	{		
		if ($this->session->userdata('login') == TRUE)
		{	
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Document Print';
			
			//$data['recordcountVend'] 	= $this->m_project_invoice->count_all_num_rowsVend();
			//$data['viewvendor'] 		= $this->m_project_invoice->viewvendor()->result();
			$data['recordcountOwner'] 	= $this->m_project_invoice->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_invoice->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_invoice->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_invoice->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_invoice->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice->viewProject()->result();
			
			$getpurINVO					= $this->m_project_invoice->get_PINV_by_number($PINV_Number)->row();
			
			$this->session->set_userdata('PINV_Number', $getpurINVO->PINV_CODE);
			
			$data['link'] 				= array('link_back' => anchor('c_project/c_project_invoice/get_last_ten_projinv/'.$getpurINVO->PRJCODE,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			$data['PINV_CODE'] 		= $getpurINVO->PINV_CODE;
			$data['PINV_MANNO'] 	= $getpurINVO->PINV_MANNO;
			$data['PINV_STEP'] 		= $getpurINVO->PINV_STEP;
			$data['PINV_CAT'] 		= $getpurINVO->PINV_CAT;
			$data['PINV_SOURCE']	= $getpurINVO->PINV_SOURCE;
			$data['PRJCODE'] 		= $getpurINVO->PRJCODE;
			$data['PINV_OWNER'] 	= $getpurINVO->PINV_OWNER;
			$data['PINV_DATE'] 		= $getpurINVO->PINV_DATE;
			$data['PINV_ENDDATE']	= $getpurINVO->PINV_ENDDATE; 
			$data['PINV_CHECKD'] 	= $getpurINVO->PINV_CHECKD; 
			$data['PINV_CREATED']	= $getpurINVO->PINV_CREATED;
			$data['PINV_RETVAL'] 	= $getpurINVO->PINV_RETVAL;
			$data['PINV_RETCUT'] 	= $getpurINVO->PINV_RETCUT;
			$data['PINV_DPPER'] 	= $getpurINVO->PINV_DPPER;
			$data['PINV_DPVAL'] 	= $getpurINVO->PINV_DPVAL;
			$data['PINV_DPVALPPn']	= $getpurINVO->PINV_DPVALPPn;
			$data['PINV_DPBACK'] 	= $getpurINVO->PINV_DPBACK;
			$data['PINV_DPBACKPPn'] = $getpurINVO->PINV_DPBACKPPn;
			$data['PINV_PROG'] 		= $getpurINVO->PINV_PROG;
			$data['PINV_PROGVAL']	= $getpurINVO->PINV_PROGVAL;
			$data['PINV_PROGVALPPn']= $getpurINVO->PINV_PROGVALPPn;
			$data['PINV_PROGAPP']	= $getpurINVO->PINV_PROGAPP;
			$data['PINV_PROGAPPVAL']= $getpurINVO->PINV_PROGAPPVAL;
			$data['PINV_VALADD'] 	= $getpurINVO->PINV_VALADD;
			$data['PINV_VALADDPPn'] = $getpurINVO->PINV_VALADDPPn;
			$data['PINV_MATVAL'] 	= $getpurINVO->PINV_MATVAL;
			$data['PINV_VALBEF']	= $getpurINVO->PINV_VALBEF;
			$data['PINV_VALBEFPPn']	= $getpurINVO->PINV_VALBEFPPn;
			$data['PINV_AKUMNEXT']	= $getpurINVO->PINV_AKUMNEXT;
			$data['PINV_TOTVAL'] 	= $getpurINVO->PINV_TOTVAL;
			$data['PINV_TOTVALPPn'] = $getpurINVO->PINV_TOTVALPPn;
			$data['PINV_NOTES'] 	= $getpurINVO->PINV_NOTES;
			$data['PINV_EMPID'] 	= $getpurINVO->PINV_EMPID;
			$data['PINV_STAT'] 		= $getpurINVO->PINV_STAT;
			$data['PATT_YEAR'] 		= $getpurINVO->PATT_YEAR;
			$data['PATT_MONTH'] 	= $getpurINVO->PATT_MONTH;
			$data['PATT_DATE'] 		= $getpurINVO->PATT_DATE;
			$data['PATT_NUMBER'] 	= $getpurINVO->PATT_NUMBER;
			
			$this->load->view('v_project/v_project_invoice/project_invoice_sd_print1', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallitem($DocNumber) // U
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$proj_Code		 		= $this->session->userdata['dtProjSess']['myProjSession'];
		
		$data['title'] 			= $appName;
		$data['DocNumber'] 		= $DocNumber;
		$data['h2_title'] 		= 'Input Adendum';
		$data['main_view'] 		= 'v_project/v_project_invoice/project_invoice_sd_form';
				
		$this->load->view('v_project/v_project_invoice/project_selectinv_sd', $data);
	}
	
	function editdocument($PINV_Number) // U
	{		
		if ($this->session->userdata('login') == TRUE)
		{	
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title'] 			= 'Edit Document';
			$data['form_action']		= site_url('c_project/c_project_invoice/editdocument_process');
			
			//$data['recordcountVend'] 	= $this->m_project_invoice->count_all_num_rowsVend();
			//$data['viewvendor'] 		= $this->m_project_invoice->viewvendor()->result();
			$data['recordcountOwner'] 	= $this->m_project_invoice->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_invoice->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_invoice->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_invoice->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_invoice->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice->viewProject()->result();
			
			$getpurINVO 				= $this->m_project_invoice->get_PINV_by_number($PINV_Number)->row();
			
			$this->session->set_userdata('PINV_Number', $getpurINVO->PINV_Number);
			
			$data['link'] 				= array('link_back' => anchor('c_project/c_project_invoice/get_last_ten_projinv/'.$getpurINVO->proj_Code,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			$data['proj_Code'] 			= $getpurINVO->proj_Code;
			$data['PINV_Number'] 		= $getpurINVO->PINV_Number;
			$data['PINV_SPKNo'] 		= $getpurINVO->PINV_SPKNo;
			$data['PINV_Date'] 			= $getpurINVO->PINV_Date;
			$data['PINV_EndDate'] 		= $getpurINVO->PINV_EndDate;
			$data['PINV_Class']			= $getpurINVO->PINV_Class;
			$data['PINV_Type'] 			= $getpurINVO->PINV_Type;
			$data['Owner_Code'] 		= $getpurINVO->Owner_Code;
			$data['PINV_EmpID'] 		= $getpurINVO->PINV_EmpID;
			$data['PINV_Notes'] 		= $getpurINVO->PINV_Notes;
			$data['PINV_Status'] 		= $getpurINVO->PINV_Status;
			$data['PINV_STAT'] 			= $getpurINVO->PINV_STAT;
			$data['Patt_Year'] 			= $getpurINVO->Patt_Year;
			$data['Patt_Number'] 		= $getpurINVO->Patt_Number;
			$data['Memo_Revisi'] 		= $getpurINVO->Memo_Revisi;
			
			$this->load->view('v_project/v_project_invoice/project_invoice_sd_edit', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
    function project_invoiceRealInd() // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$secIndex 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'get_last_ten_projListRealINV');
			redirect($secIndex);
		}
		else
		{
			redirect('Auth');
		}
    }

	function get_last_ten_projListRealINV($offset=0) // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// Secure URL
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 		= $this->session->userdata['dtSessSrc2']['selSearchproj_Code']; // Session Project Per User
			
			$data['secAddURL'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'addRealInd');
			$data['showIndex'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'indexRealInd');
			$data['srch_url'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'get_last_ten_projListRealInd_src');
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Project Invoice | Project Planning List';
			$data['main_view'] 			= 'v_project/v_project_invoice/project_list_sd_RealInd';
			//$data['srch_url'] = site_url('c_project/c_project_invoice/get_last_ten_projList_src');
			$data['moffset'] 			= $offset;
			$data['perpage'] 			= 15;
			$data['theoffset']			= 0;			
			$num_rows 					= $this->m_project_invoice_RealINV->count_all_num_rows($DefEmp_ID);
			$data["recordcount"] 		= $num_rows;
			
			$config 					= array();
			$config["total_rows"]		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"] 		= 4;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_project/c_project_invoice/get_last_ten_projListRealInd');
			$config['full_tag_open']	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
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
	 
			$data['vewproject'] 		= $this->m_project_invoice_RealINV->get_last_ten_project($config["per_page"], $offset, $DefEmp_ID)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			// // Start : Searching Function --- Untuk delete session
			$dataSessSrc = array(
					'selSearchproj_Code' => $DefProj_Code,
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			// End : Searching Function	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_projinvRealINV($proj_Code, $offset=0) // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE					= $proj_Code;
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// Secure URL
			//$data['secAddURL']		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'addRealINV');
			$data['showIdxMReq']		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'get_last_ten_projinvRealINV');
			$data['srch_url'] 			= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'get_last_ten_projinvRealINV_src');
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Project Invoice Realization List';
			$data['main_view'] 			= 'v_project/v_project_invoice/project_invoice_sd_RealINV';
			//$data['srch_url'] 		= site_url('c_project/c_project_invoice/get_last_ten_projinv_src/'.$proj_Code);
			
			$data['link'] 				= array('link_back' => anchor('c_project/c_project_invoice/project_invoiceRealInd','<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE'] 			= $proj_Code;
			$data['moffset'] 			= $offset;		
			
			$num_rows 					= $this->m_project_invoice_RealINV->count_all_num_rowsProjInv($PRJCODE);
			$data["recordcount"] 		= $num_rows;
						
			$config 					= array();
			$config["total_rows"]		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"] 		= 4;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_project/c_project_invoice/get_last_ten_projinvRealINV');
			$config['full_tag_open']	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
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
	 
			$data['viewprojinvoice']	= $this->m_project_invoice_RealINV->get_last_ten_projinv($PRJCODE, $config["per_page"], $offset)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			$selSearchproj_Code = $proj_Code;
			$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
			$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
				
			$dataSessSrc = array(
				'selSearchproj_Code' => $selSearchproj_Code,
				'selSearchType' => $selSearchType,
				'txtSearch' => $txtSearch);
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			
			$myProjectSess = array(
					'myProjSession' => $selSearchproj_Code);
			$this->session->set_userdata('dtProjSess', $myProjectSess);
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function addRealINV($proj_Code) // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['proj_Code'] 			= $proj_Code;	
			$data['PRJCODE'] 			= $proj_Code;	
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Project Invoice | Add Project Invoice Realization';
			$data['main_view'] 			= 'v_project/v_project_invoice/project_invoice_sd_formRealINV';
			$data['form_action']		= site_url('c_project/c_project_invoice/add_processRealINV');
			$data['link'] 				= array('link_back' => anchor('c_project/c_project_invoice/get_last_ten_projinvRealINV/'.$proj_Code,'<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			//$data['recordcountVend'] 	= $this->m_project_invoice->count_all_num_rowsVend();
			//$data['viewvendor'] 		= $this->m_project_invoice->viewvendor()->result();
			$data['recordcountOwner'] 	= $this->m_project_invoice_RealINV->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_invoice_RealINV->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_invoice_RealINV->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_invoice_RealINV->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_invoice_RealINV->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice_RealINV->viewProject()->result();
			
			$MenuCode 				= 'MN233';
			$data['viewDocPattern'] 	= $this->m_project_invoice_RealINV->getDataDocPat($MenuCode)->result();
	
			$this->load->view('template', $data);
			
			$myProjectSess = array(
					'myProjSession' => $proj_Code);
			$this->session->set_userdata('dtProjSess', $myProjectSess);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_processRealINV() // U
	{ 
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$this->db->trans_begin();
		
		//setting PINV Date	
		$PINV_Number		= $this->input->post('PINV_Number');
		$PRINV_Number		= $this->input->post('PRINV_Number');
		$PRINV_Date			= date('Y-m-d',strtotime($this->input->post('PRINV_CreateDate')));
		$PRINV_CreateDate	= date('Y-m-d',strtotime($this->input->post('PRINV_CreateDate')));
		$PINV_Amount		= $this->input->post('PINV_Amount');
		$RealINVAmount		= $this->input->post('RealINVAmount');
		$RealINVAmountPPh	= $this->input->post('RealINVAmountPPh');
		$RealINVOtherAm		= $this->input->post('RealINVOtherAm');
		$isPPh				= $this->input->post('isPPh');
		$proj_Code			= $this->input->post('proj_Code');
		$PRINV_Notes		= $this->input->post('PRINV_Notes');
		$Patt_Year			= date('Y',strtotime($this->input->post('PRINV_Date')));		
		$Patt_Number		= $this->input->post('Patt_Number');	
		$PRINV_Deviation	= $this->input->post('PRINV_Deviation');
		
		$prohPINVH = array('PINV_Number' 	=> $PINV_Number,
						'PRINV_Number'		=> $PRINV_Number,
						'PRINV_Date'		=> $PRINV_Date,
						'PRINV_CreateDate'	=> $PRINV_CreateDate,
						'PINV_Amount'		=> $PINV_Amount,
						'RealINVAmount'		=> $RealINVAmount,
						'RealINVAmountPPh'	=> $RealINVAmountPPh,
						'RealINVOtherAm'	=> $RealINVOtherAm, 
						'isPPh'				=> $isPPh,
						'proj_Code'			=> $proj_Code,
						'PRINV_Notes'		=> $PRINV_Notes,
						'Patt_Number'		=> $Patt_Number,
						'PRINV_Deviation'	=> $PRINV_Deviation,
						'Patt_Year'			=> $Patt_Year);
						
		$this->m_project_invoice_RealINV->add($prohPINVH);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		
		redirect('c_project/c_project_invoice/get_last_ten_projinvRealINV/'.$proj_Code);
	}
	
	function popupallINV() // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$DefProj_Code    		= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
			$data['h2_title'] 		= 'Select Project Invoice';
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'PR';
			
			$data['recordcountAllPINV'] = $this->m_project_invoice_RealINV->count_all_num_rowsAllINV($DefProj_Code);
			$data['viewAllPINV'] 		= $this->m_project_invoice_RealINV->viewAllINV($DefProj_Code)->result();
					
			$this->load->view('v_project/v_project_invoice/project_selectinv_sd_selectINV', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function updateRealINV($PRINV_Number) // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Invoice Realization | Edit Invoice Realization';
			$data['main_view'] 		= 'v_project/v_project_invoice/project_invoice_sd_formRealINV';
			$data['form_action']	= site_url('c_project/c_project_invoice/update_processRealINV');
			
			$data['recordcountProject']	= $this->m_project_invoice_RealINV->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice_RealINV->viewProject()->result();
			$getpurINVO 				= $this->m_project_invoice_RealINV->get_PRINV_by_number($PRINV_Number)->row();
			
			$this->session->set_userdata('PRINV_Number', $getpurINVO->PRINV_Number);
			
			$data['link'] 			= array('link_back' => anchor('c_project/c_project_invoice/project_invoiceRealInd/'.$getpurINVO->proj_Code,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			$data['default']['PINV_Number'] 		= $getpurINVO->PINV_Number;
			$data['default']['PRINV_Number'] 		= $getpurINVO->PRINV_Number;
			$data['default']['PRINV_Date'] 			= $getpurINVO->PRINV_Date;
			$data['default']['PRINV_CreateDate'] 	= $getpurINVO->PRINV_CreateDate;
			$data['default']['PRINV_Deviation'] 	= $getpurINVO->PRINV_Deviation;
			$data['default']['PINV_Amount'] 		= $getpurINVO->PINV_Amount;
			$data['default']['RealINVAmount'] 		= $getpurINVO->RealINVAmount; 
			$data['default']['RealINVAmountPPh'] 	= $getpurINVO->RealINVAmountPPh; 
			$data['default']['RealINVOtherAm'] 		= $getpurINVO->RealINVOtherAm;
			$data['default']['isPPh'] 				= $getpurINVO->isPPh;
			$data['default']['proj_Code'] 			= $getpurINVO->proj_Code;
			$data['default']['PRJCODE'] 			= $getpurINVO->proj_Code;
			$data['default']['Patt_Number'] 		= $getpurINVO->Patt_Number;
			$data['default']['PRINV_Notes'] 		= $getpurINVO->PRINV_Notes;
			$data['default']['Patt_Year'] 			= $getpurINVO->Patt_Year;
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_processRealINV() // U
	{ 
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$this->db->trans_begin();
		
		//setting PINV Date	
		$PINV_Number		= $this->input->post('PINV_Number');
		$PRINV_Number		= $this->input->post('PRINV_Number');
		$PRINV_Date			= date('Y-m-d',strtotime($this->input->post('PRINV_CreateDate')));
		$PRINV_CreateDate	= date('Y-m-d',strtotime($this->input->post('PRINV_CreateDate')));
		$PINV_Amount		= $this->input->post('PINV_Amount');
		$RealINVAmount		= $this->input->post('RealINVAmount');
		$RealINVAmountPPh	= $this->input->post('RealINVAmountPPh');
		$RealINVOtherAm		= $this->input->post('RealINVOtherAm');
		$isPPh				= $this->input->post('isPPh');
		$proj_Code			= $this->input->post('proj_Code');
		$PRINV_Notes		= $this->input->post('PRINV_Notes');
		$Patt_Year			= date('Y',strtotime($this->input->post('PRINV_Date')));		
		$Patt_Number		= $this->input->post('Patt_Number');	
		$PRINV_Deviation	= $this->input->post('PRINV_Deviation');
		
		$prohPINVH = array('PINV_Number' 	=> $PINV_Number,
						'PRINV_Number'		=> $PRINV_Number,
						'PRINV_Date'		=> $PRINV_Date,
						'PRINV_CreateDate'	=> $PRINV_CreateDate,
						'PINV_Amount'		=> $PINV_Amount,
						'RealINVAmount'		=> $RealINVAmount,
						'RealINVAmountPPh'	=> $RealINVAmountPPh,
						'RealINVOtherAm'	=> $RealINVOtherAm, 
						'isPPh'				=> $isPPh,
						'proj_Code'			=> $proj_Code,
						'PRINV_Notes'		=> $PRINV_Notes,
						'Patt_Number'		=> $Patt_Number,
						'PRINV_Deviation'	=> $PRINV_Deviation,
						'Patt_Year'			=> $Patt_Year);
						
		$this->m_project_invoice_RealINV->updareRealPRINV($PRINV_Number, $prohPINVH);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		
		redirect('c_project/c_project_invoice/get_last_ten_projinvRealINV/'.$proj_Code);
	}
	
	function printdocumentRealInv($PRINV_Number) // U
	{		
		if ($this->session->userdata('login') == TRUE)
		{	
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Document Print';
			
			//$data['recordcountVend'] 	= $this->m_project_invoice->count_all_num_rowsVend();
			//$data['viewvendor'] 		= $this->m_project_invoice->viewvendor()->result();
			$data['recordcountOwner'] 	= $this->m_project_invoice_RealINV->count_all_num_rowsOwner();
			$data['viewOwner'] 			= $this->m_project_invoice_RealINV->viewOwner()->result();
			$data['recordcountEmpDept'] = $this->m_project_invoice_RealINV->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_project_invoice_RealINV->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_project_invoice_RealINV->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_project_invoice_RealINV->viewProject()->result();
			
			$getpurINVO					= $this->m_project_invoice_RealINV->get_PRINV_by_number($PRINV_Number)->row();
			
			$this->session->set_userdata('PRINV_Number', $getpurINVO->PRINV_Number);
			
			$data['link'] 				= array('link_back' => anchor('c_project/c_project_invoice/get_last_ten_projinv/'.$getpurINVO->proj_Code,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			/*$data['default']['PINV_Number'] 		= $getpurINVO->PINV_Number;
			$data['default']['PRINV_Number'] 		= $getpurINVO->PRINV_Number;
			$data['default']['PRINV_Date'] 			= $getpurINVO->PRINV_Date;
			$data['default']['PRINV_CreateDate'] 	= $getpurINVO->PRINV_CreateDate;
			$data['default']['PRINV_Deviation'] 	= $getpurINVO->PRINV_Deviation;
			$data['default']['PINV_Amount'] 		= $getpurINVO->PINV_Amount;
			$data['default']['RealINVAmount'] 		= $getpurINVO->RealINVAmount; 
			$data['default']['RealINVAmountPPh'] 	= $getpurINVO->RealINVAmountPPh; 
			$data['default']['RealINVOtherAm'] 		= $getpurINVO->RealINVOtherAm;
			$data['default']['isPPh'] 				= $getpurINVO->isPPh;
			$data['default']['proj_Code'] 			= $getpurINVO->proj_Code;
			$data['default']['PRJCODE'] 			= $getpurINVO->proj_Code;
			$data['default']['Patt_Number'] 		= $getpurINVO->Patt_Number;
			$data['default']['PRINV_Notes'] 		= $getpurINVO->PRINV_Notes;
			$data['default']['Patt_Year'] 			= $getpurINVO->Patt_Year;*/
								
			$data['proj_Code'] 			= $getpurINVO->proj_Code;
			$data['PINV_Number'] 		= $getpurINVO->PINV_Number;
			$data['PINV_SPKNo'] 		= $getpurINVO->PINV_SPKNo;
			$data['PINV_Date'] 			= $getpurINVO->PINV_Date;
			$data['PINV_EndDate'] 		= $getpurINVO->PINV_EndDate;
			$data['PINV_Class']			= $getpurINVO->PINV_Class;
			$data['PINV_Type'] 			= $getpurINVO->PINV_Type;
			$data['Owner_Code'] 		= $getpurINVO->Owner_Code;
			$data['PINV_EmpID'] 		= $getpurINVO->PINV_EmpID;
			$data['PINV_Notes'] 		= $getpurINVO->PINV_Notes;
			$data['PINV_Status'] 		= $getpurINVO->PINV_Status;
			$data['PINV_STAT'] 			= $getpurINVO->PINV_STAT;
			$data['Patt_Year'] 			= $getpurINVO->Patt_Year;
			$data['Patt_Number'] 		= $getpurINVO->Patt_Number;
			$data['Memo_Revisi'] 		= $getpurINVO->Memo_Revisi;
			$this->load->view('v_project/v_project_invoice/project_invoice_sd_print', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallpr()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Select Purchase Requisition';
			$data['form_action']	= site_url('c_project/c_project_invoice/update_process');
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'PR';
			
			$data['recordcountAllPR'] = $this->m_project_invoice->count_all_num_rowsAllPR();
			$data['viewAllPR'] 	= $this->m_project_invoice->viewAllPR()->result();
					
			$this->load->view('v_project/v_listproject/purchase_selectpr', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallpr2()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Select Item';
			$data['form_action']	= site_url('c_project/c_project_invoice/update_process');
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'DIR'; //DIR = Direct (non PR)
			
			$data['recordcountAllItem'] = $this->m_project_invoice->count_all_num_rowsAllItem();
			$data['viewAllItem'] 	= $this->m_project_invoice->viewAllItem()->result();
					
			$this->load->view('v_project/v_listproject/purchase_selectitem', $data);	
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function delete($PR_Number)
	{
		$this->m_project_invoice->delete($this->input->post('chkDetail'));
		$this->session->set_flashdata('message', 'Data successfull deleted');
		
		redirect('c_project/c_project_invoice/');
	}
	
    function inbox() 
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$secIndex 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/c_project_invoice'),'inboxInbox');
			redirect($secIndex);
		}
		else
		{
			redirect('Auth');
		}
    }
	
    function inboxInbox($offset=0)
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// Secure URL
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 		= $this->session->userdata['dtSessSrc2']['selSearchproj_Code']; // Session Project Per User
		
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Project Planning List';
			$data['main_view'] 	= 'v_project/v_project_invoice/project_invoice_sd_inb';
			$data['srch_url'] = site_url('c_project/c_project_invoice/inbox_src');
			$data['moffset'] = $offset;

			//$num_rows = $this->m_project_invoice->count_all_num_rows();
			$num_rows = $this->m_project_invoice->count_all_num_rowsInbox($DefEmp_ID);		
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_project/project_planning/get_last_ten_project');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 20;
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
		
			$data['vewproject'] = $this->m_project_invoice->get_last_ten_projectInbox($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();
		
			// // Start : Searching Function --- Untuk delete session
			$dataSessSrc = array(
					'selSearchproj_Code' => $DefProj_Code,
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$dataSessSrc   = $this->session->userdata('dtSessSrc1');
			$dataSessSrc   = $this->session->userdata('dtSessSrc2');
			// End : Searching Function	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
    }
	
    function inbox_src($offset=0)
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// Secure URL
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 		= $this->session->userdata['dtSessSrc2']['selSearchproj_Code']; // Session Project Per User
			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Project Planning List';
			$data['main_view'] 	= 'v_project/v_project_invoice/project_invoice_sd_inb';
			$data['srch_url'] = site_url('c_project/c_project_invoice/inbox_src');
			$data['moffset'] = $offset;
		
			$data['selSearchType'] = $this->input->post('selSearchType');
			$data['txtSearch'] = $this->input->post('txtSearch');		
			
			if (isset($_POST['submitSrch']))
			{
				$selSearchType	= $this->input->post('selSearchType');
				$txtSearch 		= $this->input->post('txtSearch');
			}
			else
			{
				$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
				$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
			}	
			
			if($selSearchType == 'ProjNumber')
			{				
				$num_rows = $this->m_project_invoice->count_all_num_rows_PNo($txtSearch, $DefEmp_ID);
			}
			else
			{
				$num_rows = $this->m_project_invoice->count_all_num_rows_PNm($txtSearch, $DefEmp_ID);
			}	
			
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_project/project_planning/get_last_ten_project');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 10;
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
			
			if($selSearchType == 'ProjNumber')
			{
				$data['vewproject'] = $this->m_project_invoice->get_last_ten_project_PNo($config["per_page"], $offset, $txtSearch, $DefEmp_ID)->result();
			}
			else
			{
				$data['vewproject'] = $this->m_project_invoice->get_last_ten_project_PNm($config["per_page"], $offset, $txtSearch, $DefEmp_ID)->result();
			}
			
			$data["pagination"] = $this->pagination->create_links();
		
			// // Start : Searching Function --- Untuk delete session
			$dataSessSrc = array(
					'selSearchproj_Code' => $DefProj_Code,
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
    }

	function get_last_ten_projinvInb($proj_Code, $offset=0)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		// Secure URL
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		$DefProj_Code		 	= $proj_Code; // Session Project Per User
		
		$data['title'] 		= $appName;
		$data['h2_title']	= 'Project Invoice';
		$data['main_view'] 	= 'v_project/v_project_invoice/project_invoice_sd_inbox';
		$data['srch_url'] 	= site_url('c_project/c_project_invoice/get_last_ten_projinvInb_src/'.$proj_Code);
		$data['link'] 		= array('link_back' => anchor('c_project/c_project_invoice/','<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Back" />', array('style' => 'text-decoration: none;')));
		//$data['proj_ID1'] = $proj_ID;
		$data['proj_Code1'] = $proj_Code;
		$data['moffset'] 	= $offset;	
		
		$num_rows = $this->m_project_invoice->count_all_num_rowsProjInv($proj_Code);
		$num_rows1 = $this->m_project_invoice->count_all_num_rowsProjInv1($proj_Code);
        $data["recordcount"] = $num_rows;
        $data["recordcount1"] = $num_rows1;
		$config = array();
        $config['base_url'] = site_url('c_project/c_project_invoice/get_last_ten_projinv');
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

        $data['viewprojinvoice'] = $this->m_project_invoice->get_last_ten_projinvInb($proj_Code, $config["per_page"], $offset)->result();
        $data["pagination"] = $this->pagination->create_links();
		
		// // Start : Searching Function --- Untuk delete session
		$dataSessSrc = array(
                'selSearchproj_Code' => $DefProj_Code,
                'selSearchType' => $this->input->post('selSearchType'),
                'txtSearch' => $this->input->post('txtSearch'));
		$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
		$dataSessSrc   = $this->session->userdata('dtSessSrc1');
		// End : Searching Function	
		
		$this->load->view('template', $data);
	}

	function get_last_ten_projinvInb_src($proj_Code, $offset=0)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] = $appName;
		$data['h2_title'] = 'Project Invoice';
		$data['main_view'] = 'v_project/v_project_invoice/project_invoice_sd_inbox';
			$data['srch_url'] = site_url('c_project/c_project_invoice/get_last_ten_projinvInb_src/'.$proj_Code);
		$data['link'] 			= array('link_back' => anchor('c_project/c_project_invoice/','<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Back" />', array('style' => 'text-decoration: none;')));
		//$data['proj_ID1'] = $proj_ID;
		$data['proj_Code1'] = $proj_Code;
		$data['moffset'] = $offset;
		
		$data['selSearchType'] = $this->input->post('selSearchType');
		$data['txtSearch'] = $this->input->post('txtSearch');		
		
		if (isset($_POST['submitSrch']))
		{
			$selSearchType	= $this->input->post('selSearchType');
			$txtSearch 		= $this->input->post('txtSearch');
			
			$dataSessSrc = array(
                'selSearchType' => $this->input->post('selSearchType'),
                'txtSearch' => $this->input->post('txtSearch'));
				
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			
			$dataSessSrc   = $this->session->userdata('dtSessSrc1');
		}
		else
		{
			$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
			$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
			
			$dataSessSrc = array(
                'selSearchType' => $this->session->userdata['dtSessSrc1']['selSearchType'],
                'txtSearch' => $this->session->userdata['dtSessSrc1']['txtSearch']);
				
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
		}
		
		$num_rows = $this->m_project_invoice->count_all_num_rows();
        $data["recordcount"] = $num_rows;
		$config = array();
        $config['base_url'] = site_url('c_project/c_project_invoice/get_last_ten_projinv');
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
		
		if($selSearchType == 'projINV_No')
		{
			$data['viewprojinvoice'] = $this->m_project_invoice->get_last_ten_projinvInb_MRNo($config["per_page"], $offset, $txtSearch)->result();
		}
		else
		{
			$data['viewprojinvoice'] = $this->m_project_invoice->get_last_ten_projinvInb_PNm($config["per_page"], $offset, $txtSearch)->result();
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
	
	function update_inbox($PINV_Number)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;
		$data['task'] 			= 'edit';
		$data['h2_title'] 		= 'Project Invoice | Update';
		$data['main_view'] 		= 'v_project/v_project_invoice/project_invoice_sd_form_inbox';
		$data['form_action']	= site_url('c_project/c_project_invoice/update_process_inbox');
		
		$data['recordcountVend'] 	= $this->m_project_invoice->count_all_num_rowsVend();
		$data['viewvendor'] 	= $this->m_project_invoice->viewvendor()->result();
		$data['recordcountDept'] 	= $this->m_project_invoice->count_all_num_rowsDept();
		$data['viewDepartment'] 	= $this->m_project_invoice->viewDepartment()->result();
		$data['recordcountEmpDept'] 	= $this->m_project_invoice->count_all_num_rowsEmpDept();
		$data['viewEmployeeDept'] 	= $this->m_project_invoice->viewEmployeeDept()->result();
		$data['recordcountProject']	= $this->m_project_invoice->count_all_num_rowsProject();
		$data['viewProject'] 		= $this->m_project_invoice->viewProject()->result();
		
		$getpurINVO = $this->m_project_invoice->get_PINV_by_number($PINV_Number)->row();
		
		$this->session->set_userdata('PINV_Number', $getpurINVO->PINV_Number);
		
		$data['link'] 			= array('link_back' => anchor('c_project/c_project_invoice/get_last_ten_projinvInb/'.$getpurINVO->proj_Code,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
		
		$data['default']['proj_ID'] = $getpurINVO->proj_ID;
		$data['default']['proj_Code'] = $getpurINVO->proj_Code;
		$data['default']['PINV_Number'] = $getpurINVO->PINV_Number;
		$data['default']['PINV_Date'] = $getpurINVO->PINV_Date;
		$data['default']['req_date'] = $getpurINVO->req_date;
		$data['default']['PINV_EndDate'] = $getpurINVO->PINV_EndDate;
		$data['default']['PINV_Class'] = $getpurINVO->PINV_Class;
		$data['default']['PINV_Type'] = $getpurINVO->PINV_Type;
		$data['default']['Owner_Code'] = $getpurINVO->Owner_Code;
		$data['default']['PINV_EmpID'] = $getpurINVO->PINV_EmpID;
		$data['default']['Vend_Code'] = $getpurINVO->Vend_Code;
		$data['default']['PINV_Notes'] = $getpurINVO->PINV_Notes;
		$data['default']['PINV_Status'] = $getpurINVO->PINV_Status;
		$data['default']['PINV_STAT'] = $getpurINVO->PINV_STAT;
		$data['default']['Patt_Year'] = $getpurINVO->Patt_Year;
		$data['default']['Patt_Number'] = $getpurINVO->Patt_Number;
		$data['default']['Memo_Revisi'] = $getpurINVO->Memo_Revisi;
			
		$this->load->view('template', $data);
	}
	
	function update_process_inbox()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;
		$data['task'] 			= 'edit';
		$data['h2_title'] 		= 'Project Invoice | Update Project Invoice';
		$data['main_view'] 		= 'v_project/v_project_invoice/project_invoice_sd_form';
		$data['form_action']	= site_url('c_project/c_project_invoice/update_inbox');
		$data['link'] 			= array('link_back' => anchor('c_project/c_project_invoice/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
		
		//setting MR Date
		$PINV_Date= date('Y-m-d',strtotime($this->input->post('PINV_Date')));
		$Patt_Year= date('Y',strtotime($this->input->post('PINV_Date')));
		//setting Requested Date	
		$req_date= date('Y-m-d',strtotime($this->input->post('req_date')));
		//setting Latest Date
		$PINV_EndDate= date('Y-m-d',strtotime($this->input->post('PINV_EndDate')));
		
		date_default_timezone_set("Asia/Jakarta");
		$Approve_Date = date('Y-m-d H:i:s');
		
		$proj_ID = $this->input->post('proj_ID');
		$proj_Code = $this->input->post('proj_Code');
		
		$valAppStatus = $this->input->post('PINV_STAT');		
		if($valAppStatus == 1 || $valAppStatus == 2)
		{
			$PINV_Status = 2; // 1 = New, 2 = confirm, 3 = Approved, 4 = Close, 5 = Reject
		}
		elseif($valAppStatus == 3)
		{
			$PINV_Status = 3; // 1 = New, 2 = confirm, 3 = Approved, 4 = Close, 5 = Reject
		}
		elseif($valAppStatus == 4)
		{
			$PINV_Status = 1; // 1 = New, 2 = confirm, 3 = Approved, 4 = Close, 5 = Reject
		}
		elseif($valAppStatus == 5)
		{
			$PINV_Status = 5; // 1 = New, 2 = confirm, 3 = Approved, 4 = Close, 5 = Reject
		}
		
		$prohPINVH = array('PINV_Number' 	=> $this->input->post('PINV_Number'),
						'proj_ID'		=> $this->input->post('proj_ID'),
						'proj_Code'		=> $this->input->post('proj_Code'),
						'PINV_Date'		=> $PINV_Date,
						'req_date'		=> $req_date,
						'PINV_EndDate'	=> $PINV_EndDate,
						'Approve_Date'		=> $Approve_Date,
						'PINV_Class'		=> $this->input->post('PINV_Class'),
						'PINV_Type'		=> $this->input->post('PINV_Type'), 
						'Owner_Code'		=> $this->input->post('Owner_Code'),
						'PINV_EmpID'		=> $this->input->post('PINV_EmpID'),
						'Vend_Code'		=> $this->input->post('Vend_Code'),
						'PINV_Notes'		=> $this->input->post('PINV_Notes'),
						'PINV_Status'		=> $PINV_Status,
						'PINV_STAT'	=> $this->input->post('PINV_STAT'),
						'Patt_Year'		=> $this->input->post('Patt_Year'),
						'Patt_Number'	=> $this->input->post('lastPatternNumb'),
						'Memo_Revisi'	=> $this->input->post('Memo_Revisi'));
						
		$this->m_project_invoice->update($this->session->userdata('PINV_Number'), $prohPINVH);	
		
		$this->m_project_invoice->deleteDetail($this->input->post('PINV_Number'));
		
		foreach($_POST['data'] as $d)
		{
			if($valAppStatus == 3)
			{
				$PINV_Number = $d['PINV_Number'];
				$Item_code = $d['Item_code'];
				$request_qty1 = $d['request_qty1'];
				$request_qty2 = $d['request_qty2'];
				// Update Qty  PO for Project Plan per Item Per Project
				$parameters = array(
					'PINV_Number' => $PINV_Number,
					'Item_code' => $Item_code,
					'proj_ID' => $proj_ID,
					'proj_Code' => $proj_Code,
					'request_qty1' => $request_qty1,
					'request_qty2' => $request_qty2
				);
				$this->m_project_invoice->updatePP($PO_Number, $parameters);	
			}
        	$this->db->insert('tproject_mrdetail',$d);
        }
		
		$this->session->set_flashdata('message', 'Data succesful to update.');
		
		redirect('c_project/c_project_invoice/get_last_ten_projinvInb/'.$proj_Code);
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
}