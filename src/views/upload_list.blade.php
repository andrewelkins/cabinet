<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/jquery.dataTables.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/datatables-tabletools/2.1.4/js/TableTools.min.js"></script>

<table id="uploads" class="table table-bordered table-hover">
    <thead>
    <tr>
        <th class="span2">{{{ Lang::get('cabinet::table.filename') }}}</th>
        <th class="span2">{{{ Lang::get('cabinet::table.file_path') }}}</th>
        <th class="span2">{{{ Lang::get('cabinet::table.extension') }}}</th>
        <th class="span2">{{{ Lang::get('cabinet::table.size') }}}</th>
        <th class="span2">{{{ Lang::get('cabinet::table.mimetype') }}}</th>
        <th class="span2">{{{ Lang::get('cabinet::table.user_id') }}}</th>
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
            "sAjaxSource": "{{ URL::to('upload/data') }}"
        });
    });
</script>
