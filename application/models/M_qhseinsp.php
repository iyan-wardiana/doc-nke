<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 17 Oktober 2018
 * File Name	= M_qhseinsp.php
 * Location		= -
*/

class M_qhseinsp extends CI_Model
{	
	function add($InsInsp) // G
	{
		$this->db->insert('tbl_qhse_inscpech', $InsInsp);
	}
	
	function update($RSV_CODE, $UpdRes) // MR - VH
	{
		$RSV_CATEG		= $UpdRes['RSV_CATEG'];
		//Insert Meeting Room
		if($RSV_CATEG == 'MR')
		{
			$MR_CODE 		= $UpdRes['CATEG_CODE2'];
			$MR_STARTD 		= $UpdRes['RSV_STARTD2'];
			$MR_ENDD 		= $UpdRes['RSV_ENDD2'];
			$MR_STARTT 		= $UpdRes['RSV_STARTT2'];
			$MR_ENDT 		= $UpdRes['RSV_ENDT2'];
			$MR_NOTES 		= $UpdRes['RSV_DESC'];
			$MR_SUBMITTER 	= $UpdRes['RSV_SUBMITTER'];
			$RSV_MAIL 		= $UpdRes['RSV_MAIL'];
			$MR_STAT 		= $UpdRes['RSV_STAT'];
			
			if($MR_STAT == 2)
			{
				$MR_NAME		= '';
				$sqlMR 			= "SELECT MR_NAME FROM tbl_meeting_room WHERE MR_CODE = '$MR_CODE'";
				$resMR 			= $this->db->query($sqlMR)->result();
				foreach($resMR as $rowMR) :
					$MR_NAME 	= $rowMR->MR_NAME;		
				endforeach;
				$RSVSTARTDV		= date('d/m/Y', strtotime($MR_STARTD));
				$MRENDDV		= date('d/m/Y', strtotime($MR_ENDD));
				$MRSTARTTV		= date('H:i', strtotime($MR_STARTT));
				$MRENDTV		= date('H:i', strtotime($MR_ENDT));
				
				// SENT MAIL TO SUBMITTER
					$toMail		= ''.$RSV_MAIL.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Pemesanan Ruang Rapat";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Terimakasih sudah melakukan pemesanan Ruang Rapat melalui NKE Smart System.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Pesanan Anda:</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Ruang Rapat</td>
											<td width="89%">: '.$MR_NAME.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSVSTARTDV.' s.d. '.$MRENDDV.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$MRSTARTTV.' s.d. '.$MRENDTV.' WIB</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: MENUNGGU</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);
				
				// SENT MAIL TO APPROVER
					$MAIL_APP	= 'ully@nusakonstruksi.com';
					$toMail		= ''.$MAIL_APP.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Pemesanan Ruang Rapat";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Ada pemesanan baru untuk penggunaan Ruang Rapat yang perlu Anda konfirmasi.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Ruang Rapat</td>
											<td width="89%">: '.$MR_NAME.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSVSTARTDV.' s.d. '.$MRENDDV.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$MRSTARTTV.' s.d. '.$MRENDTV.' WIB</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: MENUNGGU KONFIRMASI</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);
			}
		}
		else if($RSV_CATEG == 'VH')
		{
			//$RSV_CODE 		= $UpdRes['RSV_CODE'];
			$VH_CODE 		= $UpdRes['CATEG_CODE2'];
			$DRIVER_CODE	= $UpdRes['DRIVER_CODE'];
			$VH_STARTD 		= $UpdRes['RSV_STARTD2'];
			$VH_ENDD 		= $UpdRes['RSV_ENDD2'];
			$VH_STARTT 		= $UpdRes['RSV_STARTT2'];
			$VH_ENDT 		= $UpdRes['RSV_ENDT2'];
			$VH_NOTES 		= $UpdRes['RSV_DESC'];
			$VH_SUBMITTER 	= $UpdRes['RSV_SUBMITTER'];
			$RSV_MAIL 		= $UpdRes['RSV_MAIL'];
			$VH_STAT 		= $UpdRes['RSV_STAT'];	
			
			if($VH_STAT == 2)
			{
				$VH_MEREK		= '';
				$sqlVH 			= "SELECT VH_MEREK, VH_NOPOL FROM tbl_vehicle WHERE VH_CODE = '$VH_CODE'";
				$resVH 			= $this->db->query($sqlVH)->result();
				foreach($resVH as $rowVH) :
					$VH_MEREK 	= $rowVH->VH_MEREK;
					$VH_NOPOL 	= $rowVH->VH_NOPOL;		
				endforeach;
				
				$DRIVER		= '';
				$sqlDR 		= "SELECT DRIVER FROM tbl_driver WHERE DRIVER_CODE = '$DRIVER_CODE'";
				$resDR 		= $this->db->query($sqlDR)->result();
				foreach($resDR as $rowDR) :
					$DRIVER 	= $rowDR->DRIVER;	
				endforeach;
				
				$RSVSTARTDV		= date('d/m/Y', strtotime($VH_STARTD));
				$VHENDDV		= date('d/m/Y', strtotime($VH_ENDD));
				$VHSTARTTV		= date('H:i', strtotime($VH_STARTT));
				$VHENDTV		= date('H:i', strtotime($VH_ENDT));
				
				// SENT MAIL TO SUBMITTER
					$toMail		= ''.$RSV_MAIL.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Pemesanan Kendaraan";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Dear,</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Terimakasih sudah melakukan pemesanan kendaraan melalui NKE Smart System.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Pesanan Anda:</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Merek</td>
											<td width="89%">: '.$VH_MEREK.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">No. Polisi</td>
											<td width="89%">: '.$VH_NOPOL.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Driver</td>
											<td width="89%">: '.$DRIVER.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSVSTARTDV.' s.d. '.$VHENDDV.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$VHSTARTTV.' s.d. '.$VHENDTV.' WIB</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: MENUNGGU</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);
				
				// SENT MAIL TO APPROVER
					$MAIL_APP	= 'iyanwardiana@gmail.com';
					$toMail		= ''.$MAIL_APP.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Pemesanan Kendaraan";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Dear,</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Ada pemesanan baru untuk penggunaan kendaraan yang perlu Anda konfirmasi.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Merek</td>
											<td width="89%">: '.$VH_MEREK.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">No. Polisi</td>
											<td width="89%">: '.$VH_NOPOL.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Driver</td>
											<td width="89%">: '.$DRIVER.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSVSTARTDV.' s.d. '.$VHENDDV.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$VHSTARTTV.' s.d. '.$VHENDTV.' WIB</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: MENUNGGU KONFIRMASI</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);
			}
		}
		elseif($RSV_CATEG == 'RR')
		{
			$AR_CODE 		= $UpdRes['CATEG_CODE2'];
			$AR_STARTD 		= $UpdRes['RSV_STARTD2'];
			$AR_ENDD 		= $UpdRes['RSV_ENDD2'];
			$AR_STARTT 		= $UpdRes['RSV_STARTT2'];
			$AR_ENDT 		= $UpdRes['RSV_ENDT2'];
			$AR_NOTES 		= $UpdRes['RSV_DESC'];
			$AR_SUBMITTER 	= $UpdRes['RSV_SUBMITTER'];
			$RSV_MAIL 		= $UpdRes['RSV_MAIL'];
			$AR_STAT 		= $UpdRes['RSV_STAT'];
			
			if($AR_STAT == 2)
			{
				$AR_NAME		= '';
				$AR_ADDRESS		= '';
				$sqlAR 			= "SELECT AR_NAME, AR_ADDRESS FROM tbl_apartement WHERE AR_CODE = '$AR_CODE'";
				$resAR 			= $this->db->query($sqlAR)->result();
				foreach($resAR as $rowAR) :
					$AR_NAME 	= $rowAR->AR_NAME;	
					$AR_ADDRESS	= $rowAR->AR_ADDRESS;		
				endforeach;
				$RSVSTARTDV		= date('d/m/Y', strtotime($AR_STARTD));
				$MRENDDV		= date('d/m/Y', strtotime($AR_ENDD));
				$MRSTARTTV		= date('H:i', strtotime($AR_STARTT));
				$MRENDTV		= date('H:i', strtotime($AR_ENDT));
				
				// SENT MAIL TO SUBMITTER
					$toMail		= ''.$RSV_MAIL.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Pemesanan Kamar Penginapan";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Terimakasih sudah melakukan pemesanan kamar penginapan melalui NKE Smart System.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Pesanan Anda:</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Kamar</td>
											<td width="89%">: '.$AR_NAME.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Alamat</td>
											<td width="89%">: '.$AR_ADDRESS.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSVSTARTDV.' s.d. '.$MRENDDV.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$MRSTARTTV.' s.d. '.$MRENDTV.' WIB</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: MENUNGGU</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);
				
				// SENT MAIL TO APPROVER
					$MAIL_APP	= 'ully@nusakonstruksi.com';
					$toMail		= ''.$MAIL_APP.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Pemesanan Kamar Penginapan";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Ada pemesanan baru untuk penggunaan kamar penginapan yang perlu Anda konfirmasi.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Kamar</td>
											<td width="89%">: '.$AR_NAME.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Alamat</td>
											<td width="89%">: '.$AR_ADDRESS.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSVSTARTDV.' s.d. '.$MRENDDV.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$MRSTARTTV.' s.d. '.$MRENDTV.' WIB</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: MENUNGGU KONFIRMASI</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);
			}
		}
		
		$this->db->where('RSV_CODE', "$RSV_CODE");
		$this->db->update('tbl_reservation', $UpdRes);
	}
	
	// --------------- START - MEETING ROOM PROCESS ---------------
		function count_all_MR($DefEmp_ID, $REQ_STAT)  // MR
		{
			$sql	= "tbl_reservation WHERE RSV_EMPID = '$DefEmp_ID' AND RSV_CATEG = 'MR' AND RSV_STAT IN ($REQ_STAT)";
			return $this->db->count_all($sql);
		}
		
		function get_all_MR($DefEmp_ID, $REQ_STAT)  // MR
		{
			$sql = "SELECT * FROM tbl_reservation WHERE RSV_EMPID = '$DefEmp_ID' AND RSV_CATEG = 'MR' AND RSV_STAT IN ($REQ_STAT)";
			return $this->db->query($sql);
		}
		
		function count_all_YourMR($DefEmp_ID)  // MR
		{
			$sql	= "tbl_reservation WHERE RSV_EMPID = '$DefEmp_ID' AND RSV_CATEG = 'MR'";
			return $this->db->count_all($sql);
		}
		
		function get_all_YourMR($DefEmp_ID)  // MR
		{
			$sql = "SELECT * FROM tbl_reservation WHERE RSV_EMPID = '$DefEmp_ID' AND RSV_CATEG = 'MR'";
			return $this->db->query($sql);
		}
		
		function count_all_MRAvail($DefEmp_ID, $REQ_STAT)  // MR
		{
			$sql	= "tbl_meeting_room";
			return $this->db->count_all($sql);
		}
		
		function get_all_MRAvail($DefEmp_ID, $REQ_STAT)  // MR
		{
			$sql = "SELECT * FROM tbl_meeting_room";
			return $this->db->query($sql);
		}
		
		function count_all_VHAvail()  // VH
		{
			$sql	= "tbl_vehicle";
			return $this->db->count_all($sql);
		}
		
		function get_all_VHAvail()  // VH
		{
			$sql = "SELECT * FROM tbl_vehicle";
			return $this->db->query($sql);
		}
		
		function count_all_BL()  // MR
		{
			$sql	= "tbl_reservation WHERE RSV_CATEG = 'MR' AND RSV_STAT NOT IN (6,7)";
			//$sql	= "tbl_reservation WHERE RSV_STAT NOT IN (5,6,7)";
			return $this->db->count_all($sql);
		}
		
		function get_all_BL()  // MR
		{
			$sql = "SELECT * FROM tbl_reservation WHERE RSV_CATEG = 'MR' AND RSV_STAT NOT IN (6,7)";
			//$sql = "SELECT * FROM tbl_reservation WHERE RSV_STAT NOT IN (5,6,7)";
			return $this->db->query($sql);
		}
		
		function updateMRStat($MR_CODE, $UpdMRS, $RSV_STAT, $RSV_MAIL, $RSV_CODE) // MR
		{
			$MR_CODE 		= $UpdMRS['MR_CODE'];
			$MR_STARTD 		= $UpdMRS['MR_STARTD'];
			$MR_ENDD 		= $UpdMRS['MR_ENDD'];
			$MR_STARTT 		= $UpdMRS['MR_STARTT'];
			$MR_ENDT 		= $UpdMRS['MR_ENDT'];
			$MR_NOTES 		= $UpdMRS['MR_NOTES'];
			$MR_SUBMITTER 	= $UpdMRS['MR_ENDT'];
			$MR_STAT 		= $UpdMRS['MR_STAT'];
			
			$MR_NAME		= '';
			$sqlMR 			= "SELECT MR_NAME FROM tbl_meeting_room WHERE MR_CODE = '$MR_CODE'";
			$resMR 			= $this->db->query($sqlMR)->result();
			foreach($resMR as $rowMR) :
				$MR_NAME 	= $rowMR->MR_NAME;		
			endforeach;
			
			//echo "Test : $MR_NAME";
			
			$MR_NAME		= '';
			$sqlMR 			= "SELECT MR_NAME FROM tbl_meeting_room WHERE MR_CODE = '$MR_CODE'";
			$resMR 			= $this->db->query($sqlMR)->result();
			foreach($resMR as $rowMR) :
				$MR_NAME 	= $rowMR->MR_NAME;		
			endforeach;
			
			//return false;
			
			$RSVSTARTDV		= date('d/m/Y', strtotime($MR_STARTD));
			$MRENDDV		= date('d/m/Y', strtotime($MR_ENDD));
			$MRSTARTTV		= date('H:i', strtotime($MR_STARTT));
			$MRENDTV		= date('H:i', strtotime($MR_ENDT));
			
			if($RSV_STAT == 1)
			{
				$RSV_STATD 	= 'New';
			}
			elseif($RSV_STAT == 2)
			{
				$RSV_STATD 	= 'MENUNGGU';
			}
			elseif($RSV_STAT == 3)
			{
				$RSV_STATD 	= 'DISETUJUI';
			}
			elseif($RSV_STAT == 4)
			{
				$RSV_STATD 	= 'JADWAL ULANG';
			}
			elseif($RSV_STAT == 5)
			{
				$RSV_STATD 	= 'DITOLAK';
			}
			elseif($RSV_STAT == 6)
			{
				$RSV_STATD 	= 'SELESAI';
			}
			elseif($RSV_STAT == 7)
			{
				$RSV_STATD 	= 'REVISI';
			}
			elseif($RSV_STAT == 8)
			{
				$RSV_STATD 	= 'SEDANG DIGUNAKAN';
			}
			else
			{
				$RSV_STATD 	= 'MENUNGGU';
			}
			
			$this->db->where('MR_CODE', $MR_CODE);
			$this->db->update('tbl_meeting_room', $UpdMRS);
			
			// SENT MAIL TO SUBMITTER
				$toMail		= ''.$RSV_MAIL.'';
				$headers 	= 'MIME-Version: 1.0' . "\r\n";
				$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
				$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
				$subject 	= "Konfirmasi Pemesanan Ruang Rapat";
				$output		= '';
				$output		.= '<table width="100%" border="0">
									<tr>
										<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3">Dear,</td>
									</tr>
									<tr>
										<td colspan="3">Assalamu \'alaikum wr.wb.</td>
									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Pemesanan Ruang Rapat Anda sudah dikonfirmasi, bahwa pesanan Anda:</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">&nbsp;</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">ID</td>
										<td width="89%">: '.$RSV_CODE.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Ruang Rapat</td>
										<td width="89%">: '.$MR_NAME.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Tanggal</td>
										<td width="89%">: '.$RSVSTARTDV.' s.d. '.$MRENDDV.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Waktu</td>
										<td width="89%">: Pukul '.$MRSTARTTV.' s.d. '.$MRENDTV.' WIB</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">STATUS</td>
										<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: '.$RSV_STATD.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">&nbsp;</td>
										<td width="89%">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
									</tr>
									<tr>
										<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Hormat kami,</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
									</tr>
									<tr>
										<td style="vertical-align:top">&nbsp;</td>
									<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">ttd</td>
									</tr>
									<tr>
									<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">&nbsp;</td>
									</tr>';
				$output		.= '</table>';
				//send email
				@mail($toMail, $subject, $output, $headers);
			
			$MRSTAT			= 0;
			if($RSV_STAT == 3 || $RSV_STAT == 4 || $RSV_STAT == 6)
			{
				$MRSTAT		= 1;
			}
			
			$MR_CODE01		= "MR-180001";
			$MR_CODE02		= "MR-180002";
			$MR_CODE03		= "MR-180003";
			$MR_CODE08		= "MR-180008";
			$MR_CODE09		= "MR-180009";
			$MR_CODE10		= "MR-180010";
			
			if($MR_CODE == $MR_CODE01)				// MR-AB and MR-ABC is disabled
			{
				// SENT MAIL
				$sqlColMC	= "tbl_reservation WHERE CATEG_CODE2 IN ('$MR_CODE08','$MR_CODE10')
								AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$resColMC	= $this->db->count_all($sqlColMC);
				if($resColMC > 0)
				{
					$sqlColMail	= "SELECT RSV_CODE, RSV_MAIL, CATEG_CODE2, RSV_STARTD2, RSV_ENDD2, RSV_STARTT2, RSV_ENDT2
									FROM tbl_reservation WHERE CATEG_CODE2 IN ('$MR_CODE08','$MR_CODE10')
										AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
					$resColMail = $this->db->query($sqlColMail)->result();
					foreach($resColMail as $rowColM) :
						$RSV_MAILC 		= $rowColM->RSV_MAIL;
						$RSV_CODE2 		= $rowColM->RSV_CODE;
						$CATEG_CODE2 	= $rowColM->CATEG_CODE2;
						$RSV_STARTD2	= $rowColM->RSV_STARTD2;
						$RSV_ENDD2 		= $rowColM->RSV_ENDD2;
						$RSV_STARTT2 	= $rowColM->RSV_STARTT2;
						$RSV_ENDT2 		= $rowColM->RSV_ENDT2;
						
						$MR_NAME2		= '';
						$sqlMR2			= "SELECT MR_NAME FROM tbl_meeting_room WHERE MR_CODE = '$CATEG_CODE2'";
						$resMR2			= $this->db->query($sqlMR2)->result();
						foreach($resMR2 as $rowMR2) :
							$MR_NAME2 	= $rowMR2->MR_NAME;		
						endforeach;
						
						$RSVSTARTDVa	= date('d/m/Y', strtotime($RSV_STARTD2));
						$MRENDDVa		= date('d/m/Y', strtotime($RSV_ENDD2));
						$MRSTARTTVa		= date('H:i', strtotime($RSV_STARTT2));
						$MRENDTVa		= date('H:i', strtotime($RSV_ENDT2));
						
						$toMail		= ''.$RSV_MAILC.'';
						$headers 	= 'MIME-Version: 1.0' . "\r\n";
						$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
						$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
						$subject 	= "Konfirmasi Pemesanan Ruang Rapat";
						$output		= '';
						$output		.= '<table width="100%" border="0">
											<tr>
												<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3">Dear,</td>
											</tr>
											<tr>
												<td colspan="3">Assalamu \'alaikum wr.wb.</td>
											</tr>
											<tr>
												<td colspan="3">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Pemesanan Ruang Rapat Anda sudah dikonfirmasi oleh admin. Pesanan Anda:</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">ID</td>
												<td width="89%">: '.$RSV_CODE2.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Ruang Rapat</td>
												<td width="89%">: '.$MR_NAME2.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Tanggal</td>
												<td width="89%">: '.$RSVSTARTDVa.' s.d. '.$MRENDDVa.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Waktu</td>
												<td width="89%">: Pukul '.$MRSTARTTVa.' s.d. '.$MRENDTVa.' WIB</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">STATUS</td>
												<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: PERLU PEMBARUAN.</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">&nbsp;</td>
												<td width="89%">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Silahkan buka kembali pesanan Anda, lalu perbaharui tempat atau waktu pelaksanaan rapat.</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
											</tr>
											<tr>
												<td style="vertical-align:top">&nbsp;</td>
												<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Hormat kami,</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
											</tr>
											<tr>
												<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">ttd</td>
											</tr>
											<tr>
											<td style="vertical-align:top">&nbsp;</td>
												<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>';
						$output		.= '</table>';
						//send email
						@mail($toMail, $subject, $output, $headers);
					endforeach;
				}
				
				$sql_08 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE08'";
				$this->db->query($sql_08);
				
				$sql_10 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE10'";
				$this->db->query($sql_10);
				
				$sql_01 	= "UPDATE tbl_meeting_room SET MR_STATD = 'Booked' WHERE MR_CODE = '$MR_CODE01'";
				$this->db->query($sql_01);
				
				// BATLKAN SEMUA PEMESANAN ATAS RUANGAN BERSANGKUTAN YANG DIBOOKING PADA TANGGAL DAN JAM YANG SAMA
				$sql_08a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE08' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_08a);
				
				$sql_10a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE10' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_10a);
			}
			elseif($MR_CODE == $MR_CODE02)			// MR-AB, MR-BC and MR-ABC is disabled
			{
				// SENT MAIL
				$sqlColMC	= "tbl_reservation WHERE CATEG_CODE2 IN ('$MR_CODE08','$MR_CODE09','$MR_CODE10')
								AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$resColMC	= $this->db->count_all($sqlColMC);
				if($resColMC > 0)
				{
					$sqlColMail	= "SELECT RSV_CODE, RSV_MAIL, CATEG_CODE2, RSV_STARTD2, RSV_ENDD2, RSV_STARTT2, RSV_ENDT2
									FROM tbl_reservation WHERE CATEG_CODE2 IN ('$MR_CODE08','$MR_CODE09','$MR_CODE10')
										AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
					$resColMail = $this->db->query($sqlColMail)->result();
					foreach($resColMail as $rowColM) :
						$RSV_MAILC 		= $rowColM->RSV_MAIL;
						$RSV_CODE2 		= $rowColM->RSV_CODE;
						$CATEG_CODE2 	= $rowColM->CATEG_CODE2;
						$RSV_STARTD2	= $rowColM->RSV_STARTD2;
						$RSV_ENDD2 		= $rowColM->RSV_ENDD2;
						$RSV_STARTT2 	= $rowColM->RSV_STARTT2;
						$RSV_ENDT2 		= $rowColM->RSV_ENDT2;
						
						$MR_NAME2		= '';
						$sqlMR2			= "SELECT MR_NAME FROM tbl_meeting_room WHERE MR_CODE = '$CATEG_CODE2'";
						$resMR2			= $this->db->query($sqlMR2)->result();
						foreach($resMR2 as $rowMR2) :
							$MR_NAME2 	= $rowMR2->MR_NAME;		
						endforeach;
						
						$RSVSTARTDVa	= date('d/m/Y', strtotime($RSV_STARTD2));
						$MRENDDVa		= date('d/m/Y', strtotime($RSV_ENDD2));
						$MRSTARTTVa		= date('H:i', strtotime($RSV_STARTT2));
						$MRENDTVa		= date('H:i', strtotime($RSV_ENDT2));
						
						$toMail		= ''.$RSV_MAILC.'';
						$headers 	= 'MIME-Version: 1.0' . "\r\n";
						$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
						$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
						$subject 	= "Konfirmasi Pemesanan Ruang Rapat";
						$output		= '';
						$output		.= '<table width="100%" border="0">
											<tr>
												<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3">Dear,</td>
											</tr>
											<tr>
												<td colspan="3">Assalamu \'alaikum wr.wb.</td>
											</tr>
											<tr>
												<td colspan="3">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Pemesanan Ruang Rapat Anda sudah dikonfirmasi oleh admin. Pesanan Anda:</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">ID</td>
												<td width="89%">: '.$RSV_CODE2.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Ruang Rapat</td>
												<td width="89%">: '.$MR_NAME2.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Tanggal</td>
												<td width="89%">: '.$RSVSTARTDVa.' s.d. '.$MRENDDVa.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Waktu</td>
												<td width="89%">: Pukul '.$MRSTARTTVa.' s.d. '.$MRENDTVa.' WIB</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">STATUS</td>
												<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: PERLU PEMBARUAN.</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">&nbsp;</td>
												<td width="89%">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Silahkan buka kembali pesanan Anda, lalu perbaharui tempat atau waktu pelaksanaan rapat.</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
											</tr>
											<tr>
												<td style="vertical-align:top">&nbsp;</td>
												<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Hormat kami,</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
											</tr>
											<tr>
												<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">ttd</td>
											</tr>
											<tr>
											<td style="vertical-align:top">&nbsp;</td>
												<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>';
						$output		.= '</table>';
						//send email
						@mail($toMail, $subject, $output, $headers);
					endforeach;
				}
				
				$sql_08 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE08'";
				$this->db->query($sql_08);
				
				$sql_09 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE09'";
				$this->db->query($sql_09);
				
				$sql_10 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE10'";
				$this->db->query($sql_10);
				
				$sql_02 	= "UPDATE tbl_meeting_room SET MR_STATD = 'Booked' WHERE MR_CODE = '$MR_CODE02'";
				$this->db->query($sql_02);
				
				// BATLKAN SEMUA PEMESANAN ATAS RUANGAN BERSANGKUTAN YANG DIBOOKING PADA TANGGAL DAN JAM YANG SAMA
				$sql_08a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE08' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_08a);
				
				$sql_09a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE09' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_09a);
				
				$sql_10a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE10' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_10a);
			}
			elseif($MR_CODE == $MR_CODE03)			// MR-BC and MR-ABC is disabled
			{
				// SENT MAIL
				$sqlColMC	= "tbl_reservation WHERE CATEG_CODE2 IN ('$MR_CODE09','$MR_CODE10')
								AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$resColMC	= $this->db->count_all($sqlColMC);
				if($resColMC > 0)
				{
					$sqlColMail	= "SELECT RSV_CODE, RSV_MAIL, CATEG_CODE2, RSV_STARTD2, RSV_ENDD2, RSV_STARTT2, RSV_ENDT2
									FROM tbl_reservation WHERE CATEG_CODE2 IN ('$MR_CODE09','$MR_CODE10')
										AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
					$resColMail = $this->db->query($sqlColMail)->result();
					foreach($resColMail as $rowColM) :
						$RSV_MAILC 		= $rowColM->RSV_MAIL;
						$RSV_CODE2 		= $rowColM->RSV_CODE;
						$CATEG_CODE2 	= $rowColM->CATEG_CODE2;
						$RSV_STARTD2	= $rowColM->RSV_STARTD2;
						$RSV_ENDD2 		= $rowColM->RSV_ENDD2;
						$RSV_STARTT2 	= $rowColM->RSV_STARTT2;
						$RSV_ENDT2 		= $rowColM->RSV_ENDT2;
						
						$MR_NAME2		= '';
						$sqlMR2			= "SELECT MR_NAME FROM tbl_meeting_room WHERE MR_CODE = '$CATEG_CODE2'";
						$resMR2			= $this->db->query($sqlMR2)->result();
						foreach($resMR2 as $rowMR2) :
							$MR_NAME2 	= $rowMR2->MR_NAME;		
						endforeach;
						
						$RSVSTARTDVa	= date('d/m/Y', strtotime($RSV_STARTD2));
						$MRENDDVa		= date('d/m/Y', strtotime($RSV_ENDD2));
						$MRSTARTTVa		= date('H:i', strtotime($RSV_STARTT2));
						$MRENDTVa		= date('H:i', strtotime($RSV_ENDT2));
						
						$toMail		= ''.$RSV_MAILC.'';
						$headers 	= 'MIME-Version: 1.0' . "\r\n";
						$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
						$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
						$subject 	= "Konfirmasi Pemesanan Ruang Rapat";
						$output		= '';
						$output		.= '<table width="100%" border="0">
											<tr>
												<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3">Dear,</td>
											</tr>
											<tr>
												<td colspan="3">Assalamu \'alaikum wr.wb.</td>
											</tr>
											<tr>
												<td colspan="3">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Pemesanan Ruang Rapat Anda sudah dikonfirmasi oleh admin. Pesanan Anda:</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">ID</td>
												<td width="89%">: '.$RSV_CODE2.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Ruang Rapat</td>
												<td width="89%">: '.$MR_NAME2.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Tanggal</td>
												<td width="89%">: '.$RSVSTARTDVa.' s.d. '.$MRENDDVa.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Waktu</td>
												<td width="89%">: Pukul '.$MRSTARTTVa.' s.d. '.$MRENDTVa.' WIB</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">STATUS</td>
												<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: PERLU PEMBARUAN.</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">&nbsp;</td>
												<td width="89%">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Silahkan buka kembali pesanan Anda, lalu perbaharui tempat atau waktu pelaksanaan rapat.</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
											</tr>
											<tr>
												<td style="vertical-align:top">&nbsp;</td>
												<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Hormat kami,</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
											</tr>
											<tr>
												<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">ttd</td>
											</tr>
											<tr>
											<td style="vertical-align:top">&nbsp;</td>
												<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>';
						$output		.= '</table>';
						//send email
						@mail($toMail, $subject, $output, $headers);
					endforeach;
				}
				
				$sql_09 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE09'";
				$this->db->query($sql_09);
				
				$sql_10 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE10'";
				$this->db->query($sql_10);
				
				$sql_03 	= "UPDATE tbl_meeting_room SET MR_STATD = 'Booked' WHERE MR_CODE = '$MR_CODE03'";
				$this->db->query($sql_02);
				
				// BATLKAN SEMUA PEMESANAN ATAS RUANGAN BERSANGKUTAN YANG DIBOOKING PADA TANGGAL DAN JAM YANG SAMA
				$sql_09a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE09' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_09a);
				
				$sql_10a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE10' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_10a);
			}		
			elseif($MR_CODE == $MR_CODE08)			// MR-AB Useable to MR1 and MR1
			{
				// SENT MAIL
				$sqlColMC	= "tbl_reservation WHERE CATEG_CODE2 IN ('$MR_CODE01','$MR_CODE02','$MR_CODE09','$MR_CODE10')
								AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$resColMC	= $this->db->count_all($sqlColMC);
				if($resColMC > 0)
				{
					$sqlColMail	= "SELECT RSV_CODE, RSV_MAIL, CATEG_CODE2, RSV_STARTD2, RSV_ENDD2, RSV_STARTT2, RSV_ENDT2
									FROM tbl_reservation WHERE CATEG_CODE2 IN ('$MR_CODE01','$MR_CODE02','$MR_CODE09','$MR_CODE10')
										AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
					$resColMail = $this->db->query($sqlColMail)->result();
					foreach($resColMail as $rowColM) :
						$RSV_MAILC 		= $rowColM->RSV_MAIL;
						$RSV_CODE2 		= $rowColM->RSV_CODE;
						$CATEG_CODE2 	= $rowColM->CATEG_CODE2;
						$RSV_STARTD2	= $rowColM->RSV_STARTD2;
						$RSV_ENDD2 		= $rowColM->RSV_ENDD2;
						$RSV_STARTT2 	= $rowColM->RSV_STARTT2;
						$RSV_ENDT2 		= $rowColM->RSV_ENDT2;
						
						$MR_NAME2		= '';
						$sqlMR2			= "SELECT MR_NAME FROM tbl_meeting_room WHERE MR_CODE = '$CATEG_CODE2'";
						$resMR2			= $this->db->query($sqlMR2)->result();
						foreach($resMR2 as $rowMR2) :
							$MR_NAME2 	= $rowMR2->MR_NAME;		
						endforeach;
						
						$RSVSTARTDVa	= date('d/m/Y', strtotime($RSV_STARTD2));
						$MRENDDVa		= date('d/m/Y', strtotime($RSV_ENDD2));
						$MRSTARTTVa		= date('H:i', strtotime($RSV_STARTT2));
						$MRENDTVa		= date('H:i', strtotime($RSV_ENDT2));
						
						$toMail		= ''.$RSV_MAILC.'';
						$headers 	= 'MIME-Version: 1.0' . "\r\n";
						$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
						$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
						$subject 	= "Konfirmasi Pemesanan Ruang Rapat";
						$output		= '';
						$output		.= '<table width="100%" border="0">
											<tr>
												<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3">Dear,</td>
											</tr>
											<tr>
												<td colspan="3">Assalamu \'alaikum wr.wb.</td>
											</tr>
											<tr>
												<td colspan="3">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Pemesanan Ruang Rapat Anda sudah dikonfirmasi oleh admin. Pesanan Anda:</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">ID</td>
												<td width="89%">: '.$RSV_CODE2.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Ruang Rapat</td>
												<td width="89%">: '.$MR_NAME2.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Tanggal</td>
												<td width="89%">: '.$RSVSTARTDVa.' s.d. '.$MRENDDVa.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Waktu</td>
												<td width="89%">: Pukul '.$MRSTARTTVa.' s.d. '.$MRENDTVa.' WIB</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">STATUS</td>
												<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: PERLU PEMBARUAN.</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">&nbsp;</td>
												<td width="89%">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Silahkan buka kembali pesanan Anda, lalu perbaharui tempat atau waktu pelaksanaan rapat.</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
											</tr>
											<tr>
												<td style="vertical-align:top">&nbsp;</td>
												<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Hormat kami,</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
											</tr>
											<tr>
												<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">ttd</td>
											</tr>
											<tr>
											<td style="vertical-align:top">&nbsp;</td>
												<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>';
						$output		.= '</table>';
						//send email
						@mail($toMail, $subject, $output, $headers);
					endforeach;
				}
				
				$sql_01 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE01'";
				$this->db->query($sql_01);
				
				$sql_02 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE02'";
				$this->db->query($sql_02);
				
				$sql_08 	= "UPDATE tbl_meeting_room SET MR_STATD = 'Booked' WHERE MR_CODE = '$MR_CODE08'";
				$this->db->query($sql_08);
				
				$sql_09 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE09'";
				$this->db->query($sql_09);
				
				$sql_10 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE10'";
				$this->db->query($sql_10);
				
				// BATLKAN SEMUA PEMESANAN ATAS RUANGAN BERSANGKUTAN YANG DIBOOKING PADA TANGGAL DAN JAM YANG SAMA
				$sql_01a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE01' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_01a);
				
				$sql_02a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE02' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_02a);
				
				$sql_09a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE09' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_09a);
				
				$sql_10a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE10' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_10a);
			}
			elseif($MR_CODE == $MR_CODE09)
			{
				// SENT MAIL
				$sqlColMC	= "tbl_reservation WHERE CATEG_CODE2 IN ('$MR_CODE02','$MR_CODE03','$MR_CODE08','$MR_CODE10')
								AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$resColMC	= $this->db->count_all($sqlColMC);
				if($resColMC > 0)
				{
					$sqlColMail	= "SELECT RSV_CODE, RSV_MAIL, CATEG_CODE2, RSV_STARTD2, RSV_ENDD2, RSV_STARTT2, RSV_ENDT2
									FROM tbl_reservation WHERE CATEG_CODE2 IN ('$MR_CODE02','$MR_CODE03','$MR_CODE08','$MR_CODE10')
										AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
					$resColMail = $this->db->query($sqlColMail)->result();
					foreach($resColMail as $rowColM) :
						$RSV_MAILC 		= $rowColM->RSV_MAIL;
						$RSV_CODE2 		= $rowColM->RSV_CODE;
						$CATEG_CODE2 	= $rowColM->CATEG_CODE2;
						$RSV_STARTD2	= $rowColM->RSV_STARTD2;
						$RSV_ENDD2 		= $rowColM->RSV_ENDD2;
						$RSV_STARTT2 	= $rowColM->RSV_STARTT2;
						$RSV_ENDT2 		= $rowColM->RSV_ENDT2;
						
						$MR_NAME2		= '';
						$sqlMR2			= "SELECT MR_NAME FROM tbl_meeting_room WHERE MR_CODE = '$CATEG_CODE2'";
						$resMR2			= $this->db->query($sqlMR2)->result();
						foreach($resMR2 as $rowMR2) :
							$MR_NAME2 	= $rowMR2->MR_NAME;		
						endforeach;
						
						$RSVSTARTDVa	= date('d/m/Y', strtotime($RSV_STARTD2));
						$MRENDDVa		= date('d/m/Y', strtotime($RSV_ENDD2));
						$MRSTARTTVa		= date('H:i', strtotime($RSV_STARTT2));
						$MRENDTVa		= date('H:i', strtotime($RSV_ENDT2));
						
						$toMail		= ''.$RSV_MAILC.'';
						$headers 	= 'MIME-Version: 1.0' . "\r\n";
						$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
						$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
						$subject 	= "Konfirmasi Pemesanan Ruang Rapat";
						$output		= '';
						$output		.= '<table width="100%" border="0">
											<tr>
												<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3">Dear,</td>
											</tr>
											<tr>
												<td colspan="3">Assalamu \'alaikum wr.wb.</td>
											</tr>
											<tr>
												<td colspan="3">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Pemesanan Ruang Rapat Anda sudah dikonfirmasi oleh admin. Pesanan Anda:</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">ID</td>
												<td width="89%">: '.$RSV_CODE2.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Ruang Rapat</td>
												<td width="89%">: '.$MR_NAME2.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Tanggal</td>
												<td width="89%">: '.$RSVSTARTDVa.' s.d. '.$MRENDDVa.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Waktu</td>
												<td width="89%">: Pukul '.$MRSTARTTVa.' s.d. '.$MRENDTVa.' WIB</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">STATUS</td>
												<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: PERLU PEMBARUAN.</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">&nbsp;</td>
												<td width="89%">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Silahkan buka kembali pesanan Anda, lalu perbaharui tempat atau waktu pelaksanaan rapat.</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
											</tr>
											<tr>
												<td style="vertical-align:top">&nbsp;</td>
												<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Hormat kami,</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
											</tr>
											<tr>
												<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">ttd</td>
											</tr>
											<tr>
											<td style="vertical-align:top">&nbsp;</td>
												<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>';
						$output		.= '</table>';
						//send email
						@mail($toMail, $subject, $output, $headers);
					endforeach;
				}
				
				$sql_02 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE02'";
				$this->db->query($sql_02);
				
				$sql_03 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE03'";
				$this->db->query($sql_03);
				
				$sql_08 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE08'";
				$this->db->query($sql_08);
				
				$sql_09 	= "UPDATE tbl_meeting_room SET MR_STATD = 'Booked' WHERE MR_CODE = '$MR_CODE09'";
				$this->db->query($sql_09);
				
				$sql_10 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE10'";
				$this->db->query($sql_10);
				
				// BATLKAN SEMUA PEMESANAN ATAS RUANGAN BERSANGKUTAN YANG DIBOOKING PADA TANGGAL DAN JAM YANG SAMA
				$sql_02a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE02' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_02a);
				
				$sql_03a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE03' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_03a);
				
				$sql_08a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE08' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_08a);
				
				$sql_10a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE10' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_10a);
			}
			elseif($MR_CODE == $MR_CODE10)
			{
				// SENT MAIL
				$sqlColMC	= "tbl_reservation WHERE CATEG_CODE2 IN ('$MR_CODE01','$MR_CODE02','$MR_CODE08','$MR_CODE09')
								AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$resColMC	= $this->db->count_all($sqlColMC);
				if($resColMC > 0)
				{
					$sqlColMail	= "SELECT RSV_CODE, RSV_MAIL, CATEG_CODE2, RSV_STARTD2, RSV_ENDD2, RSV_STARTT2, RSV_ENDT2
									FROM tbl_reservation WHERE CATEG_CODE2 IN ('$MR_CODE01','$MR_CODE02','$MR_CODE08','$MR_CODE09')
										AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
					$resColMail = $this->db->query($sqlColMail)->result();
					foreach($resColMail as $rowColM) :
						$RSV_MAILC 		= $rowColM->RSV_MAIL;
						$RSV_CODE2 		= $rowColM->RSV_CODE;
						$CATEG_CODE2 	= $rowColM->CATEG_CODE2;
						$RSV_STARTD2	= $rowColM->RSV_STARTD2;
						$RSV_ENDD2 		= $rowColM->RSV_ENDD2;
						$RSV_STARTT2 	= $rowColM->RSV_STARTT2;
						$RSV_ENDT2 		= $rowColM->RSV_ENDT2;
						
						$MR_NAME2		= '';
						$sqlMR2			= "SELECT MR_NAME FROM tbl_meeting_room WHERE MR_CODE = '$CATEG_CODE2'";
						$resMR2			= $this->db->query($sqlMR2)->result();
						foreach($resMR2 as $rowMR2) :
							$MR_NAME2 	= $rowMR2->MR_NAME;		
						endforeach;
						
						$RSVSTARTDVa	= date('d/m/Y', strtotime($RSV_STARTD2));
						$MRENDDVa		= date('d/m/Y', strtotime($RSV_ENDD2));
						$MRSTARTTVa		= date('H:i', strtotime($RSV_STARTT2));
						$MRENDTVa		= date('H:i', strtotime($RSV_ENDT2));
						
						$toMail		= ''.$RSV_MAILC.'';
						$headers 	= 'MIME-Version: 1.0' . "\r\n";
						$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
						$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
						$subject 	= "Konfirmasi Pemesanan Ruang Rapat";
						$output		= '';
						$output		.= '<table width="100%" border="0">
											<tr>
												<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3">Dear,</td>
											</tr>
											<tr>
												<td colspan="3">Assalamu \'alaikum wr.wb.</td>
											</tr>
											<tr>
												<td colspan="3">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Pemesanan Ruang Rapat Anda sudah dikonfirmasi oleh admin. Pesanan Anda:</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">ID</td>
												<td width="89%">: '.$RSV_CODE2.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Ruang Rapat</td>
												<td width="89%">: '.$MR_NAME2.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Tanggal</td>
												<td width="89%">: '.$RSVSTARTDVa.' s.d. '.$MRENDDVa.'</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">Waktu</td>
												<td width="89%">: Pukul '.$MRSTARTTVa.' s.d. '.$MRENDTVa.' WIB</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">STATUS</td>
												<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: PERLU PEMBARUAN.</td>
											</tr>
											<tr>
												<td width="2%" style="vertical-align:top">&nbsp;</td>
												<td width="9%">&nbsp;</td>
												<td width="89%">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Silahkan buka kembali pesanan Anda, lalu perbaharui tempat atau waktu pelaksanaan rapat.</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
											</tr>
											<tr>
												<td style="vertical-align:top">&nbsp;</td>
												<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Hormat kami,</td>
											</tr>

											<tr>
												<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
											</tr>
											<tr>
												<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">ttd</td>
											</tr>
											<tr>
											<td style="vertical-align:top">&nbsp;</td>
												<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
											</tr>
											<tr>
												<td colspan="3" style="vertical-align:top">&nbsp;</td>
											</tr>';
						$output		.= '</table>';
						//send email
						@mail($toMail, $subject, $output, $headers);
					endforeach;
				}
				
				$sql_01 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE01'";
				$this->db->query($sql_01);
				
				$sql_02 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE02'";
				$this->db->query($sql_02);
				
				$sql_03 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE03'";
				$this->db->query($sql_03);
				
				$sql_08 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE08'";
				$this->db->query($sql_08);
				
				$sql_09 	= "UPDATE tbl_meeting_room SET MR_STARTD = '$MR_STARTD', MR_ENDD = '$MR_ENDD', MR_STARTT = '$MR_STARTT', MR_ENDT = '$MR_ENDT',
								MR_NOTES = '$MR_NOTES', MR_SUBMITTER = '$MR_SUBMITTER', MR_STAT = $MRSTAT, MR_STATD = 'Reserved' WHERE MR_CODE = '$MR_CODE09'";
				$this->db->query($sql_09);
				
				$sql_10 	= "UPDATE tbl_meeting_room SET MR_STATD = 'Booked' WHERE MR_CODE = '$MR_CODE10'";
				$this->db->query($sql_10);
				
				// BATLKAN SEMUA PEMESANAN ATAS RUANGAN BERSANGKUTAN YANG DIBOOKING PADA TANGGAL DAN JAM YANG SAMA
				$sql_01a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE01' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_01a);
				
				$sql_02a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE02' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_02a);
				
				$sql_08a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE08' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_08a);
				
				$sql_09a	= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE2 = '$MR_CODE09' AND RSV_STAT = '2' AND (RSV_STARTD2 BETWEEN '$MR_STARTD' AND '$MR_ENDD')";
				$this->db->query($sql_09a);
			}
		}
		
		function count_all_BL2()  // MR
		{
			$sql	= "tbl_reservation WHERE RSV_CATEG = 'MR' AND RSV_STAT NOT IN (5,6,7)";
			return $this->db->count_all($sql);
		}
		
		function get_all_BL2()  // MR
		{
			$sql = "SELECT * FROM tbl_reservation WHERE RSV_CATEG = 'MR' AND RSV_STAT NOT IN (3,4,5,6,7)";
			return $this->db->query($sql);
		}
		
		// ADD ON 12 FEBRUARI 2018
		public function get_eventsC($start, $end, $MR_CODE, $RSV_EMPID)  // MR
		{
			if($MR_CODE == "All")
			{
				$sql 	= "tbl_reservation WHERE RSV_CATEG = 'MR' AND RSV_STARTD2 >= '$start' AND RSV_ENDD2 <= '$end' AND RSV_EMPID = '$RSV_EMPID'";
			}
			else
			{
				$sql 	= "tbl_reservation WHERE RSV_CATEG = 'MR' AND RSV_STARTD2 >= '$start' AND RSV_ENDD2 <= '$end' AND CATEG_CODE = '$MR_CODE' AND RSV_STAT NOT IN (5)";
			}
			return $this->db->count_all($sql);
		}
		
		public function get_events($start, $end, $MR_CODE, $RSV_EMPID)  // MR
		{
			//return $this->db->where("RSV_STARTD2 >=", $start)->where("RSV_ENDD2 <=", $end)->where("CATEG_CODE =", $MR_CODE)->get("tbl_reservation");
			if($MR_CODE == 'MR-180001')
			{
				$MR_CODE = "'MR-180001','MR-180008','MR-180010'";
			}
			elseif($MR_CODE == 'MR-180002')
			{
				$MR_CODE = "'MR-180002','MR-180008','MR-180009','MR-180010'";
			}
			elseif($MR_CODE == 'MR-180003')
			{
				$MR_CODE = "'MR-180003','MR-180009','MR-180010'";
			}
			elseif($MR_CODE == 'MR-180008')
			{
				$MR_CODE = "'MR-180001','MR-180002','MR-180008','MR-180009','MR-180010'";
			}
			elseif($MR_CODE == 'MR-180009')
			{
				$MR_CODE = "'MR-180002','MR-180003','MR-180008','MR-180009','MR-180010'";
			}
			elseif($MR_CODE == 'MR-180010')
			{
				$MR_CODE = "'MR-180001','MR-180002','MR-180003','MR-180008','MR-180009','MR-180010'";
			}
			elseif($MR_CODE == 'All')
			{
				$sql 	= "SELECT * FROM tbl_reservation WHERE RSV_STARTD2 >= '$start' AND RSV_ENDD2 <= '$end' AND RSV_EMPID = '$RSV_EMPID'";
				return $this->db->query($sql);
			}
			else
			{
				$MR_CODE = "'$MR_CODE'";
			}
				
			$sql 	= "SELECT * FROM tbl_reservation WHERE RSV_STARTD2 >= '$start' AND RSV_ENDD2 <= '$end' AND CATEG_CODE IN ($MR_CODE) AND RSV_STAT NOT IN (5)";
			return $this->db->query($sql);
		}
		
		public function get_eventsCVH($start, $end, $VH_CODE)  // VH
		{
			$sql 	= "tbl_reservation WHERE RSV_CATEG = 'VH' AND RSV_STARTD2 >= '$start' AND RSV_ENDD2 <= '$end' AND CATEG_CODE = '$VH_CODE' AND RSV_STAT NOT IN (5)";
			return $this->db->count_all($sql);
		}
		
		public function get_eventsVH($start, $end, $VH_CODE)  // VH
		{
			if($VH_CODE == "")
			{
				$sql 	= "SELECT A.*, B.VH_NOPOL, B.VH_MEREK FROM tbl_reservation A
						  INNER JOIN tbl_vehicle B ON B.VH_CODE = A.CATEG_CODE
						  WHERE A.RSV_CATEG = 'VH'";
			}
			else
			{
				$sql 	= "SELECT A.*, B.VH_NOPOL, B.VH_MEREK FROM tbl_reservation A
						  INNER JOIN tbl_vehicle B ON B.VH_CODE = A.CATEG_CODE
						  WHERE A.RSV_STARTD2 >= '$start' AND A.RSV_ENDD2 <= '$end' AND A.RSV_CATEG = 'VH' AND A.CATEG_CODE = '$VH_CODE' AND A.RSV_STAT NOT IN (5)";
				//$sql 	= "SELECT * FROM tbl_reservation WHERE RSV_STARTD2 >= '$start' AND RSV_ENDD2 <= '$end' AND RSV_CATEG = 'VH' AND CATEG_CODE = '$VH_CODE'";	
			}
			return $this->db->query($sql);
		}
		
		public function get_eventsAdd($start, $end, $MR_CODE)  // MR
		{
			//return $this->db->where("RSV_STARTD2 >=", $start)->where("RSV_ENDD2 <=", $end)->where("CATEG_CODE =", $MR_CODE)->get("tbl_reservation");
			
			$sql 	= "SELECT * FROM tbl_reservation WHERE RSV_STARTD2 >= '$start' AND RSV_ENDD2 <= '$end' AND CATEG_CODE = '$MR_CODE'";
			return $this->db->query($sql);
		}
		
		public function add_event($data)  // MR
		{
			$this->db->insert("tbl_reservation", $data);
		}
		
		public function get_event($id)  // MR
		{
			return $this->db->where("ID", $id)->get("calendar_events");
		}
		
		public function update_event($id, $data)  // MR
		{
			$this->db->where("ID", $id)->update("calendar_events", $data);
		}
		
		public function delete_event($id)  // MR
		{
			$this->db->where("ID", $id)->delete("calendar_events");
		}
	// --------------- END - MEETING ROOM PROCESS ---------------
	
	// --------------- START - VEHICLE PROCESS ---------------
		function get_RESERV($RSV_CODE) // VH
		{
			$sql = "SELECT * FROM tbl_reservation
					WHERE RSV_CODE = '$RSV_CODE'";
			return $this->db->query($sql);
		}
		
		function get_VEHICLE($CATEG_CODE) // VH
		{
			$sql = "SELECT * FROM tbl_vehicle
					WHERE VH_CODE = '$CATEG_CODE'";
			return $this->db->query($sql);
		}
		
		function get_DRIVER($DRIVER_CODE) // VH
		{
			$sql = "SELECT * FROM tbl_driver
					WHERE DRIVER_CODE = '$DRIVER_CODE'";
			return $this->db->query($sql);
		}
		
		function count_all_YourVH($DefEmp_ID)  // VH
		{
			$sql	= "tbl_reservation WHERE RSV_EMPID = '$DefEmp_ID' AND RSV_CATEG = 'VH'";
			return $this->db->count_all($sql);
		}
		
		function get_all_YourVH($DefEmp_ID)  // VH
		{
			$sql = "SELECT * FROM tbl_reservation WHERE RSV_EMPID = '$DefEmp_ID' AND RSV_CATEG = 'VH'";
			return $this->db->query($sql);
		}
		
		function updateVHStat($VH_CODE, $UpdVHS, $UpdDR, $RSV_STAT, $RSV_MAIL, $RSV_CODE, $CATEG_CODE, $DRIVER_CODE) // VH
		{
			if($RSV_STAT == 1)
			{
				$RSV_STATD 	= 'New';
			}
			elseif($RSV_STAT == 2)
			{
				$RSV_STATD 	= 'Awaiting';
			}
			elseif($RSV_STAT == 3)
			{
				$RSV_STATD 	= 'Approved';
			}
			elseif($RSV_STAT == 4)
			{
				$RSV_STATD 	= 'Reschedule';
			}
			elseif($RSV_STAT == 5)
			{
				$RSV_STATD 	= 'Rejected';
			}
			elseif($RSV_STAT == 6)
			{
				$RSV_STATD 	= 'Close';
			}
			elseif($RSV_STAT == 8)
			{
				$RSV_STATD 	= 'in used';
			}
			else
			{
				$RSV_STATD 	= 'Awaiting';
			}
			
			$this->db->where('VH_CODE', $VH_CODE);
			$this->db->update('tbl_vehicle', $UpdVHS);
			
			$this->db->where('DRIVER_CODE', $DRIVER_CODE);
			$this->db->update('tbl_driver', $UpdDR);
			
			//GET VEHICLE
			$CountVH	= "tbl_vehicle WHERE VH_CODE = '$VH_CODE'";
			$HCountVH	= $this->db->count_all($CountVH);
			if($HCountVH > 0)
			{
				$queryVH	= "SELECT * FROM tbl_vehicle WHERE VH_CODE = '$VH_CODE'";
				$HQ_VH		= $this->db->query($queryVH)->result_array();
				foreach($HQ_VH as $rowHQ_VH)
				{
					$VH_MEREK = $rowHQ_VH['VH_MEREK'];
					$VH_NOPOL = $rowHQ_VH['VH_NOPOL'];
				}
			}
			
			//GET DRIVER
			$DRIVER		= '';
			$sqlDR 		= "SELECT DRIVER FROM tbl_driver WHERE DRIVER_CODE = '$DRIVER_CODE'";
			$resDR 		= $this->db->query($sqlDR)->result();
			foreach($resDR as $rowDR) :
				$DRIVER 	= $rowDR->DRIVER;	
			endforeach;
			
			//GET RSV Date
			$CountRSV	= "tbl_reservation WHERE RSV_CODE = '$RSV_CODE'";
			$HCRSV		= $this->db->count_all($CountRSV);
			if($HCRSV > 0)
			{
				$QRSV	= "SELECT * FROM tbl_reservation WHERE RSV_CODE = '$RSV_CODE'";	
				$HQRSV	= $this->db->query($QRSV)->result_array();
				foreach($HQRSV as $rowHQRSV)
				{
					$RSV_STARTD 	= date('d/m/Y',strtotime($rowHQRSV['RSV_STARTD2']));
					$RSV_ENDD		= date('d/m/Y',strtotime($rowHQRSV['RSV_ENDD2']));
					$RSV_STARTT		= date('H:i',strtotime($rowHQRSV['RSV_STARTT2']));
					$RSV_ENDT		= date('H:i',strtotime($rowHQRSV['RSV_ENDT2']));
				}
			}
			
			// SENT MAIL TO SUBMITTER
				$toMail		= ''.$RSV_MAIL.'';
				$headers 	= 'MIME-Version: 1.0' . "\r\n";
				$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
				$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
				$subject 	= "Konfirmasi Pemesanan Kendaraan";
				$output		= '';
				$output		.= '<table width="100%" border="0">
									<tr>
										<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3">Dear,</td>
									</tr>
									<tr>
										<td colspan="3">Assalamu \'alaikum wr.wb.</td>
									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Pemesanan Kendaraan Anda sudah dikonfirmasi, bahwa pesanan Anda:</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">&nbsp;</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">ID</td>
										<td width="89%">: '.$RSV_CODE.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Kendaraan</td>
										<td width="89%">: '.$VH_MEREK.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">No. Polisi</td>
										<td width="89%">: '.$VH_NOPOL.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Driver</td>
										<td width="89%">: '.$DRIVER.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Tanggal</td>
										<td width="89%">: '.$RSV_STARTD.' s.d. '.$RSV_ENDD.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Waktu</td>
										<td width="89%">: Pukul '.$RSV_STARTT.' s.d. '.$RSV_ENDT.' WIB</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">STATUS</td>
										<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: '.$RSV_STATD.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">&nbsp;</td>
										<td width="89%">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
									</tr>
									<tr>
										<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Hormat kami,</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
									</tr>
									<tr>
										<td style="vertical-align:top">&nbsp;</td>
									<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">ttd</td>
									</tr>
									<tr>
									<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">&nbsp;</td>
									</tr>';
				$output		.= '</table>';
				//send email
				@mail($toMail, $subject, $output, $headers);
				
			$VHSTAT			= 0;
			if($RSV_STAT == 3 || $RSV_STAT == 4 || $RSV_STAT == 6)
			{
				$VHSTAT		= 1;
			}
			
			// BATLKAN SEMUA PEMESANAN KENDARAAN YANG SAMA
			$cRVK	= "tbl_reservation WHERE CATEG_CODE = '$CATEG_CODE'
						AND RSV_STAT = '2'";
			$resRVK	= $this->db->count_all($cRVK);
			if($resRVK > 0)
			{
				//CEK EMAIL SUBMITER Untuk Update Kendaraan
				$qMail		= "SELECT * FROM tbl_reservation WHERE CATEG_CODE = '$CATEG_CODE'
								AND RSV_STAT = '2'";
				$resMail	= $this->db->query($qMail)->result_array();
				foreach($resMail as $rowMail)
				{
					$RSV_MAIL_S	= $rowMail['RSV_MAIL'];
					//SENT MAIL TO SUBMITTER Untuk Update kendaraan
					$toMail		= ''.$RSV_MAIL_S.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Konfirmasi Pemesanan Kendaraan";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Dear,</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Pemesanan Kendaraan Anda sudah dikonfirmasi, bahwa pesanan Anda:</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Kendaraan</td>
											<td width="89%">: '.$VH_MEREK.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">No. Polisi</td>
											<td width="89%">: '.$VH_NOPOL.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Driver</td>
											<td width="89%">: '.$DRIVER.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSV_STARTD.' s.d. '.$RSV_ENDD.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$RSV_STARTT.' s.d. '.$RSV_ENDT.' WIB</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: PERLU PEMBARUAN DATA KENDARAAN</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Silahkan buka kembali pesanan Anda, lalu perbaharui kendaraan yang dipesan.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);
				}
				
				$vRVK		= "UPDATE tbl_reservation SET RSV_STAT = 7, CATEG_CODE = '', CATEG_CODE2 = ''
								WHERE CATEG_CODE = '$CATEG_CODE' AND RSV_STAT = '2'";
				$this->db->query($vRVK);	
			}
			
			// BATLKAN SEMUA PEMESANAN DRIVER YANG SAMA
			$cDR	= "tbl_reservation WHERE DRIVER_CODE = '$DRIVER_CODE'
						AND RSV_STAT = '2'";
			$resDR	= $this->db->count_all($cDR);
			if($resDR > 0)
			{
				//CEK EMAIL SUBMITER Untuk Update Driver
				$qMailD		= "SELECT * FROM tbl_reservation WHERE DRIVER_CODE = '$DRIVER_CODE'
								AND RSV_STAT = '2'";
				$resMailD	= $this->db->query($qMailD)->result_array();
				foreach($resMailD as $rowMailD)
				{
					$RSV_MAIL_DS	= $rowMailD['RSV_MAIL'];
					//SENT MAIL TO SUBMITTER Untuk Update Driver
					$toMail		= ''.$RSV_MAIL_DS.'';
					$headers 	= 'MIME-Version: 1.0' . "\r\n";
					$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
					$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
					$subject 	= "Konfirmasi Pemesanan Kendaraan";
					$output		= '';
					$output		.= '<table width="100%" border="0">
										<tr>
											<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3">Dear,</td>
										</tr>
										<tr>
											<td colspan="3">Assalamu \'alaikum wr.wb.</td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Pemesanan Kendaraan Anda sudah dikonfirmasi, bahwa pesanan Anda:</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">ID</td>
											<td width="89%">: '.$RSV_CODE.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Kendaraan</td>
											<td width="89%">: '.$VH_MEREK.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">No. Polisi</td>
											<td width="89%">: '.$VH_NOPOL.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Driver</td>
											<td width="89%">: '.$DRIVER.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Tanggal</td>
											<td width="89%">: '.$RSV_STARTD.' s.d. '.$RSV_ENDD.'</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">Waktu</td>
											<td width="89%">: Pukul '.$RSV_STARTT.' s.d. '.$RSV_ENDT.' WIB</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">STATUS</td>
											<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: PERLU PEMBARUAN DATA DRIVER</td>
										</tr>
										<tr>
											<td width="2%" style="vertical-align:top">&nbsp;</td>
											<td width="9%">&nbsp;</td>
											<td width="89%">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Silahkan buka kembali pesanan Anda, lalu perbaharui driver yang dipesan.</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Hormat kami,</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
										</tr>
										<tr>
											<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">ttd</td>
										</tr>
										<tr>
										<td style="vertical-align:top">&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align:top">&nbsp;</td>
										</tr>';
					$output		.= '</table>';
					//send email
					@mail($toMail, $subject, $output, $headers);
				}
				
				$vDR		= "UPDATE tbl_reservation SET RSV_STAT = 7, DRIVER_CODE = ''
								WHERE DRIVER_CODE = '$DRIVER_CODE' AND RSV_STAT = '2'";
				$this->db->query($vDR);
			}
		}
		
		function CountVH_TYPE($VH_TYPEX) // VH
		{
			$sql = "tbl_vehicle WHERE VH_TYPE = '$VH_TYPEX'";
			return $this->db->count_all($sql);
		}
		
		function GETVH_NOPOL($CATEG_CODE) // VH
		{
			$sql = "SELECT VH_NOPOL FROM tbl_vehicle WHERE VH_CODE = '$CATEG_CODE'";
			return $this->db->query($sql)->result_array();
		}
		
		function count_all_VH($DefEmp_ID, $REQ_STAT)  // VH
		{
			$sql	= "tbl_reservation WHERE RSV_EMPID = '$DefEmp_ID' AND RSV_CATEG = 'VH' AND RSV_STAT IN ($REQ_STAT)";
			return $this->db->count_all($sql);
		}
		
		function get_all_VH($DefEmp_ID, $REQ_STAT)  // VH
		{
			$sql = "SELECT * FROM tbl_reservation WHERE RSV_EMPID = '$DefEmp_ID' AND RSV_CATEG = 'VH' AND RSV_STAT IN ($REQ_STAT)";
			return $this->db->query($sql);
		}
	// --------------- END - VEHICLE PROCESS ---------------
	
	// --------------- START - REST ROOM PROCESS ---------------
		// ADD ON 12 FEBRUARI 2018
		public function get_eventsC_RR($start, $end, $AR_CODE)  // MR
		{
			$sql 	= "tbl_reservation WHERE RSV_STARTD2 >= '$start' AND RSV_ENDD2 <= '$end' AND CATEG_CODE = '$AR_CODE'";
			return $this->db->count_all($sql);
		}
		
		public function get_events_RR($start, $end, $AR_CODE)  // MR
		{				
			$sql 	= "SELECT * FROM tbl_reservation WHERE RSV_STARTD2 >= '$start' AND RSV_ENDD2 <= '$end' AND CATEG_CODE = '$AR_CODE'";
			return $this->db->query($sql);
		}
		
		function updateARStat($AR_CODE, $UpdARS, $RSV_STAT, $RSV_MAIL, $RSV_CODE) // MR
		{
			$AR_CODE 		= $UpdARS['AR_CODE'];
			$AR_STARTD 		= $UpdARS['AR_STARTD'];
			$AR_ENDD 		= $UpdARS['AR_ENDD'];
			$AR_STARTT 		= $UpdARS['AR_STARTT'];
			$AR_ENDT 		= $UpdARS['AR_ENDT'];
			$AR_NOTES 		= $UpdARS['AR_NOTES'];
			$AR_SUBMITTER 	= $UpdARS['AR_ENDT'];
			$AR_STAT 		= $UpdARS['AR_STAT'];
			
			$AR_NAME		= '';
			$AR_ADDRESS		= '';
			$sqlAR 			= "SELECT AR_NAME, AR_ADDRESS FROM tbl_apartement WHERE AR_CODE = '$AR_CODE'";
			$resAR 			= $this->db->query($sqlAR)->result();
			foreach($resAR as $rowAR) :
				$AR_NAME 	= $rowAR->AR_NAME;	
				$AR_ADDRESS	= $rowAR->AR_ADDRESS;		
			endforeach;
			
			$RSVSTARTDV		= date('d/m/Y', strtotime($AR_STARTD));
			$MRENDDV		= date('d/m/Y', strtotime($AR_ENDD));
			$MRSTARTTV		= date('H:i', strtotime($AR_STARTT));
			$MRENDTV		= date('H:i', strtotime($AR_ENDT));
			
			if($RSV_STAT == 1)
			{
				$RSV_STATD 	= 'New';
			}
			elseif($RSV_STAT == 2)
			{
				$RSV_STATD 	= 'MENUNGGU';
			}
			elseif($RSV_STAT == 3)
			{
				$RSV_STATD 	= 'DISETUJUI';
			}
			elseif($RSV_STAT == 4)
			{
				$RSV_STATD 	= 'JADWAL ULANG';
			}
			elseif($RSV_STAT == 5)
			{
				$RSV_STATD 	= 'DITOLAK';
			}
			elseif($RSV_STAT == 6)
			{
				$RSV_STATD 	= 'SELESAI';
			}
			elseif($RSV_STAT == 8)
			{
				$RSV_STATD 	= 'SEDANG DIGUNAKAN';
			}
			else
			{
				$RSV_STATD 	= 'MENUNGGU';
			}
			
			$this->db->where('AR_CODE', $AR_CODE);
			$this->db->update('tbl_apartement', $UpdARS);
			
			// SENT MAIL TO SUBMITTER
				$toMail		= ''.$RSV_MAIL.'';
				$headers 	= 'MIME-Version: 1.0' . "\r\n";
				$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
				$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
				$subject 	= "Konfirmasi Pemesanan Penginapan";
				$output		= '';
				$output		.= '<table width="100%" border="0">
									<tr>
										<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3">Dear,</td>
									</tr>
									<tr>
										<td colspan="3">Assalamu \'alaikum wr.wb.</td>
									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Pemesanan kamar penginapan Anda sudah dikonfirmasi, bahwa pesanan Anda:</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">&nbsp;</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">ID</td>
										<td width="89%">: '.$RSV_CODE.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Kamar</td>
										<td width="89%">: '.$AR_NAME.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Alamat</td>
										<td width="89%">: '.$AR_ADDRESS.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Tanggal</td>
										<td width="89%">: '.$RSVSTARTDV.' s.d. '.$MRENDDV.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">Waktu</td>
										<td width="89%">: Pukul '.$MRSTARTTV.' s.d. '.$MRENDTV.' WIB</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">STATUS</td>
										<td width="89%" style="text-decoration:underline; font-weight:bold; color:#F00">: '.$RSV_STATD.'</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">&nbsp;</td>
										<td width="89%">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
									</tr>
									<tr>
										<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Hormat kami,</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">NKE Smart System Dev. Team</td>
									</tr>
									<tr>
										<td style="vertical-align:top">&nbsp;</td>
									<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">ttd</td>
									</tr>
									<tr>
									<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Dian Hermanto</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">&nbsp;</td>
									</tr>';
				$output		.= '</table>';
				//send email
				@mail($toMail, $subject, $output, $headers);
			
			$MRSTAT			= 0;
			if($RSV_STAT == 3 || $RSV_STAT == 4 || $RSV_STAT == 6)
			{
				$MRSTAT		= 1;
			}
		}
		
		function count_all_YourRR($DefEmp_ID)  // MR
		{
			$sql	= "tbl_reservation WHERE RSV_EMPID = '$DefEmp_ID' AND RSV_CATEG = 'RR'";
			return $this->db->count_all($sql);
		}
		
		function get_all_YourRR($DefEmp_ID)  // MR
		{
			$sql = "SELECT * FROM tbl_reservation WHERE RSV_EMPID = '$DefEmp_ID' AND RSV_CATEG = 'RR'";
			return $this->db->query($sql);
		}
		
		function count_und_employee() //MR - Undangan Kary utk meeting
		{
			$sql	= "tbl_employee WHERE Emp_Status = '1'";	
			return $this->db->count_all($sql);
		}
		
		function get_und_employee() //MR - Undangan Kary utk meeting
		{
			$sql	= "SELECT Emp_ID, First_Name, Last_Name, Email FROM tbl_employee WHERE Emp_Status = '1'";
			return $this->db->query($sql);
		}
		
		public function count_BLVH($RSV_STAT)  // MR
		{
			if($RSV_STAT == "")
			{
				$sql 	= "tbl_reservation WHERE RSV_CATEG = 'VH' AND RSV_STAT NOT IN (6,7)";
			}
			else
			{
				$sql 	= "tbl_reservation WHERE RSV_CATEG = 'VH' AND RSV_STAT = '$RSV_STAT' AND RSV_STAT NOT IN (6,7)";
			}
			return $this->db->count_all($sql);
		}
		
		public function get_BLVH($RSV_STAT)  // MR
		{
			if($RSV_STAT == "")
			{
				$sql 	= "SELECT A.*, B.VH_NOPOL, B.VH_MEREK, C.DRIVER FROM tbl_reservation A
						  INNER JOIN tbl_vehicle B ON B.VH_CODE = A.CATEG_CODE
						  INNER JOIN tbl_driver C ON C.DRIVER_CODE = A.DRIVER_CODE
						  WHERE A.RSV_CATEG = 'VH'";
			}
			else
			{
				$sql 	= "SELECT A.*, B.VH_NOPOL, B.VH_MEREK, C.DRIVER FROM tbl_reservation A
						  INNER JOIN tbl_vehicle B ON B.VH_CODE = A.CATEG_CODE
						  INNER JOIN tbl_driver C ON C.DRIVER_CODE = A.DRIVER_CODE
						  WHERE A.RSV_CATEG = 'VH' AND A.RSV_STAT = '$RSV_STAT'";
				//$sql 	= "SELECT * FROM tbl_reservation WHERE RSV_STARTD2 >= '$start' AND RSV_ENDD2 <= '$end' AND RSV_CATEG = 'VH' AND CATEG_CODE = '$VH_CODE'";	
			}
			return $this->db->query($sql);
		}
		
		public function getInvEmail($RSV_CODE)
		{
			$sql = "SELECT RSV_INVIT FROM tbl_reservation WHERE RSV_CODE = '$RSV_CODE'";	
			return $this->db->query($sql)->result_array();
		}
		
		public function update_close_Expire()
		{
			date_default_timezone_set("Asia/Jakarta");
			$dateSYs	= date('Y-m-d H:i:s');
			//Update Status Approve to close jika RSV_ENDD sudah lewat dari tanggal system
			$sqlA = "UPDATE tbl_reservation SET RSV_STAT = 6, RSV_MEMO = 'Close On System' WHERE RSV_STAT = 3 AND RSV_ENDD < '$dateSYs'";	
			$this->db->query($sqlA);
			
			//Update Status InUsed to close jika RSV_ENDD sudah lewat dari tanggal system
			$sqlU = "UPDATE tbl_reservation SET RSV_STAT = 6, RSV_MEMO = 'Close On System' WHERE RSV_STAT = 8 AND RSV_ENDD < '$dateSYs'";	
			$this->db->query($sqlU);
			
			//Update VH_Status = 0
			//cek RSV Stat close
			$CK_CRSV	= "tbl_reservation WHERE RSV_CATEG = 'VH' AND RSV_STAT = 6 AND RSV_ENDD < '$dateSYs'";
			$RES_RSV	= "SELECT * FROM tbl_reservation WHERE RSV_CATEG = 'VH' AND RSV_STAT = 6 AND RSV_ENDD < '$dateSYs'";
			$Count_CRSV	= $this->db->count_all($CK_CRSV);
			if($Count_CRSV > 0)
			{
				$vW_RSV	= $this->db->query($RES_RSV)->result();
				foreach($vW_RSV as $rowRSV)
				{
					$CATEG_CODE		= $rowRSV->CATEG_CODE;
					$DRIVER_CODE	= $rowRSV->DRIVER_CODE;
					//Update Kendaraan
					$sql_VH	= "UPDATE tbl_vehicle SET VH_STAT = 0 WHERE VH_CODE = '$CATEG_CODE'";
					$this->db->query($sql_VH);
					
					$sql_DR	= "UPDATE tbl_driver SET DRIVER_STAT = 0 WHERE DRIVER_CODE = '$DRIVER_CODE'";
					$this->db->query($sql_DR);
				}
			}
		}
	// --------------- END - REST ROOM PROCESS ---------------
}
?>