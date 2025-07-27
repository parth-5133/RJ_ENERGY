<table border="1">
    <tr>
        <td colspan="{{ $colSpan }}" style="font-weight: bold;">Attendance Report -- {{ $monthName }} {{ $year }}</td>
    </tr>
    <tr style="font-weight: bold;">
        <td>Employee Name</td>
        @foreach ($days as $day)
            <td>{{ $day['day'] }}</td>
        @endforeach
    </tr>
    <tr>
        <td></td>
        @foreach ($days as $day)
            <td>{{ $day['weekday'] }}</td>
        @endforeach
    </tr>
    @foreach ($data as $row)
        <tr>
            @foreach ($row as $cell)
                <td>{{ $cell }}</td>
            @endforeach
        </tr>
    @endforeach
</table>

