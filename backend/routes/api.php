<?php

use App\Http\Controllers;
use App\Http\Controllers\AgenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DomaineCompetenceController;
use App\Http\Controllers\NiveauCompetenceController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\JourFerieController;
use App\Http\Controllers\UserDomaineCompetenceController;
use App\Http\Controllers\ModuleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['cors', 'json.response']], function () {



    // Route pour envoyer le lien de réinitialisation de mot de passe
    Route::post('/password/email', [ApiAuthController::class, 'sendResetLinkEmail']);
    // Route pour réinitialiser le mot de passe
    Route::post('/password/reset', [ApiAuthController::class, 'reset']);

    Route::post('/login', [ApiAuthController::class, 'login'])->name('login.api');
    Route::post('/register', [ApiAuthController::class, 'register']);
    // ...
    Route::post('/change-password', [App\Http\Controllers\Auth\ApiAuthController::class, 'changePassword']);
});

Route::middleware('auth:api')->group(function () {
    // Protected routes
    Route::get('/login-audits', [ApiAuthController::class, 'getLoginAudits']);
    Route::middleware('auth:api')->post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/me', [ApiAuthController::class, 'me']);
    Route::get('/users', [UserController::class, 'users'])->name('users');
    Route::put('/users/{id}/password', [UserController::class, 'updatePassword']);
    Route::post('/users/{id}/firstconnect', [UserController::class, 'updateFirstConnect']);
    Route::get('/user', [UserController::class, 'getUser']);

    Route::get('/users/type-zero', [UserController::class, 'getTypeZeroUsers']);
    Route::post('/users/{id}/deactivate', [UserController::class, 'deactivate']);
    Route::post('/users/{id}/activate', [UserController::class, 'activate']);
    Route::post('/store', [ApiAuthController::class, 'store'])->name('store.user.api');
    Route::post('/users/{id}', [UserController::class, 'update']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/users/{id}', [UserController::class, 'showAll']);
    // Route pour récupérer les utilisateurs disponibles pour une intervention donnée
    Route::get('/interventions/{interventionId}/available-users', [UserController::class, 'getAvailableUsers']);
    Route::get('/userSearch', [UserController::class, 'getUsers']);
    Route::get('/interventions', [InterventionController::class, 'index1']);
    Route::get('/interventions/{id}/users', [InterventionController::class, 'getAssignedUsers']);



//liste des routes pour les demandes
 Route::get('/demandes', [DemandeController::class, 'index']);
    Route::post('/demandes', [DemandeController::class, 'store']);
    Route::get('/demandes/{id}', [DemandeController::class, 'show']);
    Route::get('/demande/', [DemandeController::class, 'showit']);
    Route::put('/demandes/{id}', [DemandeController::class, 'update']);
    Route::delete('/demandes/{id}', [DemandeController::class, 'destroy']);

    // List all agences
    Route::get('/agences', [AgenceController::class, 'index']);

    // Store a new agence
    Route::post('/agences', [AgenceController::class, 'store']);

    // Show a specific agence
    Route::get('/agences/{id}', [AgenceController::class, 'show']);

    // Update a specific agence
    Route::put('/agences/{id}', [AgenceController::class, 'update']);

    // Delete a specific agence
    Route::delete('/agences/{id}', [AgenceController::class, 'destroy']);
    //pour afficher les users selon l'agence
    Route::get('/agences/{agenceId}/users', [AgenceController::class, 'getUsersByAgence']);
    //les metodes proteges du client

    Route::get('/clients', [ClientController::class, 'index']); // Liste tous les clients avec leurs interventions
    Route::post('/clients', [ClientController::class, 'store']); // Crée un nouveau client
    Route::get('/clients/{id}', [ClientController::class, 'show']); // Affiche un client spécifique et ses interventions
    Route::get('clients/{id}', [ClientController::class, 'showby']);
    Route::put('/clients/{id}', [ClientController::class, 'update']); // Met à jour un client existant
    Route::delete('/clients/{id}', [ClientController::class, 'destroy']); // Supprime un client


    Route::delete('/{userId}/competence/{domaineId}', [UserController::class, 'removeCompetence']); // Retirer une compétence d'un utilisateur
    Route::post('/user-domaine-competence', [DomaineCompetenceController::class, 'storeUserDomaineCompetence']);
    Route::delete('user-domaine-competence/{id}', [DomaineCompetenceController::class, 'DELETE']);

    Route::post('user/{id}/competences', [UserController::class, 'up']);
    Route::post('/users/{id}/competence', [UserController::class, 'addCompetence']);
    // Route to list the skills (domaines de compétence) of a user
    Route::get('/users/{id}/skills', [UserController::class, 'getUserSkills']);


    // Dans routes/web.php ou routes/api.php
    Route::get('/user/{userId}/interventions', [InterventionController::class, 'getUserInterventions']);
    Route::get('/mycalendar', [InterventionController::class, 'getAuthenticatedUserInterventions']);


    Route::get('/domaines-competence', [DomaineCompetenceController::class, 'index']);
    Route::post('/domaines-competence', [DomaineCompetenceController::class, 'store']);
    Route::put('/domaines-competence/{id}', [DomaineCompetenceController::class, 'update']);
    Route::delete('/domaines-competence/{id}', [DomaineCompetenceController::class, 'destroy']);

    // Route pour créer l'association entre utilisateur et domaine de compétence
    Route::post('/user-domaine-competence', [UserDomaineCompetenceController::class, 'store']);


    Route::get('/niveaux-competence', [NiveauCompetenceController::class, 'index']);
    Route::post('/niveaux-competence', [NiveauCompetenceController::class, 'store']);
    Route::put('/niveaux-competence/{id}', [NiveauCompetenceController::class, 'update']);
    Route::delete('/niveaux-competence/{id}', [NiveauCompetenceController::class, 'destroy']);


    Route::get('intervention', [InterventionController::class, 'index']);
    Route::post('interventions', [InterventionController::class, 'store']);
    Route::get('interventions/{id}', [InterventionController::class, 'show']);
    Route::post('interventions/{id}', [InterventionController::class, 'update']);
    Route::delete('interventions/{id}', [InterventionController::class, 'destroy']);
    // Route pour ajouter une nouvelle intervention
    Route::post('/interventions', [InterventionController::class, 'store']);
    Route::get('/clients/{clientId}/interventions', [InterventionController::class, 'getInterventionsByClient']);
   

    Route::get('/jours-feries', [JourFerieController::class, 'index']);
    Route::post('/jours-feries', [JourFerieController::class, 'store']);
    Route::get('/jours-feries/{id}', [JourFerieController::class, 'show']);
    Route::put('/jours-feries/{id}', [JourFerieController::class, 'update']);
    Route::delete('/jours-feries/{id}', [JourFerieController::class, 'destroy']);


    //route modules

    Route::get('/modules', [ModuleController::class, 'index']);
});
