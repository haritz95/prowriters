<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Services\AiContentGenerateUseCases\BlogIdea;
use App\Services\AiContentGenerateUseCases\BlogSection;
use App\Services\AiContentGenerateUseCases\BrandName;
use App\Services\AiContentGenerateUseCases\BusinessIdeaPitch;
use App\Services\AiContentGenerateUseCases\BusinessIdeas;
use App\Services\AiContentGenerateUseCases\CallToAction;
use App\Services\AiContentGenerateUseCases\CopywritingFrameworkAida;
use App\Services\AiContentGenerateUseCases\CopywritingFrameworkPas;
use App\Services\AiContentGenerateUseCases\CoverLetter;
use App\Services\AiContentGenerateUseCases\Email;
use App\Services\AiContentGenerateUseCases\GoogleSearchAds;
use App\Services\AiContentGenerateUseCases\InterviewQuestions;
use App\Services\AiContentGenerateUseCases\JobDescription;
use App\Services\AiContentGenerateUseCases\KeywordExtractor;
use App\Services\AiContentGenerateUseCases\KeywordsGenerator;
use App\Services\AiContentGenerateUseCases\LandingPageCopy;
use App\Services\AiContentGenerateUseCases\PostAndCaptionIdeas;
use App\Services\AiContentGenerateUseCases\ProductDescription;
use App\Services\AiContentGenerateUseCases\ProfileBio;
use App\Services\AiContentGenerateUseCases\QuestionAnswer;
use App\Services\AiContentGenerateUseCases\ReplyToReviewsAndMessages;
use App\Services\AiContentGenerateUseCases\SEOMetaDescription;
use App\Services\AiContentGenerateUseCases\SEOMetaTitle;
use App\Services\AiContentGenerateUseCases\SocialMediaPost;
use App\Services\AiContentGenerateUseCases\SongLyrics;
use App\Services\AiContentGenerateUseCases\StoryPlot;
use App\Services\AiContentGenerateUseCases\Tagline;
use App\Services\AiContentGenerateUseCases\TestimonialAndReview;
use App\Services\AiContentGenerateUseCases\VideoChannelDescription;
use App\Services\AiContentGenerateUseCases\VideoDescription;
use App\Services\AiContentGenerateUseCases\VideoIdea;

class UseCaseConfiguration
{
    private function register()
    {
        return [
            BlogIdea::class,
            BlogSection::class,
            BrandName::class,
            BusinessIdeaPitch::class,
            BusinessIdeas::class,
            CallToAction::class,
            CopywritingFrameworkAida::class,
            CopywritingFrameworkPas::class,
            CoverLetter::class,
            Email::class,
            SocialMediaPost::class,
            GoogleSearchAds::class,
            InterviewQuestions::class,
            JobDescription::class,
            KeywordExtractor::class,
            KeywordsGenerator::class,
            LandingPageCopy::class,
            PostAndCaptionIdeas::class,
            ProductDescription::class,
            ProfileBio::class,
            QuestionAnswer::class,
            ReplyToReviewsAndMessages::class,
            SEOMetaDescription::class,
            SEOMetaTitle::class,
            SongLyrics::class,
            StoryPlot::class,
            Tagline::class,
            TestimonialAndReview::class,
            VideoChannelDescription::class,
            VideoDescription::class,
            VideoIdea::class,

        ];
    }

    public function getRegisteredClasses()
    {
        return $this->register();
    }

    public function getUseCasesDropdown()
    {
        $dropdown = [];
        foreach ($this->getRegisteredClasses() as $useCase) {
            $dropdown[] = $useCase::dropdown();
        }
        return $dropdown;
    }

    public function getUseCasesIdentifiers()
    {
        $dropdown = [];
        foreach ($this->getRegisteredClasses() as $useCase) {
            $dropdown[$useCase::getUniqueIdentifier()] = $useCase;
        }
        return $dropdown;
    }

    public function getUseCaseByIdentifier($identifier)
    {
        $identifiers = $this->getUseCasesIdentifiers();

        return isset($identifiers[$identifier]) ? $identifiers[$identifier] : null;
    }

