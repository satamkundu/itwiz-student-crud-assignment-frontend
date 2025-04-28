@extends('layouts.app')

@section('content')
    @include('auth.auth-check')
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Register</h2>
        <form id="registerForm">
            <input type="text" id="name" class="w-full mb-3 p-2 border rounded" placeholder="Name" required>
            <input type="email" id="email" class="w-full mb-3 p-2 border rounded" placeholder="Email" required>
            <input type="password" id="password" class="w-full mb-3 p-2 border rounded" placeholder="Password" required>
            <input type="password" id="password_confirmation" class="w-full mb-3 p-2 border rounded"placeholder="Confirm Password" required>
            <button type="submit" class="w-full bg-green-500 text-white p-2 rounded">Register</button>
        </form>
        <p class="mt-4 text-center">
            For add student <a href="/login" class="text-blue-500 hover:text-blue-700">Log here</a>
        </p>
    </div>

    <script>
        $('#registerForm').submit(function(e) {
            e.preventDefault();
            axios.post(`${API_BASE_URL}register`, {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    password_confirmation: $('#password_confirmation').val()
                }, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(function(response) {
                    localStorage.setItem('token', response.data.data.token);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Registration Successful'
                    });
                    window.location.href = "/students";
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
                                if (error.response.data.errors.password) {
                                    errorMessage += 'Password: ' + error.response.data.errors.password.join(
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
                        text: errorMessage || 'Please check your input',
                    });
                    console.error(error.response.data);
                });
        });
    </script>
@endsection
