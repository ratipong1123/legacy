<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        .price-info {
            display: none;
            margin-top: 20px;
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
            <a class="nav-link active" aria-current="page" data-bs-toggle="modal" data-bs-target="#login" href="#">Calculate</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
    <br>
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mt-5">Calculate to Legacy</h1>
        <button type="button" class="btn btn-secondary" id="showPricesButton">
            <i class="fas fa-cog"></i> Show Prices
        </button>
    </div>
    <div class="price-info" id="priceInfo">
        @foreach ($prices as $price)
        <h6>ราคาของตอนนี้ {{$price->name}}: {{$price->price}}</h6>
        @endforeach
    </div>

    <form action="{{ route('calculate') }}" method="POST" id="calculateForm">
        @csrf
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Weapon</th>
                    <th>Money</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td><input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox"></td>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->weapon }}</td>
                    <td>{{ $user->money }}</td>
                </tr>
                @endforeach
            </tbody>
 
        </table>
        <div class="d-flex justify-content-center">
            {!! $users->links() !!}
        </div>
        <div class="d-flex justify-content-between align-items-center">เงินที่ต้องจ่ายทั้งหมด: {{ $totalMoney }}</div>
        <div class="form-group">
            <label for="cement">จำนวนปูน</label>
            <input type="number" class="form-control" id="cement" name="cement" required>
        </div>
        <div class="form-group">
            <label for="oil">จำนวนน้ำมัน</label>
            <input type="number" class="form-control" id="oil" name="oil" required>
        </div>
        <div class="form-group">
            <label for="electricity">จำนวนซ่อมไฟ</label>
            <input type="number" class="form-control" id="electricity" name="electricity" required>
        </div>

        <button type="submit" class="btn btn-primary">Calculate</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
document.getElementById('showPricesButton').addEventListener('click', function() {
    var priceInfo = document.getElementById('priceInfo');
    if (priceInfo.style.display === 'none' || priceInfo.style.display === '') {
        priceInfo.style.display = 'block';
    } else {
        priceInfo.style.display = 'none';
    }
});

function storeCheckboxState() {
    const selectedCheckboxes = Array.from(document.querySelectorAll('.user-checkbox'))
        .filter(cb => cb.checked)
        .map(cb => cb.value);
    localStorage.setItem('selectedCheckboxes', JSON.stringify(selectedCheckboxes));
}

function restoreCheckboxState() {
    const selectedCheckboxes = JSON.parse(localStorage.getItem('selectedCheckboxes') || '[]');
    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        if (selectedCheckboxes.includes(checkbox.value)) {
            checkbox.checked = true;
        }
    });
}

// Call restoreCheckboxState when the page loads
document.addEventListener('DOMContentLoaded', restoreCheckboxState);

// Attach event listener to checkboxes
document.querySelectorAll('.user-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', storeCheckboxState);
});

document.getElementById('calculateForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var selectedUsers = [];
    var totalMoney = 0; // เพิ่มตัวแปรเพื่อรวมยอดเงินทั้งหมด

    document.querySelectorAll('input[name="user_ids[]"]:checked').forEach(function(checkbox) {
        selectedUsers.push(checkbox.value);
        var money = parseFloat(checkbox.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.textContent); // ดึงข้อมูลยอดเงินจากตาราง
        totalMoney += money; // เพิ่มยอดเงินที่ดึงมาในการคำนวณรวม
    });

    if (selectedUsers.length === 0) {
        alert('Please select at least one user.');
        return;
    }

    var cement = parseFloat(document.getElementById('cement').value);
    var oil = parseFloat(document.getElementById('oil').value);
    var electricity = parseFloat(document.getElementById('electricity').value);

    var total = cement + oil + electricity;
    var perUser = total / selectedUsers.length;

    console.log('Selected user IDs:', selectedUsers);
    console.log('Total amount:', total);
    console.log('Amount per user:', perUser);
    console.log('Total money of selected users:', totalMoney); // แสดงยอดเงินทั้งหมดของผู้ใช้ที่เลือก

    var formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('cement', cement);
    formData.append('oil', oil);
    formData.append('electricity', electricity);
    formData.append('total', total);
    formData.append('perUser', perUser);
    formData.append('totalMoney', totalMoney); // เพิ่มยอดเงินทั้งหมดใน FormData
    formData.append('user_ids', JSON.stringify(selectedUsers));

    fetch('{{ route("calculate") }}', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    }).then(data => {
        console.log(data);
        alert('Calculation and saving completed successfully. Total price: ' + data.total_price + '. Number of users: ' + data.user_count + '. User IDs: ' + data.user_ids.join(', ') + '. Sum per user: ' + data.sum_per_user);
        localStorage.removeItem('selectedCheckboxes'); // Clear stored checkbox state after form submission
        location.reload(); 
    }).catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});

</script>
</body>
</html>
