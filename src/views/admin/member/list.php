<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <a href="/admin/members/create" class="btn color-bg-green-1 text-white hover">Tambah Anggota</a>
                </div>
                <div class="card-body">
                    <table id="listMember" class="table table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Nama Anggota</th>
                                <th>Alamat</th>
                                <th>No HP</th>
                                <th>Tanggal Bergabung</th>
                            </tr>
                        </thead>
                        <?php 
                            foreach($data as $member) {
                                echo "<tr>";
                                    $btnType = 'delete';
                                    $btnTitle = 'Nonaktifkan Anggota';
                                    $btnIcon = 'fa-trash-alt';
                                    $btnColor = 'btn-danger';
                                    if($member['user_status'] == 0 || $member['user_status'] == 2) {
                                        $btnType = 'activate';
                                        $btnTitle = 'Aktivasi Anggota';
                                        $btnIcon = 'fa-check';
                                        $btnColor = 'btn-success';
                                    }
                                    echo "
                                        <td>
                                            <a href='/admin/members/".$member['id']."' class='btn btn-info'>
                                                <span><i class='fa fas fa-eye'></i></span>
                                            </a>
                                            <a href='/admin/members/edit/".$member['id']."' class='btn btn-primary'>
                                                <span><i class='fa fas fa-pencil-alt'></i></span>
                                            </a>
                                            <button type='button' class='btn ". $btnColor ." ".$btnType."' data-toggle='tooltip' data-placement='top' title='". $btnTitle ."' data-member-id='". $member['id'] ."'>
                                                <span><i class='fa fas ". $btnIcon ."'></i></span>
                                            </button>
                                        </td>";
                                    echo "<td>" . $member['fullname'] . "</td>";
                                    echo "<td>" . $member['alamat'] . "</td>";
                                    echo "<td>" . $member['no_hp'] . "</td>";
                                    echo "<td>" . $member['created_at'] . "</td>";
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
        $('#listMember').addClass("nowrap").dataTable({
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            scrollCollapse: true,
            columnDefs: [
                {
                    target: 4,
                    render: DataTable.render.date(),
                },
            ]
        })
        $('[data-toggle="tooltip"]').tooltip()

        function deactivateCategory(e) {
            e.preventDefault();
            showPrompt("Nonaktifkan Anggota?", "Apakah anda ingin menonaktifkan anggota ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var memberId = $(this).data('member-id');
                try {
                    request.setUrl(`/admin/members/${memberId}`).setMethod('DELETE');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                        let button = $(this);
                        button.addClass("activate");
                        button.removeClass("delete");
                        button.addClass("btn-success");
                        button.removeClass("btn-danger");
                        button.attr('title', 'Aktivasi Anggota');
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
                    showAlert("Gagal nonaktifkan anggota", 'error')
                }
            });
        }

        function activateCategory(e) {
            e.preventDefault();
            showPrompt("Aktivasi Anggota?", "Apakah anda ingin mengaktifkan Anggota ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var memberId = $(this).data('member-id');
                try {
                    request.setUrl(`/admin/members/reactivate/${memberId}`).setMethod('GET');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                        let button = $(this);
                        button.addClass("delete");
                        button.removeClass("activate");
                        button.addClass("btn-danger");
                        button.removeClass("btn-success");
                        button.attr('title', 'Nonaktifkan Anggota');
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
                    showAlert("Gagal aktivasi anggota", 'error')
                }
            });
        }

        $(document).on("click", "button.activate", activateCategory);
        $(document).on("click", "button.delete", deactivateCategory);
    })
</script>