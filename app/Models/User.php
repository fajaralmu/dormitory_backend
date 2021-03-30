<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;


use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nickname',
        'nip',
        'nis',
        'nisn',
        'ip',
        'session_id',
        'roles',
        'active',
        'yayasan_id',
    ];

    protected $name;
    protected $email;
    protected $password;
    protected $nickname;
    protected $nip;
    protected $nis;
    protected $nisn;
    protected $ip;
    protected $session_id;
    protected $roles;
    protected $active;
    protected $yayasan_id;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles' => 'array',
    ];

    public function siswa()
    {
        
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nip');
    }

    public function getAuthIdentifierName()
    {
        return 'email';
    }

    public function getAuthIdentifier()
    {
        
        return $this->attributes["email"];
    }

    public function getAuthPassword()
    {
        return ($this->attributes["password"]);
    }
 
    public function isAdmin() :bool
    {
        return $this->hasRole('admin');
    }

    public function hasRole($roles)
    {
        $user_roles = json_decode($this->attributes['roles']);
        // dd($roles, $user_roles);
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        foreach ($roles as $role) {
            foreach ($user_roles as $user_role) {
                if ($user_role == $role) {
                    return true;
                }
            }
        }
        out("NO ROLE");
        return false;
        // return in_array($role, [$this->attributes['role']]);
        // return in_array($role, $this->roles);
    }

    public function addRole(string $role)
    {
        if ($this->hasRole($role)) {
            return;
        }
        $user_roles = json_decode($this->roles);
       
        array_push($user_roles, $role);
        $this->roles = json_encode($user_roles);
        $this->setAttribute('roles', $user_roles);
        $this->save();
    }

    public function removeRole(string $role)
    {
        if (false == $this->hasRole($role)) {
            return;
        }
        $user_roles = json_decode($this->roles);
        for ($i=0; $i < sizeof($user_roles); $i++) {
            if ($role == $user_roles[$i]) {
                array_splice($user_roles, $i, 1);
                break;
            }
        }
        $this->roles  = json_encode($user_roles);
        $this->setAttribute('roles', $user_roles);
        $this->save();
    }
}
