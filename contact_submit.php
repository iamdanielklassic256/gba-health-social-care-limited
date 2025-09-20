<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject'] ?? 'Contact Form Submission'));
    $msgBody = htmlspecialchars(trim($_POST['message'] ?? ''));

    if (empty($name) || !$email || empty($msgBody)) {
        header("Location: index.php?status=error&msg=" . urlencode("Please fill in all required fields.") . "#contact");
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'gbahealthsocialcareltd@gmail.com';
        $mail->Password   = 'gqqe bcnm ieuo qqrb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('gbahealthsocialcareltd@gmail.com', 'Website Contact Form');
        $mail->addReplyTo($email, $name);
        $mail->addAddress('gbahealthsocialcareltd@gmail.com', 'HR Team');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <div style='background: #4a6fa5; color: white; padding: 20px; text-align: center;'>
                    <h2 style='margin: 0;'>New Contact Form Submission</h2>
                </div>
                <div style='padding: 25px; background: #f9f9f9;'>
                    <p>You have received a new message from your website contact form.</p>
                    <div style='background: white; border-radius: 8px; padding: 20px; margin: 20px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>
                        <p><strong style='color: #4a6fa5;'>From:</strong> $name</p>
                        <p><strong style='color: #4a6fa5;'>Email:</strong> <a href='mailto:$email'>$email</a></p>
                        <p><strong style='color: #4a6fa5;'>Subject:</strong> $subject</p>
                    </div>
                    <div style='background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>
                        <h3 style='color: #4a6fa5; margin-top: 0;'>Message:</h3>
                        <p style='line-height: 1.6;'>" . nl2br($msgBody) . "</p>
                    </div>
                </div>
                <div style='background: #eee; padding: 15px; text-align: center; color: #666; font-size: 14px;'>
                    <p>This email was sent from your website's contact form on " . date('F j, Y, g:i a') . "</p>
                </div>
            </div>
        ";
        $mail->AltBody = "New Contact Form Submission\n\nFrom: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$msgBody\n\nSent on: " . date('F j, Y, g:i a');

        $mail->send();
        header("Location: index.php?status=success&msg=" . urlencode("Your message has been sent successfully! We'll get back to you soon.") . "#contact");
        exit;
    } catch (Exception $e) {
        header("Location: index.php?status=error&msg=" . urlencode("Sorry, there was an error sending your message. Please try again later.") . "#contact");
        exit;
    }
}
?>

<!-- Display message -->
<?php if (!empty($message)) : ?>
    <div id="alertMessage" class="alert alert-info text-center" style="transition: opacity 1s;">
        <?= $message ?>
    </div>
<?php endif; ?>

<!-- JS to fade out alert after 5 seconds -->
<script>
    setTimeout(function() {
        var alert = document.getElementById('alertMessage');
        if (alert) {
            alert.style.opacity = '0';
            setTimeout(function() { alert.style.display = 'none'; }, 1000);
        }
    }, 5000);
</script>
