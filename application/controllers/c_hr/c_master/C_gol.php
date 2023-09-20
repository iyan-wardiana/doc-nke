<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 31 Oktober 2017
 * File Name	= C_gol.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_gol extends CI_Controller  
{
 	function index() // OK
	{
		$this->load->model('m_hr/m_master/m_gol', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_hr/c_master/c_gol/listgol/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function listgol() // OK
	{
		$this->load->model('m_hr/m_master/m_gol', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$appName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($appName);
			$EmpID 				= $this->session->userdata('Emp_ID');
				
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_hr/c_master/c_gol/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['MenuCode'] 	= 'MN326';
			
			$num_rows 			= $this->m_gol->count_all_gol();
			$data["countGol"] 	= $num_rows;		
			$data['vwGol'] 		= $this->m_gol->get_all_gol()->result();
			
			$this->load->view('v_hr/v_master/v_gol/entry_gol', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_hr/m_master/m_gol', '', TRUE);
		$this->load->model('m_hr/m_employee/m_employee', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_hr/c_master/c_gol/add_process');
			$data['backURL'] 		= site_url('c_hr/c_master/c_gol/?id='.$this->url_encryption_helper->encode_url($appName));
			$data["MenuCode"] 		= 'MN326';
			$MenuCode 				= 'MN326';
			$data['vwDocPatt'] 		= $this->m_employee->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_hr/v_master/v_gol/entry_gol_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
		
	function getCode($CHILDC) // OK
	{ 	
		$this->load->model('m_hr/m_master/m_gol', '', TRUE);
		$countCode 	= $this->m_gol->count_child_code($CHILDC);
		echo $countCode;
	}
		
	function getKoef($sendCode) // OK -- Update only
	{
		$splitCode 	= explode("~", $sendCode);
		$EMPG_CHILD	= $splitCode[0];
		$EMPG_BASAL	= $splitCode[1];
		//$task		= $splitCode[2];
		
		$TOTSALARY	= 1;
		$sqlCP		= "SELECT SUM(EMPG_BASAL * EMPG_COUNT) AS TOTSALARY 
						FROM tbl_employee_gol WHERE EMPG_CHILD != '$EMPG_CHILD' AND EMPG_STAT = 3";
		$resCP		= $this->db->query($sqlCP)->result();
		foreach($resCP as $rowCP) :
			$TOTSALARY1 = $rowCP->TOTSALARY; // APABILA ADA PERUBAHAN GAPOK, AKAN MENGAKUMULASI DENGAN GAPOK YANG BARU
			$TOTSALARY	= $TOTSALARY1 + $EMPG_BASAL;
		endforeach;
		if($TOTSALARY == 0)
			$TOTSALARY = $EMPG_BASAL;
			
		$koefGol	= $EMPG_BASAL / $TOTSALARY * 100;
		echo $koefGol;
	}
	
	function add_process() // OK
	{
		$this->load->model('m_hr/m_master/m_gol', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			date_default_timezone_set("Asia/Jakarta");
			
			$EmpID 			= $this->session->userdata('Emp_ID');	
			
			$EMPG_CODE		= $this->input->post('EMPG_CODE');
			//$EMPG_NAME		= $this->input->post('EMPG_NAME');
			$EMPG_RANK		= $this->input->post('EMPG_RANK');
			$EMPG_PARENT	= $this->input->post('EMPG_PARENT');
			$EMPG_CHILD		= $this->input->post('EMPG_CHILD');
			$EMPG_BASAL		= $this->input->post('EMPG_BASAL');
			$EMPG_P_ALLOW	= $this->input->post('EMPG_P_ALLOW');
			$EMPG_H_ALLOW	= $this->input->post('EMPG_H_ALLOW');
			$EMPG_C_ALLOW	= $this->input->post('EMPG_C_ALLOW');
			$EMPG_A_ALLOW1	= $this->input->post('EMPG_A_ALLOW1');
			$EMPG_A_ALLOW2	= $this->input->post('EMPG_A_ALLOW2');
			$EMPG_M_ALLOW	= $this->input->post('EMPG_M_ALLOW');
			$EMPG_PF_ALLOW	= $this->input->post('EMPG_PF_ALLOW');
			$EMPG_MK_ALLOW	= $this->input->post('EMPG_MK_ALLOW');
			$EMPG_I_ALLOW	= $this->input->post('EMPG_I_ALLOW');
			$EMPG_K_ALLOW	= $this->input->post('EMPG_K_ALLOW');
			
			// COUNT ALL WITH THE SAME PARENT
			$sqlGOL	= "tbl_employee WHERE Gol_Code = '$EMPG_CHILD' AND Emp_Status = 1";
			$resGOL	= $this->db->count_all($sqlGOL);
			if($resGOL == 0)
				$resGOL	= 1;
				
			$EMPG_COUNT		= $resGOL;	
					
			$EMPG_STAT		= $this->input->post('EMPG_STAT');
			$EMPG_NOTES		= $this->input->post('EMPG_NOTES');
			$EMPG_CREATED	= date('Y-m-d H:i:s');
			$EMPG_EMPID		= $EmpID;
			
			$Patt_Year		= date('Y',strtotime($EMPG_CREATED));
			$Patt_Month		= date('m',strtotime($EMPG_CREATED));
			$Patt_Date		= date('d',strtotime($EMPG_CREATED));			
			
			$inpGol 		= array('EMPG_CODE' 	=> $EMPG_CODE,
									'EMPG_PARENT'	=> $EMPG_PARENT,
									'EMPG_CHILD'	=> $EMPG_CHILD,
									'EMPG_RANK'		=> $EMPG_RANK,
									'EMPG_BASAL'	=> $EMPG_BASAL,
									'EMPG_P_ALLOW'	=> $EMPG_P_ALLOW,
									'EMPG_T_ALLOW'	=> $EMPG_T_ALLOW,
									'EMPG_HM_ALLOW'	=> $EMPG_HM_ALLOW,
									'EMPG_H_ALLOW'	=> $EMPG_H_ALLOW,
									'EMPG_C_ALLOW'	=> $EMPG_C_ALLOW,
									'EMPG_A_ALLOW1'	=> $EMPG_A_ALLOW1,
									'EMPG_A_ALLOW2'	=> $EMPG_A_ALLOW2,
									'EMPG_M_ALLOW'	=> $EMPG_M_ALLOW,
									'EMPG_PF_ALLOW'	=> $EMPG_PF_ALLOW,
									'EMPG_MK_ALLOW'	=> $EMPG_MK_ALLOW,
									'EMPG_I_ALLOW'	=> $EMPG_I_ALLOW,
									'EMPG_K_ALLOW'	=> $EMPG_K_ALLOW,
									'EMPG_COUNT'	=> $EMPG_COUNT,
									'EMPG_STAT'		=> $EMPG_STAT,
									'EMPG_NOTES'	=> $EMPG_NOTES,
									'EMPG_CREATED'	=> $EMPG_CREATED,
									'EMPG_EMPID'	=> $EMPG_EMPID,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_gol->add($inpGol);
			
			// HITUNG KOEFISIEN			
			$sqlCP		= "SELECT SUM(EMPG_BASAL * EMPG_COUNT) AS TOTSALARY 
							FROM tbl_employee_gol WHERE EMPG_STAT = 3";
			$resCP		= $this->db->query($sqlCP)->result();
			foreach($resCP as $rowCP) :
				$TOTSALARY	= $rowCP->TOTSALARY;
			endforeach;
			
			$sqlAll	= "SELECT EMPG_CODE, EMPG_PARENT, EMPG_CHILD, EMPG_BASAL FROM tbl_employee_gol WHERE EMPG_STAT = 3";
			$resAll = $this->db->query($sqlAll)->result();	
			foreach($resAll as $row) :
				$EMPG_CODE 		= $row->EMPG_CODE;
				$EMPG_PARENT 	= $row->EMPG_PARENT;
				$EMPG_CHILD		= $row->EMPG_CHILD;
				$EMPG_BASAL		= $row->EMPG_BASAL;
				
				// MENGHITUNG KOEFISIEN
				$koefGol		= $EMPG_BASAL / $TOTSALARY * 100;
				// UPDATE KOEFISIEN
				$sqlUKOEF		= "UPDATE tbl_employee_gol SET EMPG_K_ALLOW = $koefGol
									WHERE EMPG_CODE = '$EMPG_CODE' AND EMPG_STAT = 3";
				$this->db->query($sqlUKOEF);
			endforeach;
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_hr/c_master/c_gol/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_hr/m_master/m_gol', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EMPG_CODE	= $_GET['id'];
			$EMPG_CODE	= $this->url_encryption_helper->decode_url($EMPG_CODE);
			$geDATA 	= $this->m_gol->get_data_by_Code($EMPG_CODE)->row();
			$EMPG_CODE 	= $geDATA->EMPG_CODE;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_hr/c_master/c_gol/update_process');
			$data['backURL'] 		= site_url('c_hr/c_master/c_gol/?id='.$this->url_encryption_helper->encode_url($appName));
			$data["MenuCode"] 		= 'MN326';
			$MenuCode 				= 'MN326';				
			
			$data['default']['EMPG_CODE']		= $geDATA->EMPG_CODE;
			$data['default']['EMPG_NAME']		= $geDATA->EMPG_NAME;
			$data['default']['EMPG_RANK']		= $geDATA->EMPG_RANK;
			$data['default']['EMPG_PARENT']		= $geDATA->EMPG_PARENT;
			$data['default']['EMPG_CHILD']		= $geDATA->EMPG_CHILD;
			$data['default']['EMPG_BASAL']		= $geDATA->EMPG_BASAL;
			$data['default']['EMPG_P_ALLOW']	= $geDATA->EMPG_P_ALLOW;
			$data['default']['EMPG_T_ALLOW']	= $geDATA->EMPG_T_ALLOW;
			$data['default']['EMPG_HM_ALLOW']	= $geDATA->EMPG_HM_ALLOW;
			$data['default']['EMPG_H_ALLOW']	= $geDATA->EMPG_H_ALLOW;
			$data['default']['EMPG_C_ALLOW']	= $geDATA->EMPG_C_ALLOW;
			$data['default']['EMPG_A_ALLOW1']	= $geDATA->EMPG_A_ALLOW1;
			$data['default']['EMPG_A_ALLOW2']	= $geDATA->EMPG_A_ALLOW2;
			$data['default']['EMPG_M_ALLOW']	= $geDATA->EMPG_M_ALLOW;
			$data['default']['EMPG_PF_ALLOW']	= $geDATA->EMPG_PF_ALLOW;
			$data['default']['EMPG_MK_ALLOW']	= $geDATA->EMPG_MK_ALLOW;
			$data['default']['EMPG_I_ALLOW']	= $geDATA->EMPG_I_ALLOW;
			$data['default']['EMPG_K_ALLOW']	= $geDATA->EMPG_K_ALLOW;
			$data['default']['EMPG_COUNT']		= $geDATA->EMPG_COUNT;					
			$data['default']['EMPG_STAT']		= $geDATA->EMPG_STAT;
			$data['default']['EMPG_NOTES']		= $geDATA->EMPG_NOTES;
			$data['default']['Patt_Number']		= $geDATA->Patt_Number;
			
			$this->load->view('v_hr/v_master/v_gol/entry_gol_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_hr/m_master/m_gol', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			$EmpID 			= $this->session->userdata('Emp_ID');	
			
			$EMPG_CODE		= $this->input->post('EMPG_CODE');
			//$EMPG_NAME		= $this->input->post('EMPG_NAME');
			$EMPG_RANK		= $this->input->post('EMPG_RANK');
			$EMPG_PARENT	= $this->input->post('EMPG_PARENT');
			$EMPG_CHILD		= $this->input->post('EMPG_CHILD');
			$EMPG_BASAL		= $this->input->post('EMPG_BASAL');
			$EMPG_P_ALLOW	= $this->input->post('EMPG_P_ALLOW');
			$EMPG_T_ALLOW	= $this->input->post('EMPG_T_ALLOW');
			$EMPG_HM_ALLOW	= $this->input->post('EMPG_HM_ALLOW');
			$EMPG_H_ALLOW	= $this->input->post('EMPG_H_ALLOW');
			$EMPG_C_ALLOW	= $this->input->post('EMPG_C_ALLOW');
			$EMPG_A_ALLOW1	= $this->input->post('EMPG_A_ALLOW1');
			$EMPG_A_ALLOW2	= $this->input->post('EMPG_A_ALLOW2');
			$EMPG_M_ALLOW	= $this->input->post('EMPG_M_ALLOW');
			$EMPG_PF_ALLOW	= $this->input->post('EMPG_PF_ALLOW');
			$EMPG_MK_ALLOW	= $this->input->post('EMPG_MK_ALLOW');
			$EMPG_I_ALLOW	= $this->input->post('EMPG_I_ALLOW');
			$EMPG_K_ALLOW	= $this->input->post('EMPG_K_ALLOW');
			
			// COUNT ALL WITH THE SAME PARENT
			$sqlGOL	= "tbl_employee WHERE Gol_Code = '$EMPG_CHILD' AND Emp_Status = 1";
			$resGOL	= $this->db->count_all($sqlGOL);
			if($resGOL == 0)
				$resGOL	= 1;
				
			$EMPG_COUNT		= $resGOL;	
					
			$EMPG_STAT		= $this->input->post('EMPG_STAT');
			$EMPG_NOTES		= $this->input->post('EMPG_NOTES');
			$EMPG_CREATED	= date('Y-m-d H:i:s');
			$EMPG_EMPID		= $EmpID;
			
			$Patt_Year		= date('Y',strtotime($EMPG_CREATED));
			$Patt_Month		= date('m',strtotime($EMPG_CREATED));
			$Patt_Date		= date('d',strtotime($EMPG_CREATED));			
			
			$inpGol 		= array('EMPG_CODE' 	=> $EMPG_CODE,
									'EMPG_PARENT'	=> $EMPG_PARENT,
									'EMPG_CHILD'	=> $EMPG_CHILD,
									'EMPG_RANK'		=> $EMPG_RANK,
									'EMPG_BASAL'	=> $EMPG_BASAL,
									'EMPG_P_ALLOW'	=> $EMPG_P_ALLOW,
									'EMPG_T_ALLOW'	=> $EMPG_T_ALLOW,
									'EMPG_HM_ALLOW'	=> $EMPG_HM_ALLOW,
									'EMPG_H_ALLOW'	=> $EMPG_H_ALLOW,
									'EMPG_C_ALLOW'	=> $EMPG_C_ALLOW,
									'EMPG_A_ALLOW1'	=> $EMPG_A_ALLOW1,
									'EMPG_A_ALLOW2'	=> $EMPG_A_ALLOW2,
									'EMPG_M_ALLOW'	=> $EMPG_M_ALLOW,
									'EMPG_PF_ALLOW'	=> $EMPG_PF_ALLOW,
									'EMPG_MK_ALLOW'	=> $EMPG_MK_ALLOW,
									'EMPG_I_ALLOW'	=> $EMPG_I_ALLOW,
									'EMPG_K_ALLOW'	=> $EMPG_K_ALLOW,
									'EMPG_COUNT'	=> $EMPG_COUNT,
									'EMPG_STAT'		=> $EMPG_STAT,
									'EMPG_NOTES'	=> $EMPG_NOTES,
									'EMPG_CREATED'	=> $EMPG_CREATED,
									'EMPG_EMPID'	=> $EMPG_EMPID,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));							
			$this->m_gol->update($EMPG_CODE, $inpGol);
			
			// HITUNG KOEFISIEN			
			$sqlCP		= "SELECT SUM(EMPG_BASAL * EMPG_COUNT) AS TOTSALARY 
							FROM tbl_employee_gol WHERE EMPG_STAT = 3";
			$resCP		= $this->db->query($sqlCP)->result();
			foreach($resCP as $rowCP) :
				$TOTSALARY	= $rowCP->TOTSALARY;
			endforeach;
			
			$sqlAll	= "SELECT EMPG_CODE, EMPG_PARENT, EMPG_CHILD, EMPG_BASAL FROM tbl_employee_gol WHERE EMPG_STAT = 3";
			$resAll = $this->db->query($sqlAll)->result();	
			foreach($resAll as $row) :
				$EMPG_CODE 		= $row->EMPG_CODE;
				$EMPG_PARENT 	= $row->EMPG_PARENT;
				$EMPG_CHILD		= $row->EMPG_CHILD;
				$EMPG_BASAL		= $row->EMPG_BASAL;
				
				// MENGHITUNG KOEFISIEN
				$koefGol		= $EMPG_BASAL / $TOTSALARY * 100;
				// UPDATE KOEFISIEN
				$sqlUKOEF		= "UPDATE tbl_employee_gol SET EMPG_K_ALLOW = $koefGol
									WHERE EMPG_CODE = '$EMPG_CODE' AND EMPG_STAT = 3";
				$this->db->query($sqlUKOEF);
			endforeach;
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_hr/c_master/c_gol/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
}