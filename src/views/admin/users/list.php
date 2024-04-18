<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <a href="/users/create" class="btn color-bg-green-1 text-white hover">Tambah User</a>
                </div>
                <div class="card-body">
                    <table id="listUser" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Fullname</th>
                                <th>Username</th>
                                <th>Tipe User</th>
                                <th>Tgl. Dibuat</th>
                            </tr>
                        </thead>
                        <?php 
                            $sess = new Session();
                            $user_data = $sess->get('user_credential');
                            foreach($data as $user) {
                                echo "<tr>";
                                if($user['username'] == $user_data['username']) {
                                    echo '<td></td>';
                                }
                                else {
                                    echo "
                                        <td><a href='/users/edit/". $user['id'] ."' class='btn btn-primary' data-toggle='tooltip' data-placement='top' title='Edit User'>
                                                <span><i class='fa fas fa-pencil-alt'></i></span>
                                            </a>
                                            <button type='button' class='btn btn-danger delete' data-toggle='tooltip' data-placement='top' title='Hapus User' data-user-id='". $user['id'] ."'>
                                                <span><i class='fa fas fa-trash-alt'></i></span>
                                            </button>
                                        </td>";
                                }
                                echo "<td>" . $user['fullname'] . "</td>";
                                echo "<td>" . $user['username'] . "</td>";
                                echo $user['user_type'] == 1 ? "<td>Admin</td>" : "<td>Reguler</td>";
                                echo "<td>" . $user['created_at'] . "</td>";
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
    let table = new DataTable('#listUser');
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
        $("button.delete").on('click', function(e) {
            e.preventDefault();
            showPrompt("Hapus User?", "Apakah anda ingin menghapus user ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var userId = $(this).data('user-id');
                try {
                    request.setUrl(`/users/${userId}`).setMethod('DELETE');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                    var row = table.row($(this).closest('tr'));
                    row.remove().draw();
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
                showToast("Berhasil menghapus", "success");
            });
        })
    });
</script>