<?php
//$folder = "track";
$core = Core::getInstance();
$today = strtotime('00:00:00');
$tomorrow = strtotime('+1 day', $today);

function addData($table, $contentData){
    global $core;
    $content = array();
    $column = "";
    $columnKey = "";
    foreach($contentData as $key=>$data){
        $content[':'.$key] = $data;
        $column = $column."`".$key."`".",";
        $columnKey = $columnKey.":".$key.",";
    }
    $columnKey = rtrim($columnKey, ",");
    $column = rtrim($column, ",");
    $column = "(".$column.")";
    $columnKey = "(".$columnKey.")";
    $insert = "INSERT INTO ".$table." ".$column." VALUES ".$columnKey;
    $result = $core->dbh->prepare($insert);
    $result->execute($content);
    $lastId = $core->dbh->lastInsertId();
    return $lastId;
}

function updateData($table, $contentData, $column, $id){
    global $core;
    $content = array();
    $columnKey = "";
    foreach($contentData as $key=>$data){
        $content[':'.$key] = $data;
        $columnKey = $columnKey."`".$key."`"."=:".$key.",";
    }
    $columnKey = rtrim($columnKey, ",");
    $update = "UPDATE ".$table." SET ".$columnKey." WHERE ".$column."=:".$column;
    $content[':'.$column] = $id;

    $result = $core->dbh->prepare($update);
    $result->execute($content);
    $lastId = $id;
    return $lastId;
}
function updateDataMulti($table, $contentData, $whereQuery){
    global $core;
    $content = array();
    $wherequery = " WHERE ";
    $whereQ = " WHERE ";
    $columnKey = "";
    $tableData = "";
    foreach($contentData as $key=>$data){
        $content[':'.$key] = $data;
        $columnKey = $columnKey."`".$key."`"."=:".$key.",";
        $tableData = $tableData."`".$key."`"."='".$data."',";
    }

    foreach($whereQuery as $key=>$data){
        $wherequery = $wherequery." ";
        $whereQ = $whereQ." ";
        $count = 0;
        foreach($data as $key1=>$value1){
            if($count == "0"){
                $wherequery = $wherequery. "`".$key1."`=:".$key1;
                $whereQ = $whereQ. "`".$key1."`=".$value1;
            }else{
                $wherequery = $wherequery. " $key `".$key1."`=:".$key1;
                $whereQ = $whereQ. " $key `".$key1."`=".$value1;
            }
            $content[':'.$key1] = $value1;
            $count++;
        }
    }

    $columnKey = rtrim($columnKey, ",");
    $tableData = rtrim($tableData, ",");

    $updateQuery = "UPDATE ".$table." SET ".$tableData.$whereQ;

    $update = "UPDATE ".$table." SET ".$columnKey.$wherequery;
    $result = $core->dbh->prepare($update);
    $result->execute($content);

    $lastId = $id;
    return $lastId;
}

    function deleteData($query ,$array = array(), &$count ){

            global $core;
            $select = $query;
            $result = $core->dbh->prepare($select);
            $result->execute($array);
           
    }

 

function fetchData($query, $array=array(), &$count){
    global $core;
    $select = $query;
    $result = $core->dbh->prepare($select);
    $result->execute($array);
    $count = $result->rowCount();
    $dataRecord = array();
    while($record = $result->fetch(PDO::FETCH_ASSOC)){
        array_push($dataRecord, $record);
    }
    return $dataRecord;
}

function countData($query, &$totalCount, $array = array()){
    global $core;
    $select = $query;

    $result = $core->dbh->prepare($select);
    $result->execute($array);
    $totalCount = $result->rowCount();
}


function getIp(){
    switch(true){
      case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
      case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
      case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
      default : return $_SERVER['REMOTE_ADDR'];
    }
 }

 function getCategory($limit){
    $categoryArray = array();
    $query = "SELECT * FROM tbl_category where parent=0";
    $array = array();
    $count = 0;
    $data = fetchData($query, $array, $count);
    if($limit=='all'){
        return $data;
    }else{
      $categoryArray = $data[0];  
    }
    return $categoryArray;
}


function reDirect($aPath){
        $aFullPath = ROOTURLPATH.$aPath;
        header("Location: $aFullPath");
        die();
}

function forceReDirect($aPath){
         die("<script>location.href = '$aPath'</script>");

} 

function xss_clean($data)
        {
        // Fix &entity\n;
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        $data = str_replace('<!--','',$data);
        $data = str_replace('-->','',$data);
        $data = strip_tags(strtolower($data));
        $data = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data);
        $data = preg_replace('/((<[\\s\\/]*script\\b[^>]*>)([^>]*)(<\\/script>))/i', '', $data);
        do
        {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);

        // we are done...
        return $data;
        }    


function getDateFormat($my_date,$type){
     $non="";    
        if($my_date=="")
            {
            return $non;
            }
        
        if ($type==1)
            {
            $bdate=explode("-",$my_date);
            return date("M d, Y", mktime(0, 0, 0, $bdate[1], $bdate[2], $bdate[0]));
            }
        
        if($type==2)
            {
            $bdate=explode("/",$my_date);
            return date("Y-n-d", mktime(0, 0, 0, $bdate[0], $bdate[1], $bdate[2]));
            }
        
        if ($type==3)
            {
            
            $bdate=explode("-",$my_date);
            return date("n/d/Y", mktime(0, 0, 0, $bdate[1], $bdate[2], $bdate[0]));
            }
            
            return $my_date;
    }

    function mailData($to,$subject,$data){       
    $from = 'Go Dashboard@dynateam.in';
    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    // Create email headers
    $headers .= 'From: '.$from."\r\n". 'Reply-To: '.$from."\r\n" . 'X-Mailer: PHP/' . phpversion();
    mail($to,$subject,$data,$headers);
    }


    function in_array_r($needle, $haystack, $strict = false) {
            if(isset($haystack) && !empty($haystack)){
                    foreach ($haystack as $item) {
                        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                            return true;
                        }
                    }            
            }


    return false;
    }

    function create_zip($files = array(),$destination = '',$overwrite = false) {
        return true;
    }
//function just replace for print_r Written By @Sonu Yadav
function pr($value = null) {
    printf('<pre>%s</pre>', print_r($value, true));
}
