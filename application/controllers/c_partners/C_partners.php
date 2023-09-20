<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 Juni 2021
 * File Name	= C_partners.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_partners extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_partners/m_partners', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		
		function cut_text2($var, $len = 200, $txt_titik = "-") 
		{
			$var1	= explode("</p>",$var);
			$var	= $var1[0];
			if (strlen ($var) < $len) 
			{ 
				return $var; 
			}
			if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
			{
				return $match [1] . $txt_titik;
			}
			else
			{
				return substr ($var, 0, $len) . $txt_titik;
			}
		}
	}
	
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_partners/c_partners/gPartn/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function gPartn() // OK
	{
		//$SPLCODE 		= $this->session->userdata['Emp_ID'];
		$SPLCODE 		= "DYE001.000002";
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_partners/c_partners/update_process');
			$data['backURL'] 		= site_url('c_partners/c_partners/');
			
			// GET MENU DESC
				$mnCode				= 'MN005';
				$data["MenuApp"] 	= 'MN005';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$MenuCode 				= 'MN005';
			$data['MenuCode'] 		= 'MN005';
			$getvendor 				= $this->m_partners->get_vendor_by_code($SPLCODE)->row();
			
			$data['default']['SPLCODE']	= $getvendor->SPLCODE;
			$data['default']['SPLDESC']	= $getvendor->SPLDESC;
			$data['default']['SPLCAT']	= $getvendor->SPLCAT;
			$data['default']['SPLADD1']	= $getvendor->SPLADD1;
			$data['default']['SPLKOTA']	= $getvendor->SPLKOTA;
			$data['default']['SPLNPWP']	= $getvendor->SPLNPWP;
			$data['default']['SPLPERS']	= $getvendor->SPLPERS;
			$data['default']['SPLTELP']	= $getvendor->SPLTELP;
			$data['default']['SPLMAIL']	= $getvendor->SPLMAIL;
			$data['default']['SPLNOREK']= $getvendor->SPLNOREK;
			$data['default']['SPLSCOPE']= $getvendor->SPLSCOPE;
			$data['default']['SPLNMREK']= $getvendor->SPLNMREK;
			$data['default']['SPLBANK']	= $getvendor->SPLBANK;
			$data['default']['SPLOTHR']	= $getvendor->SPLOTHR;
			$data['default']['SPLOTHR2']= $getvendor->SPLOTHR2;
			$data['default']['SPLTOP']	= $getvendor->SPLTOP;
			$data['default']['SPLTOPD']	= $getvendor->SPLTOPD;
			$data['default']['SPLSTAT']	= $getvendor->SPLSTAT;
			$data['default']['Patt_Number']	= $getvendor->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getvendor->SPLCODE;
				$MenuCode 		= 'MN005';
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
			
			$this->load->view('v_partners/v_partners', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_purchase/m_partners/m_partners', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$SPLCODE	= $this->input->post('SPLCODE');
			
			$vendor 	= array('SPLDESC'		=> addslashes($this->input->post('SPLDESC')),
								'SPLCAT'		=> $this->input->post('SPLCAT'),
								'SPLADD1'		=> addslashes($this->input->post('SPLADD1')),
								'SPLKOTA'		=> addslashes($this->input->post('SPLKOTA')),
								'SPLNPWP'		=> $this->input->post('SPLNPWP'),
								'SPLPERS'		=> $this->input->post('SPLPERS'),
								'SPLTELP'		=> $this->input->post('SPLTELP'),
								'SPLMAIL'		=> $this->input->post('SPLMAIL'),
								'SPLNOREK'		=> $this->input->post('SPLNOREK'),
								'SPLSCOPE'		=> $this->input->post('SPLSCOPE'),
								'SPLNMREK'		=> $this->input->post('SPLNMREK'),
								'SPLBANK'		=> $this->input->post('SPLBANK'),
								'SPLOTHR'		=> $this->input->post('SPLOTHR'),
								'SPLOTHR2'		=> $this->input->post('SPLOTHR2'),
								'SPLTOP'		=> $this->input->post('SPLTOP'),
								'SPLTOPD'		=> $this->input->post('SPLTOPD'),
								'SPLSTAT'		=> $this->input->post('SPLSTAT'));
			$this->m_partners->update($SPLCODE, $vendor);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $SPLCODE;
				$MenuCode 		= 'MN005';
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
			
			$url			= site_url('c_partners/c_partners/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function aP_L15t()
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$SPLCODE	= $_GET['id'];
		$SPLCODE	= $this->url_encryption_helper->decode_url($SPLCODE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			$data['SPLCODE'] 	= $SPLCODE;
			
			$data['countAP']	= $this->m_partners->count_all_AP($SPLCODE);
			$data['vwAP'] 		= $this->m_partners->get_all_AP($SPLCODE)->result();
			$gettotAP 			= $this->m_partners->get_all_TOTAP($SPLCODE)->row();
			$data['REMTOT']		= $gettotAP->REMTOT;
							
			$this->load->view('v_partners/v_partners/v_vendor_aplist', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function gLastCd()
	{
		$SPLCAT		= $this->input->post('SPLCAT');
		
		$sql 	= "tbl_supplier WHERE SPLCAT = '$SPLCAT'";
		$result = $this->db->count_all($sql);
		$myMax 	= $result+1;
		
		$Pattern_Length	= 6;
		$len = strlen($myMax);
		$nol = '';
		if($Pattern_Length==3)
		{if($len==1) $nol="00";else if($len==2) $nol="0";
		}
		elseif($Pattern_Length==4)
		{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
		}
		elseif($Pattern_Length==5)
		{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
		}
		elseif($Pattern_Length==6)
		{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
		}
		
		$lastPatt 	= $nol.$myMax;
		$DocNo 		= "$SPLCAT.$lastPatt";

		echo $DocNo;
	}
}