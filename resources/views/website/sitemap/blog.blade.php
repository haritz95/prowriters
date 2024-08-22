<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?php echo '<?xml-stylesheet type="text/xsl" href="'.asset("css/sitemap.xsl").'"?>'?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if($posts->count() > 0)
        @foreach($posts as $post)
            <url>
                <loc>{{ URL::to($post->slug) }}</loc>
                @if($post->updated_at)
                    <lastmod>{{ $post->updated_at->tz('UTC')->toAtomString() }}</lastmod>
                @endif
            </url>
        @endforeach
    @endif
</urlset>
