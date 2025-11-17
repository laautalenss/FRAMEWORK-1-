
<?php
$user = 'zonzamas';
$password = 'Lanza25.pass';
$database = 'zonzamas';
$table = 'zonzamas.todo_list';
try {
$db = new PDO("mysql:host=localhost;dbname=$database", $user,
$password);
echo "<h2>TODO</h2><ol>";
foreach($db->query("SELECT content FROM $table") as $row) {
echo "<li>" . $row['content'] . "</li>";
}
echo "</ol>";
} catch (PDOException $e) {
print "Error!: " . $e->getMessage() . "<br/>";
die();
}

/*

/var/www/zonzamas/todo_list.php
CREATE USER 'zonzamas'@'%' IDENTIFIED WITH mysql_native_password BY 'Lanza25.pass';

GRANT ALL ON zonzamas.* TO 'zonzamas'@'%';

CREATE TABLE zonzamas.todo_list (
item_id INT AUTO_INCREMENT,
content VARCHAR(255),
PRIMARY KEY(item_id)
);

mysql -u 'zonzamas'@'%' -p;

INSERT INTO zonzamas.todo_list (content) VALUES ("My first
important item");
*/