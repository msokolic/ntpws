<?php 
	print '
	<h1>Forma za registraciju</h1>
	<div class="forma">';
	
	if ($_POST['_action_'] == FALSE) {
		print '
		<form action="" id="registracijaForma" name="registracijaForma" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">
			
			<label for="ime">Ime *</label>
			<input type="text" id="ime" name="ime" placeholder="Vaše ime..." required>

			<label for="prezime">Prezime *</label>
			<input type="text" id="prezime" name="prezime" placeholder="Vaše prezime..." required>
				
			<label for="email">E-mail adresa *</label>
			<input type="email" id="email" name="email" placeholder="Vaš e-mail..." required>
			
			<label for="korisnickoIme">Korisničko ime: * <small>(Korisničko ime mora imati minimalno 5 i maksimalno 10 znakova)</small></label>
			<input type="text" id="korisnickoIme" name="korisnickoIme" pattern=".{5,10}" placeholder="Korisničko ime..." required>
			<br/><input type="checkbox" id="generiraj" value="Y" name="generiraj" onchange="generirajIme(this)">
			<label for="generiraj" id="label"><small> Generiraj automatski</small></label>
															
			<label for="lozinka">Lozinka: * <small>(Lozinka mora imati minimalno 4 znaka)</small></label>
			<input type="password" id="lozinka" name="lozinka" placeholder="Lozinka..." pattern=".{4,}" required>

			<label for="drzava">Država:</label>
			<select name="drzava" id="drzava">
				<option value="">molimo odaberite</option>';
				# odaberi sve države iz baze ntpws, tablice drzave
				$query  = "SELECT * FROM drzave";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '<option value="' . $row['drzavaKod'] . '">' . $row['drzavaIme'] . '</option>';
				}
			print '
			</select>

            <label for="grad">Grad</label>
			<input type="text" id="grad" name="grad" placeholder="Grad...">

            <label for="ulica">Ulica</label>
			<input type="text" id="ulica" name="ulica" placeholder="Ulica...">

            <label for="datumRodenja">Datum rođenja *</label>
			<input type="date" id="datumRodenja" name="datumRodenja" required>

			<input type="submit" value="Registriraj se">
		</form>';
	}
	else if ($_POST['_action_'] == TRUE) {
		
		if(!isset($_POST['korisnickoIme'])) {
			$kIme = generirajKorisnickoIme($_POST['ime'], $_POST['prezime']);

			$query  = "SELECT * FROM korisnik";
			$query .= " WHERE korisnickoIme ='" .  $kIme . "'";
			$result = @mysqli_query($MySQL, $query);
			$row = @mysqli_fetch_array($result, MYSQLI_ASSOC);
			
			$broj = 1;

			while ($row !== null) {
				$temp_kIme = $kIme . $broj;
				$broj = $broj + 1;
				$query  = "SELECT * FROM korisnik";
				$query .= " WHERE korisnickoIme ='" .  $temp_kIme . "'";
				$result = @mysqli_query($MySQL, $query);
				$row = @mysqli_fetch_array($result, MYSQLI_ASSOC);
			}

			if (isset($temp_kIme)){
				$kIme = $temp_kIme;
			}
		}
		else {
			$kIme = $_POST['korisnickoIme'];
		}

		$query  = "SELECT * FROM korisnik";
		$query .= " WHERE email = '" .  $_POST['email'] . "'";
		$query .= " OR korisnickoIme = '" .  $kIme . "'";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result, MYSQLI_ASSOC);
			
		if ($row === null) {
			# password_hash https://secure.php.net/manual/en/function.password-hash.php
			# password_hash() creates a new password hash using a strong one-way hashing algorithm
			$pass_hash = password_hash($_POST['lozinka'], PASSWORD_DEFAULT, ['cost' => 12]);
							
			$query  = "INSERT INTO korisnik (ime, prezime, email, korisnickoIme, lozinka, drzava, grad, ulica, datumRodenja)";
			$query .= " VALUES ('" . $_POST['ime'] . "', '" . $_POST['prezime'] . "', '" . $_POST['email'] . "', '" . $kIme . "', '" . $pass_hash . "', '" . $_POST['drzava'] . "', '" . $_POST['grad'] . "', '" . $_POST['ulica'] . "', '" . $_POST['datumRodenja'] ."')";
			$result = @mysqli_query($MySQL, $query);
				
			# ucfirst() — Make a string's first character uppercase
			# strtolower() - Make a string lowercase
			echo '<p>' . ucfirst(strtolower($_POST['ime'])) . ' ' .  ucfirst(strtolower($_POST['prezime'])) . ', hvala na registraciji. (Korisničko ime: ' . $kIme . ') </p>
			<p><a href="index.php?menu=6"><i class="fa-solid fa-arrow-left"></i></a></p>';
		}
		else {
			echo '<p>Korisnik s ovim e-mailom ili korisničkim imenom već postoji!</p>
			<p><a href="index.php?menu=6"><i class="fa-solid fa-arrow-left"></i></a></p>';
		}
	}
	print '
	</div>';
?>