<!DOCTYPE html>
<html>
<head>
    <title>Todo List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome 5 Css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    {{-- Toaster Css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    {{-- Sweet Alert2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.12/dist/sweetalert2.min.css" rel="stylesheet">

</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1>Todo List</h1>
                <div class="input-group mb-3">
                    <input type="text" id="new-todo" class="form-control" placeholder="Add a new todo">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="add-todo-btn">Add Task</button>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <td>#</td>
                        <td>Task</td>
                        <td>Status</td>
                        <td>Action</td>
                    </thead>
                    <tbody id="todos-list">

                    </tbody>
                </table>

                <button class="btn btn-primary" id="show-all-todos">Show All Todos</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    {{-- Toaster Js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- Sweet Alert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.12/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            window.showAll = false;
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            function fetchTodos() {

                var url = (showAll) ? `/get-todos/` : '/get-todos/0';

                $.get(url, function(todos) {
                    $('#todos-list').empty();
                    let sno = 0;
                    if(todos.length){
                        todos.forEach(todo => {
                            sno++
                            addTodoToList(todo , sno);
                        });
                    }
                    else{
                        $('#todos-list').html(`<tr><td colspan="4" class="text-center text-danger">No Todos Found.</td></tr>`);
                    }
                });
            }

            $('#show-all-todos').click(function(){
                showAll = true;
                fetchTodos();
            });
            
            function addTodoToList(todo, sno) {
                const todoItem = `
                  <tr data-id="${todo.id}">
                    <td>${sno}</td>
                    <td>${todo.title}</td>
                    <td><span class="badge bg-${todo.completed ? "success" : "danger"}">${todo.completed ? "Done" : "Pending"}</span></td>
                    <td>
                        ${todo.completed ? '' : '<button class="btn btn-success btn-sm todo-completed"><i class="fas fa-check-circle"></i></button>'}
                        <button class="btn btn-danger btn-sm delete-todo"><i class="fas fa-trash-alt"></i></button>
                    </td>
                  </tr>`;
                $('#todos-list').append(todoItem);
            }

            $('#add-todo-btn').on('click', function() {

                const title = $('#new-todo').val().trim();
                    $.post({
                        url: '/todo',
                        data: { title },
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        success: function(todo) {
                            fetchTodos();
                            $('#new-todo').val('');
                        },
                        error: function(error){
                            let errorMessage = JSON.parse(error.responseText)
                            toastr.error(`${errorMessage.message}`, 'Invalid Value', {timeOut: 5000});
                            console.error(errorMessage.message);
                        }
                    });
            });
            

            $('#todos-list').on('click', '.todo-completed', function() {
                const todoItem = $(this).closest('tr');
                const todoId = todoItem.data('id');

                $.ajax({
                    url: `/todo/${todoId}`,
                    type: 'PUT',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    success: function(todo) {
                        fetchTodos();
                    }
                });
            });

            $('#todos-list').on('click', '.delete-todo', function() {
                const todoItem = $(this).closest('tr');
                const todoId = todoItem.data('id');
                    
                    // Sweet Alert 
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!'
                    }).then(function (result) {
                        if(result.value){
                            $.ajax({
                                url: `/todo/${todoId}`,
                                type: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': csrfToken },
                                success: function() {
                                    fetchTodos();
                                }
                            });
                        }else{
                            Swal.fire(
                                'Failed',
                                'Could not proceed now :)',
                                'error'
                            )
                        } 
                })

            });


            // Initial fetch of todos
            fetchTodos();
        });
    </script>
</body>
</html>
