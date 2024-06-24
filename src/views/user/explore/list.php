<div class="container-fluid">
    <div class="row">
        <div class="col-12 connectedSortable">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" placeholder="Cari buku..." />
                            <div class="input-group-append" id="searchBtn">
                                <span class="input-group-text bg-primary"><i class="fa fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row" id="dataContainer"></div>
                    </div>
                    <div class="container-fluid" id="paginationContainer"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var data = <?= json_encode($data); ?>;
        function search() {
            var query = $("#search").val().toLowerCase();
            var filteredData = data.filter(function(item) {
                if(query === "") {
                    return true;
                }
                return item['title'].toLowerCase().includes(query);
            });
            renderData(filteredData)
        }
        function renderData(data) {
            $("#paginationContainer").pagination({
                dataSource: data,
                pageSize: 8,
                callback: function(data, pagination) {
                    var html = data.map(function(item) {
                        return `
                            <div class="col-12 col-md-3 col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <img src="<?= getBaseURL() ?>/${item['picture']}" class="w-100" />
                                        </div>
                                        <div class="mb-3">
                                            <h5>${item['title']}</h5>
                                        </div>
                                        <hr />
                                        <div class="mb-3">
                                            <p>${item['publisher_name']}</p>
                                        </div>
                                        <div>
                                            <a href="<?= getBaseURL() ?>/explore/detail/${item['id']}" class="btn btn-primary">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `
                    }).join('');
                    $('#dataContainer').html(html);
                }
            })
        };
        $(document).ready(function() {
            renderData(data)
            $('input.form-control').on('keydown', (event) => {
                if(event.key == 'Enter') {
                    search();
                }
            })
        });
        $("#searchBtn").on("click", function() {
            search()
        });
    })
</script>