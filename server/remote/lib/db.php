<?php
 /**
 * @class myDB
 */
class myDB {
	private $dbh=null;
    public function __construct() {
	
		$hostname = 'localhost';
		$username = 'root';
		$password = 'm';

		try {
			$this->dbh = new PDO("mysql:host=$hostname;dbname=demo", $username, $password);
			//echo 'Connected to database';
		}
		catch(PDOException $e)
		{

            //print_r($e);
			echo $e->getMessage();
		}

    }
	public function __destruct() {
		$this->dbh=null;
	}

    public function pk() {
        if(!isset($_SESSION['pk']))
            $_SESSION['pk']='';
        return $_SESSION['pk'];
    }
	
    public function rs() {
		$rs= array();
		
		$sql = null;
		if(isset($_REQUEST['searchtxt']) && strlen($_REQUEST['searchtxt'])>=1)
		{
			$searchTxt = strToLower(htmlentities($_REQUEST['searchtxt']));
			if($searchTxt==='true') $searchTxt1 =1;
			else if($searchTxt==='false') $searchTxt1 =0;
			$searchTxt2 = "%".$searchTxt."%";
			$sql  = $this->dbh->prepare("SELECT * FROM products WHERE LOWER(name) LIKE ? OR quantity=? OR price=? OR LOWER(description) LIKE ? OR is_in_stock=?");
			$sql->bindParam(1, $searchTxt2);
			$sql->bindParam(2, $searchTxt);
			$sql->bindParam(3, $searchTxt);
			$sql->bindParam(4, $searchTxt2);
			$sql->bindParam(5, $searchTxt1);
		}
		else
		{
			$dummyTxt = 0;
			$sql  = $this->dbh->prepare("SELECT * FROM products WHERE id>?");
			$sql->bindParam(1, $dummyTxt);
		}
		$sql->execute();
		if($sql)
		{
			while($row = $sql->fetch(PDO::FETCH_ASSOC))
			{
				if($row['is_in_stock']==1) $row['is_in_stock']='true';
				else if($row['is_in_stock']==0) $row['is_in_stock']='false';
				$rs[] = $row;
			}
		}
        return $rs;
    }
    public function insert($rec) {
		$sql = $this->dbh->prepare("INSERT INTO products  VALUES (0,?,?,?,?,?)");
		$sql->bindParam(1, $rec['name']);
		$sql->bindParam(2, $rec['quantity']);
		$sql->bindParam(3, $rec['price']);
		$sql->bindParam(4, $rec['description']);
		$sql->bindParam(5, $rec['is_in_stock']);
        $this->dbh->beginTransaction();
		$sql->execute();
       // echo $this->dbh->lastInsertId();
        $_SESSION['pk'] = $this->dbh->lastInsertId();
        $this->dbh->commit();
        return $_SESSION['pk'];



    }
    public function update($idx, $attributes) {
		$sql = "UPDATE products SET ";
		$id =0;
		foreach($attributes as $key=>$val)
		{
			if($key=='is_in_stock')
				$sql .= $key."='".$val."' ";
			else if($key=='id')
				$id=$val;
			else
				$sql .= $key."='".$val."', ";
		}
		$sql .= " WHERE id=".$id;
		//echo $sql;
		$this->dbh->query($sql);		
    }
    public function destroy($idx, $attributes) {
		$sql = $this->dbh->prepare("DELETE FROM products WHERE id=?");
		//print_r($attributes['id']);
		$sql->bindParam(1, $attributes['id']);
		$sql->execute();
		return $this->rs();
    }
	
	public function doLogin($user, $pass) {
		$user = strToLower($user);
		$sql  = $this->dbh->prepare("SELECT uid, `name` FROM users WHERE (LOWER(username)=? OR LOWER(email)=?) AND passwd=?");		
		$pass = md5($pass);
		$sql->bindParam(1, $user);
		$sql->bindParam(2, $user);
		$sql->bindParam(3, $pass);
		$sql->execute();
		if($sql)
		{
			if($row = $sql->fetch(PDO::FETCH_ASSOC))
			{
				$_SESSION['uid'] = $row['uid'];
				$_SESSION['name'] = $row['name'];
				return true;
			}
		}
		return false;
    }
	
}
