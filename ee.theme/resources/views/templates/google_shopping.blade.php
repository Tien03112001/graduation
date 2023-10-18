{!!'<?xml version = "1.0" encoding="UTF-8"?>' !!}
<feed xmlns="http://www.w3.org/2005/Atom" {!!'xmlns:g="http://base.google.com/ns/1.0"'!!}>
    <title>{{config('app.name')}}</title>
    <link rel="self" href="{{config('app.url')}}"/>
    <updated>{{now()->toISOString()}}</updated>

    @foreach($rows as $row)
        <entry>
            @foreach($row as $key=>$value)
                <{{'g:'.$key}}>{{$value}}<{{'/g:'.$key}}>
            @endforeach
        </entry>
    @endforeach
</feed>