
<!DOCTYPE html>
<html>
<head>
	<title>Display</title>
	<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
	<script src="js/jquery-3.2.1.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<?php

	include "tokenisasi.php";
	include "stopword.php";
	include "stemming.php";
	include "koneksi.php";
	truncate();
	if(!empty($_POST['text'])){
		$kata = strtolower($_POST['text']); 
		$kata1 = $_POST['text'];
		$jumlah_kata = jumlah_kata($kata);
		$token = tokenisasi($kata);
		$tokenisasi2 = tokenisasi2($kata1);
		$tokenisasi3 = tokenisasi3($kata);
		$stopword1 = stopword($kata);
		$splitparagraf = splitparagraf($kata);
		$countparagraf = count($splitparagraf);
		$summary_proc = summary_proc($splitparagraf);
		$splitparagraf1 = splitparagraf($kata1);
	}else if(!empty($_POST['corpus'])){
		$line = $_POST['corpus'];
		$start = 0;
		$filename = $_POST['corpus'];
		$handle = fopen($filename, "r"); 
		while (!feof($handle)) { 
			$line = fgets($handle); //baca per baris 
			
			$kata = strtolower($line); 
			$kata1 = $line;
			$splitparagraf [] = $kata;
			$splitprag [] = $kata1;
			
		}
		$countparagraf = count($splitparagraf);
		$summary_proc = summary_proc($splitparagraf);

		$prag = implode(" ",$splitprag);
		$para = preg_replace("/[\n\r]/","",$prag);
		$paralow = strtolower($para);

		$jumlah_kata = jumlah_kata($paralow);
		$token = tokenisasi($paralow);
		$tokenisasi2 = tokenisasicorp($para);
		$tokenisasi3 = tokenisasi3($paralow);
		$stopword1 = stopword($paralow);
		$splitparagraf1 = splitparagraf($para);
		
	}else{
		header('Location: http://localhost/STKI/index.php?message=failed');
	}

	function katatype($kata){
		$arr_stem[] = array();
		$arr_val[] = array();
		unset($arr_stem);
		unset($arr_val);
		$kata_type = stopword($kata);
		foreach($kata_type as $z=>$z_value) {
			$stem= talakamus($z);   
			if(!empty($z)){
				$arr_stem[] = $stem;	                                                                                                    
            }				
		}
		return $arr_stem;
	}

	function kalimat($splitkalimat){
		$stopwordproc = array();
		unset($stopwordproc);
		foreach($splitkalimat as $kalimat){
			if(!empty($kalimat))
			$stopwordproc[] = stopwordresult($kalimat);
		}

		return $stopwordproc;
	}
	
	
	function stopwordresult($kata){
		$tokenisasinocount = tokenisasinocount($kata);
		$stopword = array();
		unset($stopword);
		foreach($tokenisasinocount as $stop) { 
			$stopwordtemp = caristopword($stop);
			if($stopwordtemp != 0){
				unset($stop);
			}else{
				$stopword[] = talakamus($stop);
			}			
		}
		
		return array_count_values($stopword);
	}

	$arr_combination = array();
	$countfile = 0;

	function summary_proc($splitparagraf){
		foreach($splitparagraf as $splitparagraf){
	
			$splitkalimat = splitkalimat($splitparagraf);
			$jumlah_kata_paragraf = jumlah_kata($splitparagraf);
			$dokumen = kalimat($splitkalimat);
			$term = katatype($splitparagraf);
			$countterm = count($term);
			$dokumencount = count(kalimat($splitkalimat));
			
			$termresult = array();
			$result = array();
		
			$arr_comdf = array();
			$arr_term = array();
			$arr_doccount = array();
			unset($arr_term);
			unset($arr_doccount);
			unset($arr_comdf);
			
			//banyak dokumen yang mengandung kata
			for($i=0;$i<$countterm;$i++){
				$k = 0;
				for($j=0;$j<$dokumencount;$j++){
					if(array_key_exists($term[$i], $dokumen[$j])){
						$k++;
					}
				}
				$arr_term [] = $term[$i];
				$arr_doccount [] = $k;
				
			}
	
			$arr_comdf = array_combine($arr_term,$arr_doccount);
	
			$arr_tf = array();
			$tf = array();
			$tftemp = array();
			$arr_comtfidf = array();
			$tfidf = array();
			$com_tfidf = array();
			$com_tf = array();
			unset($arr_comtfidf);

			for($i=0;$i<$dokumencount;$i++){
				$countdoc = count($dokumen[$i]);
				unset($tf);
				unset($tfidf);
				$tftemp = 0;
				$tfidftemp = 0;
				foreach($dokumen[$i] as $item => $item_value){
					foreach($arr_comdf as $df => $df_valued){
						if($item != "" && $item == $df){
							$tftemp = $item;
							$tfidftemp = number_format(($item_value*log((($dokumencount)/$df_valued),10)),2);
							break;
						}	
					}
					$tf [] = $tftemp;
					$tfidf [] =$tfidftemp;
				}
				$com_tf []= $tf;
				$com_tfidf [] = $tfidf;
	
			}

			$counttf = count($com_tf);	
			$value_all = array();

			for($i=0;$i<$counttf;$i++){
				$value =0;
				foreach($com_tfidf[$i] as $tfidf_value){
					$value = number_format($value + $tfidf_value,2);
				}
				$value_all [] = $value;
			}
			$com_value [] = $value_all;
		}
		return $com_value;
	}
	
	// index bobot terbesar
	for($paragraf = 0; $paragraf<$countparagraf;$paragraf++){
		$max_bot = max($summary_proc[$paragraf]);
		foreach($summary_proc[$paragraf] as $bobot => $bobot_value){
			if($max_bot == $bobot_value){
				$indexbobot = $bobot;
				$bobot = $bobot_value;
				break;
			}
		}
		$arr_indexbobot []= $indexbobot;
		$arr_bobot [] = $bobot;
	}
	$arr_combobot = array_combine($arr_indexbobot,$arr_bobot);
	$countarrbot = count($arr_combobot);


