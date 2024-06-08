<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .pagination {
            justify-content: center;
        }
        .pagination li {
            margin: 0 5px;
        }
        .pagination li a, .pagination li span {
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
            color: #007bff;
            text-decoration: none;
        }
        .pagination li.active span {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Legacy</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{ url('/') }}">Home</a>
        </li>
        
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ url('/member') }}">Member</a> 
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ url('/calculate') }}">Calculate</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

    <div class="container">
        <h1 class="mt-5">Member to Legacy</h1>
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">เพิ่มข้อมูล</button>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Weapon</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->weapon }}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm editBtn" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-weapon="{{ $user->weapon }}" data-toggle="modal" data-target="#editModal">แก้ไข</button>
                        </td>
                    </tr>
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">แก้ไขข้อมูล</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('member.edit', ['id' => $user->id]) }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label for="editName">Name</label>
                                <input type="text" class="form-control" id="editName" name="name" value="{{ $user->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="editWeapon">Weapon</label>
                                <input type="text" class="form-control" id="editWeapon" name="weapon" value="{{ $user->weapon }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">เพิ่มข้อมูล</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('member.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="weapon">Weapon</label>
                                <input type="text" class="form-control" id="weapon" name="weapon" required>
                            </div>
                            <button type="submit" class="btn btn-primary">เพิ่ม</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {!! $users->links() !!}
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    $(document).ready(function(){
        $('.editBtn').click(function(){
            var id = $(this).data('id');
            var name = $(this).data('name');
            var weapon = $(this).data('weapon');
            $('#editModal form').attr('action', '/member/' + id);
            $('#editName').val(name);
            $('#editWeapon').val(weapon);
        });
    });
</script>

</body>
</html>
