<?php
// Config - Setup this first
$txt_file = '../twtxt.txt'; // File route
$pass = ""; // Put your password's hash here

if (isset($_POST["sub"])) {
  if (password_verify($_POST["pass"], $pass)) {
    $new_post = filter_input(INPUT_POST, 'new_post');

    function id() {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $randstring = '';
      $i = 0;

      while ($i <= 6) {
        $randstring .= $characters[rand(0, strlen($characters))];
        $i++;
      }

      return $randstring;
    }

    $id = id();

    if ($new_post) {
      $contents = file_get_contents($txt_file);
      $contents .= "\n" . date("Y-m-d\TH:i:s\Z") . "\t(#" . $id . ")\t" ;
      $contents .= "$new_post";

      file_put_contents($txt_file, $contents);
      header("Refresh:0; url=?");
      exit;
    } else {
      echo "Opps something went wrong...\n\nCheck the error_log on the server";
      exit;
    }
  } else{ header("location: ?retry"); }
} else { ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>pdpush</title>
  <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
  <style type="text/css">
    body{
      margin:1em;
      background-color:#333;
      color:#FFE;
      font-family:-apple-system, "Segoe UI", Roboto, Helvetica, sans-serif;
      font-size:1em;
      font-weight:400;
      line-height:1.6;
      max-width:800px;
      margin:0 auto;
    }
    a{color:#9F9;text-decoration:none;}
    a:hover{color:#9F9;text-decoration:underline;}
    form{align-content:center;}
    form input,#retry{
      font-size:1.2em;
      border-radius:2px;
      padding:10px;
    }
    form input[type=text],form input[type=password]{
      flex-grow:1;
      background:#222;
      color:#ffe;
      border:solid 1px #9F9;
      margin-right:5px;
    }
    form input[type=password]{font-size:90%;}
    form input[type=submit]{
      margin-left:5px;
      background:#9F9;
      color:#222;
      border:none;
    }
    #posting{display:flex;}
    #retry{margin:0 0 20px 0;border:solid 1px #f45;}
    iframe{
      border:none;
      margin-top:1em;
      background-color:#ffe;
      width:100%;
    }
    footer{font-size:0.9em;text-align:right;}
  </style>
</head>
<body>
  <h1>phppub2twtxt</h1>
  <p>An interface for publishing quickly to your twtxt.txt file</p>
  <?php if(isset($_GET["retry"])){echo '<div id="retry">Your author key isn\'t valid, please try again</div>';} ?>
  <form method="POST" class="column">
    <div id="posting">
      <input type="text" name="new_post" autofocus placeholder="Write you twtxt post here">
      <input type="submit" value="Post" name="sub">
    </div>
    <input type="password" name="pass" autofocus placeholder="Your dynamic password">
  </form>
  <iframe src="<?= $txt_file ?>" height="450"></iframe>
  <footer><a href="https://github.com/luqaska/pdpush">source code</a></footer>
</body>
</html>
<?php } ?>
