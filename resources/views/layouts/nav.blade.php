<nav class="bg-white shadow-lg mb-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('students.index') }}" class="text-xl font-bold">Student CRUD</a>
                </div>
            </div>
            <div class="flex items-center">
                <button id="logoutButton" class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
            </div>
        </div>
    </div>
</nav>
<script>
    $(document).ready(function() {
        
        const token = localStorage.getItem('token');
        if (!token) {
            Swal.fire({
                icon: 'warning',
                title: 'Authentication Required',
                text: 'Please login to continue',
                confirmButtonColor: '#3085d6',
            }).then(() => {
                window.location.href = "/login";
            });
        }

        $('#logoutButton').click(function() {
            const token = localStorage.getItem('token');

            // Show loading state
            Swal.fire({
                title: 'Logging out...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post(`${API_BASE_URL}logout`, {}, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                })
                .then(function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Logged out successfully!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        localStorage.removeItem('token'); // Remove token
                        window.location.href = "/login"; // Redirect to login
                    });
                })
                .catch(function(error) {
                    console.error(error.response.data);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error while logging out!',
                    });
                });
        });
    });
</script>
