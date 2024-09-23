<?php
/* Template Name: Sign-in */
get_header();
?>


  <main>
    <div class="container">
      <article class="login">

        <div class="page-title flex">
          <h1>LOGIN PAGE</h1>
        </div>
        <div class="flex-box">
          <div class="flex-box__item">
            <!-- HTML FORM  -->
             <?php dynamic_sidebar( 'sign_in_html_form' ) ?>
          </div>
        </div>
      </article>

    </div>
  </main>




<?php
// Include WordPress footer
get_footer();
?>