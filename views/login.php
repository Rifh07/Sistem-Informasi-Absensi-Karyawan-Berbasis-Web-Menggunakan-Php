<?php
session_start();
if (isset($_SESSION['nip'])){
   header('location: ./');
} 

$title->setTitle('Login');

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $login = $users->login($_POST['nip'], $_POST['password']);
    if ($login){
        header("location: /");
    } else {
        $msg= 'Username / password wrong';
    }
}


?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Website ini adalah website absensi resmi Getsmart">
  <meta name="author" content="Syarif Hidayat">
  <title><?php echo $title->getTitle();?></title>
  <link href="<?php echo BaseUrl?>/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="<?php echo BaseUrl?>/assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-4 col-md-9 my-5">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-md-12">
                <div class="p-3">
                    <div class="p-3">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Selamat Datang!</h1>
                  </div>
                  <?php if (isset($msg)):
                            echo '<div class="alert alert-danger">
                                    <b>'.$msg.'</b>
                                </div>';
                        endif;
                    ?>
                  <form class="user" action="" method="POST">
                    <div class="form-group">
                      <input type="text" name="nip" class="form-control form-control-user" placeholder="Masukan NIP" require>
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" class="form-control form-control-user" placeholder="Masukan Password">
                    </div>
                    <input class="btn btn-primary btn-user btn-block" name="login" type="submit" value="Masuk">
                  </form>
                  <br>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo BaseUrl?>/assets/vendor/jquery/jquery.min.js"></script>
  <script src="<?php echo BaseUrl?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo BaseUrl?>/assets/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="<?php echo BaseUrl?>/assets/js/sb-admin-2.min.js"></script>
</body>
</html>
