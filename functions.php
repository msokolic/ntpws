<?php 
	function pickerDateToMysql($pickerDate){
		$date = DateTime::createFromFormat('Y-m-d H:i:s', $pickerDate);
		return $date->format('d. m. Y H:i:s');
	}  

	function pickerOnlyDateToMysql($pickerDate){
		$date = DateTime::createFromFormat('Y-m-d', $pickerDate);
		return $date->format('d. m. Y.');
	}

	function generirajKorisnickoIme($ime, $prezime){
		$temp_kIme = substr(strtolower($ime), 0, 1) . strtolower($prezime);
		return $temp_kIme;
	}
?>