<?php
    $json_str = file_get_contents('php://input'); //�𦻖�𤣰request��body
    $json_obj = json_decode($json_str); //頧㗇�郄son�聢撘�
  
    $myfile = fopen("log.txt", "w+") or die("Unable to open file!"); //閮剖�帋��嚯og.txt靘��㫲閮𦠜��
    fwrite($myfile, "\xEF\xBB\xBF".$json_str); //�銁摮𦯀葡��漤𢒰��牐�蹾xEF\xBB\xBF頧㗇�鈎tf8�聢撘�
    
    $sender_userid = $json_obj->events[0]->source->userId; //��硋�𡑒�𦠜�舐䔄������id
    $sender_txt = $json_obj->events[0]->message->text; //��硋�𡑒�𦠜�臬�批捆
    $sender_replyToken = $json_obj->events[0]->replyToken; //��硋�𡑒�𦠜�舐�replyToken
    $imageId = $json_obj->events[0]->message->id; //��硋�堒�𣇉���𦠜�舐楊���
    $url = 'https://api.line.me/v2/bot/message/'.$imageId.'/content'; //��硋�堒�𣇉��雯��
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer 58tGd62pBsrYGL7qy1kx+LJCG8W/SheF6lG0CsIJuP0Rerj/i6md02bTC7ipkRtCC9epuOdT1LVE+gtfk0QD74eA6qJ6nfk9A4UeS8alVgrFkL+2Ww7ZcWzgcFN90KuXkLJ9n6iXKEmFIGPItm4iBwdB04t89/1O/w1cDnyilFU='
    ));
	
    $json_content = curl_exec($ch);
    curl_close($ch);
    $imagefile = fopen($imageId.".jpeg", "w+") or die("Unable to open file!"); //��硋�堒�𣇉��
    fwrite($imagefile, $json_content); 
    fclose($imagefile); //撠��𣇉���睃銁�䌊撌御erver銝�
			
    $header[] = "Content-Type: application/json";
    $post_data = array (
        "requests" => array (
            array (
                "image" => array (
                    "source" => array (
                      //  "imageUri" => "https://159.65.4.103/cht20190214/learning/".$imageId.".jpeg"
                        "imageUri" => '.$url.'
                    )
                ),
                "features" => array (
                    array (
                        "type" => "TEXT_DETECTION",
                        "maxResults" => 1
                    )
                )
            )
        )
    );
  //  fwrite($myfile, "\xEF\xBB\xBF".json_encode($post_data));
    $ch = curl_init('https://vision.googleapis.com/v1/images:annotate?key=AIzaSyBJH3w6aTjoIBYhCh8GiI5byZ0Z-Q88cfg');                                                                      
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
    $result = json_decode(curl_exec($ch));
    $result_ary = explode("\n",$result -> responses[0] -> fullTextAnnotation -> text);
    //fwrite($myfile, "\xEF\xBB\xBF".json_encode($result -> responses[0] -> fullTextAnnotation -> text));
    $ans_txt = "�坔撐�䔄蟡冽�垍鍂鈭�嚗䔶�惩��ˊ�牐�銝�撘萄��䔿";
    foreach ($result_ary as $val) {
        if($val == "MB-76164441"){
          $ans_txt = "�剖�𨀣�其葉��𤾸襥嚗�翰��蝝�!!";
        }
    }
    //fwrite($myfile, "aaaaa");
    $response = array (
        "replyToken" => $sender_replyToken,
        "messages" => array (
            array (
                "type" => "text",
             //   "text" => $ans_txt
            "text" => $result -> responses[0] -> fullTextAnnotation -> text
            )
        )
    );
  
    fwrite($myfile, "\xEF\xBB\xBF".json_encode($response)); //�銁摮𦯀葡��漤𢒰��牐�蹾xEF\xBB\xBF頧㗇�鈎tf8�聢撘�
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
