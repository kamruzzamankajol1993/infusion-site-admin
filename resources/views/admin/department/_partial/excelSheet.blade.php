<table>
    <thead>
        <tr>
            <th >ID</th>
            <th >department/Agent Organization Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($departmentList as $key=>$departmentLists)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $departmentLists->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>