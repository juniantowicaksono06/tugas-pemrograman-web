<?php
    $months = getMonths();
    $date1 = new DateTime($data['birthdate']);
    $month = $months[intval($date1->format('m')) - 1];
    $date = $date1->format('d');
    $year = $date1->format('Y');
    $date2 = new DateTime($data['created_at']);
    $monthJoin = $months[intval($date2->format('m')) - 1];
    $dateJoin = $date2->format('d');
    $yearJoin = $date2->format('Y');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <a href="<?= getBaseURL() ?>/admin/members" class="btn color-bg-green-1 text-white hover">Kembali</a>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table table-bordered">
                            <tr>
                                <th>Nama Anggota</th>
                                <td><?= $data['fullname'] ?></td>
                            </tr>
                            <tr>
                                <th>Tempat, Tanggal Lahir</th>
                                <td><?= $data['city_name'] . ", " . $date . " " . $month . " " . $year ?></td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td><?= $data['gender'] == 1 ? "Laki-laki" : "Perempuan" ?></td>
                            </tr>
                            <tr>
                                <th>No HP</th>
                                <td><?= $data['no_hp'] ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?= $data['email'] ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Bergabung</th>
                                <td><?= $dateJoin . " " . $monthJoin . " " . $yearJoin ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>