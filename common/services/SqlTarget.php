<?php

namespace common\services;

class SqlTarget extends \yii\log\Target{

	public function export(){
		array_pop($this->messages); //去掉最后一个消息，因为这个与SQL无关，只是底层自动额外追加的一些请求和运行时相关信息
		
		$sqlList = [];
		foreach($this->messages as $message){
			$sqlList[] = $message[0];
		}
		
		$logContent = sprintf("%s [info] query: %d \nsql: %s \n\n", date('Y-m-d H:i:s'), count($sqlList), implode(PHP_EOL, $sqlList));

		$logFile = \Yii::getAlias('@runtime/logs/sql-'. date('Y-m-d') .'.log');
		file_put_contents($logFile, $logContent, FILE_APPEND);
	}
}