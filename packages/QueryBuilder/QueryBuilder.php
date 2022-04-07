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
        $query = 'INSERT INTO `' . $table . '`';

        $insertingRows = [];
        if (isset($data[0]) && is_array($data[0])) {
            $insertingColumns = array_keys($data[0]);
            foreach ($data as $datum) {
                $insertingRows[] = array_values($datum);
            }
        } else {
            $insertingColumns = array_keys($data);
            $insertingRows = [array_values($data)];
        }


        $insertingColumnsImploded = implode(',', $insertingColumns);
        $query .= " ($insertingColumnsImploded) VALUES ";

        $implodedRows = [];
        foreach ($insertingRows as $rowData) {
            foreach ($rowData as $key => $datum) {
                if (is_string($datum)) {
                    $rowData[$key] = "'$datum'";
                }
            }
            $implodedRows[] = "(" . implode(',', array_values($rowData)) . ")";
        }
        $query .= implode(',', $implodedRows);
        return $query;
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