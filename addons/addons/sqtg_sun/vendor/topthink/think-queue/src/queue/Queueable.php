<?php
 namespace think\queue; trait Queueable { public $queue; public $delay; public function queue($queue) { $this->queue = $queue; return $this; } public function delay($delay) { $this->delay = $delay; return $this; } }