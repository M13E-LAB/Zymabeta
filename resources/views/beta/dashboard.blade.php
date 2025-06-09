@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">📊 Dashboard Beta ZYMA</h1>
            <p class="text-gray-600">Suivi des inscriptions et codes d'invitation</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-blue-500 text-white p-6 rounded-xl">
                <div class="text-3xl font-bold">{{ $stats['total_codes'] }}</div>
                <div class="text-blue-100">Codes générés</div>
            </div>
            <div class="bg-green-500 text-white p-6 rounded-xl">
                <div class="text-3xl font-bold">{{ $stats['used_codes'] }}</div>
                <div class="text-green-100">Codes utilisés</div>
            </div>
            <div class="bg-orange-500 text-white p-6 rounded-xl">
                <div class="text-3xl font-bold">{{ $stats['available_codes'] }}</div>
                <div class="text-orange-100">Codes disponibles</div>
            </div>
            <div class="bg-purple-500 text-white p-6 rounded-xl">
                <div class="text-3xl font-bold">{{ $stats['beta_users'] }}</div>
                <div class="text-purple-100">Beta testeurs</div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white rounded-xl p-6 mb-8 shadow-lg">
            <h3 class="text-xl font-bold mb-4">Progression Beta ({{ $stats['used_codes'] }}/50)</h3>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-blue-600 h-4 rounded-full transition-all duration-300" 
                     style="width: {{ ($stats['used_codes'] / 50) * 100 }}%"></div>
            </div>
            <p class="text-sm text-gray-600 mt-2">
                {{ 50 - $stats['used_codes'] }} places restantes
            </p>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl p-6 mb-8 shadow-lg">
            <h3 class="text-xl font-bold mb-4">Actions</h3>
            <div class="flex gap-4">
                <button onclick="generateCodes()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    🎫 Générer 50 Codes
                </button>
                <a href="{{ route('beta.codes') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                    📋 Voir tous les codes
                </a>
            </div>
        </div>

        <!-- Recent Invitations -->
        <div class="bg-white rounded-xl p-6 shadow-lg">
            <h3 class="text-xl font-bold mb-4">Invitations récentes</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Code</th>
                            <th class="text-left py-2">Utilisateur</th>
                            <th class="text-left py-2">Email</th>
                            <th class="text-left py-2">Date</th>
                            <th class="text-left py-2">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentInvitations as $invitation)
                        <tr class="border-b">
                            <td class="py-2 font-mono">{{ $invitation->code }}</td>
                            <td class="py-2">
                                {{ $invitation->user ? $invitation->user->name : '-' }}
                            </td>
                            <td class="py-2">
                                {{ $invitation->user ? $invitation->user->email : $invitation->email ?? '-' }}
                            </td>
                            <td class="py-2">{{ $invitation->used_at ? $invitation->used_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="py-2">
                                @if($invitation->used)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">✅ Utilisé</span>
                                @else
                                    <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs">⏳ Disponible</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function generateCodes() {
    if (confirm('Générer les codes d\'invitation beta ?')) {
        fetch('{{ route("beta.generate") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload();
        })
        .catch(error => {
            alert('Erreur: ' + error);
        });
    }
}
</script>
@endsection 