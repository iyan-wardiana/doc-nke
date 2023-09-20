<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 1 November 2017
	* File Name	= C_position_str.php
	* Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_position_str extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_hr/m_organiz/m_position_str', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	}

 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_hr/c_organiz/c_position_str/get_position_str/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

  	function get_AllData() // OK
	{
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("POSS_CODE", 
									"POSS_NAME", 
									"POSS_DESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_position_str->get_AllDataC($search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_position_str->get_AllDataL($search, $length, $start, $order, $dir);
			
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$POSS_CODE		= $dataI['POSS_CODE'];
				$POSS_NAME		= $dataI['POSS_NAME'];
				$POSS_DESC		= $dataI['POSS_DESC'];
				$POSS_PARENT	= $dataI['POSS_PARENT'];
				if($POSS_PARENT == '')
				{
					$PARENT_NAME = "-";
				}
				else
				{
					$PARENT_NAME  	= "-";
					$sqlGetParent	= "SELECT POSS_NAME FROM tbl_position_str WHERE POSS_CODE = '$POSS_PARENT'";
					$resGetParent	= $this->db->query($sqlGetParent)->result();
					foreach($resGetParent as $newrow) :
						$PARENT_NAME = $newrow->POSS_NAME;
					endforeach;
				}
				
                $secUpd		= site_url('c_hr/c_organiz/c_position_str/update/?id='.$this->url_encryption_helper->encode_url($POSS_CODE));
				$secDel 	= base_url().'index.php/__l1y/trashPSTR/?id=';
				$delID 		= "$secDel~tbl_position_str~POSS_CODE~$POSS_CODE";

				$sqlSTR	 	= "tbl_employee WHERE Dept_Code = '$POSS_CODE'";
				$resSTR 	= $this->db->count_all($sqlSTR);
				if($resSTR == 0)
				{
					$sqlCHLD	= "tbl_position_str WHERE POSS_PARENT = '$POSS_CODE'";
					$resCHLD 	= $this->db->count_all($sqlCHLD);
					if($resCHLD == 0)
						$resSTR = 0;
					else
						$resSTR = 1;
				}

				if($resSTR == 0)
				{
					$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}

				$output['data'][] 	= array("$noU.",
											$POSS_CODE,
											"<div style='white-space:nowrap'>$POSS_NAME</div>",
											"<div style='white-space:nowrap'>$PARENT_NAME</div>",
										  	"<div style='white-space:nowrap'>".$POSS_DESC."</div>",
										  	"<div style='white-space:nowrap'>".$secAction."</div>");

				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function get_position_str($offset=0)
	{
		$this->load->model('m_hr/m_organiz/m_position_str', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			$EmpID 		= $this->session->userdata('Emp_ID');
					
			$data['title'] 		= $appName;
			$data["MenuCode"] 	= 'MN329';			
			$num_rows 			= $this->m_position_str->count_all();
			$data["countPosStr"]= $num_rows;	 
			$data['vwPosStr']	= $this->m_position_str->get_position_str()->result();
			
			$this->load->view('v_hr/v_organiz/v_position_str/position_str', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add()
	{
		$this->load->model('m_hr/m_organiz/m_position_str', '', TRUE);
		$this->load->model('m_docpattern/m_docpattern', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_hr/c_organiz/c_position_str/add_process');
			$data['backURL'] 	= site_url('c_hr/c_organiz/c_position_str/?id='.$this->url_encryption_helper->encode_url($appName));
			$data["MenuCode"] 	= 'MN329';
			
			$MenuCode			= 'MN329';			
			$data['vwDocPatt'] 	= $this->m_docpattern->getDataDocPat($MenuCode)->result();
			
			$data['countParent'] = $this->m_position_str->count_all();		
			$data['vwParent'] 	= $this->m_position_str->get_position_str_prn()->result();
			
			$this->load->view('v_hr/v_organiz/v_position_str/position_str_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getTot($PercData) // OK -- Update only
	{
		$splitCode 	= explode("~", $PercData);
		$POSS_CODE	= $splitCode[0];
		$PercVal	= $splitCode[1];
		
		/*$POSS_PARENT= '';
		$sqlPC		= "SELECT POSS_PARENT FROM tbl_position_str WHERE POSS_CODE = '$POSS_CODE' AND POSS_STAT = 1";
		$resPC		= $this->db->query($sqlPC)->result();
		foreach($resPC as $rowPC) :
			$POSS_PARENT = $rowPC->POSS_PARENT;
		endforeach;*/
		
		$TOTALLOW	= 0;
		$sqlTOT		= "SELECT SUM(POSS_ALLOW) AS TOTALLOW FROM tbl_position_str WHERE POSS_PARENT = '$POSS_CODE' AND POSS_STAT = 1";
		$resTOT		= $this->db->query($sqlTOT)->result();
		foreach($resTOT as $rowTOT) :
			$TOTALLOW = $rowTOT->TOTALLOW;
		endforeach;
		echo $TOTALLOW + $PercVal;
	}
		
	function getAmount($PercData) // OK -- Update only
	{
		$splitCode 		= explode("~", $PercData);
		$POSS_PARENT1	= $splitCode[0];	// EX.: DIV.MRK
		$POSS_ALLOW0	= $splitCode[1];	// EX.: 500,000,000.00
		$ALLOW_AMOUNT	= $splitCode[2];	// EX.: 500,000,000.00
		
		$AllowPercent	= 0;
		$AllowAmount	= 0;
			
		// CHECK KEBERADAAN POSS_PARENT1 DI DATABASE, ADA / TIDAK ... ?
		$countALLOW1		= 0;
		$sqlALLOW1C			= "tbl_position_str WHERE POSS_CODE = '$POSS_PARENT1' AND POSS_STAT = 1";
		$countALLOW1		= $this->db->count_all($sqlALLOW1C);
		if($countALLOW1 > 0)
		{
			$POSS_PARENT2		= '';
			$POSS_ALLOW1		= 0;
			$sqlALLOW1			= "SELECT POSS_PARENT, POSS_ALLOW FROM tbl_position_str WHERE POSS_CODE = '$POSS_PARENT1' AND POSS_STAT = 1";
			$resALLOW1			= $this->db->query($sqlALLOW1)->result();
			foreach($resALLOW1 as $rowALLOW1) :
				$POSS_PARENT2	= $rowALLOW1->POSS_PARENT;	// DEPT.MRKT	// PARENT 2
				$POSS_ALLOW1	= $rowALLOW1->POSS_ALLOW;	// 4
				if($POSS_ALLOW1 == '' || $POSS_ALLOW1 == 0)
				{
					$POSS_ALLOW1	= 1;
				}
			endforeach;			
			$AllowPercent		= $POSS_ALLOW0 * $POSS_ALLOW1 / 100;
			$AllowAmount		= $ALLOW_AMOUNT * $AllowPercent / 100;
			$AllowAmount		= $AllowAmount;
				
			// CHECK KEBERADAAN POSS_PARENT2 DI DATABASE, ADA / TIDAK ... ?
			$countALLOW2		= 0;
			$sqlALLOW2C			= "tbl_position_str WHERE POSS_CODE = '$POSS_PARENT2' AND POSS_STAT = 1";
			$countALLOW2		= $this->db->count_all($sqlALLOW2C);
			if($countALLOW2 > 0)
			{
				$POSS_PARENT3		= '';
				$POSS_ALLOW2		= 0;
				$sqlALLOW2			= "SELECT POSS_PARENT, POSS_ALLOW FROM tbl_position_str WHERE POSS_CODE = '$POSS_PARENT2' AND POSS_STAT = 1";
				$resALLOW2			= $this->db->query($sqlALLOW2)->result();
				foreach($resALLOW2 as $rowALLOW2) :
					$POSS_PARENT3	= $rowALLOW2->POSS_PARENT;
					$POSS_ALLOW2	= $rowALLOW2->POSS_ALLOW;
					if($POSS_ALLOW2 == '' || $POSS_ALLOW2 == 0)
					{
						$POSS_ALLOW2	= 1;
					}
				endforeach;
				$AllowPercent	= $POSS_ALLOW0 * $POSS_ALLOW1 * $POSS_ALLOW2 / 10000;
				$AllowAmount	= $ALLOW_AMOUNT * $AllowPercent / 100;
				$AllowAmount	= $AllowAmount;
				
				// CHECK KEBERADAAN POSS_PARENT3 DI DATABASE, ADA / TIDAK ... ?
				$countALLOW3		= 0;
				$sqlALLOW3C			= "tbl_position_str WHERE POSS_CODE = '$POSS_PARENT3' AND POSS_STAT = 1";
				$countALLOW3		= $this->db->count_all($sqlALLOW3C);
				if($countALLOW3 > 0)
				{
					$sqlALLOW3			= "SELECT POSS_PARENT, POSS_ALLOW FROM tbl_position_str WHERE POSS_CODE = '$POSS_PARENT3' AND POSS_STAT = 1";
					$resALLOW3			= $this->db->query($sqlALLOW3)->result();
					foreach($resALLOW3 as $rowALLOW3) :
						$POSS_PARENT4	= $rowALLOW3->POSS_PARENT;
						$POSS_ALLOW3	= $rowALLOW3->POSS_ALLOW;
						if($POSS_ALLOW3 == '' || $POSS_ALLOW3 == 0)
						{
							$POSS_ALLOW3	= 1;
						}
					endforeach;
					$AllowPercent	= $POSS_ALLOW0 * $POSS_ALLOW1 * $POSS_ALLOW2 * $POSS_ALLOW3 / 1000000;
					$AllowAmount	= $ALLOW_AMOUNT * $AllowPercent / 100;
					$AllowAmount	= $AllowAmount;
				
					// CHECK KEBERADAAN POSS_PARENT4 DI DATABASE, ADA / TIDAK ... ?
					$countALLOW4		= 0;
					$sqlALLOW4C			= "tbl_position_str WHERE POSS_CODE = '$POSS_PARENT4' AND POSS_STAT = 1";
					$countALLOW4		= $this->db->count_all($sqlALLOW4C);
					if($countALLOW4 > 0)
					{
						$sqlALLOW4			= "SELECT POSS_PARENT, POSS_ALLOW FROM tbl_position_str WHERE POSS_CODE = '$POSS_PARENT4' AND POSS_STAT = 1";
						$resALLOW4			= $this->db->query($sqlALLOW4)->result();
						foreach($resALLOW4 as $rowALLOW4) :
							$POSS_PARENT5	= $rowALLOW4->POSS_PARENT;
							$POSS_ALLOW4	= $rowALLOW4->POSS_ALLOW;
							if($POSS_ALLOW4 == '' || $POSS_ALLOW4 == 0)
							{
								$POSS_ALLOW4	= 1;
							}
						endforeach;
						$AllowPercent	= $POSS_ALLOW1 * $POSS_ALLOW2 * $POSS_ALLOW3 * $POSS_ALLOW4 / 10000000;
						$AllowAmount	= $ALLOW_AMOUNT * $AllowPercent / 100;
						$AllowAmount	= $AllowAmount;
				
						// CHECK KEBERADAAN POSS_PARENT5 DI DATABASE, ADA / TIDAK ... ?
						$countALLOW5		= 0;
						$sqlALLOW5C			= "tbl_position_str WHERE POSS_CODE = '$POSS_PARENT5' AND POSS_STAT = 1";
						$countALLOW5		= $this->db->count_all($sqlALLOW5C);
						if($countALLOW5 > 0)
						{
							$sqlALLOW5			= "SELECT POSS_PARENT, POSS_ALLOW FROM tbl_position_str WHERE POSS_CODE = '$POSS_PARENT5' AND POSS_STAT = 1";
							$resALLOW5			= $this->db->query($sqlALLOW5)->result();
							foreach($resALLOW5 as $rowALLOW5) :
								$POSS_PARENT6	= $rowALLOW5->POSS_PARENT;
								$POSS_ALLOW5	= $rowALLOW5->POSS_ALLOW;
							endforeach;
							$AllowPercent	= $POSS_ALLOW1 * $POSS_ALLOW2 * $POSS_ALLOW3 * $POSS_ALLOW4 * $POSS_ALLOW5 / 10000000000;
							$AllowAmount	= $ALLOW_AMOUNT * $AllowPercent / 100;
							$AllowAmount	= $AllowAmount;
						}
					}
				}
			}
		}
		echo $AllowAmount;
	}
	
	function add_process()
	{ 
		$this->load->model('m_hr/m_organiz/m_position_str', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			$POSS_LEVEL	= $this->input->post('POSS_LEVEL');
			if($POSS_LEVEL == 'BOD')
				$POSS_LEVIDX	= 1;
			elseif($POSS_LEVEL == 'DEPT')
				$POSS_LEVIDX	= 2;
			elseif($POSS_LEVEL == 'DIV')
				$POSS_LEVIDX	= 3;
			elseif($POSS_LEVEL == 'BIRO')
				$POSS_LEVIDX	= 4;
			elseif($POSS_LEVEL == 'UNIT')
				$POSS_LEVIDX	= 5;
			elseif($POSS_LEVEL == 'URS')
				$POSS_LEVIDX	= 6;
			elseif($POSS_LEVEL == 'STAF')
				$POSS_LEVIDX	= 7;
			else
				$POSS_LEVIDX	= 99;

			// GET LEVEL INDEX
				$POSS_PARENT	= $this->input->post('POSS_PARENT');
				if($POSS_PARENT == '0')
				{
					$POSS_LEV	= 1;
				}
				else
				{
					$POSS_LEV 		= 0;
					$sqlLEV			= "SELECT POSS_LEVIDX FROM tbl_position_str WHERE POSS_CODE = '$POSS_PARENT' AND POSS_STAT = 1";
					$resLEV			= $this->db->query($sqlLEV)->result();
					foreach($resLEV as $rowLEV) :
						$POSS_LEV	= $rowLEV->POSS_LEVIDX;
					endforeach;
					$POSS_LEV 		= $POSS_LEV + 1;
				}
				
			$position = array('POSS_NO'			=> $this->input->post('POSS_NO'),
								'POSS_CODE'		=> $this->input->post('POSS_CODE'),
								'POSS_NAME'		=> $this->input->post('POSS_NAME'),
								'POSS_LEVEL'	=> $POSS_LEVEL,
								'POSS_LEVIDX'	=> $POSS_LEV,
								'POSS_PARENT'	=> $this->input->post('POSS_PARENT'),
								'POSS_ALLOW'	=> $this->input->post('POSS_ALLOW'),
								'POSS_ALLOW_VAL'=> $this->input->post('POSS_ALLOW_VAL'),
								'POSS_DESC'		=> $this->input->post('POSS_DESC'),
								'POSS_ISLAST'	=> $this->input->post('POSS_ISLAST'),
								'POSS_STAT'		=> $this->input->post('POSS_STAT'),
								'chkAllow'		=> $this->input->post('chkAllow'),
								'Patt_Number'	=> $this->input->post('Patt_Number'));
	
			$this->m_position_str->add($position);			
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_hr/c_organiz/c_position_str/');
		}
		else
		{
			redirect('__l1y');
		}	
	}
	
	function update()
	{
		$this->load->model('m_hr/m_organiz/m_position_str', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$POSS_CODE	= $_GET['id'];
			$POSS_CODE	= $this->url_encryption_helper->decode_url($POSS_CODE);
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_hr/c_organiz/c_position_str/update_process');
			$data['backURL'] 	= site_url('c_hr/c_organiz/c_position_str/?id='.$this->url_encryption_helper->encode_url($appName));
			$data["MenuCode"] 	= 'MN329';
			
			$data['countParent'] = $this->m_position_str->count_all();		
			$data['vwParent'] 	= $this->m_position_str->get_position_str_prn()->result();
			
			$getposition = $this->m_position_str->get_position_by_code($POSS_CODE)->row();
			
			$data['default']['POSS_NO'] 	= $getposition->POSS_NO;
			$data['default']['POSS_CODE'] 	= $getposition->POSS_CODE;
			$data['default']['POSS_NAME'] 	= $getposition->POSS_NAME;
			$data['default']['POSS_LEVEL'] 	= $getposition->POSS_LEVEL;
			$data['default']['POSS_PARENT']	= $getposition->POSS_PARENT;
			$data['default']['POSS_ALLOW']	= $getposition->POSS_ALLOW;
			$data['default']['POSS_ALLOW_VAL']	= $getposition->POSS_ALLOW_VAL;
			$data['default']['POSS_DESC'] 	= $getposition->POSS_DESC;
			$data['default']['POSS_ISLAST']	= $getposition->POSS_ISLAST;
			$data['default']['POSS_STAT']	= $getposition->POSS_STAT;
			$data['default']['chkAllow']	= $getposition->chkAllow;
			
			$this->load->view('v_hr/v_organiz/v_position_str/position_str_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_hr/m_organiz/m_position_str', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{
			$POSS_CODE	= $this->input->post('POSS_CODE');
			
			$this->db->trans_begin();
			
			$POSS_LEVEL	= $this->input->post('POSS_LEVEL');
			if($POSS_LEVEL == 'BOD')
				$POSS_LEVIDX	= 1;
			elseif($POSS_LEVEL == 'DEPT')
				$POSS_LEVIDX	= 2;
			elseif($POSS_LEVEL == 'DIV')
				$POSS_LEVIDX	= 3;
			elseif($POSS_LEVEL == 'BIRO')
				$POSS_LEVIDX	= 4;
			elseif($POSS_LEVEL == 'UNIT')
				$POSS_LEVIDX	= 5;
			elseif($POSS_LEVEL == 'URS')
				$POSS_LEVIDX	= 6;
			elseif($POSS_LEVEL == 'STAF')
				$POSS_LEVIDX	= 7;
			else
				$POSS_LEVIDX	= 99;

			// GET LEVEL INDEX
				$POSS_PARENT	= $this->input->post('POSS_PARENT');

				if($POSS_PARENT == '0')
				{
					$POSS_LEV	= 1;
				}
				else
				{
					$POSS_LEV 		= 0;
					$sqlLEV			= "SELECT POSS_LEVIDX FROM tbl_position_str WHERE POSS_CODE = '$POSS_PARENT' AND POSS_STAT = 1";
					$resLEV			= $this->db->query($sqlLEV)->result();
					foreach($resLEV as $rowLEV) :
						$POSS_LEV	= $rowLEV->POSS_LEVIDX;
					endforeach;
					$POSS_LEV 		= $POSS_LEV + 1;
				}
				
			$position = array('POSS_NAME'		=> $this->input->post('POSS_NAME'),
								'POSS_LEVEL'	=> $POSS_LEVEL,
								'POSS_LEVIDX'	=> $POSS_LEV,
								'POSS_PARENT'	=> $this->input->post('POSS_PARENT'),
								'POSS_ALLOW'	=> $this->input->post('POSS_ALLOW'),
								'POSS_ALLOW_VAL'=> $this->input->post('POSS_ALLOW_VAL'),
								'POSS_DESC'		=> $this->input->post('POSS_DESC'),
								'POSS_ISLAST'	=> $this->input->post('POSS_ISLAST'),
								'POSS_STAT'		=> $this->input->post('POSS_STAT'),
								'chkAllow'		=> $this->input->post('chkAllow'));				
			$this->m_position_str->update($POSS_CODE, $position);
			
			redirect('c_hr/c_organiz/c_position_str/');
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getTheCode($POSS_CODE) // OK
	{
		$sqlSTR		= "tbl_position_str WHERE POSS_CODE = '$POSS_CODE'";
		$resSTR 	= $this->db->count_all($sqlSTR);
		echo $resSTR;
	}
}