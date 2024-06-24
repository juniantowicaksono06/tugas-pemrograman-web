<?php require_once('./views/components/imagecropper.php'); ?>
<?php 
    $datetime1 = new DateTime($_SESSION['user_credential']['birthdate']);
    $birthdate = $datetime1->format('Y-m-d');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-body">
                    <div class="w-100">
                        <form action="/register" method="POST" id="formEditProfile">
                            <div class="form-group mb-3">
                                <label for="fullname" class="form-label auth-form-label color-gray-1 inika-regular">Nama Lengkap</label>
                                <input type="text" class="form-control poppins-regular" id="fullname" name="fullname" placeholder="Masukkan Nama Lengkap anda" value="<?= $_SESSION['user_credential']['fullname'] ?>">
                                <div class="mt-2">
                                    <span class="text-danger error" id="fullnameError"></span>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="gender" class="form-label auth-form-label color-gray-1 inika-regular">Jenis Kelamin</label>
                                <select name="gender" id="gender" class="combobox2 w-100 h-100 poppins-regular d-none">
                                    <option value="1" <?= $_SESSION['user_credential']['gender'] == 1 ? "selected" : "" ?>>Laki-laki</option>
                                    <option value="2" <?= $_SESSION['user_credential']['gender'] == 2 ? "selected" : "" ?>>Perempuan</option>
                                </select>
                                <div class="mt-2">
                                    <span class="text-danger error" id="genderError"></span>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="birthplace" class="form-label auth-form-label color-gray-1 inika-regular">Tempat Tanggal Lahir</label>
                                <div class="row">
                                    <div class="col-6">
                                        <select name="birthplace" id="birthplace" class="combobox2 w-100 h-100 poppins-regular">
                                            <?php foreach($provinces as $province): ?>
                                                <optgroup label="<?= $province['name'] ?>">
                                                    <?php foreach($cities as $city): ?>
                                                        <?php if($city['id_province'] === $province['id']): ?>
                                                            <option value="<?= $city['id'] ?>" <?= $_SESSION['user_credential']['birthplace'] == $city['id'] ? "selected" : "" ?>><?= $city['city_name'] ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="mt-2">
                                            <span class="text-danger error" id="birthplaceError"></span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-outline datepicker-with-limits" data-mdb-format="yyyy-mm-dd">
                                            <input type="text" class="form-control" id="birthdate" name="birthdate" value="<?= $birthdate ?>" />
                                        </div>
                                        <div class="mt-2">
                                            <span class="text-danger error" id="birthdateError"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email" class="form-label auth-form-label color-gray-1 inika-regular">Email</label>
                                <input type="email" class="form-control poppins-regular" id="email" name="email" placeholder="Masukkan email anda" value="<?= $_SESSION['user_credential']['email'] ?>">
                                <div class="mt-2">
                                    <span class="text-danger error" id="emailError"></span>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="noHP" class="form-label auth-form-label color-gray-1 inika-regular">Nomor Telepon</label>
                                <input type="number" class="form-control poppins-regular" id="noHP" name="noHP" placeholder="Masukkan nomor telepon anda" value="<?= $_SESSION['user_credential']['no_hp'] ?>">
                                <div class="mt-2">
                                    <span class="text-danger error" id="noHPError"></span>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label auth-form-label color-gray-1 inika-regular">Password</label>
                                <input type="password" class="form-control poppins-regular" id="password" name="password" placeholder="Masukkan password anda">
                                <div class="mt-2">
                                    <span class="text-danger error" id="passwordError"></span>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label auth-form-label color-gray-1 inika-regular">Konfirmasi Password</label>
                                <input type="password" class="form-control poppins-regular" id="konfirmasiPassword" name="konfirmasiPassword" placeholder="Masukkan konfirmasi password anda">
                                <div class="mt-2">
                                    <span class="text-danger error" id="konfirmasiPasswordError"></span>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="address" class="form-label auth-form-label color-gray-1 inika-regular">Alamat</label>
                                <textarea name="address" id="address" rows="3" class="form-control poppins-regular" style="resize: none;" placeholder="Masukkan alamat anda"><?= $_SESSION['user_credential']['alamat'] ?></textarea>
                                <div class="mt-2">
                                    <span class="text-danger error" id="addressError"></span>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3 px-2">
                                <div class="input-group position-relative">
                                    <div class="photo-upload-overlay">
                                        <div class="d-flex justify-content-center align-items-center h-100">
                                            <div class="text-center">
                                            <span class="text-center"><i class="fa fa-camera fa-4x"></i></span>
                                            <p class="text-center mb-0">Silahkan upload atau drag gambar ke sini</p>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control photo-upload" name="profilePicture" id="editPhoto">
                                </div>
                            </div>
                            <div class="row mt-3 mb-3 px-2">
                                <div class="card">
                                    <div class="card-body">
                                        <img src="<?= $finalHost . '/' . $_SESSION['user_credential']['picture'] ?>" alt="" class="img-responsive" id="imagePreview" />
                                    </div>
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
</div>
<script>
    async function editProfile(e) {
        e.preventDefault();
        clearError();
        let request = new Request();
        let editProfileInput = document.querySelectorAll("#formEditProfile input, #formEditProfile select, #formEditProfile textarea")
        let data = {};
        let validator = new Validator()
        let dataValidate = {
            'fullname': 'required',
            'birthplace': 'required',
            'gender': "required",
            'birthdate': 'required|validDate',
            'address': 'required',
            'email': 'required|validEmail',
            'password': 'optional',
            'noHP': 'required|phoneNumber',
            'konfirmasiPassword': 'optional|matches[password]',
        };
        let imageBlob = getCroppedImageBlob()
        validator.setInputName({
            'fullname': "Nama Lengkap",
            'gender': "Jenis Kelamin",
            'birthplace': "Tempat Lahir",
            'birthdate': 'Tanggal Lahir',
            'address': 'Alamat',
            'email': "Email",
            'password': "Password",
            'konfirmasiPassword': "Konfirmasi Password",
            'noHP': "Nomor HP",
        })

        let formData = new FormData();
        editProfileInput.forEach((element) => {
            if(element.name == 'username') return
            formData.append(element.name, element.value)
            data[element.name] = element.value
        })
        if(imageBlob != null) {
            formData.append('picture', imageBlob, 'image.webp')
        }
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
            request.setUrl('/profile/edit-profile/<?= $_SESSION['user_credential']['id'] ?>').setMethod('POST').setData(formData);
            response = await request.makeFormRequest();
            hideLoading();
            if(response['code'] == 200) {
                showToast(response['message'], 'success', () => {
                    window.location.href = window.location.pathname
                });
            }
            else if(response['code'] == 201) {
                showAlert(response['message'], 'success');
            }
        }
        catch (error) {
            hideLoading();
            showAlert("Gagal edit profile", 'error')
        }
    }
    document.getElementById("formEditProfile").addEventListener('submit', editProfile);
    $("#editPhoto").on('change', (event) => {
        const currentFiles = event.target.files
        if(currentFiles && currentFiles.length > 0) {
            const reader = new FileReader()
            reader.onload = (e) => {
                // loadCrop(e.target.result)
                showModal(e.target.result)
            }
            reader.readAsDataURL(currentFiles[0])
        }
    })
    $(document).ready(function() {
        var datepickerWithLimits = document.querySelector('.datepicker-with-limits'); new
        mdb.Datepicker(datepickerWithLimits, {
            min: new Date(1900, 1, 1),
            max: new Date()
        });
        $('.combobox2').select2({
            tags: true,
            width: "100%"
        });
    })
    $(window).on('ready', function() {
        setOnModalClose(() => {
            $("#imagePreview").attr('src', getPreviewImageUrl())
        })
        $('input.form-control').on('keydown', (event) => {
            if(event.key == 'Enter') {
                editProfile();
            }
        })
    })
</script>