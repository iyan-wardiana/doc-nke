<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 9 Februari 2017
 * File Name	= O180c2gner.php
 * Location		= -
*/

class O180c2gner extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_project/m_project_owner/m_project_owner', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_journal/m_journal', '', TRUE);
	
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
	
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/o180c2gner/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function i180c2gdx($offset=0)
	{
		$this->load->model('m_project/m_project_owner/m_project_owner', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project Owner';
			$data['secAddURL'] 			= site_url('c_project/o180c2gner/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_project/o180c2gner/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 					= $this->m_project_owner->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
			$data['MenuCode'] 			= 'MN230';
			
			// Start of Pagination
			$config 					= array();
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"]		= 4;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_project/o180c2gner/get_last_ten_owner');				
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
	 
			$data['viewOwner'] = $this->m_project_owner->get_last_ten_owner($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN230';
				$TTR_CATEG		= 'L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_project_owner/project_owner', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}

  	function get_AllData() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Active')$Active = $LangTransl;
			if($TranslCode == 'Inactive')$Inactive = $LangTransl;
		endforeach;
		
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
			
			$columns_valid 	= array("own_Code", 
									"own_Name", 
									"own_Add1",
									"own_CP_Name");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_project_owner->get_AllDataC($search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_project_owner->get_AllDataL($search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$own_Code	= $dataI['own_Code'];
				$own_Title	= $dataI['own_Title'];
				$ownNm		= $dataI['own_Name'];
				if($own_Title != '')
					$ownNm 	= $own_Title.". ".$dataI['own_Name'];

				$own_Add1	= str_replace(array("\r","\n","<br>")," ",$dataI['own_Add1']);
				/*$own_Add1	= str_replace(array("\r","\n","<br>")," ",$dataI['own_Add1']);
				$own_Add1V	= '';
				if(strlen($own_Add1) > 70)
				{
					$MAXCHAR_1		= 70;
					$CUT_TEXT_1		= substr($own_Add1, 0, $MAXCHAR_1);
					if ($own_Add1{$MAXCHAR_1 - 1} != ' ') 
					{
						$NEW_POS_1 	= strrpos($CUT_TEXT_1, ' ');
						$CUT_TEXT_1 = substr($own_Add1, 1, $NEW_POS_1);
					}
					$own_Add1V	= $CUT_TEXT_1 . "...";
				}
				else
				{
					$own_Add1V	= $own_Add1;
				}
				$own_Add1V		= $own_Add1V ?: "-";*/

				/*$own_Add2	= str_replace(array("\r","\n","<br>")," ",$dataI['own_Add2']);
				$own_Add2V	= '';
				if(strlen($own_Add2) > 70)
				{
					$MAXCHAR_1		= 70;
					$CUT_TEXT_1		= substr($own_Add2, 0, $MAXCHAR_1);
					if ($own_Add2{$MAXCHAR_1 - 1} != ' ') 
					{
						$NEW_POS_1 	= strrpos($CUT_TEXT_1, ' ');
						$CUT_TEXT_1 = substr($own_Add2, 1, $NEW_POS_1);
					}
					$own_Add2V	= $CUT_TEXT_1 . "...";
				}
				else
				{
					$own_Add2V	= $own_Add2;
				}
				
				if($own_Add2V != '')
				{
					$own_Add2X = "<br>$own_Add2V";
					$own_Add2V	= $own_Add2X ?: "-";
				}*/

				$ownAdd 	= wordwrap($own_Add1,100,"<br>\n");
				
				$own_Telp	= $dataI['own_Telp'] ?: "-";
				$own_CPNm	= $dataI['own_CP_Name'] ?: "-";
				$own_CP		= $dataI['own_CP'] ?: "-";
				$own_Status	= $dataI['own_Status'];
				if($own_Status == 1)
				{
					$own_StatDesc	= $Active;
					$STATCOL		= 'success';
				}
				else
				{
					$own_StatDesc	= $Inactive;
					$STATCOL		= 'danger';
				}

				$own_Email	= $dataI['own_Email'];
				if($own_Email == '') $own_Email = "-";
				$own_Inst	= $dataI['own_Inst'];
				if($own_Inst == 'S')
					$instD 	= "Swasta";
				else
					$instD 	= "Pemerintah";

				$secUpd		= site_url('c_project/o180c2gner/u180c2gdt/?id='.$this->url_encryption_helper->encode_url($own_Code));
				$sureDelete	= site_url('c_project/o180c2gner/u180c2gdt/?id='.$this->url_encryption_helper->encode_url($own_Code));
				
				$IMGFILNMX  = "";
			    $sqlGetIMG  = "SELECT IMGO_FILENAME, IMGO_FILENAMEX FROM tbl_owner_img WHERE IMGO_CUSTCODE = '$own_Code'";
			    $resGetIMG  = $this->db->query($sqlGetIMG)->result();
			    foreach($resGetIMG as $rowGIMG) :
			        $IMGFILNM  = $rowGIMG->IMGO_FILENAME;
			        $IMGFILNMX = $rowGIMG->IMGO_FILENAMEX;
			    endforeach;
			    $IMGFILNM   = $IMGFILNMX ?: "";
			    
			    $imgLoc     = base_url('assets/AdminLTE-2.0.5/own_img/'.$own_Code.'/'.$IMGFILNM);
			    if (!file_exists('assets/AdminLTE-2.0.5/own_img/'.$own_Code))
			    {
			        $imgLoc = base_url('assets/AdminLTE-2.0.5/own_img/username.jpg');
			    }

				$secDelIcut = base_url().'index.php/__l1y/trashOwn/?id=';
				$delID 		= "$secDelIcut~$own_Code";
				$secActIcut = base_url().'index.php/__l1y/acthOwn/?id=';
				$actID 		= "$secActIcut~$own_Code";
                                    
				if($own_Status == 2) 
				{
					$secPrint	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlAct".$noU."' id='urlAct".$noU."' value='".$actID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Aktifkan' onClick='reActivated(".$noU.")'>
                                        <i class='glyphicon glyphicon-repeat'></i>
                                    </a>
									</label>";
				}
				else
				{
					$secPrint	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlAct".$noU."' id='urlAct".$noU."' value='".$actID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-success btn-xs' title='Non aktifkan' onClick='deleteDOC(".$noU.")'>
                                        <i class='glyphicon glyphicon-ok'></i>
                                    </a>
									</label>";
				}
				
				$output['data'][] 	= array($noU,
											$own_Code,
										  	"$ownNm
										  	<div>
										  		<p class='text-muted'>
										  			$instD
										  		</p>
										  	</div>",
										  	"$ownAdd<br>
										  	<strong><i class='fa fa-user margin-r-5'></i></strong>&nbsp;$own_CPNm ($own_CP), &nbsp;&nbsp;
										  	<strong><i class='fa fa-phone margin-r-5'></i></strong>$own_Telp<br>
										  	<strong><i class='fa fa-envelope-square margin-r-5'></i></strong>$own_Email",
										  	"<img class='direct-chat-img' src='".$imgLoc."' style=border:groove; border-color:#0C3' >",
										  	$secPrint);

				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function add() // OK
	{
		$this->load->model('m_project/m_project_owner/m_project_owner', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN230';
				$MenuCode			= 'MN230';
				$data["MenuCode"] 	= 'MN230';
				$data["MenuApp"] 	= 'MN230';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Owner';
			$data['main_view'] 		= 'v_project/v_project_owner/project_owner_sd_form';
			$data['form_action']	= site_url('c_project/o180c2gner/add_process');
			//$data['link'] 			= array('link_back' => anchor('c_project/o180c2gner/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_project/o180c2gner/');
			$data['default']['VendCat_Code'] = '';
			
			$data['viewDocPattern'] = $this->m_project_owner->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN230';
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
			
			$this->load->view('v_project/v_project_owner/project_owner_form', $data);
		}
		else
		{
			redirect('__I1y'); // by. DH on 16 Maret 14 : Failed, so ... load back to login
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_project/m_project_owner/m_project_owner', '', TRUE);
		
		$input	= $this->input->post('own_Add1');
		// memecah string input berdasarkan karakter '\r\n\r\n'
		$pecah 	= explode("\n", $input);
		// string kosong inisialisasi
		$text 	= "";
		$vgv	= count($pecah);
		// untuk setiap substring hasil pecahan, sisipkan <p> di awal dan </p> di akhir
		// lalu menggabungnya menjadi satu string utuh $text
		for ($i=0; $i<=count($pecah)-1; $i++)
		{
			$part = str_replace($pecah[$i], "".$pecah[$i]."<br>", $pecah[$i]);
			$text .= $part;
		}
		$own_Add1New	= $text;
		
		$input2	= $this->input->post('own_Add2');
		$pecah2	= explode("\n", $input2);
		$text2 	= "";
		$vgv2	= count($pecah2);
		// untuk setiap substring hasil pecahan, sisipkan <p> di awal dan </p> di akhir
		// lalu menggabungnya menjadi satu string utuh $text
		for ($i=0; $i<=count($pecah2)-1; $i++)
		{
			$part2 = str_replace($pecah2[$i], "".$pecah2[$i]."<br>", $pecah2[$i]);
			$text2 .= $part2;
		}
		$own_Add2New	= $text2;
			
		$owner 	= array('own_Code' 		=> $this->input->post('own_Code'),
						'own_Title'		=> $this->input->post('own_Title'),
						'own_Inst'		=> $this->input->post('own_Inst'),
						'own_Name'		=> $this->input->post('own_Name'),
						'own_Add1'		=> $own_Add1New,
						'own_Add2'		=> $own_Add2New,
						'own_Telp'		=> $this->input->post('own_Telp'),
						'own_CP'		=> $this->input->post('own_CP'),
						'own_CP_Name'	=> $this->input->post('own_CP_Name'),
						'own_Email'		=> $this->input->post('own_Email'),
						'own_ACC_ID'	=> $this->input->post('own_ACC_ID'),
						'own_ACC_ID2'	=> $this->input->post('own_ACC_ID2'),
						'own_ACC_ID3'	=> $this->input->post('own_ACC_ID3'),
						'own_ACC_ID4'	=> $this->input->post('own_ACC_ID4'),
						'own_ACC_ID5'	=> $this->input->post('own_ACC_ID5'),
						'own_Status'	=> $this->input->post('own_Status'),
						'own_ImgNm'		=> 'username.jpg',
						'patt_No'		=> $this->input->post('patt_No'));
		$this->m_project_owner->add($owner);
			
		$ownimg	= array('IMGO_CUSTCODE' 	=> $this->input->post('own_Code'),
						'IMGO_FILENAME'		=> 'username',
						'IMGO_FILENAMEX'	=> 'username.jpg');
		$this->m_project_owner->add3($ownimg);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('own_Code');
				$MenuCode 		= 'MN230';
				$TTR_CATEG		= 'C';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
		
		$url			= site_url('c_project/o180c2gner/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function do_upload()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$own_Code 			= $this->input->post('own_Code');
		
		// CEK FILE
        $file 				= $_FILES['userfile'];
		$nameFile			= $_FILES["userfile"]["name"];
		$ext 				= end((explode(".", $nameFile)));
       	$fileInpName 		= $this->input->post('userfile');

		if (!file_exists('assets/AdminLTE-2.0.5/own_img/'.$own_Code))
		{
			mkdir('assets/AdminLTE-2.0.5/own_img/'.$own_Code, 0777, true);
		}
		
		$file 						= $_FILES['userfile'];
		$file_name 					= $file['name'];
		$config['upload_path']   	= "assets/AdminLTE-2.0.5/own_img/$own_Code/"; 
		$config['allowed_types']	= 'gif|jpg|png'; 
		$config['overwrite'] 		= TRUE;
		$config['max_size']     	= 1000000; 
		$config['max_width']    	= 10024; 
		$config['max_height']    	= 10000;  
		$config['file_name']       	= $file['name'];
		
        $this->load->library('upload', $config);
		
        if ( ! $this->upload->do_upload('userfile')) 
		{
			$data['own_Code']		= $own_Code;
			$data['task'] 			= 'edit';
         }
         else 
		 {
            $data['path']			= $file_name;
			$data['own_Code']		= $own_Code;
			$data['task'] 			= 'edit';
            $data['showSetting']	= 0;

            $nameFilex 				= str_replace(" ", "_", $nameFile);
            $this->m_project_owner->updateProfPict($own_Code, $nameFilex, $fileInpName);
         }
		 
		$url 	= site_url('c_project/o180c2gner/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function u180c2gdt() // OK
	{
		$this->load->model('m_project/m_project_owner/m_project_owner', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$ownCode	= $_GET['id'];
		$ownCode	= $this->url_encryption_helper->decode_url($ownCode);
			
		$LangID 	= $this->session->userdata['LangID'];
		
		// GET MENU DESC
			$mnCode				= 'MN230';
			$MenuCode			= 'MN230';
			$data["MenuCode"] 	= 'MN230';
			$data["MenuApp"] 	= 'MN230';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Project Owner | Edit Project Owner';
			$data['main_view'] 		= 'v_project/v_project_owner/project_owner_sd_form';
			$data['form_action']	= site_url('c_project/o180c2gner/update_process');
			$data['MenuCode'] 		= 'MN230';
			
			//$data['link'] 			= array('link_back' => anchor('c_project/o180c2gner/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_project/o180c2gner/');
			
			$getvendor = $this->m_project_owner->get_owner_by_code($ownCode)->row();
			
			$data['default']['own_Code'] 	= $getvendor->own_Code;
			$data['default']['own_Title']	= $getvendor->own_Title;
			$data['default']['own_Inst']	= $getvendor->own_Inst;
			$data['default']['own_Name'] 	= $getvendor->own_Name;
			$data['default']['own_Add1'] 	= $getvendor->own_Add1;
			$data['default']['own_Add2'] 	= $getvendor->own_Add2;
			$data['default']['own_Telp'] 	= $getvendor->own_Telp;
			$data['default']['own_CP'] 		= $getvendor->own_CP;
			$data['default']['own_CP_Name'] = $getvendor->own_CP_Name;
			$data['default']['own_Email'] 	= $getvendor->own_Email;
			$data['default']['own_ACC_ID'] 	= $getvendor->own_ACC_ID;
			$data['default']['own_ACC_ID2'] = $getvendor->own_ACC_ID2;
			$data['default']['own_ACC_ID3'] = $getvendor->own_ACC_ID3;
			$data['default']['own_ACC_ID4'] = $getvendor->own_ACC_ID4;
			$data['default']['own_ACC_ID5'] = $getvendor->own_ACC_ID5;
			$data['default']['own_Status'] 	= $getvendor->own_Status;
			$data['default']['patt_No'] 	= $getvendor->patt_No;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getvendor->own_Code;
				$MenuCode 		= 'MN230';
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
			
			$this->load->view('v_project/v_project_owner/project_owner_form', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function update_process() // OK
	{	
		$this->load->model('m_project/m_project_owner/m_project_owner', '', TRUE);
			
		$input	= $this->input->post('own_Add1');
		// memecah string input berdasarkan karakter '\r\n\r\n'
		$pecah 	= explode("\n", $input);
		// string kosong inisialisasi
		$text 	= "";
		$vgv	= count($pecah);
		// untuk setiap substring hasil pecahan, sisipkan <p> di awal dan </p> di akhir
		// lalu menggabungnya menjadi satu string utuh $text
		for ($i=0; $i<=count($pecah)-1; $i++)
		{
			$part = str_replace($pecah[$i], "".$pecah[$i]."<br>", $pecah[$i]);
			$text .= $part;
		}
		$own_Add1New	= $text;
		
		$input2	= $this->input->post('own_Add2');
		$pecah2	= explode("\n", $input2);
		$text2 	= "";
		$vgv2	= count($pecah2);
		// untuk setiap substring hasil pecahan, sisipkan <p> di awal dan </p> di akhir
		// lalu menggabungnya menjadi satu string utuh $text
		for ($i=0; $i<=count($pecah2)-1; $i++)
		{
			$part2 = str_replace($pecah2[$i], "".$pecah2[$i]."<br>", $pecah2[$i]);
			$text2 .= $part2;
		}
		$own_Add2New	= $text2;
		
		$own_Status		= 1;
		
		$own_Code	= $this->input->post('own_Code');
			
		$owner = array('own_Title'		=> $this->input->post('own_Title'),
						'own_Name'		=> $this->input->post('own_Name'),
						'own_Inst'		=> $this->input->post('own_Inst'),
						'own_Add1'		=> $own_Add1New,
						'own_Add2'		=> $own_Add2New,
						'own_Telp'		=> $this->input->post('own_Telp'),
						'own_CP'		=> $this->input->post('own_CP'),
						'own_CP_Name'	=> $this->input->post('own_CP_Name'),
						'own_Email'		=> $this->input->post('own_Email'),
						'own_ACC_ID'	=> $this->input->post('own_ACC_ID'),
						'own_ACC_ID2'	=> $this->input->post('own_ACC_ID2'),
						'own_ACC_ID3'	=> $this->input->post('own_ACC_ID3'),
						'own_ACC_ID4'	=> $this->input->post('own_ACC_ID4'),
						'own_ACC_ID5'	=> $this->input->post('own_ACC_ID5'),
						'own_Status'	=> $this->input->post('own_Status'),
						'patt_No'		=> $this->input->post('patt_No'));
						
		$this->m_project_owner->update($own_Code, $owner);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $own_Code;
				$MenuCode 		= 'MN230';
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
		
		$url			= site_url('c_project/o180c2gner/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	/*function delete($own_Code)
	{
		$owner = array('own_Status'		=> $this->input->post('own_Status'));
		$this->m_project_owner->delete($this->input->post('chkDetail'));
		$this->session->set_flashdata('message', 'Data succesfull deleted.');
		
		redirect('c_project/o180c2gner/');
	}*/
	
	/*function get_last_ten_owner_src($offset=0) // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$MyAppName    			= $this->session->userdata['SessAppTitle']['app_title_name'];
			//$DefProj_Code			= $this->session->userdata['dtSessSrc2']['selSearchproj_Code'];
			$DefProj_Code			= $this->session->userdata['userSessProject']['userprojSession'];
								
			$data['title'] 			= $MyAppName;
			$data['h2_title'] 		= 'Project Owner';
			$data['main_view'] 		= 'v_project/v_project_owner/project_owner_sd';	
			$data['moffset'] 		= $offset;
			$data['perpage'] 		= 20;
			$data['theoffset'] 		= 0;
			
			$data['srch_url'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/o180c2gner_sd'),'get_last_ten_owner_src');
			
			$data['selSearchType'] 	= $this->input->post('selSearchType');
			$data['txtSearch'] 		= $this->input->post('txtSearch');
			$data['selOwnStatus']	= $this->input->post('selOwnStatus');
			
			if (isset($_POST['submitSrch']))
			{
				$selSearchType	= $this->input->post('selSearchType');
				$txtSearch 		= $this->input->post('txtSearch');
				$selOwnStatus 	= $this->input->post('selOwnStatus');
				$VendStat	 	= $this->input->post('selOwnStatus');
				
				$dataSessSrc = array(
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'),
					'selOwnStatus' => $this->input->post('selOwnStatus'));
					
				$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
				
				$dataSessSrc   = $this->session->userdata('dtSessSrc1');
			}
			else
			{
				$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
				$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];

				$selOwnStatus      = $this->session->userdata['dtSessSrc1']['selOwnStatus'];
				
				$dataSessSrc = array(
					'selSearchType' => $this->session->userdata['dtSessSrc1']['selSearchType'],
					'txtSearch' => $this->session->userdata['dtSessSrc1']['txtSearch'],
					'selOwnStatus' => $this->session->userdata['dtSessSrc1']['selOwnStatus']);
					
				$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			}
			
			if($selSearchType == 'OwnCode')
			{
				$num_rows = $this->m_project_owner->count_all_num_rows_VCode($txtSearch, $VendStat);
			}
			else
			{
				$num_rows = $this->m_project_owner->count_all_num_rows_VName($txtSearch, $VendStat);
			}			
			
			$data["recordcount"] = $num_rows;
			
			// Start of Pagination
			$config 					= array();
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"]		= 4;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_project/o180c2gner/get_last_ten_owner');				
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
	 
			//$data['viewvendor'] = $this->m_project_owner->get_last_ten_owner($config["per_page"], $offset)->result();
			
			if($selSearchType == 'OwnCode')
			{
				$data['viewOwner'] = $this->m_project_owner->get_last_ten_owner_VCode($config["per_page"], $offset, $txtSearch, $VendStat)->result();
			}
			else
			{
				$data['viewOwner'] = $this->m_project_owner->get_last_ten_owner_VName($config["per_page"], $offset, $txtSearch, $VendStat)->result();
			}
			
			$data["pagination"] = $this->pagination->create_links();
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__I1y'); // by. DH on 16 Maret 14 : Failed, so ... load back to login
		}
	}*/
}