<?php
require_once __DIR__ . '/lineBot.php';

// connect db
$servername = 'localhost';
$password = '';
$username = 'root';
$dbname = 'aero_db';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


$bot = new Linebot();
$text = $bot->getMessageText();
$postback = $bot->postbackEvent();
if($postback != ""){
	if(substr($postback,0,5) =='news-'){
		$id_news = substr($postback,5,(strlen($postback)-5));
		
		$sql = "SELECT * FROM newses WHERE is_publish = 1 AND id=".$id_news."";
		$result = $conn->query($sql);
	

		$balas = "NEWS\n";
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$content = strip_tags($row["content"]);
				$balas = $balas . "\n\n" . $row["title"]. " \n\n created at " . $row["created_at"]. " \n\n " . $content. "";
			}
		} else {
			$balas = $balas . "\n 0 results";
		}
		$bot->reply($balas);
	}elseif(substr($postback,0,6) =='forum-'){
		$id_forum = substr($postback,6,(strlen($postback)-6));
		
		$sql = "SELECT * FROM forums WHERE  id=".$id_forum."";
		$result = $conn->query($sql);
	

		$balas = "FORUM\n";
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$balas = $balas . "\n\n" . $row["title"]. " \n\n created at " . $row["created_at"]. " \n\n " . $row["content"]. "";
			}
		} else {
			$balas = $balas . "\n 0 results";
		}
		$bot->reply($balas);
	}
}
if($text != ""){
	if(strtolower($text) == 'news'){
		$datetime = new DateTime();
		$date = $datetime->getTimestamp();
		$sql = "SELECT * FROM newses WHERE is_publish = 1 limit 5";
		$result = $conn->query($sql);
	
		$items = array();
		$newarray = array();
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				//$balas = $balas . "\n\n" . $row["id"]. " -- " . $row["title"]. " -- " . $row["updated_at"]. "";
				$title = substr($row["title"],0,40);
				$postback = "news-".$row["id"];
				$content = substr(strip_tags($row["content"]),0,60);
				$newarray[] = array(
								"thumbnailImageUrl"=> "https://7b390aa1.ngrok.io/line/aerofood.jpg",
								"imageBackgroundColor"=> "#FFFFFF",
								"title"=> $title,
								"text"=> $content,
								"defaultAction"=> array(
									"type"=> "uri",
									"label"=> "View detail",
									"uri"=> "http://103.75.103.81:8084/news-board"
								),
								"actions"=> [
									array(
										"type"=> "postback",
										"label"=> "Read at Chat",
										"data"=> $postback
									),
									array(
										"type"=> "uri",
										"label"=> "Read at Web",
										"uri"=> "http://103.75.103.81:8084/news-board"
									)
								]
							);

			}
		} else {
			$balas = $balas . "\n 0 results";
		}
		$bot->reply_carousel($newarray);

	}elseif(strtolower($text) == 'forums'){
		$datetime = new DateTime();
		$date = $datetime->getTimestamp();
		$sql = "SELECT * FROM forums limit 5";
		$result = $conn->query($sql);
	
		$newarray= array();
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				//$balas = $balas . "\n\n" . $row["id"]. " -- " . $row["title"]. " -- " . $row["created_at"]. "";
				$title = substr($row["title"],0,40);
				$postback = "forum-".$row["id"];
				$content = substr(strip_tags($row["content"]),0,60);
				$newarray[] = array(
								"thumbnailImageUrl"=> "https://6edf809e.ngrok.io/line/aerofood.jpg",
								"imageBackgroundColor"=> "#FFFFFF",
								"title"=> $title,
								"text"=> $content,
								"defaultAction"=> array(
									"type"=> "uri",
									"label"=> "View detail",
									"uri"=> "http://103.75.103.81:8084/news-board"
								),
								"actions"=> [
									array(
										"type"=> "postback",
										"label"=> "Read at Chat",
										"data"=> $postback
									),
									array(
										"type"=> "uri",
										"label"=> "Read at Web",
										"uri"=> "http://103.75.103.81:8084/news-board"
									)
								]
							);

			}
		} else {
			$balas = $balas . "\n 0 results";
		}
		$bot->reply_carousel($newarray);
	}elseif($text == 'schedule' or $text =='Schedule'){
		$datetime = new DateTime();
		$date = $datetime->getTimestamp();
		$sql = "SELECT * FROM modul_trainings WHERE is_publish = 1 AND is_child = 1 AND date > DATE_SUB(NOW(),INTERVAL 1 MINUTE) limit 5";
		$result = $conn->query($sql);
	
		$newarray=array();
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$modul = substr($row["modul_name"],0,40);
				$th_date = new DateTime($row["date"]);
				$th_date->setTime($row["time"]);
				$waktu = "".($th_date)->format('Y-m-d') ." at ".$row["time"];
				$newarray[] = array(
								"thumbnailImageUrl"=> "https://6edf809e.ngrok.io/line/aerofood.jpg",
								"imageBackgroundColor"=> "#FFFFFF",
								"title"=> $modul,
								"text"=> $waktu,
								"defaultAction"=> array(
									"type"=> "uri",
									"label"=> "View detail",
									"uri"=> "http://103.75.103.81:8084"
								),
								"actions"=> [
									array(
										"type"=> "uri",
										"label"=> "Read at Web",
										"uri"=> "http://103.75.103.81:8084"
									)
								]
							);

			}
		} else {
			$balas = $balas . "\n 0 results";
		}
		$bot->reply_carousel($newarray);
	}elseif($text == 'trainings' or $text =='Trainings'){
		$datetime = new DateTime();
		$date = $datetime->getTimestamp();
		$sql = "SELECT * FROM modul_trainings WHERE is_publish = 1 AND is_child = 1 limit 10";
		$result = $conn->query($sql);
		$newarray=array();

		// $balas = "LIST OF TRAINING\n";
		// $balas = $balas . "\n Date -- Time -- Training Name";
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				//$balas = $balas . "\n\n" . $row["date"]. " -- " . $row["time"]. " -- " . $row["modul_name"]. "";
				$modul = substr($row["modul_name"],0,40);
				$th_date = new DateTime($row["date"]);
				$th_date->setTime($row["time"]);
				$waktu = "".($th_date)->format('Y-m-d') ." at ".$row["time"];
				$newarray[] = array(
								"thumbnailImageUrl"=> "https://6edf809e.ngrok.io/line/aerofood.jpg",
								"imageBackgroundColor"=> "#FFFFFF",
								"title"=> $modul,
								"text"=> $waktu,
								"defaultAction"=> array(
									"type"=> "uri",
									"label"=> "View detail",
									"uri"=> "http://103.75.103.81:8084"
								),
								"actions"=> [
									array(
										"type"=> "uri",
										"label"=> "Read at Web",
										"uri"=> "http://103.75.103.81:8084"
									)
								]
							);
			}
		} else {
			$balas = $balas . "\n 0 results";
		}
		$bot->reply_carousel($newarray);
	}elseif($text == 'profile'){
		$datetime = new DateTime();
		$date = $datetime->getTimestamp();
		$sql = "SELECT * FROM users WHERE id = 1";
		$result = $conn->query($sql);
	
		$balas = "USER DATA\n";
		//$balas = $balas . "\n Date -- Time -- Training Name";
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$balas = $balas . "\n\nNama:" . $row["name"]. "\nEmail: " . $row["email"]. "\nUsername: " . $row["username"]. "\nPosition: " . $row["position_name"]. "\nBirtdate: " . $row["birtdate"]. "";
			}
		} else {
			$balas = $balas . "\n 0 results";
		}
		$bot->reply($balas);
	}elseif($text =='record'){
		
		$sql = "SELECT * FROM user_chapter_records r JOIN modul_trainings m ON r.id_module_training = m.id JOIN chapters c ON r.id_chapter_training = c.id JOIN users u ON r.id_user = u.id  WHERE  r.id_user=1";
		$result = $conn->query($sql);
		

		$balas = "TRAINING RECORD\n";
		$balas = $balas . "\n TRAINING -- CHAPTER -- STATUS";
		$balas = $balas . "\n =============================\n";
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				if($row['is_finish'] == 1){
					$balas = $balas . "\n ".$row['modul_name']." -- ".$row['chapter_name']." -- Finish";
				}else{
					$balas = $balas . "\n ".$row['modul_name']." -- ".$row['chapter_name']." -- Not Finish";
				}
				
			}
		} else {
			$balas = $balas . "\n 0 results";
		}
		$bot->reply($balas);
	}elseif(strtolower($text) == 'register'){
		$id_user = $bot->getUserId();
		$sql = "SELECT * FROM line_user WHERE  id_user='".$id_user."'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$bot->reply("Anda sudah terbagung dalam sistem kami");
			
		}else{
			$sql = "INSERT INTO line_user(id_user) VALUES ('".$id_user."')";
			if ($conn->query($sql) === TRUE) {
				$bot->reply("Selamat.. Anda tergabung dalam sistem kami.");
			} else {
				$error ="Error: " . $sql . "<br>" . $conn->error;
				$bot->reply($error); 
			}
		}
	}elseif(substr($text,0,5) =='admin'){
		$pesan = substr($text,6,(strlen($text)-6));
		$sql = "SELECT * FROM line_user";
		$result = $conn->query($sql);
		

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$id_user = $row["id_user"]; 
				$bot->pushText($id_user,$pesan);
				
			}
		}
		
	}
}

