<?php
;
namespace App\Http\Controllers\Auth;

use App\Models\User; // Assurez-vous que le modèle User est correctement référencé;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use App\Notifications\ResetPasswordNotification;
use App\Models\LoginAudit;
use App\Models\UserCompetence;

class ApiAuthController extends Controller
{
    /**
     * Handle user registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'domaine_competence' => 'required|string|max:255',
            'statut' => 'required|string|max:255',
            'type' => 'integer',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $request['type'] = $request['type'] ? $request['type']  : 0;
        // Hash the password
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);

        // Create the user
        $user = User::create($request->toArray());

        // Generate the access token
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token];

        return response($response, 200);
    }

    /**
     * Handle user login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            $this->logLoginAttempt($request, false, 'Validation failed');
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Check if the password matches
            if (Hash::check($request->password, $user->password)) {
                // Generate the access token
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;

                // Check if the user needs to change the password
                if ($user->firstconnect == 'ON') {
                    $this->logLoginAttempt($request, true, 'Password change required');
                    return response(['message' => 'Veuillez changer votre mot de passe pour continuer.']);
                } else {
                    $response = [
                        'token' => $token,
                        'type' => $user->type  // Include the user type in the response
                    ];

                    $this->logLoginAttempt($request, true, 'Login successful');
                    return response($response, 200);
                }
            } else {
                $this->logLoginAttempt($request, false, 'Incorrect password');
                return response(['message' => 'Votre adresse email ou votre mot de passe sont incorrects.'], 422);
            }
        } else {
            $this->logLoginAttempt($request, false, 'User does not exist');
            return response(['message' => 'L\'utilisateur n\'existe pas.'], 422);
        }
    }

    private function logLoginAttempt(Request $request, bool $success, string $message)
    {
        LoginAudit::create([
            'email' => $request->email,
            'ip_address' => $request->ip(),
            'success' => $success,
            'message' => $message,
        ]);
    }

    /**
     * Handle user password change.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Retourner les erreurs de validation s'il y en a
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Check if the password matches
            if (Hash::check($request->old_password, $user->password)) {
                // Hash the new password
                $user->password = Hash::make($request->password);
                $user->firstconnect = 'NO';
                $user->save();

                return response(['message' => 'Votre mot de passe a été changé avec succès.']);
            } else {
                return response(['message' => 'Votre mot de passe actuel est incorrect.', ], 422);
            }
        } else {
            return response(['message' => 'L\'utilisateur n\'existe pas.'], 422);
        }
    }
     /**
     * Handle user logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Revoke the user's token
        $token = $request->user()->token();
        $token->revoke();

        return response(['message' => 'You have been successfully logged out!'], 200);
    }

    /**
     * Get the authenticated User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request)
{
    return response()->json($request->user());
}
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
        'type' => 'integer',
        'domaines' => 'required|array',
        'domaines.*.id' => 'required|exists:domaines_competences,id',
        'domaines.*.modules' => 'required|array',
        'domaines.*.modules.*.id' => 'required|exists:modules,id',
        'domaines.*.modules.*.niveau_competence_id' => 'required|exists:niveaux_competences,id',
    ]);

    if ($validator->fails()) {
        return response(['errors' => $validator->errors()->all()], 422);
    }

    $request['type'] = $request['type'] ? $request['type'] : 0;
    $request['password'] = Hash::make($request['password']);
    $request['remember_token'] = Str::random(10);
    $request['firstconnect'] = 'ON';
    $request['active'] = true;
    $request['recuperation'] = 60; // Initialiser recuperation à 60 jours
    $request['conge'] = 60; // Initialiser conge à 60 jours

    $user = User::create($request->toArray());
    $token = $user->createToken('Laravel Password Grant Client')->accessToken;

    // Enregistrer les domaines de compétence dans la table userCompetence
    foreach ($request->input('domaines') as $domaine) {
        foreach ($domaine['modules'] as $module) {
            UserCompetence::create([
                'user_id' => $user->id,
                'domaine_competence_id' => $domaine['id'],
                'module_id' => $module['id'],
                'niveau_competence_id' => $module['niveau_competence_id'],
            ]);
        }
    }

    $response = [
        'user' => $user,
        'token' => $token
    ];

    return response()->json($response, 201);
}





    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)], 200);
        } else {
            return response()->json(['error' => __($status)], 400);
        }
    }



    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Aucun utilisateur trouvé avec cette adresse e-mail.'], 400);
        }

        $token = Password::createToken($user);

        $user->notify(new ResetPasswordNotification($token));

        return response()->json(['message' => 'Un lien de réinitialisation a été envoyé à votre adresse e-mail.'], 200);
    }

    public function getLoginAudits()
    {
        $audits = LoginAudit::select('*')->orderBy('created_at', 'desc')->get();
        return response()->json($audits);
    }

}
