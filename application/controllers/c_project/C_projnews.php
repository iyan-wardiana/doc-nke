<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 Juni 2021
 * File Name	= C_projnews.php
 * Location		= -
*/

class C_projnews extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();

		setlocale(LC_ALL, 'id-ID', 'id_ID');

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
	
 	public function index() // OK
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url		= site_url('c_project/c_projnews/prj180d0blst/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180d0blst() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN306';
				$MenuCode			= 'MN306';
				$data["MenuCode"] 	= 'MN306';
				$data["MenuApp"] 	= 'MN242';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN306';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_projnews/galnews/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list_news';
			if($this->session->userdata['nSELP'] == 0)
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				$this->load->view($data["secVIEW"], $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function galnews() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN306';
			$MenuCode			= 'MN306';
			$data["MenuCode"] 	= 'MN306';
			$data["MenuApp"] 	= 'MN242';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$collDATA		= $_GET['id'];
			$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);	
			$EXP_COLLD		= explode('~', $EXP_COLLD1);	
			$C_COLLD1		= count($EXP_COLLD);
			
			if($C_COLLD1 > 1)
			{
				$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
				$key		= $EXP_COLLD[0];
				$PRJCODE	= $EXP_COLLD[1];
				$mxLS		= $EXP_COLLD[2];
				$end		= $EXP_COLLD[3];
				$start		= 0;
			}
			else
			{
				$key		= '';
				$PRJCODE	= $EXP_COLLD1;
				$start		= 0;
				$end		= 50;
			}
			$data["url_search"] = site_url('c_project/c_projnews/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$sqlPrj 		= "SELECT PRJLOCT, PRJNAME, PRJ_IMGNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$rPrj 			= $this->db->query($sqlPrj)->result();
			foreach($rPrj as $rwPrj) :
				$PRJNAME 	= $rwPrj->PRJNAME;
				$PRJLOCT 	= $rwPrj->PRJLOCT;
				$PRJ_IMGNAME= $rwPrj->PRJ_IMGNAME;
			endforeach;

			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_projnews/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_projnews/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			$MenuCode 			= 'MN306';
			$data["MenuCode"] 	= 'MN306';
			$data['PRJNAME']	= $PRJNAME;
			$data['PRJLOCT']	= $PRJLOCT;
			$data['PRJ_IMGNAME']= $PRJ_IMGNAME;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN306';
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
			
			$this->load->view('v_project/v_project_news/v_project_news', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function svForm()
	{
		date_default_timezone_set("Asia/Jakarta");

		$prjcode 		= $_POST['prjcode'];
		$empid 			= $_POST['empid'];
		$emp_msg 		= $_POST['emp_msg'];
		//$userfile 		= $_POST['userfile'];

		$info_code 		= date('YmdHis');

		$empname 		= "-";
		$s_emp			= "SELECT CONCAT(First_Name, ' ', Last_Name) AS compl_nm FROM tbl_employee WHERE Emp_ID = '$empid' LIMIT 1";
		$r_emp			= $this->db->query($s_emp)->result();
		foreach($r_emp as $rw_emp) :
			$empname 	= $rw_emp->compl_nm;
		endforeach;

		$s_01 			= "UPDATE tbl_project_liveinfo SET islast = 0 WHERE prjcode = '$prjcode'";
		$this->db->query($s_01);

		$s_02 			= "INSERT INTO tbl_project_liveinfo (info_code, prjcode, emp_id, emp_name, emp_msg, islast)
							VALUES ('$info_code', '$prjcode', '$empid', '$empname', '$emp_msg', 1)";
		$this->db->query($s_02);

		$countfiles 	= count($_FILES['file_input']['name']);
		for($i=0;$i<$countfiles;$i++)
		{
			if (!file_exists('assets/AdminLTE-2.0.5/project_image/'.$prjcode))
			{
				mkdir('assets/AdminLTE-2.0.5/project_image/'.$prjcode, 0777, true);
			}

			if (!file_exists('assets/AdminLTE-2.0.5/project_image/'.$prjcode.'/prjlivinfo'))
			{
				mkdir('assets/AdminLTE-2.0.5/project_image/'.$prjcode.'/prjlivinfo', 0777, true);
			}

				$filename1 	= $_FILES['file_input']['name'][$i];
				$filename 	= str_replace(" ","_", $filename1);
				if($filename != '')
				{
					move_uploaded_file($_FILES['file_input']['tmp_name'][$i],'assets/AdminLTE-2.0.5/project_image/'.$prjcode.'/prjlivinfo/'.$filename);

				$s_03 			= "INSERT INTO tbl_project_liveinfopic (info_code, prjcode, picture_name)
									VALUES ('$info_code', '$prjcode', '$filename')";
				$this->db->query($s_03);
			}
		}

		$s_pinfo		= "SELECT A.*, B.imgemp_filenameX AS file_nm FROM tbl_project_liveinfo A
								LEFT JOIN tbl_employee_img B ON A.emp_id = B.imgemp_empid
							WHERE A.prjcode = '$prjcode' AND A.islast = 1 ORDER BY A.created DESC";
		$r_pinfo		= $this->db->query($s_pinfo)->result();
		foreach($r_pinfo as $rw_pinfo) :
			$emp_id		= $rw_pinfo->emp_id;
			$emp_name	= $rw_pinfo->emp_name;
			$emp_msg	= $rw_pinfo->emp_msg;
			$islast		= $rw_pinfo->islast;
			$created	= $rw_pinfo->created;
			$file_nm	= $rw_pinfo->file_nm;

			$dateIND1 	= new DateTime($created);
			$dateIND 	= strftime('%A', $dateIND1->getTimestamp());

			$crtDV		= strftime('%d %B %Y', strtotime($created));
			$crtTV		= date('H:i', strtotime($created));

			$s_img		= "SELECT Pos_Code FROM tbl_employee WHERE Emp_ID = '$emp_id'";
			$r_img		= $this->db->query($s_img)->result();
			foreach($r_img as $rw_img) :
				$Pos_Code 	= $rw_img->Pos_Code;
			endforeach;

			$POSNM  	= "-";
			$sqlDEP 	= "SELECT POSS_NAME FROM tbl_position_str WHERE POSS_CODE = '$Pos_Code'";
			$resDEP 	= $this->db->query($sqlDEP)->result();
			foreach($resDEP as $rowDEP) :
				$POSNM 	= $rowDEP->POSS_NAME;
			endforeach;

			$imgLoc		= base_url('assets/AdminLTE-2.0.5/emp_image/'.$emp_id.'/'.$file_nm);
			if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$emp_id))
			{
				$imgLoc	= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
			}

			$s_imgPC	= "tbl_project_liveinfopic WHERE info_code = '$info_code' AND prjcode = '$prjcode'";
			$r_imgPC	= $this->db->count_all($s_imgPC);
			echo 	"<div class='post'>
						<div class='user-block'>
							<img class='img-circle img-bordered-sm' src='".$imgLoc."' alt='user image'>
							<span class='username'>
								<a href='#'>".$emp_name."</a>
								<!-- <a href='#' class='pull-right btn-box-tool'><i class='fa fa-clock-o'></i></a> -->
							</span>
							<span class='description'>".$POSNM." - ".$crtTV."<div class='pull-right'>".$dateIND.', '.$crtDV."</div></span>
						</div>
						<p>
							".$emp_msg."
						</p>
						<div class='row margin-bottom'>
							";
								if($r_imgPC > 0)
								{
									$i 			= 0;
									$s_imgP		= "SELECT picture_name FROM tbl_project_liveinfopic WHERE info_code = '$info_code' AND prjcode = '$prjcode'";
									$r_imgP		= $this->db->query($s_imgP)->result();
									foreach($r_imgP as $rw_imgP) :
										$i 		= $i+1;

										$picInf = $rw_imgP->picture_name;
										$imgP	= base_url('assets/AdminLTE-2.0.5/project_image/'.$prjcode.'/prjlivinfo/'.$picInf);
										if($i==1) { ?>
											<div class="col-sm-6">
						                      	<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
						                    </div>
										<?php } ?>
										<?php if($i == 2) { ?>
						                    <div class="col-sm-6">
												<div class="row">
													<div class="col-sm-6">
														<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
														<?php } if($i == 3) { ?>
														<br>
														<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
													</div>
													<?php } if($i == 4) { ?>
														<div class="col-sm-6">
															<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
															<?php } if($i == 5) { ?>
																<br>
																<img class="img-responsive" src="<?=$imgP?>" alt="Photo">
														</div>
												</div>
											</div>
										<?php }
									endforeach;
								}
							"
						</div>
						<ul class='list-inline' style='display: none'>
							<li><a href='#' class='link-black text-sm'><i class='fa fa-clock-o margin-r-5'></i>'.$dateIND.', '.$crtDV.'</a></li>
							<li class='pull-right'>
							<a href='#' class='link-black text-sm'>".$crtTV." WIB</a></li>
						</ul>
					</div>
					";
		endforeach;
	}
}