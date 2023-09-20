<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Maret 2020
 * File Name	= C_mnotes.php
 * Location		= -
*/

class C_mnotes extends CI_Controller
{
	var $limit = 2;
	var $title = 'NKE ITSys';
	
 	function __construct()
 	{
 		parent::__construct();
 		$this->session->keep_flashdata('msg');
 	}

 	public function index() // USED
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_mnotes/c_mnotes/t53Mp/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function t53Mp($offset=0) // USED
	{
		$this->load->model('m_mnotes/m_mnotes', '', TRUE);
		$Emp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Notulen Rapat';
			$data['secAddURL'] 			= site_url('c_mnotes/c_mnotes/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_mnotes/c_mnotes/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 					= $this->m_mnotes->count_allTS($Emp_ID);
			$data["TSCount"] 			= $num_rows;
			$data['MenuCode'] 			= 'MN421';
			
			// Start of Pagination
			$config 					= array();
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"]		= 4;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_mnotes/c_mnotes/get_allTS');				
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open']	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link'] 		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close'] 	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link'] 		= '&gt;&gt;';
			// End of Pagination
	 
			$this->pagination->initialize($config);
	 
			$data['TSView'] 	= $this->m_mnotes->get_allTS($config["per_page"], $offset, $Emp_ID)->result();
			$data["pagination"] = $this->pagination->create_links();
			
