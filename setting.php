<?php

class Setting {
	public function getChannelAccessToken(){
		$channelAccessToken = "LLJRo6eJnT1MzCnpMfOxs6w/MlCl0T/RrC9oU5UD7xYO/fhfd9mqsL2NZYWQREzDs0CmytAS3OiZFcQVgrH+RP2R9Eqs+SQfq0JqSJw1FcVrAYsEbPwISAbBYzcXXwdWVpBtu37LXlMkJgpTyAn/3gdB04t89/1O/w1cDnyilFU=";
		return $channelAccessToken;
	}
	public function getChannelSecret(){
		$channelSecret = "6ca75364cac0b2eb3600c3063f5b269b";
		return $channelSecret;
	}
	public function getApiReply(){
		$api = "https://api.line.me/v2/bot/message/reply";
		return $api;
	}
	public function getApiPush(){
		$api = "https://api.line.me/v2/bot/message/push";
		return $api;
	}
}