?>

<?php 
	if(!empty($_POST['text'])){
		insertsplitdoc($tokenisasi2);
		$stem = stemming();
		arsort($stem);
		insertstem($stem);

		$result = weight($kata);
		insertdocweight($result);

	}else{
		insertsplitdoc($tokenisasi2);
		$stem = stemming();
		arsort($stem);
		insertstem($stem);

		$result = weight($paralow);
		insertdocweight($result);
	}

	$phrase = selectweight();
	sort($phrase);
	
	function insertsplitdoc($tokenisasi2){
		foreach($tokenisasi2 as $doc => $doc_value){
			if(!empty($doc_value)){
				$sql = "INSERT INTO `dokumen` (`dokumen`, `index`) VALUES ('$doc_value',$doc)";
				if(konek()->query($sql) === TRUE){
					$record = true;
				} else{
					$record = mysqli_error(koneksi());
				}
			}
			
		}
		return $record;
	}

	function stemming(){
		$stopword = array();
		$sql = "SELECT * FROM `dokumen`";
		$result = konek()->query($sql);
		
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$doc = strtolower($row["dokumen"]);
				$tokenisasinocount = tokenisasinocount($doc);
				foreach($tokenisasinocount as $stop) { 
					$stopwordtemp = caristopword($stop);
					if($stopwordtemp != 0){
						unset($stop);
					}else{
						$stopword[] = talakamus($stop);
					}			
				}
			}
			
		} else {
			$row = 0;
		}		
		return array_count_values($stopword);
	}

	function insertstem($stem){
		foreach($stem as $stem => $stem_value){
			if(!empty($stem)){
				$sql = "INSERT INTO `stemming` (`term`, `term_value` ) VALUES ('$stem',$stem_value)";
				if(konek()->query($sql) === TRUE){
					$record1 = true;
				} else{
					$record1 = mysqli_error(konek());
				}
			}
		}
		return $record1;
	}

	function insertdocweight($result){
		foreach($result as $index => $weight_value){
			$sql = "INSERT INTO `dokbot` (`bobot`, `id_dokumen` ) VALUES ('$weight_value',($index +1))";
			if(konek()->query($sql) === TRUE){
				$record1 = true;
			} else{
				$record1 = mysqli_error(koneksi());
			}
		}
		return $record1;
	}
	
	function weight($kata){
		$term = array();
		unset($term);
		$splitkalimat = splitkalimat2($kata);
		$jumlah_kata_paragraf = jumlah_kata($kata);
		$dokumen = kalimat($splitkalimat);
		$term = katatype($kata);
		$countterm = count($term);

		$dokumencount = count(kalimat($splitkalimat));
		$arr_comdf = array();
		$arr_term = array();
		$arr_doccount = array();
		unset($arr_term);
		unset($arr_doccount);
		unset($arr_comdf);

		//banyak dokumen yang mengandung kata
		for($i=0;$i<$countterm;$i++){
			$k = 0;
			for($j=0;$j<$dokumencount;$j++){
				if(array_key_exists($term[$i], $dokumen[$j])){
					$k++;
				}
			}
			$arr_term [] = $term[$i];
			$arr_doccount [] = $k;
		}

		//tfidf
		$arr_comdf = array_combine($arr_term,$arr_doccount);

		$arr_tf = array();
		$tf = array();
		$tftemp = array();
		$arr_comtfidf = array();
		$tfidf = array();
		$com_tfidf = array();
		$com_tf = array();
		unset($arr_comtfidf);

		for($i=0;$i<$dokumencount;$i++){
			$countdoc = count($dokumen[$i]);
			unset($tf);
			unset($tfidf);
			$tftemp = 0;
			$tfidftemp = 0;
			foreach($dokumen[$i] as $item => $item_value){
				foreach($arr_comdf as $df => $df_valued){
					if($item != "" && $item == $df){
						$tftemp = $item;
						$tfidftemp = number_format(($item_value*log((($dokumencount)/$df_valued),10)),2);
						break;
					}	
				}
				$tf [] = $tftemp;
				$tfidf [] =$tfidftemp;
			}
			$com_tf []= $tf;
			$com_tfidf [] = $tfidf;

		}

		// bobot kalimat
		$counttf = count($com_tf);		
		for($i=0;$i<$counttf;$i++){
			$value =0;
			foreach($com_tfidf[$i] as $tfidf_value){
				$value = number_format($value + $tfidf_value,2);
			}
			$value_all [] = $value;
		}
		return $value_all;
	}

	function selectweight(){
		$stopword = array();
		$sql = "SELECT `bobot`, `id_dokumen` FROM `dokbot` ORDER BY `bobot` DESC";
		$result = konek()->query($sql);
		
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$doc [] = $row["id_dokumen"];
				$bot [] = $row["bobot"];
			}
			
			for($i=0;$i<3;$i++){
				if($doc[$i+1]== $doc[$i]+2 && $i != 2){
					$doc_com [] = $doc[$i];
					$doc_com [] = $doc[$i]+1;
				}else if($doc[$i+1]== $doc[$i]-2 && $i != 2){
					$doc_com [] = $doc[$i];
					$doc_com [] = $doc[$i]-1;
				}else{
					$doc_com [] = $doc[$i];
				}
			}
			return $doc_com;
		} else {
			return false;
		}		

	}

