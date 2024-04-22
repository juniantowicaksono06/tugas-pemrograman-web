<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <a href="/admin/publishers/create" class="btn color-bg-green-1 text-white hover">Tambah Penerbit</a>
                </div>
                <div class="card-body">
                    <table id="listPublisher" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Nama Penerbit</th>
                                <th>Tgl. Dibuat</th>
                            </tr>
                        </thead>
                        <?php 
                            foreach($data as $publisher) {
                                echo "<tr>";
                                    $btnType = 'delete';
                                    $btnTitle = 'Nonaktifkan Penerbit';
                                    $btnIcon = 'fa-trash-alt';
                                    $btnColor = 'btn-danger';
                                    if($publisher['status'] == 0) {
                                        $btnType = 'activate';
                                        $btnTitle = 'Aktivasi Penerbit';
                                        $btnIcon = 'fa-check';
                                        $btnColor = 'btn-success';
                                    }
                                    echo "
                                        <td><a href='/admin/publishers/edit/". $publisher['id'] ."' class='btn btn-primary' data-toggle='tooltip' data-placement='top' title='Edit Penerbit'>
                                                <span><i class='fa fas fa-pencil-alt'></i></span>
                                            </a>
                                            <button type='button' class='btn ". $btnColor ." ".$btnType."' data-toggle='tooltip' data-placement='top' title='". $btnTitle ."' data-publisher-id='". $publisher['id'] ."'>
                                                <span><i class='fa fas ". $btnIcon ."'></i></span>
                                            </button>
                                        </td>";
                                    echo "<td>" . $publisher['name'] . "</td>";
                                    echo "<td>" . $publisher['created_at'] . "</td>";
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
    let table = new DataTable('#listPublisher');
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()

        function deletePublisher(e) {
            e.preventDefault();
            showPrompt("Nonaktifkan Penerbit?", "Apakah anda ingin menonaktifkan penerbit ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var publisherId = $(this).data('publisher-id');
                try {
                    request.setUrl(`/admin/publishers/${publisherId}`).setMethod('DELETE');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                        let button = $(this);
                        button.addClass("activate");
                        button.removeClass("delete");
                        button.addClass("btn-success");
                        button.removeClass("btn-danger");
                        button.attr('title', 'Aktivasi Penerbit');
                        let icon = $(button).find('span > i');
                        icon.addClass('fa-check');
                        icon.removeClass('fa-trash-alt');

                        // var row = table.row($(this).closest('tr'));
                        // row.remove().draw();
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

        function activatePublisher(e) {
            e.preventDefault();
            showPrompt("Aktivasi Penerbit?", "Apakah anda ingin mengaktifkan user ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var publisherId = $(this).data('publisher-id');
                try {
                    request.setUrl(`/admin/publishers/activate/${publisherId}`).setMethod('GET');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                        let button = $(this);
                        button.addClass("delete");
                        button.removeClass("activate");
                        button.addClass("btn-danger");
                        button.removeClass("btn-success");
                        button.attr('title', 'Nonaktifkan Penerbit');
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

        $(document).on("click", "button.activate", activatePublisher);
        $(document).on("click", "button.delete", deletePublisher);
    });
</script>