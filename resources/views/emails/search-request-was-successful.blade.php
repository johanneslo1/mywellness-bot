<x-mail::message>
# Aufenthalt gefunden!
Es wurden ein oder mehrere Zeitslots gefunden, die deinen Suchkriterien entsprechen.

<x-mail::table>
| Datum         | Link          |
| ------------- |:-------------:|
@foreach($daysInCommingWeek as $day)
| {{ $day['date']->locale('de')->dayName }}, {{ $day['date']->format('d.m.Y') }}     | <a href="{{ $day['url'] }}">Buchen</a> |
@endforeach
</x-mail::table>
@php($url = \Illuminate\Support\Facades\URL::signedRoute('search-requests.show', $searchRequest->id))
<x-mail::button :url="$url" color="primary">
    Suchauftrag verwalten
</x-mail::button>



</x-mail::message>
