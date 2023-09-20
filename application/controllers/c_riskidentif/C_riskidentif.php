<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 April 2017
 * File Name	= C_riskidentif.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_riskidentif extends CI_Controller
{
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_riskidentif/c_riskidentif/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function index1($offset=0)
	{
		$this->load->model('m_riskidentif/m_riskidentif', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Risk Identification';
			$data['secAddURL'] 			= site_url('c_riskidentif/c_riskidentif/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data["MenuCode"] 		= 'MN276';
			
			$num_rows 					= $this->m_riskidentif->count_all_num_rows($DefEmp_ID);
			$data["recordcount"] 		= $num_rows;
	 
			$data['viewRiskIdentif'] 	= $this->m_riskidentif->get_last_ten_risk($DefEmp_ID)->result();
			
			$this->load->view('v_riskidentif/risk_identif', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_riskidentif/m_riskidentif', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Risk Identification';
			$data['h3_title']		= 'add';
			$data['form_action']	= site_url('c_riskidentif/c_riskidentif/add_process');
			//$data['link'] 			= array('link_back' => anchor('c_riskidentif/c_riskidentif/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_riskidentif/c_riskidentif/');
			
			$MenuCode 				= 'MN276';
			$data["MenuCode"] 		= 'MN276';
			$data['viewDocPattern'] = $this->m_riskidentif->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_riskidentif/risk_identif_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_riskidentif/m_riskidentif', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$RID_PRJCODE	= $this->input->post('RID_PRJCODE');
			$RID_DIVDEP		= '';
			if($RID_PRJCODE == 'KTR')
			{
				$RID_DIVDEP	= $this->input->post('RID_DIVDEP');
			}
			
			$RID_CAUSE	= $this->input->post('RID_CAUSE');
				/*$RID_CAUSE1	= explode("\n", $RID_CAUSE);
				$RID_CAUSE2 = "";
				$RID_CAUSE3	= count($RID_CAUSE1);
				for ($i=0; $i<=count($RID_CAUSE1)-1; $i++)
				{
					$RID_CAUSE4 	= str_replace($RID_CAUSE1[$i], "".$RID_CAUSE1[$i]."<br>", $RID_CAUSE1[$i]);
					$RID_CAUSE2 	.= $RID_CAUSE4;
				}
				$RID_CAUSE5	= $RID_CAUSE2;
				
				$RID_RISK	= $this->input->post('RID_RISK');
				$RID_RISK1	= explode("\n", $RID_RISK);
				$RID_RISK2 	= "";
				$RID_RISK3	= count($RID_RISK1);
				for ($i=0; $i<=count($RID_RISK1)-1; $i++)
				{
					$RID_RISK4 	= str_replace($RID_RISK1[$i], "".$RID_RISK1[$i]."<br>", $RID_RISK1[$i]);
					$RID_RISK2 	.= $RID_RISK4;
				}
				$RID_RISK5		= $RID_RISK2;
				
				$RID_POLICY		= $this->input->post('RID_POLICY');
				$RID_POLICY1	= explode("\n", $RID_POLICY);
				$RID_POLICY2 	= "";
				$RID_POLICY3	= count($RID_POLICY1);
				for ($i=0; $i<=count($RID_POLICY1)-1; $i++)
				{
					$RID_POLICY4 	= str_replace($RID_POLICY1[$i], "".$RID_POLICY1[$i]."\n", $RID_POLICY1[$i]);
					$RID_POLICY2 	.= $RID_POLICY4;
				}
				$RID_POLICY5	= $RID_POLICY2;
				
				$RID_IMPACT		= $this->input->post('RID_IMPACT');
				$RID_IMPACT1	= explode("\n", $RID_IMPACT);
				$RID_IMPACT2 	= "";
				$RID_IMPACT3	= count($RID_IMPACT1);
				for ($i=0; $i<=count($RID_IMPACT1)-1; $i++)
				{
					$RID_IMPACT4 	= str_replace($RID_IMPACT1[$i], "".$RID_IMPACT1[$i]."\n", $RID_IMPACT1[$i]);
					$RID_IMPACT2 	.= $RID_IMPACT4;
				}
				$RID_IMPACT5	= $RID_IMPACT2;*/
			
			
			$riskidentif = array('RID_CODE' 		=> $this->input->post('RID_CODE'),
									'RID_PRJCODE'	=> $this->input->post('RID_PRJCODE'),
									'RID_DIVDEP'	=> $RID_DIVDEP,
									'RID_RCATCODE'	=> $this->input->post('RID_RCATCODE'),
									'RID_CAUSE'		=> $RID_CAUSE,
									/*'RID_RISK'		=> $RID_RISK5,
									'RID_POLICY'	=> $RID_POLICY5,
									'RID_IMPACT'	=> $RID_IMPACT5,*/
									'EMP_ID'		=> $this->session->userdata['Emp_ID'],
									'Patt_Number'	=> $this->input->post('Patt_Number'));
	
			$this->m_riskidentif->add($riskidentif);
			
			foreach($_POST['data1'] as $d1)
			{
				$this->db->insert('tbl_riskdescdet',$d1);
			}
			
			foreach($_POST['data2'] as $d2)
			{
				$this->db->insert('tbl_riskimpactdet',$d2);
			}
			
			foreach($_POST['data3'] as $d3)
			{
				$this->db->insert('tbl_riskpolicydet',$d3);
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_riskidentif/c_riskidentif/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_riskidentif/m_riskidentif', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$RID_CODE	= $_GET['id'];
		$RID_CODE	= $this->url_encryption_helper->decode_url($RID_CODE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Risk Identification';
			$data['h3_title']		= 'update';
			$data['form_action']	= site_url('c_riskidentif/c_riskidentif/update_process');
			$data['link'] 			= array('link_back' => anchor('c_riskidentif/c_riskidentif/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 		= 'MN276';
			
			$getriskidentif = $this->m_riskidentif->get_riskidentif_by_code($RID_CODE)->row();
			
			$data['default']['RID_CODE'] 	= $getriskidentif->RID_CODE;
			$data['default']['RID_PRJCODE'] = $getriskidentif->RID_PRJCODE;
			$data['default']['RID_DIVDEP'] 	= $getriskidentif->RID_DIVDEP;
			$data['default']['RID_RCATCODE'] = $getriskidentif->RID_RCATCODE;
			$data['default']['RID_CAUSE']	= $getriskidentif->RID_CAUSE;
			$data['default']['RID_RISK'] 	= $getriskidentif->RID_RISK;		
			$data['default']['RID_POLICY'] 	= $getriskidentif->RID_POLICY;
			$data['default']['RID_IMPACT'] 	= $getriskidentif->RID_IMPACT;
			$data['default']['EMP_ID'] 		= $getriskidentif->EMP_ID;
			$data['default']['Patt_Number'] = $getriskidentif->Patt_Number;
			
			$this->load->view('v_riskidentif/risk_identif_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_riskidentif/m_riskidentif', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$RID_CODE		= $this->input->post('RID_CODE');
			$RID_PRJCODE	= $this->input->post('RID_PRJCODE');
			$RID_DIVDEP		= '';
			if($RID_PRJCODE == 'KTR')
			{
				$RID_DIVDEP	= $this->input->post('RID_DIVDEP');
			}
			
			$RID_CAUSE	= $this->input->post('RID_CAUSE');
				/*$RID_CAUSE1	= explode("\n", $RID_CAUSE);
				$RID_CAUSE2 = "";
				$RID_CAUSE3	= count($RID_CAUSE1);
				for ($i=0; $i<=count($RID_CAUSE1)-1; $i++)
				{
					$RID_CAUSE4 	= str_replace($RID_CAUSE1[$i], "".$RID_CAUSE1[$i]."<br>", $RID_CAUSE1[$i]);
					$RID_CAUSE2 	.= $RID_CAUSE4;
				}
				$RID_CAUSE5	= $RID_CAUSE2;
				
				$RID_RISK	= $this->input->post('RID_RISK');
				$RID_RISK1	= explode("\n", $RID_RISK);
				$RID_RISK2 	= "";
				$RID_RISK3	= count($RID_RISK1);
				for ($i=0; $i<=count($RID_RISK1)-1; $i++)
				{
					$RID_RISK4 	= str_replace($RID_RISK1[$i], "".$RID_RISK1[$i]."<br>", $RID_RISK1[$i]);
					$RID_RISK2 	.= $RID_RISK4;
				}
				$RID_RISK5		= $RID_RISK2;
				
				$RID_POLICY		= $this->input->post('RID_POLICY');
				$RID_POLICY1	= explode("\n", $RID_POLICY);
				$RID_POLICY2 	= "";
				$RID_POLICY3	= count($RID_POLICY1);
				for ($i=0; $i<=count($RID_POLICY1)-1; $i++)
				{
					$RID_POLICY4 	= str_replace($RID_POLICY1[$i], "".$RID_POLICY1[$i]."\n", $RID_POLICY1[$i]);
					$RID_POLICY2 	.= $RID_POLICY4;
				}
				$RID_POLICY5	= $RID_POLICY2;
				
				$RID_IMPACT		= $this->input->post('RID_IMPACT');
				$RID_IMPACT1	= explode("\n", $RID_IMPACT);
				$RID_IMPACT2 	= "";
				$RID_IMPACT3	= count($RID_IMPACT1);
				for ($i=0; $i<=count($RID_IMPACT1)-1; $i++)
				{
					$RID_IMPACT4 	= str_replace($RID_IMPACT1[$i], "".$RID_IMPACT1[$i]."\n", $RID_IMPACT1[$i]);
					$RID_IMPACT2 	.= $RID_IMPACT4;
				}
				$RID_IMPACT5	= $RID_IMPACT2;*/
			
			
			$riskidentif = array('RID_CODE' 		=> $this->input->post('RID_CODE'),
									'RID_PRJCODE'	=> $this->input->post('RID_PRJCODE'),
									'RID_DIVDEP'	=> $RID_DIVDEP,
									'RID_RCATCODE'	=> $this->input->post('RID_RCATCODE'),
									'RID_CAUSE'		=> $RID_CAUSE,
									/*'RID_RISK'		=> $RID_RISK5,
									'RID_POLICY'	=> $RID_POLICY5,
									'RID_IMPACT'	=> $RID_IMPACT5,*/
									'EMP_ID'		=> $this->session->userdata['Emp_ID'],
									'Patt_Number'	=> $this->input->post('Patt_Number'));
						
			$this->m_riskidentif->update($RID_CODE, $riskidentif);
			
			$this->m_riskidentif->deleteDetail1($RID_CODE, $DefEmp_ID);
			foreach($_POST['data1'] as $d1)
			{
				$this->db->insert('tbl_riskdescdet',$d1);
			}
			
			$this->m_riskidentif->deleteDetail2($RID_CODE, $DefEmp_ID);			
			foreach($_POST['data2'] as $d2)
			{
				$this->db->insert('tbl_riskimpactdet',$d2);
			}
			
			$this->m_riskidentif->deleteDetail3($RID_CODE, $DefEmp_ID);
			foreach($_POST['data3'] as $d3)
			{
				$this->db->insert('tbl_riskpolicydet',$d3);
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_riskidentif/c_riskidentif/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function deleteRISK() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_riskidentif/m_riskidentif', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$DefEmp_ID	= $_GET['id'];
			$DefEmp_ID	= $this->url_encryption_helper->decode_url($DefEmp_ID);
			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Delete Risk Identifier';
			$data['h2_title'] 	= 'Risk Management';
			$data['DefEmp_ID'] 	= $DefEmp_ID;
			
			$this->load->view('v_riskidentif/risk_identif_delete', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
}