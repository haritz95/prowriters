<?php

namespace Database\Seeders;

use App\Enums\ServiceType;
use App\Models\Business\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        $allowed_file_extensions = '.jpg,.png,.gif, .doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.pdf,.zip,.rar';
        $services                = [
            [
                'service_type_id'                   => ServiceType::CONTENT_WRITING,
                'name'                              => 'Content Writing',
                'assignment_label'                  => 'Type of content',
                'minimum_order_quantity'            => 275,
                'allowed_file_extensions'           => $allowed_file_extensions,
                'maximum_file_size'                 => 10,
                'minimum_number_of_files_to_upload' => 0,
                'maximum_number_of_files_to_upload' => 10,
                'image'                             => 'storage/system/services/writing.png',
                'description'                       => 'Crafting compelling narratives and engaging content, our service offers tailored solutions for blogs, websites, and social media. Our adept writers deliver captivating content that resonates with your audience, boosting your brand\'s visibility and driving engagement.',
                'commission'                        => 10,
                'commission_from_bid'               => 20,
                'unit_name'                         => 'words',
                'not_available_for_direct_order'    => NULL,
                'not_available_for_bidding'         => NULL,
            ],
            [
                'service_type_id'                   => ServiceType::ACADEMIC_WRITING,
                'name'                              => 'Academic Writing',
                'assignment_label'                  => 'Type of writing',
                'minimum_order_quantity'            => 275,
                'allowed_file_extensions'           => $allowed_file_extensions,
                'maximum_file_size'                 => 10,
                'minimum_number_of_files_to_upload' => 0,
                'maximum_number_of_files_to_upload' => 10,
                'image'                             => 'storage/system/services/academic.png',
                'description'                       => 'Our expert team ensures academic success by providing meticulously researched and well-structured papers, essays, and theses across diverse subjects. We uphold academic standards, offering original, insightful content that reflects thorough understanding and critical analysis.',
                'commission'                        => 10,
                'commission_from_bid'               => 20,
                'unit_name'                         => 'words',
                'not_available_for_direct_order'    => NULL,
                'not_available_for_bidding'         => NULL,

            ],
            [
                'service_type_id'                   => ServiceType::FIXED_PRICE,
                'name'                              => 'Resume Writing',
                'assignment_label'                  => 'Packages',
                'minimum_order_quantity'            => 1,
                'allowed_file_extensions'           => $allowed_file_extensions,
                'maximum_file_size'                 => 10,
                'minimum_number_of_files_to_upload' => 0,
                'maximum_number_of_files_to_upload' => 10,
                'image'                             => 'storage/system/services/resume.png',
                'description'                       => 'Maximize career opportunities with our professional resume writing service. We craft standout resumes, highlighting your skills and accomplishments to captivate employers and secure more interviews. Our tailored approach emphasizes your strengths and optimizes keyword relevance for applicant tracking systems (ATS).',
                'commission'                        => 10,
                'commission_from_bid'               => 20,
                'unit_name'                         => 'each',
                'not_available_for_direct_order'    => NULL,
                'not_available_for_bidding'         => true,
            ],
            [
                'service_type_id'                   => ServiceType::FIXED_PRICE,
                'name'                              => 'Video Editing',
                'assignment_label'                  => 'Video Length',
                'minimum_order_quantity'            => 1,
                'allowed_file_extensions'           => '.mp4, .mov, .avi, .wmv, .flv, .mkv',
                'maximum_file_size'                 => 10000,
                'minimum_number_of_files_to_upload' => 1,
                'maximum_number_of_files_to_upload' => 10,
                'image'                             => 'storage/system/services/video-editing.png',
                'description'                       => 'Discover our professional video editing service, designed to help you create stunning and engaging videos for your business or personal projects. Our experienced team of editors will work with you every step of the way to ensure your vision is brought to life.',
                'commission'                        => 10,
                'commission_from_bid'               => 20,
                'unit_name'                         => 'each',
                'not_available_for_direct_order'    => NULL,
                'not_available_for_bidding'         => NULL,
            ],
            [
                'service_type_id'                   => ServiceType::FIXED_PRICE,
                'name'                              => 'Graphics Designing',
                'assignment_label'                  => 'Type of Graphic Design',
                'minimum_order_quantity'            => 1,
                'allowed_file_extensions'           => '.jpg, .png, .gif, .bmp, .tiff, .svg, .eps, .pdf, .psd, .ai',
                'maximum_file_size'                 => 10000,
                'minimum_number_of_files_to_upload' => 1,
                'maximum_number_of_files_to_upload' => 10,
                'image'                             => 'storage/system/services/graphics.png',
                'description'                       => 'Whether you\'re looking for eye-catching logos, vibrant marketing materials, engaging social media posts, or anything in between, we\'ve got you covered. With a keen eye for detail and a commitment to excellence, we ensure that every design reflects your brand identity and resonates with your target audience',
                'commission'                        => 10,
                'commission_from_bid'               => 20,
                'unit_name'                         => 'each',
                'not_available_for_direct_order'    => NULL,
                'not_available_for_bidding'         => NULL,
            ],
        ];
        Service::unguard();
        foreach ($services as $service) {
            Service::create($service);
        }

    }
}
