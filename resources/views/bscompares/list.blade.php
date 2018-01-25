@extends('layouts.master')

@section('content')
<div class="col-sm-8 blog-main">
    <a class="btn btn-primary" href="/bscheckpoint/add" role="button">Add</a>
    <table class="table">
        <thead>
            <th>Date</th><th>AccountID</th><th>Actual</th><th>Calculated</th><th>V.Diff</th><th>H.Diff</th><th>Delete</th>
        </thead>
        <tbody>
                <?php $previouscamt = 0 ?>
                @foreach($bscompares as $bscompare)
                    <tr>
                    
                        <td>{{ $bscompare->yymm }}</td>
                        <td>
                            @if($bscompare->accid == 'abank')
                                <a href="//dev.irealook.com/atr_bank.php?"
                                {{ $bscompare->accid }}
                            @else

                            @endif
                        </td>
                        <td class="number-align">{{ $bscompare->aamt }}</td>
                        <td class="number-align">{{ $bscompare->camt }}</td>
                        <td class="number-align">{{ $previouscamt - $bscompare->camt }}</td>
                        <td class="number-align">{{ $bscompare->camt - $bscompare->aamt }}</td>
                        <td><a href="/bscompares/accupdate/{{ $bscompare->yymm }}/{{ $bscompare->accid }}">Update</a></td>

                    </tr>
                    <?php $previouscamt = $bscompare->camt ?>
                @endforeach

        </tbody>

    </table>

</div>

    <a class="btn btn-primary" href="/bscheckpoint/add" role="button">Add</a>

@endsection


@section('layouts.footer')

<script type="text/javascript">
function w3_open() {
    $("div#sidemenubar").style.display = "block";
}

function w3_close() {
    $("div#sidemenubar").style.display = "none";
}
</script>

@endsection
