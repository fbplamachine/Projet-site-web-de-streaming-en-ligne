<?php
    session_start();
    $conn = new mysqli('localhost', 'root', 'fbplamachine', 'streaming');

    //register
    if (isset($_POST['register'])) {
        /*$file = $_FILES['picture']['name'];
        $file_loc = $_FILES['picture']['tmp_name'];
        $folder="images/";
        $new_file_name = strtolower($file);
        $final_file=str_replace(' ','-',$new_file_name);*/

        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $picture = $conn->real_escape_string($_POST['picture']);
        $password = $conn->real_escape_string($_POST['password']);

        /*if(move_uploaded_file($file_loc,$folder.$final_file))
        {
            $image=$final_file;
        }*/

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sql = $conn->query("SELECT id FROM users WHERE email='$email'");
            if ($sql->num_rows > 0){
                /*echo 'failedUserExists';
                header('location: index.php');*/
                echo '<script type="text/javascript">alert("failedUserExists");</script>';
                //exit();
            }

            else {
                $ePassword = password_hash($password, PASSWORD_BCRYPT);
                $conn->query("INSERT INTO users (name,email,picture,password,createdOn) VALUES ('$name', '$email', '$picture' ,'$ePassword', NOW())");

                $sql = $conn->query("SELECT id FROM users ORDER BY id DESC LIMIT 1");
                $data = $sql->fetch_assoc();

                $_SESSION['loggedIn'] = 1;
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['picture'] = $picture;
                $_SESSION['userID'] = $data['id'];

                exit('success');
            }
        } else
            exit('failedEmail');
    }
    //end of register

//login

$loggedIn = false;

    if (isset($_SESSION['loggedIn']) && isset($_SESSION['name'])) {
        $loggedIn = true;
    }
    if (isset($_POST['logIn'])) {
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sql = $conn->query("SELECT id, password, name FROM users WHERE email='$email'");
            if ($sql->num_rows == 0)
                exit('failed');
            else {
                $data = $sql->fetch_assoc();
                $passwordHash = $data['password'];

                if (password_verify($password, $passwordHash)) {
                    $_SESSION['loggedIn'] = 1;
                    $_SESSION['name'] = $data['name'];
                    $_SESSION['email'] = $email;
                    $_SESSION['userID'] = $data['id'];

                    exit('success');
                } else
                    exit('failed');
            }
        } else
            exit('failed');
    }
    //end of login
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script type="text/javascript">

        function validate()
        {
            var extensions = new Array("jpg","jpeg");
            var image_file = document.regform.image.value;
            var image_length = document.regform.image.value.length;
            var pos = image_file.lastIndexOf('.') + 1;
            var ext = image_file.substring(pos, image_length);
            var final_ext = ext.toLowerCase();
            for (i = 0; i < extensions.length; i++)
            {
                if(extensions[i] == final_ext)
                {
                    return true;
                }
            }
            alert("Image Extension Not Valid (Use Jpg,jpeg)");
            return false;
        }

    </script>

    <!-- Website Title -->
    <title>XYZ - Web Application</title>
    
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/fontawesome-all.css" rel="stylesheet">
    <link href="css/swiper.css" rel="stylesheet">
	<link href="css/magnific-popup.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	
	<!-- Favicon  -->
    <link rel="icon" href="images/logo.jpg">
