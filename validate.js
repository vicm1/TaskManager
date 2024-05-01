//Javascript portion of the midterm, used for client-side validation with loginpage.php, setupusers.php
function validateForm() {
    //Validates the username for both loginpage.php and setupusers.php
    let x = document.forms["validate"]["username"].value;
    //checks if the username is empty, if it is then alert the user and return false
    if (x == "") { 
        alert("Username must be filled out")
        return "false";
    }
    //checks to see that username is longer than 5 or more letters
    else if (x.length < 5){
        alert("Username must be at least 5 characters long")
        return "false";
    }
    //checks to see if username uses appropriate characters
    else if (/[^a-zA-Z0-9_-]/.test(x)){
        alert("Only a-z, A-Z, 0-9, - and _ allowed in Usernames.\n")
        return "false";
    }

    //Validates the password for both loginpage.php and setupusers.php
    let y = document.forms["validate"]["password"].value;
    //checks if the password is empty, if it is then alert the user and return false
    if (y == ""){ 
        alert("No Password was entered.\n")
        return "false";
    }
  
    //Validates the email for both setupusers.php since login doesn't require the email
    let z = document.forms["validate"]["email"].value;
    //checks if the email is empty, if it is then alert the user and return false
    if (z == ""){
        alert("Email must be filled out")
        return "false";
    } 
    //checks to see if email uses appropriate characters that an email uses
    else if (!((z.indexOf(".") > 0) && (z.indexOf("@") > 0)) || /[^a-zA-Z0-9.@_-]/.test(z)){
        alert("Email address is invalid")
        return "false";
    }

    //part of code from javascript to only show the first 3 lines, and when hit expand the whole text will show
    var g = document.getElementById("myDIV");
    if (g.style.display === "none") {
        g.style.display = "block";
    } else {
        g.style.display = "none";
    }
      
    
    return ""
  }

