{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

@foreach($products as $p)
<url>
    <loc>{{ $p['loc'] }}</loc>
    <lastmod>{{ $p['lastmod'] }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
</url>
@endforeach

</urlset>
