<?php 
	print '
	<h1>Kontakt forma</h1>
    <div class="forma">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d177891.7767980031!2d15.824247747527428!3d45.84011036318502!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4765d692c902cc39%3A0x3a45249628fbc28a!2sZagreb!5e0!3m2!1shr!2shr!4v1666261508294!5m2!1shr!2shr" width="100%" height="450" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
		<form action="index.php?menu=9" id="kontaktForma" name="kontaktForma" method="POST">
			<label for="ime">Ime *</label>
			<input type="text" id="ime" name="ime" placeholder="Vaše ime..." required>

			<label for="prezime">Prezime *</label>
			<input type="text" id="prezime" name="prezime" placeholder="Vaše prezime..." required>
				
			<label for="email">E-mail adresa *</label>
			<input type="email" id="email" name="email" placeholder="Vaš e-mail..." required>

			<label for="drzava">Država</label>
			<select id="drzava" name="drzava">
				<option value="" disabled>Molim odaberite</option>
                <option value="BA">Bosna i Hercegovina</option>
                <option value="ME">Crna Gora</option>
                <option value="HR" selected>Hrvatska</option>
                <option value="HU">Mađarska</option>
                <option value="SI">Slovenija</option>
                <option value="RS">Srbija</option>
			</select>

			<label for="opis">Opis *</label>
			<textarea id="opis" name="opis" placeholder="Napišite poruku..." style="height:200px" required></textarea>

			<input type="submit" value="Pošalji">
		</form>
    </div>';
?>