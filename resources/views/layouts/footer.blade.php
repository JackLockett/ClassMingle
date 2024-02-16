<!DOCTYPE html>
<html>
<head>
<style>
/* Sticky footer style */
@font-face {
  font-family: 'Luckiest Guy Pro';
  src: url('{{ asset('fonts/LuckiestGuy-Regular.ttf') }}') format('truetype');
}
@font-face {
  font-family: 'Impress';
  src: url('{{ asset('fonts/Impress.ttf') }}') format('truetype');
}
.footer-sticky {
  font-size: 18px;
  color: white;
  font-family: 'Impress', sans-serif;
  font-size: 20px;
  background-color: #23426d;
  padding: 20px 0;
  text-align: center;
  width: 100%;
  margin-top: auto; /* Push the footer to the bottom */
  border-top: 2px solid #1A3A6B;
}

/* Social links styling */
.social-links {
  margin-top: 10px;
}

.social-links a {
  display: inline-block;
  margin: 0 10px;
  color: white;
  text-decoration: none;
}

.social-links i {
  font-size: 24px;
}

</style>
</head>

<body>
<div class="footer-sticky">
   <div class="container">
      Class Mingle © 2024 All rights reserved.
      <div class="social-links">
         <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
         <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
         <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
      </div>
   </div>
</div>
</body>
</html>

