<?php

    function konek(){
        $user = "root";
        $pass = "";
        $db = "peringkasan";
        $host = "localhost";
        $konek = mysqli_connect($host, $user, $pass,$db);

        return $konek;
    }

    function truncate(){
        $sql = "CALL turncate()";

        if(konek()->query($sql)===true){
            $record =  true;
        } else{
            $record = mysqli_error(konek());
        }
        return $record;
    }

    // function stemming(){
	// 	$sql = "SELECT * FROM `dokumen`";
	// 	$result = konek()->query($sql);

	// 	if ($result->num_rows > 0) {
	// 		// output data of each row
	// 		while($row = $result->fetch_assoc()) {
	// 			echo "<br> id: " . $row["id_dokumen"];
	// 			echo "<br> doc: " . $row["dokumen"];
	// 			echo "<br> index: " . $row["index"];
	// 			// $row[] = $result->fetch_assoc();

	// 			$tokenisasinocount = tokenisasinocount($row["dokumen"]);
	// 			$stopword = array();
	// 			unset($stopword);
	// 			foreach($tokenisasinocount as $stop) { 
	// 				$stopwordtemp = caristopword($stop);
	// 				if($stopwordtemp != 0){
	// 					unset($stop);
	// 				}else{
	// 					$stopword[] = talakamus($stop);
	// 					// $stopword[] = $stop;
	// 				}			
	// 			}
	// 		}
			
	// 	} else {
	// 		$row = 0;
	// 	}		
	// 	return array_count_values($stopword);
	// }

?>