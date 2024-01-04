@extends('template.app')
@section('main-section')
<div class="container mt-4">
        <div class="d-flex justify-content-between">
            <div class=""><h3>Register New User</h3></div>
            <div class=""><button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addNewRecord">+ Add New Record</button></div>
        </div>
        <hr/>
        <div class="row" id="successalert">

        </div>
        <table class="table table-bordered table-stripped" id="tableAjax">
            <thead>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Role</th>
                <th>Description</th>
                <th>Image</th>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
</div>
<div class="modal fade" id="addNewRecord" tabindex="-1" aria-labelledby="addNewRecordLabel" aria-hidden="true">
    <form id="formSubmitID"  method="post" enctype="multipart/form-data">
        @csrf
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addNewRecordLabel">Add New Record</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="container">
                <div class="row " id="alertContainer"></div>
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your name">
                        <span id="nameError" style="color: red;"></span>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone number" onkeyup="validatePhoneNumber()">
                        <span id="phoneError" style="color: red;"></span>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label>Email</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Email" onkeyup="checkEmailIsValid()">
                        <span id="emailError" style="color: red;"></span>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label>Select Role</label>
                        <select name="role" class="form-control">
                            <option value="">Select Option</option>
                        @php
                        $role = App\Models\Role::pluck('name','id')->toArray();
                            if(isset($role))
                            {
                                foreach ($role as $key => $value) {
                               @endphp
                                      <option value="{{ $key }}">{{ $value }}</option>
                               @php
                                 }
                            }

                        @endphp
                        </select>
                        <span id="roleError" style="color: red;"></span>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label>Profile Image</label>
                        <input type="file" name="profile_pic" class="form-control form-control-file" accept="image/*" placeholder="Upload Profile Image" >
                        <span id="imageError" style="color: red;"></span>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" id="" cols="30" rows="3"></textarea>
                        <span id="descriptionError" style="color: red;"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input type="submit" name="submit" value="Submit" class="btn btn-primary  submitbutton">
          <button class="btn btn-danger  disabledBtn" disabled style="display: none;">Progressing...</button>
        </div>
      </div>
    </div>
    </form>
  </div>
