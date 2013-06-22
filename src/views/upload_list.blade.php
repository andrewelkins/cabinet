<script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/jquery.dataTables.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/datatables-tabletools/2.1.4/js/TableTools.min.js"></script>

<table id="uploads" class="table table-bordered table-hover">
    <thead>
    <tr>
        <th class="span2">{{{ Lang::get('admin/users/table.username') }}}</th>
        <th class="span3">{{{ Lang::get('admin/users/table.email') }}}</th>
        <th class="span3">{{{ Lang::get('admin/users/table.roles') }}}</th>
        <th class="span2">{{{ Lang::get('admin/users/table.activated') }}}</th>
        <th class="span2">{{{ Lang::get('admin/users/table.created_at') }}}</th>
        <th class="span2">{{{ Lang::get('table.actions') }}}</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>



<script type="text/javascript">
    var oTable;
    $(document).ready(function() {
        oTable = $('#uploads').dataTable( {
            "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            },
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "{{ URL::to('uploads/data') }}"
        });
    });
</script>
