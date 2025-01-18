document.addEventListener('DOMContentLoaded', () => {
    const taskForm = document.getElementById('task-form');

    // Populate assignee dropdown
    const populateAssignees = () => {
        fetch('fetch_users.php')
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch users');
                return response.json();
            })
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
            .catch(error => alert(error.message));
    };    

    // Handle task creation
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
                    alert(data.message); // Show success/error message
                    if (data.status === 'success') {
                        taskForm.reset(); // Clear the form
                        window.location.href = 'dashboard.html'; // Redirect to dashboard
                    }
                })
                .catch(() => alert('An error occurred. Please try again.'));
        });
    }

    populateAssignees(); // Populate the assignee dropdown
});
