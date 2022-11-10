<?php
$dsn = 'mysql:dbname=sakelist;host=localhost';
$user = 'root';
$password = '';
$pdo = new PDO($dsn, $user, $password);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$proCat = intval(isset($_POST['JS_proCat']) ? $_POST["JS_proCat"] : NULL);
$proName = isset($_POST['JS_proName']) ? $_POST["JS_proName"] : NULL;
$sortby = intval(isset($_POST['JS_sort']) ? $_POST["JS_sort"] : NULL);

$SQL = <<<SQL
SELECT
    *,
    stock / basicStock as fillRate
FROM
    products
    LEFT OUTER JOIN procategory  ON products.catId = procategory.catID
SQL;
$WHERE = '';
$catflag = false;
$nameflag = false;

if (!empty($proCat)) {
    $WHERE .= "procategory.catID = :catID AND";
    $catflag = true;
}
if (!empty($proName)) {
    $proName = mb_convert_kana($proName, 'as');
    if (preg_match("/^[ぁ-ゞ]+$/u", $proName)) {
        $proName = mb_convert_kana($proName, 'h');
    }
    $ProName = '%' . $proName . '%';
    $WHERE .= " products.proName LIKE :proName OR proRuby LIKE :proRuby AND";
    $nameflag = true;
}
if (!empty($WHERE)) {
    //成形
    $WHERE = rtrim($WHERE, 'AND');
    $SQL .= " WHERE" . $WHERE;
}
if (!is_null($sortby)) {
    if ($sortby === 0) {
        $SQL .= " ORDER BY products.proClip , CASE products.basicStock WHEN '0' THEN 2 ELSE 1 END , fillRate";
    } else if ($sortby === 1) {
        $SQL .= " ORDER BY products.proClip , products.proRuby";
    }
}
$stmt = $pdo->prepare($SQL);
if ($catflag) {
    $stmt->bindParam(':catID', $proCat, PDO::PARAM_INT);
}
if ($nameflag) {
    $stmt->bindParam(':proName', $proName, PDO::PARAM_STR);
}
$stmt->execute();
$return = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($return, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
