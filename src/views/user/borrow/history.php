<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-body">
                    <table id="listHistory" class="table table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Tanggal Peminjaman</th>
                                <th>Jatuh Tempo</th>
                                <th>Tanggal Kembali</th>
                                <th>Denda</th>
                            </tr>
                        </thead>
                        <?php 
                            $date1 = new \DateTime();
                            foreach($data as $borrowing) {
                                $denda = 0;
                                if(empty($borrowing['date_return'])) {
                                    $date2 = new \DateTime($borrowing['due_date']);
                                    if($date1 > $date2) {
                                        $interval = $date1->diff($date2);
                                        $daysDifference = $interval->days + 1;
                                        $denda = $fines['denda'] * $daysDifference;
                                    }
                                }
                                else {
                                    $denda = empty($borrowing['denda']) ? 0 : $borrowing['denda'];
                                }

                                echo "<tr>";
                                    if(empty($borrowing['date_return'])):
                                        echo "
                                            <td>
                                                <a href='/borrow-history/". $borrowing['id'] ."' class='btn btn-success' data-toggle='tooltip' data-placement='top' title='Detail Peminjaman'>
                                                    <span><i class='fa fas fa-eye'></i></span>
                                                </a>
                                            </td>";
                                    else:
                                        echo "
                                            <td>
                                                <a href='/borrow-history/". $borrowing['id'] ."' class='btn btn-success' data-toggle='tooltip' data-placement='top' title='Detail Peminjaman'>
                                                    <span><i class='fa fas fa-eye'></i></span>
                                                </a>
                                            </td>";
                                    endif;
                                    echo "<td>" . $borrowing['date_borrow'] . "</td>";
                                    echo "<td>
                                        ".$borrowing['due_date']."
                                    </td>";
                                    echo "<td>" . $borrowing['date_return'] . "</td>";
                                    echo "<td>Rp. " . number_format($denda, 0, ',', '.') . "</td>";
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
        $('#listHistory').addClass("nowrap").dataTable({
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
                {
                    target: 3,
                    render: DataTable.render.date(),
                },
            ]
        })
        $('[data-toggle="tooltip"]').tooltip()

        // async function returnBook(e) {
        //     e.preventDefault();
        //     showPrompt("Kembalikan Buku?", "Apakah anda menyelesaikan peminjaman ini?", 'warning', async () => {
        //         var response;
        //         let request = new Request();
        //         var borrowingId = $(this).data('borrowing-id');
        //         try {
        //             request.setUrl(`/admin/borrowing-books/${borrowingId}`).setMethod('POST');
        //             response = await request.makeFormRequest();
        //             hideLoading();
        //             if(response['code'] == 200) {
        //                 showToast(response['message'], 'success', () => {
        //                     window.location.reload()
        //                 });
        //             }
        //             else {
        //                 showAlert(response['message'], 'warning');
        //             }
        //         }
        //         catch (error) {
        //             hideLoading();
        //             showAlert("Gagal menyelesaikan peminjaman", 'error')
        //         }
        //     });
        // }

        // $(document).on("click", "button.return-book", returnBook);
        // $(document).on("click", "button.delete", deleteAuthor);
    })
</script>