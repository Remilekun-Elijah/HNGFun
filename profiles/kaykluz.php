<?php
   if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(!isset($conn)) {
        include '../../config.php';
        $conn = new PDO("mysql:host=". DB_HOST. ";dbname=". DB_DATABASE , DB_USER, DB_PASSWORD);
    }
    if(isset($_POST['q']) && $_POST['q'] != '') {
      $q = trim($_POST['q']);
      if(strtolower($q) == 'aboutbot') {echo "Name: KluzBot. Version: 1.0.1"; exit();}
      $q = $_POST['q'];
      // HANDLE TRAINING
      $words = explode(':', $q);
      if((count($words) > 1) && ($words[0] == 'train')) {
        $QandA = explode('#', $words[1]);
        // Ensure '?' is always removed question
        $question = trim($QandA[0]);
        $question_length = strlen($question);
        $question = ($question[$question_length - 1] == '?') ? substr($question, 0, $question_length -1) : $question;
        $answer = trim($QandA[1]);
        $password = trim($QandA[2]);
        if(count($QandA) != 3 || $question == '' || $answer == '') {
          echo "Incorrect training format. Train me using <span style=\"color:pink;\">train: question # answer # password<span>"; exit();
        }
        if($password == 'password') {
         
          $sql = "select * from chatbot where question like '{$question}%'";
          $query = $conn->prepare($sql);
          $query->execute();
          $results = $query->fetchAll(PDO::FETCH_OBJ);
          
          if($query->rowCount()) {
            foreach ($results as $result) {
              if($answer == trim($result->answer)) {
                echo "I Know this already"; exit();
              }
            }
            $sql = "insert into chatbot(question, answer) values('{$question}', '{$answer}')";
            $query = $conn->prepare($sql);
            $query->execute();
            if($query->rowCount()) {
              echo "Thanks for retraining me"; exit();
            }
            else{
              echo "Error, something went wrong."; exit();
            }
          }
          // train bot if not retrieved
          $sql = "insert into chatbot(question, answer) values('{$question}', '{$answer}')";
          $query = $conn->prepare($sql);
          $query->execute();
          if($query->rowCount()) {
            echo "Thanks for training me. :)"; exit();
          }
        }
        echo "Incorrect password used."; exit();
      }
    
      $q_length = strlen($q);
      $q = ($q[$q_length - 1] == '?') ? substr($q, 0, $q_length - 1) : $q;
      $sql = "select * from chatbot where question like '$q%'";
      $query = $conn->prepare($sql);
      $query->execute();
      $results = $query->fetchAll(PDO::FETCH_OBJ);
      $rowCount = $query->rowCount();
      if($rowCount == 1) { // if one answer
        echo $results[0]->answer; exit();
      }
      else if($rowCount > 1) { 
        echo $results[rand(0, $rowCount - 1)]->answer; exit();
      }
      else {
        echo "I am not yet trained on that. Train me using <span style=\"color:black;\">train: question # answer # password<span>";
      }
      exit();
    }
	}
 
 <?php
    try {
        $sql = 'SELECT * FROM secret_word';
        $q = $conn->query($sql);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $data = $q->fetch();
    } catch (PDOException $e) {
        throw $e;
    }
    
$secret_word = '';
$sql = "SELECT secret_word from secret_word";
foreach ($conn->query($sql) as $row) {
    $secret_word = $row['secret_word'];
   
}
    ?>

