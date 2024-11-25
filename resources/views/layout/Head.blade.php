 <base href="{{ url('/') }}/">
 <meta charset="utf-8" />
 <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
 <title>Penentuan Jurusan</title>

 <link rel="stylesheet" type="text/css"
     href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">

 <!-- CSS Files -->
 <link href="assets/css/material-dashboard.css?v=2.2.2" rel="stylesheet" />
 <link rel="stylesheet" href="assets/plugins/parsleyjs/src/parsley.css">
 <!-- CSS Just for demo purpose, don't include it in your project -->
 <link href="assets/demo/demo.css" rel="stylesheet" />

 <style>
     /* Loader */
     .spinner {
         height: 60px;
         width: 60px;
         margin: auto;
         display: flex;
         position: absolute;
         -webkit-animation: rotation .6s infinite linear;
         -moz-animation: rotation .6s infinite linear;
         -o-animation: rotation .6s infinite linear;
         animation: rotation .6s infinite linear;
         border-left: 6px solid rgba(0, 174, 239, .15);
         border-right: 6px solid rgba(0, 174, 239, .15);
         border-bottom: 6px solid rgba(0, 174, 239, .15);
         border-top: 6px solid rgba(0, 174, 239, .8);
         border-radius: 100%;
     }

     @-webkit-keyframes rotation {
         from {
             -webkit-transform: rotate(0deg);
         }

         to {
             -webkit-transform: rotate(359deg);
         }
     }

     @-moz-keyframes rotation {
         from {
             -moz-transform: rotate(0deg);
         }

         to {
             -moz-transform: rotate(359deg);
         }
     }

     @-o-keyframes rotation {
         from {
             -o-transform: rotate(0deg);
         }

         to {
             -o-transform: rotate(359deg);
         }
     }

     @keyframes rotation {
         from {
             transform: rotate(0deg);
         }

         to {
             transform: rotate(359deg);
         }
     }

     #overlay {
         position: absolute;
         display: none;
         top: 0;
         left: 0;
         right: 0;
         bottom: 0;
         background-color: rgba(0, 0, 0, 0.5);
         z-index: 9998;
         cursor: pointer;
     }
 </style>

 @stack('CSS')
