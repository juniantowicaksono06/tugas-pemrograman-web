<?php 
$currentPathExp = explode('/', $_SERVER['REQUEST_URI']);
$currentPath = $currentPathExp[1]; 
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <a href="<?= getBaseURL() ?>/<?= $currentPath ?>" class="btn color-bg-green-1 text-white hover">Kembali</a>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table table-bordered">
                            <tr>
                                <th>Tanggal Pinjam</th>
                                <td><?= $data['date_borrow'] ?></td>
                            </tr>
                            <tr>
                                <th>Kode Pinjam</th>
                                <td><?= $data['borrow_code'] ?></td>
                            </tr>
                            <tr>
                                <th>Jatuh Tempo</th>
                                <td><?= $data['due_date'] ?></td>
                            </tr>
                            <tr>
                                <th>Denda</th>
                                <td>Rp. <?= number_format($data['denda'], 0, ',', '.') ?></td>
                            </tr>
                            <?php if(!empty($data['date_return'])): ?>
                            <tr>
                                <th>Tanggal Dikembalikan</th>
                                <td><?= $data['date_return'] ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div>
                        <table id="detailHistory" class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th>Nama Buku</th>
                                    <th>Penerbit</th>
                                    <th>Pengarang</th>
                                    <!-- <th>Jatuh Tempo</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Denda</th> -->
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