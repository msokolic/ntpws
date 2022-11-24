<?php
	
	if (isset($action) && $action != '') {
		$query  = "SELECT * FROM novosti";
		$query .= " WHERE id = " . $_GET['action'];
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);

        $query2  = "SELECT * FROM slike";
        $query2 .= " WHERE id_novosti = " . $row['id'];
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
                    <p><a href="index.php?menu=2"><i class="fa-solid fa-arrow-left"></i></a></p>
                </div>';
	}
	else {
		print '<h1>Novosti</h1>';
		$query  = "SELECT * FROM novosti";
		$query .= " WHERE arhiva='N' AND odobreno = 'Y'";
		$query .= " ORDER BY datum DESC";
		$result = @mysqli_query($MySQL, $query);
        print '
			<div class="novosti">';
		while($row = @mysqli_fetch_array($result)) {
			$query2  = "SELECT * FROM slike";
            $query2 .= " WHERE id_novosti = " . $row['id'];
            $result2 = @mysqli_query($MySQL, $query2);
            $row2 = @mysqli_fetch_array($result2);
            if (!is_null($row2)) {
                print '<div class="centar"><a href="index.php?menu=' . $menu . '&amp;action=' . $row['id'] . '"><img src="img/novosti/' . $row2['naziv'] . '" alt="' . $row['opis'] . '" title="' . $row['opis'] . '"></a></div>';
            }
            print '				
				<a href="index.php?menu=' . $menu . '&amp;action=' . $row['id'] . '"><h2>' . $row['naslov'] . '</h2></a>';
				if(strlen($row['opis']) > 300) {
					print '<p>' . substr(strip_tags($row['opis']), 0, 300).'... <a href="index.php?menu=' . $menu . '&amp;action=' . $row['id'] . '"><i class="fa-solid fa-arrow-right"></i></a></p>';
				} else {
					print '<p>' . strip_tags($row['opis']) . ' <a href="index.php?menu=' . $menu . '&amp;action=' . $row['id'] . '"><i class="fa-solid fa-arrow-right"></i></a></p>';
				}
				print '
				<p><time datetime="' . $row['datum'] . '">' . pickerDateToMysql($row['datum']) . '</time></p>
				<hr/>';
		}
        print '
            </div>';
	}
?>