<DOCTYPE html>
<html>
<head>
<style>
@import url(https://fonts.googleapis.com/css?family=Quicksand:300,400|Lato:400,300|Coda|Open+Sans);
/* 
* Art by @kaykluz 
* April 2018
*/
body {
  background: #f8f5f0;
  font-family: "Open sans", sans-serif;
}
a {
  text-decoration: none;
  color: #3498db;
}
.content-profile-page {
  margin: 1em auto;
  width: 44.23em;
}

.card {
  background: #fff;
  border-radius: 0.3rem;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
  border: .1em solid rgba(0, 0, 0, 0.2);
  margin-bottom: 1em; 
}

.profile-user-page .img-user-profile {
	margin: 0 auto;
  text-align: center; 
}
.profile-user-page .img-user-profile .profile-bgHome {
	border-bottom: .2em solid #f5f5f5;
  width: 44.23em;
  height: 16em;
  }
.profile-user-page .img-user-profile .avatar {
	margin: 0 auto;
  background: #fff;
  width: 7em;
  height: 7em;
  padding: 0.25em;
  border-radius: .4em;
  margin-top: -10em;
  box-shadow: 0 0 .1em rgba(0, 0, 0, 0.35);
}


html, body {
            height: 100%;
            background-image: url('https://cdn.pixabay.com/photo/2017/03/19/11/07/light-2156209_960_720.jpg');
            background-repeat:no-repeat;
            background-size:cover;
            margin: 0px;
        }

.profile-user-page button {
	position: absolute;
  font-size: 13px;
  font-weight: bold;
  cursor: pointer;
  width: 7em; 
  background: #3498db;
  border: 1px solid #2487c9;
  color: #fff;
  outline: none;
  border-radius: 0 .6em .6em 0;
  padding: .80em;
}

.profile-user-page button:hover {
  background: #51a7e0;
  transition: background .2s ease-in-out;
  border: 1px solid #2487c9;
}
.profile-user-page .user-profile-data, .profile-user-page .description-profile {
  text-align: center;
  padding: 0 1.5em; 
}
.profile-user-page .user-profile-data h1 {
  font-family: "Lato", sans-serif;
  margin-top: 0.35em;
  color: #292f33;
  margin-bottom: 0; 
}
.profile-user-page .user-profile-data p {
	font-family: "Lato", sans-serif;
  color: #8899a6; 
  font-size: 1.1em;
  margin-top: 0;
  margin-bottom: 0.5em; 
}
.profile-user-page .description-profile {
  color: #75787b;
  font-size: 0.98em; 
}
.profile-user-page .data-user {
	font-family: "Quicksand", sans-serif;
  margin-bottom: 0;
  cursor: pointer;
  padding: 0;
  list-style: none;
  display: table;
  width: 100.15%; 
}
.profile-user-page .data-user li {
  margin: 0;
  padding: 0;
  width: 33.33334%;
  display: table-cell;
  text-align: center;
  border-left: 0.1em solid transparent; 
}
.profile-user-page .data-user li:first-child {
  border-left: 0; 
}
.profile-user-page .data-user li:first-child a {
  border-bottom-left-radius: 0.3rem; 
  }
.profile-user-page .data-user li:last-child a {
  border-bottom-right-radius: 0.3rem; 
}
.profile-user-page .data-user li a, .profile-user-page .data-user li strong {
  display: block; 
}
.profile-user-page .data-user li a {
  background-color: #f7f7f7;
  border-top: 1px solid rgba(242,242,242,0.5);
  border-bottom: .2em solid #f7f7f7;
  box-shadow: inset 0 1px 0 rgba(255,255,255,0.4),0 1px 1px rgba(255,255,255,0.4);
  padding: .93em 0;
  color: #46494c; 
}
.profile-user-page .data-user li a strong, .profile-user-page .data-user li a span {
  font-weight: 600;
  line-height: 1; 
}
.profile-user-page .data-user li a strong {
  font-size: 2em; 
}
.profile-user-page .data-user li a span {
  color: #717a7e; 
}
.profile-user-page .data-user li a:hover {
  background: rgba(0, 0, 0, 0.05);
  border-bottom: .2em solid #3498db;
  color: #3498db; 
}
.profile-user-page .data-user li a:hover span {
  color: #3498db; 
}

footer h4 {
  display: block;
  text-align: center;
  clear: both;
  font-family: "Coda", sans-serif;
  color: #566965;
  line-height: 6;
  font-size: 1em;
}
footer h4 a {
  text-decoration: none;
  color: #3498db;
}


/*--------------------
Mixins
--------------------*/
/*--------------------
Body
--------------------*/
*,
*::before,
*::after {
  box-sizing: border-box;
}

html,
body {
  height: 100%;
}

body {
  background: linear-gradient(135deg, #044f48, #345093);
  background-size: cover;
  background: #fff;
  font-family: 'Avenir', 'Open Sans', sans-serif;
  font-size: 14px;
  line-height: 1.3;
  overflow: hidden;
}

.bg {
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  z-index: 1;
  /* background: url('http://res.cloudinary.com/kaykluz/image/upload/v1524408376/avatar_solo.jpg') no-repeat 0 0;*/
  -webkit-filter: blur(80px);
          filter: blur(80px);
  -webkit-transform: scale(1.2);
          transform: scale(1.2);
  background: #fff;
}

/*--------------------
Chat
--------------------*/
.chat {
  position: relative;
  /*  top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);*/
  width: 100%;
  height: calc(100% - 15px);
  max-height: 500px;
  z-index: 10;
  overflow: hidden;
  /*box-shadow: 0 5px 30px rgba(0, 0, 0, .2);*/
  /* background: rgba(0, 0, 0, .5);*/
  background: transparent;
  border-radius: 20px;
  -webkit-border-radius: 20px;
  -moz-border-radius: 20px;
  display: flex;
  justify-content: space-between;
  flex-direction: column;
}

/*--------------------
Chat Title
--------------------*/
.chat-title {
  flex: 0 1 45px;
  position: relative;
  z-index: 2;
  width: 100%;
  border-bottom: 1px solid #ccc;
  color: #777;
  padding-top: 50px;
  padding-bottom: 5px;
  background-color: #fff;
  text-transform: uppercase;
  text-align: center;
}
.chat-title h1,
.chat-title h2 {
  font-weight: normal;
  font-size: 14px;
  margin: 0;
  padding: 0;
}
.chat-title h2 {
  /* color: rgba(255, 255, 255, .8);*/
  font-size: 11px;
  letter-spacing: 1px;
}
.chat-title .avatar {
  position: absolute;
  z-index: 1;
  top: 8px;
  left: 9px;
  border-radius: 30px;
  width: 60px;
  height: 60px;
  overflow: hidden;
  margin: 0;
  padding: 0;
  border: 1px solid #fff;
}
.chat-title .avatar img {
  width: 100%;
  height: auto;
}

/*--------------------
Messages
--------------------*/
.messages {
  flex: 1 1 auto;
  /*  color: rgba(255, 255, 255, .5);
  color: #fff;*/
  overflow: hidden;
  position: relative;
  width: 100%;
}
.messages .messages-content {
  position: absolute;
  top: 0;
  left: 0;
  height: 101%;
  width: 100%;
}
.messages .message {
  clear: both;
  float: left;
  padding: 6px 10px 7px;
  border-radius: 20px 20px 20px 0;
  background: #eee;
  /*rgba(0, 0, 0, 0.1);*/
  margin: 8px 0;
  font-size: 14px;
  line-height: 1.4;
  margin-left: 35px;
  position: relative;
  border: 1px solid #ccc;
  /*text-shadow: 0 1px 1px rgba(0, 0, 0, .2);*/
}
.messages .message .timestamp {
  position: absolute;
  bottom: -15px;
  font-size: 10px;
  color: #555;
  right: 30px;
  /* color: rgba(255, 255, 255, .7);*/
}
.messages .message .checkmark-sent-delivered {
  position: absolute;
  bottom: -15px;
  right: 10px;
  font-size: 12px;
  color: #999;
}
.messages .message .checkmark-read {
  color: blue;
  position: absolute;
  bottom: -15px;
  right: 16px;
  font-size: 12px;
}
.messages .message::before {
  /*  content: '';
    position: absolute;
    bottom: -6px;
    border-top: 6px solid rgba(0, 0, 0, 0.1);
    left: 0;
    border-right: 7px solid transparent;*/
}
.messages .message .avatar {
  position: absolute;
  z-index: 1;
  bottom: -15px;
  left: -35px;
  border-radius: 30px;
  width: 30px;
  height: 30px;
  overflow: hidden;
  margin: 0;
  padding: 0;
  border: 2px solid rgba(255, 255, 255, 0.5);
}
.messages .message .avatar img {
  width: 100%;
  height: auto;
}
.messages .message.message-personal {
  float: right;
  text-align: right;
  /*      background: linear-gradient(120deg, #ddd, #eee);*/
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 20px 20px 0 20px;
}
.messages .message.message-personal::before {
  /*
      content:"";
  border-color:#4A90E2 transparent;
    width:0;
    border-style:solid;*/
 /*
 left: auto;
 right: 0;
 border-right: none;
border-left: 8px solid transparent;
 border-top: 9px solid #fff;
 
 bottom: -8px;*/
}
.messages .message:last-child {
  margin-bottom: 30px;
}
.messages .message.new {
  -webkit-transform: scale(0);
          transform: scale(0);
  -webkit-transform-origin: 0 0;
          transform-origin: 0 0;
  -webkit-animation: bounce 500ms linear both;
          animation: bounce 500ms linear both;
}
.messages .message.loading::before {
  position: relative;
  /*  top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);*/
  content: '';
  display: block;
  width: 5px;
  height: 5px;
  border-radius: 50%;
  /*  background: rgba(255, 255, 255, .5);*/
  background: #888;
  z-index: 2;
  margin-top: 4px;
  -webkit-animation: ball 0.45s cubic-bezier(0, 0, 0.15, 1) alternate infinite;
          animation: ball 0.45s cubic-bezier(0, 0, 0.15, 1) alternate infinite;
  border: none;
  -webkit-animation-delay: .15s;
          animation-delay: .15s;
}
.messages .message.loading span {
  display: block;
  font-size: 0;
  width: 20px;
  height: 10px;
  position: relative;
}
.messages .message.loading span::before {
  position: relative;
  /*  top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);*/
  content: '';
  display: block;
  width: 5px;
  height: 5px;
  border-radius: 50%;
  /*  background: rgba(255, 255, 255, .5);*/
  background: #888;
  z-index: 2;
  margin-top: 4px;
  -webkit-animation: ball 0.45s cubic-bezier(0, 0, 0.15, 1) alternate infinite;
          animation: ball 0.45s cubic-bezier(0, 0, 0.15, 1) alternate infinite;
  margin-left: -7px;
}
.messages .message.loading span::after {
  position: relative;
  /*  top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);*/
  content: '';
  display: block;
  width: 5px;
  height: 5px;
  border-radius: 50%;
  /*  background: rgba(255, 255, 255, .5);*/
  background: #888;
  z-index: 2;
  margin-top: 4px;
  -webkit-animation: ball 0.45s cubic-bezier(0, 0, 0.15, 1) alternate infinite;
          animation: ball 0.45s cubic-bezier(0, 0, 0.15, 1) alternate infinite;
  margin-left: 7px;
  -webkit-animation-delay: .3s;
          animation-delay: .3s;
}

/*--------------------
Message Box
--------------------*/
.message-box {
  flex: 0 1 42px;
  width: 90%;
  background: #fff;
  margin: 2px auto;
  /*-webkit-box-shadow: 0px 1px 1px 1px rgba(0,0,0,0.4);
  -moz-box-shadow: 0px 1px 1px 1px rgba(0,0,0,0.4);
  box-shadow: 0px 1px 1px 1px rgba(0,0,0,0.4);*/
  /*background: rgba(0, 0, 0, 0.3);
    border-top:1px solid #e3e3e3;*/
  padding: 10px;
  position: relative;
  border-radius: 20px;
  height: 14px;
  border: 1px solid #ccc;
}
.message-box .message-input {
  background: none;
  border: none;
  outline: none !important;
  resize: none;
  /* color: rgba(255, 255, 255, .8);*/
  font-size: 15px;
  height: 24px;
  margin: 0;
  padding-right: 20px;
  width: 265px;
  color: #444;
}
.message-box textarea:focus:-webkit-placeholder {
  color: transparent;
}
.message-box .message-submit {
  position: absolute;
  z-index: 1;
  top: 9px;
  right: 10px;
  color: #4A90E2;
  border: none;
  /* background: #c29d5f;*/
  background: #fff;
  font-size: 12px;
  text-transform: uppercase;
  line-height: 1;
  padding: 6px 10px;
  border-radius: 5px;
  outline: none !important;
  transition: background .2s ease;
  cursor: pointer;
}
.message-box .message-submit:hover {
  background: #fff;
  color: #333;
}

/*--------------------
Custom Srollbar
--------------------*/
.mCSB_scrollTools {
  margin: 1px -3px 1px 0;
  opacity: 0;
}

.mCSB_inside > .mCSB_container {
  margin-right: 0px;
  padding: 0 10px;
}

.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar {
  background-color: rgba(0, 0, 0, 0.5) !important;
}

/*--------------------
Bounce
--------------------*/
@-webkit-keyframes bounce {
  0% {
    -webkit-transform: matrix3d(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  4.7% {
    -webkit-transform: matrix3d(0.45, 0, 0, 0, 0, 0.45, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(0.45, 0, 0, 0, 0, 0.45, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  9.41% {
    -webkit-transform: matrix3d(0.883, 0, 0, 0, 0, 0.883, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(0.883, 0, 0, 0, 0, 0.883, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  14.11% {
    -webkit-transform: matrix3d(1.141, 0, 0, 0, 0, 1.141, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(1.141, 0, 0, 0, 0, 1.141, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  18.72% {
    -webkit-transform: matrix3d(1.212, 0, 0, 0, 0, 1.212, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(1.212, 0, 0, 0, 0, 1.212, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  24.32% {
    -webkit-transform: matrix3d(1.151, 0, 0, 0, 0, 1.151, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(1.151, 0, 0, 0, 0, 1.151, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  29.93% {
    -webkit-transform: matrix3d(1.048, 0, 0, 0, 0, 1.048, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(1.048, 0, 0, 0, 0, 1.048, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  35.54% {
    -webkit-transform: matrix3d(0.979, 0, 0, 0, 0, 0.979, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(0.979, 0, 0, 0, 0, 0.979, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  41.04% {
    -webkit-transform: matrix3d(0.961, 0, 0, 0, 0, 0.961, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(0.961, 0, 0, 0, 0, 0.961, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  52.15% {
    -webkit-transform: matrix3d(0.991, 0, 0, 0, 0, 0.991, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(0.991, 0, 0, 0, 0, 0.991, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  63.26% {
    -webkit-transform: matrix3d(1.007, 0, 0, 0, 0, 1.007, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(1.007, 0, 0, 0, 0, 1.007, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  85.49% {
    -webkit-transform: matrix3d(0.999, 0, 0, 0, 0, 0.999, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(0.999, 0, 0, 0, 0, 0.999, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  100% {
    -webkit-transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
}
@keyframes bounce {
  0% {
    -webkit-transform: matrix3d(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  4.7% {
    -webkit-transform: matrix3d(0.45, 0, 0, 0, 0, 0.45, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(0.45, 0, 0, 0, 0, 0.45, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  9.41% {
    -webkit-transform: matrix3d(0.883, 0, 0, 0, 0, 0.883, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(0.883, 0, 0, 0, 0, 0.883, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  14.11% {
    -webkit-transform: matrix3d(1.141, 0, 0, 0, 0, 1.141, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(1.141, 0, 0, 0, 0, 1.141, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  18.72% {
    -webkit-transform: matrix3d(1.212, 0, 0, 0, 0, 1.212, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(1.212, 0, 0, 0, 0, 1.212, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  24.32% {
    -webkit-transform: matrix3d(1.151, 0, 0, 0, 0, 1.151, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(1.151, 0, 0, 0, 0, 1.151, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  29.93% {
    -webkit-transform: matrix3d(1.048, 0, 0, 0, 0, 1.048, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(1.048, 0, 0, 0, 0, 1.048, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  35.54% {
    -webkit-transform: matrix3d(0.979, 0, 0, 0, 0, 0.979, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(0.979, 0, 0, 0, 0, 0.979, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  41.04% {
    -webkit-transform: matrix3d(0.961, 0, 0, 0, 0, 0.961, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(0.961, 0, 0, 0, 0, 0.961, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  52.15% {
    -webkit-transform: matrix3d(0.991, 0, 0, 0, 0, 0.991, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(0.991, 0, 0, 0, 0, 0.991, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  63.26% {
    -webkit-transform: matrix3d(1.007, 0, 0, 0, 0, 1.007, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(1.007, 0, 0, 0, 0, 1.007, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  85.49% {
    -webkit-transform: matrix3d(0.999, 0, 0, 0, 0, 0.999, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(0.999, 0, 0, 0, 0, 0.999, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
  100% {
    -webkit-transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
            transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
  }
}
@-webkit-keyframes ball {
  from {
    -webkit-transform: translateY(0) scaleY(0.8);
            transform: translateY(0) scaleY(0.8);
  }
  to {
    -webkit-transform: translateY(-10px);
            transform: translateY(-10px);
  }
}
@keyframes ball {
  from {
    -webkit-transform: translateY(0) scaleY(0.8);
            transform: translateY(0) scaleY(0.8);
  }
  to {
    -webkit-transform: translateY(-10px);
            transform: translateY(-10px);
  }
}
.avenue-messenger {
  opacity: 1;
  border-radius: 20px;
  height: calc(100% - 60px) !important;
  max-height: 460px !important;
  min-height: 220px !important;
  width: 320px;
  /*  transform: translateY(0);
    -webkit-transform: translateY(0);
    -moz-transform: translateY(0);
  */
  background: rgba(255, 255, 255, 0.9);
  position: fixed;
  right: 20px;
  bottom: 20px;
  margin: auto;
  z-index: 10;
  box-shadow: 2px 10px 40px rgba(22, 20, 19, 0.4);
  /*  transform: translateX(300px);*/
  -webkit-transition: 0.3s all ease-out 0.1s, transform 0.2s ease-in;
  -moz-transition: 0.3s all ease-out 0.1s, transform 0.2s ease-in;
}

.avenue-messenger div.agent-face {
  position: absolute;
  left: 0;
  top: -50px;
  right: 0;
  margin: auto;
  width: 101px;
  height: 50px;
  background: transparent;
  z-index: 12;
}

.avenue-messenger div {
  font-size: 14px;
  color: #232323;
}

.close {
  display: block;
  width: 100px;
  height: 100px;
  margin: 1em auto;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
  border-radius: 99em;
  opacity: 0.5;
  /*border: 1px solid #ccc;
  color:#ccc;*/
  /* -webkit-box-shadow: 0px -1px 2px 0px rgba(0, 0, 0, 0.5);
  -moz-box-shadow:    0px -1px 2px 0px rgba(0, 0, 0, 0.5);
  box-shadow:         0px -1px 2px 0px rgba(0, 0, 0, 0.5);*/
}

.close:hover {
              /*
-webkit-box-shadow:  0 1px 1px rgba(0,0,0,0.3);
-moz-box-shadow:  0 1px 1px rgba(0,0,0,0.3);
box-shadow: 0 1px 1px rgba(0,0,0,0.3);*/
  opacity: 0.9;
}

.circle {
  display: block;
  width: 80px;
  height: 80px;
  margin: 1em auto;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
  border-radius: 99em;
  border: 2px solid #fff;
  /*#4A90E2;*/
 /* -webkit-box-shadow: 0px 0px 10px rgba(0,0,0,.8);
  -moz-box-shadow: 0px 0px 10px rgba(0,0,0,.8);
box-shadow: 0px 0px 10px rgba(0,0,0,.8);*/
}

.contact-icon .circle:hover {
  box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
  -webkit-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
  -moz-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
  transition: 0.2s all ease-out 0.2s;
  -webkit-transition: 0.2s all ease-out 0.2s;
  -moz-transition: 0.2s all ease-out 0.2s;
}

.arrow_box:after {
  border-color: rgba(255, 255, 255, 0);
  border-left-color: #fff;
  border-width: 5px;
  margin-top: -5px;
}

.arrow_box {
  position: relative;
  background: #fff;
  border: 1px solid #4A90E2;
}

.arrow_box:after, .arrow_box:before {
  left: 100%;
  top: 50%;
  border: solid transparent;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
}

.menu div.items {
  /*  height: 140px;
    width: 180px;
    overflow: hidden;
    position: absolute;
    left: -130px;
    z-index: 2;
    top: 20px;*/
}

.menu .items span {
  color: #111;
  z-index: 12;
  font-size: 14px;
  text-align: center;
  position: absolute;
  right: 0;
  top: 40px;
  height: 86px;
  line-height: 40px;
  background: #fff;
  border-left: 1px solid #ccc;
  border-bottom: 1px solid #ccc;
  border-right: 1px solid #ccc;
  width: 48px;
  opacity: 0;
  border-bottom-left-radius: 20px;
  border-bottom-right-radius: 20px;
  transition: .3s all ease-in-out;
  -webkit-transition: .3s all ease-in-out;
  -moz-transition: .3s all ease-in-out;
}

.menu .button {
  font-size: 30px;
  z-index: 12;
  text-align: right;
  color: #333;
  content: "...";
  display: block;
  width: 48px;
  line-height: 25px;
  height: 40px;
  border-top-right-radius: 20px;
  /*  border-top-left-radius:20px;*/
  position: absolute;
  right: 0;
  padding-right: 10px;
  cursor: pointer;
  transition: .3s all ease-in-out;
  -webkit-transition: .3s all ease-in-out;
  -moz-transition: .3s all ease-in-out;
}

.menu .button.active {
  background: #ccc;
}

/*
.menu .button:hover .menu .items span {
  display: block;
  left: -2px;
  opacity: 1;
}*/
.menu .items span.active {
  opacity: 1;
  /*border-radius:10px;
  height: 180px;
  width: 400px;
  transform:translateY(0);
  -webkit-transform:translateY(0);
  -moz-transform:translateY(0);*/
}

.menu .items a {
  color: #111;
  text-decoration: none;
}

.menu .items a:hover {
  color: #777;
}

@media only screen and (max-device-width: 667px), screen and (max-width: 450px) {
  .avenue-messenger {
    z-index: 2147483001 !important;
    width: 100% !important;
    height: 100% !important;
    max-height: none !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    border-radius: 0 !important;
    background: #fff;
  }

  .avenue-messenger div.agent-face {
    top: -10px !important;
    /* left:initial !important;*/
  }

  .chat {
    border-radius: 0 !important;
    max-height: initial !important;
  }

  .chat-title {
    padding: 20px 20px 15px 10px !important;
    text-align: left;
  }

  .circle {
    width: 80px;
    height: 80px;
    border: 1px solid #fff;
  }

  .menu .button {
    border-top-right-radius: 0;
  }
}
@media only screen and (min-device-width: 667px) {
  div.half {
    margin: auto;
    width: 80px;
    height: 40px;
    background-color: #fff;
    border-top-left-radius: 60px;
    border-top-right-radius: 60px;
    border-bottom: 0;
    box-shadow: 1px 4px 20px rgba(22, 20, 19, 0.6);
    -webkit-box-shadow: 1px 4px 20px rgba(22, 20, 19, 0.6);
    -moz-box-shadow: 1px 4px 20px rgba(22, 20, 19, 0.6);
  }
}

</style>

<div class="content-profile-page">

<?php
    try {
        $sql2 = 'SELECT * FROM interns_data WHERE username="kaykluz"';
        $q2 = $conn->query($sql2);
        $q2->setFetchMode(PDO::FETCH_ASSOC);
        $my_data = $q2->fetch();
    } catch (PDOException $e) {
        throw $e;
    }
    ?>

   <div class="profile-user-page card">
      <div class="img-user-profile">
        <img class="profile-bgHome" src="http://res.cloudinary.com/kaykluz/image/upload/v1523627149/13012866_1109913765737811_2486734311465436607_n.jpg" />
        <img class="avatar" src="http://res.cloudinary.com/kaykluz/image/upload/v1523627210/AAEAAQAAAAAAAAlhAAAAJDlmZDJlYWZhLWYwZTctNDNhNS04ZmJjLTg2YzRiNTc0ZjY1Nw.jpg"/>
           </div>
          <button>Follow</button>
          <div class="user-profile-data">
            <h1><?=$my_data['name'] ?></h1>
            <p>github.com/ojoawo</p>
          </div> 
          <div class="description-profile">Hotels.Ng Intern | Front-end | CSS Demon | <a href="https://twitter.com/kaykluz" title="Kaykluz"><strong>@kaykluz</strong></a> | Hungry and Talented!</div>
       <ul class="data-user">
        <li><a href="https://facebook.com/ojoawosolomon" title="ojoawosolomon"><strong>@ojoawosolomon</strong><span>Facebook</span></a></li>
        <li><a href="https://instagram.com/kaykluz" title="kaykluz"><strong>@kaykluz</strong><span>Instagram</span></a></li>
        <li><a href="https://linkedin.com/in/ojoawosolomon" title="SolomonOjoawo"><strong>Solomon Ojoawo</strong><span>Linkedin</span></a></li>
       </ul>
      </div>

    </div>
<section class="avenue-messenger">
  <div class="menu">
   <div class="items"><span>
     <a href="#" title="Minimize">&mdash;</a><br>
<!--     
     <a href="">enter email</a><br>
     <a href="">email transcript</a><br>-->
     <a href="#" title="End Chat">&#10005;</a>
     
     </span></div>
    <div class="button">...</div>
  </div>
  <div class="agent-face">
    <div class="half">
     <img class="agent circle" src="http://res.cloudinary.com/kaykluz/image/upload/v1524408376/avatar_solo.jpg"></div>
  </div>
<div class="chat">
  <div class="chat-title">
    <h1>Solomon Kaykluz Ojoawo</h1>
    <h2>KluzBot</h2>
  <!--  <figure class="avatar">
      <img src="http://res.cloudinary.com/kaykluz/image/upload/v1524408376/avatar_solo.jpg" /></figure>-->
  </div>
  <div class="messages">
    <div class="messages-content"></div>
  </div>
  <div class="message-box">
    <textarea type="text" class="message-input" placeholder="Type message..."></textarea>
    <button type="submit" class="message-submit">Send</button>
  </div>
</div>
  </div>
<footer>
   <h4>Design by <a href="https://twitter.com/kaykluz" target="_blank" title="Solomon Ojoawo">@kaykluz</a></h4>
</footer>

</head>
</html>
<!--<div class="bg"></div>-->
