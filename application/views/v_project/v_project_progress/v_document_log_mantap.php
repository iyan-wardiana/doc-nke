<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 April 2015
 * File Name	= r_matbudget.php
*/
// Start : Query ini wajib dipasang disetiap halaman View
$sql = "SELECT * FROM tlanguage WHERE Language_Status = 1";
$reslang = $this->db->query($sql)->result();
foreach($reslang as $row) :
	$Language_ID	= $row->Language_ID;
endforeach;

$sql = "SELECT code_translate, transalate_$Language_ID as myTransalte FROM ttranslate";
$restrans = $this->db->query($sql)->result();
foreach($restrans as $row) :
	$code_translate	= $row->code_translate;
	if($code_translate == 'VendCode')$VendCode = $row->myTransalte;
	if($code_translate == 'VendName')$VendName = $row->myTransalte;
	if($code_translate == 'Category')$Category = $row->myTransalte;
	if($code_translate == 'displayreport')$displayreport = $row->myTransalte;
	if($code_translate == 'exporttoexcel')$exporttoexcel = $row->myTransalte;
	if($code_translate == 'Status')$Status = $row->myTransalte;
endforeach;

$Start_DateY 	= date('Y');
$Start_DateM 	= date('m');
$Start_DateD 	= date('d');
$Start_Date 	= "$Start_DateY-$Start_DateM-$Start_DateD";
// End : Query ini wajib dipasang disetiap halaman View

?>

