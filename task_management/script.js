document.addEventListener('DOMContentLoaded', () => {
    const role = sessionStorage.getItem('role'); // Assuming role is stored in session
    const taskCreationForm = document.getElementById('task-creation');

    // Validate the user's session
    const validateSession = () => {
        fetch('validate_session.php')
            .then((response) => response.json())
            .then((data) => {
                if (data.status === 'error') {
                    alert(data.message); // Alert user about session expiration
                    window.location.href = 'login.html'; // Redirect to login page
                } else {
                    sessionStorage.setItem('role', data.role); // Store role for frontend logic
                    initializeDashboard(data.role); // Initialize the dashboard based on role
                }
            })
            .catch(() => {
                alert('Failed to validate session. Please try again.');
                window.location.href = 'login.html';
            });
    };

    // Initialize the dashboard
    const initializeDashboard = (role) => {
        // Show "Create Task" link for Admins and Managers
        if (role === 'Admin' || role === 'Manager') {
            document.getElementById('create-task-link').style.display = 'block';
        }

        // Fetch and display tasks
        fetchTasks();
    };

    // Show task creation form for Admin and Manager
    if (role === 'Admin' || role === 'Manager') {
        taskCreationForm.style.display = 'block';
    }

    // Ensure the role exists and is valid
    if (!role) {
        console.error('User role not found. Please log in again.');
        alert('Your session has expired. Please log in again.');
        window.location.href = 'login.html'; // Redirect to login page
        return;
    }

    // Show the "Create Task" link for Admins and Managers
    if (role === 'Admin' || role === 'Manager') {
        document.getElementById('create-task-link').style.display = 'block';
    }

    

    // Update task status after drag-and-drop
    const updateTaskStatus = (taskId, newStatus) => {
        fetch('update_task.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ task_id: taskId, status: newStatus }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Task status updated!');
                    fetchTasks(); // Refresh tasks
                } else {
                    alert('Failed to update task status');
                }
            })
            .catch(() => alert('An error occurred while updating task status'));
    };
    

    // Dragging within columns
    const columns = document.querySelectorAll('.task-column');
    columns.forEach(column => {
        column.addEventListener('dragover', e => {
            e.preventDefault();
        });
        column.addEventListener('drop', e => {
            const draggingTask = document.querySelector('.dragging');
            const newStatus = column.getAttribute('data-status');
            draggingTask.dataset.status = newStatus;
            column.querySelector('.task-list').appendChild(draggingTask);
        });
    });

    const taskForm = document.getElementById('task-form');

    // Populate assignee dropdown
    const populateAssignees = () => {
        fetch('fetch_users.php') // Create a PHP script to fetch users
            .then(response => response.json())
            .then(users => {
                const assigneeSelect = document.getElementById('assignee');
                assigneeSelect.innerHTML = ''; // Clear existing options
                users.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = user.username;
                    assigneeSelect.appendChild(option);
                });
            });
    };

    if (taskForm) {
        taskForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = new FormData(taskForm);

            fetch('create_task.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message); // Display success message
                        taskForm.reset();    // Reset the form
                        fetchTasks();        // Refresh tasks on the dashboard
                    } else {
                        alert(data.message); // Display error message
                    }
                })
                .catch(() => alert('An error occurred. Please try again.'));
        });
    }

    // Fetch and populate tasks
    const fetchTasks = () => {
        fetch('fetch_tasks.php')
            .then(response => response.json())
            .then(tasks => {
                const columns = document.querySelectorAll('.task-list');
                columns.forEach(col => col.innerHTML = ''); // Clear columns
                tasks.forEach(task => {
                    const taskElement = document.createElement('div');
                    taskElement.classList.add('task');
                    taskElement.setAttribute('draggable', 'true');
                    taskElement.dataset.id = task.id;
                    taskElement.dataset.status = task.status;
                    taskElement.innerHTML = `
                        <h3>${task.title}</h3>
                        <p>${task.description}</p>
                        <p>Deadline: ${task.deadline}</p>
                        ${role === 'Admin' || role === 'Manager' ? 
                        `<button onclick="editTask(${task.id})">Edit</button>
                         <button onclick="deleteTask(${task.id})">Delete</button>` 
                        : ''}
                    `;
                    document.querySelector(`[data-status="${task.status}"] .task-list`).appendChild(taskElement);

                    // Dragging functionality
                    taskElement.addEventListener('dragstart', () => {
                        taskElement.classList.add('dragging');
                    });
                    taskElement.addEventListener('dragend', () => {
                        taskElement.classList.remove('dragging');
                        updateTaskStatus(taskElement.dataset.id, taskElement.dataset.status);
                    });
                });
            });
    };

    // Handle task deletion
    window.deleteTask = (taskId) => {
        if (confirm('Are you sure you want to delete this task?')) {
            fetch('delete_task.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ task_id: taskId })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message); // Show success/error message
                    if (data.status === 'success') {
                        fetchTasks(); // Refresh tasks
                    }
                })
                .catch(() => alert('An error occurred. Please try again.'));
        }
    };

    const loginForm = document.querySelector('form'); // Ensure this matches your login form selector

    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault(); // Prevent default form submission

            const formData = new FormData(loginForm);

            fetch('login.php', {
                method: 'POST',
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === 'success') {
                        // Store role and user info in sessionStorage for later use
                        sessionStorage.setItem('role', data.role);
                        alert(data.message); // Optional success message
                        window.location.href = 'dashboard.html'; // Redirect to dashboard
                    } else {
                        alert(data.message); // Display error messages
                    }
                })
                .catch(() => {
                    alert('An error occurred. Please try again.');
                });
        });
    }

    // Fetch tasks initially
    populateAssignees();
    fetchTasks();
});
