<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <a href="/admin/borrowing-books/create" class="btn color-bg-green-1 text-white hover">Peminjaman Baru</a>
                </div>
                <div class="card-body">
                    <table id="listProcurement" class="table table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Tanggal Peminjaman</th>
                            </tr>
                        </thead>
                        <?php 
                            foreach($data as $procurement) {
                                echo "<tr>";
                                    // $btnType = 'delete';
                                    // $btnTitle = 'Nonaktifkan Pengadaan';
                                    // $btnIcon = 'fa-trash-alt';
                                    // $btnColor = 'btn-danger';
                                    // if($procurement['status'] == 0) {
                                    //     $btnType = 'activate';
                                    //     $btnTitle = 'Aktivasi Pengadaan';
                                    //     $btnIcon = 'fa-check';
                                    //     $btnColor = 'btn-success';
                                    // }
                                    echo "
                                        <td><a href='/admin/procurements/". $procurement['id'] ."' class='btn btn-success' data-toggle='tooltip' data-placement='top' title='Deail Pengadaan'>
                                                <span><i class='fa fas fa-eye'></i></span>
                                            </a>
                                        </td>";
                                    echo "<td>" . $procurement['date_procurement'] . "</td>";
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
        $('#listProcurement').addClass("nowrap").dataTable({
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            scrollCollapse: true,
            columnDefs: [
                {
                    target: 1,
                    render: DataTable.render.date(),
                },
            ]
        })
        $('[data-toggle="tooltip"]').tooltip()

        function deleteAuthor(e) {
            e.preventDefault();
            showPrompt("Nonaktifkan Pengadaan?", "Apakah anda ingin menonaktifkan Pengadaan ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var authorId = $(this).data('author-id');
                try {
                    request.setUrl(`/admin/procurements/${authorId}`).setMethod('DELETE');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                        let button = $(this);
                        button.addClass("activate");
                        button.removeClass("delete");
                        button.addClass("btn-success");
                        button.removeClass("btn-danger");
                        button.attr('title', 'Aktivasi Pengadaan');
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

        function activateAuthor(e) {
            e.preventDefault();
            showPrompt("Aktivasi Pengadaan?", "Apakah anda ingin mengaktifkan Pengadaan ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var authorId = $(this).data('author-id');
                try {
                    request.setUrl(`/admin/procurements/activate/${authorId}`).setMethod('GET');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                        let button = $(this);
                        button.addClass("delete");
                        button.removeClass("activate");
                        button.addClass("btn-danger");
                        button.removeClass("btn-success");
                        button.attr('title', 'Nonaktifkan Pengadaan');
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

        $(document).on("click", "button.activate", activateAuthor);
        $(document).on("click", "button.delete", deleteAuthor);
    })
</script>