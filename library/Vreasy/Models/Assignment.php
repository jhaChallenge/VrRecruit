<?php

namespace Vreasy\Models;

use Vreasy\Query\Builder;

class Assignment extends Base
{
	// Protected attributes should match table columns
    protected $id;
    protected $task_id;
    protected $message_id;
    protected $created_at;
    protected $response_at;
    protected $response;

    public function __construct()
    {
        // Validation is done run by Valitron library
        $this->validates(
            'required',
            ['message_id', 'task_id']
        );
        $this->validates(
            'date',
            ['created_at', 'response_at']
        );
        $this->validates(
            'integer',
            ['id', 'task_id']
        );
    }

    public function save()
    {
        // Base class forward all static:: method calls directly to Zend_Db
        if ($this->isValid()) {
            if ($this->isNew()) {
                $this->created_at = gmdate(DATE_FORMAT);
                static::insert('assignments', $this->attributesForDb());
                $this->id = static::lastInsertId();
            } else {
            	$this->response_at = gmdate(DATE_FORMAT);
                static::update(
                    'assignments',
                    $this->attributesForDb(),
                    ['id = ?' => $this->id]
                );
            }
            return $this->id;
        }
    }

    public static function findOrInit($id)
    {
        $assignment = new Assignment();
        if ($assignmentsFound = static::where(['id' => (int)$id])) {
            $assignment = array_pop($assignmentsFound);
        }
        return $assignment;
    }

    public static function findByMessageId($message_id)
    {
        $assignment = new Assignment();
        if ($assignmentsFound = static::where(['message_id' => $message_id])) {
            $assignment = array_pop($assignmentsFound);
        }
        return $assignment;
    }

    public static function findByTaskId($task_id)
    {
        $assignment = new Assignment();
        if ($assignmentsFound = static::where(['task_id' => $task_id])) {
            $assignment = array_pop($assignmentsFound);
        }
        return $assignment;
    }

    public static function where($params, $opts = [])
    {
        // Default options' values
        $limit = 0;
        $start = 0;
        $orderBy = ['created_at'];
        $orderDirection = ['desc'];
        extract($opts, EXTR_IF_EXISTS);
        $orderBy = array_flatten([$orderBy]);
        $orderDirection = array_flatten([$orderDirection]);

        // Return value
        $collection = [];
        // Build the query
        list($where, $values) = Builder::expandWhere(
            $params,
            ['wildcard' => true, 'prefix' => 't.']);

        // Select header
        $select = "SELECT t.* FROM assignments AS t";

        // Build order by
        foreach ($orderBy as $i => $value) {
            $dir = isset($orderDirection[$i]) ? $orderDirection[$i] : 'ASC';
            $orderBy[$i] = "`$value` $dir";
        }
        $orderBy = implode(', ', $orderBy);

        $limitClause = '';
        if ($limit) {
            $limitClause = "LIMIT $start, $limit";
        }

        $orderByClause = '';
        if ($orderBy) {
            $orderByClause = "ORDER BY $orderBy";
        }
        if ($where) {
            $where = "WHERE $where";
        }

        $sql = "$select $where $orderByClause $limitClause";
        if ($res = static::fetchAll($sql, $values)) {
            foreach ($res as $row) {
                $collection[] = static::instanceWith($row);
            }
        }
        return $collection;
    }
}