<?php


namespace think\queue;

use Exception;
use think\Hook;
use think\Queue;
class Worker
{
	public function pop($queue = null, $delay = 0, $sleep = 3, $maxTries = 0)
	{
		$job = $this->getNextJob($queue);
		if (!is_null($job)) {
			Hook::listen("worker_before_process", $queue);
			return $this->process($job, $maxTries, $delay);
		}
		Hook::listen("worker_before_sleep", $queue);
		$this->sleep($sleep);
		return ["job" => null, "failed" => false];
	}
	protected function getNextJob($queue)
	{
		if (is_null($queue)) {
			return Queue::pop();
		}
		foreach (explode(",", $queue) as $queue) {
			if (!is_null($job = Queue::pop($queue))) {
				return $job;
			}
		}
	}
	public function process(Job $job, $maxTries = 0, $delay = 0)
	{
		if ($maxTries > 0 && $job->attempts() > $maxTries) {
			return $this->logFailedJob($job);
		}
		try {
			$job->fire();
			return ["job" => $job, "failed" => false];
		} catch (Exception $e) {
			if (!$job->isDeleted()) {
				$job->release($delay);
			}
			throw $e;
		}
	}
	protected function logFailedJob(Job $job)
	{
		if (!$job->isDeleted()) {
			try {
				$job->delete();
				$job->failed();
			} finally {
				Hook::listen("queue_failed", $job);
			}
		}
		return ["job" => $job, "failed" => true];
	}
	public function sleep($seconds)
	{
		sleep($seconds);
	}
}