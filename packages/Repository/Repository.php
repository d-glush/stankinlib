<?php

namespace Packages\Repository;

class Repository
{
    protected function makeIn(array $data): string {
        $inTemplate = 'IN (%s)';
        $formattedData = [];
        foreach ($data as $datum) {
            if (is_string($datum)) {
                $datum = "'$datum'";
            }
            $formattedData[] = $datum;
        }
        return sprintf($inTemplate, implode(',', $formattedData));
    }
}