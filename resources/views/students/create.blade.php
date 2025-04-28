@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Add Student</h2>
    <form id="createStudentForm">
        <input type="text" id="name" class="w-full mb-3 p-2 border rounded" placeholder="Name" required>
        <input type="email" id="email" class="w-full mb-3 p-2 border rounded" placeholder="Email" required>
        <input type="text" id="phone" class="w-full mb-3 p-2 border rounded" placeholder="Phone" required>
        <button type="submit" class="w-full bg-green-500 text-white p-2 rounded">Create</button>
    </form>
</div>

<script>
const API_BASE_URL = "http://127.0.0.1:8000/api";

$('#createStudentForm').submit(function(e){
    e.preventDefault();
    const token = localStorage.getItem('token');

    axios.post(`${API_BASE_URL}/students`, {
        name: $('#name').val(),
        email: $('#email').val(),
        phone: $('#phone').val()
    }, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    })
    .then(function(response){
        alert('Student created successfully!');
        window.location.href = "/students";
    })
    .catch(function(error){
        alert('Failed to create student.');
        console.error(error.response.data);
    });
});
</script>
@endsection
