<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\MealScore;
use App\Models\User;
use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealScoreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Score a meal post.
     */
    public function score(Request $request, Post $post)
    {
        // Vérifier que le post est de type repas
        if ($post->post_type !== 'meal') {
            return back()->with('error', 'Ce post n\'est pas un repas et ne peut pas être noté.');
        }
        
        $request->validate([
            'health_score' => 'required|integer|min:0|max:100',
            'visual_score' => 'required|integer|min:0|max:100',
            'diversity_score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:500',
        ]);
        
        // Créer ou mettre à jour le score
        $mealScore = MealScore::updateOrCreate(
            ['post_id' => $post->id],
            [
                'health_score' => $request->health_score,
                'visual_score' => $request->visual_score,
                'diversity_score' => $request->diversity_score,
                'feedback' => $request->feedback,
                'is_ai_scored' => false,
            ]
        );
        
        // Calculer le score total
        $mealScore->calculateTotalScore();
        $mealScore->save();
        
        // Accorder des points à l'auteur du repas s'il s'agit d'un bon score (plus de 60)
        if ($mealScore->total_score >= 60) {
            $points = min(floor($mealScore->total_score / 10), 10); // Max 10 points
            $post->user->awardPoints($points, 'meal_scored', 'Repas bien noté', ['score' => $mealScore->total_score]);
            
            // Mettre à jour les scores dans les ligues
            $this->updateUserLeagueScores($post->user, $points);
        }
        
        return back()->with('success', 'Score attribué avec succès ! Le repas a obtenu ' . $mealScore->total_score . ' points.');
    }
    
    /**
     * Request AI analysis for a meal post.
     */
    public function requestAIAnalysis(Post $post)
    {
        // Vérifier que le post est de type repas
        if ($post->post_type !== 'meal') {
            return back()->with('error', 'Ce post n\'est pas un repas et ne peut pas être analysé.');
        }
        
        // Dans une véritable implémentation, ici on enverrait l'image à une API d'IA
        // Pour l'instant, simulons une analyse avec des scores aléatoires
        $healthScore = rand(40, 95);
        $visualScore = rand(50, 95);
        $diversityScore = rand(30, 95);
        
        // Feedback simulé basé sur les scores
        $feedbacks = [
            'health' => [
                'low' => 'Ce repas pourrait bénéficier de plus d\'aliments nutritifs.',
                'medium' => 'Bon équilibre nutritionnel, mais quelques améliorations possibles.',
                'high' => 'Excellent choix d\'aliments nutritifs et équilibrés !'
            ],
            'visual' => [
                'low' => 'La présentation pourrait être améliorée pour plus d\'attrait visuel.',
                'medium' => 'Belle présentation avec quelques points à améliorer.',
                'high' => 'Présentation très attrayante et appétissante !'
            ],
            'diversity' => [
                'low' => 'Essayez d\'incorporer plus de variété de couleurs et de groupes alimentaires.',
                'medium' => 'Bonne variété alimentaire, mais pourrait être encore plus diversifiée.',
                'high' => 'Excellente diversité de nutriments et de groupes alimentaires !'
            ]
        ];
        
        // Générer le feedback en fonction des scores
        $healthFeedback = $healthScore < 60 ? $feedbacks['health']['low'] : ($healthScore < 80 ? $feedbacks['health']['medium'] : $feedbacks['health']['high']);
        $visualFeedback = $visualScore < 60 ? $feedbacks['visual']['low'] : ($visualScore < 80 ? $feedbacks['visual']['medium'] : $feedbacks['visual']['high']);
        $diversityFeedback = $diversityScore < 60 ? $feedbacks['diversity']['low'] : ($diversityScore < 80 ? $feedbacks['diversity']['medium'] : $feedbacks['diversity']['high']);
        
        $feedbackText = "$healthFeedback $visualFeedback $diversityFeedback";
        
        // Créer ou mettre à jour le score
        $mealScore = MealScore::updateOrCreate(
            ['post_id' => $post->id],
            [
                'health_score' => $healthScore,
                'visual_score' => $visualScore,
                'diversity_score' => $diversityScore,
                'feedback' => $feedbackText,
                'is_ai_scored' => true,
                'ai_analysis' => [
                    'detected_foods' => ['légumes', 'protéines', 'féculents'], // Simulé
                    'nutrition_estimation' => [
                        'calories' => rand(300, 800),
                        'proteins' => rand(15, 40),
                        'carbs' => rand(30, 80),
                        'fats' => rand(10, 30)
                    ]
                ]
            ]
        );
        
        // Calculer le score total
        $mealScore->calculateTotalScore();
        $mealScore->save();
        
        // Accorder des points à l'auteur du repas
        if ($mealScore->total_score >= 60) {
            $points = min(floor($mealScore->total_score / 10), 10); // Max 10 points
            $post->user->awardPoints($points, 'meal_ai_scored', 'Repas bien noté par l\'IA', ['score' => $mealScore->total_score]);
            
            // Mettre à jour les scores dans les ligues
            $this->updateUserLeagueScores($post->user, $points);
        }
        
        return back()->with('success', 'Analyse IA effectuée ! Le repas a obtenu ' . $mealScore->total_score . ' points.');
    }
    
    /**
     * Update user's scores in all leagues they are part of.
     */
    private function updateUserLeagueScores(User $user, $points)
    {
        foreach ($user->leagues as $league) {
            $league->updateMemberScore(
                $user, 
                $points, // weekly score
                $points, // monthly score
                $points  // total score
            );
        }
    }
} 