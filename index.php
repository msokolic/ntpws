<?php 
    # Stop Hacking attempt
	define('__APP__', TRUE);
	
	# Start session
    session_start();

    # konekcija na bazu
    include ("dbconn.php");

    if(isset($_GET['menu'])){
        $menu = (int)$_GET['menu'];
    } else {
        $menu = 1;
    }

    if(isset($_GET['action'])){
        $action = (int)$_GET['action'];
    }

    if(!isset($_POST['_action_'])){
        $_POST['_action_'] = FALSE;
    }

    # funkcije
    include_once("functions.php");

    print '
    <!DOCTYPE HTML>
    <html>
        <head>
            <title>Vedran Tolić - foto</title>
            
            <meta charset="UTF-8">
            <meta name="description" content="web aplikacija foto studia">
            <meta name="keywords" content="web, aplikacija, servis, foto, fotografija, fotografiranje, usluga, Vedran Tolić">
            <meta name="author" content="Marica Sokolić">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            
            <!--font-->
            <link href="https://api.fontshare.com/css?f[]=clash-grotesk@300,400,600&display=swap" rel="stylesheet">
            <!--ikone-->
            <script src="https://kit.fontawesome.com/b8e9b7f961.js" crossorigin="anonymous"></script>
            <!--favicon-->
            <link rel="icon" type="image/png" sizes="16x16" href="img/favicon.png">
            <!--css-->
            <link rel="stylesheet" href="style2.css">
        </head>
        <body>
            <header>
            <div class="hero"></div>
                <nav id="nav" class="original">';
                    include("menu.php");
                print '
                </nav>
            </header>
            <main>';

                if (isset($_SESSION['message'])) {
                    print $_SESSION['message'];
                    unset($_SESSION['message']);
                }

                # Početna stranica
                if ($menu == 0 || $menu == 1) { include("pocetna.php"); }

                # Novosti
                else if ($menu == 2) { include("novosti.php"); }

                # Kontakt
                else if ($menu == 3) { include("kontakt.php"); }

                # O meni
                else if ($menu == 4) { include("omeni.php"); }

                # Galerija
                else if ($menu == 5) { include("galerija.php"); }

                # Registracija
                else if ($menu == 6) { include("registracija.php"); }
                
                # Prijava
	            else if ($menu == 7) { include("prijava.php"); }
	
                # Admin
                else if ($menu == 8) { include("admin.php"); }

                # Poslan kontakt
                else if ($menu == 9) { include("posaljiKontakt.php"); }

                print '
            </main>
            <footer>
                <p>Copyright &copy; 2022 Marica Sokolić. <a href="https://github.com/msokolic?tab=repositories" target="_blank"><i class="fa-brands fa-github"></i></a></p>
            </footer>

            <script>
                function otvori(){
                    let x = document.getElementById("nav");
                    if (x.className === "original") {
                        x.className = "responsive";
                    } else {
                        x.className = "original";
                    }
                }';

                if($menu == 8){
                    print '
                    let x = Array.from(document.getElementsByTagName("a"))[5];
                    x.className = "active";
                    x.setAttribute("disabled", "");';
                } 
                else if ($menu == 2 && isset($action)) {
                    #ništa
                }
                else if ($menu == 9){
                    #ništa
                }
                else {
                    print '
                    let x = Array.from(document.getElementsByTagName("a"))[' . $menu - 1 . '];
                    x.className = "active";
                    x.setAttribute("disabled", "");';
                }
                
            print'
                
                let kIme = document.getElementById("korisnickoIme");
                                    
                function generirajIme(x) {
                    if (x.checked) {
                        kIme.setAttribute("disabled", "");
                        kIme.removeAttribute("required");
                        kIme.value = "";
                    }
                    else {
                        kIme.setAttribute("required", "");
                        kIme.removeAttribute("disabled");
                    }
                }
            
            </script>
        </body>
    </html>';
?>