<?php

/**
 * 转换SQL关键字
 *
 * @param unknown_type $string
 * @return unknown
 */
function strip_sql($string) {
	$pattern_arr = array(
		"/\bunion\b/i",
		"/\bselect\b/i",
		"/\bupdate\b/i",
		"/\bdelete\b/i",
		"/\boutfile\b/i",
		"/\bor\b/i",
		"/\bchar\b/i",
		"/\bconcat\b/i",
		"/\btruncate\b/i",
		"/\bdrop\b/i",
		"/\binsert\b/i",
		"/\brevoke\b/i",
		"/\bgrant\b/i",
		"/\breplace\b/i",
		"/\balert\b/i",
		"/\brename\b/i",
		"/\bcreate\b/i",
		"/\bmaster\b/i",
		"/\bdeclare\b/i",
		"/\bsource\b/i",
		"/\bload\b/i",
		"/\bcall\b/i",
		"/\bexec\b/i",
		"/\bdelimiter\b/i",
	);
	$replace_arr = array(
		'ｕｎｉｏｎ',
		'ｓｅｌｅｃｔ',
		'ｕｐｄａｔｅ',
		'ｄｅｌｅｔｅ',
		'ｏｕｔｆｉｌｅ',
		'ｏｒ',
		'ｃｈａｒ',
		'ｃｏｎｃａｔ',
		'ｔｒｕｎｃａｔｅ',
		'ｄｒｏｐ',
		'ｉｎｓｅｒｔ',
		'ｒｅｖｏｋｅ',
		'ｇｒａｎｔ',
		'ｒｅｐｌａｃｅ',
		'ａｌｅｒｔ',
		'ｒｅｎａｍｅ',
		'ｃｒｅａｔｅ',
		'ｍａｓｔｅｒ',
		'ｄｅｃｌａｒｅ',
		'ｓｏｕｒｃｅ',
		'ｌｏａｄ',
		'ｃａｌｌ',
		'ｅｘｅｃ',
		'ｄｅｌｉｍｉｔｅｒ',
	);

	return is_array($string) ? array_map('strip_sql', $string) : preg_replace($pattern_arr, $replace_arr, $string);
}

/**
 * @param $arr
 * @param $key_name
 * @return array
 * 将数据库中查出的列表以指定的 id 作为数组的键名
 */
function convert_arr_key($arr, $key_name)
{
	$arr2 = array();
	foreach($arr as $key => $val){
		$arr2[$val[$key_name]] = $val;
	}
	return $arr2;
}


/**
 * 获取数组中的某一列
 * @param type $arr 数组
 * @param type $key_name  列名
 * @return type  返回那一列的数组
 */
function get_arr_column($arr, $key_name)
{
	$arr2 = array();
	foreach($arr as $key => $val){
		$arr2[] = $val[$key_name];
	}
	return $arr2;
}


/**
 * 获取url 中的各个参数  类似于 pay_code=alipay&bank_code=ICBC-DEBIT
 * @param type $str
 * @return type
 */
function parse_url_param($str){
	$data = array();
	$parameter = explode('&',end(explode('?',$str)));
	foreach($parameter as $val){
		$tmp = explode('=',$val);
		$data[$tmp[0]] = $tmp[1];
	}
	return $data;
}


/**
 * 二维数组排序
 * @param $arr
 * @param $keys
 * @param string $type
 * @return array
 */
function array_sort($arr, $keys, $type = 'desc')
{
	$key_value = $new_array = array();
	foreach ($arr as $k => $v) {
		$key_value[$k] = $v[$keys];
	}
	if ($type == 'asc') {
		asort($key_value);
	} else {
		arsort($key_value);
	}
	reset($key_value);
	foreach ($key_value as $k => $v) {
		$new_array[$k] = $arr[$k];
	}
	return $new_array;
}


/**
 * 多维数组转化为一维数组
 * @param 多维数组
 * @return array 一维数组
 */
function array_multi2single($array)
{
	static $result_array = array();
	foreach ($array as $value) {
		if (is_array($value)) {
			array_multi2single($value);
		} else
			$result_array [] = $value;
	}
	return $result_array;
}

/**
 * 友好时间显示
 * @param $time
 * @return bool|string
 */
