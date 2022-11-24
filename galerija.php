<?php 
	$slikeOpisi = [
        "Promotivno fotografiranje za Karlovačko",
        "Promotivno fotografiranje boksača Noa Ježek",
        "Promotivno fotografiranje za Jameson",
        "Obiteljsko fotografiranje",
        "Obiteljsko fotografiranje",
        "Fotografiranje interijera",
        "Fotografiranje eksterijera",
        "Promotivno fotografiranje nakita Fabula",
        "Fotografiranje krštenja",
        "Fotografiranje krštenja",
        "Fotografiranje krštenja",
        "Promotivno fotografiranje za Hair Expo"
    ];
    
    print '
	<h1>Galerija</h1>
    <div id="galerija2">';

    for($i = 0; $i < 12; $i++) {
        echo '<figure>
                <a href="img/galerija/' . $i + 1 . '.jpg" target="_blank"><img src="img/galerija/' . $i + 1 . '.jpg" alt="' . $slikeOpisi[$i] . '" title="' . $slikeOpisi[$i] . '"></a>
                <figcaption>' . $slikeOpisi[$i] . '<figcaption>
            </figure>';
    };

    print '</div>';

    
?>