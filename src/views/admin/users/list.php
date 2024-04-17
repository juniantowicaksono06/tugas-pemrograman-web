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
                                <th>Fullname</th>
                                <th>Username</th>
                                <th>Tipe User</th>
                                <th>Tgl. Dibuat</th>
                            </tr>
                        </thead>
                        <?php 
                            foreach($data as $user) {
                                echo "<tr>";
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
</script>