<?php
/* Template Name: Register */
get_header();
?>  


<main>
    <div class="container">
      <article class="register">

        <div class="page-title flex">
          <h1>MEBERSHIP REGISTRATION</h1>
        </div>
        <div class="flex-box">



          <div class="flex-box__item">
             <!-- HTML FORM  -->
             <?php dynamic_sidebar( 'registration_html_form' ) ?>
          </div>

          <div class="flex-box__item">
            <h3>Membership Benefits:</h3>
            <ol>
              <li>Unlimited movies and TV shows.</li>
              <li>Watch on your laptop, TV, phone or tablet.</li>
              <li>HD + UHD (4K) quality across the board.</li>
              <li>Allows you to watch on 4 devices max at the same time.</li>
              <li>No commercials.</li>
              <li>You can download the content and watch it offline.</li>
              <li>The new content is not available right away..</li>

            </ol>

          </div>


      </article>

    </div>
  </main>





<?php
// Include WordPress footer
get_footer();
?>