function friend_date($time)
{
	if (!$time)
		return false;
	$fdate = '';
	$d = time() - intval($time);
	$ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
	$md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
	$byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
	$yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
	$dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
	$td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
	$atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
	if ($d == 0) {
		$fdate = '刚刚';
	} else {
		switch ($d) {
			case $d < $atd:
				$fdate = date('Y年m月d日', $time);
				break;
			case $d < $td:
				$fdate = '后天' . date('H:i', $time);
				break;
			case $d < 0:
				$fdate = '明天' . date('H:i', $time);
				break;
			case $d < 60:
				$fdate = $d . '秒前';
				break;
			case $d < 3600:
				$fdate = floor($d / 60) . '分钟前';
				break;
			case $d < $dd:
				$fdate = floor($d / 3600) . '小时前';
				break;
			case $d < $yd:
				$fdate = '昨天' . date('H:i', $time);
				break;
			case $d < $byd:
				$fdate = '前天' . date('H:i', $time);
				break;
			case $d < $md:
				$fdate = date('m月d日 H:i', $time);
				break;
			case $d < $ld:
				$fdate = date('m月d日', $time);
				break;
			default:
				$fdate = date('Y年m月d日', $time);
				break;
		}
	}
	return $fdate;
}


/**
 * 返回状态和信息
 * @param $status
 * @param $info
 * @return array
 */
function arrayRes($status, $info, $url = "")
{
	return array("status" => $status, "info" => $info, "url" => $url);
}

/**
 * @param $arr
 * @param $key_name
 * @param $key_name2
 * @return array
 * 将数据库中查出的列表以指定的 id 作为数组的键名 数组指定列为元素 的一个数组
 */
function get_id_val($arr, $key_name,$key_name2)
{
	$arr2 = array();
	foreach($arr as $key => $val){
		$arr2[$val[$key_name]] = $val[$key_name2];
	}
	return $arr2;
}

/**
 *  自定义函数 判断 用户选择 从下面的列表中选择 可选值列表：不能为空
 * @param type $attr_values
 * @return boolean
 */
function checkAttrValues($attr_values)
{
	if((trim($attr_values) == '') && ($_POST['attr_input_type'] == '1'))
		return false;
	else
		return true;
}

// 定义一个函数getIP() 客户端IP，
function getIP(){
	if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
	else $ip = "Unknow";

	if(preg_match('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1 -9]?\d))))$/', $ip))
		return $ip;
	else
		return '';
}
// 服务器端IP
function serverIP(){
	return gethostbyname($_SERVER["SERVER_NAME"]);
}


/**
 * 自定义函数递归的复制带有多级子目录的目录
 * 递归复制文件夹
 * @param type $src 原目录
 * @param type $dst 复制到的目录
 */
//参数说明：
//自定义函数递归的复制带有多级子目录的目录
function recurse_copy($src, $dst)
{
	$now = time();
	$dir = opendir($src);
	@mkdir($dst);
	while (false !== $file = readdir($dir)) {
		if (($file != '.') && ($file != '..')) {
			if (is_dir($src . '/' . $file)) {
				recurse_copy($src . '/' . $file, $dst . '/' . $file);
			}
			else {
				if (file_exists($dst . DIRECTORY_SEPARATOR . $file)) {
					if (!is_writeable($dst . DIRECTORY_SEPARATOR . $file)) {
						exit($dst . DIRECTORY_SEPARATOR . $file . '不可写');
					}
					@unlink($dst . DIRECTORY_SEPARATOR . $file);
				}
				if (file_exists($dst . DIRECTORY_SEPARATOR . $file)) {
					@unlink($dst . DIRECTORY_SEPARATOR . $file);
				}
				$copyrt = copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
				if (!$copyrt) {
					echo 'copy ' . $dst . DIRECTORY_SEPARATOR . $file . ' failed<br>';
				}
			}
		}
	}
	closedir($dir);
}

