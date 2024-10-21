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
                <input type="text" id="userID" name="userID">
            </ul>
            <ul>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username">
            </ul>
            <ul>
                <label for="userGender">UserGender:</label>
                <input type="text" id="userGender" name="userGender">
            </ul>
            <ul>
                <label for="userAddress">UserAddress:</label>
                <input type="text" id="userAddress" name="userAddress">
            </ul>
            <ul>
                <label for="userOld">UserOld:</label>
                <input type="text" id="userOld" name="userOld">
            </ul>
            <ul>
                <label for="userType">UserType:</label>
                <input type="text" id="userType" name="userType">
            </ul>
            <ul>
                <label for="usertask">UserTask:</label>
                <input type="text" id="usertask" name="usertask">
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
    session_start();

    // Initialize product array in session if not already set
    if (!isset($_SESSION['products'])) {
        $_SESSION['products'] = array();
    }

    // Handle form submission for adding a new product
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $id = htmlspecialchars($_POST["userID"]);
        $name = htmlspecialchars($_POST["username"]);
        $gender = htmlspecialchars($_POST["userGender"]);
        $old = htmlspecialchars($_POST["userOld"]);
        $type = htmlspecialchars($_POST["userType"]);
        $task = htmlspecialchars($_POST["usertask"]);

        // Add product to session array
        $_SESSION['products'][] = array(
            'id' => $id,
            'name' => $name,
            'gender' => $gender,
            'old' => $old,
            'type' => $type,
            'task' => $task
        );
    }

    // Handle search by old
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['find'])) {
        $search = htmlspecialchars($_POST["search"]);

        // Check if the search field is not empty
        if ($search != '') {
            // Get products from session
            $normalArray = $_SESSION['products'];

            // Filter the array based on the 'old' value
            $result = array_filter($normalArray, function ($value) use ($search) {
                return $value['old'] == $search; // Compare the 'old' field with search input
            });

            // Display search results
            if (!empty($result)) {
                echo "Search results for old = '$search':<br>";
                echo "<table>";
                echo "<tr><th>UserID</th><th>Username</th><th>UserGender</th><th>UserOld</th><th>UserType</th><th>UserTask</th></tr>";
                foreach ($result as $item) {
                    echo "<tr>
                        <td>{$item['id']}</td>
                        <td>{$item['name']}</td>
                        <td>{$item['gender']}</td>
                        <td>{$item['old']}</td>
                        <td>{$item['type']}</td>
                        <td>{$item['task']}</td>
                    </tr>";
                }
                echo "</table>";
            } else {
                echo "No results found for old = '$search'.";
            }
        } else {
            echo "Please enter a value to search.";
        }
    }

    // Display all products if no search is performed
    if (empty($_POST['find'])) {
        $normalArray = $_SESSION['products'];

        if (!empty($normalArray)) {
            echo "<table>";
            echo "<tr><th>UserID</th><th>Username</th><th>UserGender</th><th>UserOld</th><th>UserType</th><th>UserTask</th></tr>";
            foreach ($normalArray as $item) {
                echo "<tr>
                    <td>{$item['id']}</td>
                    <td>{$item['name']}</td>
                    <td>{$item['gender']}</td>
                    <td>{$item['old']}</td>
                    <td>{$item['type']}</td>
                    <td>{$item['task']}</td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "No products available.";
        }
    }

    // Handle delete action to clear session data
    if (isset($_POST['delete'])) {
        if (isset($_SESSION['products'])) {
            unset($_SESSION['products']); // Clear all session data
            echo "Data has been deleted.";
        }
    }
    ?>

</body>

</html>