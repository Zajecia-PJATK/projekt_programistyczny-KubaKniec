<?php
session_start();
include ("config.php");
$zdjecie = $_POST['zdjecie'];
$tytul = $_POST['tytul'];
$opis = $_POST['opis'];
$opisDuzy = $_POST['opisDuzy'];
$cenaBez = $_POST['cenaBez'];
$cenaZ = $_POST['cenaZ'];
$ilosc = $_POST['ilosc'];
$id = $_POST['id'];



$connection = mysqli_connect("127.0.0.1","root","","sklep_internetowy");
if(isset($_POST['dodaj'])){
    if(isset($_SESSION['koszyk']))
    {
        $item_array_id = array_column($_SESSION['koszyk'],'item_id');
        if(!in_array($id,$item_array_id))
        {
            $count = count($_SESSION['koszyk']);
            $item_array = array(
                'item_id' => $_POST['id'],
                'item_tytul' => $tytul,
                'item_cenaBez' => $cenaBez,
                'item_cenaZ' => $cenaZ,
                'item_ilosc' => $ilosc,
                'item_ileProduktow' => $_POST['ileProduktow']
            );
            $_SESSION['koszyk'][$count] = $item_array;
        }
        else
        {
            echo '<script> alert("Przedmiot byl juz dodany")</script>';
            echo '<script>window.location="koszyk.php"</script>';
        }
    }
    else
    {
        $item_array = array(
            'item_id' => $_POST['id'],
            'item_tytul' => $tytul,
            'item_cenaBez' => $cenaBez,
            'item_cenaZ' => $cenaZ,
            'item_ilosc' => $ilosc,
            'item_ileProduktow' => $_POST['ileProduktow']

        );
        $_SESSION['koszyk'][0]=$item_array;
    }
    header('Location: koszyk.php');
}
if(empty($_SESSION['user_id'])){
    $_SESSION['user_id'] = 0;
}
$userId =  $_SESSION['user_id'];
$zapytanie2 = "SELECT* FROM user WHERE id='$userId'";

$selectNazwa = mysqli_query($connection, $zapytanie2);
$nazwa = mysqli_fetch_assoc($selectNazwa);
if(isset($_GET["action"]))
{
    if($_GET["action"] == "usun")
    {
        foreach($_SESSION["koszyk"] as $keys => $values)
        {
            if($values["item_id"] == $_GET["id"])
            {
                unset($_SESSION["koszyk"][$keys]);
                echo '<script>alert("Usunieto przedmiot")</script>';
                echo '<script>window.location="koszyk.php"</script>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css.css">

    <title>
        👕 KOSZULKI PREMIUM 👕
    </title>
    <?php
    if (!empty($_SESSION['user_id'])){
        echo
        '<div class="loginInfo">
        <ul>';
        echo 'Zalogowano jako:';
        echo $nazwa['nazwa'];

        echo '<a href="logout.php"><br>wyloguj</a> 
        </ul>
    </div>';
    }
    ?>

    <a href='main_site.php'><br>
        <img class="logo" src="logo.png" ></a>
    <div class="navigationbar">
        <nav>
            <ul>
                <a href="main_site.php">
                    <button>Strona glowna</button></a>
                <a href="info_page.php">
                    <button>O nas</button></a>
                <a href="koszyk.php">
                    <button>Koszyk</button></a>
                <a href="logowanie.php">
                    <button>Zaloguj</button></a>
            </ul>
        </nav>
    </div>
    <?php

    echo"
    <div class='produkt'>
        <img class='zdjecieKoszulki'  src='$zdjecie' width='350px' height='350px'>
        
        <br/><li > $tytul </li >
        <li >   Cena bez dostawy: $cenaBez zl</li >
        <li >   Cena z dostawą: $cenaZ zl</li >
        <li >   Pozostalo: $ilosc sztuk</li >
        <li >   Opis: $opisDuzy</li >
        
        <form method='post' action=''>
        <input type='submit' class='addToCart' name='dodaj' value='Dodaj do koszyka'>
        <input type='text' class='' name='ileProduktow' placeholder='Wpisz ilosc' value='1'>  
        <input type='hidden' value='$tytul' name='tytul'> 
        <input type='hidden' value='$zdjecie' name='zdjecie'> 
        <input type='hidden' value='$opis' name='opis'> 
        <input type='hidden' value='$cenaBez' name='cenaBez'> 
        <input type='hidden' value='$cenaZ' name='cenaZ'> 
        <input type='hidden' value='$ilosc' name='ilosc'>
        <input type='hidden' value='$opisDuzy' name='opisDuzy'>
        <input type='hidden' value='$id' name='id'>
        </form>
        </div>
        "
    ?>




</head>
<body>


</body>
<footer></footer>
</html>
