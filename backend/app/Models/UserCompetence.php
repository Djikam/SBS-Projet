<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCompetence extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'domaine_competence_id', 'module_id', 'niveau_competence_id'];
    protected $table = 'user_competence';
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function domaineCompetence()
    {
        return $this->belongsTo(DomaineCompetence::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function niveauCompetence()
    {
        return $this->belongsTo(NiveauCompetence::class);
    }
}
