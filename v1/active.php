<!DOCTYPE html>
<html lang="en-gb" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="shortcut icon" type="image/png" href="https://via.placeholder.com/16x16" >
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/hyper.css?v=1.2" />
    <script src="../js/uikit.js"></script>
</head>

<body>

<?php
require "../config.php";
session_start();
$userLogged = false;
$isAdmin = false;
$is_active = false;
$access_tok = 'null';
if (isset($_SESSION['username'])) {
  $userLogged = true;
  if ($_SESSION['roles'] === "admin") {
    $isAdmin = true;
  }
   if ($_SESSION['status'] === 'active'){
      $is_active =true;
  }
  if($_SESSION['access']!== null){
      $access_tok = $_SESSION['access'];
  }
}
if ($userLogged !== true) {
  return header("location: ./?message=user_not_logged");
}

include"./include/header.php"; 

$message = "";

if (isset($_GET['disapprove_user'])) {
    $userid =  $_GET['disapprove_user'];
    $upd = "UPDATE users SET status='inactive' WHERE id=$userid";
    if ($connect->query($upd) === TRUE) {
        return  $hyper->redirect_to("./active?message=user_disapproved&success=disapproved");
    } else {
        return $hyper->redirect_to("./active?message=error_ocurred&error=" . $connect->error);
    }
    $connect->close();
}

?>

<div class="uk-section uk-section-muted">
  <div class="uk-container">
    <div class="uk-background-default uk-border-rounded uk-box-shadow-small">
      <div class="uk-container uk-container-xsmall uk-padding-large">
        <article class="uk-article">
          <h1 class="uk-article-title">Active Users</h1>
          <div class="uk-article-content">
            <p class="uk-text-lead uk-text-muted">If you want to see current pending users then check <a href="./pending" class="access_link">here</a></p>
            <?php
             if ($hyper->get_parameter("message", "user_disapproved")) {
                                
              echo $hyper->create_notice_bar(
                  array(
                      "text" => "✅ User account disapproved.",
                      "css" => "text-success",
                      "ele" => "p"
                  )
              );
          }
            $seluser = "SELECT * from users where status = 'active'";
            $myconnect = mysqli_query($connect, $seluser);

            $tusers = mysqli_num_rows($myconnect);


            ?>
            <div class="uk-card card_ccv uk-card-category hyper_mt3 uk-card-default uk-card-hover uk-card-body uk-inline uk-border-rounded uk-width-1-1">
                <!-- <a class="uk-position-cover" href="article.html"></a> -->
                <h3 class="uk-card-title uk-margin-remove uk-text-primary green_title">Active - <span id="ccvCount"><?php echo $tusers; ?></span>
              
              <span id="showCvv">Show</span>
              </h3>
              <span id="cvvList">
                  <?php
                  while($users = mysqli_fetch_assoc($myconnect)){ ?>
              <p class="uk-margin-small-top user_line">
                <?php echo $users['username']; ?> 
                <a class="dap" style="color:#804100;" href="<?php echo "./active?disapprove_user=" . $users['id'] . "&disapprove=true"; ?>">Disapprove</a></p>

            
            <?php
            }
                    ?>
                
                </span>
               
              </div>

          </div>
        </article>
      </div>
    </div>
  </div>
</div>


<?php include './include/footer.php'; ?>

<script src="../js/awesomplete.js"></script>
<script src="../js/custom.js"></script>


</body>

</html>