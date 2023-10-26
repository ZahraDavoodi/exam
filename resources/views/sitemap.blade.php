<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($properties as $property)
    <url>
         <loc>https://parsa-perfume.ir/property_info/{{$property->slug}}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime($property->property_updatedat)) }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
@endforeach


@foreach($hotels as $hotel)
    <url>
                <loc>https://parsa-perfume.ir/hotel_info/{{$hotel->slug}}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime($hotel->hotel_updatedat)) }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
@endforeach

</urlset>

