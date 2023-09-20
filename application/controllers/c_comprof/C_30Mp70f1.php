<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 Juli 2019
 * File Name	= C_30Mp70f.php
 * Location		= -
*/

class C_30Mp70f extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_company/m_comprof/m_comprof', '', TRUE);
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
	
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_comprof/c_30Mp70f/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function i180c2gdx($offset=0)
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Project List';
				
			// START : GET MENU NAME
				$data['MenuCode'] 	= 'MN402';
				$MenuCode			= 'MN402';
				$getMNNm 		= $this->m_updash->get_menunm($MenuCode)->row();
				if($this->data['LangID'] == 'IND')
					$data['MenuName'] = $getMNNm->menu_name_IND;
				else
					$data['MenuName'] = $getMNNm->menu_name_ENG;
			// END : GET MENU NAME
			
			$data['form_action']			= site_url('c_comprof/c_30Mp70f/update_process');
			
			$get_COMP 						= $this->m_comprof->get_comprof()->row();					
			$data['default']['proj_Number'] = $get_COMP->proj_Number;
			$data['default']['PRJCODE'] 	= $get_COMP->PRJCODE;
			$data['default']['PRJCNUM'] 	= $get_COMP->PRJCNUM;
			$data['default']['PRJNAME'] 	= $get_COMP->PRJNAME;
			$data['default']['PRJLOCT'] 	= $get_COMP->PRJLOCT;
			$data['default']['PRJADD'] 		= $get_COMP->PRJADD;
			$data['default']['PRJTELP'] 	= $get_COMP->PRJTELP;
			$data['default']['PRJFAX'] 		= $get_COMP->PRJFAX;
			$data['default']['PRJMAIL'] 	= $get_COMP->PRJMAIL;
			$data['default']['PRJCATEG'] 	= $get_COMP->PRJCATEG;
			$data['default']['PRJOWN'] 		= $get_COMP->PRJOWN;
			$data['default']['PRJDATE'] 	= $get_COMP->PRJDATE;
			$data['default']['PRJDATE_CO'] 	= $get_COMP->PRJDATE_CO;
			$data['default']['PRJEDAT'] 	= $get_COMP->PRJEDAT;
			$PRJEDAT						= $get_COMP->PRJEDAT;
			$data['default']['PRJCOST'] 	= $get_COMP->PRJCOST;
			$data['default']['PRJBOQ'] 		= $get_COMP->PRJBOQ;
			$data['default']['PRJRAP'] 		= $get_COMP->PRJRAP;
			$data['default']['PRJLKOT'] 	= $get_COMP->PRJLKOT;
			$data['default']['PRJCBNG']		= $get_COMP->PRJCBNG;
			$data['default']['PRJCURR']		= $get_COMP->PRJCURR;
			$data['default']['CURRRATE']	= $get_COMP->CURRRATE;
			$data['default']['PRJSTAT'] 	= $get_COMP->PRJSTAT;
			$data['default']['PRJNOTE'] 	= $get_COMP->PRJNOTE;
			$data['default']['isHO']		= $get_COMP->isHO;
			$data['default']['ISCHANGE']	= $get_COMP->ISCHANGE;
			$data['default']['REFCHGNO']	= $get_COMP->REFCHGNO;
			$data['default']['PRJCOST2'] 	= $get_COMP->PRJCOST2;
			$data['default']['CHGUSER'] 	= $get_COMP->CHGUSER;
			$data['default']['CHGSTAT'] 	= $get_COMP->CHGSTAT;
			$data['default']['PRJ_MNG'] 	= $get_COMP->PRJ_MNG;
			$data['default']['QTY_SPYR'] 	= $get_COMP->QTY_SPYR;
			$data['default']['PRC_STRK'] 	= $get_COMP->PRC_STRK;
			$data['default']['PRC_ARST'] 	= $get_COMP->PRC_ARST;
			$data['default']['PRC_MKNK'] 	= $get_COMP->PRC_MKNK;
			$data['default']['PRC_ELCT'] 	= $get_COMP->PRC_ELCT;
			$data['default']['PRJ_IMGNAME'] = $get_COMP->PRJ_IMGNAME;
			$data['default']['Patt_Year'] 	= $get_COMP->Patt_Year;
			 
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN402';
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
			
			$this->load->view('v_company/v_compprof/compprof_view', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function update_process()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			$DATE_CREATED	= date('Y-m-d H:i:s');
			
			$proj_Number			= $this->input->post('proj_Number');
			$PRJCODE 				= $this->input->post('PRJCODE');
			$PRJNAME 				= $this->input->post('PRJNAME');
			$PRJDATE				= date('Y-m-d',strtotime($this->input->post('PRJDATE')));
			$PRJDATE_CO				= date('Y-m-d',strtotime($this->input->post('PRJDATE_CO')));
			$PRJEDAT				= date('Y-m-d',strtotime($this->input->post('PRJEDAT')));
			$PRJCNUM 				= $this->input->post('PRJCNUM');
			$PRJCATEG 				= $this->input->post('PRJCATEG');
			$PRJOWN 				= $this->input->post('PRJOWN');
			$isHO 					= $this->input->post('isHO');
			$PRJCURR				= $this->input->post('PRJCURR');
			$PRJCOST 				= $this->input->post('PRJCOST');
			$PRJLOCT 				= $this->input->post('PRJLOCT');
			$PRJADD 				= $this->input->post('PRJADD');
			$PRJTELP 				= $this->input->post('PRJTELP');
			$PRJFAX 				= $this->input->post('PRJFAX');
			$PRJMAIL 				= $this->input->post('PRJMAIL');
			$PRJLKOT 				= $this->input->post('PRJLKOT');
			$PRJ_MNG				= $this->input->post('PRJ_MNG');
			$QTY_SPYR				= $this->input->post('QTY_SPYR');
			$PRJNOTE				= $this->input->post('PRJNOTE');
			$PRC_STRK				= $this->input->post('PRC_STRK');
			$PRC_ARST				= $this->input->post('PRC_ARST');
			$PRC_MKNK				= $this->input->post('PRC_MKNK');
			$PRC_ELCT				= $this->input->post('PRC_ELCT');
			$PRJSTAT 				= $this->input->post('PRJSTAT');
			
			$projectheader 	= array('PRJNAME'			=> $PRJNAME,
									'PRJLOCT'			=> $PRJLOCT,
									'PRJADD'			=> $PRJADD,
									'PRJTELP'			=> $PRJTELP,
									'PRJFAX'			=> $PRJFAX,
									'PRJMAIL'			=> $PRJMAIL,
									'PRJCATEG'			=> $PRJCATEG,
									'PRJOWN'			=> $PRJOWN,
									'PRJDATE'			=> $PRJDATE,
									'PRJEDAT'			=> $PRJEDAT,
									'PRJDATE_CO'		=> $PRJDATE_CO,
									'PRJBOQ'			=> $PRJCOST,
									'PRJCOST'			=> $PRJCOST,
									'PRJLKOT'			=> $PRJLKOT,
									'PRJCBNG'			=> $PRJCBNG,
									'PRJCURR'			=> $PRJCURR,
									'CURRRATE'			=> $CURRRATE,
									'PRJSTAT'			=> $PRJSTAT,
									'PRJNOTE'			=> $PRJNOTE,
									'PRJ_MNG'			=> $PRJ_MNG,
									'QTY_SPYR'			=> $QTY_SPYR,
									'PRC_STRK'			=> $PRC_STRK,
									'PRC_ARST'			=> $PRC_ARST,
									'PRC_MKNK'			=> $PRC_MKNK,
									'PRC_ELCT'			=> $PRC_ELCT);							
					$this->m_comprof->update($PRJCODE, $projectheader);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $proj_Number;
				$MenuCode 		= 'MN402';
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
			
			redirect('c_comprof/c_30Mp70f/');
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function do_upload()
	{
		$PRJCODE			= $this->input->post('PRJCODE');
		
		// CEK FILE
        $file 				= $_FILES['userfile'];
		$nameFile			= $_FILES["userfile"]["name"];
		$ext 				= end((explode(".", $nameFile)));
			
		if (!file_exists('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE))
		{
			mkdir('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE, 0777, true);
		}
		
		$file 						= $_FILES['userfile'];
		$file_name 					= $file['name'];
		$config['upload_path']   	= "assets/AdminLTE-2.0.5/project_image/$PRJCODE/"; 
		$config['allowed_types']	= 'gif|jpg|png'; 
		$config['overwrite'] 		= TRUE;
		$config['max_size']     	= 1000000; 
		$config['max_width']    	= 10024; 
		$config['max_height']    	= 10000;  
		$config['file_name']       	= $file['name'];
		
        $this->load->library('upload', $config);
		
        if ( ! $this->upload->do_upload('userfile')) 
		{
			//$data['Emp_ID']		= $Emp_ID;
			//$data['task'] 		= 'edit';
         }
         else 
		 {
            //$data['path']			= $file_name;
			//$data['Emp_ID']			= $Emp_ID;
			//$data['task'] 			= 'edit';
            //$data['showSetting']	= 0;
            $this->m_comprof->updatePict($PRJCODE, $nameFile);
         }
		 
         $sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_comprof/c_30Mp70f/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
}