<script type="text/javascript">
	var persisteduls=new Object()
	var ddtreemenu=new Object()
	
	ddtreemenu.closefolder="<?php echo base_url() . 'images/closed.gif'; ?>" //set image path to "closed" folder image
	ddtreemenu.openfolder="<?php echo base_url() . 'images/open.gif'; ?>" //set image path to "open" folder image
	
	//////////No need to edit beyond here///////////////////////////
	
	ddtreemenu.createTree=function(treeid, enablepersist, persistdays){
	var ultags=document.getElementById(treeid).getElementsByTagName("ul")
	if (typeof persisteduls[treeid]=="undefined")
	persisteduls[treeid]=(enablepersist==true && ddtreemenu.getCookie(treeid)!="")? ddtreemenu.getCookie(treeid).split(",") : ""
	for (var i=0; i<ultags.length; i++)
	ddtreemenu.buildSubTree(treeid, ultags[i], i)
	if (enablepersist==true){ //if enable persist feature
	var durationdays=(typeof persistdays=="undefined")? 1 : parseInt(persistdays)
	ddtreemenu.dotask(window, function(){ddtreemenu.rememberstate(treeid, durationdays)}, "unload") //save opened UL indexes on body unload
	}
	}
	
	ddtreemenu.buildSubTree=function(treeid, ulelement, index){
	ulelement.parentNode.className="submenu"
	if (typeof persisteduls[treeid]=="object"){ //if cookie exists (persisteduls[treeid] is an array versus "" string)
	if (ddtreemenu.searcharray(persisteduls[treeid], index)){
	ulelement.setAttribute("rel", "open")
	ulelement.style.display="block"
	ulelement.parentNode.style.backgroundImage="url("+ddtreemenu.openfolder+")"
	}
	else
	ulelement.setAttribute("rel", "closed")
	} //end cookie persist code
	else if (ulelement.getAttribute("rel")==null || ulelement.getAttribute("rel")==false) //if no cookie and UL has NO rel attribute explicted added by user
	ulelement.setAttribute("rel", "closed")
	else if (ulelement.getAttribute("rel")=="open") //else if no cookie and this UL has an explicit rel value of "open"
	ddtreemenu.expandSubTree(treeid, ulelement) //expand this UL plus all parent ULs (so the most inner UL is revealed!)
	ulelement.parentNode.onclick=function(e){
	var submenu=this.getElementsByTagName("ul")[0]
	if (submenu.getAttribute("rel")=="closed"){
	submenu.style.display="block"
	submenu.setAttribute("rel", "open")
	ulelement.parentNode.style.backgroundImage="url("+ddtreemenu.openfolder+")"
	}
	else if (submenu.getAttribute("rel")=="open"){
	submenu.style.display="none"
	submenu.setAttribute("rel", "closed")
	ulelement.parentNode.style.backgroundImage="url("+ddtreemenu.closefolder+")"
	}
	ddtreemenu.preventpropagate(e)
	}
	ulelement.onclick=function(e){
	ddtreemenu.preventpropagate(e)
	}
	}
	
	ddtreemenu.expandSubTree=function(treeid, ulelement){ //expand a UL element and any of its parent ULs
	var rootnode=document.getElementById(treeid)
	var currentnode=ulelement
	currentnode.style.display="block"
	currentnode.parentNode.style.backgroundImage="url("+ddtreemenu.openfolder+")"
	while (currentnode!=rootnode){
	if (currentnode.tagName=="UL"){ //if parent node is a UL, expand it too
	currentnode.style.display="block"
	currentnode.setAttribute("rel", "open") //indicate it's open
	currentnode.parentNode.style.backgroundImage="url("+ddtreemenu.openfolder+")"
	}
	currentnode=currentnode.parentNode
	}
	}
	
	ddtreemenu.flatten=function(treeid, action){ //expand or contract all UL elements
	var ultags=document.getElementById(treeid).getElementsByTagName("ul")
	for (var i=0; i<ultags.length; i++){
	ultags[i].style.display=(action=="expand")? "block" : "none"
	var relvalue=(action=="expand")? "open" : "closed"
	ultags[i].setAttribute("rel", relvalue)
	ultags[i].parentNode.style.backgroundImage=(action=="expand")? "url("+ddtreemenu.openfolder+")" : "url("+ddtreemenu.closefolder+")"
	}
	}
	
	ddtreemenu.rememberstate=function(treeid, durationdays){ //store index of opened ULs relative to other ULs in Tree into cookie
	var ultags=document.getElementById(treeid).getElementsByTagName("ul")
	var openuls=new Array()
	for (var i=0; i<ultags.length; i++){
	if (ultags[i].getAttribute("rel")=="open")
	openuls[openuls.length]=i //save the index of the opened UL (relative to the entire list of ULs) as an array element
	}
	if (openuls.length==0) //if there are no opened ULs to save/persist
	openuls[0]="none open" //set array value to string to simply indicate all ULs should persist with state being closed
	ddtreemenu.setCookie(treeid, openuls.join(","), durationdays) //populate cookie with value treeid=1,2,3 etc (where 1,2... are the indexes of the opened ULs)
	}
	
	////A few utility functions below//////////////////////
	
	ddtreemenu.getCookie=function(Name){ //get cookie value
	var re=new RegExp(Name+"=[^;]+", "i"); //construct RE to search for target name/value pair
	if (document.cookie.match(re)) //if cookie found
	return document.cookie.match(re)[0].split("=")[1] //return its value
	return ""
	}
	
	ddtreemenu.setCookie=function(name, value, days){ //set cookei value
	var expireDate = new Date()
	//set "expstring" to either future or past date, to set or delete cookie, respectively
	var expstring=expireDate.setDate(expireDate.getDate()+parseInt(days))
	document.cookie = name+"="+value+"; expires="+expireDate.toGMTString()+"; path=/";
	}
	
	ddtreemenu.searcharray=function(thearray, value){ //searches an array for the entered value. If found, delete value from array
	var isfound=false
	for (var i=0; i<thearray.length; i++){
	if (thearray[i]==value){
	isfound=true
	thearray.shift() //delete this element from array for efficiency sake
	break
	}
	}
	return isfound
	}
	
	ddtreemenu.preventpropagate=function(e){ //prevent action from bubbling upwards
	if (typeof e!="undefined")
	e.stopPropagation()
	else
	event.cancelBubble=true
	}
	
	ddtreemenu.dotask=function(target, functionref, tasktype){ //assign a function to execute to an event handler (ie: onunload)
	var tasktype=(window.addEventListener)? tasktype : "on"+tasktype
	if (target.addEventListener)
	target.addEventListener(tasktype, functionref, false)
	else if (target.attachEvent)
	target.attachEvent(tasktype, functionref)
	}
