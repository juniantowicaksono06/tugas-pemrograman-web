<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <a href="/admin/cities/create" class="btn color-bg-green-1 text-white hover">Tambah Kota</a>
                </div>
                <div class="card-body">
                    <table id="listcity" class="table table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Provinsi</th>
                                <th>Nama Kota</th>
                                <th>Tgl. Dibuat</th>
                            </tr>
                        </thead>
                        <?php 
                            foreach($data as $city) {
                                echo "<tr>";
                                    $btnType = 'delete';
                                    $btnTitle = 'Nonaktifkan Kota';
                                    $btnIcon = 'fa-trash-alt';
                                    $btnColor = 'btn-danger';
                                    if($city['status'] == 0) {
                                        $btnType = 'activate';
                                        $btnTitle = 'Aktivasi Kota';
                                        $btnIcon = 'fa-check';
                                        $btnColor = 'btn-success';
                                    }
                                    echo "
                                        <td><a href='/admin/cities/edit/". $city['id'] ."' class='btn btn-primary' data-toggle='tooltip' data-placement='top' title='Edit Kota'>
                                                <span><i class='fa fas fa-pencil-alt'></i></span>
                                            </a>
                                            <button type='button' class='btn ". $btnColor ." ".$btnType."' data-toggle='tooltip' data-placement='top' title='". $btnTitle ."' data-city-id='". $city['id'] ."'>
                                                <span><i class='fa fas ". $btnIcon ."'></i></span>
                                            </button>
                                        </td>";
                                    echo "<td>" . $city['province_name'] . "</td>";
                                    echo "<td>" . $city['city_name'] . "</td>";
                                    echo "<td>" . $city['created_at'] . "</td>";
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
        $('#listcity').addClass("nowrap").dataTable({
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
            showPrompt("Nonaktifkan Kota?", "Apakah anda ingin menonaktifkan Kota ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var cityId = $(this).data('city-id');
                try {
                    request.setUrl(`/admin/cities/${cityId}`).setMethod('DELETE');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                        let button = $(this);
                        button.addClass("activate");
                        button.removeClass("delete");
                        button.addClass("btn-success");
                        button.removeClass("btn-danger");
                        button.attr('title', 'Aktivasi Kota');
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
            showPrompt("Aktivasi Kota?", "Apakah anda ingin mengaktifkan provinsi ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var cityId = $(this).data('city-id');
                try {
                    request.setUrl(`/admin/cities/reactivate/${cityId}`).setMethod('GET');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                        let button = $(this);
                        button.addClass("delete");
                        button.removeClass("activate");
                        button.addClass("btn-danger");
                        button.removeClass("btn-success");
                        button.attr('title', 'Nonaktifkan Kota');
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