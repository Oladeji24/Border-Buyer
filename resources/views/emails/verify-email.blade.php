<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - Border Buyers</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        <!-- Header -->
        <div style="background-color: #2563eb; padding: 30px 20px; text-align: center;">
            <div style="font-size: 32px; color: white; margin-bottom: 10px;">
                <i class="fas fa-shield-alt"></i> Border Buyers
            </div>
            <h1 style="color: white; margin: 0; font-size: 24px; font-weight: 600;">
                Verify Your Email Address
            </h1>
        </div>

        <!-- Content -->
        <div style="padding: 40px 30px;">
            <p style="font-size: 16px; margin-bottom: 20px;">
                Hello {{ $user->name }},
            </p>
            
            <p style="font-size: 16px; margin-bottom: 30px; line-height: 1.6;">
                Thank you for registering with Border Buyers! To complete your registration and secure your account, 
                please verify your email address by clicking the button below:
            </p>

            <!-- Verify Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $verificationUrl }}" 
                   style="background-color: #2563eb; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 18px; font-weight: 600; display: inline-block;">
                    <i class="fas fa-check-circle"></i> Verify Email Address
                </a>
            </div>

            <!-- Alternative Link -->
            <div style="text-align: center; margin: 20px 0;">
                <p style="font-size: 14px; color: #666; margin: 0;">
                    If the button above doesn't work, copy and paste the following link into your browser:
                </p>
                <p style="font-size: 12px; color: #2563eb; word-break: break-all; margin: 10px 0;">
                    {{ $verificationUrl }}
                </p>
            </div>

            <!-- Security Notice -->
            <div style="background-color: #f3f4f6; border-left: 4px solid #2563eb; padding: 20px; margin: 30px 0;">
                <h3 style="color: #2563eb; margin: 0 0 10px 0; font-size: 16px;">
                    <i class="fas fa-shield-alt"></i> Security Information
                </h3>
                <ul style="margin: 10px 0; padding-left: 20px; font-size: 14px; color: #666;">
                    <li>This verification link will expire in 24 hours</li>
                    <li>If you didn't create this account, you can safely ignore this email</li>
                    <li>Never share your verification link with anyone</li>
                    <li>Our team will never ask for your password or verification code</li>
                </ul>
            </div>

            <!-- Account Benefits -->
            <div style="margin: 30px 0;">
                <h3 style="color: #333; margin: 0 0 15px 0; font-size: 16px;">
                    <i class="fas fa-star"></i> What You Can Do After Verification:
                </h3>
                <ul style="margin: 10px 0; padding-left: 20px; font-size: 14px; color: #666;">
                    <li>Create and manage listings for cross-border transactions</li>
                    <li>Connect with verified agents and buyers</li>
                    <li>Use our AI-powered risk assessment tools</li>
                    <li>Access secure escrow services</li>
                    <li>Receive transaction monitoring alerts</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f9fafb; padding: 30px 20px; text-align: center; border-top: 1px solid #e5e7eb;">
            <p style="font-size: 14px; color: #666; margin: 0 0 10px 0;">
                Need help? Contact our support team
            </p>
            <p style="font-size: 14px; color: #666; margin: 0 0 20px 0;">
                <a href="mailto:support@borderbuyers.com" style="color: #2563eb; text-decoration: none;">support@borderbuyers.com</a>
            </p>
            
            <div style="margin: 20px 0;">
                <a href="#" style="color: #666; text-decoration: none; margin: 0 10px;">
                    <i class="fab fa-facebook fa-lg"></i>
                </a>
                <a href="#" style="color: #666; text-decoration: none; margin: 0 10px;">
                    <i class="fab fa-twitter fa-lg"></i>
                </a>
                <a href="#" style="color: #666; text-decoration: none; margin: 0 10px;">
                    <i class="fab fa-linkedin fa-lg"></i>
                </a>
                <a href="#" style="color: #666; text-decoration: none; margin: 0 10px;">
                    <i class="fab fa-instagram fa-lg"></i>
                </a>
            </div>
            
            <p style="font-size: 12px; color: #999; margin: 20px 0 0 0;">
                © {{ date('Y') }} Border Buyers. All rights reserved.<br>
                {{ config('app.url') }}
            </p>
            
            <p style="font-size: 12px; color: #999; margin: 10px 0 0 0;">
                This email was sent to {{ $user->email }}.<br>
                If you believe this was sent in error, please contact us.
            </p>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>