</head>
<body data-spy="scroll" data-target=".fixed-top">
    
    <!-- Preloader -->
	<div class="spinner-wrapper">
        <div class="spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
    <!-- end of preloader -->
    

    <!-- Navigation -->
    <nav class="navbar navbar-expand-md navbar-dark navbar-custom fixed-top">
        <!-- Text Logo - Use this if you don't have a graphic logo -->
        <!-- <a class="navbar-brand logo-text page-scroll" href="index.php">Leno</a> -->

        <!-- Image Logo -->
        <a class="navbar-brand logo-image" href="index.php"><img src="images/logo.jpg" alt="alternative"></a>
        
        <!-- Mobile Menu Toggle Button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-awesome fas fa-bars"></span>
            <span class="navbar-toggler-awesome fas fa-times"></span>
        </button>
        <!-- end of mobile menu toggle button -->

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link page-scroll" href="#header">ACCUEIL <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link page-scroll" href="#features">XYZ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link page-scroll" href="#preview">APERCU</a>
                </li>

                <!-- Dropdown Menu -->          
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle page-scroll" href="#details" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">VOIR</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#"><span class="item-text">FILMS</span></a>
                        <div class="dropdown-items-divide-hr"></div>
                        <a class="dropdown-item" href="#"><span class="item-text">SERIES</span></a>
                    </div>
                </li>
                <!-- end of dropdown menu -->

                <li class="nav-item">
                    <a class="nav-link page-scroll" href="#contact">CONTACT</a>
                </li>
            </ul>
            <span class="nav-item social-icons">
                <span class="fa-stack">
                    <a href="#your-link">
                        <i class="fas fa-circle fa-stack-2x"></i>
                        <i class="fab fa-facebook-f fa-stack-1x"></i>
                    </a>
                </span>
                <span class="fa-stack">
                    <a href="#your-link">
                        <i class="fas fa-circle fa-stack-2x"></i>
                        <i class="fab fa-twitter fa-stack-1x"></i>
                    </a>
                </span>
                <span class="fa-stack">
                    <a href="#your-link">
                        <i class="fas fa-circle fa-stack-2x"></i>
                        <i class="fab fa-user fa-stack-1x"></i>
                    </a>
                </span>
            </span>
        </div>
    </nav> <!-- end of navbar -->
    <!-- end of navigation -->


    <!-- Header -->
    <header id="header" class="header">
        <div class="header-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="text-container">
                            <h1>APPLICATION MOBILE <br>POUR <span id="js-rotating">VISIONNAGE, STREAMING</span></h1>
                            <p class="p-large">XYZ est une application web de streaming (diffusion continu des vidéos) permettant aux abonnés de suivre de façon continu leurs Films, Séries... Télécharger maintenant!</p>
                            <a class="btn-solid-lg page-scroll" href="#your-link"><i class="fab fa-apple"></i>APP STORE</a>
                            <a class="btn-solid-lg page-scroll" href="#your-link"><i class="fab fa-google-play"></i>PLAY STORE</a>
                        </div>
                    </div> <!-- end of col -->
                    <div class="col-lg-6">
                        <div class="image-container">
                            <img class="img-fluid" src="images/bg-header2.png" alt="alternative">
                        </div> <!-- end of image-container -->
                    </div> <!-- end of col -->
                </div> <!-- end of row -->
            </div> <!-- end of container -->
        </div> <!-- end of header-content -->
    </header> <!-- end of header -->
    <!-- end of header -->


    <!-- Testimonials -->
    <div class="slider-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <!-- Card Slider -->
                    <div class="slider-container">
                        <div class="swiper-container card-slider">
                            <div class="swiper-wrapper">
                                
                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="card">
                                        <img class="card-image" src="images/mbella.jpg" alt="alternative">
                                        <div class="card-body">
                                            <p class="testimonial-text">Chef de projet, graphiste, développeur web front-office.</p>
                                            <p class="testimonial-author">Mbella Dominique. Chef de groupe</p>
                                        </div>
                                    </div>
                                </div> <!-- end of swiper-slide -->
                                <!-- end of slide -->
        
                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="card">
                                        <img class="card-image" src="images/fbplamachine.jpg" alt="alternative">
                                        <div class="card-body">
                                            <p class="testimonial-text">Développeur back-office, intégrateur et gestionnaire d'équipe.</p>
                                            <p class="testimonial-author">Fouoka Stephane - Jocker Developpeur</p>
                                        </div>
                                    </div>        
                                </div> <!-- end of swiper-slide -->
                                <!-- end of slide -->
        
                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="card">
                                        <img class="card-image" src="images/mbianda.jpg" alt="alternative">
                                        <div class="card-body">
                                            <p class="testimonial-text">Expert en SGBD, gestionnaire des information.</p>
                                            <p class="testimonial-author">Manuellan Mbianda - Gestionnaire BD</p>
                                        </div>
                                    </div>        
                                </div> <!-- end of swiper-slide -->
                                <!-- end of slide -->
        
                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="card">
                                        <img class="card-image" src="images/tchinda.jpg" alt="alternative">
                                        <div class="card-body">
                                            <p class="testimonial-text">Expert en images, sons et vidéo, gestionnaire des médias.</p>
                                            <p class="testimonial-author">Tchinda Boris - Infographiste</p>
                                        </div>
                                    </div>
                                </div> <!-- end of swiper-slide -->
                                <!-- end of slide -->

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="card">
                                        <img class="card-image" src="images/love.jpg" alt="alternative">
                                        <div class="card-body">
                                            <p class="testimonial-text">Expert en images, sons et vidéo, gestionnaire des médias.</p>
                                            <p class="testimonial-author">Bapeck Love - Infographiste</p>
                                        </div>
                                    </div>
                                </div> <!-- end of swiper-slide -->
                                <!-- end of slide -->

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="card">
                                        <img class="card-image" src="images/mefire.jpg" alt="alternative">
                                        <div class="card-body">
                                            <p class="testimonial-text">Expert en images, sons et vidéo, gestionnaire des médias.</p>
                                            <p class="testimonial-author">Mefire  - Infographiste</p>
                                        </div>
                                    </div>
                                </div> <!-- end of swiper-slide -->
                                <!-- end of slide -->
                            
                            </div> <!-- end of swiper-wrapper -->
        
                            <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <!-- end of add arrows -->
        
                        </div> <!-- end of swiper-container -->
                    </div> <!-- end of slider-container -->
                    <!-- end of card slider -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of slider-1 -->
    <!-- end of testimonials -->
    

    <!-- Features -->
    <div id="features" class="tabs">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>XYZ</h2>
                    <div class="p-heading p-large">XYZ a été conçu à partir des informations fournies par les utilisateurs des autres applications de streaming, les acteurs et producteurs, de sorte à palier aux différentes failles que présentait ses derniers.</div>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
            <div class="row">

                <!-- Tabs Links -->
                <ul class="nav nav-tabs" id="lenoTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="nav-tab-1" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true"><i class="fas fa-cog"></i>CONNEXION</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-tab-2" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false"><i class="fas fa-binoculars"></i>ABONNEMENT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-tab-3" data-toggle="tab" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false"><i class="fas fa-search"></i>VISIONNAGE</a>
                    </li>
                </ul>
                <!-- end of tabs links -->


                <!-- Tabs Content-->
                <div class="tab-content" id="lenoTabsContent">
                    
                    <!-- Tab -->
                    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1">
                        <div class="container">
                            <div class="row">
                                
                                <!-- Icon Cards Pane -->
                                <div class="col-lg-4">
                                    <div class="card left-pane first">
                                        <div class="card-body">
                                            <div class="text-wrapper">
                                                <h4 class="card-title">S'inscrire</h4>
                                                <p>La première étape consiste à s'inscire pour avoir un compte au sein de la plate-forme</p>
                                            </div>
                                            <div class="card-icon">
                                                <i class="far fa-compass"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card left-pane">
                                        <div class="card-body">
                                            <div class="text-wrapper">
                                                <h4 class="card-title">Valider Compte</h4>
                                                <p>Après remplissage du formulaire et envoi des données il sera question de valider votre compte via l'email reçu dans votre boite.</p>
                                            </div>
                                            <div class="card-icon">
                                                <i class="far fa-file-code"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end of icon cards pane -->

                                <!-- Image Pane -->
                                <div class="col-lg-4">
                                    <img class="img-fluid" src="images/connexion.jpg" alt="alternative">
                                </div>
                                <!-- end of image pane -->
                                
                                <!-- Icon Cards Pane -->
                                <div class="col-lg-4">
                                    <div class="card right-pane first">
                                        <div class="card-body">
                                            <div class="card-icon">
                                                <i class="far fa-calendar-check"></i>
                                            </div>
                                            <div class="text-wrapper">
                                                <h4 class="card-title">Se Connecter</h4>
                                                <p>Le compte déjà valider, il est pret à l'utilisation, il reste qu'a vous connecter.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card right-pane">
                                        <div class="card-body">
                                            <div class="card-icon">
                                                <i class="far fa-bookmark"></i>
                                            </div>
                                            <div class="text-wrapper">
                                                <h4 class="card-title">Profiter</h4>
                                                <p>Félication vous avez déjà un compte et vous êtes connecter. Il vous reste qu'à profiter des merveilles que
                                                    vous propose XYZ</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end of icon cards pane -->

                            </div> <!-- end of row -->
                        </div> <!-- end of container -->
                    </div> <!-- end of tab-pane -->
                    <!-- end of tab -->

                    <!-- Tab -->
                    <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="tab-2">
                        <div class="container">
                            <div class="row">

                                <!-- Image Pane -->
                                <div class="col-md-4">
                                    <img class="img-fluid" src="images/abonne1.jpg" alt="alternative">
                                </div>
                                <!-- end of image pane -->
                                
                                <!-- Text And Icon Cards Area -->
                                <div class="col-md-8">
                                    <div class="text-area">
                                        <h3>Abonnez Vous</h3>
                                        <p>Pour profiter de plus de contenu sur XYZ vous devez avoir un compte et un abonnement absolument afin d'avoir accès à toutes les fonctionnalités et ce que XYZ propose comme service. <a class="turquoise" href="#your-link">plus de details</a></p>
                                    </div> <!-- end of text-area -->
                                    
                                    <div class="icon-cards-area">
                                            <div class="card">
                                                <div class="card-icon">
                                                    <i class="fas fa-cube"></i>
                                                </div>
                                                <div class="card-body">
                                                    <h4 class="card-title">Par Orange Money</h4>
                                                    <p>Vous possédez que d'un compte Orange Money? Génial car on a penser à vous. Il vous reste qu'à nous
                                                        faire confiance et vous abonnnez via le service.</p>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-icon">
                                                    <i class="far fa-bookmark"></i>
                                                </div>
                                                <div class="card-body">
                                                    <h4 class="card-title">Par Mobile Money</h4>
                                                    <p>Oh! j'ai plutôt MTN MoMo. T'inquiète on a aussi penser à vous. Il vous reste qu'à nous faire confiance,
                                                        vous abonnez avec votre compte et profiter du contenu.</p>
                                                </div>
                                            </div>
                                    </div> <!-- end of icon cards area -->
                                </div> <!-- end of col-md-8 -->
                                <!-- end of text and icon cards area -->

                            </div> <!-- end of row -->
                        </div> <!-- end of container -->
                    </div> <!-- end of tab-pane -->
                    <!-- end of tab -->

                    <!-- Tab -->
                    <div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="tab-3">
                        <div class="container">
                            <div class="row">

                                <!-- Text And Icon Cards Area -->
                                <div class="col-md-8">
                                    <div class="icon-cards-area">
                                        <div class="card">
                                            <div class="card-icon">
                                                <i class="far fa-calendar-check"></i>
                                            </div>
                                            <div class="card-body">
                                                <h4 class="card-title">Basic</h4>
                                                <p>Avec seulement <font size="5px"> <b>5000 FCFA</b></font> vous avez accès à tous les films, séries et web séries camerounais. Qu'attendez vous? on n'attend que vous.</p>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-icon">
                                                <i class="fas fa-cube"></i>
                                            </div>
                                            <div class="card-body">
                                                <h4 class="card-title">Standard</h4>
                                                <p>Avec <font size="5px"> <b>7000 FCFA</b></font> vous avez accès à tous les films, séries, web séries et comédies africains. Tout le potentiel africain à votre posséssion.</p>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-icon">
                                                <i class="far fa-bookmark"></i>
                                            </div>
                                            <div class="card-body">
                                                <h4 class="card-title">Premium</h4>
                                                <p>Avec <font size="5px"> <b> 10000 FCFA</b></font> XYZ vous appartient. Accès non restaint, sans condition et ilimité sur tout nos produits de XYZ.</p>
                                            </div>
                                        </div>
                                    </div> <!-- end of icon cards area -->
                                    
                                    <div class="text-area">
                                        <h3>Annuler à tout moment</h3>
                                        <p>Vous décidez de ne plus utilisé XYZ, pas de problème. vous pouvez annuler à tout moment. <a class="turquoise" href="#your-link">plus de details</a>.</p>
                                    </div> <!-- end of text-area -->
                                </div> <!-- end of col-md-8 -->
                                <!-- end of text and icon cards area -->

                                <!-- Image Pane -->
                                <div class="col-md-4">
                                    <img class="img-fluid" src="images/bg-header3.png" alt="alternative">
                                </div>
                                <!-- end of image pane -->
                                    
                            </div> <!-- end of row -->
                        </div> <!-- end of container -->
                    </div><!-- end of tab-pane -->
                    <!-- end of tab -->

                </div> <!-- end of tab-content -->
                <!-- end of tabs content -->

            </div> <!-- end of row --> 
        </div> <!-- end of container --> 
    </div> <!-- end of tabs -->
    <!-- end of features -->


    <!-- Video -->
    <div id="preview" class="basic-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>APERCU</h2>
                    <div class="p-heading p-large">Prenez juste 1min de votre temps et observer comment XYZ marche, comment il a été conçu, dans quelle condition
                        et par qui? Ont vous laisse découvrir.</div>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
            <div class="row">
                <div class="col-lg-12">

                    <!-- Video Preview https://www.youtube.com/watch?v=bCJzCjxZyjw&t=11s-->
                    <div class="image-container">
                        <div class="video-wrapper">
                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%209.mp4" data-effect="fadeIn">
                                <img class="img-fluid" src="images/bg-header.png" alt="alternative" width="800px">
                                <span class="video-play-button">
                                    <span></span>
                                </span>
                            </a>
                        </div> <!-- end of video-wrapper -->
                    </div> <!-- end of image-container -->
                    <!-- end of video preview -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of basic-1 -->
    <!-- end of video -->


    <!-- Details 1 -->
    <div id="details" class="basic-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <img class="img-fluid" src="images/connexion.jpg" alt="alternative">
                </div> <!-- end of col -->
                <div class="col-lg-6">
                    <div class="text-container">
                        <h3>Connexion</h3>
                        <p>Vous souhaitez avoir accès aux contenus? Bah c'est facile. Connecter vous juste.</p>
                        <a class="btn-solid-reg popup-with-move-anim" href="#details-lightbox-3">Se Connecter</a>
                    </div> <!-- end of text-container -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of basic-2 -->
    <!-- end of details 1 -->


    <!-- Details 2 -->
    <div class="basic-3">
        <div class="second">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="text-container">
                            <h3>Abonnement</h3>
                            <p> Votre film, série... préféré non terminé? Achéter vous rapidement un abonnement et finissez votre visionnage avant qu'un
                                proche vous spoil.</p>
                            <a class="btn-solid-reg popup-with-move-anim" href="#details-lightbox-2">S'abonner</a>
                        </div> <!-- end of text-container -->
                    </div> <!-- end of col -->
                    <div class="col-lg-6">
                        <img class="img-fluid" src="images/details-2-iphone.png" alt="alternative">
                    </div> <!-- end of col -->
                </div> <!-- end of row -->
            </div> <!-- end of container -->
        </div> <!-- end of second -->
    </div> <!-- end of basic-3 -->    
    <!-- end of details 2 -->


    <!-- Register Lightboxes -->
	<!-- Lightbox -->
	<div id="details-lightbox-1" class="lightbox-basic zoom-anim-dialog mfp-hide">
		<div class="row">
			<button title="Close (Esc)" type="button" class="mfp-close x-button">×</button>
			<div class="col-lg-6">
				<img class="img-fluid" src="images/details-lightbox-1.png" alt="alternative">
			</div>
			<div class="col-lg-6">
                <!-- Connexion Form -->
                <form id="connectForm" data-toggle="validator" data-focus="false" action="index.php" method="post" onSubmit="return validate();">
                    <div class="form-group">
                        <input type="text" class="form-control-input" id="connectname" name="name" required>
                        <label class="label-control" for="connectname">Nom Complet</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control-input" id="connectemail" name="email" required>
                        <label class="label-control" for="connectemail">Email</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="file" class="form-control-input" id="picture" name="picture" required>
                        <label class="label-control" for="picture">Photo Profile</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control-input" id="connectpassword" name="password" required>
                        <label class="label-control" for="connectpassword">Mot de passe</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group checkbox">
                        J'ai déjà un compte <a href="#details-lightbox-3"> Connexion</a>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="form-control-submit-button" name="register">S'INSCRIRE</button>
                    </div>
                    <div class="form-message">
                        <div id="connectSubmit" class="h3 text-center hidden"></div>
                    </div>
                </form>
                <!-- end of connexion form -->
				<a class="btn-outline-reg mfp-close as-button" href="#details">BACK</a>
			</div>
		</div> <!-- end of row -->
    </div> <!-- end of lightbox-basic -->
    <!-- end of lightbox -->
    
    <!-- Abonnement Lightbox -->
    <div id="details-lightbox-2" class="lightbox-basic zoom-anim-dialog mfp-hide">
		<div class="row">
			<button title="Close (Esc)" type="button" class="mfp-close x-button">×</button>
			<div class="col-lg-6">
				<img class="img-fluid" src="images/details-lightbox-2.png" alt="alternative">
			</div>
			<div class="col-lg-6">
                <!-- Abonnement Form -->
                <form id="abonneForm" data-toggle="validator" data-focus="false">
                    <div class="form-group">
                        <input type="text" class="form-control-input" id="abonnename" required>
                        <label class="label-control" for="abonnename">Nom Complet</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control-input" id="abonneemail" required>
                        <label class="label-control" for="abonneemail">Email</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control-input" id="abonnepassword" required>
                        <label class="label-control" for="abonnepassword">Mot de passe</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group checkbox">
                        J'ai déjà un abonnement <a href="#"> Connexion</a>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="form-control-submit-button">S'INSCRIRE</button>
                    </div>
                    <div class="form-message">
                        <div id="abonneSubmit" class="h3 text-center hidden"></div>
                    </div>
                </form>
                <!-- end of Abonne form -->
				<!--<a class="btn-solid-reg" href="#your-link">DETAILS</a>--> <a class="btn-outline-reg mfp-close as-button" href="#details">BACK</a>
			</div>
		</div> <!-- end of row -->
    </div> <!-- end of lightbox-basic -->
    <!-- end of lightbox -->
    <!-- end of details lightboxes -->


    <!-- Login Lightboxes -->
    <!-- Lightbox -->
    <div id="details-lightbox-3" class="lightbox-basic zoom-anim-dialog mfp-hide">
        <div class="row">
            <button title="Close (Esc)" type="button" class="mfp-close x-button">×</button>
            <div class="col-lg-6">
                <img class="img-fluid" src="images/details-lightbox-1.png" alt="alternative">
            </div>
            <div class="col-lg-6">
                <!-- Connexion Form -->
                <form id="connectForm" data-toggle="validator" data-focus="false"  onSubmit="return validate();" method="post" action="index.php">
                    <div class="form-group">
                        <input type="email" class="form-control-input" id="connectemail" required name="email">
                        <label class="label-control" for="connectemail">Email</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control-input" id="connectpassword" required name="password">
                        <label class="label-control" for="connectpassword">Mot de passe</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group checkbox">
                        Je suis nouveau <a href="#details-lightbox-1"> S'inscrire</a>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="form-control-submit-button">SE CONNECTER</button>
                    </div>
                    <div class="form-message">
                        <div id="connectSubmit" class="h3 text-center hidden"></div>
                    </div>
                </form>
                <!-- end of connexion form -->
                <a class="btn-outline-reg mfp-close as-button" href="#details">BACK</a>
            </div>
        </div> <!-- end of row -->
    </div> <!-- end of lightbox-basic -->
    <!-- end of lightbox -->


    <!-- Trailer -->
    <div class="slider-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    
                    <!-- Série Slider -->
                    <div class="slider-container">
                        <div class="swiper-container image-slider">
                            <h1><font color="#00C9DB"><i>SERIES</i></font></h1>
                            <div class="swiper-wrapper">
                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-container">
                                        <div class="video-wrapper">
                                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%201.mp4" data-effect="fadeIn">
                                                <img class="img-fluid" src="images/flyer3.jpg" alt="alternative" width="800px">
                                                <span class="video-play-button">
                                                <span></span>
                                                </span>
                                            </a>
                                        </div> <!-- end of video-wrapper -->
                                    </div>
                                </div>
                                <!-- end of slide -->

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-container">
                                        <div class="video-wrapper">
                                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%202.mp4" data-effect="fadeIn">
                                                <img class="img-fluid" src="images/flyer3.jpg" alt="alternative" width="800px">
                                                <span class="video-play-button">
                                                <span></span>
                                                </span>
                                            </a>
                                        </div> <!-- end of video-wrapper -->
                                    </div>
                                </div>
                                <!-- end of slide -->
                                
                                <!-- Slide -->
                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-container">
                                        <div class="video-wrapper">
                                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%203.mp4" data-effect="fadeIn">
                                                <img class="img-fluid" src="images/flyer3.jpg" alt="alternative" width="800px">
                                                <span class="video-play-button">
                                                <span></span>
                                                </span>
                                            </a>
                                        </div> <!-- end of video-wrapper -->
                                    </div>
                                </div>
                                <!-- end of slide -->

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-container">
                                        <div class="video-wrapper">
                                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%204.mp4" data-effect="fadeIn">
                                                <img class="img-fluid" src="images/flyer3.jpg" alt="alternative" width="800px">
                                                <span class="video-play-button">
                                                <span></span>
                                                </span>
                                            </a>
                                        </div> <!-- end of video-wrapper -->
                                    </div>
                                </div>
                                <!-- end of slide -->

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-container">
                                        <div class="video-wrapper">
                                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%205.mp4" data-effect="fadeIn">
                                                <img class="img-fluid" src="images/flyer3.jpg" alt="alternative" width="800px">
                                                <span class="video-play-button">
                                                <span></span>
                                                </span>
                                            </a>
                                        </div> <!-- end of video-wrapper -->
                                    </div>
                                </div>
                                <!-- end of slide -->

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-container">
                                        <div class="video-wrapper">
                                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%206.mp4" data-effect="fadeIn">
                                                <img class="img-fluid" src="images/flyer3.jpg" alt="alternative" width="800px">
                                                <span class="video-play-button">
                                                <span></span>
                                                </span>
                                            </a>
                                        </div> <!-- end of video-wrapper -->
                                    </div>
                                </div>
                                <!-- end of slide -->

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-container">
                                        <div class="video-wrapper">
                                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%207.mp4" data-effect="fadeIn">
                                                <img class="img-fluid" src="images/flyer3.jpg" alt="alternative" width="800px">
                                                <span class="video-play-button">
                                                <span></span>
                                                </span>
                                            </a>
                                        </div> <!-- end of video-wrapper -->
                                    </div>
                                </div>
                                <!-- end of slide -->
                                
                            </div> <!-- end of swiper-wrapper -->

                            <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <!-- end of add arrows -->

                        </div> <!-- end of swiper-container -->
                    </div> <!-- end of slider-container -->
                    <!-- end of série slider -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    <!-- end of slider-2 -->


    <div class="slider-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <!-- Série Slider -->
                    <div class="slider-container">
                        <div class="swiper-container image-slider">
                            <h1><font color="#00C9DB"><i>FILMS</i></font></h1>
                            <div class="swiper-wrapper">
                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-container">
                                        <div class="video-wrapper">
                                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%201.mp4" data-effect="fadeIn">
                                                <img class="img-fluid" src="images/flyer1.jpg" alt="alternative" width="800px">
                                                <span class="video-play-button">
                                                <span></span>
                                                </span>
                                            </a>
                                        </div> <!-- end of video-wrapper -->
                                    </div>
                                </div>
                                <!-- end of slide -->

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-container">
                                        <div class="video-wrapper">
                                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%202.mp4" data-effect="fadeIn">
                                                <img class="img-fluid" src="images/flyer1.jpg" alt="alternative" width="800px">
                                                <span class="video-play-button">
                                                <span></span>
                                                </span>
                                            </a>
                                        </div> <!-- end of video-wrapper -->
                                    </div>
                                </div>
                                <!-- end of slide -->

                                <!-- Slide -->
                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-container">
                                        <div class="video-wrapper">
                                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%203.mp4" data-effect="fadeIn">
                                                <img class="img-fluid" src="images/flyer1.jpg" alt="alternative" width="800px">
                                                <span class="video-play-button">
                                                <span></span>
                                                </span>
                                            </a>
                                        </div> <!-- end of video-wrapper -->
                                    </div>
                                </div>
                                <!-- end of slide -->

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-container">
                                        <div class="video-wrapper">
                                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%204.mp4" data-effect="fadeIn">
                                                <img class="img-fluid" src="images/flyer1.jpg" alt="alternative" width="800px">
                                                <span class="video-play-button">
                                                <span></span>
                                                </span>
                                            </a>
                                        </div> <!-- end of video-wrapper -->
                                    </div>
                                </div>
                                <!-- end of slide -->

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-container">
                                        <div class="video-wrapper">
                                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%205.mp4" data-effect="fadeIn">
                                                <img class="img-fluid" src="images/flyer1.jpg" alt="alternative" width="800px">
                                                <span class="video-play-button">
                                                <span></span>
                                                </span>
                                            </a>
                                        </div> <!-- end of video-wrapper -->
                                    </div>
                                </div>
                                <!-- end of slide -->

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-container">
                                        <div class="video-wrapper">
                                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%206.mp4" data-effect="fadeIn">
                                                <img class="img-fluid" src="images/flyer1.jpg" alt="alternative" width="800px">
                                                <span class="video-play-button">
                                                <span></span>
                                                </span>
                                            </a>
                                        </div> <!-- end of video-wrapper -->
                                    </div>
                                </div>
                                <!-- end of slide -->

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-container">
                                        <div class="video-wrapper">
                                            <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%207.mp4" data-effect="fadeIn">
                                                <img class="img-fluid" src="images/flyer1.jpg" alt="alternative" width="800px">
                                                <span class="video-play-button">
                                                <span></span>
                                                </span>
                                            </a>
                                        </div> <!-- end of video-wrapper -->
                                    </div>
                                </div>
                                <!-- end of slide -->

                            </div> <!-- end of swiper-wrapper -->

                            <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <!-- end of add arrows -->

                        </div> <!-- end of swiper-container -->
                    </div> <!-- end of slider-container -->
                    <!-- end of série slider -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    <!-- end of slider-2 -->


        <div class="slider-2">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">

                        <!-- Série Slider -->
                        <div class="slider-container">
                            <div class="swiper-container image-slider">
                                <h1><font color="#00C9DB"><i>D'ACTUALITES</i></font></h1>
                                <div class="swiper-wrapper">
                                    <!-- Slide -->
                                    <div class="swiper-slide">
                                        <div class="image-container">
                                            <div class="video-wrapper">
                                                <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%201.mp4" data-effect="fadeIn">
                                                    <img class="img-fluid" src="images/flyer4.jpg" alt="alternative" width="800px">
                                                    <span class="video-play-button">
                                                <span></span>
                                                </span>
                                                </a>
                                            </div> <!-- end of video-wrapper -->
                                        </div>
                                    </div>
                                    <!-- end of slide -->

                                    <!-- Slide -->
                                    <div class="swiper-slide">
                                        <div class="image-container">
                                            <div class="video-wrapper">
                                                <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%202.mp4" data-effect="fadeIn">
                                                    <img class="img-fluid" src="images/flyer4.jpg" alt="alternative" width="800px">
                                                    <span class="video-play-button">
                                                <span></span>
                                                </span>
                                                </a>
                                            </div> <!-- end of video-wrapper -->
                                        </div>
                                    </div>
                                    <!-- end of slide -->

                                    <!-- Slide -->
                                    <!-- Slide -->
                                    <div class="swiper-slide">
                                        <div class="image-container">
                                            <div class="video-wrapper">
                                                <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%203.mp4" data-effect="fadeIn">
                                                    <img class="img-fluid" src="images/flyer4.jpg" alt="alternative" width="800px">
                                                    <span class="video-play-button">
                                                <span></span>
                                                </span>
                                                </a>
                                            </div> <!-- end of video-wrapper -->
                                        </div>
                                    </div>
                                    <!-- end of slide -->

                                    <!-- Slide -->
                                    <div class="swiper-slide">
                                        <div class="image-container">
                                            <div class="video-wrapper">
                                                <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%204.mp4" data-effect="fadeIn">
                                                    <img class="img-fluid" src="images/flyer4.jpg" alt="alternative" width="800px">
                                                    <span class="video-play-button">
                                                <span></span>
                                                </span>
                                                </a>
                                            </div> <!-- end of video-wrapper -->
                                        </div>
                                    </div>
                                    <!-- end of slide -->

                                    <!-- Slide -->
                                    <div class="swiper-slide">
                                        <div class="image-container">
                                            <div class="video-wrapper">
                                                <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%205.mp4" data-effect="fadeIn">
                                                    <img class="img-fluid" src="images/flyer4.jpg" alt="alternative" width="800px">
                                                    <span class="video-play-button">
                                                <span></span>
                                                </span>
                                                </a>
                                            </div> <!-- end of video-wrapper -->
                                        </div>
                                    </div>
                                    <!-- end of slide -->

                                    <!-- Slide -->
                                    <div class="swiper-slide">
                                        <div class="image-container">
                                            <div class="video-wrapper">
                                                <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%206.mp4" data-effect="fadeIn">
                                                    <img class="img-fluid" src="images/flyer4.jpg" alt="alternative" width="800px">
                                                    <span class="video-play-button">
                                                <span></span>
                                                </span>
                                                </a>
                                            </div> <!-- end of video-wrapper -->
                                        </div>
                                    </div>
                                    <!-- end of slide -->

                                    <!-- Slide -->
                                    <div class="swiper-slide">
                                        <div class="image-container">
                                            <div class="video-wrapper">
                                                <a class="popup-youtube" href="vidéo/TokyoGhoul/Tokyo%20ghoul%20épisode%207.mp4" data-effect="fadeIn">
                                                    <img class="img-fluid" src="images/flyer4.jpg" alt="alternative" width="800px">
                                                    <span class="video-play-button">
                                                <span></span>
                                                </span>
                                                </a>
                                            </div> <!-- end of video-wrapper -->
                                        </div>
                                    </div>
                                    <!-- end of slide -->

                                </div> <!-- end of swiper-wrapper -->

                                <!-- Add Arrows -->
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                                <!-- end of add arrows -->

                            </div> <!-- end of swiper-container -->
                        </div> <!-- end of slider-container -->
                        <!-- end of série slider -->

                    </div> <!-- end of col -->
                </div> <!-- end of row -->
            </div> <!-- end of container -->
        </div> <!-- end of slider-2 -->
    <!-- end of Trailer -->


    <!-- Download -->
    <div class="basic-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-5">
                    <div class="text-container">
                        <h2>Télécharger <span class="blue">XYZ</span></h2>
                        <p class="p-large">XYZ est une application web de streaming (diffusion continu des vidéos) permettant aux abonnés de suivre de façon continu leurs Films, Séries...
                            Disponible sur Mobile, Télécharger maintenant!</p>
                        <a class="btn-solid-lg" href="#your-link"><i class="fab fa-apple"></i>APP STORE</a>
                        <a class="btn-solid-lg" href="#your-link"><i class="fab fa-google-play"></i>PLAY STORE</a>
                    </div> <!-- end of text-container -->
                </div> <!-- end of col -->
                <div class="col-lg-6 col-xl-7">
                    <div class="image-container">
                        <img class="img-fluid" src="images/triple3.png" alt="alternative">
                    </div> <!-- end of img-container -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of basic-4 -->
    <!-- end of download -->


    <!-- Statistics -->
    <div class="counter">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    
                    <!-- Counter -->
                    <div id="counter">
                        <div class="cell">
                            <div class="counter-value number-count" data-count="231">1</div>
                            <p class="counter-info">Appréciations</p>
                        </div>
                        <div class="cell">
                            <div class="counter-value number-count" data-count="85">1</div>
                            <p class="counter-info">Problèmes Résolus</p>
                        </div>
                        <div class="cell">
                            <div class="counter-value number-count" data-count="59">1</div>
                            <p class="counter-info">Bonnes Critiques</p>
                        </div>
                        <div class="cell">
                            <div class="counter-value number-count" data-count="127">1</div>
                            <p class="counter-info">Remarques</p>
                        </div>
                    </div>
                    <!-- end of counter -->
                    
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of counter -->
    <!-- end of statistics -->


    <!-- Contact -->
    <div id="contact" class="form">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>CONTACT</h2>
                    <ul class="list-unstyled li-space-lg">
                        <li class="address">Vous avez des questions? des suggestions ou critiques? des Remarques? On a penser à un moment génial de nous
                            contacter.</li>
                        <li><i class="fas fa-map-marker-alt"></i>Akwa, Douala, Cameroun</li>
                        <li><i class="fas fa-phone"></i><a class="blue" href="tel:003024630820">+237 694 378 506</a></li>
                        <li><i class="fas fa-envelope"></i><a class="blue" href="mailto:office@leno.com">fbplamachine@stream.com</a></li>
                    </ul>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    
                    <!-- Contact Form -->
                    <form id="contactForm" data-toggle="validator" data-focus="false">
                        <div class="form-group">
                            <input type="text" class="form-control-input" id="cname" required>
                            <label class="label-control" for="cname">Nom Complet</label>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control-input" id="cemail" required>
                            <label class="label-control" for="cemail">Email</label>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control-textarea" id="cmessage" required></textarea>
                            <label class="label-control" for="cmessage">Votre Message</label>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group checkbox">
                            <input type="checkbox" id="cterms" value="Agreed-to-Terms" required>J'ai lus et j'accepte les conditions d'utilisation de XYZ <a href="privacy-policy.html">Privacy Policy</a> and <a href="terms-conditions.html">Terms Conditions</a>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="form-control-submit-button">ENVOYER MESSAGE</button>
                        </div>
                        <div class="form-message">
                            <div id="cmsgSubmit" class="h3 text-center hidden"></div>
                        </div>
                    </form>
                    <!-- end of contact form -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of form -->
    <!-- end of contact -->


    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-col">
                        <h4>Apropos XYZ</h4>
                        <p>Nous sommes passionnés par le cinema en particulier et l'art en générale.</p>
                    </div>
                </div> <!-- end of col -->
                <div class="col-md-4">
                    <div class="footer-col middle">
                        <h4>Liens Important</h4>
                        <ul class="list-unstyled li-space-lg">
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">Nos partenaires <a class="turquoise" href="#your-link">fbplamachine.com</a></div>
                            </li>
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">Lire notre <a class="turquoise" href="terms-conditions.html">Termes & Conditions</a>, <a class="turquoise" href="privacy-policy.html">Politique de confidentialité</a></div>
                            </li>
                        </ul>
                    </div>
                </div> <!-- end of col -->
                <div class="col-md-4">
                    <div class="footer-col last">
                        <h4>Nos Médias Sociaux</h4>
                        <span class="fa-stack">
                            <a href="#your-link">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-facebook-f fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="#your-link">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-twitter fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="#your-link">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-google-plus-g fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="#your-link">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-instagram fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="#your-link">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-linkedin-in fa-stack-1x"></i>
                            </a>
                        </span>
                    </div> 
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of footer -->  
    <!-- end of footer -->


    <!-- Copyright -->
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p class="p-small">Copyright © XYZ - Web Application by <a href="#">fbplamachine</a></p>
                </div> <!-- end of col -->
            </div> <!-- enf of row -->
        </div> <!-- end of container -->
    </div> <!-- end of copyright --> 
    <!-- end of copyright -->
    
    	
    <!-- Scripts -->
    <script src="js/jquery.min.js"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
    <script src="js/popper.min.js"></script> <!-- Popper tooltip library for Bootstrap -->
    <script src="js/bootstrap.min.js"></script> <!-- Bootstrap framework -->
    <script src="js/jquery.easing.min.js"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
    <script src="js/swiper.min.js"></script> <!-- Swiper for image and text sliders -->
    <script src="js/jquery.magnific-popup.js"></script> <!-- Magnific Popup for lightboxes -->
    <script src="js/morphext.min.js"></script> <!-- Morphtext rotating text in the header -->
    <script src="js/validator.min.js"></script> <!-- Validator.js - Bootstrap plugin that validates forms -->
    <script src="js/scripts.js"></script> <!-- Custom scripts -->
</body>
</html>
