<?php
// app/Models/Certification.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'issuer',
        'description',
        'issue_date',
        'expiry_date',
        'skills',
        'credential_url',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'skills' => 'array',
    ];

    public function isValid()
    {
        return !$this->expiry_date || $this->expiry_date->isFuture();
    }
}
