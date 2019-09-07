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

        $keys = array_keys($data);
        
        foreach($keys as $key) {
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

        $sets = array();
        $wheres = array();
        $keys = array_keys($data);

        foreach($keys as $key) {
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
        $query = sprintf(
            "SELECT
                %s
            FROM
                %s
            WHERE
                %s",
            $this->compileSelect($select),
            $table,
            $this->compileWhere($where)
        );

        $stmt = $this->pdo->prepare($query);

        $stmt->execute($where);

        return $stmt->fetch();
    }

    public function fetchOneQuery($query, $vars=array())
    {
        $query = sprintf(
            $query,
            $vars
        );
        
        $stmt = $this->pdo->prepare($query);
        
        $stmt->execute($vars);
        
        return $stmt->fetch();
    }
    
    protected function compileWhere(array $where=array())
    {
        if(empty($where)) {
            return '1';
        }
        
        $keys = array_keys($where);
        
        $tokens = array();
        foreach($keys as $key) {
            $tokens[] = $key.'=:'.$key;
        }
        
        return implode(' AND ', $tokens);
    }
    
    protected function compileSelect($select)
    {
        if(empty($select) || $select === '*') {
            return '*';    
        }

        if(is_array($select)) {
            return implode(', ', $select);
        }
        
        return $select;
    }
    
    public function fetchKey($table, $keyName, array $where=array())
    {
        $data = $this->fetchOne($table, $where, $keyName);
        if(isset($data[$keyName])) {
            return $data[$keyName];
        }

        return null;
    }
    
    public function fetchAllKey(string $table, string $keyName, array $where=array())
    {
        $data = $this->fetchAll($table, $where, $keyName);
        
        $result = array();
        
        foreach($data as $entry) {
            if(isset($entry[$keyName])) {
                $result[] = $entry[$keyName];
            }
        }   
        
        return $result;
    }
    
    public function fetchAll(string $table, array $where=array(), $select='*')
    {
        $query = sprintf( 
        "SELECT 
            %s
        FROM
            %s
        WHERE
            %s",
            $this->compileSelect($select),
            $table,
            $this->compileWhere($where)
        );
        
        $stmt = $this->pdo->prepare($query);
        
        $stmt->execute($where);
        
        return $stmt->fetchAll();
    }
    
    public function fetchAllQuery($query, $vars=array())
    {
        $stmt = $this->pdo->prepare($query);
        
        $stmt->execute($vars);
        
        return $stmt->fetchAll();
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
    
    public function delete(string $table, array $where)
    {
        $query = sprintf( 
        "DELETE FROM
            %s
        WHERE
            %s",
            $table,
            $this->compileWhere($where)
        );
            
        $stmt = $this->pdo->prepare($query);
        
        $stmt->execute($where);
        
        return $stmt->rowCount();
    }
}