?>
	

<body style="background-image: url(bg.jpg); background-size: 100%">
	<div class="content">
		<div class="container">
			<form id="form" method="post" action="" style="width: 80%; height: 700%; padding: 20px;  margin: auto; margin-top: 10%; border: 1px solid #DDD; box-shadow: 10px 10px 10px #DDD; background-color: white; border-radius: 5px;">
				<h1 style="margin-bottom: 30px">STKI Peringkasan</h1>
				<!-- <div class="form-group row">
					<label for="inputEmail3" class="col-sm-4 col-form-label">Judul</label>
					<div class="col-sm-8">
						<p Align="Justify">: 
					
					</div>
				</div> -->
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-4 col-form-label">Text Awal</label>
					<div class="col-sm-8">
						<p Align="Justify">: <?php 
							if(!empty($_POST['text'])){
								echo $_POST['text'];
							}else{
								echo $para;
							}

						?> </p>
					</div>
				</div>
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-4 col-form-label">Jumlah Token</label>
					<div class="col-sm-8">
						<p>: <?php echo $jumlah_kata; ?> </p>
					</div>
				</div>
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-4 col-form-label">Jumlah Kata Type</label>
					<div class="col-sm-8">
						<p>: <?php 
							$token1 = 	0;
							arsort($stopword1);
							foreach($stopword1 as $a => $a_value){   
								$stem= talakamus($a);
							    if(!empty($a)){
							    	$token1 = ($token1+1);
								}	
									
							}
						echo $token1?> 
						</p>
					
					</div>
				</div>
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-4 col-form-label">Ringkasan1</label>
					<div class="col-sm-8">
						<p>: 
						<?php 	
						
						if(!empty($_POST['text'])){
							$splitparagraf1 = splitparagraf($kata1);
							for($hitparagraf = 0; $hitparagraf<$countparagraf; $hitparagraf++){
								$splitkalimat1 = splitkalimat1($splitparagraf1[$hitparagraf]);
								echo $splitkalimat1[$arr_indexbobot[$hitparagraf]].". ";
							}
						}else{
							$handle = fopen($filename, "r"); 
							unset($splitparagraf1);
							while (!feof($handle)) { 
								$line = fgets($handle); //baca per baris 
								$kata1 = $line;
								$splitparagraf1 [] = $kata1;
								$countparagraf = count($splitparagraf1);
							}
							for($hitparagraf = 0; $hitparagraf<$countparagraf; $hitparagraf++){
								$splitkalimat1 = splitkalimat1($splitparagraf1[$hitparagraf]);
								echo $splitkalimat1[$arr_indexbobot[$hitparagraf]].". ";
							}
						}
							
						?> 
						</p>
					</div>
				</div>
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-4 col-form-label">Ringkasan2</label>
					<div class="col-sm-8">
						<p>: 
						<?php 
							if(!in_array(1,$phrase)){
								$sql = "SELECT `dokumen` FROM `dokumen` WHERE `id_dokumen`= 1";
								$result = konek()->query($sql);
								if ($result->num_rows > 0) {
									// output data of each row
									while($row = $result->fetch_assoc()) {
										echo $row["dokumen"].".";
									}
								}
							}
							foreach($phrase as $ph){
								$sql2 = "SELECT `dokumen` FROM `dokumen` WHERE `id_dokumen`= $ph";
								$result = konek()->query($sql2);
								if ($result->num_rows > 0) {
									// output data of each row
									while($row = $result->fetch_assoc()) {
										echo $row["dokumen"].".";
									}
								}
							}

							$sql3 = "SELECT MAX(`id_dokumen`) as id_dokumen FROM `dokumen`";
								$result = konek()->query($sql3);
								if ($result->num_rows > 0) {
									// output data of each row
									while($row = $result->fetch_assoc()) {
										$lastid = $row["id_dokumen"];
									}
								}

							if(!in_array($lastid,$phrase)){
								$sql4 = "SELECT `dokumen` FROM `dokumen` WHERE `id_dokumen`= $lastid";
								$result = konek()->query($sql4);
								if ($result->num_rows > 0) {
									// output data of each row
									while($row = $result->fetch_assoc()) {
										echo $row["dokumen"].".";
									}
								}
							}
						?> 
						</p>
					</div>
				</div>

				<div class="col-sm-12" >
					<table class="table">
						<?php 
							
							for($hitparagraf = 0; $hitparagraf<$countparagraf; $hitparagraf++){
								echo "<tr>";
								echo "<th width='50px' style='text-align:center'> Index <th>";
								echo "<th width='800px' style='text-align:center'> Kalimat pada Paragraf ". ($hitparagraf+1)."<th>";
								echo "<th width='200px' style='text-align:center'> Bobot </th>";
								echo "</tr>";
								$splitkalimat1 = splitkalimat1($splitparagraf1[$hitparagraf]);
								$dokumencount1 = count(kalimat($splitkalimat1));

								for ($doc=0;$doc<$dokumencount1;$doc++){
								echo "<tr>";
								echo "<td style='text-align:center'>".($doc+1)."<td>";
								echo "<td>".$splitkalimat1[$doc]."<td>";
								echo "<td style='text-align:center'>".$summary_proc[$hitparagraf][$doc]."<td>";
								echo "</tr>";
	
								}							
							}
						?>
					</table>
						
				</div>



				<div class="col-sm-12" >
					<table class="table">
						<?php 
							echo "<tr>";
							echo "<th width='50px' style='text-align:center'> Index <th>";
							echo "<th width='800px' style='text-align:center'> Kalimat <th>";
							echo "<th width='200px' style='text-align:center'> Bobot </th>";
							echo "</tr>";

							$sql2 = "SELECT `dokumen`.`id_dokumen` as `id_dokumen`, `dokumen`.`dokumen` as `dokumen`, `dokbot`.`bobot` as `bobot` FROM `dokbot` INNER JOIN `dokumen` USING (`id_dokumen`) ORDER BY `dokbot`.`bobot` DESC";
							$result = konek()->query($sql2);
							if ($result->num_rows > 0) {
								// output data of each row
								while($row = $result->fetch_assoc()) {
									echo "<tr>";
									echo "<td style='text-align:center'>".$row['id_dokumen']."<td>";
									echo "<td>".$row['dokumen']."<td>";
									echo "<td style='text-align:center'>".$row['bobot']."<td>";
									echo "</tr>";
								}
							}
						
						?>
					</table>
						
				</div>

				<table width="900px">
					<tr>
						<td ALIGN="center"><button id="btn_token" name ="btn-token" type="button" class="btn btn-success" onclick="tokenFunction()">TOKEN</button></td>
				        <td ALIGN="center"><button id="btn_stopword" name ="btn-stopword" type="button" class="btn btn-primary" onclick="stopwordFunction()">STOPWORD</button></td>
				        <td ALIGN="center"><button id="btn-stemming" name ="btn-stemming" type="button" class="btn btn-info" onclick="stemmingFunction()">STEMMING</button></td>
				    </tr>
				</table>
				<br>
				
				<div class="row">
					<div class="col-sm-4">
						<table class="table" border="1" id="token" style="display: none;">
							<tr>
								<th width="200px">WORD</th>
								<th width="200px">VALUE</th>
							</tr>
							<?php 			
								arsort($token);
								foreach($token as $x => $x_value) {   
								    if(!empty($x)){
								    	echo "<tr>";
									    echo "<td>".$x."</td>";
									    echo "<td>".$x_value."</td>";
									    echo "</tr>";
									}
								}
								
							?>
						</table>
					</div>
					<div class="col-sm-4">
						<table class="table" border="1" id="stopword" style="display: none;">
							<tr>
								<th width="200px">WORD</th>
								<th width="200px">VALUE</th>
							</tr>
							<?php 
							$stopwordresult = stopword($kata);	
							arsort($stopwordresult);
							foreach($stopwordresult as $y =>$y_value){
								if(!empty($y)){
									echo "<tr>";
									echo "<td>".$y."</td>";
									echo "<td>".$y_value."</td>";
									echo "</tr>";
								}	
							}
							?>
						</table>
					</div>
					<div class="col-sm-4">
						<table class="table" border="1" id="stemming" style="display: none;">
							<tr>
								<th width="200px">WORD</th>
								<th width="200px">VALUE</th>
							</tr>
							<?php 
							arsort($stopwordresult);
							foreach($stopwordresult as $z => $z_value) {
								$stem= talakamus($z);   
								if(!empty($z)){
									echo "<tr>";
									echo "<td>".$stem."</td>";
									echo "<td>".$z_value."</td>";
									echo "</tr>";
								}		
							}	
							?>
						</table>
					</div>
				</div>	
			</form>
		</div>
	</div>			
</body>


<script type="text/javascript">
	function stopwordFunction() {
	    var x = document.getElementById("stopword");
	    if (x.style.display === "none") {
	        x.style.display = "block";
	    } 
	}

	function stemmingFunction() {
	    var x = document.getElementById("stemming");
	    if (x.style.display === "none") {
	        x.style.display = "block";
	    }
	}

	function tokenFunction() {
	    var x = document.getElementById("token");
	    if (x.style.display === "none") {
	        x.style.display = "block";
	    }
	}
</script>
</html>

