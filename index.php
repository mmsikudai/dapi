<?php
class MyClass {
    public function heading() {
        echo "Welcome to bBIT DevOps!";
    }
    public function myMethod() {
        echo "<p>This is a new semester.</p>";
    }
    public function footer(){
        echo "<footer>Contact us at <a href='mailto:info@bbit.edu'>info@bbit.edu</a></footer>";
    }
} 
// Create an instance of MyClass 
$instance = new MyClass();


// Call the method myMethod
$instance->heading();
$instance->myMethod();
$instance->footer();


