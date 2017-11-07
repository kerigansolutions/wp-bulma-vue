<?php
namespace Includes\Modules\Comments;

use Includes\Modules\CPT\CustomPostType;

class CommentBox
{
    public function __construct()
    {
    }

    public function display()
    {
        return file_get_contents(wp_normalize_path(get_template_directory() .'/inc/modules/Comments/display.php'));
    }

    public function createPostType()
    {
        //CREATE LEAD MGMT SYS
        $feedback = new CustomPostType(
            'Feedback',
            [
                'supports'           => [ 'title' ],
                'menu_icon'          => 'dashicons-star-empty',
                'has_archive'        => false,
                'menu_position'      => null,
                'public'             => false,
                'publicly_queryable' => false,
            ]
        );

        $feedback->addMetaBox(
            'Feedback',
            [
                'Email Address' => 'locked',
                'Feedback'      => 'locked'
            ]
        );
    }

    public function createAdminColumns()
    {
        add_filter('manage_lead_posts_columns', function () {
            $defaults = [
                'email_address' => 'Email',
            ];
            return $defaults;
        }, 0);
        add_action('manage_lead_posts_custom_column', function ($column_name, $post_ID) {
            switch ($column_name) {
                case 'email_address':
                    $email_address = get_post_meta($post_ID, 'lead_info_email_address', true);
                    echo(isset($email_address) ? '<a href="mailto:'.$email_address.'" >'.$email_address.'</a>' : null);
                    break;
            }
        }, 0, 2);
    }

    public function sendEmail(
        $sendadmin = [
            'to'      => 'daron@kerigan.com',
            'from'    => 'Website <noreply@kerigan.com>',
            'subject' => 'Email from website'
        ],
        $emaildata = [
            'headline'  => 'This is an email from the website!',
            'introcopy' => 'If we weren\'t testing, there would be stuff here.',
            'filedata'  => '',
            'fileinfo'  => ''
        ],
        $emailTemplate = ''
    ) {
        $eol = "\r\n";

        //search for directory in active WP template
        if (file_exists(wp_normalize_path(get_template_directory().'/inc/modules/leads/emailtemplate.php'))) {
            $emailTemplate = file_get_contents(wp_normalize_path(get_template_directory().'/inc/modules/leads/emailtemplate.php'));
        } else {
            $emailTemplate = '<!doctype html>
                <html>
                    <head>
                        <meta charset="utf-8">
                    </head>
                    <body bgcolor="#EAEAEA" style="background-color:#EAEAEA;">
                        <table cellpadding="0" cellspacing="0" border="0" align="center" style="width:650px; background-color:#FFFFFF; margin:30px auto;" bgcolor="#FFFFFF" >
                            <tbody>
                                <tr>
                                    <td style="padding:20px; border-top:10px solid #333333; border-bottom: #333333 solid 2px;" >
                                    <!--[content]-->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </body>
                </html>';
        }

        $split       = strrpos($emailTemplate, '<!--[content]-->');
        $templatebot = substr($emailTemplate, $split);
        $templatetop = substr($emailTemplate, 0, $split);

        $bottomsplit = strrpos($templatebot, '<!--[date]-->');
        $bottombot   = substr($templatebot, $bottomsplit);
        $bottomtop   = substr($templatebot, 0, $bottomsplit);
        $senddate    = date('M j, Y').' @ '.date('g:i a');

        //build headers
        $headers  = 'From: ' . $sendadmin['from'] . $eol;
        $headers .= (isset($sendadmin['cc']) ? 'Cc: ' . $sendadmin['cc'] . $eol : '');
        $headers .= (isset($sendadmin['bcc']) ? 'Bcc: ' . $sendadmin['bcc'] . $eol : '');
        $headers .= 'MIME-Version: 1.0' . $eol;

        //noreply pass: raw9z.kvc@b*
        $headers       .= 'Content-type: text/html; charset=utf-8' . $eol;
        $emailcontent   = $templatetop . $eol . $eol;
        $emailcontent  .= '<h2>'.$emaildata['headline'].'</h2>';
        $emailcontent  .= '<p>'.$emaildata['introcopy'].'</p>';
        $emailcontent  .= $templatebot . $eol . $eol;

        mail($sendadmin['to'], $sendadmin['subject'], $emailcontent, $headers);
    }

    public function addToDashboard($contactInfo)
    {
        wp_insert_post(
            [ //POST INFO
                'post_content'   => '',
                'post_status'    => 'publish',
                'post_type'      => 'response',
                'post_title'     => $contactInfo['email_address'],
                'comment_status' => 'closed',
                'ping_status'    => 'closed',
                'meta_input'     => [ //POST META
                    'feedback_email_address' => $contactInfo['email_address'],
                    'feedback_feedback'      => $contactInfo['commentBox'],
                ]
            ],
            true
        );
    }
}
