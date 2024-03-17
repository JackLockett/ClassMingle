<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>FAQ</title>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   </head>
   <body>
      <!-- Navbar -->
      @include('layouts.navbar')
      <div class="container mt-3">
         <div class="row justify-content-center">
            <div class="col-md-10">
               <h3 class="text-center">Class Mingle FAQ</h3>
               <br>
               <!-- FAQ Section -->
               <div class="accordion" id="faqAccordion">
                  <!-- FAQ Item 1 -->
                  <div class="card">
                     <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                           <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                           What is Class Mingle?
                           </button>
                        </h2>
                     </div>
                     <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#faqAccordion">
                        <div class="card-body">
                           Class Mingle is a social media web application designed for university students. It focuses on student-formed societies, providing forums, user profiles, and communities for meaningful discussion and information sharing.
                        </div>
                     </div>
                  </div>
                  <!-- FAQ Item 2 -->
                  <div class="card">
                     <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                           <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                           How can I join a society on Class Mingle?
                           </button>
                        </h2>
                     </div>
                     <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqAccordion">
                        <div class="card-body">
                           To join a society on Class Mingle, simply create your own profile and then you can browse and join various societies based on your interests and academics.
                        </div>
                     </div>
                  </div>
                  <!-- FAQ Item 3 -->
                  <div class="card">
                     <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                           <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                           What features does Class Mingle offer?
                           </button>
                        </h2>
                     </div>
                     <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#faqAccordion">
                        <div class="card-body">
                           Class Mingle offers user profiles, forums, posts, societies, event support, rich media sharing, gamification features, and moderation tools to facilitate communication, information sharing, and community building.
                        </div>
                     </div>
                  </div>
                  <!-- FAQ Item 4 -->
                  <div class="card">
                     <div class="card-header" id="headingFour">
                        <h2 class="mb-0">
                           <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                           How do I post on the forums?
                           </button>
                        </h2>
                     </div>
                     <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#faqAccordion">
                        <div class="card-body">
                           To post on the forums, simply navigate to the societies section, join a society then click on the "Create Post" button, and then write your post. You can also reply to existing posts by clicking on the "Reply" button.
                        </div>
                     </div>
                  </div>
                  <!-- FAQ Item 5 -->
                  <div class="card">
                     <div class="card-header" id="headingFive">
                        <h2 class="mb-0">
                           <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                           Can I organize events through Class Mingle?
                           </button>
                        </h2>
                     </div>
                     <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#faqAccordion">
                        <div class="card-body">
                           Yes, Class Mingle supports event organization. You can create and manage events for your educational or social groups, and other users can find and participate in these events through the platform.
                        </div>
                     </div>
                  </div>
                  <!-- FAQ Item 6 -->
                  <div class="card">
                     <div class="card-header" id="headingSix">
                        <h2 class="mb-0">
                           <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                           How can I customize my user profile?
                           </button>
                        </h2>
                     </div>
                     <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#faqAccordion">
                        <div class="card-body">
                           You can customize your user profile by adding information about yourself, uploading a profile picture, showcasing your achievements, involvement in societies, and contributions to discussions.
                        </div>
                     </div>
                  </div>
                  <!-- FAQ Item 7 -->
                  <div class="card">
                     <div class="card-header" id="headingSeven">
                        <h2 class="mb-0">
                           <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                           How can I report inappropriate content?
                           </button>
                        </h2>
                     </div>
                     <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#faqAccordion">
                        <div class="card-body">
                           If you encounter inappropriate content, you can report it by clicking on the "Report" button next to the content. Our moderation team will review the report and take appropriate action.
                        </div>
                     </div>
                  </div>
                  <!-- FAQ Item 8 -->
                  <div class="card">
                     <div class="card-header" id="headingEight">
                        <h2 class="mb-0">
                           <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                           Is Class Mingle available on mobile devices?
                           </button>
                        </h2>
                     </div>
                     <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#faqAccordion">
                        <div class="card-body">
                           Yes, Class Mingle is optimized for mobile access. You can use the platform seamlessly on your smartphone or tablet by accessing it through your web browser.
                        </div>
                     </div>
                  </div>
                  <!-- FAQ Item 9 -->
                  <div class="card">
                     <div class="card-header" id="headingTen">
                        <h2 class="mb-0">
                           <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                           How can I earn badges on Class Mingle?
                           </button>
                        </h2>
                     </div>
                     <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#faqAccordion">
                        <div class="card-body">
                           You can earn badges on Class Mingle by joining communities, leaving comments, making posts, and actively engaging with the platform. The more you contribute, the more badges you'll earn.
                        </div>
                     </div>
                  </div>
                  <!-- Additional FAQ Items can be added here -->
               </div>
            </div>
         </div>
      </div>
      <!-- Footer -->
      @include('layouts.footer')
   </body>
</html>