<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-body">
                    <?php require_once('./views/components/loading.php'); ?>
                    <?php require_once('./views/components/imagecropper.php'); ?>
                    <form action="/edit" method="POST" id="formEdit">
                        <div class="form-group mb-3">
                            <label for="fullname" class="form-label auth-form-label color-gray-1 inika-regular">Nama Lengkap</label>
                            <input type="text" class="form-control poppins-regular" id="fullname" name="fullname" placeholder="Masukkan Nama Lengkap anda" value="<?= $data['fullname'] ?>">
                            <div class="mt-2">
                                <span class="text-danger error" id="fullnameError"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="gender" class="form-label auth-form-label color-gray-1 inika-regular">Jenis Kelamin</label>
                            <select name="gender" id="gender" class="combobox2 w-100 h-100 poppins-regular d-none">
                                <option value="1" <?= $data['gender'] == 1 ? "selected" : "" ?>>Laki-laki</option>
                                <option value="2" <?= $data['gender'] == 2 ? "selected" : "" ?>Perempuan</option>
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
                                                        <option value="<?= $city['id'] ?>" <?= $data['birthplace'] == $city['id'] ? "selected" : "" ?>><?= $city['city_name'] ?></option>
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
                                        <input type="text" class="form-control" id="birthdate" name="birthdate" value="<?= date('Y-m-d', strtotime($data['birthdate'])) ?>" />
                                    </div>
                                    <div class="mt-2">
                                        <span class="text-danger error" id="birthdateError"></span>
                                    </div>
                                </div>
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
                            <label for="noHP" class="form-label auth-form-label color-gray-1 inika-regular">Nomor Handphone</label>
                            <input type="number" class="form-control poppins-regular" id="noHP" name="noHP" placeholder="Masukkan nomor handphone anda" value="<?= $data['no_hp'] ?>">
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
                            <textarea name="address" id="address" rows="3" class="form-control poppins-regular" style="resize: none;" placeholder="Masukkan alamat anda"><?= $data['alamat'] ?></textarea>
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
                        <div class="row mt-3 mb-3">
                            <div class="col-12 col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <img src="<?= getBaseURL() . '/' . $data['picture'] ?>" alt="" class="img-responsive w-100" id="imagePreview" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit"  class="color-bg-green-1 btn text-white rounded" style="border-radius: 15px !important;">Submit</button>
                            <a href="/auth/login" class="btn btn-primary text-white rounded" style="border-radius: 15px !important;">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        async function edit(e) {
            e.preventDefault();
            clearError();
            let request = new Request();
            let editInputElements = document.querySelectorAll("#formEdit input, #formEdit select, #formEdit textarea")
            let data = {};
            let validator = new Validator()
            let dataValidate = {
                'fullname': 'required',
                'birthplace': 'required',
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
                'birthplace': "Tempat Lahir",
                'birthdate': 'Tanggal Lahir',
                'address': 'Alamat',
                'email': "Email",
                'password': "Password",
                'konfirmasiPassword': "Konfirmasi Password",
                'noHP': "Nomor HP",
            })

            let formData = new FormData();
            if(imageBlob !== null) {
                formData.append('picture', imageBlob, 'image.webp')
            }
            editInputElements.forEach((element) => {
                let value = $(element).val()
                formData.append(element.name, value)
                data[element.name] = value
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
                request.setUrl('/admin/members/<?= $data['id'] ?>').setMethod('POST').setData(formData);
                response = await request.makeFormRequest();
                hideLoading();
                if(response['code'] == 200) {
                    showAlert(response['message'], 'success');
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
        var datepickerWithLimits = document.querySelector('.datepicker-with-limits'); new
        mdb.Datepicker(datepickerWithLimits, {
            min: new Date(1900, 1, 1),
            max: new Date()
        });
        $('.combobox2').select2({
            tags: true,
            width: "100%"
        });
        document.getElementById("formEdit").addEventListener('submit', edit);
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
        
        setOnModalClose(() => {
            $("#imagePreview").attr('src', getPreviewImageUrl())
        })
        $('input.form-control').on('keydown', (event) => {
            if(event.key == 'Enter') {
                edit();
            }
        })
    })
</script>