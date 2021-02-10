<style>
    .filterable .filters input[disabled] {
        background-color: transparent;
        border: none;
        cursor: auto;
        box-shadow: none;
        padding: 0;
        height: auto;
        font-weight: bolder;
    }
    .filterable .filters input[disabled]::-webkit-input-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]::-moz-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]:-ms-input-placeholder {
        color: #333;
    }
    .filterable tbody *{
        font-size: 12px;
    }
</style>
<div class="table-responsive">
    <div class="filterable">
        <div class="card">
            <h5 class="card-header">Files<button class="btn btn-success btn-xs btn-filter float-right"><span class="glyphicon glyphicon-filter"></span> Filter</button></h5>
        </div>
        <table class="table table-striped table-bordered table-sm">
            <thead>
                <tr class="filters">
                    <th><input type="text" class="form-control" placeholder="Pathname" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Uploader" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Module" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Branch" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Unity" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Semester" disabled></th>
                    <?php if(AuthUser::IsAdministrator()){ ?>
                    <th>Action</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($files as $k => $v){ ?>
                    <tr>
                        <td><i><b><a title="<?= $v["uploadedAt"] ?>" target="_blank" href="<?= WEBROOT."uploads/".$v["path"] ?>"><?= pathinfo($v["path"], PATHINFO_BASENAME) ?></a></b></i></td>
                        <td><a href='<?= ROOT."users/profile/".$v["user_id"] ?>'><?= $v["firstName"]." ".$v["lastName"] ?></a></td>
                        <td><?= $v["module"] ?></td>
                        <td><?= $v["branch"] ?></td>
                        <td><?= $v["unity"] ?></td>
                        <td style="width:90px;"><?= $v["semester"] ?></td>
                        <?php if(AuthUser::IsAdministrator()){ ?>
                        <td style="width:90px;">
                            <div onclick="editFile('<?= ROOT.'files/confirmed/'.$v['id'] ?>',$(this))" class="btn btn-light" title="<?= intval($v["confirmed"])==0?'Confirm':'Annul'?>"><i class="fa <?= intval($v["confirmed"])==0?'fa-check-circle':'fa-minus-circle'?>"></i></div>
                            <div onclick="editFile('<?= ROOT.'files/delete/'.$v['id'] ?>',$(this))" class="btn btn-danger" title="Delete"><i class="fa fa-trash-alt"></i></div>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function editFile(url,t){
        if(url.indexOf("delete") !== -1){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.value) {
                    $.get(url,function(data,status){
                        if(status === "success"){
                            let tr = t.parent("td").parent("tr");
                            tr.hide(300,function(){
                                tr.remove();
                            });
                        }
                    });
                }
            });
        }
        else{
            $.get(url,function(data,status){
                if(status === "success"){
                    t.find('i').toggleClass('fa-check-circle').toggleClass('fa-minus-circle');
                    t.attr("title",(t.attr("title") === "Confirm"?"Annul":"Confirm"));
                }
            });
        }
    }
    $(document).ready(function(){
        $('.filterable .btn-filter').click(function(){
            var $panel = $(this).parents('.filterable'),
            $filters = $panel.find('.filters input'),
            $tbody = $panel.find('.table tbody');
            if ($filters.prop('disabled') == true) {
                $filters.prop('disabled', false);
                $filters.first().focus();
            } else {
                $filters.val('').prop('disabled', true);
                $tbody.find('.no-result').remove();
                $tbody.find('tr').show();
            }
        });
        
        $('.filterable .filters input').keyup(function(e){
            /* Ignore tab key */
            var code = e.keyCode || e.which;
            if (code == '9') return;
            var $table = $(".filterable table"),$rows = $table.find('tbody tr'),$cols = $(".filters input");
            var $filteredRows = $rows.filter(function(){
                let verified=false;
                for(let i=0;i<$cols.length;i++){
                    var tdValue = $(this).find('td').eq(i).text().toLowerCase(),
                    inputValue = $(".filters th input").eq(i).val().toLowerCase();
                    verified = verified || (tdValue.indexOf(inputValue) === -1);
                }
                return verified;
            });
            $table.find('tbody .no-result').remove();
            $rows.show();
            $filteredRows.hide();
            if ($filteredRows.length === $rows.length) {
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
            }
        });
    });
</script>