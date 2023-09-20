<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 7 Februari 2017
	* File Name		= Listproject.php
	* Location		= -
*/

class Lst180c2hprj extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();

		setlocale(LC_ALL, 'id-ID', 'id_ID');
		
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
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
	
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/lst180c2hprj/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function projectlist() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN126';
				$data["MenuApp"] 	= 'MN126';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN126';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();	
			$data["secURL"] 	= "c_project/lst180c2hprj/i180c2gdx/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list';
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
	
	function i180c2gdx($offset=0)
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			$data['secAddURL'] 	= site_url('c_project/lst180c2hprj/add/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN126';
				$data["MenuApp"] 	= 'MN126';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN126';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();	
			$data["secURL"] 	= "c_project/lst180c2hprj/i180c2gdx/?id=";
			
			$data["secVIEW"]	= 'v_project/v_listproject/listproject';
			//$data["secVIEW"]	= 'v_projectlist/project_list';
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

  	function get_AllDataPRJ() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$secURL		= $_GET['id'];
		$mnCode		= $_GET['mnCode'];
		$MenuApp	= $_GET['MenuApp'];
		$jrnCat		= $_GET['jrnCat'];
		$jrnType	= $_GET['jrnType'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
    		if($TranslCode == 'usageCateg')$usageCateg = $LangTransl;
    		if($TranslCode == 'Remarks')$Remarks = $LangTransl;
    		if($TranslCode == 'Periode')$Periode = $LangTransl;
    		if($TranslCode == 'ProjectManager')$ProjectManager = $LangTransl;
        endforeach;

        if($LangID == 'IND')
        {
        	$outDesc	= "UM, Opname";
        }
        else
        {
        	$outDesc	= "Use Material";
        }

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
			
			$columns_valid 	= array("");
	 
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_projectlist->get_AllDataC($DefEmp_ID, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_projectlist->get_AllDataL($DefEmp_ID, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$j 				= 0;
			foreach ($query->result_array() as $dataI)
			{
				$PRJCODE 		= $dataI['PRJCODE'];
				$PRJCODE_HO		= $dataI['PRJCODE_HO'];
				$PRJPERIOD		= $dataI['PRJPERIOD'];
				$PRJCNUM		= $dataI['PRJCNUM'];
				$PRJNAME		= $dataI['PRJNAME'];
				$PRJCOST		= $dataI['PRJCOST'];
				$PRJDATE		= $dataI['PRJDATE'];
				$myDateProj 	= $dataI['PRJDATE'];
				$PRJEDAT		= $dataI['PRJEDAT'];
				$PRJEDATV		= strftime('%d %b %Y', strtotime($PRJEDAT));
				$PRJSTAT		= $dataI['PRJSTAT'];
				$isActif 		= $dataI['PRJSTAT'];
				$PRJADD			= $dataI['PRJADD'];
				$PRJLOCT		= $dataI['PRJLOCT'];
				$PRJTELP		= $dataI['PRJTELP'];
				$PRJ_MNG		= $dataI['PRJ_MNG'];
				$PRJFAX			= $dataI['PRJFAX'];
				$PRJMAIL		= $dataI['PRJMAIL'];
				$PRJNOTE		= $dataI['PRJNOTE'];
				$PRJLEV			= $dataI['PRJLEV'];

				// GET ADDRESS
					$PRJADD2 		= $PRJLOCT;
					if($PRJADD != '')
						$PRJADD2	= $PRJADD." - ".$PRJLOCT;

					if($PRJTELP != '')
						$PRJADD2	= $PRJADD2."<br>Telp. : ".$PRJTELP;

					if($PRJMAIL != '')
						$PRJADD2	= $PRJADD2.". E-mail: ".$PRJMAIL;
				
				// GET PROJECT DETAIL
					//$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
					$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$result = $this->db->query($sqlX)->result();
					foreach($result as $rowx) :
						$PRJNAME1	= $rowx->PRJNAME;
					endforeach;
					
					$PRJCODE2 	= "-";
					$PRJNAME2	= "-";
					$sqlX 	= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
					$result = $this->db->query($sqlX)->result();
					foreach($result as $rowx) :
						$PRJCODE2	= $rowx->PRJCODE;
						$PRJNAME2	= $rowx->PRJNAME;
					endforeach;
				
					if($PRJSTAT == 0) $PRJSTATDesc = "New";
					elseif($PRJSTAT == 1) $PRJSTATDesc = "Confirm";		
				
					if($myDateProj == '0000-00-00')
					{
						$sqlX = "SELECT PRJDATE FROM tbl_project WHERE PRJCODE = '$prjcode'";
						$result = $this->db->query($sqlX)->result();
						foreach($result as $rowx) :
							$PRJDATE	= $rowx->PRJDATE;
						endforeach;
					}
					$PRJDATEV		= strftime('%d %b %Y', strtotime($PRJDATE));
				
				// PRJ BUDGET DETAIL
					$TOTBP		= 0;	// Budget Plan
					$TOTADD		= 0;
					$TOTUSEDM	= 0;
					$sqlBudg 	= "SELECT SUM(ITM_BUDG) AS TOTBP, SUM(ADD_JOBCOST) AS TOTADD, SUM(ITM_USED_AM) AS TOTUSEDM FROM tbl_joblist_detail 
									WHERE ISLAST = 1 AND PRJCODE = '$PRJCODE'";
					$resBudg 	= $this->db->query($sqlBudg)->result();
					foreach($resBudg as $rowBT) :
						$TOTBP		= $rowBT->TOTBP;
						$TOTADD		= $rowBT->TOTADD;
						//$TOTUSEDM	= $rowBT->TOTUSEDM;
					endforeach;
					$TOTBUDG	= $TOTBP + $TOTADD;
					if($TOTBUDG == 0)
						$TOTBUDG	= 1;
					
					// TOTAL PENGGUNAAN BUDGET DIAMBIL DARI JOURNAL, BUKAN DARI tbl_joblist_detail
						$TOT_USEGEJ = 0;
						$sqlUBGEJ 	= "SELECT SUM(A.Base_Debet) AS TOT_USEGEJ FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'GEJ'
										WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBGEJ 	= $this->db->query($sqlUBGEJ)->result();
						foreach($resUBGEJ as $rowBGEJ) :
							$TOT_USEGEJ	= $rowBGEJ->TOT_USEGEJ;
						endforeach;

						$TOT_UCASH 	= 0;
						$sqlUCASH 	= "SELECT SUM(A.Base_Debet) AS TOT_UCASH FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'CHO'
										WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUCASH 	= $this->db->query($sqlUCASH)->result();
						foreach($resUCASH as $rowCASH) :
							$TOT_UCASH	= $rowCASH->TOT_UCASH;
						endforeach;

						$TOT_USEUM 	= 0;
						$sqlUBUM 	= "SELECT SUM(A.Base_Debet) AS TOT_USEUM FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'UM'
										WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBUM 	= $this->db->query($sqlUBUM)->result();
						foreach($resUBUM as $rowBUM) :
							$TOT_USEUM	= $rowBUM->TOT_USEUM;
						endforeach;

						$TOT_USEOPN = 0;
						$sqlUBOPN 	= "SELECT SUM(A.Base_Debet) AS TOT_USEOPN FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'OPN'
										WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBOPN 	= $this->db->query($sqlUBOPN)->result();
						foreach($resUBOPN as $rowBOPN) :
							$TOT_USEOPN	= $rowBOPN->TOT_USEOPN;
						endforeach;

						$TOT_USEOPR	= $TOT_USEGEJ + $TOT_UCASH;			// JOURNAL NON-MATERIAL
						$TOT_USEUMX	= $TOT_USEUM + $TOT_USEOPN;			// MATERIAL

						$TOTUSEDM 	= $TOT_USEOPR + $TOT_USEUMX;
					
					$PERCUSEDV	= round($TOTUSEDM / $TOTBUDG * 100, 2);
					$PERCUSED	= round($TOTUSEDM / $TOTBUDG * 100, 6);
					if($PERCUSED <= 25)
						$GRFCOL	= 'danger';
					elseif($PERCUSED <= 50)
						$GRFCOL	= 'warning';
					elseif($PERCUSED <= 75)
						$GRFCOL	= 'primary';
					elseif($PERCUSED <= 100)
						$GRFCOL	= 'success';
				
				// STATUS DESC
					if($isActif == 1)
					{
						$isActDesc 	= $Active;
						$STATCOL	= 'success';
					}
					else
					{
						$isActDesc 	= $Inactive;
						$STATCOL	= 'danger';
					}

				// PM AREA
					if($PRJ_MNG == '')
					{
						$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
						$PRJ_MNG 		= "[ no selected ID ]";
						$EMPNAMEMng		= "[ no selected ]";
						$BirthPlace		= "-";
						$BirthDate		= "0000-00-00";
					}
					else
					{
						$imgempfNmX 	= "username.jpg";
						$sqlIMGCrt		= "SELECT imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$PRJ_MNG'";
						$resIMGCrt 		= $this->db->query($sqlIMGCrt)->result();
						foreach($resIMGCrt as $rowGIMGCrt) :
							$imgempfNmX = $rowGIMGCrt->imgemp_filenameX;
						endforeach;
						
						$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/'.$PRJ_MNG.'/'.$imgempfNmX);
						if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$PRJ_MNG))
						{
							$imgMng		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
						}

						$EMPNAMEMng		= "";
						$BirthPlace		= "-";
						$BirthDate		= "0000-00-00";
						$sqlEmpCrt		= "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME, Birth_Place, Date_Of_Birth FROM tbl_employee WHERE Emp_ID = '$PRJ_MNG'";
						$resEmpCrt 		= $this->db->query($sqlEmpCrt)->result();
						foreach($resEmpCrt as $rowEmpCrt) :
							$EMPNAMEMng	= strtolower($rowEmpCrt->EMP_NAME);
							$BirthPlace	= strtolower($rowEmpCrt->Birth_Place);
							$BirthDate	= strftime('%d %b %Y', strtotime($rowEmpCrt->Date_Of_Birth));
						endforeach;
					}

					/*if ($j==1) {
						echo "<tr class=zebra1>";
						$j++;
					} else {
						echo "<tr class=zebra2>";
						$j--;
					}*/
				
                $secUpd	= site_url($secURL.$this->url_encryption_helper->encode_url($PRJCODE).'&mnCode='.$mnCode.'&MenuApp='.$MenuApp.'&jrnCat='.$jrnCat.'&jrnType='.$jrnType);
                
		    	/*$s_01 	= "CREATE VIEW vw_tbl_wbd_$PRJCODE AS
							    SELECT
									ORD_ID,
									JOBCODEDET,
									JOBCODEID,
									JOBPARENT,
									PRJCODE,
									PRJCODE_HO,
									PRJPERIOD,
									JOBDESC,
									ITM_GROUP,
									GROUP_CATEG,
									ITM_CODE,
									ITM_UNIT,
									ITM_VOLM,
									ITM_PRICE,
									ITM_LASTP,
									ITM_AVGP,
									ITM_BUDG,
									BOQ_VOLM,
									BOQ_PRICE,
									BOQ_JOBCOST,
									BOQ_BOBOT,
									ISBOBOT,
									BOQ_AMDVOLM,
									BOQ_AMDPRICE,
									BOQ_AMDTOTAL,
									ADD_VOLM,
									ADD_PRICE,
									ADD_JOBCOST,
									ADDM_VOLM,
									ADDM_JOBCOST,
									REQ_VOLM,
									REQ_AMOUNT,
									PO_VOLM,
									PO_AMOUNT,
									IR_VOLM,
									IR_AMOUNT,
									WO_QTY,
									WO_AMOUNT,
									OPN_QTY,
									OPN_AMOUNT,
									ITM_USED,
									ITM_USED_AM,
									ITM_STOCK,
									ITM_STOCK_AM,
									IS_LEVEL,
									ISLAST
								FROM
									tbl_joblist_detail 
								WHERE
									PRJCODE = '$PRJCODE'";
				$this->db->query($s_01);*/

				$PRJLEVD 		= $PRJNAME1;
				if($PRJLEV == 1)
				{
					$vwPM 		= "";
					$PRJCODEV 	= $PRJCODE2;
					$PRJLEVD 	= "$PRJNAME2 - $PRJNAME1";
				}
				else
				{
					$PRJCODEV 	= $PRJCODE;
					$vwPM 		= 	"<strong><i class='fa fa-user margin-r-5'></i>".$ProjectManager." </strong><br>
									<div class='box-comments' style='background-color: transparent; margin-left: 15px'>
								  		<div class='box-comment'>
							                <img class='img-circle img-sm' src='".$imgMng."' alt='User Image'>
							                <div class='comment-text'>
							                   	<span class='username'>
							                        ".ucwords($EMPNAMEMng)."
							                    </span>
						                  		".$PRJ_MNG."<br>
						                  		".ucwords($BirthPlace).", ".$BirthDate."<strong>
							                </div>
							            </div>
						            </div>";
				}

				$output['data'][] 	= array("<div style='text-align:center; white-space:nowrap'>".anchor("$secUpd",$PRJCODEV,array('class' => 'update')).''."</div>",
									  		"<div style='white-space:nowrap'>
									  			<strong><i class='fa fa-calendar margin-r-5'></i>".$Periode."</strong>
										  		<div style='margin-left: 20px'>
											  		".$PRJDATEV." - ".$PRJEDATV."
											  	</div>
											  	".$vwPM."
											</div>",
											"<strong><i class='fa fa-building margin-r-5'></i>".strtoupper($PRJLEVD)." </strong><br>
								            <strong><i class='fa fa-map-marker margin-r-5'></i> ".$Location." - ".$Contact." </strong>
									  		<div style='margin-left: 15px'>
										  		<div>".$PRJADD2."</div>
										  	</div>
										  	<strong><i class='fa fa-tags margin-r-5'></i>".$Remarks." </strong><br>
									  		<div style='margin-left: 20px'>
										  		<p class='text-muted'>
										  			".$PRJNOTE."
										  		</p>
										  	</div>",
											"<div style='text-align:center; white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".$isActDesc."</span></div>",
											"<div class='cssProgress'>
											    <div class='progress3'>
													<div id='progressbarXX' style='text-align: center;'>
														<div class='cssProgress-bar cssProgress-".$GRFCOL." cssProgress-active' style='width: ".$PERCUSED."%; text-align:center; font-weight:bold;'></div>
														<span class='cssProgress-label' style='font-size: 12px'>".$PERCUSEDV." %</span>
													</div>
												</div>
											</div>
											<strong><i class='fa fa-dollar margin-r-5'></i> ".$usageCateg." </strong>
										    <div style='margin-left: 15px'>
										  		<p class='text-muted'>
										  			IDR ".number_format($TOT_USEOPR,2)." (Cash Out)<br>IDR ".number_format($TOT_USEUMX,2)." ($outDesc)
										  		</p>
										  	</div>");
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataPRJ_news() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$secURL		= $_GET['id'];
		$mnCode		= $_GET['mnCode'];
		$MenuApp	= $_GET['MenuApp'];
		$jrnCat		= $_GET['jrnCat'];

		$LangID     = $this->session->userdata['LangID'];

		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$s0 		= "SELECT SPLCODE FROM tbl_supplier WHERE SPLUSERN = '$DefEmp_ID' AND SPLPASSW = '$'";
		$r0 		= $this->db->query($s0)->result();
		foreach($r0 as $rw0) :
			$SPLCODE	= $rw0->SPLCODE;
		endforeach;
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
    		if($TranslCode == 'usageCateg')$usageCateg = $LangTransl;
    		if($TranslCode == 'Address')$Address = $LangTransl;
    		if($TranslCode == 'News')$News = $LangTransl;
    		if($TranslCode == 'Picture')$Picture = $LangTransl;
        endforeach;

        if($LangID == 'IND')
        {
        	$npost 	= "kiriman berita";
        	$ppost 	= "kiriman gambar";
        }
       	else
       	{
        	$npost 	= "news post";
        	$ppost 	= "pictures post";
       	}

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
			
			$columns_valid 	= array("");
	 
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_projectlist->get_AllDataC($DefEmp_ID, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_projectlist->get_AllDataL($DefEmp_ID, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$j 				= 0;
			foreach ($query->result_array() as $dataI)
			{
				$PRJCODE 		= $dataI['PRJCODE'];
				$PRJCODE_HO		= $dataI['PRJCODE_HO'];
				$PRJPERIOD		= $dataI['PRJPERIOD'];
				$PRJCNUM		= $dataI['PRJCNUM'];
				$PRJNAME		= $dataI['PRJNAME'];
				$PRJLOCT		= $dataI['PRJLOCT'];
				$PRJADD			= $dataI['PRJADD'];
				$PRJCOST		= $dataI['PRJCOST'];
				$PRJDATE		= $dataI['PRJDATE'];
				$myDateProj 	= $dataI['PRJDATE'];
				$PRJEDAT		= $dataI['PRJEDAT'];
				$PRJEDATV		= strftime('%d %B %Y', strtotime($PRJEDAT));
				$PRJSTAT		= $dataI['PRJSTAT'];
				$isActif 		= $dataI['PRJSTAT'];
				$PRJ_MNG		= $dataI['PRJ_MNG'];
				$PRJTELP		= $dataI['PRJTELP'];
				$PRJFAX			= $dataI['PRJFAX'];
				$PRJMAIL		= $dataI['PRJMAIL'];
				
				// GET PROJECT DETAIL
					//$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
					$s_0 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$r_0 	= $this->db->query($s_0)->result();
					foreach($r_0 as $rw_0) :
						$PRJNAME1	= $rw_0->PRJNAME;
					endforeach;
				
					$PRJNAME2	= "-";
					$PRJADD2 	= "-";
					$s_1 		= "SELECT PRJNAME, PRJADD, PRJLOCT, PRJTELP, PRJMAIL FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
					$r_1 		= $this->db->query($s_1)->result();
					foreach($r_1 as $rw_1) :
						$PRJNAME2	= $rw_1->PRJNAME;
						$PRJADD2	= $rw_1->PRJADD;
						$PRJTELP	= $rw_1->PRJTELP;
						$PRJMAIL	= $rw_1->PRJMAIL;
					endforeach;

					$PRJADD3 		= $PRJADD2;
					if($PRJTELP != '')
						$PRJADD3	= $PRJADD3."<br>Telp. : ".$PRJTELP;

					if($PRJMAIL != '')
						$PRJADD3	= $PRJADD3.".<br>E-mail: ".$PRJMAIL;

				// PRJ BUDGET DETAIL
					$TOTBP		= 0;	// Budget Plan
					$TOTADD		= 0;
					$TOTUSEDM	= 0;
					$sqlBudg 	= "SELECT SUM(ITM_BUDG) AS TOTBP, SUM(ADD_JOBCOST) AS TOTADD, SUM(ITM_USED_AM) AS TOTUSEDM FROM tbl_joblist_detail 
									WHERE ISLAST = 1 AND PRJCODE = '$PRJCODE'";
					$resBudg 	= $this->db->query($sqlBudg)->result();
					foreach($resBudg as $rowBT) :
						$TOTBP		= $rowBT->TOTBP;
						$TOTADD		= $rowBT->TOTADD;
						//$TOTUSEDM	= $rowBT->TOTUSEDM;
					endforeach;
					$TOTBUDG	= $TOTBP + $TOTADD;
					if($TOTBUDG == 0)
						$TOTBUDG	= 1;
					
					// TOTAL PENGGUNAAN BUDGET DIAMBIL DARI JOURNAL, BUKAN DARI tbl_joblist_detail
						$TOT_USEGEJ = 0;
						$sqlUBGEJ 	= "SELECT SUM(A.Base_Debet) AS TOT_USEGEJ FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'GEJ'
										WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBGEJ 	= $this->db->query($sqlUBGEJ)->result();
						foreach($resUBGEJ as $rowBGEJ) :
							$TOT_USEGEJ	= $rowBGEJ->TOT_USEGEJ;
						endforeach;

						$TOT_UCASH 	= 0;
						$sqlUCASH 	= "SELECT SUM(A.Base_Debet) AS TOT_UCASH FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'CHO'
										WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUCASH 	= $this->db->query($sqlUCASH)->result();
						foreach($resUCASH as $rowCASH) :
							$TOT_UCASH	= $rowCASH->TOT_UCASH;
						endforeach;

						$TOT_USEUM 	= 0;
						$sqlUBUM 	= "SELECT SUM(A.Base_Debet) AS TOT_USEUM FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'UM'
										WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBUM 	= $this->db->query($sqlUBUM)->result();
						foreach($resUBUM as $rowBUM) :
							$TOT_USEUM	= $rowBUM->TOT_USEUM;
						endforeach;

						$TOT_USEOPN = 0;
						$sqlUBOPN 	= "SELECT SUM(A.Base_Debet) AS TOT_USEOPN FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'OPN'
										WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBOPN 	= $this->db->query($sqlUBOPN)->result();
						foreach($resUBOPN as $rowBOPN) :
							$TOT_USEOPN	= $rowBOPN->TOT_USEOPN;
						endforeach;

						$TOT_USEOPR	= $TOT_USEGEJ + $TOT_UCASH;			// JOURNAL NON-MATERIAL
						$TOT_USEUMX	= $TOT_USEUM + $TOT_USEOPN;			// MATERIAL

						$TOTUSEDM 	= $TOT_USEOPR + $TOT_USEUMX;
					
					$PERCUSEDV	= round($TOTUSEDM / $TOTBUDG * 100, 2);
					$PERCUSED	= round($TOTUSEDM / $TOTBUDG * 100, 6);
					if($PERCUSED <= 25)
						$GRFCOL	= 'danger';
					elseif($PERCUSED <= 50)
						$GRFCOL	= 'warning';
					elseif($PERCUSED <= 75)
						$GRFCOL	= 'primary';
					elseif($PERCUSED <= 100)
						$GRFCOL	= 'success';

				// TOTAL POST
					$s_2 	= "tbl_project_liveinfo WHERE prjcode = '$PRJCODE'";
					$r_2 	= $this->db->count_all($s_2);

					$s_3 	= "tbl_project_liveinfopic WHERE prjcode = '$PRJCODE'";
					$r_3 	= $this->db->count_all($s_3);
					
				
                $secUpd				= site_url($secURL.$this->url_encryption_helper->encode_url($PRJCODE).'&mnCode='.$mnCode.'&MenuApp='.$MenuApp.'&jrnCat='.$jrnCat);               	
				$output['data'][] 	= array("<div style='text-align:center; white-space:nowrap'>".anchor("$secUpd",$PRJCODE,array('class' => 'update')).''."</div>",
									  		"<div style='white-space:nowrap'>
									  		<p><strong style='font-size:13px'>".$PRJNAME1."</strong><br></p>
										  	<strong><i class='fa fa-calendar margin-r-5'></i> $CompanyName </strong>
									  		<div style='margin-left: 18px'>
										  		<p class='text-muted'>
										  			".$PRJNAME2."
										  		</p>
										  	</div>",
											"<strong><i class='fa fa-map-marker margin-r-5'></i> ".$Address." - ".$Contact." </strong>
									  		<div style='margin-left: 15px'>
										  			<div>".$PRJADD3."</div>
										  	</div>",
											"<strong><i class='fa fa-newspaper-o margin-r-5'></i> $News </strong>
									  		<div style='margin-left: 20px'>
										  		<div> ".number_format($r_2,0)." $npost</div>
										  	</div>
										  	<strong><i class='fa fa-picture-o margin-r-5'></i> $Picture </strong>
									  		<div style='margin-left: 20px'>
										  		<div> ".number_format($r_3,0)." $ppost</div>
										  	</div>",
											"<div class='cssProgress'>
											    <div class='progress3'>
													<div id='progressbarXX' style='text-align: center;'>
														<div class='cssProgress-bar cssProgress-".$GRFCOL." cssProgress-active' style='width: ".$PERCUSED."%; text-align:center; font-weight:bold;'></div>
														<span class='cssProgress-label' style='font-size: 12px'>".$PERCUSEDV." %</span>
													</div>
												</div>
											</div>");
				$noU			= $noU + 1;
			}               	

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataPRJ_partn() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$secURL		= $_GET['id'];
		$mnCode		= $_GET['mnCode'];
		$MenuApp	= $_GET['MenuApp'];
		$jrnCat		= $_GET['jrnCat'];

		$LangID     = $this->session->userdata['LangID'];

		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$s0 		= "SELECT SPLCODE FROM tbl_supplier WHERE SPLUSERN = '$DefEmp_ID' AND SPLPASSW = '$'";
		$r0 		= $this->db->query($s0)->result();
		foreach($r0 as $rw0) :
			$SPLCODE	= $rw0->SPLCODE;
		endforeach;
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
    		if($TranslCode == 'usageCateg')$usageCateg = $LangTransl;
    		if($TranslCode == 'BillAddress')$BillAddress = $LangTransl;
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
			
			$columns_valid 	= array("");
	 
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_projectlist->get_AllDataC($DefEmp_ID, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_projectlist->get_AllDataL($DefEmp_ID, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$j 				= 0;
			foreach ($query->result_array() as $dataI)
			{
				$PRJCODE 		= $dataI['PRJCODE'];
				$PRJCODE_HO		= $dataI['PRJCODE_HO'];
				$PRJPERIOD		= $dataI['PRJPERIOD'];
				$PRJCNUM		= $dataI['PRJCNUM'];
				$PRJNAME		= $dataI['PRJNAME'];
				$PRJLOCT		= $dataI['PRJLOCT'];
				$PRJADD			= $dataI['PRJADD'];
				$PRJCOST		= $dataI['PRJCOST'];
				$PRJDATE		= $dataI['PRJDATE'];
				$myDateProj 	= $dataI['PRJDATE'];
				$PRJEDAT		= $dataI['PRJEDAT'];
				$PRJEDATV		= strftime('%d %B %Y', strtotime($PRJEDAT));
				$PRJSTAT		= $dataI['PRJSTAT'];
				$isActif 		= $dataI['PRJSTAT'];
				$PRJ_MNG		= $dataI['PRJ_MNG'];
				$PRJTELP		= $dataI['PRJTELP'];
				$PRJFAX			= $dataI['PRJFAX'];
				$PRJMAIL		= $dataI['PRJMAIL'];
				
				// GET PROJECT DETAIL
					//$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
					$s_0 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$r_0 	= $this->db->query($s_0)->result();
					foreach($r_0 as $rw_0) :
						$PRJNAME1	= $rw_0->PRJNAME;
					endforeach;
				
					$PRJNAME2	= "-";
					$PRJADD2 	= "-";
					$s_1 		= "SELECT PRJNAME, PRJADD, PRJLOCT, PRJTELP, PRJMAIL FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
					$r_1 		= $this->db->query($s_1)->result();
					foreach($r_1 as $rw_1) :
						$PRJNAME2	= $rw_1->PRJNAME;
						$PRJADD2	= $rw_1->PRJADD;
						$PRJTELP	= $rw_1->PRJTELP;
						$PRJMAIL	= $rw_1->PRJMAIL;
					endforeach;

					$PRJADD3 		= $PRJADD2;
					if($PRJTELP != '')
						$PRJADD3	= $PRJADD3."<br>Telp. : ".$PRJTELP;

					if($PRJMAIL != '')
						$PRJADD3	= $PRJADD3.".<br>E-mail: ".$PRJMAIL;

				// TOTAL INV
					/*$s_2 	= "SELECT SUM(INV_AMOUNT) AS TOT_INV, SUM(INV_AMOUNT_PAID) AS TOT_PAID FROM tbl_pinv_header
								WHERE PRJCODE = '$PRJCODE' AND SPLCODE = '' AND PINV_STAT IN (3,6)";
					$r_2 	= $this->db->query($s_2)->result();
					foreach($r_2 as $rw_2) :
						$PRJNAME1	= $rw_2->PRJNAME;
					endforeach;*/
				
                $secUpd				= site_url($secURL.$this->url_encryption_helper->encode_url($PRJCODE).'&mnCode='.$mnCode.'&MenuApp='.$MenuApp.'&jrnCat='.$jrnCat);               	
				$output['data'][] 	= array("<div style='text-align:center; white-space:nowrap'>".anchor("$secUpd",$PRJCODE,array('class' => 'update')).''."</div>",
									  		"<div style='white-space:nowrap'>
									  		<p><strong style='font-size:13px'>".$PRJNAME1."</strong><br></p>
										  	<strong><i class='fa fa-calendar margin-r-5'></i> $CompanyName </strong>
									  		<div style='margin-left: 18px'>
										  		<p class='text-muted'>
										  			".$PRJNAME2."
										  		</p>
										  	</div>",
											"<strong><i class='fa fa-map-marker margin-r-5'></i> ".$BillAddress." - ".$Contact." </strong>
									  		<div style='margin-left: 15px'>
										  			<div>".$PRJADD3."</div>
										  	</div>",
											"",
											"");
				$noU			= $noU + 1;
			}               	

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataPRJL() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$secURL		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
    		if($TranslCode == 'usageCateg')$usageCateg = $LangTransl;
        endforeach;

        if($LangID == 'IND')
        {
        	$outDesc	= "UM, Opname";
        }
        else
        {
        	$outDesc	= "Use Material";
        }
		
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
			
			$columns_valid 	= array("");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_projectlist->get_AllDataC($DefEmp_ID, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_projectlist->get_AllDataL($DefEmp_ID, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$j 				= 0;
			foreach ($query->result_array() as $dataI) 
			{
				$PRJCODE 		= $dataI['PRJCODE'];
				$PRJCODE_HO		= $dataI['PRJCODE_HO'];
				$PRJPERIOD		= $dataI['PRJPERIOD'];
				$PRJCNUM		= $dataI['PRJCNUM'];
				$PRJNAME		= $dataI['PRJNAME'];
				$PRJLOCT		= $dataI['PRJLOCT'];
				$PRJADD			= $dataI['PRJADD'];
				$PRJCOST		= $dataI['PRJCOST'];
				$PRJDATE		= $dataI['PRJDATE'];
				$myDateProj 	= $dataI['PRJDATE'];
				$PRJEDAT		= $dataI['PRJEDAT'];
				$PRJEDATV		= strftime('%d %B %Y', strtotime($PRJEDAT));
				$PRJSTAT		= $dataI['PRJSTAT'];
				$isActif 		= $dataI['PRJSTAT'];
				$PRJ_MNG		= $dataI['PRJ_MNG'];
				$PRJTELP		= $dataI['PRJTELP'];
				$PRJFAX			= $dataI['PRJFAX'];
				$PRJMAIL		= $dataI['PRJMAIL'];

				$PRJADD2 		= $PRJADD;
				if($PRJLOCT != '')
					$PRJADD2	= $PRJLOCT."<br>".$PRJADD;

				if($PRJTELP != '')
					$PRJADD2	= $PRJADD2."<br>Telp. : ".$PRJTELP;

				if($PRJMAIL != '')
					$PRJADD2	= $PRJADD2.". E-mail: ".$PRJMAIL;
				
				// GET PROJECT DETAIL
					//$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
					$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$result = $this->db->query($sqlX)->result();
					foreach($result as $rowx) :
						$PRJNAME1	= $rowx->PRJNAME;
					endforeach;
				
					$PRJNAME2	= "-";
					$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
					$result = $this->db->query($sqlX)->result();
					foreach($result as $rowx) :
						$PRJNAME2	= $rowx->PRJNAME;
					endforeach;
				
					if($PRJSTAT == 0) $PRJSTATDesc = "New";
					elseif($PRJSTAT == 1) $PRJSTATDesc = "Confirm";		
				
					if($myDateProj == '0000-00-00')
					{
						$sqlX = "SELECT PRJDATE FROM tbl_project WHERE PRJCODE = '$prjcode'";
						$result = $this->db->query($sqlX)->result();
						foreach($result as $rowx) :
							$PRJDATE	= $rowx->PRJDATE;
						endforeach;
					}
					$PRJDATEV		= strftime('%d %B %Y', strtotime($PRJDATE));
				
				// PRJ BUDGET DETAIL
					$TOTBP		= 0;	// Budget Plan
					$TOTADD		= 0;
					$TOTUSEDM	= 0;
					$sqlBudg 	= "SELECT SUM(ITM_BUDG) AS TOTBP, SUM(ADD_JOBCOST) AS TOTADD, SUM(ITM_USED_AM) AS TOTUSEDM FROM tbl_joblist_detail 
									WHERE ISLAST = 1 AND PRJCODE = '$PRJCODE'";
					$resBudg 	= $this->db->query($sqlBudg)->result();
					foreach($resBudg as $rowBT) :
						$TOTBP		= $rowBT->TOTBP;
						$TOTADD		= $rowBT->TOTADD;
						$TOTUSEDM	= $rowBT->TOTUSEDM;
					endforeach;
					$TOTBUDG	= $TOTBP + $TOTADD;
					if($TOTBUDG == 0)
						$TOTBUDG	= 1;
					
					// TOTAL PENGGUNAAN BUDGET DIAMBIL DARI JOURNAL, BUKAN DARI tbl_joblist_detail
						$TOT_USEGEJ = 0;
						$sqlUBGEJ 	= "SELECT SUM(A.Base_Debet) AS TOT_USEGEJ FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'GEJ'
										WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBGEJ 	= $this->db->query($sqlUBGEJ)->result();
						foreach($resUBGEJ as $rowBGEJ) :
							$TOT_USEGEJ	= $rowBGEJ->TOT_USEGEJ;
						endforeach;

						$TOT_UCASH 	= 0;
						$sqlUCASH 	= "SELECT SUM(A.Base_Debet) AS TOT_UCASH FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'CHO'
										WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUCASH 	= $this->db->query($sqlUCASH)->result();
						foreach($resUCASH as $rowCASH) :
							$TOT_UCASH	= $rowCASH->TOT_UCASH;
						endforeach;


						$TOT_USEUM 	= 0;
						$sqlUBUM 	= "SELECT SUM(A.Base_Debet) AS TOT_USEUM FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'UM'
										WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBUM 	= $this->db->query($sqlUBUM)->result();
						foreach($resUBUM as $rowBUM) :
							$TOT_USEUM	= $rowBUM->TOT_USEUM;
						endforeach;

						$TOT_USEOPN = 0;
						$sqlUBOPN 	= "SELECT SUM(A.Base_Debet) AS TOT_USEOPN FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'OPN'
										WHERE A.proj_Code = '$PRJCODE' AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBOPN 	= $this->db->query($sqlUBOPN)->result();
						foreach($resUBOPN as $rowBOPN) :
							$TOT_USEOPN	= $rowBOPN->TOT_USEOPN;
						endforeach;

						$TOT_USEOPR	= $TOT_USEGEJ + $TOT_UCASH;			// JOURNAL NON-MATERIAL
						$TOT_USEUMX	= $TOT_USEUM + $TOT_USEOPN;			// MATERIAL

						$TOTUSEDM 	= $TOT_USEOPR + $TOT_USEUMX;
					
					$PERCUSEDV	= round($TOTUSEDM / $TOTBUDG * 100, 2);
					$PERCUSED	= round($TOTUSEDM / $TOTBUDG * 100, 6);
					if($PERCUSED <= 25)
						$GRFCOL	= 'danger';
					elseif($PERCUSED <= 50)
						$GRFCOL	= 'warning';
					elseif($PERCUSED <= 75)
						$GRFCOL	= 'primary';
					elseif($PERCUSED <= 100)
						$GRFCOL	= 'success';
					
				if($isActif == 1)
				{
					$isActDesc 	= $Active;
					$STATCOL	= 'success';
				}
				else
				{
					$isActDesc 	= $Inactive;
					$STATCOL	= 'danger';
				}

				// PM AREA
					if($PRJ_MNG == '')
					{
						$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
						$PRJ_MNG 		= "[ no selected ID ]";
						$EMPNAMEMng		= "[ no selected ]";
						$BirthPlace		= "-";
						$BirthDate		= "0000-00-00";
					}
					else
					{
						$imgempfNmX 	= "username.jpg";
						$sqlIMGCrt		= "SELECT imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$PRJ_MNG'";
						$resIMGCrt 		= $this->db->query($sqlIMGCrt)->result();
						foreach($resIMGCrt as $rowGIMGCrt) :
							$imgempfNmX = $rowGIMGCrt->imgemp_filenameX;
						endforeach;
						
						$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/'.$PRJ_MNG.'/'.$imgempfNmX);
						if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$PRJ_MNG))
						{
							$imgMng		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
						}

						$EMPNAMEMng		= "";
						$BirthPlace		= "-";
						$BirthDate		= "0000-00-00";
						$sqlEmpCrt		= "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME, Birth_Place, Date_Of_Birth FROM tbl_employee WHERE Emp_ID = '$PRJ_MNG'";
						$resEmpCrt 		= $this->db->query($sqlEmpCrt)->result();
						foreach($resEmpCrt as $rowEmpCrt) :
							$EMPNAMEMng	= strtolower($rowEmpCrt->EMP_NAME);
							$BirthPlace	= strtolower($rowEmpCrt->Birth_Place);
							$BirthDate	= strftime('%d %B %Y', strtotime($rowEmpCrt->Date_Of_Birth));
						endforeach;
					}

					/*if ($j==1) {
						echo "<tr class=zebra1>";
						$j++;
					} else {
						echo "<tr class=zebra2>";
						$j--;
					}*/
				
                $secUpd				= site_url('c_project/lst180c2hprj/u180c2gdt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
               	//$secUpd			= site_url($secURL.$this->url_encryption_helper->encode_url($PRJCODE));
				$output['data'][] 	= array("<div style='text-align:center; white-space:nowrap'>".anchor("$secUpd",$PRJCODE,array('class' => 'update')).' '."</div>",
									  		"<div style='white-space:nowrap'>
									  		<p><strong style='font-size:13px'>".$PRJNAME1."</strong><br></p>
										  	<strong><i class='fa fa-calendar margin-r-5'></i> Periode </strong>
									  		<div>
										  		<p class='text-muted'>
										  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$PRJDATEV." - ".$PRJEDATV."
										  		</p>
										  	</div>",
											"<strong><i class='fa fa-building margin-r-5'></i>".$CompanyName." </strong>
									  		<div>
										  		<p class='text-muted'>
										  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$PRJNAME2."
										  		</p>
										  	</div>
								            <strong><i class='fa fa-map-marker margin-r-5'></i> ".$Location." - ".$Contact." </strong>
									  		<div style='margin-left: 15px'>
										  			<div>".$PRJADD2."</div>
										  	</div>",
											"<div style='text-align:center; white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".$isActDesc."</span></div>",
											"<div class='cssProgress'>
											    <div class='progress3'>
													<div id='progressbarXX' style='text-align: center;'>
														<div class='cssProgress-bar cssProgress-".$GRFCOL." cssProgress-active' style='width: ".$PERCUSED."%; text-align:center; font-weight:bold;'></div>
														<span class='cssProgress-label' style='font-size: 12px'>".$PERCUSEDV."%</span>
													</div>
												</div>
											</div>
											<strong><i class='fa fa-dollar margin-r-5'></i> ".$usageCateg." </strong>
										    <div style='margin-left: 15px'>
										  		<p class='text-muted'>
										  			IDR ".number_format($TOT_USEGEJ,2)." (Journal)<br>IDR ".number_format($TOT_USEUMX,2)." ($outDesc)
										  		</p>
										  	</div>");
				$noU			= $noU + 1;
			}
			
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add() // OK
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['h2_title']	= 'Add Project';
			$data['main_view'] 	= 'v_project/v_listproject/listproject_form';
			$data['main_view2'] = 'v_project/v_listproject/getaddress_sd';
			$data['form_action']= site_url('c_project/lst180c2hprj/add_process');
			$data['backURL'] 	= site_url('c_project/lst180c2hprj/');
			
			$MenuCode 				= 'MN126';
			$data['MenuCode'] 		= 'MN126';
			$data['viewDocPattern'] = $this->m_listproject->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN126';
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
			
			$this->load->view('v_project/v_listproject/listproject_form', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function getTheCode($PRJCODE) // OK
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		$recordcountProj 	= $this->m_listproject->count_all_num_rowsProj($PRJCODE);
		echo $recordcountProj;
	}
	
	function add_process() // OK
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		date_default_timezone_set("Asia/Jakarta");
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$proj_Number			= $this->input->post('proj_Number');
			$PRJCODE 				= $this->input->post('PRJCODE');
			$PRJNAME 				= $this->input->post('PRJNAME');
			$PRJDATE				= date('Y-m-d',strtotime($this->input->post('PRJDATE')));
			$PRJDATE_CO				= date('Y-m-d',strtotime($this->input->post('PRJDATE_CO')));
			$PRJEDAT				= date('Y-m-d',strtotime($this->input->post('PRJEDAT')));
			$PRJCNUM 				= $this->input->post('PRJCNUM');
			$PRJCATEG 				= $this->input->post('PRJCATEG');
			$PRJOWN 				= $this->input->post('PRJOWN');
			$PRJCURR				= $this->input->post('PRJCURR');
			$PRJCOST 				= $this->input->post('PRJCOST');
			$PRJLOCT 				= $this->input->post('PRJLOCT');
			$PRJLKOT 				= $this->input->post('PRJLKOT');
			$PRJ_MNG1				= $this->input->post('PRJ_MNG');
			$QTY_SPYR				= $this->input->post('QTY_SPYR');
			$PRJNOTE				= $this->input->post('PRJNOTE');
			$PRC_STRK				= $this->input->post('PRC_STRK');
			$PRC_ARST				= $this->input->post('PRC_ARST');
			$PRC_MKNK				= $this->input->post('PRC_MKNK');
			$PRC_ELCT				= $this->input->post('PRC_ELCT');
			$PRJSTAT 				= $this->input->post('PRJSTAT');			
			$PRJCBNG				= '';			
			$Patt_Year				= date('Y',strtotime($this->input->post('PRJDATE')));
			$Patt_Number			= $this->input->post('Patt_Number');
			
			$sqlPRJHOC	= "tbl_project WHERE isHO = '1'";
			$resPRJHOC 	= $this->db->count_all($sqlPRJHOC);
			$syncPRJHO	= "";
			if($resPRJHOC > 0)
			{
				$sqlPRJHO	= "SELECT PRJCODE AS PRJCODEHO FROM tbl_project WHERE isHO = 1";
				$resPRJHO 	= $this->db->query($sqlPRJHO)->result();
				foreach($resPRJHO as $rowPRJHO) :
					$PRJCODEHO 	= $rowPRJHO->PRJCODEHO;
				endforeach;
				$syncPRJHO	= "$PRJCODEHO~$PRJCODE";
			}
			else
			{
				echo "We not found the Head Office Code<br>Contact your administrator.";
				return false;
			}
			
			$CURRRATE				= 1;
			if($PRJCURR == 'IDR')
			{
				//$CURRRATE 	= 1;
			}
			else
			{
				//$PRJCOST 	= $this->input->post('proj_amountUSD');
				//$CURRRATE 	= $this->input->post('CURRRATE');
				//$PRJCOST	= $PRJCOST1 * $CURRRATE;
			}
			
			$selStep	= 0;
			$PRJ_MNG	= '';
			if($PRJ_MNG1 != '')
			{
				foreach ($PRJ_MNG1 as $sel_pm)
				{
					$selStep	= $selStep + 1;
					if($selStep == 1)
					{
						$user_to	= explode ("|",$sel_pm);
						$user_ID	= $user_to[0];
						$PRJ_MNG	= $user_ID;
						//$coll_MADD	= $user_ADD;
					}
					else
					{					
						$user_to	= explode ("|",$sel_pm);
						$user_ID	= $user_to[0];			
						$PRJ_MNG	= "$TASKD_EMPID2;$user_ID";
						//$coll_MADD	= "$coll_MADD;$user_ADD";
					}
				}
			}
			
			$projectheader = array('proj_Number' 	=> $proj_Number,
									'PRJCODE'		=> $PRJCODE,
									'PRJCNUM'		=> $PRJCNUM,
									'PRJNAME'		=> $PRJNAME,
									'PRJLOCT'		=> $PRJLOCT,
									'PRJCATEG'		=> $PRJCATEG,
									'PRJOWN'		=> $PRJOWN,
									'PRJDATE'		=> $PRJDATE,
									'PRJDATE_CO'	=> $PRJDATE_CO,
									'PRJEDAT'		=> $PRJEDAT,
									'PRJBOQ'		=> $PRJCOST,
									'PRJCOST'		=> $PRJCOST,
									'PRJLKOT'		=> $PRJLKOT,
									'PRJCBNG'		=> $PRJCBNG,
									'PRJCURR'		=> $PRJCURR,
									'CURRRATE'		=> $CURRRATE,
									'PRJSTAT'		=> $PRJSTAT,
									'PRJNOTE'		=> $PRJNOTE,
									'PRJ_MNG'		=> $PRJ_MNG,
									'QTY_SPYR'		=> $QTY_SPYR,
									'PRC_STRK'		=> $PRC_STRK,
									'PRC_ARST'		=> $PRC_ARST,
									'PRC_MKNK'		=> $PRC_MKNK,
									'PRC_ELCT'		=> $PRC_ELCT,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Number'	=> $Patt_Number);
			$this->m_listproject->add($projectheader);
			
			// START : CREATE LABA RUGI
				$LR_CODE	= date('YmdHis');
				$PERIODE	= date('Y-m-d');
				$LR_CREATER	= $this->session->userdata['Emp_ID'];
				$LR_CREATED	= date('Y-m-d H:i:s');
				
				$projectLR 	= array('LR_CODE'			=> $LR_CODE,
								'PERIODE'				=> $PERIODE,
								'PRJCODE'				=> $PRJCODE,
								'PRJNAME'				=> $PRJNAME,
								'PRJCOST'				=> $PRJCOST,
								'LR_CREATER'			=> $LR_CREATER,
								'LR_CREATED'			=> $LR_CREATED);
				$this->m_listproject->addLR($projectLR);
			// END : UPDATE LABA RUGI
			
			// START : COPY ALL ACCOUNT
				$LR_CODE	= date('YmdHis');
				$PERIODE	= date('Y-m-d');
				$LR_CREATER	= $this->session->userdata['Emp_ID'];
				$LR_CREATED	= date('Y-m-d H:i:s');
				
				$projectLR 	= array('LR_CODE'			=> $LR_CODE,
								'PERIODE'				=> $PERIODE,
								'PRJCODE'				=> $PRJCODE,
								'PRJNAME'				=> $PRJNAME,
								'PRJCOST'				=> $PRJCOST,
								'LR_CREATER'			=> $LR_CREATER,
								'LR_CREATED'			=> $LR_CREATED);
				$this->m_listproject->addLR($projectLR);
			// END : UPDATE LABA RUGI
			
			// START : COPY COA
				$sqlAcc		= "SELECT Acc_ID, Account_Class, Account_Number, Account_NameEn, Account_NameId, Account_Category,
									Account_Level, Acc_DirParent, Acc_ParentList, Acc_StatusLinked, Company_ID, Default_Acc,
									Currency_id, Base_OpeningBalance, Base_Debet, Base_Kredit, Base_Debet_tax, Base_Kredit_tax,
									IsInterCompany, isCostComponent, isOnDuty, isFOHCost, Base_Debet2, Base_Kredit2,
									Base_Debet_tax2, Base_Kredit_tax2, COGSReportID, isHO, syncPRJ, isLast
								FROM tbl_chartaccount
								WHERE 
									PRJCODE = '$PRJCODEHO'";
				$resAcc 	= $this->db->query($sqlAcc)->result();
				foreach($resAcc as $rowAcc) :
					$PRJCODE			= $PRJCODE;
					$Acc_ID 			= $rowAcc->Acc_ID;
					$Account_Class 		= $rowAcc->Account_Class;
					$Account_Number 	= $rowAcc->Account_Number;
					$Account_NameEn 	= $rowAcc->Account_NameEn;
					$Account_NameId 	= $rowAcc->Account_NameId;
					$Account_Category 	= $rowAcc->Account_Category;
					$Account_Level 		= $rowAcc->Account_Level;
					$Acc_DirParent 		= $rowAcc->Acc_DirParent;
					$Acc_ParentList 	= $rowAcc->Acc_ParentList;
					$Acc_StatusLinked 	= $rowAcc->Acc_StatusLinked;
					$Company_ID 		= $rowAcc->Company_ID;
					$Default_Acc 		= $rowAcc->Default_Acc;
					$Currency_id 		= $rowAcc->Currency_id;
					$isHO 				= $rowAcc->isHO;

					//if($isHO == 1)
					if($Account_Class == 3 || $Account_Class == 4)
					{
						$Base_OpeningBalance= $rowAcc->Base_OpeningBalance;
						$Base_Debet 		= $rowAcc->Base_Debet;
						$Base_Kredit 		= $rowAcc->Base_Kredit;
						$Base_Debet_tax 	= $rowAcc->Base_Debet_tax;
						$Base_Kredit_tax 	= $rowAcc->Base_Kredit_tax;						
					}
					else
					{
						// SEMENTAR SEMUA AKUN DI NOL KAN BAIK KAS/BANK
							$Base_OpeningBalance= 0;
							$Base_Debet 		= 0;
							$Base_Kredit 		= 0;
							$Base_Debet_tax 	= 0;
							$Base_Kredit_tax 	= 0;						
					}
						
					$IsInterCompany 	= $rowAcc->IsInterCompany;
					$isCostComponent 	= $rowAcc->isCostComponent;
					$isOnDuty 			= $rowAcc->isOnDuty;
					$isFOHCost 			= $rowAcc->isFOHCost;

					//if($isHO == 1)
					if($Account_Class == 3 || $Account_Class == 4)
					{
						$Base_Debet2 		= $rowAcc->Base_Debet2;
						$Base_Kredit2 		= $rowAcc->Base_Kredit2;
						$Base_Debet_tax2 	= $rowAcc->Base_Debet_tax2;
						$Base_Kredit_tax2 	= $rowAcc->Base_Kredit_tax2;					
					}
					else
					{
						// SEMENTAR SEMUA AKUN DI NOL KAN BAIK KAS/BANK
							$Base_Debet2		= 0;
							$Base_Kredit2 		= 0;
							$Base_Debet_tax2 	= 0;
							$Base_Kredit_tax2 	= 0;						
					}
						
					$COGSReportID 		= $rowAcc->COGSReportID;
					$syncPRJ1 			= $rowAcc->syncPRJ;
					//$syncPRJ			= "$syncPRJ1~$PRJCODE";
					$syncPRJ			= $syncPRJHO;
					$isLast 			= $rowAcc->isLast;
					$sqlInsrAcc			= "INSERT INTO tbl_chartaccount 
												(PRJCODE, Acc_ID, Account_Class, Account_Number, Account_NameEn, Account_NameId, 
												Account_Category, Account_Level, Acc_DirParent, Acc_ParentList, Acc_StatusLinked,
												Company_ID, Default_Acc, Currency_id, Base_OpeningBalance, Base_Debet, Base_Kredit, 
												Base_Debet_tax, Base_Kredit_tax, IsInterCompany, isCostComponent, isOnDuty, isFOHCost,
												Base_Debet2, Base_Kredit2, Base_Debet_tax2, Base_Kredit_tax2, COGSReportID, isHO,
												syncPRJ, isLast) 
											VALUES 
												('$PRJCODE', '$Acc_ID', '$Account_Class', '$Account_Number', '$Account_NameEn', 
												'$Account_NameId', '$Account_Category', '$Account_Level', '$Acc_DirParent', 
												'$Acc_ParentList', '$Acc_StatusLinked', '$Company_ID', '$Default_Acc','$Currency_id', 
												'$Base_OpeningBalance', '$Base_Debet', '$Base_Kredit', '$Base_Debet_tax', 
												'$Base_Kredit_tax', '$IsInterCompany', '$isCostComponent', '$isOnDuty', '$isFOHCost',
												'$Base_Debet2', '$Base_Kredit2', '$Base_Debet_tax2', '$Base_Kredit_tax2', 
												'$COGSReportID', '$isHO', '$syncPRJ', '$isLast')";
					$this->db->query($sqlInsrAcc);
				endforeach;	
			// END : COPY COA
			
			// UPDATE SYNC KHUSUS UNTUK CLASS KAS/BANK (3,4) AGAR DISET KE SEMUA PROYEK
				$syncPRALL	= '';
				$i			= 0;
				$sqlPRJALL	= "SELECT DISTINCT PRJCODE FROM tbl_project";
				$resPRJALL 	= $this->db->query($sqlPRJALL)->result();
				foreach($resPRJALL as $rowPRJALL) :
					$i			= $i + 1;
					$PRJCODE1 	= $rowPRJALL->PRJCODE;
					if($i == 1)
					{
						$syncPRALL = $PRJCODE1;
					}
					else
					{
						$syncPRALL	= "$syncPRALL~$PRJCODE1";
					}
				endforeach;
				
				$sqlUpdPRJ			= "UPDATE tbl_chartaccount SET syncPRJ = '$syncPRALL' WHERE Account_Class IN (3,4) AND isLast = 1";
				$this->db->query($sqlUpdPRJ);
					
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/lst180c2hprj/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function do_upload()
	{ 
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
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
            $this->m_listproject->updatePict($PRJCODE, $nameFile);
         }
		 
         $sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/lst180c2hprj/i180c2gdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function u180c2gdt()
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Project';
			$data['main_view'] 		= 'v_project/v_listproject/listproject_form';
			$data['form_action']	= site_url('c_project/lst180c2hprj/update_process');
			$data['backURL'] 		= site_url('c_project/lst180c2hprj/');
			
			$data['recordcountCust'] 	= $this->m_listproject->count_all_num_rowsCust();
			$data['viewcustomer'] 		= $this->m_listproject->viewcustomer()->result();
			
			$MenuCode 					= 'MN126';
			$data['MenuCode'] 			= 'MN126';
			$data['viewDocPattern'] 	= $this->m_listproject->getDataDocPat($MenuCode)->result();
			
			$getproject = $this->m_listproject->get_PROJ_by_number($PRJCODE)->row();
					
			$data['default']['proj_Number'] = $getproject->proj_Number;
			$data['default']['PRJCODE'] 	= $getproject->PRJCODE;
			$data['default']['PRJCNUM'] 	= $getproject->PRJCNUM;
			$data['default']['PRJNAME'] 	= $getproject->PRJNAME;
			$data['default']['PRJLOCT'] 	= $getproject->PRJLOCT;
			$data['default']['PRJCATEG'] 	= $getproject->PRJCATEG;
			$data['default']['PRJOWN'] 		= $getproject->PRJOWN;
			$data['default']['PRJDATE'] 	= $getproject->PRJDATE;
			$data['default']['PRJDATE_CO'] 	= $getproject->PRJDATE_CO;
			$data['default']['PRJEDAT'] 	= $getproject->PRJEDAT;
			$PRJEDAT						= $getproject->PRJEDAT;
			//echo "c_hehe $PRJEDAT";
			$data['default']['PRJCOST'] 	= $getproject->PRJCOST;
			$data['default']['PRJBOQ'] 		= $getproject->PRJBOQ;
			$data['default']['PRJRAP'] 		= $getproject->PRJRAP;
			$data['default']['PRJLKOT'] 	= $getproject->PRJLKOT;
			$data['default']['PRJCBNG']		= $getproject->PRJCBNG;
			$data['default']['PRJCURR']		= $getproject->PRJCURR;
			$data['default']['CURRRATE']	= $getproject->CURRRATE;
			$data['default']['PRJSTAT'] 	= $getproject->PRJSTAT;
			$data['default']['PRJNOTE'] 	= $getproject->PRJNOTE;
			$data['default']['isHO']		= $getproject->isHO;
			
			$data['default']['ISCHANGE']	= $getproject->ISCHANGE;
			$data['default']['REFCHGNO']	= $getproject->REFCHGNO;
			$data['default']['PRJCOST2'] 	= $getproject->PRJCOST2;
			$data['default']['CHGUSER'] 	= $getproject->CHGUSER;
			$data['default']['CHGSTAT'] 	= $getproject->CHGSTAT;
			$data['default']['PRJ_MNG'] 	= $getproject->PRJ_MNG;
			$data['default']['QTY_SPYR'] 	= $getproject->QTY_SPYR;
			$data['default']['PRC_STRK'] 	= $getproject->PRC_STRK;
			$data['default']['PRC_ARST'] 	= $getproject->PRC_ARST;
			$data['default']['PRC_MKNK'] 	= $getproject->PRC_MKNK;
			$data['default']['PRC_ELCT'] 	= $getproject->PRC_ELCT;
			$data['default']['PRJ_IMGNAME'] = $getproject->PRJ_IMGNAME;
			$data['default']['Patt_Year'] 	= $getproject->Patt_Year;
			$data['default']['Patt_Number'] = $getproject->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getproject->proj_Number;
				$MenuCode 		= 'MN126';
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
			
			$this->load->view('v_project/v_listproject/listproject_form', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
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
			$PRJDATE				= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJDATE'))));
			$PRJDATE_CO				= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJDATE_CO'))));
			$PRJEDAT				= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRJEDAT'))));
			
			$PRJCNUM 				= $this->input->post('PRJCNUM');
			$PRJCATEG 				= $this->input->post('PRJCATEG');
			$PRJOWN 				= $this->input->post('PRJOWN');
			$isHO 					= $this->input->post('isHO');
			$PRJCURR				= $this->input->post('PRJCURR');
			$PRJCOST 				= $this->input->post('PRJCOST');
			$PRJLOCT 				= $this->input->post('PRJLOCT');
			$PRJLKOT 				= $this->input->post('PRJLKOT');
			$PRJ_MNG				= $this->input->post('PRJ_MNG');
			$QTY_SPYR				= $this->input->post('QTY_SPYR');
			$PRJNOTE				= $this->input->post('PRJNOTE');
			$PRC_STRK				= $this->input->post('PRC_STRK');
			$PRC_ARST				= $this->input->post('PRC_ARST');
			$PRC_MKNK				= $this->input->post('PRC_MKNK');
			$PRC_ELCT				= $this->input->post('PRC_ELCT');
			$PRJSTAT 				= $this->input->post('PRJSTAT');
			$PRJCBNG				= '';			
			$Patt_Year				= date('Y',strtotime($this->input->post('PRJDATE')));
			$Patt_Number			= $this->input->post('Patt_Number');
			
			$CURRRATE				= 1;
			if($PRJCURR == 'IDR')
			{
				//$CURRRATE 	= 1;
			}
			else
			{
				//$PRJCOST 	= $this->input->post('proj_amountUSD');
				//$CURRRATE 	= $this->input->post('CURRRATE');
				//$PRJCOST	= $PRJCOST1 * $CURRRATE;
			}
			$PRJ_MNG1				= $this->input->post('PRJ_MNG');
			
			// CEK HEAD OFFICE EXIST
				$sqlHO	= "tbl_project WHERE PRJCODE != '$PRJCODE' AND isHO = 1";
				$resHO	= $this->db->count_all($sqlHO);
			
			// CEK THIS PROJECT
				$sqlHO1	= "tbl_project WHERE PRJCODE = '$PRJCODE' AND isHO = 1";
				$resHO1	= $this->db->count_all($sqlHO1);
			if($resHO1 == 1)
			{
				$projectheader = array('proj_Number' 	=> $proj_Number,
								'PRJCODE'				=> $PRJCODE,
								'PRJCNUM'				=> $PRJCNUM,
								'PRJNAME'				=> $PRJNAME,
								'PRJLOCT'				=> $PRJLOCT,
								'PRJCATEG'				=> $PRJCATEG,
								'PRJOWN'				=> $PRJOWN,
								'PRJDATE'				=> $PRJDATE,
								'PRJEDAT'				=> $PRJEDAT,
								'PRJDATE_CO'			=> $PRJDATE_CO,
								'PRJBOQ'				=> $PRJCOST,
								'PRJCOST'				=> $PRJCOST,
								'PRJLKOT'				=> $PRJLKOT,
								'PRJCBNG'				=> $PRJCBNG,
								'PRJCURR'				=> $PRJCURR,
								'CURRRATE'				=> $CURRRATE,
								'PRJSTAT'				=> $PRJSTAT,
								'PRJNOTE'				=> $PRJNOTE,
								'PRJ_MNG'				=> $PRJ_MNG,
								'QTY_SPYR'				=> $QTY_SPYR,
								'PRC_STRK'				=> $PRC_STRK,
								'PRC_ARST'				=> $PRC_ARST,
								'PRC_MKNK'				=> $PRC_MKNK,
								'PRC_ELCT'				=> $PRC_ELCT,
								'Patt_Year'				=> $Patt_Year,
								'Patt_Number'			=> $Patt_Number);							
				$this->m_listproject->update($PRJCODE, $projectheader);
			}
			else
			{
				$PRJCODEHOX	= '';
				if($resHO == 0)
				{
					$LangID 	= $this->session->userdata['LangID'];
					if($LangID == 'IND')
					{
						$Back	= "Kembali";
					}
					else
					{
						$Back	= "Back";
					}
					
					$backURL	= site_url('c_project/lst180c2hprj/');
					echo "Kami tidak menemukan kode kantor pusat.<br>Tidak bsia dilanjutkan. Hubungi administrator.<br>";
					echo anchor("$backURL",'<button class="btn btn-primary">'.$Back.'</button>');
					return false;
				}
				else
				{
					// START : COPY COA BARU UNTUK PROYEK HO BARU
						if($isHO == 1)
						{
							$PRJCODEHO	= '';
							$sqlPRJHO	= "SELECT PRJCODE AS PRJCODEHO FROM tbl_project WHERE isHO = 1";
							$resPRJHO 	= $this->db->query($sqlPRJHO)->result();
							foreach($resPRJHO as $rowPRJHO) :
								$PRJCODEHO 	= $rowPRJHO->PRJCODEHO;
							endforeach;
							$PRJCODEHOX	= $PRJCODEHO;
							
							$syncPRJA	= '';
							$sqlCOASYN	= "SELECT syncPRJ FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODEHO' LIMIT 1";
							$resCOASYN 	= $this->db->query($sqlCOASYN)->result();
							foreach($resCOASYN as $rowCOASYN) :
								$syncPRJA 	= $rowCOASYN->syncPRJ;
							endforeach;
							
							$splitPRJ	= explode("~",$syncPRJA);
							$row 		= 0;
							$syncPRJHO	= '';
							foreach($splitPRJ as $i =>$key) 
							{
								$row	= $row + 1;
								$NEWPRJ	= $key;
								if($NEWPRJ == $PRJCODEHO)
								{
									$NEWPRJ	= $PRJCODE;
								}
								if($row == 1)
								{
									$syncPRJHO	= $NEWPRJ;
								}
								else
								{
									$syncPRJHO	= "$syncPRJHO~$NEWPRJ";
								}
							}
							
							// DELETE SEMUA COA UNTUK PROYEK BARU U/ DIGANTI DENGAN COA BARU
								$sqlDELCOA	= "DELETE FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE'";
								$this->db->query($sqlDELCOA);
								
							$sqlAcc		= "SELECT Acc_ID, Account_Class, Account_Number, Account_NameEn, Account_NameId, Account_Category,
												Account_Level, Acc_DirParent, Acc_ParentList, Acc_StatusLinked, Company_ID, Default_Acc,
												Currency_id, Base_OpeningBalance, Base_Debet, Base_Kredit, Base_Debet_tax, Base_Kredit_tax,
												IsInterCompany, isCostComponent, isOnDuty, isFOHCost, Base_Debet2, Base_Kredit2,
												Base_Debet_tax2, Base_Kredit_tax2, COGSReportID, isHO, syncPRJ, isLast
											FROM tbl_chartaccount
											WHERE 
												PRJCODE = '$PRJCODEHO'";
							$resAcc 	= $this->db->query($sqlAcc)->result();
							foreach($resAcc as $rowAcc) :
								$PRJCODE			= $PRJCODE;
								$Acc_ID 			= $rowAcc->Acc_ID;
								$Account_Class 		= $rowAcc->Account_Class;
								$Account_Number 	= $rowAcc->Account_Number;
								$Account_NameEn 	= $rowAcc->Account_NameEn;
								$Account_NameId 	= $rowAcc->Account_NameId;
								$Account_Category 	= $rowAcc->Account_Category;
								$Account_Level 		= $rowAcc->Account_Level;
								$Acc_DirParent 		= $rowAcc->Acc_DirParent;
								$Acc_ParentList 	= $rowAcc->Acc_ParentList;
								$Acc_StatusLinked 	= $rowAcc->Acc_StatusLinked;
								$Company_ID 		= $rowAcc->Company_ID;
								$Default_Acc 		= $rowAcc->Default_Acc;
								$Currency_id 		= $rowAcc->Currency_id;
								$isHOX 				= $rowAcc->isHO;
								$Base_OpeningBalance= $rowAcc->Base_OpeningBalance;
								$Base_Debet 		= $rowAcc->Base_Debet;
								$Base_Kredit 		= $rowAcc->Base_Kredit;
								$Base_Debet_tax 	= $rowAcc->Base_Debet_tax;
								$Base_Kredit_tax 	= $rowAcc->Base_Kredit_tax;	
								$IsInterCompany 	= $rowAcc->IsInterCompany;
								$isCostComponent 	= $rowAcc->isCostComponent;
								$isOnDuty 			= $rowAcc->isOnDuty;
								$isFOHCost 			= $rowAcc->isFOHCost;
								$Base_Debet2 		= $rowAcc->Base_Debet2;
								$Base_Kredit2 		= $rowAcc->Base_Kredit2;
								$Base_Debet_tax2 	= $rowAcc->Base_Debet_tax2;
								$Base_Kredit_tax2 	= $rowAcc->Base_Kredit_tax2;								
								$COGSReportID 		= $rowAcc->COGSReportID;
								//$syncPRJ1 		= $rowAcc->syncPRJ;
								//$syncPRJ			= "$syncPRJ1~$PRJCODE";
								$syncPRJ			= $syncPRJHO;
								$isLast 			= $rowAcc->isLast;
								$sqlInsrAcc			= "INSERT INTO tbl_chartaccount 
														(PRJCODE, Acc_ID, Account_Class, Account_Number, Account_NameEn, Account_NameId, 
														Account_Category, Account_Level, Acc_DirParent, Acc_ParentList, Acc_StatusLinked,
														Company_ID, Default_Acc, Currency_id, Base_OpeningBalance, Base_Debet, Base_Kredit, 
														Base_Debet_tax, Base_Kredit_tax, IsInterCompany, isCostComponent, isOnDuty, isFOHCost,
														Base_Debet2, Base_Kredit2, Base_Debet_tax2, Base_Kredit_tax2, COGSReportID, isHO,
														syncPRJ, isLast) 
														VALUES 
														('$PRJCODE', '$Acc_ID', '$Account_Class', '$Account_Number', '$Account_NameEn', 
														'$Account_NameId', '$Account_Category', '$Account_Level', '$Acc_DirParent', 
														'$Acc_ParentList', '$Acc_StatusLinked', '$Company_ID', '$Default_Acc','$Currency_id', 
														'$Base_OpeningBalance', '$Base_Debet', '$Base_Kredit', '$Base_Debet_tax', 
														'$Base_Kredit_tax', '$IsInterCompany', '$isCostComponent', '$isOnDuty', '$isFOHCost',
														'$Base_Debet2', '$Base_Kredit2', '$Base_Debet_tax2', '$Base_Kredit_tax2', 
														'$COGSReportID', '$isHOX', '$syncPRJ', '$isLast')";
								$this->db->query($sqlInsrAcc);
							endforeach;	
						}
					// END : COPY COA
				}
				
				$selStep	= 0;
				$PRJ_MNG	= '';
				if($PRJ_MNG1 != '')
				{
					foreach ($PRJ_MNG1 as $sel_pm)
					{
						$selStep	= $selStep + 1;
						if($selStep == 1)
						{
							$user_to	= explode ("|",$sel_pm);
							$user_ID	= $user_to[0];
							$PRJ_MNG	= $user_ID;
							//$coll_MADD	= $user_ADD;
						}
						else
						{					
							$user_to	= explode ("|",$sel_pm);
							$user_ID	= $user_to[0];			
							$PRJ_MNG	= "$TASKD_EMPID2;$user_ID";
							//$coll_MADD	= "$coll_MADD;$user_ADD";
						}
					}
				}
				// UPDATE OLD HO
					$sqlOLDHO	= "UPDATE tbl_project SET isHO = 0 WHERE PRJCODE = '$PRJCODEHOX'";
					$this->db->query($sqlOLDHO);
				
				$projectheader = array('proj_Number' 	=> $proj_Number,
								'PRJCODE'				=> $PRJCODE,
								'PRJCNUM'				=> $PRJCNUM,
								'PRJNAME'				=> $PRJNAME,
								'PRJLOCT'				=> $PRJLOCT,
								'PRJCATEG'				=> $PRJCATEG,
								'PRJOWN'				=> $PRJOWN,
								'isHO'					=> $isHO,
								'PRJDATE'				=> $PRJDATE,
								'PRJEDAT'				=> $PRJEDAT,
								'PRJDATE_CO'			=> $PRJDATE_CO,
								'PRJBOQ'				=> $PRJCOST,
								'PRJCOST'				=> $PRJCOST,
								'PRJLKOT'				=> $PRJLKOT,
								'PRJCBNG'				=> $PRJCBNG,
								'PRJCURR'				=> $PRJCURR,
								'CURRRATE'				=> $CURRRATE,
								'PRJSTAT'				=> $PRJSTAT,
								'PRJNOTE'				=> $PRJNOTE,
								'PRJ_MNG'				=> $PRJ_MNG,
								'QTY_SPYR'				=> $QTY_SPYR,
								'PRC_STRK'				=> $PRC_STRK,
								'PRC_ARST'				=> $PRC_ARST,
								'PRC_MKNK'				=> $PRC_MKNK,
								'PRC_ELCT'				=> $PRC_ELCT,
								'Patt_Year'				=> $Patt_Year,
								'Patt_Number'			=> $Patt_Number);							
				$this->m_listproject->update($PRJCODE, $projectheader);
			}
			
			// START : CHECK IN PROFLOSS
				$sqlPL	= "tbl_profitloss WHERE PRJCODE = '$PRJCODE'";
				$resPL	= $this->db->count_all($sqlPL);
				if($resPL == 0)
				{
					$LR_CODE	= date('YmdHis');
					$PERIODE	= date('Y-m-d');
					$LR_CREATER	= $this->session->userdata['Emp_ID'];
					$LR_CREATED	= date('Y-m-d H:i:s');
					
					$projectLR 	= array('LR_CODE'			=> $LR_CODE,
									'PERIODE'				=> $PERIODE,
									'PRJCODE'				=> $PRJCODE,
									'PRJNAME'				=> $PRJNAME,
									'PRJCOST'				=> $PRJCOST,
									'LR_CREATER'			=> $LR_CREATER,
									'LR_CREATED'			=> $LR_CREATED);
					$this->m_listproject->addLR($projectLR);
				}
				else
				{
					$LR_CODE	= date('YmdHis');
					$PERIODE	= date('Y-m-d');
					$CREATER	= $this->session->userdata['Emp_ID'];
					$CREATED	= date('Y-m-d H:i:s');
					
					$projectLR 	= array('PRJNAME'		=> $PRJNAME,
										'PRJCOST'		=> $PRJCOST);
					$this->m_listproject->updateLR($PRJCODE, $projectLR);
				}
			// END : CHECK IN PROFLOSS
			
			// UPDATE SYNC KHUSUS UNTUK CLASS KAS/BANK (3,4) AGAR DISET KE SEMUA PROYEK
				$syncPRALL	= '';
				$i			= 0;
				$sqlPRJALL	= "SELECT DISTINCT PRJCODE FROM tbl_project";
				$resPRJALL 	= $this->db->query($sqlPRJALL)->result();
				foreach($resPRJALL as $rowPRJALL) :
					$i			= $i + 1;
					$PRJCODE1 	= $rowPRJALL->PRJCODE;
					if($i == 1)
					{
						$syncPRALL = $PRJCODE1;
					}
					else
					{
						$syncPRALL	= "$syncPRALL~$PRJCODE1";
					}
				endforeach;
				
				$sqlUpdPRJ			= "UPDATE tbl_chartaccount SET syncPRJ = '$syncPRALL' WHERE Account_Class IN (3,4) AND isLast = 1";
				$this->db->query($sqlUpdPRJ);
			
			/*$PRJEDAT2		= date('Y-m-d',strtotime($this->input->post('PRJEDAT2')));	
			$PRJEDAT2a		= date('d/m/Y',strtotime($this->input->post('PRJEDAT2')));			
			$updateEndDate 	= array('PRJCODE'		=> $PRJCODE,
								'EDATORI'			=> $PRJEDAT,
								'ENDDATE'			=> $PRJEDAT2,
								'DATETIME'			=> $DATE_CREATED,
								'EMP_ID'			=> $DefEmp_ID);												
			$this->m_listproject->addUpdEDat($updateEndDate);*/
			
			//$odbc 		= odbc_connect ("DBaseNKE4", "" , "");
			//$DBFname	= "PROJECT.DBF";
			//$db 		= dbase_open('C:/NKE/DatabaseDBF/VOCHD.DBF', 2); for local
			/*$db 		= dbase_open('G:/NKE/SDBP/PROJECT.DBF', 2); // for live in 0.44
			$jmlSPL		= dbase_numrecords($db);
			$getTID1	= "UPDATE PROJECT.DBF SET ENDDATE = '$PRJEDAT2a' WHERE PRJCODE = '$PRJCODE'";
			$qTID1 		= odbc_exec($odbc, $getTID1) or die (odbc_errormsg());*/
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $proj_Number;
				$MenuCode 		= 'MN126';
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
			
			redirect('c_project/lst180c2hprj/');
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function vInpProjDet($PRJCODE) // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;	
			
			$data['proj_Code'] 		= $PRJCODE;
			$data['title'] 			= $appName;
			$data['form_action']	= site_url('c_project/lst180c2hprj/vInpProjDet_process');
			$data['h2_title'] 		= 'Input Project Progress';
			
			$this->load->view('v_project/v_listproject/project_sd_detInput', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function vInpProjDet_process() // HOLD - LANGSUNG DI HALAMAN POP UP
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		$this->db->trans_begin();
		
		//$this->m_listproject->deleteProjDet($this->input->post('proj_Code'));
		
		// untuk penyimpanan ke tabel tproject_header
		$projectDet = array('proj_Code' 	=> $this->input->post('proj_Code'),
						'OrigProj_Value'	=> $this->input->post('OrigProj_Value'),
						'Remeasure_Value'	=> $this->input->post('Remeasure_Value'),
						'SIntruc_Value'		=> $this->input->post('SIntruc_Value'),
						
						'RemPropos_Value'	=> $this->input->post('RemPropos_Value'),
						'RemApprov_Value'	=> $this->input->post('RemApprov_Value'),
						'RemDenied_Value'	=> $this->input->post('RemDenied_Value'),
						
						'SInPropos_Value'	=> $this->input->post('SInPropos_Value'),
						'SInApprov_Value'	=> $this->input->post('SInApprov_Value'),
						'SInDenied_Value'	=> $this->input->post('SInDenied_Value'),
						
						'CIDP_Value'		=> $this->input->post('CIDP_Value'),
						'CIProgress_Value'	=> $this->input->post('CIProgress_Value'),
						'CIOthers_Value'	=> $this->input->post('CIOthers_Value'),
						
						'COSDBP_Value'		=> $this->input->post('COSDBP_Value'),
						'COOStanding_Value'	=> $this->input->post('COOStanding_Value'),
						
						'LastUpdate'		=> $this->input->post('LastUpdate'),
						'UpdatedBy'			=> $this->input->post('UpdatedBy'));	
											
		$this->m_listproject->addInpProjDet($projectDet);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		
		$this->session->set_flashdata('message', 'Data succesfull to insert.!');
		redirect('c_project/lst180c2hprj/');
	}
	
	function vProjPerform($PRJCODE) // OK
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'View Project Performance';
			
			$data['proj_Code'] 		= $PRJCODE;
			
			$this->load->view('v_project/v_listproject/project_sd_perform', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
    function inbox($offset=0)
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Project List Inbox';
			$data['main_view'] 	= 'v_project/v_listproject/listproject_inbox';

			/*$num_rows = $this->m_listproject->count_all_num_rows_inbox();
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_project/lst180c2hprj/get_last_ten_project');
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
	 		
			$data['viewpurord'] = $this->m_listproject->get_last_ten_PR_inbox($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();*/	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__I1y');
		}
    }
	
	function getVendAddress($vendCode)
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['myVendCode']		= "$vendCode";
		$sql = "SELECT Vend_Code, Vend_Name, Vend_Address FROM tvendor
					WHERE Vend_Code = '$vendCode'";
		$result1 = $this->db->query($sql)->result();
		foreach($result1 as $row) :
			$Vend_Name = $row->Vend_Address;
		endforeach;
		echo $Vend_Name;
	}

  	function get_AllDataHO() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$secURL		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
    		if($TranslCode == 'usageCateg')$usageCateg = $LangTransl;
        endforeach;

        if($LangID == 'IND')
        {
        	$outDesc	= "Peng. Material: UM, OPN, (Man. : Produksi)";
        }
        else
        {
        	$outDesc	= "Use Material: UM, OPN, (Man. : Production)";
        }
		
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
			
			$columns_valid 	= array("");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_projectlist->get_AllDataHOC($DefEmp_ID, $search); 
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_projectlist->get_AllDataHOL($DefEmp_ID, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PRJCODE 		= $dataI['PRJCODE'];
				$PRJCODE_HO		= $dataI['PRJCODE_HO'];
				$PRJPERIOD		= $dataI['PRJPERIOD'];
				$PRJCNUM		= $dataI['PRJCNUM'];
				$PRJNAME		= $dataI['PRJNAME'];
				$PRJLOCT		= $dataI['PRJLOCT'];
				$PRJCOST		= $dataI['PRJCOST'];
				$PRJDATE		= $dataI['PRJDATE'];
				$myDateProj 	= $dataI['PRJDATE'];
				$PRJEDAT		= $dataI['PRJEDAT'];
				$PRJEDATV		= strftime('%d %B %Y', strtotime($PRJEDAT));
				$PRJSTAT		= $dataI['PRJSTAT'];
				$isActif 		= $dataI['PRJSTAT'];
				$PRJ_MNG		= $dataI['PRJ_MNG'];
				$PRJTELP		= $dataI['PRJTELP'];
				$PRJFAX			= $dataI['PRJFAX'];
				$PRJMAIL		= $dataI['PRJMAIL'];
				
				// GET PROJECT DETAIL
					//$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
					$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$result = $this->db->query($sqlX)->result();
					foreach($result as $rowx) :
						$PRJNAME1	= $rowx->PRJNAME;
					endforeach;
				
					if($PRJSTAT == 0) $PRJSTATDesc = "New";
					elseif($PRJSTAT == 1) $PRJSTATDesc = "Confirm";		
				
					if($myDateProj == '0000-00-00')
					{
						$sqlX = "SELECT PRJDATE FROM tbl_project WHERE PRJCODE = '$prjcode'";
						$result = $this->db->query($sqlX)->result();
						foreach($result as $rowx) :
							$PRJDATE	= $rowx->PRJDATE;
						endforeach;
					}
					$PRJDATEV		= strftime('%d %B %Y', strtotime($PRJDATE));
				
				// PRJ BUDGET DETAIL
					$TOTBP		= 0;	// Budget Plan
					$TOTADD		= 0;
					$TOTUSEDM	= 0;
					$sqlBudg 	= "SELECT SUM(ITM_BUDG) AS TOTBP, SUM(ADD_JOBCOST) AS TOTADD, SUM(ITM_USED_AM) AS TOTUSEDM FROM tbl_joblist_detail 
									WHERE ISLAST = 1 AND PRJCODE IN (SELECT C.PRJCODE FROM tbl_project C WHERE C.PRJCODE_HO = '$PRJCODE')";
					$resBudg 	= $this->db->query($sqlBudg)->result();
					foreach($resBudg as $rowBT) :
						$TOTBP		= $rowBT->TOTBP;
						$TOTADD		= $rowBT->TOTADD;
						$TOTUSEDM	= $rowBT->TOTUSEDM;
					endforeach;
					$TOTBUDG	= $TOTBP + $TOTADD;
					if($TOTBUDG == 0)
						$TOTBUDG	= 1;
					
					// TOTAL PENGGUNAAN BUDGET DIAMBIL DARI JOURNAL, BUKAN DARI tbl_joblist_detail
						$TOT_USEGEJ = 0;
						$sqlUBGEJ 	= "SELECT SUM(A.Base_Debet) AS TOT_USEGEJ FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'GEJ'
										WHERE A.proj_Code = '$PRJCODE'
											AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBGEJ 	= $this->db->query($sqlUBGEJ)->result();
						foreach($resUBGEJ as $rowBGEJ) :
							$TOT_USEGEJ	= $rowBGEJ->TOT_USEGEJ;
						endforeach;

						$TOT_UCASH 	= 0;
						$sqlUCASH 	= "SELECT SUM(A.Base_Debet) AS TOT_UCASH FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'CHO'
										WHERE A.proj_Code IN (SELECT C.PRJCODE FROM tbl_project C WHERE C.PRJCODE_HO = '$PRJCODE')
											AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUCASH 	= $this->db->query($sqlUCASH)->result();
						foreach($resUCASH as $rowCASH) :
							$TOT_UCASH	= $rowCASH->TOT_UCASH;
						endforeach;

						$TOT_USEUM 	= 0;
						$sqlUBUM 	= "SELECT SUM(A.Base_Debet) AS TOT_USEUM FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'UM'
										WHERE A.proj_Code = '$PRJCODE'
											AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBUM 	= $this->db->query($sqlUBUM)->result();
						foreach($resUBUM as $rowBUM) :
							$TOT_USEUM	= $rowBUM->TOT_USEUM;
						endforeach;

						$TOT_USEOPN = 0;
						$sqlUBOPN 	= "SELECT SUM(A.Base_Debet) AS TOT_USEOPN FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'OPN'
										WHERE A.proj_Code = '$PRJCODE'
										AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBOPN 	= $this->db->query($sqlUBOPN)->result();
						foreach($resUBOPN as $rowBOPN) :
							$TOT_USEOPN	= $rowBOPN->TOT_USEOPN;
						endforeach;

						$TOT_USEOPR	= $TOT_USEGEJ + $TOT_UCASH;			// JOURNAL NON-MATERIAL
						$TOT_USEUMX	= $TOT_USEUM + $TOT_USEOPN;			// MATERIAL

						$TOTUSEDM 	= $TOT_USEOPR + $TOT_USEUMX;
					
					$PERCUSEDV	= round($TOTUSEDM / $TOTBUDG * 100, 2);
					$PERCUSED	= round($TOTUSEDM / $TOTBUDG * 100, 6);
					if($PERCUSED <= 25)
						$GRFCOL	= 'danger';
					elseif($PERCUSED <= 50)
						$GRFCOL	= 'warning';
					elseif($PERCUSED <= 75)
						$GRFCOL	= 'primary';
					elseif($PERCUSED <= 100)
						$GRFCOL	= 'success';
					
				if($isActif == 1)
				{
					$isActDesc 	= $Active;
					$STATCOL	= 'success';
				}
				else
				{
					$isActDesc 	= $Inactive;
					$STATCOL	= 'danger';
				}

				// PM AREA
					if($PRJ_MNG == '')
					{
						$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
						$PRJ_MNG 		= "[ no selected ID ]";
						$EMPNAMEMng		= "[ no selected ]";
						$BirthPlace		= "-";
						$BirthDate		= "0000-00-00";
					}
					else
					{
						$imgempfNmX 	= "username.jpg";
						$sqlIMGCrt		= "SELECT imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$PRJ_MNG'";
						$resIMGCrt 		= $this->db->query($sqlIMGCrt)->result();
						foreach($resIMGCrt as $rowGIMGCrt) :
							$imgempfNmX = $rowGIMGCrt->imgemp_filenameX;
						endforeach;
						
						$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/'.$PRJ_MNG.'/'.$imgempfNmX);
						if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$PRJ_MNG))
						{
							$imgMng		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
						}

						$EMPNAMEMng		= "";
						$BirthPlace		= "-";
						$BirthDate		= "0000-00-00";
						$sqlEmpCrt		= "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME, Birth_Place, Date_Of_Birth FROM tbl_employee WHERE Emp_ID = '$PRJ_MNG'";
						$resEmpCrt 		= $this->db->query($sqlEmpCrt)->result();
						foreach($resEmpCrt as $rowEmpCrt) :
							$EMPNAMEMng	= strtolower($rowEmpCrt->EMP_NAME);
							$BirthPlace	= strtolower($rowEmpCrt->Birth_Place);
							$BirthDate	= strftime('%d %B %Y', strtotime($rowEmpCrt->Date_Of_Birth));
						endforeach;
					}
				
                $secUpd				= site_url($secURL.$this->url_encryption_helper->encode_url($PRJCODE));               	
				$output['data'][] 	= array("<div style='text-align:center; white-space:nowrap'>".anchor("$secUpd",$PRJCODE,array('class' => 'update')).' '."</div>",
									  		"<div style='white-space:nowrap'>
									  		<strong> ".strtoupper($PRJNAME1)."</strong><br><br>
									  		<strong><i class='fa fa-user'></i> ".$Director."</strong><br>
									  		<div class='box-comments' style='background-color: transparent; margin-left: 15px'>
										  		<div class='box-comment'>
									                <!-- User image -->
									                <img class='img-circle img-sm' src='".$imgMng."' alt='User Image'>
									                <div class='comment-text'>
									                   	<span class='username'>
									                        ".ucwords($EMPNAMEMng)."
									                    </span>
								                  		".$PRJ_MNG."<br>
								                  		".ucwords($BirthPlace).", ".$BirthDate."<strong>
									                </div>
									            </div>
								            </div>",
											"<strong><i class='fa fa-map-marker margin-r-5'></i> ".$Location." </strong>
									  		<div style='margin-left: 15px'>
										  		<p class='text-muted'>
										  			".$PRJLOCT."
										  		</p>
										  	</div>
											<strong><i class='fa fa-phone margin-r-5'></i> ".$Contact." </strong>
									  		<div style='margin-left: 15px'>
										  		<p class='text-muted'>
										  			".$PRJTELP.", ".$PRJFAX."<br>
										  			".$PRJMAIL."
										  		</p>
										  	</div>",
											"<div style='text-align:center; white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".$isActDesc."</span></div>",
											"<div class='cssProgress'>
											    <div class='progress3'>
													<div id='progressbarXX' style='text-align: center;'>
														<div class='cssProgress-bar cssProgress-".$GRFCOL." cssProgress-active' style='width: ".$PERCUSED."%; text-align:center; font-weight:bold;'></div>
														<span class='cssProgress-label' style='font-size: 12px'>".$PERCUSEDV." %</span>
													</div>
												</div>
											</div>
											<strong><i class='fa fa-dollar margin-r-5'></i> ".$usageCateg." </strong>
										    <div style='margin-left: 15px'>
										  		<p class='text-muted'>
										  			IDR ".number_format($TOT_USEOPR,2)." (Journal)<br>IDR ".number_format($TOT_USEUMX,2)." ($outDesc)
										  		</p>
										  	</div>");
				$noU			= $noU + 1;
			}
			
			

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataHOFFICE_211213() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$secURL		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
    		if($TranslCode == 'usageCateg')$usageCateg = $LangTransl;
        endforeach;

        if($LangID == 'IND')
        {
        	$outDesc	= "Peng. Material: UM, OPN, (Man. : Produksi)";
        }
        else
        {
        	$outDesc	= "Use Material: UM, OPN, (Man. : Production)";
        }
		
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
			
			$columns_valid 	= array("");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_projectlist->get_AllDataHOFFC($DefEmp_ID, $search); 
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_projectlist->get_AllDataHOFFL($DefEmp_ID, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PRJCODE 		= $dataI['PRJCODE'];
				$PRJCODE_HO		= $dataI['PRJCODE_HO'];
				$PRJPERIOD		= $dataI['PRJPERIOD'];
				$PRJCNUM		= $dataI['PRJCNUM'];
				$PRJNAME		= $dataI['PRJNAME'];
				$PRJLOCT		= $dataI['PRJLOCT'];
				$PRJCOST		= $dataI['PRJCOST'];
				$PRJDATE		= $dataI['PRJDATE'];
				$myDateProj 	= $dataI['PRJDATE'];
				$PRJEDAT		= $dataI['PRJEDAT'];
				$PRJEDATV		= strftime('%d %B %Y', strtotime($PRJEDAT));
				$PRJSTAT		= $dataI['PRJSTAT'];
				$isActif 		= $dataI['PRJSTAT'];
				$PRJ_MNG		= $dataI['PRJ_MNG'];
				$PRJTELP		= $dataI['PRJTELP'];
				$PRJFAX			= $dataI['PRJFAX'];
				$PRJMAIL		= $dataI['PRJMAIL'];
				
				// GET PROJECT DETAIL
					//$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
					$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$result = $this->db->query($sqlX)->result();
					foreach($result as $rowx) :
						$PRJNAME1	= $rowx->PRJNAME;
					endforeach;
				
					if($PRJSTAT == 0) $PRJSTATDesc = "New";
					elseif($PRJSTAT == 1) $PRJSTATDesc = "Confirm";		
				
					if($myDateProj == '0000-00-00')
					{
						$sqlX = "SELECT PRJDATE FROM tbl_project WHERE PRJCODE = '$prjcode'";
						$result = $this->db->query($sqlX)->result();
						foreach($result as $rowx) :
							$PRJDATE	= $rowx->PRJDATE;
						endforeach;
					}
					$PRJDATEV		= strftime('%d %B %Y', strtotime($PRJDATE));
				
				// PRJ BUDGET DETAIL
					$TOTBP		= 0;	// Budget Plan
					$TOTADD		= 0;
					$TOTUSEDM	= 0;
					$sqlBudg 	= "SELECT SUM(ITM_BUDG) AS TOTBP, SUM(ADD_JOBCOST) AS TOTADD, SUM(ITM_USED_AM) AS TOTUSEDM FROM tbl_joblist_detail 
									WHERE ISLAST = 1 AND PRJCODE IN (SELECT C.PRJCODE FROM tbl_project C WHERE C.PRJCODE_HO = '$PRJCODE')";
					$resBudg 	= $this->db->query($sqlBudg)->result();
					foreach($resBudg as $rowBT) :
						$TOTBP		= $rowBT->TOTBP;
						$TOTADD		= $rowBT->TOTADD;
						$TOTUSEDM	= $rowBT->TOTUSEDM;
					endforeach;
					$TOTBUDG	= $TOTBP + $TOTADD;
					if($TOTBUDG == 0)
						$TOTBUDG	= 1;
					
					// TOTAL PENGGUNAAN BUDGET DIAMBIL DARI JOURNAL, BUKAN DARI tbl_joblist_detail
						$TOT_USEGEJ = 0;
						$sqlUBGEJ 	= "SELECT SUM(A.Base_Debet) AS TOT_USEGEJ FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'GEJ'
										WHERE A.proj_Code = '$PRJCODE'
											AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBGEJ 	= $this->db->query($sqlUBGEJ)->result();
						foreach($resUBGEJ as $rowBGEJ) :
							$TOT_USEGEJ	= $rowBGEJ->TOT_USEGEJ;
						endforeach;

						$TOT_UCASH 	= 0;
						$sqlUCASH 	= "SELECT SUM(A.Base_Debet) AS TOT_UCASH FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'CHO'
										WHERE A.proj_Code IN (SELECT C.PRJCODE FROM tbl_project C WHERE C.PRJCODE_HO = '$PRJCODE')
											AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUCASH 	= $this->db->query($sqlUCASH)->result();
						foreach($resUCASH as $rowCASH) :
							$TOT_UCASH	= $rowCASH->TOT_UCASH;
						endforeach;

						$TOT_USEUM 	= 0;
						$sqlUBUM 	= "SELECT SUM(A.Base_Debet) AS TOT_USEUM FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'UM'
										WHERE A.proj_Code = '$PRJCODE'
											AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBUM 	= $this->db->query($sqlUBUM)->result();
						foreach($resUBUM as $rowBUM) :
							$TOT_USEUM	= $rowBUM->TOT_USEUM;
						endforeach;

						$TOT_USEOPN = 0;
						$sqlUBOPN 	= "SELECT SUM(A.Base_Debet) AS TOT_USEOPN FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'OPN'
										WHERE A.proj_Code = '$PRJCODE'
										AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBOPN 	= $this->db->query($sqlUBOPN)->result();
						foreach($resUBOPN as $rowBOPN) :
							$TOT_USEOPN	= $rowBOPN->TOT_USEOPN;
						endforeach;

						$TOT_USEOPR	= $TOT_USEGEJ + $TOT_UCASH;			// JOURNAL NON-MATERIAL
						$TOT_USEUMX	= $TOT_USEUM + $TOT_USEOPN;			// MATERIAL

						$TOTUSEDM 	= $TOT_USEOPR + $TOT_USEUMX;
					
					$PERCUSEDV	= round($TOTUSEDM / $TOTBUDG * 100, 2);
					$PERCUSED	= round($TOTUSEDM / $TOTBUDG * 100, 6);
					if($PERCUSED <= 25)
						$GRFCOL	= 'danger';
					elseif($PERCUSED <= 50)
						$GRFCOL	= 'warning';
					elseif($PERCUSED <= 75)
						$GRFCOL	= 'primary';
					elseif($PERCUSED <= 100)
						$GRFCOL	= 'success';
					
				if($isActif == 1)
				{
					$isActDesc 	= $Active;
					$STATCOL	= 'success';
				}
				else
				{
					$isActDesc 	= $Inactive;
					$STATCOL	= 'danger';
				}

				// PM AREA
					if($PRJ_MNG == '')
					{
						$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
						$PRJ_MNG 		= "[ no selected ID ]";
						$EMPNAMEMng		= "[ no selected ]";
						$BirthPlace		= "-";
						$BirthDate		= "0000-00-00";
					}
					else
					{
						$imgempfNmX 	= "username.jpg";
						$sqlIMGCrt		= "SELECT imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$PRJ_MNG'";
						$resIMGCrt 		= $this->db->query($sqlIMGCrt)->result();
						foreach($resIMGCrt as $rowGIMGCrt) :
							$imgempfNmX = $rowGIMGCrt->imgemp_filenameX;
						endforeach;
						
						$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/'.$PRJ_MNG.'/'.$imgempfNmX);
						if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$PRJ_MNG))
						{
							$imgMng		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
						}

						$EMPNAMEMng		= "";
						$BirthPlace		= "-";
						$BirthDate		= "0000-00-00";
						$sqlEmpCrt		= "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME, Birth_Place, Date_Of_Birth FROM tbl_employee WHERE Emp_ID = '$PRJ_MNG'";
						$resEmpCrt 		= $this->db->query($sqlEmpCrt)->result();
						foreach($resEmpCrt as $rowEmpCrt) :
							$EMPNAMEMng	= strtolower($rowEmpCrt->EMP_NAME);
							$BirthPlace	= strtolower($rowEmpCrt->Birth_Place);
							$BirthDate	= strftime('%d %B %Y', strtotime($rowEmpCrt->Date_Of_Birth));
						endforeach;
					}
				
                $secUpd				= site_url($secURL.$this->url_encryption_helper->encode_url($PRJCODE));               	
				$output['data'][] 	= array("<div style='text-align:center; white-space:nowrap'>".anchor("$secUpd",$PRJCODE,array('class' => 'update')).' '."</div>",
									  		"<div style='white-space:nowrap'>
									  		<strong> ".strtoupper($PRJNAME1)."</strong><br><br>
									  		<strong><i class='fa fa-user'></i> ".$Director."</strong><br>
									  		<div class='box-comments' style='background-color: transparent; margin-left: 15px'>
										  		<div class='box-comment'>
									                <!-- User image -->
									                <img class='img-circle img-sm' src='".$imgMng."' alt='User Image'>
									                <div class='comment-text'>
									                   	<span class='username'>
									                        ".ucwords($EMPNAMEMng)."
									                    </span>
								                  		".$PRJ_MNG."<br>
								                  		".ucwords($BirthPlace).", ".$BirthDate."<strong>
									                </div>
									            </div>
								            </div>",
											"<strong><i class='fa fa-map-marker margin-r-5'></i> ".$Location." </strong>
									  		<div style='margin-left: 15px'>
										  		<p class='text-muted'>
										  			".$PRJLOCT."
										  		</p>
										  	</div>
											<strong><i class='fa fa-phone margin-r-5'></i> ".$Contact." </strong>
									  		<div style='margin-left: 15px'>
										  		<p class='text-muted'>
										  			".$PRJTELP.", ".$PRJFAX."<br>
										  			".$PRJMAIL."
										  		</p>
										  	</div>",
											"<div style='text-align:center; white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".$isActDesc."</span></div>",
											"<div class='cssProgress'>
											    <div class='progress3'>
													<div id='progressbarXX' style='text-align: center;'>
														<div class='cssProgress-bar cssProgress-".$GRFCOL." cssProgress-active' style='width: ".$PERCUSED."%; text-align:center; font-weight:bold;'></div>
														<span class='cssProgress-label' style='font-size: 12px'>".$PERCUSEDV." %</span>
													</div>
												</div>
											</div>
											<strong><i class='fa fa-dollar margin-r-5'></i> ".$usageCateg." </strong>
										    <div style='margin-left: 15px'>
										  		<p class='text-muted'>
										  			IDR ".number_format($TOT_USEOPR,2)." (Journal)<br>IDR ".number_format($TOT_USEUMX,2)." ($outDesc)
										  		</p>
										  	</div>");
				$noU			= $noU + 1;
			}
			
			

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataHOFFICE() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$secURL		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
    		if($TranslCode == 'usageCateg')$usageCateg = $LangTransl;
        endforeach;

        if($LangID == 'IND')
        {
        	$outDesc	= "Peng. Material: UM, OPN, (Man. : Produksi)";
        }
        else
        {
        	$outDesc	= "Use Material: UM, OPN, (Man. : Production)";
        }
		
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
			
			$columns_valid 	= array("");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_projectlist->get_AllDataHOFFC($DefEmp_ID, $search); 
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_projectlist->get_AllDataHOFFL($DefEmp_ID, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PRJCODE 		= $dataI['PRJCODE'];
				$PRJCODE_HO		= $dataI['PRJCODE_HO'];
				$PRJPERIOD		= $dataI['PRJPERIOD'];
				$PRJCNUM		= $dataI['PRJCNUM'];
				$PRJNAME		= $dataI['PRJNAME'];
				$PRJLOCT		= $dataI['PRJLOCT'];
				$PRJCOST		= $dataI['PRJCOST'];
				$PRJDATE		= $dataI['PRJDATE'];
				$myDateProj 	= $dataI['PRJDATE'];
				$PRJEDAT		= $dataI['PRJEDAT'];
				$PRJEDATV		= strftime('%d %B %Y', strtotime($PRJEDAT));
				$PRJSTAT		= $dataI['PRJSTAT'];
				$isActif 		= $dataI['PRJSTAT'];
				$PRJ_MNG		= $dataI['PRJ_MNG'];
				$PRJTELP		= $dataI['PRJTELP'];
				$PRJFAX			= $dataI['PRJFAX'];
				$PRJMAIL		= $dataI['PRJMAIL'];
				$PRJNOTE		= $dataI['PRJNOTE'];
				
				// GET PROJECT DETAIL
					//$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
					$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$result = $this->db->query($sqlX)->result();
					foreach($result as $rowx) :
						$PRJNAME1	= $rowx->PRJNAME;
					endforeach;
				
					if($PRJSTAT == 0) $PRJSTATDesc = "New";
					elseif($PRJSTAT == 1) $PRJSTATDesc = "Confirm";		
				
					if($myDateProj == '0000-00-00')
					{
						$sqlX = "SELECT PRJDATE FROM tbl_project WHERE PRJCODE = '$prjcode'";
						$result = $this->db->query($sqlX)->result();
						foreach($result as $rowx) :
							$PRJDATE	= $rowx->PRJDATE;
						endforeach;
					}
					$PRJDATEV		= strftime('%d %B %Y', strtotime($PRJDATE));
				
				// PRJ BUDGET DETAIL
					$TOTBP		= 0;	// Budget Plan
					$TOTADD		= 0;
					$TOTUSEDM	= 0;
					$sqlBudg 	= "SELECT SUM(ITM_BUDG) AS TOTBP, SUM(ADD_JOBCOST) AS TOTADD, SUM(ITM_USED_AM) AS TOTUSEDM FROM tbl_joblist_detail 
									WHERE ISLAST = 1 AND PRJCODE IN (SELECT C.PRJCODE FROM tbl_project C WHERE C.PRJCODE_HO = '$PRJCODE')";
					$resBudg 	= $this->db->query($sqlBudg)->result();
					foreach($resBudg as $rowBT) :
						$TOTBP		= $rowBT->TOTBP;
						$TOTADD		= $rowBT->TOTADD;
						$TOTUSEDM	= $rowBT->TOTUSEDM;
					endforeach;
					$TOTBUDG	= $TOTBP + $TOTADD;
					if($TOTBUDG == 0)
						$TOTBUDG	= 1;
					
					// TOTAL PENGGUNAAN BUDGET DIAMBIL DARI JOURNAL, BUKAN DARI tbl_joblist_detail
						$TOT_USEGEJ = 0;
						$sqlUBGEJ 	= "SELECT SUM(A.Base_Debet) AS TOT_USEGEJ FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'GEJ'
										WHERE A.proj_Code = '$PRJCODE'
											AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBGEJ 	= $this->db->query($sqlUBGEJ)->result();
						foreach($resUBGEJ as $rowBGEJ) :
							$TOT_USEGEJ	= $rowBGEJ->TOT_USEGEJ;
						endforeach;

						$TOT_UCASH 	= 0;
						$sqlUCASH 	= "SELECT SUM(A.Base_Debet) AS TOT_UCASH FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'CHO'
										WHERE A.proj_Code IN (SELECT C.PRJCODE FROM tbl_project C WHERE C.PRJCODE_HO = '$PRJCODE')
											AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUCASH 	= $this->db->query($sqlUCASH)->result();
						foreach($resUCASH as $rowCASH) :
							$TOT_UCASH	= $rowCASH->TOT_UCASH;
						endforeach;

						$TOT_USEUM 	= 0;
						$sqlUBUM 	= "SELECT SUM(A.Base_Debet) AS TOT_USEUM FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'UM'
										WHERE A.proj_Code = '$PRJCODE'
											AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBUM 	= $this->db->query($sqlUBUM)->result();
						foreach($resUBUM as $rowBUM) :
							$TOT_USEUM	= $rowBUM->TOT_USEUM;
						endforeach;

						$TOT_USEOPN = 0;
						$sqlUBOPN 	= "SELECT SUM(A.Base_Debet) AS TOT_USEOPN FROM tbl_journaldetail A
											INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
												AND B.JournalType = 'OPN'
										WHERE A.proj_Code = '$PRJCODE'
										AND A.GEJ_STAT = 3 AND (A.JOBCODEID != '' OR A.ITM_CODE !='')";
						$resUBOPN 	= $this->db->query($sqlUBOPN)->result();
						foreach($resUBOPN as $rowBOPN) :
							$TOT_USEOPN	= $rowBOPN->TOT_USEOPN;
						endforeach;

						$TOT_USEOPR	= $TOT_USEGEJ + $TOT_UCASH;			// JOURNAL NON-MATERIAL
						$TOT_USEUMX	= $TOT_USEUM + $TOT_USEOPN;			// MATERIAL

						$TOTUSEDM 	= $TOT_USEOPR + $TOT_USEUMX;
					
					$PERCUSEDV	= round($TOTUSEDM / $TOTBUDG * 100, 2);
					$PERCUSED	= round($TOTUSEDM / $TOTBUDG * 100, 6);
					if($PERCUSED <= 25)
						$GRFCOL	= 'danger';
					elseif($PERCUSED <= 50)
						$GRFCOL	= 'warning';
					elseif($PERCUSED <= 75)
						$GRFCOL	= 'primary';
					elseif($PERCUSED <= 100)
						$GRFCOL	= 'success';
					
				if($isActif == 1)
				{
					$isActDesc 	= $Active;
					$STATCOL	= 'success';
				}
				else
				{
					$isActDesc 	= $Inactive;
					$STATCOL	= 'danger';
				}
				
                $secUpd				= site_url($secURL.$this->url_encryption_helper->encode_url($PRJCODE));               	
				$output['data'][] 	= array("<div style='text-align:center; white-space:nowrap'>".anchor("$secUpd",$PRJCODE,array('class' => 'update')).' '."</div>",
									  		"<div style='white-space:nowrap'>
									  		<strong> ".strtoupper($PRJNAME1)."</strong>
									  		<div style='font-style: italic;'>
										  		<p class='text-muted'>
										  			".$PRJNOTE."
										  		</p>
										  	</div>",
											"<strong><i class='fa fa-map-marker margin-r-5'></i> ".$Location." </strong>
									  		<div style='margin-left: 15px'>
										  		<p class='text-muted'>
										  			".$PRJLOCT."
										  		</p>
										  	</div>
											<strong><i class='fa fa-phone margin-r-5'></i> ".$Contact." </strong>
									  		<div style='margin-left: 15px'>
										  		<p class='text-muted'>
										  			".$PRJTELP.", ".$PRJFAX."<br>
										  			".$PRJMAIL."
										  		</p>
										  	</div>",
											"<div style='text-align:center; white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".$isActDesc."</span></div>",
											"<div class='cssProgress'>
											    <div class='progress3'>
													<div id='progressbarXX' style='text-align: center;'>
														<div class='cssProgress-bar cssProgress-".$GRFCOL." cssProgress-active' style='width: ".$PERCUSED."%; text-align:center; font-weight:bold;'></div>
														<span class='cssProgress-label' style='font-size: 12px'>".$PERCUSEDV." %</span>
													</div>
												</div>
											</div>
											<strong><i class='fa fa-dollar margin-r-5'></i> ".$usageCateg." </strong>
										    <div style='margin-left: 15px'>
										  		<p class='text-muted'>
										  			IDR ".number_format($TOT_USEOPR,2)." (Journal)<br>IDR ".number_format($TOT_USEUMX,2)." ($outDesc)
										  		</p>
										  	</div>");
				$noU			= $noU + 1;
			}
			
			

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataComp() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$secURL		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
    		if($TranslCode == 'usageCateg')$usageCateg = $LangTransl;
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
			
			$columns_valid 	= array("");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_projectlist->get_AllDataLCC($DefEmp_ID, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_projectlist->get_AllDataLCL($DefEmp_ID, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$proj_Number 	= $dataI['proj_Number'];
				$PRJCODE 		= $dataI['PRJCODE'];
				$PRJCODE_HO		= $dataI['PRJCODE_HO'];
				$PRJPERIOD		= $dataI['PRJPERIOD'];
				$PRJCNUM		= $dataI['PRJCNUM'];
				$PRJNAME		= $dataI['PRJNAME'];
				$PRJLOCT		= $dataI['PRJLOCT'];
				$PRJCOST		= $dataI['PRJCOST'];
				$PRJDATE		= $dataI['PRJDATE'];
				$myDateProj 	= $dataI['PRJDATE'];
				$PRJEDAT		= $dataI['PRJEDAT'];
				$PRJEDATV		= strftime('%d %B %Y', strtotime($PRJEDAT));
				$PRJSTAT		= $dataI['PRJSTAT'];
				$isActif 		= $dataI['PRJSTAT'];
				$PRJ_MNG		= $dataI['PRJ_MNG'];
				$PRJLOCT 		= $dataI['PRJLOCT'];
				$PRJADD 		= $dataI['PRJADD'];
				$PRJTELP 		= $dataI['PRJTELP'];
				$PRJFAX			= $dataI['PRJFAX'];
				$PRJMAIL		= $dataI['PRJMAIL'];
				$PRJCATEG 		= $dataI['PRJCATEG'];
				$PRJTYPE 		= $dataI['PRJTYPE'];
				$PRJNOTE 		= $dataI['PRJNOTE'];
				$isHO 			= $dataI['isHO'];

				$PRJNOTED		= "-";
				if($PRJNOTE != '')
					$PRJNOTED	= $PRJNOTE;

				// GET CATEGORY
	                if($PRJTYPE == 1)
	                	$PRJCATEGD1 = 'Head Company - ';
	                else
	                	$PRJCATEGD1	= "";

	                if($PRJCATEG == 1)
	                    $PRJCATEGD  = $PRJCATEGD1.'Kontraktor';
	                elseif($PRJCATEG == 2)
	                    $PRJCATEGD  = $PRJCATEGD1.'Manufacture';
	                elseif($PRJCATEG == 3)
	                    $PRJCATEGD  = $PRJCATEGD1.'Tambang';
	                else
	                    $PRJCATEGD  = $PRJCATEGD1.'Lainnya';


				// GET ADDRESS
					$PRJADD2 	= "-";
					if($PRJLOCT != '')
						$PRJADD2	= $PRJLOCT."<br>".$PRJADD;

					$PRJTELPD	= "-";
					if($PRJTELP != '')
						$PRJTELPD	= "Telp. : ".$PRJTELP;

					$PRJMAILD	= "-";
					if($PRJMAIL != '')
						$PRJMAILD	= "E-mail : ".$PRJMAIL;
				
				// PRJ DETAIL INFORMATION
					//$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
					$sqlX 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$result = $this->db->query($sqlX)->result();
					foreach($result as $rowx) :
						$PRJNAME1	= $rowx->PRJNAME;
					endforeach;
					
					if($PRJSTAT == 0) $PRJSTATDesc = "New";
					elseif($PRJSTAT == 1) $PRJSTATDesc = "Confirm";		
				
					if($myDateProj == '0000-00-00')
					{
						$sqlX = "SELECT PRJDATE FROM tbl_project WHERE PRJCODE = '$prjcode'";
						$result = $this->db->query($sqlX)->result();
						foreach($result as $rowx) :
							$PRJDATE		= $rowx->PRJDATE;
						endforeach;
					}
					$PRJDATEV		= strftime('%d %B %Y', strtotime($PRJDATE));
					
					$TOTBP		= 0;	// Budget Plan
					$TOTADD		= 0;
					$TOTUSEDM	= 0;
					$sqlBudg 	= "SELECT SUM(ITM_BUDG) AS TOTBP, SUM(ADD_JOBCOST) AS TOTADD, SUM(ITM_USED_AM) AS TOTUSEDM FROM tbl_joblist_detail 
									WHERE ISLAST = 1 AND PRJCODE = '$PRJCODE'";
					$resBudg 	= $this->db->query($sqlBudg)->result();
					foreach($resBudg as $rowBT) :
						$TOTBP		= $rowBT->TOTBP;
						$TOTADD		= $rowBT->TOTADD;
						$TOTUSEDM	= $rowBT->TOTUSEDM;
					endforeach;
					$TOTBUDG	= $TOTBP + $TOTADD;
					if($TOTBUDG == 0)
						$TOTBUDG	= 1;
					
					$PERCUSED	= round($TOTUSEDM / $TOTBUDG * 100, 0);
					if($PERCUSED <= 25)
						$GRFCOL	= 'danger';
					elseif($PERCUSED <= 50)
						$GRFCOL	= 'warning';
					elseif($PERCUSED <= 75)
						$GRFCOL	= 'primary';
					elseif($PERCUSED <= 100)
						$GRFCOL	= 'success';
						
					if($isActif == 1)
					{
						$isActDesc 	= $Active;
						$STATCOL	= 'success';
					}
					else
					{
						$isActDesc 	= $Inactive;
						$STATCOL	= 'danger';
					}

					$sqlChild		= "tbl_project WHERE PRJCODE_HO = '$PRJCODE'";
					$resChild		= $this->db->count_all($sqlChild);
					if($resChild == 0 && $isHO == 0)
					{
						$disDel		= "";
						$disFunc	= "deleteDOC";
					}
					else
					{
						$disDel		= "disabled='disabled'";
						$disFunc	= "deleteDOCX";
					}

				// PM AREA
					if($PRJ_MNG == '')
					{
						$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
						$PRJ_MNG 		= "[ no selected ID ]";
						$EMPNAMEMng		= "[ no selected ]";
						$BirthPlace		= "-";
						$BirthDate		= "0000-00-00";
					}
					else
					{
						$imgempfNmX 	= "username.jpg";
						$sqlIMGCrt		= "SELECT imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$PRJ_MNG'";
						$resIMGCrt 		= $this->db->query($sqlIMGCrt)->result();
						foreach($resIMGCrt as $rowGIMGCrt) :
							$imgempfNmX = $rowGIMGCrt->imgemp_filenameX;
						endforeach;
						
						$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/'.$PRJ_MNG.'/'.$imgempfNmX);
						if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$PRJ_MNG))
						{
							$imgMng		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
						}

						$EMPNAMEMng		= "";
						$BirthPlace		= "-";
						$BirthDate		= "0000-00-00";
						$sqlEmpCrt		= "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME, Birth_Place, Date_Of_Birth FROM tbl_employee WHERE Emp_ID = '$PRJ_MNG'";
						$resEmpCrt 		= $this->db->query($sqlEmpCrt)->result();
						foreach($resEmpCrt as $rowEmpCrt) :
							$EMPNAMEMng	= strtolower($rowEmpCrt->EMP_NAME);
							$BirthPlace	= strtolower($rowEmpCrt->Birth_Place);
							$BirthDate	= strftime('%d %B %Y', strtotime($rowEmpCrt->Date_Of_Birth));
						endforeach;
					}
				
                $secUpd			= site_url('c_comprof/c_c0mPL15t/u180c2gdt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                $secvwPRJ		= site_url('c_comprof/c_c0mPL15t/c_project_progress/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                $secDel 		= base_url().'index.php/__l1y/trashHO/?id=';
                $delID 			= "$secDel~$PRJCODE";

				$disJava		= $secUpd;
				$disEdit		= "";
				if($PRJTYPE == 1)
				{
					$disJava	= "javascript:void(null)";
					$disEdit	= "disabled='disabled'";
				}

                $secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
                				<label style='white-space:nowrap'>
							   	<a href='".$disJava."' class='btn btn-info btn-xs' title='Update' ".$disEdit.">
									<i class='glyphicon glyphicon-pencil'></i>
							   	</a>
								<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='".$disFunc."(".$noU.")' title='Delete' ".$disDel.">
									<i class='fa fa-trash-o'></i>
								</a>
								</label>";

				$output['data'][] = array($dataI['PRJCODE'],
										  	"<div style='white-space:nowrap'>
										  		<p><strong style='font-size:13px'>".$PRJNAME1."</strong></p>
										  		<strong><i class='fa fa-user'></i> ".$Director."</strong><br>
										  		<div class='box-comments' style='background-color: transparent; margin-left: 15px'>
											  		<div class='box-comment'>
										                <!-- User image -->
										                <img class='img-circle img-sm' src='".$imgMng."' alt='User Image'>
										                <div class='comment-text'>
										                   	<span class='username'>
										                        ".ucwords($EMPNAMEMng)."
										                    </span>
									                  		".$PRJ_MNG."<br>
									                  		".ucwords($BirthPlace).", ".$BirthDate."
										                </div>
										            </div>
									            </div>
									  	  	</div>",
										  	"<div style='white-space:nowrap'>$PRJCATEGD</div>",
										  	"<strong><i class='fa fa-map-marker margin-r-5'></i> ".$Location."</strong>
									  		<div style='margin-left: 15px'>
										  		<p class='text-muted'>".$PRJADD2."</p>
										  	</div>
										  	<strong><i class='glyphicon glyphicon-phone-alt margin-r-5'></i>".$Phone." </strong>
									  		<div style='margin-left: 17px'>
										  		<p class='text-muted'>".$PRJTELPD."</p>
										  	</div>
										  	<strong><i class='fa fa-envelope margin-r-5'></i>E-mail</strong>
									  		<div style='margin-left: 17px'>
										  		<p class='text-muted'>".$PRJMAILD."</p>
										  	</div>",
										 	$PRJNOTED,
										  	"<div style='text-align:center; white-space:nowrap'>$secAction</div>");

				$noU			= $noU + 1;
			}
			
			

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function svForm()
	{
		$prjcode 	= $_POST['prjcode'];
		$empid 		= $_POST['empid'];
		$emp_msg 	= $_POST['emp_msg'];
		$userfile 	= $_POST['userfile'];

		/*if($task == 'add')
		{
			$dataSUBJ 	= array('ag_code'	=> date('YmdHis'),
								'sy_code'	=> $sy_code,
								'ag_dates'	=> date('Y-m-d',strtotime(str_replace('/', '-', $_POST['date1']))),
								'ag_datef'	=> date('Y-m-d',strtotime(str_replace('/', '-', $_POST['date2']))),
								'ag_name'	=> $ag_name,
								'ag_smt'	=> $ag_smt);
			$this->m_agenda->add($dataSUBJ);
		}
		else
		{
			$dataSUBJ 	= array('sy_code'	=> $sy_code,
								'ag_dates'	=> date('Y-m-d',strtotime(str_replace('/', '-', $_POST['date1']))),
								'ag_datef'	=> date('Y-m-d',strtotime(str_replace('/', '-', $_POST['date2']))),
								'ag_name'	=> $ag_name,
								'ag_smt'	=> $ag_smt);
			$this->m_agenda->update($ag_code, $dataSUBJ);
		}*/
		echo $userfile;
	}
}