// 递归删除文件夹
function delFile($dir,$file_type='') {
	if(is_dir($dir)){
		$files = scandir($dir);
		//打开目录 //列出目录中的所有文件并去掉 . 和 ..
		foreach($files as $filename){
			if($filename!='.' && $filename!='..'){
				if(!is_dir($dir.'/'.$filename)){
					if(empty($file_type)){
						unlink($dir.'/'.$filename);
					}else{
						if(is_array($file_type)){
							//正则匹配指定文件
							if(preg_match($file_type[0],$filename)){
								unlink($dir.'/'.$filename);
							}
						}else{
							//指定包含某些字符串的文件
							if(false!=stristr($filename,$file_type)){
								unlink($dir.'/'.$filename);
							}
						}
					}
				}else{
					delFile($dir.'/'.$filename);
					rmdir($dir.'/'.$filename);
				}
			}
		}
	}else{
		if(file_exists($dir)) unlink($dir);
	}
}


/**
 * 多个数组的笛卡尔积
 *
 * @param unknown_type $data
 */
function combineDika() {
	$data = func_get_args();
	$data = current($data);
	$cnt = count($data);
	$result = array();
	$arr1 = array_shift($data);
	foreach($arr1 as $key=>$item)
	{
		$result[] = array($item);
	}

	foreach($data as $key=>$item)
	{
		$result = combineArray($result,$item);
	}
	return $result;
}


/**
 * 两个数组的笛卡尔积
 * @param unknown_type $arr1
 * @param unknown_type $arr2
 */
function combineArray($arr1,$arr2) {
	$result = array();
	foreach ($arr1 as $item1)
	{
		foreach ($arr2 as $item2)
		{
			$temp = $item1;
			$temp[] = $item2;
			$result[] = $temp;
		}
	}
	return $result;
}
/**
 * 将二维数组以元素的某个值作为键 并归类数组
 * array( array('name'=>'aa','type'=>'pay'), array('name'=>'cc','type'=>'pay') )
 * array('pay'=>array( array('name'=>'aa','type'=>'pay') , array('name'=>'cc','type'=>'pay') ))
 * @param $arr 数组
 * @param $key 分组值的key
 * @return array
 */
function group_same_key($arr,$key){
	$new_arr = array();
	foreach($arr as $k=>$v ){
		$new_arr[$v[$key]][] = $v;
	}
	return $new_arr;
}

/**
 * 获取随机字符串
 * @param int $randLength  长度
 * @param int $addtime  是否加入当前时间戳
 * @param int $includenumber   是否包含数字
 * @return string
 */
function get_rand_str($randLength=6,$addtime=1,$includenumber=0){
	if ($includenumber){
		$chars='abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
	}else {
		$chars='abcdefghijklmnopqrstuvwxyz';
	}
	$len=strlen($chars);
	$randStr='';
	for ($i=0;$i<$randLength;$i++){
		$randStr.=$chars[rand(0,$len-1)];
	}
	$tokenvalue=$randStr;
	if ($addtime){
		$tokenvalue=$randStr.time();
	}
	return $tokenvalue;
}

/**
 * CURL请求
 * @param $url 请求url地址
 * @param $method 请求方法 get post
 * @param null $postfields post数据数组
 * @param array $headers 请求header信息
 * @param bool|false $debug  调试开启 默认false
 * @return mixed
 */
function httpRequest($url, $method, $postfields = null, $headers = array(), $debug = false) {
	$method = strtoupper($method);
	$ci = curl_init();
	/* Curl settings */
	curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
	curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
	curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
	curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
	curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
	switch ($method) {
		case "POST":
			curl_setopt($ci, CURLOPT_POST, true);
			if (!empty($postfields)) {
				$tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
				curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
			}
			break;
		default:
			curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
			break;
	}
	$ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
	curl_setopt($ci, CURLOPT_URL, $url);
	if($ssl){
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
	}
	//curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
	curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
	curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ci, CURLINFO_HEADER_OUT, true);
	/*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
	$response = curl_exec($ci);
	$requestinfo = curl_getinfo($ci);
	$http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
	if ($debug) {
		echo "=====post data======\r\n";
		var_dump($postfields);
		echo "=====info===== \r\n";
		print_r($requestinfo);
		echo "=====response=====\r\n";
		print_r($response);
	}
	curl_close($ci);
	return $response;
	//return array($http_code, $response,$requestinfo);
}

/**
 * 过滤数组元素前后空格 (支持多维数组)
 * @param $array 要过滤的数组
 * @return array|string
 */
