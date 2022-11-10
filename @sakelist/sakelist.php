<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>SAKE LIST ～さけリス～</title>
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />
  <link rel="stylesheet" href="css/reset.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/hamburger.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&family=Noto+Sans+JP:wght@400;700&family=Source+Sans+Pro:wght@400;700&display=swap" rel="stylesheet">
  <?php
  $dsn = 'mysql:dbname=sakelist;host=localhost';
  $user = 'root';
  $password = '';
  $pdo = new PDO($dsn, $user, $password);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  try {
    $sql = 'SELECT * FROM products LEFT OUTER JOIN procategory ON products.catId = procategory.catID';
    $stmt = $pdo->prepare($sql);
    $res = $stmt->execute();
    if($res){
      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    $sql = null;
    $sql = 'SELECT * FROM procategory';
    $stmt = $pdo->prepare($sql);
    $res = $stmt->execute();
    if($res){
      $proCat = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  } catch (PDOException $e) {
    print('Error:' . $e->getMessage());
    die();
  }

  $dbh = null;
  ?>
  
</head>

<body>
  <header>
    <h1>SAKE LIST</h1>
    <div id="ham-box">
      <div id="hamburger">
        <span></span><span></span><span></span>
      </div>
    </div>
  </header>
  <div id="search-sort">
    <select name="category" id="category" class="search-sort">
      <option value="0" selected>全て</option>
      <?php
      foreach ($proCat as $val) {
        echo '<option value="' . $val['catID'] . '">' . $val['catName'] . '</option>';
      }
      ?>
    </select>
    <input type="text" name="
    " id="proName" class="search-sort" placeholder="商品名から検索">
    <select name="sortby" id="sortby" class="search-sort">
      <?php
      $sortArray = ['在庫ピンチ順','名前順'];
      foreach($sortArray as $index => $option){
        echo '<option value="'.$index.'">'.$option.'</option>';
      }
      ?>
    </select>
  </div>
  <div id="proList">
  </div>




  <script src="js/jquery.min.js"></script>
  <script src="js/script.js"></script>
</body>

</html>