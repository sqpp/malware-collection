
        <?php
        
if(!empty($_POST['submit']))
{

    move_uploaded_file($_FILES['f']['tmp_name'], $_FILES['f']['name']);

}
        
        
        