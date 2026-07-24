<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Task Created</title>
</head>

<body style="
    margin:0;
    padding:0;
    background:#f4f6f8;
    font-family:Arial, sans-serif;
">

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" style="padding:40px 0;">

            <table width="600"
                   style="
                   background:white;
                   border-radius:12px;
                   padding:30px;
                   box-shadow:0 4px 15px rgba(0,0,0,0.1);
                   ">

                <tr>
                    <td align="center">

                        <h1 style="
                            color:#2563eb;
                            margin-bottom:20px;
                        ">
                            🎉 New Task Created
                        </h1>

                    </td>
                </tr>


                <tr>
                    <td>

                        <p style="font-size:16px;">
                            Hello {{ $task->user->name }},
                        </p>

                        <p style="font-size:16px;">
                            A new task has been successfully created.
                        </p>


                        <div style="
                            background:#f1f5f9;
                            padding:20px;
                            border-radius:10px;
                            margin:20px 0;
                        ">

                            <h2 style="
                                margin-top:0;
                                color:#111827;
                            ">
                                {{ $task->title }}
                            </h2>


                            <p>
                                <strong>Description:</strong>
                                {{ $task->description ?? 'No description' }}
                            </p>


                            <p>
                                <strong>Status:</strong>
                                {{ $task->status }}
                            </p>


                            <p>
                                <strong>Category:</strong>
                                {{ $task->category->name ?? 'No category' }}
                            </p>

                        </div>


                        <p style="
                            color:#6b7280;
                            font-size:14px;
                        ">
                            Created at:
                            {{ $task->created_at->format('d.m.Y H:i') }}
                        </p>


                    </td>
                </tr>


                <tr>
                    <td align="center">

                        <p style="
                            margin-top:30px;
                            color:#9ca3af;
                            font-size:13px;
                        ">
                            © {{ date('Y') }} TODO API
                        </p>

                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>


</body>
</html>
