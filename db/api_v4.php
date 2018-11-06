<?php 

	setlocale(LC_ALL, 'IND');
	date_default_timezone_set('Asia/Jakarta');

	error_reporting(0);
	define('SMSUSER','your user name');
	define('PASSWORD','your password');
	define('SENDERID','your sender id');

	//define (DATABASE, 'dbConnect_v5.php');
	$DATABASE = 'dbConnect_v5.php';
	$SERVER =  "http://103.85.14.86/";
	$SERVER_PROFILE = $SERVER."m/";
	$SERVER_PROFILE_THUMBS=$SERVER_PROFILE."thumbs/";
	
	$SERVER_IMAGE=$SERVER."i/";
	$SERVER_IMAGE_THUMBS=$SERVER_IMAGE."thumbs/";
	
	$FOLDER_PROFILE="m/";
	$FOLDER_PROFILE_THUMBS=$FOLDER_PROFILE."thumbs/";
	
	$FOLDER_IMAGE = "i/";
	$FOLDER_IMAGE_THUMBS=$FOLDER_IMAGE."thumbs/";
 
	function generateThumbnail($thumb_folder, $img, $width, $height, $quality = 90)
	{
		if (is_file($img)) {
			$imagick = new Imagick(realpath($img));
			$imagick->setImageFormat('jpeg');
			$imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
			$imagick->setImageCompressionQuality($quality);
			$imagick->thumbnailImage($width, $height, false, false);
			//$filename = reset($img);
			if (file_put_contents($thumb_folder, $imagick) === false) {
				throw new Exception("Could not put contents.");
			}
			return true;
		}
		else {
			throw new Exception("No valid image provided with {$img}.");
		}
	}
	
	function make_thumb($src, $dest, $desired_width) {

		$desired_width = 200;
		/* read the source image */
		$source_image = imagecreatefromjpeg($src);
		$width = imagesx($source_image);
		$height = imagesy($source_image);

		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = floor($height * ($desired_width / $width));

		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);

		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

		/* create the physical thumbnail image to its destination */
		imagejpeg($virtual_image, $dest);
	}
 
	function send_notification ($tokens, $title, $message, $body='', $url = '')
	{

	$url = 'https://fcm.googleapis.com/fcm/send';

	$msg = array
	(
	 'body' => $body,
	 'title' => "$title",
	 'tag' => $url,		 
	 'priority' => "high",
	 'sound' => "default",
	 'icon' => "icon_notification"
	);

	$data = array
	(
	'data1' => '123'
	);
	$fields = array
	(
	 'to' => $tokens,
	 'notification' => $msg,
	 'priority' =>'high',
	 'data' => array('message' => json_encode($message))
	);


	$fields =array
	(
	'to' => $tokens,
	'data' => array(
		'title' => $title,
		'body' => $body,
		'room' => json_encode($message)
	)
	);
	$headers = array(
	'Authorization:key = AAAAafb15mg:APA91bHLT13Fy4x3g2LqxhrvTTgOlgU3Pt4U0e5jOriDcYZ5WCTV53w4PjmVea_kyTEVK3HZG5PbdnmiAqd6GNYMtlH2CZXrFzmqCwyH0GyBSqZqlJ5H5U3wx90f9EIJ6O8nMJWRvITl',
	'Content-Type: application/json'
	);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);           
	if ($result === FALSE) {
	//die('Curl failed: ' . curl_error($ch));
	$return = "error";
	}else
	 $return = "success";
	curl_close($ch);
	return $return;


	}


	//This function will send the otp 
	function sendOtp($message, $phone){

	/*
	//1.SMS GATEWAY
	include "smsGateway.php";
	$smsGateway = new SmsGateway('mastur.jaelani@gmail.com', 'dede1234');
	$deviceID = '97855';
	$result = $smsGateway->sendMessageToNumber($phone, $message, $deviceID); 	
	*/

	//2.SMS MASKING
	//$url = "http://masking.sms-notifikasi.com/apps/smsapi.php?userkey=blinkcorp&passkey=randompassword&nohp=".$phone."&pesan=".$message."";
	$userkey='blinkcorp';
	$passkey='randompassword';
	$pesan='BLINK VERIFICATION NUMBER : '.$message;
	$url = "http://masking.sms-notifikasi.com/apps/smsapi.php?";
	$curlHandle = curl_init();
	curl_setopt($curlHandle, CURLOPT_URL, $url);
	curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey='.$userkey.'&passkey='.$passkey.'&nohp='.$phone.'&pesan='.urlencode($pesan));
	curl_setopt($curlHandle, CURLOPT_HEADER, 0);
	curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
	curl_setopt($curlHandle, CURLOPT_POST, 1);
	$results = curl_exec($curlHandle);
	curl_close($curlHandle);

	/*
	//3. SINCH
	$contents = file_get_contents($url);

	//If $contents is not a boolean FALSE value.
	if($contents !== false){
	//Print out the contents.
	//echo $contents;
	}

	/*
	$ch = curl_init();
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);

	// grab URL and pass it to the browser
	curl_exec($ch);

	// close cURL resource, and free up system resources
	curl_close($ch);

	/*
	$key = "6a56ee99-7714-4083-a3b1-238c570609b1";    
	$secret = "FTIGqkIw9U65Mue6WCDEOw=="; 
	$phone_number = $phone;
	$user = "application\\" . $key . ":" . $secret;     
	$message = array("message"=>"Test");    
	$data = json_encode($message);    
	$ch = curl_init('https://messagingapi.sinch.com/v1/sms/' . $phone_number);    
	curl_setopt($ch, CURLOPT_POST, true);    
	curl_setopt($ch, CURLOPT_USERPWD,$user);    
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);    
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));    
	$result = curl_exec($ch);    
	if(curl_errno($ch)) {    
	echo 'Curl error: ' . curl_error($ch);    
	} else {    
	echo $result;    
	}   
	curl_close($ch);    
	*/
	}

	//print_r($_GET);

	if (isset($_GET['otp']) and  ($_GET['otp'] =="send") )
	{
		$otp = rand(1000, 9999);
		sendOtp($otp,'+6281321180268');
	}

 
 //If a post request comes to this script 
 if($_SERVER['REQUEST_METHOD']=='POST'){ 
	 
	 //getting username password and phone number 
	 require_once($DATABASE);
	 $action  = $_POST['action'];
	 
	 if ($action == 'register')
	 {
		$nama  = $_POST['nama'];
		$phone = $_POST['phone'];
		//$phone = str_replace("-","",str_replace(" ","", str_replace("+62","0",$_POST['phone']))); 
		$token = $_POST['token'];
			 
		//Generating a 6 Digits OTP or verification code 
		//$otp = rand(100000, 999999);
		$otp = rand(1000, 9999);

		//include "smsGateway.php";
		//$smsGateway = new SmsGateway('mastur.jaelani@gmail.com', 'dede1234');

		//$deviceID = '70727';
		//$deviceID = '70715';
		$deviceID = '80179';
		$number = $phone;
		$message = 'BLINK VERIFICATION NUMBER : '.$otp;

		//Please note options is no required and can be left out
		//$result = $smsGateway->sendMessageToNumber($number, $message, $deviceID); 

		//Importing the db connection script 


		$sql = "SELECT nama FROM phone_otp WHERE phone = '$phone'";

		//Getting the result array from database 
		$result = mysqli_fetch_array(mysqli_query($con,$sql));

		//Getting the otp from the array 
		$realotp = $result['nama'];

		$data_message = array(
		'message' =>  " KODE : ".$otp
		);
		
		//$sql = "insert INTO bulk (text) values ('".json_encode($realotp)."')";
		//mysqli_query($con,$sql);
		 
		if (($realotp == NULL) or ($realotp == ''))
		{
			$sql = "insert INTO bulk (text) values ('".json_encode($realotp)."')";
			mysqli_query($con,$sql);

			//Creating an SQL Query 
			$sql = "INSERT INTO phone_otp (nama, phone, token_baru, otp) values ('$nama','$phone','$token','$otp')";
			
			//If the query executed on the db successfully 
			if(mysqli_query($con,$sql)){
			//printing the response given by sendOtp function by passing the otp and phone number 
				//echo sendOtp($otp,$phone);
				//$result = $smsGateway->sendMessageToNumber($number, $message, $deviceID);
				sendOtp($otp, $number);
				echo "success";//send_notification($token, "YOUR VERIFICATION NUMBER",json_encode($data_message));
				//echo "Success";
			}else{
			//printing the failure message in json 
			echo '{"ErrorMessage":"Failure"}';
			}
		}else
		{
			//Creating an SQL Query 
			//$sql = "UPDATE phone_otp set nama='$nama', token_baru='$token', otp='$otp' where phone = '$phone' ";
			$sql = "UPDATE phone_otp set token_baru='$token', otp='$otp' where phone = '$phone' ";



			//If the query executed on the db successfully 
			if(mysqli_query($con,$sql)){
			//printing the response given by sendOtp function by passing the otp and phone number 
				//echo sendOtp($otp,$phone);
				
				//$result = $smsGateway->sendMessageToNumber($number, $message, $deviceID); 
				sendOtp($otp, $number);
				
				echo "success";//send_notification($token, "YOUR VERIFICATION NUMBER", json_encode($data_message));
				//echo "Success";
			}else{
				//printing the failure message in json 
				echo '{"ErrorMessage":"Failure"}';
			} 
		}

		//Closing the database connection 
		mysqli_close($con);
	 }
	 elseif ($action == "token")
	 {
		 $phone = $_POST['phone']; 
		 //$phone = str_replace("-","",str_replace(" ","", str_replace("+62","0",$_POST['phone']))); 
		 $token = $_POST['token'];
		 
		 $sql = "UPDATE phone_otp set token = '$token' where phone = '$phone' ";			 
		 mysqli_query($con,$sql);
		 
	 }
	 elseif ($action=="userstatus") 
	 {
		 
		 
		 
		 $chatroom_id = $_POST['chatroom_id'];
		 $phone = trim($_POST['phone']);
		 $dt = date("Y-m-d H:i:s");
		 $sql1 = "UPDATE phone_otp set last_online = '".$dt."' WHERE phone = '".$phone."'";
		 mysqli_query($con,$sql1);
		 
		 $sql = "SELECT phone1,phone2 FROM chatrooms WHERE id = '$chatroom_id'";
		 $result = mysqli_fetch_array(mysqli_query($con,$sql));
		 
		 if ($result['phone1'] == $phone)
			 $phone2 = $result['phone2'];
		 else
			 $phone2 = $result['phone1'];
		 
		 
		 
		 $sql = "SELECT last_online FROM phone_otp WHERE phone = '$phone2'";
		 $result = mysqli_fetch_array(mysqli_query($con,$sql));
		 
		 echo $result['last_online'];
		 
	 }	 
	 elseif ($action =="confirm")
	 {
		  
		  //Getting the token and otp 
		 $phone = $_POST['phone'];
		 $nama  = $_POST['nama'];
		 $otp = $_POST['otp'];
		   
		 //Importing the dbConnect script 
		 
		 
		 //Creating an SQL to fetch the otp from the table 
		 $sql = "SELECT otp FROM phone_otp WHERE phone = '$phone'";
		 
		 //Getting the result array from database 
		 $result = mysqli_fetch_array(mysqli_query($con,$sql));
		 
		 //Getting the otp from the array 
		 $realotp = $result['otp'];
		 
		 //If the otp given is equal to otp fetched from database 
		 if($otp == $realotp){
			 
		 if ($nama <> "") $nama = " nama = '".$nama."', ";
		 //Creating an sql query to update the column verified to 1 for the specified user 
		 $sql = "UPDATE phone_otp SET  ".$nama." token = token_baru, verified =  '1' WHERE phone ='$phone'";
		 
		 //If the table is updated 
		 if(mysqli_query($con,$sql)){
		 //displaying success 
		 echo 'success';
		 }else{
		 //displaying failure 
		 echo 'failure';
		 }
		 }else{
		 //displaying failure if otp given is not equal to the otp fetched from database  
		 echo 'failure';
		 }
		 
		 //Closing the database 
		 mysqli_close($con);
	 }
	 elseif ($action=="getuser")
	 {
		 
		 $phone = trim($_POST['phone']);
		 $sql = "SELECT * FROM phone_otp WHERE phone = '$phone'";
		 $result = mysqli_fetch_array(mysqli_query($con,$sql));
		 
		 if (($result['image'] == "") OR ($result['image'] == NULL))
			 $result['image'] = $SERVER."facebook_avatar.png";
		 else
		 {
			 if (substr($result['image'],0,4)=='http')
				$result['image'] = $result['image'];
			else
				$result['image'] = $SERVER_PROFILE.$result['image'];
		 }
		 echo json_encode($result);
		 
	 }	 
	 elseif ($action =="listmessages")
	 {
		  
		 $phone = $_POST['phone']; 
		 $search = $_POST['search']; 
		 
		 
		 
		 if ($seacrh <> '')
			 $seacrh = " AND (c.nama like '%".$search."%') "; 
		 
		 $sql = "SELECT a.*,b.nama nama1, b.token token1, c.nama nama2, c.token token2, sum(if(e.status=0,1,0)) as 'unread', b.image, c.image FROM chatrooms_users d left join chatrooms a on d.id_chatroom = a.id left join phone_otp b on a.phone1 = b.phone 
				left join phone_otp c on a.phone2 = c.phone left join chatdetails as e on (a.phone1 = e.phone and a.id = e.id_chatroom) 
				WHERE d.phone = '$phone' and d.status = '1' ".$seacrh."  group by a.id order by a.datetime desc ";
				
		 $results = mysqli_fetch_all(mysqli_query($con,$sql));
		 
		 $result = array();
		 
		 
		 foreach ($results as $row)
		 {
			 
			 if ($row[7]=='1')
			 {
				$name2 = $row[8];

				$sql = "SELECT image FROM groups_info WHERE id_chatroom = '".$row[0]."' ";
				$result_info = mysqli_fetch_array(mysqli_query($con,$sql));
				$image2 = $result_info['image'];

				if (($image2 <> null) and ($image2 <> '')){
					$image2 = $SERVER."".$image2;
				}
				else
				$image2 = $SERVER."group_avatar.png";

				//$image2 = $SERVER."group_avatar.png";
				$phone2 = $row[2];
				 
			 }else
			 {
				if ($row[1]==$phone)
				{
					$name2 = $row[11];
					$image2 = $row[15];
					$phone2 = $row[2];
				}
				else
				{
					$name2 = $row[9];
					$image2 = $row[14];
					$phone2 = $row[1];
				}


				if (($image2 == '') or ($image2 == NULL))
				{
					$image2 = $SERVER."facebook_avatar.png";
				}
			 }
			
			 $phone1 = $phone;
			 array_push($result,array(
				'id' => $row[0],
				'date' => $row[4],
				'phone1' => $phone1,
				'phone2' => $phone2,
				'name1' => $row[9],
				'name2' => $name2,
				'image2' => $image2,
				'lastmessage' => $row[3],
				'read' => $row[5],
				'new_message' => $row[13],
				'status' => $row[6],
				'group_status' => $row[7],
				'group_name' => $row[8]
			));
		 }
		 
		$stat = array();		
		array_push($stat,array(	'JML'=> '0 Pesan'));
		$data=array("result"=>$result,"stat"=>$stat);
		
		echo json_encode($data);
		 
		 //Closing the database 
		 mysqli_close($con);
	 }
	 elseif ($action =="download_messages")
	 {
		  
		 $phone = $_POST['phone']; 
		 $search = $_POST['search']; 
		 
		 
		 
		 if ($seacrh <> '')
			 $seacrh = " AND (c.nama like '%".$search."%') "; 
		 
		 $sql = "SELECT a.*,b.nama nama1, b.token token1, c.nama nama2, c.token token2, sum(if(e.status=0,1,0)) as 'unread', b.image, c.image FROM chatrooms_users d left join chatrooms a on d.id_chatroom = a.id left join phone_otp b on a.phone1 = b.phone 
				left join phone_otp c on a.phone2 = c.phone left join chatdetails as e on (a.phone1 = e.phone and a.id = e.id_chatroom) 
				WHERE d.phone = '$phone' and d.status = '1' ".$seacrh."  group by a.id order by a.datetime desc ";
				
		 $results = mysqli_fetch_all(mysqli_query($con,$sql));
		 
		 $result = array();
		 
		 
		 foreach ($results as $row)
		 {
			 
			  $image_group = $SERVER."group_avatar.png";
			 // $image2 = $SERVER."facebook_avatar.png";
			 if ($row[7]=='1')
			 {
				$name2 = $row[8];

				$sql = "SELECT image FROM groups_info WHERE id_chatroom = '".$row[0]."' ";
				$result_info = mysqli_fetch_array(mysqli_query($con,$sql));
				$image2 = $result_info['image'];

				if (($image2 <> null) and ($image2 <> ''))
				{
					if (substr($image2,0,1)=='m')
						$image2 = $SERVER.$image2;
					else
						$image2 = $SERVER_PROFILE_THUMBS.$image2;
					
					$image_full = $SERVER.$image2;
				}
				else
				{
					$image2 = $SERVER."group_avatar.png";
					$image_full = $image2;
				}
			
				$phone2 = $row[2];
				 
			 }else{
				 
				if ($row[1]==$phone){
					$name2 = $row[11];
					
					if (substr($row[15],0,4)=='http')
					{
						$image2 = $row[15];
						$image_full = $image2;
					}
					else
					{
						$image2 = $SERVER_PROFILE_THUMBS.$row[15];
						$image_full = $SERVER_PROFILE.$row[15];
					}
					
					$phone2 = $row[2];
				}
				else{
					$name2 = $row[9];
					
					if (substr($row[14],0,4)=='http')
					{
						$image2 = $row[14];
						$image_full = $image2;
					}
					else
					{
						$image2 = $SERVER_PROFILE_THUMBS.$row[14];
						$image_full = $SERVER_PROFILE.$row[14];
					}
					//$image2 = $row[14];
					$phone2 = $row[1];
				}


				if (($image2 == '') or ($image2 == NULL)){
					$image2 = $SERVER."facebook_avatar.png";
				}
			 }
			
			 $phone1 = $phone;
			 array_push($result,array(
				'id' => $row[0],
				'date' => $row[4],
				'phone1' => $phone1,
				'phone2' => $phone2,
				'name1' => $row[9],
				'name2' => $name2,
				'image2' => $image2,
				'image2_full' => $image_full,
				'lastmessage' => $row[3],
				'read' => $row[5],
				'new_message' => $row[13],
				'status' => $row[6],
				'group_status' => $row[7],
				'group_name' => $row[8],
				'group_image' => $image_group
			));
		 }
		 
		$stat = array();		
		array_push($stat,array(	'JML'=> '0 Pesan'));
		$data=array("result"=>$result,"stat"=>$stat);
		
		echo json_encode($data);
		 
		 //Closing the database 
		 mysqli_close($con);
	 }
	 elseif ($action =="listcalls")
	 {
		  
		 $phone = $_POST['phone']; 
		 $search = $_POST['search']; 
		 
		 
		 
		 $sql = "SELECT a.id, a.phone2, b.nama,b.image, a.datetime, a.datetime_end, a.duration, a.type, a.mode, a.status from callrooms a left join phone_otp b on a.phone2 = b.phone where a.phone1 = '".$phone."' order by datetime desc limit 0, 30";
		 $results = mysqli_fetch_all(mysqli_query($con,$sql));
		 
		 //print_r($results);
		 
		 $result = array();
		 
		 
		 foreach ($results as $row)
		 {
			
			$image2 = $row[3];
			if (($image2 == '') or ($image2 == NULL))
			{
				$image2 = $SERVER."facebook_avatar.png";
			} else{
				if (substr($image2,0,4)=='http')
					$image2 = $image2;
				else
					$image2 = $SERVER_PROFILE_THUMBS.$image2;
			}
			
			 array_push($result,
			 array(
				'id' 		=> $row[0],
				'phone2' 	=> $row[1],
				'name2' 	=> $row[2],
				'image2' 	=> $image2,
				'date' 		=> $row[4],
				'duration' 	=> $row[5],
				'type' 		=> $row[7],
				'mode' 		=> $row[8],
				'status' 	=> $row[9],				
				'lastmessage' => $row[7],
				'new_message' => '0',
				'phone1' => '',
				'name1' => '',
				'read' => ''
				
			));
		 }
		 
		$stat = array();		
		array_push($stat,array(	'JML'=> '0 Pesan'));
		$data=array("result"=>$result,"stat"=>$stat);
		
		echo json_encode($data);
		 
		 //Closing the database 
		 mysqli_close($con);
	 }
	 elseif ($action =="listcontacs")
	 {
		  
		 $phone = $_POST['phone']; 
		 $search = $_POST['search']; 
		 
		 
		 
		 $sql_search = "";
		 if ($search <> '') $sql_search = " and b.nama like '%".$seacrh."%' ";
		 
		 
		 $sql = "select a.id, a.phone2, b.nama, b.image, b.last_online, b.verified from contacts a left join phone_otp b on a.phone2 = b.phone  where a.phone1 = '".$phone."' and b.verified = 1 ".$sql_search." group by a.phone2 order by b.nama asc";
		
		 $results = mysqli_fetch_all(mysqli_query($con,$sql));
		 
		 //print_r($results);
		 
		 $result = array();
		 
		 
		 foreach ($results as $row)
		 {
			
			$image2 = $row[3];
			if (($image2 == '') or ($image2 == NULL))
			{
				$image2 = $SERVER."facebook_avatar.png";
			}else{
				if (substr($image2,0,4)=='http')
					$image2 = $image2;
				else
					$image2 = $SERVER_PROFILE_THUMBS.$image2;
			}
			
			$chatroom_id = "";
			$sql_chat = "SELECT id FROM chatrooms WHERE phone1 = '$phone' and phone2 = '".$row[1]."' ";
			$result_chat = mysqli_fetch_array(mysqli_query($con,$sql_chat));
			$chatroom_id = $result_chat['id'];
			if (($chatroom_id == '') or ($chatroom_id == NULL))
			{
				$sql_chat = "SELECT id FROM chatrooms WHERE phone2 = '$phone' and phone1 = '".$row[1]."' ";
				$result_chat = mysqli_fetch_array(mysqli_query($con,$sql_chat));
				$chatroom_id = $result_chat['id'];
				
				if (($chatroom_id == '') or ($chatroom_id == NULL))
					$chatroom_id = "";
			}
			
				
			
			
			 array_push($result,
			 array(
				'id' 		=> $row[0],
				'phone2' 	=> $row[1],
				'name2' 	=> $row[2],
				'image2' 	=> $image2,
				'date' 		=> $row[4],
				'duration' 	=> "",
				'type' 		=> "",
				'mode' 		=> "",
				'status' 	=> $row[5],				
				'lastmessage' => $row[4],
				'new_message' => '0',
				'phone1' => '',
				'name1' => '',
				'read' => '',
				'chatroom_id' => $chatroom_id
				
			));
		 }
		 
		$stat = array();		
		array_push($stat,array(	'JML'=> '0 Pesan'));
		$data=array("result"=>$result,"stat"=>$stat);
		
		echo json_encode($data);
		 
		 //Closing the database 
		 mysqli_close($con);
	 }
	 elseif ($action =="listcontacs_share")
	 {
		  
		 $phone = $_POST['phone']; 
		 $search = $_POST['search']; 
		 
		 
		 
		 $sql_search = "";
		 if ($search <> '') $sql_search = " and b.nama like '%".$seacrh."%' ";
		 
		 $sql ="select a.id, a.group_name, a.datetime,a.lastmessage from chatrooms a left join chatrooms_users b on a.id = b.id_chatroom where a.group_status = '1' and b.phone = '$phone' group by a.id order by group_name asc;";
		 $results = mysqli_fetch_all(mysqli_query($con,$sql));
		 $result = array();
		 foreach ($results as $row)
		 {

			//$image2 = $SERVER."group_avatar.png";
			
			$sql = "SELECT image FROM groups_info WHERE id_chatroom = '".$row[0]."' ";
			$result_info = mysqli_fetch_array(mysqli_query($con,$sql));
			$image2 = $result_info['image'];
			
			if (($image2 <> null) and ($image2 <> '')){
				if (substr($image2,0,1)=='m')
					$image2 = $SERVER."".$image2;
					//$image2 = $image2;
				else
					$image2 = $SERVER_PROFILE_THUMBS.$image2;
			}
			else
				$image2 = $SERVER."group_avatar.png";
			
			 array_push($result,
			 array(
				'id' 		=> $row[0],
				'phone2' 	=> "",
				'name2' 	=> $row[1],
				'image2' 	=> $image2,
				'date' 		=> $row[2],
				'duration' 	=> "",
				'type' 		=> "",
				'mode' 		=> "",
				'status' 	=> "1",				
				'lastmessage' => $row[3],
				'new_message' => '0',
				'phone1' => '',
				'name1' => '',
				'read' => '',
				'chatroom_id' => $row[0]
				
			));
		 }
		 
		 
		 $sql = "select a.id, a.phone2, b.nama, b.image, b.last_online, b.verified, c.id as chatroom_id, d.id as chatroom_id2  from contacts a left join phone_otp b on a.phone2 = b.phone left join chatrooms c on (a.phone2=c.phone2 and a.phone1=c.phone1)  left join chatrooms d on (a.phone2=d.phone1 and a.phone1=d.phone2)where a.phone1 = '".$phone."' and b.verified = 1 ".$sql_search." group by a.phone2 order by b.nama asc";
		 $results = mysqli_fetch_all(mysqli_query($con,$sql));
		 foreach ($results as $row)
		 {
			$image2 = $row[3];
			if (($image2 == '') or ($image2 == NULL))
			{
				$image2 = $SERVER."facebook_avatar.png";
			}else{
				if (substr($image2,0,4)=='http')
					$image2 = $image2;
				else
					$image2 = $SERVER_PROFILE_THUMBS.$image2;
			}
			
			if (($row[6] <> '') and ($row[6] <> NULL))
			{
				$chatroom_id = $row[6];
			} else if (($row[7] <> '') and ($row[7] <> NULL))
			{
				$chatroom_id = $row[7];
			} else 
				$chatroom_id = "";
			
			 array_push($result,
			 array(
				'id' 		=> $row[0],
				'phone2' 	=> $row[1],
				'name2' 	=> $row[2],
				'image2' 	=> $image2,
				'date' 		=> $row[4],
				'duration' 	=> "",
				'type' 		=> "",
				'mode' 		=> "",
				'status' 	=> $row[5],				
				'lastmessage' => $row[4],
				'new_message' => '0',
				'phone1' => '',
				'name1' => '',
				'read' => '',
				'chatroom_id' => $chatroom_id
				
			));
		 }
		 
		$stat = array();		
		array_push($stat,array(	'JML'=> '0 Pesan'));
		$data=array("result"=>$result,"stat"=>$stat);
		echo json_encode($data);
		 //Closing the database 
		 mysqli_close($con);
	 }
	 elseif ($action =="chatroom")
	 {
		  
		 $id = $_POST['id']; 
		 $page = $_POST['page']; 
		 $phone = $_POST['phone']; 
		 
		 
		 
		 
		 
		 
		 $sql = "select * from chatrooms  where id='$id' ";
		 $chatroom = mysqli_fetch_array(mysqli_query($con,$sql));
		 
		 if ($phone <> '')
			$sql = "UPDATE chatdetails set status = '1' where id_chatroom = '$id' and phone= '".$phone."' ";
		 else
			$sql = "UPDATE chatdetails set status = '1' where id_chatroom = '$id'";
		 
		 mysqli_query($con,$sql);
		 
		 if ($page > 1)
			$sql = "SELECT a.*,b.id,b.phone,b.nama FROM chatdetails a left join phone_otp b on a.phone = b.phone  WHERE a.id_chatroom = '$id' order by a.id desc ";
		 else
			$sql = "SELECT a.*,b.id,b.phone,b.nama FROM chatdetails a left join phone_otp b on a.phone = b.phone  WHERE a.id_chatroom = '$id' order by a.id asc ";
		
		 $count = mysqli_num_rows(mysqli_query($con,$sql));
		 
		 //$sql = $sql . " limit ".(($count >=50) ? ($count-50) : 0) . ", 50";
		 
		 $limit = 20;
		 //$limit = 100000000;
		 
		 $from = ($count >=$limit) ? ($count-$limit) : 0;
		 
		 if ($page > 1)
		 {
			 /*
			 $total = $page*$limit;
			 
			 if ($total <= $count)
				$from = $count - $total;
			 else
			 {
				 $from = 0;
				 $limit = 0;
			 }
			 */
			 $from = ($page-1)*$limit;
		 }
		 
		 $sql = $sql . " limit ". $from . ", ".$limit;
		 $results = mysqli_fetch_all(mysqli_query($con,$sql));
		 
		 //print_r($results);
		 
		 $result = array();
		 
		 foreach ($results as $row)
		 {
			 
			 $image = '';
			 $image_full = '';
			 if ($row[6] <> '')
			 {
				if (substr($row[6],0,4)=='http')
				{
					$image = $row[6];
					$image_full = $row[6];
				}
				else
				{
					$image = $SERVER_IMAGE.$row[6];
					$image_full = $SERVER_IMAGE.$row[6];
				}
			 }
			
			 array_push($result,array(
				'message_id' => $row[0],
				'message' => $row[4],
				'image' => $image,
				'image_full' => $image_full,
				'created_at' => $row[3],
				'type' => $row[7],
				'status' => $row[5],
				'user_id' => $row[8],
				'user_phone' => $row[9],
				'user_name' => $row[10],
				'group_status' => $chatroom['group_status'],
				'group_name' => $chatroom['group_name']
			));
		 }
		 
		$stat = array();		
		array_push($stat,array(	'JML'=> '0 Pesan'));
		$data=array("result"=>$result,"stat"=>$stat);
		
		echo json_encode($data);
		 
		 //Closing the database 
		 mysqli_close($con);
	 }
	 elseif ($action =="download_chatroom")
	 {
		$phone = $_POST['phone'];  
		

		$sql = "select * from chatrooms  where id='$id' ";
		$chatroom = mysqli_fetch_array(mysqli_query($con,$sql));		 

		$sql = "SELECT a.* FROM chatdetails a left join chatrooms_users b on a.id_chatroom = b.id_chatroom  WHERE b.phone = '".$phone."' order by a.id asc ";

		$results = mysqli_fetch_all(mysqli_query($con,$sql));
		$result = array();
		 
		 foreach ($results as $row)
		 {
			 array_push($result,array(
				'message_id' => $row[0],
				'chatroom_id' => $row[1],
				'user_phone' => $row[2],
				'created_at' => $row[3],
				'message' => $row[4],
				'image' => $row[6],
				'image_full' => $row[6],
				'type' => $row[7],
				'status' => $row[5]
			));
		 }
		 
		$stat = array();		
		array_push($stat,array(	'JML'=> '0'));
		$data=array("result"=>$result,"stat"=>$stat);
		
		echo json_encode($data);
		 
		 //Closing the database 
		 mysqli_close($con);
	 }
	 elseif ($action =="sendmessage")
	 {
		  
		 $chatroom_id = $_POST['chatroom_id']; 
		 $userid = $_POST['userid']; 
		 $name = $_POST['name']; 
		 $name2 = $_POST['name2']; 
		 $phone = $_POST['phone']; 
		 $phone2 = $_POST['phone2']; 
		 //$phone =  str_replace("-","",str_replace(" ","", str_replace("+62","0",$_POST['phone']))); 
		 //$phone2 = str_replace("-","",str_replace(" ","", str_replace("+62","0",$_POST['phone2']))); 
		 $message = $_POST['message']; 
		 $type = $_POST['type']; 
		 $dt = date("Y-m-d H:i:s");
		 $chat = trim($chatroom_id);
		 
		 	
		 
		 
		 $image = $_POST['image'];
		 
		 /*
		 $sql = "select * from chatrooms  where phone1='$phone' and phone2='$phone2' ";
		 $chatroom = mysqli_fetch_array(mysqli_query($con,$sql));
		 if (($chatroom['id'] <> NULL) and ($chatroom['id'] <> ''))
		 {
			 $chatroom_id = $chatroom['id'];
		 }else{
			 $sql = "select * from chatrooms  where phone2='$phone' and phone1='$phone2' ";
			 $chatroom = mysqli_fetch_array(mysqli_query($con,$sql));
			 if (($chatroom['id'] <> NULL) and ($chatroom['id'] <> ''))
			 {
				 $chatroom_id = $chatroom['id'];
			 }
		 }
		 */
		 
		 if (trim($chatroom_id) == 'new'){
			 
			 //$sql = "insert INTO bulk (text) values ('".json_encode($_POST)."')";
			 //mysqli_query($con,$sql);
		 
			 $sql = "INSERT INTO chatrooms (phone1, phone2, lastmessage, datetime) values ('$phone', '$phone2', '$message', '$dt')";
			 if(mysqli_query($con,$sql))
			 {
				$chatroom_id = mysqli_insert_id($con);
				
				$sql = "INSERT INTO chatrooms_users (id_chatroom, phone) values ('$chatroom_id','$phone') "; mysqli_query($con,$sql);
				$sql = "INSERT INTO chatrooms_users (id_chatroom, phone) values ('$chatroom_id','$phone2') "; mysqli_query($con,$sql);
				
				$sql = "INSERT INTO phone_otp (nama, phone) values ('$name2','$phone2') "; mysqli_query($con,$sql);
			 }
			 
			$sql = "SELECT * from contacts where phone1 = '$phone1' and phone2 = '$phone2'";
			$user = mysqli_fetch_array(mysqli_query($con,$sql));
			if (($user['status'] == NULL) or ($user['status'] == ''))
			{
				$sql = "INSERT INTO contacts (phone1, phone2, datetime) values ('$phone','$phone2','".$dt."') "; mysqli_query($con,$sql);
				mysqli_query($con,$sql);
			}
		 }
		 
		 $sql = "select * from chatrooms  where id='$chatroom_id' ";
		 $result_chatroom = mysqli_fetch_array(mysqli_query($con,$sql));
		 
		 $sql = "SELECT * from phone_otp where phone = '$phone'";
		 $user = mysqli_fetch_array(mysqli_query($con,$sql));
		 
		 $sql = "SELECT * from phone_otp where phone = '$phone2'";
		 $user2 = mysqli_fetch_array(mysqli_query($con,$sql));
		 
		 //Creating an SQL Query 
		 
		 
		 $id = md5(date("Y-m-d H:i:s"));
					
		$filename = "";
		if (isset($_POST['image']))
		{
			// $name_image = "Blink_".$chatroom_id."_".$id.".jpg";
			// $filename = "i/".$name_image;
			// file_put_contents($filename,base64_decode($image));
			// $filename = $SERVER."".$filename;
			
			$thumb_folder = $FOLDER_IMAGE_THUMBS;
			$name_image = "Blink_".$chatroom_id."_".$id.".jpg";
			$filename = $FOLDER_IMAGE.$name_image;
			if (file_put_contents($filename,base64_decode($image)))
			{
				make_thumb($filename, $thumb_folder.$name_image, 100);
			}
			
			//try {generateThumbnail($thumb_folder.$name_image, $filename, 60, 60, $quality = 90);}catch (ImagickException $e) {}catch (Exception $e) {}
			$filename = $SERVER_IMAGE.$name_image;
			//$img = ', image = "'.$name_image.'"';
			
		}
				
		 
		 $sql = "INSERT INTO chatdetails (id_chatroom, phone, datetime, message, status, image,type) values ('$chatroom_id','$phone','$dt','$message','0','$name_image','$type')";
		 //file_put_contents("querysql".time().".txt",$sql);
		 $message_id = '';
		 if(mysqli_query($con,$sql))
		 {
			$message_id = mysqli_insert_id($con);
			
			if ($result_chatroom['group_status']=='1')
				$notif_msg = "@".$name.": ".$message;
			else
				$notif_msg = $message;
			
			
			$sql = "UPDATE chatrooms set lastmessage = '".$notif_msg."', datetime = '$dt'  where id = '$chatroom_id'";
			mysqli_query($con,$sql);
			
			$sql = "UPDATE  chatrooms_users set status = '1' where id_chatroom = '$chatroom_id' ";
			mysqli_query($con,$sql);
			
			$user_image = $user['image'];
			if (($user_image == '') or ($user_image == NULL))
			{
				$user_image = $SERVER."facebook_avatar.png";
			} else
			{
				if (substr($user_image,0,4)=='http')
				{
					$user_image = $user_image;
				}else
				{
					$user_image = $SERVER_PROFILE_THUMBS.$user_image;
				}
			}
			
			
			
			$data_message = array(
				'chat' => $chat,
				'chat_room_id' => $chatroom_id,
				'message_id' => $message_id,
				'image' => $filename,
				'message' => $message,
				'create_at' => $dt,
				'type' => $type,
				'status' => '0',
				'user_id' => $user['id'],
				'user_phone' => $phone,
				'user_name' => $name,
				'user_image' => $user_image,
				'user_name2' => $name2,
				'group_status' => $result_chatroom['group_status'],
				'group_name' => $result_chatroom['group_name']
			);
			
			$sql = "SELECT b.token,b.nama,b.phone as token FROM chatrooms_users a left join phone_otp b on a.phone = b.phone  WHERE a.id_chatroom = '$chatroom_id'";
			$results = mysqli_fetch_all(mysqli_query($con,$sql));
			foreach ($results as $row)
			{
				 $token = $row[0];
				 if ($row[2] <> $phone)
					 if ($token <> '')
					 {
						if ($filename <> '') $msg ='Mengirim gambar';
						else if ($type == '5') $msg ='Mengirim lokasi';
						else $msg = 'Mengirim sebuah pesan';
						
						send_notification($token, $name, $data_message, $msg);
					 }
			}
		 }
			 		 
		 //print_r($results);
		 
		 $user_image = $user2['image'];
		if (($user_image == '') or ($user_image == NULL))
		{
			$user_image = $SERVER."facebook_avatar.png";
		} else
		{
			if (substr($user_image,0,4)=='http')
			{
				$user_image = $user_image;
			}else
			{
				$user_image = $SERVER_PROFILE_THUMBS.$user_image;
			}
		}
		 
		 $result = array(
				'chat' => $chat,
				'chat_room_id' => $chatroom_id,
				'message_id' => $message_id,
				'image' => $filename,
				'message' => $message,
				'created_at' => $dt,
				'type' => $type,
				'status' => '0',
				'user_id' => $user['id'],
				'user_phone' => $phone,
				'user_name' => $name2,				
				'user_image' => $user_image,
				'group_status' => $result_chatroom['group_status'],
				'group_name' => $result_chatroom['group_name']
			);
		 
		 
		$data=array("result"=>$result);
		echo json_encode($data);
		 
		 //Closing the database 
		 mysqli_close($con);
	 }
	 elseif ($action =="uploadPhoto")
	 {
		 
		$name = $_POST['name']; 
		$phone = $_POST['phone'];  
		
		$image = $_POST['image'];
		$id = md5(date("Y-m-d H:i:s"));
		$filename = "";
		$img = "";
		if (isset($_POST['image']))
		{
			$thumb_folder = $FOLDER_PROFILE_THUMBS;
			$name_image = "BlinkUser_".$id.".jpg";
			$filename = $FOLDER_PROFILE.$name_image;
			if (file_put_contents($filename,base64_decode($image)))
			{
				make_thumb($filename, $thumb_folder.$name_image, 100);
			}
			
			//try {generateThumbnail($thumb_folder.$name_image, $filename, 60, 60, $quality = 90);}catch (ImagickException $e) {}catch (Exception $e) {}
			//$filename = $SERVER.$filename;
			$img = ', image = "'.$name_image.'"';
		}
		$sql = 'UPDATE phone_otp set nama = "'.$name.'" '.$img.' where phone = "'.$phone.'"'; 
		mysqli_query($con,$sql);		
		echo "success";		 
		mysqli_close($con);
		
	 }
	 elseif ($action =="deletechat")
	 {
		  
		 
		 $chatroom_id = $_POST['chatroom_id']; 
		 $phone = $_POST['phone']; 
		 
		 
		 
		$sql = 'UPDATE chatrooms_users set status = "0" where phone = "'.$phone.'" and id_chatroom = "'.$chatroom_id.'" '; 
		mysqli_query($con,$sql);
		
		echo "success";
		 
		 //Closing the database 
		 mysqli_close($con);
	 }
	 elseif ($action =="deletecall")
	 {
		  
		 
		 $id = $_POST['chatroom_id']; 
		 $phone = $_POST['phone']; 
		 
		 
		 
		$sql = 'delete from callrooms  where phone1 = "'.$phone.'" and id = "'.$id.'" '; 
		mysqli_query($con,$sql);
		
		echo "success";
		 
		 //Closing the database 
		 mysqli_close($con);
	 }
	 elseif ($action =="deletecontact")
	 {
		  
		 
		 $id = $_POST['chatroom_id']; 
		 $phone = $_POST['phone']; 
		 
		 
		 
		$sql = 'delete from contacts  where phone1 = "'.$phone.'" and id = "'.$id.'" '; 
		mysqli_query($con,$sql);
		
		echo "success";
		 
		 //Closing the database 
		 mysqli_close($con);
	 }
	 elseif ($action =="makecall")
	 {

		$phone1 = $_POST['phone1']; 
		$phone2 = $_POST['phone2']; 
		// $phone1 = str_replace("-","",str_replace(" ","", str_replace("+62","0",$_POST['phone1']))); 
		// $phone2 = str_replace("-","",str_replace(" ","", str_replace("+62","0",$_POST['phone2']))); 
		$nama2 = $_POST['nama2']; 
		$mode = $_POST['mode']; 
		$dt = date("Y-m-d H:i:s");
		
		if ($nama2 == "") $nama2 = $phone2;
		 
		
		
		
		 
		$sql = "SELECT * from phone_otp where phone = '$phone2'";
		$user = mysqli_fetch_array(mysqli_query($con,$sql));
		
		if (($user['nama'] == NULL) or ($user['nama'] == ''))
		{
			
			 $image = $_POST['image'];
		 
			 $id = md5(date("Y-m-d H:i:s"));
						
			$name_image = "";
			$img = "";
			if (isset($_POST['image']))
			{
				// $name_image = "BlinkUser_".$id.".jpg";
				// $filename = "m/".$name_image;
				// file_put_contents($filename,base64_decode($image));
				// $filename = $SERVER."".$filename;
				
				$thumb_folder = $FOLDER_PROFILE_THUMBS;
				$name_image = "BlinkUser_".$id.".jpg";
				$filename = $FOLDER_PROFILE.$name_image;
				if (file_put_contents($filename,base64_decode($image)))
				{
					make_thumb($filename, $thumb_folder.$name_image, 100);
				}
			
				//$img = ', image = "'.$filename.'"';
			}
		
			$sql = "INSERT INTO phone_otp (nama, phone, image) values ('$nama2','$phone2','$name_image') "; mysqli_query($con,$sql);
			mysqli_query($con,$sql);
			
			$sql = "INSERT INTO contacts (phone1, phone2, datetime) values ('$phone1','$phone2','".$dt."') "; mysqli_query($con,$sql);
			mysqli_query($con,$sql);
		}
		
		$sql = "INSERT INTO contacts (phone1, phone2, datetime) values ('$phone1','$phone2','".$dt."') "; mysqli_query($con,$sql);
		mysqli_query($con,$sql);
		 
		 
		
		$sql = 'insert into callrooms(phone1, phone2, datetime, type, mode, status) values ("'.$phone1.'","'.$phone2.'","'.$dt.'", "call", "'.$mode.'", 0)'; 
		mysqli_query($con,$sql);
		$id = mysqli_insert_id($con);
		echo $id;
		mysqli_close($con);
	 }
	 elseif ($action =="makecall_answered")
	 {
		$id = $_POST['id']; 
		
		$dt = date("Y-m-d H:i:s");
		$sql = 'Update callrooms set status = 1 where id = "'.$id.'"'; 
		mysqli_query($con,$sql);
		$id = mysqli_insert_id($con);
		echo "success";
		mysqli_close($con);
	 }
	 elseif ($action =="makecall_stop")
	 {
		$id = $_POST['id']; 
		$duration = $_POST['duration']; 
		
		$dt = date("Y-m-d H:i:s");
		$sql = 'Update callrooms set datetime_end = "'.$dt.'", duration = "'.$duration.'" where id = "'.$id.'"'; 
		mysqli_query($con,$sql);
		$id = mysqli_insert_id($con);
		echo "success";
		mysqli_close($con);
	 }
	 elseif ($action =="call_incoming")
	 {
		  
		$phone1 = $_POST['phone1']; 
		$phone2 = $_POST['phone2']; 
		// $phone1 = str_replace("-","",str_replace(" ","", str_replace("+62","0",$_POST['phone1']))); 
		// $phone2 = str_replace("-","",str_replace(" ","", str_replace("+62","0",$_POST['phone2'])));  
		$mode = $_POST['mode'];
		 
		
		$dt = date("Y-m-d H:i:s");
		
		$sql = "SELECT * from contacts where phone1 = '$phone1' and phone2 = '$phone2'";
		$user = mysqli_fetch_array(mysqli_query($con,$sql));
		if (($user['status'] == NULL) or ($user['status'] == ''))
		{
			$sql = "INSERT INTO contacts (phone1, phone2, datetime) values ('$phone1','$phone2','".$dt."') "; mysqli_query($con,$sql);
			mysqli_query($con,$sql);
		}
			
		$sql = 'insert into callrooms(phone1, phone2, datetime, type, mode, status) values ("'.$phone1.'","'.$phone2.'","'.$dt.'", "receive", "'.$mode.'", 0)'; 
		mysqli_query($con,$sql);
		$id = mysqli_insert_id($con);
		echo $id;
		mysqli_close($con);
		
	 }
	 elseif ($action =="new_group")
	 { 
		 $name1 = $_POST['name']; 
		 $phone1 = $_POST['phone']; 
		 // $phone1 =  str_replace("-","",str_replace(" ","", str_replace("+62","0",$_POST['phone']))); 
		 
		 $group_name = $_POST['group_name']; 
		 $dt = date("Y-m-d H:i:s");
		 $image = $_POST['image'];
		 
		 $participants = $_POST['participats']; 
		 $arr_part = explode("|",$participants);
		 
		 	
		 
		 
			 
			 //$sql = "insert INTO bulk (text) values ('".json_encode($_POST)."')";
			 //mysqli_query($con,$sql);
		 
		 $sql = "INSERT INTO chatrooms (phone1, phone2, lastmessage, datetime, group_status, group_name) values ('$phone1','', 'Dibuat oleh @".$phone1."', '$dt','1','$group_name')";
		 //file_put_contents("query1.txt",$sql);
		 if(mysqli_query($con,$sql))
		 {
			$chatroom_id = mysqli_insert_id($con);
			
			$sql = "INSERT INTO chatrooms_users (id_chatroom, phone) values ('$chatroom_id','".$phone1."') "; mysqli_query($con,$sql);
			
			for ($idx=0;$idx<count($arr_part)-1;$idx++)
			{
				$sql = "INSERT INTO chatrooms_users (id_chatroom, phone) values ('$chatroom_id','".$arr_part[$idx]."') "; mysqli_query($con,$sql);
			}
		
			
		 }
		 
		$id = md5(date("Y-m-d H:i:s"));
					
		$filename = "";
		if (isset($_POST['image']))
		{
			$name_image = "Blink_".$chatroom_id."_".$id.".jpg";
			$filename = "i/".$name_image;
			file_put_contents($filename,base64_decode($image));
			$filename = $SERVER."".$filename;
		}
		
		$data_message = array(
			'chat' => $chatroom_id,
			'chat_room_id' => $chatroom_id,
			'message_id' => 0,
			'image' => $SERVER."group_avatar.png",
			'message' => 'Dibuat oleh @'.$phone1,
			'create_at' => $dt,
			'type' => "1",
			'user_id' => "",
			'user_phone' => $phone1,
			'user_name' => $group_name,
			'user_image' => $SERVER."group_avatar.png",
			'user_name2' => $group_name,
			'group_status' => "1",
			'group_name' => $group_name
		);
			
		$sql = "SELECT b.token as token ,b.nama, b.phone  FROM chatrooms_users a left join phone_otp b on a.phone = b.phone  WHERE a.id_chatroom = '$chatroom_id'";
		$results = mysqli_fetch_all(mysqli_query($con,$sql));
		foreach ($results as $row)
		{
			 $token = $row[0];
			 if ($row[2] <> $phone1)
			 {
				 if ($token <> '')
				 {
					$msg = $name1.' Mengundang anda ke grup '.$group_name;					
					send_notification($token, $name, $data_message, $msg);
				 }
			 } else
			 {
				 if ($token <> '')
				 {
					$msg = ' Anda berhasil membuat grup '.$group_name;					
					send_notification($token, $name, $data_message, $msg);
				 }
			 }
		}
		
		$result = array(
				'chat' => $chatroom_id,
				'chat_room_id' => $chatroom_id,
				'message_id' => 0,
				'image' => $SERVER."group_avatar.png",
				'message' => 'Dibuat oleh @'.$phone1,
				'created_at' => $dt,
				'type' => "1",
				'user_id' => "",
				'user_phone' => $phone1,
				'user_name' => $group_name,
				'user_image' => $SERVER."group_avatar.png",
				'group_status' => "1",
				'group_name' => $group_name
			);
		 
		 
		$data=array("result"=>$result);
		echo json_encode($data);
	 
		 //Closing the database 
		 mysqli_close($con);
	 }
	 elseif ($action =="add_user_to_group")
	 { 
		$chatroom_id = $_POST['group_id']; 
		$name1 = $_POST['name']; 
		$phone1 = $_POST['phone']; 
		// $phone1 =  str_replace("-","",str_replace(" ","", str_replace("+62","0",$_POST['phone']))); 

		$dt = date("Y-m-d H:i:s");

		$participants = $_POST['participats']; 
		$arr_part = explode("|",$participants);

		


		$sql = "SELECT group_name from chatrooms where id = '$chatroom_id' ";
		$group_detail = mysqli_fetch_array(mysqli_query($con,$sql));
		$group_name = $group_detail['group_name'];
		 		 	
		$all_phone = array();
		for ($idx=0;$idx<count($arr_part)-1;$idx++)
		{
			$sql = "INSERT INTO chatrooms_users (id_chatroom, phone) values ('$chatroom_id','".$arr_part[$idx]."') "; 
			mysqli_query($con,$sql);
			
			$all_phone[$idx] = $arr_part[$idx];
		}
		
		$phones = join(',',$all_phone);
		 
		$message = $phones." ditambahkan oleh @".$name1;
		$notif_msg = "@".$name1." menambahkan ".$phones;
		$sql = "INSERT INTO chatdetails (id_chatroom, phone, datetime, message, status, image,type) values ('$chatroom_id','$phone1','$dt','$message','0','','1')";
		 $message_id = '';
		 if(mysqli_query($con,$sql))
		 {
			$message_id = mysqli_insert_id($con);
			
			$sql = "UPDATE chatrooms set lastmessage = '".$notif_msg."', datetime = '$dt'  where id = '$chatroom_id'";
			mysqli_query($con,$sql);
			
			$data_message = array(
					'chat' => $chatroom_id,
					'chat_room_id' => $chatroom_id,
					'message_id' => $message_id,
					'image' => "",
					'message' => $message,
					'create_at' => $dt,
					'type' => "1",
					'user_id' => "",
					'user_phone' => $phone1,
					'user_name' => $group_name,
					'user_image' => $SERVER."group_avatar.png",
					'user_name2' => $group_name,
					'group_status' => "1",
					'group_name' => $group_name
				);	
				
			$sql = "SELECT b.token as token ,b.nama, b.phone  FROM chatrooms_users a left join phone_otp b on a.phone = b.phone  WHERE a.id_chatroom = '$chatroom_id'";
			$results = mysqli_fetch_all(mysqli_query($con,$sql));
			foreach ($results as $row)
			{
				 $token = $row[0];
				 if ($row[2] <> $phone1)
				 {
					 if ($token <> '')
					 {
						$msg = $name1.' menambahkan '.$phones.' ke grup '.$group_name;					
						send_notification($token, $name, $data_message, $msg);
					 }
				 } else
				 {
					 if ($token <> '')
					 {
						$msg = ' Anda berhasil menambahkan '.$phones.' ke grup '.$group_name;					
						send_notification($token, $name, $data_message, $msg);
					 }
				 }
			}
		 }
		
		/*
		
		$result = array(
				'chat' => $chatroom_id,
				'chat_room_id' => $chatroom_id,
				'message_id' => 0,
				'image' => $SERVER."group_avatar.png",
				'message' => 'Dibuat oleh @'.$phone1,
				'created_at' => $dt,
				'type' => "1",
				'user_id' => "",
				'user_phone' => $phone1,
				'user_name' => $group_name,
				'user_image' => $SERVER."group_avatar.png",
				'group_status' => "1",
				'group_name' => $group_name
			);
		 
		 
		$data=array("result"=>$result);
		echo json_encode($data);
		*/
		echo "1";
	 
		 //Closing the database 
		 mysqli_close($con);
	 }
	 elseif ($action=="saveContact") 
	 {
		 
		 
		 
		 $phone1 = $_POST['phone1']; 
		 // $phone1 = str_replace("-","",str_replace(" ","", str_replace("+62","0",$_POST['phone1']))); 
		 
		 $phones = $_POST['phone2'];
		 
		 $arr_phone2 = explode("|",$phones);
		 
		 for ($i=0;$i<count($arr_phone2)-1;$i++)
		 {
			 
			 $phone2 = $arr_phone2[$i]; 
			 $phone2 = str_replace("-","",str_replace(" ","", $arr_phone2[$i]));
			 if (substr($phone2,0,1) =='0')
				 $phone2 = "+62".substr($phone2,1,20);
			 
			 $sql = "SELECT count(*) as JML FROM phone_otp WHERE phone = '$phone2' and verified = '1' ";
			 $result = mysqli_fetch_array(mysqli_query($con,$sql));
			 
			 if ($result['JML'] > 0)
			 {
				if ($phone1 <> $phone2)
				{
					$dt = date("Y-m-d H:i:s");
					$sql = "INSERT INTO contacts(phone1,phone2,datetime) values('$phone1','$phone2','$dt') ";
					$result = mysqli_fetch_array(mysqli_query($con,$sql));
				}
			 }
		 }
		 echo "1";
	 }
	 elseif ($action =="group_info")
	 { 
		 
		 sleep(1);
		 $chatroom_id = $_POST['chatroom_id']; 
		 	
		 
		 
		$sql = "SELECT a.phone, b.nama   FROM chatrooms_users a left join phone_otp b on a.phone = b.phone  WHERE a.id_chatroom = '$chatroom_id'";
		$results = mysqli_fetch_all(mysqli_query($con,$sql));
		
		$result = array();		
		$nama = array();
		$i=0;
		foreach ($results as $row)
		{
			
			array_push($result,array(
				'phone' => $row[0],
				'name' => $row[1]
			));
			$nama[$i] = $row[1];
			$i++;
			//$result['member'][] = $row[0];
		}
		
		$title = join(',',$nama);
		
		$sql = "SELECT image FROM groups_info WHERE id_chatroom = '".$chatroom_id."' ";
		$result = mysqli_fetch_array(mysqli_query($con,$sql));
		$image = $result['image'];
		
		if (($image <> null) and ($image <> '')){
			//$image = $SERVER."".$image;
			
			if (substr($image,0,1)=='m')
				$image = $SERVER.$image;
			else
				$image = $SERVER_PROFILE.$image;
		}
		else
			$image = $SERVER."group_avatar.png";
		 
		$data=array("title"=>$title,"image"=>$image);
		//echo $title;
		echo json_encode($data);
		mysqli_close($con);
	 }
	 elseif ($action =="group_contacts")
	 { 
		 
		 //sleep(1);
		 $phone1 = $_POST['phone']; 
		 $chatroom_id = $_POST['chatroom_id']; 
		 	
		 
		//$sql = "select a.id, a.phone2, b.nama, b.image, b.last_online, b.verified from contacts a left join phone_otp b on a.phone2 = b.phone  where a.phone1 = '".$phone."' and b.verified = 1 ".$sql_search." group by a.phone2 order by b.nama asc"; 
		$sql = "SELECT b.id, a.phone, b.nama,b.image, b.last_online, b.verified, c.phone1   FROM chatrooms_users a left join phone_otp b on a.phone = b.phone left join chatrooms c on a.id_chatroom = c.id  WHERE a.id_chatroom = '$chatroom_id'";
		
		//file_put_contents("query_group.txt",$sql);
		$results = mysqli_fetch_all(mysqli_query($con,$sql));
		
		$result = array();		
		$i=0;
		
		$created_by = "";
		foreach ($results as $row)
		{
			
			$image2 = $row[3];
			if (($image2 == '') or ($image2 == NULL))
			{
				$image2 = $SERVER."facebook_avatar.png";
			} else
			{
				if (substr($image2,0,4)=='http')
					$image2 = $image2;
				else
					$image2 = $SERVER_PROFILE_THUMBS.$image2;
			}
			
			$chatroom_id = "";
			$sql_chat = "SELECT id FROM chatrooms WHERE phone1 = '$phone' and phone2 = '".$row[1]."' ";
			$result_chat = mysqli_fetch_array(mysqli_query($con,$sql_chat));
			$chatroom_id = $result_chat['id'];
			if (($chatroom_id == '') or ($chatroom_id == NULL))
			{
				$sql_chat = "SELECT id FROM chatrooms WHERE phone2 = '$phone' and phone1 = '".$row[1]."' ";
				$result_chat = mysqli_fetch_array(mysqli_query($con,$sql_chat));
				$chatroom_id = $result_chat['id'];
				
				if (($chatroom_id == '') or ($chatroom_id == NULL))
					$chatroom_id = "";
			}
			
			if ($row[1]==$row[6])
			{
				$admin = "1";
				$created_by = $row[2];
			}
			else
				$admin = "0";
			
			//if ($phone1 == $row[1])
				//$phone = $row[1]." ~ Saya";
			//else
				$phone = $row[1];
			
			array_push($result,
			 array(
				'id' 		=> $row[0],
				'phone2' 	=> $phone,
				'name2' 	=> $row[2],
				'image2' 	=> $image2,
				'date' 		=> $row[4],
				'duration' 	=> "",
				'type' 		=> "",
				'mode' 		=> "",
				'status' 	=> $row[5],				
				'lastmessage' => $row[4],
				'new_message' => '0',
				'phone1' => '',
				'name1' => '',
				'read' => '',
				'chatroom_id' => $chatroom_id,
				'admin' => $admin
				
			));
		 }
		 
		$stat = array();		
		array_push($stat,array(	
		'JML'=> '0 Pesan',
		'created_by'=> 'Dibuat oleh : @'.$created_by)
		);
		$data=array("result"=>$result,"stat"=>$stat);
		
		echo json_encode($data);
	 }	
	elseif ($action =="changeGroupImage")
	 {
		  
		 
		 $name = $_POST['name']; 
		 $phone = $_POST['phone']; 
		 $chatroom_id = $_POST['chatroom_id']; 
		 
		 
		 
		 $image = $_POST['image'];
		 
		 $dt = date("Y-m-d H:i:s");
		 $id = md5($dt);
					
		$filename = "";
		$img = "";
		if (isset($_POST['image']))
		{
			
			$thumb_folder = $FOLDER_PROFILE_THUMBS;
			$name_image = "BlinkGroup_".$id.".jpg";
			$filename = $FOLDER_PROFILE.$name_image;
			if (file_put_contents($filename,base64_decode($image)))
			{
				make_thumb($filename, $thumb_folder.$name_image, 100);
			}
			
			// $name_image = "BlinkGroup_".$id.".jpg";
			// $filename = "m/".$name_image;
			// file_put_contents($filename,base64_decode($image));
			
					
			$sql = 'INSERT into groups_info set image = "'.$name_image.'", date_update = "'.$dt.'", user_update = "'.$phone.'", id_chatroom = "'.$chatroom_id.'"'; 
			if(!mysqli_query($con,$sql))
			{
				
				$sql = 'UPDATE groups_info set image = "'.$name_image.'", date_update = "'.$dt.'", user_update = "'.$phone.'" where id_chatroom = "'.$chatroom_id.'"';
				mysqli_query($con,$sql);
			}
			echo $name_image;
		}
		
		 //Closing the database 
		 mysqli_close($con);
	 }
}
 ?>