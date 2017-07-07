<?php
/*
从金山词霸每日一句中随机抓取一句名言，返回json数据
不使用前端JS直接抓取的原因是金山的 API 服务器不支持 https，这里相当于进行一次封装转发

带有缓存 1s

返回json: http://sentence.iciba.com/index.php?c=dailysentence&m=getdetail&title=2017-07-07
	*/


const cache_path = '../../cache/api_saying_ciba.txt';

const cache_max_age = 1; //second

const curl_ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36';


/**
 * @return string 生成一个完整的随机的请求地址，日期格式为 2017-11-04
 */
function build_request_url() {
    $begin_time = strtotime('2015-5-1');
    $end_time = time();
    $time = rand($begin_time, $end_time);
    $date_string = date('Y-m-d', $time);

    return 'http://sentence.iciba.com/index.php?c=dailysentence&m=getdetail&title='.$date_string;
}

/**
 * @param $url 请求地址
 * @return string curl 获取的内容
 */
function get_saying($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, curl_ua);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data? $data : '{}';
}


// MAIN

header('Content-Type: application/json; charset=utf-8');

if(file_exists(cache_path) && time() - filemtime(cache_path) < cache_max_age) {
    //use cache
    $f = fopen(cache_path, "r");
    echo fgets($f);
    fclose($f);
}
else {
    //don't use cache
    $saying = get_saying( build_request_url() );
    echo $saying;

    $f = fopen(cache_path, "w");
    fwrite($f, $saying);
    fclose($f);
}

?>
