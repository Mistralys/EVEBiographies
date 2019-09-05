<?php

namespace EVEBiographies;

require_once 'DB/Collection.php';
require_once 'DB/Item.php';

class DB
{
    const ERROR_TRANSACTION_REQUIRED = 30801;

    const ERROR_UPDATE_MISSING_PRIMARY = 30802;

    protected $dataPath;

   /**
    * @var \PDO
    */
    protected $pdo;

    public function __construct()
    {
        $this->dataPath = APP_ROOT.'/storage/biographies.sqlite';

        $pdo = new \PDO('sqlite:'.$this->dataPath);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->pdo = $pdo;
    }

    public function exists()
    {
        return file_exists($this->dataPath);
    }

    public function create()
    {
        // TODO: insert structure.sql
    }

    public function insert($table, $data)
    {
        $keysList = array();
        $placeholders = array();

        foreach($data as $key => $val) {
            $keysList[] = $key;
            $placeholders[] = ':'.$key;
        }

        $query = sprintf(
            "INSERT INTO
                %s
                (
                    %s
                )
            VALUES
                (
                    %s
                )",
            $table,
            implode(', ', $keysList),
            implode(', ', $placeholders)
        );

        $stmt = $this->pdo->prepare($query);

        $stmt->execute($data);

        return $this->pdo->lastInsertId();
    }

    public function update($table, $data, $primaries=array())
    {
        $this->requireTransaction('Update table '.$table);

        $where = '1';
        $sets = array();
        $wheres = array();

        foreach($data as $key => $val) {
            $sets[] = $key.'=:'.$key;
        }

        foreach($primaries as $key)
        {
            $wheres[] = $key.'=:'.$key;
            if(!isset($data[$key])) {
                throw new Website_Exception(
                    'Missing primary in data set',
                    self::ERROR_UPDATE_MISSING_PRIMARY,
                    sprintf(
                        'Looking for key [%s].',
                        $key
                    )
                );
            }
        }

        $query = sprintf(
            "UPDATE
                %s
            SET
                %s
            WHERE
                %s",
            $table,
            implode(', ', $sets),
            implode(' AND ', $wheres)
        );

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($data);
    }

    public function updateByPrimary($primaryPath, $data)
    {
        $tokens = explode('.', $primaryPath);

        return $this->update($tokens[0], $data, array($tokens[1]));
    }

    public function fetchByPrimary($primaryPath, $primary, $select='*')
    {
        $tokens = explode('.', $primaryPath);

        return $this->fetchOne($tokens[0], array($tokens[1] => $primary));
    }

    public function fetchOne($table, $where, $select='*')
    {
        if(is_array($select)) {
            $select = implode(',', $select);
        }

        $whereStatement = '';
        $tokens = array();
        foreach($where as $key => $val) {
            $tokens[] = $key.'=:'.$key;
        }

        $query = sprintf(
            "SELECT
                %s
            FROM
                %s
            WHERE
                %s",
            $select,
            $table,
            implode(' AND ', $tokens)
        );

        $stmt = $this->pdo->prepare($query);

        $stmt->execute($where);

        return $stmt->fetch();
    }

    public function fetchKey($table, $keyName, $where)
    {
        $data = $this->fetchOne($table, $where, $keyName);
        if(isset($data[$keyName])) {
            return $data[$keyName];
        }

        return null;
    }

    protected $transactionActive = false;

    public function requireTransaction($operationLabel)
    {
        if($this->transactionActive) {
            return;
        }

        throw new Website_Exception(
            'A transactiion is required',
            self::ERROR_TRANSACTION_REQUIRED,
            'For operation: ['.$operationLabel.']'
        );
    }

    public function startTransaction() : DB
    {
        $this->transactionActive = true;

        $stmt = $this->pdo->prepare("BEGIN TRANSACTION");
        $stmt->execute();

        return $this;
    }

    public function commitTransaction() : DB
    {
        $stmt = $this->pdo->prepare("COMMIT");
        $stmt->execute();

        $this->transactionActive = false;

        return $this;
    }

    public function rollbackTransaction()
    {
        $stmt = $this->pdo->prepare("ROLLBACK");
        $stmt->execute();

        $this->transactionActive = false;

        return $this;
    }
}
