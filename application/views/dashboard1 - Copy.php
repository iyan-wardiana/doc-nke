<?php
    // _global function
        $this->db->select('Display_Rows,decFormat,CompDesc');
        $resGlobal = $this->db->get('tglobalsetting')->result();
        foreach($resGlobal as $row) :
            $Display_Rows   = $row->Display_Rows;
            $decFormat    = $row->decFormat;
            $CompDesc     = $row->CompDesc;
        endforeach;
        $decFormat  = 2;

        date_default_timezone_set("Asia/Jakarta");

		$tNow    	= date('Y-m-d');
		$tNDate1    = date('Y-m-t', strtotime('-11 month', strtotime($tNow)));
		$tY       	= date('Y', strtotime($tNDate1));
		$tM       	= date('n', strtotime($tNDate1));

        $myPRJCODE  = '';

        $this->load->view('template/head');

        $appName 	= $this->session->userdata('appName');
		$vers     	= $this->session->userdata['vers'];
		$FlagUSER 	= $this->session->userdata['FlagUSER'];
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$Emp_ID 	= $this->session->userdata['Emp_ID'];
		$appBody    = $this->session->userdata['appBody'];
		$sysMnt		= $this->session->userdata['sysMnt'];
		$LastMntD	= $this->session->userdata['LastMntD'];
		$PRJSCATEG	= $this->session->userdata['PRJSCATEG'];
		$tgl1 		= new DateTime($LastMntD);
		$tgl2 		= new DateTime();
		$dif1 		= $tgl1->diff($tgl2);
		$dif2 		= $dif1->days;

		$cLogV		= "tbl_emp_vers WHERE EMP_ID = '$Emp_ID' AND VERS = '$vers'";
		$vLogV		= $this->db->count_all($cLogV);

		/*$isEnd 		= 0;
		if($sysMnt == 1 && $tgl2 >= $tgl1)
		{
			$isEnd 	= 2;
		}
		elseif($sysMnt == 1 && $dif2 < 6) 
		{
			$isEnd 	= 1;
		}*/

		$lasTDMnt	= date('d M Y', strtotime($LastMntD));
		$mntWarn1	= "";
		$mntWarn2	= "";

		$LangID 	= $this->session->userdata['LangID'];

		if($LangID == 'IND')
		{
			$transL01	= "Perangkat lunak ini mutahkir.";
			$transL02	= "NKE-Sys v$vers berhasil dipasang. Sembunyikan pesan ini.";
			$transL03	= "Anda tidak akan melihat peringatan ini di lain waktu. Terimakasih.";
			$transL04	= "Anda akan tetap melihat peringatan ini di saat Anda Log In. Terimakasih.";
			$transL05	= "Ya";
			$transL06	= "Tidak";
			$transL07	= "Anda dapat mengganti warna template.";
			$transL08	= "Cara Penggunaan";
			$transL09	= "Klik ikon pengaturan pada kanan atas, kemudian pilih warna.";

			/*if($isEnd == 1)
			{
				$mntWarn1	= "Perawatan Sistem";
				$mntWarn2	= "Akan berakhir pada $lasTDMnt.";
			}
			else if($isEnd == 2)
			{
				$mntWarn1	= "Perawatan Sistem";
				$mntWarn2	= "sudah berkahir pada $lasTDMnt.";
			}*/
		}
		else
		{
			$transL01	= "The software is up to date.";
			$transL02	= "NKE-Sys v$vers successfully installed. Please hide this message.";
			$transL03	= "You will not see this warning again. Thank you.";
			$transL04	= "You will still see this warning when you log in. Thank you.";
			$transL05	= "Yes";
			$transL06	= "No";
			$transL07	= "You can change the template color.";
			$transL08	= "How to use";
			$transL09	= "Click the settings icon on the top right. And select the color.";

			/*if($isEnd == 1)
			{
				$mntWarn1	= "System Maintenance";
				$mntWarn2	= "will expire on $lasTDMnt.";
			}
			else if($isEnd == 2)
			{
				$mntWarn1	= "System Maintenance";
				$mntWarn2	= "has expired on $lasTDMnt.";
			}*/
		}

        //$this->load->view('template/topbar');
        //$this->load->view('template/sidebar');

        $LOGHIST  = 1;

        $WEEK_BEF1  = mktime(0, 0, 0, date("m"), date("d")-31, date("Y"));
        $WEEK_BEF = date("Y-m-d", $WEEK_BEF1);
        $WEEK_NEXT1 = mktime(0, 0, 0, date("m"), date("d")+2, date("Y"));
        $WEEK_NEXT  = date("Y-m-d", $WEEK_NEXT1);
        $THISD    = date('d');
        $THISM    = date('m');
        $THISY    = date('Y');

    // CHECK LOG ON
        $TOTE       = 0;
        $TOTOL      = 0;
        $TOTOF      = 0;
        $sqlTLE     = "SELECT COUNT(*) AS TOTENTER
                        FROM tbl_trail_tracker
                        WHERE
                          DAY(TTR_DATE) = '$THISD' AND MONTH(TTR_DATE) = '$THISM' AND YEAR(TTR_DATE) = '$THISY' 
                          AND TTR_CATEG = 'ENTER'
                        GROUP BY
                        DATE(TTR_DATE)";
        $resTLE     = $this->db->query($sqlTLE)->result();
        foreach($resTLE as $rowTLE) :
            $TOTE   = $rowTLE->TOTENTER;
        endforeach;
        $sqlTOL     = "SELECT COUNT(*) AS TOTOL FROM tbl_employee WHERE OLStat = 1";
        $resTOL     = $this->db->query($sqlTOL)->result();
        foreach($resTOL as $rowTOL) :
            $TOTOL  = $rowTOL->TOTOL;
        endforeach;
        $sqlTOF     = "SELECT COUNT(*) AS TOTOF FROM tbl_employee WHERE OLStat = 0";
        $resTOF     = $this->db->query($sqlTOF)->result();
        foreach($resTOF as $rowTOF) :
            $TOTOF  = $rowTOF->TOTOF;
        endforeach;
        $TOTE   = $TOTOL + $TOTOF;

    // Menampilkan Grafik Project Plan Akumulate
        $jumTotPlanAkuma  = '';
        $GraphicTitleText = 3;

    // SET PROJECT
		$selPRJCODE   = "AllPRJ";
		if(isset($_POST['submitPRJ']))
		{
			$selPRJCODE = $_POST['selPRJCODE'];
		}
		
		$myPRJCODE    = '';
		if($selPRJCODE == "AllPRJ")
		{
			$myrow    = 0;
			$sql      = "SELECT A.proj_Code, B.PRJNAME
						  FROM tbl_employee_proj A
						  LEFT JOIN tbl_project B ON B.PRJCODE = A.proj_Code
						  WHERE A.Emp_ID = '$Emp_ID'";
			$result   = $this->db->query($sql)->result();
			foreach($result as $row) :
				$myrow    = $myrow + 1;
				$PRJCODED   = $row->proj_Code;
				$PRJNAMEX   = $row->PRJNAME;
				if($myrow == 1)
				{
					$myPRJCODE  = "'$PRJCODED'";
					$myPRJCODE1 = "'$PRJCODED'";
				}
				if($myrow > 1)
				{
					$myPRJCODE1 = "$myPRJCODE1, '$PRJCODED'";
					$myPRJCODE  = "$myPRJCODE1";
				}   
			endforeach;
			$myPRJCODEX = "'$PRJCODED'";
		}
		else
		{
			$myPRJCODE  = "'$selPRJCODE'";
			$myPRJCODEX = "'$selPRJCODE'";
			$PRJNAMEX = '';
			$sqlPRJ   = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = $myPRJCODE";
			$resPRJ   = $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJNAMEX   = $rowPRJ->PRJNAME;
			endforeach;
		}

    // SET DEFAULT VALUE
      	// ----------------------- REQUEST / PR
			$REQ_NEW  = 0;
			$REQ_CON  = 0;
			$REQ_APP  = 0;
			$REQ_REV  = 0;
			$REQ_REJ  = 0;
			$REQ_CLS  = 0;
			  	$TOT_REQ_NEW  = 0;
			  	$TOT_REQ_CON  = 0;
			  	$TOT_REQ_APP  = 0;
			  	$TOT_REQ_REV  = 0;
			  	$TOT_REQ_REJ  = 0;
          		$TOT_REQ_CLS  = 0;
      	// ----------------------- PURCHASE ORDER / PO
			$PO_NEW   = 0;
			$PO_CON   = 0;
			$PO_APP   = 0;
			$PO_REV   = 0;
			$PO_REJ   = 0;
			$PO_CLS   = 0;
				$TOT_PO_NEW = 0;
				$TOT_PO_CON = 0;
				$TOT_PO_APP = 0;
				$TOT_PO_REV = 0;
				$TOT_PO_REJ = 0;
				$TOT_PO_CLS = 0;
      	// ----------------------- ITEM RECEIPT / IR
			$IR_NEW   = 0;
			$IR_CON   = 0;
			$IR_APP   = 0;
			$IR_REV   = 0;
			$IR_REJ   = 0;
			$IR_CLS   = 0;
				$TOT_IR_NEW = 0;
				$TOT_IR_CON = 0;
				$TOT_IR_APP = 0;
				$TOT_IR_REV = 0;
				$TOT_IR_REJ = 0;
          		$TOT_IR_CLS = 0;
     	 // ----------------------- PURCHASE INVOICE / FAKTUR
			$PINV_NEW = 0;
			$PINV_CON = 0;  // PAYMENT
			$PINV_APP = 0;
			$PINV_HP  = 0;
			$PINV_FP  = 0;
			$PINV_REJ = 0;
				$TOT_PINV_NEW = 0;
				$TOT_PINV_CON = 0;
				$TOT_PINV_APP = 0;
				$TOT_PINV_HP  = 0;
				$TOT_PINV_FP  = 0;
				$TOT_PINV_REJ = 0;
      	// ----------------------- MC PROYEK
			$MC_NEW   = 0;
			$MC_CON   = 0;
			$MC_APP   = 0;
			$MC_REV   = 0;
			$MC_REJ   = 0;
			$MC_CLS   = 0;
				$TOT_MC_NEW = 0;
				$TOT_MC_CON = 0;
				$TOT_MC_APP = 0;
				$TOT_MC_REV = 0;
				$TOT_MC_REJ = 0;
      	// ----------------------- INVOICE / FAKTUR
			$INV_NEW  = 0;
			$INV_CON  = 0;
			$INV_APP  = 0;
			$INV_REV  = 0;
			$INV_REJ  = 0;
			$INV_CLS  = 0;
				$TOT_INV_NEW  = 0;
				$TOT_INV_CON  = 0;
				$TOT_INV_APP  = 0;
				$TOT_INV_REV  = 0;
				$TOT_INV_REJ  = 0;
				$TOT_INV_CLS  = 0;
				$TOT_INV_FP   = 0;
				$TOT_INV_NP   = 0;
      	// ----------------------- BANK PAYMENT / CB
			$CB_NEW   = 0;
			$CB_CON   = 0;
			$CB_APP   = 0;
			$CB_REV   = 0;
			$CB_REJ   = 0;
			$CB_CLS   = 0;
				$TOT_CB_NEW = 0;
				$TOT_CB_CON = 0;
				$TOT_CB_APP = 0;
				$TOT_CB_REV = 0;
				$TOT_CB_REJ = 0;
				$TOT_CB_CLS = 0;
      	// ----------------------- BANK PAYMENT / CB
			$BR_NEW   = 0;
			$BR_CON   = 0;
			$BR_APP   = 0;
			$BR_REV   = 0;
			$BR_REJ   = 0;
			$BR_CLS   = 0;
				$TOT_BR_NEW = 0;
				$TOT_BR_CON = 0;
				$TOT_BR_APP = 0;
				$TOT_BR_REV = 0;
				$TOT_BR_REJ = 0;
				$TOT_BR_CLS = 0;
      	// ----------------------- OFFERING LETTER / OFF
			$OFF_NEW   = 0;
			$OFF_CON   = 0;
			$OFF_APP   = 0;
			$OFF_REV   = 0;
			$OFF_REJ   = 0;
			$OFF_CLS   = 0;
				$TOT_OFF_NEW = 0;
				$TOT_OFF_CON = 0;
				$TOT_OFF_APP = 0;
				$TOT_OFF_REV = 0;
				$TOT_OFF_REJ = 0;
				$TOT_OFF_CLS = 0;
		// ----------------------- SALES ORDER / SO
			$SO_NEW  = 0;
			$SO_CON  = 0;
			$SO_APP  = 0;
			$SO_REV  = 0;
			$SO_REJ  = 0;
			$SO_CLS  = 0;
				$TOT_SO_NEW  = 0;
				$TOT_SO_CON  = 0;
				$TOT_SO_APP  = 0;
				$TOT_SO_REV  = 0;
				$TOT_SO_REJ  = 0;
				$TOT_SO_CLS  = 0;
		// ----------------------- JOB ORDER / JO
			$JO_NEW  = 0;
			$JO_CON  = 0;
			$JO_APP  = 0;
			$JO_REV  = 0;
			$JO_REJ  = 0;
			$JO_CLS  = 0;
				$TOT_JO_NEW  = 0;
				$TOT_JO_CON  = 0;
				$TOT_JO_APP  = 0;
				$TOT_JO_REV  = 0;
				$TOT_JO_REJ  = 0;
				$TOT_JO_CLS  = 0;
		// ----------------------- MATERIAL REQUEST / MR
			$MR_NEW  = 0;
			$MR_CON  = 0;
			$MR_APP  = 0;
			$MR_REV  = 0;
			$MR_REJ  = 0;
			$MR_CLS  = 0;
				$TOT_MR_NEW  = 0;
				$TOT_MR_CON  = 0;
				$TOT_MR_APP  = 0;
				$TOT_MR_REV  = 0;
				$TOT_MR_REJ  = 0;
				$TOT_MR_CLS  = 0;
		// ----------------------- WORK ORDER / JO / SPK
			$WO_NEW  = 0;
			$WO_CON  = 0;
			$WO_APP  = 0;
			$WO_REV  = 0;
			$WO_REJ  = 0;
			$WO_CLS  = 0;
				$TOT_WO_NEW  = 0;
				$TOT_WO_CON  = 0;
				$TOT_WO_APP  = 0;
				$TOT_WO_REV  = 0;
				$TOT_WO_REJ  = 0;
				$TOT_WO_CLS  = 0;
		// ----------------------- OPNAME
			$OPN_NEW  = 0;
			$OPN_CON  = 0;
			$OPN_APP  = 0;
			$OPN_REV  = 0;
			$OPN_REJ  = 0;
			$OPN_CLS  = 0;
				$TOT_OPN_NEW  = 0;
				$TOT_OPN_CON  = 0;
				$TOT_OPN_APP  = 0;
				$TOT_OPN_REV  = 0;
				$TOT_OPN_REJ  = 0;
				$TOT_OPN_CLS  = 0;
		// ----------------------- SHIPMENT NOTE / SN
			$SN_NEW  = 0;
			$SN_CON  = 0;
			$SN_APP  = 0;
			$SN_REV  = 0;
			$SN_REJ  = 0;
			$SN_CLS  = 0;
				$TOT_SN_NEW  = 0;
				$TOT_SN_CON  = 0;
				$TOT_SN_APP  = 0;
				$TOT_SN_REV  = 0;
				$TOT_SN_REJ  = 0;
				$TOT_SN_CLS  = 0;
		// ----------------------- SHIPMENT NOTE / SN
			$SINV_NEW  = 0;
			$SINV_CON  = 0;
			$SINV_APP  = 0;
			$SINV_REV  = 0;
			$SINV_REJ  = 0;
			$SINV_CLS  = 0;
				$TOT_SINV_NEW  = 0;
				$TOT_SINV_CON  = 0;
				$TOT_SINV_APP  = 0;
				$TOT_SINV_REV  = 0;
				$TOT_SINV_REJ  = 0;
				$TOT_SINV_CLS  = 0;

      	// ----------------------- CHART
			$GRF_LOGHIST  	= 0;
			$GRF_SALES     	= 0;
			$GRF_DPROD 		= 0;
			$GRF_PURCH     	= 0;
			$GRF_BP			= 0;
			$GRF_GOALC		= 0;
			$SSWIP_INFO		= 0;
			$BR_INFO		= 0;
			$MC_PRJ			= 0;

    	// GET QTY FOR EACH STATUS
			if($selPRJCODE == "AllPRJ")
			{
				$sqlAllData = "SELECT
									SUM(TOT_REQ) AS TOT_REQ, SUM(TOT_REQ_N) AS TOT_REQ_NEW, SUM(TOT_REQ_C) AS TOT_REQ_CON,
										SUM(TOT_REQ_A) AS TOT_REQ_APP, SUM(TOT_REQ_R) AS TOT_REQ_REV, SUM(TOT_REQ_RJ) AS TOT_REQ_REJ,
										SUM(TOT_REQ_CL) AS TOT_REQ_CLS,
									SUM(TOT_PO) AS TOT_PO, SUM(TOT_PO_N) AS TOT_PO_NEW, SUM(TOT_PO_C) AS TOT_PO_CON, SUM(TOT_PO_A) AS TOT_PO_APP,
										SUM(TOT_PO_R) AS TOT_PO_REV, SUM(TOT_PO_RJ) AS TOT_PO_REJ, SUM(TOT_PO_CL) AS TOT_PO_CLS,
									SUM(TOT_IR) AS TOT_IR, SUM(TOT_IR_N) AS TOT_IR_NEW, SUM(TOT_IR_C) AS TOT_IR_CON, SUM(TOT_IR_A) AS TOT_IR_APP,
										SUM(TOT_IR_R) AS TOT_IR_REV, SUM(TOT_IR_RJ) AS TOT_IR_REJ, SUM(TOT_IR_CL) AS TOT_IR_CLS,
									SUM(TOT_PINV) AS TOT_PINV, SUM(TOT_PINV_N) AS TOT_PINV_NEW, SUM(TOT_PINV_C) AS TOT_PINV_CON, SUM(TOT_PINV_A) AS TOT_PINV_APP,
										SUM(TOT_PINV_HP) AS TOT_PINV_HP, SUM(TOT_PINV_FP) AS TOT_PINV_FP, SUM(TOT_PINV_R) AS TOT_PINV_REJ,
									SUM(TOT_INV) AS TOT_INV, SUM(TOT_INV_FP) AS TOT_INV_FP, SUM(TOT_INV_NP) AS TOT_INV_NP,
									SUM(TOT_CB) AS TOT_CB, SUM(TOT_CB_N) AS TOT_CB_NEW, SUM(TOT_CB_C) AS TOT_CB_CON, SUM(TOT_CB_A) AS TOT_CB_APP,
										SUM(TOT_CB_R) AS TOT_CB_REV, SUM(TOT_CB_RJ) AS TOT_CB_REJ, SUM(TOT_CB_CL) AS TOT_CB_CLS,
									SUM(TOT_OFF) AS TOT_OFF, SUM(TOT_OFF_N) AS TOT_OFF_NEW, SUM(TOT_OFF_C) AS TOT_OFF_CON, SUM(TOT_OFF_A) AS TOT_OFF_APP,
										SUM(TOT_OFF_R) AS TOT_OFF_REV, SUM(TOT_OFF_RJ) AS TOT_OFF_REJ, SUM(TOT_OFF_CL) AS TOT_OFF_CLS,
									SUM(TOT_SO) AS TOT_SO, SUM(TOT_SO_N) AS TOT_SO_NEW, SUM(TOT_SO_C) AS TOT_SO_CON, SUM(TOT_SO_A) AS TOT_SO_APP,
										SUM(TOT_SO_R) AS TOT_SO_REV, SUM(TOT_SO_RJ) AS TOT_SO_REJ, SUM(TOT_SO_CL) AS TOT_SO_CLS,
									SUM(TOT_JO) AS TOT_JO, SUM(TOT_JO_N) AS TOT_JO_NEW, SUM(TOT_JO_C) AS TOT_JO_CON, SUM(TOT_JO_A) AS TOT_JO_APP,
										SUM(TOT_JO_R) AS TOT_JO_REV, SUM(TOT_JO_RJ) AS TOT_JO_REJ, SUM(TOT_JO_CL) AS TOT_JO_CLS,
									SUM(TOT_WO) AS TOT_WO, SUM(TOT_WO_N) AS TOT_WO_NEW, SUM(TOT_WO_C) AS TOT_WO_CON, SUM(TOT_WO_A) AS TOT_WO_APP,
										SUM(TOT_WO_R) AS TOT_WO_REV, SUM(TOT_WO_RJ) AS TOT_WO_REJ, SUM(TOT_WO_CL) AS TOT_WO_CLS,
									SUM(TOT_OPN) AS TOT_OPN, SUM(TOT_OPN_N) AS TOT_OPN_NEW, SUM(TOT_OPN_C) AS TOT_OPN_CON, SUM(TOT_OPN_A) AS TOT_OPN_APP,
										SUM(TOT_OPN_R) AS TOT_OPN_REV, SUM(TOT_OPN_RJ) AS TOT_OPN_REJ, SUM(TOT_OPN_CL) AS TOT_OPN_CLS,
									SUM(TOT_MR) AS TOT_MR, SUM(TOT_MR_N) AS TOT_MR_NEW, SUM(TOT_MR_C) AS TOT_MR_CON, SUM(TOT_MR_A) AS TOT_MR_APP,
										SUM(TOT_MR_R) AS TOT_MR_REV, SUM(TOT_MR_RJ) AS TOT_MR_REJ, SUM(TOT_MR_CL) AS TOT_MR_CLS,
									SUM(TOT_SN) AS TOT_SN, SUM(TOT_SN_N) AS TOT_SN_NEW, SUM(TOT_SN_C) AS TOT_SN_CON, SUM(TOT_SN_A) AS TOT_SN_APP,
										SUM(TOT_SN_R) AS TOT_SN_REV, SUM(TOT_SN_RJ) AS TOT_SN_REJ, SUM(TOT_SN_CL) AS TOT_SN_CLS,
									SUM(TOT_SINV) AS TOT_SINV, SUM(TOT_SINV_N) AS TOT_SINV_NEW, SUM(TOT_SINV_C) AS TOT_SINV_CON, SUM(TOT_SINV_A) AS TOT_SINV_APP,
										SUM(TOT_SINV_R) AS TOT_SINV_REV, SUM(TOT_SINV_RJ) AS TOT_SINV_REJ, SUM(TOT_SINV_CL) AS TOT_SINV_CLS,
									SUM(TOT_MC) AS TOT_MC, SUM(TOT_MC_N) AS TOT_MC_NEW, SUM(TOT_MC_C) AS TOT_MC_CON, SUM(TOT_MC_A) AS TOT_MC_APP,
										SUM(TOT_MC_R) AS TOT_MC_REV, SUM(TOT_MC_RJ) AS TOT_MC_REJ, SUM(TOT_MC_CL) AS TOT_MC_CLS,
									SUM(TOT_BR) AS TOT_BR, SUM(TOT_BR_N) AS TOT_BR_NEW, SUM(TOT_BR_C) AS TOT_BR_CON, SUM(TOT_BR_A) AS TOT_BR_APP,
										SUM(TOT_BR_R) AS TOT_BR_REV, SUM(TOT_BR_RJ) AS TOT_BR_REJ, SUM(TOT_BR_CL) AS TOT_BR_CLS
								-- FROM tbl_dash_transac WHERE PRJ_CODE IN ($myPRJCODE)
								FROM tbl_dash_transac WHERE PRJ_CODE IN (SELECT PRJCODE FROM tbl_project WHERE PRJCODE_HO  IN ($myPRJCODE))";
				$resAllData   = $this->db->query($sqlAllData)->result();
				foreach($resAllData as $rowAllD) :
					// PR
						$TOT_REQ    	= $rowAllD->TOT_REQ;
						$TOT_REQ_NEW	= $rowAllD->TOT_REQ_NEW;
						$TOT_REQ_CON  	= $rowAllD->TOT_REQ_CON;
						$TOT_REQ_APP  	= $rowAllD->TOT_REQ_APP;
						$TOT_REQ_REV  	= $rowAllD->TOT_REQ_REV;
						$TOT_REQ_REJ  	= $rowAllD->TOT_REQ_REJ;
						$TOT_REQ_CLS  	= $rowAllD->TOT_REQ_CLS;  // CLOSE
					// PO
						$TOT_PO     	= $rowAllD->TOT_PO;
						$TOT_PO_NEW  	= $rowAllD->TOT_PO_NEW;
						$TOT_PO_CON   	= $rowAllD->TOT_PO_CON;
						$TOT_PO_APP   	= $rowAllD->TOT_PO_APP;
						$TOT_PO_REV   	= $rowAllD->TOT_PO_REV;
						$TOT_PO_REJ   	= $rowAllD->TOT_PO_REJ;
						$TOT_PO_CLS  	= $rowAllD->TOT_PO_CLS;
					// IR
						$TOT_IR     	= $rowAllD->TOT_IR;
						$TOT_IR_NEW   	= $rowAllD->TOT_IR_NEW;
						$TOT_IR_CON   	= $rowAllD->TOT_IR_CON;
						$TOT_IR_APP   	= $rowAllD->TOT_IR_APP;
						$TOT_IR_REV   	= $rowAllD->TOT_IR_REV;
						$TOT_IR_REJ   	= $rowAllD->TOT_IR_REJ;
						$TOT_IR_CLS   	= $rowAllD->TOT_IR_CLS;
					// PINV
						$TOT_PINV   	= $rowAllD->TOT_PINV;
						$TOT_PINV_NEW 	= $rowAllD->TOT_PINV_NEW;
						$TOT_PINV_CON 	= $rowAllD->TOT_PINV_CON;
						$TOT_PINV_APP 	= $rowAllD->TOT_PINV_APP;
						$TOT_PINV_HP  	= $rowAllD->TOT_PINV_HP;
						$TOT_PINV_FP  	= $rowAllD->TOT_PINV_FP;
						$TOT_PINV_REJ 	= $rowAllD->TOT_PINV_REJ;
						$TOT_INV    	= $rowAllD->TOT_INV;
						$TOT_INV_FP   	= $rowAllD->TOT_INV_FP;
						$TOT_INV_NP   	= $rowAllD->TOT_INV_NP;
					// CB
						$TOT_CB     	= $rowAllD->TOT_CB;
						$TOT_CB_NEW   	= $rowAllD->TOT_CB_NEW;
						$TOT_CB_CON   	= $rowAllD->TOT_CB_CON;
						$TOT_CB_APP   	= $rowAllD->TOT_CB_APP;
						$TOT_CB_REV   	= $rowAllD->TOT_CB_REV;
						$TOT_CB_REJ   	= $rowAllD->TOT_CB_REJ;
						$TOT_CB_CLS   	= $rowAllD->TOT_CB_CLS;
					// OFF LETTER
						$TOT_OFF     	= $rowAllD->TOT_OFF;
						$TOT_OFF_NEW   	= $rowAllD->TOT_OFF_NEW;
						$TOT_OFF_CON   	= $rowAllD->TOT_OFF_CON;
						$TOT_OFF_APP   	= $rowAllD->TOT_OFF_APP;
						$TOT_OFF_REV   	= $rowAllD->TOT_OFF_REV;
						$TOT_OFF_REJ   	= $rowAllD->TOT_OFF_REJ;
						$TOT_OFF_CLS   	= $rowAllD->TOT_OFF_CLS;
					// SO
						$TOT_SO     	= $rowAllD->TOT_SO;
						$TOT_SO_NEW   	= $rowAllD->TOT_SO_NEW;
						$TOT_SO_CON   	= $rowAllD->TOT_SO_CON;
						$TOT_SO_APP   	= $rowAllD->TOT_SO_APP;
						$TOT_SO_REV   	= $rowAllD->TOT_SO_REV;
						$TOT_SO_REJ   	= $rowAllD->TOT_SO_REJ;
						$TOT_SO_CLS   	= $rowAllD->TOT_SO_CLS;
					// JO
						$TOT_JO     	= $rowAllD->TOT_JO;
						$TOT_JO_NEW   	= $rowAllD->TOT_JO_NEW;
						$TOT_JO_CON   	= $rowAllD->TOT_JO_CON;
						$TOT_JO_APP   	= $rowAllD->TOT_JO_APP;
						$TOT_JO_REV   	= $rowAllD->TOT_JO_REV;
						$TOT_JO_REJ   	= $rowAllD->TOT_JO_REJ;
						$TOT_JO_CLS   	= $rowAllD->TOT_JO_CLS;
					// MR
						$TOT_MR     	= $rowAllD->TOT_MR;
						$TOT_MR_NEW   	= $rowAllD->TOT_MR_NEW;
						$TOT_MR_CON   	= $rowAllD->TOT_MR_CON;
						$TOT_MR_APP   	= $rowAllD->TOT_MR_APP;
						$TOT_MR_REV   	= $rowAllD->TOT_MR_REV;
						$TOT_MR_REJ   	= $rowAllD->TOT_MR_REJ;
						$TOT_MR_CLS   	= $rowAllD->TOT_MR_CLS;
					// WO
						$TOT_WO     	= $rowAllD->TOT_WO;
						$TOT_WO_NEW   	= $rowAllD->TOT_WO_NEW;
						$TOT_WO_CON   	= $rowAllD->TOT_WO_CON;
						$TOT_WO_APP   	= $rowAllD->TOT_WO_APP;
						$TOT_WO_REV   	= $rowAllD->TOT_WO_REV;
						$TOT_WO_REJ   	= $rowAllD->TOT_WO_REJ;
						$TOT_WO_CLS   	= $rowAllD->TOT_WO_CLS;
					// OPN
						$TOT_OPN     	= $rowAllD->TOT_OPN;
						$TOT_OPN_NEW   	= $rowAllD->TOT_OPN_NEW;
						$TOT_OPN_CON   	= $rowAllD->TOT_OPN_CON;
						$TOT_OPN_APP   	= $rowAllD->TOT_OPN_APP;
						$TOT_OPN_REV   	= $rowAllD->TOT_OPN_REV;
						$TOT_OPN_REJ   	= $rowAllD->TOT_OPN_REJ;
						$TOT_OPN_CLS   	= $rowAllD->TOT_OPN_CLS;
					// SN
						$TOT_SN     	= $rowAllD->TOT_SN;
						$TOT_SN_NEW   	= $rowAllD->TOT_SN_NEW;
						$TOT_SN_CON   	= $rowAllD->TOT_SN_CON;
						$TOT_SN_APP   	= $rowAllD->TOT_SN_APP;
						$TOT_SN_REV   	= $rowAllD->TOT_SN_REV;
						$TOT_SN_REJ   	= $rowAllD->TOT_SN_REJ;
						$TOT_SN_CLS   	= $rowAllD->TOT_SN_CLS;
					// SINV
						$TOT_SINV     	= $rowAllD->TOT_SINV;
						$TOT_SINV_NEW   = $rowAllD->TOT_SINV_NEW;
						$TOT_SINV_CON   = $rowAllD->TOT_SINV_CON;
						$TOT_SINV_APP   = $rowAllD->TOT_SINV_APP;
						$TOT_SINV_REV   = $rowAllD->TOT_SINV_REV;
						$TOT_SINV_REJ   = $rowAllD->TOT_SINV_REJ;
						$TOT_SINV_CLS   = $rowAllD->TOT_SINV_CLS;
					// MC
						$TOT_MC     	= $rowAllD->TOT_MC;
						$TOT_MC_NEW   	= $rowAllD->TOT_MC_NEW;
						$TOT_MC_CON   	= $rowAllD->TOT_MC_CON;
						$TOT_MC_APP   	= $rowAllD->TOT_MC_APP;
						$TOT_MC_REV   	= $rowAllD->TOT_MC_REV;
						$TOT_MC_REJ   	= $rowAllD->TOT_MC_REJ;
						$TOT_MC_CLS   	= $rowAllD->TOT_MC_CLS;
					// BR
						$TOT_BR     	= $rowAllD->TOT_BR;
						$TOT_BR_NEW   	= $rowAllD->TOT_BR_NEW;
						$TOT_BR_CON   	= $rowAllD->TOT_BR_CON;
						$TOT_BR_APP   	= $rowAllD->TOT_BR_APP;
						$TOT_BR_REV   	= $rowAllD->TOT_BR_REV;
						$TOT_BR_REJ   	= $rowAllD->TOT_BR_REJ;
						$TOT_BR_CLS   	= $rowAllD->TOT_BR_CLS;
				endforeach;
      		}
      		else
			{
				$sqlAllData   	= "SELECT
									SUM(TOT_REQ) AS TOT_REQ, SUM(TOT_REQ_N) AS TOT_REQ_NEW, SUM(TOT_REQ_C) AS TOT_REQ_CON,
										SUM(TOT_REQ_A) AS TOT_REQ_APP, SUM(TOT_REQ_R) AS TOT_REQ_REV, SUM(TOT_REQ_RJ) AS TOT_REQ_REJ,
										SUM(TOT_REQ_CL) AS TOT_REQ_CLS,
									SUM(TOT_PO) AS TOT_PO, SUM(TOT_PO_N) AS TOT_PO_NEW, SUM(TOT_PO_C) AS TOT_PO_CON, SUM(TOT_PO_A) AS TOT_PO_APP,
										SUM(TOT_PO_R) AS TOT_PO_REV, SUM(TOT_PO_RJ) AS TOT_PO_REJ, SUM(TOT_PO_CL) AS TOT_PO_CLS,
									SUM(TOT_IR) AS TOT_IR, SUM(TOT_IR_N) AS TOT_IR_NEW, SUM(TOT_IR_C) AS TOT_IR_CON, SUM(TOT_IR_A) AS TOT_IR_APP,
										SUM(TOT_IR_R) AS TOT_IR_REV, SUM(TOT_IR_RJ) AS TOT_IR_REJ, SUM(TOT_IR_CL) AS TOT_IR_CLS,
									SUM(TOT_PINV) AS TOT_PINV, SUM(TOT_PINV_N) AS TOT_PINV_NEW, SUM(TOT_PINV_C) AS TOT_PINV_CON, SUM(TOT_PINV_A) AS TOT_PINV_APP,
										SUM(TOT_PINV_HP) AS TOT_PINV_HP, SUM(TOT_PINV_FP) AS TOT_PINV_FP, SUM(TOT_PINV_R) AS TOT_PINV_REJ,
									SUM(TOT_INV) AS TOT_INV, SUM(TOT_INV_FP) AS TOT_INV_FP, SUM(TOT_INV_NP) AS TOT_INV_NP,
									SUM(TOT_CB) AS TOT_CB, SUM(TOT_CB_N) AS TOT_CB_NEW, SUM(TOT_CB_C) AS TOT_CB_CON, SUM(TOT_CB_A) AS TOT_CB_APP,
										SUM(TOT_CB_R) AS TOT_CB_REV, SUM(TOT_CB_RJ) AS TOT_CB_REJ, SUM(TOT_CB_CL) AS TOT_CB_CLS,
									SUM(TOT_OFF) AS TOT_OFF, SUM(TOT_OFF_N) AS TOT_OFF_NEW, SUM(TOT_OFF_C) AS TOT_OFF_CON, SUM(TOT_OFF_A) AS TOT_OFF_APP,
										SUM(TOT_OFF_R) AS TOT_OFF_REV, SUM(TOT_OFF_RJ) AS TOT_OFF_REJ, SUM(TOT_OFF_CL) AS TOT_OFF_CLS,
									SUM(TOT_SO) AS TOT_SO, SUM(TOT_SO_N) AS TOT_SO_NEW, SUM(TOT_SO_C) AS TOT_SO_CON, SUM(TOT_SO_A) AS TOT_SO_APP,
										SUM(TOT_SO_R) AS TOT_SO_REV, SUM(TOT_SO_RJ) AS TOT_SO_REJ, SUM(TOT_SO_CL) AS TOT_SO_CLS,
									SUM(TOT_JO) AS TOT_JO, SUM(TOT_JO_N) AS TOT_JO_NEW, SUM(TOT_JO_C) AS TOT_JO_CON, SUM(TOT_JO_A) AS TOT_JO_APP,
										SUM(TOT_JO_R) AS TOT_JO_REV, SUM(TOT_JO_RJ) AS TOT_JO_REJ, SUM(TOT_JO_CL) AS TOT_JO_CLS,
									SUM(TOT_WO) AS TOT_WO, SUM(TOT_WO_N) AS TOT_WO_NEW, SUM(TOT_WO_C) AS TOT_WO_CON, SUM(TOT_WO_A) AS TOT_WO_APP,
										SUM(TOT_WO_R) AS TOT_WO_REV, SUM(TOT_WO_RJ) AS TOT_WO_REJ, SUM(TOT_WO_CL) AS TOT_WO_CLS,
									SUM(TOT_OPN) AS TOT_OPN, SUM(TOT_OPN_N) AS TOT_OPN_NEW, SUM(TOT_OPN_C) AS TOT_OPN_CON, SUM(TOT_OPN_A) AS TOT_OPN_APP,
										SUM(TOT_OPN_R) AS TOT_OPN_REV, SUM(TOT_OPN_RJ) AS TOT_OPN_REJ, SUM(TOT_OPN_CL) AS TOT_OPN_CLS,
									SUM(TOT_MR) AS TOT_MR, SUM(TOT_MR_N) AS TOT_MR_NEW, SUM(TOT_MR_C) AS TOT_MR_CON, SUM(TOT_MR_A) AS TOT_MR_APP,
										SUM(TOT_MR_R) AS TOT_MR_REV, SUM(TOT_MR_RJ) AS TOT_MR_REJ, SUM(TOT_MR_CL) AS TOT_MR_CLS,
									SUM(TOT_SN) AS TOT_SN, SUM(TOT_SN_N) AS TOT_SN_NEW, SUM(TOT_SN_C) AS TOT_SN_CON, SUM(TOT_SN_A) AS TOT_SN_APP,
										SUM(TOT_SN_R) AS TOT_SN_REV, SUM(TOT_SN_RJ) AS TOT_SN_REJ, SUM(TOT_SN_CL) AS TOT_SN_CLS,
									SUM(TOT_SINV) AS TOT_SINV, SUM(TOT_SINV_N) AS TOT_SINV_NEW, SUM(TOT_SINV_C) AS TOT_SINV_CON, SUM(TOT_SINV_A) AS TOT_SINV_APP,
										SUM(TOT_SINV_R) AS TOT_SINV_REV, SUM(TOT_SINV_RJ) AS TOT_SINV_REJ, SUM(TOT_SINV_CL) AS TOT_SINV_CLS,
									SUM(TOT_MC) AS TOT_MC, SUM(TOT_MC_N) AS TOT_MC_NEW, SUM(TOT_MC_C) AS TOT_MC_CON, SUM(TOT_MC_A) AS TOT_MC_APP,
										SUM(TOT_MC_R) AS TOT_MC_REV, SUM(TOT_MC_RJ) AS TOT_MC_REJ, SUM(TOT_MC_CL) AS TOT_MC_CLS,
									SUM(TOT_BR) AS TOT_BR, SUM(TOT_BR_N) AS TOT_BR_NEW, SUM(TOT_BR_C) AS TOT_BR_CON, SUM(TOT_BR_A) AS TOT_BR_APP,
										SUM(TOT_BR_R) AS TOT_BR_REV, SUM(TOT_BR_RJ) AS TOT_BR_REJ, SUM(TOT_BR_CL) AS TOT_BR_CLS
								-- FROM tbl_dash_transac WHERE PRJ_CODE IN ($myPRJCODE)
								FROM tbl_dash_transac WHERE PRJ_CODE = $myPRJCODE";
				$resAllData   	= $this->db->query($sqlAllData)->result();
				foreach($resAllData as $rowAllD) :
					// PR
						$TOT_REQ    	= $rowAllD->TOT_REQ;
						$TOT_REQ_NEW	= $rowAllD->TOT_REQ_NEW;
						$TOT_REQ_CON  	= $rowAllD->TOT_REQ_CON;
						$TOT_REQ_APP  	= $rowAllD->TOT_REQ_APP;
						$TOT_REQ_REV  	= $rowAllD->TOT_REQ_REV;
						$TOT_REQ_REJ  	= $rowAllD->TOT_REQ_REJ;
						$TOT_REQ_CLS  	= $rowAllD->TOT_REQ_CLS;  // CLOSE
					// PO
						$TOT_PO     	= $rowAllD->TOT_PO;
						$TOT_PO_NEW  	= $rowAllD->TOT_PO_NEW;
						$TOT_PO_CON   	= $rowAllD->TOT_PO_CON;
						$TOT_PO_APP   	= $rowAllD->TOT_PO_APP;
						$TOT_PO_REV   	= $rowAllD->TOT_PO_REV;
						$TOT_PO_REJ   	= $rowAllD->TOT_PO_REJ;
						$TOT_PO_CLS  	= $rowAllD->TOT_PO_CLS;
					// IR
						$TOT_IR     	= $rowAllD->TOT_IR;
						$TOT_IR_NEW   	= $rowAllD->TOT_IR_NEW;
						$TOT_IR_CON   	= $rowAllD->TOT_IR_CON;
						$TOT_IR_APP   	= $rowAllD->TOT_IR_APP;
						$TOT_IR_REV   	= $rowAllD->TOT_IR_REV;
						$TOT_IR_REJ   	= $rowAllD->TOT_IR_REJ;
						$TOT_IR_CLS   	= $rowAllD->TOT_IR_CLS;
					// PINV
						$TOT_PINV   	= $rowAllD->TOT_PINV;
						$TOT_PINV_NEW 	= $rowAllD->TOT_PINV_NEW;
						$TOT_PINV_CON 	= $rowAllD->TOT_PINV_CON;
						$TOT_PINV_APP 	= $rowAllD->TOT_PINV_APP;
						$TOT_PINV_HP  	= $rowAllD->TOT_PINV_HP;
						$TOT_PINV_FP  	= $rowAllD->TOT_PINV_FP;
						$TOT_PINV_REJ 	= $rowAllD->TOT_PINV_REJ;
						$TOT_INV    	= $rowAllD->TOT_INV;
						$TOT_INV_FP   	= $rowAllD->TOT_INV_FP;
						$TOT_INV_NP   	= $rowAllD->TOT_INV_NP;
					// CB
						$TOT_CB     	= $rowAllD->TOT_CB;
						$TOT_CB_NEW   	= $rowAllD->TOT_CB_NEW;
						$TOT_CB_CON   	= $rowAllD->TOT_CB_CON;
						$TOT_CB_APP   	= $rowAllD->TOT_CB_APP;
						$TOT_CB_REV   	= $rowAllD->TOT_CB_REV;
						$TOT_CB_REJ   	= $rowAllD->TOT_CB_REJ;
						$TOT_CB_CLS   	= $rowAllD->TOT_CB_CLS;
					// OFF LETTER
						$TOT_OFF     	= $rowAllD->TOT_OFF;
						$TOT_OFF_NEW   	= $rowAllD->TOT_OFF_NEW;
						$TOT_OFF_CON   	= $rowAllD->TOT_OFF_CON;
						$TOT_OFF_APP   	= $rowAllD->TOT_OFF_APP;
						$TOT_OFF_REV   	= $rowAllD->TOT_OFF_REV;
						$TOT_OFF_REJ   	= $rowAllD->TOT_OFF_REJ;
						$TOT_OFF_CLS   	= $rowAllD->TOT_OFF_CLS;
					// SO
						$TOT_SO     	= $rowAllD->TOT_SO;
						$TOT_SO_NEW   	= $rowAllD->TOT_SO_NEW;
						$TOT_SO_CON   	= $rowAllD->TOT_SO_CON;
						$TOT_SO_APP   	= $rowAllD->TOT_SO_APP;
						$TOT_SO_REV   	= $rowAllD->TOT_SO_REV;
						$TOT_SO_REJ   	= $rowAllD->TOT_SO_REJ;
						$TOT_SO_CLS   	= $rowAllD->TOT_SO_CLS;
					// JO
						$TOT_JO     	= $rowAllD->TOT_JO;
						$TOT_JO_NEW   	= $rowAllD->TOT_JO_NEW;
						$TOT_JO_CON   	= $rowAllD->TOT_JO_CON;
						$TOT_JO_APP   	= $rowAllD->TOT_JO_APP;
						$TOT_JO_REV   	= $rowAllD->TOT_JO_REV;
						$TOT_JO_REJ   	= $rowAllD->TOT_JO_REJ;
						$TOT_JO_CLS   	= $rowAllD->TOT_JO_CLS;
					// MR
						$TOT_MR     	= $rowAllD->TOT_MR;
						$TOT_MR_NEW   	= $rowAllD->TOT_MR_NEW;
						$TOT_MR_CON   	= $rowAllD->TOT_MR_CON;
						$TOT_MR_APP   	= $rowAllD->TOT_MR_APP;
						$TOT_MR_REV   	= $rowAllD->TOT_MR_REV;
						$TOT_MR_REJ   	= $rowAllD->TOT_MR_REJ;
						$TOT_MR_CLS   	= $rowAllD->TOT_MR_CLS;
					// WO
						$TOT_WO     	= $rowAllD->TOT_WO;
						$TOT_WO_NEW   	= $rowAllD->TOT_WO_NEW;
						$TOT_WO_CON   	= $rowAllD->TOT_WO_CON;
						$TOT_WO_APP   	= $rowAllD->TOT_WO_APP;
						$TOT_WO_REV   	= $rowAllD->TOT_WO_REV;
						$TOT_WO_REJ   	= $rowAllD->TOT_WO_REJ;
						$TOT_WO_CLS   	= $rowAllD->TOT_WO_CLS;
					// OPN
						$TOT_OPN     	= $rowAllD->TOT_OPN;
						$TOT_OPN_NEW   	= $rowAllD->TOT_OPN_NEW;
						$TOT_OPN_CON   	= $rowAllD->TOT_OPN_CON;
						$TOT_OPN_APP   	= $rowAllD->TOT_OPN_APP;
						$TOT_OPN_REV   	= $rowAllD->TOT_OPN_REV;
						$TOT_OPN_REJ   	= $rowAllD->TOT_OPN_REJ;
						$TOT_OPN_CLS   	= $rowAllD->TOT_OPN_CLS;
					// SN
						$TOT_SN     	= $rowAllD->TOT_SN;
						$TOT_SN_NEW   	= $rowAllD->TOT_SN_NEW;
						$TOT_SN_CON   	= $rowAllD->TOT_SN_CON;
						$TOT_SN_APP   	= $rowAllD->TOT_SN_APP;
						$TOT_SN_REV   	= $rowAllD->TOT_SN_REV;
						$TOT_SN_REJ   	= $rowAllD->TOT_SN_REJ;
						$TOT_SN_CLS   	= $rowAllD->TOT_SN_CLS;
					// SINV
						$TOT_SINV     	= $rowAllD->TOT_SINV;
						$TOT_SINV_NEW   = $rowAllD->TOT_SINV_NEW;
						$TOT_SINV_CON   = $rowAllD->TOT_SINV_CON;
						$TOT_SINV_APP   = $rowAllD->TOT_SINV_APP;
						$TOT_SINV_REV   = $rowAllD->TOT_SINV_REV;
						$TOT_SINV_REJ   = $rowAllD->TOT_SINV_REJ;
						$TOT_SINV_CLS   = $rowAllD->TOT_SINV_CLS;
					// MC
						$TOT_MC     	= $rowAllD->TOT_MC;
						$TOT_MC_NEW   	= $rowAllD->TOT_MC_NEW;
						$TOT_MC_CON   	= $rowAllD->TOT_MC_CON;
						$TOT_MC_APP   	= $rowAllD->TOT_MC_APP;
						$TOT_MC_REV   	= $rowAllD->TOT_MC_REV;
						$TOT_MC_REJ   	= $rowAllD->TOT_MC_REJ;
						$TOT_MC_CLS   	= $rowAllD->TOT_MC_CLS;
					// BR
						$TOT_BR     	= $rowAllD->TOT_BR;
						$TOT_BR_NEW   	= $rowAllD->TOT_BR_NEW;
						$TOT_BR_CON   	= $rowAllD->TOT_BR_CON;
						$TOT_BR_APP   	= $rowAllD->TOT_BR_APP;
						$TOT_BR_REV   	= $rowAllD->TOT_BR_REV;
						$TOT_BR_REJ   	= $rowAllD->TOT_BR_REJ;
						$TOT_BR_CLS   	= $rowAllD->TOT_BR_CLS;
				endforeach;
			}

    	// GET HR DASH SETTING
			$EMP_TOT    	= 0;
			$EMP_ACT   	 	= 0;
			$EMP_NACT   	= 0;
			$EMP_NEW    	= 0;
			$GRP_EMP_TOT  	= 0;
			$GRP_EMP_GEND 	= 0;
			$GRP_EMP_AGE  	= 0;
			$GRP_EMP_POS  	= 0;
			
			$DHR_TOTEMP   	= 0;
			$DHR_TOTACT  	= 0;
			$DHR_TOTNACT  	= 0;
			$DHR_TOTM   	= 0;
			$DHR_TOTF   	= 0;
			$DHR_TOTNEW   	= 0;
			$DHR_TOT30    	= 0;
			$DHR_TOT40    	= 0;
			$DHR_TOT50    	= 0;
			$DHR_TOTBOD   	= 0;
			$DHR_TOTGM    	= 0;
			$DHR_TOTMNG   	= 0;
			$DHR_TOTKEPU  	= 0;
			$DHR_TOTPM    	= 0;
			$DHR_TOTKU    	= 0;
			$DHR_TOTSM    	= 0;
			$DHR_TOTSPEC  	= 0;
			$DHR_TOTSTF   	= 0;
			$DHR_TOTNSTF  	= 0;

		$sqlAllDBHR   = "SELECT * FROM tbl_dash_hr";
		$resAllDBHR   = $this->db->query($sqlAllDBHR)->result();
		foreach($resAllDBHR as $rowDBHR) :
			$DHR_TOTEMP   = $rowDBHR->DHR_TOTEMP;
			$DHR_TOTACT   = $rowDBHR->DHR_TOTACT;
			$DHR_TOTNACT  = $rowDBHR->DHR_TOTNACT;
			$DHR_TOTM     = $rowDBHR->DHR_TOTM;
			$DHR_TOTF     = $rowDBHR->DHR_TOTF;
			$DHR_TOTNEW   = $rowDBHR->DHR_TOTNEW;
			$DHR_TOT30    = $rowDBHR->DHR_TOT30;
			$DHR_TOT40    = $rowDBHR->DHR_TOT40;
			$DHR_TOT50    = $rowDBHR->DHR_TOT50;
			$DHR_TOTBOD   = $rowDBHR->DHR_TOTBOD;
			$DHR_TOTGM    = $rowDBHR->DHR_TOTGM;
			$DHR_TOTMNG   = $rowDBHR->DHR_TOTMNG;
			$DHR_TOTKEPU  = $rowDBHR->DHR_TOTKEPU;
			$DHR_TOTPM    = $rowDBHR->DHR_TOTPM;
			$DHR_TOTKU    = $rowDBHR->DHR_TOTKU;
			$DHR_TOTSM    = $rowDBHR->DHR_TOTSM;
			$DHR_TOTSPEC  = $rowDBHR->DHR_TOTSPEC;
			$DHR_TOTSTF   = $rowDBHR->DHR_TOTSTF;
			$DHR_TOTNSTF  = $rowDBHR->DHR_TOTNSTF;
      	endforeach;

      	$sqlDBHR  = "SELECT DS_TYPE AS DS_TYPEHR FROM tbl_dash_sett_hr_emp WHERE EMP_ID = '$Emp_ID'";
      	$resDBHR  = $this->db->query($sqlDBHR)->result();
      	foreach($resDBHR as $rowDBHR) :
			$DS_TYPEHR  = $rowDBHR->DS_TYPEHR;
			if($DS_TYPEHR == "EMP_TOT")
			{
			  $EMP_TOT  = 1;
			  if($DHR_TOTEMP == '')
				$DHR_TOTEMP = 0;
			}
			elseif($DS_TYPEHR == "EMP_ACT")
			{
			  $EMP_ACT  = 1;
			  if($DHR_TOTACT == '')
				$DHR_TOTACT = 0;
			}
			elseif($DS_TYPEHR == "EMP_NACT")
			{
			  $EMP_NACT = 1;
			  if($DHR_TOTNACT == '')
				$DHR_TOTNACT = 0;
			}
			elseif($DS_TYPEHR == "EMP_NEW")
			{
			  $EMP_NEW  = 1;
			  if($DHR_TOTNEW == '')
				$DHR_TOTNEW = 0;
			}
			elseif($DS_TYPEHR == "GRP_EMP_TOT")
			{
			  $GRP_EMP_TOT  = 1;
			  $TOT_GRP_EMP  = $DHR_TOTACT + $DHR_TOTNACT;
			}
			elseif($DS_TYPEHR == "GRP_EMP_GEND")
			{
			  $GRP_EMP_GEND = 1;
			  $TOT_GRP_EMP_GEN= $DHR_TOTM + $DHR_TOTM;
			}
			elseif($DS_TYPEHR == "GRP_EMP_AGE")
			{
			  $GRP_EMP_AGE  = 1;
			  $TOT_GRP_EMP_AGE= $DHR_TOT30 + $DHR_TOT40 + $DHR_TOT50;
			}
			elseif($DS_TYPEHR == "GRP_EMP_POS")
			{
			  $GRP_EMP_POS  = 1;
			  $TOT_GRP_EMP_POS= $DHR_TOTBOD + $DHR_TOTGM + $DHR_TOTMNG + $DHR_TOTKEPU + $DHR_TOTPM + $DHR_TOTKU + $DHR_TOTSM + $DHR_TOTSPEC + $DHR_TOTSTF + $DHR_TOTNSTF;
			}
      	endforeach;

    // GET EMP SETT DS_TYPE
      	$sqlDBoard  = "SELECT DS_TYPE FROM tbl_dash_sett_emp WHERE EMP_ID = '$Emp_ID'";
      	$resDBoard  = $this->db->query($sqlDBoard)->result();
      	foreach($resDBoard as $rowDB) :
	        $DS_TYPE  = $rowDB->DS_TYPE;
	        
	        if($DS_TYPE == "REQ_NEW")
	        {
	          	$REQ_NEW  = 1;
	          	if($TOT_REQ_NEW == '')
	            	$TOT_REQ_NEW = 0;
	        }
	        elseif($DS_TYPE == "REQ_CON")
	        {
	          	$REQ_CON  = 1;
	          	if($TOT_REQ_CON == '')
	            	$TOT_REQ_CON = 0;
	        }
	        elseif($DS_TYPE == "REQ_APP")
	        {
	          	$REQ_APP  = 1;
	          	if($TOT_REQ_APP == '')
	            	$TOT_REQ_APP = 0;
	        }
	        elseif($DS_TYPE == "REQ_CLS")
	        {
	          	$REQ_CLS  = 1;
	          	if($TOT_REQ_CLS == '')
	            	$TOT_REQ_CLS = 0;
	        }
	        elseif($DS_TYPE == "PO_NEW")
	        {
	          	$PO_NEW = 1;
	          	if($TOT_PO_NEW == '')
	            	$TOT_PO_NEW = 0;
	        }
	        elseif($DS_TYPE == "PO_CON")
	        {
	          	$PO_CON = 1;
	          	if($TOT_PO_CON == '')
	            	$TOT_PO_CON = 0;
	        }
	        elseif($DS_TYPE == "PO_APP")
	        {
	         	$PO_APP = 1;
	          	if($TOT_PO_APP == '')
	            	$TOT_PO_APP = 0;
	        }
	        elseif($DS_TYPE == "PO_CLS")
	        {
	          	$PO_CLS = 1;
	          	if($TOT_PO_CLS == '')
	            	$TOT_PO_CLS = 0;
	        }
	        elseif($DS_TYPE == "IR_NEW")
	        {
	          	$IR_NEW = 1;
	          	if($TOT_IR_NEW == '')
	            	$TOT_IR_NEW = 0;
	        }
	        elseif($DS_TYPE == "IR_CON")
	        {
	          	$IR_CON = 1;
	          	if($TOT_IR_CON == '')
	            	$TOT_IR_CON = 0;
	        }
	        elseif($DS_TYPE == "IR_APP")
	        {
	         	$IR_APP = 1;
	          	if($TOT_IR_APP == '')
	            	$TOT_IR_APP = 0;
	        }
	        elseif($DS_TYPE == "IR_CLS")
	        {
	          	$IR_CLS = 1;
	          	if($TOT_IR_CLS == '')
	            	$TOT_IR_CLS = 0;
	        }
	        elseif($DS_TYPE == "PINV_NEW")
	        {
	          	$PINV_NEW = 1;
	          	if($TOT_PINV_NEW == '')
	            	$TOT_PINV_NEW = 0;
	        }
	        elseif($DS_TYPE == "PINV_CON")
	        {
	          	$PINV_CON = 1;
	          	if($TOT_PINV_CON == '')
	            	$TOT_PINV_CON = 0;
	        }
	        elseif($DS_TYPE == "PINV_APP")
	        {
	          	$PINV_APP = 1;
	          	if($TOT_PINV_APP == '')
	            	$TOT_PINV_APP = 0;
	        }
	        elseif($DS_TYPE == "PINV_HP")
	        {
	          	$PINV_HP  = 1;
	          	if($TOT_PINV_HP == '')
	            	$TOT_PINV_HP = 0;
	        }
	        elseif($DS_TYPE == "PINV_FP")
	        {
	          	$PINV_FP  = 1;
	          	if($TOT_PINV_FP == '')
	            	$TOT_PINV_FP = 0;
	        }
	        elseif($DS_TYPE == "PINV_REJ")
	        {
	          	$PINV_REJ = 1;
	          	if($TOT_PINV_REJ == '')
	            	$TOT_PINV_REJ = 0;
	        }
	        elseif($DS_TYPE == "CB_NEW")
	        {
	          	$CB_NEW = 1;
	          	if($TOT_CB_NEW == '')
	            	$TOT_CB_NEW = 0;
	        }
	        elseif($DS_TYPE == "CB_CON")
	        {
	          	$CB_CON = 1;
	          	if($TOT_CB_CON == '')
	            	$TOT_CB_CON = 0;
	        }
	        elseif($DS_TYPE == "CB_APP")
	        {
	         	$CB_APP = 1;
	          	if($TOT_CB_APP == '')
	            	$TOT_CB_APP = 0;
	        }
	        elseif($DS_TYPE == "CB_CLS")
	        {
	          	$CB_CLS = 1;
	          	if($TOT_CB_CLS == '')
	            	$TOT_CB_CLS = 0;
	        }
	        elseif($DS_TYPE == "OFF_NEW")
	        {
	          	$OFF_NEW = 1;
	          	if($TOT_OFF_NEW == '')
	            	$TOT_OFF_NEW = 0;
	        }
	        elseif($DS_TYPE == "OFF_CON")
	        {
	          	$OFF_CON = 1;
	          	if($TOT_OFF_CON == '')
	            	$TOT_OFF_CON = 0;
	        }
	        elseif($DS_TYPE == "OFF_APP")
	        {
	         	$OFF_APP = 1;
	          	if($TOT_OFF_APP == '')
	            	$TOT_OFF_APP = 0;
	        }
	        elseif($DS_TYPE == "OFF_CLS")
	        {
	          	$OFF_CLS = 1;
	          	if($TOT_OFF_CLS == '')
	            	$TOT_OFF_CLS = 0;
	        }
	        elseif($DS_TYPE == "SO_NEW")
	        {
	          	$SO_NEW = 1;
	          	if($TOT_SO_NEW == '')
	            	$TOT_SO_NEW = 0;
	        }
	        elseif($DS_TYPE == "SO_CON")
	        {
	          	$SO_CON = 1;
	          	if($TOT_SO_CON == '')
	            	$TOT_SO_CON = 0;
	        }
	        elseif($DS_TYPE == "SO_APP")
	        {
	         	$SO_APP = 1;
	          	if($TOT_SO_APP == '')
	            	$TOT_SO_APP = 0;
	        }
	        elseif($DS_TYPE == "SO_CLS")
	        {
	          	$SO_CLS = 1;
	          	if($TOT_SO_CLS == '')
	            	$TOT_SO_CLS = 0;
	        }
	        elseif($DS_TYPE == "JO_NEW")
	        {
	          	$JO_NEW = 1;
	          	if($TOT_JO_NEW == '')
	            	$TOT_JO_NEW = 0;
	        }
	        elseif($DS_TYPE == "JO_CON")
	        {
	          	$JO_CON = 1;
	          	if($TOT_JO_CON == '')
	            	$TOT_JO_CON = 0;
	        }
	        elseif($DS_TYPE == "JO_APP")
	        {
	         	$JO_APP = 1;
	          	if($TOT_JO_APP == '')
	            	$TOT_JO_APP = 0;
	        }
	        elseif($DS_TYPE == "JO_CLS")
	        {
	          	$JO_CLS = 1;
	          	if($TOT_JO_CLS == '')
	            	$TOT_JO_CLS = 0;
	        }
	        elseif($DS_TYPE == "MR_NEW")
	        {
	          	$MR_NEW = 1;
	          	if($TOT_MR_NEW == '')
	            	$TOT_MR_NEW = 0;
	        }
	        elseif($DS_TYPE == "MR_CON")
	        {
	          	$MR_CON = 1;
	          	if($TOT_MR_CON == '')
	            	$TOT_MR_CON = 0;
	        }
	        elseif($DS_TYPE == "MR_APP")
	        {
	         	$MR_APP = 1;
	          	if($TOT_MR_APP == '')
	            	$TOT_MR_APP = 0;
	        }
	        elseif($DS_TYPE == "MR_CLS")
	        {
	          	$MR_CLS = 1;
	          	if($TOT_MR_CLS == '')
	            	$TOT_MR_CLS = 0;
	        }
	        elseif($DS_TYPE == "SN_NEW")
	        {
	          	$SN_NEW = 1;
	          	if($TOT_SN_NEW == '')
	            	$TOT_SN_NEW = 0;
	        }
	        elseif($DS_TYPE == "SN_CON")
	        {
	          	$SN_CON = 1;
	          	if($TOT_SN_CON == '')
	            	$TOT_SN_CON = 0;
	        }
	        elseif($DS_TYPE == "SN_APP")
	        {
	         	$SN_APP = 1;
	          	if($TOT_SN_APP == '')
	            	$TOT_SN_APP = 0;
	        }
	        elseif($DS_TYPE == "SN_CLS")
	        {
	          	$SN_CLS = 1;
	          	if($TOT_SN_CLS == '')
	            	$TOT_SN_CLS = 0;
	        }
	        elseif($DS_TYPE == "SINV_NEW")
	        {
	          	$SINV_NEW = 1;
	          	if($TOT_SINV_NEW == '')
	            	$TOT_SINV_NEW = 0;
	        }
	        elseif($DS_TYPE == "SINV_CON")
	        {
	          	$SINV_CON = 1;
	          	if($TOT_SINV_CON == '')
	            	$TOT_SINV_CON = 0;
	        }
	        elseif($DS_TYPE == "SINV_APP")
	        {
	         	$SINV_APP = 1;
	          	if($TOT_SINV_APP == '')
	            	$TOT_SINV_APP = 0;
	        }
	        elseif($DS_TYPE == "SINV_CLS")
	        {
	          	$SINV_CLS = 1;
	          	if($TOT_SINV_CLS == '')
	            	$TOT_SINV_CLS = 0;
	        }
	        elseif($DS_TYPE == "GRF_LOGHIST")
	        {
				$sqlLogH  = "SELECT LCONC_NEVER, LCONC_SOMET, LCONC_OFTEN, LCONC_FANTASTIC FROM tbl_login_concl";
				$resLogH  = $this->db->query($sqlLogH)->result();
				foreach($resLogH as $rowLH) :
					$LCONC_NEVER  = $rowLH->LCONC_NEVER;
					$LCONC_SOMET  = $rowLH->LCONC_SOMET;
					$LCONC_OFTEN  = $rowLH->LCONC_OFTEN;
					$LCONC_FANT   = $rowLH->LCONC_FANTASTIC;
				endforeach;     
				$GRF_LOGHIST  = 1;
	        }
	        elseif($DS_TYPE == "GRF_SALES")
	        {
				$GRF_SALES	= 1;
	        }
	        elseif($DS_TYPE == "GRF_DPROD")
	        {
				$GRF_DPROD	= 1;
	        }
	        elseif($DS_TYPE == "GRF_PURCH")
	        {
	        	$GRF_PURCH    = 1;
	        }
	        elseif($DS_TYPE == "GRF_BP")
	        {
	        	$GRF_BP    	= 1;
	        }
	        elseif($DS_TYPE == "GRF_GOALC")
	        {
	        	$GRF_GOALC	= 1;
	        }
	        elseif($DS_TYPE == "EMP_TOT")
	        {
	        	$EMP_TOT	= 1;
	        }
	        elseif($DS_TYPE == "SSWIP_INFO")
	        {
	        	$SSWIP_INFO	= 1;
	        }
	        elseif($DS_TYPE == "BR_INFO")
	        {
	        	$BR_INFO	= 1;
	        }
	        elseif($DS_TYPE == "MC_PRJ")
	        {
	        	$MC_PRJ	= 1;
	        }
	        elseif($DS_TYPE == "MC_NEW")
	        {
	          	$MC_NEW = 1;
	          	if($TOT_MC_NEW == '')
	            	$TOT_MC_NEW = 0;
	        }
	        elseif($DS_TYPE == "MC_CON")
	        {
	          	$MC_CON = 1;
	          	if($TOT_MC_CON == '')
	            	$TOT_MC_CON = 0;
	        }
	        elseif($DS_TYPE == "MC_APP")
	        {
	         	$MC_APP = 1;
	          	if($TOT_MC_APP == '')
	            	$TOT_MC_APP = 0;
	        }
	        elseif($DS_TYPE == "MC_CLS")
	        {
	          	$MC_CLS = 1;
	          	if($TOT_MC_CLS == '')
	            	$TOT_MC_CLS = 0;
	        }
	        elseif($DS_TYPE == "BR_NEW")
	        {
	          	$BR_NEW = 1;
	          	if($TOT_BR_NEW == '')
	            	$TOT_BR_NEW = 0;
	        }
	        elseif($DS_TYPE == "BR_CON")
	        {
	          	$BR_CON = 1;
	          	if($TOT_BR_CON == '')
	            	$TOT_BR_CON = 0;
	        }
	        elseif($DS_TYPE == "BR_APP")
	        {
	         	$BR_APP = 1;
	          	if($TOT_BR_APP == '')
	            	$TOT_BR_APP = 0;
	        }
	        elseif($DS_TYPE == "BR_CLS")
	        {
	          	$BR_CLS = 1;
	          	if($TOT_BR_CLS == '')
	            	$TOT_BR_CLS = 0;
	        }
	        elseif($DS_TYPE == "WO_NEW")
	        {
	          	$WO_NEW = 1;
	          	if($TOT_WO_NEW == '')
	            	$TOT_WO_NEW = 0;
	        }
	        elseif($DS_TYPE == "WO_CON")
	        {
	          	$WO_CON = 1;
	          	if($TOT_WO_CON == '')
	            	$TOT_WO_CON = 0;
	        }
	        elseif($DS_TYPE == "WO_APP")
	        {
	         	$WO_APP = 1;
	          	if($TOT_WO_APP == '')
	            	$TOT_WO_APP = 0;
	        }
	        elseif($DS_TYPE == "WO_CLS")
	        {
	          	$WO_CLS = 1;
	          	if($TOT_WO_CLS == '')
	            	$TOT_WO_CLS = 0;
	        }
	        elseif($DS_TYPE == "OPN_NEW")
	        {
	          	$OPN_NEW = 1;
	          	if($TOT_OPN_NEW == '')
	            	$TOT_OPN_NEW = 0;
	        }
	        elseif($DS_TYPE == "OPN_CON")
	        {
	          	$OPN_CON = 1;
	          	if($TOT_OPN_CON == '')
	            	$TOT_OPN_CON = 0;
	        }
	        elseif($DS_TYPE == "OPN_APP")
	        {
	         	$OPN_APP = 1;
	          	if($TOT_OPN_APP == '')
	            	$TOT_OPN_APP = 0;
	        }
	        elseif($DS_TYPE == "OPN_CLS")
	        {
	          	$OPN_CLS = 1;
	          	if($TOT_OPN_CLS == '')
	            	$TOT_OPN_CLS = 0;
	        }
      	endforeach;

    // URL
	    $imgLoc		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
		$showAllOUT = site_url('__180c2f/prjlist/?id='.$this->url_encryption_helper->encode_url($appName));
		$showAboutC = site_url('__180c2f/aboutcomp/?id='.$this->url_encryption_helper->encode_url($appName));
		$logURL 	= site_url('__180c2f/crtLogV/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
		$GraphicTitleText	= 3;						
?>
<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <title>Dashboard</title>
	    <!-- Tell the browser to be responsive to screen width -->
	    <?php
		    $vers     	= $this->session->userdata['vers'];

	        $sqlcss 	= "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
	        $rescss 	= $this->db->query($sqlcss)->result();
	        foreach($rescss as $rowcss) :
	            $cssjs_lnk  = $rowcss->cssjs_lnk;
	            ?>
	                <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
	            <?php
	        endforeach;

	        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
	        $rescss = $this->db->query($sqlcss)->result();
	        foreach($rescss as $rowcss) :
	            $cssjs_lnk1  = $rowcss->cssjs_lnk;
	            ?>
	                <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
	            <?php
        	endforeach;
		?>

		<!-- Google Font -->

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>
	<style>
		#dailyProd {
		  	width: 100%;
		  	height: 400px;
		}

		#salesInfo {
		  	width: 100%;
		  	height: 400px;
		}
		
		.swal-overlay {
			  background-color: rgba(43, 165, 137, 0.45);
			}

		.preloader {
		  position: fixed;
		  top: 0;
		  left: 0;
		  width: 100%;
		  height: 100%;
		  z-index: 9999;
		  background-color: #fff;
		}
		.preloader .loading {
		  position: absolute;
		  left: 50%;
		  top: 50%;
		  transform: translate(-50%,-50%);
		  font: 14px arial;
		}
		canvas{
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
	</style>

	<?php
		// Top Bar
  			//______$this->load->view('template/topbar');

		// Left side column. contains the logo and sidebar
  			//______$this->load->view('template/sidebar');

		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];	
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl	= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$Translate	= $rowTransl->LangTransl;
			if($TranslCode == 'Dashboard')$Dashboard = $Translate;
			if($TranslCode == 'DailyNews')$DailyNews = $Translate;
			if($TranslCode == 'Supply')$Supply = $Translate;
			if($TranslCode == 'WIP')$WIP = $Translate;
			if($TranslCode == 'Waste')$Waste = $Translate;
			if($TranslCode == 'Sales')$Sales = $Translate;
			if($TranslCode == 'SalesOrder')$SalesOrder = $Translate;
			if($TranslCode == 'Production')$Production = $Translate;
			if($TranslCode == 'Shipment')$Shipment = $Translate;

			if($TranslCode == 'PurchaseRequest')$PurchaseRequest = $Translate;
			if($TranslCode == 'Purchase')$Purchase = $Translate;
			if($TranslCode == 'MtrReceipt')$MtrReceipt = $Translate;
			if($TranslCode == 'WorkOrder')$WorkOrder = $Translate;
			if($TranslCode == 'JobOpname')$JobOpname = $Translate;
			if($TranslCode == 'mtrUsed')$mtrUsed = $Translate;
			if($TranslCode == 'mcprj')$mcprj = $Translate;
			if($TranslCode == 'InvoiceRealization')$InvoiceRealization = $Translate;
		endforeach;
		
		$sql001   	= "SELECT browsername FROM browser";
		$res001 	= $this->db->query($sql001)->result();
		foreach($res001 as $row001) :
			$browsername 	= $row001->browsername;
			$trendbrowser	= $browsername;    
			$sql002   	= "SELECT total FROM browser WHERE browsername = '$trendbrowser'";
			$res002 	= $this->db->query($sql002)->result();
			foreach($res002 as $row002) :
				$total 	= $row002->total;
			endforeach;
		endforeach;
		
		$thisDay	= (int)date('d');
		$modDay		= $thisDay % 6;
		if($modDay == 0)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading.gif";
		}
		elseif($modDay == 1)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif";
		}
		elseif($modDay == 2)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading2.gif";
		}
		elseif($modDay == 3)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading3.gif";
		}
		elseif($modDay == 4)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading4.gif";
		}
		elseif($modDay == 5)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading5.gif";
		}

		if($LangID == 'IND')
		{
			$transL1	= "Mengaktfikan 'Fixed Layout'. Anda tidak dapat menggunakan 'Fixed Layout' dan 'Boxed Layout' bersama-sama.";
			$transL2	= "Mengaktifkan 'Boxed Layout'.";
			$transL3	= "Sembunyikan menu utama sisi kiri.";
			$transL4	= "SO Confirmed vs Approved";
			$transL5	= "Aktifitas";
			$transL6	= "Persediaan bahan produksi";
			$transL7	= "Sales Order disetujui per tahun berjalan";
			$transL8	= "Persediaan material dalam proses";
			$transL9	= "Persediaan barang jadi";
			$transL10	= "Progres Penyelesaian";
			$transL11	= "Informasi Umum";
			$transL12	= "Latest Login";
		}
		else
		{
			$transL1	= "Activate the 'Fixed Layout'. You can't use 'Fixed Layout' and 'Boxed Layout' together.";
			$transL2	= "Activate the boxed layout";
			$transL3	= "Toggle the left sidebar's state.";
			$transL4	= "SO Confirmed vs Approved";
			$transL5	= "Activity Graph";
			$transL6	= "Inventory of production materials";
			$transL7	= "Sales Order Approved Annualy";
			$transL8	= "Material inventory in process";
			$transL9	= "Finished goods inventory";
			$transL10	= "Completion Progress";
			$transL11	= "General Information";
			$transL12	= "Latest Login";
		}
	?>
	<body class="<?php echo $appBody; ?>" onLoad="StartTimers();" onmousemove="ResetTimers();">
		<!-- <input type="hidden" name="isEnd" id="isEnd" value="<?php echo $isEnd; ?>"> -->
		<input type="hidden" name="hasSv" id="hasSv" value="<?php echo $vLogV; ?>">
		<input type="hidden" name="EMPID" id="EMPID" value="<?php echo $Emp_ID; ?>">
		<div class="preloader">
			<div class="loading">
				<img src="<?php echo base_url() . $loadFile; ?>" width="150">
			</div>
		</div>

		<section class="content">
			<div class="row">
				<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-yellow-gradient"><i class="fa fa-question"></i></span>
					    <?php
							$cLogTask	= "tbl_task_request WHERE TASK_REQUESTER = '$Emp_ID'";
							$cLogTaskv	= $this->db->count_all($cLogTask);
						?>
						<div class="info-box-content">
							<span class="info-box-text">TASK REQUEST</span>
							<span class="info-box-number"><?php echo number_format($cLogTaskv,0); ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-green-gradient"><i class="fa fa-history"></i></span>
					    <?php
							$cLogHist	= "tbl_login_hist WHERE LOG_EMP = '$Emp_ID'";
							$cLogHistV	= $this->db->count_all($cLogHist);
						?>
						<div class="info-box-content">
							<span class="info-box-text">LOG HIST.</span>
							<span class="info-box-number"><?php echo number_format($cLogHistV,0); ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-blue-gradient"><i class="fa fa-flag-o"></i></span>

						<div class="info-box-content">
							<span class="info-box-text">
						    <?php
								$getCount		= "tbl_employee_proj WHERE Emp_ID = '$Emp_ID'";
								$resGetCount	= $this->db->count_all($getCount);
							?>
						    <form name="frmsrch" id="frmsrch" action="" method=POST>
						        <select name="selPRJCODE" id="selPRJCODE" class="form-control select2" onChange="chooseProject(this)">
						        	<option value="AllPRJ" <?php if($selPRJCODE == "AllPRJ") { ?> selected <?php } ?>> All</option>
									<?php                
						                if($resGetCount > 0 && $PRJSCATEG == 1)
						                {
						                    $getData		= "SELECT A.Emp_ID, A.proj_Code, B.PRJNAME 
						                                        FROM tbl_employee_proj A
						                                        INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
						                                        WHERE A.Emp_ID = '$Emp_ID'";
						                    $resGetData 	= $this->db->query($getData)->result();
						                    foreach($resGetData as $rowData) :
						                        $Emp_ID 	= $rowData->Emp_ID;
						                        $proj_Code 	= $rowData->proj_Code;
						                        $proj_Name 	= $rowData->PRJNAME;
						                        ?>
						                        <option value="<?php echo $proj_Code; ?>" <?php if($proj_Code == $selPRJCODE) { ?> selected <?php } ?>><?php echo "$proj_Name"; ?></option>
						                        <?php
						                    endforeach;
						                }
						            ?>
						        </select>
						        <input type="submit" name="submitPRJ" id="submitPRJ" style="display:none">
						    </form>
							</span>
							<span class="info-box-number"><?php echo number_format($resGetCount,0); ?><font size="2px" style="font-style: italic;"> - company(s) associated with you</font> </span>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
					<div class="small-box bg-teal-gradient">
						<div class="inner">
						    <?php
								$cLogEMP	= "tbl_trail_tracker WHERE TTR_EMPID = '$Emp_ID' AND DATE(TTR_DATE) = DATE(NOW())";
								$cLogEMP	= $this->db->count_all($cLogEMP);
							?>
							<h3><?php echo $cLogEMP; ?></h3>
							<p>Activity Count</p>
						</div>
						<div class="icon">
							<i class="ion ion-speedometer"></i>
						</div>
					</div>
				</div>
                <div class="col-md-3 col-sm-6 col-xs-12">
					<div class="small-box bg-yellow-gradient">
						<div class="inner">
						    <?php
						    	$AVGLOG = 0;
								/*$cLogT	= "SELECT COUNT(*) / DAY(LAST_DAY(now())) * 100 AS AVG_LOG FROM tbl_trail_tracker
											WHERE TTR_EMPID = '$EMP_ID' AND DATE(TTR_DATE) = DATE(NOW());";*/
								$AVGLOG2= 0;
								$cLogT2	= "SELECT COUNT(*) AS LOGT2 FROM tbl_trail_tracker WHERE DATE(TTR_DATE) = DATE(NOW())";
								$cLogT2	= $this->db->query($cLogT2)->result();
								foreach($cLogT2 as $rowT2):
									$AVGLOG2 	= $rowT2->LOGT2;
								endforeach;
								if($AVGLOG2 == 0) $AVGLOG2 = 1;
								$AVGLOG = $cLogEMP / $AVGLOG2 * 100;
							?>
							<h3><?php echo number_format($AVGLOG,2); ?><sup style="font-size: 20px">%</sup></h3>
							<p>Login Rate</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
					</div>
				</div>
                <div class="col-md-3 col-sm-6 col-xs-12">
					<div class="small-box bg-green-gradient">
						<div class="inner">
						    <?php
						    	$usrT = 0;
								$qusrT= "tbl_employee WHERE Emp_Status = '1'";
								$rusrT= $this->db->count_all($qusrT);
							?>
							<h3><?php echo $rusrT; ?></h3>
							<p>Total Users</p>
						</div>
						<div class="icon">
							<i class="ion ion-ios-people"></i>
						</div>
					</div>
				</div>
                <div class="col-md-3 col-sm-6 col-xs-12">
					<div class="small-box bg-green-gradient">
						<div class="inner">
						    <?php
						    	$OLT 	= 0;
								$qOLT	= "tbl_employee WHERE Emp_Status = '1' AND OLStat = '1'";
								$rOLT 	= $this->db->count_all($qOLT);
							?>
							<h3><?php echo $rOLT; ?></h3>
							<p>Online Users</p>
						</div>
						<div class="icon">
							<i class="ion ion-planet"></i>
						</div>
					</div>
				</div>
				<?php $url_sHw	=  site_url('__180c2f/shwLstDoc/?id='.$this->url_encryption_helper->encode_url($appName)); ?>
				<script>
                    var url = "<?php echo $url_sHw;?>";
		            
                    function shwDoc(theTbl)
                    {
                       	PRJCODE	= '<?php echo $selPRJCODE;?>';

                        title 	= 'Show document';
                        w = 800;
                        h = 550;

                        var left = (screen.width/2)-(w/2);
                        var top = (screen.height/2)-(h/2);
                        return window.open(url+'&prjC='+PRJCODE+'&theTbl='+theTbl, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                    }
                </script>
				<!--- START : DOC. STATUS INFORMATION -->
		            <?php if($REQ_NEW == 1) { if($TOT_REQ_NEW > 20) { $COL_REQ_NEW = "red"; } else if($TOT_REQ_NEW > 10) { $COL_REQ_NEW = "red"; } else { $COL_REQ_NEW = "red";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_REQ_NEW; ?>">
		                    	<div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-yellow-gradient text-lg">
										New
									</div>
								</div>
		                        <div class="inner">
		                            <h3><?php echo $TOT_REQ_NEW; ?></h3>
		                            Requisition
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-bag"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_pr_header~PR_CODE~PR_DATE~PR_NOTE~PR_STAT~1"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($REQ_CON == 1) { if($TOT_REQ_CON > 20) { $COL_REQ_CON = "yellow"; } else if($TOT_REQ_CON > 10) { $COL_REQ_CON = "yellow"; } else { $COL_REQ_CON = "yellow";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_REQ_CON; ?>">
		                    	<div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-green-gradient text-lg">
										Confirmed
									</div>
								</div>
		                        <div class="inner">
		                            <h3><?php echo $TOT_REQ_CON; ?></h3>
		                            Requisition
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-bag"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_pr_header~PR_CODE~PR_DATE~PR_NOTE~PR_STAT~2"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($REQ_APP == 1) { if($TOT_REQ_APP > 20) { $COL_REQ_APP = "green"; } else if($TOT_REQ_APP > 10) { $COL_REQ_APP = "green"; } else { $COL_REQ_APP = "green";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_REQ_APP; ?>">
		                    	<div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-blue-gradient text-lg">
										Approved
									</div>
								</div>
		                        <div class="inner">
		                            <h3><?php echo $TOT_REQ_APP; ?></h3>
		                            Requisition
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-bag"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_pr_header~PR_CODE~PR_DATE~PR_NOTE~PR_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($REQ_CLS == 1) { if($TOT_REQ_CLS > 20) { $COL_REQ_CLS = "primary"; } else if($TOT_REQ_CLS > 10) { $COL_REQ_CLS = "primary"; } else { $COL_REQ_CLS = "primary";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_REQ_CLS; ?>">
		                    	<div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-aqua-gradient text-lg">
										Closed
									</div>
								</div>
		                        <div class="inner">
		                            <h3><?php echo $TOT_REQ_CLS; ?></h3>
		                            Requisition
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-bag"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_pr_header~PR_CODE~PR_DATE~PR_NOTE~PR_STAT~6"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($PO_NEW == 1) { if($TOT_PO_NEW > 10) { $COL_PO_NEW = "red"; } else if($TOT_PO_NEW > 0) { $COL_PO_NEW = "red"; } else { $COL_PO_NEW = "red";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_PO_NEW; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-yellow-gradient text-lg">
										New
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_PO_NEW; ?></h3>
		                            Purchase Order
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-ios-cart-outline"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_po_header~PO_CODE~PO_DATE~PO_NOTES~PO_STAT~1"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($PO_CON == 1) { if($TOT_PO_CON > 10) { $COL_PO_CON = "yellow"; } else if($TOT_PO_CON > 0) { $COL_PO_CON = "yellow"; } else { $COL_PO_CON = "yellow";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_PO_CON; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-green-gradient text-lg">
										Confirmed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_PO_CON; ?></h3>
		                            Purchase Order
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-ios-cart-outline"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_po_header~PO_CODE~PO_DATE~PO_NOTES~PO_STAT~2"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($PO_APP == 1) { if($TOT_PO_APP > 10) { $COL_PO_APP = "green"; } else if($TOT_PO_APP > 0) { $COL_PO_APP = "green"; } else { $COL_PO_APP = "green";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_PO_APP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-blue-gradient text-lg">
										Approved
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_PO_APP; ?></h3>
		                            Purchase Order
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-ios-cart-outline"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_po_header~PO_CODE~PO_DATE~PO_NOTES~PO_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($PO_CLS == 1) { if($TOT_PO_CLS > 10) { $COL_PO_CLS = "primary"; } else if($TOT_PO_CLS > 0) { $COL_PO_CLS = "primary"; } else { $COL_PO_CLS = "primary";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_PO_CLS; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-aqua-gradient text-lg">
										Closed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_PO_CLS; ?></h3>
		                            Purchase Order
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-ios-cart-outline"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_po_header~PO_CODE~PO_DATE~PO_NOTES~PO_STAT~6"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($IR_NEW == 1) { if($TOT_IR_NEW > 10) { $COL_IR_NEW = "red"; } else if($TOT_IR_NEW > 0) { $COL_IR_NEW = "red"; } else { $COL_IR_NEW = "red";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_IR_NEW; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-yellow-gradient text-lg">
										New
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_IR_NEW; ?></h3>
		                            Item Receipt
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cube"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_ir_header~IR_CODE~IR_DATE~IR_NOTE~IR_STAT~1"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($IR_CON == 1) { if($TOT_IR_CON > 10) { $COL_IR_CON = "yellow"; } else if($TOT_IR_CON > 0) { $COL_IR_CON = "yellow"; } else { $COL_IR_CON = "yellow";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_IR_CON; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-green-gradient text-lg">
										Confirmed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_IR_CON; ?></h3>
		                            Item Receipt
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cube"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_ir_header~IR_CODE~IR_DATE~IR_NOTE~IR_STAT~2"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($IR_APP == 1) { if($TOT_IR_APP > 10) { $COL_IR_APP = "green"; } else if($TOT_IR_APP > 0) { $COL_IR_APP = "green"; } else { $COL_IR_APP = "green";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_IR_APP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-blue-gradient text-lg">
										Approved
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_IR_APP; ?></h3>
		                            Item Receipt
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cube"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_ir_header~IR_CODE~IR_DATE~IR_NOTE~IR_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($IR_CLS == 1) { if($TOT_IR_CLS > 10) { $COL_IR_CLS = "primary"; } else if($TOT_IR_CLS > 0) { $COL_IR_CLS = "primary"; } else { $COL_IR_CLS = "primary";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_IR_CLS; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-aqua-gradient text-lg">
										Closed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_IR_CLS; ?></h3>
		                            Item Receipt
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cube"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_ir_header~IR_CODE~IR_DATE~IR_NOTE~IR_STAT~6"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($WO_NEW == 1) { if($TOT_WO_NEW > 10) { $COL_WO_NEW = "red"; } else if($TOT_WO_NEW > 0) { $COL_WO_NEW = "red"; } else { $COL_WO_NEW = "red";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_WO_NEW; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-yellow-gradient text-lg">
										New
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_WO_NEW; ?></h3>
		                            Work Order / SPK 
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-lightbulb"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_wo_header~WO_CODE~WO_DATE~WO_NOTE~WO_STAT~1"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($WO_CON == 1) { if($TOT_WO_CON > 10) { $COL_WO_CON = "yellow"; } else if($TOT_WO_CON > 0) { $COL_WO_CON = "yellow"; } else { $COL_WO_CON = "yellow";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_WO_CON; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-green-gradient text-lg">
										Confirmed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_WO_CON; ?></h3>
		                            Work Order / SPK
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-lightbulb"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_wo_header~WO_CODE~WO_DATE~WO_NOTE~WO_STAT~2"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($WO_APP == 1) { if($TOT_WO_APP > 10) { $COL_WO_APP = "green"; } else if($TOT_WO_APP > 0) { $COL_WO_APP = "green"; } else { $COL_WO_APP = "green";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_WO_APP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-blue-gradient text-lg">
										Approved
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_WO_APP; ?></h3>
		                            Work Order / SPK
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-lightbulb"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_wo_header~WO_CODE~WO_DATE~WO_NOTE~WO_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($WO_CLS == 1) { if($TOT_WO_CLS > 10) { $COL_WO_CLS = "primary"; } else if($TOT_WO_CLS > 0) { $COL_WO_CLS = "primary"; } else { $COL_WO_CLS = "primary";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_WO_CLS; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-aqua-gradient text-lg">
										Closed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_WO_CLS; ?></h3>
		                            Work Order / SPK
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-lightbulb"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_wo_header~WO_CODE~WO_DATE~WO_NOTE~WO_STAT~6"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($OPN_NEW == 1) { if($TOT_OPN_NEW > 10) { $COL_OPN_NEW = "red"; } else if($TOT_OPN_NEW > 0) { $COL_OPN_NEW = "red"; } else { $COL_OPN_NEW = "red";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_OPN_NEW; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-yellow-gradient text-lg">
										New
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_OPN_NEW; ?></h3>
		                            Opname
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-clipboard"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_opn_header~OPNH_CODE~OPNH_DATE~OPNH_NOTE~OPNH_STAT~1"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($OPN_CON == 1) { if($TOT_OPN_CON > 10) { $COL_OPN_CON = "yellow"; } else if($TOT_OPN_CON > 0) { $COL_OPN_CON = "yellow"; } else { $COL_OPN_CON = "yellow";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_OPN_CON; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-green-gradient text-lg">
										Confirmed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_OPN_CON; ?></h3>
		                            Opname
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-clipboard"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_opn_header~OPNH_CODE~OPNH_DATE~OPNH_NOTE~OPNH_STAT~2"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($OPN_APP == 1) { if($TOT_OPN_APP > 10) { $COL_OPN_APP = "green"; } else if($TOT_OPN_APP > 0) { $COL_OPN_APP = "green"; } else { $COL_OPN_APP = "green";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_OPN_APP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-blue-gradient text-lg">
										Approved
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_OPN_APP; ?></h3>
		                            Opname
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-clipboard"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_opn_header~OPNH_CODE~OPNH_DATE~OPNH_NOTE~OPNH_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($OPN_CLS == 1) { if($TOT_OPN_CLS > 10) { $COL_OPN_CLS = "primary"; } else if($TOT_OPN_CLS > 0) { $COL_OPN_CLS = "primary"; } else { $COL_OPN_CLS = "primary";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_OPN_CLS; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-aqua-gradient text-lg">
										Closed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_OPN_CLS; ?></h3>
		                            Opname
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-clipboard"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_opn_header~OPNH_CODE~OPNH_DATE~OPNH_NOTE~OPNH_STAT~6"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($PINV_CON == 1) { if($TOT_PINV_CON > 10) { $COL_PINV_CON = "red"; } else if($TOT_PINV_CON > 0) { $COL_PINV_CON = "red"; } else { $COL_PINV_CON = "red";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_PINV_CON; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-yellow-gradient text-lg">
										Confirmed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_PINV_CON; ?></h3>
		                            Purchase Invoice
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-android-playstore"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_pinv_header~INV_CODE~INV_DATE~INV_NOTES~INV_STAT~2"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($PINV_APP == 1) { if($TOT_PINV_APP > 10) { $COL_PINV_APP = "yellow"; } else if($TOT_PINV_APP > 0) { $COL_PINV_APP = "yellow"; } else { $COL_PINV_APP = "yellow";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_PINV_APP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-green-gradient text-lg">
										Not Paid
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_PINV_APP; ?></h3>
		                            Purchase Invoice
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-android-playstore"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_opn_header~OPNH_CODE~OPNH_DATE~OPNH_NOTE~OPNH_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($PINV_HP == 1) { if($TOT_PINV_HP > 10){ $COL_PINV_HP = "green"; } else if($TOT_PINV_HP > 0) { $COL_PINV_HP = "green"; } else { $COL_PINV_HP = "green";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_PINV_HP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-blue-gradient text-lg">
										Half Paid
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_PINV_HP; ?></h3>
		                            Purchase Invoice
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-android-playstore"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_pinv_header~INV_CODE~INV_DATE~INV_NOTES~INV_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($PINV_FP == 1) { if($TOT_PINV_FP > 10){ $COL_PINV_FP = "primary"; } else if($TOT_PINV_FP > 0) { $COL_PINV_FP = "primary"; } else { $COL_PINV_FP = "primary";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_PINV_FP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-aqua-gradient text-lg">
										Full Paid
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_PINV_FP; ?></h3>
		                            Purchase Invoice
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-android-playstore"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_pinv_header~INV_CODE~INV_DATE~INV_NOTES~INV_STAT~6"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($CB_NEW == 1) { if($TOT_CB_NEW > 10) { $COL_CB_NEW = "red"; } else if($TOT_CB_NEW > 0) { $COL_CB_NEW = "red"; } else { $COL_CB_NEW = "red";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_CB_NEW; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-yellow-gradient text-lg">
										New
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_CB_NEW; ?></h3>
		                            Cash/Bank Payment
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-card"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_bp_header~CB_CODE~CB_DATE~CB_NOTES~CB_STAT~1"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($CB_CON == 1) { if($TOT_CB_CON > 10) { $COL_CB_CON = "yellow"; } else if($TOT_CB_CON > 0) { $COL_CB_CON = "yellow"; } else { $COL_CB_CON = "yellow";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_CB_CON; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-green-gradient text-lg">
										Confirmed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_CB_CON; ?></h3>
		                            Cash/Bank Payment
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-card"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_bp_header~CB_CODE~CB_DATE~CB_NOTES~CB_STAT~2"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($CB_APP == 1) { if($TOT_CB_APP > 10) { $COL_CB_APP = "green"; } else if($TOT_CB_APP > 0) { $COL_CB_APP = "green"; } else { $COL_CB_APP = "green";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_CB_APP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-blue-gradient text-lg">
										Approved
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_CB_APP; ?></h3>
		                            Cash/Bank Payment
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-card"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_bp_header~CB_CODE~CB_DATE~CB_NOTES~CB_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($CB_CLS == 1) { if($TOT_CB_CLS > 10) { $COL_CB_CLS = "primary"; } else if($TOT_CB_CLS > 0) { $COL_CB_CLS = "primary"; } else { $COL_CB_CLS = "primary";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_CB_CLS; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-aqua-gradient text-lg">
										Closed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_CB_CLS; ?></h3>
		                            Cash/Bank Payment
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-card"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_bp_header~CB_CODE~CB_DATE~CB_NOTES~CB_STAT~6"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($MC_NEW == 1) { if($TOT_MC_NEW > 10) { $COL_MC_NEW = "red"; } else if($TOT_MC_NEW > 0) { $COL_MC_NEW = "red"; } else { $COL_MC_NEW = "red";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_MC_NEW; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-yellow-gradient text-lg">
										New
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_MC_NEW; ?></h3>
		                            MC Proyek
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-connection-bars"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_mcheader~MC_CODE~MC_DATE~MS_NOTES~MC_STAT~1"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($MC_CON == 1) { if($TOT_MC_CON > 10) { $COL_MC_CON = "yellow"; } else if($TOT_MC_CON > 0) { $COL_MC_CON = "yellow"; } else { $COL_MC_CON = "yellow";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_MC_CON; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-green-gradient text-lg">
										Confirmed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_MC_CON; ?></h3>
		                            MC Proyek
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-connection-bars"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_mcheader~MC_CODE~MC_DATE~MS_NOTES~MC_STAT~2"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($MC_APP == 1) { if($TOT_MC_APP > 10) { $COL_MC_APP = "green"; } else if($TOT_MC_APP > 0) { $COL_MC_APP = "green"; } else { $COL_MC_APP = "green";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_MC_APP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-blue-gradient text-lg">
										Approved
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_MC_APP; ?></h3>
		                            MC Proyek
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-connection-bars"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_mcheader~MC_CODE~MC_DATE~MS_NOTES~MC_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($MC_CLS == 1) { if($TOT_MC_CLS > 10) { $COL_MC_CLS = "primary"; } else if($TOT_MC_CLS > 0) { $COL_MC_CLS = "primary"; } else { $COL_MC_CLS = "primary";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_MC_CLS; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-aqua-gradient text-lg">
										Closed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_MC_CLS; ?></h3>
		                            MC Proyek
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-connection-bars"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_mcheader~MC_CODE~MC_DATE~MS_NOTES~MC_STAT~6"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($SO_NEW == 1) { if($TOT_SO_NEW > 10) { $COL_SO_NEW = "red"; } else if($TOT_SO_NEW > 0) { $COL_SO_NEW = "red"; } else { $COL_SO_NEW = "red";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_SO_NEW; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-yellow-gradient text-lg">
										New
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_SO_NEW; ?></h3>
		                            Sales Order
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-android-call"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_so_header~SO_CODE~SO_DATE~SO_NOTES~SO_STAT~1"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($SO_CON == 1) { if($TOT_SO_CON > 10) { $COL_SO_CON = "yellow"; } else if($TOT_SO_CON > 0) { $COL_SO_CON = "yellow"; } else { $COL_SO_CON = "yellow";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_SO_CON; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-green-gradient text-lg">
										Confirmed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_SO_CON; ?></h3>
		                            Sales Order
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-android-call"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_so_header~SO_CODE~SO_DATE~SO_NOTES~SO_STAT~2"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($SO_APP == 1) { if($TOT_SO_APP > 10) { $COL_SO_APP = "green"; } else if($TOT_SO_APP > 0) { $COL_SO_APP = "green"; } else { $COL_SO_APP = "green";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_SO_APP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-blue-gradient text-lg">
										Approved
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_SO_APP; ?></h3>
		                            Sales Order
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-android-call"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_so_header~SO_CODE~SO_DATE~SO_NOTES~SO_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($SO_CLS == 1) { if($TOT_SO_CLS > 10) { $COL_SO_CLS = "primary"; } else if($TOT_SO_CLS > 0) { $COL_SO_CLS = "primary"; } else { $COL_SO_CLS = "primary";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_SO_CLS; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-aqua-gradient text-lg">
										Closed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_SO_CLS; ?></h3>
		                            Sales Order
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-android-call"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_so_header~SO_CODE~SO_DATE~SO_NOTES~SO_STAT~6"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($JO_NEW == 1) { if($TOT_JO_NEW > 10) { $COL_JO_NEW = "red"; } else if($TOT_JO_NEW > 0) { $COL_JO_NEW = "red"; } else { $COL_JO_NEW = "red";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_JO_NEW; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-yellow-gradient text-lg">
										New
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_JO_NEW; ?></h3>
		                            Job Order / JO
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-clipboard"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_jo_header~JO_CODE~JO_DATE~JO_NOTES~JO_STAT~1"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($JO_CON == 1) { if($TOT_JO_CON > 10) { $COL_JO_CON = "yellow"; } else if($TOT_JO_CON > 0) { $COL_JO_CON = "yellow"; } else { $COL_JO_CON = "yellow";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_JO_CON; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-green-gradient text-lg">
										Confirmed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_JO_CON; ?></h3>
		                            Job Order / JO
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-clipboard"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_jo_header~JO_CODE~JO_DATE~JO_NOTES~JO_STAT~2"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($JO_APP == 1) { if($TOT_JO_APP > 10) { $COL_JO_APP = "green"; } else if($TOT_JO_APP > 0) { $COL_JO_APP = "green"; } else { $COL_JO_APP = "green";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_JO_APP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-blue-gradient text-lg">
										Approved
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_JO_APP; ?></h3>
		                            Job Order / JO
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-clipboard"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_jo_header~JO_CODE~JO_DATE~JO_NOTES~JO_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($JO_CLS == 1) { if($TOT_JO_CLS > 10) { $COL_JO_CLS = "primary"; } else if($TOT_JO_CLS > 0) { $COL_JO_CLS = "primary"; } else { $COL_JO_CLS = "primary";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_JO_CLS; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-aqua-gradient text-lg">
										Closed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_JO_CLS; ?></h3>
		                            Job Order / JO
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-clipboard"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_jo_header~JO_CODE~JO_DATE~JO_NOTES~JO_STAT~6"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($MR_NEW == 1) { if($TOT_MR_NEW > 10) { $COL_MR_NEW = "red"; } else if($TOT_MR_NEW > 0) { $COL_MR_NEW = "red"; } else { $COL_MR_NEW = "red";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_MR_NEW; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-yellow-gradient text-lg">
										New
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_MR_NEW; ?></h3>
		                            Material Request
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-android-microphone"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_mr_header~MR_CODE~MR_DATE~MR_NOTE~MR_STAT~1"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($MR_CON == 1) { if($TOT_MR_CON > 10) { $COL_MR_CON = "yellow"; } else if($TOT_MR_CON > 0) { $COL_MR_CON = "yellow"; } else { $COL_MR_CON = "yellow";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_MR_CON; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-green-gradient text-lg">
										Confirmed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_MR_CON; ?></h3>
		                            Material Request
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-android-microphone"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_mr_header~MR_CODE~MR_DATE~MR_NOTE~MR_STAT~2"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($MR_APP == 1) { if($TOT_MR_APP > 10) { $COL_MR_APP = "green"; } else if($TOT_MR_APP > 0) { $COL_MR_APP = "green"; } else { $COL_MR_APP = "green";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_MR_APP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-blue-gradient text-lg">
										Approved
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_MR_APP; ?></h3>
		                            Material Request
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-android-microphone"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_mr_header~MR_CODE~MR_DATE~MR_NOTE~MR_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($MR_CLS == 1) { if($TOT_MR_CLS > 10) { $COL_MR_CLS = "primary"; } else if($TOT_MR_CLS > 0) { $COL_MR_CLS = "primary"; } else { $COL_MR_CLS = "primary";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_MR_CLS; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-aqua-gradient text-lg">
										Closed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_MR_CLS; ?></h3>
		                            Material Request
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-android-microphone"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_mr_header~MR_CODE~MR_DATE~MR_NOTE~MR_STAT~6"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($SN_NEW == 1) { if($TOT_SN_NEW > 10) { $COL_SN_NEW = "red"; } else if($TOT_SN_NEW > 0) { $COL_SN_NEW = "red"; } else { $COL_SN_NEW = "red";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_SN_NEW; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-yellow-gradient text-lg">
										New
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_SN_NEW; ?></h3>
		                            Shipment Notes
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-navigate"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_sn_header~SN_CODE~SN_DATE~SN_NOTES~SN_STAT~1"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($SN_CON == 1) { if($TOT_SN_CON > 10) { $COL_SN_CON = "yellow"; } else if($TOT_SN_CON > 0) { $COL_SN_CON = "yellow"; } else { $COL_SN_CON = "yellow";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_SN_CON; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-green-gradient text-lg">
										Confirmed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_SN_CON; ?></h3>
		                            Shipment Notes
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-navigate"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_sn_header~SN_CODE~SN_DATE~SN_NOTES~SN_STAT~2"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($SN_APP == 1) { if($TOT_SN_APP > 10) { $COL_SN_APP = "green"; } else if($TOT_SN_APP > 0) { $COL_SN_APP = "green"; } else { $COL_SN_APP = "green";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_SN_APP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-blue-gradient text-lg">
										Approved
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_SN_APP; ?></h3>
		                            Shipment Notes
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-navigate"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_sn_header~SN_CODE~SN_DATE~SN_NOTES~SN_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($SN_CLS == 1) { if($TOT_SN_CLS > 10) { $COL_SN_CLS = "primary"; } else if($TOT_SN_CLS > 0) { $COL_SN_CLS = "primary"; } else { $COL_SN_CLS = "primary";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_SN_CLS; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-aqua-gradient text-lg">
										Closed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_SN_CLS; ?></h3>
		                            Shipment Notes
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-navigate"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_sn_header~SN_CODE~SN_DATE~SN_NOTES~SN_STAT~6"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($SINV_NEW == 1) { if($TOT_SINV_NEW > 10) { $COL_SINV_NEW = "red"; } else if($TOT_SINV_NEW > 0) { $COL_SINV_NEW = "red"; } else { $COL_SINV_NEW = "red";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_SINV_NEW; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-yellow-gradient text-lg">
										New
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_SINV_NEW; ?></h3>
		                            Sales Invoice
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-filing"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_sinv_header~SINV_CODE~SINV_DATE~SINV_NOTES~SINV_STAT~1"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($SINV_CON == 1) { if($TOT_SINV_CON > 10) { $COL_SINV_CON = "yellow"; } else if($TOT_SINV_CON > 0) { $COL_SINV_CON = "yellow"; } else { $COL_SINV_CON = "yellow";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_SINV_CON; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-green-gradient text-lg">
										Confirmed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_SINV_CON; ?></h3>
		                            Sales Invoice
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-filing"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_sinv_header~SINV_CODE~SINV_DATE~SINV_NOTES~SINV_STAT~2"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($SINV_APP == 1) { if($TOT_SINV_APP > 10) { $COL_SINV_APP = "green"; } else if($TOT_SINV_APP > 0) { $COL_SINV_APP = "green"; } else { $COL_SINV_APP = "green";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_SINV_APP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-blue-gradient text-lg">
										Approved
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_SINV_APP; ?></h3>
		                            Sales Invoice
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-filing"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_sinv_header~SINV_CODE~SINV_DATE~SINV_NOTES~SINV_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($SINV_CLS == 1) { if($TOT_SINV_CLS > 10) { $COL_SINV_CLS = "primary"; } else if($TOT_SINV_CLS > 0) { $COL_SINV_CLS = "primary"; } else { $COL_SINV_CLS = "primary";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_SINV_CLS; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-aqua-gradient text-lg">
										Closed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_SINV_CLS; ?></h3>
		                            Sales Invoice
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-filing"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_sinv_header~SINV_CODE~SINV_DATE~SINV_NOTES~SINV_STAT~6"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($BR_NEW == 1) { if($TOT_BR_NEW > 10) { $COL_BR_NEW = "red"; } else if($TOT_BR_NEW > 0) { $COL_BR_NEW = "red"; } else { $COL_BR_NEW = "red";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_BR_NEW; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-yellow-gradient text-lg">
										New
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_BR_NEW; ?></h3>
		                            Bank Receipt
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cash"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_br_header~BR_CODE~BR_DATE~BR_NOTES~BR_STAT~1"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($BR_CON == 1) { if($TOT_BR_CON > 10) { $COL_BR_CON = "yellow"; } else if($TOT_BR_CON > 0) { $COL_BR_CON = "yellow"; } else { $COL_BR_CON = "yellow";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_BR_CON; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-green-gradient text-lg">
										Confirmed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_BR_CON; ?></h3>
		                            Bank Receipt
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cash"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_br_header~BR_CODE~BR_DATE~BR_NOTES~BR_STAT~2"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($BR_APP == 1) { if($TOT_BR_APP > 10) { $COL_BR_APP = "green"; } else if($TOT_BR_APP > 0) { $COL_BR_APP = "green"; } else { $COL_BR_APP = "green";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_BR_APP; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-blue-gradient text-lg">
										Approved
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_BR_APP; ?></h3>
		                            Bank Receipt
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cash"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_br_header~BR_CODE~BR_DATE~BR_NOTES~BR_STAT~3"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php } if($BR_CLS == 1) { if($TOT_BR_CLS > 10) { $COL_BR_CLS = "primary"; } else if($TOT_BR_CLS > 0) { $COL_BR_CLS = "primary"; } else { $COL_BR_CLS = "primary";} ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-<?php echo $COL_BR_CLS; ?>">
		                        <div class="ribbon-wrapper ribbon-lg">
									<div class="ribbon bg-aqua-gradient text-lg">
										Closed
									</div>
								</div>
								<div class="inner">
		                            <h3><?php echo $TOT_BR_CLS; ?></h3>
		                            Bank Receipt
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-cash"></i>
		                        </div>
		                        <?php $shwType 	= "tbl_br_header~BR_CODE~BR_DATE~BR_NOTES~BR_STAT~6"; ?>
		                        <a href="javascript:void(null);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right" onClick="shwDoc('<?php echo $shwType; ?>');"></i></a>
		                    </div>
		                </div>
		            <?php /* dashboard1_190514 Line 2160 - 2215; */ ?>
		            <?php } if($EMP_TOT == 1) { ?>
		                <div class="col-md-3 col-sm-6 col-xs-12">
		                    <div class="small-box bg-aqua">
		                        <div class="inner">
		                            <h3><?php echo $DHR_TOTACT; ?></h3>
		                            Employe <br />Total Employee
		                        </div>
		                        <div class="icon">
		                            <i class="ion ion-android-contacts"></i>
		                        </div>
		                    </div>
		                </div>
		            <?php } ?>
		    	<!--- END : DOC. STATUS INFORMATION -->
		    </div>

		    <!--- START : PRODUCTION GRAP -->
	        	<?php if($GRF_DPROD == 1) { ?>
			      	<div class="row">
			          	<div class="col-lg-3 col-xs-4">
			          		<div class="box box-success">
			          			<div id="salesInfo"></div>
			          		</div>
	                	</div>
			          	<div class="col-lg-3 col-xs-8">
	                		<div class="box box-primary">
	                			<div id="dailyProd"></div>
	                		</div>
	                	</div>
					</div>
				<?php } ?>
		    	<!--- END : PRODUCTION GRAP -->

				<?php if($GRF_PURCH == 1) { ?>
			      	<div class="row">
				        <section class="col-lg-12">
				        	<div class="box box-warning">
				        		<div class="box-body">
									<!-- START 	: PURCHASING GRAPH / DOCUMENT PROGRESS (CONTR.) -->
										<div class="nav-tabs-custom">
											<div class="col-md-8">
												<div class="tab-content">
									               	<canvas class="chart tab-pane" id="purchase-chart" style="position: relative; height: 300px;"></canvas>
												</div>
											</div>
							                <?php //if($PRJSCATEG == 1 && $GRF_GOALC == 1) {
												$PR_2_AMN 	= 0;
												$PR_3_AMN 	= 0;
												$PR_TOT		= 0;
												$PR_PER		= 0;

												$PO_2_AMN 	= 0;
												$PO_3_AMN 	= 0;
												$PO_TOT		= 0;
												$PO_PER		= 0;

												$IR_2_AMN 	= 0;
												$IR_3_AMN 	= 0;
												$IR_TOT		= 0;
												$IR_PER		= 0;

												$WO_2_AMN 	= 0;
												$WO_3_AMN 	= 0;
												$WO_TOT		= 0;
												$WO_PER		= 0;

												$OPN_2_AMN 	= 0;
												$OPN_3_AMN 	= 0;
												$OPN_TOT	= 0;
												$OPN_PER	= 0;

												if($PRJSCATEG == 1)
													$thePRJ	= "PRJCODE IN ($myPRJCODE) AND";
												else
													$thePRJ	= "PRJCODE_HO IN ($myPRJCODE) AND";

							                	$sqlSOGC		= "SELECT 	SUM(PR_2_AMN) AS PR_2_AMN, SUM(PR_3_AMN) AS PR_3_AMN,
							                								SUM(PO_2_AMN) AS PO_2_AMN, SUM(PO_3_AMN) AS PO_3_AMN,
							                								SUM(IR_2_AMN) AS IR_2_AMN, SUM(IR_3_AMN) AS IR_3_AMN,
							                								SUM(WO_2_AMN) AS WO_2_AMN, SUM(WO_3_AMN) AS WO_3_AMN,
							                								SUM(OPN_2_AMN) AS OPN_2_AMN, SUM(OPN_3_AMN) AS OPN_3_AMN
							                						FROM tbl_qty_coll WHERE $thePRJ YEAR(PERIODE) = YEAR(NOW())";
												$resSOGC		= $this->db->query($sqlSOGC)->result();
												foreach($resSOGC as $rowSOGC):
													$PR_2_AMN 	= $rowSOGC->PR_2_AMN;
													$PR_3_AMN 	= $rowSOGC->PR_3_AMN;
													$PR_TOT		= $PR_2_AMN + $PR_3_AMN;
													$PR_TOTD	= $PR_TOT;
													if($PR_TOT == 0) $PR_TOTD = 1;

													$PO_2_AMN 	= $rowSOGC->PO_2_AMN;
													$PO_3_AMN 	= $rowSOGC->PO_3_AMN;
													$PO_TOT		= $PO_2_AMN + $PO_3_AMN;

													$PO_3_AMND	= $PO_3_AMN;
													if($PO_3_AMN == 0) $PO_3_AMND = 1;
													$PO_TOTD	= $PO_TOT;
													if($PO_TOT == 0) $PO_TOTD = 1;

													$IR_2_AMN 	= $rowSOGC->IR_2_AMN;
													$IR_3_AMN 	= $rowSOGC->IR_3_AMN;
													$IR_TOT		= $IR_2_AMN + $IR_3_AMN;
													$IR_TOTD	= $IR_TOT;
													if($IR_TOT == 0) $IR_TOTD = 1;

													$WO_2_AMN 	= $rowSOGC->WO_2_AMN;
													$WO_3_AMN 	= $rowSOGC->WO_3_AMN;
													$WO_TOT		= $WO_2_AMN + $WO_3_AMN;
													$WO_TOTD	= $WO_TOT;
													if($WO_TOT == 0) $WO_TOTD = 1;

													$OPN_2_AMN 	= $rowSOGC->OPN_2_AMN;
													$OPN_3_AMN 	= $rowSOGC->OPN_3_AMN;
													$OPN_TOT	= $OPN_2_AMN + $OPN_3_AMN;
													$OPN_TOTD	= $OPN_TOT;
													if($OPN_TOT == 0) $OPN_TOTD = 1;

													// PERCENTATION
														$PR_PER		= $PR_3_AMN / $PR_TOTD * 100;
														$PO_PER		= $PO_3_AMN / $PO_TOTD * 100;
														$IR_PER		= $IR_3_AMN / $IR_TOTD * 100;
														$WO_PER		= $WO_3_AMN / $WO_TOTD * 100;
														$OPN_PER	= $OPN_3_AMN / $OPN_TOTD * 100;
												endforeach;
												if($PR_PER == 0) $PR_PER = 1;
												if($PO_PER == 0) $PO_PER = 1;
												if($IR_PER == 0) $IR_PER = 1;
												if($WO_PER == 0) $WO_PER = 1;
												if($OPN_PER == 0) $OPN_PER = 1;
											?>
											<div class="col-md-4">
												<p class="text-center">
													<strong><?php //echo $transL10; ?></strong><br><!-- (Juta Rupiah) -->
												</p>

												<div class="progress-group">
													<span class="progress-text"><?php echo "$PurchaseRequest : $PRJSCATEG"; ?></span>
													<span class="progress-number">
														<b><?php echo number_format($PR_3_AMN/1000000,2); ?></b>/<?php echo number_format($PR_TOTD/1000000,2); ?>
													</span>
													<div class="progress sm">
														<div class="progress-bar progress-bar-aqua" style="width: <?php echo $PR_PER; ?>%"></div>
													</div>
												</div>

												<div class="progress-group">
													<span class="progress-text"><?php echo $Purchase; ?></span>
													<span class="progress-number">
														<b><?php echo number_format($PO_3_AMN/1000000,2); ?></b>/<?php echo number_format($PO_TOTD/1000000,2); ?>
													</span>
													<div class="progress sm">
														<div class="progress-bar progress-bar-blue" style="width: <?php echo $PO_PER; ?>%"></div>
													</div>
												</div>

												<div class="progress-group">
													<span class="progress-text"><?php echo $MtrReceipt; ?></span>
													<span class="progress-number">
														<b><?php echo number_format($IR_3_AMN/1000000,2); ?></b>/<?php echo number_format($IR_TOTD/1000000,2); ?>
													</span>
													<div class="progress sm">
														<div class="progress-bar progress-bar-green" style="width: <?php echo $IR_PER; ?>%"></div>
													</div>
												</div>

												<div class="progress-group">
													<span class="progress-text"><?php echo $WorkOrder; ?></span>
													<span class="progress-number">
														<b><?php echo number_format($WO_3_AMN/1000000,2); ?></b>/<?php echo number_format($WO_TOTD/1000000,2); ?>
													</span>
													<div class="progress sm">
														<div class="progress-bar progress-bar-yellow" style="width: <?php echo $WO_PER; ?>%"></div>
													</div>
												</div>

												<div class="progress-group">
													<span class="progress-text"><?php echo $JobOpname; ?></span>
													<span class="progress-number">
														<b><?php echo number_format($OPN_3_AMN/1000000,2); ?></b>/<?php echo number_format($OPN_TOTD/1000000,2); ?>
													</span>
													<div class="progress sm">
														<div class="progress-bar progress-bar-red" style="width: <?php echo $OPN_PER; ?>%"></div>
													</div>
												</div>
											</div>
										</div>
									<!-- END 	: PURCHASING GRAPH / DOCUMENT PROGRESS (CONTR.) -->
								</div>
							</div>
						</section>
					</div>
				<?php } ?>
				<?php if($GRF_SALES == 1) { ?>
		        	<div class="box box-success">
		        		<div class="box-body">
							<div class="row">
								<div class="col-md-8">
									<div class="col-sm-12">
										<div class="tab-content">
							               	<canvas class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></canvas>
										</div>
									</div>
								</div>
								<?php //if($PRJSCATEG == 2 && $GRF_GOALC == 1) { 
									$SO_2_AMN 	= 0;
									$SO_3_AMN 	= 0;
									$SO_TOT		= 0;
									$SO_PER		= 0;

									$JO_2_AMN 	= 0;
									$JO_3_AMN 	= 0;
									$JO_TOT		= 0;
									$JO_PER		= 0;

									$STF_2_AMN 	= 0;
									$STF_3_AMN 	= 0;
									$STF_TOT	= 0;
									$STF_PER	= 0;

									$SN_2_AMN 	= 0;
									$SN_3_AMN 	= 0;
									$SN_TOT		= 0;
									$SN_PER		= 0;

									if($PRJSCATEG == 1)
										$thePRJ	= "PRJCODE IN ($myPRJCODE) AND";
									else
										$thePRJ	= "PRJCODE_HO IN ($myPRJCODE) AND";

				                	$sqlSOGC		= "SELECT 	SUM(SO_2_AMN) AS SO_2_AMN, SUM(SO_3_AMN) AS SO_3_AMN,
				                								SUM(JO_2_AMN) AS JO_2_AMN, SUM(JO_3_AMN) AS JO_3_AMN,
				                								SUM(STF_2_AMN) AS STF_2_AMN, SUM(STF_3_AMN) AS STF_3_AMN,
				                								SUM(SN_2_AMN) AS SN_2_AMN, SUM(SN_3_AMN) AS SN_3_AMN
				                						FROM tbl_qty_coll WHERE $thePRJ YEAR(PERIODE) = YEAR(NOW())";
									$resSOGC		= $this->db->query($sqlSOGC)->result();
									foreach($resSOGC as $rowSOGC):
										$SO_2_AMN 	= $rowSOGC->SO_2_AMN;
										$SO_3_AMN 	= $rowSOGC->SO_3_AMN;
										$SO_TOT		= $SO_2_AMN + $SO_3_AMN;
										$SO_TOTD	= $SO_TOT;
										if($SO_TOT == 0) $SO_TOTD = 1;

										$JO_2_AMN 	= $rowSOGC->JO_2_AMN;
										$JO_3_AMN 	= $rowSOGC->JO_3_AMN;
										$JO_TOT		= $JO_2_AMN + $JO_3_AMN;

										$JO_3_AMND	= $JO_3_AMN;
										if($JO_3_AMN == 0) $JO_3_AMND = 1;
										$JO_TOTD	= $JO_TOT;
										if($JO_TOT == 0) $JO_TOTD = 1;

										$STF_2_AMN 	= $rowSOGC->STF_2_AMN;
										$STF_3_AMN 	= $rowSOGC->STF_3_AMN;
										$STF_TOT	= $STF_2_AMN + $STF_3_AMN;
										$STF_TOTD	= $STF_TOT;
										if($STF_TOT == 0) $STF_TOTD = 1;

										$SN_2_AMN 	= $rowSOGC->SN_2_AMN;
										$SN_3_AMN 	= $rowSOGC->SN_3_AMN;
										$SN_TOT		= $SN_2_AMN + $SN_3_AMN;
										$SN_TOTD	= $SN_TOT;
										if($SN_TOT == 0) $SN_TOTD = 1;

										// PERCENTATION
											$SO_PER		= $SO_3_AMN / $SO_TOTD * 100;
											$JO_PER		= $JO_3_AMN / $JO_TOTD * 100;
											$STF_PER	= $STF_3_AMN / $JO_3_AMND * 100;
											$SN_PER		= $SN_3_AMN / $SN_TOTD * 100;
									endforeach;
									if($SO_PER == 0) $SO_PER = 1;
									if($JO_PER == 0) $JO_PER = 1;
									if($STF_PER == 0) $STF_PER = 1;
									if($SN_PER == 0) $SN_PER = 1;
				                ?>
				                <div class="col-md-4">
				                    <p class="text-center">
				                    	<strong><?php //echo $transL10; ?> <?php //echo date('Y'); ?> <br><font size="2px" style="font-style: italic;"> <!-- ( dalam Juta ) --></font></strong>
				                    </p>
				                    <div class="progress-group">
				                        <span class="progress-text"><?php echo $SalesOrder; ?></span>
				                        <span class="progress-number"><b><?php echo number_format($SO_3_AMN/1000000,2); ?></b>/<?php echo number_format($SO_TOT/1000000,2); ?></span>
				                        
				                    	<div class="progress sm">
				                            <div class="progress-bar progress-bar-aqua" style="width: <?php echo $SO_PER; ?>%"></div>
				                        </div>
				                    </div>
				                    <div class="progress-group">
				                        <span class="progress-text">Job Order</span>
				                        <span class="progress-number"><b><?php echo number_format($JO_3_AMN/1000000,2); ?></b>/<?php echo number_format($JO_TOT/1000000,2); ?></span>
				                        
				                        <div class="progress sm">
				                            <div class="progress-bar progress-bar-red" style="width: <?php echo $JO_PER; ?>%"></div>
				                        </div>
				                    </div>
				                    <div class="progress-group">
				                        <span class="progress-text"><?php echo $Production; ?></span>
				                        <span class="progress-number"><b><?php echo number_format($STF_3_AMN/1000000,2); ?></b>/<?php echo number_format($JO_3_AMN/1000000,2); ?></span>
				                        
				                        <div class="progress sm">
				                        	<div class="progress-bar progress-bar-warning" style="width: <?php echo $STF_PER; ?>%"></div>
				                    	</div>
				                    </div>
				                    <div class="progress-group">
				                        <span class="progress-text"><?php echo $Shipment; ?></span>
				                        <span class="progress-number"><b><?php echo number_format($SN_3_AMN/1000000,2); ?></b>/<?php echo number_format($SN_TOT/1000000,2); ?></span>
				                        
				                    	<div class="progress sm">
				                            <div class="progress-bar progress-bar-green" style="width: <?php echo $SN_PER; ?>%"></div>
				                        </div>
				                    </div>
									<div class="box-footer text-left" style="display: none;">
										<a href="javascript:void(0)" style="font-style: italic; font-size: 12px">
											Progres persetujuan dokumen.
										</a>
									</div>
					            </div>
				            </div>
				            <div class="row">
								<div class="col-sm-3">
									<div class="description-block border-right">
										<span id="textSO" class="description-percentage text-primary"><i id="tot_sopicn" class="fa fa-angle-double-up"></i>
											<span id="tot_sop"><?php echo number_format(0,2); ?></span>%
										</span>
										<h5 class="description-header">IDR <span id="tot_sov"><?php echo number_format(0,2); ?></span></h5>
										<span class="description-text"><i class="fa fa-star"></i> TOTAL SALES ORDER</span>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="description-block border-right">
										<span id="textJO" class="description-percentage text-red"><i id="tot_jopicn" class="fa fa-angle-double-up"></i>
											<span id="tot_jop"><?php echo number_format(0,2); ?></span>%
										</span>
										<h5 class="description-header">IDR <span id="tot_jov"><?php echo number_format(0,2); ?></span></h5>
										<span class="description-text"><i class="fa fa-fire"></i> PRODUCTION COST</span>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="description-block border-right">
										<span id="textLR" class="description-percentage text-green"><i id="tot_shipment" class="fa fa-angle-double-up"></i>
											<span id="gross_profp"><?php echo number_format(0,2); ?></span>%
										</span>
										<h5 class="description-header">IDR <span id="gross_prof"><?php echo number_format(0,2); ?></span></h5>
										<span class="description-text"><i class="fa fa-truck"></i> PENGIRIMAN</span>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="description-block border-right">
										<span id="textLR" class="description-percentage text-green"><i id="gross_profpicn" class="fa fa-angle-double-up"></i>
											<span id="gross_profp"><?php echo number_format(0,2); ?></span>%
										</span>
										<h5 class="description-header">IDR <span id="gross_prof"><?php echo number_format(0,2); ?></span></h5>
										<span class="description-text"><i class="fa fa-dollar"></i> GROSS PROFIT</span>
									</div>
								</div>
							</div>
		        		</div>
				    </div>
				<?php } ?>
				<!-- START 	: SALES GRAPH / PROJECT PROGRESS (CONTR.) -->
		        	<?php if($PRJSCATEG == 1) { ?>
				      	<div class="row">
					        <section class="col-lg-12 connectedSortable">
								<div class="nav-tabs-custom">
				                    <div id="chart-proj_progress" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
								</div>
							</section>
				      	</div>
					<?php } ?>
				<!-- END 	: SALES GRAPH / PROJECT PROGRESS (CONTR.) -->
				<!-- START 	: STOCK - SALES / PENGGUNAAN (CONTR.) - WIP / MC PROYEK (CONTR.) - PRODUCTION / BANK RECEIPT (CONTR.) -->
					<?php
						if($PRJSCATEG == 2 && $SSWIP_INFO == 1)
						{
							$STOCK_AMN		= 0;
							$sqlSTOCK		= "SELECT 	IF(ISNULL(SUM(Base_Debet - Base_Kredit)), 0,
													SUM(Base_Debet - Base_Kredit)) AS STOCK_AMN
												FROM tbl_chartaccount WHERE Acc_Group = 'STOCK' AND PRJCODE IN ($myPRJCODE)";
							$resSTOCK		= $this->db->query($sqlSTOCK)->result();
							foreach($resSTOCK as $rowSTOCK):
								$STOCK_AMN 	= $rowSTOCK->STOCK_AMN;
							endforeach;

							$SALES_AMN		= 0;
							/*$sqlSALES		= "SELECT 	IF(ISNULL(SUM(Base_Debet - Base_Kredit)), 0, 
													SUM(Base_Debet - Base_Kredit)) AS SALES_AMN
												FROM tbl_chartaccount WHERE Acc_Group = 'SALES' AND PRJCODE IN ($myPRJCODE)";*/
							$sqlSALES		= "SELECT IF(ISNULL(SUM(SO_COST)), 0, SUM(SO_COST)) AS SALES_AMN
												FROM tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
													AND YEAR(B.SO_DATE) = YEAR(CURDATE()) AND B.SO_STAT = 3";
							$resSALES		= $this->db->query($sqlSALES)->result();
							foreach($resSALES as $rowSALES):
								$SALES_AMN 	= $rowSALES->SALES_AMN;
							endforeach;

							$WIP_AMN		= 0;
							$sqlWIP			= "SELECT 	IF(ISNULL(SUM(Base_Debet - Base_Kredit)), 0,
													SUM(Base_Debet - Base_Kredit)) AS WIP_AMN
												FROM tbl_chartaccount WHERE Acc_Group = 'WIP' AND PRJCODE IN ($myPRJCODE)";
							$resWIP			= $this->db->query($sqlWIP)->result();
							foreach($resWIP as $rowWIP):
								$WIP_AMN 	= $rowWIP->WIP_AMN;
							endforeach;

							$PROD_AMN		= 0;
							$sqlPROD		= "SELECT 	IF(ISNULL(SUM(Base_Debet - Base_Kredit)), 0,
													SUM(Base_Debet - Base_Kredit)) AS PROD_AMN
												FROM tbl_chartaccount WHERE Acc_Group = 'PROD' AND PRJCODE IN ($myPRJCODE)";
							$resPROD		= $this->db->query($sqlPROD)->result();
							foreach($resPROD as $rowPROD):
								$PROD_AMN 	= $rowPROD->PROD_AMN;
							endforeach;
							?>
					      	<div class="row">
						        <section class="col-lg-12 connectedSortable">
						        	<div class="col-md-6 col-sm-6 col-xs-12">
					                    <div class="info-box bg-blue">
					                        <span class="info-box-icon"><i class="ion ion-cube"></i></span>
					                        <div class="info-box-content">
					                            <span class="info-box-text"><?php echo $Supply; ?></span>
					                            <span class="info-box-number"><?php echo number_format($STOCK_AMN); ?></span>
					                            
					                            <div class="progress">
					                                <div class="progress-bar" style="width: 100%"></div>
					                            </div>
					                            <span class="progress-description" style="font-style: italic; font-size: 12px">
					                            	<?php echo $transL6; ?>
					                            </span>
					                        </div>
					                    </div>
					                </div>
					                <div class="col-md-6 col-sm-6 col-xs-12">
					                    <div class="info-box bg-aqua">
					                        <span class="info-box-icon"><i class="ion ion-social-usd-outline"></i></span>
					                        
					                        <div class="info-box-content">
					                            <span class="info-box-text"><?php echo $Sales; ?></span>
					                            <span class="info-box-number"><?php echo number_format($SALES_AMN); ?></span>
					                            
					                            <div class="progress">
					                                <div class="progress-bar" style="width: 100%"></div>
					                            </div>
					                            <span class="progress-description" style="font-style: italic; font-size: 12px">
					                            	<?php echo $transL7; ?>
					                            </span>
					                        </div>
					                    </div>
					                </div>
					                <div class="col-md-6 col-sm-6 col-xs-12">
					                    <div class="info-box bg-yellow">
					                        <span class="info-box-icon"><i class="ion ion-aperture"></i></span>
					                        <div class="info-box-content">
					                            <span class="info-box-text"><?php echo $WIP; ?></span>
					                            <span class="info-box-number"><?php echo number_format($WIP_AMN); ?></span>
					                            
					                            <div class="progress">
					                                <div class="progress-bar" style="width: 100%"></div>
					                            </div>
					                            <span class="progress-description" style="font-style: italic; font-size: 12px">
					                            	<?php echo $transL8; ?>
					                            </span>
					                        </div>
					                    </div>
					                </div>
					                <div class="col-md-6 col-sm-6 col-xs-12">
					                    <div class="info-box bg-green">
					                        <span class="info-box-icon"><i class="glyphicon glyphicon-barcode"></i></span>
					                        <div class="info-box-content">
					                            <span class="info-box-text"><?php echo $Production; ?></span>
					                            <span class="info-box-number"><?php echo number_format($PROD_AMN); ?></span>
					                            
					                            <div class="progress">
					                                <div class="progress-bar" style="width: 100%"></div>
					                            </div>
					                            <span class="progress-description" style="font-style: italic; font-size: 12px">
					                            	<?php echo $transL9; ?>
					                            </span>
					                        </div>
					                    </div>
					                </div>
					            </section>
					        </div>
	            			<?php 
	            		}
	            		else if($PRJSCATEG == 1 && $SSWIP_INFO == 1)
	            		{
							?>
					      	<div class="row">
						        <section class="col-lg-12 connectedSortable">
						        	<div class="col-md-6 col-sm-6 col-xs-12">
					                    <div class="info-box bg-blue">
					                        <span class="info-box-icon"><i class="ion ion-cube"></i></span>
					                        <div class="info-box-content">
					                            <span class="info-box-text"><?php echo $Supply; ?></span>
					                            <span class="info-box-number"><?php echo number_format(0); ?></span>
					                            
					                            <div class="progress">
					                                <div class="progress-bar" style="width: 100%"></div>
					                            </div>
					                            <span class="progress-description" style="font-style: italic; font-size: 12px">
					                            	<?php echo $transL6; ?>
					                            </span>
					                        </div>
					                    </div>
					                </div>
					                <div class="col-md-6 col-sm-6 col-xs-12">
					                    <div class="info-box bg-aqua">
					                        <span class="info-box-icon"><i class="glyphicon glyphicon-refresh"></i></span>
					                        
					                        <div class="info-box-content">
					                            <span class="info-box-text"><?php echo $mtrUsed; ?></span>
					                            <span class="info-box-number"><?php echo number_format(0); ?></span>
					                            
					                            <div class="progress">
					                                <div class="progress-bar" style="width: 100%"></div>
					                            </div>
					                            <span class="progress-description" style="font-style: italic; font-size: 12px">
					                            	<?php echo $transL7; ?>
					                            </span>
					                        </div>
					                    </div>
					                </div>
					                <div class="col-md-6 col-sm-6 col-xs-12" <?php if($MC_PRJ == 0) { ?> style="display: none;" <?php } ?>>
					                    <div class="info-box bg-yellow">
					                        <span class="info-box-icon"><i class="fa fa-certificate"></i></span>
					                        <div class="info-box-content">
					                            <span class="info-box-text"><?php echo $mcprj; ?></span>
					                            <span class="info-box-number"><?php echo number_format(0); ?></span>
					                            
					                            <div class="progress">
					                                <div class="progress-bar" style="width: 100%"></div>
					                            </div>
					                            <span class="progress-description" style="font-style: italic; font-size: 12px">
					                            	<?php echo $transL8; ?>
					                            </span>
					                        </div>
					                    </div>
					                </div>
					                <div class="col-md-6 col-sm-6 col-xs-12" <?php if($BR_INFO == 0) { ?> style="display: none;" <?php } ?>>
					                    <div class="info-box bg-green">
					                        <span class="info-box-icon"><i class="ion ion-social-usd-outline"></i></span>
					                        <div class="info-box-content">
					                            <span class="info-box-text"><?php echo $InvoiceRealization; ?></span>
					                            <span class="info-box-number"><?php echo number_format(0); ?></span>
					                            
					                            <div class="progress">
					                                <div class="progress-bar" style="width: 100%"></div>
					                            </div>
					                            <span class="progress-description" style="font-style: italic; font-size: 12px">
					                            	<?php echo $transL9; ?>
					                            </span>
					                        </div>
					                    </div>
					                </div>
					            </section>
					        </div>
	            			<?php 
	            		}
	            	?>
				<!-- END 	: STOCK - SALES / PENGGUNAAN (CONTR.) - WIP / MC PROYEK (CONTR.) - PRODUCTION / BANK RECEIPT (CONTR.) -->

	      	<!-- START 	: GENERAL INFORMATION -->
      			<div class="row">
      					<div class="col-lg-3 col-xs-4">
							<div class="box box-danger direct-chat direct-chat-warning">
								<div class="box-header with-border">
									<h3 class="box-title"><?php echo $transL11; ?></h3>

									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
										</button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
										</button>
									</div>
								</div>

								<div class="box-body">
									<div class="direct-chat-messages">
										<?php
											$sqlNEws	= "SELECT A.NEWSD_CREATER, DATE_FORMAT(A.NEWSD_CREATED,'%d %M') AS NEWS_D, 
																DATE_FORMAT(A.NEWSD_CREATED,'%H:%i') AS NEWS_T,
																CONCAT(B.First_Name, ' ', B.Last_Name) AS compName, 
																A.NEWSD_TITLE, A.NEWSD_CONTENT
															FROM tbl_news_detail A 
															INNER JOIN tbl_employee B ON A.NEWSD_CREATER = B.Emp_ID
																ORDER BY A.NEWSD_CREATED DESC LIMIT 10;";
											$resNEws	= $this->db->query($sqlNEws)->result();
											foreach($resNEws as $rowNEws):
												$NEWS_EMP	= $rowNEws->NEWSD_CREATER;
												$NEWS_D		= $rowNEws->NEWS_D;
												$NEWS_T		= $rowNEws->NEWS_T;
												$NEWS_NM	= $rowNEws->compName;
												$NEWS_TITLE	= $rowNEws->NEWSD_TITLE;
												$NEWS_CONT	= $rowNEws->NEWSD_CONTENT;

												if($NEWS_CONT == '') $NEWS_CONT = $NEWS_TITLE;

												$sqlIMG1	= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img
																	WHERE imgemp_empid = '$Emp_ID'";
												$resIMG1 = $this->db->query($sqlIMG1)->result();
												foreach($resIMG1 as $rowIMG1) :
													$filenamex1 	= $rowIMG1->imgemp_filename;
													$filenamex1 	= $rowIMG1->imgemp_filenameX;
												endforeach;

												$newsIMG	= base_url('assets/AdminLTE-2.0.5/emp_image/'.$NEWS_EMP.'/'.$filenamex1);
												if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$NEWS_EMP.'/'.$filenamex1))
												{
													$newsIMG	= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
												}
												?>
													<div class="direct-chat-msg">
														<div class="direct-chat-info clearfix">
															<span class="direct-chat-name pull-left"><?php echo $NEWS_NM; ?></span>
															<span class="direct-chat-timestamp pull-right"><?php echo "$NEWS_D $NEWS_T"; ?></span>
														</div>
														<img class="direct-chat-img" src="<?php echo $newsIMG; ?>" alt="message user image">
														<div class="direct-chat-text">
															<?php echo $NEWS_CONT; ?>
														</div>
													</div>
												<?php
											endforeach;
										?>
									</div>
								</div>
								<div class="box-footer text-center" style="display: none;">
									<a href="javascript:void(0)" class="uppercase">View All Info</a>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-xs-4">
				          	<div class="box box-solid bg-teal-gradient">
					            <div class="box-header">
			                    	<i class="fa fa-th"></i>
			                        <h3 class="box-title"><?php echo $transL5; ?></h3>
			                    
			                        <div class="box-tools pull-right">
			                            <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
			                            </button>
			                            <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>
			                            </button>
			                        </div>
			                    </div>
				                <div class="box-body border-radius-none">
				                    <div class="chart" id="line-chart2" style="height: 250px;"></div>
				                </div>
								<div class="box-footer no-border" style="display: none;">
				                    <div class="row">
				                       	<div class="col-md-4 text-center" style="border-right: 1px solid #f4f4f4">
				                            <input type="text" class="knob" data-readonly="true" value="70" data-width="60" data-height="60" data-fgColor="#00BEEE">
				                            <div class="knob-label">Aktif</div>
				                        </div>
				                        <div class="col-md-4 text-center" style="border-right: 1px solid #f4f4f4">
				                            <input type="text" class="knob" data-readonly="true" value="80" data-width="60" data-height="60" data-fgColor="#00A65B">
				                            <div class="knob-label">Online</div>
				                        </div>
				                        <div class="col-md-4 text-center">
				                            <input type="text" class="knob" data-readonly="true" value="90" data-width="60" data-height="60" data-fgColor="#F39F12">
				                            <div class="knob-label">Offline</div>
				                        </div>
				                    </div>
				                </div>
				            </div>
				        </div>
				        <!-- START : LATEST LOGIN -->
							<div class="col-lg-3 col-xs-4">
				          		<div class="box box-solid bg-blue-gradient">
									<div class="box-header">
				                    	<i class="fa fa-history"></i>
				                        <h3 class="box-title"><?php echo $transL12; ?></h3>
				                    
				                        <div class="box-tools pull-right">
				                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
											</button>
											<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
											</button>
				                        </div>
				                    </div>

									<div class="box-footer no-border">
					                    <div class="row">
					                    	<div class="box-body">
												<ul class="products-list product-list-in-box">
													<?php
														$sqlLLog	= "SELECT DISTINCT A.LOG_EMP, DATE_FORMAT(A.LOG_IND,'%d %M') AS LOG_D,
																			DATE_FORMAT(A.LOG_IND,'%H:%i') AS LOG_T,
																			CONCAT(B.First_Name,' ', B.Last_Name) AS compName, B.Pos_Code
																		FROM tbl_login_hist A INNER JOIN tbl_employee B
																			ON A.LOG_EMP = B.Emp_ID
																		ORDER BY LOG_IND DESC LIMIT 4";
														$resLLog	= $this->db->query($sqlLLog)->result();
														foreach($resLLog as $rowLLog):
															$EMPID	= $rowLLog->LOG_EMP;
															$LOG_D	= $rowLLog->LOG_D;
															$LOG_T	= $rowLLog->LOG_T;
															$EMPNM	= $rowLLog->compName;
															$POSCD	= $rowLLog->Pos_Code;

															$filenamex 	= "username.jpg";
															$sqlIMG	= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img
																				WHERE imgemp_empid = '$Emp_ID'";
															$resIMG = $this->db->query($sqlIMG)->result();
															foreach($resIMG as $rowIMG) :
																$filenamex 	= $rowIMG->imgemp_filename;
																$filenamex 	= $rowIMG->imgemp_filenameX;
															endforeach;

															if($POSCD == '')
															{
																$POSNM 	= "-";
															}
															else
															{
																$POSNM  = "-";
																$sqlDEP = "SELECT POSS_NAME FROM tbl_position_str WHERE POSS_CODE = '$POSCD'";
																$resDEP = $this->db->query($sqlDEP)->result();
																foreach($resDEP as $rowDEP) :
																	$POSNM 	= $rowDEP->POSS_NAME;
																endforeach;
															}

															$empIMG	= base_url('assets/AdminLTE-2.0.5/emp_image/'.$EMPID.'/'.$filenamex);
															if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$EMPID.'/'.$filenamex))
															{
																$empIMG	= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
															}
															?>
																<li class="item">
																	<div class="product-img">
																		<img src="<?php echo $empIMG; ?>" alt="Product Image">
																	</div>
																	<div class="product-info">
																		<a href="javascript:void(0)" class="product-title"><?php echo $EMPNM; ?>
																		<span class="label label-warning pull-right"><?php echo "$LOG_D $LOG_T"; ?></span></a>
																		<span class="product-description" style="font-size: 12px">
																			<?php echo "$EMPID<br>Bagian : $POSNM"; ?>
																		</span>
																	</div>
																</li>
															<?php
														endforeach;
													?>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
				        <!-- END : LATEST LOGIN -->
				    </section>
            	</div>
            <!-- END 	: GENERAL INFORMATION -->
		</section>
	</body>
</html>

<script type="text/javascript">
	$(document).ready(function(){
		$(".preloader").fadeOut();
		var hasSv = $('#hasSv').val();
		//ar isEnd = $('#isEnd').val();
		if(hasSv == 0)
		{
			swal({
				title: "<?php echo $transL01; ?>",
				text: "<?php echo $transL02; ?>",
				icon: "success",
				//buttons: true,
				buttons: ["<?php echo $transL06; ?>", "<?php echo $transL05; ?>"],
				dangerMode: true,
			})
			.then((willDelete) => 
			{
				if (willDelete) 
				{
					var EMPID	= document.getElementById('EMPID').value;
					$.ajax({
                        type: 'POST',
                        url: '<?php echo $logURL; ?>',
                        data: "EMPID="+EMPID,
                        success: function(msg)
                        {
                            //
                        }
                    });
					swal("<?php echo $transL03; ?>", 
					{
						icon: "warning",
					});
				} 
				else 
				{
					swal("<?php echo $transL04; ?>", 
					{
						icon: "warning",
					});
				}
				versNotif0();
				versNotif1();
			});
		}

		/*var isEnd = $('#isEnd').val();
		if(isEnd == 1)
		{
			mntAlert1();
		}
		else if(isEnd == 2)
		{
			mntAlert1();
		}*/
	});

	function versNotif0()
	{
		title 	= "Template / Skins";
		message = "<?php echo $transL07; ?>";

		window.createNotification
		({
			closeOnClick: true,
			displayCloseButton: true,
			positionClass: 'nfc-bottom-right',
			showDuration: 600000, // 1K = 1s
			theme: 'warning' // success, warning, info, error, None
		})
		({
			title: title,
			message: message
		});
	}

	function versNotif1()
	{
		title 	= "<?php echo $transL08; ?>";
		message = "<?php echo $transL09; ?>";

		window.createNotification
		({
			closeOnClick: true,
			displayCloseButton: true,
			positionClass: 'nfc-bottom-right',
			showDuration: 600000, // 1K = 1s
			theme: 'success' // success, warning, info, error, None
		})
		({
			title: title,
			message: message
		});
	}

	/*function mntAlert1()
	{
		title 	= "<?php echo $mntWarn1; ?>";
		message = "<?php echo $mntWarn2; ?>";

		window.createNotification
		({
			closeOnClick: false,
			displayCloseButton: false,
			positionClass: 'nfc-bottom-right',
			showDuration: 6000000, // 1K = 1s
			theme: 'error' // success, warning, info, error, None
		})
		({
			title: title,
			message: message
		});
	}*/

	function chooseProject(thisVal)
	{
		sssss	= thisVal.value;
		projCode = document.getElementById('selPRJCODE').value;
		document.frmsrch.submitPRJ.click();
	}

	$(function () {
	$(".select2").select2();
	});

    $(function() {
        "use strict";

	// AREA CHART
		<?php if($PRJSCATEG == 2 && $GRF_SALES == 999999) { ?>
			var area = new Morris.Area({
				element: 'revenue-chart1',
				resize: true,
				data: [
					<?php
						/*$sqlSOMNTH	= "SELECT DISTINCT MONTH (B.SO_DATE) AS M_SO, YEAR (B.SO_DATE) AS Y_SO
										FROM
											tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM AND B.SO_STAT IN (2,3)";*/
						$sqlC 		= "tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM AND B.SO_STAT IN (2,3)";
						$resC 		= $this->db->count_all($sqlC);
						if($resC > 0)
						{
							$sqlSOMNTH	= "SELECT DISTINCT YEAR (B.SO_DATE) AS Y_SO, QUARTER(B.SO_DATE) AS QUART
											FROM
												tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM AND B.SO_STAT IN (2,3)
											ORDER BY YEAR(B.SO_DATE) DESC LIMIT 3";
							$resSOMNTH	= $this->db->query($sqlSOMNTH)->result();
							foreach($resSOMNTH as $rowSOMNTH):
								$Y_SO 		= $rowSOMNTH->Y_SO;
								$QUART 		= $rowSOMNTH->QUART;


								$M_SO2		= 0;
								$sqlSOQTY2	= "SELECT IF(ISNULL(SUM(SO_VOLM)), 0, SUM(SO_VOLM)) AS SOQTY
												FROM tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
													AND QUARTER(B.SO_DATE) = $QUART AND YEAR(B.SO_DATE) = $Y_SO AND B.SO_STAT = 2";
								$resSOQTY2	= $this->db->query($sqlSOQTY2)->result();
								foreach($resSOQTY2 as $rowSOQTY2):
									$M_SO2 	= $rowSOQTY2->SOQTY;
								endforeach;

								$M_SO3		= 0;
								$sqlSOQTY3	= "SELECT IF(ISNULL(SUM(SO_VOLM)), 0, SUM(SO_VOLM)) AS SOQTY
												FROM tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
													AND QUARTER(B.SO_DATE) = $QUART AND YEAR(B.SO_DATE) = $Y_SO AND B.SO_STAT = 3";
								$resSOQTY3	= $this->db->query($sqlSOQTY3)->result();
								foreach($resSOQTY3 as $rowSOQTY3):
									$M_SO3 	= $rowSOQTY3->SOQTY;
								endforeach;

								$QRT 	= "Q".$QUART;
								$year1 	= "'$Y_SO $QRT'";
								?>
									{y: <?php echo $year1; ?>, so2qty: <?php echo $M_SO2; ?>, so3qty: <?php echo $M_SO3; ?>},
								<?php
							endforeach;
						}
						else
						{
							$Y_SO 	= date('Y');
							$QRT 	= "Q1";
							$year1 	= "'$Y_SO $QRT'";
							$M_SO2 	= 0;
							$M_SO3 	= 0;
							?>
								{y: <?php echo $year1; ?>, so2qty: <?php echo $M_SO2; ?>, so3qty: <?php echo $M_SO3; ?>},
							<?php
						}
					?>
					/*{y: '2011 Q1', so2qty: 3750, so3qty: 3750},
					{y: '2011 Q2', so2qty: 2778, so3qty: 2294},
					{y: '2011 Q3', so2qty: 4912, so3qty: 1969},
					{y: '2011 Q4', so2qty: 3767, so3qty: 3597},
					{y: '2012 Q1', so2qty: 6810, so3qty: 1914},
					{y: '2012 Q2', so2qty: 5670, so3qty: 4293},
					{y: '2012 Q3', so2qty: 4820, so3qty: 3795},
					{y: '2012 Q4', so2qty: 15073, so3qty: 5967},
					{y: '2013 Q1', so2qty: 10687, so3qty: 4460},
					{y: '2013 Q2', so2qty: 8432, so3qty: 5713},*/
				],
				xkey: 'y',
				ykeys: ['so2qty', 'so3qty'],
				labels: ['Confirmed', 'Approved'],
				lineColors: ['#a0d0e0', '#3c8dbc'],
				hideHover: 'auto'
			});
		<?php } ?>

		<?php if($PRJSCATEG == 1) { ?>
			Highcharts.chart('chart-proj_progress', {
				chart: {
					type: 'line'
				},
				title: {
					text: 'Project Progress : <?php echo $PRJNAMEX; ?>'
				},
				subtitle: {
					//text: 'Source: WorldClimate.com'
				},
				xAxis: {
					title: {
						text: 'Minggu ke-'
					},
					categories: [
					<?php
						$sqlProgC	= "tbl_projprogres WHERE proj_Code IN ($myPRJCODEX) AND progress_Type = $GraphicTitleText AND isShow = 1";
						$resProgC	= $this->db->count_all($sqlProgC);
						if($resProgC <= 20)
						{
							$pmbg	= 1;
						}
						else
						{
							$pmbg	= $resProgC / 20;
						}
						$ADDR	= 0;
						for($PC = 1; $PC <= $resProgC; $PC++)
						{
							$ADDRX	= $ADDR + $PC;
							echo "'$ADDRX',";
						}
					?>
					]
				},
				yAxis: {
					title: {
						text: 'Progress/Prosentasi (%)'
					}
				},
				plotOptions: {
					line: {
						dataLabels: {
							enabled: true
						},
						enableMouseTracking: false
					}
				},
				series: [{
					name: 'Plan',
					color: "#00F",
					data: [<?php
								$jumTotPlanAkuma 	= '';
								$GraphicTitleText	= 3;
								$sqla   	= "SELECT A.proj_Code, B.PRJNAME
												FROM tbl_projprogres A
												INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
												WHERE A.proj_Code IN ($myPRJCODEX) AND progress_Type = $GraphicTitleText
												GROUP BY proj_Code";
								$ressqla 	= $this->db->query($sqla)->result();
								
								foreach($ressqla as $rowa) :
									$proj_Code 	= $rowa->proj_Code;
									$projName 	= $rowa->PRJNAME;
									$proj_Name	= "Project : $proj_Code";
									
									$NoU 		= 0;
									$sql0   	= "SELECT MAX(day(Prg_Date1)) AS myDay, MAX(MONTH(Prg_Date1)) AS myMonth, 
														MAX(YEAR(Prg_Date1)) AS myYear, MAX(Prg_Date1) AS myDate,
														MAX(Prg_PlanAkum) AS Prg_PlanAkum, MAX(Prg_RealAkum) AS Prg_RealAkum, isShow, 
														isShowRA, isShowDev
													FROM tbl_projprogres WHERE proj_Code IN ($myPRJCODEX)
														AND progress_Type = $GraphicTitleText AND isShow = 1 
													GROUP BY day(Prg_Date1), month(Prg_Date1), year(Prg_Date1) ORDER BY Prg_Date1";
									$ressql0 	= $this->db->query($sql0)->result();
									foreach($ressql0 as $row0) :
										$NoU 			= $NoU + 1;
										$DayAx 			= $row0->myDay;
										$myDate			= $row0->myDate;
										$Prg_PlanAkum	= $row0->Prg_PlanAkum;
										$Prg_RealAkum	= $row0->Prg_RealAkum;
										$Prg_Dev2 		= $Prg_PlanAkum - $Prg_RealAkum;
										$isShow			= $row0->isShow;
										$isShowRA		= $row0->isShowRA;
										$isShowDev		= $row0->isShowDev;
										if($isShow == 1)
										{
											echo "$Prg_PlanAkum,";
										}
									endforeach;
								endforeach;
							?>]
				}, {
					name: 'Real',
					color: "#390",
					data: [<?php
								$jumTotPlanAkuma 	= '';
								$GraphicTitleText	= 3;
								$sqla   	= "SELECT A.proj_Code, B.PRJNAME
												FROM tbl_projprogres A
												INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
												WHERE A.proj_Code IN ($myPRJCODEX) AND progress_Type = $GraphicTitleText
												GROUP BY proj_Code";
								$ressqla 	= $this->db->query($sqla)->result();
								
								foreach($ressqla as $rowa) :
									$proj_Code 	= $rowa->proj_Code;
									$projName 	= $rowa->PRJNAME;
									$proj_Name	= "Project : $proj_Code";
									
									$NoU 		= 0;
									$sql0   	= "SELECT MAX(day(Prg_Date1)) AS myDay, MAX(MONTH(Prg_Date1)) AS myMonth, 
														MAX(YEAR(Prg_Date1)) AS myYear, MAX(Prg_Date1) AS myDate,
														MAX(Prg_PlanAkum) AS Prg_PlanAkum, MAX(Prg_RealAkum) AS Prg_RealAkum, isShow, 
														isShowRA, isShowDev
													FROM tbl_projprogres WHERE proj_Code IN ($myPRJCODEX)
														AND progress_Type = $GraphicTitleText AND isShow = 1 
													GROUP BY day(Prg_Date1), month(Prg_Date1), year(Prg_Date1) ORDER BY Prg_Date1";
									$ressql0 	= $this->db->query($sql0)->result();
									foreach($ressql0 as $row0) :
										$NoU 			= $NoU + 1;
										$DayAx 			= $row0->myDay;
										$myDate			= $row0->myDate;
										$Prg_PlanAkum	= $row0->Prg_PlanAkum;
										$Prg_RealAkum	= $row0->Prg_RealAkum;
										$Prg_Dev2 		= $Prg_PlanAkum - $Prg_RealAkum;
										$isShow			= $row0->isShow;
										$isShowRA		= $row0->isShowRA;
										$isShowDev		= $row0->isShowDev;
										if($isShowRA == 1)
										{
											echo "$Prg_RealAkum,";
										}
									endforeach;
								endforeach;
							?>]
				}]
			});
		<?php } ?>

		<?php //if($GRF_LOGHIST == 1) { ?>
			var data = [];
			/*
			 * LINE CHART
			 * ----------
			 */
			//LINE randomly generated data
			var arr1 	= [];
			var arr1a	= [];
			var arr1b	= [];
			<?php
				$sqlTL   			= "SELECT DAY(TTR_DATE) AS THEDAY, MONTH (TTR_DATE) AS THEMONTH,
											COUNT(DATE(TTR_DATE)) AS TOTLOG
										FROM tbl_trail_tracker
										WHERE
											TTR_DATE >= '$WEEK_BEF' AND TTR_DATE <= '$WEEK_NEXT'
										GROUP BY
											DATE(TTR_DATE)
										ORDER BY
											YEAR(TTR_DATE), MONTH(TTR_DATE), DATE(TTR_DATE) ASC";
				$resTL 				= $this->db->query($sqlTL)->result();
				$therow1			= 0;
				foreach($resTL as $rowTL) :
					$therow1	= $therow1 + 1;
					$THEDAY 	= $rowTL->THEDAY;
					$len 		= strlen($THEDAY);
					if($len==1) 
						$nol	= "0";
					else
						$nol	= "";
					$THEDAY1 	= $nol.$THEDAY;
					//echo "THEDAY1 = $THEDAY1 === ";
						
					$THEMONTH 	= $rowTL->THEMONTH;
					$len1 		= strlen($THEMONTH);
					if($len1==1) 
						$nol1	= "0";
					else
						$nol1	= "";
					$THEMONTH1 	= $nol1.$THEMONTH;
					
					//echo "THEMONTH1 = $THEMONTH1<BR>";
					$THEMD		= "$THEMONTH1$THEDAY1";
					$therow2	= "$therow1.$THEMD";
					$therow		= $therow2;
					$TOTLOG 	= $rowTL->TOTLOG;
					
					?>
						arr1a.push([<?php echo $therow; ?>]);
						arr1b.push([<?php echo $THEDAY; ?>]);
						arr1.push([<?php echo $therow; ?>, <?php echo $TOTLOG; ?>]);
					<?php
				endforeach;
			?>
			var line_data1 = {
				label: "Log Act",	
				data: arr1,					
				color: "#3c8dbc"
			};
			/*var line_data2 = {
				label: "Real",	
				data: arr2,
				color: "#00c0ef"
			};
			var line_data3 = {
				label: "Deviation",	
				data: arr3,
				color: "#f56954"
			};*/
			
			$.plot("#line-chart2", [line_data1], {
				grid: {
					hoverable: true,
					borderColor: "#f3f3f3",
					borderWidth: 1,
					tickColor: "#f3f3f3"
				},
				series: {
					shadowSize: 0,
					lines: {
						show: true
					},
					points: {
						show: true
					}
				},
				lines: {
					fill: true,
					color: ["#3c8dbc", "#f56954"]
				},
				yaxis: {
					show: true,
				},
				xaxis: {
					show: true
				}
			});
			//Initialize tooltip on hover
			$("<div class='tooltip-inner' id='line-chart-tooltip'></div>").css({
				position: "absolute",
				display: "none",
				//opacity: 0.8
			}).appendTo("body");
			$("#line-chart2").bind("plothover", function(event, pos, item) {
	
				if (item) {
					var x = item.datapoint[0].toFixed(4),
						y = item.datapoint[1].toFixed(2);
	
					$("#line-chart-tooltip").html(item.series.label + " of " + x + " = " + y)
							.css({top: item.pageY + 10, left: item.pageX + 10})
							.fadeIn(200);
				} else {
					$("#line-chart-tooltip").hide();
				}
	
			});
		<?php //} ?>


		<?php if($GRF_PURCH == 1 || $GRF_SALES == 1) {
			if($PRJSCATEG == 1)
				$ADDQRY 	= "PRJCODE IN ($myPRJCODE) AND";
			else
				$ADDQRY 	= "";

		    $nMonth = date('m');
		    $tBef 	= date('Y-m-t', strtotime('-1 month', strtotime($tNow)));
		    $tBef2 	= date('m', strtotime($tBef));
		    $varMB	= "T".$tBef2."_SO";
		    $varM	= "T".$nMonth."_SO";
			$tYear	= date('Y');
			$sql_01 = "SELECT SUM(DOC_PRV) AS TOT_PR, SUM(DOC_POV) AS TOT_PO, SUM(DOC_IRV) AS TOT_IR, SUM(DOC_UMV) AS TOT_UM,
							SUM(DOC_SOV) AS TOT_SO, SUM(DOC_JOV) AS TOT_JO, SUM(DOC_PRODV) AS TOT_PRD
						FROM tbl_doc_concl WHERE $ADDQRY YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '01'";
		    $res_01 = $this->db->query($sql_01)->result();
		    foreach($res_01 as $row_01) :
		        $T01_PR  = $row_01->TOT_PR / 1000000;
		        $T01_PO  = $row_01->TOT_PO / 1000000;
		        $T01_IR  = $row_01->TOT_IR / 1000000;
		        $T01_UM  = $row_01->TOT_UM / 1000000;
		        $T01_SO  = $row_01->TOT_SO / 1000000;
		        $T01_JO  = $row_01->TOT_JO / 1000000;
		        $T01_PRD = $row_01->TOT_PRD / 1000000;
		    endforeach;
		    if($T01_PR == '') $T01_PR = 0;
		    if($T01_PO == '') $T01_PO = 0;
		    if($T01_IR == '') $T01_IR = 0;
		    if($T01_UM == '') $T01_UM = 0;
		    if($T01_SO == '') $T01_SO = 0;
		    if($T01_JO == '') $T01_JO = 0;
		    if($T01_PRD == '') $T01_PRD = 0;

			$sql_02 = "SELECT SUM(DOC_PRV) AS TOT_PR, SUM(DOC_POV) AS TOT_PO, SUM(DOC_IRV) AS TOT_IR, SUM(DOC_UMV) AS TOT_UM,
							SUM(DOC_SOV) AS TOT_SO, SUM(DOC_JOV) AS TOT_JO, SUM(DOC_PRODV) AS TOT_PRD
						FROM tbl_doc_concl WHERE $ADDQRY YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '02'";
		    $res_02 = $this->db->query($sql_02)->result();
		    foreach($res_02 as $row_02) :
		        $T02_PR  = $row_02->TOT_PR / 1000000;
		        $T02_PO  = $row_02->TOT_PO / 1000000;
		        $T02_IR  = $row_02->TOT_IR / 1000000;
		        $T02_UM  = $row_02->TOT_UM / 1000000;
		        $T02_SO  = $row_02->TOT_SO / 1000000;
		        $T02_JO  = $row_02->TOT_JO / 1000000;
		        $T02_PRD = $row_02->TOT_PRD / 1000000;
		    endforeach;
		    if($T02_PR == '') $T02_PR = 0;
		    if($T02_PO == '') $T02_PO = 0;
		    if($T02_IR == '') $T02_IR = 0;
		    if($T02_UM == '') $T02_UM = 0;
		    if($T02_SO == '') $T02_SO = 0;
		    if($T02_JO == '') $T02_JO = 0;
		    if($T02_PRD == '') $T02_PRD = 0;

			$sql_03 = "SELECT SUM(DOC_PRV) AS TOT_PR, SUM(DOC_POV) AS TOT_PO, SUM(DOC_IRV) AS TOT_IR, SUM(DOC_UMV) AS TOT_UM,
							SUM(DOC_SOV) AS TOT_SO, SUM(DOC_JOV) AS TOT_JO, SUM(DOC_PRODV) AS TOT_PRD
						FROM tbl_doc_concl WHERE $ADDQRY YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '03'";
		    $res_03 = $this->db->query($sql_03)->result();
		    foreach($res_03 as $row_03) :
		        $T03_PR  = $row_03->TOT_PR / 1000000;
		        $T03_PO  = $row_03->TOT_PO / 1000000;
		        $T03_IR  = $row_03->TOT_IR / 1000000;
		        $T03_UM  = $row_03->TOT_UM / 1000000;
		        $T03_SO  = $row_03->TOT_SO / 1000000;
		        $T03_JO  = $row_03->TOT_JO / 1000000;
		        $T03_PRD = $row_03->TOT_PRD / 1000000;
		    endforeach;
		    if($T03_PR == '') $T03_PR = 0;
		    if($T03_PO == '') $T03_PO = 0;
		    if($T03_IR == '') $T03_IR = 0;
		    if($T03_UM == '') $T03_UM = 0;
		    if($T03_SO == '') $T03_SO = 0;
		    if($T03_JO == '') $T03_JO = 0;
		    if($T03_PRD == '') $T03_PRD = 0;

			$sql_04 = "SELECT SUM(DOC_PRV) AS TOT_PR, SUM(DOC_POV) AS TOT_PO, SUM(DOC_IRV) AS TOT_IR, SUM(DOC_UMV) AS TOT_UM,
							SUM(DOC_SOV) AS TOT_SO, SUM(DOC_JOV) AS TOT_JO, SUM(DOC_PRODV) AS TOT_PRD
						FROM tbl_doc_concl WHERE $ADDQRY YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '04'";
		    $res_04 = $this->db->query($sql_04)->result();
		    foreach($res_04 as $row_04) :
		        $T04_PR  = $row_04->TOT_PR / 1000000;
		        $T04_PO  = $row_04->TOT_PO / 1000000;
		        $T04_IR  = $row_04->TOT_IR / 1000000;
		        $T04_UM  = $row_04->TOT_UM / 1000000;
		        $T04_SO  = $row_04->TOT_SO / 1000000;
		        $T04_JO  = $row_04->TOT_JO / 1000000;
		        $T04_PRD = $row_04->TOT_PRD / 1000000;
		    endforeach;
		    if($T04_PR == '') $T04_PR = 0;
		    if($T04_PO == '') $T04_PO = 0;
		    if($T04_IR == '') $T04_IR = 0;
		    if($T04_UM == '') $T04_UM = 0;
		    if($T04_SO == '') $T04_SO = 0;
		    if($T04_JO == '') $T04_JO = 0;
		    if($T04_PRD == '') $T04_PRD = 0;

			$sql_05 = "SELECT SUM(DOC_PRV) AS TOT_PR, SUM(DOC_POV) AS TOT_PO, SUM(DOC_IRV) AS TOT_IR, SUM(DOC_UMV) AS TOT_UM,
							SUM(DOC_SOV) AS TOT_SO, SUM(DOC_JOV) AS TOT_JO, SUM(DOC_PRODV) AS TOT_PRD
						FROM tbl_doc_concl WHERE $ADDQRY YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '05'";
		    $res_05 = $this->db->query($sql_05)->result();
		    foreach($res_05 as $row_05) :
		        $T05_PR  = $row_05->TOT_PR / 1000000;
		        $T05_PO  = $row_05->TOT_PO / 1000000;
		        $T05_IR  = $row_05->TOT_IR / 1000000;
		        $T05_UM  = $row_05->TOT_UM / 1000000;
		        $T05_SO  = $row_05->TOT_SO / 1000000;
		        $T05_JO  = $row_05->TOT_JO / 1000000;
		        $T05_PRD = $row_05->TOT_PRD / 1000000;
		    endforeach;
		    if($T05_PR == '') $T05_PR = 0;
		    if($T05_PO == '') $T05_PO = 0;
		    if($T05_IR == '') $T05_IR = 0;
		    if($T05_UM == '') $T05_UM = 0;
		    if($T05_SO == '') $T05_SO = 0;
		    if($T05_JO == '') $T05_JO = 0;
		    if($T05_PRD == '') $T05_PRD = 0;

			$sql_06 = "SELECT SUM(DOC_PRV) AS TOT_PR, SUM(DOC_POV) AS TOT_PO, SUM(DOC_IRV) AS TOT_IR, SUM(DOC_UMV) AS TOT_UM,
							SUM(DOC_SOV) AS TOT_SO, SUM(DOC_JOV) AS TOT_JO, SUM(DOC_PRODV) AS TOT_PRD
						FROM tbl_doc_concl WHERE $ADDQRY YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '06'";
		    $res_06 = $this->db->query($sql_06)->result();
		    foreach($res_06 as $row_06) :
		        $T06_PR  = $row_06->TOT_PR / 1000000;
		        $T06_PO  = $row_06->TOT_PO / 1000000;
		        $T06_IR  = $row_06->TOT_IR / 1000000;
		        $T06_UM  = $row_06->TOT_UM / 1000000;
		        $T06_SO  = $row_06->TOT_SO / 1000000;
		        $T06_JO  = $row_06->TOT_JO / 1000000;
		        $T06_PRD = $row_06->TOT_PRD / 1000000;
		    endforeach;
		    if($T06_PR == '') $T06_PR = 0;
		    if($T06_PO == '') $T06_PO = 0;
		    if($T06_IR == '') $T06_IR = 0;
		    if($T06_UM == '') $T06_UM = 0;
		    if($T06_SO == '') $T06_SO = 0;
		    if($T06_JO == '') $T06_JO = 0;
		    if($T06_PRD == '') $T06_PRD = 0;

			$sql_07 = "SELECT SUM(DOC_PRV) AS TOT_PR, SUM(DOC_POV) AS TOT_PO, SUM(DOC_IRV) AS TOT_IR, SUM(DOC_UMV) AS TOT_UM,
							SUM(DOC_SOV) AS TOT_SO, SUM(DOC_JOV) AS TOT_JO, SUM(DOC_PRODV) AS TOT_PRD
						FROM tbl_doc_concl WHERE $ADDQRY YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '07'";
		    $res_07 = $this->db->query($sql_07)->result();
		    foreach($res_07 as $row_07) :
		        $T07_PR  = $row_07->TOT_PR / 1000000;
		        $T07_PO  = $row_07->TOT_PO / 1000000;
		        $T07_IR  = $row_07->TOT_IR / 1000000;
		        $T07_UM  = $row_07->TOT_UM / 1000000;
		        $T07_SO  = $row_07->TOT_SO / 1000000;
		        $T07_JO  = $row_07->TOT_JO / 1000000;
		        $T07_PRD = $row_07->TOT_PRD / 1000000;
		    endforeach;
		    if($T07_PR == '') $T07_PR = 0;
		    if($T07_PO == '') $T07_PO = 0;
		    if($T07_IR == '') $T07_IR = 0;
		    if($T07_UM == '') $T07_UM = 0;
		    if($T07_SO == '') $T07_SO = 0;
		    if($T07_JO == '') $T07_JO = 0;
		    if($T07_PRD == '') $T07_PRD = 0;

			$sql_08 = "SELECT SUM(DOC_PRV) AS TOT_PR, SUM(DOC_POV) AS TOT_PO, SUM(DOC_IRV) AS TOT_IR, SUM(DOC_UMV) AS TOT_UM,
							SUM(DOC_SOV) AS TOT_SO, SUM(DOC_JOV) AS TOT_JO, SUM(DOC_PRODV) AS TOT_PRD
						FROM tbl_doc_concl WHERE $ADDQRY YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '08'";
		    $res_08 = $this->db->query($sql_08)->result();
		    foreach($res_08 as $row_08) :
		        $T08_PR  = $row_08->TOT_PR / 1000000;
		        $T08_PO  = $row_08->TOT_PO / 1000000;
		        $T08_IR  = $row_08->TOT_IR / 1000000;
		        $T08_UM  = $row_08->TOT_UM / 1000000;
		        $T08_SO  = $row_08->TOT_SO / 1000000;
		        $T08_JO  = $row_08->TOT_JO / 1000000;
		        $T08_PRD = $row_08->TOT_PRD / 1000000;
		    endforeach;
		    if($T08_PR == '') $T08_PR = 0;
		    if($T08_PO == '') $T08_PO = 0;
		    if($T08_IR == '') $T08_IR = 0;
		    if($T08_UM == '') $T08_UM = 0;
		    if($T08_SO == '') $T08_SO = 0;
		    if($T08_JO == '') $T08_JO = 0;
		    if($T08_PRD == '') $T08_PRD = 0;

			$sql_09 = "SELECT SUM(DOC_PRV) AS TOT_PR, SUM(DOC_POV) AS TOT_PO, SUM(DOC_IRV) AS TOT_IR, SUM(DOC_UMV) AS TOT_UM,
							SUM(DOC_SOV) AS TOT_SO, SUM(DOC_JOV) AS TOT_JO, SUM(DOC_PRODV) AS TOT_PRD
						FROM tbl_doc_concl WHERE $ADDQRY YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '09'";
		    $res_09 = $this->db->query($sql_09)->result();
		    foreach($res_09 as $row_09) :
		        $T09_PR  = $row_09->TOT_PR / 1000000;
		        $T09_PO  = $row_09->TOT_PO / 1000000;
		        $T09_IR  = $row_09->TOT_IR / 1000000;
		        $T09_UM  = $row_09->TOT_UM / 1000000;
		        $T09_SO  = $row_09->TOT_SO / 1000000;
		        $T09_JO  = $row_09->TOT_JO / 1000000;
		        $T09_PRD = $row_09->TOT_PRD / 1000000;
		    endforeach;
		    if($T09_PR == '') $T09_PR = 0;
		    if($T09_PO == '') $T09_PO = 0;
		    if($T09_IR == '') $T09_IR = 0;
		    if($T09_UM == '') $T09_UM = 0;
		    if($T09_SO == '') $T09_SO = 0;
		    if($T09_JO == '') $T09_JO = 0;
		    if($T09_PRD == '') $T09_PRD = 0;

			$sql_10 = "SELECT SUM(DOC_PRV) AS TOT_PR, SUM(DOC_POV) AS TOT_PO, SUM(DOC_IRV) AS TOT_IR, SUM(DOC_UMV) AS TOT_UM,
							SUM(DOC_SOV) AS TOT_SO, SUM(DOC_JOV) AS TOT_JO, SUM(DOC_PRODV) AS TOT_PRD
						FROM tbl_doc_concl WHERE $ADDQRY YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '10'";
		    $res_10 = $this->db->query($sql_10)->result();
		    foreach($res_10 as $row_10) :
		        $T10_PR  = $row_10->TOT_PR / 1000000;
		        $T10_PO  = $row_10->TOT_PO / 1000000;
		        $T10_IR  = $row_10->TOT_IR / 1000000;
		        $T10_UM  = $row_10->TOT_UM / 1000000;
		        $T10_SO  = $row_10->TOT_SO / 1000000;
		        $T10_JO  = $row_10->TOT_JO / 1000000;
		        $T10_PRD = $row_10->TOT_PRD / 1000000;
		    endforeach;
		    if($T10_PR == '') $T10_PR = 0;
		    if($T10_PO == '') $T10_PO = 0;
		    if($T10_IR == '') $T10_IR = 0;
		    if($T10_UM == '') $T10_UM = 0;
		    if($T10_SO == '') $T10_SO = 0;
		    if($T10_JO == '') $T10_JO = 0;
		    if($T10_PRD == '') $T10_PRD = 0;

			$sql_11 = "SELECT SUM(DOC_PRV) AS TOT_PR, SUM(DOC_POV) AS TOT_PO, SUM(DOC_IRV) AS TOT_IR, SUM(DOC_UMV) AS TOT_UM,
							SUM(DOC_SOV) AS TOT_SO, SUM(DOC_JOV) AS TOT_JO, SUM(DOC_PRODV) AS TOT_PRD
						FROM tbl_doc_concl WHERE $ADDQRY YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '11'";
		    $res_11 = $this->db->query($sql_11)->result();
		    foreach($res_11 as $row_11) :
		        $T11_PR  = $row_11->TOT_PR / 1000000;
		        $T11_PO  = $row_11->TOT_PO / 1000000;
		        $T11_IR  = $row_11->TOT_IR / 1000000;
		        $T11_UM  = $row_11->TOT_UM / 1000000;
		        $T11_SO  = $row_11->TOT_SO / 1000000;
		        $T11_JO  = $row_11->TOT_JO / 1000000;
		        $T11_PRD = $row_11->TOT_PRD / 1000000;
		    endforeach;
		    if($T11_PR == '') $T11_PR = 0;
		    if($T11_PO == '') $T11_PO = 0;
		    if($T11_IR == '') $T11_IR = 0;
		    if($T11_UM == '') $T11_UM = 0;
		    if($T11_SO == '') $T11_SO = 0;
		    if($T11_JO == '') $T11_JO = 0;
		    if($T11_PRD == '') $T11_PRD = 0;

			$sql_12 = "SELECT SUM(DOC_PRV) AS TOT_PR, SUM(DOC_POV) AS TOT_PO, SUM(DOC_IRV) AS TOT_IR, SUM(DOC_UMV) AS TOT_UM,
							SUM(DOC_SOV) AS TOT_SO, SUM(DOC_JOV) AS TOT_JO, SUM(DOC_PRODV) AS TOT_PRD
						FROM tbl_doc_concl WHERE $ADDQRY YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '12'";
		    $res_12 = $this->db->query($sql_12)->result();
		    foreach($res_12 as $row_12) :
		        $T12_PR  = $row_12->TOT_PR / 1000000;
		        $T12_PO  = $row_12->TOT_PO / 1000000;
		        $T12_IR  = $row_12->TOT_IR / 1000000;
		        $T12_UM  = $row_12->TOT_UM / 1000000;
		        $T12_SO  = $row_12->TOT_SO / 1000000;
		        $T12_JO  = $row_12->TOT_JO / 1000000;
		        $T12_PRD = $row_12->TOT_PRD / 1000000;
		    endforeach;
		    if($T12_PR == '') $T12_PR = 0;
		    if($T12_PO == '') $T12_PO = 0;
		    if($T12_IR == '') $T12_IR = 0;
		    if($T12_UM == '') $T12_UM = 0;
		    if($T12_SO == '') $T12_SO = 0;
		    if($T12_JO == '') $T12_JO = 0;
		    if($T12_PRD == '') $T12_PRD = 0;
		}
		
		if($GRF_SALES == 1) 
		{
			$nSOValB 	= 0;
			$nJOValB 	= 0;
			if($varMB == 'T01_SO') { $nSOValB	= $T01_SO; $nJOValB	= $T01_JO; }
			elseif($varMB == 'T02_SO') { $nSOValB	= $T02_SO; $nJOValB	= $T02_JO; }
			elseif($varMB == 'T03_SO') { $nSOValB	= $T03_SO; $nJOValB	= $T03_JO; }
			elseif($varMB == 'T04_SO') { $nSOValB	= $T04_SO; $nJOValB	= $T04_JO; }
			elseif($varMB == 'T05_SO') { $nSOValB	= $T05_SO; $nJOValB	= $T05_JO; }
			elseif($varMB == 'T06_SO') { $nSOValB	= $T06_SO; $nJOValB	= $T06_JO; }
			elseif($varMB == 'T07_SO') { $nSOValB	= $T07_SO; $nJOValB	= $T07_JO; }
			elseif($varMB == 'T08_SO') { $nSOValB	= $T08_SO; $nJOValB	= $T08_JO; }
			elseif($varMB == 'T09_SO') { $nSOValB	= $T09_SO; $nJOValB	= $T09_JO; }
			elseif($varMB == 'T10_SO') { $nSOValB	= $T10_SO; $nJOValB	= $T10_JO; }
			elseif($varMB == 'T11_SO') { $nSOValB	= $T11_SO; $nJOValB	= $T11_JO; }
			elseif($varMB == 'T12_SO') { $nSOValB	= $T12_SO; $nJOValB	= $T12_JO; }

			$nSOVal 	= 0;
			$nJOVal 	= 0;
			if($varM == 'T01_SO') { $nSOVal	= $T01_SO; $nJOVal	= $T01_JO; }
			elseif($varM == 'T02_SO') { $nSOVal	= $T02_SO; $nJOVal	= $T02_JO; }
			elseif($varM == 'T03_SO') { $nSOVal	= $T03_SO; $nJOVal	= $T03_JO; }
			elseif($varM == 'T04_SO') { $nSOVal	= $T04_SO; $nJOVal	= $T04_JO; }
			elseif($varM == 'T05_SO') { $nSOVal	= $T05_SO; $nJOVal	= $T05_JO; }
			elseif($varM == 'T06_SO') { $nSOVal	= $T06_SO; $nJOVal	= $T06_JO; }
			elseif($varM == 'T07_SO') { $nSOVal	= $T07_SO; $nJOVal	= $T07_JO; }
			elseif($varM == 'T08_SO') { $nSOVal	= $T08_SO; $nJOVal	= $T08_JO; }
			elseif($varM == 'T09_SO') { $nSOVal	= $T09_SO; $nJOVal	= $T09_JO; }
			elseif($varM == 'T10_SO') { $nSOVal	= $T10_SO; $nJOVal	= $T10_JO; }
			elseif($varM == 'T11_SO') { $nSOVal	= $T11_SO; $nJOVal	= $T11_JO; }
			elseif($varM == 'T12_SO') { $nSOVal	= $T12_SO; $nJOVal	= $T12_JO; }

			$nSOBal		= $nSOVal - $nSOValB;			// Selisih SO thd bulan sebelumnya
			$nSOValBP 	= $nSOValB;
				if($nSOValBP == 0) $nSOValBP = 1;
			$nSOPerc	= $nSOBal / $nSOValBP * 100;	// SO Percentation

			$nJOBal		= $nJOVal - $nJOValB;			// Selisih JO thd bulan sebelumnya
			$nJOValBP 	= $nJOValB;
				if($nJOValBP == 0) $nJOValBP = 1;
			$nJOPerc	= $nJOBal / $nJOValBP * 100;	// JO Percentation

			$nGPRFValB	= ($nSOValB * 1000000) - ($nJOValB * 1000000);	// Gross Grofit Before
			$nGPRFValC	= ($nSOVal * 1000000) - ($nJOVal * 1000000);	// Gross Grofit Current

			$nGPRFBal	= $nGPRFValC - $nGPRFValB;			// Selisih GPRF thd bulan sebelumnya
			$nGPRFValBP = $nGPRFValB;
				if($nGPRFValBP == 0) $nGPRFValBP = 1;
			$nGPRFPerc	= $nGPRFBal / $nGPRFValBP * 100;	// GPRF Percentation

			$nSOPercV	= number_format($nSOPerc, 2);
			$nJOPercV	= number_format($nJOPerc, 2);
			$nGPRFPercV	= number_format($nGPRFPerc, 2);

			$nSOValV	= number_format($nSOVal * 1000000, 2);
			$nJOValV	= number_format($nJOVal * 1000000, 2);
			$nGPRFValV	= number_format($nGPRFValC, 2);
		}
    	?>
		<?php if($GRF_SALES == 1)  { ?>
			var nSOPerc 	= "<?php echo $nSOPercV; ?>";
			var nJOPerc 	= "<?php echo $nJOPerc; ?>";
			var nGPRFPerc 	= "<?php echo $nGPRFPerc; ?>";

    		document.getElementById('tot_sop').innerHTML 		= "<?php echo $nSOPercV; ?>";
			if(nSOPerc >= 0)
			{
				document.getElementById('textSO').className		= "description-percentage text-green";
				document.getElementById('tot_sopicn').className	= "fa fa-angle-double-up";
			}
			else
			{
				document.getElementById('textSO').className			= "description-percentage text-red";
				document.getElementById('tot_sopicn').className		= "fa fa-angle-double-down";
			}
    		

    		document.getElementById('tot_jop').innerHTML 			= "<?php echo $nJOPercV; ?>";
			if(nJOPerc >= 0)
			{
				document.getElementById('textJO').className			= "description-percentage text-green";
				document.getElementById('tot_jopicn').className		= "fa fa-angle-double-up";
			}
			else
			{
				document.getElementById('textJO').className			= "description-percentage text-red";
				document.getElementById('tot_jopicn').className		= "fa fa-angle-double-down";
			}

    		document.getElementById('gross_profp').innerHTML 		= "<?php echo $nGPRFPercV; ?>";
			if(nGPRFPerc >= 0)
			{
				document.getElementById('textLR').className			= "description-percentage text-green";
				document.getElementById('gross_profpicn').className	= "fa fa-angle-double-up";
			}
			else
			{
				document.getElementById('textLR').className			= "description-percentage text-red";
				document.getElementById('gross_profpicn').className	= "fa fa-angle-double-down";
			}

    		document.getElementById('gross_profp').title 		= "<?php echo number_format($nGPRFValC, 2) ."-". number_format($nGPRFValB, 2); ?>";

    		document.getElementById('tot_sov').innerHTML 		= "<?php echo $nSOValV; ?>";
    		document.getElementById('tot_jov').innerHTML 		= "<?php echo $nJOValV; ?>";
    		document.getElementById('gross_prof').innerHTML 	= "<?php echo $nGPRFValV; ?>";
    	<?php } ?>

		<?php if($GRF_PURCH == 1) { ?>
			window.chartColors = {
				red: 'rgb(255, 99, 132)',
				orange: 'rgb(255, 159, 64)',
				yellow: 'rgb(255, 205, 86)',
				green: 'rgb(75, 192, 192)',
				blue: 'rgb(54, 162, 235)',
				purple: 'rgb(153, 102, 255)',
				grey: 'rgb(201, 203, 207)'
			};

			(function(global) {
				var MONTHS = [
					'Jan',
					'Feb',
					'Mar',
					'Apr',
					'Mei',
					'Jun',
					'Jul',
					'Agus',
					'Sept',
					'Okt',
					'Nop',
					'Des'
				];

				var COLORS = [
					'#4dc9f6',
					'#f67019',
					'#f53794',
					'#537bc4',
					'#acc236',
					'#166a8f',
					'#00a950',
					'#58595b',
					'#8549ba'
				];

				var Samples = global.Samples || (global.Samples = {});
				var Color = global.Color;

				Samples.utils = {
					// Adapted from http://indiegamr.com/generate-repeatable-random-numbers-in-js/
					srand: function(seed) {
						this._seed = seed;
					},

					rand: function(min, max) {
						var seed = this._seed;
						min = min === undefined ? 0 : min;
						max = max === undefined ? 1 : max;
						this._seed = (seed * 9301 + 49297) % 233280;
						return min + (this._seed / 233280) * (max - min);
					},

					numbers: function(config) {
						var cfg = config || {};
						var min = cfg.min || 0;
						var max = cfg.max || 1;
						var from = cfg.from || [];
						var count = cfg.count || 8;
						var decimals = cfg.decimals || 8;
						var continuity = cfg.continuity || 1;
						var dfactor = Math.pow(10, decimals) || 0;
						var data = [];
						var i, value;

						for (i = 0; i < count; ++i) {
							value = (from[i] || 0) + this.rand(min, max);
							if (this.rand() <= continuity) {
								data.push(Math.round(dfactor * value) / dfactor);
							} else {
								data.push(null);
							}
						}

						return data;
					},

					labels: function(config) {
						var cfg = config || {};
						var min = cfg.min || 0;
						var max = cfg.max || 100;
						var count = cfg.count || 8;
						var step = (max - min) / count;
						var decimals = cfg.decimals || 8;
						var dfactor = Math.pow(10, decimals) || 0;
						var prefix = cfg.prefix || '';
						var values = [];
						var i;

						for (i = min; i < max; i += step) {
							values.push(prefix + Math.round(dfactor * i) / dfactor);
						}

						return values;
					},

					months: function(config) {
						var cfg = config || {};
						var count = cfg.count || 12;
						var section = cfg.section;
						var values = [];
						var i, value;

						for (i = 0; i < count; ++i) {
							value = MONTHS[Math.ceil(i) % 12];
							values.push(value.substring(0, section));
						}

						return values;
					},

					color: function(index) {
						return COLORS[index % COLORS.length];
					},

					transparentize: function(color, opacity) {
						var alpha = opacity === undefined ? 0.5 : 1 - opacity;
						return Color(color).alpha(alpha).rgbString();
					}
				};

				// DEPRECATED
				window.randomScalingFactor = function() {
					return Math.round(Samples.utils.rand(-100, 100));
				};

				// INITIALIZATION

				Samples.utils.srand(Date.now());

				// Google Analytics
				/* eslint-disable */
				if (document.location.hostname.match(/^(www\.)?chartjs\.org$/)) {
					(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
					})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
					ga('create', 'UA-28909194-3', 'auto');
					ga('send', 'pageview');
				}
				/* eslint-enable */

			}(this));

			var MONTHS 		= ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agus', 'Sept', 'Okt', 'Nov', 'Des'];
			var config_purc = {
				type: 'line',
				data: {
					labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agus', 'Sept', 'Okt', 'Nov', 'Des'],
					datasets: [
					{
						label: 'Permintaan',
						backgroundColor: window.chartColors.red,
						borderColor: window.chartColors.red,
						data: [
							<?php echo $T01_PR; ?>,
							<?php echo $T02_PR; ?>,
							<?php echo $T03_PR; ?>,
							<?php echo $T04_PR; ?>,
							<?php echo $T05_PR; ?>,
							<?php echo $T06_PR; ?>,
							<?php echo $T07_PR; ?>,
							<?php echo $T08_PR; ?>,
							<?php echo $T09_PR; ?>,
							<?php echo $T10_PR; ?>,
							<?php echo $T11_PR; ?>,
							<?php echo $T12_PR; ?>
						],
						fill: false,
					}, {
						label: 'Pembelian',
						fill: false,
						backgroundColor: window.chartColors.blue,
						borderColor: window.chartColors.blue,
						data: [
							<?php echo $T01_PO; ?>,
							<?php echo $T02_PO; ?>,
							<?php echo $T03_PO; ?>,
							<?php echo $T04_PO; ?>,
							<?php echo $T05_PO; ?>,
							<?php echo $T06_PO; ?>,
							<?php echo $T07_PO; ?>,
							<?php echo $T08_PO; ?>,
							<?php echo $T09_PO; ?>,
							<?php echo $T10_PO; ?>,
							<?php echo $T11_PO; ?>,
							<?php echo $T12_PO; ?>
						],
					}, {
						label: 'Penerimaan',
						fill: false,
						backgroundColor: window.chartColors.yellow,
						borderColor: window.chartColors.yellow,
						data: [
							<?php echo $T01_IR; ?>,
							<?php echo $T02_IR; ?>,
							<?php echo $T03_IR; ?>,
							<?php echo $T04_IR; ?>,
							<?php echo $T05_IR; ?>,
							<?php echo $T06_IR; ?>,
							<?php echo $T07_IR; ?>,
							<?php echo $T08_IR; ?>,
							<?php echo $T09_IR; ?>,
							<?php echo $T10_IR; ?>,
							<?php echo $T11_IR; ?>,
							<?php echo $T12_IR; ?>
						],
					}, {
						label: 'Penggunaan',
						fill: false,
						backgroundColor: window.chartColors.green,
						borderColor: window.chartColors.green,
						data: [
							<?php echo $T01_UM; ?>,
							<?php echo $T02_UM; ?>,
							<?php echo $T03_UM; ?>,
							<?php echo $T04_UM; ?>,
							<?php echo $T05_UM; ?>,
							<?php echo $T06_UM; ?>,
							<?php echo $T07_UM; ?>,
							<?php echo $T08_UM; ?>,
							<?php echo $T09_UM; ?>,
							<?php echo $T10_UM; ?>,
							<?php echo $T11_UM; ?>,
							<?php echo $T12_UM; ?>
						],
					}]
				},
				options: {
					responsive: true,
					title: {
						display: true,
						text: 'Grafik Pembelian'
					},
					tooltips: {
						mode: 'index',
						intersect: false,
					},
					hover: {
						mode: 'nearest',
						intersect: true
					},
					scales: {
						xAxes: [{
							display: true,
							scaleLabel: {
								display: true,
								labelString: 'Bulan'
							}
						}],
						yAxes: [{
							display: true,
							scaleLabel: {
								display: true,
								labelString: 'Nilai (dalam Juta Rp)'
							}
						}]
					}
				}
			};

			var config_sls 	= {
				type: 'line',
				data: {
					labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agus', 'Sept', 'Okt', 'Nov', 'Des'],
					datasets: [
					{
						label: 'Sales Order',
						backgroundColor: window.chartColors.red,
						borderColor: window.chartColors.red,
						data: [
							<?php echo $T01_SO; ?>,
							<?php echo $T02_SO; ?>,
							<?php echo $T03_SO; ?>,
							<?php echo $T04_SO; ?>,
							<?php echo $T05_SO; ?>,
							<?php echo $T06_SO; ?>,
							<?php echo $T07_SO; ?>,
							<?php echo $T08_SO; ?>,
							<?php echo $T09_SO; ?>,
							<?php echo $T10_SO; ?>,
							<?php echo $T11_SO; ?>,
							<?php echo $T12_SO; ?>
						],
						fill: false,
					}, {
						label: 'Job Order',
						fill: false,
						backgroundColor: window.chartColors.blue,
						borderColor: window.chartColors.blue,
						data: [
							<?php echo $T01_JO; ?>,
							<?php echo $T02_JO; ?>,
							<?php echo $T03_JO; ?>,
							<?php echo $T04_JO; ?>,
							<?php echo $T05_JO; ?>,
							<?php echo $T06_JO; ?>,
							<?php echo $T07_JO; ?>,
							<?php echo $T08_JO; ?>,
							<?php echo $T09_JO; ?>,
							<?php echo $T10_JO; ?>,
							<?php echo $T11_JO; ?>,
							<?php echo $T12_JO; ?>
						],
					}, {
						label: 'Produksi',
						fill: false,
						backgroundColor: window.chartColors.yellow,
						borderColor: window.chartColors.yellow,
						data: [
							<?php echo $T01_PRD; ?>,
							<?php echo $T02_PRD; ?>,
							<?php echo $T03_PRD; ?>,
							<?php echo $T04_PRD; ?>,
							<?php echo $T05_PRD; ?>,
							<?php echo $T06_PRD; ?>,
							<?php echo $T07_PRD; ?>,
							<?php echo $T08_PRD; ?>,
							<?php echo $T09_PRD; ?>,
							<?php echo $T10_PRD; ?>,
							<?php echo $T11_PRD; ?>,
							<?php echo $T12_PRD; ?>
						],
					}]
				},
				options: {
					responsive: true,
					title: {
						display: true,
						text: 'Grafik Penjualan'
					},
					tooltips: {
						mode: 'index',
						intersect: false,
					},
					hover: {
						mode: 'nearest',
						intersect: true
					},
					scales: {
						xAxes: [{
							display: true,
							scaleLabel: {
								display: true,
								labelString: 'Bulan'
							}
						}],
						yAxes: [{
							display: true,
							scaleLabel: {
								display: true,
								labelString: 'Nilai (dalam Juta Rp)'
							}
						}]
					}
				}
			};

			window.onload = function() {
				<?php if($GRF_PURCH == 1) { ?>
					var ctx = document.getElementById('purchase-chart').getContext('2d');
					window.myLine = new Chart(ctx, config_purc);
				<?php } ?>
				<?php if($GRF_SALES == 1) { ?>
					var cty = document.getElementById('sales-chart').getContext('2d');
					window.myLine = new Chart(cty, config_sls);
				<?php } ?>
			};
		<?php } ?>
    });
</script>
<?php
	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

	// Right side column. contains the Control Panel
	//______$this->load->view('template/aside');

	//______$this->load->view('template/js_data');

	//______$this->load->view('template/foot');
?>

<?php
	$addDt 		= 0;
	$getPRDQ 	= base_url().'index.php/__l1y/getPRDQ/?id=';
	$cIMG		= base_url('assets/AdminLTE-2.0.5/cust_image/');
	$secIMG 	= base_url().'index.php/__l1y/getCUSTIMG/?id=';
?>
<script>
	<?php if($GRF_DPROD == 1) { ?>
		am4core.ready(function()
		{
			// Themes begin
			am4core.useTheme(am4themes_material);
			am4core.useTheme(am4themes_animated);
			// Themes end

			// START : DAILY PRODUCTION
				// Create chart instance
				var chart = am4core.create("dailyProd", am4charts.XYChart);

				// Add data
				chart.data = generateChartData();

				// Create axes
				var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
				dateAxis.renderer.minGridDistance = 50;

				var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

				// Create series
				var series = chart.series.push(new am4charts.LineSeries());
				series.dataFields.valueY = "visits";
				series.dataFields.dateX = "date";
				series.strokeWidth = 2;
				series.minBulletDistance = 10;
				series.tooltipText = "{valueY} KG";
				series.tooltip.pointerOrientation = "vertical";
				series.tooltip.background.cornerRadius = 20;
				series.tooltip.background.fillOpacity = 0.5;
				series.tooltip.label.padding(12,12,12,12)

				// Add scrollbar
				chart.scrollbarX = new am4charts.XYChartScrollbar();
				chart.scrollbarX.series.push(series);

				// Add cursor
				chart.cursor = new am4charts.XYCursor();
				chart.cursor.xAxis = dateAxis;
				chart.cursor.snapToSeries = series;

				function generateChartData()
				{
					var tYear   	= "<?php echo $tY; ?>";
				    var tMonth   	= "<?php echo $tM; ?>";
				    var chartData 	= [];
				    var firstDate 	= new Date(tYear, tMonth, 0, 0, 0, 0);
				    firstDate.setDate(firstDate.getDate());
				    //alert(firstDate)  // Tue Dec 31 2019 00:00:00 GMT+0700 (Indochina Time)
				    var visits = 0;
				    <?php
				    	for($i=0; $i< 360; $i++)
				    	{
		   					$newDt      = date('Y-m-d', strtotime('+'.$i.' day', strtotime($tNDate1)));

		   					$sql_01 	= "SELECT SUM(DOC_PRODQ) AS TOT_PRDQ, SUM(DOC_PRODV) AS TOT_PRDV
											FROM tbl_doc_concl WHERE DOC_DATE = '$newDt'";
						    $res_01 	= $this->db->query($sql_01)->result();
						    foreach($res_01 as $row_01) :
						        $T_PRDQ  = $row_01->TOT_PRDQ;
						        $T_PRDV  = $row_01->TOT_PRDV;
						    endforeach;
						    /*if($T01_PR == '') $T01_PR = 0;
						    if($T01_PO == '') $T01_PO = 0;
						    if($T01_IR == '') $T01_IR = 0;
						    if($T01_UM == '') $T01_UM = 0;
						    if($T01_SO == '') $T01_SO = 0;
						    if($T01_JO == '') $T01_JO = 0;
						    if($T01_PRD == '') $T01_PRD = 0;*/
					    	?>
								visits += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);

								chartData.push({
								    date: "<?php echo $newDt; ?>",
								    visits: "<?php echo $T_PRDQ; ?>"
								});
							<?php
						}
					?>
					return chartData;
				}
			// END : DAILY PRODUCTION

			// START : CUSTOMER SALES
				var data = {
			        <?php
						$sqlCUST  = "SELECT DISTINCT CUST_CODE, CUST_DESC FROM tbl_customer WHERE CUST_STAT = 1";
				      	$resCUST  = $this->db->query($sqlCUST)->result();
				      	foreach($resCUST as $rowCUST) :
							$CUST_CODE  = $rowCUST->CUST_CODE;
							$CUST_DESC  = $rowCUST->CUST_DESC;
							// GET IMG_CODE
								$CUST_IMG = "";
								$sqlCIMG  = "SELECT IMGC_FILENAMEX FROM tbl_customer_img WHERE IMGC_CUSTCODE = '$CUST_CODE'";
						      	$resCIMG  = $this->db->query($sqlCIMG)->result();
						      	foreach($resCIMG as $rowCIMG) :
									$CUST_IMG  = $rowCIMG->IMGC_FILENAMEX;
									//$CUST_IMG  = "BMW";
								endforeach;
								$cDATA 	= "$CUST_CODE~$CUST_IMG~$CUST_DESC";
								//$cDATA 	= "bmw";
							?>
					        "<?=$cDATA?>": {
					        	<?php
					        		$CUST_SLS = 0;
									$sqlCSLS  = "SELECT SUM(A.SO_COST) AS TOT_SLS, A.ITM_CODE, B.ITM_NAME
													FROM tbl_so_detail A
														INNER JOIN tbl_so_header X ON X.SO_NUM = A.SO_NUM AND X.PRJCODE = A.PRJCODE
														INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.PRJCODE = A.PRJCODE
													WHERE X.CUST_CODE = '$CUST_CODE'
													GROUP BY A.ITM_CODE";
							      	$resCSLS  = $this->db->query($sqlCSLS)->result();
							      	foreach($resCSLS as $rowCSLS) :
										$TOT_SLS  	= $rowCSLS->TOT_SLS;
										$ITM_NAME  	= $rowCSLS->ITM_NAME;
										?>
											"<?=$ITM_NAME?>": <?=$TOT_SLS?>,
										<?php
									endforeach;
								?>
					        },
						    <?php
						endforeach;
					?>
			        "Volvo": { "S60": 16825, "S80": 7, "S90": 11090, "XC60": 22516, "XC90": 30996 }
			    }

			    function processData(data) {
			        var treeData = [];

			        var smallBrands = { name: "Other", children: [] };

			        for (var brand in data) {
			        	var countDt	= brand.split("~").length;
			        	if(countDt > 1)
			        	{
			        		var cscode 	= brand.split("~")[0];
			        		var csimgnm = brand.split("~")[1];
				        	var csfname = brand.split("~")[2];
			        	}
			        	else
			        	{
			        		var cscode 	= "";
			        		var csimgnm = "username";
				        	var csfname = "Others";
			        	}

			            var brandData 	= { code: cscode, imgnm: csimgnm, name: csfname, children: [] }
			            var brandTotal 	= 0;
			            for (var model in data[brand]) {
			                brandTotal += data[brand][model];
			            }

			            for (var model in data[brand]) {
			                // do not add very small
			                if (data[brand][model] > 100) {
			                    brandData.children.push({ name: model, count: data[brand][model] });
			                }
			            }

			            // add to small brands if total number less than
			            if (brandTotal > 100000) {
			                treeData.push(brandData);
			            }
			            else {
			              smallBrands.children.push(brandData)
			            }
			        }
			        treeData.push(smallBrands);
			        return treeData;
			    }

			    // create chart
			        var chart = am4core.create("salesInfo", am4charts.TreeMap);
			        chart.hiddenState.properties.opacity = 0; // this makes initial fade in effect

			    // only one level visible initially
			        chart.maxLevels = 1;
			        // define data fields
			        chart.dataFields.value 		= "count";
			        chart.dataFields.code 		= "code";
			        chart.dataFields.imgnm 		= "imgnm";
			        chart.dataFields.name 		= "name";
			        chart.dataFields.children 	= "children";

			    // enable navigation
			        chart.navigationBar = new am4charts.NavigationBar();

			    // level 0 series template
			        var level0SeriesTemplate = chart.seriesTemplates.create("0");
			        level0SeriesTemplate.strokeWidth = 2;

			    // by default only current level series bullets are visible, but as we need brand bullets to be visible all the time, we modify it's hidden state
			        level0SeriesTemplate.bulletsContainer.hiddenState.properties.opacity = 1;
			        level0SeriesTemplate.bulletsContainer.hiddenState.properties.visible = true;
			    // create hover state
			        var columnTemplate = level0SeriesTemplate.columns.template;
			        var hoverState = columnTemplate.states.create("hover");

			    // darken
			        hoverState.adapter.add("fill", function (fill, target) {
			            if (fill instanceof am4core.Color) {
			                return am4core.color(am4core.colors.brighten(fill.rgb, -0.2));
			            }
			            return fill;
			        })

			    // add logo
			        var image = columnTemplate.createChild(am4core.Image);
			        image.opacity = 0.15;
			        image.align = "center";
			        image.valign = "middle";
			        image.width = am4core.percent(80);
			        image.height = am4core.percent(80);

			    // add adapter for href to load correct image
			        image.adapter.add("href", function (href, target) {
			            var dataItem = target.parent.dataItem;
			            if (dataItem) {
			            	var cscode 	= dataItem.treeMapDataItem.code;
			            	var csimgnm = dataItem.treeMapDataItem.imgnm;
			            	var csname 	= dataItem.treeMapDataItem.name;

			                var tPath 	= "<?=$cIMG?>/" + cscode + "/"+ csimgnm;
				        	if(csname == 'Other')
				        	{
			                	var tPath 	= "<?=$cIMG?>/username.png";
				        	}
			                return tPath;
			            }
			        });

			    // level1 series template
			        var level1SeriesTemplate = chart.seriesTemplates.create("1");
			        level1SeriesTemplate.columns.template.fillOpacity = 0;

			        var bullet1 = level1SeriesTemplate.bullets.push(new am4charts.LabelBullet());
			        bullet1.locationX = 0.5;
			        bullet1.locationY = 0.5;
			        bullet1.label.text = "{name}";
			        bullet1.label.fill = am4core.color("#ffffff");

			    // level2 series template
			        var level2SeriesTemplate = chart.seriesTemplates.create("2");
			        level2SeriesTemplate.columns.template.fillOpacity = 0;

			        var bullet2 = level2SeriesTemplate.bullets.push(new am4charts.LabelBullet());
			        bullet2.locationX = 0.5;
			        bullet2.locationY = 0.5;
			        bullet2.label.text = "{name}";
			        bullet2.label.fill = am4core.color("#ffffff");

			        chart.data = processData(data);
			// END : CUSTOMER SALES
		});
	<?php } ?>
</script>