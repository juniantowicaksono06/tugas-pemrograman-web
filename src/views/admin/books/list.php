<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <a href="/admin/books/create" class="btn color-bg-green-1 text-white hover">Tambah Buku</a>
                </div>
                <div class="card-body">
                    <table id="listBook" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Judul Buku</th>
                                <th>Penerbit</th>
                                <th>Pengarang</th>
                                <th>Tgl. Dibuat</th>
                            </tr>
                        </thead>
                        <?php 
                            foreach($data as $author) {
                                echo "<tr>";
                                    $btnType = 'delete';
                                    $btnTitle = 'Nonaktifkan Buku';
                                    $btnIcon = 'fa-trash-alt';
                                    $btnColor = 'btn-danger';
                                    if($author['status'] == 0) {
                                        $btnType = 'activate';
                                        $btnTitle = 'Aktivasi Buku';
                                        $btnIcon = 'fa-check';
                                        $btnColor = 'btn-success';
                                    }
                                    echo "
                                        <td><a href='/admin/books/edit/". $author['id'] ."' class='btn btn-primary' data-toggle='tooltip' data-placement='top' title='Edit Buku'>
                                                <span><i class='fa fas fa-pencil-alt'></i></span>
                                            </a>
                                            <button type='button' class='btn ". $btnColor ." ".$btnType."' data-toggle='tooltip' data-placement='top' title='". $btnTitle ."' data-author-id='". $author['id'] ."'>
                                                <span><i class='fa fas ". $btnIcon ."'></i></span>
                                            </button>
                                        </td>";
                                    echo "<td>" . $author['name'] . "</td>";
                                    echo "<td>" . $author['created_at'] . "</td>";
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
    let table = new DataTable('#listBook');
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()

        function deleteBook(e) {
            e.preventDefault();
            showPrompt("Nonaktifkan Buku?", "Apakah anda ingin menonaktifkan Buku ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var authorId = $(this).data('author-id');
                try {
                    request.setUrl(`/admin/books/${authorId}`).setMethod('DELETE');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                        let button = $(this);
                        button.addClass("activate");
                        button.removeClass("delete");
                        button.addClass("btn-success");
                        button.removeClass("btn-danger");
                        button.attr('title', 'Aktivasi Buku');
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

        function activateBook(e) {
            e.preventDefault();
            showPrompt("Aktivasi Buku?", "Apakah anda ingin mengaktifkan user ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var authorId = $(this).data('author-id');
                try {
                    request.setUrl(`/admin/books/activate/${authorId}`).setMethod('GET');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                        let button = $(this);
                        button.addClass("delete");
                        button.removeClass("activate");
                        button.addClass("btn-danger");
                        button.removeClass("btn-success");
                        button.attr('title', 'Nonaktifkan Buku');
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

        $(document).on("click", "button.activate", activateBook);
        $(document).on("click", "button.delete", deleteBook);
    });
</script>