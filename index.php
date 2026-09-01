<?php

require_once __DIR__ . "/config/databse.php";

/* ADD TASK */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST["title"];
    $description = $_POST["description"];
    $status = "TODO";

    $sql = "INSERT INTO tasks (title, description, status)
            VALUES (:title, :description, :status)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':title' => $title,
        ':description' => $description,
        ':status' => $status
    ]);
}


/* GET ALL TASKS */

$stmt = $pdo->query("SELECT * FROM tasks ORDER BY id");

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Task Manager</title>
</head>

<body>

    <h1>Task Manager</h1>

    <h2>Add New Task</h2>

    <form method="POST">

        <label>Task Title:</label><br>

        <input type="text" name="title" required>

        <br><br>

        <label>Description:</label><br>

        <textarea name="description"></textarea>

        <br><br>

        <button type="submit">Add Task</button>

    </form>

    <hr>

    <h2>My Tasks</h2>

    <?php foreach ($tasks as $task): ?>

        <div>

            <h3><?= $task['title'] ?></h3>

            <p><?= $task['description'] ?></p>

            <p>
                Status: <?= $task['status'] ?>
            </p>

            <hr>

        </div>

    <?php endforeach; ?>

</body>
</html>