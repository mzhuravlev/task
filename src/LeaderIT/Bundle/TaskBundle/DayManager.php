<?php

namespace LeaderIT\Bundle\TaskBundle;


class DayManager {

    private $tasks;
    private $data;

    public function loadTasks($tasks) {
        $this->tasks = $tasks;
    }

    public function processTasks() {
        foreach($this->tasks as $key => $task) {
            $this->data[] = $task->serialize();
        }

        if(count($this->data) == 0)
            $this->data = array();
    }

    public function getData() {
        return $this->data;
    }
}