<?php
if (!defined('DB_USER')) {
	require "../../config.php";
}
try {
	$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USER, DB_PASSWORD);
}
catch(PDOException $pe) {
	die("Could not connect to the database " . DB_DATABASE . ": " . $pe->getMessage());
}
$date_time = new DateTime('now', new DateTimezone('Africa/Lagos'));
global $conn;
if (isset($_POST['payload'])) {
	$question = trim($_POST['payload']);
	function isTraining($question)
	{
		if (strpos($question, 'train:') !== false) {
			return true;
		}
		return false;
	}
	function getAnswer()
	{
		global $question;
		global $conn;
		$sql = 'SELECT * FROM chatbot WHERE question LIKE "' . $question . '"';
		$answer_data_query = $conn->query($sql);
		$answer_data_query->setFetchMode(PDO::FETCH_ASSOC);
		$answer_data_result = $answer_data_query->fetchAll();
		$answer_data_index = 0;
		if (count($answer_data_result) > 0) {
			$answer_data_index = rand(0, count($answer_data_result) - 1);
		}
		if ($answer_data_result[$answer_data_index]["answer"] == "") {
			return 'I don\'t get :/. Train me to understand small something sha,no vex please type "<code>train: your question? # The answer.</code> ;)"';
		}
		if (containsVariables($answer_data_result[$answer_data_index]['answer']) || containsFunctions($answer_data_result[$answer_data_index]['answer'])) {
			$answer = resolveAnswer($answer_data_result[$answer_data_index]['answer']);
			return $answer;
		}
		else {
			return $answer_data_result[$answer_data_index]['answer'];
		}
	}
	function resolveQuestionFromTraining($question)
	{
		$start = 7;
		$end = strlen($question) - strpos($question, " # ");
		$new_question = substr($question, $start, -$end);
		return $new_question;
	}
	function resolveAnswerFromTraining($question)
	{
		$start = strpos($question, " # ") + 3;
		$answer = substr($question, $start);
		return $answer;
	}
	if (isTraining($question)) {
		$answer = resolveAnswerFromTraining($question);
		$question = strtolower(resolveQuestionFromTraining($question));
		$question_data = array(
			':question' => $question,
			':answer' => $answer
		);
		$sql = 'SELECT * FROM chatbot WHERE question = "' . $question . '"';
		$question_data_query = $conn->query($sql);
		$question_data_query->setFetchMode(PDO::FETCH_ASSOC);
		$question_data_result = $question_data_query->fetch();
		$sql = 'INSERT INTO chatbot ( question, answer )
          VALUES ( :question, :answer );';
		$q = $conn->prepare($sql);
		$q->execute($question_data);
		echo "Now I understand. No wahala, now try me again";
		return;
	}
	function containsVariables($answer)
	{
		if (strpos($answer, "{{") !== false && strpos($answer, "}}") !== false) {
			return true;
		}
		return false;
	}
	function containsFunctions($answer)
	{
		if (strpos($answer, "((") !== false && strpos($answer, "))") !== false) {
			return true;
		}
		return false;
	}
	function resolveAnswer($answer)
	{
		if (strpos($answer, "((") == "" && strpos($answer, "((") !== 0) {
			return $answer;
		}
		else {
			$start = strpos($answer, "((") + 2;
			$end = strlen($answer) - strpos($answer, "))");
			$function_found = substr($answer, $start, -$end);
			$replacable_text = substr($answer, $start, -$end);
			$new_answer = str_replace($replacable_text, $function_found() , $answer);
			$new_answer = str_replace("((", "", $new_answer);
			$new_answer = str_replace("))", "", $new_answer);
			return resolveAnswer($new_answer);
		}
	}
	$answer = getAnswer();
	echo $answer;
	exit();
}
else {
?>

<html lang="en-us" style="height:100%;" dir="ltr">
  <head>
    <title>Composite Components - Basic</title>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../css/images/favicon.ico">
    <link rel="apple-touch-icon-precomposed" href="../css/images/touchicon.png">
    <meta name="apple-mobile-web-app-title" content="Oracle JET">
    <link rel="stylesheet" id="css" href="http://www.oracle.com/webfolder/technetwork/jet/css/libs/oj/v5.0.0/alta/oj-alta-min.css">
    <link rel="stylesheet" href="../css/demo.css">
    <script>
      // The "oj_whenReady" global variable enables a strategy that the busy context whenReady,
      // will implicitly add a busy state, until the application calls applicationBootstrapComplete
      // on the busy state context.
      window["oj_whenReady"] = true;
    </script>
    <script src="http://www.oracle.com/webfolder/technetwork/jet/js/libs/require/require.js"></script>
    <!-- RequireJS bootstrap file -->
    <script src="../js/main.js"></script>
    <script src="../js/demo.js"></script>
  </head>


<div class="profile">
						<h1>Dennis Otugo</h1>
						<p>Human Being &nbsp;&bull;&nbsp; Cyborg &nbsp;&bull;&nbsp; Never asked for this</p>

					</div>
  <div class="bot-body">
    <div class="messages-body">
      <div>
        <div class="message bot">
          <span class="content">Look alive</span>
        </div>
      </div>
	<div>
        <div class="message bot">
          <span class="content">What do you have in mind, Let's talk :) </span>
        </div>
      </div>
    </div>
    <div class="send-message-body">
      <input class="message-box" placeholder="Enter your words here..."/>
    </div>
  </div>
<body class="demo-disable-bg-image">
    <div id="sampleDemo" style="" class="demo-padding demo-container">
      <div id="componentDemoContent" style="width: 1px; min-width: 100%;">
        
        <div id="composite-container" class="oj-flex oj-sm-flex-items-initial">
          <!-- ko foreach: employees -->
            <card class="oj-flex-item" name="[[name]]" avatar="[[avatar]]" work-title="[[title]]" 
              work-number="[[work]]" email="[[email]]">
            </card>
          <!-- /ko -->
        </div>

        
      </div>
    </div>
  </body>
</html>

<style>
.profile {height: 100%;text-align: center;position: fixed;position: fixed;position: fixed;width: 50%;right: 0;background-color: #007bff}footer {display: none;padding: 0px !important}h1, h2, h3, h4, h5, h6 {color: white;text-align: center;bottom: 50%;left: 65%;position: fixed;font-family: Lato,'Helvetica Neue',Helvetica,Arial,sans-serif;font-weight: 700}p {position: fixed;bottom: 40%;left: 58%;line-height: 1.5;margin: 30px 0}.bot-body {max-width: 100% !important;position: fixed;margin: 32px auto;position: fixed;width: 100%;left: 0;bottom: 0px;height: 80%}.messages-body {overflow-y: scroll;height: 100%;background-color: #FFFFFF;color: #3A3A5E;padding: 10px;overflow: auto;width: 50%;padding-bottom: 50px;border-top-left-radius: 5px;border-top-right-radius: 5px}.messages-body > div {background-color: #FFFFFF;color: #3A3A5E;padding: 10px;overflow: auto;width: 100%;padding-bottom: 50px}.message {float: left;font-size: 16px;background-color: #007bff63;padding: 10px;display: inline-block;border-radius: 3px;position: relative;margin: 5px}.message: before {position: absolute;top: 0;content: '';width: 0;height: 0;border-style: solid}.message.bot: before {border-color: transparent #9cccff transparent transparent;border-width: 0 10px 10px 0;left: -9px}.color-change {border-radius: 5px;font-size: 20px;padding: 14px 80px;cursor: pointer;color: #fff;background-color: #00A6FF;font-size: 1.5rem;font-family: 'Roboto';font-weight: 100;border: 1px solid #fff;box-shadow: 2px 2px 5px #AFE9FF;transition-duration: 0.5s;-webkit-transition-duration: 0.5s;-moz-transition-duration: 0.5s}.color-change: hover {color: #006398;border: 1px solid #006398;box-shadow: 2px 2px 20px #AFE9FF}.message.you: before {border-width: 10px 10px 0 0;right: -9px;border-color: #edf3fd transparent transparent transparent}.message.you {float: right}.content {display: block;color: #000000}.send-message-body {border-right: solid black 3px;position: fixed;width: 50%;left: 0;bottom: 0px;box-sizing: border-box;box-shadow: 1px 1px 9px 0px rgba(1, 1, 1, 1)}.message-box {width: -webkit-fill-available;border: none;padding: 2px 4px;font-size: 18px}body {overflow: hidden;height: 100%;background: #FFFFFF !important}.container {max-width: 100% !important}.fixed-top {position: fixed !important;}
  #composite-container card .card-front-side {
    background-image: url('https://res.cloudinary.com/dekstar-incorporated/image/upload/v1523717927/overlay.png');
  }

</style>

<script>
	
	require(['ojs/ojcore', 'knockout', 'jquery', 'ojs/ojknockout', 'jet-composites/demo-card/loader'],
function(oj, ko, $) {
  function model() {      
    var self = this;
    self.employees = [
      {
        name: 'Dennis Otugo',
        avatar: 'https://res.cloudinary.com/dekstar-incorporated/image/upload/v1523701221/avatar.png',
        title: 'System Administrator',
        work: 08114948073,
        email: 'wesleyotugo@fedoraproject.org'
      }
    ];
  }

  $(function() {
      ko.applyBindings(new model(), document.getElementById('composite-container'));
  });

});

	
  window.onload = function () {
          $(document).keypress(function (e) {
                  if (e.which == 13) {
                          getResponse(getQuestion());
                  }
          });
  }

  function isUrl(string) {
          var expression =
                  /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi;
          var regex = new RegExp(expression);
          var t = string;
          if (t.match(regex)) {
                  return true;
          } else {
                  return false;
          }
  }

  function stripHTML(message) {
          var re = /<\S[^><]*>/g
          return message.replace(re, "");
  }

  function getResponse(question) {
          updateThread(question);
          showResponse(true);
          if (question.trim() === "") {
                  showResponse(':)');
                  return;
          }
          if (question.toLowerCase().includes("aboutbot")) {
                  var textToSay = question.toLowerCase().split("aboutbot")[1];
                  showResponse('version 1.1.0');
                  return;
          }
          $.ajax({
                  url: "profiles/dennisotugo.php",
                  method: "POST",
                  data: {
                          payload: question
                  },
                  success: function (res) {
                          if (res.trim() === "") {
                                  showResponse(
                                          `
         Train me , please type "train: question # answer # password"
          `
                                  );
                          } else {
                                  showResponse(res);
                          }
                  }
          });
  }

  function showResponse(response) {
          if (response === true) {
                  $('.messages-body').append(
                          `<div>
          <div class="message bot temp">
            <span class="content">...</span>
          </div>
        </div>`
                  );
                  return;
          }
          $('.temp').parent().remove();
          $('.messages-body').append(
                  `<div>
        <div class="message bot">
          <span class="content">${response}</span>
        </div>
      </div>`
          );
          $('.message-box').val("");
  }

  function getQuestion() {
          return $('.message-box').val();
  }

  function updateThread(message) {
          message = stripHTML(message);
          $('.messages-body').append(
                  `<div>
        <div class="message you">
          <span class="content">${message}</span>
        </div>
      </div>`
          );
  }
</script>
<?php } 
?>
