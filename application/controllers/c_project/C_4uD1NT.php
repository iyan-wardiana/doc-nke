<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 8 Agustus 2019
 * File Name	= C_4uD1NT.php 
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_4uD1NT extends CI_Controller  
{
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_4uD1NT/get_allDoc/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function get_allDoc($offset=0)
	{
		$this->load->model('m_project/m_audit/m_audit', '', TRUE);
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			$EmpID 		= $this->session->userdata('Emp_ID');
					
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Audit';
			$data['h3_title']		= 'Internal';
			$data['main_view'] 		= 'v_project/v_audit/v_audit';
			$data["MenuCode"] 		= 'MN374';
			
			$num_rows 				= $this->m_audit->count_allDoc($DefEmp_ID);
			$data["recordcount"] 	= $num_rows;
	 
			$data['vwAudit'] 		= $this->m_audit->get_allDoc($DefEmp_ID)->result();
			
			$this->load->view('v_project/v_audit/v_audit', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function add()
	{
		$this->load->model('m_project/m_audit/m_audit', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title'] 		= 'Tambah Dokumen';
			$data['h3_title'] 		= 'audit internal';
			$data['form_action1']	= site_url('c_project/c_4uD1NT/add_process');
			$data['form_action2']	= site_url('c_project/c_4uD1NT/update_process');
			$data['form_action3']	= site_url('c_project/c_4uD1NT/update_process');
			$data['form_action4']	= site_url('c_project/c_4uD1NT/add_process4');
			$data['backURL'] 		= site_url('c_project/c_4uD1NT/');
			
			$MenuCode 				= 'MN374';
			$data["MenuCode"] 		= 'MN374';
			$data['viewDocPattern'] = $this->m_audit->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_project/v_audit/v_audit_form', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function add_process()
	{ 
		$this->load->model('m_project/m_audit/m_audit', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			$PRJCODE	= $this->input->post('PRJCODE');
			$PRJNAME	= '';
			$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$resPRJ		= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJNAME	= $rowPRJ->PRJNAME;
			endforeach;
			
			$AUI_SUBJEK1	= $this->input->post('AUI_SUBJEK');
			$AUI_SUBJ		= '';
			$COLL_SUBJEK	= '';
			$selStep		= 0;
			
			if($AUI_SUBJEK1 != '')
			{
				$refStep	= 0;					
				foreach ($AUI_SUBJEK1 as $AUI_SUBJ)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLL_SUBJEK	= "$AUI_SUBJ";
					}
					else
					{
						$COLL_SUBJEK	= "$COLL_SUBJEK;$AUI_SUBJ";
					}
				}
			}
			
			$AUI_AUDITOR1	= $this->input->post('AUI_AUDITOR');
			$AUI_AUDITOR	= '';
			$COLL_AUDITOR	= '';
			$COLL_AUINIT	= '';
			$selStep		= 0;
			if($AUI_AUDITOR1 != '')
			{
				$refStep	= 0;					
				foreach ($AUI_AUDITOR1 as $AUI_AUDITOR)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLL_AUDITOR	= "$AUI_AUDITOR";
						$EMP_INIT		= '';
						$sqlINIT		= "SELECT EMP_INIT FROM tbl_employee WHERE Emp_ID = '$AUI_AUDITOR'";
						$resINIT		= $this->db->query($sqlINIT)->result();
						foreach($resINIT as $rowINIT):
							$EMP_INIT	= $rowINIT->EMP_INIT;
						endforeach;
						$COLL_AUINIT	= $EMP_INIT;
					}
					else
					{
						$COLL_AUDITOR	= "$COLL_AUDITOR;$AUI_AUDITOR";
						$EMP_INIT		= '';
						$sqlINIT		= "SELECT EMP_INIT FROM tbl_employee WHERE Emp_ID = '$AUI_AUDITOR'";
						$resINIT		= $this->db->query($sqlINIT)->result();
						foreach($resINIT as $rowINIT):
							$EMP_INIT	= $rowINIT->EMP_INIT;
						endforeach;
						$COLL_AUINIT	= "$COLL_AUINIT,$EMP_INIT";
					}
				}
			}
			
			$AUI_CC1	= $this->input->post('AUI_CC');
			$AUI_CC		= '';
			$COLL_CC	= '';
			$selStep	= 0;
			if($AUI_CC1 != '')
			{
				$refStep	= 0;					
				foreach ($AUI_CC1 as $AUI_CC)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLL_CC	= "$AUI_CC";
					}
					else
					{
						$COLL_CC	= "$COLL_CC;$AUI_CC";
					}
				}
			}
			
			$REFDOC1	= $this->input->post('AUI_REFDOC');
			$REFDOC		= '';
			$COLL_REFD	= '';
			$selStep	= 0;
			if($REFDOC1 != '')
			{
				$refStep	= 0;					
				foreach ($REFDOC1 as $REFDOC)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLL_REFD	= "$REFDOC";
					}
					else
					{
						$COLL_REFD	= "$COLL_REFD;$REFDOC";
					}
				}
			}
			
			$AUI_STAT1	= $this->input->post('AUI_STAT1');
			if($AUI_STAT1 == 1)
				$AUI_STAT	= 1;
			
			$insAudit 	= array('AUI_NUM'		=> $this->input->post('AUI_NUM'),
								'AUI_CODE'		=> $this->input->post('AUI_CODE'),
								'PRJCODE'		=> $PRJCODE,
								'PRJNAME'		=> $PRJNAME, 
								'AUI_STEP'		=> $this->input->post('AUI_STEP'),
								'AUI_ORDNO'		=> $this->input->post('AUI_ORDNO'),
								'AUI_INIT'		=> $COLL_AUINIT,
								'AUI_DEPT'		=> $this->input->post('AUI_DEPT'),
								'AUI_SUBJEK'	=> $COLL_SUBJEK,
								'AUI_LOC'		=> $this->input->post('AUI_LOC'),
								'AUI_DATE'		=> date('Y-m-d', strtotime($this->input->post('AUI_DATE'))),
								'AUI_TARGETD'	=> date('Y-m-d', strtotime($this->input->post('AUI_TARGETD'))),
								'AUI_DATE_NCR'	=> date('Y-m-d', strtotime($this->input->post('AUI_DATE_NCR'))),
								'AUI_FINISHP'	=> date('Y-m-d', strtotime($this->input->post('AUI_TARGETD'))),
								'AUI_NCRD'		=> date('Y-m-d', strtotime($this->input->post('AUI_TARGETD'))),
								'AUI_REVIEWD'	=> date('Y-m-d', strtotime($this->input->post('AUI_TARGETD'))),
								'AUI_AUDITOR'	=> $COLL_AUDITOR,
								'AUI_CC'		=> $COLL_CC,
								'AUI_REFDOC'	=> $COLL_REFD,
								'AUI_PROBLDESC'	=> $this->input->post('AUI_PROBLDESC'),
								'AUI_KLAUS1'	=> $this->input->post('AUI_KLAUS1'),
								'AUI_KLAUS2'	=> $this->input->post('AUI_KLAUS2'),
								'AUI_KLAUS3'	=> $this->input->post('AUI_KLAUS3'),
								'AUI_KLAUS4'	=> $this->input->post('AUI_KLAUS4'),
								'AUI_TYPE'		=> $this->input->post('AUI_TYPE'),
								'AUI_SCOPE1'	=> $this->input->post('AUI_SCOPE1'),
								'AUI_SCOPE2'	=> $this->input->post('AUI_SCOPE2'),
								'AUI_SCOPE3'	=> $this->input->post('AUI_SCOPE3'),
								'AUI_SYSPROC1'	=> $this->input->post('AUI_SYSPROC1'),
								'AUI_SYSPROC2'	=> $this->input->post('AUI_SYSPROC2'),
								'AUI_SYSPROC3'	=> $this->input->post('AUI_SYSPROC3'),
								'AUI_SYSPROC4'	=> $this->input->post('AUI_SYSPROC4'),
								'AUI_STAT'		=> $AUI_STAT,
								'AUI_STAT1'		=> $this->input->post('AUI_STAT1'));
			$this->m_audit->add($insAudit);			
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_project/c_4uD1NT/');
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function update()
	{
		$this->load->model('m_project/m_audit/m_audit', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$AUI_NUM	= $_GET['id'];
			$AUI_NUM	= $this->url_encryption_helper->decode_url($AUI_NUM);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Dokumen';
			$data['h3_title'] 		= 'audit internal';
			$data['main_view'] 		= 'v_project/v_audit/v_audit_form';
			$data['form_action1']	= site_url('c_project/c_4uD1NT/update_process1');
			$data['form_action2']	= site_url('c_project/c_4uD1NT/update_process2');
			$data['form_action3']	= site_url('c_project/c_4uD1NT/update_process3');
			$data['form_action4']	= site_url('c_project/c_4uD1NT/update_process4');
			$data['backURL'] 		= site_url('c_project/c_4uD1NT/');
			$data["MenuCode"] 		= 'MN374';
			
			$getDoc = $this->m_audit->get_dok_by_code($AUI_NUM)->row();
			
			$data['default']['AUI_NUM']			= $getDoc->AUI_NUM;
			$data['default']['AUI_CODE']		= $getDoc->AUI_CODE;
			$data['default']['PRJCODE']			= $getDoc->PRJCODE;
			$data['default']['PRJNAME']			= $getDoc->PRJNAME; 
			$data['default']['AUI_STEP']		= $getDoc->AUI_STEP;
			$data['default']['AUI_ORDNO']		= $getDoc->AUI_ORDNO;
			$data['default']['AUI_INIT']		= $getDoc->AUI_INIT;
			$data['default']['AUI_DEPT']		= $getDoc->AUI_DEPT;
			$data['default']['AUI_SUBJEK']		= $getDoc->AUI_SUBJEK;
			$data['default']['AUI_LOC']			= $getDoc->AUI_LOC;
			$data['default']['AUI_CC']			= $getDoc->AUI_CC;
			$data['default']['AUI_DATE']		= $getDoc->AUI_DATE;
			$data['default']['AUI_TARGETD']		= $getDoc->AUI_TARGETD;
			$data['default']['AUI_DATE_NCR']	= $getDoc->AUI_DATE_NCR;
			$data['default']['AUI_AUDITOR']		= $getDoc->AUI_AUDITOR;
			$data['default']['AUI_REFDOC']		= $getDoc->AUI_REFDOC;
			$data['default']['AUI_PROBLDESC']	= $getDoc->AUI_PROBLDESC;
			$data['default']['AUI_KLAUS1']		= $getDoc->AUI_KLAUS1;
			$data['default']['AUI_KLAUS2']		= $getDoc->AUI_KLAUS2;
			$data['default']['AUI_KLAUS3']		= $getDoc->AUI_KLAUS3;
			$data['default']['AUI_KLAUS4']		= $getDoc->AUI_KLAUS4;
			$data['default']['AUI_TYPE']		= $getDoc->AUI_TYPE;
			$data['default']['AUI_SCOPE1']		= $getDoc->AUI_SCOPE1;
			$data['default']['AUI_SCOPE2']		= $getDoc->AUI_SCOPE2;
			$data['default']['AUI_SCOPE3']		= $getDoc->AUI_SCOPE3;
			$data['default']['AUI_SYSPROC1']	= $getDoc->AUI_SYSPROC1;
			$data['default']['AUI_SYSPROC2']	= $getDoc->AUI_SYSPROC2;
			$data['default']['AUI_SYSPROC3']	= $getDoc->AUI_SYSPROC3;
			$data['default']['AUI_SYSPROC4']	= $getDoc->AUI_SYSPROC4;
			$data['default']['AUI_CAUSE']		= $getDoc->AUI_CAUSE;
			$data['default']['AUI_CORACT']		= $getDoc->AUI_CORACT;
			$data['default']['AUI_CORSTEP']		= $getDoc->AUI_CORSTEP;
			$data['default']['AUI_PREVENT']		= $getDoc->AUI_PREVENT;
			$data['default']['AUI_FINISHP']		= $getDoc->AUI_FINISHP;
			$data['default']['AUI_EVIDEN']		= $getDoc->AUI_EVIDEN;
			$data['default']['AUI_REVIEWD']		= $getDoc->AUI_REVIEWD;
			$data['default']['AUI_CONCL']		= $getDoc->AUI_CONCL;
			$data['default']['AUI_NCRNO']		= $getDoc->AUI_NCRNO;
			$data['default']['AUI_NOTESREV']	= $getDoc->AUI_NOTESREV;
			$data['default']['AUI_NCRD']		= $getDoc->AUI_NCRD;
			$data['default']['AUI_STAT1']		= $getDoc->AUI_STAT1;
			$data['default']['AUI_STAT2']		= $getDoc->AUI_STAT2;
			$data['default']['AUI_STAT3']		= $getDoc->AUI_STAT3;
			$data['default']['AUI_STAT']		= $getDoc->AUI_STAT;
			$data['default']['TYPE']			= 1;
			
			// CATATAN
			$data['default']['AUN_NUM']			= '';
			$data['default']['AUN_CODE']		= '';
			$data['default']['AUN_DATE']		= date('m/d/Y');
			$data['default']['AUN_DEPT']		= '';
			$data['default']['AUN_AUDITEE']		= '';
			$data['default']['AUN_ACUAN']		= '';
			$data['default']['AUN_AUDITOR']		= '';
			$data['default']['AUN_STAT']		= '';
			$data['default']['AUN_DESC']		= '';
			
			$this->load->view('v_project/v_audit/v_audit_form', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function update_process1()
	{
		$this->load->model('m_project/m_audit/m_audit', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$EMP_INIT	= '';
		$sqlINIT	= "SELECT EMP_INIT FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
		$resINIT	= $this->db->query($sqlINIT)->result();
		foreach($resINIT as $rowINIT):
			$EMP_INIT	= $rowINIT->EMP_INIT;
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{			
			$AUI_NUM	= $this->input->post('AUI_NUM');
			$AUI_CODE	= $this->input->post('AUI_CODE');
			
			$PRJCODE	= $this->input->post('PRJCODE');
			$PRJNAME	= '';
			$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$resPRJ		= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJNAME	= $rowPRJ->PRJNAME;
			endforeach;
			
			$AUI_SUBJEK1	= $this->input->post('AUI_SUBJEK');
			$AUI_SUBJ		= '';
			$COLL_SUBJEK	= '';
			$selStep		= 0;
			
			if($AUI_SUBJEK1 != '')
			{
				$refStep	= 0;					
				foreach ($AUI_SUBJEK1 as $AUI_SUBJ)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLL_SUBJEK	= "$AUI_SUBJ";
					}
					else
					{
						$COLL_SUBJEK	= "$COLL_SUBJEK;$AUI_SUBJ";
					}
				}
			}
			
			$AUI_AUDITOR1	= $this->input->post('AUI_AUDITOR');
			$AUI_AUDITOR	= '';
			$COLL_AUDITOR	= '';
			$COLL_AUINIT	= '';
			$selStep		= 0;
			if($AUI_AUDITOR1 != '')
			{
				$refStep	= 0;					
				foreach ($AUI_AUDITOR1 as $AUI_AUDITOR)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLL_AUDITOR	= "$AUI_AUDITOR";
						$EMP_INIT		= '';
						$sqlINIT		= "SELECT EMP_INIT FROM tbl_employee WHERE Emp_ID = '$AUI_AUDITOR'";
						$resINIT		= $this->db->query($sqlINIT)->result();
						foreach($resINIT as $rowINIT):
							$EMP_INIT	= $rowINIT->EMP_INIT;
						endforeach;
						$COLL_AUINIT	= $EMP_INIT;
					}
					else
					{
						$COLL_AUDITOR	= "$COLL_AUDITOR;$AUI_AUDITOR";
						$EMP_INIT		= '';
						$sqlINIT		= "SELECT EMP_INIT FROM tbl_employee WHERE Emp_ID = '$AUI_AUDITOR'";
						$resINIT		= $this->db->query($sqlINIT)->result();
						foreach($resINIT as $rowINIT):
							$EMP_INIT	= $rowINIT->EMP_INIT;
						endforeach;
						$COLL_AUINIT	= "$COLL_AUINIT,$EMP_INIT";
					}
				}
			}
			
			$AUI_CC1	= $this->input->post('AUI_CC');
			$AUI_CC		= '';
			$COLL_CC	= '';
			$selStep	= 0;
			if($AUI_CC1 != '')
			{
				$refStep	= 0;					
				foreach ($AUI_CC1 as $AUI_CC)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLL_CC	= "$AUI_CC";
					}
					else
					{
						$COLL_CC	= "$COLL_CC;$AUI_CC";
					}
				}
			}
			
			$REFDOC1	= $this->input->post('AUI_REFDOC');
			$REFDOC		= '';
			$COLL_REFD	= '';
			$selStep	= 0;
			if($REFDOC1 != '')
			{
				$refStep	= 0;					
				foreach ($REFDOC1 as $REFDOC)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLL_REFD	= "$REFDOC";
					}
					else
					{
						$COLL_REFD	= "$COLL_REFD;$REFDOC";
					}
				}
			}
			
			$AUI_STAT1	= $this->input->post('AUI_STAT1');
			if($AUI_STAT1 == 1)
				$AUI_STAT	= 1;
			
			$updAudit 	= array('AUI_CODE'		=> $AUI_CODE,
								'PRJCODE'		=> $PRJCODE,
								'PRJNAME'		=> $PRJNAME, 
								'AUI_STEP'		=> $this->input->post('AUI_STEP'),
								'AUI_ORDNO'		=> $this->input->post('AUI_ORDNO'),
								'AUI_INIT'		=> $COLL_AUINIT,
								'AUI_DEPT'		=> $this->input->post('AUI_DEPT'),
								'AUI_SUBJEK'	=> $COLL_SUBJEK,
								'AUI_LOC'		=> $this->input->post('AUI_LOC'),
								'AUI_DATE'		=> date('Y-m-d', strtotime($this->input->post('AUI_DATE'))),
								'AUI_TARGETD'	=> date('Y-m-d', strtotime($this->input->post('AUI_TARGETD'))),
								'AUI_DATE_NCR'	=> date('Y-m-d', strtotime($this->input->post('AUI_DATE_NCR'))),
								'AUI_FINISHP'	=> date('Y-m-d', strtotime($this->input->post('AUI_TARGETD'))),
								'AUI_NCRD'		=> date('Y-m-d', strtotime($this->input->post('AUI_TARGETD'))),
								'AUI_REVIEWD'	=> date('Y-m-d', strtotime($this->input->post('AUI_TARGETD'))),
								'AUI_AUDITOR'	=> $COLL_AUDITOR,
								'AUI_CC'		=> $COLL_CC,
								'AUI_REFDOC'	=> $COLL_REFD,
								'AUI_PROBLDESC'	=> $this->input->post('AUI_PROBLDESC'),
								'AUI_KLAUS1'	=> $this->input->post('AUI_KLAUS1'),
								'AUI_KLAUS2'	=> $this->input->post('AUI_KLAUS2'),
								'AUI_KLAUS3'	=> $this->input->post('AUI_KLAUS3'),
								'AUI_KLAUS4'	=> $this->input->post('AUI_KLAUS4'),
								'AUI_TYPE'		=> $this->input->post('AUI_TYPE'),
								'AUI_SCOPE1'	=> $this->input->post('AUI_SCOPE1'),
								'AUI_SCOPE2'	=> $this->input->post('AUI_SCOPE2'),
								'AUI_SCOPE3'	=> $this->input->post('AUI_SCOPE3'),
								'AUI_SYSPROC1'	=> $this->input->post('AUI_SYSPROC1'),
								'AUI_SYSPROC2'	=> $this->input->post('AUI_SYSPROC2'),
								'AUI_SYSPROC3'	=> $this->input->post('AUI_SYSPROC3'),
								'AUI_SYSPROC4'	=> $this->input->post('AUI_SYSPROC4'),
								'AUI_CAUSE'		=> $this->input->post('AUI_CAUSE'),
								'AUI_STAT'		=> $AUI_STAT,
								'AUI_STAT1'		=> $AUI_STAT1);
			$this->m_audit->update($AUI_NUM, $updAudit);
			
			redirect('c_project/c_4uD1NT/');
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function update_process2()
	{
		$this->load->model('m_project/m_audit/m_audit', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{			
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			$AUI_NUM	= $this->input->post('AUI_NUM');
			
			$PRJCODE	= $this->input->post('PRJCODE');
			$PRJNAME	= '';
			$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$resPRJ		= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJNAME	= $rowPRJ->PRJNAME;
			endforeach;
			
			$AUI_CODE		= $this->input->post('AUI_CODE');
			$AUI_FINISHP	= $this->input->post('AUI_FINISHP');
			$AUI_FINISHP	= date('Y-m-d', strtotime($AUI_FINISHP));
			$AUI_REVIEWD	= $this->input->post('AUI_REVIEWD');
			$AUI_REVIEWD	= date('Y-m-d', strtotime($AUI_REVIEWD));
			$AUI_NCRD		= $this->input->post('AUI_NCRD');
			$AUI_NCRD		= date('Y-m-d', strtotime($AUI_NCRD));
			
			$AUI_STAT2	= $this->input->post('AUI_STAT2');
			$AUI_STAT3	= 0;
			if($AUI_STAT2 == 1)
			{
				$AUI_STAT	= 2;
				$AUI_STAT3	= 0;
			}
			
			$updAudit 	= array('AUI_REVIEWD'	=> $AUI_REVIEWD,
								'AUI_CAUSE'		=> $this->input->post('AUI_CAUSE'),
								'AUI_CORACT'	=> $this->input->post('AUI_CORACT'),
								'AUI_CORSTEP'	=> $this->input->post('AUI_CORSTEP'),
								'AUI_PREVENT'	=> $this->input->post('AUI_PREVENT'),
								'AUI_FINISHP'	=> $AUI_FINISHP,
								'AUI_EVIDEN'	=> $this->input->post('AUI_EVIDEN'),
								'AUI_REVIEWD'	=> $AUI_REVIEWD,
								'AUI_NCRD'		=> $AUI_NCRD,
								'AUI_STAT'		=> $AUI_STAT,
								'AUI_STAT2'		=> $AUI_STAT2,
								'AUI_STAT3'		=> $AUI_STAT3);
			$this->m_audit->update($AUI_NUM, $updAudit);
			
			redirect('c_project/c_4uD1NT/U5r/');
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function update_process3()
	{
		$this->load->model('m_project/m_audit/m_audit', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{			
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			$AUI_NUM	= $this->input->post('AUI_NUM');
			
			$PRJCODE	= $this->input->post('PRJCODE');
			$PRJNAME	= '';
			$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$resPRJ		= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJNAME	= $rowPRJ->PRJNAME;
			endforeach;
			
			$AUI_CODE		= $this->input->post('AUI_CODE');
			$AUI_REVIEWD	= $this->input->post('AUI_REVIEWD');
			$AUI_REVIEWD	= date('Y-m-d', strtotime($AUI_REVIEWD));
			$AUI_NCRD		= $this->input->post('AUI_NCRD');
			$AUI_NCRD		= date('Y-m-d', strtotime($AUI_NCRD));
			$AUI_NOTESREV	= $this->input->post('AUI_NOTESREV');
			
			$AUI_STAT2		= $this->input->post('AUI_STAT2');
			$AUI_STAT3		= $this->input->post('AUI_STAT3');
			$AUI_STAT		= $this->input->post('AUI_STAT');
			if($AUI_STAT3 == 1)
			{
				$AUI_STAT	= 3;
				$AUI_CONCL	= $this->input->post('AUI_CONCL');
				if($AUI_CONCL == 2)
				{
					$AUI_STAT2	= 0;
					$AUI_STAT	= 4;
				}
			}
			
			$updAudit 	= array('AUI_REVIEWD'	=> $AUI_REVIEWD,
								'AUI_CONCL'		=> $this->input->post('AUI_CONCL'),
								'AUI_NCRNO'		=> $this->input->post('AUI_NCRNO'),
								'AUI_NOTESREV'	=> $this->input->post('AUI_NOTESREV'),
								'AUI_NCRD'		=> $AUI_NCRD,
								'AUI_STAT'		=> $AUI_STAT,
								'AUI_STAT2'		=> $AUI_STAT2,
								'AUI_STAT3'		=> $AUI_STAT3);
			$this->m_audit->update($AUI_NUM, $updAudit);
			
			redirect('c_project/c_4uD1NT/');
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function add_process4()
	{ 
		$this->load->model('m_project/m_audit/m_audit', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			$AUN_CODE	= $this->input->post('AUN_CODE');
			$PRJCODE	= $this->input->post('PRJCODEN');
			$PRJNAME	= '';
			$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$resPRJ		= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJNAME	= $rowPRJ->PRJNAME;
			endforeach;
			
			$AUN_DATE	= date('Y-m-d', strtotime($this->input->post('AUN_DATE')));
			$AUNAUDITOR	= $this->input->post('AUN_AUDITOR');
			$AUDITOR	= '';
			$COLLAUDITOR= '';
			$selStep		= 0;
			
			if($AUNAUDITOR != '')
			{
				$refStep	= 0;					
				foreach ($AUNAUDITOR as $AUDITOR)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLLAUDITOR	= "$AUDITOR";
					}
					else
					{
						$COLLAUDITOR	= "$COLLAUDITOR;$AUDITOR";
					}
				}
			}
			
			$AUN_AUDITEE1	= $this->input->post('AUN_AUDITEE');
			$AUDITEE		= '';
			$COLLAUDITEE	= '';
			$selStep		= 0;
			
			if($AUN_AUDITEE1 != '')
			{
				$refStep	= 0;					
				foreach ($AUN_AUDITEE1 as $AUDITEE)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLLAUDITEE	= "$AUDITEE";
					}
					else
					{
						$COLLAUDITEE	= "$COLLAUDITEE;$AUDITEE";
					}
				}
			}
			
			$AUN_STAT	= $this->input->post('AUN_STAT');
			
			$insNoteAud	= array('AUN_NUM'		=> $this->input->post('AUN_NUM'),
								'AUN_CODE'		=> $AUN_CODE,
								'PRJCODE'		=> $PRJCODE,
								'PRJNAME'		=> $PRJNAME, 
								'AUN_DATE'		=> $AUN_DATE,
								'AUN_ACUAN'		=> $this->input->post('AUN_ACUAN'),
								'AUN_DEPT'		=> $this->input->post('AUN_DEPT'),
								'AUN_AUDITOR'	=> $COLLAUDITOR,
								'AUN_AUDITEE'	=> $COLLAUDITEE,
								'AUN_STAT'		=> $AUN_STAT,
								'AUN_DESC'		=> $this->input->post('AUN_DESC'));
			$this->m_audit->addNAud($insNoteAud);	
			
			foreach($_POST['data'] as $d)
			{
				$d['AUN_CODE']	= $AUN_CODE;
				$AUN_NUM		= $d['AUN_NUM'];
				$AUN_NOTES		= $d['AUN_NOTES'];
				$FOC_1			= 0;
				$FOC_2			= 0;
				$FOC_3			= 0;
				$PSYS_1			= 0;
				$PSYS_2			= 0;
				$PSYS_3			= 0;
				$PSYS_4			= 0;
				$TEM_1			= 0;
				$TEM_2			= 0;
				$TEM_3			= 0;
				
				if (!empty($d['FOC_1'])){ $FOC_1 = $d['FOC_1']; }
				if (!empty($d['FOC_2'])){ $FOC_2 = $d['FOC_2']; }
				if (!empty($d['FOC_3'])){ $FOC_3 = $d['FOC_3']; }
				if (!empty($d['PSYS_1'])){ $PSYS_1 = $d['PSYS_1']; }
				if (!empty($d['PSYS_2'])){ $PSYS_2 = $d['PSYS_2']; }
				if (!empty($d['PSYS_3'])){ $PSYS_3 = $d['PSYS_3']; }
				if (!empty($d['PSYS_4'])){ $PSYS_4 = $d['PSYS_4']; }
				if (!empty($d['TEM_1'])){ $TEM_1 = $d['TEM_1']; }
				if (!empty($d['TEM_2'])){ $TEM_2 = $d['TEM_2']; }
				if (!empty($d['TEM_3'])){ $TEM_3 = $d['TEM_3']; }
				
				$d['FOC_1']			= $FOC_1;
				$d['FOC_2']			= $FOC_2;
				$d['FOC_3']			= $FOC_3;
				$d['PSYS_1']		= $PSYS_1;
				$d['PSYS_2']		= $PSYS_2;
				$d['PSYS_3']		= $PSYS_3;
				$d['PSYS_4']		= $PSYS_4;
				$d['TEM_1']			= $TEM_1;
				$d['TEM_2']			= $TEM_2;
				$d['TEM_3']			= $TEM_3;
				
				$this->db->insert('tbl_auditn_d',$d);
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_project/c_4uD1NT/');
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function update_nts()
	{
		$this->load->model('m_project/m_audit/m_audit', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$AUN_NUM	= $_GET['id'];
			$AUN_NUM	= $this->url_encryption_helper->decode_url($AUN_NUM);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Dokumen';
			$data['h3_title'] 		= 'audit internal';
			$data['form_action1']	= site_url('c_project/c_4uD1NT/update_process1');
			$data['form_action2']	= site_url('c_project/c_4uD1NT/update_process2');
			$data['form_action3']	= site_url('c_project/c_4uD1NT/update_process3');
			$data['form_action4']	= site_url('c_project/c_4uD1NT/update_process4');
			$data['backURL'] 		= site_url('c_project/c_4uD1NT/');
			$data["MenuCode"] 		= 'MN374';
			
			$data['default']['AUI_NUM']			= '';	
			$data['default']['AUI_CODE']		= '135';
			$data['default']['AUI_CODE1']		= '';
			$data['default']['AUI_STEP']		= '';
			$data['default']['AUI_ORDNO']		= '';
			$data['default']['AUI_INIT']		= '';
			$data['default']['AUI_DEPT']		= '';
			$data['default']['AUI_SUBJEK']		= '';
			$data['default']['AUI_LOC']			= '';
			$data['default']['AUI_CC']			= '';
			$data['default']['AUI_DATE']		= date('m/d/Y');
			$data['default']['AUI_DATE_NCR']	= date('m/d/Y');
			$data['default']['AUI_TARGETD']		= date('m/d/Y');
			$data['default']['AUI_AUDITOR']		= '';
			$data['default']['AUI_REFDOC']		= '';
			$data['default']['AUI_PROBLDESC']	= '';
			$data['default']['AUI_KLAUS1']		= '';
			$data['default']['AUI_KLAUS2']		= '';
			$data['default']['AUI_KLAUS3']		= '';
			$data['default']['AUI_KLAUS4']		= '';
			$data['default']['AUI_TYPE']		= '';
			$data['default']['AUI_SCOPE1']		= '';
			$data['default']['AUI_SCOPE2']		= '';
			$data['default']['AUI_SCOPE3']		= '';
			$data['default']['AUI_SYSPROC1']	= '';
			$data['default']['AUI_SYSPROC2']	= '';
			$data['default']['AUI_SYSPROC3']	= '';
			$data['default']['AUI_SYSPROC4']	= '';
			$data['default']['AUI_CAUSE']		= '';
			$data['default']['AUI_CORACT']		= '';
			$data['default']['AUI_CORSTEP']		= '';
			$data['default']['AUI_PREVENT']		= '';
			$data['default']['AUI_FINISHP']		= date('m/d/Y');
			$data['default']['AUI_EVIDEN']		= '';
			$data['default']['AUI_REVIEWD']		= date('m/d/Y');
			$data['default']['AUI_CONCL']		= '';
			$data['default']['AUI_NCRNO']		= '';
			$data['default']['AUI_NCRD']		= date('m/d/Y');
			$data['default']['AUI_NOTESREV']	= '';
			$data['default']['AUI_AUDITOR1']	= '';
			$data['default']['AUI_AUDITOR2']	= '';
			$data['default']['AUI_SIGN1']		= '';
			$data['default']['AUI_SIGN2']		= '';
			$data['default']['AUI_STAT1']		= 0;
			$data['default']['AUI_STAT2']		= 0;
			$data['default']['AUI_STAT3']		= 0;
			$data['default']['AUI_STAT']		= 0;
	
			$getDoc = $this->m_audit->get_dok_by_code4($AUN_NUM)->row();
			
			$data['default']['AUN_NUM']			= $getDoc->AUN_NUM;
			$data['default']['AUN_CODE']		= $getDoc->AUN_CODE;
			$data['default']['PRJCODE']			= $getDoc->PRJCODE;
			$data['default']['PRJNAME']			= $getDoc->PRJNAME; 
			$data['default']['AUN_DATE']		= $getDoc->AUN_DATE;
			$data['default']['AUN_DEPT']		= $getDoc->AUN_DEPT;
			$data['default']['AUN_AUDITEE']		= $getDoc->AUN_AUDITEE;
			$data['default']['AUN_ACUAN']		= $getDoc->AUN_ACUAN;
			$data['default']['AUN_AUDITOR']		= $getDoc->AUN_AUDITOR;
			$data['default']['AUN_STAT']		= $getDoc->AUN_STAT;
			$data['default']['AUN_DESC']		= $getDoc->AUN_DESC;
			$data['default']['TYPE']			= 2;
			
			$this->load->view('v_project/v_audit/v_audit_form', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function update_process4()
	{
		$this->load->model('m_project/m_audit/m_audit', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{
			$AUN_NUM	= $this->input->post('AUN_NUM');
			$AUN_CODE	= $this->input->post('AUN_CODE');
			$PRJCODE	= $this->input->post('PRJCODEN');
			$PRJNAME	= '';
			$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$resPRJ		= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJNAME	= $rowPRJ->PRJNAME;
			endforeach;
			
			$AUN_DATE	= date('Y-m-d', strtotime($this->input->post('AUN_DATE')));
			$AUNAUDITOR	= $this->input->post('AUN_AUDITOR');
			$AUDITOR	= '';
			$COLLAUDITOR= '';
			$selStep		= 0;
			
			if($AUNAUDITOR != '')
			{
				$refStep	= 0;					
				foreach ($AUNAUDITOR as $AUDITOR)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLLAUDITOR	= "$AUDITOR";
					}
					else
					{
						$COLLAUDITOR	= "$COLLAUDITOR;$AUDITOR";
					}
				}
			}
			
			$AUN_AUDITEE1	= $this->input->post('AUN_AUDITEE');
			$AUDITEE		= '';
			$COLLAUDITEE	= '';
			$selStep		= 0;
			
			if($AUN_AUDITEE1 != '')
			{
				$refStep	= 0;					
				foreach ($AUN_AUDITEE1 as $AUDITEE)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLLAUDITEE	= "$AUDITEE";
					}
					else
					{
						$COLLAUDITEE	= "$COLLAUDITEE;$AUDITEE";
					}
				}
			}
			
			$AUN_STAT	= $this->input->post('AUN_STAT');
			
			$updNoteAud	= array('AUN_CODE'		=> $AUN_CODE,
								'PRJCODE'		=> $PRJCODE,
								'PRJNAME'		=> $PRJNAME, 
								'AUN_DATE'		=> $AUN_DATE,
								'AUN_ACUAN'		=> $this->input->post('AUN_ACUAN'),
								'AUN_DEPT'		=> $this->input->post('AUN_DEPT'),
								'AUN_AUDITOR'	=> $COLLAUDITOR,
								'AUN_AUDITEE'	=> $COLLAUDITEE,
								'AUN_STAT'		=> $AUN_STAT,
								'AUN_DESC'		=> $this->input->post('AUN_DESC'));
			$this->m_audit->updateNAud($AUN_NUM, $updNoteAud);
			
			$this->m_audit->delDetNAud($AUN_NUM);	
			
			foreach($_POST['data'] as $d)
			{
				$AUN_NUM		= $d['AUN_NUM'];
				$AUN_CODE		= $d['AUN_CODE'];
				$AUN_NOTES		= $d['AUN_NOTES'];
				$FOC_1			= 0;
				$FOC_2			= 0;
				$FOC_3			= 0;
				$PSYS_1			= 0;
				$PSYS_2			= 0;
				$PSYS_3			= 0;
				$PSYS_4			= 0;
				$TEM_1			= 0;
				$TEM_2			= 0;
				$TEM_3			= 0;
				
				if (!empty($d['FOC_1'])){ $FOC_1 = $d['FOC_1']; }
				if (!empty($d['FOC_2'])){ $FOC_2 = $d['FOC_2']; }
				if (!empty($d['FOC_3'])){ $FOC_3 = $d['FOC_3']; }
				if (!empty($d['PSYS_1'])){ $PSYS_1 = $d['PSYS_1']; }
				if (!empty($d['PSYS_2'])){ $PSYS_2 = $d['PSYS_2']; }
				if (!empty($d['PSYS_3'])){ $PSYS_3 = $d['PSYS_3']; }
				if (!empty($d['PSYS_4'])){ $PSYS_4 = $d['PSYS_4']; }
				if (!empty($d['TEM_1'])){ $TEM_1 = $d['TEM_1']; }
				if (!empty($d['TEM_2'])){ $TEM_2 = $d['TEM_2']; }
				if (!empty($d['TEM_3'])){ $TEM_3 = $d['TEM_3']; }
				
				$d['FOC_1']			= $FOC_1;
				$d['FOC_2']			= $FOC_2;
				$d['FOC_3']			= $FOC_3;
				$d['PSYS_1']		= $PSYS_1;
				$d['PSYS_2']		= $PSYS_2;
				$d['PSYS_3']		= $PSYS_3;
				$d['PSYS_4']		= $PSYS_4;
				$d['TEM_1']			= $TEM_1;
				$d['TEM_2']			= $TEM_2;
				$d['TEM_3']			= $TEM_3;
				
				$this->db->insert('tbl_auditn_d',$d);
			}
			redirect('c_project/c_4uD1NT/');
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function prN7_d0c()
	{
		$this->load->model('m_project/m_audit/m_audit', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$COLLDATA	= $_GET['id'];
		$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
		$EXTRACTCOL	= explode("~", $COLLDATA);
		$PRJCODE	= $EXTRACTCOL[0];
		$AUI_NUM	= $EXTRACTCOL[1];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['PRJCODE'] 	= $PRJCODE;
			$data['AUI_NUM'] 	= $AUI_NUM;
			$data['title'] 		= $appName;
			
			$getDoc = $this->m_audit->get_dok_by_code($AUI_NUM)->row();
			
			$data['AUI_CODE']		= $getDoc->AUI_CODE;
			$data['PRJCODE']		= $getDoc->PRJCODE;
			$data['PRJNAME']		= $getDoc->PRJNAME; 
			$data['AUI_STEP']		= $getDoc->AUI_STEP;
			$data['AUI_ORDNO']		= $getDoc->AUI_ORDNO;
			$data['AUI_INIT']		= $getDoc->AUI_INIT;
			$data['AUI_DEPT']		= $getDoc->AUI_DEPT;
			$data['AUI_SUBJEK']		= $getDoc->AUI_SUBJEK;
			$data['AUI_LOC']		= $getDoc->AUI_LOC;
			$data['AUI_DATE']		= $getDoc->AUI_DATE;
			$data['AUI_TARGETD']	= $getDoc->AUI_TARGETD;
			$data['AUI_DATE_NCR']	= $getDoc->AUI_DATE_NCR;
			$data['AUI_AUDITOR']	= $getDoc->AUI_AUDITOR;
			$data['AUI_REFDOC']		= $getDoc->AUI_REFDOC;
			$data['AUI_PROBLDESC']	= $getDoc->AUI_PROBLDESC;
			$data['AUI_KLAUS1']		= $getDoc->AUI_KLAUS1;
			$data['AUI_KLAUS2']		= $getDoc->AUI_KLAUS2;
			$data['AUI_KLAUS3']		= $getDoc->AUI_KLAUS3;
			$data['AUI_KLAUS4']		= $getDoc->AUI_KLAUS4;
			$data['AUI_TYPE']		= $getDoc->AUI_TYPE;
			$data['AUI_SCOPE1']		= $getDoc->AUI_SCOPE1;
			$data['AUI_SCOPE2']		= $getDoc->AUI_SCOPE2;
			$data['AUI_SCOPE3']		= $getDoc->AUI_SCOPE3;
			$data['AUI_SYSPROC1']	= $getDoc->AUI_SYSPROC1;
			$data['AUI_SYSPROC2']	= $getDoc->AUI_SYSPROC2;
			$data['AUI_SYSPROC3']	= $getDoc->AUI_SYSPROC3;
			$data['AUI_SYSPROC4']	= $getDoc->AUI_SYSPROC4;
			$data['AUI_CAUSE']		= $getDoc->AUI_CAUSE;
			$data['AUI_CORACT']		= $getDoc->AUI_CORACT;
			$data['AUI_CORSTEP']	= $getDoc->AUI_CORSTEP;
			$data['AUI_PREVENT']	= $getDoc->AUI_PREVENT;
			$data['AUI_FINISHP']	= $getDoc->AUI_FINISHP;
			$data['AUI_EVIDEN']		= $getDoc->AUI_EVIDEN;
			$data['AUI_REVIEWD']	= $getDoc->AUI_REVIEWD;
			$data['AUI_CONCL']		= $getDoc->AUI_CONCL;
			$data['AUI_NCRNO']		= $getDoc->AUI_NCRNO;
			$data['AUI_NOTESREV']	= $getDoc->AUI_NOTESREV;
			$data['AUI_NCRD']		= $getDoc->AUI_NCRD;
			
			$this->load->view('v_project/v_audit/v_audit_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prN7_d0cNts()
	{
		$this->load->model('m_project/m_audit/m_audit', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$COLLDATA	= $_GET['id'];
		$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
		$EXTRACTCOL	= explode("~", $COLLDATA);
		$PRJCODE	= $EXTRACTCOL[0];
		$AUI_NUM	= $EXTRACTCOL[1];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['PRJCODE'] 	= $PRJCODE;
			$data['AUI_NUM'] 	= $AUI_NUM;
			$data['title'] 		= $appName;
			
			$getDoc = $this->m_audit->get_dok_by_code($AUI_NUM)->row();
			
			$data['AUI_CODE']		= $getDoc->AUI_CODE;
			$data['PRJCODE']		= $getDoc->PRJCODE;
			$data['PRJNAME']		= $getDoc->PRJNAME; 
			$data['AUI_STEP']		= $getDoc->AUI_STEP;
			$data['AUI_ORDNO']		= $getDoc->AUI_ORDNO;
			$data['AUI_INIT']		= $getDoc->AUI_INIT;
			$data['AUI_DEPT']		= $getDoc->AUI_DEPT;
			$data['AUI_SUBJEK']		= $getDoc->AUI_SUBJEK;
			$data['AUI_LOC']		= $getDoc->AUI_LOC;
			$data['AUI_DATE']		= $getDoc->AUI_DATE;
			$data['AUI_TARGETD']	= $getDoc->AUI_TARGETD;
			$data['AUI_DATE_NCR']	= $getDoc->AUI_DATE_NCR;
			$data['AUI_AUDITOR']	= $getDoc->AUI_AUDITOR;
			$data['AUI_REFDOC']		= $getDoc->AUI_REFDOC;
			$data['AUI_PROBLDESC']	= $getDoc->AUI_PROBLDESC;
			$data['AUI_KLAUS1']		= $getDoc->AUI_KLAUS1;
			$data['AUI_KLAUS2']		= $getDoc->AUI_KLAUS2;
			$data['AUI_KLAUS3']		= $getDoc->AUI_KLAUS3;
			$data['AUI_KLAUS4']		= $getDoc->AUI_KLAUS4;
			$data['AUI_TYPE']		= $getDoc->AUI_TYPE;
			$data['AUI_SCOPE1']		= $getDoc->AUI_SCOPE1;
			$data['AUI_SCOPE2']		= $getDoc->AUI_SCOPE2;
			$data['AUI_SCOPE3']		= $getDoc->AUI_SCOPE3;
			$data['AUI_SYSPROC1']	= $getDoc->AUI_SYSPROC1;
			$data['AUI_SYSPROC2']	= $getDoc->AUI_SYSPROC2;
			$data['AUI_SYSPROC3']	= $getDoc->AUI_SYSPROC3;
			$data['AUI_SYSPROC4']	= $getDoc->AUI_SYSPROC4;
			$data['AUI_CAUSE']		= $getDoc->AUI_CAUSE;
			$data['AUI_CORACT']		= $getDoc->AUI_CORACT;
			$data['AUI_CORSTEP']	= $getDoc->AUI_CORSTEP;
			$data['AUI_PREVENT']	= $getDoc->AUI_PREVENT;
			$data['AUI_FINISHP']	= $getDoc->AUI_FINISHP;
			$data['AUI_EVIDEN']		= $getDoc->AUI_EVIDEN;
			$data['AUI_REVIEWD']	= $getDoc->AUI_REVIEWD;
			$data['AUI_CONCL']		= $getDoc->AUI_CONCL;
			$data['AUI_NCRNO']		= $getDoc->AUI_NCRNO;
			$data['AUI_NOTESREV']	= $getDoc->AUI_NOTESREV;
			$data['AUI_NCRD']		= $getDoc->AUI_NCRD;
			
			$this->load->view('v_project/v_audit/v_audit_print_nts', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function U5r()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_4uD1NT/U5r2/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function U5r2($offset=0)
	{
		$this->load->model('m_project/m_audit/m_audit', '', TRUE);
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			$EmpID 		= $this->session->userdata('Emp_ID');
					
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Audit';
			$data['h3_title']		= 'Internal';
			$data['main_view'] 		= 'v_project/v_audit/v_audit';
			$data["MenuCode"] 		= 'MN374';
			
			$num_rows 				= $this->m_audit->count_allDocUsr($DefEmp_ID);
			$data["recordcount"] 	= $num_rows;
	 
			$data['vwAudit'] 		= $this->m_audit->get_allDocUsr($DefEmp_ID)->result();
			
			$this->load->view('v_project/v_audit/v_audit', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}

	function uploadEVD()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;

			$ID			= $_GET['id'];
			$AUI_NUM	= $this->url_encryption_helper->decode_url($ID);
			$data['AUI_NUM'] = $AUI_NUM;
					
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Upload Eviden/Bukti Closing';
			$data['main_view'] 		= 'v_project/v_audit/v_audit';
			$data['frmAction']		= site_url('c_project/c_4uD1NT/uploadEVD_process/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['error']			= "";
			$data['msgUpload']	 	= "";
			$data["MenuCode"] 		= 'MN374';	

			$this->load->view('v_project/v_audit/v_upload_eviden_form', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}

	function uploadEVD_process()
	{
		$this->load->model('m_project/m_audit/m_audit', '', TRUE);

		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$data['title'] 			= $appName;
			$data['h2_title']		= 'Upload Eviden/Bukti Closing';
			$data['main_view'] 		= 'v_project/v_audit/v_audit';
			$data['frmAction']		= site_url('c_project/c_4uD1NT/uploadEVD_process/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['error']			= "";
			$data['msgUpload']	 	= "";
			$data["MenuCode"] 		= 'MN374';	

			$config['upload_path']          = 'NCR_Upload/';
            $config['allowed_types']        = 'jpg|jpeg|png|gif';
            //$config['max_size']           = 100;
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('imagefile'))
            {
            	date_default_timezone_set("Asia/Bangkok");
	        	$AUI_NUM		= $this->input->post('AUI_NUM');
            	$data['error'] 		= $this->upload->display_errors();
            	$data['AUI_NUM']	= $AUI_NUM;
            	$this->load->view('v_project/v_audit/v_upload_eviden_form', $data);
            }
            else
            {
            	$AUI_NUM		= $this->input->post('AUI_NUM');
	        	$file_upload 	= $this->upload->data();
	        	$PICT_NAME		= $file_upload['file_name'];
	        	$PICT_DESC		= $this->input->post('PICT_DESC');
	        	$UPL_DATET		= date('Y-m-d H:i:s');
	        	$AUI_NEXT		= $this->input->post('AUI_NEXT');

            	$insUpload = array('AUI_NUM' => $AUI_NUM, 'PICT_NAME' => $PICT_NAME, 'PICT_DESC' => $PICT_DESC, 'UPL_DATET' => $UPL_DATET);
            	$this->m_audit->addUpload($insUpload);
            	if($AUI_NEXT == 1)
            	{
            		$data['file_upload'] = $this->upload->data();
            		$data['msgUpload']	 = "&nbsp;&nbsp;File ".$file_upload['file_name']." berhasil disimpan.";
            		$data['AUI_NUM']	 = $AUI_NUM;
            		$this->load->view('v_project/v_audit/v_upload_eviden_form', $data);
            	}
            	else
            	{
            		?> <script type="text/javascript">window.close();</script> <?php
            	}
            }
		}
		else
		{
			redirect('__I1y');
		}
	}

	function viewImage()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$ID					= $_GET['id'];
			$AUI_NUM			= $this->url_encryption_helper->decode_url($ID);
			$data['AUI_NUM'] 	= $AUI_NUM;
			$data['h2_title']	= 'Gallery Eviden/Bukti Closing';

			$this->load->view('v_project/v_audit/v_audit_pic_adm', $data);	
		}
		else
		{
			redirect('__I1y');
		}
	}
}