<?php
class Task {
    private $id;
    private $description;
    private $completed;

    public function __construct($description, $completed = false, $id = null) {
        $this->id = $id;
        $this->description = $description;
        $this->completed = $completed;
    }

    // Getters and Setters
    public function getId() {
        return $this->id;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function isCompleted() {
        return $this->completed;
    }

    public function setCompleted($completed) {
        $this->completed = $completed;
    }
}


?>

<?php
include 'Task.php';

// Database connection using PDO
function connectToDatabase() {
    try {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=publications", "Katleho", "password");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Function to add a task to the database
function addTask($description) {
    $pdo = connectToDatabase();
    $sql = "INSERT INTO tasks (description) VALUES (:description)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':description' => $description]);
}

// Function to get all tasks from the database
function getTasks() {
    $pdo = connectToDatabase();
    $sql = "SELECT * FROM tasks";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to mark a task as completed
function completeTask($taskId) {
    $pdo = connectToDatabase();
    $sql = "UPDATE tasks SET completed = 1 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $taskId]);
}

// Handle form submission for adding a new task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['description'])) {
    $description = $_POST['description'];
    addTask($description);
    header("Location: index.php");
    exit;
}

// Handle form submission for marking tasks as completed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete'])) {
    $taskId = $_POST['complete'];
    completeTask($taskId);
    header("Location: index.php");
    exit;
}

$tasks = getTasks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List Application</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>To-Do List</h1>

    <!-- Form to add a new task -->
    <form action="index.php" method="POST">
        <label for="description">New Task:</label>
        <input type="text" name="description" id="description" required>
        <button type="submit">Add Task</button>
    </form>

    <h2>Tasks</h2>
    <form action="index.php" method="POST">
        <table>
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Completed</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td>
                            <?php if ($task['completed']): ?>
                                ✔
                            <?php else: ?>
                                <button type="submit" name="complete" value="<?php echo $task['id']; ?>">Mark as Completed</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>
