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
use Tymon\JWTAuth\Contracts\JWTSubject;

// use Illuminate\Foundation\Auth\User as Authenticatables;
class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    JWTSubject
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
    protected $api_token;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
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

    public function getJWTIdentifier()
    {
        $id = $this->getAuthIdentifier();
        out("getJWTIdentifier>> ".$id." this->id: ".$this->id);
        return $id??$this->id;
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        out("getJWTCustomClaims");
        $milis = round(microtime(true) * 1000);
        return [
            // 'iatssss' => $milis
        ];
    }

    public static function forResponse(User $u) : User
    {
        unset($u->password);

        $attributes = $u->getAttributes();
        
        unset(
            $attributes['api_token'],
            $attributes['password'],
            $attributes['created_at'],
            $attributes['updated_at'],
            $attributes['deleted_at']
        );
        $u->setRawAttributes($attributes);
        // $u->setAttribute('api_token', null);
        // $u->setAttribute('password', null);
        return $u;
    }
}
