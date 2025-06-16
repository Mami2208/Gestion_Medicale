<div class="container mx-auto p-6 max-w-6xl">
    <h1 class="text-3xl font-bold mb-6">Journal d'audit</h1>

    <table class="min-w-full bg-white border border-gray-300 rounded">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Utilisateur</th>
                <th class="py-2 px-4 border-b">Action</th>
                <th class="py-2 px-4 border-b">IP</th>
                <th class="py-2 px-4 border-b">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td class="py-2 px-4 border-b">{{ $log->id }}</td>
                <td class="py-2 px-4 border-b">{{ $log->user->nom ?? 'N/A' }} {{ $log->user->prenom ?? '' }}</td>
                <td class="py-2 px-4 border-b">{{ $log->action }}</td>
                <td class="py-2 px-4 border-b">{{ $log->ip_address }}</td>
                <td class="py-2 px-4 border-b">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
