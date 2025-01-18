document.addEventListener('DOMContentLoaded', () => {
    const currentPage = window.location.pathname.split('/').pop();  // Get the current page

    // Run session validation only on the dashboard page
    if (currentPage === 'dashboard.html') {
        validateSession();
    }

    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();  // Prevent default form submission

            const formData = new FormData(loginForm);

            fetch('login.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        sessionStorage.setItem('role', data.role);  // Store role for frontend logic
                        window.location.href = 'dashboard.html';  // Redirect to the dashboard
                    } else {
                        alert(data.message);  // Show error message
                    }
                })
                .catch(() => alert('An error occurred. Please try again.'));
        });
    }

    // Function to validate session on the dashboard
    function validateSession() {
        fetch('validate_session.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Session validation failed.');  // Handle HTTP errors
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'error') {
                    alert(data.message);
                    window.location.href = 'login.html';  // Redirect to login if session is invalid
                } else {
                    sessionStorage.setItem('role', data.role);  // Store role in sessionStorage
                    fetchTasks();  // Fetch tasks if the session is valid
                }
            })
            .catch(error => {
                console.error('Error:', error.message);
                alert('Failed to validate session. Please log in again.');
                window.location.href = 'login.html';  // Redirect on validation failure
            });
    }

    // Fetch tasks from the server
    const fetchTasks = () => {
        fetch('fetch_tasks.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch tasks from the server.');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'error') {
                    throw new Error(data.message || 'Failed to fetch tasks.');
                }

                const tasks = data.tasks;
                const columns = document.querySelectorAll('.task-list');
                columns.forEach(column => (column.innerHTML = '')); // Clear columns

                const role = sessionStorage.getItem('role'); // Get the user's role from sessionStorage

                tasks.forEach(task => {
                    const taskElement = document.createElement('div');
                    taskElement.classList.add('task');
                    taskElement.innerHTML = `
                        <h3>${task.title}</h3>
                        <p>${task.description}</p>
                        <p>Deadline: ${task.deadline}</p>
                        ${role === 'Admin' || role === 'Manager' ? `
                            <button onclick="editTask(${task.id})">Edit</button>
                            <button onclick="deleteTask(${task.id})">Delete</button>
                        ` : ''}
                    `;
                    document.querySelector(`[data-status="${task.status}"] .task-list`).appendChild(taskElement);
                });
            })
            .catch(error => {
                console.error('Error:', error.message);
                alert('Error: ' + error.message);
            });
    };

    fetchTasks(); // Fetch tasks when the page loads

    // Edit Task Function
    window.editTask = (taskId) => {
        // Implement edit functionality or open an edit modal
        alert(`Edit functionality for task ${taskId} is under development.`);
    };

        // Function to open the Edit Task Modal
    window.editTask = (taskId) => {
        document.getElementById('edit-task-modal').style.display = 'block';
        // Fetch task details and populate the form for editing (you need to implement fetchTaskDetails(taskId))
    };

    function closeEditModal() {
        document.getElementById('edit-task-modal').style.display = 'none';
    }


    // Delete Task Function
    window.deleteTask = (taskId) => {
        if (confirm('Are you sure you want to delete this task?')) {
            fetch('delete_task.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ task_id: taskId }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        fetchTasks(); // Refresh tasks after deletion
                    } else {
                        alert('Failed to delete task: ' + data.message);
                    }
                })
                .catch(() => alert('An error occurred while deleting the task.'));
        }
    };

    const logoutButton = document.getElementById('logout-button');

    if (logoutButton) {
        logoutButton.addEventListener('click', () => {
            fetch('logout.php')
                .then(() => {
                    // Clear client-side session storage
                    sessionStorage.clear();
                    window.location.href = 'login.html'; // Redirect to login page
                })
                .catch(() => alert('Logout failed. Please try again.'));
        });
    }
});
