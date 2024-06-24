<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-body">
                    <table id="listBooks" class="table table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>Nama Buku</th>
                                <th>Nama Penerbit</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <?php 
                            foreach($books as $book) {
                                echo "<tr>";
                                    echo "<td>" . $book['title'] . "</td>";
                                    echo "<td>" . $book['publisher_name'] . "</td>";
                                    echo "<td>" . $book['stock'] . "</td>";
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
        $('#listBooks').addClass("nowrap").dataTable({
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            scrollCollapse: true,
            // columnDefs: [
            //     {
            //         target: 5,
            //         render: DataTable.render.date(),
            //     },
            // ]
        })
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>