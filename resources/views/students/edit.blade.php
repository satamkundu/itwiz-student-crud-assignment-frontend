@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Edit Student</h2>
    <form id="editStudentForm">
        <input type="text" id="name" class="w-full mb-3 p-2 border rounded" placeholder="Name" required>
        <input type="email" id="email" class="w-full mb-3 p-2 border rounded" placeholder="Email" required>
        <input type="text" id="phone" class="w-full mb-3 p-2 border rounded" placeholder="Phone" required>
        <button type="submit" class="w-full bg-yellow-500 text-white p-2 rounded">Update</button>
    </form>
</div>

<script>
const API_BASE_URL = "http://127.0.0.1:8000/api";

$(document).ready(function(){
    const token = localStorage.getItem('token');
    const id = window.location.pathname.split('/')[2]; // extract id from URL

    // Pre-fill the form
    axios.get(`${API_BASE_URL}/students/${id}`, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    })
    .then(function(response){
        const student = response.data;
        $('#name').val(student.name);
        $('#email').val(student.email);
        $('#phone').val(student.phone);
    })
    .catch(function(error){
        alert('Failed to load student.');
        console.error(error.response.data);
    });

    $('#editStudentForm').submit(function(e){
        e.preventDefault();

        axios.put(`${API_BASE_URL}/students/${id}`, {
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
            alert('Student updated successfully!');
            window.location.href = "/students";
        })
        .catch(function(error){
            alert('Failed to update student.');
            console.error(error.response.data);
        });
    });
});
</script>
@endsection
