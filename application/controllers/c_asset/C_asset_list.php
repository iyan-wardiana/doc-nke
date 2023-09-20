<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Maret 2017
 * File Name	= C_asset_list.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_asset_list  extends CI_Controller  
{
	var $limit = 2;
	var $title = 'NKE ITSys';
	
 	// Start : Index tiap halaman
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_asset/c_asset_list/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_asset/m_asset_list', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Asset List';
			$data['h3_title']		= 'asset management';
			$data['secAddURL'] 		= site_url('c_asset/c_asset_list/add/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 				= $this->m_asset_list->count_all_num_rows();
			$data["recordcount"] 	= $num_rows;
			$data["MenuCode"] 		= 'MN057';
			$data['vAssetGroup']	= $this->m_asset_list->get_last_ten_AG()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN057';
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
			
			$this->load->view('v_asset/v_asset_list/asset_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	// End
	
	function add() // OK
	{	
		$this->load->model('m_asset/m_asset_list', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Asset';
			$data['h3_title']		= 'asset management';
			$data['form_action']	= site_url('c_asset/c_asset_list/add_process');
			//$data['link'] 			= array('link_back' => anchor('c_asset/c_asset_list/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_asset/c_asset_list/');
			$MenuCode 				= 'MN057';
			$data["MenuCode"] 		= 'MN057';
			$data['viewDocPattern'] = $this->m_asset_list->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN057';
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
			
			$this->load->view('v_asset/v_asset_list/asset_list_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_asset/m_asset_list', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$AS_PRICE	= $this->input->post('AS_PRICE');
			if($AS_PRICE == 'NaN')
				$AS_PRICE = 0;
				
			$AS_CODE	= $this->input->post('AS_CODE');
			
			$InsAG 		= array('AS_CODE' 		=> $this->input->post('AS_CODE'),
								'AS_CODE_M'		=> $this->input->post('AS_CODE_M'),
								'AS_PREFIX'		=> $this->input->post('AS_PREFIX'),
								'AG_CODE'		=> $this->input->post('AG_CODE'),
								'AST_CODE'		=> $this->input->post('AST_CODE'),
								'AST_REFNO'		=> $this->input->post('AST_REFNO'),
								'AS_NAME'		=> $this->input->post('AS_NAME'),
								'AS_DESC'		=> $this->input->post('AS_DESC'),
								'AS_TYPECODE'	=> $this->input->post('AS_TYPECODE'),
								'AS_BRAND'		=> $this->input->post('AS_BRAND'),
								'AS_SN'			=> $this->input->post('AS_SN'),
								'AS_CAPACITY'	=> $this->input->post('AS_CAPACITY'),
								'AS_MACHINE'	=> $this->input->post('AS_MACHINE'),
								'AS_MACH_TYPE'	=> $this->input->post('AS_MACH_TYPE'),
								'AS_MACH_SN'	=> $this->input->post('AS_MACH_SN'),
								'AS_EXPMONTH'	=> $this->input->post('AS_EXPMONTH'),
								'AS_PRICE'		=> $this->input->post('AS_PRICE'),
								'AS_YEAR'		=> $this->input->post('AS_YEAR'),
								'AS_HM'			=> $this->input->post('AS_HM'),
								'AS_LASTPOS'	=> $this->input->post('AS_LASTPOS'),
								'AS_STAT'		=> $this->input->post('AS_STAT'),
								'AS_VOLM'		=> $this->input->post('AS_VOLM'),
								'AS_LINKACC'	=> $this->input->post('AS_LINKACC'),
								'AS_LADEPREC'	=> $this->input->post('AS_LADEPREC'),
								'Patt_Number'	=> $this->input->post('Patt_Number'));
												
			$this->m_asset_list->add($InsAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $AS_CODE;
				$MenuCode 		= 'MN057';
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
			
			$url			= site_url('c_asset/c_asset_list/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update() // OK
	{	
		$this->load->model('m_asset/m_asset_list', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$AG_CODE	= $_GET['id'];
			$AG_CODE	= $this->url_encryption_helper->decode_url($AG_CODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Add Asset Group';
			$data['h3_title']		= 'asset management';
			$data['form_action']	= site_url('c_asset/c_asset_list/update_process');
			$data["MenuCode"] 		= 'MN057';
			//$data['link'] 			= array('link_back' => anchor('c_asset/c_asset_list/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_asset/c_asset_list/');
			$getAG 					= $this->m_asset_list->get_AG($AG_CODE)->row();
			
			$data['default']['AS_CODE'] 		= $getAG->AS_CODE;
			$data['default']['AS_PREFIX'] 		= $getAG->AS_PREFIX;
			$data['default']['AS_CODE_M'] 		= $getAG->AS_CODE_M;
			$data['default']['AG_CODE'] 		= $getAG->AG_CODE;
			$data['default']['AST_CODE'] 		= $getAG->AST_CODE;
			$data['default']['AST_REFNO'] 		= $getAG->AST_REFNO;
			$data['default']['AS_NAME'] 		= $getAG->AS_NAME;
			$data['default']['AS_DESC'] 		= $getAG->AS_DESC;
			$data['default']['AS_TYPECODE'] 	= $getAG->AS_TYPECODE;
			$data['default']['AS_BRAND'] 		= $getAG->AS_BRAND;
			$data['default']['AS_SN'] 			= $getAG->AS_SN;
			$data['default']['AS_CAPACITY'] 	= $getAG->AS_CAPACITY;
			$data['default']['AS_MACHINE']		= $getAG->AS_MACHINE;
			$data['default']['AS_MACH_TYPE'] 	= $getAG->AS_MACH_TYPE;
			$data['default']['AS_MACH_SN'] 		= $getAG->AS_MACH_SN;
			$data['default']['AS_EXPMONTH'] 	= $getAG->AS_EXPMONTH;
			$data['default']['AS_PRICE'] 		= $getAG->AS_PRICE;
			$data['default']['AS_YEAR'] 		= $getAG->AS_YEAR;
			$data['default']['AS_HM'] 			= $getAG->AS_HM;
			$data['default']['AS_LASTPOS']		= $getAG->AS_LASTPOS;
			$data['default']['AS_LASTSTAT']		= $getAG->AS_LASTSTAT;
			$data['default']['AS_STAT']			= $getAG->AS_STAT;
			$data['default']['AS_VOLM']			= $getAG->AS_VOLM;
			$data['default']['AS_LINKACC']		= $getAG->AS_LINKACC;
			$data['default']['AS_LADEPREC']		= $getAG->AS_LADEPREC;
			$data['default']['Patt_Number'] 	= $getAG->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $AG_CODE;
				$MenuCode 		= 'MN057';
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
			
			$this->load->view('v_asset/v_asset_list/asset_list_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process()
	{	
		$this->load->model('m_asset/m_asset_list', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$AS_CODE	= $this->input->post('AS_CODE');
			
			$UpdAG 		= array('AS_CODE' 		=> $this->input->post('AS_CODE'),
								'AS_PREFIX'		=> $this->input->post('AS_PREFIX'),
								'AS_CODE_M'		=> $this->input->post('AS_CODE_M'),
								'AG_CODE'		=> $this->input->post('AG_CODE'),
								'AST_CODE'		=> $this->input->post('AST_CODE'),
								'AST_REFNO'		=> $this->input->post('AST_REFNO'),
								'AS_NAME'		=> $this->input->post('AS_NAME'),
								'AS_DESC'		=> $this->input->post('AS_DESC'),
								'AS_TYPECODE'	=> $this->input->post('AS_TYPECODE'),
								'AS_BRAND'		=> $this->input->post('AS_BRAND'),
								'AS_SN'			=> $this->input->post('AS_SN'),
								'AS_CAPACITY'	=> $this->input->post('AS_CAPACITY'),
								'AS_MACHINE'	=> $this->input->post('AS_MACHINE'),
								'AS_MACH_TYPE'	=> $this->input->post('AS_MACH_TYPE'),
								'AS_MACH_SN'	=> $this->input->post('AS_MACH_SN'),
								'AS_EXPMONTH'	=> $this->input->post('AS_EXPMONTH'),
								'AS_PRICE'		=> $this->input->post('AS_PRICE'),
								'AS_YEAR'		=> $this->input->post('AS_YEAR'),
								'AS_HM'			=> $this->input->post('AS_HM'),
								'AS_LASTPOS'	=> $this->input->post('AS_LASTPOS'),
								'AS_STAT'		=> $this->input->post('AS_STAT'),
								'AS_VOLM'		=> $this->input->post('AS_VOLM'),
								'AS_LINKACC'	=> $this->input->post('AS_LINKACC'),
								'AS_LADEPREC'	=> $this->input->post('AS_LADEPREC'),
								'Patt_Number'	=> $this->input->post('Patt_Number'));
								
			$this->m_asset_list->update($AS_CODE, $UpdAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $AS_CODE;
				$MenuCode 		= 'MN057';
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
			
			$url			= site_url('c_asset/c_asset_list/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}