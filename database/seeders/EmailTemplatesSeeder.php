<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('email_templates')->insert([
            [
                'role' => null,
                'title' => 'unpaid_bill_alert',
                'subject' => 'Reminder: Outstanding Payment for [monthYear]',
                'template' => '<table align="center" border="1" cellpadding="0" cellspacing="0" style="border-radius:10px;border:1px solid #000;width:610px!important"><tbody><tr><td style="border-top-left-radius:10px;border-top-right-radius:10px;height:60px!important;text-align:center"><strong><span style="font-size:26px">RENTCARE</span></strong></td></tr><tr><td style="text-align:justify"><div style="margin-left:15px;margin-right:15px;text-align:left"><p><strong><span style="color:#000">Dear [GuestName],</span></strong></p><p><span style="color:#000">I hope this message finds you well. This is a reminder for the monthly rent payment for<strong>[room_name]</strong>(<strong>[room_code]</strong>) for the month of<strong>[monthYear]</strong>, amounting to<strong>[totalAmount]</strong>rupees.</span></p><p><span style="color:#000">Thank you for your understanding and timely cooperation.</span></p><p><span style="color:#000"><strong>RentCare</strong></span></p></div></td></tr><tr><td style="background-color:#000;border-bottom-left-radius:10px;border-bottom-right-radius:10px;height:50px!important;text-align:center"><strong><span style="color:#fff">Thank you for using our services! 😍</span></strong></td></tr></tbody></table>',
                'tag_desc' => '[{"tag":"[monthYear]","desc":"Define month and year"}]',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role' => null,
                'title' => 'paid_bill_alert',
                'subject' => 'Receipt for Room:- [RoomNumber] Payment - Invoice [InvoiceNumber]',
                'template' => '<table align="center" border="1" cellpadding="0" cellspacing="0" style="border-radius:10px;border:1px solid #000;width:610px!important"><tbody><tr><td style="border-top-left-radius:10px;border-top-right-radius:10px;height:60px!important;text-align:center"><strong><span style="font-size:26px">RENTCARE</span></strong></td></tr><tr><td><div style="margin:15px"><p><strong>Dear [GuestName],</strong></p><p>Thank you for your payment for <strong>[RoomNumber]</strong>.</p></div></td></tr><tr><td style="background-color:#000;text-align:center"><strong><span style="color:#fff">Thank you for using our services! 😍</span></strong></td></tr></tbody></table>',
                'tag_desc' => '[{"tag":"[RoomNumber]","desc":"Define room number"}]',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role' => 'admin',
                'title' => 'two_factor_authentication_login',
                'subject' => 'Login :- Your Two-Factor Authentication Code',
                'template' => '<table align="center" border="1" cellpadding="0" cellspacing="0" style="border-radius:10px;border:1px solid #000;width:610px"><tbody><tr><td style="border-top-left-radius:10px;border-top-right-radius:10px;height:60px!important;text-align:center"><strong><span style="font-size:26px">AME Application</span></strong></td></tr><tr><td style="text-align:justify"><div style="margin-left:15px;margin-right:15px;text-align:left"><p style="text-align:justify"><span style="font-size:14px"><strong>Dear [ClientName],</strong></span></p><p style="text-align:justify"><span style="color:#000">Your two-factor authentication code is:</span></p><div style="background-color:#2980b9;color:#fff;font-size:18px;padding-left:20px!important;padding:5px">[TwoFactorCode]</div><p style="text-align:justify"><span style="color:#000">Please use this code to complete the login process. If you did not request this code, please ignore this message.</span></p><p style="text-align:justify">&nbsp;</p><p style="text-align:justify"><span style="color:#000">Best regards,</span></p><p style="text-align:justify"><span style="color:#000"><strong>AME Application</strong></span></p></div></td></tr><tr><td style="background-color:#000;border-bottom-left-radius:10px;border-bottom-right-radius:10px;height:50px!important;text-align:center"><span style="font-size:22px"><strong><span style="color:#fff">Thank you for using our services! 😍</span></strong></span></td></tr></tbody></table>',
                'tag_desc' => '[{"tag":"[ClientName]","desc":"Give client name"}]',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role' => 'admin',
                'title' => 'two_factor_authentication_signup',
                'subject' => 'Registration :- Your Two-Factor Authentication Code',
                'template' => '<table align="center" border="1" cellpadding="0" cellspacing="0" style="border-radius:10px;border:1px solid #000;width:610px"><tbody><tr><td style="border-top-left-radius:10px;border-top-right-radius:10px;height:60px!important;text-align:center"><strong><span style="font-size:26px">AME Application</span></strong></td></tr><tr><td style="text-align:justify"><div style="margin-left:15px;margin-right:15px;text-align:left"><p style="text-align:justify"><span style="color:#000"><span style="font-size:14px"><strong>Dear [ClientName],</strong></span></span></p><p style="text-align:justify"><span style="color:#000">Thank you for registering with us. Please use the following OTP to verify your email address:</span></p><div style="background-color:#2980b9;color:#fff;font-size:18px;padding-left:20px!important;padding:5px">[TwoFactorCode]</div><p style="text-align:justify"><span style="color:#000">If you did not register on our website, you can safely ignore this email</span></p><p style="text-align:justify">&nbsp;</p><p style="text-align:justify"><span style="color:#000">Best regards,</span></p><p style="text-align:justify"><span style="color:#000"><strong>AME Application</strong></span></p></div></td></tr><tr><td style="background-color:#000;border-bottom-left-radius:10px;border-bottom-right-radius:10px;height:50px!important;text-align:center"><span style="font-size:22px"><strong><span style="color:#fff">Thank you for using our services! 😍</span></strong></span></td></tr></tbody></table>',
                'tag_desc' => '[{"tag":"[ClientName]","desc":"Give client name"}]',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role' => 'admin',
                'title' => 'reset_password_otp',
                'subject' => 'Password Reset OTP',
                'template' => '<table align="center" border="1" cellpadding="0" cellspacing="0" style="border-radius:10px;border:1px solid #000;width:610px"><tbody><tr><td style="border-top-left-radius:10px;border-top-right-radius:10px;height:60px!important;text-align:center"><strong><span style="font-size:26px">AME Application</span></strong></td></tr><tr><td style="text-align:justify"><div style="margin-left:15px;margin-right:15px;text-align:left"><p style="text-align:justify"><span style="color:#000"><span style="font-size:14px"><strong>Dear [ClientName],</strong></span></span></p><p style="text-align:justify"><span style="color:#000"><span style="background-color:transparent">Your password reset OTP is:</span></span></p><div style="background-color:#2980b9;color:#fff;font-size:18px;padding-left:20px!important;padding:5px">[OTP]</div><p style="text-align:justify"><span style="color:#000">Please use this OTP to complete the password reset process. If you did not request a password reset, please ignore this message.</span></p><p style="text-align:justify">&nbsp;</p><p style="text-align:justify"><span style="color:#000">Best regards,</span></p><p style="text-align:justify"><span style="color:#000"><strong>AME Application</strong></span></p></div></td></tr><tr><td style="background-color:#000;border-bottom-left-radius:10px;border-bottom-right-radius:10px;height:50px!important;text-align:center"><span style="font-size:22px"><strong><span style="color:#fff">Thank you for using our services! 😍</span></strong></span></td></tr></tbody></table>',
                'tag_desc' => '[{"tag":"[ClientName]","desc":"Give client name"}]',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
