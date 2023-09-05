<?php
include 'db_connect.php';

if (isset($_GET['search_title']) || isset($_GET['search_year'])) {
    $search_title = isset($_GET['search_title']) ? $_GET['search_title'] : '';
    $search_year = isset($_GET['search_year']) ? $_GET['search_year'] : '';

    $sql = "SELECT * FROM books WHERE 1";
    $params = array();

    if (!empty($search_title)) {
        $sql .= " AND title LIKE :search_title";
        $params[':search_title'] = '%' . $search_title . '%';
    }

    if (!empty($search_year)) {
        $sql .= " AND pub_year = :search_year";
        $params[':search_year'] = $search_year;
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Lỗi truy vấn cơ sở dữ liệu: " . $e->getMessage());
    }
} else {
    try {
        $stmt = $pdo->prepare("SELECT * FROM books");
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Lỗi truy vấn cơ sở dữ liệu: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý sách</title>
</head>
<body>
    <h1>Danh sách sách</h1>

    <form action="index.php" method="GET">
        <label for="search_title">Tìm kiếm theo tiêu đề:</label>
        <input type="text" name="search_title" id="search_title" placeholder="Nhập tiêu đề">

        <label for="search_year">Tìm kiếm theo năm xuất bản:</label>
        <input type="text" name="search_year" id="search_year" placeholder="Nhập năm xuất bản">

        <input type="submit" value="Tìm kiếm">
    </form>

    <table>
        <tr>
            <th>Tên</th>
            <th>Tác giả</th>
            <th>ISBN</th>
            <th>Năm xuất bản</th>
            <th>Trạng thái</th>
        </tr>
        <?php
if (isset($result) && !empty($result)) {
    foreach ($result as $book) {
        echo "<tr>";
        echo "<td>" . $book['title'] . "</td>";
        echo "<td>" . $book['authorid'] . "</td>";
        echo "<td>" . $book['ISBN'] . "</td>";
        echo "<td>" . $book['pub_year'] . "</td>";
        echo "<td>" . ($book['available'] ? 'Có sẵn' : 'Không có sẵn') . "</td>";
        echo "</tr>";
    }
} else {
    foreach ($books as $book) {
        echo "<tr>";
        echo "<td>" . $book['title'] . "</td>";
        echo "<td>" . $book['authorid'] . "</td>";
        echo "<td>" . $book['ISBN'] . "</td>";
        echo "<td>" . $book['pub_year'] . "</td>";
        echo "<td>" . ($book['available'] ? 'Có sẵn' : 'Không có sẵn') . "</td>";
        echo "</tr>";
    }
}
?>
    </table>
</body>
</html>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
}

h1 {
    background-color: #333;
    color: #fff;
    padding: 10px;
    text-align: center;
}

form {
    margin: 20px auto;
    text-align: center;
}

label {
    font-weight: bold;
}

input[type="text"] {
    width: 200px;
    padding: 5px;
    margin-right: 10px;
}

input[type="submit"] {
    background-color: #333;
    color: #fff;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
}

table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    border: 1px solid #333;
}

table th, table td {
    border: 1px solid #333;
    padding: 10px;
    text-align: center;
}

table th {
    background-color: #333;
    color: #fff;
}

</style>