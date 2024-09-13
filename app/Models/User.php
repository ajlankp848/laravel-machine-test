<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'contact_number',
        'profile_photo',
        'hobbies_id',
        'category_id',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hobby()
    {
        return $this->belongsTo(Hobby::class, 'hobbies_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public static function fetchAllUsers()
    {
        return self::with('category')
            ->select('id', 'name', 'contact_number', 'profile_photo', 'hobbies_id', 'category_id', 'created_at')
            ->orderBy('created_at', 'desc')->get();
    }

    public static function validateAttributes(array $data)
    {
        return Validator::make($data, [
            'name'              => 'required|string|max:255|unique:users,name',
            'contact_number'    => 'required|digits_between:10,15|unique:users,contact_number',
            'hobbies'           => 'required|array',
            'hobbies.*'         => 'integer|exists:hobbies,id',
            'category_id'       => 'required|integer|exists:categories,id',
            'profile_photo'     => 'required|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.required'                 => 'The name field is required.',
            'name.string'                   => 'The name must be a string.',
            'name.unique'                   => 'The name has already been taken.',
            'contact_number.required'       => 'The contact number field is required.',
            'contact_number.unique'         => 'The contact number has already been taken.',
            'name.max'                      => 'The name may not be greater than 255 characters.',
            'contact_number.digits_between' => 'The contact number must be between 10 and 15 digits.',
            'hobbies.required'              => 'At least one hobby must be selected.',
            'hobbies.array'                 => 'Hobbies must be an array.',
            'hobbies.*.integer'             => 'Each hobby must be a valid integer.',
            'hobbies.*.exists'              => 'Selected hobby is invalid.',
            'category_id.required'          => 'The category field is required.',
            'category_id.integer'           => 'The category must be an integer.',
            'category_id.exists'            => 'Selected category is invalid.',
            'profile_photo.required'        => 'The profile photo field is required.',
            'profile_photo.mimes'           => 'The profile photo must be a file of type: jpg, jpeg, png.',
            'profile_photo.max'             => 'The profile photo may not be greater than 2MB.',
        ]);
    }

    public static function createUser(array $data)
    {
        $filePath = null;
        if (isset($data['profile_photo']) && $data['profile_photo']) {
            $file = $data['profile_photo'];
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profile_photos', $filename);
            $filePath = 'storage/profile_photos/' . $filename;
        }
        $dataToStore = [
            'name'             => $data['name'],
            'email'            => $data['email'] ?? null,
            'password'         => $data['password'] ?? null,
            'contact_number'   => $data['contact_number'],
            'profile_photo'    => $filePath,
            'category_id'      => $data['category_id'],
            'is_active'        => 1,
            'hobbies_id'       => isset($data['hobbies']) ? implode(',', $data['hobbies']) : null,
        ];
        $user = User::create($dataToStore);
        return $user;
    }

    public static function validateAttributesEdit(array $data)
    {
        $userId = $data['user_id'] ?? null;
        return Validator::make($data, [
            'user_id'               => 'required|exists:users,id',
            'edit_name'             => "required|string|max:255|unique:users,name,{$userId}",
            'edit_contact_number'   => "required|digits_between:10,15|unique:users,contact_number,{$userId}",
            'edit_hobbies'          => 'required|array',
            'edit_hobbies.*'        => 'integer|exists:hobbies,id',
            'edit_category_id'      => 'required|integer|exists:categories,id',
            'edit_profile_photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'user_id.required'                   => 'The user ID field is required.',
            'user_id.exists'                     => 'The selected user ID is invalid.',
            'edit_name.required'                 => 'The name field is required.',
            'edit_name.string'                   => 'The name must be a string.',
            'edit_name.max'                      => 'The name may not be greater than 255 characters.',
            'edit_name.unique'                   => 'The name has already been taken.',
            'edit_contact_number.required'       => 'The contact number field is required.',
            'edit_contact_number.digits_between' => 'The contact number must be between 10 and 15 digits.',
            'edit_contact_number.unique'         => 'The contact number has already been taken.',
            'edit_hobbies.required'              => 'At least one hobby must be selected.',
            'edit_hobbies.array'                 => 'Hobbies must be an array.',
            'edit_hobbies.*.integer'             => 'Each hobby must be a valid integer.',
            'edit_hobbies.*.exists'              => 'Selected hobby is invalid.',
            'edit_category_id.required'          => 'The category field is required.',
            'edit_category_id.integer'           => 'The category must be an integer.',
            'edit_category_id.exists'            => 'Selected category is invalid.',
            'edit_profile_photo.image'           => 'The profile photo must be an image.',
            'edit_profile_photo.mimes'           => 'The profile photo must be a file of type: jpg, jpeg, png.',
            'edit_profile_photo.max'             => 'The profile photo may not be greater than 2MB.',
        ]);
    }

    public static function updateUser(array $data)
    {
        $user = self::find($data['user_id']);
        if (!$user) {
            return response()->json(['error' => 'User Not Found'], 404);
        }
        $filePath = $user->profile_photo;
        if (isset($data['edit_profile_photo']) && $data['edit_profile_photo']) {
            $file = $data['edit_profile_photo'];
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profile_photos', $filename);
            $filePath = 'storage/profile_photos/' . $filename;
        }
        $updateData = [
            'name'             => $data['edit_name'],
            'contact_number'   => $data['edit_contact_number'],
            'profile_photo'    => $filePath,
            'category_id'      => $data['edit_category_id'],
            'hobbies_id'       => isset($data['edit_hobbies']) ? implode(',', $data['edit_hobbies']) : null,
        ];
        $user->update($updateData);
        return $user;
    }
}
