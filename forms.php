<?php
class user_forms{
    public function signup_form(){
        ?>
        <h2>signup_form</h2>
        <form action='submit_signup.php' method='post'>
            <label for='username'>Username:</label>
            <imput type='text' id='username' name='username' required><br>
            <label for=émail'>Email:</label>
            <input type='email' id='email' name='email' required><br>
            <label for='password'>Password:</label>
            <input type='password' id='password' name='password' required><br>
            <input type='submit' value='Sign Up'>
            </form>
        <?php
    }
}