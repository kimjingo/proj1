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
                                <a href="//dev.irealook.com/atr_bank.php?"{{ $bscompare->accid }}>
                                {{ $bscompare->accid }}</a>
                            @else
                                {{ $bscompare->accid }}
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


@section('footer')

<script type="text/javascript">
$(document).ready(function () {

    $('#sidebarCollapse').on('click', function () {
        // console.log("aa");
        $('#sidemenubar').toggleClass('active');
    });

});
</script>

@endsection
