<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>OTP Verification</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <img src="../favlogo.png" alt="Company Logo" width="100" height="auto">
            </td>
        </tr>
    </table>
    <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" align="center" style="background-color: #ffffff; padding: 20px; border-radius: 5px;">
        <tr>
            <td>
                <h1 style="color: #333; font-size: 24px; margin-bottom: 20px;">OTP Verification</h1>
                <p style="font-size: 16px; color: #555;">Dear User,</p>
                <p style="font-size: 16px; color: #555;">Your One-Time Password (OTP) for account verification is:</p>
                <div style="background-color: #f5f5f5; border-radius: 5px; padding: 10px; font-size: 24px; text-align: center; margin: 20px 0;">
                    <strong style="color: #333;"><?php echo $otp; ?></strong>
                </div>
                <p style="font-size: 16px; color: #555;">This OTP is valid for 8 minutes.</p>
                <p style="font-size: 16px; color: #555;">If you didn't request this OTP, please ignore this email.</p>
            </td>
        </tr>
    </table>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <p style="font-size: 14px; color: #777;">&copy; <?php echo date('Y'); ?> Your Company. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
