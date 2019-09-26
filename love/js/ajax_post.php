<?php
    header("Content-Type:text/html; charset=utf-8");
	//sleep(3);//效果演示，该句可移除;
    
	$course = cutstr_html($_POST["course"]);//获取留言
	$name = cutstr_html($_POST["name"]);//获取姓名
	$tel = cutstr_html($_POST["tel"]);//获取电话
	//$gender = cutstr_html($_POST["gender"]);//获取姓别
	//$age = cutstr_html($_POST["age"]);//获取年龄
	//$address = cutstr_html($_POST["address"]);//获取地址
	//$email = cutstr_html($_POST["email"]);//获取邮箱
	$msg = cutstr_html($_POST["msg"]);//获取留言
	$date = date('Y-m-d H:i:s',strtotime("-8 h"));//获取提交时间
	//$url = $_SERVER['HTTP_REFERER'];//获取提交来源

if(!empty($course)&&!empty($name)&&!empty($tel)){
	//echo '{"info":"'.$name.'","status":"y"}';

	//设置smtp服务器的相关信息----------------------------------------
	$smtpserver         = "smtp.exmail.qq.com"; //SMTP服务器
	$smtpserverport		= "25"; //SMTP服务器端口
	$smtpusermail		= "wuji@study88.cn"; //SMTP服务器的用户邮箱
	$smtpuser			= "wuji@study88.cn"; //SMTP服务器的用户帐号
	$smtppass			= "study88"; //SMTP服务器的用户密码
	$mailtype			= 'HTML';
	//////////////////////////----------------------------------------

	$lines=file("maillist.txt");//获取投送地址
	$subject=$name."-".$course."-婚宴";//邮件标题
	$message = "是否参加：$course<br />姓名：$name<br />电话：$tel<br />留言：$msg<br />报名时间：$date<br />来源地址：$url<br />";//邮件内容

	//包含smtp类
	require_once "smtp.class.php";

	$smtp = new smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); 
	
		foreach($lines as $line_num => $line)
		{
		$smtp->sendmail(cutstr_html($line), $smtpusermail, $subject, $message, $mailtype);
		}
		
		echo '{"info":"数据提交成功！","status":"y"}';//提交成功

		////本地保存数据，以备份
		$txt = "---------报名时间：".$date."---------"."\n是否参加：".$course."\n姓名：".$name."\n电话：".$tel."\n留言：".$msg."\n来源地址：".$url."\n\n";
		$k=fopen("jiehun_weixin_bm.db","a");
		fwrite($k,$txt);
		fclose($k);
		////保存结束
}
else{echo '{"info":"数据提交失败，请稍后再试！","status":"n"}';}

	//function cutstr_html($string, $sublen){
	function cutstr_html($string){
			$string = strip_tags($string);
			$string = preg_replace ('/\n/is', '', $string);
			$string = preg_replace ('/ |　/is', '', $string);
			$string = preg_replace ('/&nbsp;/is', '', $string);
			preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);
			//if(count($t_string[0]) - 0 > $sublen) $string = join('', array_slice($t_string[0], 0, $sublen))."…";   
			//else $string = join('', array_slice($t_string[0], 0, $sublen));
			return htmlspecialchars($string);}

?>