    public function getValidationRulesByIdentifier($identifier)
    {
        $rules = [
            'language_id' => 'required',
            'tone_id'     => 'required',
            'use_case_id' => 'required',
        ];

        $field_names = [
            'language_id' => __('Language'),
            'tone_id'     => __('Tone'),
            'use_case_id' => __('Use Case'),
        ];
        $useCase = $this->getUseCaseByIdentifier($identifier);

        if ($useCase) {
            $useCaseValidationRules = $useCase::validationRules();

            foreach ($useCase::formFields() as $field) {
                $field_names[$field['name']] = $field['label'];
            }

            return [
                'rules'       => array_merge($rules, $useCaseValidationRules),
                'field_names' => $field_names,
            ];
        }
        return [
            'rules'       => $rules,
            'field_names' => $field_names,
        ];
    }

    public function getPromptByIdentifier($identifier)
    {
        $useCase = $this->getUseCaseByIdentifier($identifier);
        return ($useCase) ? $useCase::prompt() : [];
    }

    public function getAllFormFields()
    {
        $data = [];
        foreach ($this->getRegisteredClasses() as $useCase) {
            $data[$useCase::getUniqueIdentifier()] = $useCase::formFields();
        }
        return $data;
    }

    // 'use_cases' => [
    //     ['id' => 'Blog Idea & Outline', 'name' => __('Blog Idea & Outline')],
    //     ['id' => 'Blog Section Writing', 'name' => __('Blog Section Writing')],
    //     ['id' => 'Brand Name', 'name' => __('Brand Name')],
    //     ['id' => 'Business Idea Pitch', 'name' => __('Business Idea Pitch')],

    //     ['id' => 'Business Ideas', 'name' => __('Business Ideas')],
    //     ['id' => 'Call To Action', 'name' => __('Call To Action')],
    //     ['id' => 'Copywriting Framework: AIDA', 'name' => __('Copywriting Framework: AIDA')],
    //     ['id' => 'Copywriting Framework: PAS', 'name' => __('Copywriting Framework: PAS')],

    //     ['id' => 'Cover Letter', 'name' => __('Cover Letter')],
    //     ['id' => 'Email', 'name' => __('Email')],
    //     ['id' => 'Facebook, Twitter, LinkedIn Ads', 'name' => __('Facebook, Twitter, LinkedIn Ads')],
    //     ['id' => 'Google Search Ads', 'name' => __('Google Search Ads')],

    //     ['id' => 'Interview Questions', 'name' => __('Interview Questions')],
    //     ['id' => 'Job Description', 'name' => __('Job Description')],
    //     ['id' => 'Keywords Extractor', 'name' => __('Keywords Extractor')],

    //     ['id' => 'Keywords Generator', 'name' => __('Keywords Generator')],
    //     ['id' => 'Landing Page & Website Copies', 'name' => __('Landing Page & Website Copies')],
    //     ['id' => 'Post & Caption Ideas', 'name' => __('Post & Caption Ideas')],

    //     ['id' => 'Product Description', 'name' => __('Product Description')],
    //     ['id' => 'Product Description (bullet points)', 'name' => __('Product Description (bullet points)')],
    //     ['id' => 'Profile Bio', 'name' => __('Profile Bio')],

    //     ['id' => 'Question & Answer', 'name' => __('Question & Answer')],
    //     ['id' => 'Reply to Reviews & Messages', 'name' => __('Reply to Reviews & Messages')],
    //     ['id' => 'SEO Meta Description', 'name' => __('SEO Meta Description')],

    //     ['id' => 'SEO Meta Title', 'name' => __('SEO Meta Title')],

    //     ['id' => 'Song Lyrics', 'name' => __('Song Lyrics')],
    //     ['id' => 'Story Plot', 'name' => __('Story Plot')],

    //     ['id' => 'Tagline & Headline', 'name' => __('Tagline & Headline')],
    //     ['id' => 'Testimonial & Review', 'name' => __('Testimonial & Review')],
    //     ['id' => 'Video Channel Description', 'name' => __('Video Channel Description')],

    //     ['id' => 'Video Description', 'name' => __('Video Description')],
    //     ['id' => 'Video Idea', 'name' => __('Video Idea')],

    // ],
}
