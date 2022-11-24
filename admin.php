<?php 
	if ($_SESSION['user']['valid'] == 'true') {
		
		print '
		<h1>Administracija</h1>
		<div id="admin">
			<ul>';
				if ($_SESSION['user']['rola'] == 1) {
					print '<li><a href="index.php?menu=8&amp;action=1">Korisnici</a></li>';
				}
				print '
				<li><a href="index.php?menu=8&amp;action=2">Novosti</a></li>
			</ul>';
			
			if (isset($action)){
				# Admin Users
				if ($action == 1) { include("admin/korisnici.php"); }
			
				# Admin News
				else if ($action == 2) { include("admin/novosti.php"); }
			}
			
		print '
		</div>';
	}
	else {
		$_SESSION['message'] = '<p>Molimo da se ulogirate sa svojim podatcima!</p>';
		header("Location: index.php?menu=8");
	}
?>