<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 April 2017
 * File Name	= Item_list.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Edit Date	= 29 September 2017
 * File Name	= c_fuel_usage.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class c_fuel_usage  extends CI_Controller  
{
 	function index() // OK
	{
		$this->load->model('m_asset/m_fuel_usage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_asset/c_fuel_usage/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function listproject($offset=0) // OK
	{
		$this->load->model('m_project/m_joblist/m_joblist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project List';
			$data['h3_title']			= 'Asset';
			
			$num_rows 					= $this->m_joblist->count_all_project();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_joblist->get_last_ten_project()->result();
			$data["MenuCode"] 			= 'MN315';
			$this->load->view('v_asset/v_fuel_usage/listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_item() // OK
	{
		$this->load->model('m_asset/m_fuel_usage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 				= $this->session->userdata('Emp_ID');
				
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Fuel Usage';
			$data['h3_title']	= 'Asset';
			$data['main_view'] 	= 'v_asset/v_fuel_usage/fuel_usage';
			//$linkback			= site_url('c_asset/c_fuel_usage/');
			//$data['link'] 		= array('link_back' => anchor("$linkback",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_asset/c_fuel_usage/');
			$data['MenuCode'] 	= 'MN315';
			
			$num_rows 				= $this->m_fuel_usage->count_all_num_rows($PRJCODE);
			$data["recordcount"] 	= $num_rows;
			$data['PRJCODE'] 		= $PRJCODE;
			
			$data['viewfuel_usage'] 	= $this->m_fuel_usage->get_last_ten_item($PRJCODE)->result();
			
			$this->load->view('v_asset/v_fuel_usage/fuel_usage', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_asset/m_fuel_usage', '', TRUE);
		
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
			$data['h2_title']		= 'Add Fuel Usage';
			$data['h3_title']		= 'Asset';
			$data['form_action']	= site_url('c_asset/c_fuel_usage/add_process');
			//$linkback				= site_url('c_asset/c_fuel_usage/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkback",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_asset/c_fuel_usage/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data["MenuCode"] 		= 'MN315';		
			$data['recordcountUnit'] 	= $this->m_fuel_usage->count_all_num_rowsUnit();
			$data['viewUnit'] 			= $this->m_fuel_usage->viewunit()->result();
			$data['recordcountCateg'] 	= $this->m_fuel_usage->count_all_num_rowsCateg();
//			$data['viewCateg'] 			= $this->m_fuel_usage->viewCateg()->result();
			$data['PRJCODE'] 			= $PRJCODE;
			
			$this->load->view('v_asset/v_fuel_usage/fuel_usage_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_asset/m_fuel_usage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$this->db->trans_begin();
			
			$FU_CODE 		= $this->input->post('FU_CODE');
			$FU_DATE	 	= date('Y-m-d',strtotime($this->input->post('FU_DATE')));
			$FU_TIME 		= date('H:i:s',strtotime($this->input->post('FU_TIME')));
			$FU_DATET		= "$FU_DATE $FU_TIME";
			
			$FU_PRJCODE 	= $this->input->post('FU_PRJCODE');
			$FU_ASSET 		= $this->input->post('FU_ASSET');
			$FU_QTY		 	= $this->input->post('FU_QTY');
			$FU_PRICE	 	= $this->input->post('FU_PRICE');
			$FU_NOTE	 	= $this->input->post('FU_NOTE');
			$FU_CREATER 	= $this->input->post('FU_CREATER');
			$FU_CREATED 	= date('Y-m-d H:i:s');
			$FU_STAT	 	= $this->input->post('FU_STAT');
			
			$itemPar 		= array('FU_CODE' 		=> $FU_CODE,
									'FU_DATE'		=> $FU_DATE,
									'FU_TIME'		=> $FU_TIME,
									'FU_DATET'		=> $FU_DATET,
									'FU_PRJCODE'	=> $FU_PRJCODE,
									'FU_ASSET'		=> $FU_ASSET,
									'FU_QTY'		=> $FU_QTY,
									'FU_PRICE'		=> $FU_PRICE,
									'FU_NOTE'		=> $FU_NOTE,
									'FU_CREATER'	=> $FU_CREATER,
									'FU_CREATED'	=> $FU_CREATED,
									'FU_STAT'		=> $FU_STAT);
			$this->m_fuel_usage->add($itemPar);
			
			// SAVE TO COST REPORT tbl_asset_rcost
			if($FU_STAT == 3)
			{
				// Start : PEMBUATAN TABEL COST REPORT DAN PRODCUTION
					$AS_NAME	= '';
					$sqlAST	= "SELECT AS_NAME FROM tbl_asset_list WHERE AS_CODE = '$FU_ASSET' LIMIT 1";
					$resAST	= $this->db->query($sqlAST)->result();
					foreach($resAST as $rowsAST) :
						$AS_NAME = $rowsAST->AS_NAME;
					endforeach;
					
					$PRJNAME	= '';
					$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$FU_PRJCODE' LIMIT 1";
					$resPRJ		= $this->db->query($sqlPRJ)->result();
					foreach($resPRJ as $rowsPRJ) :
						$PRJNAME = $rowsPRJ->PRJNAME;
					endforeach;
					
					$ITM_UNIT	= 'LTR';
					$ITM_COSTTOT= $FU_QTY * $FU_PRICE;
					$InsReport 	= array('RASTC_CODE' 	=> $FU_CODE,
										'RASTC_DATE'	=> $FU_DATE,
										'RASTC_PRJCODE'	=> $FU_PRJCODE,
										'RASTC_PRJNAME'	=> $PRJNAME,
										'RASTC_ASTCODE'	=> $FU_ASSET,
										'RASTC_ASTDESC'	=> $AS_NAME,
										'RAST_DESC'		=> $FU_NOTE,
										'RASTC_STARTD' 	=> $FU_DATET,
										'RASTC_ENDD'	=> $FU_DATET,
										'RASTC_QTYTIME'	=> 0,
										'RASTC_TYPE'	=> 'F',
										//'RASTC_JOBC'	=> $AU_JOBCODE,	
										//'RASTC_JOBD'	=> $JL_NAME,
										'RASTC_VOL'		=> $FU_QTY,
										//'RASTC_VOLAVG'=> $VOL_AVG,
										'RASTC_PRICE'	=> $FU_PRICE,
										'RASTC_UNIT'	=> $ITM_UNIT,
										'RASTC_COSTTOT'	=> $ITM_COSTTOT,
										//'RASTC_COSTAVGH'=> $COST_AVGH,
										//'RASTC_COSTAVGV'=> $COST_AVGV,
										'RASTC_NOTE'	=> $NOTES);
					$this->m_fuel_usage->createCostReport($InsReport);
				// End : Pembuatan Journal Detail
			}
			
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_fuel_usage/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($FU_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_asset/m_fuel_usage', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$FU_CODE	= $_GET['id'];
			$FU_CODE	= $this->url_encryption_helper->decode_url($FU_CODE);
			
			$geITEM = $this->m_fuel_usage->get_Item_by_Code($FU_CODE)->row();
			$FU_PRJCODE = $geITEM->FU_PRJCODE;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Fuel Usage';
			$data['h3_title']		= 'Asset';
			$data['form_action']	= site_url('c_asset/c_fuel_usage/update_process');
			//$linkback				= site_url('c_asset/c_fuel_usage/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($FU_PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkback",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_asset/c_fuel_usage/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($FU_PRJCODE));
			$data["MenuCode"] 		= 'MN315';
			//$data['link'] 			= array('link_back' => anchor('c_asset/c_fuel_usage','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
					
			$data['recordcountUnit'] 	= $this->m_fuel_usage->count_all_num_rowsUnit();
			$data['viewUnit'] 			= $this->m_fuel_usage->viewunit()->result();
			$data['recordcountCateg'] 	= $this->m_fuel_usage->count_all_num_rowsCateg();
			//$data['viewCateg'] 			= $this->m_fuel_usage->viewCateg()->result();
			
						
			
			$data['default']['FU_CODE'] 		= $geITEM->FU_CODE;
			$data['default']['FU_DATE']			= $geITEM->FU_DATE;
			$data['default']['FU_TIME']			= $geITEM->FU_TIME;
			$data['default']['FU_PRJCODE']		= $geITEM->FU_PRJCODE;
			$data['default']['FU_ASSET']		= $geITEM->FU_ASSET;
			$data['default']['FU_QTY']	 		= $geITEM->FU_QTY;
			$data['default']['FU_PRICE'] 		= $geITEM->FU_PRICE;
			$data['default']['FU_NOTE'] 		= $geITEM->FU_NOTE;
			$data['default']['FU_STAT'] 		= $geITEM->FU_STAT;
			$data['default']['FU_CREATER']		= $geITEM->FU_CREATER;
			$data['default']['FU_CREATED'] 		= $geITEM->FU_CREATED;
			
			$this->load->view('v_asset/v_fuel_usage/fuel_usage_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_asset/m_fuel_usage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			$FU_CODE 		= $this->input->post('FU_CODE');
			$FU_DATE	 	= date('Y-m-d',strtotime($this->input->post('FU_DATE')));
			$FU_PRJCODE 	= $this->input->post('FU_PRJCODE');
			$FU_ASSET 		= $this->input->post('FU_ASSET');
			$FU_QTY		 	= $this->input->post('FU_QTY');
			$FU_PRICE	 	= $this->input->post('FU_PRICE');
			$FU_NOTE	 	= $this->input->post('FU_NOTE');
			$FU_CREATER 	= $this->input->post('FU_CREATER');
			$FU_CREATED 	= date('Y-m-d',strtotime($this->input->post('FU_CREATED')));
			
			$itemPar 	= array('FU_CODE' 		=> $FU_CODE,
							'FU_DATE'			=> date('Y-m-d',strtotime($this->input->post('FU_DATE'))),
							'FU_PRJCODE'		=> $FU_PRJCODE,
							'FU_ASSET'			=> $this->input->post('FU_ASSET'),
							'FU_QTY'			=> $this->input->post('FU_QTY'),
							'FU_PRICE'			=> $this->input->post('FU_PRICE'),
							'FU_NOTE'			=> $this->input->post('FU_NOTE'),
							'FU_CREATER'		=> $this->input->post('FU_CREATER'),
							'FU_CREATED'		=> date('Y-m-d',strtotime($this->input->post('FU_CREATED'))));
							
			$this->m_fuel_usage->update($FU_CODE, $itemPar);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_fuel_usage/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($FU_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function popupallasset()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_asset/m_fuel_usage', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName 	= $therow->app_name;		
			endforeach;
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Asset List';
			$data['h3_title'] 			= 'asset';
			$data['PRJCODE'] 			= $PRJCODE;
		
			$data['reCountAllAsset'] 	= $this->m_fuel_usage->count_all_num_rowsAllAsset($PRJCODE);
			$data['viewAllAsset'] 		= $this->m_fuel_usage->viewAllIAsset($PRJCODE)->result();
					
			$this->load->view('v_asset/v_fuel_usage/fuel_usage_selectasset', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function changeStatusItem()
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$CodeItem 			= $this->input->post('chkDetail');
			$gesd_tcost 		= $this->m_fuel_usage->get_Item_by_Code($CodeItem)->row();
			
			$ItemStatus			= $gesd_tcost->Status;
			if($ItemStatus == 'Active')
			{
				$NItemStatus = 'InActive';
			}
			else
			{
				$NItemStatus = 'Active';
			}
							
			$this->m_fuel_usage->updateStatus($CodeItem, $NItemStatus);
			
			redirect('c_asset/c_fuel_usage/');
		}
		else
		{
			redirect('login');
		}
	}
	
	function delete($Item_Code)
	{
		$this->m_fuel_usage->delete($this->input->post('chkDetail'));
		$this->session->set_flashdata('message', 'Data successfull deleted');
		
		redirect('c_asset/c_fuel_usage/');
	}
	
	function popupallitem()
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $MyAppName;;
			$data['h2_title'] 		= 'Select Item';
			$data['main_view'] 		= 'v_asset/v_fuel_usage/Itemlist_sd_form';
			$data['form_action']	= site_url('c_asset/c_fuel_usage/update_process');
			
			$data['recordcountAllItem'] = $this->m_fuel_usage->count_all_num_rowsAllItem();
			$data['viewAllItem'] 	= $this->m_fuel_usage->viewAllItem()->result();
					
			$this->load->view('v_asset/v_fuel_usage/purchase_reqselecsd_tcost_sd', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
    function inbox($offset=0)
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $MyAppName;
			$data['h2_title']	= 'Item List Inbox';
			$data['main_view'] 	= 'v_asset/v_fuel_usage/Itemlist_inbox_sd';

			$num_rows = $this->m_fuel_usage->count_all_num_rows_inbox();
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_asset/c_fuel_usage/get_last_ten_item');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 2;
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
	 		
			$data['viewpurreq'] = $this->m_fuel_usage->get_last_ten_PR_inbox($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
    }
}