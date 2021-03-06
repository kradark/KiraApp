<?php
    $json_str = file_get_contents('php://input'); //接收request的body
    $json_obj = json_decode($json_str); //轉成json格式
  
    $myfile = fopen("log.txt", "w+") or die("Unable to open file!"); //設定一個log.txt來印訊息
    fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
    
    $sender_userid = $json_obj->events[0]->source->userId; //取得訊息發送者的id
    $sender_txt = $json_obj->events[0]->message->text; //取得訊息內容
    $sender_replyToken = $json_obj->events[0]->replyToken; //取得訊息的replyToken
    $imageId = $json_obj->events[0]->message->id; //取得圖片訊息編號
    $url = 'https://api.line.me/v2/bot/message/'.$imageId.'/content'; //取得圖片網址
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer 58tGd62pBsrYGL7qy1kx+LJCG8W/SheF6lG0CsIJuP0Rerj/i6md02bTC7ipkRtCC9epuOdT1LVE+gtfk0QD74eA6qJ6nfk9A4UeS8alVgrFkL+2Ww7ZcWzgcFN90KuXkLJ9n6iXKEmFIGPItm4iBwdB04t89/1O/w1cDnyilFU='
    ));

    $json_content = curl_exec($ch);
    curl_close($ch);
    $imagefile = fopen($imageId.".jpeg", "w+") or die("Unable to open file!"); //取得圖片
    fwrite($imagefile, $json_content); 
    fclose($imagefile); //將圖片存在自己server上
			
    $header[] = "Content-Type: application/json";
    $post_data = array (
        "requests" => array (
            array (
                "image" => array (
                    "source" => array (
                        "imageUri" => "https://159.65.4.103/cht20190214/kira/".$imageId.".jpeg"
                    )
                ),
                "features" => array (
                    array (
                        "type" => "PRODUCT_SEARCH"
                    )
                ),
		"imageContext" => array (
                    "productSearchParams" => array (
                        "productSet" => "projects/visual-search-176510/locations/us-west1/products/",
			"productCategories" => array (
				"homegoods"
			),
			"filter" => "style = womens"
                    )
                )  
            )
        )
    );



"imageContext": {
        "productSearchParams": {
          "productSet": "projects/project-id/locations/location-id/productSets/product-set-id",
          "productCategories": [
               "homegoods"
             ],
        "filter": "style = womens"
        }

    fwrite($myfile, "\xEF\xBB\xBF".json_encode($post_data));
    $ch = curl_init('https://alpha-vision.googleapis.com/v1/images:annotate?key=AIzaSyBJH3w6aTjoIBYhCh8GiI5byZ0Z-Q88cfg');                                                                      
//https://alpha-vision.googleapis.com/v1/images:annotate
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
    $result = json_decode(curl_exec($ch));

    //$val = explode("\n",$result -> responses[0] -> productSearchResults[0] -> results[0] -> product -> name);
    $name = $result -> responses[0] -> productSearchResults[0] -> results[0] -> product -> displayName;
    fwrite($myfile, "\xEF\xBB\xBF".$name);
  
    $response = array (
        //"replyToken" => $sender_replyToken,
	"to" => "U5ac8bed58b53fa1834130d8fafcbc2bc", // mention wlyz	    
        "messages" => array (
            array (
                   "type" => "text",
                  "text" => $name            
            )
        )    
    );
  
    fwrite($myfile, "\xEF\xBB\xBF".json_encode($response)); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
    $header[] = "Content-Type: application/json";
    $header[] = "Authorization: Bearer 58tGd62pBsrYGL7qy1kx+LJCG8W/SheF6lG0CsIJuP0Rerj/i6md02bTC7ipkRtCC9epuOdT1LVE+gtfk0QD74eA6qJ6nfk9A4UeS8alVgrFkL+2Ww7ZcWzgcFN90KuXkLJ9n6iXKEmFIGPItm4iBwdB04t89/1O/w1cDnyilFU=";
    //$ch = curl_init("https://api.line.me/v2/bot/message/reply");
    $ch = curl_init("https://api.line.me/v2/bot/message/push");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
    $result = curl_exec($ch);
    curl_close($ch);
?>
