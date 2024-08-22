<?php

namespace Database\Seeders\Website;

use App\Models\Website\WebsiteTestimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class WebsiteTestimonialSeeder extends Seeder
{
    private $path = 'storage/photos/1/testimonial/';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        

        WebsiteTestimonial::insert([
            [
                'locale'   => 'en',
                'avatar'   => $this->path . 'testimonial_1.jpg',
                'name'     => 'Elonda Stokes',
                'position' => 'Social Media Manager',
                'rating'   => 5,
                'comment'  => 'Highly recommend!! Helped me out in such a short period of time and it was really good standards ! Keep it up...',
            ],
            [
                'locale'   => 'en',
                'avatar'   => $this->path . 'testimonial_2.jpg',
                'name'     => 'Justin Staples',
                'position' => 'Content Marketer',
                'rating'   => 5,
                'comment'  => 'I have used their service quite a few times over the past few years and have been extremely happy with the services they have provided for my company. Their Content Shop really makes it easy for us to log in and order our content.',
            ],
            [
                'locale'   => 'en',
                'avatar'   => $this->path . 'testimonial_3.jpg',
                'name'     => 'Kyle Kramin',
                'position' => 'CEO, Dan Digital',
                'rating'   => 5,
                'comment'  => 'Their team are top notch. They have a deep understanding of our needs in terms of content and we are supremely confident that whatever content they produce for us, it will help us achieve our business goals. I can’t recommend them enough.',
            ],
            [
                'locale'   => 'en',
                'avatar'   => $this->path . 'testimonial_4.jpg',
                'name'     => 'Stacey Abrams',
                'position' => 'Owner, Intergo Ineractive',
                'rating'   => 5,
                'comment'  => 'Since my recent collaboration with Express Writers, I have felt extremely confident in the creativity & the quality of work produced by their team. I love how seamless their online operation is – from my initial request to the final deliverable.',
            ],
            [
                'locale'   => 'en',
                'avatar'   => $this->path . 'testimonial_5.jpg',
                'name'     => 'Stacey Abrams',
                'position' => 'Owner, Intergo Ineractive',
                'rating'   => 5,
                'comment'  => 'The nice balance of creativity with the technical writing has produced instant results for my business in search. The staff is super-friendly and responsive. Prowriters is highly recommended for any businesses looking to ‘level up’ their content marketing strategy.',
            ],
        ]);
    }
}
