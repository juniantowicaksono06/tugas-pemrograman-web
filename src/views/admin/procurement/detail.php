<?php 
$currentPathExp = explode('/', $_SERVER['REQUEST_URI']);
$currentPath = $currentPathExp[2]; 
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <a href="<?= getBaseURL() ?>/admin/<?= $currentPath ?>" class="btn color-bg-green-1 text-white hover">Kembali</a>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table table-bordered">
                            <tr>
                                <th>Tanggal Pengadaan</th>
                                <td><?= $data['date_procurement'] ?></td>
                            </tr>
                            <tr>
                                <th>Kode Pengadaan</th>
                                <td><?= $data['procurement_code'] ?></td>
                            </tr>
                            <tr>
                                <th>Diadakan Oleh</th>
                                <td><?= $data['created_by'] ?></td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        <table id="detailHistory" class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th>Nama Buku</th>
                                    <th>Penerbit</th>
                                    <th>Pengarang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($detail as $index => $book): ?>
                                    <tr>
                                        <td><?= $book['book_title'] ?></td>
                                        <td><?= $book['publisher'] ?></td>
                                        <td>
                                            <ol>
                                                <?php foreach($book['authors'] as $author): ?>
                                                    <li><?= $author['name'] ?></li>
                                                <?php endforeach; ?>
                                            </ol>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>