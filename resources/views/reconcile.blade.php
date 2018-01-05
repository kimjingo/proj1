<!DOCTYPE html>
<html><head><title></title></head><body>
<ul>
	<table border=1><thead><th>parent</th><th>child</th><th>Delete</th></thead>
<tbody>
	
    @foreach($reconciles as $reconcile)
    	<tr>
    		
        <td>{{ $reconcile->accid }}</td>
        <td>{{ $reconcile->toreconcile }}</td>
        <td><a href="/reconcile/delete/{{ $reconcile->id }}">X</a></td>

    	</tr>
    @endforeach
</tbody>

	</table>
</ul>

</body></html>