function trim_array_element($array){
	if(!is_array($array))
		return trim($array);
	return array_map('trim_array_element',$array);
}

/**
 * 检查手机号码格式
 * @param $mobile 手机号码
 */
function check_mobile($mobile){
	if(preg_match('/1[34578]\d{9}$/',$mobile))
		return true;
	return false;
}

/**
 * 检查邮箱地址格式
 * @param $email 邮箱地址
 */
function check_email($email){
	if(filter_var($email,FILTER_VALIDATE_EMAIL))
		return true;
	return false;
}


/**
 *   实现中文字串截取无乱码的方法
 */
function getSubstr($string, $start, $length) {
	if(mb_strlen($string,'utf-8')>$length){
		$str = mb_substr($string, $start, $length,'utf-8');
		return $str.'...';
	}else{
		return $string;
	}
}


//php获取中文字符拼音首字母
function getFirstCharter($str){
	if(empty($str))
	{
		return '';
	}
	$fchar=ord($str{0});
	if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
	$s1=iconv('UTF-8','gb2312',$str);
	$s2=iconv('gb2312','UTF-8',$s1);
	$s=$s2==$str?$s1:$str;
	$asc=ord($s{0})*256+ord($s{1})-65536;
	if($asc>=-20319&&$asc<=-20284) return 'A';
	if($asc>=-20283&&$asc<=-19776) return 'B';
	if($asc>=-19775&&$asc<=-19219) return 'C';
	if($asc>=-19218&&$asc<=-18711) return 'D';
	if($asc>=-18710&&$asc<=-18527) return 'E';
	if($asc>=-18526&&$asc<=-18240) return 'F';
	if($asc>=-18239&&$asc<=-17923) return 'G';
	if($asc>=-17922&&$asc<=-17418) return 'H';
	if($asc>=-17417&&$asc<=-16475) return 'J';
	if($asc>=-16474&&$asc<=-16213) return 'K';
	if($asc>=-16212&&$asc<=-15641) return 'L';
	if($asc>=-15640&&$asc<=-15166) return 'M';
	if($asc>=-15165&&$asc<=-14923) return 'N';
	if($asc>=-14922&&$asc<=-14915) return 'O';
	if($asc>=-14914&&$asc<=-14631) return 'P';
	if($asc>=-14630&&$asc<=-14150) return 'Q';
	if($asc>=-14149&&$asc<=-14091) return 'R';
	if($asc>=-14090&&$asc<=-13319) return 'S';
	if($asc>=-13318&&$asc<=-12839) return 'T';
	if($asc>=-12838&&$asc<=-12557) return 'W';
	if($asc>=-12556&&$asc<=-11848) return 'X';
	if($asc>=-11847&&$asc<=-11056) return 'Y';
	if($asc>=-11055&&$asc<=-10247) return 'Z';
	return null;
}
//调试使用
function dd($var){
	if($var){
		echo "<pre>";
		print_r($var);
	}else{
		echo "变量不存在";
	}
	die;
}

/**
 * 获取用户信息
 * @param $user_id 用户id
 * @return mixed
 */
function getUserInfo($user_id){
	$userInfo = M('users')->where(array('user_id'=>$user_id))->find();
	return $userInfo;
}

function getUserAvatar($user_id){
	$avatar = M('users')->where(array('user_id'=>$user_id))->field('head_pic')->find();
	if($avatar){
		return $avatar;
	}else{
		return '/Public/images/user_defualt.jpg';
	}
}

/**
 * 获取某个商品分类的 儿子 孙子  重子重孙 的 id
 * @param type $cat_id
 */
function getCatGrandson ($cat_id)
{
	$GLOBALS['catGrandson'] = array();
	$GLOBALS['category_id_arr'] = array();
	// 先把自己的id 保存起来
	$GLOBALS['catGrandson'][] = $cat_id;
	// 把整张表找出来
	$GLOBALS['category_id_arr'] = M('GoodsCategory')->getField('id,parent_id');
	// 先把所有儿子找出来
	$son_id_arr = M('GoodsCategory')->where("parent_id = $cat_id")->getField('id',true);
	foreach($son_id_arr as $k => $v)
	{
		getCatGrandson2($v);
	}
	return $GLOBALS['catGrandson'];
}

