<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-body">
                    <div class="w-100">
                        <form action="#" method="POST" id="formEditProfile">
                            <div class="form-group mb-3">
                                <label for="denda" class="form-label auth-form-label color-gray-1 inika-regular">Denda</label>
                                <input type="number" class="form-control poppins-regular" id="denda" name="denda" placeholder="Masukkan Nominal Denda" value="<?= $fines['denda'] ?>">
                                <div class="mt-2">
                                    <span class="text-danger error" id="dendaError"></span>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <button type="button" id="btnSubmit" class="color-bg-green-1 btn text-white rounded" style="border-radius: 15px !important;">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    async function updateFines(e) {
        e.preventDefault();
        
        var response;
        let validator = new Validator()
        let dataValidate = {
            'denda': 'required|greaterThan:0',
        };
        validator.setInputName({
            'denda': "Denda",
        })
        let validate = validator.validate(dataValidate, {
            'denda': $('#denda').val()
        });
        if(!validate) {
            let message = validator.getMessages()
            Object.keys(message).forEach((key) => {
                Object.keys(message[key]).forEach((error_key) => {
                    document.querySelector(`#${key}Error`).innerText = message[key][error_key]
                })
            })
            return
        }
        var response;
        let formData = new FormData();
        formData.append('denda', $('#denda').val())
        var request = new Request();
        try {
            showLoading();
            request.setUrl('/admin/setting-fines').setMethod('PUT').setData(formData);
            response = await request.makeFormRequest();
            hideLoading();
            if(response['code'] == 200) {
                showToast(response['message'], 'success');
            }
            else if(response['code'] == 500) {
                showAlert(response['message'], 'error');
            }
        }
        catch (error) {
            hideLoading();
            showAlert("Gagal mengatur denda", 'error')
        }
    }
    $(document).ready(function(e) {
        $("#btnSubmit").on('click', updateFines);
    })
    $('input.form-control').on('keydown', (event) => {
        if(event.key == 'Enter') {
            updateFines();
        }
    })
</script>