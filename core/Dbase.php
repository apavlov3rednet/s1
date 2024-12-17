<?php
namespace Core;

use PDO;
use PDOException;
use Exception;

class Dbase
{
    public $dbname;
    public $database;
    public $host;
    public $user;
    public $password;
    public $conn;

    public $settings;

    public function __construct(array $options)
    {
        //ip = 127.127.126.26
        //port = 3306
        //
        $this->host = $options['host'];
        $this->user = $options['login'];
        $this->password = $options['password'];
        $this->database = $options['database'];

        $this->connect();
    }

    public function runMethod(string $sql): void {
        try {
            echo '<pre>';
            print_r($sql);
            echo '</pre>';

            $request = $this->conn->prepare($sql);
            //Обработка ответа
            $request->execute();
            
        } catch (PDOException $e) {
            //Logs::add2Log('Query get list fail: ' . $e->getMessage()); 
        }
    }

    private function connect(): bool
    {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->database",
                $this->user,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            //Logs::add2Log('Connection fails: ' . $e->getMessage());
            $this->conn = null;
            return false;
        }
    }

    private function prepareFilter($arFilter, &$sql, &$filter, &$execute) {
        if (!empty($arFilter)) {
            foreach ($arFilter as $key => $value) {
                $filter[] = $key . ' = ?';
                $execute[] = $value;
            }
        }

        if (!empty($filter)) {
            $sql .= ' WHERE ' . join(', ', $filter);
        }
    }

    /**
     * <p>Получение записей из БД</p>
     * @param string $table
     * @param array $params = [
     *  'select' => ['*', 'NAME', 'PASSWORD', 'CODE'], // поля которые выбираем
     *  'order' => [ 'SORT' => 'ASC' ], // принцип сортировки
     *  'filter' => [
     *      'GROUP' => 5,
     *      'AGE' => 10
     *  ], // фильтр
     *  'limit' => [
     *      'offset' => 1 // текущая позиция выборки, умножаем на лимит при постраничной навигации
     *      'rows' => 5 // количество отбираемых строк
     *  ]   // ограничения на количество выбираемых элементов
     * ]
     * 
     * 
     * @return array
     */
    public function getList(string $table, array $params = []): array
    {
        if (!$this->conn)
            return [];

        //Значения по умолчанию
        $filter = []; //готовый фильтр
        $execute = []; //параметры фильтра
        $limit = 100;
        $offset = 0;

        //Основная выборка из таблицы
        $sql = 'SELECT ';
        $select = (!empty($params['select'])) ? join(', ', $params['select']) : '*';

        $sql .= $select . ' ';
        $sql .= 'FROM ' . $table;

        //Применение фильтров в выборке
        if(!empty($params['filter']))
            $this->prepareFilter($params['filter'], $sql, $filter, $execute);
        
        //Сортировка
        if (!empty($params['order'])) {
            $key = array_key_first($params['order']);
            $sql .= ' ORDER BY ' . $key  . ' ' . $params['order'][$key];
        }

        //Применение лимитов и стратовой позиции выборки
        if (!empty($params['limit'])) {
            $limit = (!empty($params['limit']['rows'])) ? $params['limit']['rows'] : $limit;
            $offset = (!empty($params['limit']['offset'])) ? $params['limit']['offset'] : $offset;

            $sql .= ' LIMIT ' . $limit;
            $sql .= ' OFFSET ' . $offset;
        }

        //Запрос и ответ
        $result = [];
        try {
            $request = $this->conn->prepare($sql);
            $request->execute($execute);

            $response = $request->fetchAll(PDO::FETCH_ASSOC);

            //Обработка ответа
            foreach ($response as $row) {
                $result[] = $row;
            }
        } catch (PDOException $e) {
            //Logs::add2Log('Query get list fail: ' . $e->getMessage()); 
        }
        return $result;
    }

    /**
     * Summary of add
     * @param string $table
     * @param array $arFields = [
     *  'KEY' => 'VALUE',
     *  'KEY2' => 'VALUE'
     * ]
     * @return mixed
     */
    public function add(string $table, array $arFields) {
        try {
            $fields = join(', ', array_keys($arFields));
            $prepValues =  ':' .join(', :', array_keys($arFields));
            $values = [];
            
            $sql = 'INSERT INTO ' . $table . '(' . $fields . ') VALUES (' . $prepValues . ')';
            // INSERT INTO table (KEY, KEY2) VALUES (:KEY, :KEY2)
    
            $request = $this->conn->prepare($sql);
    
            foreach($arFields as $key => $value) {
                $request->bindValue(':'. $key, $value); // :KEY , VALUE
            }
    
            if($request->execute()) {
                $id = $this->conn->lastInsertId('ID');
                return $id;
            }
            else {
                return false;
            }
            
        }
        catch(PDOException $e) {
            //Logs::add2Log('Query add: ' . $e->getMessage()); 
        }
    }

    public function delete(string $table, array $where) {
        try {
            $filter = [];
            $execute = [];
            $sql = 'DELETE FROM ' . $table;
            $this->prepareFilter($where, $sql, $filter, $execute);
            //DELETE FROM users WHERE ID = ?
            $request = $this->conn->prepare($sql);

            if($request->execute($execute)) {
                return true;
            }
            else {
                //Logs::add2Log('Error sql: '. $sql);
                return false;
            }
        }
        catch(PDOException $e) {
            //Logs::add2Log('delete: '. $e->getMessage());
            return false;
        }
    }

    public function update(string $table, int $id, array $arFields) {
        try {
            //UPDATE `users` SET `LOGIN` = :LOGIN, `PASSWORD` = :PASSWORD WHERE `users`.`ID` = ?;
            $filter = [];
            $execute = [];
            $arSql = [];
            $sql = 'UPDATE '. $table . ' SET ';

            foreach( $arFields as $key => $value ) {
                $arSql[] = $key . ' = :' . $value; //`LOGIN` = :LOGIN
            }

            if( !empty($arSql) ) {
                $sql .= join(', ', $arSql);
            }
            //UPDATE `users` SET `LOGIN` = :LOGIN, `PASSWORD` = :PASSWORD

            $this->prepareFilter(['ID' => $id], $sql, $filter, $execute );
            //UPDATE `users` SET `LOGIN` = :LOGIN, `PASSWORD` = :PASSWORD WHERE `users`.`ID` = ?;

            $request = $this->conn->prepare($sql);

            foreach( $arFields as $key => $value ) {
                $request->bindValue(":".$key, $value);
            }

            if($request->execute($execute)) {
                return true;
            }
            else {
                //Logs::add2Log("Update error: ". $sql);
                return false;
            }

        }
        catch(PDOException $e) {
            //Logs::add2Log('update: '. $e->getMessage());
            return false;
        }
    }

    public function deleteById(string $table, int $id) {
        return $this->delete($table, ['filter' => ['ID'=> $id]]);
    }

    public function getById(string $table, int $id) {
        return $this->getList($table, [
            'filter' => ['ID' => $id]
        ]);
    }

    public function getCount(string $table) {
        $ob = $this->getList($table, [
            'select' => ['ID']
        ]);

        return count($ob);
    }
}