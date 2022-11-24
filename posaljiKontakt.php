<?php
    print'
		<h1>Kontakt forma</h1>
		<div id="kontakt">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d177891.7767980031!2d15.824247747527428!3d45.84011036318502!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4765d692c902cc39%3A0x3a45249628fbc28a!2sZagreb!5e0!3m2!1shr!2shr!4v1666261508294!5m2!1shr!2shr" width="100%" height="450" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
		<p style="text-align:center; padding: 10px; background-color: #d7d6d6;border-radius: 5px;">Primili smo Vaš upit. Odgovorit ćemo unutar 24 sata.</p>';
				
    $EmailHeaders  = "MIME-Version: 1.0\r\n";
	$EmailHeaders .= "Content-type: text/html; charset=utf-8\r\n";
	$EmailHeaders .= "From: <msokolic@tvz.hr>\r\n";
	$EmailHeaders .= "Reply-To:<msokolic@tvz.hr>\r\n";
	$EmailHeaders .= "X-Mailer: PHP/".phpversion();
	$EmailSubject = 'Kontakt forma - ntpws';
	$EmailBody  = '
		    <html>
				<head>
				   <title>'.$EmailSubject.'</title>
				   <style>
					body {
					    background-color: #ffffff;
						font-family: Arial, Helvetica, sans-serif;
						font-size: 16px;
						padding: 0px;
						margin: 0px auto;
						width: 500px;
						color: #000000;
					}
					p {
						font-size: 14px;
					}
					a {
						color: #00bad6;
						text-decoration: underline;
						font-size: 14px;
					}
				   </style>
				</head>
				<body>
					<p>Ime: ' . $_POST['ime'] . '</p>
					<p>Prezime: ' . $_POST['prezime'] . '</p>
					<p>E-mail: <a href="mailto:' . $_POST['email'] . '">' . $_POST['email'] . '</a></p>
					<p>Država: ' . $_POST['drzava'] . '</p>
					<p>Opis: ' . $_POST['opis'] . '</p>
				</body>
			</html>';

	print '<p>Ime: ' . $_POST['ime'] . '</p>
			<p>Prezime: ' . $_POST['prezime'] . '</p>
			<p>E-mail: ' . $_POST['email'] . '</p>
			<p>Država: ' . $_POST['drzava'] . '</p>
			<p>Opis: ' . $_POST['opis'] . '</p>';
	
    mail($_POST['email'], $EmailSubject, $EmailBody, $EmailHeaders);
	
    print '</div>';
?>