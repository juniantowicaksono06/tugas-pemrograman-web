<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <a href="/admin/provinces/create" class="btn color-bg-green-1 text-white hover">Tambah Provinsi</a>
                </div>
                <div class="card-body">
                    <table id="listprovince" class="table table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Nama Provinsi</th>
                                <th>Tgl. Dibuat</th>
                            </tr>
                        </thead>
                        <?php 
                            foreach($data as $province) {
                                echo "<tr>";
                                    $btnType = 'delete';
                                    $btnTitle = 'Nonaktifkan Provinsi';
                                    $btnIcon = 'fa-trash-alt';
                                    $btnColor = 'btn-danger';
                                    if($province['status'] == 0) {
                                        $btnType = 'activate';
                                        $btnTitle = 'Aktivasi Provinsi';
                                        $btnIcon = 'fa-check';
                                        $btnColor = 'btn-success';
                                    }
                                    echo "
                                        <td><a href='/admin/provinces/edit/". $province['id'] ."' class='btn btn-primary' data-toggle='tooltip' data-placement='top' title='Edit Provinsi'>
                                                <span><i class='fa fas fa-pencil-alt'></i></span>
                                            </a>
                                            <button type='button' class='btn ". $btnColor ." ".$btnType."' data-toggle='tooltip' data-placement='top' title='". $btnTitle ."' data-province-id='". $province['id'] ."'>
                                                <span><i class='fa fas ". $btnIcon ."'></i></span>
                                            </button>
                                        </td>";
                                    echo "<td>" . $province['name'] . "</td>";
                                    echo "<td>" . $province['created_at'] . "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#listprovince').addClass("nowrap").dataTable({
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            scrollCollapse: true,
            columnDefs: [
                {
                    target: 2,
                    render: DataTable.render.date(),
                },
            ]
        })
        $('[data-toggle="tooltip"]').tooltip()

        function deleteProvince(e) {
            e.preventDefault();
            showPrompt("Nonaktifkan Provinsi?", "Apakah anda ingin menonaktifkan Provinsi ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var provinceId = $(this).data('province-id');
                try {
                    request.setUrl(`/admin/provinces/${provinceId}`).setMethod('DELETE');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                        let button = $(this);
                        button.addClass("activate");
                        button.removeClass("delete");
                        button.addClass("btn-success");
                        button.removeClass("btn-danger");
                        button.attr('title', 'Aktivasi Provinsi');
                        let icon = $(button).find('span > i');
                        icon.addClass('fa-check');
                        icon.removeClass('fa-trash-alt');
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
                let message = response['message'];
                showToast(message, response['code'] == 200 || response['code'] == 201 ? 'success' : 'warning');
            });
        }

        function activateProvince(e) {
            e.preventDefault();
            showPrompt("Aktivasi Provinsi?", "Apakah anda ingin mengaktifkan provinsi ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var provinceId = $(this).data('province-id');
                try {
                    request.setUrl(`/admin/provinces/reactivate/${provinceId}`).setMethod('GET');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                        let button = $(this);
                        button.addClass("delete");
                        button.removeClass("activate");
                        button.addClass("btn-danger");
                        button.removeClass("btn-success");
                        button.attr('title', 'Nonaktifkan Provinsi');
                        let icon = $(button).find('span > i');
                        icon.addClass('fa-trash-alt');
                        icon.removeClass('fa-check');
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
                let message = response['message'];
                showToast(message, response['code'] == 200 || response['code'] == 201 ? 'success' : 'warning');
            });
        }

        $(document).on("click", "button.activate", activateProvince);
        $(document).on("click", "button.delete", deleteProvince);
    })
</script>