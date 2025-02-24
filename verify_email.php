<?php
include('connection.php');
if(isset($_GET['token']))
{
    $token = $_GET['token'];
    
    $verify_query = "SELECT `token`,`verify_status` from `supplier` WHERE `token` = '$token' limit 1";
    $verify_query_run = mysqli_query($connect, "$verify_query");

    if(mysqli_num_rows($verify_query_run)>0)
    {
        $row = mysqli_fetch_array($verify_query_run);
        if($row['verify_status']== '0')
        {
            $clicked_token = $row['token'];
            $update_query = "UPDATE supplier SET verify_status ='1' WHERE token = '$clicked_token' LIMIT 1";
            $update_query_run = mysqli_query($connect,"$update_query");

            if($update_query_run)
            {
                echo "Verify Successful. Please Refresh the page and Login.";
            }
            else
            {
                echo" <script>alert('Your Account Have Been Verified Successful.')</script>";
                header("Location:login.php");
                exit(0);
            }
        }
        else
        {
            echo" <script>alert('Email already verified. Please Log in')</script>";
            header("Location:login.php");
            exit(0);
        }
    }
    else
    {
        echo" <script>alert('Token not Exitst')</script>";
    header("Location:login.php");
    }
}   
else
{
    echo" <script>alert('wrong')</script>";
    header("Location:login.php");
}

?>