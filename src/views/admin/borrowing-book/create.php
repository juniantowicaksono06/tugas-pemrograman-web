<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <a href="/admin/borrowing-books/book" class="btn color-bg-green-1 text-white hover">Pilih Buku</a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-group">
                            <label for="borrower" class="form-label auth-form-label color-gray-1 inika-regular">Nama Peminjam</label>
                            <select name="borrower" id="borrower" class="combobox2 w-100 h-100 poppins-regular d-none">
                                <?php foreach($allActiveMembers as $member): ?>
                                    <option value="<?= $member['id'] ?>"><?= $member['fullname'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="borrowing_date" class="form-label auth-form-label color-gray-1 inika-regular">Tanggal Pinjam</label>
                            <div class="form-outline datepicker-with-limits" data-mdb-format="yyyy-mm-dd">
                                <input type="text" class="form-control" id="borrowing_date" name="borrowing_date" />
                            </div>
                            <div class="mt-2">
                                <span class="text-danger error" id="borrowing_dateError"></span>
                            </div>
                        </div>
                    </div>
                    <table id="listBook" class="table table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Nama Buku</th>
                                <th>Nama Penerbit</th>
                                <th>Nama Pengarang</th>
                                <th>Kategori</th>
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
                                    if(!in_array($book['id'], $_SESSION['dataPeminjamanBooksId'])) {
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
                'borrowing_date': 'required|validDate',
                'borrower': 'required',
            };
            validator.setInputName({
                'borrowing_date': "Tanggal Pinjam",
                'borrower': "Nama Pinjam",
            })
            var booksId = $("button.book").map(function() {
                return $(this).data('book-id')
            }).get()
            if(booksId.length <= 0) {
                showAlert("Silahkan memilih buku terlebih dulu", 'warning');
                return;
            }
            $('.error').each(function(elementIndex, element) {
                $(element).text("")
            })
            
            let validate = validator.validate(dataValidate, {
                'borrowing_date': $('#borrowing_date').val(),
                'borrower': $('#borrower').val(),
            });
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
                "booksId": booksId
            }))
            formData.append('borrowing_date', $("#borrowing_date").val())
            formData.append('borrower', $("#borrower").val())
            var request;
            try {
                request = new Request();
                showLoading()
                request.setUrl(`/admin/borrowing-books`).setMethod('POST').setData(formData);
                response = await request.makeFormRequest();
                hideLoading();
                if(response['code'] == 201) {
                    // var row = table.row('.selected-book').remove().draw()
                    $('.selected-book').each(function(index, element) {
                        table.row(element).remove().draw();
                    })
                    showToast(response['message'], 'success');
                }
                else {
                    showAlert(response['message'], 'warning');
                }
            } catch (error) {
                hideLoading();
                showAlert("Gagal melakukan peminjaman", 'error')
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
                request.setUrl(`/admin/borrowing-books/deselect/${bookId}`).setMethod('DELETE');
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

        $(document).ready(function() {
            $('.combobox2').select2({
                tags: true,
                width: "100%"
            });
            var currentDate = new Date()
            let year = currentDate.getFullYear();
            let month = currentDate.getMonth() + 1;
            month = padLeft(month.toString())
            let date = currentDate.getDate();
            date = padLeft(date.toString())
            $('#borrowing_date').val(`${year}-${month}-${date}`)
            var datepickerWithLimits = document.querySelector('.datepicker-with-limits'); new
            mdb.Datepicker(datepickerWithLimits, {
                min: new Date(1900, 1, 1),
                max: new Date()
            });
        })

        $(document).on("click", "button.book-deselect", deselectBook);
        $(document).on("click", "#btnSubmit", submit);
    })
</script>