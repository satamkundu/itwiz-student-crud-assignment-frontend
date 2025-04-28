@extends('layouts.app')

@section('content')
<div class="flex justify-between mb-4">
    <h2 class="text-2xl font-bold">Students</h2>
    <button onclick="window.location.href='/students/create'" class="bg-green-500 text-white px-4 py-2 rounded">Add Student</button>
</div>

<table class="w-full bg-white rounded shadow">
    <thead>
        <tr class="bg-gray-200">
            <th class="p-2">ID</th>
            <th class="p-2">Name</th>
            <th class="p-2">Email</th>
            <th class="p-2">Phone</th>
            <th class="p-2">Actions</th>
        </tr>
    </thead>
    <tbody id="studentTable"></tbody>
</table>

<script>
$(document).ready(function(){
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = '/login';
    }

    axios.get(`${API_BASE_URL}students`, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    }).then(function(response){
        let students = response.data;
        $.each(students, function(index, student){
            $('#studentTable').append(`
                <tr>
                    <td class="border p-2">${student.id}</td>
                    <td class="border p-2">${student.name}</td>
                    <td class="border p-2">${student.email}</td>
                    <td class="border p-2">${student.phone}</td>
                    <td class="border p-2">
                        <button onclick="viewStudent(${student.id})" class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                        <button onclick="editStudent(${student.id})" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                        <button onclick="deleteStudent(${student.id})" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                    </td>
                </tr>
            `);
        });
    }).catch(function(error){
        console.error(error.response.data);
    });
});

function viewStudent(id) {
    window.location.href = `/students/${id}`;
}

function editStudent(id) {
    window.location.href = `/students/${id}/edit`;
}

function deleteStudent(id) {
    if (confirm('Are you sure to delete this student?')) {
        const token = localStorage.getItem('token');
        axios.delete(`${API_BASE_URL}students/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        }).then(function(){
            alert('Student deleted!');
            window.location.reload();
        }).catch(function(error){
            console.error(error.response.data);
        });
    }
}
</script>
@endsection
