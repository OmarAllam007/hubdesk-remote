<table class="listing-table" >
    <thead class="table-design">
    <tr>
        <th>File</th>
        <th>Uploaded By</th>
        <th>At</th>
        <th>&nbsp;</th>
    </tr>
    </thead>

    @foreach($ticket->files as $file)
    <tr>
        <td><a href="{{route('ticket.attachment.download',$file)}}" target="_blank" >{{$file->display_name}}</a></td>
        <td>{{$file->uploaded_by}}</td>
        <td>{{$file->created_at->format('d/m/Y H:i')}}</td>
        <th><a href="{{route('ticket.attachment.download',$file)}}" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-download"></i> Download</a></th>    </tr>
    @endforeach
</table>

