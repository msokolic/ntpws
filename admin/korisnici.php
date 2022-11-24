<?php 
	
	# edit user profile
	if (isset($_POST['edit']) && $_POST['_action_'] == 'TRUE') {
		$query  = "UPDATE korisnik SET ime = '" . $_POST['ime'] . "', prezime = '" . $_POST['prezime'] . "', email='" . $_POST['email'] . "', korisnickoIme = '" . $_POST['korisnickoIme'] . "', drzava = '" . $_POST['drzava'] . "', grad = '" . $_POST['grad'] . "', ulica = '" . $_POST['ulica'] . "', arhiva = '" . $_POST['arhiva'] . "', id_role = '" . $_POST['rola'] . "'";
        $query .= " WHERE id = " . (int)$_POST['edit'];
        $query .= " LIMIT 1";
        $result = @mysqli_query($MySQL, $query);
		# Close MySQL connection
		@mysqli_close($MySQL);
		
		$_SESSION['message'] = '<p>Uspješno ste promijenili korisnički profil!</p>';
		
		# Redirect
		header("Location: index.php?menu=8&action=1");
	}
	# End edit user profile
	
	# Delete user profile
	if (isset($_GET['delete']) && $_GET['delete'] != '') {
	
		$query  = "DELETE FROM korisnik";
		$query .= " WHERE id = ".(int)$_GET['delete'];
		$query .= " LIMIT 1";
		$result = @mysqli_query($MySQL, $query);

		$_SESSION['message'] = '<p>Uspješno ste obrisali korisnika!</p>';
		
		# Redirect
		header("Location: index.php?menu=8&action=1");
	}
	# End delete user profile
	
	
	#Show user info
	if (isset($_GET['id']) && $_GET['id'] != '') {
		$query  = "SELECT * FROM korisnik";
		$query .= " WHERE id = " . $_GET['id'];
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		print '
		<h2>Korisnički profil</h2>
		<p><b>Ime:</b> ' . $row['ime'] . '</p>
		<p><b>Prezime:</b> ' . $row['prezime'] . '</p>
		<p><b>E-mail:</b> ' . $row['email'] . '</p>
		<p><b>Korisničko ime:</b> ' . $row['korisnickoIme'] . '</p>';
		if ($row['drzava'] != '') {
			$_query  = "SELECT * FROM drzave";
			$_query .= " WHERE drzavaKod = '" . $row['drzava'] . "'";
			$_result = @mysqli_query($MySQL, $_query);
			$_row = @mysqli_fetch_array($_result);
			print '
			<p><b>Država:</b> ' .$_row['drzavaIme'] . '</p>';
		}
		if ($row['grad'] != '') {
			print '<p><b>Grad:</b> ' . $row['grad'] . '</p>';
		}
		if ($row['ulica'] != '') {
			print '<p><b>Ulica:</b> ' . $row['ulica'] . '</p>';
		}
		print'<p><b>Datum rođenja:</b> ' . pickerOnlyDateToMysql($row['datumRodenja']) . '</p>';

		$query2  = "SELECT naziv FROM role";
		$query2 .= " WHERE id = " . $row['id_role'];
		$result2 = @mysqli_query($MySQL, $query2);
		$row2 = @mysqli_fetch_array($result2);
						
		print'<p><b>Rola:</b> ' . $row2['naziv'] . '</p>
		<p><a href="index.php?menu=' . $menu . '&amp;action=' . $action . '"><i class="fa-solid fa-arrow-left"></i></a></p>';
	}

	#Edit user profile
	else if (isset($_GET['edit']) && $_GET['edit'] != '') {
		if ($_SESSION['user']['rola'] == 1) {
			$query  = "SELECT * FROM korisnik";
			$query .= " WHERE id = " . $_GET['edit'];
			$result = @mysqli_query($MySQL, $query);
			$row = @mysqli_fetch_array($result);
			$checked_archive = false;
			
			print '
			<h2>Uredite korisnički profil</h2>
			<div class="forma">
				<form action="" id="urediKorisnikaForma" name="urediKorisnikaForma" method="POST">
					<input type="hidden" id="_action_" name="_action_" value="TRUE">
					<input type="hidden" id="edit" name="edit" value="' . $_GET['edit'] . '">
					
					<label for="ime">Ime *</label>
					<input type="text" id="ime" name="ime" value="' . $row['ime'] . '" placeholder="Vaše ime..." required>
					
					<label for="prezime">Prezime *</label>
					<input type="text" id="prezime" name="prezime" value="' . $row['prezime'] . '" placeholder="Vaše prezime..." required>
						
					<label for="email">E-mail adresa *</label>
					<input type="email" id="email" name="email"  value="' . $row['email'] . '" placeholder="Vaš e-mail.." required>
					
					<label for="korisnickoIme">Korisničko ime *<small>(Korisničko ime mora imati minimalno 5 i maksimalno 10 znakova)</small></label>
					<input type="text" id="korisnickoIme" name="korisnickoIme" value="' . $row['korisnickoIme'] . '" pattern=".{5,10}" placeholder="Korisničko ime..." required><br>
					
					<label for="drzava">Država:</label>
					<select name="drzava" id="drzava">
						<option value="">molimo odaberite</option>';
						#odaberi sve države iz baze ntpws, tablice drzave
						$_query  = "SELECT * FROM drzave";
						$_result = @mysqli_query($MySQL, $_query);
						while($_row = @mysqli_fetch_array($_result)) {
							print '<option value="' . $_row['drzavaKod'] . '"';
							if ($row['drzava'] == $_row['drzavaKod']) { print ' selected'; }
							print '>' . $_row['drzavaIme'] . '</option>';
						}
					print '
					</select>
					
					<label for="grad">Grad</label>
					<input type="text" id="grad" name="grad" value="' . $row['grad'] . '" placeholder="Grad...">

					<label for="ulica">Ulica</label>
					<input type="text" id="ulica" name="ulica" value="' . $row['ulica'] . '" placeholder="Ulica...">

					<label for="datumRodenja">Datum rođenja *</label>
					<input type="date" id="datumRodenja" name="datumRodenja" value="' . $row['datumRodenja'] . '" required>
						
					<label for="arhiva">Arhiva:</label>
					<input type="radio" name="arhiva" value="Y"'; if($row['arhiva'] == 'Y') { echo ' checked="checked"'; $checked_archive = true; } echo ' /> DA &nbsp;&nbsp;
					<input type="radio" name="arhiva" value="N"'; if($checked_archive == false) { echo ' checked="checked"'; } echo ' /> NE

					<label for="rola">Rola:</label>
					<select name="rola" id="rola">';
						#odaberi sve države iz baze ntpws, tablice drzave
						$_query  = "SELECT * FROM role";
						$_result = @mysqli_query($MySQL, $_query);
						while($_row = @mysqli_fetch_array($_result)) {
							print '<option value="' . $_row['id'] . '"';
							if ($row['id_role'] == $_row['id']) { print ' selected'; }
							print '>' . $_row['naziv'] . '</option>';
						}
					print '
					</select>					
					<input type="submit" value="Potvrdi">
				</form>
			</div>
			<p><a href="index.php?menu=' . $menu . '&amp;action=' . $action . '"><i class="fa-solid fa-arrow-left"></i></a></p>';
		}
		else {
			print '<p>Zabranjeno</p>';
		}
	}

	#Approve users
	else if (isset($_GET['approve']) && $_GET['approve'] != ''){
		$query  = "UPDATE korisnik SET odobren = 'Y'";
        $query .= " WHERE id = " . (int)$_GET['approve'];
        $query .= " LIMIT 1";
        $result = @mysqli_query($MySQL, $query);

		$_SESSION['message'] = '<p>Uspješno ste odobrili korisnika!</p>';
		
		# Redirect
		header("Location: index.php?menu=8&action=1");
	}

	else {
		print '
		<h2>Lista korisnika</h2>
		<div id="korisnici">
			<table>
				<thead>
					<tr>
						<th width="16"></th>
						<th width="16"></th>
						<th width="16"></th>
						<th>Ime</th>
						<th>Prezime</th>
						<th>E-mail</th>
						<th width="16"></th>
						<th width="16"></th>
						<th width="16"></th>
					</tr>
				</thead>
				<tbody>';
				$query  = "SELECT * FROM korisnik";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '
					<tr>
						<td><a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;id=' .$row['id']. '"><img src="img/user.png" alt="korisnik"></a></td>
						<td>';
							if ($_SESSION['user']['rola'] == 1 || $_SESSION['user']['rola'] == 2) {
								print '<a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;edit=' .$row['id']. '"><img src="img/edit.png" alt="uredi"></a></td>';
							}
						print '
						<td>';
							if ($_SESSION['user']['rola'] == 1 || $_SESSION['user']['rola'] == 2) {
								print '<a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;delete=' .$row['id']. '"><img src="img/delete.png" alt="obriši"></a>';
							}
						print '	
						</td>
						<td><strong>' . $row['ime'] . '</strong></td>
						<td><strong>' . $row['prezime'] . '</strong></td>
						<td>' . $row['email'] . '</td>
						<td>';
							if ($row['arhiva'] == 'Y') { print '<img src="img/inactive.png" alt="" title="" />'; }
                            else if ($row['arhiva'] == 'N') { print '<img src="img/active.png" alt="" title="" />'; }
						print '
						</td>';
						if($row['odobren'] == 'N' && $_SESSION['user']['rola'] == 1){
							print '<td><a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;approve=' . $row['id'] . '"><img src="img/tick.png" alt="odobri"></a></td>
							<td><a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;delete=' . $row['id'] . '"><img src="img/cross.png" alt="odbij"></a></td>';
						}
						print '
					</tr>';
				}
			print '
				</tbody>
			</table>
		</div>';
	}
	
	# Close MySQL connection
	@mysqli_close($MySQL);
?>