			$this->load->view('v_mnotes/v_mnotes', $data);
		}
		else
		{
			redirect('login');
		}
	}

	function get_AllData() // GOOD
	{
		$this->load->model('m_mnotes/m_mnotes', '', TRUE);
		//$PRJCODE		= $_GET['id'];

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
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
			
			$columns_valid 	= array("NTLN_CODE",
									"NTLN_DATE",
									"NTLN_START", 
									"NTLN_END", 
									"NTLN_LOC", 
									"NTLN_TOPIC", 
									"NTLN_DESC", 
									"NTLN_USER",
									"NTLN_THEORY");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_mnotes->get_AllDataC($search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_mnotes->get_AllDataL($search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$NTLN_CODE 		= $dataI['NTLN_CODE'];
				$getTheory 		= site_url('c_mnotes/c_mnotes/download/?id='.$this->url_encryption_helper->encode_url($NTLN_CODE));
				$NTLN_DATE		= date('d M Y', strtotime($dataI['NTLN_DATE']));
				$NTLN_START		= date('H:i', strtotime($dataI['NTLN_START']));
				$NTLN_END		= date('H:i', strtotime($dataI['NTLN_END']));
				$NTLN_LOC 		= $dataI['NTLN_LOC'];
				$NTLN_TOPIC		= $dataI['NTLN_TOPIC'];
				$NTLN_DESC 		= $dataI['NTLN_DESC'];
				$NTLN_USER 		= $dataI['NTLN_USER'];
				$NTLN_THEORY 	= $dataI['NTLN_THEORY'];
				$NTLN_THEORY1	= "<label style='white-space:nowrap'>
								   	<a href='".$getTheory."' class='btn btn-success btn-xs' title='Download'>
										<i class='fa fa-download'></i>
								   	</a>
								   	</label>";
				if($NTLN_THEORY == '')
					$NTLN_THEORY = $NTLN_THEORY;
				else
					$NTLN_THEORY = $NTLN_THEORY1;

				$NTLN_CREATER	= $dataI['NTLN_CREATER'];
				$NTLN_CREATED	= $dataI['NTLN_CREATED'];
				
				//$CollCode	= "$PRJCODE~$INVT_NUM";
				$CollCode	= "$NTLN_CODE";
				$secUpd		= site_url('c_mnotes/c_mnotes/upN0t35/?id='.$this->url_encryption_helper->encode_url($CollCode));
				$urlViewNot	= site_url('c_mnotes/c_mnotes/viewNot/?id='.$this->url_encryption_helper->encode_url($CollCode));
                                    
				if($NTLN_CREATER == $DefEmp_ID)
				{
					$secAction	= 	"<input type='hidden' name='urlViewNot".$noU."' id='urlViewNot".$noU."' value='".$urlViewNot."'>
								<label style='white-space:nowrap'>
							   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Edit'>
									<i class='glyphicon glyphicon-pencil'></i>
							   	</a>
								<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='viewNot(".$noU.")' title='Lihat Hasil Rapat'>
									<i class='fa fa-eye'></i>
								</a>
								</label>";	
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlViewNot".$noU."' id='urlViewNot".$noU."' value='".$urlViewNot."'>
								<label style='white-space:nowrap'>
							   	<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='viewNot(".$noU.")' title='Lihat Hasil Rapat'>
									<i class='fa fa-eye'></i>
								</a>
								</label>";
				}
				
				$output['data'][] = array("$noU.",
										  $NTLN_DATE,
										  $NTLN_START,
										  $NTLN_END,
										  $NTLN_TOPIC,
										  $NTLN_THEORY,
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add() // USED
	{
		$this->load->model('m_mnotes/m_mnotes', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['MenuCode'] 		= 'MN421';
			$data['h2_title']		= 'Tambah Notulen Rapat';
			$data['main_view'] 		= 'v_mnotes/v_mnotes_sd_form';
			$data['form_action']	= site_url('c_mnotes/c_mnotes/add_process');
			$data['backURL'] 		= site_url('c_mnotes/c_mnotes/');
			$data['default']['VendCat_Code'] = '';
			
			$this->load->view('v_mnotes/v_mnotes_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add_process() // USED
	{
		$this->load->model('m_mnotes/m_mnotes', '', TRUE);
		
		// SET START DATE AND TIME
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$NTLN_DATE		= date('Y-m-d',strtotime($this->input->post('NTLN_DATE')));
		
		$NTLN_START 	= date('H:i:s',strtotime($this->input->post('NTLN_START')));
		$NTLN_END		= date('H:i:s',strtotime($this->input->post('NTLN_END')));

		$file 						    = $_FILES['NTLN_THEORY'];
  		$file_name 					    = $file['name'];
		$config['upload_path']          = 'uploads/Theory_Meeting';
        $config['allowed_types']        = 'pdf';
        $config['overwrite']		  	= true;
  		$config['max_size']       		= 1000;

        $this->load->library('upload', $config);

        if($file_name != '')
        {
        	if ($this->upload->do_upload('NTLN_THEORY'))
        	{
        		$data 		= array('upload_data' => $this->upload->data());
        		$file_name	= $data['upload_data']['file_name'];

        		$jobEmp = array('NTLN_CODE' 	=> $this->input->post('NTLN_CODE'),
						'NTLN_DATE'		=> $NTLN_DATE,
						'NTLN_START'	=> $NTLN_START,
						'NTLN_END'		=> $NTLN_END,
						'NTLN_LOC'		=> $this->input->post('NTLN_LOC'),
						'NTLN_TOPIC'	=> $this->input->post('NTLN_TOPIC'),
						'NTLN_DESC'		=> $this->input->post('NTLN_DESC'),
						'NTLN_USER'		=> $this->input->post('NTLN_USER'),
						'NTLN_THEORY'	=> $file_name,
						'NTLN_CREATER'	=> $DefEmp_ID,
						'NTLN_CREATED'	=> date('Y-m-d H:i:s'));
        		$this->m_mnotes->add($jobEmp);
        	}
        	else
        	{
        		$error = $this->upload->display_errors();
          		echo $this->session->set_flashdata('msg',$error);

          		$url			= site_url('c_mnotes/c_mnotes/add/?id='.$this->url_encryption_helper->encode_url($appName));
				redirect($url);
        	}
        }
        else
        {
        	$jobEmp = array('NTLN_CODE' 	=> $this->input->post('NTLN_CODE'),
						'NTLN_DATE'		=> $NTLN_DATE,
						'NTLN_START'	=> $NTLN_START,
						'NTLN_END'		=> $NTLN_END,
						'NTLN_LOC'		=> $this->input->post('NTLN_LOC'),
						'NTLN_TOPIC'	=> $this->input->post('NTLN_TOPIC'),
						'NTLN_DESC'		=> $this->input->post('NTLN_DESC'),
						'NTLN_USER'		=> $this->input->post('NTLN_USER'),
						//'NTLN_THEORY'	=> $file_name,
						'NTLN_CREATER'	=> $DefEmp_ID,
						'NTLN_CREATED'	=> date('Y-m-d H:i:s'));
        	$this->m_mnotes->add($jobEmp);
        }
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_mnotes/c_mnotes/index/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function download()
	{
		$this->load->model('m_mnotes/m_mnotes', '', TRUE);
		$this->load->helper('download');
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$NTLN_CODE	= $_GET['id'];
		$NTLN_CODE	= $this->url_encryption_helper->decode_url($NTLN_CODE);

		$path_download 	= "uploads/Theory_Meeting";
		$file 			= $this->db->select('NTLN_THEORY')->get_where('tbl_notulen_meetting', array('NTLN_CODE' => $NTLN_CODE))->row();


		force_download("$path_download/$file->NTLN_THEORY", NULL);
	}

	function viewNot()
	{
		$this->load->model('m_mnotes/m_mnotes', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$NTLN_CODE	= $_GET['id'];
		$NTLN_CODE	= $this->url_encryption_helper->decode_url($NTLN_CODE);

		$data['title'] 			= $appName;
		$rowVNot 				= $this->db->get_where('tbl_notulen_meetting', array('NTLN_CODE' => $NTLN_CODE))->row();
		$data['NTLN_TOPIC']		= $rowVNot->NTLN_TOPIC;
		$data['NTLN_DATE']		= date('d M Y', strtotime($rowVNot->NTLN_DATE));
		$data['NTLN_DESC']		= $rowVNot->NTLN_DESC;

		$this->load->view('v_mnotes/v_mnotes_NotV', $data);
	}
	
	function upN0t35() // USED
	{
		$this->load->model('m_mnotes/m_mnotes', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$NTLN_CODE	= $_GET['id'];
		$NTLN_CODE	= $this->url_encryption_helper->decode_url($NTLN_CODE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Notule Rapat';
			$data['main_view'] 		= 'v_mnotes/v_mnotes_sd_form';
			$data['form_action']	= site_url('c_mnotes/c_mnotes/update_process');
			$data['MenuCode'] 		= 'MN421';
			
			//$data['link'] 			= array('link_back' => anchor('c_mnotes/c_mnotes/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_mnotes/c_mnotes/');
			
			$getTSEmp = $this->m_mnotes->get_TSEmp_Bycode($NTLN_CODE)->row();
			
			$data['default']['NTLN_CODE'] 		= $getTSEmp->NTLN_CODE;
			$data['default']['NTLN_DATE'] 		= $getTSEmp->NTLN_DATE;
			$data['default']['NTLN_START'] 		= $getTSEmp->NTLN_START;
			$data['default']['NTLN_END'] 		= $getTSEmp->NTLN_END;
			$data['default']['NTLN_LOC']		= $getTSEmp->NTLN_LOC;
			$data['default']['NTLN_TOPIC'] 		= $getTSEmp->NTLN_TOPIC;
			$data['default']['NTLN_DESC']		= $getTSEmp->NTLN_DESC;
			$data['default']['NTLN_USER'] 		= $getTSEmp->NTLN_USER;
			$data['default']['NTLN_THEORY']		= $getTSEmp->NTLN_THEORY;
			
			$this->load->view('v_mnotes/v_mnotes_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_mnotes/m_mnotes', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$NTLN_CODE 		= $this->input->post('NTLN_CODE');
		$NTLN_DATE		= date('Y-m-d',strtotime($this->input->post('NTLN_DATE')));
		
		$NTLN_START 	= date('H:i:s',strtotime($this->input->post('NTLN_START')));
		$NTLN_END		= date('H:i:s',strtotime($this->input->post('NTLN_END')));

		$file 						    = $_FILES['NTLN_THEORY'];
  		$file_name 					    = $file['name'];
		$config['upload_path']          = 'uploads/Theory_Meeting';
        $config['allowed_types']        = 'pdf';
        $config['overwrite']		  	= true;
  		$config['max_size']       		= 1000;

        $this->load->library('upload', $config);

        if($file_name != '')
        {
        	if ($this->upload->do_upload('NTLN_THEORY'))
        	{
        		$data 		= array('upload_data' => $this->upload->data());
        		$file_name	= $data['upload_data']['file_name'];

        		$jobEmp = array('NTLN_CODE' 	=> $NTLN_CODE,
								'NTLN_DATE'		=> $NTLN_DATE,
								'NTLN_START'	=> $NTLN_START,
								'NTLN_END'		=> $NTLN_END,
								'NTLN_LOC'		=> $this->input->post('NTLN_LOC'),
								'NTLN_TOPIC'	=> $this->input->post('NTLN_TOPIC'),
								'NTLN_DESC'		=> $this->input->post('NTLN_DESC'),
								'NTLN_USER'		=> $this->input->post('NTLN_USER'),
								'NTLN_THEORY'	=> $file_name,
								'NTLN_UPDATER'	=> $DefEmp_ID,
								'NTLN_UPDATED'	=> date('Y-m-d H:i:s'));
        	}
        	else
        	{
        		$error = $this->upload->display_errors();
          		echo $this->session->set_flashdata('msg',$error);

          		$url			= site_url('c_mnotes/c_mnotes/upN0t35/?id='.$this->url_encryption_helper->encode_url($NTLN_CODE));
				redirect($url);
        	}
        }
        else
        {
        	$jobEmp = array('NTLN_CODE' 	=> $NTLN_CODE,
							'NTLN_DATE'		=> $NTLN_DATE,
							'NTLN_START'	=> $NTLN_START,
							'NTLN_END'		=> $NTLN_END,
							'NTLN_LOC'		=> $this->input->post('NTLN_LOC'),
							'NTLN_TOPIC'	=> $this->input->post('NTLN_TOPIC'),
							'NTLN_DESC'		=> $this->input->post('NTLN_DESC'),
							'NTLN_USER'		=> $this->input->post('NTLN_USER'),
							//'NTLN_THEORY'	=> $file_name,
							'NTLN_UPDATER'	=> $DefEmp_ID,
							'NTLN_UPDATED'	=> date('Y-m-d H:i:s'));
        }
						
		$this->m_mnotes->update($NTLN_CODE, $jobEmp);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_mnotes/c_mnotes/index/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
}