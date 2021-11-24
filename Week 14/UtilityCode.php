<?php require_once "pdo.php";

session_start();

function flashMessage(){

       if (isset($_SESSION['success'])){
       echo ('<p style= "color:green">'.htmlentities($_SESSION['success'])."</p>\n");
       unset($_SESSION['success']);
       }
   
       if (isset($_SESSION['error'])){
       echo ('<p style= "color:red">'.htmlentities($_SESSION['error'])."</p>\n");
       unset($_SESSION['error']);
       }
}

function validateProfile(){
    if (  (strlen($_POST['first_name']) < 1 ) || (strlen($_POST['last_name']) < 1) || 
            (strlen($_POST['email']) < 1) || (strlen($_POST['headline']) < 1 ) || 
            (strlen($_POST['summary']) < 1)   ){
           return "All fields are required";           

    } 

    if (stripos($_POST['email'],'@') === false){
           return "Email address must contain @";    
    }  

    return true;
}

function validatePosition(){
     for  ($i=1; $i <=9; $i++)  {
           if(!isset($_POST['year'.$i]))  { continue; }
           if(!isset($_POST['desc'.$i]))  { continue; }
           $year = $_POST['year'.$i];
           $desc = $_POST['desc'.$i];
           if ( ( strlen($year) == 0 ) || ( strlen($desc) == 0 ) ) {
                  return 'All fields are required';
           }

           if (! is_numeric($year)){
                  return "Position year must be numeric";
           }
       }
       return true;
}

function validateEducation(){
       for  ($i=1; $i <=9; $i++)  {
             if(!isset($_POST['edu_year'.$i]))  { continue; }
             if(!isset($_POST['edu_school'.$i])) { continue; }
             $instyear = $_POST['edu_year'.$i];
             $inst = $_POST['edu_school'.$i];
             if ( ( strlen($instyear) == 0 ) || ( strlen($inst) == 0) ) {
                    return 'All fields are required';
             }
  
             if (! is_numeric($instyear)){
                    return "Education year must be numeric";
             }
         }
         return true;
  }


function loadProfile($pdo){
       $stmt = $pdo->prepare("SELECT profile_id, user_id, first_name, last_name, email, headline, summary FROM Profile where profile_id = :xyz");
       $stmt->execute(array(":xyz" => $_GET['profile_id']));
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
       return $row;
}

function loadPos($pdo, $profile_id){
       $stmt = $pdo->prepare('SELECT * FROM Position WHERE profile_id = :prof ORDER BY rank');
       $stmt->execute(array(':prof' => $profile_id));
       $positions = array();
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              $positions [] = $row;
       }
       return $positions;
} 
     
function loadEducation($pdo, $profile_id){
       $stmt = $pdo->prepare('SELECT * FROM Education WHERE profile_id = :prof ORDER BY rank');
       $stmt->execute(array(':prof' => $profile_id));
       $education = array();
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $education [] = $row;
       }
       return $education;

}

function getInstitutionName($pdo, $education){
       $names = array();
       foreach($education as $id){
       $stmt = $pdo->prepare('SELECT name FROM Institution WHERE institution_id = :id ');
       $stmt->execute(array( ':id' => $id['institution_id']));
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
              $names [] = $row['name'];
       }
       return $names;
}

