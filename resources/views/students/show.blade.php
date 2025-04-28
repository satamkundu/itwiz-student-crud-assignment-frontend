@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Student Details</h2>
    <div id="studentDetails" class="space-y-2"></div>

    <button onclick="window.location.href='/students'" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">Back</button>
</div>

<script>
const API_BASE_URL = "http://127.0.0.1:8000/api";

$(document).ready(function(){
    const token = localStorage.getItem('token');
    const id = window.location.pathname.split('/').pop(); // extract id from URL

    axios.get(`${API_BASE_URL}/students/${id}`, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    })
    .then(function(response){
        const student = response.data;
        $('#studentDetails').html(`
            <p><strong>Name:</strong> ${student.name}</p>
            <p><strong>Email:</strong> ${student.email}</p>
            <p><strong>Phone:</strong> ${student.phone}</p>
        `);
    })
    .catch(function(error){
        alert('Failed to fetch student.');
        console.error(error.response.data);
    });
});
</script>
@endsection