@section('js-script')
<script>

    var baseUrlApi = "{{ URL::to('/api/')}}";
    fetchDataAndPopulateTable();
    let formSubmitButton;
    function validatePhoneNumber(){
        var phoneNumberInput = document.getElementById("phone");
        var phoneError = document.getElementById("phoneError");
        phoneError.textContent = "";
        var phoneNumber = phoneNumberInput.value;
        var regex = /^[6789]\d{9}$/;
        if (!regex.test(phoneNumber)) {
            phoneError.textContent = "Invalid phone number";
            formSubmitButton = false;
        }else{
            formSubmitButton = true;
        }

    }


    async function fetchDataAndPopulateTable() {
            const apiUrl = baseUrlApi+'/users-list';
            $.LoadingOverlay("show");
            try {
                const response = await fetch(apiUrl);
                const dataResponse = await response.json();
                var data = dataResponse.data;
                if (data && data.length > 0) {
                    const tableBody = document.getElementById('tableBody');
                    tableBody.innerHTML = '';
                    data.forEach((item, index) => {
                        console.log('item',item)
                        const row = tableBody.insertRow();

                        const img = document.createElement('img');
                        img.src = item.profile_img; // Assuming 'profile_img' is the property in your data
                        img.alt = 'Profile Image';
                        img.style.width = '50px'; // Adjust the width as needed



                        row.insertCell(0).textContent = index + 1;
                        row.insertCell(1).appendChild(img);
                        row.insertCell(1).textContent = item.name;
                        row.insertCell(2).textContent = item.email;
                        row.insertCell(3).textContent = item.phone;
                        row.insertCell(4).textContent = item.role.name;
                        row.insertCell(5).textContent = item.description;

                    });
                } else {
                    console.error('No data received from the API');
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }finally{
                $.LoadingOverlay("hide");
            }
        }
    function checkEmailIsValid(){
        var emailInput = document.getElementById("email");
        var emailError = document.getElementById("emailError");
        emailError.textContent = "";
        var emailInputValue = emailInput.value;
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regex.test(emailInputValue)) {
            emailError.textContent = "Invalid email address";
            formSubmitButton = false;
        }else{
            formSubmitButton = true;
        }
    return regex.test(email);
    }
    var myModal = new bootstrap.Modal(document.getElementById('addNewRecord'), {
  keyboard: false
})
  var mySubmitFormId = document.getElementById('formSubmitID');

  mySubmitFormId.addEventListener('submit', async function (event) {
    var progressBar = document.getElementById('disabledBtn');
    var submitButton = document.getElementById('submitbutton');
    event.preventDefault();
    var roleError = document.getElementById("roleError");
    var imageError = document.getElementById("imageError");
    var descriptionError = document.getElementById("descriptionError");
    var phoneError = document.getElementById("phoneError");
    var emailError = document.getElementById("emailError");
    var nameError = document.getElementById("nameError");
    roleError.textContent = "";
    imageError.textContent = "";
    descriptionError.textContent = "";
    var name = mySubmitFormId.elements.name.value;
    var email = mySubmitFormId.elements.email.value;
    var phone = mySubmitFormId.elements.phone.value;
    var role = mySubmitFormId.elements.role.value;
    var description = mySubmitFormId.elements.description.value;

    var errorMessages = [];
    if (!name.trim()) {
        errorMessages.push('Name cannot be empty');
    }

    if (!email.trim()) {
        errorMessages.push('Email cannot be empty');
    }

    if (!phone.trim()) {
        errorMessages.push('Phone cannot be empty');
    }

    if (!role.trim()) {
        errorMessages.push('Role cannot be empty');
    }
    if (mySubmitFormId.elements.profile_pic.files.length === 0) {
        errorMessages.push('Please select file!');
    }
    if (!description.trim()) {
        errorMessages.push('Description cannot be empty');
    }

    if (errorMessages.length > 0) {
        showAlert('danger', errorMessages.join('<br>'));
        return;
    }

    var imageFileData = mySubmitFormId.elements.profile_pic.files[0];
    var formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('phone', phone);
    formData.append('description', description);
    formData.append('role', role);

    if (imageFileData) {
        formData.append('image', imageFileData, imageFileData.name);
    }

    try {
        const response = await fetch(baseUrlApi + '/user-save', {
            method: 'POST',
            headers: {},
            body: formData
        });

        const apiResponse = await response.json();
        console.log('apiResponse',apiResponse)
        if(apiResponse.status === 'success')
        {
            console.log("API Response:", apiResponse.message);

            myModal.hide();
            fetchDataAndPopulateTable();
            var successContainer = document.getElementById('successalert');
            successContainer.innerHTML = `<div class="col-md-12"><div class="alert alert-success">${apiResponse.message}</div></div>`;
            mySubmitFormId.reset();
            setTimeout(function () {
                successContainer.innerHTML = '';
            }, 5000);
        }else{
            setTimeout(function () {
                var alertContainer = document.getElementById('alertContainer');
                alertContainer.innerHTML = `<div class="col-md-12"><div class="alert alert-danger">${apiResponse.message}</div></div>`;

                setTimeout(function () {
                    alertContainer.innerHTML = '';
                }, 5000);
        }, 4000);
        }

    } catch (error) {
        setTimeout(function () {
            showAlert('danger', error.message);
        }, 4000);
    }
});


  function showAlert(type, message) {
    var alertContainer = document.getElementById('alertContainer');
    var alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-' + type;
    alertDiv.innerHTML = message;

    alertContainer.appendChild(alertDiv);
    window.scrollTo({ top: 0, behavior: 'smooth' });
    setTimeout(function () {
        alertContainer.removeChild(alertDiv);
    }, 3000);
}
   </script>
@endsection
@endsection
