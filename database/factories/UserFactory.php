<?php

namespace Database\Factories;

use App\Enums\PermissionType;
use App\Enums\ServiceType;
use App\Enums\UserType;
use App\Models\Business\AuthorLevel;
use App\Models\Business\Service;
use App\Models\CustomerProfile;
use App\Models\Author\EducationLevel;
use App\Models\Author\AuthorProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{

    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid'              => Str::orderedUuid(),
            'email'             => $this->faker->unique()->safeEmail(),
            'first_name'        => $this->faker->firstName,
            'last_name'         => $this->faker->lastName,
            'language'          => 'en',
            'timezone'          => 'America/New_York',
            'country_code'      => 'US',
            'phone'             => $this->faker->phoneNumber,
            'email_verified_at' => now(),
            'password'          => bcrypt('123456'),
            'remember_token'    => Str::random(10),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (User $user) {
        })->afterCreating(function (User $user) {
            if ($user->type == UserType::AUTHOR) {
                $this->createAuthorProfile($user);
            }

            if ($user->type == UserType::CUSTOMER) {
                $this->createCustomerProfile($user);
            }

            if ($user->type == UserType::ADMIN && $user->id == 1) {
                $user->assignRole(PermissionType::ROLE_SUPER_ADMIN);
            }

            if ($user->type == UserType::ADMIN && $user->email == 'editor@demo.com') {
                $user->assignRole(PermissionType::ROLE_EDITOR);
            }
        });
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => UserType::ADMIN,
                'code' => $this->generateUserCode(UserType::ADMIN),
            ];
        });
    }

    public function customer()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => UserType::CUSTOMER,
                'code' => $this->generateUserCode(UserType::CUSTOMER),
            ];
        });
    }

    public function author()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => UserType::AUTHOR,
                'code' => $this->generateUserCode(UserType::AUTHOR),
            ];
        });
    }

    private function createCustomerProfile($user)
    {
        if ($user->email == 'customer@demo.com') {
            CustomerProfile::create([
                'user_id'            => $user->id,
                'allow_paying_later' => true,
                'credit_limit'       => 1000,
                'internal_note'      => 'This is a note for internal staffs only. You can modify this message and enter yours from edit profile section',
            ]);
        } else {
            CustomerProfile::create([
                'user_id'            => $user->id,
                'allow_paying_later' => null,
                'credit_limit'       => 0,
                'internal_note'      => 'This is a note for internal staffs only. You can modify this message and enter yours from edit profile section',
            ]);
        }

    }
    private function createAuthorProfile($user)
    {
        $author_level_id = AuthorLevel::select('id')->orderBy('numeric_value', 'DESC')->get()->first()->id;
        $education_level_id  = EducationLevel::select('id')->orderBy('id', 'DESC')->skip(1)->take(1)->first()->id;

        list($subject_1, $subject_2, $subject_3) = Service::where('id', ServiceType::CONTENT_WRITING)->with(['subjects' => function ($q) {
            $q->select('subjects.id')->inRandomOrder()->limit(3);
        }])->get()->first()->subjects->pluck('id');

        list($subject_4, $subject_5) = Service::where('id', ServiceType::ACADEMIC_WRITING)->with(['subjects' => function ($q) {
            $q->select('subjects.id')->inRandomOrder()->limit(2);
        }])->get()->first()->subjects->pluck('id');

        AuthorProfile::create([
            'user_id'                => $user->id,
            'author_level_id'    => $author_level_id,
            'education_level_id'     => $education_level_id,
            'bio'                    => $this->faker->paragraph(3),
            'address'                => $this->faker->address(),
            'city'                   => $this->faker->city(),
            'state'                  => $this->faker->state(),
            'payment_method'         => 'Paypal',
            'payment_method_details' => $this->faker->email(),
            'blog_url'               => $this->faker->url(),
            'online_portfolio_url'   => $this->faker->url(),
            'linked_in_url'          => 'www.linkedin.com/in/' . $this->faker->userName(),
            'years_of_experience'    => 10,
            'resume'                 => NULL,
            'language_id_1'          => 1,
            'language_id_2'          => 2,
            'service_id_1'           => 1,
            'service_id_2'           => 2,
            'service_id_3'           => 3,
            'subject_id_1'           => $subject_1,
            'subject_id_2'           => $subject_2,
            'subject_id_3'           => $subject_3,
            'subject_id_4'           => $subject_4,
            'subject_id_5'           => $subject_5,
        ]);
    }

    private function generateUserCode($user_type)
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
