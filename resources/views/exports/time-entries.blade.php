<table>
    <tbody>
    <tr>
        <td>LastName</td>
        <td>name</td>
    </tr>
    <tr>
        <td>LastName</td>
        <td>name</td>
    </tr>
    <tr>
        <td>LastName</td>
        <td>name</td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td>LastName</td>
        <td>name</td>
    </tr>
    <tr>
        <td>LastName</td>
        <td>name</td>
    </tr>
    <tr>
    </tr>
    @foreach($timeEntries as $entries)
        <tr>
            <td>{{ $entries->project_id }}</td>
            <td>{{ $entries->date }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
