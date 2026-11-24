<?php
require_once 'config.php';
if($_POST)
{
    // ... logic remains intact
}	
include 'header.php';
?>

<div class="auth-container">
    <div class="auth-box split-layout">
        <div class="auth-image" style="background-image: url('assets/login_bg.png');">
            <h3>Get In Touch</h3>
            <p>Our support team is here to help you navigate your journey on Nexus. Send us a message and we'll reply shortly.</p>
        </div>

        <div class="auth-form-side">
            <h2>📝 Contact Us</h2>
            
            <form action="#" method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject">
                </div>

                <div class="form-group">
                    <label for="yourmessage">Your Message</label>
                    <textarea id="yourmessage" name="yourmessage" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address">
                </div>

                <div class="form-group">
                    <label for="contactnumber">Contact Number</label>
                    <input type="tel" id="contactnumber" name="contactnumber">
                </div>

                <div class="form-group">
                    <label for="messagebox">Additional Message</label>
                    <textarea id="messagebox" name="messagebox" rows="5"></textarea>
                </div>

                <button type="submit" class="btn-submit">Submit</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>