</script>
<style type="text/css">
	.treeview ul {
		margin: 0;
		padding: 0;
	}
	
	.treeview li {
		background: white url(<?php echo base_url() . 'images/list.gif'; ?>) no-repeat left center;
		list-style-type: none;
		padding-left: 22px;
		margin-bottom: 3px;
		line-height: 18px;
		font-size:14px;
	}
	
	.treeview li.submenu {
		background: white url(<?php echo base_url() . 'images/closed.gif'; ?>) no-repeat left 1px;
		cursor: hand !important;
		cursor: pointer !important;
	}
	
	.treeview li.submenu ul {
		display: none;
	}
	
	.treeview .submenu ul li {
		cursor: default;
	}
</style>
<div class="HCSSTableGenerator">
    <table width="100%" border="0">
        <tr height="20">
            <td colspan="3"><b><?php echo $h2_title; ?></b></td>
        </tr>
    </table>
</div>
<table width="100%" border="0">
    <tr height="20">
        <td><HR /></td>
    </tr>
    <tr height="20">
        <td style="text-align:center; font-weight:bold; font-size:16px">LIST OF DOCUMENT1</td>
    </tr>
    <tr height="20">
        <td style="text-align:center; font-weight:bold; font-size:16px">PT NUSA KONSTRUKSI ENJINIRING Tbk</td>
    </tr>
