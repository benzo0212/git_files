document.addEventListener('DOMContentLoaded', () => {
    const taskForm = document.getElementById('task-form');

    // Populate the assignee dropdown
    const populateAssignees = () => {
        fetch('fetch_users.php')
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
            })
            .catch(() => alert('Failed to fetch users'));
    };

    // Handle task creation
    if (taskForm) {
        taskForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(taskForm);

            fetch('create_task.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        taskForm.reset();
                        window.location.href = 'dashboard.html'; // Redirect to dashboard
                    } else {
                        alert(data.message);
                    }
                })
                .catch(() => alert('An error occurred while creating the task.'));
        });
    }

    populateAssignees(); // Populate the assignee dropdown on page load
});
