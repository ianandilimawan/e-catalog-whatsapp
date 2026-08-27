{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/register') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/login') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
@foreach ($stores as $store)
    <url>
        <loc>{{ url('/' . $store->slug) }}</loc>
        <lastmod>{{ ($store->updated_at ?? $store->created_at ?? now())->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
@endforeach
</urlset>
