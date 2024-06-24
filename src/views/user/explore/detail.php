<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <a href="<?= getBaseURL() ?>/explore" class="btn color-bg-green-1 text-white hover">Kembali</a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="container px-5">
                                            <div class="row">
                                                <div class="col-12 col-md-3 col-lg-3">
                                                    <img src="<?= getBaseURL() ?>/<?= $dataBook['picture'] ?>" alt="" class="w-100">
                                                </div>
                                                <div class="col-12 col-md-9 col-lg-9">
                                                    <h4><?= $dataBook['title'] ?></h4>
                                                    <h5>Penerbit: <?= $dataBook['name'] ?></h5>
                                                    <h5>Pengarang: <ol class="d-inline-block pl-4">
                                                        <?php foreach($dataAuthors as $author): ?>
                                                            <li><?= $author['name'] ?></li>
                                                        <?php endforeach; ?>
                                                    </ol></h5>
                                                    <h5>Deskripsi: </h5>
                                                    <p><?= empty($dataBook['description']) ? "Tidak ada deskripsi" : $dataBook['description'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>