/**
 * 获取某个文章分类的 儿子 孙子  重子重孙 的 id
 * @param type $cat_id
 */
function getArticleCatGrandson ($cat_id)
{
	$GLOBALS['ArticleCatGrandson'] = array();
	$GLOBALS['cat_id_arr'] = array();
	// 先把自己的id 保存起来
	$GLOBALS['ArticleCatGrandson'][] = $cat_id;
	// 把整张表找出来
	$GLOBALS['cat_id_arr'] = M('ArticleCat')->getField('cat_id,parent_id');
	// 先把所有儿子找出来
	$son_id_arr = M('ArticleCat')->where("parent_id = $cat_id")->getField('cat_id',true);
	foreach($son_id_arr as $k => $v)
	{
		getArticleCatGrandson2($v);
	}
	return $GLOBALS['ArticleCatGrandson'];
}

/**
 * 递归调用找到 重子重孙
 * @param type $cat_id
 */
function getCatGrandson2($cat_id)
{
	$GLOBALS['catGrandson'][] = $cat_id;
	foreach($GLOBALS['category_id_arr'] as $k => $v)
	{
		// 找到孙子
		if($v == $cat_id)
		{
			getCatGrandson2($k); // 继续找孙子
		}
	}
}


/**
 * 递归调用找到 重子重孙
 * @param type $cat_id
 */
function getArticleCatGrandson2($cat_id)
{
	$GLOBALS['ArticleCatGrandson'][] = $cat_id;
	foreach($GLOBALS['cat_id_arr'] as $k => $v)
	{
		// 找到孙子
		if($v == $cat_id)
		{
			getArticleCatGrandson2($k); // 继续找孙子
		}
	}
}

/**
 * 获取缓存或者更新缓存
 * @param string $config_key 缓存文件名称
 * @param array $data 缓存数据  array('k1'=>'v1','k2'=>'v3')
 * @return array or string or bool
 */
function tpCache($config_key,$data = array()){
	$param = explode('.', $config_key);
	if(empty($data)){
		//如$config_key=shop_info则获取网站信息数组
		//如$config_key=shop_info.logo则获取网站logo字符串
		$config = F($param[0],'',TEMP_PATH);//直接获取缓存文件
		if(empty($config)){
			//缓存文件不存在就读取数据库
			$res = D('config')->where("inc_type='$param[0]'")->select();
			if($res){
				foreach($res as $k=>$val){
					$config[$val['name']] = $val['value'];
				}
				F($param[0],$config,TEMP_PATH);
			}
		}
		if(count($param)>1){
			return $config[$param[1]];
		}else{
			return $config;
		}
	}else{
		//更新缓存
		$result =  D('config')->where("inc_type='$param[0]'")->select();
		if($result){
			foreach($result as $val){
				$temp[$val['name']] = $val['value'];
			}
			foreach ($data as $k=>$v){
				$newArr = array('name'=>$k,'value'=>trim($v),'inc_type'=>$param[0]);
				if(!isset($temp[$k])){
					M('config')->add($newArr);//新key数据插入数据库
				}else{
					if($v!=$temp[$k])
						M('config')->where("name='$k'")->save($newArr);//缓存key存在且值有变更新此项
				}
			}
			//更新后的数据库记录
			$newRes = D('config')->where("inc_type='$param[0]'")->select();
			foreach ($newRes as $rs){
				$newData[$rs['name']] = $rs['value'];
			}
		}else{
			foreach($data as $k=>$v){
				$newArr[] = array('name'=>$k,'value'=>trim($v),'inc_type'=>$param[0]);
			}
			M('config')->addAll($newArr);
			$newData = $data;
		}
		return F($param[0],$newData,TEMP_PATH);
	}
}

/**
 * 查看某个用户购物车中商品的数量
 * @param type $user_id
 * @param type $session_id
 * @return type 购买数量
 */
function cart_goods_num($user_id = 0,$session_id = '')
{
	$where = " session_id = '$session_id' ";
	$user_id && $where .= " or user_id = $user_id ";
	// 查找购物车数量
	$cart_count =  M('Cart')->where($where)->sum('goods_num');
	$cart_count = $cart_count ? $cart_count : 0;
	return $cart_count;
}


