<?php

namespace App\Core;

use App\Core\DB;
use \PDO;

/**
 * The base Model class for interacting with the database.
 */
abstract class Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected static $table;

    /**
     * The primary key for the table.
     *
     * @var string
     */
    protected static $primaryKey = 'id';

    /**
     * The where clauses for the query.
     *
     * @var array
     */
    private static $wheres = [];

    /**
     * The order by clauses for the query.
     *
     * @var array
     */
    private static $orderBys = [];

    /**
     * The column to group by.
     *
     * @var string
     */
    private static $groupBy;

    /**
     * The maximum number of records to return.
     *
     * @var int
     */
    private static $limit;

    /**
     * The number of records to skip.
     *
     * @var int
     */
    private static $offset;

    /**
     * Get all records from the table.
     *
     * @return array
     */
    public static function all()
    {
        $query = "SELECT * FROM " . static::$table;
        return DB::query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a record from the table by id.
     *
     * @param int $id The id of the record to get.
     *
     * @return array
     */
    public static function find($id)
    {
        $query = "SELECT * FROM " . static::$table . " WHERE id = ?";
        return DB::query($query, [self::$primaryKey => $id])->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Paginate the results.
     *
     * @param int $perPage The number of records to display per page.
     * @param int $page    The current page number.
     *
     * @return array
     */
    public static function paginate($perPage = 10, $page = 1)
    {
        // Calculate the offset for the current page.
        $offset = ($page - 1) * $perPage;

        // Build the query.
        $query = "SELECT * FROM " . static::$table . " LIMIT {$perPage} OFFSET {$offset}";

        // Get the results.
        $results = DB::query($query)->fetchAll(PDO::FETCH_ASSOC);

        // Get the total number of records.
        $countQuery = "SELECT COUNT(*) as total FROM " . static::$table;
        $total = DB::query($countQuery)->fetch(PDO::FETCH_ASSOC)['total'];

        // Calculate the last page number.
        $lastPage = ceil($total / $perPage);

        return [
            'data' => $results,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => $lastPage,
        ];
    }

    /**
     * Add a where clause to the query.
     *
     * @param string $column   The column to search in.
     * @param string $operator The operator to use in the where clause.
     * @param mixed  $value    The value to search for.
     *
     * @return $this
     */
    public function where($column, $operator, $value)
    {
        $this->wheres[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];

        return $this;
    }
    /**
     * Adds an ORDER BY clause to the query.
     *
     * @param string $column    The column to order by.
     * @param string $direction The direction to order by ('ASC' or 'DESC').
     *
     * @return $this
     */
    public function orderBy($column, $direction)
    {
        $this->orderBys[] = [
            'column' => $column,
            'direction' => $direction
        ];

        return $this;
    }

    /**
     * Adds a GROUP BY clause to the query.
     *
     * @param string $column The column to group by.
     *
     * @return $this
     */
    public function groupBy($column)
    {
        $this->groupBy = $column;

        return $this;
    }

    /**
     * Sets the maximum number of rows to return.
     *
     * @param int $limit The maximum number of rows to return.
     *
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Sets the number of rows to skip before returning results.
     *
     * @param int $offset The number of rows to skip.
     *
     * @return $this
     */
    public function offset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Executes the query and returns the results.
     *
     * @return array The results of the query.
     */
    public function get()
    {
        // Build the query string
        $query = "SELECT * FROM " . $this->table;

        // Add WHERE clauses to the query
        if (!empty($this->wheres)) {
            $query .= " WHERE ";
            foreach ($this->wheres as $index => $where) {
                if ($index != 0) {
                    $query .= " AND ";
                }
                $query .= $where['column'] . " " . $where['operator'] . " :" . $where['column'];
            }
        }

        // Add ORDER BY clauses to the query
        if (!empty($this->orderBys)) {
            $query .= " ORDER BY ";
            foreach ($this->orderBys as $index => $orderBy) {
                if ($index != 0) {
                    $query .= ", ";
                }
                $query .= $orderBy['column'] . " " . $orderBy['direction'];
            }
        }

        // Add GROUP BY clauses to the query
        if (!empty($this->groupBy)) {
            $query .= " GROUP BY " . $this->groupBy;
        }

        // Add LIMIT clause to the query
        if (!empty($this->limit)) {
            $query .= " LIMIT " . $this->limit;
        }

        // Add OFFSET clause to the query
        if (!empty($this->offset)) {
            $query .= " OFFSET " . $this->offset;
        }

        // Execute the query
        $stmt = DB::query($query, $this->getWhereParameters());

        // Return the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Returns an associative array of parameters for the WHERE clauses.
     *
     * @return array An associative array of parameters for the WHERE clauses.
     */
    protected function getWhereParameters()
    {
        $parameters = [];
        foreach ($this->wheres as $where) {
            $parameters[$where['column']] = $where['value'];
        }

        return $parameters;
    }
}
