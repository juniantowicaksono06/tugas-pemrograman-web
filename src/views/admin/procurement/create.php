<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <a href="/admin/procurements/book" class="btn color-bg-green-1 text-white hover">Tambah Pengadaan</a>
                </div>
                <div class="card-body">
                    <table id="listBook" class="table table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Nama Buku</th>
                                <th>Nama Penerbit</th>
                                <th>Nama Pengarang</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php 
                            foreach($data as $book) {
                                echo "<tr>";
                                    $btnType = "book-deselect";
                                    $btnColour = "btn-danger";
                                    $btnIcon = "fa fas fa-trash-alt";
                                    $btnTitle = "Hapus Pilihan";
                                    if(!in_array($book['id'], $_SESSION['dataPengadaanBooksId'])) {
                                        $btnType = "book-select";
                                        $btnColour = "btn-success";
                                        $btnIcon = "fa fas fa-check";
                                        $btnTitle = "Pilih Buku";
                                    }
                                    echo "
                                        <td><button type='button' data-book-id='".$book['id']."' data-book='".json_encode($book)."' class='btn ".$btnColour." ". $btnType ."' data-toggle='tooltip' data-placement='top' title='".$btnTitle."'>
                                                <span><i class='".$btnIcon."'></i></span>
                                            </button>
                                        </td>";
                                    echo "<td>" . $book['title'] . "</td>";
                                    echo "<td>" . $book['publisher_name'] . "</td>";
                                    echo "<td><ol>";
                                    foreach($book['authors'] as $author):
                                        echo "<li>" . $author['name'] . "</li>";
                                    endforeach;
                                    echo "</ol></td>";
                                    echo "<td>" . $book['categories'] . "</td>";
                                    echo "<td><input type='text' class='form-control input-value' value='0' name='input-value[]' /></td>";
                                echo "</tr>";
                            }
                        ?>
                        </tbody>
                    </table>
                    <div>
                        <button type="button" class="btn color-bg-green-1 text-white hover <?= !empty($data) ? 'd-block' : 'd-none' ?>" id="btnSubmit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var table = new DataTable("#listBook", {
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            scrollCollapse: true,
            // columnDefs: [
            //     {
            //         target: 5,
            //         render: DataTable.render.date(),
            //     },
            // ]
        })
        // $('#listBook').addClass("nowrap").dataTable({
        //     responsive: true,
        //     rowReorder: {
        //         selector: 'td:nth-child(2)'
        //     },
        //     scrollCollapse: true,
        //     // columnDefs: [
        //     //     {
        //     //         target: 5,
        //     //         render: DataTable.render.date(),
        //     //     },
        //     // ]
        // })
        $('[data-toggle="tooltip"]').tooltip()

        async function deselectBook(e) {
            e.preventDefault();
            var response;
            let request = new Request();
            var book = $(this).data('book');
            var bookId = $(this).data('book-id');
            if(book === null || book === undefined || bookId === null || bookId === undefined) {
                return;
            }
            let formData = new FormData();
            try {
                showLoading()
                request.setUrl(`/admin/procurements/deselect/${bookId}`).setMethod('DELETE');
                response = await request.makeFormRequest();
                hideLoading();
                if(response['code'] == 200) {
                    var row = table.row($(this).closest('tr'));
                    row.remove().draw();
                    if($(".dt-empty").length == 1) {
                        $("#btnSubmit").removeClass('d-block');
                        $("#btnSubmit").addClass('d-none');
                    }
                }
                else {
                    showAlert(response['message'], 'warning');
                }
            }
            catch (error) {
                hideLoading();
                showAlert("Gagal menghapus pilihan buku", 'error')
            }
            hideLoading()
            let message = response['message'];
            // showToast(message, response['code'] == 200 || response['code'] == 201 ? 'success' : 'warning');
        }

        $(document).on("click", "button.book-deselect", deselectBook);
        // $(document).on("click", "button.book-select", selectBook);
    })
</script>