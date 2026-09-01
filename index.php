<?php

require_once __DIR__ . "/config/databse.php";
// Change from conflict-practice branch

/* ================= ADD TASK ================= */

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_task"])) {

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

    header("Location: index.php");
    exit;
}


/* ================= MARK TASK AS COMPLETED ================= */

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["complete_task"])) {

    $id = $_POST["task_id"];

    $sql = "UPDATE tasks 
            SET status = 'COMPLETED'
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    header("Location: index.php");
    exit;
}


/* ================= GET ALL TASKS ================= */

$stmt = $pdo->query("SELECT * FROM tasks ORDER BY id DESC");

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* ================= TASK STATISTICS ================= */

$totalTasks = count($tasks);

$todoTasks = 0;
$completedTasks = 0;

foreach ($tasks as $task) {

    if ($task["status"] === "TODO") {
        $todoTasks++;
    }

    if ($task["status"] === "COMPLETED") {
        $completedTasks++;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TaskFlow - Task Manager</title>

    <link rel="stylesheet" href="database/assets/style.css">

</head>


<body>

<div class="app">


    <!-- ================= SIDEBAR ================= -->

    <aside class="sidebar">

        <div class="logo">

            <div class="logo-icon">✓</div>

            <span>TaskFlow</span>

        </div>


        <nav class="menu">

            <a href="#" class="menu-item active">
                <span>▦</span>
                Dashboard
            </a>

            <a href="#tasks" class="menu-item">
                <span>☑</span>
                My Tasks
            </a>

            <a href="#add-task" class="menu-item">
                <span>＋</span>
                Add Task
            </a>

        </nav>


        <div class="sidebar-bottom">

            <div class="user-box">

                <div class="avatar">K</div>

                <div>

                    <strong>Task Manager</strong>

                    <small>Stay productive 🚀</small>

                </div>

            </div>

        </div>

    </aside>



    <!-- ================= MAIN CONTENT ================= -->

    <main class="main-content">


        <!-- HEADER -->

        <header class="top-header">

            <div>

                <p class="welcome">WELCOME BACK 👋</p>

                <h1>Your Tasks Dashboard</h1>

                <p class="header-text">
                    Organize your work and stay productive.
                </p>

            </div>


            <div class="header-date">

                <span>📅</span>

                <span><?= date("d M Y"); ?></span>

            </div>

        </header>



        <!-- ================= STATISTICS ================= -->

        <section class="stats">


            <div class="stat-card">

                <div class="stat-icon purple">
                    📋
                </div>

                <div>

                    <p>Total Tasks</p>

                    <h2><?= $totalTasks ?></h2>

                </div>

            </div>



            <div class="stat-card">

                <div class="stat-icon orange">
                    ⏳
                </div>

                <div>

                    <p>To Do</p>

                    <h2><?= $todoTasks ?></h2>

                </div>

            </div>



            <div class="stat-card">

                <div class="stat-icon green">
                    ✓
                </div>

                <div>

                    <p>Completed</p>

                    <h2><?= $completedTasks ?></h2>

                </div>

            </div>


        </section>



        <!-- ================= ADD TASK ================= -->

        <section class="add-task-section" id="add-task">


            <div class="section-header">

                <div>

                    <h2>Create New Task</h2>

                    <p>Add something new to your task list.</p>

                </div>

            </div>


            <form method="POST" class="task-form">


                <div class="form-group">

                    <label>Task Title</label>

                    <input
                        type="text"
                        name="title"
                        placeholder="What do you need to do?"
                        required
                    >

                </div>



                <div class="form-group">

                    <label>Description</label>

                    <textarea
                        name="description"
                        placeholder="Add some details about your task..."
                    ></textarea>

                </div>



                <!-- IMPORTANT: name="add_task" -->

                <button
                    type="submit"
                    name="add_task"
                    class="add-button"
                >

                    <span>＋</span>

                    Create Task

                </button>


            </form>


        </section>



        <!-- ================= TASKS ================= -->

        <section class="tasks-section" id="tasks">


            <div class="tasks-header">

                <div>

                    <h2>My Tasks</h2>

                    <p><?= $totalTasks ?> tasks in your workspace</p>

                </div>

            </div>



            <div class="tasks-grid">


                <?php if (count($tasks) > 0): ?>


                    <?php foreach ($tasks as $task): ?>


                        <article class="task-card">


                            <div class="task-card-top">


                                <div class="task-title-area">

                                    <div class="task-checkbox"></div>

                                    <div>

                                        <h3>
                                            <?= htmlspecialchars($task['title']) ?>
                                        </h3>

                                    </div>

                                </div>



                                <?php

                                $statusClass =
                                    $task['status'] === "COMPLETED"
                                    ? "completed"
                                    : "todo";

                                ?>


                                <span class="status <?= $statusClass ?>">

                                    <?= htmlspecialchars($task['status']) ?>

                                </span>


                            </div>



                            <?php if (!empty($task['description'])): ?>


                                <p class="task-description">

                                    <?= htmlspecialchars($task['description']) ?>

                                </p>


                            <?php endif; ?>



                            <!-- COMPLETE BUTTON -->

                            <?php if ($task['status'] === "TODO"): ?>


                                <form method="POST">

                                    <input
                                        type="hidden"
                                        name="task_id"
                                        value="<?= $task['id'] ?>"
                                    >

                                    <button
                                        type="submit"
                                        name="complete_task"
                                        class="complete-btn"
                                    >
                                        ✓ Mark as Completed
                                    </button>

                                </form>


                            <?php endif; ?>



                            <div class="task-footer">


                                <span class="task-id">

                                    Task #<?= $task['id'] ?>

                                </span>



                                <span class="task-time">


                                    <?php if ($task['status'] === "COMPLETED"): ?>

                                        ✓ Completed

                                    <?php else: ?>

                                        ● Active

                                    <?php endif; ?>


                                </span>


                            </div>


                        </article>


                    <?php endforeach; ?>



                <?php else: ?>


                    <div class="empty-state">

                        <div class="empty-icon">📋</div>

                        <h3>No tasks yet!</h3>

                        <p>Create your first task and start being productive.</p>

                    </div>


                <?php endif; ?>


            </div>


        </section>


    </main>


</div>


</body>

</html>