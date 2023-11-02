<x-mail::message>
# Aufenthalt gefunden

<x-mail::table>
| Datum         | Link          |
| ------------- |:-------------:|
@foreach($daysInCommingWeek as $day)
| {{ $day['date']->format('d.m.Y') }}     | <a href="{{ $day['url'] }}">Buchen</a> |
@endforeach
</x-mail::table>




</x-mail::message>
