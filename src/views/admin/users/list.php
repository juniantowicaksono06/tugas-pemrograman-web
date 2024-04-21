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
                                    $btnType = 'delete';
                                    $btnTitle = 'Nonaktifkan User';
                                    $btnIcon = 'fa-trash-alt';
                                    $btnColor = 'btn-danger';
                                    if($user['user_status'] == 0) {
                                        $btnType = 'activate';
                                        $btnTitle = 'Aktivasi User';
                                        $btnIcon = 'fa-check';
                                        $btnColor = 'btn-success';
                                    }
                                    echo "
                                        <td><a href='/users/edit/". $user['id'] ."' class='btn btn-primary' data-toggle='tooltip' data-placement='top' title='Edit User'>
                                                <span><i class='fa fas fa-pencil-alt'></i></span>
                                            </a>
                                            <button type='button' class='btn ". $btnColor ." ".$btnType."' data-toggle='tooltip' data-placement='top' title='". $btnTitle ."' data-user-id='". $user['id'] ."'>
                                                <span><i class='fa fas ". $btnIcon ."'></i></span>
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

        function deleteUser(e) {
            e.preventDefault();
            showPrompt("Nonaktifkan User?", "Apakah anda ingin menonaktifkan user ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var userId = $(this).data('user-id');
                try {
                    request.setUrl(`/users/${userId}`).setMethod('DELETE');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                        let button = $(this);
                        button.addClass("activate");
                        button.removeClass("delete");
                        button.addClass("btn-success");
                        button.removeClass("btn-danger");
                        button.attr('title', 'Aktivasi User');
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

        function activateUser(e) {
            e.preventDefault();
            showPrompt("Aktivasi User?", "Apakah anda ingin mengaktifkan user ini?", 'warning', async () => {
                var response;
                let request = new Request();
                var userId = $(this).data('user-id');
                try {
                    request.setUrl(`/users/activate/${userId}`).setMethod('GET');
                    response = await request.makeFormRequest();
                    hideLoading();
                    if(response['code'] == 200) {
                        let button = $(this);
                        button.addClass("delete");
                        button.removeClass("activate");
                        button.addClass("btn-danger");
                        button.removeClass("btn-success");
                        button.attr('title', 'Nonaktifkan User');
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

        $(document).on("click", "button.activate", activateUser);
        $(document).on("click", "button.delete", deleteUser);
    });
</script>