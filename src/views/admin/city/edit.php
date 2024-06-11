<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-body">
                    <form action="#" method="PUT" id="formEditCity">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label auth-form-label color-gray-1 inika-regular">Provinsi</label>
                            <div class="w-100">
                                <select name="id_province" id="id_province" class="combobox2 w-100 h-100">
                                    <?php foreach($dataProvince as $province): ?>
                                        <option value="<?= $province['id'] ?>" <?= $province['id'] == $dataCity['id_province'] ? "selected" : "" ?>><?= $province['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mt-2">
                                <span class="text-danger error" id="id_provinceError"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name" class="form-label auth-form-label color-gray-1 inika-regular">Nama Kota</label>
                            <input type="text" class="form-control poppins-regular" id="name" name="name" placeholder="Masukkan Nama Kota" value="<?= $dataCity['name']; ?>">
                            <div class="mt-2">
                                <span class="text-danger error" id="nameError"></span>
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
            let createInputElements = document.querySelectorAll("#formEditCity input, #formEditCity select")
            let data = {};
            let validator = new Validator()
            let dataValidate = {
                'name': 'required|max:64',
                'id_province': 'required',
            };
            validator.setInputName({
                'name': "Nama Kota",
                'id_province': "Provinsi"
            })

            let formData = new FormData();
            createInputElements.forEach((element) => {
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
                request.setUrl('/admin/cities/<?= $dataCity['id'] ?>').setMethod('PUT').setData(formData);
                response = await request.makeFormRequest();
                hideLoading();
                if(response['code'] == 201) {
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
            $('.combobox2').select2({
                tags: true,
                width: "100%"
            });
        })
        document.getElementById("formEditCity").addEventListener('submit', edit);
        $(window).on('ready', function() {
            $('input.form-control').on('keydown', (event) => {
                if(event.key == 'Enter') {
                    edit();
                }
            })
        })
</script>