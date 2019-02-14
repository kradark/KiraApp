<?php
  	$json_str = file_get_contents('php://input'); //接收request的body
  	$json_obj = json_decode($json_str); //轉成json格式
  
  	$myfile = fopen("log.txt", "w+") or die("Unable to open file!"); //設定一個log.txt來印訊息
  	//fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
  
  	$sender_userid = $json_obj->events[0]->source->userId; //取得訊息發送者的id
  	$sender_txt = $json_obj->events[0]->message->text; //取得訊息內容
  	$sender_replyToken = $json_obj->events[0]->replyToken; //取得訊息的replyToken
	  
	$response = array (
		"replyToken" => $sender_replyToken,
		"messages" => array (
			array (
				"type" => "text",
				"text" => "請試用quick replies功能",
				"quickReply" => array (
					"items" => array (
						array (
							"type" => "action",
							"imageUrl" => "https://www.google.com.tw/logos/doodles/2019/valentines-day-2019-4848332248711168-s.png",
							"action" => array (
								"type" => "message",
								"label"=> "Apple",
								"text" => "這是一個Apple"
							)
						),
						array (
                            "type" => "action",
                            "imageUrl" => "https://pic.pimg.tw/dreammaker88/1443152599-1854556715.png",
                            "action" => array (
                                "type" => "location",
                                "label"=> "請選擇位置"
                            )
                        ),
                        array (
                            "type" => "action",
                            "imageUrl" => "https://pic.pimg.tw/dreammaker88/1443152599-1854556715.png",
                            "action" => array (
                                "type" => "camera",
                                "label"=> "啟動相機"
                            )
                        ),
                        array (
                            "type" => "action",
                            "imageUrl" => "https://pic.pimg.tw/dreammaker88/1443152599-1854556715.png",
                            "action" => array (
                                "type" => "cameraRoll",
                                "label"=> "啟動相簿"
                            )
						)
					)
				)	
			)
		)
	);
			
  	fwrite($myfile, "\xEF\xBB\xBF".json_encode($response)); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
  	$header[] = "Content-Type: application/json";
$header[] = "Authorization: Bearer 58tGd62pBsrYGL7qy1kx+LJCG8W/SheF6lG0CsIJuP0Rerj/i6md02bTC7ipkRtCC9epuOdT1LVE+gtfk0QD74eA6qJ6nfk9A4UeS8alVgrFkL+2Ww7ZcWzgcFN90KuXkLJ9n6iXKEmFIGPItm4iBwdB04t89/1O/w1cDnyilFU=";
  	$ch = curl_init("https://api.line.me/v2/bot/message/reply");
  	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
  	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
  	$result = curl_exec($ch);
  	curl_close($ch);
?>
