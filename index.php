<?php
function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function lista($t) { return implode(", ", $t); }
function wiersz($nr, $tekst) { echo "<b>$nr</b> $tekst<br>"; }

function liczby($s) {
    $t = array();
    foreach (preg_split('/[\s,;]+/', trim($s), -1, PREG_SPLIT_NO_EMPTY) as $x) {
        if (is_numeric($x)) $t[] = $x + 0;
    }
    return $t;
}
function litery($s) {
    return preg_split('//u', preg_replace('/[\s,;]+/u', '', $s), -1, PREG_SPLIT_NO_EMPTY);
}
function zamien($c) {
    return $c === mb_strtoupper($c, 'UTF-8') ? mb_strtolower($c, 'UTF-8') : mb_strtoupper($c, 'UTF-8');
}
function addStr($a, $b) {
    $r = ''; $c = 0; $i = strlen($a) - 1; $j = strlen($b) - 1;
    while ($i >= 0 || $j >= 0 || $c) {
        $s = $c;
        if ($i >= 0) $s += (int)$a[$i--];
        if ($j >= 0) $s += (int)$b[$j--];
        $r = ($s % 10) . $r;
        $c = intdiv($s, 10);
    }
    return $r;
}
function zadanie3($tab, $nazwa) {
    if (count($tab) != 10) {
        echo "<b>$nazwa</b> <span style='color:red'>podaj dokładnie 10 liter (podano " . count($tab) . ").</span><br><br>";
        return;
    }
    echo "<b>$nazwa</b><br>Tablica: " . e(lista($tab)) . "<br>";
    $z = array();
    foreach ($tab as $c) $z[] = zamien($c);
    echo "Po zamianie wielkości liter: " . e(lista($z)) . "<br>";
    $l = $tab;
    shuffle($l);
    echo "W losowej kolejności: " . e(lista($l)) . "<br><br>";
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Tablice w PHP</title>
<style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 0 10px; }
    h2 { margin-bottom: 4px; }
    p { margin: 2px 0 6px; color: #555; }
    div { background: #eee; padding: 6px; margin-bottom: 6px; line-height: 1.5; overflow-wrap: anywhere; }
    input { width: 260px; }
    input.m { width: 70px; }
    hr { margin: 16px 0; }
</style>
</head>
<body>

<h1>Tablice w PHP</h1>

<!-- ZADANIE 1 -->
<h2>Zad 1</h2>
<p>Tablica tab1: 7, 3, 1, 6, 9, 5, 4, 10, 2, 2 (indeksy liczone od 0).</p>
<div><?php
$tab1 = array(7, 3, 1, 6, 9, 5, 4, 10, 2, 2);

wiersz("1.1", "Komórka [5] = " . $tab1[5]);
$tab1[7] = 12;
wiersz("", "Po zmianie komórki [7] na 12: " . lista($tab1));

$tab2 = array();
for ($i = 0; $i < count($tab1); $i++) {
    $tab2[$i] = $tab1[$i];
}
wiersz("1.2", "tab2 = " . lista($tab2));

$tab3 = array();
for ($i = 0; $i < count($tab1); $i++) {
    $tab3[$i] = $tab1[$i] + $tab2[$i];
}
wiersz("1.3", "tab3 = " . lista($tab3));

for ($i = 0; $i < count($tab1); $i++) {
    $tab2[$i] = $tab1[count($tab1) - 1 - $i];
}
wiersz("1.4", "tab2 (odwrotnie) = " . lista($tab2));
?></div>
<hr>

<!-- ZADANIE 2 -->
<h2>Zad 2</h2>
<p>Podaj liczby oddzielone przecinkami lub spacjami.</p>
<?php $t2 = isset($_POST['t2']) ? $_POST['t2'] : "5, 3, 8, 3, 13, 9, 33, 2"; ?>
<form method="post">
    <input name="t2" value="<?= e($t2) ?>">
    <button name="zad" value="2">Oblicz</button>
</form>
<div><?php
$t = liczby($t2);
$n = count($t);
if ($n == 0) {
    echo "Podaj przynajmniej jedną liczbę.";
} else {
    wiersz("2.1", "Tablica: " . lista($t));

    $min = $t[0]; $max = $t[0];
    foreach ($t as $x) {
        if ($x < $min) $min = $x;
        if ($x > $max) $max = $x;
    }
    wiersz("2.2", "Najmniejszy: $min, największy: $max");

    wiersz("2.3", "Średnia: " . round(array_sum($t) / $n, 2));

    $trojki = 0;
    foreach ($t as $x) $trojki += substr_count((string)abs($x), "3");
    wiersz("2.4", "Cyfra 3 występuje: $trojki razy");

    $s = $t;
    sort($s);
    wiersz("2.5", "Posortowana rosnąco: " . lista($s));

    $med = $n % 2 ? $s[intdiv($n, 2)] : ($s[$n / 2 - 1] + $s[$n / 2]) / 2;
    wiersz("2.6", "Mediana: $med");

    wiersz("2.7", "3 najmniejsze: " . lista(array_slice($s, 0, 3)) . "; 3 największe: " . lista(array_slice($s, -3)));

    $kw = array();
    foreach ($t as $x) $kw[] = $x * $x;
    wiersz("2.8", "Kwadraty: " . lista($kw));

    $parz = 0; $nieparz = 0; $przez3 = 0;
    foreach ($t as $x) {
        if ($x % 2 == 0) $parz++; else $nieparz++;
        if ($x % 3 == 0) $przez3++;
    }
    wiersz("2.9", "Parzystych: $parz, nieparzystych: $nieparz");
    wiersz("2.10", "Podzielnych przez 3: $przez3");
}
?></div>
<hr>

<!-- ZADANIE 3 -->
<h2>Zad 3</h2>
<p>Tablica 10 elementów wypełniona literami albo 10-literowym wyrazem.</p>
<?php
$l3 = isset($_POST['l3']) ? $_POST['l3'] : "a, B, c, D, e, F, g, H, i, J";
$w3 = isset($_POST['w3']) ? $_POST['w3'] : "Informatyk";
?>
<form method="post">
    10 liter: <input name="l3" value="<?= e($l3) ?>"><br>
    10-literowy wyraz: <input name="w3" value="<?= e($w3) ?>">
    <button name="zad" value="3">Wykonaj</button>
</form>
<div><?php
echo "<b>3.1</b> ";
zadanie3(litery($l3), "Z liter:");
echo "<b>3.2</b> ";
zadanie3(litery($w3), "Z wyrazu:");
?></div>
<hr>

<!-- ZADANIE 4 -->
<h2>Zad 4</h2>
<p>Tablica 100 losowych liczb całkowitych (zakres 1-50).</p>
<?php
$A = isset($_POST['tab4']) ? liczby($_POST['tab4']) : array();
if (count($A) != 100) {
    $A = array();
    for ($i = 0; $i < 100; $i++) $A[] = rand(1, 50);
}
$a4 = isset($_POST['a4']) && is_numeric($_POST['a4']) ? $_POST['a4'] + 0 : 25;
?>
<form method="post">
    <input type="hidden" name="tab4" value="<?= e(lista($A)) ?>">
    Liczba a: <input class="m" name="a4" value="<?= e($a4) ?>">
    <button name="zad" value="4">Znajdź (pkt 5)</button>
</form>
<div><?php
echo "Tablica A: " . lista($A) . "<br><br>";

$max = $A[0]; $ile = 0;
foreach ($A as $x) if ($x > $max) $max = $x;
foreach ($A as $x) if ($x == $max) $ile++;
wiersz("4.1", "Największa liczba: $max, występuje $ile razy");

$nieparz = array();
foreach ($A as $x) if ($x % 2 != 0) $nieparz[] = $x;
wiersz("4.2", "Liczby nieparzyste: " . lista($nieparz));

$nieparzIdx = array();
for ($i = 1; $i < count($A); $i += 2) $nieparzIdx[] = $A[$i];
wiersz("4.3", "Liczby z nieparzystych indeksów: " . lista($nieparzIdx));

$przedzial = array();
foreach ($A as $x) if ($x >= 5 && $x < 15) $przedzial[] = $x;
wiersz("4.4", "W przedziale &lt;5, 15) jest " . count($przedzial) . " liczb: " . lista($przedzial));

$najblizszy = $A[0];
foreach ($A as $x) {
    if (abs($x - $a4) < abs($najblizszy - $a4)) $najblizszy = $x;
}
wiersz("4.5", "Najbliższy liczbie a = $a4 jest element: $najblizszy");

$imin = 0;
for ($i = 1; $i < count($A); $i++) if ($A[$i] < $A[$imin]) $imin = $i;
$poprz = $imin > 0 ? $A[$imin - 1] : "brak";
$nast = $imin < count($A) - 1 ? $A[$imin + 1] : "brak";
wiersz("4.6", "Minimum: {$A[$imin]} (indeks $imin), poprzednik: $poprz, następnik: $nast");

$nowa = array();
foreach ($A as $x) if ($x > 10) $nowa[] = $x;
wiersz("4.7", "Nowa tablica (rozmiar " . count($nowa) . "): " . lista($nowa));

$B = array(); $suma = 0;
for ($i = 0; $i < count($A); $i++) {
    $suma += $A[$i];
    $B[$i] = $suma;
}
wiersz("4.8", "Tablica B (sumy częściowe): " . lista($B));

$C = $A;
rsort($C);
wiersz("4.9", "Posortowana malejąco: " . lista($C));

$ilosci = array_count_values($A);
ksort($ilosci);
$powt = array();
foreach ($ilosci as $liczba => $ile) {
    if ($ile >= 3) $powt[] = "$liczba (x$ile)";
}
wiersz("4.10", "Występują co najmniej 3 razy: " . lista($powt));
?></div>
<hr>

<!-- ZADANIE 5 -->
<h2>Zad 5</h2>
<p>Pusta tablica 100 elementów wypełniana na różne sposoby.</p>
<div><?php
$T = array();
$T[0] = "1"; $T[1] = "1";
for ($i = 2; $i < 100; $i++) $T[$i] = addStr($T[$i - 1], $T[$i - 2]);
wiersz("5.1", "Fibonacci: " . lista($T));
echo "<br>";

$T = array();
$T[0] = "1";
for ($i = 1; $i < 100; $i++) $T[$i] = addStr($T[$i - 1], $T[$i - 1]);
wiersz("5.2", "Potęgi liczby 2 (2^0 ... 2^99): " . lista($T));
echo "<br>";

$T = array();
for ($i = 0; $i < 100; $i++) $T[$i] = 3 * ($i + 1);
wiersz("5.3", "3, 6, 9, 12, ...: " . lista($T));
echo "<br>";

$T = array();
$T[0] = "2";
for ($i = 1; $i < 100; $i++) $T[$i] = addStr($T[$i - 1], $T[$i - 1]);
wiersz("5.4", "2, 4, 8, 16, 32, ...: " . lista($T));
?></div>

</body>
</html>