<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Maret 2017
 * File Name	= C_asset_group.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_asset_group  extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_asset/m_asset_group', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		$this->data['nSELP']		= $this->session->userdata['nSELP'];
		
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
		
		// DEFAULT PROJECT
			$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$PRJCODE	= $rowISHO->PRJCODE;
			endforeach;
			$this->data['PRJCODE']		= $PRJCODE;
			$this->data['PRJCODE_HO']	= $PRJCODE;
		
		// GET PROJECT SELECT
			if(isset($_GET['id']))
			{
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
			}
			else
			{
				$EXP_COLLD1		= '';
			}

			$EXP_COLLD		= explode('~', $EXP_COLLD1);	
			$C_COLLD1		= count($EXP_COLLD);
			if($C_COLLD1 > 1)
			{
				$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
				$PRJCODE	= $EXP_COLLD[0];
			}
			else
			{
				$PRJCODE	= $EXP_COLLD1;
			}
		
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}
	
	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_asset/c_asset_group/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function index1($offset=0)
	{
		$this->load->model('m_asset/m_asset_group', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			// GET MENU DESC
				$mnCode				= 'MN054';
				$data["MenuApp"] 	= 'MN054';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
					
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Asset Group List';
			$data['h3_title']		= 'asset management';
			$data['addURL'] 		= site_url('c_asset/c_asset_group/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 		= site_url('c_asset/c_asset_group/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 				= $this->m_asset_group->count_all_num_rows();
			$data["recordcount"] 	= $num_rows;
			$data["MenuCode"] 		= 'MN054';
			$data['vAssetGroup']	= $this->m_asset_group->get_last_ten_AG()->result();
			
			$this->load->view('v_asset/v_asset_group/asset_group', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

  	function get_AllData() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		
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
			
			$columns_valid 	= array("PR_ID",
									"PR_CODE", 
									"PR_DATE", 
									"PR_NOTE", 
									"CREATERNM", 
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_req->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_req->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							
				$PR_NUM		= $dataI['PR_NUM'];
				$PR_CODE	= $dataI['PR_CODE'];
				
				$PR_DATE	= $dataI['PR_DATE'];
				$PR_DATEV	= date('d M Y', strtotime($PR_DATE));
				
				$JOBCODE	= $dataI['JOBCODE'];
				$JOBDESC	= $dataI['JOBDESC'];
				$PR_NOTE	= $dataI['PR_NOTE'];
				$JOBDESC	= "$PR_NOTE";
				$PR_STAT	= $dataI['PR_STAT'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				//$empName	= cut_text2 ("$CREATERNM", 15);
				$REQUESTER	= $dataI['PR_REQUESTER'];
				$empName	= cut_text2 ("$REQUESTER", 15);
				if($empName == '')
					$empName= "-";
				
				$CollCode	= "$PRJCODE~$PR_NUM";
				$secUpd		= site_url('c_purchase/c_pr180d0c/update/?id='.$this->url_encryption_helper->encode_url($CollCode));
				$secPPOList	= site_url('c_purchase/c_pr180d0c/pRn_P0l/?id='.$this->url_encryption_helper->encode_url($PR_NUM));
				$secPrint	= site_url('c_purchase/c_pr180d0c/printdocument/?id='.$this->url_encryption_helper->encode_url($PR_NUM));
				$CollID		= "PR~$PR_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_pr_header~tbl_pr_detail~PR_NUM~$PR_NUM~PRJCODE~$PRJCODE";
				$secVoid 	= base_url().'index.php/__l1y/trashPR/?id=';
				$voidID 	= "$secVoid~tbl_pr_header~tbl_pr_detail~PR_NUM~$PR_NUM~PRJCODE~$PRJCODE";

				// CEK PO
					$sqlPOC	= "tbl_po_header WHERE PRJCODE = '$PRJCODE' AND PR_NUM = '$PR_NUM' AND PO_STAT NOT IN (5,9)";
					$resPOC = $this->db->count_all($sqlPOC);
 
					if($resPOC > 0)
					{
						$disCl 	= "voidDOCX";
						$disV 	= "disabled='disabled'";
					}
					else
					{
						$disCl 	= "voidDOC";
						$disV 	= "";
					}
                                    
				if($PR_STAT == 1 || $PR_STAT == 4)
				{
					$secAction	= 	"<input type='hidden' name='urlPOList".$noU."' id='urlPOList".$noU."' value='".$secPPOList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   	<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Order' disabled='disabled'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($PR_STAT == 3)
				{
					$secAction	= 	"<input type='hidden' name='urlPOList".$noU."' id='urlPOList".$noU."' value='".$secPPOList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Order' onClick='pRn_P0l(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='".$disCl."(".$noU.")' title='Void' ".$disV.">
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlPOList".$noU."' id='urlPOList".$noU."' value='".$secPPOList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Order' onClick='pRn_P0l(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				
				$output['data'][] = array("$noU.",
										  "<div style='white-space:nowrap'>".$dataI['PR_CODE']."</div>",
										  $PR_DATEV,
										  $JOBDESC,
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add() // OK
	{	
		$this->load->model('m_asset/m_asset_group', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Asset Group';
			$data['h3_title']		= 'asset management';
			$data['form_action']	= site_url('c_asset/c_asset_group/add_process');
			//$data['link'] 			= array('link_back' => anchor('c_asset/c_asset_group/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_asset/c_asset_group/');
			$MenuCode 				= 'MN054';
			$data["MenuCode"] 		= 'MN054';
			$data['viewDocPattern'] = $this->m_asset_group->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_asset/v_asset_group/asset_group_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_asset/m_asset_group', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$AG_CODE	= $this->input->post('AG_CODE');
			$AG_MANCODE	= $this->input->post('AG_MANCODE');
			$AG_NAME	= $this->input->post('AG_NAME');
			$AG_DESC	= $this->input->post('AG_DESC');
			
			$InsAG 		= array('AG_CODE' 		=> $AG_CODE,
								'AG_MANCODE'	=> $AG_MANCODE,
								'AG_NAME'		=> $AG_NAME,
								'AG_DESC'		=> $AG_DESC);
												
			$this->m_asset_group->add($InsAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_asset_group/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{	
		$this->load->model('m_asset/m_asset_group', '', TRUE);
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
			$data['form_action']	= site_url('c_asset/c_asset_group/update_process');
			$data['link'] 			= array('link_back' => anchor('c_asset/c_asset_group/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_asset/c_asset_group/');
			
			$getAG 					= $this->m_asset_group->get_AG($AG_CODE)->row();
			$data["MenuCode"] 		= 'MN054';
			
			$data['default']['AG_CODE'] 	= $getAG->AG_CODE;
			$data['default']['AG_MANCODE'] 	= $getAG->AG_MANCODE;
			$data['default']['AG_NAME'] 	= $getAG->AG_NAME;
			$data['default']['AG_DESC'] 	= $getAG->AG_DESC;
			
			$this->load->view('v_asset/v_asset_group/asset_group_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{	
		$this->load->model('m_asset/m_asset_group', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$AG_CODE	= $this->input->post('AG_CODE');
			$AG_MANCODE	= $this->input->post('AG_MANCODE');
			$AG_NAME	= $this->input->post('AG_NAME');
			$AG_DESC	= $this->input->post('AG_DESC');
			
			$UpdAG 		= array('AG_CODE' 		=> $AG_CODE,
								'AG_MANCODE'	=> $AG_MANCODE,
								'AG_NAME'		=> $AG_NAME,
								'AG_DESC'		=> $AG_DESC);
								
			$this->m_asset_group->update($AG_CODE, $UpdAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_asset_group/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}