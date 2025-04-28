@extends('layouts.app')

@section('content')
    @include('layouts.nav')
    <div class="flex justify-between mb-4">
        <h2 class="text-2xl font-bold">Students</h2>
        <button onclick="window.location.href='/students/create'" class="bg-green-500 text-white px-4 py-2 rounded">Add
            Student</button>
    </div>

    <div class="overflow-hidden rounded-lg shadow-lg border border-gray-100">
        <div class="mb-4 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" 
                id="searchInput" 
                class="block w-full pl-10 pr-3 mb-4 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                placeholder="Search students by name, or email..."
                oninput="loadStudents(1)">
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>                   
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="studentTable" class="bg-white divide-y divide-gray-200">
                <tr>
                    <td colspan="4" class="px-6 py-4 whitespace-nowrap">
                        <div class="flex justify-center items-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-4 border-gray-200 border-t-blue-500"></div>
                            <span class="ml-3 text-sm text-gray-600">Loading students...</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Pagination Controls will be inserted here -->
<div id="paginationControls"></div>
    </div>

    <script>
        
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '/login';
            }

            let currentPage = 1;
            let perPage = 10;

            $(document).ready(function() {
                loadStudents();
            });           

            function loadStudents(page = 1) {
                const search = $('#searchInput').val();
                currentPage = page;

                axios.get(`${API_BASE_URL}students`, {
                    params: {
                        page: currentPage,
                        per_page: perPage,
                        search: search
                    },
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                }).then(function(response) {
                    const students = response.data.data;
                    const pagination = response.data.meta;

                    $('#studentTable').empty();

                    if (students.length === 0) {
                        $('#studentTable').append(`
                    <tr>
                        <td colspan="5" class="text-center p-4">No students found</td>
                    </tr>
                `);
                    } else {
                        students.forEach(student => {
                            $('#studentTable').append(`
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 text-sm">${student.id}</td>
                            <td class="px-6 py-4 text-sm">${student.name}</td>
                            <td class="px-6 py-4 text-sm">${student.email}</td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <button onclick="viewStudent(${student.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md">View</button>
                                <button onclick="editStudent(${student.id})" class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 rounded-md">Edit</button>
                                <button onclick="deleteStudent(${student.id})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md">Delete</button>
                            </td>
                        </tr>
                    `);
                        });
                    }

                    // Render pagination controls
                    renderPagination(pagination);
                }).catch(function(error) {
                    console.error(error.response?.data || error.message);
                });
            }

            function renderPagination(pagination) {
                let html = `
                    <div class="flex items-center justify-between px-6 py-4">
                        <div class="text-sm text-gray-700">
                            Showing ${(pagination.current_page - 1) * pagination.per_page + 1} to 
                            ${Math.min(pagination.current_page * pagination.per_page, pagination.total)} of 
                            ${pagination.total} results
                        </div>
                        <div class="flex space-x-2 items-center">
                `;

                // Previous button
                if (pagination.prev_page_url) {
                    html += `<button onclick="loadStudents(${pagination.current_page - 1})" class="px-3 py-1 rounded-md bg-gray-200 hover:bg-gray-300">Prev</button>`;
                } else {
                    html += `<button disabled class="px-3 py-1 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Prev</button>`;
                }

                // Numbered page links
                const maxPagesToShow = 5; // Adjust if you want more numbers
                let startPage = Math.max(pagination.current_page - Math.floor(maxPagesToShow / 2), 1);
                let endPage = startPage + maxPagesToShow - 1;

                if (endPage > pagination.last_page) {
                    endPage = pagination.last_page;
                    startPage = Math.max(endPage - maxPagesToShow + 1, 1);
                }

                for (let i = startPage; i <= endPage; i++) {
                    if (i === pagination.current_page) {
                        html += `<button disabled class="px-3 py-1 rounded-md bg-blue-600 text-white">${i}</button>`;
                    } else {
                        html += `<button onclick="loadStudents(${i})" class="px-3 py-1 rounded-md bg-gray-200 hover:bg-gray-300">${i}</button>`;
                    }
                }

                // Next button
                if (pagination.next_page_url) {
                    html += `<button onclick="loadStudents(${pagination.current_page + 1})" class="px-3 py-1 rounded-md bg-gray-200 hover:bg-gray-300">Next</button>`;
                } else {
                    html += `<button disabled class="px-3 py-1 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Next</button>`;
                }

                html += `</div></div>`;

                $('#paginationControls').html(html);
            }

            function viewStudent(id) {
                $('body').append(`
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded-lg">
                            <div class="flex justify-center items-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                                <span class="ml-2">Loading student details...</span>
                            </div>
                        </div>
                    </div>
                `);

                $.ajax({
                    url: `${API_BASE_URL}students/${id}`,
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        $('.fixed').remove();

                        const student = response.data;
                        const modalContent = `
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">                        
                            <div class="bg-white p-6 rounded-lg w-96">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl font-bold">Student Details</h2>
                                    <button class="close-modal text-gray-500 hover:text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mb-4">
                                    <p><strong>Name:</strong> ${student.name}</p>
                                    <p><strong>Email:</strong> ${student.email}</p>
                                    <p><strong>Phone:</strong> ${student.phone}</p>
                                </div>
                                <button class="close-modal bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition duration-200">Close</button>
                            </div>
                        </div>
                    `;

                        const $modal = $(modalContent);
                        $('body').append($modal);

                        $modal.find('.close-modal').on('click', function() {
                            $modal.remove();
                        });
                    },
                    error: function(error) {
                        console.error(error.responseJSON);
                    }
                });
            }

            function editStudent(id) {
                $('body').append(`
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded-lg w-96">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold">Edit Student</h2>
                                <button onclick="document.querySelector('.fixed').remove()" class="text-gray-500 hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <form id="editStudentForm" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" id="editName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" id="editEmail" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="tel" id="editPhone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="document.querySelector('.fixed').remove()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition duration-200">Cancel</button>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition duration-200">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                `);


                // Fetch current student data
                axios.get(`${API_BASE_URL}students/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                }).then(function(response) {
                    const student = response.data.data;
                    $('#editName').val(student.name);
                    $('#editEmail').val(student.email);
                    $('#editPhone').val(student.phone);
                });

                // Handle form submission
                $('#editStudentForm').on('submit', function(e) {
                    e.preventDefault();

                    const updatedData = {
                        name: $('#editName').val(),
                        email: $('#editEmail').val(),
                        phone: $('#editPhone').val()
                    };

                    axios.put(`${API_BASE_URL}students/${id}`, updatedData, {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    }).then(function() {
                        $('.fixed').remove();
                        Swal.fire(
                            'Updated!',
                            'Student information has been updated.',
                            'success'
                        ).then(() => {
                            loadStudents(currentPage)
                        });
                    }).catch(function(error) {
                        Swal.fire(
                            'Error!',
                            'Something went wrong while updating the student.',
                            'error'
                        );
                        console.error(error.response.data);
                    });
                });
            }

            function deleteStudent(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`${API_BASE_URL}students/${id}`, {
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            }
                        }).then(function() {
                            Swal.fire(
                                'Deleted!',
                                'Student has been deleted.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        }).catch(function(error) {
                            Swal.fire(
                                'Error!',
                                'Something went wrong while deleting the student.',
                                'error'
                            );
                            console.error(error.response.data);
                        });
                    }
                });
            }
        
    </script>
@endsection
