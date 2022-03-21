<?php

namespace Packages\QueryBuilder;

class QueryBuilder {

    public function buildSelect(string $table, string $columns, string $where = '', $limit = ''): string
    {
        $query = "SELECT $columns FROM $table";
        if ($where) {
            $query .= " WHERE $where";
        }
        return $query;
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

    public function buildUpdate(string $tableName, array $setArray, array $whereArray): string
    {
        $query = "UPDATE $tableName SET ";

        $setters = [];
        foreach ($setArray as $key => $item) {
            if (is_string($item)) {
                $item = "'$item'";
            }
            $setters[] = "$key = $item";
        }
        $query .= implode(', ', $setters);

        $wheres = [];
        if ($whereArray) {
            $query .= ' WHERE ';
            foreach ($whereArray as $key => $item) {
                if (is_string($item)) {
                    $item = "'$item'";
                }
                $wheres[] = "$key = $item";
            }
            $query .= implode(' AND ', $wheres);
        }

        return $query;
    }
}