/**
 * 获取商品库存
 * @param type $goods_id 商品id
 * @param type $key  库存 key
 */
function getGoodNum($goods_id,$key)
{
	if(!empty($key))
		return  M("SpecGoodsPrice")->where("goods_id = $goods_id and `key` = '$key'")->getField('store_count');
	else
		return  M("Goods")->where("goods_id = $goods_id")->getField('store_count');
}

/*
 * 获取用户地址列表
 */
function get_user_address_list($user_id){
	$lists = M('user_address')->where(array('user_id'=>$user_id))->select();
	return $lists;
}

/*
 * 获取地区列表
 */
function get_region_list(){
	//获取地址列表 缓存读取
	if(!S('region_list')){
		$region_list = M('region')->select();
		$region_list = convert_arr_key($region_list,'id');
		S('region_list',$region_list);
	}

	return $region_list ? $region_list : S('region_list');
}


/*
 * 获取指定地址信息
 */
function get_user_address_info($user_id,$address_id){
	$data = M('user_address')->where(array('user_id'=>$user_id,'address_id'=>$address_id))->find();
	return $data;
}
/*
 * 获取用户默认收货地址
 */
function get_user_default_address($user_id){
	$data = M('user_address')->where(array('user_id'=>$user_id,'is_default'=>1))->find();
	return $data;
}


/**
 * 计算订单金额
 * @param type $user_id  用户id
 * @param type $order_goods  购买的商品
 */

function calculate_price($user_id=0,$order_goods)
{
	$cartLogic = new \Home\Logic\CartLogic();
	$user = M('users')->where("user_id = $user_id")->find();// 查找用户
	/** 判断商品列表不为空 **/
	if(empty($order_goods)){
		return array('status'=>-9,'msg'=>'商品列表不能为空','result'=>'');
	}
	$goods_id_arr = get_arr_column($order_goods,'goods_id'); // 获取商品id
	$goods_arr = M('goods')->where("goods_id in(".  implode(',',$goods_id_arr).")")->getField('goods_id,weight,market_price,is_free_shipping'); // 商品id 和重量对应的键值对

	foreach($order_goods as $key => $val){
		// 如果传递过来的商品列表没有定义会员价
		if(!array_key_exists('member_goods_price',$val))
		{
			$user['discount'] = $user['discount'] ? $user['discount'] : 1; // 会员折扣 不能为 0
			$order_goods[$key]['member_goods_price'] = $val['member_goods_price'] = $val['goods_price'] * $user['discount'];
		}


		$order_goods[$key]['goods_fee'] = $val['goods_num'] * $val['member_goods_price'];    // 小计
		$order_goods[$key]['store_count']  = getGoodNum($val['goods_id'],$val['spec_key']); // 最多可购买的库存数量
		if($order_goods[$key]['store_count'] <= 0){
			return array('status'=>-10,'msg'=>$order_goods[$key]['goods_name']."库存不足,请重新下单",'result'=>'');
		}

		$goods_price += $order_goods[$key]['goods_fee']; // 商品总价
		$cut_fee     += $val['goods_num'] * ($val['market_price'] - $val['member_goods_price']); // 共节约
		$anum        += $val['goods_num']; // 购买数量
	}

	$order_amount = $goods_price; // 应付金额

	$total_amount = $goods_price;
	//订单总价  应付金额  物流费  商品总价 节约金额 共多少件商品 积分  余额  优惠券
	$result = array(
		'total_amount'      => $total_amount, // 商品总价
		'order_amount'      => $order_amount, // 应付金额
		'goods_price'       => $goods_price, // 商品总价
		'cut_fee'           => $cut_fee, // 共节约多少钱
		'anum'              => $anum, // 商品总共数量
		'order_goods'       => $order_goods, // 商品列表 多加几个字段原样返回
	);
	return array('status'=>1,'msg'=>"计算价钱成功",'result'=>$result); // 返回结果状态
}


