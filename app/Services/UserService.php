<?php

namespace App\Services;

use App\Enums\UserType;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Author\AuthorProfile;
use App\Models\CustomerProfile;
use App\Models\User;
use App\Notifications\AccountCreated;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    const DEFAULT_TIME_ZONE = 'America/New_York';

    public function createCustomer($data)
    {
        $user                    = new User();
        $user->type              = UserType::CUSTOMER;
        $user->uuid              = Str::orderedUuid();
        $user->code              = $this->generateUserCode(UserType::CUSTOMER);
        $user->first_name        = $data['first_name'];
        $user->last_name         = $data['last_name'];
        $user->email             = $data['email'];
        $user->password          = bcrypt($data['password']);
        $user->language          = isset($data['language']) ? $data['language'] : 'en';
        $user->photo             = isset($data['photo']) ? $data['photo'] : null;
        $user->phone             = isset($data['phone']) ? $data['phone'] : null;
        $user->country_code      = isset($data['country_code']) ? strtoupper($data['country_code']) : 'US';
        $user->google_id         = isset($data['google_id']) ? $data['google_id'] : null;
        $user->timezone          = isset($data['timezone']) ? $data['timezone'] : self::DEFAULT_TIME_ZONE;
        $user->email_verified_at = now();
        $user->save();

        // Store Customer Profile
        $user->customerProfile()->save((new CustomerProfile([
            'internal_note'      => isset($data['internal_note']) ? $data['internal_note'] : null,
            'allow_paying_later' => isset($data['allow_paying_later']) ? $data['allow_paying_later'] : null,
            // 'credit_limit'  => isset($data['credit_limit']) ? $data['credit_limit'] : 0,
        ])));

        if (isset($data['send_notification_email']) && $data['send_notification_email']) {
            // Send notification email to the user with password
            $user->notify(new AccountCreated($user, $data['password']));
        }

        return $user;
    }

    public function updateCustomer(User $user, StoreCustomerRequest $request)
    {
        $user->fill($request->validated())->update();
        $user->customerProfile()->update([
            'internal_note'      => $request->internal_note,
            'allow_paying_later' => $request->allow_paying_later,
            // 'credit_limit'  => $request->credit_limit,
        ]);
    }

    public function createAdmin($data)
    {
        $user                    = new User();
        $user->type              = UserType::ADMIN;
        $user->uuid              = Str::orderedUuid();
        $user->code              = $this->generateUserCode(UserType::ADMIN);
        $user->first_name        = $data['first_name'];
        $user->last_name         = $data['last_name'];
        $user->email             = $data['email'];
        $user->password          = bcrypt($data['password']);
        $user->language          = 'en';
        $user->phone             = $data['phone'];
        $user->country_code      = strtoupper($data['country_code']);
        $user->timezone          = isset($data['timezone']) ? $data['timezone'] : 'Europe/Paris';
        $user->email_verified_at = now();
        $user->save();

        if (isset($data['send_notification_email']) && $data['send_notification_email']) {
            // Send notification email to the user with password
            $user->notify(new AccountCreated($user, $data['password']));
        }

        return $user;
    }

    public function createAuthor($data)
    {
        $user                    = new User();
        $user->type              = UserType::AUTHOR;
        $user->uuid              = Str::orderedUuid();
        $user->code              = $this->generateUserCode(UserType::AUTHOR);
        $user->first_name        = $data['first_name'];
        $user->last_name         = $data['last_name'];
        $user->email             = $data['email'];
        $user->password          = bcrypt($data['password']);
        $user->language          = 'en';
        $user->phone             = $data['phone'];
        $user->country_code      = strtoupper($data['country_code']);
        $user->timezone          = isset($data['timezone']) ? $data['timezone'] : 'Europe/Paris';
        $user->email_verified_at = now();
        $user->save();

        $data['user_id'] = $user->id;

        AuthorProfile::create($data);

        if (isset($data['send_notification_email']) && $data['send_notification_email']) {
            // Send notification email to the user with password
            $user->notify(new AccountCreated($user, $data['password']));
        }
        return $user;
    }

    public function changePassword(User $user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();
    }

    /**
     * Customer Registration
     * @param mixed $data
     *
     * @return \App\Models\User
     */
    public function customerRegistration(array $data)
    {
        $user = User::create([
            'type'         => UserType::CUSTOMER,
            'uuid'         => Str::orderedUuid(),
            'code'         => $this->generateUserCode(UserType::CUSTOMER),
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'language'     => app()->getLocale(),
            'country_code' => isset($data['country_code']) ? strtoupper($data['country_code']) : null,
            'timezone'     => isset($data['timezone']) ? $data['timezone'] : null,
            'phone'        => isset($data['phone']) ? $data['phone'] : null,
        ]);

        // Store Customer Profile
        $user->customerProfile()->save((new CustomerProfile([
            'internal_note'      => null,
            'allow_paying_later' => null,
        ])));

        return $user;

    }

    public function generateUserCode($user_type)
    {
        switch ($user_type) {
            case UserType::ADMIN:
                $prefix = 2;
                break;
            case UserType::AUTHOR:
                $prefix = 7;
                break;
            default:
                $prefix = 3;
                break;
        }
        $code = $prefix . mt_rand(1, 9) . substr(time(), -5);

        $users = User::select('id')->where('code', $code)->get();
        if ($users->count() > 0) {
            return $this->generateUserCode($user_type);
        }
        return $code;

    }

}
