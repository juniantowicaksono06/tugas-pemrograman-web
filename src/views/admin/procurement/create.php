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
                            foreach($data as $x => $book) {
                                echo "<tr class='selected-book'>";
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
                                        <td><button type='button' data-book-id='".$book['id']."' data-book='".json_encode($book)."' class='book btn ".$btnColour." ". $btnType ."' data-toggle='tooltip' data-placement='top' title='".$btnTitle."'>
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
                                    echo "<td><input type='number' class='form-control jumlah' value='0' name='jumlah[]' /><div class='mt-2'><span class='text-danger error' id='jumlah".$x."Error'></span></div></td>";
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
            pageLength: 1000,
            lengthMenu: [10, 25, 50, 75, 100, 1000],
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            scrollCollapse: true,
        })
        $('[data-toggle="tooltip"]').tooltip()

        async function submit(e) {
            e.preventDefault()
            var response;
            let validator = new Validator()
            let dataValidate = {
                'jumlah[]': 'required|greaterThan:0',
            };
            validator.setInputName({
                'jumlah[]': "Jumlah",
            })
            var jumlah = $("input[name='jumlah[]']").map(function() {
                return $(this).val();
            }).get();
            var booksId = $("button.book").map(function() {
                return $(this).data('book-id')
            }).get()
            let validate = validator.validate(dataValidate, {
                'jumlah': jumlah
            });
            if(booksId.length <= 0) {
                showAlert("Silahkan memilih buku terlebih dulu", 'warning');
                return;
            }
            $('.error').each(function(elementIndex, element) {
                // console.log(element)
                $(element).text("")
            })
            if(!validate) {
                let message = validator.getMessages()
                
                Object.keys(message).forEach((key) => {
                    Object.keys(message[key]).forEach((rule) => {
                        Object.keys(message[key][rule]).forEach((errorKey) => {
                            document.querySelector(`#${errorKey}Error`).innerText = message[key][rule][errorKey]
                        })
                    })
                })
                return
            }
            let formData = new FormData();
            formData.append('data', JSON.stringify({
                "jumlah": jumlah,
                "booksId": booksId
            }))
            var request;
            try {
                request = new Request();
                showLoading()
                request.setUrl(`/admin/procurements`).setMethod('POST').setData(formData);
                response = await request.makeFormRequest();
                hideLoading();
                if(response['code'] == 201) {
                    // var row = table.row('.selected-book').remove().draw()
                    $('.selected-book').each(function(index, element) {
                        table.row(element).remove().draw();
                    })
                    showToast(response['message'], 'success');
                }
            } catch (error) {
                hideLoading();
                showAlert("Gagal melakukan pengadaan", 'error')
            }
        }

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
        $(document).on("click", "#btnSubmit", submit);
        // $(document).on("click", "button.book-select", selectBook);
    })
</script>