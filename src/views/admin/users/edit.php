<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-body">
                    <form action="/users" method="POST" id="formEditUser">
                        <div class="form-group mb-3">
                            <label for="fullname" class="form-label auth-form-label color-gray-1 inika-regular">Nama Lengkap</label>
                            <input type="text" class="form-control poppins-regular" id="fullname" name="fullname" placeholder="Masukkan Nama Lengkap anda" value="<?= $data['fullname'] ?>">
                            <div class="mt-2">
                                <span class="text-danger error" id="fullnameError"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="username" class="form-label auth-form-label color-gray-1 inika-regular">Username</label>
                            <input type="text" class="form-control poppins-regular" id="username" name="username" placeholder="Masukkan username anda" value="<?= $data['username'] ?>">
                            <div class="mt-2">
                                <span class="text-danger error" id="usernameError"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label auth-form-label color-gray-1 inika-regular">Email</label>
                            <input type="email" class="form-control poppins-regular" id="email" name="email" placeholder="Masukkan email anda" value="<?= $data['email'] ?>">
                            <div class="mt-2">
                                <span class="text-danger error" id="emailError"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="noHP" class="form-label auth-form-label color-gray-1 inika-regular">Nomor Telepon</label>
                            <input type="number" class="form-control poppins-regular" id="noHP" name="noHP" placeholder="Masukkan nomor telepon anda" value="<?= $data['no_hp'] ?>">
                            <div class="mt-2">
                                <span class="text-danger error" id="noHPError"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="noHP" class="form-label auth-form-label color-gray-1 inika-regular">Tipe User</label>
                            <select name="userType" id="userType" class="form-control">
                                <option value="admin" <?= $data['user_type'] == 1 ? "selected" : "" ?>>Admin</option>
                                <option value="reguler" <?= $data['user_type'] == 2 ? "selected" : "" ?>>Reguler</option>
                            </select>
                            <div class="mt-2">
                                <span class="text-danger error" id="userTypeError"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="form-label auth-form-label color-gray-1 inika-regular">Password</label>
                            <input type="password" class="form-control poppins-regular" id="password" name="password" placeholder="Masukkan password">
                            <div class="mt-2">
                                <span class="text-danger error" id="passwordError"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="form-label auth-form-label color-gray-1 inika-regular">Konfirmasi Password</label>
                            <input type="password" class="form-control poppins-regular" id="konfirmasiPassword" name="konfirmasiPassword" placeholder="Masukkan konfirmasi password">
                            <div class="mt-2">
                                <span class="text-danger error" id="konfirmasiPasswordError"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit"  class="color-bg-green-1 btn text-white rounded" style="border-radius: 15px !important;">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    async function edit(e) {
            e.preventDefault();
            clearError();
            let request = new Request();
            let editInputElements = document.querySelectorAll("#formEditUser input, #formEditUser select")
            let data = {};
            let validator = new Validator()
            let dataValidate = {
                'fullname': 'required',
                'username': 'required|max:32',
                'email': 'required|validEmail',
                'noHP': 'required|phoneNumber',
                'userType': 'required',
                'konfirmasiPassword': 'matches[password]',
            };
            validator.setInputName({
                'username': "Username",
                'fullname': "Nama Lengkap",
                'email': "Email",
                'password': "Password",
                'userType': 'Tipe User',
                'konfirmasiPassword': "Konfirmasi Password",
                'noHP': "Nomor HP",
            })

            let formData = new FormData();
            editInputElements.forEach((element) => {
                formData.append(element.name, element.value)
                data[element.name] = element.value
            })
            let validate = validator.validate(dataValidate, data);
            if(!validate) {
                let message = validator.getMessages()
                Object.keys(message).forEach((key) => {
                    Object.keys(message[key]).forEach((error_key) => {
                        document.querySelector(`#${key}Error`).innerText = message[key][error_key]
                    })
                })
                return
            }
            showLoading();
            var response;
            try {
                request.setUrl('/users/edit/<?php $user_id = $data['id']; echo $user_id; ?>').setMethod('POST').setData(formData);
                response = await request.makeFormRequest();
                hideLoading();
                if(response['code'] == 200) {
                    showToast(response['message'], 'success');
                }
                else {
                    showAlert(response['message'], 'warning');
                }
            }
            catch (error) {
                hideLoading();
                showAlert(response['message'], 'error')
            }
        }
        document.getElementById("formEditUser").addEventListener('submit', edit);
        $(window).on('ready', function() {
            $('input.form-control').on('keydown', (event) => {
                if(event.key == 'Enter') {
                    edit();
                }
            })
        })
</script>