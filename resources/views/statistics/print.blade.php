<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Statistics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        h1, h2, h3 {
            text-align: center;
        }
        .no-print {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Printable Statistics</h1>

    <!-- Students -->
    @if($students->count() > 0)
        <h2>Students</h2>
        <table>
            <thead>
                <tr>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Site</th>
                    <th>Promotion</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->last_name }}</td>
                        <td>{{ $student->first_name }}</td>
                        <td>{{ $student->site->designation }}</td>
                        <td>
                            @foreach($student->promotions as $promotion)
                                {{ $promotion->num_promotion }}
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No students found.</p>
    @endif

    <!-- Subjects -->
    @if($matiers->count() > 0)
        <h2>Subjects</h2>
        <table>
            <thead>
                <tr>
                    <th>Designation</th>
                    <th>Coefficient</th>
                    <th>Site</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matiers as $matier)
                    <tr>
                        <td>{{ $matier->designation }}</td>
                        <td>{{ $matier->coef }}</td>
                        <td>{{ $matier->site->designation }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No subjects found.</p>
    @endif

    <!-- Users -->
    @if($users->count() > 0)
        <h2>Users</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Site</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->site->designation }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No users found.</p>
    @endif

    <!-- Print Button (hidden when printing) -->
    <div class="no-print">
        <button onclick="window.print()">Print</button>
    </div>
</body>
</html>
