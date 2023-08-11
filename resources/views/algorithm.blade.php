<h1>Algorithm</h1>
<h2>Section I</h2>

@foreach($data as $key => $dt)
    <p>{{'Case '.$key+1}}</p>
    <div>
        - Input:
        <div>
            <pre>@php print_r($testcases[$key]) @endphp</pre>
        </div>
    </div>
    <div>
        - Output:
        @foreach($dt['data'] as $d)
        <p>[Contract with] {{$d['name']}} {{$d['container']}} container, price {{$d['totalCost']}}</p>
        @endforeach

        @if(!empty($dt['data']))
            @if($dt['container_actual'] < $dt['container_needed'])
                <p>Not enough container</p>
            @endif
            <p>[Summary] total cost {{$dt['container_cost']}}</p>
        @endif
    </div>
@endforeach
