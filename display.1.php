<?php
	include "stopword.php";
	include "stemming.php";
	include "koneksi.php";
	if(!empty($_POST['judul'])){
		$line1 = $_POST['judul'];
		$kata1 = strtolower($_POST['judul']); 
		$token1 = tokenisasi($kata1);
		$stopword1 = stopword($kata1);
		$jumlah_kata1 = jumlah_kata($kata1);

		echo $kata1."<br>";
		foreach($token1 as $x => $x_value) {   
			if(!empty($x)){
				echo $x;
				echo $x_value."<br>";
			}
		}
		echo "<br> Stopword <br>";
		foreach($stopword1 as $y => $y_value) {   
			if(!empty($y)){
				echo $y;
				echo $y_value."<br>";
			}
		}
		echo "<br> Stemming <br>";
		$countstop = count($stopword1);
		foreach($stopword1 as $z => $z_value) {  
			if(!empty($z)){
				$stem1 = talakamus($z);
				echo $stem1;
				echo $z_value."<br>";

				//tf
				$tf = $z_value/$countstop;
				echo number_format($tf,2)."<br>";
			}
		}

	}else if(!empty($_POST['text'])){
		$line1 = $_POST['text'];
		$kata1 = strtolower($_POST['text']); 
		$token1 = tokenisasi($kata);
		$stopword1 = stopword($kata);
		$jumlah_kata1 = jumlah_kata($kata);

	}else if(!empty($_POST['corpus'])){
		$line = $_POST['corpus'];
		$start = 0;
		$start1 = 0;
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
				echo $kata;
			}			
		}
	}	
?>