/**
 * 订单操作日志
 * 参数示例
 * @param type $order_id  订单id
 * @param type $action_note 操作备注
 * @param type $status_desc 操作状态  提交订单, 付款成功, 取消, 等待收货, 完成
 * @param type $user_id  用户id 默认为管理员
 * @return boolean
 */
function logOrder($order_id,$action_note,$status_desc,$user_id = 0)
{
	$status_desc_arr = array('提交订单', '付款成功', '取消', '等待收货', '完成','退货');
	$order = M('order')->where("order_id = $order_id")->find();
	$action_info = array(
		'order_id'        =>$order_id,
		'action_user'     =>$user_id,
		'order_status'    =>$order['order_status'],
		'shipping_status' =>$order['shipping_status'],
		'pay_status'      =>$order['pay_status'],
		'action_note'     => $action_note,
		'status_desc'     =>$status_desc,
		'log_time'        =>time(),
	);
	return M('order_action')->add($action_info);
}


/**
 * 支付完成修改订单
 * $order_sn 订单号
 */
function update_pay_status($order_sn)
{
	// 找出对应的订单
	$order = M('order')->where("order_sn = '$order_sn'")->find();
	// 修改支付状态  已支付
	$result = M('order')->where("order_sn = '$order_sn'")->save(array('pay_status'=>1,'pay_time'=>time()));

	if($result !== false){
		// 减少对应商品的库存
		minus_stock($order['order_id']);
		// 记录订单操作日志
		logOrder($order['order_id'],'订单付款成功','付款成功',$order['user_id']);
		return true;
	}else{
		return false;
	}

}

/**
 * 订单确认收货
 * @param $id   订单id
 */
function confirm_order($id,$user_id = 0){

	$where = "order_id = $id";
	$user_id && $where .= " and user_id = $user_id ";

	$order = M('order')->where($where)->find();
	if($order['order_status'] != 1)
		return array('status'=>-1,'msg'=>'该订单不能收货确认');

	$data['order_status'] = 2; // 已收货
	$data['pay_status'] = 1; // 已付款
	$data['confirm_time'] = time(); // 收货确认时间

	$row = M('order')->where(array('order_id'=>$id))->save($data);
	if(!$row)
		return array('status'=>-3,'msg'=>'操作失败');

	return array('status'=>1,'msg'=>'操作成功');
}

/**
 * 给订单数组添加属性  包括按钮显示属性 和 订单状态显示属性
 * @param type $order
 */
function set_btn_order_status($order)
{
	$order_status_arr = C('ORDER_STATUS_DESC');
	$order['order_status_code'] = $order_status_code = getOrderStatus(0, $order); // 订单状态显示给用户看的
//    print_r($order_status_code);die;
	$order['order_status_desc'] = $order_status_arr[$order_status_code];
	$orderBtnArr = orderBtn(0, $order);
	return array_merge($order,$orderBtnArr); // 订单该显示的按钮
}


/**
 * 获取订单状态的 显示按钮
 * @param type $order_id  订单id
 * @param type $order     订单数组
 * @return array()
 */
