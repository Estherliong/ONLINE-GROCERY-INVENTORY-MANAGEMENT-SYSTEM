<?php

include('connection.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

</head>


<style>
.hbody{
    background-color: #f2f6fc;
    font-family: "poppins", sans-serif;
    margin: 0;
    padding: 0;
    overflow-x:hidden;
}

.icons-size{
    color: #333;
    font-size: 14px;
}
/* .haction{
    position: fixed;
    right: 30px;
    top:20px
} */
.haction .profile{
    border-radius: 60%;
    cursor: pointer;
    height: 40px;
    overflow: hidden;
    position: relative;
    width: 40px;
}
.haction .profile img{
    width: 100%;
    top:0;
    position: absolute;
    object-fit: cover;
    left: 0;
    height: 100%;
    border: 2px solid black;
}
.haction .menu{
    background-color:#FFF;
    box-sizing:0 5px 25px rgba(0,0,0,0.1);
    border-radius: 15px;
    padding: 10px 20px;
    position: absolute;
    right: -5px;
    width: 200px;
    transition: 0.5s;
    top: 120px;
    visibility: hidden;
    opacity: 0;
}
.haction .menu.active{
    opacity: 1;
    top: auto;
    visibility: visible;
    z-index:10;
}
.haction .menu::before{
    background-color:#fff;
    content: '';
    height: 20px;
    position: absolute;
    right: 80px;
    transform:rotate(45deg);
    top:-5px;
    width: 20px;
}
.haction .menu h3{
    color: #555;
    font-size: 16px;
    font-weight: 600;
    line-height: 1.3em;
    padding: 20px 0px;
    text-align: left;
    width: 100%;
}
.haction .menu h3 div{
    color: #818181;
    font-size: 11px;
    font-weight: 400;
}

.haction .menu ul{
    margin:0;
    padding:0;
}

.haction .menu ul li{
    align-items: center;
    border-top:1px solid rgba(0,0,0,0.05);
    display: flex;
    justify-content: left;
    list-style: none;
    padding: 10px 15px;
}
.haction .menu ul li img{
    max-width: 20px;
    margin-right: 10px;
    opacity: 0.5;
    transition:0.5s
}
.haction .menu ul li a{
    display: inline-block;
    color: #555;
    font-size: 14px;
    font-weight: 600;
    padding-left: 15px;
    text-decoration: none;
    text-transform: uppercase;
    transition: 0.5s;
}
.haction .menu ul li:hover img{
    opacity: 1;
}
.haction .menu ul li:hover a{
    color:#ff00ff;
}
    /*--------------Header-----------*/
.header
{
    font-family: 'Poppins', sans-serif;
}

.navbar
{
    display:flex;
    align-items: center;
}

nav
{
    flex: 1;
    text-align: right;
}

nav ul
{
    display: inline;
    list-style: none;
}

nav ul li
{
    display: inline-block;
    margin-right: 20px;
}

a
{
    text-decoration: none;
    color: black;
}

.hcontainer
{
    max-width: 1500px;
    margin: auto;
    padding-left: 25px;
    padding-right: 25px;
}

li .mnav
{
    text-decoration: none;
    color: black;
}

li .mnav:hover {
    color: #024dbc;
}

/* .row
{
    display: flex;
    justify-content: space-around;
} */

.col-2
{
    min-width: 300px;
}
.col-2 img
{
    max-width: 100%;
    height: 500px;
    width: 1120px;
    margin: auto;
}

.menu-icon
{
    width: 28px;
    margin-left: 20px;
    display: none;
}

/*-----------media query for menu--------*/
@media only screen and (max-width: 800px)
{
    nav ul
    {
        position: absolute;
        top:90px;
        left: 0;
        background: #333;
        width: 100%;
        overflow: hidden;
        transition: 0.5s;
    }

    nav ul li
    {
        display: block;
        margin-right: 80px;
        margin-top: 10px;
        margin-bottom: 10px;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    nav ul li a
    {
        color:#fff;
        
    }

    .menu-icon
    {
        display: block;
        cursor: pointer;
    }

}


    </style>

                    

              
</html>