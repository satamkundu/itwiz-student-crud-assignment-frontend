@extends('layouts.app')

@section('content')
    @include('auth.auth-check')
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Login</h2>
        <form id="loginForm">
            <input type="email" id="email" class="w-full mb-3 p-2 border rounded" placeholder="Email" required>
            <input type="password" id="password" class="w-full mb-3 p-2 border rounded" placeholder="Password" required>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">Login</button>
        </form>
    </div>

    <script>
        $('#loginForm').submit(function(e) {
            Swal.fire({
                title: 'Logging in...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            e.preventDefault();
            axios.post(`${API_BASE_URL}login`, {
                    email: $('#email').val(),
                    password: $('#password').val()
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
                        text: 'Login Successful',
                        timer: 1500,
                        showConfirmButton: false
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
                                    errorMessage += 'Email: ' + error.response.data.errors.email.join(', ') + '\n';
                                }
                                if (error.response.data.errors.password) {
                                    errorMessage += 'Password: ' + error.response.data.errors.password.join(', ');
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
                });
        });
    </script>
@endsection
