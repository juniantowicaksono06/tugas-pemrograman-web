<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-body">
                    <form action="/admin/publishers" method="POST" id="formCreateCategory">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label auth-form-label color-gray-1 inika-regular">Nama Kategori</label>
                            <input type="text" class="form-control poppins-regular" id="name" name="name" placeholder="Masukkan Nama Kategori">
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
    async function create(e) {
            e.preventDefault();
            clearError();
            let request = new Request();
            let createInputElements = document.querySelectorAll("#formCreateCategory input, #formCreateCategory select")
            let data = {};
            let validator = new Validator()
            let dataValidate = {
                'name': 'required|max:64',
            };
            validator.setInputName({
                'name': "Nama Kategori"
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
                request.setUrl('/admin/categories').setMethod('POST').setData(formData);
                response = await request.makeFormRequest();
                hideLoading();
                if(response['code'] == 201) {
                    createInputElements.forEach((element) => {
                        element.value = ""
                    })
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
        document.getElementById("formCreateCategory").addEventListener('submit', create);
        $(window).on('ready', function() {
            $('input.form-control').on('keydown', (event) => {
                if(event.key == 'Enter') {
                    create();
                }
            })
        })
</script>