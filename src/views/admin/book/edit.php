<?php require_once('./views/components/imagecropper.php'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-body">
                    <form action="/admin/books" method="POST" id="formEditBook">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label auth-form-label color-gray-1 inika-regular">Pengarang</label>
                            <div class="w-100">
                                <select name="id_author" id="id_author" class="combobox2 w-100 h-100" multiple="multiple">
                                    <?php foreach($dataAuthors as $author): ?>
                                        <option value="<?= $author['id'] ?>"><?= $author['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mt-2">
                                <span class="text-danger error" id="id_authorError"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name" class="form-label auth-form-label color-gray-1 inika-regular">Penerbit</label>
                            <div class="w-100">
                                <select name="id_publisher" id="id_publisher" class="combobox2 w-100 h-100">
                                    <?php foreach($dataPublishers as $publisher): ?>
                                        <option value="<?= $publisher['id'] ?>" <?= $dataEdit['book']['id_publisher'] == $publisher['id'] ? 'selected' : '' ?>><?= $publisher['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mt-2">
                                <span class="text-danger error" id="id_publisherError"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name" class="form-label auth-form-label color-gray-1 inika-regular">Kategori</label>
                            <div class="w-100">
                                <select name="id_category" id="id_category" class="combobox2 w-100 h-100" multiple="multiple">
                                    <?php foreach($dataCategories as $category): ?>
                                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mt-2">
                                <span class="text-danger error" id="id_categoryError"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name" class="form-label auth-form-label color-gray-1 inika-regular">Nama Buku</label>
                            <input type="text" class="form-control poppins-regular" id="name" name="name" placeholder="Masukkan Nama Buku" value="<?= $dataEdit['book']['title'] ?>">
                            <div class="mt-2">
                                <span class="text-danger error" id="nameError"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="barcode" class="form-label auth-form-label color-gray-1 inika-regular">Barcode</label>
                            <input type="text" class="form-control poppins-regular" id="barcode" name="barcode" placeholder="Masukkan Barcode" value="<?= $dataEdit['book']['barcode'] ?>">
                            <div class="mt-2">
                                <span class="text-danger error" id="barcodeError"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="published_year" class="form-label color-gray-1 inika-regular">Tahun terbit:</label>
                            <div class="input-group">
                                <input type="text" name="published_year" id="published_year" class="form-control" value="<?= $dataEdit['book']['published_year'] ?>" />
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div> 
                        <div class="mt-2">
                            <span class="text-danger error" id="published_yearError"></span>
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
                                <input type="file" class="form-control photo-upload" name="bookPhoto" id="bookPhoto">
                            </div>
                        </div>
                        <div class="row mt-3 mb-3">
                            <div class="col-12 col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <img src="<?= $finalHost . '/' . $dataEdit['book']['picture'] ?>" alt="" class="img-responsive w-100" id="imagePreview" />
                                    </div>
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
<script>
    async function edit(e) {
            e.preventDefault();
            clearError();
            let request = new Request();
            let editInputElements = document.querySelectorAll("#formEditBook input, #formEditBook select")
            let data = {};
            let validator = new Validator()
            let dataValidate = {
                'name': 'required|max:64',
                'id_author': "required",
                'id_publisher': 'required',
                'id_category': 'required',
                'published_year': 'required|numeric',
                'barcode': 'required|min:8',
            };
            validator.setInputName({
                'name': "Nama Buku",
                'id_author': "Pengarang",
                'id_publisher': 'Penerbit',
                'id_category': "Kategori",
                'published_year': "Tanggal Terbit",
                'barcode': 'Barcode'
            })

            let formData = new FormData();
            let imageBlob = getCroppedImageBlob()
            if(imageBlob != null) {
                formData.append('picture', imageBlob, 'image.webp')
            }
            editInputElements.forEach((element) => {
                var value = element.value
                if(element.name == 'id_category' || element.name == 'id_author') {
                    if(value != "") {
                        value = JSON.stringify($(element).val());
                    }
                }
                data[element.name] = value
                formData.append(element.name, value)
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
                request.setUrl('/admin/books/<?= $dataEdit['book']['id'] ?>').setMethod('POST').setData(formData);
                response = await request.makeFormRequest();
                hideLoading();
                if(response['code'] == 201) {
                    // editInputElements.forEach((element) => {
                    //     element.value = ""
                    // })
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
        $(document).ready(function() {
            setCroppedImageWidth(720)
            setCroppedImageHeight(1280)
            setCropAspectRatio(9 / 16)
            $("#id_category").val(<?= json_encode($dataEdit['bookCategory']) ?>)
            $("#id_author").val(<?= json_encode($dataEdit['bookAuthor']) ?>)
            $('.combobox2').select2({
                tags: true,
                width: "100%"
            });
            // $("#published_year").datepicker({
            //     dateFormat: "yy-mm-dd",
            //     minDate: 0, // Today
            //     maxDate: "+7d" // 7 days from today
            // });
            // $("#published_year").datepicker({
            //     dateFormat: "yy-mm-dd",
            //     maxDate: "+0d" // 7 days from today
            // });
        });
        $("#bookPhoto").on('change', (event) => {
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
        document.getElementById("formEditBook").addEventListener('submit', edit);
        $(window).on('ready', function() {
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