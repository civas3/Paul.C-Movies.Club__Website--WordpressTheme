<?php
/* Template Name: forgot-password */
get_header();
?>  


<main>
    <div class="container">
      <article class="forgot-password">

        <div class="page-title flex">
          <h1>PASSWORD RECOVRY</h1>
        </div>

        <div class="flex-box">
          <div class="flex-box__item">
             <!-- HTML FORM  -->
             <?php dynamic_sidebar( 'forgotpassword_html_form' ) ?>
          </div>
        </div>

      </article>

    </div>
</main>






<?php
// Include WordPress footer
get_footer();
?>