<?xml version="1.0" encoding="UTF-8" ?>
<source>
   <publisher>Réserve Civique</publisher>
   <publisherurl>https://jeveuxaider.gouv.fr</publisherurl>
   <lastBuildDate>{{  now() }}</lastBuildDate>
    @foreach ($missions as $mission)
    <mission>
        <title><![CDATA[{{ $mission->name }}]]></title>
        <clientId><![CDATA[{{ $mission->id }}]]></clientId>
        <description><![CDATA[{{ $mission->description }}\n\nObjectifs: \n{{ $mission->objectif }}]]></description>
        <applicationUrl><![CDATA[{{ config('app.url') }}/missions/{{ $mission->id }}/{{ $mission->slug }}]]></applicationUrl>
        <organizationName><![CDATA[{{ $mission->structure->name }}]]></organizationName>
        <organizationId><![CDATA[{{ $mission->structure->id }}]]></organizationId>
        <organizationUrl><![CDATA[{{ $mission->structure->website }}]]></organizationUrl>
        <organizationFullAddress><![CDATA[{{ $mission->structure->address }}, {{ $mission->structure->zip }} {{ $mission->structure->city }}, {{ $mission->structure->country }}]]></organizationFullAddress>
        <postedAt><![CDATA[{{ $mission->created_at }}]]></postedAt>
        <startAt><![CDATA[{{ $mission->start_date }}]]></startAt>
        <endAt><![CDATA[{{ $mission->end_date }}]]></endAt>
        <adresse><![CDATA[{{ $mission->address }}, {{ $mission->zip }} {{ $mission->city }}]]></adresse>
        <postalCode><![CDATA[{{ $mission->zip }}]]></postalCode>
        <departmentName><![CDATA[{{ config('taxonomies.departments.terms')[$mission->department] }}]]></departmentName>
        <departmentCode><![CDATA[{{ $mission->department }}]]></departmentCode>
        <city><![CDATA[{{ $mission->city }}]]></city>
        <country><![CDATA[{{ $mission->country }}]]></country>
        <lonlat><![CDATA[{{ $mission->longitude }},{{ $mission->latitude }}]]></lonlat>
        <places><![CDATA[{{ $mission->places_left }}]]></places>
        @if ($mission->type == 'Mission en présentiel')
            <remote><![CDATA[no]]></remote>
        @elseif ($mission->type == 'Mission à distance')
            <remote><![CDATA[full]]></remote>
        @endif
        @php
            $domain_id = $mission->template ? $mission->template->domaine->id : $mission->domaine->id;
        @endphp
        @switch($domain_id)
        @case(1) {{-- Mobilisation covid-19 --}}
            <domain><![CDATA[sante]]></domain>
            @break
        @case(2) {{-- Éducation pour tous  --}}
            <domain><![CDATA[education]]></domain>
            @break
        @case(3) {{-- Santé pour tous  --}}
            <domain><![CDATA[sante]]></domain>
            @break
        @case(4) {{-- Protection de la nature  --}}
            <domain><![CDATA[environnement]]></domain>
            @break
        @case(6) {{-- Solidarité et insertion  --}}
            <domain><![CDATA[solidarite-insertion]]></domain>
            @break
        @case(7) {{-- Sport pour tous  --}}
            <domain><![CDATA[sport]]></domain>
            @break
        @case(8) {{-- Prévention et protection  --}}
            <domain><![CDATA[autre]]></domain>
            @break
        @case(9) {{-- Mémoire et citoyenneté  --}}
            <domain><![CDATA[vivre-ensemble]]></domain>
            @break
        @case(10) {{-- Coopération internationale  --}}
            <domain><![CDATA[vivre-ensemble]]></domain>
            @break
        @case(11) {{-- Art et culture pour tous  --}}
            <domain><![CDATA[culture-loisirs]]></domain>
            @break
        @endswitch
    </mission>
    @endforeach
</source>

