
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
	if(!empty($_POST['text'])){
		$kata = strtolower($_POST['text']); 
		$kata1 = $_POST['text'];
		$jumlah_kata = jumlah_kata($kata);
		$token = tokenisasi($kata);
		$stopword1 = stopword($kata);
		$splitparagraf = splitparagraf($kata);
		$countparagraf = count($splitparagraf);

	}else if(!empty($_POST['corpus'])){
		$line = $_POST['corpus'];
		$start = 0;
		$filename = $_POST['corpus'];
		$handle = fopen($filename, "r"); 
		while (!feof($handle)) { 
			$line = fgets($handle); //baca per baris 

				$line = preg_replace('/^\s*/', '', $line); 
				$line = preg_replace('/\s*$/', '', $line);

			$line = strtolower($line); 
			if ($line=="<text>") { // awal
				$start=1; 
			}elseif ($line=="</text>") { 
				$start=0; 
			}
			if ($start==1 && $line!="<text>"){ 
				$kata = strtolower($line); 
				$token = tokenisasi($kata);
				$stopword = stopword($kata);
				$jumlah_kata = jumlah_kata($kata);
			}
		}
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
            // $arr_com =array_combine($arr_stem,$arr_val);
				
		}
		return $arr_stem;
	}

	function kalimat($splitkalimat){
		$stopwordproc = array();
		unset($stopwordproc);
		foreach($splitkalimat as $kalimat){
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
				// $stopword[] = $stop;
			}			
		}
		
		return array_count_values($stopword);
	}

	$arr_combination = array();
	// unset($arr_combination);

	$countfile = 0;
	foreach($splitparagraf as $splitparagraf){
	
	$splitkalimat = splitkalimat($splitparagraf);
	$jumlah_kata_paragraf = jumlah_kata($splitparagraf);
	$dokumen = kalimat($splitkalimat);
	$term = katatype($kata);
	$countterm = count($term);
	
	echo "<hr><br><br>" .json_encode($dokumen);
	// echo "<br><br>" .json_encode($term);
	$dokumencount = count(kalimat($splitkalimat));
	// echo "<br><br>".$countterm."<br><br>";
	echo "<br><br> Jumlah kata: ".$jumlah_kata_paragraf;
	
	$termresult = array();
	$result = array();

	//df perkalimat
	

	for($i=0;$i<$dokumencount-1;$i++){
			$k = 0;
		for($j=0;$j<$countterm;$j++){
			foreach($dokumen[$i] as $item => $item_value){
				// $df[$j][$k] = 0;
				if($term[$j] == $item){
					$df = $item_value;
					$termresult = $term[$j];
					$result= $df;
					break;
				}else{
					$termresult = $term[$j];
					$result= 0;
				}
			}
			// $k++;
		}
	}

	// echo "<br><br>" .json_encode($termresult);
	// echo "<br><br>" .json_encode($result);
	$arr_comdf = array();
	$arr_term = array();
	$arr_doccount = array();
	unset($arr_term);
	unset($arr_doccount);
	unset($arr_comdf);
	
	//banyak dokumen yang mengandung kata
	for($i=0;$i<$countterm;$i++){
		$k = 1;
		for($j=0;$j<$dokumencount-1;$j++){
			if(array_key_exists($term[$i], $dokumen[$j])){
				$docterm = $term[$i];
				$k++;
			}
		}
		$arr_term [] = $docterm;
		$arr_doccount [] = $k;
	}
	
	//tfidf
	$arr_comdf = array_combine($arr_term,$arr_doccount);
	echo "<br><br>" .json_encode($arr_comdf);
	$arr_tf = array();
	$tf = array();
	$arr_comtfidf = array();
	$tfidf = array();
	unset($arr_comtfidf);
	unset($tf);
	unset($tfidf);
	for($i=0;$i<$dokumencount-1;$i++){
		$countdoc = count($dokumen[$i]);
		for($j=0;$j<$countdoc;$j++){
			foreach($dokumen[$i] as $item => $item_value){
				foreach($arr_comdf as $df=>$df_valued){
					if($item == $df){
						
						$tf [] = $item;
						$tfidf []= number_format(($item_value*log(($jumlah_kata_paragraf/$df_valued),10)),2);
						// $tfidf []= (1*log((42/2),10));
						break;
					}
				}
			}
		}
		// $arr_tf [] = $tf;
		// $arr_tfidf [] = $tfidf;
		$arr_comtfidf = array_combine($tf,$tfidf);
	}
	// echo "<br><br>" .json_encode($arr_comtfidf);
	// foreach($arr_comtfidf as $term=>$valued){
	// 	echo "<br><br>".$term." :".$valued;
	// }

	
	//term*tfidf
	$arr_sumtfidf = array();
	unset($arr_sumtfidf);
	for($i=0;$i<$dokumencount-1;$i++){
		unset($tf);
		unset($tfidf);
		foreach($dokumen[$i] as $item => $item_value){
			$sumall = 0;
			foreach($arr_comtfidf as $term=>$valued){
				if($item == $term){
					$tf [] = $item;
					$tfidf []= number_format(($item_value*$valued),2);
					break;
				}
				// $sumall = $sumall + $tfidf;
			}
			
		}
		$arr_sumtfidf [$i] = array_combine($tf,$tfidf);
	}
	// $arr_sumtfidf = array_combine($tf,$tfidf);
	// echo "<br>". json_encode($arr_sumtfidf);
	// echo "<br><hr>";

	//sumt bobot per dokumen per paragraf
	unset($arr_dokumen);
	unset($arr_sumall);
	for($i=0;$i<$dokumencount-1;$i++){
		$sumall = 0;
		foreach($arr_sumtfidf[$i] as $term=>$valued){
			$sumall = $sumall+$valued;	
		}
		// $arr_dokumen [] = $countfile.$i;
		$arr_sumall [] = number_format($sumall,2);
		// echo "<br><br> Dokumen ".$i." :".$sumall;
	}
	// echo json_encode($arr_dokumen);
	// echo json_encode($arr_sumall);
	// $arr_combination = array_combine($arr_dokumen,$arr_sumall);
	// $arr_combination = $arr_sumall;

	$countfile++;
	$arr_comall []= $arr_sumall;
}
	echo "<br>";
	echo json_encode($arr_comall);
	//index bobot terbesar
	for($paragraf = 0; $paragraf<$countparagraf;$paragraf++){
		$max_bot = max($arr_comall[$paragraf]);
		foreach($arr_comall[$paragraf] as $bobot => $bobot_value){
			if($max_bot == $bobot_value){
				$indexbobot = $bobot;
				$bobot = $bobot_value;
				break;
			}
		}
		$arr_indexbobot []= $indexbobot;
		$arr_bobot [] = $bobot;
	}
	echo "<br>".json_encode($arr_indexbobot);
	echo "<br>".json_encode($arr_bobot);
	$arr_combobot = array_merge($arr_indexbobot, $arr_bobot);
	// echo json_encode($arr_combobot);
	$countarrbot = count($arr_combobot);

	$splitparagraf1 = splitparagraf($kata1);
	echo "<br>".json_encode($arr_combobot);
			
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
								$filename = $_POST['corpus'];
								$handle = fopen($filename, "r"); 
								while (!feof($handle)) { 
									$line = fgets($handle);
									$line = preg_replace('/^\s*/', '', $line); 
									$line = preg_replace('/\s*$/', '', $line);

									$line = strtolower($line); 
									if ($line=="<text>") { // awal
										$start=1; 
									}elseif ($line=="</text>") { 
										$start=0; 
									}
									if ($start==1 && $line!="<text>"){
										echo $line;
									}
								}
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
					<label for="inputEmail3" class="col-sm-4 col-form-label">Ringkasan</label>
					<div class="col-sm-8">
						<p>: 
						<?php 
							for($hitparagraf = 0; $hitparagraf<$countparagraf; $hitparagraf++){
								$splitkalimat1 = splitkalimat($splitparagraf1[$hitparagraf]);
								echo $splitkalimat1[$arr_indexbobot[$hitparagraf]].". ";
							}
						?> 
						</p>
					</div>
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

