

<?php
	session_start();

	require_once "connect.php";
	
	$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

	if($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	unset($_SESSION['blad_item']);
	unset($_SESSION['blad_item2']);
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Sklep internetowy</title>
	<link rel="stylesheet" href="adminstyle.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
	
</head>
<body>
	<div class="menu-bar"> 
	<ul>
		<?php
			echo "Witaj, ".$_SESSION['Imie']."!";
		?>
		<li class="active">Wybierz opcję admina</li>
		<li><a href="zamowienia.php">Zamówienia</a></li>
		<li><a href="nowy_przedmiot.php">Nowy przedmiot</a></li>
		<li><a href="usun_przedmiot.php">Usuń przedmiot</a></li>
		<li><a href="nowy_admin.php">Nowy admin</a></li>
		<li><a href="index.php">Wyloguj się </a><i class="fas fa-sign-out-alt"></i></li>
	</ul>
	</div>
	<style>
	.profile-main-container{
		width: 800px;
		height: 95px;
		background-image: linear-gradient(to right,#deeaee,#FFFECF,#C3EBF9,#C2F5CF,#FFFECF);
		border: 5px solid green;
		margin: 0 auto;
		margin-top: 50px;
		font-size: 50px;
		text-align: center;
	}
	.profile-main-container p
	{
		font-size: 50px;
		margin-top: 10px;
	}
	.container-for-goods{
		width: 800px;
		height: 75px;
		background-image: linear-gradient(to right,#deeaee,#FFFECF,#C3EBF9,#C2F5CF,#FFFECF);
		border: 5px solid green;
		margin: 0 auto;
		margin-top: 25px;
		font-size: 50px;
		text-align: center;
	}
	.list {
		float: left;
		font-size: 12px;
		text-align: left;
		margin-top: 10px;
		margin-left: 10px;
	}
	.date {
		font-size: 18px;
		margin-top: 20px;
		text-align: center;
		float: right;
		margin-right: 40px;
	}
	.date input[type="submit"]
	{
		margin-top: 20px;
		border: none;
		outline: none;
		height: 40px;
		width: 240px;
		margin-right: 75px;
		background: #fb2525;
		color: #fff;
		font-size: 15px;
		border-radius: 20px;
		
	}
	.date input[type="submit"]:hover
	{
		curson: pointer;
		background: #ffc107;
		color: #000;
	}
	</style>
	<div class="profile-main-container">
		<p>Zamówienia klientów!</p>
	</div>
	<?php
		$basic_height=200;
		$sql = "SELECT * FROM zamowienia";
		$rezultat = @$polaczenie->query($sql);
		while($row = $rezultat->fetch_assoc()) 
		{
			$sql_status = "SELECT * FROM statusy WHERE ID_Status='".$row["ID_Status"]."'";
			$rezultat_status = @$polaczenie->query($sql_status);
			$status_row = $rezultat_status->fetch_assoc();
			
			$sql2 = "SELECT * FROM produktyzamowienia WHERE ID_Zamowienia='".$row["ID_Zamowienia"]."'";
			$rezultat2 = @$polaczenie->query($sql2);
			$ile = $rezultat2->num_rows;
			
			$sql_user = "SELECT * FROM danelogowania WHERE ID_Klient='".$row["ID_Klient"]."'";
			$rezultat_user = @$polaczenie->query($sql_user);
			$row_user = $rezultat_user->fetch_assoc();
			
			$sql_adr = "SELECT * FROM daneadresowe WHERE unique_ID='".$row["Adres_unique_id"]."'";
			$rezultat_adr = @$polaczenie->query($sql_adr);
			$row_adr = $rezultat_adr->fetch_assoc();
			
			$sql_phone = "SELECT * FROM telefon WHERE ID_Telefon='".$row["ID_Klient"]."'";
			$rezultat_phone = @$polaczenie->query($sql_phone);
			$row_phone = $rezultat_phone->fetch_assoc();		
			/*
			echo $row_user["Imie"];
			echo $row_adr["Adres"];
			echo $row_phone["Telefon"].'<br>';
			$row_phone = $rezultat_phone->fetch_assoc();	
			echo $row_phone["Telefon"].'<br>';
			*/
			$kod_lewo=intval($row_adr["KodPocztowy"]/1000);
			$kod_prawo=$row_adr["KodPocztowy"]-$kod_lewo*1000;
			$adres_caly = $row_adr["Miasto"]." ".$kod_lewo."-".$kod_prawo." ".$row_adr["Adres"];
			if($row_adr["NrLokalu"]!=NULL)
			{
				$adres_caly=$adres_caly." m. ".$row_adr["NrLokalu"]; 
			}
			
			$odbiorca = "Nadano przez: ".$row_user["Imie"]." ".$row_user["Nazwisko"];
			//zwiekszamy $basic_height bo za malo miejsca bedzie
			if($ile>10)
			{
				$ile_zwiekszyc = 0;
				while($row2=$rezultat2->fetch_assoc()) $ile_zwiekszyc=$ile_zwiekszyc+1;
				$rozmiar_new = $ile_zwiekszyc - 10;
				$rozmiar_new = $rozmiar_new * 15 + 200; //15 pixeli na kazde nowe pole//200 to podstawowy rozmiar
				echo '<form action="informacje_o_zamowieniu.php" method="post">';
				echo '<div class="container-for-goods" style="height:'.$rozmiar_new.'px">';
				echo '	<div class="list">';
				$rezultat2 = @$polaczenie->query($sql2);
				$sql_products = "SELECT * FROM produkt WHERE";
				while($row2=$rezultat2->fetch_assoc())
				{
					
					$sql_get_name = "SELECT Nazwa FROM produkt WHERE ID_Produktu='".$row2["ID_Produktu"]."'";
					$rezultat_get_name = @$polaczenie->query($sql_get_name);
					$row_name = $rezultat_get_name->fetch_assoc();
					echo $row2["Sztuki"]."x ".$row_name["Nazwa"]."<br>";
				}
				echo '	</div>';
				echo '	<div class="date">';
				echo ' 	Numer kontrolny zamówienia: '.$row["ID_Zamowienia"]."<br>";
				echo '	Dostawa na adres: '.$adres_caly."<br>";
				echo 	$odbiorca."<br>";
				echo '	Email kontaktowy: '.$row_adr["Email"]."<br>";
				echo '	<input type="submit" value="Więcej informacji" />';
				echo '	<input type="hidden" name="ID_zam" value="'.$row["ID_Zamowienia"].'" />';
				echo '	</div>';
				echo '</div>';
				echo '</form>';
			}
			
			else //jest wystarczajaco duzo miejsca na 5 produktow
			{
				echo '<form action="informacje_o_zamowieniu.php" method="post">';
				echo '<div class="container-for-goods" style="height:200px">';
				echo '	<div class="list">';
				
				$sql_products = "SELECT * FROM produkt WHERE";
				while($row2=$rezultat2->fetch_assoc())
				{
					
					$sql_get_name = "SELECT Nazwa FROM produkt WHERE ID_Produktu='".$row2["ID_Produktu"]."'";
					$rezultat_get_name = @$polaczenie->query($sql_get_name);
					$row_name = $rezultat_get_name->fetch_assoc();
					echo $row2["Sztuki"]."x ".$row_name["Nazwa"]."<br>";
				}
				echo '	</div>';
				echo '	<div class="date">';
				echo ' 	Numer kontrolny zamówienia: '.$row["ID_Zamowienia"]."<br>";
				echo '	Dostawa na adres: '.$adres_caly."<br>";
				echo 	$odbiorca."<br>";
				echo '	Email kontaktowy: '.$row_adr["Email"]."<br>";
				echo '	<input type="submit" value="Więcej informacji" />';
				echo '	<input type="hidden" name="ID_zam" value="'.$row["ID_Zamowienia"].'" />';
				echo '	</div>';
				echo '</div>';
				echo '</form>';
			}
		}
	?>
</body>
</html>
