<table>
    <tbody>
    <tr>
        <td>LastName:</td>
        <td>{{ $user->first_name }}</td>
    </tr>
    <tr>
        <td>FirstName:</td>
        <td>{{ $user->last_name }}</td>
    </tr>
    <tr>
        <td>Invoicing company:</td>
        <td>{{ $user->company }}</td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td>Reporting Year:</td>
        <td>{{ $year }}</td>
    </tr>
    <tr>
        <td>Reporting Month:</td>
        <td> {{ $month }}</td>
    </tr>
    <tr></tr>
    <tr>
        <td bgcolor="#00008b" style="color: white">Day</td>
        <td bgcolor="#00008b" style="color: white">Project/CR</td>
        <td bgcolor="#00008b" style="color: white">Customer</td>
        <td bgcolor="#00008b" style="color: white">Action</td>
        <td bgcolor="#00008b" style="color: white">Hours</td>
        <td bgcolor="#00008b" style="color: white">Comment</td>
    </tr>
    @foreach($timeEntries as $entries)
        <tr>
            <td width="25">{{ $entries['date'] }}</td>
            <td width="50">{{ $entries['project_name'] }}</td>
            <td width="50">{{ $entries['customer_names'] }}</td>
            <td width="50">{{ $entries['type_name'] }}</td>
            <td>{{ $entries['time'] }}</td>
            <td width="250">{{ $entries['comment'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
