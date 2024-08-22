<?php

return [
    'created' => 'Successfully created',
    'updated' => 'Successfully updated',
    'deleted' => 'Successfully deleted',

    'credit_limit_note'            => 'Even if you have zero or negative balance in your wallet, you can still use it to place credit order. You are allowed to have negative balance up to :amount, which you can adjust by paying later',
    'credit_limit_crossed'         => 'Insufficient credit to place the order. Your credit limit is :amount',
    'edit_content_preview_message' => 'Paste your content here to show the work progress and receive feedback from the client',
    'author'                       => [
        'discussions'          => [
            'title'     => 'Discussions',
            'sub_title' => 'Discuss with client if you have any confusion',
        ],
        'internal_discussions' => [
            'title'     => 'Internal Discussions',
            'sub_title' => 'Discuss with admin if you have any confusion',
        ],
    ],
    'notifications'                => [
        'task_in_progress'                => 'The task :number is in progress',
        'new_order'                       => 'A new order has been placed. Invoice Number : :number',
        'new_bid_request'                 => 'A new bid request has been placed. Bid Request Number : :number',
        'self_assigned_task'              => 'author: has assigned him/her a new task',
        'new_comment_on_task'             => ':author has posted a comment on :task',
        'work_submitted_for_qa'           => 'Works for :number has been uploaded. Please check the work and provide your feedback',
        'task_approved_by_qa'             => 'Work for :number has been approved by the editor and sent to the customer for review',
        'task_rejected_by_qa'             => 'Work for :number has been rejected by the editor and sent back to the author',
        'submitted_for_customer_approval' => 'Files for your task :number is ready for download. Please download the files and submit your feedback',
        'revision_request'                => 'The customer has requested for a revision for task :number',
        'customer_accepted_work'          => 'The customer has accepted the delivered work for :number .The status of the task is now complete',

        'new_bill'                => 'You have a new payment request of total :total from :author',
        'payout_processed_line_1' => 'We sent you money!',
        'payout_processed_line_2' => 'We just processed your payout for :amount against your bill :bill_number',

        'customer_payment_approved' => 'Your payment of :amount via :payment_method, reference number :reference_number has been approved',

        'customer_payment_disapproved' => 'Your payment of :amount via :payment_method, reference number :reference_number has been disapproved. Should you have any query please do not hesitate to contact us',

        'payment_pending_for_approval' => 'A new payment of :amount is pending for your approval',

        'new_author_application' => 'A new author has submitted an application. The tracking number is :number',
        
        'new_bid_submitted_subject' => 'A new bid has been received',
        'new_bid_submitted_message' => 'You have received a new bid from an author',
        'bid_approved_subject' => 'Bid Approved',
        'bid_approved_message' => 'Your bid has been approved',
        'new_message' => 'You have a new message in your inbox. Please login to the application to see the message.',
    ],
    'order_summary'                => [
        'greeting'       => 'Dear :name',
        'message_line_1' => 'Thank you for choosing :company_name, Here\'s a summary of your order :',
        'footer_text'    => 'Thank you for your order',
    ],
    'activity_log'                 => [
        'author_started_working' => 'has started working on',
        'admin_assigned_task'    => 'has assigned task to :author',
    ],
    'email_subjects'               => [
        'new_task_comment'                => ':number - New comment',
        'submitted_for_qa'                => ':number - Is ready for QA',
        'qa_approved'                     => ':number - The editor has approved the work',
        'qa_rejected'                     => ':number - The editor has rejected the work',
        'submitted_for_customer_approval' => ':number - Is ready for download',
        'revision_request'                => ':number - Request for a revision',
        'customer_accepted_work'          => ':number - The customer has approved the work',
        'payout_processed'                => 'You have received your payment',
        'customer_payment_approved'       => 'Your payment has been approved',
        'customer_payment_disapproved'    => 'Your payment has been disapproved',

        'payment_pending_for_approval' => ':number - A new payment is pending for your approval',
        'new_message'                => 'You have a new message',
    ],
    'cookie'                       => [
        'message'        => 'This website uses cookies to ensure you get the best experience on our website',
        'ok_button_text' => 'Got it!',
    ],
    'ai_content'                   => [
        'placeholders' => [
            'landing_page_copy_website_features'        => 'Uses language AI and copywriting formulas to generate content. Supports 20+ content types and tones in 15+ languages. Simple and easy to use with minimal complexity. Fast, affordable, and works well on mobile devices',
            'about_product'                             => ':company_name is a writing tool powered by the best language AI technology. As a smart writer, it automatically generates content for many use cases like blogs, emails, ads, and more. It is fast & works well on mobile.',
            'about_you'                                 => 'Experienced content writer and digital marketing expert with a decade-plus experience of working in startups',
            'question_answer_topic_description'         => 'AI Writing Assistants are now being used to help write content. Some companies use them for a specific topic or niche, while digital agencies use them to generate all kinds of content for their clients',
            'reply_to_reviews_message'                  => 'I am absolutely in love with :company_name. All the other AI writing apps that I have tried felt cluttered & overwhelming. :company_name, on the other hand, offers a seamless interface and generates the best quality of content. :company_name is the best AI writer, hands down!',
            'seo_meta_description_page_meta_title'      => ':company_name - Best AI Writer, Content Generator & Writing Assistant',
            'seo_meta_title_keywords'                   => 'AI writing assistant, content generator',
            'song_idea'                                 => 'Soothing song for a couple in love',
            'story_idea'                                => 'The time when I couldn\'t distinguish AI from humans',
            'tagline_description'                       => 'An AI writing assistant that helps you automatically generate content for anything. :company_name can create original, engaging copies for you within seconds, at a fraction of the cost!',
            'review_title'                              => 'Best AI writer and copywriting assistant in the market',
            'video_channel_description_channel_purpose' => 'Learn all about AI writing tools on this channel. We cover the what, why, and how of these tools using live demos and case studies',
            'video_description_video_title'             => 'How to Use AI Writers to Create High-Quality Blogs in Minutes',
            'vide_idea_keywords'                        => 'AI writing assistants',
        ],
    ],
];
