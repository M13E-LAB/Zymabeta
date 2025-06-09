@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
            <!-- Logo -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-blue-600 mb-2">ZYMA</h1>
                <div class="inline-block bg-orange-100 text-orange-600 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    🚀 Version Beta
                </div>
                <p class="text-gray-600">L'app nutrition communautaire</p>
            </div>

            <!-- Description Beta -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Accès Beta Privé</h2>
                <p class="text-gray-600 mb-4">
                    ZYMA est actuellement en phase beta avec <strong>50 testeurs sélectionnés</strong>. 
                    Pour rejoindre la communauté, vous avez besoin d'un code d'invitation.
                </p>
                
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 text-left">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>✨ Fonctionnalités disponibles :</strong><br>
                                • Scanner de codes-barres<br>
                                • Comparaison de prix en temps réel<br>
                                • Partage de repas par ligues<br>
                                • Scoring nutritionnel IA<br>
                                • Feed social communautaire
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire Code -->
            <form method="POST" action="{{ route('beta.verify') }}" class="mb-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Code d'invitation Beta
                    </label>
                    <input type="text" 
                           name="code" 
                           value="{{ old('code') }}"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-center text-lg font-mono tracking-wider"
                           placeholder="Entrez votre code"
                           maxlength="10"
                           style="text-transform: uppercase;">
                    
                    @error('code')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105">
                    🔓 Vérifier le Code
                </button>
            </form>

            <!-- Contact -->
            <div class="text-center text-sm text-gray-500">
                <p>Vous n'avez pas de code d'invitation ?</p>
                <p class="mt-2">Contactez-nous pour rejoindre la liste d'attente !</p>
                <a href="mailto:beta@zyma.app" class="text-blue-600 hover:text-blue-800 font-semibold">
                    beta@zyma.app
                </a>
            </div>
        </div>

        <!-- Stats Beta -->
        <div class="mt-6 text-center text-white text-sm opacity-75">
            <p>🔥 Beta limitée • 50 testeurs maximum</p>
        </div>
    </div>
</div>
@endsection 