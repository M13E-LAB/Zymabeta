<?php

namespace App\Http\Controllers;

use App\Models\BetaInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BetaController extends Controller
{
    /**
     * Page d'accueil beta - demande de code
     */
    public function welcome()
    {
        return view('beta.welcome');
    }

    /**
     * Vérifier le code d'invitation
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10'
        ]);

        $invitation = BetaInvitation::where('code', strtoupper($request->code))
                                  ->where('used', false)
                                  ->first();

        if (!$invitation) {
            return back()->withErrors(['code' => 'Code d\'invitation invalide ou déjà utilisé.']);
        }

        // Stocker le code en session pour l'inscription
        session(['valid_beta_code' => $invitation->code]);
        
        return redirect()->route('register')->with('success', 'Code valide ! Vous pouvez maintenant vous inscrire.');
    }

    /**
     * Générer 50 codes d'invitation (pour l'admin)
     */
    public function generateCodes()
    {
        // Vérifier qu'on n'a pas déjà 50 codes
        $existingCodes = BetaInvitation::count();
        $codesToGenerate = max(0, 50 - $existingCodes);

        if ($codesToGenerate === 0) {
            return response()->json(['message' => '50 codes déjà générés']);
        }

        $codes = [];
        for ($i = 0; $i < $codesToGenerate; $i++) {
            $code = BetaInvitation::generateUniqueCode();
            BetaInvitation::create(['code' => $code]);
            $codes[] = $code;
        }

        return response()->json([
            'message' => "$codesToGenerate codes générés",
            'codes' => $codes
        ]);
    }

    /**
     * Dashboard beta pour voir les stats
     */
    public function dashboard()
    {
        $stats = [
            'total_codes' => BetaInvitation::count(),
            'used_codes' => BetaInvitation::where('used', true)->count(),
            'available_codes' => BetaInvitation::where('used', false)->count(),
            'beta_users' => \App\Models\User::where('is_beta_tester', true)->count()
        ];

        $recentInvitations = BetaInvitation::with('user')
                                        ->orderBy('updated_at', 'desc')
                                        ->limit(10)
                                        ->get();

        return view('beta.dashboard', compact('stats', 'recentInvitations'));
    }

    /**
     * Lister tous les codes (pour l'admin)
     */
    public function listCodes()
    {
        $codes = BetaInvitation::with('user')
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('beta.codes', compact('codes'));
    }
} 