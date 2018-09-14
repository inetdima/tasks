<?php

class Database
{


    private $db_host = 'localhost';
    private $db_user = 'root';
    private $db_pass = '';
    private $db_name = 'base_tasks';

    private $connected = false;
    private $results = array();


    public function connect() {
        if(!$this->connected) {
            $mainconn = @mysql_connect($this->db_host,$this->db_user,$this->db_pass);
            if($mainconn) {
                $selectdb = @mysql_select_db($this->db_name,$mainconn);
                if($selectdb) {
                    $this->connected = true;
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }


    public function setDatabase($name) {
        if($this->connected) {
            if(@mysql_close()) {
                $this->connected = false;
                $this->results0 = null;
                $this->db_name = $name;
                $this->connect();
            }
        }
    }


    private function tableExists($table) {
        $tablesInDb = @mysql_query('SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"');
        if($tablesInDb) {
            if(mysql_num_rows($tablesInDb)==1) {
                return true;
            } else {
                return false;
            }
        }
    }


    public function select($table, $rows = '*', $where = null, $order = null)
    {
        $q = '';
        $q .= 'SELECT '.$rows.' FROM '.$table;
        if($where != null)
            $q .= ' WHERE '.$where;
        if($order != null)
            $q .= ' ORDER BY '.$order;

        $query = @mysql_query($q);
        if($query)
        {
            $this->numResults = mysql_num_rows($query);
            for($i = 0; $i < $this->numResults; $i++)
            {
                $r = mysql_fetch_array($query);
                $key = array_keys($r);
                for($x = 0; $x < count($key); $x++)
                {
            
                    if(!is_int($key[$x]))
                    {
                        if(mysql_num_rows($query) > 1)
                            $this->results[$i][$key[$x]] = $r[$key[$x]];
                        else if(mysql_num_rows($query) < 1)
                            $this->results = null;
                        else
                            $this->results[$key[$x]] = $r[$key[$x]];
                    }
                }
            }
            return true;
        }
        else
        {
            return false;
        }
    }


    public function insert($table,$values,$rows = null)
    {
        if($this->tableExists($table))
        {
            $insert = 'INSERT INTO '.$table;
            if($rows != null)
            {
                $insert .= ' ('.$rows.')';
            }

            for($i = 0; $i < count($values); $i++)
            {
 
                if(is_string($values[$i]))
                    $values[$i] = "'".$values[$i]."'";
            }
            $values = implode(',',$values);
            $insert .= ' VALUES ('.$values.')';

            $ins = @mysql_query($insert);
            if($ins)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }


    public function delete($table,$where = null)
    {
        if($this->tableExists($table))
        {
            if($where == null)
            {
                $delete = 'DELETE '.$table;
            }
            else
            {
                $delete = 'DELETE FROM '.$table.' WHERE '.$where;
            }
            $del = @mysql_query($delete);

            if($del)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }


    public function update($table,$rows,$where,$condition) {
        if($this->tableExists($table)) {

            for($i = 0; $i < count($where); $i++) {
                if($i%2 != 0) {
                    if(is_string($where[$i])) {
                        if(($where[$i+1]) != null)
                            $where[$i] = '"'.$where[$i].'" AND ';
                        else
                            $where[$i] = '"'.$where[$i].'"';
                    }
                }
            }
            $where = implode($condition,$where);



            $update = 'UPDATE '.$table.' SET ';
            $keys = array_keys($rows);
            for($i = 0; $i < count($rows); $i++) {
                if(is_string($rows[$keys[$i]])) {
                    $update .= $keys[$i].'="'.$rows[$keys[$i]].'"';
                } else {
                    $update .= $keys[$i].'='.$rows[$keys[$i]];
                }
                // Parse to add commas
                if($i != count($rows)-1) {
                    $update .= ',';
                }
            }
            $update .= ' WHERE '.$where;    

            $query = @mysql_query($update);
            if($query) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
    * Returns the result set
    */
    public function getResult()
    {
        return $this->results;
    }
}






/*$db = new Database();
$db->connect();
//$db->insert('tasks',array("Name 5","82",'777'), "name, status, project_id");
//$db->delete('mysqcrud', 'id=4');
//$db->update('projects', array('name'=>'Name 5'), array('id',20),"=");
$db->select('projects', '*', null, 'id ASC');
$res = $db->getResult();
echo "<pre>";
print_r($res);
echo "</pre>";
  */
?>