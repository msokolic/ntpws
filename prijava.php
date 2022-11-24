<?php 
	print '
	<h1>Forma za prijavu</h1>
	<div class="forma">';
    
    if ($_POST['_action_'] == FALSE) {
		print '
		<form action="" name="prijavaForma" id="prijavaForma" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">
			
            <label for="korisnickoIme">Korisničko ime: *</label>
			<input type="text" id="korisnickoIme" name="korisnickoIme" value="" pattern=".{5,10}" required>
									
			<label for="lozinka">Lozinka: *</label>
			<input type="password" id="lozinka" name="lozinka" value="" pattern=".{4,}" required>
									
			<input type="submit" value="Prijavi se">
		</form>';
	}
	else if ($_POST['_action_'] == TRUE) {
		
		$query  = "SELECT * FROM korisnik";
		$query .= " WHERE korisnickoIme='" .  $_POST['korisnickoIme'] . "'";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		if (password_verify($_POST['lozinka'], $row['lozinka']) && $row['odobren'] == 'Y' && $row['arhiva'] == 'N') {
			#password_verify https://secure.php.net/manual/en/function.password-verify.php
			$_SESSION['user']['valid'] = 'true';
			$_SESSION['user']['id'] = $row['id'];
			# 1 - administrator; 2 - editor; 3 - user
			$_SESSION['user']['rola'] = $row['id_role'];
			$_SESSION['user']['ime'] = $row['ime'];
			$_SESSION['user']['prezime'] = $row['prezime'];
			$_SESSION['message'] = '<p>Dobrodošli, ' . $_SESSION['user']['ime'] . ' ' . $_SESSION['user']['prezime'] . '</p>';
			# Redirect to admin website
			header("Location: index.php?menu=8");
		}

		else if ($row['odobren'] == 'N') {
			unset($_SESSION['user']);
			$_SESSION['message'] = '<p>Vaš profil još nije odobren!</p>';
			header("Location: index.php?menu=7");
		}

		else if ($row['arhiva'] == 'Y') {
			unset($_SESSION['user']);
			$_SESSION['message'] = '<p>Vaš profil je arhiviran!</p>';
			header("Location: index.php?menu=7");
		}
		
		# Bad username or password
		else {
			unset($_SESSION['user']);
			$_SESSION['message'] = '<p>Upisali ste krivo korisničko ime ili lozinku!</p>';
			header("Location: index.php?menu=7");
		}
	}
	print '
	</div>';
?>