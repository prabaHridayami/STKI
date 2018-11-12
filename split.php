
<?php
include "stopword.php";
include "stemming.php";
function split1($file){
    if(!empty($file)){
		$line = $file;
		$start = 0;
		$filename = $file;
		$handle = fopen($filename, "r"); 
		while (!feof($handle)) { 
			$line = fgets($handle); //baca per baris 

				$line = preg_replace('/^\s*/', '', $line); 
				$line = preg_replace('/\s*$/', '', $line);

            $line = strtolower($line); 

            if ($line=="<title>") { // awal
				$start1=1; 
			}elseif ($line=="</title>") { 
				$start1=0; 
            }
            if ($start1==1 && $line!="<text>"){ 
				$kata = strtolower($line); 
				$token = tokenisasi($kata);
                $stopword = stopword($kata);
                $stem = talakamus($stopword);
				$jumlah_kata = jumlah_kata($kata);
			}
            
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
    }
}

?>