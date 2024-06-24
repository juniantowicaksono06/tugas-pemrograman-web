<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-12">
            <a href="/borrow-history">
                <div class="card bg-primary">
                    <div class="card-body">
                        <h3>Total Peminjaman</h3>
                        <div class="d-flex justify-content-between">
                            <h4 class="fa-3x"><?= $totalBorrow ?></h4>
                            <span><i class="fa fa-book fa-4x"></i></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
            <a href="/fines-history">
                <div class="card bg-success">
                    <div class="card-body">
                        <h3>Total Denda</h3>
                        <div class="d-flex justify-content-between">
                            <h4 class="fa-3x">Rp. <?= number_format($totalFines, 0, ',', '.') ?></h4>
                            <span><i class="fa fa-user fa-4x"></i></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>