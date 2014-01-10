<?php
class ZookeeperDemo extends Zookeeper {
	public function watcher($i,$type,$key) {
		echo "Insider Watcher\n";
		$this->get('/test',array($this,'watcher'));
	}
}
$zoo=new ZookeeperDemo('127.0.0.1:2181');
$zoo->get('/test',array($zoo,'watcher'));
while(true) {
	echo '.';
	sleep(2);
}
?>