function orderBtn($order_id = 0, $order = array())
{
	if(empty($order))
		$order = M('Order')->where("order_id = $order_id")->find();
	/**
	 *  订单用户端显示按钮
	去支付     AND pay_status=0 AND order_status=0 AND pay_code ! ="cod"
	取消按钮  AND pay_status=0 AND shipping_status=0 AND order_status=0
	确认收货  AND shipping_status=1 AND order_status=0
	评价      AND order_status=1
	查看物流  if(!empty(物流单号))
	 */
	$btn_arr = array(
		'pay_btn' => 0, // 去支付按钮
		'cancel_btn' => 0, // 取消按钮
		'receive_btn' => 0, // 确认收货
		'comment_btn' => 0, // 评价按钮
		'shipping_btn' => 0, // 查看物流
		'return_btn' => 0, // 退货按钮 (联系客服)
	);


	// 货到付款
	if($order['pay_code'] == 'cod')
	{
		if(($order['order_status']==0 || $order['order_status']==1) && $order['shipping_status'] == 0) // 待发货
		{
			$btn_arr['cancel_btn'] = 1; // 取消按钮 (联系客服)
		}
		if($order['shipping_status'] == 1 && $order['order_status'] == 1) //待收货
		{
			$btn_arr['receive_btn'] = 1;  // 确认收货
			$btn_arr['return_btn'] = 1; // 退货按钮 (联系客服)
		}
	}
	// 非货到付款
	else
	{
		if($order['pay_status'] == 0 && $order['order_status'] == 0) // 待支付
		{
			$btn_arr['pay_btn'] = 1; // 去支付按钮
			$btn_arr['cancel_btn'] = 1; // 取消按钮
		}
		if($order['pay_status'] == 1 && in_array($order['order_status'],array(0,1)) && $order['shipping_status'] == 0) // 待发货
		{
			$btn_arr['return_btn'] = 1; // 退货按钮 (联系客服)
		}
		if($order['pay_status'] == 1 && $order['order_status'] == 1  && $order['shipping_status'] == 1) //待收货
		{
			$btn_arr['receive_btn'] = 1;  // 确认收货
			$btn_arr['return_btn'] = 1; // 退货按钮 (联系客服)
		}
	}
	if($order['order_status'] == 2)
	{
		$btn_arr['comment_btn'] = 1;  // 评价按钮
		$btn_arr['return_btn'] = 1; // 退货按钮 (联系客服)
	}
	if($order['shipping_status'] != 0)
	{
		$btn_arr['shipping_btn'] = 1; // 查看物流
	}
	if($order['shipping_status'] == 2 && $order['order_status'] == 1) // 部分发货
	{
		$btn_arr['return_btn'] = 1; // 退货按钮 (联系客服)
	}

	return $btn_arr;
}

/**
 * 获取订单状态的 中文描述名称
 * @param type $order_id  订单id
 * @param type $order     订单数组
 * @return string
 */
function getOrderStatus($order_id = 0, $order = array())
{
	if(empty($order))
		$order = M('Order')->where("order_id = $order_id")->find();

	if($order['pay_status'] == 0 && $order['order_status'] == 0)
		return 'WAITPAY'; //'待支付',
	if($order['pay_status'] == 1 &&  in_array($order['order_status'],array(0,1)) && $order['shipping_status'] != 1)
		return 'WAITSEND'; //'待发货',
	if(($order['shipping_status'] == 1) && ($order['order_status'] == 1))
		return 'WAITRECEIVE'; //'待收货',
	if($order['order_status'] == 2)
		return 'WAITCCOMMENT'; //'待评价',
	if($order['order_status'] == 3)
		return 'CANCEL'; //'已取消',
	if($order['order_status'] == 4)
		return 'FINISH'; //'已完成',
	if($order['order_status'] == 5)
		return 'CANCELLED'; //'已作废',
	return 'OTHER';
}

/**
 * 刷新商品库存, 如果商品有设置规格库存, 则商品总库存 等于 所有规格库存相加
 * @param type $goods_id  商品id
 */
function refresh_stock($goods_id){
	$count = M("SpecGoodsPrice")->where("goods_id = $goods_id")->count();
	if($count == 0) return false; // 没有使用规格方式 没必要更改总库存

	$store_count = M("SpecGoodsPrice")->where("goods_id = $goods_id")->sum('store_count');
	M("Goods")->where("goods_id = $goods_id")->save(array('store_count'=>$store_count)); // 更新商品的总库存
}

/**
 * 根据 order_goods 表扣除商品库存
 * @param type $order_id  订单id
 */
function minus_stock($order_id){
	$orderGoodsArr = M('OrderGoods')->where("order_id = $order_id")->select();
	foreach($orderGoodsArr as $key => $val)
	{
		// 有选择规格的商品
		if(!empty($val['spec_key']))
		{   // 先到规格表里面扣除数量 再重新刷新一个 这件商品的总数量
			M('SpecGoodsPrice')->where("goods_id = {$val['goods_id']} and `key` = '{$val['spec_key']}'")->setDec('store_count',$val['goods_num']);
			refresh_stock($val['goods_id']);
		}else{
			M('Goods')->where("goods_id = {$val['goods_id']}")->setDec('store_count',$val['goods_num']); // 直接扣除商品总数量
		}
		M('Goods')->where("goods_id = {$val['goods_id']}")->setInc('sales_sum',$val['goods_num']); // 增加商品销售量
	}
}