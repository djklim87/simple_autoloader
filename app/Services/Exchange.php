<?php

namespace App\Services;

class Exchange
{
    private $curl;
    private $saver;
    private $result;

    public function __construct(Curl $curl, Saver $saver)
    {
        $this->curl  = $curl;
        $this->saver = $saver;
    }

    public function run()
    {
        $today        = date('d.m.Y');
        $this->result = $this->curl->get(EXCHANGE_URL . $today);
        $this->saveResult();
    }

    private function saveResult()
    {
        if ($this->validate()) {
            $this->saver->save($this->result['result']);
        }
    }

    private function validate()
    {
        if ($this->result['status'] == Curl::STATUS_ERROR) {
            echo "Данные не получены. Ошибка: " . $this->curl->getError();

            return false;
        }

        return true;
    }
}