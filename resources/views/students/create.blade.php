@extends('layouts.app')

@section('content')
    @include('layouts.nav')
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between mb-4">
            <h2 class="text-2xl font-bold">Students</h2>
            <button onclick="window.location.href='/students/'" class="bg-green-500 text-white px-4 py-2 rounded">View All
                Student</button>
        </div>
        <form id="createStudentForm">
            <input type="text" id="name" class="w-full mb-3 p-2 border rounded" placeholder="Name" required>
            <input type="email" id="email" class="w-full mb-3 p-2 border rounded" placeholder="Email" required>
            <input type="text" id="phone" class="w-full mb-3 p-2 border rounded" placeholder="Phone" required>
            <button type="submit" class="w-full bg-green-500 text-white p-2 rounded">Create</button>
        </form>
    </div>

    <script>
        $('#createStudentForm').submit(function(e) {
            Swal.fire({
                title: 'Submitting...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            e.preventDefault();
            const token = localStorage.getItem('token');

            axios.post(`${API_BASE_URL}students`, {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    phone: $('#phone').val()
                }, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                })
                .then(function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Student created successfully!'
                    }).then(() => {
                        window.location.href = "/students";
                    });
                })
                .catch(function(error) {
                    let errorMessage = '';
                    let errorHeader = "Oops...";
                    if (error.response) {
                        switch (error.response.status) {
                            case 401:
                                errorMessage = "Invalid email or password";
                                break;
                            case 422:
                                errorHeader = error.response.data.message;
                                if (error.response.data.errors.email) {
                                    errorMessage += 'Email: ' + error.response.data.errors.email.join(', ') +
                                        '\n';
                                }
                                if (error.response.data.errors.name) {
                                    errorMessage += 'Name: ' + error.response.data.errors.name.join(
                                        ', ');
                                }
                                if (error.response.data.errors.phone) {
                                    errorMessage += 'Phone: ' + error.response.data.errors.phone.join(
                                        ', ');
                                }
                                break;
                            case 429:
                                errorMessage = 'Too many login attempts. Please try again later';
                                break;
                            default:
                                errorMessage = 'Login failed. Please try again';
                        }
                    } else {
                        errorMessage = 'Network error. Please check your connection';
                    }
                    Swal.fire({
                        icon: "error",
                        title: errorHeader,
                        text: errorMessage || 'Failed to create student',
                    });
                    console.error(error.response.data);
                });
        });
    </script>
@endsection
