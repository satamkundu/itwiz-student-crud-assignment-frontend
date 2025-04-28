@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Register</h2>
    <form id="registerForm">
        <input type="text" id="name" class="w-full mb-3 p-2 border rounded" placeholder="Name" required>
        <input type="email" id="email" class="w-full mb-3 p-2 border rounded" placeholder="Email" required>
        <input type="password" id="password" class="w-full mb-3 p-2 border rounded" placeholder="Password" required>
        <button type="submit" class="w-full bg-green-500 text-white p-2 rounded">Register</button>
    </form>
</div>

<script>
$('#registerForm').submit(function(e){
    e.preventDefault();
    axios.post(`${API_BASE_URL}register`, {
        name: $('#name').val(),
        email: $('#email').val(),
        password: $('#password').val()
    }, {
        headers: { 'Accept': 'application/json' }
    })
    .then(function(response){
        localStorage.setItem('token', response.data.token);
        alert('Registration Successful');
        window.location.href = "/students";
    })
    .catch(function(error){
        alert('Registration Failed');
        console.error(error.response.data);
    });
});
</script>
@endsection