</table>
<table width="100%">
    <tr>
        <td colspan="2"><hr /></td>
  </tr>
  <script type="text/javascript">
	function submitform(valueexp)
	{
		getText = document.getElementById('menuID_'+valueexp).value;
		//alert(getText)
		document.getElementById('myIndex').value = valueexp;
		document.getElementById('menuID_'+valueexp).disabled = false;
		document.getElementById('indexMenu_'+valueexp).disabled = false;
		document.forms["myform"].submit();
	}
	</script>
    <tr id="showLoc">
        <td width="24%" style="vertical-align:top" nowrap>
        <form id="myform" method="post">
        	<input type="text" name="myIndex" id="myIndex" value=""  style="display:none" />
        <ul id="treemenu1" class="treeview">
        	<?php
				// LOOP 1
				$rowVal = 0;
        		$sqlA	= "SELECT * FROM tdocument WHERE doc_level = 1";
				$resDoccumentA = $this->db->query($sqlA)->result();
				foreach($resDoccumentA as $rowA) :
					$doc_codeA = $rowA->doc_code;
					$doc_levelA = $rowA->doc_level;
					$doc_indexA = $rowA->doc_index;
					$doc_parentA = $rowA->doc_parent;
					$doc_nameA = $rowA->doc_name;
					$isFileA = $rowA->isFile;
					if($isFileA == 0) // apabila isFile == 0, maka = folder
					{
						?>
                        <li> <?php echo $doc_nameA; ?>
                            <?php
                                // Cek apakah mempunyai turunan. Apabila "ya". Lakukan LOOP 2								
                                $sqlB		= "tdocument WHERE doc_parent = '$doc_codeA'";
                                $CountB 	= $this->db->count_all($sqlB);
                                if($CountB > 0)
                                {
                                    // Lakukan LOOP 2
                                    ?>
                                    <ul>
                                        <?php
                                            // LOOP 2
                                            // Cek apakah mempunyai turuan. Apabila mempunyai turunan, buatkan anak menu untuk turunan ke dua
                                            $sqlC	= "SELECT * FROM tdocument WHERE doc_parent = '$doc_codeA'";
                                            $resDoccumentC = $this->db->query($sqlC)->result();
                                            foreach($resDoccumentC as $rowC) :
                                                $doc_codeC = $rowC->doc_code;
                                                $doc_levelC = $rowC->doc_level;
                                                $doc_indexC = $rowC->doc_index;
                                                $doc_parentC = $rowC->doc_parent;
                                                $doc_nameC = $rowC->doc_name;
                                                $isFileC = $rowC->isFile;
												if($isFileC == 0)
												{
													?>
													<li> <?php echo "$doc_nameC"; ?>
														<?php
															// Setelah melakukan LOOP 2. Cek apakah mempunyai turunan. Apabila "ya". Lakukan LOOP 3								
															$sqlD		= "tdocument WHERE doc_parent = '$doc_codeC'";
															$CountD 	= $this->db->count_all($sqlD);
															if($CountD > 0)
															{
																?>
																<ul>
																	<?php
																		// LOOP 2
																		// Cek apakah mempunyai turuan. Apabila mempunyai turunan, buatkan anak menu untuk turunan ke dua
																		$sqlE	= "SELECT * FROM tdocument WHERE doc_parent = '$doc_codeC'";
																		$resDoccumentE = $this->db->query($sqlE)->result();
																		foreach($resDoccumentE as $rowE) :
																			$doc_codeE = $rowE->doc_code;
																			$doc_levelE = $rowE->doc_level;
																			$doc_indexE = $rowE->doc_index;
																			$doc_parentE = $rowE->doc_parent;
																			$doc_nameE = $rowE->doc_name;
																			$isFileE = $rowE->isFile;
																			//Apabila ada penambahan akar, copy mulai dari sini.
																			// START COPY
																			$rowVal = $rowVal + 1;
																			if($isFileE == 0)
																			{
																				?>
																				<li> <?php echo "$doc_nameE"; ?>
																					<?php
																						// Setelah melakukan LOOP 3. Cek apakah mempunyai turunan. Apabila "ya". Lakukan LOOP 4								
																						$sqlF		= "tdocument WHERE doc_parent = '$doc_codeE'";
																						$CountF 	= $this->db->count_all($sqlF);
																						if($CountF > 0)
																						{
																							?>
																							<ul>
																								<?php
																									// LOOP 4
																									// Cek apakah mempunyai turuan. Apabila mempunyai turunan, buatkan anak menu untuk turunan ke dua
																									$sqlG	= "SELECT * FROM tdocument WHERE doc_parent = '$doc_codeE'";
																									$resDoccumentG = $this->db->query($sqlG)->result();
																									foreach($resDoccumentG as $rowG) :
																										$doc_codeG = $rowG->doc_code;
																										$doc_levelG = $rowG->doc_level;
																										$doc_indexG = $rowG->doc_index;
																										$doc_parentG = $rowG->doc_parent;
																										$doc_nameG = $rowG->doc_name;
																										$isFileG = $rowG->isFile;
																										$rowVal = $rowVal + 1;
																										
																										//Apabila ada penambahan akar, copy mulai dari sini.
																										// START COPY
																										$rowVal = $rowVal + 1;
																										if($isFileG == 0)
																										{
																											?>
																											<li> <?php echo "$doc_nameG"; ?>
																												<?php
																													// Setelah melakukan LOOP 3. Cek apakah mempunyai turunan. Apabila "ya". Lakukan LOOP 5								
																													$sqlH		= "tdocument WHERE doc_parent = '$doc_codeG'";
																													$CountH 	= $this->db->count_all($sqlH);
																													if($CountH > 0)
																													{
																														?>
																														<ul>
																															<?php
																																// LOOP 5
																																// Cek apakah mempunyai turuan. Apabila mempunyai turunan, buatkan anak menu untuk turunan ke dua
																																$sqlI	= "SELECT * FROM tdocument WHERE doc_parent = '$doc_codeG'";
																																$resDoccumentI = $this->db->query($sqlI)->result();
																																foreach($resDoccumentI as $rowI) :
																																	$doc_codeI = $rowI->doc_code;
																																	$doc_levelI = $rowI->doc_level;
																																	$doc_indexI = $rowI->doc_index;
																																	$doc_parentI = $rowI->doc_parent;
																																	$doc_nameI = $rowI->doc_name;
																																	$isFileI = $rowI->isFile;
																																	$rowVal = $rowVal + 1;
																																	?>
																																	<li><a href="javascript: submitform('<?php echo $rowVal; ?>')"><?php echo $doc_nameI; ?> </a> <input type='text' name='menuID_<?php echo $rowVal; ?>' id='menuID_<?php echo $rowVal; ?>' value="<?php echo $doc_nameI; ?>" style="display:none" disabled><input type="text" name="indexMenu_<?php echo $rowVal; ?>" id="indexMenu_<?php echo $rowVal; ?>" value="<?php echo $rowVal; ?>" style="display:none" disabled /></li>
																																	<?php
																																endforeach;
																															?>
																														</ul>
																														<?php
																													}
																												?>
																											</li>
																											<?php
																										}
																										else
																										{
																											$rowVal = $rowVal + 1;
																											?>
																												<li> <a href="javascript: submitform('<?php echo $rowVal; ?>')"><?php echo $doc_nameE; ?> </a> <input type='text' name='menuID_<?php echo $rowVal; ?>' id='menuID_<?php echo $rowVal; ?>' value="<?php echo $doc_nameE; ?>" style="display:none" disabled><input type="text" name="indexMenu_<?php echo $rowVal; ?>" id="indexMenu_<?php echo $rowVal; ?>" value="<?php echo $rowVal; ?>" style="display:none" disabled /></li>
																											<?php
																										}
																										// END COPY
																									endforeach;
																								?>
																							</ul>
																							<?php
																						}
																					?>
																				</li>
																				<?php
																			}
																			else
																			{
																				$rowVal = $rowVal + 1;
																				?>
																					<li> <a href="javascript: submitform('<?php echo $rowVal; ?>')"><?php echo $doc_nameE; ?> </a> <input type='text' name='menuID_<?php echo $rowVal; ?>' id='menuID_<?php echo $rowVal; ?>' value="<?php echo $doc_nameE; ?>" style="display:none" disabled><input type="text" name="indexMenu_<?php echo $rowVal; ?>" id="indexMenu_<?php echo $rowVal; ?>" value="<?php echo $rowVal; ?>" style="display:none" disabled /></li>
																				<?php
																			}
																			// END COPY
																		endforeach;
																	?>
																</ul>
																<?php
															}
														?>
													</li>
													<?php
												}
												else
												{
													$rowVal = $rowVal + 1;
													?>
                                                        <li> <a href="javascript: submitform('<?php echo $rowVal; ?>')"><?php echo $doc_nameC; ?> </a> <input type='text' name='menuID_<?php echo $rowVal; ?>' id='menuID_<?php echo $rowVal; ?>' value="<?php echo $doc_nameC; ?>" style="display:none" disabled><input type="text" name="indexMenu_<?php echo $rowVal; ?>" id="indexMenu_<?php echo $rowVal; ?>" value="<?php echo $rowVal; ?>" style="display:none" disabled /></li>
                                                    <?php
												}			
                                            endforeach;
                                        ?>
                                    </ul>
                                    <?php
                                }
                            ?>
                        </li>
                    	<?php
					}
					else
					{
						$rowVal = $rowVal + 1;
						?>
							<li> <a href="javascript: submitform('<?php echo $doc_name; ?>')"><?php echo $doc_nameA; ?> </a> <input type='text' name='menuID_<?php echo $rowVal; ?>' id='menuID_<?php echo $rowVal; ?>' value="<?php echo $doc_name; ?>" style="display:none" disabled><input type="text" name="indexMenu_<?php echo $rowVal; ?>" id="indexMenu_<?php echo $rowVal; ?>" value="<?php echo $rowVal; ?>" style="display:none" disabled /></li>
                        <?php
					}				
				endforeach;
			?>            
        </ul>
        </form>
          <script type="text/javascript">
				ddtreemenu.createTree("treemenu1", true)
				ddtreemenu.createTree("treemenu2", false)
			</script>
            
        </td>
        <script type="text/javascript">
			function executMenu()
			{
				document.forms["formMenu"].submit();
			}
		</script>
  	  	<td width="76%" nowrap style="vertical-align:top">
			<?php
				if(isset($_POST['myIndex']))
				{
					$myIndex = $_POST['myIndex'];
					$getText = "menuID_$myIndex";
					//echo $getText;
					$FileUpName = $_POST[$getText];
					//echo $FileUpName;
					$myPath = 'system/application/views/v_document_log/document_list/'.$FileUpName;
					$nama_file = base_url() . "$myPath"; # read file into array
					//echo $nama_file;
					?>
					<object data="<?php echo $nama_file; ?>" type="application/pdf" width="900" height="400">
					</object> 
			<?php
				}
            ?>
		</td>
  </tr>
</table>
