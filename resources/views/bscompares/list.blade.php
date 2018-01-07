@extends('layouts.master')

@section('content')
<div class="col-sm-8 blog-main">
    <table class="table">
        <thead>
            <th>Date</th><th>AccountID</th><th>Actual</th><th>Calculated</th><th>Delete</th>
        </thead>
        <tbody>
            
                @foreach($bscompares as $bscompare)
                    <tr>
                    
                        <td>{{ $bscompare->yymm }}</td>
                        <td>{{ $bscompare->accid }}</td>
                        <td class="number-align">{{ $bscompare->aamt }}</td>
                        <td class="number-align">{{ $bscompare->camt }}</td>
                        <td><a href="/bscompares/accupdate/{{ $bscompare->yymm }}/{{ $bscompare->accid }}">Update</a></td>

                    </tr>
                @endforeach

        </tbody>

    </table>

    <a class="btn btn-primary" href="/bscheckpoint/add" role="button">Add</a>
</div>
@endsection


@section('layouts.footer')

<script type="text/javascript"></script>

@endsection
