<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 Oktober 2018
 * File Name	= C_t4x_ppn.php
 * Location		= -
*/

class C_t4x_ppn extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_setting/m_tax_ppn/m_tax_ppn', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
	}

 	function index() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_t4x_ppn/t4x_l41nd/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function t4x_l41nd() // G
	{
		$this->load->model('m_setting/m_tax_ppn/m_tax_ppn', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 			= $appName;		
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN386';
				$MenuCode			= 'MN386';
				$data["MenuCode"] 	= 'MN386';
				$data["MenuApp"] 	= 'MN386';
				//$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 				= $this->m_tax_ppn->count_all_taxla();
			$data["counttaxla"] 	= $num_rows;	 
			$data['vwttaxla'] 		= $this->m_tax_ppn->get_all_taxla()->result();
			
			$this->load->view('v_setting/v_tax_ppn/v_tax_la_ppn', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() // GOOD
	{
		//$PRJCODE		= $_GET['id'];
		//$PRJCODE		= "";
		
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
			
			$columns_valid 	= array("TAXLA_CODE",
									"TAXLA_DESC",
									"TAXLA_LINKIN");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_tax_ppn->get_AllDataC($search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_tax_ppn->get_AllDataL($search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$TAXLA_NUM		= $dataI['TAXLA_NUM'];
				$TAXLA_CODE		= $dataI['TAXLA_CODE'];
				$TAXLA_DESC		= $dataI['TAXLA_DESC'];
				$TAXLA_PERC		= $dataI['TAXLA_PERC'];
				$TAXLA_PERCD 	= number_format($TAXLA_PERC,2);
				$TAXLA_LINKIN	= $dataI['TAXLA_LINKIN'];
				$TAXLA_LINKOUT	= $dataI['TAXLA_LINKOUT'];
            						
				$Acc_Nm			= '';
				$sqlC1b			= "SELECT Account_Number, Account_NameEn, Account_NameId
									FROM tbl_chartaccount 
									WHERE Account_Number = '$TAXLA_LINKIN'";
				$resC1b 		= $this->db->query($sqlC1b)->result();
				foreach($resC1b as $rowC1b) :
					$Acc_Nm 	= $rowC1b->Account_NameId;
				endforeach;

				$TAXLA_DESC2	= "$TAXLA_LINKIN - $Acc_Nm";

				$secUpd			= site_url('c_setting/c_t4x_ppn/t4x_l4_update/?id='.$this->url_encryption_helper->encode_url($TAXLA_NUM));

				$secDelIcut 	= base_url().'index.php/__l1y/trash_TAX/?id=';
				$delID 			= "$secDelIcut~tbl_tax_ppn~TAXLA_NUM~$TAXLA_NUM";
				$sTaxC			= "tbl_po_detail WHERE TAXCODE1 = '$TAXLA_NUM'";
				$rTaxC			= $this->db->count_all($sTaxC);
				
				if($rTaxC == 0) 
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
				
				$output['data'][] = array($noU.".",
										  //$TAXLA_CODE,
										  $TAXLA_DESC,
										  $TAXLA_DESC2,
										  $TAXLA_PERCD,
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function t4x_l44d() // G
	{
		$this->load->model('m_setting/m_tax_ppn/m_tax_ppn', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';	
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN386';
				$MenuCode			= 'MN386';
				$data["MenuCode"] 	= 'MN386';
				$data["MenuApp"] 	= 'MN386';
				//$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']	= site_url('c_setting/c_t4x_ppn/t4x_l4_process');
			$data['backURL'] 		= site_url('c_setting/c_t4x_ppn/');

			$data['vwDocPatt'] 	= $this->m_tax_ppn->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN386';
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
			
			$this->load->view('v_setting/v_tax_ppn/v_tax_la_ppn_form', $data);
		}
		else
		{
			rredirect('__l1y');
		}
	}
	
	function t4x_l4_process() // G
	{
		$this->load->model('m_setting/m_tax_ppn/m_tax_ppn', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
	
			$this->load->model('m_projectlist/m_projectlist', '', TRUE);
			$Pattern_Code	= "XX";
			$MenuCode 		= 'MN386';
			$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
			foreach($vwDocPatt as $row) :
				$Pattern_Code = $row->Pattern_Code;
			endforeach;
			
			$PRJCODE 		= '';
			$TRXTIME1		= date('His');
			$TAXLA_NUM		= "$TRXTIME1";
			
			/*$InsLA	= array('TAXLA_NUM'		=> $TAXLA_NUM,
							'TAXLA_CODE'	=> $this->input->post('TAXLA_CODE'),
							'TAXLA_DESC'	=> $this->input->post('TAXLA_DESC'),
							'TAXLA_PERC'	=> $this->input->post('TAXLA_PERC'),
							'TAXLA_LINKIN'	=> $this->input->post('TAXLA_LINKIN'),
							'TAXLA_LINKOUT'	=> $this->input->post('TAXLA_LINKOUT'));*/
			$InsLA	= array('TAXLA_NUM'		=> $TAXLA_NUM,
							'TAXLA_CODE'	=> $this->input->post('TAXLA_CODE'),
							'TAXLA_DESC'	=> $this->input->post('TAXLA_DESC'),
							'TAXLA_PERC'	=> $this->input->post('TAXLA_PERC'),
							'TAXLA_LINKIN'	=> $this->input->post('TAXLA_LINKIN'),
							'TAXLA_LINKOUT'	=> $this->input->post('TAXLA_LINKIN'));
			$this->m_tax_ppn->add($InsLA);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp 	= $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_setting/c_t4x_ppn/index/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function t4x_l4_update() // G
	{
		$this->load->model('m_setting/m_tax_ppn/m_tax_ppn', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$TAXLA_NUM			= $_GET['id'];
		$TAXLA_NUM			= $this->url_encryption_helper->decode_url($TAXLA_NUM);
		$data["MenuCode"] 	= 'MN386';
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN386';
				$MenuCode			= 'MN386';
				$data["MenuCode"] 	= 'MN386';
				$data["MenuApp"] 	= 'MN386';
				//$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']	= site_url('c_setting/c_t4x_ppn/t4x_l4_up_process');
			$data['backURL'] 		= site_url('c_setting/c_t4x_ppn/');
			
			$getTaxLA 				= $this->m_tax_ppn->get_tax_la($TAXLA_NUM)->row();
			
			$data['default']['TAXLA_NUM']		= $getTaxLA->TAXLA_NUM;
			$data['default']['TAXLA_CODE']		= $getTaxLA->TAXLA_CODE;
			$data['default']['TAXLA_DESC']		= $getTaxLA->TAXLA_DESC;
			$data['default']['TAXLA_PERC']		= $getTaxLA->TAXLA_PERC;
			$data['default']['TAXLA_LINKIN'] 	= $getTaxLA->TAXLA_LINKIN;
			$data['default']['TAXLA_LINKOUT'] 	= $getTaxLA->TAXLA_LINKOUT;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN386';
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
			
			$this->load->view('v_setting/v_tax_ppn/v_tax_la_ppn_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function t4x_l4_up_process() // G
	{		
		$this->load->model('m_setting/m_tax_ppn/m_tax_ppn', '', TRUE);
		
		$MLANG_CODE	= $this->input->post('MLANG_CODE');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$TAXLA_NUM	= $this->input->post('TAXLA_NUM');
			/*$UpdTLA		= array('TAXLA_NUM'		=> $TAXLA_NUM,
								'TAXLA_CODE'	=> $this->input->post('TAXLA_CODE'),
								'TAXLA_DESC'	=> $this->input->post('TAXLA_DESC'),
								'TAXLA_PERC'	=> $this->input->post('TAXLA_PERC'),
								'TAXLA_LINKIN'	=> $this->input->post('TAXLA_LINKIN'),
								'TAXLA_LINKOUT'	=> $this->input->post('TAXLA_LINKOUT'));*/
			$UpdTLA		= array('TAXLA_NUM'		=> $TAXLA_NUM,
								'TAXLA_CODE'	=> $this->input->post('TAXLA_CODE'),
								'TAXLA_DESC'	=> $this->input->post('TAXLA_DESC'),
								'TAXLA_PERC'	=> $this->input->post('TAXLA_PERC'),
								'TAXLA_LINKIN'	=> $this->input->post('TAXLA_LINKIN'),
								'TAXLA_LINKOUT'	=> $this->input->post('TAXLA_LINKIN'));
			$this->m_tax_ppn->update($TAXLA_NUM, $UpdTLA);
			
			if ($this->db->trans_status() == FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp 	= $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_setting/c_t4x_ppn/index/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}