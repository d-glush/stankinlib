<?php

namespace Packages\QueryBuilder;

class QueryBuilder {

    public function buildSelect(string $tableName, string $string, string $string1): string
    {

    }

    public function buildInsert(string $table, array $data): string
    {
        $queryTemplate = '%1$s%2$s %3$s';
        $startTemplate = 'INSERT INTO %1$s';
        $insertingValuesTemplate = '(%1$s)';
        $valuesTemplate = 'VALUES (%1$s)';

        $start = sprintf($startTemplate, $table);

        $keys = implode(',', array_keys($data));
        $insertingValues = sprintf($insertingValuesTemplate, $keys);

        foreach ($data as $key => $datum) {
            if (is_string($datum)) {
                $data[$key] = "'$datum'";
            }
        }
        $values = implode(',', array_values($data));
        $values = sprintf($valuesTemplate, $values);

        return sprintf($queryTemplate, $start, $insertingValues, $values);
    }
}