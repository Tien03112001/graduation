{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<rss {!! 'xmlns:g="http://base.google.com/ns/1.0"' !!} version="2.0">
    <channel>
        <title>{{env('APP_NAME')}}</title>
        <link>{{env('APP_URL')}}</link>
        <description>Product Catalog of {{config('app.name')}}</description>
        @foreach($rows as $row)
            <item>
                @foreach($row as $key=>$value)
                    <{{'g:'.$key}}>{{$value}}<{{'/g:'.$key}}>
                @endforeach
            </item>
        @endforeach
    </channel>
</rss>