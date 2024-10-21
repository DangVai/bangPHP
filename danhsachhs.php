<?php session_start();  ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <form action="" method="post">
        <div id="add">
            <ul>
                <label for="userID">UserID:</label>
                <input type="text" id="userID" name="userID" value="<?php echo a('id') ?>">

            </ul>
            
            <ul>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo a('name') ?>">
            </ul>
            <ul>
                <label for="userGender">UserGender:</label>
                <input type="text" id="userGender" name="userGender" value="<?php echo a('gender') ?>">
            </ul>
            <ul>
                <label for="userAddress">UserAddress:</label>
                <input type="text" id="userAddress" name="userAddress" value="<?php echo a('old') ?>">
            </ul>
            <ul>
                <label for="userOld">UserOld:</label>
                <input type="text" id="userOld" name="userOld" value="<?php echo a('old') ?>">
            </ul>
            <ul>
                <label for="userType">UserType:</label>
                <input type="text" id="userType" name="userType" value="<?php echo a('type') ?>">
            </ul>
            <ul>
                <label for="usertask">UserTask:</label>
                <input type="text" id="usertask" name="usertask" value="<?php echo a('task') ?>">
            </ul>
            <ul>
                <input type="text" id="action" name="action" hidden value="<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editItem']) && !empty($_SESSION['products'])) {
                    echo 'edit';} else echo 'add'?>">
            </ul>
            <button type="submit" name="submit">OK</button>
            <button type="submit" name="delete">Delete</button>
        </div>
    </form>

    <form action="" method="post">
        <ul>
            <label for="search">Search by Old:</label>
            <input type="text" id="search" name="search">
            <button type="submit" name="find">OK</button>
        </ul>
    </form>

    <?php
   
    
    // Kiểm tra nếu mảng 'products' chưa tồn tại trong session, thì khởi tạo nó
    if (!isset($_SESSION['products'])) {
        $_SESSION['products'] = array();
    }

    // Xử lý việc thêm người dùng
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $id = htmlspecialchars($_POST["userID"]);
        $name = htmlspecialchars($_POST["username"]);
        $gender = htmlspecialchars($_POST["userGender"]);
        $old = htmlspecialchars($_POST["userOld"]);
        $type = htmlspecialchars($_POST["userType"]);
        $task = htmlspecialchars($_POST["usertask"]);
        $action = $_POST['action'];
        if ($action == 'add') {
            // Thêm người dùng vào mảng session
            $_SESSION['products'][] = array(
                'id' => $id,
                'name' => $name,
                'gender' => $gender,
                'old' => $old,
                'type' => $type,
                'task' => $task
            );
        }
        if($action == 'edit'){
            // Lấy ID của người dùng cần sửa
            // Tìm người dùng có ID tương ứng và cập nhật thông tin
            foreach ($_SESSION['products'] as &$product) {
                if ($product['id'] == $id) {
                    
                    $product['name'] = $name;
                    $product['gender'] = $gender;
                    $product['old'] = $old;
                    
                    $product['type'] = $type;
                    $product['task'] = $task;
                }
            }
        }
    }

    // Xử lý việc xóa một người dùng dựa trên ID
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteItem'])) {
        $deleteID = $_POST['deleteID']; // Lấy ID của người dùng cần xóa
    
        // Tìm và xóa người dùng có ID tương ứng
        foreach ($_SESSION['products'] as $key => $product) {
            if ($product['id'] == $deleteID) {
                unset($_SESSION['products'][$key]);
                break;
            }
        }
    }

    // Xử lý việc chỉnh sửa một người dùng dựa trên ID
    function a($keyname)
    {
        global $_SESSION;
        global $_SERVER;

        global $_POST;


        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editItem']) && !empty($_SESSION['products'])) {
            $editID = $_POST['editID']; // Lấy ID của người dùng cần sửa
            // Tìm người dùng có ID tương ứng và cập nhật thông tin
            foreach ($_SESSION['products'] as $product) {
                if ($product['id'] == $editID) {
                    return $product[$keyname];
                }
            }
        }

    };
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['find'])) {
        $search = htmlspecialchars($_POST["search"]);
        if ($search != '') {
            $normalArray = $_SESSION['products'];
            $result = array_filter($normalArray, function ($value) use ($search) {
                return $value['old'] == $search;
            });
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['find'])) {
                $search = htmlspecialchars($_POST["search"]);
                if ($search != '') {
                    $normalArray = $_SESSION['products'];
                    $result = array_filter($normalArray, function ($value) use ($search) {
                        return $value['old'] == $search;
                    });
                    // Hiển thị bảng dữ liệu người dùng
                    if (!empty($result)) {  // Sử dụng $result thay vì $_SESSION['products']
                        echo "Search results for old = '$search':<br>";
                        echo "<table>";
                        echo "<tr><th>Mã sinh viên</th><th>Họ và tên</th><th>Giới tính</th><th>Quên quán</th><th>Ngày sinh</th><th>Ngành học</th><th>Tác vụ</th></tr>";
                        foreach ($result as $item) {  // Duyệt qua mảng $result thay vì $_SESSION['products']
                            echo "<tr>
                                <td>{$item['id']}</td>
                                <td>{$item['name']}</td>
                                <td>{$item['gender']}</td>
                                <td>{$item['old']}</td>
                                <td>{$item['type']}</td>
                                <td>{$item['task']}</td>
                                <td>
                                    <form method='post' style='display:inline;'>
                                        <input type='hidden' name='deleteID' value='{$item['id']}'>
                                        <button type='submit' name='deleteItem'>Delete</button>
                                    </form>
                                    <form method='post' action='' style='display:inline;'>
                                        <input type='hidden' name='editID' value='{$item['id']}'>
                                        <button type='submit' name='editItem'>Edit</button>
                                    </form>
                                </td>
                            </tr>";
                        }
                        echo "</table>";
                    } 
                    else {
                        echo "No products found with old = '$search'.";
                    }
                }
            }
        }
    }



    // Hiển thị bảng dữ liệu người dùng
    if (!empty($_SESSION['products'])) {
        echo "Search results for old = '$search':<br>";
        echo "<table>";
        echo "<tr><th>Mã sinh viên</th><th>Họ và tên</th><th>Giới tính</th><th>Quên quán</th><th>Ngày sinh</th><th>Ngành học</th><th>Tác vụ</th></tr>";
        foreach ($_SESSION['products'] as $item) {
            echo "<tr>
                <td>{$item['id']}</td>
                <td>{$item['name']}</td>
                <td>{$item['gender']}</td>
                <td>{$item['old']}</td>
                <td>{$item['type']}</td>
                <td>{$item['task']}</td>
                <td>
                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='deleteID' value='{$item['id']}'>
                        <button type='submit' name='deleteItem'>Delete</button>
                    </form>
                    <form method='post' action='' style='display:inline;'>
                        <input type='hidden' name='editID' value='{$item['id']}'>
                        <button type='submit' name='editItem'>Edit</button>
                    </form>
                </td>
            </tr>";
        }
        echo "</table>";
    } else {
        echo "No products available.";
    }

    // Xử lý hành động xóa tất cả dữ liệu trong session (xóa toàn bộ sản phẩm)
    if (isset($_POST['delete'])) {
        if (isset($_SESSION['products'])) {
            unset($_SESSION['products']); // Xóa mảng 'products' trong session
            echo "Data has been deleted."; // Thông báo dữ liệu đã bị xóa
        }
    }
    ?>


</body>

</html>