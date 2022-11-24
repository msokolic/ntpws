<?php 
	
	#Add news
	if (isset($_POST['_action_']) && $_POST['_action_'] == 'add_news') {
		$_SESSION['message'] = '';
		# htmlspecialchars — Convert special characters to HTML entities
		# http://php.net/manual/en/function.htmlspecialchars.php
		if ($_SESSION['user']['rola'] == 3){
			$query  = "INSERT INTO novosti (naslov, opis, arhiva, odobreno)";
			$query .= " VALUES ('" . htmlspecialchars($_POST['naslov'], ENT_QUOTES) . "', '" . htmlspecialchars($_POST['opis'], ENT_QUOTES) . "', '" . $_POST['arhiva'] . "', 'N')";
		} else {
			$query  = "INSERT INTO novosti (naslov, opis, arhiva)";
			$query .= " VALUES ('" . htmlspecialchars($_POST['naslov'], ENT_QUOTES) . "', '" . htmlspecialchars($_POST['opis'], ENT_QUOTES) . "', '" . $_POST['arhiva'] . "')";
		}
		
		$result = @mysqli_query($MySQL, $query);
		
		$ID = mysqli_insert_id($MySQL);
		
		# slike      
		// Count # of uploaded files in array
		$count = 0;

		// Loop through each file
		if ($_FILES['slike']['name'][0] != "") {
			foreach ($_FILES['slike']['name'] as $filename) {

				# strtolower - Returns string with all alphabetic characters converted to lowercase. 
				# strrchr - Find the last occurrence of a character in a string
				$ext = strtolower(strrchr($_FILES['slike']['name'][$count], "."));
				
				$_picture = $ID . '-' . rand(1,100) . $ext;
				copy($_FILES['slike']['tmp_name'][$count], "img/novosti/" . $_picture);
					
				if ($ext == '.jpg' || $ext == '.png' || $ext == '.gif') { # test if format is picture
					$query2  = "INSERT INTO slike (naziv, opis, id_novosti)";
					$query2 .= " VALUES ('" . $_picture . "', '" . $filename . "', '" . $ID . "')";
					$result2 = @mysqli_query($MySQL, $query2);
				}
				$count = $count + 1;
					
			}
		}

		$_SESSION['message'] .= '<p>Uspješno ste dodali sliku/slike (' . $count . ').</p>';
		$_SESSION['message'] .= '<p>Uspješno ste dodali novost!</p>';
		
		# Redirect
		header("Location: index.php?menu=8&action=2");
	}
	
	# Update news
	if (isset($_POST['_action_']) && $_POST['_action_'] == 'edit_news') {
		$query  = "UPDATE novosti SET naslov = '" . htmlspecialchars($_POST['naslov'], ENT_QUOTES) . "', opis = '" . htmlspecialchars($_POST['opis'], ENT_QUOTES) . "', arhiva = '" . $_POST['arhiva'] . "'";
        $query .= " WHERE id = " . (int)$_POST['edit'];
        $query .= " LIMIT 1";
        $result = @mysqli_query($MySQL, $query);
		
		# slike
		if($_POST['obrisiSlike'] == 'Y'){
			$query2  = "DELETE FROM slike";
        	$query2 .= " WHERE id_novosti = " . (int)$_GET['edit'];
        	$result2 = @mysqli_query($MySQL, $query2);  
		}

		// Count # of uploaded files in array
		$count = 0;
		// Loop through each file
		if ($_FILES['slike']['name'][0] != "") {
			foreach ($_FILES['slike']['name'] as $filename) {

				# strtolower - Returns string with all alphabetic characters converted to lowercase. 
				# strrchr - Find the last occurrence of a character in a string
				$ext = strtolower(strrchr($_FILES['slike']['name'][$count], "."));
					
				$_picture = (int)$_GET['edit'] . '-' . rand(1,100) . $ext;
				copy($_FILES['slike']['tmp_name'][$count], "img/novosti/" . $_picture);
						
				if ($ext == '.jpg' || $ext == '.png' || $ext == '.gif') { # test if format is picture
					$query2  = "INSERT INTO slike (naziv, opis, id_novosti)";
					$query2 .= " VALUES ('" . $_FILES['slike']['name'][$count] . "', '" . $filename . "', '" . (int)$_GET['edit'] . "')";
					$result2 = @mysqli_query($MySQL, $query2);
				}
				
				$count = $count + 1;	
			
			}
			$_SESSION['message'] = '<p>Uspješno ste dodali sliku/slike (' . $count . ').</p>';
		}
		
		$_SESSION['message'] .= '<p>Uspješno ste promijenili vijest!</p>';
		
		# Redirect
		header("Location: index.php?menu=8&action=2");
	}
	# End update news
	
	# Delete news and reject news
	if (isset($_GET['delete']) && $_GET['delete'] != '') {
		
		# Delete picture
        $query  = "DELETE FROM slike";
        $query .= " WHERE id_novosti = " . (int)$_GET['delete'];
        $result = @mysqli_query($MySQL, $query);         
		
		# Delete news
		$query  = "DELETE FROM novosti";
		$query .= " WHERE id = " . (int)$_GET['delete'];
		$query .= " LIMIT 1";
		$result = @mysqli_query($MySQL, $query);

		$_SESSION['message'] = '<p>Uspješno ste obrisali novost!</p>';
		
		# Redirect
		header("Location: index.php?menu=8&action=2");
	}
	# End delete news
	
	
	#Show news info
	if (isset($_GET['id']) && $_GET['id'] != '') {
		$query  = "SELECT * FROM novosti";
		$query .= " WHERE id = " . $_GET['id'];
		$query .= " ORDER BY datum DESC";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);

		$query2  = "SELECT * FROM slike";
        $query2 .= " WHERE id_novosti = " . $_GET['id'];
        $result2 = @mysqli_query($MySQL, $query2);
		
		print '
            <script>
                let main = Array.from(document.getElementsByTagName("main"))[0];
                main.className = "novost";
            </script>
            
            <h1>Novosti</h1>
            <div id="galerija">';
            
            while($row2 = @mysqli_fetch_array($result2)){
            print '
                <figure>
                    <img src="img/novosti/' . $row2['naziv'] . '" alt="' . $row2['opis'] . '" title="' . $row2['opis'] . '">
                    <figcaption>' . $row2['opis'] . '</figcaption>
                </figure>';
        }

        print '
            </div>
            <hr/>
            <div class="novosti">
                <h2>' . $row['naslov'] . '</h2>
                <p><time datetime="' . $row['datum'] . '">' . pickerDateToMysql($row['datum']) . '</time></p>
                <p>'  . $row['opis'] . '</p>
                <p><a href="index.php?menu=' . $menu . '&amp;action=' . $action . '"><i class="fa-solid fa-arrow-left"></i></a></p>
            </div>';
	}
	
	#Add news 
	else if (isset($_GET['add']) && $_GET['add'] != '') {
		
		print '
		<h2>Dodaj novost</h2>
		<div class="forma">
			<form action="" id="dodajNovosForma" name="dodajNovosForma" method="POST" enctype="multipart/form-data">
				<input type="hidden" id="_action_" name="_action_" value="add_news">
				
				<label for="naslov">Naslov *</label>
				<input type="text" id="naslov" name="naslov" placeholder="Naslov novosti..." required>

				<label for="opis">Opis *</label>
				<textarea id="opis" name="opis" placeholder="Opis novosti..." required></textarea>
					
				<label for="slika">Slika</label>
				<input type="file" id="slika" name="slike[]" multiple>
							
				<label for="arhiva">Arhiva:</label>
				<input type="radio" name="arhiva" value="Y"> DA &nbsp;&nbsp;
				<input type="radio" name="arhiva" value="N" checked> NE

				<input type="submit" value="Dodaj">
			</form>
		</div>
		<p><a href="index.php?menu=' . $menu . '&amp;action=' . $action . '"><i class="fa-solid fa-arrow-left"></i></a></p>';
	}

	#Edit news
	else if (isset($_GET['edit']) && $_GET['edit'] != '') {
		$query  = "SELECT * FROM novosti";
		$query .= " WHERE id = " . $_GET['edit'];
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		$checked_archive = false;

		$query2  = "SELECT * FROM slike";
		$query2 .= " WHERE id_novosti = " . $row['id'];
		$result2 = @mysqli_query($MySQL, $query2);
		$count = mysqli_num_rows($result2);

		print '
		<h2>Uredi novost</h2>
		<div class="forma">
			<form action="" id="urediNovostForma" name="urediNovostForma" method="POST" enctype="multipart/form-data">
				<input type="hidden" id="_action_" name="_action_" value="edit_news">
				<input type="hidden" id="edit" name="edit" value="' . $row['id'] . '">
				
				<label for="naslov">Naslov *</label>
				<input type="text" id="naslov" name="naslov" value="' . $row['naslov'] . '" placeholder="Naslov novosti..." required>
				
				<label for="opis">Opis *</label>
				<textarea id="opis" name="opis" placeholder="Opis novosti..." required>' . $row['opis'] . '</textarea>
					
				<label for="slika">Slika</label>
				<input type="file" id="slika" name="slike[]" multiple>
				
				<label for="obrisiSlike">Obriši stare slike (' . $count . ')</label>
				<input type="radio" name="obrisiSlike" value="Y"'; if($count == 0) print 'disabled';  print'> DA &nbsp;&nbsp;
				<input type="radio" name="obrisiSlike" value="N" checked> NE
							
				<label for="arhiva">Arhiva:</label>
				<input type="radio" name="arhiva" value="Y"'; if($row['arhiva'] == 'Y') { echo ' checked="checked"'; $checked_archive = true; } echo ' /> DA &nbsp;&nbsp;
				<input type="radio" name="arhiva" value="N"'; if($checked_archive == false) { echo ' checked="checked"'; } echo ' /> NE
				
				<input type="submit" value="Potvrdi">
			</form>
		</div>
		<p><a href="index.php?menu=' . $menu . '&amp;action=' . $action . '"><i class="fa-solid fa-arrow-left"></i></a></p>';
	}

	#Approve news
	else if (isset($_GET['approve']) && $_GET['approve'] != ''){
		$query  = "UPDATE novosti SET odobreno = 'Y'";
        $query .= " WHERE id = " . (int)$_GET['approve'];
        $query .= " LIMIT 1";
        $result = @mysqli_query($MySQL, $query);

		$_SESSION['message'] = '<p>Uspješno ste odobrili vijest!</p>';
		
		# Redirect
		header("Location: index.php?menu=8&action=2");
	}

	else {
		print '
		<h2>Novosti</h2>
		<div id="novosti">
			<table>
				<thead>
					<tr>
						<th width="16"></th>
						<th width="16"></th>
						<th width="16"></th>
						<th>Naslov</th>
						<th>Opis</th>
						<th>Datum</th>
						<th width="16"></th>
						<th width="16"></th>
						<th width="16"></th>
					</tr>
				</thead>
				<tbody>';
				$query  = "SELECT * FROM novosti";
				$query .= " ORDER BY datum DESC";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '
					<tr>
						<td><a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;id=' . $row['id'] . '"><img src="img/info.png" alt="korisnik"></a></td>
						<td>';
						if ($_SESSION['user']['rola'] == 1 || $_SESSION['user']['rola'] == 2) {
							print '<a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;edit=' . $row['id'] . '"><img src="img/edit.png" alt="uredi"></a>';
						}
						print '
						<td>';
							if ($_SESSION['user']['rola'] == 1) {
								print '<a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;delete=' . $row['id'] . '"><img src="img/delete.png" alt="obriši"></a>';
							}
						print '	
						</td>
						<td>' . $row['naslov'] . '</td>
						<td>';
						if(strlen($row['opis']) > 160) {
                            echo substr(strip_tags($row['opis']), 0, 160).'...';
                        } else {
                            echo strip_tags($row['opis']);
                        }
						print '
						</td>
						<td>' . pickerDateToMysql($row['datum']) . '</td>
						<td>';
							if ($row['arhiva'] == 'Y') { print '<img src="img/inactive.png" alt="" title="" />'; }
                            else if ($row['arhiva'] == 'N') { print '<img src="img/active.png" alt="" title="" />'; }
						print '
						</td>';
						if($row['odobreno'] == 'N' && $_SESSION['user']['rola'] == 1){
							print '<td><a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;approve=' . $row['id'] . '"><img src="img/tick.png" alt="odobri"></a></td>
							<td><a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;delete=' . $row['id'] . '"><img src="img/cross.png" alt="odbij"></a></td>';
						}
						print '
					</tr>';
				}
			print '
				</tbody>
			</table>
			<a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;add=true" class="dodaj">Dodaj novost</a>
		</div>';
	}
	
	# Close MySQL connection
	@mysqli_close($MySQL);
?>