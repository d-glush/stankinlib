<?php

namespace Packages\DTO;

class DTO
{
    public function getArrayData(): array
    {
        $data = get_object_vars($this);
        foreach ($data as $key => $datum) {
            if (is_null($datum)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}